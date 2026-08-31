<?php
namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Community;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
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
        $members = Member::with('community', 'parent', 'spouse')->paginate(10);
        return view('members.index', compact('members'));
    }

    public function create()
    {
        $communities = Community::all();
        $selectedParent = old('parent_id') ? Member::find(old('parent_id')) : null;
        $selectedSpouse = old('spouse_id') ? Member::find(old('spouse_id')) : null;
        return view('members.create', compact('communities', 'selectedParent', 'selectedSpouse'));
    }

 public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:members,email',
            'phone' => 'nullable|digits:11',
            'gender' => 'required|in:male,female,other',
            'cnic' => 'nullable|regex:/^[0-9]{5}-[0-9]{7}-[0-9]$/|unique:members,cnic|required_without:b_form_number',
            'marital_status' => 'required|in:single,married,divorced,widowed,widower',
            'community_id' => 'required|exists:communities,id',
            'member_type' => 'required|in:householder,non_householder,child,sibling',
            'parent_id' => 'nullable|exists:members,id',
            'spouse_id' => 'nullable|exists:members,id',
            'address' => 'nullable|string',
            'date_of_birth' => 'required|date',
            'is_deceased' => 'nullable|boolean',
            'profile_picture' => 'nullable|image|max:2048|mimes:jpg,jpeg,png',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'card_received' => 'nullable|integer',
            'b_form_number' => 'nullable|regex:/^[0-9]{5}-[0-9]{7}-[0-9]$/|required_without:cnic',
            'country' => 'required|in:Pakistan,India,United States,United Kingdom,Canada,Australia,Germany,France,China,Japan',
            'death_date' => 'nullable|date|required_if:is_deceased,1',
            'education' => 'required|string',
            'skill' => 'required|string',
            'membership_number' => 'nullable|string|max:50|unique:members,membership_number',
            'father_name' => 'nullable|string|max:255',
            'grandfather_name' => 'nullable|string|max:255',
            'caste' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profiles'), $filename);
            $data['profile_picture'] = $filename;
        }

        $member = Member::create($data);

        if ($request->input('is_deceased') && $member->spouse_id) {
            $spouse = Member::find($member->spouse_id);
            if ($spouse) {
                $spouse->marital_status = $member->gender === 'male' ? 'widowed' : 'widower';
                $spouse->save();
            }
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Created member ' . $member->name,
        ]);

        return redirect()->route('members.index')->with('success', 'Member added successfully.');
    }

    public function show(Member $member)
    {
        $member = Member::with(['community', 'parent', 'spouse', 'children'])->findOrFail($member->id);
        return view('members.show', compact('member'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:members,email,' . $member->id,
            'phone' => 'nullable|digits:11',
            'gender' => 'required|in:male,female,other',
            'cnic' => 'nullable|regex:/^[0-9]{5}-[0-9]{7}-[0-9]$/|unique:members,cnic,' . $member->id . '|required_without:b_form_number',
            'marital_status' => 'required|in:single,married,divorced,widowed,widower',
            'community_id' => 'required|exists:communities,id',
            'member_type' => 'required|in:householder,non_householder,child,sibling',
            'parent_id' => ['nullable', 'exists:members,id', Rule::notIn([$member->id])],
            'spouse_id' => ['nullable', 'exists:members,id', Rule::notIn([$member->id])],
            'address' => 'nullable|string',
            'date_of_birth' => 'required|date',
            'is_deceased' => 'nullable|boolean',
            'profile_picture' => 'nullable|image|max:2048|mimes:jpg,jpeg,png',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'card_received' => 'nullable|integer',
            'b_form_number' => 'nullable|regex:/^[0-9]{5}-[0-9]{7}-[0-9]$/|required_without:cnic',
            'country' => 'required|in:Pakistan,India,United States,United Kingdom,Canada,Australia,Germany,France,China,Japan',
            'death_date' => 'nullable|date|required_if:is_deceased,1',
            'education' => 'required|string',
            'skill' => 'required|string',
            'membership_number' => 'nullable|string|max:50|unique:members,membership_number,' . $member->id,
            'father_name' => 'nullable|string|max:255',
            'grandfather_name' => 'nullable|string|max:255',
            'caste' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        if ($request->hasFile('profile_picture')) {
            if ($member->profile_picture && file_exists(public_path('profiles/' . $member->profile_picture))) {
                unlink(public_path('profiles/' . $member->profile_picture));
            }
            $file = $request->file('profile_picture');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profiles'), $filename);
            $data['profile_picture'] = $filename;
        }

        $oldDeceased = $member->is_deceased;
        $member->update($data);

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

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Updated member ' . $member->name,
        ]);

        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        $name = $member->name;
        $member->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Deleted member ' . $name,
        ]);

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
        $member = Member::with(['community', 'parent', 'spouse', 'children'])->findOrFail($member->id);
        $pdf = Pdf::loadView('members.pdf', compact('member'));
        return $pdf->download('member-details-' . $member->id . '.pdf');
    }

    public function downloadCard(Member $member)
    {
        $member = Member::with('community')->findOrFail($member->id);

        // Front and back are rendered as separate PDFs and merged with FPDI.
        // Dompdf's Cpdf backend misbehaves at this tiny (CR80) paper size when
        // a document needs more than one page, so each side must stay a
        // single logical page; see the page-1-only import below.
        $frontPath = tempnam(sys_get_temp_dir(), 'card_front_') . '.pdf';
        $backPath = tempnam(sys_get_temp_dir(), 'card_back_') . '.pdf';

        file_put_contents($frontPath, Pdf::loadView('members.card-front-pdf', ['member' => $member])
            ->setPaper([0, 0, 243, 153])
            ->output());
        file_put_contents($backPath, Pdf::loadView('members.card-back-pdf', ['member' => $member])
            ->setPaper([0, 0, 243, 153])
            ->output());

        // Only page 1 of each source is used: at this tiny paper size,
        // Dompdf's Cpdf backend appends a spurious blank trailing page even
        // when all content fits on a single page.
        $fpdi = new \setasign\Fpdi\Fpdi('L', 'pt', [243, 153]);
        foreach ([$frontPath, $backPath] as $path) {
            $fpdi->setSourceFile($path);
            $fpdi->AddPage();
            $fpdi->useTemplate($fpdi->importPage(1));
        }

        unlink($frontPath);
        unlink($backPath);

        return response($fpdi->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="member-card-' . $member->id . '.pdf"',
        ]);
    }

    public function downloadFamilyTreePdf(Member $member)
    {
        $member = Member::with(['parent', 'spouse', 'children'])->findOrFail($member->id);
        $treeData = $this->buildTreeData($member);
        $pdf = Pdf::loadView('members.family-tree-pdf', compact('member', 'treeData'));
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

  

    protected function memberIdentifier(Member $person): string
    {
        if ($person->cnic) {
            return 'CNIC: ' . $person->cnic;
        }
        if ($person->b_form_number) {
            return 'B-Form: ' . $person->b_form_number;
        }
        return 'No CNIC/B-Form';
    }

    protected function buildTreeData(Member $member, $visited = [], $relation = 'Member')
    {
        if (in_array($member->id, $visited)) {
            Log::warning('Circular reference detected for member ID: ' . $member->id);
            return null;
        }
        $visited[] = $member->id;

        $nodeId = 'member_' . $member->id;

        $node = [
            'id' => $nodeId,
            'text' => $member->name . ' (' . ucfirst($member->member_type) . ') — ' . $this->memberIdentifier($member),
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
            $node['children'][] = [
                'id' => 'spouse_' . $spouse->id . '_' . $member->id,
                'text' => 'Spouse: ' . $spouse->name . ' (' . ucfirst($spouse->member_type) . ') — ' . $this->memberIdentifier($spouse),
                'type' => 'spouse',
                'relation' => 'Spouse',
                'icon' => 'jstree-file',
                'name' => $spouse->name,
                'cnic' => $spouse->cnic,
                'b_form_number' => $spouse->b_form_number,
            ];
            $addedNodes[] = 'spouse_' . $spouse->id;
        }

        foreach ($member->children as $child) {
            $childNode = $this->buildTreeData($child, $visited, 'Child');
            if ($childNode) {
                $node['children'][] = $childNode;
            }
        }

        $siblings = $member->parent_id
            ? Member::where('parent_id', $member->parent_id)
                ->where('id', '!=', $member->id)
                ->get()
            : collect();
        foreach ($siblings as $sibling) {
            if (!in_array('sibling_' . $sibling->id, $addedNodes)) {
                $node['children'][] = [
                    'id' => 'sibling_' . $sibling->id . '_' . $member->id,
                    'text' => 'Sibling: ' . $sibling->name . ' (' . ucfirst($sibling->member_type) . ') — ' . $this->memberIdentifier($sibling),
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
                $node['children'][] = [
                    'id' => 'parent_' . $parent->id . '_' . $member->id,
                    'text' => 'Parent: ' . $parent->name . ' (' . ucfirst($parent->member_type) . ') — ' . $this->memberIdentifier($parent),
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