<?php $__env->startSection('title', 'Event Registrations'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-lg-4">
    <!-- Header with Toggle Banner -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Competition & Event Registrations</h2>
            <p class="text-muted small mb-0">Manage group event entries, view registered participants, and control registration status.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- Registration Master Toggle -->
            <form action="<?php echo e(route('admin.registrations.toggle-status')); ?>" method="POST" class="d-inline">
                <?php echo csrf_field(); ?>
                <?php if($isRegistrationOpen): ?>
                    <button type="submit" class="btn btn-outline-danger shadow-sm rounded-pill px-3 py-2 fw-semibold"
                        onclick="return confirm('Are you sure you want to CLOSE event registrations? Groups will not be able to add or cancel registrations.');">
                        <i class="bi bi-lock-fill me-1"></i> Close Group Registration
                    </button>
                <?php else: ?>
                    <button type="submit" class="btn btn-teal text-white shadow-sm rounded-pill px-3 py-2 fw-semibold"
                        style="background-color: #9b111e;"
                        onclick="return confirm('Are you sure you want to OPEN event registrations for group portals?');">
                        <i class="bi bi-unlock-fill me-1"></i> Open Group Registration
                    </button>
                <?php endif; ?>
            </form>

            <a href="<?php echo e(route('admin.registrations.create')); ?>" class="btn btn-teal text-white shadow-sm rounded-pill px-3 py-2 fw-semibold" style="background-color: #9b111e;">
                <i class="bi bi-plus-lg me-1"></i> Register Participants
            </a>
        </div>
    </div>

    <!-- Status & Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card admin-card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Registration Status</div>
                        <h4 class="fw-bold mb-0 mt-1">
                            <?php if($isRegistrationOpen): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 fs-6">
                                    <i class="bi bi-check-circle-fill me-1"></i> OPEN
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 fs-6">
                                    <i class="bi bi-x-circle-fill me-1"></i> CLOSED
                                </span>
                            <?php endif; ?>
                        </h4>
                    </div>
                    <div class="p-3 rounded-circle <?php echo e($isRegistrationOpen ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'); ?> fs-4">
                        <i class="bi <?php echo e($isRegistrationOpen ? 'bi-toggle-on' : 'bi-toggle-off'); ?>"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card admin-card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Total Registrations</div>
                        <h4 class="fw-bold mb-0 mt-1"><?php echo e(number_format($totalRegistrations)); ?></h4>
                    </div>
                    <div class="p-3 rounded-circle bg-teal-subtle text-teal fs-4" style="background-color: rgba(13,148,136,0.1); color: #9b111e;">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card admin-card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Programs With Entries</div>
                        <h4 class="fw-bold mb-0 mt-1"><?php echo e($registeredProgramsCount); ?></h4>
                    </div>
                    <div class="p-3 rounded-circle bg-primary-subtle text-primary fs-4">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card admin-card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Active Groups</div>
                        <h4 class="fw-bold mb-0 mt-1"><?php echo e($participatingGroupsCount); ?></h4>
                    </div>
                    <div class="p-3 rounded-circle bg-warning-subtle text-warning fs-4">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card admin-card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="<?php echo e(route('admin.registrations.index')); ?>" method="GET" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search participant, UID, Chest No, program..." value="<?php echo e(request('search')); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="group_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Groups...</option>
                        <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($grp->id); ?>" <?php echo e(request('group_id') == $grp->id ? 'selected' : ''); ?>>
                                <?php echo e($grp->name); ?> (<?php echo e($grp->code); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="program_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Programs...</option>
                        <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($prg->id); ?>" <?php echo e(request('program_id') == $prg->id ? 'selected' : ''); ?>>
                                <?php echo e($prg->name); ?> (<?php echo e($prg->code); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="section_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Sections...</option>
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sec->id); ?>" <?php echo e(request('section_id') == $sec->id ? 'selected' : ''); ?>>
                                <?php echo e($sec->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="stage_type" class="form-select" onchange="this.form.submit()">
                        <option value="">All Stage Types...</option>
                        <option value="stage" <?php echo e(request('stage_type') === 'stage' ? 'selected' : ''); ?>>Stage Programs</option>
                        <option value="non_stage" <?php echo e(request('stage_type') === 'non_stage' ? 'selected' : ''); ?>>Non-Stage Programs</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-secondary w-100">Filter</button>
                    <?php if(request()->hasAny(['search', 'group_id', 'program_id', 'section_id', 'stage_type'])): ?>
                        <a href="<?php echo e(route('admin.registrations.index')); ?>" class="btn btn-light"><i class="bi bi-x-lg"></i></a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Registrations Table -->
    <div class="card admin-card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Program</th>
                            <th>Section & Stage</th>
                            <th>Group / House</th>
                            <th>Participant (Member)</th>
                            <th>Chest No</th>
                            <th>Class</th>
                            <th>Registered By</th>
                            <th>Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark"><?php echo e($reg->program->name ?? 'N/A'); ?></div>
                                    <span class="badge bg-light text-muted border font-monospace"><?php echo e($reg->program->code ?? ''); ?></span>
                                    <span class="badge bg-secondary-subtle text-secondary border ms-1"><?php echo e(ucfirst($reg->program->type ?? 'individual')); ?></span>
                                </td>
                                <td>
                                    <span class="badge <?php echo e(strtolower($reg->program->section->name ?? '') === 'general' ? 'bg-primary-subtle text-primary border' : 'bg-info-subtle text-dark border'); ?>">
                                        <?php echo e($reg->program->section->name ?? 'N/A'); ?>

                                    </span>
                                    <span class="badge <?php echo e(($reg->program->stage_type ?? 'stage') === 'stage' ? 'bg-purple-subtle text-purple border' : 'bg-warning-subtle text-dark border'); ?>" style="background-color: #f3e8ff; color: #7e22ce;">
                                        <?php echo e(($reg->program->stage_type ?? 'stage') === 'non_stage' ? 'Non-Stage' : 'Stage'); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="d-inline-flex align-items-center gap-1">
                                        <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background-color:<?php echo e($reg->group->color ?? '#9b111e'); ?>;"></span>
                                        <strong><?php echo e($reg->group->name ?? 'N/A'); ?></strong>
                                        <small class="text-muted">(<?php echo e($reg->group->code ?? ''); ?>)</small>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?php echo e($reg->member->name ?? 'N/A'); ?></div>
                                    <small class="text-muted font-monospace"><i class="bi bi-upc-scan me-1"></i><?php echo e($reg->member->uid ?? ''); ?></small>
                                </td>
                                <td>
                                    <?php
                                        $chest = $reg->chest_number ?: ($reg->member->chest_number ?? null);
                                    ?>
                                    <?php if($chest): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle font-monospace fw-bold px-2 py-1">
                                            #<?php echo e($chest); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning border px-2 py-1 small">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?php echo e($reg->member->class_year ?? 'N/A'); ?></span>
                                </td>
                                <td>
                                    <?php if($reg->registered_by === 'admin'): ?>
                                        <span class="badge bg-dark-subtle text-dark border"><i class="bi bi-shield-check me-1"></i> Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-teal-subtle text-teal border" style="background-color: rgba(13,148,136,0.1); color: #9b111e;"><i class="bi bi-house me-1"></i> Group</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted"><?php echo e($reg->created_at->format('M d, Y h:i A')); ?></small>
                                </td>
                                <td class="text-end pe-4">
                                    <form action="<?php echo e(route('admin.registrations.destroy', $reg)); ?>" method="POST"
                                        onsubmit="return confirm('Remove registration for <?php echo e($reg->member->name ?? 'participant'); ?> from <?php echo e($reg->program->name ?? 'program'); ?>?');" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Delete Registration">
                                            <i class="bi bi-trash"></i> Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-clipboard-x display-4 mb-3 d-block text-secondary"></i>
                                    <h5>No Event Registrations Found</h5>
                                    <p class="small">No participants have been registered for events matching this criteria.</p>
                                    <a href="<?php echo e(route('admin.registrations.create')); ?>" class="btn btn-teal text-white rounded-pill px-4" style="background-color: #9b111e;">
                                        <i class="bi bi-plus-lg me-1"></i> Register Participants
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($registrations->hasPages()): ?>
                <div class="p-3 border-top">
                    <?php echo e($registrations->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/admin/registrations/index.blade.php ENDPATH**/ ?>