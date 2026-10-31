<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Community;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MembersExport;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function report(Request $request)
    {
        $search = trim((string)($request->input('search') ?: $request->input('cnic_search')));
        $filters = $request->only([
            'community_id', 'member_type', 'gender', 'marital_status',
            'blood_group', 'is_deceased', 'education', 'skill'
        ]);

        $query = Member::with('community')
            ->search($search)
            ->filter($filters)
            ->latest('id');

        $members = $query->paginate(15)->withQueryString();
        $communities = Community::orderBy('name')->get();

        return view('report', compact('members', 'communities', 'search', 'filters'));
    }

    public function export(Request $request)
    {
        $search = trim((string)($request->input('search') ?: $request->input('cnic_search')));
        $filters = $request->only([
            'community_id', 'member_type', 'gender', 'marital_status',
            'blood_group', 'is_deceased', 'education', 'skill'
        ]);

        $query = Member::with('community')
            ->search($search)
            ->filter($filters)
            ->latest('id');

        $members = $query->get();

        return Excel::download(new MembersExport($members), 'members_report_' . now()->format('Y-m-d_H-i') . '.xlsx');
    }
}
