<?php $__env->startSection('title', 'Add Group Member'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid px-lg-4">
        <div class="mb-4">
            <a href="<?php echo e(route('admin.members.index')); ?>" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Back to Members
            </a>
            <h2 class="h4 fw-bold mt-2 mb-1">Add Group Member</h2>
            <p class="text-muted small mb-0">Register a new participant and assign them to a festival group.</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card admin-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="<?php echo e(route('admin.members.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Participating Group / House <span
                                            class="text-danger">*</span></label>
                                    <select name="group_id" class="form-select" required>
                                        <option value="">Select Group...</option>
                                        <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($grp->id); ?>" <?php echo e(old('group_id') == $grp->id ? 'selected' : ''); ?>>
                                                <?php echo e($grp->name); ?> (<?php echo e($grp->code); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Section (Junior / Senior) <span
                                            class="text-danger">*</span></label>
                                    <select name="section_id" class="form-select" required>
                                        <option value="">Select Section...</option>
                                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($sec->id); ?>" <?php echo e(old('section_id') == $sec->id ? 'selected' : ''); ?>>
                                                <?php echo e($sec->name); ?> (<?php echo e($sec->code); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">UID <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="uid" class="form-control"
                                        placeholder="e.g. UID1024" value="<?php echo e(old('uid')); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Chest Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="chest_number" class="form-control"
                                        placeholder="e.g. 101 or C-14" value="<?php echo e(old('chest_number')); ?>" required>
                                    <small class="text-muted">Unique participant chest number for competition entries.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Member Full Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Full legal or registered student name" value="<?php echo e(old('name')); ?>"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Class <span
                                            class="text-danger">*</span></label>
                                    <select name="class_year" class="form-select" required>
                                        <option value="">Select Class...</option>
                                        <?php $__currentLoopData = ['H1', 'H2', 'D1', 'D2', 'D3', 'D4']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($cls); ?>" <?php echo e(old('class_year') == $cls ? 'selected' : ''); ?>><?php echo e($cls); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                            <a href="<?php echo e(route('admin.members.index')); ?>"
                                                class="btn btn-light px-4">Cancel</a>
                                            <button type="submit" class="btn btn-teal text-white px-5"
                                                style="background-color: #9b111e;">
                                                <i class="bi bi-check-lg me-1"></i> Save Member
                                            </button>
                                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Laravel Project\college-fest\resources\views/admin/members/create.blade.php ENDPATH**/ ?>