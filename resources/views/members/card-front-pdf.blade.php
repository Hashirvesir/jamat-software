<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ID Card Front</title>
@include('members.partials.card-styles')
</head>
<body>
@php
    $typeUrdu = match($member->member_type) {
        'householder'  => 'سربراہِ خاندان',
        'family_member'=> 'فرد خاندان',
        'child'        => 'بچہ',
        'spouse'       => 'شریکِ حیات',
        default        => ucfirst(str_replace('_', ' ', $member->member_type)),
    };
    $logoPath = public_path('logo.jpeg');
    $hasPhoto = $member->profile_picture && file_exists(public_path('profiles/' . $member->profile_picture));
    $photoPath = $hasPhoto
        ? public_path('profiles/' . $member->profile_picture)
        : public_path('image/default-profile.jpg');
@endphp

{{-- ══ HEADER ══ --}}
<table style="width:100%;background-color:#064433;border-bottom:1.5pt solid #c9a227;"><tr>
    <td style="width:20pt;padding:2.5pt 0 2.5pt 5pt;vertical-align:middle;">
        @if(file_exists($logoPath))
        <img src="{{ $logoPath }}" style="width:15pt;height:15pt;" alt="">
        @endif
    </td>
    <td style="padding:2.5pt 0 2.5pt 3pt;vertical-align:middle;">
        <div style="color:#ffffff;font-size:7.5pt;font-weight:bold;letter-spacing:0.3pt;line-height:1;">SINDHI HALAI JAMAT</div>
        <div style="color:#c3ddd0;font-size:3.8pt;letter-spacing:0.2pt;margin-top:0.8pt;line-height:1;">MEMBERSHIP IDENTITY CARD &bull; <span class="urdu">شناختی کارڈ</span></div>
    </td>
    <td style="padding:2.5pt 5pt 2.5pt 0;text-align:right;vertical-align:middle;">
        <div style="background:#0d5a42;border:0.5pt solid #c9a227;color:#fff;font-size:4.8pt;font-weight:bold;padding:1.5pt 3pt;">{{ $member->membership_number ?? 'SHJ-' . str_pad($member->id, 4, '0', STR_PAD_LEFT) }}</div>
    </td>
</tr></table>

{{-- ══ BODY ══ --}}
<table style="width:100%;padding:0;margin:0;"><tr>

    {{-- Left: info column --}}
    <td style="padding:3pt 3pt 1pt 5pt;vertical-align:top;">

        <div style="font-size:3.5pt;text-transform:uppercase;letter-spacing:0.2pt;color:#5c6e66;font-weight:bold;line-height:1;margin-bottom:0.2pt;">Name / <span class="urdu">نام</span></div>
        <div style="font-size:7pt;font-weight:bold;color:#064433;border-bottom:0.5pt solid #c9a227;padding-bottom:0.4pt;margin-bottom:1pt;line-height:1.1;">{{ $member->name }}</div>

        <div style="font-size:3.5pt;text-transform:uppercase;letter-spacing:0.2pt;color:#5c6e66;font-weight:bold;line-height:1;margin-bottom:0.2pt;">Father / <span class="urdu">والد</span></div>
        <div style="font-size:5.8pt;font-weight:bold;color:#0f2a1f;border-bottom:0.4pt solid #dce4df;padding-bottom:0.4pt;margin-bottom:1pt;line-height:1.1;">{{ $member->father_name ?? 'N/A' }}</div>

        <table style="width:100%;margin-bottom:1pt;"><tr>
            <td style="width:55%;padding:0;vertical-align:top;">
                <div style="font-size:3.5pt;text-transform:uppercase;letter-spacing:0.2pt;color:#5c6e66;font-weight:bold;line-height:1;margin-bottom:0.2pt;">Grandfather / <span class="urdu">دادا</span></div>
                <div style="font-size:5.5pt;font-weight:bold;color:#0f2a1f;border-bottom:0.4pt solid #dce4df;padding-bottom:0.3pt;line-height:1.1;padding-right:2pt;">{{ $member->grandfather_name ?? 'N/A' }}</div>
            </td>
            <td style="padding:0;vertical-align:top;">
                <div style="font-size:3.5pt;text-transform:uppercase;letter-spacing:0.2pt;color:#5c6e66;font-weight:bold;line-height:1;margin-bottom:0.2pt;">Caste / <span class="urdu">قوم</span></div>
                <div style="font-size:5.5pt;font-weight:bold;color:#0f2a1f;border-bottom:0.4pt solid #dce4df;padding-bottom:0.3pt;line-height:1.1;">{{ $member->caste ?? 'N/A' }}</div>
            </td>
        </tr></table>

        <div style="font-size:3.5pt;text-transform:uppercase;letter-spacing:0.2pt;color:#5c6e66;font-weight:bold;line-height:1;margin-bottom:0.2pt;">{{ $member->cnic ? 'CNIC' : 'B-Form' }} / <span class="urdu">{{ $member->cnic ? 'شناختی نمبر' : 'ب فارم' }}</span></div>
        <div style="font-size:6.5pt;font-weight:bold;color:#064433;letter-spacing:0.4pt;border-bottom:0.4pt solid #dce4df;padding-bottom:0.4pt;margin-bottom:1pt;line-height:1.1;">{{ $member->cnic ?? $member->b_form_number ?? 'N/A' }}</div>

        {{-- Signature --}}
        <div style="margin-top:2pt;text-align:right;">
            <div style="width:78pt;border-bottom:0.5pt solid #c9a227;margin-left:auto;">&nbsp;</div>
            <div style="font-size:3.5pt;color:#064433;font-weight:bold;margin-top:0.5pt;">Authorized Signatory / <span class="urdu">مجاز دستخط</span></div>
        </div>

    </td>

    {{-- Right: photo column --}}
    <td style="width:45pt;padding:3pt 5pt 1pt 0;vertical-align:top;text-align:center;">
        <div style="width:40pt;height:42pt;border:0.8pt solid #064433;overflow:hidden;background-color:#eef4f0;margin:0 auto;">
            <img src="{{ $photoPath }}" style="width:40pt;height:42pt;" alt="">
        </div>
        <div style="background-color:#064433;color:#fff;font-size:3.8pt;font-weight:bold;text-align:center;padding:1pt 1pt;margin-top:1pt;line-height:1.2;">
            {{ strtoupper(str_replace('_', ' ', $member->member_type)) }}<br><span class="urdu">{{ $typeUrdu }}</span>
        </div>
        <div style="font-size:3pt;color:#5c6e66;text-align:center;margin-top:1pt;letter-spacing:0.2pt;">Reg. 3811</div>
    </td>

</tr></table>

{{-- ══ FOOTER STRIPE ══ --}}
<div style="width:100%;background-color:#064433;height:2pt;"></div>

</body>
</html>