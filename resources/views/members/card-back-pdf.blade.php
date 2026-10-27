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
        'male' => 'مرد',
        'female' => 'عورت',
        default => 'دیگر',
    };
    $maritalUrdu = match($member->marital_status) {
        'single' => 'غیر شادی شدہ',
        'married' => 'شادی شدہ',
        'divorced' => 'طلاق یافتہ',
        'widowed' => 'بیوہ',
        'widower' => 'رنڈوا',
        default => ucfirst($member->marital_status),
    };
@endphp

{{-- ══ HEADER ══ --}}
<table class="card-header-table">
    <tr>
        <td style="width:20pt;vertical-align:middle;">
            @if(file_exists($logoPath))
                <img src="{{ $logoPath }}" class="card-logo" alt="">
            @endif
        </td>
        <td style="padding-left:3pt;vertical-align:middle;">
            <div class="card-title-main">SINDHI HALAI JAMAT</div>
            <div class="card-title-sub">MEMBER PARTICULARS &bull; <span class="urdu">کوائف رکن</span></div>
        </td>
        <td style="width:68pt;text-align:right;vertical-align:middle;">
            <div class="card-id-badge">{{ $member->membership_number ?? 'SHJ-' . str_pad($member->id, 4, '0', STR_PAD_LEFT) }}</div>
        </td>
    </tr>
</table>

{{-- ══ MAIN BODY ══ --}}
<div style="padding: 2.5pt 5pt 1pt 5pt;">
    <div class="back-sec-title">PARTICULARS &bull; <span class="urdu">تفصیلات و تصدیق</span></div>

    <table style="width:100%;margin-bottom:1.5pt;">
        <tr>
            <td style="width:155pt;padding-right:3pt;vertical-align:top;">
                <div class="back-info-item">
                    <strong>Gender / <span class="urdu">جنس</span>:</strong> {{ ucfirst($member->gender) }} ({{ $genderUrdu }}) &nbsp;|&nbsp;
                    <strong>Status:</strong> {{ ucfirst($member->marital_status) }}
                </div>
                <div class="back-info-item">
                    <strong>DOB / <span class="urdu">تاریخ پیدائش</span>:</strong> {{ $member->date_of_birth ? \Carbon\Carbon::parse($member->date_of_birth)->format('d-M-Y') : 'N/A' }} &nbsp;|&nbsp;
                    <strong>Blood:</strong> <span style="color:#b30000;font-weight:bold;">{{ $member->blood_group ?? 'N/A' }}</span>
                </div>
                <div class="back-info-item">
                    <strong>Phone / <span class="urdu">فون نمبر</span>:</strong> {{ $member->phone ?? 'N/A' }}
                </div>
                <div class="back-info-item">
                    <strong>Address / <span class="urdu">پتہ</span>:</strong> {{ \Illuminate\Support\Str::limit($member->address ?? 'N/A', 75) }}
                </div>
                <div class="back-info-item">
                    <strong>Date of Issue / <span class="urdu">تاریخِ اجراء</span>:</strong> {{ $member->created_at?->format('d-M-Y') ?? date('d-M-Y') }}
                </div>
            </td>
            <td style="width:58pt;text-align:center;vertical-align:middle;">
                <div class="back-stamp-box">
                    <div style="font-size:4.5pt;font-weight:bold;color:#8a7c30;text-transform:uppercase;letter-spacing:0.3pt;">Office Stamp</div>
                    <div style="font-size:4.2pt;color:#8a7c30;margin-top:1pt;" class="urdu">دفتری مہر و تصدیق</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Disclaimer Box --}}
    <div class="back-disclaimer" style="border-bottom: 2pt solid #074e37;">
        <div style="font-size:3.8pt;color:#3b4e45;line-height:1.2;">
            Property of Sindhi Halai Jamat &bull; Non-transferable. If found, please return to Jamat Office.
        </div>
        <div style="font-size:4.2pt;color:#074e37;margin-top:0.8pt;line-height:1.2;" class="urdu">
            یہ کارڈ سندھی ہالائی جماعت کی ملکیت ہے۔ گُم ہونے کی صورت میں فوری طور پر جماعت آفس میں جمع کروائیں۔
        </div>
    </div>
</div>

</body>
</html>
