@extends('layouts.app')

@section('title', 'Select Family Tree')

@section('content')
    @php
        $memberTypeLabels = [
            'householder' => 'Householder / سربراہِ خاندان',
            'non_householder' => 'Non-Householder / غیر سربراہ',
            'family_member' => 'Family Member / خاندان کا فرد',
            'child' => 'Child / بچہ',
            'spouse' => 'Spouse / شریکِ حیات',
            'sibling' => 'Sibling / بہن بھائی',
        ];
    @endphp
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-0"><i class="bi bi-diagram-3-fill me-2"></i>Select Member for Family Tree</h5>
                <small class="text-white-50" dir="rtl">شجرہ نسب کے لیے ممبر منتخب کریں</small>
            </div>
            <a href="{{ route('members.index') }}" class="btn btn-light btn-sm fw-semibold">
                <i class="bi bi-people me-1"></i> Back to Members / واپس ممبرز لسٹ
            </a>
        </div>
        <div class="card-body bg-light-subtle pb-3">
            <form method="GET" action="{{ route('members.select-family-tree') }}">
                <div class="row g-2 align-items-center">
                    <div class="col-lg-6 col-md-12">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Search by Name, Phone, Member # (e.g. 0103, 103), or CNIC..."
                                value="{{ request('search') }}">
                            @if (request('search'))
                                <a href="{{ route('members.select-family-tree', request()->except('search')) }}"
                                    class="btn btn-outline-secondary" title="Clear Search"><i class="bi bi-x-lg"></i></a>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <select name="community_id" class="form-select" onchange="this.form.submit()">
                            <option value="">All Communities / تمام برادریاں</option>
                            @foreach ($communities as $comm)
                                <option value="{{ $comm->id }}" {{ request('community_id') == $comm->id ? 'selected' : '' }}>
                                    {{ $comm->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 fw-semibold">
                            <i class="bi bi-search me-1"></i> Search / تلاش
                        </button>
                        @if (request('search') || request('community_id'))
                            <a href="{{ route('members.select-family-tree') }}"
                                class="btn btn-outline-danger">Reset / ری سیٹ</a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top small text-muted">
                <div>
                    Total Members: <strong>{{ $members->total() }}</strong>
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
                            <th class="ps-3">Member # / ممبر نمبر</th>
                            <th>Name / نام</th>
                            <th>Phone / فون</th>
                            <th>CNIC / شناختی کارڈ</th>
                            <th>Community / برادری</th>
                            <th>Type / قسم</th>
                            <th>Status / حالت</th>
                            <th class="text-end pe-3">Action / عمل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr>
                                <td class="ps-3">
                                    <span class="badge bg-dark font-monospace px-2 py-1">{{ $member->membership_number }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('members.show', $member) }}" class="fw-bold text-primary text-decoration-none" title="View details">
                                        {{ $member->name }}
                                    </a>
                                </td>
                                <td>{{ $member->phone ?? '—' }}</td>
                                <td>{{ $member->cnic ?? ($member->b_form_number ?? '—') }}</td>
                                <td>{{ $member->community?->name ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark border">
                                        {{ $memberTypeLabels[$member->member_type] ?? ucfirst(str_replace('_', ' ', $member->member_type)) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($member->is_deceased)
                                        <span class="badge bg-danger">Deceased</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('members.family-tree', $member) }}"
                                        class="btn btn-primary btn-sm fw-semibold shadow-sm">
                                        <i class="bi bi-diagram-3 me-1"></i> Family Tree / شجرہ نسب
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-1"></i>
                                    No members found matching your search. / کوئی ممبر نہیں ملا۔
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
