@extends('layouts.admin')

@section('title', 'Event Registrations')

@section('content')
    <div class="container-fluid px-lg-4">
        <!-- Header with Toggle Banner -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">Competition & Event Registrations</h2>
                <p class="text-muted small mb-0">Manage group event entries, view registered participants, and control
                    registration status.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <!-- Registration Master Toggle -->
                <form action="{{ route('admin.registrations.toggle-status') }}" method="POST" class="d-inline">
                    @csrf
                    @if($isRegistrationOpen)
                        <button type="submit" class="btn btn-outline-danger shadow-sm rounded-pill px-3 py-2 fw-semibold"
                            onclick="return confirm('Are you sure you want to CLOSE event registrations? Groups will not be able to add or cancel registrations.');">
                            <i class="bi bi-lock-fill me-1"></i> Close Group Registration
                        </button>
                    @else
                        <button type="submit" class="btn btn-teal text-white shadow-sm rounded-pill px-3 py-2 fw-semibold"
                            style="background-color: #9b111e;"
                            onclick="return confirm('Are you sure you want to OPEN event registrations for group portals?');">
                            <i class="bi bi-unlock-fill me-1"></i> Open Group Registration
                        </button>
                    @endif
                </form>

                <a href="{{ route('admin.registrations.create') }}"
                    class="btn btn-teal text-white shadow-sm rounded-pill px-3 py-2 fw-semibold"
                    style="background-color: #9b111e;">
                    <i class="bi bi-plus-lg me-1"></i> Register Participants
                </a>
            </div>
        </div>

        <!-- Status & Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card admin-card border-0 shadow-sm p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Registration Status</div>
                            <h4 class="fw-bold mb-0 mt-1">
                                @if($isRegistrationOpen)
                                    <span
                                        class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 fs-6">
                                        <i class="bi bi-check-circle-fill me-1"></i> OPEN
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 fs-6">
                                        <i class="bi bi-x-circle-fill me-1"></i> CLOSED
                                    </span>
                                @endif
                            </h4>
                        </div>
                        <div
                            class="p-3 rounded-circle {{ $isRegistrationOpen ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} fs-4">
                            <i class="bi {{ $isRegistrationOpen ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card admin-card border-0 shadow-sm p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Total Registrations</div>
                            <h4 class="fw-bold mb-0 mt-1">{{ number_format($totalRegistrations) }}</h4>
                        </div>
                        <div class="p-3 rounded-circle bg-teal-subtle text-teal fs-4"
                            style="background-color: rgba(13,148,136,0.1); color: #9b111e;">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card admin-card border-0 shadow-sm p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Programs With Entries</div>
                            <h4 class="fw-bold mb-0 mt-1">{{ $registeredProgramsCount }}</h4>
                        </div>
                        <div class="p-3 rounded-circle bg-primary-subtle text-primary fs-4">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card admin-card border-0 shadow-sm p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Active Groups</div>
                            <h4 class="fw-bold mb-0 mt-1">{{ $participatingGroupsCount }}</h4>
                        </div>
                        <div class="p-3 rounded-circle bg-warning-subtle text-warning fs-4">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="card admin-card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.registrations.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i
                                    class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                placeholder="Search participant, UID, Chest No, program..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="group_id" class="form-select" onchange="this.form.submit()">
                            <option value="">All Groups...</option>
                            @foreach($groups as $grp)
                                <option value="{{ $grp->id }}" {{ request('group_id') == $grp->id ? 'selected' : '' }}>
                                    {{ $grp->name }} ({{ $grp->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="program_id" class="form-select" onchange="this.form.submit()">
                            <option value="">All Programs...</option>
                            @foreach($programs as $prg)
                                <option value="{{ $prg->id }}" {{ request('program_id') == $prg->id ? 'selected' : '' }}>
                                    {{ $prg->name }} ({{ $prg->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="section_id" class="form-select" onchange="this.form.submit()">
                            <option value="">All Sections...</option>
                            @foreach($sections as $sec)
                                <option value="{{ $sec->id }}" {{ request('section_id') == $sec->id ? 'selected' : '' }}>
                                    {{ $sec->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="stage_type" class="form-select" onchange="this.form.submit()">
                            <option value="">All Stage Types...</option>
                            <option value="stage" {{ request('stage_type') === 'stage' ? 'selected' : '' }}>Stage Programs
                            </option>
                            <option value="non_stage" {{ request('stage_type') === 'non_stage' ? 'selected' : '' }}>Non-Stage
                                Programs</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex gap-1">
                        <button type="submit" class="btn btn-secondary w-100">Filter</button>
                        @if(request()->hasAny(['search', 'group_id', 'program_id', 'section_id', 'stage_type']))
                            <a href="{{ route('admin.registrations.index') }}" class="btn btn-light"><i
                                    class="bi bi-x-lg"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Registrations Table -->
        <div class="card admin-card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Program</th>
                                <th>Section & Stage</th>
                                <th>Group / House</th>
                                <th>Participant (Member)</th>
                                <th>Chest No</th>
                                <th>Class</th>
                                <th>Registered By</th>
                                <th>Date</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $reg)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark">{{ $reg->program->name ?? 'N/A' }}</div>
                                        <span
                                            class="badge bg-light text-muted border font-monospace">{{ $reg->program->code ?? '' }}</span>
                                        <span
                                            class="badge bg-secondary-subtle text-secondary border ms-1">{{ ucfirst($reg->program->type ?? 'individual') }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ strtolower($reg->program->section->name ?? '') === 'general' ? 'bg-primary-subtle text-primary border' : 'bg-info-subtle text-dark border' }}">
                                            {{ $reg->program->section->name ?? 'N/A' }}
                                        </span>
                                        <span
                                            class="badge {{ ($reg->program->stage_type ?? 'stage') === 'stage' ? 'bg-purple-subtle text-purple border' : 'bg-warning-subtle text-dark border' }}"
                                            style="background-color: #f3e8ff; color: #7e22ce;">
                                            {{ ($reg->program->stage_type ?? 'stage') === 'non_stage' ? 'Non-Stage' : 'Stage' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="d-inline-flex align-items-center gap-1">
                                            <span
                                                style="display:inline-block;width:10px;height:10px;border-radius:50%;background-color:{{ $reg->group->color ?? '#9b111e' }};"></span>
                                            <strong>{{ $reg->group->name ?? 'N/A' }}</strong>
                                            <small class="text-muted">({{ $reg->group->code ?? '' }})</small>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $reg->member->name ?? 'N/A' }}</div>
                                        <small class="text-muted font-monospace"><i
                                                class="bi bi-upc-scan me-1"></i>{{ $reg->member->uid ?? '' }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $chest = $reg->chest_number ?: ($reg->member->chest_number ?? null);
                                        @endphp
                                        @if($chest)
                                            <span
                                                class="badge bg-danger-subtle text-danger border border-danger-subtle font-monospace fw-bold px-2 py-1">
                                                #{{ $chest }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border px-2 py-1 small">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-light text-dark border">{{ $reg->member->class_year ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if($reg->registered_by === 'admin')
                                            <span class="badge bg-dark-subtle text-dark border"><i
                                                    class="bi bi-shield-check me-1"></i> Admin</span>
                                        @else
                                            <span class="badge bg-teal-subtle text-teal border"
                                                style="background-color: rgba(13,148,136,0.1); color: #9b111e;"><i
                                                    class="bi bi-house me-1"></i> Group</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $reg->created_at->format('M d, Y h:i A') }}</small>
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('admin.registrations.destroy', $reg) }}" method="POST"
                                            onsubmit="return confirm('Remove registration for {{ $reg->member->name ?? 'participant' }} from {{ $reg->program->name ?? 'program' }}?');"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                title="Delete Registration">
                                                <i class="bi bi-trash"></i> Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="bi bi-clipboard-x display-4 mb-3 d-block text-secondary"></i>
                                        <h5>No Event Registrations Found</h5>
                                        <p class="small">No participants have been registered for events matching this criteria.
                                        </p>
                                        <a href="{{ route('admin.registrations.create') }}"
                                            class="btn btn-teal text-white rounded-pill px-4"
                                            style="background-color: #9b111e;">
                                            <i class="bi bi-plus-lg me-1"></i> Register Participants
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($registrations->hasPages())
                    <div class="p-3 border-top">
                        {{ $registrations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection