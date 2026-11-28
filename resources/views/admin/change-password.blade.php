@extends('layouts.admin')

@section('title', 'Admin Account Security — Super Admin')

@section('content')
<div class="container-fluid px-lg-4">
    <div class="mb-4">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
        <h2 class="h4 fw-bold mt-2 mb-1">Admin Account Security</h2>
        <p class="text-muted small mb-0">Update your administrative login username or master password.</p>
    </div>

    <div class="row g-4">
        <!-- Change Username Card -->
        <div class="col-lg-6">
            <div class="card admin-card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-person-badge me-2 text-teal"></i>Change Admin Username
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.username.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Current Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person-check text-muted"></i></span>
                                <input type="text" class="form-control bg-light border-start-0 ps-0 font-monospace" value="{{ auth()->user()->username }}" disabled>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">New Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="username" class="form-control border-start-0 ps-0 font-monospace @error('username') is-invalid @enderror" value="{{ old('username', auth()->user()->username) }}" placeholder="Enter new username" required>
                                @error('username')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">3–50 characters (letters, numbers, dashes, underscores).</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Current Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-shield-lock text-muted"></i></span>
                                <input type="password" name="username_current_password" class="form-control border-start-0 ps-0 @error('username_current_password') is-invalid @enderror" placeholder="Confirm with current password" required>
                                @error('username_current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-teal text-white px-4" style="background-color: #9b111e;">
                                <i class="bi bi-check2-circle me-1"></i> Update Username
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="col-lg-6">
            <div class="card admin-card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-key-fill me-2 text-teal"></i>Change Admin Password
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.password.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Current Master Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-shield-lock text-muted"></i></span>
                                <input type="password" name="current_password" class="form-control border-start-0 ps-0 @error('current_password') is-invalid @enderror" placeholder="Enter your current password" required>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-key text-muted"></i></span>
                                <input type="password" name="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" placeholder="Enter new password (min. 6 characters)" required>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Must be at least 6 characters long.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Confirm New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-check2-circle text-muted"></i></span>
                                <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0" placeholder="Re-enter new password" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" class="btn btn-teal text-white px-4" style="background-color: #9b111e;">
                                <i class="bi bi-shield-check me-1"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card admin-card border-0 shadow-sm p-4 bg-light">
                <h6 class="fw-bold mb-2"><i class="bi bi-info-circle-fill text-primary me-2"></i> Security Guidelines</h6>
                <ul class="small text-muted ps-3 mb-0">
                    <li class="mb-1">Your super admin credentials give full access to all fest operations, results publication, and system settings.</li>
                    <li class="mb-1">Keep your username and password confidential and update them periodically.</li>
                    <li>All username and password updates are logged in the system activity tracker.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
