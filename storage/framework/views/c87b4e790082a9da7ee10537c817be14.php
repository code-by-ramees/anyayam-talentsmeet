<?php $__env->startSection('title', "Programs & Schedule — {$festName}"); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('public.index')); ?>" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active">Programs Schedule</li>
                </ol>
            </nav>
            <h3 class="fw-extrabold mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-calendar-check text-primary"></i>
                <span>Competition Programs & Schedule</span>
            </h3>
        </div>
    </div>

    <!-- Filters -->
    <div class="fest-card p-3 mb-4 bg-white">
        <form action="<?php echo e(route('public.programs')); ?>" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fs-xs text-muted fw-bold mb-1">Filter by Section</label>
                <select name="section_id" class="form-select form-select-sm rounded-pill">
                    <option value="">All Sections</option>
                    <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sec->id); ?>" <?php echo e(request('section_id') == $sec->id ? 'selected' : ''); ?>>
                            <?php echo e($sec->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fs-xs text-muted fw-bold mb-1">Filter by Category</label>
                <select name="category_id" class="form-select form-select-sm rounded-pill">
                    <option value="">All Categories</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>>
                            <?php echo e($cat->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fs-xs text-muted fw-bold mb-1">Program Status</label>
                <select name="status" class="form-select form-select-sm rounded-pill">
                    <option value="">All Statuses</option>
                    <option value="scheduled" <?php echo e(request('status') == 'scheduled' ? 'selected' : ''); ?>>Scheduled</option>
                    <option value="ongoing" <?php echo e(request('status') == 'ongoing' ? 'selected' : ''); ?>>Ongoing</option>
                    <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                </select>
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-fest-primary btn-sm rounded-pill w-100">
                    <i class="bi bi-funnel me-1"></i> Apply Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Programs Grid -->
    <div class="row g-3">
        <?php $__empty_1 = true; $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-6 col-lg-4">
                <div class="fest-card p-4 h-100 d-flex flex-direction-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-light text-dark border"><?php echo e($prog->section->name); ?></span>
                            <?php if($prog->status === 'completed'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Completed</span>
                            <?php elseif($prog->status === 'ongoing'): ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Live / Ongoing</span>
                            <?php else: ?>
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">Scheduled</span>
                            <?php endif; ?>
                        </div>

                        <h5 class="fw-bold text-dark mb-1"><?php echo e($prog->name); ?></h5>
                        <p class="text-muted small mb-2">Code: <code><?php echo e($prog->code); ?></code></p>

                        <div class="p-2 bg-light rounded-3 border small mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Category:</span>
                                <strong class="text-dark"><?php echo e($prog->category->name); ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Type:</span>
                                <span class="badge bg-white text-dark border text-capitalize"><?php echo e($prog->type); ?></span>
                            </div>
                            <?php if($prog->venue): ?>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Venue:</span>
                                    <span><i class="bi bi-geo-alt text-danger me-1"></i><?php echo e($prog->venue); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Marks allocated preview -->
                    <div class="border-top pt-2 d-flex justify-content-between text-muted fs-xs">
                        <span>1st: <strong><?php echo e($prog->category->first_marks); ?> pts</strong></span>
                        <span>2nd: <strong><?php echo e($prog->category->second_marks); ?> pts</strong></span>
                        <span>3rd: <strong><?php echo e($prog->category->third_marks); ?> pts</strong></span>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="fest-card p-5 text-center text-muted">
                    <h5>No competition programs matched your search.</h5>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if($programs->hasPages()): ?>
        <div class="mt-4">
            <?php echo e($programs->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/public/programs.blade.php ENDPATH**/ ?>