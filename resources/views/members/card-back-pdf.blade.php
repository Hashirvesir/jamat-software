<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ID Card Back - {{ $member->name }}</title>
@include('members.partials.card-styles')
</head>
<body>
@php
    $logoPath = public_path('logo.jpeg');
    $genderUrdu = match($member->gender) {
        'male'   => 'مرد',
        'female' => 'عورت',
        default  => 'دیگر',
    };
    $maritalUrdu = match($member->marital_status) {
        'single'   => 'غیر شادی شدہ',
        'married'  => 'شادی شدہ',
        'divorced' => 'طلاق یافتہ',
        'widowed'  => 'بیوہ',
        'widower'  => 'رنڈوا',
        default    => ucfirst($member->marital_status),
    };

    $membershipNumber = $member->membership_number
        ? mb_substr($member->membership_number, 0, 15)
        : ('SHJ-' . str_pad($member->id, 4, '0', STR_PAD_LEFT));

    $cleanAddress = preg_replace('/\s+/', ' ', trim((string)($member->address ?? 'N/A')));
    $shortAddress = \Illuminate\Support\Str::limit($cleanAddress, 65);
    $issueDate = $member->created_at?->format('d-M-Y') ?? date('d-M-Y');
    $dobFormatted = $member->date_of_birth ? \Carbon\Carbon::parse($member->date_of_birth)->format('d-M-Y') : 'N/A';
@endphp

<table class="card-table">
    <!-- HEADER -->
    <tr class="card-header-row">
        <td style="width: 20pt; padding: 2.5pt 2pt 2.5pt 4pt; vertical-align: middle;">
            @if(file_exists($logoPath))
                <img src="{{ $logoPath }}" style="width: 14pt; height: 14pt;" alt="">
            @endif
        </td>
        <td style="padding: 2.5pt 2pt; vertical-align: middle;">
            <div class="card-title-main">SINDHI HALAI JAMAT</div>
            <div class="card-title-sub">MEMBER PARTICULARS &bull; <span class="urdu">کوائف رکن</span></div>
        </td>
        <td style="width: 72pt; padding: 2.5pt 4pt 2.5pt 2pt; text-align: right; vertical-align: middle;">
            <div class="card-id-badge">{{ $membershipNumber }}</div>
        </td>
    </tr>

    <!-- BODY -->
    <tr>
        <td colspan="3" style="padding: 2.5pt 4pt 1pt 4pt; vertical-align: top;">
            <div class="back-sec-title">
                PARTICULARS &bull; <span class="urdu">تفصیلات و تصدیق</span>
            </div>

            <table style="width: 100%; border-collapse: collapse; margin-bottom: 2pt;">
                <tr>
                    <!-- LEFT PARTICULARS -->
                    <td style="width: 165pt; padding-right: 4pt; vertical-align: top;">
                        <div class="back-info-item">
                            <strong style="color: #074e37;">Gender / <span class="urdu">جنس</span>:</strong> {{ ucfirst($member->gender) }} ({{ $genderUrdu }}) &nbsp;|&nbsp;
                            <strong style="color: #074e37;">Status:</strong> {{ ucfirst($member->marital_status) }}
                        </div>
                        <div class="back-info-item">
                            <strong style="color: #074e37;">DOB / <span class="urdu">تاریخ پیدائش</span>:</strong> {{ $dobFormatted }} &nbsp;|&nbsp;
                            <strong style="color: #074e37;">Blood:</strong> <span style="color:#b30000;font-weight:bold;">{{ $member->blood_group ?? 'N/A' }}</span>
                        </div>
                        <div class="back-info-item">
                            <strong style="color: #074e37;">Phone / <span class="urdu">فون نمبر</span>:</strong> {{ $member->phone ?? 'N/A' }}
                        </div>
                        <div class="back-info-item" style="font-size: 4.2pt;">
                            <strong style="color: #074e37;">Address / <span class="urdu">پتہ</span>:</strong> {{ $shortAddress }}
                        </div>
                        <div class="back-info-item">
                            <strong style="color: #074e37;">Date of Issue / <span class="urdu">تاریخِ اجراء</span>:</strong> {{ $issueDate }}
                        </div>
                    </td>

                    <!-- RIGHT STAMP BOX -->
                    <td style="width: 58pt; text-align: center; vertical-align: middle;">
                        <div class="back-stamp-box">
                            <div style="font-size: 4.2pt; font-weight: bold; color: #8a7c30; text-transform: uppercase; letter-spacing: 0.3pt;">Office Stamp</div>
                            <div style="font-size: 3.8pt; color: #8a7c30; margin-top: 1pt;" class="urdu">دفتری مہر و تصدیق</div>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- DISCLAIMER BOX -->
            <div class="back-disclaimer">
                <div style="font-size: 3.5pt; color: #3b4e45; line-height: 1.1;">
                    Property of Sindhi Halai Jamat &bull; Non-transferable. If found, please return to Jamat Office.
                </div>
                <div style="font-size: 3.8pt; color: #074e37; margin-top: 0.8pt; line-height: 1.1;" class="urdu">
                    یہ کارڈ سندھی ہالائی جماعت کی ملکیت ہے۔ گُم ہونے کی صورت میں فوری طور پر جماعت آفس میں جمع کروائیں۔
                </div>
            </div>
        </td>
    </tr>
</table>

</body>
</html>
