<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Details - {{ $member->name }}</title>
    <style>
        @@font-face {
            font-family: "Urdu";
            src: url("{{ str_replace('\\', '/', resource_path('fonts/NotoNaskhArabic.ttf')) }}");
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1,
        h3 {
            color: #1b5e3f;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #c9a227;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #1b5e3f;
            color: white;
        }

        .section {
            margin-bottom: 10px;
            margin-top: 40px
        }

        .tag {
            text-align: center;
            font-size: 24px;
            color: #1b5e3f;
            margin-bottom: 4px;
        }

        .subtitle {
            text-align: center;
            font-size: 12px;
            color: #8a6d1f;
            margin-bottom: 20px;
        }

        .urdu {
            font-family: "Urdu", serif;
            font-weight: normal;
            font-style: normal;
            color: #8a6d1f;
        }
    </style>
</head>

<body>
    <h1 class="tag">SINDHI HALAI JAMAT</h1>
    <div class="subtitle">Member Details Report &nbsp;|&nbsp; <span class="urdu">@urdu('رکن کی تفصیلات')</span></div>
    <h3>Member Details: {{ $member->name }} {{ $member->community->name }}</h3>
    <div style="float: right; margin-left: 20px;">
        @if ($member->profile_picture)
            <img src="{{ public_path('profiles/' . $member->profile_picture) }}" alt="{{ $member->name }}"
                style="width: 120px; height: 120px; border-radius: 50%; border: 2px solid #1b5e3f;">
        @else
            <img src="{{ public_path('image/default-profile.jpg') }}" alt="Default Profile Picture"
                style="width: 120px; height: 120px; border-radius: 50%; border: 2px solid #1b5e3f;">
        @endif
    </div>
    <div class="section">
        <div style="display: inline-block">
            <p><strong>Membership Number</strong> <span class="urdu">@urdu('رکنیت نمبر')</span>: {{ $member->membership_number ?? 'N/A' }}</p>
            <p><strong>Name</strong> <span class="urdu">@urdu('نام')</span>: {{ $member->name }}</p>
            <p><strong>Father Name</strong> <span class="urdu">@urdu('ولدیت')</span>: {{ $member->father_name ?? 'N/A' }}</p>
            <p><strong>Grandfather Name</strong> <span class="urdu">@urdu('دادا کا نام')</span>: {{ $member->grandfather_name ?? 'N/A' }}</p>
            <p><strong>Cast / Gote</strong> <span class="urdu">@urdu('گوت')</span>: {{ $member->caste ?? 'N/A' }}</p>
            <p><strong>Email</strong> <span class="urdu">@urdu('ای میل')</span>: {{ $member->email ?? 'N/A' }}</p>
            <p><strong>Phone</strong> <span class="urdu">@urdu('فون نمبر')</span>: {{ $member->phone ?? 'N/A' }}</p>
            <p><strong>Gender</strong> <span class="urdu">@urdu('جنس')</span>: {{ ucfirst($member->gender) }}</p>
            <p><strong>{{ $member->cnic ? 'CNIC' : 'B-Form Number' }}</strong> <span class="urdu">{{ \App\Support\Urdu::shape($member->cnic ? 'شناختی کارڈ نمبر' : 'بی فارم نمبر') }}</span>: {{ $member->cnic ?? $member->b_form_number ?? 'N/A' }}</p>
            <p><strong>Date of Birth</strong> <span class="urdu">@urdu('تاریخ پیدائش')</span>: {{ $member->date_of_birth ?? 'N/A' }}</p>
            <p><strong>Blood Group</strong> <span class="urdu">@urdu('خون کا گروہ')</span>: {{ $member->blood_group ?? 'N/A' }}</p>
            <p><strong>Card Received</strong>: {{ $member->card_received ?? 'N/A' }}</p>
        </div>
        <div style="display: inline-block">
            <p><strong>Community</strong> <span class="urdu">@urdu('برادری')</span>: {{ $member->community->name }}</p>
            <p><strong>Member Type</strong>: {{ ucwords(str_replace('_', ' ', $member->member_type)) }}</p>
            <p><strong>Marital Status</strong> <span class="urdu">@urdu('ازدواجی حیثیت')</span>: {{ ucfirst($member->marital_status) }}</p>
            <p><strong>Parent</strong> <span class="urdu">@urdu('والدین')</span>: {{ $member->parent ? $member->parent->name : 'None' }}</p>
            <p><strong>Deceased</strong> <span class="urdu">@urdu('متوفی')</span>: {{ $member->is_deceased ? 'Yes' : 'No' }}</p>
            <p><strong>Death Date</strong> <span class="urdu">@urdu('تاریخ وفات')</span>: {{ $member->death_date ?? 'N/A' }}</p>
            <p><strong>Country</strong> <span class="urdu">@urdu('ملک')</span>: {{ $member->country ?? 'N/A' }}</p>
            <p><strong>Education</strong> <span class="urdu">@urdu('تعلیم')</span>: {{ $member->education ?? 'N/A' }}</p>
            <p><strong>Skill</strong> <span class="urdu">@urdu('ہنر')</span>: {{ $member->skill ?? 'N/A' }}</p>
        </div>
    </div>
    <div>
        <p><strong>Address</strong> <span class="urdu">@urdu('پتہ')</span>: {{ $member->address ?? 'N/A' }}</p>
        <p><strong>Description</strong> <span class="urdu">@urdu('تفصیل')</span>: {{ $member->description ?? 'N/A' }}</p>
    </div>

    <!-- Spouse Details -->
    @if ($member->spouse)
        <h3>Spouse Details &nbsp;<span class="urdu">@urdu('شریک حیات کی تفصیلات')</span></h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>CNIC</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    {{-- <th>Email</th> --}}
                    <th>Member Type</th>
                    <th>Deceased</th>
                    <th>Date of Birth</th>
                    {{-- <th>Death Date</th> --}}
                    <th>Country</th>
                    {{-- <th>Education</th> --}}
                    {{-- <th>Skill</th> --}}
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $member->spouse->name }}</td>
                    <td>{{ $member->spouse->cnic ?? 'N/A' }}</td>
                    <td>{{ ucfirst($member->spouse->gender) }}</td>
                    <td>{{ $member->spouse->phone ?? 'N/A' }}</td>
                    {{-- <td>{{ $member->spouse->email ?? 'N/A' }}</td> --}}
                    <td>{{ ucwords(str_replace('_', ' ', $member->spouse->member_type)) }}</td>
                    <td>{{ $member->spouse->is_deceased ? 'Yes' : 'No' }}</td>
                    <td>{{ $member->spouse->date_of_birth ?? 'N/A' }}</td>
                    {{-- <td>{{ $member->spouse->death_date ?? 'N/A' }}</td> --}}
                    <td>{{ $member->spouse->country ?? 'N/A' }}</td>
                    {{-- <td>{{ $member->spouse->education ?? 'N/A' }}</td> --}}
                    {{-- <td>{{ $member->spouse->skill ?? 'N/A' }}</td> --}}
                </tr>
            </tbody>
        </table>
    @else
        <p><strong>Spouse</strong>: None</p>
    @endif

    <!-- Children Details -->
    <h3>Children Details &nbsp;<span class="urdu">@urdu('بچوں کی تفصیلات')</span></h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>CNIC</th>
                <th>Gender</th>
                <th>Phone</th>
                {{-- <th>Email</th> --}}
                <th>Member Type</th>
                <th>Deceased</th>
                <th>Date of Birth</th>
                {{-- <th>Death Date</th> --}}
                <th>Country</th>
                {{-- <th>Education</th> --}}
                {{-- <th>Skill</th> --}}
            </tr>
        </thead>
        <tbody>
            @forelse($member->children as $child)
                <tr>
                    <td>{{ $child->name }}</td>
                    <td>{{ $child->cnic ?? 'N/A' }}</td>
                    <td>{{ ucfirst($child->gender) }}</td>
                    <td>{{ $child->phone ?? 'N/A' }}</td>
                    {{-- <td>{{ $child->email ?? 'N/A' }}</td> --}}
                    <td>{{ ucwords(str_replace('_', ' ', $child->member_type)) }}</td>
                    <td>{{ $child->is_deceased ? 'Yes' : 'No' }}</td>
                    <td>{{ $child->date_of_birth ?? 'N/A' }}</td>
                    {{-- <td>{{ $child->death_date ?? 'N/A' }}</td> --}}
                    <td>{{ $child->country ?? 'N/A' }}</td>
                    {{-- <td>{{ $child->education ?? 'N/A' }}</td> --}}
                    {{-- <td>{{ $child->skill ?? 'N/A' }}</td> --}}
                </tr>
            @empty
                <tr>
                    <td colspan="8">No children found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
