<?php $__env->startSection('title', 'Festival Settings'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid px-lg-4">
        <div class="mb-4">
            <h2 class="h4 fw-bold mb-1">Festival Global Settings</h2>
            <p class="text-muted small mb-0">Configure festival naming, institutional identity, tie-breaker rules, and
                portal permissions.</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card admin-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="<?php echo e(route('admin.settings.update')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                                <i class="bi bi-mortarboard-fill text-teal me-2" style="color: #9b111e;"></i> Festival &
                                Institution Identity
                            </h5>

                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Festival Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="fest_name" class="form-control"
                                        value="<?php echo e(old('fest_name', $settings['fest_name'])); ?>" required>
                                    <small class="text-muted">Displayed across header banners, public leaderboard, and
                                        portal titles.</small>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">College / Institution Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="college_name" class="form-control"
                                        value="<?php echo e(old('college_name', $settings['college_name'])); ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Festival Edition</label>
                                    <input type="text" name="edition" class="form-control"
                                        value="<?php echo e(old('edition', $settings['edition'])); ?>" placeholder="e.g. Edition 03">
                                </div>
                            </div>

                            <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                                <i class="bi bi-shield-lock-fill text-teal me-2" style="color: #9b111e;"></i> Group Portal
                                Permissions & Rules
                            </h5>

                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="allow_group_member_add" value="0">
                                            <input class="form-check-input" type="checkbox" name="allow_group_member_add"
                                                value="1" id="globalMemberReg" <?php echo e(old('allow_group_member_add', $settings['allow_group_member_add']) == '1' ? 'checked' : ''); ?>>
                                            <label class="form-check-label fw-bold text-dark" for="globalMemberReg">
                                                Allow Groups to Register Their Own Members
                                            </label>
                                        </div>
                                        <div class="small text-muted ps-4">
                                            When enabled, group leaders can register participants from their group portal.
                                            If disabled, member registration is locked to administrators only.
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="allow_event_registration" value="0">
                                            <input class="form-check-input" type="checkbox" name="allow_event_registration"
                                                value="1" id="globalEventReg" <?php echo e(old('allow_event_registration', $settings['allow_event_registration'] ?? '1') == '1' ? 'checked' : ''); ?>>
                                            <label class="form-check-label fw-bold text-dark" for="globalEventReg">
                                                Allow Groups to Register for Events / Programs
                                            </label>
                                        </div>
                                        <div class="small text-muted ps-4">
                                            When enabled, group leaders can register participants for programs and competitions from the group portal.
                                            If disabled, event registration is closed and locked for all groups.
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Tie-Breaker Rule</label>
                                    <select name="tie_breaker" class="form-select">
                                        <option value="most_first_prizes" <?php echo e(old('tie_breaker', $settings['tie_breaker']) === 'most_first_prizes' ? 'selected' : ''); ?>>
                                            Standard: Total Points &rarr; Most 1st Prizes &rarr; Most 2nd Prizes &rarr; Most
                                            3rd Prizes
                                        </option>
                                        <option value="total_points_only" <?php echo e(old('tie_breaker', $settings['tie_breaker']) === 'total_points_only' ? 'selected' : ''); ?>>
                                            Shared Rank on Equal Points
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                <button type="submit" class="btn btn-teal text-white px-5"
                                    style="background-color: #9b111e;">
                                    <i class="bi bi-save me-1"></i> Save Festival Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                    <div class="card admin-card border-0 shadow-sm p-4 bg-light mb-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i> About System
                            Settings</h6>
                        <p class="small text-muted mb-3">
                            These settings control global parameters across the entire College Fest Management System:
                        </p>
                        <ul class="small text-muted ps-3 mb-0">
                            <li class="mb-2">Changes take effect immediately across all public and group portal views.</li>
                            <li class="mb-2">Admin activity logs will track when settings are modified.</li>
                        </ul>
                    </div>

                    <div class="card admin-card border-0 shadow-sm p-4 bg-white">
                        <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-shield-lock-fill text-teal me-2"
                                style="color: #9b111e;"></i> Super Admin Password</h6>
                        <p class="small text-muted mb-3">
                            Logged in as <strong><?php echo e(auth()->user()->username ?? 'admin'); ?></strong>. You can change your
                            administrative login password at any time.
                        </p>
                        <a href="<?php echo e(route('admin.password.edit')); ?>" class="btn btn-outline-dark btn-sm rounded-pill py-2">
                            <i class="bi bi-key-fill me-1"></i> Change Admin Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/admin/settings.blade.php ENDPATH**/ ?>