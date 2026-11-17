@extends('layouts.group')

@section('title', 'Programs & Schedule — ' . $group->name)

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Eligible Programs & Schedule</h2>
            <p class="text-muted small mb-0">{{ $group->name }} &bull; Competitions in {{ $group->section->name ?? 'Your' }} Section.</p>
        </div>
        <span class="badge bg-teal text-white px-3 py-2 rounded-pill fs-6" style="background-color: #0d9488;">
            <i class="bi bi-layers-fill me-1"></i> {{ $group->section->name ?? '' }} Section
        </span>
    </div>

    <!-- Filters Card -->
    <div class="card group-card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('group.programs') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search program, code, venue..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }} (1st: {{ $cat->first_marks }}, 2nd: {{ $cat->second_marks }}, 3rd: {{ $cat->third_marks }} pts)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-secondary w-100">Filter</button>
                    @if(request()->hasAny(['search', 'category_id']))
                        <a href="{{ route('group.programs') }}" class="btn btn-light"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Programs Grid -->
    <div class="row g-4">
        @forelse($programs as $prog)
            <div class="col-md-6 col-lg-4">
                <div class="card group-card border-0 shadow-sm h-100">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill">
                                {{ $prog->category->name ?? '' }}
                            </span>
                            @if($prog->status === 'completed')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Completed</span>
                            @elseif($prog->status === 'ongoing')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Ongoing</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border rounded-pill">Scheduled</span>
                            @endif
                        </div>

                        <h5 class="fw-bold text-dark mt-2 mb-1">{{ $prog->name }}</h5>
                        <div class="text-muted small font-monospace mb-3">Code: {{ $prog->code }}</div>

                        <div class="bg-light p-3 rounded-3 mb-3 mt-auto">
                            <div class="d-flex justify-content-between text-muted small py-1 border-bottom">
                                <span><i class="bi bi-person me-1"></i> Type:</span>
                                <span class="fw-semibold text-dark text-capitalize">{{ $prog->type }} (Max: {{ $prog->max_participants }})</span>
                            </div>
                            <div class="d-flex justify-content-between text-muted small py-1 border-bottom">
                                <span><i class="bi bi-geo-alt me-1"></i> Venue:</span>
                                <span class="fw-semibold text-dark">{{ $prog->venue ?: 'Main Stage' }}</span>
                            </div>
                            <div class="d-flex justify-content-between text-muted small py-1">
                                <span><i class="bi bi-clock me-1"></i> Schedule:</span>
                                <span class="fw-semibold text-dark">{{ $prog->scheduled_at ? $prog->scheduled_at->format('d M, h:i A') : 'TBD' }}</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center text-muted small">
                            <span>Points available:</span>
                            <span class="badge bg-light text-dark border">
                                1st: {{ $prog->category->first_marks ?? 0 }} | 2nd: {{ $prog->category->second_marks ?? 0 }} | 3rd: {{ $prog->category->third_marks ?? 0 }} pts
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card group-card p-5 text-center text-muted">
                    <i class="bi bi-calendar-x display-4 mb-3 text-secondary"></i>
                    <h5>No Programs Found</h5>
                    <p class="small">No competition programs match your filter for this section.</p>
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
