<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Membership Card Front - {{ $member->name }}</title>
@include('members.partials.card-styles')
</head>
<body>
<div class="page"><table class="header"><tr>
    <td width="24"><div class="seal">SHJ</div></td>
    <td style="padding-left:6pt;">
        <div class="org-name">SINDHI HALAI JAMAT</div>
        <div class="org-subtitle">MEMBERSHIP IDENTITY CARD</div>
    </td>
    <td width="62" style="text-align:center;"><div class="membership-box">{{ $member->membership_number ?? ('#' . $member->id) }}</div></td>
</tr></table>
<div class="body">
<table style="width:100%;"><tr>
    <td style="padding-right:6pt;">
        <div class="label-en">Name</div>
        <div class="value">{{ $member->name }}</div>

        <div class="label-en" style="margin-top:3pt;">Father Name</div>
        <div class="value">{{ $member->father_name ?? 'N/A' }}</div>

        <table style="width:100%;margin-top:3pt;"><tr>
            <td width="55%">
                <div class="label-en">Grandfather</div>
                <div class="value">{{ $member->grandfather_name ?? 'N/A' }}</div>
            </td>
            <td>
                <div class="label-en">Cast</div>
                <div class="value">{{ $member->caste ?? 'N/A' }}</div>
            </td>
        </tr></table>

        <div class="label-en" style="margin-top:3pt;">
            {{ $member->cnic ? 'CNIC Number' : 'B-Form Number' }}
        </div>
        <div class="value">{{ $member->cnic ?? $member->b_form_number ?? 'N/A' }}</div>
    </td>
    <td width="46">
        <div class="photo-box">
            @if ($member->profile_picture)
                <img src="{{ public_path('profiles/' . $member->profile_picture) }}" alt="{{ $member->name }}">
            @else
                <img src="{{ public_path('image/default-profile.jpg') }}" alt="Default">
            @endif
        </div>
        <div class="photo-caption">{{ ucwords(str_replace('_', ' ', $member->member_type)) }}</div>
    </td>
</tr></table>
<table style="width:100%;margin-top:3pt;"><tr>
    <td></td>
    <td width="90"><div class="signature">Authorized Signatory</div></td>
</tr></table>
</div>
</div></body>
</html>