
<style>
/* ══════════════════════════════════════════════════
   Tab Personal — mejoras de diseño
══════════════════════════════════════════════════ */

/* ── Filas de contacto ─────────────────────────── */
.pers-contact-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 7px 8px;
    border-radius: 9px;
    transition: background 0.15s;
}
.pers-contact-row:hover { background: rgba(252,123,4,.04); }

.pers-contact-ico {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: rgba(252,123,4,.08);
    color: #fc7b04;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.88rem;
    flex-shrink: 0;
}

.pers-contact-body { flex: 1; min-width: 0; }

.pers-contact-lbl {
    font-size: 0.59rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #94a3b8;
    line-height: 1;
    margin-bottom: 2px;
}

.pers-contact-val {
    font-size: 0.84rem;
    font-weight: 500;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.pers-contact-link {
    text-decoration: none;
    color: #1e293b;
    transition: color 0.15s;
}
.pers-contact-link:hover { color: #fc7b04; }

.pers-contact-act {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 7px;
    border: 1px solid #e2e8f0;
    background: white;
    color: #64748b;
    cursor: pointer;
    transition: all 0.18s;
    font-size: 0.82rem;
    flex-shrink: 0;
    text-decoration: none;
}
.pers-contact-act:hover {
    border-color: #fc7b04;
    background: rgba(252,123,4,.08);
    color: #fc7b04;
}
.pers-contact-act.wa:hover {
    border-color: #25d366;
    background: rgba(37,211,102,.08);
    color: #25d366;
}

/* ── Inline data row ───────────────────────────── */
.pers-inline-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    padding: 10px 10px 4px;
}

.pers-inline-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

/* ── Chips de cuentas (columna izquierda) ──────── */
.pers-acc-chips {
    display: flex;
    flex-direction: column;
    gap: 5px;
    width: 100%;
    padding-top: 4px;
}

.pers-acc-chip {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 8px;
    font-size: 0.67rem;
    font-weight: 600;
    letter-spacing: 0.02em;
}

.pers-chip-ok {
    background: rgba(34,197,94,.18);
    color: #dcfce7;
    border: 1px solid rgba(34,197,94,.35);
}
.pers-chip-ok i { color: #86efac; }

.pers-chip-no {
    background: rgba(255,255,255,.08);
    color: rgba(255,255,255,.45);
    border: 1px solid rgba(255,255,255,.12);
}

/* ── Items de datos en la columna derecha ──────── */
.pers-info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    background: white;
    border: 1px solid var(--est-border, #e2e8f0);
    border-radius: 9px;
    transition: box-shadow 0.18s;
}
.pers-info-item:hover { box-shadow: 0 2px 8px rgba(252,123,4,.08); }

.pers-info-ico {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    background: rgba(252,123,4,.08);
    color: #fc7b04;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.82rem;
    flex-shrink: 0;
}

.pers-info-lbl {
    font-size: 0.6rem;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 2px;
}

.pers-info-val {
    font-size: 0.82rem;
    font-weight: 600;
    color: #1e293b;
    word-break: break-all;
}

/* ── Card de estudio ───────────────────────────── */
.pers-study-card {
    background: white;
    border: 1px solid var(--est-border, #e2e8f0);
    border-radius: 10px;
    padding: 10px 12px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    transition: box-shadow 0.15s;
}
.pers-study-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,.06); }

.pers-study-grado {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    border-radius: 5px;
    background: rgba(252,123,4,.09);
    color: #c96004;
    font-size: 0.62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    width: fit-content;
}

.pers-study-profesion {
    font-size: 0.83rem;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.3;
}

.pers-study-univ {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.73rem;
    color: #64748b;
}

/* ── Separador de sección ──────────────────────── */
.pers-section-sep {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--est-text-muted, #64748b);
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 0 6px;
}
.pers-section-sep i { color: #fc7b04; }

/* ── Responsive ────────────────────────────────── */
@media (max-width: 767px) {
    .pers-inline-grid { grid-template-columns: 1fr; }
}
</style>

<?php
    $persona = $estudiante->persona;

    $tieneFoto = $persona && $persona->fotografia
        && file_exists(public_path('images/personas/' . $persona->fotografia));
    $avatarUrl = $tieneFoto ? asset('images/personas/' . $persona->fotografia) : null;

    $nombreCompleto = $persona
        ? trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''))
        : 'Estudiante';

    $iniciales = collect(explode(' ', $nombreCompleto))
        ->filter()->take(2)->map(fn($p) => strtoupper($p[0]))->implode('');

    $edad = ($persona && $persona->fecha_nacimiento)
        ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->age
        : null;

    $ubicacion = ($persona && $persona->ciudad)
        ? optional($persona->ciudad)->nombre . ', ' . (optional(optional($persona->ciudad)->departamento)->nombre ?? '')
        : null;

    $estudios           = $persona?->estudios ?? collect();
    $tieneSistemaCuenta = $persona?->usuario !== null;
    $tieneMoodleCuenta  = $inscripciones->contains(fn($i) => !empty($i->moodle_user_id));
    $moodleUsername     = $persona?->usuario?->username;
?>

<div class="est-tabs-body active est-ci-wrap" id="tab-personal">
    <div class="est-ci-stripe"></div>

    <div class="est-ci-body">

        
        <div class="est-ci-left">
            <div class="est-ci-foto-label">
                <i class="ri-building-2-line"></i>
                <span>INNOVA CIENCIA</span>
            </div>

            <div class="est-ci-foto" id="est-ci-foto-container">
                <img src="<?php echo e($avatarUrl ?? ''); ?>" alt="Foto"
                     id="est-ci-foto-img"
                     style="<?php echo e($tieneFoto ? '' : 'display:none;'); ?>"
                     onerror="this.style.display='none';document.getElementById('est-ci-initials').style.display='flex';">
                <div id="est-ci-initials" class="est-ci-initials"
                     style="<?php echo e($tieneFoto ? 'display:none;' : ''); ?>">
                    <?php echo e($iniciales ?: '?'); ?>

                </div>
            </div>

            <div class="est-ci-quick-data">
                <?php if($persona?->carnet): ?>
                <div class="est-ci-qd-item">
                    <i class="ri-shield-check-line"></i>
                    <span class="est-ci-qd-label">CI</span>
                    <span class="est-ci-qd-val"><?php echo e($persona->carnet); ?><?php echo e($persona->expedido ? ' '.$persona->expedido : ''); ?></span>
                </div>
                <?php endif; ?>
                <?php if($persona?->fecha_nacimiento): ?>
                <div class="est-ci-qd-item">
                    <i class="ri-cake-line"></i>
                    <span class="est-ci-qd-label">Nacim.</span>
                    <span class="est-ci-qd-val"><?php echo e(\Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y')); ?></span>
                </div>
                <?php endif; ?>
                <?php if($edad): ?>
                <div class="est-ci-qd-item">
                    <i class="ri-user-line"></i>
                    <span class="est-ci-qd-label">Edad</span>
                    <span class="est-ci-qd-val"><?php echo e($edad); ?> años</span>
                </div>
                <?php endif; ?>
                <?php if($persona?->sexo): ?>
                <div class="est-ci-qd-item">
                    <i class="ri-genderless-line"></i>
                    <span class="est-ci-qd-label">Sexo</span>
                    <span class="est-ci-qd-val"><?php echo e($persona->sexo == 'M' ? 'Masculino' : ($persona->sexo == 'F' ? 'Femenino' : '—')); ?></span>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="pers-acc-chips">
                <div class="pers-acc-chip <?php echo e($tieneSistemaCuenta ? 'pers-chip-ok' : 'pers-chip-no'); ?>">
                    <i class="<?php echo e($tieneSistemaCuenta ? 'ri-computer-line' : 'ri-computer-line'); ?>"></i>
                    <span>Sistema<?php echo e($tieneSistemaCuenta ? ': Activo' : ': Sin cuenta'); ?></span>
                </div>
                <div class="pers-acc-chip <?php echo e($tieneMoodleCuenta ? 'pers-chip-ok' : 'pers-chip-no'); ?>">
                    <i class="ri-links-line"></i>
                    <span>Moodle<?php echo e($tieneMoodleCuenta ? ': Activo' : ': Sin cuenta'); ?></span>
                </div>
            </div>
        </div>

        
        <div class="est-ci-center">

            
            <div class="est-ci-nombre-wrap">
                <div>
                    <div class="est-ci-nombre"><?php echo e($nombreCompleto); ?></div>
                    <div class="est-ci-estado-label">
                        <i class="ri-graduation-cap-line"></i> Estudiante
                    </div>
                </div>
                <span class="est-ci-estado-badge est-ci-badge-<?php echo e(($estudiante->estado ?? 'Activo') === 'Activo' ? 'activo' : 'inactivo'); ?>">
                    <i class="ri-checkbox-circle-line"></i>
                    <?php echo e($estudiante->estado ?? 'Activo'); ?>

                </span>
            </div>

            
            <div class="est-ci-section-title">
                <i class="ri-contacts-line"></i> Datos de Contacto
            </div>

            <?php if($persona?->correo): ?>
            <div class="pers-contact-row">
                <div class="pers-contact-ico"><i class="ri-mail-line"></i></div>
                <div class="pers-contact-body">
                    <div class="pers-contact-lbl">Correo electrónico</div>
                    <div class="pers-contact-val">
                        <a href="mailto:<?php echo e($persona->correo); ?>" class="pers-contact-link"><?php echo e($persona->correo); ?></a>
                    </div>
                </div>
                <a href="mailto:<?php echo e($persona->correo); ?>" class="pers-contact-act" title="Enviar correo">
                    <i class="ri-send-plane-line"></i>
                </a>
            </div>
            <?php endif; ?>

            <?php if($persona?->celular): ?>
            <div class="pers-contact-row">
                <div class="pers-contact-ico"><i class="ri-smartphone-line"></i></div>
                <div class="pers-contact-body">
                    <div class="pers-contact-lbl">Celular</div>
                    <div class="pers-contact-val">
                        <a href="tel:<?php echo e($persona->celular); ?>" class="pers-contact-link"><?php echo e($persona->celular); ?></a>
                    </div>
                </div>
                <a href="https://wa.me/<?php echo e(preg_replace('/\D/', '', $persona->celular)); ?>"
                   target="_blank" class="pers-contact-act wa" title="WhatsApp">
                    <i class="ri-whatsapp-line"></i>
                </a>
            </div>
            <?php endif; ?>

            <?php if($persona?->telefono): ?>
            <div class="pers-contact-row">
                <div class="pers-contact-ico"><i class="ri-phone-line"></i></div>
                <div class="pers-contact-body">
                    <div class="pers-contact-lbl">Teléfono</div>
                    <div class="pers-contact-val"><?php echo e($persona->telefono); ?></div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($ubicacion): ?>
            <div class="pers-contact-row">
                <div class="pers-contact-ico"><i class="ri-map-pin-2-line"></i></div>
                <div class="pers-contact-body">
                    <div class="pers-contact-lbl">Ciudad / Departamento</div>
                    <div class="pers-contact-val"><?php echo e($ubicacion); ?></div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($persona?->direccion): ?>
            <div class="pers-contact-row">
                <div class="pers-contact-ico"><i class="ri-home-3-line"></i></div>
                <div class="pers-contact-body">
                    <div class="pers-contact-lbl">Dirección</div>
                    <div class="pers-contact-val"><?php echo e($persona->direccion); ?></div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if($persona?->estado_civil || $persona?->fecha_nacimiento): ?>
            <div class="pers-inline-grid" style="margin-top:6px;">
                <?php if($persona?->estado_civil): ?>
                <div class="pers-inline-item">
                    <span class="est-ci-label">Estado Civil</span>
                    <span class="est-ci-value"><?php echo e($persona->estado_civil); ?></span>
                </div>
                <?php endif; ?>
                <?php if($persona?->fecha_nacimiento): ?>
                <div class="pers-inline-item">
                    <span class="est-ci-label">Fecha de Nacimiento</span>
                    <span class="est-ci-value">
                        <?php echo e(\Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y')); ?>

                        <?php if($edad): ?><span style="color:#94a3b8;font-size:.78rem;font-weight:500;"> · <?php echo e($edad); ?> años</span><?php endif; ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>

        
        <div class="est-ci-right">
            <div class="est-ci-right-header">
                <i class="ri-graduation-cap-line"></i>
                <span>Datos del Estudiante</span>
            </div>

            <div style="display:flex;flex-direction:column;gap:6px;">
                <div class="pers-info-item">
                    <div class="pers-info-ico"><i class="ri-calendar-check-line"></i></div>
                    <div>
                        <div class="pers-info-lbl">Fecha Inscripción</div>
                        <div class="pers-info-val"><?php echo e($estudiante->created_at->format('d/m/Y')); ?></div>
                    </div>
                </div>

                <div class="pers-info-item">
                    <div class="pers-info-ico"><i class="ri-vip-diamond-line"></i></div>
                    <div>
                        <div class="pers-info-lbl">Estado</div>
                        <div class="pers-info-val"><?php echo e($estudiante->estado ?? 'Activo'); ?></div>
                    </div>
                </div>

                <?php if($inscripciones->count()): ?>
                <div class="pers-info-item">
                    <div class="pers-info-ico"><i class="ri-book-open-line"></i></div>
                    <div>
                        <div class="pers-info-lbl">Ofertas Académicas</div>
                        <div class="pers-info-val"><?php echo e($inscripciones->count()); ?> inscripción(es)</div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($tieneSistemaCuenta && $moodleUsername): ?>
                <div class="pers-info-item">
                    <div class="pers-info-ico"><i class="ri-user-settings-line"></i></div>
                    <div>
                        <div class="pers-info-lbl">Usuario Sistema</div>
                        <div class="pers-info-val"><?php echo e($moodleUsername); ?></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            
            <?php if($estudios->count() > 0): ?>
            <div class="pers-section-sep">
                <i class="ri-book-line"></i>
                Estudios
                <span style="background:rgba(252,123,4,.1);color:#c96004;padding:1px 7px;border-radius:5px;font-size:.65rem;">
                    <?php echo e($estudios->count()); ?>

                </span>
            </div>
            <div style="display:flex;flex-direction:column;gap:6px;overflow-y:auto;max-height:220px;padding-right:2px;">
                <?php $__currentLoopData = $estudios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $est): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="pers-study-card">
                    <?php if($est->grado_academico?->nombre): ?>
                    <span class="pers-study-grado">
                        <i class="ri-award-line"></i>
                        <?php echo e($est->grado_academico->nombre); ?>

                    </span>
                    <?php endif; ?>
                    <div class="pers-study-profesion">
                        <?php echo e($est->profesion?->nombre ?? '—'); ?>

                    </div>
                    <?php if($est->universidad?->nombre): ?>
                    <div class="pers-study-univ">
                        <i class="ri-building-4-line" style="font-size:.7rem;flex-shrink:0;"></i>
                        <?php echo e($est->universidad->nombre); ?>

                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <div class="est-ci-bottom-bar">
        <span><i class="ri-id-card-line"></i> Carnet de Identificación</span>
        <span><?php echo e(now()->format('Y')); ?></span>
    </div>
</div>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/estudiantes/partials/tab-personal.blade.php ENDPATH**/ ?>