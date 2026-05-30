<div class="tab-content-section active" id="tab-info">
<div class="tgi-wrap">

    <?php
        $hoy = \Carbon\Carbon::now();
        $inicioP = $oferta->fecha_inicio_programa;
        $finP    = $oferta->fecha_fin_programa;

        if ($finP && $hoy->gt($finP)) {
            $estadoLabel = 'Finalizado';
            $estadoClass = 'tgi-estado-fin';
        } elseif ($inicioP && $hoy->lt($inicioP)) {
            $estadoLabel = 'Por iniciar';
            $estadoClass = 'tgi-estado-prox';
        } else {
            $estadoLabel = 'En curso';
            $estadoClass = 'tgi-estado-activo';
        }

        $duracionDias = ($inicioP && $finP) ? $inicioP->diffInDays($finP) : null;
        $nombrePrograma = $oferta->programa->nombre ?? ($oferta->posgrado->nombre ?? null);

        $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
        $fmtFecha = function($date) use ($meses) {
            if (!$date) return null;
            $d = \Carbon\Carbon::parse($date);
            return $d->day . ' de ' . $meses[$d->month - 1] . ' de ' . $d->year;
        };
    ?>

    
    <div class="tgi-banner" style="--tgi-brand: <?php echo e($brandColor); ?>; border-top: 4px solid <?php echo e($brandColor); ?>;">
        <div class="tgi-banner-left">
            <div class="tgi-banner-icon" style="background: rgba(<?php echo e($brandColorRgb); ?>,.12); color: <?php echo e($brandColor); ?>;">
                <i class="ri-graduation-cap-line"></i>
            </div>
            <div class="tgi-banner-text">
                <div class="tgi-banner-code"><?php echo e($oferta->codigo); ?></div>
                <?php if($nombrePrograma): ?>
                    <div class="tgi-banner-name"><?php echo e($nombrePrograma); ?></div>
                <?php endif; ?>
                <div class="tgi-banner-pills">
                    <?php if($oferta->sucursal): ?>
                        <span class="tgi-pill tgi-pill-gray"><i class="ri-map-pin-line"></i> <?php echo e($oferta->sucursal->nombre); ?></span>
                    <?php endif; ?>
                    <?php if($oferta->modalidad): ?>
                        <span class="tgi-pill tgi-pill-gray"><i class="ri-wifi-line"></i> <?php echo e($oferta->modalidad->nombre); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="tgi-banner-right">
            <span class="tgi-estado <?php echo e($estadoClass); ?>">
                <span class="tgi-estado-dot"></span>
                <?php echo e($estadoLabel); ?>

            </span>
            <?php if($duracionDias): ?>
                <div class="tgi-duracion">
                    <i class="ri-time-line"></i>
                    <?php echo e($duracionDias); ?> días de programa
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="tgi-kpi-row">
        <div class="tgi-kpi">
            <div class="tgi-kpi-ico" style="background:rgba(59,130,246,.1);color:#2563eb;"><i class="ri-calendar-check-line"></i></div>
            <div class="tgi-kpi-body">
                <div class="tgi-kpi-lbl">Gestión</div>
                <div class="tgi-kpi-val"><?php echo e($oferta->gestion); ?></div>
            </div>
        </div>
        <div class="tgi-kpi">
            <div class="tgi-kpi-ico" style="background:rgba(245,158,11,.1);color:#d97706;"><i class="ri-git-branch-line"></i></div>
            <div class="tgi-kpi-body">
                <div class="tgi-kpi-lbl">Versión / Grupo</div>
                <div class="tgi-kpi-val">v<?php echo e($oferta->version); ?> — G<?php echo e($oferta->grupo); ?></div>
            </div>
        </div>
        <div class="tgi-kpi">
            <div class="tgi-kpi-ico" style="background:rgba(139,92,246,.1);color:#7c3aed;"><i class="ri-building-line"></i></div>
            <div class="tgi-kpi-body">
                <div class="tgi-kpi-lbl">Sucursal</div>
                <div class="tgi-kpi-val"><?php echo e($oferta->sucursal->nombre ?? '—'); ?></div>
            </div>
        </div>
        <div class="tgi-kpi">
            <div class="tgi-kpi-ico" style="background:rgba(var(--brand-color-rgb),.1);color:<?php echo e($brandColor); ?>;"><i class="ri-route-line"></i></div>
            <div class="tgi-kpi-body">
                <div class="tgi-kpi-lbl">Fase actual</div>
                <div class="tgi-kpi-val"><?php echo e($oferta->fase->nombre ?? '—'); ?></div>
            </div>
        </div>
    </div>

    
    <div class="tgi-grid">

        
        <div class="tgi-col">

            
            <div class="tgi-card">
                <div class="tgi-card-hdr">
                    <div class="tgi-card-hdr-icon" style="background:rgba(var(--brand-color-rgb),.1);color:<?php echo e($brandColor); ?>;"><i class="ri-calendar-event-line"></i></div>
                    <span>Calendario del Programa</span>
                </div>
                <div class="tgi-timeline">

                    <div class="tgi-tl-node">
                        <div class="tgi-tl-dot tgi-tl-dot-green">
                            <i class="ri-door-open-line"></i>
                        </div>
                        <div class="tgi-tl-connector"></div>
                        <div class="tgi-tl-content">
                            <div class="tgi-tl-label">Apertura de inscripciones</div>
                            <?php $fInsc = $fmtFecha($oferta->fecha_inicio_inscripciones); ?>
                            <div class="tgi-tl-date <?php echo e($fInsc ? '' : 'tgi-tl-date-empty'); ?>">
                                <?php echo e($fInsc ?? 'No definida'); ?>

                            </div>
                        </div>
                    </div>

                    <div class="tgi-tl-node">
                        <div class="tgi-tl-dot tgi-tl-dot-orange">
                            <i class="ri-play-circle-line"></i>
                        </div>
                        <div class="tgi-tl-connector"></div>
                        <div class="tgi-tl-content">
                            <div class="tgi-tl-label">Inicio de clases</div>
                            <?php $fIni = $fmtFecha($inicioP); ?>
                            <div class="tgi-tl-date <?php echo e($fIni ? '' : 'tgi-tl-date-empty'); ?>">
                                <?php echo e($fIni ?? 'No definido'); ?>

                            </div>
                        </div>
                    </div>

                    <div class="tgi-tl-node tgi-tl-last">
                        <div class="tgi-tl-dot tgi-tl-dot-red">
                            <i class="ri-flag-line"></i>
                        </div>
                        <div class="tgi-tl-content">
                            <div class="tgi-tl-label">Cierre del programa</div>
                            <?php $fFin = $fmtFecha($finP); ?>
                            <div class="tgi-tl-date <?php echo e($fFin ? '' : 'tgi-tl-date-empty'); ?>">
                                <?php echo e($fFin ?? 'No definido'); ?>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            
            <div class="tgi-card tgi-card-mt">
                <div class="tgi-card-hdr">
                    <div class="tgi-card-hdr-icon" style="background:rgba(99,102,241,.1);color:#6366f1;"><i class="ri-settings-3-line"></i></div>
                    <span>Configuración Académica</span>
                </div>
                <div class="tgi-acad-grid">
                    <div class="tgi-acad-stat">
                        <div class="tgi-acad-ico" style="background:rgba(99,102,241,.1);color:#6366f1;"><i class="ri-layout-grid-line"></i></div>
                        <div class="tgi-acad-num"><?php echo e($oferta->n_modulos); ?></div>
                        <div class="tgi-acad-lbl">Módulos</div>
                    </div>
                    <div class="tgi-acad-stat">
                        <div class="tgi-acad-ico" style="background:rgba(20,184,166,.1);color:#0d9488;"><i class="ri-slideshow-line"></i></div>
                        <div class="tgi-acad-num"><?php echo e($oferta->cantidad_sesiones); ?></div>
                        <div class="tgi-acad-lbl">Sesiones</div>
                    </div>
                    <div class="tgi-acad-stat">
                        <div class="tgi-nota-circle" style="background:<?php echo e($brandColor); ?>;box-shadow:0 4px 14px rgba(<?php echo e($brandColorRgb); ?>,.4);">
                            <span class="tgi-nota-val" style="color:<?php echo e($brandContrastColor); ?>;"><?php echo e($oferta->nota_minima); ?></span>
                            <span class="tgi-nota-sub" style="color:<?php echo e($brandContrastColor); ?>;">pts</span>
                        </div>
                        <div class="tgi-acad-lbl" style="margin-top:.6rem;">Nota mínima</div>
                    </div>
                </div>
            </div>

        </div>

        
        <div class="tgi-col">

            
            <div class="tgi-card">
                <div class="tgi-card-hdr">
                    <div class="tgi-card-hdr-icon" style="background:rgba(var(--brand-color-rgb),.1);color:<?php echo e($brandColor); ?>;"><i class="ri-team-line"></i></div>
                    <span>Equipo Responsable</span>
                    <button type="button" class="tgi-edit-btn ms-auto"
                        data-bs-toggle="modal" data-bs-target="#modalEditarResponsables"
                        title="Editar responsables">
                        <i class="ri-pencil-line"></i> Editar
                    </button>
                </div>
                <div class="tgi-responsables">

                    <?php if($respAcademico): ?>
                        <div class="tgi-resp">
                            <div class="tgi-resp-avatar" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);">
                                <?php echo e(strtoupper(substr($respAcademico, 0, 1))); ?>

                            </div>
                            <div class="tgi-resp-info">
                                <div class="tgi-resp-rol">Coordinador Académico</div>
                                <div class="tgi-resp-nombre"><?php echo e($respAcademico); ?></div>
                            </div>
                            <div class="tgi-resp-tag" style="background:rgba(59,130,246,.1);color:#2563eb;">
                                <i class="ri-book-open-line"></i> Académico
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="tgi-empty-resp">
                            <i class="ri-user-unfollow-line"></i>
                            <span>Coordinador académico sin asignar</span>
                        </div>
                    <?php endif; ?>

                    <?php if($respMarketing): ?>
                        <div class="tgi-resp">
                            <div class="tgi-resp-avatar" style="background:linear-gradient(135deg,<?php echo e($brandColor); ?>,#c96004);">
                                <?php echo e(strtoupper(substr($respMarketing, 0, 1))); ?>

                            </div>
                            <div class="tgi-resp-info">
                                <div class="tgi-resp-rol">Coordinador de Marketing</div>
                                <div class="tgi-resp-nombre"><?php echo e($respMarketing); ?></div>
                            </div>
                            <div class="tgi-resp-tag" style="background:rgba(var(--brand-color-rgb),.1);color:<?php echo e($brandColor); ?>;">
                                <i class="ri-megaphone-line"></i> Marketing
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="tgi-empty-resp">
                            <i class="ri-user-unfollow-line"></i>
                            <span>Coordinador de marketing sin asignar</span>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            
            <div class="tgi-card tgi-card-mt">
                <div class="tgi-card-hdr">
                    <div class="tgi-card-hdr-icon" style="background:rgba(var(--brand-color-rgb),.1);color:<?php echo e($brandColor); ?>;"><i class="ri-folder-open-line"></i></div>
                    <span>Documentos Adjuntos</span>
                    <button type="button" class="tgi-edit-btn ms-auto"
                        data-bs-toggle="modal" data-bs-target="#modalEditarDocumentos"
                        title="Gestionar ambos documentos">
                        <i class="ri-upload-2-line"></i> Gestionar
                    </button>
                </div>
                <div class="tgi-docs">

                    
                    <?php if($oferta->portada): ?>
                        <div class="tgi-doc">
                            <div class="tgi-doc-thumb">
                                <?php if(preg_match('/\.(jpe?g|png|gif|webp)$/i', $oferta->portada)): ?>
                                    <img src="<?php echo e(asset('storage/' . $oferta->portada)); ?>" alt="Portada">
                                <?php else: ?>
                                    <i class="ri-image-line"></i>
                                <?php endif; ?>
                            </div>
                            <div class="tgi-doc-info">
                                <div class="tgi-doc-name">Imagen de portada</div>
                                <div class="tgi-doc-sub">Archivo adjunto</div>
                                <a href="<?php echo e(asset('storage/' . $oferta->portada)); ?>" target="_blank" class="tgi-doc-link">
                                    <i class="ri-download-2-line"></i> Descargar
                                </a>
                            </div>
                            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.35rem;">
                                <i class="ri-checkbox-circle-fill tgi-doc-ok"></i>
                                <button type="button" class="tgi-edit-btn"
                                    onclick="abrirModalDocSolo('portada')"
                                    title="Reemplazar portada"
                                    style="font-size:.7rem;padding:.2rem .55rem;">
                                    <i class="ri-edit-line"></i> Editar
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="tgi-doc-empty" style="cursor:pointer;" onclick="abrirModalDocSolo('portada')" title="Subir portada">
                            <i class="ri-image-add-line"></i>
                            <span>Sin portada — <u>Agregar</u></span>
                        </div>
                    <?php endif; ?>

                    
                    <?php if($oferta->certificado): ?>
                        <div class="tgi-doc">
                            <div class="tgi-doc-thumb tgi-doc-thumb-pdf">
                                <?php if(preg_match('/\.(jpe?g|png|gif|webp)$/i', $oferta->certificado)): ?>
                                    <img src="<?php echo e(asset('storage/' . $oferta->certificado)); ?>" alt="Certificado">
                                <?php else: ?>
                                    <i class="ri-file-pdf-line" style="color:#ef4444;"></i>
                                <?php endif; ?>
                            </div>
                            <div class="tgi-doc-info">
                                <div class="tgi-doc-name">Certificado base</div>
                                <div class="tgi-doc-sub">Plantilla oficial</div>
                                <a href="<?php echo e(asset('storage/' . $oferta->certificado)); ?>" target="_blank" class="tgi-doc-link">
                                    <i class="ri-download-2-line"></i> Descargar
                                </a>
                            </div>
                            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.35rem;">
                                <i class="ri-checkbox-circle-fill tgi-doc-ok"></i>
                                <button type="button" class="tgi-edit-btn"
                                    onclick="abrirModalDocSolo('certificado')"
                                    title="Reemplazar certificado"
                                    style="font-size:.7rem;padding:.2rem .55rem;">
                                    <i class="ri-edit-line"></i> Editar
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="tgi-doc-empty" style="cursor:pointer;" onclick="abrirModalDocSolo('certificado')" title="Subir certificado">
                            <i class="ri-file-text-line"></i>
                            <span>Sin certificado — <u>Agregar</u></span>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>

</div>
</div>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/ofertas-academicas/partials/ofertas-detalle/tab-info.blade.php ENDPATH**/ ?>