<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Attendance System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        /* Navbar Enhancements */
        .navbar {
            padding: 1rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-brand i {
            margin-right: 0.5rem;
        }

        .nav-link {
            font-weight: 500;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
            font-size: 1.05rem;
        }

        .nav-link i {
            margin-right: 0.4rem;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.25);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        /* Theme Toggle Button */
        .theme-toggle {
            font-size: 1.2rem;
            padding: 0.5rem 0.75rem !important;
            transition: all 0.3s ease;
            border-radius: 0.5rem;
        }

        .theme-toggle:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: rotate(20deg);
        }

        /* Dropdown Enhancement */
        .nav-link.dropdown-toggle::after {
            margin-left: 0.4rem;
        }

        .dropdown-menu {
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
            border-radius: 0.25rem;
            margin: 0.25rem 0.5rem;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            border-radius: 0.25rem;
            transform: translateX(4px);
        }

        .dropdown-item i {
            margin-right: 0.5rem;
            width: 1.2rem;
        }

        /* Dark Mode Custom Styles */
        [data-bs-theme="dark"] {
            --bs-body-bg: #1a1a1a;
            --bs-body-color: #e0e0e0;
            --bs-border-color: #404040;
            --bs-card-bg: #2a2a2a;
            --bs-card-border-color: #404040;
        }

        [data-bs-theme="dark"] .navbar {
            background-color: #1a1a1a !important;
            border-bottom: 2px solid #404040;
        }

        [data-bs-theme="dark"] .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        [data-bs-theme="dark"] .nav-link.active {
            background-color: rgba(13, 110, 253, 0.2);
            border-left: 3px solid #0d6efd;
            padding-left: calc(1rem - 3px) !important;
        }

        [data-bs-theme="dark"] .navbar-brand {
            color: #0d6efd !important;
        }

        [data-bs-theme="dark"] .card {
            background-color: #2a2a2a;
            border-color: #404040;
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .card-header {
            background-color: #353535;
            border-color: #404040;
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .table {
            color: #e0e0e0;
            border-color: #404040;
        }

        [data-bs-theme="dark"] .table-hover tbody tr:hover {
            background-color: #353535;
        }

        [data-bs-theme="dark"] .table thead {
            border-color: #404040;
        }

        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: #353535;
            border-color: #404040;
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus {
            background-color: #353535;
            border-color: #0d6efd;
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .form-control::placeholder {
            color: #808080;
        }

        [data-bs-theme="dark"] .dropdown-menu {
            background-color: #2a2a2a;
            border-color: #404040;
        }

        [data-bs-theme="dark"] .dropdown-item {
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .dropdown-item:hover,
        [data-bs-theme="dark"] .dropdown-item:focus {
            background-color: #353535;
            color: #0d6efd;
        }

        [data-bs-theme="dark"] .dropdown-divider {
            border-color: #404040;
        }

        [data-bs-theme="dark"] .btn-outline-primary:not(:hover) {
            color: #0d6efd;
            border-color: #0d6efd;
        }

        [data-bs-theme="dark"] .modal-content {
            background-color: #2a2a2a;
            border-color: #404040;
        }

        [data-bs-theme="dark"] .modal-header {
            background-color: #353535;
            border-color: #404040;
        }

        [data-bs-theme="dark"] .text-muted {
            color: #a0a0a0 !important;
        }

        [data-bs-theme="dark"] .badge {
            filter: brightness(0.9);
        }

        [data-bs-theme="dark"] .progress {
            background-color: #353535;
        }

        [data-bs-theme="dark"] .table-light {
            background-color: #353535;
        }

        [data-bs-theme="dark"] .table-dark {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }

        /* Theme Toggle Button */
        .theme-toggle {
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .theme-toggle:hover {
            transform: rotate(20deg);
        }

        main {
            min-height: calc(100vh - 56px);
        }
    </style>

    @yield('styles')
</head>
<body>
    @auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid px-3 px-md-4">
            <a class="navbar-brand fw-bold" href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('attendance.dashboard')) : route('login') }}">
                <i class="bi bi-clock-history"></i> Attendance System
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto gap-1">
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> <span class="d-lg-inline">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                                <i class="bi bi-people"></i> <span class="d-lg-inline">Users</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.attendances*') ? 'active' : '' }}" href="{{ route('admin.attendances') }}">
                                <i class="bi bi-list"></i> <span class="d-lg-inline">Attendances</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}" href="{{ route('admin.reports') }}">
                                <i class="bi bi-bar-chart"></i> <span class="d-lg-inline">Reports</span>
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('attendance.dashboard') ? 'active' : '' }}" href="{{ route('attendance.dashboard') }}">
                                <i class="bi bi-house"></i> <span class="d-lg-inline">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('attendance.history') ? 'active' : '' }}" href="{{ route('attendance.history') }}">
                                <i class="bi bi-clock-history"></i> <span class="d-lg-inline">History</span>
                            </a>
                        </li>
                    @endif
                </ul>
                
                <ul class="navbar-nav gap-1 align-items-center">
                    <li class="nav-item">
                        <button class="nav-link btn btn-link theme-toggle" id="themeToggle" title="Toggle Dark Mode">
                            <i class="bi bi-moon-stars"></i>
                        </button>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(auth()->user()->profile_picture)
                                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="{{ auth()->user()->name }}" 
                                     class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                            @else
                                <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
                            @endif
                            <span class="fw-bold d-none d-md-inline">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <div class="px-3 py-3 border-bottom">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        @if(auth()->user()->profile_picture)
                                            <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="{{ auth()->user()->name }}" 
                                                 class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                 style="width: 48px; height: 48px;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="mb-0 fw-bold">{{ auth()->user()->name }}</p>
                                            <small class="text-muted">{{ auth()->user()->email }}</small>
                                        </div>
                                    </div>
                                    @if(auth()->user()->isAdmin())
                                        <span class="badge bg-danger">Admin</span>
                                    @else
                                        <span class="badge bg-success">User</span>
                                    @endif
                                </div>
                            </li>
                            @if(!auth()->user()->isAdmin())
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="bi bi-person-circle"></i> My Profile
                                    </a>
                                </li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-bold">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Breadcrumbs -->
    @if(isset($breadcrumbs))
        <x-breadcrumbs :items="$breadcrumbs" />
    @endif
    @endauth

    <main class="py-4">
        <div class="container">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Dark Mode Toggle
        (function() {
            const htmlElement = document.documentElement;
            const themeToggle = document.getElementById('themeToggle');
            
            // Check for saved theme preference, default to 'light'
            const currentTheme = localStorage.getItem('theme') || 'light';
            htmlElement.setAttribute('data-bs-theme', currentTheme);
            updateThemeIcon(currentTheme);
            
            // Theme toggle button click handler
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const theme = htmlElement.getAttribute('data-bs-theme');
                    const newTheme = theme === 'light' ? 'dark' : 'light';
                    
                    htmlElement.setAttribute('data-bs-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateThemeIcon(newTheme);
                });
            }
            
            // Update theme icon
            function updateThemeIcon(theme) {
                const icon = themeToggle?.querySelector('i');
                if (icon) {
                    if (theme === 'dark') {
                        icon.classList.remove('bi-moon-stars');
                        icon.classList.add('bi-sun-fill');
                    } else {
                        icon.classList.remove('bi-sun-fill');
                        icon.classList.add('bi-moon-stars');
                    }
                }
            }
            
            // Auto-dismiss alerts after 5 seconds
            document.addEventListener('DOMContentLoaded', function() {
                const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
                alerts.forEach(function(alert) {
                    setTimeout(function() {
                        if (alert && alert.classList.contains('show')) {
                            const bsAlert = new bootstrap.Alert(alert);
                            bsAlert.close();
                        }
                    }, 5000);
                });
            });
        })();
    </script>

    @yield('scripts')
</body>
</html>