<?php $__env->startSection('title'); ?>
    Detalle Oferta Académica - <?php echo e($oferta->codigo); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $brandColor = $oferta->color ?? '#fc7b04';
        
        // Clean up hex code (remove # and trim whitespace)
        $hex = str_replace('#', '', trim($brandColor));
        
        // Handle short hex syntax (e.g. "f30" -> "ff3300")
        if (strlen($hex) == 3) {
            $hex = substr($hex, 0, 1) . substr($hex, 0, 1) .
                   substr($hex, 1, 1) . substr($hex, 1, 1) .
                   substr($hex, 2, 1) . substr($hex, 2, 1);
        }
        
        // Standardize to 6 digits hex, with fallback to default orange if invalid
        if (strlen($hex) != 6 || !ctype_xdigit($hex)) {
            $hex = 'fc7b04';
            $brandColor = '#fc7b04';
        }
        
        // Extract RGB values
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $brandColorRgb = "$r, $g, $b";
        
        // Calculate Relative Luminance (WCAG standard formula: L = 0.2126 * R + 0.7152 * G + 0.0722 * B)
        $rNorm = $r / 255;
        $gNorm = $g / 255;
        $bNorm = $b / 255;
        
        $rL = ($rNorm <= 0.03928) ? ($rNorm / 12.92) : pow(($rNorm + 0.055) / 1.055, 2.4);
        $gL = ($gNorm <= 0.03928) ? ($gNorm / 12.92) : pow(($gNorm + 0.055) / 1.055, 2.4);
        $bL = ($bNorm <= 0.03928) ? ($bNorm / 12.92) : pow(($bNorm + 0.055) / 1.055, 2.4);
        
        $luminance = 0.2126 * $rL + 0.7152 * $gL + 0.0722 * $bL;
        
        // WCAG threshold is 0.179. Above it, dark text is better; below it, light text.
        $brandContrastColor = ($luminance > 0.179) ? '#1e1e1e' : '#ffffff';
    ?>

    <div class="oferta-details-theme-wrapper" style="--brand-color: <?php echo e($brandColor); ?>; --brand-color-rgb: <?php echo e($brandColorRgb); ?>; --brand-contrast-color: <?php echo e($brandContrastColor); ?>; overflow: visible;">
        <div class="dept-page-header">
            <div class="container-fluid">
                <?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle.header-tabs', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

        <div class="container-fluid py-4" style="overflow: visible;">
            <div class="row" style="overflow: visible;">
                <div class="col-12" style="overflow: visible;">
                    <div class="oferta-detail-card" style="overflow: visible;">
                        <?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle.tab-info', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle.tab-modulos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle.tab-contable', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle.tab-finanzas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle.tab-inscripciones', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle.tab-plataforma', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle.modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?php echo e(URL::asset('build/libs/fullcalendar/index.global.min.js')); ?>"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/ofertas-academicas/detalle.blade.php ENDPATH**/ ?>