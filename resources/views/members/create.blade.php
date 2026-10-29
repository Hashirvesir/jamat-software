@extends('layouts.app')

@section('title', 'Add Member')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 38px;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }

        .modal-backdrop {
            z-index: 1040 !important;
        }

        .modal {
            z-index: 1050 !important;
        }
    </style>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2">
            <h5 class="mb-0">Add New Member</h5>
            <span dir="rtl" class="fs-6 fw-bold">نیا ممبر شامل کریں</span>
        </div>
        <div class="card-body">
            <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data" id="memberForm">
                @csrf
                <input type="hidden" name="form_token" id="formToken" value="{{ (string) \Illuminate\Support\Str::uuid() }}">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="profile_picture" class="form-label d-flex justify-content-between align-items-center">
                            <span>Profile Picture</span>
                            <span dir="rtl" class="text-secondary fw-semibold">پروفائل تصویر</span>
                        </label>
                        <div class="input-group">
                            <input type="file" name="profile_picture" id="profile_picture" class="form-control d-none"
                                accept="image/jpeg,image/jpg,image/png">
                            <button type="button" class="btn btn-primary" id="addProfile">Add Profile / تصویر شامل کریں</button>
                            <input type="hidden" name="captured_image" id="capturedImage"
                                value="{{ old('captured_image') }}">
                            <small class="text-muted ms-2 align-self-center">Max size: 2MB. Allowed formats: JPG, JPEG, PNG / زیادہ سے زیادہ 2MB</small>
                        </div>
                        @error('profile_picture')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        <div id="imagePreview" class="mt-2">
                            @if (old('captured_image'))
                                <img src="{{ old('captured_image') }}" alt="Preview"
                                    style="max-width: 100px; max-height: 100px;">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label d-flex justify-content-between align-items-center">
                            <span>Name</span>
                            <span dir="rtl" class="text-secondary fw-semibold">نام</span>
                        </label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label d-flex justify-content-between align-items-center">
                            <span>Email</span>
                            <span dir="rtl" class="text-secondary fw-semibold">ای میل</span>
                        </label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ old('email') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="membership_number" class="form-label d-flex justify-content-between align-items-center">
                            <span>Membership Number <small class="text-muted fw-normal">(Auto-generated if blank)</small></span>
                            <span dir="rtl" class="text-secondary fw-semibold">ممبرشپ نمبر <small class="text-muted fw-normal">(خالی چھوڑنے پر خودکار)</small></span>
                        </label>
                        <input type="text" name="membership_number" id="membership_number" class="form-control"
                            value="{{ old('membership_number') }}" placeholder="Auto-generated e.g., SHJ-0048">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="father_name" class="form-label d-flex justify-content-between align-items-center">
                            <span>Father Name</span>
                            <span dir="rtl" class="text-secondary fw-semibold">والد کا نام</span>
                        </label>
                        <input type="text" name="father_name" id="father_name" class="form-control"
                            value="{{ old('father_name') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="grandfather_name" class="form-label d-flex justify-content-between align-items-center">
                            <span>Grandfather Name</span>
                            <span dir="rtl" class="text-secondary fw-semibold">دادا کا نام</span>
                        </label>
                        <input type="text" name="grandfather_name" id="grandfather_name" class="form-control"
                            value="{{ old('grandfather_name') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="caste" class="form-label d-flex justify-content-between align-items-center">
                            <span>Cast / Gote</span>
                            <span dir="rtl" class="text-secondary fw-semibold">قوم / گوت</span>
                        </label>
                        <input type="text" name="caste" id="caste" class="form-control"
                            value="{{ old('caste') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label d-flex justify-content-between align-items-center">
                            <span>Phone (Optional)</span>
                            <span dir="rtl" class="text-secondary fw-semibold">فون نمبر (اختیاری)</span>
                        </label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}"
                            maxlength="20" placeholder="e.g., 03244230121">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label d-flex justify-content-between align-items-center">
                            <span>Gender</span>
                            <span dir="rtl" class="text-secondary fw-semibold">جنس</span>
                        </label>
                        <select name="gender" id="gender" class="form-select">
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male / مرد</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female / عورت</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other / دیگر</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cnic" class="form-label d-flex justify-content-between align-items-center">
                            <span>CNIC (e.g., 42301-2323311-3)</span>
                            <span dir="rtl" class="text-secondary fw-semibold">شناختی کارڈ نمبر</span>
                        </label>
                        <input type="text" name="cnic" id="cnic" class="form-control"
                            value="{{ old('cnic') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="marital_status" class="form-label d-flex justify-content-between align-items-center">
                            <span>Marital Status</span>
                            <span dir="rtl" class="text-secondary fw-semibold">ازدواجی حیثیت</span>
                        </label>
                        <select name="marital_status" id="marital_status" class="form-select">
                            <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single / غیر شادی شدہ</option>
                            <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married / شادی شدہ</option>
                            <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced / طلاق یافتہ</option>
                            <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed / بیوہ</option>
                            <option value="widower" {{ old('marital_status') == 'widower' ? 'selected' : '' }}>Widower / رنڈوا</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="community_id" class="form-label d-flex justify-content-between align-items-center">
                            <span>Community</span>
                            <span dir="rtl" class="text-secondary fw-semibold">برادری / جماعت</span>
                        </label>
                        <select name="community_id" id="community_id" class="form-select">
                            @foreach ($communities as $community)
                                <option value="{{ $community->id }}"
                                    {{ old('community_id') == $community->id ? 'selected' : '' }}>{{ $community->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="member_type" class="form-label d-flex justify-content-between align-items-center">
                            <span>Member Type</span>
                            <span dir="rtl" class="text-secondary fw-semibold">ممبر کی قسم</span>
                        </label>
                        <select name="member_type" id="member_type" class="form-select">
                            <option value="householder" {{ old('member_type') == 'householder' ? 'selected' : '' }}>Householder / سربراہِ خاندان</option>
                            <option value="non_householder" {{ old('member_type') == 'non_householder' ? 'selected' : '' }}>Non-Householder / غیر سربراہ</option>
                            <option value="child" {{ old('member_type') == 'child' ? 'selected' : '' }}>Child / بچہ</option>
                            <option value="sibling" {{ old('member_type') == 'sibling' ? 'selected' : '' }}>Sibling / بہن بھائی</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="parent_id" class="form-label d-flex justify-content-between align-items-center">
                            <span>Parent (Search by CNIC or select)</span>
                            <span dir="rtl" class="text-secondary fw-semibold">والدین (شناختی کارڈ سے تلاش کریں)</span>
                        </label>
                        <select name="parent_id" id="parent_id" class="form-select select2-cnic" style="width: 100%;">
                            <option value="">None / کوئی نہیں</option>
                            @if ($selectedParent)
                                <option value="{{ $selectedParent->id }}" selected
                                    data-cnic="{{ $selectedParent->cnic }}">{{ $selectedParent->name }}
                                    ({{ $selectedParent->cnic ?? 'No CNIC' }})
                                </option>
                            @endif
                        </select>
                    </div>
                </div>

                <!-- Spouse Section (Shown when Married) -->
                <div class="card mb-4 border-primary shadow-sm" id="spouseSection" style="display: {{ old('marital_status') == 'married' ? 'block' : 'none' }};">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2">
                        <h6 class="mb-0 fw-bold">Spouse Details</h6>
                        <span dir="rtl" class="fw-semibold">شریکِ حیات کی تفصیلات</span>
                    </div>
                    <div class="card-body bg-light-subtle">
                        <div class="d-flex mb-3 gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="spouse_type" id="spouse_type_new" value="new" {{ old('spouse_type', 'new') == 'new' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="spouse_type_new">
                                    Add New Spouse / نیا شریکِ حیات شامل کریں
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="spouse_type" id="spouse_type_existing" value="existing" {{ old('spouse_type') == 'existing' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="spouse_type_existing">
                                    Select Existing Member / موجودہ ممبر منتخب کریں
                                </label>
                            </div>
                        </div>

                        <!-- Existing Spouse Selector -->
                        <div id="existingSpouseBlock" style="display: {{ old('spouse_type') == 'existing' ? 'block' : 'none' }};">
                            <div class="mb-2">
                                <label for="spouse_id" class="form-label d-flex justify-content-between align-items-center">
                                    <span>Select Spouse (Search by CNIC or Name)</span>
                                    <span dir="rtl" class="text-secondary fw-semibold">شریکِ حیات تلاش کریں</span>
                                </label>
                                <select name="spouse_id" id="spouse_id" class="form-select select2-cnic" style="width: 100%;">
                                    <option value="">None / کوئی نہیں</option>
                                    @if ($selectedSpouse)
                                        <option value="{{ $selectedSpouse->id }}" selected
                                            data-cnic="{{ $selectedSpouse->cnic }}">{{ $selectedSpouse->name }}
                                            ({{ $selectedSpouse->cnic ?? 'No CNIC' }})
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- New Spouse Form Inputs -->
                        <div id="newSpouseBlock" style="display: {{ old('spouse_type', 'new') == 'new' ? 'block' : 'none' }};">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="spouse_name" class="form-label d-flex justify-content-between align-items-center">
                                        <span>Spouse Name</span>
                                        <span dir="rtl" class="text-secondary fw-semibold">شریکِ حیات کا نام</span>
                                    </label>
                                    <input type="text" name="spouse_name" id="spouse_name" class="form-control"
                                        value="{{ old('spouse_name') }}" placeholder="Spouse Full Name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="spouse_gender" class="form-label d-flex justify-content-between align-items-center">
                                        <span>Gender</span>
                                        <span dir="rtl" class="text-secondary fw-semibold">جنس</span>
                                    </label>
                                    <select name="spouse_gender" id="spouse_gender" class="form-select">
                                        <option value="female" {{ old('spouse_gender', 'female') == 'female' ? 'selected' : '' }}>Female / عورت</option>
                                        <option value="male" {{ old('spouse_gender') == 'male' ? 'selected' : '' }}>Male / مرد</option>
                                        <option value="other" {{ old('spouse_gender') == 'other' ? 'selected' : '' }}>Other / دیگر</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="spouse_cnic" class="form-label d-flex justify-content-between align-items-center">
                                        <span>CNIC</span>
                                        <span dir="rtl" class="text-secondary fw-semibold">شناختی کارڈ نمبر</span>
                                    </label>
                                    <input type="text" name="spouse_cnic" id="spouse_cnic" class="form-control"
                                        value="{{ old('spouse_cnic') }}" placeholder="e.g., 42301-xxxxxxx-x">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="spouse_phone" class="form-label d-flex justify-content-between align-items-center">
                                        <span>Phone</span>
                                        <span dir="rtl" class="text-secondary fw-semibold">فون نمبر</span>
                                    </label>
                                    <input type="text" name="spouse_phone" id="spouse_phone" class="form-control"
                                        value="{{ old('spouse_phone') }}" placeholder="e.g., 03001234567">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="spouse_date_of_birth" class="form-label d-flex justify-content-between align-items-center">
                                        <span>Date of Birth</span>
                                        <span dir="rtl" class="text-secondary fw-semibold">تاریخِ پیدائش</span>
                                    </label>
                                    <input type="date" name="spouse_date_of_birth" id="spouse_date_of_birth" class="form-control"
                                        value="{{ old('spouse_date_of_birth') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="spouse_blood_group" class="form-label d-flex justify-content-between align-items-center">
                                        <span>Blood Group</span>
                                        <span dir="rtl" class="text-secondary fw-semibold">بلڈ گروپ</span>
                                    </label>
                                    <select name="spouse_blood_group" id="spouse_blood_group" class="form-select">
                                        <option value="">Select Blood Group / بلڈ گروپ منتخب کریں</option>
                                        <option value="A+" {{ old('spouse_blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ old('spouse_blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ old('spouse_blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ old('spouse_blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ old('spouse_blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ old('spouse_blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ old('spouse_blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ old('spouse_blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label d-flex justify-content-between align-items-center">
                            <span>Address</span>
                            <span dir="rtl" class="text-secondary fw-semibold">پتہ / رہائش</span>
                        </label>
                        <textarea name="address" id="address" class="form-control">{{ old('address') }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date_of_birth" class="form-label d-flex justify-content-between align-items-center">
                            <span>Date of Birth</span>
                            <span dir="rtl" class="text-secondary fw-semibold">تاریخِ پیدائش</span>
                        </label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control"
                            value="{{ old('date_of_birth') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="blood_group" class="form-label d-flex justify-content-between align-items-center">
                            <span>Blood Group</span>
                            <span dir="rtl" class="text-secondary fw-semibold">بلڈ گروپ</span>
                        </label>
                        <select name="blood_group" id="blood_group" class="form-select">
                            <option value="">Select Blood Group / بلڈ گروپ منتخب کریں</option>
                            <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="card_received" class="form-label d-flex justify-content-between align-items-center">
                            <span>Card Received</span>
                            <span dir="rtl" class="text-secondary fw-semibold">کارڈ وصولی تعداد</span>
                        </label>
                        <input type="number" name="card_received" id="card_received" class="form-control"
                            value="{{ old('card_received') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="b_form_number" class="form-label d-flex justify-content-between align-items-center">
                            <span>B-Form Number (e.g., 12345-6789012-3)</span>
                            <span dir="rtl" class="text-secondary fw-semibold">ب فارم نمبر</span>
                        </label>
                        <input type="text" name="b_form_number" id="b_form_number" class="form-control"
                            value="{{ old('b_form_number') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="country" class="form-label d-flex justify-content-between align-items-center">
                            <span>Country</span>
                            <span dir="rtl" class="text-secondary fw-semibold">ملک</span>
                        </label>
                        <select name="country" id="country" class="form-select">
                            <option value="">Select Country / ملک منتخب کریں</option>
                            <option value="Pakistan" {{ old('country') == 'Pakistan' ? 'selected' : '' }}>Pakistan / پاکستان</option>
                            <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India / بھارت</option>
                            <option value="United States" {{ old('country') == 'United States' ? 'selected' : '' }}>United States / امریکہ</option>
                            <option value="United Kingdom" {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom / برطانیہ</option>
                            <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada / کینیڈا</option>
                            <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia / آسٹریلیا</option>
                            <option value="Germany" {{ old('country') == 'Germany' ? 'selected' : '' }}>Germany / جرمنی</option>
                            <option value="France" {{ old('country') == 'France' ? 'selected' : '' }}>France / فرانس</option>
                            <option value="China" {{ old('country') == 'China' ? 'selected' : '' }}>China / چین</option>
                            <option value="Japan" {{ old('country') == 'Japan' ? 'selected' : '' }}>Japan / جاپان</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="death_date" class="form-label d-flex justify-content-between align-items-center">
                            <span>Death Date (Optional)</span>
                            <span dir="rtl" class="text-secondary fw-semibold">تاریخِ وفات (اختیاری)</span>
                        </label>
                        <input type="date" name="death_date" id="death_date" class="form-control"
                            value="{{ old('death_date') }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="education" class="form-label d-flex justify-content-between align-items-center">
                            <span>Education</span>
                            <span dir="rtl" class="text-secondary fw-semibold">تعلیم</span>
                        </label>
                        <input type="text" name="education" id="education" class="form-control"
                            value="{{ old('education') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="skill" class="form-label d-flex justify-content-between align-items-center">
                            <span>Skill</span>
                            <span dir="rtl" class="text-secondary fw-semibold">ہنر / مہارت</span>
                        </label>
                        <input type="text" name="skill" id="skill" class="form-control"
                            value="{{ old('skill') }}">
                    </div>
                </div>

                <!-- Children Section -->
                <div class="card mb-4 border-success shadow-sm" id="childrenSection">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center py-2">
                        <div>
                            <h6 class="mb-0 fw-bold d-inline-block">Children Details</h6>
                            <small class="ms-2 opacity-75">(Add multiple children / ایک سے زیادہ بچے شامل کریں)</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span dir="rtl" class="fw-semibold">بچوں کی تفصیلات</span>
                            <button type="button" class="btn btn-sm btn-light text-success fw-bold px-3" id="addChildBtn">
                                + Add Child / بچہ شامل کریں
                            </button>
                        </div>
                    </div>
                    <div class="card-body bg-light-subtle p-3">
                        <div id="noChildrenNotice" class="text-muted text-center py-3 {{ old('children') ? 'd-none' : '' }}">
                            No children added yet. Click <strong>"+ Add Child / بچہ شامل کریں"</strong> to add children.
                            <br><span dir="rtl" class="small">اگر اس ممبر کے بچے ہیں تو اوپر دیئے گئے بٹن "+ Add Child" پر کلک کریں۔</span>
                        </div>
                        <div id="childrenContainer">
                            @if (old('children'))
                                @foreach (old('children') as $idx => $child)
                                    <div class="child-row card mb-3 border shadow-sm bg-white" data-index="{{ $idx }}">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-1 px-3">
                                            <span class="fw-bold text-success">Child #<span class="child-number">{{ $loop->iteration }}</span></span>
                                            <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 remove-child-btn">
                                                &times; Remove / حذف کریں
                                            </button>
                                        </div>
                                        <div class="card-body py-2 px-3">
                                            <div class="row">
                                                <div class="col-md-3 mb-2">
                                                    <label class="form-label small fw-semibold">Child Name / بچے کا نام</label>
                                                    <input type="text" name="children[{{ $idx }}][name]" class="form-control form-control-sm"
                                                        value="{{ $child['name'] ?? '' }}" placeholder="Child Name">
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <label class="form-label small fw-semibold">Gender / جنس</label>
                                                    <select name="children[{{ $idx }}][gender]" class="form-select form-select-sm">
                                                        <option value="male" {{ ($child['gender'] ?? '') == 'male' ? 'selected' : '' }}>Male / لڑکا</option>
                                                        <option value="female" {{ ($child['gender'] ?? '') == 'female' ? 'selected' : '' }}>Female / لڑکی</option>
                                                        <option value="other" {{ ($child['gender'] ?? '') == 'other' ? 'selected' : '' }}>Other / دیگر</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <label class="form-label small fw-semibold">Date of Birth / تاریخِ پیدائش</label>
                                                    <input type="date" name="children[{{ $idx }}][date_of_birth]" class="form-control form-control-sm"
                                                        value="{{ $child['date_of_birth'] ?? '' }}">
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <label class="form-label small fw-semibold">B-Form / CNIC</label>
                                                    <input type="text" name="children[{{ $idx }}][b_form_number]" class="form-control form-control-sm"
                                                        value="{{ $child['b_form_number'] ?? '' }}" placeholder="e.g., 42101-xxxxxxx-x">
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <label class="form-label small fw-semibold">Blood Group / بلڈ گروپ</label>
                                                    <select name="children[{{ $idx }}][blood_group]" class="form-select form-select-sm">
                                                        <option value="">Select</option>
                                                        @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                                            <option value="{{ $bg }}" {{ ($child['blood_group'] ?? '') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label d-flex justify-content-between align-items-center">
                        <span>Description</span>
                        <span dir="rtl" class="text-secondary fw-semibold">تفصیل / اضافی معلومات</span>
                    </label>
                    <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                </div>
                <div class="mb-4 form-check d-flex justify-content-between align-items-center p-3 bg-light rounded border">
                    <div>
                        <input type="checkbox" name="is_deceased" id="is_deceased" class="form-check-input" value="1"
                            {{ old('is_deceased') ? 'checked' : '' }}>
                        <label for="is_deceased" class="form-check-label ms-1 fw-semibold">Deceased</label>
                    </div>
                    <span dir="rtl" class="text-secondary fw-semibold">انتقال شدہ / وفات</span>
                </div>
                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold" id="submitBtn">Save Member / ممبر محفوظ کریں</button>
                <a href="{{ route('members.index') }}" class="btn btn-secondary ms-2 px-3 py-2">Cancel / منسوخ کریں</a>
            </form>
        </div>
    </div>

    <!-- Profile Picture Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Add Profile Picture / تصویر شامل کریں</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-secondary w-100 mb-2" id="selectPicture">Select Picture / فائل سے منتخب کریں</button>
                    <button type="button" class="btn btn-info w-100 text-white" id="openCamera">Open Camera / کیمرہ کھولیں</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Camera Modal -->
    <div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraModalLabel">Capture Photo / تصویر کھینچیں</h5>
                    <button type="button" class="btn-close" id="cameraModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <video id="video" width="100%" height="400" autoplay playsinline></video>
                    <canvas id="canvas" style="display:none;"></canvas>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-success" id="capture">Capture / تصویر لیں</button>
                        <button type="button" class="btn btn-secondary ms-2" id="retake">Retake / دوبارہ لیں</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="usePhoto" data-bs-dismiss="modal">Use Photo / تصویر استعمال کریں</button>
                    <button type="button" class="btn btn-danger" id="cancelCamera" data-bs-dismiss="modal">Cancel / منسوخ</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Select2 initialization
            $('.select2-cnic').select2({
                placeholder: "Search by CNIC or select",
                allowClear: true,
                ajax: {
                    url: "{{ route('members.search-by-cnic') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(member) {
                                return {
                                    id: member.id,
                                    text: member.name + ' (' + (member.cnic || 'No CNIC') + ')',
                                    cnic: member.cnic
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1,
                templateResult: function(data) {
                    if (!data.cnic) {
                        return data.text;
                    }
                    return $('<span>' + data.text + '</span>');
                },
                templateSelection: function(data) {
                    return data.text || data.name;
                }
            });

            // Toggle Spouse Section based on Marital Status
            function checkMaritalStatus() {
                if ($('#marital_status').val() === 'married') {
                    $('#spouseSection').slideDown();
                } else {
                    $('#spouseSection').slideUp();
                }
            }
            $('#marital_status').on('change', checkMaritalStatus);
            checkMaritalStatus(); // initial check

            // Toggle between New Spouse vs Existing Member dropdown
            $('input[name="spouse_type"]').on('change', function() {
                if ($(this).val() === 'new') {
                    $('#newSpouseBlock').slideDown();
                    $('#existingSpouseBlock').slideUp();
                } else {
                    $('#newSpouseBlock').slideUp();
                    $('#existingSpouseBlock').slideDown();
                }
            });

            // Auto-set spouse gender opposite to main member gender
            $('#gender').on('change', function() {
                const memberGender = $(this).val();
                if (memberGender === 'male') {
                    $('#spouse_gender').val('female');
                } else if (memberGender === 'female') {
                    $('#spouse_gender').val('male');
                }
            });

            // Dynamic Children Logic
            let childCounter = {{ old('children') ? count(old('children')) : 0 }};

            $('#addChildBtn').on('click', function() {
                $('#noChildrenNotice').addClass('d-none');
                childCounter++;
                const newIndex = childCounter;

                const childHtml = `
                    <div class="child-row card mb-3 border shadow-sm bg-white" data-index="${newIndex}">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-1 px-3">
                            <span class="fw-bold text-success">Child #<span class="child-number"></span></span>
                            <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 remove-child-btn">
                                &times; Remove / حذف کریں
                            </button>
                        </div>
                        <div class="card-body py-2 px-3">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small fw-semibold">Child Name / بچے کا نام</label>
                                    <input type="text" name="children[${newIndex}][name]" class="form-control form-control-sm" placeholder="Child Name">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label small fw-semibold">Gender / جنس</label>
                                    <select name="children[${newIndex}][gender]" class="form-select form-select-sm">
                                        <option value="male">Male / لڑکا</option>
                                        <option value="female">Female / لڑکی</option>
                                        <option value="other">Other / دیگر</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small fw-semibold">Date of Birth / تاریخِ پیدائش</label>
                                    <input type="date" name="children[${newIndex}][date_of_birth]" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label small fw-semibold">B-Form / CNIC</label>
                                    <input type="text" name="children[${newIndex}][b_form_number]" class="form-control form-control-sm" placeholder="e.g., 42101-xxxxxxx-x">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label small fw-semibold">Blood Group / بلڈ گروپ</label>
                                    <select name="children[${newIndex}][blood_group]" class="form-select form-select-sm">
                                        <option value="">Select</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('#childrenContainer').append(childHtml);
                renumberChildren();
            });

            // Remove child handler
            $(document).on('click', '.remove-child-btn', function() {
                $(this).closest('.child-row').remove();
                if ($('#childrenContainer .child-row').length === 0) {
                    $('#noChildrenNotice').removeClass('d-none');
                }
                renumberChildren();
            });

            function renumberChildren() {
                $('#childrenContainer .child-row').each(function(i) {
                    $(this).find('.child-number').text(i + 1);
                });
            }
            $('#is_deceased').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#death_date').prop('disabled', false);
                } else {
                    $('#death_date').prop('disabled', true);
                    $('#death_date').val('');
                }
            });

            // Bootstrap 5 Modal Instances
            const profileModalEl = document.getElementById('profileModal');
            const cameraModalEl = document.getElementById('cameraModal');
            const profileModal = profileModalEl ? bootstrap.Modal.getOrCreateInstance(profileModalEl) : null;
            const cameraModal = cameraModalEl ? bootstrap.Modal.getOrCreateInstance(cameraModalEl) : null;

            // Profile Modal Trigger
            $('#addProfile').on('click', function() {
                if (profileModal) profileModal.show();
            });

            // Select Picture Handler
            $('#selectPicture').on('click', function() {
                $('#profile_picture').click();
            });

            $('#profile_picture').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#capturedImage').val(e.target.result);
                        $('#imagePreview').html('<img src="' + e.target.result +
                            '" alt="Preview" class="img-thumbnail" style="max-width: 120px; max-height: 120px;">');
                    };
                    reader.readAsDataURL(file);
                    if (profileModal) profileModal.hide();
                }
            });

            // Open Camera Handler
            $('#openCamera').on('click', function() {
                if (profileModal) profileModal.hide();
                if (cameraModal) cameraModal.show();
            });

            let stream = null;

            if (cameraModalEl) {
                cameraModalEl.addEventListener('shown.bs.modal', function() {
                    startCamera();
                });

                cameraModalEl.addEventListener('hidden.bs.modal', function() {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                        stream = null;
                    }
                });
            }

            function startCamera() {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    alert('Camera access is not supported in this browser.');
                    if (cameraModal) cameraModal.hide();
                    return;
                }

                const video = document.getElementById('video');
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(function(s) {
                        stream = s;
                        video.srcObject = stream;
                        video.play();
                    })
                    .catch(function(err) {
                        console.error('Camera access error: ', err);
                        alert('Unable to access camera. Please check permissions or upload a file.');
                        if (cameraModal) cameraModal.hide();
                    });
            }

            // Capture Photo (Optimized lightweight JPEG)
            $('#capture').on('click', function() {
                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const context = canvas.getContext('2d');
                
                const maxWidth = 640;
                const maxHeight = 480;
                let width = video.videoWidth || 640;
                let height = video.videoHeight || 480;
                
                if (width > maxWidth) {
                    height = Math.round((height * maxWidth) / width);
                    width = maxWidth;
                }
                if (height > maxHeight) {
                    width = Math.round((width * maxHeight) / height);
                    height = maxHeight;
                }
                
                canvas.width = width;
                canvas.height = height;
                context.drawImage(video, 0, 0, width, height);
                
                // JPEG at 0.82 quality reduces payload size from ~4MB PNG to ~45KB
                const dataUrl = canvas.toDataURL('image/jpeg', 0.82);
                $('#capturedImage').val(dataUrl);
                $('#imagePreview').html('<img src="' + dataUrl +
                    '" alt="Preview" class="img-thumbnail" style="max-width: 120px; max-height: 120px;">');
            });

            // Retake Photo
            $('#retake').on('click', function() {
                startCamera();
            });

            // Double submission prevention & robust form submission
            let isSubmitting = false;
            $('#memberForm').on('submit', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }

                const memberName = $.trim($('#name').val());
                if (!memberName) {
                    e.preventDefault();
                    alert('Please enter member name / براہ کرم ممبر کا نام درج کریں');
                    $('#name').focus();
                    return false;
                }

                isSubmitting = true;
                const $btn = $('#submitBtn');

                // Defer button disable so browser dispatches the submit POST event smoothly
                setTimeout(function() {
                    $btn.addClass('disabled').css({ 'pointer-events': 'none', 'opacity': '0.75' });
                    $btn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Saving Member... / ممبر محفوظ ہو رہا ہے...');
                }, 10);

                // Safety timeout: re-enable after 25 seconds in case of slow connection/offline
                setTimeout(function() {
                    isSubmitting = false;
                    $btn.removeClass('disabled').css({ 'pointer-events': 'auto', 'opacity': '1' });
                    $btn.html('Save Member / ممبر محفوظ کریں');
                }, 25000);
            });

            // Re-enable button on back/forward browser navigation
            window.addEventListener('pageshow', function() {
                isSubmitting = false;
                const $btn = $('#submitBtn');
                $btn.removeClass('disabled').css({ 'pointer-events': 'auto', 'opacity': '1' });
                $btn.html('Save Member / ممبر محفوظ کریں');
            });
        });
    </script>
@endsection
