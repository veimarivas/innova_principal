@extends('layouts.master')
@section('title', 'Cuenta – ' . $cuenta->nombre)

@section('css')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700&display=swap');

    :root {
        --cv-orange:    #fc7b04;
        --cv-orange-lt: rgba(252,123,4,0.10);
        --cv-orange-dk: #d46604;
        --cv-surface:   #faf8f5;
        --cv-border:    #ede8e2;
        --cv-border-lt: #f5f0eb;
        --cv-text:      #2a2520;
        --cv-muted:     #7a746c;
        --cv-ok:        #2e9a6e;
        --cv-err:       #d94f4f;
        --cv-warn:      #c9860a;
        --cv-blue:      #3d5af1;
        --cv-r-sm:  8px;
        --cv-r-md:  12px;
        --cv-r-lg:  16px;
        --cv-sh-sm: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
        --cv-sh-md: 0 4px 16px rgba(0,0,0,0.06), 0 2px 8px rgba(0,0,0,0.04);
    }

    .cv-page { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--cv-text); }

    /* ────────────────── HERO ────────────────── */
    .cv-hero {
        background: linear-gradient(135deg, #2a1200 0%, #6b3300 45%, #c96004 100%);
        border-radius: var(--cv-r-lg);
        padding: 22px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        color: #fff;
    }
    .cv-hero::before {
        content: '';
        position: absolute; top: -40%; right: -3%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(252,123,4,0.22) 0%, transparent 68%);
        border-radius: 50%; pointer-events: none;
    }
    .cv-hero::after {
        content: '';
        position: absolute; bottom: -50%; left: 5%;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        border-radius: 50%; pointer-events: none;
    }
    .cv-hero-left {
        display: flex; align-items: center; gap: 18px;
        position: relative; z-index: 1;
        flex: 1; min-width: 0;
    }
    .cv-hero-avatar {
        width: 60px; height: 60px;
        background: rgba(255,255,255,0.12);
        border: 1.5px solid rgba(255,255,255,0.22);
        border-radius: var(--cv-r-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.7rem; flex-shrink: 0;
        color: #fc7b04;
        backdrop-filter: blur(6px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
    }
    .cv-hero-pre {
        font-size: 0.62rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.09em;
        color: rgba(255,255,255,0.6) !important; margin-bottom: 4px;
    }
    .cv-hero-name {
        font-family: 'Outfit', sans-serif;
        font-size: 1.4rem; font-weight: 700;
        margin: 0; letter-spacing: -0.02em;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        color: #ffffff !important;
        text-shadow: 0 1px 4px rgba(0,0,0,0.25);
    }
    .cv-hero-meta {
        display: flex; flex-wrap: wrap; gap: 5px 10px;
        margin-top: 8px; font-size: 0.78rem;
        color: rgba(255,255,255,0.72) !important;
    }
    .cv-hero-meta > span { display: flex; align-items: center; gap: 4px; color: rgba(255,255,255,0.72); }
    .cv-hero-chip {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 11px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 20px; font-size: 0.7rem; font-weight: 600;
        backdrop-filter: blur(6px);
        color: rgba(255,255,255,0.9) !important;
    }
    .cv-hero-chip.ok  { background: rgba(46,154,110,0.28); border-color: rgba(46,154,110,0.45); }
    .cv-hero-chip.off { background: rgba(160,160,160,0.18); border-color: rgba(160,160,160,0.3); }
    .cv-hero-right {
        position: relative; z-index: 1;
        display: flex; align-items: center; gap: 8px;
        flex-shrink: 0;
    }
    .cv-back {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.22);
        border-radius: var(--cv-r-sm);
        color: #fff !important; text-decoration: none;
        font-size: 0.8rem; font-weight: 600;
        transition: all 0.2s; backdrop-filter: blur(6px);
    }
    .cv-back:hover { background: #fff; color: var(--cv-orange) !important; border-color: #fff; }

    /* ────────────────── STAT CARDS ────────────────── */
    .cv-stat {
        background: var(--d-card, #fff);
        border: 1px solid var(--d-card-border, var(--cv-border));
        border-radius: var(--cv-r-md);
        box-shadow: 0 2px 10px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.04);
        padding: 18px 20px;
        display: flex; align-items: center; gap: 15px;
        transition: all 0.25s ease;
        position: relative; overflow: hidden;
    }
    .cv-stat::after {
        content: '';
        position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, var(--cv-orange), var(--cv-orange-dk));
        transform: scaleX(0); transform-origin: left;
        transition: transform 0.25s ease;
        border-radius: 0 0 var(--cv-r-md) var(--cv-r-md);
    }
    .cv-stat:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
    .cv-stat:hover::after { transform: scaleX(1); }
    .cv-stat-ico {
        width: 50px; height: 50px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.35rem; flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        transition: transform 0.2s;
    }
    .cv-stat:hover .cv-stat-ico { transform: scale(1.08); }
    .cv-stat-num {
        font-family: 'Outfit', sans-serif;
        font-size: 1.75rem; font-weight: 800; line-height: 1;
        letter-spacing: -0.02em;
        color: var(--cv-text);
    }
    .cv-stat-lbl {
        font-size: 0.71rem; color: var(--cv-muted); margin-top: 4px;
        font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em;
    }

    /* ────────────────── MAIN CARD & TABS ────────────────── */
    .cv-card {
        background: var(--d-card, #fff);
        border: 1px solid var(--d-card-border, var(--cv-border));
        border-radius: var(--cv-r-lg);
        box-shadow: var(--cv-sh-sm);
        overflow: hidden;
    }
    .cv-tabs-nav {
        display: flex; overflow-x: auto; scrollbar-width: none;
        background: var(--d-header-bg, var(--cv-surface));
        border-bottom: 1px solid var(--d-header-border, var(--cv-border));
    }
    .cv-tabs-nav::-webkit-scrollbar { display: none; }
    .cv-tab-btn {
        display: flex; align-items: center; gap: 7px;
        padding: 13px 20px;
        font-size: 0.84rem; font-weight: 600;
        color: var(--cv-muted);
        border: none; background: none;
        border-bottom: 3px solid transparent;
        cursor: pointer; white-space: nowrap;
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .cv-tab-btn i { font-size: 1rem; }
    .cv-tab-btn:hover:not(.active) { color: var(--cv-orange); background: var(--cv-orange-lt); }
    .cv-tab-btn.active {
        color: var(--cv-orange);
        border-bottom-color: var(--cv-orange);
        background: var(--d-card, #fff);
    }
    .cv-tab-count {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 20px; height: 20px; padding: 0 5px;
        background: var(--cv-orange-lt); color: var(--cv-orange);
        border-radius: 20px; font-size: 0.67rem; font-weight: 700;
    }
    .cv-tab-btn.active .cv-tab-count { background: var(--cv-orange); color: #fff; }
    .cv-tab-body { padding: 22px; display: none; }
    .cv-tab-body.active { display: block; }

    /* ────────────────── INFO GRID ────────────────── */
    .cv-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    @media (max-width: 1024px) { .cv-info-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 640px)  { .cv-info-grid { grid-template-columns: 1fr; } }

    .cv-icard {
        background: var(--d-card, #fff);
        border: 1px solid var(--d-card-border, var(--cv-border));
        border-radius: var(--cv-r-md);
        overflow: hidden; box-shadow: var(--cv-sh-sm);
    }
    .cv-icard-head {
        padding: 10px 14px;
        border-bottom: 1px solid var(--cv-border);
        display: flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, #fff 0%, #fdf9f4 100%);
    }
    .cv-icard-ico {
        width: 28px; height: 28px; border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem;
        background: var(--cv-orange-lt); color: var(--cv-orange);
        flex-shrink: 0;
    }
    .cv-icard-title {
        font-size: 0.68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.06em;
        color: var(--cv-text); margin: 0;
    }
    .cv-field {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 10px 14px;
        border-bottom: 1px solid var(--cv-border-lt);
    }
    .cv-field:last-child { border-bottom: none; }
    .cv-field-ico {
        width: 26px; height: 26px; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.78rem; flex-shrink: 0;
        background: rgba(252,123,4,0.07); color: var(--cv-orange);
        margin-top: 1px;
    }
    .cv-field-lbl {
        font-size: 0.6rem; text-transform: uppercase;
        letter-spacing: 0.05em; color: var(--cv-muted);
        font-weight: 700; margin-bottom: 2px; line-height: 1;
    }
    .cv-field-val { font-size: 0.83rem; font-weight: 500; color: var(--cv-text); }
    .cv-field-val.dim { color: var(--cv-muted); font-style: italic; font-weight: 400; }
    .cv-field-sub { font-size: 0.68rem; color: var(--cv-muted); margin-top: 1px; }

    /* Mini stats inside info card */
    .cv-mini {
        display: flex; align-items: center; gap: 11px;
        padding: 11px 14px;
        border-bottom: 1px solid var(--cv-border-lt);
    }
    .cv-mini:last-child { border-bottom: none; }
    .cv-mini-ico {
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; flex-shrink: 0;
    }
    .cv-mini-num {
        font-family: 'Outfit', sans-serif;
        font-size: 1.2rem; font-weight: 700; line-height: 1;
    }
    .cv-mini-lbl { font-size: 0.71rem; color: var(--cv-muted); margin-top: 2px; }
    .cv-mini-prog {
        flex: 1; height: 4px;
        background: var(--cv-border); border-radius: 2px;
        overflow: hidden; margin-top: 5px;
    }
    .cv-mini-prog-fill {
        height: 100%; background: var(--cv-ok);
        border-radius: 2px; transition: width 0.6s ease;
    }

    /* ────────────────── ACCORDION (enlaces) ────────────────── */
    .cv-acc-list { display: flex; flex-direction: column; gap: 10px; }
    .cv-acc {
        background: var(--d-card, #fff);
        border: 1px solid var(--d-card-border, var(--cv-border));
        border-radius: var(--cv-r-md);
        overflow: hidden; box-shadow: var(--cv-sh-sm);
        transition: box-shadow 0.2s;
    }
    .cv-acc.open { box-shadow: 0 4px 20px rgba(0,0,0,0.07); border-color: rgba(252,123,4,0.2); }
    .cv-acc-toggle {
        width: 100%; display: flex; align-items: center;
        gap: 12px; padding: 13px 17px;
        border: none; background: var(--d-header-bg, var(--cv-surface));
        cursor: pointer; text-align: left;
        transition: background 0.15s; flex-wrap: wrap; row-gap: 8px;
    }
    .cv-acc-toggle:hover { background: var(--cv-orange-lt); }
    .cv-acc.open .cv-acc-toggle {
        background: var(--d-card, #fff);
        border-bottom: 1px solid var(--d-card-border, var(--cv-border));
    }
    .cv-acc-icon {
        width: 36px; height: 36px; border-radius: var(--cv-r-sm);
        background: var(--cv-orange-lt); color: var(--cv-orange);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.95rem; flex-shrink: 0;
    }
    .cv-acc-info { flex: 1; min-width: 0; }
    .cv-acc-name {
        font-size: 0.88rem; font-weight: 700; color: var(--cv-text);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .cv-acc-url-preview {
        display: flex; align-items: center; gap: 4px;
        font-size: 0.72rem; color: var(--cv-muted);
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        margin-top: 2px;
    }
    .cv-acc-meta {
        display: flex; align-items: center; gap: 6px;
        flex-wrap: wrap; flex-shrink: 0;
    }
    .cv-chip {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 20px;
        font-size: 0.67rem; font-weight: 600; white-space: nowrap;
    }
    .cv-chip-gray { background: var(--cv-surface); border: 1px solid var(--cv-border); color: var(--cv-muted); }
    .cv-chip-rec  { background: rgba(46,154,110,0.1); border: 1px solid rgba(46,154,110,0.22); color: var(--cv-ok); }
    .cv-chip-on   { background: rgba(46,154,110,0.1); border: 1px solid rgba(46,154,110,0.22); color: var(--cv-ok); }
    .cv-chip-off  { background: rgba(120,120,120,0.08); border: 1px solid rgba(120,120,120,0.18); color: var(--cv-muted); }

    /* Recording progress in accordion header */
    .cv-prog {
        display: flex; align-items: center; gap: 6px; flex-shrink: 0;
    }
    .cv-prog-bar {
        width: 56px; height: 4px;
        background: var(--cv-border); border-radius: 2px; overflow: hidden;
    }
    .cv-prog-fill { height: 100%; background: var(--cv-ok); border-radius: 2px; }
    .cv-prog-txt { font-size: 0.66rem; color: var(--cv-muted); white-space: nowrap; }

    .cv-acc-arrow {
        width: 27px; height: 27px; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.95rem; color: var(--cv-muted);
        background: rgba(0,0,0,0.04); flex-shrink: 0;
        transition: transform 0.2s, color 0.2s;
    }
    .cv-acc.open .cv-acc-arrow { transform: rotate(180deg); color: var(--cv-orange); }

    /* Body of each accordion */
    .cv-acc-body { display: none; }
    .cv-acc.open .cv-acc-body { display: block; }

    /* URL action bar */
    .cv-url-bar {
        padding: 9px 17px;
        display: flex; align-items: center; gap: 10px;
        background: var(--cv-surface);
        border-bottom: 1px solid var(--cv-border);
        flex-wrap: wrap; row-gap: 6px;
    }
    .cv-url-txt {
        flex: 1; min-width: 0;
        font-size: 0.76rem;
        font-family: 'Consolas', 'Courier New', monospace;
        color: var(--cv-muted);
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .cv-url-actions { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
    .cv-copied {
        display: none; align-items: center; gap: 4px;
        font-size: 0.7rem; color: var(--cv-ok); font-weight: 600;
    }
    .cv-copied.show { display: inline-flex; }
    .cv-btn-sm {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 11px;
        border-radius: var(--cv-r-sm);
        font-size: 0.72rem; font-weight: 600;
        cursor: pointer; text-decoration: none;
        transition: all 0.2s; white-space: nowrap; border: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .cv-btn-copy {
        background: rgba(0,0,0,0.04);
        color: var(--cv-muted);
        border: 1px solid var(--cv-border) !important;
    }
    .cv-btn-copy:hover { background: var(--cv-orange-lt); color: var(--cv-orange); border-color: rgba(252,123,4,0.28) !important; }
    .cv-btn-open {
        background: var(--cv-orange-lt);
        color: var(--cv-orange);
        border: 1px solid rgba(252,123,4,0.22) !important;
    }
    .cv-btn-open:hover { background: var(--cv-orange); color: #fff; }

    /* Filter bar */
    .cv-filter-bar {
        padding: 9px 17px;
        display: flex; align-items: center; gap: 10px;
        border-bottom: 1px solid var(--cv-border);
        flex-wrap: wrap;
    }
    .cv-filter-wrap { position: relative; flex: 1; max-width: 280px; }
    .cv-filter-wrap i {
        position: absolute; left: 10px; top: 50%;
        transform: translateY(-50%);
        font-size: 0.82rem; color: var(--cv-muted);
        pointer-events: none;
    }
    .cv-filter-input {
        width: 100%;
        padding: 6px 10px 6px 30px;
        border: 1px solid var(--cv-border);
        border-radius: 20px;
        font-size: 0.77rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--cv-text);
        background: #fff; outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .cv-filter-input:focus {
        border-color: var(--cv-orange);
        box-shadow: 0 0 0 3px rgba(252,123,4,0.08);
    }
    .cv-filter-stats { font-size: 0.71rem; color: var(--cv-muted); white-space: nowrap; margin-left: auto; }

    /* Sessions table */
    .cv-table { width: 100%; border-collapse: collapse; }
    .cv-table thead tr { background: var(--d-header-bg, var(--cv-surface)); }
    .cv-table th {
        padding: 9px 14px;
        font-size: 0.67rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.05em;
        color: var(--cv-muted);
        border-bottom: 1px solid var(--cv-border);
        white-space: nowrap;
    }
    .cv-table td {
        padding: 10px 14px;
        font-size: 0.82rem;
        border-bottom: 1px solid var(--d-row-border, #f0ebe4);
        vertical-align: middle;
    }
    .cv-table tbody tr:last-child td { border-bottom: none; }
    .cv-table tbody tr:hover td { background: var(--cv-orange-lt); }
    .cv-table tbody tr.has-rec td { background: rgba(46,154,110,0.025); }
    .cv-table tbody tr.has-rec:hover td { background: rgba(46,154,110,0.07); }

    .cv-mod-name { font-weight: 600; font-size: 0.83rem; }
    .cv-mod-num  { font-size: 0.67rem; color: var(--cv-muted); margin-top: 2px; }
    .cv-row-num  { font-size: 0.74rem; color: var(--cv-muted); font-weight: 600; }

    .cv-state {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 20px;
        font-size: 0.66rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.03em;
    }
    .cv-state.programado   { background: #e8f0fe; color: #3d5af1; }
    .cv-state.realizado    { background: #e6f4ee; color: var(--cv-ok); }
    .cv-state.cancelado    { background: #fde8e8; color: var(--cv-err); }
    .cv-state.reprogramado { background: #fff4e0; color: var(--cv-warn); }
    .cv-state.pendiente    { background: #fdfbe4; color: #9a8200; }
    .cv-state.default      { background: #f0f0f0; color: var(--cv-muted); }

    .cv-rec-btn {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px;
        background: rgba(46,154,110,0.09);
        border: 1px solid rgba(46,154,110,0.22);
        border-radius: var(--cv-r-sm);
        color: var(--cv-ok); text-decoration: none;
        font-size: 0.7rem; font-weight: 600;
        transition: all 0.2s; white-space: nowrap;
    }
    .cv-rec-btn:hover { background: var(--cv-ok); color: #fff; }
    .cv-no-rec {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 0.7rem; color: var(--cv-muted);
    }

    /* Empty states */
    .cv-empty-page {
        text-align: center; padding: 50px 24px;
        color: var(--cv-muted);
    }
    .cv-empty-page i { font-size: 2.8rem; opacity: 0.28; display: block; margin-bottom: 12px; }
    .cv-empty-page p { font-size: 0.88rem; margin: 0; }
    .cv-empty-row {
        text-align: center; padding: 26px 18px;
        font-size: 0.8rem; color: var(--cv-muted);
    }
    .cv-empty-row i { font-size: 1.6rem; opacity: 0.28; display: block; margin-bottom: 7px; }

    /* Hidden filter rows */
    .cv-table tbody tr.cv-hidden { display: none; }

    @media (max-width: 768px) {
        .cv-tab-body { padding: 14px; }
        .cv-hero { padding: 14px 16px; }
        .cv-hero-name { font-size: 1.1rem; }
    }
    @media (max-width: 576px) {
        .cv-acc-meta { display: none; }
        .cv-prog { display: none; }
        .cv-acc-toggle { row-gap: 4px; }
    }
</style>
@endsection

@section('content')
@php
    $platformIcons = [
        'Zoom'            => 'ri-video-on-line',
        'Google Meet'     => 'ri-google-line',
        'Microsoft Teams' => 'ri-microsoft-line',
        'Jitsi'           => 'ri-code-s-slash-line',
        'Cisco Webex'     => 'ri-global-line',
        'Otro'            => 'ri-link',
    ];
    $platformIcon  = $platformIcons[$cuenta->plataforma] ?? 'ri-video-line';

    $totalEnlaces  = $cuenta->enlaces->count();
    $totalSesiones = $cuenta->enlaces->sum(fn($e) => $e->horarios->count());
    $conGrab       = $cuenta->enlaces->sum(fn($e) =>
                         $e->horarios->filter(fn($h) => !empty($h->enlace_grabacion))->count());
    $sinGrab       = $totalSesiones - $conGrab;
    $pctGrab       = $totalSesiones > 0 ? round($conGrab / $totalSesiones * 100) : 0;
@endphp

<div class="cv-page">

    {{-- ── Hero ───────────────────────────────── --}}
    <div class="cv-hero">
        <div class="cv-hero-left">
            <div class="cv-hero-avatar"><i class="{{ $platformIcon }}"></i></div>
            <div style="min-width:0;">
                <p class="cv-hero-pre">Cuenta de videollamada</p>
                <h1 class="cv-hero-name">{{ $cuenta->nombre }}</h1>
                <div class="cv-hero-meta">
                    <span class="cv-hero-chip">
                        <i class="{{ $platformIcon }}"></i>{{ $cuenta->plataforma }}
                    </span>
                    <span class="cv-hero-chip {{ $cuenta->activo ? 'ok' : 'off' }}">
                        <i class="{{ $cuenta->activo ? 'ri-checkbox-circle-fill' : 'ri-close-circle-line' }}"></i>
                        {{ $cuenta->activo ? 'Activa' : 'Inactiva' }}
                    </span>
                    @if($cuenta->created_at)
                    <span>
                        <i class="ri-calendar-line"></i>
                        Registrada el {{ $cuenta->created_at->format('d/m/Y') }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="cv-hero-right">
            <a href="{{ route('admin.cuentas-videollamada.index') }}" class="cv-back">
                <i class="ri-arrow-left-line"></i> Volver al listado
            </a>
        </div>
    </div>

    {{-- ── Stats ──────────────────────────────── --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-lg-3">
            <div class="cv-stat">
                <div class="cv-stat-ico" style="background:var(--cv-orange-lt);">
                    <i class="ri-links-line" style="color:var(--cv-orange);"></i>
                </div>
                <div>
                    <div class="cv-stat-num">{{ $totalEnlaces }}</div>
                    <div class="cv-stat-lbl">{{ $totalEnlaces === 1 ? 'Enlace' : 'Enlaces' }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="cv-stat">
                <div class="cv-stat-ico" style="background:#e8f0fe;">
                    <i class="ri-calendar-check-line" style="color:var(--cv-blue);"></i>
                </div>
                <div>
                    <div class="cv-stat-num">{{ $totalSesiones }}</div>
                    <div class="cv-stat-lbl">{{ $totalSesiones === 1 ? 'Sesión total' : 'Sesiones totales' }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="cv-stat">
                <div class="cv-stat-ico" style="background:rgba(46,154,110,0.1);">
                    <i class="ri-record-circle-line" style="color:var(--cv-ok);"></i>
                </div>
                <div>
                    <div class="cv-stat-num">{{ $conGrab }}</div>
                    <div class="cv-stat-lbl">Con grabación</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="cv-stat">
                <div class="cv-stat-ico" style="background:rgba(120,120,120,0.07);">
                    <i class="ri-video-off-line" style="color:var(--cv-muted);"></i>
                </div>
                <div>
                    <div class="cv-stat-num">{{ $sinGrab }}</div>
                    <div class="cv-stat-lbl">Sin grabación</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Main card ──────────────────────────── --}}
    <div class="cv-card">

        {{-- Tab nav --}}
        <div class="cv-tabs-nav">
            <button class="cv-tab-btn active" data-tab="info">
                <i class="ri-information-line"></i> Información
            </button>
            <button class="cv-tab-btn" data-tab="enlaces">
                <i class="ri-links-line"></i> Enlaces
                <span class="cv-tab-count">{{ $totalEnlaces }}</span>
            </button>
        </div>

        {{-- ── Tab: Información ── --}}
        <div class="cv-tab-body active" id="tab-info">
            <div class="cv-info-grid">

                {{-- Card 1: Datos de la cuenta --}}
                <div class="cv-icard">
                    <div class="cv-icard-head">
                        <div class="cv-icard-ico"><i class="ri-bank-card-line"></i></div>
                        <p class="cv-icard-title">Datos de la cuenta</p>
                    </div>
                    <div class="cv-field">
                        <div class="cv-field-ico"><i class="ri-text"></i></div>
                        <div>
                            <div class="cv-field-lbl">Nombre</div>
                            <div class="cv-field-val">{{ $cuenta->nombre }}</div>
                        </div>
                    </div>
                    <div class="cv-field">
                        <div class="cv-field-ico"><i class="{{ $platformIcon }}"></i></div>
                        <div>
                            <div class="cv-field-lbl">Plataforma</div>
                            <div class="cv-field-val">{{ $cuenta->plataforma }}</div>
                        </div>
                    </div>
                    <div class="cv-field">
                        <div class="cv-field-ico">
                            <i class="{{ $cuenta->activo ? 'ri-toggle-line' : 'ri-toggle-fill' }}"
                               style="color:{{ $cuenta->activo ? 'var(--cv-ok)' : 'var(--cv-muted)' }};"></i>
                        </div>
                        <div>
                            <div class="cv-field-lbl">Estado</div>
                            <div class="cv-field-val">
                                @if($cuenta->activo)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary">Inactiva</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="cv-field">
                        <div class="cv-field-ico"><i class="ri-hashtag"></i></div>
                        <div>
                            <div class="cv-field-lbl">ID interno</div>
                            <div class="cv-field-val">#{{ $cuenta->id }}</div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Actividad --}}
                <div class="cv-icard">
                    <div class="cv-icard-head">
                        <div class="cv-icard-ico"><i class="ri-bar-chart-grouped-line"></i></div>
                        <p class="cv-icard-title">Actividad</p>
                    </div>
                    <div class="cv-mini">
                        <div class="cv-mini-ico" style="background:var(--cv-orange-lt);">
                            <i class="ri-links-line" style="color:var(--cv-orange);"></i>
                        </div>
                        <div style="flex:1;">
                            <div class="cv-mini-num">{{ $totalEnlaces }}</div>
                            <div class="cv-mini-lbl">{{ $totalEnlaces === 1 ? 'Enlace registrado' : 'Enlaces registrados' }}</div>
                        </div>
                    </div>
                    <div class="cv-mini">
                        <div class="cv-mini-ico" style="background:#e8f0fe;">
                            <i class="ri-calendar-2-line" style="color:var(--cv-blue);"></i>
                        </div>
                        <div style="flex:1;">
                            <div class="cv-mini-num">{{ $totalSesiones }}</div>
                            <div class="cv-mini-lbl">{{ $totalSesiones === 1 ? 'Sesión asociada' : 'Sesiones asociadas' }}</div>
                        </div>
                    </div>
                    <div class="cv-mini">
                        <div class="cv-mini-ico" style="background:rgba(46,154,110,0.1);">
                            <i class="ri-record-circle-line" style="color:var(--cv-ok);"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="cv-mini-num">{{ $pctGrab }}%</div>
                            <div class="cv-mini-lbl">Sesiones con grabación ({{ $conGrab }}/{{ $totalSesiones }})</div>
                            <div class="cv-mini-prog">
                                <div class="cv-mini-prog-fill" style="width:{{ $pctGrab }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Registro --}}
                <div class="cv-icard">
                    <div class="cv-icard-head">
                        <div class="cv-icard-ico"><i class="ri-time-line"></i></div>
                        <p class="cv-icard-title">Registro</p>
                    </div>
                    <div class="cv-field">
                        <div class="cv-field-ico"><i class="ri-calendar-add-line"></i></div>
                        <div>
                            <div class="cv-field-lbl">Fecha de creación</div>
                            @if($cuenta->created_at)
                                <div class="cv-field-val">{{ $cuenta->created_at->format('d/m/Y') }}</div>
                                <div class="cv-field-sub">{{ $cuenta->created_at->format('H:i') }} hrs</div>
                            @else
                                <div class="cv-field-val dim">—</div>
                            @endif
                        </div>
                    </div>
                    <div class="cv-field">
                        <div class="cv-field-ico"><i class="ri-edit-2-line"></i></div>
                        <div>
                            <div class="cv-field-lbl">Última actualización</div>
                            @if($cuenta->updated_at)
                                <div class="cv-field-val">{{ $cuenta->updated_at->format('d/m/Y') }}</div>
                                <div class="cv-field-sub">{{ $cuenta->updated_at->format('H:i') }} hrs</div>
                            @else
                                <div class="cv-field-val dim">—</div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>{{-- /cv-info-grid --}}
        </div>{{-- /tab info --}}

        {{-- ── Tab: Enlaces ── --}}
        <div class="cv-tab-body" id="tab-enlaces">
            @if($cuenta->enlaces->isEmpty())
                <div class="cv-empty-page">
                    <i class="ri-link-unlink"></i>
                    <p>Esta cuenta no tiene enlaces de videollamada registrados.</p>
                </div>
            @else
                <div class="cv-acc-list">
                    @foreach($cuenta->enlaces as $enlace)
                    @php
                        $sesiones  = $enlace->horarios;
                        $grabCount = $sesiones->filter(fn($h) => !empty($h->enlace_grabacion))->count();
                        $sesCount  = $sesiones->count();
                        $pct       = $sesCount > 0 ? round($grabCount / $sesCount * 100) : 0;
                        $enlaceId  = 'acc-' . $enlace->id;
                    @endphp
                    <div class="cv-acc {{ $loop->first ? 'open' : '' }}" id="{{ $enlaceId }}">

                        {{-- Toggle button --}}
                        <button class="cv-acc-toggle" type="button" data-acc="{{ $enlaceId }}">
                            <div class="cv-acc-icon"><i class="ri-link"></i></div>
                            <div class="cv-acc-info">
                                <div class="cv-acc-name">{{ $enlace->nombre }}</div>
                                <div class="cv-acc-url-preview">
                                    <i class="ri-global-line" style="flex-shrink:0;"></i>
                                    <span>{{ $enlace->enlace }}</span>
                                </div>
                            </div>
                            <div class="cv-acc-meta">
                                @if($sesCount > 0)
                                <div class="cv-prog">
                                    <div class="cv-prog-bar">
                                        <div class="cv-prog-fill" style="width:{{ $pct }}%;"></div>
                                    </div>
                                    <span class="cv-prog-txt">{{ $grabCount }}/{{ $sesCount }}</span>
                                </div>
                                @endif
                                @if($grabCount > 0)
                                <span class="cv-chip cv-chip-rec">
                                    <i class="ri-record-circle-line"></i>
                                    {{ $grabCount }} grabación{{ $grabCount > 1 ? 'es' : '' }}
                                </span>
                                @endif
                                <span class="cv-chip cv-chip-gray">
                                    <i class="ri-calendar-line"></i>
                                    {{ $sesCount }} sesión{{ $sesCount !== 1 ? 'es' : '' }}
                                </span>
                                @if($enlace->activo)
                                    <span class="cv-chip cv-chip-on">
                                        <i class="ri-checkbox-circle-line"></i>Activo
                                    </span>
                                @else
                                    <span class="cv-chip cv-chip-off">
                                        <i class="ri-close-circle-line"></i>Inactivo
                                    </span>
                                @endif
                            </div>
                            <div class="cv-acc-arrow"><i class="ri-arrow-down-s-line"></i></div>
                        </button>

                        {{-- Body --}}
                        <div class="cv-acc-body">

                            {{-- URL bar --}}
                            <div class="cv-url-bar">
                                <i class="ri-links-line" style="color:var(--cv-orange);flex-shrink:0;"></i>
                                <span class="cv-url-txt">{{ $enlace->enlace }}</span>
                                <div class="cv-url-actions">
                                    <span class="cv-copied" id="tip-{{ $enlace->id }}">
                                        <i class="ri-check-line"></i> ¡Copiado!
                                    </span>
                                    <button class="cv-btn-sm cv-btn-copy"
                                            onclick="cvCopy({{ $enlace->id }}, '{{ addslashes($enlace->enlace) }}')">
                                        <i class="ri-file-copy-line"></i> Copiar URL
                                    </button>
                                    <a href="{{ $enlace->enlace }}" target="_blank" rel="noopener"
                                       class="cv-btn-sm cv-btn-open">
                                        <i class="ri-external-link-line"></i> Abrir
                                    </a>
                                </div>
                            </div>

                            {{-- Sessions --}}
                            @if($sesiones->isEmpty())
                                <div class="cv-empty-row">
                                    <i class="ri-calendar-close-line"></i>
                                    Sin sesiones asignadas a este enlace.
                                </div>
                            @else
                                {{-- Filter bar --}}
                                <div class="cv-filter-bar">
                                    <div class="cv-filter-wrap">
                                        <i class="ri-search-line"></i>
                                        <input type="text" class="cv-filter-input"
                                               placeholder="Buscar por módulo o fecha…"
                                               data-table="{{ $enlace->id }}"
                                               oninput="cvFilter(this)">
                                    </div>
                                    <span class="cv-filter-stats" id="fst-{{ $enlace->id }}">
                                        {{ $sesCount }} {{ $sesCount === 1 ? 'sesión' : 'sesiones' }}
                                    </span>
                                </div>

                                <div class="table-responsive">
                                    <table class="cv-table" id="tbl-{{ $enlace->id }}">
                                        <thead>
                                            <tr>
                                                <th style="width:36px;">#</th>
                                                <th>Módulo</th>
                                                <th>Fecha</th>
                                                <th>Horario</th>
                                                <th>Estado</th>
                                                <th>Grabación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sesiones as $idx => $h)
                                            @php
                                                $hasRec  = !empty($h->enlace_grabacion);
                                                $est     = strtolower($h->estado ?? '');
                                                $validEs = in_array($est,['programado','realizado','cancelado','reprogramado','pendiente']);
                                                $fecha   = $h->fecha ? $h->fecha->format('d/m/Y') : '—';
                                                $mNom    = $h->modulo?->nombre ?? null;
                                                $mNum    = $h->modulo?->n_modulo ?? null;
                                                $hi      = $h->hora_inicio ? substr($h->hora_inicio, 0, 5) : null;
                                                $hf      = $h->hora_fin    ? substr($h->hora_fin,    0, 5) : null;
                                                $search  = strtolower(($mNom ?? '') . ' ' . $fecha);
                                            @endphp
                                            <tr class="{{ $hasRec ? 'has-rec' : '' }}"
                                                data-search="{{ $search }}">
                                                <td class="cv-row-num">{{ $idx + 1 }}</td>
                                                <td>
                                                    @if($mNom)
                                                        <div class="cv-mod-name">{{ $mNom }}</div>
                                                        @if($mNum)
                                                            <div class="cv-mod-num">Módulo {{ $mNum }}</div>
                                                        @endif
                                                    @else
                                                        <span style="color:var(--cv-muted);">—</span>
                                                    @endif
                                                </td>
                                                <td style="white-space:nowrap;">{{ $fecha }}</td>
                                                <td style="white-space:nowrap;">
                                                    @if($hi && $hf)
                                                        {{ $hi }}<span style="color:var(--cv-muted);margin:0 3px;">–</span>{{ $hf }}
                                                    @else —
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="cv-state {{ $validEs ? $est : 'default' }}">
                                                        {{ ucfirst($h->estado ?? '—') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($hasRec)
                                                        <a href="{{ $h->enlace_grabacion }}"
                                                           target="_blank" rel="noopener"
                                                           class="cv-rec-btn">
                                                            <i class="ri-play-circle-fill"></i> Ver grabación
                                                        </a>
                                                    @else
                                                        <span class="cv-no-rec">
                                                            <i class="ri-close-circle-line"></i> Sin grabación
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        </div>{{-- /cv-acc-body --}}
                    </div>{{-- /cv-acc --}}
                    @endforeach
                </div>{{-- /cv-acc-list --}}
            @endif
        </div>{{-- /tab-enlaces --}}

    </div>{{-- /cv-card --}}

</div>{{-- /cv-page --}}
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ── Accordion ────────────────────────────────────────── */
    document.querySelectorAll('.cv-acc-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var el = document.getElementById(this.dataset.acc);
            if (el) el.classList.toggle('open');
        });
    });

    /* ── Tabs ─────────────────────────────────────────────── */
    document.querySelectorAll('.cv-tab-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.cv-tab-btn').forEach(function (b) { b.classList.remove('active'); });
            document.querySelectorAll('.cv-tab-body').forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');
            var body = document.getElementById('tab-' + this.dataset.tab);
            if (body) body.classList.add('active');
        });
    });

    /* ── Copy URL ─────────────────────────────────────────── */
    window.cvCopy = function (id, url) {
        var show = function () {
            var tip = document.getElementById('tip-' + id);
            if (!tip) return;
            tip.classList.add('show');
            setTimeout(function () { tip.classList.remove('show'); }, 2000);
        };
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(show).catch(fallback);
        } else {
            fallback();
        }
        function fallback() {
            var ta = document.createElement('textarea');
            ta.value = url;
            ta.style.cssText = 'position:fixed;opacity:0;top:0;left:0;';
            document.body.appendChild(ta);
            ta.focus(); ta.select();
            try { document.execCommand('copy'); show(); } catch (e) {}
            document.body.removeChild(ta);
        }
    };

    /* ── Filter rows ──────────────────────────────────────── */
    window.cvFilter = function (input) {
        var id    = input.dataset.table;
        var q     = input.value.trim().toLowerCase();
        var tbody = document.querySelector('#tbl-' + id + ' tbody');
        var stat  = document.getElementById('fst-' + id);
        if (!tbody) return;
        var visible = 0;
        tbody.querySelectorAll('tr').forEach(function (tr) {
            var match = !q || (tr.dataset.search || '').includes(q);
            tr.classList.toggle('cv-hidden', !match);
            if (match) visible++;
        });
        if (stat) stat.textContent = visible + (visible === 1 ? ' sesión' : ' sesiones');
    };
})();
</script>
@endpush
