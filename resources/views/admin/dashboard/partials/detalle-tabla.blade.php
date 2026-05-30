<div class="table-responsive">
    <table class="dash-table">
        <thead>
            <tr>
                <th class="row-num">#</th>
                <th>Estudiante</th>
                <th>Programa</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($inscripciones as $ins)
                @php
                    $nombre  = trim(($ins->estudiante->persona->nombres ?? '') . ' ' . ($ins->estudiante->persona->apellido_paterno ?? ''));
                    $inicial = mb_strtoupper(mb_substr($ins->estudiante->persona->nombres ?? 'E', 0, 1));
                @endphp
                <tr>
                    <td class="row-num">{{ $loop->iteration }}</td>
                    <td>
                        <div class="student-cell">
                            <div class="student-avatar">{{ $inicial }}</div>
                            <a href="{{ route('admin.estudiantes.verDetalle', $ins->estudiante->id) }}"
                               class="student-name-link">
                                {{ $nombre }}
                            </a>
                        </div>
                    </td>
                    <td class="program-cell">
                        <span class="program-name">{{ $ins->ofertaAcademica->posgrado->nombre ?? '—' }}</span>
                        <span class="sede-name">
                            <i class="ri-map-pin-line"></i>
                            {{ $ins->ofertaAcademica->sucursal->nombre ?? '—' }}
                        </span>
                    </td>
                    <td>
                        <span class="tipo-pill">
                            {{ $ins->ofertaAcademica->posgrado->tipo->nombre ?? '—' }}
                        </span>
                    </td>
                    <td>
                        @if ($ins->estado === 'Inscrito')
                            <span class="estado-badge estado-inscrito">
                                <i class="ri-checkbox-circle-line"></i> Inscrito
                            </span>
                        @else
                            <span class="estado-badge estado-preinscrito">
                                <i class="ri-time-line"></i> Pre-Inscrito
                            </span>
                        @endif
                    </td>
                    <td>
                        <span class="fecha-text">
                            <i class="ri-calendar-event-line me-1"></i>
                            {{ \Carbon\Carbon::parse($ins->fecha_registro)->format('d/m/Y') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="ri-inbox-line"></i>
                            <h6>Sin resultados</h6>
                            <p>No hay inscripciones para los filtros aplicados.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
