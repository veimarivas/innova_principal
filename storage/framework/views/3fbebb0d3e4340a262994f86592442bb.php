<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Mi Portal'); ?> | Innova Ciencia Virtual</title>
    <link rel="shortcut icon" href="<?php echo e(URL::asset('build/images/logo_chico.ico')); ?>">
    <!-- Bootstrap + Icons + App CSS (mismas que el admin) -->
    <link href="<?php echo e(URL::asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('build/css/icons.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('build/css/app.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('build/css/custom.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('build/css/brand-colors.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('build/css/admin-common.css')); ?>" rel="stylesheet">
    <style>
    :root {
        --est-nav-h: 70px;
        --est-accent: #fc7b04;
        --est-accent2: #743c04;
    }
    body { background: var(--vz-body-bg, #f3f3f9); min-height: 100vh; }

    /* ─── Topbar ───────────────────────────────────── */
    .est-nav {
        position: fixed; top: 0; left: 0; right: 0; z-index: 1002;
        height: var(--est-nav-h);
        background: var(--vz-header-bg, #fff);
        border-bottom: 1px solid var(--vz-header-border-color, #e9ecef);
        display: flex; align-items: center; justify-content: space-between;
        padding: 0 1.25rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        transition: background .2s, border-color .2s;
    }

    /* Brand */
    .est-nav-brand {
        display: flex; align-items: center; gap: .6rem;
        text-decoration: none; flex-shrink: 0;
    }
    .est-nav-brand img { height: 22px; }
    .est-nav-brand-text {
        font-size: .9rem; font-weight: 700; letter-spacing: -.01em;
        color: var(--vz-header-item-color, #495057);
        transition: color .15s;
    }
    .est-nav-brand:hover .est-nav-brand-text { color: var(--est-accent); }

    /* Icon buttons (fullscreen, dark mode) */
    .est-nav-icon-btn {
        width: 38px; height: 38px;
        display: inline-flex; align-items: center; justify-content: center;
        border: none; background: transparent; border-radius: 50%;
        color: var(--vz-header-item-color, #6c757d);
        font-size: 1.25rem; cursor: pointer; transition: all .2s;
        flex-shrink: 0;
    }
    .est-nav-icon-btn:hover {
        background: rgba(154, 73, 4, .08);
        color: var(--est-accent);
    }

    /* User dropdown trigger */
    .est-nav-user-btn {
        display: flex; align-items: center; gap: .5rem;
        background: none; border: none; padding: .3rem .5rem;
        cursor: pointer; border-radius: 10px; transition: background .15s;
    }
    .est-nav-user-btn:hover { background: rgba(0,0,0,.04); }
    .est-nav-avatar {
        width: 36px; height: 36px; border-radius: 50%; object-fit: cover;
        border: 2px solid rgba(154, 73, 4, .25); flex-shrink: 0;
    }
    .est-nav-user-info {
        flex-direction: column; align-items: flex-start; line-height: 1.2;
    }
    .est-nav-username {
        font-size: .82rem; font-weight: 600;
        color: var(--vz-header-item-color, #495057);
        white-space: nowrap; max-width: 150px;
        overflow: hidden; text-overflow: ellipsis; display: block;
    }
    .est-nav-userrole {
        font-size: .7rem; color: var(--vz-header-item-sub-color, #9ca3af); display: block;
    }
    .est-nav-chevron { font-size: .9rem; color: #9ca3af; margin-left: .1rem; }

    /* Dropdown menu */
    .est-nav-tud-menu {
        border: none !important; border-radius: 14px !important;
        box-shadow: 0 8px 30px rgba(154, 73, 4, .18), 0 2px 8px rgba(0,0,0,.08) !important;
        overflow: hidden; min-width: 220px; padding: 0 !important; margin-top: 8px !important;
    }
    .est-nav-tud-header {
        background: linear-gradient(135deg, #9a4904 0%, #df6a04 100%);
        padding: 16px; display: flex; align-items: center; gap: 12px;
        position: relative; overflow: hidden;
    }
    .est-nav-tud-header::after {
        content: ''; position: absolute; top: -30%; right: -10%;
        width: 100px; height: 100px;
        background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
        border-radius: 50%; pointer-events: none;
    }
    .est-nav-tud-avatar {
        width: 42px; height: 42px; border-radius: 50%; object-fit: cover;
        border: 2px solid rgba(255,255,255,.6); position: relative; z-index: 1; flex-shrink: 0;
    }
    .est-nav-tud-info { position: relative; z-index: 1; overflow: hidden; }
    .est-nav-tud-name {
        font-size: .875rem; font-weight: 700; color: #fff;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        max-width: 140px; line-height: 1.2;
    }
    .est-nav-tud-role {
        display: inline-flex; align-items: center; margin-top: 4px;
        font-size: .68rem; font-weight: 600; padding: 2px 8px;
        background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.3);
        border-radius: 50px; color: rgba(255,255,255,.95); backdrop-filter: blur(4px);
    }
    .est-nav-tud-body { padding: 8px; }
    .est-nav-tud-item {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 12px; border-radius: 8px;
        font-size: .84rem; font-weight: 500; color: #391b04;
        text-decoration: none; transition: all .15s; cursor: pointer;
    }
    .est-nav-tud-item:hover { background: rgba(252,123,4,.09); color: #9a4904; }
    .est-nav-tud-icon {
        width: 30px; height: 30px; border-radius: 8px;
        background: rgba(252,123,4,.10); color: #df6a04;
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem; flex-shrink: 0; transition: all .15s;
    }
    .est-nav-tud-item:hover .est-nav-tud-icon { background: rgba(252,123,4,.18); color: #9a4904; }
    .est-nav-tud-divider { height: 1px; background: rgba(154,73,4,.10); margin: 4px 0; }
    .est-nav-tud-danger { color: #b91c1c !important; }
    .est-nav-tud-danger:hover { background: rgba(239,68,68,.07) !important; color: #991b1b !important; }
    .est-nav-tud-icon-danger { background: rgba(239,68,68,.10) !important; color: #ef4444 !important; }
    .est-nav-tud-danger:hover .est-nav-tud-icon-danger { background: rgba(239,68,68,.18) !important; }

    /* ─── Main content ─────────────────────────────── */
    .est-main { margin-top: var(--est-nav-h); min-height: calc(100vh - var(--est-nav-h)); }
    .est-container { max-width: 1200px; margin: 0 auto; padding: 2rem 1.25rem; }

    </style>
    <?php echo $__env->yieldContent('css'); ?>
</head>
<body>

<?php
    $navUser    = Auth::user();
    $navPersona = optional($navUser)->persona;
    if ($navPersona && $navPersona->fotografia && file_exists(public_path('images/personas/' . $navPersona->fotografia))) {
        $navAvatar = URL::asset('images/personas/' . $navPersona->fotografia);
    } elseif ($navUser && $navUser->avatar && file_exists(public_path('images/' . $navUser->avatar))) {
        $navAvatar = URL::asset('images/' . $navUser->avatar);
    } else {
        $navAvatar = URL::asset('build/images/users/avatar-1.jpg');
    }
    $navNombre = $navPersona
        ? (trim(($navPersona->nombres ?? '') . ' ' . ($navPersona->apellido_paterno ?? '')) ?: $navUser->name)
        : ($navUser->name ?? 'Usuario');
    $navRol = ucfirst($navUser->role ?? 'Estudiante');
?>

<nav class="est-nav">
    
    <a href="<?php echo e(route('virtual.dashboard')); ?>" class="est-nav-brand">
        <img src="<?php echo e(URL::asset('build/images/logo_chico.png')); ?>" alt="Logo"
             onerror="this.style.display='none'">
        <span class="est-nav-brand-text">Portal Virtual</span>
    </a>

    
    <div class="d-flex align-items-center gap-1">

        
        <button type="button" class="est-nav-icon-btn light-dark-mode" title="Modo oscuro / claro">
            <i class='bx bx-moon'></i>
        </button>

        
        <div class="dropdown">
            <button type="button" class="est-nav-user-btn" id="virt-user-dropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?php echo e($navAvatar); ?>" alt="Avatar" class="est-nav-avatar"
                     onerror="this.src='<?php echo e(URL::asset('build/images/users/avatar-1.jpg')); ?>'">
                <span class="est-nav-user-info d-none d-xl-flex">
                    <span class="est-nav-username"><?php echo e($navNombre); ?></span>
                    <span class="est-nav-userrole"><?php echo e($navRol); ?></span>
                </span>
                <i class="ri-arrow-down-s-line est-nav-chevron d-none d-xl-inline-block"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-end est-nav-tud-menu"
                 aria-labelledby="virt-user-dropdown">
                <div class="est-nav-tud-header">
                    <img src="<?php echo e($navAvatar); ?>" alt="Avatar" class="est-nav-tud-avatar"
                         onerror="this.src='<?php echo e(URL::asset('build/images/users/avatar-1.jpg')); ?>'">
                    <div class="est-nav-tud-info">
                        <div class="est-nav-tud-name"><?php echo e($navNombre); ?></div>
                        <span class="est-nav-tud-role"><?php echo e($navRol); ?></span>
                    </div>
                </div>
                <div class="est-nav-tud-body">
                    <a class="est-nav-tud-item" href="<?php echo e(route('virtual.dashboard')); ?>">
                        <span class="est-nav-tud-icon"><i class="ri-home-4-line"></i></span>
                        <span>Mi Portal</span>
                    </a>
                    <div class="est-nav-tud-divider"></div>
                    <a class="est-nav-tud-item est-nav-tud-danger" href="javascript:void(0);"
                       onclick="event.preventDefault(); document.getElementById('virt-logout-form').submit();">
                        <span class="est-nav-tud-icon est-nav-tud-icon-danger">
                            <i class="ri-logout-box-r-line"></i>
                        </span>
                        <span>Cerrar Sesión</span>
                    </a>
                </div>
                <form id="virt-logout-form" action="<?php echo e(route('logout')); ?>" method="POST"
                      style="display:none;"><?php echo csrf_field(); ?></form>
            </div>
        </div>

    </div>
</nav>

<main class="est-main">
    <div class="est-container">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</main>

<footer class="est-footer">
    &copy; <?php echo e(date('Y')); ?> <span>Innova Ciencia Virtual</span> — Portal Estudiantil. Todos los derechos reservados.
</footer>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="<?php echo e(URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<script>
(function () {
    // Restore saved theme on load
    var saved = localStorage.getItem('virt-theme');
    if (saved) document.documentElement.setAttribute('data-bs-theme', saved);

    // Dark mode toggle
    document.querySelectorAll('.light-dark-mode').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var current = document.documentElement.getAttribute('data-bs-theme');
            var next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', next);
            localStorage.setItem('virt-theme', next);
        });
    });
})();
</script>
<?php echo $__env->yieldContent('script'); ?>
</body>
</html>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/layouts/virtual.blade.php ENDPATH**/ ?>