<div class="ins-list-header">
    <span>#</span>
    <span>Estudiante / Programa</span>
    <span>Estado · Fecha</span>
</div>

<div class="ins-list" id="ins-list-body">
    <?php $__empty_1 = true; $__currentLoopData = $inscripciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ins): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $nombre  = trim(($ins->estudiante->persona->nombres ?? '') . ' ' . ($ins->estudiante->persona->apellido_paterno ?? ''));
            $inicial = mb_strtoupper(mb_substr($ins->estudiante->persona->nombres ?? 'E', 0, 1));
            $programa = $ins->ofertaAcademica->posgrado->nombre ?? '—';
            $sede     = $ins->ofertaAcademica->sucursal->nombre ?? '—';
            $tipo     = $ins->ofertaAcademica->posgrado->tipo->nombre ?? null;
            $ofertaId = $ins->ofertaAcademica->id ?? null;
        ?>
        <div class="ins-row">
            
            <div class="ins-idx"><?php echo e($loop->iteration); ?></div>

            
            <div class="ins-main">
                <div class="ins-top">
                    <div class="student-avatar"><?php echo e($inicial); ?></div>
                    <a href="<?php echo e(route('admin.estudiantes.verDetalle', $ins->estudiante->id)); ?>"
                       class="student-name-link">
                        <?php echo e($nombre); ?>

                    </a>
                    <?php if($tipo): ?>
                        <span class="tipo-pill"><?php echo e($tipo); ?></span>
                    <?php endif; ?>
                </div>
                <div class="ins-bottom">
                    <?php if($ofertaId): ?>
                        <a href="<?php echo e(route('admin.posgrads.ofertas.detalle', $ofertaId)); ?>"
                           class="program-link" title="<?php echo e($programa); ?>">
                            <i class="ri-graduation-cap-line" style="font-size:0.75rem;margin-right:3px;"></i><?php echo e($programa); ?>

                        </a>
                    <?php else: ?>
                        <span class="program-link" style="color:var(--dash-text-muted);cursor:default;"><?php echo e($programa); ?></span>
                    <?php endif; ?>
                    <span class="ins-sep">·</span>
                    <span class="sede-name">
                        <i class="ri-map-pin-line"></i><?php echo e($sede); ?>

                    </span>
                </div>
            </div>

            
            <div class="ins-meta">
                <div class="ins-meta-top">
                    <?php if($ins->estado === 'Inscrito'): ?>
                        <span class="estado-badge estado-inscrito">
                            <i class="ri-checkbox-circle-line"></i> Inscrito
                        </span>
                    <?php else: ?>
                        <span class="estado-badge estado-preinscrito">
                            <i class="ri-time-line"></i> Pre-Inscrito
                        </span>
                    <?php endif; ?>
                </div>
                <span class="fecha-text">
                    <i class="ri-calendar-event-line"></i>
                    <?php echo e(\Carbon\Carbon::parse($ins->fecha_registro)->format('d/m/Y')); ?>

                </span>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div style="padding: 56px 24px; text-align: center;">
            <i class="ri-inbox-line" style="font-size:3.2rem;color:#cbd5e1;display:block;margin-bottom:12px;"></i>
            <p style="font-weight:600;color:#64748b;margin-bottom:4px;">Sin resultados</p>
            <p style="color:#94a3b8;font-size:.88rem;margin:0;">No hay inscripciones para los filtros aplicados.</p>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/dashboard/partials/detalle-tabla.blade.php ENDPATH**/ ?>