<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Portal Login — {{ \App\Models\Setting::get('fest_name', "Masa Talents' Meet 26") }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Bootstrap 5.3 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Manjari:wght@400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --fest-crimson: #9b111e;
            --fest-wine: #5c0617;
            --fest-black: #0c0c0e;
            --fest-dark: #151518;
            --fest-primary-gradient: linear-gradient(135deg, #9b111e 0%, #5c0617 100%);
            --fest-crimson-glow: rgba(155, 17, 30, 0.35);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #120306 0%, #1e0509 50%, #0a0a0c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            padding: 20px;
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(25px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes floatFist {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .portal-card {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5), 0 0 35px rgba(155, 17, 30, 0.25);
            border: 1px solid rgba(255,255,255,0.15);
            overflow: hidden;
            width: 100%;
            max-width: 480px;
            animation: cardEntrance 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .portal-header {
            background: linear-gradient(145deg, #1a0408 0%, #5c0617 55%, #9b111e 100%);
            padding: 2.25rem 2rem;
            text-align: center;
            color: #ffffff;
            position: relative;
        }

        .fist-logo-box {
            background: rgba(255,255,255,0.95);
            padding: 10px;
            border-radius: 1.25rem;
            display: inline-block;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            animation: floatFist 3.5s ease-in-out infinite;
        }

        .btn-brand {
            background: var(--fest-primary-gradient);
            color: #ffffff;
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            border-radius: 50rem;
            border: none;
            box-shadow: 0 4px 15px var(--fest-crimson-glow);
            transition: all 0.25s ease;
        }

        .btn-brand:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #6e0b14 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 22px rgba(155, 17, 30, 0.45);
        }

        .demo-pill {
            cursor: pointer;
            transition: all 0.2s ease;
            border-radius: 50rem;
        }
        .demo-pill:hover {
            background-color: #f1f5f9 !important;
            transform: scale(1.03);
            border-color: var(--fest-crimson) !important;
        }
    </style>
</head>
<body>
    <div class="portal-card">
        <div class="portal-header">
            <div class="fist-logo-box mb-2">
                <img src="{{ asset('images/logo.png') }}" alt="Masa Fest Logo" style="max-height: 68px; width: auto; object-fit: contain;">
            </div>
            <div class="font-malayalam small fw-bold text-warning mb-1" style="color: #fca5a5 !important;">
                {{ \App\Models\Setting::get('tagline', "“കല കലഹിക്കട്ടെ; സത്യം സംസാരിക്കട്ടെ”") }}
            </div>
            <h4 class="fw-extrabold mb-1">Group Dedicated Portal</h4>
            <p class="mb-0 small text-white-50">{{ \App\Models\Setting::get('fest_name', "Masa Talents' Meet 26") }}</p>
        </div>

        <div class="p-4 p-md-5">
            @if(session('info'))
                <div class="alert alert-info py-2 px-3 small rounded-3 mb-3">
                    {{ session('info') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('group.login.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark small">Group Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-people text-muted"></i></span>
                        <input type="text" name="username" id="usernameInput" class="form-control border-start-0 ps-0" placeholder="e.g. emerald, ruby, sapphire, topaz" value="{{ old('username') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark small">Portal Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" name="password" id="passwordInput" class="form-control border-start-0 ps-0" placeholder="Enter password" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                        <label class="form-check-label small text-muted" for="rememberMe">Remember me</label>
                    </div>
                    <span class="small text-muted">Single-group isolated access</span>
                </div>

                <button type="submit" class="btn btn-brand w-100 py-2 fs-6 mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Group Portal
                </button>
            </form>

            <!-- Quick Demo Helper -->
            <div class="border-top pt-3 mt-4">
                <div class="small fw-bold text-muted mb-2 text-center text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                    Demo Group Accounts (Click to Fill):
                </div>
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <button type="button" class="btn btn-sm btn-light border demo-pill" onclick="fillGroup('emerald', 'group123')">
                        <span class="badge bg-success me-1">&bull;</span> Emerald Hawks
                    </button>
                    <button type="button" class="btn btn-sm btn-light border demo-pill" onclick="fillGroup('ruby', 'group123')">
                        <span class="badge bg-danger me-1">&bull;</span> Ruby Knights
                    </button>
                    <button type="button" class="btn btn-sm btn-light border demo-pill" onclick="fillGroup('sapphire', 'group123')">
                        <span class="badge bg-primary me-1">&bull;</span> Sapphire Tigers
                    </button>
                    <button type="button" class="btn btn-sm btn-light border demo-pill" onclick="fillGroup('topaz', 'group123')">
                        <span class="badge bg-warning text-dark me-1">&bull;</span> Topaz Warriors
                    </button>
                </div>
            </div>

            <div class="text-center mt-4 pt-2">
                <a href="{{ route('public.index') }}" class="small text-decoration-none text-muted">
                    <i class="bi bi-arrow-left me-1"></i> Return to Public Site
                </a>
                <span class="mx-2 text-muted">&bull;</span>
                <a href="{{ route('admin.login') }}" class="small text-decoration-none text-muted">
                    Super Admin Login
                </a>
            </div>
        </div>
    </div>

    <script>
        function fillGroup(u, p) {
            document.getElementById('usernameInput').value = u;
            document.getElementById('passwordInput').value = p;
        }
    </script>
</body>
</html>
