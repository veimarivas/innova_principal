<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Estudiantes — {{ $modulo->nombre ?? '' }}</title>
    <style>
        @page { margin: 14mm 12mm 16mm 12mm; }
        * { box-sizing: border-box; }
        body {
            margin: 0; padding: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #111;
            font-size: 10pt;
        }

        /* ── Header ── */
        .mp-header {
            width: 100%;
            border-bottom: 3px solid #fc7b04;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .mp-header table { width: 100%; border-collapse: collapse; }
        .mp-header td { vertical-align: middle; }
        .mp-logo-cell { width: 120px; }
        .mp-logo { max-width: 110px; max-height: 70px; }
        .mp-title-cell { text-align: center; }
        .mp-title {
            font-size: 15pt; font-weight: bold;
            letter-spacing: 1.2px; color: #1e1e1e;
            margin-bottom: 2px;
        }
        .mp-subtitle {
            font-size: 11pt; font-weight: bold;
            color: #fc7b04;
            margin-bottom: 2px;
        }
        .mp-codigo { font-size: 8.5pt; color: #444; }
        .mp-date-cell {
            width: 130px;
            text-align: right;
        }
        .mp-date-lbl {
            font-size: 7.5pt; color: #777;
            text-transform: uppercase; letter-spacing: .04em;
        }
        .mp-date-val {
            font-size: 9.5pt; font-weight: bold;
            color: #1e1e1e; margin-top: 2px;
        }

        /* ── Info card ── */
        .mp-info {
            margin: 0 0 12px;
            padding: 8px 12px;
            background: #f8f8f4;
            border-left: 4px solid #fc7b04;
        }
        .mp-info table { width: 100%; border-collapse: collapse; }
        .mp-info td { padding: 3px 0; vertical-align: top; font-size: 9.5pt; }
        .mp-info-lbl {
            font-weight: bold; color: #666;
            text-transform: uppercase;
            font-size: 8pt; letter-spacing: .04em;
            width: 110px;
        }
        .mp-info-val { font-weight: bold; color: #1e1e1e; }

        /* ── Tabla de estudiantes ── */
        .mp-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            font-size: 10pt;
        }
        .mp-table thead th {
            background: #fc7b04;
            color: #ffffff;
            font-weight: bold;
            font-size: 9pt;
            padding: 6px 8px;
            border: 1px solid #c95f00;
            text-align: left;
        }
        .mp-table tbody td {
            padding: 5px 8px;
            border: 1px solid #d4d4d4;
            vertical-align: middle;
        }
        .mp-table tbody tr.even td { background: #fafafa; }
        .mp-c-n { width: 32px; text-align: center; font-weight: bold; }
        .mp-c-ci {
            width: 95px;
            font-family: DejaVu Sans Mono, Courier, monospace;
        }
        .mp-c-nombre { font-weight: bold; }
        .mp-c-firma { width: 160px; }

        /* ── Firmas footer ── */
        .mp-signs {
            margin-top: 50px;
            width: 100%;
        }
        .mp-signs table { width: 100%; border-collapse: collapse; }
        .mp-signs td {
            text-align: center;
            padding: 0 30px;
        }
        .mp-sign-line {
            border-top: 1px solid #1e1e1e;
            margin: 40px 0 4px;
        }
        .mp-sign-lbl {
            font-size: 9pt; font-weight: bold;
            color: #1e1e1e;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .mp-sign-name {
            font-size: 8.5pt; color: #555;
            margin-top: 2px;
        }

        .mp-empty {
            text-align: center;
            padding: 30px 10px;
            font-size: 10pt;
            color: #888;
            font-style: italic;
        }
    </style>
</head>
<body>

    @php
        $programa = $oferta?->programa?->nombre ?? $oferta?->posgrado?->nombre ?? '—';
        $codigoOferta = $oferta?->codigo ?? '—';
        $gestion = $oferta?->gestion ?? '';
        $version = $oferta?->version ?? '';
        $grupo   = $oferta?->grupo ?? '';
        $modFechas = '';
        if ($modulo->fecha_inicio) {
            try {
                $modFechas = \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y');
                if ($modulo->fecha_fin) {
                    $modFechas .= ' — ' . \Carbon\Carbon::parse($modulo->fecha_fin)->format('d/m/Y');
                }
            } catch (\Throwable $e) { $modFechas = ''; }
        }
        $codigoExtras = [];
        if ($gestion) $codigoExtras[] = 'Gestión ' . $gestion;
        if ($version) $codigoExtras[] = 'V' . $version;
        if ($grupo)   $codigoExtras[] = 'G' . $grupo;
    @endphp

    {{-- ════════════ Header ════════════ --}}
    <div class="mp-header">
        <table>
            <tr>
                <td class="mp-logo-cell">
                    @if ($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo" class="mp-logo">
                    @endif
                </td>
                <td class="mp-title-cell">
                    <div class="mp-title">LISTA DE ESTUDIANTES</div>
                    <div class="mp-subtitle">{{ $programa }}</div>
                    <div class="mp-codigo">
                        Código: <strong>{{ $codigoOferta }}</strong>
                        @if (count($codigoExtras))
                            · {{ implode(' · ', $codigoExtras) }}
                        @endif
                    </div>
                </td>
                <td class="mp-date-cell">
                    <div class="mp-date-lbl">Fecha impresión</div>
                    <div class="mp-date-val">{{ $fechaImpresion }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ════════════ Info de módulo / docente ════════════ --}}
    <div class="mp-info">
        <table>
            <tr>
                <td class="mp-info-lbl">Módulo</td>
                <td class="mp-info-val">{{ $modulo->nombre ?? '—' }}</td>
                @if ($modFechas)
                    <td class="mp-info-lbl" style="width:80px;">Fechas</td>
                    <td class="mp-info-val">{{ $modFechas }}</td>
                @else
                    <td></td><td></td>
                @endif
            </tr>
            <tr>
                <td class="mp-info-lbl">Docente</td>
                <td class="mp-info-val">{{ $nombreDocente }}</td>
                <td class="mp-info-lbl" style="width:80px;">Total</td>
                <td class="mp-info-val">{{ count($inscritos) }} estudiante(s)</td>
            </tr>
        </table>
    </div>

    {{-- ════════════ Tabla ════════════ --}}
    @if (count($inscritos) === 0)
        <div class="mp-empty">No hay estudiantes inscritos en este módulo.</div>
    @else
        <table class="mp-table">
            <thead>
                <tr>
                    <th class="mp-c-n">#</th>
                    <th class="mp-c-ci">CI</th>
                    <th class="mp-c-nombre">Apellidos y Nombres</th>
                    <th class="mp-c-firma">Firma</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inscritos as $i => $est)
                    @php
                        $nombreCompleto = trim(
                            ($est['apellido_paterno'] ?? '') . ' ' .
                            ($est['apellido_materno'] ?? '') . ' ' .
                            ($est['nombres'] ?? '')
                        );
                    @endphp
                    <tr class="{{ $i % 2 === 1 ? 'even' : '' }}">
                        <td class="mp-c-n">{{ $i + 1 }}</td>
                        <td class="mp-c-ci">{{ $est['carnet'] }}</td>
                        <td class="mp-c-nombre">{{ $nombreCompleto ?: '—' }}</td>
                        <td class="mp-c-firma"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- ════════════ Firmas ════════════ --}}
    <div class="mp-signs">
        <table>
            <tr>
                <td>
                    <div class="mp-sign-line"></div>
                    <div class="mp-sign-lbl">Firma Docente</div>
                    <div class="mp-sign-name">{{ $nombreDocente }}</div>
                </td>
                <td>
                    <div class="mp-sign-line"></div>
                    <div class="mp-sign-lbl">Coordinación Académica</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
