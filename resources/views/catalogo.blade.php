<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo Académico — Innova Ciencia Virtual</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta name="description" content="Explora toda la oferta académica de posgrado de Innova Ciencia Virtual. Filtra por tipo, área, convenio y sede.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* ── RESET & BASE ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --orange:    #fc7b04;
            --orange-dk: #ec6c04;
            --orange-dr: #bc5404;
            --brown:     #743c04;
            --brown-dk:  #391b04;
            --gold:      #c8902a;
            --bg:        #0d0d0d;
            --bg2:       #141414;
            --bg3:       #1c1c1c;
            --bg4:       #222;
            --border:    rgba(255,255,255,.06);
            --border2:   rgba(255,255,255,.11);
            --white:     #fff;
            --t:         #e8e8e8;
            --t-sub:     #b0b0b0;
            --t-muted:   #686868;
            --radius:    .75rem;
            --radius-sm: .45rem;
            --sidebar-w: 272px;
            --hdr-h:     68px;
        }
        html { scroll-behavior: smooth; }
        body {
            background: var(--bg);
            color: var(--t);
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; display: block; }
        button { cursor: pointer; border: none; background: none; font-family: inherit; }

        /* ── LOADING SCREEN ── */
        #loading {
            position: fixed; inset: 0; z-index: 9999;
            background: var(--bg);
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem;
        }
        .ld-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem; font-weight: 700;
            color: var(--orange);
            opacity: 0;
        }
        .ld-bar {
            width: 140px; height: 2px;
            background: rgba(255,255,255,.08);
            border-radius: 2px; overflow: hidden;
        }
        .ld-fill {
            height: 100%; width: 0;
            background: var(--orange);
            animation: ldFill 1s ease forwards .3s;
        }
        @keyframes ldFill { to { width: 100%; } }

        /* ── HEADER ── */
        .hdr {
            position: fixed; top: 0; left: 0; right: 0; z-index: 500;
            height: var(--hdr-h);
            display: flex; align-items: center;
            padding: 0 2rem;
            background: rgba(13,13,13,.85);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
            transition: background .3s, box-shadow .3s;
        }
        .hdr.scrolled {
            background: rgba(13,13,13,.97);
            box-shadow: 0 2px 20px rgba(0,0,0,.5);
        }
        .hdr-inner {
            width: 100%; max-width: 1400px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between; gap: 2rem;
        }
        .hdr-logo {
            display: flex; align-items: center; gap: .65rem;
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem; font-weight: 700; color: var(--white);
            white-space: nowrap;
        }
        .hdr-logo .logo-dot { color: var(--orange); }
        .hdr-logo img { height: 40px; width: 40px; border-radius: 8px; object-fit: contain; }
        .hdr-logo small {
            display: block;
            font-family: 'Inter', sans-serif;
            font-size: .58rem;
            font-weight: 500;
            letter-spacing: .08em;
            color: var(--orange);
            text-transform: uppercase;
        }
        .nav-links {
            display: flex; align-items: center; gap: .2rem;
            list-style: none;
        }
        .nav-links a {
            font-size: .83rem; font-weight: 500; letter-spacing: .02em;
            color: var(--t-sub); padding: .45rem .8rem; border-radius: var(--radius-sm);
            transition: color .2s, background .2s;
        }
        .nav-links a:hover, .nav-links a.active { color: var(--white); background: rgba(255,255,255,.06); }
        .nav-links a.active { color: var(--orange); }
        .hdr-cta {
            display: flex; align-items: center; gap: .75rem;
            flex-shrink: 0;
        }
        .hdr-back {
            font-size: .8rem; font-weight: 500; color: var(--t-sub);
            display: flex; align-items: center; gap: .4rem;
            padding: .4rem .85rem; border-radius: var(--radius-sm);
            border: 1px solid var(--border2);
            transition: color .2s, border-color .2s;
        }
        .hdr-back:hover { color: var(--white); border-color: rgba(255,255,255,.22); }
        .menu-toggle {
            display: none; flex-direction: column; gap: 5px; padding: .4rem;
        }
        .menu-toggle span {
            display: block; width: 22px; height: 2px;
            background: var(--t-sub); border-radius: 2px;
            transition: transform .3s, opacity .3s;
        }

        /* ── HERO ── */
        .cat-hero {
            margin-top: var(--hdr-h);
            background: linear-gradient(135deg, #0d0d0d 0%, #1a0800 60%, #0d0d0d 100%);
            border-bottom: 1px solid var(--border);
            padding: 4rem 2rem 3.5rem;
            position: relative; overflow: hidden;
        }
        .cat-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse 70% 60% at 60% 50%, rgba(252,123,4,.07) 0%, transparent 70%);
            pointer-events: none;
        }
        .cat-hero-inner {
            max-width: 1400px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            gap: 2rem; flex-wrap: wrap;
        }
        .cat-hero-text .eyebrow {
            display: inline-flex; align-items: center; gap: .5rem;
            font-size: .72rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase;
            color: var(--orange); margin-bottom: .75rem;
        }
        .cat-hero-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 700; line-height: 1.2;
            color: var(--white); margin-bottom: .6rem;
        }
        .cat-hero-text h1 span { color: var(--orange); }
        .cat-hero-text p {
            font-size: .9rem; color: var(--t-sub); max-width: 480px;
        }
        .cat-hero-stats {
            display: flex; gap: 2rem; flex-wrap: wrap;
        }
        .hero-stat {
            text-align: center;
        }
        .hero-stat strong {
            display: block;
            font-family: 'Playfair Display', serif;
            font-size: 2rem; font-weight: 700; color: var(--orange);
            line-height: 1;
        }
        .hero-stat span {
            font-size: .75rem; color: var(--t-muted); text-transform: uppercase; letter-spacing: .06em;
        }

        /* ── CATALOG LAYOUT ── */
        .cat-layout {
            max-width: 1400px; margin: 0 auto;
            display: flex; gap: 0; min-height: 70vh;
            padding: 0 2rem 4rem;
        }

        /* ── SIDEBAR ── */
        .cat-sidebar {
            width: var(--sidebar-w);
            flex-shrink: 0;
            padding: 1.75rem 0 2rem;
            position: sticky;
            top: var(--hdr-h);
            height: calc(100vh - var(--hdr-h));
            overflow-y: auto;
            border-right: 1px solid var(--border);
            scrollbar-width: thin;
            scrollbar-color: rgba(252,123,4,.3) transparent;
        }
        .cat-sidebar::-webkit-scrollbar { width: 4px; }
        .cat-sidebar::-webkit-scrollbar-thumb { background: rgba(252,123,4,.3); border-radius: 4px; }
        .sidebar-inner { padding-right: 1.5rem; }

        /* Search */
        .sidebar-search {
            position: relative; margin-bottom: 1.5rem;
        }
        .sidebar-search input {
            width: 100%;
            background: var(--bg3);
            border: 1px solid var(--border2);
            border-radius: var(--radius-sm);
            color: var(--t);
            font-family: inherit; font-size: .83rem;
            padding: .6rem .9rem .6rem 2.4rem;
            outline: none;
            transition: border-color .2s;
        }
        .sidebar-search input::placeholder { color: var(--t-muted); }
        .sidebar-search input:focus { border-color: var(--orange); }
        .sidebar-search .search-icon {
            position: absolute; left: .75rem; top: 50%; transform: translateY(-50%);
            color: var(--t-muted); font-size: .8rem; pointer-events: none;
        }

        /* Filter group */
        .filter-group { margin-bottom: 1.6rem; }
        .filter-group-title {
            font-size: .68rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: var(--t-muted);
            margin-bottom: .75rem; display: flex; align-items: center; gap: .5rem;
        }
        .filter-group-title::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }
        .filter-pills { display: flex; flex-wrap: wrap; gap: .4rem; }
        .filter-pill {
            font-size: .75rem; font-weight: 500;
            color: var(--t-sub);
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: .3rem .8rem;
            transition: color .18s, background .18s, border-color .18s;
            cursor: pointer;
        }
        .filter-pill:hover { color: var(--white); border-color: var(--border2); background: var(--bg4); }
        .filter-pill.active {
            color: var(--white);
            background: var(--orange);
            border-color: var(--orange);
        }

        /* Reset */
        .sidebar-reset {
            width: 100%; margin-top: .5rem;
            font-size: .78rem; font-weight: 500; color: var(--t-muted);
            padding: .5rem; border-radius: var(--radius-sm);
            border: 1px dashed var(--border2);
            transition: color .2s, border-color .2s;
            display: flex; align-items: center; justify-content: center; gap: .4rem;
        }
        .sidebar-reset:hover { color: var(--orange); border-color: var(--orange); }

        /* Active filters summary */
        .active-filters-bar {
            margin-bottom: 1rem; display: flex; flex-wrap: wrap; gap: .4rem; align-items: center;
        }
        .af-label { font-size: .72rem; color: var(--t-muted); margin-right: .2rem; }
        .af-tag {
            display: inline-flex; align-items: center; gap: .35rem;
            font-size: .72rem; background: rgba(252,123,4,.12);
            color: var(--orange); border: 1px solid rgba(252,123,4,.3);
            border-radius: 20px; padding: .18rem .65rem;
        }
        .af-tag button {
            font-size: .65rem; color: var(--orange); opacity: .7;
            transition: opacity .15s;
        }
        .af-tag button:hover { opacity: 1; }

        /* ── CONTENT AREA ── */
        .cat-content {
            flex: 1; min-width: 0;
            padding: 1.75rem 0 0 2rem;
        }

        /* Topbar */
        .cat-topbar {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.25rem; flex-wrap: wrap; gap: .75rem;
        }
        .results-info {
            font-size: .83rem; color: var(--t-muted);
        }
        .results-info strong { color: var(--t-sub); }
        .filter-toggle-btn {
            display: none;
            align-items: center; gap: .4rem;
            font-size: .8rem; font-weight: 600; color: var(--t-sub);
            background: var(--bg3); border: 1px solid var(--border2);
            border-radius: var(--radius-sm); padding: .4rem .9rem;
            transition: color .2s, border-color .2s;
        }
        .filter-toggle-btn:hover { color: var(--white); border-color: rgba(255,255,255,.22); }

        /* Sort/view controls */
        .cat-controls {
            display: flex; align-items: center; gap: .6rem;
        }
        .sort-select {
            font-size: .78rem; color: var(--t-sub);
            background: var(--bg3); border: 1px solid var(--border2);
            border-radius: var(--radius-sm); padding: .35rem .7rem;
            outline: none; cursor: pointer;
            transition: border-color .2s;
        }
        .sort-select:focus { border-color: var(--orange); }

        /* Cards grid */
        .cat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.25rem;
        }

        /* ── OFFER CARD ── */
        .oferta-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            display: flex; flex-direction: column;
            transition: transform .25s, box-shadow .25s, border-color .25s;
        }
        .oferta-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,.5);
            border-color: rgba(252,123,4,.25);
        }
        .card-img {
            position: relative; height: 170px; overflow: hidden;
            background: var(--bg3);
        }
        .card-img img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform .4s ease;
        }
        .oferta-card:hover .card-img img { transform: scale(1.04); }
        .badge-tipo {
            position: absolute; top: .65rem; left: .65rem;
            font-size: .65rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase;
            background: var(--orange); color: var(--white);
            padding: .2rem .6rem; border-radius: 20px;
        }
        .badge-fase {
            position: absolute; top: .65rem; right: .65rem;
            font-size: .65rem; font-weight: 600;
            background: rgba(0,0,0,.65); backdrop-filter: blur(6px);
            color: var(--t-sub); padding: .2rem .6rem; border-radius: 20px;
            border: 1px solid var(--border2);
        }
        .card-convenio-img {
            position: absolute; bottom: .5rem; right: .5rem;
            background: rgba(255,255,255,.95);
            border-radius: 6px;
            padding: .2rem .4rem;
            height: 32px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,.15);
        }
        .card-convenio-img img {
            max-height: 24px;
            max-width: 50px;
            object-fit: contain;
        }
        .card-body {
            flex: 1; display: flex; flex-direction: column;
            padding: 1.1rem 1.1rem 1rem;
        }
        .card-meta-top {
            display: flex; flex-wrap: wrap; gap: .35rem; margin-bottom: .65rem;
        }
        .tag-area, .tag-convenio {
            display: inline-flex; align-items: center; gap: .3rem;
            font-size: .67rem; font-weight: 500;
            padding: .18rem .6rem; border-radius: 20px;
        }
        .tag-area {
            background: rgba(200,144,42,.1); color: var(--gold);
            border: 1px solid rgba(200,144,42,.2);
        }
        .tag-convenio {
            background: rgba(252,123,4,.08); color: var(--orange-dk);
            border: 1px solid rgba(252,123,4,.18);
        }
        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: .98rem; font-weight: 700; line-height: 1.3;
            color: var(--white); margin-bottom: .7rem;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        .card-info {
            display: flex; flex-direction: column; gap: .3rem;
            margin-bottom: .9rem;
        }
        .info-item {
            display: flex; align-items: center; gap: .45rem;
            font-size: .74rem; color: var(--t-muted);
        }
        .info-item i { color: var(--orange-dr); font-size: .7rem; flex-shrink: 0; width: 12px; text-align: center; }
        .card-footer {
            margin-top: auto;
            padding-top: .85rem;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between; gap: .5rem;
        }
        .card-price { display: flex; flex-direction: column; }
        .price-amount {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem; font-weight: 700; color: var(--orange);
            line-height: 1;
        }
        .price-label { font-size: .65rem; color: var(--t-muted); margin-top: .15rem; }
        .price-consult { font-size: .78rem; color: var(--t-muted); font-style: italic; }
        .btn-detail {
            display: inline-flex; align-items: center; gap: .4rem;
            font-size: .75rem; font-weight: 600; white-space: nowrap;
            color: var(--white);
            background: var(--orange);
            padding: .45rem 1rem; border-radius: var(--radius-sm);
            transition: background .2s, transform .15s;
        }
        .btn-detail:hover { background: var(--orange-dk); transform: scale(1.02); }
        .btn-detail i { font-size: .65rem; transition: transform .2s; }
        .btn-detail:hover i { transform: translateX(3px); }

        /* ── EMPTY / NO RESULTS ── */
        .no-results {
            grid-column: 1 / -1;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 1rem; padding: 4rem 2rem; text-align: center;
        }
        .no-results i { font-size: 2.5rem; color: var(--t-muted); }
        .no-results h3 { font-family: 'Playfair Display', serif; font-size: 1.3rem; color: var(--t-sub); }
        .no-results p { font-size: .85rem; color: var(--t-muted); max-width: 380px; }
        .no-results .btn-reset {
            font-size: .8rem; font-weight: 600; color: var(--orange);
            border: 1px solid rgba(252,123,4,.4); border-radius: var(--radius-sm);
            padding: .5rem 1.2rem; transition: background .2s;
        }
        .no-results .btn-reset:hover { background: rgba(252,123,4,.1); }

        /* ── SIDEBAR MOBILE OVERLAY ── */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0; z-index: 400;
            background: rgba(0,0,0,.7); backdrop-filter: blur(4px);
        }

        /* ── FOOTER ── */
        .cat-footer {
            background: var(--bg2);
            border-top: 1px solid var(--border);
            padding: 2rem;
            text-align: center;
        }
        .cat-footer-inner {
            max-width: 1400px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 1rem;
        }
        .cat-footer .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 1rem; font-weight: 700; color: var(--white);
        }
        .cat-footer .logo-text span { color: var(--orange); }
        .cat-footer nav { display: flex; gap: 1.5rem; flex-wrap: wrap; justify-content: center; }
        .cat-footer nav a {
            font-size: .78rem; color: var(--t-muted);
            transition: color .2s;
        }
        .cat-footer nav a:hover { color: var(--t-sub); }
        .cat-footer .copy {
            font-size: .72rem; color: var(--t-muted);
            width: 100%; text-align: center; margin-top: .5rem;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .cat-layout { padding: 0 1.25rem 3rem; }
            .cat-sidebar {
                position: fixed; top: 0; left: -100%; bottom: 0;
                width: 300px; z-index: 450;
                background: var(--bg2); border-right: 1px solid var(--border2);
                padding: 1.5rem 1.5rem 2rem;
                transition: left .3s ease;
                height: 100vh;
            }
            .cat-sidebar.open { left: 0; }
            .sidebar-inner { padding-right: 0; }
            .sidebar-overlay.open { display: block; }
            .filter-toggle-btn { display: flex; }
            .cat-content { padding-left: 0; }
            :root { --sidebar-w: 0px; }
        }
        @media (max-width: 768px) {
            .cat-hero { padding: 2.5rem 1.25rem 2rem; }
            .cat-hero-stats { gap: 1.25rem; }
            .hero-stat strong { font-size: 1.6rem; }
            .cat-topbar { flex-direction: row; }
            .cat-grid { grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); }
            .nav-links { display: none; }
            .hdr { padding: 0 1.25rem; }
        }
        @media (max-width: 480px) {
            .cat-grid { grid-template-columns: 1fr; }
            .cat-layout { padding: 0 1rem 2rem; }
        }
    </style>
</head>
<body>

{{-- ── LOADING SCREEN ── --}}
<div id="loading">
    <div class="ld-logo">Innova Ciencia Virtual</div>
    <div class="ld-bar"><div class="ld-fill"></div></div>
</div>

{{-- ── HEADER ── --}}
<header class="hdr" id="hdr">
    <div class="hdr-inner">
        <a href="{{ route('welcome') }}" class="hdr-logo">
            <img src="{{ asset('images/logo-secundario.png') }}" alt="Innova Ciencia"
                style="width:44px;height:44px;border-radius:8px;object-fit:contain;">
            <span>Innova<span class="logo-dot">·</span>Ciencia <small>Virtual — Posgrados</small></span>
        </a>
        <ul class="nav-links">
            <li><a href="{{ route('welcome') }}#inicio">Inicio</a></li>
            <li><a href="{{ route('welcome') }}#nosotros">Nosotros</a></li>
            <li><a href="{{ route('catalogo') }}" class="active">Catálogo</a></li>
            <li><a href="{{ route('welcome') }}#equipo">Equipo</a></li>
            <li><a href="{{ route('welcome') }}#sedes">Sedes</a></li>
            <li><a href="{{ route('welcome') }}#contacto">Contacto</a></li>
        </ul>
        <div class="hdr-cta">
            @if(auth()->check())
                @php
                    $user = auth()->user();
                    $dashboardUrl = $user->role === 'admin' ? url('/admin/dashboard') : ($user->role === 'moodle' ? route('virtual.dashboard') : url('/admin/dashboard'));
                @endphp
                <a href="{{ $dashboardUrl }}" style="font-size:.78rem;font-weight:600;color:var(--orange);padding:.4rem .85rem;border:1px solid rgba(252,123,4,.4);border-radius:var(--radius-sm);transition:background .2s" onmouseover="this.style.background='rgba(252,123,4,.08)'" onmouseout="this.style.background=''"  >
                    Panel Admin
                </a>
            @endif
        </div>
        <button class="menu-toggle" id="menuToggle" aria-label="Menú">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

{{-- ── HERO ── --}}
<section class="cat-hero">
    <div class="cat-hero-inner">
        <div class="cat-hero-text">
            <span class="eyebrow"><i class="fas fa-book-open"></i> Oferta académica</span>
            <h1>Catálogo de <span>Programas</span> de Posgrado</h1>
            <p>Explora toda nuestra oferta de posgrados. Filtra por tipo, área temática, convenio o sede para encontrar el programa ideal para ti.</p>
        </div>
        <div class="cat-hero-stats">
            <div class="hero-stat">
                <strong>{{ $ofertas->count() }}</strong>
                <span>Programas</span>
            </div>
            <div class="hero-stat">
                <strong>{{ $tipos->count() }}</strong>
                <span>Tipos</span>
            </div>
            <div class="hero-stat">
                <strong>{{ $convenios->count() }}</strong>
                <span>Convenios</span>
            </div>
            <div class="hero-stat">
                <strong>{{ $sucursalesDisponibles->count() }}</strong>
                <span>Sedes</span>
            </div>
        </div>
    </div>
</section>

{{-- ── MAIN CATALOG LAYOUT ── --}}
<div class="cat-layout">

    {{-- SIDEBAR --}}
    <aside class="cat-sidebar" id="catSidebar">
        <div class="sidebar-inner">

            {{-- Search --}}
            <div class="sidebar-search">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInput" placeholder="Buscar programa…" autocomplete="off">
            </div>

            {{-- Tipo --}}
            @if($tipos->count())
            <div class="filter-group">
                <div class="filter-group-title"><i class="fas fa-graduation-cap" style="color:var(--orange);font-size:.75rem"></i> Tipo de Programa</div>
                <div class="filter-pills">
                    <button class="filter-pill active" data-group="tipo" data-value="">Todos</button>
                    @foreach($tipos as $tipo)
                        <button class="filter-pill" data-group="tipo" data-value="{{ $tipo->id }}">{{ $tipo->nombre }}</button>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Area --}}
            @if($areas->count())
            <div class="filter-group">
                <div class="filter-group-title"><i class="fas fa-layer-group" style="color:var(--gold);font-size:.75rem"></i> Área Temática</div>
                <div class="filter-pills">
                    <button class="filter-pill active" data-group="area" data-value="">Todas</button>
                    @foreach($areas as $area)
                        <button class="filter-pill" data-group="area" data-value="{{ $area->id }}">{{ $area->nombre }}</button>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Convenio --}}
            @if($convenios->count())
            <div class="filter-group">
                <div class="filter-group-title"><i class="fas fa-handshake" style="color:var(--orange-dr);font-size:.75rem"></i> Convenio</div>
                <div class="filter-pills">
                    <button class="filter-pill active" data-group="convenio" data-value="">Todos</button>
                    @foreach($convenios as $conv)
                        <button class="filter-pill" data-group="convenio" data-value="{{ $conv->id }}">{{ $conv->sigla ?? $conv->nombre }}</button>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Sede --}}
            @if($sucursalesDisponibles->count())
            <div class="filter-group">
                <div class="filter-group-title"><i class="fas fa-map-marker-alt" style="color:var(--orange);font-size:.75rem"></i> Sede</div>
                <div class="filter-pills">
                    <button class="filter-pill active" data-group="sucursal" data-value="">Todas</button>
                    @foreach($sucursalesDisponibles as $suc)
                        <button class="filter-pill" data-group="sucursal" data-value="{{ $suc->id }}">
                            {{ optional($suc->sede)->nombre ? optional($suc->sede)->nombre.' – ' : '' }}{{ $suc->nombre }}
                        </button>
                    @endforeach
                </div>
            </div>
            @endif

            <button class="sidebar-reset" id="resetFilters">
                <i class="fas fa-times-circle"></i> Limpiar filtros
            </button>
        </div>
    </aside>

    {{-- Mobile sidebar overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- CONTENT --}}
    <main class="cat-content">

        {{-- Topbar --}}
        <div class="cat-topbar">
            <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap">
                <p class="results-info">Mostrando <strong id="resultsCount">{{ $ofertas->count() }}</strong> de {{ $ofertas->count() }} programas</p>
                <div id="activeFiltersBar" class="active-filters-bar" style="display:none"></div>
            </div>
            <button class="filter-toggle-btn" id="filterToggle">
                <i class="fas fa-sliders-h"></i> Filtros
            </button>
        </div>

        {{-- Cards grid --}}
        <div class="cat-grid" id="catGrid">
@forelse($ofertas as $oferta)
                @php
                    $planesAgrupados = $oferta->planesConceptos->groupBy(fn($pc) => optional($pc->plan_pago)->nombre ?? 'General');
                    $primerPlanNombre = $planesAgrupados->keys()->first() ?? '';
                    $primerPlan = $planesAgrupados->first();
                    $precio = $primerPlan ? $primerPlan->sum('pago_bs') : 0;
                    $tipoId      = optional(optional($oferta->posgrado)->tipo)->id ?? '';
                    $tipoNombre  = optional(optional($oferta->posgrado)->tipo)->nombre ?? 'Programa';
                    $areaId      = optional(optional($oferta->posgrado)->area)->id ?? '';
                    $areaNombre  = optional(optional($oferta->posgrado)->area)->nombre ?? '';
                    $convenioId  = optional(optional($oferta->posgrado)->convenio)->id ?? '';
                    $convenioNombre = optional(optional($oferta->posgrado)->convenio)->nombre ?? '';
                    $convenioSigla  = optional(optional($oferta->posgrado)->convenio)->sigla ?? $convenioNombre;
                    $sucursalId  = optional($oferta->sucursal)->id ?? '';
                    $sucursalNombre = optional($oferta->sucursal)->nombre ?? 'Sin sede';
                    $duracion = (isset($oferta->posgrado->duracion_numero, $oferta->posgrado->duracion_unidad))
                        ? "{$oferta->posgrado->duracion_numero} {$oferta->posgrado->duracion_unidad}" : null;
                    $searchStr = strtolower(implode(' ', array_filter([
                        optional($oferta->posgrado)->nombre,
                        $areaNombre, $tipoNombre, $convenioNombre, $sucursalNombre
                    ])));
                @endphp
                <div class="oferta-card"
                     data-tipo-id="{{ $tipoId }}"
                     data-area-id="{{ $areaId }}"
                     data-convenio-id="{{ $convenioId }}"
                     data-sucursal-id="{{ $sucursalId }}"
                     data-search="{{ $searchStr }}">
                    <div class="card-img">
                        @if($oferta->portada)
                            <img src="{{ asset('storage/' . $oferta->portada) }}"
                                 alt="{{ optional($oferta->programa)->nombre ?? optional($oferta->posgrado)->nombre }}"
                                 onerror="this.src='https://placehold.co/600x340/1a1a1a/fc7b04?text={{ urlencode($tipoNombre) }}'">
                        @else
                            <img src="https://placehold.co/600x340/1a1a1a/fc7b04?text={{ urlencode($tipoNombre) }}"
                                 alt="{{ optional($oferta->programa)->nombre ?? optional($oferta->posgrado)->nombre }}">
                        @endif
                        <span class="badge-tipo">{{ $tipoNombre }}</span>
                        @if($oferta->fase)
                            <span class="badge-fase">{{ $oferta->fase->nombre }}</span>
                        @endif
                        @if(optional(optional($oferta->posgrado)->convenio)->imagen)
                            <div class="card-convenio-img">
                                <img src="{{ asset('storage/' . $oferta->posgrado->convenio->imagen) }}"
                                     alt="{{ $convenioNombre }}"
                                     onerror="this.parentElement.style.display='none'">
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="card-meta-top">
                            @if($areaNombre)
                                <span class="tag-area"><i class="fas fa-layer-group"></i> {{ $areaNombre }}</span>
                            @endif
                            @if($convenioSigla)
                                <span class="tag-convenio"><i class="fas fa-handshake"></i> {{ $convenioSigla }}</span>
                            @endif
                        </div>
                        <h3 class="card-title">{{ optional($oferta->posgrado)->nombre ?? 'Programa sin nombre' }}</h3>
                        <div class="card-info">
                            <span class="info-item">
                                <i class="fas fa-map-marker-alt"></i> {{ $sucursalNombre }}
                            </span>
                            @if($oferta->modalidad)
                                <span class="info-item">
                                    <i class="fas fa-laptop"></i> {{ $oferta->modalidad->nombre }}
                                </span>
                            @endif
                            @if($duracion)
                                <span class="info-item">
                                    <i class="far fa-clock"></i> {{ $duracion }}
                                </span>
                            @endif
                            @if($oferta->fecha_inicio_programa)
                                <span class="info-item">
                                    <i class="far fa-calendar-alt"></i> Inicio: {{ $oferta->fecha_inicio_programa->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="card-price">
                                @if($precio > 0)
                                    <span class="price-amount">Bs. {{ number_format($precio, 0, ',', '.') }}</span>
                                    <span class="price-label">{{ $primerPlanNombre ?: 'Precio total' }}</span>
                                @else
                                    <span class="price-consult">Consultar precio</span>
                                @endif
                            </div>
                            <a href="{{ route('oferta.detalle', $oferta->id) }}" class="btn-detail">
                                Ver detalles <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="no-results" style="display:flex">
                    <i class="fas fa-book-open"></i>
                    <h3>Sin programas disponibles</h3>
                    <p>Próximamente publicaremos nuestra oferta académica. Contáctanos para más información.</p>
                </div>
            @endforelse

            {{-- No results placeholder (hidden, shown by JS) --}}
            <div class="no-results" id="noResultsMsg" style="display:none">
                <i class="fas fa-search"></i>
                <h3>Sin resultados</h3>
                <p>No se encontraron programas con los filtros seleccionados.</p>
                <button class="btn-reset" onclick="resetFilters()">
                    <i class="fas fa-times"></i> Limpiar filtros
                </button>
            </div>
        </div>
    </main>

</div>

{{-- ── FOOTER ── --}}
<footer class="cat-footer">
    <div class="cat-footer-inner">
        <div class="logo-text">Innova<span>·</span>Ciencia Virtual</div>
        <nav>
            <a href="{{ route('welcome') }}">Inicio</a>
            <a href="{{ route('catalogo') }}">Catálogo</a>
            <a href="{{ route('welcome') }}#equipo">Equipo</a>
            <a href="{{ route('welcome') }}#sedes">Sedes</a>
            <a href="{{ route('welcome') }}#contacto">Contacto</a>
        </nav>
    </div>
    <p class="copy">&copy; {{ date('Y') }} Innova Ciencia Virtual. Todos los derechos reservados.</p>
</footer>

<script>
    /* ── LOADING SCREEN ── */
    (function () {
        const loading = document.getElementById('loading');
        const ldLogo  = loading ? loading.querySelector('.ld-logo') : null;
        if (ldLogo) setTimeout(() => { ldLogo.style.transition = 'opacity .5s'; ldLogo.style.opacity = '1'; }, 200);
        setTimeout(() => {
            if (!loading) return;
            loading.style.transition = 'opacity .6s ease';
            loading.style.opacity = '0';
            setTimeout(() => { loading.style.display = 'none'; }, 650);
        }, 1200);
    })();

    /* ── HEADER SCROLL ── */
    const hdr = document.getElementById('hdr');
    window.addEventListener('scroll', () => {
        hdr && hdr.classList.toggle('scrolled', scrollY > 60);
    }, { passive: true });

    /* ── MOBILE SIDEBAR ── */
    const filterToggle   = document.getElementById('filterToggle');
    const catSidebar     = document.getElementById('catSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function openSidebar()  { catSidebar.classList.add('open'); sidebarOverlay.classList.add('open'); document.body.style.overflow = 'hidden'; }
    function closeSidebar() { catSidebar.classList.remove('open'); sidebarOverlay.classList.remove('open'); document.body.style.overflow = ''; }

    if (filterToggle)   filterToggle.addEventListener('click', openSidebar);
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);

    /* ── FILTER LOGIC ── */
    const activeFilters = { tipo: '', area: '', convenio: '', sucursal: '' };
    const labelMap = {
        tipo: 'Tipo', area: 'Área', convenio: 'Convenio', sucursal: 'Sede'
    };
    const totalCount = document.querySelectorAll('.oferta-card').length;

    document.querySelectorAll('.filter-pill').forEach(pill => {
        pill.addEventListener('click', () => {
            const group = pill.dataset.group;
            const value = pill.dataset.value;
            document.querySelectorAll(`.filter-pill[data-group="${group}"]`).forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            activeFilters[group] = value;
            applyFilters();
            updateActiveBar();
        });
    });

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            applyFilters();
            updateActiveBar();
        });
    }

    document.getElementById('resetFilters')?.addEventListener('click', resetFilters);

    function applyFilters() {
        const searchVal = (searchInput ? searchInput.value : '').toLowerCase().trim();
        let visible = 0;

        document.querySelectorAll('.oferta-card').forEach(card => {
            const matchTipo     = !activeFilters.tipo     || card.dataset.tipoId     === activeFilters.tipo;
            const matchArea     = !activeFilters.area     || card.dataset.areaId     === activeFilters.area;
            const matchConvenio = !activeFilters.convenio || card.dataset.convenioId === activeFilters.convenio;
            const matchSucursal = !activeFilters.sucursal || card.dataset.sucursalId === activeFilters.sucursal;
            const matchSearch   = !searchVal              || (card.dataset.search || '').includes(searchVal);

            const show = matchTipo && matchArea && matchConvenio && matchSucursal && matchSearch;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        const countEl = document.getElementById('resultsCount');
        if (countEl) countEl.textContent = visible;

        const noMsg = document.getElementById('noResultsMsg');
        if (noMsg) noMsg.style.display = (visible === 0 && totalCount > 0) ? 'flex' : 'none';
    }

    function updateActiveBar() {
        const bar = document.getElementById('activeFiltersBar');
        if (!bar) return;
        bar.innerHTML = '';
        let hasActive = false;
        const searchVal = (searchInput ? searchInput.value : '').trim();

        Object.entries(activeFilters).forEach(([group, value]) => {
            if (!value) return;
            hasActive = true;
            const pill = document.querySelector(`.filter-pill[data-group="${group}"][data-value="${value}"]`);
            const label = pill ? pill.textContent.trim() : value;
            const tag = document.createElement('span');
            tag.className = 'af-tag';
            tag.innerHTML = `<span class="af-label">${labelMap[group]}:</span> ${label} <button onclick="clearFilter('${group}')"><i class="fas fa-times"></i></button>`;
            bar.appendChild(tag);
        });

        if (searchVal) {
            hasActive = true;
            const tag = document.createElement('span');
            tag.className = 'af-tag';
            tag.innerHTML = `<span class="af-label">Búsqueda:</span> "${searchVal}" <button onclick="clearSearch()"><i class="fas fa-times"></i></button>`;
            bar.appendChild(tag);
        }

        bar.style.display = hasActive ? 'flex' : 'none';
    }

    function clearFilter(group) {
        activeFilters[group] = '';
        document.querySelectorAll(`.filter-pill[data-group="${group}"]`).forEach(p => p.classList.remove('active'));
        document.querySelector(`.filter-pill[data-group="${group}"][data-value=""]`)?.classList.add('active');
        applyFilters();
        updateActiveBar();
    }

    function clearSearch() {
        if (searchInput) { searchInput.value = ''; }
        applyFilters();
        updateActiveBar();
    }

    function resetFilters() {
        Object.keys(activeFilters).forEach(k => activeFilters[k] = '');
        document.querySelectorAll('.filter-pill').forEach(p => {
            p.classList.toggle('active', p.dataset.value === '');
        });
        if (searchInput) searchInput.value = '';
        applyFilters();
        updateActiveBar();
        closeSidebar();
    }
</script>
</body>
</html>
