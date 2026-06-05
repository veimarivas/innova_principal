<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante N° {{ $pago->recibo ?? $pago->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;
            color: #000;
            line-height: 1.3;
            background: #fff;
        }

        .recibo-container {
            max-width: 750px;
            margin: 0 auto;
            padding: 25px 30px;
            border: 1px solid #000;
        }

        /* Header */
        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-logo img {
            width: 50px;
            height: auto;
        }

        .header-logo-text .nombre {
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
        }

        .header-logo-text .slogan {
            font-size: 9px;
            color: #444;
        }

        .comprobante-nro {
            font-weight: bold;
            font-size: 14px;
            letter-spacing: 1px;
            text-align: right;
            color: #c96004;
        }

        .sede {
            font-weight: bold;
            font-size: 11px;
            text-align: center;
            text-transform: uppercase;
            margin: 8px 0;
            padding: 4px 0;
            border-bottom: 1px solid #000;
        }

        .header-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 10px;
            font-size: 10px;
            margin-top: 8px;
        }

        .header-grid strong {
            display: inline-block;
            width: 105px;
            font-weight: bold;
        }

        /* Información */
        .info-section {
            margin: 12px 0;
            font-size: 11px;
            padding: 10px;
            background: #f9f9f9;
            border-left: 3px solid #c96004;
        }

        .info-line {
            margin: 4px 0;
        }

        .info-line strong {
            display: inline-block;
            width: 145px;
            font-weight: bold;
        }

        /* Tabla */
        .conceptos-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10px;
            border: 1px solid #ccc;
        }

        .conceptos-table th,
        .conceptos-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
        }

        .conceptos-table th {
            font-weight: bold;
            background: #f0f0f0;
            color: #000;
        }

        .conceptos-table th:nth-child(2),
        .conceptos-table td:nth-child(2) {
            text-align: left;
            width: 40%;
        }

        .conceptos-table .monto-col {
            text-align: right;
            font-weight: 600;
        }

        .conceptos-table tbody tr:nth-child(even) {
            background: #fafafa;
        }

        /* Total */
        .total-section {
            margin: 20px 0;
            text-align: right;
            padding: 15px;
            background: #fafafa;
            border: 1px solid #ddd;
        }

        .total-label {
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 11px;
        }

        .monto-letras {
            font-style: italic;
            margin: 6px 0 8px;
            text-align: justify;
            font-size: 10px;
        }

        .monto-numero {
            font-weight: bold;
            font-size: 14px;
            color: #c96004;
        }

        /* Firmas */
        .firmas-section {
            margin-top: 40px;
            display: table;
            width: 100%;
            page-break-inside: avoid;
        }

        .firma-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 10px;
        }

        .firma-linea {
            border-top: 1px solid #000;
            margin-bottom: 8px;
            width: 100%;
        }

        .firma-nombre {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 2px;
            line-height: 1.2;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .firma-cargo {
            font-size: 9px;
            color: #333;
            margin-bottom: 8px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .firma-label {
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 12px;
            border-top: 1px solid #000;
            font-size: 9px;
            text-align: center;
            clear: both;
        }

        .footer-line {
            margin: 2px 0;
        }

        /* Utilidades */
        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        @media print {
            body {
                background: #fff;
            }

            .recibo-container {
                border: none;
                padding: 0;
            }

            .firmas-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    @php
        $inscripcion = null;
        $estudiante = null;
        if ($pago->pagosCuotas && $pago->pagosCuotas->isNotEmpty()) {
            $primerPc = $pago->pagosCuotas->first();
            if ($primerPc && $primerPc->cuota) {
                $inscripcion = $primerPc->cuota->inscripcion;
                $estudiante = $inscripcion?->estudiante;
            }
        }

        function numeroALetras($numero)
        {
            $formatter = new NumberFormatter('es_BO', NumberFormatter::SPELLOUT);
            $entero = floor($numero);
            $centavos = round(($numero - $entero) * 100);
            $letras = $formatter->format($entero);
            $letras = ucfirst($letras);
            return 'Son ' . $letras . ' bolivianos ' . $entero . '.' . str_pad($centavos, 2, '0', STR_PAD_LEFT) . '/00';
        }
    @endphp

    <div class="recibo-container">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <div class="header-logo">
                    <img src="{{ public_path('images/logo_secundario.png') }}" alt="Logo">
                    <div class="header-logo-text">
                        <div class="nombre">INNOVA CIENCIA VIRTUAL</div>
                        <div class="slogan">Educación Superior Virtual</div>
                    </div>
                </div>
                <div class="comprobante-nro">
                    COMPROBANTE N° {{ str_pad($pago->recibo ?? $pago->id, 5, '0', STR_PAD_LEFT) }}
                </div>
            </div>
            <div class="sede">Lugar/Sede:
                {{ $inscripcion?->ofertaAcademica?->sucursal?->sede?->nombre ?? 'ESAM SUCRE' }}</div>
            <div class="header-grid">
                <div><strong>Fecha Emisión:</strong> {{ \Carbon\Carbon::parse($pago->fecha_pago)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}
                </div>
                <div><strong>Forma Pago:</strong> {{ $pago->tipo_pago ?? 'Transferencia' }}</div>
                <div><strong>N° Factura:</strong> {{ $pago->factura_nro ?? '————' }}</div>
                @if ($inscripcion?->ofertaAcademica?->posgrado)
                    <div><strong>Programa:</strong> {{ $inscripcion->ofertaAcademica->posgrado->nombre }}</div>
                @endif
            </div>
        </div>

        <!-- Datos del Estudiante -->
        @if ($estudiante)
            <div class="info-section">
                <div class="info-line">
                    <strong>Estudiante:</strong>
                    {{ $estudiante->persona->nombres ?? '' }}
                    {{ $estudiante->persona->apellido_paterno ?? '' }}
                    {{ $estudiante->persona->apellido_materno ?? '' }}
                    @if ($estudiante->persona->carnet)
                        - {{ $estudiante->persona->carnet }}
                    @endif
                </div>
                <div class="info-line">
                    <strong>Señor(a) Depositante:</strong>
                    {{ $pago->depositante ?? $estudiante->persona->nombres . ' ' . $estudiante->persona->apellido_paterno }}
                </div>
            </div>
        @endif

        <!-- Tabla de Conceptos -->
        <table class="conceptos-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-left">Concepto</th>
                    <th>N° de Cuota</th>
                    <th>Cantidad</th>
                    <th>Monto</th>
                    <th>Subtotal</th>
                    <th>Unidad Medida</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pago->pagosCuotas as $index => $pc)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-left">{{ $pc->cuota->nombre ?? 'Colegiatura' }}</td>
                        <td>{{ $pc->cuota->n_cuota ?? $index + 1 }}</td>
                        <td>1</td>
                        <td class="monto-col">Bs. {{ number_format($pc->monto_bs ?? 0, 2) }}</td>
                        <td class="monto-col bold">Bs. {{ number_format($pc->monto_bs ?? 0, 2) }}</td>
                        <td>Cuota</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 12px;">Sin conceptos registrados</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($pago->pagosCuotas->isNotEmpty())
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right bold">TOTAL:</td>
                        <td class="monto-col bold">Bs. {{ number_format($pago->monto_total ?? 0, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            @endif
        </table>

        <!-- Total en Letras y Número -->
        <div class="total-section">
            <div class="total-label">Total (bolivianos)</div>
            <div class="monto-letras">{{ numeroALetras($pago->monto_total ?? 0) }}</div>
            <div class="monto-numero">Bs. {{ number_format($pago->monto_total ?? 0, 2) }}</div>
        </div>

        <!-- Firmas -->
        <div class="firmas-section">
            <!-- Emisor (izquierda) -->
            <div class="firma-box">
                <div class="firma-linea"></div>
                <div class="firma-nombre">
                    @if ($pago->trabajadorCargo && $pago->trabajadorCargo->trabajador && $pago->trabajadorCargo->trabajador->persona)
                        {{ $pago->trabajadorCargo->trabajador->persona->nombres }}
                        {{ $pago->trabajadorCargo->trabajador->persona->apellido_paterno }}
                    @else
                        García Marisol
                    @endif
                </div>
                <div class="firma-cargo">Auxiliar Contable</div>
                <div class="firma-label">EMISOR</div>
            </div>

            <!-- Depositante (derecha) -->
            <div class="firma-box">
                <div class="firma-linea"></div>
                <div class="firma-nombre">
                    {{ $pago->depositante ?? ($estudiante->persona->nombres ?? '') }}
                    {{ $pago->depositante_apellido ?? ($estudiante->persona->apellido_paterno ?? '') }}
                </div>
                <div class="firma-cargo">C.I. {{ $estudiante->persona->carnet ?? '————' }}</div>
                <div class="firma-label">DEPOSITANTE</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-line"><strong>N° Doc:</strong> {{ $pago->documento_referencia ?? '——————' }}</div>
            <div class="footer-line">Documento generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</div>
            <div class="footer-line" style="margin-top: 8px; font-style: italic;">Este comprobante es válido como
                constancia de pago</div>
        </div>
    </div>
</body>

</html>