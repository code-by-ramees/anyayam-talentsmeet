<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white py-3 px-4 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="bi bi-person-badge-fill text-danger" style="color: #9b111e !important;"></i>
                <span>{{ $title ?? 'Individual Score Leaderboard' }}</span>
            </h5>
            <small class="text-muted">Top individual performers ranked by total points earned in individual competitions.</small>
        </div>
        <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">
            Tie-Breaker: Points &rarr; 1st &rarr; 2nd &rarr; 3rd
        </span>
    </div>

    <div class="card-body p-0">
        @if($individualRankings->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-person-x display-4 d-block mb-2 text-secondary"></i>
                <h6 class="fw-bold mb-1">No Individual Scores Published Yet</h6>
                <p class="small mb-0">Individual rankings will appear here once results for individual events are published.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 text-center" style="width: 75px;">Rank</th>
                            <th>Participant</th>
                            <th>Chest No</th>
                            <th>Group / House</th>
                            <th>Section</th>
                            <th class="text-center">1st</th>
                            <th class="text-center">2nd</th>
                            <th class="text-center">3rd</th>
                            <th class="text-end pe-4">Total Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($individualRankings as $row)
                            @php
                                $m = $row['member'];
                                $g = $row['group'];
                                $isHighlighted = isset($highlightGroupId) && $g && $g->id === $highlightGroupId;
                            @endphp
                            <tr class="{{ $isHighlighted ? 'table-primary bg-opacity-25 fw-semibold' : '' }}">
                                <td class="ps-4 text-center">
                                    @if($row['rank'] === 1)
                                        <span class="badge bg-warning text-dark rounded-circle p-2 fs-6 shadow-xs" title="1st Place">
                                            <i class="bi bi-trophy-fill"></i>
                                        </span>
                                    @elseif($row['rank'] === 2)
                                        <span class="badge bg-secondary text-white rounded-circle px-2 py-1 fs-6">2</span>
                                    @elseif($row['rank'] === 3)
                                        <span class="badge text-white rounded-circle px-2 py-1 fs-6" style="background-color: #cd7f32;">3</span>
                                    @else
                                        <span class="text-muted fw-bold">#{{ $row['rank'] }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $m->name }}</div>
                                    <small class="text-muted font-monospace">{{ $m->uid }} &bull; {{ $m->class_year }}</small>
                                </td>
                                <td>
                                    @if($m->chest_number)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle font-monospace fw-bold">
                                            #{{ $m->chest_number }}
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($g)
                                        <span class="d-inline-flex align-items-center gap-2">
                                            <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background-color: {{ $g->color ?? '#9b111e' }};"></span>
                                            <span class="text-dark">{{ $g->name }}</span>
                                            @if($isHighlighted)
                                                <span class="badge bg-primary text-white" style="font-size: 0.65rem;">YOUR GROUP</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border">
                                        {{ $m->section->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($row['first_prizes'] > 0)
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2">{{ $row['first_prizes'] }}</span>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($row['second_prizes'] > 0)
                                        <span class="badge bg-secondary-subtle text-secondary border px-2">{{ $row['second_prizes'] }}</span>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($row['third_prizes'] > 0)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2">{{ $row['third_prizes'] }}</span>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <span class="fs-5 fw-extrabold" style="color: #9b111e;">{{ $row['total_marks'] }}</span>
                                    <small class="text-muted">pts</small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
