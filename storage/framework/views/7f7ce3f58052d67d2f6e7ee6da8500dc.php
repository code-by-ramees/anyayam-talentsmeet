<?php $__env->startSection('title', 'Our Results & Awards — ' . $group->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Our Group's Awards & Results</h2>
            <p class="text-muted small mb-0"><?php echo e($group->name); ?> &bull; Certified published competition awards.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="fs-6 text-muted">Total Score:</span>
            <span class="badge bg-success fs-5 px-3 py-2 rounded-pill">
                <i class="bi bi-star-fill me-1"></i> <?php echo e($totalMarks); ?> Points
            </span>
        </div>
    </div>

    <div class="card group-card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Program</th>
                            <th>Category</th>
                            <th>Participant / Team</th>
                            <th>Award / Position</th>
                            <th>Remarks</th>
                            <th class="text-end pe-4">Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark"><?php echo e($res->program->name ?? ''); ?></div>
                                    <span class="badge bg-light text-secondary border font-monospace"><?php echo e($res->program->code ?? ''); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                        <?php echo e($res->program->category->name ?? ''); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo e($res->participant_name); ?></div>
                                </td>
                                <td>
                                    <span class="badge <?php echo e($res->getPositionBadgeClass()); ?> px-3 py-2 fs-6">
                                        <i class="bi bi-trophy-fill me-1"></i> <?php echo e($res->getPositionLabel()); ?>

                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted"><?php echo e($res->remarks ?: '—'); ?></small>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="badge bg-success fs-6 fw-bold px-3 py-1">
                                        +<?php echo e($res->marks); ?> pts
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-award display-4 mb-3 d-block text-secondary"></i>
                                    <h5>No Published Awards Yet</h5>
                                    <p class="small">As results are entered and published by festival organizers, your won positions will appear here.</p>
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

<?php echo $__env->make('layouts.group', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/group/results/index.blade.php ENDPATH**/ ?>