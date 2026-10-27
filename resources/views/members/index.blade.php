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
                <table class="table table-striped table-hover align-middle">
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
                                <td>
                                    <a href="{{ route('members.show', $member) }}" class="fw-bold text-primary text-decoration-none" title="Click to view details">
                                        {{ $member->name }}
                                    </a>
                                </td>
                                <td>{{ $member->email ?? 'N/A' }}</td>
                                <td>{{ $member->phone ?? 'N/A' }}</td>
                                <td>{{ ucfirst((string)$member->gender) }}</td>
                                <td>{{ $member->cnic ?? ($member->b_form_number ?? 'N/A') }}</td>
                                <td>{{ $member->community?->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', (string)$member->member_type)) }}</td>
                                <td>
                                    @if ($member->parent)
                                        <a href="{{ route('members.show', $member->parent->id) }}" class="text-decoration-none">
                                            {{ $member->parent->name }}
                                        </a>
                                    @else
                                        None
                                    @endif
                                </td>
                                <td>
                                    @if ($member->spouse)
                                        <a href="{{ route('members.show', $member->spouse->id) }}" class="text-decoration-none">
                                            {{ $member->spouse->name }}
                                        </a>
                                    @else
                                        None
                                    @endif
                                </td>
                                <td>{{ $member->is_deceased ? 'Yes' : 'No' }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        @can('view members')
                                            <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-outline-primary py-1 px-2" title="View Member Details / تفصیلات دیکھیں">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        @endcan
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm py-1 px-2" type="button"
                                                id="dropdownMenuButton{{ $member->id }}" data-bs-toggle="dropdown"
                                                data-bs-boundary="window" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuButton{{ $member->id }}">
                                                @can('view members')
                                                    <li><a class="dropdown-item" href="{{ route('members.show', $member) }}"><i class="bi bi-eye text-primary me-2"></i>View Details / تفصیلات</a></li>
                                                @endcan
                                                @can('edit members')
                                                    <li><a class="dropdown-item" href="{{ route('members.edit', $member) }}"><i class="bi bi-pencil text-warning me-2"></i>Edit / ترمیم</a></li>
                                                @endcan
                                                @can('view members')
                                                    <li><a class="dropdown-item" href="{{ route('members.family-tree', $member) }}"><i class="bi bi-diagram-3 text-info me-2"></i>Family Tree / شجرۂ نسب</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('members.card', $member) }}"><i class="bi bi-person-badge text-success me-2"></i>ID Card / شناختی کارڈ</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('members.download-pdf', $member) }}"><i class="bi bi-file-earmark-pdf text-danger me-2"></i>Download PDF / پی ڈی ایف</a></li>
                                                @endcan
                                                @can('delete members')
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('members.destroy', $member) }}" method="POST"
                                                            style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                onclick="return confirm('Are you sure you want to delete {{ $member->name }}?')">
                                                                <i class="bi bi-trash me-2"></i>Delete / حذف
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
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
