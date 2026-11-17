@extends('layouts.app')

@section('title', "Published Results — {$festName}")

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('public.index') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active">Published Results</li>
                </ol>
            </nav>
            <h3 class="fw-extrabold mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-award text-primary"></i>
                <span>Published Competition Results</span>
            </h3>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="fest-card p-3 mb-4 bg-white">
        <form action="{{ route('public.results') }}" method="GET" class="row g-2 align-items-end">
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
                <label class="form-label fs-xs text-muted fw-bold mb-1">Filter by Group</label>
                <select name="group_id" class="form-select form-select-sm rounded-pill">
                    <option value="">All Groups</option>
                    @foreach($groups as $grp)
                        <option value="{{ $grp->id }}" {{ request('group_id') == $grp->id ? 'selected' : '' }}>
                            {{ $grp->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <label class="form-label fs-xs text-muted fw-bold mb-1">Search Participant / Program</label>
                        <input type="text" name="search" class="form-control form-control-sm rounded-pill" placeholder="Name or code..." value="{{ request('search') }}">
                    </div>
                    <div class="align-self-end">
                        <button type="submit" class="btn btn-fest-primary btn-sm rounded-pill px-3">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Table -->
    <div class="fest-card overflow-hidden">
        <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">
                Official Results Feed
            </h6>
            <span class="badge bg-white text-muted border">{{ $results->total() }} Results Published</span>
        </div>

        @if($results->isEmpty())
            <div class="p-5 text-center text-muted">
                <i class="bi bi-inbox display-4 d-block mb-2 text-muted"></i>
                <h5>No published results found.</h5>
                <p class="small mb-0">Try clearing your filters or search keywords.</p>
                <a href="{{ route('public.results') }}" class="btn btn-sm btn-outline-secondary rounded-pill mt-2">Clear Filters</a>
            </div>
        @else
            <div class="table-responsive mb-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase text-muted">
                        <tr>
                            <th>Program</th>
                            <th>Section & Category</th>
                            <th>Position & Prize</th>
                            <th>Participant / Team</th>
                            <th>Group</th>
                            <th class="text-end pe-4">Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $res)
                            <tr>
                                <td>
                                    <strong class="text-dark">{{ $res->program->name }}</strong>
                                    <small class="text-muted d-block fs-xs">{{ $res->program->code }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $res->program->section->name }}</span>
                                    <span class="badge bg-secondary-subtle text-secondary">{{ $res->program->category->name }}</span>
                                </td>
                                <td>
                                    <span class="{{ $res->getPositionBadgeClass() }} rounded-pill px-3 py-1 fw-bold">
                                        {{ $res->getPositionLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $res->participant_name }}</div>
                                    @if($res->remarks)
                                        <small class="text-muted fs-xs"><i class="bi bi-chat-left-quote me-1"></i>{{ $res->remarks }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="width: 12px; height: 12px; border-radius: 50%; background-color: {{ $res->group->color }};"></span>
                                        <span class="fw-bold text-dark">{{ $res->group->name }}</span>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle fs-6 px-3 py-1 fw-bold">
                                        +{{ $res->marks }} Pts
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($results->hasPages())
                <div class="p-3 border-top bg-light">
                    {{ $results->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
