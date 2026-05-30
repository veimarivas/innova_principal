<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ optional($oferta->posgrado)->nombre ?? 'Detalle del Programa' }} — Innova Ciencia Virtual</title>
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
        /* ─── TOKENS ─── */
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
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 1.5rem
        }

        /* ─── LOADING ─── */
        #loading {
            position: fixed;
            inset: 0;
            background: #0e0600;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .ld-ring {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 3px solid rgba(252, 123, 4, .15);
            border-top-color: var(--or1);
            animation: spin .85s linear infinite;
            margin-bottom: .8rem
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        .ld-text {
            font-size: .65rem;
            font-weight: 600;
            letter-spacing: .25em;
            color: var(--or1);
            opacity: 0
        }

        /* ─── SCROLL BAR ─── */
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

        /* ─── HEADER ─── */
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

        .brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--or1), var(--or2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            box-shadow: 0 4px 14px var(--shadow-or);
            flex-shrink: 0;
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
            letter-spacing: .12em;
            color: var(--or1);
            text-transform: uppercase
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .6rem 1.4rem;
            border-radius: 4px;
            background: var(--or1);
            color: var(--white);
            font-weight: 600;
            font-size: .85rem;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 18px var(--shadow-or);
            transition: background var(--ease), transform var(--ease), box-shadow var(--ease);
        }

        .btn-primary:hover {
            background: var(--or2);
            transform: translateY(-2px);
            box-shadow: 0 8px 26px var(--shadow-or)
        }

        .btn-outline-w {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .58rem 1.4rem;
            border-radius: 4px;
            border: 1.5px solid rgba(255, 255, 255, .35);
            color: var(--white);
            font-weight: 600;
            font-size: .85rem;
            transition: all var(--ease);
            cursor: pointer;
            background: transparent;
        }

        .btn-outline-w:hover {
            background: rgba(255, 255, 255, .08);
            border-color: rgba(255, 255, 255, .6)
        }

        /* ─── BACK LINK ─── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .78rem;
            font-weight: 500;
            color: var(--t-muted);
            transition: color var(--ease);
        }

        .back-link:hover {
            color: var(--or1)
        }

        /* ─── HERO ─── */
        .hero {
            position: relative;
            min-height: auto;
            padding: 8rem 0 3rem;
            overflow: hidden;
            background: linear-gradient(160deg, #0e0600 0%, #150a04 50%, #0e0600 100%);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(252, 123, 4, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(200, 144, 42, 0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        /* Imagen destacada junto al contenido */
        .hero-with-image {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }

        .hero-text-content {
            max-width: 600px;
        }

        .hero-image-showcase {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.3),
                0 8px 20px rgba(0, 0, 0, 0.2);
            aspect-ratio: 16 / 10;
            background: #1a0c02;
        }

        .hero-image-showcase img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .hero-image-showcase:hover img {
            transform: scale(1.05);
        }

        .hero-image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top,
                    rgba(14, 6, 0, 0.6) 0%,
                    transparent 40%,
                    transparent 60%,
                    rgba(14, 6, 0, 0.3) 100%);
            pointer-events: none;
        }

        .hero-image-badge {
            position: absolute;
            bottom: 1.5rem;
            left: 1.5rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            background: rgba(255, 255, 255, 0.95);
            padding: .5rem 1rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .hero-image-badge i {
            color: var(--or1);
            font-size: .9rem;
        }

        .hero-image-badge span {
            font-size: .8rem;
            font-weight: 600;
            color: var(--ink);
        }

        .hero-convenio-badge {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            background: rgba(255, 255, 255, 0.95);
            padding: .5rem .8rem;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            max-width: 180px;
        }

        .hero-convenio-badge img {
            max-height: 28px;
            max-width: 60px;
            object-fit: contain;
        }

        .hero-convenio-badge span {
            font-size: .72rem;
            font-weight: 600;
            color: var(--ink);
            line-height: 1.2;
        }

        /* Sin imagen - diseño alternativo */
        .hero-simple {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        @media(max-width:900px) {
            .hero-with-image {
                grid-template-columns: 1fr;
            }

            .hero-image-showcase {
                order: -1;
                max-width: 500px;
                margin: 0 auto;
            }

            .hero-text-content {
                max-width: 100%;
                text-align: center;
            }

            .hero-badges {
                justify-content: center;
            }

            .hero-meta {
                justify-content: center;
            }

            .hero-actions {
                justify-content: center;
            }
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: .4rem;
            flex-wrap: wrap;
            font-size: .75rem;
            color: var(--t-muted);
            margin-bottom: 1.6rem;
        }

        .breadcrumb a {
            color: var(--t-muted);
            transition: color .2s
        }

        .breadcrumb a:hover {
            color: var(--or1)
        }

        .breadcrumb span {
            color: rgba(255, 255, 255, .25)
        }

        .hero-badges {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            margin-bottom: 1.2rem
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .28rem .8rem;
            border-radius: 50px;
            font-size: .7rem;
            font-weight: 600;
        }

        .badge-type {
            background: var(--or1);
            color: var(--white)
        }

        .badge-sede {
            background: rgba(255, 255, 255, .12);
            color: var(--t-light);
            border: 1px solid rgba(255, 255, 255, .18)
        }

        .badge-fase {
            background: rgba(200, 144, 42, .2);
            color: var(--gold-lt);
            border: 1px solid rgba(200, 144, 42, .3)
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 4vw, 3.2rem);
            font-weight: 800;
            line-height: 1.15;
            color: var(--white);
            margin-bottom: 1rem;
            max-width: 740px;
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.2rem;
            font-size: .82rem;
            color: var(--t-light);
            margin-bottom: 1.4rem;
        }

        .hero-meta-item {
            display: flex;
            align-items: center;
            gap: .4rem
        }

        .hero-meta-item i {
            color: var(--or1);
            width: 14px;
            text-align: center
        }

        .hero-actions {
            display: flex;
            gap: .8rem;
            flex-wrap: wrap
        }

        /* ─── MAIN LAYOUT ─── */
        .main-layout {
            background: #0e0600;
            padding: 3.5rem 0 5rem;
        }

        .layout-grid {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 2.5rem;
            align-items: start;
        }

        /* ─── LEFT COLUMN ─── */
        .section-block {
            background: rgba(255, 255, 255, .03);
            border: 1px solid rgba(255, 255, 255, .07);
            border-radius: 14px;
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        .section-block:last-child {
            margin-bottom: 0
        }

        .block-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .block-title i {
            color: var(--or1);
            font-size: 1rem
        }

        .block-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, .07)
        }

        .prose {
            font-size: .9rem;
            color: var(--t-light);
            line-height: 1.8;
            text-align: justify
        }

        .prose p+p {
            margin-top: .8rem
        }

        .prose strong {
            color: var(--white);
            font-weight: 600
        }

        /* Modules */
        .modules-list {
            display: flex;
            flex-direction: column;
            gap: .7rem
        }

        .module-item {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            background: rgba(255, 255, 255, .025);
            border: 1px solid rgba(255, 255, 255, .06);
            border-radius: 10px;
            padding: 1rem 1.1rem;
            transition: border-color .25s, background .25s;
        }

        .module-item:hover {
            border-color: rgba(252, 123, 4, .2);
            background: rgba(252, 123, 4, .04)
        }

        .module-num {
            min-width: 36px;
            height: 36px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--or1), var(--or2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            font-weight: 800;
            color: var(--white);
            flex-shrink: 0;
            box-shadow: 0 3px 10px var(--shadow-or);
        }

        .module-body {
            flex: 1;
            min-width: 0
        }

        .module-name {
            font-size: .88rem;
            font-weight: 600;
            color: var(--white);
            margin-bottom: .3rem
        }

        .module-info {
            display: flex;
            flex-wrap: wrap;
            gap: .8rem
        }

        .module-meta {
            font-size: .74rem;
            color: var(--t-muted);
            display: flex;
            align-items: center;
            gap: .3rem
        }

        .module-meta i {
            color: var(--or1);
            font-size: .65rem
        }

        .module-docente {
            font-size: .74rem;
            color: var(--gold-lt);
            display: flex;
            align-items: center;
            gap: .3rem;
            margin-top: .25rem
        }

        .module-docente i {
            font-size: .65rem
        }

        .no-modules {
            text-align: center;
            padding: 2rem;
            color: var(--t-muted);
            font-size: .85rem;
        }

        .no-modules i {
            font-size: 1.5rem;
            display: block;
            margin-bottom: .5rem;
            color: var(--or4)
        }

        /* Directed to */
        .directed-text {
            font-size: .9rem;
            color: var(--t-light);
            line-height: 1.8;
            text-align: justify;
            padding: .8rem 1rem .8rem 1.2rem;
            border-left: 3px solid var(--or1);
            background: rgba(252, 123, 4, .04);
            border-radius: 0 8px 8px 0;
        }

        /* ─── RIGHT COLUMN (SIDEBAR) ─── */
        .sidebar-card {
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .09);
            border-radius: 14px;
            padding: 1.6rem;
            margin-bottom: 1.2rem;
            top: 90px;
        }

        .price-label {
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--t-muted);
            margin-bottom: .3rem
        }

        .price-main {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem;
            font-weight: 800;
            color: var(--or1);
            line-height: 1;
            margin-bottom: .15rem;
        }

        .price-note {
            font-size: .75rem;
            color: var(--t-muted);
            margin-bottom: 1.4rem
        }

        .plan-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
            margin-bottom: 1.2rem
        }

        .plan-tab {
            padding: .3rem .75rem;
            border-radius: 50px;
            font-size: .72rem;
            font-weight: 600;
            border: 1.5px solid rgba(255, 255, 255, .15);
            color: var(--t-muted);
            cursor: pointer;
            transition: all .2s;
            background: transparent;
        }

        .plan-tab.active,
        .plan-tab:hover {
            background: var(--or1);
            border-color: var(--or1);
            color: var(--white);
        }

        .plan-detail {
            display: none
        }

        .plan-detail.active {
            display: block
        }

        .concept-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .55rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
        }

        .concept-row:last-child {
            border-bottom: none
        }

        .concept-name {
            font-size: .8rem;
            color: var(--t-light)
        }

        .concept-val {
            font-size: .9rem;
            font-weight: 700;
            color: var(--white)
        }

        .concept-cuotas {
            font-size: .7rem;
            color: var(--t-muted);
            display: block;
            text-align: right
        }

        .sidebar-cta {
            display: flex;
            flex-direction: column;
            gap: .7rem;
            margin-top: 1.2rem
        }

        .sidebar-cta .btn-primary {
            justify-content: center;
            padding: .75rem
        }

        .sidebar-cta .btn-outline-w {
            justify-content: center;
            padding: .73rem
        }

        /* Info card */
        .info-rows {
            display: flex;
            flex-direction: column;
            gap: .1rem
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: .6rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
            gap: .5rem;
        }

        .info-row:last-child {
            border-bottom: none
        }

        .info-key {
            font-size: .76rem;
            color: var(--t-muted);
            display: flex;
            align-items: center;
            gap: .4rem;
            flex-shrink: 0
        }

        .info-key i {
            color: var(--or1);
            width: 13px;
            text-align: center
        }

        .info-val {
            font-size: .82rem;
            font-weight: 600;
            color: var(--white);
            text-align: right
        }

        /* Convenio card */
        .convenio-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .09);
            border-radius: 14px;
            padding: 1.2rem 1.4rem;
            margin-bottom: 1.2rem;
        }

        .convenio-logo-wrap {
            width: 70px;
            height: 50px;
            background: rgba(255, 255, 255, .07);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: .4rem;
            flex-shrink: 0;
        }

        .convenio-logo-wrap img {
            max-height: 38px;
            object-fit: contain;
            filter: brightness(0) invert(1);
            opacity: .8
        }

        .convenio-info h4 {
            font-size: .85rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: .15rem
        }

        .convenio-info p {
            font-size: .75rem;
            color: var(--t-muted)
        }

        /* ─── RELATED ─── */
        .related-section {
            background: var(--cream);
            padding: 4rem 0 5rem
        }

        .related-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: .4rem;
        }

        .related-title span {
            color: var(--or1)
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.4rem;
            margin-top: 2rem
        }

        .rel-card {
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, .07);
            display: flex;
            flex-direction: column;
            transition: transform .3s, box-shadow .3s;
        }

        .rel-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 36px rgba(92, 46, 0, .13)
        }

        .rel-img {
            height: 160px;
            overflow: hidden;
            background: #f0e8dc;
            position: relative
        }

        .rel-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s
        }

        .rel-card:hover .rel-img img {
            transform: scale(1.05)
        }

        .rel-type {
            position: absolute;
            top: .6rem;
            left: .6rem;
            background: var(--or1);
            color: var(--white);
            font-size: .62rem;
            font-weight: 700;
            padding: .22rem .55rem;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .rel-body {
            padding: 1.1rem;
            flex: 1;
            display: flex;
            flex-direction: column
        }

        .rel-sede {
            font-size: .7rem;
            font-weight: 600;
            color: var(--or3);
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .3rem;
            display: flex;
            align-items: center;
            gap: .25rem
        }

        .rel-name {
            font-family: 'Playfair Display', serif;
            font-size: .92rem;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.3;
            margin-bottom: .5rem;
            flex: 1
        }

        .rel-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: .7rem;
            border-top: 1px solid rgba(0, 0, 0, .06)
        }

        .rel-price {
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--or1)
        }

        .rel-btn {
            font-size: .75rem;
            font-weight: 600;
            color: var(--or1);
            display: inline-flex;
            align-items: center;
            gap: .25rem;
            transition: gap .2s;
        }

        .rel-btn:hover {
            gap: .45rem
        }

        /* ─── FOOTER ─── */
        .mini-footer {
            background: #080400;
            border-top: 1px solid rgba(252, 123, 4, .1);
            padding: 2rem 0;
            text-align: center;
        }

        .mini-footer-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: .8rem
        }

        .mini-footer p {
            font-size: .78rem;
            color: var(--t-muted)
        }

        .mini-footer a {
            font-size: .78rem;
            color: var(--t-muted);
            transition: color .2s
        }

        .mini-footer a:hover {
            color: var(--or1)
        }

        /* ─── RESPONSIVE ─── */
        @media(max-width:900px) {
            .layout-grid {
                grid-template-columns: 1fr
            }

            .sidebar-card:first-child {
                position: static
            }
        }

        @media(max-width:640px) {
            .hero-title {
                font-size: 1.6rem
            }

            .related-grid {
                grid-template-columns: 1fr
            }

            .hero-actions {
                flex-direction: column;
                gap: .6rem
            }

            .hero-actions a {
                justify-content: center
            }
        }
    </style>
</head>

<body>

    <!-- Loading -->
    <div id="loading">
        <div class="ld-ring"></div>
        <span class="ld-text">CARGANDO PROGRAMA</span>
    </div>
    <div class="scroll-bar" id="scrollBar"></div>

    <!-- ═══════════════ HEADER ═══════════════ -->
    <header id="hdr">
        <div class="container">
            <nav class="nav">
                <a href="{{ route('welcome') }}" class="brand">
                    <img src="{{ asset('images/logo-secundario.png') }}" alt="Innova Ciencia"
                        style="width:44px;height:44px;border-radius:8px;object-fit:contain;">
                    <div class="brand-name">
                        Innova Ciencia
                        <small>Virtual — Posgrados</small>
                    </div>
                </a>
                <div style="display:flex;align-items:center;gap:1rem">
                    <a href="{{ route('catalogo') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i> Volver al catálogo
                    </a>
                    @if (Route::has('login'))
                        @if(auth()->check())
                            @php
                                $user = auth()->user();
                                $dashboardUrl = $user->role === 'admin' ? url('/admin/dashboard') : ($user->role === 'moodle' ? route('virtual.dashboard') : url('/admin/dashboard'));
                            @endphp
                            <a href="{{ $dashboardUrl }}" class="btn-primary"
                                style="font-size:.8rem;padding:.5rem 1.1rem">
                                <i class="fas fa-th-large"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary" style="font-size:.8rem;padding:.5rem 1.1rem">
                                <i class="fas fa-sign-in-alt"></i> Ingresar
                            </a>
                        @endif
                    @endif
                </div>
            </nav>
        </div>
    </header>

    <!-- ═══════════════ HERO ═══════════════ -->
    <section class="hero" id="inicio">
        <div class="container">
            <div class="@if ($oferta->portada) hero-with-image @else hero-simple @endif">

                @if ($oferta->portada)
                    <div class="hero-image-showcase">
                        <img src="{{ asset('storage/' . $oferta->portada) }}"
                            alt="{{ optional($oferta->posgrado)->nombre }}">
                        <div class="hero-image-overlay"></div>
                        @if (optional($oferta->posgrado)->tipo)
                            <div class="hero-image-badge">
                                <i class="fas fa-graduation-cap"></i>
                                <span>{{ $oferta->posgrado->tipo->nombre }}</span>
                            </div>
                        @endif
                        @if (optional(optional($oferta->posgrado)->convenio)->imagen)
                            <div class="hero-convenio-badge">
                                <img src="{{ asset('storage/' . $oferta->posgrado->convenio->imagen) }}"
                                    alt="{{ $oferta->posgrado->convenio->nombre }}"
                                    onerror="this.parentElement.style.display='none'">
                                @if (optional($oferta->posgrado)->convenio->nombre)
                                    <span>{{ $oferta->posgrado->convenio->nombre }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                <div class="hero-text-content">
                    <!-- Breadcrumb -->
                    <div class="breadcrumb">
                        <a href="{{ route('welcome') }}"><i class="fas fa-home" style="font-size:.7rem"></i> Inicio</a>
                        <span>/</span>
                        <a href="{{ route('catalogo') }}">Catálogo</a>
                        <span>/</span>
                        <span
                            style="color:rgba(255,255,255,.6)">{{ optional($oferta->posgrado)->nombre ?? 'Programa' }}</span>
                    </div>

                    <!-- Badges -->
                    <div class="hero-badges">
                        @if (optional(optional($oferta->posgrado)->tipo)->nombre && !$oferta->portada)
                            <span class="badge-pill badge-type">
                                <i class="fas fa-graduation-cap"></i>
                                {{ $oferta->posgrado->tipo->nombre }}
                            </span>
                        @endif
                        @if (optional($oferta->sucursal)->nombre)
                            <span class="badge-pill badge-sede">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ optional($oferta->sucursal->sede)->nombre }} — {{ $oferta->sucursal->nombre }}
                            </span>
                        @endif
                        @if ($oferta->fase)
                            <span class="badge-pill badge-fase">
                                <i class="fas fa-circle" style="font-size:.5rem"></i>
                                {{ $oferta->fase->nombre }}
                            </span>
                        @endif
                        @if ($oferta->modalidad)
                            <span class="badge-pill badge-sede">
                                <i class="fas fa-laptop"></i>
                                {{ $oferta->modalidad->nombre }}
                            </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 class="hero-title">{{ optional($oferta->posgrado)->nombre ?? 'Programa sin nombre' }}</h1>

                    <!-- Meta -->
                    <div class="hero-meta">
                        @php
                            $fechaInicio = $oferta->fecha_inicio_programa ? \Carbon\Carbon::parse($oferta->fecha_inicio_programa)->locale('es')->translatedFormat('d \d\e F, Y') : null;
                            $fechaFin = $oferta->fecha_fin_programa ? \Carbon\Carbon::parse($oferta->fecha_fin_programa)->locale('es')->translatedFormat('d \d\e F, Y') : null;
                        @endphp
                        @if($fechaInicio)
                            <div class="hero-meta-item">
                                <i class="far fa-calendar-alt"></i>
                                Inicio: {{ $fechaInicio }}
                            </div>
                        @endif
                        @if($fechaFin)
                            <div class="hero-meta-item">
                                <i class="far fa-calendar-check"></i>
                                Fin: {{ $fechaFin }}
                            </div>
                        @endif
                        @if (isset($oferta->posgrado->duracion_numero) && isset($oferta->posgrado->duracion_unidad))
                            <div class="hero-meta-item">
                                <i class="far fa-clock"></i>
                                {{ $oferta->posgrado->duracion_numero }} {{ $oferta->posgrado->duracion_unidad }}
                            </div>
                        @endif
                        @if ($oferta->n_modulos)
                            <div class="hero-meta-item">
                                <i class="fas fa-book"></i>
                                {{ $oferta->n_modulos }} módulos
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="hero-actions">
                        <a href="{{ route('login') }}" class="btn-primary">
                            <i class="fas fa-file-alt"></i> Inscribirme ahora
                        </a>
                        <a href="#planes" class="btn-outline-w">
                            <i class="fas fa-dollar-sign"></i> Ver planes de pago
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════ MAIN ═══════════════ -->
    <div class="main-layout">
        <div class="container">
            <div class="layout-grid">

                <!-- ── LEFT COLUMN ── -->
                <div class="left-col">

                    {{-- Objetivo / Descripción --}}
                    @if (optional($oferta->posgrado)->objetivo)
                        <div class="section-block" id="objetivo">
                            <h2 class="block-title"><i class="fas fa-bullseye"></i> Objetivo del Programa</h2>
                            <div class="prose">
                                <p>{{ $oferta->posgrado->objetivo }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Dirigido a --}}
                    @if (optional($oferta->posgrado)->dirigido)
                        <div class="section-block">
                            <h2 class="block-title"><i class="fas fa-users"></i> Dirigido a</h2>
                            <div class="directed-text">{{ $oferta->posgrado->dirigido }}</div>
                        </div>
                    @endif

                    {{-- Módulos / Currículo --}}
                    <div class="section-block" id="modulos">
                        <h2 class="block-title"><i class="fas fa-layer-group"></i> Plan de Estudios</h2>

                        @if ($oferta->modulos->isEmpty())
                            <div class="no-modules">
                                <i class="fas fa-book-open"></i>
                                El plan de estudios se publicará próximamente.
                            </div>
                        @else
                            <div class="modules-list">
                                @foreach ($oferta->modulos->sortBy('n_modulo') as $modulo)
                                    <div class="module-item">
                                        <div class="module-num">{{ str_pad($modulo->n_modulo, 2, '0', STR_PAD_LEFT) }}
                                        </div>
                                        <div class="module-body">
                                            <div class="module-name">{{ $modulo->nombre }}</div>
                                            <div class="module-info"></div>
                                            @if ($modulo->docente && $modulo->docente->persona)
                                                <div class="module-docente">
                                                    <i class="fas fa-chalkboard-teacher"></i>
                                                    {{ $modulo->docente->persona->nombres }}
                                                    {{ $modulo->docente->persona->apellido_paterno }}
                                                    {{ $modulo->docente->persona->apellido_materno }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Información adicional --}}
                    @if (optional($oferta->posgrado)->creditaje ||
                            optional($oferta->posgrado)->carga_horaria ||
                            $oferta->cantidad_sesiones ||
                            $oferta->gestion)
                        <div class="section-block">
                            <h2 class="block-title"><i class="fas fa-info-circle"></i> Información Adicional</h2>
                            <div
                                style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem">

                                @if (optional($oferta->posgrado)->creditaje)
                                    <div
                                        style="background:rgba(252,123,4,.06);border:1px solid rgba(252,123,4,.15);border-radius:10px;padding:1rem;text-align:center">
                                        <div
                                            style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:800;color:var(--or1);line-height:1">
                                            {{ $oferta->posgrado->creditaje }}
                                        </div>
                                        <div
                                            style="font-size:.72rem;color:var(--t-muted);text-transform:uppercase;letter-spacing:.06em;margin-top:.2rem">
                                            Créditos</div>
                                    </div>
                                @endif

                                @if (optional($oferta->posgrado)->carga_horaria)
                                    <div
                                        style="background:rgba(252,123,4,.06);border:1px solid rgba(252,123,4,.15);border-radius:10px;padding:1rem;text-align:center">
                                        <div
                                            style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:800;color:var(--or1);line-height:1">
                                            {{ $oferta->posgrado->carga_horaria }}h
                                        </div>
                                        <div
                                            style="font-size:.72rem;color:var(--t-muted);text-transform:uppercase;letter-spacing:.06em;margin-top:.2rem">
                                            Carga Horaria</div>
                                    </div>
                                @endif

                                @if ($oferta->cantidad_sesiones)
                                    <div
                                        style="background:rgba(252,123,4,.06);border:1px solid rgba(252,123,4,.15);border-radius:10px;padding:1rem;text-align:center">
                                        <div
                                            style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:800;color:var(--or1);line-height:1">
                                            {{ $oferta->cantidad_sesiones }}
                                        </div>
                                        <div
                                            style="font-size:.72rem;color:var(--t-muted);text-transform:uppercase;letter-spacing:.06em;margin-top:.2rem">
                                            Sesiones</div>
                                    </div>
                                @endif

                                @if ($oferta->gestion)
                                    <div
                                        style="background:rgba(252,123,4,.06);border:1px solid rgba(252,123,4,.15);border-radius:10px;padding:1rem;text-align:center">
                                        <div
                                            style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:800;color:var(--or1);line-height:1">
                                            {{ $oferta->gestion }}
                                        </div>
                                        <div
                                            style="font-size:.72rem;color:var(--t-muted);text-transform:uppercase;letter-spacing:.06em;margin-top:.2rem">
                                            Gestión</div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endif

                </div>

                <!-- ── RIGHT COLUMN (SIDEBAR) ── -->
                <aside class="right-col">

                    {{-- Planes de pago --}}
                    <div class="sidebar-card" id="planes">
                        @php
                            $precioTotal = $oferta->planesConceptos->sum('pago_bs');
                            $primerPlanConceptos = $planesPago->first();
                            $precioTotalMostrar = $primerPlanConceptos ? $primerPlanConceptos->sum('pago_bs') : $precioTotal;
                        @endphp

                        @if ($precioTotal > 0)
                            <div class="price-label">Precio total</div>
                            <div class="price-main" id="price-main-display">Bs. {{ number_format($precioTotalMostrar, 0, ',', '.') }}</div>
                            <div class="price-note">Total del plan seleccionado</div>
                        @else
                            <div class="price-main"
                                style="font-size:1.2rem;color:var(--t-muted);font-family:'Inter',sans-serif;font-weight:500">
                                Consultar precio</div>
                            <div class="price-note" style="margin-bottom:1rem"></div>
                        @endif

                        @if ($planesPago->isNotEmpty())
                            <div class="plan-tabs">
                                @foreach ($planesPago as $planNombre => $conceptos)
                                    <button class="plan-tab {{ $loop->first ? 'active' : '' }}"
                                        onclick="switchPlan('{{ Str::slug($planNombre) }}', this)">
                                        {{ $planNombre }}
                                    </button>
                                @endforeach
                            </div>

                            @foreach ($planesPago as $planNombre => $conceptos)
                                @php $totalPlan = $conceptos->sum('pago_bs'); @endphp
                                <div class="plan-detail {{ $loop->first ? 'active' : '' }}"
                                    id="plan-{{ Str::slug($planNombre) }}"
                                    data-plan-price="{{ $totalPlan }}">
                                    @foreach ($conceptos as $pc)
                                        <div class="concept-row">
                                            <div class="concept-name">
                                                {{ optional($pc->concepto)->nombre ?? 'Concepto' }}</div>
                                            <div style="text-align:right">
                                                <div class="concept-val">Bs.
                                                    {{ number_format($pc->pago_bs, 0, ',', '.') }}</div>
                                                @if ($pc->n_cuotas && $pc->n_cuotas > 1)
                                                    <span class="concept-cuotas">en {{ $pc->n_cuotas }} cuotas</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @if ($totalPlan > 0)
                                        <div class="concept-row" style="border-top:1px solid rgba(252,123,4,.25);margin-top:.5rem;padding-top:.5rem;font-weight:700;">
                                            <div class="concept-name" style="color:var(--or1);">Total</div>
                                            <div style="text-align:right">
                                                <div class="concept-val" style="color:var(--or1);">Bs. {{ number_format($totalPlan, 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif

                        <div class="sidebar-cta">
                            <a href="{{ route('login') }}" class="btn-primary">
                                <i class="fas fa-file-alt"></i> Inscribirme ahora
                            </a>
                            <a href="https://wa.me/591000000000?text=Hola,%20estoy%20interesado%20en%20el%20programa%20{{ urlencode(optional($oferta->posgrado)->nombre ?? '') }}"
                                target="_blank" class="btn-outline-w"
                                style="border-color:rgba(37,211,102,.4);color:#4ade96">
                                <i class="fab fa-whatsapp"></i> Consultar por WhatsApp
                            </a>
                        </div>
                    </div>

                    {{-- Información clave --}}
                    <div class="sidebar-card">
                        <div class="block-title" style="font-size:1rem;margin-bottom:1rem"><i
                                class="fas fa-clipboard-list"></i> Ficha del Programa</div>
                        <div class="info-rows">


                            @if ($oferta->modalidad)
                                <div class="info-row">
                                    <div class="info-key"><i class="fas fa-laptop"></i> Modalidad</div>
                                    <div class="info-val">{{ $oferta->modalidad->nombre }}</div>
                                </div>
                            @endif

                            @if (optional($oferta->posgrado)->area)
                                <div class="info-row">
                                    <div class="info-key"><i class="fas fa-tag"></i> Área</div>
                                    <div class="info-val">{{ $oferta->posgrado->area->nombre }}</div>
                                </div>
                            @endif

                            @if (isset($oferta->posgrado->duracion_numero) && isset($oferta->posgrado->duracion_unidad))
                                <div class="info-row">
                                    <div class="info-key"><i class="far fa-clock"></i> Duración</div>
                                    <div class="info-val">{{ $oferta->posgrado->duracion_numero }}
                                        {{ $oferta->posgrado->duracion_unidad }}</div>
                                </div>
                            @endif

                            @if ($oferta->n_modulos)
                                <div class="info-row">
                                    <div class="info-key"><i class="fas fa-book"></i> Módulos</div>
                                    <div class="info-val">{{ $oferta->n_modulos }}</div>
                                </div>
                            @endif



                            @if ($oferta->fecha_inicio_programa)
                                <div class="info-row">
                                    <div class="info-key"><i class="fas fa-play-circle"></i> Inicio</div>
                                    <div class="info-val">{{ \Carbon\Carbon::parse($oferta->fecha_inicio_programa)->locale('es')->translatedFormat('d M, Y') }}</div>
                                </div>
                            @endif

                        </div>
                    </div>

                </aside>
            </div>
        </div>
    </div>

    <!-- ═══════════════ RELATED ═══════════════ -->
    @if ($relacionadas->isNotEmpty())
        <section class="related-section">
            <div class="container">
                <p
                    style="font-size:.72rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--or1);margin-bottom:.5rem;display:flex;align-items:center;gap:.4rem">
                    <span
                        style="display:inline-block;width:24px;height:2px;background:var(--or1);border-radius:2px"></span>
                    Otros programas
                </p>
                <h2 class="related-title">Programas <span>Relacionados</span></h2>
                <div class="related-grid">
                    @foreach ($relacionadas as $rel)
                        @php
                            $relPlan =
                                $rel->planesConceptos->first(
                                    fn($pc) => $pc->plan_pago && strtolower($pc->plan_pago->nombre) === 'al contado',
                                ) ?? $rel->planesConceptos->first();
                            $relPrecio = $relPlan ? $relPlan->pago_bs : null;
                        @endphp
                        <div class="rel-card">
                            <div class="rel-img">
                                @if ($rel->portada)
                                    <img src="{{ asset($rel->portada) }}"
                                        alt="{{ optional($rel->posgrado)->nombre }}"
                                        onerror="this.src='https://placehold.co/600x300/2e1600/fc7b04?text=Programa'">
                                @else
                                    <img src="https://placehold.co/600x300/2e1600/fc7b04?text={{ urlencode(optional(optional($rel->posgrado)->tipo)->nombre ?? 'Programa') }}"
                                        alt="{{ optional($rel->posgrado)->nombre }}">
                                @endif
                                @if (optional(optional($rel->posgrado)->tipo)->nombre)
                                    <span class="rel-type">{{ $rel->posgrado->tipo->nombre }}</span>
                                @endif
                            </div>
                            <div class="rel-body">
                                @if (optional($rel->sucursal)->nombre)
                                    <div class="rel-sede"><i class="fas fa-map-marker-alt"></i>
                                        {{ $rel->sucursal->nombre }}</div>
                                @endif
                                <div class="rel-name">{{ optional($rel->posgrado)->nombre ?? 'Programa' }}</div>
                                <div class="rel-footer">
                                    <div class="rel-price">
                                        @if ($relPrecio)
                                            Bs. {{ number_format($relPrecio, 0, ',', '.') }}
                                        @else
                                            <span
                                                style="font-size:.78rem;color:#9a6040;font-family:'Inter',sans-serif;font-weight:400">Consultar</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('oferta.detalle', $rel->id) }}" class="rel-btn">
                                        Ver detalle <i class="fas fa-chevron-right" style="font-size:.7rem"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ═══════════════ FOOTER ═══════════════ -->
    <footer class="mini-footer">
        <div class="container">
            <div class="mini-footer-inner">
                <p>&copy; {{ date('Y') }} Innova Ciencia Virtual. Todos los derechos reservados.</p>
                <div style="display:flex;gap:1.2rem;align-items:center">
                    <a href="{{ route('welcome') }}">Inicio</a>
                    <a href="{{ route('catalogo') }}">Catálogo</a>
                    <a href="{{ route('welcome') }}#contacto">Contacto</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        /* ── Plan tabs ── */
        function switchPlan(slug, btn) {
            document.querySelectorAll('.plan-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.plan-detail').forEach(d => d.classList.remove('active'));
            btn.classList.add('active');
            const detail = document.getElementById('plan-' + slug);
            if (detail) {
                detail.classList.add('active');
                const priceEl = document.getElementById('price-main-display');
                if (priceEl) {
                    const total = parseFloat(detail.dataset.planPrice) || 0;
                    priceEl.textContent = 'Bs. ' + total.toLocaleString('es-BO', { maximumFractionDigits: 0 });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {

            /* ── Loading screen: pure CSS/JS, SIN depender de GSAP ──────
               Si GSAP falla por cualquier motivo, la pantalla igual desaparece. */
            const loading = document.getElementById('loading');
            const ldText = loading ? loading.querySelector('.ld-text') : null;

            if (ldText) {
                setTimeout(() => {
                    ldText.style.transition = 'opacity .5s';
                    ldText.style.opacity = '1';
                }, 300);
            }
            setTimeout(() => {
                if (loading) {
                    loading.style.transition = 'opacity .65s ease';
                    loading.style.opacity = '0';
                    setTimeout(() => {
                        loading.style.display = 'none';
                        boot();
                    }, 680);
                }
            }, 1100);

            /* ── Header: tampoco depende de GSAP ── */
            const hdr = document.getElementById('hdr');
            window.addEventListener('scroll', () => hdr && hdr.classList.toggle('scrolled', scrollY > 60), {
                passive: true
            });

            /* ── Animaciones GSAP (solo si la librería está disponible) ── */
            function boot() {
                if (typeof gsap === 'undefined') return;

                gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

                /* Scroll bar */
                const sb = document.getElementById('scrollBar');
                if (sb) {
                    ScrollTrigger.create({
                        start: 0,
                        end: 'bottom bottom',
                        onUpdate: s => sb.style.transform = `scaleX(${s.progress})`
                    });
                }

                /* Hero entrance */
                const tl = gsap.timeline();
                tl.fromTo('.breadcrumb', {
                        opacity: 0,
                        y: 12
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: .5,
                        ease: 'power3.out'
                    })
                    .fromTo('.hero-badges', {
                        opacity: 0,
                        y: 12
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: .5,
                        ease: 'power3.out'
                    }, '-=.2')
                    .fromTo('.hero-title', {
                        opacity: 0,
                        y: 24
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: .8,
                        ease: 'power3.out'
                    }, '-=.2')
                    .fromTo('.hero-meta', {
                        opacity: 0,
                        y: 16
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: .6,
                        ease: 'power3.out'
                    }, '-=.3')
                    .fromTo('.hero-actions > *', {
                        opacity: 0,
                        y: 14
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: .5,
                        stagger: .1,
                        ease: 'power3.out'
                    }, '-=.2');

                /* Scroll reveal */
                gsap.utils.toArray('.section-block').forEach(el => {
                    gsap.fromTo(el, {
                        opacity: 0,
                        y: 28
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: .7,
                        ease: 'power3.out',
                        scrollTrigger: {
                            trigger: el,
                            start: 'top 88%',
                            once: true
                        }
                    });
                });

                gsap.utils.toArray('.sidebar-card, .convenio-card').forEach((el, i) => {
                    gsap.fromTo(el, {
                        opacity: 0,
                        x: 20
                    }, {
                        opacity: 1,
                        x: 0,
                        duration: .65,
                        delay: i * .08,
                        ease: 'power3.out',
                        scrollTrigger: {
                            trigger: el,
                            start: 'top 88%',
                            once: true
                        }
                    });
                });

                gsap.utils.toArray('.module-item').forEach((el, i) => {
                    gsap.fromTo(el, {
                        opacity: 0,
                        x: -16
                    }, {
                        opacity: 1,
                        x: 0,
                        duration: .5,
                        delay: i * .05,
                        ease: 'power3.out',
                        scrollTrigger: {
                            trigger: el,
                            start: 'top 90%',
                            once: true
                        }
                    });
                });

                gsap.utils.toArray('.rel-card').forEach((el, i) => {
                    gsap.fromTo(el, {
                        opacity: 0,
                        y: 24
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: .6,
                        delay: i * .1,
                        ease: 'power3.out',
                        scrollTrigger: {
                            trigger: el,
                            start: 'top 90%',
                            once: true
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>
