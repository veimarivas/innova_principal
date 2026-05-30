@extends('layouts.master')
@section('title')
    Detalle Oferta Académica - {{ $oferta->codigo }}
@endsection

@section('css')
    @include('admin.ofertas-academicas.partials.ofertas-detalle.styles')
@endsection

@section('content')
    @php
        $brandColor = $oferta->color ?? '#fc7b04';
        
        // Clean up hex code (remove # and trim whitespace)
        $hex = str_replace('#', '', trim($brandColor));
        
        // Handle short hex syntax (e.g. "f30" -> "ff3300")
        if (strlen($hex) == 3) {
            $hex = substr($hex, 0, 1) . substr($hex, 0, 1) .
                   substr($hex, 1, 1) . substr($hex, 1, 1) .
                   substr($hex, 2, 1) . substr($hex, 2, 1);
        }
        
        // Standardize to 6 digits hex, with fallback to default orange if invalid
        if (strlen($hex) != 6 || !ctype_xdigit($hex)) {
            $hex = 'fc7b04';
            $brandColor = '#fc7b04';
        }
        
        // Extract RGB values
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $brandColorRgb = "$r, $g, $b";
        
        // Calculate Relative Luminance (WCAG standard formula: L = 0.2126 * R + 0.7152 * G + 0.0722 * B)
        $rNorm = $r / 255;
        $gNorm = $g / 255;
        $bNorm = $b / 255;
        
        $rL = ($rNorm <= 0.03928) ? ($rNorm / 12.92) : pow(($rNorm + 0.055) / 1.055, 2.4);
        $gL = ($gNorm <= 0.03928) ? ($gNorm / 12.92) : pow(($gNorm + 0.055) / 1.055, 2.4);
        $bL = ($bNorm <= 0.03928) ? ($bNorm / 12.92) : pow(($bNorm + 0.055) / 1.055, 2.4);
        
        $luminance = 0.2126 * $rL + 0.7152 * $gL + 0.0722 * $bL;
        
        // WCAG threshold is 0.179. Above it, dark text is better; below it, light text.
        $brandContrastColor = ($luminance > 0.179) ? '#1e1e1e' : '#ffffff';
    @endphp

    <div class="oferta-details-theme-wrapper" style="--brand-color: {{ $brandColor }}; --brand-color-rgb: {{ $brandColorRgb }}; --brand-contrast-color: {{ $brandContrastColor }}; overflow: visible;">
        <div class="dept-page-header">
            <div class="container-fluid">
                @include('admin.ofertas-academicas.partials.ofertas-detalle.header-tabs')
            </div>
        </div>

        <div class="container-fluid py-4" style="overflow: visible;">
            <div class="row" style="overflow: visible;">
                <div class="col-12" style="overflow: visible;">
                    <div class="oferta-detail-card" style="overflow: visible;">
                        @include('admin.ofertas-academicas.partials.ofertas-detalle.tab-info')
                        @include('admin.ofertas-academicas.partials.ofertas-detalle.tab-modulos')
                        @include('admin.ofertas-academicas.partials.ofertas-detalle.tab-contable')
                        @include('admin.ofertas-academicas.partials.ofertas-detalle.tab-finanzas')
                        @include('admin.ofertas-academicas.partials.ofertas-detalle.tab-inscripciones')
                        @include('admin.ofertas-academicas.partials.ofertas-detalle.tab-plataforma')
                    </div>
                </div>
            </div>
        </div>

        @include('admin.ofertas-academicas.partials.ofertas-detalle.modals')
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ URL::asset('build/libs/fullcalendar/index.global.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    @include('admin.ofertas-academicas.partials.ofertas-detalle.scripts')
@endsection
