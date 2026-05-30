@extends('layouts.master')
@section('title')
    Buscar Estudiante - Gestión Financiera
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="container-fluid py-4">
        @php
            $trabajadorCargoId = '';
            if (auth()->user()) {
                $user = auth()->user();
                if ($user->persona_id) {
                    $trabajador = \App\Models\Trabajadore::where('persona_id', $user->persona_id)->first();
                    if ($trabajador) {
                        $cargoActivo = \App\Models\TrabajadoresCargo::where('trabajadore_id', $trabajador->id)
                            ->where('estado', 'Vigente')
                            ->first();
                        $trabajadorCargoId = $cargoActivo?->id;
                    }
                }
            }
        @endphp
        <input type="hidden" id="current-trabajador-cargo" value="{{ $trabajadorCargoId }}">
        @if (!$trabajadorCargoId)
            <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                <i class="ri-alert-line me-2"></i>
                <strong>Advertencia:</strong> Tu usuario no tiene un cargo activo asignado. No podrás registrar pagos hasta
                que un administrador te asigne un cargo activo en el sistema.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-header border-0 bg-transparent py-4 px-4" style="border-bottom: 1px solid #f1f5f9;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-3"
                                style="width: 48px; height: 48px; background: #fc7b0415;">
                                <i class="ri-search-line" style="color: #fc7b04; font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold" style="color: #1e293b;">Buscar Estudiante</h4>
                                <p class="mb-0 text-muted" style="font-size: 0.875rem;">Busca por carnet, nombres o
                                    apellidos</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-lg-8">
                                <div class="position-relative">
                                    <i class="ri-search-line position-absolute"
                                        style="left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; z-index: 1;"></i>
                                    <input type="text" id="busquedaInput" class="form-control form-control-lg border-2"
                                        placeholder="Escribe para buscar (carnet, nombres o apellidos)..."
                                        style="padding-left: 48px; border-radius: 12px; border-color: #e2e8f0; font-size: 1rem;">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <button type="button" id="btnBuscar" class="btn btn-lg w-100 fw-semibold"
                                    style="background: #fc7b04; color: white; border-radius: 12px;">
                                    <i class="ri-search-line me-2"></i>Buscar
                                </button>
                            </div>
                        </div>

                        <!-- Resultados en Cards -->
                        <div id="resultadosBusqueda" class="d-none">
                            <h6 class="mb-3 fw-bold" style="color: #1e293b;">Resultados de búsqueda</h6>
                            <div id="cardsResultados" class="row g-3"></div>
                        </div>

                        <div id="sinResultados" class="text-center py-5 d-none">
                            <div class="mb-3">
                                <i class="ri-user-search-line" style="font-size: 4rem; color: #cbd5e1;"></i>
                            </div>
                            <h5 class="fw-semibold" style="color: #64748b;">Sin resultados</h5>
                            <p class="text-muted">No se encontraron estudiantes con ese criterio de búsqueda</p>
                        </div>

                        <div id="buscando" class="text-center py-5 d-none">
                            <div class="spinner-border text-warning" role="status">
                                <span class="visually-hidden">Buscando...</span>
                            </div>
                            <p class="mt-3 text-muted">Buscando estudiantes...</p>
                        </div>

                        <div id="placeholder" class="text-center py-5">
                            <div class="mb-3">
                                <i class="ri-user-follow-line" style="font-size: 4rem; color: #cbd5e1;"></i>
                            </div>
                            <h5 class="fw-semibold" style="color: #64748b;">Buscar estudiantes</h5>
                            <p class="text-muted">Ingresa al menos 2 caracteres para iniciar la búsqueda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Pago Masivo -->
        <div class="modal fade" id="modalPagoMasivo" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title"><i class="ri-file-list-3-line"></i> Registro Masivo de Cuotas</h5>
                            <small class="text-muted d-block" id="pago-masivo-oferta"></small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formPagoMasivo">
                        <div class="modal-body">
                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label class="form-label"><i class="ri-money-dollar-line"></i> Monto a Pagar
                                        (Bs.)</label>
                                    <input type="number" class="form-control" id="pago-masivo-monto" name="monto"
                                        step="0.01" min="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label"><i class="ri-discount-line"></i> Descuento (Bs.)</label>
                                    <input type="number" class="form-control" id="pago-masivo-descuento"
                                        name="descuento" step="0.01" min="0" value="0">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label"><i class="ri-calendar-line"></i> Fecha de Pago</label>
                                    <input type="date" class="form-control" id="pago-masivo-fecha" name="fecha_pago"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label"><i class="ri-bank-card-line"></i> Método de Pago</label>
                                    <select class="form-select" id="pago-masivo-metodo" name="metodo" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Qr">QR</option>
                                        <option value="Parcial">Parcial (Efectivo + QR)</option>
                                    </select>
                                </div>
                                <div class="col-md-6" id="pago-masivo-campo-efectivo" style="display:none;">
                                    <label class="form-label"><i class="ri-money-dollar-line"></i> Efectivo (Bs.)</label>
                                    <input type="number" class="form-control" id="pago-masivo-efectivo" name="efectivo"
                                        step="0.01" min="0">
                                </div>
                                <div class="col-md-6" id="pago-masivo-campo-qr" style="display:none;">
                                    <label class="form-label"><i class="ri-qr-code-line"></i> QR (Bs.)</label>
                                    <input type="number" class="form-control" id="pago-masivo-qr" name="qr"
                                        step="0.01" min="0">
                                </div>
                            </div>
                            <div id="pago-masivo-lista-cuotas" class="mb-4"
                                style="max-height: 300px; overflow-y: auto;"></div>
                            <div class="alert alert-info">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <div class="text-muted small">Total Deuda</div>
                                        <div class="fw-bold" id="pago-masivo-deuda-total">—</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-muted small">Monto Ingresado</div>
                                        <div class="fw-bold text-primary" id="pago-masivo-monto-ingresado">—</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-muted small">Nueva Deuda</div>
                                        <div class="fw-bold text-success" id="pago-masivo-nueva-deuda">—</div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="pago-masivo-estudiante-id" name="estudiante_id">
                            <input type="hidden" id="pago-masivo-inscripcion-id" name="inscripcion_id">
                            <input type="hidden" id="pago-masivo-trabajador-cargo" name="trabajador_cargo_id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                    class="ri-close-line"></i> Cancelar</button>
                            <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Registrar
                                Pago</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Recibo de Pago -->
        <div class="modal fade" id="modalReciboPago" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
                <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">
                    <div class="modal-body p-0">
                        <!-- Cabecera verde de éxito -->
                        <div class="text-center py-4 px-4" style="background: linear-gradient(135deg, #16a34a, #15803d);">
                            <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3"
                                style="width: 64px; height: 64px; background: rgba(255,255,255,0.2);">
                                <i class="ri-checkbox-circle-fill text-white" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="text-white fw-bold mb-1">Pago Registrado</h5>
                            <p class="text-white mb-0" style="opacity: .85; font-size: .875rem;"
                                id="recibo-mensaje-exito">—</p>
                        </div>

                        <!-- Datos del recibo -->
                        <div class="px-4 py-3" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">N° Recibo</span>
                                <span class="fw-bold" style="color: #1e293b;" id="recibo-numero">—</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-muted small">Total Pagado</span>
                                <span class="fw-bold text-success" id="recibo-total">—</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-muted small">Saldo Restante</span>
                                <span class="fw-bold" id="recibo-saldo">—</span>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="p-4">
                            <p class="text-muted text-center mb-3" style="font-size: .8rem;">¿Desea generar el comprobante
                                de pago?</p>
                            <div class="d-grid gap-2">
                                <button type="button" id="btn-imprimir-recibo" class="btn fw-semibold"
                                    style="background: #fc7b04; color: white; border-radius: 10px; padding: 10px;">
                                    <i class="ri-printer-line me-2"></i>Imprimir Recibo
                                </button>
                                <button type="button" id="btn-descargar-recibo"
                                    class="btn btn-outline-secondary fw-semibold"
                                    style="border-radius: 10px; padding: 10px;">
                                    <i class="ri-download-line me-2"></i>Descargar Recibo
                                </button>
                                <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal"
                                    style="font-size: .85rem;">
                                    Cerrar sin imprimir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            const buscarInput = document.getElementById('busquedaInput');
            const btnBuscar = document.getElementById('btnBuscar');
            const resultadosDiv = document.getElementById('resultadosBusqueda');
            const cardsResultados = document.getElementById('cardsResultados');
            const sinResultados = document.getElementById('sinResultados');
            const buscando = document.getElementById('buscando');
            const placeholder = document.getElementById('placeholder');

            let debounceTimer;

            window.mostrarSeccion = function(seccion) {
                resultadosDiv.classList.add('d-none');
                sinResultados.classList.add('d-none');
                buscando.classList.add('d-none');
                placeholder.classList.add('d-none');
                seccion.classList.remove('d-none');
            }

            window.buscarEstudiantes = function(query) {
                if (query.length < 2) return;

                mostrarSeccion(buscando);

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    fetch('/admin/estudiantes/buscar/api?q=' + encodeURIComponent(query))
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            if (data.data && data.data.length > 0) {
                                renderizarResultados(data.data);
                            } else {
                                mostrarSeccion(sinResultados);
                            }
                        })
                        .catch(function(error) {
                            console.error('Error:', error);
                            mostrarSeccion(sinResultados);
                        });
                }, 300);
            }

            function renderizarResultados(estudiantes) {
                mostrarSeccion(resultadosDiv);

                cardsResultados.innerHTML = estudiantes.map(function(est) {
                    var estadoColor = est.saldo <= 0 ? '#16a34a' : (est.total_pagado > 0 ? '#d97706' :
                        '#dc2626');
                    var estadoBadge = est.saldo <= 0 ? 'Al día' : (est.cuotas_pendientes > 0 ? 'Pendiente' :
                        'Sin pagos');

                    var ofertasHtml = '';
                    if (est.ofertas && est.ofertas.length > 0) {
                        ofertasHtml = '<div class="mt-3 pt-3" style="border-top: 1px solid #e2e8f0;">' +
                            '<h6 class="fw-semibold mb-2" style="color: #64748b; font-size: 0.75rem; text-transform: uppercase;">Ofertas Académicas</h6>' +
                            '<div class="row g-2">';

                        est.ofertas.forEach(function(oferta, idx) {
                            var ofertaEstadoColor = oferta.saldo <= 0 ? '#16a34a' : (oferta
                                .total_pagado > 0 ? '#d97706' : '#dc2626');
                            ofertasHtml += '<div class="col-12">' +
                                '<div class="d-flex justify-content-between align-items-center p-2 rounded-2" style="background: #f8fafc;">' +
                                '<div class="d-flex align-items-center gap-2">' +
                                '<span class="badge fs-10 fw-semibold" style="background: #fc7b0420; color: #fc7b04;">' +
                                oferta.oferta_codigo + '</span>' +
                                '<span class="text-decoration-none fw-semibold" style="color: #1e293b; font-size: 0.8rem;">' +
                                oferta.oferta_nombre + '</span>' +
                                '</div>' +
                                '<div class="d-flex align-items-center gap-3">' +
                                '<div class="text-end">' +
                                '<div class="fs-10 text-muted">Pagado</div>' +
                                '<div class="fw-bold text-success">Bs. ' + parseFloat(oferta
                                    .total_pagado).toFixed(2) + '</div>' +
                                '</div>' +
                                '<div class="text-end">' +
                                '<div class="fs-10 text-muted">Saldo</div>' +
                                '<div class="fw-bold" style="color: ' + ofertaEstadoColor +
                                ';">Bs. ' + parseFloat(oferta.saldo).toFixed(2) + '</div>' +
                                '</div>' +
                                '<div class="d-flex gap-1">' +
                                (oferta.saldo > 0 ?
                                    '<button type="button" class="btn btn-sm btn-pagar-oferta" style="background: #22c55e; color: white; border-radius: 6px; padding: 4px 10px;" title="Pagar" data-estudiante-id="' +
                                    est.estudiante_id + '" data-inscripcion-id="' + (oferta
                                        .inscripcion_id || '') + '" data-oferta-name="Pago: ' +
                                    oferta.oferta_nombre + '">' +
                                    '<i class="ri-bank-card-line"></i>' +
                                    '</button>' : '') +
                                '<a href="/admin/estudiantes/' + est.estudiante_id +
                                '/detalle?oferta=' + oferta.oferta_id +
                                '" class="btn btn-sm" style="background: #fc7b04; color: white; border-radius: 6px; padding: 4px 10px;" title="Ver Detalle">' +
                                '<i class="ri-eye-line"></i>' +
                                '</a>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        });

                        ofertasHtml += '</div></div>';
                    }

                    return '<div class="col-lg-6 col-xl-6">' +
                        '<div class="card border-0 h-100" style="border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">' +
                        '<div class="card-body p-3">' +
                        '<div class="d-flex justify-content-between align-items-start mb-2">' +
                        '<div class="d-flex align-items-center gap-2">' +
                        '<div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #fc7b04, #e66a00);">' +
                        '<span class="text-white fw-bold">' + est.nombre_completo.charAt(0) + '</span>' +
                        '</div>' +
                        '<div>' +
                        '<a href="/admin/estudiantes/' + est.estudiante_id +
                        '/detalle" class="text-decoration-none fw-bold" style="color: #1e293b;">' + est
                        .nombre_completo + '</a>' +
                        '<div class="d-flex align-items-center gap-2">' +
                        '<span class="badge fs-9 fw-semibold text-white" style="background: #16a34a; padding: 2px 8px; border-radius: 4px;">' +
                        (est.carnet || '—') + '</span>' +
                        '<span class="badge fs-9" style="background: ' + estadoColor + '20; color: ' +
                        estadoColor + '; padding: 2px 8px; border-radius: 4px;">' + estadoBadge +
                        '</span>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<a href="/admin/estudiantes/' + est.estudiante_id +
                        '/detalle" class="btn btn-sm" style="background: #1e293b; color: white; border-radius: 8px; width: 32px; height: 32px; padding: 0;">' +
                        '<i class="ri-eye-line"></i>' +
                        '</a>' +
                        '</div>' +

                        '<div class="d-flex justify-content-between align-items-center py-2 px-2 rounded-2 mb-2" style="background: #f8fafc;">' +
                        '<div class="text-center">' +
                        '<div class="fs-9 text-muted">Celular</div>' +
                        (est.celular ? '<a href="tel:' + est.celular +
                            '" class="text-success text-decoration-none fw-semibold">' + est.celular +
                            '</a>' : '<span class="text-muted">—</span>') +
                        '</div>' +
                        '<div class="text-center">' +
                        '<div class="fs-9 text-muted">Correo</div>' +
                        (est.correo ? '<a href="mailto:' + est.correo +
                            '" class="text-primary text-decoration-none fw-semibold" style="font-size: 0.7rem;">' +
                            (est.correo.length > 15 ? est.correo.substring(0, 15) + '...' : est.correo) +
                            '</a>' : '<span class="text-muted">—</span>') +
                        '</div>' +
                        '</div>' +

                        '<div class="row g-2 text-center">' +
                        '<div class="col-4">' +
                        '<div class="p-2 rounded-2" style="background: #f1f5f9;">' +
                        '<div class="fs-9 text-muted">Total Plan</div>' +
                        '<div class="fw-bold" style="color: #1e293b;">Bs. ' + parseFloat(est.total_plan)
                        .toFixed(2) + '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-4">' +
                        '<div class="p-2 rounded-2" style="background: #dcfce7;">' +
                        '<div class="fs-9 text-success">Pagado</div>' +
                        '<div class="fw-bold text-success">Bs. ' + parseFloat(est.total_pagado).toFixed(2) +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-4">' +
                        '<div class="p-2 rounded-2" style="background: ' + estadoColor + '15;">' +
                        '<div class="fs-9" style="color: ' + estadoColor + ';">Saldo</div>' +
                        '<div class="fw-bold" style="color: ' + estadoColor + ';">Bs. ' + parseFloat(est
                            .saldo).toFixed(2) + '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="d-flex justify-content-end gap-2 mt-2">' +
                        (est.saldo > 0 ?
                            '<button type="button" class="btn btn-sm fw-semibold btn-pagar-estudiante" style="background: #22c55e; color: white; border-radius: 8px; padding: 6px 16px;" data-estudiante-id="' +
                            est.estudiante_id + '" data-oferta-name="Pago General">' +
                            '<i class="ri-bank-card-line me-1"></i>Pagar' +
                            '</button>' : '') +
                        '<a href="/admin/estudiantes/' + est.estudiante_id +
                        '/detalle" class="btn btn-sm fw-semibold" style="background: #1e293b; color: white; border-radius: 8px; padding: 6px 16px;">' +
                        '<i class="ri-eye-line me-1"></i>Ver Detalle' +
                        '</a>' +
                        '</div>' +

                        ofertasHtml +
                        '</div>' +
                        '</div>' +
                        '</div>';
                }).join('');
            }

            buscarInput.addEventListener('input', function() {
                buscarEstudiantes(this.value);
            });

            btnBuscar.addEventListener('click', function() {
                buscarEstudiantes(buscarInput.value);
            });

            buscarInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    buscarEstudiantes(this.value);
                }
            });
        });

        // ===== TOAST =====
        function toast(tipo, mensaje) {
            const iconMap = {
                success: 'ri-checkbox-circle-line',
                error: 'ri-error-warning-line',
                warning: 'ri-alert-line'
            };
            const el = document.createElement('div');
            el.className = 'toast-notify ' + tipo;
            el.innerHTML = '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') +
                '"></i></div>' +
                '<div class="toast-body-text"><span>' + mensaje + '</span></div>';
            let c = document.getElementById('toastContainer');
            if (!c) {
                c = document.createElement('div');
                c.id = 'toastContainer';
                c.className = 'toast-container';
                document.body.appendChild(c);
            }
            c.appendChild(el);
            setTimeout(() => {
                el.classList.add('show');
            }, 10);
            setTimeout(() => {
                el.classList.add('hiding');
                setTimeout(() => el.remove(), 300);
            }, 4000);
        }

        // Modal Pago Masivo
        let cuotaData = [];
        let deudaTotalGlobal = 0;
        const modalPagoMasivo = new bootstrap.Modal(document.getElementById('modalPagoMasivo'));

        function abrirModalPago(estudianteId, inscripcionId, ofertaNombre) {
            document.getElementById('pago-masivo-estudiante-id').value = estudianteId;
            document.getElementById('pago-masivo-inscripcion-id').value = inscripcionId || '';
            document.getElementById('pago-masivo-oferta').textContent = ofertaNombre || 'Pago General';
            document.getElementById('pago-masivo-trabajador-cargo').value = document.getElementById(
                'current-trabajador-cargo').value;
            document.getElementById('pago-masivo-monto').value = '';
            document.getElementById('pago-masivo-descuento').value = '0';
            const nowLaPazBuscar = new Date(new Date().toLocaleString('en-US', { timeZone: 'America/La_Paz' }));
            const yearBuscar = String(nowLaPazBuscar.getFullYear());
            const monthBuscar = String(nowLaPazBuscar.getMonth() + 1).padStart(2, '0');
            const dayBuscar = String(nowLaPazBuscar.getDate()).padStart(2, '0');
            document.getElementById('pago-masivo-fecha').value = yearBuscar + '-' + monthBuscar + '-' + dayBuscar;
            document.getElementById('pago-masivo-metodo').value = '';
            document.getElementById('pago-masivo-efectivo').value = '';
            document.getElementById('pago-masivo-qr').value = '';
            document.getElementById('pago-masivo-campo-efectivo').style.display = 'none';
            document.getElementById('pago-masivo-campo-qr').style.display = 'none';

            fetch('/admin/estudiantes/' + estudianteId + '/cuotas-json')
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    // Set inscripcion_id from first cuota if not set
                    if (!inscripcionId && data.length > 0) {
                        for (var i = 0; i < data.length; i++) {
                            if (data[i].inscripcion_id) {
                                document.getElementById('pago-masivo-inscripcion-id').value = data[i].inscripcion_id;
                                break;
                            }
                        }
                    }

                    var lista = document.getElementById('pago-masivo-lista-cuotas');

                    // Ordenar cuotas por concepto
                    var cuotasOrdenadas = [];
                    ['Matrícula', 'Colegiatura', 'Certificación'].forEach(function(concBuscada) {
                        data.forEach(function(cuota) {
                            if (cuota.estado !== 'Pagado') {
                                var nombreRaw = (cuota.nombre || '').toLowerCase();
                                var esConcepto = false;
                                if (concBuscada === 'Matrícula' && nombreRaw.includes('matr'))
                                    esConcepto = true;
                                else if (concBuscada === 'Colegiatura' && nombreRaw.includes('coleg'))
                                    esConcepto = true;
                                else if (concBuscada === 'Certificación' && nombreRaw.includes(
                                    'certif')) esConcepto = true;

                                if (esConcepto) {
                                    cuota.concepto = concBuscada;
                                    cuotasOrdenadas.push(cuota);
                                }
                            }
                        });
                    });

                    // Agregar las demás cuotas al final
                    data.forEach(function(cuota) {
                        if (cuota.estado !== 'Pagado' && !cuota.concepto) {
                            cuota.concepto = 'Otro';
                            cuotasOrdenadas.push(cuota);
                        }
                    });

                    var total = 0;
                    var html = '<h6 class="text-muted mb-3"><i class="ri-install-line"></i> Cuotas Pendientes</h6>';
                    html += '<table class="table table-sm table-hover" id="tabla-cuotas-pendientes"><thead><tr>';
                    html +=
                        '<th>#</th><th>Concepto</th><th>Cuota</th><th>Monto</th><th>Pendiente</th><th>A Pagar</th><th>Vencimiento</th><th>Estado</th>';
                    html += '</tr></thead><tbody>';

                    cuotasOrdenadas.forEach(function(cuota, idx) {
                        var concepto = cuota.concepto || 'Otro';
                        var color = concepto === 'Matrícula' ? '#2563eb' : (concepto === 'Colegiatura' ?
                            '#0891b2' : (concepto === 'Certificación' ? '#d97706' : '#64748b'));
                        var pendiente = parseFloat(cuota.pago_pendiente_bs) || 0;

                        html += '<tr class="cuota-row" data-concepto="' + concepto + '" data-cuota-id="' + cuota
                            .id + '" data-pendiente="' + pendiente + '">';
                        html += '<td>' + (idx + 1) + '</td>';
                        html += '<td><span class="badge fw-semibold" style="background: ' + color +
                            '20; color: ' + color + ';">' + concepto + '</span></td>';
                        html += '<td>' + cuota.nombre + '</td>';
                        html += '<td>Bs. ' + parseFloat(cuota.monto_bs).toFixed(2) + '</td>';
                        html += '<td class="text-warning fw-bold" data-pendiente="' + pendiente + '">Bs. ' +
                            pendiente.toFixed(2) + '</td>';
                        html += '<td class="text-success fw-bold a-pagar-cell">—</td>';
                        html += '<td>' + (cuota.fecha_vencimiento ? new Date(cuota.fecha_vencimiento)
                            .toLocaleDateString('es-ES') : '—') + '</td>';
                        html += '<td><span class="badge bg-warning text-dark">' + cuota.estado + '</span></td>';
                        html += '</tr>';
                        total += pendiente;
                    });

                    html += '</tbody></table>';
                    html += '</tbody></table>';
                    lista.innerHTML = html;
                    window.cuotaData = data;
                    window.deudaTotalGlobal = total;
                    document.getElementById('pago-masivo-deuda-total').textContent = 'Bs. ' + total.toFixed(2);
                    actualizarResumenPagoMasivo();
                });

            modalPagoMasivo.show();
        }

        function actualizarResumenPagoMasivo() {
            var monto = parseFloat(document.getElementById('pago-masivo-monto').value) || 0;
            var descuento = parseFloat(document.getElementById('pago-masivo-descuento').value) || 0;
            var metodo = document.getElementById('pago-masivo-metodo').value;

            var montoIngresado = monto;
            if (metodo === 'Parcial') {
                montoIngresado = (parseFloat(document.getElementById('pago-masivo-efectivo').value) || 0) +
                    (parseFloat(document.getElementById('pago-masivo-qr').value) || 0);
            }

            var totalIngresado = Math.round((montoIngresado + descuento) * 100) / 100;
            var nuevaDeuda = Math.max(0, Math.round((window.deudaTotalGlobal - totalIngresado) * 100) / 100);

            document.getElementById('pago-masivo-monto-ingresado').textContent = 'Bs. ' + totalIngresado.toFixed(2);
            document.getElementById('pago-masivo-nueva-deuda').textContent = 'Bs. ' + nuevaDeuda.toFixed(2);

            // Marcar cuotas según monto
            var tabla = document.getElementById('tabla-cuotas-pendientes');
            if (tabla) {
                var filas = tabla.querySelectorAll('.cuota-row');
                var remaining = totalIngresado;
                filas.forEach(function(fila) {
                    fila.style.background = '';
                    var cellAPagar = fila.querySelector('.a-pagar-cell');
                    if (cellAPagar) cellAPagar.textContent = '—';
                    var pendiente = parseFloat(fila.getAttribute('data-pendiente')) || 0;
                    if (remaining > 0 && pendiente > 0) {
                        var aPagar = Math.round(Math.min(pendiente, remaining) * 100) / 100;
                        if (aPagar > 0) {
                            fila.style.background = 'rgba(34, 197, 94, 0.15)';
                            if (cellAPagar) cellAPagar.textContent = 'Bs. ' + aPagar.toFixed(2);
                            remaining = Math.round((remaining - aPagar) * 100) / 100;
                        }
                    }
                });
            }
        }

        // Manejar botones de pagar
        document.addEventListener('click', function(e) {
            var btnOferta = e.target.closest('.btn-pagar-oferta');
            var btnEstudiante = e.target.closest('.btn-pagar-estudiante');

            if (btnOferta) {
                document.getElementById('pago-masivo-trabajador-cargo').value = document.getElementById(
                    'current-trabajador-cargo').value;
                abrirModalPago(btnOferta.dataset.estudianteId, btnOferta.dataset.inscripcionId, btnOferta.dataset
                    .ofertaName);
            } else if (btnEstudiante) {
                document.getElementById('pago-masivo-trabajador-cargo').value = document.getElementById(
                    'current-trabajador-cargo').value;
                abrirModalPago(btnEstudiante.dataset.estudianteId, '', btnEstudiante.dataset.ofertaName);
            }
        });

        document.getElementById('pago-masivo-metodo').addEventListener('change', function() {
            var metodo = this.value;
            document.getElementById('pago-masivo-campo-efectivo').style.display = (metodo === 'Parcial') ? 'block' :
                'none';
            document.getElementById('pago-masivo-campo-qr').style.display = (metodo === 'Parcial') ? 'block' :
                'none';
            actualizarResumenPagoMasivo();
        });

        document.getElementById('pago-masivo-qr').addEventListener('input', function() {
            var monto = parseFloat(document.getElementById('pago-masivo-monto').value) || 0;
            var qr = parseFloat(this.value) || 0;
            var efectivoCampo = document.getElementById('pago-masivo-efectivo');
            if (efectivoCampo) efectivoCampo.value = Math.max(0, monto - qr).toFixed(2);
            actualizarResumenPagoMasivo();
        });

        document.getElementById('pago-masivo-efectivo').addEventListener('input', function() {
            var monto = parseFloat(document.getElementById('pago-masivo-monto').value) || 0;
            var efectivo = parseFloat(this.value) || 0;
            var qrCampo = document.getElementById('pago-masivo-qr');
            if (qrCampo) qrCampo.value = Math.max(0, monto - efectivo).toFixed(2);
            actualizarResumenPagoMasivo();
        });

        document.getElementById('pago-masivo-monto').addEventListener('input', actualizarResumenPagoMasivo);
        document.getElementById('pago-masivo-descuento').addEventListener('input', actualizarResumenPagoMasivo);

        document.getElementById('formPagoMasivo').addEventListener('submit', function(e) {
            e.preventDefault();
            var estudianteId = document.getElementById('pago-masivo-estudiante-id').value;
            var inscripcionId = document.getElementById('pago-masivo-inscripcion-id').value;
            var monto = parseFloat(document.getElementById('pago-masivo-monto').value);
            var descuento = parseFloat(document.getElementById('pago-masivo-descuento').value) || 0;
            var metodo = document.getElementById('pago-masivo-metodo').value;
            var efectivo = parseFloat(document.getElementById('pago-masivo-efectivo').value) || 0;
            var qr = parseFloat(document.getElementById('pago-masivo-qr').value) || 0;
            var fechaPago = document.getElementById('pago-masivo-fecha').value;

            // Obtener cuotas seleccionadas
            var cuotasSeleccionadas = [];
            var tabla = document.getElementById('tabla-cuotas-pendientes');
            if (tabla) {
                tabla.querySelectorAll('.cuota-row').forEach(function(fila) {
                    var cellAPagar = fila.querySelector('.a-pagar-cell');
                    var cuotaId = fila.getAttribute('data-cuota-id');
                    var pendiente = parseFloat(fila.getAttribute('data-pendiente')) || 0;
                    if (cellAPagar && cellAPagar.textContent !== '—' && cellAPagar.textContent !== '') {
                        var aPagar = parseFloat(cellAPagar.textContent.replace('Bs. ', ''));
                        if (cuotaId && aPagar > 0) {
                            cuotasSeleccionadas.push({
                                id: cuotaId,
                                monto: aPagar
                            });
                        }
                    }
                });
            }

            console.log('Sending payment request:', {
                estudiante_id: estudianteId,
                inscripcion_id: inscripcionId,
                monto: monto,
                descuento: descuento,
                metodo: metodo,
                efectivo: efectivo,
                qr: qr,
                fecha_pago: fechaPago,
                cuotas: cuotasSeleccionadas
            });

            fetch('/admin/estudiantes/' + estudianteId + '/pago-masivo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        estudiante_id: estudianteId,
                        inscripcion_id: inscripcionId,
                        monto: monto,
                        descuento: descuento,
                        metodo: metodo,
                        efectivo: efectivo,
                        qr: qr,
                        fecha_pago: fechaPago,
                        cuotas: cuotasSeleccionadas,
                        trabajador_cargo_id: document.getElementById('pago-masivo-trabajador-cargo')
                            .value,
                    })
                })
                .then(function(res) {
                    return res.json().then(function(data) {
                        if (res.ok && data.success) {
                            modalPagoMasivo.hide();
                            toast('success', data.message || 'Pago registrado correctamente.');
                            buscarEstudiantes(document.getElementById('busquedaInput').value);
                            if (data.data && data.data.pago_id) {
                                document.getElementById('recibo-mensaje-exito').textContent = data
                                    .message || 'Pago registrado correctamente.';
                                document.getElementById('recibo-numero').textContent = data.data
                                    .recibo || '—';
                                document.getElementById('recibo-total').textContent = 'Bs. ' +
                                    parseFloat(data.data.total_pagado || 0).toFixed(2);
                                document.getElementById('recibo-saldo').textContent = 'Bs. ' +
                                    parseFloat(data.data.nueva_deuda || 0).toFixed(2);
                                var pagoId = data.data.pago_id;
                                document.getElementById('btn-imprimir-recibo').onclick = function() {
                                    window.open('/admin/estudiantes/recibo/' + pagoId +
                                        '/pdf?inline=1', '_blank');
                                };
                                document.getElementById('btn-descargar-recibo').onclick = function() {
                                    window.open('/admin/estudiantes/recibo/' + pagoId + '/pdf',
                                        '_blank');
                                };
                                var modalRecibo = new bootstrap.Modal(document.getElementById(
                                    'modalReciboPago'));
                                modalRecibo.show();
                            }
                        } else {
                            console.error('Error response:', data);
                            toast('error', data.message || 'Error al registrar el pago.');
                        }
                    });
                })
                .catch(function(err) {
                    console.error('Fetch error:', err);
                    toast('error', 'Error al registrar el pago. Intente nuevamente.');
                });
        });
    </script>
@endsection
