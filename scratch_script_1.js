<script src="null"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    (function() {
        'use strict';
        const CSRF = 'null';
        window.OFERTA_ID = null;
        window.CANTIDAD_SESIONES = null;
        window.CSRF = CSRF;
        let currentModuloId = null;
        let currentHorarioId = null;
        let allModulos = [];
        let moduloColors = {};
        let calendar = null;
        let selectedModuloFilter = null;

        // ===== UTILIDADES =====
        function toast(tipo, mensaje) {
            const iconMap = {
                success: 'ri-check-double-line',
                error: 'ri-close-circle-line',
                warning: 'ri-alert-line'
            };
            const el = document.createElement('div');
            el.className = 'toast-notify ' + tipo;
            el.innerHTML = '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') +
                '"></i></div><div class="toast-body-text"><span>' + mensaje +
                '</span></div><button class="toast-close" title="Cerrar"><i class="ri-close-line"></i></button>';
            let c = document.getElementById('toastContainer');
            if (!c) {
                c = document.createElement('div');
                c.id = 'toastContainer';
                c.style.cssText =
                    'position:fixed;top:1.5rem;right:1.5rem;z-index:10050;display:flex;flex-direction:column;gap:0.6rem;max-width:380px;';
                document.body.appendChild(c);
            }
            c.appendChild(el);
            el.querySelector('.toast-close').addEventListener('click', () => {
                el.classList.add('hiding');
                el.addEventListener('animationend', () => el.remove(), {
                    once: true
                });
            });
            setTimeout(() => {
                el.classList.add('hiding');
                el.addEventListener('animationend', () => el.remove(), {
                    once: true
                });
            }, 4500);
        }
        
        // Exponer función toast globalmente
        window.toast = toast;

        function setBtnLoading(sel, loading, labelHtml) {
            const btn = document.querySelector(sel);
            if (!btn) return;
            btn.disabled = loading;
            if (loading) {
                btn.dataset.orig = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>' +
                    labelHtml;
            } else {
                btn.innerHTML = btn.dataset.orig || labelHtml;
            }
        }

        function escHtml(str) {
            return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(
                /"/g, '&quot;');
        }

        function toCamelCase(str) {
            if (!str) return '';
            return str.toLowerCase().replace(/(?:^|\s)\w/g, function(match) {
                return match.toUpperCase();
            });
        }

        function formatDate(str) {
            if (!str) return '—';
            const p = str.split('-');
            return p[2] + '/' + p[1] + '/' + p[0];
        }

        function formatDateLong(str) {
            if (!str) return '';
            const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre',
                'octubre', 'noviembre', 'diciembre'
            ];
            const p = str.split('-');
            return parseInt(p[2]) + ' de ' + meses[parseInt(p[1]) - 1] + ' del ' + p[0];
        }

        function formatTime(str) {
            if (!str) return '—';
            return str.substring(0, 5);
        }

        function openModal(id) {
            const el = document.getElementById(id);
            if (!el) return;
            const existing = bootstrap.Modal.getInstance(el);
            if (existing) existing.show();
            else new bootstrap.Modal(el).show();
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (!el) return;
            const existing = bootstrap.Modal.getInstance(el);
            if (existing) existing.hide();
        }

        // ===== TABS =====
        document.querySelectorAll('.oferta-tab').forEach(function(tab) {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.oferta-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content-section').forEach(s => s.classList.remove(
                    'active'));
                this.classList.add('active');
                const target = this.getAttribute('data-tab');
                document.getElementById('tab-' + target).classList.add('active');
                if (target === 'modulos') {
                    cargarModulosSidebar();
                    initCalendar();
                    cargarTrabajadoresLista();
                }
                if (target === 'inscripciones') cargarInscripciones();
                if (target === 'plataforma') cargarControlAcceso(false);
            });
        });

        // ===== MÓDULOS & CALENDARIO =====
        function cargarModulosSidebar() {
            $.ajax({
                    url: '/admin/posgrads/ofertas/' + window.OFERTA_ID + '/modulos/listar'
                })
                .done(function(r) {
                    allModulos = r.data || [];
                    renderModulosSidebar();
                })
                .fail(function() {
                    $('#modulosSidebarList').html(
                        '<div class="text-center text-muted py-4"><i class="ri-error-warning-line"></i> Error al cargar</div>'
                    );
                });
        }

        function renderModulosSidebar() {
            const $list = $('#modulosSidebarList');
            if (!allModulos.length) {
                $list.html(
                    '<div class="text-center text-muted py-4"><i class="ri-inbox-line"></i> Sin módulos</div>');
                return;
            }
            let html = '';
            allModulos.forEach(function(mod) {
                const color = mod.color || '#6366f1';
                const docenteNombre = mod.docente && mod.docente.persona ? (mod.docente.persona.nombres ||
                    '') + ' ' + (mod.docente.persona.apellido_paterno || '') : 'Sin docente';
                const horariosCount = mod.horarios ? mod.horarios.length : 0;
                const tieneCursoMoodle = mod.moodle_course_id ? true : false;
                const badgeMoodle = tieneCursoMoodle
                    ? '<span class="msi-moodle-badge" title="Vinculado a Moodle"><i class="ri-links-line"></i></span>'
                    : '';
                html += '<div class="modulo-sidebar-item" data-modulo-id="' + mod.id +
                    '"><div class="msi-color" style="background:' + color +
                    ';"></div><div class="msi-info"><div class="msi-name">' + escHtml(mod.nombre) + badgeMoodle +
                    '</div><div class="msi-docente">' + escHtml(docenteNombre.trim()) +
                    '</div></div><div class="msi-sessions">' + horariosCount + '/' + window.CANTIDAD_SESIONES +
                    '</div><div class="msi-actions">' +
                    '<a href="/admin/posgrads/ofertas/' + window.OFERTA_ID + '/modulos/' + mod.id + '/detalle" class="msi-btn btn-ver-modulo" title="Ver detalle"><i class="ri-eye-line"></i></a>' +
                    '<button class="msi-btn btn-edit-modulo" data-id="' + mod.id + '" title="Editar"><i class="ri-pencil-fill"></i></button>' +
                    '<button class="msi-btn msi-btn-add btn-asignar-horario" data-id="' + mod.id + '" title="Asignar horario"><i class="ri-add-line"></i></button>' +
                    '<button class="msi-btn msi-btn-moodle btn-matricular-moodle" data-id="' + mod.id + '" data-nombre="' + escHtml(mod.nombre) + '" title="Matricular estudiantes en Moodle"><i class="ri-user-add-line"></i></button>' +
                    '</div></div>';
            });
            $list.html(html);
        }
        $(document).on('click', '.modulo-sidebar-item', function(e) {
            if ($(e.target).closest('.msi-actions').length) return;
            const moduloId = $(this).data('modulo-id');
            const moduloNombre = $(this).find('.msi-name').text();
            const moduloColor = $(this).find('.msi-color').css('background-color');
            $('.modulo-sidebar-item').removeClass('active');
            $(this).addClass('active');
            $('#btnTodosModulos').removeClass('active');
            selectedModuloFilter = moduloId;
            actualizarBadgeModuloSeleccionado(moduloId, moduloNombre, moduloColor);
            refreshCalendar();
        });
        $('#btnTodosModulos').on('click', function() {
            $('.modulo-sidebar-item').removeClass('active');
            $(this).addClass('active');
            selectedModuloFilter = null;
            ocultarBadgeModuloSeleccionado();
            refreshCalendar();
        });

        function actualizarBadgeModuloSeleccionado(moduloId, nombre, color) {
            const badge = $('#moduloSeleccionadoBadge');
            if (moduloId && nombre) {
                badge.find('.modulo-badge-color').css('background-color', color);
                badge.find('.modulo-badge-name').text(nombre);
                badge.show();
            } else {
                badge.hide();
            }
        }

        function ocultarBadgeModuloSeleccionado() {
            $('#moduloSeleccionadoBadge').hide();
        }

        $('#moduloSeleccionadoBadge .modulo-badge-close').on('click', function(e) {
            e.stopPropagation();
            $('.modulo-sidebar-item').removeClass('active');
            $('#btnTodosModulos').addClass('active');
            selectedModuloFilter = null;
            ocultarBadgeModuloSeleccionado();
            refreshCalendar();
        });

        function initCalendar() {
            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;
            if (calendar) calendar.destroy();
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    list: 'Lista'
                },
                editable: false,
                selectable: true,
                eventColor: null,
                eventDidMount: function(info) {
                    const moduloId = info.event.extendedProps?.modulo_id;
                    const color = moduloColors[moduloId] || info.event.backgroundColor || '#6366f1';
                    info.el.style.setProperty('background-color', color, 'important');
                    info.el.style.setProperty('border-color', color, 'important');
                    info.el.style.background = color + ' !important';
                    info.el.style.borderColor = color + ' !important';
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    loadCalendarEvents(successCallback);
                },
                eventClick: function(info) {
                    openDetalleHorario(info.event);
                },
                dateClick: function(info) {
                    if (selectedModuloFilter) openAsignarHorario(selectedModuloFilter, info.dateStr);
                }
            });
            calendar.render();
            refreshCalendar();
        }

        function loadCalendarEvents(callback) {
            $.ajax({
                    url: '/admin/posgrads/ofertas/' + window.OFERTA_ID + '/modulos/listar'
                })
                .done(function(r) {
                    moduloColors = {};
                    const modulos = r.data || [];
                    modulos.forEach(function(mod) {
                        moduloColors[mod.id] = mod.color || '#6366f1';
                    });
                    const events = [];
                    modulos.forEach(function(mod) {
                        if (selectedModuloFilter && mod.id !== selectedModuloFilter) return;
                        (mod.horarios || []).forEach(function(h) {
                            const color = h.color || mod.color || '#6366f1';
                            const fechaStr = h.fecha ? String(h.fecha).substring(0, 10) : '';
                            if (!fechaStr) return;
                            events.push({
                                id: 'h-' + h.id,
                                title: mod.nombre + ' (' + formatTime(h.hora_inicio) +
                                    '-' + formatTime(h.hora_fin) + ')',
                                start: fechaStr + 'T' + (h.hora_inicio || '00:00'),
                                end: fechaStr + 'T' + (h.hora_fin || '00:00'),
                                backgroundColor: color,
                                borderColor: color,
                                color: '#ffffff',
                                extendedProps: {
                                    horario_id: h.id,
                                    modulo_id: mod.id,
                                    modulo_nombre: mod.nombre,
                                    modulo_color: color,
                                    fecha: fechaStr,
                                    hora_inicio: h.hora_inicio,
                                    hora_fin: h.hora_fin,
                                    docente_nombre: (mod.docente && mod.docente
                                            .persona) ? (mod.docente.persona.nombres ||
                                            '') + ' ' + (mod.docente.persona
                                            .apellido_paterno || '') + ' ' + (mod
                                            .docente.persona.apellido_materno || '') :
                                        'Sin asignar',
                                    estado: h.estado || 'Confirmado',
                                    trabajador_id: h.trabajadores_cargo_id || '',
                                    trabajador: h.trabajador_cargo ? (h.trabajador_cargo
                                        .nombre_cargo || '') : ''
                                }
                            });
                        });
                    });
                    callback(events);
                }).fail(function() {
                    callback([]);
                });
        }

        function refreshCalendar() {
            if (calendar) calendar.refetchEvents();
        }

        // ===== INSCRIPCIONES =====
        function cargarInscripciones() {
            $('#inscripcionesLoading').show();
            $('#inscripcionesEmpty').hide();
            $('#tabla-inscripciones').hide();
            $.ajax({
                    url: '/admin/ofertas-academicas/' + window.OFERTA_ID + '/inscripciones',
                    type: 'GET'
                })
                .done(function(response) {
                    $('#inscripcionesLoading').hide();
                    const inscripciones = response.data || [];
                    if (!inscripciones.length) {
                        $('#inscripcionesEmpty').show();
                        return;
                    }
                    renderInscripcionesTable(inscripciones, 'todos');
                    $('#tabla-inscripciones').show();
                })
                .fail(function() {
                    $('#inscripcionesLoading').hide();
                    $('#inscripcionesEmpty').show();
                    toast('error', 'Error al cargar las inscripciones');
                });
        }

        function renderInscripcionesTable(inscripciones, filter) {
            const $tbody = $('#inscripcionesTableBody');
            let filtered = inscripciones;

            // Aplicar filtro por estado
            if (filter === 'Inscrito') {
                filtered = inscripciones.filter(i => i.estado === 'Inscrito');
            } else if (filter === 'Pre-Inscrito') {
                filtered = inscripciones.filter(i => i.estado === 'Pre-Inscrito');
            }

            // Ordenar alfabéticamente por apellidos y nombre
            filtered.sort(function(a, b) {
                const aAp = (a.estudiante_nombre || '').toLowerCase();
                const bAp = (b.estudiante_nombre || '').toLowerCase();
                if (aAp > bAp) return 1;
                if (aAp < bAp) return -1;
                return 0;
            });

            if (!filtered.length) {
                $tbody.html(
                    '<tr><td colspan="8" class="text-center text-muted py-4">No hay inscripciones</td></tr>'
                );
                return;
            }

            let html = '';
            filtered.forEach(function(ins, idx) {
                const nombres = (ins.estudiante_nombre || '').trim() || '—';
                const ci = ins.estudiante_ci || '—';
                const celular = ins.celular || '—';
                const correo = ins.correo || '—';
                const plan = ins.plan_pago || '—';
                const estado = ins.estado || '—';
                const estadoClass = estado === 'Inscrito' ? 'inscrito' : 'pre-inscrito';
                
                // Formatear fecha: "12/04/2026 14:30" -> "12 de abril del 2026"
                let fechaFormateada = '—';
                if (ins.fecha_registro) {
                    const fechaStr = String(ins.fecha_registro).trim();
                    const partes = fechaStr.split(' ')[0].split('/');
                    const dia = parseInt(partes[0], 10);
                    const mes = parseInt(partes[1], 10) - 1;
                    const anio = parseInt(partes[2], 10);
                    if (!isNaN(dia) && !isNaN(mes) && !isNaN(anio) && mes >= 0 && mes <= 11 && dia > 0 && dia <= 31) {
                        const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
                        fechaFormateada = dia + ' de ' + meses[mes] + ' del ' + anio;
                    }
                }
                
                const trabajador = ins.trabajador || '—';

                // Botón para ver detalle del estudiante
                const btnDetalle = ins.estudiante_id ? 
                    '<a href="/admin/estudiantes/' + ins.estudiante_id + '/detalle" class="btn btn-action btn-action-view" title="Ver Detalle del Estudiante"><i class="ri-eye-line"></i></a>' :
                    '<button type="button" class="btn btn-action" title="Sin estudiante" disabled style="opacity:0.3;cursor:not-allowed;"><i class="ri-eye-line"></i></button>';

                // Botón para cambiar de Pre-Inscrito a Inscrito
                const btnCambiar = estado === 'Pre-Inscrito' ?
                    '<button type="button" class="btn btn-action btn-action-edit cambiar-a-inscrito" title="Cambiar a Inscrito" data-inscripcion-id="' +
                    ins.id + '" data-estudiante="' + escHtml(nombres) + '" data-ci="' + escHtml(ci) +
                    '" data-plan-pago-id="' + (ins.plan_pago_id || '') +
                    '"><i class="ri-user-star-line"></i></button>' :
                    '';

                const celularLimpio = celular.replace(/[^0-9]/g, '');
                const tieneCuenta = ins.tiene_cuenta_moodle;
                const tieneSistema = ins.tiene_cuenta_sistema;
                const sistemaStatus = tieneSistema
                    ? '<span class="moodle-status tiene-cuenta"><i class="ri-check-line"></i> Activa</span>'
                    : '<span class="moodle-status sin-cuenta"><i class="ri-close-line"></i> Sin cuenta</span>';
                const moodleStatus = tieneCuenta
                    ? '<span class="moodle-status tiene-cuenta"><i class="ri-check-line"></i> Cuenta</span>'
                    : '<span class="moodle-status sin-cuenta"><i class="ri-close-line"></i> Sin cuenta</span>';
                
                let btnWhatsapp = '';
                if (celularLimpio.length >= 8) {
                    if (tieneCuenta) {
                        btnWhatsapp = '<button type="button" class="btn btn-action btn-whatsapp-moodle" title="Enviar accesos por WhatsApp" ' +
                            'data-celular="' + celularLimpio + '" ' +
                            'data-nombre="' + escHtml(nombres) + '" ' +
                            'data-programa="' + escHtml(ins.programa_nombre || '') + '" ' +
                            'data-username="' + escHtml(ins.moodle_username || '') + '" ' +
                            'data-password="' + escHtml(ins.moodle_password || '') + '" ' +
                            '><i class="ri-whatsapp-line"></i></button>';
                    } else {
                        btnWhatsapp = '<button type="button" class="btn btn-action" title="Sin cuenta en Moodle" disabled style="opacity:0.3;cursor:not-allowed;"><i class="ri-whatsapp-line"></i></button>';
                    }
                } else {
                    btnWhatsapp = '<button type="button" class="btn btn-action" title="Sin celular" disabled style="opacity:0.3;cursor:not-allowed;"><i class="ri-whatsapp-line"></i></button>';
                }

                html += '<tr>' +
                    '<td class="text-center text-muted">' + (idx + 1) + '</td>' +
                    '<td class="px-2 py-2">' +
                        '<div class="d-flex flex-column">' +
                            '<a href="/admin/estudiantes/' + (ins.estudiante_id || '') + '/detalle" class="text-decoration-none fw-semibold" style="color: #1e293b;">' + toCamelCase(escHtml(nombres)) + '</a>' +
                            '<span class="badge fs-9 fw-semibold text-white" style="background: #16a34a; width: fit-content; margin-top: 2px;">' + escHtml(ci) + '</span>' +
                        '</div>' +
                    '</td>' +
                    '<td class="text-center">' + escHtml(celular) + '</td>' +
                    '<td style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' + escHtml(correo) + '</td>' +
                    '<td class="text-center"><span class="badge fs-9 fw-semibold" style="background: #fc7b0415; color: #fc7b04;">' + escHtml(plan) + '</span></td>' +
                    '<td class="text-center"><span class="inscrito-estado ' + estadoClass + '">' + estado + '</span></td>' +
                    '<td class="text-center">' + sistemaStatus + '</td>' +
                    '<td class="text-center">' + moodleStatus + '</td>' +
                    '<td class="text-center"><div style="display: flex; gap: 0.25rem; justify-content: center;">' + btnDetalle + btnCambiar + btnWhatsapp + '</div></td>' +
                    '</tr>';
            });

            $tbody.html(html);
        }

        document.querySelectorAll('.btn-filtro-inscripciones').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.btn-filtro-inscripciones').forEach(b => b.classList
                    .remove('active'));
                this.classList.add('active');
                const filter = this.getAttribute('data-filter');
                $.ajax({
                        url: '/admin/ofertas-academicas/' + window.OFERTA_ID + '/inscripciones',
                        type: 'GET'
                    })
                    .done(function(response) {
                        renderInscripcionesTable(response.data || [], filter);
                    });
            });
        });

        $(document).on('click', '.btn-whatsapp-moodle', function(e) {
            e.stopPropagation();
            const btn = $(this);
            const celular = btn.data('celular');
            const nombre = btn.data('nombre');
            const programa = btn.data('programa');
            const username = btn.data('username');
            const password = btn.data('password');

            const mensaje = '*¡Bienvenido/a a ' + programa + '!*\n\n' +
                'Estimado/a ' + nombre + ',\n\n' +
                'Su inscripción ha sido registrada exitosamente. A continuación, le proporcionamos sus datos de acceso a la plataforma:\n\n' +
                '*Plataforma:* http://moodle52.localhost/\n' +
                '*Usuario:* ' + username + '\n' +
                '*Contraseña:* ' + password + '\n\n' +
                '*Área Académica Innova-Ciencia-Virtual*';

            const url = 'https://wa.me/' + celular + '?text=' + encodeURIComponent(mensaje);
            window.open(url, '_blank');
        });

        let currentParticipanteId = null;
        let currentParticipanteNombre = '';
        let currentPlanPagoId = null;

        $(document).on('click', '.ver-plan-participante', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const btn = $(this);
            currentParticipanteId = btn.data('inscripcion-id');
            currentParticipanteNombre = btn.data('estudiante');
            currentPlanPagoId = btn.data('plan-pago-id');

            // Mostrar loading y resetear el contenedor
            $('#planesParticipanteLoading').show();
            $('#planesParticipanteEmpty').hide();
            $('#planesParticipanteContainer').empty();

            // Actualizar el título del modal con el nombre del estudiante
            $('#planesParticipanteNombre').text(currentParticipanteNombre);

            // Petición AJAX para obtener los datos del plan de pagos
            $.ajax({
                    url: '/admin/posgrads/ofertas/' + window.OFERTA_ID + '/participante/' +
                        currentParticipanteId + '/plan-pagos',
                    type: 'GET',
                    dataType: 'json'
                })
                .done(function(response) {
                    $('#planesParticipanteLoading').hide();

                    if (response.success && response.grupos) {
                        renderParticipantePlanPagos(response.grupos, response.plan_nombre, response
                            .total_general);
                        $('#modalPlanParticipante').modal('show');
                    } else {
                        $('#planesParticipanteEmpty').show();
                    }
                })
                .fail(function(xhr) {
                    $('#planesParticipanteLoading').hide();
                    $('#planesParticipanteEmpty').show();
                    let errorMsg = 'Error al cargar el plan de pagos.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    toast('error', errorMsg);
                });
        });

        function renderParticipantePlanPagos(grupos, planNombre, totalGeneral) {
            const $container = $('#planesParticipanteContainer');

            if (!grupos || !grupos.length) {
                $container.html('<div class="text-center text-muted py-4">No hay cuotas registradas.</div>');
                return;
            }

            let html = `
        <div class="plan-participante-header mb-3 p-3 rounded" style="background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%); border: 1px solid var(--d-card-border);">
            <div class="d-flex justify-content-between align-items-center">
                <span><i class="ri-bank-card-line me-2" style="color:#fc7b04;"></i><strong>Plan:</strong> ${escHtml(planNombre)}</span>
                <span class="fw-bold fs-5" style="color:#fc7b04;">Total: Bs. ${parseFloat(totalGeneral).toFixed(2)}</span>
            </div>
        </div>
    `;

            grupos.forEach(function(grupo) {
                const subtotal = parseFloat(grupo.subtotal).toFixed(2);

                html += `
            <div class="contable-plan-card mb-3">
                <div class="contable-plan-header" style="background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);">
                    <span class="contable-plan-nombre">${escHtml(grupo.concepto)}</span>
                    <span class="fw-bold" style="color:#6366f1;">Subtotal: Bs. ${subtotal}</span>
                </div>
                <div class="contable-conceptos-list" style="padding: 0.5rem 0;">
                    <table class="contable-conceptos-table">
                        <thead>
                            <tr>
                                <th>Cuota</th>
                                <th>Estado</th>
                                <th class="text-end">Monto (Bs)</th>
                                <th class="text-end">Pendiente (Bs)</th>
                                <th class="text-end">Descuento (Bs)</th>
                                <th>Vencimiento</th>
                                <th>Fecha Pago</th>
                            </tr>
                        </thead>
                        <tbody>`;

                grupo.cuotas.forEach(function(c) {
                    const estadoClass = c.estado === 'pagado' ? 'inscrito' : 'pre-inscrito';
                    const estadoTexto = c.estado === 'pagado' ? 'Pagado' : 'Pendiente';

                    html += `
                <tr>
                    <td>${escHtml(c.nombre)}</td>
                    <td><span class="inscrito-estado ${estadoClass}">${estadoTexto}</span></td>
                    <td class="text-end">${parseFloat(c.monto_bs).toFixed(2)}</td>
                    <td class="text-end">${parseFloat(c.pago_pendiente_bs).toFixed(2)}</td>
                    <td class="text-end">${parseFloat(c.descuento_bs).toFixed(2)}</td>
                    <td>${c.fecha_vencimiento || '—'}</td>
                    <td>${c.fecha_pago || '—'}</td>
                </tr>`;
                });

                html += `
                        </tbody>
                    </table>
                </div>
            </div>`;
            });

            $container.html(html);
        }

        let inscripcionCambiarId = null;
        let estudianteCambiarData = null;

        $(document).on('click', '.cambiar-a-inscrito', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const btn = $(this);
            inscripcionCambiarId = btn.data('inscripcion-id');
            const nombre = btn.data('estudiante') || '—';
            const ci = btn.data('ci') || '—';
            const planActualId = btn.data('plan-pago-id');

            if (!inscripcionCambiarId) {
                toast('error', 'No se pudo identificar la inscripción.');
                return;
            }

            estudianteCambiarData = {
                nombre,
                ci
            };

            $('#cambiarEstudianteNombre').text(nombre);
            $('#cambiarEstudianteCi').text(ci);
            $('#cambiarPlanPagoSelect').html('<option value="">Cargando planes...</option>');
            $('#cambiarConceptosContainer').hide();
            $('#cambiarConceptosList').empty();

            openModal('modalCambiarAInscrito');

            // Cargar planes configurados
            $.ajax({
                    url: '/admin/posgrads/ofertas/' + window.OFERTA_ID + '/planes-pago/configurados',
                    type: 'GET',
                    dataType: 'json',
                    timeout: 10000
                })
                .done(function(response) {
                    const planes = response.data || [];
                    let opts = '<option value="">Seleccionar plan...</option>';
                    if (planes.length) {
                        planes.forEach(p => {
                            const selected = (p.id == planActualId) ? 'selected' : '';
                            opts +=
                                `<option value="${p.id}" ${selected}>${escHtml(p.nombre)}</option>`;
                        });
                    } else {
                        opts += '<option value="" disabled>No hay planes configurados</option>';
                    }
                    $('#cambiarPlanPagoSelect').html(opts);

                    // Si hay un plan preseleccionado, cargar su detalle automáticamente
                    if (planActualId) {
                        cargarDetallePlanCambio(planActualId);
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('Error cargando planes:', status, error);
                    $('#cambiarPlanPagoSelect').html(
                    '<option value="">Error al cargar planes</option>');
                    toast('error', 'No se pudieron cargar los planes de pago.');
                });
        });

        // Cambiar el plan seleccionado -> recargar detalle
        $('#cambiarPlanPagoSelect').on('change', function() {
            const planId = $(this).val();
            if (planId) {
                cargarDetallePlanCambio(planId);
            } else {
                $('#cambiarConceptosContainer').hide();
            }
        });

        $('#btnConfirmarCambiarAInscrito').on('click', function() {
            const planId = $('#cambiarPlanPagoSelect').val();
            if (!planId) {
                toast('warning', 'Debe seleccionar un plan de pago.');
                return;
            }

            const btn = $(this);
            btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Procesando...');

            $.ajax({
                    url: '/admin/posgrads/ofertas/' + window.OFERTA_ID + '/inscripciones/' +
                        inscripcionCambiarId + '/cambiar-a-inscrito',
                    type: 'POST',
                    data: {
                        planes_pago_id: planId,
                        _token: CSRF
                    }
                })
                .done(function(r) {
                    closeModal('modalCambiarAInscrito');
                    toast('success', r.message || 'Inscripción completada correctamente.');
                    cargarInscripciones(); // Refrescar tabla de inscripciones
                })
                .fail(function(xhr) {
                    let msg = 'Error al cambiar el estado.';
                    if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    toast('error', msg);
                })
                .always(function() {
                    btn.prop('disabled', false).html('<i class="ri-check-line"></i> Confirmar');
                });
        });

        // ===== CREAR CUENTAS MOODLE =====
        let estudiantesSinCuentaMoodle = [];

        function generarUsernameMoodle(nombres, apellidoPaterno, apellidoMaterno) {
            const normalizar = (str) => str.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            
            const nombre = normalizar(nombres || '').trim();
            const ap = normalizar(apellidoPaterno || '').trim();
            const am = normalizar(apellidoMaterno || '').trim();
            
            const parts = nombre.split(' ');
            const primerNombre = parts[0] || '';
            const segundaInicial = parts.length > 1 ? parts[1].charAt(0) : '';
            
            let username1 = primerNombre.charAt(0) + ap + am;
            let username2 = segundaInicial + ap + am;
            let username3 = primerNombre + ap + am;
            
            username1 = username1.replace(/\s/g, '').substring(0, 20);
            username2 = username2.replace(/\s/g, '').substring(0, 20);
            username3 = username3.replace(/\s/g, '').substring(0, 20);
            
            return { op1: username1, op2: username2, op3: username3 };
        }

        $('#btnCrearCuentasMoodle').on('click', function() {
            $('#modalCrearCuentasMoodle').modal('show');
            $('#moodleCuentasLoading').show();
            $('#moodleCuentasEmpty').hide();
            $('#moodleCuentasList').hide();
            $('#btnConfirmarCrearCuentas').prop('disabled', true);
            
            $.ajax({
                url: '/admin/ofertas-academicas/' + window.OFERTA_ID + '/inscripciones',
                type: 'GET'
            })
            .done(function(response) {
                const inscripciones = response.data || [];
                estudiantesSinCuentaMoodle = [];
                
                inscripciones.forEach(function(ins) {
                    if (ins.estado === 'Inscrito' && (!ins.tiene_cuenta_moodle || !ins.tiene_cuenta_sistema) && ins.estudiante_nombre) {
                        const partesNombre = ins.estudiante_nombre.trim().split(' ');
                        const nombres = partesNombre[0] || '';
                        const apellidoPaterno = partesNombre.length > 1 ? partesNombre[partesNombre.length - 2] : '';
                        const apellidoMaterno = partesNombre.length > 1 ? partesNombre[partesNombre.length - 1] : '';
                        
                        const usernames = generarUsernameMoodle(nombres, apellidoPaterno, apellidoMaterno);
                        const carnetLimpio = (ins.estudiante_ci || '').replace(/[^0-9]/g, '');
                        
                        estudiantesSinCuentaMoodle.push({
                            id: ins.id,
                            nombre: ins.estudiante_nombre,
                            ci: ins.estudiante_ci,
                            correo: ins.correo,
                            usernames: usernames,
                            password: carnetLimpio.length >= 7 ? carnetLimpio : 'innova' + carnetLimpio
                        });
                    }
                });
                
                $('#moodleCuentasLoading').hide();
                
                if (estudiantesSinCuentaMoodle.length === 0) {
                    $('#moodleCuentasEmpty').show();
                } else {
                    $('#moodleCuentasList').show();
                    renderEstudiantesSinCuentaMoodle();
                }
            })
            .fail(function() {
                $('#moodleCuentasLoading').hide();
                toast('error', 'Error al cargar las inscripciones');
            });
        });

        function renderEstudiantesSinCuentaMoodle() {
            let html = '';
            estudiantesSinCuentaMoodle.forEach(function(est, idx) {
                html += '<tr>' +
                    '<td><input type="checkbox" class="checkbox-moodle-account" data-id="' + est.id + '"></td>' +
                    '<td><strong>' + escHtml(est.nombre) + '</strong></td>' +
                    '<td>' + escHtml(est.ci) + '</td>' +
                    '<td><code style="font-size:0.75rem;">' + escHtml(est.usernames.op1) + '</code></td>' +
                    '<td><code style="font-size:0.75rem;">' + escHtml(est.password) + '</code></td>' +
                    '</tr>';
            });
            $('#moodleCuentasTableBody').html(html);
        }

        $(document).on('change', '.checkbox-moodle-account', function() {
            const checked = $('.checkbox-moodle-account:checked').length;
            $('#btnConfirmarCrearCuentas').prop('disabled', checked === 0);
        });

        $('#selectAllMoodleAccounts').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('.checkbox-moodle-account').prop('checked', isChecked);
            $('#btnConfirmarCrearCuentas').prop('disabled', !isChecked);
        });

        $('#btnConfirmarCrearCuentas').on('click', function() {
            const seleccionados = [];
            $('.checkbox-moodle-account:checked').each(function() {
                const id = $(this).data('id');
                const est = estudiantesSinCuentaMoodle.find(e => e.id === id);
                if (est) seleccionados.push(est);
            });
            
            if (seleccionados.length === 0) {
                toast('warning', 'Seleccione al menos un estudiante');
                return;
            }
            
            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Creando...');
            
            $.ajax({
                url: '/admin/posgrads/ofertas/' + window.OFERTA_ID + '/crear-cuentas-moodle',
                type: 'POST',
                data: {
                    _token: CSRF,
                    estudiantes: JSON.stringify(seleccionados.map(e => ({
                        id: e.id,
                        nombre: e.nombre,
                        ci: e.ci,
                        correo: e.correo,
                        username: e.usernames.op1,
                        password: e.password
                    })))
                }
            })
            .done(function(r) {
                closeModal('modalCrearCuentasMoodle');
                toast('success', r.message || 'Cuentas creadas correctamente');
                cargarInscripciones();
            })
            .fail(function(xhr) {
                let msg = 'Error al crear las cuentas';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                toast('error', msg);
            })
            .always(function() {
                btn.prop('disabled', false).html('<i class="ri-user-add-line"></i> Crear Cuentas');
            });
        });

        // ===== DOCENTES & MÓDULOS =====
        let docenteTempData = null;
        $(document).on('click', '.btn-edit-modulo', function(e) {
            e.stopPropagation();
            const moduloId = $(this).data('id');
            const mod = allModulos.find(m => m.id === moduloId);
            if (!mod) return;
            $('#editModuloId').val(mod.id);
            $('#editModuloNombre').val(mod.nombre);
            $('#editModuloFechaInicio').val(mod.fecha_inicio ? mod.fecha_inicio.substring(0, 10) : '');
            $('#editModuloFechaFin').val(mod.fecha_fin ? mod.fecha_fin.substring(0, 10) : '');
            $('#editModuloDocenteCarnet').val('');
            $('#editModuloDocenteId').val(mod.docente_id || '');
            $('#editModuloDocentePreview').hide();
            const color = mod.color || '#6366f1';
            const colorInput = document.getElementById('editModuloColor');
            if (colorInput) {
                colorInput.value = color;
            }
            $('#editModuloColorBar').css('background', color);
            $('#editModuloColorPreview').css('background', color);
            if (mod.docente && mod.docente.persona) {
                const p = mod.docente.persona;
                const nombre = (p.nombres || '') + ' ' + (p.apellido_paterno || '') + ' ' + (p
                    .apellido_materno || '');
                $('#editModuloDocentePreview').show();
                $('#editModuloDocenteNombre').text(nombre.trim() + ' (CI: ' + p.carnet + ')');
            }
            openModal('modalEditarModulo');
        });
        $('#editModuloColor').on('input', function() {
            const color = this.value;
            $('#editModuloColorBar').css('background', color);
            $('#editModuloColorPreview').css('background', color);
        });
        $('#btnBuscarDocenteModulo').on('click', function() {
            const carnet = $('#editModuloDocenteCarnet').val().trim();
            if (!carnet) {
                toast('warning', 'Ingrese un carnet.');
                return;
            }
            $.ajax({
                    url: 'null',
                    type: 'POST',
                    data: {
                        _token: CSRF,
                        carnet: carnet,
                        modulo_id: $('#editModuloId').val()
                    }
                })
                .done(function(r) {
                    if (r.es_docente) {
                        $('#editModuloDocenteId').val(r.docente.id);
                        $('#editModuloDocentePreview').show();
                        $('#editModuloDocenteNombre').text(r.docente.nombre + ' (CI: ' + r.docente
                            .carnet + ')');
                        $.ajax({
                            url: '/admin/posgrads/modulos/' + $('#editModuloId').val(),
                            type: 'PUT',
                            data: {
                                _token: CSRF,
                                nombre: $('#editModuloNombre').val(),
                                color: $('#editModuloColor').val(),
                                fecha_inicio: $('#editModuloFechaInicio').val(),
                                fecha_fin: $('#editModuloFechaFin').val(),
                                docente_id: r.docente.id
                            }
                        }).done(function() {
                            cargarModulosSidebar();
                        });
                        if (r.moodle_result) {
                            if (r.moodle_result.sin_curso) {
                                if (confirm('El módulo no tiene curso en Moodle. ¿Desea crear uno y matricular al docente?')) {
                                    crearCursoYMatricularDocente($('#editModuloId').val(), r.docente.id);
                                }
                            } else if (r.moodle_result.moodle) {
                                toast('success', 'Docente asignado y matriculado en Moodle correctamente.');
                            } else {
                                toast('info', 'Docente asignado. ' + r.moodle_result.mensaje);
                            }
                        } else {
                            toast('success', 'Docente encontrado.');
                        }
                    } else if (r.persona_encontrada) {
                        docenteTempData = {
                            tipo: 'persona_encontrada',
                            persona_id: r.persona.id,
                            carnet: r.persona.carnet,
                            nombres: r.persona.nombre.split(' ')[0] || '',
                            nombre_completo: r.persona.nombre,
                            correo: r.persona.correo || '',
                        };
                        $('#confirmarRegistroDocenteMsg').text('Se encontró a "' + r.persona.nombre +
                            '" pero no está registrado como docente.');
                        openModal('modalConfirmarRegistroDocente');
                    } else if (r.not_found) {
                        docenteTempData = {
                            tipo: 'not_found',
                            carnet: carnet,
                        };
                        $('#confirmarRegistroDocenteMsg').text(
                            'No se encontró ninguna persona con el carnet: ' + carnet + '.');
                        openModal('modalConfirmarRegistroDocente');
                    } else {
                        toast('warning', r.message || 'Docente no encontrado.');
                    }
                })
                .fail(function() {
                    toast('error', 'Error al buscar docente.');
                });
        });
        $('#btnConfirmarRegistroDocente').on('click', function() {
            closeModal('modalConfirmarRegistroDocente');
            setTimeout(function() {
                if (!docenteTempData) return;
                clearValidationErrors();
                $('#registroDocentePersonaId').val('');
                $('#registroDocenteCarnet').val(docenteTempData.carnet || '');
                $('#registroDocenteNombres').val('');
                $('#registroDocenteApellidoPaterno').val('');
                $('#registroDocenteApellidoMaterno').val('');
                $('#registroDocenteCorreo').val('');
                $('#registroDocenteCelular').val('');
                if (docenteTempData.tipo === 'persona_encontrada') {
                    $('#registroDocentePersonaId').val(docenteTempData.persona_id);
                    $('#registroDocenteCarnet').prop('readonly', true);
                    const partes = docenteTempData.nombre_completo.split(' ');
                    $('#registroDocenteNombres').val(partes[0] || '');
                    if (partes.length > 2) {
                        $('#registroDocenteApellidoPaterno').val(partes[partes.length - 2] || '');
                        $('#registroDocenteApellidoMaterno').val(partes[partes.length - 1] || '');
                    } else if (partes.length > 1) {
                        $('#registroDocenteApellidoPaterno').val(partes[1] || '');
                    }
                    $('#registroDocenteCorreo').val(docenteTempData.correo || '');
                } else {
                    $('#registroDocenteCarnet').prop('readonly', false);
                }
                const mod = allModulos.find(m => m.id === $('#editModuloId').val());
                const color = mod ? (mod.color || '#6366f1') : '#6366f1';
                $('#registroDocenteColorBar').css('background', color);
                $('#estudiosRowsContainer').empty();
                estudioRowCount = 0;
                openModal('modalRegistroDocente');
            }, 300);
        });

        function clearValidationErrors() {
            $('#registroDocenteCarnet, #registroDocenteNombres, #registroDocenteApellidoPaterno, #registroDocenteCorreo, #registroDocenteCelular')
                .removeClass('is-invalid-custom');
            $('#registroDocenteCarnetError, #registroDocenteNombresError, #registroDocenteApellidoPaternoError, #registroDocenteCorreoError, #registroDocenteCelularError')
                .text('');
        }

        function setFieldError(fieldId, errorId, msg) {
            $('#' + fieldId).addClass('is-invalid-custom');
            $('#' + errorId).text(msg);
        }

        function validateField(fieldId, value, rules) {
            clearSingleFieldError(fieldId);
            if (rules.required && !value.trim()) {
                const label = $('#' + fieldId).prev('label').text().replace(' *', '').trim();
                setFieldError(fieldId, fieldId + 'Error', label + ' es requerido.');
                return false;
            }
            if (rules.email && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                setFieldError(fieldId, fieldId + 'Error', 'Ingrese un correo válido.');
                return false;
            }
            if (rules.maxlength && value.length > rules.maxlength) {
                setFieldError(fieldId, fieldId + 'Error', 'Máximo ' + rules.maxlength + ' caracteres.');
                return false;
            }
            if (rules.pattern && value && !rules.pattern.test(value)) {
                setFieldError(fieldId, fieldId + 'Error', rules.patternMsg || 'Formato inválido.');
                return false;
            }
            return true;
        }

        function clearSingleFieldError(fieldId) {
            $('#' + fieldId).removeClass('is-invalid-custom');
            $('#' + fieldId + 'Error').text('');
        }
        $('#registroDocenteCarnet').on('input', function() {
            validateField('registroDocenteCarnet', $(this).val(), {
                required: true,
                maxlength: 20
            });
        });
        $('#registroDocenteNombres').on('input', function() {
            validateField('registroDocenteNombres', $(this).val(), {
                required: true,
                maxlength: 100
            });
        });
        $('#registroDocenteApellidoPaterno').on('input', function() {
            validateField('registroDocenteApellidoPaterno', $(this).val(), {
                required: true,
                maxlength: 100
            });
        });
        $('#registroDocenteCorreo').on('input', function() {
            validateField('registroDocenteCorreo', $(this).val(), {
                email: true,
                maxlength: 150
            });
        });
        $('#registroDocenteCelular').on('input', function() {
            validateField('registroDocenteCelular', $(this).val(), {
                maxlength: 20,
                pattern: /^[0-9+\-\s()]*$/,
                patternMsg: 'Solo números, +, -, espacios y paréntesis.'
            });
        });

        let estudioRowCount = 0,
            allGrados = [],
            allProfesiones = [],
            allUniversidades = [];

        function cargarCatalogosEstudios() {
            return $.when($.getJSON('/admin/personas/listar-grados').fail(function() {
                allGrados = [];
            }), $.getJSON('/admin/personas/listar-profesiones').fail(function() {
                allProfesiones = [];
            }), $.getJSON('/admin/personas/listar-universidades').fail(function() {
                allUniversidades = [];
            })).done(function(r1, r2, r3) {
                allGrados = r1[0].data || r1[0] || [];
                allProfesiones = r2[0].data || r2[0] || [];
                allUniversidades = r3[0].data || r3[0] || [];
            });
        }

        function renderGradosOptions(selectedId) {
            let html = '<option value="">— Seleccionar —</option>';
            allGrados.forEach(function(g) {
                html += '<option value="' + g.id + '" ' + (selectedId == g.id ? 'selected' : '') + '>' +
                    escHtml(g.nombre) + '</option>';
            });
            return html;
        }

        function renderProfesionesOptions(selectedId) {
            let html = '<option value="">— Seleccionar —</option>';
            allProfesiones.forEach(function(p) {
                html += '<option value="' + p.id + '" ' + (selectedId == p.id ? 'selected' : '') + '>' +
                    escHtml(p.nombre) + '</option>';
            });
            return html;
        }

        function renderUniversidadesOptions(selectedId) {
            let html = '<option value="">— Seleccionar —</option>';
            allUniversidades.forEach(function(u) {
                html += '<option value="' + u.id + '" ' + (selectedId == u.id ? 'selected' : '') + '>' +
                    escHtml(u.nombre) + '</option>';
            });
            return html;
        }

        function addEstudioRow() {
            estudioRowCount++;
            const idx = estudioRowCount;
            const html = '<div class="estudio-row" data-idx="' + idx + '"><span class="estudio-num">Estudio #' +
                idx +
                '</span><button type="button" class="btn-remove-estudio" title="Quitar"><i class="ri-close-line"></i></button><div class="row g-2"><div class="col-md-4"><label class="form-label" style="font-size:0.75rem;font-weight:600;">Grado <span style="color:#ef4444;">*</span></label><select class="form-select form-select-sm estudio-grado">' +
                renderGradosOptions() +
                '</select></div><div class="col-md-4"><label class="form-label" style="font-size:0.75rem;font-weight:600;">Profesión</label><select class="form-select form-select-sm estudio-profesion">' +
                renderProfesionesOptions() +
                '</select></div><div class="col-md-4"><label class="form-label" style="font-size:0.75rem;font-weight:600;">Universidad</label><select class="form-select form-select-sm estudio-universidad">' +
                renderUniversidadesOptions() +
                '</select></div></div><div class="row g-2 mt-2"><div class="col-md-6"><label class="form-label" style="font-size:0.75rem;font-weight:600;">Estado <span style="color:#ef4444;">*</span></label><select class="form-select form-select-sm estudio-estado"><option value="Graduado">Graduado</option><option value="En Curso">En Curso</option><option value="Truncado">Truncado</option></select></div><div class="col-md-6 d-flex align-items-end"><div class="form-check"><input class="form-check-input estudio-principal" type="checkbox" id="estudioPrincipal' +
                idx + '"><label class="form-check-label" for="estudioPrincipal' + idx +
                '" style="font-size:0.75rem;font-weight:600;">Estudio Principal</label></div></div></div></div>';
            $('#estudiosRowsContainer').append(html);
        }
        $('#btnAddEstudioRow').on('click', function() {
            addEstudioRow();
        });
        $(document).on('click', '.btn-remove-estudio', function() {
            $(this).closest('.estudio-row').remove();
            renumberEstudios();
        });

        function renumberEstudios() {
            $('#estudiosRowsContainer .estudio-row').each(function(i) {
                $(this).find('.estudio-num').text('Estudio #' + (i + 1));
            });
        }
        $('#btnRegistrarYAsignarDocente').on('click', function() {
            clearValidationErrors();
            const carnet = $('#registroDocenteCarnet').val().trim(),
                nombres = $('#registroDocenteNombres').val().trim(),
                apellidoPaterno = $('#registroDocenteApellidoPaterno').val().trim(),
                apellidoMaterno = $('#registroDocenteApellidoMaterno').val().trim(),
                correo = $('#registroDocenteCorreo').val().trim(),
                celular = $('#registroDocenteCelular').val().trim();
            let valid = true;
            if (!validateField('registroDocenteCarnet', carnet, {
                    required: true,
                    maxlength: 20
                })) valid = false;
            if (!validateField('registroDocenteNombres', nombres, {
                    required: true,
                    maxlength: 100
                })) valid = false;
            if (!validateField('registroDocenteApellidoPaterno', apellidoPaterno, {
                    required: true,
                    maxlength: 100
                })) valid = false;
            if (correo && !validateField('registroDocenteCorreo', correo, {
                    email: true,
                    maxlength: 150
                })) valid = false;
            if (celular && !validateField('registroDocenteCelular', celular, {
                    maxlength: 20,
                    pattern: /^[0-9+\-\s()]*$/,
                    patternMsg: 'Solo números y caracteres válidos.'
                })) valid = false;
            if (!valid) {
                toast('warning', 'Corrija los campos marcados.');
                return;
            }
            const estudiosRows = $('#estudiosRowsContainer .estudio-row'),
                estudios = [];
            estudiosRows.each(function() {
                const gradoId = $(this).find('.estudio-grado').val(),
                    profesionId = $(this).find('.estudio-profesion').val(),
                    universidadId = $(this).find('.estudio-universidad').val(),
                    estado = $(this).find('.estudio-estado').val(),
                    principal = $(this).find('.estudio-principal').is(':checked') ? 1 : 0;
                if (gradoId) estudios.push({
                    grado_id: gradoId,
                    profesion_id: profesionId || null,
                    universidad_id: universidadId || null,
                    estado: estado,
                    principal: principal,
                });
            });
            setBtnLoading('#btnRegistrarYAsignarDocente', true, 'Registrando…');
            $.ajax({
                    url: 'null',
                    type: 'POST',
                    data: {
                        _token: CSRF,
                        persona_id: $('#registroDocentePersonaId').val() || null,
                        modulo_id: $('#editModuloId').val() || null,
                        carnet: carnet,
                        nombres: nombres,
                        apellido_paterno: apellidoPaterno,
                        apellido_materno: apellidoMaterno,
                        correo: correo,
                        celular: celular,
                        estudios: estudios,
                    }
                })
                .done(function(r) {
                    $('#editModuloDocenteId').val(r.docente.id);
                    const p = r.docente.persona,
                        nombreCompleto = (p.nombres || '') + ' ' + (p.apellido_paterno || '') + ' ' + (p
                            .apellido_materno || '');
                    $('#editModuloDocentePreview').show();
                    $('#editModuloDocenteNombre').text(nombreCompleto.trim() + ' (CI: ' + p.carnet +
                        ')');
                    $('#editModuloDocenteCarnet').val('');
                    closeModal('modalRegistroDocente');
                    cargarModulosSidebar();
                    
                    if (r.moodle_result) {
                        if (r.moodle_result.sin_curso) {
                            if (confirm('El módulo no tiene curso en Moodle. ¿Desea crear uno y matricular al docente?')) {
                                crearCursoYMatricularDocente($('#editModuloId').val(), r.docente.id);
                            } else {
                                toast('success', 'Docente registrado y asignado al módulo.');
                            }
                        } else if (r.moodle_result.moodle) {
                            toast('success', 'Docente registrado, asignado y matriculado en Moodle correctamente.');
                        } else {
                            toast('info', 'Docente asignado. ' + r.moodle_result.mensaje);
                        }
                    } else {
                        toast('success', 'Docente registrado y asignado al módulo correctamente.');
                    }
                })
                .fail(function(xhr) {
                    const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON
                        .message : 'Error al registrar docente.';
                    toast('error', msg);
                })
                .always(function() {
                    setBtnLoading('#btnRegistrarYAsignarDocente', false,
                        '<i class="ri-check-line"></i> Registrar y Asignar');
                });
        });
        let catalogosLoaded = false;
        $('#modalRegistroDocente').on('show.bs.modal', function() {
            if (!catalogosLoaded) {
                cargarCatalogosEstudios().done(function() {
                    catalogosLoaded = true;
                });
            }
        });
        $('#btnGuardarEditarModulo').on('click', function() {
            const id = $('#editModuloId').val(),
                nombre = $('#editModuloNombre').val().trim();
            if (!nombre) {
                toast('warning', 'Ingrese el nombre del módulo.');
                return;
            }
            setBtnLoading('#btnGuardarEditarModulo', true, 'Guardando…');
            $.ajax({
                    url: '/admin/posgrads/modulos/' + id,
                    type: 'PUT',
                    data: {
                        _token: CSRF,
                        nombre: nombre,
                        color: $('#editModuloColor').val(),
                        fecha_inicio: $('#editModuloFechaInicio').val(),
                        fecha_fin: $('#editModuloFechaFin').val(),
                        docente_id: $('#editModuloDocenteId').val() || null
                    }
                })
                .done(function() {
                    toast('success', 'Módulo actualizado.');
                    closeModal('modalEditarModulo');
                    cargarModulosSidebar();
                    refreshCalendar();
                })
                .fail(function() {
                    toast('error', 'Error al actualizar módulo.');
                })
                .always(function() {
                    setBtnLoading('#btnGuardarEditarModulo', false,
                        '<i class="ri-check-line"></i> Guardar Cambios');
                });
        });

        // ===== HORARIOS =====
        let sesionRowCount = 0,
            allTrabajadores = [];

        function addSesionRow(fecha, inicio, fin, horarioId) {
            sesionRowCount++;
            const idx = sesionRowCount;
            const html = '<div class="row g-2 align-items-end mb-2 sesion-row" data-idx="' + idx +
                '" data-horario-id="' + (horarioId || '') +
                '"><div class="col-md-4"><label class="form-label" style="font-size:0.75rem;font-weight:600;">Fecha</label><input type="date" class="form-control form-control-sm sesion-fecha" value="' +
                (fecha || '') +
                '"></div><div class="col-md-4"><label class="form-label" style="font-size:0.75rem;font-weight:600;">Hora Inicio</label><input type="time" class="form-control form-control-sm sesion-inicio" value="' +
                (inicio || '') +
                '"></div><div class="col-md-4"><label class="form-label" style="font-size:0.75rem;font-weight:600;">Hora Fin</label><input type="time" class="form-control form-control-sm sesion-fin" value="' +
                (fin || '') + '"></div></div>';
            $('#sesionesRowsContainer').append(html);
        }

        function cargarTrabajadoresLista() {
            return $.getJSON('/admin/posgrads/personas/listar-trabajadores').done(function(r) {
                allTrabajadores = r.data || [];
            }).fail(function() {
                allTrabajadores = [];
                toast('error', 'Error al cargar trabajadores.');
            });
        }
        $('#btnBuscarTrabajador').on('click', function() {
            const q = $('#asigTrabajadorSearch').val().trim().toLowerCase();
            if (!q) {
                toast('warning', 'Ingrese un nombre o carnet.');
                return;
            }

            function doSearch() {
                const found = allTrabajadores.filter(function(t) {
                    const persona = t.trabajador && t.trabajador.persona ? t.trabajador.persona :
                        null;
                    if (!persona) return false;
                    const nombre = ((persona.nombres || '') + ' ' + (persona.apellido_paterno ||
                        '') + ' ' + (persona.apellido_materno || '')).toLowerCase();
                    const carnet = (persona.carnet || '').toLowerCase();
                    return nombre.includes(q) || carnet.includes(q);
                });
                if (found.length === 0) {
                    toast('warning', 'No se encontró ningún trabajador con "' + q + '".');
                    $('#asigTrabajadorPreview').html('').hide();
                    return;
                }
                if (found.length === 1) {
                    const t = found[0];
                    const persona = t.trabajador.persona;
                    const nombre = (persona.nombres || '') + ' ' + (persona.apellido_paterno || '') + ' ' +
                        (persona.apellido_materno || '');
                    $('#asigTrabajadorId').val(t.id);
                    $('#asigTrabajadorPreview').html(
                        '<div class="d-flex align-items-center gap-2"><i class="ri-check-line" style="color:#22c55e;"></i><div><div style="font-size:0.85rem;font-weight:600;">' +
                        escHtml(nombre.trim()) +
                        '</div><div style="font-size:0.7rem;color:var(--d-muted);">' + escHtml(t
                            .nombre_cargo) + ' (CI: ' + escHtml(persona.carnet || 'N/A') +
                        ')</div></div></div>').show();
                    toast('success', 'Trabajador encontrado.');
                    return;
                }
                let html =
                    '<div class="mt-2 p-2 rounded" style="background:rgba(252,123,4,0.06);border:1px solid rgba(252,123,4,0.15);max-height:200px;overflow-y:auto;">';
                found.forEach(function(t) {
                    const p = t.trabajador.persona;
                    const nombre = (p.nombres || '') + ' ' + (p.apellido_paterno || '') + ' ' + (p
                        .apellido_materno || '');
                    html +=
                        '<div class="trabajador-select-item d-flex align-items-center gap-2 py-2 px-2" style="cursor:pointer;border-radius:6px;transition:background 0.15s;" data-id="' +
                        t.id + '" data-nombre="' + escHtml(nombre.trim()) + '" data-cargo="' +
                        escHtml(t.nombre_cargo) + '" data-carnet="' + escHtml(p.carnet || '') +
                        '"><i class="ri-user-line" style="color:#fc7b04;"></i><div><div style="font-size:0.85rem;font-weight:600;">' +
                        escHtml(nombre.trim()) +
                        '</div><div style="font-size:0.7rem;color:var(--d-muted);">' + escHtml(t
                            .nombre_cargo) + ' (CI: ' + escHtml(p.carnet || 'N/A') +
                        ')</div></div></div>';
                });
                html += '</div>';
                $('#asigTrabajadorPreview').html(html).show();
            }
            if (allTrabajadores.length === 0) {
                cargarTrabajadoresLista().done(function() {
                    doSearch();
                });
            } else {
                doSearch();
            }
        });
        $('#asigTrabajadorSearch').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#btnBuscarTrabajador').click();
            }
        });
        $(document).on('click', '.trabajador-select-item', function() {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            const cargo = $(this).data('cargo');
            const carnet = $(this).data('carnet');
            $('#asigTrabajadorId').val(id);
            $('#asigTrabajadorPreview').html(
                '<div class="d-flex align-items-center gap-2"><i class="ri-check-line" style="color:#22c55e;"></i><div><div style="font-size:0.85rem;font-weight:600;">' +
                escHtml(nombre) + '</div><div style="font-size:0.7rem;color:var(--d-muted);">' +
                escHtml(cargo) + ' (CI: ' + escHtml(carnet) + ')</div></div></div>').show();
            toast('success', 'Trabajador seleccionado.');
        });

        function openAsignarHorario(moduloId, fecha) {
            const mod = allModulos.find(m => m.id === moduloId);
            if (!mod) return;
            currentModuloId = moduloId;
            const horariosCount = mod.horarios ? mod.horarios.length : 0,
                pendientes = Math.max(0, window.CANTIDAD_SESIONES - horariosCount),
                horarios = mod.horarios || [],
                primerHorario = horarios.length > 0 ? horarios[0] : null,
                primerTrabajador = primerHorario && primerHorario.trabajador_cargo ? primerHorario
                .trabajador_cargo : null;
            $('#asigHorarioColorBar').css('background', mod.color || '#6366f1');
            $('#asigHorarioModuloNombre').text(mod.nombre);
            $('#asigTotalSesiones').text(window.CANTIDAD_SESIONES);
            $('#asigRegistradas').text(horariosCount);
            $('#asigPendientes').text(pendientes);
            $('#sesionesRowsContainer').empty();
            sesionRowCount = 0;
            if (primerTrabajador) {
                const nombre = primerTrabajador.trabajador && primerTrabajador.trabajador.persona ? (
                    primerTrabajador.trabajador.persona.nombres || '') + ' ' + (primerTrabajador.trabajador
                    .persona.apellido_paterno || '') : (primerTrabajador.nombre_cargo || 'Sin nombre');
                $('#asigTrabajadorId').val(primerTrabajador.id);
                $('#asigTrabajadorSearch').val(nombre);
                $('#asigTrabajadorPreview').html(
                    '<div class="d-flex align-items-center gap-2"><i class="ri-check-line" style="color:#22c55e;"></i><div><div style="font-size:0.85rem;font-weight:600;">' +
                    escHtml(nombre) + '</div><div style="font-size:0.7rem;color:var(--d-muted);">' + escHtml(
                        primerTrabajador.nombre_cargo || '') + '</div></div></div>').show();
            } else {
                $('#asigTrabajadorSearch').val('');
                $('#asigTrabajadorId').val('');
                $('#asigTrabajadorPreview').html('').hide();
            }
            for (let i = 0; i < window.CANTIDAD_SESIONES; i++) {
                const h = horarios[i];
                const horarioId = h ? h.id : null;
                const fechaVal = h ? (h.fecha ? String(h.fecha).substring(0, 10) : '') : '';
                const inicioVal = h ? (h.hora_inicio ? h.hora_inicio.substring(0, 5) : '') : '';
                const finVal = h ? (h.hora_fin ? h.hora_fin.substring(0, 5) : '') : '';
                addSesionRow(fechaVal, inicioVal, finVal, horarioId);
            }
            $('#btnAddSesionRow').toggle(pendientes > 0);
            openModal('modalAsignarHorario');
        }
        $(document).on('click', '.btn-asignar-horario', function(e) {
            e.stopPropagation();
            openAsignarHorario($(this).data('id'));
        });
        $('#modalAsignarHorario').on('hidden.bs.modal', function() {
            $('#btnAddSesionRow').show();
        });
        $('#btnGuardarAsignarHorario').on('click', function() {
            const rows = $('#sesionesRowsContainer .sesion-row'),
                trabajadorId = $('#asigTrabajadorId').val() || null;
            let valid = true;
            rows.each(function() {
                const fecha = $(this).find('.sesion-fecha').val(),
                    inicio = $(this).find('.sesion-inicio').val(),
                    fin = $(this).find('.sesion-fin').val();
                if (!fecha || !inicio || !fin) {
                    valid = false;
                    return false;
                }
            });
            if (!valid) {
                toast('warning', 'Complete fecha, hora inicio y hora fin en todas las filas.');
                return;
            }
            if (rows.length === 0) {
                toast('warning', 'No hay sesiones para registrar.');
                return;
            }
            const promises = [];
            rows.each(function() {
                const $row = $(this),
                    horarioId = $row.data('horario-id'),
                    fecha = $row.find('.sesion-fecha').val(),
                    inicio = $row.find('.sesion-inicio').val(),
                    fin = $row.find('.sesion-fin').val();
                if (horarioId && horarioId !== '') {
                    promises.push($.ajax({
                        url: '/admin/posgrads/horarios/' + horarioId,
                        type: 'PUT',
                        data: {
                            _token: CSRF,
                            fecha: fecha,
                            hora_inicio: inicio,
                            hora_fin: fin,
                            trabajadores_cargo_id: trabajadorId,
                            estado: 'Confirmado'
                        }
                    }));
                } else {
                    promises.push($.ajax({
                        url: '/admin/posgrads/modulos/' + currentModuloId +
                            '/horarios',
                        type: 'POST',
                        data: {
                            _token: CSRF,
                            fecha: fecha,
                            hora_inicio: inicio,
                            hora_fin: fin,
                            trabajadores_cargo_id: trabajadorId,
                            estado: 'Confirmado'
                        }
                    }));
                }
            });
            setBtnLoading('#btnGuardarAsignarHorario', true, 'Guardando…');
            Promise.all(promises).then(function() {
                toast('success', promises.length + ' horario(s) actualizado(s).');
                closeModal('modalAsignarHorario');
                cargarModulosSidebar();
                refreshCalendar();
            }).catch(function() {
                toast('error', 'Error al guardar horarios.');
            }).finally(function() {
                setBtnLoading('#btnGuardarAsignarHorario', false,
                    '<i class="ri-check-line"></i> Guardar Todas');
            });
        });

        function openDetalleHorario(event) {
            const props = event.extendedProps;
            currentHorarioId = props.horario_id;
            $('#detHorarioColorBar').css('background', props.modulo_color);
            $('#detHorarioModulo').text(props.modulo_nombre);
            $('#detHorarioFecha').text(formatDate(props.fecha));
            $('#detHorarioHora').text(formatTime(props.hora_inicio) + ' — ' + formatTime(props.hora_fin));
            $('#detHorarioDocente').text(props.docente_nombre || 'Sin asignar');
            const estadoClass = props.estado === 'Desarrollado' ? 'bg-success' : (props.estado === 'Postergado' ?
                'bg-warning' : 'bg-secondary');
            $('#detHorarioEstado').html('<span class="badge ' + estadoClass + '">' + escHtml(props.estado) +
                '</span>');
            cargarTrabajadoresSelect('#detHorarioTrabajador', props.trabajador_id);
            if (props.estado === 'Confirmado') {
                $('#horarioReprogramarBox').show();
                $('#reprogFecha').val(props.fecha);
                $('#reprogInicio').val(props.hora_inicio);
                $('#reprogFin').val(props.hora_fin);
                $('#btnEliminarHorario').show();
            } else {
                $('#horarioReprogramarBox').hide();
                $('#btnEliminarHorario').hide();
            }
            openModal('modalDetalleHorario');
        }
        $('#btnCambiarEstadoHorario').on('click', function() {
            closeModal('modalDetalleHorario');
            setTimeout(function() {
                openModal('modalCambiarEstadoHorario');
            }, 300);
        });
        $('#btnConfirmarCambiarEstado').on('click', function() {
            const nuevoEstado = $('#nuevoEstadoHorario').val();
            $.ajax({
                url: '/admin/posgrads/horarios/' + currentHorarioId + '/estado',
                type: 'PUT',
                data: {
                    _token: CSRF,
                    estado: nuevoEstado
                }
            }).done(function() {
                toast('success', 'Estado actualizado a: ' + nuevoEstado);
                closeModal('modalCambiarEstadoHorario');
                cargarModulosSidebar();
                refreshCalendar();
            }).fail(function() {
                toast('error', 'Error al cambiar estado.');
            });
        });
        $('#btnEliminarHorario').on('click', function() {
            if (!confirm('¿Eliminar este horario?')) return;
            $.ajax({
                url: '/admin/posgrads/horarios/' + currentHorarioId,
                type: 'DELETE',
                data: {
                    _token: CSRF
                }
            }).done(function() {
                toast('success', 'Horario eliminado.');
                closeModal('modalDetalleHorario');
                cargarModulosSidebar();
                refreshCalendar();
            }).fail(function() {
                toast('error', 'Error al eliminar horario.');
            });
        });
        $('#detHorarioTrabajador').on('change', function() {
            const trabajadorId = $(this).val();
            $.ajax({
                url: '/admin/posgrads/horarios/' + currentHorarioId,
                type: 'PUT',
                data: {
                    _token: CSRF,
                    fecha: $('#detHorarioFecha').text().split('/').reverse().join('-'),
                    hora_inicio: $('#detHorarioHora').text().split(' — ')[0],
                    hora_fin: $('#detHorarioHora').text().split(' — ')[1],
                    trabajadores_cargo_id: trabajadorId
                }
            }).done(function() {
                toast('success', 'Trabajador actualizado.');
            }).fail(function() {
                toast('error', 'Error al actualizar trabajador.');
            });
        });
        $('#btnConfirmarReprogramar').on('click', function() {
            const fecha = $('#reprogFecha').val(),
                inicio = $('#reprogInicio').val(),
                fin = $('#reprogFin').val();
            if (!fecha || !inicio || !fin) {
                toast('warning', 'Complete todos los campos.');
                return;
            }
            $.ajax({
                url: '/admin/posgrads/horarios/' + currentHorarioId + '/reprogramar',
                type: 'POST',
                data: {
                    _token: CSRF,
                    fecha: fecha,
                    hora_inicio: inicio,
                    hora_fin: fin
                }
            }).done(function() {
                toast('success', 'Horario reprogramado.');
                closeModal('modalDetalleHorario');
                cargarModulosSidebar();
                refreshCalendar();
            }).fail(function() {
                toast('error', 'Error al reprogramar.');
            });
        });

        function cargarTrabajadoresSelect(selector, selectedId) {
            if (!allTrabajadores.length) {
                $.getJSON('/admin/posgrads/personas/listar-trabajadores').done(function(r) {
                    allTrabajadores = r.data || [];
                    renderTrabajadoresSelect(selector, selectedId);
                }).fail(function() {
                    $(selector).html('<option value="">— Sin datos —</option>');
                });
            } else {
                renderTrabajadoresSelect(selector, selectedId);
            }
        }

        function renderTrabajadoresSelect(selector, selectedId) {
            const opts = '<option value="">— Sin asignar —</option>' + allTrabajadores.map(function(t) {
                const persona = t.trabajador && t.trabajador.persona ? t.trabajador.persona : null;
                const nombre = persona ? (persona.nombres || '') + ' ' + (persona.apellido_paterno || '') +
                    ' ' + (persona.apellido_materno || '') : t.nombre_cargo;
                return '<option value="' + t.id + '" ' + (selectedId == t.id ? 'selected' : '') + '>' +
                    escHtml(nombre.trim()) + ' — ' + escHtml(t.nombre_cargo) + '</option>';
            }).join('');
            $(selector).html(opts);
        }

        // ===== AREA CONTABLE: PlanesConcepto =====
        let contableData = [];

        function initContable() {
            $.getJSON('null').done(function(
                r) {
                contableData = r.data || [];
                renderContableCards();
            }).fail(function() {
                toast('error', 'Error al cargar configuraciones.');
            });
        }

        function renderContableCards() {
            const $container = $('#contableCardsContainer'),
                $empty = $('#contableEmptyState');
            if (!contableData.length) {
                $container.empty();
                $empty.show();
                return;
            }
            $empty.hide();
            const conceptoOrden = {
                'matricula': 1,
                'matrícula': 1,
                'colegiatura': 2,
                'certificacion': 3,
                'certificación': 3
            };
            const planes = {};
            contableData.forEach(function(item) {
                const planNombre = item.plan_pago?.nombre || 'Sin plan',
                    planId = item.plan_pago?.id || 0,
                    esPromocion = item.plan_pago?.es_promocion || false,
                    fechaInicio = item.plan_pago?.fecha_inicio_promocion || null,
                    fechaFin = item.plan_pago?.fecha_fin_promocion || null;
                if (!planes[planId]) {
                    planes[planId] = {
                        nombre: planNombre,
                        es_promocion: esPromocion,
                        fecha_inicio: fechaInicio,
                        fecha_fin: fechaFin,
                        conceptos: []
                    };
                }
                planes[planId].conceptos.push(item);
            });
            const planOrden = {
                'matricula': 1,
                'matrícula': 1,
                'colegiatura': 2,
                'certificacion': 3,
                'certificación': 3
            };
            const sortedPlanIds = Object.keys(planes).sort(function(a, b) {
                const nombreA = planes[a].nombre.toLowerCase().trim(),
                    nombreB = planes[b].nombre.toLowerCase().trim(),
                    ordenA = planOrden[nombreA] !== undefined ? planOrden[nombreA] : 99,
                    ordenB = planOrden[nombreB] !== undefined ? planOrden[nombreB] : 99;
                if (ordenA !== ordenB) return ordenA - ordenB;
                return nombreA.localeCompare(nombreB);
            });
            const planColors = ['#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899',
                '#14b8a6'
            ];
            let colorIndex = 0;
            const planColorMap = {};
            sortedPlanIds.forEach(function(id) {
                planColorMap[id] = planColors[colorIndex % planColors.length];
                colorIndex++;
            });
            let html = '';
            sortedPlanIds.forEach(function(planId) {
                const plan = planes[planId],
                    accentColor = planColorMap[planId];
                plan.conceptos.sort(function(a, b) {
                    const nombreA = (a.concepto?.nombre || '').toLowerCase().trim(),
                        nombreB = (b.concepto?.nombre || '').toLowerCase().trim(),
                        ordenA = conceptoOrden[nombreA] !== undefined ? conceptoOrden[nombreA] : 99,
                        ordenB = conceptoOrden[nombreB] !== undefined ? conceptoOrden[nombreB] : 99;
                    if (ordenA !== ordenB) return ordenA - ordenB;
                    return nombreA.localeCompare(nombreB);
                });
                let totalPlan = 0;
                plan.conceptos.forEach(function(c) {
                    totalPlan += parseFloat(c.pago_bs || 0);
                });
                const descuentoHeader = plan.es_promocion ? '<th class="text-end">Descuento (Bs)</th>' : '';
                const promoRango = (plan.fecha_inicio && plan.fecha_fin) ? formatDateLong(plan
                    .fecha_inicio) + ' al ' + formatDateLong(plan.fecha_fin) : '';
                const cardClass = plan.es_promocion ? 'contable-plan-card contable-plan-promo' :
                    'contable-plan-card';
                const headerClass = plan.es_promocion ? 'contable-plan-header contable-plan-header-promo' :
                    'contable-plan-header';
                const promoBadge = plan.es_promocion ?
                    '<span class="contable-promo-badge"><i class="ri-gift-line"></i> Promoción</span>' : '';
                const promoDates = plan.es_promocion && promoRango ?
                    '<div class="contable-promo-dates-bar"><i class="ri-calendar-event-line"></i> ' +
                    promoRango + '</div>' : '';
                const planTypeLabel = plan.es_promocion ?
                    '<span class="contable-plan-type-label promo">Promoción</span>' :
                    '<span class="contable-plan-type-label normal">Plan Regular</span>';
                const accentBar = '<div class="contable-accent-bar" style="background:' + accentColor +
                    ';"></div>';
                html += '<div class="' + cardClass + ' mb-3">' + accentBar + '  <div class="' +
                    headerClass +
                    '">    <div class="contable-plan-header-left">      <span class="contable-plan-nombre">' +
                    escHtml(plan.nombre) + '</span>' + promoBadge +
                    '    </div>    <div class="contable-plan-header-right">      <span class="contable-plan-total">Bs. ' +
                    totalPlan.toFixed(2) +
                    '</span>      <button class="btn-contable-edit-plan btn-editar-plan-completo" data-plan-id="' +
                    planId +
                    '" title="Editar plan completo"><i class="ri-pencil-fill"></i> Editar</button>    </div>  </div>';
                if (promoDates) html += '  <div class="contable-promo-dates-bar">' + promoDates + '</div>';
                html += '  <div class="contable-plan-type-row">' + planTypeLabel +
                    '</div>  <div class="contable-conceptos-list">    <table class="contable-conceptos-table">      <thead><tr><th>Concepto</th><th class="text-center" style="width:80px;">Cuotas</th><th class="text-end">P. Regular (Bs)</th>' +
                    descuentoHeader +
                    '<th class="text-end">Pago (Bs)</th><th class="text-center" style="width:90px;">Acciones</th></tr></thead>      <tbody>';
                plan.conceptos.forEach(function(c) {
                    const descuento = parseFloat(c.descuento_bs || 0);
                    const descuentoCell = plan.es_promocion ? '<td class="text-end">' + (descuento >
                            0 ? '<span style="color:#22c55e;">-' + descuento.toFixed(2) +
                            '</span>' : '<span style="color:var(--d-muted);">—</span>') + '</td>' :
                        '';
                    html += '<tr>  <td><span style="font-weight:500;">' + escHtml(c.concepto
                            ?.nombre || '') +
                        '</span></td>  <td class="text-center"><span class="contable-cuotas-badge">' +
                        c.n_cuotas + '</span></td>  <td class="text-end">' + parseFloat(c
                            .precio_regular || 0).toFixed(2) + '</td>' + descuentoCell +
                        '  <td class="text-end" style="font-weight:700;color:' + accentColor +
                        ';">' + parseFloat(c.pago_bs || 0).toFixed(2) +
                        '</td>  <td class="text-center">    <div class="d-inline-flex gap-1">      <button class="btn-contable-edit btn-accion-editar-pc" data-id="' +
                        c.id +
                        '" title="Editar"><i class="ri-pencil-fill"></i></button>      <button class="btn-contable-delete btn-accion-eliminar-pc" data-id="' +
                        c.id + '" data-plan="' + escHtml(plan.nombre) + '" data-concepto="' +
                        escHtml(c.concepto?.nombre || '') +
                        '" title="Eliminar"><i class="ri-delete-bin-fill"></i></button>    </div>  </td></tr>';
                });
                html += '      </tbody>    </table>  </div></div>';
            });
            $container.html(html);
        }
        $(document).on('click', '.btn-accion-editar-pc', function() {
            const id = $(this).data('id');
            const row = contableData.find(r => r.id === id);
            if (!row) return;
            $('#idEditarPc').val(id);
            $('#nCuotasEditar').val(row.n_cuotas);
            $('#precioRegularEditar').val(parseFloat(row.precio_regular || 0).toFixed(2));
            $('#descuentoBsEditar').val(parseFloat(row.descuento_bs || 0).toFixed(2));
            $('#pagoBsEditar').val(parseFloat(row.pago_bs || 0).toFixed(2));
            $('#labelPcEditar').text(row.plan_pago?.nombre + ' — ' + row.concepto?.nombre);
            openModal('modalEditarPc');
        });
        $(document).on('click', '.btn-accion-eliminar-pc', function() {
            const id = $(this).data('id');
            const plan = $(this).data('plan');
            const concepto = $(this).data('concepto');
            $('#idEliminarPc').val(id);
            $('#nombreEliminarPc').text(plan + ' — ' + concepto);
            openModal('modalEliminarPc');
        });
        $(document).on('click', '.btn-editar-plan-completo', function() {
            const planId = $(this).data('plan-id'),
                planConceptos = contableData.filter(function(c) {
                    return c.plan_pago?.id == planId;
                });
            if (!planConceptos.length) return;
            const plan = planConceptos[0].plan_pago,
                esPromocion = plan?.es_promocion || false;
            $('#editPlanId').val(planId);
            $('#editPlanNombre').val(plan?.nombre || '');
            $('#editPlanEsPromocion').val(esPromocion ? '1' : '0');
            if (esPromocion) {
                $('#editBadgePromocion').show();
                const fechaInicio = plan?.fecha_inicio_promocion,
                    fechaFin = plan?.fecha_fin_promocion;
                let rangoTexto = '';
                if (fechaInicio && fechaFin) rangoTexto = 'Vigencia: ' + formatDateLong(fechaInicio) +
                    ' al ' + formatDateLong(fechaFin);
                $('#editRangoPromocion').text(rangoTexto);
                $('#editPlanFechasBox').show();
                $('#editPlanFechaInicio').val(fechaInicio || '');
                $('#editPlanFechaFin').val(fechaFin || '');
                $('.col-edit-descuento').show();
            } else {
                $('#editBadgePromocion').hide();
                $('#editPlanFechasBox').hide();
                $('.col-edit-descuento').hide();
            }
            let rowsHtml = '',
                totalPlan = 0;
            planConceptos.forEach(function(c) {
                const descuento = parseFloat(c.descuento_bs || 0);
                totalPlan += parseFloat(c.pago_bs || 0);
                const descuentoCell = esPromocion ? '<td class="text-end">' + (descuento > 0 ?
                    '<span style="color:#22c55e;">-' + descuento.toFixed(2) + '</span>' :
                    '<span style="color:var(--d-muted);">—</span>') + '</td>' : '';
                rowsHtml += '<tr>  <td><span style="font-weight:500;">' + escHtml(c.concepto
                        ?.nombre || '') +
                    '</span></td>  <td class="text-center"><input type="number" class="form-control form-control-sm input-edit-cuotas" data-id="' +
                    c.id + '" value="' + c.n_cuotas +
                    '" min="1" max="60" style="width:70px;margin:0 auto;"></td>  <td class="text-end"><input type="number" class="form-control form-control-sm input-edit-precio" data-id="' +
                    c.id + '" value="' + parseFloat(c.precio_regular || 0).toFixed(2) +
                    '" min="0" step="0.01" style="width:100px;margin-left:auto;"></td>' +
                    descuentoCell;
                if (esPromocion) rowsHtml +=
                    '  <td class="text-end"><input type="number" class="form-control form-control-sm input-edit-descuento" data-id="' +
                    c.id + '" value="' + parseFloat(c.descuento_bs || 0).toFixed(2) +
                    '" min="0" step="0.01" style="width:100px;margin-left:auto;"></td>';
                rowsHtml += '  <td class="text-end"><span class="edit-fila-pago" data-id="' + c.id +
                    '" style="font-weight:700;color:#fc7b04;">' + parseFloat(c.pago_bs || 0)
                    .toFixed(2) + '</span></td></tr>';
            });
            $('#editarPlanConceptosBody').html(rowsHtml);
            $('#editTotalPlanValor').text('Bs. ' + totalPlan.toFixed(2));
            openModal('modalEditarPlanCompleto');
        });
        $(document).on('input', '.input-edit-precio, .input-edit-descuento', function() {
            const id = $(this).data('id');
            const $fila = $(this).closest('tr');
            const regular = parseFloat($fila.find('.input-edit-precio').val()) || 0,
                descuento = parseFloat($fila.find('.input-edit-descuento').val()) || 0,
                pago = Math.max(0, regular - descuento);
            $fila.find('.edit-fila-pago').text(pago.toFixed(2));
            recalcularTotalEditarPlan();
        });
        $(document).on('input', '.input-edit-cuotas', function() {
            recalcularTotalEditarPlan();
        });

        function recalcularTotalEditarPlan() {
            let total = 0;
            $('#editarPlanConceptosBody tr').each(function() {
                const regular = parseFloat($(this).find('.input-edit-precio').val()) || 0,
                    descuento = parseFloat($(this).find('.input-edit-descuento').val()) || 0;
                total += Math.max(0, regular - descuento);
            });
            $('#editTotalPlanValor').text('Bs. ' + total.toFixed(2));
        }
        $(document).on('click', '.btn-eliminar-concepto-plan', function() {
            const id = $(this).data('id');
            const plan = $(this).data('plan');
            const concepto = $(this).data('concepto');
            $('#idEliminarPc').val(id);
            $('#nombreEliminarPc').text(plan + ' — ' + concepto);
            closeModal('modalEditarPlanCompleto');
            setTimeout(function() {
                openModal('modalEliminarPc');
            }, 300);
        });
        $('#btnGuardarEditarPlan').on('click', function() {
            const planId = $('#editPlanId').val(),
                esPromocion = $('#editPlanEsPromocion').val() == '1',
                $btn = $(this),
                updates = [];
            $('#editarPlanConceptosBody tr').each(function() {
                const id = $(this).find('.input-edit-cuotas').data('id'),
                    nCuotas = $(this).find('.input-edit-cuotas').val(),
                    precioRegular = $(this).find('.input-edit-precio').val(),
                    descuentoBs = esPromocion ? ($(this).find('.input-edit-descuento').val() || 0) :
                    0;
                updates.push({
                    id: id,
                    n_cuotas: parseInt(nCuotas) || 1,
                    precio_regular: parseFloat(precioRegular) || 0,
                    descuento_bs: parseFloat(descuentoBs) || 0,
                });
            });
            if (!updates.length) {
                toast('warning', 'No hay conceptos para actualizar.');
                return;
            }
            $btn.prop('disabled', true).html(
                '<i class="ri-loader-4-line" style="animation:spin 1s linear infinite;"></i> Guardando…'
            );
            const promises = [];
            if (esPromocion) {
                const fechaInicio = $('#editPlanFechaInicio').val(),
                    fechaFin = $('#editPlanFechaFin').val();
                promises.push($.ajax({
                    url: '/admin/posgrads/planes-pagos/' + planId,
                    type: 'PUT',
                    data: {
                        _token: CSRF,
                        nombre: $('#editPlanNombre').val(),
                        fecha_inicio_promocion: fechaInicio || null,
                        fecha_fin_promocion: fechaFin || null,
                        es_promocion: true,
                    }
                }));
            }
            updates.forEach(function(u) {
                promises.push($.ajax({
                    url: '/admin/posgrads/ofertas/planes-conceptos/' + u.id,
                    type: 'PUT',
                    data: {
                        _token: CSRF,
                        n_cuotas: u.n_cuotas,
                        precio_regular: u.precio_regular,
                        descuento_bs: u.descuento_bs,
                    }
                }));
            });
            Promise.all(promises).then(function() {
                closeModal('modalEditarPlanCompleto');
                initContable();
                toast('success', 'Plan actualizado correctamente.');
            }).fail(function() {
                toast('error', 'Error al actualizar el plan.');
            }).always(function() {
                $btn.prop('disabled', false).html('<i class="ri-save-line"></i> Guardar Cambios');
            });
        });

        // ===== CREACIÓN MÚLTIPLE DE PLANES/CONCEPTOS =====
        let filaConceptoCount = 0,
            planesPagoData = [],
            conceptosData = [],
            tienePlanPrincipal = false;

        function renderFilaConcepto(conceptoId, nCuotas, precioRegular, descuentoBs) {
            filaConceptoCount++;
            const idx = filaConceptoCount;
            const esPromocion = $('#planesPagoCrear').find('option:selected').data('promocion') == '1';
            let conceptosOpts = '<option value="">— Seleccionar —</option>';
            conceptosData.forEach(function(c) {
                const selected = c.id == conceptoId ? 'selected' : '';
                conceptosOpts += '<option value="' + c.id + '" ' + selected + '>' + escHtml(c.nombre) +
                    '</option>';
            });
            let descuentoCell = '';
            if (esPromocion) descuentoCell =
                '<td><input type="number" class="form-control form-control-sm input-descuento" data-fila="' + idx +
                '" value="' + (descuentoBs || '0.00') + '" min="0" step="0.01"></td>';
            const html = '<tr class="fila-concepto" data-fila-idx="' + idx +
                '"><td><select class="form-select form-select-sm select-concepto" data-fila="' + idx + '">' +
                conceptosOpts +
                '</select><div class="warning-precio" style="display:none;"><i class="ri-information-line"></i> <span>Sin precio base</span></div></td><td><input type="number" class="form-control form-control-sm input-cuotas" data-fila="' +
                idx + '" value="' + (nCuotas || 1) +
                '" min="1" max="60"></td><td><input type="number" class="form-control form-control-sm input-precio" data-fila="' +
                idx + '" value="' + (precioRegular || '0.00') + '" min="0" step="0.01"></td>' + descuentoCell +
                '<td class="text-end"><span class="fila-pago" data-fila="' + idx +
                '" style="font-weight:700;color:#fc7b04;">0.00</span></td><td class="text-center"><button type="button" class="btn-remove-fila" data-fila="' +
                idx + '" title="Eliminar fila"><i class="ri-close-line"></i></button></td></tr>';
            $('#filasConceptosBody').append(html);
            actualizarBotonesEliminar();
            calcularTotalGeneral();
        }

        function actualizarBotonesEliminar() {
            const totalFilas = $('#filasConceptosBody .fila-concepto').length;
            $('#filasConceptosBody .btn-remove-fila').prop('disabled', totalFilas <= 1);
        }

        function calcularPagoFila(filaIdx) {
            const $fila = $('.fila-concepto[data-fila-idx="' + filaIdx + '"]');
            const regular = parseFloat($fila.find('.input-precio').val()) || 0,
                descuento = parseFloat($fila.find('.input-descuento').val()) || 0,
                pago = Math.max(0, regular - descuento);
            $fila.find('.fila-pago').text(pago.toFixed(2));
            calcularTotalGeneral();
        }

        function calcularTotalGeneral() {
            let total = 0;
            $('#filasConceptosBody .fila-concepto').each(function() {
                const regular = parseFloat($(this).find('.input-precio').val()) || 0,
                    descuento = parseFloat($(this).find('.input-descuento').val()) || 0;
                total += Math.max(0, regular - descuento);
            });
            $('#totalGeneralCrear').text('Bs. ' + total.toFixed(2));
            validarFormularioCrear();
        }

        function validarFormularioCrear() {
            const planId = $('#planesPagoCrear').val();
            const filas = $('#filasConceptosBody .fila-concepto');
            let todasCompletas = true;
            if (!planId || filas.length === 0) {
                $('#btnGuardarPc').prop('disabled', true);
                return;
            }
            filas.each(function() {
                const conceptoId = $(this).find('.select-concepto').val(),
                    cuotas = $(this).find('.input-cuotas').val(),
                    precio = $(this).find('.input-precio').val();
                if (!conceptoId || !cuotas || !precio) {
                    todasCompletas = false;
                    return false;
                }
            });
            const selectedOption = $('#planesPagoCrear').find('option:selected'),
                esPromocion = selectedOption.data('promocion') == '1',
                bloqueoPromocion = esPromocion && !tienePlanPrincipal;
            $('#btnGuardarPc').prop('disabled', !todasCompletas || bloqueoPromocion);
        }

        function autoCompletarPrecio(filaIdx) {
            const $fila = $('.fila-concepto[data-fila-idx="' + filaIdx + '"]'),
                conceptoId = $fila.find('.select-concepto').val(),
                $warning = $fila.find('.warning-precio');
            if (!conceptoId) {
                $warning.hide();
                return;
            }
            const ruta =
                'null'
                .replace('__ID__', conceptoId);
            $.getJSON(ruta).done(function(r) {
                if (r.precio_base !== null) {
                    $fila.find('.input-precio').val(parseFloat(r.precio_base).toFixed(2));
                    $warning.hide();
                } else {
                    $fila.find('.input-precio').val('0.00');
                    $warning.find('span').text('Sin precio base — ingrese manualmente');
                    $warning.show();
                }
                calcularPagoFila(filaIdx);
            }).fail(function() {
                $warning.find('span').text('Error al obtener precio base');
                $warning.show();
            });
        }
        $('#btn-nuevo-plan-concepto').on('click', function() {
            $('#formCrearPc')[0].reset();
            $('#planesPagoCrear').html('<option value="">— Seleccionar —</option>');
            $('#filasConceptosBody').empty();
            filaConceptoCount = 0;
            tienePlanPrincipal = false;
            $('#bannerSinPrincipal').hide();
            $('#tablaConceptosContainer').show();
            $('#totalGeneralContainer').show();
            $('#badgePromocionCrear').hide();
            $('#btnGuardarPc').prop('disabled', true);
            $('#totalGeneralCrear').text('Bs. 0.00');
            $.when($.getJSON('null'),
                    $.getJSON('null'), $
                    .getJSON(
                        'null'
                    ))
                .done(function(rPlanes, rConceptos, rPrincipal) {
                    planesPagoData = rPlanes[0].data || [];
                    conceptosData = rConceptos[0].data || [];
                    tienePlanPrincipal = rPrincipal[0].tiene_principal || false;
                    let planesParaMostrar = planesPagoData;
                    if (contableData.length === 0) {
                        planesParaMostrar = planesPagoData.filter(function(p) {
                            return p.principal == 1 || p.principal === true;
                        });
                    }
                    planesParaMostrar.forEach(function(p) {
                        $('#planesPagoCrear').append('<option value="' + p.id +
                            '" data-promocion="' + (p.es_promocion ? '1' : '0') +
                            '" data-fecha-inicio="' + (p.fecha_inicio_promocion || '') +
                            '" data-fecha-fin="' + (p.fecha_fin_promocion || '') + '">' +
                            escHtml(p.nombre) + '</option>');
                    });
                    renderFilaConcepto();
                }).fail(function() {
                    toast('error', 'Error al cargar datos.');
                });
            openModal('modalCrearPc');
        });
        $('#planesPagoCrear').on('change', function() {
            const selected = $(this).find('option:selected'),
                esPromocion = selected.data('promocion') == '1';
            if (esPromocion) {
                const fechaInicio = selected.data('fecha-inicio'),
                    fechaFin = selected.data('fecha-fin');
                let rangoTexto = '';
                if (fechaInicio && fechaFin) rangoTexto = 'Vigencia: ' + formatDateLong(fechaInicio) +
                    ' al ' + formatDateLong(fechaFin);
                $('#badgePromocionCrear').show();
                $('#rangoPromocionCrear').text(rangoTexto);
                if (!tienePlanPrincipal) $('#bannerSinPrincipal').show();
                else $('#bannerSinPrincipal').hide();
            } else {
                $('#badgePromocionCrear').hide();
                $('#bannerSinPrincipal').hide();
            }
            $('.col-descuento').toggle(esPromocion);
            $('#filasConceptosBody .fila-concepto').each(function() {
                if (esPromocion) {
                    if (!$(this).find('.input-descuento').length) $(this).find('.input-precio')
                        .parent().after(
                            '<td><input type="number" class="form-control form-control-sm input-descuento" data-fila="' +
                            $(this).data('fila-idx') + '" value="0.00" min="0" step="0.01"></td>');
                } else {
                    $(this).find('.input-descuento').parent().remove();
                }
            });
            calcularTotalGeneral();
            validarFormularioCrear();
        });
        $('#btnAddFilaConcepto').on('click', function() {
            renderFilaConcepto();
        });
        $(document).on('change', '.select-concepto', function() {
            const filaIdx = $(this).data('fila');
            autoCompletarPrecio(filaIdx);
            validarFormularioCrear();
        });
        $(document).on('input', '.input-precio, .input-descuento', function() {
            const filaIdx = $(this).data('fila');
            calcularPagoFila(filaIdx);
        });
        $(document).on('input', '.input-cuotas', function() {
            validarFormularioCrear();
        });
        $(document).on('click', '.btn-remove-fila', function() {
            const totalFilas = $('#filasConceptosBody .fila-concepto').length;
            if (totalFilas <= 1) return;
            $(this).closest('.fila-concepto').remove();
            actualizarBotonesEliminar();
            calcularTotalGeneral();
        });
        $('#btnGuardarPc').on('click', function() {
            const planId = $('#planesPagoCrear').val();
            if (!planId) {
                toast('warning', 'Seleccione un plan de pago.');
                return;
            }
            const conceptos = [],
                conceptoIdsSet = new Set();
            let valid = true;
            $('#filasConceptosBody .fila-concepto').each(function() {
                const conceptoId = $(this).find('.select-concepto').val(),
                    nCuotas = $(this).find('.input-cuotas').val(),
                    precioRegular = $(this).find('.input-precio').val(),
                    $descuentoInput = $(this).find('.input-descuento'),
                    descuentoBs = $descuentoInput.length ? ($descuentoInput.val() || 0) : 0;
                if (!conceptoId) {
                    $(this).find('.select-concepto').addClass('is-invalid-custom');
                    valid = false;
                    return false;
                }
                $(this).find('.select-concepto').removeClass('is-invalid-custom');
                conceptos.push({
                    concepto_id: conceptoId,
                    n_cuotas: parseInt(nCuotas) || 1,
                    precio_regular: parseFloat(precioRegular) || 0,
                    descuento_bs: parseFloat(descuentoBs) || 0,
                });
            });
            if (!valid || conceptos.length === 0) {
                toast('warning', 'Complete todos los campos obligatorios.');
                return;
            }
            const $btn = $(this);
            $btn.prop('disabled', true).html(
                '<i class="ri-loader-4-line" style="animation:spin 1s linear infinite;"></i> Guardando…'
            );
            $.ajax({
                    url: 'null',
                    type: 'POST',
                    data: {
                        _token: CSRF,
                        planes_pago_id: planId,
                        conceptos: conceptos,
                    }
                })
                .done(function(r) {
                    closeModal('modalCrearPc');
                    initContable();
                    toast('success', r.message || 'Configuración guardada.');
                })
                .fail(function(xhr) {
                    let msg = 'Error al guardar.';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        if (xhr.responseJSON.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            msg = errors.join('. ');
                        }
                    }
                    toast(xhr.status === 400 || xhr.status === 422 ? 'warning' : 'error', msg);
                    console.error('Validation errors:', xhr.responseJSON);
                    console.error('Payload sent:', {
                        planes_pago_id: planId,
                        conceptos: conceptos
                    });
                })
                .always(function() {
                    $btn.prop('disabled', false).html('<i class="ri-save-line"></i> Guardar Todo');
                });
        });
        $('#precioRegularEditar, #descuentoBsEditar').on('input', function() {
            const regular = parseFloat($('#precioRegularEditar').val()) || 0,
                descuento = parseFloat($('#descuentoBsEditar').val()) || 0,
                pago = Math.max(0, regular - descuento);
            $('#pagoBsEditar').val(pago.toFixed(2));
        });
        $('#btnActualizarPc').on('click', function() {
            const id = $('#idEditarPc').val(),
                nCuotas = $('#nCuotasEditar').val(),
                precioRegular = $('#precioRegularEditar').val(),
                descuentoBs = $('#descuentoBsEditar').val();
            if (!nCuotas || !precioRegular) {
                toast('warning', 'Complete todos los campos obligatorios.');
                return;
            }
            setBtnLoading('#btnActualizarPc', true, 'Actualizando…');
            $.ajax({
                    url: '/admin/posgrads/ofertas/planes-conceptos/' + id,
                    type: 'PUT',
                    data: {
                        _token: CSRF,
                        n_cuotas: nCuotas,
                        precio_regular: precioRegular,
                        descuento_bs: descuentoBs || 0,
                    }
                })
                .done(function(r) {
                    closeModal('modalEditarPc');
                    initContable();
                    toast('success', r.message || 'Configuración actualizada.');
                })
                .fail(function() {
                    toast('error', 'Error al actualizar.');
                })
                .always(function() {
                    setBtnLoading('#btnActualizarPc', false,
                        '<i class="ri-refresh-line"></i> Actualizar');
                });
        });
        $('#btnConfirmarEliminarPc').on('click', function() {
            const id = $('#idEliminarPc').val();
            if (!id) return;
            setBtnLoading('#btnConfirmarEliminarPc', true, 'Eliminando…');
            $.ajax({
                    url: '/admin/posgrads/ofertas/planes-conceptos/' + id,
                    type: 'DELETE',
                    data: {
                        _token: CSRF
                    }
                })
                .done(function(r) {
                    closeModal('modalEliminarPc');
                    initContable();
                    toast('success', r.message || 'Configuración eliminada.');
                })
                .fail(function(xhr) {
                    const msg = xhr.responseJSON?.message || 'Error al eliminar.';
                    toast(xhr.status === 400 ? 'warning' : 'error', msg);
                })
                .always(function() {
                    setBtnLoading('#btnConfirmarEliminarPc', false,
                        '<i class="ri-delete-bin-line"></i> Eliminar');
                });
        });
        document.querySelector('.oferta-tab[data-tab="contable"]').addEventListener('click', function() {
            initContable();
        }, {
            once: true
        });

        // Función para renderizar el detalle del plan en el modal de cambio
        function renderConceptosParaCambio(data) {
            let html = '';
            data.forEach(function(item, idx) {
                const pagoBsOriginal = parseFloat(item.pago_bs);
                const esUnaCuota = item.n_cuotas === 1;
                let badgeClass = 'background: rgba(16, 185, 129, 0.15); color: #059669;';

                html +=
                    '<div class="mb-3 p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0;">';
                html += '<div class="d-flex justify-content-between align-items-center mb-2">';
                html += '<span class="fw-semibold" style="color: #334155;">' + escHtml(item.concepto) +
                    '</span>';
                html += '<div class="d-flex align-items-center gap-2">';
                html += '<span class="badge badge-total-concepto-cambio" style="' + badgeClass +
                    ' font-size: 0.8rem;" data-original="' + pagoBsOriginal + '">Total: Bs. ' + parseFloat(
                        item.pago_bs).toFixed(2) + '</span>';
                html += '<span class="badge badge-diferencia-cambio" style="font-size: 0.7rem;"></span>';
                html += '</div></div>';

                const habilitarDividir = !esUnaCuota;
                const disabledDividir = habilitarDividir ? '' : 'disabled';
                html +=
                    '<div class="mb-2 p-2 rounded" style="background: #f0f9ff; border: 1px solid #bae6fd;">';
                html += '<div class="d-flex align-items-center gap-2 flex-wrap">';
                html +=
                    '<input type="number" class="form-control form-control-sm" id="cambiar-monto-pagar-' +
                    idx + '" placeholder="Monto por cuota" min="0" step="1" style="width: 130px;" ' +
                    disabledDividir + '>';
                html +=
                    '<button type="button" class="btn btn-sm btn-info text-white btn-dividir-cuotas-cambio" data-concepto-idx="' +
                    idx + '" data-n-cuotas="' + item.n_cuotas + '" ' + disabledDividir +
                    '><i class="ri-divide-line"></i> Aplicar a ' + item.n_cuotas + ' cuotas</button>';
                if (item.n_cuotas > 1) {
                    html +=
                        '<button type="button" class="btn btn-sm btn-secondary btn-invertir-cuotas-cambio ms-2" data-concepto-idx="' +
                        idx +
                        '" title="Invertir orden de cuotas"><i class="ri-swap-line"></i> Invertir</button>';
                }
                if (!habilitarDividir) {
                    html +=
                        '<small class="text-muted ms-2"><i class="ri-information-line"></i> Cuota única</small>';
                }
                html += '</div></div>';

                html += '<table class="table table-sm mb-2" style="font-size: 0.8rem;">';
                html +=
                    '<thead style="background: #f1f5f9;"><tr><th>#</th><th>Monto (Bs)</th><th>Fecha Vencimiento</th></tr></thead><tbody>';

                item.cuotas.forEach(function(cuota, cuotaIdx) {
                    const fechaVenc = cuota.fecha_vencimiento || '';
                    const disabledAttr = esUnaCuota ? 'disabled' : '';
                    html += '<tr>';
                    html += '<td>Cuota ' + cuota.n_cuota + '</td>';
                    html +=
                        '<td><input type="number" class="form-control form-control-sm cuota-monto-cambio" data-concepto-idx="' +
                        idx + '" data-cuota-idx="' + cuotaIdx + '" value="' + cuota.monto_bs +
                        '" min="0" step="1" style="width: 100px;" ' + disabledAttr + '></td>';
                    html +=
                        '<td><input type="date" class="form-control form-control-sm cuota-fecha-cambio" data-concepto-idx="' +
                        idx + '" data-cuota-idx="' + cuotaIdx + '" value="' + fechaVenc +
                        '" style="width: 150px;"></td>';
                    html += '</tr>';
                });
                html += '</tbody></table>';
                if (esUnaCuota) {
                    html +=
                        '<small class="text-muted"><i class="ri-information-line"></i> Cuota única - monto no modificable</small>';
                }
                html += '</div>';
            });

            html += '<div class="text-end mt-3">';
            html += '<div id="cambiarMensajeValidacion" class="me-3" style="display: inline-block;"></div>';
            html += '</div>';

            $('#cambiarConceptosList').html(html);

            // Eventos para dividir cuotas
            $('.btn-dividir-cuotas-cambio').on('click', function() {
                const conceptoIdx = $(this).data('concepto-idx');
                const nCuotas = $(this).data('n-cuotas');
                const montoPorCuota = parseFloat($('#cambiar-monto-pagar-' + conceptoIdx).val()) || 0;
                const totalOriginal = parseFloat($('.badge-total-concepto-cambio').eq(conceptoIdx).data(
                    'original')) || 0;
                if (montoPorCuota <= 0) {
                    toast('warning', 'Ingrese un monto mayor a 0.');
                    return;
                }
                const montoUltimaCuota = totalOriginal - (montoPorCuota * (nCuotas - 1));
                if (montoUltimaCuota <= 0) {
                    toast('warning', 'El monto por cuota genera una última cuota con valor 0 o negativo. ' +
                        'Total: Bs. ' + totalOriginal.toFixed(2) + ', Cuotas: ' + nCuotas + '. Ingrese un monto menor.');
                    return;
                }
                $('.cuota-monto-cambio[data-concepto-idx="' + conceptoIdx + '"]').each(function(i) {
                    $(this).val((i === nCuotas - 1) ? montoUltimaCuota : montoPorCuota);
                });
                $('#cambiar-monto-pagar-' + conceptoIdx).val('');
                recalcularTotalConceptoCambio(conceptoIdx);
                toast('success', 'Monto distribuido en ' + nCuotas + ' cuota' + (nCuotas !== 1 ? 's' : '') + ' correctamente.');
            });

            $(document).on('click', '.btn-invertir-cuotas-cambio', function() {
                const conceptoIdx = $(this).data('concepto-idx');
                const $montos = $('.cuota-monto-cambio[data-concepto-idx="' + conceptoIdx + '"]');
                let valores = [];
                $montos.each(function() {
                    valores.push($(this).val());
                });
                valores.reverse();
                $montos.each(function(i) {
                    $(this).val(valores[i]);
                });
                recalcularTotalConceptoCambio(conceptoIdx);
                toast('success', 'Orden de cuotas invertido correctamente.');
            });

            $('.cuota-monto-cambio').on('change', function() {
                recalcularTotalConceptoCambio($(this).data('concepto-idx'));
            });

            data.forEach((_, idx) => recalcularTotalConceptoCambio(idx));
        }

        function recalcularTotalConceptoCambio(conceptoIdx) {
            let total = 0;
            $('.cuota-monto-cambio[data-concepto-idx="' + conceptoIdx + '"]').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            const badgeTotal = $('.badge-total-concepto-cambio').eq(conceptoIdx);
            const original = parseFloat(badgeTotal.data('original')) || 0;
            const diferencia = total - original;
            const badgeDif = $('.badge-diferencia-cambio').eq(conceptoIdx);
            badgeTotal.text('Total: Bs. ' + total.toFixed(2));
            if (diferencia === 0) {
                badgeDif.html(
                    '<span class="badge" style="background: rgba(34, 197, 94, 0.15); color: #16a34a;"><i class="ri-check-line"></i> Correcto</span>'
                );
            } else if (diferencia > 0) {
                badgeDif.html(
                    '<span class="badge" style="background: rgba(239, 68, 68, 0.15); color: #dc2626;"><i class="ri-error-warning-line"></i> Exceso: Bs. ' +
                    diferencia.toFixed(2) + '</span>');
            } else {
                badgeDif.html(
                    '<span class="badge" style="background: rgba(245, 158, 11, 0.15); color: #d97706;"><i class="ri-alert-line"></i> Falta: Bs. ' +
                    Math.abs(diferencia).toFixed(2) + '</span>');
            }
            actualizarEstadoBotonCambio();
        }

        function actualizarEstadoBotonCambio() {
            let hayErrores = false;
            $('.badge-total-concepto-cambio').each(function() {
                const original = parseFloat($(this).data('original')) || 0;
                const actual = parseFloat($(this).text().replace('Total: Bs. ', '')) || 0;
                if (actual !== original) hayErrores = true;
            });
            const btn = $('#btnConfirmarCambiarAInscrito');
            const msgDiv = $('#cambiarMensajeValidacion');
            if (hayErrores) {
                btn.prop('disabled', true);
                msgDiv.html(
                    '<span class="text-danger"><i class="ri-alert-line"></i> Corrige los montos antes de guardar</span>'
                );
            } else {
                btn.prop('disabled', false);
                msgDiv.html('<span class="text-success"><i class="ri-check-line"></i> Los montos coinciden</span>');
            }
        }

// Función para cargar el detalle de un plan específico
        function cargarDetallePlanCambio(planId) {
            if (!planId) return;
            $('#cambiarConceptosContainer').hide();
            $('#cambiarConceptosList').html(
                '<div class="text-center py-3"><span class="spinner-border spinner-border-sm"></span> Cargando detalle...</div>'
            );
            $('#cambiarConceptosContainer').show();

            $.ajax({
                url: '/admin/ofertas-academicas/' + window.OFERTA_ID + '/plan/' + planId + '/detalle',
                type: 'GET',
                success: function(response) {
                    if (response.success && response.data.length) {
                        renderConceptosParaCambio(response.data);
                        $('#cambiarConceptosContainer').show();
                    } else {
                        $('#cambiarConceptosList').html(
                            '<p class="text-muted">No hay conceptos configurados para este plan.</p>');
                    }
                },
                error: function() {
                    $('#cambiarConceptosList').html(
                        '<p class="text-danger">Error al cargar el detalle del plan.</p>');
                }
            });
        }

        // ===== FINANZAS CHARTS =====
        (function() {
            'use strict';
            
            var resumenPorConcepto = null;
            if (!resumenPorConcepto || Object.keys(resumenPorConcepto).length === 0) return;

            var conceptos = Object.keys(resumenPorConcepto);
            var conceptosFiltrados = conceptos.filter(function(c) {
                return (resumenPorConcepto[c].total || 0) > 0;
            });
            
            if (conceptosFiltrados.length === 0) return;

            var ingresosData = conceptosFiltrados.map(function(c) {
                return resumenPorConcepto[c].pagado || 0;
            });
            var cobranzaData = conceptosFiltrados.map(function(c) {
                return resumenPorConcepto[c].porcentaje || 0;
            });

            // Colors
            var colors = {
                'Matrícula': '#2563eb',
                'Colegiatura': '#0891b2',
                'Certificación': '#d97706',
            };
            var bgColors = conceptosFiltrados.map(function(c) {
                return colors[c] || '#64748b';
            });

            // Ingresos por Concepto (Pie/Doughnut)
            var ctx1 = document.getElementById('ingresosConceptoChart');
            if (ctx1) {
                new Chart(ctx1.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: conceptosFiltrados,
                        datasets: [{
                            data: ingresosData,
                            backgroundColor: bgColors,
                            borderWidth: 0,
                            hoverOffset: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: { 
                                position: 'bottom', 
                                labels: { 
                                    boxWidth: 12, 
                                    padding: 15,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: { size: 11, family: "'Inter', sans-serif" }
                                } 
                            }
                        },
                        animation: {
                            animateRotate: true,
                            animateScale: true,
                            duration: 1000,
                            easing: 'easeOutQuart'
                        }
                    }
                });
            }

            // Estado de Cobranza (Bar)
            var ctx2 = document.getElementById('cobranzaConceptoChart');
            if (ctx2) {
                new Chart(ctx2.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: conceptosFiltrados,
                        datasets: [{
                            label: '% Cobrado',
                            data: cobranzaData,
                            backgroundColor: bgColors,
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        scales: {
                            x: { 
                                beginAtZero: true, 
                                max: 100,
                                grid: { color: '#f1f5f9' },
                                ticks: { font: { size: 10 } }
                            },
                            y: {
                                grid: { display: false },
                                ticks: { font: { size: 10 } }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleFont: { size: 12 },
                                bodyFont: { size: 11 },
                                padding: 10,
                                cornerRadius: 8,
                            }
                        },
                        animation: {
                            duration: 1200,
                            easing: 'easeOutQuart'
                        }
                    }
                });
            }
        })();
    })();

    // =========================================================================
    // MOODLE — Matrícula de estudiantes
    // =========================================================================
    (function () {
        'use strict';
        const CSRF = 'null';
        let moduloActual = { id: null, nombre: '', programa: '' };
        let estudiantesData = [];
        let whatsappWindowCounter = 0;

        // Abrir modal al hacer clic en el botón de matrícula
        $(document).on('click', '.btn-matricular-moodle', function (e) {
            e.stopPropagation();
            moduloActual.id     = $(this).data('id');
            moduloActual.nombre = $(this).data('nombre');
            abrirModalMatricula();
        });

        function abrirModalMatricula() {
            document.getElementById('moodleModuloNombre').textContent = moduloActual.nombre;
            mostrarEstado('loading');
            new bootstrap.Modal(document.getElementById('modalMatricularMoodle')).show();
            cargarEstudiantes();
        }

        function cargarEstudiantes() {
            $.ajax({
                url: '/admin/posgrads/modulos/' + moduloActual.id + '/moodle/estudiantes',
                method: 'GET'
            }).done(function (r) {
                if (!r.success && r.sin_curso_moodle) {
                    mostrarEstado('sin-curso');
                    return;
                }
                if (!r.success) {
                    mostrarEstado('error', r.message || 'Error al cargar estudiantes.');
                    return;
                }
                moduloActual.programa = r.programa_nombre || 'Programa';
                const cursoBadge = document.getElementById('moodleCursoNombre');
                const cursoBody = document.getElementById('moodleCursoNombreBody');
                const cursoInfo = document.getElementById('moodleCursoInfo');
                if (r.moodle_course_nombre) {
                    if (cursoBadge) {
                        cursoBadge.textContent = r.moodle_course_nombre;
                        cursoBadge.style.display = '';
                    }
                    if (cursoBody) {
                        cursoBody.textContent = 'Vinculado a: ' + r.moodle_course_nombre;
                    }
                    if (cursoInfo) {
                        cursoInfo.style.display = '';
                    }
                } else {
                    if (cursoBadge) cursoBadge.style.display = 'none';
                    if (cursoInfo) cursoInfo.style.display = 'none';
                }
                estudiantesData = r.estudiantes || [];
                renderTablaEstudiantes();
                actualizarBotonWhatsApp();
                mostrarEstado('lista');
            }).fail(function () {
                mostrarEstado('error', 'No se pudo conectar con el servidor.');
            });
        }

        function renderTablaEstudiantes() {
            const tbody = document.getElementById('moodleEstudiantesBody');
            const btn = document.getElementById('btnMatricularSeleccionados');
            if (!estudiantesData.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3"><i class="ri-inbox-line"></i> No hay estudiantes inscritos en esta oferta.</td></tr>';
                if (btn) btn.disabled = true;
                return;
            }

            const matriculados = estudiantesData.filter(e => e.en_curso);
            const noMatriculados = estudiantesData.filter(e => !e.en_curso);

            function renderFila(e) {
                const tieneCuenta = e.tiene_cuenta ?? e.en_moodle;
                const badge = e.en_curso
                    ? '<span class="badge bg-success-subtle text-success"><i class="ri-checkbox-circle-line"></i> Matriculado</span>'
                    : (tieneCuenta ? '<span class="badge bg-info-subtle text-info"><i class="ri-user-line"></i> Cuenta</span>'
                    : '<span class="badge bg-warning-subtle text-warning"><i class="ri-user-add-line"></i> Sin cuenta</span>');
                return `<tr>
                    <td class="text-center">
                        <input type="checkbox" class="form-check-input chk-estudiante" ${e.en_curso ? '' : 'checked'}
                            data-estudiante-id="${e.estudiante_id}"
                            data-moodle-user-id="${e.moodle_user_id || ''}"
                            data-celular="${e.celular || ''}"
                            data-nombre="${escHtml(e.nombre)}"
                            data-username="${escHtml(e.username)}"
                            data-password="${escHtml(e.carnet)}"
                            ${e.en_curso ? 'disabled' : ''}>
                    </td>
                    <td><div style="font-weight:600;font-size:0.85rem;">${escHtml(e.nombre)}</div>
                        <div style="font-size:0.75rem;color:var(--d-muted);">${escHtml(e.carnet)}</div></td>
                    <td style="font-size:0.8rem;font-family:monospace;">${escHtml(e.username)}</td>
                    <td>${badge}</td>
                    <td style="font-size:0.75rem;color:var(--d-muted);">${e.en_curso ? '—' : 'Pass: ' + escHtml(e.carnet)}</td>
                </tr>`;
            }

            let html = '';

            if (matriculados.length > 0) {
                html += `<tr><td colspan="5" class="bg-success bg-opacity-10 py-2 fw-semibold text-success">
                    <i class="ri-check-line me-1"></i> ${matriculados.length} estudiante${matriculados.length !== 1 ? 's' : ''} ya matriculado${matriculados.length !== 1 ? 's' : ''} en este módulo
                </td></tr>`;
                html += matriculados.map(renderFila).join('');
            }

            if (noMatriculados.length > 0) {
                html += `<tr><td colspan="5" class="bg-warning bg-opacity-10 py-2 fw-semibold text-warning">
                    <i class="ri-user-add-line me-1"></i> ${noMatriculados.length} estudiante${noMatriculados.length !== 1 ? 's' : ''} por matricular
                </td></tr>`;
                html += noMatriculados.map(renderFila).join('');
            }

            tbody.innerHTML = html;
            if (btn) btn.disabled = noMatriculados.length === 0;
            actualizarContadorSeleccion();

            tbody.addEventListener('change', actualizarContadorSeleccion);
        }

        function actualizarContadorSeleccion() {
            const total = document.querySelectorAll('.chk-estudiante:checked').length;
            document.getElementById('moodleContadorSeleccion').textContent =
                total + ' estudiante' + (total !== 1 ? 's' : '') + ' seleccionado' + (total !== 1 ? 's' : '');
        }

        function actualizarBotonWhatsApp() {
            const btnWhats = document.getElementById('btnEnviarWhatsAppMasivo');
            const conCelular = estudiantesData.filter(e => e.celular && e.celular.length >= 8);
            if (btnWhats) {
                btnWhats.style.display = conCelular.length > 0 ? '' : 'none';
            }
        }

        function enviarWhatsAppMasivo() {
            const seleccionados = [];
            document.querySelectorAll('.chk-estudiante:checked').forEach(function (chk) {
                const celular = chk.dataset.celular;
                if (celular && celular.length >= 8) {
                    seleccionados.push({
                        celular: celular.replace(/[^0-9]/g, ''),
                        nombre: chk.dataset.nombre,
                        username: chk.dataset.username,
                        password: chk.dataset.password,
                    });
                }
            });

            if (seleccionados.length === 0) {
                alert('Seleccione estudiantes con celular registrado que tengan cuenta en Moodle.');
                return;
            }

            seleccionados.forEach(function (est, index) {
                const mensaje = '*¡Bienvenido/a a ' + moduloActual.programa + '!*\n\n' +
                    'Estimado/a ' + est.nombre + ',\n\n' +
                    'Se le ha matriculado en el módulo ' + moduloActual.nombre + '. A continuación, sus datos de acceso:\n\n' +
                    '*Plataforma:* http://moodle52.localhost/\n' +
                    '*Usuario:* ' + est.username + '\n' +
                    '*Contraseña:* ' + est.password + '\n\n' +
                    '*Área Académica Innova-Ciencia-Virtual*';

                setTimeout(function () {
                    const url = 'https://wa.me/' + est.celular + '?text=' + encodeURIComponent(mensaje);
                    const windowName = 'wa_' + (++whatsappWindowCounter) + '_' + Date.now();
                    window.open(url, windowName, 'width=800,height=600');
                }, index * 1200);
            });

            toast('success', 'Abriendo WhatsApp para ' + seleccionados.length + ' estudiante(s).');
        }

        document.getElementById('btnSeleccionarTodosMoodle')?.addEventListener('click', function () {
            document.querySelectorAll('.chk-estudiante').forEach(c => c.checked = true);
            actualizarContadorSeleccion();
        });

        document.getElementById('btnDeseleccionarTodosMoodle')?.addEventListener('click', function () {
            document.querySelectorAll('.chk-estudiante').forEach(c => c.checked = false);
            actualizarContadorSeleccion();
        });

        document.getElementById('btnEnviarWhatsAppMasivo')?.addEventListener('click', enviarWhatsAppMasivo);

        document.getElementById('btnMatricularSeleccionados')?.addEventListener('click', function () {
            const seleccionados = [];
            document.querySelectorAll('.chk-estudiante:checked').forEach(function (chk) {
                seleccionados.push({
                    estudiante_id:  parseInt(chk.dataset.estudianteId),
                    moodle_user_id: chk.dataset.moodleUserId ? parseInt(chk.dataset.moodleUserId) : null,
                });
            });
            if (!seleccionados.length) {
                alert('Selecciona al menos un estudiante.');
                return;
            }
            ejecutarMatricula(seleccionados);
        });

        function ejecutarMatricula(seleccionados) {
            const btn = document.getElementById('btnMatricularSeleccionados');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Matriculando...';

            $.ajax({
                url: '/admin/posgrads/modulos/' + moduloActual.id + '/moodle/matricular',
                method: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': CSRF },
                data: JSON.stringify({ estudiantes: seleccionados }),
            }).done(function (r) {
                renderResultados(r.resultados || []);
                mostrarEstado('resultados');
            }).fail(function (xhr) {
                const msg = xhr.responseJSON?.message || 'Error al matricular.';
                mostrarEstado('error', msg);
            }).always(function () {
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-user-add-line"></i> Matricular seleccionados';
            });
        }

        function renderResultados(resultados) {
            const container = document.getElementById('moodleResultadosBody');
            let html = '';
            resultados.forEach(function (r) {
                const icon = r.ok
                    ? '<i class="ri-checkbox-circle-fill text-success"></i>'
                    : '<i class="ri-close-circle-fill text-danger"></i>';
                html += `<div class="d-flex align-items-center gap-2 py-1 border-bottom" style="font-size:0.83rem;">
                    ${icon}
                    <span style="flex:1;font-weight:500;">${escHtml(r.nombre || 'ID ' + r.estudiante_id)}</span>
                    <span style="color:var(--d-muted);">${escHtml(r.mensaje)}</span>
                </div>`;
            });
            container.innerHTML = html || '<p class="text-muted text-center">Sin resultados.</p>';
        }

        function mostrarEstado(estado, mensaje) {
            const ids = ['moodleLoadingState','moodleSinCursoState','moodleErrorState','moodleListaState','moodleResultadosState'];
            ids.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.style.display = 'none';
            });
            const mapa = {
                'loading'    : 'moodleLoadingState',
                'sin-curso'  : 'moodleSinCursoState',
                'error'      : 'moodleErrorState',
                'lista'      : 'moodleListaState',
                'resultados' : 'moodleResultadosState',
            };
            const el = document.getElementById(mapa[estado]);
            if (el) el.style.display = '';
            if (estado === 'error' && mensaje) {
                const msgEl = document.getElementById('moodleErrorMsg');
                if (msgEl) msgEl.textContent = mensaje;
            }
        }

        // Volver a la lista desde los resultados
        document.getElementById('btnVolverListaMoodle')?.addEventListener('click', function () {
            renderTablaEstudiantes();
            mostrarEstado('lista');
        });

        // Crear curso en Moodle para módulos que aún no tienen uno
        document.getElementById('btnCrearCursoMoodle')?.addEventListener('click', function () {
            const btn = this;
            const msgEl = document.getElementById('crearCursoMoodleMsg');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Creando curso...';
            msgEl.style.display = 'none';

            $.ajax({
                url: '/admin/posgrads/modulos/' + moduloActual.id + '/moodle/crear-curso',
                method: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': CSRF },
                data: JSON.stringify({}),
            }).done(function (r) {
                if (r.success) {
                    msgEl.className = 'mt-2 text-success';
                    msgEl.textContent = r.message;
                    msgEl.style.display = '';
                    // Esperar un momento y luego recargar la lista de estudiantes
                    setTimeout(function () {
                        mostrarEstado('loading');
                        cargarEstudiantes();
                    }, 900);
                } else {
                    msgEl.className = 'mt-2 text-danger';
                    msgEl.textContent = r.message || 'No se pudo crear el curso.';
                    msgEl.style.display = '';
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ri-graduation-cap-line"></i> Crear curso en Moodle';
                }
            }).fail(function (xhr) {
                const msg = xhr.responseJSON?.message || 'Error al conectar con el servidor.';
                msgEl.className = 'mt-2 text-danger';
                msgEl.textContent = msg;
                msgEl.style.display = '';
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-graduation-cap-line"></i> Crear curso en Moodle';
            });
        });

        function crearCursoYMatricularDocente(moduloId, docenteId) {
            $.ajax({
                url: '/admin/posgrads/modulos/' + moduloId + '/moodle/crear-curso-y-matricular-docente',
                method: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': CSRF },
                data: JSON.stringify({ docente_id: docenteId }),
            }).done(function (r) {
                if (r.success) {
                    toast('success', 'Curso creado y docente matriculado en Moodle.');
                } else {
                    toast('error', r.message || 'Error al crear curso y matricular docente.');
                }
            }).fail(function (xhr) {
                const msg = xhr.responseJSON?.message || 'Error al conectar con el servidor.';
                toast('error', msg);
            });
        }

        function escHtml(str) {
            return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        // ===== CONTROL DE ACCESO MOODLE =====
        let _caData = null;        // datos cargados
        let _caState = {};         // { "inscripcionId-moduloId": true=bloqueado / false=habilitado }

        window.cargarControlAcceso = function cargarControlAcceso(force) {
            if (_caData && !force) return;
            document.getElementById('plataformaLoading').style.display = '';
            document.getElementById('plataformaContent').style.display = 'none';
            document.getElementById('plataformaEmpty').style.display = 'none';

            $.ajax({ url: '/admin/posgrads/ofertas/' + window.OFERTA_ID + '/moodle/control-acceso' })
                .done(function(r) {
                    document.getElementById('plataformaLoading').style.display = 'none';
                    if (!r.success || !r.estudiantes || r.estudiantes.length === 0) {
                        document.getElementById('plataformaEmpty').style.display = '';
                        return;
                    }
                    _caData = r;
                    _caState = {};
                    r.estudiantes.forEach(function(est) {
                        Object.keys(est.modulos).forEach(function(mid) {
                            const md = est.modulos[mid];
                            if (md.matriculado && md.acceso_suspendido) {
                                _caState[est.inscripcion_id + '-' + mid] = true;
                            }
                        });
                    });
                    _renderCA();
                    document.getElementById('plataformaContent').style.display = '';
                })
                .fail(function() {
                    document.getElementById('plataformaLoading').style.display = 'none';
                    document.getElementById('plataformaEmpty').style.display = '';
                    toast('error', 'Error al cargar datos de acceso Moodle.');
                });
        }

        function _renderCA() {
            const modulos = _caData.modulos;
            const estudiantes = _caData.estudiantes;

            // Header
            let th = '<tr style="font-size:.8rem;">';
            th += '<th style="min-width:200px;position:sticky;left:0;background:var(--d-bg);z-index:2;">Estudiante</th>';
            modulos.forEach(function(m) {
                th += '<th style="min-width:155px;text-align:center;">';
                th += '<div style="font-weight:600;">' + escHtml(m.nombre) + '</div>';
                if (!m.moodle_course_id) {
                    th += '<div style="color:#ef4444;font-size:.7rem;font-weight:400;"><i class="ri-alert-line"></i> Sin curso Moodle</div>';
                }
                th += '</th>';
            });
            th += '</tr>';
            document.getElementById('plataformaHead').innerHTML = th;

            // Body
            let tb = '';
            estudiantes.forEach(function(est) {
                tb += '<tr>';
                // Sticky name cell
                tb += '<td style="position:sticky;left:0;background:var(--d-bg);z-index:1;vertical-align:middle;">';
                tb += '<div style="font-weight:600;font-size:.85rem;">' + escHtml(est.nombre) + '</div>';
                if (!est.tiene_cuenta_moodle) {
                    tb += '<div style="font-size:.72rem;color:#94a3b8;margin-top:2px;"><i class="ri-user-x-line"></i> Sin cuenta Moodle</div>';
                }
                tb += '</td>';

                modulos.forEach(function(m) {
                    const md = est.modulos[m.id];
                    tb += '<td style="text-align:center;vertical-align:middle;padding:8px;">';

                    if (!est.tiene_cuenta_moodle) {
                        tb += '<span style="color:#94a3b8;font-size:.78rem;">—</span>';
                    } else if (!m.moodle_course_id) {
                        tb += '<span style="color:#94a3b8;font-size:.75rem;">Sin curso</span>';
                    } else if (!md || !md.matriculado) {
                        tb += '<span style="color:#94a3b8;font-size:.75rem;">No matriculado</span>';
                    } else {
                        const sk = est.inscripcion_id + '-' + m.id;
                        const bloqueado = _caState[sk] === true;
                        const cuota = md.cuota;

                        if (bloqueado) {
                            tb += _celdaBloqueado(m.id, est.inscripcion_id, sk, m.nombre);
                        } else if (!cuota) {
                            tb += '<span style="color:#94a3b8;font-size:.78rem;">Sin cuota</span>';
                        } else if (cuota.pagada) {
                            tb += _celdaPagado(cuota);
                        } else if (cuota.vencida) {
                            tb += _celdaVencida(m.id, est.inscripcion_id, sk, cuota, m.nombre);
                        } else {
                            tb += _celdaPendiente(cuota);
                        }
                    }
                    tb += '</td>';
                });
                tb += '</tr>';
            });
            document.getElementById('plataformaBody').innerHTML = tb;
        }

        function _celdaPagado(c) {
            return '<div style="color:#16a34a;font-size:.78rem;font-weight:600;line-height:1.4;">' +
                '<i class="ri-checkbox-circle-fill" style="font-size:1rem;"></i><br>Pagado<br>' +
                '<small style="color:#64748b;font-weight:400;">Bs ' + Number(c.monto_bs).toFixed(2) + '</small></div>';
        }

        function _celdaPendiente(c) {
            const f = _fmtFecha(c.fecha_vencimiento);
            return '<div style="color:#d97706;font-size:.78rem;font-weight:600;line-height:1.4;">' +
                '<i class="ri-time-line" style="font-size:1rem;"></i><br>Pendiente<br>' +
                '<small style="color:#64748b;font-weight:400;">' + f + '</small></div>';
        }

        function _celdaVencida(moduloId, inscripcionId, sk, c, nombreModulo) {
            return '<div style="display:flex;flex-direction:column;align-items:center;gap:5px;">' +
                '<div style="color:#dc2626;font-size:.78rem;font-weight:700;line-height:1.3;">' +
                '<i class="ri-alarm-warning-line"></i> Vencida<br>Bs ' + Number(c.pago_pendiente_bs).toFixed(2) + '</div>' +
                '<button class="btn-ca" data-mod="' + moduloId + '" data-ins="' + inscripcionId +
                '" data-sk="' + sk + '" data-nombre="' + escHtml(nombreModulo) + '" data-sus="1"' +
                ' style="padding:4px 12px;border-radius:7px;border:none;background:linear-gradient(135deg,#dc2626,#b91c1c);' +
                'color:#fff;font-size:.75rem;font-weight:700;cursor:pointer;">' +
                '<i class="ri-lock-line"></i> Bloquear</button>' +
                '</div>';
        }

        function _celdaBloqueado(moduloId, inscripcionId, sk, nombreModulo) {
            return '<div style="display:flex;flex-direction:column;align-items:center;gap:5px;">' +
                '<div style="color:#b91c1c;font-size:.78rem;font-weight:700;background:rgba(239,68,68,.1);' +
                'padding:3px 8px;border-radius:6px;"><i class="ri-lock-fill"></i> BLOQUEADO</div>' +
                '<button class="btn-ca" data-mod="' + moduloId + '" data-ins="' + inscripcionId +
                '" data-sk="' + sk + '" data-nombre="' + escHtml(nombreModulo) + '" data-sus="0"' +
                ' style="padding:4px 12px;border-radius:7px;border:none;background:linear-gradient(135deg,#16a34a,#15803d);' +
                'color:#fff;font-size:.75rem;font-weight:700;cursor:pointer;">' +
                '<i class="ri-lock-unlock-line"></i> Habilitar</button>' +
                '</div>';
        }

        function _fmtFecha(s) {
            if (!s) return '—';
            const [y,m,d] = s.split('-');
            const mm = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
            return parseInt(d) + ' ' + mm[parseInt(m)-1] + '/' + y.slice(2);
        }

        // Variable para almacenar la acción pendiente
        let pendienteAccesoPlataforma = null;
        let modalAccesoPlataformaInstance = null;

        // Función para ejecutar el cambio de acceso
        function ejecutarCambioAcceso(btn, moduloId, inscripcionId, sk, nombreModulo, suspender) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || window.CSRF || '';
            console.log('CSRF Token:', csrfToken);
            console.log('Params:', { moduloId, inscripcionId, suspender });
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:.8rem;height:.8rem;"></span>';

            fetch('/admin/posgrads/modulos/' + moduloId + '/moodle/suspender-acceso?_=' + Date.now(), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ inscripcion_id: parseInt(inscripcionId), suspender: suspender })
            }).then(function(response) {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }
                return response.json();
            }).then(function(r) {
                console.log('Response data:', r);
                if (r.success) {
                    _caState[sk] = suspender;
                    var accion = suspender ? 'suspendido' : 'habilitado';
                    toast('success', 'El acceso del estudiante ha sido ' + accion + ' correctamente en ' + nombreModulo);
                    _renderCA();
                } else {
                    toast('error', r.message || 'Error al actualizar acceso.');
                    btn.disabled = false;
                    btn.innerHTML = suspender
                        ? '<i class="ri-lock-line"></i> Bloquear'
                        : '<i class="ri-lock-unlock-line"></i> Habilitar';
                }
            }).catch(function(err) {
                console.error('Error:', err);
                toast('error', 'Error de conexión.');
                btn.disabled = false;
                btn.innerHTML = suspender
                    ? '<i class="ri-lock-line"></i> Bloquear'
                    : '<i class="ri-lock-unlock-line"></i> Habilitar';
            });
        }

        // Delegación de eventos para botones bloquear/habilitar con modal de confirmación
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-ca');
            if (!btn) return;

            e.preventDefault();
            e.stopPropagation();

            const moduloId    = btn.dataset.mod;
            const inscripcionId = btn.dataset.ins;
            const sk          = btn.dataset.sk;
            const nombreModulo = btn.dataset.nombre || 'módulo';
            const nombreEstudiante = btn.closest('tr')?.querySelector('td:first-child div')?.textContent || 'Estudiante';
            const suspender   = btn.dataset.sus === '1';

            // Almacenar la acción pendiente
            pendienteAccesoPlataforma = { btn, moduloId, inscripcionId, sk, nombreModulo, nombreEstudiante, suspender };

            // Configurar el modal
            const titulo = suspender ? 'Bloquear Acceso' : 'Habilitar Acceso';
            const mensaje = suspender 
                ? '¿Está seguro que desea <strong>bloquear</strong> el acceso a la plataforma Moodle para este estudiante? El estudiante no podrá acceder al contenido del módulo hasta que se reactive el acceso.'
                : '¿Está seguro que desea <strong>habilitar</strong> el acceso a la plataforma Moodle para este estudiante? El estudiante podrá acceder nuevamente al contenido del módulo.';

            document.getElementById('modalConfirmarAccesoPlataformaTitle').innerHTML = '<i class="ri-shield-keyhole-line me-2"></i> ' + titulo;
            document.getElementById('modalConfirmarAccesoPlataformaMsg').innerHTML = mensaje;
            document.getElementById('modalEstudiantePlataforma').textContent = nombreEstudiante;
            document.getElementById('modalModuloPlataforma').textContent = nombreModulo;

            // Cambiar color del botón según la acción
            document.getElementById('btnConfirmarAccesoPlataforma').style.background = suspender ? '#dc2626' : '#16a34a';

            // Mostrar modal
            const modalEl = document.getElementById('modalConfirmarAccesoPlataforma');
            if (!modalAccesoPlataformaInstance) {
                modalAccesoPlataformaInstance = new bootstrap.Modal(modalEl);
            }
            modalAccesoPlataformaInstance.show();
        });

        // Confirmar acción desde el modal
        document.getElementById('btnConfirmarAccesoPlataforma').addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            if (!pendienteAccesoPlataforma) return;

            const { btn, moduloId, inscripcionId, sk, nombreModulo, nombreEstudiante, suspender } = pendienteAccesoPlataforma;

            // Cerrar modal
            if (modalAccesoPlataformaInstance) {
                modalAccesoPlataformaInstance.hide();
            }

            // Ejecutar la acción
            ejecutarCambioAcceso(btn, moduloId, inscripcionId, sk, nombreModulo, suspender);

            pendienteAccesoPlataforma = null;
        });
        // ===== FIN CONTROL DE ACCESO =====

    })();
</script>
