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
            <div class="card-title-sub">MEMBERSHIP CARD &bull; <span class="urdu">سندھی ہالائی جماعت</span></div>
        </td>
        <td style="width: 72pt; padding: 2.5pt 4pt 2.5pt 2pt; text-align: right; vertical-align: middle;">
            <div class="card-id-badge">{{ $membershipNumber }}</div>
        </td>
    </tr>

    <!-- BODY -->
    <tr>
        <td colspan="3" style="padding: 3pt 4pt 1pt 4pt; vertical-align: top;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <!-- LEFT COLUMN: MEMBER DETAILS -->
                    <td style="width: 168pt; padding-right: 4pt; vertical-align: top;">
                        {{-- Name --}}
                        <div class="field-lbl">Name / <span class="urdu">نام</span></div>
                        <div class="field-val-lg">{{ mb_substr($member->name, 0, 28) }}</div>

                        {{-- Father --}}
                        <div class="field-lbl">Father / <span class="urdu">والد کا نام</span></div>
                        <div class="field-val-md">{{ mb_substr($fatherDisplayName, 0, 30) }}</div>

                        {{-- Grandfather & Caste --}}
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 1.2pt;">
                            <tr>
                                <td style="width: 55%; padding-right: 2pt; vertical-align: top;">
                                    <div class="field-lbl">Grandfather / <span class="urdu">دادا</span></div>
                                    <div class="field-val-sm">{{ mb_substr($grandfatherDisplayName, 0, 18) }}</div>
                                </td>
                                <td style="padding-left: 2pt; vertical-align: top;">
                                    <div class="field-lbl">Caste / <span class="urdu">قوم / گوت</span></div>
                                    <div class="field-val-sm">{{ mb_substr($casteDisplayName, 0, 16) }}</div>
                                </td>
                            </tr>
                        </table>

                        {{-- CNIC / B-Form --}}
                        <div class="field-lbl">{{ $member->cnic ? 'CNIC' : 'B-Form' }} / <span class="urdu">{{ $member->cnic ? 'شناختی کارڈ' : 'ب فارم' }}</span></div>
                        <div class="field-val-cnic">{{ $cnicDisplayName }}</div>

                        {{-- Community & Blood Group --}}
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 65%; vertical-align: middle;">
                                    <div style="font-size: 4.5pt; color: #1b2e25; line-height: 1.1;">
                                        <strong style="color: #074e37;">Community:</strong> {{ mb_substr($member->community?->name ?? 'N/A', 0, 22) }}
                                    </div>
                                </td>
                                <td style="text-align: right; vertical-align: middle;">
                                    @if($member->blood_group)
                                        <span style="background-color: #b30000; color: #fff; font-size: 4.5pt; font-weight: bold; padding: 0.8pt 2.5pt; border-radius: 1pt;">
                                            {{ $member->blood_group }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>

                    <!-- RIGHT COLUMN: PHOTO & BADGE -->
                    <td style="width: 54pt; text-align: center; vertical-align: top;">
                        <div class="photo-frame">
                            @if($photoPath)
                                <img src="{{ $photoPath }}" style="width: 44pt; height: 48pt;" alt="{{ $member->name }}">
                            @else
                                <div style="padding-top: 14pt; font-size: 5pt; color: #7a8f84;">Photo / تصویر</div>
                            @endif
                        </div>

                        <div class="type-badge">
                            {{ strtoupper(str_replace('_', ' ', $member->member_type)) }}<br>
                            <span class="urdu" style="font-size: 3.5pt; color: #f0f7f3;">{{ $typeUrdu }}</span>
                        </div>

                        <div style="font-size: 3.2pt; color: #6b7e74; text-align: center; margin-top: 1pt; letter-spacing: 0.2pt;">
                            Reg. No. 3811
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- FOOTER -->
    <tr>
        <td colspan="3" style="padding: 1pt 4pt 1.5pt 4pt; border-bottom: 2pt solid #074e37; vertical-align: bottom;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="vertical-align: bottom;">
                        <span style="font-size: 3.5pt; color: #074e37; font-weight: bold;">Govt. Reg. Act XXI 1860</span>
                    </td>
                    <td style="text-align: right; vertical-align: bottom;">
                        <div style="width: 60pt; border-bottom: 0.4pt solid #c59b27; margin-left: auto; margin-bottom: 0.4pt;">&nbsp;</div>
                        <div style="font-size: 3.2pt; color: #074e37; font-weight: bold;">Authorized Signatory / <span class="urdu">مجاز دستخط</span></div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>
