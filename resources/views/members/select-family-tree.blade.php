@extends('layouts.app')

@section('title', 'Select Family Tree')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Select Member for Family Tree</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form method="GET" action="{{ route('members.select-family-tree') }}">
                    <label for="search" class="form-label">Search by Name, CNIC or B-Form Number</label>
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control"
                            placeholder="Enter name, CNIC or B-Form number" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Search</button>
                        @if (request('search'))
                            <a href="{{ route('members.select-family-tree') }}"
                                class="btn btn-outline-secondary">Clear</a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>CNIC</th>
                            <th>B-Form Number</th>
                            <th>Community</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->cnic ?? 'N/A' }}</td>
                                <td>{{ $member->b_form_number ?? 'N/A' }}</td>
                                <td>{{ $member->community->name }}</td>
                                <td>{{ ucfirst($member->member_type) }}</td>
                                <td>
                                    <a href="{{ route('members.family-tree', $member) }}"
                                        class="btn btn-primary btn-sm">View Family Tree</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No members found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-4">
                {{ $members->links('pagination::bootstrap-5') }}
            </div>
            <a href="{{ route('members.index') }}" class="btn btn-secondary mt-3">Back to Members</a>
        </div>
    </div>
@endsection
