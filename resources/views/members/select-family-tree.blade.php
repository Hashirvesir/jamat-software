@extends('layouts.app')

@section('title', 'Select Family Tree')

@section('content')
    @php
        $memberTypeLabels = [
            'householder' => 'Householder / سربراہِ خاندان',
            'family_member' => 'Family Member / خاندان کا فرد',
            'child' => 'Child / بچہ',
            'spouse' => 'Spouse / شریکِ حیات',
            'sibling' => 'Sibling / بہن بھائی',
        ];
    @endphp
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Select Member for Family Tree</h5>
            <span dir="rtl" class="text-white-50">شجرہ نسب کے لیے ممبر منتخب کریں</span>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form method="GET" action="{{ route('members.select-family-tree') }}">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="search" class="form-label mb-0">Search by Name, CNIC or B-Form Number</label>
                        <span dir="rtl" class="text-muted small">نام، شناختی کارڈ یا ب فارم نمبر سے تلاش کریں</span>
                    </div>
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control"
                            placeholder="Enter name, CNIC or B-Form number / نام، شناختی کارڈ یا ب فارم نمبر درج کریں" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Search / تلاش</button>
                        @if (request('search'))
                            <a href="{{ route('members.select-family-tree') }}"
                                class="btn btn-outline-secondary">Clear / صاف کریں</a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name / نام</th>
                            <th>CNIC / شناختی کارڈ</th>
                            <th>B-Form Number / ب فارم نمبر</th>
                            <th>Community / برادری</th>
                            <th>Type / قسم</th>
                            <th>Action / عمل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr>
                                <td>
                                    <a href="{{ route('members.show', $member) }}" class="fw-bold text-primary text-decoration-none">
                                        {{ $member->name }}
                                    </a>
                                </td>
                                <td>{{ $member->cnic ?? 'N/A' }}</td>
                                <td>{{ $member->b_form_number ?? 'N/A' }}</td>
                                <td>{{ $member->community?->name ?? 'N/A' }}</td>
                                <td>{{ $memberTypeLabels[$member->member_type] ?? ucfirst(str_replace('_', ' ', $member->member_type)) }}</td>
                                <td>
                                    <a href="{{ route('members.family-tree', $member) }}"
                                        class="btn btn-primary btn-sm">View Family Tree / شجرہ نسب دیکھیں</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No members found. / کوئی ممبر نہیں ملا۔</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-4">
                {{ $members->links('pagination::bootstrap-5') }}
            </div>
            <a href="{{ route('members.index') }}" class="btn btn-secondary mt-3">Back to Members / واپس ممبرز لسٹ</a>
        </div>
    </div>
@endsection
