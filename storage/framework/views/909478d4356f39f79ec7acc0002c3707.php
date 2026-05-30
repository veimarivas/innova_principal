<style>
    :root {
        --d-bg: #f5f6fa;
        --d-card: #ffffff;
        --d-card-border: #e9ecef;
        --d-title: #2c3e50;
        --d-body: #495057;
        --d-muted: #6c757d;
        --d-primary: #fc7b04;
        --d-primary-dark: #9a4904;
        --d-primary-soft: rgba(154, 73, 4, 0.06);
        --d-primary-light: rgba(252, 123, 4, 0.12);
    }

    body { background: var(--d-bg); }

    @keyframes fadeInUpMd {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .modulo-detalle-page { animation: fadeInUpMd 0.38s ease-out; }

    /* ═══ HEADER ═══ */
    .modulo-detalle-header {
        background: linear-gradient(135deg, #9a4904 0%, #7a3b03 50%, #c96004 100%);
        padding: 1.5rem 0;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .modulo-detalle-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 20% 50%, rgba(255,255,255,.06) 0%, transparent 60%);
        pointer-events: none;
    }

    .mdh-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        position: relative;
    }

    .mdh-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .mdh-icon-wrap {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: rgba(255,255,255,.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #fff;
        backdrop-filter: blur(4px);
    }

    .mdh-text-block h1 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }

    .modulo-badge-display {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.25rem;
        padding: 0.2rem 0.75rem;
        background: rgba(255,255,255,.15);
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #fff;
    }

    .modulo-badge-display .color-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        box-shadow: 0 0 0 2px rgba(255,255,255,.3);
    }

    .mdh-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.25);
        color: #fff;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.2s;
    }

    .mdh-btn-back:hover {
        background: rgba(255,255,255,.22);
        color: #fff;
        transform: translateY(-1px);
    }

    /* ═══ INFO GRID ═══ */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .info-card {
        background: var(--d-card);
        border: 1px solid var(--d-card-border);
        border-radius: 12px;
        padding: 1rem 1.15rem;
        transition: box-shadow .2s, transform .2s;
    }
    .info-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,.06);
        transform: translateY(-2px);
    }

    .info-card label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--d-muted);
        display: block;
        margin-bottom: 0.25rem;
    }
    .info-card label i { color: var(--d-primary); }

    .info-card .value {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--d-body);
    }

    /* ═══ TABS ═══ */
    .tabs-container {
        background: var(--d-card);
        border: 1px solid #e9ecef;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,.04);
    }

    .tabs-header {
        display: flex;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(to bottom, #fafbfc, #f5f6fa);
        padding: 0 .25rem;
        gap: .15rem;
    }

    .tab-btn {
        padding: .82rem 1.25rem;
        font-size: .82rem;
        font-weight: 600;
        color: #64748b;
        background: none;
        border: none;
        cursor: pointer;
        position: relative;
        transition: all .2s;
        display: flex;
        align-items: center;
        gap: .45rem;
        border-radius: 10px 10px 0 0;
        margin-top: 4px;
    }

    .tab-btn:hover {
        color: #9a4904;
        background: rgba(154, 73, 4, .06);
    }

    .tab-btn.active {
        color: #9a4904;
        background: #fff;
        box-shadow: 0 -2px 4px rgba(0,0,0,.04);
    }

    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: .5rem;
        right: .5rem;
        height: 3px;
        background: linear-gradient(90deg, #fc7b04, #c96004);
        border-radius: 3px 3px 0 0;
    }

    .tab-content { padding: 1.5rem; }
    .tab-pane { display: none; }
    .tab-pane.active { display: block; }

    .tab-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--d-card-border);
    }

    .tab-title-section {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tab-title-section i { color: #fc7b04; font-size: 1.1rem; }
    .tab-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--d-title);
    }

    .tab-actions-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* ═══ BUTTONS ═══ */
    .btn-matricular {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #9a4904 0%, #c96004 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-matricular:hover {
        background: linear-gradient(135deg, #7a3b03 0%, #9a4904 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(154, 73, 4, .25);
    }
    .btn-matricular:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .btn-matricular-moodle {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #fc7b04 0%, #d46604 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-matricular-moodle:hover {
        background: linear-gradient(135deg, #d46604 0%, #b85503 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(252, 123, 4, .3);
    }
    .btn-matricular-moodle:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* ═══ TABLES ═══ */
    .table-matriculas {
        width: 100%;
        border-collapse: collapse;
    }

    .table-matriculas thead th {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #fff;
        text-align: left;
        padding: 0.75rem 0.5rem;
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    }
    .table-matriculas thead th:first-child { border-radius: 8px 0 0 0; }
    .table-matriculas thead th:last-child  { border-radius: 0 8px 0 0; }

    .table-matriculas tbody td {
        font-size: 0.85rem;
        color: var(--d-body);
        padding: 0.75rem 0.5rem;
        border-bottom: 1px solid var(--d-card-border);
    }

    .table-matriculas tbody tr { transition: background .1s; }
    .table-matriculas tbody tr:nth-child(even) { background: #fafbfc; }
    .table-matriculas tbody tr:hover td { background: rgba(252, 123, 4, 0.035); }

    /* ═══ BADGES ═══ */
    .badge-matriculado {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-matriculado.si {
        background: rgba(34, 197, 94, 0.1);
        color: #16a34a;
    }
    .badge-matriculado.no {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .badge-moodle {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-moodle.activo {
        background: rgba(34, 197, 94, 0.1);
        color: #16a34a;
    }
    .badge-moodle.suspendido {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }
    .badge-moodle.no {
        background: rgba(100, 116, 139, 0.1);
        color: #64748b;
    }

    .btn-matricular-estudiante {
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(252, 123, 4, 0.1);
        color: #c96004;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-matricular-estudiante:hover {
        background: #fc7b04;
        color: #fff;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: var(--d-muted);
    }
    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        display: block;
        opacity: 0.5;
    }

    .btn-moodle-single {
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(252, 123, 4, 0.1);
        color: #c96004;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-moodle-single:hover {
        background: #fc7b04;
        color: #fff;
    }
    .btn-moodle-single:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-suspender {
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-suspender:hover {
        background: #dc2626;
        color: #fff;
    }

    .btn-activar {
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(252, 123, 4, 0.1);
        color: #c96004;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-activar:hover {
        background: #fc7b04;
        color: #fff;
    }
    .btn-suspender:disabled,
    .btn-activar:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* ═══ BADGE CUENTA SISTEMA ═══ */
    .badge-cuenta {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-cuenta.si {
        background: rgba(34, 197, 94, 0.1);
        color: #16a34a;
    }
    .badge-cuenta.no {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .cuenta-ayuda {
        margin-top: 0.4rem;
        padding: 0.4rem 0.55rem;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 6px;
        font-size: 0.7rem;
        color: #9a3412;
        line-height: 1.5;
        display: flex;
        align-items: flex-start;
        gap: 0.3rem;
        max-width: 260px;
    }
    .cuenta-ayuda i {
        flex-shrink: 0;
        margin-top: 1px;
        color: #ea580c;
        font-size: 0.8rem;
    }
    .cuenta-ayuda a {
        color: #c96004;
        font-weight: 700;
        text-decoration: underline;
    }
    .cuenta-ayuda a:hover {
        color: #9a4904;
    }

    /* ═══ ESTADOS GRID ═══ */
    .estados-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }
    .estado-card {
        background: var(--d-bg);
        border-radius: 12px;
        padding: 1.25rem;
        border: 1px solid var(--d-card-border);
    }
    .estado-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .estado-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        background: rgba(252, 123, 4, 0.1);
        color: #fc7b04;
    }
    .estado-card-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--d-title);
    }
    .estado-card-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--d-body);
    }

    .actividad-placeholder {
        text-align: center;
        padding: 3rem;
        color: var(--d-muted);
    }
    .actividad-placeholder i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.3;
        display: block;
    }

    /* ═══ TAB ACTIVIDADES ═══ */
    .act-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--d-card-border);
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .act-resumen {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .act-stat {
        background: var(--d-bg);
        border: 1px solid var(--d-card-border);
        border-radius: 10px;
        padding: 0.85rem 1rem;
        text-align: center;
        transition: box-shadow .15s;
    }
    .act-stat:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }
    .act-stat-icon { font-size: 1.4rem; margin-bottom: 0.2rem; }
    .act-stat-val  { font-size: 1.6rem; font-weight: 700; color: var(--d-body); line-height: 1; }
    .act-stat-lbl  { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: var(--d-muted); margin-top: 0.15rem; }

    .seccion-card  { border: 1px solid var(--d-card-border); border-radius: 10px; margin-bottom: 0.65rem; overflow: hidden; }

    .seccion-hdr {
        padding: 0.7rem 1rem;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
    }
    .seccion-hdr:hover { background: #f0f1f5; }

    .seccion-nombre { font-size: 0.875rem; font-weight: 700; color: var(--d-title); display: flex; align-items: center; gap: 0.5rem; }

    .seccion-toggle { font-size: 0.9rem; color: var(--d-muted); transition: transform 0.2s; }
    .seccion-hdr.open .seccion-toggle { transform: rotate(180deg); }

    .seccion-body { display: none; }
    .seccion-body.open { display: block; }

    .seccion-descripcion {
        padding: 0.75rem 1rem;
        font-size: 0.8rem;
        color: var(--d-body);
        line-height: 1.6;
        background: rgba(252, 123, 4, 0.035);
        border-bottom: 1px solid var(--d-card-border);
    }
    .seccion-descripcion img { max-width: 100%; height: auto; border-radius: 6px; margin: 0.5rem 0; }
    .seccion-descripcion p { margin: 0.5rem 0; }
    .seccion-descripcion p:first-child { margin-top: 0; }
    .seccion-descripcion p:last-child { margin-bottom: 0; }
    .seccion-descripcion a { color: #fc7b04; text-decoration: underline; }

    .act-item {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 0.55rem 1rem;
        border-top: 1px solid var(--d-card-border);
        gap: 0.5rem;
    }
    .act-item:hover { background: rgba(252, 123, 4, 0.025); }

    .act-item-left { display: flex; align-items: flex-start; gap: 0.65rem; }
    .act-item-left .act-icon { margin-top: 2px; }

    .act-icon {
        width: 30px; height: 30px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    .act-icon.assign  { background: rgba(99,102,241,0.12); color: #6366f1; }
    .act-icon.quiz    { background: rgba(245,158,11,0.12); color: #d97706; }
    .act-icon.forum   { background: rgba(34,197,94,0.12);  color: #16a34a; }
    .act-icon.resource{ background: rgba(239,68,68,0.12);  color: #dc2626; }
    .act-icon.url     { background: rgba(14,165,233,0.12); color: #0284c7; }
    .act-icon.page    { background: rgba(156,163,175,0.12);color: #6b7280; }
    .act-icon.label   { background: rgba(156,163,175,0.08);color: #9ca3af; }
    .act-icon.default { background: rgba(156,163,175,0.1); color: #9ca3af; }

    .act-name  { font-size: 0.83rem; font-weight: 600; color: var(--d-body); }
    .act-tipo  { font-size: 0.68rem; color: var(--d-muted); }
    .act-duedate { margin-top: 2px; }
    .act-duedate div { font-size: 0.75rem; color: var(--d-muted); margin-top: 2px; }
    .act-duedate div i { margin-right: 3px; }

    .act-dates-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        margin-top: 5px;
    }
    .act-date-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
        border: 1px solid transparent;
    }
    .act-date-open {
        background: rgba(14,165,233,.1);
        color: #0369a1;
        border-color: rgba(14,165,233,.3);
    }
    .act-date-open.act-date-active {
        background: rgba(34,197,94,.1);
        color: #15803d;
        border-color: rgba(34,197,94,.3);
    }
    .act-date-due {
        background: rgba(252,123,4,.1);
        color: #c96004;
        border-color: rgba(252,123,4,.3);
    }
    .act-date-due.act-date-overdue {
        background: rgba(239,68,68,.1);
        color: #dc2626;
        border-color: rgba(239,68,68,.3);
    }

    .act-actions { display: flex; gap: 0.35rem; flex-shrink: 0; }

    .btn-toggle-contenido {
        padding: 0.22rem 0.6rem;
        font-size: 0.7rem;
        border-radius: 6px;
        border: 1px solid var(--d-card-border);
        background: var(--d-bg);
        color: var(--d-muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    .btn-toggle-contenido:hover {
        border-color: #fc7b04;
        color: #fc7b04;
    }
    .btn-toggle-contenido.expanded {
        background: rgba(252, 123, 4, 0.08);
        border-color: #fc7b04;
        color: #fc7b04;
    }

    .act-contenido {
        width: 100%;
        padding: 0.75rem 1rem;
        background: var(--d-card-bg);
        border-top: 1px dashed var(--d-card-border);
        font-size: 0.8rem;
        color: var(--d-body);
        line-height: 1.5;
    }
    .act-contenido img { max-width: 100%; height: auto; border-radius: 6px; margin: 0.5rem 0; }
    .act-contenido p { margin: 0.5rem 0; }
    .act-contenido p:first-child { margin-top: 0; }
    .act-contenido p:last-child { margin-bottom: 0; }
    .act-contenido ul, .act-contenido ol { margin: 0.5rem 0; padding-left: 1.25rem; }
    .act-contenido a { color: #fc7b04; text-decoration: underline; }

    .act-label-content {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        color: var(--d-body);
        line-height: 1.6;
        background: rgba(156,163,175,0.05);
        border-left: 3px solid #9ca3af;
    }
    .act-label-content img { max-width: 100%; height: auto; border-radius: 4px; margin: 0.5rem 0; }

    .btn-act-link {
        padding: 0.22rem 0.6rem;
        font-size: 0.72rem;
        font-weight: 600;
        border-radius: 5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        border: none;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-act-moodle { background: rgba(252, 123, 4, 0.1); color: #fc7b04; }
    .btn-act-moodle:hover { background: #fc7b04; color: #fff; }
    .btn-act-disc { background: rgba(34, 197, 94, 0.1); color: #16a34a; }
    .btn-act-disc:hover { background: #16a34a; color: #fff; }

    .seccion-vacia {
        padding: 0.75rem 1rem;
        font-size: 0.8rem;
        color: var(--d-muted);
        border-top: 1px solid var(--d-card-border);
    }

    .act-label-content {
        padding: 0.65rem 1rem;
        border-top: 1px solid var(--d-card-border);
        line-height: 1.65;
        font-size: 0.85rem;
        word-break: break-word;
        color: var(--d-body);
    }
    .act-label-content img { max-width: 100%; height: auto; border-radius: 6px; margin: 0.3rem 0; display: block; }
    .act-label-content p { margin-bottom: 0.4rem; }
    .act-label-content p:last-child { margin-bottom: 0; }
    .act-label-content ul, .act-label-content ol { padding-left: 1.4rem; margin-bottom: 0.4rem; }
    .act-label-content li { margin-bottom: 0.15rem; }
    .act-label-content a { color: #fc7b04; text-decoration: underline; }
    .act-label-content a:hover { color: #c96004; }
    .act-label-content h1,.act-label-content h2,.act-label-content h3,
    .act-label-content h4,.act-label-content h5,.act-label-content h6 { font-weight: 700; margin: 0.5rem 0 0.25rem; }
    .act-label-content table { width: 100%; border-collapse: collapse; font-size: 0.82rem; margin-bottom: 0.4rem; }
    .act-label-content th, .act-label-content td { border: 1px solid var(--d-card-border); padding: 0.3rem 0.5rem; }
    .act-label-content th { background: rgba(0,0,0,.04); font-weight: 600; }
    html[data-bs-theme="dark"] .act-label-content th { background: rgba(255,255,255,.04); }
    .act-label-content video, .act-label-content audio, .act-label-content iframe { max-width: 100%; border-radius: 6px; border: none; margin: 0.3rem 0; }

    .act-loading { text-align: center; padding: 2.5rem; color: var(--d-muted); }
    .act-loading i { font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.5; animation: spin 1s linear infinite; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    .foros-section { margin-top: 1.25rem; }
    .foros-title   { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: var(--d-muted); margin-bottom: 0.65rem; }

    .foro-card {
        border: 1px solid #fde6cd;
        border-radius: 10px;
        margin-bottom: 0.65rem;
        overflow: hidden;
    }
    .foro-card-hdr {
        background: rgba(252, 123, 4, 0.06);
        padding: 0.65rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .foro-card-name { font-size: 0.85rem; font-weight: 700; color: #9a4904; display: flex; align-items: center; gap: 0.4rem; }
    .foro-card-actions { display: flex; gap: 0.35rem; }

    /* ═══ MODAL DISCUSIONES ═══ */
    .disc-modal-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 9000;
        align-items: center;
        justify-content: center;
    }
    .disc-modal-overlay.open { display: flex; }

    .disc-modal {
        background: #fff;
        border-radius: 14px;
        width: 100%;
        max-width: 620px;
        max-height: 80vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        margin: 1rem;
    }
    .disc-modal-hdr {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--d-card-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .disc-modal-title { font-size: 0.95rem; font-weight: 700; color: var(--d-title); }
    .disc-modal-close { background: none; border: none; font-size: 1.2rem; color: var(--d-muted); cursor: pointer; }
    .disc-modal-close:hover { color: var(--d-body); }
    .disc-modal-body { padding: 1rem 1.25rem; overflow-y: auto; flex: 1; }
    .disc-modal-footer {
        padding: 0.75rem 1.25rem;
        border-top: 1px solid var(--d-card-border);
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    .disc-item { padding: 0.65rem 0; border-bottom: 1px solid var(--d-card-border); }
    .disc-item:last-child { border-bottom: none; }
    .disc-item-name { font-size: 0.85rem; font-weight: 600; color: var(--d-body); }
    .disc-item-meta { font-size: 0.72rem; color: var(--d-muted); margin-top: 0.1rem; }
    .disc-empty { text-align: center; padding: 1.5rem; color: var(--d-muted); font-size: 0.85rem; }

    .nueva-disc-form { display: flex; flex-direction: column; gap: 0.75rem; }
    .nueva-disc-form label { font-size: 0.78rem; font-weight: 700; color: var(--d-muted); text-transform: uppercase; letter-spacing: 0.4px; display: block; margin-bottom: 0.25rem; }
    .nueva-disc-form input,
    .nueva-disc-form textarea {
        width: 100%;
        padding: 0.55rem 0.75rem;
        border: 1px solid var(--d-card-border);
        border-radius: 8px;
        font-size: 0.85rem;
        color: var(--d-body);
        outline: none;
        transition: border-color 0.15s;
        box-sizing: border-box;
    }
    .nueva-disc-form input:focus,
    .nueva-disc-form textarea:focus { border-color: #fc7b04; box-shadow: 0 0 0 3px rgba(252, 123, 4, 0.12); }

    .btn-guardar-disc {
        padding: 0.5rem 1.25rem;
        background: linear-gradient(135deg, #fc7b04 0%, #d46604 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-guardar-disc:hover {
        background: linear-gradient(135deg, #d46604 0%, #b85503 100%);
        box-shadow: 0 4px 12px rgba(252, 123, 4, .25);
    }
    .btn-guardar-disc:disabled { opacity: 0.5; cursor: not-allowed; }

    .btn-cancel-disc {
        padding: 0.5rem 1rem;
        background: var(--d-bg);
        color: var(--d-muted);
        border: 1px solid var(--d-card-border);
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-cancel-disc:hover { background: #e9ecef; }

    /* ═══ TAB ACADÉMICO ═══ */
    .academico-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: .75rem;
        margin-bottom: 1.25rem;
    }
    .academico-summary-card {
        background: linear-gradient(135deg, var(--d-card) 0%, #fafbfc 100%);
        border: 1px solid var(--d-card-border);
        border-radius: 10px;
        padding: .85rem 1rem;
        display: flex;
        align-items: center;
        gap: .75rem;
        transition: box-shadow .2s, transform .2s;
    }
    .academico-summary-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,.05);
        transform: translateY(-1px);
    }
    .academico-summary-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .academico-summary-icon.primary { background: rgba(252,123,4,.1); color: #fc7b04; }
    .academico-summary-icon.success { background: rgba(22,163,74,.1); color: #16a34a; }
    .academico-summary-icon.info    { background: rgba(99,102,241,.1); color: #6366f1; }
    .academico-summary-icon.warning { background: rgba(245,158,11,.1); color: #d97706; }
    .academico-summary-val {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--d-title);
        line-height: 1;
    }
    .academico-summary-lbl {
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
        color: var(--d-muted);
        margin-top: 2px;
    }

    .academico-tbl-wrap {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,.04);
    }
    .academico-tbl {
        width: 100%;
        border-collapse: collapse;
        font-size: .83rem;
    }
    .academico-tbl thead th {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: #f1f5f9;
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: .7rem .65rem;
        border-bottom: none;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .academico-tbl thead th:first-child { border-radius: 0; }
    .academico-tbl thead th:last-child  { border-radius: 0; }
    .academico-tbl tbody td {
        padding: .7rem .65rem;
        border-bottom: 1px solid #f1f5f9;
        color: var(--d-body);
        vertical-align: middle;
        transition: background .12s;
    }
    .academico-tbl tbody tr { transition: background .12s; }
    .academico-tbl tbody tr:nth-child(even) { background: #fafbfc; }
    .academico-tbl tbody tr:hover td { background: rgba(252,123,4,.035); }
    .academico-tbl tbody tr:last-child td { border-bottom: none; }

    .academico-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fc7b04, #c96004);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .72rem;
        font-weight: 700;
        flex-shrink: 0;
        letter-spacing: .03em;
    }
    .academico-name {
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .academico-name-text {
        font-weight: 600;
        color: var(--d-title);
        font-size: .87rem;
    }
    .academico-name-sub {
        font-size: .7rem;
        color: var(--d-muted);
        margin-top: 1px;
    }

    .academico-ci {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .2rem .55rem;
        background: rgba(100,116,139,.08);
        border-radius: 6px;
        font-size: .78rem;
        font-weight: 600;
        color: #475569;
        letter-spacing: .3px;
        font-family: 'Courier New', monospace;
    }
    .academico-ci i { font-size: .75rem; color: var(--d-muted); }

    .academico-plan {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .22rem .6rem;
        border-radius: 6px;
        font-size: .73rem;
        font-weight: 700;
        background: rgba(252,123,4,.1);
        color: #c96004;
        border: 1px solid rgba(252,123,4,.2);
    }
    .academico-plan i { font-size: .78rem; }

    .academico-contact {
        display: flex;
        flex-direction: column;
        gap: 2px;
        font-size: .8rem;
    }
    .academico-contact-item {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        color: var(--d-body);
    }
    .academico-contact-item i {
        font-size: .75rem;
        color: var(--d-muted);
        width: 14px;
        text-align: center;
    }

    .academico-estudios-tip {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .2rem .6rem .2rem .45rem;
        background: rgba(252,123,4,.06);
        border: 1px solid rgba(252,123,4,.15);
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 600;
        color: #9a4904;
        cursor: help;
        transition: all .15s;
        max-width: 200px;
    }
    .academico-estudios-tip:hover {
        background: rgba(252,123,4,.1);
        border-color: rgba(252,123,4,.3);
    }
    .academico-estudios-tip i { font-size: .82rem; color: #9a4904; flex-shrink: 0; }
    .academico-estudios-tip span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .academico-estudios-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px; height: 18px;
        border-radius: 50%;
        background: #fc7b04;
        color: #fff;
        font-size: .6rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .academico-btn-ver {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: rgba(252,123,4,.1);
        color: #c96004;
        border-radius: 8px;
        font-size: .95rem;
        text-decoration: none;
        transition: all .15s;
        border: 1px solid transparent;
    }
    .academico-btn-ver:hover {
        background: linear-gradient(135deg, #fc7b04, #d46604);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(252,123,4,.25);
    }

    .academico-empty {
        text-align: center;
        padding: 2.5rem;
        color: var(--d-muted);
    }
    .academico-empty i {
        font-size: 2.5rem;
        display: block;
        margin-bottom: .5rem;
        opacity: .4;
    }

    /* ═══ LIBRO / ACADÉMICO ═══ */
    .libro-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--d-card-border);
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .btn-cargar-libro {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 1rem;
        background: linear-gradient(135deg, #fc7b04 0%, #d46604 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.18s;
        box-shadow: 0 2px 6px rgba(252, 123, 4, .2);
    }
    .btn-cargar-libro:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(252, 123, 4, .3);
    }

    .nota-cell {
        text-align: center;
        font-weight: 600;
        color: var(--d-body);
    }
    .nota-cell.sin-nota { color: #94a3b8; font-weight: 400; }

    .nota-final-cell {
        text-align: center;
        font-weight: 700;
        font-size: 0.9rem;
        background: rgba(252, 123, 4, 0.08);
        color: #9a4904;
        min-width: 80px;
    }
    .nota-final-cell.sin-nota { color: #94a3b8; font-weight: 400; background: transparent; }

    /* ═══ PONDERACIONES PANEL ═══ */
    .pond-panel {
        border: 1px solid var(--d-card-border);
        border-radius: 12px;
        margin-bottom: 1.25rem;
        overflow: hidden;
    }
    .pond-panel-hdr {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.7rem;
        padding: 0.8rem 1.1rem;
        background: linear-gradient(135deg, rgba(252, 123, 4, 0.06), rgba(154, 73, 4, 0.03));
        border-bottom: 1px solid var(--d-card-border);
    }
    .pond-panel-titulo {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .pond-panel-titulo i { color: #fc7b04 !important; font-size: 1rem; }
    .pond-panel-titulo strong { font-size: 0.85rem; color: var(--d-title); }
    .pond-panel-titulo small { font-size: 0.75rem; color: var(--d-muted); }
    .pond-controls { display: flex; align-items: center; gap: 0.7rem; flex-wrap: wrap; }

    .switch-wrap {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--d-body);
        cursor: pointer;
        user-select: none;
    }
    .switch-wrap input[type="checkbox"] {
        width: 36px;
        height: 20px;
        appearance: none;
        background: #cbd5e1;
        border-radius: 10px;
        position: relative;
        cursor: pointer;
        transition: background .2s;
        flex-shrink: 0;
    }
    .switch-wrap input[type="checkbox"]::after {
        content: '';
        position: absolute;
        top: 2px; left: 2px;
        width: 16px; height: 16px;
        border-radius: 50%;
        background: #fff;
        transition: transform .2s;
        box-shadow: 0 1px 3px rgba(0,0,0,.15);
    }
    .switch-wrap input[type="checkbox"]:checked {
        background: #fc7b04;
    }
    .switch-wrap input[type="checkbox"]:checked::after {
        transform: translateX(16px);
    }

    .suma-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.28rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1.5px solid;
        transition: all .25s;
    }
    .suma-badge.ok {
        background: rgba(34, 197, 94, 0.1);
        color: #16a34a;
        border-color: rgba(34, 197, 94, 0.25);
    }
    .suma-badge.bad {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border-color: rgba(239, 68, 68, 0.25);
    }

    .pond-table-wrap { overflow-x: auto; }
    .pond-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.83rem;
    }
    .pond-table thead th {
        background: #f8f9fa;
        color: var(--d-muted);
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 0.55rem 0.75rem;
        border-bottom: 2px solid var(--d-card-border);
        text-align: left;
    }
    .pond-table tbody td {
        padding: 0.5rem 0.75rem;
        border-bottom: 1px solid var(--d-card-border);
        vertical-align: middle;
    }
    .pond-table tbody tr { transition: background .1s; }
    .pond-table tbody tr:nth-child(even) { background: #fafbfc; }
    .pond-table tbody tr:hover { background: rgba(252, 123, 4, 0.035); }

    .pond-tipo-badge {
        display: inline-block;
        padding: 0.15rem 0.5rem;
        border-radius: 5px;
        font-size: 0.68rem;
        font-weight: 700;
    }

    .peso-input {
        width: 72px;
        padding: 0.28rem 0.35rem;
        border: 1.5px solid #fde6cd;
        border-radius: 7px;
        font-size: 0.8rem;
        font-weight: 700;
        text-align: center;
        color: #9a4904;
        background: #fff8f0;
        transition: all .15s;
    }
    .peso-input:hover { border-color: #fc7b04; }
    .peso-input:focus {
        outline: none;
        border-color: #fc7b04;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(252, 123, 4, 0.12);
    }

    .btn-guardar-pesos {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 1rem;
        background: linear-gradient(135deg, #fc7b04 0%, #d46604 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.18s;
    }
    .btn-guardar-pesos:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(252, 123, 4, .25);
    }
    .btn-guardar-pesos:disabled { opacity: 0.45; cursor: not-allowed; transform: none; }

    .pond-badge-ro {
        display: inline-block;
        padding: 0.12rem 0.5rem;
        background: rgba(252, 123, 4, 0.1);
        color: #9a4904;
        border-radius: 5px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .th-act { text-align: center; vertical-align: middle; }
    .act-th-name { display: block; font-size: 0.72rem; font-weight: 700; color: var(--d-body); margin-bottom: 0.2rem; }
    .act-th-mod  { font-size: 0.65rem; color: var(--d-muted); }

    .seccion-divider {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: var(--d-muted);
        margin: 1.25rem 0 0.75rem;
        padding-bottom: 0.35rem;
        border-bottom: 1px solid var(--d-card-border);
    }
    .seccion-divider i { color: #fc7b04; font-size: 1rem; }

    .libro-table-wrap { overflow-x: auto; border: 1px solid var(--d-card-border); border-radius: 10px; }
    .libro-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
    }
    .libro-table thead th {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: #f1f5f9;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.65rem 0.5rem;
        white-space: nowrap;
        text-align: center;
        border-bottom: none;
    }
    .libro-table tbody td {
        padding: 0.55rem 0.5rem;
        border-bottom: 1px solid #f1f5f9;
        text-align: center;
    }
    .libro-table tbody tr { transition: background .1s; }
    .libro-table tbody tr:nth-child(even) { background: #fafbfc; }
    .libro-table tbody tr:hover { background: rgba(252, 123, 4, 0.035); }

    .libro-loading { text-align: center; padding: 2.5rem; color: var(--d-muted); }
    .libro-loading i { font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.5; animation: spin 1s linear infinite; }

    .libro-msg { text-align: center; padding: 2rem; color: var(--d-muted); font-size: 0.85rem; }
    .libro-msg i { font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.35; }

    /* ═══ CENTRALIZADOR ═══ */
    .centr-pond-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .7rem;
        background: linear-gradient(135deg, rgba(252, 123, 4, 0.06), rgba(154, 73, 4, 0.03));
        border: 1px solid #fde6cd;
        border-left: 4px solid #fc7b04;
        border-radius: 0 10px 10px 0;
        padding: .8rem 1.1rem;
        margin-bottom: 1rem;
    }
    .centr-pond-bar-left {
        display: flex;
        align-items: center;
        gap: .5rem;
        flex-wrap: wrap;
    }
    .centr-pond-bar-left i { color: #fc7b04; font-size: 1rem; }
    .centr-pond-bar-left strong { font-size: .85rem; color: var(--d-title); }
    .centr-pond-bar-left small { font-size: .75rem; color: var(--d-muted); }
    .centr-pond-bar-right { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }

    .centr-suma-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .32rem .85rem;
        border-radius: 20px;
        border: 1.5px solid #e2e8f0;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .01em;
        transition: all .25s;
        white-space: nowrap;
    }

    .centr-btn-save {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .42rem 1rem;
        background: linear-gradient(135deg, #fc7b04 0%, #d46604 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: .81rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .18s;
        white-space: nowrap;
    }
    .centr-btn-save:hover:not(:disabled) {
        background: linear-gradient(135deg, #d46604 0%, #b85503 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(252, 123, 4, .3);
    }
    .centr-btn-save:disabled { opacity: .45; cursor: not-allowed; transform: none; }

    .centr-btn-sync {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .42rem 1rem;
        background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: .81rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .18s;
        white-space: nowrap;
    }
    .centr-btn-sync:hover:not(:disabled) {
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15,118,110,.3);
    }
    .centr-btn-sync:disabled { opacity: .45; cursor: not-allowed; transform: none; }

    .centr-btn-export {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .42rem 1rem;
        background: rgba(22,163,74,.1);
        color: #15803d;
        border: 1px solid rgba(22,163,74,.3);
        border-radius: 8px;
        font-size: .81rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .18s;
        white-space: nowrap;
    }
    .centr-btn-export:hover { background: #16a34a; color: #fff; transform: translateY(-1px); }

    .centr-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 600px;
        font-size: .83rem;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }

    .centr-table thead .centr-thead-r1 th {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: #f1f5f9;
        font-size: .72rem;
        font-weight: 700;
        padding: .7rem .8rem;
        white-space: nowrap;
        letter-spacing: .02em;
        border-bottom: none;
    }
    .centr-table thead .centr-thead-r1 .centr-th-fixed {
        background: linear-gradient(135deg, #9a4904 0%, #c96004 100%);
        color: #fff;
    }
    .centr-table thead .centr-thead-r1 .centr-th-nfinal {
        background: linear-gradient(135deg, #fc7b04 0%, #d46604 100%);
        color: #fff;
    }

    .centr-table thead .centr-thead-r2 th {
        background: #f8f9fa;
        color: var(--d-muted);
        font-size: .68rem;
        padding: .38rem .65rem;
        border-bottom: 1px solid #e9ecef;
        text-align: center;
    }
    .centr-table thead .centr-thead-r3 th {
        background: #fff;
        padding: .4rem .5rem;
        border-bottom: 2px solid #e9ecef;
        text-align: center;
    }

    .centr-peso-input {
        width: 72px;
        padding: .3rem .4rem;
        border: 1.5px solid #fde6cd;
        border-radius: 7px;
        font-size: .8rem;
        font-weight: 700;
        text-align: center;
        color: #9a4904;
        background: #fff8f0;
        transition: all .15s;
    }
    .centr-peso-input:hover { border-color: #fc7b04; }
    .centr-peso-input:focus {
        outline: none;
        border-color: #fc7b04;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(252, 123, 4, .12);
    }

    .centr-table tbody tr { transition: background .1s; }
    .centr-table tbody tr:nth-child(even) { background: #fafbfc; }
    .centr-table tbody tr:hover { background: rgba(252, 123, 4, .035); }
    .centr-table tbody td {
        font-size: .84rem;
        padding: .58rem .75rem;
        border-bottom: 1px solid #f1f5f9;
        color: var(--d-body);
        vertical-align: middle;
    }
    .centr-table tbody tr:last-child td { border-bottom: none; }

    .centr-td-num   { text-align: center; color: var(--d-muted); font-size: .78rem; width: 36px; }
    .centr-td-ci    { text-align: center; font-size: .8rem; color: var(--d-muted); }
    .centr-td-nota  { text-align: center; font-weight: 600; }

    .centr-aprobado  { color: #15803d !important; background: rgba(22,163,74,.08) !important; font-weight: 700 !important; }
    .centr-regular   { color: #b45309 !important; background: rgba(245,158,11,.08) !important; font-weight: 700 !important; }
    .centr-reprobado { color: #dc2626 !important; background: rgba(239,68,68,.08) !important; font-weight: 700 !important; }
    .centr-incompleta {
        background: rgba(234,179,8,.08) !important;
        color: #92400e !important;
        border-left: 3px solid #f59e0b;
    }

    .centr-modo-badge {
        display: inline-block;
        font-size: .6rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
        border: 1px solid;
        margin-top: 4px;
        cursor: default;
        letter-spacing: .03em;
        text-transform: uppercase;
    }

    .centr-leyenda {
        display: flex;
        gap: .4rem;
        flex-wrap: wrap;
        margin-bottom: .9rem;
        align-items: center;
    }
    .centr-leyenda-label {
        font-size: .72rem;
        font-weight: 600;
        color: var(--d-muted);
        margin-right: .2rem;
    }

    .centr-footer-note {
        font-size: .71rem;
        color: var(--d-muted);
        margin-top: .65rem;
        display: flex;
        align-items: flex-start;
        gap: .35rem;
        line-height: 1.5;
    }
    .centr-footer-note i { color: #fc7b04; flex-shrink: 0; margin-top: 1px; }

    .centr-opt-btn {
        width: 100%;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        padding: 1rem 1.15rem;
        cursor: pointer;
        transition: border-color .15s, background .15s, box-shadow .15s;
        text-align: left;
    }
    .centr-opt-btn:hover { transform: translateY(-1px); }
    .centr-opt-ponderar:hover {
        border-color: #fc7b04;
        background: rgba(252, 123, 4, .04);
        box-shadow: 0 4px 16px rgba(252, 123, 4, .12);
    }
    .centr-opt-mantener:hover {
        border-color: #d97706;
        background: rgba(251,191,36,.04);
        box-shadow: 0 4px 16px rgba(217,119,6,.1);
    }

    .btn-ver-curso {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.9rem;
        background: rgba(252, 123, 4, 0.1);
        color: #fc7b04;
        border: 1px solid rgba(252, 123, 4, 0.25);
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-ver-curso:hover { background: #fc7b04; color: #fff; }

    /* ═══ TOAST ═══ */
    .toast-notification {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        border-radius: 8px;
        background: white;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        font-size: 0.9rem;
        font-weight: 500;
        transform: translateX(400px);
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 9999;
    }
    .toast-notification.show {
        transform: translateX(0);
        opacity: 1;
    }
    .toast-notification.success { border-left: 4px solid #16a34a; }
    .toast-notification.success i { color: #16a34a; font-size: 1.2rem; }
    .toast-notification.error { border-left: 4px solid #dc2626; }
    .toast-notification.error i { color: #dc2626; font-size: 1.2rem; }

    /* ═══ MODAL STYLES ═══ */
    #modalConfirmarAcceso .modal-content {
        font-family: 'Inter', sans-serif;
    }
    #modalConfirmarAcceso .btn-cancelar-modal:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }
    #modalConfirmarAcceso .btn-confirmar-modal:hover {
        opacity: 0.9;
    }

    /* ═══ TOOLBAR ═══ */
    .toolbar-editor {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.65rem 1.5rem;
        background: var(--d-bg);
        border-bottom: 1px solid var(--d-card-border);
        flex-wrap: wrap;
    }
    .toolbar-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: var(--d-muted);
        margin-right: 0.5rem;
    }
    .tool-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.65rem;
        border: 1px solid var(--d-card-border);
        border-radius: 6px;
        background: #fff;
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--d-body);
        cursor: pointer;
        transition: all 0.12s;
    }
    .tool-btn:hover {
        background: rgba(252, 123, 4, 0.06);
        border-color: #fc7b04;
        color: #9a4904;
    }
    .tool-btn i { font-size: 0.85rem; }

    .drag-handle {
        color: var(--d-muted);
        cursor: grab;
        font-size: 1rem;
        opacity: 0.4;
        padding: 2px;
        flex-shrink: 0;
    }
    .drag-handle:hover { opacity: 0.8; }

    .seccion-hdr-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
        min-width: 0;
    }
    .seccion-hdr-actions {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        flex-shrink: 0;
    }

    .btn-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        border: 1px solid transparent;
        background: transparent;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        color: var(--d-muted);
        transition: all 0.12s;
    }
    .btn-icon:hover { background: rgba(252, 123, 4, 0.08); color: #9a4904; border-color: var(--d-card-border); }
    .btn-icon.delete:hover { background: rgba(220,38,38,0.08); color: #dc2626; }

    .form-section-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--d-muted);
        margin: 1rem 0 0.5rem;
        padding-bottom: 0.25rem;
        border-bottom: 1px solid var(--d-card-border);
    }
    .checkbox-inline {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.85rem;
        cursor: pointer;
    }
    .checkbox-inline input[type="checkbox"] {
        width: 16px;
        height: 16px;
    }

    .seccion-nombre-input {
        border: 1px solid #fc7b04;
        border-radius: 6px;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--d-title);
        background: #fff;
        width: 100%;
        max-width: 350px;
        outline: none;
    }
    .seccion-nombre-input:focus { box-shadow: 0 0 0 2px rgba(252, 123, 4, 0.2); }

    .desc-editor-toolbar {
        display: flex;
        gap: 2px;
        margin-bottom: 0.35rem;
        flex-wrap: wrap;
    }
    .desc-editor-toolbar button {
        width: 26px; height: 26px;
        border: 1px solid var(--d-card-border);
        border-radius: 4px;
        background: #fff;
        font-size: 0.7rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--d-muted);
    }
    .desc-editor-toolbar button:hover { background: var(--d-bg); color: var(--d-body); }

    .desc-editor {
        border: 1px solid var(--d-card-border);
        border-radius: 8px;
        padding: 0.5rem;
        min-height: 60px;
        font-size: 0.8rem;
        background: #fff;
        outline: none;
    }
    .desc-editor:focus { border-color: #fc7b04; box-shadow: 0 0 0 2px rgba(252, 123, 4, 0.1); }

    .btn-act-edit { background: rgba(252, 123, 4, 0.1); color: #9a4904; }
    .btn-act-edit:hover { background: #fc7b04; color: #fff; }
    .btn-act-delete { background: rgba(220,38,38,0.1); color: #dc2626; }
    .btn-act-delete:hover { background: #dc2626; color: #fff; }

    .wysiwyg-toolbar {
        display: flex;
        gap: 2px;
        padding: 0.35rem;
        background: var(--d-bg);
        border: 1px solid var(--d-card-border);
        border-bottom: none;
        border-radius: 8px 8px 0 0;
        flex-wrap: wrap;
    }
    .wysiwyg-toolbar button {
        width: 28px; height: 28px;
        border: none;
        border-radius: 4px;
        background: transparent;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        color: var(--d-muted);
    }
    .wysiwyg-toolbar button:hover { background: #fff; color: var(--d-body); }

    .wysiwyg-editor {
        border: 1px solid var(--d-card-border);
        border-radius: 0 0 8px 8px;
        padding: 0.75rem;
        min-height: 120px;
        font-size: 0.85rem;
        outline: none;
    }
    .wysiwyg-editor:focus { border-color: #fc7b04; }

    #toastContainer { position: fixed; top: 1rem; right: 1rem; z-index: 99999; }

    @media (max-width: 768px) {
        .toolbar-editor { gap: 0.3rem; padding: 0.5rem 0.75rem; }
        .tool-btn { font-size: 0.68rem; padding: 0.25rem 0.5rem; }
        .seccion-hdr-actions { gap: 0.1rem; }
        .info-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 480px) {
        .info-grid { grid-template-columns: 1fr; }
    }
</style>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/ofertas-academicas/partials/modulo-detalle-styles.blade.php ENDPATH**/ ?>