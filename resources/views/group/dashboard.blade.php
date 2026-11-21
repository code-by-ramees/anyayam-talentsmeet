@extends('layouts.group')

@section('title', 'Dashboard — ' . $group->name)

@section('content')
    <div class="container py-4">
        <!-- Welcome Banner -->
        <div class="card group-card border-0 shadow-sm p-4 mb-4 text-white position-relative overflow-hidden"
            style="background: linear-gradient(135deg, {{ $group->color ?: '#0d9488' }} 0%, #0f172a 100%);">
            <div class="row align-items-center position-relative" style="z-index: 2;">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-white text-dark font-monospace px-3 py-1 fw-bold fs-6">
                            CODE: {{ $group->code }}
                        </span>
                        <span class="badge bg-white-50 text-white px-3 py-1 rounded-pill">
                            Sanaviyya & Bakalooriyya
                        </span>
                    </div>
                    <h2 class="display-6 fw-bold mb-2">{{ $group->name }}</h2>
                    <p class="mb-0 text-white-50">
                        Welcome to your dedicated fest portal for <strong>{{ $festName }}</strong>. Track your group's live
                        standings, awards won, and participants.
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="d-inline-block bg-white bg-opacity-10 backdrop-blur p-3 rounded-4 text-center">
                        <div class="small text-uppercase text-white-50 fw-semibold" style="letter-spacing: 0.5px;">Overall
                            Standing</div>
                        <div class="display-5 fw-bold text-white">#{{ $overallRank }}</div>
                        <div class="small text-white-50">#{{ $bakalooriyyaRank }} Bakalooriyya &bull; #{{ $sanaviyyaRank }}
                            Sanaviyya</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card group-card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3 text-white fs-4"
                            style="background-color: {{ $group->color ?: '#0d9488' }};">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold text-dark">{{ $totalMarks }}</div>
                            <div class="text-muted small">Total Points Won</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card group-card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning-subtle text-warning-emphasis rounded-3 p-3 fs-4">
                            <i class="bi bi-trophy-fill"></i>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold text-dark">{{ $firstPrizes }}</div>
                            <div class="text-muted small">1st Prizes Won</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card group-card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-secondary-subtle text-secondary rounded-3 p-3 fs-4">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold text-dark">{{ $secondPrizes + $thirdPrizes }}</div>
                            <div class="text-muted small">2nd & 3rd Prizes</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card group-card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary-subtle text-primary rounded-3 p-3 fs-4">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold text-dark">{{ $group->members->count() }}</div>
                            <div class="text-muted small">Registered Members</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Won Awards Section -->
            <div class="col-lg-8">
                <div class="card group-card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-award text-success me-1"></i> Our Group's Published Awards
                        </h5>
                        <a href="{{ route('group.results') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            View All ({{ $myResults->count() }})
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Program</th>
                                        <th>Category</th>
                                        <th>Participant</th>
                                        <th>Prize</th>
                                        <th class="text-end pe-4">Points</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($myResults->take(6) as $res)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="fw-bold text-dark">{{ $res->program->name ?? '' }}</div>
                                                <span
                                                    class="badge bg-light text-secondary border font-monospace">{{ $res->program->code ?? '' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                                    {{ $res->program->category->name ?? '' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-dark">{{ $res->participant_name }}</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $res->getPositionBadgeClass() }} px-3 py-1">
                                                    {{ $res->getPositionLabel() }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <span class="badge bg-success fs-6">+{{ $res->marks }} pts</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="bi bi-hourglass-split display-5 d-block text-secondary mb-2"></i>
                                                <p class="mb-1 fw-bold">No published awards yet</p>
                                                <small>Results will appear here as soon as competition outcomes are published by
                                                    administrators.</small>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Section Programs & Actions -->
            <div class="col-lg-4">
                <!-- Upcoming Festival Programs -->
                <div class="card group-card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-calendar3 me-1 text-teal" style="color: #0d9488;"></i> Festival Programs
                        </h6>
                        <a href="{{ route('group.programs') }}" class="small text-decoration-none">View All</a>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse($sectionPrograms as $prog)
                            <div class="list-group-item px-3 py-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <span class="fw-bold text-dark small">{{ $prog->name }}</span>
                                    <span class="badge bg-light text-secondary border">{{ $prog->code }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center text-muted small">
                                    <span
                                        class="badge {{ $prog->stage_type === 'stage' ? 'text-primary border border-primary-subtle' : 'text-dark border border-warning-subtle' }}"
                                        style="{{ $prog->stage_type === 'stage' ? 'background-color: #f3e8ff; color: #7e22ce !important; border-color: #d8b4fe !important;' : 'background-color: #fef3c7;' }}">
                                        <i
                                            class="bi {{ $prog->stage_type === 'stage' ? 'bi-mic' : 'bi-pencil-square' }} me-1"></i>{{ $prog->stage_type === 'stage' ? 'Stage' : 'Non-Stage' }}
                                    </span>
                                    <div class="d-flex gap-1">
                                        <span
                                            class="badge bg-secondary-subtle text-secondary">{{ $prog->section->name ?? '' }}</span>
                                        <span
                                            class="badge bg-info-subtle text-info border border-info-subtle">{{ $prog->category->name ?? '' }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-3 text-center text-muted small">No programs scheduled yet.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Action Links -->
                <div class="card group-card border-0 shadow-sm p-4 bg-light">
                    <h6 class="fw-bold mb-3 text-dark">Portal Shortcuts</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('group.members') }}"
                            class="btn btn-outline-dark btn-sm text-start py-2 px-3 bg-white">
                            <i class="bi bi-person-lines-fill me-2 text-primary"></i> View & Manage Our Members
                        </a>
                        <a href="{{ route('group.rankings') }}"
                            class="btn btn-outline-dark btn-sm text-start py-2 px-3 bg-white">
                            <i class="bi bi-bar-chart-line me-2 text-success"></i> View Full Fest Leaderboard
                        </a>
                        <a href="{{ route('group.profile') }}"
                            class="btn btn-outline-dark btn-sm text-start py-2 px-3 bg-white">
                            <i class="bi bi-gear me-2 text-secondary"></i> Group Profile & Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection