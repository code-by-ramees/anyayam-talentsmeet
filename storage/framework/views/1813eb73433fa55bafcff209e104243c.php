<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', \App\Models\Setting::get('fest_name', "Masa Talents' Meet 26") . " — " . \App\Models\Setting::get('college_name', "Shamsul Ulama Islamic & Arts College")); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">

    <!-- Bootstrap 5.3 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Manjari:wght@400;700&family=Baloo+Chettan+2:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Official Masa Talents' Meet 26 Palette (Theme: JUSTICE — അന്യായം) */
            --fest-crimson: #9b111e;
            --fest-crimson-dark: #6e0b14;
            --fest-crimson-glow: rgba(155, 17, 30, 0.28);
            --fest-wine: #5c0617;
            --fest-wine-dark: #3a030d;
            --fest-ruby: #b91c1c;
            --fest-black: #0c0c0e;
            --fest-dark: #151518;
            --fest-surface: #1e1e24;
            --fest-sand: #f5f0e8;
            --fest-sand-light: #faf8f5;
            --fest-gold: #d97706;
            --fest-gold-light: #f59e0b;
            --fest-border: #e8e2d8;
            --fest-border-dark: #27272e;
            --fest-primary-gradient: linear-gradient(135deg, #9b111e 0%, #5c0617 100%);
            --fest-hero-gradient: linear-gradient(145deg, #150306 0%, #220509 50%, #0d0d10 100%);
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--fest-sand-light);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Top 5-color identity brand strip matching the poster bottom bar */
        .top-brand-strip {
            height: 5px;
            background: linear-gradient(90deg, 
                #5c0617 0%, #5c0617 25%, 
                #9b111e 25%, #9b111e 50%, 
                #0c0c0e 50%, #0c0c0e 75%, 
                #d8cfc4 75%, #d8cfc4 100%
            );
        }

        .navbar-fest {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--fest-border);
            padding: 0.75rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .brand-logo-img {
            height: 48px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(155, 17, 30, 0.2));
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .brand-logo-img:hover {
            transform: scale(1.08) rotate(-2deg);
        }

        .fest-card {
            border: 1px solid var(--fest-border);
            border-radius: 1.15rem;
            background: #ffffff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .fest-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(155, 17, 30, 0.08);
            border-color: rgba(155, 17, 30, 0.25);
        }

        .btn-fest-primary {
            background: var(--fest-primary-gradient);
            color: #ffffff;
            font-weight: 700;
            border-radius: 50rem;
            border: none;
            padding: 0.65rem 1.6rem;
            box-shadow: 0 4px 14px var(--fest-crimson-glow);
            transition: all 0.25s ease;
        }

        .btn-fest-primary:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #6e0b14 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(155, 17, 30, 0.4);
        }

        .btn-fest-outline {
            border: 2px solid var(--fest-crimson);
            color: var(--fest-crimson);
            font-weight: 700;
            border-radius: 50rem;
            padding: 0.6rem 1.4rem;
            background: transparent;
            transition: all 0.25s ease;
        }

        .btn-fest-outline:hover {
            background: var(--fest-crimson);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 14px var(--fest-crimson-glow);
        }

        .text-crimson {
            color: var(--fest-crimson) !important;
        }

        .bg-crimson {
            background-color: var(--fest-crimson) !important;
        }

        .bg-crimson-subtle {
            background-color: rgba(155, 17, 30, 0.08) !important;
            color: var(--fest-crimson) !important;
        }

        .border-crimson {
            border-color: var(--fest-crimson) !important;
        }

        /* Rank Badges */
        .rank-badge-1 {
            background: linear-gradient(135deg, #fbbf24, #d97706);
            color: white;
            border-radius: 50%;
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.15rem;
            box-shadow: 0 4px 14px rgba(217, 119, 6, 0.45);
            animation: pulseGold 2.5s infinite;
        }

        .rank-badge-2 {
            background: linear-gradient(135deg, #cbd5e1, #64748b);
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.05rem;
            box-shadow: 0 3px 10px rgba(100, 116, 139, 0.3);
        }

        .rank-badge-3 {
            background: linear-gradient(135deg, #d97706, #78350f);
            color: white;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 3px 10px rgba(120, 53, 15, 0.3);
        }

        /* Global Keyframe Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes floatFist {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-10px) rotate(-1.5deg);
            }
        }

        @keyframes pulseCrimson {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(155, 17, 30, 0.5);
            }
            50% {
                box-shadow: 0 0 0 12px rgba(155, 17, 30, 0);
            }
        }

        @keyframes pulseGold {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.5);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(245, 158, 11, 0);
            }
        }

        @keyframes shimmerBanner {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease forwards;
        }

        .animate-slide-up {
            animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-float {
            animation: floatFist 4s ease-in-out infinite;
        }

        .pulse-badge {
            animation: pulseCrimson 2s infinite;
        }

        /* Malayalam font class */
        .font-malayalam {
            font-family: 'Manjari', 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Identity Strip -->
    <div class="top-brand-strip"></div>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-fest sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo e(route('public.index')); ?>">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Masa Talents' Meet Logo" class="brand-logo-img">
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-extrabold text-dark d-block lh-1" style="font-size: 1.15rem; letter-spacing: -0.5px;">
                            MASA TALENTS <span class="text-crimson">'26</span>
                        </span>
                        <span class="badge bg-crimson text-white rounded-pill px-2 py-0" style="font-size: 0.65rem;">JUSTICE</span>
                    </div>
                    <small class="text-muted fs-xs fw-semibold text-uppercase d-block mt-1">
                        <?php echo e(\App\Models\Setting::get('college_name', "Shamsul Ulama Islamic & Arts College")); ?>

                    </small>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="publicNav">
                <ul class="navbar-nav me-auto ms-lg-4 mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-bold <?php echo e(request()->routeIs('public.index') ? 'text-crimson active' : 'text-secondary'); ?>" href="<?php echo e(route('public.index')); ?>">
                            <i class="bi bi-trophy me-1"></i> Live Leaderboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-bold <?php echo e(request()->routeIs('public.rankings') ? 'text-crimson active' : 'text-secondary'); ?>" href="<?php echo e(route('public.rankings')); ?>">
                            <i class="bi bi-bar-chart-line me-1"></i> Overall & Section Ranks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-bold <?php echo e(request()->routeIs('public.results') ? 'text-crimson active' : 'text-secondary'); ?>" href="<?php echo e(route('public.results')); ?>">
                            <i class="bi bi-award me-1"></i> Published Results
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-bold <?php echo e(request()->routeIs('public.programs') ? 'text-crimson active' : 'text-secondary'); ?>" href="<?php echo e(route('public.programs')); ?>">
                            <i class="bi bi-calendar-event me-1"></i> Programs Schedule
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <!-- Group Portal Login Button -->
                    <a href="<?php echo e(route('group.login')); ?>" class="btn btn-outline-dark rounded-pill px-3 btn-sm fw-bold">
                        <i class="bi bi-people-fill me-1 text-crimson"></i> Group Portal
                    </a>

                    <!-- Super Admin Login Button -->
                    <a href="<?php echo e(route('admin.login')); ?>" class="btn btn-fest-primary rounded-pill px-3 btn-sm fw-bold">
                        <i class="bi bi-shield-lock-fill me-1"></i> Admin Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Global Alerts -->
    <div class="container mt-3">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-5 me-2"></i>
                <div><?php echo e(session('success')); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5 me-2"></i>
                <div><?php echo e(session('error')); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('info')): ?>
            <div class="alert alert-info alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill text-info fs-5 me-2"></i>
                <div><?php echo e(session('info')); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <main class="flex-grow-1 animate-fade-in">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white border-top border-dark py-5 mt-auto" style="background-color: var(--fest-black) !important;">
        <div class="container">
            <div class="row gy-4 align-items-center justify-content-between pb-4 border-bottom border-secondary border-opacity-25">
                <div class="col-md-6 text-center text-md-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-3 mb-2">
                        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Masa Logo" style="height: 52px; object-fit: contain;">
                        <div>
                            <h5 class="fw-bold mb-0 text-white"><?php echo e(\App\Models\Setting::get('fest_name', "Masa Talents' Meet 26")); ?></h5>
                            <div class="text-danger small font-malayalam fw-bold" style="color: #ff6b7a !important;">
                                <?php echo e(\App\Models\Setting::get('tagline', "“കല കലഹിക്കട്ടെ; സത്യം സംസാരിക്കട്ടെ”")); ?>

                            </div>
                        </div>
                    </div>
                    <p class="text-white-50 small mb-0 mt-2">
                        <?php echo e(\App\Models\Setting::get('college_name', "Shamsul Ulama Islamic & Arts College (SNEC)")); ?> &bull; Pathiyankara, Alappuzha
                    </p>
                    <p class="text-white-50 fs-xs mb-0">Organized by Manbaul Afnan Students' Association (MASA)</p>
                </div>

                <div class="col-md-5 text-center text-md-end">
                    <div class="d-flex justify-content-center justify-content-md-end gap-2 mb-3">
                        <a href="<?php echo e(route('public.rankings')); ?>" class="btn btn-outline-light btn-sm rounded-pill px-3">Live Standings</a>
                        <a href="<?php echo e(route('public.results')); ?>" class="btn btn-outline-light btn-sm rounded-pill px-3">Results</a>
                        <a href="<?php echo e(route('group.login')); ?>" class="btn btn-danger btn-sm rounded-pill px-3" style="background-color: var(--fest-crimson); border-color: var(--fest-crimson);">Group Login</a>
                    </div>
                    <div class="small text-white-50">
                        Theme: <strong class="text-white">JUSTICE — അന്യായം</strong> &bull; <?php echo e(\App\Models\Setting::get('edition', "Meet '26")); ?>

                    </div>
                </div>
            </div>

            <div class="text-center text-white-50 fs-xs pt-3">
                &copy; <?php echo e(date('Y')); ?> <?php echo e(\App\Models\Setting::get('fest_name', "Masa Talents' Meet 26")); ?> &bull; College Fest Management System
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\Laravel Project\college-fest\resources\views/layouts/app.blade.php ENDPATH**/ ?>