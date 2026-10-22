<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Family Tree - {{ $member->name }}</title>
    <style>
        body {
            font-family: sans-serif, 'urdu', 'noto';
            margin: 0;
            color: #222;
            font-size: 11px;
        }

        .urdu {
            font-family: 'urdu', 'noto', sans-serif;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #1b5e3f;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .org-title {
            font-size: 20px;
            font-weight: bold;
            color: #1b5e3f;
            letter-spacing: 0.5px;
        }

        .doc-subtitle {
            font-size: 12px;
            color: #8a6d1f;
            margin-top: 3px;
        }

        .meta-info {
            font-size: 10px;
            color: #555;
            line-height: 1.4;
        }

        .info-box {
            border: 1px solid #c9a227;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 16px;
            background: #fbf8f0;
        }

        .info-box table {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
        }

        .info-box td {
            border: none;
            padding: 3px 0;
        }

        .tree-wrapper {
            font-size: 11px;
        }

        ul.tree {
            list-style: none;
            padding-left: 18px;
            margin: 0;
            border-left: 1px dashed #c9a227;
        }

        ul.tree.root {
            border-left: none;
            padding-left: 0;
        }

        li.node {
            margin: 6px 0;
            padding-left: 8px;
        }

        .badge {
            display: inline-block;
            font-size: 9px;
            padding: 2px 7px;
            border-radius: 8px;
            color: #fff;
            margin-right: 6px;
        }

        .badge-member {
            background: #1b5e3f;
        }

        .badge-child {
            background: #b8860b;
        }

        .badge-spouse {
            background: #6b8e23;
        }

        .badge-sibling {
            background: #6f42c1;
        }

        .badge-parent {
            background: #2f6690;
        }

        .name {
            font-weight: bold;
        }

        .identifier {
            color: #555;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <table class="header-table">
        <tr>
            <td width="60" style="vertical-align: middle;">
                <img src="{{ public_path('logo.jpeg') }}" style="width: 52px; height: 52px; border-radius: 50%;" alt="Logo">
            </td>
            <td style="vertical-align: middle; padding-left: 10px;">
                <div class="org-title">SINDHI HALAI JAMAT</div>
                <div class="doc-subtitle">
                    Family Tree Report &nbsp;|&nbsp; <span class="urdu">خاندانی شجرہ نسب</span>
                </div>
            </td>
            <td style="text-align: right; vertical-align: middle;">
                <div class="meta-info"><strong>Generated:</strong> {{ now()->format('d M Y, h:i A') }}</div>
                <div class="meta-info"><strong>Membership No:</strong> {{ $member->membership_number ?? ('#' . $member->id) }}</div>
            </td>
        </tr>
    </table>

    <!-- Member Summary Box -->
    <div class="info-box">
        <h3 style="margin: 0 0 6px 0; color: #1b5e3f; font-size: 15px;">{{ $member->name }}</h3>
        <table>
            <tr>
                <td width="50%">
                    <strong>CNIC / <span class="urdu">شناختی کارڈ</span>:</strong> {{ $member->cnic ?? 'N/A' }}
                </td>
                <td width="50%">
                    <strong>Community / <span class="urdu">برادری</span>:</strong> {{ $member->community->name }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>B-Form / <span class="urdu">ب فارم</span>:</strong> {{ $member->b_form_number ?? 'N/A' }}
                </td>
                <td>
                    <strong>Member Type / <span class="urdu">ممبر کی قسم</span>:</strong>
                    {{ ucwords(str_replace('_', ' ', $member->member_type)) }} (<span class="urdu">{{ match($member->member_type) { 'householder' => 'سربراہِ خاندان', 'family_member' => 'خاندان کا فرد', 'child' => 'بچہ', 'spouse' => 'شریکِ حیات', default => ucfirst(str_replace('_', ' ', $member->member_type)) } }}</span>)
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Gender / <span class="urdu">جنس</span>:</strong>
                    {{ ucfirst($member->gender) }} (<span class="urdu">{{ $member->gender === 'male' ? 'مرد' : ($member->gender === 'female' ? 'عورت' : 'دیگر') }}</span>)
                </td>
                <td>
                    <strong>Marital Status / <span class="urdu">ازدواجی حیثیت</span>:</strong>
                    {{ ucfirst($member->marital_status) }} (<span class="urdu">{{ match($member->marital_status) { 'single' => 'غیر شادی شدہ', 'married' => 'شادی شدہ', 'divorced' => 'طلاق یافتہ', 'widowed' => 'بیوہ', default => ucfirst($member->marital_status) } }}</span>)
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Date of Birth / <span class="urdu">تاریخِ پیدائش</span>:</strong> {{ $member->date_of_birth ?? 'N/A' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Tree Structure -->
    <div class="tree-wrapper">
        @if ($treeData)
            @include('members.partials.tree-node', ['node' => $treeData, 'root' => true])
        @else
            <p>No family relationships found for {{ $member->name }}. / <span class="urdu">کوئی خاندانی تعلق نہیں ملا۔</span></p>
        @endif
    </div>
</body>

</html>
