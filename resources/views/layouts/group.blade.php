<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Group Portal') — {{ auth()->user()->group->name ?? 'Group' }} |
        {{ \App\Models\Setting::get('fest_name', "Masa Talents' Meet 26") }}</title>

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
            --group-color:
                {{ auth()->user()->group->color ?? '#9b111e' }}
            ;
            --fest-crimson: #9b111e;
            --fest-wine: #5c0617;
            --fest-black: #0c0c0e;
            --portal-bg: #f8f6f2;
            --portal-border: #e8e2d8;
            --fest-primary-gradient: linear-gradient(135deg, #9b111e 0%, #5c0617 100%);
            --fest-crimson-glow: rgba(155, 17, 30, 0.28);
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--portal-bg);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Top 5-color Identity Strip */
        .group-top-strip {
            height: 4px;
            background: linear-gradient(90deg,
                    #5c0617 0%, #5c0617 25%,
                    #9b111e 25%, #9b111e 50%,
                    #0c0c0e 50%, #0c0c0e 75%,
                    #d8cfc4 75%, #d8cfc4 100%);
        }

        .group-nav {
            background-color: var(--fest-black);
            border-bottom: 3px solid var(--group-color);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .nav-link-group {
            color: #cbd5e1;
            font-weight: 500;
            padding: 0.5rem 0.85rem;
            border-radius: 0.5rem;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .nav-link-group:hover,
        .nav-link-group.active {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.12);
            transform: translateY(-1px);
        }

        .group-card {
            border: 1px solid var(--portal-border);
            border-radius: 1.15rem;
            background: #ffffff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .group-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(155, 17, 30, 0.07);
            border-color: rgba(155, 17, 30, 0.25);
        }

        .group-color-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            background-color: var(--group-color);
        }

        .btn-teal,
        .btn-primary {
            background: var(--fest-primary-gradient) !important;
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

        /* Smooth page entrance animation */
        @keyframes groupSlideUp {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .group-content-animate {
            animation: groupSlideUp 0.25s ease-out;
        }

        /* Ensure modals always stay above backdrops and never get trapped in stacking contexts */
        .modal {
            z-index: 1060 !important;
        }

        .modal-backdrop {
            z-index: 1050 !important;
        }

        .group-brand-logo {
            height: 38px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(255, 255, 255, 0.15));
            transition: transform 0.25s ease;
        }

        .group-brand-logo:hover {
            transform: scale(1.1);
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="group-top-strip"></div>

    <nav class="navbar navbar-expand-lg group-nav navbar-dark py-2 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('group.dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Masa Fest Logo" class="group-brand-logo">
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="p-1 px-2 rounded text-white fw-bold fs-6 lh-1"
                            style="background-color: var(--group-color);">
                            <i class="bi bi-flag-fill me-1"></i> {{ auth()->user()->group->code ?? 'GRP' }}
                        </span>
                        <span class="fw-extrabold text-white">{{ auth()->user()->group->name ?? 'Group Portal' }}</span>
                    </div>
                    <small class="text-white-50 fs-xs d-block mt-1">
                        {{ \App\Models\Setting::get('fest_name', "Masa Talents' Meet 26") }}
                    </small>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#groupNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="groupNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link nav-link-group {{ request()->routeIs('group.dashboard') ? 'active' : '' }}"
                            href="{{ route('group.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-group {{ request()->routeIs('group.members*') ? 'active' : '' }}"
                            href="{{ route('group.members') }}">
                            <i class="bi bi-people me-1"></i> Our Members
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-group {{ request()->routeIs('group.programs*') ? 'active' : '' }}"
                            href="{{ route('group.programs') }}">
                            <i class="bi bi-calendar-event me-1"></i> Programs & Schedule
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-group {{ request()->routeIs('group.registrations*') ? 'active' : '' }}"
                            href="{{ route('group.registrations.index') }}">
                            <i class="bi bi-clipboard-check me-1"></i> Event Registrations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-group {{ request()->routeIs('group.results*') ? 'active' : '' }}"
                            href="{{ route('group.results') }}">
                            <i class="bi bi-award me-1"></i> Won Results
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-group {{ request()->routeIs('group.rankings*') ? 'active' : '' }}"
                            href="{{ route('group.rankings') }}">
                            <i class="bi bi-trophy me-1"></i> Leaderboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-group {{ request()->routeIs('group.profile*') ? 'active' : '' }}"
                            href="{{ route('group.profile') }}">
                            <i class="bi bi-info-circle me-1"></i> Profile
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('public.index') }}" target="_blank"
                        class="btn btn-sm btn-outline-light rounded-pill px-3">
                        <i class="bi bi-globe me-1 text-danger" style="color: #ff6b7a !important;"></i> Public Site
                    </a>

                    <a href="{{ route('group.password.edit') }}"
                        class="btn btn-sm btn-outline-secondary text-light rounded-pill px-3 {{ request()->routeIs('group.password.*') ? 'active' : '' }}">
                        <i class="bi bi-key-fill me-1"></i> Password
                    </a>

                    <form action="{{ route('group.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3"
                            style="background-color: var(--fest-crimson); border-color: var(--fest-crimson);">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Group Portal Alerts -->
    <div class="container mt-3">
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
    </div>

    <main class="py-4 flex-grow-1 group-content-animate">
        @yield('content')
    </main>

    <footer class="bg-white border-top py-3 text-center text-muted small mt-auto">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span>{{ auth()->user()->group->name ?? 'Group Portal' }} &bull;
                {{ \App\Models\Setting::get('fest_name', "Masa Talents' Meet 26") }}</span>
            <span class="badge bg-dark text-white font-monospace">{{ auth()->user()->group->code ?? 'HOUSE' }}</span>
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