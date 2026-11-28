@extends('layouts.group')

@section('title', 'Fest Leaderboard & Standings — ' . $group->name)

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Live Leaderboard & Standings</h2>
            <p class="text-muted small mb-0">{{ $festName }} &bull; Official published group rankings.</p>
        </div>
        <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">
            <i class="bi bi-broadcast text-danger me-1"></i> Live Published Scores
        </span>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-pills mb-4 bg-white p-2 rounded-3 shadow-sm border" id="portalRankTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold" id="overall-p-tab" data-bs-toggle="pill" data-bs-target="#overallP" type="button" role="tab">
                <i class="bi bi-trophy-fill text-warning me-1"></i> Overall Championship
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="bak-p-tab" data-bs-toggle="pill" data-bs-target="#bakP" type="button" role="tab">
                <i class="bi bi-mortarboard-fill text-primary me-1"></i> Bakalooriyya (Senior)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="san-p-tab" data-bs-toggle="pill" data-bs-target="#sanP" type="button" role="tab">
                <i class="bi bi-book-fill text-teal me-1" style="color: #9b111e;"></i> Sanaviyya Ulya (Junior)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="ind-p-tab" data-bs-toggle="pill" data-bs-target="#indP" type="button" role="tab">
                <i class="bi bi-person-badge-fill text-danger me-1" style="color: #9b111e;"></i> Individual Score Leaderboard
            </button>
        </li>
    </ul>

    <div class="tab-content" id="portalRankTabsContent">
        <!-- Overall Tab -->
        <div class="tab-pane fade show active" id="overallP" role="tabpanel">
            <div class="card group-card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark">Overall Combined Championship</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 text-center" style="width: 70px;">Rank</th>
                                    <th>Group / House</th>
                                    <th class="text-center">1st</th>
                                    <th class="text-center">2nd</th>
                                    <th class="text-center">3rd</th>
                                    <th class="text-center">Total Prizes</th>
                                    <th class="text-end pe-4">Combined Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($overallRankings as $row)
                                    @php $isMyGroup = ($row['group']->id === $group->id); @endphp
                                    <tr class="{{ $isMyGroup ? 'table-primary bg-opacity-25 fw-bold' : '' }}">
                                        <td class="ps-4 text-center">
                                            @if($row['rank'] === 1)
                                                <span class="badge bg-warning text-dark rounded-circle p-2 fs-6">
                                                    <i class="bi bi-trophy-fill"></i>
                                                </span>
                                            @elseif($row['rank'] === 2)
                                                <span class="badge bg-secondary text-white rounded-circle p-2 fs-6">2</span>
                                            @elseif($row['rank'] === 3)
                                                <span class="badge text-white rounded-circle p-2 fs-6" style="background-color: #cd7f32;">3</span>
                                            @else
                                                <span class="text-muted fw-bold">#{{ $row['rank'] }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="rounded-circle p-1 d-inline-block" style="width: 14px; height: 14px; background-color: {{ $row['group']->color ?? '#9b111e' }};"></span>
                                                <span class="text-dark">{{ $row['group']->name }}</span>
                                                @if($isMyGroup)
                                                    <span class="badge bg-primary text-white ms-1">YOU</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $row['first_prizes'] }}</td>
                                        <td class="text-center">{{ $row['second_prizes'] }}</td>
                                        <td class="text-center">{{ $row['third_prizes'] }}</td>
                                        <td class="text-center text-muted">{{ $row['total_prizes'] }}</td>
                                        <td class="text-end pe-4">
                                            <span class="fs-5 fw-bold text-success">{{ $row['total_marks'] }}</span>
                                            <small class="text-muted">pts</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bakalooriyya Tab -->
        <div class="tab-pane fade" id="bakP" role="tabpanel">
            <div class="card group-card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark">Bakalooriyya (Senior Section) Standings</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 text-center" style="width: 70px;">Rank</th>
                                    <th>Group / House</th>
                                    <th class="text-center">1st</th>
                                    <th class="text-center">2nd</th>
                                    <th class="text-center">3rd</th>
                                    <th class="text-center">Total Prizes</th>
                                    <th class="text-end pe-4">Section Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bakalooriyyaRankings as $row)
                                    @php $isMyGroup = ($row['group']->id === $group->id); @endphp
                                    <tr class="{{ $isMyGroup ? 'table-primary bg-opacity-25 fw-bold' : '' }}">
                                        <td class="ps-4 text-center">
                                            @if($row['rank'] === 1)
                                                <span class="badge bg-warning text-dark rounded-circle p-2 fs-6">
                                                    <i class="bi bi-trophy-fill"></i>
                                                </span>
                                            @elseif($row['rank'] === 2)
                                                <span class="badge bg-secondary text-white rounded-circle p-2 fs-6">2</span>
                                            @elseif($row['rank'] === 3)
                                                <span class="badge text-white rounded-circle p-2 fs-6" style="background-color: #cd7f32;">3</span>
                                            @else
                                                <span class="text-muted fw-bold">#{{ $row['rank'] }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="rounded-circle p-1 d-inline-block" style="width: 14px; height: 14px; background-color: {{ $row['group']->color ?? '#9b111e' }};"></span>
                                                <span class="text-dark">{{ $row['group']->name }}</span>
                                                @if($isMyGroup)
                                                    <span class="badge bg-primary text-white ms-1">YOU</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $row['first_prizes'] }}</td>
                                        <td class="text-center">{{ $row['second_prizes'] }}</td>
                                        <td class="text-center">{{ $row['third_prizes'] }}</td>
                                        <td class="text-center text-muted">{{ $row['total_prizes'] }}</td>
                                        <td class="text-end pe-4">
                                            <span class="fs-5 fw-bold text-success">{{ $row['total_marks'] }}</span>
                                            <small class="text-muted">pts</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sanaviyya Tab -->
        <div class="tab-pane fade" id="sanP" role="tabpanel">
            <div class="card group-card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark">Sanaviyya Ulya (Junior Section) Standings</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 text-center" style="width: 70px;">Rank</th>
                                    <th>Group / House</th>
                                    <th class="text-center">1st</th>
                                    <th class="text-center">2nd</th>
                                    <th class="text-center">3rd</th>
                                    <th class="text-center">Total Prizes</th>
                                    <th class="text-end pe-4">Section Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sanaviyyaRankings as $row)
                                    @php $isMyGroup = ($row['group']->id === $group->id); @endphp
                                    <tr class="{{ $isMyGroup ? 'table-primary bg-opacity-25 fw-bold' : '' }}">
                                        <td class="ps-4 text-center">
                                            @if($row['rank'] === 1)
                                                <span class="badge bg-warning text-dark rounded-circle p-2 fs-6">
                                                    <i class="bi bi-trophy-fill"></i>
                                                </span>
                                            @elseif($row['rank'] === 2)
                                                <span class="badge bg-secondary text-white rounded-circle p-2 fs-6">2</span>
                                            @elseif($row['rank'] === 3)
                                                <span class="badge text-white rounded-circle p-2 fs-6" style="background-color: #cd7f32;">3</span>
                                            @else
                                                <span class="text-muted fw-bold">#{{ $row['rank'] }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="rounded-circle p-1 d-inline-block" style="width: 14px; height: 14px; background-color: {{ $row['group']->color ?? '#9b111e' }};"></span>
                                                <span class="text-dark">{{ $row['group']->name }}</span>
                                                @if($isMyGroup)
                                                    <span class="badge bg-primary text-white ms-1">YOU</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $row['first_prizes'] }}</td>
                                        <td class="text-center">{{ $row['second_prizes'] }}</td>
                                        <td class="text-center">{{ $row['third_prizes'] }}</td>
                                        <td class="text-center text-muted">{{ $row['total_prizes'] }}</td>
                                        <td class="text-end pe-4">
                                            <span class="fs-5 fw-bold text-success">{{ $row['total_marks'] }}</span>
                                            <small class="text-muted">pts</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Individual Score Leaderboard Tab -->
        <div class="tab-pane fade" id="indP" role="tabpanel">
            @include('partials.individual_leaderboard_table', [
                'individualRankings' => $individualRankings,
                'title' => 'Individual Score Leaderboard',
                'highlightGroupId' => $group->id
            ])
        </div>
    </div>
</div>
@endsection
