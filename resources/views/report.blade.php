@extends('layouts.app')

@section('title', 'Members Report')

@section('content')
    @php
        $activeFilterCount = 0;
        if (request('community_id')) $activeFilterCount++;
        if (request('member_type')) $activeFilterCount++;
        if (request('gender')) $activeFilterCount++;
        if (request('marital_status')) $activeFilterCount++;
        if (request('blood_group')) $activeFilterCount++;
        if (request('is_deceased') !== null && request('is_deceased') !== '') $activeFilterCount++;
        if (request('education')) $activeFilterCount++;
        if (request('skill')) $activeFilterCount++;
    @endphp

    <div class="container-fluid px-3">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h3 class="mb-0 text-primary fw-bold"><i class="bi bi-file-earmark-bar-graph me-2"></i>Members Report</h3>
                <small class="text-muted" dir="rtl">جامع ارکان کی رپورٹ، فلٹرز اور ایکسل ایکسپورٹ</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('members.report.export', request()->query()) }}" class="btn btn-success fw-semibold shadow-sm">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export to Excel / ایکسل ایکسپورٹ
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search and Filter Bar -->
        <div class="card shadow-sm mb-3 border-0 bg-light-subtle">
            <div class="card-body py-3">
                <form action="{{ route('members.report') }}" method="GET" id="reportSearchForm">
                    <!-- Preserve existing modal filter values inside hidden inputs if search is submitted from top bar -->
                    @if(request('community_id')) <input type="hidden" name="community_id" value="{{ request('community_id') }}"> @endif
                    @if(request('member_type')) <input type="hidden" name="member_type" value="{{ request('member_type') }}"> @endif
                    @if(request('gender')) <input type="hidden" name="gender" value="{{ request('gender') }}"> @endif
                    @if(request('marital_status')) <input type="hidden" name="marital_status" value="{{ request('marital_status') }}"> @endif
                    @if(request('blood_group')) <input type="hidden" name="blood_group" value="{{ request('blood_group') }}"> @endif
                    @if(request('is_deceased') !== null && request('is_deceased') !== '') <input type="hidden" name="is_deceased" value="{{ request('is_deceased') }}"> @endif
                    @if(request('education')) <input type="hidden" name="education" value="{{ request('education') }}"> @endif
                    @if(request('skill')) <input type="hidden" name="skill" value="{{ request('skill') }}"> @endif

                    <div class="row g-2 align-items-center">
                        <div class="col-lg-7 col-md-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Search by Name, Phone, Member # (e.g. 0103, 103), or CNIC..."
                                    value="{{ request('search') ?: request('cnic_search') }}">
                                @if(request('search') || request('cnic_search'))
                                    <a href="{{ route('members.report', request()->except(['search', 'cnic_search'])) }}" class="btn btn-outline-secondary" title="Clear Search">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-primary px-4 fw-semibold">Search / تلاش</button>
                            </div>
                        </div>

                        <div class="col-lg-5 col-md-12 d-flex justify-content-lg-end gap-2 flex-wrap">
                            <button type="button" class="btn btn-primary position-relative fw-semibold" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="bi bi-funnel me-1"></i> Apply Filters / فلٹرز
                                @if($activeFilterCount > 0)
                                    <span class="badge bg-danger rounded-pill ms-1">{{ $activeFilterCount }}</span>
                                @endif
                            </button>

                            @if(request('search') || request('cnic_search') || $activeFilterCount > 0)
                                <a href="{{ route('members.report') }}" class="btn btn-outline-danger">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset All / سب صاف کریں
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- Active Filter Tags & Count -->
                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top flex-wrap gap-2">
                    <div class="small text-muted">
                        Total Matching Members: <strong>{{ $members->total() }}</strong>
                        @if(request('search') || request('cnic_search'))
                            <span class="badge bg-secondary ms-1">Search: {{ request('search') ?: request('cnic_search') }}</span>
                        @endif
                        @if($activeFilterCount > 0)
                            <span class="badge bg-primary ms-1">{{ $activeFilterCount }} filter(s) active</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Members Table with Horizontal Scroll -->
        <div class="card shadow-sm border-0">
            <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="ps-3">Member #</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>CNIC</th>
                            <th>B-Form Number</th>
                            <th>Community</th>
                            <th>Member Type</th>
                            <th>Marital Status</th>
                            <th>Blood Group</th>
                            <th>Status</th>
                            <th>Address</th>
                            <th>Date of Birth</th>
                            <th>Education</th>
                            <th>Skill</th>
                            <th>Email</th>
                            <th>Death Date</th>
                            <th>Country</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr>
                                <td class="ps-3">
                                    <span class="badge bg-dark font-monospace px-2 py-1">{{ $member->membership_number }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('members.show', $member) }}" class="fw-bold text-primary text-decoration-none">
                                        {{ $member->name }}
                                    </a>
                                </td>
                                <td>{{ $member->phone ?? '—' }}</td>
                                <td>{{ ucfirst((string)$member->gender) }}</td>
                                <td>{{ $member->cnic ?? '—' }}</td>
                                <td>{{ $member->b_form_number ?? '—' }}</td>
                                <td>{{ $member->community->name ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark border">
                                        {{ ucwords(str_replace('_', ' ', $member->member_type)) }}
                                    </span>
                                </td>
                                <td>{{ ucfirst((string)$member->marital_status) }}</td>
                                <td>
                                    @if($member->blood_group)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">{{ $member->blood_group }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if ($member->is_deceased)
                                        <span class="badge bg-danger">Deceased</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td>{{ $member->address ?? '—' }}</td>
                                <td>{{ $member->date_of_birth ?? '—' }}</td>
                                <td>{{ $member->education ?? '—' }}</td>
                                <td>{{ $member->skill ?? '—' }}</td>
                                <td>{{ $member->email ?? '—' }}</td>
                                <td>{{ $member->death_date ?? '—' }}</td>
                                <td>{{ $member->country ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="18" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-1"></i>
                                    No members found matching your search or filters. / کوئی ممبر نہیں ملا۔
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-center p-3 border-top">
                {{ $members->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <!-- Filter Modal -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="filterModalLabel">
                            <i class="bi bi-funnel me-1"></i> Apply Detailed Filters / تفصیلی فلٹرز
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('members.report') }}" method="GET" id="filterForm">
                        <div class="modal-body">
                            <!-- Retain text search parameter -->
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="community_id" class="form-label fw-semibold">Community / برادری</label>
                                    <select name="community_id" id="community_id" class="form-select">
                                        <option value="">All Communities / تمام برادریاں</option>
                                        @foreach ($communities as $community)
                                            <option value="{{ $community->id }}"
                                                {{ request()->input('community_id') == $community->id ? 'selected' : '' }}>
                                                {{ $community->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="member_type" class="form-label fw-semibold">Member Type / ممبر کی قسم</label>
                                    <select name="member_type" id="member_type" class="form-select">
                                        <option value="">All Member Types / تمام اقسام</option>
                                        <option value="householder" {{ request('member_type') == 'householder' ? 'selected' : '' }}>Householder / سربراہِ خاندان</option>
                                        <option value="non_householder" {{ request('member_type') == 'non_householder' ? 'selected' : '' }}>Non-Householder / غیر سربراہ</option>
                                        <option value="child" {{ request('member_type') == 'child' ? 'selected' : '' }}>Child / بچہ</option>
                                        <option value="sibling" {{ request('member_type') == 'sibling' ? 'selected' : '' }}>Sibling / بہن بھائی</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="gender" class="form-label fw-semibold">Gender / جنس</label>
                                    <select name="gender" id="gender" class="form-select">
                                        <option value="">All Genders / تمام</option>
                                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male / مرد</option>
                                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female / عورت</option>
                                        <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other / دیگر</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="marital_status" class="form-label fw-semibold">Marital Status / ازدواجی حیثیت</label>
                                    <select name="marital_status" id="marital_status" class="form-select">
                                        <option value="">All Marital Statuses / تمام</option>
                                        <option value="single" {{ request('marital_status') == 'single' ? 'selected' : '' }}>Single / غیر شادی شدہ</option>
                                        <option value="married" {{ request('marital_status') == 'married' ? 'selected' : '' }}>Married / شادی شدہ</option>
                                        <option value="divorced" {{ request('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced / طلاق یافتہ</option>
                                        <option value="widowed" {{ request('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed / بیوہ</option>
                                        <option value="widower" {{ request('marital_status') == 'widower' ? 'selected' : '' }}>Widower / رنڈوا</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="blood_group" class="form-label fw-semibold">Blood Group / بلڈ گروپ</label>
                                    <select name="blood_group" id="blood_group" class="form-select">
                                        <option value="">All Blood Groups / تمام</option>
                                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                            <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="is_deceased" class="form-label fw-semibold">Life Status / حیات یا وفات</label>
                                    <select name="is_deceased" id="is_deceased" class="form-select">
                                        <option value="">All / تمام</option>
                                        <option value="0" {{ request('is_deceased') === '0' ? 'selected' : '' }}>Living (Active) / بقید حیات</option>
                                        <option value="1" {{ request('is_deceased') === '1' ? 'selected' : '' }}>Deceased / وفات شدہ</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="education" class="form-label fw-semibold">Education / تعلیم</label>
                                    <input type="text" name="education" id="education" class="form-control"
                                        value="{{ request('education') }}" placeholder="e.g. Matric, Graduate">
                                </div>

                                <div class="col-md-6">
                                    <label for="skill" class="form-label fw-semibold">Skill / ہنر</label>
                                    <input type="text" name="skill" id="skill" class="form-control"
                                        value="{{ request('skill') }}" placeholder="e.g. Electrician, Tailor">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <a href="{{ route('members.report', ['search' => request('search')]) }}" class="btn btn-outline-secondary">
                                Reset Filters / ری سیٹ کریں
                            </a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                <i class="bi bi-check-lg me-1"></i> Apply Filters / فلٹرز لاگو کریں
                            </button>
                        </div>
                    </form>
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

        th, td {
            white-space: nowrap;
        }
    </style>
@endsection
