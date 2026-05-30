<?php
    $rolNombre = auth()->user()->role ?? 'Usuario';
?>

<div class="profile-header">
    <div>
        <h1><i class="ri-user-line me-2"></i>Mi Perfil</h1>
        <p>Gestiona tu información personal y configuración de cuenta</p>
    </div>
    <div class="profile-badges">
        <span class="profile-badge">
            <i class="ri-shield-user-line"></i><?php echo e($rolNombre); ?>

        </span>
        <span class="profile-badge">
            <i class="ri-calendar-check-line"></i>Miembro desde <?php echo e(auth()->user()->created_at->format('Y')); ?>

        </span>
    </div>
</div>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/profile/header.blade.php ENDPATH**/ ?>