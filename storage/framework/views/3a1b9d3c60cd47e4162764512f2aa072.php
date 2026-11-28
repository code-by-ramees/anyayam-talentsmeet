<?php $__env->startSection('title', 'Group Members'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid px-lg-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">Group Members</h2>
                <p class="text-muted small mb-0">Manage registered student members across all participating groups.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('admin.members.export', request()->query())); ?>" class="btn btn-outline-success shadow-sm">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export CSV
                </a>
                <a href="<?php echo e(route('admin.members.create')); ?>" class="btn btn-teal text-white shadow-sm"
                    style="background-color: #9b111e;">
                    <i class="bi bi-person-plus-fill me-1"></i> Add Member
                </a>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="card admin-card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="<?php echo e(route('admin.members.index')); ?>" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i
                                    class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                placeholder="Search by name, UID, Chest No, class..." value="<?php echo e(request('search')); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="group_id" class="form-select" onchange="this.form.submit()">
                            <option value="">All Groups / Houses</option>
                            <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($grp->id); ?>" <?php echo e(request('group_id') == $grp->id ? 'selected' : ''); ?>>
                                    <?php echo e($grp->name); ?> (<?php echo e($grp->code); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="section_id" class="form-select" onchange="this.form.submit()">
                            <option value="">All Sections (Junior / Senior)</option>
                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sec->id); ?>" <?php echo e(request('section_id') == $sec->id ? 'selected' : ''); ?>>
                                    <?php echo e($sec->name); ?> (<?php echo e($sec->code); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-secondary px-3">Filter</button>
                        <?php if(request()->hasAny(['search', 'group_id', 'section_id'])): ?>
                            <a href="<?php echo e(route('admin.members.index')); ?>" class="btn btn-light">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Members Table -->
        <div class="card admin-card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Member Name</th>
                                <th>UID</th>
                                <th>Chest No</th>
                                <th>Group</th>
                                <th>Section</th>
                                <th>Class</th>
                                <th class="text-end pe-4">Actions</th>
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
                                        <span class="badge px-2 py-1 text-white"
                                            style="background-color: <?php echo e($m->group->color ?? '#9b111e'); ?>;">
                                            <?php echo e($m->group->name ?? 'Unassigned'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border">
                                            <?php echo e($m->section->name ?? 'N/A'); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($m->class_year); ?></td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="<?php echo e(route('admin.members.edit', $m)); ?>"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="bi bi-pencil me-1"></i> Edit
                                            </a>
                                            <form action="<?php echo e(route('admin.members.destroy', $m)); ?>" method="POST"
                                                onsubmit="return confirm('Remove member <?php echo e($m->name); ?>?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-person-x display-4 mb-3 d-block text-secondary"></i>
                                        <h5>No Members Found</h5>
                                        <p class="small">Try adjusting your filters or click "Add Member" to register students.
                                        </p>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/admin/members/index.blade.php ENDPATH**/ ?>