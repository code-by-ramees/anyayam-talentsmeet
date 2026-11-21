@extends('layouts.admin')

@section('title', 'Create Competition Program')

@section('content')
    <div class="container-fluid px-lg-4">
        <div class="mb-4">
            <a href="{{ route('admin.programs.index') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Back to Programs
            </a>
            <h2 class="h4 fw-bold mt-2 mb-1">Create Competition Program</h2>
            <p class="text-muted small mb-0">Add an arts/cultural event and assign its competition section and category.</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card admin-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.programs.store') }}" method="POST">
                            @csrf

                            <div class="row g-3 mb-4">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">Program Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="e.g. Classical Music Solo, Elocution, Mime" value="{{ old('name') }}"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Program Code <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control text-uppercase"
                                        placeholder="e.g. PRG-101" value="{{ old('code') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Festival Section <span
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
                                    <label class="form-label fw-semibold">Program Category <span
                                            class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">Select Category...</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }} (1st: {{ $cat->first_marks }}, 2nd: {{ $cat->second_marks }},
                                                3rd: {{ $cat->third_marks }} pts)
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Determines how many marks winners receive.</small>
                                </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Stage Type <span
                                                class="text-danger">*</span></label>
                                        <select name="stage_type" class="form-select" required>
                                            <option value="stage" {{ old('stage_type', 'stage') === 'stage' ? 'selected' : '' }}>Stage Program</option>
                                            <option value="non_stage" {{ old('stage_type') === 'non_stage' ? 'selected' : '' }}>Non-Stage Program</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Program Type <span
                                                class="text-danger">*</span></label>
                                        <select name="type" class="form-select" required>
                                            <option value="individual" {{ old('type') === 'individual' ? 'selected' : '' }}>
                                                Individual Item</option>
                                            <option value="group" {{ old('type') === 'group' ? 'selected' : '' }}>Group / Team
                                                Item</option>
                                        </select>
                                    </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Max Participants per Group</label>
                                            <input type="number" name="max_participants" class="form-control"
                                                value="{{ old('max_participants', 1) }}" min="1">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                        <a href="{{ route('admin.programs.index') }}" class="btn btn-light px-4">Cancel</a>
                                        <button type="submit" class="btn btn-teal text-white px-5"
                                            style="background-color: #0d9488;">
                                            <i class="bi bi-check-lg me-1"></i> Save Program
                                        </button>
                                    </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection