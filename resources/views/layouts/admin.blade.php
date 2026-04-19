<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Photobooth</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #0d0d0d; color: #e0e0e0; }
        .sidebar { width: 240px; min-height: 100vh; background: #111; border-right: 1px solid #222; position: fixed; top: 0; left: 0; padding: 1.5rem 1rem; }
        .sidebar .brand { font-size: 1.2rem; font-weight: 800; color: #e040fb; margin-bottom: 2rem; display: block; }
        .sidebar .nav-link { color: #aaa; border-radius: 8px; margin-bottom: .25rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #1e1e1e; color: #e040fb; }
        .sidebar .nav-link i { margin-right: .5rem; }
        .main-content { margin-left: 240px; padding: 2rem; }
        .stat-card { background: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 12px; padding: 1.5rem; }
        .stat-card .value { font-size: 2rem; font-weight: 700; color: #e040fb; }
        .stat-card .label { color: #888; font-size: .85rem; }
        .table { color: #ddd; }
        .table thead th { border-color: #2a2a2a; color: #888; font-size: .8rem; text-transform: uppercase; }
        .table td, .table th { border-color: #1e1e1e; vertical-align: middle; }
        .badge-active    { background: #0d6efd; }
        .badge-completed { background: #198754; }
        .badge-pending   { background: #ffc107; color: #000; }
        .badge-expired   { background: #6c757d; }
        .badge-paid      { background: #198754; }
        .badge-failed    { background: #dc3545; }
        .card { background: #1a1a1a; border: 1px solid #2a2a2a; }
    </style>
    @stack('styles')
</head>
<body>
<div class="sidebar">
    <span class="brand">📸 Photobooth</span>
    <nav class="nav flex-column">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('admin.sessions') }}" class="nav-link {{ request()->routeIs('admin.sessions*') ? 'active' : '' }}">
            <i class="bi bi-camera"></i> Sessions
        </a>
        <a href="{{ route('admin.payments') }}" class="nav-link {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Payments
        </a>
    </nav>
</div>

<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
