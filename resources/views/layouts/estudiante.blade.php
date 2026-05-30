<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal') | Innova Ciencia</title>
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
        --est-nav-h: 60px;
        --est-accent: #fc7b04;
        --est-accent2: #743c04;
    }
    body { background: var(--vz-body-bg, #f3f3f9); min-height: 100vh; }

    /* ─── Topbar ───────────────────────────────────── */
    .est-nav {
        position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
        height: var(--est-nav-h);
        background: #fff;
        border-bottom: 1px solid #e9ecef;
        display: flex; align-items: center; justify-content: space-between;
        padding: 0 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.06);
    }
    html[data-bs-theme="dark"] .est-nav { background: #1a1d21; border-color: #2c2e33; }
    .est-nav-brand {
        display: flex; align-items: center; gap: .6rem;
        font-weight: 700; font-size: 1rem; color: var(--est-accent2);
        text-decoration: none;
    }
    .est-nav-brand:hover { color: var(--est-accent); }
    .est-nav-right { display: flex; align-items: center; gap: 1rem; }
    .est-nav-user {
        display: flex; align-items: center; gap: .5rem;
        font-size: .85rem; font-weight: 600; color: var(--d-body, #495057);
    }
    .est-nav-user i { font-size: 1.1rem; color: var(--est-accent); }
    .est-btn-salir {
        display: inline-flex; align-items: center; gap: .3rem;
        background: none; border: 1px solid #dee2e6; border-radius: 8px;
        padding: .35rem .85rem; font-size: .82rem; font-weight: 600;
        color: #6c757d; cursor: pointer; transition: all .2s;
    }
    .est-btn-salir:hover { border-color: #dc3545; color: #dc3545; }

    /* ─── Main content ─────────────────────────────── */
    .est-main { margin-top: var(--est-nav-h); min-height: calc(100vh - var(--est-nav-h)); }
    .est-container { max-width: 1200px; margin: 0 auto; padding: 2rem 1.25rem; }

    /* ─── Footer ───────────────────────────────────── */
    .est-footer {
        text-align: center; padding: 1rem;
        font-size: .78rem; color: #adb5bd;
        border-top: 1px solid #e9ecef;
    }
    html[data-bs-theme="dark"] .est-footer { border-color: #2c2e33; }
    </style>
    @yield('css')
</head>
<body>

<nav class="est-nav">
    <a href="{{ route('estudiante.dashboard') }}" class="est-nav-brand">
        <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="Logo" height="28"
             onerror="this.style.display='none'">
        <span>Portal Estudiantil</span>
    </a>
    <div class="est-nav-right">
        <div class="est-nav-user">
            <i class="ri-user-3-line"></i>
            <span>{{ Auth::user()->name }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="est-btn-salir">
                <i class="ri-logout-box-line"></i> Salir
            </button>
        </form>
    </div>
</nav>

<main class="est-main">
    <div class="est-container">
        @yield('content')
    </div>
</main>

<footer class="est-footer">
    &copy; {{ date('Y') }} Innova Ciencia — Portal Estudiantil
</footer>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@yield('script')
</body>
</html>
