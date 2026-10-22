@extends('layouts.app')

@section('title', 'Add Community')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2">
            <h5 class="mb-0">Add New Community</h5>
            <span dir="rtl" class="fs-6 fw-bold">نئی برادری شامل کریں</span>
        </div>
        <div class="card-body">
            <form action="{{ route('communities.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label d-flex justify-content-between align-items-center">
                        <span>Community Name <span class="text-danger">*</span></span>
                        <span dir="rtl" class="text-secondary fw-semibold">برادری کا نام <span class="text-danger">*</span></span>
                    </label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                        required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label d-flex justify-content-between align-items-center">
                        <span>Description</span>
                        <span dir="rtl" class="text-secondary fw-semibold">تفصیل / نوٹس</span>
                    </label>
                    <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Community / برادری محفوظ کریں</button>
                <a href="{{ route('communities.index') }}" class="btn btn-secondary ms-2">Cancel / منسوخ کریں</a>
            </form>
        </div>
    </div>
@endsection
