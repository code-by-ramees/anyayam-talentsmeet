@extends('layouts.group')

@section('title', 'Change Password — ' . $group->name)

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('group.dashboard') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
        <h2 class="h4 fw-bold mt-2 mb-1">Change Portal Password</h2>
        <p class="text-muted small mb-0">{{ $group->name }} ({{ $group->code }}) &bull; Update your group portal account login password.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card group-card border-0 shadow-sm p-4">
                <form action="{{ route('group.password.update') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Current Password <span class="text-danger">*</span></label>
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
                        <a href="{{ route('group.profile') }}" class="btn btn-light px-4">Cancel</a>
                        <button type="submit" class="btn btn-teal text-white px-5" style="background-color: #0d9488;">
                            <i class="bi bi-check2-circle me-1"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card group-card border-0 shadow-sm p-4 bg-light">
                <h6 class="fw-bold mb-3"><i class="bi bi-shield-lock-fill text-teal me-2" style="color: #0d9488;"></i> Group Portal Security</h6>
                <p class="small text-muted mb-3">
                    Ensure your group credentials are secure to prevent unauthorized member alterations or score inquiries.
                </p>
                <div class="p-3 bg-white rounded-3 border mb-3 small">
                    <div class="text-muted">Username:</div>
                    <div class="fw-bold font-monospace text-dark">{{ auth()->user()->username }}</div>
                </div>
                <ul class="small text-muted ps-3 mb-0">
                    <li class="mb-2">If you forget your password, contact the central festival super administrator to reset it.</li>
                    <li class="mb-2">Your group session will remain active after changing your password.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
