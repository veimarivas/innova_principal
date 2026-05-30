# Cronograma General Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Create a centralized calendar view showing all module schedules filtered by Sede → Sucursal → Oferta Académica using FullCalendar.

**Architecture:** New CronogramaController with index view and AJAX endpoints for cascading filters and calendar data. FullCalendar displays each module as a date-range event.

**Tech Stack:** Laravel 10, Blade, jQuery, FullCalendar v6

---

### Task 1: Create CronogramaController

**Files:**
- Create: `app/Http/Controllers/CronogramaController.php`

- [ ] **Step 1: Create the controller file**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Models\Sucursale;
use App\Models\OfertasAcademica;
use App\Models\Modulo;
use App\Models\Horario;
use Illuminate\Http\Request;

class CronogramaController extends Controller
{
    public function index()
    {
        $sedes = Sede::orderBy('nombre', 'asc')->get();
        return view('admin.cronogramas.index', compact('sedes'));
    }

    public function listarSucursales($sedeId)
    {
        $sucursales = Sucursale::where('sede_id', $sedeId)
            ->orderBy('nombre', 'asc')
            ->get();
        return response()->json(['data' => $sucursales]);
    }

    public function listarOfertas($sucursalId)
    {
        $ofertas = OfertasAcademica::with(['posgrado', 'modalidad', 'fase'])
            ->where('sucursale_id', $sucursalId)
            ->orderBy('gestion', 'desc')
            ->orderBy('codigo', 'asc')
            ->get();
        return response()->json(['data' => $ofertas]);
    }

    public function listarHorarios(Request $request)
    {
        $validator = validator($request->all(), [
            'oferta_id' => 'required|exists:ofertas_academicas,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $modulos = Modulo::with([
                'docente.persona',
                'horarios.trabajadorCargo.trabajador.persona',
                'oferta_academica',
            ])
            ->where('ofertas_academica_id', $request->oferta_id)
            ->orderBy('n_modulo', 'asc')
            ->get();

        return response()->json(['data' => $modulos]);
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/Http/Controllers/CronogramaController.php
git commit -m "feat: add CronogramaController with filter and calendar endpoints"
```

---

### Task 2: Add routes for cronograma

**Files:**
- Modify: `routes/web.php:104` (add 4 routes before the closing `});` of the posgrados group)

- [ ] **Step 1: Add routes to the posgrados group**

In `routes/web.php`, find line 104 (the `Route::get('/personas/listar-trabajadores', ...)` line) and add these 4 routes after it, before the closing `});`:

```php
    Route::get('/personas/listar-trabajadores', [ModuloController::class, 'listarTrabajadores'])->name('personas.listar-trabajadores');

    // Cronograma
    Route::get('/cronograma', [CronogramaController::class, 'index'])->name('cronograma.index');
    Route::get('/cronograma/sucursales/{sedeId}', [CronogramaController::class, 'listarSucursales'])->name('cronograma.sucursales');
    Route::get('/cronograma/ofertas/{sucursalId}', [CronogramaController::class, 'listarOfertas'])->name('cronograma.ofertas');
    Route::get('/cronograma/horarios', [CronogramaController::class, 'listarHorarios'])->name('cronograma.horarios');
});
```

Also add the import at the top of the file. Find the existing controller imports and add:

```php
use App\Http\Controllers\CronogramaController;
```

- [ ] **Step 2: Commit**

```bash
git add routes/web.php
git commit -m "feat: add cronograma routes to posgrados group"
```

---

### Task 3: Create the cronograma view

**Files:**
- Create: `resources/views/admin/cronogramas/index.blade.php`

- [ ] **Step 1: Create the view with cascading filters, calendar, and modal**

```blade
@extends('layouts.master')

@section('title', 'Cronograma General')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-sm-0">Cronograma General</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">Posgrados</a></li>
                    <li class="breadcrumb-item active">Cronograma</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- Filtros en cascada --}}
<div class="row mb-3">
    <div class="col-md-4">
        <label class="form-label fw-semibold">Sede</label>
        <select class="form-select" id="filtroSede">
            <option value="">Seleccionar sede...</option>
            @foreach($sedes as $sede)
                <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Sucursal</label>
        <select class="form-select" id="filtroSucursal" disabled>
            <option value="">Seleccionar sucursal...</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Oferta Académica</label>
        <select class="form-select" id="filtroOferta" disabled>
            <option value="">Seleccionar oferta...</option>
        </select>
    </div>
</div>

{{-- Calendario --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div id="calendarCronograma"></div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Modal: Detalle del Módulo --}}
<div class="modal fade" id="modalDetalleModulo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-2">
                    <div id="detModuloColorBar" style="width:6px;height:32px;border-radius:4px;"></div>
                    <div>
                        <h5 class="modal-title" id="detModuloNombre"></h5>
                        <small class="text-muted" id="detModuloOferta"></small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="mb-2">
                            <small class="text-muted text-uppercase fw-semibold" style="font-size:0.65rem;">Docente</small>
                            <div id="detModuloDocente" class="fw-semibold"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <small class="text-muted text-uppercase fw-semibold" style="font-size:0.65rem;">Estado</small>
                            <div id="detModuloEstado"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <small class="text-muted text-uppercase fw-semibold" style="font-size:0.65rem;">Fechas</small>
                            <div id="detModuloFechas" class="fw-semibold"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <small class="text-muted text-uppercase fw-semibold" style="font-size:0.65rem;">Total Sesiones</small>
                            <div id="detModuloSesiones" class="fw-semibold"></div>
                        </div>
                    </div>
                </div>

                <label class="form-label fw-semibold mb-2" style="font-size:0.8rem;">Horarios</label>
                <div id="detModuloHorariosTable">
                    <div class="text-center text-muted py-4">Sin horarios registrados</div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
<script src="{{ URL::asset('build/libs/fullcalendar/index.global.min.js') }}"></script>
<script>
(function() {
    'use strict';
    const CSRF = '{{ csrf_token() }}';
    let calendar = null;
    let currentOfertaId = null;

    function toast(tipo, mensaje) {
        const iconMap = { success: 'ri-check-double-line', error: 'ri-close-circle-line', warning: 'ri-alert-line' };
        const el = document.createElement('div');
        el.className = 'toast-notify ' + tipo;
        el.innerHTML = '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') + '"></i></div><div class="toast-body-text"><span>' + mensaje + '</span></div><button class="toast-close" title="Cerrar"><i class="ri-close-line"></i></button>';
        let c = document.getElementById('toastContainer');
        if (!c) { c = document.createElement('div'); c.id = 'toastContainer'; c.className = 'toast-container'; document.body.appendChild(c); }
        c.appendChild(el);
        el.querySelector('.toast-close').addEventListener('click', () => { el.classList.add('hiding'); el.addEventListener('animationend', () => el.remove(), { once: true }); });
        setTimeout(() => { el.classList.add('hiding'); el.addEventListener('animationend', () => el.remove(), { once: true }); }, 4500);
    }

    function escHtml(str) { return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }
    function formatDate(str) { if (!str) return '—'; const p = str.split('-'); return p[2] + '/' + p[1] + '/' + p[0]; }
    function formatTime(str) { if (!str) return '—'; return str.substring(0, 5); }
    function openModal(id) { new bootstrap.Modal(document.getElementById(id)).show(); }

    /* Cascading filters */
    $('#filtroSede').on('change', function() {
        const sedeId = $(this).val();
        $('#filtroSucursal').html('<option value="">Seleccionar sucursal...</option>').prop('disabled', !sedeId);
        $('#filtroOferta').html('<option value="">Seleccionar oferta...</option>').prop('disabled', true);
        if (calendar) calendar.removeAllEvents();

        if (!sedeId) return;

        $.ajax({ url: '/admin/posgrados/cronograma/sucursales/' + sedeId })
            .done(function(r) {
                let html = '<option value="">Seleccionar sucursal...</option>';
                (r.data || []).forEach(function(s) {
                    html += '<option value="' + s.id + '">' + escHtml(s.nombre) + '</option>';
                });
                $('#filtroSucursal').html(html);
            })
            .fail(function() { toast('error', 'Error al cargar sucursales.'); });
    });

    $('#filtroSucursal').on('change', function() {
        const sucursalId = $(this).val();
        $('#filtroOferta').html('<option value="">Seleccionar oferta...</option>').prop('disabled', !sucursalId);
        if (calendar) calendar.removeAllEvents();

        if (!sucursalId) return;

        $.ajax({ url: '/admin/posgrados/cronograma/ofertas/' + sucursalId })
            .done(function(r) {
                let html = '<option value="">Seleccionar oferta...</option>';
                (r.data || []).forEach(function(o) {
                    const label = (o.codigo || '') + ' — ' + (o.posgrado ? o.posgrado.nombre : '') + (o.fase ? ' (' + o.fase.nombre + ')' : '');
                    html += '<option value="' + o.id + '">' + escHtml(label) + '</option>';
                });
                $('#filtroOferta').html(html);
            })
            .fail(function() { toast('error', 'Error al cargar ofertas.'); });
    });

    $('#filtroOferta').on('change', function() {
        const ofertaId = $(this).val();
        currentOfertaId = ofertaId;
        if (calendar) calendar.refetchEvents();
    });

    /* FullCalendar */
    function initCalendar() {
        const calendarEl = document.getElementById('calendarCronograma');
        if (!calendarEl) return;

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                list: 'Lista'
            },
            editable: false,
            selectable: true,
            eventColor: null,
            events: function(fetchInfo, successCallback, failureCallback) {
                if (!currentOfertaId) { successCallback([]); return; }
                $.ajax({ url: '/admin/posgrados/cronograma/horarios', data: { oferta_id: currentOfertaId } })
                    .done(function(r) {
                        const events = [];
                        (r.data || []).forEach(function(mod) {
                            const color = mod.color || '#6366f1';
                            const docente = mod.docente && mod.docente.persona
                                ? (mod.docente.persona.nombres || '') + ' ' + (mod.docente.persona.apellido_paterno || '') + ' ' + (mod.docente.persona.apellido_materno || '')
                                : 'Sin asignar';
                            const fechaInicio = mod.fecha_inicio ? String(mod.fecha_inicio).substring(0, 10) : '';
                            const fechaFin = mod.fecha_fin ? String(mod.fecha_fin).substring(0, 10) : '';
                            // FullCalendar end dates are exclusive
                            let endDate = fechaFin;
                            if (fechaFin) {
                                const d = new Date(fechaFin + 'T00:00:00');
                                d.setDate(d.getDate() + 1);
                                endDate = d.toISOString().split('T')[0];
                            }
                            events.push({
                                id: 'm-' + mod.id,
                                title: mod.nombre,
                                start: fechaInicio,
                                end: endDate,
                                backgroundColor: color,
                                borderColor: color,
                                color: '#ffffff',
                                extendedProps: {
                                    modulo_id: mod.id,
                                    modulo_nombre: mod.nombre,
                                    modulo_color: color,
                                    oferta_id: mod.ofertas_academica_id,
                                    oferta_codigo: mod.oferta_academica ? mod.oferta_academica.codigo : '',
                                    docente_nombre: docente.trim(),
                                    fecha_inicio: fechaInicio,
                                    fecha_fin: fechaFin,
                                    estado: mod.estado || 'No Inicio',
                                    horarios_count: mod.horarios ? mod.horarios.length : 0,
                                    horarios: mod.horarios || []
                                }
                            });
                        });
                        successCallback(events);
                    })
                    .fail(function() { failureCallback(); });
            },
            eventClick: function(info) {
                openDetalleModulo(info.event);
            }
        });
        calendar.render();
    }

    /* Modal detalle */
    function openDetalleModulo(event) {
        const props = event.extendedProps;
        $('#detModuloColorBar').css('background', props.modulo_color);
        $('#detModuloNombre').text(props.modulo_nombre);
        $('#detModuloOferta').text(props.oferta_codigo || '');
        $('#detModuloDocente').text(props.docente_nombre);
        $('#detModuloFechas').text(formatDate(props.fecha_inicio) + ' → ' + formatDate(props.fecha_fin));
        $('#detModuloSesiones').text(props.horarios_count + ' sesiones');

        const estadoClass = props.estado === 'En Desarrollo' ? 'bg-success' : (props.estado === 'Concluido' ? 'bg-info' : 'bg-warning');
        $('#detModuloEstado').html('<span class="badge ' + estadoClass + '">' + escHtml(props.estado) + '</span>');

        const horarios = props.horarios || [];
        if (horarios.length === 0) {
            $('#detModuloHorariosTable').html('<div class="text-center text-muted py-4">Sin horarios registrados</div>');
        } else {
            let html = '<div class="table-responsive"><table class="table table-sm table-hover align-middle">';
            html += '<thead class="table-light"><tr><th>Fecha</th><th>Hora</th><th>Estado</th><th>Trabajador</th></tr></thead><tbody>';
            horarios.forEach(function(h) {
                const hEstado = h.estado === 'Desarrollado' ? 'bg-success' : (h.estado === 'Postergado' ? 'bg-warning' : 'bg-secondary');
                const trabajador = h.trabajador_cargo ? (h.trabajador_cargo.nombre_cargo || 'Sin asignar') : 'Sin asignar';
                html += '<tr>' +
                    '<td>' + formatDate(h.fecha ? String(h.fecha).substring(0, 10) : '') + '</td>' +
                    '<td>' + formatTime(h.hora_inicio) + ' — ' + formatTime(h.hora_fin) + '</td>' +
                    '<td><span class="badge ' + hEstado + '">' + escHtml(h.estado || 'Confirmado') + '</span></td>' +
                    '<td>' + escHtml(trabajador) + '</td>' +
                    '</tr>';
            });
            html += '</tbody></table></div>';
            $('#detModuloHorariosTable').html(html);
        }

        openModal('modalDetalleModulo');
    }

    /* Init */
    document.addEventListener('DOMContentLoaded', function() {
        initCalendar();
    });
})();
</script>
@endsection
```

- [ ] **Step 2: Commit**

```bash
git add resources/views/admin/cronogramas/index.blade.php
git commit -m "feat: add cronograma view with cascading filters and FullCalendar"
```

---

### Task 4: Add cronograma link to sidebar navigation

**Files:**
- Modify: `resources/views/layouts/sidebar.blade.php` (or wherever the sidebar menu is defined)

- [ ] **Step 1: Find the sidebar menu and add the cronograma link**

Search for the posgrados menu section in the sidebar and add a link for the cronograma. The exact location depends on the existing sidebar structure. Look for links like "Posgrados" or "Ofertas Académicas" and add:

```blade
<li class="nav-item">
    <a href="{{ route('admin.posgrads.cronograma.index') }}" class="nav-link">
        <i class="ri-calendar-line"></i>
        <span>Cronograma</span>
    </a>
</li>
```

- [ ] **Step 2: Commit**

```bash
git add resources/views/layouts/sidebar.blade.php
git commit -m "feat: add cronograma link to sidebar navigation"
```
