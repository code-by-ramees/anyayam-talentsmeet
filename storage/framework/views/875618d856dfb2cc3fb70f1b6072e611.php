<?php $__env->startSection('title', 'Competition Results & Awards'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-lg-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Competition Results & Awards</h2>
            <p class="text-muted small mb-0">Record winners, automatically calculate points, and manage publication status.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <?php if($draftCount > 0): ?>
                <form action="<?php echo e(route('admin.results.publish-all')); ?>" method="POST" onsubmit="return confirm('Publish all <?php echo e($draftCount); ?> pending draft results to group portals and public leaderboard?');">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-warning text-dark fw-semibold shadow-sm">
                        <i class="bi bi-broadcast me-1"></i> Publish All Drafts (<?php echo e($draftCount); ?>)
                    </button>
                </form>
            <?php endif; ?>
            <a href="<?php echo e(route('admin.results.create')); ?>" class="btn btn-teal text-white shadow-sm" style="background-color: #0d9488;">
                <i class="bi bi-plus-circle me-1"></i> Enter New Result
            </a>
        </div>
    </div>

    <!-- Status Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card admin-card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary-subtle text-primary rounded-3 p-3 fs-4">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark"><?php echo e($publishedCount + $draftCount); ?></div>
                        <div class="text-muted small">Total Results Entered</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card admin-card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success-subtle text-success rounded-3 p-3 fs-4">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-success"><?php echo e($publishedCount); ?></div>
                        <div class="text-muted small">Published Live</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card admin-card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-warning-subtle text-warning-emphasis rounded-3 p-3 fs-4">
                        <i class="bi bi-file-earmark-medical-fill"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-warning-emphasis"><?php echo e($draftCount); ?></div>
                        <div class="text-muted small">Saved as Drafts</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card admin-card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-info-subtle text-info rounded-3 p-3 fs-4">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                    <div>
                        <a href="<?php echo e(route('public.results')); ?>" target="_blank" class="fw-bold text-decoration-none text-dark d-block">
                            View Public Page <i class="bi bi-box-arrow-up-right small"></i>
                        </a>
                        <div class="text-muted small">Live Result Feed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card admin-card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="<?php echo e(route('admin.results.index')); ?>" method="GET" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search program or participant..." value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-md-3">
                    <select name="section_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Sections</option>
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sec->id); ?>" <?php echo e(request('section_id') == $sec->id ? 'selected' : ''); ?>>
                                <?php echo e($sec->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="group_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Groups</option>
                        <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($grp->id); ?>" <?php echo e(request('group_id') == $grp->id ? 'selected' : ''); ?>>
                                <?php echo e($grp->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="published" <?php echo e(request('status') === 'published' ? 'selected' : ''); ?>>Published Only</option>
                        <option value="draft" <?php echo e(request('status') === 'draft' ? 'selected' : ''); ?>>Drafts Only</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-secondary w-100">Filter</button>
                    <?php if(request()->hasAny(['search', 'section_id', 'group_id', 'status'])): ?>
                        <a href="<?php echo e(route('admin.results.index')); ?>" class="btn btn-light"><i class="bi bi-x-lg"></i></a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card admin-card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Program</th>
                            <th>Section & Cat</th>
                            <th>Award & Position</th>
                            <th>Winning Group</th>
                            <th>Participant / Team</th>
                            <th class="text-center">Points</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark"><?php echo e($res->program->name ?? 'N/A'); ?></div>
                                    <span class="badge bg-light text-secondary border font-monospace"><?php echo e($res->program->code ?? ''); ?></span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-2 py-1 text-white" style="background-color: #0d9488;">
                                        <?php echo e($res->program->section->name ?? 'N/A'); ?>

                                    </span>
                                    <span class="badge bg-light text-dark border">
                                        <?php echo e($res->program->category->name ?? 'N/A'); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?php echo e($res->getPositionBadgeClass()); ?> px-3 py-2 fs-6">
                                        <i class="bi bi-trophy-fill me-1"></i> <?php echo e($res->getPositionLabel()); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="badge px-2 py-1 text-white" style="background-color: <?php echo e($res->group->color ?? '#0d9488'); ?>;">
                                        <?php echo e($res->group->name ?? 'N/A'); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?php echo e($res->participant_name); ?></div>
                                    <?php if($res->remarks): ?>
                                        <small class="text-muted d-block"><?php echo e($res->remarks); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success fs-6 fw-bold px-3 py-1 border border-success-subtle">
                                        +<?php echo e($res->marks); ?>

                                    </span>
                                </td>
                                <td class="text-center">
                                    <form action="<?php echo e(route('admin.results.toggle-publish', $res)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit" class="btn btn-sm p-0 border-0" title="Click to toggle publication">
                                            <?php if($res->is_published): ?>
                                                <span class="badge bg-success rounded-pill px-3 py-1 cursor-pointer">
                                                    <i class="bi bi-globe me-1"></i> Published
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 cursor-pointer">
                                                    <i class="bi bi-hourglass-split me-1"></i> Draft
                                                </span>
                                            <?php endif; ?>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo e(route('admin.results.edit', $res)); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        <form action="<?php echo e(route('admin.results.destroy', $res)); ?>" method="POST" onsubmit="return confirm('Delete this result entry? Points will be deducted.');">
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
                                    <i class="bi bi-award display-4 mb-3 d-block text-secondary"></i>
                                    <h5>No Results Found</h5>
                                    <p class="small">Click "Enter New Result" above to record competition winners.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($results->hasPages()): ?>
                <div class="p-3 border-top">
                    <?php echo e($results->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/admin/results/index.blade.php ENDPATH**/ ?>