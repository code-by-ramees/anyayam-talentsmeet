<?php $__env->startSection('title', "Live Leaderboard & Results — {$festName}"); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="py-5 bg-white border-bottom">
    <div class="container py-lg-3">
        <div class="row align-items-center gy-4">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-light border text-dark fw-bold small mb-3">
                    <span class="badge bg-danger rounded-pill px-2 py-1">LIVE</span>
                    <span><?php echo e($collegeName); ?></span>
                    <span>·</span>
                    <span class="text-primary"><?php echo e($edition); ?></span>
                </div>

                <h1 class="display-4 fw-extrabold text-dark tracking-tight mb-2">
                    <?php echo e($festName); ?>

                </h1>
                <p class="lead text-muted mb-4 fs-5" style="max-width: 620px;">
                    Centralized festival scoreboards for <strong>Bakalooriyya</strong> and <strong>Sanaviyya Ulya</strong> sections. Track real-time group points, prize tallies, and official results transparently.
                </p>

                <div class="d-flex flex-wrap align-items-center gap-3">
                    <a href="#leaderboard" class="btn btn-fest-primary btn-lg px-4 shadow-sm">
                        <i class="bi bi-trophy-fill me-2 text-warning"></i> View Live Leaderboard
                    </a>
                    <a href="<?php echo e(route('public.results')); ?>" class="btn btn-outline-dark btn-lg rounded-pill px-4">
                        <i class="bi bi-award me-1"></i> Published Results
                    </a>
                </div>
            </div>

            <!-- Fest Stats Badges -->
            <div class="col-lg-4">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="fest-card p-3 text-center bg-light">
                            <span class="text-muted small fw-semibold d-block">Participating Groups</span>
                            <span class="display-6 fw-extrabold text-dark"><?php echo e($totalGroups); ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fest-card p-3 text-center bg-light">
                            <span class="text-muted small fw-semibold d-block">Total Programs</span>
                            <span class="display-6 fw-extrabold text-primary"><?php echo e($totalPrograms); ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fest-card p-3 text-center bg-light">
                            <span class="text-muted small fw-semibold d-block">Completed Events</span>
                            <span class="display-6 fw-extrabold text-success"><?php echo e($completedPrograms); ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fest-card p-3 text-center bg-light">
                            <span class="text-muted small fw-semibold d-block">Sections</span>
                            <span class="display-6 fw-extrabold text-secondary"><?php echo e($sections->count()); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Podium & Leaderboard Section -->
<section id="leaderboard" class="py-5">
    <div class="container">
        
        <!-- Section Tabs Navigation -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h3 class="fw-extrabold mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-trophy-fill text-warning"></i>
                    <span>Festival Standings & Rankings</span>
                </h3>
                <p class="text-muted small mb-0">Official points awarded across all completed events with automated tie-breaking.</p>
            </div>

            <!-- Tab Pills -->
            <ul class="nav nav-pills bg-white p-1 rounded-pill border shadow-sm" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-4 fw-bold" id="pills-overall-tab" data-bs-toggle="pill" data-bs-target="#pills-overall" type="button" role="tab">
                        Overall Rankings
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4 fw-bold" id="pills-bak-tab" data-bs-toggle="pill" data-bs-target="#pills-bak" type="button" role="tab">
                        Bakalooriyya
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4 fw-bold" id="pills-san-tab" data-bs-toggle="pill" data-bs-target="#pills-san" type="button" role="tab">
                        Sanaviyya Ulya
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Contents -->
        <div class="tab-content" id="pills-tabContent">
            
            <!-- 1. Overall Tab -->
            <div class="tab-pane fade show active" id="pills-overall" role="tabpanel">
                <?php echo $__env->make('public.partials.leaderboard_table', ['rankings' => $overallRankings, 'title' => 'Overall Festival Rankings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <!-- 2. Bakalooriyya Tab -->
            <div class="tab-pane fade" id="pills-bak" role="tabpanel">
                <?php echo $__env->make('public.partials.leaderboard_table', ['rankings' => $bakalooriyyaRankings, 'title' => 'Bakalooriyya Section Rankings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <!-- 3. Sanaviyya Ulya Tab -->
            <div class="tab-pane fade" id="pills-san" role="tabpanel">
                <?php echo $__env->make('public.partials.leaderboard_table', ['rankings' => $sanaviyyaRankings, 'title' => 'Sanaviyya Ulya Section Rankings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

        </div>

        <!-- Recent Published Results Row -->
        <div class="mt-5 pt-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary"></i> Latest Published Results
                </h4>
                <a href="<?php echo e(route('public.results')); ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    View All Results <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <?php if($latestResults->isEmpty()): ?>
                <div class="fest-card p-4 text-center text-muted">
                    No results have been officially published yet.
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php $__currentLoopData = $latestResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="fest-card p-3 h-100 position-relative">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-light text-dark border small"><?php echo e($res->program->section->name); ?></span>
                                    <span class="<?php echo e($res->getPositionBadgeClass()); ?> rounded-pill px-2 py-1 fs-xs fw-bold">
                                        <?php echo e($res->getPositionLabel()); ?>

                                    </span>
                                </div>
                                <h6 class="fw-bold text-dark mb-1 text-truncate" title="<?php echo e($res->program->name); ?>">
                                    <?php echo e($res->program->name); ?>

                                </h6>
                                <p class="text-muted fs-xs mb-2 text-truncate">
                                    <?php echo e($res->program->category->name); ?>

                                </p>
                                <div class="d-flex align-items-center justify-content-between border-top pt-2 mt-auto">
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="width: 10px; height: 10px; border-radius: 50%; background-color: <?php echo e($res->group->color); ?>;"></span>
                                        <span class="fw-bold small text-truncate" style="max-width: 140px;"><?php echo e($res->group->name); ?></span>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold">
                                        +<?php echo e($res->marks); ?> Pts
                                    </span>
                                </div>
                                <div class="text-muted fs-xs mt-1 text-truncate">
                                    <i class="bi bi-person me-1"></i><?php echo e($res->participant_name); ?>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/public/index.blade.php ENDPATH**/ ?>