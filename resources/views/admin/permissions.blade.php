@extends('layouts.app')

@section('title', 'Permission Management')

@section('content')
    <div class="container">
        <h1>Permission Management</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h2>Add Permission</h2>
        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input type="text" name="name" class="form-control" placeholder="Permission Name" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Permission</button>
        </form>

        <h2>Existing Permissions</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                        <tr>
                            <td>{{ $permission->name }}</td>
                            <td>
                                <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST"
                                    class="d-flex flex-wrap gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" class="form-control"
                                        style="min-width: 150px; max-width: 300px;"
                                        value="{{ $permission->name }}" required>
                                    <button type="submit" class="btn btn-success">Update</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
