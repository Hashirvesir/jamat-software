@extends('layouts.app')

@section('title', 'Members List')

@section('content')
    @php
        $activeFilterCount = 0;
        if (request('community_id')) $activeFilterCount++;
        if (request('member_type')) $activeFilterCount++;
        if (request('gender')) $activeFilterCount++;
        if (request('marital_status')) $activeFilterCount++;
        if (request('blood_group')) $activeFilterCount++;
        if (request('is_deceased') !== null && request('is_deceased') !== '') $activeFilterCount++;
    @endphp

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Members Directory</h5>
                <small class="text-white-50" dir="rtl">ارکان کی فہرست و تلاش</small>
            </div>
            @can('create members')
                <a href="{{ route('members.create') }}" class="btn btn-light btn-sm fw-semibold">
                    <i class="bi bi-person-plus-fill me-1"></i> Add New Member / نیا ممبر شامل کریں
                </a>
            @endcan
        </div>

        <div class="card-body bg-light-subtle pb-2">
            <!-- Search & Filter Form -->
            <form action="{{ route('members.index') }}" method="GET" id="searchFilterForm">
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-lg-7 col-md-12">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" id="main_search" class="form-control"
                                placeholder="Search by Name, Phone, Member # (e.g. 0103, 103), or CNIC..."
                                value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('members.index', request()->except('search')) }}" class="btn btn-outline-secondary" title="Clear search">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                Search / تلاش
                            </button>
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-12 d-flex justify-content-lg-end gap-2 flex-wrap">
                        <button type="button" class="btn btn-outline-primary position-relative fw-semibold" data-bs-toggle="collapse" data-bs-target="#filterPanel" aria-expanded="{{ $activeFilterCount > 0 ? 'true' : 'false' }}">
                            <i class="bi bi-funnel me-1"></i> Filters / فلٹرز
                            @if($activeFilterCount > 0)
                                <span class="badge bg-danger rounded-pill ms-1">{{ $activeFilterCount }}</span>
                            @endif
                        </button>

                        @if(request('search') || $activeFilterCount > 0)
                            <a href="{{ route('members.index') }}" class="btn btn-outline-danger">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset / سب صاف کریں
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Collapsible Advanced Filters -->
                <div class="collapse {{ $activeFilterCount > 0 ? 'show' : '' }}" id="filterPanel">
                    <div class="card card-body bg-white border mt-2 mb-2 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                            <span class="fw-bold text-secondary small text-uppercase">
                                <i class="bi bi-sliders me-1"></i> Filter By Specific Criteria / فلٹر کریں
                            </span>
                            @if($activeFilterCount > 0)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $activeFilterCount }} active filter(s)</span>
                            @endif
                        </div>

                        <div class="row g-2">
                            <!-- Community -->
                            <div class="col-md-4 col-sm-6">
                                <label class="form-label small fw-semibold text-muted mb-1">Community / برادری</label>
                                <select name="community_id" class="form-select form-select-sm">
                                    <option value="">All Communities / تمام برادریاں</option>
                                    @foreach($communities as $comm)
                                        <option value="{{ $comm->id }}" {{ request('community_id') == $comm->id ? 'selected' : '' }}>
                                            {{ $comm->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Member Type -->
                            <div class="col-md-4 col-sm-6">
                                <label class="form-label small fw-semibold text-muted mb-1">Member Type / ممبر کی قسم</label>
                                <select name="member_type" class="form-select form-select-sm">
                                    <option value="">All Types / تمام اقسام</option>
                                    <option value="householder" {{ request('member_type') == 'householder' ? 'selected' : '' }}>Householder / سربراہِ خاندان</option>
                                    <option value="non_householder" {{ request('member_type') == 'non_householder' ? 'selected' : '' }}>Non-Householder / غیر سربراہ</option>
                                    <option value="child" {{ request('member_type') == 'child' ? 'selected' : '' }}>Child / بچہ</option>
                                    <option value="sibling" {{ request('member_type') == 'sibling' ? 'selected' : '' }}>Sibling / بہن بھائی</option>
                                </select>
                            </div>

                            <!-- Gender -->
                            <div class="col-md-4 col-sm-6">
                                <label class="form-label small fw-semibold text-muted mb-1">Gender / جنس</label>
                                <select name="gender" class="form-select form-select-sm">
                                    <option value="">All Genders / تمام</option>
                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male / مرد</option>
                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female / عورت</option>
                                    <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other / دیگر</option>
                                </select>
                            </div>

                            <!-- Marital Status -->
                            <div class="col-md-4 col-sm-6">
                                <label class="form-label small fw-semibold text-muted mb-1">Marital Status / ازدواجی حیثیت</label>
                                <select name="marital_status" class="form-select form-select-sm">
                                    <option value="">All Marital Statuses / تمام</option>
                                    <option value="single" {{ request('marital_status') == 'single' ? 'selected' : '' }}>Single / غیر شادی شدہ</option>
                                    <option value="married" {{ request('marital_status') == 'married' ? 'selected' : '' }}>Married / شادی شدہ</option>
                                    <option value="divorced" {{ request('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced / طلاق یافتہ</option>
                                    <option value="widowed" {{ request('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed / بیوہ</option>
                                    <option value="widower" {{ request('marital_status') == 'widower' ? 'selected' : '' }}>Widower / رنڈوا</option>
                                </select>
                            </div>

                            <!-- Blood Group -->
                            <div class="col-md-4 col-sm-6">
                                <label class="form-label small fw-semibold text-muted mb-1">Blood Group / بلڈ گروپ</label>
                                <select name="blood_group" class="form-select form-select-sm">
                                    <option value="">All Blood Groups / تمام</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                        <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Life Status (Deceased) -->
                            <div class="col-md-4 col-sm-6">
                                <label class="form-label small fw-semibold text-muted mb-1">Life Status / حیات یا وفات</label>
                                <select name="is_deceased" class="form-select form-select-sm">
                                    <option value="">All / تمام</option>
                                    <option value="0" {{ request('is_deceased') === '0' ? 'selected' : '' }}>Living (Active) / بقید حیات</option>
                                    <option value="1" {{ request('is_deceased') === '1' ? 'selected' : '' }}>Deceased / وفات شدہ</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3 pt-2 border-top">
                            <a href="{{ route('members.index', ['search' => request('search')]) }}" class="btn btn-sm btn-outline-secondary">
                                Reset Filters / فلٹرز ری سیٹ کریں
                            </a>
                            <button type="submit" class="btn btn-sm btn-primary px-3 fw-semibold">
                                <i class="bi bi-check-lg me-1"></i> Apply Filters / فلٹر لاگو کریں
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Results Summary Count -->
            <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top">
                <div class="small text-muted">
                    Showing <strong>{{ $members->firstItem() ?? 0 }}</strong> - <strong>{{ $members->lastItem() ?? 0 }}</strong> of <strong>{{ $members->total() }}</strong> members
                    @if(request('search'))
                        <span class="ms-1">matching "<strong>{{ request('search') }}</strong>"</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="ps-3">Member #</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>CNIC / B-Form</th>
                            <th>Community</th>
                            <th>Type</th>
                            <th>Parent</th>
                            <th>Spouse</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr>
                                <td class="ps-3">
                                    <span class="badge bg-dark font-monospace px-2 py-1">{{ $member->membership_number }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('members.show', $member) }}" class="fw-bold text-primary text-decoration-none" title="Click to view details">
                                        {{ $member->name }}
                                    </a>
                                </td>
                                <td>{{ $member->phone ?? '—' }}</td>
                                <td>{{ ucfirst((string)$member->gender) }}</td>
                                <td>{{ $member->cnic ?? ($member->b_form_number ?? '—') }}</td>
                                <td>{{ $member->community?->name ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark border">
                                        {{ ucfirst(str_replace('_', ' ', (string)$member->member_type)) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($member->parent)
                                        <a href="{{ route('members.show', $member->parent->id) }}" class="text-decoration-none">
                                            {{ $member->parent->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($member->spouse)
                                        <a href="{{ route('members.show', $member->spouse->id) }}" class="text-decoration-none">
                                            {{ $member->spouse->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($member->is_deceased)
                                        <span class="badge bg-danger">Deceased</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <div class="d-inline-flex align-items-center gap-1">
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
                                <td colspan="11" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-1"></i>
                                    No members found matching your search or filters. / کوئی ممبر نہیں ملا۔
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center p-3 border-top">
                {{ $members->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
