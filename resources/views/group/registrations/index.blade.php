@extends('layouts.group')

@section('title', 'Event Registrations â€” ' . $group->name)

@section('content')
    <div class="container py-4">
        <!-- Header with Action -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">Event & Competition Registrations</h2>
                <p class="text-muted small mb-0">{{ $group->name }} ({{ $group->code }}) &bull; Register and manage your
                    participants across festival events.</p>
            </div>

            @if($isRegistrationOpen)
                <button type="button" class="btn btn-teal text-white shadow-sm rounded-pill px-4 py-2"
                    style="background-color: #9b111e;" data-bs-toggle="modal" data-bs-target="#registerEventModal">
                    <i class="bi bi-plus-lg me-1"></i> Register for Event
                </button>
            @else
                <span class="badge bg-secondary-subtle text-secondary border px-3 py-2 rounded-pill fs-6">
                    <i class="bi bi-lock-fill me-1"></i> Registration Locked by Admin
                </span>
            @endif
        </div>

        <!-- Registration Status Notice -->
        @if($isRegistrationOpen)
            <div
                class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                    <div>
                        <strong class="d-block text-dark">Event Registration is Currently OPEN</strong>
                        <span class="small text-muted">You can enroll your members for competitions. Each individual or group
                            item has a participant limit set by the festival rules.</span>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-success rounded-pill px-3" data-bs-toggle="modal"
                    data-bs-target="#registerEventModal">
                    <i class="bi bi-plus-circle me-1"></i> Add Registration
                </button>
            </div>
        @else
            <div class="alert alert-warning border-0 shadow-sm rounded-3 d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
                <div>
                    <strong class="d-block text-dark">Event Registration is CLOSED</strong>
                    <span class="small text-muted">Festival administrators have locked event entries. You can review your
                        registered participants below, but changes and new entries are not permitted.</span>
                </div>
            </div>
        @endif

        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card group-card border-0 shadow-sm p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Events Registered</div>
                            <h3 class="fw-bold mb-0 mt-1" style="color: var(--group-color);">{{ $totalRegisteredEvents }}
                            </h3>
                        </div>
                        <div class="p-3 rounded-circle bg-light fs-3">
                            <i class="bi bi-calendar2-check text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card group-card border-0 shadow-sm p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Total Participants Registered</div>
                            <h3 class="fw-bold mb-0 mt-1" style="color: var(--group-color);">
                                {{ $totalRegisteredParticipants }}</h3>
                        </div>
                        <div class="p-3 rounded-circle bg-light fs-3">
                            <i class="bi bi-people text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card group-card border-0 shadow-sm p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Registration Portal</div>
                            <h4 class="fw-bold mb-0 mt-1">
                                @if($isRegistrationOpen)
                                    <span
                                        class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 fs-6">
                                        <i class="bi bi-unlock-fill me-1"></i> OPEN
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 fs-6">
                                        <i class="bi bi-lock-fill me-1"></i> LOCKED
                                    </span>
                                @endif
                            </h4>
                        </div>
                        <div class="p-3 rounded-circle bg-light fs-3">
                            <i
                                class="bi {{ $isRegistrationOpen ? 'bi-check2-circle text-success' : 'bi-lock text-danger' }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="card group-card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="{{ route('group.registrations.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i
                                    class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                placeholder="Search participant name, UID, or event..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
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
                            <option value="">All Types...</option>
                            <option value="stage" {{ request('stage_type') === 'stage' ? 'selected' : '' }}>Stage</option>
                            <option value="non_stage" {{ request('stage_type') === 'non_stage' ? 'selected' : '' }}>Non-Stage
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-secondary w-100">Filter</button>
                        @if(request()->hasAny(['search', 'section_id', 'stage_type']))
                            <a href="{{ route('group.registrations.index') }}" class="btn btn-light"><i
                                    class="bi bi-x-lg"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Registrations Table -->
        <div class="card group-card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Program / Event</th>
                                <th>Section & Stage</th>
                                <th>Participant (Member)</th>
                                <th>Chest No</th>
                                <th>Class</th>
                                <th>Registered At</th>
                                @if($isRegistrationOpen)
                                    <th class="text-end pe-4">Actions</th>
                                @endif
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
                                        <small class="text-muted">{{ $reg->created_at->format('M d, Y h:i A') }}</small>
                                    </td>
                                    @if($isRegistrationOpen)
                                        <td class="text-end pe-4">
                                            <form action="{{ route('group.registrations.destroy', $reg) }}" method="POST"
                                                onsubmit="return confirm('Cancel registration of {{ $reg->member->name }} for {{ $reg->program->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                    <i class="bi bi-x-circle me-1"></i> Cancel
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isRegistrationOpen ? 7 : 6 }}" class="text-center py-5 text-muted">
                                        <i class="bi bi-clipboard-x display-4 mb-3 d-block text-secondary"></i>
                                        <h5>No Registered Events Yet</h5>
                                        <p class="small">Your group has not registered any participants for competitions yet.
                                        </p>
                                        @if($isRegistrationOpen)
                                            <button type="button" class="btn btn-teal text-white rounded-pill px-4"
                                                style="background-color: #9b111e;" data-bs-toggle="modal"
                                                data-bs-target="#registerEventModal">
                                                <i class="bi bi-plus-lg me-1"></i> Register for Event Now
                                            </button>
                                        @endif
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

    @if($isRegistrationOpen)
        <!-- Register Event Modal -->
        <div class="modal fade" id="registerEventModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('group.registrations.store') }}" method="POST" id="groupRegForm">
                    @csrf
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">
                                <i class="bi bi-calendar-plus text-teal me-2" style="color: #9b111e;"></i> Register for
                                Competition
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Step 1: Select Program -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Competition Program <span
                                        class="text-danger">*</span></label>
                                <select name="program_id" id="modalProgramSelect" class="form-select" required>
                                    <option value="">Choose a Program...</option>
                                    @foreach($availablePrograms as $prg)
                                        <option value="{{ $prg->id }}" data-section="{{ $prg->section->name }}"
                                            data-type="{{ $prg->type }}" data-max="{{ $prg->max_participants }}">
                                            {{ $prg->name }} ({{ $prg->code }}) &bull; {{ $prg->section->name }} &bull;
                                            {{ $prg->stage_label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Program Info Banner -->
                            <div id="modalProgramInfo" class="alert alert-light border rounded-3 d-none mb-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <div>
                                        <h6 class="fw-bold mb-1" id="mInfoName">Event Name</h6>
                                        <div class="small text-muted">
                                            Section: <strong id="mInfoSection">General</strong> &bull;
                                            Type: <span id="mInfoType" class="badge bg-secondary">Individual</span> &bull;
                                            Max Allowed: <strong id="mInfoMax">1</strong>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-teal text-white p-2" style="background-color: #9b111e;"
                                            id="mSlotsBadge">
                                            Remaining Slots: 1
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Member Selection -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Participant(s) From Your Group <span
                                        class="text-danger">*</span></label>

                                <div
                                    class="alert alert-light border border-danger-subtle rounded-3 py-2 px-3 small mb-2 d-flex align-items-center gap-2">
                                    <i class="bi bi-info-circle-fill text-danger"></i>
                                    <span><strong>Chest Number Required:</strong> A Chest Number is mandatory to register for
                                        any event. Members without a Chest Number cannot be selected.</span>
                                </div>

                                <div id="modalMembersLoading" class="text-center py-4 d-none">
                                    <div class="spinner-border spinner-border-sm text-teal" style="color: #9b111e;"
                                        role="status"></div>
                                    <span class="ms-2 small text-muted">Loading your group's eligible members...</span>
                                </div>

                                <div id="modalMembersContainer" class="border rounded-3 p-3 bg-light"
                                    style="max-height: 280px; overflow-y: auto;">
                                    <div class="text-muted text-center py-4 small">
                                        <i class="bi bi-arrow-up-circle display-6 d-block mb-2 text-secondary"></i>
                                        Please choose a program above to see eligible group members.
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Optional Notes -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Notes / Chest No (Optional)</label>
                                <input type="text" name="notes" class="form-control" placeholder="e.g. Chest No: 12">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="modalSubmitBtn" class="btn btn-teal text-white px-4"
                                style="background-color: #9b111e;" disabled>
                                <i class="bi bi-check-lg me-1"></i> Confirm Registration
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    @if($isRegistrationOpen)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const programSelect = document.getElementById('modalProgramSelect');
                const programInfo = document.getElementById('modalProgramInfo');
                const membersLoading = document.getElementById('modalMembersLoading');
                const membersContainer = document.getElementById('modalMembersContainer');
                const submitBtn = document.getElementById('modalSubmitBtn');
                let remainingSlots = 0;

                programSelect.addEventListener('change', function () {
                    const programId = this.value;
                    if (!programId) {
                        programInfo.classList.add('d-none');
                        membersContainer.innerHTML = '<div class="text-muted text-center py-4 small"><i class="bi bi-arrow-up-circle display-6 d-block mb-2 text-secondary"></i>Please choose a program above to see eligible group members.</div>';
                        submitBtn.disabled = true;
                        return;
                    }

                    membersLoading.classList.remove('d-none');
                    membersContainer.classList.add('d-none');

                    fetch(`{{ url('group/registrations/eligible-members') }}/${programId}`)
                        .then(res => res.json())
                        .then(data => {
                            membersLoading.classList.add('d-none');
                            membersContainer.classList.remove('d-none');

                            if (!data.success) {
                                membersContainer.innerHTML = '<div class="alert alert-warning mb-0 small">Failed to load eligible members.</div>';
                                submitBtn.disabled = true;
                                return;
                            }

                            // Show program info
                            programInfo.classList.remove('d-none');
                            document.getElementById('mInfoName').textContent = data.program.name + ' (' + data.program.code + ')';
                            document.getElementById('mInfoSection').textContent = data.program.section_name;
                            document.getElementById('mInfoType').textContent = data.program.type.toUpperCase();
                            document.getElementById('mInfoMax').textContent = data.program.max_participants;

                            remainingSlots = data.remaining_slots;
                            document.getElementById('mSlotsBadge').textContent = `Remaining Slots: ${remainingSlots} of ${data.program.max_participants}`;

                            if (remainingSlots <= 0) {
                                membersContainer.innerHTML = `<div class="alert alert-warning mb-0 small text-center"><i class="bi bi-exclamation-triangle-fill me-1"></i> Your group has already registered the maximum allowed participants (${data.program.max_participants}) for this program.</div>`;
                                submitBtn.disabled = true;
                                return;
                            }

                            if (data.members.length === 0) {
                                membersContainer.innerHTML = `<div class="alert alert-secondary mb-0 small text-center"><i class="bi bi-person-x me-1"></i> No registered members found in your group for section: <strong>${data.program.section_name}</strong>. Add members to this section first from 'Our Members'.</div>`;
                                submitBtn.disabled = true;
                                return;
                            }

                            let html = '<div class="list-group list-group-flush">';
                            data.members.forEach(m => {
                                const hasChest = m.has_chest_number && m.chest_number;
                                const disabled = (m.is_registered || !hasChest) ? 'disabled' : '';

                                let badgeText = '';
                                if (m.is_registered) {
                                    badgeText = '<span class="badge bg-secondary ms-auto">Already Enrolled</span>';
                                } else if (!hasChest) {
                                    badgeText = '<span class="badge bg-danger ms-auto"><i class="bi bi-exclamation-triangle-fill me-1"></i>Chest No Required</span>';
                                }

                                const chestBadge = hasChest
                                    ? `<span class="badge bg-danger-subtle text-danger border border-danger-subtle font-monospace fw-bold me-1">#${m.chest_number}</span>`
                                    : `<span class="badge bg-warning-subtle text-danger border border-warning-subtle me-1"><i class="bi bi-x-circle me-1"></i>No Chest No</span>`;

                                const sectionBadge = `<span class="badge bg-light text-dark border ms-2">${m.section_name} &bull; ${m.class_year}</span>`;

                                html += `
                                <label class="list-group-item d-flex align-items-center gap-3 py-2 ${(m.is_registered || !hasChest) ? 'bg-light text-muted' : ''}">
                                    <input class="form-check-input modal-member-checkbox flex-shrink-0" type="checkbox" name="member_ids[]" value="${m.id}" ${disabled}>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark">${m.name} ${sectionBadge}</div>
                                        <div class="small mt-1 d-flex align-items-center gap-2">
                                            ${chestBadge}
                                            <span class="text-muted font-monospace"><i class="bi bi-upc-scan me-1"></i>${m.uid}</span>
                                        </div>
                                    </div>
                                    ${badgeText}
                                </label>
                            `;
                            });
                            html += '</div>';

                            membersContainer.innerHTML = html;
                            updateSubmitState();

                            // Limit selection to remaining slots
                            const checkboxes = membersContainer.querySelectorAll('.modal-member-checkbox:not(:disabled)');
                            checkboxes.forEach(cb => {
                                cb.addEventListener('change', function () {
                                    const checkedCount = membersContainer.querySelectorAll('.modal-member-checkbox:checked').length;
                                    if (checkedCount > remainingSlots) {
                                        this.checked = false;
                                        alert(`You can only select up to ${remainingSlots} participant(s) for this program.`);
                                    }
                                    updateSubmitState();
                                });
                            });
                        })
                        .catch(err => {
                            membersLoading.classList.add('d-none');
                            membersContainer.classList.remove('d-none');
                            membersContainer.innerHTML = '<div class="alert alert-danger mb-0 small">Error fetching members. Please try again.</div>';
                            submitBtn.disabled = true;
                        });
                });

                function updateSubmitState() {
                    const checkedCount = membersContainer.querySelectorAll('.modal-member-checkbox:checked').length;
                    submitBtn.disabled = checkedCount === 0;
                }
            });
        </script>
    @endif
@endpush