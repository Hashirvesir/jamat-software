@extends('layouts.app')

@section('title', 'Member Details')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Member Details: {{ $member->name }} ({{ $member->community->name }})</h5>
            <div>
                <a href="{{ route('members.download-pdf', $member) }}" class="btn btn-light btn-sm me-2">Download PDF</a>
                <a href="{{ route('members.card', $member) }}" class="btn btn-light btn-sm">Download ID Card</a>
                {{-- <button onclick="window.print()" class="btn btn-light btn-sm">Print</button> --}}
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    @if ($member->profile_picture)
                        <img src="{{ asset('profiles/' . $member->profile_picture) }}" alt="{{ $member->name }}"
                            class="img-fluid rounded-circle border border-gray" style="width: 150px; height: 150px;">
                    @else
                        <img src="{{ asset('image/default-profile.jpg') }}" alt="Default Profile Picture"
                            class="img-fluid rounded-circle border border-gray" style="width: 150px; height: 150px;">
                    @endif
                </div>
                <div class="col-md-4">
                    <p><strong>Name:</strong> {{ $member->name }}</p>
                    <p><strong>Membership Number:</strong> {{ $member->membership_number ?? 'N/A' }}</p>
                    <p><strong>Father Name:</strong> {{ $member->father_name ?? 'N/A' }}</p>
                    <p><strong>Grandfather Name:</strong> {{ $member->grandfather_name ?? 'N/A' }}</p>
                    <p><strong>Cast / Gote:</strong> {{ $member->caste ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $member->email ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $member->phone ?? 'N/A' }}</p>
                    <p><strong>Gender:</strong> {{ ucfirst($member->gender) }}</p>
                    <p><strong>CNIC:</strong> {{ $member->cnic ?? 'N/A' }}</p>
                    <p><strong>Date of Birth:</strong> {{ $member->date_of_birth ?? 'N/A' }}</p>
                    <p><strong>Blood Group:</strong> {{ $member->blood_group ?? 'N/A' }}</p>
                    <p><strong>Card Received:</strong> {{ $member->card_received ?? 'N/A' }}</p>
                    <p><strong>B-Form Number:</strong> {{ $member->b_form_number ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Community:</strong> {{ $member->community->name }}</p>
                    <p><strong>Member Type:</strong> {{ ucfirst($member->member_type) }}</p>
                    <p><strong>Marital Status:</strong> {{ ucfirst($member->marital_status) }}</p>
                    <p><strong>Parent:</strong> {{ $member->parent ? $member->parent->name : 'None' }}</p>
                    <p><strong>Deceased:</strong> {{ $member->is_deceased ? 'Yes' : 'No' }}</p>
                    <p><strong>Death Date:</strong> {{ $member->death_date ?? 'N/A' }}</p>
                    <p><strong>Country:</strong> {{ $member->country ?? 'N/A' }}</p>
                    <p><strong>Education:</strong> {{ $member->education ?? 'N/A' }}</p>
                    <p><strong>Skill:</strong> {{ $member->skill ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <p><strong>Address:</strong> {{ $member->address ?? 'N/A' }}</p>
                    <p><strong>Description:</strong> {{ $member->description ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Spouse Details -->
            @if ($member->spouse)
                <h5 class="mt-4">Spouse Details</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>CNIC</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Member Type</th>
                                <th>Deceased</th>
                                <th>Date of Birth</th>
                                <th>Death Date</th>
                                <th>Country</th>
                                <th>Education</th>
                                <th>Skill</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $member->spouse->name }}</td>
                                <td>{{ $member->spouse->cnic ?? 'N/A' }}</td>
                                <td>{{ ucfirst($member->spouse->gender) }}</td>
                                <td>{{ $member->spouse->phone ?? 'N/A' }}</td>
                                <td>{{ $member->spouse->email ?? 'N/A' }}</td>
                                <td>{{ ucfirst($member->spouse->member_type) }}</td>
                                <td>{{ $member->spouse->is_deceased ? 'Yes' : 'No' }}</td>
                                <td>{{ $member->spouse->date_of_birth ?? 'N/A' }}</td>
                                <td>{{ $member->spouse->death_date ?? 'N/A' }}</td>
                                <td>{{ $member->spouse->country ?? 'N/A' }}</td>
                                <td>{{ $member->spouse->education ?? 'N/A' }}</td>
                                <td>{{ $member->spouse->skill ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mt-4"><strong>Spouse:</strong> None</p>
            @endif

            <!-- Children Details -->
            <h5 class="mt-4">Children Details</h5>
            <div class="table-responsive">
                <table class="table table-striped">
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
                                <td>{{ ucfirst($child->member_type) }}</td>
                                <td>{{ $child->is_deceased ? 'Yes' : 'No' }}</td>
                                <td>{{ $child->date_of_birth ?? 'N/A' }}</td>
                                {{-- <td>{{ $child->death_date ?? 'N/A' }}</td> --}}
                                <td>{{ $child->country ?? 'N/A' }}</td>
                                {{-- <td>{{ $child->education ?? 'N/A' }}</td> --}}
                                {{-- <td>{{ $child->skill ?? 'N/A' }}</td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">No children found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('members.index') }}" class="btn btn-secondary mt-3">Back to Members</a>
        </div>
    </div>
@endsection
