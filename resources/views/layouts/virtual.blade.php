<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Portal') | Innova Ciencia Virtual</title>
    <link rel="shortcut icon" href="{{ URL::asset('build/images/logo_chico.ico') }}">
    <!-- Bootstrap + Icons + App CSS (mismas que el admin) -->
    <link href="{{ URL::asset('build/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('build/css/icons.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('build/css/app.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('build/css/custom.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('build/css/brand-colors.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('build/css/admin-common.css') }}" rel="stylesheet">
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
        display: flex; align-items: center; gap: .65rem;
        text-decoration: none; flex-shrink: 0;
        padding: .3rem .55rem .3rem .3rem;
        border-radius: 10px;
        transition: background .18s ease;
    }
    .est-nav-brand:hover { background: rgba(252,123,4,.06); }
    .est-nav-brand-logo {
        height: 38px; width: 38px;
        object-fit: contain;
        filter: drop-shadow(0 2px 6px rgba(154,73,4,.25));
        transition: transform .2s ease;
    }
    .est-nav-brand:hover .est-nav-brand-logo { transform: scale(1.05) rotate(-3deg); }
    .est-nav-brand-text {
        display: inline-flex; flex-direction: column; line-height: 1;
        font-family: 'Outfit', sans-serif;
        font-size: 1.02rem; font-weight: 700; letter-spacing: -.015em;
        color: var(--vz-header-item-color, #1e1610);
        transition: color .15s;
    }
    .est-nav-brand-text small {
        display: block;
        font-size: .58rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: .12em;
        color: var(--est-accent);
        margin-top: 2px;
    }
    .est-nav-brand:hover .est-nav-brand-text { color: var(--est-accent2); }

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

    /* ─── Footer ───────────────────────────────────── */
    .est-footer {
        position: relative;
        margin-top: 3rem;
        padding: 1.75rem 1.25rem 1.25rem;
        background: linear-gradient(135deg, #1a0d05 0%, #2e1600 45%, #5c2e00 100%);
        color: rgba(255,255,255,.8);
        overflow: hidden;
    }
    .est-footer::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, transparent 0%, #9a4904 25%, #fc7b04 50%, #9a4904 75%, transparent 100%);
    }
    .est-footer::after {
        content: '';
        position: absolute; top: -40%; right: -10%;
        width: 360px; height: 360px; border-radius: 50%;
        background: radial-gradient(circle, rgba(252,123,4,.10) 0%, transparent 70%);
        pointer-events: none;
    }
    .est-footer-inner {
        max-width: 1200px; margin: 0 auto;
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        grid-template-areas:
            "brand links"
            "copy  copy";
        gap: 1.25rem 1.5rem;
        align-items: center;
        position: relative; z-index: 1;
    }
    .est-footer-brand {
        grid-area: brand;
        display: flex; align-items: center; gap: .8rem;
    }
    .est-footer-logo {
        width: 44px; height: 44px;
        object-fit: contain;
        filter: drop-shadow(0 4px 12px rgba(252,123,4,.35));
        flex-shrink: 0;
    }
    .est-footer-brand-text { display: flex; flex-direction: column; line-height: 1.15; }
    .est-footer-brand-name {
        font-family: 'Outfit', sans-serif;
        font-size: 1rem; font-weight: 700;
        color: #fff; letter-spacing: -.01em;
    }
    .est-footer-brand-tag {
        font-size: .72rem; color: rgba(255,255,255,.55);
        margin-top: 3px; font-weight: 500;
    }

    .est-footer-links {
        grid-area: links;
        display: flex; justify-content: flex-end; align-items: center;
        gap: .65rem; flex-wrap: wrap;
    }
    .est-footer-link {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .78rem; font-weight: 600;
        color: rgba(255,255,255,.7);
        text-decoration: none;
        padding: .35rem .7rem;
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,.08);
        background: rgba(255,255,255,.04);
        transition: all .2s ease;
    }
    .est-footer-link i { font-size: .9rem; color: #fc7b04; }
    .est-footer-link:hover {
        color: #fff;
        background: rgba(252,123,4,.18);
        border-color: rgba(252,123,4,.4);
        transform: translateY(-1px);
    }
    .est-footer-sep {
        width: 1px; height: 14px;
        background: rgba(255,255,255,.12);
    }

    .est-footer-copy {
        grid-area: copy;
        padding-top: 1rem;
        border-top: 1px solid rgba(255,255,255,.08);
        font-size: .74rem;
        color: rgba(255,255,255,.55);
        text-align: center;
        letter-spacing: .015em;
    }
    .est-footer-copy strong {
        color: rgba(255,255,255,.85); font-weight: 700;
    }
    .est-footer-copy-divider { margin: 0 .35rem; color: rgba(255,255,255,.25); }

    @media (max-width: 720px) {
        .est-footer-inner {
            grid-template-columns: 1fr;
            grid-template-areas:
                "brand"
                "links"
                "copy";
            text-align: center;
        }
        .est-footer-brand { justify-content: center; }
        .est-footer-links { justify-content: center; }
    }

    </style>
    @yield('css')
</head>
<body>

@php
    $navUser    = Auth::user();
    $navPersona = optional($navUser)->persona;
    if ($navPersona && $navPersona->fotografia && file_exists(public_path('images/personas/' . $navPersona->fotografia))) {
        $navAvatar = URL::asset('images/personas/' . $navPersona->fotografia);
    } elseif ($navUser && $navUser->avatar && file_exists(public_path('images/' . $navUser->avatar))) {
        $navAvatar = URL::asset('images/' . $navUser->avatar);
    } else {
        $sexoNav = $navPersona?->sexo;
        $defaultNavFile = $sexoNav === 'F' ? 'mujer.png' : 'chico.png';
        $navAvatar = URL::asset('images/' . $defaultNavFile);
    }
    $navNombre = $navPersona
        ? (trim(($navPersona->nombres ?? '') . ' ' . ($navPersona->apellido_paterno ?? '')) ?: $navUser->name)
        : ($navUser->name ?? 'Usuario');
    $navRol = ucfirst($navUser->role ?? 'Estudiante');
@endphp

<nav class="est-nav">
    {{-- Brand / Logo --}}
    <a href="{{ route('virtual.dashboard') }}" class="est-nav-brand">
        <img src="{{ asset('images/logo_secundario.png') }}" alt="InnovaCiencia"
             class="est-nav-brand-logo"
             onerror="this.style.display='none'">
        <span class="est-nav-brand-text">
            InnovaCiencia Virtual
            <small>Portal Académico</small>
        </span>
    </a>

    {{-- Right actions --}}
    <div class="d-flex align-items-center gap-1">

        {{-- Dark mode toggle --}}
        <button type="button" class="est-nav-icon-btn light-dark-mode" title="Modo oscuro / claro">
            <i class='bx bx-moon'></i>
        </button>

        {{-- User dropdown --}}
        <div class="dropdown">
            <button type="button" class="est-nav-user-btn" id="virt-user-dropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ $navAvatar }}" alt="Avatar" class="est-nav-avatar" id="est-nav-avatar-img"
                     onerror="this.src='{{ URL::asset('build/images/users/avatar-1.jpg') }}'">
                <span class="est-nav-user-info d-none d-xl-flex">
                    <span class="est-nav-username">{{ $navNombre }}</span>
                    <span class="est-nav-userrole">{{ $navRol }}</span>
                </span>
                <i class="ri-arrow-down-s-line est-nav-chevron d-none d-xl-inline-block"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-end est-nav-tud-menu"
                 aria-labelledby="virt-user-dropdown">
                <div class="est-nav-tud-header">
                    <img src="{{ $navAvatar }}" alt="Avatar" class="est-nav-tud-avatar" id="est-nav-tud-avatar-img"
                         onerror="this.src='{{ URL::asset('build/images/users/avatar-1.jpg') }}'">
                    <div class="est-nav-tud-info">
                        <div class="est-nav-tud-name">{{ $navNombre }}</div>
                        <span class="est-nav-tud-role">{{ $navRol }}</span>
                    </div>
                </div>
                <div class="est-nav-tud-body">
                    <a class="est-nav-tud-item" href="{{ route('virtual.dashboard') }}">
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
                <form id="virt-logout-form" action="{{ route('logout') }}" method="POST"
                      style="display:none;">@csrf</form>
            </div>
        </div>

    </div>
</nav>

<main class="est-main">
    <div class="est-container">
        @yield('content')
    </div>
</main>

<footer class="est-footer">
    <div class="est-footer-inner">
        <div class="est-footer-brand">
            <img src="{{ asset('images/logo_secundario.png') }}" alt="InnovaCiencia"
                 class="est-footer-logo" onerror="this.style.display='none'">
            <div class="est-footer-brand-text">
                <span class="est-footer-brand-name">InnovaCiencia Virtual</span>
                <span class="est-footer-brand-tag">Plataforma académica y científica</span>
            </div>
        </div>
        <div class="est-footer-links">
            <a href="{{ route('virtual.dashboard') }}" class="est-footer-link">
                <i class="ri-home-4-line"></i> Mi Portal
            </a>
            <span class="est-footer-sep"></span>
            <a href="mailto:soporte@innovaciencia.edu.bo" class="est-footer-link">
                <i class="ri-customer-service-2-line"></i> Soporte
            </a>
            <span class="est-footer-sep"></span>
            <a href="https://wa.me/59100000000" target="_blank" rel="noopener" class="est-footer-link">
                <i class="ri-whatsapp-line"></i> WhatsApp
            </a>
        </div>
        <div class="est-footer-copy">
            &copy; {{ date('Y') }} <strong>InnovaCiencia Virtual</strong>
            <span class="est-footer-copy-divider">·</span>
            Todos los derechos reservados
        </div>
    </div>
</footer>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
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
@yield('script')
</body>
</html>
