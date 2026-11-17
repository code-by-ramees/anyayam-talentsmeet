@extends('layouts.admin')

@section('title', 'Edit Member: ' . $member->name)

@section('content')
<div class="container-fluid px-lg-4">
    <div class="mb-4">
        <a href="{{ route('admin.members.index') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Back to Members
        </a>
        <h2 class="h4 fw-bold mt-2 mb-1">Edit Member: {{ $member->name }}</h2>
        <p class="text-muted small mb-0">Update student enrollment and group assignment.</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card admin-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.members.update', $member) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Participating Group <span class="text-danger">*</span></label>
                                <select name="group_id" class="form-select" required>
                                    @foreach($groups as $grp)
                                        <option value="{{ $grp->id }}" {{ old('group_id', $member->group_id) == $grp->id ? 'selected' : '' }}>
                                            {{ $grp->name }} ({{ $grp->section->name ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Admission / Registration No <span class="text-danger">*</span></label>
                                <input type="text" name="registration_no" class="form-control" value="{{ old('registration_no', $member->registration_no) }}" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Member Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $member->name) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Class / Academic Year <span class="text-danger">*</span></label>
                                <input type="text" name="class_year" class="form-control" value="{{ old('class_year', $member->class_year) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select" required>
                                    <option value="Male" {{ old('gender', $member->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $member->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $member->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $member->phone) }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.members.index') }}" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-save me-1"></i> Update Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
