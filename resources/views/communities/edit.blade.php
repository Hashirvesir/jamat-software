@extends('layouts.app')

@section('title', 'Edit Community')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Community: {{ $community->name }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('communities.update', $community) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Community Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ old('name', $community->name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description', $community->description) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Community</button>
                <a href="{{ route('communities.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
