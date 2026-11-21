@extends('layouts.admin')

@section('title', 'Edit Result: ' . $result->program->name)

@section('content')
    <div class="container-fluid px-lg-4">
        <div class="mb-4">
            <a href="{{ route('admin.results.index') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Back to Results
            </a>
            <h2 class="h4 fw-bold mt-2 mb-1">Edit Result: {{ $result->program->name }}</h2>
            <p class="text-muted small mb-0">Modify prize winner, position, marks awarded, or publication status.</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card admin-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.results.update', $result) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="p-3 bg-light rounded-3 mb-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold fs-6 text-dark">{{ $result->program->name }}
                                        ({{ $result->program->code }})</div>
                                    <div class="small text-muted">
                                        Section: <strong>{{ $result->program->section->name ?? '' }}</strong> |
                                        Category: <strong>{{ $result->program->category->name ?? '' }}</strong>
                                    </div>
                                </div>
                                <span class="badge {{ $result->getPositionBadgeClass() }} fs-6 px-3 py-2">
                                    Current: {{ $result->getPositionLabel() }}
                                </span>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Winning Group <span
                                            class="text-danger">*</span></label>
                                    <select name="group_id" class="form-select" required>
                                        @foreach($groups as $grp)
                                            <option value="{{ $grp->id }}" {{ old('group_id', $result->group_id) == $grp->id ? 'selected' : '' }}>
                                                {{ $grp->name }} ({{ $grp->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Participant / Team Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="participant_name" class="form-control"
                                        value="{{ old('participant_name', $result->participant_name) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Position Awarded <span
                                            class="text-danger">*</span></label>
                                    <select name="position" class="form-select" required>
                                        <option value="first" {{ old('position', $result->position) === 'first' ? 'selected' : '' }}>First Prize (1st)</option>
                                        <option value="second" {{ old('position', $result->position) === 'second' ? 'selected' : '' }}>Second Prize (2nd)</option>
                                        <option value="third" {{ old('position', $result->position) === 'third' ? 'selected' : '' }}>Third Prize (3rd)</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Awarded Points / Marks <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="marks" class="form-control fw-bold fs-5 text-success"
                                            value="{{ old('marks', $result->marks) }}" min="0" required>
                                        <span class="input-group-text bg-light">Points</span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Remarks (Optional)</label>
                                    <input type="text" name="remarks" class="form-control"
                                        value="{{ old('remarks', $result->remarks) }}">
                                </div>

                                <div class="col-12">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_published" value="1"
                                                id="publishCheck" {{ old('is_published', $result->is_published) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-dark" for="publishCheck">
                                                Published Live
                                            </label>
                                        </div>
                                        <div class="small text-muted ps-4">
                                            Toggle whether this result is actively contributing to the group's leaderboard
                                            score.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                <a href="{{ route('admin.results.index') }}" class="btn btn-light px-4">Cancel</a>
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="bi bi-save me-1"></i> Update Result
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection