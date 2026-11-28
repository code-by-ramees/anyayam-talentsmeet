@extends('layouts.admin')

@section('title', 'Enter Competition Result')

@section('content')
    <div class="container-fluid px-lg-4">
        <div class="mb-4">
            <a href="{{ route('admin.results.index') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Back to Results
            </a>
            <h2 class="h4 fw-bold mt-2 mb-1">Enter Competition Result</h2>
            <p class="text-muted small mb-0">Select a program and assign 1st, 2nd, and 3rd prize winners from registered participants. Use the <i class="bi bi-plus-circle-fill text-danger"></i> button to add joint/tied winners.</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card admin-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.results.store') }}" method="POST" id="resultForm">
                            @csrf

                            <!-- Section Filter & Program Selection -->
                            <div class="row g-3 mb-4 pb-3 border-bottom">
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-funnel-fill text-teal me-1" style="color: #9b111e;"></i> Filter by Section
                                    </label>
                                    <select id="sectionFilter" class="form-select">
                                        <option value="">All Sections (Show All Programs)</option>
                                        @foreach($sections as $sec)
                                            <option value="{{ $sec->id }}" {{ (isset($selectedProgram) && $selectedProgram->section_id == $sec->id) ? 'selected' : '' }}>
                                                {{ $sec->name }} ({{ $sec->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Filter competition programs by section.</small>
                                </div>

                                <div class="col-md-7">
                                    <label class="form-label fw-semibold">Competition Program <span class="text-danger">*</span></label>
                                    <select name="program_id" id="programSelect" class="form-select" required>
                                        <option value="">Select Program...</option>
                                        @foreach($programs as $prog)
                                            <option value="{{ $prog->id }}"
                                                data-section-id="{{ $prog->section_id }}"
                                                data-category-id="{{ $prog->category_id }}"
                                                data-category-name="{{ $prog->category->name ?? '' }}"
                                                data-section="{{ $prog->section->name ?? '' }}"
                                                data-type="{{ $prog->type }}"
                                                data-m-first="{{ $prog->category->first_marks ?? 0 }}"
                                                data-m-second="{{ $prog->category->second_marks ?? 0 }}"
                                                data-m-third="{{ $prog->category->third_marks ?? 0 }}"
                                                {{ (old('program_id') == $prog->id || (isset($selectedProgram) && $selectedProgram->id == $prog->id)) ? 'selected' : '' }}>
                                                {{ $prog->name }} ({{ $prog->code }}) &bull; {{ $prog->section->name ?? '' }} [{{ $prog->category->name ?? '' }}]
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="programDetailsBadge" class="mt-2 small text-muted"></div>
                                </div>
                            </div>

                            <!-- Loading Indicator -->
                            <div id="participantsLoading" class="text-center py-4 d-none">
                                <div class="spinner-border spinner-border-sm text-danger" role="status"></div>
                                <span class="ms-2 small text-muted">Loading registered participants for this program...</span>
                            </div>

                            <!-- Prompt before selecting program -->
                            <div id="selectProgramPrompt" class="alert alert-light border text-center py-4 mb-4">
                                <i class="bi bi-trophy display-6 d-block mb-2 text-muted"></i>
                                <span class="text-muted small">Please select a <strong>Competition Program</strong> above to load registered participants and assign 1st, 2nd, and 3rd prize winners.</span>
                            </div>

                            <!-- No registered participants notice -->
                            <div id="noRegisteredAlert" class="alert alert-warning border-warning-subtle d-none mb-4">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <span>No registered participants found for this program. Register participants first from Event Registrations.</span>
                            </div>

                            <!-- 1st, 2nd, 3rd Position Blocks -->
                            <div id="positionsContainer" class="d-none">
                                @php
                                    $positionsConfig = [
                                        'first' => [
                                            'label' => '1st Prize',
                                            'badge' => 'bg-warning text-dark border border-warning',
                                            'border' => 'border-warning-subtle',
                                            'icon' => 'bi-trophy-fill text-warning',
                                        ],
                                        'second' => [
                                            'label' => '2nd Prize',
                                            'badge' => 'bg-secondary text-white',
                                            'border' => 'border-secondary-subtle',
                                            'icon' => 'bi-award-fill text-secondary',
                                        ],
                                        'third' => [
                                            'label' => '3rd Prize',
                                            'badge' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                            'border' => 'border-danger-subtle',
                                            'icon' => 'bi-award text-danger',
                                        ],
                                    ];
                                @endphp

                                @foreach($positionsConfig as $posKey => $posInfo)
                                    <div class="card border {{ $posInfo['border'] }} rounded-3 mb-3 shadow-xs position-card" data-position="{{ $posKey }}">
                                        <div class="card-header bg-light d-flex align-items-center justify-content-between py-2 px-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi {{ $posInfo['icon'] }} fs-5"></i>
                                                <span class="fw-bold text-dark">{{ $posInfo['label'] }}</span>
                                                <span class="badge {{ $posInfo['badge'] }} position-default-marks" id="defaultMarks_{{ $posKey }}">0 pts</span>
                                            </div>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger rounded-pill px-3 add-winner-btn"
                                                data-position="{{ $posKey }}"
                                                title="Add another {{ $posInfo['label'] }} winner">
                                                <i class="bi bi-plus-lg me-1"></i> Add Participant
                                            </button>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="winners-rows d-flex flex-column gap-2" id="rows_{{ $posKey }}">
                                                <!-- Dynamic rows injected by JS -->
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Publish Toggle -->
                            <div class="mb-4 mt-3">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishCheck" {{ old('is_published', '1') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold text-dark" for="publishCheck">
                                            Publish Immediately
                                        </label>
                                    </div>
                                    <div class="small text-muted ps-4">
                                        When enabled, these results immediately impact group points and display on live leaderboards. Uncheck to save as drafts.
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                <a href="{{ route('admin.results.index') }}" class="btn btn-light px-4">Cancel</a>
                                <button type="submit" id="submitBtn" class="btn btn-teal text-white px-5" style="background-color: #9b111e;" disabled>
                                    <i class="bi bi-check-lg me-1"></i> Save Results
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
                        <li class="mb-2"><strong>Registered Only:</strong> Only students who have registered for this program appear in the participant dropdown. Register them first from <strong>Event Registrations</strong> if they're missing.</li>
                        <li class="mb-2"><strong>Auto-Detected Group:</strong> Each participant's group/house is automatically linked when you select them.</li>
                        <li class="mb-2"><strong>Tied / Joint Winners:</strong> Click the <strong><i class="bi bi-plus-circle"></i> Add Participant</strong> button on 1st, 2nd, or 3rd to record multiple winners for the same position.</li>
                        <li class="mb-2"><strong>Automatic Mark Calculation:</strong> Marks are auto-filled from Category rules and can be adjusted if needed.</li>
                        <li class="mb-2"><strong>Draft Security:</strong> Draft results do not affect group scores until published.</li>
                    </ul>

                    <div id="existingResultsWrapper" class="{{ (isset($selectedProgram) && $selectedProgram->results->isNotEmpty()) ? '' : 'd-none' }}">
                        <h6 class="fw-bold mb-2">Existing Results for this Program</h6>
                        <div class="list-group list-group-flush bg-white rounded-3 border" id="existingResultsList">
                            @if(isset($selectedProgram) && $selectedProgram->results->isNotEmpty())
                                @foreach($selectedProgram->results as $r)
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3 small">
                                        <div>
                                            <span class="badge {{ $r->getPositionBadgeClass() }} me-1">{{ $r->getPositionLabel() }}</span>
                                            <span class="fw-semibold">{{ $r->group->name ?? '' }}</span>
                                            <div class="text-muted small">{{ $r->participant_name }}</div>
                                        </div>
                                        <span class="badge bg-light text-dark border">{{ $r->marks }} pts</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sectionFilter = document.getElementById('sectionFilter');
    const programSelect = document.getElementById('programSelect');
    const detailsBadge = document.getElementById('programDetailsBadge');
    const participantsLoading = document.getElementById('participantsLoading');
    const selectProgramPrompt = document.getElementById('selectProgramPrompt');
    const positionsContainer = document.getElementById('positionsContainer');
    const submitBtn = document.getElementById('submitBtn');
    const existingResultsWrapper = document.getElementById('existingResultsWrapper');
    const existingResultsList = document.getElementById('existingResultsList');

    const participantsUrlTemplate = "{{ route('admin.results.program-participants', ['program' => '__PROGRAM_ID__']) }}";

    // Store original program options for section filtering
    const allProgramOptions = Array.from(programSelect.options).map(opt => ({
        value: opt.value,
        text: opt.text,
        sectionId: opt.getAttribute('data-section-id'),
        catId: opt.getAttribute('data-category-id'),
        catName: opt.getAttribute('data-category-name'),
        secName: opt.getAttribute('data-section'),
        progType: opt.getAttribute('data-type'),
        mFirst: opt.getAttribute('data-m-first'),
        mSecond: opt.getAttribute('data-m-second'),
        mThird: opt.getAttribute('data-m-third'),
        selected: opt.selected
    }));

    let currentData = null;
    let rowCounters = { first: 0, second: 0, third: 0 };

    function filterProgramsBySection() {
        const selectedSecId = sectionFilter.value;
        const currentSelectedProgramId = programSelect.value;

        programSelect.innerHTML = '<option value="">Select Program...</option>';

        allProgramOptions.forEach(optData => {
            if (!optData.value) return;
            if (!selectedSecId || optData.sectionId == selectedSecId) {
                const opt = document.createElement('option');
                opt.value = optData.value;
                opt.text = optData.text;
                opt.setAttribute('data-section-id', optData.sectionId);
                opt.setAttribute('data-category-id', optData.catId);
                opt.setAttribute('data-category-name', optData.catName);
                opt.setAttribute('data-section', optData.secName);
                opt.setAttribute('data-type', optData.progType);
                opt.setAttribute('data-m-first', optData.mFirst);
                opt.setAttribute('data-m-second', optData.mSecond);
                opt.setAttribute('data-m-third', optData.mThird);

                if (optData.value === currentSelectedProgramId) {
                    opt.selected = true;
                }
                programSelect.appendChild(opt);
            }
        });

        loadProgramParticipants();
    }

    function getDefaultMarksForPosition(pos) {
        if (!currentData || !currentData.program) return 0;
        if (pos === 'first') return currentData.program.first_marks;
        if (pos === 'second') return currentData.program.second_marks;
        if (pos === 'third') return currentData.program.third_marks;
        return 0;
    }

    function buildParticipantSelectOptions(selectEl) {
        selectEl.innerHTML = '';

        if (!currentData) return;

        if (!currentData.registered || currentData.registered.length === 0) {
            const noOpt = document.createElement('option');
            noOpt.value = '';
            noOpt.text = '⚠ No registered participants — register participants first';
            noOpt.disabled = true;
            selectEl.appendChild(noOpt);
            return;
        }

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.text = '-- Select Registered Participant --';
        selectEl.appendChild(placeholder);

        // Registered Participants only
        const regGroup = document.createElement('optgroup');
        regGroup.label = '★ Registered Participants';
        currentData.registered.forEach(m => {
            const chestText = m.chest_number ? `[#${m.chest_number}] ` : '';
            const opt = document.createElement('option');
            opt.value = `member_${m.member_id}`;
            opt.text = `${chestText}${m.name} (${m.uid}) — ${m.group_name}`;
            opt.setAttribute('data-member-id', m.member_id ?? '');
            opt.setAttribute('data-group-id', m.group_id ?? '');
            opt.setAttribute('data-group-name', m.group_name ?? '');
            opt.setAttribute('data-participant-name', m.participant_name);
            regGroup.appendChild(opt);
        });
        selectEl.appendChild(regGroup);
    }

    function createWinnerRow(pos, isFirstRow = false) {
        const idx = rowCounters[pos]++;
        const defaultMarks = getDefaultMarksForPosition(pos);
        const container = document.getElementById(`rows_${pos}`);

        const row = document.createElement('div');
        row.className = 'winner-row border rounded-3 p-2 bg-white';
        row.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <select class="form-select form-select-sm participant-select" name="winners[${pos}][${idx}][selection]">
                    </select>
                    <input type="hidden" class="hidden-member-id" name="winners[${pos}][${idx}][member_id]" value="">
                    <input type="hidden" class="hidden-group-id" name="winners[${pos}][${idx}][group_id]" value="">
                    <input type="hidden" class="hidden-participant-name" name="winners[${pos}][${idx}][participant_name]" value="">
                    <div class="group-badge-preview small text-muted mt-1 d-none">
                        <i class="bi bi-people-fill me-1"></i>Group: <strong class="group-name-text"></strong>
                    </div>
                </div>
                <div class="col-md-2 col-5">
                    <div class="input-group input-group-sm">
                        <input type="number" name="winners[${pos}][${idx}][marks]" class="form-control fw-bold text-success marks-input" value="${defaultMarks}" min="0" placeholder="Pts">
                        <span class="input-group-text">pts</span>
                    </div>
                </div>
                <div class="col-md-3 col-5">
                    <input type="text" name="winners[${pos}][${idx}][remarks]" class="form-control form-control-sm" placeholder="Remarks (optional)">
                </div>
                <div class="col-md-1 col-2 text-end">
                    ${isFirstRow
                        ? `<button type="button" class="btn btn-sm btn-outline-secondary clear-row-btn" title="Clear selection"><i class="bi bi-x-lg"></i></button>`
                        : `<button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" title="Remove winner"><i class="bi bi-trash"></i></button>`
                    }
                </div>
            </div>
        `;

        const selectEl = row.querySelector('.participant-select');
        buildParticipantSelectOptions(selectEl);

        const memberIdEl = row.querySelector('.hidden-member-id');
        const groupIdEl = row.querySelector('.hidden-group-id');
        const participantNameEl = row.querySelector('.hidden-participant-name');
        const groupBadgePreview = row.querySelector('.group-badge-preview');
        const groupNameText = row.querySelector('.group-name-text');

        selectEl.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                memberIdEl.value = opt.getAttribute('data-member-id') || '';
                groupIdEl.value = opt.getAttribute('data-group-id') || '';
                participantNameEl.value = opt.getAttribute('data-participant-name') || '';
                const gName = opt.getAttribute('data-group-name') || '';
                if (gName) {
                    groupNameText.textContent = gName;
                    groupBadgePreview.classList.remove('d-none');
                } else {
                    groupBadgePreview.classList.add('d-none');
                }
            } else {
                memberIdEl.value = '';
                groupIdEl.value = '';
                participantNameEl.value = '';
                groupBadgePreview.classList.add('d-none');
            }
            updateSubmitState();
        });

        const clearBtn = row.querySelector('.clear-row-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                selectEl.value = '';
                selectEl.dispatchEvent(new Event('change'));
            });
        }

        const removeBtn = row.querySelector('.remove-row-btn');
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                row.remove();
                updateSubmitState();
            });
        }

        container.appendChild(row);
    }

    function updateSubmitState() {
        const anySelected = Array.from(document.querySelectorAll('.participant-select')).some(sel => sel.value !== '');
        submitBtn.disabled = !programSelect.value || !anySelected;
    }

    function renderAllPositions() {
        ['first', 'second', 'third'].forEach(pos => {
            rowCounters[pos] = 0;
            const container = document.getElementById(`rows_${pos}`);
            container.innerHTML = '';
            document.getElementById(`defaultMarks_${pos}`).textContent = `${getDefaultMarksForPosition(pos)} pts`;
            createWinnerRow(pos, true);
        });
        updateSubmitState();
    }

    function loadProgramParticipants() {
        const programId = programSelect.value;
        const selectedOpt = programSelect.options[programSelect.selectedIndex];

        if (!programId || !selectedOpt) {
            detailsBadge.innerHTML = '';
            positionsContainer.classList.add('d-none');
            selectProgramPrompt.classList.remove('d-none');
            submitBtn.disabled = true;
            currentData = null;
            return;
        }

        const catName = selectedOpt.getAttribute('data-category-name');
        const secName = selectedOpt.getAttribute('data-section');
        const mFirst = selectedOpt.getAttribute('data-m-first');
        const mSecond = selectedOpt.getAttribute('data-m-second');
        const mThird = selectedOpt.getAttribute('data-m-third');

        detailsBadge.innerHTML = `<span class="badge bg-secondary me-1">${secName}</span> <span class="badge bg-info text-dark">Category: ${catName} (1st: ${mFirst}pts, 2nd: ${mSecond}pts, 3rd: ${mThird}pts)</span>`;

        selectProgramPrompt.classList.add('d-none');
        positionsContainer.classList.add('d-none');
        participantsLoading.classList.remove('d-none');
        submitBtn.disabled = true;

        const url = participantsUrlTemplate.replace('__PROGRAM_ID__', programId);
        fetch(url)
            .then(r => r.json())
            .then(data => {
                participantsLoading.classList.add('d-none');
                if (!data.success) {
                    selectProgramPrompt.classList.remove('d-none');
                    return;
                }

                currentData = data;
                positionsContainer.classList.remove('d-none');
                renderAllPositions();

                // Update existing results sidebar
                if (data.existing_results && data.existing_results.length > 0) {
                    existingResultsWrapper.classList.remove('d-none');
                    existingResultsList.innerHTML = data.existing_results.map(r => `
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3 small">
                            <div>
                                <span class="badge ${r.badge_class} me-1">${r.position_label}</span>
                                <span class="fw-semibold">${r.group_name}</span>
                                <div class="text-muted small">${r.participant_name}</div>
                            </div>
                            <span class="badge bg-light text-dark border">${r.marks} pts</span>
                        </div>
                    `).join('');
                } else {
                    existingResultsWrapper.classList.add('d-none');
                    existingResultsList.innerHTML = '';
                }
            })
            .catch(() => {
                participantsLoading.classList.add('d-none');
                selectProgramPrompt.classList.remove('d-none');
            });
    }

    // Add Winner (+) buttons
    document.querySelectorAll('.add-winner-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const pos = this.getAttribute('data-position');
            if (!currentData) return;
            createWinnerRow(pos, false);
        });
    });

    sectionFilter.addEventListener('change', filterProgramsBySection);
    programSelect.addEventListener('change', loadProgramParticipants);

    if (sectionFilter.value) {
        filterProgramsBySection();
    } else if (programSelect.value) {
        loadProgramParticipants();
    }
});
</script>
@endpush
