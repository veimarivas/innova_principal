@php
    $persona = $user->persona;
    $estudioPrincipal = $persona->estudios->where('principal', 1)->first();

    $estadoDoc = function ($archivo, $verificado) {
        if (!$archivo)     return ['label' => 'No subido',  'cls' => 'danger',  'icon' => 'ri-close-circle-line'];
        if ($verificado)   return ['label' => 'Verificado', 'cls' => 'success', 'icon' => 'ri-checkbox-circle-line'];
        return              ['label' => 'Sin verificar','cls' => 'warning', 'icon' => 'ri-time-line'];
    };

    $docs = [
        [
            'grupo'      => 'Documentos Personales',
            'nombre'    => 'Carnet de Identidad',
            'icono'      => 'ri-id-card-line',
            'archivo'    => $persona->fotografia_carnet,
            'verificado' => $persona->carnet_verificado,
            'filename'   => 'carnet.pdf',
            'fecha'     => $persona->updated_at,
            'url'       => $persona->fotografia_carnet ? asset('storage/' . $persona->fotografia_carnet) : null,
            'tipo'      => 'carnet',
        ],
        [
            'grupo'      => 'Documentos Personales',
            'nombre'    => 'Certificado de Nacimiento',
            'icono'      => 'ri-file-paper-line',
            'archivo'    => $persona->fotografia_certificado_nacimiento,
            'verificado' => $persona->certificado_nacimiento_verificado,
            'filename'  => 'certificado_nacimiento.pdf',
            'fecha'    => $persona->updated_at,
            'url'      => $persona->fotografia_certificado_nacimiento ? asset('storage/' . $persona->fotografia_certificado_nacimiento) : null,
            'tipo'      => 'certificado_nacimiento',
        ],
        [
            'grupo'      => 'Documentos Académicos',
            'nombre'    => 'Documento Académico',
            'icono'      => 'ri-graduation-cap-line',
            'archivo'    => $estudioPrincipal?->documento_academico,
            'verificado' => $estudioPrincipal?->documento_academico_verificado,
            'filename'  => 'documento_academico.pdf',
            'fecha'    => $estudioPrincipal?->updated_at,
            'url'      => $estudioPrincipal?->documento_academico ? asset('storage/' . $estudioPrincipal->documento_academico) : null,
            'tipo'      => 'documento_academico',
            'sin_estudio'=> !$estudioPrincipal,
        ],
        [
            'grupo'      => 'Documentos Académicos',
            'nombre'    => 'Provisión Nacional',
            'icono'      => 'ri-government-line',
            'archivo'    => $estudioPrincipal?->documento_provision_nacional,
            'verificado' => $estudioPrincipal?->documento_provision_verificado,
            'filename'  => 'provision_nacional.pdf',
            'fecha'    => $estudioPrincipal?->updated_at,
            'url'      => $estudioPrincipal?->documento_provision_nacional ? asset('storage/' . $estudioPrincipal->documento_provision_nacional) : null,
            'tipo'      => 'provision_nacional',
            'sin_estudio'=> !$estudioPrincipal,
        ],
    ];

    $totalDocs    = 0;
    $verificados  = 0;
    $sinVerificar = 0;
    $noSubidos   = 0;

    foreach ($docs as $d) {
        if ($d['sin_estudio'] ?? false) continue;
        $totalDocs++;
        if (!$d['archivo'])      { $noSubidos++; }
        elseif ($d['verificado']) { $verificados++; }
        else                   { $sinVerificar++; }
    }

    $pctDocs  = $totalDocs > 0 ? ($verificados / $totalDocs) * 100 : 0;
    $colorBar = match(true) {
        $pctDocs == 100 => 'success',
        $pctDocs >= 50  => 'warning',
        default         => 'danger',
    };
@endphp

<div class="documents-tab">
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="docs-stat-card">
                <div class="docs-stat-value">{{ $totalDocs }}</div>
                <div class="docs-stat-label">Total</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="docs-stat-card verified">
                <div class="docs-stat-value">{{ $verificados }}</div>
                <div class="docs-stat-label">Verificados</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="docs-stat-card pending">
                <div class="docs-stat-value">{{ $sinVerificar }}</div>
                <div class="docs-stat-label">Sin verificar</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="docs-stat-card missing">
                <div class="docs-stat-value">{{ $noSubidos }}</div>
                <div class="docs-stat-label">No subidos</div>
            </div>
        </div>
    </div>

    <div class="docs-progress mb-4">
        <div class="docs-progress-header">
            <span>Progreso de documentación</span>
            <span class="docs-progress-badge bg-{{ $colorBar }}">{{ number_format($pctDocs, 0) }}% completado</span>
        </div>
        <div class="docs-progress-bar">
            <div class="docs-progress-fill bg-{{ $colorBar }}" style="width: {{ $pctDocs }}%"></div>
        </div>
    </div>

    <div class="row g-3">
        @foreach ($docs as $doc)
            @php
                $sinEstudio = $doc['sin_estudio'] ?? false;
                $estado = $sinEstudio 
                    ? ['label' => 'Sin estudio', 'cls' => 'secondary', 'icon' => 'ri-question-line']
                    : $estadoDoc($doc['archivo'], $doc['verificado']);
                
                $colorLeft = match($estado['cls']) {
                    'success' => '#198754',
                    'warning' => '#ffc107',
                    'danger' => '#dc3545',
                    default  => '#6c757d',
                };
            @endphp

            <div class="col-md-6">
                <div class="doc-card" style="border-left: 4px solid {{ $colorLeft }};">
                    <div class="doc-card-header">
                        <div class="doc-icon bg-{{ $estado['cls'] }}-subtle">
                            <i class="{{ $doc['icono'] }}"></i>
                        </div>
                        <div class="doc-info">
                            <div class="doc-name">{{ $doc['nombre'] }}</div>
                            <div class="doc-group">{{ $doc['grupo'] }}</div>
                        </div>
                        <div class="doc-status badge bg-{{ $estado['cls'] }}-subtle text-{{ $estado['cls'] }}">
                            <i class="{{ $estado['icon'] }}"></i>{{ $estado['label'] }}
                        </div>
                    </div>
                    <div class="doc-card-body">
                        @if ($sinEstudio)
                            <div class="doc-empty">
                                <i class="ri-error-warning-line"></i>
                                <p>Sin estudio principal registrado</p>
                            </div>
                        @elseif ($doc['archivo'])
                            <div class="doc-file">
                                <i class="ri-file-pdf-line"></i>
                                <div class="doc-file-info">
                                    <div class="doc-filename">{{ $doc['filename'] }}</div>
                                    <div class="doc-filedate">
                                        <i class="ri-calendar-line"></i>
                                        {{ $doc['fecha'] ? \Carbon\Carbon::parse($doc['fecha'])->format('d/m/Y H:i') : '—' }}
                                    </div>
                                </div>
                                @if ($doc['verificado'])
                                    <i class="ri-shield-check-line verified"></i>
                                @else
                                    <i class="ri-shield-line pending"></i>
                                @endif
                            </div>
                            <div class="doc-actions">
                                <button type="button" class="btn-doc preview-doc" data-url="{{ $doc['url'] }}">
                                    <i class="ri-eye-line"></i>Ver
                                </button>
                                <a href="{{ $doc['url'] }}" class="btn-doc download" download>
                                    <i class="ri-download-line"></i>Descargar
                                </a>
                                <button type="button" class="btn-doc replace" data-tipo="{{ $doc['tipo'] }}">
                                    <i class="ri-upload-line"></i>Reemplazar
                                </button>
                                @if (!$doc['verificado'])
                                    <button type="button" class="btn-doc verify" data-tipo="{{ $doc['tipo'] }}">
                                        <i class="ri-check-line"></i>Verificar
                                    </button>
                                @else
                                    <button type="button" class="btn-doc unverify" data-tipo="{{ $doc['tipo'] }}">
                                        <i class="ri-close-line"></i>Quitar verificación
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="doc-empty">
                                <i class="ri-upload-cloud-2-line"></i>
                                <p>Documento no subido aún</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>