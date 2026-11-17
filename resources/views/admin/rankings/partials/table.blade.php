<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th class="ps-4 text-center" style="width: 70px;">Rank</th>
                <th>Group</th>
                @if($showSection ?? false)
                    <th>Section</th>
                @endif
                <th class="text-center">1st</th>
                <th class="text-center">2nd</th>
                <th class="text-center">3rd</th>
                <th class="text-center">Total Prizes</th>
                <th class="text-end pe-4">Total Points</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rankings as $row)
                <tr class="{{ $loop->iteration === 1 ? 'table-warning bg-opacity-25' : '' }}">
                    <td class="ps-4 text-center">
                        @if($loop->iteration === 1)
                            <span class="badge bg-warning text-dark rounded-circle p-2 fs-6" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                <i class="bi bi-trophy-fill"></i>
                            </span>
                        @elseif($loop->iteration === 2)
                            <span class="badge bg-secondary text-white rounded-circle p-2 fs-6" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                2
                            </span>
                        @elseif($loop->iteration === 3)
                            <span class="badge text-white rounded-circle p-2 fs-6" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background-color: #cd7f32;">
                                3
                            </span>
                        @else
                            <span class="fw-bold text-muted fs-6">#{{ $loop->iteration }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle p-1 d-inline-block" style="width: 14px; height: 14px; background-color: {{ $row['group']->color ?? '#0d9488' }};"></span>
                            <div>
                                <span class="fw-bold text-dark">{{ $row['group']->name }}</span>
                                <span class="badge bg-light text-muted border font-monospace ms-1">{{ $row['group']->code }}</span>
                            </div>
                        </div>
                    </td>
                    @if($showSection ?? false)
                        <td>
                            <span class="badge rounded-pill px-2 py-1 text-white" style="background-color: #0d9488;">
                                {{ $row['group']->section->name ?? 'N/A' }}
                            </span>
                        </td>
                    @endif
                    <td class="text-center">
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                            {{ $row['first_prizes'] }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                            {{ $row['second_prizes'] }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge text-white px-2 py-1" style="background-color: #cd7f32;">
                            {{ $row['third_prizes'] }}
                        </span>
                    </td>
                    <td class="text-center fw-semibold text-muted">
                        {{ $row['total_prizes'] }}
                    </td>
                    <td class="text-end pe-4">
                        <span class="fs-5 fw-bold text-success">
                            {{ $row['total_marks'] }}
                        </span>
                        <small class="text-muted">pts</small>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ ($showSection ?? false) ? 8 : 7 }}" class="text-center py-4 text-muted">
                        No groups or published scores in this category.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
