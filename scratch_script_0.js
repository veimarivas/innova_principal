null
        (function() {
                'use strict';
                let tabla;
                let idEliminar = null;
                let quickAddTarget = null;
                const CSRF = 'null';

                $.fn.dataTable.ext.errMode = 'none';

                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    const convenio = $('#filtro-convenio').val();
                    const area = $('#filtro-area').val();
                    const tipo = $('#filtro-tipo').val();
                    const estado = $('#filtro-estado').val();

                    if (!convenio && !area && !tipo && !estado) return true;

                    const row = settings.aoData[dataIndex];
                    if (!row) return true;
                    const raw = row._aData;
                    if (!raw) return true;

                    if (convenio && String(raw.convenio_id || '') !== convenio) return false;
                    if (area && String(raw.area_id || '') !== area) return false;
                    if (tipo && String(raw.tipo_id || '') !== tipo) return false;
                    if (estado !== '' && String(raw.estado ? 1 : 0) !== estado) return false;

                    return true;
                });

                function init() {
                    initDataTable();
                    bindEvents();
                }

                function initDataTable() {
                    tabla = $('#tabla-posgrads').DataTable({
                        ajax: {
                            url: 'null',
                            dataSrc: 'data',
                            error: function(xhr) {
                                const msg = xhr.status === 0 ? 'Sin conexión al servidor.' : 'Error ' + xhr
                                    .status + ' al cargar los datos.';
                                toast('error', msg);
                            }
                        },
                        ordering: true,
                        paging: false,
                        info: false,
                        columns: [{
                                data: 'nombre',
                                render: n => '<span style="font-weight:600;">' + escHtml(n) + '</span>'
                            },
                            {
                                data: 'convenio.nombre',
                                render: d => d ? '<span class="badge-display">' + escHtml(d) + '</span>' :
                                    '<span class="text-muted">-</span>'
                            },
                            {
                                data: 'area.nombre',
                                render: d => d ? '<span class="badge-display badge-info">' + escHtml(d) +
                                    '</span>' : '<span class="text-muted">-</span>'
                            },
                            {
                                data: 'tipo.nombre',
                                render: d => d ? '<span class="badge-display">' + escHtml(d) + '</span>' :
                                    '<span class="text-muted">-</span>'
                            },
                            {
                                data: null,
                                render: d => d.duracion_numero > 0 ? d.duracion_numero + ' ' + (d
                                    .duracion_unidad || 'Horas') : '-'
                            },
                            {
                                data: null,
                                className: 'text-center',
                                render: d => {
                                    const activo = d.estado;
                                    const label = activo ? 'Activo' : 'No Activo';
                                    const cls = activo ? 'activo' : 'inactivo';
                                    return '<span class="badge-estado ' + cls + '">' +
                                        '<span class="badge-estado-dot"></span>' + label + '</span>';
                                }
                            },
                            {
                                data: null,
                                className: 'text-center',
                                render: d => '<div class="action-cell">' +
                                    '<a href="/admin/posgrads/' + d.id +
                                    '/ofertas" class="btn btn-action btn-action-ofertas" data-id="' + d.id +
                                    '" data-nombre="' + escHtml(d.nombre) +
                                    '" title="Ofertas académicas" style="color:#22c55e;"><i class="ri-book-open-line"></i></a>' +
                                    '<button class="btn btn-action btn-action-edit btn-accion-editar" data-id="' +
                                    d.id + '" data-nombre="' + escHtml(d.nombre) + '" data-creditaje="' + (d
                                        .creditaje || '') + '" data-carga_horaria="' + (d.carga_horaria || '') +
                                    '" data-duracion_numero="' + (d.duracion_numero || '') +
                                    '" data-duracion_unidad="' + (d.duracion_unidad || '') +
                                    '" data-convenio_id="' + (d.convenio_id || '') + '" data-area_id="' + (d
                                        .area_id || '') + '" data-tipo_id="' + (d.tipo_id || '') +
                                    '" data-dirigido="' + escHtml(d.dirigido || '') + '" data-objetivo="' +
                                    escHtml(d.objetivo || '') + '" data-estado="' + (d.estado ? 1 : 0) +
                                    '" title="Editar posgrado"><i class="ri-pencil-fill"></i></button>' +
                                    '<button class="btn btn-action btn-action-delete btn-accion-eliminar" data-id="' +
                                    d.id + '" data-nombre="' + escHtml(d.nombre) +
                                    '" title="Eliminar posgrado"><i class="ri-delete-bin-fill"></i></button>' +
                                    '</div>'
                            }
                        ],
                        language: {
                            processing: 'Procesando...',
                            search: 'Buscar:',
                            lengthMenu: 'Mostrar _MENU_ registros',
                            info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                            infoEmpty: 'Mostrando 0 a 0 de 0 registros',
                            infoFiltered: '(filtrado de _MAX_ registros totales)',
                            loadingRecords: 'Cargando...',
                            zeroRecords: 'No se encontraron registros',
                            emptyTable: 'No hay datos disponibles',
                            paginate: {
                                first: 'Primero',
                                previous: 'Anterior',
                                next: 'Siguiente',
                                last: 'Último'
                            },
                            aria: {
                                sortAscending: ': activar para ordenar ascendente',
                                sortDescending: ': activar para ordenar descendente'
                            }
                        },
                        order: [
                            [0, 'asc']
                        ],
                        lengthMenu: [
                            [10, 25, 50, -1],
                            [10, 25, 50, 'Todos']
                        ],
                        pageLength: 10,
                        drawCallback: function() {
                            const api = this.api();
                            const recordsTotal = api.rows().data().length;
                            const activosCount = api.rows().data().filter(function(row) {
                                return row.estado;
                            }).length;
                            $('#stat-total').text(recordsTotal);
                            $('#stat-activos').text(activosCount);
                            if (recordsTotal > 10) {
                                $('.dataTables_paginate').show();
                                $('.dataTables_length').show();
                            } else {
                                $('.dataTables_paginate').hide();
                                $('.dataTables_length').hide();
                            }
                        }
                    });
                }

                function populateSelects() {
                    $.when(
                        $.get('null'),
                        $.get('null')
                    ).done(function(areasRes, tiposRes) {
                        const areas = areasRes[0].data;
                        const tipos = tiposRes[0].data;

                        const selects = ['areaCrear', 'areaEditar'];
                        selects.forEach(function(selId) {
                            const currentVal = $('#' + selId).val();
                            const $sel = $('#' + selId);
                            $sel.find('option:not(:first)').remove();
                            areas.forEach(function(a) {
                                $sel.append('<option value="' + a.id + '">' + escHtml(a.nombre) +
                                    '</option>');
                            });
                            if (currentVal) $sel.val(currentVal);
                        });

                        const tipoSelects = ['tipoCrear', 'tipoEditar'];
                        tipoSelects.forEach(function(selId) {
                            const currentVal = $('#' + selId).val();
                            const $sel = $('#' + selId);
                            $sel.find('option:not(:first)').remove();
                            tipos.forEach(function(t) {
                                $sel.append('<option value="' + t.id + '">' + escHtml(t.nombre) +
                                    '</option>');
                            });
                            if (currentVal) $sel.val(currentVal);
                        });
                    });
                }

                function addOptionToSelect(selectId, value, text) {
                    const $sel = $('#' + selectId);
                    if ($sel.find('option[value="' + value + '"]').length === 0) {
                        $sel.append('<option value="' + value + '">' + escHtml(text) + '</option>');
                    }
                    $sel.val(value);
                }

                function bindEvents() {
                    $('#btn-nuevo').on('click', () => {
                        resetField('nombreCrear', 'iconCrear', 'fbCrear');
                        $('#formCrear')[0].reset();
                        $('#estadoCrear').prop('checked', true);
                        $('#estadoCrearLabel').text('Activo').removeClass('inactive').addClass('active');
                        $('#btnGuardar').prop('disabled', true);
                        openModal('modalCrear');
                    });

                    $('#estadoCrear').on('change', function() {
                        const label = $('#estadoCrearLabel');
                        if ($(this).is(':checked')) {
                            label.text('Activo').removeClass('inactive').addClass('active');
                        } else {
                            label.text('No Activo').removeClass('active').addClass('inactive');
                        }
                    });

                    $('#estadoEditar').on('change', function() {
                        const label = $('#estadoEditarLabel');
                        if ($(this).is(':checked')) {
                            label.text('Activo').removeClass('inactive').addClass('active');
                        } else {
                            label.text('No Activo').removeClass('active').addClass('inactive');
                        }
                    });

                    $(document).on('click', '.btn-accion-editar', function() {
                        const d = $(this).data();
                        $('#idEditar').val(d.id);
                        $('#nombreEditar').val(d.nombre);
                        $('#creditajeEditar').val(d.creditaje !== undefined ? d.creditaje : '');
                        $('#cargaHorariaEditar').val(d.carga_horaria !== undefined ? d.carga_horaria : '');
                        $('#duracionNumeroEditar').val(d.duracion_numero !== undefined ? d.duracion_numero : '');
                        $('#duracionUnidadEditar').val(d.duracion_unidad || 'Horas');
                        $('#convenioEditar').val(d.convenio_id || '');
                        $('#areaEditar').val(d.area_id || '');
                        $('#tipoEditar').val(d.tipo_id || '');
                        $('#dirigidoEditar').val(d.dirigido || '');
                        $('#objetivoEditar').val(d.objetivo || '');
                        const estadoVal = d.estado !== undefined ? d.estado : 1;
                        $('#estadoEditar').prop('checked', estadoVal == 1);
                        if (estadoVal == 1) {
                            $('#estadoEditarLabel').text('Activo').removeClass('inactive').addClass('active');
                        } else {
                            $('#estadoEditarLabel').text('No Activo').removeClass('active').addClass('inactive');
                        }
                        resetField('nombreEditar', 'iconEditar', 'fbEditar');
                        $('#btnActualizar').prop('disabled', true);
                        verificarNombre('nombreEditar', 'iconEditar', 'fbEditar', d.id, '#btnActualizar');
                        openModal('modalEditar');
                    });

                    $(document).on('click', '.btn-accion-eliminar', function() {
                        idEliminar = $(this).data('id');
                        $('#nombreEliminar').text($(this).data('nombre'));
                        openModal('modalEliminar');
                    });

                    $('#btnConfirmarEliminar').on('click', function() {
                        if (!idEliminar) return;
                        eliminarPosgrado(idEliminar);
                    });

                    $('#formCrear').on('submit', e => {
                        e.preventDefault();
                        guardar();
                    });

                    $('#formEditar').on('submit', e => {
                        e.preventDefault();
                        actualizar();
                    });

                    $('#btnGuardar').on('click', function() {
                        guardar();
                    });

                    $('#btnActualizar').on('click', function() {
                        actualizar();
                    });

                    $('#nombreCrear').on('input', function() {
                        verificarNombre('nombreCrear', 'iconCrear', 'fbCrear', null, '#btnGuardar');
                    });

                    $('#nombreEditar').on('input', function() {
                        const id = $('#idEditar').val();
                        verificarNombre('nombreEditar', 'iconEditar', 'fbEditar', id, '#btnActualizar');
                    });

                    document.getElementById('modalCrear').addEventListener('hidden.bs.modal', () => {
                        resetField('nombreCrear', 'iconCrear', 'fbCrear');
                        $('#formCrear')[0].reset();
                        $('#btnGuardar').prop('disabled', true);
                    });

                    document.getElementById('modalEditar').addEventListener('hidden.bs.modal', () => {
                        resetField('nombreEditar', 'iconEditar', 'fbEditar');
                        $('#formEditar')[0].reset();
                    });

                    $('#filtro-convenio, #filtro-area, #filtro-tipo, #filtro-estado').on('change', function() {
                        tabla.draw();
                    });

                    $('#btn-limpiar-filtros').on('click', function() {
                        $('#filtro-convenio, #filtro-area, #filtro-tipo, #filtro-estado').val('');
                        tabla.draw();
                    });

                    // Quick add: Área
                    $('#btnQuickArea, #btnQuickAreaEditar').on('click', function() {
                        quickAddTarget = $(this).closest('.col-fields-2').find('select[id^="area"]').attr('id');
                        $('#quickAreaNombre').val('');
                        $('#fbQuickArea').html('');
                        openModal('modalQuickArea');
                        setTimeout(() => $('#quickAreaNombre').focus(), 300);
                    });

                    $('#btnGuardarQuickArea').on('click', function() {
                        const nombre = $('#quickAreaNombre').val().trim();
                        if (!nombre || nombre.length < 2) {
                            $('#fbQuickArea').html(
                                '<span style="color:#ef4444;font-size:0.78rem;"><i class="ri-error-warning-line"></i>Debe tener al menos 2 caracteres.</span>'
                            );
                            return;
                        }
                        setBtnLoading('#btnGuardarQuickArea', true, 'Guardando…');
                        $.post('null', {
                                _token: CSRF,
                                nombre: nombre
                            })
                            .done(function(r) {
                                closeModal('modalQuickArea');
                                populateSelects();
                                setTimeout(function() {
                                    if (quickAddTarget) {
                                        addOptionToSelect(quickAddTarget, r.data.id, r.data.nombre);
                                    }
                                }, 200);
                                toast('success', r.message || 'Área guardada correctamente.');
                            })
                            .fail(function(xhr) {
                                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors && xhr
                                    .responseJSON.errors.nombre) {
                                    $('#fbQuickArea').html(
                                        '<span style="color:#ef4444;font-size:0.78rem;"><i class="ri-error-warning-line"></i>' +
                                        xhr.responseJSON.errors.nombre[0] + '</span>');
                                } else {
                                    toast('error', 'No se pudo guardar el área.');
                                }
                            })
                            .always(function() {
                                setBtnLoading('#btnGuardarQuickArea', false,
                                    '<i class="ri-save-line"></i> Guardar');
                            });
                    });

                    $('#quickAreaNombre').on('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            $('#btnGuardarQuickArea').click();
                        }
                    });

                    // Quick add: Tipo
                    $('#btnQuickTipo, #btnQuickTipoEditar').on('click', function() {
                        quickAddTarget = $(this).closest('.col-fields-2').find('select[id^="tipo"]').attr('id');
                        $('#quickTipoNombre').val('');
                        $('#quickTipoDesc').val('');
                        $('#fbQuickTipo').html('');
                        openModal('modalQuickTipo');
                        setTimeout(() => $('#quickTipoNombre').focus(), 300);
                    });

                    $('#btnGuardarQuickTipo').on('click', function() {
                        const nombre = $('#quickTipoNombre').val().trim();
                        if (!nombre || nombre.length < 2) {
                            $('#fbQuickTipo').html(
                                '<span style="color:#ef4444;font-size:0.78rem;"><i class="ri-error-warning-line"></i>Debe tener al menos 2 caracteres.</span>'
                            );
                            return;
                        }
                        setBtnLoading('#btnGuardarQuickTipo', true, 'Guardando…');
                        $.post('null', {
                                _token: CSRF,
                                nombre: nombre,
                                descripcion: $('#quickTipoDesc').val().trim()
                            })
                            .done(function(r) {
                                closeModal('modalQuickTipo');
                                populateSelects();
                                setTimeout(function() {
                                    if (quickAddTarget) {
                                        addOptionToSelect(quickAddTarget, r.data.id, r.data.nombre);
                                    }
                                }, 200);
                                toast('success', r.message || 'Tipo guardado correctamente.');
                            })
                            .fail(function(xhr) {
                                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors && xhr
                                    .responseJSON.errors.nombre) {
                                    $('#fbQuickTipo').html(
                                        '<span style="color:#ef4444;font-size:0.78rem;"><i class="ri-error-warning-line"></i>' +
                                        xhr.responseJSON.errors.nombre[0] + '</span>');
                                } else {
                                    toast('error', 'No se pudo guardar el tipo.');
                                }
                            })
                            .always(function() {
                                setBtnLoading('#btnGuardarQuickTipo', false,
                                    '<i class="ri-save-line"></i> Guardar');
                            });
                    });

                    $('#quickTipoNombre').on('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            $('#btnGuardarQuickTipo').click();
                        }
                    });

                    // Reset quickAddTarget when quick modals close
                    document.getElementById('modalQuickArea').addEventListener('hidden.bs.modal', function() {
                        quickAddTarget = null;
                    });
                    document.getElementById('modalQuickTipo').addEventListener('hidden.bs.modal', function() {
                        quickAddTarget = null;
                    });

                    // === OFERTAS ACADÉMICAS ===
                    let tablaOfertas = null;
                    let currentPosgradoOfertas = null;
                    let idEliminarOferta = null;
                    let portadaFile = null;
                    let certificadoFile = null;

                    const programasData = null;
                    const trabajadoresAcademicosData = null;
                    const trabajadoresMarketingData = null;
                    const fasesData = null;
                    const faseAprobadoId = fasesData.find(function(f) {
                        return f.nombre.toLowerCase().indexOf('aprobado') !== -1;
                    });
                    const faseNoAprobadoId = fasesData.find(function(f) {
                        return f.nombre.toLowerCase().indexOf('no aprobado') !== -1 || f.nombre.toLowerCase()
                            .indexOf('no aprob') !== -1;
                    });

                    let timeoutOfertaPrograma = null;
                    function checkOfertaPrograma() {
                        const programaNombre = $('#ofertaProgramaText').val().trim();
                        const version = $('#ofertaVersion').val() || 1;
                        const grupo = $('#ofertaGrupo').val() || 1;
                        const posgradoId = $('#ofertaPosgradoId').val();
                        const id = $('#ofertaId').val();

                        if (!programaNombre || programaNombre.length < 2) {
                            if (!programaNombre) setFieldFeedback('ofertaProgramaText', 'fbOfertaPrograma', false, 'El programa es obligatorio.');
                            else setFieldFeedback('ofertaProgramaText', 'fbOfertaPrograma', false, 'Mínimo 2 caracteres.');
                            return;
                        }

                        clearTimeout(timeoutOfertaPrograma);
                        timeoutOfertaPrograma = setTimeout(() => {
                            $.ajax({
                                url: 'null',
                                type: 'POST',
                                data: {
                                    _token: CSRF,
                                    posgrado_id: posgradoId,
                                    programa_nombre: programaNombre,
                                    version: version,
                                    grupo: grupo,
                                    exclude_id: id || null
                                }
                            }).done(function(r) {
                                if (r.existe) {
                                    setFieldFeedback('ofertaProgramaText', 'fbOfertaPrograma', false, 'Ya existe un programa con este nombre, versión y grupo.');
                                } else {
                                    setFieldFeedback('ofertaProgramaText', 'fbOfertaPrograma', true, 'Programa válido y disponible.');
                                }
                            });
                        }, 400);
                    }

                    function generarNombrePrograma() {
                        let currentName = $('#ofertaProgramaText').val().trim();
                        const gestion = $('#ofertaGestion').val() || new Date().getFullYear();
                        const version = $('#ofertaVersion').val() || 1;
                        const grupo = $('#ofertaGrupo').val() || 1;
                        const suffix = ' ' + gestion + ' V' + version + ' G' + grupo;
                        
                        if (currentName) {
                            let newName = currentName.replace(/( \d{4})? V\d+ G\d+$/, '');
                            $('#ofertaProgramaText').val(newName + suffix);
                        } else {
                            const posgradoNombre = $('#ofertasPosgradoNombre').text().trim();
                            if (posgradoNombre) {
                                $('#ofertaProgramaText').val(posgradoNombre + suffix);
                            }
                        }
                        checkOfertaPrograma();
                    }

                    $('#ofertaVersion, #ofertaGrupo, #ofertaGestion').on('input', function() {
                        generarNombrePrograma();
                    });

                    function initSearchableSelect(searchId, dropdownId, hiddenId, data, displayKey, subKey) {
                        const $search = $('#' + searchId);
                        const $dropdown = $('#' + dropdownId);
                        const $hidden = $('#' + hiddenId);
                        let activeIndex = -1;

                        function renderItems(filter) {
                            const f = filter.toLowerCase();
                            const filtered = data.filter(function(item) {
                                return item[displayKey].toLowerCase().indexOf(f) !== -1 || (item[subKey] && item[
                                    subKey].toLowerCase().indexOf(f) !== -1);
                            });
                            if (filtered.length === 0) {
                                $dropdown.html('<div class="searchable-dropdown-empty">Sin resultados</div>');
                            } else {
                                let html = '';
                                filtered.forEach(function(item, i) {
                                    const sub = item[subKey] ? '<span class="dd-sub">' + escHtml(item[subKey]) +
                                        '</span>' : '';
                                    html += '<div class="searchable-dropdown-item" data-index="' + i +
                                        '" data-id="' + item.id + '">' + escHtml(item[displayKey]) + sub + '</div>';
                                });
                                $dropdown.html(html);
                            }
                            activeIndex = -1;
                        }

                        $search.on('focus', function() {
                            renderItems($(this).val());
                            $dropdown.addClass('show');
                        });

                        $search.on('input', function() {
                            renderItems($(this).val());
                            $dropdown.addClass('show');
                            $hidden.val('');
                        });

                        $dropdown.on('click', '.searchable-dropdown-item', function() {
                            const id = $(this).data('id');
                            const text = $(this).clone().children().remove().end().text().trim();
                            $search.val(text);
                            $hidden.val(id);
                            $dropdown.removeClass('show');
                        });

                        $search.on('keydown', function(e) {
                            const items = $dropdown.find('.searchable-dropdown-item');
                            if (e.key === 'ArrowDown') {
                                e.preventDefault();
                                activeIndex = Math.min(activeIndex + 1, items.length - 1);
                                items.removeClass('active').eq(activeIndex).addClass('active');
                            } else if (e.key === 'ArrowUp') {
                                e.preventDefault();
                                activeIndex = Math.max(activeIndex - 1, 0);
                                items.removeClass('active').eq(activeIndex).addClass('active');
                            } else if (e.key === 'Enter') {
                                e.preventDefault();
                                if (activeIndex >= 0 && items.length > 0) {
                                    items.eq(activeIndex).click();
                                }
                            }
                        });

                        $(document).on('click', function(e) {
                            if (!$(e.target).closest('.' + 'searchable-select-wrap').length) {
                                $dropdown.removeClass('show');
                            }
                        });

                        return {
                            setValue: function(id, text) {
                                $hidden.val(id);
                                $search.val(text);
                            },
                            reset: function() {
                                $hidden.val('');
                                $search.val('');
                            }
                        };
                    }

                    let programaSelect, respAcademicoSelect, respMarketingSelect;

                    $(document).on('click', '.btn-action-ofertas', function(e) {
                        e.preventDefault();
                        const posgradoId = $(this).data('id');
                        const posgradoNombre = $(this).data('nombre');
                        currentPosgradoOfertas = posgradoId;
                        $('#ofertasPosgradoNombre').text(posgradoNombre);
                        openModal('modalOfertas');
                    });

                    $('#modalOfertas').on('shown.bs.modal', function() {
                        if (currentPosgradoOfertas) {
                            initOfertasTable(currentPosgradoOfertas);
                        }
                    });

                    $('#btnNuevaOferta').on('click', function() {
                        $('#formOferta')[0].reset();
                        $('#ofertaId').val('');
                        $('#ofertaPosgradoId').val(currentPosgradoOfertas);
                        $('#ofertaGestion').val(new Date().getFullYear());
                        $('#ofertaNModulos').val(1);
                        $('#ofertaCantSesiones').val(1);
                        $('#ofertaNotaMinima').val(61);
                        $('#ofertaVersion').val(1);
                        $('#ofertaGrupo').val(1);
                        $('#ofertaColor').val('#fc7b04');
                        $('#ofertaColorText').val('#fc7b04');
                        $('#ofertaColorPreview').css('background', '#fc7b04');
                        $('#fbOfertaCodigo').html('');
                        $('#fbOfertaFechaInsc').html('');
                        $('#fbOfertaFechaInicio').html('');
                        $('#fbOfertaFechaFin').html('');
                        $('#ofertaFormTitle').html(
                            '<i class="ri-book-open-line" style="color:#fc7b04;"></i> Nueva Oferta Académica');
                        $('#ofertaPrograma').val('');
                        $('#ofertaFase').val('');
                        if (faseNoAprobadoId) {
                            $('#ofertaFase').val(faseNoAprobadoId.id);
                        }
                        generarNombrePrograma();
                        if (respAcademicoSelect) respAcademicoSelect.reset();
                        if (respMarketingSelect) respMarketingSelect.reset();
                        resetFilePreview('portadaPreview', 'portadaUploadZone');
                        resetFilePreview('certificadoPreview', 'certificadoUploadZone');
                        portadaFile = null;
                        certificadoFile = null;
                        openModal('modalOfertaForm');
                    });

                    $(document).on('click', '.btn-edit-oferta', function() {
                        const btn = $(this);
                        const d = {
                            id: btn.data('id'),
                            codigo: btn.data('codigo'),
                            programa_id: btn.data('programa_id'),
                            programa_nombre: btn.data('programa_nombre'),
                            fase_id: btn.data('fase_id'),
                            modalidade_id: btn.data('modalidade_id'),
                            sucursale_id: btn.data('sucursale_id'),
                            gestion: btn.data('gestion'),
                            fecha_inicio_inscripciones: btn.data('fecha_inicio_inscripciones'),
                            fecha_inicio_programa: btn.data('fecha_inicio_programa'),
                            fecha_fin_programa: btn.data('fecha_fin_programa'),
                            n_modulos: btn.data('n_modulos'),
                            cantidad_sesiones: btn.data('cantidad_sesiones'),
                            nota_minima: btn.data('nota_minima'),
                            version: btn.data('version'),
                            grupo: btn.data('grupo'),
                            color: btn.data('color'),
                            responsable_academico_id: btn.data('responsable_academico_id'),
                            responsable_academico_nombre: btn.data('responsable_academico_nombre'),
                            responsable_marketing_id: btn.data('responsable_marketing_id'),
                            responsable_marketing_nombre: btn.data('responsable_marketing_nombre'),
                            portada: btn.data('portada'),
                            certificado: btn.data('certificado'),
                        };
                        $('#ofertaId').val(d.id);
                        $('#ofertaPosgradoId').val(currentPosgradoOfertas);
                        $('#ofertaCodigo').val(d.codigo);
                        $('#ofertaProgramaText').val(d.programa_nombre || '');
                        $('#ofertaPrograma').val(d.programa_id || '');
                        $('#ofertaFase').val(d.fase_id || '');
                        console.log('Edit oferta data:', d);
                        console.log('modalidade_id raw:', btn.data('modalidade_id'), 'type:', typeof btn.data(
                            'modalidade_id'));
                        $('#ofertaModalidad').val(String(d.modalidade_id || ''));
                        console.log('ofertaModalidad val after set:', $('#ofertaModalidad').val());
                        console.log('ofertaModalidad options:', $('#ofertaModalidad option').map(function() {
                            return {
                                val: this.value,
                                text: this.text
                            };
                        }).get());
                        $('#ofertaSucursal').val(d.sucursale_id || '');
                        $('#ofertaGestion').val(d.gestion || '');
                        $('#ofertaFechaInscripciones').val(d.fecha_inicio_inscripciones || '');
                        $('#ofertaFechaInicio').val(d.fecha_inicio_programa || '');
                        $('#ofertaFechaFin').val(d.fecha_fin_programa || '');
                        $('#ofertaNModulos').val(d.n_modulos || '');
                        $('#ofertaCantSesiones').val(d.cantidad_sesiones || '');
                        $('#ofertaNotaMinima').val(d.nota_minima || '');
                        $('#ofertaVersion').val(d.version || '');
                        $('#ofertaGrupo').val(d.grupo || '');
                        const color = d.color || '#fc7b04';
                        $('#ofertaColor').val(color);
                        $('#ofertaColorText').val(color);
                        $('#ofertaColorPreview').css('background', color);
                        if (respAcademicoSelect) respAcademicoSelect.setValue(d.responsable_academico_id || '', d
                            .responsable_academico_nombre || '');
                        if (respMarketingSelect) respMarketingSelect.setValue(d.responsable_marketing_id || '', d
                            .responsable_marketing_nombre || '');
                        $('#fbOfertaCodigo').html('');
                        $('#fbOfertaFechaInsc').html('');
                        $('#fbOfertaFechaInicio').html('');
                        $('#fbOfertaFechaFin').html('');
                        $('#ofertaFormTitle').html(
                            '<i class="ri-edit-2-line" style="color:#fc7b04;"></i> Editar Oferta Académica');

                        portadaFile = null;
                        certificadoFile = null;
                        if (d.portada) {
                            showExistingFile('portadaPreview', 'portadaUploadZone', d.portada, function() {
                                portadaFile = null;
                            });
                        } else {
                            resetFilePreview('portadaPreview', 'portadaUploadZone');
                        }
                        if (d.certificado) {
                            showExistingFile('certificadoPreview', 'certificadoUploadZone', d.certificado,
                                function() {
                                    certificadoFile = null;
                                });
                        } else {
                            resetFilePreview('certificadoPreview', 'certificadoUploadZone');
                        }
                        openModal('modalOfertaForm');
                    });

                    $(document).on('click', '.btn-delete-oferta', function() {
                        idEliminarOferta = $(this).data('id');
                        $('#ofertaEliminarCodigo').text($(this).data('codigo'));
                        openModal('modalEliminarOferta');
                    });

                    $('#btnConfirmarEliminarOferta').on('click', function() {
                        if (!idEliminarOferta) return;
                        eliminarOferta(idEliminarOferta);
                    });

                    $('#btnGuardarOferta').on('click', function() {
                        console.log('=== Click en Guardar Oferta ===');
                        guardarOferta();
                    });

                    $('#ofertaColor').on('input', function() {
                        const v = $(this).val();
                        $('#ofertaColorText').val(v);
                        $('#ofertaColorPreview').css('background', v);
                    });
                    $('#ofertaColorText').on('input', function() {
                        const v = $(this).val();
                        if (/^#[0-9A-Fa-f]{6}$/.test(v)) {
                            $('#ofertaColor').val(v);
                            $('#ofertaColorPreview').css('background', v);
                        }
                    });

                    // Date validation
                    $('#ofertaFechaInscripciones, #ofertaFechaInicio, #ofertaFechaFin').on('change', validateFechas);

                    function validateFechas() {
                        const insc = $('#ofertaFechaInscripciones').val();
                        const inicio = $('#ofertaFechaInicio').val();
                        const fin = $('#ofertaFechaFin').val();
                        let valid = true;

                        $('#fbOfertaFechaInsc').html('');
                        $('#fbOfertaFechaInicio').html('');
                        $('#fbOfertaFechaFin').html('');

                        if (insc && inicio && insc >= inicio) {
                            $('#fbOfertaFechaInsc').html(
                                '<span style="color:#ef4444;font-size:0.75rem;"><i class="ri-error-warning-line"></i>Debe ser menor al inicio del programa</span>'
                            );
                            valid = false;
                        }
                        if (inicio && insc && inicio <= insc) {
                            $('#fbOfertaFechaInicio').html(
                                '<span style="color:#ef4444;font-size:0.75rem;"><i class="ri-error-warning-line"></i>Debe ser mayor al inicio de inscripciones</span>'
                            );
                            valid = false;
                        }
                        if (inicio && fin && fin <= inicio) {
                            $('#fbOfertaFechaFin').html(
                                '<span style="color:#ef4444;font-size:0.75rem;"><i class="ri-error-warning-line"></i>Debe ser mayor al inicio del programa</span>'
                            );
                            valid = false;
                        }
                        if (fin && inicio && fin <= inicio) {
                            $('#fbOfertaFechaFin').html(
                                '<span style="color:#ef4444;font-size:0.75rem;"><i class="ri-error-warning-line"></i>Debe ser mayor al inicio del programa</span>'
                            );
                            valid = false;
                        }
                        return valid;
                    }

                    // File upload handlers
                    $('#ofertaPortada').on('change', function(e) {
                        handleFileSelect(e.target.files[0], 'portadaPreview', 'portadaUploadZone', function(file) {
                            portadaFile = file;
                        });
                    });

                    $('#ofertaCertificado').on('change', function(e) {
                        handleFileSelect(e.target.files[0], 'certificadoPreview', 'certificadoUploadZone', function(
                            file) {
                            certificadoFile = file;
                        });
                    });

                    // Drag and drop
                    ['portadaUploadZone', 'certificadoUploadZone'].forEach(function(zoneId) {
                        const zone = document.getElementById(zoneId);
                        if (!zone) return;
                        zone.addEventListener('dragover', function(e) {
                            e.preventDefault();
                            zone.style.borderColor = '#fc7b04';
                        });
                        zone.addEventListener('dragleave', function() {
                            zone.style.borderColor = '';
                        });
                        zone.addEventListener('drop', function(e) {
                            e.preventDefault();
                            zone.style.borderColor = '';
                            const inputId = zoneId === 'portadaUploadZone' ? 'ofertaPortada' :
                                'ofertaCertificado';
                            const previewId = zoneId === 'portadaUploadZone' ? 'portadaPreview' :
                                'certificadoPreview';
                            if (e.dataTransfer.files.length > 0) {
                                const file = e.dataTransfer.files[0];
                                document.getElementById(inputId).files = e.dataTransfer.files;
                                handleFileSelect(file, previewId, zoneId, function(f) {
                                    if (zoneId === 'portadaUploadZone') portadaFile = f;
                                    else certificadoFile = f;
                                });
                            }
                        });
                    });

                    function handleFileSelect(file, previewId, zoneId, callback) {
                        if (!file) return;
                        callback(file);
                        const $preview = $('#' + previewId);
                        const $zone = $('#' + zoneId);
                        $zone.addClass('has-file');

                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                $preview.html(
                                    '<img src="' + e.target.result + '" alt="Preview">' +
                                    '<span class="file-name">' + escHtml(file.name) + '</span>' +
                                    '<span class="file-remove" onclick="removeFile(\'' + previewId + '\', \'' +
                                    zoneId + '\', \'' + (zoneId === 'portadaUploadZone' ? 'portada' :
                                        'certificado') + '\')">Quitar</span>'
                                );
                            };
                            reader.readAsDataURL(file);
                        } else {
                            $preview.html(
                                '<i class="ri-file-pdf-line" style="font-size:2.5rem;color:#ef4444;"></i>' +
                                '<span class="file-name">' + escHtml(file.name) + '</span>' +
                                '<span class="file-remove" onclick="removeFile(\'' + previewId + '\', \'' + zoneId +
                                '\', \'' + (zoneId === 'portadaUploadZone' ? 'portada' : 'certificado') +
                                '\')">Quitar</span>'
                            );
                        }
                    }

                    function resetFilePreview(previewId, zoneId) {
                        const $preview = $('#' + previewId);
                        const $zone = $('#' + zoneId);
                        $zone.removeClass('has-file');
                        if (previewId === 'portadaPreview') {
                            $preview.html('<i class="ri-image-add-line"></i><span>Click o arrastra una imagen</span>');
                        } else {
                            $preview.html('<i class="ri-file-add-line"></i><span>Click o arrastra un archivo</span>');
                        }
                    }

                    function showExistingFile(previewId, zoneId, filePath, onRemoveCallback) {
                        const $preview = $('#' + previewId);
                        const $zone = $('#' + zoneId);
                        $zone.addClass('has-file');
                        const isImage = /\.(jpe?g|png|gif|webp)$/i.test(filePath);
                        const fileName = filePath.split('/').pop();
                        const fileUrl = '/storage/' + filePath;
                        if (isImage) {
                            $preview.html(
                                '<img src="' + fileUrl +
                                '" alt="Preview" style="max-width:100%;max-height:100px;border-radius:6px;object-fit:cover;">' +
                                '<span class="file-name">' + escHtml(fileName) + '</span>' +
                                '<span class="file-remove" onclick="removeExistingFile(\'' + previewId + '\', \'' +
                                zoneId + '\')">Quitar</span>'
                            );
                        } else {
                            $preview.html(
                                '<i class="ri-file-pdf-line" style="font-size:2.5rem;color:#ef4444;"></i>' +
                                '<span class="file-name">' + escHtml(fileName) + '</span>' +
                                '<span class="file-remove" onclick="removeExistingFile(\'' + previewId + '\', \'' +
                                zoneId + '\')">Quitar</span>'
                            );
                        }
                    }

                    window.removeExistingFile = function(previewId, zoneId) {
                        resetFilePreview(previewId, zoneId);
                        const inputId = zoneId === 'portadaUploadZone' ? 'ofertaPortada' : 'ofertaCertificado';
                        document.getElementById(inputId).value = '';
                        if (zoneId === 'portadaUploadZone') portadaFile = null;
                        else certificadoFile = null;
                    };

                    window.removeFile = function(previewId, zoneId, varName) {
                        if (varName === 'portada') portadaFile = null;
                        else certificadoFile = null;
                        const inputId = zoneId === 'portadaUploadZone' ? 'ofertaPortada' : 'ofertaCertificado';
                        document.getElementById(inputId).value = '';
                        resetFilePreview(previewId, zoneId);
                    };

                    document.getElementById('modalOfertas').addEventListener('hidden.bs.modal', function() {
                        if (tablaOfertas) {
                            tablaOfertas.destroy();
                            tablaOfertas = null;
                        }
                    });

                    document.getElementById('modalOfertaForm').addEventListener('hidden.bs.modal', function() {
                        $('#formOferta')[0].reset();
                    });

                    // Init searchable selects when modal opens
                    $('#modalOfertaForm').on('shown.bs.modal', function() {
                        if (!respAcademicoSelect) {
                            respAcademicoSelect = initSearchableSelect('ofertaRespAcademicoSearch',
                                'ofertaRespAcademicoDropdown', 'ofertaRespAcademico',
                                trabajadoresAcademicosData, 'nombre', 'cargo');
                        }
                        if (!respMarketingSelect) {
                            respMarketingSelect = initSearchableSelect('ofertaRespMarketingSearch',
                                'ofertaRespMarketingDropdown', 'ofertaRespMarketing', trabajadoresMarketingData,
                                'nombre', 'cargo');
                        }
                    });

                    function getOrCreatePrograma(nombre) {
                        const nombreUpper = nombre.toUpperCase();
                        const existing = programasData.find(function(p) {
                            return p.nombre.toUpperCase() === nombreUpper;
                        });
                        if (existing) {
                            return $.when({
                                id: existing.id,
                                nombre: existing.nombre
                            });
                        }
                        return $.post('null', {
                            _token: CSRF,
                            nombre: nombre
                        }).then(function(r) {
                            programasData.push({
                                id: r.data.id,
                                nombre: r.data.nombre
                            });
                            return {
                                id: r.data.id,
                                nombre: r.data.nombre
                            };
                        });
                    }

                    function initOfertasTable(posgradoId) {
                        if (tablaOfertas) {
                            tablaOfertas.destroy();
                            $('#tabla-ofertas tbody').empty();
                        }
                        tablaOfertas = $('#tabla-ofertas').DataTable({
                            ajax: {
                                url: '/admin/posgrads/' + posgradoId + '/ofertas/listar',
                                dataSrc: 'data',
                                error: function(xhr) {
                                    toast('error', 'Error al cargar las ofertas.');
                                }
                            },
                            ordering: true,
                            paging: false,
                            info: false,
                            columns: [{
                                    data: 'codigo',
                                    render: c => '<span style="font-weight:600;">' + escHtml(c) + '</span>'
                                },
                                {
                                    data: 'programa.nombre',
                                    render: d => d ? '<span class="badge-display">' + escHtml(d) + '</span>' :
                                        '<span class="text-muted">-</span>'
                                },
                                {
                                    data: 'fase.nombre',
                                    render: d => d ? '<span class="badge-display badge-info">' + escHtml(d) +
                                        '</span>' : '<span class="text-muted">-</span>'
                                },
                                {
                                    data: 'sucursal.nombre',
                                    render: d => d ? escHtml(d) : '<span class="text-muted">-</span>'
                                },
                                {
                                    data: 'modalidad.nombre',
                                    render: d => d ? escHtml(d) : '<span class="text-muted">-</span>'
                                },
                                {
                                    data: 'gestion',
                                    render: g => g ? '<span style="font-weight:500;">' + g + '</span>' : '-'
                                },
                                {
                                    data: 'fecha_inicio_inscripciones',
                                    render: d => d ? formatDate(d) : '-'
                                },
                                {
                                    data: null,
                                    className: 'text-center',
                                    render: d => '<div class="action-cell">' +
                                        '<a href="/admin/posgrads/ofertas/' + d.id +
                                        '/detalle" class="btn btn-action btn-action-view" title="Ver detalle" style="color:#0d6efd;"><i class="ri-eye-line"></i></a>' +
                                        '<button class="btn btn-action btn-modulos-oferta" data-id="' + d.id +
                                        '" data-n-modulos="' + (d.n_modulos || 0) + '" data-codigo="' + escHtml(
                                            d.codigo) +
                                        '" title="Gestionar Módulos" style="color:#6366f1;"><i class="ri-stack-line"></i></button>' +
                                        '<button class="btn btn-action btn-action-edit btn-edit-oferta" data-id="' +
                                        d.id + '" data-codigo="' + escHtml(d.codigo) + '" data-programa_id="' +
                                        (d.programa_id || '') + '" data-programa_nombre="' + escHtml(d
                                            .programa ? d.programa.nombre : '') + '" data-fase_id="' + (d
                                            .fase_id || '') + '" data-modalidade_id="' + (d.modalidade_id ||
                                            '') +
                                        '" data-sucursale_id="' + (d.sucursale_id || '') + '" data-gestion="' +
                                        (d.gestion || '') + '" data-fecha_inicio_inscripciones="' + (d
                                            .fecha_inicio_inscripciones || '') +
                                        '" data-fecha_inicio_programa="' + (d.fecha_inicio_programa || '') +
                                        '" data-fecha_fin_programa="' + (d.fecha_fin_programa || '') +
                                        '" data-n_modulos="' + (d.n_modulos || '') +
                                        '" data-cantidad_sesiones="' + (d.cantidad_sesiones || '') +
                                        '" data-nota_minima="' + (d.nota_minima || '') + '" data-version="' + (d
                                            .version || '') + '" data-grupo="' + (d.grupo || '') +
                                        '" data-color="' + (d.color || '#fc7b04') +
                                        '" data-responsable_academico_id="' + (d.responsable_academico_id ||
                                            '') +
                                        '" data-responsable_academico_nombre="' + escHtml(d
                                            .responsable_academico_nombre || '') +
                                        '" data-responsable_marketing_id="' +
                                        (d.responsable_marketing_id || '') +
                                        '" data-responsable_marketing_nombre="' + escHtml(d
                                            .responsable_marketing_nombre || '') + '" data-portada="' + (d
                                            .portada || '') + '" data-certificado="' + (d.certificado || '') +
                                        '" title="Editar oferta"><i class="ri-pencil-fill"></i></button>' +
                                        '<button class="btn btn-action btn-action-delete btn-delete-oferta" data-id="' +
                                        d.id + '" data-codigo="' + escHtml(d.codigo) +
                                        '" title="Eliminar oferta"><i class="ri-delete-bin-fill"></i></button>' +
                                        '</div>'
                                }
                            ],
                            language: {
                                processing: 'Procesando...',
                                loadingRecords: 'Cargando...',
                                zeroRecords: 'No se encontraron registros',
                                emptyTable: 'No hay ofertas registradas'
                            },
                            order: [
                                [0, 'asc']
                            ]
                        });
                    }

                    function formatDate(dateStr) {
                        if (!dateStr) return '-';
                        const parts = dateStr.split('-');
                        if (parts.length === 3) return parts[2] + '/' + parts[1] + '/' + parts[0];
                        return dateStr;
                    }

                    // Real-time validation feedback
                    function setFieldFeedback(fieldId, feedbackId, isValid, message) {
                        const $fb = $('#' + feedbackId);
                        if (isValid) {
                            $fb.html('<span style="color:#22c55e;font-size:0.75rem;"><i class="ri-check-line"></i> ' +
                                message + '</span>');
                        } else {
                            $fb.html(
                                '<span style="color:#ef4444;font-size:0.75rem;"><i class="ri-error-warning-line"></i> ' +
                                message + '</span>');
                        }
                    }

                    $('#ofertaCodigo').on('input', function() {
                        const v = $(this).val().trim();
                        if (!v) setFieldFeedback('ofertaCodigo', 'fbOfertaCodigo', false,
                            'El código es obligatorio.');
                        else if (v.length < 2) setFieldFeedback('ofertaCodigo', 'fbOfertaCodigo', false,
                            'Mínimo 2 caracteres.');
                        else setFieldFeedback('ofertaCodigo', 'fbOfertaCodigo', true, 'Código válido.');
                    });

                    $('#ofertaProgramaText').on('input', function() {
                        checkOfertaPrograma();
                    });

                    $('#ofertaGestion').on('input', function() {
                        const v = parseInt($(this).val());
                        if (!v || v < 2000) setFieldFeedback('ofertaGestion', 'fbOfertaGestion', false,
                            'Gestión debe ser ≥ 2000.');
                        else setFieldFeedback('ofertaGestion', 'fbOfertaGestion', true, 'Gestión válida.');
                    });

                    $('#ofertaFechaInscripciones, #ofertaFechaInicio, #ofertaFechaFin').on('change', function() {
                        validateFechas();
                    });

                    $('#ofertaNModulos').on('input', function() {
                        const v = parseInt($(this).val());
                        if (!v || v < 1) setFieldFeedback('ofertaNModulos', 'fbOfertaNModulos', false,
                            'Mínimo 1 módulo.');
                        else setFieldFeedback('ofertaNModulos', 'fbOfertaNModulos', true, 'Válido.');
                    });

                    $('#ofertaCantSesiones').on('input', function() {
                        const v = parseInt($(this).val());
                        if (!v || v < 1) setFieldFeedback('ofertaCantSesiones', 'fbOfertaCantSesiones', false,
                            'Mínimo 1 sesión.');
                        else setFieldFeedback('ofertaCantSesiones', 'fbOfertaCantSesiones', true, 'Válido.');
                    });

                    $('#ofertaNotaMinima').on('input', function() {
                        const v = parseFloat($(this).val());
                        if (isNaN(v) || v < 0 || v > 100) setFieldFeedback('ofertaNotaMinima', 'fbOfertaNotaMinima',
                            false, 'Debe ser entre 0 y 100.');
                        else setFieldFeedback('ofertaNotaMinima', 'fbOfertaNotaMinima', true, 'Válido.');
                    });

                    function validateAllFields() {
                        let valid = true;
                        const codigo = $('#ofertaCodigo').val().trim();
                        const programa = $('#ofertaProgramaText').val().trim();
                        const gestion = parseInt($('#ofertaGestion').val());
                        const fase = $('#ofertaFase').val();
                        const insc = $('#ofertaFechaInscripciones').val();
                        const inicio = $('#ofertaFechaInicio').val();
                        const fin = $('#ofertaFechaFin').val();
                        const modulos = parseInt($('#ofertaNModulos').val());
                        const sesiones = parseInt($('#ofertaCantSesiones').val());
                        const nota = parseFloat($('#ofertaNotaMinima').val());

                        if (!codigo || codigo.length < 2) {
                            setFieldFeedback('ofertaCodigo', 'fbOfertaCodigo', false,
                                'El código es obligatorio (mín. 2 caracteres).');
                            valid = false;
                        } else {
                            setFieldFeedback('ofertaCodigo', 'fbOfertaCodigo', true, 'Código válido.');
                        }

                        if (!programa || programa.length < 2) {
                            setFieldFeedback('ofertaProgramaText', 'fbOfertaPrograma', false,
                                'El programa es obligatorio.');
                            valid = false;
                        } else {
                            setFieldFeedback('ofertaProgramaText', 'fbOfertaPrograma', true, 'Programa válido.');
                        }

                        if (!gestion || gestion < 2000) {
                            setFieldFeedback('ofertaGestion', 'fbOfertaGestion', false, 'Gestión debe ser ≥ 2000.');
                            valid = false;
                        } else {
                            setFieldFeedback('ofertaGestion', 'fbOfertaGestion', true, 'Gestión válida.');
                        }

                        if (!fase) {
                            setFieldFeedback('ofertaFase', 'fbOfertaFase', false, 'Fase obligatoria.');
                            valid = false;
                        }

                        if (!insc) {
                            setFieldFeedback('ofertaFechaInscripciones', 'fbOfertaFechaInsc', false, 'Fecha obligatoria.');
                            valid = false;
                        }
                        if (!inicio) {
                            setFieldFeedback('ofertaFechaInicio', 'fbOfertaFechaInicio', false, 'Fecha obligatoria.');
                            valid = false;
                        }
                        if (!fin) {
                            setFieldFeedback('ofertaFechaFin', 'fbOfertaFechaFin', false, 'Fecha obligatoria.');
                            valid = false;
                        }

                        if (insc && inicio && insc >= inicio) {
                            setFieldFeedback('ofertaFechaInscripciones', 'fbOfertaFechaInsc', false,
                                'Debe ser menor al inicio del programa.');
                            valid = false;
                        }
                        if (inicio && insc && inicio <= insc) {
                            setFieldFeedback('ofertaFechaInicio', 'fbOfertaFechaInicio', false,
                                'Debe ser mayor a inscripciones.');
                            valid = false;
                        }
                        if (fin && inicio && fin <= inicio) {
                            setFieldFeedback('ofertaFechaFin', 'fbOfertaFechaFin', false, 'Debe ser mayor al inicio.');
                            valid = false;
                        }

                        if (!modulos || modulos < 1) {
                            setFieldFeedback('ofertaNModulos', 'fbOfertaNModulos', false, 'Mínimo 1 módulo.');
                            valid = false;
                        } else {
                            setFieldFeedback('ofertaNModulos', 'fbOfertaNModulos', true, 'Válido.');
                        }
                        if (!sesiones || sesiones < 1) {
                            setFieldFeedback('ofertaCantSesiones', 'fbOfertaCantSesiones', false, 'Mínimo 1 sesión.');
                            valid = false;
                        } else {
                            setFieldFeedback('ofertaCantSesiones', 'fbOfertaCantSesiones', true, 'Válido.');
                        }
                        if (isNaN(nota) || nota < 0 || nota > 100) {
                            setFieldFeedback('ofertaNotaMinima', 'fbOfertaNotaMinima', false, 'Entre 0 y 100.');
                            valid = false;
                        } else {
                            setFieldFeedback('ofertaNotaMinima', 'fbOfertaNotaMinima', true, 'Válido.');
                        }

                        return valid;
                    }

                    function guardarOferta() {
                        console.log('guardarOferta() llamada');
                        const validacion = validateAllFields();
                        console.log('validateAllFields:', validacion);
                        if (!validacion) {
                            toast('warning', 'Completa todos los campos obligatorios correctamente.');
                            return;
                        }

                        const programaNombre = $('#ofertaProgramaText').val().trim();
                        const version = $('#ofertaVersion').val();
                        const grupo = $('#ofertaGrupo').val();
                        const posgradoId = $('#ofertaPosgradoId').val();
                        const id = $('#ofertaId').val();
                        const isEdit = id !== '';

                        $.ajax({
                            url: 'null',
                            type: 'POST',
                            data: {
                                _token: CSRF,
                                posgrado_id: posgradoId,
                                programa_nombre: programaNombre,
                                version: version,
                                grupo: grupo,
                                exclude_id: isEdit ? id : null
                            }
                        }).done(function(r) {
                            if (r.existe) {
                                setBtnLoading('#btnGuardarOferta', false, '<i class="ri-save-line"></i> Guardar');
                                setFieldFeedback('ofertaProgramaText', 'fbOfertaPrograma', false,
                                    'Ya existe una oferta con este programa, versión y grupo.');
                                toast('error', 'Ya existe una oferta con este programa, versión y grupo.');
                                return;
                            }
                            proceedToSave();
                        });

                        function proceedToSave() {
                            console.log('programa:', programaNombre, 'isEdit:', isEdit, 'id:', id);
                            const btnSel = '#btnGuardarOferta';
                            setBtnLoading(btnSel, true, 'Guardando…');

                            console.log('Llamando getOrCreatePrograma...');
                            getOrCreatePrograma(programaNombre).done(function(progResult) {
                                console.log('getOrCreatePrograma done:', progResult);
                                const formData = new FormData();
                                formData.append('_token', CSRF);
                                formData.append('codigo', $('#ofertaCodigo').val().trim());
                                formData.append('posgrado_id', $('#ofertaPosgradoId').val());
                                formData.append('programa_id', progResult.id);
                                formData.append('fase_id', $('#ofertaFase').val());
                                formData.append('modalidade_id', $('#ofertaModalidad').val());
                                formData.append('sucursale_id', $('#ofertaSucursal').val());
                                formData.append('fecha_inicio_inscripciones', $('#ofertaFechaInscripciones').val());
                                formData.append('fecha_inicio_programa', $('#ofertaFechaInicio').val());
                                formData.append('fecha_fin_programa', $('#ofertaFechaFin').val());
                                formData.append('gestion', $('#ofertaGestion').val());
                                formData.append('n_modulos', $('#ofertaNModulos').val());
                                formData.append('cantidad_sesiones', $('#ofertaCantSesiones').val());
                                formData.append('version', $('#ofertaVersion').val());
                                formData.append('grupo', $('#ofertaGrupo').val());
                                formData.append('nota_minima', $('#ofertaNotaMinima').val());
                                formData.append('color', $('#ofertaColor').val());
                                formData.append('responsable_academico_id', $('#ofertaRespAcademico').val());
                                formData.append('responsable_marketing_id', $('#ofertaRespMarketing').val());
                                if (portadaFile) formData.append('portada', portadaFile);
                                if (certificadoFile) formData.append('certificado', certificadoFile);

                                $.ajax({
                                        url: isEdit ? '/admin/posgrads/ofertas/' + id :
                                            'null',
                                        type: 'POST',
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        headers: {
                                            'X-HTTP-Method-Override': isEdit ? 'PUT' : 'POST'
                                        }
                                    })
                                    .done(function(r) {
                                        closeModal('modalOfertaForm');
                                        if (tablaOfertas) tablaOfertas.ajax.reload();
                                        toast('success', r.message || (isEdit ? 'Oferta actualizada.' :
                                            'Oferta registrada.'));
                                    })
                                    .fail(function(xhr) {
                                        console.log('Error response:', xhr);
                                        console.log('Errors JSON:', xhr.responseJSON);
                                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                            const errs = xhr.responseJSON.errors;
                                            console.log('Validation errors:', errs);
                                            let allErrors = [];
                                            if (errs.codigo) {
                                                setFieldFeedback('ofertaCodigo', 'fbOfertaCodigo', false, errs
                                                    .codigo[0]);
                                                allErrors.push(errs.codigo[0]);
                                            }
                                            if (errs.programa_id) {
                                                toast('error', errs.programa_id[0]);
                                                allErrors.push(errs.programa_id[0]);
                                            }
                                            if (errs.fase_id) {
                                                toast('error', errs.fase_id[0]);
                                                allErrors.push(errs.fase_id[0]);
                                            }
                                            if (errs.fecha_inicio_inscripciones) {
                                                setFieldFeedback('ofertaFechaInscripciones',
                                                    'fbOfertaFechaInsc',
                                                    false, errs.fecha_inicio_inscripciones[0]);
                                                allErrors.push(errs.fecha_inicio_inscripciones[0]);
                                            }
                                            if (errs.fecha_inicio_programa) {
                                                setFieldFeedback('ofertaFechaInicio', 'fbOfertaFechaInicio',
                                                    false,
                                                    errs.fecha_inicio_programa[0]);
                                                allErrors.push(errs.fecha_inicio_programa[0]);
                                            }
                                            if (errs.fecha_fin_programa) {
                                                setFieldFeedback('ofertaFechaFin', 'fbOfertaFechaFin', false,
                                                    errs
                                                    .fecha_fin_programa[0]);
                                                allErrors.push(errs.fecha_fin_programa[0]);
                                            }
                                            if (errs.gestion) {
                                                setFieldFeedback('ofertaGestion', 'fbOfertaGestion', false, errs
                                                    .gestion[0]);
                                                allErrors.push(errs.gestion[0]);
                                            }
                                            if (errs.n_modulos) {
                                                setFieldFeedback('ofertaNModulos', 'fbOfertaNModulos', false,
                                                    errs
                                                    .n_modulos[0]);
                                                allErrors.push(errs.n_modulos[0]);
                                            }
                                            if (errs.cantidad_sesiones) {
                                                setFieldFeedback('ofertaCantSesiones', 'fbOfertaCantSesiones',
                                                    false, errs.cantidad_sesiones[0]);
                                                allErrors.push(errs.cantidad_sesiones[0]);
                                            }
                                            if (errs.nota_minima) {
                                                setFieldFeedback('ofertaNotaMinima', 'fbOfertaNotaMinima',
                                                    false,
                                                    errs.nota_minima[0]);
                                                allErrors.push(errs.nota_minima[0]);
                                            }
                                            if (errs.posgrado_id) {
                                                allErrors.push('posgrado_id: ' + errs.posgrado_id[0]);
                                            }
                                            if (errs.version) {
                                                allErrors.push('version: ' + errs.version[0]);
                                            }
                                            if (errs.grupo) {
                                                allErrors.push('grupo: ' + errs.grupo[0]);
                                            }
                                            if (errs.color) {
                                                allErrors.push('color: ' + errs.color[0]);
                                            }
                                            if (errs.responsable_academico_id) {
                                                allErrors.push('responsable_academico_id: ' + errs
                                                    .responsable_academico_id[0]);
                                            }
                                            if (errs.responsable_marketing_id) {
                                                allErrors.push('responsable_marketing_id: ' + errs
                                                    .responsable_marketing_id[0]);
                                            }
                                            if (errs.portada) {
                                                allErrors.push('portada: ' + errs.portada[0]);
                                            }
                                            if (errs.certificado) {
                                                allErrors.push('certificado: ' + errs.certificado[0]);
                                            }
                                            if (allErrors.length > 0) {
                                                toast('error', 'Errores de validación: ' + allErrors.join(
                                                    ' | '));
                                            }
                                        } else {
                                            console.log('Full error:', xhr.responseText);
                                            toast('error', 'Error: ' + (xhr.responseJSON && xhr.responseJSON
                                                .message ? xhr.responseJSON.message :
                                                'Intente nuevamente.'
                                            ));
                                        }
                                    })
                                    .always(function() {
                                        setBtnLoading(btnSel, false, '<i class="ri-save-line"></i> Guardar');
                                    });
                            }).fail(function(xhr) {
                                console.log('Programa error:', xhr);
                                toast('error', 'No se pudo registrar el programa. ' + (xhr.responseJSON && xhr
                                    .responseJSON.errors && xhr.responseJSON.errors.nombre ? xhr
                                    .responseJSON
                                    .errors.nombre[0] : ''));
                                setBtnLoading(btnSel, false, '<i class="ri-save-line"></i> Guardar');
                            });
                        }
                    }

                    function eliminarOferta(id) {
                        setBtnLoading('#btnConfirmarEliminarOferta', true,
                                '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
                            $.ajax({
                                    url: '/admin/posgrads/ofertas/' + id,
                                    type: 'DELETE',
                                    data: {
                                        _token: CSRF
                                    }
                                })
                                .done(r => {
                                    closeModal('modalEliminarOferta');
                                    if (tablaOfertas) tablaOfertas.ajax.reload();
                                    toast('success', r.message || 'Oferta eliminada.');
                                })
                                .fail(xhr => {
                                    const msg = xhr.responseJSON ? xhr.responseJSON.message : 'No se pudo eliminar.';
                                    toast(xhr.status === 400 ? 'warning' : 'error', msg);
                                })
                                .always(() => {
                                    setBtnLoading('#btnConfirmarEliminarOferta', false,
                                        '<i class="ri-delete-bin-line"></i> Eliminar');
                                    idEliminarOferta = null;
                                });
                        }
                    }

                    function verificarNombre(inputId, iconId, fbId, idPosgrado, btnId) {
                        const input = document.getElementById(inputId);
                        const icon = document.getElementById(iconId);
                        const fb = document.getElementById(fbId);
                        const val = input.value.trim();
                        if (val.length < 2) {
                            input.classList.remove('is-valid');
                            input.classList.add('is-invalid');
                            icon.className = 'validation-icon invalid';
                            icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                            fb.className = 'field-feedback error';
                            fb.innerHTML = '<i class="ri-error-warning-line"></i>Debe tener al menos 2 caracteres.';
                            $(btnId).prop('disabled', true);
                            return;
                        }
                        $.ajax({
                            url: 'null',
                            type: 'POST',
                            data: {
                                _token: CSRF,
                                nombre: val,
                                id: idPosgrado || null
                            },
                            success: function(r) {
                                if (r.existe) {
                                    input.classList.remove('is-valid');
                                    input.classList.add('is-invalid');
                                    icon.className = 'validation-icon invalid';
                                    icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                                    fb.className = 'field-feedback error';
                                    fb.innerHTML =
                                        '<i class="ri-error-warning-line"></i>Este posgrado ya existe.';
                                    $(btnId).prop('disabled', true);
                                } else {
                                    input.classList.remove('is-invalid');
                                    input.classList.add('is-valid');
                                    icon.className = 'validation-icon valid';
                                    icon.innerHTML = '<i class="ri-checkbox-circle-fill"></i>';
                                    fb.className = 'field-feedback success';
                                    fb.innerHTML = '<i class="ri-check-line"></i>Nombre disponible';
                                    $(btnId).prop('disabled', false);
                                }
                            },
                            error: function() {
                                $(btnId).prop('disabled', true);
                            }
                        });
                    }

                    function resetField(inputId, iconId, fbId) {
                        const input = document.getElementById(inputId);
                        input.classList.remove('is-valid', 'is-invalid');
                        document.getElementById(iconId).className = 'validation-icon';
                        document.getElementById(iconId).innerHTML = '';
                        document.getElementById(fbId).className = 'field-feedback';
                        document.getElementById(fbId).innerHTML = '';
                    }

                    function guardar() {
                        if ($('#btnGuardar').prop('disabled')) return;
                        setBtnLoading('#btnGuardar', true, 'Guardando…');
                        $.ajax({
                                url: 'null',
                                type: 'POST',
                                data: {
                                    _token: CSRF,
                                    nombre: $('#nombreCrear').val().trim(),
                                    creditaje: $('#creditajeCrear').val() || 0,
                                    carga_horaria: $('#cargaHorariaCrear').val() || 0,
                                    duracion_numero: $('#duracionNumeroCrear').val() || 0,
                                    duracion_unidad: $('#duracionUnidadCrear').val(),
                                    dirigido: $('#dirigidoCrear').val().trim(),
                                    objetivo: $('#objetivoCrear').val().trim(),
                                    estado: $('#estadoCrear').is(':checked') ? 1 : 0,
                                    convenio_id: $('#convenioCrear').val(),
                                    area_id: $('#areaCrear').val(),
                                    tipo_id: $('#tipoCrear').val()
                                }
                            })
                            .done(r => {
                                closeModal('modalCrear');
                                tabla.ajax.reload();
                                toast('success', r.message || 'Posgrado guardado correctamente.');
                            })
                            .fail(xhr => handleAjaxError(xhr, 'nombreCrear', 'iconCrear', 'fbCrear'))
                            .always(() => setBtnLoading('#btnGuardar', false, '<i class="ri-save-line"></i> Guardar'));
                    }

                    function actualizar() {
                        if ($('#btnActualizar').prop('disabled')) return;
                        const id = $('#idEditar').val();
                        setBtnLoading('#btnActualizar', true, 'Actualizando…');
                        $.ajax({
                                url: '/admin/posgrads/' + id,
                                type: 'PUT',
                                data: {
                                    _token: CSRF,
                                    nombre: $('#nombreEditar').val().trim(),
                                    creditaje: $('#creditajeEditar').val() || 0,
                                    carga_horaria: $('#cargaHorariaEditar').val() || 0,
                                    duracion_numero: $('#duracionNumeroEditar').val() || 0,
                                    duracion_unidad: $('#duracionUnidadEditar').val(),
                                    dirigido: $('#dirigidoEditar').val().trim(),
                                    objetivo: $('#objetivoEditar').val().trim(),
                                    estado: $('#estadoEditar').is(':checked') ? 1 : 0,
                                    convenio_id: $('#convenioEditar').val(),
                                    area_id: $('#areaEditar').val(),
                                    tipo_id: $('#tipoEditar').val()
                                }
                            })
                            .done(r => {
                                closeModal('modalEditar');
                                tabla.ajax.reload();
                                toast('success', r.message || 'Posgrado actualizado correctamente.');
                            })
                            .fail(xhr => handleAjaxError(xhr, 'nombreEditar', 'iconEditar', 'fbEditar'))
                            .always(() => setBtnLoading('#btnActualizar', false,
                                '<i class="ri-refresh-line"></i> Actualizar'));
                    }

                    function eliminarPosgrado(id) {
                        setBtnLoading('#btnConfirmarEliminar', true,
                            '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
                        $.ajax({
                                url: '/admin/posgrads/' + id,
                                type: 'DELETE',
                                data: {
                                    _token: CSRF
                                }
                            })
                            .done(r => {
                                closeModal('modalEliminar');
                                tabla.ajax.reload();
                                toast('success', r.message || 'Posgrado eliminado correctamente.');
                            })
                            .fail(xhr => {
                                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'No se pudo eliminar.';
                                toast(xhr.status === 400 ? 'warning' : 'error', msg);
                            })
                            .always(() => {
                                setBtnLoading('#btnConfirmarEliminar', false,
                                    '<i class="ri-delete-bin-line"></i> Eliminar');
                                idEliminar = null;
                            });
                    }

                    function handleAjaxError(xhr, inputId, iconId, fbId) {
                        if (xhr.status === 422) {
                            const errs = xhr.responseJSON.errors || {};
                            if (errs.nombre) {
                                const input = document.getElementById(inputId);
                                const icon = document.getElementById(iconId);
                                const fb = document.getElementById(fbId);
                                input.classList.remove('is-valid');
                                input.classList.add('is-invalid');
                                icon.className = 'validation-icon invalid';
                                icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                                fb.className = 'field-feedback error';
                                fb.innerHTML = '<i class="ri-error-warning-line"></i>' + errs.nombre[0];
                            }
                        } else {
                            toast('error', 'Ocurrió un error. Intente nuevamente.');
                        }
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
                            btn.innerHTML = labelHtml;
                        }
                    }

                    function openModal(id) {
                        new bootstrap.Modal(document.getElementById(id)).show();
                    }

                    function closeModal(id) {
                        const el = document.getElementById(id);
                        const m = bootstrap.Modal.getInstance(el);
                        if (m) m.hide();
                    }

                    function escHtml(str) {
                        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g,
                            '&quot;');
                    }

                    function getToastContainer() {
                        let c = document.getElementById('toastContainer');
                        if (c && c.parentElement !== document.body) {
                            document.body.appendChild(c);
                        }
                        return c;
                    }

                    function toast(tipo, mensaje) {
                        const iconMap = {
                            success: 'ri-check-double-line',
                            error: 'ri-close-circle-line',
                            warning: 'ri-alert-line'
                        };
                        const el = document.createElement('div');
                        el.className = 'toast-notify ' + tipo;
                        el.innerHTML = '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') +
                            '"></i></div>' + '<div class="toast-body-text"><span>' + mensaje + '</span></div>' +
                            '<button class="toast-close" title="Cerrar"><i class="ri-close-line"></i></button>';
                        const container = getToastContainer();
                        const updatePosition = () => {
                            container.style.top = Math.max(20, window.scrollY + 20) + 'px';
                        };
                        container.style.transition = 'top 0.3s ease';
                        updatePosition();
                        if (!container._scrollListenerAttached) {
                            container._scrollListenerAttached = true;
                            let scrollTimeout;
                            window.addEventListener('scroll', () => {
                                clearTimeout(scrollTimeout);
                                scrollTimeout = setTimeout(updatePosition, 10);
                            });
                        }
                        container.appendChild(el);
                        el.querySelector('.toast-close').addEventListener('click', () => removeToast(el));
                        setTimeout(() => removeToast(el), 4500);
                    }

                    function removeToast(el) {
                        el.classList.add('hiding');
                        el.addEventListener('animationend', () => el.remove(), {
                            once: true
                        });
                    }

                    $(document).ready(init);
                })();
    null