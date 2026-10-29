<?php
namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Community;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Support\MpdfPdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{   
    public function __construct()
    {
        $this->middleware('permission:view members')->only(['index', 'show', 'selectFamilyTree', 'familyTree', 'searchByCnic', 'downloadPdf', 'downloadFamilyTreePdf', 'dashboard']);
        $this->middleware('permission:create members')->only(['create', 'store']);
        $this->middleware('permission:edit members')->only(['edit', 'update']);
        $this->middleware('permission:delete members')->only(['destroy']);
    }
    public function index()
    {
        $members = Member::with('community', 'parent', 'spouse')->latest('id')->paginate(10);
        return view('members.index', compact('members'));
    }

    public function create()
    {
        $communities = Community::all();
        $selectedParent = old('parent_id') ? Member::find(old('parent_id')) : null;
        $selectedSpouse = old('spouse_id') ? Member::find(old('spouse_id')) : null;
        return view('members.create', compact('communities', 'selectedParent', 'selectedSpouse'));
    }

    protected function sanitizeDate($date): ?string
    {
        if (empty($date)) {
            return null;
        }
        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function store(Request $request)
    {
        // 0. Double-submit prevention via atomic Form Token
        $formToken = $request->input('form_token');
        if (!empty($formToken)) {
            $tokenKey = 'member_store_token_' . md5($formToken);
            if (!Cache::add($tokenKey, 1, 60)) {
                Log::warning('Duplicate member submission blocked via form_token: ' . $formToken);
                return redirect()->route('members.index')->with('success', 'Member pehle hi save ho chuka hai (Duplicate request prevented).');
            }
        }

        $data = $request->all();

        // 1. Validate Member Name
        $rawName = isset($data['name']) ? trim((string) $data['name']) : '';
        if ($rawName === '') {
            return redirect()->back()->withInput()->with('error', 'Member ka naam likhna zaroori hai (Member name is required).');
        }
        $data['name'] = mb_substr($rawName, 0, 250);

        // 2. Truncate string inputs and convert empty strings to null
        $stringFields = [
            'father_name', 'grandfather_name', 'caste', 'email', 'phone',
            'cnic', 'membership_number', 'blood_group', 'b_form_number', 'country',
            'education', 'skill', 'address', 'description'
        ];
        foreach ($stringFields as $f) {
            if (isset($data[$f]) && is_string($data[$f])) {
                $trimmed = trim($data[$f]);
                $maxLen = ($f === 'description') ? 65000 : 250;
                $data[$f] = $trimmed === '' ? null : mb_substr($trimmed, 0, $maxLen);
            }
        }

        // 3. Validate and normalize Enums
        if (empty($data['gender']) || !in_array($data['gender'], ['male', 'female', 'other'])) {
            $data['gender'] = 'male';
        }
        if (empty($data['marital_status']) || !in_array($data['marital_status'], ['single', 'married', 'divorced', 'widowed', 'widower'])) {
            $data['marital_status'] = 'single';
        }
        if (empty($data['member_type']) || !in_array($data['member_type'], ['householder', 'non_householder', 'child', 'sibling'])) {
            $data['member_type'] = 'householder';
        }
        if (empty($data['status']) || !in_array($data['status'], ['active', 'inactive'])) {
            $data['status'] = 'active';
        }

        // 4. Normalize Date & Numeric fields
        $data['is_deceased'] = !empty($data['is_deceased']) ? 1 : 0;
        $data['date_of_birth'] = $this->sanitizeDate($data['date_of_birth'] ?? null);
        $data['death_date'] = $data['is_deceased'] ? $this->sanitizeDate($data['death_date'] ?? null) : null;
        $data['card_received'] = (!empty($data['card_received']) && is_numeric($data['card_received'])) ? (int) $data['card_received'] : null;

        // 5. Safe Foreign Keys (Community, Parent, Spouse)
        if (!empty($data['community_id'])) {
            $commExists = Community::where('id', $data['community_id'])->exists();
            if (!$commExists) {
                $defaultComm = Community::first() ?? Community::create(['name' => 'General Community']);
                $data['community_id'] = $defaultComm->id;
            } else {
                $data['community_id'] = (int) $data['community_id'];
            }
        } else {
            $defaultComm = Community::first() ?? Community::create(['name' => 'General Community']);
            $data['community_id'] = $defaultComm->id;
        }

        if (!empty($data['parent_id'])) {
            $data['parent_id'] = Member::where('id', $data['parent_id'])->exists() ? (int) $data['parent_id'] : null;
        } else {
            $data['parent_id'] = null;
        }

        if (!empty($data['spouse_id'])) {
            $data['spouse_id'] = Member::where('id', $data['spouse_id'])->exists() ? (int) $data['spouse_id'] : null;
        } else {
            $data['spouse_id'] = null;
        }

        // 6. Fast Duplicate Detection (prevent creating identical member within 15 seconds)
        $recentDuplicateQuery = Member::where('name', $data['name'])
            ->where('community_id', $data['community_id'])
            ->where('created_at', '>=', now()->subSeconds(15));
        if (!empty($data['cnic'])) {
            $recentDuplicateQuery->where('cnic', $data['cnic']);
        } elseif (!empty($data['phone'])) {
            $recentDuplicateQuery->where('phone', $data['phone']);
        }
        $existingDuplicate = $recentDuplicateQuery->first();
        if ($existingDuplicate) {
            Log::info("Rapid duplicate submission blocked for: {$data['name']} (Existing ID: {$existingDuplicate->id})");
            return redirect()->route('members.index')->with('success', 'Member kamiyabi se add ho gaya hai.');
        }

        // 7. Handle Profile Picture (File upload or Base64 Webcam capture)
        if (!file_exists(public_path('profiles'))) {
            @mkdir(public_path('profiles'), 0777, true);
        }

        if ($request->hasFile('profile_picture')) {
            try {
                $file = $request->file('profile_picture');
                $ext = strtolower($file->getClientOriginalExtension());
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    $ext = 'jpg';
                }
                $filename = uniqid('prof_') . '.' . $ext;
                $file->move(public_path('profiles'), $filename);
                $data['profile_picture'] = $filename;
            } catch (\Throwable $e) {
                Log::warning('Profile picture file upload failed: ' . $e->getMessage());
            }
        } elseif (!empty($request->input('captured_image')) && str_starts_with($request->input('captured_image'), 'data:image')) {
            try {
                $captured = $request->input('captured_image');
                $parts = explode(';base64,', $captured);
                if (count($parts) === 2) {
                    $extParts = explode('image/', $parts[0]);
                    $ext = $extParts[1] ?? 'jpeg';
                    $ext = explode('+', $ext)[0];
                    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $ext = 'jpeg';
                    }
                    $filename = uniqid('cam_') . '.' . $ext;
                    file_put_contents(public_path('profiles/' . $filename), base64_decode($parts[1]));
                    $data['profile_picture'] = $filename;
                }
            } catch (\Throwable $e) {
                Log::warning('Captured image base64 decode failed: ' . $e->getMessage());
            }
        }

        // 8. Separate Spouse and Children input data
        $spouseType = $request->input('spouse_type', 'new');
        $spouseName = trim((string) $request->input('spouse_name', ''));
        $spouseCnic = trim((string) $request->input('spouse_cnic', '')) ?: null;
        $spousePhone = trim((string) $request->input('spouse_phone', '')) ?: null;
        $spouseGender = $request->input('spouse_gender');
        if (!in_array($spouseGender, ['male', 'female', 'other'])) {
            $spouseGender = $data['gender'] === 'male' ? 'female' : 'male';
        }
        $spouseDob = $this->sanitizeDate($request->input('spouse_date_of_birth'));
        $spouseBloodGroup = trim((string) $request->input('spouse_blood_group', '')) ?: null;
        $existingSpouseId = $request->input('spouse_id');
        if (!empty($existingSpouseId) && !Member::where('id', $existingSpouseId)->exists()) {
            $existingSpouseId = null;
        }

        $childrenData = $request->input('children', []);

        // Clean extra non-column keys from main member payload
        unset(
            $data['form_token'],
            $data['spouse_type'],
            $data['spouse_name'],
            $data['spouse_cnic'],
            $data['spouse_phone'],
            $data['spouse_gender'],
            $data['spouse_date_of_birth'],
            $data['spouse_blood_group'],
            $data['children'],
            $data['captured_image']
        );

        if ($spouseType === 'new') {
            $data['spouse_id'] = null;
        }

        try {
            DB::transaction(function () use ($data, $spouseType, $spouseName, $spouseCnic, $spousePhone, $spouseGender, $spouseDob, $spouseBloodGroup, $existingSpouseId, $childrenData) {
                if (!empty($spouseName) || !empty($existingSpouseId)) {
                    $data['marital_status'] = 'married';
                }

                $member = Member::create($data);

                // Handle Spouse (Only when married)
                if ($member->marital_status === 'married') {
                    if ($spouseType === 'new' && !empty($spouseName)) {
                        // Check if spouse already exists by CNIC to prevent duplicate spouse records
                        $existingSpouseByCnic = $spouseCnic ? Member::where('cnic', $spouseCnic)->first() : null;
                        if ($existingSpouseByCnic) {
                            $spouse = $existingSpouseByCnic;
                            $spouse->spouse_id = $member->id;
                            $spouse->marital_status = 'married';
                            $spouse->save();
                        } else {
                            $spouse = Member::create([
                                'name' => mb_substr($spouseName, 0, 250),
                                'gender' => $spouseGender,
                                'cnic' => $spouseCnic ? mb_substr($spouseCnic, 0, 50) : null,
                                'phone' => $spousePhone ? mb_substr($spousePhone, 0, 50) : null,
                                'date_of_birth' => $spouseDob,
                                'blood_group' => $spouseBloodGroup ? mb_substr($spouseBloodGroup, 0, 10) : null,
                                'marital_status' => 'married',
                                'spouse_id' => $member->id,
                                'community_id' => $member->community_id,
                                'member_type' => 'non_householder',
                                'address' => $member->address,
                                'country' => $member->country,
                                'caste' => $member->caste,
                                'status' => 'active',
                            ]);
                        }

                        $member->spouse_id = $spouse->id;
                        $member->marital_status = 'married';
                        $member->save();
                    } elseif ($spouseType === 'existing' && !empty($existingSpouseId)) {
                        $existingSpouse = Member::find($existingSpouseId);
                        if ($existingSpouse) {
                            $member->spouse_id = $existingSpouse->id;
                            $member->marital_status = 'married';
                            $member->save();

                            $existingSpouse->spouse_id = $member->id;
                            $existingSpouse->marital_status = 'married';
                            $existingSpouse->save();
                        }
                    }
                }

                // Handle Deceased spouse status change
                if (!empty($data['is_deceased']) && $member->spouse_id) {
                    $spouse = Member::find($member->spouse_id);
                    if ($spouse) {
                        $spouse->marital_status = $member->gender === 'male' ? 'widowed' : 'widower';
                        $spouse->save();
                    }
                }

                // Handle Children (with in-memory & database deduplication)
                $createdChildrenCount = 0;
                if (is_array($childrenData) && !empty($childrenData)) {
                    $fatherId = $member->gender === 'male' ? $member->id : ($member->spouse_id ?: $member->id);
                    $fatherName = $member->gender === 'male' ? $member->name : ($spouseName ?: ($member->father_name ?? null));
                    $grandfatherName = $member->gender === 'male' ? $member->father_name : ($member->grandfather_name ?? null);

                    $seenChildren = [];

                    foreach ($childrenData as $childItem) {
                        if (!is_array($childItem)) {
                            continue;
                        }
                        $childName = isset($childItem['name']) ? trim((string) $childItem['name']) : '';
                        if (empty($childName)) {
                            continue;
                        }

                        $childDob = $this->sanitizeDate($childItem['date_of_birth'] ?? null);
                        $childBform = !empty($childItem['b_form_number']) ? mb_substr(trim((string) $childItem['b_form_number']), 0, 50) : null;

                        // In-memory deduplication key to stop duplicate rows in same submit
                        $dedupKey = mb_strtolower($childName) . '|' . ($childBform ?? '') . '|' . ($childDob ?? '');
                        if (isset($seenChildren[$dedupKey])) {
                            continue;
                        }
                        $seenChildren[$dedupKey] = true;

                        // Check if child already exists under this father
                        $childExists = Member::where('parent_id', $fatherId)
                            ->where('name', $childName)
                            ->when(!empty($childBform), fn($q) => $q->where('b_form_number', $childBform))
                            ->exists();

                        if ($childExists) {
                            continue;
                        }

                        $childGender = $childItem['gender'] ?? 'male';
                        if (!in_array($childGender, ['male', 'female', 'other'])) {
                            $childGender = 'male';
                        }
                        $childBloodGroup = !empty($childItem['blood_group']) ? mb_substr(trim((string) $childItem['blood_group']), 0, 10) : null;

                        Member::create([
                            'name' => mb_substr($childName, 0, 250),
                            'gender' => $childGender,
                            'date_of_birth' => $childDob,
                            'b_form_number' => $childBform,
                            'blood_group' => $childBloodGroup,
                            'parent_id' => $fatherId,
                            'community_id' => $member->community_id,
                            'member_type' => 'child',
                            'marital_status' => 'single',
                            'address' => $member->address,
                            'country' => $member->country,
                            'caste' => $member->caste,
                            'father_name' => $fatherName ? mb_substr($fatherName, 0, 250) : null,
                            'grandfather_name' => $grandfatherName ? mb_substr($grandfatherName, 0, 250) : null,
                            'status' => 'active',
                        ]);
                        $createdChildrenCount++;
                    }
                }

                $auditMsg = 'Created member ' . mb_substr($member->name ?? 'N/A', 0, 100);
                if ($member->spouse_id) {
                    $auditMsg .= ' with spouse';
                }
                if ($createdChildrenCount > 0) {
                    $auditMsg .= ' and ' . $createdChildrenCount . ' children';
                }

                if (auth()->check()) {
                    AuditLog::create([
                        'user_id' => auth()->id(),
                        'action' => mb_substr($auditMsg, 0, 245),
                    ]);
                }
            });

            return redirect()->route('members.index')->with('success', 'Member kamiyabi se add ho gaya hai (Member added successfully).');
        } catch (\Throwable $e) {
            Log::error('Member store error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Member save nahi ho saka: ' . $e->getMessage());
        }
    }

    public function show($member)
    {
        try {
            if (!$member instanceof Member) {
                $member = Member::findOrFail($member);
            }
            $member->loadMissing(['community', 'parent.spouse', 'spouse']);
            $familyChildren = $member->family_children ?? collect();
            $father = $member->father;
            $mother = $member->mother;
            $siblings = $member->siblings ?? collect();
            return view('members.show', compact('member', 'familyChildren', 'father', 'mother', 'siblings'));
        } catch (\Throwable $e) {
            Log::error('Member show error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->route('members.index')->with('error', 'Member record open nahi ho saka: ' . $e->getMessage());
        }
    }

    public function edit(Member $member)
    {
        $communities = Community::all();
        $parentId = old('parent_id', $member->parent_id);
        $spouseId = old('spouse_id', $member->spouse_id);
        $selectedParent = $parentId ? Member::find($parentId) : null;
        $selectedSpouse = $spouseId ? Member::find($spouseId) : null;
        return view('members.edit', compact('member', 'communities', 'selectedParent', 'selectedSpouse'));
    }

    public function update(Request $request, Member $member)
    {
        $data = $request->all();

        // 1. Truncate string inputs and convert empty strings to null
        $stringFields = [
            'name', 'father_name', 'grandfather_name', 'caste', 'email', 'phone',
            'cnic', 'membership_number', 'blood_group', 'b_form_number', 'country',
            'education', 'skill', 'address', 'description'
        ];
        foreach ($stringFields as $f) {
            if (isset($data[$f]) && is_string($data[$f])) {
                $trimmed = trim($data[$f]);
                $maxLen = ($f === 'description') ? 65000 : 250;
                $data[$f] = $trimmed === '' ? null : mb_substr($trimmed, 0, $maxLen);
            }
        }

        // 2. Validate and normalize Enums
        if (empty($data['name'])) {
            $data['name'] = $member->name ?: 'N/A';
        }
        if (empty($data['gender']) || !in_array($data['gender'], ['male', 'female', 'other'])) {
            $data['gender'] = $member->gender ?: 'male';
        }
        if (empty($data['marital_status']) || !in_array($data['marital_status'], ['single', 'married', 'divorced', 'widowed', 'widower'])) {
            $data['marital_status'] = $member->marital_status ?: 'single';
        }
        if (empty($data['member_type']) || !in_array($data['member_type'], ['householder', 'non_householder', 'child', 'sibling'])) {
            $data['member_type'] = $member->member_type ?: 'householder';
        }
        if (empty($data['status']) || !in_array($data['status'], ['active', 'inactive'])) {
            $data['status'] = $member->status ?: 'active';
        }

        // 3. Normalize Date & Numeric fields
        $data['is_deceased'] = !empty($data['is_deceased']) ? 1 : 0;
        $data['date_of_birth'] = $this->sanitizeDate($data['date_of_birth'] ?? null);
        $data['death_date'] = $data['is_deceased'] ? $this->sanitizeDate($data['death_date'] ?? null) : null;
        $data['card_received'] = (!empty($data['card_received']) && is_numeric($data['card_received'])) ? (int) $data['card_received'] : null;

        // 4. Safe Foreign Keys
        if (!empty($data['community_id'])) {
            $commExists = Community::where('id', $data['community_id'])->exists();
            $data['community_id'] = $commExists ? (int) $data['community_id'] : $member->community_id;
        } else {
            $data['community_id'] = $member->community_id;
        }

        if (!empty($data['parent_id'])) {
            $data['parent_id'] = Member::where('id', $data['parent_id'])->where('id', '!=', $member->id)->exists() ? (int) $data['parent_id'] : null;
        } else {
            $data['parent_id'] = null;
        }

        if (!empty($data['spouse_id'])) {
            $data['spouse_id'] = Member::where('id', $data['spouse_id'])->where('id', '!=', $member->id)->exists() ? (int) $data['spouse_id'] : null;
        } else {
            $data['spouse_id'] = null;
        }

        // 5. Handle Profile Picture
        if (!file_exists(public_path('profiles'))) {
            @mkdir(public_path('profiles'), 0777, true);
        }

        if ($request->hasFile('profile_picture')) {
            try {
                if ($member->profile_picture && file_exists(public_path('profiles/' . $member->profile_picture))) {
                    @unlink(public_path('profiles/' . $member->profile_picture));
                }
                $file = $request->file('profile_picture');
                $ext = strtolower($file->getClientOriginalExtension());
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    $ext = 'jpg';
                }
                $filename = uniqid('prof_') . '.' . $ext;
                $file->move(public_path('profiles'), $filename);
                $data['profile_picture'] = $filename;
            } catch (\Throwable $e) {
                Log::warning('Profile picture update failed: ' . $e->getMessage());
            }
        } elseif (!empty($request->input('captured_image')) && str_starts_with($request->input('captured_image'), 'data:image')) {
            try {
                if ($member->profile_picture && file_exists(public_path('profiles/' . $member->profile_picture))) {
                    @unlink(public_path('profiles/' . $member->profile_picture));
                }
                $captured = $request->input('captured_image');
                $parts = explode(';base64,', $captured);
                if (count($parts) === 2) {
                    $extParts = explode('image/', $parts[0]);
                    $ext = $extParts[1] ?? 'png';
                    $ext = explode('+', $ext)[0];
                    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $ext = 'png';
                    }
                    $filename = uniqid('cam_') . '.' . $ext;
                    file_put_contents(public_path('profiles/' . $filename), base64_decode($parts[1]));
                    $data['profile_picture'] = $filename;
                }
            } catch (\Throwable $e) {
                Log::warning('Captured image update failed: ' . $e->getMessage());
            }
        }

        unset($data['captured_image']);

        try {
            DB::transaction(function () use ($request, $member, $data) {
                $oldDeceased = $member->is_deceased;
                $oldSpouseId = $member->spouse_id;
                $member->update($data);

                // Bidirectional spouse linking
                if (!empty($member->spouse_id) && $member->spouse_id != $oldSpouseId) {
                    $newSpouse = Member::find($member->spouse_id);
                    if ($newSpouse && $newSpouse->spouse_id != $member->id) {
                        $newSpouse->spouse_id = $member->id;
                        $newSpouse->marital_status = 'married';
                        $newSpouse->save();
                    }
                } elseif (empty($member->spouse_id) && !empty($oldSpouseId)) {
                    $oldSpouse = Member::find($oldSpouseId);
                    if ($oldSpouse && $oldSpouse->spouse_id == $member->id) {
                        $oldSpouse->spouse_id = null;
                        $oldSpouse->save();
                    }
                }

                if ($request->input('is_deceased') && !$oldDeceased && $member->spouse_id) {
                    $spouse = Member::find($member->spouse_id);
                    if ($spouse) {
                        $spouse->marital_status = $member->gender === 'male' ? 'widowed' : 'widower';
                        $spouse->save();
                    }
                } elseif (!$request->input('is_deceased') && $oldDeceased && $member->spouse_id) {
                    $spouse = Member::find($member->spouse_id);
                    if ($spouse && $spouse->is_deceased) {
                        $member->marital_status = $spouse->gender === 'male' ? 'widowed' : 'widower';
                        $member->save();
                    }
                }

                if (auth()->check()) {
                    AuditLog::create([
                        'user_id' => auth()->id(),
                        'action' => mb_substr('Updated member ' . ($member->name ?? 'N/A'), 0, 245),
                    ]);
                }
            });

            return redirect()->route('members.index')->with('success', 'Member kamiyabi se update ho gaya hai (Member updated successfully).');
        } catch (\Throwable $e) {
            \Log::error('Member update error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Member update nahi ho saka: ' . $e->getMessage());
        }
    }

    public function destroy(Member $member)
    {
        $name = $member->name;
        $member->delete();

        if (auth()->check()) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => mb_substr('Deleted member ' . $name, 0, 245),
            ]);
        }

        return redirect()->route('members.index')->with('success', 'Member deleted successfully.');
    }

    public function selectFamilyTree(Request $request)
    {
        $query = Member::with('community');
        $search = trim((string) $request->input('search'));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('cnic', 'LIKE', "%{$search}%")
                    ->orWhere('b_form_number', 'LIKE', "%{$search}%");
            });
        }
        $members = $query->orderBy('name')->paginate(10)->withQueryString();
        return view('members.select-family-tree', compact('members'));
    }

    public function familyTree(Member $member)
    {
        $member = Member::with(['parent', 'spouse', 'children'])->findOrFail($member->id);
        $treeData = $this->buildTreeData($member);
        return view('members.family-tree', compact('member', 'treeData'));
    }

    public function searchByCnic(Request $request)
    {
        $cnic = $request->query('q');
        $query = Member::where('cnic', 'LIKE', "%$cnic%");
        if ($request->filled('exclude')) {
            $query->where('id', '!=', $request->query('exclude'));
        }
        $members = $query->select('id', 'name', 'cnic')
            ->limit(10)
            ->get();
        return response()->json($members);
    }

    public function downloadPdf(Member $member)
    {
        $member = Member::with(['community', 'parent.spouse', 'spouse'])->findOrFail($member->id);
        $pdf = MpdfPdf::loadView('members.pdf', compact('member'));
        return $pdf->download('member-details-' . $member->id . '.pdf');
    }

    public function downloadCard(Member $member)
    {
        $member = Member::with(['community', 'parent.spouse'])->findOrFail($member->id);
        $father = $member->father;
        $pdf = MpdfPdf::loadCard(
            'members.card-front-pdf',
            'members.card-back-pdf',
            compact('member', 'father')
        );
        return $pdf->download('member-card-' . $member->id . '.pdf');
    }

    public function downloadFamilyTreePdf(Member $member)
    {
        $member = Member::with(['parent', 'spouse', 'children'])->findOrFail($member->id);
        $treeData = $this->buildTreeData($member);
        $pdf = MpdfPdf::loadView('members.family-tree-pdf', compact('member', 'treeData'));
        return $pdf->download('family-tree-' . $member->id . '.pdf');
    }

    public function dashboard()
    {
        try {
            $totalMembers = Member::count();
            $totalHouseholders = Member::where('member_type', 'householder')->count();
            $totalDeceased = Member::where('is_deceased', true)->count();
            $totalDivorced = Member::where('marital_status', 'divorced')->count();

            $communities = Community::withCount('members')->get();

            $maleCounts = Member::where('gender', 'male')->groupBy('community_id')->selectRaw('community_id, count(*) as aggregate')->pluck('aggregate', 'community_id');
            $femaleCounts = Member::where('gender', 'female')->groupBy('community_id')->selectRaw('community_id, count(*) as aggregate')->pluck('aggregate', 'community_id');
            $childCounts = Member::where('member_type', 'child')->groupBy('community_id')->selectRaw('community_id, count(*) as aggregate')->pluck('aggregate', 'community_id');
            $deceasedCounts = Member::where('is_deceased', true)->groupBy('community_id')->selectRaw('community_id, count(*) as aggregate')->pluck('aggregate', 'community_id');

            $communitiesData = $communities->map(function ($community) use ($maleCounts, $femaleCounts, $childCounts, $deceasedCounts) {
                return [
                    'name' => $community->name,
                    'count' => $community->members_count,
                    'male_count' => $maleCounts->get($community->id, 0),
                    'female_count' => $femaleCounts->get($community->id, 0),
                    'child_count' => $childCounts->get($community->id, 0),
                    'deceased_count' => $deceasedCounts->get($community->id, 0),
                ];
            });

            $trendData = [];
            $labels = [];
            for ($i = 4; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $count = Member::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count();
                $trendData[] = $count;
                $labels[] = $date->format('M Y');
            }

            $recentMembers = Member::with('community')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            return view('dashboard', compact('totalMembers', 'totalHouseholders', 'totalDeceased', 'totalDivorced', 'communitiesData', 'recentMembers', 'trendData', 'labels'));
        } catch (\Exception $e) {
            Log::error('Dashboard Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return view('dashboard', ['error' => 'An error occurred while loading the dashboard: ' . $e->getMessage()]);
        }
    }

  

    protected function memberTypeUrdu(string $type): string
    {
        return match ($type) {
            'householder' => 'سربراہِ خاندان',
            'family_member' => 'خاندان کا فرد',
            'non_householder' => 'غیر سربراہ',
            'child' => 'بچہ',
            'spouse' => 'شریکِ حیات',
            'sibling' => 'بہن بھائی',
            default => ucfirst(str_replace('_', ' ', $type)),
        };
    }

    protected function memberIdentifier(Member $person): string
    {
        $idParts = [];
        if (!empty($person->membership_number)) {
            $idParts[] = 'Mem #: ' . $person->membership_number;
        }
        if ($person->cnic) {
            $idParts[] = 'CNIC: ' . $person->cnic;
        } elseif ($person->b_form_number) {
            $idParts[] = 'B-Form: ' . $person->b_form_number;
        }
        return !empty($idParts) ? implode(' | ', $idParts) : 'ID: #' . $person->id;
    }

    protected function buildTreeData(Member $member, $visited = [], $relation = 'Member')
    {
        if (in_array($member->id, $visited)) {
            Log::warning('Circular reference detected for member ID: ' . $member->id);
            return null;
        }
        $visited[] = $member->id;

        $nodeId = 'member_' . $member->id;
        $typeLabel = ucfirst(str_replace('_', ' ', $member->member_type)) . ' / ' . $this->memberTypeUrdu($member->member_type);
        $relationPrefix = match ($relation) {
            'Child' => 'Child / بچہ: ',
            'Parent' => 'Parent / والدین: ',
            'Spouse' => 'Spouse / شریکِ حیات: ',
            'Sibling' => 'Sibling / بہن بھائی: ',
            default => '',
        };

        $node = [
            'id' => $nodeId,
            'text' => $relationPrefix . $member->name . ' (' . $typeLabel . ') — ' . $this->memberIdentifier($member),
            'state' => ['opened' => true],
            'children' => [],
            'type' => $member->member_type,
            'relation' => $relation,
            'name' => $member->name,
            'cnic' => $member->cnic,
            'b_form_number' => $member->b_form_number,
        ];

        $addedNodes = [];

        if ($member->spouse && !in_array('spouse_' . $member->spouse->id, $addedNodes)) {
            $spouse = $member->spouse;
            $spouseType = ucfirst(str_replace('_', ' ', $spouse->member_type)) . ' / ' . $this->memberTypeUrdu($spouse->member_type);
            $node['children'][] = [
                'id' => 'spouse_' . $spouse->id . '_' . $member->id,
                'text' => 'Spouse / شریکِ حیات: ' . $spouse->name . ' (' . $spouseType . ') — ' . $this->memberIdentifier($spouse),
                'type' => 'spouse',
                'relation' => 'Spouse',
                'icon' => 'jstree-file',
                'name' => $spouse->name,
                'cnic' => $spouse->cnic,
                'b_form_number' => $spouse->b_form_number,
            ];
            $addedNodes[] = 'spouse_' . $spouse->id;
        }

        foreach ($member->family_children as $child) {
            $childNode = $this->buildTreeData($child, $visited, 'Child');
            if ($childNode) {
                $node['children'][] = $childNode;
            }
        }

        $siblings = $member->siblings;
        foreach ($siblings as $sibling) {
            if (!in_array('sibling_' . $sibling->id, $addedNodes)) {
                $siblingType = ucfirst(str_replace('_', ' ', $sibling->member_type)) . ' / ' . $this->memberTypeUrdu($sibling->member_type);
                $node['children'][] = [
                    'id' => 'sibling_' . $sibling->id . '_' . $member->id,
                    'text' => 'Sibling / بہن بھائی: ' . $sibling->name . ' (' . $siblingType . ') — ' . $this->memberIdentifier($sibling),
                    'type' => 'sibling',
                    'relation' => 'Sibling',
                    'icon' => 'jstree-file',
                    'name' => $sibling->name,
                    'cnic' => $sibling->cnic,
                    'b_form_number' => $sibling->b_form_number,
                ];
                $addedNodes[] = 'sibling_' . $sibling->id;
            }
        }

        if ($member->parent && !($member->member_type === 'child' && $member->spouse_id) && !in_array('parent_' . $member->parent->id, $addedNodes)) {
            $isParentTopLevel = false;
            foreach ($visited as $visitedId) {
                if ($visitedId == $member->parent->id && $visitedId != $member->id) {
                    $isParentTopLevel = true;
                    break;
                }
            }
            if (!$isParentTopLevel) {
                $parent = $member->parent;
                $parentType = ucfirst(str_replace('_', ' ', $parent->member_type)) . ' / ' . $this->memberTypeUrdu($parent->member_type);
                $node['children'][] = [
                    'id' => 'parent_' . $parent->id . '_' . $member->id,
                    'text' => 'Parent / والدین: ' . $parent->name . ' (' . $parentType . ') — ' . $this->memberIdentifier($parent),
                    'type' => 'parent',
                    'relation' => 'Parent',
                    'icon' => 'jstree-file',
                    'name' => $parent->name,
                    'cnic' => $parent->cnic,
                    'b_form_number' => $parent->b_form_number,
                ];
                $addedNodes[] = 'parent_' . $parent->id;
            }
        }

        return $node;
    }

   
}