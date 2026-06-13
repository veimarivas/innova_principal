<script src="{{ URL::asset('build/libs/fullcalendar/index.global.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    (function() {
        'use strict';
        const CSRF = '{{ csrf_token() }}';
        window.OFERTA_ID = {{ $oferta->id }};
        window.CANTIDAD_SESIONES = {{ $oferta->cantidad_sesiones }};
        window.CSRF = CSRF;
        window.MOODLE_URL = '{{ config('moodle.url') }}';
        window.PROGRAMA_NOMBRE = '{{ addslashes($oferta->programa?->nombre ?? $oferta->codigo) }}';
        window.OFERTA_VERSION    = '{{ $oferta->version }}';
        window.OFERTA_GRUPO      = '{{ $oferta->grupo }}';
        window.OFERTA_GESTION    = '{{ $oferta->gestion }}';
        window.OFERTA_POSGRADO   = '{{ addslashes($oferta->posgrado?->nombre ?? '') }}';
        let currentModuloId = null;
        let currentHorarioId = null;
        let allModulos = [];
        let moduloColors = {};
        let calendar = null;
        let selectedModuloFilter = null;

        // ===== Mover los modals del detalle al <body> para evitar conflictos =====
        // de stacking-context heredado del wrapper .oferta-details-theme-wrapper /
        // .oferta-detail-card (que provocaba que solo se viera el backdrop).
        function _relocateModalsToBody() {
            const ids = [
                'modalGeneralBaja',
                'modalContableDetalle',
                'modalAcademicoDetalle',
                'modalCrearCuentasMoodle',
                'modal2daInstancia',
                'modalEstudiosEstudiante',
                'modalConfirmarAccesoPlataforma',
            ];
            ids.forEach(function(id) {
                const el = document.getElementById(id);
                if (el && el.parentElement !== document.body) {
                    document.body.appendChild(el);
                }
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', _relocateModalsToBody);
        } else {
            _relocateModalsToBody();
        }

        // ===== Sincronización global del estado de una inscripción =====
        // Llamar a esta función después de cualquier toggle exitoso (`activo`,
        // `activo_contable`, `activo_academico`) para que TODAS las tabs reflejen
        // el cambio sin recargar la página. Acepta el objeto data devuelto por
        // /toggle-estado: { activo, activo_contable, activo_academico }.
        window.aplicarEstadoInscripcion = function aplicarEstadoInscripcion(inscripcionId, estado) {
            if (!inscripcionId || !estado) return;
            const map = {
                'activo':           !!estado.activo,
                'activo_contable':  !!estado.activo_contable,
                'activo_academico': !!estado.activo_academico,
            };
            Object.keys(map).forEach(function(campo) {
                const nuevoValor = map[campo];
                const label = nuevoValor ? 'Activo' : 'Baja';
                const tituloBase = campo === 'activo_contable' ? 'Estado contable'
                    : (campo === 'activo_academico' ? 'Estado académico' : 'Estado general');
                const titulo = tituloBase + ': ' + (nuevoValor
                    ? 'Activo (click para dar de baja)'
                    : 'Inactivo (click para activar)');

                $('.ins-estado-toggle[data-inscripcion-id="' + inscripcionId + '"][data-campo="' + campo + '"]').each(function() {
                    const $t = $(this);
                    $t.removeClass('on off').addClass(nuevoValor ? 'on' : 'off');
                    $t.attr('data-valor', nuevoValor ? 1 : 0).removeData('valor');
                    $t.find('.ins-estado-toggle-label').text(label);
                    $t.attr('title', titulo);

                    // Tints + sugerencias específicas
                    if ($t.hasClass('fin-contable-toggle') && campo === 'activo_contable'
                            && typeof actualizarSugerenciaContable === 'function') {
                        actualizarSugerenciaContable($t.closest('.fin-contable-cell'), nuevoValor);
                        pintarFilaContable($t.closest('tr.fin-row'), nuevoValor);
                    }
                    if ($t.hasClass('aa-academico-toggle') && campo === 'activo_academico'
                            && typeof actualizarSugerenciaAcademica === 'function') {
                        actualizarSugerenciaAcademica($t.closest('.aa-academico-cell'), nuevoValor);
                        pintarFilaAcademica($t.closest('tr'), nuevoValor);
                    }
                });
            });

            // Invalida caché del tab Plataforma Moodle (los chips Contable/Académico
            // y posibles bloqueos cambian). Si el tab está visible, recargar.
            if (typeof _caData !== 'undefined') { try { _caData = null; } catch(e){} }
            const tabPlat = document.getElementById('tab-plataforma');
            if (tabPlat && tabPlat.classList.contains('active') && typeof window.cargarControlAcceso === 'function') {
                window.cargarControlAcceso(true);
            }
        };

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

        function hexToRgba(hex, alpha) {
            if (!hex) return 'transparent';
            if (hex.startsWith('rgb')) {
                return hex;
            }
            hex = hex.replace('#', '');
            let r, g, b;
            if (hex.length === 3) {
                r = parseInt(hex.charAt(0) + hex.charAt(0), 16);
                g = parseInt(hex.charAt(1) + hex.charAt(1), 16);
                b = parseInt(hex.charAt(2) + hex.charAt(2), 16);
            } else if (hex.length === 6) {
                r = parseInt(hex.substring(0, 2), 16);
                g = parseInt(hex.substring(2, 4), 16);
                b = parseInt(hex.substring(4, 6), 16);
            } else {
                return hex;
            }
            return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
        }

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
                // No auto-cargar notas desde Moodle al abrir el tab:
                // las notas locales (matriculaciones.nota_regular) ya vienen renderizadas
                // server-side. El botón "Recargar notas" sigue disponible para sincronizar
                // contra Moodle manualmente.
                // if (target === 'area-academica') cargarAreaAcademicaNotas();
            });
        });

        // ===== ÁREA ACADÉMICA — carga notas por módulo desde Moodle =====
        let aaNotasCargadas = false;
        function cargarAreaAcademicaNotas(force) {
            if (aaNotasCargadas && !force) return;
            const $tabla = $('#tablaAreaAcademica');
            if (!$tabla.length) return;
            // Sólo módulos cuyo estado es "Concluido"
            const moduloIds = [];
            $tabla.find('thead th.aa-mod-col').each(function() {
                const id = $(this).data('modulo-id');
                const estado = $(this).data('modulo-estado');
                if (id && estado === 'Concluido') moduloIds.push(id);
            });
            if (moduloIds.length === 0) {
                aaNotasCargadas = true;
                return;
            }
            // Snapshot del valor server-side (matriculaciones.nota_regular) por si Moodle no tiene datos
            $tabla.find('.aa-mod-cell:not(.aa-mod-cell-blocked) .aa-nota').each(function() {
                const $n = $(this);
                if ($n.attr('data-original') === undefined) {
                    $n.attr('data-original', $n.text());
                }
            });
            // Marcar todas las celdas (no bloqueadas) como loading
            $tabla.find('.aa-mod-cell:not(.aa-mod-cell-blocked) .aa-nota').addClass('loading').text('…');
            $('#btnRefrescarNotasAA').addClass('is-loading').prop('disabled', true);

            let pendientes = moduloIds.length;
            moduloIds.forEach(function(mid) {
                $.ajax({
                    url: '/admin/posgrads/modulos/' + mid + '/academico/calificaciones',
                    type: 'GET',
                    timeout: 25000
                }).done(function(r) {
                    function restore($n) {
                        const orig = $n.attr('data-original');
                        $n.removeClass('loading').addClass('loaded').text(orig !== undefined ? orig : '—');
                    }
                    if (!r || r.success === false) {
                        // Sin datos en Moodle: restaurar el valor local (matriculaciones.nota_regular)
                        $tabla.find('.aa-mod-cell[data-modulo-id="' + mid + '"] .aa-nota').each(function() {
                            restore($(this));
                        });
                        return;
                    }
                    const estudiantes = r.estudiantes || [];
                    // Indexar por carnet (ci)
                    const porCi = {};
                    estudiantes.forEach(function(e) {
                        if (e.ci) porCi[String(e.ci).trim()] = e;
                    });
                    $tabla.find('.aa-mod-cell[data-modulo-id="' + mid + '"]').each(function() {
                        const ci = String($(this).data('carnet') || '').trim();
                        const est = porCi[ci];
                        const $final = $(this).find('.aa-nota-final');
                        const $segunda = $(this).find('.aa-nota-2da');
                        $final.removeClass('loading');
                        $segunda.removeClass('loading');
                        if (est && est.nota_final !== undefined && est.nota_final !== null) {
                            const nf = parseFloat(est.nota_final);
                            $final.text(isNaN(nf) ? '—' : nf.toFixed(2))
                                .addClass('loaded')
                                .toggleClass('ok', nf >= 71)
                                .toggleClass('fail', !isNaN(nf) && nf < 71);
                        } else {
                            restore($final);
                        }
                        // Nota 2da instancia: si el endpoint la trae en algún día, leerla
                        if (est && (est.nota_segunda !== undefined || est.nota_2da !== undefined)) {
                            const ns = parseFloat(est.nota_segunda ?? est.nota_2da);
                            $segunda.text(isNaN(ns) ? '—' : ns.toFixed(2))
                                .addClass('loaded')
                                .toggleClass('ok', ns >= 71)
                                .toggleClass('fail', !isNaN(ns) && ns < 71);
                        } else {
                            restore($segunda);
                        }
                    });
                }).fail(function() {
                    // Fallo de Moodle: restaurar valores locales
                    $tabla.find('.aa-mod-cell[data-modulo-id="' + mid + '"] .aa-nota').each(function() {
                        const $n = $(this);
                        const orig = $n.attr('data-original');
                        $n.removeClass('loading').addClass('loaded').text(orig !== undefined ? orig : '—');
                    });
                }).always(function() {
                    pendientes--;
                    if (pendientes <= 0) {
                        aaNotasCargadas = true;
                        $('#btnRefrescarNotasAA').removeClass('is-loading').prop('disabled', false);
                    }
                });
            });
        }
        $(document).on('click', '#btnRefrescarNotasAA', function() {
            cargarAreaAcademicaNotas(true);
        });

        // ===== ÁREA ACADÉMICA — modal de estudios =====
        $(document).on('click', '.aa-btn-estudios', function() {
            const $btn = $(this);
            let estudios = $btn.attr('data-estudios');
            try { estudios = JSON.parse(estudios); } catch (e) { estudios = []; }
            const nombre = $btn.data('estudiante') || '—';
            const carnet = $btn.data('carnet') || '—';
            $('#modalEstudiosNombre').text(nombre);
            $('#modalEstudiosCarnet').text(carnet);
            const $lista = $('#modalEstudiosLista');
            $lista.empty();
            if (!Array.isArray(estudios) || estudios.length === 0) {
                $lista.html('<div class="text-muted" style="text-align:center;padding:1rem;font-size:0.85rem;">Sin estudios académicos registrados.</div>');
            } else {
                // Mostrar el principal primero
                estudios.sort(function(a, b) {
                    return (b.principal ? 1 : 0) - (a.principal ? 1 : 0);
                });
                estudios.forEach(function(est, idx) {
                    const cls = est.principal ? 'aa-modal-estudio-item principal' : 'aa-modal-estudio-item';
                    const grado = est.grado || est.texto || 'Estudio';
                    const profesion = est.profesion || '';
                    const universidad = est.universidad || '';
                    const estado = est.estado || '';
                    let metaHtml = '';
                    if (profesion) metaHtml += '<span class="aa-modal-estudio-meta-item"><i class="ri-briefcase-line"></i>' + escHtml(profesion) + '</span>';
                    if (universidad) metaHtml += '<span class="aa-modal-estudio-meta-item"><i class="ri-building-4-line"></i>' + escHtml(universidad) + '</span>';
                    const starHtml = est.principal ? '<i class="ri-star-fill aa-principal-star" title="Estudio principal"></i>' : '';
                    const estadoHtml = estado ? '<div class="aa-modal-estudio-estado">' + escHtml(estado) + '</div>' : '';
                    const item =
                        '<div class="' + cls + '">' +
                            '<div class="aa-modal-estudio-num">' + (idx + 1) + '</div>' +
                            '<div class="aa-modal-estudio-body">' +
                                '<div class="aa-modal-estudio-grado">' + starHtml + escHtml(grado) + '</div>' +
                                (metaHtml ? '<div class="aa-modal-estudio-meta">' + metaHtml + '</div>' : '') +
                                estadoHtml +
                            '</div>' +
                        '</div>';
                    $lista.append(item);
                });
            }
            openModal('modalEstudiosEstudiante');
        });

        // ===== MÓDULOS & CALENDARIO =====
        function cargarModulosSidebar() {
            $.ajax({
                    url: '/admin/posgrads/ofertas/' + window.OFERTA_ID + '/modulos/listar'
                })
                .done(function(r) {
                    allModulos = r.data || [];
                    window.allModulos = allModulos;
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
                $list.html('<div class="msi-loading"><i class="ri-inbox-line"></i> Sin módulos registrados</div>');
                return;
            }
            allModulos.sort(function(a, b) { return a.id - b.id; });
            let html = '';
            allModulos.forEach(function(mod, idx) {
                const color = mod.color || '#6366f1';
                const docenteNombre = mod.docente && mod.docente.persona
                    ? ((mod.docente.persona.nombres || '') + ' ' + (mod.docente.persona.apellido_paterno || '')).trim()
                    : 'Sin docente asignado';
                // Trabajador asignado: se toma del primer horario registrado
                const primerHorario = mod.horarios && mod.horarios.length > 0 ? mod.horarios[0] : null;
                const primerTrabajador = primerHorario && primerHorario.trabajador_cargo ? primerHorario.trabajador_cargo : null;
                let trabajadorNombre = '';
                let trabajadorCargo = '';
                if (primerTrabajador) {
                    trabajadorCargo = primerTrabajador.nombre_cargo || '';
                    if (primerTrabajador.trabajador && primerTrabajador.trabajador.persona) {
                        const p = primerTrabajador.trabajador.persona;
                        trabajadorNombre = ((p.nombres || '') + ' ' + (p.apellido_paterno || '')).trim();
                    }
                    if (!trabajadorNombre) trabajadorNombre = trabajadorCargo;
                }
                const horariosCount = mod.horarios ? mod.horarios.length : 0;
                const total = window.CANTIDAD_SESIONES || 0;
                const pct = total > 0 ? Math.min(Math.round((horariosCount / total) * 100), 100) : 0;
                const badgeMoodle = mod.moodle_course_id
                    ? '<span class="msi-moodle-badge" title="Vinculado a Moodle"><i class="ri-links-line"></i></span>'
                    : '';

                // Badge de estado del módulo
                const estadoMod = mod.estado || 'No Inició';
                const estadoStyles = {
                    'No Inició':     { bg: 'rgba(100,116,139,.12)', color: '#475569', icon: 'ri-time-line' },
                    'En Desarrollo': { bg: 'rgba(34,197,94,.12)',   color: '#16a34a', icon: 'ri-loader-3-line' },
                    'Concluido':     { bg: 'rgba(99,102,241,.12)',  color: '#4338ca', icon: 'ri-checkbox-circle-line' },
                };
                const est = estadoStyles[estadoMod] || estadoStyles['No Inició'];
                const estadoBadge = '<span style="display:inline-flex;align-items:center;gap:.25rem;font-size:.68rem;font-weight:600;padding:.15rem .55rem;border-radius:20px;background:' + est.bg + ';color:' + est.color + ';margin-left:.35rem;"><i class="' + est.icon + '"></i>' + escHtml(estadoMod) + '</span>';

                // Enlace de videollamada del módulo
                const enlaceUrl    = mod.enlace_videollamada_url    || '';
                const enlaceNombre = mod.enlace_videollamada_nombre || '';
                const enlaceId     = mod.enlace_videollamada_id     || '';
                const enlaceRow = enlaceUrl
                    ? '<div class="msi-enlace-vid"><i class="ri-video-chat-line"></i><a href="' + escHtml(enlaceUrl) + '" target="_blank" rel="noopener" title="' + escHtml(enlaceNombre) + '" onclick="event.stopPropagation();">' + escHtml(enlaceNombre || enlaceUrl) + '</a></div>'
                    : '<div class="msi-enlace-vid msi-enlace-vacio"><i class="ri-video-chat-line"></i>Sin enlace virtual</div>';

                const btnEnlaceTitle = enlaceId ? 'Modificar enlace de sesión virtual' : 'Registrar enlace de sesión virtual';
                const btnEnlaceColor = enlaceId ? '#16a34a' : '#6366f1';

                html +=
                    '<div class="modulo-sidebar-item" data-modulo-id="' + mod.id + '" data-color="' + color + '">' +
                        '<div class="msi-strip" style="background:' + color + ';"></div>' +
                        '<div class="msi-body">' +
                            '<div class="msi-top">' +
                                '<div class="msi-num" style="color:' + color + ';">' + (idx + 1) + '</div>' +
                                '<div class="msi-info">' +
                                    '<div class="msi-name">' + escHtml(mod.nombre) + badgeMoodle + '</div>' +
                                    '<div class="msi-estado-row">' + estadoBadge + '</div>' +
                                    '<div class="msi-docente"><i class="ri-user-3-line"></i>' + escHtml(docenteNombre) + '</div>' +
                                    (trabajadorNombre
                                        ? '<div class="msi-trabajador"><i class="ri-shield-user-line"></i>' + escHtml(trabajadorNombre) + (trabajadorCargo ? ' <span class="msi-trabajador-cargo">(' + escHtml(trabajadorCargo) + ')</span>' : '') + '</div>'
                                        : '<div class="msi-trabajador msi-trabajador-vacio"><i class="ri-shield-user-line"></i>Sin trabajador asignado</div>') +
                                    enlaceRow +
                                '</div>' +
                            '</div>' +
                            '<div class="msi-actions-row">' +
                                '<a href="/admin/posgrads/ofertas/' + window.OFERTA_ID + '/modulos/' + mod.id + '/detalle" class="msi-btn btn-ver-modulo" title="Ver detalle"><i class="ri-eye-line"></i></a>' +
                                '<button class="msi-btn btn-edit-modulo" data-id="' + mod.id + '" title="Editar"><i class="ri-pencil-fill"></i></button>' +
                                '<button class="msi-btn msi-btn-add btn-asignar-horario" data-id="' + mod.id + '" title="Asignar horario"><i class="ri-add-line"></i></button>' +
                                '<button class="msi-btn btn-cambiar-estado-modulo" data-id="' + mod.id + '" data-estado="' + escHtml(estadoMod) + '" data-nombre="' + escHtml(mod.nombre) + '" title="Cambiar estado del módulo" style="color:' + est.color + ';"><i class="ri-flag-line"></i></button>' +
                                '<button class="msi-btn btn-enlace-videollamada" data-id="' + mod.id + '" data-nombre="' + escHtml(mod.nombre) + '" data-enlace-id="' + escHtml(String(enlaceId)) + '" data-enlace-url="' + escHtml(enlaceUrl) + '" data-enlace-nombre="' + escHtml(enlaceNombre) + '" title="' + btnEnlaceTitle + '" style="color:' + btnEnlaceColor + ';"><i class="ri-video-chat-line"></i></button>' +
                                '<button class="msi-btn msi-btn-moodle btn-matricular-moodle" data-id="' + mod.id + '" data-nombre="' + escHtml(mod.nombre) + '" data-estado="' + escHtml(estadoMod) + '" title="Enviar credenciales Moodle"><i class="ri-send-plane-2-line"></i></button>' +
                            '</div>' +
                            '<div class="msi-progress-wrap">' +
                                '<div class="msi-progress-bar" style="width:' + pct + '%;background:' + color + ';"></div>' +
                            '</div>' +
                            '<div class="msi-sessions-label">' +
                                '<span>' + horariosCount + ' de ' + total + ' sesiones</span>' +
                                '<span>' + pct + '%</span>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
            });
            $list.html(html);
        }
        $(document).on('click', '.modulo-sidebar-item', function(e) {
            if ($(e.target).closest('.msi-actions-row').length) return;
            const moduloId = $(this).data('modulo-id');
            // Nombre completo desde allModulos para evitar truncamiento del DOM
            const modObj = allModulos.find(m => m.id === moduloId);
            const moduloNombre = modObj ? modObj.nombre : $(this).find('.msi-name').text().trim();
            const moduloColor = $(this).data('color') || '#6366f1';

            $('.modulo-sidebar-item').removeClass('active').css({
                '--active-mod-color': '',
                '--active-mod-bg': '',
                '--active-mod-shadow': ''
            });

            $(this).css({
                '--active-mod-color': moduloColor,
                '--active-mod-bg': hexToRgba(moduloColor, 0.08),
                '--active-mod-shadow': `0 6px 15px ${hexToRgba(moduloColor, 0.12)}`
            }).addClass('active');

            $('#btnTodosModulos').removeClass('active');
            selectedModuloFilter = moduloId;
            actualizarBadgeModuloSeleccionado(moduloId, moduloNombre, moduloColor);
            
            // On mobile, clicking a module closes the sidebar drawer so they can see the calendar
            if (window.innerWidth < 992) {
                $('#modulosSidebar').removeClass('mobile-active');
                $('#sidebarOverlay').removeClass('mobile-active');
            }

            refreshCalendar();
        });
        
        $('#btnTodosModulos').on('click', function() {
            $('.modulo-sidebar-item').removeClass('active').css({
                '--active-mod-color': '',
                '--active-mod-bg': '',
                '--active-mod-shadow': ''
            });
            $(this).addClass('active');
            selectedModuloFilter = null;
            ocultarBadgeModuloSeleccionado();
            refreshCalendar();
        });

        // Click handler for collapsing/expanding sidebar
        $(document).on('click', '#btnToggleSidebar', function() {
            const isMobile = window.innerWidth < 992;
            const $sidebar = $('#modulosSidebar');
            const $overlay = $('#sidebarOverlay');
            const $wrapper = $('.modulos-layout-wrapper');
            const $icon = $(this).find('i');

            if (isMobile) {
                $sidebar.toggleClass('mobile-active');
                $overlay.toggleClass('mobile-active');
            } else {
                $wrapper.toggleClass('sidebar-collapsed');
                
                // Toggle icon between fold and unfold
                if ($wrapper.hasClass('sidebar-collapsed')) {
                    $icon.removeClass('ri-menu-fold-line').addClass('ri-menu-unfold-line');
                } else {
                    $icon.removeClass('ri-menu-unfold-line').addClass('ri-menu-fold-line');
                }

                // Force FullCalendar to update size after CSS transition (350ms)
                setTimeout(function() {
                    if (calendar) {
                        calendar.updateSize();
                    }
                }, 355);
            }
        });

        // Click overlay to close mobile sidebar
        $(document).on('click', '#sidebarOverlay', function() {
            $('#modulosSidebar').removeClass('mobile-active');
            $(this).removeClass('mobile-active');
        });

        // Clean up mobile drawer classes on window resize
        $(window).on('resize', function() {
            if (window.innerWidth >= 992) {
                $('#modulosSidebar').removeClass('mobile-active');
                $('#sidebarOverlay').removeClass('mobile-active');
            }
        });

        function actualizarBadgeModuloSeleccionado(moduloId, nombre, color) {
            const c = color || '#6366f1';

            // Panel nuevo prominente
            const panel  = document.getElementById('moduloActivoPanel');
            const bar    = document.getElementById('moduloActivoBar');
            const iconW  = document.getElementById('moduloActivoIconWrap');
            const nmEl   = document.getElementById('moduloActivoNombre');

            if (moduloId && nombre && panel) {
                // Extraer r,g,b del color para CSS vars
                let r = 99, g = 102, b = 241;
                const m = c.match(/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i);
                if (m) { r = parseInt(m[1],16); g = parseInt(m[2],16); b = parseInt(m[3],16); }

                panel.style.setProperty('--map-color', c);
                panel.style.setProperty('--map-color-rgb', `${r}, ${g}, ${b}`);
                panel.style.border = `1px solid rgba(${r},${g},${b},0.22)`;
                panel.style.background = `rgba(${r},${g},${b},0.04)`;
                if (bar) { bar.style.background = c; }
                if (iconW) { iconW.style.background = `rgba(${r},${g},${b},0.12)`; iconW.style.color = c; }
                if (nmEl) { nmEl.textContent = nombre; }
                panel.style.display = 'flex';
            } else if (panel) {
                panel.style.display = 'none';
            }
        }

        function ocultarBadgeModuloSeleccionado() {
            const panel = document.getElementById('moduloActivoPanel');
            if (panel) panel.style.display = 'none';
        }

        // Botón "Todos" dentro del panel activo
        $(document).on('click', '#moduloActivoPanel .map-clear', function(e) {
            e.stopPropagation();
            $('.modulo-sidebar-item').removeClass('active').css({
                '--active-mod-color': '',
                '--active-mod-bg': '',
                '--active-mod-shadow': ''
            });
            $('#btnTodosModulos').addClass('active');
            selectedModuloFilter = null;
            ocultarBadgeModuloSeleccionado();
            refreshCalendar();
        });

        // Badge legacy — click (por compatibilidad, en caso de que quede visible)
        $(document).on('click', '#moduloSeleccionadoBadge .modulo-badge-close', function(e) {
            e.stopPropagation();
            $('.modulo-sidebar-item').removeClass('active').css({
                '--active-mod-color': '',
                '--active-mod-bg': '',
                '--active-mod-shadow': ''
            });
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
                    const textColor = info.event.textColor || '#ffffff';
                    const estado = info.event.extendedProps?.estado;

                    if (estado === 'Postergado') {
                        info.el.classList.add('fc-event-postergado');
                        info.el.style.setProperty('--event-color', color, 'important');
                        
                        // Set text color to event color for postponed events
                        const titleEl = info.el.querySelector('.fc-event-title');
                        if (titleEl) {
                            titleEl.style.setProperty('color', color, 'important');
                            if (!titleEl.querySelector('.ri-time-line')) {
                                titleEl.innerHTML = '<i class="ri-time-line me-1" style="font-size:0.8rem;vertical-align:middle;"></i>' + titleEl.innerHTML;
                            }
                        }
                        const timeEl = info.el.querySelector('.fc-event-time');
                        if (timeEl) {
                            timeEl.style.setProperty('color', color, 'important');
                        }
                    } else {
                        info.el.style.setProperty('background-color', color, 'important');
                        info.el.style.setProperty('border-color', color, 'important');
                        info.el.style.setProperty('color', textColor, 'important');

                        const titleEl = info.el.querySelector('.fc-event-title');
                        if (titleEl) {
                            titleEl.style.setProperty('color', textColor, 'important');
                        }
                        const timeEl = info.el.querySelector('.fc-event-time');
                        if (timeEl) {
                            timeEl.style.setProperty('color', textColor, 'important');
                        }
                    }
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
                            const textColor = (h.estado === 'Postergado') ? '#475569' : '#ffffff';
                            events.push({
                                id: 'h-' + h.id,
                                title: mod.nombre + ' (' + formatTime(h.hora_inicio) +
                                    '-' + formatTime(h.hora_fin) + ')',
                                start: fechaStr + 'T' + (h.hora_inicio || '00:00'),
                                end: fechaStr + 'T' + (h.hora_fin || '00:00'),
                                backgroundColor: color,
                                borderColor: color,
                                textColor: textColor,
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
                                        .nombre_cargo || '') : '',
                                    reprogramado_de_fecha: h.reprogramado_de_fecha,
                                    reprogramado_a_fecha: h.reprogramado_a_fecha,
                                    enlace_videollamada_url:    h.enlace_videollamada_url    || '',
                                    enlace_videollamada_nombre: h.enlace_videollamada_nombre || '',
                                    enlace_grabacion:           h.enlace_grabacion           || '',
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
            $('#inscripcionesTableWrap').hide();
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

            if (filter === 'Inscrito') {
                filtered = inscripciones.filter(i => i.estado === 'Inscrito');
            } else if (filter === 'Pre-Inscrito') {
                filtered = inscripciones.filter(i => i.estado === 'Pre-Inscrito');
            }

            // Ordenar A→Z por apellido paterno, materno y nombres
            function buildSortKey(i) {
                return [
                    (i.apellido_paterno || '').toLowerCase(),
                    (i.apellido_materno || '').toLowerCase(),
                    (i.nombres || '').toLowerCase()
                ].join(' ').trim() || (i.estudiante_nombre || '').toLowerCase();
            }
            filtered.sort(function(a, b) {
                return buildSortKey(a).localeCompare(buildSortKey(b), 'es', { sensitivity: 'base' });
            });

            const $wrap = $('#inscripcionesTableWrap');

            if (!filtered.length) {
                $wrap.hide();
                $('#inscripcionesEmpty').show();
                return;
            }

            $('#inscripcionesEmpty').hide();
            $wrap.show();

            function initials(name) {
                const parts = name.trim().split(/\s+/);
                if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
                return (parts[0][0] || '?').toUpperCase();
            }

            let html = '';
            filtered.forEach(function(ins, idx) {
                const ap        = (ins.apellido_paterno || '').trim();
                const am        = (ins.apellido_materno || '').trim();
                const nm        = (ins.nombres || '').trim();
                const nombreOrdenado = (ap + ' ' + am + ' ' + nm).replace(/\s+/g, ' ').trim();
                const nombres   = nombreOrdenado || (ins.estudiante_nombre || '').trim() || '—';
                const ci        = ins.estudiante_ci || '—';
                const celular   = ins.celular || '—';
                const correo    = ins.correo || '—';
                const plan      = ins.plan_pago || '—';
                const estado    = ins.estado || '—';
                const esCls     = estado === 'Inscrito' ? 'inscrito' : 'pre-inscrito';
                const esIco     = estado === 'Inscrito' ? 'ri-user-check-line' : 'ri-user-add-line';

                const tieneCuenta  = ins.tiene_cuenta_moodle;
                const tieneSistema = ins.tiene_cuenta_sistema;
                const celularLimpio = celular.replace(/[^0-9]/g, '');
                const ini = nombres !== '—' ? initials(nombres) : '?';

                const sistemaChip = tieneSistema
                    ? '<span class="ins-acc-chip activa"><i class="ri-check-line"></i> Activa</span>'
                    : '<span class="ins-acc-chip sin"><i class="ri-close-line"></i> Sin cuenta</span>';

                const moodleChip = tieneCuenta
                    ? '<span class="ins-acc-chip activa"><i class="ri-check-line"></i> Activa</span>'
                    : '<span class="ins-acc-chip sin"><i class="ri-close-line"></i> Sin cuenta</span>';

                // Toggles de Estado Contable y Académico — solo para Inscritos
                function buildEstadoToggle(campo, valor, label) {
                    if (estado !== 'Inscrito') {
                        return '<span class="ins-estado-toggle-na" title="Solo disponible para Inscritos">—</span>';
                    }
                    const activo = valor !== false;
                    return '<button type="button" class="ins-estado-toggle ' + (activo ? 'on' : 'off') + '" ' +
                        'data-inscripcion-id="' + ins.id + '" data-campo="' + campo + '" data-valor="' + (activo ? 1 : 0) + '" ' +
                        'title="' + label + ': ' + (activo ? 'Activo (click para dar de baja)' : 'Inactivo (click para activar)') + '">' +
                        '<span class="ins-estado-toggle-track"><span class="ins-estado-toggle-knob"></span></span>' +
                        '<span class="ins-estado-toggle-label">' + (activo ? 'Activo' : 'Baja') + '</span>' +
                    '</button>';
                }

                // Sugerencias por estudiante (mora contable + 2+ reprobados académicos)
                const tieneVencidas = !!ins.tiene_cuota_vencida;
                const reprobados = Number(ins.cantidad_reprobados) || 0;
                const acadActivo = ins.activo_academico !== false;
                const contActivo = ins.activo_contable !== false;
                const genActivo  = ins.activo !== false;

                const sugContable = (tieneVencidas && contActivo) ? 'desactivar'
                    : (!tieneVencidas && !contActivo ? 'activar' : null);
                const sugAcad = (reprobados >= 2 && acadActivo) ? 'desactivar' : null;
                // Sugerencia del campo "Activo" general: ambas sugerencias de baja activas → sugerir baja general
                const sugGen = (sugContable === 'desactivar' && sugAcad === 'desactivar' && genActivo)
                    ? 'desactivar' : null;

                function buildSugChip(tipo, attrs, txt, titulo) {
                    return '<button type="button" class="ins-cell-sug ins-cell-sug--' + tipo + ' ins-cell-sug-btn" ' +
                        attrs + ' title="' + titulo + '">' +
                        '<i class="' + (tipo === 'down' ? 'ri-error-warning-line' : 'ri-arrow-up-circle-line') + '"></i> ' + txt +
                        ' <i class="ri-eye-line ins-cell-sug-eye"></i>' +
                    '</button>';
                }

                function wrapCell(toggleHtml, sug, sugAttrs, sugTitle) {
                    if (estado !== 'Inscrito' || !sug) return toggleHtml;
                    const txt = sug === 'desactivar' ? 'Sugerir baja' : 'Sugerir alta';
                    const tipo = sug === 'desactivar' ? 'down' : 'up';
                    return '<div class="ins-cell-wrap">' + toggleHtml +
                        buildSugChip(tipo, sugAttrs, txt, sugTitle) +
                    '</div>';
                }

                const baseAttrs = 'data-inscripcion-id="' + ins.id + '"';
                const activoCell    = wrapCell(
                    buildEstadoToggle('activo', ins.activo, 'Estado general'),
                    sugGen,
                    baseAttrs + ' data-tipo="general"',
                    'Mora contable + 2 o más módulos reprobados — ver detalle'
                );
                const contableCell  = wrapCell(
                    buildEstadoToggle('activo_contable', ins.activo_contable, 'Estado contable'),
                    sugContable,
                    baseAttrs + ' data-tipo="contable"',
                    sugContable === 'desactivar' ? 'Tiene cuotas vencidas — ver detalle' : 'Está al día — ver detalle'
                );
                const academicoCell = wrapCell(
                    buildEstadoToggle('activo_academico', ins.activo_academico, 'Estado académico'),
                    sugAcad,
                    baseAttrs + ' data-tipo="academico"',
                    reprobados + ' módulo(s) reprobado(s) — ver detalle'
                );

                const btnDetalle = ins.estudiante_id
                    ? '<a href="/admin/estudiantes/' + ins.estudiante_id + '/detalle" class="ins-action-btn" title="Ver detalle"><i class="ri-eye-line"></i></a>'
                    : '<button class="ins-action-btn" disabled title="Sin estudiante"><i class="ri-eye-line"></i></button>';

                const btnCambiar = estado === 'Pre-Inscrito'
                    ? '<button type="button" class="ins-action-btn upgrade cambiar-a-inscrito" title="Cambiar a Inscrito"' +
                      ' data-inscripcion-id="' + ins.id + '" data-estudiante="' + escHtml(nombres) +
                      '" data-ci="' + escHtml(ci) + '" data-plan-pago-id="' + (ins.plan_pago_id || '') +
                      '"><i class="ri-user-star-line"></i></button>'
                    : '';

                let btnWa = '';
                if (celularLimpio.length >= 8 && tieneCuenta) {
                    btnWa = '<button type="button" class="ins-action-btn wa btn-whatsapp-moodle" title="Enviar accesos WhatsApp"' +
                        ' data-celular="' + celularLimpio + '" data-nombre="' + escHtml(nombres) +
                        '" data-programa="' + escHtml(ins.programa_nombre || '') +
                        '" data-username="' + escHtml(ins.moodle_username || '') +
                        '" data-password="' + escHtml(ins.moodle_password || '') +
                        '"><i class="ri-whatsapp-line"></i></button>';
                } else {
                    btnWa = '<button class="ins-action-btn" disabled title="' + (celularLimpio.length < 8 ? 'Sin celular' : 'Sin cuenta Moodle') + '"><i class="ri-whatsapp-line"></i></button>';
                }

                const faseDes = window.OFERTA_FASE_DESARROLLO === true;
                const rowAcadTint = (estado === 'Inscrito' && faseDes)
                    ? (genActivo ? ' ins-row--gen-on' : ' ins-row--gen-off')
                    : '';

                html +=
                    '<tr class="ins-row' + rowAcadTint + '" data-inscripcion-id="' + ins.id + '">' +
                    '<td class="text-center" style="color:#94a3b8;font-weight:600;font-size:.72rem;">' + (idx + 1) + '</td>' +
                    '<td>' +
                        '<div class="ins-student-cell">' +
                            '<div class="ins-avatar">' + ini + '</div>' +
                            '<div>' +
                                '<a href="/admin/estudiantes/' + (ins.estudiante_id || '') + '/detalle" class="ins-student-name">' + toCamelCase(escHtml(nombres)) + '</a>' +
                                '<span class="ins-ci-badge"><i class="ri-fingerprint-line"></i>' + escHtml(ci) + '</span>' +
                            '</div>' +
                        '</div>' +
                    '</td>' +
                    '<td class="text-center" style="color:#475569;white-space:nowrap;">' + escHtml(celular) + '</td>' +
                    '<td style="max-width:170px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#475569;">' + escHtml(correo) + '</td>' +
                    '<td class="text-center"><span class="ins-plan-badge" title="' + escHtml(plan) + '">' + escHtml(plan) + '</span></td>' +
                    '<td class="text-center"><span class="ins-estado-badge ' + esCls + '"><i class="' + esIco + '"></i>' + estado + '</span></td>' +
                    (faseDes ? (
                        '<td class="text-center">' + activoCell + '</td>' +
                        '<td class="text-center">' + contableCell + '</td>' +
                        '<td class="text-center">' + academicoCell + '</td>'
                    ) : '') +
                    '<td class="text-center">' + sistemaChip + '</td>' +
                    '<td class="text-center">' + moodleChip + '</td>' +
                    '<td><div class="ins-actions">' + btnDetalle + btnCambiar + btnWa + '</div></td>' +
                    '</tr>';
            });

            $tbody.html(html);
        }

        document.querySelectorAll('.ins-filter-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.ins-filter-btn').forEach(b => b.classList.remove('active'));
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

        // ── Sticky cols (#, Carnet, Estudiante): oscurecer al hacer scroll horizontal ──
        (function() {
            const wrap = document.querySelector('.aa-table-wrap');
            if (!wrap) return;
            const sync = function() {
                if (wrap.scrollLeft > 4) wrap.classList.add('is-scrolled');
                else wrap.classList.remove('is-scrolled');
            };
            wrap.addEventListener('scroll', sync, { passive: true });
            window.addEventListener('resize', sync, { passive: true });
            sync();
        })();

        // ── Nota de 2da instancia (registrar / editar) ──
        let aa2daCtx = null;

        function abrirModal2da(ctx) {
            aa2daCtx = ctx;
            $('#modal2daSub').text((ctx.estudiante || '—') + ' · ' + (ctx.moduloNombre || ''));
            $('#modal2daMax').text(Number(ctx.notaMinima).toFixed(2));
            $('#modal2daInput').val(ctx.notaActual !== null && ctx.notaActual !== undefined ? Number(ctx.notaActual).toFixed(2) : '');
            $('#modal2daInput').attr('max', Number(ctx.notaMinima));
            $('#modal2daError').hide().text('');
            $('#modal2daDelete').toggle(ctx.notaActual !== null && ctx.notaActual !== undefined);
            new bootstrap.Modal(document.getElementById('modal2daInstancia')).show();
            setTimeout(function() { $('#modal2daInput').focus().select(); }, 250);
        }

        // Sincroniza el sub-label "2da Inst." del header de un módulo según haya o no
        // alguna celda en ese módulo con la 2da habilitada
        function syncHeader2da(moduloId) {
            const $th = $('th.aa-mod-col[data-modulo-id="' + moduloId + '"]');
            if (!$th.length) return;
            const hayAlguna = $('.aa-mod-cell[data-modulo-id="' + moduloId + '"] .aa-sub-2da').length > 0;
            const $sub = $th.find('.aa-mod-col-sub');
            $th.attr('data-tiene-2da', hayAlguna ? 1 : 0);
            $th.toggleClass('aa-mod-col--solo', !hayAlguna);
            // Asegurar que existan los span necesarios
            let $labels = $sub.find('.aa-mod-col-sub-label');
            if ($labels.length === 0) {
                $sub.append('<span class="aa-mod-col-sub-label">Final</span>');
                $labels = $sub.find('.aa-mod-col-sub-label');
            }
            if (hayAlguna) {
                if ($labels.length < 2) {
                    $sub.append('<span class="aa-mod-col-sub-label">2da Inst.</span>');
                }
            } else {
                if ($labels.length > 1) {
                    $labels.slice(1).remove();
                }
            }
        }

        // Habilitar 2da instancia (crea la columna 2da en la celda)
        $(document).on('click', '.aa-nivel-hab-btn', function(e) {
            e.stopPropagation();
            const $btn = $(this);
            const inscripcionId = $btn.attr('data-inscripcion-id');
            const moduloId = $btn.attr('data-modulo-id');
            if (!inscripcionId || !moduloId) return;

            $btn.prop('disabled', true);
            $.ajax({
                url: '/admin/ofertas-academicas/' + window.OFERTA_ID + '/matriculacion/habilitar-nivelacion',
                type: 'PATCH',
                data: { inscripcion_id: inscripcionId, modulo_id: moduloId, habilitada: 1, _token: '{{ csrf_token() }}' }
            }).done(function(resp) {
                if (!resp.success) {
                    toast('error', resp.message || 'No se pudo habilitar.');
                    return;
                }
                const $cell = $btn.closest('td.aa-mod-cell');
                reconstruirCelda2da($cell, {
                    inscripcionId : inscripcionId,
                    moduloId      : moduloId,
                    moduloNombre  : $btn.attr('data-modulo-nombre') || '',
                    estudiante    : $btn.attr('data-estudiante') || '',
                    notaMinima    : Number($cell.find('.aa-nota-final').attr('title')?.match(/Mínima:\s*([\d.]+)/)?.[1] || 0),
                }, /*habilitada*/ true, /*notaN*/ null);
                syncHeader2da(moduloId);
                toast('success', '2da instancia habilitada.');
            }).fail(function(xhr) {
                toast('error', xhr.responseJSON?.message || 'Error al habilitar.');
            }).always(function() { $btn.prop('disabled', false); });
        });

        // Deshabilitar 2da instancia (limpia la columna 2da y borra nota_nivelacion)
        $(document).on('click', '.aa-nivel-deshab-btn', function(e) {
            e.stopPropagation();
            if (!confirm('¿Deshabilitar la 2da instancia para este estudiante?')) return;
            const $btn = $(this);
            const inscripcionId = $btn.attr('data-inscripcion-id');
            const moduloId = $btn.attr('data-modulo-id');
            const $cell = $btn.closest('td.aa-mod-cell');

            $.ajax({
                url: '/admin/ofertas-academicas/' + window.OFERTA_ID + '/matriculacion/habilitar-nivelacion',
                type: 'PATCH',
                data: { inscripcion_id: inscripcionId, modulo_id: moduloId, habilitada: 0, _token: '{{ csrf_token() }}' }
            }).done(function(resp) {
                if (!resp.success) { toast('error', resp.message || 'Error.'); return; }
                colapsarCelda2da($cell, inscripcionId, moduloId);
                syncHeader2da(moduloId);
                toast('success', '2da instancia deshabilitada.');
            }).fail(function() { toast('error', 'Error al deshabilitar.'); });
        });

        // Construye la sub-celda 2da y cambia el grid a 2 columnas
        function reconstruirCelda2da($cell, ctx, habilitada, notaN) {
            const $grid = $cell.find('.aa-notas-grid');
            if (!$grid.length) return;

            if (!habilitada) {
                colapsarCelda2da($cell, ctx.inscripcionId, ctx.moduloId);
                return;
            }

            $grid.removeClass('aa-notas-grid--solo');
            $cell.find('.aa-nivel-hab-btn').remove();

            // Si ya existe la sub-2da no duplicar
            let $sub2 = $grid.find('.aa-sub-2da');
            if (!$sub2.length) {
                $sub2 = $('<div class="aa-sub aa-sub-2da"></div>');
                $grid.append($sub2);
            }
            $sub2.empty().removeClass('aa-sub--ok aa-sub--fail');

            if (notaN === null || notaN === undefined) {
                const wrap = $('<div class="aa-sub-content"></div>');
                const btn = $('<button type="button" class="aa-nota-2da-add"><i class="ri-add-line"></i> Registrar</button>')
                    .attr('data-inscripcion-id', ctx.inscripcionId)
                    .attr('data-modulo-id', ctx.moduloId)
                    .attr('data-modulo-nombre', ctx.moduloNombre)
                    .attr('data-estudiante', ctx.estudiante)
                    .attr('data-nota-minima', ctx.notaMinima)
                    .attr('title', 'Registrar nota de 2da instancia (máx. ' + Number(ctx.notaMinima).toFixed(2) + ')');
                const btnX = $('<button type="button" class="aa-nivel-deshab-btn" title="Deshabilitar 2da instancia"><i class="ri-close-circle-line"></i></button>')
                    .attr('data-inscripcion-id', ctx.inscripcionId)
                    .attr('data-modulo-id', ctx.moduloId);
                wrap.append(btn).append(btnX);
                $sub2.append(wrap);
            } else {
                const min = Number(ctx.notaMinima);
                const aprobado = Number(notaN) >= min;
                $sub2.addClass(aprobado ? 'aa-sub--ok' : 'aa-sub--fail');
                const cls = aprobado ? 'aa-nota-final-cargada' : 'aa-nota-final-fail';
                const span = $('<span class="aa-nota aa-nota-2da aa-nota-2da-editable"></span>')
                    .addClass(cls)
                    .text(Number(notaN).toFixed(2))
                    .attr('data-inscripcion-id', ctx.inscripcionId)
                    .attr('data-modulo-id', ctx.moduloId)
                    .attr('data-modulo-nombre', ctx.moduloNombre)
                    .attr('data-estudiante', ctx.estudiante)
                    .attr('data-nota-actual', notaN)
                    .attr('data-nota-minima', min)
                    .attr('title', '2da Instancia: ' + Number(notaN).toFixed(2) + ' — click para editar');
                $sub2.append(span);
            }
        }

        function colapsarCelda2da($cell, inscripcionId, moduloId) {
            const $grid = $cell.find('.aa-notas-grid');
            $grid.find('.aa-sub-2da').remove();
            $grid.addClass('aa-notas-grid--solo');

            // Volver a poner el botón "Habilitar 2da" si la nota regular es reprobatoria
            const $finalNota = $grid.find('.aa-nota-final');
            const $finalSub = $grid.find('.aa-sub-final');
            if ($finalNota.length && $finalNota.hasClass('aa-nota-final-fail') && !$finalSub.find('.aa-nivel-hab-btn').length) {
                const min = Number($finalNota.attr('title')?.match(/Mínima:\s*([\d.]+)/)?.[1] || 0);
                const $content = $finalSub.find('.aa-sub-content');
                const btn = $('<button type="button" class="aa-nivel-hab-btn"><i class="ri-add-circle-line"></i> Habilitar 2da</button>')
                    .attr('data-inscripcion-id', inscripcionId)
                    .attr('data-modulo-id', moduloId)
                    .attr('title', 'Habilitar 2da instancia para este estudiante');
                if ($content.length) $content.append(btn);
                else $finalSub.append(btn);
            }
        }

        $(document).on('click', '.aa-nota-2da-add', function(e) {
            e.stopPropagation();
            abrirModal2da({
                inscripcionId : $(this).attr('data-inscripcion-id'),
                moduloId      : $(this).attr('data-modulo-id'),
                moduloNombre  : $(this).attr('data-modulo-nombre'),
                estudiante    : $(this).attr('data-estudiante'),
                notaActual    : null,
                notaMinima    : Number($(this).attr('data-nota-minima')),
                $cell         : $(this).closest('td'),
            });
        });

        $(document).on('click', '.aa-nota-2da-editable', function(e) {
            e.stopPropagation();
            abrirModal2da({
                inscripcionId : $(this).attr('data-inscripcion-id'),
                moduloId      : $(this).attr('data-modulo-id'),
                moduloNombre  : $(this).attr('data-modulo-nombre'),
                estudiante    : $(this).attr('data-estudiante'),
                notaActual    : Number($(this).attr('data-nota-actual')),
                notaMinima    : Number($(this).attr('data-nota-minima')),
                $cell         : $(this).closest('td'),
            });
        });

        function guardar2da(nuevaNota) {
            if (!aa2daCtx) return;
            const ctx = aa2daCtx;
            const $save = $('#modal2daSave').prop('disabled', true);
            $.ajax({
                url: '/admin/ofertas-academicas/' + window.OFERTA_ID + '/matriculacion/nota-nivelacion',
                type: 'PATCH',
                data: {
                    inscripcion_id : ctx.inscripcionId,
                    modulo_id      : ctx.moduloId,
                    nota_nivelacion: nuevaNota,
                    _token         : '{{ csrf_token() }}'
                }
            }).done(function(resp) {
                if (!resp.success) {
                    $('#modal2daError').show().text(resp.message || 'No se pudo guardar.');
                    return;
                }
                actualizarCelda2da(ctx, resp.data);
                toast('success', nuevaNota === null ? 'Nota de 2da instancia eliminada.' : 'Nota de 2da instancia guardada.');
                bootstrap.Modal.getInstance(document.getElementById('modal2daInstancia'))?.hide();
            }).fail(function(xhr) {
                const msg = xhr.responseJSON?.message || (xhr.responseJSON?.errors ? Object.values(xhr.responseJSON.errors).flat().join(' ') : 'Error al guardar.');
                $('#modal2daError').show().text(msg);
            }).always(function() {
                $save.prop('disabled', false);
            });
        }

        $(document).on('click', '#modal2daSave', function() {
            const raw = $('#modal2daInput').val();
            if (raw === '' || raw === null) {
                $('#modal2daError').show().text('Ingresá una nota.');
                return;
            }
            const n = Number(raw);
            if (isNaN(n) || n < 0) {
                $('#modal2daError').show().text('La nota debe ser un número positivo.');
                return;
            }
            if (aa2daCtx && n > aa2daCtx.notaMinima) {
                $('#modal2daError').show().text('La nota no puede ser mayor a ' + aa2daCtx.notaMinima.toFixed(2) + '.');
                return;
            }
            guardar2da(n);
        });

        $(document).on('click', '#modal2daDelete', function() {
            if (!confirm('¿Quitar la nota de 2da instancia?')) return;
            guardar2da(null);
        });

        function actualizarCelda2da(ctx, data) {
            const $cell = ctx.$cell;
            if (!$cell || !$cell.length) return;
            // Reconstruir delegando en el helper común (asegura grid + habilitada)
            const min = Number(data.nota_minima);
            reconstruirCelda2da($cell, {
                inscripcionId : ctx.inscripcionId,
                moduloId      : ctx.moduloId,
                moduloNombre  : ctx.moduloNombre,
                estudiante    : ctx.estudiante,
                notaMinima    : min,
            }, /*habilitada*/ data.nivelacion_habilitada !== false, /*notaN*/ data.nota_nivelacion);
            syncHeader2da(ctx.moduloId);
        }

        // ── Datos académicos (módulos reprobados por inscripción) ──
        @php
            $areaAcadJs = [];
            foreach (($areaAcademicaEstudiantes ?? []) as $e) {
                $areaAcadJs[] = [
                    'inscripcion_id'      => $e['inscripcion_id'] ?? null,
                    'estudiante'          => trim(($e['apellido_paterno'] ?? '') . ' ' . ($e['apellido_materno'] ?? '') . ' ' . ($e['nombres'] ?? '')) ?: '—',
                    'carnet'              => $e['carnet'] ?? '—',
                    'cantidad_reprobados' => $e['cantidad_reprobados'] ?? 0,
                    'modulos_reprobados'  => $e['modulos_reprobados'] ?? [],
                    'nota_minima'         => $e['nota_minima'] ?? 0,
                ];
            }
        @endphp
        const areaAcadDataMap = (function() {
            const map = {};
            const items = @json($areaAcadJs);
            items.forEach(function(it) { if (it.inscripcion_id) map[it.inscripcion_id] = it; });
            return map;
        })();

        // Re-pintar fila / sugerencia académica
        function pintarFilaAcademica($row, acadActivo) {
            if (!$row || !$row.length) return;
            $row.removeClass('aa-row--acad-on aa-row--acad-off')
                .addClass(acadActivo ? 'aa-row--acad-on' : 'aa-row--acad-off');
        }

        function actualizarSugerenciaAcademica($cell, acadActivo) {
            if (!$cell || !$cell.length) return;
            const inscripcionId = $cell.attr('data-inscripcion-id');
            const reprobados = Number($cell.attr('data-reprobados')) || 0;
            const data = areaAcadDataMap[inscripcionId];
            const nombre = data ? data.estudiante : '';
            const $existing = $cell.find('.aa-acad-sug');

            // Solo "Sugerir baja" cuando hay 2+ reprobados y está activo
            if (acadActivo && reprobados >= 2) {
                const html =
                    '<button type="button" class="aa-acad-sug aa-acad-sug--down aa-acad-sug-btn" ' +
                    'data-inscripcion-id="' + inscripcionId + '" ' +
                    'data-estudiante="' + escHtml(nombre) + '" ' +
                    'title="' + reprobados + ' módulo(s) reprobado(s) — ver detalle">' +
                    '<i class="ri-error-warning-line"></i> Sugerir baja' +
                    ' <i class="ri-eye-line aa-acad-sug-eye"></i>' +
                    '</button>';
                if ($existing.length) { $existing.replaceWith(html); }
                else { $cell.append($(html).hide()); $cell.find('.aa-acad-sug').fadeIn(180); }
            } else {
                if ($existing.length) $existing.fadeOut(150, function() { $(this).remove(); });
            }
        }

        // Click en sugerencia académica → abrir modal
        $(document).on('click', '.aa-acad-sug-btn', function(e) {
            e.stopPropagation();
            const inscripcionId = $(this).attr('data-inscripcion-id');
            if (!inscripcionId) return;
            abrirModalAcademico(inscripcionId);
        });

        function abrirModalAcademico(inscripcionId) {
            const d = areaAcadDataMap[inscripcionId];
            if (!d) return;

            $('#modalAcademicoSubtitulo').text((d.estudiante || '—') + ' · CI ' + (d.carnet || '—'));
            $('#modalAcademicoNotaMinima').text('Nota mínima: ' + d.nota_minima);

            // Banner
            $('#modalAcademicoBanner').html(
                '<i class="ri-error-warning-fill"></i>' +
                '<span><strong>Sugerencia:</strong> dar de baja académica. Tiene <strong>' + d.cantidad_reprobados + '</strong> módulo(s) reprobado(s).</span>'
            );

            // Tabla
            const $tb = $('#modalAcademicoTbodyReprobados').empty();
            (d.modulos_reprobados || []).forEach(function(m) {
                const diff = (Number(m.nota_regular) - Number(m.nota_minima)).toFixed(2);
                const nota2da = (m.nota_nivelacion === null || m.nota_nivelacion === undefined)
                    ? '<span class="aa-mc-nota empty">—</span>'
                    : ('<span class="aa-mc-nota ' + (Number(m.nota_nivelacion) < Number(m.nota_minima) ? 'fail' : '') + '">' + Number(m.nota_nivelacion).toFixed(2) + '</span>');
                $tb.append(
                    '<tr>' +
                    '<td><i class="ri-book-2-line" style="color:#94a3b8;margin-right:5px;"></i>' + escHtml(m.modulo_nombre || '') + '</td>' +
                    '<td class="text-center"><span class="aa-mc-nota fail">' + Number(m.nota_regular).toFixed(2) + '</span></td>' +
                    '<td class="text-center">' + nota2da + '</td>' +
                    '<td class="text-center" style="color:#64748b;font-weight:600;">' + Number(m.nota_minima).toFixed(2) + '</td>' +
                    '<td class="text-center"><span class="aa-mc-diff">' + diff + '</span></td>' +
                    '</tr>'
                );
            });

            $('#modalAcademicoAccion').attr('data-inscripcion-id', inscripcionId).show();
            new bootstrap.Modal(document.getElementById('modalAcademicoDetalle')).show();
        }

        // Aplicar baja académica desde el modal
        $(document).on('click', '#modalAcademicoAccion', function() {
            const $btn = $(this);
            const inscripcionId = $btn.attr('data-inscripcion-id');
            if (!inscripcionId) return;
            $btn.prop('disabled', true);

            $.ajax({
                url: '/admin/ofertas-academicas/inscripciones/' + inscripcionId + '/toggle-estado',
                type: 'PATCH',
                data: { campo: 'activo_academico', valor: 0, _token: '{{ csrf_token() }}' }
            }).done(function(resp) {
                if (!resp.success) { toast('error', resp.message || 'No se pudo actualizar.'); return; }
                const d = resp.data || {};
                window.aplicarEstadoInscripcion(inscripcionId, {
                    activo:           d.activo,
                    activo_contable:  d.activo_contable,
                    activo_academico: d.activo_academico !== undefined ? d.activo_academico : false,
                });
                toast('success', 'Estado académico actualizado.');
                bootstrap.Modal.getInstance(document.getElementById('modalAcademicoDetalle'))?.hide();
            }).fail(function(xhr) {
                toast('error', xhr.responseJSON?.message || 'Error al actualizar.');
            }).always(function() {
                $btn.prop('disabled', false);
            });
        });

        // Abrir modal de detalle de estado contable
        $(document).on('click', '.fin-contable-sug-btn', function(e) {
            e.stopPropagation();
            const inscripcionId = $(this).attr('data-inscripcion-id');
            if (!inscripcionId) return;
            abrirModalContable(inscripcionId);
        });

        function fmtBs(n) {
            const v = Number(n) || 0;
            return 'Bs. ' + v.toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        // Pinta la fila completa según el estado contable (verde activo / rojo inactivo)
        function pintarFilaContable($row, contableActivo) {
            if (!$row || !$row.length) return;
            $row.removeClass('fin-row--contable-on fin-row--contable-off')
                .addClass(contableActivo ? 'fin-row--contable-on' : 'fin-row--contable-off');
        }

        // Recalcula el chip de sugerencia contable según el estado actual del toggle
        // y la realidad de las cuotas (data-tiene-vencidas en la celda).
        // - activo + vencidas → "Sugerir baja"
        // - inactivo + sin vencidas → "Sugerir alta"
        // - cualquier otro → sin sugerencia
        function actualizarSugerenciaContable($cell, contableActivo) {
            if (!$cell || !$cell.length) return;
            const inscripcionId = $cell.attr('data-inscripcion-id');
            const tieneVencidas = $cell.attr('data-tiene-vencidas') === '1';
            const $existing = $cell.find('.fin-contable-sug');

            let claseExtra = null;
            let icono = '';
            let texto = '';
            let titulo = '';

            if (contableActivo && tieneVencidas) {
                claseExtra = 'fin-contable-sug--down';
                icono  = 'ri-error-warning-line';
                texto  = 'Sugerir baja';
                titulo = 'Tiene cuotas vencidas — ver detalle';
            } else if (!contableActivo && !tieneVencidas) {
                claseExtra = 'fin-contable-sug--up';
                icono  = 'ri-arrow-up-circle-line';
                texto  = 'Sugerir alta';
                titulo = 'Está al día — ver detalle';
            }

            if (claseExtra === null) {
                if ($existing.length) $existing.fadeOut(150, function() { $(this).remove(); });
                return;
            }

            const html =
                '<button type="button" class="fin-contable-sug ' + claseExtra + ' fin-contable-sug-btn" ' +
                'data-inscripcion-id="' + inscripcionId + '" title="' + titulo + '">' +
                '<i class="' + icono + '"></i> ' + texto +
                ' <i class="ri-eye-line fin-contable-sug-eye"></i>' +
                '</button>';

            if ($existing.length) {
                $existing.replaceWith(html);
            } else {
                $cell.append($(html).hide());
                $cell.find('.fin-contable-sug').fadeIn(180);
            }
        }

        function abrirModalContable(inscripcionId) {
            const $modal = $('#modalContableDetalle');
            $('#modalContableLoading').show();
            $('#modalContableContent').hide();
            $('#modalContableAccion').hide();
            $('#modalContableSubtitulo').text('—');

            new bootstrap.Modal(document.getElementById('modalContableDetalle')).show();

            $.ajax({
                url: '/admin/ofertas-academicas/inscripciones/' + inscripcionId + '/detalle-estado-contable',
                type: 'GET',
            }).done(function(resp) {
                if (!resp.success) {
                    toast('error', resp.message || 'No se pudo cargar el detalle.');
                    bootstrap.Modal.getInstance(document.getElementById('modalContableDetalle'))?.hide();
                    return;
                }
                renderModalContable(resp.data, inscripcionId);
            }).fail(function() {
                toast('error', 'Error al cargar el detalle de cuotas.');
                bootstrap.Modal.getInstance(document.getElementById('modalContableDetalle'))?.hide();
            });
        }

        function renderModalContable(d, inscripcionId) {
            // Subtítulo
            const sub = (d.estudiante || '—') + ' · CI ' + (d.carnet || '—') + ' · ' + (d.plan_pago || '');
            $('#modalContableSubtitulo').text(sub);

            // Header color y banner según sugerencia
            const $header = $('#modalContableHeader');
            const $banner = $('#modalContableBanner');
            const $accion = $('#modalContableAccion');

            if (d.sugerencia === 'desactivar') {
                $header.css('background', 'linear-gradient(135deg,#7f1d1d 0%,#b91c1c 50%,#dc2626 100%)');
                $('#modalContableHeaderIcon').attr('class', 'ri-error-warning-line');
                $('#modalContableTitulo').text('Cuotas vencidas detectadas');
                $banner.attr('class', 'fin-mc-banner suggest-down').html(
                    '<i class="ri-error-warning-fill"></i><span><strong>Sugerencia:</strong> dar de baja contablemente. ' +
                    'Tiene ' + d.cantidad_vencidas + ' cuota(s) vencida(s) por ' + fmtBs(d.monto_vencido) + '.</span>'
                );
                $accion.css('background', 'linear-gradient(135deg,#b91c1c,#dc2626)').show();
                $('#modalContableAccionText').text('Dar de baja contable');
                $accion.attr('data-nuevo-valor', '0');
            } else if (d.sugerencia === 'activar') {
                $header.css('background', 'linear-gradient(135deg,#065f46 0%,#047857 50%,#059669 100%)');
                $('#modalContableHeaderIcon').attr('class', 'ri-checkbox-circle-line');
                $('#modalContableTitulo').text('Estudiante al día');
                $banner.attr('class', 'fin-mc-banner suggest-up').html(
                    '<i class="ri-checkbox-circle-fill"></i><span><strong>Sugerencia:</strong> reactivar contablemente. ' +
                    'No tiene cuotas vencidas pendientes.</span>'
                );
                $accion.css('background', 'linear-gradient(135deg,#047857,#059669)').show();
                $('#modalContableAccionText').text('Reactivar contable');
                $accion.attr('data-nuevo-valor', '1');
            } else {
                $header.css('background', 'linear-gradient(135deg,#1e293b 0%,#334155 50%,#475569 100%)');
                $('#modalContableHeaderIcon').attr('class', 'ri-information-line');
                $('#modalContableTitulo').text('Detalle de cuotas');
                $banner.attr('class', 'fin-mc-banner no-suggest').html(
                    '<i class="ri-information-line"></i><span>Estado contable y realidad de cuotas son coherentes — sin sugerencias.</span>'
                );
                $accion.hide();
            }

            $accion.attr('data-inscripcion-id', inscripcionId);

            // KPIs
            $('#modalContableKpiVencidas').text(d.cantidad_vencidas);
            $('#modalContableKpiVencidasMonto').text(fmtBs(d.monto_vencido));
            $('#modalContableKpiPorVencer').text((d.cuotas_por_vencer || []).length);
            $('#modalContableKpiPorVencerMonto').text(fmtBs(d.monto_por_vencer));
            $('#modalContableKpiPagadas').text((d.cuotas_pagadas || []).length);

            // Tabla vencidas
            const $tbV = $('#modalContableTbodyVencidas').empty();
            if ((d.cuotas_vencidas || []).length === 0) {
                $('#modalContableSeccionVencidas').hide();
            } else {
                $('#modalContableSeccionVencidas').show();
                d.cuotas_vencidas.forEach(function(c) {
                    $tbV.append(
                        '<tr>' +
                        '<td><strong>#' + (c.n_cuota || '-') + '</strong> <span style="color:#64748b;">' + escHtml(c.nombre || '') + '</span></td>' +
                        '<td class="text-end">' + fmtBs(c.monto) + '</td>' +
                        '<td class="text-end" style="color:#059669;">' + fmtBs(c.pagado) + '</td>' +
                        '<td class="text-end" style="color:#b91c1c;font-weight:700;">' + fmtBs(c.pendiente) + '</td>' +
                        '<td class="text-center" style="color:#475569;">' + (c.fecha_vencimiento_fmt || '—') + '</td>' +
                        '<td class="text-center"><span class="fin-mc-dias">' + c.dias_atraso + ' día(s)</span></td>' +
                        '</tr>'
                    );
                });
            }

            // Tabla por vencer
            const $tbP = $('#modalContableTbodyPorVencer').empty();
            if ((d.cuotas_por_vencer || []).length === 0) {
                $('#modalContableSeccionPorVencer').hide();
            } else {
                $('#modalContableSeccionPorVencer').show();
                d.cuotas_por_vencer.forEach(function(c) {
                    $tbP.append(
                        '<tr>' +
                        '<td><strong>#' + (c.n_cuota || '-') + '</strong> <span style="color:#64748b;">' + escHtml(c.nombre || '') + '</span></td>' +
                        '<td class="text-end">' + fmtBs(c.monto) + '</td>' +
                        '<td class="text-end" style="color:#059669;">' + fmtBs(c.pagado) + '</td>' +
                        '<td class="text-end" style="color:#d97706;font-weight:700;">' + fmtBs(c.pendiente) + '</td>' +
                        '<td class="text-center" style="color:#475569;">' + (c.fecha_vencimiento_fmt || '—') + '</td>' +
                        '</tr>'
                    );
                });
            }

            $('#modalContableLoading').hide();
            $('#modalContableContent').show();
        }

        // Aplicar acción sugerida desde el modal
        $(document).on('click', '#modalContableAccion', function() {
            const $btn = $(this);
            const inscripcionId = $btn.attr('data-inscripcion-id');
            const nuevoValor = $btn.attr('data-nuevo-valor') === '1';
            if (!inscripcionId) return;
            $btn.prop('disabled', true);

            $.ajax({
                url: '/admin/ofertas-academicas/inscripciones/' + inscripcionId + '/toggle-estado',
                type: 'PATCH',
                data: { campo: 'activo_contable', valor: nuevoValor ? 1 : 0, _token: '{{ csrf_token() }}' }
            }).done(function(resp) {
                if (!resp.success) {
                    toast('error', resp.message || 'No se pudo actualizar.');
                    return;
                }
                const d = resp.data || {};
                window.aplicarEstadoInscripcion(inscripcionId, {
                    activo:           d.activo,
                    activo_contable:  d.activo_contable  !== undefined ? d.activo_contable  : nuevoValor,
                    activo_academico: d.activo_academico,
                });
                toast('success', 'Estado contable actualizado.');
                bootstrap.Modal.getInstance(document.getElementById('modalContableDetalle'))?.hide();
            }).fail(function(xhr) {
                toast('error', xhr.responseJSON?.message || 'Error al actualizar.');
            }).always(function() {
                $btn.prop('disabled', false);
            });
        });

        // ── Sugerencias en el tab Inscripciones (cell-sug) ──
        // Click en la sugerencia contable → reusar el modal de Finanzas
        $(document).on('click', '.ins-cell-sug-btn[data-tipo="contable"]', function(e) {
            e.stopPropagation();
            const id = $(this).attr('data-inscripcion-id');
            if (id) abrirModalContable(id);
        });

        // Click en la sugerencia académica → modal académico (requiere data en areaAcadDataMap)
        $(document).on('click', '.ins-cell-sug-btn[data-tipo="academico"]', function(e) {
            e.stopPropagation();
            const id = $(this).attr('data-inscripcion-id');
            if (!id) return;
            if (areaAcadDataMap[id]) {
                abrirModalAcademico(id);
            } else {
                toast('warning', 'Cambia al tab Área Académica para ver el detalle de notas.');
            }
        });

        // Click en la sugerencia general → modal combinado
        $(document).on('click', '.ins-cell-sug-btn[data-tipo="general"]', function(e) {
            e.stopPropagation();
            const id = $(this).attr('data-inscripcion-id');
            if (id) abrirModalGeneralBaja(id);
        });

        function abrirModalGeneralBaja(inscripcionId) {
            const _mgEl = document.getElementById('modalGeneralBaja');
            if (!_mgEl) {
                toast('error', 'No se encontró el modal de sugerencia general.');
                return;
            }
            // Reubicar el modal al <body> para evitar stacking-context heredado
            if (_mgEl.parentElement !== document.body) {
                document.body.appendChild(_mgEl);
            }
            // Reset visual
            $('#modalGeneralLoading').show();
            $('#modalGeneralContent').hide();
            // Usar getOrCreateInstance para no apilar instancias
            const _mgInstance = bootstrap.Modal.getOrCreateInstance(_mgEl, { backdrop: true, focus: true });
            _mgInstance.show();

            // Trae detalle contable; el académico viene de areaAcadDataMap
            $.ajax({
                url: '/admin/ofertas-academicas/inscripciones/' + inscripcionId + '/detalle-estado-contable',
                type: 'GET',
            }).done(function(resp) {
                if (!resp.success) {
                    toast('error', resp.message || 'No se pudo cargar el detalle.');
                    bootstrap.Modal.getInstance(document.getElementById('modalGeneralBaja'))?.hide();
                    return;
                }
                renderModalGeneralBaja(resp.data, inscripcionId);
            }).fail(function() {
                toast('error', 'Error al cargar el detalle.');
                bootstrap.Modal.getInstance(document.getElementById('modalGeneralBaja'))?.hide();
            });
        }

        function renderModalGeneralBaja(c, inscripcionId) {
            const a = areaAcadDataMap[inscripcionId] || null;

            $('#modalGeneralSubtitulo').text((c.estudiante || '—') + ' · CI ' + (c.carnet || '—'));

            // Sección Contable
            $('#modalGeneralContBanner').text(
                c.cantidad_vencidas + ' cuota(s) vencida(s) · ' + fmtBs(c.monto_vencido)
            );
            const $tbC = $('#modalGeneralContTbody').empty();
            (c.cuotas_vencidas || []).forEach(function(cu) {
                $tbC.append(
                    '<tr>' +
                    '<td><strong>#' + (cu.n_cuota || '-') + '</strong> <span style="color:#64748b;">' + escHtml(cu.nombre || '') + '</span></td>' +
                    '<td class="text-end" style="color:#b91c1c;font-weight:700;">' + fmtBs(cu.pendiente) + '</td>' +
                    '<td class="text-center" style="color:#475569;">' + (cu.fecha_vencimiento_fmt || '—') + '</td>' +
                    '<td class="text-center"><span class="fin-mc-dias">' + cu.dias_atraso + ' día(s)</span></td>' +
                    '</tr>'
                );
            });

            // Sección Académica
            if (a) {
                $('#modalGeneralAcadBanner').text(
                    a.cantidad_reprobados + ' módulo(s) reprobado(s) · Nota mínima ' + a.nota_minima
                );
                const $tbA = $('#modalGeneralAcadTbody').empty();
                (a.modulos_reprobados || []).forEach(function(m) {
                    const diff = (Number(m.nota_regular) - Number(m.nota_minima)).toFixed(2);
                    $tbA.append(
                        '<tr>' +
                        '<td><i class="ri-book-2-line" style="color:#94a3b8;margin-right:5px;"></i>' + escHtml(m.modulo_nombre || '') + '</td>' +
                        '<td class="text-center"><span class="aa-mc-nota fail">' + Number(m.nota_regular).toFixed(2) + '</span></td>' +
                        '<td class="text-center" style="color:#64748b;">' + Number(m.nota_minima).toFixed(2) + '</td>' +
                        '<td class="text-center"><span class="aa-mc-diff">' + diff + '</span></td>' +
                        '</tr>'
                    );
                });
                $('#modalGeneralAcadEmpty').hide();
                $('#modalGeneralAcadWrap').show();
            } else {
                $('#modalGeneralAcadEmpty').show();
                $('#modalGeneralAcadWrap').hide();
            }

            $('#modalGeneralAccion').attr('data-inscripcion-id', inscripcionId);
            $('#modalGeneralLoading').hide();
            $('#modalGeneralContent').show();
        }

        // Aplicar baja general desde el modal combinado
        $(document).on('click', '#modalGeneralAccion', function() {
            const $btn = $(this);
            const inscripcionId = $btn.attr('data-inscripcion-id');
            if (!inscripcionId) return;
            $btn.prop('disabled', true);

            $.ajax({
                url: '/admin/ofertas-academicas/inscripciones/' + inscripcionId + '/toggle-estado',
                type: 'PATCH',
                data: { campo: 'activo', valor: 0, _token: '{{ csrf_token() }}' }
            }).done(function(resp) {
                if (!resp.success) { toast('error', resp.message || 'No se pudo actualizar.'); return; }
                const d = resp.data || {};
                window.aplicarEstadoInscripcion(inscripcionId, {
                    activo:           d.activo           !== undefined ? d.activo           : false,
                    activo_contable:  d.activo_contable  !== undefined ? d.activo_contable  : false,
                    activo_academico: d.activo_academico !== undefined ? d.activo_academico : false,
                });
                // Refrescar la tabla de inscripciones para reflejar tinte de fila + sugerencias
                if (typeof cargarInscripciones === 'function') cargarInscripciones();
                toast('success', 'Estado general actualizado (dado de baja).');
                bootstrap.Modal.getInstance(document.getElementById('modalGeneralBaja'))?.hide();
            }).fail(function(xhr) {
                toast('error', xhr.responseJSON?.message || 'Error al actualizar.');
            }).always(function() {
                $btn.prop('disabled', false);
            });
        });

        // Toggle de estado Contable / Académico por inscripción
        $(document).on('click', '.ins-estado-toggle', function(e) {
            e.stopPropagation();
            const $btn = $(this);
            if ($btn.hasClass('loading')) return;

            const inscripcionId = $btn.attr('data-inscripcion-id');
            const campo = $btn.attr('data-campo');
            // Leer del atributo (no de .data() — jQuery cachea el valor inicial)
            const valorActual = Number($btn.attr('data-valor')) === 1;
            const nuevoValor = !valorActual;

            $btn.addClass('loading');

            $.ajax({
                url: '/admin/ofertas-academicas/inscripciones/' + inscripcionId + '/toggle-estado',
                type: 'PATCH',
                data: { campo: campo, valor: nuevoValor ? 1 : 0, _token: '{{ csrf_token() }}' }
            })
            .done(function(resp) {
                if (!resp.success) {
                    toast('error', resp.message || 'No se pudo actualizar el estado.');
                    return;
                }
                // Sincroniza TODAS las tabs (Inscripciones, Finanzas, Área Académica, Moodle)
                // con los valores reales que devuelve el backend.
                const d = resp.data || {};
                window.aplicarEstadoInscripcion(inscripcionId, {
                    activo:           d.activo           !== undefined ? d.activo           : (campo === 'activo'           ? nuevoValor : undefined),
                    activo_contable:  d.activo_contable  !== undefined ? d.activo_contable  : (campo === 'activo_contable'  ? nuevoValor : undefined),
                    activo_academico: d.activo_academico !== undefined ? d.activo_academico : (campo === 'activo_academico' ? nuevoValor : undefined),
                });
                const labelTxt = campo === 'activo_contable' ? 'Estado contable'
                    : (campo === 'activo_academico' ? 'Estado académico' : 'Estado general');
                toast('success', labelTxt + ' actualizado.');
            })
            .fail(function(xhr) {
                const msg = xhr.responseJSON?.message || 'Error al actualizar el estado.';
                toast('error', msg);
            })
            .always(function() {
                $btn.removeClass('loading');
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

            const displayUrl = 'https://posgradosinnovaciencia.com';
            const v = window.OFERTA_VERSION;
            const g = window.OFERTA_GRUPO;
            const gestion = window.OFERTA_GESTION;
            const posgrado = window.OFERTA_POSGRADO;
            const subtitleParts = [];
            if (posgrado) subtitleParts.push(posgrado);
            if (gestion) subtitleParts.push(gestion);
            if (v || g) subtitleParts.push('V' + (v || '?') + ' G' + (g || '?'));
            const subtitle = subtitleParts.length ? '\n_' + subtitleParts.join(' - ') + '_' : '';

            const mensaje = [
                '\uD83C\uDF93 *INNOVA CIENCIA VIRTUAL*',
                subtitle ? subtitle + '\n' : '',
                '\u2728 \u00A1Bienvenido/a a tu plataforma acad\u00E9mica!',
                '',
                '\uD83D\uDC4B Estimado/a *' + nombre + '*,',
                'Te damos la bienvenida al *' + programa + '*.',
                'A continuaci\u00F3n encontrar\u00E1s tus credenciales de acceso a la plataforma virtual.',
                '',
                '\uD83D\uDD10 *ACCESO A LA PLATAFORMA*',
                '\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC',
                '\uD83C\uDF10  *Sitio web:*  ' + displayUrl,
                '\uD83D\uDC64  *Usuario:*      `' + username + '`',
                '\uD83D\uDD11  *Contrase\u00F1a:*  `' + password + '`',
                '\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC',
                '',
                '\uD83D\uDCCC *PASOS PARA INGRESAR*',
                '1\uFE0F\u20E3 Abre tu navegador (Chrome, Edge o Firefox)',
                '2\uFE0F\u20E3 Visita \u2192 ' + displayUrl,
                '3\uFE0F\u20E3 Ingresa tu usuario y contrase\u00F1a',
                '4\uFE0F\u20E3 Completa tu perfil en el primer acceso',
                '',
                '\u26A0\uFE0F *IMPORTANTE*',
                '\u2022 Guarda tus credenciales en un lugar seguro.',
                '\u2022 No compartas tu contrase\u00F1a con nadie.',
                '\u2022 Si olvidas tu acceso, cont\u00E1ctanos de inmediato.',
                '',
                '\uD83D\uDE80 *\u00A1\u00C9xitos en tu proceso de formaci\u00F3n!*',
                '',
                '\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500',
                '\uD83C\uDFE2 *\u00C1rea Acad\u00E9mica*',
                '\uD83D\uDCCD Innova Ciencia Virtual',
                '\uD83D\uDCE7 soporte@posgradosinnovaciencia.com',
                '\uD83D\uDCF1 +591 XXX XXX XXX',
            ].join('\n');

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

            // Recolectar montos y fechas editadas por el usuario
            const cuotasPersonalizadasCambio = [];
            $('.cuota-monto-cambio').each(function() {
                const conceptoIdx = $(this).data('concepto-idx');
                const cuotaIdx    = $(this).data('cuota-idx');
                const fecha = $('.cuota-fecha-cambio[data-concepto-idx="' + conceptoIdx +
                                '"][data-cuota-idx="' + cuotaIdx + '"]').val();
                cuotasPersonalizadasCambio.push({
                    concepto_idx: parseInt(conceptoIdx),
                    cuota_idx:    parseInt(cuotaIdx),
                    monto:        parseFloat($(this).val()) || 0,
                    fecha:        fecha || null
                });
            });

            $.ajax({
                    url: '/admin/posgrads/ofertas/' + window.OFERTA_ID + '/inscripciones/' +
                        inscripcionCambiarId + '/cambiar-a-inscrito',
                    type: 'POST',
                    data: {
                        planes_pago_id: planId,
                        cuotas_personalizadas: cuotasPersonalizadasCambio.length
                            ? JSON.stringify(cuotasPersonalizadasCambio)
                            : null,
                        _token: CSRF
                    }
                })
                .done(function(r) {
                    closeModal('modalCambiarAInscrito');
                    toast('success', r.message || 'Inscripción completada correctamente.');
                    cargarInscripciones();
                    // Abrir modal de comprobante
                    const nombreComp = estudianteCambiarData ? estudianteCambiarData.nombre : '—';
                    const planComp   = $('#cambiarPlanPagoSelect option:selected').text();
                    abrirComprobanteInscripcion(inscripcionCambiarId, nombreComp, planComp);
                })
                .fail(function(xhr) {
                    let msg = 'Error al cambiar el estado.';
                    if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    toast('error', msg);
                })
                .always(function() {
                    btn.prop('disabled', false).html('<i class="ri-user-check-line me-1"></i>Confirmar Inscripción');
                });
        });

        // ===== COMPROBANTE POST-INSCRIPCIÓN =====

        let compInscripcionId = null;

        function abrirComprobanteInscripcion(inscripcionId, nombre, plan) {
            compInscripcionId = inscripcionId;

            $('#compInscNombre').text(nombre);
            $('#compInscDetalle').text('Plan: ' + plan + ' · Inscripción completada');
            $('#compInscArchivo').val('');
            $('#compInscObservaciones').val('');
            $('#compInscCuotasContainer').hide().html('');
            $('#compInscCuotasLoading').show();

            var modal = new bootstrap.Modal(document.getElementById('modalComprobanteInscripcion'));
            modal.show();

            $.get('/admin/ofertas-academicas/inscripciones/' + inscripcionId + '/cuotas')
                .done(function(data) {
                    $('#compInscCuotasLoading').hide();
                    if (!data.success) {
                        $('#compInscCuotasContainer').html('<p class="p-3 text-muted" style="font-size:.82rem;">No se pudieron cargar las cuotas.</p>').show();
                        return;
                    }
                    var grupo = data.grupo;
                    var html = '<div style="background:#f8fafc;padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;font-weight:600;font-size:.82rem;color:#475569;">'
                        + '<i class="ri-bank-card-line me-1"></i>' + escHtml(grupo.plan_nombre) + '</div>'
                        + '<div style="padding:.6rem .75rem;">';

                    if (!grupo.cuotas || !grupo.cuotas.length) {
                        html += '<p style="color:#16a34a;font-size:.82rem;margin:.5rem 0;display:flex;align-items:center;gap:.4rem;"><i class="ri-checkbox-circle-line"></i>Todas las cuotas están al día.</p>';
                    } else {
                        grupo.cuotas.forEach(function(c) {
                            var estadoColor = c.estado === 'pagado' ? '#16a34a' : c.estado === 'vencido' ? '#dc2626' : '#f59e0b';
                            html += '<label style="display:flex;align-items:center;gap:.75rem;padding:.5rem .25rem;border-bottom:1px solid #f8fafc;cursor:pointer;">'
                                + '<input type="checkbox" name="compCuotas[]" value="' + c.id + '" style="width:15px;height:15px;accent-color:#9a4904;flex-shrink:0;">'
                                + '<div style="flex:1;">'
                                +   '<div style="font-size:.83rem;font-weight:500;color:#1e293b;">' + escHtml(c.nombre) + ' #' + c.n_cuota + '</div>'
                                +   '<div style="font-size:.72rem;color:#64748b;">Bs ' + c.monto_bs + ' · Pendiente Bs ' + c.pago_pendiente_bs + ' · Vence: ' + (c.fecha_vencimiento || '—') + '</div>'
                                + '</div>'
                                + '<span style="font-size:.7rem;font-weight:600;color:' + estadoColor + ';background:' + estadoColor + '1a;padding:.15rem .45rem;border-radius:4px;">' + escHtml(c.estado) + '</span>'
                                + '</label>';
                        });
                    }
                    html += '</div>';
                    $('#compInscCuotasContainer').html(html).show();
                })
                .fail(function() {
                    $('#compInscCuotasLoading').hide();
                    $('#compInscCuotasContainer').html('<p class="p-3 text-danger" style="font-size:.82rem;">Error al cargar las cuotas.</p>').show();
                });
        }

        $('#btnEnviarComprobanteInscripcion').on('click', function() {
            if (!compInscripcionId) return;

            var archivo = document.getElementById('compInscArchivo').files[0];
            if (!archivo) { toast('warning', 'Debes seleccionar un archivo.'); return; }

            var cuotasChecked = $('input[name="compCuotas[]"]:checked');
            if (!cuotasChecked.length) { toast('warning', 'Selecciona al menos una cuota.'); return; }

            var formData = new FormData();
            formData.append('_token', CSRF);
            formData.append('inscripcion_id', compInscripcionId);
            formData.append('archivo', archivo);
            formData.append('observaciones', $('#compInscObservaciones').val());
            cuotasChecked.each(function() { formData.append('cuotas[]', $(this).val()); });

            var btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Enviando...');

            $.ajax({
                url: '/admin/ofertas-academicas/inscripciones/comprobante',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false
            })
            .done(function(r) {
                btn.prop('disabled', false).html('<i class="ri-upload-cloud-line"></i>Enviar Comprobante');
                if (r.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalComprobanteInscripcion'))?.hide();
                    toast('success', r.mensaje || r.message || 'Comprobante enviado correctamente.');
                } else {
                    toast('error', r.message || 'Error al enviar el comprobante.');
                }
            })
            .fail(function() {
                btn.prop('disabled', false).html('<i class="ri-upload-cloud-line"></i>Enviar Comprobante');
                toast('error', 'Error de conexión al enviar el comprobante.');
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
                        const apellidoPaterno = partesNombre.length === 2
                            ? partesNombre[1]
                            : (partesNombre.length > 2 ? partesNombre[partesNombre.length - 2] : '');
                        const apellidoMaterno = partesNombre.length > 2 ? partesNombre[partesNombre.length - 1] : '';
                        
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
            $('#moodleCuentasCount').text(estudiantesSinCuentaMoodle.length);
            let html = '';
            estudiantesSinCuentaMoodle.forEach(function(est, idx) {
                html += '<tr style="border-bottom:1px solid #f1f5f9;transition:background .1s;">' +
                    '<td style="padding:.6rem .5rem;text-align:center;"><input type="checkbox" class="checkbox-moodle-account" data-id="' + est.id + '" style="width:16px;height:16px;accent-color:#fc7b04;cursor:pointer;"></td>' +
                    '<td style="padding:.6rem .75rem;font-weight:600;color:#1e293b;font-size:.82rem;">' + escHtml(est.nombre) + '</td>' +
                    '<td style="padding:.6rem .75rem;color:#64748b;font-size:.8rem;">' + escHtml(est.ci) + '</td>' +
                    '<td style="padding:.6rem .75rem;"><code style="font-size:.75rem;background:rgba(252,123,4,.07);color:#9a4904;padding:.15rem .4rem;border-radius:5px;border:1px solid rgba(252,123,4,.12);">' + escHtml(est.usernames.op1) + '</code></td>' +
                    '<td style="padding:.6rem .75rem;"><code style="font-size:.75rem;background:rgba(16,185,129,.07);color:#047857;padding:.15rem .4rem;border-radius:5px;border:1px solid rgba(16,185,129,.12);">' + escHtml(est.password) + '</code></td>' +
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
            $('#editModuloDocenteCarnet').val('').css('border-color', '');
            $('#editModuloDocenteCarnetFeedback').html('').css('color', '');
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
        $(document).on('click', '#btnLimpiarDocenteModulo', function() {
            $('#editModuloDocenteId').val('');
            $('#editModuloDocentePreview').hide();
            $('#editModuloDocenteCarnet').val('').css('border-color', '').focus();
            $('#editModuloDocenteCarnetFeedback').html('').css('color', '');
        });

        $('#btnBuscarDocenteModulo').on('click', function() {
            const v = validarCarnetBuscador();
            if (v.empty) {
                toast('warning', 'Ingrese un carnet.');
                $('#editModuloDocenteCarnet').focus();
                return;
            }
            if (!v.ok) {
                toast('warning', 'El carnet debe tener entre 7 y 11 dígitos numéricos.');
                $('#editModuloDocenteCarnet').focus();
                return;
            }
            const carnet = v.value;
            $.ajax({
                    url: '{{ route('admin.posgrads.modulos.buscar-docente') }}',
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
                            expedido: r.persona.expedido || '',
                            nombres: r.persona.nombres || r.persona.nombre.split(' ')[0] || '',
                            apellido_paterno: r.persona.apellido_paterno || '',
                            apellido_materno: r.persona.apellido_materno || '',
                            nombre_completo: r.persona.nombre,
                            correo: r.persona.correo || '',
                            celular: r.persona.celular || '',
                            telefono: r.persona.telefono || '',
                            direccion: r.persona.direccion || '',
                            fecha_nacimiento: r.persona.fecha_nacimiento || '',
                            sexo: r.persona.sexo || '',
                            estado_civil: r.persona.estado_civil || '',
                            ciudad_id: r.persona.ciudad_id || '',
                            departamento_id: r.persona.departamento_id || '',
                            estudios: r.persona.estudios || [],
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
                $('#registroDocenteExpedido').val('');
                $('#registroDocenteNombres').val('');
                $('#registroDocenteApellidoPaterno').val('');
                $('#registroDocenteApellidoMaterno').val('');
                $('#registroDocenteCorreo').val('');
                $('#registroDocenteCelular').val('');
                $('#registroDocenteTelefono').val('');
                $('#registroDocenteDireccion').val('');
                $('#registroDocenteFechaNacimiento').val('');
                $('#registroDocenteSexo').val('');
                $('#registroDocenteEstadoCivil').val('');
                $('#registroDocenteDepartamento').val('');
                $('#registroDocenteCiudad').html('<option value="">— Primero elige departamento —</option>').prop('disabled', true);
                $('#registroDocenteCarnet').prop('readonly', true);
                if (docenteTempData.tipo === 'persona_encontrada') {
                    $('#registroDocentePersonaId').val(docenteTempData.persona_id);
                    $('#registroDocenteExpedido').val(docenteTempData.expedido || '');
                    $('#registroDocenteNombres').val(docenteTempData.nombres || '');
                    $('#registroDocenteApellidoPaterno').val(docenteTempData.apellido_paterno || '');
                    $('#registroDocenteApellidoMaterno').val(docenteTempData.apellido_materno || '');
                    $('#registroDocenteCorreo').val(docenteTempData.correo || '');
                    $('#registroDocenteCelular').val(docenteTempData.celular || '');
                    $('#registroDocenteTelefono').val(docenteTempData.telefono || '');
                    $('#registroDocenteDireccion').val(docenteTempData.direccion || '');
                    $('#registroDocenteFechaNacimiento').val(docenteTempData.fecha_nacimiento || '');
                    $('#registroDocenteSexo').val(docenteTempData.sexo || '');
                    $('#registroDocenteEstadoCivil').val(docenteTempData.estado_civil || '');
                }
                const mod = allModulos.find(m => m.id === $('#editModuloId').val());
                const color = mod ? (mod.color || '#6366f1') : '#6366f1';
                $('#registroDocenteColorBar').css('background', color);
                $('#estudiosRowsContainer').empty();
                $('#estudiosEmptyMsg').show();
                estudioRowCount = 0;
                cargarDepartamentosDocente(
                    docenteTempData.tipo === 'persona_encontrada' ? docenteTempData.departamento_id : null,
                    docenteTempData.tipo === 'persona_encontrada' ? docenteTempData.ciudad_id : null
                );
                openModal('modalRegistroDocente');
            }, 300);
        });

        function cargarDepartamentosDocente(preselectedDepId, preselectedCiudadId) {
            const $select = $('#registroDocenteDepartamento');
            $select.html('<option value="">Cargando...</option>');
            $.get('/admin/personas/listar-departamentos')
                .done(function(r) {
                    const lista = Array.isArray(r) ? r : (r.data || []);
                    const opts = lista.map(d => '<option value="' + d.id + '">' + d.nombre + '</option>').join('');
                    $select.html('<option value="">— Seleccionar —</option>' + opts);
                    if (preselectedDepId) {
                        $select.val(preselectedDepId);
                        if (preselectedCiudadId) {
                            const $ciudadSelect = $('#registroDocenteCiudad');
                            $ciudadSelect.html('<option value="">Cargando ciudades...</option>').prop('disabled', true);
                            $.get('/admin/departamentos/' + preselectedDepId + '/ciudades/listar')
                                .done(function(rc) {
                                    const ciudades = Array.isArray(rc) ? rc : (rc.data || []);
                                    if (ciudades.length) {
                                        const copts = ciudades.map(c => '<option value="' + c.id + '">' + c.nombre + '</option>').join('');
                                        $ciudadSelect
                                            .html('<option value="">— Seleccionar ciudad —</option>' + copts)
                                            .prop('disabled', false)
                                            .val(preselectedCiudadId);
                                    }
                                })
                                .fail(function() {
                                    $ciudadSelect.html('<option value="">Error al cargar ciudades</option>').prop('disabled', true);
                                });
                        }
                    }
                })
                .fail(function() {
                    $select.html('<option value="">Error al cargar</option>');
                });
        }

        $(document).on('change', '#registroDocenteDepartamento', function() {
            const depId = $(this).val();
            const $ciudadSelect = $('#registroDocenteCiudad');
            if (!depId) {
                $ciudadSelect.html('<option value="">— Primero elige departamento —</option>').prop('disabled', true);
                return;
            }
            $ciudadSelect.html('<option value="">Cargando ciudades...</option>').prop('disabled', true);
            $.get('/admin/departamentos/' + depId + '/ciudades/listar')
                .done(function(r) {
                    const lista = Array.isArray(r) ? r : (r.data || []);
                    if (!lista.length) {
                        $ciudadSelect.html('<option value="">Sin ciudades registradas</option>').prop('disabled', true);
                        return;
                    }
                    const opts = lista.map(c => '<option value="' + c.id + '">' + c.nombre + '</option>').join('');
                    $ciudadSelect.html('<option value="">— Seleccionar ciudad —</option>' + opts).prop('disabled', false);
                })
                .fail(function() {
                    $ciudadSelect.html('<option value="">Error al cargar ciudades</option>').prop('disabled', true);
                });
        });

        function clearValidationErrors() {
            $('#registroDocenteCarnet, #registroDocenteNombres, #registroDocenteApellidoPaterno, #registroDocenteApellidoMaterno, #registroDocenteCorreo, #registroDocenteCelular, #registroDocenteSexo, #registroDocenteEstadoCivil, #registroDocenteCiudad')
                .removeClass('is-invalid-custom is-valid-custom');
            $('#registroDocenteCarnetError, #registroDocenteNombresError, #registroDocenteApellidoPaternoError, #registroDocenteApellidoMaternoError, #registroDocenteCorreoError, #registroDocenteCelularError, #registroDocenteSexoError, #registroDocenteEstadoCivilError, #registroDocenteCiudadError')
                .text('');
        }

        function setFieldError(fieldId, errorId, msg) {
            $('#' + fieldId).addClass('is-invalid-custom');
            $('#' + errorId).text(msg);
        }

        function validateField(fieldId, value, rules) {
            clearSingleFieldError(fieldId);
            const val = (value || '').toString();
            if (rules.required && !val.trim()) {
                const label = $('#' + fieldId).closest('div').find('label').first().text().replace('*', '').trim();
                setFieldError(fieldId, fieldId + 'Error', (label || 'Este campo') + ' es requerido.');
                return false;
            }
            if (rules.email && val && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                setFieldError(fieldId, fieldId + 'Error', 'Ingrese un correo válido.');
                return false;
            }
            if (rules.maxlength && val.length > rules.maxlength) {
                setFieldError(fieldId, fieldId + 'Error', 'Máximo ' + rules.maxlength + ' caracteres.');
                return false;
            }
            if (rules.digitsOnly && val && !/^\d+$/.test(val)) {
                setFieldError(fieldId, fieldId + 'Error', 'Solo se permiten números.');
                return false;
            }
            if (rules.exactDigits && val && !new RegExp('^\\d{' + rules.exactDigits + '}$').test(val)) {
                setFieldError(fieldId, fieldId + 'Error', 'Debe tener exactamente ' + rules.exactDigits + ' dígitos.');
                return false;
            }
            if (rules.minDigits && val && val.length < rules.minDigits) {
                setFieldError(fieldId, fieldId + 'Error', 'Mínimo ' + rules.minDigits + ' dígitos.');
                return false;
            }
            if (rules.pattern && val && !rules.pattern.test(val)) {
                setFieldError(fieldId, fieldId + 'Error', rules.patternMsg || 'Formato inválido.');
                return false;
            }
            if (val.trim() !== '') {
                $('#' + fieldId).addClass('is-valid-custom');
            }
            return true;
        }

        function clearSingleFieldError(fieldId) {
            $('#' + fieldId).removeClass('is-invalid-custom is-valid-custom');
            $('#' + fieldId + 'Error').text('').removeClass('valid-feedback-custom');
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
        function validarApellidos() {
            const ap = $('#registroDocenteApellidoPaterno').val().trim();
            const am = $('#registroDocenteApellidoMaterno').val().trim();
            clearSingleFieldError('registroDocenteApellidoPaterno');
            clearSingleFieldError('registroDocenteApellidoMaterno');
            if (!ap && !am) {
                setFieldError('registroDocenteApellidoPaterno', 'registroDocenteApellidoPaternoError',
                    'Ingrese al menos un apellido (paterno o materno).');
                setFieldError('registroDocenteApellidoMaterno', 'registroDocenteApellidoMaternoError',
                    'Ingrese al menos un apellido (paterno o materno).');
                return false;
            }
            if (ap.length > 100) {
                setFieldError('registroDocenteApellidoPaterno', 'registroDocenteApellidoPaternoError', 'Máximo 100 caracteres.');
                return false;
            }
            if (am.length > 100) {
                setFieldError('registroDocenteApellidoMaterno', 'registroDocenteApellidoMaternoError', 'Máximo 100 caracteres.');
                return false;
            }
            if (ap) $('#registroDocenteApellidoPaterno').addClass('is-valid-custom');
            if (am) $('#registroDocenteApellidoMaterno').addClass('is-valid-custom');
            return true;
        }
        $('#registroDocenteApellidoPaterno, #registroDocenteApellidoMaterno').on('input', function() {
            validarApellidos();
        });

        // Correo: validación de formato + verificación de unicidad en tiempo real (debounced)
        let correoCheckTimer = null;
        let correoCheckXhr = null;
        $('#registroDocenteCorreo').on('input', function() {
            const $input = $(this);
            const val = $input.val().trim();
            const ok = validateField('registroDocenteCorreo', val, {
                email: true,
                maxlength: 150
            });
            if (correoCheckTimer) clearTimeout(correoCheckTimer);
            if (correoCheckXhr) { try { correoCheckXhr.abort(); } catch (e) {} }
            if (!ok || !val) return;
            correoCheckTimer = setTimeout(function() {
                correoCheckXhr = $.ajax({
                    url: '{{ route('admin.personas.verificarCorreo') }}',
                    type: 'POST',
                    data: {
                        _token: CSRF,
                        correo: val,
                        id: $('#registroDocentePersonaId').val() || ''
                    }
                }).done(function(r) {
                    if (r && r.existe) {
                        setFieldError('registroDocenteCorreo', 'registroDocenteCorreoError',
                            'Este correo ya está registrado.');
                    } else {
                        $('#registroDocenteCorreoError').text('Correo disponible.').addClass('valid-feedback-custom');
                        $('#registroDocenteCorreo').addClass('is-valid-custom');
                    }
                });
            }, 450);
        });

        // Celular: solo números, exactamente 8 dígitos
        $('#registroDocenteCelular').on('input', function() {
            const cleaned = ($(this).val() || '').replace(/\D/g, '').slice(0, 8);
            if (cleaned !== $(this).val()) $(this).val(cleaned);
            validateField('registroDocenteCelular', cleaned, {
                required: true,
                digitsOnly: true,
                exactDigits: 8
            });
        });

        // Sexo, Estado Civil, Ciudad: requeridos (validación en tiempo real al cambiar)
        $('#registroDocenteSexo').on('change', function() {
            validateField('registroDocenteSexo', $(this).val(), { required: true });
        });
        $('#registroDocenteEstadoCivil').on('change', function() {
            validateField('registroDocenteEstadoCivil', $(this).val(), { required: true });
        });
        $('#registroDocenteCiudad').on('change', function() {
            validateField('registroDocenteCiudad', $(this).val(), { required: true });
        });

        // Carnet del buscador de docente (en editar módulo): solo dígitos, 7-11
        function setBuscarDocenteEnabled(enabled) {
            const $btn = $('#btnBuscarDocenteModulo');
            $btn.prop('disabled', !enabled).css({
                'opacity': enabled ? '' : '0.5',
                'cursor': enabled ? '' : 'not-allowed',
                'pointer-events': enabled ? '' : 'none'
            });
        }
        function validarCarnetBuscador() {
            const $input = $('#editModuloDocenteCarnet');
            const $fb = $('#editModuloDocenteCarnetFeedback');
            const raw = $input.val() || '';
            const cleaned = raw.replace(/\D/g, '').slice(0, 11);
            if (cleaned !== raw) $input.val(cleaned);
            $input.css('border-color', '');
            if (!cleaned) {
                $fb.html('').css('color', '');
                setBuscarDocenteEnabled(false);
                return { ok: false, value: cleaned, empty: true };
            }
            if (cleaned.length < 7) {
                $fb.html('<i class="ri-error-warning-line"></i> El carnet debe tener entre 7 y 11 dígitos. Faltan ' + (7 - cleaned.length) + '.').css('color', '#ef4444');
                $input.css('border-color', '#ef4444');
                setBuscarDocenteEnabled(false);
                return { ok: false, value: cleaned };
            }
            if (cleaned.length > 11) {
                $fb.html('<i class="ri-error-warning-line"></i> Máximo 11 dígitos.').css('color', '#ef4444');
                $input.css('border-color', '#ef4444');
                setBuscarDocenteEnabled(false);
                return { ok: false, value: cleaned };
            }
            $fb.html('<i class="ri-checkbox-circle-line"></i> Carnet válido (' + cleaned.length + ' dígitos).').css('color', '#10b981');
            $input.css('border-color', '#10b981');
            setBuscarDocenteEnabled(true);
            return { ok: true, value: cleaned };
        }
        $(document).on('input', '#editModuloDocenteCarnet', function() {
            validarCarnetBuscador();
        });
        // Estado inicial al abrir el modal Editar Módulo
        $(document).on('shown.bs.modal', '#modalEditarModulo', function() {
            validarCarnetBuscador();
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
            const colors = ['#6366f1','#8b5cf6','#ec4899','#0ea5e9','#10b981'];
            const color  = colors[(idx - 1) % colors.length];
            const html =
                '<div class="estudio-row" data-idx="' + idx + '" style="' +
                    'border:1px solid #e2e8f0;border-radius:14px;background:#fff;' +
                    'box-shadow:0 2px 8px rgba(0,0,0,.05);overflow:hidden;' +
                    'transition:box-shadow .2s;' +
                '">' +

                    /* ── Franja de color lateral + header ── */
                    '<div style="display:flex;align-items:stretch;">' +

                        /* Franja color */
                        '<div style="width:4px;background:' + color + ';flex-shrink:0;"></div>' +

                        '<div style="flex:1;min-width:0;">' +

                            /* Header */
                            '<div style="display:flex;align-items:center;justify-content:space-between;' +
                                'padding:.6rem 1rem;background:#f8fafc;border-bottom:1px solid #f1f5f9;">' +
                                '<div style="display:flex;align-items:center;gap:.55rem;">' +
                                    '<div style="width:26px;height:26px;border-radius:7px;background:' + color + ';' +
                                        'display:flex;align-items:center;justify-content:center;flex-shrink:0;">' +
                                        '<i class="ri-graduation-cap-fill" style="color:#fff;font-size:.82rem;"></i>' +
                                    '</div>' +
                                    '<div>' +
                                        '<div class="estudio-num" style="font-size:.72rem;font-weight:800;color:#1e293b;letter-spacing:.01em;">Estudio #' + idx + '</div>' +
                                        '<div style="font-size:.62rem;color:#94a3b8;margin-top:.05rem;">Formación académica</div>' +
                                    '</div>' +
                                '</div>' +
                                '<button type="button" class="btn-remove-estudio" title="Eliminar estudio" ' +
                                    'style="width:28px;height:28px;border-radius:7px;background:rgba(239,68,68,.08);' +
                                    'border:1px solid rgba(239,68,68,.18);color:#ef4444;cursor:pointer;' +
                                    'display:flex;align-items:center;justify-content:center;font-size:.85rem;' +
                                    'transition:all .15s;flex-shrink:0;">' +
                                    '<i class="ri-close-line"></i>' +
                                '</button>' +
                            '</div>' +

                            /* Body fields */
                            '<div style="padding:.9rem 1rem 1rem;">' +

                                /* Fila 1: tres selects */
                                '<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.65rem;margin-bottom:.65rem;">' +

                                    /* Grado */
                                    '<div>' +
                                        '<label style="display:flex;align-items:center;gap:.3rem;font-size:.65rem;font-weight:700;' +
                                            'text-transform:uppercase;letter-spacing:.05em;color:#64748b;margin-bottom:.3rem;">' +
                                            '<i class="ri-award-line" style="color:' + color + ';font-size:.8rem;"></i>' +
                                            'Grado <span style="color:#ef4444;">*</span>' +
                                        '</label>' +
                                        '<select class="form-select form-select-sm estudio-grado" ' +
                                            'style="border-radius:8px;font-size:.82rem;border-color:#e2e8f0;' +
                                            'background:#fff;color:#1e293b;">' +
                                            renderGradosOptions() +
                                        '</select>' +
                                    '</div>' +

                                    /* Profesión */
                                    '<div>' +
                                        '<label style="display:flex;align-items:center;gap:.3rem;font-size:.65rem;font-weight:700;' +
                                            'text-transform:uppercase;letter-spacing:.05em;color:#64748b;margin-bottom:.3rem;">' +
                                            '<i class="ri-briefcase-line" style="color:' + color + ';font-size:.8rem;"></i>' +
                                            'Profesión' +
                                        '</label>' +
                                        '<select class="form-select form-select-sm estudio-profesion" ' +
                                            'style="border-radius:8px;font-size:.82rem;border-color:#e2e8f0;' +
                                            'background:#fff;color:#1e293b;">' +
                                            renderProfesionesOptions() +
                                        '</select>' +
                                    '</div>' +

                                    /* Universidad */
                                    '<div>' +
                                        '<label style="display:flex;align-items:center;gap:.3rem;font-size:.65rem;font-weight:700;' +
                                            'text-transform:uppercase;letter-spacing:.05em;color:#64748b;margin-bottom:.3rem;">' +
                                            '<i class="ri-building-4-line" style="color:' + color + ';font-size:.8rem;"></i>' +
                                            'Universidad' +
                                        '</label>' +
                                        '<select class="form-select form-select-sm estudio-universidad" ' +
                                            'style="border-radius:8px;font-size:.82rem;border-color:#e2e8f0;' +
                                            'background:#fff;color:#1e293b;">' +
                                            renderUniversidadesOptions() +
                                        '</select>' +
                                    '</div>' +

                                '</div>' +

                                /* Fila 2: estado + principal */
                                '<div style="display:flex;align-items:center;gap:.65rem;flex-wrap:wrap;">' +

                                    /* Estado */
                                    '<div style="min-width:140px;">' +
                                        '<label style="display:flex;align-items:center;gap:.3rem;font-size:.65rem;font-weight:700;' +
                                            'text-transform:uppercase;letter-spacing:.05em;color:#64748b;margin-bottom:.3rem;">' +
                                            '<i class="ri-checkbox-circle-line" style="color:' + color + ';font-size:.8rem;"></i>' +
                                            'Estado <span style="color:#ef4444;">*</span>' +
                                        '</label>' +
                                        '<select class="form-select form-select-sm estudio-estado" ' +
                                            'style="border-radius:8px;font-size:.82rem;border-color:#e2e8f0;">' +
                                            '<option value="En Desarrollo">En Desarrollo</option>' +
                                            '<option value="Concluido">Concluido</option>' +
                                        '</select>' +
                                    '</div>' +

                                    /* Toggle principal */
                                    '<div class="estudio-principal-wrap" style="flex:1;display:flex;align-items:flex-end;padding-bottom:.05rem;">' +
                                        '<label for="estudioPrincipal' + idx + '" ' +
                                            'style="display:inline-flex;align-items:center;gap:.5rem;' +
                                            'background:rgba(99,102,241,.05);border:1.5px solid rgba(99,102,241,.15);' +
                                            'border-radius:9px;padding:.45rem .9rem;cursor:pointer;' +
                                            'transition:all .18s;user-select:none;width:100%;">' +
                                            '<input class="form-check-input estudio-principal m-0" type="checkbox" ' +
                                                'id="estudioPrincipal' + idx + '" ' +
                                                'style="width:17px;height:17px;cursor:pointer;flex-shrink:0;' +
                                                'accent-color:' + color + ';">' +
                                            '<div>' +
                                                '<div style="font-size:.77rem;font-weight:700;color:#4f46e5;line-height:1.1;">' +
                                                    '<i class="ri-star-fill" style="color:#f59e0b;margin-right:.25rem;font-size:.75rem;"></i>' +
                                                    'Formación principal' +
                                                '</div>' +
                                                '<div style="font-size:.63rem;color:#94a3b8;margin-top:.1rem;">Será la titulación destacada del docente</div>' +
                                            '</div>' +
                                        '</label>' +
                                    '</div>' +

                                '</div>' +
                            '</div>' +

                        '</div>' +
                    '</div>' +
                '</div>';
            $('#estudiosRowsContainer').append(html);
            refreshPrincipalVisibility();
        }
        function syncEstudiosEmptyMsg() {
            const count = $('#estudiosRowsContainer .estudio-row').length;
            $('#estudiosEmptyMsg').toggle(count === 0);
        }

        function refreshPrincipalVisibility() {
            const $rows = $('#estudiosRowsContainer .estudio-row');
            const $checked = $rows.find('.estudio-principal:checked');
            if ($checked.length > 0) {
                const $principalRow = $checked.closest('.estudio-row');
                $rows.each(function() {
                    const $wrap = $(this).find('.estudio-principal-wrap');
                    if (this === $principalRow[0]) $wrap.show();
                    else $wrap.hide();
                });
            } else {
                $rows.find('.estudio-principal-wrap').show();
            }
        }

        $('#btnAddEstudioRow').on('click', function() {
            addEstudioRow();
            syncEstudiosEmptyMsg();
        });
        $(document).on('change', '.estudio-principal', function() {
            refreshPrincipalVisibility();
        });
        $(document).on('click', '.btn-remove-estudio', function() {
            const $row = $(this).closest('.estudio-row');
            $row.css({opacity:0, transform:'scale(.97)'});
            setTimeout(function() {
                $row.remove();
                renumberEstudios();
                syncEstudiosEmptyMsg();
                refreshPrincipalVisibility();
            }, 180);
        });
        $(document).on('mouseenter', '#estudiosRowsContainer .estudio-row', function() {
            $(this).css('box-shadow', '0 4px 16px rgba(0,0,0,.09)');
        }).on('mouseleave', '#estudiosRowsContainer .estudio-row', function() {
            $(this).css('box-shadow', '0 2px 8px rgba(0,0,0,.05)');
        });
        $(document).on('mouseenter', '.btn-remove-estudio', function() {
            $(this).css({background:'rgba(239,68,68,.15)', borderColor:'rgba(239,68,68,.35)'});
        }).on('mouseleave', '.btn-remove-estudio', function() {
            $(this).css({background:'rgba(239,68,68,.08)', borderColor:'rgba(239,68,68,.18)'});
        });

        function renumberEstudios() {
            const colors = ['#6366f1','#8b5cf6','#ec4899','#0ea5e9','#10b981'];
            $('#estudiosRowsContainer .estudio-row').each(function(i) {
                const num = i + 1;
                const color = colors[i % colors.length];
                $(this).find('.estudio-num').text('Estudio #' + num);
                $(this).find('[style*="width:4px"]').css('background', color);
                $(this).find('[style*="ri-graduation-cap-fill"]').closest('div[style*="width:26px"]').css('background', color);
            });
        }
        $('#btnRegistrarYAsignarDocente').on('click', function() {
            clearValidationErrors();
            const carnet          = $('#registroDocenteCarnet').val().trim(),
                expedido           = $('#registroDocenteExpedido').val().trim(),
                nombres            = $('#registroDocenteNombres').val().trim(),
                apellidoPaterno    = $('#registroDocenteApellidoPaterno').val().trim(),
                apellidoMaterno    = $('#registroDocenteApellidoMaterno').val().trim(),
                correo             = $('#registroDocenteCorreo').val().trim(),
                celular            = $('#registroDocenteCelular').val().trim(),
                telefono           = $('#registroDocenteTelefono').val().trim(),
                direccion          = $('#registroDocenteDireccion').val().trim(),
                fechaNacimiento    = $('#registroDocenteFechaNacimiento').val(),
                sexo               = $('#registroDocenteSexo').val(),
                estadoCivil        = $('#registroDocenteEstadoCivil').val(),
                ciudadId           = $('#registroDocenteCiudad').val();
            let valid = true;
            if (!validateField('registroDocenteCarnet', carnet, {
                    required: true,
                    maxlength: 20
                })) valid = false;
            if (!validateField('registroDocenteNombres', nombres, {
                    required: true,
                    maxlength: 100
                })) valid = false;
            if (!validarApellidos()) valid = false;
            if (!validateField('registroDocenteSexo', sexo, { required: true })) valid = false;
            if (!validateField('registroDocenteEstadoCivil', estadoCivil, { required: true })) valid = false;
            if (!validateField('registroDocenteCiudad', ciudadId, { required: true })) valid = false;
            if (!validateField('registroDocenteCelular', celular, {
                    required: true,
                    digitsOnly: true,
                    exactDigits: 8
                })) valid = false;
            if (correo && !validateField('registroDocenteCorreo', correo, {
                    email: true,
                    maxlength: 150
                })) valid = false;
            if (!valid) {
                toast('warning', 'Corrija los campos marcados.');
                return;
            }
            // Verificación final de correo (anti race-condition con debounced check)
            if (correo) {
                setBtnLoading('#btnRegistrarYAsignarDocente', true, 'Verificando…');
                $.ajax({
                    url: '{{ route('admin.personas.verificarCorreo') }}',
                    type: 'POST',
                    async: false,
                    data: {
                        _token: CSRF,
                        correo: correo,
                        id: $('#registroDocentePersonaId').val() || ''
                    }
                }).done(function(r) {
                    if (r && r.existe) {
                        setFieldError('registroDocenteCorreo', 'registroDocenteCorreoError',
                            'Este correo ya está registrado.');
                        toast('warning', 'El correo ingresado ya está registrado.');
                        valid = false;
                    }
                });
                setBtnLoading('#btnRegistrarYAsignarDocente', false,
                    '<i class="ri-check-line"></i> Registrar y Asignar');
                if (!valid) return;
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
                    url: '{{ route('admin.posgrads.docentes.registrar') }}',
                    type: 'POST',
                    data: {
                        _token: CSRF,
                        persona_id:       $('#registroDocentePersonaId').val() || null,
                        modulo_id:        $('#editModuloId').val() || null,
                        carnet:           carnet,
                        expedido:         expedido || null,
                        nombres:          nombres,
                        apellido_paterno: apellidoPaterno,
                        apellido_materno: apellidoMaterno,
                        correo:           correo,
                        celular:          celular,
                        telefono:         telefono || null,
                        direccion:        direccion || null,
                        fecha_nacimiento: fechaNacimiento || null,
                        sexo:             sexo || null,
                        estado_civil:     estadoCivil || null,
                        ciudade_id:       ciudadId || null,
                        estudios:         estudios,
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
                    $('#editModuloDocenteCarnet').val('').css('border-color', '');
                    $('#editModuloDocenteCarnetFeedback').html('').css('color', '');
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
                    prePopularEstudiosDocente();
                });
            } else {
                prePopularEstudiosDocente();
            }
        });

        function prePopularEstudiosDocente() {
            if (!docenteTempData || docenteTempData.tipo !== 'persona_encontrada') return;
            const estudios = docenteTempData.estudios || [];
            if (!estudios.length) return;
            $('#estudiosRowsContainer').empty();
            estudioRowCount = 0;
            estudios.forEach(function(est) {
                addEstudioRow();
                const $row = $('#estudiosRowsContainer .estudio-row').last();
                $row.find('.estudio-grado').val(est.grado_academico_id || '');
                $row.find('.estudio-profesion').val(est.profesion_id || '');
                $row.find('.estudio-universidad').val(est.universidad_id || '');
                $row.find('.estudio-estado').val(est.estado || 'En Desarrollo');
                if (est.principal) {
                    $row.find('.estudio-principal').prop('checked', true);
                }
            });
            syncEstudiosEmptyMsg();
            refreshPrincipalVisibility();
        }
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

        function addSesionRow(fecha, inicio, fin, horarioId, estado) {
            sesionRowCount++;
            const idx = sesionRowCount;
            const isDesarrollado = estado === 'Desarrollado';
            const disabledAttr = isDesarrollado ? ' disabled' : '';
            const badgeDesarrollado = isDesarrollado
                ? '<span style="font-size:.63rem;font-weight:600;padding:.1rem .4rem;border-radius:20px;background:rgba(16,185,129,.14);color:#059669;display:inline-flex;align-items:center;gap:.2rem;vertical-align:middle;"><i class=\'ri-checkbox-circle-line\'></i>Desarrollado</span>'
                : '';
            const wrapStyle = isDesarrollado
                ? 'opacity:.75;background:rgba(16,185,129,.04);border:1.5px dashed rgba(16,185,129,.28);border-radius:10px;padding:.65rem .8rem;margin-bottom:.55rem;'
                : 'border:1.5px solid rgba(99,102,241,.14);border-left:3px solid #6366f1;border-radius:10px;padding:.65rem .8rem;margin-bottom:.55rem;';
            const labelStyle = 'font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#64748b;display:block;margin-bottom:.3rem;';
            const html =
                '<div class="sesion-row" data-idx="' + idx + '" data-horario-id="' + (horarioId || '') + '" data-estado="' + escHtml(estado || '') + '" style="' + wrapStyle + '">' +
                    '<div class="row g-2 align-items-end">' +
                        '<div class="col-md-4">' +
                            '<label style="' + labelStyle + '">Sesión #' + idx + ' ' + badgeDesarrollado + '</label>' +
                            '<input type="date" class="form-control form-control-sm sesion-fecha" value="' + (fecha || '') + '"' + disabledAttr + ' style="border-radius:7px;font-size:.83rem;">' +
                        '</div>' +
                        '<div class="col-md-4">' +
                            '<label style="' + labelStyle + '">Hora Inicio</label>' +
                            '<input type="time" class="form-control form-control-sm sesion-inicio" value="' + (inicio || '') + '"' + disabledAttr + ' style="border-radius:7px;font-size:.83rem;">' +
                        '</div>' +
                        '<div class="col-md-4">' +
                            '<label style="' + labelStyle + '">Hora Fin</label>' +
                            '<input type="time" class="form-control form-control-sm sesion-fin" value="' + (fin || '') + '"' + disabledAttr + ' style="border-radius:7px;font-size:.83rem;">' +
                        '</div>' +
                    '</div>' +
                '</div>';
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
        function setBuscarTrabajadorEnabled(enabled) {
            const $btn = $('#btnBuscarTrabajador');
            $btn.prop('disabled', !enabled).css({
                'opacity': enabled ? '' : '0.5',
                'cursor': enabled ? '' : 'not-allowed',
                'pointer-events': enabled ? '' : 'none'
            });
        }
        function validarTrabajadorSearch() {
            const $input = $('#asigTrabajadorSearch');
            const $fb = $('#asigTrabajadorSearchFeedback');
            const raw = ($input.val() || '').trim();
            $input.css('border-color', '');
            if (!raw) {
                $fb.html('').css('color', '');
                setBuscarTrabajadorEnabled(false);
                return { ok: false, empty: true, value: '' };
            }
            // Si es completamente numérico, asumimos búsqueda por carnet → validar 7-11 dígitos
            if (/^\d+$/.test(raw)) {
                if (raw.length < 7) {
                    $fb.html('<i class="ri-error-warning-line"></i> El carnet debe tener entre 7 y 11 dígitos. Faltan ' + (7 - raw.length) + '.').css('color', '#ef4444');
                    $input.css('border-color', '#ef4444');
                    setBuscarTrabajadorEnabled(false);
                    return { ok: false, value: raw, isCarnet: true };
                }
                if (raw.length > 11) {
                    $fb.html('<i class="ri-error-warning-line"></i> El carnet no puede tener más de 11 dígitos.').css('color', '#ef4444');
                    $input.css('border-color', '#ef4444');
                    setBuscarTrabajadorEnabled(false);
                    return { ok: false, value: raw, isCarnet: true };
                }
                $fb.html('<i class="ri-checkbox-circle-line"></i> Carnet válido (' + raw.length + ' dígitos).').css('color', '#10b981');
                $input.css('border-color', '#10b981');
                setBuscarTrabajadorEnabled(true);
                return { ok: true, value: raw, isCarnet: true };
            }
            // Si contiene letras es nombre → sin restricción de longitud
            $fb.html('').css('color', '');
            setBuscarTrabajadorEnabled(true);
            return { ok: true, value: raw, isCarnet: false };
        }
        $(document).on('input', '#asigTrabajadorSearch', function() {
            validarTrabajadorSearch();
        });
        $(document).on('shown.bs.modal', '#modalAsignarHorario', function() {
            validarTrabajadorSearch();
        });

        $('#btnBuscarTrabajador').on('click', function() {
            const v = validarTrabajadorSearch();
            if (v.empty) {
                toast('warning', 'Ingrese un nombre o carnet.');
                $('#asigTrabajadorSearch').focus();
                return;
            }
            if (!v.ok) {
                toast('warning', 'El carnet debe tener entre 7 y 11 dígitos.');
                $('#asigTrabajadorSearch').focus();
                return;
            }
            const q = v.value.toLowerCase();

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
            // Mostrar período del módulo como referencia para las fechas de sesiones
            const fmtFecha = function(d) {
                if (!d) return '—';
                const p = String(d).substring(0, 10).split('-');
                const meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
                return parseInt(p[2]) + ' ' + meses[parseInt(p[1]) - 1] + '. ' + p[0];
            };
            if (mod.fecha_inicio || mod.fecha_fin) {
                const fi = fmtFecha(mod.fecha_inicio), ff = fmtFecha(mod.fecha_fin);
                $('#asigHorarioFechaInicio').text(fi);
                $('#asigHorarioFechaFin').text(ff);
                $('#asigHorarioPeriodoWrap').css('display', 'flex');
                $('#asigBannerFechaInicio').text(fi);
                $('#asigBannerFechaFin').text(ff);
                $('#asigHorarioBannerPeriodo').show();
            } else {
                $('#asigHorarioPeriodoWrap').hide();
                $('#asigHorarioBannerPeriodo').hide();
            }
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
            $('#asigTrabajadorSearch').css('border-color', '');
            $('#asigTrabajadorSearchFeedback').html('').css('color', '');
            for (let i = 0; i < window.CANTIDAD_SESIONES; i++) {
                const h = horarios[i];
                const horarioId = h ? h.id : null;
                const fechaVal = h ? (h.fecha ? String(h.fecha).substring(0, 10) : '') : '';
                const inicioVal = (h && h.hora_inicio) ? h.hora_inicio.substring(0, 5) : '19:00';
                const finVal = (h && h.hora_fin) ? h.hora_fin.substring(0, 5) : '22:00';
                const estadoVal = h ? (h.estado || 'Confirmado') : '';
                addSesionRow(fechaVal, inicioVal, finVal, horarioId, estadoVal);
            }
            openModal('modalAsignarHorario');
        }
        $(document).on('click', '.btn-asignar-horario', function(e) {
            e.stopPropagation();
            openAsignarHorario($(this).data('id'));
        });

        // ===== ENLACE DE VIDEOLLAMADA =====
        let _enlaceVidCuentas = [];

        $(document).on('click', '.btn-enlace-videollamada', function(e) {
            e.stopPropagation();
            const $btn = $(this);
            abrirModalEnlaceVideollamada(
                $btn.data('id'),
                $btn.data('nombre'),
                $btn.data('enlace-id')   || '',
                $btn.data('enlace-url')  || '',
                $btn.data('enlace-nombre') || ''
            );
        });

        function abrirModalEnlaceVideollamada(moduloId, moduloNombre, enlaceId, enlaceUrl, enlaceNombre) {
            const esEdicion = !!enlaceId;
            $('#enlaceVidModuloId').val(moduloId);
            $('#enlaceVidEnlaceId').val(enlaceId || '');
            $('#enlaceVidModuloNombre').text(moduloNombre || '').attr('title', moduloNombre || '');
            $('#enlaceVidTitulo').text(esEdicion ? 'Modificar Enlace Virtual' : 'Enlace de Sesión Virtual');
            $('#enlaceVidBtnText').text(esEdicion ? 'Guardar Cambios' : 'Registrar Enlace');
            $('#enlaceVidBtnIcon').attr('class', esEdicion ? 'ri-refresh-line' : 'ri-save-line');

            $('#enlaceVidNombre').val(enlaceNombre || '').removeClass('is-invalid');
            $('#enlaceVidUrl').val(enlaceUrl || '').removeClass('is-invalid');
            $('#enlaceVidNombreError').text('');
            $('#enlaceVidUrlError').text('');
            $('#enlaceVidCuentaId').val('').removeClass('is-invalid');
            $('#enlaceVidCuentaPreview').hide();

            openModal('modalEnlaceVideollamada');

            $.ajax({ url: '/admin/cuentas-videollamada/listar' })
                .done(function(r) {
                    _enlaceVidCuentas = (r.data || []).filter(c => c.activo);
                    const $sel = $('#enlaceVidCuentaId');
                    $sel.empty().append('<option value="">— Seleccionar cuenta —</option>');
                    _enlaceVidCuentas.forEach(function(c) {
                        $sel.append('<option value="' + c.id + '">' + escHtml(c.nombre) + ' (' + escHtml(c.plataforma) + ')</option>');
                    });

                    if (_enlaceVidCuentas.length === 1) {
                        const unica = _enlaceVidCuentas[0];
                        $sel.val(unica.id);
                        $('#enlaceVidCuentaWrap').hide();
                        $('#enlaceVidCuentaPreviewText').text(unica.nombre + ' (' + unica.plataforma + ')');
                        $('#enlaceVidCuentaPreview').show();
                    } else {
                        $('#enlaceVidCuentaWrap').show();
                        // Si es edición, intentar pre-seleccionar la cuenta actual
                        if (esEdicion && $sel.find('option').length > 1) {
                            // Se mantiene sin selección para que el usuario confirme
                        }
                        $('#enlaceVidCuentaPreview').hide();
                    }
                })
                .fail(function() {
                    toast('error', 'No se pudo cargar las cuentas de videollamada.');
                });
        }

        $('#btnGuardarEnlaceVid').on('click', function() {
            const moduloId  = $('#enlaceVidModuloId').val();
            const cuentaId  = $('#enlaceVidCuentaId').val();
            const nombre    = $('#enlaceVidNombre').val().trim();
            const enlaceUrl = $('#enlaceVidUrl').val().trim();
            let valid = true;

            $('#enlaceVidNombre, #enlaceVidUrl, #enlaceVidCuentaId').removeClass('is-invalid');

            if (!cuentaId) {
                $('#enlaceVidCuentaId').addClass('is-invalid');
                valid = false;
            }
            if (!nombre) {
                $('#enlaceVidNombre').addClass('is-invalid');
                $('#enlaceVidNombreError').text('El nombre es obligatorio.');
                valid = false;
            }
            if (!enlaceUrl) {
                $('#enlaceVidUrl').addClass('is-invalid');
                $('#enlaceVidUrlError').text('El enlace es obligatorio.');
                valid = false;
            }
            if (!valid) return;

            const $btn = $('#btnGuardarEnlaceVid');
            const origHtml = $btn.html();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Guardando...');

            $.ajax({
                url: '/admin/posgrads/modulos/' + moduloId + '/enlace-videollamada',
                method: 'POST',
                data: {
                    _token:    CSRF,
                    cuenta_id: cuentaId,
                    nombre:    nombre,
                    enlace:    enlaceUrl,
                },
            })
            .done(function(r) {
                if (r.success) {
                    closeModal('modalEnlaceVideollamada');
                    toast('success', r.message || 'Enlace guardado correctamente.');
                    cargarModulosSidebar();
                    refreshCalendar();
                } else {
                    toast('error', r.message || 'Error al guardar el enlace.');
                }
            })
            .fail(function(xhr) {
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    const errs = xhr.responseJSON.errors;
                    if (errs.cuenta_id) { $('#enlaceVidCuentaId').addClass('is-invalid'); }
                    if (errs.nombre)    { $('#enlaceVidNombre').addClass('is-invalid'); $('#enlaceVidNombreError').text(errs.nombre[0]); }
                    if (errs.enlace)    { $('#enlaceVidUrl').addClass('is-invalid'); $('#enlaceVidUrlError').text(errs.enlace[0]); }
                } else {
                    toast('error', 'Error al guardar el enlace.');
                }
            })
            .always(function() {
                $btn.prop('disabled', false).html(origHtml);
            });
        });

        $('#modalEnlaceVideollamada').on('hidden.bs.modal', function() {
            $('#enlaceVidCuentaWrap').show();
            $('#enlaceVidCuentaPreview').hide();
        });
        // ===== FIN ENLACE DE VIDEOLLAMADA =====

        $('#modalAsignarHorario').on('hidden.bs.modal', function() {});
        $('#btnGuardarAsignarHorario').on('click', function() {
            const rows = $('#sesionesRowsContainer .sesion-row'),
                trabajadorId = $('#asigTrabajadorId').val() || null;
            let valid = true;
            rows.each(function() {
                if ($(this).data('estado') === 'Desarrollado') return; // saltear sesiones desarrolladas
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
            const editableRows = rows.filter(function() { return $(this).data('estado') !== 'Desarrollado'; });
            if (editableRows.length === 0) {
                toast('info', 'No hay sesiones pendientes para guardar.');
                return;
            }
            const promises = [];
            editableRows.each(function() {
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

            // Enlace de videollamada del horario
            const enlaceVidUrl    = props.enlace_videollamada_url    || '';
            const enlaceVidNombre = props.enlace_videollamada_nombre || '';
            if (enlaceVidUrl) {
                const urlExterna = /^https?:\/\//i.test(enlaceVidUrl) ? enlaceVidUrl : 'https://' + enlaceVidUrl;
                $('#detHorarioEnlaceNombre').text(enlaceVidNombre || 'Enlace de sesión');
                $('#detHorarioEnlaceUrl').off('click.enlace').on('click.enlace', function() {
                    window.open(urlExterna, '_blank', 'noopener,noreferrer');
                });
            }

            // Información de reprogramación
            $('#detHorarioReprogramadoInfo').hide();
            if (props.reprogramado_a_fecha) {
                $('#detHorarioReprogramadoMsg').html('<i class="ri-arrow-right-line me-1"></i> Esta sesión fue postergada al <strong>' + props.reprogramado_a_fecha + '</strong>');
                $('#detHorarioReprogramadoInfo').removeClass('alert-success').addClass('alert-info').show();
            } else if (props.reprogramado_de_fecha) {
                $('#detHorarioReprogramadoMsg').html('<i class="ri-history-line me-1"></i> Sesión reprogramada de la fecha <strong>' + props.reprogramado_de_fecha + '</strong>');
                $('#detHorarioReprogramadoInfo').removeClass('alert-info').addClass('alert-success').show();
            }

            // Grabación de la sesión
            const grabUrl = props.enlace_grabacion || '';
            actualizarVistaGrabacion(grabUrl);

            // Visibilidad de botones según estado
            if (props.estado === 'Postergado') {
                $('#btnCambiarEstadoHorario').hide();
            } else {
                $('#btnCambiarEstadoHorario').show();
            }

            // Sesión virtual: visible solo en Confirmado (con enlace)
            // Grabación: visible solo en Desarrollado
            // Postergado: ocultar ambos (la sesión ya no se realizará en esa fecha)
            if (props.estado === 'Desarrollado') {
                $('#detHorarioEnlaceWrap').hide();
                $('#detHorarioGrabacionWrap').show();
            } else if (props.estado === 'Postergado') {
                $('#detHorarioEnlaceWrap').hide();
                $('#detHorarioGrabacionWrap').hide();
            } else {
                // Confirmado
                $('#detHorarioEnlaceWrap').toggle(!!enlaceVidUrl);
                $('#detHorarioGrabacionWrap').hide();
            }

            openModal('modalDetalleHorario');
        }
        $('#btnCambiarEstadoHorario').on('click', function() {
            closeModal('modalDetalleHorario');
            setTimeout(function() {
                openModal('modalCambiarEstadoHorario');
            }, 300);
        });

        $('#nuevoEstadoHorario').on('change', function() {
            const val = $(this).val();
            if (val === 'Postergado') {
                $('#boxNuevaFechaPostergado').slideDown();
                $('#boxEnlaceGrabacion').slideUp();
                $('#inputEnlaceGrabacion').val('');
            } else if (val === 'Desarrollado') {
                $('#boxNuevaFechaPostergado').slideUp();
                $('#nuevaFechaPostergado').val('');
                $('#boxEnlaceGrabacion').slideDown();
            } else {
                $('#boxNuevaFechaPostergado').slideUp();
                $('#nuevaFechaPostergado').val('');
                $('#boxEnlaceGrabacion').slideUp();
                $('#inputEnlaceGrabacion').val('');
            }
        });

        $('#modalCambiarEstadoHorario').on('show.bs.modal', function() {
            $('#nuevoEstadoHorario').val('Confirmado');
            $('#boxNuevaFechaPostergado').hide();
            $('#nuevaFechaPostergado').val('');
            $('#boxEnlaceGrabacion').hide();
            $('#inputEnlaceGrabacion').val('');
        });

        $('#btnConfirmarCambiarEstado').on('click', function() {
            const nuevoEstado   = $('#nuevoEstadoHorario').val();
            const nuevaFecha    = $('#nuevaFechaPostergado').val();
            const enlaceGrab    = $('#inputEnlaceGrabacion').val().trim();

            if (nuevoEstado === 'Postergado' && !nuevaFecha) {
                toast('warning', 'Debe seleccionar una nueva fecha para postergar.');
                return;
            }

            setBtnLoading('#btnConfirmarCambiarEstado', true, 'Confirmando…');
            $.ajax({
                url: '/admin/posgrads/horarios/' + currentHorarioId + '/estado',
                type: 'PUT',
                data: {
                    _token:           CSRF,
                    estado:           nuevoEstado,
                    nueva_fecha:      nuevaFecha,
                    enlace_grabacion: enlaceGrab,
                }
            }).done(function() {
                toast('success', 'Estado actualizado a: ' + nuevoEstado);
                closeModal('modalCambiarEstadoHorario');
                cargarModulosSidebar();
                refreshCalendar();
            }).fail(function(xhr) {
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error al cambiar estado.';
                toast('error', msg);
            }).always(function() {
                setBtnLoading('#btnConfirmarCambiarEstado', false, 'Confirmar');
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

        // ===== GRABACIÓN DE SESIÓN =====
        let _grabacionUrl = '';

        function actualizarVistaGrabacion(url) {
            _grabacionUrl = url || '';
            $('#detGrabacionForm').hide();
            if (_grabacionUrl) {
                $('#detGrabacionVacia').hide();
                $('#detGrabacionConEnlace').show();
                const urlExt = /^https?:\/\//i.test(_grabacionUrl) ? _grabacionUrl : 'https://' + _grabacionUrl;
                $('#btnAbrirGrabacion').off('click.grab').on('click.grab', function() {
                    window.open(urlExt, '_blank', 'noopener,noreferrer');
                });
                $('#btnEditarGrabacionText').text('Editar enlace');
            } else {
                $('#detGrabacionVacia').show();
                $('#detGrabacionConEnlace').hide();
                $('#btnEditarGrabacionText').text('Agregar enlace');
            }
        }

        $(document).on('click', '#btnEditarGrabacion', function() {
            $('#detGrabacionInput').val(_grabacionUrl);
            $('#detGrabacionForm').slideDown(150);
            $(this).hide();
        });

        $(document).on('click', '#btnCancelarGrabacion', function() {
            $('#detGrabacionForm').slideUp(150);
            $('#btnEditarGrabacion').show();
        });

        $(document).on('click', '#btnGuardarGrabacion', function() {
            const nuevoEnlace = $('#detGrabacionInput').val().trim();
            const $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: '/admin/posgrads/horarios/' + currentHorarioId + '/grabacion',
                type: 'PUT',
                data: { _token: CSRF, enlace_grabacion: nuevoEnlace }
            }).done(function(r) {
                if (r.success) {
                    toast('success', r.message || 'Enlace de grabación actualizado.');
                    actualizarVistaGrabacion(nuevoEnlace);
                    $('#detGrabacionForm').slideUp(150);
                    $('#btnEditarGrabacion').show();
                } else {
                    toast('error', r.message || 'Error al guardar.');
                }
            }).fail(function() {
                toast('error', 'Error al guardar el enlace de grabación.');
            }).always(function() {
                $btn.prop('disabled', false).html('<i class="ri-save-line"></i>');
            });
        });
        // ===== FIN GRABACIÓN =====

        // ===== CAMBIAR ESTADO DE MÓDULO =====
        let currentModuloEstadoId = null;

        const ESTADO_MODULO_STYLES = {
            'No Inició':     { bg: 'rgba(100,116,139,.14)', color: '#475569', icon: 'ri-time-line' },
            'En Desarrollo': { bg: 'rgba(34,197,94,.14)',   color: '#16a34a', icon: 'ri-loader-3-line' },
            'Concluido':     { bg: 'rgba(99,102,241,.14)',  color: '#4f46e5', icon: 'ri-checkbox-circle-line' }
        };
        let estadoActualModulo = '';

        function pintarBadgeEstadoActualModulo(estado) {
            const st = ESTADO_MODULO_STYLES[estado] || ESTADO_MODULO_STYLES['No Inició'];
            $('#cambiarEstadoModuloActualBadge').css({ background: st.bg, color: st.color });
            $('#cambiarEstadoModuloActualIcon').attr('class', st.icon);
            $('#cambiarEstadoModuloActualText').text(estado || 'No Inició');
        }

        function actualizarHintCambiarEstado() {
            const seleccionado = $('#nuevoEstadoModulo').val();
            $('#cambiarEstadoModuloHint').css('display', seleccionado === estadoActualModulo ? 'flex' : 'none');
        }

        $(document).on('click', '.btn-cambiar-estado-modulo', function(e) {
            e.stopPropagation();
            currentModuloEstadoId = $(this).data('id');
            const estadoActual = $(this).data('estado') || 'No Inició';
            const nombre = $(this).data('nombre');
            estadoActualModulo = estadoActual;
            $('#cambiarEstadoModuloNombre').text(nombre);
            pintarBadgeEstadoActualModulo(estadoActual);
            // Preseleccionar el estado actual como "Nuevo Estado" por defecto
            $('#nuevoEstadoModulo').val(estadoActual);
            if (!$('#nuevoEstadoModulo').val()) $('#nuevoEstadoModulo').val('No Inició');
            actualizarHintCambiarEstado();
            openModal('modalCambiarEstadoModulo');
        });

        $(document).on('change', '#nuevoEstadoModulo', actualizarHintCambiarEstado);

        $('#btnConfirmarCambiarEstadoModulo').on('click', function() {
            const nuevoEstado = $('#nuevoEstadoModulo').val();
            if (nuevoEstado === estadoActualModulo) {
                toast('warning', 'El estado seleccionado es igual al actual. Elige uno diferente para actualizar.');
                return;
            }
            setBtnLoading('#btnConfirmarCambiarEstadoModulo', true, 'Confirmando…');
            $.ajax({
                url: '/admin/posgrads/modulos/' + currentModuloEstadoId + '/estado',
                type: 'PUT',
                data: { _token: CSRF, estado: nuevoEstado }
            }).done(function() {
                toast('success', 'Estado del módulo actualizado a: ' + nuevoEstado);
                closeModal('modalCambiarEstadoModulo');
                cargarModulosSidebar();
                refreshCalendar();
            }).fail(function(xhr) {
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error al cambiar estado.';
                toast('error', msg);
            }).always(function() {
                setBtnLoading('#btnConfirmarCambiarEstadoModulo', false, '<i class="ri-check-line me-1"></i>Confirmar');
            });
        });
        // ===== FIN CAMBIAR ESTADO DE MÓDULO =====

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
            $.getJSON('{{ route('admin.posgrads.ofertas.planes-conceptos.listar', $oferta->id) }}').done(function(
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
            const _esPromoConcepto = row.plan_pago?.es_promocion || false;
            $('#descuentoBsEditarWrap').toggle(_esPromoConcepto);
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
                $('#editPlanFechaInicio').val(fechaInicio || '');
                $('#editPlanFechaFin').val(fechaFin || '');
                $('.col-edit-descuento').show();
            } else {
                $('#editBadgePromocion').hide();
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

        function getConceptosSeleccionados(excluirFilaIdx) {
            const seleccionados = new Set();
            $('#filasConceptosBody .fila-concepto').each(function() {
                const filaIdx = $(this).data('fila-idx');
                if (excluirFilaIdx !== undefined && filaIdx == excluirFilaIdx) return;
                const val = $(this).find('.select-concepto').val();
                if (val) seleccionados.add(String(val));
            });
            return seleccionados;
        }

        function actualizarSelectsConceptos() {
            $('#filasConceptosBody .fila-concepto').each(function() {
                const $fila = $(this);
                const filaIdx = $fila.data('fila-idx');
                const $select = $fila.find('.select-concepto');
                const valorActual = $select.val();
                const usados = getConceptosSeleccionados(filaIdx);
                let opts = '<option value="">— Seleccionar —</option>';
                conceptosData.forEach(function(c) {
                    const cId = String(c.id);
                    if (usados.has(cId)) return;
                    const selected = cId == valorActual ? 'selected' : '';
                    opts += '<option value="' + c.id + '" ' + selected + '>' + escHtml(c.nombre) + '</option>';
                });
                $select.html(opts);
            });
        }

        function renderFilaConcepto(conceptoId, nCuotas, precioRegular, descuentoBs) {
            filaConceptoCount++;
            const idx = filaConceptoCount;
            const esPromocion = $('#planesPagoCrear').find('option:selected').data('promocion') == '1';
            const usados = getConceptosSeleccionados();
            let conceptosOpts = '<option value="">— Seleccionar —</option>';
            conceptosData.forEach(function(c) {
                const cId = String(c.id);
                if (usados.has(cId) && cId != String(conceptoId)) return;
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
                '{{ route('admin.posgrads.ofertas.planes-conceptos.precio-base', ['ofertaId' => $oferta->id, 'conceptoId' => '__ID__']) }}'
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
            $.when($.getJSON('{{ route('admin.posgrads.ofertas.planes-pago.disponibles', $oferta->id) }}'),
                    $.getJSON('{{ route('admin.posgrads.ofertas.conceptos.disponibles', $oferta->id) }}'), $
                    .getJSON(
                        '{{ route('admin.posgrads.ofertas.planes-conceptos.verificar-principal', $oferta->id) }}'
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
            actualizarSelectsConceptos();
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
            actualizarSelectsConceptos();
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
                    url: '{{ route('admin.posgrads.ofertas.planes-conceptos.multiple', $oferta->id) }}',
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

            const MESES_OPTS_C = [
                'Enero','Febrero','Marzo','Abril','Mayo','Junio',
                'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
            ].map(function(m, mi) {
                return '<option value="' + (mi + 1) + '">' + m + '</option>';
            }).join('');

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

                // ── Controles de fecha por concepto ──────────────────
                html += '<div class="mb-2 p-2 rounded d-flex align-items-center gap-3 flex-wrap" style="background:#f0fdf4;border:1px solid #bbf7d0;">';
                html += '<div class="d-flex align-items-center gap-2">';
                html += '<label style="font-size:0.74rem;font-weight:600;color:#15803d;white-space:nowrap;margin-bottom:0;">Día de pago:</label>';
                html += '<input type="number" class="form-control form-control-sm" id="cambiar-dia-venc-' + idx + '" min="1" max="31" placeholder="1–31" style="width:72px;font-size:0.8rem;">';
                html += '<button type="button" class="btn btn-sm btn-aplicar-dia-cambio" data-concepto-idx="' + idx + '" style="background:#15803d;color:#fff;font-size:0.73rem;padding:0.22rem 0.65rem;">';
                html += '<i class="ri-calendar-check-line"></i> Aplicar</button>';
                html += '</div>';
                html += '<div style="width:1px;height:26px;background:#86efac;"></div>';
                html += '<div class="d-flex align-items-center gap-2">';
                html += '<label style="font-size:0.74rem;font-weight:600;color:#15803d;white-space:nowrap;margin-bottom:0;">Mes inicio:</label>';
                html += '<select class="form-select form-select-sm" id="cambiar-mes-inicio-' + idx + '" style="width:135px;font-size:0.78rem;"><option value="">— Mes —</option>' + MESES_OPTS_C + '</select>';
                html += '<button type="button" class="btn btn-sm btn-aplicar-mes-cambio" data-concepto-idx="' + idx + '" data-n-cuotas="' + item.n_cuotas + '" style="background:#15803d;color:#fff;font-size:0.73rem;padding:0.22rem 0.65rem;">';
                html += '<i class="ri-calendar-line"></i> Aplicar</button>';
                html += '</div>';
                html += '</div>';
                // ─────────────────────────────────────────────────────

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

            // ── Helper: ajusta el día al máximo válido del mes ───────
            function clampDay(year, month, day) {
                return Math.min(day, new Date(year, month, 0).getDate());
            }

            // ── Aplicar día a todas las fechas del concepto ──────────
            $('.btn-aplicar-dia-cambio').on('click', function() {
                const conceptoIdx = $(this).data('concepto-idx');
                const dia = parseInt($('#cambiar-dia-venc-' + conceptoIdx).val());
                if (!dia || dia < 1 || dia > 31) {
                    toast('warning', 'Ingrese un día válido entre 1 y 31.');
                    return;
                }
                let aplicados = 0;
                $('.cuota-fecha-cambio[data-concepto-idx="' + conceptoIdx + '"]').each(function() {
                    const val = $(this).val();
                    if (val) {
                        const parts = val.split('-');
                        if (parts.length === 3) {
                            const year  = parseInt(parts[0]);
                            const month = parseInt(parts[1]);
                            const diaReal = clampDay(year, month, dia);
                            parts[2] = String(diaReal).padStart(2, '0');
                            $(this).val(parts.join('-'));
                            aplicados++;
                        }
                    }
                });
                if (aplicados > 0) {
                    toast('success', 'Día ' + dia + ' aplicado a ' + aplicados + ' fecha' + (aplicados !== 1 ? 's' : '') + '.');
                } else {
                    toast('warning', 'No hay fechas con valores para modificar.');
                }
            });

            // ── Aplicar mes inicio al concepto ───────────────────────
            $('.btn-aplicar-mes-cambio').on('click', function() {
                const conceptoIdx = $(this).data('concepto-idx');
                const nCuotas    = parseInt($(this).data('n-cuotas')) || 1;
                const mesInicio  = parseInt($('#cambiar-mes-inicio-' + conceptoIdx).val());
                if (!mesInicio) {
                    toast('warning', 'Seleccione un mes de inicio.');
                    return;
                }
                const $fechas = $('.cuota-fecha-cambio[data-concepto-idx="' + conceptoIdx + '"]');

                let dia = 1;
                const diaInput = parseInt($('#cambiar-dia-venc-' + conceptoIdx).val());
                if (diaInput && diaInput >= 1 && diaInput <= 31) {
                    dia = diaInput;
                } else {
                    const primera = $fechas.first().val();
                    if (primera) {
                        const p = primera.split('-');
                        if (p.length === 3) dia = parseInt(p[2]) || 1;
                    }
                }

                let anioBase = new Date().getFullYear();
                $fechas.each(function() {
                    const v = $(this).val();
                    if (v) {
                        const p = v.split('-');
                        if (p.length === 3) { anioBase = parseInt(p[0]); return false; }
                    }
                });

                $fechas.each(function(cuotaIdx) {
                    let mes  = mesInicio + cuotaIdx;
                    let anio = anioBase + Math.floor((mes - 1) / 12);
                    mes = ((mes - 1) % 12) + 1;
                    const diaReal = clampDay(anio, mes, dia);
                    $(this).val(anio + '-' + String(mes).padStart(2, '0') + '-' + String(diaReal).padStart(2, '0'));
                });

                const nombresMeses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                                      'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                toast('success', 'Fechas redistribuidas desde ' + nombresMeses[mesInicio - 1] +
                    ' (' + nCuotas + ' cuota' + (nCuotas !== 1 ? 's' : '') + ').');
            });
            // ─────────────────────────────────────────────────────────

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

        // ===== FINANZAS CHARTS (3 tabs: Completo / IngresosReales / Retirados) =====
        (function() {
            'use strict';

            var resumenes = {
                'Completo':       @json($resumenPorConcepto ?? []),
                'IngresosReales': @json($resumenPorConceptoIngresosReales ?? []),
                'Retirados':      @json($resumenPorConceptoRetirados ?? []),
            };

            var colores = { 'Matrícula': '#6366f1', 'Colegiatura': '#0891b2', 'Certificación': '#d97706' };
            var rendered = {};

            function renderSection(suffix) {
                if (rendered[suffix]) return;
                var resumen = resumenes[suffix];
                if (!resumen || Object.keys(resumen).length === 0) return;

                var conceptos = Object.keys(resumen).filter(function(c) { return (resumen[c].total || 0) > 0; });
                if (conceptos.length === 0) { rendered[suffix] = true; return; }

                var totalesData = conceptos.map(function(c) { return resumen[c].total  || 0; });
                var pagadoData  = conceptos.map(function(c) { return resumen[c].pagado || 0; });
                var bgColors    = conceptos.map(function(c) { return colores[c] || '#64748b'; });
                var totalProg   = totalesData.reduce(function(a, b) { return a + b; }, 0);
                var totalPag    = pagadoData.reduce(function(a, b) { return a + b; }, 0);
                var totalPend   = totalProg - totalPag;

                // 1. Donut distribución por concepto
                var ctx1 = document.getElementById('finChartConceptos' + suffix);
                if (ctx1) {
                    new Chart(ctx1.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: conceptos,
                            datasets: [{
                                data: totalesData,
                                backgroundColor: bgColors,
                                hoverBackgroundColor: bgColors.map(function(c) { return c + 'dd'; }),
                                borderWidth: 3,
                                borderColor: '#ffffff',
                                hoverOffset: 12,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '68%',
                            animation: { animateRotate: true, duration: 900 },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) {
                                            var sum = totalesData.reduce(function(a, b) { return a + b; }, 0);
                                            var pct = sum > 0 ? (ctx.parsed / sum * 100).toFixed(1) : 0;
                                            return '  Bs. ' + ctx.parsed.toLocaleString('es-BO') + '  (' + pct + '%)';
                                        }
                                    }
                                }
                            }
                        }
                    });

                    var legendEl = document.getElementById('finLegendConceptos' + suffix);
                    if (legendEl) {
                        legendEl.innerHTML = '';
                        conceptos.forEach(function(label, i) {
                            var distPct = totalProg > 0 ? (totalesData[i] / totalProg * 100).toFixed(1) : '0.0';
                            var row = document.createElement('div');
                            row.className = 'fin-legend-row';
                            row.innerHTML =
                                '<span class="fin-legend-swatch" style="background:' + bgColors[i] + ';"></span>' +
                                '<span class="fin-legend-name">' + label + '</span>' +
                                '<span class="fin-legend-amount">Bs. ' + totalesData[i].toLocaleString('es-BO') + '</span>' +
                                '<span class="fin-legend-pct" style="background:' + bgColors[i] + '22;color:' + bgColors[i] + ';">' + distPct + '%</span>';
                            legendEl.appendChild(row);
                        });
                    }
                }

                // 3. Donut estado de pagos
                var ctx3 = document.getElementById('finChartEstado' + suffix);
                if (ctx3) {
                    var pendColor = (suffix === 'Retirados') ? '#991b1b' : '#f1f5f9';
                    var pendHover = (suffix === 'Retirados') ? '#7f1d1d' : '#e2e8f0';
                    new Chart(ctx3.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: (suffix === 'Retirados') ? ['Cobrado', 'Perdido'] : ['Cobrado', 'Pendiente'],
                            datasets: [{
                                data: [totalPag, totalPend],
                                backgroundColor: ['#059669', pendColor],
                                hoverBackgroundColor: ['#047857', pendHover],
                                borderWidth: 3,
                                borderColor: '#ffffff',
                                hoverOffset: 10,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '72%',
                            animation: { animateRotate: true, duration: 1000 },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) { return '  Bs. ' + ctx.parsed.toLocaleString('es-BO'); }
                                    }
                                }
                            }
                        }
                    });
                }

                // ── 4. Distribución de Planes de Pago + Sexo ──
                var distEl = document.getElementById('finDistData' + suffix);
                if (distEl) {
                    try {
                        var distData = JSON.parse(distEl.textContent || distEl.innerText || '{}');
                        renderDistCharts(suffix, distData);
                    } catch (e) { /* noop */ }
                }

                rendered[suffix] = true;
            }

            // Render para "Planes de pago" (barras horizontales) y "Sexo" (donut)
            var paletaPlanes = ['#8b5cf6','#06b6d4','#22c55e','#f59e0b','#ef4444','#0ea5e9','#a855f7','#10b981','#f97316'];
            function renderDistCharts(suffix, dist) {
                var planes = dist.planes || {};
                var labelsP = Object.keys(planes);
                var dataP   = labelsP.map(function(k) { return Number(planes[k]) || 0; });

                var ctxP = document.getElementById('finChartPlanes' + suffix);
                if (ctxP && labelsP.length) {
                    var bgP = labelsP.map(function(_, i) { return paletaPlanes[i % paletaPlanes.length]; });
                    new Chart(ctxP, {
                        type: 'bar',
                        data: {
                            labels: labelsP,
                            datasets: [{
                                label: 'Estudiantes',
                                data: dataP,
                                backgroundColor: bgP,
                                hoverBackgroundColor: bgP.map(function(c) { return c + 'cc'; }),
                                borderRadius: 8,
                                borderSkipped: false,
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: { label: function(ctx) { return '  ' + ctx.parsed.x + ' estudiante(s)'; } }
                                }
                            },
                            scales: {
                                x: { beginAtZero: true, ticks: { precision: 0, color: '#64748b' }, grid: { color: 'rgba(15,23,42,.04)' } },
                                y: { ticks: { color: '#1e293b', font: { weight: '600' } }, grid: { display: false } }
                            }
                        }
                    });

                    var legend = document.getElementById('finPlanesLegend' + suffix);
                    if (legend) {
                        legend.innerHTML = '';
                        var totalP = dataP.reduce(function(a, b) { return a + b; }, 0);
                        labelsP.forEach(function(lbl, i) {
                            var pct = totalP > 0 ? ((dataP[i] / totalP) * 100).toFixed(1) : 0;
                            var row = document.createElement('span');
                            row.className = 'fin-dist-legend-row';
                            row.innerHTML =
                                '<span class="swatch" style="background:' + bgP[i] + ';"></span>' +
                                lbl + ' · <strong>' + dataP[i] + '</strong> <span style="color:#94a3b8;">(' + pct + '%)</span>';
                            legend.appendChild(row);
                        });
                    }
                }

                var sexo = dist.sexo || {};
                var labelsS = Object.keys(sexo);
                var dataS   = labelsS.map(function(k) { return Number(sexo[k]) || 0; });
                var ctxS = document.getElementById('finChartSexo' + suffix);
                if (ctxS && labelsS.length) {
                    var colorMapSexo = { 'M': '#3b82f6', 'F': '#ec4899', 'Otro': '#94a3b8' };
                    var bgS = labelsS.map(function(k) { return colorMapSexo[k] || '#94a3b8'; });
                    var labelTxt = labelsS.map(function(k) { return k === 'M' ? 'Masculino' : (k === 'F' ? 'Femenino' : 'Otro'); });
                    new Chart(ctxS, {
                        type: 'doughnut',
                        data: {
                            labels: labelTxt,
                            datasets: [{
                                data: dataS,
                                backgroundColor: bgS,
                                hoverBackgroundColor: bgS.map(function(c) { return c + 'dd'; }),
                                borderWidth: 3, borderColor: '#fff', hoverOffset: 10,
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false, cutout: '68%',
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) {
                                            var total = dataS.reduce(function(a, b) { return a + b; }, 0);
                                            var pct = total > 0 ? (ctx.parsed / total * 100).toFixed(1) : 0;
                                            return '  ' + ctx.parsed + ' (' + pct + '%)';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }

            // Render the active tab immediately, lazy-render others when shown
            renderSection('Completo');

            ['fin-tab-completo', 'fin-tab-ingresos', 'fin-tab-retirados'].forEach(function(id) {
                var btn = document.getElementById(id);
                if (!btn) return;
                btn.addEventListener('shown.bs.tab', function() {
                    var target = btn.getAttribute('data-bs-target') || '';
                    if (target.indexOf('ingresos') !== -1) renderSection('IngresosReales');
                    else if (target.indexOf('retirados') !== -1) renderSection('Retirados');
                    else renderSection('Completo');
                });
            });
        })();
    })();

    // =========================================================================
    // MOODLE — Matrícula de estudiantes
    // =========================================================================
    (function () {
        'use strict';
        const CSRF = '{{ csrf_token() }}';
        let moduloActual = { id: null, nombre: '', programa: '' };
        let estudiantesData = [];
        let whatsappWindowCounter = 0;

        // Abrir modal al hacer clic en el botón de matrícula
        $(document).on('click', '.btn-matricular-moodle', function (e) {
            e.stopPropagation();
            moduloActual.id     = $(this).data('id');
            moduloActual.nombre = $(this).data('nombre');
            moduloActual.estado = $(this).data('estado') || '';
            abrirModalMatricula();
        });

        function abrirModalMatricula() {
            document.getElementById('moodleModuloNombre').textContent = moduloActual.nombre;

            // Mostrar u ocultar la pestaña "Info del Módulo" según el estado
            const enDesarrollo = moduloActual.estado === 'En Desarrollo';
            const tabInfoBtn = document.getElementById('tabBtnInfoModulo');
            if (tabInfoBtn) tabInfoBtn.style.display = enDesarrollo ? '' : 'none';

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
                        cursoBadge.setAttribute('title', r.moodle_course_nombre);
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
            const btnEnviar = document.getElementById('btnEnviarCredencialesMoodle');

            if (!estudiantesData.length) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:2.5rem 1rem;color:#94a3b8;"><i class="ri-inbox-line" style="font-size:1.8rem;display:block;margin-bottom:.5rem;"></i>No hay estudiantes inscritos en esta oferta.</td></tr>';
                if (btnEnviar) { btnEnviar.disabled = true; btnEnviar.style.opacity = '.5'; }
                return;
            }

            function renderFila(e) {
                const tieneCuenta = e.tiene_cuenta ?? e.en_moodle;
                const estadoBadge = tieneCuenta
                    ? '<span style="display:inline-flex;align-items:center;gap:.25rem;padding:.18rem .6rem;border-radius:20px;font-size:.7rem;font-weight:600;background:rgba(16,185,129,.1);color:#065f46;"><i class="ri-checkbox-circle-line"></i>Activa</span>'
                    : '<span style="display:inline-flex;align-items:center;gap:.25rem;padding:.18rem .6rem;border-radius:20px;font-size:.7rem;font-weight:600;background:rgba(245,158,11,.1);color:#92400e;"><i class="ri-user-add-line"></i>Sin cuenta</span>';

                const celularLimpio = (e.celular || '').replace(/[^0-9]/g, '');
                const tieneCelular = celularLimpio.length >= 8;
                const btnWa = tieneCelular
                    ? `<button type="button" class="btn-wa-individual"
                            data-celular="${celularLimpio}"
                            data-nombre="${escHtml(e.nombre)}"
                            data-username="${escHtml(e.username)}"
                            data-password="${escHtml(e.password || e.carnet)}"
                            title="Enviar por WhatsApp"
                            style="width:30px;height:30px;border-radius:7px;border:none;background:rgba(37,211,102,.12);color:#25d366;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:background .15s;font-size:.95rem;">
                            <i class="ri-whatsapp-line"></i>
                        </button>`
                    : `<span title="Sin número de celular"
                            style="width:30px;height:30px;display:inline-flex;align-items:center;justify-content:center;color:#cbd5e1;font-size:.85rem;">
                            <i class="ri-phone-off-line"></i>
                        </span>`;

                return `<tr style="border-bottom:1px solid #f1f5f9;transition:background .1s;">
                    <td style="padding:.55rem .75rem;text-align:center;">
                        <input type="checkbox" class="chk-estudiante" checked
                            data-celular="${escHtml(e.celular || '')}"
                            data-nombre="${escHtml(e.nombre)}"
                            data-username="${escHtml(e.username)}"
                            data-password="${escHtml(e.password || e.carnet)}"
                            style="width:15px;height:15px;accent-color:#9a4904;cursor:pointer;border-radius:3px;">
                    </td>
                    <td style="padding:.55rem .75rem;">
                        <div style="font-weight:600;font-size:.83rem;color:#1e293b;">${escHtml(e.nombre)}</div>
                        <div style="font-size:.71rem;color:#94a3b8;margin-top:.1rem;">${escHtml(e.carnet)}</div>
                        <div style="margin-top:.2rem;">${estadoBadge}</div>
                    </td>
                    <td style="padding:.55rem .75rem;">
                        <code style="font-size:.76rem;background:rgba(252,123,4,.07);color:#9a4904;padding:.15rem .5rem;border-radius:5px;border:1px solid rgba(252,123,4,.12);">${escHtml(e.username)}</code>
                    </td>
                    <td style="padding:.55rem .75rem;">
                        <code style="font-size:.76rem;background:rgba(154,73,4,.07);color:#9a4904;padding:.15rem .5rem;border-radius:5px;border:1px solid rgba(154,73,4,.12);">${escHtml(e.password || e.carnet)}</code>
                    </td>
                    <td style="padding:.55rem .75rem;text-align:center;">${btnWa}</td>
                </tr>`;
            }

            tbody.innerHTML = estudiantesData.map(renderFila).join('');

            // WhatsApp individual
            tbody.querySelectorAll('.btn-wa-individual').forEach(function (btn) {
                btn.addEventListener('mouseenter', function () { this.style.background = 'rgba(37,211,102,.22)'; });
                btn.addEventListener('mouseleave', function () { this.style.background = 'rgba(37,211,102,.12)'; });
                btn.addEventListener('click', function () {
                    const msg = buildMensajeWhatsApp(this.dataset.nombre, this.dataset.username, this.dataset.password);
                    window.open('https://wa.me/' + this.dataset.celular + '?text=' + encodeURIComponent(msg), '_blank');
                });
            });

            actualizarContadorSeleccion();
            tbody.addEventListener('change', function (ev) {
                if (ev.target.classList.contains('chk-estudiante')) actualizarContadorSeleccion();
            });
        }

        function buildSubtitle() {
            const v = window.OFERTA_VERSION;
            const g = window.OFERTA_GRUPO;
            const gestion = window.OFERTA_GESTION;
            const posgrado = window.OFERTA_POSGRADO;
            const parts = [];
            if (posgrado) parts.push(posgrado);
            if (gestion) parts.push(gestion);
            if (v || g) parts.push('V' + (v || '?') + ' G' + (g || '?'));
            return parts.length ? '_' + parts.join(' - ') + '_' : '';
        }

        function buildMensajeWhatsApp(nombre, username, password) {
            const displayUrl = 'https://posgradosinnovaciencia.com';
            const programa   = moduloActual.programa || window.PROGRAMA_NOMBRE || 'Programa';
            const subtitulo  = buildSubtitle();

            return [
                '\uD83C\uDF93 *INNOVA CIENCIA VIRTUAL*',
                subtitulo ? subtitulo + '\n' : '',
                '\u2728 \u00A1Bienvenido/a a tu plataforma acad\u00E9mica!',
                '',
                '\uD83D\uDC4B Estimado/a *' + nombre + '*,',
                'Te damos la bienvenida al *' + programa + '*.',
                'A continuaci\u00F3n encontrar\u00E1s tus credenciales de acceso a la plataforma virtual.',
                '',
                '\uD83D\uDD10 *ACCESO A LA PLATAFORMA*',
                '\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC',
                '\uD83C\uDF10  *Sitio web:*  ' + displayUrl,
                '\uD83D\uDC64  *Usuario:*      `' + username + '`',
                '\uD83D\uDD11  *Contrase\u00F1a:*  `' + password + '`',
                '\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC',
                '',
                '\uD83D\uDCCC *PASOS PARA INGRESAR*',
                '1\uFE0F\u20E3 Abre tu navegador (Chrome, Edge o Firefox)',
                '2\uFE0F\u20E3 Visita \u2192 ' + displayUrl,
                '3\uFE0F\u20E3 Ingresa tu usuario y contrase\u00F1a',
                '4\uFE0F\u20E3 Completa tu perfil en el primer acceso',
                '',
                '\u26A0\uFE0F *IMPORTANTE*',
                '\u2022 Guarda tus credenciales en un lugar seguro.',
                '\u2022 No compartas tu contrase\u00F1a con nadie.',
                '\u2022 Si olvidas tu acceso, cont\u00E1ctanos de inmediato.',
                '',
                '\uD83D\uDE80 *\u00A1\u00C9xitos en tu proceso de formaci\u00F3n!*',
                '',
                '\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500',
                '\uD83C\uDFE2 *\u00C1rea Acad\u00E9mica*',
                '\uD83D\uDCCD Innova Ciencia Virtual',
                '\uD83D\uDCE7 soporte@posgradosinnovaciencia.com',
                '\uD83D\uDCF1 +591 XXX XXX XXX',
            ].join('\n');
        }

        function actualizarContadorSeleccion() {
            const chksChecked = [...document.querySelectorAll('.chk-estudiante:checked')];
            const total = chksChecked.length;
            const conCelular = chksChecked.filter(c => {
                const cel = (c.dataset.celular || '').replace(/[^0-9]/g, '');
                return cel.length >= 8;
            }).length;

            const el = document.getElementById('moodleContadorSeleccion');
            if (el) {
                el.textContent = total + ' seleccionado' + (total !== 1 ? 's' : '') +
                    (conCelular < total ? ' · ' + conCelular + ' con celular' : '');
            }

            const btnEnviar = document.getElementById('btnEnviarCredencialesMoodle');
            if (btnEnviar) {
                btnEnviar.disabled = conCelular === 0;
                btnEnviar.style.opacity = conCelular === 0 ? '.5' : '1';
            }
        }

        function actualizarBotonWhatsApp() {
            // mantener compatibilidad — ahora el estado se gestiona en actualizarContadorSeleccion
        }

        function enviarWhatsAppMasivo() {
            const seleccionados = [];
            document.querySelectorAll('.chk-estudiante:checked').forEach(function (chk) {
                const celular = (chk.dataset.celular || '').replace(/[^0-9]/g, '');
                if (celular.length >= 8) {
                    seleccionados.push({
                        celular:   celular,
                        nombre:    chk.dataset.nombre,
                        username:  chk.dataset.username,
                        password:  chk.dataset.password,
                    });
                }
            });

            if (seleccionados.length === 0) {
                toast('warning', 'Selecciona estudiantes que tengan número de celular registrado.');
                return;
            }

            seleccionados.forEach(function (est, index) {
                const msg = buildMensajeWhatsApp(est.nombre, est.username, est.password);
                setTimeout(function () {
                    window.open('https://wa.me/' + est.celular + '?text=' + encodeURIComponent(msg),
                        'wa_' + (++whatsappWindowCounter) + '_' + Date.now(), 'width=800,height=600');
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

        document.getElementById('btnEnviarCredencialesMoodle')?.addEventListener('click', enviarWhatsAppMasivo);

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

        // ===== PESTAÑA: INFO DEL MÓDULO =====

        // Tab activa por defecto
        let moodleTabActivo = 'credenciales';

        function activarTabMoodle(tab) {
            moodleTabActivo = tab;
            const btnCred   = document.getElementById('tabBtnCredenciales');
            const btnInfo   = document.getElementById('tabBtnInfoModulo');
            const panelCred = document.getElementById('moodleTabCredenciales');
            const panelInfo = document.getElementById('moodleTabInfoModulo');
            const btnEnviarCred = document.getElementById('btnEnviarCredencialesMoodle');
            const btnEnviarInfo = document.getElementById('btnEnviarInfoModulo');

            if (tab === 'credenciales') {
                if (btnCred) { btnCred.style.borderBottomColor = '#9a4904'; btnCred.style.color = '#9a4904'; btnCred.style.fontWeight = '700'; }
                if (btnInfo) { btnInfo.style.borderBottomColor = 'transparent'; btnInfo.style.color = '#94a3b8'; btnInfo.style.fontWeight = '600'; }
                if (panelCred) panelCred.style.display = '';
                if (panelInfo) panelInfo.style.display = 'none';
                if (btnEnviarCred) btnEnviarCred.style.display = 'inline-flex';
                if (btnEnviarInfo) btnEnviarInfo.style.display = 'none';
            } else {
                if (btnCred) { btnCred.style.borderBottomColor = 'transparent'; btnCred.style.color = '#94a3b8'; btnCred.style.fontWeight = '600'; }
                if (btnInfo) { btnInfo.style.borderBottomColor = '#25d366'; btnInfo.style.color = '#059669'; btnInfo.style.fontWeight = '700'; }
                if (panelCred) panelCred.style.display = 'none';
                if (panelInfo) panelInfo.style.display = '';
                if (btnEnviarCred) btnEnviarCred.style.display = 'none';
                if (btnEnviarInfo) btnEnviarInfo.style.display = 'inline-flex';
                renderTablaInfoEstudiantes();
                construirMensajeInfoModulo();
            }
        }

        document.getElementById('tabBtnCredenciales')?.addEventListener('click', function () { activarTabMoodle('credenciales'); });
        document.getElementById('tabBtnInfoModulo')?.addEventListener('click', function () { activarTabMoodle('info'); });

        // Resetear tab al abrir el modal
        document.getElementById('modalMatricularMoodle')?.addEventListener('show.bs.modal', function () {
            activarTabMoodle('credenciales');
            moduloActual._msgInfo = null;
        });

        // Construye el mensaje de información del módulo
        function construirMensajeInfoModulo() {
            const _modulos = window.allModulos || [];
            const mod = _modulos.find(m => String(m.id) === String(moduloActual.id));
            if (!mod) {
                const el = document.getElementById('moodleInfoMsgPreview');
                if (el) el.textContent = 'No se pudo obtener la información del módulo.';
                return;
            }

            const diasSemana = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
            const meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];

            // Docente
            const docente = mod.docente && mod.docente.persona
                ? ((mod.docente.persona.nombres || '') + ' ' + (mod.docente.persona.apellido_paterno || '') + (mod.docente.persona.apellido_materno ? ' ' + mod.docente.persona.apellido_materno : '')).trim()
                : 'Sin docente asignado';

            // Horarios (solo Confirmado y Desarrollado, ordenados por fecha)
            const horarios = (mod.horarios || [])
                .filter(h => h.estado !== 'Postergado')
                .sort((a, b) => (a.fecha || '').localeCompare(b.fecha || ''));

            let sesionesTexto = '';
            horarios.forEach(function (h, idx) {
                const fechaStr = h.fecha ? String(h.fecha).substring(0, 10) : '';
                if (!fechaStr) return;
                const parts = fechaStr.split('-');
                const d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
                const diaNombre = diasSemana[d.getDay()];
                const diaNum    = parseInt(parts[2]);
                const mesNombre = meses[parseInt(parts[1]) - 1];
                const anio      = parts[0];
                const horaInicio = h.hora_inicio ? h.hora_inicio.substring(0, 5) : '';
                const horaFin    = h.hora_fin    ? h.hora_fin.substring(0, 5)    : '';
                const sesNum = String(idx + 1).padStart(2, '0');
                sesionesTexto += '📌 *Sesión ' + sesNum + ':*  ';
                sesionesTexto += diaNombre.charAt(0).toUpperCase() + diaNombre.slice(1) + ' ' + diaNum + ' de ' + mesNombre + ' ' + anio;
                if (horaInicio && horaFin) sesionesTexto += '\n       🕐 ' + horaInicio + ' – ' + horaFin + ' hrs';
                sesionesTexto += '\n';
            });

            // Enlace de videollamada del módulo
            const enlaceNombre = mod.enlace_videollamada_nombre || '';
            const enlaceUrl    = mod.enlace_videollamada_url    || '';
            const programa  = moduloActual.programa || window.PROGRAMA_NOMBRE || 'Innova-Ciencia-Virtual';
            const subtitulo = buildSubtitle();

            const parteEnlace = enlaceUrl
                ? [
                    '',
                    '🔗 *ENLACE DE SESIÓN VIRTUAL*',
                    '▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬',
                    (enlaceNombre ? '📌 ' + enlaceNombre + '\n' : '') + '👉 ' + enlaceUrl,
                    '▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬',
                  ].join('\n')
                : '';

            const msg = [
                '🎓 *INNOVA CIENCIA VIRTUAL*',
                subtitulo ? subtitulo + '\n' : '',
                '📢 *Información de tu Módulo Académico*',
                '',
                '📖 *Módulo:*   ' + mod.nombre,
                '👨‍🏫 *Docente:*  ' + docente,
                '📚 *Programa:* ' + programa,
                '',
                '📅 *CALENDARIO DE SESIONES*',
                '▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬',
                sesionesTexto || '  (Sin sesiones registradas)',
                '▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬',
                parteEnlace,
                '',
                '💡 *RECOMENDACIONES*',
                '• Conecta 5 minutos antes de la sesión.',
                '• Ten tu cámara y micrófono listos.',
                '• Anota tus dudas para resolverlas al final.',
                '',
                '────────────────────',
                '🏢 *Área Académica*',
                '📍 Innova Ciencia Virtual',
                '📧 soporte@posgradosinnovaciencia.com',
                '📱 +591 XXX XXX XXX',
            ].join('\n');

            const preview = document.getElementById('moodleInfoMsgPreview');
            if (preview) preview.textContent = msg;

            // Guardar para reutilizar en el envío
            moduloActual._msgInfo = msg;
        }

        function renderTablaInfoEstudiantes() {
            const tbody = document.getElementById('moodleInfoEstudiantesBody');
            if (!tbody) return;
            if (!estudiantesData.length) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:2rem 1rem;color:#94a3b8;font-size:.82rem;">No hay estudiantes inscritos.</td></tr>';
                actualizarContadorInfo();
                return;
            }
            tbody.innerHTML = estudiantesData.map(function (e) {
                const celularLimpio = (e.celular || '').replace(/[^0-9]/g, '');
                const tieneCelular = celularLimpio.length >= 8;
                const btnWa = tieneCelular
                    ? `<button type="button" class="btn-wa-info-individual"
                            data-celular="${escHtml(celularLimpio)}"
                            data-nombre="${escHtml(e.nombre)}"
                            title="Enviar solo a este estudiante"
                            style="width:30px;height:30px;border-radius:7px;border:none;background:rgba(37,211,102,.12);color:#25d366;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:background .15s;font-size:.95rem;">
                            <i class="ri-whatsapp-line"></i>
                          </button>`
                    : `<span title="Sin celular registrado" style="width:30px;height:30px;display:inline-flex;align-items:center;justify-content:center;color:#cbd5e1;"><i class="ri-phone-off-line"></i></span>`;

                const celularBadge = tieneCelular
                    ? `<span style="font-size:.75rem;color:#334155;">${escHtml(e.celular || '')}</span>`
                    : `<span style="font-size:.73rem;color:#94a3b8;font-style:italic;">Sin celular</span>`;

                return `<tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:.5rem .75rem;text-align:center;">
                        <input type="checkbox" class="chk-info-estudiante" ${tieneCelular ? 'checked' : 'disabled'}
                            data-celular="${escHtml(celularLimpio)}"
                            data-nombre="${escHtml(e.nombre)}"
                            style="width:15px;height:15px;accent-color:#25d366;cursor:${tieneCelular ? 'pointer' : 'not-allowed'};border-radius:3px;">
                    </td>
                    <td style="padding:.5rem .75rem;">
                        <div style="font-weight:600;font-size:.82rem;color:#1e293b;">${escHtml(e.nombre)}</div>
                        <div style="font-size:.71rem;color:#94a3b8;">${escHtml(e.carnet)}</div>
                    </td>
                    <td style="padding:.5rem .75rem;">${celularBadge}</td>
                    <td style="padding:.5rem .75rem;text-align:center;">${btnWa}</td>
                </tr>`;
            }).join('');

            // WA individual
            tbody.querySelectorAll('.btn-wa-info-individual').forEach(function (btn) {
                btn.addEventListener('mouseenter', function () { this.style.background = 'rgba(37,211,102,.22)'; });
                btn.addEventListener('mouseleave', function () { this.style.background = 'rgba(37,211,102,.12)'; });
                btn.addEventListener('click', function () {
                    const msg = moduloActual._msgInfo || '';
                    if (!msg) { toast('warning', 'El mensaje aún no está disponible.'); return; }
                    window.open('https://wa.me/' + this.dataset.celular + '?text=' + encodeURIComponent(msg), '_blank');
                });
            });

            tbody.addEventListener('change', function (ev) {
                if (ev.target.classList.contains('chk-info-estudiante')) actualizarContadorInfo();
            });

            actualizarContadorInfo();
        }

        function actualizarContadorInfo() {
            const chks = [...document.querySelectorAll('.chk-info-estudiante:checked')];
            const total = chks.length;
            const el = document.getElementById('moodleInfoContador');
            if (el) el.textContent = total + ' seleccionado' + (total !== 1 ? 's' : '');
            const btn = document.getElementById('btnEnviarInfoModulo');
            if (btn) {
                btn.disabled = total === 0;
                btn.style.opacity = total === 0 ? '.6' : '1';
            }
        }

        function enviarInfoModuloMasivo() {
            const msg = moduloActual._msgInfo || '';
            if (!msg) { toast('warning', 'El mensaje aún no está disponible.'); return; }
            const seleccionados = [];
            document.querySelectorAll('.chk-info-estudiante:checked').forEach(function (chk) {
                const cel = (chk.dataset.celular || '').replace(/[^0-9]/g, '');
                if (cel.length >= 8) seleccionados.push({ celular: cel, nombre: chk.dataset.nombre });
            });
            if (!seleccionados.length) {
                toast('warning', 'Selecciona al menos un estudiante con celular registrado.');
                return;
            }
            seleccionados.forEach(function (est, idx) {
                setTimeout(function () {
                    window.open('https://wa.me/' + est.celular + '?text=' + encodeURIComponent(msg),
                        'wa_info_' + (++whatsappWindowCounter) + '_' + Date.now(), 'width=800,height=600');
                }, idx * 1200);
            });
            toast('success', 'Abriendo WhatsApp para ' + seleccionados.length + ' estudiante(s).');
        }

        document.getElementById('btnSeleccionarTodosInfo')?.addEventListener('click', function () {
            document.querySelectorAll('.chk-info-estudiante:not(:disabled)').forEach(c => c.checked = true);
            actualizarContadorInfo();
        });
        document.getElementById('btnDeseleccionarTodosInfo')?.addEventListener('click', function () {
            document.querySelectorAll('.chk-info-estudiante').forEach(c => c.checked = false);
            actualizarContadorInfo();
        });
        document.getElementById('btnEnviarInfoModulo')?.addEventListener('click', enviarInfoModuloMasivo);

        // ===== FIN PESTAÑA INFO MÓDULO =====

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

        function _initials(name) {
            const p = (name || '').trim().split(/\s+/);
            if (p.length >= 2) return (p[0][0] + p[1][0]).toUpperCase();
            return (p[0] && p[0][0] ? p[0][0] : '?').toUpperCase();
        }

        function _renderCA() {
            const modulos = (_caData.modulos || []).slice().sort(function(a, b) { return a.id - b.id; });
            const estudiantes = _caData.estudiantes;

            // Header
            let th = '<tr>';
            th += '<th class="plt-th-carnet"><i class="ri-id-card-line" style="margin-right:4px;color:#fc7b04;"></i>Carnet</th>';
            th += '<th class="plt-th-estudiante"><i class="ri-user-3-line" style="margin-right:4px;color:#fc7b04;"></i>Estudiante</th>';
            th += '<th class="plt-th-estado" title="Estado Contable (tab Finanzas)"><i class="ri-wallet-3-line" style="color:#0ea5e9;margin-right:3px;"></i>Contable</th>';
            th += '<th class="plt-th-estado" title="Estado Académico (tab Área Académica)"><i class="ri-graduation-cap-line" style="color:#6366f1;margin-right:3px;"></i>Académico</th>';
            modulos.forEach(function(m) {
                th += '<th class="plt-th-modulo" title="' + escHtml(m.nombre) + '">';
                th += '<div class="plt-th-modulo-name"><i class="ri-book-2-line"></i><span>' + escHtml(m.nombre) + '</span></div>';
                if (!m.moodle_course_id) {
                    th += '<span class="plt-th-alert"><i class="ri-alert-line"></i> Sin curso</span>';
                }
                th += '</th>';
            });
            th += '</tr>';
            document.getElementById('plataformaHead').innerHTML = th;

            // Show legend
            document.getElementById('plt-legend').style.display = '';

            // Body
            let tb = '';
            estudiantes.forEach(function(est) {
                const ap = (est.apellido_paterno || '').trim();
                const am = (est.apellido_materno || '').trim();
                const nm = (est.nombres || '').trim();
                const nombreOrdenado = (ap + ' ' + am + ' ' + nm).replace(/\s+/g, ' ').trim();
                const nombreDisplay = nombreOrdenado || est.nombre || '—';
                const ini = _initials(nombreDisplay);
                tb += '<tr>';
                tb += '<td class="plt-cell-ci">' +
                        '<span class="plt-ci-chip">' + escHtml(est.carnet || '—') + '</span>' +
                      '</td>';
                tb += '<td class="plt-cell-estudiante">' +
                    '<div class="plt-student-cell">' +
                        '<div class="plt-avatar">' + ini + '</div>' +
                        '<div>' +
                            '<div class="plt-student-name" title="' + escHtml(nombreDisplay) + '">' + escHtml(nombreDisplay) + '</div>' +
                            (!est.tiene_cuenta_moodle
                                ? '<div class="plt-no-moodle"><i class="ri-user-x-line"></i> Sin cuenta Moodle</div>'
                                : '') +
                        '</div>' +
                    '</div>' +
                    '</td>';

                const contOn = est.activo_contable !== false;
                const acadOn = est.activo_academico !== false;
                tb += '<td style="text-align:center;">' +
                    '<span class="plt-estado-chip ' + (contOn ? 'plt-estado-on' : 'plt-estado-off') + '" title="Estado contable: ' + (contOn ? 'Activo' : 'Baja') + '">' +
                        '<i class="' + (contOn ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill') + '"></i> ' +
                        (contOn ? 'Activo' : 'Baja') +
                    '</span>' +
                    '</td>';
                tb += '<td style="text-align:center;">' +
                    '<span class="plt-estado-chip ' + (acadOn ? 'plt-estado-on' : 'plt-estado-off') + '" title="Estado académico: ' + (acadOn ? 'Activo' : 'Baja') + '">' +
                        '<i class="' + (acadOn ? 'ri-checkbox-circle-fill' : 'ri-close-circle-fill') + '"></i> ' +
                        (acadOn ? 'Activo' : 'Baja') +
                    '</span>' +
                    '</td>';

                modulos.forEach(function(m) {
                    const md = est.modulos[m.id];
                    tb += '<td style="text-align:center;">';

                    if (!est.tiene_cuenta_moodle) {
                        tb += '<span class="plt-status-pill plt-status-sin">—</span>';
                    } else if (!m.moodle_course_id) {
                        tb += '<span class="plt-status-pill plt-status-sin"><i class="ri-minus-line"></i> Sin curso</span>';
                    } else if (!md || !md.matriculado) {
                        tb += '<span class="plt-status-pill plt-status-sin"><i class="ri-minus-line"></i> No matriculado</span>';
                    } else {
                        const sk = est.inscripcion_id + '-' + m.id;
                        const bloqueado = _caState[sk] === true;
                        const cuota = md.cuota;

                        const celEst = (est.celular || '').replace(/\D/g, '');

                        if (bloqueado) {
                            tb += _celdaBloqueado(m.id, est.inscripcion_id, sk, m.nombre, celEst, cuota);
                        } else if (!cuota) {
                            tb += '<span class="plt-status-pill plt-status-sin"><i class="ri-minus-circle-line"></i> Sin cuota</span>';
                        } else if (cuota.pagada) {
                            tb += _celdaPagado(cuota);
                        } else if (cuota.vencida) {
                            tb += _celdaVencida(m.id, est.inscripcion_id, sk, cuota, m.nombre, celEst);
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
            return '<div class="plt-cell-content">' +
                '<span class="plt-status-pill plt-status-pagado"><i class="ri-checkbox-circle-fill"></i> Pagado</span>' +
                '<span class="plt-monto">Bs ' + Number(c.monto_bs).toFixed(2) + '</span>' +
                '</div>';
        }

        function _celdaPendiente(c) {
            const f = _fmtFecha(c.fecha_vencimiento);
            return '<div class="plt-cell-content">' +
                '<span class="plt-status-pill plt-status-pendiente"><i class="ri-time-line"></i> Pendiente</span>' +
                '<span class="plt-fecha">' + f + '</span>' +
                '</div>';
        }

        function _celdaVencida(moduloId, inscripcionId, sk, c, nombreModulo, celular) {
            const monto = Number(c.pago_pendiente_bs || c.monto_bs || 0).toFixed(2);
            return '<div class="plt-cell-content">' +
                '<span class="plt-status-pill plt-status-vencida"><i class="ri-alarm-warning-line"></i> Vencida</span>' +
                '<span class="plt-monto">Bs ' + monto + '</span>' +
                '<button class="plt-btn-accion plt-btn-bloquear btn-ca"' +
                ' data-mod="' + moduloId + '" data-ins="' + inscripcionId +
                '" data-sk="' + sk + '" data-nombre="' + escHtml(nombreModulo) +
                '" data-celular="' + escHtml(celular || '') +
                '" data-cuota-nombre="' + escHtml(c.nombre || '') +
                '" data-cuota-monto="' + monto +
                '" data-sus="1">' +
                '<i class="ri-lock-line"></i> Bloquear</button>' +
                '</div>';
        }

        function _celdaBloqueado(moduloId, inscripcionId, sk, nombreModulo, celular, cuota) {
            const cuotaNombre = cuota ? (cuota.nombre || '') : '';
            const cuotaMonto  = cuota ? Number(cuota.pago_pendiente_bs || cuota.monto_bs || 0).toFixed(2) : '0.00';
            return '<div class="plt-cell-content">' +
                '<span class="plt-status-pill plt-status-bloqueado"><i class="ri-lock-fill"></i> Bloqueado</span>' +
                '<button class="plt-btn-accion plt-btn-habilitar btn-ca"' +
                ' data-mod="' + moduloId + '" data-ins="' + inscripcionId +
                '" data-sk="' + sk + '" data-nombre="' + escHtml(nombreModulo) +
                '" data-celular="' + escHtml(celular || '') +
                '" data-cuota-nombre="' + escHtml(cuotaNombre) +
                '" data-cuota-monto="' + cuotaMonto +
                '" data-sus="0">' +
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
        function ejecutarCambioAcceso(btn, moduloId, inscripcionId, sk, nombreModulo, suspender, nombreEstudiante, celular, cuotaNombre, cuotaMonto) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || window.CSRF || '';

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
                if (!response.ok) throw new Error('HTTP ' + response.status);
                return response.json();
            }).then(function(r) {
                if (r.success) {
                    _caState[sk] = suspender;
                    var accion = suspender ? 'bloqueado' : 'habilitado';
                    toast('success', 'Acceso ' + accion + ' correctamente en ' + nombreModulo + '.');
                    _renderCA();

                    // Enviar notificación por WhatsApp si tiene celular
                    const celularLimpio = (celular || '').replace(/\D/g, '');
                    if (celularLimpio.length >= 8) {
                        const programa = window.PROGRAMA_NOMBRE || 'Innova-Ciencia-Virtual';
                        const moodleUrl = window.MOODLE_URL || 'http://moodle52.localhost';
                        let mensaje;
                        if (suspender) {
                            mensaje = '🔒 *Acceso bloqueado — ' + programa + '*\n\n' +
                                'Estimado/a *' + nombreEstudiante + '*,\n\n' +
                                'Su acceso al *Módulo: ' + nombreModulo + '* ha sido *bloqueado temporalmente* por una deuda pendiente de pago.' +
                                (cuotaNombre ? '\n\n📋 *Cuota pendiente:* ' + cuotaNombre : '') +
                                (Number(cuotaMonto) > 0 ? '\n💰 *Monto pendiente:* Bs. ' + Number(cuotaMonto).toFixed(2) : '') +
                                '\n\nPara *habilitar* su acceso, le solicitamos realizar el pago de la cuota correspondiente y comunicarse con la institución.\n\n' +
                                '_Área Académica Innova-Ciencia-Virtual_';
                        } else {
                            mensaje = '✅ *Acceso habilitado — ' + programa + '*\n\n' +
                                'Estimado/a *' + nombreEstudiante + '*,\n\n' +
                                'Su acceso al *Módulo: ' + nombreModulo + '* ha sido *habilitado correctamente*.\n\n' +
                                'Ya puede ingresar a la plataforma y acceder al contenido del módulo.\n\n' +
                                '🌐 *Plataforma:* ' + moodleUrl + '\n\n' +
                                '_Área Académica Innova-Ciencia-Virtual_';
                        }
                        window.open('https://wa.me/' + celularLimpio + '?text=' + encodeURIComponent(mensaje), '_blank');
                    }
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

            const moduloId       = btn.dataset.mod;
            const inscripcionId  = btn.dataset.ins;
            const sk             = btn.dataset.sk;
            const nombreModulo   = btn.dataset.nombre || 'módulo';
            const nombreEstudiante = btn.closest('tr')?.querySelector('.plt-student-name')?.textContent || 'Estudiante';
            const suspender      = btn.dataset.sus === '1';
            const celular        = btn.dataset.celular || '';
            const cuotaNombre    = btn.dataset.cuotaNombre || '';
            const cuotaMonto     = btn.dataset.cuotaMonto || '0';

            // Almacenar la acción pendiente
            pendienteAccesoPlataforma = { btn, moduloId, inscripcionId, sk, nombreModulo, nombreEstudiante, suspender, celular, cuotaNombre, cuotaMonto };

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

            const { btn, moduloId, inscripcionId, sk, nombreModulo, nombreEstudiante, suspender, celular, cuotaNombre, cuotaMonto } = pendienteAccesoPlataforma;

            // Cerrar modal
            if (modalAccesoPlataformaInstance) {
                modalAccesoPlataformaInstance.hide();
            }

            // Ejecutar la acción
            ejecutarCambioAcceso(btn, moduloId, inscripcionId, sk, nombreModulo, suspender, nombreEstudiante, celular, cuotaNombre, cuotaMonto);

            pendienteAccesoPlataforma = null;
        });
        // ===== FIN CONTROL DE ACCESO =====

    })();

</script>

{{-- ═══════════════════════════════════════════════════════
     Scripts: Responsables y Documentos (Tab Info General)
══════════════════════════════════════════════════════════ --}}
<script>
(function() {
    'use strict';

    const OFERTA_ID   = {{ $oferta->id }};
    const CSRF_TOKEN  = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const UPDATE_URL  = '/admin/posgrads/ofertas/' + OFERTA_ID;

    /* Datos actuales de la oferta — necesarios porque actualizar() valida todos los campos */
    const OFERTA_DATA = {
        codigo:                     '{{ addslashes($oferta->codigo) }}',
        posgrado_id:                '{{ $oferta->posgrado_id }}',
        programa_id:                '{{ $oferta->programa_id }}',
        fase_id:                    '{{ $oferta->fase_id }}',
        sucursale_id:               '{{ $oferta->sucursale_id ?? '' }}',
        modalidade_id:              '{{ $oferta->modalidade_id ?? '' }}',
        fecha_inicio_inscripciones: '{{ $oferta->fecha_inicio_inscripciones ? $oferta->fecha_inicio_inscripciones->format('Y-m-d') : '' }}',
        fecha_inicio_programa:      '{{ $oferta->fecha_inicio_programa ? $oferta->fecha_inicio_programa->format('Y-m-d') : '' }}',
        fecha_fin_programa:         '{{ $oferta->fecha_fin_programa ? $oferta->fecha_fin_programa->format('Y-m-d') : '' }}',
        gestion:                    '{{ $oferta->gestion }}',
        n_modulos:                  '{{ $oferta->n_modulos }}',
        cantidad_sesiones:          '{{ $oferta->cantidad_sesiones }}',
        version:                    '{{ $oferta->version }}',
        grupo:                      '{{ $oferta->grupo }}',
        nota_minima:                '{{ $oferta->nota_minima }}',
        color:                      '{{ $oferta->color ?? '#fc7b04' }}',
        responsable_academico_id:   '{{ $oferta->responsable_academico_id ?? '' }}',
        responsable_marketing_id:   '{{ $oferta->responsable_marketing_id ?? '' }}',
    };

    function fdConDatosBase(extraOverrides = {}, skipKeys = []) {
        const fd = new FormData();
        fd.append('_token',  CSRF_TOKEN);
        fd.append('_method', 'PUT');
        Object.entries({ ...OFERTA_DATA, ...extraOverrides }).forEach(([k, v]) => {
            if (skipKeys.includes(k)) return;
            if (v !== '' && v !== null && v !== undefined) fd.append(k, v);
        });
        return fd;
    }

    /* ── helper: muestra nombre del archivo seleccionado ── */
    window.tgiFileSelected = function(inputId, labelId, dropId) {
        const input = document.getElementById(inputId);
        const label = document.getElementById(labelId);
        const drop  = document.getElementById(dropId);
        if (!input || !input.files.length) return;
        const file = input.files[0];
        label.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
        drop.classList.add('has-file');
    };

    /* ════════════════════════════════════════════════════
       RESPONSABLES — búsqueda en tiempo real por carnet
    ════════════════════════════════════════════════════ */
    (function() {
        let allTrabajadoresResp = [];

        function cargarTrabajadoresResp() {
            return fetch('/admin/posgrads/personas/listar-trabajadores')
                .then(r => r.json())
                .then(d => { allTrabajadoresResp = d.data || []; })
                .catch(() => { allTrabajadoresResp = []; });
        }

        function nombreTrabajador(t) {
            const p = t.trabajador && t.trabajador.persona ? t.trabajador.persona : null;
            if (!p) return t.nombre_cargo || '';
            return ((p.nombres || '') + ' ' + (p.apellido_paterno || '') + ' ' + (p.apellido_materno || '')).trim();
        }

        function carnetTrabajador(t) {
            return (t.trabajador && t.trabajador.persona && t.trabajador.persona.carnet) || '';
        }

        function cargoTrabajador(t) {
            return t.cargo && t.cargo.nombre ? t.cargo.nombre : (t.nombre_cargo || '');
        }

        function setupBuscador(inputId, dropId, hiddenId, nombreId, cargoId, panelId, limpiarId) {
            const input   = document.getElementById(inputId);
            const drop    = document.getElementById(dropId);
            const hidden  = document.getElementById(hiddenId);
            const nombre  = document.getElementById(nombreId);
            const cargo   = document.getElementById(cargoId);
            const panel   = document.getElementById(panelId);
            const limpiar = document.getElementById(limpiarId);
            if (!input) return;

            let debounceTimer;

            function buscar(q) {
                q = q.trim().toLowerCase();
                if (!q) { drop.style.display = 'none'; return; }
                const resultados = allTrabajadoresResp.filter(t => {
                    const cn = carnetTrabajador(t).toLowerCase();
                    const nm = nombreTrabajador(t).toLowerCase();
                    return cn.includes(q) || nm.includes(q);
                }).slice(0, 8);

                if (!resultados.length) {
                    drop.innerHTML = '<div style="padding:.65rem 1rem;font-size:.82rem;color:#94a3b8;">Sin resultados.</div>';
                    drop.style.display = 'block';
                    return;
                }

                drop.innerHTML = resultados.map(t => {
                    const nm = nombreTrabajador(t);
                    const cn = carnetTrabajador(t);
                    const cg = cargoTrabajador(t);
                    const ini = nm ? nm.charAt(0).toUpperCase() : '?';
                    return '<div class="resp-drop-item" data-id="' + t.id + '" data-nombre="' + nm.replace(/"/g,'&quot;') + '" data-cargo="' + cg.replace(/"/g,'&quot;') + '"' +
                        ' style="display:flex;align-items:center;gap:.55rem;padding:.55rem .85rem;cursor:pointer;border-bottom:1px solid #f1f5f9;transition:background .1s;"' +
                        ' onmouseover="this.style.background=\'#f8fafc\'" onmouseout="this.style.background=\'\'">'+
                        '<div style="width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.75rem;font-weight:700;color:#fff;">' + ini + '</div>' +
                        '<div style="min-width:0;flex:1;"><div style="font-size:.83rem;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + nm + '</div>' +
                        '<div style="font-size:.71rem;color:#64748b;">' + cg + (cn ? ' · CI: ' + cn : '') + '</div></div></div>';
                }).join('');
                drop.style.display = 'block';
            }

            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => buscar(this.value), 200);
            });

            drop.addEventListener('click', function(e) {
                const item = e.target.closest('.resp-drop-item');
                if (!item) return;
                const id   = item.dataset.id;
                const nm   = item.dataset.nombre;
                const cg   = item.dataset.cargo;
                hidden.value       = id;
                nombre.textContent = nm;
                cargo.textContent  = cg;
                panel.style.display = 'flex';
                input.value = '';
                drop.style.display = 'none';
            });

            document.addEventListener('click', function(e) {
                if (!drop.contains(e.target) && e.target !== input) drop.style.display = 'none';
            });

            if (limpiar) {
                limpiar.addEventListener('click', function() {
                    hidden.value = '';
                    panel.style.display = 'none';
                    input.value = '';
                });
            }
        }

        // Inicializar valores actuales al abrir el modal
        document.getElementById('modalEditarResponsables')?.addEventListener('show.bs.modal', function() {
            if (!allTrabajadoresResp.length) cargarTrabajadoresResp().then(iniciarPaneles);
            else iniciarPaneles();
        });

        function iniciarPaneles() {
            ['Academico', 'Marketing'].forEach(function(rol) {
                const hidden = document.getElementById('resp' + rol + 'Id');
                const panel  = document.getElementById('resp' + rol + 'Actual');
                const nombre = document.getElementById('resp' + rol + 'Nombre');
                const cargo  = document.getElementById('resp' + rol + 'Cargo');
                if (!hidden) return;
                const id = hidden.value;
                if (!id) { if (panel) panel.style.display = 'none'; return; }
                const t = allTrabajadoresResp.find(x => String(x.id) === String(id));
                if (t && panel) {
                    nombre.textContent = nombreTrabajador(t);
                    cargo.textContent  = cargoTrabajador(t);
                    panel.style.display = 'flex';
                }
            });
        }

        setupBuscador('inputBuscarAcademico','dropAcademico','respAcademicoId','respAcademicoNombre','respAcademicoCargo','respAcademicoActual','btnLimpiarAcademico');
        setupBuscador('inputBuscarMarketing','dropMarketing','respMarketingId','respMarketingNombre','respMarketingCargo','respMarketingActual','btnLimpiarMarketing');

        document.getElementById('btnGuardarResponsables')?.addEventListener('click', function() {
            const btn       = this;
            const academico = document.getElementById('respAcademicoId').value || null;
            const marketing = document.getElementById('respMarketingId').value || null;

            // Excluir los IDs de responsables de OFERTA_DATA para controlarlos manualmente
            const fd = fdConDatosBase({}, ['responsable_academico_id', 'responsable_marketing_id']);
            // Enviar siempre estos campos (incluso vacíos) para permitir desasignar
            if (academico) fd.append('responsable_academico_id', academico);
            else fd.append('responsable_academico_id', '');
            if (marketing) fd.append('responsable_marketing_id', marketing);
            else fd.append('responsable_marketing_id', '');

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Guardando...';

            fetch(UPDATE_URL, { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ri-save-line"></i> Guardar';
                    if (data.success !== false) {
                        bootstrap.Modal.getInstance(document.getElementById('modalEditarResponsables'))?.hide();
                        toast('success', 'Responsables actualizados correctamente.');
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        const firstError = data.errors ? Object.values(data.errors)[0]?.[0] : null;
                        toast('error', firstError || data.message || 'Error al guardar los responsables.');
                    }
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ri-save-line"></i> Guardar';
                    toast('error', 'Error de conexión.');
                });
        });
    })();

    /* ════════════════════════════════════
       GUARDAR DOCUMENTOS (modal combinado)
    ════════════════════════════════════ */
    document.getElementById('btnGuardarDocumentos')?.addEventListener('click', function() {
        const btn    = this;
        const portada = document.getElementById('inputPortada').files[0];
        const certif  = document.getElementById('inputCertificado').files[0];

        if (!portada && !certif) {
            toast('warning', 'Selecciona al menos un archivo para actualizar.');
            return;
        }

        const fd = fdConDatosBase();
        if (portada) fd.append('portada',     portada);
        if (certif)  fd.append('certificado', certif);

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Subiendo...';

        fetch(UPDATE_URL, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-save-line"></i> Guardar cambios';
                if (data.success !== false) {
                    bootstrap.Modal.getInstance(document.getElementById('modalEditarDocumentos'))?.hide();
                    toast('success', 'Documentos actualizados correctamente.');
                    setTimeout(() => location.reload(), 1200);
                } else {
                    const firstError = data.errors ? Object.values(data.errors)[0]?.[0] : null;
                    toast('error', firstError || data.message || 'Error al guardar los documentos.');
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-save-line"></i> Guardar cambios';
                toast('error', 'Error de conexión.');
            });
    });

    /* ════════════════════════════════════
       EDITAR DOCUMENTO INDIVIDUAL
    ════════════════════════════════════ */
    window.abrirModalDocSolo = function(tipo) {
        const esPortada = tipo === 'portada';
        document.getElementById('tipoDocSolo').value = tipo;
        document.getElementById('labelTituloDocSolo').textContent = esPortada ? 'Editar Portada' : 'Editar Certificado';
        document.getElementById('iconoDocSolo').className = esPortada ? 'ri-image-line' : 'ri-file-pdf-line';
        document.getElementById('iconoDocSolo').style.color = esPortada ? '#6366f1' : '#ef4444';
        document.getElementById('labelDescDocSolo').textContent = esPortada ? 'Imagen de Portada' : 'Certificado Base';
        document.getElementById('hintDocSolo').innerHTML = esPortada
            ? '<i class="ri-information-line"></i> JPG, PNG, GIF o WEBP — máx. 2 MB'
            : '<i class="ri-information-line"></i> PDF, JPG o PNG — máx. 5 MB';

        const inputDocSolo = document.getElementById('inputDocSolo');
        inputDocSolo.accept = esPortada
            ? 'image/jpeg,image/png,image/gif,image/webp'
            : 'image/jpeg,image/png,image/gif,.pdf,application/pdf';
        inputDocSolo.value = '';

        document.getElementById('labelDocSolo').textContent = 'Haz clic o arrastra el archivo aquí';
        document.getElementById('dropDocSolo').classList.remove('has-file');

        const estadoEl = document.getElementById('estadoActualDocSolo');
        @if($oferta->portada)
        if (esPortada) {
            estadoEl.innerHTML = '<i class="ri-checkbox-circle-fill" style="color:#16a34a;"></i> <a href="{{ asset('storage/' . $oferta->portada) }}" target="_blank" style="color:#16a34a;font-size:.68rem;text-decoration:underline;">ver actual</a>';
        }
        @else
        if (esPortada) estadoEl.innerHTML = '<span style="font-size:.68rem;color:#94a3b8;"><i class="ri-close-circle-line"></i> Sin portada</span>';
        @endif
        @if($oferta->certificado)
        if (!esPortada) {
            estadoEl.innerHTML = '<i class="ri-checkbox-circle-fill" style="color:#16a34a;"></i> <a href="{{ asset('storage/' . $oferta->certificado) }}" target="_blank" style="color:#16a34a;font-size:.68rem;text-decoration:underline;">ver actual</a>';
        }
        @else
        if (!esPortada) estadoEl.innerHTML = '<span style="font-size:.68rem;color:#94a3b8;"><i class="ri-close-circle-line"></i> Sin certificado</span>';
        @endif

        new bootstrap.Modal(document.getElementById('modalEditarDocSolo')).show();
    };

    document.getElementById('btnGuardarDocSolo')?.addEventListener('click', function() {
        const btn   = this;
        const tipo  = document.getElementById('tipoDocSolo').value;
        const archivo = document.getElementById('inputDocSolo').files[0];

        if (!archivo) {
            toast('warning', 'Selecciona un archivo para continuar.');
            return;
        }

        const fd = fdConDatosBase();
        fd.append(tipo, archivo);

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Subiendo...';

        fetch(UPDATE_URL, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-save-line"></i> Guardar';
                if (data.success !== false) {
                    bootstrap.Modal.getInstance(document.getElementById('modalEditarDocSolo'))?.hide();
                    const label = tipo === 'portada' ? 'Portada' : 'Certificado';
                    toast('success', label + ' actualizado correctamente.');
                    setTimeout(() => location.reload(), 1200);
                } else {
                    const firstError = data.errors ? Object.values(data.errors)[0]?.[0] : null;
                    toast('error', firstError || data.message || 'Error al guardar.');
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-save-line"></i> Guardar';
                toast('error', 'Error de conexión.');
            });
    });

})();
</script>
