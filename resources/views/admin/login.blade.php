@extends('layouts.app')

@section('title', 'Super Admin Login — College Fest Management')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            
            <div class="fest-card p-4 shadow-lg border-0 bg-white">
                <div class="text-center mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center bg-dark text-white rounded-4 p-3 mb-3 shadow-sm">
                        <i class="bi bi-shield-lock-fill fs-3 text-warning"></i>
                    </span>
                    <h4 class="fw-extrabold mb-1">Super Admin Login</h4>
                    <p class="text-muted small">Centralized Festival Administration Panel</p>
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

                    <button type="submit" class="btn btn-dark w-100 py-2 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Admin Panel
                    </button>
                </form>

                <!-- Demo helper credentials -->
                <div class="mt-4 p-3 bg-light rounded-3 border text-center small text-muted">
                    <div class="fw-bold text-dark mb-1"><i class="bi bi-info-circle text-primary me-1"></i> Default Super Admin Credentials</div>
                    <div>Username: <code>admin</code></div>
                    <div>Password: <code>admin123</code></div>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('public.index') }}" class="text-muted small text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Return to Public Festival Portal
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
