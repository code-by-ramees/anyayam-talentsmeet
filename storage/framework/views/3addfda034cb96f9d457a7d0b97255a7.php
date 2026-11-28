<?php $__env->startSection('title', 'Edit Group: ' . $group->name); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid px-lg-4">
        <div class="mb-4">
            <a href="<?php echo e(route('admin.groups.index')); ?>" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Back to Groups
            </a>
            <h2 class="h4 fw-bold mt-2 mb-1">Edit Group: <?php echo e($group->name); ?></h2>
            <p class="text-muted small mb-0">Update group metadata, festival section, permissions, and reset login password.
            </p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card admin-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="<?php echo e(route('admin.groups.update', $group)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                                <i class="bi bi-people-fill text-teal me-2" style="color: #9b111e;"></i> Group Details
                            </h5>

                            <div class="row g-3 mb-4">
                                    <div class="col-md-5">
                                        <label class="form-label fw-semibold">Group / House Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control"
                                            value="<?php echo e(old('name', $group->name)); ?>" required>
                                    </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Group Code <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="code" class="form-control text-uppercase"
                                                value="<?php echo e(old('code', $group->code)); ?>" required>
                                        </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Theme Color</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="color" name="color" class="form-control form-control-color"
                                                        value="<?php echo e(old('color', $group->color)); ?>">
                                                    <small class="text-muted">Badges & cards color</small>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Description</label>
                                                <textarea name="description" class="form-control"
                                                    rows="2"><?php echo e(old('description', $group->description)); ?></textarea>
                                            </div>
                                        </div>

                                        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                                            <i class="bi bi-key-fill text-teal me-2" style="color: #9b111e;"></i> Portal
                                            Account & Password Reset
                                        </h5>

                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Portal Username</label>
                                                <input type="text" class="form-control bg-light"
                                                    value="<?php echo e($group->user->username ?? 'No User'); ?>" readonly>
                                                <small class="text-muted">Usernames cannot be changed once assigned.</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Reset Password</label>
                                                <input type="password" name="password" class="form-control"
                                                    placeholder="Leave blank to keep current password">
                                                <small class="text-muted">Enter a new password (min. 6 characters) only if
                                                    resetting.</small>
                                            </div>
                                        </div>

                                        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                                            <i class="bi bi-sliders text-teal me-2" style="color: #9b111e;"></i> Status &
                                            Settings
                                        </h5>

                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Group Status <span
                                                        class="text-danger">*</span></label>
                                                <select name="status" class="form-select" required>
                                                    <option value="active" <?php echo e(old('status', $group->status) === 'active' ? 'selected' : ''); ?>>Active</option>
                                                    <option value="inactive" <?php echo e(old('status', $group->status) === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 d-flex align-items-center">
                                                <div class="form-check mt-3">
                                                    <input type="hidden" name="allow_member_registration" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="allow_member_registration" value="1" id="allowMemberReg" <?php echo e(old('allow_member_registration', $group->allow_member_registration) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label fw-semibold" for="allowMemberReg">
                                                        Allow group leader to register members
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                            <a href="<?php echo e(route('admin.groups.index')); ?>"
                                                class="btn btn-light px-4">Cancel</a>
                                            <button type="submit" class="btn btn-primary px-5">
                                                <i class="bi bi-save me-1"></i> Update Group
                                            </button>
                                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card admin-card border-0 shadow-sm p-4 bg-light mb-4">
                    <h6 class="fw-bold mb-3">Group Performance Summary</h6>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Sections:</span>
                        <span class="fw-bold">Bakalooriyya & Sanaviyya</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Total Registered Members:</span>
                        <span class="fw-bold"><?php echo e($group->members->count()); ?></span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Total Published Points:</span>
                        <span class="fw-bold text-success fs-5"><?php echo e($group->total_marks); ?></span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Awards Won:</span>
                        <span>
                            <span class="badge bg-warning text-dark"><i class="bi bi-trophy-fill"></i>
                                <?php echo e($group->first_prizes_count); ?></span>
                            <span class="badge bg-secondary"><i class="bi bi-award-fill"></i>
                                <?php echo e($group->second_prizes_count); ?></span>
                            <span class="badge bg-bronze text-white" style="background-color: #cd7f32;"><i
                                    class="bi bi-award"></i> <?php echo e($group->third_prizes_count); ?></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/admin/groups/edit.blade.php ENDPATH**/ ?>