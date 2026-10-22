<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Membership Card Back - {{ $member->name }}</title>
@include('members.partials.card-styles')
</head>
<body>

<div class="card">
    <table class="card-header">
        <tr>
            <td width="23"><div class="seal-circle">SHJ</div></td>
            <td style="padding-left:5pt;">
                <div class="brand-title">SINDHI HALAI JAMAT</div>
                <div class="brand-sub">VERIFICATION &amp; DETAILS &bull; <span class="urdu">دفتری تصدیق و کوائف</span></div>
            </td>
        </tr>
    </table>

    <div class="card-body">
        <div class="back-sec-title">MEMBER PARTICULARS &bull; <span class="urdu">کوائف ممبر</span></div>

        <table style="width:100%;">
            <tr>
                <td style="width:66%;padding-right:4pt;">
                    <div class="back-info-row"><strong>Community</strong> / برادری: {{ $member->community->name ?? 'N/A' }}</div>
                    <div class="back-info-row"><strong>Gender</strong> / جنس: {{ ucfirst($member->gender) }} ({{ $member->gender === 'male' ? 'مرد' : 'عورت' }}) &nbsp;|&nbsp; <strong>Status</strong>: {{ ucfirst($member->marital_status) }}</div>
                    <div class="back-info-row"><strong>DOB</strong> / پیدائش: {{ $member->date_of_birth ?? 'N/A' }} &nbsp;|&nbsp; <strong>Blood</strong>: <span class="blood-badge">{{ $member->blood_group ?? 'N/A' }}</span></div>
                    <div class="back-info-row"><strong>Phone</strong> / رابطہ: {{ $member->phone ?? 'N/A' }}</div>
                    <div class="back-info-row"><strong>Address</strong> / پتہ: {{ \Illuminate\Support\Str::limit($member->address ?? 'N/A', 38) }}</div>
                    <div class="back-info-row"><strong>Issued</strong> / تاریخِ اجراء: {{ $member->created_at?->format('d M Y') ?? 'N/A' }}</div>
                </td>
                <td style="text-align:center;">
                    <div class="stamp-box">
                        <div class="stamp-title">OFFICIAL STAMP</div>
                        <div class="stamp-sub urdu">دفتری تصدیق و مہر</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="disclaimer-box">
            <div class="disc-en">This card is the property of Sindhi Halai Jamat. Non-transferable.</div>
            <div class="disc-ur urdu">یہ کارڈ سندھی ہالائی جماعت کی ملکیت ہے۔ گمشدگی پر جماعت کے دفتر میں جمع کروائیں۔</div>
        </div>
    </div>

    <div class="bottom-stripe"></div>
</div>

</body>
</html>