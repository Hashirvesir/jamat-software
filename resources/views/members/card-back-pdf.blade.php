<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Membership Card Back - {{ $member->name }}</title>
@include('members.partials.card-styles')
</head>
<body>
<div class="page"><table class="header"><tr>
    <td width="24"><div class="seal">SHJ</div></td>
    <td style="padding-left:6pt;">
        <div class="org-name">SINDHI HALAI JAMAT</div>
        <div class="org-subtitle">MEMBERSHIP IDENTITY CARD — BACK</div>
    </td>
</tr></table>
<div class="body">
<div class="back-title">Additional Information</div>
<table style="width:100%;margin-top:1pt;"><tr>
    <td style="width:64%;">
        <div class="back-line"><strong>Community</strong>: {{ $member->community->name ?? 'N/A' }}</div>
        <div class="back-line"><strong>Gender</strong>: {{ ucfirst($member->gender) }} &nbsp;|&nbsp; <strong>Status</strong>: {{ ucfirst($member->marital_status) }}</div>
        <div class="back-line"><strong>DOB</strong>: {{ $member->date_of_birth ?? 'N/A' }} &nbsp;|&nbsp; <strong>Blood</strong>: {{ $member->blood_group ?? 'N/A' }}</div>
        <div class="back-line"><strong>Phone</strong>: {{ $member->phone ?? 'N/A' }}</div>
        <div class="back-line"><strong>Address</strong>: {{ $member->address ?? 'N/A' }}</div>
        <div class="back-line"><strong>Issued</strong>: {{ $member->created_at?->format('d M Y') ?? 'N/A' }}</div>
    </td>
    <td style="text-align:center;vertical-align:top;">
        <div class="stamp-box">Office Stamp</div>
    </td>
</tr></table>
<div class="note" style="margin-top:2pt;">
    Property of Sindhi Halai Jamat. Non-transferable — return to the Jamat office if found.
</div>
</div>
</div></body>
</html>