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
                            <span>Name <span class="text-danger">*</span></span>
                            <span dir="rtl" class="text-secondary fw-semibold">نام <span class="text-danger">*</span></span>
                        </label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                            required>
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
                            <span>Membership Number</span>
                            <span dir="rtl" class="text-secondary fw-semibold">ممبرشپ نمبر</span>
                        </label>
                        <input type="text" name="membership_number" id="membership_number" class="form-control"
                            value="{{ old('membership_number') }}" placeholder="e.g., 09-0030">
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
                            <span>Gender <span class="text-danger">*</span></span>
                            <span dir="rtl" class="text-secondary fw-semibold">جنس <span class="text-danger">*</span></span>
                        </label>
                        <select name="gender" id="gender" class="form-select" required>
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
                            value="{{ old('cnic') }}" pattern="\d{5}-\d{7}-\d{1}"
                            title="CNIC must be in format xxxxx-xxxxxxx-x">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="marital_status" class="form-label d-flex justify-content-between align-items-center">
                            <span>Marital Status <span class="text-danger">*</span></span>
                            <span dir="rtl" class="text-secondary fw-semibold">ازدواجی حیثیت <span class="text-danger">*</span></span>
                        </label>
                        <select name="marital_status" id="marital_status" class="form-select" required>
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
                            <span>Community <span class="text-danger">*</span></span>
                            <span dir="rtl" class="text-secondary fw-semibold">برادری / جماعت <span class="text-danger">*</span></span>
                        </label>
                        <select name="community_id" id="community_id" class="form-select" required>
                            @foreach ($communities as $community)
                                <option value="{{ $community->id }}"
                                    {{ old('community_id') == $community->id ? 'selected' : '' }}>{{ $community->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="member_type" class="form-label d-flex justify-content-between align-items-center">
                            <span>Member Type <span class="text-danger">*</span></span>
                            <span dir="rtl" class="text-secondary fw-semibold">ممبر کی قسم <span class="text-danger">*</span></span>
                        </label>
                        <select name="member_type" id="member_type" class="form-select" required>
                            <option value="householder" {{ old('member_type') == 'householder' ? 'selected' : '' }}>Householder / سربراہِ خاندان</option>
                            <option value="non_householder" {{ old('member_type') == 'non_householder' ? 'selected' : '' }}>Non-Householder / غیر سربراہ</option>
                            <option value="child" {{ old('member_type') == 'child' ? 'selected' : '' }}>Child / بچہ</option>
                            <option value="sibling" {{ old('member_type') == 'sibling' ? 'selected' : '' }}>Sibling / بہن بھائی</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="parent_id" class="form-label d-flex justify-content-between align-items-center">
                            <span>Parent (Search by CNIC or select)</span>
                            <span dir="rtl" class="text-secondary fw-semibold">والدین (شناختی کارڈ سے تلاش کریں)</span>
                        </label>
                        <select name="parent_id" id="parent_id" class="form-select select2-cnic">
                            <option value="">None / کوئی نہیں</option>
                            @if ($selectedParent)
                                <option value="{{ $selectedParent->id }}" selected
                                    data-cnic="{{ $selectedParent->cnic }}">{{ $selectedParent->name }}
                                    ({{ $selectedParent->cnic ?? 'No CNIC' }})
                                </option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="spouse_id" class="form-label d-flex justify-content-between align-items-center">
                            <span>Spouse (Search by CNIC or select)</span>
                            <span dir="rtl" class="text-secondary fw-semibold">شریکِ حیات (شناختی کارڈ سے تلاش کریں)</span>
                        </label>
                        <select name="spouse_id" id="spouse_id" class="form-select select2-cnic">
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
                            <span>Date of Birth <span class="text-danger">*</span></span>
                            <span dir="rtl" class="text-secondary fw-semibold">تاریخِ پیدائش <span class="text-danger">*</span></span>
                        </label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control"
                            value="{{ old('date_of_birth') }}" required>
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
                            value="{{ old('card_received') }}" min="1">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="b_form_number" class="form-label d-flex justify-content-between align-items-center">
                            <span>B-Form Number (e.g., 12345-6789012-3)</span>
                            <span dir="rtl" class="text-secondary fw-semibold">ب فارم نمبر</span>
                        </label>
                        <input type="text" name="b_form_number" id="b_form_number" class="form-control"
                            value="{{ old('b_form_number') }}" pattern="\d{5}-\d{7}-\d{1}"
                            title="B-Form Number must be in format xxxxx-xxxxxxx-x">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="country" class="form-label d-flex justify-content-between align-items-center">
                            <span>Country <span class="text-danger">*</span></span>
                            <span dir="rtl" class="text-secondary fw-semibold">ملک <span class="text-danger">*</span></span>
                        </label>
                        <select name="country" id="country" class="form-select" required>
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
                            <span>Education <span class="text-danger">*</span></span>
                            <span dir="rtl" class="text-secondary fw-semibold">تعلیم <span class="text-danger">*</span></span>
                        </label>
                        <input type="text" name="education" id="education" class="form-control"
                            value="{{ old('education') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="skill" class="form-label d-flex justify-content-between align-items-center">
                            <span>Skill <span class="text-danger">*</span></span>
                            <span dir="rtl" class="text-secondary fw-semibold">ہنر / مہارت <span class="text-danger">*</span></span>
                        </label>
                        <input type="text" name="skill" id="skill" class="form-control"
                            value="{{ old('skill') }}" required>
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
                <button type="submit" class="btn btn-primary">Save Member / ممبر محفوظ کریں</button>
                <a href="{{ route('members.index') }}" class="btn btn-secondary ms-2">Cancel / منسوخ کریں</a>
            </form>
        </div>
    </div>

    <!-- Profile Picture Modal -->
    <div class="modal fade" id="profileModal" aria-labelledby="profileModalLabel" aria-hidden="true" inert>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Add Profile Picture</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-secondary w-100 mb-2" id="selectPicture">Select
                        Picture</button>
                    <button type="button" class="btn btn-info w-100" id="openCamera">Open Camera</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Camera Modal -->
    <div class="modal fade" id="cameraModal" aria-labelledby="cameraModalLabel" aria-hidden="true" inert>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraModalLabel">Capture Photo</h5>
                    <button type="button" class="btn-close" id="cameraModalClose" data-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <video id="video" width="100%" height="400" autoplay></video>
                    <canvas id="canvas" style="display:none;"></canvas>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-success" id="capture">Capture</button>
                        <button type="button" class="btn btn-secondary ml-2" id="retake">Retake</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="usePhoto">Use Photo</button>
                    <button type="button" class="btn btn-danger" id="cancelCamera">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/wicg-inert@3.1.2/dist/inert.min.js"></script>
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

            // CNIC validation
            $('#cnic').on('input', function() {
                const cnic = $(this).val();
                const cnicRegex = /^\d{5}-\d{7}-\d{1}$/;
                if (cnic && !cnicRegex.test(cnic)) {
                    $(this).addClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                    $(this).after(
                        '<div class="invalid-feedback">CNIC must be in format xxxxx-xxxxxxx-x</div>');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                }
            });

            // Phone validation (Optional)
            $('#phone').on('input', function() {
                const phone = $(this).val();
                if (phone && phone.length > 20) {
                    $(this).addClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                    $(this).after(
                        '<div class="invalid-feedback">Phone number is too long</div>');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                }
            });

            // B-Form Number validation
            $('#b_form_number').on('input', function() {
                const bForm = $(this).val();
                const bFormRegex = /^\d{5}-\d{7}-\d{1}$/;
                if (bForm && !bFormRegex.test(bForm)) {
                    $(this).addClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                    $(this).after(
                        '<div class="invalid-feedback">B-Form Number must be in format xxxxx-xxxxxxx-x</div>'
                    );
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                }
            });

            // Death Date enable/disable logic (Optional)
            $('#is_deceased').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#death_date').prop('disabled', false);
                    $('#death_date').prop('required', false);
                } else {
                    $('#death_date').prop('disabled', true);
                    $('#death_date').prop('required', false);
                    $('#death_date').val('');
                }
            });

            // Member type change logic - clear errors if child
            $('#member_type').on('change', function() {
                if ($(this).val() === 'child') {
                    $('#cnic').removeClass('is-invalid');
                    $('#b_form_number').removeClass('is-invalid');
                    $('#cnic').next('.invalid-feedback').remove();
                    $('#b_form_number').next('.invalid-feedback').remove();
                }
            });

            // CNIC or B-Form validation (at least one required, except for child)
            $('#memberForm').on('submit', function(e) {
                const cnic = $('#cnic').val();
                const bForm = $('#b_form_number').val();
                const memberType = $('#member_type').val();
                const isChild = memberType === 'child';
                const cnicRegex = /^\d{5}-\d{7}-\d{1}$/;
                const bFormRegex = /^\d{5}-\d{7}-\d{1}$/;

                if (!isChild && !cnic && !bForm) {
                    e.preventDefault();
                    $('#cnic').addClass('is-invalid');
                    $('#b_form_number').addClass('is-invalid');
                    $('#cnic').next('.invalid-feedback').remove();
                    $('#b_form_number').next('.invalid-feedback').remove();
                    $('#cnic').after(
                        '<div class="invalid-feedback">Either CNIC or B-Form Number is required.</div>'
                    );
                    $('#b_form_number').after(
                        '<div class="invalid-feedback">Either CNIC or B-Form Number is required.</div>'
                    );
                } else if (cnic && !cnicRegex.test(cnic)) {
                    e.preventDefault();
                    $('#cnic').addClass('is-invalid');
                    $('#cnic').next('.invalid-feedback').remove();
                    $('#cnic').after(
                        '<div class="invalid-feedback">CNIC must be in format xxxxx-xxxxxxx-x</div>'
                    );
                } else if (bForm && !bFormRegex.test(bForm)) {
                    e.preventDefault();
                    $('#b_form_number').addClass('is-invalid');
                    $('#b_form_number').next('.invalid-feedback').remove();
                    $('#b_form_number').after(
                        '<div class="invalid-feedback">B-Form Number must be in format xxxxx-xxxxxxx-x</div>'
                    );
                } else {
                    $('#cnic').removeClass('is-invalid');
                    $('#b_form_number').removeClass('is-invalid');
                    $('#cnic').next('.invalid-feedback').remove();
                    $('#b_form_number').next('.invalid-feedback').remove();
                }
            });

            // Profile Modal Trigger
            $('#addProfile').on('click', function() {
                $('#profileModal').modal('show');
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
                            '" alt="Preview" style="max-width: 100px; max-height: 100px;">');
                    };
                    reader.readAsDataURL(file);
                    $('#profileModal').modal('hide');
                }
            });

            // Open Camera Handler
            $('#openCamera').on('click', function() {
                $('#profileModal').modal('hide');
                $('#cameraModal').modal('show');
            });

            // Modal accessibility handlers
            $('#cameraModal').on('show.bs.modal', function() {
                $(this).removeAttr('inert').removeAttr('aria-hidden');
            });

            $('#cameraModal').on('shown.bs.modal', function() {
                $('#capture').focus();
                trapFocus($(this));
                startCamera();
            });

            $('#cameraModal').on('hidden.bs.modal', function() {
                $(this).attr('inert', '').attr('aria-hidden', 'true');
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
                $('#openCamera').focus();
            });

            $('#profileModal').on('show.bs.modal', function() {
                $(this).removeAttr('inert').removeAttr('aria-hidden');
            });

            $('#profileModal').on('shown.bs.modal', function() {
                $('#selectPicture').focus();
                trapFocus($(this));
            });

            $('#profileModal').on('hidden.bs.modal', function() {
                $(this).attr('inert', '').attr('aria-hidden', 'true');
                $('#addProfile').focus();
            });

            // Trap focus in modal
            function trapFocus(modal) {
                const focusableElements = modal.find(
                    'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                const firstFocusable = focusableElements.first();
                const lastFocusable = focusableElements.last();

                modal.on('keydown', function(e) {
                    if (e.key === 'Tab') {
                        if (e.shiftKey) {
                            if (document.activeElement === firstFocusable[0]) {
                                e.preventDefault();
                                lastFocusable.focus();
                            }
                        } else {
                            if (document.activeElement === lastFocusable[0]) {
                                e.preventDefault();
                                firstFocusable.focus();
                            }
                        }
                    }
                });
            }

            let stream = null;

            function startCamera() {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    alert('Camera access is not supported in this browser.');
                    $('#cameraModal').modal('hide');
                    return;
                }

                if (!window.isSecureContext) {
                    alert(
                        'Camera access requires a secure context (HTTPS or localhost). Please access this page over HTTPS.'
                        );
                    $('#cameraModal').modal('hide');
                    return;
                }

                const video = document.getElementById('video');
                navigator.mediaDevices.getUserMedia({
                        video: true
                    })
                    .then(function(s) {
                        stream = s;
                        video.srcObject = stream;
                        video.onloadedmetadata = function() {
                            video.play();
                        };
                    })
                    .catch(function(err) {
                        console.error('Camera access error: ', err);
                        let errorMessage = 'Unable to access the camera. Please allow camera permissions.';
                        if (err.name === 'NotAllowedError') {
                            errorMessage =
                                'Camera access was denied. Please enable camera permissions in your browser settings and try again.';
                        } else if (err.name === 'NotFoundError') {
                            errorMessage =
                                'No camera found. Please ensure a camera is connected and try again.';
                        } else if (err.name === 'SecurityError') {
                            errorMessage = 'Camera access requires a secure context (HTTPS or localhost).';
                        }
                        alert(errorMessage);
                        $('#cameraModal').modal('hide');
                    });
            }

            // Capture Photo
            $('#capture').on('click', function() {
                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                const dataUrl = canvas.toDataURL('image/png');
                $('#capturedImage').val(dataUrl);
                $('#imagePreview').html('<img src="' + dataUrl +
                    '" alt="Preview" style="max-width: 100px; max-height: 100px;">');
            });

            // Retake Photo
            $('#retake').on('click', function() {
                startCamera();
            });

            // Use Photo
            $('#usePhoto').on('click', function() {
                $('#cameraModal').modal('hide');
            });

            // Cancel Camera
            $('#cancelCamera').on('click', function() {
                $('#cameraModal').modal('hide');
            });

            // Close Button Handler
            $('#cameraModalClose').on('click', function() {
                $('#cameraModal').modal('hide');
            });

            // Form submission handler to handle base64 image
            $('#memberForm').on('submit', function(e) {
                const capturedImage = $('#capturedImage').val();
                if (capturedImage && capturedImage.startsWith('data:image')) {
                    e.preventDefault();
                    const blob = dataURLtoBlob(capturedImage);
                    const file = new File([blob], 'captured_image.png', {
                        type: 'image/png'
                    });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    $('#profile_picture')[0].files = dataTransfer.files;
                    this.submit();
                }
            });

            // Convert data URL to Blob
            function dataURLtoBlob(dataUrl) {
                const arr = dataUrl.split(',');
                const mime = arr[0].match(/:(.*?);/)[1];
                const bstr = atob(arr[1]);
                let n = bstr.length;
                const u8arr = new Uint8Array(n);
                while (n--) {
                    u8arr[n] = bstr.charCodeAt(n);
                }
                return new Blob([u8arr], {
                    type: mime
                });
            }
        });
    </script>
@endsection
