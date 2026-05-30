@extends('layouts.virtual')
@section('title', 'Mi Módulo - ' . ($modulo->nombre ?? ''))

@section('css')
@include('admin.ofertas-academicas.partials.modulo-detalle-styles')
<style>
/* ── Hero Header ─────────────────────────────────────────── */
.dm-hero {
    position: relative;
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    margin: -2rem -1.25rem 1.75rem;
    overflow: hidden;
}
.dm-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 80% -20%, rgba(252,123,4,.18) 0%, transparent 60%);
    pointer-events: none;
}
.dm-hero-topbar {
    height: 4px;
    width: 100%;
}
.dm-hero-content {
    position: relative;
    z-index: 1;
    padding: 1.75rem 2rem 1.85rem;
    background: linear-gradient(180deg, rgba(255,255,255,.04) 0%, transparent 100%);
    border-bottom: 1px solid rgba(255,255,255,.06);
}
.dm-hero-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.25rem;
}
.dm-hero-breadcrumb {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: .75rem;
    flex-wrap: wrap;
    letter-spacing: .01em;
}
.dm-hero-breadcrumb i { color: rgba(255,255,255,.35); font-size: .82rem; }
.dm-hero-program { font-weight: 600; color: rgba(255,255,255,.8); }
.dm-hero-sep  { color: rgba(255,255,255,.2); }
.dm-hero-codigo { color: rgba(255,255,255,.4); font-size: .7rem; font-weight: 500; letter-spacing: .04em; text-transform: uppercase; }
.dm-btn-back {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .4rem .9rem; border-radius: 8px;
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.14);
    color: rgba(255,255,255,.75); font-size: .78rem; font-weight: 600;
    text-decoration: none; transition: all .25s; white-space: nowrap; flex-shrink: 0;
    backdrop-filter: blur(4px);
}
.dm-btn-back:hover { background: rgba(255,255,255,.14); color: #fff; border-color: rgba(255,255,255,.25); }
.dm-hero-main {
    display: flex;
    align-items: center;
    gap: .9rem;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
}
.dm-hero-num-badge {
    font-size: .68rem; font-weight: 700;
    padding: .28rem .8rem; border-radius: 20px;
    white-space: nowrap; flex-shrink: 0;
    letter-spacing: .03em; text-transform: uppercase;
    backdrop-filter: blur(2px);
}
.dm-hero-dot {
    width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
    box-shadow: 0 0 8px currentColor;
}
.dm-hero-modname {
    font-size: 1.4rem; font-weight: 800;
    color: #fff; margin: 0; line-height: 1.3;
    letter-spacing: -.01em;
    text-shadow: 0 1px 6px rgba(0,0,0,.15);
}
.dm-hero-meta {
    display: flex; align-items: center;
    gap: 1.5rem; flex-wrap: wrap;
    padding-top: .35rem;
}
.dm-hero-meta-item {
    display: flex; align-items: center; gap: .42rem;
    font-size: .78rem; color: rgba(255,255,255,.55);
    letter-spacing: .01em;
}
.dm-hero-meta-item i { font-size: .85rem; opacity: .8; }
.dm-hero-meta-item span { color: rgba(255,255,255,.78); font-weight: 500; }
.dm-hero-moodle-link {
    color: rgba(147,197,253,.9) !important;
    border: 1px solid rgba(147,197,253,.2);
    border-radius: 6px; padding: .2rem .6rem;
    text-decoration: none; transition: all .25s;
    font-weight: 500;
}
.dm-hero-moodle-link:hover {
    color: #93c5fd !important; background: rgba(147,197,253,.1);
    border-color: rgba(147,197,253,.35);
}
/* ── Matriculaciones stats ──────────────────────────────── */
.mat-stats-row {
    display: flex; gap: .85rem; margin-bottom: 1.25rem; flex-wrap: wrap;
}
.mat-stat-item {
    display: flex; align-items: center; gap: .7rem;
    background: #f8fafc; border: 1px solid #e9ecef;
    border-radius: 12px; padding: .8rem 1rem;
    flex: 1; min-width: 120px; transition: box-shadow .2s;
}
.mat-stat-item:hover { box-shadow: 0 4px 14px rgba(0,0,0,.06); }
.mat-stat-icon {
    width: 38px; height: 38px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem; flex-shrink: 0;
}
.mat-stat-icon.s-total      { background:rgba(99,102,241,.12); color:#6366f1; }
.mat-stat-icon.s-matriculado{ background:rgba(34,197,94,.12);  color:#16a34a; }
.mat-stat-icon.s-activo     { background:rgba(59,130,246,.12); color:#2563eb; }
.mat-stat-icon.s-suspendido { background:rgba(239,68,68,.1);   color:#dc2626; }
.mat-stat-val { font-size:1.25rem; font-weight:700; color:#1e293b; line-height:1; }
.mat-stat-lbl { font-size:.67rem; color:#64748b; margin-top:2px; font-weight:500; }
/* ── Search ─────────────────────────────────────────────── */
.mat-search-wrap {
    position: relative; margin-bottom: 1rem;
}
.mat-search-wrap > i {
    position: absolute; left: .85rem; top: 50%;
    transform: translateY(-50%); color: #94a3b8; font-size: .88rem;
    pointer-events: none;
}
.mat-search-input {
    width: 100%; padding: .52rem .9rem .52rem 2.4rem;
    border: 1.5px solid #e2e8f0; border-radius: 10px;
    font-size: .84rem; background: #f8fafc; color: #1e293b;
    transition: all .2s; outline: none;
}
.mat-search-input:focus {
    border-color: #fc7b04; background: #fff;
    box-shadow: 0 0 0 3px rgba(252,123,4,.1);
}
/* ── Enhanced table ─────────────────────────────────────── */
.mat-table-wrap { overflow-x: auto; border-radius: 10px; border: 1px solid #f1f5f9; }
.mat-table { width: 100%; border-collapse: collapse; min-width: 680px; }
.mat-table thead th {
    font-size: .67rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .5px; color: #64748b; text-align: left;
    padding: .62rem .8rem; border-bottom: 2px solid #f1f5f9;
    background: #f8fafc; white-space: nowrap;
}
.mat-table tbody td {
    font-size: .83rem; color: #334155;
    padding: .68rem .8rem; border-bottom: 1px solid #f8fafc;
    vertical-align: middle;
}
.mat-table tr:last-child td { border-bottom: none; }
.mat-table tr:hover td { background: rgba(252,123,4,.025); }
.mat-row-hidden { display: none !important; }
.mat-num { font-size:.72rem; color:#94a3b8; font-weight:600; text-align:center; width:36px; }
.mat-student-cell { display:flex; align-items:center; gap:.6rem; }
.mat-avatar {
    width: 33px; height: 33px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem; font-weight: 700; flex-shrink: 0;
}
.mat-nombre { font-size:.84rem; font-weight:600; color:#1e293b; }
.mat-ci, .mat-cel { white-space:nowrap; font-size:.82rem; color:#475569; }
.mat-correo { font-size:.76rem; color:#64748b; max-width:190px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.mat-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.22rem .58rem; border-radius:6px;
    font-size:.72rem; font-weight:600; white-space:nowrap;
}
.mat-badge-ok      { background:rgba(34,197,94,.1);   color:#16a34a; }
.mat-badge-no      { background:rgba(239,68,68,.1);   color:#dc2626; }
.mat-badge-warn    { background:rgba(245,158,11,.1);  color:#b45309; }
.mat-badge-pending { background:rgba(234,179,8,.12);  color:#92400e; }
.mat-empty-state { text-align:center; padding:2.5rem 1rem; color:#94a3b8; }
.mat-empty-state i { font-size:2.5rem; display:block; margin-bottom:.65rem; opacity:.35; }
.mat-empty-state p { font-size:.85rem; margin:0; }
.mat-section-header {
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:1rem; padding-bottom:.85rem;
    border-bottom:1px solid #f1f5f9;
}
.mat-section-title {
    display:flex; align-items:center; gap:.5rem;
}
.mat-section-title i { color:#fc7b04; font-size:1.05rem; }
.mat-section-title span { font-size:.9rem; font-weight:700; color:#1e293b; }
.mat-readonly-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.22rem .65rem; border-radius:20px; font-size:.7rem; font-weight:600;
    background:rgba(100,116,139,.08); color:#475569;
    border:1px solid rgba(100,116,139,.2);
}
/* ── Design improvements ──────────────────────────────────── */
.dm-page-tabs-container {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 6px rgba(0,0,0,.04);
}
.dm-page-tabs-header {
    display: flex;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbfc;
    padding: 0 .25rem;
    gap: .15rem;
}
.dm-page-tab-btn {
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
.dm-page-tab-btn:hover {
    color: #334155;
    background: rgba(252,123,4,.05);
}
.dm-page-tab-btn.active {
    color: #fc7b04;
    background: #fff;
    box-shadow: 0 -2px 4px rgba(0,0,0,.04);
}
.dm-page-tab-btn.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: .5rem;
    right: .5rem;
    height: 3px;
    background: #fc7b04;
    border-radius: 3px 3px 0 0;
}
.dm-page-tab-content {
    padding: 1.5rem;
}
.dm-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding-bottom: .85rem;
    border-bottom: 1px solid #f1f5f9;
}
.dm-section-title {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: .9rem;
    font-weight: 700;
    color: #1e293b;
}
.dm-section-title i { color: #fc7b04; font-size: 1.05rem; }
.dm-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 1.25rem;
    transition: box-shadow .2s;
}
.dm-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,.06); }
.dm-btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .45rem 1rem;
    border-radius: 8px;
    font-size: .82rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .15s;
    border: none;
    text-decoration: none;
}
.dm-btn-primary {
    background: linear-gradient(135deg, #fc7b04 0%, #e06900 100%);
    color: #fff;
}
.dm-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(252,123,4,.3); }
.dm-btn-outline {
    background: rgba(99,102,241,.1);
    color: #4f46e5;
    border: 1px solid rgba(99,102,241,.3);
}
.dm-btn-outline:hover { background: #4f46e5; color: #fff; }
.dm-btn-success {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: #fff;
}
.dm-btn-success:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(34,197,94,.3); }
.dm-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .83rem;
}
.dm-table thead th {
    font-size: .67rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748b;
    text-align: left;
    padding: .62rem .8rem;
    border-bottom: 2px solid #f1f5f9;
    background: #f8fafc;
}
.dm-table tbody td {
    padding: .68rem .8rem;
    border-bottom: 1px solid #f8fafc;
    color: #334155;
    vertical-align: middle;
}
.dm-table tr:last-child td { border-bottom: none; }
.dm-table tr:hover td { background: rgba(252,123,4,.025); }
</style>
@endsection

@section('content')

@php
    $nombrePrograma = $modulo->ofertaAcademica?->programa?->nombre
        ?? $modulo->ofertaAcademica?->posgrado?->nombre
        ?? 'Sin programa';
    $codigoOferta  = $modulo->ofertaAcademica?->codigo ?? '—';
    $nombreDocente = $modulo->docente?->persona
        ? trim(($modulo->docente->persona->nombres ?? '') . ' '
            . ($modulo->docente->persona->apellido_paterno ?? '') . ' '
            . ($modulo->docente->persona->apellido_materno ?? ''))
        : 'Sin docente asignado';
    $nSesiones     = $modulo->horarios->count();
    $nInscritos    = count($inscritos);
    $modColor      = $modulo->color ?? '#6366f1';
@endphp

{{-- ── Hero Header ── --}}
<div class="dm-hero">
    <div class="dm-hero-topbar" style="background:{{ $modColor }};"></div>
    <div class="dm-hero-content">

        {{-- Fila superior: breadcrumb + botón volver --}}
        <div class="dm-hero-head">
            <div class="dm-hero-breadcrumb">
                <i class="ri-graduation-cap-line"></i>
                <span class="dm-hero-program">{{ $nombrePrograma }}</span>
                <span class="dm-hero-sep">·</span>
                <span class="dm-hero-codigo">{{ $codigoOferta }}</span>
            </div>
            <a href="{{ route('virtual.dashboard') }}" class="dm-btn-back">
                <i class="ri-arrow-left-line"></i> Volver al Portal
            </a>
        </div>

        {{-- Nombre del módulo --}}
        <div class="dm-hero-main">
            <span class="dm-hero-num-badge"
                style="background:{{ $modColor }}22;color:{{ $modColor }};border:1.5px solid {{ $modColor }}45;">
                Módulo {{ $modulo->n_modulo ?? '—' }}
            </span>
            <span class="dm-hero-dot" style="background:{{ $modColor }};"></span>
            <h1 class="dm-hero-modname">{{ $modulo->nombre ?? 'Sin nombre' }}</h1>
        </div>

        {{-- Metadatos: docente, fechas, sesiones, estudiantes, moodle --}}
        <div class="dm-hero-meta">
            <div class="dm-hero-meta-item">
                <i class="ri-user-star-line"></i>
                <span>{{ $nombreDocente }}</span>
            </div>
            @if($modulo->fecha_inicio)
            <div class="dm-hero-meta-item">
                <i class="ri-calendar-line"></i>
                <span>
                    {{ \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') }}
                    @if($modulo->fecha_fin)
                        — {{ \Carbon\Carbon::parse($modulo->fecha_fin)->format('d/m/Y') }}
                    @endif
                </span>
            </div>
            @endif
            <div class="dm-hero-meta-item">
                <i class="ri-time-line"></i>
                <span>{{ $nSesiones }} sesión(es)</span>
            </div>
            <div class="dm-hero-meta-item">
                <i class="ri-group-line"></i>
                <span>{{ $nInscritos }} estudiante(s)</span>
            </div>
            @if($modulo->moodle_course_id)
            <a href="{{ rtrim(config('moodle.url'),'/') }}/course/view.php?id={{ $modulo->moodle_course_id }}"
               target="_blank" class="dm-hero-meta-item dm-hero-moodle-link">
                <i class="ri-external-link-line"></i>
                <span>Curso en Moodle</span>
            </a>
            @endif
        </div>

    </div>
</div>

{{-- Tabs --}}
<div class="tabs-container">
    <div class="tabs-header">
        <button class="tab-btn active" data-tab="matriculaciones">
            <i class="ri-user-follow-line"></i> Matriculaciones
        </button>
        <button class="tab-btn" data-tab="actividades">
            <i class="ri-task-line"></i> Actividades
        </button>
        <button class="tab-btn" data-tab="centralizador">
            <i class="ri-table-line"></i> Centralizador de Notas
        </button>
    </div>

    <div class="tab-content">

        {{-- ══════════════════════════════════════════════════
             TAB: MATRICULACIONES (solo lectura para docente)
        ══════════════════════════════════════════════════ --}}
        <div class="tab-pane active" id="tab-matriculaciones">

            @php
                $matTotal       = count($inscritos);
                $matModulo      = count(array_filter($inscritos, fn($i) => $i['matriculado']));
                $mooActivos     = count(array_filter($inscritos, fn($i) => $i['en_moodle'] && !$i['acceso_suspendido']));
                $mooSuspendidos = count(array_filter($inscritos, fn($i) => $i['en_moodle'] && $i['acceso_suspendido']));
            @endphp

            {{-- Header de sección --}}
            <div class="mat-section-header">
                <div class="mat-section-title">
                    <i class="ri-user-follow-line"></i>
                    <span>Estudiantes Inscritos</span>
                </div>
                <span class="mat-readonly-badge">
                    <i class="ri-eye-line"></i> Solo lectura
                </span>
            </div>

            {{-- Stats rápidos --}}
            <div class="mat-stats-row">
                <div class="mat-stat-item">
                    <div class="mat-stat-icon s-total"><i class="ri-group-line"></i></div>
                    <div>
                        <div class="mat-stat-val">{{ $matTotal }}</div>
                        <div class="mat-stat-lbl">Total inscritos</div>
                    </div>
                </div>
                <div class="mat-stat-item">
                    <div class="mat-stat-icon s-matriculado"><i class="ri-shield-check-line"></i></div>
                    <div>
                        <div class="mat-stat-val">{{ $matModulo }}</div>
                        <div class="mat-stat-lbl">Matriculados módulo</div>
                    </div>
                </div>
                <div class="mat-stat-item">
                    <div class="mat-stat-icon s-activo"><i class="ri-check-double-line"></i></div>
                    <div>
                        <div class="mat-stat-val">{{ $mooActivos }}</div>
                        <div class="mat-stat-lbl">Activos en Moodle</div>
                    </div>
                </div>
                <div class="mat-stat-item">
                    <div class="mat-stat-icon s-suspendido"><i class="ri-alert-line"></i></div>
                    <div>
                        <div class="mat-stat-val">{{ $mooSuspendidos }}</div>
                        <div class="mat-stat-lbl">Suspendidos</div>
                    </div>
                </div>
            </div>

            @if($matTotal > 0)

            {{-- Búsqueda --}}
            <div class="mat-search-wrap">
                <i class="ri-search-line"></i>
                <input type="text" id="matSearchInput"
                       class="mat-search-input"
                       placeholder="Buscar por nombre, CI o correo…"
                       oninput="filtrarMatriculas(this.value)">
            </div>

            {{-- Tabla mejorada --}}
            <div class="mat-table-wrap">
                <table class="mat-table">
                    <thead>
                        <tr>
                            <th class="mat-num">#</th>
                            <th>Estudiante</th>
                            <th>CI</th>
                            <th>Celular</th>
                            <th>Correo</th>
                            <th>Matrícula Módulo</th>
                            <th>Estado Moodle</th>
                        </tr>
                    </thead>
                    <tbody id="matTbody">
                        @foreach($inscritos as $idx => $inscrito)
                        @php
                            $partes   = preg_split('/\s+/', trim($inscrito['estudiante_nombre']));
                            $initials = strtoupper(
                                substr($partes[0] ?? '?', 0, 1) .
                                substr($partes[1] ?? '',  0, 1)
                            );
                            $searchVal = strtolower(
                                $inscrito['estudiante_nombre'] . ' ' .
                                $inscrito['estudiante_ci']     . ' ' .
                                $inscrito['correo']
                            );
                        @endphp
                        <tr class="mat-row"
                            data-search="{{ $searchVal }}">
                            <td class="mat-num">{{ $idx + 1 }}</td>
                            <td>
                                <div class="mat-student-cell">
                                    <div class="mat-avatar"
                                         style="background:{{ $modColor }}1a;color:{{ $modColor }};border:1.5px solid {{ $modColor }}38;">
                                        {{ $initials ?: '?' }}
                                    </div>
                                    <div class="mat-nombre">{{ $inscrito['estudiante_nombre'] }}</div>
                                </div>
                            </td>
                            <td class="mat-ci">{{ $inscrito['estudiante_ci'] }}</td>
                            <td class="mat-cel">{{ $inscrito['celular'] }}</td>
                            <td class="mat-correo" title="{{ $inscrito['correo'] }}">
                                {{ $inscrito['correo'] }}
                            </td>
                            <td>
                                @if($inscrito['matriculado'])
                                    <span class="mat-badge mat-badge-ok">
                                        <i class="ri-check-line"></i> Matriculado
                                    </span>
                                @else
                                    <span class="mat-badge mat-badge-no">
                                        <i class="ri-close-line"></i> No matriculado
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($inscrito['en_moodle'] && $inscrito['acceso_suspendido'])
                                    <span class="mat-badge mat-badge-warn">
                                        <i class="ri-forbid-line"></i> Suspendido
                                    </span>
                                @elseif($inscrito['en_moodle'])
                                    <span class="mat-badge mat-badge-ok">
                                        <i class="ri-check-double-line"></i> Activo
                                    </span>
                                @elseif($inscrito['tiene_cuenta_moodle'])
                                    <span class="mat-badge mat-badge-pending">
                                        <i class="ri-alert-line"></i> Sin matrícula
                                    </span>
                                @else
                                    <span class="mat-badge mat-badge-no">
                                        <i class="ri-close-line"></i> Sin cuenta
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="matNoResults" style="display:none;" class="mat-empty-state">
                <i class="ri-search-line"></i>
                <p>No se encontraron estudiantes con ese criterio.</p>
            </div>

            @else
            <div class="mat-empty-state">
                <i class="ri-user-unfollow-line"></i>
                <p>No hay estudiantes inscritos en este módulo.</p>
            </div>
            @endif

        </div>



        {{-- ══════════════════════════════════════════════════
             TAB: ACTIVIDADES
        ══════════════════════════════════════════════════ --}}
        <div class="tab-pane" id="tab-actividades">
            <div class="act-header">
                <div class="tab-title-section">
                    <i class="ri-task-line"></i>
                    <span class="tab-title">Actividades del Curso Moodle</span>
                </div>
                <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
                    @if($modulo->moodle_course_id)
                    <a href="{{ rtrim(config('moodle.url'),'/') }}/course/view.php?id={{ $modulo->moodle_course_id }}"
                       target="_blank" class="btn-ver-curso">
                        <i class="ri-external-link-line"></i> Ver curso en Moodle
                    </a>
                    @endif
                </div>
            </div>

            <div class="toolbar-editor" id="toolbarEditor" style="{{ $modulo->moodle_course_id ? '' : 'display:none;' }}">
                <span class="toolbar-label"><i class="ri-add-line"></i> Nuevo</span>
                <button class="tool-btn" onclick="ActividadesEditor.abrirModal('assign')" title="Tarea"><i class="ri-task-line"></i> Tarea</button>
                <button class="tool-btn" onclick="ActividadesEditor.abrirModal('quiz')" title="Cuestionario"><i class="ri-questionnaire-line"></i> Cuestionario</button>
                <button class="tool-btn" onclick="ActividadesEditor.abrirModal('forum')" title="Foro"><i class="ri-discuss-line"></i> Foro</button>
                <button class="tool-btn" onclick="abrirModalSubirArchivo()" title="Recurso"><i class="ri-file-line"></i> Recurso</button>
                <button class="tool-btn" onclick="ActividadesEditor.abrirModal('url')" title="URL"><i class="ri-link"></i> URL</button>
                <button class="tool-btn" onclick="ActividadesEditor.abrirModal('page')" title="Página"><i class="ri-file-text-line"></i> Página</button>
            </div>

            <div id="actividadesEditorData"
                data-modulo-id="{{ $modulo->id }}"
                data-course-id="{{ $modulo->moodle_course_id ?? 0 }}"
                data-api-base="/virtual/docente/modulos"
                style="display:none;"></div>

            @if(!$modulo->moodle_course_id)
            <div class="actividad-placeholder">
                <i class="ri-forbid-line"></i>
                <h5>Sin curso Moodle</h5>
                <p>Este módulo no tiene un curso asignado en Moodle.</p>
            </div>
            @else
            <div class="act-resumen" id="actResumen" style="display:none;">
                <div class="act-stat">
                    <div class="act-stat-icon" style="color:#6366f1;">&#9633;</div>
                    <div class="act-stat-val" id="cntSecciones">0</div>
                    <div class="act-stat-lbl">Secciones</div>
                </div>
                <div class="act-stat">
                    <div class="act-stat-icon" style="color:#6366f1;"><i class="ri-task-line"></i></div>
                    <div class="act-stat-val" id="cntTareas">0</div>
                    <div class="act-stat-lbl">Tareas</div>
                </div>
                <div class="act-stat">
                    <div class="act-stat-icon" style="color:#d97706;"><i class="ri-questionnaire-line"></i></div>
                    <div class="act-stat-val" id="cntCuestionarios">0</div>
                    <div class="act-stat-lbl">Cuestionarios</div>
                </div>
                <div class="act-stat">
                    <div class="act-stat-icon" style="color:#16a34a;"><i class="ri-discuss-line"></i></div>
                    <div class="act-stat-val" id="cntForos">0</div>
                    <div class="act-stat-lbl">Foros</div>
                </div>
            </div>

            <div class="act-loading" id="actLoading">
                <i class="ri-loader-4-line"></i>
                <p>Cargando actividades desde Moodle...</p>
            </div>

            <div id="actContenido" style="display:none;">
                <div id="seccionesContainer"></div>
                <div class="foros-section" id="forosSection" style="display:none;">
                    <div class="foros-title"><i class="ri-discuss-line"></i> Gestión de Foros</div>
                    <div id="forosContainer"></div>
                </div>
            </div>

            <div class="actividad-placeholder" id="actVacio" style="display:none;">
                <i class="ri-inbox-line"></i>
                <h5>Sin actividades</h5>
                <p>El curso Moodle no tiene secciones ni actividades configuradas.</p>
            </div>

            <div class="actividad-placeholder" id="actError" style="display:none;">
                <i class="ri-error-warning-line"></i>
                <h5>Error al cargar</h5>
                <p id="actErrorMsg">No se pudieron obtener las actividades de Moodle.</p>
            </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════
             TAB: CENTRALIZADOR DE NOTAS
        ══════════════════════════════════════════════════ --}}
        <div class="tab-pane" id="tab-centralizador">
            <div class="libro-header">
                <div class="tab-title-section">
                    <i class="ri-table-line"></i>
                    <span class="tab-title">Centralizador de Notas</span>
                </div>
                @if($modulo->moodle_course_id)
                <div style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;">
                    <button class="btn-cargar-libro" id="btnCargarCentralizador" data-modulo-id="{{ $modulo->id }}">
                        <i class="ri-refresh-line"></i> Cargar desde Moodle
                    </button>
                    <button id="btnAgregarActividad" style="display:none;align-items:center;gap:.4rem;padding:.35rem .85rem;border-radius:7px;font-size:.8rem;font-weight:600;background:rgba(22,163,74,.12);border:1px solid rgba(22,163,74,.4);color:#15803d;cursor:pointer;" title="Agregar actividad calificable manual">
                        <i class="ri-add-line"></i> Agregar Actividad
                    </button>
                    <button id="btnGuardarCalifManuales" style="display:none;align-items:center;gap:.4rem;padding:.35rem .85rem;border-radius:7px;font-size:.8rem;font-weight:600;background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.4);color:#4338ca;cursor:pointer;" title="Guardar calificaciones ingresadas">
                        <i class="ri-save-line"></i> Guardar Calificaciones
                    </button>
                    <button class="centr-btn-export" id="btnExportarCentralizador"
                        style="display:none;" title="Exportar tabla a CSV">
                        <i class="ri-download-line"></i> Exportar CSV
                    </button>
                    <a href="/virtual/docente/modulos/{{ $modulo->id }}/reporte/notas-detallado"
                        id="btnReporteDetallado"
                        target="_blank"
                        style="display:none;align-items:center;gap:.4rem;padding:.35rem .85rem;border-radius:7px;font-size:.8rem;font-weight:600;background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.4);color:#4338ca;text-decoration:none;cursor:pointer;"
                        title="Generar PDF con notas por actividad">
                        <i class="ri-file-chart-line"></i> Reporte Detallado
                    </a>
                    <a href="/virtual/docente/modulos/{{ $modulo->id }}/reporte/notas-finales"
                        id="btnReporteFinales"
                        target="_blank"
                        style="display:none;align-items:center;gap:.4rem;padding:.35rem .85rem;border-radius:7px;font-size:.8rem;font-weight:600;background:rgba(22,163,74,.1);border:1px solid rgba(22,163,74,.4);color:#15803d;text-decoration:none;cursor:pointer;"
                        title="Generar PDF con nota final y en literal">
                        <i class="ri-file-list-3-line"></i> Notas Finales
                    </a>
                </div>
                @endif
            </div>

            @if(!$modulo->moodle_course_id)
            <div class="libro-msg">
                <i class="ri-forbid-line"></i>
                <strong>Sin curso Moodle</strong>
                <p>Este módulo no tiene un curso asignado en Moodle.</p>
            </div>
            @else

            <div class="libro-loading" id="centrLoading" style="display:none;">
                <i class="ri-loader-4-line"></i>
                <p>Cargando calificaciones desde Moodle...</p>
            </div>

            <div id="centrContenido" style="display:none;">
                <div class="centr-pond-bar">
                    <div class="centr-pond-bar-left">
                        <i class="ri-percent-line"></i>
                        <strong>Ponderaciones</strong>
                        <small>Edita el % en el encabezado de cada actividad. Suma = 100% → nota exacta; suma parcial → se advierte.</small>
                    </div>
                    <div class="centr-pond-bar-right">
                        <span id="centrSumaBadge" class="centr-suma-badge">
                            <i class="ri-scales-line"></i>
                            <span id="centrSumaValor">—</span>
                        </span>
                        <button id="btnGuardarPesosCentr" class="centr-btn-save" disabled>
                            <i class="ri-save-line"></i> Guardar
                        </button>
                        <button id="btnSincronizarMoodle" class="centr-btn-sync" style="display:none;"
                            title="Actualiza en Moodle la nota máxima de cada actividad y recalcula las notas de los estudiantes">
                            <i class="ri-upload-cloud-line"></i> Sincronizar Moodle
                        </button>
                    </div>
                </div>

                <div id="centrLeyenda" class="centr-leyenda">
                    <span class="centr-leyenda-label"><i class="ri-price-tag-3-line"></i> Tipos:</span>
                </div>

                <div style="overflow-x:auto;border-radius:12px;">
                    <table class="centr-table" id="centrTabla">
                        <thead id="centrThead"></thead>
                        <tbody id="centrTbody"></tbody>
                    </table>
                </div>

                <div class="centr-footer-note">
                    <i class="ri-information-line"></i>
                    <span>Cada celda muestra <strong>(nota Moodle ÷ nota máx) × ponderación%</strong>.
                    Pasa el cursor sobre una celda para ver la nota original de Moodle.
                    Celdas con <strong>—</strong> = sin calificación registrada en esa actividad.</span>
                </div>
            </div>

            <div class="libro-msg" id="centrMsgInicial">
                <i class="ri-table-line"></i>
                <p>Haz clic en <strong>Cargar desde Moodle</strong> para ver el centralizador.</p>
            </div>

            <div id="centrManualMsg" style="display:none;padding:1rem 1.25rem;background:rgba(234,179,8,.08);border:1px solid rgba(234,179,8,.4);border-radius:10px;color:#92400e;font-size:.85rem;margin-top:1rem;">
                <i class="ri-information-line" style="margin-right:.4rem;"></i>
                <strong>Modo manual:</strong> Este módulo no tiene actividades calificables en Moodle. Usa <strong>Agregar Actividad</strong> para definir los componentes de evaluación y registrar las notas directamente.
            </div>

            <div class="libro-msg" id="centrError" style="display:none;">
                <i class="ri-error-warning-line"></i>
                <p id="centrErrorMsg">Error al cargar calificaciones.</p>
            </div>
            @endif
        </div>

    </div>{{-- /tab-content --}}
</div>{{-- /tabs-container --}}

{{-- Modal: Nueva actividad manual --}}
<div class="modal fade" id="modalNuevaActividad" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content" style="border-radius:14px;border:none;box-shadow:0 8px 40px rgba(0,0,0,.16);">
            <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:1.1rem 1.4rem .9rem;">
                <h5 class="modal-title" style="font-size:1rem;font-weight:700;"><i class="ri-add-circle-line" style="color:#15803d;margin-right:.4rem;"></i>Agregar Actividad Calificable</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.3rem 1.4rem;">
                <div style="margin-bottom:.9rem;">
                    <label style="font-size:.82rem;font-weight:600;color:#374151;display:block;margin-bottom:.3rem;">Nombre de la actividad <span style="color:#dc2626;">*</span></label>
                    <input type="text" id="nuevaActNombre" class="form-control" placeholder="Ej: Examen Parcial, Proyecto Final…" style="font-size:.87rem;">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;margin-bottom:.9rem;">
                    <div>
                        <label style="font-size:.82rem;font-weight:600;color:#374151;display:block;margin-bottom:.3rem;">Tipo</label>
                        <select id="nuevaActTipo" class="form-select" style="font-size:.87rem;">
                            <option value="manual">Manual</option>
                            <option value="assign">Tarea</option>
                            <option value="quiz">Cuestionario</option>
                            <option value="forum">Foro</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:.82rem;font-weight:600;color:#374151;display:block;margin-bottom:.3rem;">Nota máxima</label>
                        <input type="number" id="nuevaActMax" class="form-control" value="100" min="1" max="10000" step="0.01" style="font-size:.87rem;">
                    </div>
                </div>
                <div>
                    <label style="font-size:.82rem;font-weight:600;color:#374151;display:block;margin-bottom:.3rem;">Ponderación % <span style="font-size:.75rem;color:#6b7280;">(se puede ajustar después)</span></label>
                    <input type="number" id="nuevaActPeso" class="form-control" value="0" min="0" max="100" step="0.01" style="font-size:.87rem;">
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:.9rem 1.4rem;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="font-size:.82rem;">Cancelar</button>
                <button type="button" id="btnConfirmarNuevaAct" class="btn btn-success btn-sm" style="font-size:.82rem;font-weight:600;">Agregar</button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     MODALES — Discusiones y editor de actividades
══════════════════════════════════════════ --}}

{{-- Modal: Confirmación ponderación del centralizador --}}
<div class="modal fade" id="modalCentrPond" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content" style="border-radius:14px;border:none;box-shadow:0 8px 40px rgba(0,0,0,.16);">
            <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:1.1rem 1.4rem;background:#1e293b;border-radius:14px 14px 0 0;">
                <h5 class="modal-title" style="font-size:.93rem;font-weight:700;color:#f1f5f9;display:flex;align-items:center;gap:.5rem;">
                    <i class="ri-percent-line" style="color:#fc7b04;"></i> Cambio de ponderación
                </h5>
            </div>
            <div class="modal-body" style="padding:1.3rem 1.5rem;">
                <p style="font-size:.87rem;color:#334155;margin:0 0 .3rem;">
                    La ponderación de <strong id="cpActNombre" style="color:#1e293b;"></strong>
                    cambió de <strong id="cpPesoAnterior" style="color:#64748b;"></strong>%
                    a <strong id="cpPesoNuevo" style="color:#6366f1;"></strong>%
                    <span style="font-size:.78rem;color:#94a3b8;">(nota máx en Moodle: <span id="cpActMax"></span> pts)</span>
                </p>
                <p style="font-size:.85rem;color:#475569;margin:.8rem 0 1rem;">
                    ¿Cómo se deben mostrar las calificaciones de esta actividad?
                </p>
                <div style="display:grid;gap:.7rem;">
                    <button id="btnCentrPonderar" class="centr-opt-btn centr-opt-ponderar">
                        <div style="display:flex;align-items:flex-start;gap:.75rem;">
                            <div style="width:32px;height:32px;border-radius:50%;background:rgba(99,102,241,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-calculator-line" style="color:#6366f1;font-size:1rem;"></i>
                            </div>
                            <div style="text-align:left;">
                                <div style="font-weight:700;font-size:.87rem;color:#1e293b;margin-bottom:2px;">Ponderar — recalcular nota</div>
                                <div style="font-size:.78rem;color:#64748b;line-height:1.4;">
                                    La nota mostrada será <code style="background:#f1f5f9;padding:1px 5px;border-radius:4px;">(nota Moodle ÷ nota máx) × nuevo %</code>
                                </div>
                                <div id="cpEjemploPond" style="font-size:.75rem;color:#6366f1;margin-top:3px;font-style:italic;"></div>
                            </div>
                        </div>
                    </button>
                    <button id="btnCentrMantener" class="centr-opt-btn centr-opt-mantener">
                        <div style="display:flex;align-items:flex-start;gap:.75rem;">
                            <div style="width:32px;height:32px;border-radius:50%;background:rgba(234,179,8,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-lock-line" style="color:#b45309;font-size:1rem;"></i>
                            </div>
                            <div style="text-align:left;">
                                <div style="font-weight:700;font-size:.87rem;color:#1e293b;margin-bottom:2px;">Mantener nota — usar tal como está en Moodle</div>
                                <div style="font-size:.78rem;color:#64748b;line-height:1.4;">
                                    La nota ya fue ingresada con la ponderación aplicada directamente en Moodle.
                                </div>
                                <div id="cpEjemploMant" style="font-size:.75rem;color:#b45309;margin-top:3px;font-style:italic;"></div>
                            </div>
                        </div>
                    </button>
                </div>
                <p style="font-size:.72rem;color:#94a3b8;margin:.9rem 0 0;text-align:center;">
                    <i class="ri-information-line"></i>
                    Si cierra este diálogo sin elegir, el porcentaje volverá al valor anterior. También puede usar el botón <strong>Recalcular / Mantener</strong> en cada actividad.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Ver discusiones de un foro --}}
<div class="disc-modal-overlay" id="modalDiscusiones">
    <div class="disc-modal">
        <div class="disc-modal-hdr">
            <span class="disc-modal-title"><i class="ri-discuss-line"></i> <span id="discModalForoNombre">Discusiones</span></span>
            <button class="disc-modal-close" onclick="ActividadesEditor.cerrarModalDisc()">&times;</button>
        </div>
        <div class="disc-modal-body" id="discModalBody">
            <div class="act-loading"><i class="ri-loader-4-line"></i><p>Cargando...</p></div>
        </div>
        <div class="disc-modal-footer">
            <button class="btn-cancel-disc" onclick="ActividadesEditor.cerrarModalDisc()">Cerrar</button>
            <button class="btn-guardar-disc" id="btnAbrirNuevaDisc" onclick="mostrarFormNuevaDisc()">
                <i class="ri-add-line"></i> Nueva Discusión
            </button>
        </div>
    </div>
</div>

{{-- Modal: Nueva discusión --}}
<div class="disc-modal-overlay" id="modalNuevaDisc">
    <div class="disc-modal">
        <div class="disc-modal-hdr">
            <span class="disc-modal-title"><i class="ri-edit-line"></i> Nueva Discusión</span>
            <button class="disc-modal-close" onclick="cerrarModalNuevaDisc()">&times;</button>
        </div>
        <div class="disc-modal-body">
            <div class="nueva-disc-form">
                <div>
                    <label>Asunto</label>
                    <input type="text" id="discSubject" placeholder="Título de la discusión">
                </div>
                <div>
                    <label>Mensaje</label>
                    <textarea id="discMessage" rows="5" placeholder="Escribe el mensaje de la discusión..."></textarea>
                </div>
            </div>
        </div>
        <div class="disc-modal-footer">
            <button class="btn-cancel-disc" onclick="cerrarModalNuevaDisc()">Cancelar</button>
            <button class="btn-guardar-disc" id="btnGuardarDisc" onclick="submitNuevaDisc()">
                <i class="ri-send-plane-line"></i> Publicar Discusión
            </button>
        </div>
    </div>
</div>

{{-- Modal: Editor de Actividades --}}
<div class="disc-modal-overlay" id="modalForm">
    <div class="disc-modal" style="max-width:640px;">
        <div class="disc-modal-hdr">
            <span class="disc-modal-title"><i class="ri-pencil-line"></i> <span id="modalTitleText">Nueva Actividad</span></span>
            <button class="disc-modal-close" onclick="ActividadesEditor.cerrarModal()">&times;</button>
        </div>
        <div class="disc-modal-body" id="modalBody"></div>
        <div class="disc-modal-footer">
            <button class="btn-cancel-disc" onclick="ActividadesEditor.cerrarModal()">Cancelar</button>
            <button class="btn-guardar-disc btn-save"><i class="ri-check-line"></i> Guardar en Moodle</button>
        </div>
    </div>
</div>

{{-- Modal: Calificar Tarea --}}
<div class="disc-modal-overlay" id="modalCalificarTarea">
    <div class="disc-modal" style="max-width:720px;">
        <div class="disc-modal-hdr">
            <span class="disc-modal-title"><i class="ri-bar-chart-line"></i> Calificar: <span id="calificarTareaNombre"></span></span>
            <button class="disc-modal-close" onclick="ActividadesEditor.cerrarModalCalificar()">&times;</button>
        </div>
        <div class="disc-modal-body">
            <div id="calificarLoading" class="text-center py-4">
                <div class="spinner-border"></div><p class="mt-2">Cargando entregas...</p>
            </div>
            <div id="calificarError" class="alert alert-danger d-none">
                <span id="calificarErrorMsg"></span>
            </div>
            <div id="calificarContent" class="d-none">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr><th>Estudiante</th><th>Estado</th><th>Nota</th><th>Feedback</th><th>Acción</th></tr>
                        </thead>
                        <tbody id="calificarTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="disc-modal-footer">
            <button class="btn-cancel-disc" onclick="ActividadesEditor.cerrarModalCalificar()">Cerrar</button>
        </div>
    </div>
</div>

{{-- Modal: Posts de foro --}}
<div class="disc-modal-overlay" id="modalPosts">
    <div class="disc-modal" style="max-width:640px;">
        <div class="disc-modal-hdr">
            <span class="disc-modal-title"><i class="ri-chat-1-line"></i> <span id="postsDiscussionTitle">Respuestas</span></span>
            <button class="disc-modal-close" onclick="ActividadesEditor.cerrarModalPosts()">&times;</button>
        </div>
        <div class="disc-modal-body">
            <div id="postsLoading" class="text-center py-2 d-none"><div class="spinner-border spinner-border-sm"></div> Cargando...</div>
            <div id="postsError" class="alert alert-danger d-none"></div>
            <div id="postsContainer"></div>
            <hr>
            <h6>Responder como docente</h6>
            <textarea id="replyMessage" class="form-control mb-2" rows="3" placeholder="Escribe tu respuesta..."></textarea>
            <button class="btn btn-primary btn-sm" id="btnEnviarRespuesta">Publicar respuesta</button>
        </div>
        <div class="disc-modal-footer">
            <button class="btn-cancel-disc" onclick="ActividadesEditor.cerrarModalPosts()">Cerrar</button>
        </div>
    </div>
</div>

{{-- Modal: Resultados Quiz --}}
<div class="disc-modal-overlay" id="modalQuizResultados">
    <div class="disc-modal" style="max-width:720px;">
        <div class="disc-modal-hdr">
            <span class="disc-modal-title"><i class="ri-bar-chart-grouped-line"></i> Resultados: <span id="quizResultadosNombre"></span></span>
            <button class="disc-modal-close" onclick="ActividadesEditor.cerrarModalQuiz()">&times;</button>
        </div>
        <div class="disc-modal-body">
            <div id="quizLoading" class="text-center py-4"><div class="spinner-border"></div><p class="mt-2">Cargando resultados...</p></div>
            <div id="quizError" class="alert alert-danger d-none"><span id="quizErrorText"></span></div>
            <div id="quizContent" class="d-none">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead><tr><th>Estudiante</th><th>Intento</th><th>Estado</th><th>Calificación</th><th>Acción</th></tr></thead>
                        <tbody id="quizTableBody"></tbody>
                    </table>
                </div>
            </div>
            <div id="quizAttemptDetail" class="d-none mt-3">
                <hr><h6>Detalle del intento</h6>
                <div id="quizQuestionsContainer"></div>
            </div>
        </div>
        <div class="disc-modal-footer">
            <button class="btn-cancel-disc" onclick="ActividadesEditor.cerrarModalQuiz()">Cerrar</button>
        </div>
    </div>
</div>

{{-- Modal: Preguntas del Quiz --}}
<div class="disc-modal-overlay" id="modalPreguntasQuiz">
    <div class="disc-modal" style="max-width:640px;">
        <div class="disc-modal-hdr">
            <span class="disc-modal-title"><i class="ri-question-line"></i> Preguntas: <span id="preguntasQuizNombre"></span></span>
            <button class="disc-modal-close" onclick="ActividadesEditor.cerrarModalPreguntas()">&times;</button>
        </div>
        <div class="disc-modal-body">
            <div id="preguntasLoading" class="text-center py-4"><div class="spinner-border"></div><p class="mt-2">Cargando preguntas...</p></div>
            <div id="preguntasError" class="alert alert-danger d-none"><span id="preguntasErrorTxt"></span></div>
            <div id="preguntasContent" class="d-none">
                <div style="margin-bottom:0.75rem;display:flex;gap:0.5rem;flex-wrap:wrap;">
                    <button class="btn btn-sm btn-primary" onclick="ActividadesEditor.mostrarFormMC()">+ Opción múltiple</button>
                    <button class="btn btn-sm btn-primary" onclick="ActividadesEditor.mostrarFormTF()">+ V/F</button>
                    <button class="btn btn-sm btn-primary" onclick="ActividadesEditor.mostrarFormMatch()">+ Coincidencia</button>
                </div>
                <div id="preguntasList"></div>
            </div>
        </div>
        <div class="disc-modal-footer">
            <button class="btn-cancel-disc" onclick="ActividadesEditor.cerrarModalPreguntas()">Cerrar</button>
        </div>
    </div>
</div>

{{-- Modal: Crear opción múltiple --}}
<div class="disc-modal-overlay" id="modalMC">
    <div class="disc-modal" style="max-width:560px;">
        <div class="disc-modal-hdr">
            <span class="disc-modal-title">Nueva pregunta: Opción múltiple</span>
            <button class="disc-modal-close" onclick="ActividadesEditor.cerrarModalMC()">&times;</button>
        </div>
        <div class="disc-modal-body">
            <div class="form-group"><label>Nombre</label><input class="form-control" id="mcName" placeholder="Ej: Pregunta 1"></div>
            <div class="form-group"><label>Texto de la pregunta</label><textarea class="form-control" id="mcQuestionText" rows="3"></textarea></div>
            <div class="form-row">
                <div class="form-group"><label>Puntaje</label><input class="form-control" id="mcDefaultMark" type="number" value="1" step="0.5"></div>
                <div class="form-group"><label>Tipo</label><select class="form-control" id="mcSingle"><option value="true">Única respuesta</option><option value="false">Múltiple respuesta</option></select></div>
            </div>
            <div class="form-group"><label>Opciones</label><div id="mcOptionsContainer"></div>
            <button class="btn btn-sm btn-outline-secondary mt-1" onclick="var c=document.querySelectorAll('#mcOptionsContainer .mc-option').length;document.getElementById('mcOptionsContainer').innerHTML+='<div class=\'mc-option\' style=\'display:flex;gap:0.5rem;margin-top:4px;\'><input style=\'width:60%;\' class=\'form-control form-control-sm\' placeholder=\'Texto\' id=\'mcOpt'+c+'\'><input style=\'width:60px;\' class=\'form-control form-control-sm\' type=\'number\' step=\'0.01\' value=\'0\' id=\'mcFrac'+c+'\'><button class=\'btn btn-sm btn-outline-danger\' onclick=\'this.parentElement.remove()\'>X</button></div>'">+ Agregar opción</button></div>
        </div>
        <div class="disc-modal-footer">
            <button class="btn-cancel-disc" onclick="ActividadesEditor.cerrarModalMC()">Cancelar</button>
            <button class="btn-guardar-disc" onclick="ActividadesEditor.guardarMC()"><i class="ri-check-line"></i> Crear pregunta</button>
        </div>
    </div>
</div>

{{-- Modal: Crear V/F --}}
<div class="disc-modal-overlay" id="modalTF">
    <div class="disc-modal" style="max-width:480px;">
        <div class="disc-modal-hdr">
            <span class="disc-modal-title">Nueva pregunta: Verdadero / Falso</span>
            <button class="disc-modal-close" onclick="ActividadesEditor.cerrarModalTF()">&times;</button>
        </div>
        <div class="disc-modal-body">
            <div class="form-group"><label>Nombre</label><input class="form-control" id="tfName" placeholder="Ej: Pregunta 2"></div>
            <div class="form-group"><label>Texto de la pregunta</label><textarea class="form-control" id="tfQuestionText" rows="3"></textarea></div>
            <div class="form-row">
                <div class="form-group"><label>Puntaje</label><input class="form-control" id="tfDefaultMark" type="number" value="1" step="0.5"></div>
                <div class="form-group"><label>Respuesta correcta</label><select class="form-control" id="tfCorrect"><option value="true">Verdadero</option><option value="false">Falso</option></select></div>
            </div>
        </div>
        <div class="disc-modal-footer">
            <button class="btn-cancel-disc" onclick="ActividadesEditor.cerrarModalTF()">Cancelar</button>
            <button class="btn-guardar-disc" onclick="ActividadesEditor.guardarTF()"><i class="ri-check-line"></i> Crear pregunta</button>
        </div>
    </div>
</div>

{{-- Modal: Crear Coincidencia --}}
<div class="disc-modal-overlay" id="modalMatch">
    <div class="disc-modal" style="max-width:560px;">
        <div class="disc-modal-hdr">
            <span class="disc-modal-title">Nueva pregunta: Coincidencia</span>
            <button class="disc-modal-close" onclick="ActividadesEditor.cerrarModalMatch()">&times;</button>
        </div>
        <div class="disc-modal-body">
            <div class="form-group"><label>Nombre</label><input class="form-control" id="matchName"></div>
            <div class="form-group"><label>Texto</label><textarea class="form-control" id="matchQuestionText" rows="2"></textarea></div>
            <div class="form-group"><label>Puntaje</label><input class="form-control" id="matchDefaultMark" type="number" value="1" step="0.5"></div>
            <div class="form-group"><label>Pares</label><div id="matchPairsContainer"></div>
            <button class="btn btn-sm btn-outline-secondary mt-1" onclick="var c=document.querySelectorAll('#matchPairsContainer .match-pair').length;document.getElementById('matchPairsContainer').innerHTML+='<div class=\'match-pair\' style=\'display:flex;gap:0.5rem;margin-top:4px;\'><input style=\'width:40%;\' class=\'form-control form-control-sm\' placeholder=\'Pregunta\' id=\'mpQ'+c+'\'><input style=\'width:40%;\' class=\'form-control form-control-sm\' placeholder=\'Respuesta\' id=\'mpA'+c+'\'><button class=\'btn btn-sm btn-outline-danger\' onclick=\'this.parentElement.remove()\'>X</button></div>'">+ Agregar par</button></div>
        </div>
        <div class="disc-modal-footer">
            <button class="btn-cancel-disc" onclick="ActividadesEditor.cerrarModalMatch()">Cancelar</button>
            <button class="btn-guardar-disc" onclick="ActividadesEditor.guardarMatch()"><i class="ri-check-line"></i> Crear pregunta</button>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
// ============================================================
// BÚSQUEDA EN TABLA DE MATRICULACIONES
// ============================================================
function filtrarMatriculas(q) {
    const term  = q.toLowerCase().trim();
    const rows  = document.querySelectorAll('#matTbody .mat-row');
    const noRes = document.getElementById('matNoResults');
    let visible = 0;
    rows.forEach(function(row) {
        const hayMatch = !term || row.dataset.search.includes(term);
        row.classList.toggle('mat-row-hidden', !hayMatch);
        if (hayMatch) visible++;
    });
    if (noRes) noRes.style.display = visible === 0 && term ? 'block' : 'none';
}

// ============================================================
// TAB NAVIGATION
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    const tabBtns  = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    let actividadesCargadas  = false;
    let centralizadorCargado = false;

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanes.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('tab-' + tabId).classList.add('active');

            if (tabId === 'actividades' && !actividadesCargadas) {
                actividadesCargadas = true;
                if (typeof ActividadesEditor !== 'undefined') {
                    ActividadesEditor.cargarYRenderizar();
                }
            }
            if (tabId === 'centralizador' && !centralizadorCargado) {
                centralizadorCargado = true;
                const btnCentr = document.getElementById('btnCargarCentralizador');
                if (btnCentr) cargarCentralizador(btnCentr.getAttribute('data-modulo-id'));
            }
        });
    });
});

// ============================================================
// CENTRALIZADOR DE NOTAS
// (rutas: /docente/modulos/{id}/academico/calificaciones + /centralizador/sincronizar-moodle)
// ============================================================
(function () {
    const MOD_LABELS_C = { assign:'Tarea', quiz:'Cuestionario', forum:'Foro', resource:'Recurso', page:'Página', url:'URL', workshop:'Taller', scorm:'SCORM', feedback:'Retroalimentación' };
    const MOD_COLORS_C = {
        assign:   { bg:'rgba(99,102,241,.12)',  color:'#4f46e5' },
        quiz:     { bg:'rgba(217,119,6,.12)',   color:'#b45309' },
        forum:    { bg:'rgba(22,163,74,.12)',   color:'#15803d' },
        resource: { bg:'rgba(14,165,233,.12)',  color:'#0284c7' },
        page:     { bg:'rgba(168,85,247,.12)', color:'#7e22ce' },
        url:      { bg:'rgba(249,115,22,.12)', color:'#c2410c' },
    };
    let _items=[], _estudiantes=[], _moduloId=null, _modos={}, _pendingChange=null, _manualMode=false, _manualGradesDirty=false, _modalCentrInst=null, _modalNuevaActInst=null;

    function escHtml(s) { return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
    function getPeso(itemId) { const inp=document.getElementById('cPeso_'+itemId); return inp?(parseFloat(inp.value)||0):0; }
    function getModo(itemId) { return _modos[itemId]||'ponderar'; }
    function colorClass(valor, esTotal) {
        if (valor===null||valor===undefined) return 'sin-nota';
        const pct = esTotal ? valor/100 : valor;
        if (pct>=0.6) return 'centr-aprobado';
        if (pct>=0.4) return 'centr-regular';
        return 'centr-reprobado';
    }
    function notaPonderada(item, moodleUserId) {
        const raw=(item.grades??{})[moodleUserId]??null;
        if (raw===null) return null;
        if (getModo(item.id)==='mantener') return parseFloat(raw);
        const max=item.max!=null?parseFloat(item.max):null;
        const peso=getPeso(item.id);
        if (max===null||max===0) return null;
        return (raw/max)*peso;
    }
    function detectarModoCalculo() {
        if (_items.length===0) return 'invalid';
        let suma=0, todosEnCien=true;
        _items.forEach(item=>{ const p=getPeso(item.id); suma+=p; if(Math.abs(p-100)>0.01) todosEnCien=false; });
        suma=Math.round(suma*100)/100;
        if (Math.abs(suma-100)<0.01) return 'cumulative';
        if (todosEnCien) return 'average';
        return 'invalid';
    }
    function calcNotaFinal(moodleUserId) {
        const modo=detectarModoCalculo(); let suma=0, cnt=0;
        _items.forEach(item=>{ const np=notaPonderada(item,moodleUserId); if(np!==null){suma+=np;cnt++;} });
        if (cnt===0) return null;
        return modo==='average' ? suma/cnt : suma;
    }
    function actualizarBadgeModo(itemId) {
        const badge=document.getElementById('cModoBadge_'+itemId); if(!badge) return;
        const modo=getModo(itemId);
        if (modo==='mantener') { badge.textContent='Mantiene nota'; badge.style.background='rgba(234,179,8,.15)'; badge.style.color='#92400e'; badge.style.borderColor='#fcd34d'; }
        else { badge.textContent='Ponderado'; badge.style.background='rgba(99,102,241,.1)'; badge.style.color='#4338ca'; badge.style.borderColor='#a5b4fc'; }
    }
    function recalcularCeldas() {
        _estudiantes.forEach(est => {
            _items.forEach(item => {
                const cell=document.getElementById('cNota_'+est.moodle_user_id+'_'+item.id); if(!cell) return;
                const np=notaPonderada(item,est.moodle_user_id);
                const raw=(item.grades??{})[est.moodle_user_id]??null;
                const max=item.max!=null?parseFloat(item.max):null;
                cell.className='nota-cell '+(np===null?'sin-nota':colorClass(np/(getPeso(item.id)||1),false));
                cell.textContent=np!==null?np.toFixed(2):'—';
                if (raw!==null) { const modo=getModo(item.id); cell.title=modo==='mantener'?'Nota Moodle: '+parseFloat(raw).toFixed(2)+' / '+(max??'?')+' pts':'Moodle: '+parseFloat(raw).toFixed(2)+' / '+(max??'?')+' pts → '+np?.toFixed(2)+' ponderado'; }
                else cell.title='Sin calificación en Moodle';
            });
            const nfCell=document.getElementById('cNFinal_'+est.moodle_user_id); if(!nfCell) return;
            const nf=calcNotaFinal(est.moodle_user_id);
            const modo=detectarModoCalculo(); const incompleta=modo!=='cumulative'&&modo!=='average';
            if (nf!==null) {
                let suma=0; _items.forEach(i=>{suma+=getPeso(i.id);}); suma=Math.round(suma*100)/100;
                nfCell.className='nota-final-cell'+(incompleta?' centr-incompleta':' '+colorClass(nf,true));
                nfCell.innerHTML=incompleta?`<span style="font-size:.85rem;">${nf.toFixed(2)}</span><br><span style="font-size:.62rem;color:#f59e0b;font-weight:600;">⚠ /${suma}%</span>`:nf.toFixed(2);
                nfCell.title=incompleta?`Suma parcial: ${nf.toFixed(2)} pts sobre ${suma}% asignado`:'';
            } else { nfCell.className='nota-final-cell sin-nota'; nfCell.textContent='—'; nfCell.title=''; }
        });
    }
    function actualizarBadgeSuma() {
        const badge=document.getElementById('centrSumaBadge'); const valSpan=document.getElementById('centrSumaValor');
        const btnSave=document.getElementById('btnGuardarPesosCentr'); const nfHdr=document.getElementById('centrNotaFinalHdr');
        if (!badge||!valSpan) return;
        const modo=detectarModoCalculo(); let suma=0;
        _items.forEach(item=>{suma+=getPeso(item.id);}); suma=Math.round(suma*100)/100;
        if (modo==='average') { badge.style.background='rgba(22,163,74,.12)';badge.style.color='#15803d';badge.style.borderColor='#86efac';valSpan.textContent='Promedio ('+_items.length+' activ.)'; if(nfHdr) nfHdr.innerHTML='Nota Final<br><small style="font-size:.6rem;font-weight:400;color:#94a3b8;">promedio / 100</small>'; }
        else if (modo==='cumulative') { badge.style.background='rgba(22,163,74,.12)';badge.style.color='#15803d';badge.style.borderColor='#86efac';valSpan.textContent='100% ✓'; if(nfHdr) nfHdr.innerHTML='Nota Final<br><small style="font-size:.6rem;font-weight:400;color:#94a3b8;">ponderada / 100</small>'; }
        else { badge.style.background='rgba(234,179,8,.12)';badge.style.color='#92400e';badge.style.borderColor='#fcd34d';valSpan.textContent=suma+'% de 100%'; if(nfHdr) nfHdr.innerHTML='Nota Final<br><small style="font-size:.6rem;font-weight:400;color:#f59e0b;">⚠ incompleta</small>'; }
        if (btnSave) btnSave.disabled=false;
        const esCompleto=(modo==='cumulative'||modo==='average');
        const btnDet=document.getElementById('btnReporteDetallado'); const btnFin=document.getElementById('btnReporteFinales');
        if (btnDet) btnDet.style.display=esCompleto?'inline-flex':'none';
        if (btnFin) btnFin.style.display=esCompleto?'inline-flex':'none';
    }
    function abrirModalCambioModo(item, pesoAnterior, pesoNuevo) {
        try {
            _pendingChange={item,pesoAnterior,pesoNuevo};
            const elNombre=document.getElementById('cpActNombre');
            const elPrev=document.getElementById('cpPesoAnterior');
            const elNuevo=document.getElementById('cpPesoNuevo');
            const elMax=document.getElementById('cpActMax');
            const elEjPond=document.getElementById('cpEjemploPond');
            const elEjMant=document.getElementById('cpEjemploMant');
            if(elNombre) elNombre.textContent=item.name;
            if(elPrev)   elPrev.textContent=pesoAnterior.toFixed(2);
            if(elNuevo)  elNuevo.textContent=pesoNuevo.toFixed(2);
            // Resaltar la opción que está actualmente guardada
            const modoActual=getModo(item.id);
            const btnPond=document.getElementById('btnCentrPonderar');
            const btnMant=document.getElementById('btnCentrMantener');
            if(btnPond) btnPond.style.borderColor=modoActual==='ponderar'?'#6366f1':'#e2e8f0';
            if(btnMant) btnMant.style.borderColor=modoActual==='mantener'?'#d97706':'#e2e8f0';
            const max=item.max!=null?parseFloat(item.max).toFixed(2):'?';
            if(elMax) elMax.textContent=max;
            let ejemploRaw=null;
            for (const est of _estudiantes) { const g=(item.grades??{})[est.moodle_user_id]??null; if(g!==null){ejemploRaw=parseFloat(g);break;} }
            const maxN=item.max!=null?parseFloat(item.max):null;
            if (elEjPond&&elEjMant) {
                if (ejemploRaw!==null&&maxN&&maxN>0) {
                    const ejPond=((ejemploRaw/maxN)*pesoNuevo).toFixed(2);
                    elEjPond.textContent=`Ej: ${ejemploRaw.toFixed(2)} / ${max} pts × ${pesoNuevo.toFixed(2)}% = ${ejPond}`;
                    elEjMant.textContent=`Ej: nota mostrada = ${ejemploRaw.toFixed(2)} (tal como está en Moodle)`;
                } else { elEjPond.textContent=''; elEjMant.textContent=''; }
            }
            const modalEl=document.getElementById('modalCentrPond');
            if (!modalEl) { console.error('modalCentrPond no encontrado en el DOM'); return; }
            if (!_modalCentrInst) _modalCentrInst = new bootstrap.Modal(modalEl);
            _modalCentrInst.show();
        } catch(e) { console.error('Error al abrir modal de ponderación:', e); }
    }
    function confirmarModo(decision) {
        if (!_pendingChange) return;
        const { item, pesoNuevo } = _pendingChange;

        if (decision === 'mantener') {
            // Obtener la nota máxima registrada para esta actividad entre todos los estudiantes
            let notaMaximaRegistrada = 0;
            for (const uid in (item.grades ?? {})) {
                const notaVal = parseFloat((item.grades ?? {})[uid]);
                if (!isNaN(notaVal) && notaVal > notaMaximaRegistrada) {
                    notaMaximaRegistrada = notaVal;
                }
            }

            if (pesoNuevo < notaMaximaRegistrada) {
                alert(`No se puede seleccionar "Mantener nota". La nueva ponderación (${pesoNuevo.toFixed(2)}%) es menor que la calificación de algún estudiante (${notaMaximaRegistrada.toFixed(2)} pts). Debe elegir la opción "Ponderado" para reescalar la nota proporcionalmente.`);
                return;
            }
        }

        _modos[item.id] = decision;
        _pendingChange = null;
        const btnPond = document.getElementById('btnCentrPonderar');
        const btnMant = document.getElementById('btnCentrMantener');
        if (btnPond) btnPond.style.borderColor = '';
        if (btnMant) btnMant.style.borderColor = '';
        if (_modalCentrInst) _modalCentrInst.hide();
        actualizarBadgeSuma();
        actualizarBadgeModo(item.id);
        recalcularCeldas();
    }
    function renderCentralizador(data) {
        _items=data.grade_items||[]; _estudiantes=data.estudiantes||[];
        _moduloId=data.modulo_id||document.getElementById('btnCargarCentralizador')?.getAttribute('data-modulo-id');
        _modos={}; _manualMode=data.manual_mode||false; _manualGradesDirty=false;
        // Restaurar modos guardados desde el servidor
        (_items).forEach(item=>{ if(item.calculation_mode) _modos[item.id]=item.calculation_mode; });
        // Show/hide manual mode elements
        const btnAdd=document.getElementById('btnAgregarActividad');
        const btnSaveG=document.getElementById('btnGuardarCalifManuales');
        const manualMsg=document.getElementById('centrManualMsg');
        const btnSync=document.getElementById('btnSincronizarMoodle');
        if(btnAdd) btnAdd.style.display=_manualMode?'inline-flex':'none';
        if(btnSaveG) btnSaveG.style.display=(_manualMode&&_items.length>0)?'inline-flex':'none';
        if(manualMsg) manualMsg.style.display=_manualMode?'block':'none';
        if(btnSync) btnSync.style.display=_manualMode?'none':(btnSync.style.display||'none');
        const leyenda=document.getElementById('centrLeyenda');
        if (leyenda) {
            const tipos=[...new Set(_items.map(i=>i.module))];
            const chips=tipos.map(mod=>{const lbl=MOD_LABELS_C[mod]||mod;const col=MOD_COLORS_C[mod]||{bg:'rgba(156,163,175,.12)',color:'#6b7280'};return`<span style="background:${col.bg};color:${col.color};padding:3px 12px;border-radius:20px;font-size:.72rem;font-weight:600;border:1px solid ${col.color}22;">${lbl}</span>`;}).join('');
            leyenda.innerHTML=`<span class="centr-leyenda-label"><i class="ri-price-tag-3-line"></i> Tipos:</span>${chips}`;
        }
        const thead=document.getElementById('centrThead');
        if (thead) {
            const moodleUrl=data.moodle_url||'';
            let r1=`<tr class="centr-thead-r1"><th class="centr-th-fixed" rowspan="3" style="min-width:38px;vertical-align:middle;text-align:center;">#</th><th class="centr-th-fixed" rowspan="3" style="min-width:190px;vertical-align:middle;"><i class="ri-user-line" style="opacity:.7;margin-right:4px;"></i>Estudiante</th><th class="centr-th-fixed" rowspan="3" style="min-width:85px;vertical-align:middle;text-align:center;">CI</th>`;
            _items.forEach(item=>{
                const isManual=item.is_manual===true;
                const url=moodleUrl?moodleUrl+'/mod/'+item.module+'/view.php?id='+item.cmid:'#';
                const actionBtn=isManual
                    ?`<button class="centr-del-item-btn" data-item-id="${item.id}" title="Eliminar actividad manual" style="font-size:.6rem;color:#dc2626;background:rgba(220,38,38,.1);border:1px solid rgba(220,38,38,.3);padding:1px 7px;border-radius:10px;cursor:pointer;display:inline-flex;align-items:center;gap:3px;"><i class="ri-delete-bin-line"></i> Eliminar</button>`
                    :`<a href="${url}" target="_blank" style="font-size:.6rem;color:rgba(252,123,4,.9);background:rgba(252,123,4,.12);padding:1px 7px;border-radius:10px;display:inline-flex;align-items:center;gap:3px;text-decoration:none;"><i class="ri-external-link-line"></i> Moodle</a>`;
                r1+=`<th class="th-act" style="min-width:155px;text-align:center;vertical-align:middle;"><span style="display:block;font-size:.74rem;font-weight:700;line-height:1.3;margin-bottom:3px;">${escHtml(item.name)}</span>${actionBtn}</th>`;
            });
            r1+=`<th id="centrNotaFinalHdr" class="centr-th-nfinal" rowspan="3" style="min-width:110px;vertical-align:middle;text-align:center;font-size:.73rem;font-weight:700;letter-spacing:.03em;"><i class="ri-bar-chart-2-line" style="display:block;font-size:1.1rem;margin-bottom:2px;opacity:.8;"></i>NOTA FINAL<br><small style="font-size:.6rem;font-weight:400;opacity:.75;">(sobre 100)</small></th></tr>`;
            let r2='<tr class="centr-thead-r2">';
            _items.forEach(item=>{const lbl=MOD_LABELS_C[item.module]||item.module;const col=MOD_COLORS_C[item.module]||{bg:'rgba(156,163,175,.12)',color:'#6b7280'};const max=item.max!=null?parseFloat(item.max).toFixed(2):'—';r2+=`<th><span style="background:${col.bg};color:${col.color};padding:2px 9px;border-radius:20px;font-size:.67rem;font-weight:700;display:inline-block;border:1px solid ${col.color}33;">${lbl}</span><span style="display:block;font-size:.65rem;color:var(--d-muted);margin-top:3px;">Máx: <strong style="color:var(--d-body)">${max}</strong> pts</span><span id="cModoBadge_${item.id}" class="centr-modo-badge" style="background:rgba(99,102,241,.1);color:#4338ca;border-color:#a5b4fc;" title="Las notas se calculan como (nota Moodle / nota máx) × ponderación%.">Ponderado</span></th>`;});
            r2+='</tr>';
            let r3='<tr class="centr-thead-r3">';
            _items.forEach(item=>{r3+=`<th><label style="font-size:.62rem;font-weight:600;color:var(--d-muted);display:block;margin-bottom:3px;text-transform:uppercase;letter-spacing:.03em;">Pond. %</label><div style="display:flex;align-items:center;justify-content:center;gap:4px;"><input type="number" id="cPeso_${item.id}" class="centr-peso-input" data-item-id="${item.id}" data-peso-prev="${parseFloat(item.weight).toFixed(2)}" min="0" max="100" step="0.01" value="${parseFloat(item.weight).toFixed(2)}" title="Ponderación (%)"><span style="font-size:.7rem;color:var(--d-muted);font-weight:600;">%</span></div></th>`;});
            r3+='</tr>';
            thead.innerHTML=r1+r2+r3;
            thead.querySelectorAll('.centr-peso-input').forEach(inp=>{
                inp.addEventListener('input',actualizarBadgeSuma);

                inp.addEventListener('change',function(){
                    const itemId=parseInt(this.getAttribute('data-item-id'));
                    const pesoPrev=parseFloat(this.getAttribute('data-peso-prev'))||0;
                    const pesoNuevo=parseFloat(this.value)||0;

                    if(Math.abs(pesoNuevo-pesoPrev)<0.001) return;

                    const item=_items.find(i=>i.id===itemId||String(i.id)===String(itemId)); if(!item) return;

                    this.setAttribute('data-peso-prev',pesoNuevo.toFixed(2));
                    abrirModalCambioModo(item,pesoPrev,pesoNuevo);
                });
            });
            // Actualizar badges y botones con el modo guardado en el servidor
            _items.forEach(item=>{ actualizarBadgeModo(item.id); });
        }
        const tbody=document.getElementById('centrTbody');
        if (tbody) {
            if (_estudiantes.length===0) {
                tbody.innerHTML=`<tr><td colspan="${3+_items.length+1}" style="text-align:center;padding:2rem;color:#94a3b8;">Sin estudiantes matriculados en Moodle.</td></tr>`;
            } else {
                tbody.innerHTML=_estudiantes.map((est,idx)=>{
                    let row=`<tr><td style="text-align:center;color:#94a3b8;font-size:.82rem;">${idx+1}</td><td><strong style="font-size:.87rem;">${escHtml(est.nombre)}</strong></td><td style="text-align:center;font-size:.84rem;">${escHtml(est.ci)}</td>`;
                    _items.forEach(item=>{
                        if(item.is_manual===true){
                            const raw=(item.grades??{})[est.moodle_user_id]??null;
                            const val=raw!==null?parseFloat(raw).toFixed(2):'';
                            row+=`<td style="text-align:center;padding:2px 4px;"><input type="number" class="manual-grade-input" data-item-id="${item.id}" data-user-id="${est.moodle_user_id}" min="0" max="${item.max}" step="0.01" value="${escHtml(val)}" placeholder="—" style="width:75px;text-align:center;border:1px solid #cbd5e1;border-radius:6px;padding:3px 5px;font-size:.83rem;background:#fff;"></td>`;
                        } else {
                            const np=notaPonderada(item,est.moodle_user_id);
                            const raw=(item.grades??{})[est.moodle_user_id]??null;
                            const max=item.max!=null?parseFloat(item.max):null;
                            const cls='nota-cell '+(np===null?'sin-nota':colorClass(np/(getPeso(item.id)||1),false));
                            const lbl=np!==null?np.toFixed(2):'—';
                            const tip=raw!==null?'Moodle: '+parseFloat(raw).toFixed(2)+' / '+(max??'?')+' pts':'Sin calificación en Moodle';
                            row+=`<td id="cNota_${est.moodle_user_id}_${item.id}" class="${cls}" style="text-align:center;" title="${tip}">${lbl}</td>`;
                        }
                    });
                    const nf=calcNotaFinal(est.moodle_user_id);
                    const modoCalc=detectarModoCalculo(); const incompleta=modoCalc!=='cumulative'&&modoCalc!=='average';
                    let nfCls='nota-final-cell',nfHtml='—',nfTitle='';
                    if(nf!==null){let pt=0;_items.forEach(i=>{pt+=getPeso(i.id);});pt=Math.round(pt*100)/100;if(incompleta){nfCls+=' centr-incompleta';nfHtml=`<span style="font-size:.85rem;">${nf.toFixed(2)}</span><br><span style="font-size:.62rem;color:#f59e0b;font-weight:600;">⚠ /${pt}%</span>`;nfTitle=`Suma parcial: ${nf.toFixed(2)} pts sobre ${pt}% asignado`;}else{nfCls+=' '+colorClass(nf,true);nfHtml=nf.toFixed(2);}}
                    row+=`<td id="cNFinal_${est.moodle_user_id}" class="${nfCls}" style="text-align:center;font-weight:700;" title="${nfTitle}">${nfHtml}</td></tr>`;
                    return row;
                }).join('');
            }
        }
        actualizarBadgeSuma();
        document.getElementById('btnExportarCentralizador')?.style&&(document.getElementById('btnExportarCentralizador').style.display='inline-flex');
    }

    function guardarPesosCentr() {
        if (!_moduloId) return;
        const btn=document.getElementById('btnGuardarPesosCentr');
        const csrf=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')||'';
        const items=_items.map(item=>({id:item.id,name:item.name,module:item.module,cmid:item.cmid,weight:getPeso(item.id),calculation_mode:getModo(item.id)}));
        const isCumulative=detectarModoCalculo()!=='average';
        if(btn){btn.disabled=true;btn.innerHTML='<i class="ri-loader-4-line"></i> Guardando…';}
        fetch('/virtual/docente/modulos/'+_moduloId+'/academico/ponderaciones',{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
            body:JSON.stringify({items,is_cumulative:isCumulative}),
        })
        .then(r=>r.json())
        .then(data=>{
            if(btn){btn.disabled=false;btn.innerHTML='<i class="ri-save-line"></i> Guardar ponderaciones';}
            if(data.success){_items.forEach(item=>{item.weight=getPeso(item.id);});mostrarToastCentr('success','Ponderaciones guardadas correctamente.');const btnSync=document.getElementById('btnSincronizarMoodle');if(btnSync)btnSync.style.display='inline-flex';}
            else mostrarToastCentr('error',data.message||'Error al guardar.');
        })
        .catch(()=>{if(btn){btn.disabled=false;btn.innerHTML='<i class="ri-save-line"></i> Guardar ponderaciones';}mostrarToastCentr('error','Error de conexión.');});
    }

    function mostrarToastCentr(tipo,msg){
        const t=document.createElement('div');
        t.style.cssText='position:fixed;top:20px;right:20px;z-index:9999;display:flex;align-items:center;gap:10px;padding:12px 20px;border-radius:8px;background:white;box-shadow:0 4px 20px rgba(0,0,0,.15);font-size:.9rem;font-weight:500;transition:all .3s;transform:translateX(400px);opacity:0;';
        const ic=tipo==='success'?'#16a34a':'#dc2626';
        t.innerHTML=`<i class="ri-${tipo==='success'?'check-circle-line':'error-warning-line'}" style="color:${ic};font-size:1.2rem;"></i><span>${msg}</span>`;
        document.body.appendChild(t);
        requestAnimationFrame(()=>{t.style.transform='translateX(0)';t.style.opacity='1';});
        setTimeout(()=>{t.style.transform='translateX(400px)';t.style.opacity='0';setTimeout(()=>t.remove(),300);},3200);
    }

    function sincronizarConMoodle() {
        if(!_moduloId||!_items.length) return;
        const btn=document.getElementById('btnSincronizarMoodle');
        const csrf=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')||'';
        const items=_items.map(item=>({id:item.id,module:item.module,cmid:item.cmid,peso:getPeso(item.id),peso_original:parseFloat(item.max)||0,modo:getModo(item.id),grades:item.grades??{}}));
        const resumen=items.map(it=>`• ${_items.find(i=>i.id===it.id)?.name??it.id}: ${it.peso_original} → ${it.peso} pts (${it.modo==='mantener'?'Mantiene nota':'Recalcula nota'})`).join('\n');
        if(!confirm(`Se actualizará en Moodle:\n\n${resumen}\n\nAfectará las notas de ${_estudiantes.length} estudiante(s).\n\n¿Continuar?`)) return;
        if(btn){btn.disabled=true;btn.innerHTML='<i class="ri-loader-4-line"></i> Sincronizando…';}
        fetch('/virtual/docente/modulos/'+_moduloId+'/centralizador/sincronizar-moodle',{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
            body:JSON.stringify({items}),
        })
        .then(r=>r.json())
        .then(data=>{
            if(btn){btn.disabled=false;btn.innerHTML='<i class="ri-refresh-line"></i> Sincronizar con Moodle';}
            if(data.success){
                mostrarToastCentr('success',data.mensaje||'Moodle actualizado.');
                _items.forEach(item=>{item.max=getPeso(item.id);});
                renderCentralizador({ grade_items: _items, estudiantes: _estudiantes, modulo_id: _moduloId, manual_mode: _manualMode });
            }
            else mostrarToastCentr('error',data.mensaje||'Error al sincronizar.');
        })
        .catch(()=>{if(btn){btn.disabled=false;btn.innerHTML='<i class="ri-refresh-line"></i> Sincronizar con Moodle';}mostrarToastCentr('error','Error de conexión.');});
    }

    function exportarCentralizadorCSV(){
        if(!_items.length||!_estudiantes.length) return;
        const rows=[['#','Estudiante','CI',..._items.map(i=>`"${i.name} (${MOD_LABELS_C[i.module]||i.module} · ${getPeso(i.id)}% · ${getModo(i.id)})"`),'Nota Final'].join(',')];
        _estudiantes.forEach((est,idx)=>{const row=[idx+1,`"${est.nombre}"`,est.ci,..._items.map(item=>{const np=notaPonderada(item,est.moodle_user_id);return np!==null?np.toFixed(2):''}),(()=>{const nf=calcNotaFinal(est.moodle_user_id);return nf!==null?nf.toFixed(2):'';})()];rows.push(row.join(','));});
        const blob=new Blob(['﻿'+rows.join('\n')],{type:'text/csv;charset=utf-8;'});
        const a=Object.assign(document.createElement('a'),{href:URL.createObjectURL(blob),download:'centralizador_notas.csv'});
        a.click();URL.revokeObjectURL(a.href);
    }

    window.cargarCentralizador = function(moduloId) {
        _moduloId=moduloId;
        const loading=document.getElementById('centrLoading'); const contenido=document.getElementById('centrContenido');
        const msgInicial=document.getElementById('centrMsgInicial'); const errDiv=document.getElementById('centrError');
        const errMsg=document.getElementById('centrErrorMsg');
        if(loading) loading.style.display='block'; if(contenido) contenido.style.display='none';
        if(msgInicial) msgInicial.style.display='none'; if(errDiv) errDiv.style.display='none';
        const csrf=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')||'';
        fetch('/virtual/docente/modulos/'+moduloId+'/academico/calificaciones',{headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}})
        .then(r=>{if(!r.ok) throw new Error('HTTP '+r.status); return r.json();})
        .then(data=>{
            if(loading) loading.style.display='none';
            if(!data.success){if(errMsg) errMsg.textContent=data.message||'Error desconocido.';if(errDiv) errDiv.style.display='block';return;}
            data.modulo_id=moduloId; renderCentralizador(data); if(contenido) contenido.style.display='block';
        })
        .catch(()=>{if(loading) loading.style.display='none';if(errMsg) errMsg.textContent='Error de conexión.';if(errDiv) errDiv.style.display='block';});
    };

    // Manual grade inputs: update item.grades on change and recalculate
    document.addEventListener('input', function(e){
        if(!e.target.classList.contains('manual-grade-input')) return;
        const itemId=parseInt(e.target.getAttribute('data-item-id'));
        const userId=parseInt(e.target.getAttribute('data-user-id'));
        const val=e.target.value===''?null:parseFloat(e.target.value);
        const item=_items.find(i=>i.id===itemId);
        if(item){if(!item.grades)item.grades={};item.grades[userId]=val;}
        recalcularCeldas();
        _manualGradesDirty=true;
        const btn=document.getElementById('btnGuardarCalifManuales');
        if(btn){btn.style.display='inline-flex';btn.style.background='rgba(99,102,241,.25)';}
    });

    // Delete manual item button
    document.addEventListener('click', function(e){
        const btn=e.target.closest('.centr-del-item-btn');
        if(!btn) return;
        const itemId=parseInt(btn.getAttribute('data-item-id'));
        if(!confirm('¿Eliminar esta actividad y todas sus calificaciones?')) return;
        const csrf=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')||'';
        fetch('/virtual/docente/modulos/'+_moduloId+'/academico/actividad-manual/'+itemId,{
            method:'DELETE',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}
        }).then(r=>r.json()).then(data=>{
            if(data.success) window.cargarCentralizador(_moduloId);
            else alert(data.message||'Error al eliminar la actividad.');
        }).catch(()=>alert('Error de conexión.'));
    });

    function guardarCalificacionesManuales(){
        if(!_moduloId) return;
        const csrf=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')||'';
        const grades={};
        _items.filter(i=>i.is_manual===true).forEach(item=>{
            grades[item.id]={};
            _estudiantes.forEach(est=>{
                const inp=document.querySelector('.manual-grade-input[data-item-id="'+item.id+'"][data-user-id="'+est.moodle_user_id+'"]');
                grades[item.id][est.moodle_user_id]=inp&&inp.value!==''?parseFloat(inp.value):null;
            });
        });
        const btn=document.getElementById('btnGuardarCalifManuales');
        if(btn){btn.disabled=true;btn.innerHTML='<i class="ri-loader-4-line"></i> Guardando…';}
        fetch('/virtual/docente/modulos/'+_moduloId+'/academico/calificaciones-manuales',{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
            body:JSON.stringify({grades})
        }).then(r=>r.json()).then(data=>{
            if(btn){btn.disabled=false;btn.innerHTML='<i class="ri-save-line"></i> Guardar Calificaciones';btn.style.background='rgba(22,163,74,.2)';}
            if(!data.success) alert(data.message||'Error al guardar.');
            else _manualGradesDirty=false;
        }).catch(()=>{if(btn){btn.disabled=false;btn.innerHTML='<i class="ri-save-line"></i> Guardar Calificaciones';}alert('Error de conexión.');});
    }

    function abrirModalNuevaActividad(){
        const modal=document.getElementById('modalNuevaActividad');
        if(!modal) return;
        document.getElementById('nuevaActNombre').value='';
        document.getElementById('nuevaActMax').value='100';
        document.getElementById('nuevaActPeso').value='0';
        document.getElementById('nuevaActTipo').value='manual';
        if(!_modalNuevaActInst) _modalNuevaActInst = new bootstrap.Modal(modal);
        _modalNuevaActInst.show();
    }

    function confirmarNuevaActividad(){
        const name=document.getElementById('nuevaActNombre')?.value?.trim();
        const type=document.getElementById('nuevaActTipo')?.value||'manual';
        const max=parseFloat(document.getElementById('nuevaActMax')?.value)||100;
        const weight=parseFloat(document.getElementById('nuevaActPeso')?.value)||0;
        if(!name){alert('El nombre de la actividad es requerido.');return;}
        const csrf=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')||'';
        const btnOk=document.getElementById('btnConfirmarNuevaAct');
        if(btnOk){btnOk.disabled=true;btnOk.innerHTML='<i class="ri-loader-4-line"></i> Creando…';}
        fetch('/virtual/docente/modulos/'+_moduloId+'/academico/actividad-manual',{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
            body:JSON.stringify({name,type,max,weight})
        }).then(r=>r.json()).then(data=>{
            if(btnOk){btnOk.disabled=false;btnOk.innerHTML='Agregar';}
            if(_modalNuevaActInst) _modalNuevaActInst.hide();
            if(data.success) window.cargarCentralizador(_moduloId);
            else alert(data.message||'Error al crear la actividad.');
        }).catch(()=>{if(btnOk){btnOk.disabled=false;btnOk.innerHTML='Agregar';}alert('Error de conexión.');});
    }

    document.addEventListener('DOMContentLoaded',function(){
        document.getElementById('btnCargarCentralizador')?.addEventListener('click',function(){window.cargarCentralizador(this.getAttribute('data-modulo-id'));});
        document.getElementById('btnGuardarPesosCentr')?.addEventListener('click',guardarPesosCentr);
        document.getElementById('btnSincronizarMoodle')?.addEventListener('click',sincronizarConMoodle);
        document.getElementById('btnExportarCentralizador')?.addEventListener('click',exportarCentralizadorCSV);
        document.getElementById('btnAgregarActividad')?.addEventListener('click',abrirModalNuevaActividad);
        document.getElementById('btnGuardarCalifManuales')?.addEventListener('click',guardarCalificacionesManuales);
        document.getElementById('btnConfirmarNuevaAct')?.addEventListener('click',confirmarNuevaActividad);
        document.getElementById('btnCentrPonderar')?.addEventListener('click',()=>confirmarModo('ponderar'));
        document.getElementById('btnCentrMantener')?.addEventListener('click',()=>confirmarModo('mantener'));
        document.getElementById('modalCentrPond')?.addEventListener('hidden.bs.modal',function(){
            if(_pendingChange){const inp=document.getElementById('cPeso_'+_pendingChange.item.id);if(inp){inp.value=_pendingChange.pesoAnterior.toFixed(2);inp.setAttribute('data-peso-prev',_pendingChange.pesoAnterior.toFixed(2));}  _pendingChange=null;actualizarBadgeSuma();}
        });
    });
})();

// ============================================================
// Editor — subida de archivos
// ============================================================
function abrirModalSubirArchivo() {
    var sectionId = prompt('Número de sección donde agregar el recurso:');
    if (sectionId === null) return;
    sectionId = parseInt(sectionId);
    if (isNaN(sectionId) || sectionId < 0) { alert('Número de sección inválido.'); return; }
    var name = prompt('Nombre del recurso:');
    if (!name || name.trim() === '') { alert('Nombre requerido.'); return; }
    var fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = '.pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.mp4,.jpg,.png';
    fileInput.onchange = function() {
        if (fileInput.files.length > 0) {
            var courseId = document.querySelector('.btn-rename-sec')?.getAttribute('data-course-id');
            if (courseId) { ActividadesEditor.iniciarSubidaArchivo(fileInput.files[0], sectionId, name.trim(), parseInt(courseId)); }
            else { alert('No se pudo determinar el curso. Recarga la página.'); }
        }
    };
    fileInput.click();
}
</script>

<script src="{{ URL::asset('build/libs/sortablejs/Sortable.min.js') }}"></script>
<script src="{{ URL::asset('build/js/actividades-editor.js') }}"></script>
@endsection
