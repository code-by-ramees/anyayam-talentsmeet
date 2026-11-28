<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-lg-4">
    
    <!-- Top Bar with Quick Actions -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="fw-extrabold mb-1">Super Admin Dashboard</h3>
            <p class="text-muted small mb-0"><?php echo e($festName); ?> · Centralized Fest Control & Scoring Engine</p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="<?php echo e(route('admin.results.create')); ?>" class="btn btn-primary rounded-pill px-3 btn-sm shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Enter New Result
            </a>
            <a href="<?php echo e(route('admin.programs.create')); ?>" class="btn btn-outline-dark rounded-pill px-3 btn-sm">
                <i class="bi bi-calendar-plus me-1"></i> Add Program
            </a>
            <a href="<?php echo e(route('admin.groups.create')); ?>" class="btn btn-outline-secondary rounded-pill px-3 btn-sm">
                <i class="bi bi-people me-1"></i> Add Group
            </a>
        </div>
    </div>

    <!-- 6 Key Metrics Cards -->
    <div class="row g-3 mb-4">
        <!-- 1. Total Groups -->
        <div class="col-sm-6 col-xl-2">
            <div class="admin-card p-3 border-start border-4 border-primary">
                <span class="text-muted fs-xs fw-bold text-uppercase d-block">Groups</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="fs-4 fw-extrabold text-dark"><?php echo e($totalGroups); ?></span>
                    <i class="bi bi-people-fill text-primary fs-4"></i>
                </div>
                <small class="text-muted fs-xs">Participating Houses</small>
            </div>
        </div>

        <!-- 2. Total Members -->
        <div class="col-sm-6 col-xl-2">
            <div class="admin-card p-3 border-start border-4 border-info">
                <span class="text-muted fs-xs fw-bold text-uppercase d-block">Members</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="fs-4 fw-extrabold text-dark"><?php echo e($totalMembers); ?></span>
                    <i class="bi bi-person-badge-fill text-info fs-4"></i>
                </div>
                <small class="text-muted fs-xs">Registered Students</small>
            </div>
        </div>

        <!-- 3. Programs -->
        <div class="col-sm-6 col-xl-2">
            <div class="admin-card p-3 border-start border-4 border-warning">
                <span class="text-muted fs-xs fw-bold text-uppercase d-block">Programs</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="fs-4 fw-extrabold text-dark"><?php echo e($totalPrograms); ?></span>
                    <i class="bi bi-calendar-check-fill text-warning fs-4"></i>
                </div>
                <small class="text-muted fs-xs">Competitions</small>
            </div>
        </div>

        <!-- 4. Categories -->
        <div class="col-sm-6 col-xl-2">
            <div class="admin-card p-3 border-start border-4 border-secondary">
                <span class="text-muted fs-xs fw-bold text-uppercase d-block">Categories</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="fs-4 fw-extrabold text-dark"><?php echo e($totalCategories); ?></span>
                    <i class="bi bi-tags-fill text-secondary fs-4"></i>
                </div>
                <small class="text-muted fs-xs">Mark Configurations</small>
            </div>
        </div>

        <!-- 5. Results Entered -->
        <div class="col-sm-6 col-xl-2">
            <div class="admin-card p-3 border-start border-4 border-dark">
                <span class="text-muted fs-xs fw-bold text-uppercase d-block">Results Entered</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="fs-4 fw-extrabold text-dark"><?php echo e($totalResultsEntered); ?></span>
                    <i class="bi bi-award-fill text-dark fs-4"></i>
                </div>
                <small class="text-muted fs-xs"><?php echo e($draftResultsCount); ?> in draft</small>
            </div>
        </div>

        <!-- 6. Published Results -->
        <div class="col-sm-6 col-xl-2">
            <div class="admin-card p-3 border-start border-4 border-success">
                <span class="text-muted fs-xs fw-bold text-uppercase d-block">Published</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="fs-4 fw-extrabold text-success"><?php echo e($totalPublishedResults); ?></span>
                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                </div>
                <small class="text-success fs-xs fw-bold">Live on Portals</small>
            </div>
        </div>
    </div>

    <!-- Section Statistics Cards -->
    <div class="row g-3 mb-4">
        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-6">
                <div class="admin-card p-3 bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-dark rounded-pill mb-1"><?php echo e($sec->code); ?></span>
                            <h5 class="fw-bold mb-0 text-dark"><?php echo e($sec->name); ?> Section</h5>
                            <small class="text-muted"><?php echo e($sec->description); ?></small>
                        </div>
                        <div class="text-end">
                            <div><strong class="fs-5"><?php echo e($sec->members_count); ?></strong> <small class="text-muted">Students</small></div>
                            <div><strong class="fs-5"><?php echo e($sec->programs_count); ?></strong> <small class="text-muted">Programs</small></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="row g-4">
        
        <!-- Left: Current Group Leaderboard -->
        <div class="col-lg-8">
            <div class="admin-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-trophy text-warning"></i> Group Performance & Scoreboard
                    </h5>
                    <a href="<?php echo e(route('admin.rankings.index')); ?>" class="btn btn-sm btn-outline-secondary rounded-pill">
                        Full Rankings <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <?php if($rankings->isEmpty()): ?>
                    <div class="text-center py-4 text-muted">
                        No participating groups registered yet.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small text-uppercase text-muted">
                                <tr>
                                    <th style="width: 50px;">Rank</th>
                                    <th>Group Name</th>
                                    <th>Code</th>
                                    <th class="text-center">Members</th>
                                    <th class="text-center">1st / 2nd / 3rd</th>
                                    <th class="text-end">Draft Pts</th>
                                    <th class="text-end pe-3">Published Pts</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $rankings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="<?php echo e($idx === 0 ? 'table-warning-subtle fw-semibold' : ''); ?>">
                                        <td>
                                            <?php if($idx === 0): ?>
                                                <span class="badge bg-warning text-dark">🥇 1</span>
                                            <?php elseif($idx === 1): ?>
                                                <span class="badge bg-secondary text-white">🥈 2</span>
                                            <?php elseif($idx === 2): ?>
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">🥉 3</span>
                                            <?php else: ?>
                                                <span class="text-muted fw-bold">#<?php echo e($idx + 1); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span style="width: 12px; height: 12px; border-radius: 4px; background-color: <?php echo e($r['group']->color); ?>;"></span>
                                                <strong class="text-dark"><?php echo e($r['group']->name); ?></strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-muted border font-monospace"><?php echo e($r['group']->code); ?></span>
                                        </td>
                                        <td class="text-center text-muted small">
                                            <?php echo e($r['members_count']); ?>

                                        </td>
                                        <td class="text-center small">
                                            <span class="text-warning fw-bold"><?php echo e($r['first_prizes']); ?></span> /
                                            <span class="text-secondary fw-bold"><?php echo e($r['second_prizes']); ?></span> /
                                            <span class="text-danger fw-bold"><?php echo e($r['third_prizes']); ?></span>
                                        </td>
                                        <td class="text-end text-muted small">
                                            <?php echo e($r['draft_marks']); ?> pts
                                        </td>
                                        <td class="text-end pe-3">
                                            <span class="fw-extrabold fs-6 text-success"><?php echo e($r['total_marks']); ?> pts</span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: Recent Activities & Quick Navigation -->
        <div class="col-lg-4">
            
            <!-- Quick Actions Card -->
            <div class="admin-card p-3 mb-4 bg-light border-0">
                <h6 class="fw-bold mb-2 small text-uppercase text-muted">Quick Operations</h6>
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('admin.results.index')); ?>" class="btn btn-white border rounded-3 text-start d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-award text-primary me-2"></i>Manage Results</span>
                        <span class="badge bg-primary rounded-pill"><?php echo e($draftResultsCount); ?> Drafts</span>
                    </a>
                    <a href="<?php echo e(route('admin.groups.index')); ?>" class="btn btn-white border rounded-3 text-start d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-people text-teal me-2"></i>Group Portals & Logins</span>
                        <span class="badge bg-light text-muted border"><?php echo e($totalGroups); ?></span>
                    </a>
                    <a href="<?php echo e(route('admin.rankings.print')); ?>" target="_blank" class="btn btn-white border rounded-3 text-start d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-printer text-dark me-2"></i>Print Official Scoresheet</span>
                        <i class="bi bi-box-arrow-up-right fs-xs text-muted"></i>
                    </a>
                </div>
            </div>

            <!-- Activity Logs -->
            <div class="admin-card p-3">
                <h6 class="fw-bold mb-3 small text-uppercase text-muted d-flex align-items-center gap-1">
                    <i class="bi bi-clock-history text-secondary"></i> Recent Audit Log
                </h6>

                <?php if($recentActivities->isEmpty()): ?>
                    <p class="text-muted small mb-0">No recent activities recorded.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush fs-xs">
                        <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item px-0 py-2 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-dark"><?php echo e($act->action); ?></strong>
                                    <span class="text-muted"><?php echo e($act->created_at->diffForHumans()); ?></span>
                                </div>
                                <div class="text-muted mt-1"><?php echo e($act->description); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>