<!-- Tab: Documentos -->
<?php
    $persona = $estudiante->persona;

    $estadoDoc = function($archivo, $verificado) {
        if (!$archivo) return ['label' => 'Pendiente', 'cls' => 'pending', 'icon' => 'ri-add-circle-line'];
        if ($verificado) return ['label' => 'Aprobado', 'cls' => 'approved', 'icon' => 'ri-checkbox-circle-fill'];
        return ['label' => 'En revision', 'cls' => 'review', 'icon' => 'ri-time-line'];
    };

    $docsIdentidad = [
        [
            'nombre' => 'Carnet de Identidad',
            'icono' => 'ri-id-card-line',
            'archivo' => $persona->fotografia_carnet ?? null,
            'verificado' => $persona->carnet_verificado ?? false,
            'tipo' => 'fotografia_carnet',
        ],
        [
            'nombre' => 'Cert. Nacimiento',
            'icono' => 'ri-file-paper-line',
            'archivo' => $persona->fotografia_certificado_nacimiento ?? null,
            'verificado' => $persona->certificado_nacimiento_verificado ?? false,
            'tipo' => 'fotografia_certificado_nacimiento',
        ],
    ];

    $totalDocs = count($docsIdentidad);
    $verificados = 0;
    foreach ($docsIdentidad as $d) {
        if ($d['verificado']) $verificados++;
    }
    foreach ($estudios as $est) {
        $totalDocs += 2;
        if ($est->documento_academico_verificado) $verificados++;
        if ($est->documento_provision_verificado) $verificados++;
    }

    $pctDocs = $totalDocs > 0 ? ($verificados / $totalDocs) * 100 : 0;
    $pctDocsFormatted = number_format($pctDocs, 0);
?>

<style>
.doc-card-compact {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    transition: box-shadow 0.2s;
}
.doc-card-compact:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.doc-card-compact .doc-header {
    padding: 10px 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
}
.doc-card-compact .doc-body {
    padding: 10px 14px;
}

</style>

<div class="est-tabs-body" id="tab-documentos">
    <div style="padding: 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                <i class="ri-folder-shield-line" style="color: #fc7b04;"></i>
                Documentacion del Estudiante
            </h3>
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="flex: 1; max-width: 150px; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
                    <div style="height: 100%; background: linear-gradient(90deg, #fc7b04, #f97316); border-radius: 4px; width: <?php echo e($pctDocs); ?>%; transition: width 0.3s;"></div>
                </div>
                <span style="font-size: 0.875rem; font-weight: 700; color: #fc7b04;"><?php echo e($pctDocsFormatted); ?>%</span>
            </div>
        </div>

        
        <div style="margin-bottom: 32px;">
            <h4 style="font-size: 1rem; font-weight: 600; color: #1e293b; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <i class="ri-id-card-line" style="color: #fc7b04;"></i>
                Documentación del Estudiante
            </h4>
            <div class="row g-3">
                <?php $__currentLoopData = $docsIdentidad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $estado = $estadoDoc($doc['archivo'], $doc['verificado']);
                        $bgIcon = $estado['cls'] == 'approved' ? '#dcfce7' : ($estado['cls'] == 'review' ? '#e0f2fe' : '#fef3c7');
                        $colorIcon = $estado['cls'] == 'approved' ? '#16a34a' : ($estado['cls'] == 'review' ? '#0891b2' : '#d97706');
                        $bgBadge = $estado['cls'] == 'approved' ? '#dcfce7' : ($estado['cls'] == 'review' ? '#e0f2fe' : '#fef3c7');
                        $colorBadge = $estado['cls'] == 'approved' ? '#16a34a' : ($estado['cls'] == 'review' ? '#0891b2' : '#d97706');
                    ?>
                    <div class="col-md-6">
                        <div class="doc-card-compact">
                            <div class="doc-header">
                                <div style="width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; background: <?php echo e($bgIcon); ?>; color: <?php echo e($colorIcon); ?>; flex-shrink: 0;">
                                    <i class="<?php echo e($doc['icono']); ?>"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-size: 0.85rem; font-weight: 600; color: #1e293b;"><?php echo e($doc['nombre']); ?></div>
                                </div>
                                <span style="padding: 3px 8px; border-radius: 20px; font-size: 0.65rem; font-weight: 600; text-transform: uppercase; background: <?php echo e($bgBadge); ?>; color: <?php echo e($colorBadge); ?>;">
                                    <?php echo e($estado['label']); ?>

                                </span>
                            </div>
                            <div class="doc-body">
                                <?php if($doc['archivo']): ?>
                                    <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #f8fafc; border-radius: 8px; margin-bottom: 10px;">
                                        <div style="width: 32px; height: 32px; border-radius: 6px; background: #fee2e2; display: flex; align-items: center; justify-content: center; color: #dc2626; font-size: 1rem;">
                                            <i class="ri-file-pdf-fill"></i>
                                        </div>
                                        <div style="flex: 1; min-width: 0;">
                                            <div style="font-size: 0.78rem; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo e($doc['tipo']); ?>.pdf</div>
                                            <div style="font-size: 0.65rem; display: flex; align-items: center; gap: 4px; color: <?php echo e($doc['verificado'] ? '#16a34a' : '#d97706'); ?>;">
                                                <?php if($doc['verificado']): ?>
                                                    <i class="ri-shield-check-fill"></i> Verificado
                                                <?php else: ?>
                                                    <i class="ri-time-fill"></i> En revision
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                        <button type="button" class="btn btn-sm btn-action btn-action-view btn-ver-doc" 
                                                data-id="<?php echo e($estudiante->id); ?>" 
                                                data-tipo="<?php echo e($doc['tipo']); ?>"
                                                title="Visualizar"
                                                style="flex: 1; padding: 6px 10px; border-radius: 6px; font-size: 0.72rem; font-weight: 600; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px; min-width: 60px;">
                                            <i class="ri-eye-line"></i> Ver
                                        </button>
                                        <label style="flex: 1; padding: 6px 10px; border-radius: 6px; font-size: 0.72rem; font-weight: 600; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px; min-width: 60px;">
                                            <i class="ri-upload-line"></i> Cambiar
                                            <input type="file" class="d-none btn-subir-doc" 
                                                   data-id="<?php echo e($estudiante->id); ?>" 
                                                   data-tipo="<?php echo e($doc['tipo']); ?>"
                                                   accept=".pdf,.png,.jpg,.jpeg">
                                        </label>
                                        <?php if(!$doc['verificado']): ?>
                                        <button type="button" class="btn btn-sm btn-action btn-action-edit btn-verificar-doc" 
                                                data-id="<?php echo e($estudiante->id); ?>" 
                                                data-tipo="<?php echo e($doc['tipo']); ?>"
                                                title="Verificar"
                                                style="flex: 1; padding: 6px 10px; border-radius: 6px; font-size: 0.72rem; font-weight: 600; border: none; background: #22c55e; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px; min-width: 70px;">
                                            <i class="ri-check-line"></i> Aprobar
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <label style="display: block; border: 2px dashed #e2e8f0; border-radius: 8px; padding: 18px 12px; text-align: center; cursor: pointer; transition: all 0.2s; background: #f8fafc;">
                                        <input type="file" class="d-none btn-subir-doc" 
                                               data-id="<?php echo e($estudiante->id); ?>" 
                                               data-tipo="<?php echo e($doc['tipo']); ?>"
                                               accept=".pdf,.png,.jpg,.jpeg">
                                        <i class="ri-upload-cloud-line" style="font-size: 1.3rem; color: #64748b; margin-bottom: 4px; display: block;"></i>
                                        <p style="font-size: 0.75rem; color: #64748b; margin: 0;">
                                            <strong style="color: #fc7b04;">Subir archivo</strong> o arrastrar
                                        </p>
                                        <p style="font-size: 0.65rem; color: #94a3b8; margin: 2px 0 0;">PDF, PNG, JPG - max 2MB</p>
                                    </label>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
                <h4 style="font-size: 1rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px; margin: 0;">
                    <i class="ri-graduation-cap-line" style="color: #fc7b04;"></i>
                    Formación Académica
                    <span class="badge" style="background: #e2e8f0; color: #475569; font-size: 0.7rem; font-weight: 600;"><?php echo e($estudios->count()); ?></span>
                </h4>
                <button type="button" class="btn btn-sm btn-agregar-estudio" 
                        data-estudiante-id="<?php echo e($estudiante->id); ?>"
                        data-persona-id="<?php echo e($persona->id); ?>"
                        style="padding: 8px 16px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; border: none; background: #fc7b04; color: white; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="ri-add-line"></i> Agregar Estudio
                </button>
            </div>

            <?php $totalEstudios = $estudios->count(); ?>

            
            <?php if($estudioPrincipal): ?>
            <div class="" style="background: linear-gradient(135deg, #1a3a5c 0%, #1e5799 55%, #2e86de 100%); border-radius: 16px; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.25rem; position: relative; overflow: hidden; flex-wrap: wrap;">
                <div style="position: absolute; top: -40%; right: -4%; width: 220px; height: 220px; background: radial-gradient(circle, rgba(46,134,222,0.3) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
                <div style="width: 56px; height: 56px; border-radius: 12px; background: rgba(255,255,255,0.15); border: 1.5px solid rgba(255,255,255,0.22); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: #fff; flex-shrink: 0; position: relative; z-index: 1;">
                    <i class="ri-graduation-cap-fill"></i>
                </div>
                <div style="flex: 1; min-width: 0; position: relative; z-index: 1;">
                    <div style="font-size: 0.62rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.6); margin-bottom: 0.2rem;">Grado Académico Principal</div>
                    <div style="font-size: 1.15rem; font-weight: 700; color: #fff; font-family: 'Outfit', sans-serif; line-height: 1.2; margin-bottom: 0.3rem;"><?php echo e($estudioPrincipal->grado_academico?->nombre ?? '—'); ?></div>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem 1rem; font-size: 0.78rem; color: rgba(255,255,255,0.75);">
                        <span><i class="ri-briefcase-line"></i> <?php echo e($estudioPrincipal->profesion?->nombre ?? '—'); ?></span>
                        <span><i class="ri-building-line"></i> <?php echo e($estudioPrincipal->universidad?->nombre ?? '—'); ?><?php echo e($estudioPrincipal->universidad?->sigla ? ' (' . $estudioPrincipal->universidad->sigla . ')' : ''); ?></span>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.4rem; flex-shrink: 0; position: relative; z-index: 1;">
                    <span style="display: inline-flex; align-items: center; gap: 0.28rem; padding: 0.22rem 0.7rem; border-radius: 20px; font-size: 0.68rem; font-weight: 700; background: rgba(255,215,0,0.2); color: #ffd700; border: 1px solid rgba(255,215,0,0.35);">
                        <i class="ri-star-fill"></i> Principal
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 0.28rem; padding: 0.22rem 0.7rem; border-radius: 20px; font-size: 0.68rem; font-weight: 700; <?php echo e($estudioPrincipal->estado === 'Concluido' ? 'background: rgba(46,154,110,0.22); color: #7eefc4; border: 1px solid rgba(46,154,110,0.38);' : 'background: rgba(251,191,36,0.2); color: #fbbf24; border: 1px solid rgba(251,191,36,0.35);'); ?>">
                        <i class="ri-<?php echo e($estudioPrincipal->estado === 'Concluido' ? 'checkbox-circle-fill' : 'time-line'); ?>"></i>
                        <?php echo e($estudioPrincipal->estado); ?>

                    </span>
                </div>
            </div>
            <?php else: ?>
            <div style="background: rgba(148,163,184,0.06); border: 1.5px dashed rgba(148,163,184,0.3); border-radius: 16px; padding: 1.5rem; text-align: center; color: #64748b; margin-bottom: 1.25rem;">
                <i style="font-size: 2rem; opacity: 0.4; display: block; margin-bottom: 0.5rem;" class="ri-graduation-cap-line"></i>
                <p style="margin: 0; font-size: 0.82rem;">No hay estudio marcado como principal. Registre uno y márquelo como principal.</p>
            </div>
            <?php endif; ?>

            
            <?php if($totalEstudios > 0): ?>
            <div class="est-table-wrap">
                <table class="est-table">
                    <thead>
                        <tr>
                            <th style="width:40px;">#</th>
                            <th>Grado Académico</th>
                            <th>Profesión</th>
                            <th>Universidad</th>
                            <th style="width:110px;">Estado</th>
                            <th style="width:80px;text-align:center;">Docs</th>
                            <th style="width:80px;text-align:center;">Principal</th>
                            <th style="width:110px;text-align:center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $estudios->sortByDesc('principal'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $est): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center text-muted" style="font-size:0.75rem;"><?php echo e($index + 1); ?></td>
                            <td style="font-weight:600;"><?php echo e($est->grado_academico?->nombre ?? '—'); ?></td>
                            <td style="font-size:0.84rem;"><?php echo e($est->profesion?->nombre ?? '—'); ?></td>
                            <td style="font-size:0.84rem;">
                                <?php echo e($est->universidad?->nombre ?? '—'); ?>

                                <?php if($est->universidad?->sigla): ?>
                                    <span style="font-size:0.7rem;color:#64748b;"> (<?php echo e($est->universidad->sigla); ?>)</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="estado-badge-est <?php echo e($est->estado === 'Concluido' ? 'concluido' : 'en-desarrollo'); ?>">
                                    <i class="ri-<?php echo e($est->estado === 'Concluido' ? 'check-line' : 'time-line'); ?>"></i>
                                    <?php echo e($est->estado); ?>

                                </span>
                            </td>
                            <td class="text-center" style="font-size:1rem;">
                                <span title="Doc. Académico" style="color:<?php echo e($est->documento_academico ? ($est->documento_academico_verificado ? '#15803d' : '#d97706') : '#94a3b8'); ?>;">
                                    <i class="<?php echo e($est->documento_academico ? ($est->documento_academico_verificado ? 'ri-file-check-fill' : 'ri-file-warning-fill') : 'ri-file-close-fill'); ?>"></i>
                                </span>
                                <span title="Provisión Nacional" style="color:<?php echo e($est->documento_provision_nacional ? ($est->documento_provision_verificado ? '#15803d' : '#d97706') : '#94a3b8'); ?>;margin-left:2px;">
                                    <i class="<?php echo e($est->documento_provision_nacional ? ($est->documento_provision_verificado ? 'ri-file-check-fill' : 'ri-file-warning-fill') : 'ri-file-close-fill'); ?>"></i>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if($est->principal): ?>
                                    <span style="display:inline-flex;align-items:center;gap:0.28rem;padding:0.22rem 0.7rem;border-radius:20px;font-size:0.62rem;font-weight:700;background:rgba(255,215,0,0.2);color:#ca8a04;border:1px solid rgba(255,215,0,0.35);"><i class="ri-star-fill"></i> Sí</span>
                                <?php else: ?>
                                    <span style="font-size:0.72rem;color:#64748b;">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display:flex;gap:0.3rem;justify-content:center;">
                                    <button class="est-table-action est-action-docs btn-toggle-docs"
                                        data-est-id="<?php echo e($est->id); ?>"
                                        title="Ver/gestionar documentos">
                                        <i class="ri-folder-open-line"></i>
                                    </button>
                                    <?php if(!$est->principal): ?>
                                    <button class="est-table-action est-action-principal btn-set-principal-estudio"
                                        data-estudiante-id="<?php echo e($estudiante->id); ?>"
                                        data-estudio-id="<?php echo e($est->id); ?>"
                                        title="Marcar como principal">
                                        <i class="ri-star-line"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button class="est-table-action est-action-del btn-eliminar-estudio"
                                        data-estudiante-id="<?php echo e($estudiante->id); ?>"
                                        data-estudio-id="<?php echo e($est->id); ?>"
                                        title="Eliminar estudio">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            
            <?php $__currentLoopData = $estudios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $est): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $tieneDA  = !empty($est->documento_academico);
                $tieneDP  = !empty($est->documento_provision_nacional);
            ?>
            <div class="est-docs-panel" id="est-docs-panel-<?php echo e($est->id); ?>" style="display:none;margin-top:0.75rem;border:1px solid var(--est-border);border-radius:var(--radius-md);overflow:hidden;">
                <div style="padding:0.65rem 1rem;background:linear-gradient(135deg,var(--est-primary-light),rgba(252,123,4,0.03));border-bottom:1px solid var(--est-border);display:flex;align-items:center;gap:0.5rem;">
                    <i class="ri-folder-open-line" style="color:var(--est-primary);"></i>
                    <span style="font-size:0.78rem;font-weight:700;color:var(--est-text);">Documentos — <?php echo e($est->grado_academico?->nombre ?? 'Estudio #'.$est->id); ?></span>
                </div>
                <div class="est-docs-inner">

                    
                    <div class="est-doc-card">
                        <div class="est-doc-card-icon"
                            style="background:<?php echo e($tieneDA ? 'rgba(34,197,94,0.1)' : 'rgba(148,163,184,0.1)'); ?>;color:<?php echo e($tieneDA ? '#15803d' : '#94a3b8'); ?>;">
                            <i class="ri-file-text-line"></i>
                        </div>
                        <div class="est-doc-card-body">
                            <div class="est-doc-card-title">Documento Académico</div>
                            <div class="est-doc-card-status <?php echo e($tieneDA ? ($est->documento_academico_verificado ? 'verificado' : 'pendiente') : 'sin-doc'); ?>">
                                <i class="<?php echo e($tieneDA ? ($est->documento_academico_verificado ? 'ri-checkbox-circle-fill' : 'ri-time-line') : 'ri-upload-line'); ?>"></i>
                                <?php echo e($tieneDA ? ($est->documento_academico_verificado ? 'Verificado' : 'Pendiente de verificación') : 'Sin documento subido'); ?>

                            </div>
                        </div>
                        <div class="est-doc-actions">
                            <button class="est-doc-btn est-doc-btn-view btn-ver-doc"
                                style="<?php echo e($tieneDA ? 'display:flex;' : 'display:none;'); ?>"
                                data-id="<?php echo e($estudiante->id); ?>"
                                data-tipo="documento_academico"
                                data-estudio-id="<?php echo e($est->id); ?>"
                                title="Ver documento">
                                <i class="ri-eye-line"></i>
                            </button>
                            <button class="est-doc-btn <?php echo e($est->documento_academico_verificado ? 'est-doc-btn-uncheck' : 'est-doc-btn-check'); ?> btn-verificar-doc"
                                style="<?php echo e($tieneDA ? 'display:flex;' : 'display:none;'); ?>"
                                data-id="<?php echo e($estudiante->id); ?>"
                                data-tipo="documento_academico"
                                data-estudio-id="<?php echo e($est->id); ?>"
                                title="<?php echo e($est->documento_academico_verificado ? 'Quitar verificación' : 'Verificar documento'); ?>">
                                <i class="<?php echo e($est->documento_academico_verificado ? 'ri-close-circle-line' : 'ri-check-line'); ?>"></i>
                            </button>
                            <label class="est-doc-btn est-doc-btn-upload" title="<?php echo e($tieneDA ? 'Reemplazar archivo' : 'Subir archivo'); ?>" style="cursor:pointer;display:flex;align-items:center;justify-content:center;">
                                <i class="ri-upload-2-line"></i>
                                <input type="file" accept=".pdf,.jpg,.jpeg,.png"
                                    class="d-none btn-subir-doc"
                                    data-id="<?php echo e($estudiante->id); ?>"
                                    data-tipo="documento_academico"
                                    data-estudio-id="<?php echo e($est->id); ?>">
                            </label>
                        </div>
                    </div>

                    
                    <div class="est-doc-card">
                        <div class="est-doc-card-icon"
                            style="background:<?php echo e($tieneDP ? 'rgba(34,197,94,0.1)' : 'rgba(148,163,184,0.1)'); ?>;color:<?php echo e($tieneDP ? '#15803d' : '#94a3b8'); ?>;">
                            <i class="ri-file-shield-line"></i>
                        </div>
                        <div class="est-doc-card-body">
                            <div class="est-doc-card-title">Provisión Nacional</div>
                            <div class="est-doc-card-status <?php echo e($tieneDP ? ($est->documento_provision_verificado ? 'verificado' : 'pendiente') : 'sin-doc'); ?>">
                                <i class="<?php echo e($tieneDP ? ($est->documento_provision_verificado ? 'ri-checkbox-circle-fill' : 'ri-time-line') : 'ri-upload-line'); ?>"></i>
                                <?php echo e($tieneDP ? ($est->documento_provision_verificado ? 'Verificado' : 'Pendiente de verificación') : 'Sin documento subido'); ?>

                            </div>
                        </div>
                        <div class="est-doc-actions">
                            <button class="est-doc-btn est-doc-btn-view btn-ver-doc"
                                style="<?php echo e($tieneDP ? 'display:flex;' : 'display:none;'); ?>"
                                data-id="<?php echo e($estudiante->id); ?>"
                                data-tipo="documento_provision_nacional"
                                data-estudio-id="<?php echo e($est->id); ?>"
                                title="Ver documento">
                                <i class="ri-eye-line"></i>
                            </button>
                            <button class="est-doc-btn <?php echo e($est->documento_provision_verificado ? 'est-doc-btn-uncheck' : 'est-doc-btn-check'); ?> btn-verificar-doc"
                                style="<?php echo e($tieneDP ? 'display:flex;' : 'display:none;'); ?>"
                                data-id="<?php echo e($estudiante->id); ?>"
                                data-tipo="documento_provision_nacional"
                                data-estudio-id="<?php echo e($est->id); ?>"
                                title="<?php echo e($est->documento_provision_verificado ? 'Quitar verificación' : 'Verificar documento'); ?>">
                                <i class="<?php echo e($est->documento_provision_verificado ? 'ri-close-circle-line' : 'ri-check-line'); ?>"></i>
                            </button>
                            <label class="est-doc-btn est-doc-btn-upload" title="<?php echo e($tieneDP ? 'Reemplazar archivo' : 'Subir archivo'); ?>" style="cursor:pointer;display:flex;align-items:center;justify-content:center;">
                                <i class="ri-upload-2-line"></i>
                                <input type="file" accept=".pdf,.jpg,.jpeg,.png"
                                    class="d-none btn-subir-doc"
                                    data-id="<?php echo e($estudiante->id); ?>"
                                    data-tipo="documento_provision_nacional"
                                    data-estudio-id="<?php echo e($est->id); ?>">
                            </label>
                        </div>
                    </div>

                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php else: ?>
            <div style="text-align: center; padding: 2rem 1rem; color: #64748b;">
                <i class="ri-book-open-line" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; display: block;"></i>
                <p style="margin: 0; font-size: 0.9rem;">No hay estudios registrados para este estudiante.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/estudiantes/partials/tab-documentos.blade.php ENDPATH**/ ?>