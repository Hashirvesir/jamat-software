<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ID Card Back - {{ $member->name }}</title>
@include('members.partials.card-styles')
</head>
<body>
@php
    $logoPath = public_path('logo.jpeg');
    $membershipNumber = $member->membership_number
        ? mb_substr($member->membership_number, 0, 15)
        : ('SHJ-' . str_pad($member->id, 4, '0', STR_PAD_LEFT));

    $cleanAddress = preg_replace('/\s+/', ' ', trim((string)($member->address ?? 'N/A')));
    $shortAddress = \Illuminate\Support\Str::limit($cleanAddress, 65);
    $issueDate = $member->created_at?->format('d-M-Y') ?? date('d-M-Y');
    $dobFormatted = $member->date_of_birth ? \Carbon\Carbon::parse($member->date_of_birth)->format('d-M-Y') : 'N/A';
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
                        <div class="hdr-title-sub">MEMBER PARTICULARS &bull; <span class="urdu">کوائف رکن</span></div>
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
                    <!-- LEFT PARTICULARS TABLE -->
                    <td style="width: 172pt; padding-right: 4pt; vertical-align: top;">
                        <table class="kv-tbl">
                            <tr>
                                <td class="kv-lbl-back">Gender / Status</td>
                                <td class="kv-val-back">
                                    {{ ucfirst($member->gender ?? 'N/A') }}
                                    &bull;
                                    {{ ucfirst($member->marital_status ?? 'N/A') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="kv-lbl-back">Date of Birth</td>
                                <td class="kv-val-back">
                                    {{ $dobFormatted }}
                                    &bull;
                                    Blood: <span style="color: #b30000; font-weight: bold;">{{ $member->blood_group ?? 'N/A' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="kv-lbl-back">Phone</td>
                                <td class="kv-val-back" style="color: #074e37; font-weight: bold;">{{ $member->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="kv-lbl-back" style="vertical-align: top;">Address</td>
                                <td class="kv-val-back" style="font-size: 5pt; line-height: 1.15; font-weight: normal;">{{ $shortAddress }}</td>
                            </tr>
                            <tr>
                                <td class="kv-lbl-back" style="border-bottom: none;">Date of Issue</td>
                                <td class="kv-val-back" style="border-bottom: none;">{{ $issueDate }}</td>
                            </tr>
                        </table>
                    </td>

                    <!-- RIGHT STAMP BOX -->
                    <td style="width: 52pt; text-align: center; vertical-align: middle;">
                        <div class="stamp-box">
                            <div style="font-size: 4.2pt; font-weight: bold; color: #8a7c30; text-transform: uppercase;">Office Stamp</div>
                            <div style="font-size: 3.8pt; color: #8a7c30; margin-top: 1pt;" class="urdu">دفتری مہر</div>
                            <div style="font-size: 3.5pt; color: #a1934e; margin-top: 6pt;">VERIFIED</div>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- FOOTER DISCLAIMER -->
    <tr>
        <td style="padding: 0 4pt 2pt 4pt; vertical-align: bottom;">
            <table class="disclaimer-tbl">
                <tr>
                    <td style="padding: 2pt 3pt; text-align: center;">
                        <div style="font-size: 3.8pt; color: #1e293b; line-height: 1;">
                            Property of Sindhi Halai Jamat &bull; Non-transferable. If found, return to Jamat Office.
                        </div>
                        <div class="urdu" dir="rtl" style="font-size: 4.2pt; color: #074e37; margin-top: 1pt; line-height: 1;">
                            یہ کارڈ سندھی ہالائی جماعت کی ملکیت ہے۔ گُم ہونے کی صورت میں دفتر میں جمع کروائیں۔
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>
