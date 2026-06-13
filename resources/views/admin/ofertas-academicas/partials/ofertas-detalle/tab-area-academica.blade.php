<div class="tab-content-section" id="tab-area-academica">

    {{-- Header --}}
    <div class="tab-section-header">
        <div class="tab-section-header-left">
            <div class="tab-section-icon con-icon-color"><i class="ri-graduation-cap-line"></i></div>
            <div>
                <div class="tab-section-title">Área Académica</div>
                <div class="tab-section-sub">Estudiantes inscritos, datos personales y notas por módulo</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="aa-count-badge"><i class="ri-user-3-line"></i> <span id="aaTotalEstudiantes">{{ count($areaAcademicaEstudiantes) }}</span> estudiantes</span>
            <button type="button" class="aa-btn-refresh" id="btnRefrescarNotasAA" title="Recargar notas">
                <i class="ri-refresh-line"></i> Recargar notas
            </button>
        </div>
    </div>

    @php
        $aaEstadoStyles = [
            'No Inició'     => ['bg' => 'rgba(100,116,139,.14)', 'color' => '#475569', 'icon' => 'ri-time-line'],
            'En Desarrollo' => ['bg' => 'rgba(34,197,94,.14)',   'color' => '#16a34a', 'icon' => 'ri-loader-3-line'],
            'Concluido'     => ['bg' => 'rgba(99,102,241,.14)',  'color' => '#4f46e5', 'icon' => 'ri-checkbox-circle-line'],
        ];
        $aaFaseDesarrollo = (int) ($oferta->fase_id ?? 0) === 4;
    @endphp

    @if ($aaFaseDesarrollo)
        <div class="aa-fase-banner">
            <div class="aa-fase-banner-icon"><i class="ri-progress-3-line"></i></div>
            <div>
                <div class="aa-fase-banner-title">Programa en fase de desarrollo</div>
                <div class="aa-fase-banner-sub">Los módulos pueden estar en curso o concluidos. Las notas finales se muestran a medida que cada módulo pasa a estado <strong>Concluido</strong>.</div>
            </div>
        </div>
    @endif

    <style>
        .aa-fase-banner {
            display: flex; align-items: center; gap: 14px;
            background: linear-gradient(135deg, rgba(34,197,94,.10), rgba(16,185,129,.05));
            border: 1px solid rgba(34,197,94,.25);
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 18px;
        }
        .aa-fase-banner-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .aa-fase-banner-title { font-family: 'Sora','DM Sans',sans-serif; font-weight: 700; font-size: 1rem; color: #14532d; margin-bottom: 2px; }
        .aa-fase-banner-sub { font-size: .8rem; color: #166534; line-height: 1.5; }
        .aa-nota-final-cargada { color: #047857; font-weight: 700; }
        .aa-nota-final-fail    { color: #b91c1c; font-weight: 700; }

        /* ── Sub-celdas Final | 2da Inst. dentro de cada módulo ── */
        .aa-mod-cell { padding: 0 !important; }
        .aa-notas-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 36px;
        }
        .aa-notas-grid--solo { grid-template-columns: 1fr; }
        .aa-notas-grid--solo .aa-sub-final { border-right: none; }
        .aa-sub {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px 4px;
            transition: background .2s ease;
        }
        .aa-sub-content {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .aa-sub-final { border-right: 1px solid var(--cont-border, #e2e8f0); }
        .aa-sub--ok   { background: rgba(16, 185, 129, 0.16); }
        .aa-sub--fail { background: rgba(220, 38, 38, 0.16); }
        .aa-nota-empty {
            display: inline-block;
            width: 100%;
            min-height: 1.2em;
        }

        /* Botón "Habilitar 2da" cuando reprobó pero no se habilitó nivelación */
        .aa-nivel-hab-btn {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            background: rgba(245, 158, 11, .15);
            color: #b45309;
            border: 1px dashed rgba(245, 158, 11, .55);
            border-radius: 7px;
            font-size: .6rem;
            font-weight: 700;
            padding: 1px 6px;
            cursor: pointer;
            font-family: 'Sora','DM Sans',sans-serif;
            transition: all .15s ease;
        }
        .aa-nivel-hab-btn:hover { background: rgba(245, 158, 11, .25); transform: translateY(-1px); }
        .aa-nivel-hab-btn i { font-size: .78rem; }

        /* Botón "deshabilitar" (X chico junto al "+ Registrar") */
        .aa-nivel-deshab-btn {
            background: transparent;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: color .15s ease;
        }
        .aa-nivel-deshab-btn:hover { color: #dc2626; }
        .aa-nivel-deshab-btn i { font-size: .95rem; }
        .aa-nota-2da-editable {
            cursor: pointer;
            border-bottom: 1px dashed currentColor;
            transition: background .15s ease;
        }
        .aa-nota-2da-editable:hover { background: rgba(0,0,0,.04); }
        .aa-nota-2da-add {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            background: rgba(99,102,241,.10);
            color: #4338ca;
            border: 1px dashed rgba(99,102,241,.45);
            border-radius: 8px;
            font-size: .65rem;
            font-weight: 700;
            padding: 2px 7px;
            cursor: pointer;
            font-family: 'Sora','DM Sans',sans-serif;
            transition: all .15s ease;
        }
        .aa-nota-2da-add:hover { background: rgba(99,102,241,.18); transform: translateY(-1px); }
        .aa-nota-2da-add i { font-size: .82rem; }
    </style>

    {{-- Modal registrar / editar nota 2da instancia --}}
    <div class="modal fade" id="modal2daInstancia" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18);">
                <div class="modal-header" style="background:linear-gradient(135deg,#3730a3 0%,#4338ca 50%,#6366f1 100%);color:#fff;padding:1rem 1.4rem;border:none;">
                    <div class="d-flex align-items-center gap-3" style="flex:1;">
                        <div style="width:42px;height:42px;background:rgba(255,255,255,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="ri-edit-2-line" style="font-size:1.2rem;"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <h5 class="modal-title mb-0" style="font-size:.98rem;font-weight:700;color:#fff;">Nota de 2da Instancia</h5>
                            <div id="modal2daSub" style="font-size:.72rem;opacity:.85;margin-top:.15rem;color:rgba(255,255,255,.9);">—</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:18px 22px;">
                    <p style="font-size:.78rem;color:#475569;margin-bottom:14px;">
                        Ingresá la nota de 2da instancia. La nota máxima permitida es la <strong>nota mínima de aprobación</strong> de la oferta.
                    </p>
                    <label style="font-size:.72rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px;display:block;">Nota (máx. <span id="modal2daMax">—</span>)</label>
                    <input type="number" id="modal2daInput" step="0.01" min="0" class="form-control" style="font-size:1rem;font-weight:600;border-radius:10px;border:1.5px solid #cbd5e1;padding:.55rem .8rem;">
                    <div id="modal2daError" style="display:none;font-size:.72rem;color:#b91c1c;margin-top:8px;"></div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #e2e8f0;background:#f8fafc;padding:.8rem 1.2rem;justify-content:space-between;">
                    <button type="button" id="modal2daDelete" class="btn btn-sm" style="background:rgba(220,38,38,.10);color:#b91c1c;border:1px solid rgba(220,38,38,.25);font-weight:600;border-radius:8px;display:none;">
                        <i class="ri-delete-bin-line"></i> Quitar nota
                    </button>
                    <div class="d-flex gap-2 ms-auto">
                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal" style="border:1px solid #cbd5e1;font-weight:600;">
                            <i class="ri-close-line"></i> Cancelar
                        </button>
                        <button type="button" id="modal2daSave" class="btn btn-sm" style="background:linear-gradient(135deg,#4338ca,#6366f1);color:#fff;border:none;font-weight:700;padding:.45rem 1.1rem;border-radius:8px;">
                            <i class="ri-check-line"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        // Por cada módulo, ¿hay al menos un estudiante con nivelacion_habilitada = true?
        $modulosConNivelacion = [];
        foreach (($notasMatriculaciones ?? []) as $insId => $porModulo) {
            foreach ($porModulo as $modId => $datos) {
                if (!empty($datos['nivelacion_habilitada'])) {
                    $modulosConNivelacion[$modId] = true;
                }
            }
        }
    @endphp

    @if (empty($areaAcademicaEstudiantes))
        <div class="ins-state-box" style="margin:2rem auto;">
            <div class="ins-empty-icon" style="color:var(--brand-color);background:rgba(var(--brand-color-rgb),.08);">
                <i class="ri-user-search-line"></i>
            </div>
            <p class="ins-state-text fw-semibold" style="color:#334155;">No hay estudiantes inscritos en esta oferta</p>
            <p class="ins-state-text">Registra inscripciones desde la pestaña anterior</p>
        </div>
    @else
        <div class="aa-table-wrap">
            <table class="aa-table" id="tablaAreaAcademica">
                <thead>
                    <tr>
                        <th class="aa-sticky-col aa-stk-1">#</th>
                        <th class="aa-sticky-col aa-stk-2">Carnet</th>
                        <th class="aa-sticky-col aa-stk-3" style="min-width:260px;">Estudiante</th>
                        <th style="min-width:230px;">Contacto</th>
                        <th style="min-width:180px;">Ubicación</th>
                        <th style="min-width:230px;">Datos Personales</th>
                        <th style="min-width:140px;text-align:center;">Estudios</th>
                        <th style="min-width:170px;text-align:center;" title="Estado Académico con sugerencia automática">
                            <i class="ri-graduation-cap-line" style="color:#6366f1;margin-right:3px;"></i>Académico
                        </th>
                        @foreach ($oferta->modulos as $mod)
                            @php
                                $estMod = $mod->estado ?: 'No Inició';
                                $st = $aaEstadoStyles[$estMod] ?? $aaEstadoStyles['No Inició'];
                            @endphp
                            @php $modTiene2da = !empty($modulosConNivelacion[$mod->id]); @endphp
                            <th class="aa-mod-col {{ $modTiene2da ? '' : 'aa-mod-col--solo' }}"
                                data-modulo-id="{{ $mod->id }}"
                                data-modulo-estado="{{ $estMod }}"
                                data-tiene-2da="{{ $modTiene2da ? 1 : 0 }}">
                                <div class="aa-mod-col-name" title="{{ $mod->nombre }}">
                                    <i class="ri-book-2-line"></i>
                                    <span>{{ $mod->nombre }}</span>
                                </div>
                                @if ($aaFaseDesarrollo)
                                    <div class="aa-mod-col-estado">
                                        <span class="aa-mod-estado-chip" style="background:{{ $st['bg'] }};color:{{ $st['color'] }};">
                                            <i class="{{ $st['icon'] }}"></i> {{ $estMod }}
                                        </span>
                                    </div>
                                @endif
                                <div class="aa-mod-col-sub">
                                    <span class="aa-mod-col-sub-label">Final</span>
                                    @if ($modTiene2da)
                                        <span class="aa-mod-col-sub-label">2da Inst.</span>
                                    @endif
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($areaAcademicaEstudiantes as $i => $est)
                        @php
                            $nombreCompleto = trim(($est['apellido_paterno'] ?? '') . ' ' . ($est['apellido_materno'] ?? '') . ' ' . ($est['nombres'] ?? ''));
                            $sexoLetra = $est['sexo'] === 'M' ? 'M' : ($est['sexo'] === 'F' ? 'F' : '—');
                        @endphp
                        @php
                            $acadActivo = $est['activo_academico'] ?? true;
                            $sugAcad    = $est['sugerencia_academica'] ?? null;
                            $reprobados = $est['cantidad_reprobados'] ?? 0;
                            $rowAcadCls = $acadActivo ? 'aa-row--acad-on' : 'aa-row--acad-off';
                        @endphp
                        <tr data-carnet="{{ $est['carnet'] }}" data-inscripcion-id="{{ $est['inscripcion_id'] }}" class="{{ $rowAcadCls }}">
                            <td class="aa-sticky-col aa-stk-1 aa-cell-num">{{ $i + 1 }}</td>
                            <td class="aa-sticky-col aa-stk-2 aa-cell-ci">
                                <span class="aa-ci-chip">{{ $est['carnet'] }}</span>
                            </td>
                            <td class="aa-sticky-col aa-stk-3 aa-cell-estudiante">
                                <div class="aa-est-nombre" title="{{ $nombreCompleto }}">{{ $nombreCompleto ?: '—' }}</div>
                            </td>
                            <td class="aa-cell-contacto">
                                <div class="aa-contact-line" title="{{ $est['celular'] }}">
                                    <i class="ri-phone-line"></i>
                                    <span>{{ $est['celular'] }}</span>
                                </div>
                                <div class="aa-contact-line aa-contact-correo" title="{{ $est['correo'] }}">
                                    <i class="ri-mail-line"></i>
                                    <span>{{ $est['correo'] }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="aa-info-line"><i class="ri-map-pin-line"></i> <span>{{ $est['departamento'] }}</span></div>
                                <div class="aa-info-line aa-info-sub"><i class="ri-building-line"></i> <span>{{ $est['ciudad'] }}</span></div>
                            </td>
                            <td class="aa-cell-personal">
                                <div class="aa-personal-row">
                                    @if ($est['sexo'] === 'M')
                                        <span class="aa-sexo-chip aa-sexo-m"><i class="ri-men-line"></i> M</span>
                                    @elseif ($est['sexo'] === 'F')
                                        <span class="aa-sexo-chip aa-sexo-f"><i class="ri-women-line"></i> F</span>
                                    @else
                                        <span class="aa-sexo-chip aa-sexo-na">—</span>
                                    @endif
                                    <span class="aa-personal-item"><i class="ri-cake-2-line"></i> {{ $est['fecha_nacimiento'] }}</span>
                                </div>
                                <div class="aa-info-line aa-info-sub">
                                    <i class="ri-heart-line"></i> <span>{{ $est['estado_civil'] }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                @if (empty($est['estudios']))
                                    <span class="text-muted" style="font-size:0.72rem;">Sin estudios</span>
                                @else
                                    <button type="button" class="aa-btn-estudios"
                                        data-estudios='@json($est['estudios'])'
                                        data-estudiante="{{ $nombreCompleto }}"
                                        data-carnet="{{ $est['carnet'] }}">
                                        <i class="ri-graduation-cap-line"></i>
                                        Ver ({{ count($est['estudios']) }})
                                    </button>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="aa-academico-cell"
                                     data-inscripcion-id="{{ $est['inscripcion_id'] }}"
                                     data-reprobados="{{ $reprobados }}">
                                    <button type="button"
                                            class="ins-estado-toggle {{ $acadActivo ? 'on' : 'off' }} aa-academico-toggle"
                                            data-inscripcion-id="{{ $est['inscripcion_id'] }}"
                                            data-campo="activo_academico"
                                            data-valor="{{ $acadActivo ? 1 : 0 }}"
                                            title="Estado académico: {{ $acadActivo ? 'Activo' : 'Inactivo' }} — click para cambiar">
                                        <span class="ins-estado-toggle-track"><span class="ins-estado-toggle-knob"></span></span>
                                        <span class="ins-estado-toggle-label">{{ $acadActivo ? 'Activo' : 'Baja' }}</span>
                                    </button>
                                    @if ($sugAcad === 'desactivar')
                                        <button type="button"
                                                class="aa-acad-sug aa-acad-sug--down aa-acad-sug-btn"
                                                data-inscripcion-id="{{ $est['inscripcion_id'] }}"
                                                data-estudiante="{{ $nombreCompleto }}"
                                                title="{{ $reprobados }} módulo(s) reprobado(s) — ver detalle">
                                            <i class="ri-error-warning-line"></i> Sugerir baja
                                            <i class="ri-eye-line aa-acad-sug-eye"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                            @foreach ($oferta->modulos as $mod)
                                @php
                                    $estMod = $mod->estado ?: 'No Inició';
                                    $bloqueado = $estMod !== 'Concluido';
                                    $matri = $notasMatriculaciones[$est['inscripcion_id']][$mod->id] ?? null;
                                    $notaMR = $matri['nota_regular'] ?? null;
                                    $notaMN = $matri['nota_nivelacion'] ?? null;
                                    $nivelacionHab = (bool) ($matri['nivelacion_habilitada'] ?? false);
                                    $notaMinAA = (float) ($oferta->nota_minima ?? 0);

                                    // Reglas:
                                    // - nota_regular ≥ mínima → verde y 2da NO se habilita
                                    // - nota_regular <  mínima → roja; 2da solo aparece si nivelacion_habilitada
                                    // - nota_nivelacion ≥ mínima → verde
                                    $aprobadoRegular = ($notaMR !== null && $notaMR >= $notaMinAA);
                                    $clsRegular  = $notaMR === null ? '' : ($aprobadoRegular ? 'aa-nota-final-cargada' : 'aa-nota-final-fail');
                                    $puedeNivelar = ($notaMR !== null && !$aprobadoRegular);
                                    $muestra2da   = $puedeNivelar && $nivelacionHab;
                                    $cls2da       = $notaMN === null ? '' : ($notaMN >= $notaMinAA ? 'aa-nota-final-cargada' : 'aa-nota-final-fail');

                                    $bgFinal = '';
                                    if (!$bloqueado && $notaMR !== null) {
                                        $bgFinal = $aprobadoRegular ? 'aa-sub--ok' : 'aa-sub--fail';
                                    }
                                    $bg2da = '';
                                    if ($muestra2da && $notaMN !== null) {
                                        $bg2da = ($notaMN >= $notaMinAA) ? 'aa-sub--ok' : 'aa-sub--fail';
                                    }
                                    $estudianteNombre = trim(($est['apellido_paterno'] ?? '') . ' ' . ($est['apellido_materno'] ?? '') . ' ' . ($est['nombres'] ?? ''));
                                @endphp
                                <td class="aa-mod-cell {{ $bloqueado ? 'aa-mod-cell-blocked' : '' }}"
                                    data-modulo-id="{{ $mod->id }}"
                                    data-modulo-estado="{{ $estMod }}"
                                    data-carnet="{{ $est['carnet'] }}">
                                    <div class="aa-notas-grid {{ $muestra2da ? '' : 'aa-notas-grid--solo' }}">
                                        {{-- Final --}}
                                        <div class="aa-sub aa-sub-final {{ $bgFinal }}">
                                            @if ($bloqueado || $notaMR === null)
                                                <span class="aa-nota aa-nota-empty"></span>
                                            @else
                                                <span class="aa-nota aa-nota-final {{ $clsRegular }}" data-tipo="final" title="Nota Final (regular). Mínima: {{ number_format($notaMinAA, 2) }}">{{ number_format($notaMR, 2) }}</span>
                                            @endif
                                        </div>
                                        {{-- 2da Inst. (solo cuando nivelacion_habilitada) --}}
                                        @if ($muestra2da)
                                            <div class="aa-sub aa-sub-2da {{ $bg2da }}">
                                                @if ($notaMN !== null)
                                                    <span class="aa-nota aa-nota-2da {{ $cls2da }}"
                                                          title="2da Instancia: {{ number_format($notaMN, 2) }}">{{ number_format($notaMN, 2) }}</span>
                                                @else
                                                    <span class="aa-nota aa-nota-empty"></span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="aa-legend">
            <span><i class="ri-information-line"></i> Las notas (Final / 2da Instancia) sólo se cargan en módulos cuyo estado es <strong>Concluido</strong>. Las notas ≥ 71 se marcan en verde.</span>
        </div>
    @endif

    <style>
        /* Tinte de fila según estado académico */
        .aa-row--acad-on  > td { background-color: rgba(16, 185, 129, 0.07) !important; }
        .aa-row--acad-on:hover > td { background-color: rgba(16, 185, 129, 0.12) !important; }
        .aa-row--acad-off > td { background-color: rgba(220, 38, 38, 0.07) !important; }
        .aa-row--acad-off:hover > td { background-color: rgba(220, 38, 38, 0.12) !important; }
        .aa-row--acad-on > td, .aa-row--acad-off > td { transition: background-color .25s ease; }

        .aa-academico-cell { display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .aa-acad-sug {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: .66rem; font-weight: 700;
            padding: 2px 8px; border-radius: 999px;
            cursor: pointer; white-space: nowrap;
            font-family: 'Sora','DM Sans',sans-serif;
            animation: aaAcadSugPulse 2.4s ease-in-out infinite;
            border: 1px solid transparent;
            transition: transform .15s ease, filter .15s ease;
        }
        .aa-acad-sug i { font-size: .82rem; }
        .aa-acad-sug--down {
            background: rgba(220,38,38,.10);
            color: #b91c1c;
            border-color: rgba(220,38,38,.28);
        }
        .aa-acad-sug-btn:hover { transform: translateY(-1px); filter: brightness(1.05); }
        .aa-acad-sug-eye { opacity: .65; margin-left: 2px; }
        @keyframes aaAcadSugPulse {
            0%, 100% { opacity: .9; }
            50%      { opacity: .55; }
        }
    </style>

    {{-- ═══════════════ Modal Detalle Estado Académico ═══════════════ --}}
    <div class="modal fade" id="modalAcademicoDetalle" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18);">
                <div class="modal-header" id="modalAcademicoHeader" style="background:linear-gradient(135deg,#7f1d1d 0%,#b91c1c 50%,#dc2626 100%);color:#fff;padding:1.1rem 1.5rem;border:none;">
                    <div class="d-flex align-items-center gap-3" style="flex:1;">
                        <div style="width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i id="modalAcademicoHeaderIcon" class="ri-error-warning-line" style="font-size:1.4rem;"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <h5 class="modal-title mb-0" id="modalAcademicoTitulo" style="font-size:1rem;font-weight:700;letter-spacing:-.01em;color:#fff;">Sugerencia de estado académico</h5>
                            <div id="modalAcademicoSubtitulo" style="font-size:.73rem;opacity:.82;margin-top:.15rem;color:rgba(255,255,255,.9);">—</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="padding:0;">
                    <div id="modalAcademicoBanner" class="aa-mc-banner"></div>

                    <div class="aa-mc-section">
                        <div class="aa-mc-section-title">
                            <i class="ri-error-warning-fill"></i> Módulos reprobados
                            <span class="aa-mc-min" id="modalAcademicoNotaMinima"></span>
                        </div>
                        <div class="table-responsive">
                            <table class="table aa-mc-tbl mb-0">
                                <thead>
                                    <tr>
                                        <th>Módulo</th>
                                        <th class="text-center">Nota Regular</th>
                                        <th class="text-center">2da Instancia</th>
                                        <th class="text-center">Mínima</th>
                                        <th class="text-center">Diferencia</th>
                                    </tr>
                                </thead>
                                <tbody id="modalAcademicoTbodyReprobados"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:.85rem 1.25rem;justify-content:space-between;background:#f8fafc;">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal" style="border:1px solid #cbd5e1;font-weight:600;">
                        <i class="ri-close-line"></i> Cerrar
                    </button>
                    <button type="button" class="btn btn-sm" id="modalAcademicoAccion" style="display:none;font-weight:700;color:#fff;border:none;padding:.45rem 1.1rem;border-radius:8px;background:linear-gradient(135deg,#b91c1c,#dc2626);">
                        <i class="ri-user-unfollow-line"></i> Dar de baja académica
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .aa-mc-banner { padding: 12px 18px; font-size: .82rem; font-weight: 600; display: flex; gap: 10px; align-items: center; border-bottom: 1px solid #e2e8f0; background: rgba(220,38,38,.07); color: #991b1b; }
        .aa-mc-banner i { font-size: 1.1rem; }
        .aa-mc-section { padding: 14px 18px; }
        .aa-mc-section-title { display: flex; align-items: center; gap: 6px; font-family: 'Sora','DM Sans',sans-serif; font-weight: 700; font-size: .82rem; color: #b91c1c; margin-bottom: 8px; }
        .aa-mc-min { margin-left: auto; font-size: .7rem; font-weight: 600; color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 999px; }
        .aa-mc-tbl thead th { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #64748b; background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 8px 10px; }
        .aa-mc-tbl tbody td { font-size: .78rem; color: #1e293b; padding: 8px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .aa-mc-tbl tbody tr:last-child td { border-bottom: none; }
        .aa-mc-nota { display: inline-block; padding: 2px 9px; border-radius: 999px; font-weight: 700; font-size: .72rem; }
        .aa-mc-nota.fail { background: rgba(220,38,38,.12); color: #b91c1c; }
        .aa-mc-nota.empty { background: #f1f5f9; color: #94a3b8; }
        .aa-mc-diff { font-weight: 700; color: #b91c1c; font-family: 'Sora','DM Sans',sans-serif; }
    </style>

    {{-- Modal de Estudios Académicos --}}
    <div class="modal fade" id="modalEstudiosEstudiante" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:560px;">
            <div class="modal-content">
                <div class="modal-header modal-header-gradient">
                    <h5 class="modal-title">
                        <i class="ri-graduation-cap-line"></i>
                        Estudios Académicos
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="aa-modal-estudiante-info">
                        <div class="aa-modal-est-name" id="modalEstudiosNombre">—</div>
                        <div class="aa-modal-est-ci"><i class="ri-id-card-line"></i> <span id="modalEstudiosCarnet">—</span></div>
                    </div>
                    <div id="modalEstudiosLista" class="aa-modal-estudios-list"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
