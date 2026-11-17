@extends('layouts.admin')

@section('title', 'Enter Competition Result')

@section('content')
<div class="container-fluid px-lg-4">
    <div class="mb-4">
        <a href="{{ route('admin.results.index') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Back to Results
        </a>
        <h2 class="h4 fw-bold mt-2 mb-1">Enter Competition Result</h2>
        <p class="text-muted small mb-0">Record position winner for a program. Points are automatically fetched from category settings.</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card admin-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.results.store') }}" method="POST" id="resultForm">
                        @csrf

                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Competition Program <span class="text-danger">*</span></label>
                                <select name="program_id" id="programSelect" class="form-select" required>
                                    <option value="">Select Program...</option>
                                    @foreach($programs as $prog)
                                        <option value="{{ $prog->id }}" 
                                            data-category-id="{{ $prog->category_id }}"
                                            data-category-name="{{ $prog->category->name ?? '' }}"
                                            data-section="{{ $prog->section->name ?? '' }}"
                                            data-m-first="{{ $prog->category->first_marks ?? 0 }}"
                                            data-m-second="{{ $prog->category->second_marks ?? 0 }}"
                                            data-m-third="{{ $prog->category->third_marks ?? 0 }}"
                                            {{ (old('program_id') == $prog->id || (isset($selectedProgram) && $selectedProgram->id == $prog->id)) ? 'selected' : '' }}>
                                            {{ $prog->name }} ({{ $prog->code }}) — {{ $prog->section->name ?? '' }} [{{ $prog->category->name ?? '' }}]
                                        </option>
                                    @endforeach
                                </select>
                                <div id="programDetailsBadge" class="mt-2 small text-muted"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Winning Group <span class="text-danger">*</span></label>
                                <select name="group_id" class="form-select" required>
                                    <option value="">Select Group...</option>
                                    @foreach($groups as $grp)
                                        <option value="{{ $grp->id }}" {{ old('group_id') == $grp->id ? 'selected' : '' }}>
                                            {{ $grp->name }} ({{ $grp->section->name ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Participant / Team Name <span class="text-danger">*</span></label>
                                <input type="text" name="participant_name" class="form-control" placeholder="Student name or team name" value="{{ old('participant_name') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Position Awarded <span class="text-danger">*</span></label>
                                <select name="position" id="positionSelect" class="form-select" required>
                                    <option value="">Select Position...</option>
                                    <option value="first" {{ old('position') === 'first' ? 'selected' : '' }}>First Prize (1st)</option>
                                    <option value="second" {{ old('position') === 'second' ? 'selected' : '' }}>Second Prize (2nd)</option>
                                    <option value="third" {{ old('position') === 'third' ? 'selected' : '' }}>Third Prize (3rd)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Awarded Points / Marks <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="marks" id="marksInput" class="form-control fw-bold fs-5 text-success" value="{{ old('marks') }}" min="0" required>
                                    <span class="input-group-text bg-light">Points</span>
                                </div>
                                <small class="text-muted" id="marksNotice">Auto-calculated based on Category marks.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Remarks (Optional)</label>
                                <input type="text" name="remarks" class="form-control" placeholder="e.g. Outstanding performance, Special jury mention" value="{{ old('remarks') }}">
                            </div>

                            <div class="col-12">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishCheck" {{ old('is_published', '1') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold text-dark" for="publishCheck">
                                            Publish Result Immediately
                                        </label>
                                    </div>
                                    <div class="small text-muted ps-4">
                                        If checked, this result will be published right away to the live public leaderboard and group portals. If unchecked, it will be saved as a draft.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.results.index') }}" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" class="btn btn-teal text-white px-5" style="background-color: #0d9488;">
                                <i class="bi bi-check-lg me-1"></i> Save Result
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card admin-card border-0 shadow-sm p-4 bg-light">
                <h6 class="fw-bold mb-3"><i class="bi bi-shield-check text-success me-1"></i> Result Integrity Rules</h6>
                <ul class="small text-muted ps-3 mb-4">
                    <li class="mb-2"><strong>Duplicate Prevention:</strong> The system prevents multiple entries for the same prize position in a program.</li>
                    <li class="mb-2"><strong>Automatic Mark Calculation:</strong> Marks are automatically populated based on the Category position rules.</li>
                    <li class="mb-2"><strong>Draft Security:</strong> Draft results do not affect group scores until explicitly published by an administrator.</li>
                </ul>

                @if(isset($selectedProgram) && $selectedProgram->results->isNotEmpty())
                    <h6 class="fw-bold mb-2">Existing Results for this Program</h6>
                    <div class="list-group list-group-flush bg-white rounded-3 border">
                        @foreach($selectedProgram->results as $r)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3 small">
                                <div>
                                    <span class="badge {{ $r->getPositionBadgeClass() }} me-1">{{ $r->getPositionLabel() }}</span>
                                    <span class="fw-semibold">{{ $r->group->name ?? '' }}</span>
                                </div>
                                <span class="badge bg-light text-dark border">{{ $r->marks }} pts</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const programSelect = document.getElementById('programSelect');
        const positionSelect = document.getElementById('positionSelect');
        const marksInput = document.getElementById('marksInput');
        const detailsBadge = document.getElementById('programDetailsBadge');
        const marksNotice = document.getElementById('marksNotice');

        function updateMarks() {
            const selectedOpt = programSelect.options[programSelect.selectedIndex];
            if (!selectedOpt || !selectedOpt.value) {
                detailsBadge.innerHTML = '';
                return;
            }

            const catName = selectedOpt.getAttribute('data-category-name');
            const secName = selectedOpt.getAttribute('data-section');
            const mFirst = selectedOpt.getAttribute('data-m-first');
            const mSecond = selectedOpt.getAttribute('data-m-second');
            const mThird = selectedOpt.getAttribute('data-m-third');

            detailsBadge.innerHTML = `<span class="badge bg-secondary me-1">${secName}</span> <span class="badge bg-info text-dark">Category: ${catName} (1st: ${mFirst}pts, 2nd: ${mSecond}pts, 3rd: ${mThird}pts)</span>`;

            const pos = positionSelect.value;
            if (pos === 'first') {
                marksInput.value = mFirst;
                marksNotice.innerText = `Auto-fetched: Category ${catName} 1st prize is ${mFirst} points.`;
            } else if (pos === 'second') {
                marksInput.value = mSecond;
                marksNotice.innerText = `Auto-fetched: Category ${catName} 2nd prize is ${mSecond} points.`;
            } else if (pos === 'third') {
                marksInput.value = mThird;
                marksNotice.innerText = `Auto-fetched: Category ${catName} 3rd prize is ${mThird} points.`;
            }
        }

        programSelect.addEventListener('change', updateMarks);
        positionSelect.addEventListener('change', updateMarks);

        // Run once on load if preselected
        if (programSelect.value) {
            updateMarks();
        }
    });
</script>
@endpush
@endsection
