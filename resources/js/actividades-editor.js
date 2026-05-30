/**
 * ActividadesEditor — CRUD de secciones y actividades del curso Moodle.
 * Se carga desde modulo-detalle.blade.php.
 * Dependencias globales: Sortable (sortablejs), ClassicEditor (CKEditor 5).
 */
var ActividadesEditor = (function () {
    'use strict';

    var moduloId = null;
    var moodleUrl = '';
    var sortableInstances = [];

    var ICONS = {
        assign:   { cls: 'assign',   icon: 'ri-task-line',          label: 'Tarea' },
        quiz:     { cls: 'quiz',     icon: 'ri-questionnaire-line', label: 'Cuestionario' },
        forum:    { cls: 'forum',    icon: 'ri-discuss-line',       label: 'Foro' },
        resource: { cls: 'resource', icon: 'ri-file-line',          label: 'Recurso' },
        url:      { cls: 'url',      icon: 'ri-link',               label: 'URL' },
        page:     { cls: 'page',     icon: 'ri-file-text-line',     label: 'Página' },
        default:  { cls: 'default',  icon: 'ri-apps-line',          label: 'Actividad' },
    };

    function escHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function fmtTsAdmin(ts) {
        if (!ts) return '';
        var d = new Date(ts * 1000);
        var dd  = String(d.getDate()).padStart(2,'0');
        var mm  = String(d.getMonth()+1).padStart(2,'0');
        var yy  = d.getFullYear();
        var hh  = String(d.getHours()).padStart(2,'0');
        var min = String(d.getMinutes()).padStart(2,'0');
        return dd + '/' + mm + '/' + yy + ' ' + hh + ':' + min;
    }

    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function jsonHeaders() {
        return {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken(),
        };
    }

    function formHeaders() {
        return {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken(),
        };
    }

    function getActiveSectionId() {
        var sel = document.getElementById('sectionSelector');
        if (sel && sel.value) return parseInt(sel.value);
        var firstCard = document.querySelector('#seccionesContainer .seccion-card');
        if (firstCard) return parseInt(firstCard.getAttribute('data-section-id') || '0');
        return 0;
    }

    function getCourseId() {
        var dataEl = document.getElementById('actividadesEditorData');
        var cid = dataEl ? dataEl.getAttribute('data-course-id') : '0';
        return parseInt(cid) || 0;
    }

    function getModuloId() {
        if (moduloId) return moduloId;
        var dataEl = document.getElementById('actividadesEditorData');
        var mid = dataEl ? dataEl.getAttribute('data-modulo-id') : '0';
        moduloId = parseInt(mid) || 0;
        return moduloId;
    }

    function getApiBase() {
        var dataEl = document.getElementById('actividadesEditorData');
        return (dataEl && dataEl.getAttribute('data-api-base')) || '/admin/posgrads/modulos';
    }

    function mostrarToast(tipo, mensaje) {
        var container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.style.cssText = 'position:fixed;top:1rem;right:1rem;z-index:99999;display:flex;flex-direction:column;gap:0.5rem;';
            document.body.appendChild(container);
        }
        var toast = document.createElement('div');
        toast.style.cssText = 'display:flex;align-items:center;gap:0.6rem;padding:0.7rem 1rem;background:#fff;border-radius:8px;box-shadow:0 4px 20px rgba(0,0,0,0.12);font-size:0.82rem;font-weight:500;color:#495057;animation:slideIn 0.3s ease;border-left:4px solid ' + (tipo === 'success' ? '#16a34a' : '#dc2626') + ';';
        toast.innerHTML = '<i class="ri-' + (tipo === 'success' ? 'checkbox-circle' : 'error-warning') + '-line" style="color:' + (tipo === 'success' ? '#16a34a' : '#dc2626') + ';font-size:1.1rem;"></i> ' + mensaje;
        container.appendChild(toast);
        setTimeout(function () { if (toast.parentNode) toast.remove(); }, 3000);
    }

    // ============================================================
    // CARGA Y RENDERIZADO
    // ============================================================

    function cargarYRenderizar() {
        var id = getModuloId();
        if (!id) return;

        document.getElementById('actLoading').style.display = 'block';
        document.getElementById('actContenido').style.display = 'none';
        document.getElementById('actResumen').style.display = 'none';
        document.getElementById('actVacio').style.display = 'none';
        document.getElementById('actError').style.display = 'none';

        fetch(getApiBase() + '/' + id + '/actividades', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken() }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            document.getElementById('actLoading').style.display = 'none';

            if (!data.success) {
                document.getElementById('actErrorMsg').textContent = data.message || 'Error desconocido.';
                document.getElementById('actError').style.display = 'block';
                return;
            }

            moodleUrl = data.moodle_url || '';

            var secciones = data.secciones || [];
            var foros = data.foros || [];

            document.getElementById('cntSecciones').textContent = secciones.length;
            document.getElementById('cntTareas').textContent = (data.tareas || []).length;
            document.getElementById('cntCuestionarios').textContent = (data.cuestionarios || []).length;
            document.getElementById('cntForos').textContent = foros.length;
            document.getElementById('actResumen').style.display = 'grid';

            renderSecciones(secciones, data.tareas || [], data.cuestionarios || [], data.foros || [], data.tareas_fechas || {});
            renderForos(foros);
            habilitarDragDrop();

            var totalActs = secciones.reduce(function (sum, sec) {
                return sum + (sec.modules || []).filter(function (m) { return m.modname !== 'label'; }).length;
            }, 0);

            if (totalActs === 0 && secciones.length === 0) {
                document.getElementById('actVacio').style.display = 'block';
            } else {
                document.getElementById('actContenido').style.display = 'block';
            }
        })
        .catch(function () {
            document.getElementById('actLoading').style.display = 'none';
            document.getElementById('actErrorMsg').textContent = 'Error de conexión con el servidor.';
            document.getElementById('actError').style.display = 'block';
        });
    }

    function tsToDateInput(ts) {
        if (!ts) return '';
        var d = new Date(ts * 1000);
        return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0') + 'T' + String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0');
    }

    // Convierte el valor de un <input type="datetime-local"> a timestamp Unix
    function dateValToTs(val) {
        if (!val) return 0;
        var d = new Date(val);
        return Math.floor(d.getTime() / 1000);
    }

    function renderSecciones(secciones, tareas, cuestionarios, foros, tareasFechas) {
        tareasFechas = tareasFechas || {};
        var container = document.getElementById('seccionesContainer');
        container.innerHTML = '';

        // Build lookup maps by instance ID and by cmid (fallback when mod.instance is absent)
        var tareasMap = {}, tareasByCmid = {};
        (tareas || []).forEach(function (t) {
            if (t.id)            tareasMap[t.id]              = t;
            if (t.cmid)          tareasByCmid[t.cmid]          = t;
            if (t.coursemodule)  tareasByCmid[t.coursemodule]  = t;
        });
        var quizzesMap = {}, quizzesByCmid = {};
        (cuestionarios || []).forEach(function (q) {
            if (q.id)           quizzesMap[q.id]              = q;
            if (q.cmid)         quizzesByCmid[q.cmid]          = q;
            if (q.coursemodule) quizzesByCmid[q.coursemodule]  = q;
        });
        var forosMap = {}, forosByCmid = {};
        (foros || []).forEach(function (f) {
            if (f.id)            forosMap[f.id]            = f;
            if (f.cmid)          forosByCmid[f.cmid]        = f;
            if (f.coursemodule)  forosByCmid[f.coursemodule] = f;
        });

        secciones.forEach(function (sec, idx) {
            var allMods = sec.modules || [];
            var actMods = allMods.filter(function (m) { return m.modname !== 'label'; });

            var card = document.createElement('div');
            card.className = 'seccion-card';
            card.setAttribute('data-section-id', sec.id);

            var tieneDesc = sec.description && sec.description.trim().length > 0;

            var hdr = document.createElement('div');
            hdr.className = 'seccion-hdr' + (idx === 0 ? ' open' : '');
            hdr.innerHTML =
                '<div class="seccion-hdr-left">' +
                    '<span class="drag-handle"><i class="ri-draggable"></i></span>' +
                    '<span class="seccion-nombre">' +
                        '<i class="ri-layout-line"></i> ' +
                        '<span class="sec-name-text">' + escHtml(sec.name || 'Sección ' + (idx + 1)) + '</span>' +
                        ' <small>(' + actMods.length + ' actividad' + (actMods.length !== 1 ? 'es' : '') + ')</small>' +
                    '</span>' +
                '</div>' +
                '<div class="seccion-hdr-actions">' +
                    '<button class="btn-icon btn-rename-sec" title="Renombrar" data-section-id="' + sec.id + '" data-course-id="' + sec.course + '"><i class="ri-pencil-line"></i></button>' +
                    '<button class="btn-icon btn-edit-desc" title="Editar descripción" data-section-id="' + sec.id + '"><i class="ri-edit-line"></i></button>' +
                    '<button class="btn-icon btn-delete-sec delete" title="Eliminar sección" data-section-id="' + (sec.section || idx) + '" data-sec-name="' + escHtml(sec.name || '') + '" data-act-count="' + actMods.length + '"><i class="ri-delete-bin-line"></i></button>' +
                '</div>';

            var body = document.createElement('div');
            body.className = 'seccion-body' + (idx === 0 ? ' open' : '');

            if (tieneDesc) {
                var desc = document.createElement('div');
                desc.className = 'seccion-descripcion';
                desc.innerHTML = sec.description;
                body.appendChild(desc);
            }

            if (allMods.length === 0 && !tieneDesc) {
                body.innerHTML = '<div class="seccion-vacia">Sin actividades en esta sección</div>';
            } else {
                allMods.forEach(function (mod) {
                    if (mod.modname === 'label') {
                        if (mod.description) {
                            var labelDiv = document.createElement('div');
                            labelDiv.className = 'act-label-content';
                            labelDiv.innerHTML = mod.description;
                            body.appendChild(labelDiv);
                        }
                        return;
                    }

                    var info = ICONS[mod.modname] || ICONS.default;
                    var cmUrl = moodleUrl + '/mod/' + mod.modname + '/view.php?id=' + mod.id;

                    var item = document.createElement('div');
                    item.className = 'act-item';
                    item.setAttribute('data-cmid', mod.id);

                    var extraBtns = '';
                    if (mod.modname === 'forum') {
                        extraBtns = '<button class="btn-act-link btn-act-disc" onclick="ActividadesEditor.abrirDiscusiones(' + getModuloId() + ', ' + (mod.instance || 0) + ', \'' + escHtml(mod.name) + '\')"><i class="ri-discuss-line"></i> Discusiones</button>' +
                            '<button class="btn-act-link btn-act-grade" onclick="ActividadesEditor.calificarForo(' + getModuloId() + ', ' + mod.id + ', ' + (mod.instance || 0) + ', \'' + escHtml(mod.name) + '\')"><i class="ri-bar-chart-line"></i> Calificar</button>';
                    } else if (mod.modname === 'assign') {
                        extraBtns = '<button class="btn-act-link btn-act-grade" onclick="ActividadesEditor.calificarTarea(' + getModuloId() + ', ' + mod.id + ', \'' + escHtml(mod.name) + '\')"><i class="ri-bar-chart-line"></i> Calificar</button>';
                    } else if (mod.modname === 'quiz') {
                        extraBtns = '<button class="btn-act-link btn-act-quiz" onclick="ActividadesEditor.verPreguntasQuiz(' + getModuloId() + ', ' + (mod.instance || 0) + ', \'' + escHtml(mod.name) + '\')"><i class="ri-question-line"></i> Preguntas</button>' +
                            '<button class="btn-act-link btn-act-quiz" onclick="ActividadesEditor.verResultadosQuiz(' + getModuloId() + ', ' + (mod.instance || 0) + ', \'' + escHtml(mod.name) + '\')"><i class="ri-bar-chart-grouped-line"></i> Resultados</button>';
                    }

                    var tieneCont = mod.description && mod.description.trim().length > 0;
                    var toggleBtn = tieneCont
                        ? '<button class="btn-toggle-contenido" onclick="ActividadesEditor.toggleContenido(this, event)"><i class="ri-arrow-down-s-line"></i> Ver contenido</button>'
                        : '';

                    var extraInfo = '';
                    var editExtra = { sectionId: sec.id, intro: '' };
                    if (mod.modname === 'assign') {
                        var tarea = tareasMap[mod.instance] || tareasByCmid[mod.id];
                        if (!tarea) {
                            for (var ti = 0; ti < tareas.length; ti++) {
                                var tt = tareas[ti];
                                if (tt.cmid == mod.id || tt.coursemodule == mod.id || tt.id == mod.instance) {
                                    tarea = tt;
                                    break;
                                }
                            }
                        }
                        // Fuente primaria: mapa explícito del servidor (más confiable)
                        var tfEntry = tareasFechas[mod.instance] || tareasFechas['cm_' + mod.id] || {};
                        var opents = tfEntry.open
                            || (mod.activity_dates && mod.activity_dates.open)
                            || (tarea && (tarea.allowsubmissionsfromdate || tarea.timeopen))
                            || 0;
                        var duets = tfEntry.due
                            || (mod.activity_dates && mod.activity_dates.due)
                            || (tarea && (tarea.duedate || tarea.cutoffdate))
                            || 0;

                        var htmlFechas = '';
                        var now = Math.floor(Date.now() / 1000);
                        if (opents) {
                            var fechaApertura = new Date(opents * 1000);
                            var abierto = opents <= now;
                            htmlFechas += '<span class="act-date-chip act-date-open' + (abierto ? ' act-date-active' : '') + '">'
                                + '<i class="ri-calendar-event-line"></i> Inicio: '
                                + fechaApertura.toLocaleString('es-BO', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' })
                                + '</span>';
                        }
                        if (duets) {
                            var fechaTarea = new Date(duets * 1000);
                            var vencido = duets < now;
                            htmlFechas += '<span class="act-date-chip act-date-due' + (vencido ? ' act-date-overdue' : '') + '">'
                                + '<i class="ri-calendar-check-line"></i> Entrega: '
                                + fechaTarea.toLocaleString('es-BO', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' })
                                + '</span>';
                        }
                        if (htmlFechas) extraInfo = '<div class="act-dates-row">' + htmlFechas + '</div>';

                        if (tarea) {
                            editExtra.duedate = tarea.duedate || 0;
                            editExtra.allowsubmissionsfromdate = tarea.allowsubmissionsfromdate || 0;
                            editExtra.cutoffdate = tarea.cutoffdate || 0;
                            editExtra.grade = tarea.grade || 100;
                            editExtra.intro = tarea.intro || '';
                        }
                    } else if (mod.modname === 'quiz') {
                        var quiz = quizzesMap[mod.instance] || quizzesByCmid[mod.id];
                        var opents = (mod.activity_dates && mod.activity_dates.open)
                            || (quiz && quiz.timeopen)
                            || 0;
                        var closets = (mod.activity_dates && mod.activity_dates.close)
                            || (quiz && quiz.timeclose)
                            || 0;
                            
                        var htmlFechas = '';
                        var nowQ = Math.floor(Date.now() / 1000);
                        if (opents) {
                            var fechaApertura = new Date(opents * 1000);
                            var abiertoQ = opents <= nowQ;
                            htmlFechas += '<span class="act-date-chip act-date-open' + (abiertoQ ? ' act-date-active' : '') + '"><i class="ri-calendar-event-line"></i> Inicio: ' + fechaApertura.toLocaleString('es-BO', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' }) + '</span>';
                        }
                        if (closets) {
                            var fechaQuiz = new Date(closets * 1000);
                            var vencidoQ = closets < nowQ;
                            htmlFechas += '<span class="act-date-chip act-date-due' + (vencidoQ ? ' act-date-overdue' : '') + '"><i class="ri-calendar-check-line"></i> Cierre: ' + fechaQuiz.toLocaleString('es-BO', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' }) + '</span>';
                        }
                        if (htmlFechas) extraInfo = '<div class="act-dates-row">' + htmlFechas + '</div>';

                        if (quiz) {
                            editExtra.timeopen = quiz.timeopen || 0;
                            editExtra.timeclose = quiz.timeclose || 0;
                            editExtra.timelimit = quiz.timelimit ? Math.round(quiz.timelimit / 60) : 30;
                            editExtra.attempts = quiz.attempts || 3;
                            editExtra.grade = quiz.grade || 100;
                            editExtra.intro = quiz.intro || '';
                        }
                    } else if (mod.modname === 'forum') {
                        var foro = forosMap[mod.instance] || forosByCmid[mod.id];
                        var opents = (mod.activity_dates && mod.activity_dates.open)
                            || (foro && foro.dates && foro.dates.open)
                            || (foro && foro.duedate)
                            || 0;

                        var htmlFechas = '';
                        var nowF = Math.floor(Date.now() / 1000);
                        if (opents) {
                            var fechaApertura = new Date(opents * 1000);
                            var abiertoF = opents <= nowF;
                            htmlFechas += '<span class="act-date-chip act-date-open' + (abiertoF ? ' act-date-active' : '') + '"><i class="ri-calendar-event-line"></i> Vencimiento: ' + fechaApertura.toLocaleString('es-BO', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' }) + '</span>';
                        }
                        if (htmlFechas) extraInfo = '<div class="act-dates-row">' + htmlFechas + '</div>';

                        if (foro) {
                            editExtra.forum_type = foro.type || 'general';
                            editExtra.subscription = foro.forcesubscribe || 0;
                            editExtra.intro = foro.intro || '';
                        }
                    }

                    item.innerHTML =
                        '<div class="act-item-left">' +
                            '<span class="drag-handle"><i class="ri-draggable"></i></span>' +
                            '<div class="act-icon ' + info.cls + '"><i class="' + info.icon + '"></i></div>' +
                            '<div>' +
                                '<div class="act-name">' + escHtml(mod.name) + '</div>' +
                                '<div class="act-tipo">' + info.label + '</div>' +
                                extraInfo +
                            '</div>' +
                        '</div>' +
                        '<div class="act-actions">' +
                            toggleBtn +
                            extraBtns +
                            '<button class="btn-act-link btn-act-edit" data-cmid="' + mod.id + '" data-modname="' + mod.modname + '" data-name="' + escHtml(mod.name) + '" data-extra="' + escHtml(JSON.stringify(editExtra)) + '"><i class="ri-pencil-line"></i> Editar</button>' +
                            '<button class="btn-act-link btn-act-delete" data-cmid="' + mod.id + '" data-name="' + escHtml(mod.name) + '"><i class="ri-delete-bin-line"></i></button>' +
                            '<a href="' + cmUrl + '" target="_blank" class="btn-act-link btn-act-moodle"><i class="ri-external-link-line"></i></a>' +
                        '</div>';

                    if (tieneCont) {
                        var contDiv = document.createElement('div');
                        contDiv.className = 'act-contenido';
                        contDiv.innerHTML = mod.description;
                        item.appendChild(contDiv);
                    }

                    body.appendChild(item);
                });
            }

            hdr.addEventListener('click', function (e) {
                if (e.target.closest('.btn-icon') || e.target.closest('.btn-act-link') || e.target.closest('.drag-handle')) return;
                hdr.classList.toggle('open');
                body.classList.toggle('open');
            });

            card.appendChild(hdr);
            card.appendChild(body);
            container.appendChild(card);
        });

        // Bind edit/delete events after rendering
        bindSectionEvents();
        bindActivityEvents();
    }

    function renderForos(foros) {
        var section = document.getElementById('forosSection');
        var container = document.getElementById('forosContainer');
        container.innerHTML = '';

        if (foros.length > 0) {
            section.style.display = 'block';
            foros.forEach(function (foro) {
                var foroUrl = moodleUrl + '/mod/forum/view.php?id=' + foro.cmid;
                var card = document.createElement('div');
                card.className = 'foro-card';
                card.innerHTML =
                    '<div class="foro-card-hdr">' +
                        '<span class="foro-card-name"><i class="ri-discuss-line"></i> ' + escHtml(foro.name) + '</span>' +
                        '<div class="foro-card-actions">' +
                            '<button class="btn-act-link btn-act-disc" onclick="ActividadesEditor.abrirDiscusiones(' + getModuloId() + ', ' + foro.id + ', \'' + escHtml(foro.name) + '\')"><i class="ri-discuss-line"></i> Ver discusiones</button>' +
                            '<button class="btn-act-link btn-act-grade" onclick="ActividadesEditor.calificarForo(' + getModuloId() + ', ' + foro.cmid + ', ' + foro.id + ', \'' + escHtml(foro.name) + '\')"><i class="ri-bar-chart-line"></i> Calificar</button>' +
                            '<a href="' + foroUrl + '" target="_blank" class="btn-act-link btn-act-moodle"><i class="ri-external-link-line"></i> Ver en Moodle</a>' +
                        '</div>' +
                    '</div>';
                container.appendChild(card);
            });
        }
    }

    // ============================================================
    // EVENT BINDING
    // ============================================================

    function bindSectionEvents() {
        document.querySelectorAll('.btn-rename-sec').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var sectionId = this.getAttribute('data-section-id');
                var nameSpan = this.closest('.seccion-hdr').querySelector('.sec-name-text');
                var currentName = nameSpan.textContent;
                var input = document.createElement('input');
                input.className = 'seccion-nombre-input';
                input.value = currentName;
                input.setAttribute('data-section-id', sectionId);
                nameSpan.replaceWith(input);
                input.focus();
                input.select();

                function guardarCambio() {
                    var newName = input.value.trim() || currentName;
                    var secId = input.getAttribute('data-section-id');
                    var courseId = getCourseId();

                    var span = document.createElement('span');
                    span.className = 'sec-name-text';
                    span.textContent = newName;
                    input.replaceWith(span);

                    fetch(getApiBase() + '/' + getModuloId() + '/secciones/guardar', {
                        method: 'POST',
                        headers: jsonHeaders(),
                        body: JSON.stringify({ section_id: parseInt(secId), name: newName, course_id: parseInt(courseId) })
                    })
                    .then(function (r) { return r.json(); })
                    .then(function (res) {
                        if (res.success) {
                            mostrarToast('success', 'Sección renombrada correctamente');
                        } else {
                            span.textContent = currentName;
                            mostrarToast('error', res.message || 'Error al renombrar');
                        }
                    })
                    .catch(function () {
                        span.textContent = currentName;
                        mostrarToast('error', 'Error de conexión');
                    });
                }

                input.addEventListener('blur', guardarCambio);
                input.addEventListener('keydown', function (ev) { if (ev.key === 'Enter') input.blur(); });
            });
        });

        document.querySelectorAll('.btn-edit-desc').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var sectionId = this.getAttribute('data-section-id');
                var card = this.closest('.seccion-card');
                var body = card.querySelector('.seccion-body');
                var descDiv = card.querySelector('.seccion-descripcion');
                if (!descDiv) {
                    descDiv = document.createElement('div');
                    descDiv.className = 'seccion-descripcion';
                    body.insertBefore(descDiv, body.firstChild);
                }
                var currentHTML = descDiv.innerHTML;
                descDiv.innerHTML =
                    '<div class="desc-editor-toolbar">' +
                        '<button onclick="document.execCommand(\'bold\')"><strong>B</strong></button>' +
                        '<button onclick="document.execCommand(\'italic\')"><em>I</em></button>' +
                        '<button onclick="document.execCommand(\'insertUnorderedList\')"><i class="ri-list-unordered"></i></button>' +
                        '<button onclick="var u=prompt(\'URL:\');if(u)document.execCommand(\'createLink\',false,u)"><i class="ri-link"></i></button>' +
                    '</div>' +
                    '<div class="desc-editor" contenteditable="true">' + currentHTML + '</div>' +
                    '<div style="margin-top:0.5rem;display:flex;gap:0.4rem;">' +
                        '<button class="btn-act-link btn-act-edit save-desc" style="background:rgba(22,163,74,0.1);color:#16a34a;" data-section-id="' + sectionId + '"><i class="ri-check-line"></i> Guardar</button>' +
                        '<button class="btn-act-link cancel-desc" style="background:var(--d-bg);color:var(--d-muted);">Cancelar</button>' +
                    '</div>';
            });
        });

        document.querySelectorAll('.btn-delete-sec').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var sectionNumber = this.getAttribute('data-section-id');
                var secName = this.getAttribute('data-sec-name');
                var actCount = this.getAttribute('data-act-count');
                confirmarEliminar('sección', secName, actCount, function () {
                    fetch(getApiBase() + '/' + getModuloId() + '/secciones/' + sectionNumber, {
                        method: 'DELETE',
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken() }
                    })
                    .then(function (r) { return r.json(); })
                    .then(function (res) {
                        if (res.success) {
                            mostrarToast('success', 'Sección eliminada');
                            cargarYRenderizar();
                        } else {
                            mostrarToast('error', res.message || 'Error al eliminar');
                        }
                    })
                    .catch(function () { mostrarToast('error', 'Error de conexión'); });
                });
            });
        });
    }

    function bindActivityEvents() {
        // Edit
        document.querySelectorAll('.btn-act-edit').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var cmid    = this.getAttribute('data-cmid');
                var modname = this.getAttribute('data-modname');
                var name    = this.getAttribute('data-name');

                // Mostrar modal vacío con spinner mientras cargamos datos reales de Moodle DB
                abrirModal(modname, { cmid: parseInt(cmid), name: name, _cargando: true });

                fetch(getApiBase() + '/' + getModuloId() + '/actividades/' + cmid + '/datos', {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken() }
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (!res.success) { mostrarToast('error', res.message || 'Error al cargar datos.'); return; }

                    var d = res.data || {};
                    var extra = { cmid: parseInt(cmid), name: name };

                    if (modname === 'assign') {
                        extra.intro                    = d.intro || '';
                        extra.duedate                  = d.duedate || 0;
                        extra.allowsubmissionsfromdate = d.allowsubmissionsfromdate || 0;
                        extra.cutoffdate               = d.cutoffdate || 0;
                        extra.grade                    = d.grade || 100;
                        extra.onlinetext               = d.onlinetext != null ? d.onlinetext : 1;
                        extra.filesubmission           = d.filesubmission != null ? d.filesubmission : 1;
                        extra.maxfiles                 = d.maxfiles != null ? d.maxfiles : 3;
                        extra.maxsize                  = d.maxsize != null ? d.maxsize : 5242880;
                        extra.introfile                = d.introfile || null;
                    } else if (modname === 'quiz') {
                        extra.intro     = d.intro || '';
                        extra.timeopen  = d.timeopen || 0;
                        extra.timeclose = d.timeclose || 0;
                        extra.timelimit = d.timelimit ? Math.round(d.timelimit / 60) : 30;
                        extra.attempts  = d.attempts || 3;
                        extra.grade     = d.grade || 100;
                    } else if (modname === 'forum') {
                        extra.intro        = d.intro || '';
                        extra.forum_type   = d.type || 'general';
                        extra.subscription = d.forcesubscribe || 0;
                        extra.timeopen     = d.timeopen || d.duedate || 0;
                        extra.timeclose    = d.timeclose || d.cutoffdate || 0;
                        extra.duedate      = d.duedate || d.timeopen || 0;
                        extra.cutoffdate   = d.cutoffdate || d.timeclose || 0;
                        extra.grade        = d.grade || 0;
                    }

                    abrirModal(modname, extra);
                })
                .catch(function () { mostrarToast('error', 'Error de conexión al cargar datos.'); });
            });
        });

        // Delete
        document.querySelectorAll('.btn-act-delete').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var cmid = this.getAttribute('data-cmid');
                var name = this.getAttribute('data-name');
                confirmarEliminar('actividad', name, null, function () {
                    fetch(getApiBase() + '/' + getModuloId() + '/actividades/' + cmid, {
                        method: 'DELETE',
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken() }
                    })
                    .then(function (r) { return r.json(); })
                    .then(function (res) {
                        if (res.success) {
                            mostrarToast('success', 'Actividad eliminada');
                            cargarYRenderizar();
                        } else {
                            mostrarToast('error', res.message || 'Error al eliminar');
                        }
                    })
                    .catch(function () { mostrarToast('error', 'Error de conexión'); });
                });
            });
        });

        // Desc save/cancel delegation
        document.addEventListener('click', function (e) {
            var target = e.target.closest('.save-desc');
            if (target) {
                e.stopPropagation();
                var sectionId = target.getAttribute('data-section-id');
                var card = target.closest('.seccion-card');
                var editor = card.querySelector('.desc-editor');
                if (!editor) return;
                var html = editor.innerHTML;
                var courseId = getCourseId();
                fetch(getApiBase() + '/' + getModuloId() + '/secciones/guardar', {
                    method: 'POST',
                    headers: jsonHeaders(),
                    body: JSON.stringify({ section_id: parseInt(sectionId), name: card.querySelector('.sec-name-text')?.textContent || '', summary: html, course_id: parseInt(courseId) })
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.success) {
                        mostrarToast('success', 'Descripción guardada');
                        cargarYRenderizar();
                    } else {
                        mostrarToast('error', res.message || 'Error');
                    }
                })
                .catch(function () { mostrarToast('error', 'Error de conexión'); });
            }

            var cancel = e.target.closest('.cancel-desc');
            if (cancel) {
                e.stopPropagation();
                cargarYRenderizar();
            }
        });
    }

    // ============================================================
    // MODAL
    // ============================================================

    function buildSectionSelector() {
        var cards = document.querySelectorAll('#seccionesContainer .seccion-card');
        if (cards.length === 0) return '<input type="hidden" id="sectionSelector" value="0">';
        var options = '';
        cards.forEach(function (card) {
            var id = card.getAttribute('data-section-id');
            var name = card.querySelector('.sec-name-text')?.textContent || 'Sección ' + id;
            options += '<option value="' + id + '">' + escHtml(name) + '</option>';
        });
        return '<div class="form-group"><label>Sección <span class="required">*</span></label><select class="form-control" id="sectionSelector">' + options + '</select></div>';
    }

    function abrirModal(tipo, data) {
        var modalTitle = document.getElementById('modalTitleText');
        var modalBody = document.getElementById('modalBody');
        var overlay = document.getElementById('modalForm');

        if (!overlay) {
            mostrarToast('error', 'Error: modal no encontrado en la página.');
            return;
        }

        var editando = data && data.cmid;
        modalTitle.textContent = editando ? 'Editando: ' + data.name : 'Nueva ' + capitalize(tipo);

        // Si está cargando datos del servidor, mostrar spinner y esperar
        if (data && data._cargando) {
            modalBody.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--d-muted);"><i class="ri-loader-4-line" style="font-size:1.5rem;animation:spin 1s linear infinite;"></i><p style="margin-top:.5rem;">Cargando datos...</p></div>';
            overlay.classList.add('open');
            return;
        }

        var formHTML = getFormHTML(tipo, data);
        if (!formHTML) {
            modalTitle.textContent = 'Tipo no soportado: ' + tipo;
            modalBody.innerHTML = '<p style="color:var(--d-muted)">El tipo de actividad "' + tipo + '" aún no tiene formulario.</p>';
            overlay.classList.add('open');
            return;
        }

        // Prepend section selector to form
        var sectionSelector = buildSectionSelector();
        modalBody.innerHTML = sectionSelector + formHTML;
        overlay.classList.add('open');

        // Pre-select the section
        var sel = document.getElementById('sectionSelector');
        if (editando && data.sectionId && sel) {
            sel.value = data.sectionId;
        } else if (!editando && sel) {
            var firstCard = document.querySelector('.seccion-card.open .seccion-body')?.closest('.seccion-card');
            if (firstCard) {
                var secId = firstCard.getAttribute('data-section-id');
                if (secId) sel.value = secId;
            }
        }

        overlay.setAttribute('data-current-type', tipo);
        overlay.setAttribute('data-current-cmid', editando ? data.cmid : '');

        // Botón quitar adjunto actual
        var btnQuitar = document.getElementById('btnQuitarAdjunto');
        if (btnQuitar) {
            btnQuitar.addEventListener('click', function () {
                var panel = document.getElementById('adjuntoActual');
                if (panel) panel.remove();
                var lbl = document.querySelector('label[for="fldAdjunto"]');
                // update label text
                var adjLabel = document.querySelector('#fldAdjunto')?.previousElementSibling;
                if (adjLabel && adjLabel.tagName === 'LABEL') adjLabel.textContent = 'Adjuntar archivo:';
            });
        }
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function getFormHTML(tipo, data) {
        var editando = data && data.cmid;
        var nameVal = editando ? escHtml(data.name) : '';
        var introVal = editando ? escHtml(data.intro || '') : '';

        var onlineTextChecked  = (!editando || data.onlinetext    == null || data.onlinetext    !== 0) ? ' checked' : '';
        var fileSubChecked     = (!editando || data.filesubmission == null || data.filesubmission !== 0) ? ' checked' : '';
        var maxFilesOpts = [1, 3, 5, 10, 20].map(function(v) {
            var sel = editando && data.maxfiles != null ? (parseInt(data.maxfiles) === v ? ' selected' : '') : (v === 3 ? ' selected' : '');
            return '<option value="' + v + '"' + sel + '>' + v + '</option>';
        }).join('');
        var maxSizeLabels = { 102400: '100 KB', 1048576: '1 MB', 5242880: '5 MB', 10485760: '10 MB', 52428800: '50 MB' };
        var maxSizeOpts = [102400, 1048576, 5242880, 10485760, 52428800].map(function(v) {
            var sel = editando && data.maxsize != null ? (parseInt(data.maxsize) === v ? ' selected' : '') : (v === 5242880 ? ' selected' : '');
            return '<option value="' + v + '"' + sel + '>' + maxSizeLabels[v] + '</option>';
        }).join('');

        var introfileHTML = '';
        if (editando && data.introfile) {
            var f = data.introfile;
            var sizeKb = f.size ? Math.round(f.size / 1024) + ' KB' : '';
            introfileHTML =
                '<div id="adjuntoActual" style="display:flex;align-items:center;gap:0.5rem;padding:0.4rem 0.6rem;background:rgba(99,102,241,0.07);border-radius:6px;font-size:0.8rem;margin-bottom:0.4rem;">' +
                    '<i class="ri-attachment-line" style="color:#6366f1;"></i>' +
                    '<span style="font-weight:600;color:#4f46e5;">' + escHtml(f.name) + '</span>' +
                    (sizeKb ? '<span style="color:var(--d-muted);">(' + sizeKb + ')</span>' : '') +
                    '<button type="button" id="btnQuitarAdjunto" style="margin-left:auto;background:none;border:none;color:#dc2626;cursor:pointer;font-size:0.75rem;"><i class="ri-close-line"></i> Quitar</button>' +
                '</div>';
        }

        var assignHTML =
            '<div class="form-group"><label style="display:flex;align-items:center;gap:.35rem;font-size:.85rem;font-weight:700;color:#1e293b;"><i class="ri-task-line" style="color:#fc7b04;"></i> Nombre de la tarea <span class="required">*</span></label><input class="form-control" id="fldName" value="' + nameVal + '" placeholder="Ej: Tarea Semana 1" style="font-size:.87rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.5rem .7rem;"></div>' +
            '<div class="form-group"><label style="display:flex;align-items:center;gap:.35rem;font-size:.85rem;font-weight:700;color:#1e293b;margin-top:.2rem;"><i class="ri-file-text-line" style="color:#64748b;"></i> Descripción</label><textarea class="form-control" id="fldDescription" rows="4" placeholder="Instrucciones de la tarea..." style="font-size:.85rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.5rem .7rem;">' + introVal + '</textarea></div>' +

            '<div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.8rem 1rem;margin:.8rem 0;">' +
            '<div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem;font-weight:700;color:#1e293b;margin-bottom:.7rem;"><i class="ri-calendar-line" style="color:#fc7b04;"></i> Disponibilidad</div>' +
            '<div class="form-row" style="gap:.6rem;">' +
            '<div class="form-group" style="flex:1;min-width:0;"><label style="font-size:.78rem;font-weight:600;color:#475569;display:flex;align-items:center;gap:.3rem;"><i class="ri-play-circle-line" style="color:#16a34a;font-size:.8rem;"></i> Inicio</label><input class="form-control" id="fldAllowFrom" type="datetime-local" value="' + (editando ? tsToDateInput(data.allowsubmissionsfromdate) : '') + '" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .6rem;"></div>' +
            '<div class="form-group" style="flex:1;min-width:0;"><label style="font-size:.78rem;font-weight:600;color:#475569;display:flex;align-items:center;gap:.3rem;"><i class="ri-calendar-check-line" style="color:#dc2626;font-size:.8rem;"></i> Entrega</label><input class="form-control" id="fldDueDate" type="datetime-local" value="' + (editando ? tsToDateInput(data.duedate) : '') + '" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .6rem;"></div>' +
            '<div class="form-group" style="flex:1;min-width:0;"><label style="font-size:.78rem;font-weight:600;color:#475569;display:flex;align-items:center;gap:.3rem;"><i class="ri-close-circle-line" style="color:#9333ea;font-size:.8rem;"></i> Límite</label><input class="form-control" id="fldCutoff" type="datetime-local" value="' + (editando ? tsToDateInput(data.cutoffdate) : '') + '" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .6rem;"></div></div></div>' +

            '<div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.8rem 1rem;margin:.8rem 0;">' +
            '<div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem;font-weight:700;color:#1e293b;margin-bottom:.7rem;"><i class="ri-upload-cloud-line" style="color:#2563eb;"></i> Tipos de entrega</div>' +
            '<div class="form-row" style="gap:1rem;margin-bottom:.6rem;">' +
            '<label class="checkbox-inline" style="display:flex;align-items:center;gap:.35rem;font-size:.84rem;color:#374151;cursor:pointer;"><input type="checkbox" id="fldOnlineText"' + onlineTextChecked + ' style="width:16px;height:16px;accent-color:#fc7b04;"> Texto online</label>' +
            '<label class="checkbox-inline" style="display:flex;align-items:center;gap:.35rem;font-size:.84rem;color:#374151;cursor:pointer;"><input type="checkbox" id="fldFileSubmission"' + fileSubChecked + ' style="width:16px;height:16px;accent-color:#fc7b04;"> Archivos</label></div>' +
            '<div class="form-row" id="fileOptions" style="gap:.6rem;"><div class="form-group" style="flex:1;"><label style="font-size:.76rem;font-weight:600;color:#475569;">Máx. archivos</label><select class="form-control" id="fldMaxFiles" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.35rem .5rem;">' + maxFilesOpts + '</select></div>' +
            '<div class="form-group" style="flex:1;"><label style="font-size:.76rem;font-weight:600;color:#475569;">Tamaño máx.</label><select class="form-control" id="fldMaxSize" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.35rem .5rem;">' + maxSizeOpts + '</select></div></div></div>' +

            '<div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.8rem 1rem;margin:.8rem 0;">' +
            '<div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem;font-weight:700;color:#1e293b;margin-bottom:.7rem;"><i class="ri-bar-chart-line" style="color:#d97706;"></i> Calificación</div>' +
            '<div class="form-row"><div class="form-group" style="flex:1;"><label style="font-size:.78rem;font-weight:600;color:#475569;">Máxima calificación</label><div style="display:flex;align-items:center;gap:.4rem;"><input class="form-control" id="fldGrade" type="number" value="' + (editando && data.grade ? data.grade : 100) + '" min="0" max="100" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .6rem;width:100px;"> <span style="font-size:.78rem;color:#94a3b8;">puntos</span></div></div></div></div>' +

            '<div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.8rem 1rem;margin:.8rem 0;">' +
            '<div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem;font-weight:700;color:#1e293b;margin-bottom:.6rem;"><i class="ri-settings-line" style="color:#64748b;"></i> Configuración de entrega</div>' +
            '<div class="form-row" style="flex-direction:column;gap:.35rem;">' +
            '<label class="checkbox-inline" style="display:flex;align-items:center;gap:.35rem;font-size:.84rem;color:#374151;cursor:pointer;"><input type="checkbox" id="fldRequireStatement" checked style="width:16px;height:16px;accent-color:#fc7b04;"> Requerir declaración de autoría</label>' +
            '<label class="checkbox-inline" style="display:flex;align-items:center;gap:.35rem;font-size:.84rem;color:#374151;cursor:pointer;"><input type="checkbox" id="fldNotifyTeachers" style="width:16px;height:16px;accent-color:#fc7b04;"> Notificar a docentes</label></div></div>' +

            '<div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.8rem 1rem;margin:.8rem 0;">' +
            '<div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem;font-weight:700;color:#1e293b;margin-bottom:.5rem;"><i class="ri-attachment-line" style="color:#9333ea;"></i> Archivo adjunto (instrucciones)</div>' +
            '<div class="form-group">' + introfileHTML +
            '<label style="font-size:.78rem;font-weight:600;color:#475569;display:block;margin-top:.3rem;">' + (editando && data.introfile ? 'Reemplazar archivo:' : 'Adjuntar archivo:') + '</label>' +
            '<input type="file" class="form-control" id="fldAdjunto" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.jpg,.jpeg,.png" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .5rem;margin-top:.2rem;">' +
            '<div style="font-size:.72rem;color:#94a3b8;margin-top:.25rem;display:flex;align-items:center;gap:.3rem;"><i class="ri-information-line"></i>Formatos: PDF, Word, Excel, PowerPoint, ZIP, imágenes. Máx. 50 MB.</div></div></div>';

        var quizAttemptsSel = [1,2,3,0].map(function(v) {
            var label = v === 0 ? 'Ilimitados' : String(v);
            var sel = editando && parseInt(data.attempts) === v ? ' selected' : (v === 3 && !editando ? ' selected' : '');
            return '<option value="' + v + '"' + sel + '>' + label + '</option>';
        }).join('');

        var quizHTML =
            '<div class="form-group"><label>Nombre del cuestionario <span class="required">*</span></label><input class="form-control" id="fldName" value="' + nameVal + '" placeholder="Ej: Cuestionario Semana 1"></div>' +
            '<div class="form-group"><label>Descripción</label><textarea class="form-control" id="fldDescription" rows="3" placeholder="Instrucciones del cuestionario...">' + introVal + '</textarea></div>' +
            '<div class="form-section-title">Temporalización</div><div class="form-row">' +
            '<div class="form-group"><label>Abierto desde</label><input class="form-control" id="fldOpenDate" type="date" value="' + (editando ? tsToDateInput(data.timeopen) : '') + '"></div>' +
            '<div class="form-group"><label>Cierra</label><input class="form-control" id="fldCloseDate" type="date" value="' + (editando ? tsToDateInput(data.timeclose) : '') + '"></div>' +
            '<div class="form-group"><label>Tiempo (min.)</label><input class="form-control" id="fldTime" type="number" value="' + (editando && data.timelimit != null ? data.timelimit : 30) + '" min="0"></div></div>' +
            '<div class="form-section-title">Calificación</div><div class="form-row">' +
            '<div class="form-group"><label>Máxima calificación</label><input class="form-control" id="fldGrade" type="number" value="' + (editando && data.grade ? data.grade : 100) + '" min="0" max="100"></div>' +
            '<div class="form-group"><label>Intentos permitidos</label><select class="form-control" id="fldAttempts">' + quizAttemptsSel + '</select></div></div>';

        var forumTypeOpts = ['general','single','qanda'].map(function(v) {
            var labels = { general: 'General', single: 'Discusión única', qanda: 'Preguntas y respuestas' };
            var sel = editando && data.forum_type === v ? ' selected' : (v === 'general' && !editando ? ' selected' : '');
            return '<option value="' + v + '"' + sel + '>' + labels[v] + '</option>';
        }).join('');

        var subOpts = [0,1,2].map(function(v) {
            var labels = { 0: 'Opcional', 1: 'Forzada', 2: 'Automática' };
            var sel = editando && parseInt(data.subscription) === v ? ' selected' : (v === 0 && !editando ? ' selected' : '');
            return '<option value="' + v + '"' + sel + '>' + labels[v] + '</option>';
        }).join('');

        var forumHTML =
            '<div class="form-group"><label style="display:flex;align-items:center;gap:.35rem;font-size:.85rem;font-weight:700;color:#1e293b;"><i class="ri-discuss-line" style="color:#fc7b04;"></i> Nombre del foro <span class="required">*</span></label><input class="form-control" id="fldName" value="' + nameVal + '" placeholder="Ej: Foro de Discusión" style="font-size:.87rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.5rem .7rem;"></div>' +
            '<div class="form-group"><label style="display:flex;align-items:center;gap:.35rem;font-size:.85rem;font-weight:700;color:#1e293b;margin-top:.2rem;"><i class="ri-file-text-line" style="color:#64748b;"></i> Descripción</label><textarea class="form-control" id="fldDescription" rows="4" placeholder="Tema o instrucciones del foro..." style="font-size:.85rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.5rem .7rem;">' + introVal + '</textarea></div>' +

            '<div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.8rem 1rem;margin:.8rem 0;">' +
            '<div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem;font-weight:700;color:#1e293b;margin-bottom:.7rem;"><i class="ri-settings-line" style="color:#64748b;"></i> Configuración</div>' +
            '<div class="form-row" style="gap:.6rem;"><div class="form-group" style="flex:1;min-width:0;"><label style="font-size:.78rem;font-weight:600;color:#475569;">Tipo</label><select class="form-control" id="fldForumType" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.35rem .5rem;">' + forumTypeOpts + '</select></div>' +
            '<div class="form-group" style="flex:1;min-width:0;"><label style="font-size:.78rem;font-weight:600;color:#475569;">Suscripción</label><select class="form-control" id="fldSubscription" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.35rem .5rem;">' + subOpts + '</select></div></div></div>' +

            '<div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.8rem 1rem;margin:.8rem 0;">' +
            '<div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem;font-weight:700;color:#1e293b;margin-bottom:.7rem;"><i class="ri-calendar-line" style="color:#2563eb;"></i> Temporalización</div>' +
            '<div class="form-row" style="gap:.6rem;">' +
            '<div class="form-group" style="flex:1;min-width:0;"><label style="font-size:.78rem;font-weight:600;color:#475569;">Disponible desde</label><input class="form-control" id="fldOpenDate" type="datetime-local" value="' + (editando ? tsToDateInput(data.timeopen || data.duedate) : '') + '" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .6rem;"></div>' +
            '<div class="form-group" style="flex:1;min-width:0;"><label style="font-size:.78rem;font-weight:600;color:#475569;">Cierra</label><input class="form-control" id="fldCloseDate" type="datetime-local" value="' + (editando ? tsToDateInput(data.timeclose || data.cutoffdate) : '') + '" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .6rem;"></div></div></div>' +

            '<div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.8rem 1rem;margin:.8rem 0;">' +
            '<div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem;font-weight:700;color:#1e293b;margin-bottom:.7rem;"><i class="ri-bar-chart-line" style="color:#d97706;"></i> Calificación</div>' +
            '<div class="form-row"><div class="form-group" style="flex:1;"><label style="font-size:.78rem;font-weight:600;color:#475569;">Máxima calificación</label><div style="display:flex;align-items:center;gap:.4rem;"><input class="form-control" id="fldGrade" type="number" value="' + (editando && data.grade ? data.grade : 100) + '" min="0" max="100" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .6rem;width:100px;"> <span style="font-size:.78rem;color:#94a3b8;">puntos</span></div></div></div></div>';

        var templates = {
            assign: assignHTML,
            quiz: quizHTML,
            forum: forumHTML,
            page: '<div class="form-group"><label>Nombre de la página <span class="required">*</span></label><input class="form-control" id="fldName" value="' + nameVal + '" placeholder="Ej: Introducción"></div><div class="form-group"><label>Contenido</label><div class="wysiwyg-toolbar"><button onclick="document.execCommand(\'bold\')"><strong>B</strong></button><button onclick="document.execCommand(\'italic\')"><em>I</em></button><button onclick="document.execCommand(\'insertOrderedList\')"><i class="ri-list-ordered"></i></button><button onclick="document.execCommand(\'insertUnorderedList\')"><i class="ri-list-unordered"></i></button></div><div class="wysiwyg-editor" contenteditable="true" id="fldContent" style="min-height:150px;"></div></div>',
            url: '<div class="form-group"><label>Nombre <span class="required">*</span></label><input class="form-control" id="fldName" value="' + nameVal + '" placeholder="Ej: Video complementario"></div><div class="form-group"><label>URL <span class="required">*</span></label><input class="form-control" id="fldExternalUrl" type="url" placeholder="https://ejemplo.com/video"></div><div class="form-group"><label>Descripción</label><textarea class="form-control" id="fldDescription" rows="3"></textarea></div><div class="form-group"><label>Abrir en</label><select class="form-control" id="fldDisplay"><option value="2">Nueva ventana</option><option value="1">Misma ventana</option><option value="0">Incrustado</option></select></div>',
        };

        return templates[tipo] || null;
    }

    function cerrarModal() {
        var overlay = document.getElementById('modalForm');
        if (overlay) overlay.classList.remove('open');
        // Restore save button
        var saveBtn = overlay?.querySelector('.btn-save');
        if (saveBtn) {
            saveBtn.innerHTML = '<i class="ri-check-line"></i> Guardar en Moodle';
            saveBtn.style.background = '';
            saveBtn.disabled = false;
        }
        _guardando = false;
    }

    var _guardando = false;

    function guardarModal() {
        if (_guardando) return;
        _guardando = true;

        var overlay = document.getElementById('modalForm');
        if (!overlay) {
            _guardando = false;
            return;
        }

        var tipo = overlay.getAttribute('data-current-type');
        var cmid = overlay.getAttribute('data-current-cmid');
        var name = document.getElementById('fldName')?.value?.trim();

        if (!name) {
            _guardando = false;
            mostrarToast('error', 'El nombre es obligatorio.');
            return;
        }

        var payload = {
            modname: tipo,
            name: name,
            description: document.getElementById('fldDescription')?.value || '',
            section: getActiveSectionId(),
            course_id: getCourseId(),
        };

        if (cmid) payload.cmid = parseInt(cmid);

        // Add type-specific fields
        if (tipo === 'assign') {
            payload.duedate = dateValToTs(document.getElementById('fldDueDate')?.value);
            payload.grade = parseInt(document.getElementById('fldGrade')?.value || '100');
            payload.allowsubmissionsfromdate = dateValToTs(document.getElementById('fldAllowFrom')?.value);
            payload.cutoffdate = dateValToTs(document.getElementById('fldCutoff')?.value);
            payload.onlinetext = document.getElementById('fldOnlineText')?.checked ? 1 : 0;
            payload.filesubmission = document.getElementById('fldFileSubmission')?.checked ? 1 : 0;
            payload.maxfiles = parseInt(document.getElementById('fldMaxFiles')?.value || '3');
            payload.maxsize = parseInt(document.getElementById('fldMaxSize')?.value || '5242880');
            payload.requirestatement = document.getElementById('fldRequireStatement')?.checked ? 1 : 0;
            payload.notifyteachers = document.getElementById('fldNotifyTeachers')?.checked ? 1 : 0;
        }
        if (tipo === 'quiz') {
            payload.timelimit = parseInt(document.getElementById('fldTime')?.value || '30');
            payload.attempts = parseInt(document.getElementById('fldAttempts')?.value || '3');
            payload.grade = parseInt(document.getElementById('fldGrade')?.value || '100');
            payload.timeopen = dateValToTs(document.getElementById('fldOpenDate')?.value);
            payload.timeclose = dateValToTs(document.getElementById('fldCloseDate')?.value);
        }
        if (tipo === 'forum') {
            payload.forum_type = document.getElementById('fldForumType')?.value || 'general';
            payload.subscription = parseInt(document.getElementById('fldSubscription')?.value || '0');
            payload.grade = parseInt(document.getElementById('fldGrade')?.value || '100');
            var t_open = dateValToTs(document.getElementById('fldOpenDate')?.value);
            var t_close = dateValToTs(document.getElementById('fldCloseDate')?.value);
            payload.timeopen = t_open;
            payload.timeclose = t_close;
            payload.duedate = t_open;
            payload.cutoffdate = t_close;
        }
        if (tipo === 'page') {
            payload.content = document.getElementById('fldContent')?.innerHTML || '';
        }
        if (tipo === 'url') {
            payload.externalurl = document.getElementById('fldExternalUrl')?.value || '';
            payload.display = parseInt(document.getElementById('fldDisplay')?.value || '2');
        }

        var adjuntoFile = (tipo === 'assign') ? (document.getElementById('fldAdjunto')?.files[0] || null) : null;

        var saveBtn = overlay.querySelector('.btn-save');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="ri-loader-4-line" style="animation:spin 1s linear infinite;"></i> Guardando...';

        fetch(getApiBase() + '/' + getModuloId() + '/actividades/guardar', {
            method: 'POST',
            headers: jsonHeaders(),
            body: JSON.stringify(payload),
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            _guardando = false;
            saveBtn.disabled = false;
            if (!res.success) {
                saveBtn.innerHTML = '<i class="ri-check-line"></i> Guardar en Moodle';
                mostrarToast('error', res.message || 'Error al guardar');
                return;
            }

            // Si hay archivo adjunto, subirlo como segundo paso
            if (adjuntoFile) {
                var finalCmid = res.data?.cmid || parseInt(cmid);
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="ri-loader-4-line" style="animation:spin 1s linear infinite;"></i> Subiendo archivo...';
                var fd = new FormData();
                fd.append('file', adjuntoFile);
                fetch(getApiBase() + '/' + getModuloId() + '/actividades/' + finalCmid + '/adjunto', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken() },
                    body: fd,
                })
                .then(function (r2) { return r2.json(); })
                .then(function (adjRes) {
                    saveBtn.disabled = false;
                    cerrarModal();
                    if (adjRes.success) {
                        mostrarToast('success', 'Tarea guardada y archivo adjuntado correctamente');
                    } else {
                        mostrarToast('error', 'Tarea guardada pero error al subir adjunto: ' + (adjRes.message || ''));
                    }
                    cargarYRenderizar();
                })
                .catch(function () {
                    saveBtn.disabled = false;
                    cerrarModal();
                    mostrarToast('error', 'Tarea guardada pero error de conexión al subir adjunto');
                    cargarYRenderizar();
                });
                return;
            }

            cerrarModal();
            mostrarToast('success', res.message || 'Actividad guardada correctamente');
            cargarYRenderizar();
        })
        .catch(function () {
            _guardando = false;
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="ri-check-line"></i> Guardar en Moodle';
            mostrarToast('error', 'Error de conexión');
        });
    }

    // ============================================================
    // CONFIRMACIÓN DE ELIMINACIÓN
    // ============================================================

    function confirmarEliminar(tipo, nombre, actCount, onConfirm) {
        if (typeof Swal !== 'undefined') {
            var msg = tipo === 'sección'
                ? 'Esta acción eliminará la sección y <strong>TODAS sus actividades (' + actCount + ')</strong> permanentemente de Moodle.'
                : 'Esta acción eliminará la actividad permanentemente de Moodle.';
            Swal.fire({
                title: '¿Eliminar ' + (tipo === 'sección' ? 'la sección' : 'la actividad') + ' "' + nombre + '"?',
                html: '<p style="color:#6c757d;font-size:0.9rem;">' + msg + '<br><br><strong>Esta operación no se puede deshacer.</strong></p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="ri-delete-bin-line"></i> Eliminar',
                cancelButtonText: 'Cancelar',
            }).then(function (result) {
                if (result.isConfirmed) onConfirm();
            });
        } else {
            if (confirm('¿Eliminar "' + nombre + '"? Esta operación no se puede deshacer.')) {
                onConfirm();
            }
        }
    }

    // ============================================================
    // DRAG & DROP
    // ============================================================

    function habilitarDragDrop() {
        if (typeof Sortable === 'undefined') return;

        sortableInstances.forEach(function (s) { s.destroy(); });
        sortableInstances = [];

        var secContainer = document.getElementById('seccionesContainer');
        if (secContainer) {
            sortableInstances.push(Sortable.create(secContainer, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: function () {
                    var ids = [];
                    secContainer.querySelectorAll('.seccion-card').forEach(function (card) {
                        var id = card.getAttribute('data-section-id');
                        if (id) ids.push(parseInt(id));
                    });
                    if (ids.length > 0) {
                        fetch(getApiBase() + '/' + getModuloId() + '/reordenar', {
                            method: 'POST',
                        headers: jsonHeaders(),
                        body: JSON.stringify({ type: 'secciones', ids: ids }),
                    })
                    .then(function (r) { return r.json(); })
                    .then(function (res) {
                        if (res.success) mostrarToast('success', 'Secciones reordenadas');
                        else mostrarToast('error', res.message || 'Error al reordenar');
                    })
                    .catch(function () { mostrarToast('error', 'Error de conexión'); });
                    }
                }
            }));
        }

        // Segundo Sortable para actividades
        document.querySelectorAll('.seccion-body').forEach(function (body) {
            if (body.querySelector('.act-item')) {
                sortableInstances.push(Sortable.create(body, {
                    handle: '.drag-handle',
                    animation: 150,
                    group: 'activities',
                    onEnd: function () {
                        var sectionCard = body.closest('.seccion-card');
                        var sectionId = sectionCard ? sectionCard.getAttribute('data-section-id') : null;
                        var cmids = [];
                        body.querySelectorAll('.act-item').forEach(function (item) {
                            var cmid = item.getAttribute('data-cmid');
                            if (cmid) cmids.push(parseInt(cmid));
                        });
                        if (sectionId && cmids.length > 0) {
                            fetch(getApiBase() + '/' + getModuloId() + '/reordenar', {
                                method: 'POST',
                                headers: jsonHeaders(),
                                body: JSON.stringify({ type: 'actividades', sectionId: parseInt(sectionId), ids: cmids }),
                            })
                            .then(function (r) { return r.json(); })
                            .then(function (res) {
                                if (res.success) mostrarToast('success', 'Actividades reordenadas');
                                else mostrarToast('error', res.message || 'Error al reordenar');
                            })
                            .catch(function () { mostrarToast('error', 'Error de conexión'); });
                        }
                    }
                }));
            }
        });
    }

    // ============================================================
    // DISCUSIONES (Forum discussions — existing feature preserved)
    // ============================================================

    var _discModuloId = 0;
    var _discForumId = 0;

    function abrirDiscusiones(modId, forumId, forumNombre) {
        _discModuloId = modId;
        _discForumId = forumId;

        document.getElementById('discModalForoNombre').textContent = forumNombre;
        document.getElementById('discModalBody').innerHTML = '<div class="act-loading"><i class="ri-loader-4-line"></i><p>Cargando discusiones...</p></div>';
        document.getElementById('modalDiscusiones').classList.add('open');

        fetch(getApiBase() + '/' + modId + '/actividades/foro/' + forumId + '/discusiones', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken() }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            var body = document.getElementById('discModalBody');
            if (!data.success) {
                body.innerHTML = '<div class="disc-empty">Error al cargar discusiones.</div>';
                return;
            }
            var discs = data.discusiones || [];
            if (discs.length === 0) {
                body.innerHTML = '<div class="disc-empty"><i class="ri-inbox-line" style="font-size:1.5rem;display:block;margin-bottom:0.5rem;opacity:0.4;"></i>No hay discusiones en este foro.</div>';
                return;
            }
            body.innerHTML = discs.map(function (d) {
                return '<div class="disc-item">' +
                    '<div class="disc-item-name">' + escHtml(d.name || d.subject || 'Sin título') + '</div>' +
                    '<div class="disc-item-meta">' +
                        (d.userfullname ? escHtml(d.userfullname) + ' · ' : '') +
                        (d.created ? new Date(d.created * 1000).toLocaleString('es-BO') : '') +
                        ' · ' + (d.numreplies || 0) + ' respuesta' + ((d.numreplies || 0) !== 1 ? 's' : '') +
                    '</div>' +
                    '<div class="disc-item-actions" style="margin-top:6px;">' +
                        '<button class="btn-act-link" onclick="ActividadesEditor.verPosts(' + modId + ', ' + forumId + ', ' + d.id + ', \'' + escHtml(d.name || d.subject || '') + '\')"><i class="ri-chat-1-line"></i> Ver respuestas</button>' +
                    '</div>' +
                '</div>';
            }).join('');
        })
        .catch(function () {
            document.getElementById('discModalBody').innerHTML = '<div class="disc-empty">Error de conexión.</div>';
        });
    }

    // ============================================================
    // POSTS DE FORO
    // ============================================================

    function verPosts(modId, forumId, discussionId, subject) {
        var modal = document.getElementById('modalPosts');
        if (!modal) return;
        document.getElementById('postsDiscussionTitle').textContent = subject;
        document.getElementById('postsLoading').classList.remove('d-none');
        document.getElementById('postsContainer').innerHTML = '';
        document.getElementById('postsError').classList.add('d-none');
        document.getElementById('replyMessage').value = '';
        modal.style.display = 'flex';

        var url = getApiBase() + '/' + modId + '/actividades/foro/' + forumId + '/discusiones/' + discussionId + '/posts';
        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                document.getElementById('postsLoading').classList.add('d-none');
                if (!data.success) throw new Error(data.message);
                var container = document.getElementById('postsContainer');
                container.innerHTML = '';
                if (data.posts.length === 0) {
                    container.innerHTML = '<p class="text-muted">Sin respuestas aun.</p>';
                    return;
                }
                var _cmid = data.cmid || 0;
                data.posts.forEach(function (p) {
                    if (p._user_header) {
                        var gradeVal = p.grade !== null && p.grade !== undefined ? p.grade : '';
                        container.innerHTML +=
                            '<div class="card mb-2" style="border:1px solid var(--d-card-border);border-radius:8px;background:#f8fafc;">' +
                                '<div class="card-body py-2" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">' +
                                    '<div><strong style="color:#1e293b;"><i class="ri-user-line"></i> ' + escHtml(p.userfullname) + '</strong></div>' +
                                    '<div style="display:flex;align-items:center;gap:.5rem;flex-shrink:0;">' +
                                        '<label style="font-size:.78rem;color:#475569;white-space:nowrap;">Nota:</label>' +
                                        '<input type="number" class="form-control form-control-sm grade-input-foro" data-user="' + p.userid + '" value="' + gradeVal + '" min="0" max="100" step="0.5" style="width:80px;">' +
                                        '<button class="btn btn-sm btn-primary" onclick="ActividadesEditor.guardarCalificacionForo(' + modId + ', ' + _cmid + ', ' + p.userid + ')"><i class="ri-check-line"></i></button>' +
                                    '</div>' +
                                '</div>' +
                            '</div>';
                    } else {
                        container.innerHTML +=
                            '<div class="card mb-2" style="border:1px solid var(--d-card-border);border-radius:8px;margin-left:1.5rem;">' +
                                '<div class="card-body py-2">' +
                                    '<small class="text-muted">' + escHtml(p.userfullname) + ' · ' + new Date(p.created * 1000).toLocaleString('es-BO') + '</small>' +
                                    '<div class="mt-1">' + p.message + '</div>' +
                                '</div>' +
                            '</div>';
                    }
                });
            })
            .catch(function (err) {
                document.getElementById('postsLoading').classList.add('d-none');
                document.getElementById('postsError').textContent = err.message || 'Error al cargar respuestas.';
                document.getElementById('postsError').classList.remove('d-none');
            });

        // Set up reply handler
        document.getElementById('btnEnviarRespuesta').onclick = function () {
            var message = document.getElementById('replyMessage').value.trim();
            if (!message) { mostrarToast('error', 'Escribe un mensaje.'); return; }
            var subjectLine = 'Re: ' + subject;
            var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch(getApiBase() + '/' + modId + '/actividades/foro/' + forumId + '/discusiones/' + discussionId + '/responder', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ subject: subjectLine, message: message }),
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    document.getElementById('replyMessage').value = '';
                    mostrarToast('success', 'Respuesta publicada.');
                    verPosts(modId, forumId, discussionId, subject);
                } else {
                    mostrarToast('error', data.message || 'Error al publicar.');
                }
            })
            .catch(function () { mostrarToast('error', 'Error de conexion.'); });
        };
    }

    function cerrarModalPosts() {
        var modal = document.getElementById('modalPosts');
        if (modal) modal.style.display = 'none';
    }

    function cerrarModalDisc() {
        document.getElementById('modalDiscusiones').classList.remove('open');
    }

    // ============================================================
    // CALIFICAR FORO
    // ============================================================

    function calificarForo(moduloId, cmid, forumId, nombre) {
        var modal = document.getElementById('modalCalificarTarea');
        if (!modal) return;
        document.getElementById('calificarTareaNombre').textContent = nombre + ' (Foro)';
        document.getElementById('calificarLoading').classList.remove('d-none');
        document.getElementById('calificarContent').classList.add('d-none');
        document.getElementById('calificarError').classList.add('d-none');
        // Cambiar headers para foro
        var ths = document.querySelectorAll('#modalCalificarTarea thead th');
        if (ths.length >= 5) { ths[1].textContent = 'Posts'; ths[3].textContent = ''; }
        modal.style.display = 'flex';

        fetch(getApiBase() + '/' + moduloId + '/actividades/' + cmid + '/foro/calificaciones', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (r) { if (!r.ok) return r.json().catch(function () { throw new Error('Error del servidor (' + r.status + ')'); }).then(function (d) { throw new Error(d.message || d.error || 'Error del servidor'); }); return r.json(); })
            .then(function (data) {
                document.getElementById('calificarLoading').classList.add('d-none');
                if (!data.success) throw new Error(data.message);
                var tbody = document.getElementById('calificarTableBody');
                tbody.innerHTML = '';
                if (!data.students || data.students.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-muted text-center">No hay estudiantes matriculados.</td></tr>';
                    document.getElementById('calificarContent').classList.remove('d-none');
                    return;
                }
                data.students.forEach(function (s) {
                    var postsLabel = s.post_count + ' post' + (s.post_count !== 1 ? 's' : '');
                    var gradeVal = s.grade !== null && s.grade !== undefined ? s.grade : '';
                    tbody.innerHTML +=
                        '<tr>' +
                            '<td><strong>' + escHtml(s.name) + '</strong><br><small style="color:#6c757d;">' + escHtml(s.carnet) + '</small></td>' +
                            '<td>' + postsLabel + '</td>' +
                            '<td><input type="number" class="form-control form-control-sm grade-input" data-user="' + s.userid + '" value="' + gradeVal + '" min="0" max="100" step="0.5"></td>' +
                            '<td></td>' +
                            '<td><button class="btn btn-sm btn-primary" onclick="ActividadesEditor.guardarCalificacionForo(' + moduloId + ', ' + cmid + ', ' + s.userid + ')">Guardar</button></td>' +
                        '</tr>';
                });
                document.getElementById('calificarContent').classList.remove('d-none');
            })
            .catch(function (err) {
                document.getElementById('calificarLoading').classList.add('d-none');
                document.getElementById('calificarErrorMsg').textContent = err.message || 'Error al cargar calificaciones.';
                document.getElementById('calificarError').classList.remove('d-none');
            });
    }

    function guardarCalificacionForo(moduloId, cmid, userId) {
        var gradeInput = document.querySelector('.grade-input[data-user="' + userId + '"]') || document.querySelector('.grade-input-foro[data-user="' + userId + '"]');
        var grade = gradeInput ? gradeInput.value : 0;
        var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch(getApiBase() + '/' + moduloId + '/actividades/' + cmid + '/foro/calificar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ user_id: userId, grade: grade }),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                mostrarToast('success', data.message || 'Calificación guardada.');
            } else {
                mostrarToast('error', data.message || 'Error al guardar calificación.');
            }
        })
        .catch(function () { mostrarToast('error', 'Error de conexión.'); });
    }

    function mostrarFormNuevaDisc() {
        cerrarModalDisc();
        document.getElementById('discSubject').value = '';
        document.getElementById('discMessage').value = '';
        document.getElementById('modalNuevaDisc').classList.add('open');
    }

    function cerrarModalNuevaDisc() {
        document.getElementById('modalNuevaDisc').classList.remove('open');
    }

    function submitNuevaDisc() {
        var subject = document.getElementById('discSubject').value.trim();
        var message = document.getElementById('discMessage').value.trim();
        if (!subject || !message) {
            mostrarToast('error', 'Completa el asunto y el mensaje.');
            return;
        }
        var btn = document.getElementById('btnGuardarDisc');
        btn.disabled = true;
        btn.textContent = 'Publicando...';

        fetch(getApiBase() + '/' + _discModuloId + '/actividades/foro/' + _discForumId + '/discusion', {
            method: 'POST',
            headers: jsonHeaders(),
            body: JSON.stringify({ subject: subject, message: message })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-send-plane-line"></i> Publicar Discusión';
            if (data.success) {
                cerrarModalNuevaDisc();
                mostrarToast('success', 'Discusión publicada correctamente en Moodle.');
            } else {
                mostrarToast('error', data.message || 'Error al publicar.');
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-send-plane-line"></i> Publicar Discusión';
            mostrarToast('error', 'Error de conexión.');
        });
    }

    // ============================================================
    // TOGGLE CONTENIDO
    // ============================================================

    function toggleContenido(btn, event) {
        if (event) { event.preventDefault(); event.stopPropagation(); }
        var item = btn.closest('.act-item');
        var contenido = item.querySelector('.act-contenido');
        if (!contenido) return;
        if (contenido.style.display === 'none' || contenido.style.display === '') {
            contenido.style.display = 'block';
            btn.innerHTML = '<i class="ri-arrow-up-s-line"></i> Ocultar contenido';
            btn.classList.add('expanded');
        } else {
            contenido.style.display = 'none';
            btn.innerHTML = '<i class="ri-arrow-down-s-line"></i> Ver contenido';
            btn.classList.remove('expanded');
        }
    }

    function toggleSeccionContenido(btn, event) {
        if (event) { event.preventDefault(); event.stopPropagation(); }
        var card = btn.closest('.seccion-card');
        var body = card.querySelector('.seccion-body');
        var desc = body.querySelector('.seccion-descripcion');
        if (!desc) return;
        if (desc.style.display === 'none' || desc.style.display === '') {
            desc.style.display = 'block';
            btn.innerHTML = '<i class="ri-arrow-up-s-line"></i> Ocultar descripción';
        } else {
            desc.style.display = 'none';
            btn.innerHTML = '<i class="ri-arrow-down-s-line"></i> Ver descripción';
        }
    }

    // ============================================================
    // FILE UPLOAD (Recurso via subir-archivo)
    // ============================================================

    function iniciarSubidaArchivo(file, section, name, courseId) {
        var formData = new FormData();
        formData.append('file', file);
        formData.append('section', section);
        formData.append('name', name);
        formData.append('course_id', courseId);

        fetch(getApiBase() + '/' + getModuloId() + '/subir-archivo', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken() },
            body: formData,
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res.success) {
                mostrarToast('success', 'Recurso creado correctamente');
                cargarYRenderizar();
            } else {
                mostrarToast('error', res.message || 'Error al subir archivo');
            }
        })
        .catch(function () { mostrarToast('error', 'Error de conexión'); });
    }

    // ============================================================
    // CALIFICAR TAREA
    // ============================================================

    function calificarTarea(moduloId, cmid, nombre) {
        var modal = document.getElementById('modalCalificarTarea');
        if (!modal) return;
        document.getElementById('calificarTareaNombre').textContent = nombre;
        document.getElementById('calificarLoading').classList.remove('d-none');
        document.getElementById('calificarContent').classList.add('d-none');
        document.getElementById('calificarError').classList.add('d-none');
        // Restaurar headers por defecto
        var ths = document.querySelectorAll('#modalCalificarTarea thead th');
        if (ths.length >= 5) { ths[1].textContent = 'Estado'; ths[3].textContent = 'Feedback'; }
        modal.style.display = 'flex';

        fetch(getApiBase() + '/' + moduloId + '/actividades/' + cmid + '/entregas', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (r) { if (!r.ok) return r.json().catch(function () { throw new Error('Error del servidor (' + r.status + ')'); }).then(function (d) { throw new Error(d.message || d.error || 'Error del servidor'); }); return r.json(); })
            .then(function (data) {
                document.getElementById('calificarLoading').classList.add('d-none');
                if (!data.success) throw new Error(data.message);
                var tbody = document.getElementById('calificarTableBody');
                tbody.innerHTML = '';
                if (!data.students || data.students.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-muted text-center">No hay estudiantes matriculados.</td></tr>';
                    document.getElementById('calificarContent').classList.remove('d-none');
                    return;
                }
                data.students.forEach(function (s) {
                    var statusHtml = '';
                    if (s.has_submission) {
                        var label = s.status === 'submitted' ? 'Entregado' : 'Borrador';
                        var icon = s.status === 'submitted' ? 'ri-checkbox-circle-line' : 'ri-edit-line';
                        var color = s.status === 'submitted' ? '#16a34a' : '#f59e0b';
                        var lateTag = s.late ? ' <span style="color:#dc2626;font-size:.72rem;font-weight:600;"><i class="ri-time-warning-line"></i> Tardía</span>' : '';
                        var timeStr = s.timemodified ? fmtTsAdmin(s.timemodified) : '';
                        statusHtml = '<div style="display:flex;align-items:center;gap:.3rem;color:' + color + ';font-weight:600;font-size:.8rem;">' +
                            '<i class="' + icon + '"></i> ' + label + lateTag + '</div>' +
                            (timeStr ? '<div style="font-size:.7rem;color:#6c757d;margin-top:.15rem;">' + timeStr + '</div>' : '');
                        // Archivos
                        if (s.files && s.files.length > 0) {
                            s.files.forEach(function (f) {
                                var dlUrl = getApiBase() + '/' + moduloId + '/actividades/' + cmid + '/archivo/' + s.userid + '/' + encodeURIComponent(f.filename);
                                statusHtml += '<div style="margin-top:.25rem;font-size:.75rem;">' +
                                    '<a href="' + dlUrl + '" style="color:#3b82f6;text-decoration:none;display:inline-flex;align-items:center;gap:.25rem;" download><i class="ri-download-2-line"></i> ' + escHtml(f.filename) + '</a></div>';
                            });
                        }
                    } else {
                        statusHtml = '<span style="color:#94a3b8;">Sin entregar</span>';
                    }

                    var gradeVal = s.grade !== null && s.grade !== undefined ? s.grade : '';
                    var feedbackVal = s.feedback || '';
                    tbody.innerHTML +=
                        '<tr>' +
                            '<td><strong>' + escHtml(s.name) + '</strong><br><small style="color:#6c757d;">CI: ' + escHtml(s.carnet) + '</small></td>' +
                            '<td>' + statusHtml + '</td>' +
                            '<td><input type="number" class="form-control form-control-sm grade-input" data-user="' + s.userid + '" value="' + gradeVal + '" min="0" max="100" step="0.5"></td>' +
                            '<td><textarea class="form-control form-control-sm feedback-input" data-user="' + s.userid + '" rows="2">' + escHtml(feedbackVal) + '</textarea></td>' +
                            '<td><button class="btn btn-sm btn-primary" onclick="ActividadesEditor.guardarCalificacion(' + moduloId + ', ' + cmid + ', ' + s.userid + ')">Guardar</button></td>' +
                        '</tr>';
                });
                document.getElementById('calificarContent').classList.remove('d-none');
            })
            .catch(function (err) {
                document.getElementById('calificarLoading').classList.add('d-none');
                document.getElementById('calificarErrorMsg').textContent = err.message || 'Error al cargar entregas.';
                document.getElementById('calificarError').classList.remove('d-none');
            });
    }

    function guardarCalificacion(moduloId, cmid, userId) {
        var gradeInput = document.querySelector('.grade-input[data-user="' + userId + '"]');
        var feedbackInput = document.querySelector('.feedback-input[data-user="' + userId + '"]');
        var grade = gradeInput ? gradeInput.value : 0;
        var feedback = feedbackInput ? feedbackInput.value : '';
        var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch(getApiBase() + '/' + moduloId + '/actividades/' + cmid + '/calificar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ user_id: userId, grade: grade, feedback: feedback }),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                mostrarToast('success', data.message || 'Calificacion guardada.');
            } else {
                mostrarToast('error', data.message || 'Error al guardar calificacion.');
            }
        })
        .catch(function () { mostrarToast('error', 'Error de conexion.'); });
    }

    function cerrarModalCalificar() {
        var modal = document.getElementById('modalCalificarTarea');
        if (modal) modal.style.display = 'none';
    }

    // ============================================================
    // RESULTADOS QUIZ
    // ============================================================

    function verResultadosQuiz(moduloId, quizId, nombre) {
        var modal = document.getElementById('modalQuizResultados');
        if (!modal) return;
        document.getElementById('quizResultadosNombre').textContent = nombre;
        document.getElementById('quizLoading').classList.remove('d-none');
        document.getElementById('quizContent').classList.add('d-none');
        document.getElementById('quizAttemptDetail').classList.add('d-none');
        document.getElementById('quizError').classList.add('d-none');
        modal.style.display = 'flex';

        fetch(getApiBase() + '/' + moduloId + '/actividades/quiz/' + quizId + '/resultados')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                document.getElementById('quizLoading').classList.add('d-none');
                if (!data.success) throw new Error(data.message);
                var tbody = document.getElementById('quizTableBody');
                tbody.innerHTML = '';
                if (data.attempts.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-muted text-center">Sin intentos aun.</td></tr>';
                    document.getElementById('quizContent').classList.remove('d-none');
                    return;
                }
                data.attempts.forEach(function (a) {
                    var stateText = a.state === 'finished' ? 'Completado'
                        : a.state === 'inprogress' ? 'En progreso'
                        : a.state === 'overdue' ? 'Vencido'
                        : a.state;
                    var score = a.grade !== null ? a.grade : '-';
                    tbody.innerHTML +=
                        '<tr>' +
                            '<td><strong>' + escHtml(a.user_name || 'Usuario #' + a.userid) + '</strong></td>' +
                            '<td>' + a.attempt + '</td>' +
                            '<td>' + stateText + '</td>' +
                            '<td>' + score + ' / ' + (a.grade_max || 100) + '</td>' +
                            '<td><button class="btn btn-sm btn-outline-info" onclick="ActividadesEditor.verDetalleIntento(' + moduloId + ', ' + quizId + ', ' + a.id + ')">Ver detalle</button></td>' +
                        '</tr>';
                });
                document.getElementById('quizContent').classList.remove('d-none');
            })
            .catch(function (err) {
                document.getElementById('quizLoading').classList.add('d-none');
                document.getElementById('quizErrorText').textContent = err.message || 'Error al cargar resultados.';
                document.getElementById('quizError').classList.remove('d-none');
            });
    }

    function verDetalleIntento(moduloId, quizId, attemptId) {
        var container = document.getElementById('quizQuestionsContainer');
        container.innerHTML = '<div class="text-center py-2"><div class="spinner-border spinner-border-sm"></div> Cargando...</div>';
        document.getElementById('quizAttemptDetail').classList.remove('d-none');

        fetch(getApiBase() + '/' + moduloId + '/actividades/quiz/' + quizId + '/resultados/' + attemptId)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.success) throw new Error(data.message);
                container.innerHTML = '';
                data.questions.forEach(function (q) {
                    var pct = (q.fraction * 100).toFixed(0);
                    var badgeClass = q.fraction >= 1 ? 'bg-success' : q.fraction > 0 ? 'bg-warning text-dark' : 'bg-danger';
                    container.innerHTML +=
                        '<div class="card mb-2">' +
                            '<div class="card-body py-2">' +
                                '<small class="text-muted">Pregunta ' + q.questionnumber + '</small>' +
                                '<div class="mt-1">' + (q.questiontext || '') + '</div>' +
                                '<div class="mt-1"><strong>Respuesta:</strong> ' + (q.response || '(sin respuesta)') + '</div>' +
                                (q.rightanswer ? '<div class="mt-1"><strong>Correcta:</strong> ' + q.rightanswer + '</div>' : '') +
                                '<div class="mt-1"><span class="badge ' + badgeClass + '">' + pct + '%</span></div>' +
                            '</div>' +
                        '</div>';
                });
            })
            .catch(function (err) {
                container.innerHTML = '<div class="alert alert-danger">' + (err.message || 'Error al cargar detalle.') + '</div>';
            });
    }

    function cerrarModalQuiz() {
        var modal = document.getElementById('modalQuizResultados');
        if (modal) modal.style.display = 'none';
    }

    // ============================================================
    // GESTIÓN DE PREGUNTAS
    // ============================================================

    var currentQuizId;

    function verPreguntasQuiz(moduloId, quizId, nombre) {
        currentQuizId = quizId;
        document.getElementById('preguntasQuizNombre').textContent = nombre;
        document.getElementById('preguntasLoading').classList.remove('d-none');
        document.getElementById('preguntasContent').classList.add('d-none');
        document.getElementById('preguntasError').classList.add('d-none');
        var modal = document.getElementById('modalPreguntasQuiz');
        if (modal) modal.classList.add('open');

        console.log('Fetching preguntas for quiz', quizId, 'in modulo', moduloId);
        fetch(getApiBase() + '/' + moduloId + '/actividades/quiz/' + quizId + '/preguntas')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                document.getElementById('preguntasLoading').classList.add('d-none');
                if (!data.success) throw new Error(data.message);
                renderPreguntasList(data.questions);
                document.getElementById('preguntasContent').classList.remove('d-none');
            })
            .catch(function (err) {
                document.getElementById('preguntasLoading').classList.add('d-none');
                document.getElementById('preguntasErrorTxt').textContent = err.message || 'Error al cargar preguntas.';
                document.getElementById('preguntasError').classList.remove('d-none');
            });
    }

    function renderPreguntasList(questions) {
        var container = document.getElementById('preguntasList');
        container.innerHTML = '';
        if (!questions || questions.length === 0) {
            container.innerHTML = '<p class="text-muted">No hay preguntas en este cuestionario.</p>';
            return;
        }
        var typeLabels = { multichoice: 'Opción múltiple', truefalse: 'V/F', match: 'Coincidencia' };
        questions.forEach(function (q) {
            container.innerHTML +=
                '<div class="disc-item" style="margin-bottom:6px;">' +
                    '<div style="display:flex;justify-content:space-between;align-items:center;">' +
                        '<div><div class="disc-item-name">' + escHtml(q.name) + '</div>' +
                        '<div class="disc-item-meta">' + (typeLabels[q.qtype] || q.qtype) + ' · ' + q.defaultmark + ' pts</div></div>' +
                        '<button class="btn btn-sm btn-outline-danger" onclick="ActividadesEditor.eliminarPregunta(' + currentQuizId + ', ' + q.slot_id + ')"><i class="ri-delete-bin-line"></i></button>' +
                    '</div>' +
                '</div>';
        });
    }

    function eliminarPregunta(quizId, slotId) {
        if (!confirm('¿Eliminar esta pregunta del cuestionario?')) return;
        var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        fetch(getApiBase() + '/' + getModuloId() + '/actividades/quiz/' + quizId + '/preguntas/' + slotId, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf },
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                mostrarToast('success', data.message || 'Pregunta eliminada.');
                verPreguntasQuiz(getModuloId(), quizId, document.getElementById('preguntasQuizNombre').textContent);
            } else {
                mostrarToast('error', data.message || 'Error al eliminar.');
            }
        })
        .catch(function () { mostrarToast('error', 'Error de conexión.'); });
    }

    function cerrarModalPreguntas() {
        var modal = document.getElementById('modalPreguntasQuiz');
        if (modal) modal.classList.remove('open');
    }

    // ── Crear pregunta opción múltiple ──

    function mostrarFormMC() {
        document.getElementById('mcName').value = '';
        document.getElementById('mcQuestionText').value = '';
        document.getElementById('mcDefaultMark').value = '1';
        document.getElementById('mcSingle').value = 'true';
        document.getElementById('mcOptionsContainer').innerHTML =
            '<div class="mc-option" style="display:flex;gap:0.5rem;margin-bottom:4px;"><input style="width:60%;" class="form-control form-control-sm" placeholder="Texto" id="mcOpt0"><input style="width:60px;" class="form-control form-control-sm" type="number" step="0.01" value="0" id="mcFrac0"><button class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()">X</button></div>' +
            '<div class="mc-option" style="display:flex;gap:0.5rem;"><input style="width:60%;" class="form-control form-control-sm" placeholder="Texto" id="mcOpt1"><input style="width:60px;" class="form-control form-control-sm" type="number" step="0.01" value="0" id="mcFrac1"><button class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()">X</button></div>';
        document.getElementById('modalMC').classList.add('open');
    }

    function cerrarModalMC() {
        document.getElementById('modalMC').classList.remove('open');
    }

    function guardarMC() {
        var name = document.getElementById('mcName').value.trim();
        var qtext = document.getElementById('mcQuestionText').value.trim();
        if (!name || !qtext) { mostrarToast('error', 'Nombre y texto de pregunta requeridos.'); return; }

        var options = [];
        var optDivs = document.querySelectorAll('#mcOptionsContainer .mc-option');
        optDivs.forEach(function (div, i) {
            var text = div.querySelector('input[placeholder="Texto"]')?.value || '';
            var frac = parseFloat(div.querySelector('input[type="number"]')?.value || '0');
            if (text) options.push({ text: text, fraction: frac, feedback: '' });
        });
        if (options.length < 2) { mostrarToast('error', 'Debe tener al menos 2 opciones.'); return; }

        var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        var defaultMark = parseFloat(document.getElementById('mcDefaultMark').value) || 1;
        var single = document.getElementById('mcSingle').value;

        fetch(getApiBase() + '/' + getModuloId() + '/actividades/quiz/' + currentQuizId + '/preguntas/multichoice', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ name: name, questiontext: qtext, defaultmark: defaultMark, single: single, options: options }),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                cerrarModalMC();
                mostrarToast('success', 'Pregunta creada.');
                verPreguntasQuiz(getModuloId(), currentQuizId, document.getElementById('preguntasQuizNombre').textContent);
            } else {
                mostrarToast('error', data.message || 'Error al crear.');
            }
        })
        .catch(function () { mostrarToast('error', 'Error de conexión.'); });
    }

    // ── Crear pregunta V/F ──

    function mostrarFormTF() {
        document.getElementById('tfName').value = '';
        document.getElementById('tfQuestionText').value = '';
        document.getElementById('tfDefaultMark').value = '1';
        document.getElementById('tfCorrect').value = 'true';
        document.getElementById('modalTF').classList.add('open');
    }

    function cerrarModalTF() {
        document.getElementById('modalTF').classList.remove('open');
    }

    function guardarTF() {
        var name = document.getElementById('tfName').value.trim();
        var qtext = document.getElementById('tfQuestionText').value.trim();
        if (!name || !qtext) { mostrarToast('error', 'Nombre y texto requeridos.'); return; }

        var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        var defaultMark = parseFloat(document.getElementById('tfDefaultMark').value) || 1;
        var correct = document.getElementById('tfCorrect').value;

        fetch(getApiBase() + '/' + getModuloId() + '/actividades/quiz/' + currentQuizId + '/preguntas/truefalse', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ name: name, questiontext: qtext, defaultmark: defaultMark, correctanswer: correct }),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                cerrarModalTF();
                mostrarToast('success', 'Pregunta creada.');
                verPreguntasQuiz(getModuloId(), currentQuizId, document.getElementById('preguntasQuizNombre').textContent);
            } else {
                mostrarToast('error', data.message || 'Error al crear.');
            }
        })
        .catch(function () { mostrarToast('error', 'Error de conexión.'); });
    }

    // ── Crear pregunta coincidencia ──

    function mostrarFormMatch() {
        document.getElementById('matchName').value = '';
        document.getElementById('matchQuestionText').value = '';
        document.getElementById('matchDefaultMark').value = '1';
        document.getElementById('matchPairsContainer').innerHTML =
            '<div class="match-pair" style="display:flex;gap:0.5rem;margin-bottom:4px;"><input style="width:40%;" class="form-control form-control-sm" placeholder="Pregunta" id="mpQ0"><input style="width:40%;" class="form-control form-control-sm" placeholder="Respuesta" id="mpA0"><button class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()">X</button></div>' +
            '<div class="match-pair" style="display:flex;gap:0.5rem;"><input style="width:40%;" class="form-control form-control-sm" placeholder="Pregunta" id="mpQ1"><input style="width:40%;" class="form-control form-control-sm" placeholder="Respuesta" id="mpA1"><button class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()">X</button></div>';
        document.getElementById('modalMatch').classList.add('open');
    }

    function cerrarModalMatch() {
        document.getElementById('modalMatch').classList.remove('open');
    }

    function guardarMatch() {
        var name = document.getElementById('matchName').value.trim();
        var qtext = document.getElementById('matchQuestionText').value.trim();
        if (!name || !qtext) { mostrarToast('error', 'Nombre y texto requeridos.'); return; }

        var pairs = [];
        document.querySelectorAll('#matchPairsContainer .match-pair').forEach(function (div) {
            var q = div.querySelector('input[placeholder="Pregunta"]')?.value || '';
            var a = div.querySelector('input[placeholder="Respuesta"]')?.value || '';
            if (q && a) pairs.push({ question: q, answer: a });
        });
        if (pairs.length < 2) { mostrarToast('error', 'Debe tener al menos 2 pares.'); return; }

        var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        var defaultMark = parseFloat(document.getElementById('matchDefaultMark').value) || 1;

        fetch(getApiBase() + '/' + getModuloId() + '/actividades/quiz/' + currentQuizId + '/preguntas/matching', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ name: name, questiontext: qtext, defaultmark: defaultMark, pairs: pairs }),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                cerrarModalMatch();
                mostrarToast('success', 'Pregunta creada.');
                verPreguntasQuiz(getModuloId(), currentQuizId, document.getElementById('preguntasQuizNombre').textContent);
            } else {
                mostrarToast('error', data.message || 'Error al crear.');
            }
        })
        .catch(function () { mostrarToast('error', 'Error de conexión.'); });
    }

    // ============================================================
    // INIT
    // ============================================================

    function init() {
        // Initial load is handled by existing inline tab-activation code.
        // This function only sets up modal and button event handlers.

        var saveBtn = document.querySelector('#modalForm .btn-save');
        if (saveBtn) {
            saveBtn.addEventListener('click', guardarModal);
        }

        document.querySelector('#modalForm .modal-close')?.addEventListener('click', cerrarModal);
        document.querySelector('#modalForm .btn-cancel')?.addEventListener('click', cerrarModal);
        document.getElementById('modalForm')?.addEventListener('click', function (e) {
            if (e.target === this) cerrarModal();
        });

        document.getElementById('btnAbrirNuevaDisc')?.addEventListener('click', mostrarFormNuevaDisc);
        document.getElementById('btnGuardarDisc')?.addEventListener('click', submitNuevaDisc);
        document.querySelector('#modalNuevaDisc .modal-close')?.addEventListener('click', cerrarModalNuevaDisc);
        document.querySelector('#modalNuevaDisc .btn-cancel')?.addEventListener('click', cerrarModalNuevaDisc);
        document.getElementById('modalNuevaDisc')?.addEventListener('click', function (e) {
            if (e.target === this) cerrarModalNuevaDisc();
        });
        document.getElementById('modalDiscusiones')?.addEventListener('click', function (e) {
            if (e.target === this) cerrarModalDisc();
        });
    }

    // Expose public API
    return {
        init: init,
        cargarYRenderizar: cargarYRenderizar,
        abrirDiscusiones: abrirDiscusiones,
        cerrarModalDisc: cerrarModalDisc,
        verPosts: verPosts,
        cerrarModalPosts: cerrarModalPosts,
        mostrarFormNuevaDisc: mostrarFormNuevaDisc,
        cerrarModalNuevaDisc: cerrarModalNuevaDisc,
        submitNuevaDisc: submitNuevaDisc,
        toggleContenido: toggleContenido,
        toggleSeccionContenido: toggleSeccionContenido,
        abrirModal: abrirModal,
        cerrarModal: cerrarModal,
        guardarModal: guardarModal,
        iniciarSubidaArchivo: iniciarSubidaArchivo,
        calificarTarea: calificarTarea,
        guardarCalificacion: guardarCalificacion,
        calificarForo: calificarForo,
        guardarCalificacionForo: guardarCalificacionForo,
        cerrarModalCalificar: cerrarModalCalificar,
        verResultadosQuiz: verResultadosQuiz,
        verDetalleIntento: verDetalleIntento,
        cerrarModalQuiz: cerrarModalQuiz,
        verPreguntasQuiz: verPreguntasQuiz,
        eliminarPregunta: eliminarPregunta,
        cerrarModalPreguntas: cerrarModalPreguntas,
        mostrarFormMC: mostrarFormMC,
        cerrarModalMC: cerrarModalMC,
        guardarMC: guardarMC,
        mostrarFormTF: mostrarFormTF,
        cerrarModalTF: cerrarModalTF,
        guardarTF: guardarTF,
        mostrarFormMatch: mostrarFormMatch,
        cerrarModalMatch: cerrarModalMatch,
        guardarMatch: guardarMatch,
    };
})();

// Auto-init on DOM ready
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('tab-actividades')) {
        ActividadesEditor.init();
    }
});
