<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Group Portal') — {{ auth()->user()->group->name ?? 'Group' }}</title>

    <!-- Bootstrap 5.3 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --group-color:
                {{ auth()->user()->group->color ?? '#0d9488' }}
            ;
            --portal-bg: #f8fafc;
            --portal-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--portal-bg);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .group-nav {
            background-color: #1e293b;
            border-bottom: 3px solid var(--group-color);
        }

        .nav-link-group {
            color: #cbd5e1;
            font-weight: 500;
            padding: 0.5rem 0.85rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .nav-link-group:hover,
        .nav-link-group.active {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .group-card {
            border: 1px solid var(--portal-border);
            border-radius: 1rem;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }

        .group-color-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            background-color: var(--group-color);
        }
    </style>
    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg group-nav navbar-dark py-2 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('group.dashboard') }}">
                <span class="p-1 px-2 rounded text-white fw-bold fs-6" style="background-color: var(--group-color);">
                    <i class="bi bi-flag-fill me-1"></i> {{ auth()->user()->group->code ?? 'GRP' }}
                </span>
                <span>{{ auth()->user()->group->name ?? 'Group Portal' }}</span>
                <span class="badge bg-secondary fs-xs fw-normal">{{ auth()->user()->group->code ?? 'House' }}</span>
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
                        <i class="bi bi-globe me-1"></i> Public Site
                    </a>

                    <a href="{{ route('group.password.edit') }}"
                        class="btn btn-sm btn-outline-light rounded-pill px-3 {{ request()->routeIs('group.password.*') ? 'active' : '' }}">
                        <i class="bi bi-key-fill me-1"></i> Change Password
                    </a>

                    <form action="{{ route('group.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3">
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

    <main class="py-4 flex-grow-1">
        @yield('content')
    </main>

    <footer class="bg-white border-top py-3 text-center text-muted small mt-auto">
        <div class="container">
            {{ auth()->user()->group->name ?? 'Group Portal' }} · {{ date('Y') }}
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>