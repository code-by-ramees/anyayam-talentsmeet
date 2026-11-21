<?php $__env->startSection('title', 'Competition Programs'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-lg-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Competition Programs</h2>
            <p class="text-muted small mb-0">Create and manage festival competitions, assigned sections, and categories.</p>
        </div>
        <a href="<?php echo e(route('admin.programs.create')); ?>" class="btn btn-teal text-white shadow-sm" style="background-color: #0d9488;">
            <i class="bi bi-calendar-plus-fill me-1"></i> Add New Program
        </a>
    </div>

    <!-- Filters -->
    <div class="card admin-card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="<?php echo e(route('admin.programs.index')); ?>" method="GET" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search program or code..." value="<?php echo e(request('search')); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="section_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Sections</option>
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sec->id); ?>" <?php echo e(request('section_id') == $sec->id ? 'selected' : ''); ?>>
                                <?php echo e($sec->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>>
                                <?php echo e($cat->name); ?> (<?php echo e($cat->first_marks); ?>/<?php echo e($cat->second_marks); ?>/<?php echo e($cat->third_marks); ?> pts)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="stage_type" class="form-select" onchange="this.form.submit()">
                        <option value="">All Stages / Natures</option>
                        <option value="stage" <?php echo e(request('stage_type') === 'stage' ? 'selected' : ''); ?>>Stage Programs</option>
                        <option value="non_stage" <?php echo e(request('stage_type') === 'non_stage' ? 'selected' : ''); ?>>Non-Stage Programs</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-secondary w-100">Filter</button>
                    <?php if(request()->hasAny(['search', 'section_id', 'category_id', 'stage_type', 'status'])): ?>
                        <a href="<?php echo e(route('admin.programs.index')); ?>" class="btn btn-light"><i class="bi bi-x-lg"></i></a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Programs Table -->
    <div class="card admin-card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Program</th>
                            <th>Section</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Stage / Nature</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Results</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark fs-6"><?php echo e($prog->name); ?></div>
                                    <span class="badge bg-light text-secondary border font-monospace"><?php echo e($prog->code); ?></span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-1 text-white" style="background-color: #0d9488;">
                                        <?php echo e($prog->section->name ?? 'N/A'); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">
                                        <?php echo e($prog->category->name ?? 'N/A'); ?>

                                    </span>
                                    <div class="small text-muted mt-1">
                                        <?php echo e($prog->category->first_marks ?? 0); ?>-<?php echo e($prog->category->second_marks ?? 0); ?>-<?php echo e($prog->category->third_marks ?? 0); ?> pts
                                    </div>
                                </td>
                                <td>
                                    <?php if($prog->type === 'individual'): ?>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">
                                            <i class="bi bi-person me-1"></i> Individual
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">
                                            <i class="bi bi-people me-1"></i> Group (<?php echo e($prog->max_participants); ?> max)
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($prog->stage_type === 'stage'): ?>
                                        <span class="badge rounded-pill px-3 py-1" style="background-color: #f3e8ff; color: #7e22ce; border: 1px solid #d8b4fe;">
                                            <i class="bi bi-mic me-1"></i> Stage
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-3 py-1">
                                            <i class="bi bi-pencil-square me-1"></i> Non-Stage
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($prog->status === 'completed'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">Completed</span>
                                    <?php elseif($prog->status === 'ongoing'): ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1">Ongoing</span>
                                    <?php elseif($prog->status === 'scheduled'): ?>
                                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-1">Scheduled</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1">Cancelled</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo e(route('admin.results.create', ['program_id' => $prog->id])); ?>" class="btn btn-sm btn-outline-success rounded-pill px-3" title="Enter or view results">
                                        <i class="bi bi-award me-1"></i> <?php echo e($prog->results->count()); ?>/3 Entered
                                    </a>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo e(route('admin.registrations.create', ['program_id' => $prog->id])); ?>" class="btn btn-sm btn-outline-teal rounded-pill px-2" style="border-color:#0d9488;color:#0d9488;" title="Register Participants">
                                            <i class="bi bi-person-plus"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.programs.edit', $prog)); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        <form action="<?php echo e(route('admin.programs.destroy', $prog)); ?>" method="POST" onsubmit="return confirm('Delete program <?php echo e($prog->name); ?>?');">
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
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x display-4 mb-3 d-block text-secondary"></i>
                                    <h5>No Programs Found</h5>
                                    <p class="small">Click "Add New Program" to schedule festival events.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($programs->hasPages()): ?>
                <div class="p-3 border-top">
                    <?php echo e($programs->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/admin/programs/index.blade.php ENDPATH**/ ?>