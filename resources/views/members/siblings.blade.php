@extends('layouts.app')

@section('title', 'Siblings of ' . $member->name)

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Siblings of {{ $member->name }}</h5>
        </div>
        <div class="card-body">
            @if ($siblings->isEmpty())
                <div class="alert alert-warning">No siblings found for {{ $member->name }}.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($siblings as $sibling)
                                <tr>
                                    <td>{{ $sibling->name }}</td>
                                    <td>{{ ucfirst($sibling->gender) }}</td>
                                    <td>{{ ucfirst($sibling->member_type) }}</td>
                                    <td>
                                        <a href="{{ route('members.edit', $sibling) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <a href="{{ route('members.family-tree', $sibling) }}"
                                            class="btn btn-info btn-sm">Family Tree</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <a href="{{ route('members.index') }}" class="btn btn-secondary mt-3">Back to Members</a>
            <a href="{{ route('members.select-family-tree') }}" class="btn btn-secondary mt-3">Select Family Tree</a>
        </div>
    </div>
@endsection
