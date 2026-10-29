<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ID Card Front - {{ $member->name }}</title>
@include('members.partials.card-styles')
</head>
<body>
@php
    $typeUrdu = match($member->member_type) {
        'householder'     => 'سربراہِ خاندان',
        'non_householder' => 'غیر سربراہ',
        'family_member'   => 'خاندان کا فرد',
        'child'           => 'بچہ',
        'spouse'          => 'شریکِ حیات',
        'sibling'         => 'بہن بھائی',
        default           => ucfirst(str_replace('_', ' ', $member->member_type)),
    };
    $logoPath = public_path('logo.jpeg');
    $hasPhoto = !empty($member->profile_picture) && file_exists(public_path('profiles/' . $member->profile_picture));
    $photoPath = $hasPhoto
        ? public_path('profiles/' . $member->profile_picture)
        : (file_exists(public_path('image/default-profile.jpg')) ? public_path('image/default-profile.jpg') : null);

    $membershipNumber = $member->membership_number
        ? mb_substr($member->membership_number, 0, 15)
        : ('SHJ-' . str_pad($member->id, 4, '0', STR_PAD_LEFT));

    $fatherDisplayName = $member->father_name ?: ($member->father?->name ?? 'N/A');
    $grandfatherDisplayName = $member->grandfather_name ?: 'N/A';
    $casteDisplayName = $member->caste ?: 'N/A';
    $cnicDisplayName = $member->cnic ?: ($member->b_form_number ?: 'N/A');
@endphp

<table class="card-outer">
    <!-- HEADER -->
    <tr>
        <td style="padding: 0;">
            <table class="hdr-tbl">
                <tr>
                    <td class="hdr-logo">
                        @if(file_exists($logoPath))
                            <img src="{{ $logoPath }}" style="width: 15pt; height: 15pt;" alt="">
                        @endif
                    </td>
                    <td class="hdr-title-box">
                        <div class="hdr-title-main">SINDHI HALAI JAMAT</div>
                        <div class="hdr-title-sub">MEMBERSHIP CARD &bull; <span class="urdu">سندھی ہالائی جماعت</span></div>
                    </td>
                    <td class="hdr-badge-box">
                        <div class="hdr-badge">{{ $membershipNumber }}</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- BODY -->
    <tr>
        <td style="padding: 3pt 4pt 2pt 4pt; vertical-align: top;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <!-- LEFT COLUMN: MEMBER DETAILS TABLE -->
                    <td style="width: 172pt; padding-right: 4pt; vertical-align: top;">
                        <table class="kv-tbl">
                            <tr>
                                <td class="kv-lbl">Name</td>
                                <td class="kv-val-name">{{ strtoupper(mb_substr($member->name, 0, 26)) }}</td>
                            </tr>
                            <tr>
                                <td class="kv-lbl">Father</td>
                                <td class="kv-val">{{ strtoupper(mb_substr($fatherDisplayName, 0, 28)) }}</td>
                            </tr>
                            <tr>
                                <td class="kv-lbl">G.Father / Caste</td>
                                <td class="kv-val">
                                    {{ strtoupper(mb_substr($grandfatherDisplayName, 0, 16)) }}
                                    &bull;
                                    <span style="color: #586c63; font-size: 4.8pt;">CASTE:</span>
                                    {{ strtoupper(mb_substr($casteDisplayName, 0, 14)) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="kv-lbl">{{ $member->cnic ? 'CNIC' : 'B-Form' }}</td>
                                <td class="kv-val" style="color: #074e37; letter-spacing: 0.3pt;">{{ $cnicDisplayName }}</td>
                            </tr>
                            <tr>
                                <td class="kv-lbl" style="border-bottom: none;">Community</td>
                                <td class="kv-val" style="border-bottom: none;">
                                    {{ mb_substr($member->community?->name ?? 'General Community', 0, 22) }}
                                    @if($member->blood_group)
                                        &nbsp;&nbsp;<span style="background-color: #b30000; color: #ffffff; font-size: 4.8pt; font-weight: bold; padding: 0.8pt 2.5pt; border-radius: 1pt;">{{ $member->blood_group }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>

                    <!-- RIGHT COLUMN: PHOTO & BADGE -->
                    <td style="width: 52pt; text-align: center; vertical-align: top;">
                        <div class="photo-box">
                            @if($photoPath)
                                <img src="{{ $photoPath }}" style="width: 44pt; height: 50pt;" alt="{{ $member->name }}">
                            @else
                                <div style="padding-top: 15pt; font-size: 5pt; color: #7a8f84;">PHOTO</div>
                            @endif
                        </div>

                        <div class="type-badge">
                            {{ strtoupper(str_replace('_', ' ', $member->member_type)) }}<br>
                            <span class="urdu" style="font-size: 3.8pt; color: #f0f7f3;">{{ $typeUrdu }}</span>
                        </div>

                        <div style="font-size: 3.2pt; color: #6b7e74; text-align: center; margin-top: 1.5pt;">
                            Reg. No. 3811
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- FOOTER -->
    <tr>
        <td style="padding: 1.5pt 4pt 2pt 4pt; vertical-align: bottom;">
            <table class="ftr-tbl">
                <tr>
                    <td style="vertical-align: middle; padding: 2pt 0;">
                        <span style="font-size: 3.8pt; color: #074e37; font-weight: bold;">Govt. Reg. Act XXI 1860 &bull; Sindhi Halai Jamat</span>
                    </td>
                    <td style="text-align: right; vertical-align: middle; padding: 2pt 0;">
                        <div style="width: 60pt; border-bottom: 0.4pt solid #c59b27; margin-left: auto; margin-bottom: 1pt;">&nbsp;</div>
                        <div style="font-size: 3.4pt; color: #074e37; font-weight: bold;">Authorized Signatory</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>
