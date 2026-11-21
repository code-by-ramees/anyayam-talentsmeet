@extends('layouts.admin')

@section('title', 'Competition Results & Awards')

@section('content')
<div class="container-fluid px-lg-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Competition Results & Awards</h2>
            <p class="text-muted small mb-0">Record winners, automatically calculate points, and manage publication status.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if($draftCount > 0)
                <form action="{{ route('admin.results.publish-all') }}" method="POST" onsubmit="return confirm('Publish all {{ $draftCount }} pending draft results to group portals and public leaderboard?');">
                    @csrf
                    <button type="submit" class="btn btn-warning text-dark fw-semibold shadow-sm">
                        <i class="bi bi-broadcast me-1"></i> Publish All Drafts ({{ $draftCount }})
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.results.create') }}" class="btn btn-teal text-white shadow-sm" style="background-color: #9b111e;">
                <i class="bi bi-plus-circle me-1"></i> Enter New Result
            </a>
        </div>
    </div>

    <!-- Status Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card admin-card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary-subtle text-primary rounded-3 p-3 fs-4">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ $publishedCount + $draftCount }}</div>
                        <div class="text-muted small">Total Results Entered</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card admin-card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success-subtle text-success rounded-3 p-3 fs-4">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-success">{{ $publishedCount }}</div>
                        <div class="text-muted small">Published Live</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card admin-card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-warning-subtle text-warning-emphasis rounded-3 p-3 fs-4">
                        <i class="bi bi-file-earmark-medical-fill"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-warning-emphasis">{{ $draftCount }}</div>
                        <div class="text-muted small">Saved as Drafts</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card admin-card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-info-subtle text-info rounded-3 p-3 fs-4">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                    <div>
                        <a href="{{ route('public.results') }}" target="_blank" class="fw-bold text-decoration-none text-dark d-block">
                            View Public Page <i class="bi bi-box-arrow-up-right small"></i>
                        </a>
                        <div class="text-muted small">Live Result Feed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card admin-card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.results.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search program or participant..." value="{{ request('search') }}">
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
                <div class="col-md-2">
                    <select name="group_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Groups</option>
                        @foreach($groups as $grp)
                            <option value="{{ $grp->id }}" {{ request('group_id') == $grp->id ? 'selected' : '' }}>
                                {{ $grp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published Only</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Drafts Only</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-secondary w-100">Filter</button>
                    @if(request()->hasAny(['search', 'section_id', 'group_id', 'status']))
                        <a href="{{ route('admin.results.index') }}" class="btn btn-light"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card admin-card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Program</th>
                            <th>Section & Cat</th>
                            <th>Award & Position</th>
                            <th>Winning Group</th>
                            <th>Participant / Team</th>
                            <th class="text-center">Points</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $res)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark">{{ $res->program->name ?? 'N/A' }}</div>
                                    <span class="badge bg-light text-secondary border font-monospace">{{ $res->program->code ?? '' }}</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-2 py-1 text-white" style="background-color: #9b111e;">
                                        {{ $res->program->section->name ?? 'N/A' }}
                                    </span>
                                    <span class="badge bg-light text-dark border">
                                        {{ $res->program->category->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $res->getPositionBadgeClass() }} px-3 py-2 fs-6">
                                        <i class="bi bi-trophy-fill me-1"></i> {{ $res->getPositionLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge px-2 py-1 text-white" style="background-color: {{ $res->group->color ?? '#9b111e' }};">
                                        {{ $res->group->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $res->participant_name }}</div>
                                    @if($res->remarks)
                                        <small class="text-muted d-block">{{ $res->remarks }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success fs-6 fw-bold px-3 py-1 border border-success-subtle">
                                        +{{ $res->marks }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.results.toggle-publish', $res) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm p-0 border-0" title="Click to toggle publication">
                                            @if($res->is_published)
                                                <span class="badge bg-success rounded-pill px-3 py-1 cursor-pointer">
                                                    <i class="bi bi-globe me-1"></i> Published
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 cursor-pointer">
                                                    <i class="bi bi-hourglass-split me-1"></i> Draft
                                                </span>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.results.edit', $res) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.results.destroy', $res) }}" method="POST" onsubmit="return confirm('Delete this result entry? Points will be deducted.');">
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
                                    <i class="bi bi-award display-4 mb-3 d-block text-secondary"></i>
                                    <h5>No Results Found</h5>
                                    <p class="small">Click "Enter New Result" above to record competition winners.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($results->hasPages())
                <div class="p-3 border-top">
                    {{ $results->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
