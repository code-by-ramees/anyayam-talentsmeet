@extends('layouts.group')

@section('title', 'Group Profile — ' . $group->name)

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h2 class="h4 fw-bold mb-1">Group Profile & Details</h2>
        <p class="text-muted small mb-0">{{ $festName }} &bull; Profile configuration and participation details.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card group-card border-0 shadow-sm p-4">
                <div class="d-flex align-items-center gap-4 mb-4 pb-4 border-bottom">
                    <div class="rounded-4 p-4 text-white d-flex align-items-center justify-content-center shadow" 
                         style="width: 80px; height: 80px; background-color: {{ $group->color ?: '#0d9488' }};">
                        <i class="bi bi-flag-fill fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">{{ $group->name }}</h3>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span class="badge bg-light text-secondary border font-monospace fs-6">CODE: {{ $group->code }}</span>
                            <span class="badge rounded-pill px-3 py-1 text-white" style="background-color: #0d9488;">
                                {{ $group->section->name ?? '' }} Section
                            </span>
                            @if($group->status === 'active')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <div class="small text-muted mb-1">Assigned Festival Section</div>
                            <div class="fw-bold fs-6 text-dark">{{ $group->section->name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $group->section->description ?? '' }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <div class="small text-muted mb-1">Dedicated Portal Username</div>
                            <div class="fw-bold fs-6 font-monospace text-dark">{{ $group->user->username ?? 'N/A' }}</div>
                            <small class="text-muted">Account linked to group leadership</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3">
                            <div class="small text-muted mb-1">Group Description & Motto</div>
                            <div class="text-dark">{{ $group->description ?: 'No group description provided.' }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold text-dark">Member Registration Permission</div>
                                <div class="small text-muted">Ability to add participants directly through this portal</div>
                            </div>
                            @if($group->allow_member_registration)
                                <span class="badge bg-success px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i> Enabled by Admin</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 rounded-pill"><i class="bi bi-lock-fill me-1"></i> Admin Only</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card group-card border-0 shadow-sm p-4 bg-light">
                <h6 class="fw-bold mb-3"><i class="bi bi-shield-lock-fill text-teal me-2" style="color: #0d9488;"></i> Portal Account Security</h6>
                <p class="small text-muted mb-3">
                    Your group portal account credentials are managed securely. To request a password reset or change group leader details, please contact the festival Super Admin.
                </p>
                <div class="p-3 bg-white rounded-3 border mb-3">
                    <div class="small text-muted">Festival Support:</div>
                    <div class="fw-semibold small text-dark">Central Fest Control Room</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
