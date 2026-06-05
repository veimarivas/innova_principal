@extends('layouts.master')
@section('title', 'Detalle del Módulo - ' . ($modulo->nombre ?? ''))

@section('css')
@include('admin.ofertas-academicas.partials.modulo-detalle-styles')
@endsection

@section('content')
<div class="modulo-detalle-page">
<div class="modulo-detalle-header">
    <div class="container-fluid">
        <div class="mdh-inner">
            <div class="mdh-left">
                <div class="mdh-icon-wrap">
                    <i class="ri-layout-grid-line"></i>
                </div>
                <div class="mdh-text-block">
                    <h1>Detalle del Módulo</h1>
                    <div class="modulo-badge-display">
                        <span class="color-dot" style="background: {{ $modulo->color ?? '#fc7b04' }};"></span>
                        <span class="modulo-nombre-display">{{ $modulo->nombre ?? 'Sin nombre' }}</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.posgrads.ofertas.detalle', $ofertaId) }}" class="mdh-btn-back">
                <i class="ri-arrow-left-line"></i> Volver a la Oferta
            </a>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="info-grid">
        <div class="info-card">
            <label><i class="ri-hashtag"></i> Número de Módulo</label>
            <div class="value">{{ $modulo->n_modulo ?? '—' }}</div>
        </div>
        <div class="info-card">
            <label><i class="ri-user-line"></i> Docente</label>
            <div class="value">
                @if($modulo->docente && $modulo->docente->persona)
                    {{ $modulo->docente->persona->nombres ?? '' }} {{ $modulo->docente->persona->apellido_paterno ?? '' }} {{ $modulo->docente->persona->apellido_materno ?? '' }}
                @else
                    Sin docente asignado
                @endif
            </div>
        </div>
        <div class="info-card">
            <label><i class="ri-calendar-line"></i> Fecha Inicio</label>
            <div class="value">{{ $modulo->fecha_inicio ? \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') : '—' }}</div>
        </div>
        <div class="info-card">
            <label><i class="ri-calendar-check-line"></i> Fecha Fin</label>
            <div class="value">{{ $modulo->fecha_fin ? \Carbon\Carbon::parse($modulo->fecha_fin)->format('d/m/Y') : '—' }}</div>
        </div>
    </div>

    <div class="tabs-container">
            <div class="tabs-header">
                <button class="tab-btn active" data-tab="matriculaciones">
                    <i class="ri-user-follow-line"></i> Matriculaciones
                </button>
                <button class="tab-btn" data-tab="academico">
                    <i class="ri-book-open-line"></i> Académico
                </button>
                <button class="tab-btn" data-tab="actividades">
                    <i class="ri-task-line"></i> Actividades
                </button>
                <button class="tab-btn" data-tab="centralizador">
                    <i class="ri-table-line"></i> Centralizador de Notas
                </button>
            </div>

            <div class="tab-content">
                <div class="tab-pane active" id="tab-matriculaciones">
                    <div class="tab-actions">
                        <div class="tab-title-section">
                            <i class="ri-user-follow-line"></i>
                            <span class="tab-title">Estudiantes Inscritos</span>
                        </div>
                        <div class="tab-actions-buttons">
                            @if($modulo->moodle_course_id)
                            <button class="btn-matricular-moodle" id="btnMatricularTodosMoodle" data-modulo-id="{{ $modulo->id }}">
                                <i class="ri-graduation-cap-line"></i> Matricular Todos en Moodle
                            </button>
                            @else
                            <button class="btn-matricular-moodle" disabled title="El módulo no tiene curso en Moodle">
                                <i class="ri-graduation-cap-line"></i> Sin curso Moodle
                            </button>
                            @endif
                        </div>
                    </div>

                    @if(count($inscritos) > 0)
                    <div class="academico-tbl-wrap">
                        <table class="table-matriculas" style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr>
                                    <th style="padding:.7rem .5rem;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#fff;text-align:left;background:linear-gradient(135deg,#2c3e50,#34495e);">#</th>
                                    <th style="padding:.7rem .5rem;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#fff;text-align:left;background:linear-gradient(135deg,#2c3e50,#34495e);">Estudiante</th>
                                    <th style="padding:.7rem .5rem;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#fff;text-align:left;background:linear-gradient(135deg,#2c3e50,#34495e);">CI</th>
                                    <th style="padding:.7rem .5rem;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#fff;text-align:left;background:linear-gradient(135deg,#2c3e50,#34495e);">Celular</th>
                                    <th style="padding:.7rem .5rem;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#fff;text-align:left;background:linear-gradient(135deg,#2c3e50,#34495e);">Correo</th>
                                    <th style="padding:.7rem .5rem;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#fff;text-align:left;background:linear-gradient(135deg,#2c3e50,#34495e);">Matrícula</th>
                                    <th style="padding:.7rem .5rem;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#fff;text-align:left;background:linear-gradient(135deg,#2c3e50,#34495e);">Estado Moodle</th>
                                    <th style="padding:.7rem .5rem;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#fff;text-align:left;background:linear-gradient(135deg,#2c3e50,#34495e);">Cuenta Sistema</th>
                                    <th style="padding:.7rem .5rem;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#fff;text-align:center;background:linear-gradient(135deg,#2c3e50,#34495e);">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inscritos as $i => $inscrito)
                                <tr style="transition:background .1s;border-bottom:1px solid #e9ecef;">
                                    <td style="padding:.7rem .5rem;font-size:.85rem;color:#495057;text-align:center;">{{ $i + 1 }}</td>
                                    <td style="padding:.7rem .5rem;font-size:.85rem;color:#495057;"><strong class="estudiante-nombre">{{ $inscrito['estudiante_nombre'] }}</strong></td>
                                    <td style="padding:.7rem .5rem;font-size:.85rem;color:#495057;">{{ $inscrito['estudiante_ci'] }}</td>
                                    <td style="padding:.7rem .5rem;font-size:.85rem;color:#495057;">{{ $inscrito['celular'] }}</td>
                                    <td style="padding:.7rem .5rem;font-size:.85rem;color:#495057;">{{ $inscrito['correo'] }}</td>
                                    <td style="padding:.7rem .5rem;font-size:.85rem;color:#495057;">
                                        @if($inscrito['matriculado'])
                                            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;background:rgba(34,197,94,.1);color:#16a34a;">
                                                <i class="ri-check-line"></i> Matriculado
                                            </span>
                                        @else
                                            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;background:rgba(239,68,68,.1);color:#ef4444;">
                                                <i class="ri-close-line"></i> No matriculado
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding:.7rem .5rem;font-size:.85rem;color:#495057;">
                                        @if(!$inscrito['en_moodle'])
                                            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;background:rgba(100,116,139,.1);color:#64748b;">
                                                <i class="ri-close-line"></i> Sin Moodle
                                            </span>
                                        @elseif($inscrito['acceso_suspendido'])
                                            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;background:rgba(239,68,68,.1);color:#dc2626;">
                                                <i class="ri-forbid-line"></i> Suspendido
                                            </span>
                                        @else
                                            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;background:rgba(34,197,94,.1);color:#16a34a;">
                                                <i class="ri-check-line"></i> Activo
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding:.7rem .5rem;font-size:.85rem;color:#495057;">
                                        @if($inscrito['tiene_cuenta_moodle'])
                                            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;background:rgba(34,197,94,.1);color:#16a34a;">
                                                <i class="ri-user-smile-line"></i> Con cuenta
                                            </span>
                                        @else
                                            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;background:rgba(239,68,68,.1);color:#dc2626;">
                                                <i class="ri-user-unfollow-line"></i> Sin cuenta
                                            </span>
                                            @php
                                                $routeInscripciones = route('admin.posgrads.ofertas.detalle', $ofertaId) . '#tab-inscripciones';
                                            @endphp
                                            <div style="margin-top:.4rem;padding:.4rem .55rem;background:#fff7ed;border:1px solid #fed7aa;border-radius:6px;font-size:.7rem;color:#9a3412;line-height:1.5;display:flex;align-items:flex-start;gap:.3rem;max-width:260px;">
                                                <i class="ri-information-line" style="flex-shrink:0;margin-top:1px;color:#ea580c;"></i>
                                                <span>Debe ir a <a href="{{ $routeInscripciones }}" style="color:#c96004;font-weight:700;text-decoration:underline;">Inscripciones de la oferta</a> para crear la cuenta de usuario.</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding:.7rem .5rem;text-align:center;">
                                        @if($modulo->moodle_course_id)
                                            @if($inscrito['acceso_suspendido'])
                                                <button type="button" class="btn-activar btn-toggle-acceso"
                                                    style="padding:.35rem .75rem;font-size:.75rem;font-weight:600;background:rgba(252,123,4,.1);color:#c96004;border:none;border-radius:6px;cursor:pointer;transition:all .2s;"
                                                    onmouseover="this.style.background='#fc7b04';this.style.color='#fff'"
                                                    onmouseout="this.style.background='rgba(252,123,4,.1)';this.style.color='#c96004'"
                                                    data-suspender="0"
                                                    data-inscripcion-id="{{ $inscrito['id'] }}"
                                                    data-modulo-id="{{ $modulo->id }}">
                                                    <i class="ri-play-circle-line"></i> Reactivar
                                                </button>
                                            @elseif(!$inscrito['en_moodle'])
                                                @if($inscrito['tiene_cuenta_moodle'])
                                                    <button type="button" class="btn-moodle-individual"
                                                        style="padding:.35rem .75rem;font-size:.75rem;font-weight:600;background:rgba(252,123,4,.1);color:#c96004;border:none;border-radius:6px;cursor:pointer;transition:all .2s;"
                                                        onmouseover="this.style.background='#fc7b04';this.style.color='#fff'"
                                                        onmouseout="this.style.background='rgba(252,123,4,.1)';this.style.color='#c96004'"
                                                        data-inscripcion-id="{{ $inscrito['id'] }}"
                                                        data-modulo-id="{{ $modulo->id }}">
                                                        <i class="ri-graduation-cap-line"></i> Matricular
                                                    </button>
                                                @else
                                                    <span style="font-size:.7rem;color:#94a3b8;">—</span>
                                                @endif
                                            @else
                                                <button type="button" class="btn-suspender btn-toggle-acceso"
                                                    style="padding:.35rem .75rem;font-size:.75rem;font-weight:600;background:rgba(239,68,68,.1);color:#dc2626;border:none;border-radius:6px;cursor:pointer;transition:all .2s;"
                                                    onmouseover="this.style.background='#dc2626';this.style.color='#fff'"
                                                    onmouseout="this.style.background='rgba(239,68,68,.1)';this.style.color='#dc2626'"
                                                    data-suspender="1"
                                                    data-inscripcion-id="{{ $inscrito['id'] }}"
                                                    data-modulo-id="{{ $modulo->id }}">
                                                    <i class="ri-forbid-line"></i> Suspender
                                                </button>
                                            @endif
                                        @else
                                            <span style="font-size:.7rem;color:#94a3b8;">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="ri-user-unfollow-line"></i>
                        <p>No hay estudiantes inscritos en este módulo</p>
                    </div>
                    @endif
                </div>

                <div class="tab-pane" id="tab-academico">
                    <div class="tab-actions">
                        <div class="tab-title-section">
                            <i class="ri-group-line"></i>
                            <span class="tab-title">Listado General de Estudiantes</span>
                        </div>
                        <div class="tab-actions-buttons">
                            <span style="font-size:0.75rem;color:#94a3b8;display:inline-flex;align-items:center;gap:.3rem;">
                                <i class="ri-sort-desc"></i> Ord: apellido paterno ↓
                            </span>
                        </div>
                    </div>

                    @if(count($inscritos) > 0)
                    @php
                        $totalEst = count($inscritos);
                        $conEstudios = count(array_filter($inscritos, fn($i) => count($i['estudios'] ?? []) > 0));
                        $conPlan = count(array_filter($inscritos, fn($i) => ($i['plan_pago'] ?? '—') !== '—'));
                        $conCelular = count(array_filter($inscritos, fn($i) => !empty($i['celular']) && $i['celular'] !== '—'));
                    @endphp

                    <div class="academico-summary">
                        <div class="academico-summary-card">
                            <div class="academico-summary-icon primary"><i class="ri-group-line"></i></div>
                            <div>
                                <div class="academico-summary-val">{{ $totalEst }}</div>
                                <div class="academico-summary-lbl">Estudiantes</div>
                            </div>
                        </div>
                        <div class="academico-summary-card">
                            <div class="academico-summary-icon warning"><i class="ri-graduation-cap-line"></i></div>
                            <div>
                                <div class="academico-summary-val">{{ $conEstudios }}</div>
                                <div class="academico-summary-lbl">Con estudios</div>
                            </div>
                        </div>
                        <div class="academico-summary-card">
                            <div class="academico-summary-icon info"><i class="ri-funds-line"></i></div>
                            <div>
                                <div class="academico-summary-val">{{ $conPlan }}</div>
                                <div class="academico-summary-lbl">Plan de pagos</div>
                            </div>
                        </div>
                        <div class="academico-summary-card">
                            <div class="academico-summary-icon success"><i class="ri-phone-line"></i></div>
                            <div>
                                <div class="academico-summary-val">{{ $conCelular }}</div>
                                <div class="academico-summary-lbl">Con celular</div>
                            </div>
                        </div>
                    </div>

                    <div class="academico-tbl-wrap">
                        <table class="academico-tbl">
                            <thead>
                                <tr>
                                    <th style="width:40px;text-align:center;">#</th>
                                    <th>Estudiante</th>
                                    <th>Carnet</th>
                                    <th>Contacto</th>
                                    <th>Plan Pagos</th>
                                    <th>Estudios</th>
                                    <th style="text-align:center;width:90px;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inscritos as $index => $inscrito)
                                <tr>
                                    <td style="text-align:center;color:#94a3b8;font-size:.78rem;">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="academico-name">
                                            <span class="academico-avatar">
                                                {{ Str::upper(substr($inscrito['nombres'], 0, 1)) }}{{ Str::upper(substr($inscrito['apellido_paterno'], 0, 1)) }}
                                            </span>
                                            <div>
                                                <div class="academico-name-text">
                                                    {{ trim(($inscrito['apellido_paterno'] ?? '') . ' ' . ($inscrito['apellido_materno'] ?? '') . ' ' . ($inscrito['nombres'] ?? '')) ?: '—' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="academico-ci"><i class="ri-fingerprint-line"></i>{{ $inscrito['estudiante_ci'] }}</span></td>
                                    <td>
                                        <div class="academico-contact">
                                            @if($inscrito['celular'] && $inscrito['celular'] !== '—')
                                            <span class="academico-contact-item"><i class="ri-phone-line"></i>{{ $inscrito['celular'] }}</span>
                                            @endif
                                            @if($inscrito['correo'] && $inscrito['correo'] !== '—')
                                            <span class="academico-contact-item"><i class="ri-mail-line"></i>{{ $inscrito['correo'] }}</span>
                                            @endif
                                            @if((!$inscrito['celular'] || $inscrito['celular'] === '—') && (!$inscrito['correo'] || $inscrito['correo'] === '—'))
                                            <span style="color:#94a3b8;font-size:.78rem;">—</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($inscrito['plan_pago'] !== '—')
                                            <span class="academico-plan"><i class="ri-funds-box-line"></i>{{ $inscrito['plan_pago'] }}</span>
                                        @else
                                            <span style="color:#94a3b8;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $estudios = $inscrito['estudios'] ?? [];
                                        @endphp
                                        @if(count($estudios) > 0)
                                            @php
                                                $principal = collect($estudios)->firstWhere('principal', true) ?? $estudios[0];
                                            @endphp
                                            <div style="display:flex;flex-direction:column;gap:2px;font-size:.78rem;line-height:1.4;">
                                                @if($principal['grado'])
                                                <span style="display:inline-flex;align-items:center;gap:4px;color:#9a4904;font-weight:600;">
                                                    <i class="ri-graduation-cap-line" style="font-size:.7rem;"></i>{{ $principal['grado'] }}
                                                </span>
                                                @endif
                                                @if($principal['universidad'])
                                                <span style="display:inline-flex;align-items:center;gap:4px;color:#64748b;">
                                                    <i class="ri-building-line" style="font-size:.7rem;"></i>{{ $principal['universidad'] }}
                                                </span>
                                                @endif
                                                @if($principal['profesion'])
                                                <span style="display:inline-flex;align-items:center;gap:4px;color:#475569;font-weight:500;">
                                                    <i class="ri-briefcase-line" style="font-size:.7rem;"></i>{{ $principal['profesion'] }}
                                                </span>
                                                @endif
                                                @if(count($estudios) > 1)
                                                <span style="margin-top:2px;background:#fc7b04;color:#fff;border-radius:10px;padding:0 7px;font-size:.65rem;font-weight:700;display:inline-flex;align-items:center;width:fit-content;height:18px;">
                                                    +{{ count($estudios) - 1 }} más
                                                </span>
                                                @endif
                                            </div>
                                        @else
                                            <span style="color:#94a3b8;font-size:.78rem;">Sin estudios</span>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        <a href="{{ route('admin.estudiantes.verDetalle', $inscrito['estudiante_id']) }}"
                                           class="academico-btn-ver" title="Ver detalle del estudiante">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="academico-empty">
                        <i class="ri-user-unfollow-line"></i>
                        <p>No hay estudiantes inscritos en este módulo</p>
                    </div>
                    @endif
                </div>

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
                        <button class="tool-btn" onclick="ActividadesEditor.abrirModal('resource')" title="Recurso"><i class="ri-file-line"></i> Recurso</button>
                        <button class="tool-btn" onclick="ActividadesEditor.abrirModal('url')" title="URL"><i class="ri-link"></i> URL</button>
                        <button class="tool-btn" onclick="ActividadesEditor.abrirModal('page')" title="Página"><i class="ri-file-text-line"></i> Página</button>
                    </div>

                    <div id="actividadesEditorData" data-modulo-id="{{ $modulo->id }}" data-course-id="{{ $modulo->moodle_course_id ?? 0 }}" data-api-base="/admin/posgrads/modulos" style="display:none;"></div>

                    @if(!$modulo->moodle_course_id)
                    <div class="actividad-placeholder">
                        <i class="ri-forbid-line"></i>
                        <h5>Sin curso Moodle</h5>
                        <p>Este módulo no tiene un curso asignado en Moodle.</p>
                    </div>
                    @else
                    <div class="act-resumen" id="actResumen" style="display:none;">
                        <div class="act-stat">
                            <div class="act-stat-icon" style="color:#fc7b04;">&#9633;</div>
                            <div class="act-stat-val" id="cntSecciones">0</div>
                            <div class="act-stat-lbl">Secciones</div>
                        </div>
                        <div class="act-stat">
                            <div class="act-stat-icon" style="color:#fc7b04;"><i class="ri-task-line"></i></div>
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
                            <button class="centr-btn-export" id="btnExportarCentralizador" style="display:none;" title="Exportar tabla a CSV">
                                <i class="ri-download-line"></i> Exportar CSV
                            </button>
                            <a href="/admin/posgrads/modulos/{{ $modulo->id }}/reporte/notas-detallado" id="btnReporteDetallado" target="_blank" style="display:none;align-items:center;gap:.4rem;padding:.35rem .85rem;border-radius:7px;font-size:.8rem;font-weight:600;background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.4);color:#4338ca;text-decoration:none;cursor:pointer;" title="Generar PDF con notas por actividad">
                                <i class="ri-file-chart-line"></i> Reporte Detallado
                            </a>
                            <a href="/admin/posgrads/modulos/{{ $modulo->id }}/reporte/notas-finales" id="btnReporteFinales" target="_blank" style="display:none;align-items:center;gap:.4rem;padding:.35rem .85rem;border-radius:7px;font-size:.8rem;font-weight:600;background:rgba(22,163,74,.1);border:1px solid rgba(22,163,74,.4);color:#15803d;text-decoration:none;cursor:pointer;" title="Generar PDF con nota final y en literal">
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
                                <button id="btnSincronizarMoodle" class="centr-btn-sync" style="display:none;" title="Actualiza en Moodle la nota máxima de cada actividad y recalcula las notas de los estudiantes">
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
                            <span>Cada celda muestra <strong>(nota Moodle ÷ nota máx) × ponderación%</strong>. Pasa el cursor sobre una celda para ver la nota original de Moodle. Celdas con <strong>—</strong> = sin calificación registrada en esa actividad.</span>
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
    </div>{{-- /container-fluid --}}

    {{-- ══════════════════════════════════════════
         MODALES
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
                                    <div style="font-size:.78rem;color:#64748b;line-height:1.4;">La nota mostrada será <code style="background:#f1f5f9;padding:1px 5px;border-radius:4px;">(nota Moodle ÷ nota máx) × nuevo %</code></div>
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
                                    <div style="font-size:.78rem;color:#64748b;line-height:1.4;">La nota ya fue ingresada con la ponderación aplicada directamente en Moodle.</div>
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

    {{-- Modal: Confirmación sincronizar con Moodle --}}
    <div class="modal fade" id="modalSincronizarMoodle" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:540px;">
            <div class="modal-content" style="border-radius:14px;border:none;box-shadow:0 8px 40px rgba(0,0,0,.16);">
                <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:1.1rem 1.4rem;background:#1e293b;border-radius:14px 14px 0 0;">
                    <h5 class="modal-title" style="font-size:.93rem;font-weight:700;color:#f1f5f9;display:flex;align-items:center;gap:.5rem;">
                        <i class="ri-refresh-line" style="color:#fc7b04;"></i> Sincronizar ponderaciones con Moodle
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:1.3rem 1.5rem;">
                    <p style="font-size:.85rem;color:#475569;margin:0 0 .9rem;">
                        Se actualizarán las ponderaciones de las siguientes actividades en Moodle:
                    </p>
                    <div id="smResumenItems" style="display:flex;flex-direction:column;gap:.45rem;margin-bottom:1rem;"></div>
                    <div style="background:#fef9ec;border:1px solid #fde68a;border-radius:8px;padding:.7rem 1rem;font-size:.8rem;color:#92400e;display:flex;align-items:center;gap:.5rem;">
                        <i class="ri-user-line" style="font-size:1rem;flex-shrink:0;"></i>
                        <span>Afectará las calificaciones de <strong id="smTotalEstudiantes">0</strong> estudiante(s).</span>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:.9rem 1.4rem;gap:.5rem;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="font-size:.82rem;">Cancelar</button>
                    <button type="button" id="btnConfirmarSincMoodle" class="btn btn-sm" style="font-size:.82rem;font-weight:600;background:#fc7b04;border-color:#fc7b04;color:#fff;">
                        <i class="ri-refresh-line"></i> Confirmar sincronización
                    </button>
                </div>
            </div>
        </div>
    </div>

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

    {{-- Modal: Matricular Moodle --}}
    <div class="modal fade" id="modalMatricularMoodle" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content" style="border-radius:14px;border:none;box-shadow:0 8px 40px rgba(0,0,0,.16);">
                <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:1rem 1.4rem;">
                    <h5 class="modal-title" style="font-size:1rem;font-weight:700;"><i class="ri-graduation-cap-line" style="color:#fc7b04;margin-right:.4rem;"></i>Matricular en Moodle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:1.3rem 1.5rem;">
                    <div id="matricularMoodleLoading" style="text-align:center;padding:1rem 0;">
                        <i class="ri-loader-4-line" style="font-size:1.5rem;color:#94a3b8;animation:spin 1s linear infinite;display:block;margin-bottom:.5rem;"></i>
                        <span style="color:#64748b;font-size:.85rem;">Cargando estudiantes...</span>
                    </div>
                    <div id="matricularMoodleContenido" style="display:none;"></div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:.9rem 1.4rem;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="font-size:.82rem;">Cancelar</button>
                    <button type="button" id="btnConfirmarMatricularMoodle" class="btn btn-primary btn-sm" style="display:none;font-size:.82rem;font-weight:600;">Matricular Todos</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Matricular individual en Moodle --}}
    <div class="modal fade" id="modalMatricularUnoMoodle" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
            <div class="modal-content" style="border-radius:14px;border:none;box-shadow:0 8px 40px rgba(0,0,0,.16);">
                <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:1rem 1.4rem;">
                    <h5 class="modal-title" style="font-size:1rem;font-weight:700;"><i class="ri-graduation-cap-line" style="color:#fc7b04;margin-right:.4rem;"></i>Matricular Estudiante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:1.3rem 1.5rem;">
                    <p style="font-size:.88rem;color:#475569;margin:0 0 .3rem;">
                        ¿Estás seguro de matricular a <strong id="matricularUnoNombre" style="color:#1e293b;"></strong>
                    </p>
                    <p style="font-size:.82rem;color:#64748b;margin:0;">
                        en el curso Moodle del módulo <strong id="matricularUnoModulo" style="color:#1e293b;"></strong>?
                    </p>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:.9rem 1.4rem;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="font-size:.82rem;">Cancelar</button>
                    <button type="button" id="btnConfirmarMatricularUno" class="btn btn-primary btn-sm" style="font-size:.82rem;font-weight:600;">Matricular</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Confirmar activar/suspender acceso Moodle --}}
    <div class="modal fade" id="modalConfirmarAcceso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 12px 48px rgba(0,0,0,.18);overflow:hidden;">
                <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:1.1rem 1.5rem .9rem;">
                    <h5 class="modal-title" style="font-size:.95rem;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:.5rem;" id="modalConfirmarAccesoTitle">
                        <i class="ri-shield-keyhole-line" style="color:#fc7b04;"></i> Confirmar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size:.8rem;"></button>
                </div>
                <div class="modal-body" style="padding:1.3rem 1.5rem;">
                    <div style="display:flex;gap:.85rem;align-items:flex-start;margin-bottom:1rem;">
                        <div id="modalAccesoIcono" style="width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;background:rgba(239,68,68,.1);color:#dc2626;">
                            <i class="ri-pause-circle-line"></i>
                        </div>
                        <div style="flex:1;">
                            <p style="font-size:.88rem;color:#475569;margin:0 0 .15rem;font-weight:600;" id="modalConfirmarAccesoMsg"></p>
                            <p style="font-size:.78rem;color:#94a3b8;margin:0;">Esta acción cambiará el acceso del estudiante a la plataforma Moodle.</p>
                        </div>
                    </div>
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:.85rem 1rem;">
                        <div style="display:flex;align-items:center;gap:.6rem;font-size:.82rem;color:#64748b;margin-bottom:6px;">
                            <span style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#fc7b04,#c96004);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-user-line" style="color:#fff;font-size:.75rem;"></i>
                            </span>
                            <span><strong id="modalEstudianteNombre" style="color:#1e293b;"></strong></span>
                        </div>
                        <div style="display:flex;align-items:center;gap:.6rem;font-size:.82rem;color:#64748b;">
                            <span style="width:28px;height:28px;border-radius:50%;background:rgba(100,116,139,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-layout-grid-line" style="color:#64748b;font-size:.75rem;"></i>
                            </span>
                            <span>Módulo: <strong id="modalModuloNombre" style="color:#1e293b;"></strong></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:.9rem 1.5rem;display:flex;gap:.5rem;">
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal"
                        style="flex:1;padding:.5rem;border-radius:8px;font-size:.82rem;font-weight:600;background:#f1f5f9;color:#64748b;border:none;cursor:pointer;transition:all .15s;"
                        onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                        Cancelar
                    </button>
                    <button type="button" id="btnConfirmarAcceso" class="btn btn-sm"
                        style="flex:1;padding:.5rem;border-radius:8px;font-size:.82rem;font-weight:700;border:none;cursor:pointer;transition:all .18s;display:inline-flex;align-items:center;justify-content:center;gap:.4rem;background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;"
                        onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">
                        <i class="ri-check-line"></i> Confirmar
                    </button>
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
    <div class="disc-modal" style="max-width:680px;border-radius:16px;border:none;box-shadow:0 12px 48px rgba(0,0,0,.2);overflow:hidden;">
        <div class="disc-modal-hdr" style="background:#1e293b;padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;">
            <span class="disc-modal-title" style="font-size:.95rem;font-weight:700;color:#f1f5f9;display:flex;align-items:center;gap:.5rem;"><i class="ri-pencil-line" style="color:#fc7b04;"></i> <span id="modalTitleText">Nueva Actividad</span></span>
            <button class="disc-modal-close" onclick="ActividadesEditor.cerrarModal()" style="background:none;border:none;color:#94a3b8;font-size:1.3rem;cursor:pointer;padding:0;line-height:1;">&times;</button>
        </div>
        <div class="disc-modal-body" id="modalBody" style="padding:1.2rem 1.25rem;max-height:70vh;overflow-y:auto;">
            {{-- Dynamic content --}}
        </div>
        <div class="disc-modal-footer" style="border-top:1px solid #e9ecef;padding:.85rem 1.25rem;display:flex;gap:.5rem;justify-content:flex-end;background:#fafbfc;">
            <button class="btn-cancel-disc" onclick="ActividadesEditor.cerrarModal()" style="padding:.45rem 1rem;border-radius:8px;font-size:.82rem;font-weight:600;background:#f1f5f9;color:#64748b;border:none;cursor:pointer;transition:all .15s;">Cancelar</button>
            <button class="btn-guardar-disc btn-save" style="padding:.45rem 1rem;border-radius:8px;font-size:.82rem;font-weight:700;background:linear-gradient(135deg,#fc7b04,#c96004);color:#fff;border:none;cursor:pointer;transition:all .2s;display:inline-flex;align-items:center;gap:.4rem;"><i class="ri-check-line"></i> Guardar en Moodle</button>
        </div>
    </div>
</div>

{{-- Modal: Calificar Tarea / Foro --}}
<div class="disc-modal-overlay" id="modalCalificarTarea">
    <div class="disc-modal" style="max-width:760px;border-radius:16px;overflow:hidden;border:none;box-shadow:0 20px 60px rgba(0,0,0,.25);">
        {{-- Header --}}
        <div class="disc-modal-hdr" style="background:linear-gradient(135deg,#1e293b,#334155);padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;">
            <span style="display:flex;align-items:center;gap:.6rem;">
                <span id="calificarTipoIcono" style="width:34px;height:34px;border-radius:9px;background:rgba(252,123,4,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="ri-bar-chart-line" style="color:#fc7b04;font-size:1.05rem;"></i>
                </span>
                <span style="line-height:1.25;">
                    <span style="display:block;font-size:.72rem;font-weight:600;color:#94a3b8;" id="calificarTipoLabel">Calificar</span>
                    <span style="font-size:.93rem;font-weight:700;color:#f1f5f9;" id="calificarTareaNombre"></span>
                </span>
            </span>
            <button onclick="ActividadesEditor.cerrarModalCalificar()" style="background:none;border:none;color:#94a3b8;font-size:1.4rem;cursor:pointer;line-height:1;padding:0;transition:color .15s;" onmouseover="this.style.color='#f1f5f9'" onmouseout="this.style.color='#94a3b8'">&times;</button>
        </div>

        {{-- Body --}}
        <div class="disc-modal-body" style="padding:1rem 1.25rem;max-height:62vh;overflow-y:auto;">
            <div id="calificarLoading" class="text-center py-4">
                <i class="ri-loader-4-line" style="font-size:1.8rem;color:#fc7b04;animation:spin 1s linear infinite;display:block;margin-bottom:.5rem;"></i>
                <p style="font-size:.85rem;color:#64748b;margin:0;">Cargando datos...</p>
            </div>
            <div id="calificarError" class="d-none" style="display:flex;align-items:center;gap:.75rem;padding:.9rem 1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;margin-bottom:.75rem;">
                <i class="ri-error-warning-line" style="color:#dc2626;font-size:1.2rem;flex-shrink:0;"></i>
                <div style="flex:1;">
                    <span id="calificarErrorMsg" style="font-size:.85rem;color:#991b1b;"></span>
                </div>
                <button onclick="ActividadesEditor.reintentarCalificar()" style="padding:.3rem .75rem;border-radius:7px;font-size:.78rem;font-weight:600;background:rgba(220,38,38,.12);border:1px solid rgba(220,38,38,.3);color:#dc2626;cursor:pointer;white-space:nowrap;">Reintentar</button>
            </div>
            <div id="calificarContent" class="d-none">
                <div id="calificarTableBody"></div>
            </div>
        </div>

        <div class="disc-modal-footer" style="border-top:1.5px solid #e2e8f0;padding:.8rem 1.25rem;background:#fafbfc;">
            <button onclick="ActividadesEditor.cerrarModalCalificar()" style="padding:.42rem 1.1rem;border-radius:8px;font-size:.83rem;font-weight:600;background:#f1f5f9;color:#64748b;border:none;cursor:pointer;transition:all .15s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Cerrar</button>
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
    <div class="disc-modal" style="max-width:760px;">
        <div class="disc-modal-hdr">
            <span class="disc-modal-title"><i class="ri-bar-chart-grouped-line"></i> Resultados: <span id="quizResultadosNombre"></span></span>
            <button class="disc-modal-close" onclick="ActividadesEditor.cerrarModalQuiz()">&times;</button>
        </div>
        <div class="disc-modal-body">
            <div id="quizLoading" style="text-align:center;padding:2.5rem;color:#64748b;"><i class="ri-loader-4-line" style="font-size:1.5rem;animation:spin 1s linear infinite;"></i><p style="margin-top:.5rem;">Cargando resultados...</p></div>
            <div id="quizError" style="display:none;padding:0.75rem 1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;color:#dc2626;font-size:0.85rem;"><span id="quizErrorText"></span></div>
            <div id="quizContent" style="display:none;">
                <div id="quizCardsContainer"></div>
            </div>
            <div id="quizAttemptDetail" style="display:none;margin-top:1rem;">
                <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.75rem;padding:0.5rem 0;">
                    <button onclick="ActividadesEditor.cerrarDetalleIntento()" style="display:inline-flex;align-items:center;gap:0.3rem;padding:0.3rem 0.7rem;font-size:0.78rem;font-weight:600;border-radius:6px;background:#f1f5f9;color:#475569;border:none;cursor:pointer;"><i class="ri-arrow-left-line"></i> Volver</button>
                    <span style="font-size:0.85rem;font-weight:700;color:#1e293b;"><span id="quizDetailNombre"></span></span>
                </div>
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
    <div class="disc-modal" style="max-width:680px;border-radius:16px;overflow:hidden;border:none;box-shadow:0 20px 60px rgba(0,0,0,.25);">
        {{-- Header --}}
        <div class="disc-modal-hdr" style="background:linear-gradient(135deg,#1e293b,#334155);padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;">
            <span style="display:flex;align-items:center;gap:.6rem;">
                <span style="width:34px;height:34px;border-radius:9px;background:rgba(217,119,6,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="ri-question-line" style="color:#f59e0b;font-size:1.05rem;"></i>
                </span>
                <span style="font-size:.93rem;font-weight:700;color:#f1f5f9;line-height:1.25;">
                    Banco de preguntas
                    <span style="display:block;font-size:.72rem;font-weight:400;color:#94a3b8;" id="preguntasQuizNombre"></span>
                </span>
            </span>
            <button onclick="ActividadesEditor.cerrarModalPreguntas()" style="background:none;border:none;color:#94a3b8;font-size:1.4rem;cursor:pointer;line-height:1;padding:0;transition:color .15s;" onmouseover="this.style.color='#f1f5f9'" onmouseout="this.style.color='#94a3b8'">&times;</button>
        </div>

        {{-- Barra "Agregar pregunta" --}}
        <div style="padding:.75rem 1.25rem;background:#f8fafc;border-bottom:1.5px solid #e2e8f0;display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
            <span style="font-size:.73rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-right:.1rem;white-space:nowrap;">
                <i class="ri-add-circle-line" style="color:#fc7b04;"></i> Nueva pregunta
            </span>
            <button onclick="ActividadesEditor.mostrarFormMC()"
                style="display:inline-flex;align-items:center;gap:.35rem;padding:.33rem .85rem;border-radius:20px;font-size:.79rem;font-weight:600;border:1.5px solid #6366f1;background:rgba(99,102,241,.07);color:#4f46e5;cursor:pointer;transition:all .18s;"
                onmouseover="this.style.background='#6366f1';this.style.color='#fff'" onmouseout="this.style.background='rgba(99,102,241,.07)';this.style.color='#4f46e5'">
                <i class="ri-list-check"></i> Opción múltiple
            </button>
            <button onclick="ActividadesEditor.mostrarFormTF()"
                style="display:inline-flex;align-items:center;gap:.35rem;padding:.33rem .85rem;border-radius:20px;font-size:.79rem;font-weight:600;border:1.5px solid #16a34a;background:rgba(22,163,74,.07);color:#15803d;cursor:pointer;transition:all .18s;"
                onmouseover="this.style.background='#16a34a';this.style.color='#fff'" onmouseout="this.style.background='rgba(22,163,74,.07)';this.style.color='#15803d'">
                <i class="ri-toggle-line"></i> Verdadero / Falso
            </button>
            <button onclick="ActividadesEditor.mostrarFormMatch()"
                style="display:inline-flex;align-items:center;gap:.35rem;padding:.33rem .85rem;border-radius:20px;font-size:.79rem;font-weight:600;border:1.5px solid #0284c7;background:rgba(2,132,199,.07);color:#0369a1;cursor:pointer;transition:all .18s;"
                onmouseover="this.style.background='#0284c7';this.style.color='#fff'" onmouseout="this.style.background='rgba(2,132,199,.07)';this.style.color='#0369a1'">
                <i class="ri-links-line"></i> Coincidencia
            </button>
        </div>

        {{-- Cuerpo --}}
        <div class="disc-modal-body" style="padding:1rem 1.25rem;max-height:52vh;overflow-y:auto;">
            <div id="preguntasLoading" class="text-center py-4">
                <i class="ri-loader-4-line" style="font-size:1.8rem;color:#d97706;animation:spin 1s linear infinite;display:block;margin-bottom:.5rem;"></i>
                <p style="font-size:.85rem;color:#64748b;margin:0;">Cargando preguntas...</p>
            </div>
            <div id="preguntasError" class="alert alert-danger d-none" style="border-radius:8px;font-size:.85rem;">
                <i class="ri-error-warning-line"></i> <span id="preguntasErrorTxt"></span>
            </div>
            <div id="preguntasContent" class="d-none">
                <div id="preguntasList"></div>
            </div>
        </div>

        <div class="disc-modal-footer" style="border-top:1.5px solid #e2e8f0;padding:.8rem 1.25rem;background:#fafbfc;">
            <button onclick="ActividadesEditor.cerrarModalPreguntas()"
                style="padding:.42rem 1.1rem;border-radius:8px;font-size:.83rem;font-weight:600;background:#f1f5f9;color:#64748b;border:none;cursor:pointer;transition:all .15s;"
                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                Cerrar
            </button>
        </div>
    </div>
</div>

{{-- Modal: Crear opción múltiple --}}
<div class="disc-modal-overlay" id="modalMC">
    <div class="disc-modal" style="max-width:580px;border-radius:16px;overflow:hidden;border:none;box-shadow:0 20px 60px rgba(0,0,0,.25);">
        <div class="disc-modal-hdr" style="background:linear-gradient(135deg,#4338ca,#6366f1);padding:.9rem 1.25rem;display:flex;align-items:center;justify-content:space-between;">
            <span style="display:flex;align-items:center;gap:.5rem;color:#fff;font-size:.92rem;font-weight:700;">
                <i class="ri-list-check" style="font-size:1.1rem;opacity:.85;"></i> Nueva pregunta &mdash; Opción múltiple
            </span>
            <button onclick="ActividadesEditor.cerrarModalMC()" style="background:none;border:none;color:rgba(255,255,255,.65);font-size:1.4rem;cursor:pointer;line-height:1;padding:0;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">&times;</button>
        </div>
        <div class="disc-modal-body" style="padding:1.1rem 1.25rem;max-height:62vh;overflow-y:auto;">

            {{-- Sección General --}}
            <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.85rem 1rem;margin-bottom:.8rem;">
                <div style="font-size:.73rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.7rem;display:flex;align-items:center;gap:.35rem;">
                    <i class="ri-pencil-line" style="color:#6366f1;"></i> General
                </div>
                <div style="margin-bottom:.65rem;">
                    <label style="font-size:.8rem;font-weight:600;color:#374151;display:block;margin-bottom:.22rem;">Nombre interno <span style="color:#dc2626;">*</span></label>
                    <input class="form-control" id="mcName" placeholder="Ej: Pregunta 1" style="font-size:.85rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .65rem;">
                </div>
                <div style="margin-bottom:.65rem;">
                    <label style="font-size:.8rem;font-weight:600;color:#374151;display:block;margin-bottom:.22rem;">Texto de la pregunta <span style="color:#dc2626;">*</span></label>
                    <textarea class="form-control" id="mcQuestionText" rows="3" placeholder="¿Cuál es...?" style="font-size:.85rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .65rem;resize:vertical;"></textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem;">
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:#374151;display:block;margin-bottom:.22rem;">Puntaje</label>
                        <div style="display:flex;align-items:center;gap:.4rem;">
                            <input class="form-control" id="mcDefaultMark" type="number" value="1" step="0.5" min="0" style="font-size:.85rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .65rem;width:80px;">
                            <span style="font-size:.78rem;color:#94a3b8;">pts</span>
                        </div>
                    </div>
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:#374151;display:block;margin-bottom:.22rem;">Tipo de respuesta</label>
                        <select class="form-control" id="mcSingle" style="font-size:.85rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.38rem .5rem;">
                            <option value="true">Única respuesta</option>
                            <option value="false">Múltiple respuesta</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Sección Opciones --}}
            <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.85rem 1rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.55rem;">
                    <div style="font-size:.73rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.06em;display:flex;align-items:center;gap:.35rem;">
                        <i class="ri-checkbox-multiple-line" style="color:#6366f1;"></i> Opciones de respuesta
                    </div>
                    <span style="font-size:.7rem;color:#4f46e5;background:rgba(99,102,241,.1);padding:2px 8px;border-radius:10px;font-weight:600;">fracción 1 = correcta</span>
                </div>
                {{-- Cabecera de columnas --}}
                <div style="display:grid;grid-template-columns:1fr 76px 32px;gap:.4rem;margin-bottom:.3rem;padding:0 .1rem;">
                    <span style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;">Texto de la opción</span>
                    <span style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;text-align:center;">Fracción</span>
                    <span></span>
                </div>
                <div id="mcOptionsContainer"></div>
                <button onclick="addMcOption()"
                    style="margin-top:.6rem;display:inline-flex;align-items:center;gap:.35rem;padding:.32rem .85rem;border-radius:20px;font-size:.78rem;font-weight:600;border:1.5px solid #6366f1;background:rgba(99,102,241,.07);color:#4f46e5;cursor:pointer;transition:all .15s;"
                    onmouseover="this.style.background='rgba(99,102,241,.15)'" onmouseout="this.style.background='rgba(99,102,241,.07)'">
                    <i class="ri-add-line"></i> Agregar opción
                </button>
            </div>
        </div>
        <div class="disc-modal-footer" style="border-top:1.5px solid #e2e8f0;padding:.85rem 1.25rem;display:flex;gap:.5rem;justify-content:flex-end;background:#fafbfc;">
            <button onclick="ActividadesEditor.cerrarModalMC()" style="padding:.42rem 1rem;border-radius:8px;font-size:.82rem;font-weight:600;background:#f1f5f9;color:#64748b;border:none;cursor:pointer;transition:all .15s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Cancelar</button>
            <button onclick="ActividadesEditor.guardarMC()" style="padding:.42rem 1.1rem;border-radius:8px;font-size:.82rem;font-weight:700;background:linear-gradient(135deg,#4338ca,#6366f1);color:#fff;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;transition:opacity .15s;" onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">
                <i class="ri-check-line"></i> Crear pregunta
            </button>
        </div>
    </div>
</div>

{{-- Modal: Crear V/F --}}
<div class="disc-modal-overlay" id="modalTF">
    <div class="disc-modal" style="max-width:500px;border-radius:16px;overflow:hidden;border:none;box-shadow:0 20px 60px rgba(0,0,0,.25);">
        <div class="disc-modal-hdr" style="background:linear-gradient(135deg,#15803d,#16a34a);padding:.9rem 1.25rem;display:flex;align-items:center;justify-content:space-between;">
            <span style="display:flex;align-items:center;gap:.5rem;color:#fff;font-size:.92rem;font-weight:700;">
                <i class="ri-toggle-line" style="font-size:1.1rem;opacity:.85;"></i> Nueva pregunta &mdash; Verdadero / Falso
            </span>
            <button onclick="ActividadesEditor.cerrarModalTF()" style="background:none;border:none;color:rgba(255,255,255,.65);font-size:1.4rem;cursor:pointer;line-height:1;padding:0;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">&times;</button>
        </div>
        <div class="disc-modal-body" style="padding:1.1rem 1.25rem;">
            <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.85rem 1rem;margin-bottom:.8rem;">
                <div style="font-size:.73rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.7rem;display:flex;align-items:center;gap:.35rem;">
                    <i class="ri-pencil-line" style="color:#16a34a;"></i> General
                </div>
                <div style="margin-bottom:.65rem;">
                    <label style="font-size:.8rem;font-weight:600;color:#374151;display:block;margin-bottom:.22rem;">Nombre interno <span style="color:#dc2626;">*</span></label>
                    <input class="form-control" id="tfName" placeholder="Ej: Pregunta 2" style="font-size:.85rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .65rem;">
                </div>
                <div>
                    <label style="font-size:.8rem;font-weight:600;color:#374151;display:block;margin-bottom:.22rem;">Texto de la pregunta <span style="color:#dc2626;">*</span></label>
                    <textarea class="form-control" id="tfQuestionText" rows="3" placeholder="La capital de Bolivia es Sucre." style="font-size:.85rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .65rem;resize:vertical;"></textarea>
                </div>
            </div>
            <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.85rem 1rem;">
                <div style="font-size:.73rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.7rem;display:flex;align-items:center;gap:.35rem;">
                    <i class="ri-bar-chart-line" style="color:#16a34a;"></i> Calificación y respuesta
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem;">
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:#374151;display:block;margin-bottom:.22rem;">Puntaje</label>
                        <div style="display:flex;align-items:center;gap:.4rem;">
                            <input class="form-control" id="tfDefaultMark" type="number" value="1" step="0.5" min="0" style="font-size:.85rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .65rem;width:80px;">
                            <span style="font-size:.78rem;color:#94a3b8;">pts</span>
                        </div>
                    </div>
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:#374151;display:block;margin-bottom:.22rem;">Respuesta correcta</label>
                        <select class="form-control" id="tfCorrect" style="font-size:.85rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.38rem .5rem;">
                            <option value="true">✓ Verdadero</option>
                            <option value="false">✗ Falso</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="disc-modal-footer" style="border-top:1.5px solid #e2e8f0;padding:.85rem 1.25rem;display:flex;gap:.5rem;justify-content:flex-end;background:#fafbfc;">
            <button onclick="ActividadesEditor.cerrarModalTF()" style="padding:.42rem 1rem;border-radius:8px;font-size:.82rem;font-weight:600;background:#f1f5f9;color:#64748b;border:none;cursor:pointer;transition:all .15s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Cancelar</button>
            <button onclick="ActividadesEditor.guardarTF()" style="padding:.42rem 1.1rem;border-radius:8px;font-size:.82rem;font-weight:700;background:linear-gradient(135deg,#15803d,#16a34a);color:#fff;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;transition:opacity .15s;" onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">
                <i class="ri-check-line"></i> Crear pregunta
            </button>
        </div>
    </div>
</div>

{{-- Modal: Crear Coincidencia --}}
<div class="disc-modal-overlay" id="modalMatch">
    <div class="disc-modal" style="max-width:580px;border-radius:16px;overflow:hidden;border:none;box-shadow:0 20px 60px rgba(0,0,0,.25);">
        <div class="disc-modal-hdr" style="background:linear-gradient(135deg,#0369a1,#0284c7);padding:.9rem 1.25rem;display:flex;align-items:center;justify-content:space-between;">
            <span style="display:flex;align-items:center;gap:.5rem;color:#fff;font-size:.92rem;font-weight:700;">
                <i class="ri-links-line" style="font-size:1.1rem;opacity:.85;"></i> Nueva pregunta &mdash; Coincidencia
            </span>
            <button onclick="ActividadesEditor.cerrarModalMatch()" style="background:none;border:none;color:rgba(255,255,255,.65);font-size:1.4rem;cursor:pointer;line-height:1;padding:0;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">&times;</button>
        </div>
        <div class="disc-modal-body" style="padding:1.1rem 1.25rem;max-height:62vh;overflow-y:auto;">
            <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.85rem 1rem;margin-bottom:.8rem;">
                <div style="font-size:.73rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.7rem;display:flex;align-items:center;gap:.35rem;">
                    <i class="ri-pencil-line" style="color:#0284c7;"></i> General
                </div>
                <div style="margin-bottom:.65rem;">
                    <label style="font-size:.8rem;font-weight:600;color:#374151;display:block;margin-bottom:.22rem;">Nombre interno <span style="color:#dc2626;">*</span></label>
                    <input class="form-control" id="matchName" placeholder="Ej: Pregunta 3" style="font-size:.85rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .65rem;">
                </div>
                <div style="margin-bottom:.65rem;">
                    <label style="font-size:.8rem;font-weight:600;color:#374151;display:block;margin-bottom:.22rem;">Texto de la pregunta <span style="color:#dc2626;">*</span></label>
                    <textarea class="form-control" id="matchQuestionText" rows="2" placeholder="Empareja cada capital con su país." style="font-size:.85rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .65rem;resize:vertical;"></textarea>
                </div>
                <div>
                    <label style="font-size:.8rem;font-weight:600;color:#374151;display:block;margin-bottom:.22rem;">Puntaje</label>
                    <div style="display:flex;align-items:center;gap:.4rem;">
                        <input class="form-control" id="matchDefaultMark" type="number" value="1" step="0.5" min="0" style="font-size:.85rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .65rem;width:80px;">
                        <span style="font-size:.78rem;color:#94a3b8;">pts</span>
                    </div>
                </div>
            </div>

            {{-- Pares --}}
            <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.85rem 1rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.55rem;">
                    <div style="font-size:.73rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.06em;display:flex;align-items:center;gap:.35rem;">
                        <i class="ri-links-line" style="color:#0284c7;"></i> Pares pregunta → respuesta
                    </div>
                </div>
                {{-- Cabecera de columnas --}}
                <div style="display:grid;grid-template-columns:1fr 1fr 32px;gap:.4rem;margin-bottom:.3rem;padding:0 .1rem;">
                    <span style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;display:flex;align-items:center;gap:.25rem;"><i class="ri-question-mark" style="color:#0284c7;font-size:.75rem;"></i> Pregunta</span>
                    <span style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;display:flex;align-items:center;gap:.25rem;"><i class="ri-check-line" style="color:#16a34a;font-size:.75rem;"></i> Respuesta</span>
                    <span></span>
                </div>
                <div id="matchPairsContainer"></div>
                <button onclick="addMatchPair()"
                    style="margin-top:.6rem;display:inline-flex;align-items:center;gap:.35rem;padding:.32rem .85rem;border-radius:20px;font-size:.78rem;font-weight:600;border:1.5px solid #0284c7;background:rgba(2,132,199,.07);color:#0369a1;cursor:pointer;transition:all .15s;"
                    onmouseover="this.style.background='rgba(2,132,199,.15)'" onmouseout="this.style.background='rgba(2,132,199,.07)'">
                    <i class="ri-add-line"></i> Agregar par
                </button>
            </div>
        </div>
        <div class="disc-modal-footer" style="border-top:1.5px solid #e2e8f0;padding:.85rem 1.25rem;display:flex;gap:.5rem;justify-content:flex-end;background:#fafbfc;">
            <button onclick="ActividadesEditor.cerrarModalMatch()" style="padding:.42rem 1rem;border-radius:8px;font-size:.82rem;font-weight:600;background:#f1f5f9;color:#64748b;border:none;cursor:pointer;transition:all .15s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Cancelar</button>
            <button onclick="ActividadesEditor.guardarMatch()" style="padding:.42rem 1.1rem;border-radius:8px;font-size:.82rem;font-weight:700;background:linear-gradient(135deg,#0369a1,#0284c7);color:#fff;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;transition:opacity .15s;" onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">
                <i class="ri-check-line"></i> Crear pregunta
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    let actividadesCargadas   = false;
    let centralizadorCargado  = false;

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
                if (btnCentr) {
                    cargarCentralizador(btnCentr.getAttribute('data-modulo-id'));
                }
            }
        });
    });
    
    const btnMatricularTodosMoodle = document.getElementById('btnMatricularTodosMoodle');
    if (btnMatricularTodosMoodle) {
        btnMatricularTodosMoodle.addEventListener('click', function() {
            abrirModalMatricularMoodle(this.getAttribute('data-modulo-id'));
        });
    }

    const btnConfirmarMatricularMoodle = document.getElementById('btnConfirmarMatricularMoodle');
    if (btnConfirmarMatricularMoodle) {
        btnConfirmarMatricularMoodle.addEventListener('click', function() {
            const moduloId = this.getAttribute('data-modulo-id');
            bootstrap.Modal.getInstance(document.getElementById('modalMatricularMoodle'))?.hide();
            matricularTodosMoodle(moduloId, document.getElementById('btnMatricularTodosMoodle'));
        });
    }

    document.querySelectorAll('.btn-moodle-individual').forEach(btn => {
        btn.addEventListener('click', function() {
            const inscripcionId = this.getAttribute('data-inscripcion-id');
            const moduloId     = this.getAttribute('data-modulo-id');
            const nombreEstudiante = this.closest('tr')?.querySelector('.estudiante-nombre')?.textContent?.trim() || 'Estudiante';
            const nombreModulo     = document.querySelector('.modulo-nombre-display')?.textContent?.trim() || 'Módulo';
            const btnOrigen = this;

            document.getElementById('matricularUnoNombre').textContent = nombreEstudiante;
            document.getElementById('matricularUnoModulo').textContent = nombreModulo;

            const modalEl = document.getElementById('modalMatricularUnoMoodle');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();

            document.getElementById('btnConfirmarMatricularUno').onclick = function() {
                modal.hide();
                matricularUnoMoodle(inscripcionId, moduloId, btnOrigen);
            };
        });
    });

    
});

function abrirModalMatricularMoodle(moduloId) {
    const modalEl = document.getElementById('modalMatricularMoodle');
    const loading = document.getElementById('matricularMoodleLoading');
    const contenido = document.getElementById('matricularMoodleContenido');
    const btnConfirmar = document.getElementById('btnConfirmarMatricularMoodle');

    loading.style.display = 'block';
    contenido.style.display = 'none';
    contenido.innerHTML = '';
    btnConfirmar.style.display = 'none';
    btnConfirmar.setAttribute('data-modulo-id', moduloId);

    new bootstrap.Modal(modalEl).show();

    fetch('/admin/posgrads/modulos/' + moduloId + '/moodle/estudiantes')
        .then(r => r.json())
        .then(data => {
            loading.style.display = 'none';
            contenido.style.display = 'block';

            if (!data.success) {
                contenido.innerHTML = '<div style="display:flex;align-items:center;gap:0.75rem;padding:1rem;background:#fff7ed;border-radius:8px;border:1px solid #fed7aa;">'
                    + '<i class="ri-alert-line" style="color:#ea580c;font-size:1.3rem;"></i>'
                    + '<span style="color:#9a3412;font-size:0.9rem;">' + _escHtml(data.message || 'No se pudo cargar la información.') + '</span></div>';
                return;
            }

            const pendientes = (data.estudiantes || []).filter(e => e.tiene_cuenta && !e.en_curso);

            if (pendientes.length === 0) {
                contenido.innerHTML = '<div style="text-align:center;padding:1.5rem 0;">'
                    + '<i class="ri-checkbox-circle-line" style="font-size:2.5rem;color:#16a34a;"></i>'
                    + '<p style="margin:0.75rem 0 0;font-weight:600;color:#1e293b;">Todos los estudiantes con cuenta Moodle<br>ya están matriculados en este curso.</p>'
                    + '</div>';
                return;
            }

            btnConfirmar.style.display = 'inline-flex';
            btnConfirmar.style.alignItems = 'center';

            let html = '<p style="color:#475569;font-size:0.875rem;margin:0 0 1rem;">Se matricularán <strong style="color:#1e293b;">'
                + pendientes.length + '</strong> estudiante(s) en el curso Moodle:</p>'
                + '<div style="display:flex;flex-direction:column;gap:0.4rem;max-height:280px;overflow-y:auto;">';

            pendientes.forEach(e => {
                html += '<div style="display:flex;align-items:center;gap:0.75rem;padding:0.55rem 0.75rem;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">'
                    + '<div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#fc7b04,#e55a00);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.8rem;flex-shrink:0;">'
                    + _escHtml((e.nombre || 'E').charAt(0).toUpperCase()) + '</div>'
                    + '<div style="flex:1;min-width:0;">'
                    + '<div style="font-weight:600;font-size:0.85rem;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + _escHtml(e.nombre) + '</div>'
                    + '<div style="font-size:0.72rem;color:#64748b;">CI: ' + _escHtml(e.carnet) + ' &nbsp;·&nbsp; <i class="ri-user-line"></i> ' + _escHtml(e.username) + '</div>'
                    + '</div></div>';
            });

            html += '</div>';
            contenido.innerHTML = html;
        })
        .catch(() => {
            loading.style.display = 'none';
            contenido.style.display = 'block';
            contenido.innerHTML = '<div style="display:flex;align-items:center;gap:0.75rem;padding:1rem;background:#fef2f2;border-radius:8px;border:1px solid #fecaca;">'
                + '<i class="ri-error-warning-line" style="color:#dc2626;font-size:1.3rem;"></i>'
                + '<span style="color:#991b1b;font-size:0.9rem;">Error al cargar la lista de estudiantes.</span></div>';
        });
}

function _escHtml(str) {
    return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function matricularTodosMoodle(moduloId, btn) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const labelOriginal = btn ? btn.innerHTML : '';

    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="ri-loader-4-line"></i> Procesando...'; }

    fetch('/admin/posgrads/modulos/' + moduloId + '/moodle/matricular-todos', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(data => {
        if (btn) { btn.disabled = false; btn.innerHTML = labelOriginal; }
        if (data.success) {
            mostrarToast('success', data.mensaje || 'Matriculación completada correctamente.');
            setTimeout(() => location.reload(), 1800);
        } else {
            mostrarToast('error', data.message || 'Error al realizar la matriculación.');
        }
    })
    .catch(() => {
        if (btn) { btn.disabled = false; btn.innerHTML = labelOriginal; }
        mostrarToast('error', 'Error de conexión al procesar la solicitud.');
    });
}

function matricularUnoMoodle(inscripcionId, moduloId, btn) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const labelOriginal = btn ? btn.innerHTML : '';
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="ri-loader-4-line"></i> Procesando...'; }

    fetch('/admin/posgrads/modulos/' + moduloId + '/moodle/matricular-uno/' + inscripcionId, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            mostrarToast('success', data.mensaje || 'Estudiante matriculado en Moodle correctamente.');
            setTimeout(() => location.reload(), 1800);
        } else {
            mostrarToast('error', data.message || 'Error al matricular al estudiante.');
            if (btn) { btn.disabled = false; btn.innerHTML = labelOriginal; }
        }
    })
    .catch(() => {
        mostrarToast('error', 'Error de conexión al procesar la solicitud.');
        if (btn) { btn.disabled = false; btn.innerHTML = labelOriginal; }
    });
}

{{-- ACTIVIDADES: ahora manejado por actividades-editor.js --}}



function toggleAccesoMoodle(inscripcionId, moduloId, suspender, btn) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    if (!csrfToken) {
        console.error('Token CSRF no encontrado');
        mostrarToast('error', 'Token de seguridad no encontrado');
        return;
    }
    
    console.log('toggleAccesoMoodle - Inscripcion:', inscripcionId, 'Modulo:', moduloId, 'Suspender:', suspender);
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:0.9rem;height:0.9rem;"></span>';

    fetch('/admin/posgrads/modulos/' + moduloId + '/moodle/suspender-acceso', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': csrfToken 
        },
        body: JSON.stringify({ inscripcion_id: parseInt(inscripcionId), suspender: suspender })
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('HTTP ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            const quedaSuspendido = suspender;

            btn.setAttribute('data-suspender', quedaSuspendido ? '0' : '1');

            if (quedaSuspendido) {
                btn.className = 'btn-activar btn-toggle-acceso';
                btn.style.cssText = 'padding:.35rem .75rem;font-size:.75rem;font-weight:600;background:rgba(252,123,4,.1);color:#c96004;border:none;border-radius:6px;cursor:pointer;transition:all .2s;';
                btn.onmouseover = function(){ this.style.background='#fc7b04'; this.style.color='#fff'; };
                btn.onmouseout  = function(){ this.style.background='rgba(252,123,4,.1)'; this.style.color='#c96004'; };
                btn.innerHTML = '<i class="ri-play-circle-line"></i> Reactivar';
            } else {
                btn.className = 'btn-suspender btn-toggle-acceso';
                btn.style.cssText = 'padding:.35rem .75rem;font-size:.75rem;font-weight:600;background:rgba(239,68,68,.1);color:#dc2626;border:none;border-radius:6px;cursor:pointer;transition:all .2s;';
                btn.onmouseover = function(){ this.style.background='#dc2626'; this.style.color='#fff'; };
                btn.onmouseout  = function(){ this.style.background='rgba(239,68,68,.1)'; this.style.color='#dc2626'; };
                btn.innerHTML = '<i class="ri-forbid-line"></i> Suspender';
            }
            btn.disabled = false;

            // Actualizar celda "Estado Moodle" de la misma fila
            const row = btn.closest('tr');
            if (row) {
                const celdaEstado = row.cells[6];
                if (celdaEstado) {
                    if (quedaSuspendido) {
                        celdaEstado.innerHTML = '<span style="display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;background:rgba(239,68,68,.1);color:#dc2626;"><i class="ri-forbid-line"></i> Suspendido</span>';
                    } else {
                        celdaEstado.innerHTML = '<span style="display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .6rem;border-radius:6px;font-size:.75rem;font-weight:600;background:rgba(34,197,94,.1);color:#16a34a;"><i class="ri-check-line"></i> Activo</span>';
                    }
                }
            }
        } else {
            console.error('Error del servidor:', data.message);
            btn.disabled = false;
            btn.innerHTML = suspender ? '<i class="ri-pause-circle-line"></i> Suspender' : '<i class="ri-play-circle-line"></i> Activar';
            mostrarToast('error', data.message || 'Error al cambiar el estado');
        }
    })
    .catch(err => {
        console.error('Error de red:', err);
        btn.disabled = false;
        btn.innerHTML = suspender ? '<i class="ri-pause-circle-line"></i> Suspender' : '<i class="ri-play-circle-line"></i> Activar';
        mostrarToast('error', 'Error de conexión. Verifique la consola.');
    });
}

// Función para mostrar toast de confirmación
function mostrarToast(tipo, mensaje) {
    // Crear el elemento del toast
    const toast = document.createElement('div');
    toast.className = 'toast-notification ' + tipo;
    toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;display:flex;align-items:center;gap:10px;padding:12px 20px;border-radius:8px;background:white;box-shadow:0 4px 20px rgba(0,0,0,0.15);font-size:0.9rem;font-weight:500;transform:translateX(400px);opacity:0;transition:all 0.3s ease;';
    toast.innerHTML = '<i class="ri-' + (tipo === 'success' ? 'check-circle-line' : 'error-warning-line') + '" style="color:' + (tipo === 'success' ? '#16a34a' : '#dc2626') + ';font-size:1.2rem;"></i><span>' + mensaje + '</span>';
    
    document.body.appendChild(toast);
    
    // Animar entrada
    requestAnimationFrame(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    });
    
    // Remover después de 3 segundos
    setTimeout(() => {
        toast.style.transform = 'translateX(400px)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Variables para el modal de confirmación de acceso
let pendienteAcceso = null;
let modalAccesoInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    const modalConfirmarAcceso = document.getElementById('modalConfirmarAcceso');
    const btnConfirmarAcceso = document.getElementById('btnConfirmarAcceso');
    
    // Event handler para los botones de activar/suspender
    document.querySelectorAll('.btn-toggle-acceso').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const inscripcionId = this.getAttribute('data-inscripcion-id');
            const moduloId = this.getAttribute('data-modulo-id');
            const suspender = this.getAttribute('data-suspender') === '1';
            const estudianteNombre = this.closest('tr').querySelector('.estudiante-nombre')?.textContent || 'Estudiante';
            const moduloNombre = document.querySelector('.modulo-nombre-display')?.textContent || 'Módulo';
            
            pendienteAcceso = { inscripcionId, moduloId, suspender, btn: this };
            
            // Configurar contenido del modal
            const titulo = suspender ? 'Suspender Acceso' : 'Activar Acceso';
            const mensaje = suspender 
                ? '¿Está seguro que desea <strong>suspender</strong> el acceso a la plataforma Moodle para este estudiante? El estudiante no podrá acceder al contenido del módulo hasta que se reactive el acceso.'
                : '¿Está seguro que desea <strong>activar</strong> el acceso a la plataforma Moodle para este estudiante? El estudiante podrá acceder nuevamente al contenido del módulo.';
            
            document.getElementById('modalConfirmarAccesoTitle').innerHTML = '<i class="ri-shield-keyhole-line me-2"></i> ' + titulo;
            document.getElementById('modalConfirmarAccesoMsg').innerHTML = mensaje;
            document.getElementById('modalEstudianteNombre').textContent = estudianteNombre;
            document.getElementById('modalModuloNombre').textContent = moduloNombre;
            
            // Cambiar icono y color según la acción
            const icono = document.getElementById('modalAccesoIcono');
            if (suspender) {
                icono.style.background = 'rgba(239,68,68,.1)';
                icono.style.color = '#dc2626';
                icono.innerHTML = '<i class="ri-pause-circle-line"></i>';
                btnConfirmarAcceso.style.background = 'linear-gradient(135deg,#dc2626,#b91c1c)';
                btnConfirmarAcceso.innerHTML = '<i class="ri-check-line"></i> Confirmar';
            } else {
                icono.style.background = 'rgba(252,123,4,.1)';
                icono.style.color = '#c96004';
                icono.innerHTML = '<i class="ri-play-circle-line"></i>';
                btnConfirmarAcceso.style.background = 'linear-gradient(135deg,#fc7b04,#c96004)';
                btnConfirmarAcceso.innerHTML = '<i class="ri-check-line"></i> Confirmar';
            }
            
            // Mostrar modal usando la instancia guardada
            if (!modalAccesoInstance) {
                modalAccesoInstance = new bootstrap.Modal(modalConfirmarAcceso);
            }
            modalAccesoInstance.show();
        });
    });
    
    // Confirmar acción
    if (btnConfirmarAcceso) {
        btnConfirmarAcceso.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (!pendienteAcceso) return;
            
            const { inscripcionId, moduloId, suspender, btn } = pendienteAcceso;
            
            // Cerrar modal usando la instancia guardada
            if (modalAccesoInstance) {
                modalAccesoInstance.hide();
            }
            
            // Ejecutar acción
            toggleAccesoMoodle(inscripcionId, moduloId, suspender, btn);
            
            // Mostrar toast de confirmación
            setTimeout(() => {
                const accionTexto = suspender ? 'suspendido' : 'activado';
                mostrarToast('success', 'El acceso del estudiante ha sido ' + accionTexto + ' correctamente.');
            }, 500);
            
            pendienteAcceso = null;
        });
    }
});



(function () {
    const MOD_LABELS_C = {
        assign:'Tarea', quiz:'Cuestionario', forum:'Foro',
        resource:'Recurso', page:'Página', url:'URL',
        workshop:'Taller', scorm:'SCORM', feedback:'Retroalimentación',
    };
    const MOD_COLORS_C = {
        assign:   { bg:'rgba(252,123,4,.12)',  color:'#c96004' },
        quiz:     { bg:'rgba(217,119,6,.12)',   color:'#b45309' },
        forum:    { bg:'rgba(22,163,74,.12)',   color:'#15803d' },
        resource: { bg:'rgba(14,165,233,.12)',  color:'#0284c7' },
        page:     { bg:'rgba(168,85,247,.12)', color:'#7e22ce' },
        url:      { bg:'rgba(249,115,22,.12)', color:'#c2410c' },
    };

    // ── Estado ──
    let _items       = [];
    let _estudiantes = [];
    let _moduloId    = null;
    let _manualMode  = false;
    // Por actividad: 'ponderar' → (raw/max)*peso  |  'mantener' → raw tal como está en Moodle
    let _modos       = {};
    // Cambio pendiente mientras el modal está abierto
    let _pendingChange = null;
    let _modalCentrInst = null;

    // ── Utilidades ──
    function escHtml(s) {
        return String(s ?? '')
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function getPeso(itemId) {
        const inp = document.getElementById('cPeso_' + itemId);
        return inp ? (parseFloat(inp.value) || 0) : 0;
    }
    function getModo(itemId) {
        return _modos[itemId] || 'ponderar';
    }
    function colorClass(valor, esTotal) {
        if (valor === null || valor === undefined) return 'sin-nota';
        const pct = esTotal ? valor / 100 : valor;
        if (pct >= 0.6) return 'centr-aprobado';
        if (pct >= 0.4) return 'centr-regular';
        return 'centr-reprobado';
    }

    // ── Fórmula principal ──
    // 'ponderar': (raw / max) * peso  → la nota se escala al porcentaje asignado
    // 'mantener': raw tal como está   → el docente ya la ingresó con la ponderación aplicada
    function notaPonderada(item, moodleUserId) {
        const raw = (item.grades ?? {})[moodleUserId] ?? null;
        if (raw === null) return null;
        if (getModo(item.id) === 'mantener') return parseFloat(raw);
        const max  = item.max != null ? parseFloat(item.max) : null;
        const peso = getPeso(item.id);
        if (max === null || max === 0) return null;
        return (raw / max) * peso;
    }

    // Detecta qué modo de cálculo aplica según los pesos actuales:
    //  'cumulative' → suma de pesos = 100  (nota final = suma ponderada)
    //  'average'    → todos los pesos = 100 (nota final = promedio de notas)
    //  'invalid'    → ninguna de las anteriores (no se puede guardar)
    function detectarModoCalculo() {
        if (_items.length === 0) return 'invalid';
        let suma = 0, todosEnCien = true;
        _items.forEach(item => {
            const p = getPeso(item.id);
            suma += p;
            if (Math.abs(p - 100) > 0.01) todosEnCien = false;
        });
        suma = Math.round(suma * 100) / 100;
        if (Math.abs(suma - 100) < 0.01) return 'cumulative';
        if (todosEnCien)                  return 'average';
        return 'invalid';
    }

    function calcNotaFinal(moodleUserId) {
        const modo = detectarModoCalculo();
        let suma = 0, cnt = 0;
        _items.forEach(item => {
            const np = notaPonderada(item, moodleUserId);
            if (np !== null) { suma += np; cnt++; }
        });
        if (cnt === 0) return null;
        // En modo promedio dividimos por la cantidad de actividades con nota
        return modo === 'average' ? suma / cnt : suma;
    }

    // ── Badge de modo en el encabezado ──
    function actualizarBadgeModo(itemId) {
        const badge = document.getElementById('cModoBadge_' + itemId);
        if (!badge) return;
        const modo = getModo(itemId);
        if (modo === 'mantener') {
            badge.textContent = 'Mantiene nota';
            badge.style.background = 'rgba(234,179,8,.15)';
            badge.style.color      = '#92400e';
            badge.style.borderColor= '#fcd34d';
            badge.title = 'Las notas se muestran tal como las registró el docente en Moodle (sin recalcular).';
        } else {
            badge.textContent = 'Ponderado';
            badge.style.background = 'rgba(252,123,4,.1)';
            badge.style.color      = '#c96004';
            badge.style.borderColor= '#fde6cd';
            badge.title = 'Las notas se calculan como (nota Moodle / nota máx) × ponderación%.';
        }
    }

    // ── Recálculo de celdas (sin reconstruir DOM) ──
    function recalcularCeldas() {
        _estudiantes.forEach(est => {
            _items.forEach(item => {
                const cell = document.getElementById('cNota_' + est.moodle_user_id + '_' + item.id);
                if (!cell) return;
                const np  = notaPonderada(item, est.moodle_user_id);
                const raw = (item.grades ?? {})[est.moodle_user_id] ?? null;
                const max = item.max != null ? parseFloat(item.max) : null;

                cell.className   = 'nota-cell ' + (np === null ? 'sin-nota' : colorClass(np / (getPeso(item.id) || 1), false));
                cell.textContent = np !== null ? np.toFixed(2) : '—';

                if (raw !== null) {
                    const modo = getModo(item.id);
                    cell.title = modo === 'mantener'
                        ? 'Nota Moodle (sin ponderar): ' + parseFloat(raw).toFixed(2) + ' / ' + (max ?? '?') + ' pts'
                        : 'Moodle: ' + parseFloat(raw).toFixed(2) + ' / ' + (max ?? '?') + ' pts → ' + np?.toFixed(2) + ' ponderado';
                } else {
                    cell.title = 'Sin calificación en Moodle';
                }
            });

            const nfCell = document.getElementById('cNFinal_' + est.moodle_user_id);
            if (nfCell) {
                const nf   = calcNotaFinal(est.moodle_user_id);
                const modo = detectarModoCalculo();
                const incompleta = modo !== 'cumulative' && modo !== 'average';

                if (nf !== null) {
                    let suma = 0;
                    _items.forEach(i => { suma += getPeso(i.id); });
                    suma = Math.round(suma * 100) / 100;

                    nfCell.className = 'nota-final-cell' + (incompleta ? ' centr-incompleta' : ' ' + colorClass(nf, true));
                    nfCell.innerHTML = incompleta
                        ? `<span style="font-size:.85rem;">${nf.toFixed(2)}</span><br>
                           <span style="font-size:.62rem;color:#f59e0b;font-weight:600;">⚠ /${suma}%</span>`
                        : nf.toFixed(2);
                    nfCell.title = incompleta
                        ? `Suma parcial: ${nf.toFixed(2)} pts sobre ${suma}% asignado (falta ${(100 - suma).toFixed(2)}%)`
                        : '';
                } else {
                    nfCell.className   = 'nota-final-cell sin-nota';
                    nfCell.textContent = '—';
                    nfCell.title       = '';
                }
            }
        });
    }

    // ── Actualiza badge de suma (sin recalcular celdas) ──
    function actualizarBadgeSuma() {
        const badge   = document.getElementById('centrSumaBadge');
        const valSpan = document.getElementById('centrSumaValor');
        const btnSave = document.getElementById('btnGuardarPesosCentr');
        const nfHdr   = document.getElementById('centrNotaFinalHdr');
        if (!badge || !valSpan) return;

        const modo = detectarModoCalculo();
        let suma = 0;
        _items.forEach(item => { suma += getPeso(item.id); });
        suma = Math.round(suma * 100) / 100;

        if (modo === 'average') {
            badge.style.background = 'rgba(22,163,74,.12)';
            badge.style.color       = '#15803d';
            badge.style.borderColor = '#86efac';
            valSpan.textContent = 'Promedio (' + _items.length + ' activ.)';
            if (nfHdr) nfHdr.innerHTML = 'Nota Final<br><small style="font-size:.6rem;font-weight:400;color:#94a3b8;">promedio / 100</small>';
        } else if (modo === 'cumulative') {
            badge.style.background = 'rgba(22,163,74,.12)';
            badge.style.color       = '#15803d';
            badge.style.borderColor = '#86efac';
            valSpan.textContent = '100% ✓';
            if (nfHdr) nfHdr.innerHTML = 'Nota Final<br><small style="font-size:.6rem;font-weight:400;color:#94a3b8;">ponderada / 100</small>';
        } else {
            // Suma parcial — válido para guardar, pero la nota final estará incompleta
            badge.style.background = 'rgba(234,179,8,.12)';
            badge.style.color       = '#92400e';
            badge.style.borderColor = '#fcd34d';
            valSpan.textContent = suma + '% de 100%';
            if (nfHdr) nfHdr.innerHTML = 'Nota Final<br><small style="font-size:.6rem;font-weight:400;color:#f59e0b;">⚠ incompleta</small>';
        }

        // El botón siempre está habilitado (el backend acepta cualquier suma)
        if (btnSave) btnSave.disabled = false;

        // Mostrar botones PDF solo cuando las ponderaciones sumen 100%
        const esCompleto = (modo === 'cumulative' || modo === 'average');
        const btnDet = document.getElementById('btnReporteDetallado');
        const btnFin = document.getElementById('btnReporteFinales');
        if (btnDet) btnDet.style.display = esCompleto ? 'inline-flex' : 'none';
        if (btnFin) btnFin.style.display = esCompleto ? 'inline-flex' : 'none';
    }

    // ── Modal de confirmación al cambiar ponderación ──
    function abrirModalCambioModo(item, pesoAnterior, pesoNuevo) {
        _pendingChange = { item, pesoAnterior, pesoNuevo };

        document.getElementById('cpActNombre').textContent   = item.name;
        document.getElementById('cpPesoAnterior').textContent = pesoAnterior.toFixed(2);
        document.getElementById('cpPesoNuevo').textContent    = pesoNuevo.toFixed(2);

        const max = item.max != null ? parseFloat(item.max).toFixed(2) : '?';
        document.getElementById('cpActMax').textContent = max;

        // Ejemplo con el primer estudiante que tenga nota
        let ejemploRaw = null;
        for (const est of _estudiantes) {
            const g = (item.grades ?? {})[est.moodle_user_id] ?? null;
            if (g !== null) { ejemploRaw = parseFloat(g); break; }
        }
        const maxN = item.max != null ? parseFloat(item.max) : null;
        if (ejemploRaw !== null && maxN && maxN > 0) {
            const ejPond = ((ejemploRaw / maxN) * pesoNuevo).toFixed(2);
            document.getElementById('cpEjemploPond').textContent =
                `Ej: ${ejemploRaw.toFixed(2)} / ${max} pts × ${pesoNuevo.toFixed(2)}% = ${ejPond}`;
            document.getElementById('cpEjemploMant').textContent =
                `Ej: nota mostrada = ${ejemploRaw.toFixed(2)} (tal como está en Moodle)`;
        } else {
            document.getElementById('cpEjemploPond').textContent = '';
            document.getElementById('cpEjemploMant').textContent = '';
        }

        const modalEl = document.getElementById('modalCentrPond');
        if (!_modalCentrInst) _modalCentrInst = new bootstrap.Modal(modalEl);
        _modalCentrInst.show();
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
        if (_modalCentrInst) _modalCentrInst.hide();
        actualizarBadgeSuma();
        actualizarBadgeModo(item.id);
        recalcularCeldas();
    }

    // ── Render completo ──
    function renderCentralizador(data) {
        _items       = data.grade_items || [];
        _estudiantes = data.estudiantes || [];
        _moduloId    = data.modulo_id   || document.getElementById('btnCargarCentralizador')?.getAttribute('data-modulo-id');
        _manualMode  = data.manual_mode ?? false;
        _modos       = {};   // reset: todas en modo 'ponderar' al recargar

        // Leyenda de tipos
        const leyenda = document.getElementById('centrLeyenda');
        if (leyenda) {
            const tipos = [...new Set(_items.map(i => i.module))];
            const chips = tipos.map(mod => {
                const lbl = MOD_LABELS_C[mod] || mod;
                const col = MOD_COLORS_C[mod] || { bg:'rgba(156,163,175,.12)', color:'#6b7280' };
                return `<span style="background:${col.bg};color:${col.color};padding:3px 12px;border-radius:20px;font-size:.72rem;font-weight:600;border:1px solid ${col.color}22;">${lbl}</span>`;
            }).join('');
            leyenda.innerHTML = `<span class="centr-leyenda-label"><i class="ri-price-tag-3-line"></i> Tipos:</span>${chips}`;
        }

        // ── THEAD (3 filas) ──
        const thead = document.getElementById('centrThead');
        if (thead) {
            const moodleUrl = data.moodle_url || '';

            // Fila 1: columnas fijas + nombres de actividades + Nota Final
            let r1 = `<tr class="centr-thead-r1">
                <th class="centr-th-fixed" rowspan="3" style="min-width:38px;vertical-align:middle;text-align:center;">#</th>
                <th class="centr-th-fixed" rowspan="3" style="min-width:190px;vertical-align:middle;">
                    <i class="ri-user-line" style="opacity:.7;margin-right:4px;"></i>Estudiante
                </th>
                <th class="centr-th-fixed" rowspan="3" style="min-width:85px;vertical-align:middle;text-align:center;">CI</th>`;

            _items.forEach(item => {
                const url = moodleUrl ? moodleUrl + '/mod/' + item.module + '/view.php?id=' + item.cmid : '#';
                r1 += `<th class="th-act" style="min-width:155px;text-align:center;vertical-align:middle;">
                    <span style="display:block;font-size:.74rem;font-weight:700;line-height:1.3;margin-bottom:3px;">${escHtml(item.name)}</span>
                    <a href="${url}" target="_blank"
                        style="font-size:.6rem;color:rgba(252,123,4,.9);background:rgba(252,123,4,.12);padding:1px 7px;border-radius:10px;display:inline-flex;align-items:center;gap:3px;text-decoration:none;">
                        <i class="ri-external-link-line"></i> Moodle
                    </a>
                </th>`;
            });

            r1 += `<th id="centrNotaFinalHdr" class="centr-th-nfinal" rowspan="3"
                style="min-width:110px;vertical-align:middle;text-align:center;font-size:.73rem;font-weight:700;letter-spacing:.03em;">
                <i class="ri-bar-chart-2-line" style="display:block;font-size:1.1rem;margin-bottom:2px;opacity:.8;"></i>
                NOTA FINAL<br><small style="font-size:.6rem;font-weight:400;opacity:.75;">(sobre 100)</small>
            </th></tr>`;

            // Fila 2: tipo + nota máx + badge modo
            let r2 = '<tr class="centr-thead-r2">';
            _items.forEach(item => {
                const lbl = MOD_LABELS_C[item.module] || item.module;
                const col = MOD_COLORS_C[item.module] || { bg:'rgba(156,163,175,.12)', color:'#6b7280' };
                const max = item.max != null ? parseFloat(item.max).toFixed(2) : '—';
                r2 += `<th>
                    <span style="background:${col.bg};color:${col.color};padding:2px 9px;border-radius:20px;font-size:.67rem;font-weight:700;display:inline-block;border:1px solid ${col.color}33;">${lbl}</span>
                    <span style="display:block;font-size:.65rem;color:var(--d-muted);margin-top:3px;">Máx: <strong style="color:var(--d-body)">${max}</strong> pts</span>
                    <span id="cModoBadge_${item.id}" class="centr-modo-badge"
                        style="background:rgba(252,123,4,.1);color:#c96004;border-color:#fde6cd;"
                        title="Las notas se calculan como (nota Moodle / nota máx) × ponderación%.">Ponderado</span>
                </th>`;
            });
            r2 += '</tr>';

            // Fila 3: inputs de ponderación
            let r3 = '<tr class="centr-thead-r3">';
            _items.forEach(item => {
                r3 += `<th>
                    <label style="font-size:.62rem;font-weight:600;color:var(--d-muted);display:block;margin-bottom:3px;text-transform:uppercase;letter-spacing:.03em;">Pond. %</label>
                    <div style="display:flex;align-items:center;justify-content:center;gap:4px;">
                        <input type="number" id="cPeso_${item.id}"
                            class="centr-peso-input"
                            data-item-id="${item.id}"
                            data-peso-prev="${parseFloat(item.weight).toFixed(2)}"
                            min="0" max="100" step="0.01"
                            value="${parseFloat(item.weight).toFixed(2)}"
                            title="Ponderación de la actividad (%)">
                        <span style="font-size:.7rem;color:var(--d-muted);font-weight:600;">%</span>
                    </div>
                </th>`;
            });
            r3 += '</tr>';

            thead.innerHTML = r1 + r2 + r3;

            // Eventos de ponderación
            thead.querySelectorAll('.centr-peso-input').forEach(inp => {
                // Actualizar badge de suma en tiempo real mientras escribe
                inp.addEventListener('input', actualizarBadgeSuma);

                // Al salir del campo: mostrar modal si el valor cambió
                inp.addEventListener('change', function () {
                    const itemId     = parseInt(this.getAttribute('data-item-id'));
                    const pesoPrev   = parseFloat(this.getAttribute('data-peso-prev')) || 0;
                    const pesoNuevo  = parseFloat(this.value) || 0;

                    if (Math.abs(pesoNuevo - pesoPrev) < 0.001) return; // no cambió

                    const item = _items.find(i => i.id === itemId);
                    if (!item) return;

                    // Guardar nuevo valor como "anterior" para el próximo cambio
                    this.setAttribute('data-peso-prev', pesoNuevo.toFixed(2));

                    abrirModalCambioModo(item, pesoPrev, pesoNuevo);
                });
            });
        }

        // ── TBODY ──
        const tbody = document.getElementById('centrTbody');
        if (tbody) {
            if (_estudiantes.length === 0) {
                tbody.innerHTML = `<tr><td colspan="${3 + _items.length + 1}"
                    style="text-align:center;padding:2rem;color:#94a3b8;">
                    Sin estudiantes matriculados en Moodle.</td></tr>`;
            } else {
                tbody.innerHTML = _estudiantes.map((est, idx) => {
                    let row = `<tr>
                        <td style="text-align:center;color:#94a3b8;font-size:.82rem;">${idx + 1}</td>
                        <td><strong style="font-size:.87rem;">${escHtml(est.nombre)}</strong></td>
                        <td style="text-align:center;font-size:.84rem;">${escHtml(est.ci)}</td>`;

                    _items.forEach(item => {
                        const np  = notaPonderada(item, est.moodle_user_id);
                        const raw = (item.grades ?? {})[est.moodle_user_id] ?? null;
                        const max = item.max != null ? parseFloat(item.max) : null;
                        const cls = 'nota-cell ' + (np === null ? 'sin-nota' : colorClass(np / (getPeso(item.id) || 1), false));
                        const lbl = np !== null ? np.toFixed(2) : '—';
                        const tip = raw !== null
                            ? 'Moodle: ' + parseFloat(raw).toFixed(2) + ' / ' + (max ?? '?') + ' pts'
                            : 'Sin calificación en Moodle';
                        row += `<td id="cNota_${est.moodle_user_id}_${item.id}"
                            class="${cls}" style="text-align:center;" title="${tip}">${lbl}</td>`;
                    });

                    const nf         = calcNotaFinal(est.moodle_user_id);
                    const modoCalc   = detectarModoCalculo();
                    const incompleta = modoCalc !== 'cumulative' && modoCalc !== 'average';
                    let nfCls = 'nota-final-cell';
                    let nfHtml = '—';
                    let nfTitle = '';
                    if (nf !== null) {
                        let pesoTotal = 0;
                        _items.forEach(i => { pesoTotal += getPeso(i.id); });
                        pesoTotal = Math.round(pesoTotal * 100) / 100;
                        if (incompleta) {
                            nfCls  += ' centr-incompleta';
                            nfHtml  = `<span style="font-size:.85rem;">${nf.toFixed(2)}</span><br><span style="font-size:.62rem;color:#f59e0b;font-weight:600;">⚠ /${pesoTotal}%</span>`;
                            nfTitle = `Suma parcial: ${nf.toFixed(2)} pts sobre ${pesoTotal}% asignado`;
                        } else {
                            nfCls  += ' ' + colorClass(nf, true);
                            nfHtml  = nf.toFixed(2);
                        }
                    }
                    row += `<td id="cNFinal_${est.moodle_user_id}" class="${nfCls}"
                        style="text-align:center;font-weight:700;" title="${nfTitle}">${nfHtml}</td></tr>`;
                    return row;
                }).join('');
            }
        }

        actualizarBadgeSuma();
        document.getElementById('btnExportarCentralizador')?.style && (document.getElementById('btnExportarCentralizador').style.display = 'inline-flex');
    }

    // ── Guardar ponderaciones ──
    function guardarPesosCentr() {
        if (!_moduloId) return;
        const btn  = document.getElementById('btnGuardarPesosCentr');
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const items = _items.map(item => ({
            id: item.id, name: item.name, module: item.module, cmid: item.cmid, weight: getPeso(item.id),
        }));
        const isCumulative = detectarModoCalculo() !== 'average';
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="ri-loader-4-line"></i> Guardando…'; }
        fetch('/admin/posgrads/modulos/' + _moduloId + '/academico/ponderaciones', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify({ items, is_cumulative: isCumulative }),
        })
        .then(r => r.json())
        .then(data => {
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="ri-save-line"></i> Guardar ponderaciones'; }
            if (data.success) {
                _items.forEach(item => { item.weight = getPeso(item.id); });
                mostrarToastCentr('success', 'Ponderaciones guardadas correctamente.');
                // Mostrar botón de sincronización con Moodle
                const btnSync = document.getElementById('btnSincronizarMoodle');
                if (btnSync) btnSync.style.display = 'inline-flex';
            } else {
                mostrarToastCentr('error', data.message || 'Error al guardar.');
            }
        })
        .catch(() => {
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="ri-save-line"></i> Guardar ponderaciones'; }
            mostrarToastCentr('error', 'Error de conexión.');
        });
    }

    function mostrarToastCentr(tipo, msg) {
        const t = document.createElement('div');
        t.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;display:flex;align-items:center;gap:10px;padding:12px 20px;border-radius:8px;background:white;box-shadow:0 4px 20px rgba(0,0,0,.15);font-size:.9rem;font-weight:500;transition:all .3s;transform:translateX(400px);opacity:0;';
        const iconColor = tipo === 'success' ? '#16a34a' : '#dc2626';
        t.innerHTML = `<i class="ri-${tipo === 'success' ? 'check-circle-line' : 'error-warning-line'}" style="color:${iconColor};font-size:1.2rem;"></i><span>${msg}</span>`;
        document.body.appendChild(t);
        requestAnimationFrame(() => { t.style.transform = 'translateX(0)'; t.style.opacity = '1'; });
        setTimeout(() => { t.style.transform = 'translateX(400px)'; t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3200);
    }

    // ── Carga ──
    window.cargarCentralizador = function(moduloId) {
        _moduloId = moduloId;
        const loading    = document.getElementById('centrLoading');
        const contenido  = document.getElementById('centrContenido');
        const msgInicial = document.getElementById('centrMsgInicial');
        const errDiv     = document.getElementById('centrError');
        const errMsg     = document.getElementById('centrErrorMsg');
        if (loading)    loading.style.display    = 'block';
        if (contenido)  contenido.style.display  = 'none';
        if (msgInicial) msgInicial.style.display = 'none';
        if (errDiv)     errDiv.style.display     = 'none';
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        fetch('/admin/posgrads/modulos/' + moduloId + '/academico/calificaciones', {
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(data => {
            if (loading) loading.style.display = 'none';
            if (!data.success) {
                if (errMsg) errMsg.textContent = data.message || 'Error desconocido.';
                if (errDiv) errDiv.style.display = 'block';
                return;
            }
            data.modulo_id = moduloId;
            renderCentralizador(data);
            if (contenido) contenido.style.display = 'block';
        })
        .catch(() => {
            if (loading) loading.style.display = 'none';
            if (errMsg)  errMsg.textContent = 'Error de conexión con el servidor.';
            if (errDiv)  errDiv.style.display = 'block';
        });
    };

    // ── CSV ──
    function exportarCentralizadorCSV() {
        if (!_items.length || !_estudiantes.length) return;
        const rows = [['#','Estudiante','CI',
            ..._items.map(i => `"${i.name} (${MOD_LABELS_C[i.module]||i.module} · ${getPeso(i.id)}% · ${getModo(i.id)})"`),
            'Nota Final'].join(',')];
        _estudiantes.forEach((est, idx) => {
            const row = [idx+1, `"${est.nombre}"`, est.ci,
                ..._items.map(item => { const np=notaPonderada(item,est.moodle_user_id); return np!==null?np.toFixed(2):''; }),
                (()=>{ const nf=calcNotaFinal(est.moodle_user_id); return nf!==null?nf.toFixed(2):''; })()
            ];
            rows.push(row.join(','));
        });
        const blob = new Blob(['﻿'+rows.join('\n')], {type:'text/csv;charset=utf-8;'});
        const a = Object.assign(document.createElement('a'), {href:URL.createObjectURL(blob), download:'centralizador_notas.csv'});
        a.click(); URL.revokeObjectURL(a.href);
    }

    // ── Sincronizar ponderaciones con Moodle ──
    let _itemsSincPendientes = [];

    function sincronizarConMoodle() {
        if (!_moduloId || !_items.length) return;

        _itemsSincPendientes = _items.map(item => ({
            id:            item.id,
            module:        item.module,
            cmid:          item.cmid,
            peso:          getPeso(item.id),
            peso_original: parseFloat(item.max) || 0,
            modo:          getModo(item.id),
            grades:        item.grades ?? {},
        }));

        // Poblar resumen en el modal
        const MOD_ICON = { quiz: 'ri-questionnaire-line', assign: 'ri-task-line', forum: 'ri-discuss-line', resource: 'ri-file-line', url: 'ri-link', page: 'ri-file-text-line' };
        const resumenEl = document.getElementById('smResumenItems');
        if (resumenEl) {
            resumenEl.innerHTML = _itemsSincPendientes.map(it => {
                const nombre = _items.find(i => i.id === it.id)?.name ?? ('Item ' + it.id);
                const modo   = it.modo === 'mantener'
                    ? '<span style="color:#b45309;font-size:.75rem;"><i class="ri-lock-line"></i> Mantiene nota</span>'
                    : '<span style="color:#6366f1;font-size:.75rem;"><i class="ri-calculator-line"></i> Recalcula nota</span>';
                const icon   = MOD_ICON[it.module] || 'ri-checkbox-blank-circle-line';
                return `<div style="display:flex;align-items:center;justify-content:space-between;gap:.5rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:.55rem .85rem;">
                    <span style="display:flex;align-items:center;gap:.45rem;font-size:.83rem;color:#1e293b;font-weight:600;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        <i class="${icon}" style="color:#fc7b04;flex-shrink:0;"></i>${nombre}
                    </span>
                    <span style="display:flex;align-items:center;gap:.6rem;flex-shrink:0;">
                        <span style="font-size:.8rem;color:#64748b;">${it.peso_original}% → <strong style="color:#0f172a;">${it.peso}%</strong></span>
                        ${modo}
                    </span>
                </div>`;
            }).join('');
        }

        const totalEl = document.getElementById('smTotalEstudiantes');
        if (totalEl) totalEl.textContent = _estudiantes.length;

        const modal = new bootstrap.Modal(document.getElementById('modalSincronizarMoodle'));
        modal.show();
    }

    function ejecutarSincronizacionMoodle() {
        const btn  = document.getElementById('btnSincronizarMoodle');
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        bootstrap.Modal.getInstance(document.getElementById('modalSincronizarMoodle'))?.hide();

        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="ri-loader-4-line"></i> Sincronizando…'; }

        fetch('/admin/posgrads/modulos/' + _moduloId + '/centralizador/sincronizar-moodle', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify({ items: _itemsSincPendientes }),
        })
        .then(r => r.json())
        .then(data => {
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="ri-refresh-line"></i> Sincronizar con Moodle'; }

            if (data.success) {
                mostrarToastCentr('success', data.mensaje || 'Moodle actualizado correctamente.');
                _items.forEach(item => { item.max = getPeso(item.id); });
                renderCentralizador({ grade_items: _items, estudiantes: _estudiantes, modulo_id: _moduloId, manual_mode: _manualMode });
            } else {
                mostrarToastCentr('error', data.mensaje || 'Error al sincronizar con Moodle.');
                if (data.detalles) console.warn('Detalles sincronización:', data.detalles);
            }
        })
        .catch(err => {
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="ri-refresh-line"></i> Sincronizar con Moodle'; }
            console.error('sincronizarConMoodle catch:', err);
            mostrarToastCentr('error', 'Error al sincronizar. Revisa la consola para más detalles.');
        });
    }

    // ── Eventos DOMContentLoaded ──
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('btnCargarCentralizador')?.addEventListener('click', function () {
            window.cargarCentralizador(this.getAttribute('data-modulo-id'));
        });
        document.getElementById('btnGuardarPesosCentr')?.addEventListener('click', guardarPesosCentr);
        document.getElementById('btnSincronizarMoodle')?.addEventListener('click', sincronizarConMoodle);
        document.getElementById('btnConfirmarSincMoodle')?.addEventListener('click', ejecutarSincronizacionMoodle);
        document.getElementById('btnExportarCentralizador')?.addEventListener('click', exportarCentralizadorCSV);

        // Botones del modal de confirmación
        document.getElementById('btnCentrPonderar')?.addEventListener('click', () => confirmarModo('ponderar'));
        document.getElementById('btnCentrMantener')?.addEventListener('click', () => confirmarModo('mantener'));

        // Si el usuario cierra el modal sin elegir → revertir el valor del input
        document.getElementById('modalCentrPond')?.addEventListener('hidden.bs.modal', function () {
            if (_pendingChange) {
                const inp = document.getElementById('cPeso_' + _pendingChange.item.id);
                if (inp) {
                    inp.value = _pendingChange.pesoAnterior.toFixed(2);
                    inp.setAttribute('data-peso-prev', _pendingChange.pesoAnterior.toFixed(2));
                }
                _pendingChange = null;
                actualizarBadgeSuma();
            }
        });
    });
})();

// ============================================================
// FIN CENTRALIZADOR DE NOTAS
// ============================================================

// ── Helpers para modales de preguntas ──
function addMcOption() {
    var d = document.getElementById('mcOptionsContainer');
    var row = document.createElement('div');
    row.className = 'mc-option';
    row.style.cssText = 'display:grid;grid-template-columns:1fr 76px 32px;gap:.4rem;margin-top:.35rem;align-items:center;';
    row.innerHTML =
        '<input class="form-control" placeholder="Texto" style="font-size:.83rem;border-radius:7px;border:1.5px solid #e2e8f0;padding:.35rem .55rem;">' +
        '<input class="form-control" type="number" step="0.01" value="0" style="font-size:.83rem;border-radius:7px;border:1.5px solid #e2e8f0;padding:.35rem .4rem;text-align:center;">' +
        '<button onclick="this.closest(\'.mc-option\').remove()" style="width:28px;height:28px;border-radius:6px;background:rgba(239,68,68,.1);border:none;color:#dc2626;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.8rem;transition:all .15s;" onmouseover="this.style.background=\'rgba(239,68,68,.25)\'" onmouseout="this.style.background=\'rgba(239,68,68,.1)\'"><i class="ri-delete-bin-line"></i></button>';
    d.appendChild(row);
}

function addMatchPair() {
    var d = document.getElementById('matchPairsContainer');
    var row = document.createElement('div');
    row.className = 'match-pair';
    row.style.cssText = 'display:grid;grid-template-columns:1fr 1fr 32px;gap:.4rem;margin-top:.35rem;align-items:center;';
    row.innerHTML =
        '<input class="form-control" placeholder="Pregunta" style="font-size:.83rem;border-radius:7px;border:1.5px solid #e2e8f0;padding:.35rem .55rem;">' +
        '<input class="form-control" placeholder="Respuesta" style="font-size:.83rem;border-radius:7px;border:1.5px solid #e2e8f0;padding:.35rem .55rem;">' +
        '<button onclick="this.closest(\'.match-pair\').remove()" style="width:28px;height:28px;border-radius:6px;background:rgba(239,68,68,.1);border:none;color:#dc2626;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.8rem;transition:all .15s;" onmouseover="this.style.background=\'rgba(239,68,68,.25)\'" onmouseout="this.style.background=\'rgba(239,68,68,.1)\'"><i class="ri-delete-bin-line"></i></button>';
    d.appendChild(row);
}

</script>

{{-- Editor de Actividades Moodle --}}
<script src="{{ URL::asset('build/libs/sortablejs/Sortable.min.js') }}"></script>
<script src="{{ URL::asset('build/js/actividades-editor.js') }}"></script>
</div>{{-- .modulo-detalle-page --}}
@endsection
