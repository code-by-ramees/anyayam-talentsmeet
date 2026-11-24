<?php $__env->startSection('title', 'Our Members — ' . $group->name); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">Our Group Members</h2>
                <p class="text-muted small mb-0"><?php echo e($group->name); ?> (<?php echo e($group->code); ?>) &bull; Enrolled participants across
                    Junior & Senior sections.</p>
            </div>
            <?php if($canAddMembers): ?>
                <button type="button" class="btn btn-teal text-white shadow-sm" style="background-color: #9b111e;"
                    data-bs-toggle="modal" data-bs-target="#addGroupMemberModal">
                    <i class="bi bi-person-plus-fill me-1"></i> Add Member
                </button>
            <?php else: ?>
                <span class="badge bg-secondary-subtle text-secondary border px-3 py-2 rounded-pill">
                    <i class="bi bi-lock-fill me-1"></i> Member Registration Locked by Admin
                </span>
            <?php endif; ?>
        </div>

        <!-- Search & Filter Card -->
        <div class="card group-card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="<?php echo e(route('group.members')); ?>" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i
                                    class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                placeholder="Search members by name, UID, Chest No, or class..." value="<?php echo e(request('search')); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="section_id" class="form-select" onchange="this.form.submit()">
                            <option value="">All Sections (Junior & Senior)</option>
                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sec->id); ?>" <?php echo e(request('section_id') == $sec->id ? 'selected' : ''); ?>>
                                    <?php echo e($sec->name); ?> (<?php echo e($sec->code); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-secondary w-100">Filter</button>
                        <?php if(request()->hasAny(['search', 'section_id'])): ?>
                            <a href="<?php echo e(route('group.members')); ?>" class="btn btn-light"><i class="bi bi-x-lg"></i></a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Members Table -->
        <div class="card group-card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Member Name</th>
                                <th>UID</th>
                                <th>Chest No</th>
                                <th>Section</th>
                                <th>Class</th>
                                <?php if($canAddMembers): ?>
                                    <th class="text-end pe-4">Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark"><?php echo e($m->name); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace"><?php echo e($m->uid); ?></span>
                                    </td>
                                    <td>
                                        <?php if($m->chest_number): ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle font-monospace fw-bold px-2 py-1">
                                                #<?php echo e($m->chest_number); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning border px-2 py-1 small">No Chest No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border">
                                            <?php echo e($m->section->name ?? 'N/A'); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($m->class_year); ?></td>
                                    <?php if($canAddMembers): ?>
                                        <td class="text-end pe-4">
                                            <form action="<?php echo e(route('group.members.destroy', $m)); ?>" method="POST"
                                                onsubmit="return confirm('Remove <?php echo e($m->name); ?> from group?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                    <i class="bi bi-trash me-1"></i> Remove
                                                </button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="<?php echo e($canAddMembers ? 6 : 5); ?>" class="text-center py-5 text-muted">
                                        <i class="bi bi-people display-4 mb-3 d-block text-secondary"></i>
                                        <h5>No Members Found</h5>
                                        <p class="small">No members registered yet for <?php echo e($group->name); ?>.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($members->hasPages()): ?>
                    <div class="p-3 border-top">
                        <?php echo e($members->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if($canAddMembers): ?>
        <!-- Add Member Modal -->
        <div class="modal fade" id="addGroupMemberModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="<?php echo e(route('group.members.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Register Member for <?php echo e($group->name); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Member Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Student name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Festival Section (Junior / Senior) <span
                                        class="text-danger">*</span></label>
                                <select name="section_id" class="form-select" required>
                                    <option value="">Select Section...</option>
                                    <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($sec->id); ?>">
                                            <?php echo e($sec->name); ?> (<?php echo e($sec->code); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">UID <span class="text-danger">*</span></label>
                                <input type="text" name="uid" class="form-control" placeholder="e.g. UID1024" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Chest Number <span class="text-danger">*</span></label>
                                <input type="text" name="chest_number" class="form-control" placeholder="e.g. 101 or C-14" required>
                                <small class="text-muted">Unique participant chest number for competition entries.</small>
                            </div>
                            <div class="mb-3">
                                    <label class="form-label fw-semibold">Class <span class="text-danger">*</span></label>
                                    <select name="class_year" class="form-select" required>
                                        <option value="">Select Class...</option>
                                        <?php $__currentLoopData = ['H1', 'H2', 'D1', 'D2', 'D3', 'D4']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($cls); ?>"><?php echo e($cls); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-teal text-white px-4" style="background-color: #9b111e;">Add
                                Member</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.group', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/group/members/index.blade.php ENDPATH**/ ?>