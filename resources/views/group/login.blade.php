<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Portal Login — SNEC Talents' Meet 2026-27</title>
    <!-- Bootstrap 5.3 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-teal: #16b4a6;
            --brand-coral: #ea4949;
            --brand-gold: #f5a623;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            padding: 20px;
        }

        .portal-card {
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 480px;
        }

        .portal-header {
            background: linear-gradient(135deg, #16b4a6 0%, #0d9488 100%);
            padding: 2.25rem 2rem;
            text-align: center;
            color: #ffffff;
            position: relative;
        }

        .btn-brand {
            background-color: var(--brand-teal);
            color: #ffffff;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-brand:hover {
            background-color: #0d9488;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .demo-pill {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .demo-pill:hover {
            background-color: #e2e8f0 !important;
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    <div class="portal-card">
        <div class="portal-header">
            <div class="bg-white p-2 rounded-4 d-inline-block shadow-sm mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="SNEC Talents' Meet" style="max-height: 55px; width: auto;">
            </div>
            <h4 class="fw-bold mb-1">Group Dedicated Portal</h4>
            <p class="mb-0 small opacity-90">SNEC Talents' Meet 2026-27</p>
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
