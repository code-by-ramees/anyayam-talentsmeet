<?php $__env->startSection('title', "Live Leaderboard & Results — {$festName}"); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section with Poster Art & Theme -->
<section class="py-5 text-white position-relative overflow-hidden" style="background: var(--fest-hero-gradient); border-bottom: 3px solid var(--fest-crimson);">
    <!-- Background Glow Blobs -->
    <div style="position: absolute; top: -120px; left: -100px; width: 450px; height: 450px; background: radial-gradient(circle, rgba(155,17,30,0.35) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>
    <div style="position: absolute; bottom: -80px; right: 10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(92,6,23,0.4) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>

    <div class="container py-lg-4 position-relative" style="z-index: 2;">
        <div class="row align-items-center gy-5">
            <!-- Left Column: Fest Branding, Title & Quote -->
            <div class="col-lg-7 text-center text-lg-start animate-slide-up">
                <!-- Live Pill Badge -->
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-black bg-opacity-50 border border-danger border-opacity-50 text-white fw-bold small mb-3 shadow-sm">
                    <span class="badge bg-danger rounded-pill px-2 py-1 pulse-badge">LIVE FEST</span>
                    <span class="text-white-50"><?php echo e($collegeName); ?></span>
                    <span>·</span>
                    <span class="text-warning fw-bold"><?php echo e($edition); ?></span>
                </div>

                <!-- Malayalam Tagline from Poster -->
                <div class="font-malayalam fw-bold mb-3" style="color: #ff6b7a; letter-spacing: 0.5px; font-size: 1.05rem;">
                    <?php echo e($tagline ?? '"കല കലഹിക്കട്ടെ; സത്യം സംസാരിക്കട്ടെ"'); ?>

                </div>

                <!-- PRIMARY HEADING: Malayalam Anyayam in bold artistic font matching the official poster -->
                <h1 class="mb-2 lh-1" style="font-family: 'Baloo Chettan 2', 'Manjari', sans-serif; font-size: clamp(3.5rem, 10vw, 6.5rem); font-weight: 800; color: #ef4444; text-shadow: 3px 3px 0px rgba(0,0,0,0.6), 0 0 40px rgba(239,68,68,0.3);">
                    അന്യായം
                </h1>

                <!-- SECONDARY: English subtitle + Justice badge -->
                <div class="d-flex align-items-center justify-content-center justify-content-lg-start flex-wrap gap-2 mb-4">
                    <span class="text-white-50 fs-5 fw-semibold"><?php echo e($festName); ?></span>
                    <span class="badge px-3 py-2 rounded-pill fs-6 fw-bold text-uppercase" style="background-color: var(--fest-crimson); letter-spacing: 1px;">
                        JUSTICE
                    </span>
                </div>

                <p class="lead text-white-50 mb-4 fs-5" style="max-width: 600px;">
                    Official festival scoreboards & live competition results across <strong>Bakalooriyya</strong>, <strong>Sanaviyya Ulya</strong>, and <strong>General</strong> sections. Organized by <strong>Manbaul Afnan Students' Association (MASA)</strong>.
                </p>

                <!-- Action CTA Buttons -->
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-3">
                    <a href="#leaderboard" class="btn btn-fest-primary btn-lg px-4 shadow-lg">
                        <i class="bi bi-trophy-fill me-2 text-warning"></i> View Live Leaderboard
                    </a>
                    <a href="<?php echo e(route('public.results')); ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">
                        <i class="bi bi-award me-1"></i> Published Results
                    </a>
                </div>
            </div>

            <!-- Right Column: Clenched Fist Poster Visual & Stats -->
            <div class="col-lg-5 text-center animate-slide-up" style="animation-delay: 0.2s;">
                <div class="position-relative d-inline-block">
                    <!-- Glow Behind Fist -->
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 280px; height: 280px; background: radial-gradient(circle, rgba(168,17,30,0.55) 0%, rgba(92,6,23,0.2) 50%, rgba(0,0,0,0) 75%); border-radius: 50%; filter: blur(20px); z-index: 0;"></div>

                    <!-- Floating Justice Clenched Fist Artwork -->
                    <div class="animate-float position-relative" style="z-index: 1;">
                        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Masa Talents '26 Justice Fist" class="img-fluid" style="max-height: 290px; filter: drop-shadow(0 15px 25px rgba(0,0,0,0.7));">
                    </div>

                    <!-- MASA Banner Strip -->
                    <div class="bg-black bg-opacity-75 border border-danger border-opacity-25 rounded-4 p-2 px-3 mt-3 shadow-sm d-inline-block position-relative" style="z-index: 2;">
                        <small class="text-white-50 fs-xs text-uppercase fw-bold d-block" style="letter-spacing: 1px;">
                            MANBAUL AFNAN STUDENTS' ASSOCIATION (MASA)
                        </small>
                    </div>
                </div>

                <!-- Stats Grid Row -->
                <div class="row g-2 mt-3 pt-2">
                    <div class="col-3">
                        <div class="p-2 rounded-3 text-center" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                            <span class="fs-xs text-white-50 d-block text-uppercase">Groups</span>
                            <span class="fs-5 fw-bold text-white"><?php echo e($totalGroups); ?></span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded-3 text-center" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                            <span class="fs-xs text-white-50 d-block text-uppercase">Events</span>
                            <span class="fs-5 fw-bold text-danger" style="color: #ff6b7a !important;"><?php echo e($totalPrograms); ?></span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded-3 text-center" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                            <span class="fs-xs text-white-50 d-block text-uppercase">Done</span>
                            <span class="fs-5 fw-bold text-success"><?php echo e($completedPrograms); ?></span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded-3 text-center" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                            <span class="fs-xs text-white-50 d-block text-uppercase">Sections</span>
                            <span class="fs-5 fw-bold text-warning"><?php echo e($sections->count()); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Podium & Leaderboard Section -->
<section id="leaderboard" class="py-5" style="background: var(--fest-sand-light);">
    <div class="container">
        
        <!-- Section Tabs Navigation -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h3 class="fw-extrabold mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-trophy-fill text-warning"></i>
                    <span>Festival Standings & Rankings</span>
                </h3>
                <p class="text-muted small mb-0">Official points awarded across completed events with automated tie-breaking.</p>
            </div>

            <!-- Tab Pills styled with crimson theme -->
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
        <div class="mt-5 pt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-crimson"></i> Latest Published Results
                </h4>
                <a href="<?php echo e(route('public.results')); ?>" class="btn btn-sm btn-outline-dark rounded-pill px-3">
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
                            <div class="fest-card p-3 h-100 position-relative d-flex flex-column">
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
                                    <span class="badge bg-crimson-subtle border border-danger-subtle fw-bold">
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

<?php $__env->startPush('styles'); ?>
<style>
    .nav-pills .nav-link {
        color: #475569;
        transition: all 0.25s ease;
    }
    .nav-pills .nav-link.active {
        background: var(--fest-primary-gradient) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px var(--fest-crimson-glow);
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/public/index.blade.php ENDPATH**/ ?>