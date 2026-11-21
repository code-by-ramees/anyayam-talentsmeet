@extends('layouts.admin')

@section('title', 'Create Group')

@section('content')
    <div class="container-fluid px-lg-4">
        <div class="mb-4">
            <a href="{{ route('admin.groups.index') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Back to Groups
            </a>
            <h2 class="h4 fw-bold mt-2 mb-1">Create New Participating Group</h2>
            <p class="text-muted small mb-0">Register a new festival group and create its dedicated login credentials.</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card admin-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.groups.store') }}" method="POST">
                            @csrf

                            <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                                <i class="bi bi-people-fill text-teal me-2" style="color: #9b111e;"></i> Group Information
                            </h5>

                            <div class="row g-3 mb-4">
                                    <div class="col-md-5">
                                        <label class="form-label fw-semibold">Group / House Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="e.g. Emerald Hawks"
                                            value="{{ old('name') }}" required>
                                    </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Group Code <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="code" class="form-control text-uppercase"
                                                placeholder="e.g. EMERALD" value="{{ old('code') }}" required>
                                        </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Theme Color</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="color" name="color" class="form-control form-control-color"
                                                        value="{{ old('color', '#9b111e') }}" title="Choose group color">
                                                    <small class="text-muted">Badges & cards color</small>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Description (Optional)</label>
                                                <textarea name="description" class="form-control" rows="2"
                                                    placeholder="Brief note or motto about this house (applies to both Junior and Senior participants)...">{{ old('description') }}</textarea>
                                            </div>
                                        </div>

                                        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                                            <i class="bi bi-shield-lock-fill text-teal me-2" style="color: #9b111e;"></i>
                                            Dedicated Group Portal Login
                                        </h5>

                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Portal Username <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                    <input type="text" name="username" class="form-control"
                                                        placeholder="e.g. emerald" value="{{ old('username') }}" required>
                                                </div>
                                                <small class="text-muted">Used by group leaders to log in to their dedicated
                                                    portal.</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Initial Password <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                                    <input type="password" name="password" class="form-control"
                                                        placeholder="Min. 6 characters" required>
                                                </div>
                                                <small class="text-muted">Must be at least 6 characters long.</small>
                                            </div>
                                        </div>

                                        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                                            <i class="bi bi-sliders text-teal me-2" style="color: #9b111e;"></i> Permissions
                                            & Status
                                        </h5>

                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Group Status <span
                                                        class="text-danger">*</span></label>
                                                <select name="status" class="form-select" required>
                                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                                                        Active (Can log in)</option>
                                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive (Access blocked)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 d-flex align-items-center">
                                                <div class="form-check mt-3">
                                                    <input type="hidden" name="allow_member_registration" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="allow_member_registration" value="1" id="allowMemberReg" {{ old('allow_member_registration', '1') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold" for="allowMemberReg">
                                                        Allow group leader to register members
                                                    </label>
                                                    <div class="text-muted small">If unchecked, only the administrator can
                                                        add members for this group.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                            <a href="{{ route('admin.groups.index') }}"
                                                class="btn btn-light px-4">Cancel</a>
                                            <button type="submit" class="btn btn-teal text-white px-5"
                                                style="background-color: #9b111e;">
                                                <i class="bi bi-check-lg me-1"></i> Create Group & Login
                                            </button>
                                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card admin-card border-0 shadow-sm p-4 bg-light">
                    <h6 class="fw-bold mb-2"><i class="bi bi-info-circle-fill text-primary me-2"></i> How Group Accounts
                        Work</h6>
                    <p class="small text-muted mb-3">
                        Each group receives an isolated portal login. The group leaders will only be able to view their own
                        group's members, eligible programs, and published awards.
                    </p>
                    <div class="p-3 bg-white rounded-3 border mb-3">
                        <div class="fw-semibold small text-dark mb-1">Group Portal URL:</div>
                        <code class="small text-primary">{{ url('/group/login') }}</code>
                    </div>
                    <ul class="small text-muted ps-3 mb-0">
                        <li>Group users cannot access or edit other groups.</li>
                        <li>Unpublished draft results are strictly invisible to groups.</li>
                        <li>Password can be reset anytime by the Super Admin.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection