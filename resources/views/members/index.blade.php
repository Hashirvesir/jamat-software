@extends('layouts.app')

@section('title', 'Members List')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Members</h5>
            @can('create members')
                <a href="{{ route('members.create') }}" class="btn btn-light btn-sm">Add New Member</a>
            @endcan
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="cnic_search" class="form-label">Search by CNIC</label>
                <input type="text" id="cnic_search" class="form-control" placeholder="Enter CNIC to search">
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>CNIC / B-Form</th>
                            <th>Community</th>
                            <th>Type</th>
                            <th>Parent</th>
                            <th>Spouse</th>
                            <th>Deceased</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email ?? 'N/A' }}</td>
                                <td>{{ $member->phone ?? 'N/A' }}</td>
                                <td>{{ ucfirst($member->gender) }}</td>
                                <td>{{ $member->cnic ?? ($member->b_form_number ?? 'N/A') }}</td>
                                <td>{{ $member->community->name }}</td>
                                <td>{{ ucfirst($member->member_type) }}</td>
                                <td>{{ $member->parent ? $member->parent->name : 'None' }}</td>
                                <td>{{ $member->spouse ? $member->spouse->name : 'None' }}</td>
                                <td>{{ $member->is_deceased ? 'Yes' : 'No' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm" type="button"
                                            id="dropdownMenuButton{{ $member->id }}" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                <path
                                                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                            </svg>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $member->id }}">
                                            @can('view members')
                                                <li><a class="dropdown-item"
                                                        href="{{ route('members.show', $member) }}">View</a></li>
                                            @endcan
                                            @can('edit members')
                                                <li><a class="dropdown-item"
                                                        href="{{ route('members.edit', $member) }}">Edit</a></li>
                                            @endcan
                                            @can('view members')
                                                <li><a class="dropdown-item"
                                                        href="{{ route('members.family-tree', $member) }}">Family Tree</a></li>
                                            @endcan
                                            @can('delete members')
                                                <li>
                                                    <form action="{{ route('members.destroy', $member) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure you want to delete {{ $member->name }}?')">Delete</button>
                                                    </form>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No members found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-4">
                {{ $members->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('cnic_search').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                const cnic = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                row.style.display = cnic.includes(searchValue) || searchValue === '' ? '' : 'none';
            });
        });
    </script>
@endsection
