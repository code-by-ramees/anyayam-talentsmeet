<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — {{ \App\Models\Setting::get('fest_name', "Masa Talents' Meet 26") }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Bootstrap 5.3 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --admin-nav-bg: #0c0c0e;
            --admin-accent: #9b111e;
            --admin-accent-dark: #6e0b14;
            --admin-surface: #f7f5f0;
            --admin-card-border: #e8e2d8;
            --admin-primary-gradient: linear-gradient(135deg, #9b111e 0%, #5c0617 100%);
            --fest-crimson-glow: rgba(155, 17, 30, 0.28);
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--admin-surface);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Top Identity Strip */
        .admin-top-strip {
            height: 4px;
            background: linear-gradient(90deg,
                    #5c0617 0%, #5c0617 25%,
                    #9b111e 25%, #9b111e 50%,
                    #0c0c0e 50%, #0c0c0e 75%,
                    #d8cfc4 75%, #d8cfc4 100%);
        }

        .admin-nav {
            background-color: var(--admin-nav-bg);
            border-bottom: 2px solid rgba(155, 17, 30, 0.4);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        }

        .nav-link-admin {
            color: #cbd5e1;
            font-weight: 500;
            padding: 0.5rem 0.85rem;
            border-radius: 0.5rem;
            font-size: 0.92rem;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .nav-link-admin:hover,
        .nav-link-admin.active {
            color: #ffffff;
            background: linear-gradient(135deg, rgba(155, 17, 30, 0.6) 0%, rgba(92, 6, 23, 0.4) 100%);
            box-shadow: 0 2px 8px rgba(155, 17, 30, 0.25);
            transform: translateY(-1px);
        }

        .admin-card {
            border: 1px solid var(--admin-card-border);
            border-radius: 1rem;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            transition: all 0.25s ease;
        }

        .admin-card:hover {
            box-shadow: 0 8px 24px rgba(155, 17, 30, 0.06);
            border-color: rgba(155, 17, 30, 0.2);
        }

        /* Legacy .btn-teal and teal color helpers mapped to theme crimson */
        .btn-teal,
        .btn-primary {
            background: var(--admin-primary-gradient) !important;
            border-color: #9b111e !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px var(--fest-crimson-glow);
            transition: all 0.25s ease;
        }

        .btn-teal:hover,
        .btn-primary:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #6e0b14 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(155, 17, 30, 0.4);
            color: #ffffff !important;
        }

        .btn-outline-teal {
            border-color: #9b111e !important;
            color: #9b111e !important;
        }

        .btn-outline-teal:hover {
            background: #9b111e !important;
            color: #ffffff !important;
        }

        .text-teal {
            color: #9b111e !important;
        }

        .bg-teal {
            background-color: #9b111e !important;
        }

        .bg-teal-subtle {
            background-color: rgba(155, 17, 30, 0.08) !important;
            color: #9b111e !important;
        }

        /* Animations */
        @keyframes fadeInPage {

            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .admin-content-animate {
            animation: fadeInPage 0.25s ease-out;
        }

        /* Ensure modals always stay above backdrops and never get trapped in stacking contexts */
        .modal {
            z-index: 1060 !important;
        }

        .modal-backdrop {
            z-index: 1050 !important;
        }

        .admin-brand-logo {
            height: 38px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(255, 255, 255, 0.15));
            transition: transform 0.25s ease;
        }

        .admin-brand-logo:hover {
            transform: scale(1.1);
        }

        @media (max-width: 767.98px) {
            .admin-nav { padding-top: 0.25rem !important; padding-bottom: 0.25rem !important; }
            .admin-brand-logo { height: 26px !important; }
            .admin-nav .navbar-brand { padding-top: 0; padding-bottom: 0; }
            .admin-nav .navbar-brand .fs-6 { font-size: 0.9rem !important; }
            .admin-nav .navbar-toggler { padding: 0.2rem 0.4rem; font-size: 1rem; }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="admin-top-strip"></div>

    <nav class="navbar navbar-expand-xl admin-nav navbar-dark py-2 sticky-top">
        <div class="container-fluid px-lg-4">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Fest Logo" class="admin-brand-logo">
                <div class="lh-1">
                    <span class="fs-6 fw-extrabold text-white">MASA TALENTS '26</span>
                    <small class="d-block text-danger fw-bold"
                        style="color: #ff6b7a !important; font-size: 0.68rem; letter-spacing: 0.5px;">ADMIN
                        PANEL</small>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-xl-0 ms-xl-3">
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}"
                            href="{{ route('admin.sections.index') }}">
                            <i class="bi bi-layers me-1"></i> Sections
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin {{ request()->routeIs('admin.groups.*') ? 'active' : '' }}"
                            href="{{ route('admin.groups.index') }}">
                            <i class="bi bi-people me-1"></i> Groups
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin {{ request()->routeIs('admin.members.*') ? 'active' : '' }}"
                            href="{{ route('admin.members.index') }}">
                            <i class="bi bi-person-lines-fill me-1"></i> Members
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                            href="{{ route('admin.categories.index') }}">
                            <i class="bi bi-tags me-1"></i> Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}"
                            href="{{ route('admin.programs.index') }}">
                            <i class="bi bi-calendar-check me-1"></i> Programs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}"
                            href="{{ route('admin.registrations.index') }}">
                            <i class="bi bi-clipboard-check me-1"></i> Registrations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin {{ request()->routeIs('admin.results.*') ? 'active' : '' }}"
                            href="{{ route('admin.results.index') }}">
                            <i class="bi bi-award me-1"></i> Results
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin {{ request()->routeIs('admin.rankings.*') ? 'active' : '' }}"
                            href="{{ route('admin.rankings.index') }}">
                            <i class="bi bi-trophy me-1"></i> Rankings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                            href="{{ route('admin.settings.index') }}">
                            <i class="bi bi-gear me-1"></i> Settings
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('public.index') }}" target="_blank"
                        class="btn btn-sm btn-outline-light rounded-pill px-3">
                        <i class="bi bi-eye me-1 text-danger" style="color: #ff6b7a !important;"></i> Live Site
                    </a>

                    <a href="{{ route('admin.password.edit') }}"
                        class="btn btn-sm btn-outline-secondary rounded-pill px-3 text-light {{ request()->routeIs('admin.password.*') ? 'active' : '' }}">
                        <i class="bi bi-person-gear me-1"></i> Account
                    </a>

                    <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3"
                            style="background-color: #9b111e; border-color: #9b111e;">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Admin Alerts -->
    <div class="container-fluid px-lg-4 mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center"
                role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center"
                role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-1"></i> Please fix the following
                    errors:</div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <main class="py-3 flex-grow-1 admin-content-animate">
        @yield('content')
    </main>

    <footer class="bg-white border-top py-3 text-center text-muted small mt-auto">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span>{{ \App\Models\Setting::get('fest_name', "Masa Talents' Meet 26") }} &bull; Super Admin Panel &copy;
                {{ date('Y') }}</span>
            <span class="badge bg-dark text-white font-monospace">SNEC &bull; MASA</span>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Move all modals directly to body to prevent stacking context & backdrop blur/trap issues
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.modal').forEach(function (modal) {
                document.body.appendChild(modal);
            });
        });
    </script>
    @stack('scripts')
</body>

</html>