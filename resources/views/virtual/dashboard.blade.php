@extends('layouts.virtual')
@section('title')
    Mi Portal Virtual
@endsection

@if (session('route_not_found'))
    <div class="container mt-3">
        <div class="alert alert-warning" role="alert">
            {{ session('route_not_found') }}
        </div>
    </div>
@endif

@section('css')
    @include('virtual.partials.styles')
    <style>
    .quiz-pregunta-html .qtext { font-size:.85rem; color:#1e293b; margin-bottom:.85rem; line-height:1.7; }
    .quiz-pregunta-html .ablock .answer { display:flex; flex-direction:column; gap:.4rem; }
    .quiz-pregunta-html .ablock .answer .r0,
    .quiz-pregunta-html .ablock .answer .r1 { padding:.55rem .75rem; border-radius:10px; display:flex; align-items:center; gap:.65rem; font-size:.85rem; transition:all .15s; cursor:pointer; border:1px solid transparent; }
    .quiz-pregunta-html .ablock .answer .r0 { background:#f8fafc; border-color:#eef2f6; }
    .quiz-pregunta-html .ablock .answer .r1 { background:#f1f5f9; border-color:#e2e8f0; }
    .quiz-pregunta-html .ablock .answer .r0:hover,
    .quiz-pregunta-html .ablock .answer .r1:hover { background:#fff; border-color:#fc7b04; box-shadow:0 0 0 3px rgba(252,123,4,.08); }
    .quiz-pregunta-html .ablock .answer .r0 input[type="radio"]:checked ~ label,
    .quiz-pregunta-html .ablock .answer .r1 input[type="radio"]:checked ~ label,
    .quiz-pregunta-html .ablock .answer .r0 input[type="checkbox"]:checked ~ label,
    .quiz-pregunta-html .ablock .answer .r1 input[type="checkbox"]:checked ~ label { color:#fc7b04; }
    .quiz-pregunta-html .ablock .answer input[type="radio"],
    .quiz-pregunta-html .ablock .answer input[type="checkbox"] { accent-color:#fc7b04; width:18px; height:18px; flex-shrink:0; cursor:pointer; }
    .quiz-pregunta-html .ablock .answer label { cursor:pointer; flex:1; font-weight:500; color:#1e293b; }
    .quiz-pregunta-html input[type="text"],
    .quiz-pregunta-html input[type="number"],
    .quiz-pregunta-html textarea { width:100%; border:1.5px solid #d1d5db; border-radius:10px; padding:.6rem .8rem; font-size:.85rem; box-sizing:border-box; transition:border-color .2s,box-shadow .2s; background:#fafbfc; }
    .quiz-pregunta-html input[type="text"]:focus,
    .quiz-pregunta-html input[type="number"]:focus,
    .quiz-pregunta-html textarea:focus { outline:none; border-color:#fc7b04; box-shadow:0 0 0 3px rgba(252,123,4,.12); background:#fff; }
    .quiz-pregunta-html select { border:1.5px solid #d1d5db; border-radius:10px; padding:.45rem .7rem; font-size:.85rem; background:#fafbfc; cursor:pointer; }
    .quiz-pregunta-html select:focus { outline:none; border-color:#fc7b04; box-shadow:0 0 0 3px rgba(252,123,4,.12); background:#fff; }
    .quiz-pregunta { transition:border-color .2s,box-shadow .2s,transform .15s; }
    .quiz-pregunta:hover { border-color:#fc7b04 !important; box-shadow:0 4px 16px rgba(252,123,4,.1); transform:translateY(-1px); }
    .quiz-status-dot { display:inline-block; width:10px; height:10px; border-radius:50%; background:#d1d5db; vertical-align:middle; flex-shrink:0; }
    .quiz-status-dot.answered { background:#16a34a; box-shadow:0 0 0 3px rgba(22,163,74,.2); }

    /* ── Postergado event style (cronograma estudiante) ── */
    .cronograma-calendar-wrapper .fc-event-postergado {
        background: transparent !important;
        border: 1.5px dashed #94a3b8 !important;
        box-shadow: none !important;
    }
    .cronograma-calendar-wrapper .fc-event-postergado .fc-event-title,
    .cronograma-calendar-wrapper .fc-event-postergado .fc-event-time {
        color: inherit !important;
    }

    /* ── Modal detalle sesión estudiante (nuevas clases) ── */
    .cronograma-modal-label {
        font-size: .68rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .04em; color: #94a3b8; margin-bottom: 2px;
    }
    .cronograma-modal-value {
        font-size: .88rem; font-weight: 700; color: #1e293b; line-height: 1.3;
    }
    .cronograma-modal-value-sm {
        font-size: .8rem; font-weight: 600; color: #334155; line-height: 1.2;
    }
    .cronograma-modal-icon {
        width: 34px; height: 34px; border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem; flex-shrink: 0;
    }
    .cronograma-modal-icon-sm {
        width: 28px; height: 28px; border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem; flex-shrink: 0;
    }
    .pers-info-separator {
        display:flex; align-items:center; gap:.5rem; margin:.25rem 0 .15rem;
        font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#94a3b8;
    }
    .pers-info-separator::before,
    .pers-info-separator::after { content:''; flex:1; height:1px; background:#e2e8f0; }

    /* ── Foto editable en tab Personal ── */
    .est-ci-foto-edit-wrap { position: relative; }
    .est-ci-foto-edit-wrap img { display: block; }
    .est-ci-foto-edit-btn {
        position: absolute; left: 0; right: 0; bottom: 0;
        display: flex; align-items: center; justify-content: center; gap: .3rem;
        padding: .45rem .35rem;
        background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,.72) 100%);
        color: #fff; border: none;
        font-size: .68rem; font-weight: 600; letter-spacing: .03em;
        cursor: pointer; opacity: 0; transition: opacity .2s ease;
    }
    .est-ci-foto-edit-wrap:hover .est-ci-foto-edit-btn,
    .est-ci-foto-edit-btn:focus-visible { opacity: 1; }
    .est-ci-foto-edit-btn i { font-size: .85rem; }

    /* ── Modal cambio de foto (estudiante) ── */
    .est-foto-modal .modal-content {
        border: none; border-radius: 16px; overflow: hidden;
        box-shadow: 0 24px 60px rgba(0,0,0,.18);
    }
    .est-foto-modal .modal-header {
        background: linear-gradient(135deg, #9a4904 0%, #df6a04 100%);
        border: none; padding: 1rem 1.25rem;
    }
    .est-foto-modal .modal-title { color: #fff; font-weight: 700; font-size: 1rem; }
    .est-foto-preview-wrap {
        display: flex; justify-content: center; margin-bottom: 1rem;
    }
    .est-foto-preview {
        width: 140px; height: 175px; object-fit: cover;
        border-radius: 10px; border: 3px solid #e2e8f0;
        box-shadow: 0 6px 18px rgba(0,0,0,.12);
    }
    .est-foto-drop {
        border: 2px dashed #cbd5e1; border-radius: 12px;
        padding: 1.5rem 1rem; text-align: center; cursor: pointer;
        background: #f8fafc; transition: all .2s ease;
    }
    .est-foto-drop:hover { background: #fff7ed; border-color: #fc7b04; }
    .est-foto-drop i { font-size: 1.8rem; color: #9a4904; display: block; margin-bottom: .35rem; }
    .est-foto-btn-save {
        background: linear-gradient(135deg, #9a4904, #df6a04);
        color: #fff; border: none; padding: .55rem 1.2rem;
        border-radius: 8px; font-weight: 600; font-size: .85rem;
        display: inline-flex; align-items: center; gap: .35rem;
        transition: all .2s ease;
    }
    .est-foto-btn-save:disabled { opacity: .5; cursor: not-allowed; }
    .est-foto-btn-save:not(:disabled):hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(154,73,4,.3); }

    /* ── Académico — Selector de programas (tarjetas) ─────────────── */
    .acad-progs-wrap {
        margin-bottom: 1.25rem;
    }
    .acad-progs-head {
        display: flex; align-items: center; justify-content: space-between; gap: .75rem;
        margin-bottom: .75rem; flex-wrap: wrap;
    }
    .acad-progs-head-title {
        display: inline-flex; align-items: center; gap: .5rem;
        font-size: .78rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: #475569;
    }
    .acad-progs-head-title i { color: #fc7b04; font-size: 1rem; }
    .acad-progs-head-hint {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .72rem; color: #94a3b8; font-style: italic;
    }
    .acad-progs-head-hint i { color: #fc7b04; }

    .acad-progs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
        gap: .9rem;
    }
    .acad-prog-card {
        position: relative;
        display: flex; flex-direction: column;
        width: 100%; min-width: 0;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 0;
        text-align: left;
        text-transform: none;
        cursor: pointer;
        overflow: hidden;
        transition: transform .22s cubic-bezier(.4,0,.2,1),
                    box-shadow .22s ease,
                    border-color .22s ease;
        font-family: inherit;
    }
    .acad-prog-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(154,73,4,.12), 0 4px 10px rgba(0,0,0,.04);
        border-color: var(--prog-color, #fc7b04);
    }
    .acad-prog-card.active {
        border-color: var(--prog-color, #9a4904);
        background: linear-gradient(180deg,
            color-mix(in srgb, var(--prog-color, #9a4904) 5%, #fff) 0%,
            #fff 60%);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--prog-color, #9a4904) 22%, transparent),
                    0 12px 28px rgba(154,73,4,.18);
        transform: translateY(-3px);
    }
    .acad-prog-card-stripe {
        height: 5px; width: 100%;
        background: linear-gradient(90deg,
            var(--prog-color, #9a4904) 0%,
            color-mix(in srgb, var(--prog-color, #9a4904) 60%, #fc7b04) 100%);
    }
    .acad-prog-card-body {
        padding: .9rem 1rem 1rem;
        display: flex; flex-direction: column; gap: .6rem;
        width: 100%; min-width: 0;
        box-sizing: border-box;
    }
    .acad-prog-card-top {
        display: flex; align-items: center; justify-content: space-between;
        gap: .5rem; min-width: 0;
    }
    .acad-prog-card-num {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 32px; height: 32px; padding: 0 .55rem;
        border-radius: 8px;
        background: color-mix(in srgb, var(--prog-color, #9a4904) 12%, transparent);
        color: var(--prog-color, #9a4904);
        font-family: 'Outfit', sans-serif;
        font-weight: 800; font-size: .82rem;
        letter-spacing: .02em;
        flex-shrink: 0;
    }
    .acad-prog-card.active .acad-prog-card-num {
        background: var(--prog-color, #9a4904);
        color: #fff;
    }
    .acad-prog-card-estado {
        display: inline-flex; align-items: center; gap: .3rem;
        font-size: .64rem; font-weight: 700;
        padding: .25rem .55rem; border-radius: 30px;
        text-transform: uppercase; letter-spacing: .03em;
        flex-shrink: 0;
        white-space: nowrap;
        max-width: 60%;
        overflow: hidden; text-overflow: ellipsis;
    }
    .acad-prog-card-estado i { font-size: .55rem; }
    .acad-prog-card-estado.inscrito { background: rgba(34,197,94,.12); color: #15803d; }
    .acad-prog-card-estado.pendiente { background: rgba(252,123,4,.13); color: #c96004; }
    .acad-prog-card-estado.otro { background: rgba(100,116,139,.12); color: #475569; }

    .acad-prog-card-name {
        font-family: 'Outfit', sans-serif;
        font-size: .94rem; font-weight: 700;
        color: #0f172a; line-height: 1.35;
        text-transform: none;
        white-space: normal;
        word-break: break-word;
        overflow-wrap: anywhere;
        hyphens: auto;
        display: block;
        width: 100%;
        max-width: 100%;
    }
    .acad-prog-card.active .acad-prog-card-name {
        color: var(--prog-color, #9a4904);
    }
    .acad-prog-card-meta {
        display: flex; flex-wrap: wrap; gap: .35rem .8rem;
    }
    .acad-prog-card-meta span {
        display: inline-flex; align-items: center; gap: .3rem;
        font-size: .7rem; color: #64748b; font-weight: 500;
    }
    .acad-prog-card-meta i { color: var(--prog-color, #9a4904); font-size: .8rem; }
    .acad-prog-card.active .acad-prog-card-meta span { color: #334155; font-weight: 600; }

    /* ── Conteo simple de módulos (reemplaza la barra de progreso) ── */
    .acad-prog-card-modcount {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .35rem .65rem;
        background: color-mix(in srgb, var(--prog-color, #9a4904) 7%, #f8fafc);
        border: 1px solid color-mix(in srgb, var(--prog-color, #9a4904) 15%, #e2e8f0);
        border-radius: 8px;
        font-size: .72rem; color: #475569;
        align-self: flex-start;
    }
    .acad-prog-card-modcount i { color: var(--prog-color, #9a4904); font-size: .85rem; }
    .acad-prog-card-modcount strong {
        font-family: 'Outfit', sans-serif; color: var(--prog-color, #9a4904);
        font-weight: 800; font-size: .82rem;
    }

    /* ── CTA: Ver módulos / Visualizando módulos ─────────────────── */
    .acad-prog-card-cta {
        display: flex; align-items: center; gap: .55rem;
        margin-top: .35rem;
        padding: .6rem .85rem;
        background: linear-gradient(135deg,
            color-mix(in srgb, var(--prog-color, #9a4904) 6%, #fff) 0%,
            color-mix(in srgb, var(--prog-color, #9a4904) 14%, #fff) 100%);
        border: 1px solid color-mix(in srgb, var(--prog-color, #9a4904) 22%, transparent);
        border-radius: 10px;
        font-size: .78rem; font-weight: 700;
        color: var(--prog-color, #9a4904);
        letter-spacing: .02em;
        transition: background .25s ease, color .25s ease, border-color .25s ease,
                    box-shadow .25s ease, transform .15s ease;
        min-width: 0;
    }
    .acad-prog-card-cta-icon {
        display: inline-flex; align-items: center; justify-content: center;
        width: 26px; height: 26px; border-radius: 7px;
        background: color-mix(in srgb, var(--prog-color, #9a4904) 18%, #fff);
        color: var(--prog-color, #9a4904);
        flex-shrink: 0;
        transition: background .25s ease, color .25s ease;
    }
    .acad-prog-card-cta-icon i { font-size: .95rem; line-height: 1; }
    .acad-prog-card-cta-i-active { display: none; }
    .acad-prog-card-cta-text {
        flex: 1; min-width: 0;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .acad-prog-card-cta-when-active { display: none; }
    .acad-prog-card-arrow {
        margin-left: auto; flex-shrink: 0;
        font-size: 1rem;
        transition: transform .25s ease;
    }
    .acad-prog-card:hover .acad-prog-card-cta {
        background: linear-gradient(135deg,
            color-mix(in srgb, var(--prog-color, #9a4904) 10%, #fff) 0%,
            color-mix(in srgb, var(--prog-color, #9a4904) 20%, #fff) 100%);
    }
    .acad-prog-card:hover .acad-prog-card-arrow { transform: translateX(4px); }

    /* Estado activo */
    .acad-prog-card.active .acad-prog-card-cta-when-idle { display: none; }
    .acad-prog-card.active .acad-prog-card-cta-when-active { display: inline; }
    .acad-prog-card.active .acad-prog-card-cta-i-idle { display: none; }
    .acad-prog-card.active .acad-prog-card-cta-i-active { display: inline-block; }
    .acad-prog-card.active .acad-prog-card-cta {
        background: linear-gradient(135deg,
            var(--prog-color, #9a4904) 0%,
            color-mix(in srgb, var(--prog-color, #9a4904) 75%, #fc7b04) 100%);
        border-color: var(--prog-color, #9a4904);
        color: #fff;
        box-shadow: 0 6px 18px color-mix(in srgb, var(--prog-color, #9a4904) 38%, transparent);
    }
    .acad-prog-card.active .acad-prog-card-cta-icon {
        background: rgba(255,255,255,.22);
        color: #fff;
    }
    .acad-prog-card.active .acad-prog-card-arrow { color: #fff; }

    /* ── Académico — Contexto compacto sobre los módulos ────────── */
    .acad-prog-context {
        display: flex; align-items: center; flex-wrap: wrap;
        gap: .35rem .55rem;
        padding: .65rem .95rem;
        background: linear-gradient(135deg, #fff 0%, #faf7f3 100%);
        border: 1px solid #e2e8f0;
        border-left: 4px solid #fc7b04;
        border-radius: 10px;
        margin-bottom: 1rem;
        font-size: .82rem; color: #475569;
    }
    .acad-prog-context i { color: #fc7b04; }
    .acad-prog-context strong { color: #0f172a; font-weight: 700; }
    .acad-prog-context-label { color: #94a3b8; font-weight: 500; }
    .acad-prog-context-sep { color: #cbd5e1; }
    .acad-prog-context-code {
        font-family: 'Outfit', sans-serif; font-weight: 700;
        background: rgba(252,123,4,.1); color: #c96004;
        padding: .1rem .5rem; border-radius: 6px; font-size: .72rem;
    }
    .acad-prog-context-modcount {
        margin-left: auto;
        display: inline-flex; align-items: center; gap: .3rem;
        font-size: .75rem; font-weight: 600; color: #475569;
        background: rgba(252,123,4,.08);
        padding: .25rem .65rem; border-radius: 30px;
    }

    /* ── Cuenta inactiva ─────────────────────────────────────────── */
    .cuenta-inactiva-card {
        background: linear-gradient(180deg, #fff 0%, #fff7ed 100%);
        border: 1.5px solid #fed7aa;
        border-radius: 18px;
        padding: 2.25rem 2rem;
        text-align: center;
        max-width: 720px;
        margin: 1.25rem auto 0;
        box-shadow: 0 14px 38px rgba(154,73,4,.10), 0 4px 12px rgba(0,0,0,.04);
        position: relative;
        overflow: hidden;
    }
    .cuenta-inactiva-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: linear-gradient(90deg, #b91c1c, #d97706, #b91c1c);
    }
    .cuenta-inactiva-icon {
        width: 78px; height: 78px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #fee2e2 0%, #fef3c7 100%);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 8px 22px rgba(217,119,6,.18);
    }
    .cuenta-inactiva-icon i { font-size: 2.2rem; color: #b91c1c; }
    .cuenta-inactiva-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.6rem; font-weight: 800;
        color: #0f172a; margin: 0 0 .5rem;
        letter-spacing: -.015em;
    }
    .cuenta-inactiva-title span {
        color: #b91c1c;
        background: rgba(185,28,28,.08);
        padding: .1rem .65rem;
        border-radius: 10px;
        margin-left: .25rem;
    }
    .cuenta-inactiva-desc {
        font-size: .95rem; color: #475569;
        line-height: 1.55; margin: 0 auto 1.5rem;
        max-width: 480px;
    }
    .cuenta-inactiva-action {
        display: flex; align-items: flex-start; gap: .9rem;
        background: #fff;
        border: 1px solid #fed7aa;
        border-left: 4px solid #fc7b04;
        border-radius: 12px;
        padding: 1rem 1.15rem;
        text-align: left;
        max-width: 560px; margin: 0 auto 1.25rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.03);
    }
    .cuenta-inactiva-action-icon {
        width: 42px; height: 42px;
        background: linear-gradient(135deg, #fc7b04 0%, #c96004 100%);
        color: #fff; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(252,123,4,.32);
    }
    .cuenta-inactiva-action-label {
        font-size: .68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .08em;
        color: #c96004; margin-bottom: .25rem;
    }
    .cuenta-inactiva-action-text {
        font-size: .88rem; color: #334155; line-height: 1.5;
    }
    .cuenta-inactiva-action-text strong { color: #0f172a; }
    .cuenta-inactiva-channels {
        display: flex; gap: .75rem; justify-content: center;
        flex-wrap: wrap;
    }
    .cuenta-inactiva-channel {
        display: inline-flex; align-items: center; gap: .65rem;
        padding: .7rem 1rem;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        text-decoration: none;
        transition: all .2s ease;
        min-width: 200px;
    }
    .cuenta-inactiva-channel:hover {
        border-color: #fc7b04;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(252,123,4,.18);
    }
    .cuenta-inactiva-channel.wa:hover {
        border-color: #25D366;
        box-shadow: 0 8px 18px rgba(37,211,102,.18);
    }
    .cuenta-inactiva-channel i {
        font-size: 1.3rem; color: #fc7b04; flex-shrink: 0;
    }
    .cuenta-inactiva-channel.wa i { color: #25D366; }
    .cuenta-inactiva-channel span {
        display: flex; flex-direction: column; text-align: left;
    }
    .cuenta-inactiva-channel small {
        font-size: .65rem; color: #94a3b8;
        text-transform: uppercase; letter-spacing: .06em;
        font-weight: 600;
    }
    .cuenta-inactiva-channel strong {
        font-size: .82rem; color: #0f172a;
        font-weight: 700;
    }

    /* Suavizar la animación al cambiar de programa */
    .est-oferta-content { animation: acadFadeIn .35s ease both; }
    @keyframes acadFadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ══════════════════════════════════════════════════════════
       Tarjetas de Módulos — rediseño + grid 3 columnas
    ══════════════════════════════════════════════════════════ */
    .acad-modulos-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        align-items: stretch;
    }
    @media (max-width: 1100px) {
        .acad-modulos-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 680px) {
        .acad-modulos-grid { grid-template-columns: 1fr; }
    }

    .acad-mod-card {
        position: relative;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        display: flex; flex-direction: column;
        min-width: 0;
        transition: transform .22s cubic-bezier(.4,0,.2,1),
                    box-shadow .22s ease,
                    border-color .22s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }
    .acad-mod-card::before {
        content: '';
        position: absolute; top: 0; left: 0; bottom: 0;
        width: 4px;
        background: var(--mod-color, #6366f1);
        transition: width .22s ease;
    }
    .acad-mod-card:hover {
        transform: translateY(-3px);
        border-color: var(--mod-color, #6366f1);
        box-shadow: 0 12px 28px rgba(15,23,42,.10), 0 4px 10px rgba(0,0,0,.04);
    }
    .acad-mod-card:hover::before { width: 6px; }

    /* Card resaltada cuando su panel de actividades está abierto */
    .acad-mod-card.is-activity-open {
        border-color: var(--mod-color, #6366f1);
        background: linear-gradient(180deg,
            color-mix(in srgb, var(--mod-color, #6366f1) 7%, #fff) 0%,
            #fff 70%);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--mod-color, #6366f1) 22%, transparent),
                    0 10px 24px color-mix(in srgb, var(--mod-color, #6366f1) 20%, transparent);
        transform: translateY(-3px);
    }
    .acad-mod-card.is-activity-open::before { width: 6px; }
    .acad-mod-card.is-activity-open .acad-mod-btn.btn-ver-actividades {
        background: var(--mod-color, #6366f1);
        border-color: var(--mod-color, #6366f1);
        color: #fff;
        box-shadow: 0 4px 12px color-mix(in srgb, var(--mod-color, #6366f1) 35%, transparent);
    }

    .acad-mod-stripe { display: none; } /* reemplazado por barra lateral */

    /* Contenedor para los paneles de actividades (fuera del grid) */
    .acad-mod-panels-wrap { margin-top: 1rem; }
    .acad-mod-panels-wrap .est-act-panel {
        display: none;
        margin-top: 1rem;
        border: 1px solid color-mix(in srgb, var(--mod-color, #6366f1) 28%, #e2e8f0);
        border-radius: 12px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(15,23,42,.08);
        animation: acadFadeIn .3s ease both;
    }
    .acad-mod-panels-wrap .est-act-panel.is-open { display: block; }

    .acad-mod-body {
        padding: 1rem 1.1rem 1.1rem 1.25rem;
        display: flex; flex-direction: column; gap: .65rem;
        flex: 1; min-width: 0;
    }

    /* Encabezado: número + badge de acceso */
    .acad-mod-top {
        display: flex; align-items: center; justify-content: space-between;
        gap: .5rem; min-width: 0;
        padding-bottom: .55rem;
        border-bottom: 1px dashed #eef1f5;
    }
    .acad-mod-num {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 38px; height: 32px;
        padding: 0 .6rem;
        border-radius: 8px;
        background: color-mix(in srgb, var(--mod-color, #6366f1) 14%, #fff) !important;
        color: var(--mod-color, #6366f1) !important;
        font-family: 'Outfit', sans-serif;
        font-weight: 800; font-size: .82rem;
        letter-spacing: .02em;
        flex-shrink: 0;
        text-transform: uppercase;
    }

    /* Nombre del módulo */
    .acad-mod-card .acad-mod-name {
        font-family: 'Outfit', sans-serif;
        font-size: .94rem; font-weight: 700;
        color: #0f172a; line-height: 1.35;
        text-transform: none;
        white-space: normal;
        word-break: break-word; overflow-wrap: anywhere; hyphens: auto;
        display: block; width: 100%;
        margin: 0;
    }

    /* Badge de estado de acceso */
    .acad-mod-card .acad-mod-badge {
        display: inline-flex; align-items: center; gap: .25rem;
        padding: .22rem .55rem; border-radius: 30px;
        font-size: .64rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .03em;
        white-space: nowrap; flex-shrink: 0;
    }
    .acad-mod-card .acad-mod-badge.activo  { background: rgba(34,197,94,.13); color: #15803d; }
    .acad-mod-card .acad-mod-badge.blocked { background: rgba(239,68,68,.12); color: #b91c1c; }
    .acad-mod-card .acad-mod-badge.pending { background: rgba(100,116,139,.12); color: #475569; }

    /* Meta info */
    .acad-mod-card .acad-mod-meta {
        display: flex; flex-direction: column; gap: .35rem;
        margin-top: .15rem;
    }
    .acad-mod-card .acad-mod-meta span {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .75rem; color: #475569; font-weight: 500;
        line-height: 1.3;
    }
    .acad-mod-card .acad-mod-meta i {
        color: var(--mod-color, #6366f1);
        font-size: .9rem; flex-shrink: 0;
    }

    /* Bloque "bloqueado por pagos" */
    .acad-mod-card .acad-mod-blocked {
        display: flex; align-items: center; gap: .45rem;
        margin-top: auto;
        padding: .55rem .8rem;
        background: rgba(239,68,68,.06);
        border: 1px solid rgba(239,68,68,.2);
        border-radius: 10px;
        font-size: .76rem; color: #991b1b; font-weight: 600;
    }
    .acad-mod-card .acad-mod-blocked i { font-size: 1rem; flex-shrink: 0; }

    /* Acciones */
    .acad-mod-card .acad-mod-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .5rem;
        margin-top: auto;
        padding-top: .75rem;
        border-top: 1px solid #f1f5f9;
    }
    .acad-mod-card .acad-mod-btn {
        display: inline-flex; align-items: center; justify-content: center;
        gap: .3rem;
        padding: .5rem .65rem;
        border-radius: 9px;
        font-size: .75rem; font-weight: 700;
        letter-spacing: .02em;
        border: 1.5px solid transparent;
        cursor: pointer; text-decoration: none;
        transition: background .2s ease, color .2s ease, border-color .2s ease,
                    transform .15s ease, box-shadow .2s ease;
        flex: initial; min-width: 0;
    }
    .acad-mod-card .acad-mod-btn i { font-size: .9rem; }

    /* Botón "Actividades" — outlined */
    .acad-mod-card .acad-mod-btn.btn-ver-actividades {
        background: #fff;
        border-color: color-mix(in srgb, var(--mod-color, #6366f1) 40%, #e2e8f0);
        color: var(--mod-color, #6366f1);
    }
    .acad-mod-card .acad-mod-btn.btn-ver-actividades:hover {
        background: color-mix(in srgb, var(--mod-color, #6366f1) 8%, #fff);
        border-color: var(--mod-color, #6366f1);
        transform: translateY(-1px);
    }

    /* Botón "Ir al curso" — sólido verde (acción primaria) */
    .acad-mod-card .acad-mod-btn.acad-mod-btn-go {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: #fff;
        border-color: #15803d;
    }
    .acad-mod-card .acad-mod-btn.acad-mod-btn-go:hover {
        background: linear-gradient(135deg, #15803d 0%, #166534 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(22,163,74,.28);
    }

    /* ══════════════════════════════════════════════════════════════
       REFINEMENTS — Contable & Pagos (elegante, estilo ctb-*)
    ══════════════════════════════════════════════════════════════ */

    /* ── Balance card ── */
    .contable-balance-card {
        border: 1px solid #e9e2d9;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px -6px rgba(0,0,0,.06);
        transition: box-shadow .25s ease;
    }
    .contable-balance-card:hover {
        box-shadow: 0 8px 28px -8px rgba(0,0,0,.1);
    }
    .contable-balance-header {
        background: linear-gradient(135deg, #391b04, #5c2d0a) !important;
        padding: 14px 20px;
        display: flex; align-items: center; gap: 10px;
    }
    .contable-balance-header i { color: #fc7b04; font-size: 1.15rem; }
    .contable-balance-title {
        font-family: 'Outfit', sans-serif;
        font-size: .88rem; font-weight: 700; color: #fff; margin: 0;
        letter-spacing: .02em;
    }

    /* ── Stat items ── */
    .contable-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        background: #fff;
    }
    .contable-stat-item {
        display: flex; align-items: center; gap: 14px;
        padding: 20px 20px;
        border-right: 1px solid #f0ebe4;
        transition: background .2s;
    }
    .contable-stat-item:last-child { border-right: none; }
    .contable-stat-item:hover { background: #faf7f3; }
    .contable-stat-icon {
        width: 46px; height: 46px;
        border-radius: 13px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; flex-shrink: 0;
    }
    .contable-stat-icon.pagado   { background: rgba(34,197,94,.12); color: #15803d; }
    .contable-stat-icon.pendiente { background: rgba(245,158,11,.12); color: #b45309; }
    .contable-stat-icon.vencido  { background: rgba(239,68,68,.12); color: #dc2626; }
    .contable-stat-value {
        font-family: 'Outfit', sans-serif;
        font-weight: 800; font-size: 1.1rem; line-height: 1.1; margin-bottom: 2px;
    }
    .contable-stat-value.pagado   { color: #15803d; }
    .contable-stat-value.pendiente { color: #b45309; }
    .contable-stat-value.vencido  { color: #dc2626; }
    .contable-stat-label { font-size: .7rem; color: #7b6f62; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }

    /* ── Tabs wrapper ── */
    .contable-tabs-wrapper {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e9e2d9;
        overflow: hidden;
        box-shadow: 0 4px 20px -6px rgba(0,0,0,.06);
    }
    .contable-tabs-header {
        background: linear-gradient(180deg, #f8f5f1 0%, #f0ebe4 100%) !important;
        padding: 12px 16px;
        border-bottom: 1px solid #e9e2d9;
    }

    /* ── Pill tabs ── */
    .contable-prog-pill {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 9px 15px;
        font-size: .8rem; font-weight: 600;
        color: #7b6f62;
        background: #fff;
        border: 1.5px solid #e9e2d9;
        border-radius: 10px;
        cursor: pointer;
        transition: all .2s ease;
        white-space: nowrap;
    }
    .contable-prog-pill:hover {
        border-color: var(--est-primary);
        color: var(--est-primary);
        background: rgba(252,123,4,.06);
    }
    .contable-prog-pill.active {
        background: var(--est-primary) !important;
        color: #fff;
        border-color: var(--est-primary);
        box-shadow: 0 4px 12px rgba(252,123,4,.22);
    }

    /* ── Mini stats ── */
    .contable-mini-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        border-bottom: 1px solid #f0ebe4;
    }
    .contable-mini-stat {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 18px;
        border-right: 1px solid #f0ebe4;
    }
    .contable-mini-stat:last-child { border-right: none; }
    .contable-mini-icon {
        width: 34px; height: 34px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .contable-mini-icon.green { background: rgba(34,197,94,.1); color: #15803d; }
    .contable-mini-icon.amber { background: rgba(245,158,11,.1); color: #b45309; }
    .contable-mini-icon.red   { background: rgba(239,68,68,.1); color: #dc2626; }
    .contable-mini-val {
        font-family: 'Outfit', sans-serif;
        font-size: .9rem; font-weight: 700; line-height: 1.1;
    }
    .contable-mini-val.green { color: #15803d; }
    .contable-mini-val.amber { color: #b45309; }
    .contable-mini-val.red   { color: #dc2626; }
    .contable-mini-lbl {
        font-size: .62rem; color: #7b6f62;
        text-transform: uppercase; letter-spacing: .04em;
        font-weight: 600;
    }

    /* ── Cuotas table ── */
    .contable-cuotas-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .contable-cuotas-table thead th {
        padding: .6rem .85rem;
        font-size: .62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #7b6f62;
        background: #f8f5f1 !important;
        border-bottom: 1px solid #e9e2d9;
        white-space: nowrap;
    }
    .contable-cuotas-table tbody td {
        padding: .75rem .85rem;
        border-bottom: 1px solid #f0ebe4;
        vertical-align: middle;
        font-size: .82rem;
        color: #1e293b;
    }
    .contable-cuotas-table tbody tr:last-child td { border-bottom: none; }
    .contable-cuotas-table tbody tr:hover td { background: rgba(252,123,4,.035); }
    .contable-cuotas-table .cuota-name {
        font-weight: 600; color: #1e293b;
    }
    .contable-cuotas-table .estado-badge-est {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 20px;
        font-size: .66rem; font-weight: 700; letter-spacing: .02em;
        width: fit-content;
    }
    .contable-cuotas-table .estado-badge-est.pagado {
        background: rgba(34,197,94,.1); color: #15803d;
    }
    .contable-cuotas-table .estado-badge-est.vencido {
        background: rgba(239,68,68,.1); color: #dc2626;
    }
    .contable-cuotas-table .estado-badge-est.pendiente {
        background: rgba(245,158,11,.1); color: #b45309;
    }

    /* ── Progress bar ── */
    .contable-pay-progress {
        display: flex; align-items: center; gap: 12px;
        padding: 14px 18px;
        border-bottom: 1px solid #f0ebe4;
        background: #faf7f3;
    }
    .contable-pay-track {
        flex: 1; height: 8px;
        background: #e4ddd4;
        border-radius: 10px; overflow: hidden;
    }
    .contable-pay-track-fill {
        height: 100%; border-radius: 10px;
        background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
        transition: width .6s ease;
    }
    .contable-pay-track-fill.some {
        background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
    }
    .contable-pay-track-fill.low {
        background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
    }

    /* ── Cuota pay micro bar ── */
    .cuota-pay-micro { gap: 6px; }
    .cuota-pay-micro .track { height: 6px; background: #e4ddd4; }
    .cuota-pay-micro .pct { font-size: .7rem; color: #7b6f62; }

    /* ── Pagos tabs ── */
    .pagos-tabs-wrapper {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e9e2d9;
        overflow: hidden;
        box-shadow: 0 4px 20px -6px rgba(0,0,0,.06);
    }
    .pagos-tabs-header {
        background: linear-gradient(180deg, #f8f5f1 0%, #f0ebe4 100%);
        padding: 12px 16px;
        border-bottom: 1px solid #e9e2d9;
    }

    /* ── Pagos card (2-column) ── */
    .pagos-card {
        border: 1px solid #e9e2d9;
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 10px -4px rgba(0,0,0,.04);
        transition: box-shadow .25s ease;
    }
    .pagos-card:hover {
        box-shadow: 0 6px 20px -8px rgba(0,0,0,.08);
    }
    .pagos-card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: .85rem 1rem;
        background: linear-gradient(180deg, #f8f5f1 0%, #f0ebe4 100%);
        border-bottom: 1px solid #e9e2d9;
    }
    .pagos-card-icon {
        width: 34px; height: 34px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .pagos-card-icon.orange { background: rgba(252,123,4,.12); color: #c96004; }
    .pagos-card-icon.indigo { background: rgba(99,102,241,.12); color: #6366f1; }
    .pagos-card-title {
        font-family: 'Outfit', sans-serif;
        font-size: .82rem; font-weight: 700; color: #1e293b; margin: 0;
    }
    .pagos-card-sub {
        font-size: .68rem; color: #7b6f62; margin-top: 1px;
    }

    /* ── Pagos mini table ── */
    .pagos-mini-table {
        width: 100%;
        border-collapse: collapse;
    }
    .pagos-mini-table thead th {
        padding: .55rem .75rem;
        font-size: .6rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .06em;
        color: #7b6f62;
        background: #f8f5f1;
        border-bottom: 1px solid #e9e2d9;
        white-space: nowrap;
    }
    .pagos-mini-table tbody td {
        padding: .65rem .75rem;
        border-bottom: 1px solid #f0ebe4;
        vertical-align: middle;
        font-size: .78rem;
        color: #1e293b;
    }
    .pagos-mini-table tbody tr:last-child td { border-bottom: none; }
    .pagos-mini-table tbody tr:hover td { background: rgba(252,123,4,.03); }
    .pagos-mini-table .num-cuota {
        width: 24px; height: 24px;
        border-radius: 6px;
        background: #f0ebe4;
        display: inline-flex;
        align-items: center; justify-content: center;
        font-weight: 700; font-size: .68rem;
        color: #7b6f62;
    }
    .pagos-mini-table .fecha-cell { color: #7b6f62; font-size: .72rem; }
    .pagos-cuota-badge {
        display: inline-flex; align-items: center; gap: 3px;
        padding: 3px 9px; border-radius: 20px;
        font-size: .62rem !important; font-weight: 700 !important;
        letter-spacing: .02em;
    }
    .pagos-cuota-badge.pagado   { background: rgba(34,197,94,.1); color: #15803d; }
    .pagos-cuota-badge.vencido  { background: rgba(239,68,68,.1); color: #dc2626; }
    .pagos-cuota-badge.pendiente { background: rgba(245,158,11,.1); color: #b45309; }

    /* ── Pagos comprobante row ── */
    .pagos-comp-row {
        display: flex; align-items: center; gap: 10px;
        padding: .75rem .85rem;
        border-bottom: 1px solid #f0ebe4;
        transition: background .2s;
    }
    .pagos-comp-row:last-child { border-bottom: none; }
    .pagos-comp-row:hover { background: rgba(252,123,4,.03); }
    .pagos-comp-icon {
        width: 34px; height: 34px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .pagos-comp-icon.pdf { background: rgba(220,38,38,.1); color: #dc2626; }
    .pagos-comp-icon.img { background: rgba(99,102,241,.1); color: #6366f1; }
    .pagos-comp-body .top .fecha {
        font-size: .72rem; color: #475569;
        display: flex; align-items: center; gap: 4px;
    }
    .pagos-comp-body .top .cuota-tag {
        display: inline-flex;
        padding: 2px 8px; border-radius: 6px;
        font-size: .62rem; font-weight: 600;
        background: rgba(252,123,4,.08); color: #c96004;
    }
    .pagos-comp-badge {
        display: inline-flex; align-items: center; gap: 3px;
        padding: 3px 9px; border-radius: 20px;
        font-size: .62rem; font-weight: 700;
    }
    .pagos-comp-badge.verificado { background: #dcfce7; color: #15803d; }
    .pagos-comp-badge.revision   { background: #fef3c7; color: #b45309; }
    .pagos-comp-badge.rechazado  { background: #fee2e2; color: #dc2626; }
    .pagos-comp-link {
        width: 28px; height: 28px;
        border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        color: #7b6f62;
        background: #f0ebe4;
        transition: all .2s;
        text-decoration: none;
    }
    .pagos-comp-link:hover { background: rgba(252,123,4,.14); color: #c96004; }
    .pagos-card-empty {
        padding: 2rem; text-align: center;
        color: #7b6f62;
    }
    .pagos-card-empty i { font-size: 1.8rem; opacity: .4; margin-bottom: 8px; }
    .pagos-card-empty p { font-size: .8rem; margin: 0; }

    /* ── Pagos banco card ── */
    .pagos-banco-card {
        border: 1px solid #e9e2d9;
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 10px -4px rgba(0,0,0,.04);
        transition: box-shadow .25s ease;
    }
    .pagos-banco-card:hover {
        box-shadow: 0 6px 20px -8px rgba(0,0,0,.08);
    }
    .pagos-banco-head {
        display: flex; align-items: center; gap: 10px;
        padding: .85rem 1rem;
        background: linear-gradient(180deg, #f8f5f1 0%, #f0ebe4 100%);
        border-bottom: 1px solid #e9e2d9;
    }
    .pagos-banco-icon {
        width: 34px; height: 34px;
        border-radius: 9px;
        background: rgba(252,123,4,.1);
        color: #c96004;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .pagos-banco-name {
        font-family: 'Outfit', sans-serif;
        font-size: .82rem; font-weight: 700; color: #1e293b;
    }
    .pagos-banco-sigla {
        font-size: .65rem; color: #7b6f62;
    }
    .pagos-banco-body {
        padding: .5rem 0;
    }
    .pagos-banco-cuenta {
        display: flex; align-items: center; gap: 10px;
        padding: .65rem 1rem;
        border-bottom: 1px solid #f0ebe4;
    }
    .pagos-banco-cuenta:last-child { border-bottom: none; }
    .pagos-banco-cuenta-num {
        font-family: 'Outfit', sans-serif;
        font-weight: 600; font-size: .82rem; color: #1e293b;
        display: flex; align-items: center; gap: 6px;
    }
    .pagos-banco-cuenta-num i { color: #c96004; }
    .pagos-banco-cuenta-meta {
        display: flex; align-items: center; gap: 6px;
        margin-top: 3px;
    }
    .pagos-banco-badge {
        display: inline-flex; padding: 2px 8px;
        border-radius: 6px; font-size: .62rem; font-weight: 600;
    }
    .pagos-banco-badge.cc { background: rgba(99,102,241,.1); color: #6366f1; }
    .pagos-banco-badge.ca { background: rgba(34,197,94,.1); color: #16a34a; }
    .pagos-banco-titular {
        font-size: .65rem; color: #7b6f62;
        display: flex; align-items: center; gap: 3px;
    }

    /* ── Contable & Pagos program tabs (como admin ctb-tab) ── */
    .ctb-tabs {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
        margin-bottom: 0;
        background: #f8f5f1;
        border-radius: 12px;
        padding: 4px;
        border: 1px solid #e9e2d9;
    }
    .ctb-tab {
        padding: .5rem 1rem;
        border-radius: 9px;
        font-size: .78rem;
        font-weight: 600;
        color: #7b6f62;
        border: none;
        background: transparent;
        cursor: pointer;
        transition: all .2s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .ctb-tab:hover { color: var(--est-primary); }
    .ctb-tab.active {
        background: #fff;
        color: var(--est-primary);
        box-shadow: 0 2px 8px -2px rgba(0,0,0,.08);
    }

    /* ── Pagos buttons ── */
    .pagos-btn-subir {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 14px; border-radius: 8px;
        font-size: .72rem; font-weight: 600;
        border: 1px solid #e9e2d9;
        background: #fff; color: var(--est-primary);
        cursor: pointer; transition: all .2s ease;
    }
    .pagos-btn-subir:hover {
        background: rgba(252,123,4,.08);
        border-color: var(--est-primary);
    }
    .pagos-btn-al-dia {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 5px 12px; border-radius: 20px;
        font-size: .7rem; font-weight: 700;
        background: rgba(34,197,94,.1); color: #15803d;
    }

    /* ── PMP modal classes (para modalVerDetallePago como admin) ── */
    .pmp-modal .modal-dialog { max-width: 1080px; }
    .pmp-modal .modal-dialog.modal-lg { max-width: 760px; }
    .pmp-content {
        border: none; border-radius: 18px; overflow: hidden;
        box-shadow: 0 25px 70px rgba(154,73,4,.18), 0 8px 24px rgba(0,0,0,.08);
    }
    .pmp-header {
        position: relative; display: flex; align-items: center; gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #5c2a04 0%, #9a4904 45%, #c96004 75%, #fc7b04 100%);
        overflow: hidden;
    }
    .pmp-header::before {
        content: '';
        position: absolute; top: -50%; right: -8%;
        width: 280px; height: 280px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.14) 0%, transparent 70%);
        pointer-events: none;
    }
    .pmp-header-icon {
        width: 52px; height: 52px;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.28);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.55rem;
        flex-shrink: 0; z-index: 1;
        box-shadow: 0 4px 14px rgba(0,0,0,.18);
    }
    .pmp-header-text { z-index: 1; min-width: 0; flex: 1; }
    .pmp-header-title {
        font-family: 'Outfit', sans-serif;
        color: #fff; font-weight: 800; font-size: 1.15rem;
        margin: 0 0 2px; letter-spacing: -.015em;
    }
    .pmp-header-sub {
        color: rgba(255,255,255,.85);
        font-size: .8rem; font-weight: 500; display: block; line-height: 1.3;
    }
    .pmp-close-btn {
        z-index: 1; flex-shrink: 0; width: 36px; height: 36px;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.28);
        border-radius: 9px; color: #fff; font-size: 1.15rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background .2s;
    }
    .pmp-close-btn:hover { background: rgba(255,255,255,.3); }
    .pmp-body {
        padding: 1.25rem 1.5rem;
        background: #faf7f3;
    }
    .pmp-section-title {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .7rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .07em;
        color: #c96004; margin-bottom: .6rem;
        padding: .3rem .65rem;
        background: rgba(252,123,4,.08); border-radius: 6px;
    }
    .pmp-section-title i { font-size: .9rem; }
    .pmp-footer {
        display: flex; align-items: center; justify-content: flex-end;
        gap: .65rem; padding: 1rem 1.5rem;
        background: #fff; border-top: 1px solid #e2e8f0;
    }
    .pmp-btn {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .6rem 1.25rem; border-radius: 10px;
        font-size: .85rem; font-weight: 700;
        border: 1.5px solid transparent; cursor: pointer;
        transition: transform .15s ease, box-shadow .2s ease, background .2s ease;
        font-family: inherit; text-decoration: none;
    }
    .pmp-btn-cancel { background: #fff; border-color: #e2e8f0; color: #475569; }
    .pmp-btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; color: #475569; }
    .pmp-btn-submit {
        background: linear-gradient(135deg, #9a4904 0%, #fc7b04 100%);
        color: #fff; border-color: #9a4904;
        box-shadow: 0 4px 14px rgba(154,73,4,.32);
    }
    .pmp-btn-submit:hover { transform: translateY(-1px); box-shadow: 0 8px 22px rgba(154,73,4,.42); color: #fff; }

    /* ── Responsive overrides ── */
    @media (max-width: 767px) {
        .contable-stats-grid { grid-template-columns: 1fr; }
        .contable-stat-item { border-right: none; border-bottom: 1px solid #f0ebe4; }
        .contable-stat-item:last-child { border-bottom: none; }
    }
    </style>
@endsection

@php
    $cuentaActiva = strtolower($user->estado ?? 'activo') === 'activo';
    $tieneFotoReal = $persona && $persona->fotografia && file_exists(public_path('images/personas/' . $persona->fotografia));
    if ($tieneFotoReal) {
        $heroAvatarUrl = asset('images/personas/' . $persona->fotografia);
    } else {
        $sexoHero = $persona?->sexo;
        $defaultFile = $sexoHero === 'F' ? 'mujer.png' : 'chico.png';
        $heroAvatarUrl = asset('images/' . $defaultFile);
    }
@endphp
@section('content')
    <div class="est-hero">
        <img src="{{ $heroAvatarUrl }}" alt="Foto" class="est-hero-avatar" id="est-hero-avatar-img"
            onerror="this.src='{{ URL::asset('build/images/users/avatar-1.jpg') }}'">
        <div style="flex:1;min-width:0;">
            <div class="est-hero-name">
                {{ $persona ? trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')) : $user->name }}
            </div>
            @if ($persona)
                <div class="est-hero-sub">
                    <i class="ri-id-card-line"></i> {{ $persona->carnet }}
                    @if ($persona->correo)
                        &nbsp;·&nbsp;<i class="ri-mail-line"></i> {{ $persona->correo }}
                    @endif
                </div>
            @endif
            <div class="est-hero-badges">
                @if ($esEstudiante && $esDocente)
                    <span class="est-hero-badge est-hero-role ambos"><i class="ri-shield-user-line"></i> Estudiante y Docente</span>
                @elseif ($esEstudiante)
                    <span class="est-hero-badge est-hero-role estudiante"><i class="ri-graduation-cap-line"></i> Estudiante</span>
                @elseif ($esDocente)
                    <span class="est-hero-badge est-hero-role docente"><i class="ri-user-settings-line"></i> Docente</span>
                @endif
                @if ($moodleUserId)
                    <span class="est-hero-badge"><i class="ri-links-line"></i> Moodle activo</span>
                @else
                    <span class="est-hero-badge sin"><i class="ri-close-circle-line"></i> Sin Moodle</span>
                @endif
                @if ($cuentaActiva)
                    <span class="est-hero-badge"><i class="ri-checkbox-circle-line"></i> Sesión activa</span>
                @else
                    <span class="est-hero-badge sin"><i class="ri-shield-cross-line"></i> Cuenta inactiva</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ── SELECTOR DE ROL (solo si tiene ambos roles y cuenta activa) ── --}}
    @if ($esEstudiante && $esDocente && $cuentaActiva)
    <div class="rol-switcher" id="rol-switcher">
        <span class="rol-switcher-label">Ver como</span>
        <div class="rol-switcher-btns">

            <button type="button"
                    id="rol-btn-estudiante"
                    class="rol-btn {{ $perfilActivo === 'estudiante' ? 'active' : '' }}"
                    onclick="cambiarPerfil('estudiante')"
                    {{ $perfilActivo === 'estudiante' ? 'disabled' : '' }}>
                <div class="rol-btn-icon"><i class="ri-graduation-cap-line"></i></div>
                <div class="rol-btn-text">
                    <span class="rol-btn-title">Estudiante</span>
                    <span class="rol-btn-sub">Inscripciones, pagos y cronograma</span>
                </div>
                @if ($perfilActivo === 'estudiante')
                    <div class="rol-btn-check"><i class="ri-check-line"></i></div>
                @endif
                <div class="rol-btn-spinner"></div>
            </button>

            <button type="button"
                    id="rol-btn-docente"
                    class="rol-btn {{ $perfilActivo === 'docente' ? 'active' : '' }}"
                    onclick="cambiarPerfil('docente')"
                    {{ $perfilActivo === 'docente' ? 'disabled' : '' }}>
                <div class="rol-btn-icon"><i class="ri-user-settings-line"></i></div>
                <div class="rol-btn-text">
                    <span class="rol-btn-title">Docente</span>
                    <span class="rol-btn-sub">Módulos, sesiones y horario</span>
                </div>
                @if ($perfilActivo === 'docente')
                    <div class="rol-btn-check"><i class="ri-check-line"></i></div>
                @endif
                <div class="rol-btn-spinner"></div>
            </button>

        </div>
    </div>
    @endif

    @if ($esEstudiante)
    {{-- ── STATS BAR ESTUDIANTE ───────────────────────────────────────── --}}
    @php
        $totalProgramas = $inscripciones->count();
        $totalModulos = $inscripciones->sum(fn($i) => $i->moodleMatriculas->count());
        $activas = $inscripciones->whereIn('estado', ['Inscrito', 'Confirmado'])->count();
    @endphp
    <div class="est-stats" id="stats-estudiante" {!! $perfilActivo !== 'estudiante' ? 'style="display:none"' : '' !!}>
        <div class="est-stat-card-sm">
            <div class="est-stat-icon-sm orange"><i class="ri-book-open-line"></i></div>
            <div>
                <div class="est-stat-num">{{ $totalProgramas }}</div>
                <div class="est-stat-label">Programa(s)</div>
            </div>
        </div>
        <div class="est-stat-card-sm">
            <div class="est-stat-icon-sm blue"><i class="ri-stack-line"></i></div>
            <div>
                <div class="est-stat-num">{{ $totalModulos }}</div>
                <div class="est-stat-label">Módulo(s)</div>
            </div>
        </div>
        <div class="est-stat-card-sm">
            <div class="est-stat-icon-sm green"><i class="ri-check-double-line"></i></div>
            <div>
                <div class="est-stat-num">{{ $activas }}</div>
                <div class="est-stat-label">Inscripción(es) activa(s)</div>
            </div>
        </div>
    </div>
    @endif

    @if ($esDocente)
    @php
        $totalCursos = $modulosDocente->count();
        $totalSesiones = $modulosDocente->sum(fn($m) => $m->horarios->count());
    @endphp
    <div class="est-stats" id="stats-docente" {!! $perfilActivo !== 'docente' ? 'style="display:none"' : '' !!}>
        <div class="est-stat-card-sm">
            <div class="est-stat-icon-sm orange"><i class="ri-book-3-line"></i></div>
            <div>
                <div class="est-stat-num">{{ $totalCursos }}</div>
                <div class="est-stat-label">Curso(s)</div>
            </div>
        </div>
        <div class="est-stat-card-sm">
            <div class="est-stat-icon-sm blue"><i class="ri-calendar-event-line"></i></div>
            <div>
                <div class="est-stat-num">{{ $totalSesiones }}</div>
                <div class="est-stat-label">Sesión(es)</div>
            </div>
        </div>
    </div>
    @endif

    @if (!$cuentaActiva)
        {{-- ── CUENTA INACTIVA — bloqueo de acceso ───────────────────── --}}
        <div class="cuenta-inactiva-card">
            <div class="cuenta-inactiva-icon">
                <i class="ri-shield-cross-line"></i>
            </div>
            <h3 class="cuenta-inactiva-title">Tu cuenta está <span>Inactiva</span></h3>
            <p class="cuenta-inactiva-desc">
                Por el momento no tienes acceso al portal académico.
                Tu cuenta del sistema y de Moodle han sido deshabilitadas.
            </p>
            <div class="cuenta-inactiva-action">
                <div class="cuenta-inactiva-action-icon"><i class="ri-customer-service-2-line"></i></div>
                <div class="cuenta-inactiva-action-body">
                    <div class="cuenta-inactiva-action-label">¿Cómo reactivar mi cuenta?</div>
                    <div class="cuenta-inactiva-action-text">
                        Comunícate con el <strong>Área Contable</strong> de InnovaCiencia Virtual para regularizar tu situación y solicitar la reactivación.
                    </div>
                </div>
            </div>
            <div class="cuenta-inactiva-channels">
                <a href="mailto:contabilidad@innovaciencia.edu.bo" class="cuenta-inactiva-channel">
                    <i class="ri-mail-send-line"></i>
                    <span>
                        <small>Correo</small>
                        <strong>contabilidad@innovaciencia.edu.bo</strong>
                    </span>
                </a>
                <a href="https://wa.me/59100000000" target="_blank" rel="noopener" class="cuenta-inactiva-channel wa">
                    <i class="ri-whatsapp-line"></i>
                    <span>
                        <small>WhatsApp</small>
                        <strong>Contactar</strong>
                    </span>
                </a>
            </div>
        </div>
    @else
    {{-- ── TABS ──────────────────────────────────────────────────────── --}}
    <div class="est-tabs-card">

        @if ($esEstudiante)
        {{-- Navegación estudiante --}}
        <div class="est-tabs-nav" id="nav-estudiante" {!! $perfilActivo !== 'estudiante' ? 'style="display:none"' : '' !!}>
            <button class="est-tab-btn active" onclick="switchTab(this,'tab-personal')">
                <i class="ri-user-3-line"></i> Personal
            </button>
            <button class="est-tab-btn" onclick="switchTab(this,'tab-documentos')">
                <i class="ri-file-paper-line"></i> Documentos
            </button>
            <button class="est-tab-btn" onclick="switchTab(this,'tab-academico')">
                <i class="ri-book-3-line"></i> Académico
            </button>
            <button class="est-tab-btn" onclick="switchTab(this,'tab-contable')">
                <i class="ri-money-dollar-circle-line"></i> Contable
            </button>
            <button class="est-tab-btn" onclick="switchTab(this,'tab-pagos')">
                <i class="ri-file-list-3-line"></i> Pagos
            </button>
            <button class="est-tab-btn" onclick="switchTab(this,'tab-cronograma')">
                <i class="ri-calendar-line"></i> Cronograma
            </button>
        </div>
        @endif

        @if ($esDocente)
        <div class="est-tabs-nav" id="nav-docente" {!! $perfilActivo !== 'docente' ? 'style="display:none"' : '' !!}>
            <button class="est-tab-btn active" onclick="switchTabDocente(this,'tab-personal-docente')">
                <i class="ri-user-3-line"></i> Personal
            </button>
            <button class="est-tab-btn" onclick="switchTabDocente(this,'tab-documentos-docente')">
                <i class="ri-file-paper-line"></i> Documentos
            </button>
            <button class="est-tab-btn" onclick="switchTabDocente(this,'tab-academico-docente')">
                <i class="ri-book-3-line"></i> Académico
            </button>
            <button class="est-tab-btn" onclick="switchTabDocente(this,'tab-horario-docente')">
                <i class="ri-calendar-check-line"></i> Mi Horario
            </button>
        </div>
        @endif

        @if ($esDocente)
        <div id="content-docente" {!! $perfilActivo !== 'docente' ? 'style="display:none"' : '' !!}>

        <div class="est-tabs-body active" id="tab-personal-docente">
            @php
                $tieneFotoDoc = $persona && $persona->fotografia && file_exists(public_path('images/personas/' . $persona->fotografia));
                if ($tieneFotoDoc) {
                    $avatarUrlDoc = asset('images/personas/' . $persona->fotografia);
                } else {
                    $sexoDoc = $persona?->sexo;
                    $defaultFileDoc = $sexoDoc === 'F' ? 'mujer.png' : 'chico.png';
                    $avatarUrlDoc = asset('images/' . $defaultFileDoc);
                }
                $nombreCompletoDoc = $persona
                    ? trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''))
                    : $user->name;
                $inicialesDoc = collect(explode(' ', $nombreCompletoDoc))->filter()->take(2)->map(fn($p) => strtoupper($p[0]))->implode('');
                $edadDoc = $persona && $persona->fecha_nacimiento ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->age : null;
                $ubicacionDoc = $persona && $persona->ciudad
                    ? optional($persona->ciudad)->nombre . ', ' . (optional(optional($persona->ciudad)->departamento)->nombre ?? '')
                    : null;
                $docenteModel = $persona?->docente;
            @endphp
            <div class="est-ci-wrap">
                <div class="est-ci-stripe"></div>
                <div class="est-ci-body">
                    {{-- Izquierda: foto --}}
                    <div class="est-ci-left">
                        <div class="est-ci-foto-label"><i class="ri-building-2-line"></i><span>INNOVA CIENCIA</span></div>
                        <div class="est-ci-foto est-ci-foto-edit-wrap">
                            <img src="{{ $avatarUrlDoc }}" alt="Foto" id="doc-ci-foto-img"
                                onerror="this.src='{{ asset('images/chico.png') }}'">
                            <button type="button" class="est-ci-foto-edit-btn"
                                    onclick="abrirCambioFoto()" title="Cambiar foto">
                                <i class="ri-camera-line"></i>
                                <span>Cambiar foto</span>
                            </button>
                        </div>
                        <div class="est-ci-quick-data">
                            @if ($persona?->carnet)
                                <div class="est-ci-qd-item">
                                    <i class="ri-shield-check-line"></i>
                                    <span class="est-ci-qd-label">CI</span>
                                    <span class="est-ci-qd-val">{{ $persona->carnet }}{{ $persona->expedido ? ' ' . $persona->expedido : '' }}</span>
                                </div>
                            @endif
                            @if ($persona?->fecha_nacimiento)
                                <div class="est-ci-qd-item">
                                    <i class="ri-cake-line"></i>
                                    <span class="est-ci-qd-label">Nacimiento</span>
                                    <span class="est-ci-qd-val">{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') }}</span>
                                </div>
                            @endif
                            @if ($edadDoc)
                                <div class="est-ci-qd-item">
                                    <i class="ri-user-line"></i>
                                    <span class="est-ci-qd-label">Edad</span>
                                    <span class="est-ci-qd-val">{{ $edadDoc }} años</span>
                                </div>
                            @endif
                            @if ($persona?->sexo)
                                <div class="est-ci-qd-item">
                                    <i class="ri-genderless-line"></i>
                                    <span class="est-ci-qd-label">Sexo</span>
                                    <span class="est-ci-qd-val">{{ $persona->sexo == 'M' ? 'Masculino' : ($persona->sexo == 'F' ? 'Femenino' : '—') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="pers-acc-chips">
                            @if ($moodleUserId)
                            <div class="pers-acc-chip pers-chip-ok">
                                <i class="ri-links-line"></i><span>Moodle: Activo</span>
                            </div>
                            @else
                            <div class="pers-acc-chip pers-chip-no">
                                <i class="ri-links-line"></i><span>Moodle: Sin cuenta</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Centro: datos de contacto --}}
                    <div class="est-ci-center">
                        <div class="est-ci-nombre-wrap">
                            <div>
                                <div class="est-ci-nombre">{{ $nombreCompletoDoc }}</div>
                                <div class="est-ci-estado-label">Docente</div>
                            </div>
                            <span class="est-ci-estado-badge est-ci-badge-activo">
                                <i class="ri-checkbox-circle-line"></i> Activo
                            </span>
                        </div>
                        <div class="est-ci-section-title"><i class="ri-contacts-line"></i> Datos de Contacto</div>
                        @if($persona?->correo)
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-mail-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Correo electrónico</div>
                                <div class="pers-contact-val"><a href="mailto:{{ $persona->correo }}" class="pers-contact-link">{{ $persona->correo }}</a></div>
                            </div>
                            <a href="mailto:{{ $persona->correo }}" class="pers-contact-act" title="Enviar correo"><i class="ri-send-plane-line"></i></a>
                        </div>
                        @endif
                        @if($persona?->celular)
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-smartphone-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Celular</div>
                                <div class="pers-contact-val"><a href="tel:{{ $persona->celular }}" class="pers-contact-link">{{ $persona->celular }}</a></div>
                            </div>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $persona->celular) }}" target="_blank" class="pers-contact-act wa" title="WhatsApp"><i class="ri-whatsapp-line"></i></a>
                        </div>
                        @endif
                        @if($persona?->telefono)
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-phone-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Teléfono</div>
                                <div class="pers-contact-val">{{ $persona->telefono }}</div>
                            </div>
                        </div>
                        @endif
                        @if($persona?->estado_civil)
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-heart-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Estado Civil</div>
                                <div class="pers-contact-val">{{ $persona->estado_civil }}</div>
                            </div>
                        </div>
                        @endif
                        @if($ubicacionDoc)
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-map-pin-2-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Ciudad / Departamento</div>
                                <div class="pers-contact-val">{{ $ubicacionDoc }}</div>
                            </div>
                        </div>
                        @endif
                        @if($persona?->direccion)
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-home-3-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Dirección</div>
                                <div class="pers-contact-val">{{ $persona->direccion }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Derecha: datos del docente --}}
                    <div class="est-ci-right">
                        <div class="est-ci-right-header">
                            <i class="ri-user-star-line"></i><span>Datos del Docente</span>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            @if ($docenteModel?->created_at)
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-calendar-check-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Fecha Registro</div>
                                    <div class="pers-info-val">{{ $docenteModel->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                            @endif
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-book-3-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Módulos asignados</div>
                                    <div class="pers-info-val">{{ $modulosDocente->count() }} módulo(s)</div>
                                </div>
                            </div>
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-time-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Total de sesiones</div>
                                    <div class="pers-info-val">{{ $modulosDocente->sum(fn($m) => $m->horarios->count()) }} sesión(es)</div>
                                </div>
                            </div>
                            <div class="pers-info-separator">
                                <span>Accesos del Sistema</span>
                            </div>
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-user-settings-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Usuario del sistema</div>
                                    <div class="pers-info-val" style="font-family:monospace;font-size:.82rem;">{{ $user->username ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-mail-send-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Correo del sistema</div>
                                    <div class="pers-info-val" style="font-size:.82rem;">{{ $user->email ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-links-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Cuenta Moodle</div>
                                    <div class="pers-info-val">
                                        @if ($moodleDocenteId)
                                            <span style="color:var(--doc-success);font-weight:600;"><i class="ri-checkbox-circle-fill"></i> Activa</span>
                                        @else
                                            <span style="color:var(--doc-text-muted);">Sin cuenta Moodle</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="est-ci-bottom-bar">
                    <span><i class="ri-id-card-line"></i> Carnet de Identificación · Docente</span>
                    <span>{{ now()->format('Y') }}</span>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TAB DOCUMENTOS DOCENTE
        ══════════════════════════════════════════════════════════ --}}
        <div class="est-tabs-body" id="tab-documentos-docente">
            @php
                $estadoDocDoc = function ($archivo, $verificado) {
                    if (!$archivo) {
                        return ['label' => 'Pendiente', 'cls' => 'pending', 'icon' => 'ri-add-circle-line'];
                    }
                    if ($verificado) {
                        return ['label' => 'Aprobado', 'cls' => 'approved', 'icon' => 'ri-checkbox-circle-fill'];
                    }
                    return ['label' => 'En revisión', 'cls' => 'review', 'icon' => 'ri-time-line'];
                };
                $docsIdentidadDoc = [
                    [
                        'nombre'    => 'Carnet de Identidad',
                        'icono'     => 'ri-id-card-line',
                        'archivo'   => $persona->fotografia_carnet ?? null,
                        'verificado'=> $persona->carnet_verificado ?? false,
                        'tipo'      => 'fotografia_carnet',
                    ],
                    [
                        'nombre'    => 'Cert. Nacimiento',
                        'icono'     => 'ri-file-paper-line',
                        'archivo'   => $persona->fotografia_certificado_nacimiento ?? null,
                        'verificado'=> $persona->certificado_nacimiento_verificado ?? false,
                        'tipo'      => 'fotografia_certificado_nacimiento',
                    ],
                ];
                $totalDocsDoc = count($docsIdentidadDoc);
                $verificadosDoc = 0;
                foreach ($docsIdentidadDoc as $d) {
                    if ($d['verificado']) $verificadosDoc++;
                }
                $estudioDocente = $persona?->estudios?->first();
                if ($estudioDocente) {
                    $totalDocsDoc += 2;
                    if ($estudioDocente->documento_academico_verificado) $verificadosDoc++;
                    if ($estudioDocente->documento_provision_verificado) $verificadosDoc++;
                }
                $pctDocsDoc = $totalDocsDoc > 0 ? ($verificadosDoc / $totalDocsDoc) * 100 : 0;
            @endphp

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:16px;">
                <h3 style="margin:0;font-size:1.1rem;font-weight:600;display:flex;align-items:center;gap:8px;">
                    <i class="ri-folder-shield-line" style="color:#fc7b04;"></i> Documentación
                </h3>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="flex:1;max-width:150px;height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                        <div style="height:100%;background:linear-gradient(90deg,#fc7b04,#f97316);border-radius:4px;width:{{ $pctDocsDoc }}%;transition:width .3s;"></div>
                    </div>
                    <span style="font-size:.875rem;font-weight:700;color:#fc7b04;">{{ number_format($pctDocsDoc, 0) }}%</span>
                </div>
            </div>

            <h4 style="font-size:1rem;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="ri-id-card-line" style="color:#fc7b04;"></i> Documentación Personal
            </h4>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:32px;">
                @foreach ($docsIdentidadDoc as $doc)
                    @php
                        $estado = $estadoDocDoc($doc['archivo'], $doc['verificado']);
                        $bgIcon   = $estado['cls'] == 'approved' ? '#dcfce7' : ($estado['cls'] == 'review' ? '#e0f2fe' : '#fef3c7');
                        $colorIcon= $estado['cls'] == 'approved' ? '#16a34a' : ($estado['cls'] == 'review' ? '#0891b2' : '#d97706');
                    @endphp
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                        <div style="padding:14px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                            <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:{{ $bgIcon }};color:{{ $colorIcon }};flex-shrink:0;">
                                <i class="{{ $doc['icono'] }}"></i>
                            </div>
                            <div style="flex:1;min-width:0;font-size:.875rem;font-weight:600;">{{ $doc['nombre'] }}</div>
                            <span style="padding:4px 10px;border-radius:20px;font-size:.6875rem;font-weight:600;text-transform:uppercase;background:{{ $bgIcon }};color:{{ $colorIcon }};">
                                {{ $estado['label'] }}
                            </span>
                        </div>
                        <div style="padding:16px;">
                            @if ($doc['archivo'])
                                <div style="display:flex;align-items:center;gap:12px;padding:12px;background:#f8fafc;border-radius:8px;">
                                    <div style="width:36px;height:36px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:1.1rem;">
                                        <i class="ri-file-pdf-fill"></i>
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div style="font-size:.8125rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $doc['tipo'] }}.pdf</div>
                                        <div style="font-size:.6875rem;display:flex;align-items:center;gap:4px;color:{{ $doc['verificado'] ? '#16a34a' : '#d97706' }};">
                                            @if ($doc['verificado'])
                                                <i class="ri-shield-check-fill"></i> Verificado
                                            @else
                                                <i class="ri-time-fill"></i> En revisión
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div style="text-align:center;padding:20px;color:#94a3b8;font-size:.85rem;">
                                    <i class="ri-file-unknown-line" style="font-size:1.5rem;display:block;margin-bottom:6px;"></i>
                                    Documento no subido
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <h4 style="font-size:1rem;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="ri-graduation-cap-line" style="color:#fc7b04;"></i> Formación Académica
            </h4>
            @if ($estudioDocente)
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:16px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:#e0f2fe;color:#0891b2;">
                            <i class="ri-school-line"></i>
                        </div>
                        <div>
                            <div style="font-size:.875rem;font-weight:600;">{{ $estudioDocente->grado_academico->nombre ?? 'Sin grado' }}</div>
                            <div style="font-size:.75rem;color:#64748b;">{{ $estudioDocente->profesion->nombre ?? 'Sin profesión' }} | {{ $estudioDocente->estado ?? '—' }}</div>
                        </div>
                        <span class="ms-auto badge" style="background:#dcfce7;color:#16a34a;font-size:.6875rem;padding:4px 10px;border-radius:20px;">Principal</span>
                    </div>
                    @if ($estudioDocente->universidad)
                        <div style="font-size:.8125rem;color:#64748b;border-top:1px solid #e2e8f0;padding-top:12px;">
                            <i class="ri-building-line me-1"></i> {{ $estudioDocente->universidad->nombre }}
                        </div>
                    @endif
                </div>
                @php
                    $docsAcademicoDoc = [
                        [
                            'nombre'    => 'Título/Bachiller',
                            'icono'     => 'ri-graduation-cap-line',
                            'archivo'   => $estudioDocente->documento_academico,
                            'verificado'=> $estudioDocente->documento_academico_verificado,
                            'tipo'      => 'documento_academico',
                        ],
                        [
                            'nombre'    => 'Provisión Nacional',
                            'icono'     => 'ri-government-line',
                            'archivo'   => $estudioDocente->documento_provision_nacional,
                            'verificado'=> $estudioDocente->documento_provision_verificado,
                            'tipo'      => 'documento_provision_nacional',
                        ],
                    ];
                @endphp
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;">
                    @foreach ($docsAcademicoDoc as $doc)
                        @php
                            $estado = $estadoDocDoc($doc['archivo'], $doc['verificado']);
                            $bgIcon   = $estado['cls'] == 'approved' ? '#dcfce7' : ($estado['cls'] == 'review' ? '#e0f2fe' : '#fef3c7');
                            $colorIcon= $estado['cls'] == 'approved' ? '#16a34a' : ($estado['cls'] == 'review' ? '#0891b2' : '#d97706');
                        @endphp
                        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                            <div style="padding:14px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                                <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:{{ $bgIcon }};color:{{ $colorIcon }};flex-shrink:0;">
                                    <i class="{{ $doc['icono'] }}"></i>
                                </div>
                                <div style="flex:1;min-width:0;font-size:.875rem;font-weight:600;">{{ $doc['nombre'] }}</div>
                                <span style="padding:4px 10px;border-radius:20px;font-size:.6875rem;font-weight:600;text-transform:uppercase;background:{{ $bgIcon }};color:{{ $colorIcon }};">
                                    {{ $estado['label'] }}
                                </span>
                            </div>
                            <div style="padding:16px;">
                                @if ($doc['archivo'])
                                    <div style="display:flex;align-items:center;gap:12px;padding:12px;background:#f8fafc;border-radius:8px;">
                                        <div style="width:36px;height:36px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:1.1rem;">
                                            <i class="ri-file-pdf-fill"></i>
                                        </div>
                                        <div style="flex:1;min-width:0;">
                                            <div style="font-size:.8125rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $doc['tipo'] }}.pdf</div>
                                            <div style="font-size:.6875rem;display:flex;align-items:center;gap:4px;color:{{ $doc['verificado'] ? '#16a34a' : '#d97706' }};">
                                                @if ($doc['verificado'])
                                                    <i class="ri-shield-check-fill"></i> Verificado
                                                @else
                                                    <i class="ri-time-fill"></i> En revisión
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div style="text-align:center;padding:20px;color:#94a3b8;font-size:.85rem;">
                                        <i class="ri-file-unknown-line" style="font-size:1.5rem;display:block;margin-bottom:6px;"></i>
                                        Documento no subido
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center;padding:40px 20px;background:#fff;border:1px solid #e2e8f0;border-radius:12px;">
                    <i class="ri-user-unfollow-line" style="font-size:2.5rem;color:#94a3b8;opacity:.5;"></i>
                    <p style="margin:16px 0 0;color:#64748b;">Sin registro académico registrado</p>
                </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TAB ACADÉMICO DOCENTE
        ══════════════════════════════════════════════════════════ --}}
        <div class="est-tabs-body" id="tab-academico-docente">

            <div class="tab-banner academico">
                <div class="tab-banner-icon"><i class="ri-book-3-line"></i></div>
                <div class="tab-banner-body">
                    <p class="tab-banner-title">Mis Módulos Asignados</p>
                    <p class="tab-banner-sub">Programas en los que participas como docente</p>
                </div>
                @php $totalOfertasDoc = $modulosDocente->groupBy('ofertas_academica_id')->count(); @endphp
                <span class="tab-banner-badge">
                    <i class="ri-stack-line"></i> {{ $totalOfertasDoc }} oferta(s)
                </span>
            </div>

            @if ($modulosDocente->isEmpty())
                <div class="est-no-cuenta">
                    <i class="ri-book-open-line"></i>
                    <h5>Sin módulos asignados</h5>
                    <p>Aún no tienes módulos asignados como docente. Contacta con administración para más información.</p>
                    @if ($moodleDocenteId)
                        <p style="margin-top:.3rem;font-size:.8rem;color:var(--doc-success);"><i class="ri-checkbox-circle-fill"></i> Tu cuenta de Moodle está activa. Cuando te asignen un módulo podrás acceder a los cursos.</p>
                    @else
                        <p style="margin-top:.3rem;font-size:.8rem;color:var(--doc-text-muted);"><i class="ri-information-line"></i> Aún no tienes cuenta en Moodle. Puedes crearla desde el panel de administración.</p>
                    @endif
                </div>
            @else
                @php
                    $modulosPorOferta = $modulosDocente->groupBy('ofertas_academica_id');
                @endphp

                {{-- ── Selector de ofertas (tarjetas elegantes) ────────────── --}}
                <div class="acad-progs-wrap">
                    <div class="acad-progs-head">
                        <div class="acad-progs-head-title">
                            <i class="ri-book-open-line"></i>
                            <span>{{ $modulosPorOferta->count() > 1 ? 'Selecciona una oferta académica' : 'Tu oferta académica' }}</span>
                        </div>
                        @if ($modulosPorOferta->count() > 1)
                        <span class="acad-progs-head-hint"><i class="ri-cursor-line"></i> Haz clic en una tarjeta para ver sus módulos</span>
                        @endif
                    </div>
                    <div class="acad-progs-grid">
                        @foreach ($modulosPorOferta as $ofertaId => $mods)
                        @php
                            $primerMod    = $mods->first();
                            $ofertaCard   = $primerMod->ofertaAcademica;
                            $nombreOferta = $ofertaCard?->programa?->nombre
                                ?? $ofertaCard?->posgrado?->nombre
                                ?? 'Oferta #' . $ofertaId;
                            $progColor   = $ofertaCard?->color ?? '#9a4904';
                            $progTotalMod = $mods->count();
                            $progMoodleMod = $mods->filter(fn($m) => $m->moodle_course_id)->count();
                        @endphp
                        <button type="button"
                            class="acad-prog-card est-oferta-tab-btn {{ $loop->first ? 'active' : '' }}"
                            data-target="doc-oferta-{{ $loop->index }}"
                            style="--prog-color: {{ $progColor }};">
                            <div class="acad-prog-card-stripe"></div>
                            <div class="acad-prog-card-body">
                                <div class="acad-prog-card-top">
                                    <span class="acad-prog-card-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="acad-prog-card-estado inscrito">
                                        <i class="ri-user-settings-fill"></i>
                                        Docente
                                    </span>
                                </div>
                                <div class="acad-prog-card-name">{{ $nombreOferta }}</div>
                                <div class="acad-prog-card-meta">
                                    @if ($ofertaCard?->codigo)
                                        <span><i class="ri-hashtag"></i>{{ $ofertaCard->codigo }}</span>
                                    @endif
                                    @if ($ofertaCard?->fase?->nombre)
                                        <span><i class="ri-flag-2-line"></i>{{ $ofertaCard->fase->nombre }}</span>
                                    @endif
                                    @if ($ofertaCard?->modalidad?->nombre)
                                        <span><i class="ri-global-line"></i>{{ $ofertaCard->modalidad->nombre }}</span>
                                    @endif
                                </div>
                                <div class="acad-prog-card-modcount">
                                    <i class="ri-stack-line"></i>
                                    <span><strong>{{ $progMoodleMod }}</strong> de {{ $progTotalMod }} módulo(s) con Moodle</span>
                                </div>
                                <div class="acad-prog-card-cta">
                                    <span class="acad-prog-card-cta-icon">
                                        <i class="ri-eye-line acad-prog-card-cta-i-idle"></i>
                                        <i class="ri-checkbox-circle-fill acad-prog-card-cta-i-active"></i>
                                    </span>
                                    <span class="acad-prog-card-cta-text">
                                        <span class="acad-prog-card-cta-when-idle">Ver módulos</span>
                                        <span class="acad-prog-card-cta-when-active">Visualizando módulos</span>
                                    </span>
                                    <i class="ri-arrow-right-s-line acad-prog-card-arrow"></i>
                                </div>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Contenido por oferta --}}
                @foreach ($modulosPorOferta as $ofertaId => $mods)
                @php
                    $primerMod    = $mods->first();
                    $oferta       = $primerMod->ofertaAcademica;
                    $nombreOferta = $oferta?->programa?->nombre
                        ?? $oferta?->posgrado?->nombre
                        ?? 'Oferta #' . $ofertaId;
                @endphp
                <div class="est-oferta-content {{ $loop->first ? 'active' : '' }}"
                    id="doc-oferta-{{ $loop->index }}">

                    {{-- Contexto compacto de la oferta --}}
                    <div class="acad-prog-context">
                        <i class="ri-graduation-cap-line"></i>
                        <span class="acad-prog-context-label">Módulos de</span>
                        <strong>{{ $nombreOferta }}</strong>
                        @if ($oferta?->codigo)
                            <span class="acad-prog-context-sep">·</span>
                            <span class="acad-prog-context-code">{{ $oferta->codigo }}</span>
                        @endif
                        @if ($oferta?->fecha_inicio)
                            <span class="acad-prog-context-sep">·</span>
                            <span><i class="ri-calendar-line"></i> Inicio {{ \Carbon\Carbon::parse($oferta->fecha_inicio)->format('d/m/Y') }}</span>
                        @endif
                        <span class="acad-prog-context-modcount">
                            <i class="ri-stack-line"></i> {{ $mods->count() }} módulo(s)
                        </span>
                    </div>

                    {{-- Grid de módulos --}}
                    <div class="acad-modulos-grid">
                        @foreach ($mods->sortBy('n_modulo') as $modulo)
                        @php $modColor = $modulo->color ?? '#6366f1'; @endphp
                        <div class="acad-mod-card" style="--mod-color: {{ $modColor }};">
                            <div class="acad-mod-stripe"></div>
                            <div class="acad-mod-body">
                                <div class="acad-mod-top">
                                    <span class="acad-mod-num">M{{ $modulo->n_modulo }}</span>
                                    <span class="acad-mod-badge activo">
                                        <i class="ri-user-settings-line"></i> Docente
                                    </span>
                                </div>
                                <div class="acad-mod-name">{{ $modulo->nombre }}</div>
                                <div class="acad-mod-meta">
                                    @if ($modulo->fecha_inicio)
                                        <span>
                                            <i class="ri-calendar-line"></i>
                                            {{ \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') }}
                                            @if ($modulo->fecha_fin)
                                                — {{ \Carbon\Carbon::parse($modulo->fecha_fin)->format('d/m/Y') }}
                                            @endif
                                        </span>
                                    @endif
                                    <span>
                                        <i class="ri-time-line"></i>
                                        {{ $modulo->horarios->count() }} sesión(es)
                                    </span>
                                </div>
                                <div class="acad-mod-actions">
                                    <a href="{{ route('virtual.docente.modulo', $modulo->id) }}"
                                        class="acad-mod-btn btn-ver-actividades">
                                        <i class="ri-layout-grid-line"></i> Ver detalle
                                    </a>
                                    @if ($modulo->moodle_course_id)
                                        <a href="{{ route('virtual.moodle-sso', ['target' => config('moodle.url') . '/course/view.php?id=' . $modulo->moodle_course_id]) }}"
                                            target="_blank" class="acad-mod-btn acad-mod-btn-go">
                                            <i class="ri-external-link-line"></i> Ir al curso
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>{{-- /est-oferta-content --}}
                @endforeach

            @endif
        </div>

        <div class="est-tabs-body" id="tab-horario-docente">
            @if($modulosDocente->isEmpty())
                <div class="est-empty-state">
                    <i class="ri-calendar-close-line"></i>
                    <h5>Sin módulos asignados</h5>
                    <p>No tienes módulos asignados como docente para mostrar horario.</p>
                    @if ($moodleDocenteId)
                        <p style="margin-top:.3rem;font-size:.8rem;color:var(--doc-success);"><i class="ri-checkbox-circle-fill"></i> Tu cuenta de Moodle está activa. Cuando te asignen un módulo podrás ver el horario.</p>
                    @else
                        <p style="margin-top:.3rem;font-size:.8rem;color:var(--doc-text-muted);"><i class="ri-information-line"></i> Aún no tienes cuenta en Moodle. Puedes crearla desde el panel de administración.</p>
                    @endif
                </div>
            @else
                <div class="cronograma-container d-flex" style="min-height:600px;">

                    {{-- Sidebar: selector de oferta + lista de módulos --}}
                    <div class="cronograma-sidebar">
                        <div class="cronograma-sidebar-head">
                            <i class="ri-book-3-line"></i>
                            <span>Oferta Académica</span>
                        </div>
                        <div class="cronograma-sidebar-body">
                            <select class="cronograma-select"
                                    id="select-oferta-horario-docente"
                                    onchange="cargarModulosHorarioDocente()">
                                <option value="">Seleccionar oferta académica</option>
                                @foreach($ofertasHorariosDocente as $ofHD)
                                    <option value="{{ $ofHD['id'] }}">
                                        {{ $ofHD['codigo'] }} — {{ $ofHD['nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="cronograma-btn-all active"
                                    id="btnTodosModulosHorarioDocente"
                                    onclick="verTodosModulosHorarioDocente()">
                                <i class="ri-layout-grid-line"></i> Todos los módulos
                            </button>
                            <div id="modulosSidebarListHorarioDocente">
                                <div class="cronograma-sidebar-empty">
                                    <i class="ri-arrow-up-line"></i>
                                    Selecciona una oferta académica
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Área principal: calendario --}}
                    <div class="cronograma-main">
                        <div class="cronograma-title-section">
                            <div class="cronograma-title-left">
                                <div class="cronograma-title-icon">
                                    <i class="ri-calendar-check-line"></i>
                                </div>
                                <div class="cronograma-title-text">
                                    <h4>Mi Horario de Clases</h4>
                                    <span>Sesiones programadas como docente</span>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                                <div style="display:flex;align-items:center;gap:.4rem;font-size:.72rem;color:#64748b;">
                                    <span class="cronograma-legend-dot confirmed"></span><span>Confirmado</span>
                                    <span class="cronograma-legend-dot postponed"></span><span>Postergado</span>
                                </div>
                                <div id="moduloSeleccionadoBadgeHorarioDocente"
                                     class="cronograma-filter-badge" style="display:none;">
                                    <span class="dot"></span>
                                    <span class="modulo-badge-name"></span>
                                    <button type="button" title="Quitar filtro"
                                            onclick="verTodosModulosHorarioDocente()">
                                        <i class="ri-close-circle-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="cronograma-calendar-wrapper">
                            <div id="calendarHorarioDocente"></div>
                        </div>
                    </div>

                </div>
            @endif
        </div>

        @endif

        </div>{{-- /content-docente --}}
        <div id="content-estudiante" {!! $perfilActivo !== 'estudiante' ? 'style="display:none"' : '' !!}>
        @php
            $tieneFoto =
                $persona && $persona->fotografia && file_exists(public_path('images/personas/' . $persona->fotografia));
            if ($tieneFoto) {
                $avatarUrl = asset('images/personas/' . $persona->fotografia);
            } else {
                $sexoEst = $persona?->sexo;
                $defaultFileEst = $sexoEst === 'F' ? 'mujer.png' : 'chico.png';
                $avatarUrl = asset('images/' . $defaultFileEst);
            }
            $nombreCompleto = $persona
                ? trim(
                    ($persona->nombres ?? '') .
                        ' ' .
                        ($persona->apellido_paterno ?? '') .
                        ' ' .
                        ($persona->apellido_materno ?? ''),
                )
                : 'Estudiante';
            $iniciales = collect(explode(' ', $nombreCompleto))
                ->filter()
                ->take(2)
                ->map(fn($p) => strtoupper($p[0]))
                ->implode('');
            $edad =
                $persona && $persona->fecha_nacimiento ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->age : null;
            $ubicacion =
                $persona && $persona->ciudad
                    ? optional($persona->ciudad)->nombre .
                        ', ' .
                        (optional(optional($persona->ciudad)->departamento)->nombre ?? '')
                    : null;
            $estudio = $persona?->estudios?->first();
        @endphp
        <div class="est-tabs-body active" id="tab-personal">
            <div class="est-ci-wrap">
                <div class="est-ci-stripe"></div>
                <div class="est-ci-body">
                    {{-- Izquierda: foto --}}
                    <div class="est-ci-left">
                        <div class="est-ci-foto-label"><i class="ri-building-2-line"></i><span>INNOVA CIENCIA</span></div>
                        <div class="est-ci-foto est-ci-foto-edit-wrap">
                            <img src="{{ $avatarUrl }}" alt="Foto" id="est-ci-foto-img"
                                onerror="this.src='{{ asset('images/chico.png') }}'">
                            <button type="button" class="est-ci-foto-edit-btn"
                                    onclick="abrirCambioFoto()" title="Cambiar foto">
                                <i class="ri-camera-line"></i>
                                <span>Cambiar foto</span>
                            </button>
                        </div>
                        <div class="est-ci-quick-data">
                            @if ($persona?->carnet)
                                <div class="est-ci-qd-item">
                                    <i class="ri-shield-check-line"></i>
                                    <span class="est-ci-qd-label">CI</span>
                                    <span
                                        class="est-ci-qd-val">{{ trim($persona->carnet . ($persona->expedido ? ' ' . trim($persona->expedido) : '')) }}</span>
                                </div>
                            @endif
                            @if ($persona?->fecha_nacimiento)
                                <div class="est-ci-qd-item">
                                    <i class="ri-cake-line"></i>
                                    <span class="est-ci-qd-label">Nacimiento</span>
                                    <span
                                        class="est-ci-qd-val">{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') }}</span>
                                </div>
                            @endif
                            @if ($edad)
                                <div class="est-ci-qd-item">
                                    <i class="ri-user-line"></i>
                                    <span class="est-ci-qd-label">Edad</span>
                                    <span class="est-ci-qd-val">{{ $edad }} años</span>
                                </div>
                            @endif
                            @if ($persona?->sexo)
                                <div class="est-ci-qd-item">
                                    <i class="ri-genderless-line"></i>
                                    <span class="est-ci-qd-label">Sexo</span>
                                    <span
                                        class="est-ci-qd-val">{{ $persona->sexo == 'M' ? 'Masculino' : ($persona->sexo == 'F' ? 'Femenino' : '—') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="pers-acc-chips">
                            @if ($moodleUserId)
                            <div class="pers-acc-chip pers-chip-ok">
                                <i class="ri-links-line"></i><span>Moodle: Activo</span>
                            </div>
                            @else
                            <div class="pers-acc-chip pers-chip-no">
                                <i class="ri-links-line"></i><span>Moodle: Sin cuenta</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Centro: datos de contacto --}}
                    <div class="est-ci-center">
                        <div class="est-ci-nombre-wrap">
                            <div>
                                <div class="est-ci-nombre">{{ $nombreCompleto }}</div>
                                <div class="est-ci-estado-label">Estudiante</div>
                            </div>
                            @if ($estudiante)
                                <span
                                    class="est-ci-estado-badge est-ci-badge-{{ ($estudiante->estado ?? 'Activo') === 'Activo' ? 'activo' : 'inactivo' }}">
                                    <i class="ri-checkbox-circle-line"></i>
                                    {{ $estudiante->estado ?? 'Activo' }}
                                </span>
                            @endif
                        </div>
                        <div class="est-ci-section-title"><i class="ri-contacts-line"></i> Datos de Contacto</div>
                        @if($persona?->correo)
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-mail-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Correo electrónico</div>
                                <div class="pers-contact-val"><a href="mailto:{{ $persona->correo }}" class="pers-contact-link">{{ $persona->correo }}</a></div>
                            </div>
                            <a href="mailto:{{ $persona->correo }}" class="pers-contact-act" title="Enviar correo"><i class="ri-send-plane-line"></i></a>
                        </div>
                        @endif
                        @if($persona?->celular)
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-smartphone-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Celular</div>
                                <div class="pers-contact-val"><a href="tel:{{ $persona->celular }}" class="pers-contact-link">{{ $persona->celular }}</a></div>
                            </div>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $persona->celular) }}" target="_blank" class="pers-contact-act wa" title="WhatsApp"><i class="ri-whatsapp-line"></i></a>
                        </div>
                        @endif
                        @if($persona?->telefono)
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-phone-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Teléfono</div>
                                <div class="pers-contact-val">{{ $persona->telefono }}</div>
                            </div>
                        </div>
                        @endif
                        @if($persona?->estado_civil)
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-heart-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Estado Civil</div>
                                <div class="pers-contact-val">{{ $persona->estado_civil }}</div>
                            </div>
                        </div>
                        @endif
                        @if($ubicacion)
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-map-pin-2-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Ciudad / Departamento</div>
                                <div class="pers-contact-val">{{ $ubicacion }}</div>
                            </div>
                        </div>
                        @endif
                        @if($persona?->direccion)
                        <div class="pers-contact-row">
                            <div class="pers-contact-ico"><i class="ri-home-3-line"></i></div>
                            <div class="pers-contact-body">
                                <div class="pers-contact-lbl">Dirección</div>
                                <div class="pers-contact-val">{{ $persona->direccion }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Derecha: datos del estudiante --}}
                    <div class="est-ci-right">
                        <div class="est-ci-right-header">
                            <i class="ri-graduation-cap-line"></i><span>Datos del Estudiante</span>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            @if ($estudio?->universidad)
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-building-4-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Universidad</div>
                                    <div class="pers-info-val">{{ $estudio->universidad->nombre ?? '—' }}</div>
                                </div>
                            </div>
                            @endif
                            @if ($estudio?->profesion)
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-graduation-cap-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Carrera / Programa</div>
                                    <div class="pers-info-val">{{ $estudio->profesion->nombre ?? '—' }}</div>
                                </div>
                            </div>
                            @endif
                            @if ($estudiante)
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-calendar-check-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Fecha Inscripción</div>
                                    <div class="pers-info-val">{{ $estudiante->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-vip-diamond-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Estado</div>
                                    <div class="pers-info-val">{{ $estudiante->estado ?? 'Activo' }}</div>
                                </div>
                            </div>
                            @endif
                            @if ($inscripciones->count())
                            <div class="pers-info-item">
                                <div class="pers-info-ico"><i class="ri-book-open-line"></i></div>
                                <div>
                                    <div class="pers-info-lbl">Ofertas Académicas</div>
                                    <div class="pers-info-val">{{ $inscripciones->count() }} inscripción(es)</div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @if ($inscripciones->count() > 0)
                        <div class="pers-section-sep">
                            <i class="ri-book-2-line"></i> Programas
                            <span style="background:rgba(252,123,4,.1);color:#c96004;padding:1px 7px;border-radius:5px;font-size:.65rem;">{{ $inscripciones->count() }}</span>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:6px;overflow-y:auto;max-height:200px;padding-right:2px;">
                            @foreach($inscripciones as $ins)
                            @php
                                $nombreOferta = $ins->ofertaAcademica?->programa?->nombre ??
                                    ($ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta #' . $ins->ofertas_academica_id);
                                $saldoPendiente = 0;
                                foreach ($ins->cuotas as $cuota) {
                                    $pagado = $cuota->pagosCuota->sum('monto_bs');
                                    $pendiente = $cuota->monto_bs - $pagado;
                                    if ($pendiente > 0) { $saldoPendiente += $pendiente; }
                                }
                            @endphp
                            <div class="pers-study-card">
                                <span class="pers-study-grado"><i class="ri-book-2-line"></i> {{ $ins->estado }}</span>
                                <div class="pers-study-profesion">{{ $nombreOferta }}</div>
                                @if ($saldoPendiente > 0)
                                <div class="pers-study-univ">
                                    <i class="ri-money-dollar-circle-line" style="font-size:.7rem;flex-shrink:0;color:#ef4444;"></i>
                                    <span style="color:#ef4444;">Bs. {{ number_format($saldoPendiente, 2, ',', '.') }} pendiente</span>
                                </div>
                                @else
                                <div class="pers-study-univ">
                                    <i class="ri-checkbox-circle-line" style="font-size:.7rem;flex-shrink:0;color:#16a34a;"></i>
                                    <span style="color:#16a34a;">Al día</span>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                <div class="est-ci-bottom-bar">
                    <span><i class="ri-id-card-line"></i> Carnet de Identificación</span>
                    <span>{{ now()->format('Y') }}</span>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TAB DOCUMENTOS (solo lectura)
        ══════════════════════════════════════════════════════════ --}}
        @php
            $estadoDoc = function ($archivo, $verificado) {
                if (!$archivo) {
                    return ['label' => 'Pendiente', 'cls' => 'pending', 'icon' => 'ri-add-circle-line'];
                }
                if ($verificado) {
                    return ['label' => 'Aprobado', 'cls' => 'approved', 'icon' => 'ri-checkbox-circle-fill'];
                }
                return ['label' => 'En revisión', 'cls' => 'review', 'icon' => 'ri-time-line'];
            };
            $docsIdentidad = [
                [
                    'nombre' => 'Carnet de Identidad',
                    'icono' => 'ri-id-card-line',
                    'archivo' => $persona->fotografia_carnet ?? null,
                    'verificado' => $persona->carnet_verificado ?? false,
                    'tipo' => 'fotografia_carnet',
                ],
                [
                    'nombre' => 'Cert. Nacimiento',
                    'icono' => 'ri-file-paper-line',
                    'archivo' => $persona->fotografia_certificado_nacimiento ?? null,
                    'verificado' => $persona->certificado_nacimiento_verificado ?? false,
                    'tipo' => 'fotografia_certificado_nacimiento',
                ],
            ];
            $totalDocs = count($docsIdentidad);
            $verificados = 0;
            foreach ($docsIdentidad as $d) {
                if ($d['verificado']) {
                    $verificados++;
                }
            }
            if ($estudioPrincipal) {
                $totalDocs += 2;
                if ($estudioPrincipal->documento_academico_verificado) {
                    $verificados++;
                }
                if ($estudioPrincipal->documento_provision_verificado) {
                    $verificados++;
                }
            }
            $pctDocs = $totalDocs > 0 ? ($verificados / $totalDocs) * 100 : 0;
        @endphp
        <div class="est-tabs-body" id="tab-documentos">
            <div
                style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:16px;">
                <h3 style="margin:0;font-size:1.1rem;font-weight:600;display:flex;align-items:center;gap:8px;">
                    <i class="ri-folder-shield-line" style="color:#fc7b04;"></i> Documentación
                </h3>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="flex:1;max-width:150px;height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                        <div
                            style="height:100%;background:linear-gradient(90deg,#fc7b04,#f97316);border-radius:4px;width:{{ $pctDocs }}%;transition:width .3s;">
                        </div>
                    </div>
                    <span
                        style="font-size:.875rem;font-weight:700;color:#fc7b04;">{{ number_format($pctDocs, 0) }}%</span>
                </div>
            </div>

            {{-- Identidad --}}
            <h4 style="font-size:1rem;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="ri-id-card-line" style="color:#fc7b04;"></i> Documentación Personal
            </h4>
            <div
                style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:32px;">
                @foreach ($docsIdentidad as $doc)
                    @php
                        $estado = $estadoDoc($doc['archivo'], $doc['verificado']);
                        $bgIcon =
                            $estado['cls'] == 'approved'
                                ? '#dcfce7'
                                : ($estado['cls'] == 'review'
                                    ? '#e0f2fe'
                                    : '#fef3c7');
                        $colorIcon =
                            $estado['cls'] == 'approved'
                                ? '#16a34a'
                                : ($estado['cls'] == 'review'
                                    ? '#0891b2'
                                    : '#d97706');
                    @endphp
                    <div
                        style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                        <div
                            style="padding:14px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                            <div
                                style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:{{ $bgIcon }};color:{{ $colorIcon }};flex-shrink:0;">
                                <i class="{{ $doc['icono'] }}"></i>
                            </div>
                            <div style="flex:1;min-width:0;font-size:.875rem;font-weight:600;">{{ $doc['nombre'] }}</div>
                            <span
                                style="padding:4px 10px;border-radius:20px;font-size:.6875rem;font-weight:600;text-transform:uppercase;background:{{ $bgIcon }};color:{{ $colorIcon }};">
                                {{ $estado['label'] }}
                            </span>
                        </div>
                        <div style="padding:16px;">
                            @if ($doc['archivo'])
                                <div
                                    style="display:flex;align-items:center;gap:12px;padding:12px;background:#f8fafc;border-radius:8px;">
                                    <div
                                        style="width:36px;height:36px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:1.1rem;">
                                        <i class="ri-file-pdf-fill"></i>
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div
                                            style="font-size:.8125rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $doc['tipo'] }}.pdf</div>
                                        <div
                                            style="font-size:.6875rem;display:flex;align-items:center;gap:4px;color:{{ $doc['verificado'] ? '#16a34a' : '#d97706' }};">
                                            @if ($doc['verificado'])
                                                <i class="ri-shield-check-fill"></i> Verificado
                                            @else
                                                <i class="ri-time-fill"></i> En revisión
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div style="text-align:center;padding:20px;color:#94a3b8;font-size:.85rem;">
                                    <i class="ri-file-unknown-line"
                                        style="font-size:1.5rem;display:block;margin-bottom:6px;"></i>
                                    Documento no subido
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Formación académica --}}
            <h4 style="font-size:1rem;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="ri-graduation-cap-line" style="color:#fc7b04;"></i> Formación Académica
            </h4>
            @if ($estudioPrincipal)
                <div
                    style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:16px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <div
                            style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:#e0f2fe;color:#0891b2;">
                            <i class="ri-school-line"></i>
                        </div>
                        <div>
                            <div style="font-size:.875rem;font-weight:600;">
                                {{ $estudioPrincipal->grado_academico->nombre ?? 'Sin grado' }}</div>
                            <div style="font-size:.75rem;color:#64748b;">
                                {{ $estudioPrincipal->profesion->nombre ?? 'Sin profesión' }} |
                                {{ $estudioPrincipal->estado ?? '—' }}</div>
                        </div>
                        <span class="ms-auto badge"
                            style="background:#dcfce7;color:#16a34a;font-size:.6875rem;padding:4px 10px;border-radius:20px;">Principal</span>
                    </div>
                    @if ($estudioPrincipal->universidad)
                        <div style="font-size:.8125rem;color:#64748b;border-top:1px solid #e2e8f0;padding-top:12px;">
                            <i class="ri-building-line me-1"></i> {{ $estudioPrincipal->universidad->nombre }}
                        </div>
                    @endif
                </div>
                @php
                    $docsAcademico = [
                        [
                            'nombre' => 'Título/Bachiller',
                            'icono' => 'ri-graduation-cap-line',
                            'archivo' => $estudioPrincipal->documento_academico,
                            'verificado' => $estudioPrincipal->documento_academico_verificado,
                            'tipo' => 'documento_academico',
                        ],
                        [
                            'nombre' => 'Provisión Nacional',
                            'icono' => 'ri-government-line',
                            'archivo' => $estudioPrincipal->documento_provision_nacional,
                            'verificado' => $estudioPrincipal->documento_provision_verificado,
                            'tipo' => 'documento_provision_nacional',
                        ],
                    ];
                @endphp
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;">
                    @foreach ($docsAcademico as $doc)
                        @php
                            $estado = $estadoDoc($doc['archivo'], $doc['verificado']);
                            $bgIcon =
                                $estado['cls'] == 'approved'
                                    ? '#dcfce7'
                                    : ($estado['cls'] == 'review'
                                        ? '#e0f2fe'
                                        : '#fef3c7');
                            $colorIcon =
                                $estado['cls'] == 'approved'
                                    ? '#16a34a'
                                    : ($estado['cls'] == 'review'
                                        ? '#0891b2'
                                        : '#d97706');
                        @endphp
                        <div
                            style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                            <div
                                style="padding:14px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                                <div
                                    style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:{{ $bgIcon }};color:{{ $colorIcon }};flex-shrink:0;">
                                    <i class="{{ $doc['icono'] }}"></i>
                                </div>
                                <div style="flex:1;min-width:0;font-size:.875rem;font-weight:600;">{{ $doc['nombre'] }}
                                </div>
                                <span
                                    style="padding:4px 10px;border-radius:20px;font-size:.6875rem;font-weight:600;text-transform:uppercase;background:{{ $bgIcon }};color:{{ $colorIcon }};">
                                    {{ $estado['label'] }}
                                </span>
                            </div>
                            <div style="padding:16px;">
                                @if ($doc['archivo'])
                                    <div
                                        style="display:flex;align-items:center;gap:12px;padding:12px;background:#f8fafc;border-radius:8px;">
                                        <div
                                            style="width:36px;height:36px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:1.1rem;">
                                            <i class="ri-file-pdf-fill"></i>
                                        </div>
                                        <div style="flex:1;min-width:0;">
                                            <div
                                                style="font-size:.8125rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                {{ $doc['tipo'] }}.pdf</div>
                                            <div
                                                style="font-size:.6875rem;display:flex;align-items:center;gap:4px;color:{{ $doc['verificado'] ? '#16a34a' : '#d97706' }};">
                                                @if ($doc['verificado'])
                                                    <i class="ri-shield-check-fill"></i> Verificado
                                                @else
                                                    <i class="ri-time-fill"></i> En revisión
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div style="text-align:center;padding:20px;color:#94a3b8;font-size:.85rem;">
                                        <i class="ri-file-unknown-line"
                                            style="font-size:1.5rem;display:block;margin-bottom:6px;"></i>
                                        Documento no subido
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div
                    style="text-align:center;padding:40px 20px;background:#fff;border:1px solid #e2e8f0;border-radius:12px;">
                    <i class="ri-user-unfollow-line" style="font-size:2.5rem;color:#94a3b8;opacity:.5;"></i>
                    <p style="margin:16px 0 0;color:#64748b;">Sin registro académico registrado</p>
                </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TAB ACADÉMICO (contenido actual del dashboard)
        ══════════════════════════════════════════════════════════ --}}
        <div class="est-tabs-body" id="tab-academico">

            <div class="tab-banner academico">
                <div class="tab-banner-icon"><i class="ri-book-3-line"></i></div>
                <div class="tab-banner-body">
                    <p class="tab-banner-title">Mis Programas Académicos</p>
                    <p class="tab-banner-sub">Módulos matriculados y acceso a cursos por inscripción</p>
                </div>
                <span class="tab-banner-badge">
                    <i class="ri-stack-line"></i> {{ $inscripciones->count() }} programa(s)
                </span>
            </div>

            @if ($inscripciones->count() > 0)

                {{-- ── Selector de programas (tarjetas elegantes) ────────────── --}}
                <div class="acad-progs-wrap">
                    <div class="acad-progs-head">
                        <div class="acad-progs-head-title">
                            <i class="ri-book-open-line"></i>
                            <span>{{ $inscripciones->count() > 1 ? 'Selecciona un programa' : 'Tu programa' }}</span>
                        </div>
                        @if ($inscripciones->count() > 1)
                        <span class="acad-progs-head-hint"><i class="ri-cursor-line"></i> Haz clic en una tarjeta para ver sus módulos</span>
                        @endif
                    </div>
                    <div class="acad-progs-grid">
                        @foreach ($inscripciones as $key => $insc)
                        @php
                            $progPosgrado = $insc->ofertaAcademica?->posgrado;
                            $progPrograma = $insc->ofertaAcademica?->programa;
                            $progNombre   = $progPosgrado?->nombre ?? $progPrograma?->nombre ?? 'Programa ' . ($key + 1);
                            $progColor    = $insc->ofertaAcademica?->color ?? '#9a4904';
                            $progMatriculas = $insc->moodleMatriculas;
                            $progTotalMod   = $progMatriculas->count();
                            $progActivosMod = $progMatriculas->filter(fn($m) => $m->moodle_course_id && $m->moodle_user_id && !$m->acceso_suspendido)->count();
                            $progPct = $progTotalMod > 0 ? round(($progActivosMod / $progTotalMod) * 100) : 0;
                            $pillEstado = match ($insc->estado) {
                                'Inscrito', 'Confirmado' => 'inscrito',
                                'Pre-Inscrito'           => 'pendiente',
                                default                  => 'otro',
                            };
                        @endphp
                        <button type="button"
                            class="acad-prog-card est-oferta-tab-btn {{ $key == 0 ? 'active' : '' }}"
                            data-target="academico-oferta-{{ $key }}"
                            style="--prog-color: {{ $progColor }};">
                            <div class="acad-prog-card-stripe"></div>
                            <div class="acad-prog-card-body">
                                <div class="acad-prog-card-top">
                                    <span class="acad-prog-card-num">{{ str_pad($key + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="acad-prog-card-estado {{ $pillEstado }}">
                                        <i class="ri-checkbox-blank-circle-fill"></i>
                                        {{ $insc->estado }}
                                    </span>
                                </div>
                                <div class="acad-prog-card-name">{{ $progNombre }}</div>
                                <div class="acad-prog-card-meta">
                                    @if ($insc->ofertaAcademica?->codigo)
                                        <span><i class="ri-hashtag"></i>{{ $insc->ofertaAcademica->codigo }}</span>
                                    @endif
                                    @if ($insc->ofertaAcademica?->fase?->nombre)
                                        <span><i class="ri-flag-2-line"></i>{{ $insc->ofertaAcademica->fase->nombre }}</span>
                                    @endif
                                    @if ($insc->ofertaAcademica?->modalidad?->nombre)
                                        <span><i class="ri-global-line"></i>{{ $insc->ofertaAcademica->modalidad->nombre }}</span>
                                    @endif
                                </div>
                                <div class="acad-prog-card-modcount">
                                    <i class="ri-stack-line"></i>
                                    <span><strong>{{ $progActivosMod }}</strong> de {{ $progTotalMod }} módulo(s) con acceso</span>
                                </div>
                                <div class="acad-prog-card-cta">
                                    <span class="acad-prog-card-cta-icon">
                                        <i class="ri-eye-line acad-prog-card-cta-i-idle"></i>
                                        <i class="ri-checkbox-circle-fill acad-prog-card-cta-i-active"></i>
                                    </span>
                                    <span class="acad-prog-card-cta-text">
                                        <span class="acad-prog-card-cta-when-idle">Ver módulos</span>
                                        <span class="acad-prog-card-cta-when-active">Visualizando módulos</span>
                                    </span>
                                    <i class="ri-arrow-right-s-line acad-prog-card-arrow"></i>
                                </div>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Contenido de cada programa --}}
                @foreach ($inscripciones as $key => $insc)
                @php
                    $oferta     = $insc->ofertaAcademica;
                    $programa   = $oferta?->programa;
                    $matriculas = $insc->moodleMatriculas->sortBy(fn($m) => $m->modulo?->n_modulo);
                @endphp
                <div class="est-oferta-content {{ $key == 0 ? 'active' : '' }}"
                    id="academico-oferta-{{ $key }}">

                    {{-- Contexto compacto del programa seleccionado --}}
                    <div class="acad-prog-context">
                        <i class="ri-graduation-cap-line"></i>
                        <span class="acad-prog-context-label">Módulos de</span>
                        <strong>{{ $oferta?->posgrado?->nombre ?? $programa?->nombre ?? 'Programa' }}</strong>
                        @if ($oferta?->codigo)
                            <span class="acad-prog-context-sep">·</span>
                            <span class="acad-prog-context-code">{{ $oferta->codigo }}</span>
                        @endif
                        @if ($oferta?->fecha_inicio)
                            <span class="acad-prog-context-sep">·</span>
                            <span><i class="ri-calendar-line"></i> Inicio {{ \Carbon\Carbon::parse($oferta->fecha_inicio)->format('d/m/Y') }}</span>
                        @endif
                        <span class="acad-prog-context-modcount">
                            <i class="ri-stack-line"></i> {{ $matriculas->count() }} módulo(s)
                        </span>
                    </div>

                    {{-- Grid de módulos --}}
                    @if ($matriculas->isEmpty())
                        <div class="acad-empty-state">
                            <i class="ri-information-line"></i>
                            <p>Aún no tienes módulos matriculados en este programa.</p>
                        </div>
                    @else
                        <div class="acad-modulos-grid">
                            @foreach ($matriculas as $matricula)
                            @php
                                $modulo      = $matricula->modulo;
                                $tieneMoodle = $matricula->moodle_course_id && $matricula->moodle_user_id;
                                $suspendido  = (bool) $matricula->acceso_suspendido;
                                $modColor    = $modulo->color ?? '#6366f1';
                            @endphp
                            @if (!$modulo) @continue @endif

                            <div class="acad-mod-card" id="card-mod-{{ $modulo->id }}" style="--mod-color: {{ $modColor }};">
                                <div class="acad-mod-stripe"></div>
                                <div class="acad-mod-body">
                                    <div class="acad-mod-top">
                                        <span class="acad-mod-num">
                                            M{{ $modulo->n_modulo }}
                                        </span>
                                        @if ($tieneMoodle)
                                            @if ($suspendido)
                                                <span class="acad-mod-badge blocked">
                                                    <i class="ri-lock-line"></i> Bloqueado
                                                </span>
                                            @else
                                                <span class="acad-mod-badge activo">
                                                    <i class="ri-checkbox-circle-line"></i> Activo
                                                </span>
                                            @endif
                                        @else
                                            <span class="acad-mod-badge pending">
                                                <i class="ri-time-line"></i> Sin acceso
                                            </span>
                                        @endif
                                    </div>
                                    <div class="acad-mod-name">{{ $modulo->nombre }}</div>
                                    <div class="acad-mod-meta">
                                        @if ($modulo->fecha_inicio)
                                            <span>
                                                <i class="ri-calendar-line"></i>
                                                {{ \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') }}
                                                @if ($modulo->fecha_fin)
                                                    — {{ \Carbon\Carbon::parse($modulo->fecha_fin)->format('d/m/Y') }}
                                                @endif
                                            </span>
                                        @endif
                                        @if ($modulo->docente?->persona)
                                            <span>
                                                <i class="ri-user-line"></i>
                                                {{ trim(($modulo->docente->persona->nombres ?? '') . ' ' . ($modulo->docente->persona->apellido_paterno ?? '')) }}
                                            </span>
                                        @endif
                                    </div>
                                    @if ($tieneMoodle)
                                        @if ($suspendido)
                                            <div class="acad-mod-blocked">
                                                <i class="ri-lock-2-line"></i>
                                                Acceso bloqueado por pendientes de pago
                                            </div>
                                        @else
                                            <div class="acad-mod-actions">
                                                <button class="acad-mod-btn btn-ver-actividades"
                                                    data-modulo="{{ $modulo->id }}"
                                                    data-panel="panel-mod-{{ $modulo->id }}">
                                                    <i class="ri-eye-line"></i> Actividades
                                                </button>
                                                @php
                                                    $moodleBase = rtrim(config('moodle.url'), '/');
                                                    $courseUrl  = $moodleBase . '/course/view.php?id=' . $matricula->moodle_course_id;
                                                    $ssoUrl     = route('virtual.moodle-sso') . '?target=' . urlencode($courseUrl);
                                                @endphp
                                                <a href="{{ $ssoUrl }}" target="_blank"
                                                    class="acad-mod-btn acad-mod-btn-go">
                                                    <i class="ri-external-link-line"></i> Ir al curso
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            @endforeach
                        </div>

                        {{-- Paneles de actividades — fuera del grid, abajo de todos los módulos --}}
                        <div class="acad-mod-panels-wrap">
                            @foreach ($matriculas as $matricula)
                                @php
                                    $modulo      = $matricula->modulo;
                                    $tieneMoodle = $matricula->moodle_course_id && $matricula->moodle_user_id;
                                    $suspendido  = (bool) $matricula->acceso_suspendido;
                                    $modColor    = $modulo->color ?? '#6366f1';
                                @endphp
                                @if (!$modulo) @continue @endif
                                @if ($tieneMoodle && !$suspendido)
                                    <div class="est-act-panel" id="panel-mod-{{ $modulo->id }}" style="--mod-color: {{ $modColor }};">
                                        <div class="est-spinner" id="spinner-mod-{{ $modulo->id }}">
                                            <div class="spinner-border spinner-border-sm"></div> Cargando actividades…
                                        </div>
                                        <div id="contenido-mod-{{ $modulo->id }}"></div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                </div>{{-- /est-oferta-content --}}
                @endforeach

            @else
                <div class="est-no-cuenta">
                    <i class="ri-book-open-line"></i>
                    <h5>Sin inscripciones</h5>
                    <p>Aún no tienes programas inscritos. Contacta con administración para gestionar tu inscripción.</p>
                </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TAB CONTABLE (solo lectura)
        ══════════════════════════════════════════════════════════ --}}
        @php
            $totalPagado = 0;
            $totalPendiente = 0;
            $totalVencido = 0;
            foreach ($inscripciones as $ins) {
                foreach ($ins->cuotas as $cuota) {
                    $pagadoEnCuota = $cuota->pagosCuota->sum('monto_bs');
                    if ($pagadoEnCuota > 0) {
                        $totalPagado += $pagadoEnCuota;
                    }
                    $pendiente = $cuota->monto_bs - $pagadoEnCuota;
                    if ($pendiente > 0) {
                        if ($cuota->estado == 'Vencido') {
                            $totalVencido += $pendiente;
                        } else {
                            $totalPendiente += $pendiente;
                        }
                    }
                }
            }
        @endphp
        <div class="est-tabs-body" id="tab-contable">

            @php
                $totalOferta = $totalPagado + $totalPendiente + $totalVencido;
                $pctPagadoGlobal = $totalOferta > 0 ? round(($totalPagado / $totalOferta) * 100) : 0;
            @endphp

            {{-- Resumen financiero global con barra de progreso --}}
            <div class="contable-balance-card">
                <div class="contable-balance-header">
                    <i class="ri-bar-chart-grouped-line"></i>
                    <p class="contable-balance-title">Resumen Financiero Global</p>
                </div>
                <div class="contable-stats-grid">
                    <div class="contable-stat-item">
                        <div class="contable-stat-icon pagado"><i class="ri-checkbox-circle-line"></i></div>
                        <div>
                            <div class="contable-stat-value pagado">Bs. {{ number_format($totalPagado, 2) }}</div>
                            <div class="contable-stat-label">Total Pagado</div>
                        </div>
                    </div>
                    <div class="contable-stat-item">
                        <div class="contable-stat-icon pendiente"><i class="ri-time-line"></i></div>
                        <div>
                            <div class="contable-stat-value pendiente">Bs. {{ number_format($totalPendiente, 2) }}</div>
                            <div class="contable-stat-label">Pendiente</div>
                        </div>
                    </div>
                    <div class="contable-stat-item">
                        <div class="contable-stat-icon vencido"><i class="ri-alert-line"></i></div>
                        <div>
                            <div class="contable-stat-value vencido">Bs. {{ number_format($totalVencido, 2) }}</div>
                            <div class="contable-stat-label">Vencido</div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($inscripciones->count() > 0)
                <div class="contable-tabs-wrapper">
                    <div class="ctb-tabs">
                        @foreach ($inscripciones as $key => $ins)
                            @php
                                $insNombre = $ins->ofertaAcademica?->programa?->nombre
                                    ?? ($ins->ofertaAcademica?->posgrado?->nombre
                                    ?? 'Oferta #' . ($key + 1));
                            @endphp
                            <button type="button"
                                class="ctb-tab est-oferta-tab-btn {{ $key == 0 ? 'active' : '' }}"
                                data-target="contable-oferta-{{ $key }}">
                                <i class="ri-money-dollar-circle-line"></i>
                                {{ $insNombre }}
                            </button>
                        @endforeach
                    </div>

                    <div class="contable-tabs-body">
                        @foreach ($inscripciones as $key => $ins)
                            @php
                                $insPagado = 0;
                                $insPendiente = 0;
                                $insVencido = 0;
                                $insTotal = 0;
                                $totalCuotas = $ins->cuotas->count();
                                $cuotasPagadas = 0;
                                foreach ($ins->cuotas as $cuota) {
                                    $pagadoEnCuota = $cuota->pagosCuota->sum('monto_bs');
                                    $insTotal += $cuota->monto_bs;
                                    if ($pagadoEnCuota > 0) {
                                        $insPagado += $pagadoEnCuota;
                                        if ($pagadoEnCuota >= $cuota->monto_bs) {
                                            $cuotasPagadas++;
                                        }
                                    }
                                    $pendiente = $cuota->monto_bs - $pagadoEnCuota;
                                    if ($pendiente > 0) {
                                        if ($cuota->estado == 'Vencido') {
                                            $insVencido += $pendiente;
                                        } else {
                                            $insPendiente += $pendiente;
                                        }
                                    }
                                }
                                $pctPagado = $insTotal > 0 ? round(($insPagado / $insTotal) * 100) : 0;
                                $pctClass = $pctPagado >= 90 ? '' : ($pctPagado >= 50 ? 'some' : 'low');
                            @endphp
                            <div class="est-oferta-content {{ $key == 0 ? 'active' : '' }}"
                                id="contable-oferta-{{ $key }}">

                                {{-- Header de la oferta --}}
                                <div class="est-data-card-header" style="padding:14px 18px;display:flex;align-items:center;gap:10px;border-bottom:1px solid var(--est-border);background:linear-gradient(180deg,#f8fafc 0%,#f1f5f9 100%);">
                                    <div style="width:34px;height:34px;border-radius:10px;background:var(--est-primary-light);color:var(--est-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div style="font-family:'Outfit',sans-serif;font-size:.9rem;font-weight:600;color:#1e293b;">
                                            {{ $ins->ofertaAcademica?->programa?->nombre ?? ($ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta #' . $ins->ofertas_academica_id) }}
                                        </div>
                                        <div style="font-size:.73rem;color:#94a3b8;">
                                            Plan: {{ $ins->planesPago?->nombre ?? '—' }}
                                            &middot; {{ $totalCuotas }} cuota(s)
                                        </div>
                                    </div>
                                    <span class="estado-badge-est {{ $pctPagado >= 100 ? 'pagado' : ($insVencido > 0 ? 'vencido' : 'pendiente') }}">
                                        {{ $pctPagado >= 100 ? 'Cancelado' : ($insVencido > 0 ? 'Con vencidos' : 'Al día') }}
                                    </span>
                                </div>

                                {{-- Barra de progreso de pago --}}
                                <div class="contable-pay-progress">
                                    <i class="ri-percent-line" style="color:#94a3b8;font-size:.9rem;"></i>
                                    <div class="contable-pay-track">
                                        <div class="contable-pay-track-fill {{ $pctClass }}"
                                            style="width:{{ $pctPagado }}%;"></div>
                                    </div>
                                    <span class="contable-pay-pct">{{ $pctPagado }}%</span>
                                </div>

                                {{-- Mini stats: Pagado / Pendiente / Vencido --}}
                                <div class="contable-mini-stats">
                                    <div class="contable-mini-stat">
                                        <div class="contable-mini-icon green"><i class="ri-checkbox-circle-line"></i></div>
                                        <div>
                                            <div class="contable-mini-val green">Bs. {{ number_format($insPagado, 2) }}</div>
                                            <div class="contable-mini-lbl">Pagado</div>
                                        </div>
                                    </div>
                                    <div class="contable-mini-stat">
                                        <div class="contable-mini-icon amber"><i class="ri-time-line"></i></div>
                                        <div>
                                            <div class="contable-mini-val amber">Bs. {{ number_format($insPendiente, 2) }}</div>
                                            <div class="contable-mini-lbl">Pendiente</div>
                                        </div>
                                    </div>
                                    <div class="contable-mini-stat">
                                        <div class="contable-mini-icon red"><i class="ri-alert-line"></i></div>
                                        <div>
                                            <div class="contable-mini-val red">Bs. {{ number_format($insVencido, 2) }}</div>
                                            <div class="contable-mini-lbl">Vencido</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tabla de cuotas compacta --}}
                                @if ($ins->cuotas && $ins->cuotas->count() > 0)
                                    <div style="overflow-x:auto;">
                                        <table class="contable-cuotas-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Cuota</th>
                                                    <th>Monto</th>
                                                    <th>Vencimiento</th>
                                                    <th>Avance</th>
                                                    <th>Estado</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ins->cuotas as $cuota)
                                                    @php
                                                        $totalPagadoCuota = $cuota->pagosCuota->sum('monto_bs');
                                                        $pctCuota = $cuota->monto_bs > 0 ? round(($totalPagadoCuota / $cuota->monto_bs) * 100) : 0;
                                                        $pctCuotaClass = $pctCuota >= 100 ? 'full' : ($pctCuota > 0 ? 'part' : 'empty');
                                                        $montoNeto = $cuota->monto_bs - ($cuota->descuento_bs ?? 0);
                                                        $pagosData = [];
                                                        foreach ($cuota->pagosCuota as $pc) {
                                                            if ($pc->pago) {
                                                                $pago = $pc->pago;
                                                                $trabajadorNombre = $pago->trabajadorCargo?->trabajador?->persona
                                                                    ? $pago->trabajadorCargo->trabajador->persona->nombres . ' ' . $pago->trabajadorCargo->trabajador->persona->apellido_paterno
                                                                    : '—';
                                                                $comprobante = null;
                                                                $cuotaIds = $pago->pagosCuotas->pluck('cuota_id')->toArray();
                                                                if (!empty($cuotaIds)) {
                                                                    $respaldos = \DB::table('pago_respaldo_cuota')
                                                                        ->whereIn('pago_respaldo_cuota.cuota_id', $cuotaIds)
                                                                        ->join('pagos_respaldos', 'pago_respaldo_cuota.pago_respaldo_id', '=', 'pagos_respaldos.id')
                                                                        ->where('pagos_respaldos.estado', 'verificado')
                                                                        ->select('pagos_respaldos.archivo')
                                                                        ->first();
                                                                    if ($respaldos) {
                                                                        $comprobante = [
                                                                            'archivo' => $respaldos->archivo,
                                                                            'url' => asset('storage/comprobantes/' . $respaldos->archivo),
                                                                        ];
                                                                    }
                                                                }
                                                                $pagosData[] = [
                                                                    'id' => $pago->id,
                                                                    'recibo' => $pago->recibo,
                                                                    'fecha' => $pago->fecha_pago,
                                                                    'monto' => $pago->monto_total,
                                                                    'descuento' => $pago->descuento_bs,
                                                                    'metodo' => $pago->tipo_pago,
                                                                    'trabajador' => $trabajadorNombre,
                                                                    'estudiante' => trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '')),
                                                                    'programa' => $ins->ofertaAcademica?->posgrado?->nombre ?? ($ins->ofertaAcademica?->programa?->nombre ?? ''),
                                                                    'plan' => $ins->planesPago?->nombre ?? '',
                                                                    'comprobante' => $comprobante,
                                                                    'documento_factura' => $pago->documento_factura ? \Storage::url($pago->documento_factura) : null,
                                                                    'detalles' => ($pago->detalles ?? collect())->map(fn($d) => ['tipo' => $d->tipo_pago, 'monto' => $d->monto_bs])->toArray(),
                                                                    'cuotas' => ($pago->pagosCuotas ?? collect())->map(fn($pc2) => [
                                                                        'nombre' => $pc2->cuota?->nombre ?? 'Cuota #' . $pc2->cuota_id,
                                                                        'n_cuota' => $pc2->cuota?->n_cuota ?? null,
                                                                        'monto' => $pc2->monto_bs,
                                                                    ])->toArray(),
                                                                ];
                                                            }
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td><span class="mono">{{ $cuota->n_cuota }}</span></td>
                                                        <td><span class="cuota-name">{{ $cuota->nombre }}</span></td>
                                                        <td>
                                                            <span class="mono">Bs. {{ number_format($montoNeto, 2) }}</span>
                                                            @if (($cuota->descuento_bs ?? 0) > 0)
                                                                <br><span class="text-muted-sm">-{{ number_format($cuota->descuento_bs, 2) }} desc.</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $cuota->fecha_vencimiento ? \Carbon\Carbon::parse($cuota->fecha_vencimiento)->format('d/m/Y') : '—' }}
                                                        </td>
                                                        <td>
                                                            <div class="cuota-pay-micro">
                                                                <div class="track">
                                                                    <div class="fill {{ $pctCuotaClass }}" style="width:{{ $pctCuota }}%;"></div>
                                                                </div>
                                                                <span class="pct">{{ $pctCuota }}%</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="estado-badge-est {{ $cuota->estado == 'Pagado' ? 'pagado' : ($cuota->estado == 'Vencido' ? 'vencido' : 'pendiente') }}">
                                                                {{ $cuota->estado }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if (count($pagosData) > 0)
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-primary btn-ver-detalle-pago"
                                                                    data-pagos='{{ json_encode($pagosData) }}'
                                                                    style="border-radius:8px;font-size:.72rem;padding:3px 10px;"
                                                                    title="Ver detalle de pagos">
                                                                    <i class="ri-eye-line"></i>
                                                                </button>
                                                            @else
                                                                <span style="font-size:.7rem;color:#cbd5e1;">—</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div style="padding:24px;text-align:center;color:#94a3b8;">
                                        <i class="ri-money-dollar-line" style="font-size:1.5rem;opacity:.5;display:block;margin-bottom:6px;"></i>
                                        <span style="font-size:.85rem;">Sin cuotas registradas</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>{{-- /contable-tabs-body --}}
                </div>{{-- /contable-tabs-wrapper --}}
            @else
                <div class="est-empty-state">
                    <i class="ri-money-dollar-line"></i>
                    <h5>Sin información contable</h5>
                    <p>No hay ofertas académicas registradas</p>
                </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TAB PAGOS — Comprobantes de Pago
        ══════════════════════════════════════════════════════════ --}}
        <div class="est-tabs-body" id="tab-pagos">

            @php
                $totalComprobantes = 0;
                $comprobantesVerificados = 0;
                $comprobantesPendientes = 0;
                $comprobantesRechazados = 0;
                foreach ($inscripciones as $ins) {
                    foreach ($ins->pagosRespaldos as $r) {
                        $totalComprobantes++;
                        if ($r->estado === 'verificado') $comprobantesVerificados++;
                        elseif ($r->estado === 'rechazado') $comprobantesRechazados++;
                        else $comprobantesPendientes++;
                    }
                }
            @endphp

            {{-- Stats al estilo del contable (balance card) --}}
            <div class="contable-balance-card" style="margin-bottom:20px;">
                <div class="contable-balance-header">
                    <i class="ri-file-list-3-line"></i>
                    <p class="contable-balance-title">Resumen de Comprobantes</p>
                </div>
                <div class="contable-stats-grid">
                    <div class="contable-stat-item">
                        <div class="contable-stat-icon pagado"><i class="ri-checkbox-circle-line"></i></div>
                        <div>
                            <div class="contable-stat-value pagado">{{ $comprobantesVerificados }}</div>
                            <div class="contable-stat-label">Verificados</div>
                        </div>
                    </div>
                    <div class="contable-stat-item">
                        <div class="contable-stat-icon pendiente"><i class="ri-time-line"></i></div>
                        <div>
                            <div class="contable-stat-value pendiente">{{ $comprobantesPendientes }}</div>
                            <div class="contable-stat-label">En revisión</div>
                        </div>
                    </div>
                    <div class="contable-stat-item">
                        <div class="contable-stat-icon vencido"><i class="ri-close-circle-line"></i></div>
                        <div>
                            <div class="contable-stat-value vencido">{{ $comprobantesRechazados }}</div>
                            <div class="contable-stat-label">Rechazados</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── CUENTAS BANCARIAS PARA PAGOS (rediseñado) ──────────── --}}
            @if($bancos->isNotEmpty())
            <div class="contable-balance-card" style="margin-bottom:20px;">
                <div class="contable-balance-header">
                    <i class="ri-bank-line"></i>
                    <p class="contable-balance-title">Cuentas Bancarias para Pagos</p>
                </div>
                <div class="pagos-bancos-grid">
                    @foreach($bancos as $banco)
                        @if($banco->cuentas->isNotEmpty())
                        <div class="pagos-banco-card">
                            <div class="pagos-banco-head">
                                <div class="pagos-banco-icon">
                                    <i class="ri-bank-line"></i>
                                </div>
                                <div>
                                    <div class="pagos-banco-name">{{ $banco->nombre }}</div>
                                    @if($banco->sigla)
                                    <div class="pagos-banco-sigla">{{ $banco->sigla }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="pagos-banco-body">
                                @foreach($banco->cuentas as $cuenta)
                                <div class="pagos-banco-cuenta">
                                    <div class="pagos-banco-cuenta-main">
                                        <div class="pagos-banco-cuenta-num">
                                            <i class="ri-exchange-dollar-line"></i>
                                            {{ $cuenta->numero_cuenta }}
                                        </div>
                                        <div class="pagos-banco-cuenta-meta">
                                            <span class="pagos-banco-badge {{ $cuenta->tipo_cuenta === 'Cuenta Corriente' ? 'cc' : 'ca' }}">
                                                {{ $cuenta->tipo_cuenta === 'Cuenta Corriente' ? 'Cta. Corriente' : 'Cta. Ahorro' }}
                                            </span>
                                            @if($cuenta->titular)
                                            <span class="pagos-banco-titular">
                                                <i class="ri-user-line"></i> {{ $cuenta->titular }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($cuenta->imagen_qr)
                                    <div class="pagos-banco-qr" onclick="abrirQrModal(this.querySelector('img').src)">
                                        <img src="{{ asset('storage/' . $cuenta->imagen_qr) }}" alt="QR" loading="lazy">
                                        <span><i class="ri-qr-code-line"></i> Ver QR</span>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            @if ($inscripciones->count() > 0)

                <div class="pagos-tabs-wrapper">
                    <div class="ctb-tabs">
                        @foreach ($inscripciones as $key => $ins)
                            @php
                                $insNombre = $ins->ofertaAcademica?->programa?->nombre
                                    ?? ($ins->ofertaAcademica?->posgrado?->nombre
                                    ?? 'Oferta #' . ($key + 1));
                            @endphp
                            <button type="button"
                                class="ctb-tab pagos-tab-btn {{ $key == 0 ? 'active' : '' }}"
                                data-target="pagos-oferta-{{ $key }}">
                                <i class="ri-money-dollar-circle-line"></i>
                                {{ $insNombre }}
                            </button>
                        @endforeach
                    </div>

                    @foreach ($inscripciones as $key => $ins)
                        @php
                            $cuotasPendIns = $ins->cuotas->filter(fn($c) => (float)($c->pago_pendiente_bs ?? $c->monto_bs) > 0);
                            $tienePendientes = $cuotasPendIns->isNotEmpty();
                        @endphp
                        <div class="pagos-oferta-content {{ $key == 0 ? 'active' : '' }}" id="pagos-oferta-{{ $key }}">

                            {{-- Grid 2 columnas: Cuotas + Comprobantes --}}
                            <div class="pagos-grid-2">

                                {{-- ── COLUMNA IZQUIERDA: Cuotas ──────────────────── --}}
                                <div class="pagos-card">
                                    <div class="pagos-card-header">
                                        <div class="pagos-card-header-left">
                                            <div class="pagos-card-icon orange"><i class="ri-installment-line"></i></div>
                                            <div>
                                                <div class="pagos-card-title">Estado de Cuotas</div>
                                                <div class="pagos-card-sub">{{ $ins->planesPago?->nombre ?? 'Sin plan' }} &middot; {{ $ins->cuotas->count() }} cuota(s)</div>
                                            </div>
                                        </div>
                                        @if ($tienePendientes)
                                            @php
                                                $progNombre = addslashes($ins->ofertaAcademica?->programa?->nombre ?? ($ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta'));
                                                $planNombre = addslashes($ins->planesPago?->nombre ?? '');
                                            @endphp
                                            <button type="button" class="pagos-btn-subir"
                                                onclick="estAbrirModal('{{ $ins->id }}', '{{ $progNombre }}', '{{ $planNombre }}')">
                                                <i class="ri-upload-cloud-line"></i> Subir
                                            </button>
                                        @else
                                            <span class="pagos-btn-al-dia">
                                                <i class="ri-checkbox-circle-fill"></i> Al día
                                            </span>
                                        @endif
                                    </div>
                                    <div class="pagos-card-body">
                                        @if ($ins->cuotas && $ins->cuotas->count() > 0)
                                            <table class="pagos-mini-table">
                                                <thead>
                                                    <tr>
                                                        <th style="width:28px;">#</th>
                                                        <th>Cuota</th>
                                                        <th style="width:80px;">Monto</th>
                                                        <th style="width:85px;">Vence</th>
                                                        <th style="width:80px;">Avance</th>
                                                        <th style="width:75px;">Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($ins->cuotas as $cuota)
                                                        @php
                                                            $estadoClass = $cuota->estado == 'Pagado' ? 'pagado' : ($cuota->estado == 'Vencido' ? 'vencido' : 'pendiente');
                                                            $totalPagadoCuota = $cuota->pagosCuota->sum('monto_bs');
                                                            $pctCuota = $cuota->monto_bs > 0 ? round(($totalPagadoCuota / $cuota->monto_bs) * 100) : 0;
                                                            $pctCuotaClass = $pctCuota >= 100 ? 'full' : ($pctCuota > 0 ? 'part' : 'empty');
                                                        @endphp
                                                        <tr>
                                                            <td data-label="#"><span class="num-cuota">{{ $cuota->n_cuota }}</span></td>
                                                            <td data-label="Cuota" style="font-weight:600;color:#1e293b;font-size:.76rem;">{{ $cuota->nombre }}</td>
                                                            <td data-label="Monto" style="font-weight:600;color:#1e293b;font-size:.76rem;">Bs. {{ number_format($cuota->monto_bs, 2) }}</td>
                                                            <td data-label="Vence" class="fecha-cell" style="font-size:.72rem;">{{ $cuota->fecha_vencimiento ? \Carbon\Carbon::parse($cuota->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
                                                            <td data-label="Avance" style="padding:8px 6px;">
                                                                <div class="cuota-pay-micro" style="gap:4px;">
                                                                    <div class="track" style="min-width:40px;">
                                                                        <div class="fill {{ $pctCuotaClass }}" style="width:{{ $pctCuota }}%;"></div>
                                                                    </div>
                                                                    <span class="pct" style="font-size:.65rem;min-width:28px;">{{ $pctCuota }}%</span>
                                                                </div>
                                                            </td>
                                                            <td data-label="Estado" style="padding:8px 6px;"><span class="pagos-cuota-badge {{ $estadoClass }}" style="font-size:.65rem;padding:2px 8px;">{{ $cuota->estado }}</span></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <div class="pagos-card-empty">
                                                <i class="ri-inbox-line"></i>
                                                <p>Sin cuotas registradas</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- ── COLUMNA DERECHA: Comprobantes ──────────────── --}}
                                <div class="pagos-card">
                                    <div class="pagos-card-header">
                                        <div class="pagos-card-header-left">
                                            <div class="pagos-card-icon indigo"><i class="ri-file-list-3-line"></i></div>
                                            <div>
                                                <div class="pagos-card-title">Comprobantes Enviados</div>
                                                <div class="pagos-card-sub">{{ $ins->pagosRespaldos->count() }} total(es)</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pagos-card-body">
                                        @if ($ins->pagosRespaldos->count() > 0)
                                            @foreach ($ins->pagosRespaldos->sortByDesc('created_at') as $resp)
                                                @php
                                                    $stClass = $resp->estado === 'verificado' ? 'verificado' : ($resp->estado === 'rechazado' ? 'rechazado' : 'revision');
                                                    $stLabel = $resp->estado === 'verificado' ? 'Verificado' : ($resp->estado === 'rechazado' ? 'Rechazado' : 'En revisión');
                                                    $archivoUrl = asset('storage/comprobantes/' . $resp->archivo);
                                                    $esImagen = preg_match('/\.(jpg|jpeg|png)$/i', $resp->archivo);
                                                @endphp
                                                <div class="pagos-comp-row">
                                                    <div class="pagos-comp-icon {{ $esImagen ? 'img' : 'pdf' }}">
                                                        <i class="{{ $esImagen ? 'ri-image-fill' : 'ri-file-pdf-fill' }}"></i>
                                                    </div>
                                                    <div class="pagos-comp-body">
                                                        <div class="top">
                                                            <span class="fecha"><i class="ri-calendar-line"></i> {{ $resp->created_at->format('d/m/Y') }} <span style="color:#cbd5e1;">{{ $resp->created_at->format('H:i') }}</span></span>
                                                            <div class="cuota-tags">
                                                                @forelse ($resp->cuotas as $cq)
                                                                    <span class="cuota-tag">{{ $cq->nombre }}</span>
                                                                @empty
                                                                    <span style="font-size:.65rem;color:#94a3b8;">—</span>
                                                                @endforelse
                                                            </div>
                                                        </div>
                                                        @if ($resp->observaciones)
                                                            <div class="obs">{{ $resp->observaciones }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="pagos-comp-actions">
                                                        <span class="pagos-comp-badge {{ $stClass }}">{{ $stLabel }}</span>
                                                        <a href="{{ $archivoUrl }}" target="_blank" class="pagos-comp-link" title="Ver archivo">
                                                            <i class="{{ $esImagen ? 'ri-eye-line' : 'ri-file-pdf-line' }}"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="pagos-card-empty">
                                                <i class="ri-file-upload-line"></i>
                                                <p>No has enviado comprobantes aún</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>{{-- /pagos-grid-2 --}}

                        </div>
                    @endforeach
                </div>

            @else
                <div class="est-empty-state">
                    <i class="ri-file-list-3-line"></i>
                    <h5>Sin inscripciones</h5>
                    <p>No hay inscripciones para mostrar</p>
                </div>
            @endif
        </div>{{-- /tab-pagos --}}

    {{-- QR Lightbox --}}
    <div id="qrOverlay" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,.75);align-items:center;justify-content:center;backdrop-filter:blur(4px);" onclick="cerrarQrOverlay(event)">
        <div style="background:#fff;border-radius:18px;padding:2rem;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,.35);max-width:360px;margin:1rem;position:relative;" onclick="event.stopPropagation()">
            <button onclick="cerrarQrOverlay()" style="position:absolute;top:10px;right:14px;background:none;border:none;font-size:1.3rem;color:#94a3b8;cursor:pointer;padding:4px;line-height:1;"><i class="ri-close-line"></i></button>
            <img id="qrLightboxImg" src="" alt="QR" style="max-width:260px;border-radius:10px;">
            <p style="margin:.85rem 0 0;font-size:.82rem;color:#64748b;font-weight:500;">Código QR — Escanea para realizar el pago</p>
        </div>
    </div>

    {{-- Modal Subir Comprobante (estudiante) — rediseñado --}}
    <div class="modal fade" id="modalEstComprobante" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:580px;">
            <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 25px 60px rgba(0,0,0,.25);">
                {{-- Header con gradiente oscuro (estilo contable) --}}
                <div style="background:linear-gradient(135deg,#1e293b 0%,#2d3748 100%);padding:18px 24px;display:flex;align-items:center;justify-content:space-between;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:10px;background:rgba(252,123,4,.15);color:#fc7b04;display:flex;align-items:center;justify-content:center;font-size:1.05rem;flex-shrink:0;">
                            <i class="ri-file-upload-line"></i>
                        </div>
                        <div>
                            <div style="font-family:'Outfit',sans-serif;font-size:.9rem;font-weight:700;color:#fff;line-height:1.2;">Subir Comprobante de Pago</div>
                            <div style="font-size:.7rem;color:#94a3b8;">Adjunta el respaldo de tu pago realizado</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="opacity:.7;filter:brightness(2);"></button>
                </div>

                <div style="padding:22px 24px;background:#fff;">
                    {{-- Info programa/plan con icono --}}
                    <div style="display:flex;align-items:center;gap:12px;background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%);border-radius:12px;padding:14px 16px;margin-bottom:20px;border-left:4px solid #fc7b04;">
                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(252,123,4,.1);color:#fc7b04;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">
                            <i class="ri-book-2-line"></i>
                        </div>
                        <div>
                            <div style="font-family:'Outfit',sans-serif;font-weight:700;font-size:.88rem;color:#1e293b;" id="estCompPrograma"></div>
                            <div style="font-size:.75rem;color:#64748b;margin-top:2px;" id="estCompPlan"></div>
                        </div>
                    </div>

                    {{-- Archivo --}}
                    <div style="margin-bottom:18px;">
                        <label style="font-size:.78rem;font-weight:700;color:#475569;margin-bottom:7px;display:block;">
                            Archivo del comprobante <span style="color:#dc2626;">*</span>
                        </label>
                        <div style="border:2px dashed #e2e8f0;border-radius:14px;padding:28px;text-align:center;background:#fafbfc;cursor:pointer;transition:all .25s;position:relative;"
                             id="comprobanteFileArea"
                             onclick="document.getElementById('estCompArchivo').click()"
                             onmouseover="this.style.borderColor='#fc7b04';this.style.background='rgba(252,123,4,.03)'"
                             onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#fafbfc'">
                            <i class="ri-upload-cloud-line" style="font-size:2.2rem;color:#cbd5e1;display:block;margin-bottom:10px;"></i>
                            <span style="font-size:.82rem;color:#64748b;display:block;">Haz clic para seleccionar el archivo</span>
                            <small style="font-size:.7rem;color:#94a3b8;margin-top:6px;display:block;">JPG, PNG o PDF — máx. 5 MB</small>
                        </div>
                        <input type="file" id="estCompArchivo" accept=".jpg,.jpeg,.png,.pdf" style="display:none;">
                    </div>

                    {{-- Observaciones --}}
                    <div style="margin-bottom:18px;">
                        <label style="font-size:.78rem;font-weight:700;color:#475569;margin-bottom:7px;display:block;">Observaciones</label>
                        <textarea id="estCompObservaciones" rows="2"
                            style="width:100%;border:2px solid #e2e8f0;border-radius:12px;padding:12px 14px;font-size:.82rem;background:#f8fafc;transition:all .2s;resize:vertical;font-family:inherit;"
                            placeholder="Opcional: Agrega alguna observación sobre tu pago..."
                            onfocus="this.style.borderColor='#fc7b04';this.style.boxShadow='0 0 0 3px rgba(252,123,4,.08)';this.style.background='#fff'"
                            onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';this.style.background='#f8fafc'"></textarea>
                    </div>

                    {{-- Cuotas que cubre --}}
                    <div>
                        <label style="font-size:.78rem;font-weight:700;color:#475569;margin-bottom:7px;display:block;">
                            Cuotas que cubre este comprobante <span style="color:#dc2626;">*</span>
                        </label>
                        <div id="estCompCuotasLoading" style="text-align:center;padding:16px 0;">
                            <div class="spinner-border spinner-border-sm" style="color:#fc7b04;"></div>
                            <span style="margin-left:8px;font-size:.78rem;color:#94a3b8;">Cargando cuotas...</span>
                        </div>
                        <div id="estCompCuotasContainer" style="display:grid;gap:8px;display:none;"></div>
                    </div>
                </div>

                {{-- Footer --}}
                <div style="border-top:1px solid #e2e8f0;padding:14px 24px;background:#f8fafc;display:flex;justify-content:flex-end;gap:10px;">
                    <button type="button" data-bs-dismiss="modal"
                        style="padding:11px 20px;border-radius:10px;border:2px solid #e2e8f0;background:#fff;color:#475569;font-weight:600;font-size:.82rem;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:6px;"
                        onmouseover="this.style.borderColor='#cbd5e1';this.style.background='#f1f5f9'"
                        onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#fff'">
                        <i class="ri-close-line"></i> Cancelar
                    </button>
                    <button type="button" id="btnEstEnviarComprobante"
                        style="padding:11px 22px;border-radius:10px;border:none;background:linear-gradient(135deg,#fc7b04 0%,#e67300 100%);color:#fff;font-weight:600;font-size:.82rem;cursor:pointer;transition:all .25s;box-shadow:0 3px 12px rgba(252,123,4,.25);display:flex;align-items:center;gap:6px;"
                        onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 5px 18px rgba(252,123,4,.35)'"
                        onmouseout="this.style.transform='none';this.style.boxShadow='0 3px 12px rgba(252,123,4,.25)'">
                        <i class="ri-send-plane-line"></i> Enviar Comprobante
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast container (estudiante) --}}
    <div id="est-toast-container" style="position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:.5rem;"></div>

    {{-- Modal Ver Detalle Pago (usado por tab contable) --}}
    <div class="modal fade pmp-modal" id="modalVerDetallePago" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content pmp-content">

                <div class="pmp-header">
                    <div class="pmp-header-icon"><i class="ri-file-list-3-line"></i></div>
                    <div class="pmp-header-text">
                        <h5 class="pmp-header-title">Detalle del Pago</h5>
                        <small class="pmp-header-sub">Comprobante de pago</small>
                    </div>
                    <button type="button" class="pmp-close-btn" data-bs-dismiss="modal" aria-label="Cerrar">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <div class="pmp-body">
                    <div id="lista-pagos" class="list-group list-group-flush"
                        style="max-height: 400px; overflow-y: auto; border-radius: 10px; border: 1px solid #e2e8f0;"></div>
                    <div id="detalle-pago-container" class="p-3" style="display: none; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <button type="button" class="pmp-btn pmp-btn-cancel btn-cerrar mb-2" id="btn-volver-lista">
                            <i class="ri-arrow-left-line"></i> Volver
                        </button>
                        <div class="border-bottom pb-2 mb-3" style="border-bottom: 2px solid var(--est-primary);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('images/logo_secundario.png') }}" alt="Logo" style="width: 40px;">
                                    <div>
                                        <div class="fw-bold" style="font-size: 12px; color: #1e293b;">INNOVA CIENCIA VIRTUAL</div>
                                        <div class="text-muted" style="font-size: 9px;">Educación Superior Virtual</div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold" style="font-size: 14px; color: var(--est-primary);">COMPROBANTE</div>
                                    <div class="pmp-section-title d-inline-flex mt-1" id="detalle-recibo">—</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 text-muted" style="font-size: 10px;">
                                <span><strong>Fecha:</strong> <span id="detalle-fecha">—</span></span>
                                <span><strong>Forma Pago:</strong> <span id="detalle-metodo">—</span></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-1" style="font-size: 0.85rem;">
                                <strong>Estudiante:</strong> <span id="detalle-estudiante">—</span>
                            </div>
                            <div class="mb-1" style="font-size: 0.85rem;">
                                <strong>Programa:</strong> <span id="detalle-programa">—</span>
                            </div>
                            <div class="mb-1" style="font-size: 0.85rem;">
                                <strong>Plan de Pago:</strong> <span id="detalle-plan">—</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <table class="table table-bordered table-sm" style="font-size: 10px; margin-bottom: 0;">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Concepto</th>
                                        <th class="text-end">Monto</th>
                                    </tr>
                                </thead>
                                <tbody id="detalle-tabla"></tbody>
                                <tfoot class="table-warning">
                                    <tr>
                                        <td colspan="2" class="fw-bold">Total (Bs.)</td>
                                        <td class="text-end fw-bold" id="detalle-total">—</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="mb-2" id="detalle-descuento-container" style="display: none;">
                            <strong>Descuento:</strong> <span class="text-warning" id="detalle-descuento">—</span>
                        </div>
                        <div id="detalle-factura-container" style="display:none; margin-bottom:12px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%);border-radius:10px;">
                                <div>
                                    <div style="font-size:.7rem;font-weight:600;color:#059669;"><i class="ri-file-list-3-line"></i> Factura</div>
                                    <div style="font-size:.85rem;font-weight:700;color:#059669;" id="detalle-factura-estado">Con factura</div>
                                </div>
                                <button type="button" id="btn-ver-factura-detalle"
                                    style="background:#059669;color:white;border:none;border-radius:8px;padding:6px 14px;font-size:.78rem;cursor:pointer;display:flex;align-items:center;gap:5px;">
                                    <i class="ri-eye-line"></i> Ver factura
                                </button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4" style="font-size: 10px;">
                            <div class="text-center" style="width: 45%;">
                                <div class="border-top py-1" id="detalle-trabajador">—</div>
                                <div class="fw-bold">EMISOR</div>
                            </div>
                            <div class="text-center" style="width: 45%;">
                                <div class="border-top py-1" id="detalle-depositante">—</div>
                                <div class="fw-bold">DEPOSITANTE</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pmp-footer">
                    <a href="#" id="btn-descargar-pdf" class="pmp-btn pmp-btn-submit" target="_blank">
                        <i class="ri-file-pdf-line"></i> Descargar PDF
                    </a>
                    <button type="button" class="pmp-btn pmp-btn-cancel btn-cerrar" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i> Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>

{{-- ══════════════════════════════════════════════════════════
     TAB CRONOGRAMA — Cronograma de Clases
═════════════════════════════════════════════════════════ --}}
<div class="est-tabs-body" id="tab-cronograma">

    @if($ofertasCronograma->isEmpty())
        <div class="est-empty-state">
            <i class="ri-calendar-close-line"></i>
            <h5>No tienes ofertas inscritas</h5>
            <p>No tienes ofertas académicas con inscripción activa para mostrar cronograma.</p>
        </div>
    @else
        <div class="cronograma-container d-flex" style="min-height: 600px;">
            <div class="cronograma-sidebar">
                <div class="cronograma-sidebar-head">
                    <i class="ri-calendar-event-line"></i>
                    <span>Oferta Académica</span>
                </div>
                <div class="cronograma-sidebar-body">
                    <select class="cronograma-select" id="select-oferta-cronograma" onchange="cargarModulosCronograma()">
                        <option value="">Seleccionar oferta académica</option>
                        @foreach($ofertasCronograma as $oferta)
                            <option value="{{ $oferta['id'] }}">{{ $oferta['codigo'] }} - {{ $oferta['nombre'] }}</option>
                        @endforeach
                    </select>
                    <button class="cronograma-btn-all" id="btnTodosModulosCronograma" onclick="verTodosModulosCronograma()">
                        <i class="ri-layout-grid-line"></i> Todos los módulos
                    </button>
                    <div id="modulosSidebarListCronograma">
                        <div class="cronograma-sidebar-empty">
                            <i class="ri-arrow-up-line"></i>
                            Selecciona una oferta académica
                        </div>
                    </div>
                </div>
            </div>
            <div class="cronograma-main">
                <div class="cronograma-title-section">
                    <div class="cronograma-title-left">
                        <div class="cronograma-title-icon">
                            <i class="ri-calendar-line"></i>
                        </div>
                        <div class="cronograma-title-text">
                            <h4>Calendario de Sesiones</h4>
                            <span>Visualiza todas tus clases programadas</span>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                        <div style="display:flex;align-items:center;gap:.4rem;font-size:.72rem;color:#64748b;">
                            <span class="cronograma-legend-dot confirmed"></span><span>Confirmado</span>
                            <span class="cronograma-legend-dot postponed"></span><span>Postergado</span>
                        </div>
                        <div id="moduloSeleccionadoBadgeCronograma" class="cronograma-filter-badge" style="display: none;">
                            <span class="dot"></span>
                            <span class="modulo-badge-name"></span>
                            <button type="button" title="Quitar filtro" onclick="verTodosModulosCronograma()">
                                <i class="ri-close-circle-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="cronograma-calendar-wrapper">
                    <div id="calendarCronograma"></div>
                </div>
            </div>
        </div>
    @endif
        </div>{{-- /content-estudiante --}}
</div>
    @endif

{{-- ══════════════════════════════════════════════════════════════════════════
     MODAL ACTIVIDADES ESTUDIANTE
══════════════════════════════════════════════════════════════════════════ --}}
<div id="modal-act-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9000;overflow-y:auto;padding:2rem 1rem;">
    <div id="modal-act-box" style="background:#fff;border-radius:16px;max-width:780px;margin:0 auto;box-shadow:0 20px 60px rgba(0,0,0,.25);display:flex;flex-direction:column;max-height:90vh;">
        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid #e9ecef;flex-shrink:0;">
            <div style="display:flex;align-items:center;gap:.75rem;">
                <span id="modal-act-icon" style="font-size:1.4rem;"></span>
                <div>
                    <div id="modal-act-title" style="font-size:1rem;font-weight:700;color:#2c3e50;line-height:1.2;"></div>
                    <div id="modal-act-subtitle" style="font-size:.78rem;color:#6c757d;"></div>
                </div>
            </div>
            <button onclick="cerrarModalAct()" style="background:none;border:none;font-size:1.5rem;color:#6c757d;cursor:pointer;line-height:1;padding:.25rem .5rem;">&times;</button>
        </div>
        {{-- Body --}}
        <div id="modal-act-body" style="padding:1.5rem;overflow-y:auto;flex:1;">
            <div id="modal-act-loading" style="text-align:center;padding:2rem;color:#6c757d;">
                <div class="spinner-border spinner-border-sm" style="color:#fc7b04;"></div>
                <span style="margin-left:.5rem;font-size:.9rem;">Cargando…</span>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     MODAL DETALLE SESIÓN ESTUDIANTE
══════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalDetalleSesionEst" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:780px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden;">

            <div class="modal-header py-3 px-4" style="background:linear-gradient(135deg,#1e293b 0%,#2d3748 100%);border:none;">
                <div class="d-flex align-items-center gap-3 flex-grow-1 min-width-0">
                    <div id="estDetColorBar" style="width:8px;height:36px;border-radius:8px;flex-shrink:0;"></div>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold text-white" style="font-size:.95rem;">
                            <i class="ri-calendar-event-line me-2"></i>Detalle de Sesión
                        </h5>
                        <div style="font-size:.72rem;color:rgba(255,255,255,.65);margin-top:.1rem;">Información de la sesión académica</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-0">
                <div class="row g-0">

                    {{-- Columna izquierda: info académica (rediseñada) --}}
                    <div class="col-md-5 d-flex flex-column" style="padding:1.25rem 1.35rem;border-right:1px solid #e9ecef;background:#f8fafc;gap:14px;">

                        {{-- Módulo --}}
                        <div class="d-flex align-items-start gap-3">
                            <div class="cronograma-modal-icon" style="background:rgba(252,123,4,.1);color:#fc7b04;">
                                <i class="ri-book-open-line"></i>
                            </div>
                            <div style="min-width:0;flex:1;">
                                <div class="cronograma-modal-label">Módulo Académico</div>
                                <div class="cronograma-modal-value" id="estDetModulo"></div>
                            </div>
                        </div>

                        {{-- Fecha y hora en grid compacto --}}
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                            <div class="d-flex align-items-start gap-2">
                                <div class="cronograma-modal-icon-sm" style="background:rgba(41,156,219,.1);color:#299cdb;">
                                    <i class="ri-calendar-line"></i>
                                </div>
                                <div>
                                    <div class="cronograma-modal-label">Fecha</div>
                                    <div class="cronograma-modal-value-sm" id="estDetFecha"></div>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <div class="cronograma-modal-icon-sm" style="background:rgba(34,197,94,.1);color:#22c55e;">
                                    <i class="ri-time-line"></i>
                                </div>
                                <div>
                                    <div class="cronograma-modal-label">Horario</div>
                                    <div class="cronograma-modal-value-sm" id="estDetHora"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Docente --}}
                        <div class="d-flex align-items-start gap-2">
                            <div class="cronograma-modal-icon-sm" style="background:rgba(99,102,241,.1);color:#6366f1;">
                                <i class="ri-user-star-line"></i>
                            </div>
                            <div style="min-width:0;flex:1;">
                                <div class="cronograma-modal-label">Docente Encargado</div>
                                <div class="cronograma-modal-value-sm" id="estDetDocente"></div>
                            </div>
                        </div>

                        {{-- Estado + Reprogramado compacto --}}
                        <div>
                            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="cronograma-modal-icon-sm" style="background:rgba(245,158,11,.1);color:#f59e0b;">
                                        <i class="ri-flag-line"></i>
                                    </div>
                                    <div>
                                        <div class="cronograma-modal-label">Estado</div>
                                        <div id="estDetEstado"></div>
                                    </div>
                                </div>
                                <div id="estDetReprogramadoInfo" style="display:none;flex:1;min-width:0;" class="alert alert-info py-2 px-3 border-0 rounded-3 mb-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-information-line" style="font-size:.85rem;"></i>
                                        <div style="font-size:.72rem;font-weight:500;" id="estDetReprogramadoMsg"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Columna derecha: enlaces (rediseñada) --}}
                    <div class="col-md-7 d-flex flex-column" style="padding:1.25rem 1.35rem;gap:12px;">

                        {{-- Enlace sesión virtual --}}
                        <div id="estDetEnlaceWrap" style="display:none;border-radius:12px;border:1.5px solid rgba(99,102,241,.2);overflow:hidden;">
                            <div style="background:linear-gradient(135deg,rgba(99,102,241,.06) 0%,rgba(99,102,241,.02) 100%);padding:14px 16px;">
                                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                                    <div style="width:30px;height:30px;border-radius:8px;background:rgba(99,102,241,.1);color:#6366f1;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="ri-video-chat-line" style="font-size:.85rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#6366f1;">Sesión Virtual</div>
                                        <div style="font-size:.75rem;font-weight:600;color:#4338ca;margin-top:1px;" id="estDetEnlaceNombre"></div>
                                    </div>
                                </div>
                                <button id="estDetEnlaceBtn" type="button"
                                    style="width:100%;display:flex;align-items:center;justify-content:center;gap:6px;padding:9px 14px;border-radius:8px;border:none;background:linear-gradient(135deg,#6366f1 0%,#4f46e5 100%);color:#fff;font-weight:600;font-size:.78rem;cursor:pointer;transition:all .2s;"
                                    onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 3px 10px rgba(99,102,241,.3)'"
                                    onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                                    <i class="ri-external-link-line"></i> Unirse a la sesión virtual
                                </button>
                            </div>
                        </div>

                        {{-- Grabación --}}
                        <div id="estDetGrabacionWrap" style="display:none;border-radius:12px;border:1.5px solid rgba(220,38,38,.15);overflow:hidden;">
                            <div style="background:linear-gradient(135deg,rgba(220,38,38,.04) 0%,rgba(220,38,38,.01) 100%);padding:14px 16px;">
                                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                                    <div style="width:30px;height:30px;border-radius:8px;background:rgba(220,38,38,.1);color:#dc2626;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="ri-vidicon-line" style="font-size:.85rem;"></i>
                                    </div>
                                    <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#b91c1c;">Grabación de la Sesión</div>
                                </div>
                                <button id="estDetGrabacionBtn" type="button"
                                    style="width:100%;display:flex;align-items:center;justify-content:center;gap:6px;padding:9px 14px;border-radius:8px;border:none;background:linear-gradient(135deg,#dc2626 0%,#b91c1c 100%);color:#fff;font-weight:600;font-size:.78rem;cursor:pointer;transition:all .2s;"
                                    onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 3px 10px rgba(220,38,38,.3)'"
                                    onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                                    <i class="ri-play-circle-line"></i> Ver grabación
                                </button>
                            </div>
                        </div>

                        {{-- Sin enlaces --}}
                        <div id="estDetSinEnlaces" class="d-flex flex-column align-items-center justify-content-center flex-grow-1 text-center" style="color:#94a3b8;padding:20px;">
                            <i class="ri-calendar-check-line" style="font-size:2.2rem;margin-bottom:10px;opacity:.35;"></i>
                            <div style="font-size:.82rem;font-weight:600;color:#64748b;">Sesión confirmada</div>
                            <div style="font-size:.72rem;margin-top:4px;line-height:1.5;">Los enlaces de sesión virtual o grabación<br>aparecerán aquí cuando estén disponibles.</div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:12px 20px;background:#f8fafc;display:flex;justify-content:flex-end;">
                <button type="button" data-bs-dismiss="modal"
                    style="padding:8px 18px;border-radius:8px;border:1.5px solid #e2e8f0;background:#fff;color:#475569;font-weight:600;font-size:.78rem;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:5px;"
                    onmouseover="this.style.borderColor='#cbd5e1';this.style.background='#f1f5f9'"
                    onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#fff'">
                    <i class="ri-close-line"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal para ver factura --}}
<div class="modal fade pmp-modal" id="modalVerFactura" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content pmp-content">

            <div class="pmp-header">
                <div class="pmp-header-icon"><i class="ri-file-list-3-line"></i></div>
                <div class="pmp-header-text">
                    <h5 class="pmp-header-title">Factura — <span id="facturaReciboNum"></span></h5>
                    <small class="pmp-header-sub">Documento fiscal del pago</small>
                </div>
                <button type="button" class="pmp-close-btn" data-bs-dismiss="modal" aria-label="Cerrar">
                    <i class="ri-close-line"></i>
                </button>
            </div>

            <div class="pmp-body">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:18px;padding:14px 18px;background:linear-gradient(135deg,#fdf6ee,#fef9f2);border:1px solid #e9e2d9;border-radius:12px;">
                    <img src="{{ asset('images/logo_secundario.png') }}" alt="Logo" style="width:42px;height:42px;object-fit:contain;flex-shrink:0;">
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:.7rem;color:#927f64;text-transform:uppercase;letter-spacing:.06em;font-weight:600;">INNOVA CIENCIA VIRTUAL</div>
                        <div style="display:flex;flex-wrap:wrap;gap:14px;margin-top:6px;font-size:.82rem;">
                            <span><span style="color:#927f64;">Estudiante:</span> <strong id="facturaEstudiante" style="color:#1e293b;">—</strong></span>
                            <span><span style="color:#927f64;">Monto:</span> <strong id="facturaMonto" style="color:#059669;">—</strong></span>
                            <span><span style="color:#927f64;">Programa:</span> <strong id="facturaOferta" style="color:#1e293b;">—</strong></span>
                        </div>
                    </div>
                </div>

                <div style="background:#faf8f5;border:1px solid #e9e2d9;border-radius:12px;padding:4px;min-height:300px;">
                    <div id="facturaFileContainer" style="max-height:520px;overflow:auto;border-radius:10px;"></div>
                </div>
            </div>

            <div class="pmp-footer">
                <a id="facturaDownloadLink" href="#" target="_blank" class="pmp-btn pmp-btn-submit">
                    <i class="ri-download-2-line"></i> Descargar
                </a>
                <button type="button" class="pmp-btn pmp-btn-cancel" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i> Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ── Modal: Cambiar foto de perfil (estudiante/docente) ─────────── --}}
<div class="modal fade est-foto-modal" id="estFotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-camera-line me-2"></i>Cambiar foto de perfil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <div class="est-foto-preview-wrap">
                    <img id="estFotoPreview" src="{{ $heroAvatarUrl }}" alt="Preview" class="est-foto-preview">
                </div>
                <div class="est-foto-drop" id="estFotoDrop" onclick="document.getElementById('estFotoInput').click()">
                    <i class="ri-upload-cloud-2-line"></i>
                    <div style="font-size:.85rem;font-weight:600;color:#1e293b;">Haz clic para seleccionar tu foto</div>
                    <div style="font-size:.72rem;color:#64748b;margin-top:.2rem;">JPG, JPEG o PNG — máximo 2 MB</div>
                </div>
                <input type="file" id="estFotoInput" accept="image/jpeg,image/jpg,image/png" class="d-none">
                <div id="estFotoAlert" class="alert d-none mt-3 mb-0" role="alert" style="font-size:.82rem;"></div>
            </div>
            <div class="modal-footer border-0 pt-0 pb-3 px-4">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="est-foto-btn-save" id="estFotoBtnSave" disabled>
                    <i class="ri-save-line"></i> Guardar Foto
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/fullcalendar/index.global.min.js') }}"></script>
    <script>
        /* ── Variables globales ── */
        const loaded = {};
        let calendarioCronograma    = null;
        let calendarioDocente       = null;   // legacy — ya no se usa directamente
        let calendarioHorarioDocente = null;  // nuevo calendario docente (cronograma style)
        let datosCronograma = @json($ofertasCronograma);
        @if ($esDocente)
        let datosHorariosDocente = {!! json_encode($horariosDocente) !!};
        let datosOfertasDocente  = @json($ofertasHorariosDocente);
        @endif
        let moduloSeleccionadoId = null;
        let moduloSeleccionadoHorarioDocenteId = null;

        function formatDateEs(dateStr) {
            if (!dateStr) return '—';
            const d = new Date(dateStr);
            const meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
            return d.getDate() + ' de ' + meses[d.getMonth()] + ' del ' + d.getFullYear();
        }

        /* ── switchTab (estudiante tabs) ── */
        function switchTab(btn, tabId) {
            var nav = btn.closest('.est-tabs-nav');
            if (nav) nav.querySelectorAll('.est-tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            var container = document.getElementById('content-estudiante');
            if (container) container.querySelectorAll('.est-tabs-body').forEach(t => t.classList.remove('active'));
            var targetTab = document.getElementById(tabId);
            if (targetTab) targetTab.classList.add('active');

            if (tabId === 'tab-cronograma') {
                if (!calendarioCronograma && datosCronograma && datosCronograma.length > 0) {
                    const select = document.getElementById('select-oferta-cronograma');
                    if (select) {
                        select.value = datosCronograma[0].id;
                        cargarModulosCronograma();
                    }
                } else if (calendarioCronograma) {
                    setTimeout(function() { calendarioCronograma.updateSize(); }, 10);
                }
            }
        }

        /* ── switchTabDocente (docente tabs) ── */
        function switchTabDocente(btn, tabId) {
            var nav = document.getElementById('nav-docente');
            if (nav) nav.querySelectorAll('.est-tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            var container = document.getElementById('content-docente');
            if (container) container.querySelectorAll('.est-tabs-body').forEach(t => t.classList.remove('active'));
            var targetTab = document.getElementById(tabId);
            if (targetTab) targetTab.classList.add('active');

            if (tabId === 'tab-horario-docente') {
                setTimeout(function() {
                    if (!calendarioHorarioDocente && datosOfertasDocente && datosOfertasDocente.length > 0) {
                        const sel = document.getElementById('select-oferta-horario-docente');
                        if (sel) {
                            sel.value = datosOfertasDocente[0].id;
                            cargarModulosHorarioDocente();
                        }
                    } else if (calendarioHorarioDocente) {
                        calendarioHorarioDocente.updateSize();
                    }
                }, 50);
            }
        }

        window.switchTab = switchTab;
        window.switchTabDocente = switchTabDocente;

        /* ── Cronograma funciones ─────────────────────────────── */
        window.cargarModulosCronograma = function() {
                const ofertaId = document.getElementById('select-oferta-cronograma').value;
                const listaModulos = document.getElementById('modulosSidebarListCronograma');
                const btnTodos = document.getElementById('btnTodosModulosCronograma');
                const badge = document.getElementById('moduloSeleccionadoBadgeCronograma');

                moduloSeleccionadoId = null;
                badge.style.display = 'none';
                btnTodos.classList.add('active');

                if (!ofertaId) {
                    listaModulos.innerHTML = '<div class="cronograma-sidebar-empty"><i class="ri-inbox-line"></i>Selecciona una oferta</div>';
                    if (calendarioCronograma) {
                        calendarioCronograma.removeAllEvents();
                    }
                    return;
                }

                const oferta = datosCronograma.find(function(o) { return o.id == parseInt(ofertaId); });
                if (!oferta) {
                    return;
                }

                listaModulos.innerHTML = oferta.modulos.map(function(modulo) {
                    var badgeMoodle = modulo.moodle_course_id ? 
                        '<span style="background:rgba(21,101,192,0.12);color:#1565c0;padding:1px 5px;border-radius:4px;font-size:0.65rem;margin-left:5px;">Moodle</span>' : '';
                    var docenteHtml = '<div class="cronograma-modulo-docente">' + (modulo.docente || 'Sin docente') + '</div>';
                    var sesionesCount = modulo.sesiones_count + '/' + oferta.cantidad_sesiones;
                    return '<div class="cronograma-modulo-card" style="--mod-color:' + modulo.color + ';" onclick="seleccionarModuloCronograma(' + modulo.id + ', \'' + modulo.nombre.replace(/'/g, "\\'") + '\', \'' + modulo.color + '\', event)">' +
                        '<div class="cronograma-modulo-dot" style="background:' + modulo.color + '"></div>' +
                        '<div class="cronograma-modulo-info">' +
                            '<div class="cronograma-modulo-num">Módulo ' + modulo.numero + '</div>' +
                            '<div class="cronograma-modulo-name">' + modulo.nombre + badgeMoodle + '</div>' +
                            docenteHtml +
                        '</div>' +
                        '<div class="cronograma-modulo-badge">' + sesionesCount + '</div>' +
                        '</div>';
                }).join('');

                actualizarCalendarioCronograma(oferta.modulos);
            };

            window.seleccionarModuloCronograma = function(moduloId, moduloNombre, moduloColor, evt) {
                const badge = document.getElementById('moduloSeleccionadoBadgeCronograma');
                const btnTodos = document.getElementById('btnTodosModulosCronograma');

                badge.style.display = 'flex';
                badge.querySelector('.dot').style.background = moduloColor;
                badge.querySelector('.modulo-badge-name').textContent = 'Módulo: ' + moduloNombre;

                btnTodos.classList.remove('active');
                document.querySelectorAll('.cronograma-modulo-card').forEach(function(el) { el.classList.remove('active'); });
                if (evt && evt.target) {
                    evt.target.closest('.cronograma-modulo-card').classList.add('active');
                }

                moduloSeleccionadoId = moduloId;

                const ofertaId = document.getElementById('select-oferta-cronograma').value;
                const oferta = datosCronograma.find(function(o) { return o.id == parseInt(ofertaId); });
                const modulo = oferta.modulos.find(function(m) { return m.id == moduloId; });
                
                actualizarCalendarioCronograma([modulo]);
            };

            window.verTodosModulosCronograma = function() {
                const ofertaId = document.getElementById('select-oferta-cronograma').value;
                const badge = document.getElementById('moduloSeleccionadoBadgeCronograma');
                const btnTodos = document.getElementById('btnTodosModulosCronograma');

                moduloSeleccionadoId = null;
                badge.style.display = 'none';
                btnTodos.classList.add('active');

                document.querySelectorAll('.cronograma-modulo-card').forEach(function(el) { el.classList.remove('active'); });

                if (!ofertaId) return;

                const oferta = datosCronograma.find(function(o) { return o.id == parseInt(ofertaId); });
                actualizarCalendarioCronograma(oferta.modulos);
            };

            window.actualizarCalendarioCronograma = function(modulos) {
                const eventos = [];
                modulos.forEach(function(modulo) {
                    if (modulo.sesiones && modulo.sesiones.length > 0) {
                        modulo.sesiones.forEach(function(sesion) {
                            const esPostergado = sesion.estado === 'Postergado';
                            eventos.push({
                                id: sesion.id,
                                title: sesion.titulo,
                                start: sesion.start,
                                end: sesion.end,
                                backgroundColor: esPostergado ? 'transparent' : modulo.color,
                                borderColor: modulo.color,
                                textColor: esPostergado ? modulo.color : '#fff',
                                extendedProps: {
                                    modulo_nombre: sesion.titulo,
                                    modulo_color: modulo.color,
                                    docente: sesion.docente,
                                    salon: sesion.salon,
                                    estado: sesion.estado,
                                    enlace_videollamada_url:    sesion.enlace_videollamada_url    || '',
                                    enlace_videollamada_nombre: sesion.enlace_videollamada_nombre || '',
                                    enlace_grabacion:           sesion.enlace_grabacion           || '',
                                    reprogramado_de_fecha:      sesion.reprogramado_de_fecha      || null,
                                    reprogramado_a_fecha:       sesion.reprogramado_a_fecha       || null,
                                }
                            });
                        });
                    }
                });

                const calendarEl = document.getElementById('calendarCronograma');

                if (!calendarioCronograma) {
                    calendarioCronograma = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,listMonth'
                        },
                        locale: 'es',
                        buttonText: {
                            today: 'Hoy',
                            month: 'Mes',
                            week: 'Semana',
                            list: 'Lista'
                        },
                        editable: false,
                        selectable: false,
                        eventDisplay: 'block',
                        eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
                        eventDidMount: function(info) {
                            const estado = info.event.extendedProps.estado;
                            const color  = info.event.extendedProps.modulo_color || info.event.backgroundColor;
                            if (estado === 'Postergado') {
                                info.el.classList.add('fc-event-postergado');
                                info.el.style.setProperty('border-color', color, 'important');
                                const titleEl = info.el.querySelector('.fc-event-title');
                                if (titleEl) {
                                    titleEl.style.setProperty('color', color, 'important');
                                    if (!titleEl.querySelector('.ri-time-line')) {
                                        titleEl.innerHTML = '<i class="ri-time-line me-1" style="font-size:.8rem;vertical-align:middle;"></i>' + titleEl.innerHTML;
                                    }
                                }
                                const timeEl = info.el.querySelector('.fc-event-time');
                                if (timeEl) timeEl.style.setProperty('color', color, 'important');
                            } else {
                                info.el.style.setProperty('background-color', color, 'important');
                                info.el.style.setProperty('border-color', color, 'important');
                                info.el.style.setProperty('color', '#fff', 'important');
                            }
                        },
                        eventClick: function(info) {
                            abrirModalSesionEstudiante(info.event);
                        },
                        height: 'auto'
                    });
                    calendarioCronograma.render();
                    calendarioCronograma.addEventSource(eventos);
                } else {
                    calendarioCronograma.removeAllEventSources();
                    calendarioCronograma.addEventSource(eventos);
                }
            };

            /* ── Modal detalle sesión estudiante ──────────────────── */
            function abrirModalSesionEstudiante(event) {
                const props = event.extendedProps || {};
                const start = event.start;
                const end   = event.end;

                const fecha     = start ? start.toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : '—';
                const horaInicio = start ? start.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' }) : '—';
                const horaFin   = end   ? end.toLocaleTimeString('es-ES',   { hour: '2-digit', minute: '2-digit' }) : '—';
                const estado    = props.estado || 'Confirmado';
                const color     = props.modulo_color || event.backgroundColor || '#6366f1';

                document.getElementById('estDetColorBar').style.background  = color;
                document.getElementById('estDetModulo').textContent          = props.modulo_nombre || event.title || '—';
                document.getElementById('estDetFecha').textContent           = fecha;
                document.getElementById('estDetHora').textContent            = horaInicio + ' — ' + horaFin;
                document.getElementById('estDetDocente').textContent         = props.docente || 'Sin asignar';

                const estadoMap = {
                    'Confirmado':  { cls: 'bg-secondary', label: 'Confirmado' },
                    'Desarrollado':{ cls: 'bg-success',   label: 'Desarrollado' },
                    'Postergado':  { cls: 'bg-warning',   label: 'Postergado' },
                };
                const est = estadoMap[estado] || estadoMap['Confirmado'];
                document.getElementById('estDetEstado').innerHTML = '<span class="badge ' + est.cls + '">' + est.label + '</span>';

                const repInfo = document.getElementById('estDetReprogramadoInfo');
                const repMsg  = document.getElementById('estDetReprogramadoMsg');
                repInfo.style.display = 'none';
                if (props.reprogramado_a_fecha) {
                    repMsg.innerHTML = '<i class="ri-arrow-right-line me-1"></i> Esta sesión fue postergada al <strong>' + props.reprogramado_a_fecha + '</strong>';
                    repInfo.classList.remove('alert-success'); repInfo.classList.add('alert-info');
                    repInfo.style.display = 'block';
                } else if (props.reprogramado_de_fecha) {
                    repMsg.innerHTML = '<i class="ri-history-line me-1"></i> Sesión reprogramada de la fecha <strong>' + props.reprogramado_de_fecha + '</strong>';
                    repInfo.classList.remove('alert-info'); repInfo.classList.add('alert-success');
                    repInfo.style.display = 'block';
                }

                const enlaceWrap    = document.getElementById('estDetEnlaceWrap');
                const grabacionWrap = document.getElementById('estDetGrabacionWrap');
                const sinEnlaces    = document.getElementById('estDetSinEnlaces');
                const enlaceUrl     = props.enlace_videollamada_url    || '';
                const enlaceNombre  = props.enlace_videollamada_nombre || '';
                const grabUrl       = props.enlace_grabacion           || '';

                enlaceWrap.style.display    = 'none';
                grabacionWrap.style.display = 'none';
                sinEnlaces.style.display    = 'none';

                if (estado === 'Desarrollado' && grabUrl) {
                    grabacionWrap.style.display = 'block';
                    document.getElementById('estDetGrabacionBtn').onclick = function() {
                        const url = /^https?:\/\//i.test(grabUrl) ? grabUrl : 'https://' + grabUrl;
                        window.open(url, '_blank', 'noopener,noreferrer');
                    };
                } else if (estado === 'Confirmado' && enlaceUrl) {
                    enlaceWrap.style.display = 'block';
                    document.getElementById('estDetEnlaceNombre').textContent = enlaceNombre || 'Sesión virtual';
                    document.getElementById('estDetEnlaceBtn').onclick = function() {
                        const url = /^https?:\/\//i.test(enlaceUrl) ? enlaceUrl : 'https://' + enlaceUrl;
                        window.open(url, '_blank', 'noopener,noreferrer');
                    };
                } else {
                    sinEnlaces.style.display = 'flex';
                    const sinDivs = sinEnlaces.querySelectorAll('div');
                    if (estado === 'Postergado') {
                        sinDivs[0].textContent = 'Sesión postergada';
                        sinDivs[1].textContent = 'Esta sesión ha sido postergada a una nueva fecha.';
                    } else if (estado === 'Desarrollado') {
                        sinDivs[0].textContent = 'Sesión concluida';
                        sinDivs[1].textContent = 'La grabación estará disponible cuando el docente la comparta.';
                    } else {
                        sinDivs[0].textContent = 'Sesión confirmada';
                        sinDivs[1].innerHTML   = 'Los enlaces de sesión virtual o grabación<br>aparecerán aquí cuando estén disponibles.';
                    }
                }

                const modalEl = document.getElementById('modalDetalleSesionEst');
                const existingModal = bootstrap.Modal.getInstance(modalEl);
                if (existingModal) existingModal.show();
                else new bootstrap.Modal(modalEl).show();
            }
            window.abrirModalSesionEstudiante = abrirModalSesionEstudiante;

            /* ── Oferta sub-tabs (contable) ────────────────────────── */
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.est-oferta-tab-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const targetId = this.getAttribute('data-target');
                        const parentTab = this.closest('.est-tabs-body');
                        if (!parentTab || !targetId) return;
                        parentTab.querySelectorAll('.est-oferta-tab-btn').forEach(b => b
                            .classList.remove('active'));
                        this.classList.add('active');
                        parentTab.querySelectorAll('.est-oferta-content').forEach(c => c
                            .classList.remove('active'));
                        const el = document.getElementById(targetId);
                        if (el) el.classList.add('active');
                    });
                });
                
                // Pagos tabs (nuevo)
                document.querySelectorAll('.pagos-tab-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const targetId = this.getAttribute('data-target');
                        const parentWrapper = this.closest('.pagos-tabs-wrapper');
                        if (!parentWrapper || !targetId) return;
                        parentWrapper.querySelectorAll('.pagos-tab-btn').forEach(b => b
                            .classList.remove('active'));
                        this.classList.add('active');
                        parentWrapper.querySelectorAll('.pagos-oferta-content').forEach(c => c
                            .classList.remove('active'));
                        const el = document.getElementById(targetId);
                        if (el) el.classList.add('active');
                    });
                });
            });

            /* ── Ver detalle pago (contable) ───────────────────────── */
            document.querySelectorAll('.btn-ver-detalle-pago').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const pagosData = JSON.parse(this.getAttribute('data-pagos'));
                    const listaPagos = document.getElementById('lista-pagos');
                    const container = document.getElementById('detalle-pago-container');

                    if (pagosData.length === 1) {
                        listaPagos.style.display = 'none';
                        container.style.display = 'block';
                        mostrarDetallePago(pagosData[0]);
                    } else {
                        listaPagos.style.display = 'block';
                        container.style.display = 'none';
                        listaPagos.innerHTML = '';
                        listaPagos.style.padding = '0';
                        var headerHtml = '<div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:#f8fafc;border-bottom:2px solid #e2e8f0;border-radius:12px 12px 0 0;">' +
                            '<span style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;"><i class="ri-receipt-line me-1"></i> Selecciona un recibo</span>' +
                            '<span style="font-size:.72rem;color:#94a3b8;">' + pagosData.length + ' pago(s)</span>' +
                            '</div>';
                        listaPagos.innerHTML = headerHtml;
                        pagosData.forEach(function(pago) {
                            const item = document.createElement('div');
                            item.style.cssText = 'display:flex;align-items:center;gap:14px;padding:14px 16px;cursor:pointer;border-left:3px solid #fc7b04;margin:4px 0;border-radius:10px;background:#fff;border:1px solid #e2e8f0;transition:all .2s;';
                            item.onmouseover = function() { this.style.background = '#fef3c7'; this.style.borderColor = '#fc7b04'; };
                            item.onmouseout = function() { this.style.background = '#fff'; this.style.borderColor = '#e2e8f0'; };
                            var metodoColor = pago.metodo === 'Efectivo' ? '#2563eb' : pago.metodo === 'Qr' ? '#059669' : pago.metodo === 'Transferencia' ? '#4f46e5' : '#64748b';
                            var metodoIcon = pago.metodo === 'Efectivo' ? 'ri-cash-line' : pago.metodo === 'Qr' ? 'ri-qr-code-line' : pago.metodo === 'Transferencia' ? 'ri-bank-line' : 'ri-payment-line';
                            item.innerHTML =
                                '<div style="width:36px;height:36px;border-radius:10px;background:rgba(252,123,4,.1);color:#fc7b04;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1rem;"><i class="ri-receipt-line"></i></div>' +
                                '<div style="flex:1;min-width:0;">' +
                                '<div style="font-weight:700;font-size:.88rem;color:#1e293b;">' + (pago.recibo || '—') + '</div>' +
                                '<div style="font-size:.72rem;color:#64748b;margin-top:2px;"><i class="ri-calendar-line me-1"></i>' + formatDateEs(pago.fecha) + ' <span style="color:#cbd5e1;">·</span> <i class="' + metodoIcon + '" style="color:' + metodoColor + ';margin-right:3px;"></i>' + (pago.metodo || '—') + '</div>' +
                                '</div>' +
                                '<div style="text-align:right;flex-shrink:0;">' +
                                '<div style="font-weight:700;font-size:.9rem;color:#059669;">Bs. ' + parseFloat(pago.monto).toFixed(2) + '</div>' +
                                '<div style="font-size:.7rem;color:#94a3b8;margin-top:1px;"><i class="ri-arrow-right-s-line"></i> Ver detalle</div>' +
                                '</div>';
                            item.addEventListener('click', function() {
                                listaPagos.style.display = 'none';
                                container.style.display = 'block';
                                mostrarDetallePago(pago);
                            });
                            listaPagos.appendChild(item);
                        });
                        const totalGeneral = pagosData.reduce((s, p) => s + parseFloat(p.monto), 0);
                        const totalItem = document.createElement('div');
                        totalItem.style.cssText = 'display:flex;align-items:center;justify-content:space-between;padding:14px 16px;margin-top:6px;border-radius:10px;background:linear-gradient(135deg,#fc7b04,#c96004);color:#fff;';
                        totalItem.innerHTML =
                            '<div style="display:flex;align-items:center;gap:8px;"><i class="ri-check-double-line" style="font-size:1.1rem;"></i><span style="font-weight:600;font-size:.85rem;">Total Acumulado</span></div>' +
                            '<div style="font-weight:800;font-size:1rem;">Bs. ' +
                            totalGeneral.toFixed(2) + '</div>';
                        listaPagos.appendChild(totalItem);
                        var footerNote = document.createElement('div');
                        footerNote.style.cssText = 'text-align:center;padding:10px;font-size:.72rem;color:#94a3b8;';
                        footerNote.innerHTML = '<i class="ri-information-line me-1"></i> Selecciona un recibo para ver su detalle completo';
                        listaPagos.appendChild(footerNote);
                    }

                    const modalEl = document.getElementById('modalVerDetallePago');
                    modalEl.classList.add('show');
                    modalEl.style.display = 'block';
                    document.body.classList.add('modal-open');
                    const backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);
                    setTimeout(function() { backdrop.classList.add('show'); }, 10);
                });
            });

            function closePagoModal() {
                const modalEl = document.getElementById('modalVerDetallePago');
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                document.body.classList.remove('modal-open');
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(function(b) { b.remove(); });
            }

            function verFactura(url, recibo, estudiante, monto, oferta) {
                document.getElementById('facturaReciboNum').textContent = recibo;
                document.getElementById('facturaEstudiante').textContent = estudiante;
                document.getElementById('facturaOferta').textContent = oferta;
                document.getElementById('facturaMonto').textContent = 'Bs. ' + monto;
                document.getElementById('facturaDownloadLink').href = url;

                var container = document.getElementById('facturaFileContainer');
                var ext = url.split('.').pop().toLowerCase();
                if (ext === 'pdf') {
                    container.innerHTML = '<iframe src="' + url + '" style="width:100%;height:500px;border:none;border-radius:8px;"></iframe>';
                } else {
                    container.innerHTML = '<img src="' + url + '" style="max-width:100%;max-height:500px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">';
                }

                new bootstrap.Modal(document.getElementById('modalVerFactura')).show();
            }

            document.getElementById('btn-volver-lista')?.addEventListener('click', function() {
                document.getElementById('lista-pagos').style.display = 'block';
                document.getElementById('detalle-pago-container').style.display = 'none';
                closePagoModal();
            });

            document.getElementById('modalVerDetallePago').querySelectorAll('[data-bs-dismiss="modal"], .btn-close').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    closePagoModal();
                });
            });

            document.getElementById('modalVerDetallePago').querySelector('.btn-cerrar')?.addEventListener('click', function() {
                closePagoModal();
            });

            function mostrarDetallePago(pago) {
                document.getElementById('detalle-recibo').textContent = pago.recibo || '—';
                document.getElementById('detalle-fecha').textContent = formatDateEs(pago.fecha);
                document.getElementById('detalle-metodo').textContent = pago.metodo || '—';
                document.getElementById('detalle-estudiante').textContent = pago.estudiante || '—';
                document.getElementById('detalle-programa').textContent = pago.programa || '—';
                document.getElementById('detalle-plan').textContent = pago.plan || '—';

                const tbody = document.getElementById('detalle-tabla');
                tbody.innerHTML = '';
                let totalDetalle = 0;
                if (pago.cuotas && pago.cuotas.length > 0) {
                    pago.cuotas.forEach(function(c, i) {
                        totalDetalle += parseFloat(c.monto);
                        const tr = document.createElement('tr');
                        tr.innerHTML = '<td>' + (i + 1) + '</td><td>' + (c.nombre || 'Cuota #' + (c.n_cuota ||
                                i + 1)) + '</td><td class="text-end">Bs. ' + parseFloat(c.monto).toFixed(2) +
                            '</td>';
                        tbody.appendChild(tr);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center">Sin cuotas</td></tr>';
                }
                document.getElementById('detalle-total').textContent = 'Bs. ' + totalDetalle.toFixed(2);

                const descContainer = document.getElementById('detalle-descuento-container');
                if (pago.descuento && parseFloat(pago.descuento) > 0) {
                    descContainer.style.display = 'block';
                    document.getElementById('detalle-descuento').textContent = 'Bs. ' + parseFloat(pago.descuento)
                        .toFixed(2);
                } else {
                    descContainer.style.display = 'none';
                }

                const facturaContainer = document.getElementById('detalle-factura-container');
                const facturaEstado = document.getElementById('detalle-factura-estado');
                const btnVerFactura = document.getElementById('btn-ver-factura-detalle');
                if (pago.documento_factura) {
                    facturaContainer.style.display = 'block';
                    facturaEstado.textContent = 'Con factura';
                    btnVerFactura.onclick = function() {
                        verFactura(pago.documento_factura, pago.recibo, pago.estudiante, pago.monto, pago.programa);
                    };
                } else {
                    facturaContainer.style.display = 'none';
                }

                document.getElementById('detalle-trabajador').textContent = pago.trabajador || '—';
                document.getElementById('detalle-depositante').textContent = pago.estudiante || '—';

                const btnPdf = document.getElementById('btn-descargar-pdf');
                const footerBtns = btnPdf ? btnPdf.parentNode : null;
                if (footerBtns) {
                    const existingComprobanteBtn = footerBtns.querySelector('.btn-comprobante');
                    if (existingComprobanteBtn) existingComprobanteBtn.remove();

                    if (pago.comprobante) {
                        const btnComprobante = document.createElement('a');
                        btnComprobante.href = pago.comprobante.url;
                        btnComprobante.target = '_blank';
                        btnComprobante.className = 'btn text-white btn-comprobante me-2';
                        btnComprobante.style.background = '#059669';
                        btnComprobante.innerHTML = '<i class="ri-file-image-line"></i> Ver Comprobante';
                        footerBtns.insertBefore(btnComprobante, btnPdf);
                    }
                }

                if (btnPdf && pago && pago.id) {
                    btnPdf.href = '/virtual/recibo/' + pago.id + '/pdf';
                }
            }

            /* ── Moodle SSO helper ─────────────────────────────────── */
            function openMoodleSso(targetUrl) {
                window.open('/virtual/moodle-sso?target=' + encodeURIComponent(targetUrl), '_blank');
            }

            /* ── Actividades (tab académico) ───────────────────────── */
            // Recargar el panel de actividades de un módulo (usado tras entregar/subir archivo)
            window.recargarActividadesModulo = function(moduloId) {
                $.get('/virtual/actividades/' + moduloId)
                    .done(function(r) {
                        if (!r.success) return;
                        renderActividades(moduloId, r.contenidos, r.calificaciones, r.entregas || {}, r.archivos_subidos || {}, r.foros_participacion || {}, r.tareas_fechas || {}, r.cuestionarios || [], r.foros || [], r.tareas || [], r.urls_by_cmid || {});
                        loaded[moduloId] = true;
                    });
            };

            $(document).on('click', '.btn-ver-actividades', function() {
                const moduloId = $(this).data('modulo');
                const panelId  = $(this).data('panel');
                const $btn     = $(this);
                const $card    = $('#card-mod-' + moduloId);
                const $panel   = $('#' + panelId);
                const $oferta  = $card.closest('.est-oferta-content');
                const $panels  = $oferta.find('.acad-mod-panels-wrap .est-act-panel');
                const $cards   = $oferta.find('.acad-mod-card');

                const yaAbierto = $panel.hasClass('is-open');

                // Cerrar otros paneles y limpiar highlights del programa actual
                $panels.not($panel).removeClass('is-open').hide();
                $cards.not($card).removeClass('is-activity-open')
                    .find('.btn-ver-actividades').html('<i class="ri-eye-line"></i> Actividades');

                if (yaAbierto) {
                    $panel.removeClass('is-open').slideUp(200);
                    $card.removeClass('is-activity-open');
                    $btn.html('<i class="ri-eye-line"></i> Actividades');
                    return;
                }

                $card.addClass('is-activity-open');
                $panel.addClass('is-open').css('display','none').slideDown(220, function() {
                    const top = $panel.offset().top - 80;
                    $('html, body').animate({ scrollTop: top }, 280);
                });
                $btn.html('<i class="ri-eye-off-line"></i> Ocultar');
                if (loaded[moduloId]) return;

                $.get('/virtual/actividades/' + moduloId)
                    .done(function(r) {
                        $('#spinner-mod-' + moduloId).hide();
                        if (!r.success) {
                            $('#contenido-mod-' + moduloId).html(
                                '<p style="color:#dc3545;font-size:.85rem;"><i class="ri-close-circle-line"></i> ' +
                                escHtml(r.message) + '</p>');
                            return;
                        }
                        console.log('[Moodle actividades] módulo=' + moduloId, r.contenidos);
                        renderActividades(moduloId, r.contenidos, r.calificaciones, r.entregas || {}, r.archivos_subidos || {}, r.foros_participacion || {}, r.tareas_fechas || {}, r.cuestionarios || [], r.foros || [], r.tareas || [], r.urls_by_cmid || {});
                        loaded[moduloId] = true;
                    })
                    .fail(function() {
                        $('#spinner-mod-' + moduloId).hide();
                        $('#contenido-mod-' + moduloId).html(
                            '<p style="color:#dc3545;font-size:.85rem;"><i class="ri-wifi-off-line"></i> Error al conectar con Moodle.</p>'
                            );
                    });
            });

            function renderActividades(moduloId, contenidos, calificaciones, entregas, archivosSubidos, forosParticipacion, tareasFechas, cuestionarios, forosData, tareasData, urlsByCmid) {
                const gradeMap = {};
                if (calificaciones && Array.isArray(calificaciones)) {
                    calificaciones.forEach(function(item) {
                        if (item.cmid) gradeMap[item.cmid] = item;
                    });
                }
                const entregaMap = entregas || {};
                const archivosMap = archivosSubidos || {};

                // Mapas de fechas por instance id y cmid — igual que admin
                const tareasFechasMap = tareasFechas || {};
                const quizzesMap = {}, quizzesByCmid = {};
                (cuestionarios || []).forEach(function(q) {
                    if (q.id)           quizzesMap[q.id]              = q;
                    if (q.cmid)         quizzesByCmid[q.cmid]         = q;
                    if (q.coursemodule) quizzesByCmid[q.coursemodule] = q;
                });
                const forosMap = {}, forosByCmid = {};
                (forosData || []).forEach(function(f) {
                    if (f.id)   forosMap[f.id]   = f;
                    if (f.cmid) forosByCmid[f.cmid] = f;
                });
                const tareasMap = {}, tareasByCmid = {};
                (tareasData || []).forEach(function(t) {
                    if (t.id)            tareasMap[t.id]              = t;
                    if (t.cmid)          tareasByCmid[t.cmid]          = t;
                    if (t.coursemodule)  tareasByCmid[t.coursemodule]  = t;
                });
                let html = '';
                if (!contenidos || contenidos.length === 0) {
                    html =
                        '<p style="color:#6c757d;font-size:.85rem;"><i class="ri-information-line"></i> No hay contenido disponible.</p>';
                } else {
                    contenidos.forEach(function(seccion) {
                        const modulos = seccion.modules || [];
                        if (modulos.length === 0) return;
                        
                        // Mostrar descripción de la sección si existe
                        if (seccion.description) {
                            html += '<div class="est-seccion-descripcion">' + seccion.description + '</div>';
                        }
                        
                        html += '<div class="est-act-section">' + escHtml(seccion.name || 'Sección') + '</div>';
                        modulos.forEach(function(mod) {
                            // Mostrar contenido de labels
                            if (mod.modname === 'label') {
                                if (mod.description) html += '<div class="est-label-content">' + mod.description + '</div>';
                                return;
                            }
                            
                            
                            const grade = gradeMap[mod.id];
                            const nota = grade ? grade.gradeformatted : null;
                            const notaMax = grade && grade.max ? grade.max : null;
                            const participoForo = mod.modname === 'forum' && forosParticipacion[mod.id];
                            const enviado = mod.modname === 'assign' && entregaMap[mod.id];
                            const icono = iconoModulo(mod.modname);
                            let badge = '';
                            if (nota && nota !== '-') {
                                const numVal = parseFloat(nota);
                                const maxStr = notaMax ? ' / ' + notaMax : '';
                                if (!isNaN(numVal)) {
                                    badge = numVal >= 51 ?
                                        '<span class="est-nota-badge aprobado">' + escHtml(nota) + maxStr +
                                        '</span>' :
                                        '<span class="est-nota-badge reprobado">' + escHtml(nota) + maxStr +
                                        '</span>';
                                } else {
                                    badge = '<span class="est-nota-badge pendiente">' + escHtml(nota) + maxStr +
                                        '</span>';
                                }
                            } else if (enviado) {
                                badge = '<span class="est-nota-badge" style="background:#e6f7e6;color:#16a34a;">Enviado</span>';
                            } else if (participoForo) {
                                badge = '<span class="est-nota-badge" style="background:#e6f7e6;color:#16a34a;">Realizado</span>';
                            } else {
                                badge = '<span class="est-nota-badge pendiente">Pendiente</span>';
                            }
                            const url = (function() {
                                if (mod.modname === 'url') {
                                    var eu = (urlsByCmid || {})[mod.id] || mod.externalurl || '';
                                    if (eu && !/^https?:\/\//i.test(eu)) eu = 'https://' + eu;
                                    if (eu) return '<a href="' + escHtml(eu) + '" target="_blank" rel="noopener noreferrer" style="font-size:.72rem;color:#10b981;margin-left:.5rem;" title="Abrir enlace"><i class="ri-external-link-line"></i></a>';
                                }
                                return mod.url
                                    ? '<a href="#" class="moodle-link" data-target="' + escHtml(mod.url) + '" style="font-size:.72rem;color:#fc7b04;margin-left:.5rem;"><i class="ri-external-link-line"></i></a>'
                                    : '';
                            })();
                            // Fechas de actividad — misma lógica que modulo-detalle admin
                            let fechasHtml = '';
                            const now = Math.floor(Date.now() / 1000);
                            const fmtTs = function(ts) {
                                if (!ts || ts === 0) return null;
                                return new Date(ts * 1000).toLocaleString('es-BO', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' });
                            };

                            let tsInicio = null, tsFin = null;

                            if (mod.modname === 'assign') {
                                // Fuente primaria: tareasFechasMap (igual que admin)
                                const tfEntry = tareasFechasMap[mod.instance] || tareasFechasMap['cm_' + mod.id] || {};
                                tsInicio = tfEntry.open || null;
                                tsFin    = tfEntry.due  || null;
                                // Fallback: activity_dates
                                if (!tsInicio && !tsFin) {
                                    const ad = mod.activity_dates;
                                    if (ad) { tsInicio = ad.open || null; tsFin = ad.due || ad.close || null; }
                                }
                            } else if (mod.modname === 'quiz') {
                                // Fuente primaria: quizzesMap
                                const quiz = quizzesMap[mod.instance] || quizzesByCmid[mod.id];
                                if (quiz) {
                                    tsInicio = quiz.timeopen  || null;
                                    tsFin    = quiz.timeclose || null;
                                }
                                // Fallback: activity_dates
                                if (!tsInicio && !tsFin) {
                                    const ad = mod.activity_dates;
                                    if (ad) { tsInicio = ad.open || null; tsFin = ad.close || null; }
                                }
                            } else if (mod.modname === 'forum') {
                                // Fuente primaria: forosMap
                                const foro = forosMap[mod.instance] || forosByCmid[mod.id];
                                if (foro) {
                                    tsInicio = foro.timeopen || null;
                                    tsFin    = foro.timeclose || foro.cutoffdate || foro.duedate || null;
                                }
                                // Fallback: activity_dates
                                if (!tsInicio && !tsFin) {
                                    const ad = mod.activity_dates;
                                    if (ad) { tsInicio = ad.open || null; tsFin = ad.close || ad.due || null; }
                                }
                            }

                            // Fallback final: mod.dates[]
                            if (!tsInicio && !tsFin && Array.isArray(mod.dates) && mod.dates.length) {
                                mod.dates.forEach(function(entry) {
                                    const lbl = (entry.label || '').toLowerCase();
                                    const ts  = entry.timestamp || 0;
                                    if (!ts) return;
                                    if (lbl.includes('open') || lbl.includes('abre') || lbl.includes('inicio') || lbl.includes('desde')) {
                                        if (!tsInicio) tsInicio = ts;
                                    } else if (lbl.includes('due') || lbl.includes('close') || lbl.includes('cutoff') || lbl.includes('entrega') || lbl.includes('cierre') || lbl.includes('vencimiento')) {
                                        if (!tsFin) tsFin = ts;
                                    } else {
                                        if (!tsFin) tsFin = ts;
                                        else if (!tsInicio) tsInicio = ts;
                                    }
                                });
                            }

                            // Estado de ventana de tiempo
                            const noAbierto     = tsInicio && tsInicio > now;
                            const vencidoGlobal = tsFin && tsFin < now;
                            const dentroDeFecha = !noAbierto && !vencidoGlobal;

                            if (mod.modname === 'assign') {
                                const strOpen = fmtTs(tsInicio);
                                const strDue  = fmtTs(tsFin);
                                const abierto = tsInicio && tsInicio <= now;
                                const overdue = tsFin && tsFin < now;
                                let chips = '';
                                if (strOpen) chips += '<span class="act-date-chip act-date-open' + (abierto ? ' act-date-active' : '') + '"><i class="ri-calendar-event-line"></i> Inicio: '   + strOpen + '</span>';
                                if (strDue)  chips += '<span class="act-date-chip act-date-due'  + (overdue ? ' act-date-overdue' : '') + '"><i class="ri-calendar-check-line"></i> Entrega: ' + strDue  + '</span>';
                                if (chips)   fechasHtml = '<div class="act-dates-row">' + chips + '</div>';
                            } else if (mod.modname === 'quiz') {
                                const strOpen  = fmtTs(tsInicio);
                                const strClose = fmtTs(tsFin);
                                const abiertoQ = tsInicio && tsInicio <= now;
                                const vencidoQ = tsFin && tsFin < now;
                                let chips = '';
                                if (strOpen)  chips += '<span class="act-date-chip act-date-open' + (abiertoQ ? ' act-date-active' : '') + '"><i class="ri-calendar-event-line"></i> Inicio: ' + strOpen  + '</span>';
                                if (strClose) chips += '<span class="act-date-chip act-date-due'  + (vencidoQ ? ' act-date-overdue' : '') + '"><i class="ri-calendar-check-line"></i> Cierre: ' + strClose + '</span>';
                                if (chips)    fechasHtml = '<div class="act-dates-row">' + chips + '</div>';
                            } else if (mod.modname === 'forum') {
                                const strOpen = fmtTs(tsInicio);
                                const strVenc = fmtTs(tsFin);
                                const abiertoF = tsInicio && tsInicio <= now;
                                const overF    = tsFin && tsFin < now;
                                let chips = '';
                                if (strOpen && strVenc && strOpen !== strVenc) {
                                    chips += '<span class="act-date-chip act-date-open' + (abiertoF ? ' act-date-active' : '') + '"><i class="ri-calendar-event-line"></i> Inicio: ' + strOpen + '</span>';
                                    chips += '<span class="act-date-chip act-date-due'  + (overF    ? ' act-date-overdue' : '') + '"><i class="ri-calendar-check-line"></i> Vencimiento: ' + strVenc + '</span>';
                                } else {
                                    const sola = strVenc || strOpen;
                                    const ts   = tsFin   || tsInicio;
                                    const past = ts && ts < now;
                                    if (sola) chips = '<span class="act-date-chip act-date-open' + (past ? ' act-date-active' : '') + '"><i class="ri-calendar-event-line"></i> Vencimiento: ' + sola + '</span>';
                                }
                                if (chips) fechasHtml = '<div class="act-dates-row">' + chips + '</div>';
                            }

                            // Descripción inline + archivo adjunto (como admin)
                            let descInline = '';
                            if (mod.modname === 'assign' || mod.modname === 'forum') {
                                if (mod.description && String(mod.description).trim() !== '') {
                                    descInline += '<div style="margin:4px 0 0 0;padding:0.4rem 0;font-size:0.82rem;color:#374151;line-height:1.5;">' + mod.description + '</div>';
                                }
                                // Download link for assign/forum
                                let hasFile = false;
                                let adjUrl = '';
                                if (mod.modname === 'assign') {
                                    const tInfo = tareasMap[mod.instance] || tareasByCmid[mod.id];
                                    hasFile = mod.has_intro_file === true || (tInfo && (
                                        (Array.isArray(tInfo.introfiles) && tInfo.introfiles.length > 0) ||
                                        parseInt(tInfo.introattachments || 0) > 0
                                    ));
                                    adjUrl = '/virtual/modulo/' + moduloId + '/actividad/tarea/' + mod.id + '/adjunto';
                                } else {
                                    const fInfo = forosMap[mod.instance] || forosByCmid[mod.id];
                                    hasFile = mod.has_intro_file === true || (fInfo && Array.isArray(fInfo.introfiles) && fInfo.introfiles.length > 0);
                                    adjUrl = '/virtual/modulo/' + moduloId + '/actividad/foro/' + mod.id + '/adjunto';
                                }
                                if (hasFile) {
                                    descInline += '<div style="margin-top:6px;"><a href="' + adjUrl + '" target="_blank" style="display:inline-flex;align-items:center;gap:0.3rem;padding:0.25rem 0.65rem;font-size:0.75rem;font-weight:600;border-radius:5px;background:rgba(37,99,235,.1);color:#2563eb;text-decoration:none;cursor:pointer;"><i class="ri-download-2-line"></i> Descargar archivo</a></div>';
                                }
                            }

                            // Botones de material/recurso (no dependen de fechas)
                            let btnMaterial = '';
                            if (mod.modname === 'resource') {
                                const recursoUrl = '/virtual/modulo/' + moduloId + '/actividad/recurso/' + mod.id;
                                btnMaterial =
                                    '<a href="' + recursoUrl + '" target="_blank" rel="noopener noreferrer"' +
                                    ' style="margin-left:auto;background:#0ea5e9;color:#fff;border:none;border-radius:6px;padding:.3rem .7rem;font-size:.72rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;white-space:nowrap;">' +
                                    '<i class="ri-eye-line"></i> Visualizar</a>' +
                                    '<a href="' + recursoUrl + '?download=1"' +
                                    ' style="background:#16a34a;color:#fff;border:none;border-radius:6px;padding:.3rem .7rem;font-size:.72rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;white-space:nowrap;">' +
                                    '<i class="ri-download-2-line"></i> Descargar</a>';
                            } else if (mod.modname === 'url') {
                                let rawUrl = (urlsByCmid || {})[mod.id] || mod.externalurl || '';
                                if (rawUrl && !/^https?:\/\//i.test(rawUrl)) rawUrl = 'https://' + rawUrl;
                                if (rawUrl) {
                                    btnMaterial =
                                        '<a href="' + escHtml(rawUrl) + '" target="_blank" rel="noopener noreferrer"' +
                                        ' style="margin-left:auto;background:#10b981;color:#fff;border:none;border-radius:6px;padding:.3rem .75rem;font-size:.72rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;white-space:nowrap;">' +
                                        '<i class="ri-external-link-line"></i> Abrir enlace</a>';
                                }
                            }

                            let btnRealizar = '';
                            if (['assign', 'quiz', 'forum'].includes(mod.modname) && dentroDeFecha) {
                                const iconBtn = mod.modname === 'assign' ? 'ri-upload-2-line'
                                    : mod.modname === 'forum' ? 'ri-discuss-line'
                                    : 'ri-play-circle-line';
                                const labelBtn = (mod.modname === 'assign' && enviado) ? 'Modificar'
                                    : mod.modname === 'assign' ? 'Entregar'
                                    : mod.modname === 'forum' ? 'Participar'
                                    : 'Ver quiz';
                                btnRealizar = '<button class="btn-est-realizar"' +
                                    ' data-cmid="' + mod.id + '"' +
                                    ' data-modname="' + mod.modname + '"' +
                                    ' data-moduloid="' + moduloId + '"' +
                                    ' data-name="' + escHtml(mod.name) + '"' +
                                    ' style="margin-left:auto;background:#fc7b04;color:#fff;border:none;border-radius:6px;padding:.3rem .75rem;font-size:.75rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:.3rem;white-space:nowrap;">' +
                                    '<i class="' + iconBtn + '"></i> ' + labelBtn + '</button>';
                            }

                            // Info extra para actividades vencidas (nota, archivos)
                            let infoVencidaHtml = '';
                            if (vencidoGlobal) {
                                const gItem = gradeMap[mod.id];
                                let gNota = gItem ? (gItem.gradeformatted || null) : null;
                                if (!gNota && gItem && gItem.grades) {
                                    const vals = Object.values(gItem.grades);
                                    if (vals.length > 0 && vals[0] !== null) gNota = String(vals[0]);
                                }
                                if (gNota && gNota !== '-') {
                                    const gMax = gItem && gItem.max ? ' / ' + gItem.max : '';
                                    infoVencidaHtml += '<div style="display:flex;align-items:center;gap:.4rem;padding:.2rem 0;font-size:.75rem;color:#16a34a;font-weight:600;">' +
                                        '<i class="ri-award-line"></i> ' + escHtml(gNota) + gMax + '</div>';
                                } else if (mod.modname === 'assign' && enviado) {
                                    infoVencidaHtml += '<div style="display:flex;align-items:center;gap:.4rem;padding:.2rem 0;font-size:.72rem;color:#6c757d;">' +
                                        '<i class="ri-hourglass-line"></i> Sin calificación registrada</div>';
                                }
                                // Archivos adjuntos de tarea
                                if (mod.modname === 'assign') {
                                    const archivos = archivosMap[mod.id];
                                    if (archivos && archivos.length > 0) {
                                        archivos.forEach(function(f) {
                                            var dlUrl = '/virtual/modulo/' + moduloId + '/actividad/tarea/' + mod.id + '/archivo/' + encodeURIComponent(f.filename);
                                            infoVencidaHtml += '<div style="display:flex;align-items:center;gap:.3rem;padding:.15rem 0;font-size:.72rem;">' +
                                                '<a href="' + dlUrl + '" style="color:#3b82f6;text-decoration:none;display:flex;align-items:center;gap:.25rem;" download><i class="ri-download-2-line"></i> ' + escHtml(f.filename) + '</a></div>';
                                        });
                                    }
                                }
                            }

                            html += '<div class="est-act-item">' +
                                '<div class="est-act-item-name">' +
                                    icono + ' ' + escHtml(mod.name) + url +
                                    '<small>' + etiquetaModulo(mod.modname) + '</small>' +
                                    (descInline ? '<div class="est-act-item-desc">' + descInline + '</div>' : '') +
                                '</div>' +
                                (fechasHtml ? '<div class="est-act-item-dates">' + fechasHtml + '</div>' : '<div class="est-act-item-dates"></div>') +
                                '<div class="est-act-item-actions">' + badge + btnMaterial + btnRealizar + '</div>' +
                                (infoVencidaHtml ? '<div class="est-act-item-dates" style="padding-top:.25rem;">' + infoVencidaHtml + '</div>' : '') +
                                '</div>';
                        });
                    });
                }
                $('#contenido-mod-' + moduloId).html(html);
            }

            function iconoModulo(modname) {
                const map = {
                    assign: '<i class="ri-file-text-line" style="color:#3b82f6;"></i>',
                    quiz: '<i class="ri-question-line" style="color:#f97316;"></i>',
                    forum: '<i class="ri-discuss-line" style="color:#8b5cf6;"></i>',
                    resource: '<i class="ri-file-line" style="color:#6c757d;"></i>',
                    url: '<i class="ri-links-line" style="color:#10b981;"></i>',
                    page: '<i class="ri-pages-line" style="color:#6366f1;"></i>',
                    label: '<i class="ri-text" style="color:#0ea5e9;"></i>'
                };
                return map[modname] || '<i class="ri-apps-line" style="color:#6c757d;"></i>';
            }

            function etiquetaModulo(modname) {
                const map = {
                    assign: 'Tarea',
                    quiz: 'Cuestionario',
                    forum: 'Foro',
                    resource: 'Archivo',
                    url: 'Enlace',
                    page: 'Página',
                    label: 'Área de texto y medios'
                };
                return map[modname] || modname;
            }

            function escHtml(str) {
                return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(
                    /"/g, '&quot;');
            }

            $(document).on('click', '.moodle-link', function(e) {
                e.preventDefault();
                openMoodleSso($(this).data('target'));
            });

            /* ── Abrir modal actividad (delegación) ──────────────────────── */
            $(document).on('click', '.btn-est-realizar', function() {
                abrirModalAct(
                    $(this).data('cmid'),
                    $(this).data('modname'),
                    $(this).data('moduloid'),
                    $(this).data('name')
                );
            });

        /* ══════════════════════════════════════════════
           TAB PAGOS — funciones globales
        ══════════════════════════════════════════════ */
        var estCompInscripcionId = null;

        // Event listener para el file input del comprobante
        document.getElementById('estCompArchivo')?.addEventListener('change', function(e) {
            var file = e.target.files[0];
            var area = document.getElementById('comprobanteFileArea');
            if (file) {
                area.classList.add('has-file');
                area.innerHTML = '<i class="ri-file-check-line"></i><span>' + file.name + '</span><small>Listo para subir</small>';
            } else {
                area.classList.remove('has-file');
                area.innerHTML = '<i class="ri-upload-cloud-line"></i><span>Haz clic para seleccionar el archivo</span><small>JPG, PNG o PDF — máx. 5 MB</small>';
            }
        });

        function estMostrarToast(tipo, mensaje) {
            var bg    = tipo === 'success' ? '#16a34a' : tipo === 'warning' ? '#d97706' : '#dc2626';
            var icono = tipo === 'success' ? 'ri-checkbox-circle-line' : tipo === 'warning' ? 'ri-alert-line' : 'ri-close-circle-line';
            var t = document.createElement('div');
            t.style.cssText = 'background:' + bg + ';color:#fff;padding:.75rem 1.25rem;border-radius:10px;font-size:.85rem;font-weight:500;display:flex;align-items:center;gap:.6rem;box-shadow:0 8px 24px rgba(0,0,0,.18);max-width:360px;';
            t.innerHTML = '<i class="' + icono + '" style="font-size:1.1rem;flex-shrink:0;"></i><span>' + String(mensaje).replace(/</g,'&lt;') + '</span>';
            var c = document.getElementById('est-toast-container');
            if (c) c.appendChild(t);
            setTimeout(function() { t.style.opacity='0'; t.style.transition='opacity .4s'; setTimeout(function(){ t.remove(); }, 400); }, 4000);
        }

        function abrirQrModal(src) {
            var img = document.getElementById('qrLightboxImg');
            var overlay = document.getElementById('qrOverlay');
            if (img && overlay) {
                img.src = src;
                overlay.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function cerrarQrOverlay(e) {
            var overlay = document.getElementById('qrOverlay');
            if (overlay) {
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        function estAbrirModal(inscripcionId, programa, plan) {
            estCompInscripcionId = inscripcionId;
            document.getElementById('estCompPrograma').textContent = programa || '—';
            document.getElementById('estCompPlan').textContent = 'Plan: ' + (plan || '—');
            document.getElementById('estCompArchivo').value = '';
            document.getElementById('estCompObservaciones').value = '';
            
            // Reset file area visual
            var area = document.getElementById('comprobanteFileArea');
            area.classList.remove('has-file');
            area.innerHTML = '<i class="ri-upload-cloud-line"></i><span>Haz clic para seleccionar el archivo</span><small>JPG, PNG o PDF — máx. 5 MB</small>';

            var cuotasContainer = document.getElementById('estCompCuotasContainer');
            var cuotasLoading   = document.getElementById('estCompCuotasLoading');
            cuotasContainer.style.display = 'none';
            cuotasContainer.innerHTML = '';
            cuotasLoading.style.display = 'block';

            var modalEl = document.getElementById('modalEstComprobante');
            modalEl.style.display = 'block';
            modalEl.classList.add('show');
            document.body.classList.add('modal-open');
            document.body.style.overflow = 'hidden';
            if (!document.getElementById('est-modal-backdrop')) {
                var bd = document.createElement('div');
                bd.id = 'est-modal-backdrop';
                bd.className = 'modal-backdrop fade show';
                document.body.appendChild(bd);
            }

            fetch('/virtual/inscripcion/' + inscripcionId + '/cuotas', { headers: { 'Accept': 'application/json' } })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    cuotasLoading.style.display = 'none';
                    if (!data.success) {
                        cuotasContainer.innerHTML = '<p class="text-muted" style="font-size:.82rem;">No se pudieron cargar las cuotas.</p>';
                        cuotasContainer.style.display = 'block';
                        return;
                    }
                    var grupo = data.grupo;
                    var html = '<div style="border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;">'
                        + '<div style="background:#f8fafc;padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;font-weight:600;font-size:.82rem;color:#475569;"><i class="ri-bank-card-line me-1"></i>'
                        + String(grupo.plan_nombre || '').replace(/</g,'&lt;')
                        + '</div><div style="padding:.75rem;">';

                    if (!grupo.cuotas.length) {
                        html += '<p style="color:#16a34a;font-size:.82rem;margin:0;"><i class="ri-checkbox-circle-line me-1"></i>Todas las cuotas están al día.</p>';
                    } else {
                        grupo.cuotas.forEach(function(c) {
                            var eColor = c.estado === 'Pagado' ? '#16a34a' : c.estado === 'Vencido' ? '#dc2626' : '#f59e0b';
                            html += '<label style="display:flex;align-items:center;gap:.75rem;padding:.5rem .25rem;border-bottom:1px solid #f8fafc;cursor:pointer;">'
                                + '<input type="checkbox" name="est_cuotas[]" value="' + c.id + '" style="width:15px;height:15px;accent-color:#fc7b04;flex-shrink:0;">'
                                + '<div style="flex:1;">'
                                + '<div style="font-size:.83rem;font-weight:500;color:#1e293b;">' + String(c.nombre||'').replace(/</g,'&lt;') + ' #' + c.n_cuota + '</div>'
                                + '<div style="font-size:.72rem;color:#64748b;">Bs ' + c.monto_bs + ' · Pendiente Bs ' + c.pago_pendiente_bs + ' · Vence: ' + (c.fecha_vencimiento || '—') + '</div>'
                                + '</div>'
                                + '<span style="font-size:.7rem;font-weight:600;color:' + eColor + ';background:' + eColor + '1a;padding:.15rem .45rem;border-radius:4px;">' + String(c.estado||'').replace(/</g,'&lt;') + '</span>'
                                + '</label>';
                        });
                    }
                    html += '</div></div>';
                    cuotasContainer.innerHTML = html;
                    cuotasContainer.style.display = 'block';
                })
                .catch(function() {
                    cuotasLoading.style.display = 'none';
                    cuotasContainer.innerHTML = '<p class="text-muted" style="font-size:.82rem;">Error al cargar cuotas.</p>';
                    cuotasContainer.style.display = 'block';
                });
        }

        function estCerrarModal() {
            var modalEl = document.getElementById('modalEstComprobante');
            if (!modalEl) return;
            modalEl.style.display = 'none';
            modalEl.classList.remove('show');
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            var bd = document.getElementById('est-modal-backdrop');
            if (bd) bd.remove();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Cerrar modal con botones Cancelar / X
            document.querySelectorAll('#modalEstComprobante [data-bs-dismiss="modal"], #modalEstComprobante .btn-close').forEach(function(btn) {
                btn.addEventListener('click', estCerrarModal);
            });

            var btnEstEnviar = document.getElementById('btnEstEnviarComprobante');
            if (!btnEstEnviar) return;
            btnEstEnviar.addEventListener('click', function() {
                if (!estCompInscripcionId) return;

                var archivo = document.getElementById('estCompArchivo').files[0];
                if (!archivo) { estMostrarToast('error', 'Debes seleccionar un archivo.'); return; }
                if (archivo.size > 5 * 1024 * 1024) { estMostrarToast('error', 'El archivo supera el límite de 5 MB.'); return; }

                var cuotasChecked = Array.from(document.querySelectorAll('#estCompCuotasContainer input[name="est_cuotas[]"]:checked'));
                if (!cuotasChecked.length) { estMostrarToast('error', 'Selecciona al menos una cuota.'); return; }

                var formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('inscripcion_id', estCompInscripcionId);
                formData.append('archivo', archivo);
                formData.append('observaciones', document.getElementById('estCompObservaciones').value);
                cuotasChecked.forEach(function(cb) { formData.append('cuotas[]', cb.value); });

                btnEstEnviar.disabled = true;
                btnEstEnviar.innerHTML = '<i class="ri-loader-4-line me-1"></i>Enviando...';

                fetch('{{ route("virtual.comprobante.subir") }}', { method: 'POST', body: formData })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        btnEstEnviar.disabled = false;
                        btnEstEnviar.innerHTML = '<i class="ri-upload-cloud-line me-1"></i> Enviar Comprobante';
                        if (data.success) {
                            estCerrarModal();
                            estMostrarToast('success', data.mensaje || 'Comprobante enviado correctamente.');
                            setTimeout(function() { window.location.reload(); }, 1800);
                        } else {
                            estMostrarToast('error', data.message || 'Error al enviar el comprobante.');
                        }
                    })
                    .catch(function() {
                        btnEstEnviar.disabled = false;
                        btnEstEnviar.innerHTML = '<i class="ri-upload-cloud-line me-1"></i> Enviar Comprobante';
                        estMostrarToast('error', 'Error de conexión.');
                    });
            });
        });

        function cambiarPerfil(perfil) {
            // Feedback visual: spinner + deshabilitar ambos botones
            var btnEst = document.getElementById('rol-btn-estudiante');
            var btnDoc = document.getElementById('rol-btn-docente');
            var btnActivo = perfil === 'estudiante' ? btnEst : btnDoc;

            if (btnEst) btnEst.disabled = true;
            if (btnDoc) btnDoc.disabled = true;
            if (btnActivo) btnActivo.classList.add('loading');

            fetch('{{ route('virtual.cambiarPerfil') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ perfil: perfil })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(function() {
                // Restaurar si hay error de red
                if (btnEst) btnEst.disabled = false;
                if (btnDoc) btnDoc.disabled = false;
                if (btnActivo) btnActivo.classList.remove('loading');
            });
        }

        /* ── Mi Horario Docente — funciones cronograma style ───────── */

        window.cargarModulosHorarioDocente = function() {
            const ofertaId   = document.getElementById('select-oferta-horario-docente').value;
            const listaEl    = document.getElementById('modulosSidebarListHorarioDocente');
            const btnTodos   = document.getElementById('btnTodosModulosHorarioDocente');
            const badge      = document.getElementById('moduloSeleccionadoBadgeHorarioDocente');

            moduloSeleccionadoHorarioDocenteId = null;
            badge.style.display = 'none';
            btnTodos.classList.add('active');

            if (!ofertaId) {
                listaEl.innerHTML = '<div class="cronograma-sidebar-empty"><i class="ri-inbox-line"></i>Selecciona una oferta</div>';
                if (calendarioHorarioDocente) calendarioHorarioDocente.removeAllEvents();
                return;
            }

            const oferta = datosOfertasDocente.find(function(o) { return o.id == parseInt(ofertaId); });
            if (!oferta) return;

            listaEl.innerHTML = oferta.modulos.map(function(modulo) {
                var badgeMoodle = modulo.moodle_course_id
                    ? '<span style="background:rgba(21,101,192,.12);color:#1565c0;padding:1px 5px;border-radius:4px;font-size:.65rem;margin-left:4px;">Moodle</span>'
                    : '';
                return '<div class="cronograma-modulo-card" style="--mod-color:' + modulo.color + ';" ' +
                    'onclick="seleccionarModuloHorarioDocente(' + modulo.id + ', \'' + modulo.nombre.replace(/'/g, "\\'") + '\', \'' + modulo.color + '\', event)">' +
                    '<div class="cronograma-modulo-dot" style="background:' + modulo.color + '"></div>' +
                    '<div class="cronograma-modulo-info">' +
                        '<div class="cronograma-modulo-num">Módulo ' + modulo.numero + '</div>' +
                        '<div class="cronograma-modulo-name">' + modulo.nombre + badgeMoodle + '</div>' +
                    '</div>' +
                    '<div class="cronograma-modulo-badge">' + modulo.sesiones_count + ' ses.</div>' +
                    '</div>';
            }).join('');

            actualizarCalendarioHorarioDocente(oferta.modulos);
        };

        window.seleccionarModuloHorarioDocente = function(moduloId, moduloNombre, moduloColor, evt) {
            const badge    = document.getElementById('moduloSeleccionadoBadgeHorarioDocente');
            const btnTodos = document.getElementById('btnTodosModulosHorarioDocente');

            badge.style.display = 'flex';
            badge.querySelector('.dot').style.background = moduloColor;
            badge.querySelector('.modulo-badge-name').textContent = 'Módulo: ' + moduloNombre;
            btnTodos.classList.remove('active');

            document.querySelectorAll('#tab-horario-docente .cronograma-modulo-card')
                .forEach(function(el) { el.classList.remove('active'); });
            if (evt && evt.target) evt.target.closest('.cronograma-modulo-card').classList.add('active');

            moduloSeleccionadoHorarioDocenteId = moduloId;

            const ofertaId = document.getElementById('select-oferta-horario-docente').value;
            const oferta   = datosOfertasDocente.find(function(o) { return o.id == parseInt(ofertaId); });
            const modulo   = oferta.modulos.find(function(m) { return m.id == moduloId; });
            actualizarCalendarioHorarioDocente([modulo]);
        };

        window.verTodosModulosHorarioDocente = function() {
            const ofertaId = document.getElementById('select-oferta-horario-docente').value;
            const badge    = document.getElementById('moduloSeleccionadoBadgeHorarioDocente');
            const btnTodos = document.getElementById('btnTodosModulosHorarioDocente');

            moduloSeleccionadoHorarioDocenteId = null;
            badge.style.display = 'none';
            btnTodos.classList.add('active');
            document.querySelectorAll('#tab-horario-docente .cronograma-modulo-card')
                .forEach(function(el) { el.classList.remove('active'); });

            if (!ofertaId) return;
            const oferta = datosOfertasDocente.find(function(o) { return o.id == parseInt(ofertaId); });
            actualizarCalendarioHorarioDocente(oferta.modulos);
        };

        window.actualizarCalendarioHorarioDocente = function(modulos) {
            const eventos = [];
            modulos.forEach(function(modulo) {
                if (!modulo.sesiones || !modulo.sesiones.length) return;
                modulo.sesiones.forEach(function(sesion) {
                    const esPostergado = sesion.estado === 'Postergado';
                    eventos.push({
                        id:              sesion.id,
                        title:           sesion.titulo,
                        start:           sesion.start,
                        end:             sesion.end,
                        backgroundColor: esPostergado ? 'transparent' : modulo.color,
                        borderColor:     modulo.color,
                        textColor:       esPostergado ? modulo.color : '#fff',
                        extendedProps: {
                            modulo_nombre:               sesion.titulo,
                            modulo_color:                modulo.color,
                            estado:                      sesion.estado,
                            enlace_videollamada_url:      sesion.enlace_videollamada_url      || '',
                            enlace_videollamada_nombre:   sesion.enlace_videollamada_nombre   || '',
                            enlace_grabacion:             sesion.enlace_grabacion             || '',
                            reprogramado_de_fecha:        sesion.reprogramado_de_fecha        || null,
                            reprogramado_a_fecha:         sesion.reprogramado_a_fecha         || null,
                        }
                    });
                });
            });

            const calendarEl = document.getElementById('calendarHorarioDocente');
            if (!calendarEl) return;

            /* ── Navegar al evento más próximo (próxima o más reciente pasada) ── */
            function irAEventoProximo(evs) {
                if (!evs.length) return;
                const ahora = new Date();
                let objetivo = null, diffMin = Infinity;
                evs.forEach(function(ev) {
                    const d = new Date(ev.start);
                    const diff = d - ahora;
                    if (diff >= 0 && diff < diffMin) { diffMin = diff; objetivo = d; }
                });
                if (!objetivo) {
                    diffMin = Infinity;
                    evs.forEach(function(ev) {
                        const d = new Date(ev.start);
                        const diff = ahora - d;
                        if (diff < diffMin) { diffMin = diff; objetivo = d; }
                    });
                }
                if (objetivo) calendarioHorarioDocente.gotoDate(objetivo);
            }

            if (!calendarioHorarioDocente) {
                calendarioHorarioDocente = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left:   'prev,next today',
                        center: 'title',
                        right:  'dayGridMonth,timeGridWeek,listMonth'
                    },
                    locale: 'es',
                    buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana', list: 'Lista' },
                    editable:    false,
                    selectable:  false,
                    eventDisplay: 'block',
                    allDaySlot:  false,
                    slotMinTime: '07:00:00',
                    slotMaxTime: '21:00:00',
                    nowIndicator: true,
                    scrollTime:  '08:00:00',
                    slotDuration: '00:30:00',
                    eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
                    eventDidMount: function(info) {
                        const estado = info.event.extendedProps.estado;
                        const color  = info.event.extendedProps.modulo_color || info.event.backgroundColor;
                        const esList = info.el.classList.contains('fc-list-event');
                        if (estado === 'Postergado') {
                            info.el.classList.add('fc-event-postergado');
                            info.el.style.setProperty('border-color', color, 'important');
                            /* grid views */
                            const titleEl = info.el.querySelector('.fc-event-title');
                            if (titleEl) {
                                titleEl.style.setProperty('color', color, 'important');
                                if (!titleEl.querySelector('.ri-time-line')) {
                                    titleEl.innerHTML = '<i class="ri-time-line me-1" style="font-size:.8rem;vertical-align:middle;"></i>' + titleEl.innerHTML;
                                }
                            }
                            const timeEl = info.el.querySelector('.fc-event-time');
                            if (timeEl) timeEl.style.setProperty('color', color, 'important');
                            /* list view */
                            if (esList) {
                                const dotEl = info.el.querySelector('.fc-list-event-dot');
                                if (dotEl) dotEl.style.setProperty('border-color', color, 'important');
                                const listTitle = info.el.querySelector('.fc-list-event-title a');
                                if (listTitle) listTitle.style.setProperty('color', color, 'important');
                                const listTime  = info.el.querySelector('.fc-list-event-time');
                                if (listTime)  listTime.style.setProperty('color', '#94a3b8', 'important');
                            }
                        } else {
                            if (esList) {
                                const dotEl = info.el.querySelector('.fc-list-event-dot');
                                if (dotEl) dotEl.style.setProperty('border-color', color, 'important');
                                const listTitle = info.el.querySelector('.fc-list-event-title a');
                                if (listTitle) listTitle.style.setProperty('color', color, 'important');
                            } else {
                                info.el.style.setProperty('background-color', color, 'important');
                                info.el.style.setProperty('border-color',     color, 'important');
                                info.el.style.setProperty('color',            '#fff','important');
                            }
                        }
                    },
                    eventClick: function(info) {
                        abrirModalSesionEstudiante(info.event);
                    },
                    height: 'auto'
                });
                calendarioHorarioDocente.render();
                calendarioHorarioDocente.addEventSource(eventos);
                irAEventoProximo(eventos);
            } else {
                calendarioHorarioDocente.removeAllEventSources();
                calendarioHorarioDocente.addEventSource(eventos);
                irAEventoProximo(eventos);
            }
        };

        @if ($esDocente && $perfilActivo === 'docente')
        document.addEventListener('DOMContentLoaded', function() {
            if (datosOfertasDocente && datosOfertasDocente.length > 0) {
                const sel = document.getElementById('select-oferta-horario-docente');
                if (sel) {
                    sel.value = datosOfertasDocente[0].id;
                    cargarModulosHorarioDocente();
                }
            }
        });
        @endif

        /* ══════════════════════════════════════════════════════════════════
           MODAL ACTIVIDADES ESTUDIANTE
        ══════════════════════════════════════════════════════════════════ */
        var _actModal = { cmid: null, modname: null, moduloId: null, name: null, discId: null };

        function abrirModalAct(cmid, modname, moduloId, name) {
            _actModal = { cmid: cmid, modname: modname, moduloId: moduloId, name: name, discId: null };

            var icons = { assign: 'ri-file-text-line', quiz: 'ri-question-line', forum: 'ri-discuss-line' };
            var colors = { assign: '#3b82f6', quiz: '#f97316', forum: '#8b5cf6' };
            var subtitles = { assign: 'Tarea', quiz: 'Cuestionario', forum: 'Foro de discusión' };

            document.getElementById('modal-act-icon').innerHTML =
                '<i class="' + (icons[modname] || 'ri-apps-line') + '" style="color:' + (colors[modname] || '#6c757d') + ';"></i>';
            document.getElementById('modal-act-title').textContent = name;
            document.getElementById('modal-act-subtitle').textContent = subtitles[modname] || '';
            document.getElementById('modal-act-body').innerHTML =
                '<div id="modal-act-loading" style="text-align:center;padding:2rem;color:#6c757d;">' +
                '<div class="spinner-border spinner-border-sm" style="color:#fc7b04;"></div>' +
                '<span style="margin-left:.5rem;font-size:.9rem;">Cargando…</span></div>';

            document.getElementById('modal-act-overlay').style.display = 'block';
            document.body.style.overflow = 'hidden';

            if (modname === 'assign') cargarTarea(cmid, moduloId);
            else if (modname === 'forum') cargarForo(cmid, moduloId);
            else if (modname === 'quiz') cargarQuiz(cmid, moduloId);
        }

        function cerrarModalAct() {
            document.getElementById('modal-act-overlay').style.display = 'none';
            document.body.style.overflow = '';
            _actModal = { cmid: null, modname: null, moduloId: null, name: null, discId: null };
        }

        document.getElementById('modal-act-overlay').addEventListener('click', function(e) {
            if (e.target === this) cerrarModalAct();
        });

        function actSetBody(html) {
            document.getElementById('modal-act-body').innerHTML = html;
        }

        function actErrHtml(msg) {
            return '<div style="text-align:center;padding:2rem;color:#dc2626;">' +
                '<i class="ri-close-circle-line" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>' +
                escHtml(msg) + '</div>';
        }

        /* ── helpers fecha ── */
        function fmtTs(ts) {
            if (!ts) return '—';
            var d = new Date(ts * 1000);
            return d.toLocaleDateString('es-BO', { day:'2-digit', month:'short', year:'numeric' }) +
                ' ' + d.toLocaleTimeString('es-BO', { hour:'2-digit', minute:'2-digit' });
        }
        function fmtDur(secs) {
            if (!secs) return 'Sin límite';
            var m = Math.floor(secs / 60);
            return m >= 60 ? Math.floor(m / 60) + 'h ' + (m % 60) + 'min' : m + ' min';
        }

        /* ══════════════════════════════════════════════════════════════════
           TAREA
        ══════════════════════════════════════════════════════════════════ */
        function cargarTarea(cmid, moduloId) {
            $.get('/virtual/modulo/' + moduloId + '/actividad/tarea/' + cmid)
            .done(function(r) {
                if (!r.success) { actSetBody(actErrHtml(r.message)); return; }
                renderTarea(r.data, cmid, moduloId);
            })
            .fail(function() { actSetBody(actErrHtml('No se pudo cargar la tarea.')); });
        }

        function renderTarea(data, cmid, moduloId) {
            var assign = data.assign;
            var sub    = data.submission;
            var hasText = sub && sub.onlinetext;
            var now = Math.floor(Date.now() / 1000);
            var pastCutoff = assign.cutoffdate && now > assign.cutoffdate;
            var canEdit = !pastCutoff && (!assign.nosubmissions);

            var descHtml = assign.intro
                ? '<div style="background:#f8f9fa;border-radius:8px;padding:1rem;margin-bottom:1rem;font-size:.85rem;color:#495057;">' + assign.intro + '</div>'
                : '';

            var dueBadge = '';
            if (assign.duedate) {
                var overdue = now > assign.duedate && !sub;
                dueBadge = '<span style="font-size:.78rem;padding:.2rem .6rem;border-radius:12px;background:' +
                    (overdue ? '#fee2e2' : '#fef9c3') + ';color:' + (overdue ? '#dc2626' : '#92400e') + ';font-weight:600;">' +
                    '<i class="ri-calendar-line"></i> Entrega: ' + fmtTs(assign.duedate) + '</span>';
            }

            var statusHtml = '';
            if (sub) {
                statusHtml = '<div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;padding:.75rem 1rem;background:#f0fdf4;border-radius:8px;border-left:3px solid #16a34a;">' +
                    '<i class="ri-checkbox-circle-line" style="color:#16a34a;font-size:1.1rem;"></i>' +
                    '<div><div style="font-weight:600;font-size:.85rem;color:#166534;">Entrega registrada</div>' +
                    '<div style="font-size:.75rem;color:#6c757d;">Última modificación: ' + fmtTs(sub.timemodified) + '</div></div></div>';

                // Mostrar calificación si existe (Moodle usa -1 cuando aún no se ha calificado)
                var gradeNum = (assign.grade !== null && assign.grade !== undefined && assign.grade !== '') ? parseFloat(assign.grade) : NaN;
                var yaCalificado = !isNaN(gradeNum) && gradeNum >= 0;
                if (yaCalificado) {
                    var gradeMaxStr = (assign.grademax && parseFloat(assign.grademax) > 0) ? (' / ' + assign.grademax) : '';
                    statusHtml += '<div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;padding:.75rem 1rem;background:#f0fdf4;border-radius:8px;border-left:3px solid #16a34a;">' +
                        '<i class="ri-award-line" style="color:#16a34a;font-size:1.1rem;"></i>' +
                        '<div><div style="font-weight:600;font-size:.85rem;color:#166534;">Calificación</div>' +
                        '<div style="font-size:.85rem;font-weight:700;color:#16a34a;">' + gradeNum + gradeMaxStr + '</div></div></div>';
                } else {
                    statusHtml += '<div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;padding:.75rem 1rem;background:#f8f9fa;border-radius:8px;border-left:3px solid #6c757d;">' +
                        '<i class="ri-hourglass-line" style="color:#6c757d;font-size:1.1rem;"></i>' +
                        '<div><div style="font-weight:600;font-size:.85rem;color:#6c757d;">Calificación</div>' +
                        '<div style="font-size:.82rem;color:#6c757d;">Sin calificación registrada</div></div></div>';
                }
            }

            var prevContent = '';
            if (sub) {
                if (hasText) {
                    prevContent += '<div style="margin-bottom:.75rem;">' +
                        '<div style="font-size:.78rem;font-weight:600;color:#6c757d;margin-bottom:.4rem;text-transform:uppercase;letter-spacing:.5px;">Texto entregado</div>' +
                        '<div style="background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;padding:.75rem;font-size:.85rem;max-height:120px;overflow-y:auto;">' +
                        sub.onlinetext + '</div></div>';
                }
                if (sub.files && sub.files.length > 0) {
                    prevContent += '<div style="margin-bottom:.75rem;"><div style="font-size:.78rem;font-weight:600;color:#6c757d;margin-bottom:.4rem;text-transform:uppercase;letter-spacing:.5px;">Archivos adjuntos</div>';
                    sub.files.forEach(function(f) {
                        var downloadUrl = '/virtual/modulo/' + moduloId + '/actividad/tarea/' + cmid + '/archivo/' + encodeURIComponent(f.filename);
                        prevContent += '<div style="display:flex;align-items:center;gap:.5rem;padding:.4rem .75rem;background:#f8f9fa;border-radius:6px;margin-bottom:.25rem;font-size:.82rem;">' +
                            '<i class="ri-file-line" style="color:#6c757d;"></i> ' + escHtml(f.filename) +
                            '<span style="margin-left:auto;color:#6c757d;">' + Math.round(f.filesize / 1024) + ' KB</span>' +
                            '<a href="' + downloadUrl + '" style="color:#3b82f6;font-size:1rem;flex-shrink:0;text-decoration:none;" title="Descargar" download><i class="ri-download-2-line"></i></a>' +
                            (canEdit ? '<span style="cursor:pointer;color:#dc2626;font-size:1rem;flex-shrink:0;" onclick="eliminarArchivoTarea(' + cmid + ',' + moduloId + ',\'' + escHtml(f.filename) + '\', this)" title="Eliminar archivo"><i class="ri-close-circle-line"></i></span>' : '') +
                            '</div>';
                    });
                    prevContent += '</div>';
                }
            }

            var cutoffMsg = '';
            if (pastCutoff) {
                cutoffMsg = '<div style="padding:.75rem 1rem;background:#fee2e2;border-radius:8px;border-left:3px solid #dc2626;margin-bottom:1rem;font-size:.85rem;color:#991b1b;">' +
                    '<i class="ri-timer-flash-line" style="margin-right:.4rem;"></i> La fecha límite de entrega (' + fmtTs(assign.cutoffdate) + ') ya pasó. No es posible modificar la entrega.</div>';
            } else if (assign.cutoffdate || assign.duedate) {
                var fechaTope = assign.cutoffdate || assign.duedate;
                cutoffMsg = '<div style="padding:.5rem .75rem;background:#fef9c3;border-radius:8px;margin-bottom:1rem;font-size:.8rem;color:#92400e;">' +
                    '<i class="ri-time-line" style="margin-right:.4rem;"></i> Puedes modificar tu entrega hasta: <strong>' + fmtTs(fechaTope) + '</strong></div>';
            }

            var html = descHtml;
            if (dueBadge) html += '<div style="margin-bottom:.75rem;">' + dueBadge + '</div>';
            html += statusHtml + cutoffMsg + prevContent;

            if (canEdit) {
                html += '<div style="font-size:.78rem;font-weight:600;color:#374151;margin-bottom:.5rem;text-transform:uppercase;letter-spacing:.5px;">' +
                    (sub ? 'Modificar entrega' : 'Realizar entrega') + '</div>';

                html += '<textarea id="tarea-txt" style="width:100%;min-height:120px;border:1.5px solid #dee2e6;border-radius:8px;padding:.75rem;font-size:.85rem;font-family:inherit;resize:vertical;box-sizing:border-box;"' +
                    ' placeholder="Escribe tu respuesta aquí (opcional si adjuntas archivo)…">' + (hasText ? escHtml(sub.onlinetext) : '') + '</textarea>';

                html += '<div style="margin-top:.75rem;display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">' +
                    '<button onclick="submitTareaTexto(' + cmid + ',' + moduloId + ', this)" ' +
                    'style="background:#fc7b04;color:#fff;border:none;border-radius:8px;padding:.6rem 1.25rem;font-weight:600;font-size:.85rem;cursor:pointer;display:flex;align-items:center;gap:.4rem;">' +
                    '<i class="ri-send-plane-line"></i> ' + (sub ? 'Guardar cambios' : 'Entregar ahora') + '</button>' +
                    '<label style="cursor:pointer;display:flex;align-items:center;gap:.4rem;background:#f3f4f6;border:1.5px dashed #d1d5db;border-radius:8px;padding:.5rem 1rem;font-size:.82rem;font-weight:500;color:#374151;">' +
                    '<i class="ri-attachment-line"></i> Subir archivo' +
                    '<input type="file" id="tarea-file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.jpg,.jpeg,.png,.txt" style="display:none;" onchange="uploadTareaFile(' + cmid + ',' + moduloId + ',this)"></label>' +
                    '</div>';
                html += '<div id="tarea-file-name" style="font-size:.78rem;color:#6c757d;margin-top:.4rem;"></div>';
                html += '<div id="tarea-msg" style="margin-top:.75rem;"></div>';
            }

            actSetBody(html);
        }

        function submitTareaTexto(cmid, moduloId) {
            var text = document.getElementById('tarea-txt').value.trim();
            var btn = event.currentTarget;
            var esActualizacion = btn.textContent.includes('Guardar');
            btn.disabled = true;
            btn.innerHTML = '<i class="ri-loader-4-line"></i> Enviando…';

            $.ajax({
                url:  '/virtual/modulo/' + moduloId + '/actividad/tarea/' + cmid + '/submit',
                type: 'POST',
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: JSON.stringify({ text: text || '' }),
            })
            .done(function(r) {
                btn.disabled = false;
                if (r.success) {
                    estMostrarToast('success', r.message || 'Tarea entregada correctamente.');
                    // Cerrar modal inmediatamente y actualizar el panel de actividades
                    cerrarModalAct();
                    if (typeof window.recargarActividadesModulo === 'function') {
                        window.recargarActividadesModulo(moduloId);
                    }
                } else {
                    btn.innerHTML = '<i class="ri-send-plane-line"></i> ' + (esActualizacion ? 'Guardar cambios' : 'Entregar ahora');
                    estMostrarToast('error', r.message || 'Error al entregar.');
                }
            })
            .fail(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-send-plane-line"></i> ' + (esActualizacion ? 'Guardar cambios' : 'Entregar ahora');
                estMostrarToast('error', 'Error de conexión.');
            });
        }

        function uploadTareaFile(cmid, moduloId, input) {
            if (!input.files[0]) return;
            var file = input.files[0];
            document.getElementById('tarea-file-name').textContent = 'Adjuntando: ' + file.name + '…';

            var fd = new FormData();
            fd.append('archivo', file);
            fd.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url:         '/virtual/modulo/' + moduloId + '/actividad/tarea/' + cmid + '/archivo',
                type:        'POST',
                data:        fd,
                processData: false,
                contentType: false,
            })
            .done(function(r) {
                if (r.success) {
                    document.getElementById('tarea-file-name').textContent = '✓ ' + file.name + ' adjuntado.';
                    estMostrarToast('success', r.message || 'Archivo adjuntado correctamente.');
                    // Mantener el modal abierto y refrescar su contenido para mostrar el archivo cargado.
                    // El cierre + actualización del panel de actividades ocurre al pulsar "Guardar cambios".
                    cargarTarea(cmid, moduloId);
                } else {
                    document.getElementById('tarea-file-name').textContent = '';
                    estMostrarToast('error', r.message || 'No se pudo adjuntar el archivo.');
                }
            })
            .fail(function() {
                document.getElementById('tarea-file-name').textContent = '';
                estMostrarToast('error', 'Error al subir el archivo.');
            });
        }

        function eliminarArchivoTarea(cmid, moduloId, filename, el) {
            if (!confirm('¿Eliminar "' + filename + '"?')) return;
            el.closest('div').style.opacity = '.4';
            $.ajax({
                url:  '/virtual/modulo/' + moduloId + '/actividad/tarea/' + cmid + '/archivo',
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: { filename: filename },
            })
            .done(function(r) {
                if (r.success) {
                    estMostrarToast('success', r.message);
                    cargarTarea(cmid, moduloId);
                } else {
                    estMostrarToast('error', r.message || 'Error al eliminar.');
                }
            })
            .fail(function() {
                estMostrarToast('error', 'Error de conexión.');
            });
        }

        /* ══════════════════════════════════════════════════════════════════
           FORO
        ══════════════════════════════════════════════════════════════════ */
        function cargarForo(cmid, moduloId) {
            $.get('/virtual/modulo/' + moduloId + '/actividad/foro/' + cmid)
            .done(function(r) {
                if (!r.success) { actSetBody(actErrHtml(r.message)); return; }
                renderForoLista(r.data, cmid, moduloId, r.forum || null);
            })
            .fail(function() { actSetBody(actErrHtml('No se pudo cargar el foro.')); });
        }

        function renderForoLista(discusiones, cmid, moduloId, forumInfo) {
            var html = '';

            // Cabecera con descripción del foro y archivo adjunto (si existen)
            if (forumInfo) {
                if (forumInfo.intro && String(forumInfo.intro).trim() !== '') {
                    html += '<div style="background:#f8fafc;border-left:3px solid #8b5cf6;border-radius:6px;padding:.65rem .85rem;margin-bottom:.85rem;font-size:.85rem;color:#374151;line-height:1.45;">' +
                        '<div style="font-size:.72rem;font-weight:700;color:#6d28d9;text-transform:uppercase;letter-spacing:.5px;margin-bottom:.3rem;"><i class="ri-information-line"></i> Descripción del foro</div>' +
                        forumInfo.intro + '</div>';
                }
                if (forumInfo.has_intro_file) {
                    var adjUrl = '/virtual/modulo/' + moduloId + '/actividad/foro/' + cmid + '/adjunto';
                    html += '<div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.85rem;flex-wrap:wrap;">' +
                        '<a href="' + adjUrl + '" target="_blank" rel="noopener noreferrer"' +
                        ' style="background:#0ea5e9;color:#fff;border-radius:6px;padding:.35rem .75rem;font-size:.78rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;">' +
                        '<i class="ri-attachment-line"></i> Ver adjunto</a>' +
                        '<a href="' + adjUrl + '?download=1"' +
                        ' style="background:#16a34a;color:#fff;border-radius:6px;padding:.35rem .75rem;font-size:.78rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;">' +
                        '<i class="ri-download-2-line"></i> Descargar</a>' +
                        '</div>';
                }
            }

            html += '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">' +
                '<span style="font-size:.85rem;color:#6c757d;">' + discusiones.length + ' discusión(es)</span>' +
                '<button onclick="mostrarFormNuevaDisc(' + cmid + ',' + moduloId + ')" ' +
                'style="background:#fc7b04;color:#fff;border:none;border-radius:8px;padding:.5rem 1rem;font-size:.82rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:.4rem;">' +
                '<i class="ri-add-line"></i> Nueva discusión</button></div>';

            html += '<div id="foro-form-nueva" style="display:none;background:#f8f9fa;border-radius:10px;padding:1rem;margin-bottom:1rem;border:1.5px solid #dee2e6;"></div>';

            if (discusiones.length === 0) {
                html += '<div style="text-align:center;padding:2rem;color:#6c757d;">' +
                    '<i class="ri-discuss-line" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>' +
                    'Aún no hay discusiones. ¡Sé el primero en participar!</div>';
            } else {
                html += '<div id="foro-lista">';
                discusiones.forEach(function(d) {
                    html += '<div class="foro-disc-card" style="border:1px solid #e9ecef;border-radius:10px;padding:1rem;margin-bottom:.75rem;cursor:pointer;transition:box-shadow .15s;"' +
                        ' onclick="abrirDiscusion(' + cmid + ',' + moduloId + ',' + d.id + ',\'' + escHtml(d.name) + '\')">' +
                        '<div style="display:flex;justify-content:space-between;align-items:flex-start;gap:.5rem;">' +
                        '<div style="flex:1;min-width:0;">' +
                        '<div style="font-weight:600;font-size:.88rem;color:#2c3e50;margin-bottom:.25rem;">' + escHtml(d.name) + '</div>' +
                        '<div style="font-size:.78rem;color:#6c757d;">' + escHtml(d.firstmessage) + '</div></div>' +
                        '<div style="text-align:right;flex-shrink:0;">' +
                        '<div style="font-size:.72rem;color:#6c757d;">' + fmtTs(d.timemodified) + '</div>' +
                        '<div style="font-size:.72rem;color:#8b5cf6;font-weight:600;margin-top:.2rem;">' +
                        '<i class="ri-chat-3-line"></i> ' + d.replies + ' respuesta(s)</div></div></div>' +
                        '<div style="font-size:.72rem;color:#374151;margin-top:.4rem;">' +
                        '<i class="ri-user-line"></i> ' + escHtml(d.author) +
                        (d.is_mine ? ' <span style="color:#fc7b04;font-weight:600;">(Tú)</span>' : '') + '</div></div>';
                });
                html += '</div>';
            }

            actSetBody(html);
        }

        function mostrarFormNuevaDisc(cmid, moduloId) {
            var form = document.getElementById('foro-form-nueva');
            if (!form) return;
            form.style.display = 'block';
            form.innerHTML =
                '<div style="font-weight:600;font-size:.88rem;color:#2c3e50;margin-bottom:.75rem;"><i class="ri-add-circle-line"></i> Nueva discusión</div>' +
                '<input id="disc-asunto" type="text" placeholder="Asunto de la discusión *" maxlength="255" ' +
                'style="width:100%;border:1.5px solid #dee2e6;border-radius:8px;padding:.6rem .8rem;font-size:.85rem;margin-bottom:.6rem;box-sizing:border-box;">' +
                '<textarea id="disc-msg" rows="4" placeholder="Escribe tu mensaje *" ' +
                'style="width:100%;border:1.5px solid #dee2e6;border-radius:8px;padding:.6rem .8rem;font-size:.85rem;resize:vertical;box-sizing:border-box;"></textarea>' +
                '<div style="display:flex;gap:.5rem;margin-top:.6rem;">' +
                '<button onclick="submitNuevaDisc(' + cmid + ',' + moduloId + ')" ' +
                'style="background:#fc7b04;color:#fff;border:none;border-radius:8px;padding:.5rem 1rem;font-size:.82rem;font-weight:600;cursor:pointer;">' +
                '<i class="ri-send-plane-line"></i> Publicar</button>' +
                '<button onclick="document.getElementById(\'foro-form-nueva\').style.display=\'none\'" ' +
                'style="background:#f3f4f6;color:#374151;border:1px solid #d1d5db;border-radius:8px;padding:.5rem 1rem;font-size:.82rem;cursor:pointer;">Cancelar</button>' +
                '</div>';
        }

        function submitNuevaDisc(cmid, moduloId) {
            var asunto = (document.getElementById('disc-asunto').value || '').trim();
            var msg    = (document.getElementById('disc-msg').value || '').trim();
            if (!asunto || !msg) { estMostrarToast('error', 'Completa el asunto y el mensaje.'); return; }

            $.ajax({
                url:  '/virtual/modulo/' + moduloId + '/actividad/foro/' + cmid + '/discusion',
                type: 'POST',
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: JSON.stringify({ subject: asunto, message: msg }),
            })
            .done(function(r) {
                if (r.success) {
                    estMostrarToast('success', 'Discusión creada correctamente.');
                    cargarForo(cmid, moduloId);
                } else {
                    estMostrarToast('error', r.message || 'Error al crear la discusión.');
                }
            })
            .fail(function() { estMostrarToast('error', 'Error de conexión.'); });
        }

        function abrirDiscusion(cmid, moduloId, discId, nombre) {
            _actModal.discId = discId;
            actSetBody('<div style="text-align:center;padding:2rem;color:#6c757d;"><div class="spinner-border spinner-border-sm"></div> Cargando mensajes…</div>');

            $.get('/virtual/modulo/' + moduloId + '/actividad/foro/' + cmid + '/discusion/' + discId)
            .done(function(r) {
                if (!r.success) { actSetBody(actErrHtml(r.message)); return; }
                renderDiscusionPosts(r, cmid, moduloId);
            })
            .fail(function() { actSetBody(actErrHtml('Error al cargar los mensajes.')); });
        }

        function renderDiscusionPosts(r, cmid, moduloId) {
            var disc  = r.discussion;
            var posts = r.posts || [];
            var myUid = r.my_user_id;

            var html = '<div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;">' +
                '<button onclick="cargarForo(' + cmid + ',' + moduloId + ')" ' +
                'style="background:#f3f4f6;border:1px solid #d1d5db;border-radius:6px;padding:.3rem .7rem;font-size:.8rem;cursor:pointer;">' +
                '<i class="ri-arrow-left-line"></i> Volver</button>' +
                '<span style="font-weight:600;font-size:.9rem;color:#2c3e50;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + escHtml(disc.name) + '</span></div>';

            posts.forEach(function(p) {
                var isMe = p.userid === myUid;
                var isRoot = p.parent === 0;
                html += '<div style="margin-bottom:.75rem;' + (isRoot ? '' : 'margin-left:2rem;border-left:3px solid #e9ecef;padding-left:.75rem;') + '">' +
                    '<div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem;">' +
                    '<div style="width:28px;height:28px;border-radius:50%;background:' + (isMe ? '#fc7b04' : '#8b5cf6') + ';display:flex;align-items:center;justify-content:center;color:#fff;font-size:.72rem;font-weight:700;flex-shrink:0;">' +
                    escHtml(p.author.charAt(0).toUpperCase()) + '</div>' +
                    '<div><span style="font-weight:600;font-size:.82rem;">' + escHtml(p.author) + (isMe ? ' <span style="color:#fc7b04;">(Tú)</span>' : '') + '</span>' +
                    '<span style="font-size:.72rem;color:#6c757d;margin-left:.4rem;">' + fmtTs(p.created) + '</span></div>' +
                    '<button onclick="mostrarFormReply(' + cmid + ',' + moduloId + ',' + disc.id + ',' + p.id + ',\'' + escHtml(disc.name) + '\')" ' +
                    'style="margin-left:auto;background:none;border:1px solid #d1d5db;border-radius:6px;padding:.2rem .5rem;font-size:.72rem;cursor:pointer;color:#6c757d;">' +
                    '<i class="ri-reply-line"></i> Responder</button></div>' +
                    '<div style="font-size:.85rem;color:#374151;line-height:1.5;">' + p.message + '</div>' +
                    '<div id="form-reply-' + p.id + '" style="display:none;margin-top:.5rem;"></div>' +
                    '</div>';
            });

            html += '<div style="margin-top:1.25rem;padding-top:1rem;border-top:1px solid #e9ecef;">' +
                '<div style="font-weight:600;font-size:.85rem;color:#374151;margin-bottom:.5rem;"><i class="ri-message-line"></i> Responder en esta discusión</div>' +
                '<textarea id="reply-main" rows="3" placeholder="Escribe tu respuesta…" ' +
                'style="width:100%;border:1.5px solid #dee2e6;border-radius:8px;padding:.6rem .8rem;font-size:.85rem;resize:vertical;box-sizing:border-box;"></textarea>' +
                '<button onclick="submitReply(' + cmid + ',' + moduloId + ',' + disc.id + ',' + (posts.length > 0 ? posts[0].id : 0) + ',\'' + escHtml(disc.name) + '\',\'reply-main\')" ' +
                'style="margin-top:.5rem;background:#8b5cf6;color:#fff;border:none;border-radius:8px;padding:.5rem 1rem;font-size:.82rem;font-weight:600;cursor:pointer;">' +
                '<i class="ri-send-plane-line"></i> Publicar respuesta</button></div>';

            actSetBody(html);
        }

        function mostrarFormReply(cmid, moduloId, discId, parentId, discName) {
            var formId = 'form-reply-' + parentId;
            var form = document.getElementById(formId);
            if (!form) return;
            if (form.style.display === 'block') { form.style.display = 'none'; return; }
            form.style.display = 'block';
            form.innerHTML =
                '<textarea id="reply-inline-' + parentId + '" rows="3" placeholder="Tu respuesta…" ' +
                'style="width:100%;border:1.5px solid #dee2e6;border-radius:8px;padding:.5rem .7rem;font-size:.82rem;resize:vertical;box-sizing:border-box;"></textarea>' +
                '<div style="display:flex;gap:.4rem;margin-top:.4rem;">' +
                '<button onclick="submitReply(' + cmid + ',' + moduloId + ',' + discId + ',' + parentId + ',\'' + escHtml(discName) + '\',\'reply-inline-' + parentId + '\')" ' +
                'style="background:#8b5cf6;color:#fff;border:none;border-radius:6px;padding:.35rem .8rem;font-size:.78rem;font-weight:600;cursor:pointer;">' +
                '<i class="ri-send-plane-line"></i> Enviar</button>' +
                '<button onclick="document.getElementById(\'' + formId + '\').style.display=\'none\'" ' +
                'style="background:#f3f4f6;border:1px solid #d1d5db;border-radius:6px;padding:.35rem .8rem;font-size:.78rem;cursor:pointer;">Cancelar</button></div>';
        }

        function submitReply(cmid, moduloId, discId, parentId, discName, textareaId) {
            var msg = (document.getElementById(textareaId).value || '').trim();
            if (!msg) { estMostrarToast('error', 'Escribe un mensaje antes de responder.'); return; }

            $.ajax({
                url:  '/virtual/modulo/' + moduloId + '/actividad/foro/' + cmid + '/discusion/' + discId + '/reply',
                type: 'POST',
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: JSON.stringify({ message: msg, parent_id: parentId }),
            })
            .done(function(r) {
                if (r.success) {
                    estMostrarToast('success', 'Respuesta publicada.');
                    abrirDiscusion(cmid, moduloId, discId, discName);
                } else {
                    estMostrarToast('error', r.message || 'Error al publicar.');
                }
            })
            .fail(function() { estMostrarToast('error', 'Error de conexión.'); });
        }

        /* ══════════════════════════════════════════════════════════════════
           CUESTIONARIO
        ══════════════════════════════════════════════════════════════════ */
        var _quizTimer = { end: 0, interval: null, timelimit: 0 };

        function detenerTimer() {
            if (_quizTimer.interval) { clearInterval(_quizTimer.interval); _quizTimer.interval = null; }
        }

        function cargarQuiz(cmid, moduloId) {
            detenerTimer();
            $.get('/virtual/modulo/' + moduloId + '/actividad/quiz/' + cmid)
            .done(function(r) {
                if (!r.success) { actSetBody(actErrHtml(r.message)); return; }
                renderQuiz(r, cmid, moduloId);
            })
            .fail(function() { actSetBody(actErrHtml('No se pudo cargar el cuestionario.')); });
        }

        function renderQuiz(r, cmid, moduloId) {
            detenerTimer();
            var q        = r.data.quiz;
            var attempts = r.data.student_attempts || [];
            var maxReached = r.data.max_attempts_reached;
            var inProgress = r.data.has_inprogress;
            _quizTimer.timelimit = q.timelimit || 0;

            var html = '';

            if (q.intro) {
                html += '<div style="background:linear-gradient(135deg,#fef9ef,#fff7ed);border-left:4px solid #fc7b04;border-radius:12px;padding:1rem 1.15rem;margin-bottom:1.15rem;font-size:.85rem;color:#78350f;line-height:1.65;">' +
                    '<div style="font-weight:600;font-size:.78rem;color:#d97706;text-transform:uppercase;letter-spacing:.4px;margin-bottom:.35rem;"><i class="ri-information-line"></i> Acerca de este cuestionario</div>' + q.intro + '</div>';
            }

            html += '<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(145px,1fr));gap:.75rem;margin-bottom:1.35rem;">';
            html += infoCard('ri-time-line', 'Tiempo límite', fmtDur(q.timelimit));
            html += infoCard('ri-repeat-line', 'Intentos permitidos', q.attempts === 0 ? 'Ilimitados' : q.attempts);
            html += infoCard('ri-star-line', 'Calificación máx.', q.grade ? q.grade + ' pts' : '—');
            if (q.timeopen) html += infoCard('ri-calendar-check-line', 'Disponible desde', fmtTs(q.timeopen));
            if (q.timeclose) html += infoCard('ri-calendar-close-line', 'Cierra', fmtTs(q.timeclose));
            html += '</div>';

            if (attempts.length > 0) {
                html += '<div style="margin-bottom:1.25rem;">';
                html += '<div style="font-weight:700;font-size:.8rem;color:#475569;margin-bottom:.55rem;text-transform:uppercase;letter-spacing:.6px;display:flex;align-items:center;gap:.45rem;"><i class="ri-history-line" style="font-size:1rem;"></i> Tus intentos <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#94a3b8;font-size:.75rem;">(' + attempts.length + ')</span></div>';
                html += '<div style="border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.04);">';
                html += '<table style="width:100%;border-collapse:collapse;font-size:.8rem;">';
                html += '<thead><tr style="background:#f1f5f9;">' +
                    '<th style="padding:.6rem .9rem;text-align:left;color:#475569;font-weight:600;">Intento</th>' +
                    '<th style="padding:.6rem .9rem;text-align:left;color:#475569;font-weight:600;">Inicio</th>' +
                    '<th style="padding:.6rem .9rem;text-align:left;color:#475569;font-weight:600;">Fin</th>' +
                    '<th style="padding:.6rem .9rem;text-align:right;color:#475569;font-weight:600;">Nota</th></tr></thead><tbody>';
                attempts.forEach(function(a) {
                    var gradeDisplay = a.grade !== null
                        ? '<span style="font-weight:800;color:#0f172a;">' + a.grade + '</span> <span style="color:#94a3b8;font-weight:400;">pts</span>'
                        : '<span style="color:#94a3b8;">—</span>';
                    html += '<tr style="border-top:1px solid #f1f5f9;transition:background .12s;" onmouseenter="this.style.background=\'#fafbfc\'" onmouseleave="this.style.background=\'\'">' +
                        '<td style="padding:.65rem .9rem;font-weight:700;color:#0f172a;">#' + a.attempt + '</td>' +
                        '<td style="padding:.65rem .9rem;color:#475569;">' + fmtTs(a.timestart) + '</td>' +
                        '<td style="padding:.65rem .9rem;color:#475569;">' + fmtTs(a.timefinish) + '</td>' +
                        '<td style="padding:.65rem .9rem;text-align:right;">' + gradeDisplay + '</td></tr>';
                });
                html += '</tbody></table></div></div>';
            } else {
                html += '<div style="text-align:center;padding:2rem 1.5rem;background:linear-gradient(135deg,#f8fafc,#f1f5f9);border-radius:14px;margin-bottom:1.25rem;border:1px dashed #d1d5db;">' +
                    '<div style="font-size:2rem;color:#cbd5e1;margin-bottom:.6rem;"><i class="ri-question-answer-line"></i></div>' +
                    '<div style="font-weight:600;color:#475569;font-size:.9rem;margin-bottom:.2rem;">Sin intentos aún</div>' +
                    '<div style="font-size:.8rem;color:#94a3b8;">Aún no has realizado ningún intento en este cuestionario.</div></div>';
            }

            if (inProgress) {
                html += '<div style="background:linear-gradient(135deg,#fef9c3,#fef3c7);border:1px solid #fde68a;border-radius:12px;padding:.8rem 1rem;margin-bottom:.85rem;font-size:.85rem;color:#92400e;font-weight:500;display:flex;align-items:center;gap:.6rem;box-shadow:0 1px 4px rgba(0,0,0,.03);">' +
                    '<i class="ri-timer-flash-line" style="font-size:1.2rem;color:#d97706;flex-shrink:0;"></i>' +
                    '<span style="flex:1;">Tienes un intento en progreso.</span>' +
                    '<button onclick="cargarQuizActivo(' + cmid + ',' + moduloId + ')" style="background:#fc7b04;color:#fff;border:none;border-radius:8px;padding:.35rem .85rem;font-size:.8rem;font-weight:700;cursor:pointer;white-space:nowrap;transition:background .15s;">Continuar aquí</button></div>';
            }

            if (!maxReached) {
                var btnFn = inProgress ? 'cargarQuizActivo' : 'iniciarQuiz';
                html += '<div style="text-align:center;margin-top:.75rem;">' +
                    '<button onclick="' + btnFn + '(' + cmid + ',' + moduloId + ')" ' +
                    'style="background:linear-gradient(135deg,#fc7b04,#e06a00);color:#fff;border:none;border-radius:14px;padding:.9rem 2.75rem;font-size:.95rem;font-weight:800;cursor:pointer;display:inline-flex;align-items:center;gap:.65rem;box-shadow:0 6px 20px rgba(252,123,4,.35);transition:transform .15s,box-shadow .15s;" ' +
                    'onmouseenter="this.style.transform=\'translateY(-2px)\';this.style.boxShadow=\'0 8px 28px rgba(252,123,4,.4)\'" ' +
                    'onmouseleave="this.style.transform=\'\';this.style.boxShadow=\'0 6px 20px rgba(252,123,4,.35)\'">' +
                    '<i class="ri-play-circle-line" style="font-size:1.15rem;"></i> ' + (inProgress ? 'Continuar intento' : 'Comenzar cuestionario') + '</button></div>';
            } else {
                html += '<div style="text-align:center;margin-top:.75rem;padding:1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:12px;color:#991b1b;font-size:.85rem;font-weight:500;display:flex;align-items:center;justify-content:center;gap:.5rem;">' +
                    '<i class="ri-error-warning-line" style="font-size:1.1rem;"></i> Has alcanzado el número máximo de intentos.</div>';
            }

            actSetBody(html);
        }

        function iniciarQuiz(cmid, moduloId) {
            actSetBody('<div style="text-align:center;padding:2rem;color:#6c757d;">' +
                '<div class="spinner-border spinner-border-sm" style="color:#fc7b04;"></div>' +
                '<span style="margin-left:.5rem;font-size:.9rem;">Preparando cuestionario…</span></div>');

            var token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url:  '/virtual/modulo/' + moduloId + '/actividad/quiz/' + cmid + '/start',
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': token },
            })
            .done(function(r) {
                if (!r.success) { actSetBody(actErrHtml(r.message)); return; }
                var attemptId = r.attempt.id;
                var timestart = r.timestart || Math.floor(Date.now() / 1000);
                cargarPreguntasQuiz(attemptId, cmid, moduloId, timestart);
            })
            .fail(function() { actSetBody(actErrHtml('No se pudo iniciar el cuestionario.')); });
        }

        function cargarQuizActivo(cmid, moduloId) {
            actSetBody('<div style="text-align:center;padding:2rem;color:#6c757d;">' +
                '<div class="spinner-border spinner-border-sm" style="color:#fc7b04;"></div>' +
                '<span style="margin-left:.5rem;font-size:.9rem;">Cargando intento activo…</span></div>');

            // Fetch attempt timestart directly via a dedicated call
            cargarPreguntasQuiz(null, cmid, moduloId, null, true);
        }

        function cargarPreguntasQuiz(attemptId, cmid, moduloId, timestart, isActive) {
            if (isActive) {
                // For active/continuing quizzes, first fetch quiz data to get the in-progress attempt ID and timelimit
                $.get('/virtual/modulo/' + moduloId + '/actividad/quiz/' + cmid)
                .done(function(r) {
                    if (!r.success) { actSetBody(actErrHtml(r.message)); return; }
                    if (!r.data.has_inprogress || !r.data.inprogress_attempt_id) {
                        renderQuiz(r, cmid, moduloId);
                        return;
                    }
                    _quizTimer.timelimit = r.data.quiz.timelimit || 0;
                    var aid = r.data.inprogress_attempt_id;
                    // Now fetch questions with attempt info
                    $.get('/virtual/modulo/' + moduloId + '/actividad/quiz/' + cmid + '/attempt/' + aid)
                    .done(function(resp) {
                        if (!resp.success) { actSetBody(actErrHtml(resp.message)); return; }
                        // Preferir SIEMPRE el timestart del servidor — así el cronómetro continúa
                        // desde el momento real en que se inició el intento (no se reinicia al regresar).
                        var ts = (resp.attempt && resp.attempt.timestart) ? parseInt(resp.attempt.timestart) : 0;
                        if (!ts) {
                            actSetBody(actErrHtml('No se pudo determinar la hora de inicio del intento.'));
                            return;
                        }
                        if (resp.attempt && resp.attempt.timelimit) {
                            _quizTimer.timelimit = parseInt(resp.attempt.timelimit);
                        }
                        renderQuizPreguntas(resp.questions, aid, cmid, moduloId, ts);
                    })
                    .fail(function() { actSetBody(actErrHtml('No se pudieron cargar las preguntas.')); });
                })
                .fail(function() { actSetBody(actErrHtml('Error al cargar el cuestionario.')); });
                return;
            }

            $.get('/virtual/modulo/' + moduloId + '/actividad/quiz/' + cmid + '/attempt/' + attemptId)
            .done(function(r) {
                if (!r.success) { actSetBody(actErrHtml(r.message)); return; }
                // Preferencia: timestart del servidor sobre el pasado por parámetro o ahora,
                // para que el cronómetro continúe desde el inicio real del intento.
                var ts = (r.attempt && r.attempt.timestart)
                    ? parseInt(r.attempt.timestart)
                    : (timestart ? parseInt(timestart) : Math.floor(Date.now() / 1000));
                if (r.attempt && r.attempt.timelimit) {
                    _quizTimer.timelimit = parseInt(r.attempt.timelimit);
                }
                renderQuizPreguntas(r.questions, attemptId, cmid, moduloId, ts);
            })
            .fail(function() { actSetBody(actErrHtml('No se pudieron cargar las preguntas.')); });
        }

        function limpiarHtmlPregunta(html) {
            if (!html) return '';
            var el = document.createElement('div');
            el.innerHTML = html;

            // Remove Moodle cruft: flag, redundant header info
            el.querySelectorAll('.questionflag, .questionflagimage, [aria-label*="Marcar"], [aria-label*="Flag"], a[href*="flagquestion"]').forEach(function(e) { e.remove(); });
            el.querySelectorAll('.info > .state, .info > .grade, .info .grade, .info .state').forEach(function(e) { e.remove(); });
            el.querySelectorAll('.questionflag, .formulation .clearer, .accesshide, .qreviewcorrect').forEach(function(e) { e.remove(); });

            // Remove entire .info row if it only has the question number left
            el.querySelectorAll('.info').forEach(function(e) {
                if (e.children.length <= 1) e.remove();
            });

            // Fix input/radio/checkbox styling
            el.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(function(inp) {
                inp.style.position = 'static';
                inp.style.opacity = '1';
                inp.style.width = '1.1em';
                inp.style.height = '1.1em';
                inp.style.margin = '0 .45rem 0 0';
                inp.style.accentColor = '#fc7b04';
                inp.style.cursor = 'pointer';
            });
            el.querySelectorAll('.custom-control, .custom-radio, .custom-checkbox').forEach(function(c) {
                c.style.paddingLeft = '0';
                c.style.position = 'static';
                c.style.display = 'flex';
                c.style.alignItems = 'center';
                c.style.gap = '.5rem';
                c.style.margin = '.4rem 0';
            });
            el.querySelectorAll('label.custom-control-label, .custom-control-label, label').forEach(function(l) {
                l.style.cursor = 'pointer';
                l.style.fontSize = '.85rem';
                l.style.color = '#1e293b';
                l.style.fontWeight = '500';
            });
            el.querySelectorAll('.answer > div, .answer .r0, .answer .r1, .answer .r2, .answer .r3, .answer .r4, .answer .r5, .answer .r6, .answer .r7, .answer .r8, .answer .r9').forEach(function(r) {
                r.style.display = 'flex';
                r.style.alignItems = 'flex-start';
                r.style.gap = '.5rem';
                r.style.margin = '.4rem 0';
                r.style.padding = '.5rem .7rem';
                r.style.background = '#f8fafc';
                r.style.border = '1px solid #eef2f6';
                r.style.borderRadius = '10px';
                r.style.transition = 'all .12s';
            });
            el.querySelectorAll('.prompt').forEach(function(p) {
                p.style.fontWeight = '600';
                p.style.fontSize = '.8rem';
                p.style.color = '#475569';
                p.style.margin = '.55rem 0 .4rem';
            });

            // Clean .formulation spacing
            el.querySelectorAll('.formulation').forEach(function(f) {
                f.style.margin = '0';
                f.style.padding = '0';
            });

            return el.innerHTML;
        }

        function renderQuizPreguntas(questions, attemptId, cmid, moduloId, timestart) {
            var totalQ = questions.length;
            var html = '';

            // Timer header — uses server-side timestart so it persists across refreshes
            if (_quizTimer.timelimit > 0 && timestart) {
                _quizTimer.end = timestart + _quizTimer.timelimit;
                var now = Math.floor(Date.now() / 1000);
                var remaining = Math.max(0, _quizTimer.end - now);
                html += '<div id="quiz-timer-bar" style="display:flex;align-items:center;justify-content:space-between;gap:.75rem;background:linear-gradient(135deg,#1e293b,#2d3a4f);border-radius:14px;padding:.7rem 1.15rem;margin-bottom:1rem;color:#fff;box-shadow:0 4px 12px rgba(0,0,0,.15);">' +
                    '<div style="display:flex;align-items:center;gap:.55rem;">' +
                    '<i class="ri-timer-line" style="font-size:1.15rem;color:#fbbf24;"></i>' +
                    '<span style="font-size:.82rem;font-weight:500;color:#cbd5e1;">Tiempo restante:</span>' +
                    '</div>' +
                    '<div id="quiz-timer-display" style="font-size:1.2rem;font-weight:800;font-variant-numeric:tabular-nums;letter-spacing:1px;font-family:monospace;color:#fbbf24;">' + fmtCountdown(remaining) + '</div>' +
                    '</div>';
            }

            html += '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;background:#f8fafc;border-radius:10px;padding:.55rem .85rem;border:1px solid #e2e8f0;">' +
                '<span style="font-size:.8rem;color:#475569;font-weight:600;display:flex;align-items:center;gap:.4rem;"><i class="ri-list-check" style="color:#fc7b04;"></i> ' + totalQ + ' preguntas</span>' +
                '<span id="quiz-progress-text" style="font-size:.75rem;color:#64748b;font-weight:600;display:flex;align-items:center;gap:.3rem;"><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#16a34a;"></span>0/' + totalQ + ' respondidas</span>' +
                '</div>';

            html += '<div id="quiz-preguntas-wrap">';

            questions.forEach(function(q, idx) {
                var num = idx + 1;
                var maxMark = q.maxmark || 0;

                html += '<div class="quiz-pregunta" data-qidx="' + idx + '" style="background:#fff;border:1.5px solid #e2e8f0;border-radius:14px;padding:1.2rem 1.3rem;margin-bottom:.9rem;border-left:4px solid #fc7b04;box-shadow:0 1px 4px rgba(0,0,0,.03);">' +
                    '<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:.5rem;margin-bottom:.65rem;">' +
                    '<div style="font-weight:700;font-size:.88rem;color:#0f172a;display:flex;align-items:center;gap:.55rem;line-height:1.35;">' +
                    '<span class="quiz-numero" style="background:linear-gradient(135deg,#fc7b04,#e06a00);color:#fff;border-radius:50%;width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:800;flex-shrink:0;box-shadow:0 2px 6px rgba(252,123,4,.3);">' + num + '</span>' +
                    escHtml(q.questionname || 'Pregunta ' + num) + '</div>' +
                    (maxMark > 0 ? '<span style="font-size:.68rem;color:#64748b;white-space:nowrap;font-weight:600;background:#f1f5f9;padding:.2rem .6rem;border-radius:20px;border:1px solid #e2e8f0;"><i class="ri-star-s-line" style="font-size:.65rem;"></i> ' + maxMark + ' pts</span>' : '') +
                    '</div>';

                if (q.html) {
                    var cleaned = limpiarHtmlPregunta(q.html);
                    html += '<div class="quiz-pregunta-html" data-slot="' + q.slot + '" data-seq="' + (q.sequencecheck || '') + '">' + cleaned + '</div>';
                }

                html += '</div>';
            });

            html += '</div>';

            html += '<div id="quiz-msg" style="margin-bottom:.75rem;"></div>';

            html += '<div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap;border-top:1px solid #e2e8f0;padding-top:1.2rem;margin-top:.6rem;">' +
                '<button onclick="guardarQuiz(' + attemptId + ',' + cmid + ',' + moduloId + ',false)" ' +
                'style="background:#fff;color:#334155;border:1.5px solid #cbd5e1;border-radius:10px;padding:.7rem 1.5rem;font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:.5rem;transition:all .15s;box-shadow:0 1px 3px rgba(0,0,0,.04);" ' +
                'onmouseenter="this.style.borderColor=\'#fc7b04\';this.style.color=\'#fc7b04\'" onmouseleave="this.style.borderColor=\'#cbd5e1\';this.style.color=\'#334155\'">' +
                '<i class="ri-save-line" style="font-size:1rem;"></i> Guardar respuestas</button>' +
                '<button onclick="guardarQuiz(' + attemptId + ',' + cmid + ',' + moduloId + ',true)" ' +
                'style="background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;border:none;border-radius:10px;padding:.7rem 1.5rem;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:.5rem;box-shadow:0 4px 14px rgba(22,163,74,.3);transition:transform .15s,box-shadow .15s;" ' +
                'onmouseenter="this.style.transform=\'translateY(-1px)\';this.style.boxShadow=\'0 6px 20px rgba(22,163,74,.35)\'" ' +
                'onmouseleave="this.style.transform=\'\';this.style.boxShadow=\'0 4px 14px rgba(22,163,74,.3)\'">' +
                '<i class="ri-check-double-line" style="font-size:1rem;"></i> Finalizar intento</button>' +
                '</div>';

            actSetBody(html);

            // Start timer based on server-side timestart
            if (_quizTimer.timelimit > 0 && timestart) {
                iniciarTimer(attemptId, cmid, moduloId);
            }

            // Track answered questions
            actualizarContadorRespuestas();
            $(document).on('change', '.quiz-pregunta-html input, .quiz-pregunta-html select, .quiz-pregunta-html textarea', function() {
                actualizarContadorRespuestas();
            });
        }

        function fmtCountdown(secs) {
            if (secs <= 0) return '00:00';
            var h = Math.floor(secs / 3600);
            var m = Math.floor((secs % 3600) / 60);
            var s = secs % 60;
            if (h > 0) return String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
            return String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
        }

        function iniciarTimer(attemptId, cmid, moduloId) {
            detenerTimer();
            _quizTimer.interval = setInterval(function() {
                var now = Math.floor(Date.now() / 1000);
                var left = Math.max(0, _quizTimer.end - now);
                var $disp = $('#quiz-timer-display');
                var $bar = $('#quiz-timer-bar');

                if ($disp.length) {
                    $disp.text(fmtCountdown(left));
                }

                // Warning states
                if ($bar.length) {
                    if (left <= 60) {
                        $bar.css('background', 'linear-gradient(135deg,#991b1b,#7f1d1d)');
                        $disp.css('color', '#fca5a5');
                    } else if (left <= 300) {
                        $bar.css('background', 'linear-gradient(135deg,#92400e,#78350f)');
                        $disp.css('color', '#fde68a');
                    }
                }

                if (left <= 0) {
                    detenerTimer();
                    if ($bar.length) {
                        $bar.html('<div style="display:flex;align-items:center;gap:.5rem;color:#fff;"><i class="ri-timer-flash-line" style="font-size:1.1rem;"></i><span style="font-weight:700;">Tiempo agotado — finalizando intento…</span></div>');
                    }
                    guardarQuiz(attemptId, cmid, moduloId, true, true);
                }
            }, 1000);
        }

        function actualizarContadorRespuestas() {
            var total = $('.quiz-pregunta').length;
            var respondidas = 0;
            $('.quiz-pregunta').each(function() {
                var hasVal = false;
                $(this).find('.quiz-pregunta-html input, .quiz-pregunta-html select, .quiz-pregunta-html textarea').each(function() {
                    var $el = $(this);
                    if ($el.is(':checkbox') || $el.is(':radio')) {
                        if ($el.prop('checked')) { hasVal = true; return false; }
                    } else if ($el.is('select')) {
                        if ($el.val() && $el.val() !== '') { hasVal = true; return false; }
                    } else {
                        if ($el.val() && $el.val().trim() !== '') { hasVal = true; return false; }
                    }
                });
                if (hasVal) respondidas++;
            });
            var $txt = $('#quiz-progress-text');
            if ($txt.length) $txt.text(respondidas + '/' + total + ' respondidas');
        }

        function mostrarConfirmacionFinalizar(attemptId, cmid, moduloId) {
            var overlay = document.createElement('div');
            overlay.id = 'quiz-confirm-overlay';
            overlay.style.cssText = 'position:absolute;inset:0;background:rgba(15,23,42,.55);z-index:9999;display:flex;align-items:center;justify-content:center;padding:1rem;';
            overlay.innerHTML =
                '<div style="background:#fff;border-radius:16px;max-width:400px;width:100%;padding:1.5rem;box-shadow:0 20px 60px rgba(0,0,0,.2);text-align:center;">' +
                '<div style="width:52px;height:52px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .85rem;"><i class="ri-alert-line" style="font-size:1.5rem;color:#dc2626;"></i></div>' +
                '<div style="font-size:1rem;font-weight:700;color:#0f172a;margin-bottom:.3rem;">Finalizar intento</div>' +
                '<div style="font-size:.82rem;color:#64748b;margin-bottom:1.25rem;line-height:1.5;">¿Estás seguro de finalizar el intento?<br>No podrás modificar las respuestas después.</div>' +
                '<div style="display:flex;gap:.65rem;justify-content:center;">' +
                '<button onclick="this.closest(\'#quiz-confirm-overlay\').remove()" style="background:#f1f5f9;color:#334155;border:1.5px solid #cbd5e1;border-radius:10px;padding:.55rem 1.25rem;font-size:.82rem;font-weight:600;cursor:pointer;transition:background .15s;">Cancelar</button>' +
                '<button onclick="ejecutarFinalizarQuiz(' + attemptId + ',' + cmid + ',' + moduloId + ')" style="background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;border:none;border-radius:10px;padding:.55rem 1.25rem;font-size:.82rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;box-shadow:0 4px 12px rgba(220,38,38,.3);">' +
                '<i class="ri-check-double-line"></i> Sí, finalizar</button></div></div>';
            document.getElementById('modal-act-body').appendChild(overlay);
        }

        function ejecutarFinalizarQuiz(attemptId, cmid, moduloId) {
            var o = document.getElementById('quiz-confirm-overlay');
            if (o) o.remove();
            guardarQuiz(attemptId, cmid, moduloId, true, true);
        }

        function guardarQuiz(attemptId, cmid, moduloId, finish, confirmed) {
            if (finish && !confirmed) { mostrarConfirmacionFinalizar(attemptId, cmid, moduloId); return; }

            detenerTimer();
            var $wrap = $('#quiz-preguntas-wrap');
            if (!$wrap.length) return;

            var data = [];
            var slots = [];

            $wrap.find('.quiz-pregunta-html').each(function() {
                var slot = $(this).data('slot');
                if (slot) slots.push(slot);

                $(this).find('input, select, textarea').each(function() {
                    var $el = $(this);
                    var name = $el.attr('name');
                    if (!name) return;
                    var val = '';
                    if ($el.is(':checkbox') || $el.is(':radio')) {
                        if ($el.prop('checked')) val = $el.val();
                        else return;
                    } else if ($el.is('select')) {
                        val = $el.val() || '';
                    } else {
                        val = $el.val();
                    }
                    data.push({ name: name, value: String(val || '') });
                });
            });

            data.push({ name: 'slots', value: slots.join(',') });

            var token = $('meta[name="csrf-token"]').attr('content');
            var $msg = $('#quiz-msg');
            $msg.html('<span style="color:#64748b;font-size:.85rem;"><i class="ri-loader-4-line"></i> Guardando respuestas…</span>');

            $.ajax({
                url:  '/virtual/modulo/' + moduloId + '/actividad/quiz/' + cmid + '/attempt/' + attemptId + '/submit',
                type: 'POST',
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': token },
                data: JSON.stringify({ data: data, finish: finish }),
            })
            .done(function(r) {
                if (!r.success) {
                    $msg.html('<span style="color:#dc2626;font-size:.85rem;"><i class="ri-close-circle-line"></i> ' + escHtml(r.message) + '</span>');
                    return;
                }

                if (finish) {
                    mostrarResultadosQuiz(attemptId, cmid, moduloId, r.attempt_data);
                } else {
                    $msg.html('<span style="color:#16a34a;font-size:.85rem;"><i class="ri-checkbox-circle-line"></i> Respuestas guardadas correctamente.</span>');
                    setTimeout(function() { $msg.html(''); }, 3000);
                }
            })
            .fail(function() {
                $msg.html('<span style="color:#dc2626;font-size:.85rem;"><i class="ri-wifi-off-line"></i> Error de conexión.</span>');
            });
        }

        function mostrarResultadosQuiz(attemptId, cmid, moduloId, attemptData) {
            detenerTimer();
            var totalScore = 0;
            var totalMax = 0;

            var html = '<div style="text-align:center;padding:1.5rem 0 .5rem;">' +
                '<div style="width:64px;height:64px;background:linear-gradient(135deg,#dcfce7,#bbf7d0);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;"><i class="ri-checkbox-circle-fill" style="font-size:2rem;color:#16a34a;"></i></div>' +
                '<div style="font-size:1.2rem;font-weight:800;color:#0f172a;margin-bottom:.2rem;">Intento finalizado</div>' +
                '<div style="font-size:.85rem;color:#64748b;margin-bottom:1.5rem;">Tus respuestas han sido registradas correctamente.</div>';

            if (attemptData && attemptData.length) {
                attemptData.forEach(function(q) {
                    var mark = q.mark;
                    var maxMark = q.maxmark || 0;
                    if (mark !== null && mark !== '-') {
                        totalScore += parseFloat(mark) || 0;
                    }
                    totalMax += maxMark;
                });

                // Score summary
                html += '<div style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:1.5px solid #e2e8f0;border-radius:16px;padding:1.5rem 1rem;margin-bottom:1.25rem;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,.04);">' +
                    '<div style="display:flex;align-items:baseline;justify-content:center;gap:.35rem;">' +
                    '<span style="font-size:2.2rem;font-weight:800;color:#0f172a;">' + totalScore.toFixed(2) + '</span>' +
                    '<span style="font-size:1.2rem;color:#64748b;font-weight:600;">/ ' + totalMax + '</span>' +
                    '</div></div>';

                // Per-question results
                html += '<div style="text-align:left;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;margin-bottom:1rem;box-shadow:0 1px 4px rgba(0,0,0,.03);">';
                html += '<div style="background:#f8fafc;padding:.55rem .85rem;border-bottom:1px solid #e2e8f0;font-size:.72rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Detalle de preguntas</div>';
                attemptData.forEach(function(q, idx) {
                    var mark = q.mark;
                    var maxMark = q.maxmark || 0;
                    var numMark = (mark !== null && mark !== '-') ? parseFloat(mark) : null;
                    var statusColor = numMark !== null ? (numMark >= maxMark * 0.51 ? '#16a34a' : '#dc2626') : '#94a3b8';
                    var statusIcon = numMark !== null ? (numMark >= maxMark * 0.51 ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill') : 'ri-hourglass-line';
                    var bgColor = idx % 2 === 0 ? '#fff' : '#f8fafc';

                    html += '<div style="display:flex;align-items:center;gap:.7rem;padding:.65rem .85rem;border-bottom:1px solid #f1f5f9;background:' + bgColor + ';transition:background .12s;">' +
                        '<span style="color:' + statusColor + ';font-size:1.1rem;flex-shrink:0;"><i class="' + statusIcon + '"></i></span>' +
                        '<div style="flex:1;text-align:left;font-size:.8rem;font-weight:600;color:#0f172a;line-height:1.3;">' + escHtml(q.questionname || 'Pregunta ' + (idx + 1)) + '</div>' +
                        '<span style="font-size:.75rem;font-weight:800;color:' + statusColor + ';white-space:nowrap;background:' + (numMark !== null ? (numMark >= maxMark * 0.51 ? '#f0fdf4' : '#fef2f2') : '#f8fafc') + ';padding:.15rem .55rem;border-radius:8px;">' + (mark !== null && mark !== '-' ? mark : '—') + ' / ' + maxMark + '</span>' +
                        '</div>';
                });
                html += '</div>';
            }

            html += '<div style="text-align:center;margin-top:.5rem;">' +
                '<button onclick="cargarQuiz(' + cmid + ',' + moduloId + ')" ' +
                'style="background:#fff;color:#334155;border:1.5px solid #cbd5e1;border-radius:10px;padding:.65rem 1.65rem;font-size:.85rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;transition:all .15s;box-shadow:0 1px 3px rgba(0,0,0,.04);" ' +
                'onmouseenter="this.style.borderColor=\'#fc7b04\';this.style.color=\'#fc7b04\'" onmouseleave="this.style.borderColor=\'#cbd5e1\';this.style.color=\'#334155\'">' +
                '<i class="ri-arrow-left-line"></i> Volver al cuestionario</button></div>';

            actSetBody(html);
        }

        function infoCard(iconClass, label, value) {
            return '<div style="background:linear-gradient(135deg,#fff,#f8fafc);border:1px solid #e2e8f0;border-radius:12px;padding:.9rem .55rem;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.04);">' +
                '<div style="width:36px;height:36px;background:linear-gradient(135deg,#fef3c7,#ffedd5);border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto .4rem;"><i class="' + iconClass + '" style="font-size:1rem;color:#d97706;"></i></div>' +
                '<div style="font-size:.65rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.6px;font-weight:600;margin-bottom:.15rem;">' + label + '</div>' +
                '<div style="font-size:.85rem;font-weight:800;color:#0f172a;">' + value + '</div></div>';
        }

        @if ($esDocente && $perfilActivo === 'docente')
        document.addEventListener('DOMContentLoaded', function() {
            if (datosHorariosDocente && datosHorariosDocente.length > 0) {
                initCalendarDocente(datosHorariosDocente);
            }
        });
        @endif

        /* ──────────────────────────────────────────────────────────────
           Cambio de foto de perfil (estudiante)
        ────────────────────────────────────────────────────────────── */
        let estFotoArchivoSeleccionado = null;

        function abrirCambioFoto() {
            const modal = new bootstrap.Modal(document.getElementById('estFotoModal'));
            estFotoArchivoSeleccionado = null;
            document.getElementById('estFotoBtnSave').disabled = true;
            const alertEl = document.getElementById('estFotoAlert');
            alertEl.classList.add('d-none');
            alertEl.textContent = '';
            document.getElementById('estFotoInput').value = '';
            const headerImg = document.getElementById('est-hero-avatar-img');
            const previewImg = document.getElementById('estFotoPreview');
            if (headerImg && previewImg) previewImg.src = headerImg.src;
            modal.show();
        }
        window.abrirCambioFoto = abrirCambioFoto;

        (function() {
            const inputFile = document.getElementById('estFotoInput');
            const btnSave   = document.getElementById('estFotoBtnSave');
            const preview   = document.getElementById('estFotoPreview');
            const alertEl   = document.getElementById('estFotoAlert');
            if (!inputFile || !btnSave) return;

            function mostrarAlerta(tipo, msg) {
                alertEl.className = 'alert mt-3 mb-0 alert-' + tipo;
                alertEl.textContent = msg;
                alertEl.classList.remove('d-none');
            }

            inputFile.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                if (!['image/jpeg','image/jpg','image/png'].includes(file.type)) {
                    mostrarAlerta('danger', 'Formato no válido. Solo JPG, JPEG o PNG.');
                    btnSave.disabled = true;
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    mostrarAlerta('danger', 'La imagen no debe superar 2 MB.');
                    btnSave.disabled = true;
                    return;
                }
                alertEl.classList.add('d-none');
                estFotoArchivoSeleccionado = file;
                const reader = new FileReader();
                reader.onload = e => { preview.src = e.target.result; };
                reader.readAsDataURL(file);
                btnSave.disabled = false;
            });

            btnSave.addEventListener('click', async function() {
                if (!estFotoArchivoSeleccionado) return;
                btnSave.disabled = true;
                btnSave.innerHTML = '<i class="ri-loader-2-line ri-spin"></i> Subiendo...';
                const fd = new FormData();
                fd.append('foto', estFotoArchivoSeleccionado);
                fd.append('_token', '{{ csrf_token() }}');
                try {
                    const res = await fetch('{{ route('admin.profile.upload-foto') }}', {
                        method: 'POST',
                        body: fd,
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    });
                    const data = await res.json();
                    if (!data.success) {
                        mostrarAlerta('danger', data.message || 'No se pudo actualizar la foto.');
                        btnSave.disabled = false;
                        btnSave.innerHTML = '<i class="ri-save-line"></i> Guardar Foto';
                        return;
                    }
                    const nuevaUrl = data.url + '?t=' + Date.now();
                    ['est-ci-foto-img', 'doc-ci-foto-img', 'est-hero-avatar-img',
                     'est-nav-avatar-img', 'est-nav-tud-avatar-img']
                        .forEach(id => {
                            const el = document.getElementById(id);
                            if (el) el.src = nuevaUrl;
                        });
                    mostrarAlerta('success', data.message || 'Foto actualizada.');
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('estFotoModal'));
                        if (modal) modal.hide();
                        btnSave.innerHTML = '<i class="ri-save-line"></i> Guardar Foto';
                    }, 900);
                } catch (e) {
                    mostrarAlerta('danger', 'Error de red. Intenta nuevamente.');
                    btnSave.disabled = false;
                    btnSave.innerHTML = '<i class="ri-save-line"></i> Guardar Foto';
                }
            });
        })();
    </script>
@endsection
