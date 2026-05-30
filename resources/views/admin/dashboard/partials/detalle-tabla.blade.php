<div class="ins-list-header">
    <span>#</span>
    <span>Estudiante / Programa</span>
    <span>Estado · Fecha</span>
</div>

<div class="ins-list" id="ins-list-body">
    @forelse ($inscripciones as $ins)
        @php
            $nombre  = trim(($ins->estudiante->persona->nombres ?? '') . ' ' . ($ins->estudiante->persona->apellido_paterno ?? ''));
            $inicial = mb_strtoupper(mb_substr($ins->estudiante->persona->nombres ?? 'E', 0, 1));
            $programa = $ins->ofertaAcademica->posgrado->nombre ?? '—';
            $sede     = $ins->ofertaAcademica->sucursal->nombre ?? '—';
            $tipo     = $ins->ofertaAcademica->posgrado->tipo->nombre ?? null;
            $ofertaId = $ins->ofertaAcademica->id ?? null;
        @endphp
        <div class="ins-row">
            {{-- índice --}}
            <div class="ins-idx">{{ $loop->iteration }}</div>

            {{-- bloque central --}}
            <div class="ins-main">
                <div class="ins-top">
                    <div class="student-avatar">{{ $inicial }}</div>
                    <a href="{{ route('admin.estudiantes.verDetalle', $ins->estudiante->id) }}"
                       class="student-name-link">
                        {{ $nombre }}
                    </a>
                    @if ($tipo)
                        <span class="tipo-pill">{{ $tipo }}</span>
                    @endif
                </div>
                <div class="ins-bottom">
                    @if ($ofertaId)
                        <a href="{{ route('admin.posgrads.ofertas.detalle', $ofertaId) }}"
                           class="program-link" title="{{ $programa }}">
                            <i class="ri-graduation-cap-line" style="font-size:0.75rem;margin-right:3px;"></i>{{ $programa }}
                        </a>
                    @else
                        <span class="program-link" style="color:var(--dash-text-muted);cursor:default;">{{ $programa }}</span>
                    @endif
                    <span class="ins-sep">·</span>
                    <span class="sede-name">
                        <i class="ri-map-pin-line"></i>{{ $sede }}
                    </span>
                </div>
            </div>

            {{-- bloque derecho --}}
            <div class="ins-meta">
                <div class="ins-meta-top">
                    @if ($ins->estado === 'Inscrito')
                        <span class="estado-badge estado-inscrito">
                            <i class="ri-checkbox-circle-line"></i> Inscrito
                        </span>
                    @else
                        <span class="estado-badge estado-preinscrito">
                            <i class="ri-time-line"></i> Pre-Inscrito
                        </span>
                    @endif
                </div>
                <span class="fecha-text">
                    <i class="ri-calendar-event-line"></i>
                    {{ \Carbon\Carbon::parse($ins->fecha_registro)->format('d/m/Y') }}
                </span>
            </div>
        </div>
    @empty
        <div style="padding: 56px 24px; text-align: center;">
            <i class="ri-inbox-line" style="font-size:3.2rem;color:#cbd5e1;display:block;margin-bottom:12px;"></i>
            <p style="font-weight:600;color:#64748b;margin-bottom:4px;">Sin resultados</p>
            <p style="color:#94a3b8;font-size:.88rem;margin:0;">No hay inscripciones para los filtros aplicados.</p>
        </div>
    @endforelse
</div>
