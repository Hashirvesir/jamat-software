@extends('layouts.app')

@section('title', 'Member Details')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-0">Member Details: {{ $member->name }} ({{ $member->community->name }})</h5>
                <small dir="rtl" class="text-white-50">ممبر کی مکمل تفصیلات</small>
            </div>
            <div>
                <a href="{{ route('members.download-pdf', $member) }}" class="btn btn-light btn-sm me-2">Download PDF / پی ڈی ایف</a>
                <a href="{{ route('members.card', $member) }}" class="btn btn-light btn-sm">Download ID Card / شناختی کارڈ</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    @if ($member->profile_picture)
                        <img src="{{ asset('profiles/' . $member->profile_picture) }}" alt="{{ $member->name }}"
                            class="img-fluid rounded-circle border border-gray" style="width: 150px; height: 150px;">
                    @else
                        <img src="{{ asset('image/default-profile.jpg') }}" alt="Default Profile Picture"
                            class="img-fluid rounded-circle border border-gray" style="width: 150px; height: 150px;">
                    @endif
                </div>
                <div class="col-md-4">
                    <p><strong>Name / نام:</strong> {{ $member->name }}</p>
                    <p><strong>Membership Number / ممبرشپ نمبر:</strong> {{ $member->membership_number ?? 'N/A' }}</p>
                    <p><strong>Father Name / والد کا نام:</strong> {{ $member->father_name ?? 'N/A' }}</p>
                    <p><strong>Grandfather Name / دادا کا نام:</strong> {{ $member->grandfather_name ?? 'N/A' }}</p>
                    <p><strong>Cast / Gote / قوم / گوت:</strong> {{ $member->caste ?? 'N/A' }}</p>
                    <p><strong>Email / ای میل:</strong> {{ $member->email ?? 'N/A' }}</p>
                    <p><strong>Phone / فون نمبر:</strong> {{ $member->phone ?? 'N/A' }}</p>
                    <p><strong>Gender / جنس:</strong> {{ $member->gender === 'male' ? 'Male / مرد' : ($member->gender === 'female' ? 'Female / عورت' : 'Other / دیگر') }}</p>
                    <p><strong>CNIC / شناختی کارڈ:</strong> {{ $member->cnic ?? 'N/A' }}</p>
                    <p><strong>Date of Birth / تاریخِ پیدائش:</strong> {{ $member->date_of_birth ?? 'N/A' }}</p>
                    <p><strong>Blood Group / بلڈ گروپ:</strong> {{ $member->blood_group ?? 'N/A' }}</p>
                    <p><strong>Card Received / کارڈ وصولی:</strong> {{ $member->card_received ?? 'N/A' }}</p>
                    <p><strong>B-Form Number / ب فارم نمبر:</strong> {{ $member->b_form_number ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4">
@php
    $memberTypeLabels = [
        'householder' => 'Householder / سربراہِ خاندان',
        'family_member' => 'Family Member / خاندان کا فرد',
        'child' => 'Child / بچہ',
        'spouse' => 'Spouse / شریکِ حیات',
        'sibling' => 'Sibling / بہن بھائی',
    ];
    $maritalStatusLabels = [
        'single' => 'Single / غیر شادی شدہ',
        'married' => 'Married / شادی شدہ',
        'divorced' => 'Divorced / طلاق یافتہ',
        'widowed' => 'Widowed / بیوہ / رنڈوا',
    ];
    $genderLabels = [
        'male' => 'Male / مرد',
        'female' => 'Female / عورت',
        'other' => 'Other / دیگر',
    ];
@endphp
                    <p><strong>Community / برادری:</strong> {{ $member->community->name }}</p>
                    <p><strong>Member Type / ممبر کی قسم:</strong> {{ $memberTypeLabels[$member->member_type] ?? ucfirst(str_replace('_', ' ', $member->member_type)) }}</p>
                    <p><strong>Marital Status / ازدواجی حیثیت:</strong> {{ $maritalStatusLabels[$member->marital_status] ?? ucfirst($member->marital_status) }}</p>
                    <p><strong>Parent / والدین:</strong> {{ $member->parent ? $member->parent->name : 'None / کوئی نہیں' }}</p>
                    <p><strong>Deceased / وفات:</strong> {{ $member->is_deceased ? 'Yes / جی ہاں' : 'No / نہیں' }}</p>
                    <p><strong>Death Date / تاریخِ وفات:</strong> {{ $member->death_date ?? 'N/A' }}</p>
                    <p><strong>Country / ملک:</strong> {{ $member->country ?? 'N/A' }}</p>
                    <p><strong>Education / تعلیم:</strong> {{ $member->education ?? 'N/A' }}</p>
                    <p><strong>Skill / ہنر:</strong> {{ $member->skill ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <p><strong>Address / پتہ:</strong> {{ $member->address ?? 'N/A' }}</p>
                    <p><strong>Description / تفصیل:</strong> {{ $member->description ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Spouse Details -->
            @if ($member->spouse)
                <h5 class="mt-4">Spouse Details / شریکِ حیات کی تفصیلات</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name / نام</th>
                                <th>CNIC / شناختی کارڈ</th>
                                <th>Gender / جنس</th>
                                <th>Phone / فون نمبر</th>
                                <th>Email / ای میل</th>
                                <th>Member Type / ممبر کی قسم</th>
                                <th>Deceased / وفات</th>
                                <th>Date of Birth / تاریخِ پیدائش</th>
                                <th>Death Date / تاریخِ وفات</th>
                                <th>Country / ملک</th>
                                <th>Education / تعلیم</th>
                                <th>Skill / ہنر</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $member->spouse->name }}</td>
                                <td>{{ $member->spouse->cnic ?? 'N/A' }}</td>
                                <td>{{ $genderLabels[$member->spouse->gender] ?? ucfirst($member->spouse->gender) }}</td>
                                <td>{{ $member->spouse->phone ?? 'N/A' }}</td>
                                <td>{{ $member->spouse->email ?? 'N/A' }}</td>
                                <td>{{ $memberTypeLabels[$member->spouse->member_type] ?? ucfirst(str_replace('_', ' ', $member->spouse->member_type)) }}</td>
                                <td>{{ $member->spouse->is_deceased ? 'Yes / جی ہاں' : 'No / نہیں' }}</td>
                                <td>{{ $member->spouse->date_of_birth ?? 'N/A' }}</td>
                                <td>{{ $member->spouse->death_date ?? 'N/A' }}</td>
                                <td>{{ $member->spouse->country ?? 'N/A' }}</td>
                                <td>{{ $member->spouse->education ?? 'N/A' }}</td>
                                <td>{{ $member->spouse->skill ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mt-4"><strong>Spouse / شریکِ حیات:</strong> None / کوئی نہیں</p>
            @endif

            <!-- Children Details -->
            <h5 class="mt-4">Children Details / بچوں کی تفصیلات</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name / نام</th>
                            <th>CNIC/B-Form / شناختی کارڈ / ب فارم</th>
                            <th>Gender / جنس</th>
                            <th>Phone / فون نمبر</th>
                            <th>Member Type / ممبر کی قسم</th>
                            <th>Deceased / وفات</th>
                            <th>Date of Birth / تاریخِ پیدائش</th>
                            <th>Country / ملک</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($member->children as $child)
                            <tr>
                                <td>{{ $child->name }}</td>
                                <td>{{ $child->cnic ?? $child->b_form_number ?? 'N/A' }}</td>
                                <td>{{ $genderLabels[$child->gender] ?? ucfirst($child->gender) }}</td>
                                <td>{{ $child->phone ?? 'N/A' }}</td>
                                <td>{{ $memberTypeLabels[$child->member_type] ?? ucfirst(str_replace('_', ' ', $child->member_type)) }}</td>
                                <td>{{ $child->is_deceased ? 'Yes / جی ہاں' : 'No / نہیں' }}</td>
                                <td>{{ $child->date_of_birth ?? 'N/A' }}</td>
                                <td>{{ $child->country ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No children found. / کوئی بچہ درج نہیں ہے۔</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('members.index') }}" class="btn btn-secondary mt-3">Back to Members / واپس ممبرز لسٹ</a>
        </div>
    </div>
@endsection
