<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Notas Detallado - {{ $modulo->nombre }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 11px;
        color: #000;
        background: #fff;
    }

    .page {
        padding: 20px 24px 20px 24px;
    }

    /* ── Cabecera ── */
    .header-wrap {
        border-bottom: 2.5px solid #000;
        padding-bottom: 10px;
        margin-bottom: 12px;
        width: 100%;
    }
    .header-wrap table { width: 100%; }
    .header-wrap td { vertical-align: middle; }

    .logo-td { width: 60px; }
    .logo-td img { width: 54px; height: auto; }

    .inst-td { padding-left: 10px; }
    .inst-nombre {
        font-size: 15px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #000;
    }
    .inst-slogan { font-size: 9px; color: #444; margin-top: 2px; }

    .titulo-td { text-align: center; padding: 0 10px; }
    .titulo-reporte {
        font-size: 13px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #000;
    }
    .titulo-sub { font-size: 9px; color: #333; margin-top: 3px; }

    .fecha-td { text-align: right; white-space: nowrap; }
    .fecha-td div { font-size: 10px; color: #000; line-height: 1.7; }

    /* ── Info módulo ── */
    .info-wrap {
        width: 100%;
        border: 1.5px solid #000;
        margin-bottom: 14px;
    }
    .info-wrap table { width: 100%; border-collapse: collapse; }
    .info-wrap td {
        padding: 5px 10px;
        font-size: 10.5px;
        color: #000;
        border: none;
    }
    .info-wrap .lbl {
        font-weight: bold;
        white-space: nowrap;
        color: #000;
        width: 1%;
    }
    .info-wrap .val { color: #000; }

    /* ── Tabla de notas ── */
    .notas-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
        table-layout: fixed;
    }
    .notas-table th {
        background: #000;
        color: #fff;
        font-weight: bold;
        font-size: 9.5px;
        text-transform: uppercase;
        padding: 6px 5px;
        border: 1px solid #000;
        text-align: center;
        vertical-align: middle;
        word-break: break-word;
    }
    .notas-table th.th-left { text-align: left; }

    .notas-table td {
        border: 1px solid #000;
        padding: 5px 5px;
        font-size: 10px;
        color: #000;
        vertical-align: middle;
        text-align: center;
        word-break: break-word;
    }
    .notas-table td.td-nombre { text-align: left; }
    .notas-table td.td-num    { text-align: center; color: #000; }

    .notas-table td.nota-ponderada { font-weight: bold; color: #000; }
    .notas-table td.sin-nota       { color: #000; font-style: italic; }
    .notas-table td.nota-final {
        font-weight: bold;
        font-size: 11px;
        color: #000;
        background: #e8e8e8;
    }

    .nota-raw {
        display: block;
        font-size: 8px;
        color: #333;
        font-weight: normal;
        margin-top: 1px;
    }

    /* ── Leyenda ── */
    .leyenda {
        font-size: 9px;
        color: #000;
        margin-bottom: 16px;
        line-height: 1.6;
        border-left: 3px solid #000;
        padding-left: 8px;
    }

    /* ── Firmas ── */
    .firmas-wrap {
        width: 100%;
        margin-top: 38px;
        page-break-inside: avoid;
    }
    .firmas-wrap table { width: 100%; }
    .firmas-wrap td {
        width: 50%;
        text-align: center;
        padding: 0 30px;
        vertical-align: bottom;
    }
    .firma-espacio { height: 38px; }
    .firma-linea   { border-top: 1.5px solid #000; margin-bottom: 5px; }
    .firma-nombre  { font-weight: bold; font-size: 11px; text-transform: uppercase; color: #000; }
    .firma-cargo   { font-size: 9.5px; color: #000; margin-top: 3px; }

    /* ── Footer ── */
    .footer {
        margin-top: 18px;
        border-top: 1px solid #000;
        padding-top: 5px;
        font-size: 8.5px;
        color: #000;
        text-align: center;
    }

    @page { size: A4 portrait; margin: 0; }
</style>
</head>
<body>
<div class="page">

    {{-- ── CABECERA ── --}}
    <div class="header-wrap">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td class="logo-td">
                    <img src="{{ public_path('build/images/logo-dark.png') }}" alt="Logo">
                </td>
                <td class="inst-td">
                    <div class="inst-nombre">Innova Ciencia Virtual</div>
                    <div class="inst-slogan">Educación Superior Virtual</div>
                </td>
                <td class="titulo-td">
                    <div class="titulo-reporte">Reporte de Notas Detallado</div>
                    <div class="titulo-sub">{{ $programa_nombre }}</div>
                </td>
                <td class="fecha-td">
                    <div><strong>Fecha:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
                    <div><strong>Gestión:</strong> {{ $modulo->oferta_academica?->gestion ?? '——' }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── INFO DEL MÓDULO ── --}}
    <div class="info-wrap">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td class="lbl">Módulo:</td>
                <td class="val">{{ $modulo->nombre }}</td>
                <td class="lbl">Oferta:</td>
                <td class="val">{{ $oferta_codigo }}</td>
                <td class="lbl">Docente:</td>
                <td class="val">{{ $docente_nombre }}</td>
            </tr>
            <tr>
                <td class="lbl">Inicio:</td>
                <td class="val">{{ $modulo->fecha_inicio?->format('d/m/Y') ?? '——' }}</td>
                <td class="lbl">Fin:</td>
                <td class="val">{{ $modulo->fecha_fin?->format('d/m/Y') ?? '——' }}</td>
                <td class="lbl">Total estudiantes:</td>
                <td class="val">{{ count($estudiantes) }}</td>
            </tr>
        </table>
    </div>

    {{-- ── TABLA DE NOTAS ── --}}
    @php
        $totalActividades = count($grade_items);
        // Calcular anchos proporcionales: fijos para #, nombre, CI y nota final
        $anchoFijo    = 8 + 26 + 10 + 11; // % aprox para col fijas
        $anchoAct     = $totalActividades > 0 ? round((100 - $anchoFijo) / $totalActividades, 1) : 10;
        $anchoAct     = max($anchoAct, 8);
    @endphp

    <table class="notas-table" cellpadding="0" cellspacing="0">
        <colgroup>
            <col style="width:8%">
            <col style="width:26%">
            <col style="width:10%">
            @foreach($grade_items as $item)
                <col style="width:{{ $anchoAct }}%">
            @endforeach
            <col style="width:11%">
        </colgroup>
        <thead>
            <tr>
                <th>#</th>
                <th class="th-left">Apellidos y Nombres</th>
                <th>C.I.</th>
                @foreach($grade_items as $item)
                    <th>
                        {{ $item['name'] }}<br>
                        <span style="font-size:8px;font-weight:600;color:#ddd;">{{ number_format($item['weight'], 1) }}%</span>
                    </th>
                @endforeach
                <th>NOTA<br>FINAL</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estudiantes as $idx => $est)
                <tr>
                    <td class="td-num">{{ $idx + 1 }}</td>
                    <td class="td-nombre">{{ $est['nombre'] }}</td>
                    <td>{{ $est['ci'] }}</td>
                    @foreach($grade_items as $item)
                        @php
                            $info      = $est['notas'][$item['id']] ?? null;
                            $ponderada = $info['ponderada'] ?? 0;
                            $rawVal    = $info['raw'] ?? null;
                            $maxVal    = $item['max'] !== null ? (float)$item['max'] : null;
                        @endphp
                        <td class="nota-ponderada {{ $rawVal === null ? 'sin-nota' : '' }}">
                            {{ number_format($ponderada, 2) }}
                            @if($rawVal !== null && $maxVal !== null)
                                <span class="nota-raw">{{ number_format($rawVal, 1) }}/{{ number_format($maxVal, 1) }}</span>
                            @else
                                <span class="nota-raw">sin nota</span>
                            @endif
                        </td>
                    @endforeach
                    <td class="nota-final">{{ number_format($est['nota_final'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 3 + count($grade_items) + 1 }}" style="text-align:center;padding:14px;">
                        Sin estudiantes registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── LEYENDA ── --}}
    <div class="leyenda">
        <strong>Nota:</strong>
        Nota ponderada = (nota Moodle &divide; nota máxima) &times; ponderación (%).
        Las celdas con "sin nota" indican que el estudiante no registró calificación en Moodle; se asume 0 para el cálculo.
        @if(!$is_cumulative)
            <strong>Modo promedio:</strong> la nota final es el promedio de las notas ponderadas.
        @endif
    </div>

    {{-- ── FIRMAS ── --}}
    <div class="firmas-wrap">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div class="firma-espacio"></div>
                    <div class="firma-linea"></div>
                    <div class="firma-nombre">{{ $docente_nombre }}</div>
                    <div class="firma-cargo">Docente del Módulo</div>
                </td>
                <td>
                    <div class="firma-espacio"></div>
                    <div class="firma-linea"></div>
                    <div class="firma-nombre">{{ $academico_nombre }}</div>
                    <div class="firma-cargo">Responsable Área Académica</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        Documento generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} &mdash; Innova Ciencia Virtual
    </div>

</div>
</body>
</html>
