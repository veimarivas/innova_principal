@php
    $currentRoute = Route::currentRouteName();

    function isRouteActive($routeNames, $strict = false)
    {
        $current = Route::currentRouteName();
        if (is_array($routeNames)) {
            foreach ($routeNames as $route) {
                if ($strict) {
                    if ($current === $route) {
                        return true;
                    }
                } else {
                    if (str_starts_with($current, $route)) {
                        return true;
                    }
                }
            }
        } else {
            if ($strict) {
                return $current === $routeNames;
            }
            return str_starts_with($current, $routeNames);
        }
        return false;
    }

    function isMenuOpen($routeNames)
    {
        return isRouteActive($routeNames);
    }
@endphp

<style>
    /* ══ Sidebar – diseño mejorado ══ */

    /* ── Fondo principal ── */
    .app-menu {
        background: linear-gradient(175deg, #7a3000 0%, #b85500 48%, #f47200 100%) !important;
        border-right: none !important;
        box-shadow: 4px 0 32px rgba(0,0,0,.24) !important;
    }

    /* ── Área del logo ── */
    .app-menu .navbar-brand-box {
        background: rgba(0,0,0,.24);
        border-bottom: 1px solid rgba(255,255,255,.12);
        padding: 0 20px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 8px;
    }
    .app-menu .navbar-brand-box .btn-vertical-sm-hover { margin-left: auto; }
    .app-menu .logo { line-height: 1; }
    .app-menu .logo-lg img { height: 36px !important; width: auto; object-fit: contain; filter: drop-shadow(0 2px 6px rgba(0,0,0,.3)); }
    .app-menu .logo-sm img { height: 28px !important; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,.25)); }

    /* ── Bloque de usuario ── */
    .sidebar-user {
        background: rgba(0,0,0,.18);
        border: 1px solid rgba(255,255,255,.14);
        margin: 10px 12px !important;
        padding: 9px 11px;
        border-radius: 12px;
        transition: background .2s, border-color .2s;
    }
    .sidebar-user:hover {
        background: rgba(0,0,0,.28) !important;
        border-color: rgba(255,255,255,.26) !important;
    }
    .sidebar-user .header-profile-user {
        border: 2px solid rgba(255,255,255,.6) !important;
        box-shadow: 0 2px 10px rgba(0,0,0,.22);
    }
    .sidebar-user-name-text  { color: #fff !important; font-weight: 700; font-size: .875rem; letter-spacing: .01em; }
    .sidebar-user-name-sub-text { color: rgba(255,255,255,.7) !important; font-size: .76rem; }

    /* ── Contenedor de navegación ── */
    #navbar-nav { padding: 4px 10px 28px; }
    #navbar-nav .nav-item { margin-bottom: 1px; }

    /* ── Links principales ── */
    #navbar-nav .nav-link {
        color: rgba(255,255,255,.92) !important;
        padding: 9px 12px !important;
        border-radius: 10px;
        font-weight: 500;
        font-size: .875rem;
        transition: background .15s;
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
    }
    #navbar-nav .nav-link:hover {
        background: rgba(0,0,0,.16) !important;
        color: #fff !important;
        transform: none !important;
    }
    #navbar-nav > .nav-item > .nav-link:hover { transform: none !important; }

    /* ── Estado activo: píldora blanca con texto naranja ── */
    #navbar-nav .nav-link.active {
        background: rgba(255,255,255,.94) !important;
        color: #9a4904 !important;
        font-weight: 700 !important;
        box-shadow: 0 3px 14px rgba(0,0,0,.2);
    }
    #navbar-nav .nav-link.active:hover { background: #fff !important; }

    /* ── Iconos ── */
    #navbar-nav .nav-link > i {
        font-size: 1.1rem;
        width: 22px;
        text-align: center;
        flex-shrink: 0;
        color: rgba(255,255,255,.85);
        transition: color .15s;
    }
    #navbar-nav .nav-link:hover > i { color: #fff; }
    #navbar-nav .nav-link.active > i { color: #c96004 !important; }

    /* ── Títulos de sección ── */
    #navbar-nav .menu-title {
        color: rgba(255,255,255,.5) !important;
        font-size: .62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        padding: 5px 12px 4px;
        margin: 16px 0 3px;
        display: flex;
        align-items: center;
        gap: 8px;
        background: none;
    }
    #navbar-nav .menu-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(255,255,255,.18);
    }
    #navbar-nav .menu-title i { display: none; }

    /* ── Links con submenú (parent) ── */
    #navbar-nav .nav-link.menu-link { color: rgba(255,255,255,.92) !important; }
    #navbar-nav .nav-link.menu-link[aria-expanded="true"] {
        background: rgba(0,0,0,.2) !important;
        color: #fff !important;
        font-weight: 600;
        border-radius: 10px 10px 0 0;
    }
    /* Parent activo cuando el submenu está cerrado */
    #navbar-nav .nav-link.menu-link.active:not([aria-expanded="true"]) {
        background: rgba(255,255,255,.94) !important;
        color: #9a4904 !important;
        font-weight: 700 !important;
        box-shadow: 0 3px 14px rgba(0,0,0,.2);
    }
    #navbar-nav .nav-link.menu-link.active:not([aria-expanded="true"]) > i { color: #c96004 !important; }

    /* ── Submenú ── */
    #navbar-nav .menu-dropdown {
        margin: 0 0 3px 6px;
        padding: 4px 6px 6px 4px;
        border-left: 2px solid rgba(255,255,255,.2);
        background: rgba(0,0,0,.12);
        border-radius: 0 0 10px 10px;
        overflow: hidden;
    }
    #navbar-nav .menu-dropdown .nav-sm {
        padding-left: 0 !important;
    }
    #navbar-nav .menu-dropdown .nav-link {
        padding: 7px 10px 7px 16px !important;
        font-size: .825rem;
        color: rgba(255,255,255,.8) !important;
        border-radius: 8px;
        font-weight: 400;
        transform: none !important;
    }
    #navbar-nav .menu-dropdown .nav-link::before {
        content: '';
        position: absolute;
        left: 5px;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: rgba(255,255,255,.3);
        transition: background .15s, width .15s, height .15s;
    }
    #navbar-nav .menu-dropdown .nav-link:hover {
        color: #fff !important;
        background: rgba(255,255,255,.12) !important;
        transform: none !important;
    }
    #navbar-nav .menu-dropdown .nav-link:hover::before { background: rgba(255,255,255,.75); }

    /* Subitem activo: fondo blanco + texto naranja */
    #navbar-nav .menu-dropdown .nav-link.active {
        background: rgba(255,255,255,.94) !important;
        color: #9a4904 !important;
        font-weight: 700 !important;
    }
    #navbar-nav .menu-dropdown .nav-link.active::before { background: #c96004; width: 6px; height: 6px; }

    /* Ocultar ::before del framework en primer nivel */
    .app-menu .navbar-nav .nav-link.active::before { display: none; }

    /* ── Botón de colapso ── */
    #vertical-hover { color: rgba(255,255,255,.6); transition: color .2s; }
    #vertical-hover:hover { color: #fff; }

    /* ── Dropdown usuario ── */
    .app-menu .dropdown-menu {
        background: #fff;
        border: none;
        box-shadow: 0 10px 32px rgba(0,0,0,.18);
        border-radius: 12px;
        overflow: hidden;
    }
    .app-menu .dropdown-menu .dropdown-item { color: #374151; padding: 9px 16px; font-size: .855rem; transition: background .14s, color .14s; }
    .app-menu .dropdown-menu .dropdown-item:hover { background: rgba(252,123,4,.09); color: #c96003; }
    .app-menu .dropdown-menu .dropdown-header { color: #1e293b; font-weight: 700; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; }
    .app-menu .dropdown-divider { border-color: #f1f5f9; margin: 4px 0; }

    /* ══════════════════════════════════════════
       MODO COLAPSADO (hamburguesa → data-sidebar-size="sm")
       ══════════════════════════════════════════ */

    [data-sidebar-size="sm"] .app-menu .navbar-brand-box,
    [data-sidebar-size="sm-hover"] .app-menu .navbar-brand-box { padding: 0 6px; justify-content: center; gap: 0; }
    [data-sidebar-size="sm-hover"] .app-menu .navbar-brand-box .btn-vertical-sm-hover { margin-left: 0; }
    [data-sidebar-size="sm-hover"] .app-menu:hover .navbar-brand-box { padding: 0 20px; justify-content: flex-start; gap: 8px; }
    [data-sidebar-size="sm-hover"] .app-menu:hover .navbar-brand-box .btn-vertical-sm-hover { margin-left: auto; }

    /* Ocultar títulos de sección */
    [data-sidebar-size="sm"] .app-menu #navbar-nav .menu-title { display: none !important; }

    /* Iconos centrados */
    [data-sidebar-size="sm"] .app-menu #navbar-nav { padding-left: 6px !important; padding-right: 6px !important; }
    [data-sidebar-size="sm"] .app-menu #navbar-nav .nav-link { padding: 11px 10px !important; justify-content: center; border-radius: 10px; }

    /* Active en modo colapsado: píldora blanca */
    [data-sidebar-size="sm"] .app-menu #navbar-nav .nav-link.active {
        background: rgba(255,255,255,.94) !important;
        box-shadow: 0 3px 12px rgba(0,0,0,.2);
    }
    [data-sidebar-size="sm"] .app-menu #navbar-nav .nav-link.active > i { color: #c96004 !important; }

    /* menu-link colapsado */
    [data-sidebar-size="sm"] .app-menu #navbar-nav .nav-link.menu-link { background: rgba(0,0,0,.14); border-radius: 10px; }
    [data-sidebar-size="sm"] .app-menu .nav-item:hover > a.menu-link { background: rgba(0,0,0,.28) !important; border-radius: 10px 0 0 10px !important; transform: none !important; }
    [data-sidebar-size="sm"] .app-menu .nav-item:hover > a.menu-link span { padding-left: 0 !important; }
    [data-sidebar-size="sm"] .app-menu .nav-item:hover .nav-link span { padding-left: 0 !important; }

    /* Links simples en hover: expandir con texto claro */
    [data-sidebar-size="sm"] .app-menu .nav-item:hover > .nav-link:not(.menu-link) {
        background: linear-gradient(90deg, #8a3500, #c96004) !important;
        border-radius: 10px 0 0 10px !important;
        position: relative;
        width: calc(200px + 70px) !important;
        z-index: 1020;
        justify-content: flex-start !important;
        padding: 11px 16px !important;
        color: #fff !important;
        transform: none !important;
        box-shadow: 4px 0 18px rgba(0,0,0,.25);
    }
    [data-sidebar-size="sm"] .app-menu .nav-item:hover > .nav-link:not(.menu-link) > i { color: #fff !important; }

    /* Hover sobre menu-link activo en colapsado: texto naranja */
    [data-sidebar-size="sm"] .app-menu .nav-item:hover > a.menu-link.active,
    [data-sidebar-size="sm-hover"] .app-menu .nav-item:hover > a.menu-link.active {
        color: #c96004 !important;
        background: rgba(255,255,255,.94) !important;
        box-shadow: 0 3px 14px rgba(0,0,0,.2) !important;
    }
    [data-sidebar-size="sm"] .app-menu .nav-item:hover > a.menu-link.active > i,
    [data-sidebar-size="sm-hover"] .app-menu .nav-item:hover > a.menu-link.active > i {
        color: #c96004 !important;
    }

    /* Flyout para menús con submenú */
    [data-sidebar-size="sm"] .app-menu .nav-item > .menu-dropdown {
        background: linear-gradient(175deg, #7a3000 0%, #b85500 48%, #f47200 100%) !important;
        left: 70px !important;
        margin-left: 0 !important;
        padding: 6px !important;
        border-left: none !important;
        border-radius: 0 12px 12px 0 !important;
        box-shadow: 6px 4px 24px rgba(0,0,0,.3) !important;
    }
    [data-sidebar-size="sm"] .app-menu .nav-item .menu-dropdown .nav-link {
        padding: 9px 14px !important;
        color: rgba(255,255,255,.9) !important;
        border-radius: 8px !important;
        transform: none !important;
        font-size: .835rem !important;
        justify-content: flex-start !important;
        font-weight: 400;
    }
    [data-sidebar-size="sm"] .app-menu .nav-item .menu-dropdown .nav-link span { padding-left: 0 !important; }
    [data-sidebar-size="sm"] .app-menu .nav-item .menu-dropdown .nav-link::before { display: none !important; }
    [data-sidebar-size="sm"] .app-menu .nav-item .menu-dropdown .nav-link:hover { background: rgba(255,255,255,.16) !important; color: #fff !important; }
    [data-sidebar-size="sm"] .app-menu .nav-item .menu-dropdown .nav-link.active {
        background: rgba(255,255,255,.94) !important;
        color: #9a4904 !important;
        font-weight: 700 !important;
    }

    /* ══════════════════════════════════════════
       MODO sm-hover (hover expande el sidebar)
       ══════════════════════════════════════════ */
    [data-sidebar-size="sm-hover"] .app-menu:not(:hover) #navbar-nav .menu-title { display: none !important; }
    [data-sidebar-size="sm-hover"] .app-menu:hover { background: linear-gradient(175deg, #7a3000 0%, #b85500 48%, #f47200 100%) !important; }
    [data-sidebar-size="sm-hover"] .app-menu:hover #navbar-nav .nav-link:hover { background: rgba(0,0,0,.16) !important; transform: none !important; color: #fff !important; }
    [data-sidebar-size="sm-hover"] .app-menu:hover #navbar-nav .menu-dropdown .nav-link:hover { background: rgba(255,255,255,.12) !important; transform: none !important; color: #fff !important; }

    /* ── Scrollbar ── */
    #scrollbar::-webkit-scrollbar { width: 3px; }
    #scrollbar::-webkit-scrollbar-track { background: transparent; }
    #scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.2); border-radius: 4px; }
    #scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,.4); }
</style>

<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Logo para sidebar claro (fallback) -->
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('images/logo-secundario.png') }}" alt="Logo" height="28">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('images/logo-principal.png') }}" alt="Logo" height="36">
            </span>
        </a>
        <!-- Logo blanco para sidebar naranja/oscuro -->
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('images/logo-secundario.png') }}" alt="Logo" height="28">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('images/logo-principal.png') }}" alt="Logo" height="36">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                @if (Auth::check())
                    <img class="rounded header-profile-user"
                        src="{{ Auth::user()->avatar ? URL::asset('images/' . Auth::user()->avatar) : URL::asset('build/images/users/avatar-1.jpg') }}"
                        alt="Header Avatar">
                    <span class="text-start">
                        <span class="d-block fw-medium sidebar-user-name-text">{{ Auth::user()->name }}</span>
                        <span class="d-block fs-14 sidebar-user-name-sub-text"><i
                                class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span
                                class="align-middle">Online</span></span>
                    </span>
                @else
                    <img class="rounded header-profile-user" src="{{ URL::asset('build/images/users/avatar-1.jpg') }}"
                        alt="Header Avatar">
                    <span class="text-start">
                        <span class="d-block fw-medium sidebar-user-name-text">Invitado</span>
                    </span>
                @endif
            </span>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <h6 class="dropdown-header">Welcome {{ Auth::check() ? Auth::user()->name : 'Invitado' }}!</h6>
            <a class="dropdown-item" href="pages-profile"><i
                    class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Profile</span></a>
            <a class="dropdown-item" href="apps-chat"><i
                    class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Messages</span></a>
            <a class="dropdown-item" href="apps-tasks-kanban"><i
                    class="mdi mdi-calendar-check-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Taskboard</span></a>
            <a class="dropdown-item" href="pages-faqs"><i
                    class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Help</span></a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="pages-profile"><i
                    class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Balance :
                    <b>$5971.67</b></span></a>
            <a class="dropdown-item" href="pages-profile-settings"><span
                    class="badge bg-success-subtle text-success mt-1 float-end">New</span><i
                    class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Settings</span></a>
            <a class="dropdown-item" href="auth-lockscreen-basic"><i
                    class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Lock
                    screen</span></a>

            <a class="dropdown-item " href="javascript:void();"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                    class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                    key="t-logout">@lang('translation.logout')</span></a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">

                <!-- ========== DASHBOARD ========== -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" title="Dashboard"
                        class="nav-link {{ isRouteActive('admin.dashboard') ? 'active' : '' }}">
                        <i class="ri-dashboard-line"></i> <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.cuentas-videollamada.index') }}" title="Cuentas Videollamada"
                        class="nav-link {{ isRouteActive('admin.cuentas-videollamada.') ? 'active' : '' }}">
                        <i class="ri-video-line"></i> <span>Cuentas Videollamada</span>
                    </a>
                </li>

                <!-- ========== ACADÉMICO ========== -->
                <li class="menu-title mt-3"><i class="ri-graduation-cap-line"></i> <span>Académico</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ isMenuOpen(['admin.posgrads.', 'admin.ofertas.', 'admin.posgrads.cronograma', 'admin.sedes.']) ? 'active' : '' }}"
                        href="#sidebarAcademico" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ isMenuOpen(['admin.posgrads.', 'admin.ofertas.', 'admin.posgrads.cronograma', 'admin.sedes.']) ? 'true' : 'false' }}"
                        aria-controls="sidebarAcademico">
                        <i class="ri-book-open-line"></i> <span>Gestión Académica</span>
                    </a>
                    <div class="collapse menu-dropdown {{ isMenuOpen(['admin.posgrads.', 'admin.ofertas.', 'admin.posgrads.cronograma', 'admin.sedes.']) ? 'show' : '' }}"
                        id="sidebarAcademico">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.sedes.index') }}" title="Sedes"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.sedes.index' ? 'active' : '' }}">Sedes</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.posgrads.index') }}" title="Posgrados"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.posgrads.index' ? 'active' : '' }}">Posgrados</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.ofertas.index') }}" title="Todas las Ofertas"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.ofertas.index' ? 'active' : '' }}">Todas
                                    las Ofertas</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.posgrads.cronograma.index') }}" title="Cronograma"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.posgrads.cronograma.index' ? 'active' : '' }}">Cronograma</a>
                            </li>



                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ isMenuOpen(['admin.grados-academicos.', 'admin.profesiones.', 'admin.universidades.', 'admin.areas.', 'admin.cuentas-videollamada.', 'admin.tipos.', 'admin.convenios.', 'admin.fases.', 'admin.modalidades.']) ? 'active' : '' }}"
                        href="#sidebarCatalogos" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ isMenuOpen(['admin.grados-academicos.', 'admin.profesiones.', 'admin.universidades.', 'admin.areas.', 'admin.cuentas-videollamada.', 'admin.tipos.', 'admin.convenios.', 'admin.fases.', 'admin.modalidades.']) ? 'true' : 'false' }}"
                        aria-controls="sidebarCatalogos">
                        <i class="ri-folder-2-line"></i> <span>Catálogos</span>
                    </a>
                    <div class="collapse menu-dropdown {{ isMenuOpen(['admin.grados-academicos.', 'admin.profesiones.', 'admin.universidades.', 'admin.areas.', 'admin.cuentas-videollamada.', 'admin.tipos.', 'admin.convenios.', 'admin.fases.', 'admin.modalidades.']) ? 'show' : '' }}"
                        id="sidebarCatalogos">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.grados-academicos.index') }}" title="Grados Académicos"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.grados-academicos.index' ? 'active' : '' }}">Grados
                                    Académicos</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.profesiones.index') }}" title="Profesiones"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.profesiones.index' ? 'active' : '' }}">Profesiones</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.universidades.index') }}" title="Universidades"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.universidades.index' ? 'active' : '' }}">Universidades</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.areas.index') }}" title="Áreas"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.areas.index' ? 'active' : '' }}">Áreas</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.tipos.index') }}" title="Tipos"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.tipos.index' ? 'active' : '' }}">Tipos</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.convenios.index') }}" title="Convenios"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.convenios.index' ? 'active' : '' }}">Convenios</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.fases.index') }}" title="Fases"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.fases.index' ? 'active' : '' }}">Fases</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.modalidades.index') }}" title="Modalidades"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.modalidades.index' ? 'active' : '' }}">Modalidades</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- ========== RECURSOS HUMANOS ========== -->
                <li class="menu-title mt-3"><i class="ri-team-line"></i> <span>Recursos Humanos</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ isMenuOpen(['admin.estudiantes.', 'admin.docentes.', 'admin.trabajadores.', 'admin.personas.', 'admin.cargos.']) ? 'active' : '' }}"
                        href="#sidebarRRHH" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ isMenuOpen(['admin.estudiantes.', 'admin.docentes.', 'admin.trabajadores.', 'admin.personas.', 'admin.cargos.']) ? 'true' : 'false' }}"
                        aria-controls="sidebarRRHH">
                        <i class="ri-user-settings-line"></i> <span>Gestión de Personas</span>
                    </a>
                    <div class="collapse menu-dropdown {{ isMenuOpen(['admin.estudiantes.', 'admin.docentes.', 'admin.trabajadores.', 'admin.personas.', 'admin.cargos.']) ? 'show' : '' }}"
                        id="sidebarRRHH">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.estudiantes.index') }}" title="Estudiantes"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.estudiantes.index' ? 'active' : '' }}">Estudiantes</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.docentes.index') }}" title="Docentes"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.docentes.index' ? 'active' : '' }}">Docentes</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.trabajadores.index') }}" title="Trabajadores"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.trabajadores.index' ? 'active' : '' }}">Trabajadores</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.personas.index') }}" title="Personas"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.personas.index' ? 'active' : '' }}">Personas</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.cargos.index') }}" title="Cargos"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.cargos.index' ? 'active' : '' }}">Cargos</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- ========== ADMINISTRACION ========== -->
                <li class="menu-title mt-3"><i class="ri-settings-3-line"></i> <span>Administración</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ isMenuOpen(['admin.users.', 'admin.roles.', 'admin.permisos.']) ? 'active' : '' }}"
                        href="#sidebarAdmin" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ isMenuOpen(['admin.users.', 'admin.roles.', 'admin.permisos.']) ? 'true' : 'false' }}"
                        aria-controls="sidebarAdmin">
                        <i class="ri-shield-user-line"></i> <span>Seguridad</span>
                    </a>
                    <div class="collapse menu-dropdown {{ isMenuOpen(['admin.users.', 'admin.roles.', 'admin.permisos.']) ? 'show' : '' }}"
                        id="sidebarAdmin">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}" title="Usuarios"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.users.index' ? 'active' : '' }}">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.roles.index') }}" title="Roles"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.roles.index' ? 'active' : '' }}">Roles</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.permisos.index') }}" title="Permisos"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.permisos.index' ? 'active' : '' }}">Permisos</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- ========== CONTABILIDAD ========== -->
                <li class="menu-title mt-3"><i class="ri-wallet-line"></i> <span>Contabilidad</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ isMenuOpen(['admin.conceptos.', 'admin.planes-pagos.', 'admin.contabilidad.', 'admin.comprobantes.', 'admin.bancos.', 'admin.cuentas-bancarias.', 'admin.cajas.', 'admin.estudiantes.']) ? 'active' : '' }}"
                        href="#sidebarContable" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ isMenuOpen(['admin.conceptos.', 'admin.planes-pagos.', 'admin.contabilidad.', 'admin.comprobantes.', 'admin.bancos.', 'admin.cuentas-bancarias.', 'admin.cajas.', 'admin.estudiantes.']) ? 'true' : 'false' }}"
                        aria-controls="sidebarContable">
                        <i class="ri-money-dollar-circle-line"></i> <span>Gestión Financiera</span>
                    </a>
                    <div class="collapse menu-dropdown {{ isMenuOpen(['admin.conceptos.', 'admin.planes-pagos.', 'admin.contabilidad.', 'admin.comprobantes.', 'admin.bancos.', 'admin.cuentas-bancarias.', 'admin.cajas.', 'admin.estudiantes.']) ? 'show' : '' }}"
                        id="sidebarContable">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.estudiantes.buscar') }}" title="Buscar Estudiante"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.estudiantes.buscar' ? 'active' : '' }}">Buscar
                                    Estudiante</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.conceptos.index') }}" title="Conceptos"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.conceptos.index' ? 'active' : '' }}">Conceptos</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.contabilidad.dashboard') }}" title="Contabilidad"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.contabilidad.dashboard' ? 'active' : '' }}">Contabilidad</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.planes-pagos.index') }}" title="Planes de Pago"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.planes-pagos.index' ? 'active' : '' }}">Planes
                                    de Pago</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.contabilidad.deudas-retrasadas') }}"
                                    title="Deudas Retrasadas"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.contabilidad.deudas-retrasadas' ? 'active' : '' }}">Deudas
                                    Retrasadas</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.contabilidad.cuotas-proximas') }}" title="Cuotas Próximas"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.contabilidad.cuotas-proximas' ? 'active' : '' }}">Cuotas
                                    Próximas</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.contabilidad.recibos') }}" title="Recibos"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.contabilidad.recibos' ? 'active' : '' }}">Recibos</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.comprobantes.index') }}" title="Comprobantes de Pago"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.comprobantes.index' ? 'active' : '' }}">Comprobantes
                                    de Pago</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.bancos.index') }}" title="Bancos"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.bancos.index' ? 'active' : '' }}">Bancos</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.cuentas-bancarias.index') }}" title="Cuentas Bancarias"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.cuentas-bancarias.index' ? 'active' : '' }}">Cuentas
                                    Bancarias</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.cajas.index') }}" title="Cajas"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.cajas.index' ? 'active' : '' }}">Cajas</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- ========== CONFIGURACIÓN ========== -->
                <li class="menu-title mt-3"><i class="ri-settings-3-line"></i> <span>Configuración</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ isMenuOpen(['admin.departamentos.', 'admin.sedes.', 'admin.fases.', 'admin.modalidades.']) ? 'active' : '' }}"
                        href="#sidebarConfig" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ isMenuOpen(['admin.departamentos.', 'admin.sedes.', 'admin.fases.', 'admin.modalidades.']) ? 'true' : 'false' }}"
                        aria-controls="sidebarConfig">
                        <i class="ri-map-pin-line"></i> <span>Ubicaciones</span>
                    </a>
                    <div class="collapse menu-dropdown {{ isMenuOpen(['admin.departamentos.', 'admin.sedes.', 'admin.fases.', 'admin.modalidades.']) ? 'show' : '' }}"
                        id="sidebarConfig">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.departamentos.index') }}" title="Departamentos"
                                    class="nav-link {{ Route::currentRouteName() === 'admin.departamentos.index' ? 'active' : '' }}">Departamentos</a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
