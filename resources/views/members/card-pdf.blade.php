<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ $member->name }} - ID Card</title>
@include('members.partials.card-styles')
</head>
<body>
@php
    $typeUrdu = match($member->member_type) {
        'householder' => 'سربراہِ خاندان',
        'family_member' => 'فرد خاندان',
        'child' => 'بچہ',
        'spouse' => 'شریکِ حیات',
        default => ucfirst(str_replace('_', ' ', $member->member_type)),
    };
    $logoPath = public_path('logo.jpeg');
@endphp

{{-- ═══════════ FRONT SIDE ═══════════ --}}
<table class="hdr"><tr>
    <td width="22">
        @if(file_exists($logoPath))
            <img src="{{ $logoPath }}" class="hdr-logo" alt="">
        @endif
    </td>
    <td style="padding-left:4pt;">
        <div class="hdr-org">SINDHI HALAI JAMAT</div>
        <div class="hdr-sub">MEMBERSHIP IDENTITY CARD &bull; <span class="urdu">رکنیتی شناختی کارڈ</span></div>
    </td>
    <td width="62" style="text-align:right;">
        <div class="hdr-id">{{ $member->membership_number ?? 'SHJ-' . str_pad($member->id, 4, '0', STR_PAD_LEFT) }}</div>
    </td>
</tr></table>

<div class="bdy">
<table style="width:100%;"><tr>
    <td style="padding-right:5pt;">
        <div class="lbl">Full Name / <span class="urdu">نام</span></div>
        <div class="val-lg">{{ $member->name }}</div>

        <div class="lbl">Father Name / <span class="urdu">والد</span></div>
        <div class="val">{{ $member->father_name ?? 'N/A' }}</div>

        <table style="width:100%;"><tr>
            <td width="56%">
                <div class="lbl">Grandfather / <span class="urdu">دادا</span></div>
                <div class="val">{{ $member->grandfather_name ?? 'N/A' }}</div>
            </td>
            <td>
                <div class="lbl">Caste / <span class="urdu">قوم</span></div>
                <div class="val">{{ $member->caste ?? 'N/A' }}</div>
            </td>
        </tr></table>

        <div class="lbl">{{ $member->cnic ? 'CNIC' : 'B-Form' }} / <span class="urdu">{{ $member->cnic ? 'شناختی نمبر' : 'ب فارم نمبر' }}</span></div>
        <div class="val" style="font-size:6.8pt;letter-spacing:0.3pt;">{{ $member->cnic ?? $member->b_form_number ?? 'N/A' }}</div>
    </td>

    <td class="photo-wrap">
        <div class="photo-box">
            @if ($member->profile_picture && file_exists(public_path('profiles/' . $member->profile_picture)))
                <img src="{{ public_path('profiles/' . $member->profile_picture) }}" alt="">
            @else
                <img src="{{ public_path('image/default-profile.jpg') }}" alt="">
            @endif
        </div>
        <div class="type-lbl">{{ strtoupper(str_replace('_', ' ', $member->member_type)) }}<br><span class="urdu">{{ $typeUrdu }}</span></div>
    </td>
</tr></table>

<table style="width:100%;margin-top:1pt;"><tr>
    <td style="font-size:3.8pt;color:#5c6e66;font-weight:bold;letter-spacing:0.3pt;padding-top:3pt;">Reg. 3811</td>
    <td class="sig-area">
        <div class="sig-line">&nbsp;</div>
        <div class="sig-txt">Authorized Signatory / <span class="urdu">مجاز دستخط</span></div>
    </td>
</tr></table>
</div>
<div class="ftr"></div>

<pagebreak />

{{-- ═══════════ BACK SIDE ═══════════ --}}
<table class="hdr"><tr>
    <td width="22">
        @if(file_exists($logoPath))
            <img src="{{ $logoPath }}" class="hdr-logo" alt="">
        @endif
    </td>
    <td style="padding-left:4pt;">
        <div class="hdr-org">SINDHI HALAI JAMAT</div>
        <div class="hdr-sub">MEMBER DETAILS &bull; <span class="urdu">تفصیلاتِ رکن</span></div>
    </td>
</tr></table>

<div class="bdy">
<div class="sec-title">PARTICULARS &bull; <span class="urdu">کوائف</span></div>

<table style="width:100%;"><tr>
    <td style="width:66%;padding-right:3pt;">
        <div class="info-row"><strong>Community / برادری:</strong> {{ $member->community->name ?? 'N/A' }}</div>
        <div class="info-row"><strong>Gender / جنس:</strong> {{ ucfirst($member->gender) }} &nbsp;|&nbsp; <strong>Status:</strong> {{ ucfirst($member->marital_status) }}</div>
        <div class="info-row"><strong>DOB / تاریخ پیدائش:</strong> {{ $member->date_of_birth ?? 'N/A' }}</div>
        <div class="info-row"><strong>Blood / خون:</strong> {{ $member->blood_group ?? 'N/A' }}</div>
        <div class="info-row"><strong>Phone / رابطہ:</strong> {{ $member->phone ?? 'N/A' }}</div>
        <div class="info-row"><strong>Address / پتہ:</strong> {{ \Illuminate\Support\Str::limit($member->address ?? 'N/A', 40) }}</div>
        <div class="info-row"><strong>Issued / اجراء:</strong> {{ $member->created_at?->format('d M Y') ?? 'N/A' }}</div>
    </td>
    <td style="text-align:center;">
        <div class="stamp-area">
            <div class="stamp-head">Office Stamp</div>
            <div class="stamp-ur urdu">دفتری مہر</div>
        </div>
    </td>
</tr></table>

<div class="disc">
    <div class="disc-en">This card is the property of Sindhi Halai Jamat &bull; Non-transferable &bull; If found, return to the Jamat office.</div>
    <div class="disc-ur urdu">یہ کارڈ سندھی ہالائی جماعت کی ملکیت ہے۔ گمشدگی پر جماعت آفس میں جمع کروائیں۔</div>
</div>
</div>
<div class="ftr"></div>

</body>
</html>
