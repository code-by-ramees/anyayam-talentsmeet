<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', "College Fest Management System — SNEC Talents' Meet")</title>
    
    <!-- Bootstrap 5.3 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --fest-teal: #0d9488;
            --fest-teal-dark: #0f766e;
            --fest-coral: #e11d48;
            --fest-gold: #f59e0b;
            --fest-green: #10b981;
            --fest-dark: #0f172a;
            --fest-bg: #f8fafc;
            --fest-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--fest-bg);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-brand-strip {
            height: 4px;
            background: linear-gradient(90deg, #0d9488 0%, #0d9488 35%, #e11d48 35%, #e11d48 65%, #f59e0b 65%, #f59e0b 85%, #10b981 85%, #10b981 100%);
        }

        .navbar-fest {
            background: #ffffff;
            border-bottom: 1px solid var(--fest-border);
            padding: 0.75rem 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        .brand-logo-img {
            height: 44px;
            object-fit: contain;
        }

        .fest-card {
            border: 1px solid var(--fest-border);
            border-radius: 1rem;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .fest-card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        }

        .btn-fest-primary {
            background-color: var(--fest-teal);
            color: #ffffff;
            font-weight: 600;
            border-radius: 50rem;
            border: none;
            padding: 0.6rem 1.4rem;
            transition: all 0.2s ease;
        }

        .btn-fest-primary:hover {
            background-color: var(--fest-teal-dark);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .rank-badge-1 {
            background: linear-gradient(135deg, #fbbf24, #d97706);
            color: white;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            box-shadow: 0 3px 10px rgba(217, 119, 6, 0.35);
        }

        .rank-badge-2 {
            background: linear-gradient(135deg, #94a3b8, #64748b);
            color: white;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .rank-badge-3 {
            background: linear-gradient(135deg, #d97706, #92400e);
            color: white;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="top-brand-strip"></div>

    <nav class="navbar navbar-expand-lg navbar-fest sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('public.index') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Fest Logo" class="brand-logo-img">
                <div class="d-none d-sm-block">
                    <span class="fw-extrabold text-dark d-block lh-1" style="font-size: 1.1rem;">COLLEGE FEST</span>
                    <small class="text-muted fs-xs fw-bold text-uppercase" style="color: var(--fest-teal) !important;">Bakalooriyya & Sanaviyya</small>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="publicNav">
                <ul class="navbar-nav me-auto ms-lg-4 mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-bold {{ request()->routeIs('public.index') ? 'text-primary' : 'text-secondary' }}" href="{{ route('public.index') }}">
                            <i class="bi bi-trophy me-1"></i> Live Leaderboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-bold {{ request()->routeIs('public.rankings') ? 'text-primary' : 'text-secondary' }}" href="{{ route('public.rankings') }}">
                            <i class="bi bi-bar-chart-line me-1"></i> Overall & Section Ranks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-bold {{ request()->routeIs('public.results') ? 'text-primary' : 'text-secondary' }}" href="{{ route('public.results') }}">
                            <i class="bi bi-award me-1"></i> Published Results
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-bold {{ request()->routeIs('public.programs') ? 'text-primary' : 'text-secondary' }}" href="{{ route('public.programs') }}">
                            <i class="bi bi-calendar-event me-1"></i> Programs Schedule
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <!-- Group Portal Login Button -->
                    <a href="{{ route('group.login') }}" class="btn btn-outline-primary rounded-pill px-3 btn-sm fw-bold">
                        <i class="bi bi-people-fill me-1"></i> Group Portal
                    </a>

                    <!-- Super Admin Login Button -->
                    <a href="{{ route('admin.login') }}" class="btn btn-dark rounded-pill px-3 btn-sm fw-bold">
                        <i class="bi bi-shield-lock-fill me-1"></i> Admin Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Global Alerts -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5 me-2"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill text-info fs-5 me-2"></i>
                <div>{{ session('info') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 mt-auto">
        <div class="container text-center">
            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 36px; object-fit: contain;">
                <span class="fw-bold text-dark fs-6">{{ \App\Models\Setting::get('fest_name', "SNEC Talents' Meet 2026-27") }}</span>
            </div>
            <p class="text-muted small mb-2">
                {{ \App\Models\Setting::get('college_name', "Shamsul Ulama Islamic & Arts College") }} · {{ \App\Models\Setting::get('edition', "Edition 03") }}
            </p>
            <div class="text-muted fs-xs">
                &copy; {{ date('Y') }} College Fest Management System · Centralized Admin Panel & Group Portals.
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
