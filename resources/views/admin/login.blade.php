@extends('layouts.app')

@section('title', "Super Admin Login — " . \App\Models\Setting::get('fest_name', "Masa Talents' Meet 26"))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            
            <div class="fest-card p-4 shadow-lg border-0 bg-white position-relative overflow-hidden">
                <!-- Top Red Accent Bar -->
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: var(--fest-primary-gradient);"></div>

                <div class="text-center mb-4 pt-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Masa Fest Logo" class="animate-float mb-2" style="max-height: 80px; object-fit: contain;">
                    <div class="font-malayalam fw-bold text-danger small mb-1" style="color: #9b111e !important;">
                        {{ \App\Models\Setting::get('tagline', "“കല കലഹിക്കട്ടെ; സത്യം സംസാരിക്കട്ടെ”") }}
                    </div>
                    <h4 class="fw-extrabold mb-1">Super Admin Login</h4>
                    <p class="text-muted small mb-0">{{ \App\Models\Setting::get('fest_name', "Masa Talents' Meet 26") }}</p>
                </div>

                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold small text-muted">Admin Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" 
                                   name="username" 
                                   id="username" 
                                   class="form-control @error('username') is-invalid @enderror" 
                                   value="{{ old('username', 'admin') }}" 
                                   required 
                                   autofocus>
                        </div>
                        @error('username')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold small text-muted">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-key text-muted"></i></span>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   value="admin123" 
                                   required>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input" checked>
                        <label for="remember" class="form-check-label small text-muted">Keep me signed in</label>
                    </div>

                    <button type="submit" class="btn btn-fest-primary w-100 py-2 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Admin Panel
                    </button>
                </form>

                <!-- Helper credentials -->
                <div class="mt-4 p-3 bg-light rounded-3 border text-center small text-muted">
                    <div class="fw-bold text-dark mb-1"><i class="bi bi-shield-check text-danger me-1" style="color: #9b111e !important;"></i> Default Super Admin Credentials</div>
                    <div>Username: <code>admin</code></div>
                    <div>Password: <code>admin123</code></div>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('public.index') }}" class="text-muted small text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Return to Live Leaderboard
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
