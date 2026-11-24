<?php $__env->startSection('title', 'Enter Competition Result'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid px-lg-4">
        <div class="mb-4">
            <a href="<?php echo e(route('admin.results.index')); ?>" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Back to Results
            </a>
            <h2 class="h4 fw-bold mt-2 mb-1">Enter Competition Result</h2>
            <p class="text-muted small mb-0">Record position winner for a program. Points are automatically fetched from category settings.</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card admin-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="<?php echo e(route('admin.results.store')); ?>" method="POST" id="resultForm">
                            <?php echo csrf_field(); ?>

                            <!-- Section Filter & Program Row -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-funnel-fill text-teal me-1" style="color: #9b111e;"></i> Filter by Section
                                    </label>
                                    <select id="sectionFilter" class="form-select">
                                        <option value="">All Sections (Show All Programs)</option>
                                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($sec->id); ?>" <?php echo e((isset($selectedProgram) && $selectedProgram->section_id == $sec->id) ? 'selected' : ''); ?>>
                                                <?php echo e($sec->name); ?> (<?php echo e($sec->code); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small class="text-muted">Filter competition programs by section.</small>
                                </div>

                                <div class="col-md-7">
                                    <label class="form-label fw-semibold">Competition Program <span class="text-danger">*</span></label>
                                    <select name="program_id" id="programSelect" class="form-select" required>
                                        <option value="">Select Program...</option>
                                        <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($prog->id); ?>"
                                                data-section-id="<?php echo e($prog->section_id); ?>"
                                                data-category-id="<?php echo e($prog->category_id); ?>"
                                                data-category-name="<?php echo e($prog->category->name ?? ''); ?>"
                                                data-section="<?php echo e($prog->section->name ?? ''); ?>"
                                                data-type="<?php echo e($prog->type); ?>"
                                                data-m-first="<?php echo e($prog->category->first_marks ?? 0); ?>"
                                                data-m-second="<?php echo e($prog->category->second_marks ?? 0); ?>"
                                                data-m-third="<?php echo e($prog->category->third_marks ?? 0); ?>"
                                                <?php echo e((old('program_id') == $prog->id || (isset($selectedProgram) && $selectedProgram->id == $prog->id)) ? 'selected' : ''); ?>>
                                                <?php echo e($prog->name); ?> (<?php echo e($prog->code); ?>) &bull; <?php echo e($prog->section->name ?? ''); ?> [<?php echo e($prog->category->name ?? ''); ?>]
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div id="programDetailsBadge" class="mt-2 small text-muted"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Winning Group <span class="text-danger">*</span></label>
                                    <select name="group_id" id="groupSelect" class="form-select" required>
                                        <option value="">Select Group...</option>
                                        <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($grp->id); ?>" data-name="<?php echo e($grp->name); ?>" <?php echo e(old('group_id') == $grp->id ? 'selected' : ''); ?>>
                                                <?php echo e($grp->name); ?> (<?php echo e($grp->code); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Winning Participant <span class="text-danger">*</span>
                                    </label>
                                    <input type="hidden" name="member_id" id="memberIdInput" value="<?php echo e(old('member_id')); ?>">

                                    <select name="participant_name" id="participantSelect" class="form-select" required disabled>
                                        <option value="">Select Program & Winning Group first...</option>
                                    </select>

                                    <div id="participantLoading" class="small text-muted mt-1 d-none">
                                        <span class="spinner-border spinner-border-sm me-1" role="status"></span> Loading enrolled participants & group members...
                                    </div>
                                    <div id="participantNotice" class="small text-muted mt-1">
                                        Participants are populated automatically once program and group are selected.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Position Awarded <span class="text-danger">*</span></label>
                                    <select name="position" id="positionSelect" class="form-select" required>
                                        <option value="">Select Position...</option>
                                        <option value="first" <?php echo e(old('position') === 'first' ? 'selected' : ''); ?>>First Prize (1st)</option>
                                        <option value="second" <?php echo e(old('position') === 'second' ? 'selected' : ''); ?>>Second Prize (2nd)</option>
                                        <option value="third" <?php echo e(old('position') === 'third' ? 'selected' : ''); ?>>Third Prize (3rd)</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Awarded Points / Marks <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="marks" id="marksInput" class="form-control fw-bold fs-5 text-success"
                                            value="<?php echo e(old('marks')); ?>" min="0" required>
                                        <span class="input-group-text bg-light">Points</span>
                                    </div>
                                    <div class="form-text small text-muted" id="marksNotice">Auto-fetched from Category configuration.</div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Judge Remarks / Notes (Optional)</label>
                                    <input type="text" name="remarks" class="form-control" placeholder="e.g. Outstanding delivery, Score: 94/100" value="<?php echo e(old('remarks')); ?>">
                                </div>

                                <div class="col-12">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishCheck" <?php echo e(old('is_published', '1') == '1' ? 'checked' : ''); ?>>
                                            <label class="form-check-label fw-bold text-dark" for="publishCheck">
                                                Publish Immediately
                                            </label>
                                        </div>
                                        <div class="small text-muted ps-4">
                                            When enabled, this result immediately impacts group points and displays on live leaderboards. Uncheck to save as a draft.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                <a href="<?php echo e(route('admin.results.index')); ?>" class="btn btn-light px-4">Cancel</a>
                                <button type="submit" class="btn btn-teal text-white px-5" style="background-color: #9b111e;">
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
                        <li class="mb-2"><strong>Section Filter:</strong> Filter competition programs by section to quickly find the desired event.</li>
                        <li class="mb-2"><strong>Participant Dropdown:</strong> Select registered participants or group members directly from the dropdown without manual typing.</li>
                        <li class="mb-2"><strong>Automatic Mark Calculation:</strong> Marks are automatically populated based on the Category position rules.</li>
                        <li class="mb-2"><strong>Duplicate Prevention:</strong> Prevents multiple entries for the same prize position in a program.</li>
                        <li class="mb-2"><strong>Draft Security:</strong> Draft results do not affect group scores until explicitly published by an administrator.</li>
                    </ul>

                    <?php if(isset($selectedProgram) && $selectedProgram->results->isNotEmpty()): ?>
                        <h6 class="fw-bold mb-2">Existing Results for this Program</h6>
                        <div class="list-group list-group-flush bg-white rounded-3 border">
                            <?php $__currentLoopData = $selectedProgram->results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3 small">
                                    <div>
                                        <span class="badge <?php echo e($r->getPositionBadgeClass()); ?> me-1"><?php echo e($r->getPositionLabel()); ?></span>
                                        <span class="fw-semibold"><?php echo e($r->group->name ?? ''); ?></span>
                                        <div class="text-muted small"><?php echo e($r->participant_name); ?></div>
                                    </div>
                                    <span class="badge bg-light text-dark border"><?php echo e($r->marks); ?> pts</span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sectionFilter = document.getElementById('sectionFilter');
            const programSelect = document.getElementById('programSelect');
            const groupSelect = document.getElementById('groupSelect');
            const positionSelect = document.getElementById('positionSelect');
            const marksInput = document.getElementById('marksInput');
            const detailsBadge = document.getElementById('programDetailsBadge');
            const marksNotice = document.getElementById('marksNotice');
            const participantSelect = document.getElementById('participantSelect');
            const memberIdInput = document.getElementById('memberIdInput');
            const participantLoading = document.getElementById('participantLoading');
            const participantNotice = document.getElementById('participantNotice');

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

            // 1. Section Filter Handler
            function filterProgramsBySection() {
                const selectedSecId = sectionFilter.value;
                const currentSelectedProgramId = programSelect.value;

                // Rebuild programSelect options
                programSelect.innerHTML = '<option value="">Select Program...</option>';

                allProgramOptions.forEach(optData => {
                    if (!optData.value) return; // Skip placeholder

                    // Show if no section filter or matches section
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

                updateMarks();
                loadParticipants();
            }

            // 2. Marks Auto-Fetch
            function updateMarks() {
                const selectedOpt = programSelect.options[programSelect.selectedIndex];
                if (!selectedOpt || !selectedOpt.value) {
                    detailsBadge.innerHTML = '';
                    marksInput.value = '';
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

            // 3. Dynamic Participant Dropdown Loader
            function loadParticipants() {
                const programId = programSelect.value;
                const groupId = groupSelect.value;

                if (!programId || !groupId) {
                    participantSelect.innerHTML = '<option value="">Select Program & Winning Group first...</option>';
                    participantSelect.disabled = true;
                    memberIdInput.value = '';
                    participantNotice.innerText = 'Select program and group to choose from registered participants or group members.';
                    return;
                }

                const groupOption = groupSelect.options[groupSelect.selectedIndex];
                const groupName = groupOption ? (groupOption.getAttribute('data-name') || groupOption.text) : 'Group';

                participantLoading.classList.remove('d-none');
                participantSelect.disabled = true;

                fetch(`<?php echo e(route('admin.registrations.eligible-members')); ?>?program_id=${programId}&group_id=${groupId}`)
                    .then(r => r.json())
                    .then(data => {
                        participantLoading.classList.add('d-none');
                        participantSelect.disabled = false;

                        if (!data.success || !data.members) {
                            participantSelect.innerHTML = `<option value="Team ${groupName}">Team ${groupName}</option>`;
                            return;
                        }

                        participantSelect.innerHTML = '';

                        const defaultOpt = document.createElement('option');
                        defaultOpt.value = '';
                        defaultOpt.text = '-- Select Winning Participant --';
                        participantSelect.appendChild(defaultOpt);

                        const registered = data.members.filter(m => m.is_registered);
                        const others = data.members.filter(m => !m.is_registered);

                        // 1. Enrolled Participants OptGroup
                        if (registered.length > 0) {
                            const enrolledGroup = document.createElement('optgroup');
                            enrolledGroup.label = '★ Enrolled / Registered Participants for this Event';
                            registered.forEach(m => {
                                const chestLabel = m.chest_number ? `[Chest #${m.chest_number}] ` : '';
                                const opt = document.createElement('option');
                                opt.value = `${m.name} (${m.uid})`;
                                opt.text = `★ ${chestLabel}${m.name} (${m.uid}) — ${m.class_year} [Enrolled]`;
                                opt.setAttribute('data-member-id', m.id);
                                enrolledGroup.appendChild(opt);
                            });
                            participantSelect.appendChild(enrolledGroup);
                        }

                        // 2. Other Group Members OptGroup
                        if (others.length > 0) {
                            const othersGroup = document.createElement('optgroup');
                            othersGroup.label = 'Other Group Members (' + data.program.section_name + ')';
                            others.forEach(m => {
                                const chestLabel = m.chest_number ? `[Chest #${m.chest_number}] ` : '';
                                const opt = document.createElement('option');
                                opt.value = `${m.name} (${m.uid})`;
                                opt.text = `${chestLabel}${m.name} (${m.uid}) — ${m.class_year}`;
                                opt.setAttribute('data-member-id', m.id);
                                othersGroup.appendChild(opt);
                            });
                            participantSelect.appendChild(othersGroup);
                        }

                        // 3. Team / Group Item Option
                        const teamGroup = document.createElement('optgroup');
                        teamGroup.label = 'Team / House Entry';
                        const teamOpt = document.createElement('option');
                        teamOpt.value = `Team ${groupName}`;
                        teamOpt.text = `Team ${groupName} (Group Item)`;
                        teamGroup.appendChild(teamOpt);
                        participantSelect.appendChild(teamGroup);

                        // Auto-select first registered member if exactly one exists
                        if (registered.length === 1) {
                            participantSelect.selectedIndex = 1;
                            memberIdInput.value = registered[0].id;
                            participantNotice.innerHTML = `<span class="text-success"><i class="bi bi-check-circle me-1"></i> Auto-selected enrolled participant: <strong>${registered[0].name}</strong></span>`;
                        } else if (registered.length > 1) {
                            participantNotice.innerHTML = `<span class="text-teal" style="color:#9b111e;"><i class="bi bi-info-circle me-1"></i> ${registered.length} registered participants found for this event. Select winner from dropdown.</span>`;
                        } else {
                            participantNotice.innerText = 'No participants were pre-registered for this event. You can select any eligible group member or team entry.';
                        }
                    })
                    .catch(err => {
                        participantLoading.classList.add('d-none');
                        participantSelect.disabled = false;
                        participantSelect.innerHTML = `<option value="Team ${groupName}">Team ${groupName}</option>`;
                        participantNotice.innerText = 'Could not load members via network. Defaulted to Team option.';
                    });
            }

            // Participant dropdown selection change
            participantSelect.addEventListener('change', function () {
                const opt = this.options[this.selectedIndex];
                if (opt && opt.getAttribute('data-member-id')) {
                    memberIdInput.value = opt.getAttribute('data-member-id');
                } else {
                    memberIdInput.value = '';
                }
            });

            // Event Listeners
            sectionFilter.addEventListener('change', filterProgramsBySection);
            programSelect.addEventListener('change', function () {
                updateMarks();
                loadParticipants();
            });
            groupSelect.addEventListener('change', loadParticipants);
            positionSelect.addEventListener('change', updateMarks);

            // Initial load if old input or preselected program
            if (sectionFilter.value) {
                filterProgramsBySection();
            }
            if (programSelect.value) {
                updateMarks();
            }
            if (programSelect.value && groupSelect.value) {
                loadParticipants();
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/admin/results/create.blade.php ENDPATH**/ ?>