<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innova Ciencia Virtual — Posgrados de Excelencia</title>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,500;1,600&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js"></script>
    <style>
        /* ─────────────────────────────────────────────
   TOKENS
───────────────────────────────────────────── */
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
            --shadow-or: rgba(252, 123, 4, .32);
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

        /* ─────────────────────────────────────────────
   UTILITIES
───────────────────────────────────────────── */
        .container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 1.5rem
        }

        .section-pad {
            padding: 5.5rem 0
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--or1);
            margin-bottom: 1rem;
        }

        .eyebrow::before {
            content: '';
            width: 28px;
            height: 2px;
            background: var(--or1);
            border-radius: 2px
        }

        .title-serif {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 3.5vw, 2.8rem);
            font-weight: 700;
            line-height: 1.2;
            color: var(--white);
            margin-bottom: 1rem;
        }

        .title-serif.dark {
            color: var(--ink)
        }

        .title-serif span {
            color: var(--or1)
        }

        .title-serif em {
            font-style: italic;
            color: var(--gold-lt)
        }

        .subtitle {
            font-size: .98rem;
            line-height: 1.75;
            color: var(--t-muted);
            max-width: 560px;
        }

        .subtitle.dark {
            color: #7a4820
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .72rem 1.7rem;
            border-radius: 4px;
            background: var(--or1);
            color: var(--white);
            font-weight: 600;
            font-size: .9rem;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 20px var(--shadow-or);
            transition: background var(--ease), transform var(--ease), box-shadow var(--ease);
        }

        .btn-primary:hover {
            background: var(--or2);
            transform: translateY(-2px);
            box-shadow: 0 8px 28px var(--shadow-or)
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .7rem 1.7rem;
            border-radius: 4px;
            border: 1.5px solid rgba(252, 123, 4, .5);
            color: var(--or1);
            font-weight: 600;
            font-size: .9rem;
            transition: all var(--ease);
            cursor: pointer;
            background: transparent;
        }

        .btn-outline:hover {
            background: rgba(252, 123, 4, .08);
            border-color: var(--or1)
        }

        .btn-outline.dark {
            border-color: rgba(92, 46, 0, .4);
            color: var(--or3)
        }

        .btn-outline.dark:hover {
            background: rgba(252, 123, 4, .06);
            border-color: var(--or3)
        }

        /* ─────────────────────────────────────────────
   LOADING
───────────────────────────────────────────── */
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
            width: 52px;
            height: 52px;
            border-radius: 50%;
            border: 3px solid rgba(252, 123, 4, .15);
            border-top-color: var(--or1);
            animation: spin .85s linear infinite;
            margin-bottom: 1rem;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        .ld-text {
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .25em;
            color: var(--or1);
            opacity: 0
        }

        /* ─────────────────────────────────────────────
   SCROLL PROGRESS
───────────────────────────────────────────── */
        .scroll-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--or1), var(--gold-lt));
            transform: scaleX(0);
            transform-origin: left;
            z-index: 9997;
        }

        /* ─────────────────────────────────────────────
   MOBILE OVERLAY
───────────────────────────────────────────── */
        .mob-overlay {
            position: fixed;
            inset: 0;
            background: rgba(14, 6, 0, .78);
            backdrop-filter: blur(6px);
            z-index: 998;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s;
        }

        .mob-overlay.open {
            opacity: 1;
            pointer-events: all
        }

        /* ─────────────────────────────────────────────
   HEADER
───────────────────────────────────────────── */
        #hdr {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            padding: .9rem 0;
            transition: background .4s, padding .4s, box-shadow .4s, border-color .4s;
            border-bottom: 1px solid transparent;
        }

        #hdr.scrolled {
            background: rgba(14, 6, 0, .96);
            backdrop-filter: blur(16px);
            padding: .6rem 0;
            border-color: rgba(252, 123, 4, .12);
            box-shadow: 0 1px 32px rgba(0, 0, 0, .5);
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
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--or1), var(--or2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 4px 16px var(--shadow-or);
            flex-shrink: 0;
        }

        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--white);
            line-height: 1.1;
        }

        .brand-name small {
            display: block;
            font-family: 'Inter', sans-serif;
            font-size: .62rem;
            font-weight: 500;
            letter-spacing: .12em;
            color: var(--or1);
            text-transform: uppercase;
            margin-top: 1px
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center
        }

        .nav-links a {
            font-size: .85rem;
            font-weight: 500;
            color: var(--t-light);
            position: relative;
            transition: color var(--ease);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 0;
            height: 1.5px;
            background: var(--or1);
            transition: width var(--ease);
        }

        .nav-links a:hover {
            color: var(--or1)
        }

        .nav-links a:hover::after {
            width: 100%
        }

        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 3px
        }

        .hamburger span {
            display: block;
            width: 22px;
            height: 1.5px;
            background: var(--white);
            border-radius: 2px;
            transition: all .3s
        }

        .hamburger.open span:nth-child(1) {
            transform: translateY(6.5px) rotate(45deg)
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
            transform: scaleX(0)
        }

        .hamburger.open span:nth-child(3) {
            transform: translateY(-6.5px) rotate(-45deg)
        }

        /* ─────────────────────────────────────────────
   HERO
───────────────────────────────────────────── */
        .hero {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding: 7rem 0 4rem;
            background:
                radial-gradient(ellipse 80% 60% at 70% 40%, rgba(252, 123, 4, .10) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 15% 80%, rgba(200, 144, 42, .06) 0%, transparent 50%),
                linear-gradient(170deg, #120700 0%, #0a0400 55%, #150800 100%);
        }

        /* Decorative large text */
        .hero-bg-text {
            position: absolute;
            right: -2%;
            top: 50%;
            transform: translateY(-50%);
            font-family: 'Playfair Display', serif;
            font-size: clamp(140px, 18vw, 240px);
            font-weight: 800;
            line-height: 1;
            color: rgba(252, 123, 4, .04);
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        /* Orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            will-change: transform
        }

        .orb-1 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(252, 123, 4, .09) 0%, transparent 70%);
            top: -15%;
            right: 5%;
        }

        .orb-2 {
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(200, 144, 42, .07) 0%, transparent 70%);
            bottom: 5%;
            left: -5%;
        }

        /* Grid lines decoration */
        .hero-grid {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(252, 123, 4, .04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(252, 123, 4, .04) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, black 30%, transparent 100%);
            pointer-events: none;
        }

        .hero-inner {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(252, 123, 4, .1);
            border: 1px solid rgba(252, 123, 4, .25);
            border-radius: 50px;
            padding: .35rem .9rem;
            font-size: .72rem;
            font-weight: 600;
            color: var(--or1);
            letter-spacing: .08em;
            margin-bottom: 1.4rem;
        }

        .hero-tag i {
            font-size: .65rem
        }

        .hero-h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.2rem, 4.5vw, 3.6rem);
            font-weight: 800;
            line-height: 1.13;
            color: var(--white);
            margin-bottom: 1.4rem;
        }

        .hero-h1 em {
            font-style: italic;
            color: var(--gold-lt)
        }

        .hero-h1 strong {
            color: var(--or1);
            font-style: normal
        }

        .hero-desc {
            font-size: 1rem;
            line-height: 1.75;
            color: var(--t-light);
            margin-bottom: 2.2rem;
            max-width: 480px
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap
        }

        /* Right side */
        .hero-right {
            display: flex;
            flex-direction: column;
            gap: 1.2rem
        }

        .hero-card {
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(252, 123, 4, .18);
            border-radius: 14px;
            padding: 1.3rem 1.5rem;
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: border-color .3s, background .3s, transform .3s;
        }

        .hero-card:hover {
            border-color: rgba(252, 123, 4, .38);
            background: rgba(252, 123, 4, .07);
            transform: translateX(4px);
        }

        .hero-card-icon {
            width: 46px;
            height: 46px;
            border-radius: 10px;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--or1), var(--or2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px var(--shadow-or);
        }

        .hero-card-body h4 {
            font-size: .88rem;
            font-weight: 600;
            color: var(--white);
            margin-bottom: .15rem
        }

        .hero-card-body p {
            font-size: .78rem;
            color: var(--t-muted);
            line-height: 1.4
        }

        /* ─────────────────────────────────────────────
   STATS STRIP
───────────────────────────────────────────── */
        .stats-strip {
            background: var(--or1);
            padding: 2.4rem 0;
            position: relative;
            overflow: hidden;
        }

        .stats-strip::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--or2) 0%, var(--or1) 50%, var(--or2) 100%);
        }

        .stats-row {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
        }

        .stat-item {
            text-align: center;
            padding: 0 1rem;
            border-right: 1px solid rgba(255, 255, 255, .2);
        }

        .stat-item:last-child {
            border-right: none
        }

        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--white);
            line-height: 1;
            display: block;
        }

        .stat-label {
            font-size: .78rem;
            font-weight: 600;
            color: rgba(255, 255, 255, .8);
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-top: .3rem;
            display: block
        }

        /* ─────────────────────────────────────────────
   ABOUT / INTRO
───────────────────────────────────────────── */
        .about-section {
            background: var(--cream);
            padding: 5.5rem 0;
            position: relative;
            overflow: hidden;
        }

        .about-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 45%;
            height: 100%;
            background: linear-gradient(135deg, transparent 0%, rgba(252, 123, 4, .04) 100%);
            clip-path: polygon(15% 0, 100% 0, 100% 100%, 0% 100%);
            pointer-events: none;
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center
        }

        .about-text {
            position: relative;
            z-index: 1
        }

        .about-text .title-serif.dark {
            font-size: clamp(1.7rem, 3vw, 2.4rem)
        }

        .pillars {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 2rem
        }

        .pillar {
            background: var(--white);
            border-radius: 10px;
            padding: 1.1rem;
            border: 1px solid rgba(252, 123, 4, .1);
            transition: transform .25s, box-shadow .25s;
        }

        .pillar:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(252, 123, 4, .12)
        }

        .pillar i {
            font-size: 1.1rem;
            color: var(--or1);
            margin-bottom: .5rem;
            display: block
        }

        .pillar h4 {
            font-size: .82rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: .25rem
        }

        .pillar p {
            font-size: .76rem;
            color: var(--or4);
            line-height: 1.5
        }

        .about-visual {
            position: relative;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .av-badge {
            background: var(--white);
            border-radius: 14px;
            padding: 1.6rem;
            text-align: center;
            border: 1px solid rgba(252, 123, 4, .12);
            box-shadow: 0 4px 20px rgba(0, 0, 0, .06);
            transition: transform .25s, box-shadow .25s;
        }

        .av-badge:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(252, 123, 4, .14)
        }

        .av-badge:nth-child(1) {
            grid-column: span 2
        }

        .av-badge .big-num {
            font-family: 'Playfair Display', serif;
            font-size: 3.2rem;
            font-weight: 800;
            color: var(--or1);
            line-height: 1;
        }

        .av-badge span {
            font-size: .78rem;
            font-weight: 600;
            color: var(--or4);
            text-transform: uppercase;
            letter-spacing: .08em
        }

        .av-badge p {
            font-size: .8rem;
            color: var(--or4);
            margin-top: .25rem
        }

        /* ─────────────────────────────────────────────
   PROGRAM TYPES
───────────────────────────────────────────── */
        .types-section {
            background: #0e0600;
            padding: 5.5rem 0
        }

        .types-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.8rem;
            margin-top: .5rem
        }

        .types-grid.two-cols {
            grid-template-columns: repeat(2, 1fr);
            max-width: 900px;
            margin-left: auto;
            margin-right: auto
        }

        .types-grid.single-col {
            grid-template-columns: 1fr;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto
        }

        .type-card {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            background: linear-gradient(145deg, rgba(255, 255, 255, .06) 0%, rgba(255, 255, 255, .02) 100%);
            border: 1px solid rgba(252, 123, 4, .18);
            padding: 2.2rem 2rem;
            backdrop-filter: blur(8px);
            transition: border-color .3s, background .3s, transform .3s;
            cursor: default;
            display: flex;
            flex-direction: column;
        }

        .type-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(252, 123, 4, .08) 0%, transparent 60%);
            opacity: 0;
            transition: opacity .3s;
        }

        .type-card:hover {
            transform: translateY(-8px);
            border-color: rgba(252, 123, 4, .4);
        }

        .type-card:hover::before {
            opacity: 1
        }

        .type-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--or1), var(--or2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: var(--white);
            margin-bottom: 1.4rem;
            box-shadow: 0 8px 20px var(--shadow-or);
            position: relative;
            z-index: 1;
            flex-shrink: 0;
        }

        .type-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: .6rem;
            position: relative;
            z-index: 1;
        }

        .type-card p {
            font-size: .84rem;
            color: var(--t-muted);
            line-height: 1.7;
            position: relative;
            z-index: 1;
            margin-bottom: 1.4rem;
            flex: 1
        }

        .type-link {
            font-size: .82rem;
            font-weight: 600;
            color: var(--or1);
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            position: relative;
            z-index: 1;
            transition: gap .2s;
        }

        .type-link:hover {
            gap: .7rem
        }

        /* ─────────────────────────────────────────────
   PROGRAMS CATALOG
───────────────────────────────────────────── */
        .catalog-section {
            background: var(--cream);
            padding: 5.5rem 0
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
            margin-bottom: 2.5rem;
        }

        .filter-btn {
            padding: .45rem 1.2rem;
            border-radius: 30px;
            border: 1.5px solid rgba(92, 46, 0, .25);
            background: transparent;
            color: var(--or4);
            font-size: .83rem;
            font-weight: 500;
            cursor: pointer;
            transition: all .2s;
            font-family: 'Inter', sans-serif;
            letter-spacing: .02em;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--or1);
            border-color: var(--or1);
            color: var(--white);
        }

        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem
        }

        .prog-card {
            background: var(--white);
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, .06);
            transition: transform .3s, box-shadow .3s;
            display: flex;
            flex-direction: column;
        }

        .prog-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 56px rgba(92, 46, 0, .15)
        }

        .prog-img {
            position: relative;
            height: 200px;
            overflow: hidden;
            background: #f0e8dc
        }

        .prog-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s
        }

        .prog-card:hover .prog-img img {
            transform: scale(1.06)
        }

        .prog-type-badge {
            position: absolute;
            top: .8rem;
            left: .8rem;
            background: var(--or1);
            color: var(--white);
            font-size: .64rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            padding: .3rem .7rem;
            border-radius: 20px;
        }

        .prog-fase-badge {
            position: absolute;
            top: .8rem;
            right: .8rem;
            background: var(--ink2);
            color: rgba(255, 255, 255, .9);
            font-size: .64rem;
            font-weight: 600;
            padding: .3rem .7rem;
            border-radius: 20px;
        }

        .prog-convenio {
            position: absolute;
            bottom: .7rem;
            right: .7rem;
            background: rgba(255, 255, 255, .95);
            border-radius: 8px;
            padding: .25rem .6rem;
            height: 36px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
        }

        .prog-convenio img {
            height: 24px;
            object-fit: contain
        }

        .prog-body {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column
        }

        .prog-sede {
            font-size: .72rem;
            font-weight: 600;
            color: var(--or3);
            text-transform: uppercase;
            letter-spacing: .07em;
            margin-bottom: .5rem;
            display: flex;
            align-items: center;
            gap: .35rem;
        }

        .prog-sede i {
            font-size: .68rem
        }

        .prog-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.35;
            margin-bottom: .6rem;
        }

        .prog-desc {
            font-size: .82rem;
            color: #7a5030;
            line-height: 1.6;
            margin-bottom: 1rem;
            flex: 1
        }

        .prog-meta {
            display: flex;
            flex-direction: column;
            gap: .35rem;
            padding: 1rem 0;
            border-top: 1px solid rgba(0, 0, 0, .06);
            border-bottom: 1px solid rgba(0, 0, 0, .06);
            margin-bottom: 1.1rem;
        }

        .prog-meta-item {
            font-size: .78rem;
            color: #9a6040;
            display: flex;
            align-items: center;
            gap: .45rem
        }

        .prog-meta-item i {
            color: var(--or1);
            width: 14px;
            text-align: center;
            font-size: .8rem
        }

        .prog-footer {
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .prog-price {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--or1);
        }

        .prog-price small {
            font-family: 'Inter', sans-serif;
            font-size: .67rem;
            color: #9a6040;
            display: block;
            font-weight: 400
        }

        .no-prog-msg {
            grid-column: 1/-1;
            text-align: center;
            padding: 3.5rem;
            background: var(--white);
            border-radius: 18px;
            border: 1px dashed rgba(252, 123, 4, .3);
        }

        .no-prog-msg i {
            font-size: 2.5rem;
            color: var(--or1);
            margin-bottom: 1rem;
            display: block
        }

        .no-prog-msg h3 {
            font-size: 1.1rem;
            color: var(--ink);
            margin-bottom: .4rem
        }

        .no-prog-msg p {
            font-size: .85rem;
            color: #7a5030
        }

        /* ─────────────────────────────────────────────
   DIFFERENTIATORS
───────────────────────────────────────────── */
        .why-section {
            background: #0e0600;
            padding: 5.5rem 0
        }

        .why-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.8rem;
            margin-top: .5rem
        }

        .why-card {
            border: 1px solid rgba(252, 123, 4, .12);
            border-radius: 18px;
            padding: 2.4rem 2rem;
            background: rgba(255, 255, 255, .03);
            position: relative;
            overflow: hidden;
            transition: border-color .3s, transform .3s;
        }

        .why-card:hover {
            border-color: rgba(252, 123, 4, .4);
            transform: translateY(-6px)
        }

        .why-num {
            font-family: 'Playfair Display', serif;
            font-size: 4.5rem;
            font-weight: 800;
            color: rgba(252, 123, 4, .08);
            position: absolute;
            top: .5rem;
            right: 1rem;
            line-height: 1;
            pointer-events: none;
            user-select: none;
        }

        .why-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--or1), var(--or2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--white);
            margin-bottom: 1.3rem;
            box-shadow: 0 6px 18px var(--shadow-or);
        }

        .why-card h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: .6rem
        }

        .why-card p {
            font-size: .85rem;
            color: var(--t-muted);
            line-height: 1.7
        }

        /* ─────────────────────────────────────────────
   TEAM
───────────────────────────────────────────── */
        .team-section {
            background: var(--cream2);
            padding: 5.5rem 0
        }

        .carousel-wrap {
            position: relative;
            display: flex;
            align-items: center;
            gap: .8rem
        }

        .carousel-track-wrap {
            flex: 1;
            overflow: hidden;
            cursor: grab
        }

        .carousel-track-wrap:active {
            cursor: grabbing
        }

        .carousel-track {
            display: flex;
            gap: 1.4rem;
            will-change: transform
        }

        .team-card {
            min-width: 230px;
            max-width: 230px;
            border-radius: 16px;
            overflow: hidden;
            background: var(--white);
            border: 1px solid rgba(0, 0, 0, .08);
            flex-shrink: 0;
            transition: transform .3s, box-shadow .3s;
        }

        .team-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 44px rgba(92, 46, 0, .15)
        }

        .team-img {
            width: 100%;
            height: 200px;
            overflow: hidden;
            background: #f0e8dc
        }

        .team-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s
        }

        .team-card:hover .team-img img {
            transform: scale(1.06)
        }

        .team-info {
            padding: 1.2rem
        }

        .team-info h4 {
            font-family: 'Playfair Display', serif;
            font-size: .95rem;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.3;
            margin-bottom: .3rem;
        }

        .team-role {
            font-size: .74rem;
            font-weight: 600;
            color: var(--or1);
            display: block;
            margin-bottom: .5rem
        }

        .team-sede {
            font-size: .73rem;
            color: #8a5030;
            display: flex;
            align-items: center;
            gap: .35rem;
            margin-bottom: .8rem
        }

        .team-sede i {
            color: var(--or3);
            font-size: .68rem
        }

        .team-contacts {
            display: flex;
            gap: .5rem
        }

        .tcb {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            transition: transform .2s, background .2s;
            position: relative;
        }

        .tcb-email {
            background: rgba(252, 123, 4, .1);
            color: var(--or1)
        }

        .tcb-email:hover {
            background: var(--or1);
            color: var(--white);
            transform: scale(1.15)
        }

        .tcb-wa {
            background: rgba(37, 211, 102, .1);
            color: #25d366
        }

        .tcb-wa:hover {
            background: #25d366;
            color: var(--white);
            transform: scale(1.15)
        }

        .car-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1.5px solid rgba(92, 46, 0, .25);
            background: var(--white);
            color: var(--or3);
            cursor: pointer;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            transition: background .2s, border-color .2s, transform .2s;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        .car-btn:hover:not(:disabled) {
            background: var(--or1);
            border-color: var(--or1);
            color: var(--white);
            transform: scale(1.08)
        }

        .car-btn:disabled {
            opacity: .3;
            cursor: not-allowed
        }

/* ─────────────────────────────────────────────
   ALIANZAS ESTRATÉGICAS – DISEÑO AMIGABLE Y PROFESIONAL
 ───────────────────────────────────────────── */
        .partners-section {
            background: linear-gradient(160deg, #0e0600 0%, #150a04 50%, #0e0600 100%);
            padding: 5.5rem 0;
            position: relative;
            overflow: hidden;
        }

        .partners-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(252, 123, 4, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .partners-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(200, 144, 42, 0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .partners-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 4rem;
            align-items: center;
            margin-top: 2.5rem;
            position: relative;
            z-index: 1;
        }

        .partners-text {
            max-width: 520px;
        }

        .partners-text .title-serif {
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            margin-bottom: 1.2rem;
        }

        .partners-text .subtitle {
            font-size: 1rem;
            line-height: 1.8;
            color: var(--t-muted);
        }

        .partners-right {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.2rem;
            align-items: center;
        }

        .partner-logo-card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.95) 0%, rgba(250, 247, 242, 0.9) 100%);
            border-radius: 20px;
            padding: 1.5rem 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 110px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 
                0 4px 16px rgba(0, 0, 0, 0.06),
                0 1px 4px rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(252, 123, 4, 0.08);
        }

        .partner-logo-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(252, 123, 4, 0.05) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.4s;
        }

        .partner-logo-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 
                0 16px 32px rgba(252, 123, 4, 0.15),
                0 4px 12px rgba(252, 123, 4, 0.08);
            border-color: rgba(252, 123, 4, 0.25);
        }

        .partner-logo-card:hover::before {
            opacity: 1;
        }

        .partner-logo-card img {
            max-height: 55px;
            max-width: 90%;
            object-fit: contain;
            transition: transform 0.4s;
            position: relative;
            z-index: 1;
        }

        .partner-logo-card:hover img {
            transform: scale(1.05);
        }

        .fallback-text {
            font-weight: 600;
            color: var(--or3);
            font-size: .8rem;
            text-align: center;
            line-height: 1.4;
            position: relative;
            z-index: 1;
        }

        @media(max-width:768px) {
            .partners-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            .partners-right {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* ─────────────────────────────────────────────
   SEDE SHOWCASE (UNA SOLA SEDE – ELEGANTE Y PROFESIONAL)
───────────────────────────────────────────── */
        .sede-showcase {
            position: relative;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px -12px rgba(28, 13, 0, 0.15),
                0 1px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .sede-showcase:hover {
            transform: translateY(-4px);
            box-shadow: 0 28px 56px -16px rgba(252, 123, 4, 0.2),
                0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .sede-showcase-map {
            position: relative;
            min-height: 360px;
            background: #f5ede0;
            overflow: hidden;
        }

        .sede-showcase-map iframe {
            width: 100%;
            height: 100%;
            border: 0;
            filter: grayscale(20%) contrast(1.1);
            transition: filter 0.4s ease;
        }

        .sede-showcase:hover .sede-showcase-map iframe {
            filter: grayscale(0%) contrast(1);
        }

        .sede-showcase-info {
            padding: 3rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
            position: relative;
            z-index: 2;
        }

        .sede-showcase-parent {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--or1);
            margin-bottom: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .sede-showcase-parent::before {
            content: '';
            width: 24px;
            height: 2px;
            background: var(--or1);
            border-radius: 2px;
        }

        .sede-showcase-name {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.15;
            margin-bottom: 1rem;
            letter-spacing: -0.01em;
        }

        .sede-showcase-address {
            font-size: 1rem;
            color: #7a5030;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .sede-showcase-address i {
            color: var(--or1);
            margin-top: 0.2rem;
            font-size: 0.9rem;
        }

        .sede-showcase-stats {
            display: flex;
            gap: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(0, 0, 0, 0.07);
        }

        .sede-showcase-stat {
            display: flex;
            flex-direction: column;
        }

        .sede-showcase-stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--or1);
            line-height: 1;
        }

        .sede-showcase-stat-label {
            font-size: 0.8rem;
            font-weight: 500;
            color: #9a6040;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            margin-top: 0.3rem;
        }

        /* Decoración de fondo sutil */
        .sede-showcase-badge {
            position: absolute;
            bottom: -20px;
            right: -20px;
            font-size: 8rem;
            color: rgba(252, 123, 4, 0.035);
            pointer-events: none;
            z-index: 1;
            line-height: 1;
        }

        /* Responsive */
        @media(max-width:768px) {
            .sede-showcase {
                grid-template-columns: 1fr;
            }

            .sede-showcase-map {
                min-height: 240px;
                order: 1;
            }

            .sede-showcase-info {
                padding: 2rem 1.8rem;
                order: 2;
            }

            .sede-showcase-name {
                font-size: 1.8rem;
            }
        }

/* Responsive: en móviles se apilan las columnas */
        @media(max-width:768px) {
            .sede-card-full {
                grid-template-columns: 1fr;
            }

            .sede-card-full .sede-map {
                min-height: 240px;
            }

            .sede-info-full {
                padding: 2rem 1.5rem;
            }
        }

        /* ─────────────────────────────────────────────
   PRESENCIA NACIONAL – SEDES (DISEÑO AMIGABLE Y PROFESIONAL)
 ───────────────────────────────────────────── */
        .sedes-section {
            background: var(--cream);
            padding: 5.5rem 0;
            position: relative;
            overflow: hidden;
        }

        .sedes-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 40%;
            height: 100%;
            background: linear-gradient(135deg, transparent 0%, rgba(252, 123, 4, 0.03) 100%);
            clip-path: polygon(20% 0, 100% 0, 100% 100%, 0% 100%);
            pointer-events: none;
        }

        .sedes-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2.5rem;
        }

        .sedes-header .title-serif.dark {
            margin: 0;
        }

        /* Carrusel de sedes */
        .sede-track-wrap {
            flex: 1;
            overflow: hidden;
            cursor: grab;
        }

        .sede-track-wrap:active {
            cursor: grabbing;
        }

        .sede-track {
            display: flex;
            gap: 1.5rem;
            will-change: transform;
        }

        /* Tarjeta de sede amigable y profesional */
        .sede-card {
            min-width: 320px;
            max-width: 320px;
            border-radius: 24px;
            overflow: hidden;
            background: var(--white);
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            flex-shrink: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .sede-card:hover {
            transform: translateY(-8px);
            box-shadow: 
                0 24px 48px rgba(252, 123, 4, 0.12),
                0 8px 24px rgba(0, 0, 0, 0.08);
            border-color: rgba(252, 123, 4, 0.2);
        }

        .sede-map {
            position: relative;
            height: 180px;
            overflow: hidden;
            background: #f0e8dc;
        }

        .sede-map iframe {
            width: 100%;
            height: 100%;
            border: 0;
            filter: grayscale(30%) contrast(1.05);
            transition: filter 0.4s;
        }

        .sede-card:hover .sede-map iframe {
            filter: grayscale(0%) contrast(1);
        }

        /* Badge de ubicación */
        .sede-map-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: var(--white);
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 2;
        }

        .sede-map-badge i {
            color: var(--or1);
            font-size: .9rem;
        }

        .sede-info {
            padding: 1.8rem;
        }

        .sede-parent {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--or1);
            margin-bottom: .6rem;
            background: rgba(252, 123, 4, 0.08);
            padding: .3rem .7rem;
            border-radius: 20px;
        }

        .sede-parent::before {
            content: '';
            width: 16px;
            height: 2px;
            background: var(--or1);
            border-radius: 2px;
        }

        .sede-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.3;
            margin-bottom: .7rem;
        }

        .sede-dir {
            font-size: .85rem;
            color: #7a5030;
            display: flex;
            align-items: flex-start;
            gap: .5rem;
            margin-bottom: 1.2rem;
            line-height: 1.5;
        }

        .sede-dir i {
            color: var(--or1);
            margin-top: 2px;
            font-size: .75rem;
            flex-shrink: 0;
        }

        .sede-stats {
            display: flex;
            gap: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
        }

        .sede-stat {
            display: flex;
            flex-direction: column;
        }

        .sede-stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--or1);
            line-height: 1;
        }

        .sede-stat-lbl {
            font-size: .7rem;
            font-weight: 600;
            color: #9a6040;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-top: .25rem;
        }

        /* Estilos para showcase (una sola sede) ya existe pero lo mejoramos */
        .sede-showcase {
            position: relative;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: var(--white);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 
                0 20px 40px -12px rgba(28, 13, 0, 0.12),
                0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sede-showcase:hover {
            transform: translateY(-6px);
            box-shadow: 
                0 32px 56px -16px rgba(252, 123, 4, 0.18),
                0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .sede-showcase-map {
            position: relative;
            min-height: 380px;
            background: #f5ede0;
            overflow: hidden;
        }

        .sede-showcase-map::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.1) 50%, transparent 100%);
            pointer-events: none;
        }

        .sede-showcase-map iframe {
            width: 100%;
            height: 100%;
            border: 0;
            filter: grayscale(25%) contrast(1.1);
            transition: filter 0.4s;
        }

        .sede-showcase:hover .sede-showcase-map iframe {
            filter: grayscale(0%) contrast(1);
        }

        .sede-showcase-info {
            padding: 3.2rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: var(--white);
            position: relative;
            z-index: 2;
        }

        .sede-showcase-parent {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--or1);
            margin-bottom: .8rem;
            background: linear-gradient(135deg, rgba(252, 123, 4, 0.1) 0%, rgba(252, 123, 4, 0.05) 100%);
            padding: .4rem .9rem;
            border-radius: 25px;
            width: fit-content;
        }

        .sede-showcase-parent::before {
            content: '';
            width: 20px;
            height: 2px;
            background: var(--or1);
            border-radius: 2px;
        }

        .sede-showcase-name {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.1;
            margin-bottom: 1.2rem;
            letter-spacing: -0.01em;
        }

        .sede-showcase-address {
            font-size: 1rem;
            color: #7a5030;
            display: flex;
            align-items: flex-start;
            gap: .7rem;
            margin-bottom: 2.2rem;
            line-height: 1.6;
        }

        .sede-showcase-address i {
            color: var(--or1);
            margin-top: 3px;
            font-size: .85rem;
            flex-shrink: 0;
        }

        .sede-showcase-stats {
            display: flex;
            gap: 2.5rem;
            padding-top: 1.8rem;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
        }

        .sede-showcase-stat {
            display: flex;
            flex-direction: column;
        }

        .sede-showcase-stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--or1);
            line-height: 1;
        }

        .sede-showcase-stat-label {
            font-size: .75rem;
            font-weight: 600;
            color: #9a6040;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-top: 0.4rem;
        }

        /* Decoración de fondo sutil */
        .sede-showcase-badge {
            position: absolute;
            bottom: -25px;
            right: -25px;
            font-size: 10rem;
            color: rgba(252, 123, 4, 0.04);
            pointer-events: none;
            z-index: 1;
            line-height: 1;
        }

        /* Responsive */
        @media(max-width:900px) {
            .sede-card {
                min-width: 280px;
                max-width: 280px;
            }
        }

        @media(max-width:768px) {
            .sedes-section {
                padding: 4rem 0;
            }

            .sede-showcase {
                grid-template-columns: 1fr;
            }

            .sede-showcase-map {
                min-height: 260px;
                order: 1;
            }

            .sede-showcase-info {
                padding: 2rem 1.8rem;
                order: 2;
            }

            .sede-showcase-name {
                font-size: 1.9rem;
            }

            .sede-showcase-stats {
                gap: 1.5rem;
            }

            .sede-card {
                min-width: 260px;
                max-width: 260px;
            }

            .sede-info {
                padding: 1.4rem;
            }

            .sede-name {
                font-size: 1.1rem;
            }
        }

        /* ─────────────────────────────────────────────
   CTA
 ───────────────────────────────────────────── */
        .cta-section {
            position: relative;
            overflow: hidden;
            padding: 6.5rem 0;
            text-align: center;
            background: linear-gradient(150deg, #2e1600 0%, #4a2500 40%, #2e1600 100%);
        }

        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 80% at 80% 50%, rgba(252, 123, 4, .14) 0%, transparent 60%),
                radial-gradient(ellipse 40% 60% at 10% 60%, rgba(200, 144, 42, .10) 0%, transparent 55%);
            pointer-events: none;
        }

        .cta-section::after {
            content: 'POSGRADO';
            position: absolute;
            bottom: -10%;
            left: 50%;
            transform: translateX(-50%);
            font-family: 'Playfair Display', serif;
            font-size: clamp(80px, 12vw, 160px);
            font-weight: 800;
            color: rgba(255, 255, 255, .025);
            white-space: nowrap;
            pointer-events: none;
            user-select: none;
        }

        .cta-inner {
            position: relative;
            z-index: 1
        }

        .cta-inner h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 800;
            color: var(--white);
            margin-bottom: 1rem;
        }

        .cta-inner h2 span {
            color: var(--gold-lt)
        }

        .cta-inner p {
            font-size: 1rem;
            color: var(--t-light);
            line-height: 1.8;
            max-width: 620px;
            margin: 0 auto 2.5rem
        }

        .cta-actions {
            display: flex;
            justify-content: center;
            gap: 1.2rem;
            flex-wrap: wrap
        }

        /* ─────────────────────────────────────────────
   FOOTER
───────────────────────────────────────────── */
        footer {
            background: #1a0d05;
            color: #fff8f0;
            border-top: 1px solid rgba(252, 123, 4, .2);
            padding: 5rem 0 0;
            position: relative;
            overflow: hidden;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: radial-gradient(circle at 50% -20%, rgba(252, 123, 4, 0.05), transparent 70%);
            pointer-events: none;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 4rem;
            padding-bottom: 4rem;
            position: relative;
            z-index: 2;
        }

        .footer-brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-brand-name::before {
            content: '';
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--or1), var(--or2));
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(252, 123, 4, 0.3);
        }

        .footer-brand-name span {
            color: var(--or1);
        }

        .footer-desc {
            font-size: 0.9rem;
            line-height: 1.8;
            color: rgba(255, 248, 240, 0.6);
            margin-bottom: 2rem;
            max-width: 320px;
        }

        .socials {
            display: flex;
            gap: 0.8rem;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 248, 240, 0.7);
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            background: var(--or1);
            color: var(--white);
            border-color: var(--or1);
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(252, 123, 4, 0.3);
        }

        .footer-col h5 {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--or1);
            margin-bottom: 1.8rem;
            position: relative;
        }

        .footer-col h5::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 24px;
            height: 2px;
            background: var(--or1);
            opacity: 0.5;
        }

        .footer-links li {
            margin-bottom: 1rem;
        }

        .footer-links a {
            font-size: 0.9rem;
            color: rgba(255, 248, 240, 0.6);
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .footer-links a:hover {
            color: var(--white);
            transform: translateX(5px);
        }

        .footer-contact li {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            font-size: 0.88rem;
            color: rgba(255, 248, 240, 0.6);
            margin-bottom: 1.5rem;
        }

        .footer-contact li i {
            color: var(--or1);
            font-size: 1.1rem;
            margin-top: 3px;
        }

        .footer-bottom {
            padding: 2rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            background: rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-bottom p {
            font-size: 0.8rem;
            color: rgba(255, 248, 240, 0.4);
        }

        .footer-bottom a {
            color: rgba(255, 248, 240, 0.4);
            text-decoration: none;
            transition: color 0.3s;
            margin-left: 1.5rem;
        }

        .footer-bottom a:hover {
            color: var(--or1);
        }

        /* ─────────────────────────────────────────────
   RESPONSIVE
───────────────────────────────────────────── */
        @media(max-width:1024px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr
            }

            .why-grid {
                grid-template-columns: 1fr 1fr
            }

            .about-grid {
                grid-template-columns: 1fr
            }

            .about-visual {
                display: none
            }

            .partners-grid {
                grid-template-columns: 1fr;
                gap: 2rem
            }

            .partners-right {
                grid-template-columns: repeat(3, 1fr)
            }
        }

        @media(max-width:768px) {
            .nav-links {
                position: fixed;
                top: 0;
                right: -100%;
                bottom: 0;
                width: 270px;
                background: rgba(8, 4, 0, .98);
                flex-direction: column;
                justify-content: center;
                gap: 1.8rem;
                padding: 2rem;
                z-index: 999;
                transition: right .35s;
                border-left: 1px solid rgba(252, 123, 4, .18);
            }

            .nav-links.open {
                right: 0
            }

            .hamburger {
                display: flex
            }

            .hero-inner {
                grid-template-columns: 1fr
            }

            .hero-right {
                display: none
            }

            .stats-row {
                grid-template-columns: 1fr 1fr
            }

            .stat-item {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, .15);
                padding: 1rem
            }

            .stat-item:nth-child(2n) {
                border-right: none
            }

            .why-grid {
                grid-template-columns: 1fr
            }

            .footer-grid {
                grid-template-columns: 1fr
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center
            }

            .types-grid.two-cols,
            .types-grid.single-col {
                max-width: 100%
            }
        }

        @media(max-width:480px) {
            .catalog-grid {
                grid-template-columns: 1fr
            }

            .stats-row {
                grid-template-columns: 1fr 1fr
            }

            .partners-right {
                grid-template-columns: 1fr 1fr
            }
        }
    </style>
</head>

<body>

    <!-- Loading -->
    <div id="loading">
        <div class="ld-ring"></div>
        <span class="ld-text">INNOVA CIENCIA VIRTUAL</span>
    </div>

    <!-- Scroll progress -->
    <div class="scroll-bar" id="scrollBar"></div>

    <!-- Mobile overlay -->
    <div class="mob-overlay" id="mobOverlay"></div>

    <!-- ═══════════════════════════════════
     HEADER
══════════════════════════════════════ -->
    <header id="hdr">
        <div class="container">
            <nav class="nav">
                <a href="#inicio" class="brand">
                    <img src="<?php echo e(asset('images/logo-secundario.png')); ?>" alt="Innova Ciencia"
                        style="width:44px;height:44px;border-radius:8px;object-fit:contain;">
                    <div class="brand-name">
                        Innova Ciencia
                        <small>Virtual — Posgrados</small>
                    </div>
                </a>

                <ul class="nav-links" id="navLinks">
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#programas">Programas</a></li>
                    <li><a href="<?php echo e(route('catalogo')); ?>">Catálogo</a></li>
                    <li><a href="#equipo">Equipo</a></li>
                    <li><a href="#sedes">Sedes</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                </ul>

                <?php if(Route::has('login')): ?>
                    <?php if(auth()->check()): ?>
                        <?php
                            $user = auth()->user();
                            $dashboardUrl = $user->role === 'admin' ? url('/admin/dashboard') : ($user->role === 'moodle' ? route('virtual.dashboard') : url('/admin/dashboard'));
                        ?>
                        <a href="<?php echo e($dashboardUrl); ?>" class="btn-primary"
                            style="font-size:.82rem;padding:.55rem 1.3rem">
                            <i class="fas fa-th-large"></i> Dashboard
                        </a>
                        <a href="http://moodle52.localhost/" target="_blank" class="btn-outline"
                            style="font-size:.82rem;padding:.55rem 1.3rem">
                            <i class="fas fa-graduation-cap"></i> Moodle
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn-primary"
                            style="font-size:.82rem;padding:.55rem 1.3rem;margin-right:.15rem">
                            <i class="fas fa-sign-in-alt"></i> Ingresar
                        </a>
                        <a href="http://moodle52.localhost/" target="_blank" class="btn-outline"
                            style="font-size:.82rem;padding:.55rem 1.3rem">
                            <i class="fas fa-graduation-cap"></i> Moodle
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
            </nav>
        </div>
    </header>

    <!-- ═══════════════════════════════════
     HERO
══════════════════════════════════════ -->
    <section class="hero" id="inicio">
        <div class="hero-bg-text">POSGRADO</div>
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="hero-grid"></div>

        <div class="container">
            <div class="hero-inner">
                <!-- Left -->
                <div class="hero-left">
                    <div class="hero-tag">
                        <i class="fas fa-certificate"></i>
                        Excelencia Académica en Posgrados
                    </div>
                    <h1 class="hero-h1">
                        Potencia tu carrera con<br>
                        <em>programas de posgrado</em><br>
                        de <strong>alto nivel</strong>
                    </h1>
                    <p class="hero-desc">
                        Formación especializada con docentes de trayectoria, metodología innovadora
                        y respaldo de instituciones reconocidas a nivel nacional e internacional.
                    </p>
                    <div class="hero-actions">
                        <a href="<?php echo e(route('catalogo')); ?>" class="btn-primary">
                            <i class="fas fa-book-open"></i> Explorar Programas
                        </a>
                        <a href="#contacto" class="btn-outline">
                            <i class="fas fa-envelope"></i> Contáctanos
                        </a>
                    </div>
                </div>

                <!-- Right -->
                <div class="hero-right" id="heroRight">
                    <div class="hero-card">
                        <div class="hero-card-icon"><i class="fas fa-graduation-cap"></i></div>
                        <div class="hero-card-body">
                            <h4>Diplomados y Maestrías</h4>
                            <p>Programas con aval académico y titulación reconocida</p>
                        </div>
                    </div>
                    <div class="hero-card">
                        <div class="hero-card-icon"><i class="fas fa-laptop"></i></div>
                        <div class="hero-card-body">
                            <h4>Modalidad Virtual y Presencial</h4>
                            <p>Flexibilidad para adaptarse a tu ritmo de vida</p>
                        </div>
                    </div>
                    <div class="hero-card">
                        <div class="hero-card-icon"><i class="fas fa-handshake"></i></div>
                        <div class="hero-card-body">
                            <h4>Convenios Internacionales</h4>
                            <p>Respaldo de <?php echo e(\App\Models\Convenio::count()); ?> instituciones aliadas</p>
                        </div>
                    </div>
                    <div class="hero-card">
                        <div class="hero-card-icon"><i class="fas fa-users"></i></div>
                        <div class="hero-card-body">
                            <h4>Comunidad Académica</h4>
                            <p>Más de <?php echo e(\App\Models\Estudiante::count()); ?> profesionales formados</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════
     STATS STRIP
══════════════════════════════════════ -->
    <div class="stats-strip">
        <div class="container">
            <div class="stats-row">
                <div class="stat-item">
                    <span class="stat-num" data-target="<?php echo e(\App\Models\Posgrado::count()); ?>">0</span>
                    <span class="stat-label">Programas Académicos</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num" data-target="<?php echo e(\App\Models\Estudiante::count()); ?>">0</span>
                    <span class="stat-label">Estudiantes Formados</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num" data-target="<?php echo e(\App\Models\Sucursale::count()); ?>">0</span>
                    <span class="stat-label">Sedes Activas</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num" data-target="<?php echo e(\App\Models\Convenio::count()); ?>">0</span>
                    <span class="stat-label">Convenios Institucionales</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════
     ABOUT
══════════════════════════════════════ -->
    <section class="about-section" id="nosotros">
        <div class="container">
            <div class="about-grid">
                <div class="about-text">
                    <span class="eyebrow">Quiénes somos</span>
                    <h2 class="title-serif dark">
                        Formando líderes para los<br><span>desafíos del mundo</span> moderno
                    </h2>
                    <p class="subtitle dark" style="margin-bottom:1.8rem">
                        Somos una institución de posgrado comprometida con la calidad académica y el
                        desarrollo profesional de nuestros estudiantes. Ofrecemos programas diseñados
                        por expertos para responder a las exigencias actuales del mercado laboral.
                    </p>
                    <div class="pillars">
                        <div class="pillar">
                            <i class="fas fa-medal"></i>
                            <h4>Calidad certificada</h4>
                            <p>Programas auditados y avalados por instituciones aliadas</p>
                        </div>
                        <div class="pillar">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <h4>Docentes expertos</h4>
                            <p>Profesionales con trayectoria académica y empresarial</p>
                        </div>
                        <div class="pillar">
                            <i class="fas fa-network-wired"></i>
                            <h4>Red de contactos</h4>
                            <p>Comunidad de profesionales de múltiples sectores</p>
                        </div>
                        <div class="pillar">
                            <i class="fas fa-clock"></i>
                            <h4>Horarios flexibles</h4>
                            <p>Clases diseñadas para profesionales en actividad</p>
                        </div>
                    </div>
                </div>

                <div class="about-visual">
                    <div class="av-badge">
                        <div class="big-num"><?php echo e(\App\Models\OfertasAcademica::count()); ?>+</div>
                        <span>Ofertas Académicas</span>
                        <p>programas abiertos a inscripciones</p>
                    </div>
                    <div class="av-badge">
                        <div class="big-num"><?php echo e(\App\Models\Tipo::count()); ?></div>
                        <span>Modalidades</span>
                        <p>tipos de formación disponibles</p>
                    </div>
                    <div class="av-badge">
                        <div class="big-num"><?php echo e(\App\Models\Trabajadore::count()); ?>+</div>
                        <span>Especialistas</span>
                        <p>docentes y administrativos</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════
     PROGRAM TYPES
══════════════════════════════════════ -->
    <section class="types-section" id="programas">
        <div class="container">
            <span class="eyebrow">Modalidades académicas</span>
            <div
                style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:1rem;margin-bottom:2.5rem">
                <h2 class="title-serif" style="margin:0">Tipos de <span>Formación</span></h2>
                <a href="<?php echo e(route('catalogo')); ?>" class="btn-outline" style="font-size:.82rem">
                    Ver catálogo completo <i class="fas fa-arrow-right" style="font-size:.75rem"></i>
                </a>
            </div>
            <?php
                $tipoCount = $tipos->count();
                $tipoGridClass = 'types-grid';
                if ($tipoCount === 2) {
                    $tipoGridClass .= ' two-cols';
                } elseif ($tipoCount === 1) {
                    $tipoGridClass .= ' single-col';
                }
            ?>
            <div class="<?php echo e($tipoGridClass); ?>">
                <?php $__empty_1 = true; $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="type-card">
                        <div class="type-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3><?php echo e($tipo->nombre); ?></h3>
                        <p><?php echo e($tipo->descripcion ?? 'Programa de formación especializada diseñado para potenciar tu perfil profesional con alto estándar académico.'); ?>

                        </p>
                        <a href="<?php echo e(route('catalogo')); ?>" class="type-link">
                            Ver programas <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="type-card" style="grid-column:1/-1;text-align:center;padding:2.5rem">
                        <p style="color:var(--t-muted)">Próximamente se publicarán las modalidades disponibles.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════
     CATALOG
══════════════════════════════════════ -->
    <section class="catalog-section" id="catalogo">
        <div class="container">
            <span class="eyebrow">Oferta académica</span>
            <h2 class="title-serif dark" style="margin-bottom:.7rem">
                Catálogo de <span>Programas</span> Académicos
            </h2>
            <p class="subtitle dark" style="margin-bottom:2rem">
                Explora nuestra oferta completa y filtra por sede para encontrar el programa ideal en tu ciudad.
            </p>

            <div class="filter-row">
                <button class="filter-btn active" data-filter="todos">Todos los programas</button>
                <?php $__currentLoopData = $sucursalesDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sucursal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button class="filter-btn"
                        data-filter="<?php echo e(strtolower(str_replace(' ', '', $sucursal->nombre))); ?>">
                        <?php echo e($sucursal->nombre); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="catalog-grid" id="catalogGrid">
<?php $__empty_1 = true; $__currentLoopData = $ofertas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oferta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $planesAgrupados = $oferta->planesConceptos->groupBy(fn($pc) => optional($pc->plan_pago)->nombre ?? 'General');
                        $primerPlanNombre = $planesAgrupados->keys()->first() ?? '';
                        $primerPlan = $planesAgrupados->first();
                        $precio = $primerPlan ? $primerPlan->sum('pago_bs') : 0;

                        $sedeSlug = strtolower(str_replace(' ', '', optional($oferta->sucursal)->nombre ?? ''));
                        $tipoNombre = optional(optional($oferta->posgrado)->tipo)->nombre ?? 'Programa';
                        $duracion =
                            isset($oferta->posgrado->duracion_numero) && isset($oferta->posgrado->duracion_unidad)
                                ? "{$oferta->posgrado->duracion_numero} {$oferta->posgrado->duracion_unidad}"
                                : null;
                    ?>
                    <div class="prog-card" data-sede="<?php echo e($sedeSlug); ?>">
                        <div class="prog-img">
                            <?php if($oferta->portada): ?>
                                <img src="<?php echo e(asset('storage/' . $oferta->portada)); ?>"
                                    alt="<?php echo e(optional($oferta->programa)->nombre ?? optional($oferta->posgrado)->nombre); ?>"
                                    onerror="this.src='https://placehold.co/600x300/2e1600/fc7b04?text=<?php echo e(urlencode($tipoNombre)); ?>'">
                            <?php else: ?>
                                <img src="https://placehold.co/600x300/2e1600/fc7b04?text=<?php echo e(urlencode($tipoNombre)); ?>"
                                    alt="<?php echo e(optional($oferta->programa)->nombre ?? optional($oferta->posgrado)->nombre); ?>">
                            <?php endif; ?>
                            <span class="prog-type-badge"><?php echo e($tipoNombre); ?></span>
                            <?php if($oferta->fase): ?>
                                <span class="prog-fase-badge"><?php echo e($oferta->fase->nombre); ?></span>
                            <?php endif; ?>
                            <?php if(optional(optional($oferta->posgrado)->convenio)->imagen): ?>
                                <div class="prog-convenio">
                                    <img src="<?php echo e(asset('storage/' . $oferta->posgrado->convenio->imagen)); ?>"
                                        alt="<?php echo e($oferta->posgrado->convenio->nombre); ?>"
                                        onerror="this.parentElement.style.display='none'">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="prog-body">
                            <div class="prog-sede">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo e(optional($oferta->sucursal)->nombre ?? 'Sin sede asignada'); ?>

                            </div>
                            <h3 class="prog-title">
                                <?php echo e(optional($oferta->programa)->nombre ?? (optional($oferta->posgrado)->nombre ?? 'Programa sin nombre')); ?>

                            </h3>
                            <p class="prog-desc">
                                <?php echo e(Str::limit(optional($oferta->posgrado)->objetivo ?? 'Información disponible próximamente.', 110)); ?>

                            </p>
                            <div class="prog-meta">
                                <?php if($oferta->fecha_inicio_programa): ?>
                                    <div class="prog-meta-item">
                                        <i class="far fa-calendar-alt"></i>
                                        Inicio: <?php echo e($oferta->fecha_inicio_programa->format('d \d\e F, Y')); ?>

                                    </div>
                                <?php endif; ?>
                                <?php if($duracion): ?>
                                    <div class="prog-meta-item">
                                        <i class="far fa-clock"></i>
                                        Duración: <?php echo e($duracion); ?>

                                    </div>
                                <?php endif; ?>
                                <?php if(optional(optional($oferta->posgrado)->area)->nombre): ?>
                                    <div class="prog-meta-item">
                                        <i class="fas fa-layer-group"></i>
                                        <?php echo e($oferta->posgrado->area->nombre); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="prog-footer">
                                <div class="prog-price">
                                    <?php if($precio > 0): ?>
                                        Bs. <?php echo e(number_format($precio, 0, ',', '.')); ?>

                                        <small><?php echo e($primerPlanNombre ?: 'Precio total'); ?></small>
                                    <?php else: ?>
                                        <span
                                            style="font-size:.85rem;color:#9a6040;font-family:'Inter',sans-serif;font-weight:500">Consultar
                                            precio</span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo e(route('oferta.detalle', $oferta->id)); ?>" class="btn-primary"
                                    style="font-size:.78rem;padding:.45rem 1rem">
                                    Más información
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="no-prog-msg">
                        <i class="fas fa-book-open"></i>
                        <h3>No hay programas disponibles</h3>
                        <p>Próximamente publicaremos nuestra oferta académica. Contáctanos para más información.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════
     WHY US
══════════════════════════════════════ -->
    <section class="why-section">
        <div class="container">
            <span class="eyebrow">Por qué elegirnos</span>
            <h2 class="title-serif" style="margin-bottom:.7rem">
                Una institución comprometida<br>con tu <em>crecimiento profesional</em>
            </h2>
            <p class="subtitle" style="margin-bottom:2.5rem">
                Más que un título: te ofrecemos una experiencia formativa que marca la diferencia.
            </p>
            <div class="why-grid">
                <div class="why-card">
                    <div class="why-num">01</div>
                    <div class="why-icon"><i class="fas fa-award"></i></div>
                    <h3>Excelencia Académica</h3>
                    <p>Currículos actualizados, metodologías activas y evaluación continua para garantizar aprendizajes
                        de impacto real en tu ejercicio profesional.</p>
                </div>
                <div class="why-card">
                    <div class="why-num">02</div>
                    <div class="why-icon"><i class="fas fa-globe-americas"></i></div>
                    <h3>Reconocimiento Internacional</h3>
                    <p>Convenios con universidades e instituciones del exterior que respaldan la validez y el peso
                        académico de nuestros programas.</p>
                </div>
                <div class="why-card">
                    <div class="why-num">03</div>
                    <div class="why-icon"><i class="fas fa-users-cog"></i></div>
                    <h3>Acompañamiento Integral</h3>
                    <p>Asesoría personalizada desde la inscripción hasta la titulación, con soporte académico y
                        administrativo en cada etapa de tu formación.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════
     TEAM
══════════════════════════════════════ -->
    <section class="team-section" id="equipo">
        <div class="container">
            <span class="eyebrow">Nuestro equipo</span>
            <div
                style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:1rem;margin-bottom:2.5rem">
                <h2 class="title-serif dark" style="margin:0">Personal <span>Académico</span></h2>
            </div>
            <div class="carousel-wrap">
                <button class="car-btn" id="teamPrev"><i class="fas fa-chevron-left"></i></button>
                <div class="carousel-track-wrap" id="teamWrap">
                    <div class="carousel-track" id="teamTrack">
                        <?php if($trabajadores->isEmpty()): ?>
                            <div class="team-card">
                                <div class="team-img">
                                    <img src="<?php echo e(asset('images/hombre.png')); ?>" alt="Equipo">
                                </div>
                                <div class="team-info">
                                    <h4>Equipo Académico</h4>
                                    <span class="team-role">Docente</span>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php $__currentLoopData = $trabajadores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area => $grupo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $grupo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trabajador): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $cp = $trabajador->trabajadores_cargos->first(); ?>
                                    <?php if($cp && $cp->cargo): ?>
                                        <div class="team-card">
                                            <div class="team-img">
                                                <?php
                                                    $fallbackImg =
                                                        $trabajador->persona->sexo === 'Hombre'
                                                            ? asset('images/hombre.png')
                                                            : asset('images/mujer.png');
                                                ?>

                                                <?php if($trabajador->persona->fotografia): ?>
                                                    <img src="<?php echo e(asset('images/personas/' . $trabajador->persona->fotografia)); ?>"
                                                        alt="<?php echo e($trabajador->persona->nombres); ?>"
                                                        onerror="this.onerror=null; this.src='<?php echo e($fallbackImg); ?>'">
                                                <?php elseif($trabajador->persona->sexo === 'Hombre'): ?>
                                                    <img src="<?php echo e(asset('images/hombre.png')); ?>" alt="Foto">
                                                <?php else: ?>
                                                    <img src="<?php echo e(asset('images/mujer.png')); ?>" alt="Foto">
                                                <?php endif; ?>
                                            </div>
                                            <div class="team-info">
                                                <h4>
                                                    <?php echo e($trabajador->persona->apellido_paterno); ?>

                                                    <?php echo e($trabajador->persona->apellido_materno); ?>,
                                                    <?php echo e($trabajador->persona->nombres); ?>

                                                </h4>
                                                <span class="team-role"><?php echo e($cp->cargo->nombre); ?></span>
                                                <div class="team-sede">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <?php if($cp->sucursale): ?>
                                                        <?php echo e(optional($cp->sucursale->sede)->nombre); ?> —
                                                        <?php echo e($cp->sucursale->nombre); ?>

                                                    <?php else: ?>
                                                        <?php echo e($area); ?> (Todas las sedes)
                                                    <?php endif; ?>
                                                </div>
                                                <div class="team-contacts">
                                                    <?php if($trabajador->persona->correo): ?>
                                                        <a href="mailto:<?php echo e($trabajador->persona->correo); ?>"
                                                            class="tcb tcb-email"
                                                            title="<?php echo e($trabajador->persona->correo); ?>">
                                                            <i class="fas fa-envelope"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if($trabajador->persona->celular): ?>
                                                        <a href="https://wa.me/591<?php echo e($trabajador->persona->celular); ?>"
                                                            target="_blank" class="tcb tcb-wa"
                                                            title="<?php echo e($trabajador->persona->celular); ?>">
                                                            <i class="fab fa-whatsapp"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <button class="car-btn" id="teamNext"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </section>

<!-- ═══════════════════════════════════
     ALIANZAS ESTRATÉGICAS
 ══════════════════════════════════════ -->
    <?php if($convenios->isNotEmpty()): ?>
        <section class="partners-section">
            <div class="container">
                <span class="eyebrow">Alianzas estratégicas</span>
                <div class="partners-grid">
                    <div class="partners-text">
                        <h2 class="title-serif">
                            Instituciones de <span>Convenio</span>
                        </h2>
                        <p class="subtitle">
                            Respaldamos nuestros programas con el aval de instituciones reconocidas a nivel nacional e internacional, asegurando una formación de excelencia con estándares académicos de alto nivel.
                        </p>
                    </div>
                    <div class="partners-right">
                        <?php $__currentLoopData = $convenios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="partner-logo-card" title="<?php echo e($c->nombre); ?>">
                                <img src="<?php echo e(asset('storage/' . $c->imagen)); ?>" alt="<?php echo e($c->nombre); ?>"
                                    onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                                <span class="fallback-text" style="display:none;"><?php echo e($c->nombre); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

<!-- ═══════════════════════════════════
     SEDES
 ══════════════════════════════════════ -->
    <?php if($sucursales->isNotEmpty()): ?>
        <section class="sedes-section" id="sedes">
            <div class="container">
                <span class="eyebrow">Presencia nacional</span>
                <div class="sedes-header">
                    <h2 class="title-serif dark" style="margin:0">Nuestras <span>Sedes</span></h2>
                </div>
                <?php if($sucursales->count() === 1): ?>
                    <?php $sucursal = $sucursales->first(); ?>
                    <div class="sede-showcase">
                        <div class="sede-showcase-map">
                            <iframe
                                src="https://www.google.com/maps/embed/v1/place?key=TU_API_KEY&q=<?php echo e($sucursal->latitud); ?>,<?php echo e($sucursal->longitud); ?>"
                                allowfullscreen loading="lazy">
                            </iframe>
                        </div>
                        <div class="sede-showcase-info">
                            <?php if($sucursal->sede): ?>
                                <div class="sede-showcase-parent"><?php echo e($sucursal->sede->nombre); ?></div>
                            <?php endif; ?>
                            <h3 class="sede-showcase-name"><?php echo e($sucursal->nombre); ?></h3>
                            <?php if($sucursal->direccion): ?>
                                <div class="sede-showcase-address">
                                    <i class="fas fa-map-pin"></i>
                                    <span><?php echo e($sucursal->direccion); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="sede-showcase-stats">
                                <div class="sede-showcase-stat">
                                    <span
                                        class="sede-showcase-stat-num"><?php echo e($sucursal->ofertas_academicas()->count()); ?></span>
                                    <span class="sede-showcase-stat-label">Programas activos</span>
                                </div>
                                <div class="sede-showcase-stat">
                                    <span class="sede-showcase-stat-num">
                                        <?php echo e($sucursal->ofertas_academicas()->withCount('inscripciones')->get()->sum('inscripciones_count')); ?>

                                    </span>
                                    <span class="sede-showcase-stat-label">Estudiantes inscritos</span>
                                </div>
                            </div>
                        </div>
                        <div class="sede-showcase-badge" aria-hidden="true">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="carousel-wrap">
                        <button class="car-btn" id="sedePrev"><i class="fas fa-chevron-left"></i></button>
                        <div class="sede-track-wrap" id="sedeWrap">
                            <div class="sede-track" id="sedeTrack">
                                <?php $__currentLoopData = $sucursales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sucursal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="sede-card"
                                        data-sede="<?php echo e(strtolower(str_replace(' ', '', $sucursal->nombre))); ?>">
                                        <div class="sede-map">
                                            <div class="sede-map-badge">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <iframe
                                                src="https://www.google.com/maps/embed/v1/place?key=TU_API_KEY&q=<?php echo e($sucursal->latitud); ?>,<?php echo e($sucursal->longitud); ?>"
                                                allowfullscreen loading="lazy">
                                            </iframe>
                                        </div>
                                        <div class="sede-info">
                                            <?php if($sucursal->sede): ?>
                                                <div class="sede-parent"><?php echo e($sucursal->sede->nombre); ?></div>
                                            <?php endif; ?>
                                            <div class="sede-name"><?php echo e($sucursal->nombre); ?></div>
                                            <?php if($sucursal->direccion): ?>
                                                <div class="sede-dir">
                                                    <i class="fas fa-map-pin"></i>
                                                    <span><?php echo e($sucursal->direccion); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <div class="sede-stats">
                                                <div class="sede-stat">
                                                    <span
                                                        class="sede-stat-num"><?php echo e($sucursal->ofertas_academicas()->count()); ?></span>
                                                    <span class="sede-stat-lbl">Programas</span>
                                                </div>
                                                <div class="sede-stat">
                                                    <span class="sede-stat-num">
                                                        <?php echo e($sucursal->ofertas_academicas()->withCount('inscripciones')->get()->sum('inscripciones_count')); ?>

                                                    </span>
                                                    <span class="sede-stat-lbl">Inscritos</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <button class="car-btn" id="sedeNext"><i class="fas fa-chevron-right"></i></button>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- ═══════════════════════════════════
     CTA
══════════════════════════════════════ -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-inner">
                <span class="eyebrow" style="justify-content:center;color:var(--gold-lt)">Da el siguiente paso</span>
                <h2>Transforma tu futuro con <span>Innova Ciencia Virtual</span></h2>
                <p>
                    Inscríbete hoy o solicita asesoría personalizada. Nuestro equipo está listo para
                    orientarte en la elección del programa que mejor se adapte a tus objetivos.
                </p>
                <div class="cta-actions">
                    <a href="<?php echo e(route('catalogo')); ?>" class="btn-primary" style="font-size:1rem;padding:.8rem 2rem">
                        <i class="fas fa-book-open"></i> Ver Programas
                    </a>
                    <a href="#contacto" class="btn-outline"
                        style="border-color:rgba(255,255,255,.3);color:var(--white)">
                        <i class="fas fa-paper-plane"></i> Solicitar Información
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════
     FOOTER
══════════════════════════════════════ -->
    <footer id="contacto">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="footer-brand-name">Innova <span>Ciencia</span> Virtual</div>
                    <p class="footer-desc">
                        Institución de posgrado comprometida con la formación de profesionales de alto nivel,
                        con metodología innovadora, docentes especializados y respaldo de convenios
                        académicos nacionales e internacionales.
                    </p>
                    <div class="socials">
                        <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <div class="footer-col">
                    <h5>Navegación</h5>
                    <ul class="footer-links">
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#nosotros">Quiénes somos</a></li>
                        <li><a href="#programas">Tipos de Programa</a></li>
                        <li><a href="<?php echo e(route('catalogo')); ?>">Catálogo Académico</a></li>
                        <li><a href="#equipo">Equipo Académico</a></li>
                        <li><a href="#sedes">Nuestras Sedes</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h5>Programas</h5>
                    <ul class="footer-links">
                        <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><a href="<?php echo e(route('catalogo')); ?>"><?php echo e($tipo->nombre); ?></a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

                <div class="footer-col">
                    <h5>Contáctanos</h5>
                    <ul class="footer-contact">
                        <?php $__currentLoopData = $sucursales->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>
                                    <strong style="display:block;color:rgba(255,255,255,.7);font-size:.78rem">
                                        <?php echo e(optional($s->sede)->nombre); ?> — <?php echo e($s->nombre); ?>

                                    </strong>
                                    <?php if($s->direccion): ?>
                                        <?php echo e($s->direccion); ?>

                                    <?php endif; ?>
                                </span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?php echo e(date('Y')); ?> Innova Ciencia Virtual. Todos los derechos reservados.</p>
                <p>
                    <a href="#">Términos y Condiciones</a>
                    &nbsp;·&nbsp;
                    <a href="#">Política de Privacidad</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- ═══════════════════════════════════
     SCRIPTS
══════════════════════════════════════ -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /* ── Loading screen ── */
            const loading = document.getElementById('loading');
            const ldText = loading ? loading.querySelector('.ld-text') : null;

            if (ldText) {
                setTimeout(() => {
                    ldText.style.transition = 'opacity .5s';
                    ldText.style.opacity = '1';
                }, 400);
            }
            setTimeout(() => {
                if (loading) {
                    loading.style.transition = 'opacity .7s ease';
                    loading.style.opacity = '0';
                    setTimeout(() => {
                        loading.style.display = 'none';
                        boot();
                    }, 720);
                }
            }, 1400);

            /* ── Header scroll ── */
            const hdr = document.getElementById('hdr');
            window.addEventListener('scroll', () => hdr && hdr.classList.toggle('scrolled', scrollY > 60), {
                passive: true
            });

            function boot() {
                if (typeof gsap === 'undefined') return;
                gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

                /* ── Scroll bar ── */
                const sb = document.getElementById('scrollBar');
                if (sb) {
                    ScrollTrigger.create({
                        start: 0,
                        end: 'bottom bottom',
                        onUpdate: s => sb.style.transform = `scaleX(${s.progress})`
                    });
                }

                /* ── Hero animation ── */
                const tl = gsap.timeline();
                tl.fromTo('.hero-tag', {
                        opacity: 0,
                        y: 20
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: .6,
                        ease: 'power3.out'
                    })
                    .fromTo('.hero-h1', {
                        opacity: 0,
                        y: 30
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: .9,
                        ease: 'power3.out'
                    }, '-=.2')
                    .fromTo('.hero-desc', {
                        opacity: 0,
                        y: 20
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: .7,
                        ease: 'power3.out'
                    }, '-=.4')
                    .fromTo('.hero-actions .btn-primary, .hero-actions .btn-outline', {
                        opacity: 0,
                        y: 16
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: .6,
                        stagger: .1,
                        ease: 'power3.out'
                    }, '-=.3')
                    .fromTo('.hero-card', {
                        opacity: 0,
                        x: 24
                    }, {
                        opacity: 1,
                        x: 0,
                        duration: .55,
                        stagger: .12,
                        ease: 'power3.out'
                    }, '-=.5');

                /* Orb floating */
                gsap.to('.orb-1', {
                    y: -30,
                    x: 20,
                    duration: 8,
                    repeat: -1,
                    yoyo: true,
                    ease: 'sine.inOut'
                });
                gsap.to('.orb-2', {
                    y: 25,
                    x: -15,
                    duration: 10,
                    repeat: -1,
                    yoyo: true,
                    ease: 'sine.inOut'
                });

                /* ── Stats counter ── */
                ScrollTrigger.create({
                    trigger: '.stats-strip',
                    start: 'top 85%',
                    once: true,
                    onEnter: () => {
                        document.querySelectorAll('.stat-num[data-target]').forEach(el => {
                            const target = +el.dataset.target;
                            gsap.fromTo({
                                val: 0
                            }, {
                                val: target
                            }, {
                                duration: 1.8,
                                ease: 'power2.out',
                                onUpdate: function() {
                                    el.textContent = Math.round(this.targets()[0]
                                        .val);
                                }
                            });
                        });
                    }
                });

                /* ── Scroll reveal utility ── */
                const neutralVal = {
                    x: 0,
                    y: 0,
                    scale: 1,
                    rotate: 0,
                    skewX: 0,
                    skewY: 0
                };

                function reveal(sel, from, opts = {}) {
                    gsap.utils.toArray(sel).forEach((el, i) => {
                        const to = {};
                        Object.keys(from).forEach(k => {
                            to[k] = neutralVal[k] !== undefined ? neutralVal[k] : 0;
                        });
                        gsap.fromTo(el, {
                            opacity: 0,
                            ...from
                        }, {
                            opacity: 1,
                            ...to,
                            duration: opts.duration ?? .75,
                            delay: i * (opts.stagger ?? .08),
                            ease: opts.ease ?? 'power3.out',
                            scrollTrigger: {
                                trigger: el,
                                start: 'top 88%',
                                once: true
                            }
                        });
                    });
                }

                reveal('.pillar', {
                    y: 24
                });
                reveal('.av-badge', {
                    y: 24,
                    scale: .95
                }, {
                    ease: 'back.out(1.5)'
                });
                reveal('.type-card', {
                    y: 30
                });
                reveal('.prog-card', {
                    y: 32
                }, {
                    stagger: .06
                });
                reveal('.why-card', {
                    y: 28
                }, {
                    stagger: .1
                });
                reveal('.sede-card', {
                    y: 24
                }, {
                    stagger: .08
                });
                reveal('.partner-logo-card', {
                    y: 20,
                    scale: .96
                }, {
                    stagger: .04
                });

                gsap.utils.toArray('.section-title-anim, .eyebrow, .title-serif').forEach(el => {
                    gsap.fromTo(el, {
                        opacity: 0,
                        y: 22
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: .75,
                        ease: 'power3.out',
                        scrollTrigger: {
                            trigger: el,
                            start: 'top 88%',
                            once: true
                        }
                    });
                });

                /* ── Mobile menu ── */
                const burger = document.getElementById('hamburger');
                const navLinks = document.getElementById('navLinks');
                const overlay = document.getElementById('mobOverlay');
                const closeMenu = () => {
                    burger.classList.remove('open');
                    navLinks.classList.remove('open');
                    overlay.classList.remove('open');
                    document.body.style.overflow = '';
                };
                burger.addEventListener('click', () => {
                    const open = burger.classList.toggle('open');
                    navLinks.classList.toggle('open', open);
                    overlay.classList.toggle('open', open);
                    document.body.style.overflow = open ? 'hidden' : '';
                });
                overlay.addEventListener('click', closeMenu);
                navLinks.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));

                /* ── Catalog filter ── */
                const filterBtns = document.querySelectorAll('.filter-btn');
                const progCards = document.querySelectorAll('.prog-card');
                filterBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        filterBtns.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        const f = this.dataset.filter;
                        progCards.forEach(c => {
                            const show = f === 'todos' || c.dataset.sede === f;
                            c.style.display = show ? '' : 'none';
                        });
                    });
                });

                /* ── Sede → filter link ── */
                document.querySelectorAll('.sede-card').forEach(card => {
                    card.addEventListener('click', () => {
                        const btn = document.querySelector(
                            `.filter-btn[data-filter="${card.dataset.sede}"]`);
                        if (btn) {
                            btn.click();
                            gsap.to(window, {
                                scrollTo: {
                                    y: '#catalogo',
                                    offsetY: 90
                                },
                                duration: .9,
                                ease: 'power2.inOut'
                            });
                        }
                    });
                });

                /* ── CTA section ── */
                ScrollTrigger.create({
                    trigger: '.cta-section',
                    start: 'top 80%',
                    once: true,
                    onEnter: () => {
                        gsap.fromTo('.cta-inner h2', {
                            opacity: 0,
                            y: 36
                        }, {
                            opacity: 1,
                            y: 0,
                            duration: 1,
                            ease: 'power3.out'
                        });
                        gsap.fromTo('.cta-inner p', {
                            opacity: 0,
                            y: 22
                        }, {
                            opacity: 1,
                            y: 0,
                            duration: .8,
                            delay: .25,
                            ease: 'power3.out'
                        });
                        gsap.fromTo('.cta-actions > *', {
                            opacity: 0,
                            y: 18
                        }, {
                            opacity: 1,
                            y: 0,
                            duration: .6,
                            delay: .45,
                            stagger: .12,
                            ease: 'power3.out'
                        });
                    }
                });

                /* ── Footer ── */
                ScrollTrigger.create({
                    trigger: 'footer',
                    start: 'top 88%',
                    once: true,
                    onEnter: () => gsap.utils.toArray('.footer-col').forEach((c, i) =>
                        gsap.fromTo(c, {
                            opacity: 0,
                            y: 30
                        }, {
                            opacity: 1,
                            y: 0,
                            duration: .65,
                            delay: i * .1,
                            ease: 'power3.out'
                        }))
                });

                /* ── Generic drag carousel ── */
                function buildCarousel(trackId, wrapId, prevId, nextId) {
                    const track = document.getElementById(trackId);
                    const wrap = document.getElementById(wrapId);
                    const prev = document.getElementById(prevId);
                    const next = document.getElementById(nextId);
                    if (!track || !wrap || !prev || !next) return;

                    let idx = 0,
                        tx = 0,
                        startX = 0,
                        prevTx = 0,
                        dragging = false;

                    const cw = () => {
                        if (!track.children.length) return 0;
                        const s = getComputedStyle(track.children[0]);
                        return track.children[0].offsetWidth + parseFloat(s.marginRight || 0) + parseFloat(s
                            .marginLeft || 0);
                    };
                    const tw = () => {
                        let w = 0;
                        Array.from(track.children).forEach(c => {
                            const s = getComputedStyle(c);
                            w += c.offsetWidth + parseFloat(s.marginRight || 0) + parseFloat(s
                                .marginLeft || 0);
                        });
                        return w;
                    };
                    const maxTx = () => Math.max(0, tw() - wrap.offsetWidth);
                    const maxIdx = () => Math.max(0, track.children.length - Math.floor(wrap.offsetWidth / (cw() ||
                        1)));

                    function update(animate = true) {
                        idx = Math.max(0, Math.min(idx, maxIdx()));
                        const max = maxTx();
                        tx = idx >= maxIdx() ? -max : -idx * cw();
                        tx = Math.max(-max, Math.min(0, tx));
                        if (animate) gsap.to(track, {
                            x: tx,
                            duration: .5,
                            ease: 'power2.out'
                        });
                        else gsap.set(track, {
                            x: tx
                        });
                        gsap.to(prev, {
                            opacity: idx <= 0 ? .3 : 1,
                            duration: .3
                        });
                        gsap.to(next, {
                            opacity: idx >= maxIdx() ? .3 : 1,
                            duration: .3
                        });
                        prev.disabled = idx <= 0;
                        next.disabled = idx >= maxIdx();
                    }

                    prev.addEventListener('click', () => {
                        if (idx > 0) {
                            idx--;
                            update();
                        }
                    });
                    next.addEventListener('click', () => {
                        if (idx < maxIdx()) {
                            idx++;
                            update();
                        }
                    });

                    const ds = e => {
                        dragging = true;
                        startX = e.touches ? e.touches[0].clientX : e.clientX;
                        prevTx = tx;
                        track.style.cursor = 'grabbing';
                    };
                    const dm = e => {
                        if (!dragging) return;
                        const x = e.touches ? e.touches[0].clientX : e.clientX;
                        tx = Math.max(-maxTx(), Math.min(0, prevTx + x - startX));
                        gsap.set(track, {
                            x: tx
                        });
                    };
                    const de = e => {
                        if (!dragging) return;
                        dragging = false;
                        track.style.cursor = '';
                        const x = e.changedTouches ? e.changedTouches[0].clientX : e.clientX;
                        const d = x - startX;
                        const cwv = cw();
                        if (Math.abs(d) > 50 && cwv > 0) {
                            const sh = Math.ceil(Math.abs(d) / cwv);
                            idx = d < 0 ? Math.min(maxIdx(), idx + sh) : Math.max(0, idx - sh);
                        }
                        update();
                    };

                    wrap.addEventListener('mousedown', ds);
                    document.addEventListener('mousemove', dm);
                    document.addEventListener('mouseup', de);
                    wrap.addEventListener('touchstart', ds, {
                        passive: true
                    });
                    document.addEventListener('touchmove', dm, {
                        passive: true
                    });
                    document.addEventListener('touchend', de);
                    track.addEventListener('selectstart', e => {
                        if (dragging) e.preventDefault();
                    });

                    let rt;
                    window.addEventListener('resize', () => {
                        clearTimeout(rt);
                        rt = setTimeout(() => {
                            idx = Math.min(idx, maxIdx());
                            update(false);
                        }, 250);
                    });
                    setTimeout(() => update(false), 100);
                }

                buildCarousel('teamTrack', 'teamWrap', 'teamPrev', 'teamNext');
                buildCarousel('sedeTrack', 'sedeWrap', 'sedePrev', 'sedeNext');
            }
        });
    </script>
</body>

</html>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/welcome.blade.php ENDPATH**/ ?>