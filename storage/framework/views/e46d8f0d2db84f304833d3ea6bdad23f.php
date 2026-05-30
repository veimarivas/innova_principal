<div class="row g-4">
    <div class="col-md-8">
        <div class="password-card">
            <div class="password-card-header">
                <div class="password-card-header-icon">
                    <i class="ri-lock-password-line"></i>
                </div>
                <div>
                    <h6>Cambiar Contraseña</h6>
                    <p>Actualiza tu contraseña de acceso al sistema</p>
                </div>
            </div>

            <div class="password-card-body">
                <form id="changePasswordForm">
                    <?php echo csrf_field(); ?>

                    <div class="mb-4">
                        <label for="current_password" class="form-label fw-semibold mb-1" style="font-size:.83rem;">
                            <i class="ri-lock-line me-1 text-muted"></i>Contraseña Actual
                        </label>
                        <div class="password-input-group">
                            <span class="input-icon"><i class="ri-lock-password-line"></i></span>
                            <input type="password" class="form-control"
                                   id="current_password" name="current_password"
                                   placeholder="Ingresa tu contraseña actual" required>
                            <button class="toggle-pw" type="button"><i class="ri-eye-line"></i></button>
                        </div>
                    </div>

                    <hr class="my-3" style="border-color:var(--prof-border);opacity:.5;">

                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-semibold mb-1" style="font-size:.83rem;">
                            <i class="ri-key-line me-1 text-muted"></i>Nueva Contraseña
                        </label>
                        <div class="password-input-group">
                            <span class="input-icon"><i class="ri-key-line"></i></span>
                            <input type="password" class="form-control"
                                   id="new_password" name="new_password"
                                   placeholder="Mínimo 8 caracteres" required minlength="8">
                            <button class="toggle-pw" type="button"><i class="ri-eye-line"></i></button>
                        </div>
                        <div class="mt-2">
                            <div class="pw-strength-bar">
                                <div id="passwordStrengthBar" class="pw-strength-fill" style="width:0%;"></div>
                            </div>
                            <small id="passwordStrengthText" class="form-text fw-semibold" style="font-size:.75rem;"></small>
                        </div>
                        <ul class="mb-0 ps-3 mt-1 text-muted" style="font-size:.75rem;">
                            <li>Mínimo 8 caracteres</li>
                            <li>Al menos una letra y un número</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <label for="new_password_confirmation" class="form-label fw-semibold mb-1" style="font-size:.83rem;">
                            <i class="ri-key-fill me-1 text-muted"></i>Confirmar Nueva Contraseña
                        </label>
                        <div class="password-input-group">
                            <span class="input-icon"><i class="ri-key-fill"></i></span>
                            <input type="password" class="form-control"
                                   id="new_password_confirmation" name="new_password_confirmation"
                                   placeholder="Repite la nueva contraseña" required>
                            <button class="toggle-pw" type="button"><i class="ri-eye-line"></i></button>
                        </div>
                        <div id="passwordMatch" class="form-text fw-semibold mt-1" style="font-size:.76rem;"></div>
                    </div>

                    <div id="pwAlert" class="alert d-none mb-3" role="alert" style="font-size:.83rem;"></div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn-update-password" id="changePasswordBtn">
                            <i class="ri-shield-check-line me-1"></i>Actualizar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="tips-card">
            <div class="tips-card-header">
                <div class="tips-card-header-icon">
                    <i class="ri-shield-keyhole-line"></i>
                </div>
                <div>
                    <h6>Recomendaciones</h6>
                    <p>Buenas prácticas de seguridad</p>
                </div>
            </div>
            <div class="tips-card-body">
                <?php $__currentLoopData = [
                    ['icon'=>'ri-text-wrap',        'color'=>'#667eea', 'title'=>'Usa al menos 8 caracteres',  'desc'=>'Contraseñas más largas son más seguras'],
                    ['icon'=>'ri-spam-2-line',       'color'=>'#20c997', 'title'=>'Combina letras y números',   'desc'=>'Mezcla mayúsculas, minúsculas y dígitos'],
                    ['icon'=>'ri-forbid-line',       'color'=>'#fd7e14', 'title'=>'Evita contraseñas comunes',  'desc'=>'No uses "123456", "qwerty" o tu nombre'],
                    ['icon'=>'ri-user-forbid-line',  'color'=>'#e83e8c', 'title'=>'Sin información personal',   'desc'=>'Evita fechas de nacimiento o carnet'],
                    ['icon'=>'ri-loop-right-line',   'color'=>'#9a4904', 'title'=>'Cambia periódicamente',      'desc'=>'Actualiza tu contraseña cada 3-6 meses'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="tip-item">
                    <div class="tip-icon" style="background:<?php echo e($tip['color']); ?>1a;">
                        <i class="<?php echo e($tip['icon']); ?>" style="color:<?php echo e($tip['color']); ?>;"></i>
                    </div>
                    <div>
                        <div class="tip-title"><?php echo e($tip['title']); ?></div>
                        <p class="tip-desc"><?php echo e($tip['desc']); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/profile/tabs/password.blade.php ENDPATH**/ ?>