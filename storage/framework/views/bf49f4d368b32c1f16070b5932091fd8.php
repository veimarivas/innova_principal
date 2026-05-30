<?php if($c['total'] > 0): ?>
    <div class="fin-concepto-cell">
        <?php if($c['pagado'] >= $c['total']): ?>
            <span class="fin-concepto-amount ok">Bs <?php echo e(number_format($c['pagado'], 2)); ?></span>
            <span class="fin-chip fin-chip-ok"><i class="ri-check-line"></i> Pagado</span>
        <?php elseif($c['pagado'] > 0): ?>
            <span class="fin-concepto-amount parcial">Bs <?php echo e(number_format($c['pagado'], 2)); ?></span>
            <span class="fin-concepto-sub saldo">–Bs <?php echo e(number_format($c['pendiente'], 2)); ?></span>
            <span class="fin-chip fin-chip-pend"><i class="ri-time-line"></i> Parcial</span>
        <?php else: ?>
            <span class="fin-concepto-amount parcial" style="color:#94a3b8;">Bs <?php echo e(number_format($c['total'], 2)); ?></span>
            <span class="fin-chip fin-chip-pend"><i class="ri-alarm-warning-line"></i> Pendiente</span>
        <?php endif; ?>
    </div>
<?php else: ?>
    <span class="fin-muted" style="display:block;text-align:right;">—</span>
<?php endif; ?>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/ofertas-academicas/partials/ofertas-detalle/_concepto-cell.blade.php ENDPATH**/ ?>