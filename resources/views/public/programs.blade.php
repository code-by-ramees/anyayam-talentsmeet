@extends('layouts.app')

@section('title', "Programs & Schedule — {$festName}")

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="{{ route('public.index') }}"
                                class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active">Programs Schedule</li>
                    </ol>
                </nav>
                <h3 class="fw-extrabold mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-check text-primary"></i>
                    <span>Competition Programs & Schedule</span>
                </h3>
            </div>
        </div>

        <!-- Filters -->
        <div class="fest-card p-3 mb-4 bg-white">
            <form action="{{ route('public.programs') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fs-xs text-muted fw-bold mb-1">Filter by Section</label>
                    <select name="section_id" class="form-select form-select-sm rounded-pill">
                        <option value="">All Sections</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}" {{ request('section_id') == $sec->id ? 'selected' : '' }}>
                                {{ $sec->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fs-xs text-muted fw-bold mb-1">Filter by Category</label>
                    <select name="category_id" class="form-select form-select-sm rounded-pill">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                        <label class="form-label fs-xs text-muted fw-bold mb-1">Stage / Nature</label>
                        <select name="stage_type" class="form-select form-select-sm rounded-pill">
                            <option value="">All Programs</option>
                            <option value="stage" {{ request('stage_type') === 'stage' ? 'selected' : '' }}>Stage Programs
                            </option>
                            <option value="non_stage" {{ request('stage_type') === 'non_stage' ? 'selected' : '' }}>Non-Stage
                                Programs</option>
                        </select>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-fest-primary btn-sm rounded-pill w-100">
                        <i class="bi bi-funnel me-1"></i> Apply Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Programs Grid -->
        <div class="row g-3">
            @forelse($programs as $prog)
                <div class="col-md-6 col-lg-4">
                    <div class="fest-card p-4 h-100 d-flex flex-direction-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex gap-1 flex-wrap">
                                    <span class="badge bg-light text-dark border">{{ $prog->section->name }}</span>
                                    @if($prog->stage_type === 'stage')
                                        <span class="badge rounded-pill px-2 py-1"
                                            style="background-color: #f3e8ff; color: #7e22ce; border: 1px solid #d8b4fe;">
                                            <i class="bi bi-mic me-1"></i> Stage
                                        </span>
                                    @else
                                        <span
                                            class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2 py-1">
                                            <i class="bi bi-pencil-square me-1"></i> Non-Stage
                                        </span>
                                    @endif
                                </div>
                                @if($prog->status === 'completed')
                                    <span
                                        class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Completed</span>
                                @elseif($prog->status === 'ongoing')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Live /
                                        Ongoing</span>
                                @else
                                    <span
                                        class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">Scheduled</span>
                                @endif
                            </div>

                            <h5 class="fw-bold text-dark mb-1">{{ $prog->name }}</h5>
                            <p class="text-muted small mb-2">Code: <code>{{ $prog->code }}</code></p>

                            <div class="p-2 bg-light rounded-3 border small mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Category:</span>
                                    <strong class="text-dark">{{ $prog->category->name }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Participation:</span>
                                    <span class="badge bg-white text-dark border text-capitalize">{{ $prog->type }} (Max
                                        {{ $prog->max_participants }})</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Program Nature:</span>
                                    <strong>{{ $prog->stage_type === 'stage' ? 'Stage Event' : 'Non-Stage Event' }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Marks allocated preview -->
                        <div class="border-top pt-2 d-flex justify-content-between text-muted fs-xs">
                            <span>1st: <strong>{{ $prog->category->first_marks }} pts</strong></span>
                            <span>2nd: <strong>{{ $prog->category->second_marks }} pts</strong></span>
                            <span>3rd: <strong>{{ $prog->category->third_marks }} pts</strong></span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="fest-card p-5 text-center text-muted">
                        <h5>No competition programs matched your search.</h5>
                    </div>
                </div>
            @endforelse
        </div>

        @if($programs->hasPages())
            <div class="mt-4">
                {{ $programs->links() }}
            </div>
        @endif
    </div>
@endsection