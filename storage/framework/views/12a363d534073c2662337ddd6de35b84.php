<?php $__env->startSection('title', "Leaderboard & Overall Rankings — {$festName}"); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('public.index')); ?>" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active">Leaderboard & Rankings</li>
                </ol>
            </nav>
            <h3 class="fw-extrabold mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-trophy text-warning"></i>
                <span>Official Festival Standings</span>
            </h3>
        </div>

        <div>
            <a href="javascript:window.print()" class="btn btn-outline-secondary rounded-pill btn-sm px-3">
                <i class="bi bi-printer me-1"></i> Print Scoresheet
            </a>
        </div>
    </div>

    <!-- Tabs Nav -->
    <ul class="nav nav-pills bg-white p-1 rounded-pill border shadow-sm mb-4" id="rankingTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active rounded-pill px-4 fw-bold" id="tab-overall" data-bs-toggle="pill" data-bs-target="#content-overall" type="button">
                Overall Fest Rankings
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link rounded-pill px-4 fw-bold" id="tab-bak" data-bs-toggle="pill" data-bs-target="#content-bak" type="button">
                Bakalooriyya Section
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link rounded-pill px-4 fw-bold" id="tab-san" data-bs-toggle="pill" data-bs-target="#content-san" type="button">
                Sanaviyya Ulya Section
            </button>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content" id="rankingTabsContent">
        <div class="tab-pane fade show active" id="content-overall">
            <?php echo $__env->make('public.partials.leaderboard_table', ['rankings' => $overallRankings, 'title' => 'Overall Festival Rankings (All Sections)'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <div class="tab-pane fade" id="content-bak">
            <?php echo $__env->make('public.partials.leaderboard_table', ['rankings' => $bakalooriyyaRankings, 'title' => 'Bakalooriyya Section Leaderboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <div class="tab-pane fade" id="content-san">
            <?php echo $__env->make('public.partials.leaderboard_table', ['rankings' => $sanaviyyaRankings, 'title' => 'Sanaviyya Ulya Section Leaderboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <!-- Tie Breaking Policy Note -->
    <div class="p-3 bg-white rounded-3 border mt-4 text-muted small">
        <div class="fw-bold text-dark mb-1"><i class="bi bi-info-circle text-primary me-1"></i> Official Tie-Breaking Policy:</div>
        <div>In case of identical total points, group rankings are resolved first by the highest number of <strong>1st Prizes</strong>, followed by <strong>2nd Prizes</strong>, and then <strong>3rd Prizes</strong>.</div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/public/rankings.blade.php ENDPATH**/ ?>