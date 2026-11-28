<!-- Podium Display (Top 3) — stacked vertically on mobile, side-by-side on md+ -->
@if(count($rankings) >= 3)
    <div class="row g-3 align-items-end justify-content-center mb-4 pt-3">
        
        <!-- 2nd Place -->
        <div class="col-4 col-md-4 order-2 order-md-1 animate-slide-up" style="animation-delay: 0.1s;">
            <div class="fest-card p-2 p-md-3 text-center bg-white border-top border-4 border-secondary shadow-sm">
                <span class="rank-badge-2 mb-2">2</span>
                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background-color: {{ $rankings[1]['group']->color }};"></span>
                    <h6 class="fw-bold mb-0 text-truncate" style="font-size: clamp(0.7rem, 2.5vw, 1rem); max-width: 90px;">{{ $rankings[1]['group']->name }}</h6>
                </div>
                <div class="fw-extrabold text-dark" style="font-size: clamp(1.1rem, 4vw, 1.75rem);">{{ $rankings[1]['total_marks'] }} <small class="fs-xs text-muted">Pts</small></div>
                <div class="d-flex justify-content-center gap-1 mt-1 fs-xs text-muted">
                    <span><i class="bi bi-award-fill text-warning"></i> {{ $rankings[1]['first_prizes'] }}</span>
                    <span><i class="bi bi-award-fill text-secondary"></i> {{ $rankings[1]['second_prizes'] }}</span>
                    <span><i class="bi bi-award-fill text-danger"></i> {{ $rankings[1]['third_prizes'] }}</span>
                </div>
            </div>
        </div>

        <!-- 1st Place (Winner Center) -->
        <div class="col-4 col-md-4 order-1 order-md-2 animate-slide-up">
            <div class="fest-card p-2 p-md-4 text-center bg-white border-top border-4 border-warning shadow position-relative" style="transform: scale(1.03); border-color: #f59e0b !important;">
                <span class="position-absolute top-0 start-50 translate-middle badge bg-warning text-dark px-2 py-1 rounded-pill fw-bold shadow-sm d-none d-sm-inline-flex" style="background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%) !important; font-size: 0.65rem;">
                    <i class="bi bi-crown-fill me-1 text-dark"></i> LEADER
                </span>
                <span class="rank-badge-1 mb-2 mt-2">1</span>
                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: {{ $rankings[0]['group']->color }};"></span>
                    <h5 class="fw-extrabold text-dark mb-0 text-truncate" style="font-size: clamp(0.8rem, 3vw, 1.2rem); max-width: 100px;">{{ $rankings[0]['group']->name }}</h5>
                </div>
                <div class="fw-extrabold mb-1" style="color: var(--fest-crimson); font-size: clamp(1.3rem, 5vw, 2rem);">{{ $rankings[0]['total_marks'] }} <small class="fs-xs text-muted">Pts</small></div>
                <div class="d-flex justify-content-center gap-1 gap-md-2 mt-1 fs-xs fw-bold flex-wrap">
                    <span class="text-warning"><i class="bi bi-award-fill"></i> {{ $rankings[0]['first_prizes'] }}</span>
                    <span class="text-secondary"><i class="bi bi-award-fill"></i> {{ $rankings[0]['second_prizes'] }}</span>
                    <span class="text-danger"><i class="bi bi-award-fill"></i> {{ $rankings[0]['third_prizes'] }}</span>
                </div>
            </div>
        </div>

        <!-- 3rd Place -->
        <div class="col-4 col-md-4 order-3 order-md-3 animate-slide-up" style="animation-delay: 0.2s;">
            <div class="fest-card p-2 p-md-3 text-center bg-white border-top border-4 border-danger shadow-sm" style="border-color: #b45309 !important;">
                <span class="rank-badge-3 mb-2">3</span>
                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background-color: {{ $rankings[2]['group']->color }};"></span>
                    <h6 class="fw-bold mb-0 text-truncate" style="font-size: clamp(0.7rem, 2.5vw, 1rem); max-width: 90px;">{{ $rankings[2]['group']->name }}</h6>
                </div>
                <div class="fw-extrabold text-dark" style="font-size: clamp(1.1rem, 4vw, 1.75rem);">{{ $rankings[2]['total_marks'] }} <small class="fs-xs text-muted">Pts</small></div>
                <div class="d-flex justify-content-center gap-1 mt-1 fs-xs text-muted">
                    <span><i class="bi bi-award-fill text-warning"></i> {{ $rankings[2]['first_prizes'] }}</span>
                    <span><i class="bi bi-award-fill text-secondary"></i> {{ $rankings[2]['second_prizes'] }}</span>
                    <span><i class="bi bi-award-fill text-danger"></i> {{ $rankings[2]['third_prizes'] }}</span>
                </div>
            </div>
        </div>

    </div>
@endif

<!-- Full Table — mobile-card layout on xs, table on sm+ -->
<div class="fest-card overflow-hidden">
    <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="fw-bold mb-0 text-dark">{{ $title }}</h6>
        <span class="badge bg-white text-muted border">{{ count($rankings) }} Groups</span>
    </div>

    @if(empty($rankings) || count($rankings) === 0)
        <div class="p-4 text-center text-muted">
            No rankings available for this section yet.
        </div>
    @else
        {{-- Mobile cards (xs only) --}}
        <div class="d-sm-none">
            @foreach($rankings as $item)
                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom {{ $item['rank'] === 1 ? 'bg-warning-subtle' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        @if($item['rank'] === 1)
                            <span class="badge bg-warning text-dark px-2 py-1 shadow-sm"><i class="bi bi-trophy-fill"></i></span>
                        @elseif($item['rank'] === 2)
                            <span class="badge bg-secondary text-white px-2 py-1">2</span>
                        @elseif($item['rank'] === 3)
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">3</span>
                        @else
                            <span class="text-muted fw-bold small">#{{ $item['rank'] }}</span>
                        @endif
                        <span style="width: 10px; height: 10px; border-radius: 3px; background-color: {{ $item['group']->color }}; display:inline-block;"></span>
                        <span class="fw-bold text-dark small">{{ $item['group']->name }}</span>
                    </div>
                    <div class="text-end">
                        <span class="fw-extrabold" style="color: var(--fest-crimson); font-size: 1rem;">{{ $item['total_marks'] }}</span>
                        <span class="text-muted" style="font-size: 0.75rem;">pts</span>
                        <div class="fs-xs text-muted">
                            <i class="bi bi-award-fill text-warning"></i>{{ $item['first_prizes'] }}
                            <i class="bi bi-award-fill text-secondary ms-1"></i>{{ $item['second_prizes'] }}
                            <i class="bi bi-award-fill text-danger ms-1"></i>{{ $item['third_prizes'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Full table (sm+) --}}
        <div class="table-responsive mb-0 d-none d-sm-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase text-muted">
                    <tr>
                        <th style="width: 70px;" class="text-center">Rank</th>
                        <th>Participating Group</th>
                        <th class="text-center" style="width: 80px;">1st</th>
                        <th class="text-center" style="width: 80px;">2nd</th>
                        <th class="text-center" style="width: 80px;">3rd</th>
                        <th class="text-end pe-4" style="width: 120px;">Total Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rankings as $item)
                        <tr class="{{ $item['rank'] === 1 ? 'table-warning-subtle fw-semibold' : '' }}" style="transition: all 0.2s ease;">
                            <td class="text-center">
                                @if($item['rank'] === 1)
                                    <span class="badge bg-warning text-dark px-2 py-1 fs-6 shadow-sm"><i class="bi bi-trophy-fill me-1"></i> 1</span>
                                @elseif($item['rank'] === 2)
                                    <span class="badge bg-secondary text-white px-2 py-1 fs-6">2</span>
                                @elseif($item['rank'] === 3)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-6">3</span>
                                @else
                                    <span class="text-muted fw-bold">#{{ $item['rank'] }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span style="width: 14px; height: 14px; border-radius: 4px; background-color: {{ $item['group']->color }};"></span>
                                    <div>
                                        <span class="fw-bold text-dark">{{ $item['group']->name }}</span>
                                        <small class="text-muted d-block fs-xs">{{ $item['group']->code }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">{{ $item['first_prizes'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1">{{ $item['second_prizes'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger-subtle text-danger px-2 py-1">{{ $item['third_prizes'] }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <span class="fs-5 fw-extrabold text-crimson" style="color: var(--fest-crimson) !important;">{{ $item['total_marks'] }}</span>
                                <small class="text-muted">pts</small>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
