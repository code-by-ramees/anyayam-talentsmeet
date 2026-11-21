@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid px-lg-4">
    
    <!-- Top Bar with Quick Actions -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="fw-extrabold mb-1">Super Admin Dashboard</h3>
            <p class="text-muted small mb-0">{{ $festName }} · Centralized Fest Control & Scoring Engine</p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('admin.results.create') }}" class="btn btn-primary rounded-pill px-3 btn-sm shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Enter New Result
            </a>
            <a href="{{ route('admin.programs.create') }}" class="btn btn-outline-dark rounded-pill px-3 btn-sm">
                <i class="bi bi-calendar-plus me-1"></i> Add Program
            </a>
            <a href="{{ route('admin.groups.create') }}" class="btn btn-outline-secondary rounded-pill px-3 btn-sm">
                <i class="bi bi-people me-1"></i> Add Group
            </a>
        </div>
    </div>

    <!-- 6 Key Metrics Cards -->
    <div class="row g-3 mb-4">
        <!-- 1. Total Groups -->
        <div class="col-sm-6 col-xl-2">
            <div class="admin-card p-3 border-start border-4 border-primary">
                <span class="text-muted fs-xs fw-bold text-uppercase d-block">Groups</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="fs-4 fw-extrabold text-dark">{{ $totalGroups }}</span>
                    <i class="bi bi-people-fill text-primary fs-4"></i>
                </div>
                <small class="text-muted fs-xs">Participating Houses</small>
            </div>
        </div>

        <!-- 2. Total Members -->
        <div class="col-sm-6 col-xl-2">
            <div class="admin-card p-3 border-start border-4 border-info">
                <span class="text-muted fs-xs fw-bold text-uppercase d-block">Members</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="fs-4 fw-extrabold text-dark">{{ $totalMembers }}</span>
                    <i class="bi bi-person-badge-fill text-info fs-4"></i>
                </div>
                <small class="text-muted fs-xs">Registered Students</small>
            </div>
        </div>

        <!-- 3. Programs -->
        <div class="col-sm-6 col-xl-2">
            <div class="admin-card p-3 border-start border-4 border-warning">
                <span class="text-muted fs-xs fw-bold text-uppercase d-block">Programs</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="fs-4 fw-extrabold text-dark">{{ $totalPrograms }}</span>
                    <i class="bi bi-calendar-check-fill text-warning fs-4"></i>
                </div>
                <small class="text-muted fs-xs">Competitions</small>
            </div>
        </div>

        <!-- 4. Categories -->
        <div class="col-sm-6 col-xl-2">
            <div class="admin-card p-3 border-start border-4 border-secondary">
                <span class="text-muted fs-xs fw-bold text-uppercase d-block">Categories</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="fs-4 fw-extrabold text-dark">{{ $totalCategories }}</span>
                    <i class="bi bi-tags-fill text-secondary fs-4"></i>
                </div>
                <small class="text-muted fs-xs">Mark Configurations</small>
            </div>
        </div>

        <!-- 5. Results Entered -->
        <div class="col-sm-6 col-xl-2">
            <div class="admin-card p-3 border-start border-4 border-dark">
                <span class="text-muted fs-xs fw-bold text-uppercase d-block">Results Entered</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="fs-4 fw-extrabold text-dark">{{ $totalResultsEntered }}</span>
                    <i class="bi bi-award-fill text-dark fs-4"></i>
                </div>
                <small class="text-muted fs-xs">{{ $draftResultsCount }} in draft</small>
            </div>
        </div>

        <!-- 6. Published Results -->
        <div class="col-sm-6 col-xl-2">
            <div class="admin-card p-3 border-start border-4 border-success">
                <span class="text-muted fs-xs fw-bold text-uppercase d-block">Published</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="fs-4 fw-extrabold text-success">{{ $totalPublishedResults }}</span>
                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                </div>
                <small class="text-success fs-xs fw-bold">Live on Portals</small>
            </div>
        </div>
    </div>

    <!-- Section Statistics Cards -->
    <div class="row g-3 mb-4">
        @foreach($sections as $sec)
            <div class="col-md-6">
                <div class="admin-card p-3 bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-dark rounded-pill mb-1">{{ $sec->code }}</span>
                            <h5 class="fw-bold mb-0 text-dark">{{ $sec->name }} Section</h5>
                            <small class="text-muted">{{ $sec->description }}</small>
                        </div>
                        <div class="text-end">
                            <div><strong class="fs-5">{{ $sec->members_count }}</strong> <small class="text-muted">Students</small></div>
                            <div><strong class="fs-5">{{ $sec->programs_count }}</strong> <small class="text-muted">Programs</small></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        
        <!-- Left: Current Group Leaderboard -->
        <div class="col-lg-8">
            <div class="admin-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-trophy text-warning"></i> Group Performance & Scoreboard
                    </h5>
                    <a href="{{ route('admin.rankings.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                        Full Rankings <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                @if($rankings->isEmpty())
                    <div class="text-center py-4 text-muted">
                        No participating groups registered yet.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small text-uppercase text-muted">
                                <tr>
                                    <th style="width: 50px;">Rank</th>
                                    <th>Group Name</th>
                                    <th>Code</th>
                                    <th class="text-center">Members</th>
                                    <th class="text-center">1st / 2nd / 3rd</th>
                                    <th class="text-end">Draft Pts</th>
                                    <th class="text-end pe-3">Published Pts</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rankings as $idx => $r)
                                    <tr class="{{ $idx === 0 ? 'table-warning-subtle fw-semibold' : '' }}">
                                        <td>
                                            @if($idx === 0)
                                                <span class="badge bg-warning text-dark">🥇 1</span>
                                            @elseif($idx === 1)
                                                <span class="badge bg-secondary text-white">🥈 2</span>
                                            @elseif($idx === 2)
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">🥉 3</span>
                                            @else
                                                <span class="text-muted fw-bold">#{{ $idx + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span style="width: 12px; height: 12px; border-radius: 4px; background-color: {{ $r['group']->color }};"></span>
                                                <strong class="text-dark">{{ $r['group']->name }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-muted border font-monospace">{{ $r['group']->code }}</span>
                                        </td>
                                        <td class="text-center text-muted small">
                                            {{ $r['members_count'] }}
                                        </td>
                                        <td class="text-center small">
                                            <span class="text-warning fw-bold">{{ $r['first_prizes'] }}</span> /
                                            <span class="text-secondary fw-bold">{{ $r['second_prizes'] }}</span> /
                                            <span class="text-danger fw-bold">{{ $r['third_prizes'] }}</span>
                                        </td>
                                        <td class="text-end text-muted small">
                                            {{ $r['draft_marks'] }} pts
                                        </td>
                                        <td class="text-end pe-3">
                                            <span class="fw-extrabold fs-6 text-success">{{ $r['total_marks'] }} pts</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Recent Activities & Quick Navigation -->
        <div class="col-lg-4">
            
            <!-- Quick Actions Card -->
            <div class="admin-card p-3 mb-4 bg-light border-0">
                <h6 class="fw-bold mb-2 small text-uppercase text-muted">Quick Operations</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.results.index') }}" class="btn btn-white border rounded-3 text-start d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-award text-primary me-2"></i>Manage Results</span>
                        <span class="badge bg-primary rounded-pill">{{ $draftResultsCount }} Drafts</span>
                    </a>
                    <a href="{{ route('admin.groups.index') }}" class="btn btn-white border rounded-3 text-start d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-people text-teal me-2"></i>Group Portals & Logins</span>
                        <span class="badge bg-light text-muted border">{{ $totalGroups }}</span>
                    </a>
                    <a href="{{ route('admin.rankings.print') }}" target="_blank" class="btn btn-white border rounded-3 text-start d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-printer text-dark me-2"></i>Print Official Scoresheet</span>
                        <i class="bi bi-box-arrow-up-right fs-xs text-muted"></i>
                    </a>
                </div>
            </div>

            <!-- Activity Logs -->
            <div class="admin-card p-3">
                <h6 class="fw-bold mb-3 small text-uppercase text-muted d-flex align-items-center gap-1">
                    <i class="bi bi-clock-history text-secondary"></i> Recent Audit Log
                </h6>

                @if($recentActivities->isEmpty())
                    <p class="text-muted small mb-0">No recent activities recorded.</p>
                @else
                    <div class="list-group list-group-flush fs-xs">
                        @foreach($recentActivities as $act)
                            <div class="list-group-item px-0 py-2 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-dark">{{ $act->action }}</strong>
                                    <span class="text-muted">{{ $act->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="text-muted mt-1">{{ $act->description }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
