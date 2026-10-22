<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ID Card Back</title>
@include('members.partials.card-styles')
</head>
<body>
@php $logoPath = public_path('logo.jpeg'); @endphp

{{-- ══ HEADER ══ --}}
<table style="width:100%;background-color:#064433;border-bottom:1.5pt solid #c9a227;"><tr>
    <td style="width:20pt;padding:2.5pt 0 2.5pt 5pt;vertical-align:middle;">
        @if(file_exists($logoPath))
        <img src="{{ $logoPath }}" style="width:15pt;height:15pt;" alt="">
        @endif
    </td>
    <td style="padding:2.5pt 0 2.5pt 3pt;vertical-align:middle;">
        <div style="color:#ffffff;font-size:7.5pt;font-weight:bold;letter-spacing:0.3pt;line-height:1;">SINDHI HALAI JAMAT</div>
        <div style="color:#c3ddd0;font-size:3.8pt;letter-spacing:0.2pt;margin-top:0.8pt;line-height:1;">MEMBER DETAILS &bull; <span class="urdu">تفصیلاتِ رکن</span></div>
    </td>
</tr></table>

{{-- ══ BODY ══ --}}
<div style="padding:3pt 5pt 1.5pt 5pt;">

    <div style="font-size:5.5pt;font-weight:bold;color:#064433;border-bottom:0.5pt solid #064433;padding-bottom:1pt;margin-bottom:2pt;letter-spacing:0.3pt;">PARTICULARS &bull; <span class="urdu">کوائف ممبر</span></div>

    <table style="width:100%;"><tr>
        <td style="width:65%;padding-right:3pt;vertical-align:top;">
            <div style="font-size:5pt;color:#1a2b23;margin-bottom:1pt;line-height:1.35;"><strong style="color:#064433;">Community / <span class="urdu">برادری</span>:</strong> {{ $member->community->name ?? 'N/A' }}</div>
            <div style="font-size:5pt;color:#1a2b23;margin-bottom:1pt;line-height:1.35;"><strong style="color:#064433;">Gender / <span class="urdu">جنس</span>:</strong> {{ ucfirst($member->gender) }} &nbsp;|&nbsp; <strong style="color:#064433;">Status:</strong> {{ ucfirst($member->marital_status) }}</div>
            <div style="font-size:5pt;color:#1a2b23;margin-bottom:1pt;line-height:1.35;"><strong style="color:#064433;">DOB / <span class="urdu">پیدائش</span>:</strong> {{ $member->date_of_birth ?? 'N/A' }}</div>
            <div style="font-size:5pt;color:#1a2b23;margin-bottom:1pt;line-height:1.35;"><strong style="color:#064433;">Blood / <span class="urdu">خون</span>:</strong> <span style="color:#8b0000;font-weight:bold;">{{ $member->blood_group ?? 'N/A' }}</span> &nbsp;|&nbsp; <strong style="color:#064433;">Phone:</strong> {{ $member->phone ?? 'N/A' }}</div>
            <div style="font-size:5pt;color:#1a2b23;margin-bottom:1pt;line-height:1.35;"><strong style="color:#064433;">Address / <span class="urdu">پتہ</span>:</strong> {{ \Illuminate\Support\Str::limit($member->address ?? 'N/A', 42) }}</div>
            <div style="font-size:5pt;color:#1a2b23;margin-bottom:0;line-height:1.35;"><strong style="color:#064433;">Issued / <span class="urdu">اجراء</span>:</strong> {{ $member->created_at?->format('d M Y') ?? 'N/A' }}</div>
        </td>
        <td style="text-align:center;vertical-align:top;">
            <div style="width:52pt;height:28pt;border:0.6pt dashed #c9a227;background-color:#fdfdf8;text-align:center;padding-top:4pt;margin-left:auto;">
                <div style="font-size:4.2pt;font-weight:bold;color:#8a7c30;text-transform:uppercase;letter-spacing:0.2pt;">Office Stamp</div>
                <div style="font-size:3.8pt;color:#8a7c30;margin-top:1pt;" class="urdu">دفتری مہر</div>
            </div>
        </td>
    </tr></table>

    <div style="background-color:#f2f6f3;border:0.4pt solid #d0ddd6;padding:1.5pt 3pt;margin-top:2pt;text-align:center;">
        <div style="font-size:3.6pt;color:#4a5c54;line-height:1.2;">Property of Sindhi Halai Jamat &bull; Non-transferable. If found, return to Jamat office.</div>
        <div style="font-size:3.8pt;color:#064433;margin-top:0.5pt;line-height:1.2;" class="urdu">یہ کارڈ سندھی ہالائی جماعت کی ملکیت ہے۔ گُم ہونے پر دفتر میں جمع کروائیں۔</div>
    </div>

</div>

{{-- ══ FOOTER STRIPE ══ --}}
<div style="width:100%;background-color:#064433;height:2pt;"></div>

</body>
</html>