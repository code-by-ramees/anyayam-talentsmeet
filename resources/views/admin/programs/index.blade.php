@extends('layouts.admin')

@section('title', 'Competition Programs')

@section('content')
<div class="container-fluid px-lg-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Competition Programs</h2>
            <p class="text-muted small mb-0">Create and manage festival competitions, assigned sections, and categories.</p>
        </div>
        <a href="{{ route('admin.programs.create') }}" class="btn btn-teal text-white shadow-sm" style="background-color: #0d9488;">
            <i class="bi bi-calendar-plus-fill me-1"></i> Add New Program
        </a>
    </div>

    <!-- Filters -->
    <div class="card admin-card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.programs.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search program name, code, venue..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="section_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Sections</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}" {{ request('section_id') == $sec->id ? 'selected' : '' }}>
                                {{ $sec->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->first_marks }}/{{ $cat->second_marks }}/{{ $cat->third_marks }} pts)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-secondary w-100">Filter</button>
                    @if(request()->hasAny(['search', 'section_id', 'category_id', 'status']))
                        <a href="{{ route('admin.programs.index') }}" class="btn btn-light"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Programs Table -->
    <div class="card admin-card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Program</th>
                            <th>Section</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Venue & Schedule</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Results</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programs as $prog)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark fs-6">{{ $prog->name }}</div>
                                    <span class="badge bg-light text-secondary border font-monospace">{{ $prog->code }}</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-1 text-white" style="background-color: #0d9488;">
                                        {{ $prog->section->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">
                                        {{ $prog->category->name ?? 'N/A' }}
                                    </span>
                                    <div class="small text-muted mt-1">
                                        {{ $prog->category->first_marks ?? 0 }}-{{ $prog->category->second_marks ?? 0 }}-{{ $prog->category->third_marks ?? 0 }} pts
                                    </div>
                                </td>
                                <td>
                                    @if($prog->type === 'individual')
                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">
                                            <i class="bi bi-person me-1"></i> Individual
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">
                                            <i class="bi bi-people me-1"></i> Group ({{ $prog->max_participants }} max)
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">{{ $prog->venue ?: 'Main Stage' }}</div>
                                    <div class="small text-muted">
                                        {{ $prog->scheduled_at ? $prog->scheduled_at->format('d M, h:i A') : 'TBD' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($prog->status === 'completed')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">Completed</span>
                                    @elseif($prog->status === 'ongoing')
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1">Ongoing</span>
                                    @elseif($prog->status === 'scheduled')
                                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-1">Scheduled</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1">Cancelled</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.results.create', ['program_id' => $prog->id]) }}" class="btn btn-sm btn-outline-success rounded-pill px-3" title="Enter or view results">
                                        <i class="bi bi-award me-1"></i> {{ $prog->results->count() }}/3 Entered
                                    </a>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.programs.edit', $prog) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.programs.destroy', $prog) }}" method="POST" onsubmit="return confirm('Delete program {{ $prog->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x display-4 mb-3 d-block text-secondary"></i>
                                    <h5>No Programs Found</h5>
                                    <p class="small">Click "Add New Program" to schedule festival events.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($programs->hasPages())
                <div class="p-3 border-top">
                    {{ $programs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
