<?php $__env->startSection('title', 'Mi Perfil'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo $__env->make('admin.profile.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="profile-page">

    <?php echo $__env->make('admin.profile.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="row g-4">

        <div class="col-12">
            <div class="profile-card">
                <div class="profile-card-header">
                    <?php echo $__env->make('admin.profile.tabs.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="profile-card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personal" role="tabpanel">
                            <?php echo $__env->make('admin.profile.tabs.personal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                        <div class="tab-pane" id="documentos" role="tabpanel">
                            <?php echo $__env->make('admin.profile.tabs.documentos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                        <div class="tab-pane" id="password" role="tabpanel">
                            <?php echo $__env->make('admin.profile.tabs.password', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                        <?php if($tieneMarketing): ?>
                        <div class="tab-pane" id="marketing" role="tabpanel">
                            <?php echo $__env->make('admin.profile.tabs.marketing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                        <div class="tab-pane" id="ofertas-activas" role="tabpanel">
                            <?php echo $__env->make('admin.profile.tabs.ofertas-activas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php echo $__env->make('admin.profile.modals.upload-foto', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('admin.profile.modals.upload-doc', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php if($tieneMarketing): ?>
    <?php echo $__env->make('admin.profile.modals.enlace-preinscripcion', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <?php if($tieneMarketing): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <?php endif; ?>
    <?php echo $__env->make('admin.profile.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/profile/index.blade.php ENDPATH**/ ?>