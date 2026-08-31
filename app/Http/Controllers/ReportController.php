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
        $query = Member::with('community');

        // Search by CNIC
        if ($request->has('cnic_search') && !empty($request->input('cnic_search'))) {
            $cnic = $request->input('cnic_search');
            $query->where('cnic', 'LIKE', "%$cnic%");
        }

        // Filters
        if ($request->has('community_id') && !empty($request->input('community_id'))) {
            $query->where('community_id', $request->input('community_id'));
        }
        if ($request->has('is_deceased') && $request->input('is_deceased') == '1') {
            $query->where('is_deceased', true);
        }
        if ($request->has('marital_status') && !empty($request->input('marital_status'))) {
            $query->where('marital_status', $request->input('marital_status'));
        }
        if ($request->has('blood_group') && !empty($request->input('blood_group'))) {
            $query->where('blood_group', $request->input('blood_group'));
        }
        if ($request->has('education') && !empty($request->input('education'))) {
            $query->where('education', 'LIKE', "%" . $request->input('education') . "%");
        }
        if ($request->has('skill') && !empty($request->input('skill'))) {
            $query->where('skill', 'LIKE', "%" . $request->input('skill') . "%");
        }
        if ($request->has('member_type') && !empty($request->input('member_type'))) {
            $query->where('member_type', $request->input('member_type'));
        }

        $members = $query->paginate(10);
        $communities = Community::all();

        return view('report', compact('members', 'communities'));
    }

 

public function export(Request $request)
{
    $query = Member::with('community');

    if ($request->has('cnic_search') && !empty($request->input('cnic_search'))) {
        $cnic = $request->input('cnic_search');
        $query->where('cnic', 'LIKE', "%$cnic%");
    }

    if ($request->has('community_id') && !empty($request->input('community_id'))) {
        $query->where('community_id', $request->input('community_id'));
        if ($request->has('is_deceased') && $request->input('is_deceased') == '1') {
            $query->where('is_deceased', true);
        }
    }
    if ($request->has('marital_status') && !empty($request->input('marital_status'))) {
        $query->where('marital_status', $request->input('marital_status'));
    }
    if ($request->has('blood_group') && !empty($request->input('blood_group'))) {
        $query->where('blood_group', $request->input('blood_group'));
    }
    if ($request->has('education') && !empty($request->input('education'))) {
        $query->where('education', 'LIKE', "%" . $request->input('education') . "%");
    }
    if ($request->has('skill') && !empty($request->input('skill'))) {
        $query->where('skill', 'LIKE', "%" . $request->input('skill') . "%");
    }
    if ($request->has('member_type') && !empty($request->input('member_type'))) {
        $query->where('member_type', $request->input('member_type'));
    }

    $members = $query->get();

    return Excel::download(new MembersExport($members), 'members_report_' . now()->format('Y-m-d_H-i') . '.xlsx');
}
}