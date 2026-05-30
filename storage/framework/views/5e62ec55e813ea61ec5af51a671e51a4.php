<?php
    $user    = auth()->user();
    $persona = $user->persona;

    $tieneFoto = $persona && $persona->fotografia && file_exists(public_path('images/personas/' . $persona->fotografia));
    $avatarUrl = $tieneFoto ? asset('images/personas/' . $persona->fotografia) : null;

    $nombreCompleto = $persona
        ? trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''))
        : $user->name;
    if (!$nombreCompleto) $nombreCompleto = $user->name;

    $iniciales = collect(explode(' ', $nombreCompleto))
        ->filter()->take(2)->map(fn($p) => strtoupper($p[0]))->implode('');

    $edad = ($persona && $persona->fecha_nacimiento)
        ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->age
        : null;

    $ubicacion = ($persona && $persona->ciudad)
        ? optional($persona->ciudad)->nombre . ', ' . (optional(optional($persona->ciudad)->departamento)->nombre ?? '')
        : null;
?>

<div class="ci-wrap">

    
    <div class="ci-stripe"></div>

    <div class="ci-body">

        
        <div class="ci-left">

            <div class="ci-foto-label">
                <i class="ri-building-2-line"></i>
                <span>INNOVA CIENCIA</span>
            </div>

            <div class="ci-foto" id="ci-foto-container">
                <img src="<?php echo e($avatarUrl ?? ''); ?>" alt="Foto"
                     id="ci-foto-img"
                     style="<?php echo e($tieneFoto ? '' : 'display:none;'); ?>"
                     onerror="this.style.display='none';document.getElementById('ci-initials').style.display='flex';">
                <div id="ci-initials" class="ci-initials"
                     style="<?php echo e($tieneFoto ? 'display:none;' : ''); ?>">
                    <?php echo e($iniciales ?: '?'); ?>

                </div>
                <div class="ci-foto-overlay">
                    <button type="button" class="ci-btn-foto"
                            data-bs-toggle="modal" data-bs-target="#uploadFotoModal">
                        <i class="ri-camera-fill"></i> Cambiar
                    </button>
                </div>
            </div>

            <div class="ci-quick-data">
                <?php if($persona?->carnet): ?>
                <div class="ci-qd-item">
                    <i class="ri-shield-check-line"></i>
                    <span class="ci-qd-label">CI</span>
                    <span class="ci-qd-val"><?php echo e($persona->carnet); ?><?php echo e($persona->expedido ? ' '.$persona->expedido : ''); ?></span>
                </div>
                <?php endif; ?>
                <?php if($persona?->fecha_nacimiento): ?>
                <div class="ci-qd-item">
                    <i class="ri-cake-line"></i>
                    <span class="ci-qd-label">Nacimiento</span>
                    <span class="ci-qd-val"><?php echo e(\Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y')); ?></span>
                </div>
                <?php endif; ?>
                <?php if($edad): ?>
                <div class="ci-qd-item">
                    <i class="ri-user-line"></i>
                    <span class="ci-qd-label">Edad</span>
                    <span class="ci-qd-val"><?php echo e($edad); ?> años</span>
                </div>
                <?php endif; ?>
                <?php if($persona?->sexo): ?>
                <div class="ci-qd-item">
                    <i class="ri-genderless-line"></i>
                    <span class="ci-qd-label">Sexo</span>
                    <span class="ci-qd-val"><?php echo e($persona->sexo); ?></span>
                </div>
                <?php endif; ?>
            </div>

        </div>

        
        <div class="ci-center">

            <div class="ci-nombre-wrap">
                <div>
                    <div class="ci-nombre"><?php echo e($nombreCompleto); ?></div>
                    <div class="ci-cargo-line">
                        <i class="ri-shield-user-line"></i>
                        <?php echo e(ucfirst($user->role ?? 'Usuario')); ?>

                    </div>
                </div>
                <span class="ci-estado-badge <?php echo e(($user->estado ?? 'Activo') === 'Activo' ? 'ci-badge-activo' : 'ci-badge-inactivo'); ?>">
                    <i class="ri-checkbox-circle-line"></i>
                    <?php echo e($user->estado ?? 'Activo'); ?>

                </span>
            </div>

            <div class="ci-section-title">
                <i class="ri-contacts-line"></i> Datos de Contacto
            </div>

            <div class="ci-datos-grid">
                <div class="ci-dato">
                    <span class="ci-label">Correo</span>
                    <span class="ci-value"><?php echo e($persona?->correo ?? '—'); ?></span>
                </div>
                <div class="ci-dato">
                    <span class="ci-label">Celular</span>
                    <span class="ci-value"><?php echo e($persona?->celular ?? '—'); ?></span>
                </div>
                <div class="ci-dato">
                    <span class="ci-label">Teléfono</span>
                    <span class="ci-value"><?php echo e($persona?->telefono ?? '—'); ?></span>
                </div>
                <div class="ci-dato">
                    <span class="ci-label">Estado Civil</span>
                    <span class="ci-value"><?php echo e($persona?->estado_civil ?? '—'); ?></span>
                </div>
                <div class="ci-dato ci-full">
                    <span class="ci-label">Ciudad / Departamento</span>
                    <span class="ci-value"><?php echo e($ubicacion ?? '—'); ?></span>
                </div>
                <div class="ci-dato ci-full">
                    <span class="ci-label">Dirección</span>
                    <span class="ci-value"><?php echo e($persona?->direccion ?? '—'); ?></span>
                </div>
            </div>

        </div>

        
        <div class="ci-right">

            <div class="ci-right-header">
                <i class="ri-account-circle-line"></i>
                <span>Datos de Cuenta</span>
            </div>

            <div class="ci-account-list">
                <div class="ci-acc-item">
                    <div class="ci-acc-icon"><i class="ri-user-line"></i></div>
                    <div>
                        <div class="ci-acc-label">Usuario</div>
                        <div class="ci-acc-value"><?php echo e($user->name); ?></div>
                    </div>
                </div>
                <div class="ci-acc-item">
                    <div class="ci-acc-icon"><i class="ri-mail-send-line"></i></div>
                    <div>
                        <div class="ci-acc-label">Email de acceso</div>
                        <div class="ci-acc-value"><?php echo e($user->email); ?></div>
                    </div>
                </div>
                <div class="ci-acc-item">
                    <div class="ci-acc-icon"><i class="ri-shield-user-line"></i></div>
                    <div>
                        <div class="ci-acc-label">Rol</div>
                        <div class="ci-acc-value"><?php echo e(ucfirst($user->role ?? '—')); ?></div>
                    </div>
                </div>
                <div class="ci-acc-item">
                    <div class="ci-acc-icon"><i class="ri-calendar-check-line"></i></div>
                    <div>
                        <div class="ci-acc-label">Miembro desde</div>
                        <div class="ci-acc-value"><?php echo e($user->created_at->format('d/m/Y')); ?></div>
                    </div>
                </div>
                <?php if($persona?->estudios?->count()): ?>
                <div class="ci-acc-item">
                    <div class="ci-acc-icon"><i class="ri-graduation-cap-line"></i></div>
                    <div>
                        <div class="ci-acc-label">Estudios</div>
                        <div class="ci-acc-value"><?php echo e($persona->estudios->count()); ?> registrado(s)</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="ci-right-footer">
                <button class="ci-btn-cambiar-foto"
                        data-bs-toggle="modal" data-bs-target="#uploadFotoModal">
                    <i class="ri-camera-line"></i> Cambiar foto de perfil
                </button>
            </div>

        </div>

    </div>

    
    <div class="ci-bottom-bar">
        <span><i class="ri-id-card-line"></i> Carnet de Identificación</span>
        <span><?php echo e(now()->format('Y')); ?></span>
    </div>

</div>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/profile/tabs/personal.blade.php ENDPATH**/ ?>