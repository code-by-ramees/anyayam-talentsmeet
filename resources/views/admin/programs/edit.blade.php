@extends('layouts.admin')

@section('title', 'Edit Program: ' . $program->name)

@section('content')
<div class="container-fluid px-lg-4">
    <div class="mb-4">
        <a href="{{ route('admin.programs.index') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Back to Programs
        </a>
        <h2 class="h4 fw-bold mt-2 mb-1">Edit Program: {{ $program->name }}</h2>
        <p class="text-muted small mb-0">Update schedule, venue, section, or category for this competition.</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card admin-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.programs.update', $program) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Program Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $program->name) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Program Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control text-uppercase" value="{{ old('code', $program->code) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Festival Section <span class="text-danger">*</span></label>
                                <select name="section_id" class="form-select" required>
                                    @foreach($sections as $sec)
                                        <option value="{{ $sec->id }}" {{ old('section_id', $program->section_id) == $sec->id ? 'selected' : '' }}>
                                            {{ $sec->name }} ({{ $sec->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Program Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $program->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }} (1st: {{ $cat->first_marks }}, 2nd: {{ $cat->second_marks }}, 3rd: {{ $cat->third_marks }} pts)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Program Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" required>
                                    <option value="individual" {{ old('type', $program->type) === 'individual' ? 'selected' : '' }}>Individual Item</option>
                                    <option value="group" {{ old('type', $program->type) === 'group' ? 'selected' : '' }}>Group / Team Item</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Max Participants per Group</label>
                                <input type="number" name="max_participants" class="form-control" value="{{ old('max_participants', $program->max_participants) }}" min="1">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Event Venue</label>
                                <input type="text" name="venue" class="form-control" value="{{ old('venue', $program->venue) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Scheduled Date & Time</label>
                                <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', $program->scheduled_at ? $program->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="scheduled" {{ old('status', $program->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="ongoing" {{ old('status', $program->status) === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ old('status', $program->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $program->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.programs.index') }}" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-save me-1"></i> Update Program
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
