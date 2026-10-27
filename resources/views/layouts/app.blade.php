<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jamaat Management - @yield('title')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo.jpeg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .table-responsive {
            overflow-x: auto;
            overflow-y: visible;
        }
        .dropdown-menu {
            z-index: 1050;
        }
    </style>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @yield('styles')
</head>

<body>
    <!-- Navbar sirf tab dikhe jab user logged in ho -->
    @auth
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('members.index') }}">
                    <img src="{{ asset('logo.jpeg') }}" alt="Sindhi Halai Jamat Logo" class="rounded-circle bg-white p-1 me-2 shadow-sm" style="width: 36px; height: 36px; object-fit: contain;">
                    <span>Sindhi Halai Jamat</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('members.index') }}">Members List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('members.create') }}">Add Member</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('members.select-family-tree') }}">Family Tree</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('communities.index') }}">Communities</a>
                        </li>
                        @if (auth()->check() && auth()->user()->hasRole('admin'))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Users Management
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"> Users</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users.create') }}">Create User</a>
                                    </li>
                                    {{-- <li><a class="dropdown-item" href="{{ route('admin.permissions') }}">Manage
                                            Permissions</a></li> --}}
                                    <li><a class="dropdown-item" href="{{ route('admin.roles') }}">Manage Roles</a></li>

                                    <li><a class="dropdown-item" href="{{ route('admin.audit_logs') }}">Audit Logs</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('members.report') }}">Members Report</a>
                            </li>
                        @endif
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <span class="nav-link text-white">Welcome, {{ Auth::user()->name }}</span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-white">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endauth

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @yield('scripts')
</body>

</html>
