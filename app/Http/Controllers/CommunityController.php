<?php
namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Member;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view communities')->only(['index']);
        $this->middleware('permission:create communities')->only(['create', 'store']);
        $this->middleware('permission:edit communities')->only(['edit', 'update']);
        $this->middleware('permission:delete communities')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Community::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $communities = $query->withCount('members')->get();

        $maleCounts = Member::where('gender', 'male')->groupBy('community_id')->selectRaw('community_id, count(*) as aggregate')->pluck('aggregate', 'community_id');
        $femaleCounts = Member::where('gender', 'female')->groupBy('community_id')->selectRaw('community_id, count(*) as aggregate')->pluck('aggregate', 'community_id');
        $childCounts = Member::where('member_type', 'child')->groupBy('community_id')->selectRaw('community_id, count(*) as aggregate')->pluck('aggregate', 'community_id');
        $deceasedCounts = Member::where('is_deceased', true)->groupBy('community_id')->selectRaw('community_id, count(*) as aggregate')->pluck('aggregate', 'community_id');

        $communities->each(function ($community) use ($maleCounts, $femaleCounts, $childCounts, $deceasedCounts) {
            $community->male_count = $maleCounts->get($community->id, 0);
            $community->female_count = $femaleCounts->get($community->id, 0);
            $community->child_count = $childCounts->get($community->id, 0);
            $community->deceased_count = $deceasedCounts->get($community->id, 0);
        });

        if ($request->ajax()) {
            return response()->json(['communities' => $communities]);
        }

        return view('communities.index', compact('communities'));
    }

    public function create()
    {
        return view('communities.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:communities,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $community = Community::create($request->all());

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Created community ' . $community->name,
        ]);

        return redirect()->route('communities.index')->with('success', 'Community added successfully.');
    }

    public function edit(Community $community)
    {
        return view('communities.edit', compact('community'));
    }

    public function update(Request $request, Community $community)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:communities,name,' . $community->id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $community->update($request->all());

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Updated community ' . $community->name,
        ]);

        return redirect()->route('communities.index')->with('success', 'Community updated successfully.');
    }

    public function destroy(Community $community)
    {
        $name = $community->name;
        $community->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Deleted community ' . $name,
        ]);

        return redirect()->route('communities.index')->with('success', 'Community deleted successfully.');
    }
}