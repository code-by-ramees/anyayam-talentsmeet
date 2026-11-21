<!-- Podium Display (Top 3) -->
@if(count($rankings) >= 3)
    <div class="row g-3 align-items-end justify-content-center mb-4 pt-3">
        
        <!-- 2nd Place -->
        <div class="col-md-4 col-sm-6 order-2 order-md-1 animate-slide-up" style="animation-delay: 0.1s;">
            <div class="fest-card p-3 text-center bg-white border-top border-4 border-secondary shadow-sm">
                <span class="rank-badge-2 mb-2">2</span>
                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: {{ $rankings[1]['group']->color }};"></span>
                    <h5 class="fw-bold mb-0">{{ $rankings[1]['group']->name }}</h5>
                </div>
                <span class="badge bg-light text-muted border mb-2 font-monospace">{{ $rankings[1]['group']->code }}</span>
                <div class="display-6 fw-extrabold text-dark">{{ $rankings[1]['total_marks'] }} <small class="fs-6 text-muted">Pts</small></div>
                <div class="d-flex justify-content-center gap-2 mt-2 fs-xs text-muted">
                    <span><i class="bi bi-award-fill text-warning"></i> {{ $rankings[1]['first_prizes'] }}</span>
                    <span><i class="bi bi-award-fill text-secondary"></i> {{ $rankings[1]['second_prizes'] }}</span>
                    <span><i class="bi bi-award-fill text-danger"></i> {{ $rankings[1]['third_prizes'] }}</span>
                </div>
            </div>
        </div>

        <!-- 1st Place (Winner Center) -->
        <div class="col-md-4 col-sm-12 order-1 order-md-2 animate-slide-up">
            <div class="fest-card p-4 text-center bg-white border-top border-4 border-warning shadow position-relative" style="transform: scale(1.04); border-color: #f59e0b !important;">
                <span class="position-absolute top-0 start-50 translate-middle badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold shadow-sm" style="background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%) !important;">
                    <i class="bi bi-crown-fill me-1 text-dark"></i> FEST LEADER
                </span>
                <span class="rank-badge-1 mb-2 mt-2">1</span>
                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                    <span style="width: 12px; height: 12px; border-radius: 50%; background-color: {{ $rankings[0]['group']->color }};"></span>
                    <h4 class="fw-extrabold text-dark mb-0">{{ $rankings[0]['group']->name }}</h4>
                </div>
                <span class="badge bg-light text-muted border mb-3 font-monospace">{{ $rankings[0]['group']->code }}</span>
                <div class="display-5 fw-extrabold mb-1" style="color: var(--fest-crimson);">{{ $rankings[0]['total_marks'] }} <small class="fs-6 text-muted">Pts</small></div>
                <div class="d-flex justify-content-center gap-3 mt-2 small fw-bold">
                    <span class="text-warning"><i class="bi bi-award-fill"></i> {{ $rankings[0]['first_prizes'] }} First</span>
                    <span class="text-secondary"><i class="bi bi-award-fill"></i> {{ $rankings[0]['second_prizes'] }} Sec</span>
                    <span class="text-danger"><i class="bi bi-award-fill"></i> {{ $rankings[0]['third_prizes'] }} Third</span>
                </div>
            </div>
        </div>

        <!-- 3rd Place -->
        <div class="col-md-4 col-sm-6 order-3 order-md-3 animate-slide-up" style="animation-delay: 0.2s;">
            <div class="fest-card p-3 text-center bg-white border-top border-4 border-danger shadow-sm" style="border-color: #b45309 !important;">
                <span class="rank-badge-3 mb-2">3</span>
                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: {{ $rankings[2]['group']->color }};"></span>
                    <h5 class="fw-bold mb-0">{{ $rankings[2]['group']->name }}</h5>
                </div>
                <span class="badge bg-light text-muted border mb-2 font-monospace">{{ $rankings[2]['group']->code }}</span>
                <div class="display-6 fw-extrabold text-dark">{{ $rankings[2]['total_marks'] }} <small class="fs-6 text-muted">Pts</small></div>
                <div class="d-flex justify-content-center gap-2 mt-2 fs-xs text-muted">
                    <span><i class="bi bi-award-fill text-warning"></i> {{ $rankings[2]['first_prizes'] }}</span>
                    <span><i class="bi bi-award-fill text-secondary"></i> {{ $rankings[2]['second_prizes'] }}</span>
                    <span><i class="bi bi-award-fill text-danger"></i> {{ $rankings[2]['third_prizes'] }}</span>
                </div>
            </div>
        </div>

    </div>
@endif

<!-- Full Table -->
<div class="fest-card overflow-hidden">
    <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-dark">{{ $title }}</h6>
        <span class="badge bg-white text-muted border">{{ count($rankings) }} Participating Groups</span>
    </div>

    @if(empty($rankings) || count($rankings) === 0)
        <div class="p-4 text-center text-muted">
            No rankings available for this section yet.
        </div>
    @else
        <div class="table-responsive mb-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase text-muted">
                    <tr>
                        <th style="width: 70px;" class="text-center">Rank</th>
                        <th>Participating Group</th>
                        <th class="text-center" style="width: 100px;">1st Prize</th>
                        <th class="text-center" style="width: 100px;">2nd Prize</th>
                        <th class="text-center" style="width: 100px;">3rd Prize</th>
                        <th class="text-end pe-4" style="width: 140px;">Total Marks</th>
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
                                        <small class="text-muted d-block fs-xs">Code: {{ $item['group']->code }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                                    {{ $item['first_prizes'] }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1">
                                    {{ $item['second_prizes'] }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger-subtle text-danger px-2 py-1">
                                    {{ $item['third_prizes'] }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <span class="fs-5 fw-extrabold text-crimson" style="color: var(--fest-crimson) !important;">
                                    {{ $item['total_marks'] }}
                                </span>
                                <small class="text-muted">pts</small>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
