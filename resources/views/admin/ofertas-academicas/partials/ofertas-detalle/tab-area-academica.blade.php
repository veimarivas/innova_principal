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
                        @foreach ($oferta->modulos as $mod)
                            @php
                                $estMod = $mod->estado ?: 'No Inició';
                                $st = $aaEstadoStyles[$estMod] ?? $aaEstadoStyles['No Inició'];
                            @endphp
                            <th class="aa-mod-col" data-modulo-id="{{ $mod->id }}" data-modulo-estado="{{ $estMod }}">
                                <div class="aa-mod-col-name" title="{{ $mod->nombre }}">
                                    <i class="ri-book-2-line"></i>
                                    <span>{{ $mod->nombre }}</span>
                                </div>
                                <div class="aa-mod-col-estado">
                                    <span class="aa-mod-estado-chip" style="background:{{ $st['bg'] }};color:{{ $st['color'] }};">
                                        <i class="{{ $st['icon'] }}"></i> {{ $estMod }}
                                    </span>
                                </div>
                                <div class="aa-mod-col-sub">
                                    <span class="aa-mod-col-sub-label">Final</span>
                                    <span class="aa-mod-col-sub-label">2da Inst.</span>
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
                        <tr data-carnet="{{ $est['carnet'] }}">
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
                            @foreach ($oferta->modulos as $mod)
                                @php
                                    $estMod = $mod->estado ?: 'No Inició';
                                    $bloqueado = $estMod !== 'Concluido';
                                @endphp
                                <td class="aa-mod-cell {{ $bloqueado ? 'aa-mod-cell-blocked' : '' }}"
                                    data-modulo-id="{{ $mod->id }}"
                                    data-modulo-estado="{{ $estMod }}"
                                    data-carnet="{{ $est['carnet'] }}">
                                    @if ($bloqueado)
                                        <div class="aa-notas-pair">
                                            <span class="aa-nota aa-nota-blocked" title="Disponible cuando el módulo esté Concluido">—</span>
                                            <span class="aa-nota-sep">/</span>
                                            <span class="aa-nota aa-nota-blocked" title="Disponible cuando el módulo esté Concluido">—</span>
                                        </div>
                                    @else
                                        <div class="aa-notas-pair">
                                            <span class="aa-nota aa-nota-final" data-tipo="final" title="Nota Final">—</span>
                                            <span class="aa-nota-sep">/</span>
                                            <span class="aa-nota aa-nota-2da" data-tipo="2da" title="Nota 2da Instancia">—</span>
                                        </div>
                                    @endif
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
