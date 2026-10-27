<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Member Details - {{ $member->name }}</title>
    <style>
        body {
            font-family: sans-serif, 'urdu', 'noto';
            margin: 0;
            color: #222;
            font-size: 11px;
        }

        .urdu {
            font-family: 'urdu', 'noto', sans-serif;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #1b5e3f;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .org-title {
            font-size: 20px;
            font-weight: bold;
            color: #1b5e3f;
            letter-spacing: 0.5px;
        }

        .doc-subtitle {
            font-size: 12px;
            color: #8a6d1f;
            margin-top: 3px;
        }

        .meta-info {
            font-size: 10px;
            color: #555;
            line-height: 1.4;
        }

        .section-header {
            background-color: #f4f8f5;
            border-left: 4px solid #1b5e3f;
            padding: 5px 8px;
            font-size: 12px;
            font-weight: bold;
            color: #1b5e3f;
            margin-top: 12px;
            margin-bottom: 8px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
            border: none;
            font-size: 11px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #dcdcdc;
            padding: 5px 6px;
            font-size: 10px;
            text-align: left;
        }

        .data-table th {
            background-color: #1b5e3f;
            color: #ffffff;
            font-weight: bold;
            line-height: 1.3;
        }

        .data-table tr:nth-child(even) {
            background-color: #fafafa;
        }

        .photo-cell {
            width: 100px;
            text-align: center;
            vertical-align: top;
            padding-right: 12px;
        }

        .photo-img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 2px solid #1b5e3f;
        }

        .label-text {
            color: #333;
            font-weight: bold;
        }

        .val-text {
            color: #111;
        }
    </style>
</head>

<body>
    @php
        $memberTypeUrdu = match($member->member_type) {
            'householder' => 'سربراہِ خاندان',
            'family_member' => 'خاندان کا فرد',
            'child' => 'بچہ',
            'spouse' => 'شریکِ حیات',
            'sibling' => 'بہن بھائی',
            default => ucfirst(str_replace('_', ' ', $member->member_type)),
        };

        $maritalStatusUrdu = match($member->marital_status) {
            'single' => 'غیر شادی شدہ',
            'married' => 'شادی شدہ',
            'divorced' => 'طلاق یافتہ',
            'widowed' => 'بیوہ',
            default => ucfirst($member->marital_status),
        };

        $genderUrdu = match($member->gender) {
            'male' => 'مرد',
            'female' => 'عورت',
            default => 'دیگر',
        };

        $deceasedUrdu = $member->is_deceased ? 'جی ہاں' : 'نہیں';
        $cardReceivedUrdu = $member->card_received ? 'جی ہاں' : 'نہیں';
    @endphp

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td width="60" style="vertical-align: middle;">
                <img src="{{ public_path('logo.jpeg') }}" style="width: 52px; height: 52px; border-radius: 50%;" alt="Logo">
            </td>
            <td style="vertical-align: middle; padding-left: 10px;">
                <div class="org-title">SINDHI HALAI JAMAT</div>
                <div class="doc-subtitle">
                    Member Details Report &nbsp;|&nbsp; <span class="urdu">ممبر کی تفصیلی رپورٹ</span>
                </div>
            </td>
            <td style="text-align: right; vertical-align: middle;">
                <div class="meta-info"><strong>Generated:</strong> {{ now()->format('d M Y, h:i A') }}</div>
                <div class="meta-info"><strong>Membership No:</strong> {{ $member->membership_number ?? ('#' . $member->id) }}</div>
            </td>
        </tr>
    </table>

    <!-- Member Details Section -->
    <div class="section-header">
        Member Information &nbsp;|&nbsp; <span class="urdu">ممبر کی معلومات</span>
    </div>

    <table class="info-table">
        <tr>
            <td class="photo-cell" rowspan="6">
                @if ($member->profile_picture)
                    <img src="{{ public_path('profiles/' . $member->profile_picture) }}" class="photo-img" alt="{{ $member->name }}">
                @else
                    <img src="{{ public_path('image/default-profile.jpg') }}" class="photo-img" alt="Default Profile">
                @endif
                <div style="margin-top: 4px; font-weight: bold; font-size: 10px; color: #1b5e3f;">
                    {{ $member->name }}
                </div>
            </td>
            <td width="45%">
                <span class="label-text">Name / <span class="urdu">نام</span>:</span>
                <span class="val-text">{{ $member->name }}</span>
            </td>
            <td width="45%">
                <span class="label-text">Community / <span class="urdu">برادری</span>:</span>
                <span class="val-text">{{ $member->community->name }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label-text">Father Name / <span class="urdu">والد کا نام</span>:</span>
                <span class="val-text">{{ $member->father_name ?? 'N/A' }}</span>
            </td>
            <td>
                <span class="label-text">Member Type / <span class="urdu">ممبر کی قسم</span>:</span>
                <span class="val-text">{{ ucwords(str_replace('_', ' ', $member->member_type)) }} (<span class="urdu">{{ $memberTypeUrdu }}</span>)</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label-text">Grandfather / <span class="urdu">دادا کا نام</span>:</span>
                <span class="val-text">{{ $member->grandfather_name ?? 'N/A' }}</span>
            </td>
            <td>
                <span class="label-text">Marital Status / <span class="urdu">ازدواجی حیثیت</span>:</span>
                <span class="val-text">{{ ucfirst($member->marital_status) }} (<span class="urdu">{{ $maritalStatusUrdu }}</span>)</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label-text">Cast / Gote / <span class="urdu">قوم / گوت</span>:</span>
                <span class="val-text">{{ $member->caste ?? 'N/A' }}</span>
            </td>
            <td>
                <span class="label-text">Gender / <span class="urdu">جنس</span>:</span>
                <span class="val-text">{{ ucfirst($member->gender) }} (<span class="urdu">{{ $genderUrdu }}</span>)</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label-text">CNIC / <span class="urdu">شناختی کارڈ</span>:</span>
                <span class="val-text">{{ $member->cnic ?? 'N/A' }}</span>
            </td>
            <td>
                <span class="label-text">Blood Group / <span class="urdu">بلڈ گروپ</span>:</span>
                <span class="val-text">{{ $member->blood_group ?? 'N/A' }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label-text">B-Form No. / <span class="urdu">ب فارم نمبر</span>:</span>
                <span class="val-text">{{ $member->b_form_number ?? 'N/A' }}</span>
            </td>
            <td>
                <span class="label-text">Date of Birth / <span class="urdu">تاریخِ پیدائش</span>:</span>
                <span class="val-text">{{ $member->date_of_birth ?? 'N/A' }}</span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <span class="label-text">Phone / <span class="urdu">فون نمبر</span>:</span>
                <span class="val-text">{{ $member->phone ?? 'N/A' }}</span>
            </td>
            <td>
                <span class="label-text">Email / <span class="urdu">ای میل</span>:</span>
                <span class="val-text">{{ $member->email ?? 'N/A' }}</span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <span class="label-text">Parent / <span class="urdu">والدین</span>:</span>
                <span class="val-text">{{ $member->parent ? $member->parent->name : 'None / کوئی نہیں' }}</span>
            </td>
            <td>
                <span class="label-text">Deceased / <span class="urdu">وفات</span>:</span>
                <span class="val-text">{{ $member->is_deceased ? 'Yes (جی ہاں)' : 'No (نہیں)' }}</span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <span class="label-text">Death Date / <span class="urdu">تاریخِ وفات</span>:</span>
                <span class="val-text">{{ $member->death_date ?? 'N/A' }}</span>
            </td>
            <td>
                <span class="label-text">Country / <span class="urdu">ملک</span>:</span>
                <span class="val-text">{{ $member->country ?? 'N/A' }}</span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <span class="label-text">Education / <span class="urdu">تعلیم</span>:</span>
                <span class="val-text">{{ $member->education ?? 'N/A' }}</span>
            </td>
            <td>
                <span class="label-text">Skill / <span class="urdu">ہنر</span>:</span>
                <span class="val-text">{{ $member->skill ?? 'N/A' }}</span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <span class="label-text">Card Received / <span class="urdu">کارڈ وصولی</span>:</span>
                <span class="val-text">{{ $member->card_received ? ($member->card_received == 1 ? 'Yes (جی ہاں)' : $member->card_received) : 'No (نہیں)' }}</span>
            </td>
            <td>
                <span class="label-text">Address / <span class="urdu">پتہ</span>:</span>
                <span class="val-text">{{ $member->address ?? 'N/A' }}</span>
            </td>
        </tr>
        @if ($member->description)
        <tr>
            <td></td>
            <td colspan="2">
                <span class="label-text">Description / <span class="urdu">تفصیل</span>:</span>
                <span class="val-text">{{ $member->description }}</span>
            </td>
        </tr>
        @endif
    </table>

    <!-- Spouse Details Section -->
    <div class="section-header">
        Spouse Details &nbsp;|&nbsp; <span class="urdu">شریکِ حیات کی تفصیلات</span>
    </div>

    @if ($member->spouse)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name / <span class="urdu">نام</span></th>
                    <th>CNIC / <span class="urdu">شناختی کارڈ</span></th>
                    <th>Gender / <span class="urdu">جنس</span></th>
                    <th>Phone / <span class="urdu">فون نمبر</span></th>
                    <th>Member Type / <span class="urdu">ممبر کی قسم</span></th>
                    <th>Deceased / <span class="urdu">وفات</span></th>
                    <th>Date of Birth / <span class="urdu">تاریخِ پیدائش</span></th>
                    <th>Country / <span class="urdu">ملک</span></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $spouseGenderUrdu = match($member->spouse->gender) {
                        'male' => 'مرد',
                        'female' => 'عورت',
                        default => 'دیگر',
                    };
                    $spouseTypeUrdu = match($member->spouse->member_type) {
                        'spouse' => 'شریکِ حیات',
                        'householder' => 'سربراہِ خاندان',
                        default => ucfirst(str_replace('_', ' ', $member->spouse->member_type)),
                    };
                @endphp
                <tr>
                    <td>{{ $member->spouse->name }}</td>
                    <td>{{ $member->spouse->cnic ?? 'N/A' }}</td>
                    <td>{{ ucfirst($member->spouse->gender) }} (<span class="urdu">{{ $spouseGenderUrdu }}</span>)</td>
                    <td>{{ $member->spouse->phone ?? 'N/A' }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $member->spouse->member_type)) }} (<span class="urdu">{{ $spouseTypeUrdu }}</span>)</td>
                    <td>{{ $member->spouse->is_deceased ? 'Yes (جی ہاں)' : 'No (نہیں)' }}</td>
                    <td>{{ $member->spouse->date_of_birth ?? 'N/A' }}</td>
                    <td>{{ $member->spouse->country ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    @else
        <div style="padding: 6px 0; font-size: 11px; color: #555;">
            <strong>Spouse / <span class="urdu">شریکِ حیات</span>:</strong> None / <span class="urdu">کوئی نہیں</span>
        </div>
    @endif

    <!-- Children Details Section -->
    <div class="section-header">
        Children Details &nbsp;|&nbsp; <span class="urdu">بچوں کی تفصیلات</span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Name / <span class="urdu">نام</span></th>
                <th>CNIC / B-Form / <span class="urdu">شناختی کارڈ / ب فارم</span></th>
                <th>Gender / <span class="urdu">جنس</span></th>
                <th>Phone / <span class="urdu">فون نمبر</span></th>
                <th>Member Type / <span class="urdu">ممبر کی قسم</span></th>
                <th>Deceased / <span class="urdu">وفات</span></th>
                <th>Date of Birth / <span class="urdu">تاریخِ پیدائش</span></th>
                <th>Country / <span class="urdu">ملک</span></th>
            </tr>
        </thead>
        <tbody>
            @forelse($member->family_children as $child)
                @php
                    $childGenderUrdu = match($child->gender) {
                        'male' => 'مرد',
                        'female' => 'عورت',
                        default => 'دیگر',
                    };
                    $childTypeUrdu = match($child->member_type) {
                        'child' => 'بچہ',
                        'family_member' => 'خاندان کا فرد',
                        default => ucfirst(str_replace('_', ' ', $child->member_type)),
                    };
                @endphp
                <tr>
                    <td>{{ $child->name }}</td>
                    <td>{{ $child->cnic ?? $child->b_form_number ?? 'N/A' }}</td>
                    <td>{{ ucfirst($child->gender) }} (<span class="urdu">{{ $childGenderUrdu }}</span>)</td>
                    <td>{{ $child->phone ?? 'N/A' }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $child->member_type)) }} (<span class="urdu">{{ $childTypeUrdu }}</span>)</td>
                    <td>{{ $child->is_deceased ? 'Yes (جی ہاں)' : 'No (نہیں)' }}</td>
                    <td>{{ $child->date_of_birth ?? 'N/A' }}</td>
                    <td>{{ $child->country ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 10px; color: #666;">
                        No children found. &nbsp;|&nbsp; <span class="urdu">کوئی بچہ درج نہیں ہے</span>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
