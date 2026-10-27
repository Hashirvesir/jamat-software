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
@endphp

{{-- ══ HEADER ══ --}}
<table class="card-header-table">
    <tr>
        <td style="width:18pt;vertical-align:middle;">
            @if(file_exists($logoPath))
                <img src="{{ $logoPath }}" style="width:14pt;height:14pt;" alt="">
            @endif
        </td>
        <td style="padding-left:2pt;vertical-align:middle;">
            <div class="card-title-main">SINDHI HALAI JAMAT</div>
            <div class="card-title-sub">MEMBERSHIP CARD &bull; <span class="urdu">سندھی ہالائی جماعت</span></div>
        </td>
        <td style="width:65pt;text-align:right;vertical-align:middle;">
            <div class="card-id-badge">{{ $member->membership_number ?? 'SHJ-' . str_pad($member->id, 4, '0', STR_PAD_LEFT) }}</div>
        </td>
    </tr>
</table>

{{-- ══ MAIN BODY ══ --}}
<table class="card-body-table" style="padding: 2pt 4pt 1pt 4pt;">
    <tr>
        {{-- Left: Details Column --}}
        <td style="width:165pt;padding-right:3pt;vertical-align:top;">
            {{-- Name --}}
            <div class="field-lbl">Name / <span class="urdu">نام</span></div>
            <div class="field-val-lg">{{ $member->name }}</div>

            {{-- Father --}}
            <div class="field-lbl">Father / <span class="urdu">والد کا نام</span></div>
            <div class="field-val-md">{{ $member->father_name ?? 'N/A' }}</div>

            {{-- Grandfather & Caste --}}
            <table style="width:100%;margin-bottom:1pt;">
                <tr>
                    <td style="width:54%;padding-right:2pt;vertical-align:top;">
                        <div class="field-lbl">Grandfather / <span class="urdu">دادا</span></div>
                        <div class="field-val-sm">{{ $member->grandfather_name ?? 'N/A' }}</div>
                    </td>
                    <td style="padding-left:2pt;vertical-align:top;">
                        <div class="field-lbl">Caste / <span class="urdu">قوم / گوت</span></div>
                        <div class="field-val-sm">{{ $member->caste ?? 'N/A' }}</div>
                    </td>
                </tr>
            </table>

            {{-- CNIC / B-Form --}}
            <div class="field-lbl">{{ $member->cnic ? 'CNIC' : 'B-Form' }} / <span class="urdu">{{ $member->cnic ? 'شناختی کارڈ' : 'ب فارم' }}</span></div>
            <div class="field-val-cnic">{{ $member->cnic ?? ($member->b_form_number ?? 'N/A') }}</div>

            {{-- Community & Blood --}}
            <table style="width:100%;">
                <tr>
                    <td style="width:62%;padding-right:2pt;vertical-align:middle;">
                        <div style="font-size:4.5pt;color:#1b2e25;line-height:1.1;">
                            <strong style="color:#074e37;">Community:</strong> {{ $member->community->name ?? 'N/A' }}
                        </div>
                    </td>
                    <td style="text-align:right;vertical-align:middle;">
                        @if($member->blood_group)
                            <span style="background-color:#b30000;color:#fff;font-size:4.5pt;font-weight:bold;padding:0.8pt 2.5pt;border-radius:1pt;">
                                {{ $member->blood_group }}
                            </span>
                        @endif
                    </td>
                </tr>
            </table>
        </td>

        {{-- Right: Photo & Badge Column --}}
        <td style="width:52pt;text-align:center;vertical-align:top;">
            <div class="photo-frame">
                @if($photoPath)
                    <img src="{{ $photoPath }}" alt="{{ $member->name }}">
                @else
                    <div style="padding-top:12pt;font-size:5.5pt;color:#7a8f84;">Photo / تصویر</div>
                @endif
            </div>

            <div class="type-badge">
                {{ strtoupper(str_replace('_', ' ', $member->member_type)) }}<br>
                <span class="urdu" style="font-size:3.8pt;color:#f0f7f3;">{{ $typeUrdu }}</span>
            </div>

            <div style="font-size:3.2pt;color:#6b7e74;text-align:center;margin-top:1pt;letter-spacing:0.2pt;">
                Reg. No. 3811
            </div>
        </td>
    </tr>
</table>

{{-- ══ SIGNATURE & FOOTER ══ --}}
<table style="width:100%;padding: 0 4pt 1pt 4pt;border-bottom: 2pt solid #074e37;">
    <tr>
        <td style="vertical-align:bottom;padding-bottom:1pt;">
            <span style="font-size:3.5pt;color:#074e37;font-weight:bold;">Govt. Reg. Act XXI 1860</span>
        </td>
        <td style="text-align:right;vertical-align:bottom;padding-bottom:1pt;">
            <div class="sig-line-box">&nbsp;</div>
            <div class="sig-label">Authorized Signatory / <span class="urdu">مجاز دستخط</span></div>
        </td>
    </tr>
</table>

</body>
</html>
