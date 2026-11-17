<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Super Admin'); ?> — College Fest Management</title>
    
    <!-- Bootstrap 5.3 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --admin-nav-bg: #0f172a;
            --admin-accent: #0d9488;
            --admin-surface: #f1f5f9;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--admin-surface);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .admin-nav {
            background-color: var(--admin-nav-bg);
        }

        .nav-link-admin {
            color: #cbd5e1;
            font-weight: 500;
            padding: 0.5rem 0.85rem;
            border-radius: 0.5rem;
            font-size: 0.92rem;
            transition: all 0.2s ease;
        }

        .nav-link-admin:hover, .nav-link-admin.active {
            color: #ffffff;
            background-color: rgba(13, 148, 136, 0.25);
        }

        .admin-card {
            border: 1px solid #e2e8f0;
            border-radius: 0.85rem;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <nav class="navbar navbar-expand-xl admin-nav navbar-dark py-2 sticky-top border-bottom border-dark">
        <div class="container-fluid px-lg-4">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?php echo e(route('admin.dashboard')); ?>">
                <span class="badge bg-teal p-2 rounded-3 text-white" style="background-color: #0d9488 !important;">
                    <i class="bi bi-shield-lock-fill"></i>
                </span>
                <span>FEST ADMIN</span>
                <span class="badge bg-secondary fs-xs fw-normal">Super Admin</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-xl-0 ms-xl-2">
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard')); ?>">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin <?php echo e(request()->routeIs('admin.sections.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.sections.index')); ?>">
                            <i class="bi bi-layers me-1"></i> Sections
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin <?php echo e(request()->routeIs('admin.groups.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.groups.index')); ?>">
                            <i class="bi bi-people me-1"></i> Groups
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin <?php echo e(request()->routeIs('admin.members.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.members.index')); ?>">
                            <i class="bi bi-person-lines-fill me-1"></i> Members
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin <?php echo e(request()->routeIs('admin.categories.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.categories.index')); ?>">
                            <i class="bi bi-tags me-1"></i> Categories & Marks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin <?php echo e(request()->routeIs('admin.programs.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.programs.index')); ?>">
                            <i class="bi bi-calendar-check me-1"></i> Programs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin <?php echo e(request()->routeIs('admin.results.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.results.index')); ?>">
                            <i class="bi bi-award me-1"></i> Results
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin <?php echo e(request()->routeIs('admin.rankings.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.rankings.index')); ?>">
                            <i class="bi bi-trophy me-1"></i> Rankings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-admin <?php echo e(request()->routeIs('admin.settings.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.settings.index')); ?>">
                            <i class="bi bi-gear me-1"></i> Settings
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <a href="<?php echo e(route('public.index')); ?>" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3">
                        <i class="bi bi-eye me-1"></i> View Live Site
                    </a>

                    <form action="<?php echo e(route('admin.logout')); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Admin Alerts -->
    <div class="container-fluid px-lg-4 mt-3">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                <div><?php echo e(session('success')); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
                <div><?php echo e(session('error')); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-1"></i> Please fix the following errors:</div>
                <ul class="mb-0 ps-3">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($err); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>

    <main class="py-3 flex-grow-1">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="bg-white border-top py-3 text-center text-muted small mt-auto">
        <div class="container">
            College Fest Management System · Super Admin Panel &copy; <?php echo e(date('Y')); ?>

        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\Laravel Project\college-fest\resources\views/layouts/admin.blade.php ENDPATH**/ ?>