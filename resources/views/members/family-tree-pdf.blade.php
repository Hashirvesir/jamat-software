<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Family Tree - {{ $member->name }}</title>
    <style>
        @@font-face {
            font-family: "Urdu";
            src: url("{{ str_replace('\\', '/', resource_path('fonts/NotoNaskhArabic.ttf')) }}");
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 25px;
            color: #222;
        }

        .urdu {
            font-family: "Urdu", serif;
            font-weight: normal;
            font-style: normal;
        }

        .tag {
            text-align: center;
            font-size: 22px;
            color: #1b5e3f;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .subtitle {
            text-align: center;
            font-size: 14px;
            color: #8a6d1f;
            margin-bottom: 6px;
        }

        .meta {
            text-align: right;
            font-size: 10px;
            color: #888;
            margin-bottom: 14px;
        }

        .info-box {
            border: 1px solid #c9a227;
            border-radius: 6px;
            padding: 10px 16px;
            margin-bottom: 20px;
            background: #fbf8f0;
        }

        .info-box h3 {
            margin: 0 0 8px 0;
            color: #1b5e3f;
            font-size: 16px;
        }

        .info-box table {
            width: 100%;
            font-size: 12px;
        }

        .info-box td {
            border: none;
            padding: 2px 0;
        }

        .tree-wrapper {
            font-size: 12px;
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
    <div class="tag">SINDHI HALAI JAMAT</div>
    <div class="subtitle">Family Tree Report &nbsp;|&nbsp; <span class="urdu">@urdu('خاندانی شجرہ')</span></div>
    <div class="meta">Generated on {{ now()->format('d M Y, h:i A') }}</div>

    <div class="info-box">
        <h3>{{ $member->name }}</h3>
        <table>
            <tr>
                <td><strong>{{ $member->cnic ? 'CNIC' : 'B-Form No.' }}</strong> <span class="urdu">{{ \App\Support\Urdu::shape($member->cnic ? 'شناختی کارڈ نمبر' : 'بی فارم نمبر') }}</span>:
                    {{ $member->cnic ?? $member->b_form_number ?? 'N/A' }}</td>
                <td><strong>Community</strong> <span class="urdu">@urdu('برادری')</span>: {{ $member->community->name }}</td>
            </tr>
            <tr>
                <td><strong>Gender</strong> <span class="urdu">@urdu('جنس')</span>: {{ ucfirst($member->gender) }}</td>
                <td><strong>Member Type</strong>: {{ ucwords(str_replace('_', ' ', $member->member_type)) }}</td>
            </tr>
            <tr>
                <td><strong>Marital Status</strong> <span class="urdu">@urdu('ازدواجی حیثیت')</span>: {{ ucfirst($member->marital_status) }}</td>
                <td><strong>Date of Birth</strong> <span class="urdu">@urdu('تاریخ پیدائش')</span>: {{ $member->date_of_birth ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="tree-wrapper">
        @if ($treeData)
            @include('members.partials.tree-node', ['node' => $treeData, 'root' => true])
        @else
            <p>No family relationships found for {{ $member->name }}.</p>
        @endif
    </div>
</body>

</html>
