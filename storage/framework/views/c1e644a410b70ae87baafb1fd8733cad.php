<?php $__env->startSection('title'); ?>
    Mi Portal Virtual
<?php $__env->stopSection(); ?>

<?php if(session('route_not_found')): ?>
    <div class="container mt-3">
        <div class="alert alert-warning" role="alert">
            <?php echo e(session('route_not_found')); ?>

        </div>
    </div>
<?php endif; ?>

<?php $__env->startSection('css'); ?>
    <?php echo $__env->make('virtual.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <style>
    .quiz-pregunta-html .qtext { font-size:.85rem; color:#334155; margin-bottom:.75rem; line-height:1.65; }
    .quiz-pregunta-html .ablock .answer { display:flex; flex-direction:column; gap:.35rem; }
    .quiz-pregunta-html .ablock .answer .r0,
    .quiz-pregunta-html .ablock .answer .r1 { padding:.5rem .65rem; border-radius:8px; display:flex; align-items:center; gap:.55rem; font-size:.85rem; transition:background .15s; cursor:pointer; }
    .quiz-pregunta-html .ablock .answer .r0 { background:#f8fafc; }
    .quiz-pregunta-html .ablock .answer .r1 { background:#f1f5f9; }
    .quiz-pregunta-html .ablock .answer .r0:hover,
    .quiz-pregunta-html .ablock .answer .r1:hover { background:#e2e8f0; }
    .quiz-pregunta-html .ablock .answer input[type="radio"],
    .quiz-pregunta-html .ablock .answer input[type="checkbox"] { accent-color:#fc7b04; width:17px; height:17px; flex-shrink:0; cursor:pointer; }
    .quiz-pregunta-html .ablock .answer label { cursor:pointer; flex:1; font-weight:500; color:#1e293b; }
    .quiz-pregunta-html input[type="text"],
    .quiz-pregunta-html input[type="number"],
    .quiz-pregunta-html textarea { width:100%; border:1.5px solid #d1d5db; border-radius:8px; padding:.55rem .7rem; font-size:.85rem; box-sizing:border-box; transition:border-color .2s,box-shadow .2s; }
    .quiz-pregunta-html input[type="text"]:focus,
    .quiz-pregunta-html input[type="number"]:focus,
    .quiz-pregunta-html textarea:focus { outline:none; border-color:#fc7b04; box-shadow:0 0 0 3px rgba(252,123,4,.12); }
    .quiz-pregunta-html select { border:1.5px solid #d1d5db; border-radius:8px; padding:.4rem .6rem; font-size:.85rem; background:#fff; cursor:pointer; }
    .quiz-pregunta-html select:focus { outline:none; border-color:#fc7b04; box-shadow:0 0 0 3px rgba(252,123,4,.12); }
    .quiz-pregunta:hover { border-color:#fc7b04 !important; box-shadow:0 2px 12px rgba(252,123,4,.08); }
    .quiz-status-dot { display:inline-block; width:10px; height:10px; border-radius:50%; background:#d1d5db; vertical-align:middle; flex-shrink:0; }
    .quiz-status-dot.answered { background:#16a34a; }

    /* ── Postergado event style (cronograma estudiante) ── */
    .cronograma-calendar-wrapper .fc-event-postergado {
        background: transparent !important;
        border: 1.5px dashed #94a3b8 !important;
        box-shadow: none !important;
    }
    .cronograma-calendar-wrapper .fc-event-postergado .fc-event-title,
    .cronograma-calendar-wrapper .fc-event-postergado .fc-event-time {
        color: inherit !important;
    }

    /* ── Modal detalle sesión estudiante (nuevas clases) ── */
    .cronograma-modal-label {
        font-size: .68rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .04em; color: #94a3b8; margin-bottom: 2px;
    }
    .cronograma-modal-value {
        font-size: .88rem; font-weight: 700; color: #1e293b; line-height: 1.3;
    }
    .cronograma-modal-value-sm {
        font-size: .8rem; font-weight: 600; color: #334155; line-height: 1.2;
    }
    .cronograma-modal-icon {
        width: 34px; height: 34px; border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem; flex-shrink: 0;
    }
    .cronograma-modal-icon-sm {
        width: 28px; height: 28px; border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem; flex-shrink: 0;
    }
    .pers-info-separator {
        display:flex; align-items:center; gap:.5rem; margin:.25rem 0 .15rem;
        font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#94a3b8;
    }
    .pers-info-separator::before,
    .pers-info-separator::after { content:''; flex:1; height:1px; background:#e2e8f0; }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="est-hero">
        <?php if($persona && $persona->fotografia): ?>
            <img src="<?php echo e(url('images/personas/' . $persona->fotografia)); ?>" alt="Foto" class="est-hero-avatar"
                onerror="this.src='<?php echo e(URL::asset('build/images/users/avatar-1.jpg')); ?>'">
        <?php else: ?>
            <img src="<?php echo e(URL::asset('build/images/users/avatar-1.jpg')); ?>" alt="Foto" class="est-hero-avatar">
        <?php endif; ?>
        <div style="flex:1;min-width:0;">
            <div class="est-hero-name">
                <?php echo e($persona ? trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')) : $user->name); ?>

            </div>
            <?php if($persona): ?>
                <div class="est-hero-sub">
                    <i class="ri-id-card-line"></i> <?php echo e($persona->carnet); ?>

                    <?php if($persona->correo): ?>
                        &nbsp;·&nbsp;<i class="ri-mail-line"></i> <?php echo e($persona->correo); ?>

                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="est-hero-badges">
                <?php if($esEstudiante && $esDocente): ?>
                    <span class="est-hero-badge est-hero-role ambos"><i class="ri-shield-user-line"></i> Estudiante y Docente</span>
                <?php elseif($esEstudiante): ?>
                    <span class="est-hero-badge est-hero-role estudiante"><i class="ri-graduation-cap-line"></i> Estudiante</span>
                <?php elseif($esDocente): ?>
                    <span class="est-hero-badge est-hero-role docente"><i class="ri-user-settings-line"></i> Docente</span>
                <?php endif; ?>
                <?php if($moodleUserId): ?>
                    <span class="est-hero-badge"><i class="ri-links-line"></i> Moodle activo</span>
                <?php else: ?>
                    <span class="est-hero-badge sin"><i class="ri-close-circle-line"></i> Sin Moodle</span>
                <?php endif; ?>
                <span class="est-hero-badge"><i class="ri-checkbox-circle-line"></i> Sesión activa</span>
            </div>
        </div>
    </div>

    
    <?php if($esEstudiante && $esDocente): ?>
    <div class="rol-switcher" id="rol-switcher">
        <span class="rol-switcher-label">Ver como</span>
        <div class="rol-switcher-btns">

            <button type="button"
                    id="rol-btn-estudiante"
                    class="rol-btn <?php echo e($perfilActivo === 'estudiante' ? 'active' : ''); ?>"
                    onclick="cambiarPerfil('estudiante')"
                    <?php echo e($perfilActivo === 'estudiante' ? 'disabled' : ''); ?>>
                <div class="rol-btn-icon"><i class="ri-graduation-cap-line"></i></div>
                <div class="rol-btn-text">
                    <span class="rol-btn-title">Estudiante</span>
                    <span class="rol-btn-sub">Inscripciones, pagos y cronograma</span>
                </div>
                <?php if($perfilActivo === 'estudiante'): ?>
                    <div class="rol-btn-check"><i class="ri-check-line"></i></div>
                <?php endif; ?>
                <div class="rol-btn-spinner"></div>
            </button>

            <button type="button"
                    id="rol-btn-docente"
                    class="rol-btn <?php echo e($perfilActivo === 'docente' ? 'active' : ''); ?>"
                    onclick="cambiarPerfil('docente')"
                    <?php echo e($perfilActivo === 'docente' ? 'disabled' : ''); ?>>
                <div class="rol-btn-icon"><i class="ri-user-settings-line"></i></div>
                <div class="rol-btn-text">
                    <span class="rol-btn-title">Docente</span>
                    <span class="rol-btn-sub">Módulos, sesiones y horario</span>
                </div>
                <?php if($perfilActivo === 'docente'): ?>
                    <div class="rol-btn-check"><i class="ri-check-line"></i></div>
                <?php endif; ?>
                <div class="rol-btn-spinner"></div>
            </button>

        </div>
    </div>
    <?php endif; ?>

    <?php if($esEstudiante): ?>
    
    <?php
        $totalProgramas = $inscripciones->count();
        $totalModulos = $inscripciones->sum(fn($i) => $i->moodleMatriculas->count());
        $activas = $inscripciones->whereIn('estado', ['Inscrito', 'Confirmado'])->count();
    ?>
    <div class="est-stats" id="stats-estudiante" <?php echo $perfilActivo !== 'estudiante' ? 'style="display:none"' : ''; ?>>
        <div class="est-stat-card-sm">
            <div class="est-stat-icon-sm orange"><i class="ri-book-open-line"></i></div>
            <div>
                <div class="est-stat-num"><?php echo e($totalProgramas); ?></div>
                <div class="est-stat-label">Programa(s)</div>
            </div>
        </div>
        <div class="est-stat-card-sm">
            <div class="est-stat-icon-sm blue"><i class="ri-stack-line"></i></div>
            <div>
                <div class="est-stat-num"><?php echo e($totalModulos); ?></div>
                <div class="est-stat-label">Módulo(s)</div>
            </div>
        </div>
        <div class="est-stat-card-sm">
            <div class="est-stat-icon-sm green"><i class="ri-check-double-line"></i></div>
            <div>
                <div class="est-stat-num"><?php echo e($activas); ?></div>
                <div class="est-stat-label">Inscripción(es) activa(s)</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if($esDocente): ?>
    <?php
        $totalCursos = $modulosDocente->count();
        $totalSesiones = $modulosDocente->sum(fn($m) => $m->horarios->count());
    ?>
    <div class="est-stats" id="stats-docente" <?php echo $perfilActivo !== 'docente' ? 'style="display:none"' : ''; ?>>
        <div class="est-stat-card-sm">
            <div class="est-stat-icon-sm orange"><i class="ri-book-3-line"></i></div>
            <div>
                <div class="est-stat-num"><?php echo e($totalCursos); ?></div>
                <div class="est-stat-label">Curso(s)</div>
            </div>
        </div>
        <div class="est-stat-card-sm">
            <div class="est-stat-icon-sm blue"><i class="ri-calendar-event-line"></i></div>
            <div>
                <div class="est-stat-num"><?php echo e($totalSesiones); ?></div>
                <div class="est-stat-label">Sesión(es)</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="est-tabs-card">

        <?php if($esEstudiante): ?>
        
        <div class="est-tabs-nav" id="nav-estudiante" <?php echo $perfilActivo !== 'estudiante' ? 'style="display:none"' : ''; ?>>
            <button class="est-tab-btn active" onclick="switchTab(this,'tab-personal')">
                <i class="ri-user-3-line"></i> Personal
            </button>
            <button class="est-tab-btn" onclick="switchTab(this,'tab-documentos')">
                <i class="ri-file-paper-line"></i> Documentos
            </button>
            <button class="est-tab-btn" onclick="switchTab(this,'tab-academico')">
                <i class="ri-book-3-line"></i> Académico
            </button>
            <button class="est-tab-btn" onclick="switchTab(this,'tab-contable')">
                <i class="ri-money-dollar-circle-line"></i> Contable
            </button>
            <button class="est-tab-btn" onclick="switchTab(this,'tab-pagos')">
                <i class="ri-file-list-3-line"></i> Pagos
            </button>
            <button class="est-tab-btn" onclick="switchTab(this,'tab-cronograma')">
                <i class="ri-calendar-line"></i> Cronograma
            </button>
        </div>
        <?php endif; ?>

        <?php if($esDocente): ?>
        <div class="est-tabs-nav" id="nav-docente" <?php echo $perfilActivo !== 'docente' ? 'style="display:none"' : ''; ?>>
            <button class="est-tab-btn active" onclick="switchTabDocente(this,'tab-personal-docente')">
                <i class="ri-user-3-line"></i> Personal
            </button>
            <button class="est-tab-btn" onclick="switchTabDocente(this,'tab-documentos-docente')">
                <i class="ri-file-paper-line"></i> Documentos
            </button>
            <button class="est-tab-btn" onclick="switchTabDocente(this,'tab-academico-docente')">
                <i class="ri-book-3-line"></i> Académico
            </button>
            <button class="est-tab-btn" onclick="switchTabDocente(this,'tab-horario-docente')">
                <i class="ri-calendar-check-line"></i> Mi Horario
            </button>
        </div>
        <?php endif; ?>

        <?php if($esDocente): ?>
        <div id="content-docente" <?php echo $perfilActivo !== 'docente' ? 'style="display:none"' : ''; ?>>

        <div class="est-tabs-body active" id="tab-personal-docente">
            <?php
                $tieneFotoDoc = $persona && $persona->fotografia && file_exists(public_path('images/personas/' . $persona->fotografia));
                $avatarUrlDoc = $tieneFotoDoc ? asset('images/personas/' . $persona->fotografia) : null;
                $nombreCompletoDoc = $persona
                    ? trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''))
                    : $user->name;
                $inicialesDoc = collect(explode(' ', $nombreCompletoDoc))->filter()->take(2)->map(fn($p) => strtoupper($p[0]))->implode('');
                $edadDoc = $persona && $persona->fecha_nacimiento ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->age : null;
                $ubicacionDoc = $persona && $persona->ciudad
                    ? optional($persona->ciudad)->nombre . ', ' . (optional(optional($persona->ciudad)->departamento)->nombre ?? '')
                    : null;
                $docenteModel = $persona?->docente;
            ?>
            <div class="est-ci-wrap">
                <div class="est-ci-stripe"></div>
                <div class="est-ci-body">
                    
                    <div class="est-ci-left">
                        <div class="est-ci-foto-label"><i class="ri-building-2-line"></i><span>INNOVA CIENCIA</span></div>
                        <div class="est-ci-foto">
                            <img src="<?php echo e($avatarUrlDoc ?? ''); ?>" alt="Foto" id="doc-ci-foto-img"
                                style="<?php echo e($tieneFotoDoc ? '' : 'display:none;'); ?>"
                                onerror="this.style.display='none';document.getElementById('doc-ci-initials').style.display='flex';">
                            <div id="doc-ci-initials" class="est-ci-initials"
                                style="<?php echo e($tieneFotoDoc ? 'display:none;' : ''); ?>">
                                <?php echo e($inicialesDoc ?: '?'); ?>

                            </div>
                        </div>
                        <div class="est-ci-quick-data">
                            <?php if($persona?->carnet): ?>
                                <div class="est-ci-qd-item">
                                    <i class="ri-shield-check-line"></i>
                                    <span class="est-ci-qd-label">CI</span>
                                    <span class="est-ci-qd-val"><?php echo e($persona->carnet); ?><?php echo e($persona->expedido ? ' ' . $persona->expedido : ''); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if($persona?->fecha_nacimiento): ?>
                                <div class="est-ci-qd-item">
                                    <i class="ri-cake-line"></i>
                                    <span class="est-ci-qd-label">Nacimiento</span>
                                    <span class="est-ci-qd-val"><?php echo e(\Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y')); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if($edadDoc): ?>
                                <div class="est-ci-qd-item">
                                    <i class="ri-user-line"></i>
                                    <span class="est-ci-qd-label">Edad</span>
                                    <span class="est-ci-qd-val"><?php echo e($edadDoc); ?> años</span>
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
                            <?php if($moodleUserId): ?>
                            <div class="pers-acc-chip pers-chip-ok">
                                <i class="ri-links-line"></i><span>Moodle: Activo</span>
                            </div>
                            <?php else: ?>
                            <div class="pers-acc-chip pers-chip-no">
                                <i class="ri-links-line"></i><span>Moodle: Sin cuenta</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="est-ci-center">
                        <div class="est-ci-nombre-wrap">
                            <div>
                                <div class="est-ci-nombre"><?php echo e($nombreCompletoDoc); ?></div>
                                <div class="est-ci-estado-label">Docente</div>
                            </div>
                            <span class="est-ci-estado-badge est-ci-badge-activo">
                                <i class="ri-checkbox-circle-line"></i> Activo
                            </span>
                        </div>
                        <div class="est-ci-section-title"><i class="ri-contacts-line"></i> Datos de Contacto</div>
                        <?php if($persona?->correo): ?>
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-mail-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Correo electrónico</div>
                                <div class="pers-contact-val"><a href="mailto:<?php echo e($persona->correo); ?>" class="pers-contact-link"><?php echo e($persona->correo); ?></a></div>
                            </div>
                            <a href="mailto:<?php echo e($persona->correo); ?>" class="pers-contact-act" title="Enviar correo"><i class="ri-send-plane-line"></i></a>
                        </div>
                        <?php endif; ?>
                        <?php if($persona?->celular): ?>
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-smartphone-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Celular</div>
                                <div class="pers-contact-val"><a href="tel:<?php echo e($persona->celular); ?>" class="pers-contact-link"><?php echo e($persona->celular); ?></a></div>
                            </div>
                            <a href="https://wa.me/<?php echo e(preg_replace('/\D/', '', $persona->celular)); ?>" target="_blank" class="pers-contact-act wa" title="WhatsApp"><i class="ri-whatsapp-line"></i></a>
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
                        <?php if($persona?->estado_civil): ?>
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-heart-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Estado Civil</div>
                                <div class="pers-contact-val"><?php echo e($persona->estado_civil); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($ubicacionDoc): ?>
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-map-pin-2-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Ciudad / Departamento</div>
                                <div class="pers-contact-val"><?php echo e($ubicacionDoc); ?></div>
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
                    </div>

                    
                    <div class="est-ci-right">
                        <div class="est-ci-right-header">
                            <i class="ri-user-star-line"></i><span>Datos del Docente</span>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <?php if($docenteModel?->created_at): ?>
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-calendar-check-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Fecha Registro</div>
                                    <div class="pers-info-val"><?php echo e($docenteModel->created_at->format('d/m/Y')); ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-book-3-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Módulos asignados</div>
                                    <div class="pers-info-val"><?php echo e($modulosDocente->count()); ?> módulo(s)</div>
                                </div>
                            </div>
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-time-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Total de sesiones</div>
                                    <div class="pers-info-val"><?php echo e($modulosDocente->sum(fn($m) => $m->horarios->count())); ?> sesión(es)</div>
                                </div>
                            </div>
                            <div class="pers-info-separator">
                                <span>Accesos del Sistema</span>
                            </div>
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-user-settings-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Usuario del sistema</div>
                                    <div class="pers-info-val" style="font-family:monospace;font-size:.82rem;"><?php echo e($user->username ?? '—'); ?></div>
                                </div>
                            </div>
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-mail-send-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Correo del sistema</div>
                                    <div class="pers-info-val" style="font-size:.82rem;"><?php echo e($user->email ?? '—'); ?></div>
                                </div>
                            </div>
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-links-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Cuenta Moodle</div>
                                    <div class="pers-info-val">
                                        <?php if($moodleDocenteId): ?>
                                            <span style="color:var(--doc-success);font-weight:600;"><i class="ri-checkbox-circle-fill"></i> Activa</span>
                                        <?php else: ?>
                                            <span style="color:var(--doc-text-muted);">Sin cuenta Moodle</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="est-ci-bottom-bar">
                    <span><i class="ri-id-card-line"></i> Carnet de Identificación · Docente</span>
                    <span><?php echo e(now()->format('Y')); ?></span>
                </div>
            </div>
        </div>

        
        <div class="est-tabs-body" id="tab-documentos-docente">
            <?php
                $estadoDocDoc = function ($archivo, $verificado) {
                    if (!$archivo) {
                        return ['label' => 'Pendiente', 'cls' => 'pending', 'icon' => 'ri-add-circle-line'];
                    }
                    if ($verificado) {
                        return ['label' => 'Aprobado', 'cls' => 'approved', 'icon' => 'ri-checkbox-circle-fill'];
                    }
                    return ['label' => 'En revisión', 'cls' => 'review', 'icon' => 'ri-time-line'];
                };
                $docsIdentidadDoc = [
                    [
                        'nombre'    => 'Carnet de Identidad',
                        'icono'     => 'ri-id-card-line',
                        'archivo'   => $persona->fotografia_carnet ?? null,
                        'verificado'=> $persona->carnet_verificado ?? false,
                        'tipo'      => 'fotografia_carnet',
                    ],
                    [
                        'nombre'    => 'Cert. Nacimiento',
                        'icono'     => 'ri-file-paper-line',
                        'archivo'   => $persona->fotografia_certificado_nacimiento ?? null,
                        'verificado'=> $persona->certificado_nacimiento_verificado ?? false,
                        'tipo'      => 'fotografia_certificado_nacimiento',
                    ],
                ];
                $totalDocsDoc = count($docsIdentidadDoc);
                $verificadosDoc = 0;
                foreach ($docsIdentidadDoc as $d) {
                    if ($d['verificado']) $verificadosDoc++;
                }
                $estudioDocente = $persona?->estudios?->first();
                if ($estudioDocente) {
                    $totalDocsDoc += 2;
                    if ($estudioDocente->documento_academico_verificado) $verificadosDoc++;
                    if ($estudioDocente->documento_provision_verificado) $verificadosDoc++;
                }
                $pctDocsDoc = $totalDocsDoc > 0 ? ($verificadosDoc / $totalDocsDoc) * 100 : 0;
            ?>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:16px;">
                <h3 style="margin:0;font-size:1.1rem;font-weight:600;display:flex;align-items:center;gap:8px;">
                    <i class="ri-folder-shield-line" style="color:#fc7b04;"></i> Documentación
                </h3>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="flex:1;max-width:150px;height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                        <div style="height:100%;background:linear-gradient(90deg,#fc7b04,#f97316);border-radius:4px;width:<?php echo e($pctDocsDoc); ?>%;transition:width .3s;"></div>
                    </div>
                    <span style="font-size:.875rem;font-weight:700;color:#fc7b04;"><?php echo e(number_format($pctDocsDoc, 0)); ?>%</span>
                </div>
            </div>

            <h4 style="font-size:1rem;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="ri-id-card-line" style="color:#fc7b04;"></i> Documentación Personal
            </h4>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:32px;">
                <?php $__currentLoopData = $docsIdentidadDoc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $estado = $estadoDocDoc($doc['archivo'], $doc['verificado']);
                        $bgIcon   = $estado['cls'] == 'approved' ? '#dcfce7' : ($estado['cls'] == 'review' ? '#e0f2fe' : '#fef3c7');
                        $colorIcon= $estado['cls'] == 'approved' ? '#16a34a' : ($estado['cls'] == 'review' ? '#0891b2' : '#d97706');
                    ?>
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                        <div style="padding:14px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                            <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:<?php echo e($bgIcon); ?>;color:<?php echo e($colorIcon); ?>;flex-shrink:0;">
                                <i class="<?php echo e($doc['icono']); ?>"></i>
                            </div>
                            <div style="flex:1;min-width:0;font-size:.875rem;font-weight:600;"><?php echo e($doc['nombre']); ?></div>
                            <span style="padding:4px 10px;border-radius:20px;font-size:.6875rem;font-weight:600;text-transform:uppercase;background:<?php echo e($bgIcon); ?>;color:<?php echo e($colorIcon); ?>;">
                                <?php echo e($estado['label']); ?>

                            </span>
                        </div>
                        <div style="padding:16px;">
                            <?php if($doc['archivo']): ?>
                                <div style="display:flex;align-items:center;gap:12px;padding:12px;background:#f8fafc;border-radius:8px;">
                                    <div style="width:36px;height:36px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:1.1rem;">
                                        <i class="ri-file-pdf-fill"></i>
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div style="font-size:.8125rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($doc['tipo']); ?>.pdf</div>
                                        <div style="font-size:.6875rem;display:flex;align-items:center;gap:4px;color:<?php echo e($doc['verificado'] ? '#16a34a' : '#d97706'); ?>;">
                                            <?php if($doc['verificado']): ?>
                                                <i class="ri-shield-check-fill"></i> Verificado
                                            <?php else: ?>
                                                <i class="ri-time-fill"></i> En revisión
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div style="text-align:center;padding:20px;color:#94a3b8;font-size:.85rem;">
                                    <i class="ri-file-unknown-line" style="font-size:1.5rem;display:block;margin-bottom:6px;"></i>
                                    Documento no subido
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <h4 style="font-size:1rem;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="ri-graduation-cap-line" style="color:#fc7b04;"></i> Formación Académica
            </h4>
            <?php if($estudioDocente): ?>
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:16px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:#e0f2fe;color:#0891b2;">
                            <i class="ri-school-line"></i>
                        </div>
                        <div>
                            <div style="font-size:.875rem;font-weight:600;"><?php echo e($estudioDocente->grado_academico->nombre ?? 'Sin grado'); ?></div>
                            <div style="font-size:.75rem;color:#64748b;"><?php echo e($estudioDocente->profesion->nombre ?? 'Sin profesión'); ?> | <?php echo e($estudioDocente->estado ?? '—'); ?></div>
                        </div>
                        <span class="ms-auto badge" style="background:#dcfce7;color:#16a34a;font-size:.6875rem;padding:4px 10px;border-radius:20px;">Principal</span>
                    </div>
                    <?php if($estudioDocente->universidad): ?>
                        <div style="font-size:.8125rem;color:#64748b;border-top:1px solid #e2e8f0;padding-top:12px;">
                            <i class="ri-building-line me-1"></i> <?php echo e($estudioDocente->universidad->nombre); ?>

                        </div>
                    <?php endif; ?>
                </div>
                <?php
                    $docsAcademicoDoc = [
                        [
                            'nombre'    => 'Título/Bachiller',
                            'icono'     => 'ri-graduation-cap-line',
                            'archivo'   => $estudioDocente->documento_academico,
                            'verificado'=> $estudioDocente->documento_academico_verificado,
                            'tipo'      => 'documento_academico',
                        ],
                        [
                            'nombre'    => 'Provisión Nacional',
                            'icono'     => 'ri-government-line',
                            'archivo'   => $estudioDocente->documento_provision_nacional,
                            'verificado'=> $estudioDocente->documento_provision_verificado,
                            'tipo'      => 'documento_provision_nacional',
                        ],
                    ];
                ?>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;">
                    <?php $__currentLoopData = $docsAcademicoDoc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $estado = $estadoDocDoc($doc['archivo'], $doc['verificado']);
                            $bgIcon   = $estado['cls'] == 'approved' ? '#dcfce7' : ($estado['cls'] == 'review' ? '#e0f2fe' : '#fef3c7');
                            $colorIcon= $estado['cls'] == 'approved' ? '#16a34a' : ($estado['cls'] == 'review' ? '#0891b2' : '#d97706');
                        ?>
                        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                            <div style="padding:14px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                                <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:<?php echo e($bgIcon); ?>;color:<?php echo e($colorIcon); ?>;flex-shrink:0;">
                                    <i class="<?php echo e($doc['icono']); ?>"></i>
                                </div>
                                <div style="flex:1;min-width:0;font-size:.875rem;font-weight:600;"><?php echo e($doc['nombre']); ?></div>
                                <span style="padding:4px 10px;border-radius:20px;font-size:.6875rem;font-weight:600;text-transform:uppercase;background:<?php echo e($bgIcon); ?>;color:<?php echo e($colorIcon); ?>;">
                                    <?php echo e($estado['label']); ?>

                                </span>
                            </div>
                            <div style="padding:16px;">
                                <?php if($doc['archivo']): ?>
                                    <div style="display:flex;align-items:center;gap:12px;padding:12px;background:#f8fafc;border-radius:8px;">
                                        <div style="width:36px;height:36px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:1.1rem;">
                                            <i class="ri-file-pdf-fill"></i>
                                        </div>
                                        <div style="flex:1;min-width:0;">
                                            <div style="font-size:.8125rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($doc['tipo']); ?>.pdf</div>
                                            <div style="font-size:.6875rem;display:flex;align-items:center;gap:4px;color:<?php echo e($doc['verificado'] ? '#16a34a' : '#d97706'); ?>;">
                                                <?php if($doc['verificado']): ?>
                                                    <i class="ri-shield-check-fill"></i> Verificado
                                                <?php else: ?>
                                                    <i class="ri-time-fill"></i> En revisión
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div style="text-align:center;padding:20px;color:#94a3b8;font-size:.85rem;">
                                        <i class="ri-file-unknown-line" style="font-size:1.5rem;display:block;margin-bottom:6px;"></i>
                                        Documento no subido
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div style="text-align:center;padding:40px 20px;background:#fff;border:1px solid #e2e8f0;border-radius:12px;">
                    <i class="ri-user-unfollow-line" style="font-size:2.5rem;color:#94a3b8;opacity:.5;"></i>
                    <p style="margin:16px 0 0;color:#64748b;">Sin registro académico registrado</p>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="est-tabs-body" id="tab-academico-docente">

            <div class="tab-banner academico">
                <div class="tab-banner-icon"><i class="ri-book-3-line"></i></div>
                <div class="tab-banner-body">
                    <p class="tab-banner-title">Mis Módulos Asignados</p>
                    <p class="tab-banner-sub">Programas en los que participas como docente</p>
                </div>
                <?php $totalOfertasDoc = $modulosDocente->groupBy('ofertas_academica_id')->count(); ?>
                <span class="tab-banner-badge">
                    <i class="ri-stack-line"></i> <?php echo e($totalOfertasDoc); ?> oferta(s)
                </span>
            </div>

            <?php if($modulosDocente->isEmpty()): ?>
                <div class="est-no-cuenta">
                    <i class="ri-book-open-line"></i>
                    <h5>Sin módulos asignados</h5>
                    <p>Aún no tienes módulos asignados como docente. Contacta con administración para más información.</p>
                    <?php if($moodleDocenteId): ?>
                        <p style="margin-top:.3rem;font-size:.8rem;color:var(--doc-success);"><i class="ri-checkbox-circle-fill"></i> Tu cuenta de Moodle está activa. Cuando te asignen un módulo podrás acceder a los cursos.</p>
                    <?php else: ?>
                        <p style="margin-top:.3rem;font-size:.8rem;color:var(--doc-text-muted);"><i class="ri-information-line"></i> Aún no tienes cuenta en Moodle. Puedes crearla desde el panel de administración.</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php
                    $modulosPorOferta = $modulosDocente->groupBy('ofertas_academica_id');
                ?>

                
                <?php if($modulosPorOferta->count() > 1): ?>
                <div class="acad-prog-selector">
                    <div class="acad-prog-selector-header">
                        <i class="ri-book-open-line"></i>
                        <span>Seleccionar oferta académica</span>
                        <span class="acad-prog-count"><?php echo e($modulosPorOferta->count()); ?></span>
                    </div>
                    <div class="acad-prog-pills">
                        <?php $__currentLoopData = $modulosPorOferta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ofertaId => $mods): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $primerMod    = $mods->first();
                            $nombreOferta = $primerMod->ofertaAcademica?->programa?->nombre
                                ?? $primerMod->ofertaAcademica?->posgrado?->nombre
                                ?? 'Oferta #' . $ofertaId;
                        ?>
                        <button type="button"
                            class="acad-prog-pill est-oferta-tab-btn <?php echo e($loop->first ? 'active' : ''); ?>"
                            data-target="doc-oferta-<?php echo e($loop->index); ?>">
                            <span class="acad-prog-pill-num"><?php echo e($loop->iteration); ?></span>
                            <div class="acad-prog-pill-info">
                                <span class="acad-prog-pill-name"><?php echo e($nombreOferta); ?></span>
                                <?php if($primerMod->ofertaAcademica?->codigo): ?>
                                    <span class="acad-prog-pill-code"><?php echo e($primerMod->ofertaAcademica->codigo); ?></span>
                                <?php endif; ?>
                            </div>
                            <span class="acad-prog-pill-estado inscrito"><?php echo e($mods->count()); ?> mód.</span>
                        </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

                
                <?php $__currentLoopData = $modulosPorOferta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ofertaId => $mods): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $primerMod    = $mods->first();
                    $oferta       = $primerMod->ofertaAcademica;
                    $nombreOferta = $oferta?->programa?->nombre
                        ?? $oferta?->posgrado?->nombre
                        ?? 'Oferta #' . $ofertaId;
                ?>
                <div class="est-oferta-content <?php echo e($loop->first ? 'active' : ''); ?>"
                    id="doc-oferta-<?php echo e($loop->index); ?>">

                    
                    <div class="acad-prog-header-bar">
                        <div class="acad-prog-header-info">
                            <div class="acad-prog-header-name"><?php echo e($nombreOferta); ?></div>
                            <div class="acad-prog-header-meta">
                                <?php if($oferta?->codigo): ?>
                                    <span><i class="ri-hashtag"></i><?php echo e($oferta->codigo); ?></span>
                                <?php endif; ?>
                                <?php if($oferta?->fecha_inicio): ?>
                                    <span><i class="ri-calendar-line"></i>Inicio: <?php echo e(\Carbon\Carbon::parse($oferta->fecha_inicio)->format('d/m/Y')); ?></span>
                                <?php endif; ?>
                                <span><i class="ri-stack-line"></i><?php echo e($mods->count()); ?> módulo(s) asignado(s)</span>
                            </div>
                        </div>
                        <span class="est-estado-badge inscrito">Docente</span>
                    </div>

                    
                    <div class="acad-modulos-grid">
                        <?php $__currentLoopData = $mods->sortBy('n_modulo'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $modColor = $modulo->color ?? '#6366f1'; ?>
                        <div class="acad-mod-card">
                            <div class="acad-mod-stripe" style="background:<?php echo e($modColor); ?>;"></div>
                            <div class="acad-mod-body">
                                <div class="acad-mod-top">
                                    <span class="acad-mod-num"
                                        style="background:<?php echo e($modColor); ?>22;color:<?php echo e($modColor); ?>;">
                                        M<?php echo e($modulo->n_modulo); ?>

                                    </span>
                                    <span class="acad-mod-name"><?php echo e($modulo->nombre); ?></span>
                                    <span class="acad-mod-badge activo">
                                        <i class="ri-user-settings-line"></i> Docente
                                    </span>
                                </div>
                                <div class="acad-mod-meta">
                                    <?php if($modulo->fecha_inicio): ?>
                                        <span>
                                            <i class="ri-calendar-line"></i>
                                            <?php echo e(\Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y')); ?>

                                            <?php if($modulo->fecha_fin): ?>
                                                — <?php echo e(\Carbon\Carbon::parse($modulo->fecha_fin)->format('d/m/Y')); ?>

                                            <?php endif; ?>
                                        </span>
                                    <?php endif; ?>
                                    <span>
                                        <i class="ri-time-line"></i>
                                        <?php echo e($modulo->horarios->count()); ?> sesión(es)
                                    </span>
                                </div>
                                <div class="acad-mod-actions">
                                    <a href="<?php echo e(route('virtual.docente.modulo', $modulo->id)); ?>"
                                        class="acad-mod-btn">
                                        <i class="ri-layout-grid-line"></i> Ver detalle
                                    </a>
                                    <?php if($modulo->moodle_course_id): ?>
                                        <a href="<?php echo e(route('virtual.moodle-sso', ['target' => config('moodle.url') . '/course/view.php?id=' . $modulo->moodle_course_id])); ?>"
                                            target="_blank" class="acad-mod-btn acad-mod-btn-go">
                                            <i class="ri-external-link-line"></i> Ir al curso
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php endif; ?>
        </div>

        <div class="est-tabs-body" id="tab-horario-docente">
            <?php if($modulosDocente->isEmpty()): ?>
                <div class="est-empty-state">
                    <i class="ri-calendar-close-line"></i>
                    <h5>Sin módulos asignados</h5>
                    <p>No tienes módulos asignados como docente para mostrar horario.</p>
                    <?php if($moodleDocenteId): ?>
                        <p style="margin-top:.3rem;font-size:.8rem;color:var(--doc-success);"><i class="ri-checkbox-circle-fill"></i> Tu cuenta de Moodle está activa. Cuando te asignen un módulo podrás ver el horario.</p>
                    <?php else: ?>
                        <p style="margin-top:.3rem;font-size:.8rem;color:var(--doc-text-muted);"><i class="ri-information-line"></i> Aún no tienes cuenta en Moodle. Puedes crearla desde el panel de administración.</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="cronograma-container d-flex" style="min-height:600px;">

                    
                    <div class="cronograma-sidebar">
                        <div class="cronograma-sidebar-head">
                            <i class="ri-book-3-line"></i>
                            <span>Oferta Académica</span>
                        </div>
                        <div class="cronograma-sidebar-body">
                            <select class="cronograma-select"
                                    id="select-oferta-horario-docente"
                                    onchange="cargarModulosHorarioDocente()">
                                <option value="">Seleccionar oferta académica</option>
                                <?php $__currentLoopData = $ofertasHorariosDocente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ofHD): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ofHD['id']); ?>">
                                        <?php echo e($ofHD['codigo']); ?> — <?php echo e($ofHD['nombre']); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <button class="cronograma-btn-all active"
                                    id="btnTodosModulosHorarioDocente"
                                    onclick="verTodosModulosHorarioDocente()">
                                <i class="ri-layout-grid-line"></i> Todos los módulos
                            </button>
                            <div id="modulosSidebarListHorarioDocente">
                                <div class="cronograma-sidebar-empty">
                                    <i class="ri-arrow-up-line"></i>
                                    Selecciona una oferta académica
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="cronograma-main">
                        <div class="cronograma-title-section">
                            <div class="cronograma-title-left">
                                <div class="cronograma-title-icon">
                                    <i class="ri-calendar-check-line"></i>
                                </div>
                                <div class="cronograma-title-text">
                                    <h4>Mi Horario de Clases</h4>
                                    <span>Sesiones programadas como docente</span>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                                <div style="display:flex;align-items:center;gap:.4rem;font-size:.72rem;color:#64748b;">
                                    <span class="cronograma-legend-dot confirmed"></span><span>Confirmado</span>
                                    <span class="cronograma-legend-dot postponed"></span><span>Postergado</span>
                                </div>
                                <div id="moduloSeleccionadoBadgeHorarioDocente"
                                     class="cronograma-filter-badge" style="display:none;">
                                    <span class="dot"></span>
                                    <span class="modulo-badge-name"></span>
                                    <button type="button" title="Quitar filtro"
                                            onclick="verTodosModulosHorarioDocente()">
                                        <i class="ri-close-circle-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="cronograma-calendar-wrapper">
                            <div id="calendarHorarioDocente"></div>
                        </div>
                    </div>

                </div>
            <?php endif; ?>
        </div>

        <?php endif; ?>

        </div>
        <div id="content-estudiante" <?php echo $perfilActivo !== 'estudiante' ? 'style="display:none"' : ''; ?>>
        <?php
            $tieneFoto =
                $persona && $persona->fotografia && file_exists(public_path('images/personas/' . $persona->fotografia));
            $avatarUrl = $tieneFoto ? asset('images/personas/' . $persona->fotografia) : null;
            $nombreCompleto = $persona
                ? trim(
                    ($persona->nombres ?? '') .
                        ' ' .
                        ($persona->apellido_paterno ?? '') .
                        ' ' .
                        ($persona->apellido_materno ?? ''),
                )
                : 'Estudiante';
            $iniciales = collect(explode(' ', $nombreCompleto))
                ->filter()
                ->take(2)
                ->map(fn($p) => strtoupper($p[0]))
                ->implode('');
            $edad =
                $persona && $persona->fecha_nacimiento ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->age : null;
            $ubicacion =
                $persona && $persona->ciudad
                    ? optional($persona->ciudad)->nombre .
                        ', ' .
                        (optional(optional($persona->ciudad)->departamento)->nombre ?? '')
                    : null;
            $estudio = $persona?->estudios?->first();
        ?>
        <div class="est-tabs-body active" id="tab-personal">
            <div class="est-ci-wrap">
                <div class="est-ci-stripe"></div>
                <div class="est-ci-body">
                    
                    <div class="est-ci-left">
                        <div class="est-ci-foto-label"><i class="ri-building-2-line"></i><span>INNOVA CIENCIA</span></div>
                        <div class="est-ci-foto">
                            <img src="<?php echo e($avatarUrl ?? ''); ?>" alt="Foto" id="est-ci-foto-img"
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
                                    <span
                                        class="est-ci-qd-val"><?php echo e(trim($persona->carnet . ($persona->expedido ? ' ' . trim($persona->expedido) : ''))); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if($persona?->fecha_nacimiento): ?>
                                <div class="est-ci-qd-item">
                                    <i class="ri-cake-line"></i>
                                    <span class="est-ci-qd-label">Nacimiento</span>
                                    <span
                                        class="est-ci-qd-val"><?php echo e(\Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y')); ?></span>
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
                                    <span
                                        class="est-ci-qd-val"><?php echo e($persona->sexo == 'M' ? 'Masculino' : ($persona->sexo == 'F' ? 'Femenino' : '—')); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="pers-acc-chips">
                            <?php if($moodleUserId): ?>
                            <div class="pers-acc-chip pers-chip-ok">
                                <i class="ri-links-line"></i><span>Moodle: Activo</span>
                            </div>
                            <?php else: ?>
                            <div class="pers-acc-chip pers-chip-no">
                                <i class="ri-links-line"></i><span>Moodle: Sin cuenta</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="est-ci-center">
                        <div class="est-ci-nombre-wrap">
                            <div>
                                <div class="est-ci-nombre"><?php echo e($nombreCompleto); ?></div>
                                <div class="est-ci-estado-label">Estudiante</div>
                            </div>
                            <?php if($estudiante): ?>
                                <span
                                    class="est-ci-estado-badge est-ci-badge-<?php echo e(($estudiante->estado ?? 'Activo') === 'Activo' ? 'activo' : 'inactivo'); ?>">
                                    <i class="ri-checkbox-circle-line"></i>
                                    <?php echo e($estudiante->estado ?? 'Activo'); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="est-ci-section-title"><i class="ri-contacts-line"></i> Datos de Contacto</div>
                        <?php if($persona?->correo): ?>
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-mail-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Correo electrónico</div>
                                <div class="pers-contact-val"><a href="mailto:<?php echo e($persona->correo); ?>" class="pers-contact-link"><?php echo e($persona->correo); ?></a></div>
                            </div>
                            <a href="mailto:<?php echo e($persona->correo); ?>" class="pers-contact-act" title="Enviar correo"><i class="ri-send-plane-line"></i></a>
                        </div>
                        <?php endif; ?>
                        <?php if($persona?->celular): ?>
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-smartphone-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Celular</div>
                                <div class="pers-contact-val"><a href="tel:<?php echo e($persona->celular); ?>" class="pers-contact-link"><?php echo e($persona->celular); ?></a></div>
                            </div>
                            <a href="https://wa.me/<?php echo e(preg_replace('/\D/', '', $persona->celular)); ?>" target="_blank" class="pers-contact-act wa" title="WhatsApp"><i class="ri-whatsapp-line"></i></a>
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
                        <?php if($persona?->estado_civil): ?>
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-heart-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Estado Civil</div>
                                <div class="pers-contact-val"><?php echo e($persona->estado_civil); ?></div>
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
                    </div>

                    
                    <div class="est-ci-right">
                        <div class="est-ci-right-header">
                            <i class="ri-graduation-cap-line"></i><span>Datos del Estudiante</span>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <?php if($estudio?->universidad): ?>
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-building-4-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Universidad</div>
                                    <div class="pers-info-val"><?php echo e($estudio->universidad->nombre ?? '—'); ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if($estudio?->profesion): ?>
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-graduation-cap-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Carrera / Programa</div>
                                    <div class="pers-info-val"><?php echo e($estudio->profesion->nombre ?? '—'); ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if($estudiante): ?>
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
                            <?php endif; ?>
                            <?php if($inscripciones->count()): ?>
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-book-open-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Ofertas Académicas</div>
                                    <div class="pers-info-val"><?php echo e($inscripciones->count()); ?> inscripción(es)</div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php if($inscripciones->count() > 0): ?>
                        <div class="pers-section-sep">
                            <i class="ri-book-2-line"></i> Programas
                            <span style="background:rgba(252,123,4,.1);color:#c96004;padding:1px 7px;border-radius:5px;font-size:.65rem;"><?php echo e($inscripciones->count()); ?></span>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:6px;overflow-y:auto;max-height:200px;padding-right:2px;">
                            <?php $__currentLoopData = $inscripciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ins): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $nombreOferta = $ins->ofertaAcademica?->programa?->nombre ??
                                    ($ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta #' . $ins->ofertas_academica_id);
                                $saldoPendiente = 0;
                                foreach ($ins->cuotas as $cuota) {
                                    $pagado = $cuota->pagosCuota->sum('monto_bs');
                                    $pendiente = $cuota->monto_bs - $pagado;
                                    if ($pendiente > 0) { $saldoPendiente += $pendiente; }
                                }
                            ?>
                            <div class="pers-study-card">
                                <span class="pers-study-grado"><i class="ri-book-2-line"></i> <?php echo e($ins->estado); ?></span>
                                <div class="pers-study-profesion"><?php echo e($nombreOferta); ?></div>
                                <?php if($saldoPendiente > 0): ?>
                                <div class="pers-study-univ">
                                    <i class="ri-money-dollar-circle-line" style="font-size:.7rem;flex-shrink:0;color:#ef4444;"></i>
                                    <span style="color:#ef4444;">Bs. <?php echo e(number_format($saldoPendiente, 2, ',', '.')); ?> pendiente</span>
                                </div>
                                <?php else: ?>
                                <div class="pers-study-univ">
                                    <i class="ri-checkbox-circle-line" style="font-size:.7rem;flex-shrink:0;color:#16a34a;"></i>
                                    <span style="color:#16a34a;">Al día</span>
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
        </div>

        
        <?php
            $estadoDoc = function ($archivo, $verificado) {
                if (!$archivo) {
                    return ['label' => 'Pendiente', 'cls' => 'pending', 'icon' => 'ri-add-circle-line'];
                }
                if ($verificado) {
                    return ['label' => 'Aprobado', 'cls' => 'approved', 'icon' => 'ri-checkbox-circle-fill'];
                }
                return ['label' => 'En revisión', 'cls' => 'review', 'icon' => 'ri-time-line'];
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
                if ($d['verificado']) {
                    $verificados++;
                }
            }
            if ($estudioPrincipal) {
                $totalDocs += 2;
                if ($estudioPrincipal->documento_academico_verificado) {
                    $verificados++;
                }
                if ($estudioPrincipal->documento_provision_verificado) {
                    $verificados++;
                }
            }
            $pctDocs = $totalDocs > 0 ? ($verificados / $totalDocs) * 100 : 0;
        ?>
        <div class="est-tabs-body" id="tab-documentos">
            <div
                style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:16px;">
                <h3 style="margin:0;font-size:1.1rem;font-weight:600;display:flex;align-items:center;gap:8px;">
                    <i class="ri-folder-shield-line" style="color:#fc7b04;"></i> Documentación
                </h3>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="flex:1;max-width:150px;height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                        <div
                            style="height:100%;background:linear-gradient(90deg,#fc7b04,#f97316);border-radius:4px;width:<?php echo e($pctDocs); ?>%;transition:width .3s;">
                        </div>
                    </div>
                    <span
                        style="font-size:.875rem;font-weight:700;color:#fc7b04;"><?php echo e(number_format($pctDocs, 0)); ?>%</span>
                </div>
            </div>

            
            <h4 style="font-size:1rem;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="ri-id-card-line" style="color:#fc7b04;"></i> Documentación Personal
            </h4>
            <div
                style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:32px;">
                <?php $__currentLoopData = $docsIdentidad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $estado = $estadoDoc($doc['archivo'], $doc['verificado']);
                        $bgIcon =
                            $estado['cls'] == 'approved'
                                ? '#dcfce7'
                                : ($estado['cls'] == 'review'
                                    ? '#e0f2fe'
                                    : '#fef3c7');
                        $colorIcon =
                            $estado['cls'] == 'approved'
                                ? '#16a34a'
                                : ($estado['cls'] == 'review'
                                    ? '#0891b2'
                                    : '#d97706');
                    ?>
                    <div
                        style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                        <div
                            style="padding:14px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                            <div
                                style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:<?php echo e($bgIcon); ?>;color:<?php echo e($colorIcon); ?>;flex-shrink:0;">
                                <i class="<?php echo e($doc['icono']); ?>"></i>
                            </div>
                            <div style="flex:1;min-width:0;font-size:.875rem;font-weight:600;"><?php echo e($doc['nombre']); ?></div>
                            <span
                                style="padding:4px 10px;border-radius:20px;font-size:.6875rem;font-weight:600;text-transform:uppercase;background:<?php echo e($bgIcon); ?>;color:<?php echo e($colorIcon); ?>;">
                                <?php echo e($estado['label']); ?>

                            </span>
                        </div>
                        <div style="padding:16px;">
                            <?php if($doc['archivo']): ?>
                                <div
                                    style="display:flex;align-items:center;gap:12px;padding:12px;background:#f8fafc;border-radius:8px;">
                                    <div
                                        style="width:36px;height:36px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:1.1rem;">
                                        <i class="ri-file-pdf-fill"></i>
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div
                                            style="font-size:.8125rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            <?php echo e($doc['tipo']); ?>.pdf</div>
                                        <div
                                            style="font-size:.6875rem;display:flex;align-items:center;gap:4px;color:<?php echo e($doc['verificado'] ? '#16a34a' : '#d97706'); ?>;">
                                            <?php if($doc['verificado']): ?>
                                                <i class="ri-shield-check-fill"></i> Verificado
                                            <?php else: ?>
                                                <i class="ri-time-fill"></i> En revisión
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div style="text-align:center;padding:20px;color:#94a3b8;font-size:.85rem;">
                                    <i class="ri-file-unknown-line"
                                        style="font-size:1.5rem;display:block;margin-bottom:6px;"></i>
                                    Documento no subido
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <h4 style="font-size:1rem;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="ri-graduation-cap-line" style="color:#fc7b04;"></i> Formación Académica
            </h4>
            <?php if($estudioPrincipal): ?>
                <div
                    style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:16px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <div
                            style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:#e0f2fe;color:#0891b2;">
                            <i class="ri-school-line"></i>
                        </div>
                        <div>
                            <div style="font-size:.875rem;font-weight:600;">
                                <?php echo e($estudioPrincipal->grado_academico->nombre ?? 'Sin grado'); ?></div>
                            <div style="font-size:.75rem;color:#64748b;">
                                <?php echo e($estudioPrincipal->profesion->nombre ?? 'Sin profesión'); ?> |
                                <?php echo e($estudioPrincipal->estado ?? '—'); ?></div>
                        </div>
                        <span class="ms-auto badge"
                            style="background:#dcfce7;color:#16a34a;font-size:.6875rem;padding:4px 10px;border-radius:20px;">Principal</span>
                    </div>
                    <?php if($estudioPrincipal->universidad): ?>
                        <div style="font-size:.8125rem;color:#64748b;border-top:1px solid #e2e8f0;padding-top:12px;">
                            <i class="ri-building-line me-1"></i> <?php echo e($estudioPrincipal->universidad->nombre); ?>

                        </div>
                    <?php endif; ?>
                </div>
                <?php
                    $docsAcademico = [
                        [
                            'nombre' => 'Título/Bachiller',
                            'icono' => 'ri-graduation-cap-line',
                            'archivo' => $estudioPrincipal->documento_academico,
                            'verificado' => $estudioPrincipal->documento_academico_verificado,
                            'tipo' => 'documento_academico',
                        ],
                        [
                            'nombre' => 'Provisión Nacional',
                            'icono' => 'ri-government-line',
                            'archivo' => $estudioPrincipal->documento_provision_nacional,
                            'verificado' => $estudioPrincipal->documento_provision_verificado,
                            'tipo' => 'documento_provision_nacional',
                        ],
                    ];
                ?>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;">
                    <?php $__currentLoopData = $docsAcademico; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $estado = $estadoDoc($doc['archivo'], $doc['verificado']);
                            $bgIcon =
                                $estado['cls'] == 'approved'
                                    ? '#dcfce7'
                                    : ($estado['cls'] == 'review'
                                        ? '#e0f2fe'
                                        : '#fef3c7');
                            $colorIcon =
                                $estado['cls'] == 'approved'
                                    ? '#16a34a'
                                    : ($estado['cls'] == 'review'
                                        ? '#0891b2'
                                        : '#d97706');
                        ?>
                        <div
                            style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                            <div
                                style="padding:14px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                                <div
                                    style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:<?php echo e($bgIcon); ?>;color:<?php echo e($colorIcon); ?>;flex-shrink:0;">
                                    <i class="<?php echo e($doc['icono']); ?>"></i>
                                </div>
                                <div style="flex:1;min-width:0;font-size:.875rem;font-weight:600;"><?php echo e($doc['nombre']); ?>

                                </div>
                                <span
                                    style="padding:4px 10px;border-radius:20px;font-size:.6875rem;font-weight:600;text-transform:uppercase;background:<?php echo e($bgIcon); ?>;color:<?php echo e($colorIcon); ?>;">
                                    <?php echo e($estado['label']); ?>

                                </span>
                            </div>
                            <div style="padding:16px;">
                                <?php if($doc['archivo']): ?>
                                    <div
                                        style="display:flex;align-items:center;gap:12px;padding:12px;background:#f8fafc;border-radius:8px;">
                                        <div
                                            style="width:36px;height:36px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:1.1rem;">
                                            <i class="ri-file-pdf-fill"></i>
                                        </div>
                                        <div style="flex:1;min-width:0;">
                                            <div
                                                style="font-size:.8125rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                <?php echo e($doc['tipo']); ?>.pdf</div>
                                            <div
                                                style="font-size:.6875rem;display:flex;align-items:center;gap:4px;color:<?php echo e($doc['verificado'] ? '#16a34a' : '#d97706'); ?>;">
                                                <?php if($doc['verificado']): ?>
                                                    <i class="ri-shield-check-fill"></i> Verificado
                                                <?php else: ?>
                                                    <i class="ri-time-fill"></i> En revisión
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div style="text-align:center;padding:20px;color:#94a3b8;font-size:.85rem;">
                                        <i class="ri-file-unknown-line"
                                            style="font-size:1.5rem;display:block;margin-bottom:6px;"></i>
                                        Documento no subido
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div
                    style="text-align:center;padding:40px 20px;background:#fff;border:1px solid #e2e8f0;border-radius:12px;">
                    <i class="ri-user-unfollow-line" style="font-size:2.5rem;color:#94a3b8;opacity:.5;"></i>
                    <p style="margin:16px 0 0;color:#64748b;">Sin registro académico registrado</p>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="est-tabs-body" id="tab-academico">

            <div class="tab-banner academico">
                <div class="tab-banner-icon"><i class="ri-book-3-line"></i></div>
                <div class="tab-banner-body">
                    <p class="tab-banner-title">Mis Programas Académicos</p>
                    <p class="tab-banner-sub">Módulos matriculados y acceso a cursos por inscripción</p>
                </div>
                <span class="tab-banner-badge">
                    <i class="ri-stack-line"></i> <?php echo e($inscripciones->count()); ?> programa(s)
                </span>
            </div>

            <?php if($inscripciones->count() > 0): ?>

                
                <?php if($inscripciones->count() > 1): ?>
                <div class="acad-prog-selector">
                    <div class="acad-prog-selector-header">
                        <i class="ri-book-open-line"></i>
                        <span>Seleccionar programa</span>
                        <span class="acad-prog-count"><?php echo e($inscripciones->count()); ?></span>
                    </div>
                    <div class="acad-prog-pills">
                        <?php $__currentLoopData = $inscripciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $insc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $pillEstado = match ($insc->estado) {
                                'Inscrito', 'Confirmado' => 'inscrito',
                                'Pre-Inscrito'           => 'pendiente',
                                default                  => 'otro',
                            };
                        ?>
                        <button type="button"
                            class="acad-prog-pill est-oferta-tab-btn <?php echo e($key == 0 ? 'active' : ''); ?>"
                            data-target="academico-oferta-<?php echo e($key); ?>">
                            <span class="acad-prog-pill-num"><?php echo e($key + 1); ?></span>
                            <div class="acad-prog-pill-info">
                                <span class="acad-prog-pill-name">
                                    <?php echo e($insc->ofertaAcademica?->programa?->nombre ?? ($insc->ofertaAcademica?->posgrado?->nombre ?? 'Programa ' . ($key + 1))); ?>

                                </span>
                                <?php if($insc->ofertaAcademica?->codigo): ?>
                                <span class="acad-prog-pill-code"><?php echo e($insc->ofertaAcademica->codigo); ?></span>
                                <?php endif; ?>
                            </div>
                            <span class="acad-prog-pill-estado <?php echo e($pillEstado); ?>"><?php echo e($insc->estado); ?></span>
                        </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

                
                <?php $__currentLoopData = $inscripciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $insc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $oferta     = $insc->ofertaAcademica;
                    $programa   = $oferta?->programa;
                    $matriculas = $insc->moodleMatriculas->sortBy(fn($m) => $m->modulo?->n_modulo);
                    $estadoClass = match ($insc->estado) {
                        'Inscrito', 'Confirmado' => 'inscrito',
                        'Pre-Inscrito'           => 'pendiente',
                        default                  => 'otro',
                    };
                ?>
                <div class="est-oferta-content <?php echo e($key == 0 ? 'active' : ''); ?>"
                    id="academico-oferta-<?php echo e($key); ?>">

                    
                    <div class="acad-prog-header-bar">
                        <div class="acad-prog-header-info">
                            <div class="acad-prog-header-name">
                                <?php echo e($programa?->nombre ?? ($oferta?->posgrado?->nombre ?? 'Programa')); ?>

                            </div>
                            <div class="acad-prog-header-meta">
                                <?php if($oferta?->codigo): ?>
                                    <span><i class="ri-hashtag"></i><?php echo e($oferta->codigo); ?></span>
                                <?php endif; ?>
                                <?php if($oferta?->fecha_inicio): ?>
                                    <span><i class="ri-calendar-line"></i>Inicio: <?php echo e(\Carbon\Carbon::parse($oferta->fecha_inicio)->format('d/m/Y')); ?></span>
                                <?php endif; ?>
                                <span><i class="ri-stack-line"></i><?php echo e($matriculas->count()); ?> módulo(s)</span>
                            </div>
                        </div>
                        <span class="est-estado-badge <?php echo e($estadoClass); ?>"><?php echo e($insc->estado); ?></span>
                    </div>

                    
                    <?php if($matriculas->isEmpty()): ?>
                        <div class="acad-empty-state">
                            <i class="ri-information-line"></i>
                            <p>Aún no tienes módulos matriculados en este programa.</p>
                        </div>
                    <?php else: ?>
                        <div class="acad-modulos-grid">
                            <?php $__currentLoopData = $matriculas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $matricula): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $modulo      = $matricula->modulo;
                                $tieneMoodle = $matricula->moodle_course_id && $matricula->moodle_user_id;
                                $suspendido  = (bool) $matricula->acceso_suspendido;
                                $modColor    = $modulo->color ?? '#6366f1';
                            ?>
                            <?php if(!$modulo): ?> <?php continue; ?> <?php endif; ?>

                            <div class="acad-mod-card">
                                <div class="acad-mod-stripe" style="background:<?php echo e($modColor); ?>;"></div>
                                <div class="acad-mod-body">
                                    <div class="acad-mod-top">
                                        <span class="acad-mod-num"
                                            style="background:<?php echo e($modColor); ?>22;color:<?php echo e($modColor); ?>;">
                                            M<?php echo e($modulo->n_modulo); ?>

                                        </span>
                                        <span class="acad-mod-name"><?php echo e($modulo->nombre); ?></span>
                                        <?php if($tieneMoodle): ?>
                                            <?php if($suspendido): ?>
                                                <span class="acad-mod-badge blocked">
                                                    <i class="ri-lock-line"></i> Bloqueado
                                                </span>
                                            <?php else: ?>
                                                <span class="acad-mod-badge activo">
                                                    <i class="ri-checkbox-circle-line"></i> Activo
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="acad-mod-badge pending">
                                                <i class="ri-time-line"></i> Sin acceso
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="acad-mod-meta">
                                        <?php if($modulo->fecha_inicio): ?>
                                            <span>
                                                <i class="ri-calendar-line"></i>
                                                <?php echo e(\Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y')); ?>

                                                <?php if($modulo->fecha_fin): ?>
                                                    — <?php echo e(\Carbon\Carbon::parse($modulo->fecha_fin)->format('d/m/Y')); ?>

                                                <?php endif; ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if($modulo->docente?->persona): ?>
                                            <span>
                                                <i class="ri-user-line"></i>
                                                <?php echo e(trim(($modulo->docente->persona->nombres ?? '') . ' ' . ($modulo->docente->persona->apellido_paterno ?? ''))); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($tieneMoodle): ?>
                                        <?php if($suspendido): ?>
                                            <div class="acad-mod-blocked">
                                                <i class="ri-lock-2-line"></i>
                                                Acceso bloqueado por pendientes de pago
                                            </div>
                                        <?php else: ?>
                                            <div class="acad-mod-actions">
                                                <button class="acad-mod-btn btn-ver-actividades"
                                                    data-modulo="<?php echo e($modulo->id); ?>"
                                                    data-panel="panel-mod-<?php echo e($modulo->id); ?>">
                                                    <i class="ri-eye-line"></i> Actividades
                                                </button>
                                                <?php
                                                    $moodleBase = rtrim(config('moodle.url'), '/');
                                                    $courseUrl  = $moodleBase . '/course/view.php?id=' . $matricula->moodle_course_id;
                                                    $ssoUrl     = route('virtual.moodle-sso') . '?target=' . urlencode($courseUrl);
                                                ?>
                                                <a href="<?php echo e($ssoUrl); ?>" target="_blank"
                                                    class="acad-mod-btn acad-mod-btn-go">
                                                    <i class="ri-external-link-line"></i> Ir al curso
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if($tieneMoodle && !$suspendido): ?>
                                <div class="est-act-panel" id="panel-mod-<?php echo e($modulo->id); ?>">
                                    <div class="est-spinner" id="spinner-mod-<?php echo e($modulo->id); ?>">
                                        <div class="spinner-border spinner-border-sm"></div> Cargando actividades…
                                    </div>
                                    <div id="contenido-mod-<?php echo e($modulo->id); ?>"></div>
                                </div>
                            <?php endif; ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php else: ?>
                <div class="est-no-cuenta">
                    <i class="ri-book-open-line"></i>
                    <h5>Sin inscripciones</h5>
                    <p>Aún no tienes programas inscritos. Contacta con administración para gestionar tu inscripción.</p>
                </div>
            <?php endif; ?>
        </div>

        
        <?php
            $totalPagado = 0;
            $totalPendiente = 0;
            $totalVencido = 0;
            foreach ($inscripciones as $ins) {
                foreach ($ins->cuotas as $cuota) {
                    $pagadoEnCuota = $cuota->pagosCuota->sum('monto_bs');
                    if ($pagadoEnCuota > 0) {
                        $totalPagado += $pagadoEnCuota;
                    }
                    $pendiente = $cuota->monto_bs - $pagadoEnCuota;
                    if ($pendiente > 0) {
                        if ($cuota->estado == 'Vencido') {
                            $totalVencido += $pendiente;
                        } else {
                            $totalPendiente += $pendiente;
                        }
                    }
                }
            }
        ?>
        <div class="est-tabs-body" id="tab-contable">

            <?php
                $totalOferta = $totalPagado + $totalPendiente + $totalVencido;
                $pctPagadoGlobal = $totalOferta > 0 ? round(($totalPagado / $totalOferta) * 100) : 0;
            ?>

            
            <div class="contable-balance-card">
                <div class="contable-balance-header">
                    <i class="ri-bar-chart-grouped-line"></i>
                    <p class="contable-balance-title">Resumen Financiero Global</p>
                </div>
                <div class="contable-stats-grid">
                    <div class="contable-stat-item">
                        <div class="contable-stat-icon pagado"><i class="ri-checkbox-circle-line"></i></div>
                        <div>
                            <div class="contable-stat-value pagado">Bs. <?php echo e(number_format($totalPagado, 2)); ?></div>
                            <div class="contable-stat-label">Total Pagado</div>
                        </div>
                    </div>
                    <div class="contable-stat-item">
                        <div class="contable-stat-icon pendiente"><i class="ri-time-line"></i></div>
                        <div>
                            <div class="contable-stat-value pendiente">Bs. <?php echo e(number_format($totalPendiente, 2)); ?></div>
                            <div class="contable-stat-label">Pendiente</div>
                        </div>
                    </div>
                    <div class="contable-stat-item">
                        <div class="contable-stat-icon vencido"><i class="ri-alert-line"></i></div>
                        <div>
                            <div class="contable-stat-value vencido">Bs. <?php echo e(number_format($totalVencido, 2)); ?></div>
                            <div class="contable-stat-label">Vencido</div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($inscripciones->count() > 0): ?>
                <div class="contable-tabs-wrapper">
                    
                    <div class="contable-tabs-header">
                        <div class="contable-prog-pills">
                            <?php $__currentLoopData = $inscripciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ins): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $insNombre = $ins->ofertaAcademica?->programa?->nombre
                                        ?? ($ins->ofertaAcademica?->posgrado?->nombre
                                        ?? 'Oferta #' . ($key + 1));
                                ?>
                                <button type="button"
                                    class="contable-prog-pill est-oferta-tab-btn <?php echo e($key == 0 ? 'active' : ''); ?>"
                                    data-target="contable-oferta-<?php echo e($key); ?>">
                                    <span class="pill-badge"><?php echo e($key + 1); ?></span>
                                    <?php echo e($insNombre); ?>

                                    <span class="pill-arrow"><i class="ri-arrow-right-s-line"></i></span>
                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="contable-tabs-body">
                        <?php $__currentLoopData = $inscripciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ins): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $insPagado = 0;
                                $insPendiente = 0;
                                $insVencido = 0;
                                $insTotal = 0;
                                $totalCuotas = $ins->cuotas->count();
                                $cuotasPagadas = 0;
                                foreach ($ins->cuotas as $cuota) {
                                    $pagadoEnCuota = $cuota->pagosCuota->sum('monto_bs');
                                    $insTotal += $cuota->monto_bs;
                                    if ($pagadoEnCuota > 0) {
                                        $insPagado += $pagadoEnCuota;
                                        if ($pagadoEnCuota >= $cuota->monto_bs) {
                                            $cuotasPagadas++;
                                        }
                                    }
                                    $pendiente = $cuota->monto_bs - $pagadoEnCuota;
                                    if ($pendiente > 0) {
                                        if ($cuota->estado == 'Vencido') {
                                            $insVencido += $pendiente;
                                        } else {
                                            $insPendiente += $pendiente;
                                        }
                                    }
                                }
                                $pctPagado = $insTotal > 0 ? round(($insPagado / $insTotal) * 100) : 0;
                                $pctClass = $pctPagado >= 90 ? '' : ($pctPagado >= 50 ? 'some' : 'low');
                            ?>
                            <div class="est-oferta-content <?php echo e($key == 0 ? 'active' : ''); ?>"
                                id="contable-oferta-<?php echo e($key); ?>">

                                
                                <div class="est-data-card-header" style="padding:14px 18px;display:flex;align-items:center;gap:10px;border-bottom:1px solid var(--est-border);background:linear-gradient(180deg,#f8fafc 0%,#f1f5f9 100%);">
                                    <div style="width:34px;height:34px;border-radius:10px;background:var(--est-primary-light);color:var(--est-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div style="font-family:'Outfit',sans-serif;font-size:.9rem;font-weight:600;color:#1e293b;">
                                            <?php echo e($ins->ofertaAcademica?->programa?->nombre ?? ($ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta #' . $ins->ofertas_academica_id)); ?>

                                        </div>
                                        <div style="font-size:.73rem;color:#94a3b8;">
                                            Plan: <?php echo e($ins->planesPago?->nombre ?? '—'); ?>

                                            &middot; <?php echo e($totalCuotas); ?> cuota(s)
                                        </div>
                                    </div>
                                    <span class="estado-badge-est <?php echo e($pctPagado >= 100 ? 'pagado' : ($insVencido > 0 ? 'vencido' : 'pendiente')); ?>">
                                        <?php echo e($pctPagado >= 100 ? 'Cancelado' : ($insVencido > 0 ? 'Con vencidos' : 'Al día')); ?>

                                    </span>
                                </div>

                                
                                <div class="contable-pay-progress">
                                    <i class="ri-percent-line" style="color:#94a3b8;font-size:.9rem;"></i>
                                    <div class="contable-pay-track">
                                        <div class="contable-pay-track-fill <?php echo e($pctClass); ?>"
                                            style="width:<?php echo e($pctPagado); ?>%;"></div>
                                    </div>
                                    <span class="contable-pay-pct"><?php echo e($pctPagado); ?>%</span>
                                </div>

                                
                                <div class="contable-mini-stats">
                                    <div class="contable-mini-stat">
                                        <div class="contable-mini-icon green"><i class="ri-checkbox-circle-line"></i></div>
                                        <div>
                                            <div class="contable-mini-val green">Bs. <?php echo e(number_format($insPagado, 2)); ?></div>
                                            <div class="contable-mini-lbl">Pagado</div>
                                        </div>
                                    </div>
                                    <div class="contable-mini-stat">
                                        <div class="contable-mini-icon amber"><i class="ri-time-line"></i></div>
                                        <div>
                                            <div class="contable-mini-val amber">Bs. <?php echo e(number_format($insPendiente, 2)); ?></div>
                                            <div class="contable-mini-lbl">Pendiente</div>
                                        </div>
                                    </div>
                                    <div class="contable-mini-stat">
                                        <div class="contable-mini-icon red"><i class="ri-alert-line"></i></div>
                                        <div>
                                            <div class="contable-mini-val red">Bs. <?php echo e(number_format($insVencido, 2)); ?></div>
                                            <div class="contable-mini-lbl">Vencido</div>
                                        </div>
                                    </div>
                                </div>

                                
                                <?php if($ins->cuotas && $ins->cuotas->count() > 0): ?>
                                    <div style="overflow-x:auto;">
                                        <table class="contable-cuotas-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Cuota</th>
                                                    <th>Monto</th>
                                                    <th>Vencimiento</th>
                                                    <th>Avance</th>
                                                    <th>Estado</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $ins->cuotas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $totalPagadoCuota = $cuota->pagosCuota->sum('monto_bs');
                                                        $pctCuota = $cuota->monto_bs > 0 ? round(($totalPagadoCuota / $cuota->monto_bs) * 100) : 0;
                                                        $pctCuotaClass = $pctCuota >= 100 ? 'full' : ($pctCuota > 0 ? 'part' : 'empty');
                                                        $montoNeto = $cuota->monto_bs - ($cuota->descuento_bs ?? 0);
                                                        $pagosData = [];
                                                        foreach ($cuota->pagosCuota as $pc) {
                                                            if ($pc->pago) {
                                                                $pago = $pc->pago;
                                                                $trabajadorNombre = $pago->trabajadorCargo?->trabajador?->persona
                                                                    ? $pago->trabajadorCargo->trabajador->persona->nombres . ' ' . $pago->trabajadorCargo->trabajador->persona->apellido_paterno
                                                                    : '—';
                                                                $comprobante = null;
                                                                $cuotaIds = $pago->pagosCuotas->pluck('cuota_id')->toArray();
                                                                if (!empty($cuotaIds)) {
                                                                    $respaldos = \DB::table('pago_respaldo_cuota')
                                                                        ->whereIn('pago_respaldo_cuota.cuota_id', $cuotaIds)
                                                                        ->join('pagos_respaldos', 'pago_respaldo_cuota.pago_respaldo_id', '=', 'pagos_respaldos.id')
                                                                        ->where('pagos_respaldos.estado', 'verificado')
                                                                        ->select('pagos_respaldos.archivo')
                                                                        ->first();
                                                                    if ($respaldos) {
                                                                        $comprobante = [
                                                                            'archivo' => $respaldos->archivo,
                                                                            'url' => asset('storage/comprobantes/' . $respaldos->archivo),
                                                                        ];
                                                                    }
                                                                }
                                                                $pagosData[] = [
                                                                    'id' => $pago->id,
                                                                    'recibo' => $pago->recibo,
                                                                    'fecha' => $pago->fecha_pago,
                                                                    'monto' => $pago->monto_total,
                                                                    'descuento' => $pago->descuento_bs,
                                                                    'metodo' => $pago->tipo_pago,
                                                                    'trabajador' => $trabajadorNombre,
                                                                    'estudiante' => trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '')),
                                                                    'programa' => $ins->ofertaAcademica?->posgrado?->nombre ?? ($ins->ofertaAcademica?->programa?->nombre ?? ''),
                                                                    'plan' => $ins->planesPago?->nombre ?? '',
                                                                    'comprobante' => $comprobante,
                                                                    'documento_factura' => $pago->documento_factura ? \Storage::url($pago->documento_factura) : null,
                                                                    'detalles' => ($pago->detalles ?? collect())->map(fn($d) => ['tipo' => $d->tipo_pago, 'monto' => $d->monto_bs])->toArray(),
                                                                    'cuotas' => ($pago->pagosCuotas ?? collect())->map(fn($pc2) => [
                                                                        'nombre' => $pc2->cuota?->nombre ?? 'Cuota #' . $pc2->cuota_id,
                                                                        'n_cuota' => $pc2->cuota?->n_cuota ?? null,
                                                                        'monto' => $pc2->monto_bs,
                                                                    ])->toArray(),
                                                                ];
                                                            }
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td><span class="mono"><?php echo e($cuota->n_cuota); ?></span></td>
                                                        <td><span class="cuota-name"><?php echo e($cuota->nombre); ?></span></td>
                                                        <td>
                                                            <span class="mono">Bs. <?php echo e(number_format($montoNeto, 2)); ?></span>
                                                            <?php if(($cuota->descuento_bs ?? 0) > 0): ?>
                                                                <br><span class="text-muted-sm">-<?php echo e(number_format($cuota->descuento_bs, 2)); ?> desc.</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo e($cuota->fecha_vencimiento ? \Carbon\Carbon::parse($cuota->fecha_vencimiento)->format('d/m/Y') : '—'); ?>

                                                        </td>
                                                        <td>
                                                            <div class="cuota-pay-micro">
                                                                <div class="track">
                                                                    <div class="fill <?php echo e($pctCuotaClass); ?>" style="width:<?php echo e($pctCuota); ?>%;"></div>
                                                                </div>
                                                                <span class="pct"><?php echo e($pctCuota); ?>%</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="estado-badge-est <?php echo e($cuota->estado == 'Pagado' ? 'pagado' : ($cuota->estado == 'Vencido' ? 'vencido' : 'pendiente')); ?>">
                                                                <?php echo e($cuota->estado); ?>

                                                            </span>
                                                        </td>
                                                        <td>
                                                            <?php if(count($pagosData) > 0): ?>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-primary btn-ver-detalle-pago"
                                                                    data-pagos='<?php echo e(json_encode($pagosData)); ?>'
                                                                    style="border-radius:8px;font-size:.72rem;padding:3px 10px;"
                                                                    title="Ver detalle de pagos">
                                                                    <i class="ri-eye-line"></i>
                                                                </button>
                                                            <?php else: ?>
                                                                <span style="font-size:.7rem;color:#cbd5e1;">—</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div style="padding:24px;text-align:center;color:#94a3b8;">
                                        <i class="ri-money-dollar-line" style="font-size:1.5rem;opacity:.5;display:block;margin-bottom:6px;"></i>
                                        <span style="font-size:.85rem;">Sin cuotas registradas</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="est-empty-state">
                    <i class="ri-money-dollar-line"></i>
                    <h5>Sin información contable</h5>
                    <p>No hay ofertas académicas registradas</p>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="est-tabs-body" id="tab-pagos">

            <?php
                $totalComprobantes = 0;
                $comprobantesVerificados = 0;
                $comprobantesPendientes = 0;
                $comprobantesRechazados = 0;
                foreach ($inscripciones as $ins) {
                    foreach ($ins->pagosRespaldos as $r) {
                        $totalComprobantes++;
                        if ($r->estado === 'verificado') $comprobantesVerificados++;
                        elseif ($r->estado === 'rechazado') $comprobantesRechazados++;
                        else $comprobantesPendientes++;
                    }
                }
            ?>

            
            <div class="contable-balance-card" style="margin-bottom:20px;">
                <div class="contable-balance-header">
                    <i class="ri-file-list-3-line"></i>
                    <p class="contable-balance-title">Resumen de Comprobantes</p>
                </div>
                <div class="contable-stats-grid">
                    <div class="contable-stat-item">
                        <div class="contable-stat-icon pagado"><i class="ri-checkbox-circle-line"></i></div>
                        <div>
                            <div class="contable-stat-value pagado"><?php echo e($comprobantesVerificados); ?></div>
                            <div class="contable-stat-label">Verificados</div>
                        </div>
                    </div>
                    <div class="contable-stat-item">
                        <div class="contable-stat-icon pendiente"><i class="ri-time-line"></i></div>
                        <div>
                            <div class="contable-stat-value pendiente"><?php echo e($comprobantesPendientes); ?></div>
                            <div class="contable-stat-label">En revisión</div>
                        </div>
                    </div>
                    <div class="contable-stat-item">
                        <div class="contable-stat-icon vencido"><i class="ri-close-circle-line"></i></div>
                        <div>
                            <div class="contable-stat-value vencido"><?php echo e($comprobantesRechazados); ?></div>
                            <div class="contable-stat-label">Rechazados</div>
                        </div>
                    </div>
                </div>
            </div>

            
            <?php if($bancos->isNotEmpty()): ?>
            <div class="contable-balance-card" style="margin-bottom:20px;">
                <div class="contable-balance-header">
                    <i class="ri-bank-line"></i>
                    <p class="contable-balance-title">Cuentas Bancarias para Pagos</p>
                </div>
                <div class="pagos-bancos-grid">
                    <?php $__currentLoopData = $bancos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($banco->cuentas->isNotEmpty()): ?>
                        <div class="pagos-banco-card">
                            <div class="pagos-banco-head">
                                <div class="pagos-banco-icon">
                                    <i class="ri-bank-line"></i>
                                </div>
                                <div>
                                    <div class="pagos-banco-name"><?php echo e($banco->nombre); ?></div>
                                    <?php if($banco->sigla): ?>
                                    <div class="pagos-banco-sigla"><?php echo e($banco->sigla); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="pagos-banco-body">
                                <?php $__currentLoopData = $banco->cuentas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuenta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="pagos-banco-cuenta">
                                    <div class="pagos-banco-cuenta-main">
                                        <div class="pagos-banco-cuenta-num">
                                            <i class="ri-exchange-dollar-line"></i>
                                            <?php echo e($cuenta->numero_cuenta); ?>

                                        </div>
                                        <div class="pagos-banco-cuenta-meta">
                                            <span class="pagos-banco-badge <?php echo e($cuenta->tipo_cuenta === 'Cuenta Corriente' ? 'cc' : 'ca'); ?>">
                                                <?php echo e($cuenta->tipo_cuenta === 'Cuenta Corriente' ? 'Cta. Corriente' : 'Cta. Ahorro'); ?>

                                            </span>
                                            <?php if($cuenta->titular): ?>
                                            <span class="pagos-banco-titular">
                                                <i class="ri-user-line"></i> <?php echo e($cuenta->titular); ?>

                                            </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if($cuenta->imagen_qr): ?>
                                    <div class="pagos-banco-qr" onclick="abrirQrModal(this.querySelector('img').src)">
                                        <img src="<?php echo e(asset('storage/' . $cuenta->imagen_qr)); ?>" alt="QR" loading="lazy">
                                        <span><i class="ri-qr-code-line"></i> Ver QR</span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if($inscripciones->count() > 0): ?>

                
                <div class="pagos-tabs-wrapper">
                    <div class="pagos-tabs-header">
                        <div class="contable-prog-pills">
                            <?php $__currentLoopData = $inscripciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ins): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $insNombre = $ins->ofertaAcademica?->programa?->nombre
                                        ?? ($ins->ofertaAcademica?->posgrado?->nombre
                                        ?? 'Oferta #' . ($key + 1));
                                ?>
                                <button type="button"
                                    class="contable-prog-pill pagos-tab-btn <?php echo e($key == 0 ? 'active' : ''); ?>"
                                    data-target="pagos-oferta-<?php echo e($key); ?>">
                                    <span class="pill-badge"><?php echo e($key + 1); ?></span>
                                    <i class="ri-book-2-line" style="font-size:.85rem;"></i>
                                    <?php echo e($insNombre); ?>

                                    <span class="pill-arrow"><i class="ri-arrow-right-s-line"></i></span>
                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <?php $__currentLoopData = $inscripciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ins): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $cuotasPendIns = $ins->cuotas->filter(fn($c) => (float)($c->pago_pendiente_bs ?? $c->monto_bs) > 0);
                            $tienePendientes = $cuotasPendIns->isNotEmpty();
                        ?>
                        <div class="pagos-oferta-content <?php echo e($key == 0 ? 'active' : ''); ?>" id="pagos-oferta-<?php echo e($key); ?>">

                            
                            <div class="pagos-grid-2">

                                
                                <div class="pagos-card">
                                    <div class="pagos-card-header">
                                        <div class="pagos-card-header-left">
                                            <div class="pagos-card-icon orange"><i class="ri-installment-line"></i></div>
                                            <div>
                                                <div class="pagos-card-title">Estado de Cuotas</div>
                                                <div class="pagos-card-sub"><?php echo e($ins->planesPago?->nombre ?? 'Sin plan'); ?> &middot; <?php echo e($ins->cuotas->count()); ?> cuota(s)</div>
                                            </div>
                                        </div>
                                        <?php if($tienePendientes): ?>
                                            <?php
                                                $progNombre = addslashes($ins->ofertaAcademica?->programa?->nombre ?? ($ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta'));
                                                $planNombre = addslashes($ins->planesPago?->nombre ?? '');
                                            ?>
                                            <button type="button" class="pagos-btn-subir"
                                                onclick="estAbrirModal('<?php echo e($ins->id); ?>', '<?php echo e($progNombre); ?>', '<?php echo e($planNombre); ?>')">
                                                <i class="ri-upload-cloud-line"></i> Subir
                                            </button>
                                        <?php else: ?>
                                            <span class="pagos-btn-al-dia">
                                                <i class="ri-checkbox-circle-fill"></i> Al día
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="pagos-card-body">
                                        <?php if($ins->cuotas && $ins->cuotas->count() > 0): ?>
                                            <table class="pagos-mini-table">
                                                <thead>
                                                    <tr>
                                                        <th style="width:28px;">#</th>
                                                        <th>Cuota</th>
                                                        <th style="width:80px;">Monto</th>
                                                        <th style="width:85px;">Vence</th>
                                                        <th style="width:80px;">Avance</th>
                                                        <th style="width:75px;">Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $ins->cuotas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $estadoClass = $cuota->estado == 'Pagado' ? 'pagado' : ($cuota->estado == 'Vencido' ? 'vencido' : 'pendiente');
                                                            $totalPagadoCuota = $cuota->pagosCuota->sum('monto_bs');
                                                            $pctCuota = $cuota->monto_bs > 0 ? round(($totalPagadoCuota / $cuota->monto_bs) * 100) : 0;
                                                            $pctCuotaClass = $pctCuota >= 100 ? 'full' : ($pctCuota > 0 ? 'part' : 'empty');
                                                        ?>
                                                        <tr>
                                                            <td data-label="#"><span class="num-cuota"><?php echo e($cuota->n_cuota); ?></span></td>
                                                            <td data-label="Cuota" style="font-weight:600;color:#1e293b;font-size:.76rem;"><?php echo e($cuota->nombre); ?></td>
                                                            <td data-label="Monto" style="font-weight:600;color:#1e293b;font-size:.76rem;">Bs. <?php echo e(number_format($cuota->monto_bs, 2)); ?></td>
                                                            <td data-label="Vence" class="fecha-cell" style="font-size:.72rem;"><?php echo e($cuota->fecha_vencimiento ? \Carbon\Carbon::parse($cuota->fecha_vencimiento)->format('d/m/Y') : '—'); ?></td>
                                                            <td data-label="Avance" style="padding:8px 6px;">
                                                                <div class="cuota-pay-micro" style="gap:4px;">
                                                                    <div class="track" style="min-width:40px;">
                                                                        <div class="fill <?php echo e($pctCuotaClass); ?>" style="width:<?php echo e($pctCuota); ?>%;"></div>
                                                                    </div>
                                                                    <span class="pct" style="font-size:.65rem;min-width:28px;"><?php echo e($pctCuota); ?>%</span>
                                                                </div>
                                                            </td>
                                                            <td data-label="Estado" style="padding:8px 6px;"><span class="pagos-cuota-badge <?php echo e($estadoClass); ?>" style="font-size:.65rem;padding:2px 8px;"><?php echo e($cuota->estado); ?></span></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <div class="pagos-card-empty">
                                                <i class="ri-inbox-line"></i>
                                                <p>Sin cuotas registradas</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                
                                <div class="pagos-card">
                                    <div class="pagos-card-header">
                                        <div class="pagos-card-header-left">
                                            <div class="pagos-card-icon indigo"><i class="ri-file-list-3-line"></i></div>
                                            <div>
                                                <div class="pagos-card-title">Comprobantes Enviados</div>
                                                <div class="pagos-card-sub"><?php echo e($ins->pagosRespaldos->count()); ?> total(es)</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pagos-card-body">
                                        <?php if($ins->pagosRespaldos->count() > 0): ?>
                                            <?php $__currentLoopData = $ins->pagosRespaldos->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $stClass = $resp->estado === 'verificado' ? 'verificado' : ($resp->estado === 'rechazado' ? 'rechazado' : 'revision');
                                                    $stLabel = $resp->estado === 'verificado' ? 'Verificado' : ($resp->estado === 'rechazado' ? 'Rechazado' : 'En revisión');
                                                    $archivoUrl = asset('storage/comprobantes/' . $resp->archivo);
                                                    $esImagen = preg_match('/\.(jpg|jpeg|png)$/i', $resp->archivo);
                                                ?>
                                                <div class="pagos-comp-row">
                                                    <div class="pagos-comp-icon <?php echo e($esImagen ? 'img' : 'pdf'); ?>">
                                                        <i class="<?php echo e($esImagen ? 'ri-image-fill' : 'ri-file-pdf-fill'); ?>"></i>
                                                    </div>
                                                    <div class="pagos-comp-body">
                                                        <div class="top">
                                                            <span class="fecha"><i class="ri-calendar-line"></i> <?php echo e($resp->created_at->format('d/m/Y')); ?> <span style="color:#cbd5e1;"><?php echo e($resp->created_at->format('H:i')); ?></span></span>
                                                            <div class="cuota-tags">
                                                                <?php $__empty_1 = true; $__currentLoopData = $resp->cuotas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                                    <span class="cuota-tag"><?php echo e($cq->nombre); ?></span>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                                    <span style="font-size:.65rem;color:#94a3b8;">—</span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <?php if($resp->observaciones): ?>
                                                            <div class="obs"><?php echo e($resp->observaciones); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="pagos-comp-actions">
                                                        <span class="pagos-comp-badge <?php echo e($stClass); ?>"><?php echo e($stLabel); ?></span>
                                                        <a href="<?php echo e($archivoUrl); ?>" target="_blank" class="pagos-comp-link" title="Ver archivo">
                                                            <i class="<?php echo e($esImagen ? 'ri-eye-line' : 'ri-file-pdf-line'); ?>"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <div class="pagos-card-empty">
                                                <i class="ri-file-upload-line"></i>
                                                <p>No has enviado comprobantes aún</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

            <?php else: ?>
                <div class="est-empty-state">
                    <i class="ri-file-list-3-line"></i>
                    <h5>Sin inscripciones</h5>
                    <p>No hay inscripciones para mostrar</p>
                </div>
            <?php endif; ?>
        </div>

    
    <div id="qrOverlay" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,.75);align-items:center;justify-content:center;backdrop-filter:blur(4px);" onclick="cerrarQrOverlay(event)">
        <div style="background:#fff;border-radius:18px;padding:2rem;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,.35);max-width:360px;margin:1rem;position:relative;" onclick="event.stopPropagation()">
            <button onclick="cerrarQrOverlay()" style="position:absolute;top:10px;right:14px;background:none;border:none;font-size:1.3rem;color:#94a3b8;cursor:pointer;padding:4px;line-height:1;"><i class="ri-close-line"></i></button>
            <img id="qrLightboxImg" src="" alt="QR" style="max-width:260px;border-radius:10px;">
            <p style="margin:.85rem 0 0;font-size:.82rem;color:#64748b;font-weight:500;">Código QR — Escanea para realizar el pago</p>
        </div>
    </div>

    
    <div class="modal fade" id="modalEstComprobante" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:580px;">
            <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 25px 60px rgba(0,0,0,.25);">
                
                <div style="background:linear-gradient(135deg,#1e293b 0%,#2d3748 100%);padding:18px 24px;display:flex;align-items:center;justify-content:space-between;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:10px;background:rgba(252,123,4,.15);color:#fc7b04;display:flex;align-items:center;justify-content:center;font-size:1.05rem;flex-shrink:0;">
                            <i class="ri-file-upload-line"></i>
                        </div>
                        <div>
                            <div style="font-family:'Outfit',sans-serif;font-size:.9rem;font-weight:700;color:#fff;line-height:1.2;">Subir Comprobante de Pago</div>
                            <div style="font-size:.7rem;color:#94a3b8;">Adjunta el respaldo de tu pago realizado</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="opacity:.7;filter:brightness(2);"></button>
                </div>

                <div style="padding:22px 24px;background:#fff;">
                    
                    <div style="display:flex;align-items:center;gap:12px;background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%);border-radius:12px;padding:14px 16px;margin-bottom:20px;border-left:4px solid #fc7b04;">
                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(252,123,4,.1);color:#fc7b04;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">
                            <i class="ri-book-2-line"></i>
                        </div>
                        <div>
                            <div style="font-family:'Outfit',sans-serif;font-weight:700;font-size:.88rem;color:#1e293b;" id="estCompPrograma"></div>
                            <div style="font-size:.75rem;color:#64748b;margin-top:2px;" id="estCompPlan"></div>
                        </div>
                    </div>

                    
                    <div style="margin-bottom:18px;">
                        <label style="font-size:.78rem;font-weight:700;color:#475569;margin-bottom:7px;display:block;">
                            Archivo del comprobante <span style="color:#dc2626;">*</span>
                        </label>
                        <div style="border:2px dashed #e2e8f0;border-radius:14px;padding:28px;text-align:center;background:#fafbfc;cursor:pointer;transition:all .25s;position:relative;"
                             id="comprobanteFileArea"
                             onclick="document.getElementById('estCompArchivo').click()"
                             onmouseover="this.style.borderColor='#fc7b04';this.style.background='rgba(252,123,4,.03)'"
                             onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#fafbfc'">
                            <i class="ri-upload-cloud-line" style="font-size:2.2rem;color:#cbd5e1;display:block;margin-bottom:10px;"></i>
                            <span style="font-size:.82rem;color:#64748b;display:block;">Haz clic para seleccionar el archivo</span>
                            <small style="font-size:.7rem;color:#94a3b8;margin-top:6px;display:block;">JPG, PNG o PDF — máx. 5 MB</small>
                        </div>
                        <input type="file" id="estCompArchivo" accept=".jpg,.jpeg,.png,.pdf" style="display:none;">
                    </div>

                    
                    <div style="margin-bottom:18px;">
                        <label style="font-size:.78rem;font-weight:700;color:#475569;margin-bottom:7px;display:block;">Observaciones</label>
                        <textarea id="estCompObservaciones" rows="2"
                            style="width:100%;border:2px solid #e2e8f0;border-radius:12px;padding:12px 14px;font-size:.82rem;background:#f8fafc;transition:all .2s;resize:vertical;font-family:inherit;"
                            placeholder="Opcional: Agrega alguna observación sobre tu pago..."
                            onfocus="this.style.borderColor='#fc7b04';this.style.boxShadow='0 0 0 3px rgba(252,123,4,.08)';this.style.background='#fff'"
                            onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';this.style.background='#f8fafc'"></textarea>
                    </div>

                    
                    <div>
                        <label style="font-size:.78rem;font-weight:700;color:#475569;margin-bottom:7px;display:block;">
                            Cuotas que cubre este comprobante <span style="color:#dc2626;">*</span>
                        </label>
                        <div id="estCompCuotasLoading" style="text-align:center;padding:16px 0;">
                            <div class="spinner-border spinner-border-sm" style="color:#fc7b04;"></div>
                            <span style="margin-left:8px;font-size:.78rem;color:#94a3b8;">Cargando cuotas...</span>
                        </div>
                        <div id="estCompCuotasContainer" style="display:grid;gap:8px;display:none;"></div>
                    </div>
                </div>

                
                <div style="border-top:1px solid #e2e8f0;padding:14px 24px;background:#f8fafc;display:flex;justify-content:flex-end;gap:10px;">
                    <button type="button" data-bs-dismiss="modal"
                        style="padding:11px 20px;border-radius:10px;border:2px solid #e2e8f0;background:#fff;color:#475569;font-weight:600;font-size:.82rem;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:6px;"
                        onmouseover="this.style.borderColor='#cbd5e1';this.style.background='#f1f5f9'"
                        onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#fff'">
                        <i class="ri-close-line"></i> Cancelar
                    </button>
                    <button type="button" id="btnEstEnviarComprobante"
                        style="padding:11px 22px;border-radius:10px;border:none;background:linear-gradient(135deg,#fc7b04 0%,#e67300 100%);color:#fff;font-weight:600;font-size:.82rem;cursor:pointer;transition:all .25s;box-shadow:0 3px 12px rgba(252,123,4,.25);display:flex;align-items:center;gap:6px;"
                        onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 5px 18px rgba(252,123,4,.35)'"
                        onmouseout="this.style.transform='none';this.style.boxShadow='0 3px 12px rgba(252,123,4,.25)'">
                        <i class="ri-send-plane-line"></i> Enviar Comprobante
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <div id="est-toast-container" style="position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:.5rem;"></div>

    
    <div class="modal fade modal-detalle-pago" id="modalVerDetallePago" tabindex="-1" aria-labelledby="modalVerDetallePagoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background:linear-gradient(135deg,#fc7b04,#c96004);color:white;padding:1.25rem 1.5rem;border:none;">
                    <h5 class="modal-title" style="font-weight:600;font-size:1.1rem;color:white;">
                        <i class="ri-file-receipt-line"></i> Detalle del Pago
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:1.5rem;background:#f8fafc;">
                    <div id="lista-pagos" class="pago-list-container"></div>
                    <div id="detalle-pago-container" style="display:none;">
                        <div class="detalle-header">
                            <div class="detalle-header-top">
                                <div class="detalle-logo">
                                    <img src="<?php echo e(asset('build/images/logo_nuevo.png')); ?>" alt="Logo" style="height: 45px;">
                                    <div>
                                        <div class="detalle-logo-text">INNOVA CIENCIA VIRTUAL</div>
                                        <div class="detalle-logo-sub">Educación Superior Virtual</div>
                                    </div>
                                </div>
                                <div class="detalle-recibo-badge">
                                    <i class="ri-file-list-3-line"></i>
                                    <span id="detalle-recibo">—</span>
                                </div>
                            </div>
                            <div class="detalle-meta">
                                <span><strong>Fecha:</strong> <span id="detalle-fecha">—</span></span>
                                <span><strong>Forma de Pago:</strong> <span id="detalle-metodo">—</span></span>
                            </div>
                        </div>
                        <div class="detalle-info-section">
                            <h6><i class="ri-user-line"></i> Información del Estudiante</h6>
                            <div class="detalle-info-row">
                                <div class="detalle-info-item">
                                    <div class="detalle-info-label">Estudiante</div>
                                    <div class="detalle-info-value" id="detalle-estudiante">—</div>
                                </div>
                                <div class="detalle-info-item">
                                    <div class="detalle-info-label">Programa</div>
                                    <div class="detalle-info-value" id="detalle-programa">—</div>
                                </div>
                            </div>
                        </div>
                        <div class="detalle-info-section">
                            <h6><i class="ri-money-dollar-line"></i> Detalle del Pago</h6>
                            <div class="detalle-info-item" style="margin-bottom: 12px;">
                                <div class="detalle-info-label">Plan de Pago</div>
                                <div class="detalle-info-value" id="detalle-plan">—</div>
                            </div>
                            <div class="detalle-tabla">
                                <table class="table table-bordered table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>Concepto</th>
                                            <th class="text-end">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detalle-tabla"></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" class="fw-bold">Total (Bs.)</td>
                                            <td class="text-end fw-bold" id="detalle-total">—</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div id="detalle-descuento-container" style="display:none; margin-top: 12px;">
                            <div class="detalle-info-item" style="background: linear-gradient(135deg, #fef3c7 0%, #fef9c3 100%);">
                                <div class="detalle-info-label">Descuento Aplicado</div>
                                <div class="detalle-info-value" style="color: #d97706;" id="detalle-descuento">—</div>
                            </div>
                        </div>
                        <div id="detalle-factura-container" style="display:none; margin-top: 12px;">
                            <div class="detalle-info-item" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);">
                                <div style="display:flex;align-items:center;justify-content:space-between;">
                                    <div>
                                        <div class="detalle-info-label"><i class="ri-file-list-3-line"></i> Factura</div>
                                        <div class="detalle-info-value" id="detalle-factura-estado" style="color:#059669;">Con factura</div>
                                    </div>
                                    <button type="button" id="btn-ver-factura-detalle"
                                        style="background:#059669;color:white;border:none;border-radius:8px;padding:0.35rem 0.85rem;font-size:.8rem;cursor:pointer;display:flex;align-items:center;gap:5px;">
                                        <i class="ri-eye-line"></i> Ver factura
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="detalle-footer">
                            <div class="detalle-footer-box">
                                <div class="label"><i class="ri-user-star-line"></i> Emisor</div>
                                <div class="value" id="detalle-trabajador">—</div>
                            </div>
                            <div class="detalle-footer-box">
                                <div class="label"><i class="ri-account-circle-line"></i> Depositante</div>
                                <div class="value" id="detalle-depositante">—</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;background:#f8fafc;">
                    <a href="#" id="btn-descargar-pdf" class="btn-descargar" target="_blank">
                        <i class="ri-file-pdf-line"></i> Descargar PDF
                    </a>
                    <button type="button" class="btn-cerrar" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>


<div class="est-tabs-body" id="tab-cronograma">

    <?php if($ofertasCronograma->isEmpty()): ?>
        <div class="est-empty-state">
            <i class="ri-calendar-close-line"></i>
            <h5>No tienes ofertas inscritas</h5>
            <p>No tienes ofertas académicas con inscripción activa para mostrar cronograma.</p>
        </div>
    <?php else: ?>
        <div class="cronograma-container d-flex" style="min-height: 600px;">
            <div class="cronograma-sidebar">
                <div class="cronograma-sidebar-head">
                    <i class="ri-calendar-event-line"></i>
                    <span>Oferta Académica</span>
                </div>
                <div class="cronograma-sidebar-body">
                    <select class="cronograma-select" id="select-oferta-cronograma" onchange="cargarModulosCronograma()">
                        <option value="">Seleccionar oferta académica</option>
                        <?php $__currentLoopData = $ofertasCronograma; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oferta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($oferta['id']); ?>"><?php echo e($oferta['codigo']); ?> - <?php echo e($oferta['nombre']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button class="cronograma-btn-all" id="btnTodosModulosCronograma" onclick="verTodosModulosCronograma()">
                        <i class="ri-layout-grid-line"></i> Todos los módulos
                    </button>
                    <div id="modulosSidebarListCronograma">
                        <div class="cronograma-sidebar-empty">
                            <i class="ri-arrow-up-line"></i>
                            Selecciona una oferta académica
                        </div>
                    </div>
                </div>
            </div>
            <div class="cronograma-main">
                <div class="cronograma-title-section">
                    <div class="cronograma-title-left">
                        <div class="cronograma-title-icon">
                            <i class="ri-calendar-line"></i>
                        </div>
                        <div class="cronograma-title-text">
                            <h4>Calendario de Sesiones</h4>
                            <span>Visualiza todas tus clases programadas</span>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                        <div style="display:flex;align-items:center;gap:.4rem;font-size:.72rem;color:#64748b;">
                            <span class="cronograma-legend-dot confirmed"></span><span>Confirmado</span>
                            <span class="cronograma-legend-dot postponed"></span><span>Postergado</span>
                        </div>
                        <div id="moduloSeleccionadoBadgeCronograma" class="cronograma-filter-badge" style="display: none;">
                            <span class="dot"></span>
                            <span class="modulo-badge-name"></span>
                            <button type="button" title="Quitar filtro" onclick="verTodosModulosCronograma()">
                                <i class="ri-close-circle-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="cronograma-calendar-wrapper">
                    <div id="calendarCronograma"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>
        </div>
</div>


<div id="modal-act-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9000;overflow-y:auto;padding:2rem 1rem;">
    <div id="modal-act-box" style="background:#fff;border-radius:16px;max-width:780px;margin:0 auto;box-shadow:0 20px 60px rgba(0,0,0,.25);display:flex;flex-direction:column;max-height:90vh;">
        
        <div style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid #e9ecef;flex-shrink:0;">
            <div style="display:flex;align-items:center;gap:.75rem;">
                <span id="modal-act-icon" style="font-size:1.4rem;"></span>
                <div>
                    <div id="modal-act-title" style="font-size:1rem;font-weight:700;color:#2c3e50;line-height:1.2;"></div>
                    <div id="modal-act-subtitle" style="font-size:.78rem;color:#6c757d;"></div>
                </div>
            </div>
            <button onclick="cerrarModalAct()" style="background:none;border:none;font-size:1.5rem;color:#6c757d;cursor:pointer;line-height:1;padding:.25rem .5rem;">&times;</button>
        </div>
        
        <div id="modal-act-body" style="padding:1.5rem;overflow-y:auto;flex:1;">
            <div id="modal-act-loading" style="text-align:center;padding:2rem;color:#6c757d;">
                <div class="spinner-border spinner-border-sm" style="color:#fc7b04;"></div>
                <span style="margin-left:.5rem;font-size:.9rem;">Cargando…</span>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalDetalleSesionEst" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:780px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden;">

            <div class="modal-header py-3 px-4" style="background:linear-gradient(135deg,#1e293b 0%,#2d3748 100%);border:none;">
                <div class="d-flex align-items-center gap-3 flex-grow-1 min-width-0">
                    <div id="estDetColorBar" style="width:8px;height:36px;border-radius:8px;flex-shrink:0;"></div>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold text-white" style="font-size:.95rem;">
                            <i class="ri-calendar-event-line me-2"></i>Detalle de Sesión
                        </h5>
                        <div style="font-size:.72rem;color:rgba(255,255,255,.65);margin-top:.1rem;">Información de la sesión académica</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-0">
                <div class="row g-0">

                    
                    <div class="col-md-5 d-flex flex-column" style="padding:1.25rem 1.35rem;border-right:1px solid #e9ecef;background:#f8fafc;gap:14px;">

                        
                        <div class="d-flex align-items-start gap-3">
                            <div class="cronograma-modal-icon" style="background:rgba(252,123,4,.1);color:#fc7b04;">
                                <i class="ri-book-open-line"></i>
                            </div>
                            <div style="min-width:0;flex:1;">
                                <div class="cronograma-modal-label">Módulo Académico</div>
                                <div class="cronograma-modal-value" id="estDetModulo"></div>
                            </div>
                        </div>

                        
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                            <div class="d-flex align-items-start gap-2">
                                <div class="cronograma-modal-icon-sm" style="background:rgba(41,156,219,.1);color:#299cdb;">
                                    <i class="ri-calendar-line"></i>
                                </div>
                                <div>
                                    <div class="cronograma-modal-label">Fecha</div>
                                    <div class="cronograma-modal-value-sm" id="estDetFecha"></div>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <div class="cronograma-modal-icon-sm" style="background:rgba(34,197,94,.1);color:#22c55e;">
                                    <i class="ri-time-line"></i>
                                </div>
                                <div>
                                    <div class="cronograma-modal-label">Horario</div>
                                    <div class="cronograma-modal-value-sm" id="estDetHora"></div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="d-flex align-items-start gap-2">
                            <div class="cronograma-modal-icon-sm" style="background:rgba(99,102,241,.1);color:#6366f1;">
                                <i class="ri-user-star-line"></i>
                            </div>
                            <div style="min-width:0;flex:1;">
                                <div class="cronograma-modal-label">Docente Encargado</div>
                                <div class="cronograma-modal-value-sm" id="estDetDocente"></div>
                            </div>
                        </div>

                        
                        <div>
                            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="cronograma-modal-icon-sm" style="background:rgba(245,158,11,.1);color:#f59e0b;">
                                        <i class="ri-flag-line"></i>
                                    </div>
                                    <div>
                                        <div class="cronograma-modal-label">Estado</div>
                                        <div id="estDetEstado"></div>
                                    </div>
                                </div>
                                <div id="estDetReprogramadoInfo" style="display:none;flex:1;min-width:0;" class="alert alert-info py-2 px-3 border-0 rounded-3 mb-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-information-line" style="font-size:.85rem;"></i>
                                        <div style="font-size:.72rem;font-weight:500;" id="estDetReprogramadoMsg"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    
                    <div class="col-md-7 d-flex flex-column" style="padding:1.25rem 1.35rem;gap:12px;">

                        
                        <div id="estDetEnlaceWrap" style="display:none;border-radius:12px;border:1.5px solid rgba(99,102,241,.2);overflow:hidden;">
                            <div style="background:linear-gradient(135deg,rgba(99,102,241,.06) 0%,rgba(99,102,241,.02) 100%);padding:14px 16px;">
                                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                                    <div style="width:30px;height:30px;border-radius:8px;background:rgba(99,102,241,.1);color:#6366f1;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="ri-video-chat-line" style="font-size:.85rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#6366f1;">Sesión Virtual</div>
                                        <div style="font-size:.75rem;font-weight:600;color:#4338ca;margin-top:1px;" id="estDetEnlaceNombre"></div>
                                    </div>
                                </div>
                                <button id="estDetEnlaceBtn" type="button"
                                    style="width:100%;display:flex;align-items:center;justify-content:center;gap:6px;padding:9px 14px;border-radius:8px;border:none;background:linear-gradient(135deg,#6366f1 0%,#4f46e5 100%);color:#fff;font-weight:600;font-size:.78rem;cursor:pointer;transition:all .2s;"
                                    onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 3px 10px rgba(99,102,241,.3)'"
                                    onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                                    <i class="ri-external-link-line"></i> Unirse a la sesión virtual
                                </button>
                            </div>
                        </div>

                        
                        <div id="estDetGrabacionWrap" style="display:none;border-radius:12px;border:1.5px solid rgba(220,38,38,.15);overflow:hidden;">
                            <div style="background:linear-gradient(135deg,rgba(220,38,38,.04) 0%,rgba(220,38,38,.01) 100%);padding:14px 16px;">
                                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                                    <div style="width:30px;height:30px;border-radius:8px;background:rgba(220,38,38,.1);color:#dc2626;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="ri-vidicon-line" style="font-size:.85rem;"></i>
                                    </div>
                                    <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#b91c1c;">Grabación de la Sesión</div>
                                </div>
                                <button id="estDetGrabacionBtn" type="button"
                                    style="width:100%;display:flex;align-items:center;justify-content:center;gap:6px;padding:9px 14px;border-radius:8px;border:none;background:linear-gradient(135deg,#dc2626 0%,#b91c1c 100%);color:#fff;font-weight:600;font-size:.78rem;cursor:pointer;transition:all .2s;"
                                    onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 3px 10px rgba(220,38,38,.3)'"
                                    onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                                    <i class="ri-play-circle-line"></i> Ver grabación
                                </button>
                            </div>
                        </div>

                        
                        <div id="estDetSinEnlaces" class="d-flex flex-column align-items-center justify-content-center flex-grow-1 text-center" style="color:#94a3b8;padding:20px;">
                            <i class="ri-calendar-check-line" style="font-size:2.2rem;margin-bottom:10px;opacity:.35;"></i>
                            <div style="font-size:.82rem;font-weight:600;color:#64748b;">Sesión confirmada</div>
                            <div style="font-size:.72rem;margin-top:4px;line-height:1.5;">Los enlaces de sesión virtual o grabación<br>aparecerán aquí cuando estén disponibles.</div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:12px 20px;background:#f8fafc;display:flex;justify-content:flex-end;">
                <button type="button" data-bs-dismiss="modal"
                    style="padding:8px 18px;border-radius:8px;border:1.5px solid #e2e8f0;background:#fff;color:#475569;font-weight:600;font-size:.78rem;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:5px;"
                    onmouseover="this.style.borderColor='#cbd5e1';this.style.background='#f1f5f9'"
                    onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#fff'">
                    <i class="ri-close-line"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalVerFactura" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background:linear-gradient(135deg,#fc7b04,#c96004);color:white;padding:1.25rem 1.5rem;border:none;">
                <h5 class="modal-title" style="font-weight:600;font-size:1.1rem;color:white;">
                    <i class="ri-file-list-3-line me-2"></i>Factura — Recibo <span id="facturaReciboNum"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;background:#f8fafc;">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div style="background:white;padding:.75rem 1rem;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,0.04);">
                            <p style="color:#64748b;font-size:.75rem;margin-bottom:.2rem;">Estudiante</p>
                            <p id="facturaEstudiante" style="font-weight:600;color:#1e293b;margin:0;font-size:.9rem;"></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="background:white;padding:.75rem 1rem;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,0.04);">
                            <p style="color:#64748b;font-size:.75rem;margin-bottom:.2rem;">Oferta</p>
                            <p id="facturaOferta" style="font-weight:600;color:#1e293b;margin:0;font-size:.9rem;"></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="background:white;padding:.75rem 1rem;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,0.04);">
                            <p style="color:#64748b;font-size:.75rem;margin-bottom:.2rem;">Monto</p>
                            <p id="facturaMonto" style="font-weight:700;color:#059669;margin:0;font-size:.9rem;"></p>
                        </div>
                    </div>
                </div>
                <div style="background:white;padding:1rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.05);text-align:center;">
                    <div id="facturaFileContainer" style="max-height:500px;overflow:auto;"></div>
                </div>
            </div>
            <div class="modal-footer" style="background:#f8fafc;border:none;padding:1rem 1.5rem;">
                <a id="facturaDownloadLink" href="#" target="_blank" class="btn" style="background:linear-gradient(135deg,#fc7b04,#c96004);color:white;border:none;padding:0.6rem 1.25rem;border-radius:8px;font-weight:500;">
                    <i class="ri-download-line me-1"></i> Descargar
                </a>
                <button type="button" class="btn" style="background:#e2e8f0;color:#475569;border:none;padding:0.6rem 1.25rem;border-radius:8px;font-weight:500;" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/fullcalendar/index.global.min.js')); ?>"></script>
    <script>
        /* ── Variables globales ── */
        const loaded = {};
        let calendarioCronograma    = null;
        let calendarioDocente       = null;   // legacy — ya no se usa directamente
        let calendarioHorarioDocente = null;  // nuevo calendario docente (cronograma style)
        let datosCronograma = <?php echo json_encode($ofertasCronograma, 15, 512) ?>;
        <?php if($esDocente): ?>
        let datosHorariosDocente = <?php echo json_encode($horariosDocente); ?>;
        let datosOfertasDocente  = <?php echo json_encode($ofertasHorariosDocente, 15, 512) ?>;
        <?php endif; ?>
        let moduloSeleccionadoId = null;
        let moduloSeleccionadoHorarioDocenteId = null;

        /* ── switchTab (estudiante tabs) ── */
        function switchTab(btn, tabId) {
            var nav = btn.closest('.est-tabs-nav');
            if (nav) nav.querySelectorAll('.est-tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            var container = document.getElementById('content-estudiante');
            if (container) container.querySelectorAll('.est-tabs-body').forEach(t => t.classList.remove('active'));
            var targetTab = document.getElementById(tabId);
            if (targetTab) targetTab.classList.add('active');

            if (tabId === 'tab-cronograma') {
                if (!calendarioCronograma && datosCronograma && datosCronograma.length > 0) {
                    const select = document.getElementById('select-oferta-cronograma');
                    if (select) {
                        select.value = datosCronograma[0].id;
                        cargarModulosCronograma();
                    }
                } else if (calendarioCronograma) {
                    setTimeout(function() { calendarioCronograma.updateSize(); }, 10);
                }
            }
        }

        /* ── switchTabDocente (docente tabs) ── */
        function switchTabDocente(btn, tabId) {
            var nav = document.getElementById('nav-docente');
            if (nav) nav.querySelectorAll('.est-tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            var container = document.getElementById('content-docente');
            if (container) container.querySelectorAll('.est-tabs-body').forEach(t => t.classList.remove('active'));
            var targetTab = document.getElementById(tabId);
            if (targetTab) targetTab.classList.add('active');

            if (tabId === 'tab-horario-docente') {
                setTimeout(function() {
                    if (!calendarioHorarioDocente && datosOfertasDocente && datosOfertasDocente.length > 0) {
                        const sel = document.getElementById('select-oferta-horario-docente');
                        if (sel) {
                            sel.value = datosOfertasDocente[0].id;
                            cargarModulosHorarioDocente();
                        }
                    } else if (calendarioHorarioDocente) {
                        calendarioHorarioDocente.updateSize();
                    }
                }, 50);
            }
        }

        window.switchTab = switchTab;
        window.switchTabDocente = switchTabDocente;

        /* ── Cronograma funciones ─────────────────────────────── */
        window.cargarModulosCronograma = function() {
                const ofertaId = document.getElementById('select-oferta-cronograma').value;
                const listaModulos = document.getElementById('modulosSidebarListCronograma');
                const btnTodos = document.getElementById('btnTodosModulosCronograma');
                const badge = document.getElementById('moduloSeleccionadoBadgeCronograma');

                moduloSeleccionadoId = null;
                badge.style.display = 'none';
                btnTodos.classList.add('active');

                if (!ofertaId) {
                    listaModulos.innerHTML = '<div class="cronograma-sidebar-empty"><i class="ri-inbox-line"></i>Selecciona una oferta</div>';
                    if (calendarioCronograma) {
                        calendarioCronograma.removeAllEvents();
                    }
                    return;
                }

                const oferta = datosCronograma.find(function(o) { return o.id == parseInt(ofertaId); });
                if (!oferta) {
                    return;
                }

                listaModulos.innerHTML = oferta.modulos.map(function(modulo) {
                    var badgeMoodle = modulo.moodle_course_id ? 
                        '<span style="background:rgba(21,101,192,0.12);color:#1565c0;padding:1px 5px;border-radius:4px;font-size:0.65rem;margin-left:5px;">Moodle</span>' : '';
                    var docenteHtml = '<div class="cronograma-modulo-docente">' + (modulo.docente || 'Sin docente') + '</div>';
                    var sesionesCount = modulo.sesiones_count + '/' + oferta.cantidad_sesiones;
                    return '<div class="cronograma-modulo-card" style="--mod-color:' + modulo.color + ';" onclick="seleccionarModuloCronograma(' + modulo.id + ', \'' + modulo.nombre.replace(/'/g, "\\'") + '\', \'' + modulo.color + '\', event)">' +
                        '<div class="cronograma-modulo-dot" style="background:' + modulo.color + '"></div>' +
                        '<div class="cronograma-modulo-info">' +
                            '<div class="cronograma-modulo-num">Módulo ' + modulo.numero + '</div>' +
                            '<div class="cronograma-modulo-name">' + modulo.nombre + badgeMoodle + '</div>' +
                            docenteHtml +
                        '</div>' +
                        '<div class="cronograma-modulo-badge">' + sesionesCount + '</div>' +
                        '</div>';
                }).join('');

                actualizarCalendarioCronograma(oferta.modulos);
            };

            window.seleccionarModuloCronograma = function(moduloId, moduloNombre, moduloColor, evt) {
                const badge = document.getElementById('moduloSeleccionadoBadgeCronograma');
                const btnTodos = document.getElementById('btnTodosModulosCronograma');

                badge.style.display = 'flex';
                badge.querySelector('.dot').style.background = moduloColor;
                badge.querySelector('.modulo-badge-name').textContent = 'Módulo: ' + moduloNombre;

                btnTodos.classList.remove('active');
                document.querySelectorAll('.cronograma-modulo-card').forEach(function(el) { el.classList.remove('active'); });
                if (evt && evt.target) {
                    evt.target.closest('.cronograma-modulo-card').classList.add('active');
                }

                moduloSeleccionadoId = moduloId;

                const ofertaId = document.getElementById('select-oferta-cronograma').value;
                const oferta = datosCronograma.find(function(o) { return o.id == parseInt(ofertaId); });
                const modulo = oferta.modulos.find(function(m) { return m.id == moduloId; });
                
                actualizarCalendarioCronograma([modulo]);
            };

            window.verTodosModulosCronograma = function() {
                const ofertaId = document.getElementById('select-oferta-cronograma').value;
                const badge = document.getElementById('moduloSeleccionadoBadgeCronograma');
                const btnTodos = document.getElementById('btnTodosModulosCronograma');

                moduloSeleccionadoId = null;
                badge.style.display = 'none';
                btnTodos.classList.add('active');

                document.querySelectorAll('.cronograma-modulo-card').forEach(function(el) { el.classList.remove('active'); });

                if (!ofertaId) return;

                const oferta = datosCronograma.find(function(o) { return o.id == parseInt(ofertaId); });
                actualizarCalendarioCronograma(oferta.modulos);
            };

            window.actualizarCalendarioCronograma = function(modulos) {
                const eventos = [];
                modulos.forEach(function(modulo) {
                    if (modulo.sesiones && modulo.sesiones.length > 0) {
                        modulo.sesiones.forEach(function(sesion) {
                            const esPostergado = sesion.estado === 'Postergado';
                            eventos.push({
                                id: sesion.id,
                                title: sesion.titulo,
                                start: sesion.start,
                                end: sesion.end,
                                backgroundColor: esPostergado ? 'transparent' : modulo.color,
                                borderColor: modulo.color,
                                textColor: esPostergado ? modulo.color : '#fff',
                                extendedProps: {
                                    modulo_nombre: sesion.titulo,
                                    modulo_color: modulo.color,
                                    docente: sesion.docente,
                                    salon: sesion.salon,
                                    estado: sesion.estado,
                                    enlace_videollamada_url:    sesion.enlace_videollamada_url    || '',
                                    enlace_videollamada_nombre: sesion.enlace_videollamada_nombre || '',
                                    enlace_grabacion:           sesion.enlace_grabacion           || '',
                                    reprogramado_de_fecha:      sesion.reprogramado_de_fecha      || null,
                                    reprogramado_a_fecha:       sesion.reprogramado_a_fecha       || null,
                                }
                            });
                        });
                    }
                });

                const calendarEl = document.getElementById('calendarCronograma');

                if (!calendarioCronograma) {
                    calendarioCronograma = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,listMonth'
                        },
                        locale: 'es',
                        buttonText: {
                            today: 'Hoy',
                            month: 'Mes',
                            week: 'Semana',
                            list: 'Lista'
                        },
                        editable: false,
                        selectable: false,
                        eventDisplay: 'block',
                        eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
                        eventDidMount: function(info) {
                            const estado = info.event.extendedProps.estado;
                            const color  = info.event.extendedProps.modulo_color || info.event.backgroundColor;
                            if (estado === 'Postergado') {
                                info.el.classList.add('fc-event-postergado');
                                info.el.style.setProperty('border-color', color, 'important');
                                const titleEl = info.el.querySelector('.fc-event-title');
                                if (titleEl) {
                                    titleEl.style.setProperty('color', color, 'important');
                                    if (!titleEl.querySelector('.ri-time-line')) {
                                        titleEl.innerHTML = '<i class="ri-time-line me-1" style="font-size:.8rem;vertical-align:middle;"></i>' + titleEl.innerHTML;
                                    }
                                }
                                const timeEl = info.el.querySelector('.fc-event-time');
                                if (timeEl) timeEl.style.setProperty('color', color, 'important');
                            } else {
                                info.el.style.setProperty('background-color', color, 'important');
                                info.el.style.setProperty('border-color', color, 'important');
                                info.el.style.setProperty('color', '#fff', 'important');
                            }
                        },
                        eventClick: function(info) {
                            abrirModalSesionEstudiante(info.event);
                        },
                        height: 'auto'
                    });
                    calendarioCronograma.render();
                    calendarioCronograma.addEventSource(eventos);
                } else {
                    calendarioCronograma.removeAllEventSources();
                    calendarioCronograma.addEventSource(eventos);
                }
            };

            /* ── Modal detalle sesión estudiante ──────────────────── */
            function abrirModalSesionEstudiante(event) {
                const props = event.extendedProps || {};
                const start = event.start;
                const end   = event.end;

                const fecha     = start ? start.toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : '—';
                const horaInicio = start ? start.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' }) : '—';
                const horaFin   = end   ? end.toLocaleTimeString('es-ES',   { hour: '2-digit', minute: '2-digit' }) : '—';
                const estado    = props.estado || 'Confirmado';
                const color     = props.modulo_color || event.backgroundColor || '#6366f1';

                document.getElementById('estDetColorBar').style.background  = color;
                document.getElementById('estDetModulo').textContent          = props.modulo_nombre || event.title || '—';
                document.getElementById('estDetFecha').textContent           = fecha;
                document.getElementById('estDetHora').textContent            = horaInicio + ' — ' + horaFin;
                document.getElementById('estDetDocente').textContent         = props.docente || 'Sin asignar';

                const estadoMap = {
                    'Confirmado':  { cls: 'bg-secondary', label: 'Confirmado' },
                    'Desarrollado':{ cls: 'bg-success',   label: 'Desarrollado' },
                    'Postergado':  { cls: 'bg-warning',   label: 'Postergado' },
                };
                const est = estadoMap[estado] || estadoMap['Confirmado'];
                document.getElementById('estDetEstado').innerHTML = '<span class="badge ' + est.cls + '">' + est.label + '</span>';

                const repInfo = document.getElementById('estDetReprogramadoInfo');
                const repMsg  = document.getElementById('estDetReprogramadoMsg');
                repInfo.style.display = 'none';
                if (props.reprogramado_a_fecha) {
                    repMsg.innerHTML = '<i class="ri-arrow-right-line me-1"></i> Esta sesión fue postergada al <strong>' + props.reprogramado_a_fecha + '</strong>';
                    repInfo.classList.remove('alert-success'); repInfo.classList.add('alert-info');
                    repInfo.style.display = 'block';
                } else if (props.reprogramado_de_fecha) {
                    repMsg.innerHTML = '<i class="ri-history-line me-1"></i> Sesión reprogramada de la fecha <strong>' + props.reprogramado_de_fecha + '</strong>';
                    repInfo.classList.remove('alert-info'); repInfo.classList.add('alert-success');
                    repInfo.style.display = 'block';
                }

                const enlaceWrap    = document.getElementById('estDetEnlaceWrap');
                const grabacionWrap = document.getElementById('estDetGrabacionWrap');
                const sinEnlaces    = document.getElementById('estDetSinEnlaces');
                const enlaceUrl     = props.enlace_videollamada_url    || '';
                const enlaceNombre  = props.enlace_videollamada_nombre || '';
                const grabUrl       = props.enlace_grabacion           || '';

                enlaceWrap.style.display    = 'none';
                grabacionWrap.style.display = 'none';
                sinEnlaces.style.display    = 'none';

                if (estado === 'Desarrollado' && grabUrl) {
                    grabacionWrap.style.display = 'block';
                    document.getElementById('estDetGrabacionBtn').onclick = function() {
                        const url = /^https?:\/\//i.test(grabUrl) ? grabUrl : 'https://' + grabUrl;
                        window.open(url, '_blank', 'noopener,noreferrer');
                    };
                } else if (estado === 'Confirmado' && enlaceUrl) {
                    enlaceWrap.style.display = 'block';
                    document.getElementById('estDetEnlaceNombre').textContent = enlaceNombre || 'Sesión virtual';
                    document.getElementById('estDetEnlaceBtn').onclick = function() {
                        const url = /^https?:\/\//i.test(enlaceUrl) ? enlaceUrl : 'https://' + enlaceUrl;
                        window.open(url, '_blank', 'noopener,noreferrer');
                    };
                } else {
                    sinEnlaces.style.display = 'flex';
                    const sinDivs = sinEnlaces.querySelectorAll('div');
                    if (estado === 'Postergado') {
                        sinDivs[0].textContent = 'Sesión postergada';
                        sinDivs[1].textContent = 'Esta sesión ha sido postergada a una nueva fecha.';
                    } else if (estado === 'Desarrollado') {
                        sinDivs[0].textContent = 'Sesión concluida';
                        sinDivs[1].textContent = 'La grabación estará disponible cuando el docente la comparta.';
                    } else {
                        sinDivs[0].textContent = 'Sesión confirmada';
                        sinDivs[1].innerHTML   = 'Los enlaces de sesión virtual o grabación<br>aparecerán aquí cuando estén disponibles.';
                    }
                }

                const modalEl = document.getElementById('modalDetalleSesionEst');
                const existingModal = bootstrap.Modal.getInstance(modalEl);
                if (existingModal) existingModal.show();
                else new bootstrap.Modal(modalEl).show();
            }
            window.abrirModalSesionEstudiante = abrirModalSesionEstudiante;

            /* ── Oferta sub-tabs (contable) ────────────────────────── */
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.est-oferta-tab-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const targetId = this.getAttribute('data-target');
                        const parentTab = this.closest('.est-tabs-body');
                        if (!parentTab || !targetId) return;
                        parentTab.querySelectorAll('.est-oferta-tab-btn').forEach(b => b
                            .classList.remove('active'));
                        this.classList.add('active');
                        parentTab.querySelectorAll('.est-oferta-content').forEach(c => c
                            .classList.remove('active'));
                        const el = document.getElementById(targetId);
                        if (el) el.classList.add('active');
                    });
                });
                
                // Pagos tabs (nuevo)
                document.querySelectorAll('.pagos-tab-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const targetId = this.getAttribute('data-target');
                        const parentWrapper = this.closest('.pagos-tabs-wrapper');
                        if (!parentWrapper || !targetId) return;
                        parentWrapper.querySelectorAll('.pagos-tab-btn').forEach(b => b
                            .classList.remove('active'));
                        this.classList.add('active');
                        parentWrapper.querySelectorAll('.pagos-oferta-content').forEach(c => c
                            .classList.remove('active'));
                        const el = document.getElementById(targetId);
                        if (el) el.classList.add('active');
                    });
                });
            });

            /* ── Ver detalle pago (contable) ───────────────────────── */
            document.querySelectorAll('.btn-ver-detalle-pago').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const pagosData = JSON.parse(this.getAttribute('data-pagos'));
                    const listaPagos = document.getElementById('lista-pagos');
                    const container = document.getElementById('detalle-pago-container');

                    if (pagosData.length === 1) {
                        listaPagos.style.display = 'none';
                        container.style.display = 'block';
                        mostrarDetallePago(pagosData[0]);
                    } else {
                        listaPagos.style.display = 'block';
                        container.style.display = 'none';
                        listaPagos.innerHTML = '';
                        listaPagos.style.padding = '0';
                        var headerHtml = '<div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:#f8fafc;border-bottom:2px solid #e2e8f0;border-radius:12px 12px 0 0;">' +
                            '<span style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;"><i class="ri-receipt-line me-1"></i> Selecciona un recibo</span>' +
                            '<span style="font-size:.72rem;color:#94a3b8;">' + pagosData.length + ' pago(s)</span>' +
                            '</div>';
                        listaPagos.innerHTML = headerHtml;
                        pagosData.forEach(function(pago) {
                            const item = document.createElement('div');
                            item.style.cssText = 'display:flex;align-items:center;gap:14px;padding:14px 16px;cursor:pointer;border-left:3px solid #fc7b04;margin:4px 0;border-radius:10px;background:#fff;border:1px solid #e2e8f0;transition:all .2s;';
                            item.onmouseover = function() { this.style.background = '#fef3c7'; this.style.borderColor = '#fc7b04'; };
                            item.onmouseout = function() { this.style.background = '#fff'; this.style.borderColor = '#e2e8f0'; };
                            var metodoColor = pago.metodo === 'Efectivo' ? '#2563eb' : pago.metodo === 'Qr' ? '#059669' : pago.metodo === 'Transferencia' ? '#4f46e5' : '#64748b';
                            var metodoIcon = pago.metodo === 'Efectivo' ? 'ri-cash-line' : pago.metodo === 'Qr' ? 'ri-qr-code-line' : pago.metodo === 'Transferencia' ? 'ri-bank-line' : 'ri-payment-line';
                            item.innerHTML =
                                '<div style="width:36px;height:36px;border-radius:10px;background:rgba(252,123,4,.1);color:#fc7b04;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1rem;"><i class="ri-receipt-line"></i></div>' +
                                '<div style="flex:1;min-width:0;">' +
                                '<div style="font-weight:700;font-size:.88rem;color:#1e293b;">' + (pago.recibo || '—') + '</div>' +
                                '<div style="font-size:.72rem;color:#64748b;margin-top:2px;"><i class="ri-calendar-line me-1"></i>' + (pago.fecha ? new Date(pago.fecha).toLocaleDateString('es-ES') : '') + ' <span style="color:#cbd5e1;">·</span> <i class="' + metodoIcon + '" style="color:' + metodoColor + ';margin-right:3px;"></i>' + (pago.metodo || '—') + '</div>' +
                                '</div>' +
                                '<div style="text-align:right;flex-shrink:0;">' +
                                '<div style="font-weight:700;font-size:.9rem;color:#059669;">Bs. ' + parseFloat(pago.monto).toFixed(2) + '</div>' +
                                '<div style="font-size:.7rem;color:#94a3b8;margin-top:1px;"><i class="ri-arrow-right-s-line"></i> Ver detalle</div>' +
                                '</div>';
                            item.addEventListener('click', function() {
                                listaPagos.style.display = 'none';
                                container.style.display = 'block';
                                mostrarDetallePago(pago);
                            });
                            listaPagos.appendChild(item);
                        });
                        const totalGeneral = pagosData.reduce((s, p) => s + parseFloat(p.monto), 0);
                        const totalItem = document.createElement('div');
                        totalItem.style.cssText = 'display:flex;align-items:center;justify-content:space-between;padding:14px 16px;margin-top:6px;border-radius:10px;background:linear-gradient(135deg,#fc7b04,#c96004);color:#fff;';
                        totalItem.innerHTML =
                            '<div style="display:flex;align-items:center;gap:8px;"><i class="ri-check-double-line" style="font-size:1.1rem;"></i><span style="font-weight:600;font-size:.85rem;">Total Acumulado</span></div>' +
                            '<div style="font-weight:800;font-size:1rem;">Bs. ' +
                            totalGeneral.toFixed(2) + '</div>';
                        listaPagos.appendChild(totalItem);
                        var footerNote = document.createElement('div');
                        footerNote.style.cssText = 'text-align:center;padding:10px;font-size:.72rem;color:#94a3b8;';
                        footerNote.innerHTML = '<i class="ri-information-line me-1"></i> Selecciona un recibo para ver su detalle completo';
                        listaPagos.appendChild(footerNote);
                    }

                    const modalEl = document.getElementById('modalVerDetallePago');
                    modalEl.classList.add('show');
                    modalEl.style.display = 'block';
                    document.body.classList.add('modal-open');
                    const backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);
                    setTimeout(function() { backdrop.classList.add('show'); }, 10);
                });
            });

            function closePagoModal() {
                const modalEl = document.getElementById('modalVerDetallePago');
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                document.body.classList.remove('modal-open');
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(function(b) { b.remove(); });
            }

            function verFactura(url, recibo, estudiante, monto, oferta) {
                document.getElementById('facturaReciboNum').textContent = recibo;
                document.getElementById('facturaEstudiante').textContent = estudiante;
                document.getElementById('facturaOferta').textContent = oferta;
                document.getElementById('facturaMonto').textContent = 'Bs. ' + monto;
                document.getElementById('facturaDownloadLink').href = url;

                var container = document.getElementById('facturaFileContainer');
                var ext = url.split('.').pop().toLowerCase();
                if (ext === 'pdf') {
                    container.innerHTML = '<iframe src="' + url + '" style="width:100%;height:500px;border:none;border-radius:8px;"></iframe>';
                } else {
                    container.innerHTML = '<img src="' + url + '" style="max-width:100%;max-height:500px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">';
                }

                new bootstrap.Modal(document.getElementById('modalVerFactura')).show();
            }

            document.getElementById('btn-volver-lista')?.addEventListener('click', function() {
                document.getElementById('lista-pagos').style.display = 'block';
                document.getElementById('detalle-pago-container').style.display = 'none';
                closePagoModal();
            });

            document.getElementById('modalVerDetallePago').querySelectorAll('[data-bs-dismiss="modal"], .btn-close').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    closePagoModal();
                });
            });

            document.getElementById('modalVerDetallePago').querySelector('.btn-cerrar')?.addEventListener('click', function() {
                closePagoModal();
            });

            function mostrarDetallePago(pago) {
                document.getElementById('detalle-recibo').textContent = pago.recibo || '—';
                document.getElementById('detalle-fecha').textContent = pago.fecha ? new Date(pago.fecha)
                    .toLocaleDateString('es-ES') : '—';
                document.getElementById('detalle-metodo').textContent = pago.metodo || '—';
                document.getElementById('detalle-estudiante').textContent = pago.estudiante || '—';
                document.getElementById('detalle-programa').textContent = pago.programa || '—';
                document.getElementById('detalle-plan').textContent = pago.plan || '—';

                const tbody = document.getElementById('detalle-tabla');
                tbody.innerHTML = '';
                let totalDetalle = 0;
                if (pago.cuotas && pago.cuotas.length > 0) {
                    pago.cuotas.forEach(function(c, i) {
                        totalDetalle += parseFloat(c.monto);
                        const tr = document.createElement('tr');
                        tr.innerHTML = '<td>' + (i + 1) + '</td><td>' + (c.nombre || 'Cuota #' + (c.n_cuota ||
                                i + 1)) + '</td><td class="text-end">Bs. ' + parseFloat(c.monto).toFixed(2) +
                            '</td>';
                        tbody.appendChild(tr);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center">Sin cuotas</td></tr>';
                }
                document.getElementById('detalle-total').textContent = 'Bs. ' + totalDetalle.toFixed(2);

                const descContainer = document.getElementById('detalle-descuento-container');
                if (pago.descuento && parseFloat(pago.descuento) > 0) {
                    descContainer.style.display = 'block';
                    document.getElementById('detalle-descuento').textContent = 'Bs. ' + parseFloat(pago.descuento)
                        .toFixed(2);
                } else {
                    descContainer.style.display = 'none';
                }

                const facturaContainer = document.getElementById('detalle-factura-container');
                const facturaEstado = document.getElementById('detalle-factura-estado');
                const btnVerFactura = document.getElementById('btn-ver-factura-detalle');
                if (pago.documento_factura) {
                    facturaContainer.style.display = 'block';
                    facturaEstado.textContent = 'Con factura';
                    btnVerFactura.onclick = function() {
                        verFactura(pago.documento_factura, pago.recibo, pago.estudiante, pago.monto, pago.programa);
                    };
                } else {
                    facturaContainer.style.display = 'none';
                }

                document.getElementById('detalle-trabajador').textContent = pago.trabajador || '—';
                document.getElementById('detalle-depositante').textContent = pago.estudiante || '—';

                const btnPdf = document.getElementById('btn-descargar-pdf');
                const footerBtns = btnPdf ? btnPdf.parentNode : null;
                if (footerBtns) {
                    const existingComprobanteBtn = footerBtns.querySelector('.btn-comprobante');
                    if (existingComprobanteBtn) existingComprobanteBtn.remove();

                    if (pago.comprobante) {
                        const btnComprobante = document.createElement('a');
                        btnComprobante.href = pago.comprobante.url;
                        btnComprobante.target = '_blank';
                        btnComprobante.className = 'btn text-white btn-comprobante me-2';
                        btnComprobante.style.background = '#059669';
                        btnComprobante.innerHTML = '<i class="ri-file-image-line"></i> Ver Comprobante';
                        footerBtns.insertBefore(btnComprobante, btnPdf);
                    }
                }

                if (btnPdf && pago && pago.id) {
                    btnPdf.href = '/virtual/recibo/' + pago.id + '/pdf';
                }
            }

            /* ── Moodle SSO helper ─────────────────────────────────── */
            function openMoodleSso(targetUrl) {
                window.open('/estudiante/moodle-sso?target=' + encodeURIComponent(targetUrl), '_blank');
            }

            /* ── Actividades (tab académico) ───────────────────────── */
            $(document).on('click', '.btn-ver-actividades', function() {
                const moduloId = $(this).data('modulo');
                const panelId = $(this).data('panel');
                const $panel = $('#' + panelId);

                if ($panel.is(':visible')) {
                    $panel.slideUp(200);
                    $(this).html('<i class="ri-eye-line"></i> Ver actividades');
                    return;
                }
                $panel.slideDown(200);
                $(this).html('<i class="ri-eye-off-line"></i> Ocultar');
                if (loaded[moduloId]) return;

                $.get('/virtual/actividades/' + moduloId)
                    .done(function(r) {
                        $('#spinner-mod-' + moduloId).hide();
                        if (!r.success) {
                            $('#contenido-mod-' + moduloId).html(
                                '<p style="color:#dc3545;font-size:.85rem;"><i class="ri-close-circle-line"></i> ' +
                                escHtml(r.message) + '</p>');
                            return;
                        }
                        console.log('[Moodle actividades] módulo=' + moduloId, r.contenidos);
                        renderActividades(moduloId, r.contenidos, r.calificaciones, r.entregas || {}, r.archivos_subidos || {}, r.foros_participacion || {}, r.tareas_fechas || {}, r.cuestionarios || [], r.foros || []);
                        loaded[moduloId] = true;
                    })
                    .fail(function() {
                        $('#spinner-mod-' + moduloId).hide();
                        $('#contenido-mod-' + moduloId).html(
                            '<p style="color:#dc3545;font-size:.85rem;"><i class="ri-wifi-off-line"></i> Error al conectar con Moodle.</p>'
                            );
                    });
            });

            function renderActividades(moduloId, contenidos, calificaciones, entregas, archivosSubidos, forosParticipacion, tareasFechas, cuestionarios, forosData) {
                const gradeMap = {};
                if (calificaciones && Array.isArray(calificaciones)) {
                    calificaciones.forEach(function(item) {
                        if (item.cmid) gradeMap[item.cmid] = item;
                    });
                }
                const entregaMap = entregas || {};
                const archivosMap = archivosSubidos || {};

                // Mapas de fechas por instance id y cmid — igual que admin
                const tareasFechasMap = tareasFechas || {};
                const quizzesMap = {}, quizzesByCmid = {};
                (cuestionarios || []).forEach(function(q) {
                    if (q.id)           quizzesMap[q.id]              = q;
                    if (q.cmid)         quizzesByCmid[q.cmid]         = q;
                    if (q.coursemodule) quizzesByCmid[q.coursemodule] = q;
                });
                const forosMap = {}, forosByCmid = {};
                (forosData || []).forEach(function(f) {
                    if (f.id)   forosMap[f.id]   = f;
                    if (f.cmid) forosByCmid[f.cmid] = f;
                });
                let html = '';
                if (!contenidos || contenidos.length === 0) {
                    html =
                        '<p style="color:#6c757d;font-size:.85rem;"><i class="ri-information-line"></i> No hay contenido disponible.</p>';
                } else {
                    contenidos.forEach(function(seccion) {
                        const modulos = seccion.modules || [];
                        if (modulos.length === 0) return;
                        
                        // Mostrar descripción de la sección si existe
                        if (seccion.description) {
                            html += '<div class="est-seccion-descripcion">' + seccion.description + '</div>';
                        }
                        
                        html += '<div class="est-act-section">' + escHtml(seccion.name || 'Sección') + '</div>';
                        modulos.forEach(function(mod) {
                            // Mostrar contenido de labels
                            if (mod.modname === 'label') {
                                if (mod.description) html += '<div class="est-label-content">' + mod.description + '</div>';
                                return;
                            }
                            
                            // Mostrar contenido de otras actividades si existe
                            if (mod.description) {
                                html += '<div class="est-mod-descripcion">' + mod.description + '</div>';
                            }
                            
                            const grade = gradeMap[mod.id];
                            const nota = grade ? grade.gradeformatted : null;
                            const notaMax = grade && grade.max ? grade.max : null;
                            const participoForo = mod.modname === 'forum' && forosParticipacion[mod.id];
                            const enviado = mod.modname === 'assign' && entregaMap[mod.id];
                            const icono = iconoModulo(mod.modname);
                            let badge = '';
                            if (nota && nota !== '-') {
                                const numVal = parseFloat(nota);
                                const maxStr = notaMax ? ' / ' + notaMax : '';
                                if (!isNaN(numVal)) {
                                    badge = numVal >= 51 ?
                                        '<span class="est-nota-badge aprobado">' + escHtml(nota) + maxStr +
                                        '</span>' :
                                        '<span class="est-nota-badge reprobado">' + escHtml(nota) + maxStr +
                                        '</span>';
                                } else {
                                    badge = '<span class="est-nota-badge pendiente">' + escHtml(nota) + maxStr +
                                        '</span>';
                                }
                            } else if (enviado) {
                                badge = '<span class="est-nota-badge" style="background:#e6f7e6;color:#16a34a;">Enviado</span>';
                            } else if (participoForo) {
                                badge = '<span class="est-nota-badge" style="background:#e6f7e6;color:#16a34a;">Realizado</span>';
                            } else {
                                badge = '<span class="est-nota-badge pendiente">Pendiente</span>';
                            }
                            const url = mod.url ?
                                '<a href="#" class="moodle-link" data-target="' + escHtml(mod.url) +
                                '" style="font-size:.72rem;color:#fc7b04;margin-left:.5rem;"><i class="ri-external-link-line"></i></a>' :
                                '';
                            // Fechas de actividad — misma lógica que modulo-detalle admin
                            let fechasHtml = '';
                            const now = Math.floor(Date.now() / 1000);
                            const fmtTs = function(ts) {
                                if (!ts || ts === 0) return null;
                                return new Date(ts * 1000).toLocaleString('es-BO', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' });
                            };

                            let tsInicio = null, tsFin = null;

                            if (mod.modname === 'assign') {
                                // Fuente primaria: tareasFechasMap (igual que admin)
                                const tfEntry = tareasFechasMap[mod.instance] || tareasFechasMap['cm_' + mod.id] || {};
                                tsInicio = tfEntry.open || null;
                                tsFin    = tfEntry.due  || null;
                                // Fallback: activity_dates
                                if (!tsInicio && !tsFin) {
                                    const ad = mod.activity_dates;
                                    if (ad) { tsInicio = ad.open || null; tsFin = ad.due || ad.close || null; }
                                }
                            } else if (mod.modname === 'quiz') {
                                // Fuente primaria: quizzesMap
                                const quiz = quizzesMap[mod.instance] || quizzesByCmid[mod.id];
                                if (quiz) {
                                    tsInicio = quiz.timeopen  || null;
                                    tsFin    = quiz.timeclose || null;
                                }
                                // Fallback: activity_dates
                                if (!tsInicio && !tsFin) {
                                    const ad = mod.activity_dates;
                                    if (ad) { tsInicio = ad.open || null; tsFin = ad.close || null; }
                                }
                            } else if (mod.modname === 'forum') {
                                // Fuente primaria: forosMap
                                const foro = forosMap[mod.instance] || forosByCmid[mod.id];
                                if (foro) {
                                    tsInicio = foro.timeopen || foro.duedate || null;
                                    tsFin    = foro.duedate  || foro.timeopen || null;
                                    // Si timeopen y duedate son distintos, usar timeopen como inicio y duedate como vencimiento
                                    if (foro.timeopen && foro.duedate && foro.timeopen !== foro.duedate) {
                                        tsInicio = foro.timeopen;
                                        tsFin    = foro.duedate;
                                    }
                                }
                                // Fallback: activity_dates
                                if (!tsInicio && !tsFin) {
                                    const ad = mod.activity_dates;
                                    if (ad) { tsInicio = ad.open || null; tsFin = ad.close || ad.due || null; }
                                }
                            }

                            // Fallback final: mod.dates[]
                            if (!tsInicio && !tsFin && Array.isArray(mod.dates) && mod.dates.length) {
                                mod.dates.forEach(function(entry) {
                                    const lbl = (entry.label || '').toLowerCase();
                                    const ts  = entry.timestamp || 0;
                                    if (!ts) return;
                                    if (lbl.includes('open') || lbl.includes('abre') || lbl.includes('inicio') || lbl.includes('desde')) {
                                        if (!tsInicio) tsInicio = ts;
                                    } else if (lbl.includes('due') || lbl.includes('close') || lbl.includes('cutoff') || lbl.includes('entrega') || lbl.includes('cierre') || lbl.includes('vencimiento')) {
                                        if (!tsFin) tsFin = ts;
                                    } else {
                                        if (!tsFin) tsFin = ts;
                                        else if (!tsInicio) tsInicio = ts;
                                    }
                                });
                            }

                            // Estado de ventana de tiempo
                            const noAbierto     = tsInicio && tsInicio > now;
                            const vencidoGlobal = tsFin && tsFin < now;
                            const dentroDeFecha = !noAbierto && !vencidoGlobal;

                            if (mod.modname === 'assign') {
                                const strOpen = fmtTs(tsInicio);
                                const strDue  = fmtTs(tsFin);
                                const abierto = tsInicio && tsInicio <= now;
                                const overdue = tsFin && tsFin < now;
                                let chips = '';
                                if (strOpen) chips += '<span class="act-date-chip act-date-open' + (abierto ? ' act-date-active' : '') + '"><i class="ri-calendar-event-line"></i> Inicio: '   + strOpen + '</span>';
                                if (strDue)  chips += '<span class="act-date-chip act-date-due'  + (overdue ? ' act-date-overdue' : '') + '"><i class="ri-calendar-check-line"></i> Entrega: ' + strDue  + '</span>';
                                if (chips)   fechasHtml = '<div class="act-dates-row">' + chips + '</div>';
                            } else if (mod.modname === 'quiz') {
                                const strOpen  = fmtTs(tsInicio);
                                const strClose = fmtTs(tsFin);
                                const abiertoQ = tsInicio && tsInicio <= now;
                                const vencidoQ = tsFin && tsFin < now;
                                let chips = '';
                                if (strOpen)  chips += '<span class="act-date-chip act-date-open' + (abiertoQ ? ' act-date-active' : '') + '"><i class="ri-calendar-event-line"></i> Inicio: ' + strOpen  + '</span>';
                                if (strClose) chips += '<span class="act-date-chip act-date-due'  + (vencidoQ ? ' act-date-overdue' : '') + '"><i class="ri-calendar-check-line"></i> Cierre: ' + strClose + '</span>';
                                if (chips)    fechasHtml = '<div class="act-dates-row">' + chips + '</div>';
                            } else if (mod.modname === 'forum') {
                                const strOpen = fmtTs(tsInicio);
                                const strVenc = fmtTs(tsFin);
                                const abiertoF = tsInicio && tsInicio <= now;
                                const overF    = tsFin && tsFin < now;
                                let chips = '';
                                if (strOpen && strVenc && strOpen !== strVenc) {
                                    chips += '<span class="act-date-chip act-date-open' + (abiertoF ? ' act-date-active' : '') + '"><i class="ri-calendar-event-line"></i> Inicio: ' + strOpen + '</span>';
                                    chips += '<span class="act-date-chip act-date-due'  + (overF    ? ' act-date-overdue' : '') + '"><i class="ri-calendar-check-line"></i> Vencimiento: ' + strVenc + '</span>';
                                } else {
                                    const sola = strVenc || strOpen;
                                    const ts   = tsFin   || tsInicio;
                                    const past = ts && ts < now;
                                    if (sola) chips = '<span class="act-date-chip act-date-open' + (past ? ' act-date-active' : '') + '"><i class="ri-calendar-event-line"></i> Vencimiento: ' + sola + '</span>';
                                }
                                if (chips) fechasHtml = '<div class="act-dates-row">' + chips + '</div>';
                            }

                            let btnRealizar = '';
                            if (['assign', 'quiz', 'forum'].includes(mod.modname) && dentroDeFecha) {
                                const iconBtn = mod.modname === 'assign' ? 'ri-upload-2-line'
                                    : mod.modname === 'forum' ? 'ri-discuss-line'
                                    : 'ri-play-circle-line';
                                const labelBtn = (mod.modname === 'assign' && enviado) ? 'Modificar'
                                    : mod.modname === 'assign' ? 'Entregar'
                                    : mod.modname === 'forum' ? 'Participar'
                                    : 'Ver quiz';
                                btnRealizar = '<button class="btn-est-realizar"' +
                                    ' data-cmid="' + mod.id + '"' +
                                    ' data-modname="' + mod.modname + '"' +
                                    ' data-moduloid="' + moduloId + '"' +
                                    ' data-name="' + escHtml(mod.name) + '"' +
                                    ' style="margin-left:auto;background:#fc7b04;color:#fff;border:none;border-radius:6px;padding:.3rem .75rem;font-size:.75rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:.3rem;white-space:nowrap;">' +
                                    '<i class="' + iconBtn + '"></i> ' + labelBtn + '</button>';
                            }

                            // Info extra para actividades vencidas (nota, archivos)
                            let infoVencidaHtml = '';
                            if (vencidoGlobal) {
                                const gItem = gradeMap[mod.id];
                                let gNota = gItem ? (gItem.gradeformatted || null) : null;
                                if (!gNota && gItem && gItem.grades) {
                                    const vals = Object.values(gItem.grades);
                                    if (vals.length > 0 && vals[0] !== null) gNota = String(vals[0]);
                                }
                                if (gNota && gNota !== '-') {
                                    const gMax = gItem && gItem.max ? ' / ' + gItem.max : '';
                                    infoVencidaHtml += '<div style="display:flex;align-items:center;gap:.4rem;padding:.2rem 0;font-size:.75rem;color:#16a34a;font-weight:600;">' +
                                        '<i class="ri-award-line"></i> ' + escHtml(gNota) + gMax + '</div>';
                                } else if (mod.modname === 'assign' && enviado) {
                                    infoVencidaHtml += '<div style="display:flex;align-items:center;gap:.4rem;padding:.2rem 0;font-size:.72rem;color:#6c757d;">' +
                                        '<i class="ri-hourglass-line"></i> Sin calificación registrada</div>';
                                }
                                // Archivos adjuntos de tarea
                                if (mod.modname === 'assign') {
                                    const archivos = archivosMap[mod.id];
                                    if (archivos && archivos.length > 0) {
                                        archivos.forEach(function(f) {
                                            var dlUrl = '/virtual/modulo/' + moduloId + '/actividad/tarea/' + mod.id + '/archivo/' + encodeURIComponent(f.filename);
                                            infoVencidaHtml += '<div style="display:flex;align-items:center;gap:.3rem;padding:.15rem 0;font-size:.72rem;">' +
                                                '<a href="' + dlUrl + '" style="color:#3b82f6;text-decoration:none;display:flex;align-items:center;gap:.25rem;" download><i class="ri-download-2-line"></i> ' + escHtml(f.filename) + '</a></div>';
                                        });
                                    }
                                }
                            }

                            html += '<div class="est-act-item">' +
                                '<div class="est-act-item-name">' +
                                    icono + ' ' + escHtml(mod.name) + url +
                                    '<small>' + etiquetaModulo(mod.modname) + '</small>' +
                                '</div>' +
                                (fechasHtml ? '<div class="est-act-item-dates">' + fechasHtml + '</div>' : '<div class="est-act-item-dates"></div>') +
                                '<div class="est-act-item-actions">' + badge + btnRealizar + '</div>' +
                                (infoVencidaHtml ? '<div class="est-act-item-dates" style="padding-top:.25rem;">' + infoVencidaHtml + '</div>' : '') +
                                '</div>';
                        });
                    });
                }
                $('#contenido-mod-' + moduloId).html(html);
            }

            function iconoModulo(modname) {
                const map = {
                    assign: '<i class="ri-file-text-line" style="color:#3b82f6;"></i>',
                    quiz: '<i class="ri-question-line" style="color:#f97316;"></i>',
                    forum: '<i class="ri-discuss-line" style="color:#8b5cf6;"></i>',
                    resource: '<i class="ri-file-line" style="color:#6c757d;"></i>',
                    url: '<i class="ri-links-line" style="color:#10b981;"></i>',
                    page: '<i class="ri-pages-line" style="color:#6366f1;"></i>',
                    label: '<i class="ri-text" style="color:#0ea5e9;"></i>'
                };
                return map[modname] || '<i class="ri-apps-line" style="color:#6c757d;"></i>';
            }

            function etiquetaModulo(modname) {
                const map = {
                    assign: 'Tarea',
                    quiz: 'Cuestionario',
                    forum: 'Foro',
                    resource: 'Archivo',
                    url: 'Enlace',
                    page: 'Página',
                    label: 'Área de texto y medios'
                };
                return map[modname] || modname;
            }

            function escHtml(str) {
                return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(
                    /"/g, '&quot;');
            }

            $(document).on('click', '.moodle-link', function(e) {
                e.preventDefault();
                openMoodleSso($(this).data('target'));
            });

            /* ── Abrir modal actividad (delegación) ──────────────────────── */
            $(document).on('click', '.btn-est-realizar', function() {
                abrirModalAct(
                    $(this).data('cmid'),
                    $(this).data('modname'),
                    $(this).data('moduloid'),
                    $(this).data('name')
                );
            });

        /* ══════════════════════════════════════════════
           TAB PAGOS — funciones globales
        ══════════════════════════════════════════════ */
        var estCompInscripcionId = null;

        // Event listener para el file input del comprobante
        document.getElementById('estCompArchivo')?.addEventListener('change', function(e) {
            var file = e.target.files[0];
            var area = document.getElementById('comprobanteFileArea');
            if (file) {
                area.classList.add('has-file');
                area.innerHTML = '<i class="ri-file-check-line"></i><span>' + file.name + '</span><small>Listo para subir</small>';
            } else {
                area.classList.remove('has-file');
                area.innerHTML = '<i class="ri-upload-cloud-line"></i><span>Haz clic para seleccionar el archivo</span><small>JPG, PNG o PDF — máx. 5 MB</small>';
            }
        });

        function estMostrarToast(tipo, mensaje) {
            var bg    = tipo === 'success' ? '#16a34a' : tipo === 'warning' ? '#d97706' : '#dc2626';
            var icono = tipo === 'success' ? 'ri-checkbox-circle-line' : tipo === 'warning' ? 'ri-alert-line' : 'ri-close-circle-line';
            var t = document.createElement('div');
            t.style.cssText = 'background:' + bg + ';color:#fff;padding:.75rem 1.25rem;border-radius:10px;font-size:.85rem;font-weight:500;display:flex;align-items:center;gap:.6rem;box-shadow:0 8px 24px rgba(0,0,0,.18);max-width:360px;';
            t.innerHTML = '<i class="' + icono + '" style="font-size:1.1rem;flex-shrink:0;"></i><span>' + String(mensaje).replace(/</g,'&lt;') + '</span>';
            var c = document.getElementById('est-toast-container');
            if (c) c.appendChild(t);
            setTimeout(function() { t.style.opacity='0'; t.style.transition='opacity .4s'; setTimeout(function(){ t.remove(); }, 400); }, 4000);
        }

        function abrirQrModal(src) {
            var img = document.getElementById('qrLightboxImg');
            var overlay = document.getElementById('qrOverlay');
            if (img && overlay) {
                img.src = src;
                overlay.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function cerrarQrOverlay(e) {
            var overlay = document.getElementById('qrOverlay');
            if (overlay) {
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        function estAbrirModal(inscripcionId, programa, plan) {
            estCompInscripcionId = inscripcionId;
            document.getElementById('estCompPrograma').textContent = programa || '—';
            document.getElementById('estCompPlan').textContent = 'Plan: ' + (plan || '—');
            document.getElementById('estCompArchivo').value = '';
            document.getElementById('estCompObservaciones').value = '';
            
            // Reset file area visual
            var area = document.getElementById('comprobanteFileArea');
            area.classList.remove('has-file');
            area.innerHTML = '<i class="ri-upload-cloud-line"></i><span>Haz clic para seleccionar el archivo</span><small>JPG, PNG o PDF — máx. 5 MB</small>';

            var cuotasContainer = document.getElementById('estCompCuotasContainer');
            var cuotasLoading   = document.getElementById('estCompCuotasLoading');
            cuotasContainer.style.display = 'none';
            cuotasContainer.innerHTML = '';
            cuotasLoading.style.display = 'block';

            var modalEl = document.getElementById('modalEstComprobante');
            modalEl.style.display = 'block';
            modalEl.classList.add('show');
            document.body.classList.add('modal-open');
            document.body.style.overflow = 'hidden';
            if (!document.getElementById('est-modal-backdrop')) {
                var bd = document.createElement('div');
                bd.id = 'est-modal-backdrop';
                bd.className = 'modal-backdrop fade show';
                document.body.appendChild(bd);
            }

            fetch('/virtual/inscripcion/' + inscripcionId + '/cuotas', { headers: { 'Accept': 'application/json' } })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    cuotasLoading.style.display = 'none';
                    if (!data.success) {
                        cuotasContainer.innerHTML = '<p class="text-muted" style="font-size:.82rem;">No se pudieron cargar las cuotas.</p>';
                        cuotasContainer.style.display = 'block';
                        return;
                    }
                    var grupo = data.grupo;
                    var html = '<div style="border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;">'
                        + '<div style="background:#f8fafc;padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;font-weight:600;font-size:.82rem;color:#475569;"><i class="ri-bank-card-line me-1"></i>'
                        + String(grupo.plan_nombre || '').replace(/</g,'&lt;')
                        + '</div><div style="padding:.75rem;">';

                    if (!grupo.cuotas.length) {
                        html += '<p style="color:#16a34a;font-size:.82rem;margin:0;"><i class="ri-checkbox-circle-line me-1"></i>Todas las cuotas están al día.</p>';
                    } else {
                        grupo.cuotas.forEach(function(c) {
                            var eColor = c.estado === 'Pagado' ? '#16a34a' : c.estado === 'Vencido' ? '#dc2626' : '#f59e0b';
                            html += '<label style="display:flex;align-items:center;gap:.75rem;padding:.5rem .25rem;border-bottom:1px solid #f8fafc;cursor:pointer;">'
                                + '<input type="checkbox" name="est_cuotas[]" value="' + c.id + '" style="width:15px;height:15px;accent-color:#fc7b04;flex-shrink:0;">'
                                + '<div style="flex:1;">'
                                + '<div style="font-size:.83rem;font-weight:500;color:#1e293b;">' + String(c.nombre||'').replace(/</g,'&lt;') + ' #' + c.n_cuota + '</div>'
                                + '<div style="font-size:.72rem;color:#64748b;">Bs ' + c.monto_bs + ' · Pendiente Bs ' + c.pago_pendiente_bs + ' · Vence: ' + (c.fecha_vencimiento || '—') + '</div>'
                                + '</div>'
                                + '<span style="font-size:.7rem;font-weight:600;color:' + eColor + ';background:' + eColor + '1a;padding:.15rem .45rem;border-radius:4px;">' + String(c.estado||'').replace(/</g,'&lt;') + '</span>'
                                + '</label>';
                        });
                    }
                    html += '</div></div>';
                    cuotasContainer.innerHTML = html;
                    cuotasContainer.style.display = 'block';
                })
                .catch(function() {
                    cuotasLoading.style.display = 'none';
                    cuotasContainer.innerHTML = '<p class="text-muted" style="font-size:.82rem;">Error al cargar cuotas.</p>';
                    cuotasContainer.style.display = 'block';
                });
        }

        function estCerrarModal() {
            var modalEl = document.getElementById('modalEstComprobante');
            if (!modalEl) return;
            modalEl.style.display = 'none';
            modalEl.classList.remove('show');
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            var bd = document.getElementById('est-modal-backdrop');
            if (bd) bd.remove();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Cerrar modal con botones Cancelar / X
            document.querySelectorAll('#modalEstComprobante [data-bs-dismiss="modal"], #modalEstComprobante .btn-close').forEach(function(btn) {
                btn.addEventListener('click', estCerrarModal);
            });

            var btnEstEnviar = document.getElementById('btnEstEnviarComprobante');
            if (!btnEstEnviar) return;
            btnEstEnviar.addEventListener('click', function() {
                if (!estCompInscripcionId) return;

                var archivo = document.getElementById('estCompArchivo').files[0];
                if (!archivo) { estMostrarToast('error', 'Debes seleccionar un archivo.'); return; }
                if (archivo.size > 5 * 1024 * 1024) { estMostrarToast('error', 'El archivo supera el límite de 5 MB.'); return; }

                var cuotasChecked = Array.from(document.querySelectorAll('#estCompCuotasContainer input[name="est_cuotas[]"]:checked'));
                if (!cuotasChecked.length) { estMostrarToast('error', 'Selecciona al menos una cuota.'); return; }

                var formData = new FormData();
                formData.append('_token', '<?php echo e(csrf_token()); ?>');
                formData.append('inscripcion_id', estCompInscripcionId);
                formData.append('archivo', archivo);
                formData.append('observaciones', document.getElementById('estCompObservaciones').value);
                cuotasChecked.forEach(function(cb) { formData.append('cuotas[]', cb.value); });

                btnEstEnviar.disabled = true;
                btnEstEnviar.innerHTML = '<i class="ri-loader-4-line me-1"></i>Enviando...';

                fetch('<?php echo e(route("virtual.comprobante.subir")); ?>', { method: 'POST', body: formData })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        btnEstEnviar.disabled = false;
                        btnEstEnviar.innerHTML = '<i class="ri-upload-cloud-line me-1"></i> Enviar Comprobante';
                        if (data.success) {
                            estCerrarModal();
                            estMostrarToast('success', data.mensaje || 'Comprobante enviado correctamente.');
                            setTimeout(function() { window.location.reload(); }, 1800);
                        } else {
                            estMostrarToast('error', data.message || 'Error al enviar el comprobante.');
                        }
                    })
                    .catch(function() {
                        btnEstEnviar.disabled = false;
                        btnEstEnviar.innerHTML = '<i class="ri-upload-cloud-line me-1"></i> Enviar Comprobante';
                        estMostrarToast('error', 'Error de conexión.');
                    });
            });
        });

        function cambiarPerfil(perfil) {
            // Feedback visual: spinner + deshabilitar ambos botones
            var btnEst = document.getElementById('rol-btn-estudiante');
            var btnDoc = document.getElementById('rol-btn-docente');
            var btnActivo = perfil === 'estudiante' ? btnEst : btnDoc;

            if (btnEst) btnEst.disabled = true;
            if (btnDoc) btnDoc.disabled = true;
            if (btnActivo) btnActivo.classList.add('loading');

            fetch('<?php echo e(route('virtual.cambiarPerfil')); ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ perfil: perfil })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(function() {
                // Restaurar si hay error de red
                if (btnEst) btnEst.disabled = false;
                if (btnDoc) btnDoc.disabled = false;
                if (btnActivo) btnActivo.classList.remove('loading');
            });
        }

        /* ── Mi Horario Docente — funciones cronograma style ───────── */

        window.cargarModulosHorarioDocente = function() {
            const ofertaId   = document.getElementById('select-oferta-horario-docente').value;
            const listaEl    = document.getElementById('modulosSidebarListHorarioDocente');
            const btnTodos   = document.getElementById('btnTodosModulosHorarioDocente');
            const badge      = document.getElementById('moduloSeleccionadoBadgeHorarioDocente');

            moduloSeleccionadoHorarioDocenteId = null;
            badge.style.display = 'none';
            btnTodos.classList.add('active');

            if (!ofertaId) {
                listaEl.innerHTML = '<div class="cronograma-sidebar-empty"><i class="ri-inbox-line"></i>Selecciona una oferta</div>';
                if (calendarioHorarioDocente) calendarioHorarioDocente.removeAllEvents();
                return;
            }

            const oferta = datosOfertasDocente.find(function(o) { return o.id == parseInt(ofertaId); });
            if (!oferta) return;

            listaEl.innerHTML = oferta.modulos.map(function(modulo) {
                var badgeMoodle = modulo.moodle_course_id
                    ? '<span style="background:rgba(21,101,192,.12);color:#1565c0;padding:1px 5px;border-radius:4px;font-size:.65rem;margin-left:4px;">Moodle</span>'
                    : '';
                return '<div class="cronograma-modulo-card" style="--mod-color:' + modulo.color + ';" ' +
                    'onclick="seleccionarModuloHorarioDocente(' + modulo.id + ', \'' + modulo.nombre.replace(/'/g, "\\'") + '\', \'' + modulo.color + '\', event)">' +
                    '<div class="cronograma-modulo-dot" style="background:' + modulo.color + '"></div>' +
                    '<div class="cronograma-modulo-info">' +
                        '<div class="cronograma-modulo-num">Módulo ' + modulo.numero + '</div>' +
                        '<div class="cronograma-modulo-name">' + modulo.nombre + badgeMoodle + '</div>' +
                    '</div>' +
                    '<div class="cronograma-modulo-badge">' + modulo.sesiones_count + ' ses.</div>' +
                    '</div>';
            }).join('');

            actualizarCalendarioHorarioDocente(oferta.modulos);
        };

        window.seleccionarModuloHorarioDocente = function(moduloId, moduloNombre, moduloColor, evt) {
            const badge    = document.getElementById('moduloSeleccionadoBadgeHorarioDocente');
            const btnTodos = document.getElementById('btnTodosModulosHorarioDocente');

            badge.style.display = 'flex';
            badge.querySelector('.dot').style.background = moduloColor;
            badge.querySelector('.modulo-badge-name').textContent = 'Módulo: ' + moduloNombre;
            btnTodos.classList.remove('active');

            document.querySelectorAll('#tab-horario-docente .cronograma-modulo-card')
                .forEach(function(el) { el.classList.remove('active'); });
            if (evt && evt.target) evt.target.closest('.cronograma-modulo-card').classList.add('active');

            moduloSeleccionadoHorarioDocenteId = moduloId;

            const ofertaId = document.getElementById('select-oferta-horario-docente').value;
            const oferta   = datosOfertasDocente.find(function(o) { return o.id == parseInt(ofertaId); });
            const modulo   = oferta.modulos.find(function(m) { return m.id == moduloId; });
            actualizarCalendarioHorarioDocente([modulo]);
        };

        window.verTodosModulosHorarioDocente = function() {
            const ofertaId = document.getElementById('select-oferta-horario-docente').value;
            const badge    = document.getElementById('moduloSeleccionadoBadgeHorarioDocente');
            const btnTodos = document.getElementById('btnTodosModulosHorarioDocente');

            moduloSeleccionadoHorarioDocenteId = null;
            badge.style.display = 'none';
            btnTodos.classList.add('active');
            document.querySelectorAll('#tab-horario-docente .cronograma-modulo-card')
                .forEach(function(el) { el.classList.remove('active'); });

            if (!ofertaId) return;
            const oferta = datosOfertasDocente.find(function(o) { return o.id == parseInt(ofertaId); });
            actualizarCalendarioHorarioDocente(oferta.modulos);
        };

        window.actualizarCalendarioHorarioDocente = function(modulos) {
            const eventos = [];
            modulos.forEach(function(modulo) {
                if (!modulo.sesiones || !modulo.sesiones.length) return;
                modulo.sesiones.forEach(function(sesion) {
                    const esPostergado = sesion.estado === 'Postergado';
                    eventos.push({
                        id:              sesion.id,
                        title:           sesion.titulo,
                        start:           sesion.start,
                        end:             sesion.end,
                        backgroundColor: esPostergado ? 'transparent' : modulo.color,
                        borderColor:     modulo.color,
                        textColor:       esPostergado ? modulo.color : '#fff',
                        extendedProps: {
                            modulo_nombre:               sesion.titulo,
                            modulo_color:                modulo.color,
                            estado:                      sesion.estado,
                            enlace_videollamada_url:      sesion.enlace_videollamada_url      || '',
                            enlace_videollamada_nombre:   sesion.enlace_videollamada_nombre   || '',
                            enlace_grabacion:             sesion.enlace_grabacion             || '',
                            reprogramado_de_fecha:        sesion.reprogramado_de_fecha        || null,
                            reprogramado_a_fecha:         sesion.reprogramado_a_fecha         || null,
                        }
                    });
                });
            });

            const calendarEl = document.getElementById('calendarHorarioDocente');
            if (!calendarEl) return;

            /* ── Navegar al evento más próximo (próxima o más reciente pasada) ── */
            function irAEventoProximo(evs) {
                if (!evs.length) return;
                const ahora = new Date();
                let objetivo = null, diffMin = Infinity;
                evs.forEach(function(ev) {
                    const d = new Date(ev.start);
                    const diff = d - ahora;
                    if (diff >= 0 && diff < diffMin) { diffMin = diff; objetivo = d; }
                });
                if (!objetivo) {
                    diffMin = Infinity;
                    evs.forEach(function(ev) {
                        const d = new Date(ev.start);
                        const diff = ahora - d;
                        if (diff < diffMin) { diffMin = diff; objetivo = d; }
                    });
                }
                if (objetivo) calendarioHorarioDocente.gotoDate(objetivo);
            }

            if (!calendarioHorarioDocente) {
                calendarioHorarioDocente = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left:   'prev,next today',
                        center: 'title',
                        right:  'dayGridMonth,timeGridWeek,listMonth'
                    },
                    locale: 'es',
                    buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana', list: 'Lista' },
                    editable:    false,
                    selectable:  false,
                    eventDisplay: 'block',
                    allDaySlot:  false,
                    slotMinTime: '07:00:00',
                    slotMaxTime: '21:00:00',
                    nowIndicator: true,
                    scrollTime:  '08:00:00',
                    slotDuration: '00:30:00',
                    eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
                    eventDidMount: function(info) {
                        const estado = info.event.extendedProps.estado;
                        const color  = info.event.extendedProps.modulo_color || info.event.backgroundColor;
                        const esList = info.el.classList.contains('fc-list-event');
                        if (estado === 'Postergado') {
                            info.el.classList.add('fc-event-postergado');
                            info.el.style.setProperty('border-color', color, 'important');
                            /* grid views */
                            const titleEl = info.el.querySelector('.fc-event-title');
                            if (titleEl) {
                                titleEl.style.setProperty('color', color, 'important');
                                if (!titleEl.querySelector('.ri-time-line')) {
                                    titleEl.innerHTML = '<i class="ri-time-line me-1" style="font-size:.8rem;vertical-align:middle;"></i>' + titleEl.innerHTML;
                                }
                            }
                            const timeEl = info.el.querySelector('.fc-event-time');
                            if (timeEl) timeEl.style.setProperty('color', color, 'important');
                            /* list view */
                            if (esList) {
                                const dotEl = info.el.querySelector('.fc-list-event-dot');
                                if (dotEl) dotEl.style.setProperty('border-color', color, 'important');
                                const listTitle = info.el.querySelector('.fc-list-event-title a');
                                if (listTitle) listTitle.style.setProperty('color', color, 'important');
                                const listTime  = info.el.querySelector('.fc-list-event-time');
                                if (listTime)  listTime.style.setProperty('color', '#94a3b8', 'important');
                            }
                        } else {
                            if (esList) {
                                const dotEl = info.el.querySelector('.fc-list-event-dot');
                                if (dotEl) dotEl.style.setProperty('border-color', color, 'important');
                                const listTitle = info.el.querySelector('.fc-list-event-title a');
                                if (listTitle) listTitle.style.setProperty('color', color, 'important');
                            } else {
                                info.el.style.setProperty('background-color', color, 'important');
                                info.el.style.setProperty('border-color',     color, 'important');
                                info.el.style.setProperty('color',            '#fff','important');
                            }
                        }
                    },
                    eventClick: function(info) {
                        abrirModalSesionEstudiante(info.event);
                    },
                    height: 'auto'
                });
                calendarioHorarioDocente.render();
                calendarioHorarioDocente.addEventSource(eventos);
                irAEventoProximo(eventos);
            } else {
                calendarioHorarioDocente.removeAllEventSources();
                calendarioHorarioDocente.addEventSource(eventos);
                irAEventoProximo(eventos);
            }
        };

        <?php if($esDocente && $perfilActivo === 'docente'): ?>
        document.addEventListener('DOMContentLoaded', function() {
            if (datosOfertasDocente && datosOfertasDocente.length > 0) {
                const sel = document.getElementById('select-oferta-horario-docente');
                if (sel) {
                    sel.value = datosOfertasDocente[0].id;
                    cargarModulosHorarioDocente();
                }
            }
        });
        <?php endif; ?>

        /* ══════════════════════════════════════════════════════════════════
           MODAL ACTIVIDADES ESTUDIANTE
        ══════════════════════════════════════════════════════════════════ */
        var _actModal = { cmid: null, modname: null, moduloId: null, name: null, discId: null };

        function abrirModalAct(cmid, modname, moduloId, name) {
            _actModal = { cmid: cmid, modname: modname, moduloId: moduloId, name: name, discId: null };

            var icons = { assign: 'ri-file-text-line', quiz: 'ri-question-line', forum: 'ri-discuss-line' };
            var colors = { assign: '#3b82f6', quiz: '#f97316', forum: '#8b5cf6' };
            var subtitles = { assign: 'Tarea', quiz: 'Cuestionario', forum: 'Foro de discusión' };

            document.getElementById('modal-act-icon').innerHTML =
                '<i class="' + (icons[modname] || 'ri-apps-line') + '" style="color:' + (colors[modname] || '#6c757d') + ';"></i>';
            document.getElementById('modal-act-title').textContent = name;
            document.getElementById('modal-act-subtitle').textContent = subtitles[modname] || '';
            document.getElementById('modal-act-body').innerHTML =
                '<div id="modal-act-loading" style="text-align:center;padding:2rem;color:#6c757d;">' +
                '<div class="spinner-border spinner-border-sm" style="color:#fc7b04;"></div>' +
                '<span style="margin-left:.5rem;font-size:.9rem;">Cargando…</span></div>';

            document.getElementById('modal-act-overlay').style.display = 'block';
            document.body.style.overflow = 'hidden';

            if (modname === 'assign') cargarTarea(cmid, moduloId);
            else if (modname === 'forum') cargarForo(cmid, moduloId);
            else if (modname === 'quiz') cargarQuiz(cmid, moduloId);
        }

        function cerrarModalAct() {
            document.getElementById('modal-act-overlay').style.display = 'none';
            document.body.style.overflow = '';
            _actModal = { cmid: null, modname: null, moduloId: null, name: null, discId: null };
        }

        document.getElementById('modal-act-overlay').addEventListener('click', function(e) {
            if (e.target === this) cerrarModalAct();
        });

        function actSetBody(html) {
            document.getElementById('modal-act-body').innerHTML = html;
        }

        function actErrHtml(msg) {
            return '<div style="text-align:center;padding:2rem;color:#dc2626;">' +
                '<i class="ri-close-circle-line" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>' +
                escHtml(msg) + '</div>';
        }

        /* ── helpers fecha ── */
        function fmtTs(ts) {
            if (!ts) return '—';
            var d = new Date(ts * 1000);
            return d.toLocaleDateString('es-BO', { day:'2-digit', month:'short', year:'numeric' }) +
                ' ' + d.toLocaleTimeString('es-BO', { hour:'2-digit', minute:'2-digit' });
        }
        function fmtDur(secs) {
            if (!secs) return 'Sin límite';
            var m = Math.floor(secs / 60);
            return m >= 60 ? Math.floor(m / 60) + 'h ' + (m % 60) + 'min' : m + ' min';
        }

        /* ══════════════════════════════════════════════════════════════════
           TAREA
        ══════════════════════════════════════════════════════════════════ */
        function cargarTarea(cmid, moduloId) {
            $.get('/virtual/modulo/' + moduloId + '/actividad/tarea/' + cmid)
            .done(function(r) {
                if (!r.success) { actSetBody(actErrHtml(r.message)); return; }
                renderTarea(r.data, cmid, moduloId);
            })
            .fail(function() { actSetBody(actErrHtml('No se pudo cargar la tarea.')); });
        }

        function renderTarea(data, cmid, moduloId) {
            var assign = data.assign;
            var sub    = data.submission;
            var hasText = sub && sub.onlinetext;
            var now = Math.floor(Date.now() / 1000);
            var pastCutoff = assign.cutoffdate && now > assign.cutoffdate;
            var canEdit = !pastCutoff && (!assign.nosubmissions);

            var descHtml = assign.intro
                ? '<div style="background:#f8f9fa;border-radius:8px;padding:1rem;margin-bottom:1rem;font-size:.85rem;color:#495057;">' + assign.intro + '</div>'
                : '';

            var dueBadge = '';
            if (assign.duedate) {
                var overdue = now > assign.duedate && !sub;
                dueBadge = '<span style="font-size:.78rem;padding:.2rem .6rem;border-radius:12px;background:' +
                    (overdue ? '#fee2e2' : '#fef9c3') + ';color:' + (overdue ? '#dc2626' : '#92400e') + ';font-weight:600;">' +
                    '<i class="ri-calendar-line"></i> Entrega: ' + fmtTs(assign.duedate) + '</span>';
            }

            var statusHtml = '';
            if (sub) {
                statusHtml = '<div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;padding:.75rem 1rem;background:#f0fdf4;border-radius:8px;border-left:3px solid #16a34a;">' +
                    '<i class="ri-checkbox-circle-line" style="color:#16a34a;font-size:1.1rem;"></i>' +
                    '<div><div style="font-weight:600;font-size:.85rem;color:#166534;">Entrega registrada</div>' +
                    '<div style="font-size:.75rem;color:#6c757d;">Última modificación: ' + fmtTs(sub.timemodified) + '</div></div></div>';

                // Mostrar calificación si existe
                if (assign.grade !== null && assign.grade !== undefined) {
                    statusHtml += '<div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;padding:.75rem 1rem;background:#f0fdf4;border-radius:8px;border-left:3px solid #16a34a;">' +
                        '<i class="ri-award-line" style="color:#16a34a;font-size:1.1rem;"></i>' +
                        '<div><div style="font-weight:600;font-size:.85rem;color:#166534;">Calificación</div>' +
                        '<div style="font-size:.85rem;font-weight:700;color:#16a34a;">' + assign.grade + '</div></div></div>';
                } else {
                    statusHtml += '<div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;padding:.75rem 1rem;background:#f8f9fa;border-radius:8px;border-left:3px solid #6c757d;">' +
                        '<i class="ri-hourglass-line" style="color:#6c757d;font-size:1.1rem;"></i>' +
                        '<div><div style="font-weight:600;font-size:.85rem;color:#6c757d;">Calificación</div>' +
                        '<div style="font-size:.82rem;color:#6c757d;">Sin calificación registrada</div></div></div>';
                }
            }

            var prevContent = '';
            if (sub) {
                if (hasText) {
                    prevContent += '<div style="margin-bottom:.75rem;">' +
                        '<div style="font-size:.78rem;font-weight:600;color:#6c757d;margin-bottom:.4rem;text-transform:uppercase;letter-spacing:.5px;">Texto entregado</div>' +
                        '<div style="background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;padding:.75rem;font-size:.85rem;max-height:120px;overflow-y:auto;">' +
                        sub.onlinetext + '</div></div>';
                }
                if (sub.files && sub.files.length > 0) {
                    prevContent += '<div style="margin-bottom:.75rem;"><div style="font-size:.78rem;font-weight:600;color:#6c757d;margin-bottom:.4rem;text-transform:uppercase;letter-spacing:.5px;">Archivos adjuntos</div>';
                    sub.files.forEach(function(f) {
                        var downloadUrl = '/virtual/modulo/' + moduloId + '/actividad/tarea/' + cmid + '/archivo/' + encodeURIComponent(f.filename);
                        prevContent += '<div style="display:flex;align-items:center;gap:.5rem;padding:.4rem .75rem;background:#f8f9fa;border-radius:6px;margin-bottom:.25rem;font-size:.82rem;">' +
                            '<i class="ri-file-line" style="color:#6c757d;"></i> ' + escHtml(f.filename) +
                            '<span style="margin-left:auto;color:#6c757d;">' + Math.round(f.filesize / 1024) + ' KB</span>' +
                            '<a href="' + downloadUrl + '" style="color:#3b82f6;font-size:1rem;flex-shrink:0;text-decoration:none;" title="Descargar" download><i class="ri-download-2-line"></i></a>' +
                            (canEdit ? '<span style="cursor:pointer;color:#dc2626;font-size:1rem;flex-shrink:0;" onclick="eliminarArchivoTarea(' + cmid + ',' + moduloId + ',\'' + escHtml(f.filename) + '\', this)" title="Eliminar archivo"><i class="ri-close-circle-line"></i></span>' : '') +
                            '</div>';
                    });
                    prevContent += '</div>';
                }
            }

            var cutoffMsg = '';
            if (pastCutoff) {
                cutoffMsg = '<div style="padding:.75rem 1rem;background:#fee2e2;border-radius:8px;border-left:3px solid #dc2626;margin-bottom:1rem;font-size:.85rem;color:#991b1b;">' +
                    '<i class="ri-timer-flash-line" style="margin-right:.4rem;"></i> La fecha límite de entrega (' + fmtTs(assign.cutoffdate) + ') ya pasó. No es posible modificar la entrega.</div>';
            } else if (assign.cutoffdate || assign.duedate) {
                var fechaTope = assign.cutoffdate || assign.duedate;
                cutoffMsg = '<div style="padding:.5rem .75rem;background:#fef9c3;border-radius:8px;margin-bottom:1rem;font-size:.8rem;color:#92400e;">' +
                    '<i class="ri-time-line" style="margin-right:.4rem;"></i> Puedes modificar tu entrega hasta: <strong>' + fmtTs(fechaTope) + '</strong></div>';
            }

            var html = descHtml;
            if (dueBadge) html += '<div style="margin-bottom:.75rem;">' + dueBadge + '</div>';
            html += statusHtml + cutoffMsg + prevContent;

            if (canEdit) {
                html += '<div style="font-size:.78rem;font-weight:600;color:#374151;margin-bottom:.5rem;text-transform:uppercase;letter-spacing:.5px;">' +
                    (sub ? 'Modificar entrega' : 'Realizar entrega') + '</div>';

                html += '<textarea id="tarea-txt" style="width:100%;min-height:120px;border:1.5px solid #dee2e6;border-radius:8px;padding:.75rem;font-size:.85rem;font-family:inherit;resize:vertical;box-sizing:border-box;"' +
                    ' placeholder="Escribe tu respuesta aquí (opcional si adjuntas archivo)…">' + (hasText ? escHtml(sub.onlinetext) : '') + '</textarea>';

                html += '<div style="margin-top:.75rem;display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">' +
                    '<button onclick="submitTareaTexto(' + cmid + ',' + moduloId + ', this)" ' +
                    'style="background:#fc7b04;color:#fff;border:none;border-radius:8px;padding:.6rem 1.25rem;font-weight:600;font-size:.85rem;cursor:pointer;display:flex;align-items:center;gap:.4rem;">' +
                    '<i class="ri-send-plane-line"></i> ' + (sub ? 'Guardar cambios' : 'Entregar ahora') + '</button>' +
                    '<label style="cursor:pointer;display:flex;align-items:center;gap:.4rem;background:#f3f4f6;border:1.5px dashed #d1d5db;border-radius:8px;padding:.5rem 1rem;font-size:.82rem;font-weight:500;color:#374151;">' +
                    '<i class="ri-attachment-line"></i> Subir archivo' +
                    '<input type="file" id="tarea-file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.jpg,.jpeg,.png,.txt" style="display:none;" onchange="uploadTareaFile(' + cmid + ',' + moduloId + ',this)"></label>' +
                    '</div>';
                html += '<div id="tarea-file-name" style="font-size:.78rem;color:#6c757d;margin-top:.4rem;"></div>';
                html += '<div id="tarea-msg" style="margin-top:.75rem;"></div>';
            }

            actSetBody(html);
        }

        function submitTareaTexto(cmid, moduloId) {
            var text = document.getElementById('tarea-txt').value.trim();
            var btn = event.currentTarget;
            var esActualizacion = btn.textContent.includes('Guardar');
            btn.disabled = true;
            btn.innerHTML = '<i class="ri-loader-4-line"></i> Enviando…';

            $.ajax({
                url:  '/virtual/modulo/' + moduloId + '/actividad/tarea/' + cmid + '/submit',
                type: 'POST',
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: JSON.stringify({ text: text || '' }),
            })
            .done(function(r) {
                btn.disabled = false;
                if (r.success) {
                    btn.innerHTML = '<i class="ri-check-line"></i> Guardado';
                    btn.style.background = '#16a34a';
                    estMostrarToast('success', r.message);
                    setTimeout(function() { location.reload(); }, 800);
                } else {
                    btn.innerHTML = '<i class="ri-send-plane-line"></i> ' + (esActualizacion ? 'Guardar cambios' : 'Entregar ahora');
                    estMostrarToast('error', r.message || 'Error al entregar.');
                }
            })
            .fail(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-send-plane-line"></i> ' + (esActualizacion ? 'Guardar cambios' : 'Entregar ahora');
                estMostrarToast('error', 'Error de conexión.');
            });
        }

        function uploadTareaFile(cmid, moduloId, input) {
            if (!input.files[0]) return;
            var file = input.files[0];
            document.getElementById('tarea-file-name').textContent = 'Adjuntando: ' + file.name + '…';

            var fd = new FormData();
            fd.append('archivo', file);
            fd.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url:         '/virtual/modulo/' + moduloId + '/actividad/tarea/' + cmid + '/archivo',
                type:        'POST',
                data:        fd,
                processData: false,
                contentType: false,
            })
            .done(function(r) {
                if (r.success) {
                    document.getElementById('tarea-file-name').textContent = '✓ ' + file.name + ' adjuntado.';
                    estMostrarToast('success', r.message);
                    setTimeout(function() { cargarTarea(cmid, moduloId); }, 800);
                } else {
                    document.getElementById('tarea-file-name').textContent = '';
                    estMostrarToast('error', r.message || 'No se pudo adjuntar el archivo.');
                }
            })
            .fail(function() {
                document.getElementById('tarea-file-name').textContent = '';
                estMostrarToast('error', 'Error al subir el archivo.');
            });
        }

        function eliminarArchivoTarea(cmid, moduloId, filename, el) {
            if (!confirm('¿Eliminar "' + filename + '"?')) return;
            el.closest('div').style.opacity = '.4';
            $.ajax({
                url:  '/virtual/modulo/' + moduloId + '/actividad/tarea/' + cmid + '/archivo',
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: { filename: filename },
            })
            .done(function(r) {
                if (r.success) {
                    estMostrarToast('success', r.message);
                    cargarTarea(cmid, moduloId);
                } else {
                    estMostrarToast('error', r.message || 'Error al eliminar.');
                }
            })
            .fail(function() {
                estMostrarToast('error', 'Error de conexión.');
            });
        }

        /* ══════════════════════════════════════════════════════════════════
           FORO
        ══════════════════════════════════════════════════════════════════ */
        function cargarForo(cmid, moduloId) {
            $.get('/virtual/modulo/' + moduloId + '/actividad/foro/' + cmid)
            .done(function(r) {
                if (!r.success) { actSetBody(actErrHtml(r.message)); return; }
                renderForoLista(r.data, cmid, moduloId);
            })
            .fail(function() { actSetBody(actErrHtml('No se pudo cargar el foro.')); });
        }

        function renderForoLista(discusiones, cmid, moduloId) {
            var html = '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">' +
                '<span style="font-size:.85rem;color:#6c757d;">' + discusiones.length + ' discusión(es)</span>' +
                '<button onclick="mostrarFormNuevaDisc(' + cmid + ',' + moduloId + ')" ' +
                'style="background:#fc7b04;color:#fff;border:none;border-radius:8px;padding:.5rem 1rem;font-size:.82rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:.4rem;">' +
                '<i class="ri-add-line"></i> Nueva discusión</button></div>';

            html += '<div id="foro-form-nueva" style="display:none;background:#f8f9fa;border-radius:10px;padding:1rem;margin-bottom:1rem;border:1.5px solid #dee2e6;"></div>';

            if (discusiones.length === 0) {
                html += '<div style="text-align:center;padding:2rem;color:#6c757d;">' +
                    '<i class="ri-discuss-line" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>' +
                    'Aún no hay discusiones. ¡Sé el primero en participar!</div>';
            } else {
                html += '<div id="foro-lista">';
                discusiones.forEach(function(d) {
                    html += '<div class="foro-disc-card" style="border:1px solid #e9ecef;border-radius:10px;padding:1rem;margin-bottom:.75rem;cursor:pointer;transition:box-shadow .15s;"' +
                        ' onclick="abrirDiscusion(' + cmid + ',' + moduloId + ',' + d.id + ',\'' + escHtml(d.name) + '\')">' +
                        '<div style="display:flex;justify-content:space-between;align-items:flex-start;gap:.5rem;">' +
                        '<div style="flex:1;min-width:0;">' +
                        '<div style="font-weight:600;font-size:.88rem;color:#2c3e50;margin-bottom:.25rem;">' + escHtml(d.name) + '</div>' +
                        '<div style="font-size:.78rem;color:#6c757d;">' + escHtml(d.firstmessage) + '</div></div>' +
                        '<div style="text-align:right;flex-shrink:0;">' +
                        '<div style="font-size:.72rem;color:#6c757d;">' + fmtTs(d.timemodified) + '</div>' +
                        '<div style="font-size:.72rem;color:#8b5cf6;font-weight:600;margin-top:.2rem;">' +
                        '<i class="ri-chat-3-line"></i> ' + d.replies + ' respuesta(s)</div></div></div>' +
                        '<div style="font-size:.72rem;color:#374151;margin-top:.4rem;">' +
                        '<i class="ri-user-line"></i> ' + escHtml(d.author) +
                        (d.is_mine ? ' <span style="color:#fc7b04;font-weight:600;">(Tú)</span>' : '') + '</div></div>';
                });
                html += '</div>';
            }

            actSetBody(html);
        }

        function mostrarFormNuevaDisc(cmid, moduloId) {
            var form = document.getElementById('foro-form-nueva');
            if (!form) return;
            form.style.display = 'block';
            form.innerHTML =
                '<div style="font-weight:600;font-size:.88rem;color:#2c3e50;margin-bottom:.75rem;"><i class="ri-add-circle-line"></i> Nueva discusión</div>' +
                '<input id="disc-asunto" type="text" placeholder="Asunto de la discusión *" maxlength="255" ' +
                'style="width:100%;border:1.5px solid #dee2e6;border-radius:8px;padding:.6rem .8rem;font-size:.85rem;margin-bottom:.6rem;box-sizing:border-box;">' +
                '<textarea id="disc-msg" rows="4" placeholder="Escribe tu mensaje *" ' +
                'style="width:100%;border:1.5px solid #dee2e6;border-radius:8px;padding:.6rem .8rem;font-size:.85rem;resize:vertical;box-sizing:border-box;"></textarea>' +
                '<div style="display:flex;gap:.5rem;margin-top:.6rem;">' +
                '<button onclick="submitNuevaDisc(' + cmid + ',' + moduloId + ')" ' +
                'style="background:#fc7b04;color:#fff;border:none;border-radius:8px;padding:.5rem 1rem;font-size:.82rem;font-weight:600;cursor:pointer;">' +
                '<i class="ri-send-plane-line"></i> Publicar</button>' +
                '<button onclick="document.getElementById(\'foro-form-nueva\').style.display=\'none\'" ' +
                'style="background:#f3f4f6;color:#374151;border:1px solid #d1d5db;border-radius:8px;padding:.5rem 1rem;font-size:.82rem;cursor:pointer;">Cancelar</button>' +
                '</div>';
        }

        function submitNuevaDisc(cmid, moduloId) {
            var asunto = (document.getElementById('disc-asunto').value || '').trim();
            var msg    = (document.getElementById('disc-msg').value || '').trim();
            if (!asunto || !msg) { estMostrarToast('error', 'Completa el asunto y el mensaje.'); return; }

            $.ajax({
                url:  '/virtual/modulo/' + moduloId + '/actividad/foro/' + cmid + '/discusion',
                type: 'POST',
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: JSON.stringify({ subject: asunto, message: msg }),
            })
            .done(function(r) {
                if (r.success) {
                    estMostrarToast('success', 'Discusión creada correctamente.');
                    cargarForo(cmid, moduloId);
                } else {
                    estMostrarToast('error', r.message || 'Error al crear la discusión.');
                }
            })
            .fail(function() { estMostrarToast('error', 'Error de conexión.'); });
        }

        function abrirDiscusion(cmid, moduloId, discId, nombre) {
            _actModal.discId = discId;
            actSetBody('<div style="text-align:center;padding:2rem;color:#6c757d;"><div class="spinner-border spinner-border-sm"></div> Cargando mensajes…</div>');

            $.get('/virtual/modulo/' + moduloId + '/actividad/foro/' + cmid + '/discusion/' + discId)
            .done(function(r) {
                if (!r.success) { actSetBody(actErrHtml(r.message)); return; }
                renderDiscusionPosts(r, cmid, moduloId);
            })
            .fail(function() { actSetBody(actErrHtml('Error al cargar los mensajes.')); });
        }

        function renderDiscusionPosts(r, cmid, moduloId) {
            var disc  = r.discussion;
            var posts = r.posts || [];
            var myUid = r.my_user_id;

            var html = '<div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;">' +
                '<button onclick="cargarForo(' + cmid + ',' + moduloId + ')" ' +
                'style="background:#f3f4f6;border:1px solid #d1d5db;border-radius:6px;padding:.3rem .7rem;font-size:.8rem;cursor:pointer;">' +
                '<i class="ri-arrow-left-line"></i> Volver</button>' +
                '<span style="font-weight:600;font-size:.9rem;color:#2c3e50;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + escHtml(disc.name) + '</span></div>';

            posts.forEach(function(p) {
                var isMe = p.userid === myUid;
                var isRoot = p.parent === 0;
                html += '<div style="margin-bottom:.75rem;' + (isRoot ? '' : 'margin-left:2rem;border-left:3px solid #e9ecef;padding-left:.75rem;') + '">' +
                    '<div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">' +
                    '<div style="width:28px;height:28px;border-radius:50%;background:' + (isMe ? '#fc7b04' : '#8b5cf6') + ';display:flex;align-items:center;justify-content:center;color:#fff;font-size:.72rem;font-weight:700;flex-shrink:0;">' +
                    escHtml(p.author.charAt(0).toUpperCase()) + '</div>' +
                    '<div><span style="font-weight:600;font-size:.82rem;">' + escHtml(p.author) + (isMe ? ' <span style="color:#fc7b04;">(Tú)</span>' : '') + '</span>' +
                    '<span style="font-size:.72rem;color:#6c757d;margin-left:.4rem;">' + fmtTs(p.created) + '</span></div>' +
                    '<button onclick="mostrarFormReply(' + cmid + ',' + moduloId + ',' + disc.id + ',' + p.id + ',\'' + escHtml(disc.name) + '\')" ' +
                    'style="margin-left:auto;background:none;border:1px solid #d1d5db;border-radius:6px;padding:.2rem .5rem;font-size:.72rem;cursor:pointer;color:#6c757d;">' +
                    '<i class="ri-reply-line"></i> Responder</button></div>' +
                    '<div style="font-size:.85rem;color:#374151;line-height:1.5;">' + p.message + '</div>' +
                    '<div id="form-reply-' + p.id + '" style="display:none;margin-top:.5rem;"></div>' +
                    '</div>';
            });

            html += '<div style="margin-top:1.25rem;padding-top:1rem;border-top:1px solid #e9ecef;">' +
                '<div style="font-weight:600;font-size:.85rem;color:#374151;margin-bottom:.5rem;"><i class="ri-message-line"></i> Responder en esta discusión</div>' +
                '<textarea id="reply-main" rows="3" placeholder="Escribe tu respuesta…" ' +
                'style="width:100%;border:1.5px solid #dee2e6;border-radius:8px;padding:.6rem .8rem;font-size:.85rem;resize:vertical;box-sizing:border-box;"></textarea>' +
                '<button onclick="submitReply(' + cmid + ',' + moduloId + ',' + disc.id + ',' + (posts.length > 0 ? posts[0].id : 0) + ',\'' + escHtml(disc.name) + '\',\'reply-main\')" ' +
                'style="margin-top:.5rem;background:#8b5cf6;color:#fff;border:none;border-radius:8px;padding:.5rem 1rem;font-size:.82rem;font-weight:600;cursor:pointer;">' +
                '<i class="ri-send-plane-line"></i> Publicar respuesta</button></div>';

            actSetBody(html);
        }

        function mostrarFormReply(cmid, moduloId, discId, parentId, discName) {
            var formId = 'form-reply-' + parentId;
            var form = document.getElementById(formId);
            if (!form) return;
            if (form.style.display === 'block') { form.style.display = 'none'; return; }
            form.style.display = 'block';
            form.innerHTML =
                '<textarea id="reply-inline-' + parentId + '" rows="3" placeholder="Tu respuesta…" ' +
                'style="width:100%;border:1.5px solid #dee2e6;border-radius:8px;padding:.5rem .7rem;font-size:.82rem;resize:vertical;box-sizing:border-box;"></textarea>' +
                '<div style="display:flex;gap:.4rem;margin-top:.4rem;">' +
                '<button onclick="submitReply(' + cmid + ',' + moduloId + ',' + discId + ',' + parentId + ',\'' + escHtml(discName) + '\',\'reply-inline-' + parentId + '\')" ' +
                'style="background:#8b5cf6;color:#fff;border:none;border-radius:6px;padding:.35rem .8rem;font-size:.78rem;font-weight:600;cursor:pointer;">' +
                '<i class="ri-send-plane-line"></i> Enviar</button>' +
                '<button onclick="document.getElementById(\'' + formId + '\').style.display=\'none\'" ' +
                'style="background:#f3f4f6;border:1px solid #d1d5db;border-radius:6px;padding:.35rem .8rem;font-size:.78rem;cursor:pointer;">Cancelar</button></div>';
        }

        function submitReply(cmid, moduloId, discId, parentId, discName, textareaId) {
            var msg = (document.getElementById(textareaId).value || '').trim();
            if (!msg) { estMostrarToast('error', 'Escribe un mensaje antes de responder.'); return; }

            $.ajax({
                url:  '/virtual/modulo/' + moduloId + '/actividad/foro/' + cmid + '/discusion/' + discId + '/reply',
                type: 'POST',
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: JSON.stringify({ message: msg, parent_id: parentId }),
            })
            .done(function(r) {
                if (r.success) {
                    estMostrarToast('success', 'Respuesta publicada.');
                    abrirDiscusion(cmid, moduloId, discId, discName);
                } else {
                    estMostrarToast('error', r.message || 'Error al publicar.');
                }
            })
            .fail(function() { estMostrarToast('error', 'Error de conexión.'); });
        }

        /* ══════════════════════════════════════════════════════════════════
           CUESTIONARIO
        ══════════════════════════════════════════════════════════════════ */
        var _quizTimer = { end: 0, interval: null, timelimit: 0 };

        function detenerTimer() {
            if (_quizTimer.interval) { clearInterval(_quizTimer.interval); _quizTimer.interval = null; }
        }

        function cargarQuiz(cmid, moduloId) {
            detenerTimer();
            $.get('/virtual/modulo/' + moduloId + '/actividad/quiz/' + cmid)
            .done(function(r) {
                if (!r.success) { actSetBody(actErrHtml(r.message)); return; }
                renderQuiz(r, cmid, moduloId);
            })
            .fail(function() { actSetBody(actErrHtml('No se pudo cargar el cuestionario.')); });
        }

        function renderQuiz(r, cmid, moduloId) {
            detenerTimer();
            var q        = r.data.quiz;
            var attempts = r.data.student_attempts || [];
            var maxReached = r.data.max_attempts_reached;
            var inProgress = r.data.has_inprogress;
            _quizTimer.timelimit = q.timelimit || 0;

            var html = '';

            if (q.intro) {
                html += '<div style="background:#f8f9fa;border-radius:12px;padding:1rem;margin-bottom:1rem;font-size:.85rem;color:#495057;line-height:1.6;">' + q.intro + '</div>';
            }

            html += '<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:.75rem;margin-bottom:1.25rem;">';
            html += infoCard('<i class="ri-time-line"></i>', 'Tiempo límite', fmtDur(q.timelimit));
            html += infoCard('<i class="ri-repeat-line"></i>', 'Intentos permitidos', q.attempts === 0 ? 'Ilimitados' : q.attempts);
            html += infoCard('<i class="ri-star-line"></i>', 'Calificación máx.', q.grade ? q.grade + ' pts' : '—');
            if (q.timeopen) html += infoCard('<i class="ri-calendar-check-line"></i>', 'Disponible desde', fmtTs(q.timeopen));
            if (q.timeclose) html += infoCard('<i class="ri-calendar-close-line"></i>', 'Cierra', fmtTs(q.timeclose));
            html += '</div>';

            if (attempts.length > 0) {
                html += '<div style="margin-bottom:1.25rem;">';
                html += '<div style="font-weight:600;font-size:.82rem;color:#64748b;margin-bottom:.5rem;text-transform:uppercase;letter-spacing:.5px;">Tus intentos</div>';
                html += '<div style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">';
                html += '<table style="width:100%;border-collapse:collapse;font-size:.82rem;">';
                html += '<thead><tr style="background:#f8fafc;">' +
                    '<th style="padding:.55rem .85rem;text-align:left;color:#64748b;font-weight:600;">Intento</th>' +
                    '<th style="padding:.55rem .85rem;text-align:left;color:#64748b;font-weight:600;">Inicio</th>' +
                    '<th style="padding:.55rem .85rem;text-align:left;color:#64748b;font-weight:600;">Fin</th>' +
                    '<th style="padding:.55rem .85rem;text-align:right;color:#64748b;font-weight:600;">Nota</th></tr></thead><tbody>';
                attempts.forEach(function(a) {
                    var passColor = a.passed ? '#16a34a' : '#dc2626';
                    html += '<tr style="border-top:1px solid #f1f5f9;">' +
                        '<td style="padding:.55rem .85rem;font-weight:600;">#' + a.attempt + '</td>' +
                        '<td style="padding:.55rem .85rem;">' + fmtTs(a.timestart) + '</td>' +
                        '<td style="padding:.55rem .85rem;">' + fmtTs(a.timefinish) + '</td>' +
                        '<td style="padding:.55rem .85rem;text-align:right;font-weight:700;color:' + passColor + ';">' +
                        (a.grade !== null ? a.grade + ' pts' : '—') + '</td></tr>';
                });
                html += '</tbody></table></div></div>';
            } else {
                html += '<div style="text-align:center;padding:1.5rem;background:#f8fafc;border-radius:12px;margin-bottom:1.25rem;color:#64748b;font-size:.85rem;">' +
                    '<i class="ri-information-line" style="font-size:1.5rem;display:block;margin-bottom:.4rem;color:#94a3b8;"></i>' +
                    'Aún no has realizado ningún intento.</div>';
            }

            if (inProgress) {
                html += '<div style="background:#fef9c3;border-radius:10px;padding:.75rem 1rem;margin-bottom:.75rem;font-size:.85rem;color:#92400e;font-weight:500;display:flex;align-items:center;gap:.5rem;">' +
                    '<i class="ri-alert-line"></i> Tienes un intento en progreso.' +
                    '<button onclick="cargarQuizActivo(' + cmid + ',' + moduloId + ')" style="background:#fc7b04;color:#fff;border:none;border-radius:6px;padding:.25rem .75rem;font-size:.78rem;font-weight:600;cursor:pointer;white-space:nowrap;">Continuar aquí</button></div>';
            }

            if (!maxReached) {
                var btnFn = inProgress ? 'cargarQuizActivo' : 'iniciarQuiz';
                html += '<div style="text-align:center;margin-top:1rem;">' +
                    '<button onclick="' + btnFn + '(' + cmid + ',' + moduloId + ')" ' +
                    'style="background:linear-gradient(135deg,#fc7b04,#e06a00);color:#fff;border:none;border-radius:12px;padding:.85rem 2.5rem;font-size:.9rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:.6rem;box-shadow:0 4px 15px rgba(252,123,4,.3);transition:transform .15s,box-shadow .15s;">' +
                    '<i class="ri-play-circle-line" style="font-size:1.1rem;"></i> ' + (inProgress ? 'Continuar intento' : 'Comenzar cuestionario') + '</button></div>';
            } else {
                html += '<div style="text-align:center;margin-top:1rem;padding:.85rem;background:#f8fafc;border-radius:10px;color:#64748b;font-size:.85rem;">' +
                    '<i class="ri-information-line" style="margin-right:.3rem;"></i> Has alcanzado el número máximo de intentos.</div>';
            }

            actSetBody(html);
        }

        function iniciarQuiz(cmid, moduloId) {
            actSetBody('<div style="text-align:center;padding:2rem;color:#6c757d;">' +
                '<div class="spinner-border spinner-border-sm" style="color:#fc7b04;"></div>' +
                '<span style="margin-left:.5rem;font-size:.9rem;">Preparando cuestionario…</span></div>');

            var token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url:  '/virtual/modulo/' + moduloId + '/actividad/quiz/' + cmid + '/start',
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': token },
            })
            .done(function(r) {
                if (!r.success) { actSetBody(actErrHtml(r.message)); return; }
                var attemptId = r.attempt.id;
                var timestart = r.timestart || Math.floor(Date.now() / 1000);
                cargarPreguntasQuiz(attemptId, cmid, moduloId, timestart);
            })
            .fail(function() { actSetBody(actErrHtml('No se pudo iniciar el cuestionario.')); });
        }

        function cargarQuizActivo(cmid, moduloId) {
            actSetBody('<div style="text-align:center;padding:2rem;color:#6c757d;">' +
                '<div class="spinner-border spinner-border-sm" style="color:#fc7b04;"></div>' +
                '<span style="margin-left:.5rem;font-size:.9rem;">Cargando intento activo…</span></div>');

            // Fetch attempt timestart directly via a dedicated call
            cargarPreguntasQuiz(null, cmid, moduloId, null, true);
        }

        function cargarPreguntasQuiz(attemptId, cmid, moduloId, timestart, isActive) {
            if (isActive) {
                // For active/continuing quizzes, first fetch quiz data to get the in-progress attempt ID and timelimit
                $.get('/virtual/modulo/' + moduloId + '/actividad/quiz/' + cmid)
                .done(function(r) {
                    if (!r.success) { actSetBody(actErrHtml(r.message)); return; }
                    if (!r.data.has_inprogress || !r.data.inprogress_attempt_id) {
                        renderQuiz(r, cmid, moduloId);
                        return;
                    }
                    _quizTimer.timelimit = r.data.quiz.timelimit || 0;
                    var aid = r.data.inprogress_attempt_id;
                    // Now fetch questions with attempt info
                    $.get('/virtual/modulo/' + moduloId + '/actividad/quiz/' + cmid + '/attempt/' + aid)
                    .done(function(resp) {
                        if (!resp.success) { actSetBody(actErrHtml(resp.message)); return; }
                        var ts = (resp.attempt && resp.attempt.timestart) ? resp.attempt.timestart : Math.floor(Date.now() / 1000);
                        renderQuizPreguntas(resp.questions, aid, cmid, moduloId, ts);
                    })
                    .fail(function() { actSetBody(actErrHtml('No se pudieron cargar las preguntas.')); });
                })
                .fail(function() { actSetBody(actErrHtml('Error al cargar el cuestionario.')); });
                return;
            }

            $.get('/virtual/modulo/' + moduloId + '/actividad/quiz/' + cmid + '/attempt/' + attemptId)
            .done(function(r) {
                if (!r.success) { actSetBody(actErrHtml(r.message)); return; }
                var ts = timestart || (r.attempt && r.attempt.timestart) || Math.floor(Date.now() / 1000);
                renderQuizPreguntas(r.questions, attemptId, cmid, moduloId, ts);
            })
            .fail(function() { actSetBody(actErrHtml('No se pudieron cargar las preguntas.')); });
        }

        function limpiarHtmlPregunta(html) {
            if (!html) return '';
            var el = document.createElement('div');
            el.innerHTML = html;
            // Remove flag/mark question elements
            el.querySelectorAll('.questionflag, .questionflagimage, .flag, .flagtrips, [aria-label*="Marcar"], [aria-label*="Flag"], a[href*="flagquestion"]').forEach(function(e) { e.remove(); });
            // Remove status/grade badges
            el.querySelectorAll('.state, .questionstatus, .status, .grade, .grading, .outcome, .questionstatusspan').forEach(function(e) { e.remove(); });
            // Remove elements whose text is purely "Sin responder" / "Not answered" / empty (but keep inputs/labels)
            el.querySelectorAll('span, div, small, td').forEach(function(e) {
                var txt = (e.textContent || '').trim().toLowerCase();
                if (['sin responder', 'not answered', 'marcar pregunta', 'flag question', '', 'respondido'].indexOf(txt) !== -1) {
                    if (!e.querySelector('input, select, textarea, label, img, a')) e.remove();
                }
                // Also remove elements with just an icon and no useful text
                if (e.children.length === 1 && e.children[0].tagName === 'I' && (!e.textContent.trim() || e.textContent.trim().length < 3)) {
                    e.remove();
                }
            });
            return el.innerHTML;
        }

        function renderQuizPreguntas(questions, attemptId, cmid, moduloId, timestart) {
            var totalQ = questions.length;
            var html = '';

            // Timer header — uses server-side timestart so it persists across refreshes
            if (_quizTimer.timelimit > 0 && timestart) {
                _quizTimer.end = timestart + _quizTimer.timelimit;
                var now = Math.floor(Date.now() / 1000);
                var remaining = Math.max(0, _quizTimer.end - now);
                html += '<div id="quiz-timer-bar" style="display:flex;align-items:center;justify-content:space-between;gap:.75rem;background:linear-gradient(135deg,#1e293b,#334155);border-radius:12px;padding:.65rem 1rem;margin-bottom:1rem;color:#fff;">' +
                    '<div style="display:flex;align-items:center;gap:.5rem;">' +
                    '<i class="ri-timer-line" style="font-size:1.1rem;"></i>' +
                    '<span style="font-size:.82rem;font-weight:600;">Tiempo restante:</span>' +
                    '</div>' +
                    '<div id="quiz-timer-display" style="font-size:1.15rem;font-weight:800;font-variant-numeric:tabular-nums;letter-spacing:1px;font-family:monospace;">' + fmtCountdown(remaining) + '</div>' +
                    '</div>';
            }

            html += '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">' +
                '<span style="font-size:.82rem;color:#64748b;font-weight:500;"><i class="ri-list-check"></i> ' + totalQ + ' preguntas</span>' +
                '<span id="quiz-progress-text" style="font-size:.78rem;color:#94a3b8;font-weight:500;">0/' + totalQ + ' respondidas</span>' +
                '</div>';

            html += '<div id="quiz-preguntas-wrap">';

            questions.forEach(function(q, idx) {
                var num = idx + 1;
                var maxMark = q.maxmark || 0;

                html += '<div class="quiz-pregunta" data-qidx="' + idx + '" style="background:#fff;border:1.5px solid #e2e8f0;border-radius:12px;padding:1.15rem 1.25rem;margin-bottom:.9rem;transition:border-color .2s,box-shadow .2s;">' +
                    '<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:.5rem;margin-bottom:.6rem;">' +
                    '<div style="font-weight:700;font-size:.85rem;color:#0f172a;display:flex;align-items:center;gap:.5rem;line-height:1.3;">' +
                    '<span class="quiz-numero" style="background:#fc7b04;color:#fff;border-radius:50%;width:26px;height:26px;display:inline-flex;align-items:center;justify-content:center;font-size:.72rem;flex-shrink:0;">' + num + '</span>' +
                    escHtml(q.questionname || 'Pregunta ' + num) + '</div>' +
                    (maxMark > 0 ? '<span style="font-size:.7rem;color:#94a3b8;white-space:nowrap;font-weight:600;background:#f1f5f9;padding:.15rem .55rem;border-radius:6px;">' + maxMark + ' pts</span>' : '') +
                    '</div>';

                if (q.html) {
                    var cleaned = limpiarHtmlPregunta(q.html);
                    html += '<div class="quiz-pregunta-html" data-slot="' + q.slot + '" data-seq="' + (q.sequencecheck || '') + '">' + cleaned + '</div>';
                }

                html += '</div>';
            });

            html += '</div>';

            html += '<div id="quiz-msg" style="margin-bottom:.75rem;"></div>';

            html += '<div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap;border-top:1px solid #e2e8f0;padding-top:1.15rem;margin-top:.5rem;">' +
                '<button onclick="guardarQuiz(' + attemptId + ',' + cmid + ',' + moduloId + ',false)" ' +
                'style="background:#f1f5f9;color:#334155;border:1.5px solid #cbd5e1;border-radius:10px;padding:.65rem 1.35rem;font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:.45rem;transition:background .15s;">' +
                '<i class="ri-save-line" style="font-size:1rem;"></i> Guardar respuestas</button>' +
                '<button onclick="guardarQuiz(' + attemptId + ',' + cmid + ',' + moduloId + ',true)" ' +
                'style="background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;border:none;border-radius:10px;padding:.65rem 1.35rem;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:.45rem;box-shadow:0 3px 10px rgba(22,163,74,.25);transition:transform .15s,box-shadow .15s;">' +
                '<i class="ri-check-double-line" style="font-size:1rem;"></i> Finalizar intento</button>' +
                '</div>';

            actSetBody(html);

            // Start timer based on server-side timestart
            if (_quizTimer.timelimit > 0 && timestart) {
                iniciarTimer(attemptId, cmid, moduloId);
            }

            // Track answered questions
            actualizarContadorRespuestas();
            $(document).on('change', '.quiz-pregunta-html input, .quiz-pregunta-html select, .quiz-pregunta-html textarea', function() {
                actualizarContadorRespuestas();
            });
        }

        function fmtCountdown(secs) {
            if (secs <= 0) return '00:00';
            var h = Math.floor(secs / 3600);
            var m = Math.floor((secs % 3600) / 60);
            var s = secs % 60;
            if (h > 0) return String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
            return String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
        }

        function iniciarTimer(attemptId, cmid, moduloId) {
            detenerTimer();
            _quizTimer.interval = setInterval(function() {
                var now = Math.floor(Date.now() / 1000);
                var left = Math.max(0, _quizTimer.end - now);
                var $disp = $('#quiz-timer-display');
                var $bar = $('#quiz-timer-bar');

                if ($disp.length) {
                    $disp.text(fmtCountdown(left));
                }

                // Warning states
                if ($bar.length) {
                    if (left <= 60) {
                        $bar.css('background', 'linear-gradient(135deg,#991b1b,#7f1d1d)');
                        $disp.css('color', '#fca5a5');
                    } else if (left <= 300) {
                        $bar.css('background', 'linear-gradient(135deg,#92400e,#78350f)');
                        $disp.css('color', '#fde68a');
                    }
                }

                if (left <= 0) {
                    detenerTimer();
                    if ($bar.length) {
                        $bar.html('<div style="display:flex;align-items:center;gap:.5rem;color:#fff;"><i class="ri-timer-flash-line" style="font-size:1.1rem;"></i><span style="font-weight:700;">Tiempo agotado — finalizando intento…</span></div>');
                    }
                    guardarQuiz(attemptId, cmid, moduloId, true);
                }
            }, 1000);
        }

        function actualizarContadorRespuestas() {
            var total = $('.quiz-pregunta').length;
            var respondidas = 0;
            $('.quiz-pregunta').each(function() {
                var hasVal = false;
                $(this).find('.quiz-pregunta-html input, .quiz-pregunta-html select, .quiz-pregunta-html textarea').each(function() {
                    var $el = $(this);
                    if ($el.is(':checkbox') || $el.is(':radio')) {
                        if ($el.prop('checked')) { hasVal = true; return false; }
                    } else if ($el.is('select')) {
                        if ($el.val() && $el.val() !== '') { hasVal = true; return false; }
                    } else {
                        if ($el.val() && $el.val().trim() !== '') { hasVal = true; return false; }
                    }
                });
                if (hasVal) respondidas++;
            });
            var $txt = $('#quiz-progress-text');
            if ($txt.length) $txt.text(respondidas + '/' + total + ' respondidas');
        }

        function guardarQuiz(attemptId, cmid, moduloId, finish) {
            if (finish && !confirm('¿Estás seguro de finalizar el intento? No podrás modificar las respuestas después.')) return;

            detenerTimer();
            var $wrap = $('#quiz-preguntas-wrap');
            if (!$wrap.length) return;

            var data = [];
            var slots = [];

            $wrap.find('.quiz-pregunta-html').each(function() {
                var slot = $(this).data('slot');
                if (slot) slots.push(slot);

                $(this).find('input, select, textarea').each(function() {
                    var $el = $(this);
                    var name = $el.attr('name');
                    if (!name) return;
                    var val = '';
                    if ($el.is(':checkbox') || $el.is(':radio')) {
                        if ($el.prop('checked')) val = $el.val();
                        else return;
                    } else if ($el.is('select')) {
                        val = $el.val() || '';
                    } else {
                        val = $el.val();
                    }
                    data.push({ name: name, value: String(val || '') });
                });
            });

            data.push({ name: 'slots', value: slots.join(',') });

            var token = $('meta[name="csrf-token"]').attr('content');
            var $msg = $('#quiz-msg');
            $msg.html('<span style="color:#64748b;font-size:.85rem;"><i class="ri-loader-4-line"></i> Guardando respuestas…</span>');

            $.ajax({
                url:  '/virtual/modulo/' + moduloId + '/actividad/quiz/' + cmid + '/attempt/' + attemptId + '/submit',
                type: 'POST',
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': token },
                data: JSON.stringify({ data: data, finish: finish }),
            })
            .done(function(r) {
                if (!r.success) {
                    $msg.html('<span style="color:#dc2626;font-size:.85rem;"><i class="ri-close-circle-line"></i> ' + escHtml(r.message) + '</span>');
                    return;
                }

                if (finish) {
                    mostrarResultadosQuiz(attemptId, cmid, moduloId, r.attempt_data);
                } else {
                    $msg.html('<span style="color:#16a34a;font-size:.85rem;"><i class="ri-checkbox-circle-line"></i> Respuestas guardadas correctamente.</span>');
                    setTimeout(function() { $msg.html(''); }, 3000);
                }
            })
            .fail(function() {
                $msg.html('<span style="color:#dc2626;font-size:.85rem;"><i class="ri-wifi-off-line"></i> Error de conexión.</span>');
            });
        }

        function mostrarResultadosQuiz(attemptId, cmid, moduloId, attemptData) {
            detenerTimer();
            var totalScore = 0;
            var totalMax = 0;

            var html = '<div style="text-align:center;padding:1rem 0;">' +
                '<div style="font-size:2.5rem;color:#16a34a;margin-bottom:.5rem;"><i class="ri-checkbox-circle-fill"></i></div>' +
                '<div style="font-size:1.1rem;font-weight:700;color:#0f172a;margin-bottom:.25rem;">Intento finalizado</div>' +
                '<div style="font-size:.85rem;color:#64748b;margin-bottom:1.5rem;">Tus respuestas han sido registradas correctamente.</div>';

            if (attemptData && attemptData.length) {
                attemptData.forEach(function(q) {
                    var mark = q.mark;
                    var maxMark = q.maxmark || 0;
                    if (mark !== null && mark !== '-') {
                        totalScore += parseFloat(mark) || 0;
                    }
                    totalMax += maxMark;
                });

                // Score summary
                var pct = totalMax > 0 ? Math.round(totalScore / totalMax * 100) : 0;
                var passed = pct >= 51;
                html += '<div style="background:' + (passed ? '#f0fdf4' : '#fef2f2') + ';border:1.5px solid ' + (passed ? '#86efac' : '#fca5a5') + ';border-radius:12px;padding:1rem;margin-bottom:1.25rem;text-align:center;">' +
                    '<div style="font-size:1.4rem;font-weight:800;color:' + (passed ? '#16a34a' : '#dc2626') + ';">' + totalScore.toFixed(2) + ' / ' + totalMax + '</div>' +
                    '<div style="font-size:.78rem;color:' + (passed ? '#15803d' : '#991b1b') + ';font-weight:600;margin-top:.2rem;">' +
                    (passed ? '&#10003; Aprobado' : '&#10007; Reprobado') + ' (' + pct + '%)</div></div>';

                // Per-question results
                html += '<div style="text-align:left;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:1rem;">';
                attemptData.forEach(function(q, idx) {
                    var mark = q.mark;
                    var maxMark = q.maxmark || 0;
                    var numMark = (mark !== null && mark !== '-') ? parseFloat(mark) : null;
                    var isCorrect = numMark !== null && numMark > 0;
                    var statusColor = numMark !== null ? (numMark >= maxMark * 0.51 ? '#16a34a' : '#dc2626') : '#94a3b8';
                    var statusIcon = numMark !== null ? (numMark >= maxMark * 0.51 ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill') : 'ri-hourglass-line';
                    var bgColor = idx % 2 === 0 ? '#fff' : '#f8fafc';

                    html += '<div style="display:flex;align-items:center;gap:.75rem;padding:.7rem 1rem;border-bottom:1px solid #f1f5f9;background:' + bgColor + ';">' +
                        '<span style="color:' + statusColor + ';font-size:1.1rem;flex-shrink:0;"><i class="' + statusIcon + '"></i></span>' +
                        '<div style="flex:1;text-align:left;font-size:.82rem;font-weight:600;color:#0f172a;">' + escHtml(q.questionname || 'Pregunta ' + (idx + 1)) + '</div>' +
                        '<span style="font-size:.75rem;font-weight:700;color:' + statusColor + ';white-space:nowrap;">' + (mark !== null && mark !== '-' ? mark : '—') + ' / ' + maxMark + '</span>' +
                        '</div>';
                });
                html += '</div>';
            }

            html += '<button onclick="cargarQuiz(' + cmid + ',' + moduloId + ')" ' +
                'style="background:#f1f5f9;color:#334155;border:1.5px solid #cbd5e1;border-radius:10px;padding:.6rem 1.5rem;font-size:.85rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;">' +
                '<i class="ri-arrow-left-line"></i> Volver al cuestionario</button></div>';

            actSetBody(html);
        }

        function infoCard(icon, label, value) {
            return '<div style="background:#f8f9fa;border-radius:8px;padding:.75rem;text-align:center;">' +
                '<div style="font-size:1.1rem;color:#6c757d;margin-bottom:.2rem;">' + icon + '</div>' +
                '<div style="font-size:.72rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.5px;">' + label + '</div>' +
                '<div style="font-size:.88rem;font-weight:700;color:#2c3e50;">' + value + '</div></div>';
        }

        <?php if($esDocente && $perfilActivo === 'docente'): ?>
        document.addEventListener('DOMContentLoaded', function() {
            if (datosHorariosDocente && datosHorariosDocente.length > 0) {
                initCalendarDocente(datosHorariosDocente);
            }
        });
        <?php endif; ?>
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.virtual', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/virtual/dashboard.blade.php ENDPATH**/ ?>