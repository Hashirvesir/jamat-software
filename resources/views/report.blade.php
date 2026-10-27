@extends('layouts.app')

@section('title', 'Members Report')

@section('content')
    <div class="container">
        <h1 class="mb-4">Members Report</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search and Filter Form -->
        <form class="d-block d-md-flex flex-md-wrap align-items-md-center justify-content-md-between mb-3"
            action="{{ route('members.report') }}" method="GET">
            <div class="input-group w-100 w-md-50 mb-3 mb-md-0">
                <input type="text" name="cnic_search" class="form-control" placeholder="Search by CNIC"
                    value="{{ request()->input('cnic_search') }}">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
            </div>
            <div class="d-flex flex-wrap align-items-center">
                <button type="button" class="btn btn-primary mb-3 me-2" data-bs-toggle="modal"
                    data-bs-target="#filterModal">
                    Apply Filters
                </button>
                <a href="{{ route('members.report.export', request()->query()) }}" class="btn btn-success mb-3">Export to Excel</a>
            </div>
        </form>

        <!-- Members Table with Horizontal Scroll -->
        <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>CNIC</th>
                        <th>Community</th>
                        <th>Marital Status</th>
                        <th>Blood Group</th>
                        <th>Education</th>
                        <th>Skill</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Member Type</th>
                        <th>Address</th>
                        <th>Date of Birth</th>
                        <th>Is Deceased</th>
                        <th>Death Date</th>
                        <th>B-Form Number</th>
                        <th>Country</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        <tr>
                            <td>
                                <a href="{{ route('members.show', $member) }}" class="fw-bold text-primary text-decoration-none">
                                    {{ $member->name }}
                                </a>
                            </td>
                            <td>{{ $member->cnic ?? 'N/A' }}</td>
                            <td>{{ $member->community->name ?? 'N/A' }}</td>
                            <td>{{ $member->marital_status }}</td>
                            <td>{{ $member->blood_group ?? 'N/A' }}</td>
                            <td>{{ $member->education }}</td>
                            <td>{{ $member->skill }}</td>
                            <td>{{ $member->email ?? 'N/A' }}</td>
                            <td>{{ $member->phone ?? 'N/A' }}</td>
                            <td>{{ $member->gender }}</td>
                            <td>{{ $member->member_type }}</td>
                            <td>{{ $member->address ?? 'N/A' }}</td>
                            <td>{{ $member->date_of_birth ?? 'N/A' }}</td>
                            <td>{{ $member->is_deceased ? 'Yes' : 'No' }}</td>
                            <td>{{ $member->death_date ?? 'N/A' }}</td>
                            <td>{{ $member->b_form_number ?? 'N/A' }}</td>
                            <td>{{ $member->country }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="17" class="text-center">No members found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $members->links() }}

        <!-- Filter Modal -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Apply Filters</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('members.report') }}" method="GET" id="filterForm">
                            <div class="mb-3">
                                <label for="community_id" class="form-label">Community</label>
                                <select name="community_id" id="community_id" class="form-control">
                                    <option value="">Select Community</option>
                                    @foreach ($communities as $community)
                                        <option value="{{ $community->id }}"
                                            {{ request()->input('community_id') == $community->id ? 'selected' : '' }}>
                                            {{ $community->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="member_type" class="form-label">Member Type</label>
                                <select name="member_type" id="member_type" class="form-control">
                                    <option value="">Select Member Type</option>
                                    <option value="householder"
                                        {{ request()->input('member_type') == 'householder' ? 'selected' : '' }}>
                                        Householder</option>
                                    <option value="non_householder"
                                        {{ request()->input('member_type') == 'non_householder' ? 'selected' : '' }}>
                                        Non-Householder</option>
                                    <option value="child"
                                        {{ request()->input('member_type') == 'child' ? 'selected' : '' }}>Child</option>
                                    <option value="sibling"
                                        {{ request()->input('member_type') == 'sibling' ? 'selected' : '' }}>Sibling
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deceased</label>
                                <input type="checkbox" name="is_deceased" id="is_deceased" value="1"
                                    {{ request()->input('is_deceased') == '1' ? 'checked' : '' }}>
                            </div>
                            <div class="mb-3">
                                <label for="marital_status" class="form-label">Marital Status</label>
                                <select name="marital_status" id="marital_status" class="form-control">
                                    <option value="">Select Marital Status</option>
                                    <option value="single"
                                        {{ request()->input('marital_status') == 'single' ? 'selected' : '' }}>Single
                                    </option>
                                    <option value="married"
                                        {{ request()->input('marital_status') == 'married' ? 'selected' : '' }}>Married
                                    </option>
                                    <option value="divorced"
                                        {{ request()->input('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced
                                    </option>
                                    <option value="widowed"
                                        {{ request()->input('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed
                                    </option>
                                    <option value="widower"
                                        {{ request()->input('marital_status') == 'widower' ? 'selected' : '' }}>Widower
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="blood_group" class="form-label">Blood Group</label>
                                <select name="blood_group" id="blood_group" class="form-control">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+" {{ request()->input('blood_group') == 'A+' ? 'selected' : '' }}>
                                        A+</option>
                                    <option value="A-" {{ request()->input('blood_group') == 'A-' ? 'selected' : '' }}>
                                        A-</option>
                                    <option value="B+" {{ request()->input('blood_group') == 'B+' ? 'selected' : '' }}>
                                        B+</option>
                                    <option value="B-" {{ request()->input('blood_group') == 'B-' ? 'selected' : '' }}>
                                        B-</option>
                                    <option value="AB+"
                                        {{ request()->input('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-"
                                        {{ request()->input('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                    <option value="O+"
                                        {{ request()->input('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-"
                                        {{ request()->input('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="education" class="form-label">Education</label>
                                <input type="text" name="education" id="education" class="form-control"
                                    value="{{ request()->input('education') }}" placeholder="Enter education">
                            </div>
                            <div class="mb-3">
                                <label for="skill" class="form-label">Skill</label>
                                <input type="text" name="skill" id="skill" class="form-control"
                                    value="{{ request()->input('skill') }}" placeholder="Enter skill">
                            </div>
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        th,
        td {
            white-space: nowrap;
            min-width: 100px;
        }
    </style>
@endsection
