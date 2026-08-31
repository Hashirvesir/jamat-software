@extends('layouts.app')

@section('title', 'Communities List')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Communities</h5>
            @can('create communities')
                <a href="{{ route('communities.create') }}" class="btn btn-light btn-sm">Add New Community</a>
            @endcan
        </div>
        <div class="card-body">
            <div class="mb-3">
                <input type="text" id="searchCommunity" class="form-control" placeholder="Search by Community Name..."
                    style="max-width: 300px;">
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="communitiesTable">
                    <thead>
                        <tr>
                            <th>Community Name</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>Child</th>
                            <th>Deceased</th>
                            <th>Total Members</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="communitiesBody">
                        @forelse($communities as $community)
                            <tr>
                                <td>{{ $community->name }}</td>
                                <td>{{ $community->male_count }}</td>
                                <td>{{ $community->female_count }}</td>
                                <td>{{ $community->child_count }}</td>
                                <td>{{ $community->deceased_count }}</td>
                                <td>{{ $community->members_count }}</td>
                                <td>
                                    @can('edit communities')
                                        <a href="{{ route('communities.edit', $community) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                    @endcan
                                    @can('delete communities')
                                        <form action="{{ route('communities.destroy', $community) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete {{ $community->name }}?')">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No communities found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <a href="{{ route('members.index') }}" class="btn btn-secondary mt-3">Back to Members</a>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchCommunity').on('input', function() {
                let query = $(this).val();
                if (query.length > 2 || query.length === 0) { // Search after 3 characters or clear
                    $.ajax({
                        url: '{{ route('communities.index') }}',
                        method: 'GET',
                        data: {
                            search: query
                        },
                        success: function(response) {
                            let tbody = $('#communitiesBody');
                            tbody.empty();
                            if (response.communities.length > 0) {
                                response.communities.forEach(community => {
                                    let actions = '';
                                    @can('edit communities')
                                        actions +=
                                            `<a href="{{ url('communities') }}/${community.id}/edit" class="btn btn-sm btn-primary">Edit</a>`;
                                    @endcan
                                    @can('delete communities')
                                        actions += `
                                            <form action="{{ url('communities') }}/${community.id}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete ${community.name}?')">Delete</button>
                                            </form>
                                        `;
                                    @endcan
                                    tbody.append(`
                                        <tr>
                                            <td>${community.name}</td>
                                            <td>${community.male_count}</td>
                                            <td>${community.female_count}</td>
                                            <td>${community.child_count}</td>
                                            <td>${community.deceased_count}</td>
                                            <td>${community.members_count}</td>
                                            <td>${actions}</td>
                                        </tr>
                                    `);
                                });
                            } else {
                                tbody.append(
                                    '<tr><td colspan="7" class="text-center">No communities found.</td></tr>'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('An error occurred while searching.');
                        }
                    });
                }
            });
        });
    </script>
@endsection
