<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Membership Card Front - {{ $member->name }}</title>
@include('members.partials.card-styles')
</head>
<body>

@php
    $memberTypeUrdu = match($member->member_type) {
        'householder' => 'سربراہِ خاندان',
        'family_member' => 'خاندان کا فرد',
        'child' => 'بچہ',
        'spouse' => 'شریکِ حیات',
        default => ucfirst(str_replace('_', ' ', $member->member_type)),
    };
@endphp

<div class="card">
    <table class="card-header">
        <tr>
            <td width="23"><div class="seal-circle">SHJ</div></td>
            <td style="padding-left:5pt;">
                <div class="brand-title">SINDHI HALAI JAMAT</div>
                <div class="brand-sub">MEMBERSHIP IDENTITY CARD &bull; <span class="urdu">شناختی کارڈ</span></div>
            </td>
            <td width="65" style="text-align:right;">
                <div class="mem-badge">ID: {{ $member->membership_number ?? ('#' . $member->id) }}</div>
            </td>
        </tr>
    </table>

    <div class="card-body">
        <table style="width:100%;">
            <tr>
                <td style="padding-right:6pt;">
                    <div class="lbl">FULL NAME / نام</div>
                    <div class="val-name">{{ $member->name }}</div>

                    <div class="lbl">FATHER NAME / ولدیت</div>
                    <div class="val">{{ $member->father_name ?? 'N/A' }}</div>

                    <table style="width:100%;">
                        <tr>
                            <td width="55%">
                                <div class="lbl">GRANDFATHER / دادا</div>
                                <div class="val">{{ $member->grandfather_name ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="lbl">CASTE / قوم</div>
                                <div class="val">{{ $member->caste ?? 'N/A' }}</div>
                            </td>
                        </tr>
                    </table>

                    <div class="lbl">{{ $member->cnic ? 'CNIC NUMBER / شناختی کارڈ' : 'B-FORM / ب فارم نمبر' }}</div>
                    <div class="val-cnic">{{ $member->cnic ?? $member->b_form_number ?? 'N/A' }}</div>
                </td>
                <td class="photo-cell">
                    <div class="photo-frame">
                        @if ($member->profile_picture && file_exists(public_path('profiles/' . $member->profile_picture)))
                            <img src="{{ public_path('profiles/' . $member->profile_picture) }}" alt="{{ $member->name }}">
                        @else
                            <img src="{{ public_path('image/default-profile.jpg') }}" alt="Default">
                        @endif
                    </div>
                    <div class="type-pill">{{ ucwords(str_replace('_', ' ', $member->member_type)) }} &bull; <span class="urdu">{{ $memberTypeUrdu }}</span></div>
                </td>
            </tr>
        </table>

        <table class="card-footer-row" style="width:100%;">
            <tr>
                <td class="validity-tag">&bull; OFFICIAL IDENTITY CARD &bull;</td>
                <td class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-text">Authorized Signatory / <span class="urdu">مجاز دستخط</span></div>
                </td>
            </tr>
        </table>
    </div>

    <div class="bottom-stripe"></div>
</div>

</body>
</html>