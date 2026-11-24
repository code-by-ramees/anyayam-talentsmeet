<?php $__env->startSection('title', 'Register Participants for Event'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-lg-4">
    <div class="mb-4">
        <a href="<?php echo e(route('admin.registrations.index')); ?>" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Back to Registrations
        </a>
        <h2 class="h4 fw-bold mt-2 mb-1">Register Participants for Competition</h2>
        <p class="text-muted small mb-0">Select a group and program to enroll participants.</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card admin-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="<?php echo e(route('admin.registrations.store')); ?>" method="POST" id="registrationForm">
                        <?php echo csrf_field(); ?>

                        <div class="row g-3 mb-4">
                            <!-- Group Selection -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Participating Group / House <span class="text-danger">*</span></label>
                                <select name="group_id" id="groupSelect" class="form-select" required>
                                    <option value="">Select Group...</option>
                                    <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($grp->id); ?>" <?php echo e(old('group_id', $selectedGroupId) == $grp->id ? 'selected' : ''); ?>>
                                            <?php echo e($grp->name); ?> (<?php echo e($grp->code); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Program Selection -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Competition Program <span class="text-danger">*</span></label>
                                <select name="program_id" id="programSelect" class="form-select" required>
                                    <option value="">Select Program...</option>
                                    <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($prg->id); ?>" <?php echo e(old('program_id', $selectedProgramId) == $prg->id ? 'selected' : ''); ?>>
                                            <?php echo e($prg->name); ?> (<?php echo e($prg->code); ?>) &bull; <?php echo e($prg->section->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <!-- Program Details Alert (Dynamic) -->
                        <div id="programDetailsBox" class="alert alert-info border-0 rounded-3 d-none mb-4">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <h6 class="fw-bold mb-1" id="boxProgramName">Program Name</h6>
                                    <div class="small">
                                        Section: <strong id="boxSectionName">General</strong> &bull;
                                        Type: <span id="boxProgramType" class="badge bg-light text-dark border">Individual</span> &bull;
                                        Max Participants per Group: <strong id="boxMaxParticipants">1</strong>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="small text-muted">Already Registered for this Group:</div>
                                    <span class="badge bg-primary fs-6" id="boxRegisteredCount">0</span> / <span id="boxTotalLimit">1</span>
                                </div>
                            </div>
                        </div>

                        <!-- Member Selection Box -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label fw-semibold mb-0">
                                    Select Participant(s) <span class="text-danger">*</span>
                                </label>
                                <small class="text-muted" id="slotsRemainingText">Select a group and program first</small>
                            </div>

                            <div class="alert alert-light border border-danger-subtle rounded-3 py-2 px-3 small mb-2 d-flex align-items-center gap-2">
                                <i class="bi bi-info-circle-fill text-danger"></i>
                                <span><strong>Chest Number Required:</strong> A Chest Number is mandatory for competition entries. Members without a Chest Number cannot be registered.</span>
                            </div>

                            <div id="membersLoading" class="text-center py-4 d-none">
                                <div class="spinner-border spinner-border-sm text-teal" style="color: #9b111e;" role="status"></div>
                                <span class="ms-2 small text-muted">Loading eligible group members...</span>
                            </div>

                            <div id="membersContainer" class="border rounded-3 p-3 bg-light" style="max-height: 320px; overflow-y: auto;">
                                <div class="text-muted text-center py-4 small" id="membersPlaceholder">
                                    <i class="bi bi-people display-6 d-block mb-2 text-secondary"></i>
                                    Please select both a group and a program to view eligible members.
                                </div>
                            </div>
                        </div>

                        <!-- Optional Notes -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Notes / Chest No (Optional)</label>
                            <input type="text" name="notes" class="form-control" placeholder="e.g. Chest No: 14, Team Captain" value="<?php echo e(old('notes')); ?>">
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="<?php echo e(route('admin.registrations.index')); ?>" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" id="submitBtn" class="btn btn-teal text-white px-5" style="background-color: #9b111e;" disabled>
                                <i class="bi bi-check-lg me-1"></i> Register Participant(s)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card admin-card border-0 shadow-sm p-4 bg-light mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i> Registration Rules</h6>
                <ul class="small text-muted ps-3 mb-0">
                    <li class="mb-2"><strong>Section Eligibility:</strong> Participants must belong to the event's section (e.g. Bakalooriyya or Sanaviyya Ulya).</li>
                    <li class="mb-2"><strong>General Events:</strong> Programs marked as <strong>General</strong> allow participants from any section!</li>
                    <li class="mb-2"><strong>Limits:</strong> Individual events usually allow 1 entry per group; team events allow up to the program's max participants limit.</li>
                    <li class="mb-2"><strong>Duplicate Check:</strong> A student cannot be registered more than once for the same competition.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const groupSelect = document.getElementById('groupSelect');
    const programSelect = document.getElementById('programSelect');
    const membersContainer = document.getElementById('membersContainer');
    const membersLoading = document.getElementById('membersLoading');
    const membersPlaceholder = document.getElementById('membersPlaceholder');
    const programDetailsBox = document.getElementById('programDetailsBox');
    const submitBtn = document.getElementById('submitBtn');
    const slotsRemainingText = document.getElementById('slotsRemainingText');

    let maxAllowed = 1;
    let remainingSlots = 1;

    function fetchMembers() {
        const groupId = groupSelect.value;
        const programId = programSelect.value;

        if (!groupId || !programId) {
            programDetailsBox.classList.add('d-none');
            membersContainer.innerHTML = '<div class="text-muted text-center py-4 small"><i class="bi bi-people display-6 d-block mb-2 text-secondary"></i>Please select both a group and a program to view eligible members.</div>';
            submitBtn.disabled = true;
            slotsRemainingText.textContent = 'Select a group and program first';
            return;
        }

        membersLoading.classList.remove('d-none');
        membersContainer.classList.add('d-none');

        fetch(`<?php echo e(route('admin.registrations.eligible-members')); ?>?group_id=${groupId}&program_id=${programId}`)
            .then(res => res.json())
            .then(data => {
                membersLoading.classList.add('d-none');
                membersContainer.classList.remove('d-none');

                if (!data.success) {
                    membersContainer.innerHTML = '<div class="alert alert-warning mb-0 small">Failed to load members.</div>';
                    submitBtn.disabled = true;
                    return;
                }

                // Update Program Details Box
                programDetailsBox.classList.remove('d-none');
                document.getElementById('boxProgramName').textContent = data.program.name + ' (' + data.program.code + ')';
                document.getElementById('boxSectionName').textContent = data.program.section_name;
                document.getElementById('boxProgramType').textContent = data.program.type.toUpperCase();
                document.getElementById('boxMaxParticipants').textContent = data.program.max_participants;
                document.getElementById('boxRegisteredCount').textContent = data.group_registered_count;
                document.getElementById('boxTotalLimit').textContent = data.program.max_participants;

                maxAllowed = data.program.max_participants;
                remainingSlots = data.remaining_slots;

                slotsRemainingText.innerHTML = `Available slots for this group: <strong class="text-teal">${remainingSlots}</strong> of ${maxAllowed}`;

                if (remainingSlots <= 0) {
                    membersContainer.innerHTML = `<div class="alert alert-warning mb-0 small text-center"><i class="bi bi-exclamation-triangle-fill me-1"></i> Maximum participant limit (${maxAllowed}) for this group has already been reached for this program.</div>`;
                    submitBtn.disabled = true;
                    return;
                }

                if (data.members.length === 0) {
                    membersContainer.innerHTML = `<div class="alert alert-secondary mb-0 small text-center"><i class="bi bi-person-x me-1"></i> No eligible members found in this group for section: <strong>${data.program.section_name}</strong>.</div>`;
                    submitBtn.disabled = true;
                    return;
                }

                let html = '<div class="list-group list-group-flush">';
                data.members.forEach(m => {
                    const hasChest = m.has_chest_number && m.chest_number;
                    const disabled = (m.is_registered || !hasChest) ? 'disabled' : '';

                    let badgeText = '';
                    if (m.is_registered) {
                        badgeText = '<span class="badge bg-secondary ms-auto">Already Registered</span>';
                    } else if (!hasChest) {
                        badgeText = '<span class="badge bg-danger ms-auto"><i class="bi bi-exclamation-triangle-fill me-1"></i>Chest No Required</span>';
                    }

                    const chestBadge = hasChest 
                        ? `<span class="badge bg-danger-subtle text-danger border border-danger-subtle font-monospace fw-bold me-1">#${m.chest_number}</span>`
                        : `<span class="badge bg-warning-subtle text-danger border border-warning-subtle me-1"><i class="bi bi-x-circle me-1"></i>No Chest No</span>`;

                    const sectionBadge = `<span class="badge bg-light text-dark border ms-2">${m.section_name} &bull; ${m.class_year}</span>`;

                    html += `
                        <label class="list-group-item d-flex align-items-center gap-3 py-2 ${(m.is_registered || !hasChest) ? 'bg-light text-muted' : ''}">
                            <input class="form-check-input member-checkbox flex-shrink-0" type="checkbox" name="member_ids[]" value="${m.id}" ${disabled}>
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

                // Add listener to checkboxes to prevent selecting more than remaining slots
                const checkboxes = membersContainer.querySelectorAll('.member-checkbox:not(:disabled)');
                checkboxes.forEach(cb => {
                    cb.addEventListener('change', function () {
                        const checkedCount = membersContainer.querySelectorAll('.member-checkbox:checked').length;
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
    }

    function updateSubmitState() {
        const checkedCount = membersContainer.querySelectorAll('.member-checkbox:checked').length;
        submitBtn.disabled = checkedCount === 0;
    }

    groupSelect.addEventListener('change', fetchMembers);
    programSelect.addEventListener('change', fetchMembers);

    // Initial load if old values or query params exist
    if (groupSelect.value && programSelect.value) {
        fetchMembers();
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/admin/registrations/create.blade.php ENDPATH**/ ?>