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
                                    <label class="form-label fw-semibold">Participating Group / House <span
                                            class="text-danger">*</span></label>
                                    <select name="group_id" class="form-select" required>
                                        <option value="">Select Group...</option>
                                        @foreach($groups as $grp)
                                            <option value="{{ $grp->id }}" {{ old('group_id') == $grp->id ? 'selected' : '' }}>
                                                {{ $grp->name }} ({{ $grp->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Section (Junior / Senior) <span
                                            class="text-danger">*</span></label>
                                    <select name="section_id" class="form-select" required>
                                        <option value="">Select Section...</option>
                                        @foreach($sections as $sec)
                                            <option value="{{ $sec->id }}" {{ old('section_id') == $sec->id ? 'selected' : '' }}>
                                                {{ $sec->name }} ({{ $sec->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">UID <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="uid" class="form-control"
                                        placeholder="e.g. UID1024" value="{{ old('uid') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Member Full Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Full legal or registered student name" value="{{ old('name') }}"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Class <span
                                            class="text-danger">*</span></label>
                                    <select name="class_year" class="form-select" required>
                                        <option value="">Select Class...</option>
                                        @foreach(['H1', 'H2', 'D1', 'D2', 'D3', 'D4'] as $cls)
                                            <option value="{{ $cls }}" {{ old('class_year') == $cls ? 'selected' : '' }}>{{ $cls }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                            <a href="{{ route('admin.members.index') }}"
                                                class="btn btn-light px-4">Cancel</a>
                                            <button type="submit" class="btn btn-teal text-white px-5"
                                                style="background-color: #0d9488;">
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