@extends('layouts.app')

@section('title', 'Member Details')

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
    $maritalStatusLabels = [
        'single' => 'Single / غیر شادی شدہ',
        'married' => 'Married / شادی شدہ',
        'divorced' => 'Divorced / طلاق یافتہ',
        'widowed' => 'Widowed / بیوہ',
        'widower' => 'Widower / رنڈوا',
    ];
    $genderLabels = [
        'male' => 'Male / مرد',
        'female' => 'Female / عورت',
        'other' => 'Other / دیگر',
    ];
@endphp

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
            <div>
                <h5 class="mb-0 fw-bold">{{ $member->name }} ({{ $member->community?->name ?? 'N/A' }})</h5>
                <small dir="rtl" class="text-white-50">ممبر کی مکمل تفصیلات اور فیملی روابط</small>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('members.family-tree', $member) }}" class="btn btn-warning btn-sm fw-semibold">
                    <i class="bi bi-diagram-3"></i> Family Tree / شجرۂ نسب
                </a>
                <a href="{{ route('members.download-pdf', $member) }}" class="btn btn-light btn-sm">
                    Download PDF / پی ڈی ایف
                </a>
                <a href="{{ route('members.card', $member) }}" class="btn btn-light btn-sm">
                    ID Card / شناختی کارڈ
                </a>
                @can('edit members')
                    <a href="{{ route('members.edit', $member) }}" class="btn btn-outline-light btn-sm">
                        Edit / ترمیم
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center mb-3">
                    @if ($member->profile_picture)
                        <img src="{{ asset('profiles/' . $member->profile_picture) }}" alt="{{ $member->name }}"
                            class="img-fluid rounded-circle border border-2 shadow-sm" style="width: 140px; height: 140px; object-fit: cover;">
                    @else
                        <img src="{{ asset('image/default-profile.jpg') }}" alt="Default Profile Picture"
                            class="img-fluid rounded-circle border border-2 shadow-sm" style="width: 140px; height: 140px; object-fit: cover;">
                    @endif
                    <div class="mt-2">
                        <span class="badge bg-primary px-3 py-1">{{ $memberTypeLabels[$member->member_type] ?? ucfirst(str_replace('_', ' ', (string)$member->member_type)) }}</span>
                        @if ($member->is_deceased)
                            <span class="badge bg-danger ms-1 px-2 py-1">Deceased / وفات شدہ</span>
                        @else
                            <span class="badge bg-success ms-1 px-2 py-1">Active / فعال</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <p class="mb-2"><strong>Name / نام:</strong> <span class="fs-6 fw-bold text-dark">{{ $member->name }}</span></p>
                    <p class="mb-2"><strong>Membership Number / ممبرشپ نمبر:</strong> {{ $member->membership_number ?? 'N/A' }}</p>
                    <p class="mb-2">
                        <strong>Father / والد:</strong>
                        @if ($father)
                            <a href="{{ route('members.show', $father->id) }}" class="fw-bold text-primary text-decoration-none">
                                {{ $father->name }} &rarr;
                            </a>
                            @if ($member->father_name && $member->father_name !== $father->name)
                                <span class="text-muted small">({{ $member->father_name }})</span>
                            @endif
                        @elseif ($member->father_name)
                            <span>{{ $member->father_name }}</span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </p>
                    <p class="mb-2">
                        <strong>Mother / والدہ:</strong>
                        @if ($mother)
                            <a href="{{ route('members.show', $mother->id) }}" class="fw-bold text-primary text-decoration-none">
                                {{ $mother->name }} &rarr;
                            </a>
                        @else
                            <span class="text-muted">None / کوئی نہیں</span>
                        @endif
                    </p>
                    <p class="mb-2"><strong>Grandfather / دادا کا نام:</strong> {{ $member->grandfather_name ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Cast / Gote / قوم / گوت:</strong> {{ $member->caste ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Gender / جنس:</strong> {{ $genderLabels[$member->gender] ?? ucfirst((string)$member->gender) }}</p>
                    <p class="mb-2"><strong>CNIC / شناختی کارڈ:</strong> {{ $member->cnic ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>B-Form Number / ب فارم نمبر:</strong> {{ $member->b_form_number ?? 'N/A' }}</p>
                </div>

                <div class="col-md-5">
                    <p class="mb-2"><strong>Community / برادری:</strong> {{ $member->community?->name ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Marital Status / ازدواجی حیثیت:</strong> {{ $maritalStatusLabels[$member->marital_status] ?? ucfirst((string)$member->marital_status) }}</p>
                    <p class="mb-2">
                        <strong>Spouse / شریکِ حیات:</strong>
                        @if ($member->spouse)
                            <a href="{{ route('members.show', $member->spouse->id) }}" class="fw-bold text-primary text-decoration-none">
                                {{ $member->spouse->name }} &rarr;
                            </a>
                        @else
                            <span class="text-muted">None / کوئی نہیں</span>
                        @endif
                    </p>
                    <p class="mb-2">
                        <strong>Linked Parent Record / والدین ریکارڈ:</strong>
                        @if ($member->parent)
                            <a href="{{ route('members.show', $member->parent->id) }}" class="fw-bold text-primary text-decoration-none">
                                {{ $member->parent->name }} &rarr;
                            </a>
                        @else
                            <span class="text-muted">None / کوئی نہیں</span>
                        @endif
                    </p>
                    <p class="mb-2"><strong>Date of Birth / تاریخِ پیدائش:</strong> {{ $member->date_of_birth ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Blood Group / بلڈ گروپ:</strong> {{ $member->blood_group ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Phone / فون نمبر:</strong> {{ $member->phone ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Email / ای میل:</strong> {{ $member->email ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Country / ملک:</strong> {{ $member->country ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Education / تعلیم:</strong> {{ $member->education ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Skill / ہنر:</strong> {{ $member->skill ?? 'N/A' }}</p>
                    @if ($member->is_deceased)
                        <p class="mb-2 text-danger"><strong>Death Date / تاریخِ وفات:</strong> {{ $member->death_date ?? 'N/A' }}</p>
                    @endif
                </div>
            </div>

            <div class="row mt-3 pt-3 border-top">
                <div class="col-md-12">
                    <p class="mb-1"><strong>Address / پتہ:</strong> {{ $member->address ?? 'N/A' }}</p>
                    @if ($member->description)
                        <p class="mb-1"><strong>Description / تفصیل:</strong> {{ $member->description }}</p>
                    @endif
                </div>
            </div>

            <!-- Spouse Details -->
            <div class="mt-4 pt-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0 text-primary fw-bold">
                        <i class="bi bi-heart-fill text-danger me-1"></i> Spouse Details / شریکِ حیات کی تفصیلات
                    </h5>
                    @if ($member->spouse)
                        <a href="{{ route('members.show', $member->spouse->id) }}" class="btn btn-sm btn-outline-primary">
                            View Spouse Profile / شریکِ حیات کا پروفائل دیکھیں &rarr;
                        </a>
                    @endif
                </div>

                @if ($member->spouse)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Name / نام</th>
                                    <th>CNIC / شناختی کارڈ</th>
                                    <th>Gender / جنس</th>
                                    <th>Phone / فون نمبر</th>
                                    <th>Member Type / ممبر کی قسم</th>
                                    <th>Status / حالت</th>
                                    <th>Date of Birth / تاریخِ پیدائش</th>
                                    <th>Country / ملک</th>
                                    <th class="text-center">Action / عمل</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <a href="{{ route('members.show', $member->spouse->id) }}" class="fw-bold text-primary text-decoration-underline">
                                            {{ $member->spouse->name }}
                                        </a>
                                    </td>
                                    <td>{{ $member->spouse->cnic ?? 'N/A' }}</td>
                                    <td>{{ $genderLabels[$member->spouse->gender] ?? ucfirst((string)$member->spouse->gender) }}</td>
                                    <td>{{ $member->spouse->phone ?? 'N/A' }}</td>
                                    <td>{{ $memberTypeLabels[$member->spouse->member_type] ?? ucfirst(str_replace('_', ' ', (string)$member->spouse->member_type)) }}</td>
                                    <td>
                                        @if ($member->spouse->is_deceased)
                                            <span class="badge bg-danger">Deceased / وفات</span>
                                        @else
                                            <span class="badge bg-success">Active / حیات</span>
                                        @endif
                                    </td>
                                    <td>{{ $member->spouse->date_of_birth ?? 'N/A' }}</td>
                                    <td>{{ $member->spouse->country ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('members.show', $member->spouse->id) }}" class="btn btn-sm btn-primary">
                                            View / تفصیلات
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-light border py-2 text-muted mb-0">
                        <strong>Spouse / شریکِ حیات:</strong> None / کوئی نہیں
                    </div>
                @endif
            </div>

            <!-- Children Details -->
            <div class="mt-4 pt-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0 text-success fw-bold">
                        <i class="bi bi-people-fill me-1"></i> Children Details / بچوں کی تفصیلات
                        <span class="badge bg-success rounded-pill ms-1">{{ $familyChildren->count() }}</span>
                    </h5>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>Name / نام</th>
                                <th>CNIC / B-Form / شناختی کارڈ / ب فارم</th>
                                <th>Gender / جنس</th>
                                <th>Blood Group / بلڈ گروپ</th>
                                <th>Date of Birth / تاریخِ پیدائش</th>
                                <th>Member Type / قسم</th>
                                <th>Status / حالت</th>
                                <th class="text-center">Action / عمل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($familyChildren as $index => $child)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ route('members.show', $child->id) }}" class="fw-bold text-success text-decoration-underline">
                                            {{ $child->name }}
                                        </a>
                                    </td>
                                    <td>{{ $child->cnic ?? ($child->b_form_number ?? 'N/A') }}</td>
                                    <td>{{ $genderLabels[$child->gender] ?? ucfirst((string)$child->gender) }}</td>
                                    <td>{{ $child->blood_group ?? 'N/A' }}</td>
                                    <td>{{ $child->date_of_birth ?? 'N/A' }}</td>
                                    <td>{{ $memberTypeLabels[$child->member_type] ?? ucfirst(str_replace('_', ' ', (string)$child->member_type)) }}</td>
                                    <td>
                                        @if ($child->is_deceased)
                                            <span class="badge bg-danger">Deceased</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('members.show', $child->id) }}" class="btn btn-sm btn-outline-success">
                                            View / تفصیلات
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-3">
                                        No children found. / کوئی بچہ درج نہیں ہے۔
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Siblings Details (If applicable) -->
            @if ($siblings && $siblings->isNotEmpty())
                <div class="mt-4 pt-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0 text-info fw-bold">
                            <i class="bi bi-diagram-2 me-1"></i> Siblings / بہن بھائیوں کی تفصیلات
                            <span class="badge bg-info text-dark rounded-pill ms-1">{{ $siblings->count() }}</span>
                        </h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px;">#</th>
                                    <th>Name / نام</th>
                                    <th>CNIC / B-Form / شناختی کارڈ / ب فارم</th>
                                    <th>Gender / جنس</th>
                                    <th>Blood Group / بلڈ گروپ</th>
                                    <th>Date of Birth / تاریخِ پیدائش</th>
                                    <th class="text-center">Action / عمل</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siblings as $index => $sibling)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('members.show', $sibling->id) }}" class="fw-bold text-info text-decoration-underline">
                                                {{ $sibling->name }}
                                            </a>
                                        </td>
                                        <td>{{ $sibling->cnic ?? ($sibling->b_form_number ?? 'N/A') }}</td>
                                        <td>{{ $genderLabels[$sibling->gender] ?? ucfirst((string)$sibling->gender) }}</td>
                                        <td>{{ $sibling->blood_group ?? 'N/A' }}</td>
                                        <td>{{ $sibling->date_of_birth ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('members.show', $sibling->id) }}" class="btn btn-sm btn-outline-info">
                                                View / تفصیلات
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <a href="{{ route('members.index') }}" class="btn btn-secondary">
                    &larr; Back to Members / واپس ممبرز لسٹ
                </a>
                <a href="{{ route('members.family-tree', $member) }}" class="btn btn-warning text-dark fw-semibold">
                    <i class="bi bi-diagram-3"></i> View Family Tree / مکمل شجرۂ نسب دیکھیں &rarr;
                </a>
            </div>
        </div>
    </div>
@endsection
