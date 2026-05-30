<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Notas Finales - {{ $modulo->nombre }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 11px;
        color: #000;
        background: #fff;
    }

    .page {
        padding: 24px 28px 24px 28px;
    }

    /* ── Cabecera ── */
    .header-wrap {
        border-bottom: 2.5px solid #000;
        padding-bottom: 10px;
        margin-bottom: 14px;
        width: 100%;
    }
    .header-wrap table { width: 100%; }
    .header-wrap td { vertical-align: middle; }

    .logo-td { width: 62px; }
    .logo-td img { width: 56px; height: auto; }

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
        font-size: 14px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #000;
    }
    .titulo-sub { font-size: 9.5px; color: #333; margin-top: 3px; }

    .fecha-td { text-align: right; white-space: nowrap; }
    .fecha-td div { font-size: 10.5px; color: #000; line-height: 1.7; }

    /* ── Info módulo ── */
    .info-wrap {
        width: 100%;
        border: 1.5px solid #000;
        margin-bottom: 16px;
    }
    .info-wrap table { width: 100%; border-collapse: collapse; }
    .info-wrap td {
        padding: 6px 10px;
        font-size: 11px;
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

    /* ── Tabla de notas finales ── */
    .notas-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
    }
    .notas-table th {
        background: #000;
        color: #fff;
        font-weight: bold;
        font-size: 11px;
        text-transform: uppercase;
        padding: 8px 8px;
        border: 1px solid #000;
        text-align: center;
        vertical-align: middle;
    }
    .notas-table th.th-left { text-align: left; }

    .notas-table td {
        border: 1px solid #000;
        padding: 6px 8px;
        font-size: 11px;
        color: #000;
        vertical-align: middle;
    }
    .notas-table td.td-num    { text-align: center; width: 32px; }
    .notas-table td.td-nombre { text-align: left; }
    .notas-table td.td-ci     { text-align: center; width: 80px; }
    .notas-table td.td-nota   {
        text-align: center;
        font-weight: bold;
        font-size: 13px;
        width: 70px;
        color: #000;
    }
    .notas-table td.td-literal {
        font-size: 10.5px;
        color: #000;
        text-transform: uppercase;
        font-style: italic;
    }

    /* ── Firmas ── */
    .firmas-wrap {
        width: 100%;
        margin-top: 46px;
        page-break-inside: avoid;
    }
    .firmas-wrap table { width: 100%; }
    .firmas-wrap td {
        width: 50%;
        text-align: center;
        padding: 0 35px;
        vertical-align: bottom;
    }
    .firma-espacio { height: 42px; }
    .firma-linea   { border-top: 1.5px solid #000; margin-bottom: 6px; }
    .firma-nombre  { font-weight: bold; font-size: 11.5px; text-transform: uppercase; color: #000; }
    .firma-cargo   { font-size: 10px; color: #000; margin-top: 3px; }

    /* ── Footer ── */
    .footer {
        margin-top: 20px;
        border-top: 1px solid #000;
        padding-top: 6px;
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
                    <div class="titulo-reporte">Reporte de Notas Finales</div>
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
                <td class="lbl">Nota mínima aprobatoria:</td>
                <td class="val">{{ $modulo->oferta_academica?->nota_minima ?? '——' }}</td>
            </tr>
        </table>
    </div>

    {{-- ── TABLA DE NOTAS FINALES ── --}}
    <table class="notas-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width:32px;">#</th>
                <th class="th-left">Apellidos y Nombres</th>
                <th style="width:80px;">C.I.</th>
                <th style="width:70px;">Nota Final</th>
                <th class="th-left">Nota en Literal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estudiantes as $idx => $est)
                <tr>
                    <td class="td-num">{{ $idx + 1 }}</td>
                    <td class="td-nombre">{{ $est['nombre'] }}</td>
                    <td class="td-ci">{{ $est['ci'] }}</td>
                    <td class="td-nota">{{ number_format($est['nota_final'], 2) }}</td>
                    <td class="td-literal">{{ $est['nota_literal'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:16px;">
                        Sin estudiantes registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

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
