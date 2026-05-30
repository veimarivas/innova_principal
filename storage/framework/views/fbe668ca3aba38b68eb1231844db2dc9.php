<div class="oferta-unified-header">
    <!-- Barra Superior: Navegación Back y Breadcrumbs -->
    <div class="ouh-top-bar">
        <a href="<?php echo e(url()->previous()); ?>" class="ouh-btn-back">
            <i class="ri-arrow-left-line"></i>
            <span>Volver</span>
        </a>
        <div class="ouh-breadcrumbs d-none d-md-flex">
            <span class="ouh-breadcrumb-item">Gestión Académica</span>
            <i class="ri-arrow-right-s-line"></i>
            <span class="ouh-breadcrumb-item">Ofertas Académicas</span>
            <i class="ri-arrow-right-s-line"></i>
            <span class="ouh-breadcrumb-item active">Detalle</span>
        </div>
    </div>
    
    <!-- Contenido Principal: Identidad del Programa y Widgets de Métricas -->
    <div class="ouh-main-content">
        <div class="ouh-left-section">
            <div class="ouh-title-block">
                <div class="ouh-header-code-line">
                    <div class="ouh-program-badge">
                        <i class="ri-file-code-line"></i>
                        <span><?php echo e($oferta->codigo); ?></span>
                    </div>
                    <span class="ouh-header-version-badge"><i class="ri-git-commit-line"></i> v<?php echo e($oferta->version); ?></span>
                </div>
                <h1 class="ouh-title"><?php echo e($oferta->programa->nombre ?? 'Programa Sin Asignar'); ?></h1>
                <div class="ouh-subtitle">
                    <span class="ouh-subtitle-item"><i class="ri-graduation-cap-line"></i> <?php echo e($oferta->posgrado->nombre ?? 'Posgrado Sin Asignar'); ?></span>
                    <span class="ouh-subtitle-separator">•</span>
                    <span class="ouh-subtitle-item"><i class="ri-map-pin-line"></i> <?php echo e($oferta->sucursal->nombre ?? 'Sede Sin Asignar'); ?></span>
                </div>
            </div>
        </div>
        
        <div class="ouh-right-section">
            <!-- Widgets de Estadísticas Glassmorphic -->
            <div class="ouh-stats-grid">
                <div class="ouh-stat-card">
                    <div class="ouh-stat-icon"><i class="ri-layout-grid-line"></i></div>
                    <div class="ouh-stat-details">
                        <span class="ouh-stat-value"><?php echo e($oferta->n_modulos); ?></span>
                        <span class="ouh-stat-label">Módulos</span>
                    </div>
                </div>
                <div class="ouh-stat-card">
                    <div class="ouh-stat-icon"><i class="ri-calendar-check-line"></i></div>
                    <div class="ouh-stat-details">
                        <span class="ouh-stat-value"><?php echo e($oferta->cantidad_sesiones); ?></span>
                        <span class="ouh-stat-label">Sesiones</span>
                    </div>
                </div>
                <div class="ouh-stat-card">
                    <div class="ouh-stat-icon"><i class="ri-user-follow-line"></i></div>
                    <div class="ouh-stat-details">
                        <span class="ouh-stat-value"><?php echo e($oferta->inscripciones()->count()); ?></span>
                        <span class="ouh-stat-label">Inscritos</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Fila de Badges de Estado del Programa -->
    <div class="ouh-badges-row">
        <?php if($oferta->fase): ?>
            <span class="ouh-badge badge-fase-custom"><i class="ri-route-line"></i> <?php echo e($oferta->fase->nombre); ?></span>
        <?php endif; ?>
        <?php if($oferta->modalidad): ?>
            <span class="ouh-badge badge-modalidad-custom"><i class="ri-wifi-line"></i> <?php echo e($oferta->modalidad->nombre); ?></span>
        <?php endif; ?>
        <span class="ouh-badge badge-gestion-custom"><i class="ri-calendar-event-line"></i> Gestión <?php echo e($oferta->gestion); ?></span>
        <span class="ouh-badge badge-grupo-custom"><i class="ri-team-line"></i> Grupo <?php echo e($oferta->grupo); ?></span>
    </div>

    <!-- Navegación de Pestañas (Tabs) -->
    <div class="oferta-tabs">
        <button class="oferta-tab active" data-tab="info"><i class="ri-information-line"></i> Información General</button>
        <button class="oferta-tab" data-tab="modulos"><i class="ri-layout-grid-line"></i> Módulos y Horarios <span class="tab-badge"><?php echo e($oferta->n_modulos); ?></span></button>
        <button class="oferta-tab" data-tab="contable"><i class="ri-calculator-line"></i> Área Contable</button>
        <button class="oferta-tab" data-tab="finanzas"><i class="ri-wallet-line"></i> Finanzas</button>
        <button class="oferta-tab" data-tab="inscripciones"><i class="ri-user-follow-line"></i> Inscripciones <span class="tab-badge" id="inscripcionesBadge"><?php echo e($oferta->inscripciones()->count()); ?></span></button>
        <button class="oferta-tab" data-tab="plataforma"><i class="ri-shield-keyhole-line"></i> Plataforma Moodle</button>
    </div>
</div>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/ofertas-academicas/partials/ofertas-detalle/header-tabs.blade.php ENDPATH**/ ?>