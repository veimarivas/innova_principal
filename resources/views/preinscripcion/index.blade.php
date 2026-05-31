<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-inscripción — {{ optional($oferta->programa)->nombre ?? 'Programa' }} — InnovaCiencia Virtual</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,500;1,600&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <style>
        :root {
            --or1: #fc7b04;
            --or2: #e86e00;
            --or3: #c06000;
            --or4: #8a4500;
            --or5: #5c2e00;
            --or6: #2e1600;
            --gold: #c8902a;
            --gold-lt: #e8b84a;
            --cream: #fdf8f2;
            --cream2: #f5ede0;
            --ink: #1c0d00;
            --ink2: #3a1e08;
            --white: #fff;
            --t-light: rgba(255, 255, 255, .88);
            --t-muted: rgba(255, 255, 255, .52);
            --shadow-or: rgba(252, 123, 4, .28);
            --ease: .3s cubic-bezier(.4, 0, .2, 1);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        html {
            scroll-behavior: smooth
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0e0600;
            color: var(--white);
            overflow-x: hidden
        }

        a {
            text-decoration: none;
            color: inherit
        }

        ul {
            list-style: none
        }

        img {
            display: block;
            max-width: 100%
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 1.5rem
        }

        /* HEADER */
        #hdr {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            padding: .9rem 0;
            transition: background .4s, padding .4s, box-shadow .4s;
        }

        #hdr.scrolled {
            background: rgba(14, 6, 0, .96);
            backdrop-filter: blur(16px);
            padding: .6rem 0;
            box-shadow: 0 1px 32px rgba(0, 0, 0, .5);
            border-bottom: 1px solid rgba(252, 123, 4, .12);
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem
        }

.brand {
            display: flex;
            align-items: center;
            gap: .7rem
        }

        .brand img {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            object-fit: contain;
            box-shadow: 0 4px 14px var(--shadow-or);
        }

        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--white);
            line-height: 1.1
        }

        .brand-name small {
            display: block;
            font-family: 'Inter', sans-serif;
            font-size: .58rem;
            font-weight: 500;
            letter-spacing: .08em;
            color: var(--or1);
            text-transform: uppercase;
            margin-top: 2px
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--or1), var(--or2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .95rem;
            box-shadow: 0 4px 14px var(--shadow-or);
        }

        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--white);
            line-height: 1.1
        }

        .brand-name small {
            display: block;
            font-family: 'Inter', sans-serif;
            font-size: .55rem;
            font-weight: 500;
            letter-spacing: .12em;
            color: var(--or1);
            text-transform: uppercase
        }

        /* SCROLL BAR */
        .scroll-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--or1), var(--gold-lt));
            transform: scaleX(0);
            transform-origin: left;
            z-index: 9997
        }

        /* HERO */
        .hero {
            padding: 7.5rem 0 3rem;
            position: relative;
            overflow: hidden
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(252, 123, 4, .13) 0%, transparent 70%);
            pointer-events: none
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            background: rgba(252, 123, 4, .12);
            border: 1px solid rgba(252, 123, 4, .3);
            border-radius: 20px;
            padding: .3rem .9rem;
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--or1);
            margin-bottom: 1.2rem
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.9rem, 3.5vw, 2.8rem);
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: .9rem
        }

        .hero-title span {
            color: var(--or1)
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
            margin-bottom: 1.5rem
        }

        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 20px;
            padding: .28rem .8rem;
            font-size: .72rem;
            color: var(--t-light)
        }

        .hero-chip i {
            color: var(--or1);
            font-size: .8rem
        }

        /* GRID PRINCIPAL */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2.5rem;
            padding-bottom: 4rem;
            align-items: start
        }

        @media(max-width:900px) {
            .main-grid {
                grid-template-columns: 1fr
            }
        }

        /* INFO CARD */
        .info-card {
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 14px;
            padding: 1.8rem;
            margin-bottom: 1.5rem
        }

        .info-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 1rem;
            padding-bottom: .65rem;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            display: flex;
            align-items: center;
            gap: .5rem
        }

        .info-card h3 i {
            color: var(--or1)
        }

        /* ── PLAN DESTACADO (cuando el enlace trae plan asignado) ── */
        .plan-destacado {
            background: linear-gradient(135deg, rgba(252, 123, 4, .14) 0%, rgba(200, 144, 42, .08) 100%);
            border: 1px solid rgba(252, 123, 4, .35);
            border-radius: 14px;
            padding: 1.6rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden
        }

        .plan-destacado::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            background: rgba(252, 123, 4, .08);
            border-radius: 50%;
            pointer-events: none
        }

        .plan-destacado-label {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--or1);
            margin-bottom: .4rem
        }

        .plan-destacado-nombre {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 1.1rem
        }

        .concepto-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .65rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, .07)
        }

        .concepto-row:last-child {
            border-bottom: none
        }

        .concepto-nombre {
            font-size: .82rem;
            color: var(--t-muted)
        }

        .concepto-precio {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--white)
        }

        .concepto-cuotas {
            font-size: .68rem;
            color: var(--t-muted);
            margin-left: .4rem
        }

        .concepto-total {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            padding: .8rem 0 0;
            margin-top: .3rem;
            border-top: 1px solid rgba(255, 255, 255, .12)
        }

        .concepto-total .label {
            font-size: .78rem;
            font-weight: 600;
            color: var(--or1)
        }

        .concepto-total .amount {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gold-lt)
        }

        /* PLANES MÚLTIPLES (cuando no hay plan asignado — modo informativo) */
        .plan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: .8rem
        }

        .plan-item {
            background: rgba(252, 123, 4, .06);
            border: 1px solid rgba(252, 123, 4, .18);
            border-radius: 10px;
            padding: 1rem
        }

        .plan-item .concept-name {
            font-size: .72rem;
            color: var(--or1);
            font-weight: 600;
            letter-spacing: .04em;
            margin-bottom: .3rem
        }

        .plan-item .concept-price {
            font-size: 1rem;
            font-weight: 700;
            color: var(--white)
        }

        .plan-item .concept-cuotas {
            font-size: .67rem;
            color: var(--t-muted);
            margin-top: .15rem
        }

        .plan-badge {
            display: inline-block;
            background: rgba(200, 144, 42, .15);
            border: 1px solid rgba(200, 144, 42, .3);
            color: var(--gold-lt);
            font-size: .63rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: .18rem .6rem;
            border-radius: 12px;
            margin-bottom: .7rem
        }

        /* MÓDULOS */
        .modulo-item {
            display: flex;
            align-items: flex-start;
            gap: .8rem;
            padding: .7rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, .06)
        }

        .modulo-item:last-child {
            border-bottom: none
        }

        .mod-num {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: rgba(252, 123, 4, .15);
            border: 1px solid rgba(252, 123, 4, .3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .66rem;
            font-weight: 700;
            color: var(--or1);
            flex-shrink: 0
        }

        .mod-info {
            flex: 1
        }

        .mod-name {
            font-size: .82rem;
            font-weight: 600;
            color: var(--white)
        }

        .mod-docente {
            font-size: .7rem;
            color: var(--t-muted);
            margin-top: .2rem
        }

        /* ASESOR */
        .asesor-card {
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 14px;
            padding: 1.5rem;
            margin-bottom: 1.5rem
        }

        .asesor-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: .9rem
        }

        .asesor-avatar {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            border: 2px solid rgba(252, 123, 4, .35);
            background: rgba(252, 123, 4, .12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: var(--or1);
            flex-shrink: 0;
            overflow: hidden
        }

        .asesor-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .asesor-name {
            font-size: .95rem;
            font-weight: 700;
            color: var(--white)
        }

        .asesor-cargo {
            font-size: .68rem;
            color: var(--or1);
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-top: .1rem
        }

        .asesor-suc {
            font-size: .68rem;
            color: var(--t-muted);
            margin-top: .1rem
        }

        /* FORMULARIO */
        .form-card {
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 14px;
            padding: 1.8rem;
            position: sticky;
            top: 90px
        }

        .form-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: .3rem
        }

        .form-subtitle {
            font-size: .77rem;
            color: var(--t-muted);
            margin-bottom: 1.4rem
        }

        /* Plan resumen en formulario (cuando hay plan asignado) */
        .form-plan-resumen {
            background: rgba(252, 123, 4, .08);
            border: 1px solid rgba(252, 123, 4, .25);
            border-radius: 10px;
            padding: .9rem 1rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: .7rem
        }

        .form-plan-resumen i {
            color: var(--or1);
            font-size: 1.1rem;
            flex-shrink: 0
        }

        .form-plan-resumen strong {
            display: block;
            font-size: .8rem;
            color: var(--white)
        }

        .form-plan-resumen span {
            font-size: .72rem;
            color: var(--t-muted)
        }

        .form-group {
            margin-bottom: .95rem
        }

        .form-label {
            display: block;
            font-size: .7rem;
            font-weight: 600;
            color: var(--t-light);
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-bottom: .38rem
        }

        .form-label .req {
            color: var(--or1)
        }

        .form-control {
            width: 100%;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 8px;
            padding: .62rem .85rem;
            color: var(--white);
            font-size: .85rem;
            font-family: 'Inter', sans-serif;
            transition: border-color var(--ease), background var(--ease);
            outline: none
        }

        .form-control:focus {
            border-color: var(--or1);
            background: rgba(252, 123, 4, .06)
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, .3)
        }

        .form-control.is-invalid {
            border-color: #ef4444
        }

        textarea.form-control {
            resize: vertical;
            min-height: 78px
        }

        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem
        }

        @media(max-width:500px) {
            .row-2 {
                grid-template-columns: 1fr
            }
        }

        .btn-submit {
            width: 100%;
            padding: .88rem;
            background: var(--or1);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: .9rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 20px var(--shadow-or);
            transition: background var(--ease), transform var(--ease);
            margin-top: .5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem
        }

        .btn-submit:hover {
            background: var(--or2);
            transform: translateY(-1px)
        }

        .btn-submit:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none
        }

        /* FOOTER */
        footer {
            text-align: center;
            padding: 2rem 0;
            color: var(--t-muted);
            font-size: .75rem;
            border-top: 1px solid rgba(255, 255, 255, .06)
        }

        footer span {
            color: var(--or1)
        }
    </style>
</head>

<body>
    <div class="scroll-bar" id="scrollBar"></div>

    <header id="hdr">
        <div class="container">
            <nav class="nav">
                <a href="{{ route('welcome') }}" class="brand">
                    <img src="{{ asset('images/logo-secundario.png') }}" alt="InnovaCiencia"
                        style="width:44px;height:44px;border-radius:8px;object-fit:contain;">
                    <div class="brand-name">
                        InnovaCiencia
                        <small>Virtual — Posgrados</small>
                    </div>
                </a>
                <a href="{{ route('welcome') }}"
                    style="font-size:.8rem;color:var(--t-muted);display:flex;align-items:center;gap:.4rem;">
                    <i class="fa-solid fa-arrow-left" style="font-size:.72rem;"></i> Inicio
                </a>
            </nav>
        </div>
    </header>

    {{-- HERO --}}
    <section class="hero">
        <div class="container">
            @if ($enlace->planes_pago_id)
                <div class="hero-badge"
                    style="background:rgba(200,144,42,.12);border-color:rgba(200,144,42,.35);color:var(--gold-lt);">
                    <i class="fa-solid fa-credit-card"></i> Pre-inscripción con plan asignado
                </div>
            @else
                <div class="hero-badge">
                    <i class="fa-solid fa-graduation-cap"></i> Pre-inscripción
                </div>
            @endif

            <h1 class="hero-title">
                {{ optional($oferta->programa)->nombre ?? 'Programa de Posgrado' }}

            </h1>

            <div class="hero-meta">
                @if (optional($oferta->modalidad)->nombre)
                    <span class="hero-chip"><i
                            class="fa-solid fa-laptop-code"></i>{{ $oferta->modalidad->nombre }}</span>
                @endif
                @if (optional($oferta->sucursal)->nombre)
                    <span class="hero-chip"><i
                            class="fa-solid fa-location-dot"></i>{{ $oferta->sucursal->nombre }}{{ optional(optional($oferta->sucursal)->sede)->nombre ? ' — ' . $oferta->sucursal->sede->nombre : '' }}</span>
                @endif
                @if ($oferta->n_modulos)
                    <span class="hero-chip"><i class="fa-solid fa-layer-group"></i>{{ $oferta->n_modulos }}
                        módulos</span>
                @endif
                @if ($oferta->gestion)
                    <span class="hero-chip"><i class="fa-regular fa-calendar-check"></i>Gestión
                        {{ $oferta->gestion }}</span>
                @endif
            </div>
        </div>
    </section>

    <div class="container">
        <div class="main-grid">

            {{-- ── COLUMNA IZQUIERDA ── --}}
            <div>

                {{-- ASESOR --}}
                @php
                    $asesor = $enlace->trabajadoresCargo;
                    $personaAsesor = optional(optional($asesor)->trabajador)->persona;
                    $nombreAsesor = $personaAsesor
                        ? trim(
                            ($personaAsesor->nombres ?? '') .
                                ' ' .
                                ($personaAsesor->apellido_paterno ?? '') .
                                ' ' .
                                ($personaAsesor->apellido_materno ?? ''),
                        )
                        : null;
                @endphp
                @if ($nombreAsesor)
                    <div class="asesor-card">
                        <h3
                            style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:var(--white);margin-bottom:1rem;padding-bottom:.6rem;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:.5rem;">
                            <i class="fa-solid fa-user-tie" style="color:var(--or1);"></i> Tu Asesor de Inscripción
                        </h3>
                        <div class="asesor-header">
                            <div class="asesor-avatar">
                                @if ($personaAsesor->fotografia)
                                    <img src="{{ asset('images/personas/' . $personaAsesor->fotografia) }}"
                                        alt="{{ $nombreAsesor }}"
                                        onerror="this.parentElement.innerHTML='<i class=\'fa-solid fa-user\'></i>'">
                                @else
                                    <i class="fa-solid fa-user"></i>
                                @endif
                            </div>
                            <div>
                                <div class="asesor-name">{{ $nombreAsesor }}</div>
                                <div class="asesor-cargo">{{ optional($asesor->cargo)->nombre ?? 'Asesor' }}</div>
                                @if (optional($asesor->sucursale)->nombre)
                                    <div class="asesor-suc"><i class="fa-solid fa-location-dot"
                                            style="color:var(--or1);font-size:.65rem;margin-right:.2rem;"></i>{{ $asesor->sucursale->nombre }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <p style="font-size:.77rem;color:var(--t-muted);line-height:1.65;">
                            Este enlace fue compartido por tu asesor personal. Una vez enviada tu solicitud, se pondrá
                            en contacto contigo para completar el proceso.
                        </p>
                    </div>
                @endif
                {{-- PLAN DESTACADO (enlace con plan asignado) --}}
                @if ($enlace->planes_pago_id && $conceptosPlan->isNotEmpty())
                    <div class="plan-destacado">
                        <div class="plan-destacado-label">Plan de pago asignado</div>
                        <div class="plan-destacado-nombre">{{ optional($enlace->planesPago)->nombre ?? 'Plan' }}</div>

                        @php $totalBs = 0; @endphp
                        @foreach ($conceptosPlan as $pc)
                            @php $totalBs += $pc->pago_bs; @endphp
                            <div class="concepto-row">
                                <span
                                    class="concepto-nombre">{{ optional($pc->concepto)->nombre ?? 'Concepto' }}</span>
                                <span>
                                    <span class="concepto-precio">Bs.
                                        {{ number_format($pc->pago_bs, 0, '.', ',') }}</span>
                                    @if ($pc->n_cuotas > 1)
                                        <span class="concepto-cuotas">× {{ $pc->n_cuotas }} cuotas</span>
                                    @endif
                                </span>
                            </div>
                        @endforeach

                        <div class="concepto-total">
                            <span class="label"><i class="fa-solid fa-equals"
                                    style="margin-right:.3rem;font-size:.75rem;"></i>Total del programa</span>
                            <span class="amount">Bs. {{ number_format($totalBs, 0, '.', ',') }}</span>
                        </div>
                    </div>
                @endif


                {{-- MÓDULOS --}}
                @if ($oferta->modulos && $oferta->modulos->count() > 0)
                    <div class="info-card">
                        <h3><i class="fa-solid fa-list-check"></i> Módulos del Programa</h3>
                        @foreach ($oferta->modulos->sortBy('orden') as $i => $modulo)
                            <div class="modulo-item">
                                <div class="mod-num">{{ $i + 1 }}</div>
                                <div class="mod-info">
                                    <div class="mod-name">{{ $modulo->nombre }}</div>
                                    @if (optional(optional($modulo->docente)->persona)->nombres)
                                        <div class="mod-docente">
                                            <i class="fa-solid fa-chalkboard-user"
                                                style="color:var(--or1);font-size:.65rem;margin-right:.25rem;"></i>
                                            {{ trim(($modulo->docente->persona->nombres ?? '') . ' ' . ($modulo->docente->persona->apellido_paterno ?? '')) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif



            </div>

            {{-- ── COLUMNA DERECHA: FORMULARIO ── --}}
            <div>
                <div class="form-card" id="formCard">
                    <h2>Solicitud de Pre-inscripción</h2>
                    <p class="form-subtitle">Completa el formulario y nos pondremos en contacto contigo.</p>

                    {{-- Resumen del plan en el formulario (solo cuando hay plan asignado) --}}
                    @if ($enlace->planes_pago_id && optional($enlace->planesPago)->nombre)
                        <div class="form-plan-resumen">
                            <i class="fa-solid fa-circle-check"></i>
                            <div>
                                <strong>Plan asignado: {{ $enlace->planesPago->nombre }}</strong>
                                <span>Se registrará automáticamente con este plan.</span>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div
                            style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);border-radius:8px;padding:.75rem 1rem;margin-bottom:1rem;">
                            <ul style="padding-left:1rem;margin:0;font-size:.78rem;color:#fca5a5;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="preinscripcionForm" method="POST"
                        action="{{ route('preinscripcion.store', ['token' => $token]) }}" novalidate>
                        @csrf

                        <div class="form-group">
                            <label class="form-label">Nombres <span class="req">*</span></label>
                            <input type="text" name="nombres"
                                class="form-control {{ $errors->has('nombres') ? 'is-invalid' : '' }}"
                                placeholder="Tus nombres" value="{{ old('nombres') }}" required>
                        </div>

                        <div class="row-2">
                            <div class="form-group">
                                <label class="form-label">Apellido paterno <span class="req">*</span></label>
                                <input type="text" name="apellido_paterno"
                                    class="form-control {{ $errors->has('apellido_paterno') ? 'is-invalid' : '' }}"
                                    placeholder="Paterno" value="{{ old('apellido_paterno') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Apellido materno</label>
                                <input type="text" name="apellido_materno" class="form-control"
                                    placeholder="Materno" value="{{ old('apellido_materno') }}">
                            </div>
                        </div>

                        <div class="row-2">
                            <div class="form-group">
                                <label class="form-label">Carnet de identidad</label>
                                <input type="text" name="carnet" class="form-control" placeholder="Ej: 12345678"
                                    value="{{ old('carnet') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Teléfono / WhatsApp</label>
                                <input type="text" name="telefono" class="form-control"
                                    placeholder="Ej: 70123456" value="{{ old('telefono') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="email" class="form-control"
                                placeholder="tucorreo@ejemplo.com" value="{{ old('email') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observacion" class="form-control" placeholder="¿Alguna pregunta o comentario?">{{ old('observacion') }}</textarea>
                        </div>

                        <button type="submit" class="btn-submit" id="btnSubmit">
                            <i class="fa-solid fa-paper-plane"></i>
                            Enviar solicitud
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <footer>
        <div class="container">
            <p>© {{ date('Y') }} <span>InnovaCiencia Virtual</span> — Todos los derechos reservados</p>
        </div>
    </footer>

    <script>
        const scrollBarEl = document.getElementById('scrollBar');
        const hdr = document.getElementById('hdr');
        window.addEventListener('scroll', () => {
            const s = window.scrollY,
                h = document.documentElement.scrollHeight - window.innerHeight;
            if (scrollBarEl) scrollBarEl.style.transform = `scaleX(${h > 0 ? s/h : 0})`;
            if (hdr) hdr.classList.toggle('scrolled', s > 50);
        });

        gsap.from('.hero-badge', {
            opacity: 0,
            y: 20,
            duration: .6,
            delay: .1
        });
        gsap.from('.hero-title', {
            opacity: 0,
            y: 30,
            duration: .7,
            delay: .2
        });
        gsap.from('.hero-meta', {
            opacity: 0,
            y: 20,
            duration: .6,
            delay: .35
        });
        gsap.from('.form-card', {
            opacity: 0,
            x: 30,
            duration: .7,
            delay: .3
        });
        gsap.utils.toArray('.info-card, .plan-destacado, .asesor-card').forEach((el, i) => {
            gsap.from(el, {
                opacity: 0,
                y: 25,
                duration: .6,
                delay: .3 + i * .1,
                scrollTrigger: {
                    trigger: el,
                    start: 'top 85%'
                }
            });
        });

        const form = document.getElementById('preinscripcionForm');
        const btn = document.getElementById('btnSubmit');
        if (form && btn) {
            form.addEventListener('submit', function() {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Enviando...';
            });
        }
    </script>
</body>

</html>
