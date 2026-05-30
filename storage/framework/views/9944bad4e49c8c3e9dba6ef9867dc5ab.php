<?php $__env->startSection('title'); ?> Cajas <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<style>
@keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
@keyframes slideBar { from { width:0%; } to { width:100%; } }
.anim-fade { animation:fadeUp 0.5s ease both; }
.delay-1 { animation-delay:0.05s; }
.delay-2 { animation-delay:0.10s; }
.delay-3 { animation-delay:0.15s; }

/* Estado Badges */
.caja-estado {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.3rem 0.85rem; border-radius: 20px;
    font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px;
    transition: all 0.2s;
}
.caja-estado.abierta {
    background: rgba(40, 167, 69, 0.12); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.2);
}
.caja-estado.cerrada {
    background: rgba(108, 117, 125, 0.08); color: #6c757d; border: 1px solid rgba(108, 117, 125, 0.15);
}

/* Monto highlight */
.monto-cell { font-weight: 700; font-variant-numeric: tabular-nums; }
.monto-cell.positivo { color: #198754; }
.monto-cell.neutral { color: var(--vz-body-color, #495057); }

/* Stats hero cards */
.hero-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.75rem; }
.hero-stat {
    background: var(--vz-card-bg, #fff); border: 1px solid var(--vz-border-color, #e2e8f0);
    border-radius: 14px; padding: 1.15rem 1.35rem;
    display: flex; align-items: center; gap: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 16px rgba(0,0,0,.04); transition: all 0.3s;
}
.hero-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(154,73,4,0.10); }
.hero-stat-icon {
    width: 42px; height: 42px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 1.1rem;
}
.hero-stat-icon.green { background: rgba(40,167,69,0.10); color: #28a745; }
.hero-stat-icon.orange { background: rgba(252,123,4,0.10); color: #fc7b04; }
.hero-stat-icon.blue { background: rgba(13,110,253,0.10); color: #0d6efd; }
.hero-stat-info h6 { margin:0 0 0.15rem; font-size:0.72rem; font-weight:600; color:var(--vz-secondary-color, #64748b); text-transform:uppercase; letter-spacing:0.4px; }
.hero-stat-info span { font-size:1.25rem; font-weight:800; color:var(--vz-heading-color, #1e293b); letter-spacing:-0.3px; }
.hero-stat-info small { font-size:0.65rem; font-weight:600; color:var(--vz-secondary-color, #64748b); }

/* Modal form styling */
.form-card {
    background: rgba(154, 73, 4, 0.04);
    border: 1px solid rgba(154, 73, 4, 0.10);
    border-radius: 12px; padding: 1.25rem;
}

@media (max-width:768px) { .hero-stats { grid-template-columns: 1fr; } }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Header -->
<div class="dept-page-header anim-fade delay-1">
    <div class="container-fluid">
        <div class="dph-inner">
            <div class="dph-left">
                <div class="dph-icon-wrap" style="background:linear-gradient(135deg,#198754 0%,#28a745 100%);box-shadow:0 4px 12px rgba(25,135,84,0.25);">
                    <i class="ri-money-dollar-box-line"></i>
                </div>
                <div class="dph-text-block">
                    <h1 class="dph-title">Cajas</h1>
                    <p class="dph-desc">Gestión de apertura, cierre y control de cajas chicas</p>
                    <ol class="dph-breadcrumb">
                        <li><i class="ri-home-4-line"></i> Finanzas</li>
                        <li class="dph-sep"><i class="ri-arrow-right-s-line"></i></li>
                        <li class="active">Cajas</li>
                    </ol>
                </div>
            </div>
            <div class="dph-right">
                <button type="button" class="dph-btn-new" id="btnAbrirCaja" style="background:linear-gradient(135deg,#198754 0%,#28a745 100%);box-shadow:0 3px 10px rgba(25,135,84,0.3);">
                    <i class="ri-add-line"></i>
                    <span>Abrir Caja</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    <!-- Stats -->
    <div class="hero-stats anim-fade delay-2">
        <div class="hero-stat">
            <div class="hero-stat-icon green"><i class="ri-checkbox-circle-line"></i></div>
            <div class="hero-stat-info">
                <h6>Cajas Abiertas</h6>
                <span><?php echo e($cajasAbiertas); ?></span>
                <small>en operación</small>
            </div>
        </div>
        <div class="hero-stat">
            <div class="hero-stat-icon orange"><i class="ri-funds-line"></i></div>
            <div class="hero-stat-info">
                <h6>Saldo en Cajas</h6>
                <span>Bs. <?php echo e(number_format($totalIngresos, 2)); ?></span>
                <small>suma de montos actuales</small>
            </div>
        </div>
        <div class="hero-stat">
            <div class="hero-stat-icon blue"><i class="ri-history-line"></i></div>
            <div class="hero-stat-info">
                <h6>Total Registradas</h6>
                <span><?php echo e($totalCajas); ?></span>
                <small>cajas creadas</small>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="dept-card anim-fade delay-3">
        <div class="dept-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="dept-header-icon" style="background:linear-gradient(135deg,#198754 0%,#28a745 100%);">
                    <i class="ri-table-line"></i>
                </div>
                <div>
                    <h5 class="dept-title">Listado de Cajas</h5>
                    <p class="dept-subtitle">Consulta el historial y estado de cada caja</p>
                </div>
            </div>
        </div>
        <div class="dept-card-body">
            <table id="tabla-cajas" class="dept-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Responsable</th>
                        <th>Inicial</th>
                        <th>Actual</th>
                        <th>Apertura</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center" style="width:130px;">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===================== MODAL ABRIR CAJA ===================== -->
<div class="modal fade" id="modalAbrirCaja" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#145c32 0%,#198754 40%,#28a745 100%) !important;">
                <h5 class="modal-title">
                    <i class="ri-add-circle-line"></i> Abrir Caja
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formAbrirCaja" novalidate autocomplete="off">
                <div class="modal-body">
                    <div class="form-card">
                        <div class="mb-3">
                            <label for="trabajadorAbrir" class="form-label">
                                <i class="ri-user-line" style="color:#fc7b04;"></i>
                                Responsable <span class="req">*</span>
                            </label>
                            <select class="form-select" id="trabajadorAbrir" required>
                                <option value="">Seleccionar responsable...</option>
                                <?php $__currentLoopData = $trabajadores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($t->id); ?>">
                                    <?php echo e($t->trabajador->persona->nombre ?? ''); ?> <?php echo e($t->trabajador->persona->apellido_paterno ?? ''); ?>

                                    <?php if($t->cargo): ?> - <?php echo e($t->cargo->nombre ?? $t->nombre_cargo); ?> <?php endif; ?>
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="montoAbrir" class="form-label">
                                <i class="ri-money-dollar-circle-line" style="color:#fc7b04;"></i>
                                Monto Inicial <span class="req">*</span>
                            </label>
                            <div class="field-wrapper">
                                <input type="number" class="form-control" id="montoAbrir"
                                       step="0.01" min="0" value="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="field-feedback mt-2" id="fbAbrir"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-modal-submit" id="btnAbrir">
                        <i class="ri-lock-unlock-line"></i> Abrir Caja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===================== MODAL CERRAR CAJA ===================== -->
<div class="modal fade" id="modalCerrar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#664d03 0%,#997404 40%,#ffc107 100%) !important;">
                <h5 class="modal-title">
                    <i class="ri-lock-line"></i> Cerrar Caja
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formCerrarCaja" novalidate autocomplete="off">
                <input type="hidden" id="idCerrarCaja">
                <div class="modal-body">
                    <div class="delete-warning-box" style="background:rgba(153, 116, 4, 0.07);border-color:rgba(153, 116, 4, 0.2);">
                        <div class="delete-icon-ring" style="border-color:rgba(153, 116, 4, 0.25);">
                            <i class="ri-lock-line" style="color:#997404;"></i>
                        </div>
                        <p class="delete-msg-primary">Cerrar caja</p>
                        <p class="delete-msg-name">
                            <strong id="nombreCerrarCaja"></strong>
                        </p>
                        <p class="delete-msg-warn" style="font-size:0.82rem;">
                            Monto actual: <strong id="montoActualCerrar"></strong>
                        </p>
                    </div>
                    <div class="mt-3">
                        <label for="montoCierre" class="form-label">
                            <i class="ri-money-dollar-circle-line" style="color:#fc7b04;"></i>
                            Monto de cierre <span class="req">*</span>
                        </label>
                        <div class="field-wrapper">
                            <input type="number" class="form-control" id="montoCierre"
                                   step="0.01" min="0" required>
                            <span class="validation-icon" id="iconCerrar"></span>
                        </div>
                        <div class="field-feedback" id="fbCerrar"></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center gap-3">
                    <button type="button" class="btn btn-modal-cancel px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger-modal px-4" id="btnCerrar" style="background:linear-gradient(135deg,#997404 0%,#ffc107 100%);color:#222;">
                        <i class="ri-lock-line"></i> Cerrar Caja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Contenedor de toasts -->
<div id="toastContainer" class="toast-container"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
(function () {
    'use strict';

    let tabla;
    let CSRF = '<?php echo e(csrf_token()); ?>';

    function init() {
        initDataTable();
        bindEvents();
    }

    function initDataTable() {
        tabla = $('#tabla-cajas').DataTable({
            ajax: { url: '<?php echo e(route("admin.cajas.listar")); ?>', dataSrc: 'data' },
            ordering: true,
            columns: [
                { data: 'nombre', render: n => '<span style="font-weight:600;">' + escHtml(n) + '</span>' },
                { data: 'trabajador', render: t => '<span style="font-size:0.82rem;">' + escHtml(t) + '</span>' },
                {
                    data: 'monto_inicial',
                    render: v => '<span class="monto-cell neutral">Bs. ' + Number(v).toLocaleString('es-BO', { minimumFractionDigits: 2 }) + '</span>',
                    className: 'text-center'
                },
                {
                    data: 'monto_actual',
                    render: v => '<span class="monto-cell positivo">Bs. ' + Number(v).toLocaleString('es-BO', { minimumFractionDigits: 2 }) + '</span>',
                    className: 'text-center'
                },
                { data: 'fecha_apertura', render: f => f ? '<span style="font-size:0.78rem;white-space:nowrap;">' + f + '</span>' : '<span style="opacity:0.3;">—</span>' },
                {
                    data: 'estado',
                    render: e => e === 'Abierta'
                        ? '<span class="caja-estado abierta"><i class="ri-checkbox-circle-line"></i> Abierta</span>'
                        : '<span class="caja-estado cerrada"><i class="ri-close-circle-line"></i> Cerrada</span>',
                    className: 'text-center'
                },
                {
                    data: null, className: 'text-center',
                    render: d => {
                        let btns = '<div class="action-cell">';
                        btns += '<a href="/admin/cajas/' + d.id + '/movimientos" class="btn btn-action" style="background:rgba(13,110,253,0.08);color:#0d6efd;border-color:rgba(13,110,253,0.18);" title="Ver movimientos"><i class="ri-history-line"></i></a>';
                        if (d.estado === 'Abierta') {
                            btns += '<button class="btn btn-action btn-cerrar-caja" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '" data-monto="' + d.monto_actual + '" style="background:rgba(255,193,7,0.10);color:#997404;border-color:rgba(255,193,7,0.20);" title="Cerrar caja"><i class="ri-lock-line"></i></button>';
                        }
                        btns += '</div>';
                        return btns;
                    }
                }
            ],
            language: {
                processing:     'Procesando...',
                search:         'Buscar:',
                lengthMenu:     'Mostrar _MENU_ registros',
                info:           'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty:      'Mostrando 0 a 0 de 0 registros',
                infoFiltered:   '(filtrado de _MAX_ registros totales)',
                zeroRecords:    'No se encontraron registros',
                emptyTable:     'No hay datos disponibles',
                paginate: { first: 'Primero', previous: 'Anterior', next: 'Siguiente', last: 'Último' }
            },
            order: [[0, 'asc']],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Todos']],
            pageLength: 10,
            drawCallback: function () {
                const info = this.api().page.info();
            }
        });
    }

    function bindEvents() {
        $('#btnAbrirCaja').on('click', () => {
            $('#formAbrirCaja')[0].reset();
            $('#trabajadorAbrir').val('');
            $('#montoAbrir').val('0');
            $('#fbAbrir').text('').removeClass('text-danger text-success');
            openModal('modalAbrirCaja');
        });

        $(document).on('click', '.btn-cerrar-caja', function () {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            const monto = $(this).data('monto');
            $('#idCerrarCaja').val(id);
            $('#nombreCerrarCaja').text(nombre);
            $('#montoActualCerrar').text('Bs. ' + Number(monto).toLocaleString('es-BO', { minimumFractionDigits: 2 }));
            $('#montoCierre').val(monto);
            $('#fbCerrar').text('').removeClass('text-danger text-success');
            resetField('montoCierre', 'iconCerrar', 'fbCerrar');
            openModal('modalCerrar');
        });

        $('#formAbrirCaja').on('submit', e => { e.preventDefault(); abrirCaja(); });
        $('#formCerrarCaja').on('submit', e => { e.preventDefault(); cerrarCaja(); });
    }

    function abrirCaja() {
        const trabajadorId = $('#trabajadorAbrir').val();
        const monto = $('#montoAbrir').val();
        const fb = $('#fbAbrir');

        if (!trabajadorId) {
            fb.text('Debe seleccionar un responsable.').addClass('text-danger').removeClass('text-success');
            return;
        }
        fb.text('').removeClass('text-danger text-success');

        setBtnLoading('#btnAbrir', true, 'Abriendo…');
        $.post('<?php echo e(route("admin.cajas.abrir")); ?>', {
            _token: CSRF,
            trabajadore_cargo_id: trabajadorId,
            monto_inicial: monto
        })
        .done(r => {
            closeModal('modalAbrirCaja');
            tabla.ajax.reload();
            toast('success', r.message || 'Caja abierta correctamente.');
        })
        .fail(xhr => {
            const msg = xhr.responseJSON?.message || 'Error al abrir caja.';
            fb.text(msg).addClass('text-danger').removeClass('text-success');
        })
        .always(() => setBtnLoading('#btnAbrir', false, '<i class="ri-lock-unlock-line"></i> Abrir Caja'));
    }

    function cerrarCaja() {
        const id = $('#idCerrarCaja').val();
        const monto = $('#montoCierre').val();

        if (!monto || parseFloat(monto) < 0) {
            showError('montoCierre', 'iconCerrar', 'fbCerrar', 'El monto de cierre es requerido.');
            return;
        }

        setBtnLoading('#btnCerrar', true, 'Cerrando…');
        $.ajax({
            url: '/admin/cajas/' + id + '/cerrar',
            type: 'POST',
            data: { _token: CSRF, monto_cierre: monto }
        })
        .done(r => {
            closeModal('modalCerrar');
            tabla.ajax.reload();
            toast('success', r.message || 'Caja cerrada correctamente.');
        })
        .fail(xhr => {
            const msg = xhr.responseJSON?.message || 'Error al cerrar caja.';
            toast('error', msg);
        })
        .always(() => setBtnLoading('#btnCerrar', false, '<i class="ri-lock-line"></i> Cerrar Caja'));
    }

    function showError(inputId, iconId, fbId, msg) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        input.classList.add('is-invalid');
        icon.className = 'validation-icon invalid';
        icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
        fb.className = 'field-feedback error';
        fb.innerHTML = '<i class="ri-error-warning-line"></i>' + msg;
    }

    function resetField(inputId, iconId, fbId) {
        const input = document.getElementById(inputId);
        input.classList.remove('is-valid', 'is-invalid');
        document.getElementById(iconId).className = 'validation-icon';
        document.getElementById(iconId).innerHTML = '';
        document.getElementById(fbId).className = 'field-feedback';
        document.getElementById(fbId).innerHTML = '';
    }

    function setBtnLoading(sel, loading, labelHtml) {
        const btn = document.querySelector(sel);
        if (!btn) return;
        btn.disabled = loading;
        if (loading) {
            btn.dataset.orig = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>' + labelHtml;
        } else {
            btn.innerHTML = labelHtml;
        }
    }

    function openModal(id) { new bootstrap.Modal(document.getElementById(id)).show(); }

    function closeModal(id) {
        const el = document.getElementById(id);
        const m = bootstrap.Modal.getInstance(el);
        if (m) m.hide();
    }

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function getToastContainer() {
        let c = document.getElementById('toastContainer');
        if (c && c.parentElement !== document.body) document.body.appendChild(c);
        return c;
    }

    function toast(tipo, mensaje) {
        const iconMap = { success: 'ri-check-double-line', error: 'ri-close-circle-line', warning: 'ri-alert-line' };
        const el = document.createElement('div');
        el.className = 'toast-notify ' + tipo;
        el.innerHTML = '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') + '"></i></div>'
            + '<div class="toast-body-text"><span>' + mensaje + '</span></div>'
            + '<button class="toast-close" title="Cerrar"><i class="ri-close-line"></i></button>';
        const c = getToastContainer();
        c.style.transition = 'top 0.3s ease';
        c.style.top = Math.max(20, window.scrollY + 20) + 'px';
        if (!c._scrollListener) {
            c._scrollListener = true;
            let t; window.addEventListener('scroll', () => { clearTimeout(t); t = setTimeout(() => { c.style.top = Math.max(20, window.scrollY + 20) + 'px'; }, 10); });
        }
        c.appendChild(el);
        el.querySelector('.toast-close').addEventListener('click', () => removeToast(el));
        setTimeout(() => removeToast(el), 4500);
    }

    function removeToast(el) {
        el.classList.add('hiding');
        el.addEventListener('animationend', () => el.remove(), { once: true });
    }

    $(document).ready(init);
})();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/cajas/index.blade.php ENDPATH**/ ?>