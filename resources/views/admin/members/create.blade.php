@extends('layouts.admin')

@section('title', 'Add Group Member')

@section('content')
<div class="container-fluid px-lg-4">
    <div class="mb-4">
        <a href="{{ route('admin.members.index') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Back to Members
        </a>
        <h2 class="h4 fw-bold mt-2 mb-1">Add Group Member</h2>
        <p class="text-muted small mb-0">Register a new participant and assign them to a festival group.</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card admin-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.members.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Participating Group <span class="text-danger">*</span></label>
                                <select name="group_id" class="form-select" required>
                                    <option value="">Select Group...</option>
                                    @foreach($groups as $grp)
                                        <option value="{{ $grp->id }}" {{ old('group_id') == $grp->id ? 'selected' : '' }}>
                                            {{ $grp->name }} ({{ $grp->section->name ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Admission / Registration No <span class="text-danger">*</span></label>
                                <input type="text" name="registration_no" class="form-control" placeholder="e.g. ADM2026-042" value="{{ old('registration_no') }}" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Member Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Full legal or registered student name" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Class / Academic Year <span class="text-danger">*</span></label>
                                <input type="text" name="class_year" class="form-control" placeholder="e.g. 2nd Year BA" value="{{ old('class_year') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select" required>
                                    <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone Number (Optional)</label>
                                <input type="text" name="phone" class="form-control" placeholder="e.g. +91 9876543210" value="{{ old('phone') }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.members.index') }}" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" class="btn btn-teal text-white px-5" style="background-color: #0d9488;">
                                <i class="bi bi-check-lg me-1"></i> Save Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
