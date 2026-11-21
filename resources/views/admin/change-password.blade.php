@extends('layouts.admin')

@section('title', 'Change Password — Super Admin')

@section('content')
<div class="container-fluid px-lg-4">
    <div class="mb-4">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
        <h2 class="h4 fw-bold mt-2 mb-1">Change Admin Password</h2>
        <p class="text-muted small mb-0">Update the administrative account master password.</p>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card admin-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.password.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Current Master Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-shield-lock text-muted"></i></span>
                                <input type="password" name="current_password" class="form-control border-start-0 ps-0 @error('current_password') is-invalid @enderror" placeholder="Enter your current password" required autofocus>
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
                            <button type="submit" class="btn btn-teal text-white px-5" style="background-color: #0d9488;">
                                <i class="bi bi-shield-check me-1"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card admin-card border-0 shadow-sm p-4 bg-light">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i> Security Guidelines</h6>
                <ul class="small text-muted ps-3 mb-0">
                    <li class="mb-2">Your super admin password gives full access to all fest operations, results publication, and system settings.</li>
                    <li class="mb-2">Keep your credentials confidential and change your password periodically.</li>
                    <li class="mb-2">Password updates are logged in the system activity tracker.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
