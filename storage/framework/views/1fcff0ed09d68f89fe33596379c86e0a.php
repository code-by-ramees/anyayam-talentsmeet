<?php $__env->startSection('title', "Published Results — {$festName}"); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('public.index')); ?>" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active">Published Results</li>
                </ol>
            </nav>
            <h3 class="fw-extrabold mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-award text-primary"></i>
                <span>Published Competition Results</span>
            </h3>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="fest-card p-3 mb-4 bg-white">
        <form action="<?php echo e(route('public.results')); ?>" method="GET" class="row g-2 align-items-end">
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
                <label class="form-label fs-xs text-muted fw-bold mb-1">Filter by Group</label>
                <select name="group_id" class="form-select form-select-sm rounded-pill">
                    <option value="">All Groups</option>
                    <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($grp->id); ?>" <?php echo e(request('group_id') == $grp->id ? 'selected' : ''); ?>>
                            <?php echo e($grp->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <label class="form-label fs-xs text-muted fw-bold mb-1">Search Participant / Program</label>
                        <input type="text" name="search" class="form-control form-control-sm rounded-pill" placeholder="Name or code..." value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="align-self-end">
                        <button type="submit" class="btn btn-fest-primary btn-sm rounded-pill px-3">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Table -->
    <div class="fest-card overflow-hidden">
        <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">
                Official Results Feed
            </h6>
            <span class="badge bg-white text-muted border"><?php echo e($results->total()); ?> Results Published</span>
        </div>

        <?php if($results->isEmpty()): ?>
            <div class="p-5 text-center text-muted">
                <i class="bi bi-inbox display-4 d-block mb-2 text-muted"></i>
                <h5>No published results found.</h5>
                <p class="small mb-0">Try clearing your filters or search keywords.</p>
                <a href="<?php echo e(route('public.results')); ?>" class="btn btn-sm btn-outline-secondary rounded-pill mt-2">Clear Filters</a>
            </div>
        <?php else: ?>
            <div class="table-responsive mb-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase text-muted">
                        <tr>
                            <th>Program</th>
                            <th>Section & Category</th>
                            <th>Position & Prize</th>
                            <th>Participant / Team</th>
                            <th>Group</th>
                            <th class="text-end pe-4">Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <strong class="text-dark"><?php echo e($res->program->name); ?></strong>
                                    <small class="text-muted d-block fs-xs"><?php echo e($res->program->code); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?php echo e($res->program->section->name); ?></span>
                                    <span class="badge bg-secondary-subtle text-secondary"><?php echo e($res->program->category->name); ?></span>
                                </td>
                                <td>
                                    <span class="<?php echo e($res->getPositionBadgeClass()); ?> rounded-pill px-3 py-1 fw-bold">
                                        <?php echo e($res->getPositionLabel()); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo e($res->participant_name); ?></div>
                                    <?php if($res->remarks): ?>
                                        <small class="text-muted fs-xs"><i class="bi bi-chat-left-quote me-1"></i><?php echo e($res->remarks); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="width: 12px; height: 12px; border-radius: 50%; background-color: <?php echo e($res->group->color); ?>;"></span>
                                        <span class="fw-bold text-dark"><?php echo e($res->group->name); ?></span>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle fs-6 px-3 py-1 fw-bold">
                                        +<?php echo e($res->marks); ?> Pts
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($results->hasPages()): ?>
                <div class="p-3 border-top bg-light">
                    <?php echo e($results->links()); ?>

                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/public/results.blade.php ENDPATH**/ ?>