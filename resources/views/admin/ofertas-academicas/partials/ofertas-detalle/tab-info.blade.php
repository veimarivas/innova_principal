<div class="tab-content-section active" id="tab-info">
<div class="tgi-wrap">

    @php
        $hoy = \Carbon\Carbon::now();
        $inicioP = $oferta->fecha_inicio_programa;
        $finP    = $oferta->fecha_fin_programa;

        if ($finP && $hoy->gt($finP)) {
            $estadoLabel = 'Finalizado';
            $estadoClass = 'tgi-estado-fin';
        } elseif ($inicioP && $hoy->lt($inicioP)) {
            $estadoLabel = 'Por iniciar';
            $estadoClass = 'tgi-estado-prox';
        } else {
            $estadoLabel = 'En curso';
            $estadoClass = 'tgi-estado-activo';
        }

        $duracionDias = ($inicioP && $finP) ? $inicioP->diffInDays($finP) : null;
        $nombrePrograma = $oferta->programa->nombre ?? ($oferta->posgrado->nombre ?? null);

        $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
        $fmtFecha = function($date) use ($meses) {
            if (!$date) return null;
            $d = \Carbon\Carbon::parse($date);
            return $d->day . ' de ' . $meses[$d->month - 1] . ' de ' . $d->year;
        };
    @endphp

    {{-- ── BANNER ── --}}
    <div class="tgi-banner" style="--tgi-brand: {{ $brandColor }}; border-top: 4px solid {{ $brandColor }};">
        <div class="tgi-banner-left">
            <div class="tgi-banner-icon" style="background: rgba({{ $brandColorRgb }},.12); color: {{ $brandColor }};">
                <i class="ri-graduation-cap-line"></i>
            </div>
            <div class="tgi-banner-text">
                <div class="tgi-banner-code">{{ $oferta->codigo }}</div>
                @if($nombrePrograma)
                    <div class="tgi-banner-name">{{ $nombrePrograma }}</div>
                @endif
                <div class="tgi-banner-pills">
                    @if($oferta->sucursal)
                        <span class="tgi-pill tgi-pill-gray"><i class="ri-map-pin-line"></i> {{ $oferta->sucursal->nombre }}</span>
                    @endif
                    @if($oferta->modalidad)
                        <span class="tgi-pill tgi-pill-gray"><i class="ri-wifi-line"></i> {{ $oferta->modalidad->nombre }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="tgi-banner-right">
            <span class="tgi-estado {{ $estadoClass }}">
                <span class="tgi-estado-dot"></span>
                {{ $estadoLabel }}
            </span>
            @if($duracionDias)
                <div class="tgi-duracion">
                    <i class="ri-time-line"></i>
                    {{ $duracionDias }} días de programa
                </div>
            @endif
        </div>
    </div>

    {{-- ── KPIs ── --}}
    <div class="tgi-kpi-row">
        <div class="tgi-kpi">
            <div class="tgi-kpi-ico" style="background:rgba(59,130,246,.1);color:#2563eb;"><i class="ri-calendar-check-line"></i></div>
            <div class="tgi-kpi-body">
                <div class="tgi-kpi-lbl">Gestión</div>
                <div class="tgi-kpi-val">{{ $oferta->gestion }}</div>
            </div>
        </div>
        <div class="tgi-kpi">
            <div class="tgi-kpi-ico" style="background:rgba(245,158,11,.1);color:#d97706;"><i class="ri-git-branch-line"></i></div>
            <div class="tgi-kpi-body">
                <div class="tgi-kpi-lbl">Versión / Grupo</div>
                <div class="tgi-kpi-val">v{{ $oferta->version }} — G{{ $oferta->grupo }}</div>
            </div>
        </div>
        <div class="tgi-kpi">
            <div class="tgi-kpi-ico" style="background:rgba(139,92,246,.1);color:#7c3aed;"><i class="ri-building-line"></i></div>
            <div class="tgi-kpi-body">
                <div class="tgi-kpi-lbl">Sucursal</div>
                <div class="tgi-kpi-val">{{ $oferta->sucursal->nombre ?? '—' }}</div>
            </div>
        </div>
        <div class="tgi-kpi">
            <div class="tgi-kpi-ico" style="background:rgba(var(--brand-color-rgb),.1);color:{{ $brandColor }};"><i class="ri-route-line"></i></div>
            <div class="tgi-kpi-body">
                <div class="tgi-kpi-lbl">Fase actual</div>
                <div class="tgi-kpi-val">{{ $oferta->fase->nombre ?? '—' }}</div>
            </div>
        </div>
    </div>

    {{-- ── GRID SECUNDARIO ── --}}
    <div class="tgi-grid">

        {{-- Columna izquierda --}}
        <div class="tgi-col">

            {{-- Calendario --}}
            <div class="tgi-card">
                <div class="tgi-card-hdr">
                    <div class="tgi-card-hdr-icon" style="background:rgba(var(--brand-color-rgb),.1);color:{{ $brandColor }};"><i class="ri-calendar-event-line"></i></div>
                    <span>Calendario del Programa</span>
                </div>
                <div class="tgi-timeline">

                    <div class="tgi-tl-node">
                        <div class="tgi-tl-dot tgi-tl-dot-green">
                            <i class="ri-door-open-line"></i>
                        </div>
                        <div class="tgi-tl-connector"></div>
                        <div class="tgi-tl-content">
                            <div class="tgi-tl-label">Apertura de inscripciones</div>
                            @php $fInsc = $fmtFecha($oferta->fecha_inicio_inscripciones); @endphp
                            <div class="tgi-tl-date {{ $fInsc ? '' : 'tgi-tl-date-empty' }}">
                                {{ $fInsc ?? 'No definida' }}
                            </div>
                        </div>
                    </div>

                    <div class="tgi-tl-node">
                        <div class="tgi-tl-dot tgi-tl-dot-orange">
                            <i class="ri-play-circle-line"></i>
                        </div>
                        <div class="tgi-tl-connector"></div>
                        <div class="tgi-tl-content">
                            <div class="tgi-tl-label">Inicio de clases</div>
                            @php $fIni = $fmtFecha($inicioP); @endphp
                            <div class="tgi-tl-date {{ $fIni ? '' : 'tgi-tl-date-empty' }}">
                                {{ $fIni ?? 'No definido' }}
                            </div>
                        </div>
                    </div>

                    <div class="tgi-tl-node tgi-tl-last">
                        <div class="tgi-tl-dot tgi-tl-dot-red">
                            <i class="ri-flag-line"></i>
                        </div>
                        <div class="tgi-tl-content">
                            <div class="tgi-tl-label">Cierre del programa</div>
                            @php $fFin = $fmtFecha($finP); @endphp
                            <div class="tgi-tl-date {{ $fFin ? '' : 'tgi-tl-date-empty' }}">
                                {{ $fFin ?? 'No definido' }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Configuración Académica --}}
            <div class="tgi-card tgi-card-mt">
                <div class="tgi-card-hdr">
                    <div class="tgi-card-hdr-icon" style="background:rgba(99,102,241,.1);color:#6366f1;"><i class="ri-settings-3-line"></i></div>
                    <span>Configuración Académica</span>
                </div>
                <div class="tgi-acad-grid">
                    <div class="tgi-acad-stat">
                        <div class="tgi-acad-ico" style="background:rgba(99,102,241,.1);color:#6366f1;"><i class="ri-layout-grid-line"></i></div>
                        <div class="tgi-acad-num">{{ $oferta->n_modulos }}</div>
                        <div class="tgi-acad-lbl">Módulos</div>
                    </div>
                    <div class="tgi-acad-stat">
                        <div class="tgi-acad-ico" style="background:rgba(20,184,166,.1);color:#0d9488;"><i class="ri-slideshow-line"></i></div>
                        <div class="tgi-acad-num">{{ $oferta->cantidad_sesiones }}</div>
                        <div class="tgi-acad-lbl">Sesiones</div>
                    </div>
                    <div class="tgi-acad-stat">
                        <div class="tgi-nota-circle" style="background:{{ $brandColor }};box-shadow:0 4px 14px rgba({{ $brandColorRgb }},.4);">
                            <span class="tgi-nota-val" style="color:{{ $brandContrastColor }};">{{ $oferta->nota_minima }}</span>
                            <span class="tgi-nota-sub" style="color:{{ $brandContrastColor }};">pts</span>
                        </div>
                        <div class="tgi-acad-lbl" style="margin-top:.6rem;">Nota mínima</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Columna derecha --}}
        <div class="tgi-col">

            {{-- Equipo responsable --}}
            <div class="tgi-card">
                <div class="tgi-card-hdr">
                    <div class="tgi-card-hdr-icon" style="background:rgba(var(--brand-color-rgb),.1);color:{{ $brandColor }};"><i class="ri-team-line"></i></div>
                    <span>Equipo Responsable</span>
                    <button type="button" class="tgi-edit-btn ms-auto"
                        data-bs-toggle="modal" data-bs-target="#modalEditarResponsables"
                        title="Editar responsables">
                        <i class="ri-pencil-line"></i> Editar
                    </button>
                </div>
                <div class="tgi-responsables">

                    @if($respAcademico)
                        <div class="tgi-resp">
                            <div class="tgi-resp-avatar" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);">
                                {{ strtoupper(substr($respAcademico, 0, 1)) }}
                            </div>
                            <div class="tgi-resp-info">
                                <div class="tgi-resp-rol">Coordinador Académico</div>
                                <div class="tgi-resp-nombre">{{ $respAcademico }}</div>
                            </div>
                            <div class="tgi-resp-tag" style="background:rgba(59,130,246,.1);color:#2563eb;">
                                <i class="ri-book-open-line"></i> Académico
                            </div>
                        </div>
                    @else
                        <div class="tgi-empty-resp">
                            <i class="ri-user-unfollow-line"></i>
                            <span>Coordinador académico sin asignar</span>
                        </div>
                    @endif

                    @if($respMarketing)
                        <div class="tgi-resp">
                            <div class="tgi-resp-avatar" style="background:linear-gradient(135deg,{{ $brandColor }},#c96004);">
                                {{ strtoupper(substr($respMarketing, 0, 1)) }}
                            </div>
                            <div class="tgi-resp-info">
                                <div class="tgi-resp-rol">Coordinador de Marketing</div>
                                <div class="tgi-resp-nombre">{{ $respMarketing }}</div>
                            </div>
                            <div class="tgi-resp-tag" style="background:rgba(var(--brand-color-rgb),.1);color:{{ $brandColor }};">
                                <i class="ri-megaphone-line"></i> Marketing
                            </div>
                        </div>
                    @else
                        <div class="tgi-empty-resp">
                            <i class="ri-user-unfollow-line"></i>
                            <span>Coordinador de marketing sin asignar</span>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Documentos --}}
            <div class="tgi-card tgi-card-mt">
                <div class="tgi-card-hdr">
                    <div class="tgi-card-hdr-icon" style="background:rgba(var(--brand-color-rgb),.1);color:{{ $brandColor }};"><i class="ri-folder-open-line"></i></div>
                    <span>Documentos Adjuntos</span>
                    <button type="button" class="tgi-edit-btn ms-auto"
                        data-bs-toggle="modal" data-bs-target="#modalEditarDocumentos"
                        title="Gestionar ambos documentos">
                        <i class="ri-upload-2-line"></i> Gestionar
                    </button>
                </div>
                <div class="tgi-docs">

                    {{-- Portada --}}
                    @if($oferta->portada)
                        <div class="tgi-doc">
                            <div class="tgi-doc-thumb">
                                @if(preg_match('/\.(jpe?g|png|gif|webp)$/i', $oferta->portada))
                                    <img src="{{ asset('storage/' . $oferta->portada) }}" alt="Portada">
                                @else
                                    <i class="ri-image-line"></i>
                                @endif
                            </div>
                            <div class="tgi-doc-info">
                                <div class="tgi-doc-name">Imagen de portada</div>
                                <div class="tgi-doc-sub">Archivo adjunto</div>
                                <a href="{{ asset('storage/' . $oferta->portada) }}" target="_blank" class="tgi-doc-link">
                                    <i class="ri-download-2-line"></i> Descargar
                                </a>
                            </div>
                            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.35rem;">
                                <i class="ri-checkbox-circle-fill tgi-doc-ok"></i>
                                <button type="button" class="tgi-edit-btn"
                                    onclick="abrirModalDocSolo('portada')"
                                    title="Reemplazar portada"
                                    style="font-size:.7rem;padding:.2rem .55rem;">
                                    <i class="ri-edit-line"></i> Editar
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="tgi-doc-empty" style="cursor:pointer;" onclick="abrirModalDocSolo('portada')" title="Subir portada">
                            <i class="ri-image-add-line"></i>
                            <span>Sin portada — <u>Agregar</u></span>
                        </div>
                    @endif

                    {{-- Certificado --}}
                    @if($oferta->certificado)
                        <div class="tgi-doc">
                            <div class="tgi-doc-thumb tgi-doc-thumb-pdf">
                                @if(preg_match('/\.(jpe?g|png|gif|webp)$/i', $oferta->certificado))
                                    <img src="{{ asset('storage/' . $oferta->certificado) }}" alt="Certificado">
                                @else
                                    <i class="ri-file-pdf-line" style="color:#ef4444;"></i>
                                @endif
                            </div>
                            <div class="tgi-doc-info">
                                <div class="tgi-doc-name">Certificado base</div>
                                <div class="tgi-doc-sub">Plantilla oficial</div>
                                <a href="{{ asset('storage/' . $oferta->certificado) }}" target="_blank" class="tgi-doc-link">
                                    <i class="ri-download-2-line"></i> Descargar
                                </a>
                            </div>
                            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.35rem;">
                                <i class="ri-checkbox-circle-fill tgi-doc-ok"></i>
                                <button type="button" class="tgi-edit-btn"
                                    onclick="abrirModalDocSolo('certificado')"
                                    title="Reemplazar certificado"
                                    style="font-size:.7rem;padding:.2rem .55rem;">
                                    <i class="ri-edit-line"></i> Editar
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="tgi-doc-empty" style="cursor:pointer;" onclick="abrirModalDocSolo('certificado')" title="Subir certificado">
                            <i class="ri-file-text-line"></i>
                            <span>Sin certificado — <u>Agregar</u></span>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

</div>
</div>
