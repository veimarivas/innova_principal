/**
 * ActividadesEditor — CRUD de secciones y actividades del curso Moodle.
 * Se carga desde modulo-detalle.blade.php.
 * Dependencias globales: Sortable (sortablejs), ClassicEditor (CKEditor 5).
 */
var ActividadesEditor = (function () {
    'use strict';

    var moduloId = null;
    var moodleUrl = '';
    var urlsByCmid = {};
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
            urlsByCmid = data.urls_by_cmid || {};

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
                        var tareaFile = tareasMap[mod.instance] || tareasByCmid[mod.id];
                        var hasIntroFile = tareaFile && (
                            (Array.isArray(tareaFile.introfiles) && tareaFile.introfiles.length > 0) ||
                            parseInt(tareaFile.introattachments || 0) > 0
                        );
                        if (hasIntroFile) {
                            var adjUrl = getApiBase() + '/' + getModuloId() + '/actividades/' + mod.id + '/adjunto-intro';
                            extraBtns += '<a href="' + adjUrl + '" target="_blank" class="btn-act-link btn-act-resource"><i class="ri-download-line"></i> Descargar adjunto</a>';
                        }
                        extraBtns += '<button class="btn-act-link btn-act-grade" onclick="ActividadesEditor.calificarTarea(' + getModuloId() + ', ' + mod.id + ', \'' + escHtml(mod.name) + '\')"><i class="ri-bar-chart-line"></i> Calificar</button>';
                    } else if (mod.modname === 'quiz') {
                        extraBtns = '<button class="btn-act-link btn-act-quiz" onclick="ActividadesEditor.verPreguntasQuiz(' + getModuloId() + ', ' + (mod.instance || 0) + ', \'' + escHtml(mod.name) + '\')"><i class="ri-question-line"></i> Preguntas</button>' +
                            '<button class="btn-act-link btn-act-quiz" onclick="ActividadesEditor.verResultadosQuiz(' + getModuloId() + ', ' + (mod.instance || 0) + ', \'' + escHtml(mod.name) + '\')"><i class="ri-bar-chart-grouped-line"></i> Resultados</button>';
                    } else if (mod.modname === 'resource') {
                        var recursoUrl = getApiBase() + '/' + getModuloId() + '/actividades/' + mod.id + '/recurso';
                        extraBtns = '<a href="' + recursoUrl + '" target="_blank" class="btn-act-link btn-act-resource"><i class="ri-eye-line"></i> Visualizar</a>' +
                            '<a href="' + recursoUrl + '?download=1" class="btn-act-link btn-act-resource"><i class="ri-download-line"></i> Descargar</a>';
                    } else if (mod.modname === 'url') {
                        var rawUrl = (urlsByCmid[mod.id] && (urlsByCmid[mod.id].externalurl || urlsByCmid[mod.id])) || mod.externalurl || '';
                        if (typeof rawUrl === 'object' && rawUrl.externalurl) rawUrl = rawUrl.externalurl;
                        if (rawUrl && !/^https?:\/\//i.test(rawUrl)) rawUrl = 'https://' + rawUrl;
                        var destUrl = rawUrl || cmUrl;
                        extraBtns = '<a href="' + escHtml(destUrl) + '" target="_blank" rel="noopener noreferrer" class="btn-act-link btn-act-url"><i class="ri-external-link-line"></i> Abrir enlace</a>';
                    }

                    var tieneCont = mod.description && mod.description.trim().length > 0;
                    var toggleBtn = tieneCont && mod.modname !== 'assign' && mod.modname !== 'forum'
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
                        var openTs = (mod.activity_dates && mod.activity_dates.open)
                            || (foro && foro.timeopen)
                            || (foro && foro.dates && foro.dates.open)
                            || 0;
                        var dueTs = (mod.activity_dates && mod.activity_dates.close)
                            || (mod.activity_dates && mod.activity_dates.due)
                            || (foro && foro.timeclose)
                            || (foro && foro.cutoffdate)
                            || (foro && foro.duedate)
                            || (foro && foro.dates && foro.dates.close)
                            || (foro && foro.dates && foro.dates.due)
                            || 0;

                        var htmlFechas = '';
                        var nowF = Math.floor(Date.now() / 1000);
                        if (openTs) {
                            var fechaOpen = new Date(openTs * 1000);
                            htmlFechas += '<span class="act-date-chip act-date-open"><i class="ri-calendar-event-line"></i> Inicio: ' + fechaOpen.toLocaleString('es-BO', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' }) + '</span>';
                        }
                        if (dueTs) {
                            var fechaDue = new Date(dueTs * 1000);
                            var vencido = dueTs < nowF;
                            htmlFechas += '<span class="act-date-chip act-date-due' + (vencido ? ' act-date-overdue' : '') + '"><i class="ri-calendar-check-line"></i> Vencimiento: ' + fechaDue.toLocaleString('es-BO', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' }) + '</span>';
                        }
                        if (htmlFechas) extraInfo = '<div class="act-dates-row">' + htmlFechas + '</div>';

                        if (foro) {
                            editExtra.forum_type = foro.type || 'general';
                            editExtra.subscription = foro.forcesubscribe || 0;
                            editExtra.intro = foro.intro || '';
                        }
                    }

                    var descHtml = tieneCont ? '<div class="act-contenido act-contenido-assign" style="display:block;margin:4px 0 0 0;padding:0.4rem 0;background:transparent;border:none;border-top:1px dashed var(--d-card-border);font-size:0.82rem;color:var(--d-body);line-height:1.5;">' + mod.description + '</div>' : '';
                    var downloadLink = (mod.modname === 'assign' && hasIntroFile)
                        ? '<div style="margin-top:6px;"><a href="' + getApiBase() + '/' + getModuloId() + '/actividades/' + mod.id + '/adjunto-intro" class="btn-act-link btn-act-download" target="_blank" style="display:inline-flex;align-items:center;gap:0.3rem;padding:0.25rem 0.65rem;font-size:0.75rem;font-weight:600;border-radius:5px;background:rgba(37,99,235,.1);color:#2563eb;text-decoration:none;cursor:pointer;"><i class="ri-download-2-line"></i> Descargar archivo</a></div>'
                        : '';
                    var descInline = (mod.modname === 'assign' || mod.modname === 'forum') ? descHtml + downloadLink : '';

                    item.innerHTML =
                        '<div class="act-item-left">' +
                            '<span class="drag-handle"><i class="ri-draggable"></i></span>' +
                            '<div class="act-icon ' + info.cls + '"><i class="' + info.icon + '"></i></div>' +
                            '<div>' +
                                '<div class="act-name">' + escHtml(mod.name) + '</div>' +
                                '<div class="act-tipo">' + info.label + '</div>' +
                                descInline +
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

                    if (tieneCont && mod.modname !== 'assign' && mod.modname !== 'forum') {
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
            '<div class="form-row"><div class="form-group" style="flex:1;"><label style="font-size:.78rem;font-weight:600;color:#475569;">Máxima calificación</label><div style="display:flex;align-items:center;gap:.4rem;"><input class="form-control" id="fldGrade" type="number" value="' + (editando && data.grade ? data.grade : 100) + '" min="0" max="10000" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .6rem;width:100px;"> <span style="font-size:.78rem;color:#94a3b8;">puntos</span></div></div></div></div>' +

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
            '<div class="form-group" style="margin-bottom:.85rem;">' +
            '<label style="display:flex;align-items:center;gap:.35rem;font-size:.85rem;font-weight:700;color:#1e293b;margin-bottom:.3rem;"><i class="ri-questionnaire-line" style="color:#d97706;"></i> Nombre del cuestionario <span class="required" style="color:#dc2626;">*</span></label>' +
            '<input class="form-control" id="fldName" value="' + nameVal + '" placeholder="Ej: Cuestionario Semana 1" style="font-size:.87rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.5rem .7rem;"></div>' +

            '<div class="form-group" style="margin-bottom:.85rem;">' +
            '<label style="display:flex;align-items:center;gap:.35rem;font-size:.85rem;font-weight:700;color:#1e293b;margin-bottom:.3rem;"><i class="ri-file-text-line" style="color:#64748b;"></i> Descripción / Instrucciones</label>' +
            '<textarea class="form-control" id="fldDescription" rows="3" placeholder="Instrucciones del cuestionario..." style="font-size:.85rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.5rem .7rem;">' + introVal + '</textarea></div>' +

            '<div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.85rem 1rem;margin:.8rem 0;">' +
            '<div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem;font-weight:700;color:#1e293b;margin-bottom:.75rem;"><i class="ri-calendar-line" style="color:#2563eb;"></i> Temporalización</div>' +
            '<div class="form-row" style="gap:.6rem;">' +
            '<div class="form-group" style="flex:1;min-width:0;"><label style="font-size:.78rem;font-weight:600;color:#475569;display:flex;align-items:center;gap:.3rem;margin-bottom:.25rem;"><i class="ri-calendar-check-line" style="color:#16a34a;font-size:.8rem;"></i> Abierto desde</label><input class="form-control" id="fldOpenDate" type="datetime-local" value="' + (editando ? tsToDateInput(data.timeopen) : '') + '" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .6rem;"></div>' +
            '<div class="form-group" style="flex:1;min-width:0;"><label style="font-size:.78rem;font-weight:600;color:#475569;display:flex;align-items:center;gap:.3rem;margin-bottom:.25rem;"><i class="ri-calendar-close-line" style="color:#dc2626;font-size:.8rem;"></i> Cierra</label><input class="form-control" id="fldCloseDate" type="datetime-local" value="' + (editando ? tsToDateInput(data.timeclose) : '') + '" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .6rem;"></div>' +
            '</div>' +
            '<div class="form-row" style="gap:.6rem;margin-top:.5rem;">' +
            '<div class="form-group" style="flex:1;min-width:0;max-width:180px;"><label style="font-size:.78rem;font-weight:600;color:#475569;display:flex;align-items:center;gap:.3rem;margin-bottom:.25rem;"><i class="ri-timer-line" style="color:#7c3aed;font-size:.8rem;"></i> Límite de tiempo</label>' +
            '<div style="display:flex;align-items:center;gap:.4rem;"><input class="form-control" id="fldTime" type="number" value="' + (editando && data.timelimit != null ? data.timelimit : 30) + '" min="0" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .6rem;width:80px;"> <span style="font-size:.78rem;color:#94a3b8;white-space:nowrap;">minutos</span></div></div>' +
            '<div style="flex:1;min-width:0;padding:.5rem .75rem;background:rgba(37,99,235,.05);border:1px solid rgba(37,99,235,.15);border-radius:8px;font-size:.75rem;color:#1e40af;display:flex;align-items:center;gap:.4rem;"><i class="ri-information-line" style="flex-shrink:0;"></i><span>Poner <strong>0</strong> en tiempo para desactivar el límite.</span></div>' +
            '</div></div>' +

            '<div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.85rem 1rem;margin:.8rem 0;">' +
            '<div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem;font-weight:700;color:#1e293b;margin-bottom:.75rem;"><i class="ri-bar-chart-line" style="color:#d97706;"></i> Calificación</div>' +
            '<div class="form-row" style="gap:.6rem;">' +
            '<div class="form-group" style="flex:1;min-width:0;"><label style="font-size:.78rem;font-weight:600;color:#475569;margin-bottom:.25rem;display:block;">Máxima calificación</label><div style="display:flex;align-items:center;gap:.4rem;"><input class="form-control" id="fldGrade" type="number" value="' + (editando && data.grade ? data.grade : 100) + '" min="0" max="1000" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .6rem;width:100px;"> <span style="font-size:.78rem;color:#94a3b8;">puntos</span></div></div>' +
            '<div class="form-group" style="flex:1;min-width:0;"><label style="font-size:.78rem;font-weight:600;color:#475569;margin-bottom:.25rem;display:block;">Intentos permitidos</label><select class="form-control" id="fldAttempts" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.35rem .5rem;">' + quizAttemptsSel + '</select></div>' +
            '</div></div>';

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
            '<div class="form-row"><div class="form-group" style="flex:1;"><label style="font-size:.78rem;font-weight:600;color:#475569;">Máxima calificación</label><div style="display:flex;align-items:center;gap:.4rem;"><input class="form-control" id="fldGrade" type="number" value="' + (editando && data.grade ? data.grade : 100) + '" min="0" max="10000" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .6rem;width:100px;"> <span style="font-size:.78rem;color:#94a3b8;">puntos</span></div></div></div></div>';

        var templates = {
            assign: assignHTML,
            quiz: quizHTML,
            forum: forumHTML,
            page: '<div class="form-group"><label>Nombre de la página <span class="required">*</span></label><input class="form-control" id="fldName" value="' + nameVal + '" placeholder="Ej: Introducción"></div><div class="form-group"><label>Contenido</label><div class="wysiwyg-toolbar"><button onclick="document.execCommand(\'bold\')"><strong>B</strong></button><button onclick="document.execCommand(\'italic\')"><em>I</em></button><button onclick="document.execCommand(\'insertOrderedList\')"><i class="ri-list-ordered"></i></button><button onclick="document.execCommand(\'insertUnorderedList\')"><i class="ri-list-unordered"></i></button></div><div class="wysiwyg-editor" contenteditable="true" id="fldContent" style="min-height:150px;"></div></div>',
            url: '<div class="form-group"><label>Nombre <span class="required">*</span></label><input class="form-control" id="fldName" value="' + nameVal + '" placeholder="Ej: Video complementario"></div><div class="form-group"><label>URL <span class="required">*</span></label><input class="form-control" id="fldExternalUrl" type="url" value="' + (editando ? escHtml(data.externalurl || '') : '') + '" placeholder="https://ejemplo.com/video"></div><div class="form-group"><label>Descripción</label><textarea class="form-control" id="fldDescription" rows="3">' + introVal + '</textarea></div><div class="form-group"><label>Abrir en</label><select class="form-control" id="fldDisplay"><option value="3"' + (editando && parseInt(data.display)===3 ? ' selected' : '') + '>Nueva ventana</option><option value="5"' + (editando && parseInt(data.display)===5 ? ' selected' : (!editando ? ' selected' : '')) + '>Misma ventana</option><option value="1"' + (editando && parseInt(data.display)===1 ? ' selected' : '') + '>Incrustado</option></select></div>',
            resource:
                '<div class="form-group"><label style="display:flex;align-items:center;gap:.35rem;font-size:.85rem;font-weight:700;color:#1e293b;"><i class="ri-file-line" style="color:#0284c7;"></i> Nombre del recurso <span class="required">*</span></label><input class="form-control" id="fldName" value="' + nameVal + '" placeholder="Ej: Material de lectura" style="font-size:.87rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.5rem .7rem;"></div>'
                + '<div class="form-group"><label style="font-size:.85rem;font-weight:700;color:#1e293b;margin-top:.2rem;">Descripción</label><textarea class="form-control" id="fldDescription" rows="3" style="font-size:.85rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.5rem .7rem;">' + introVal + '</textarea></div>'
                + (!editando
                    ? '<div class="form-group"><label style="display:flex;align-items:center;gap:.35rem;font-size:.85rem;font-weight:700;color:#1e293b;margin-top:.2rem;"><i class="ri-upload-cloud-line" style="color:#0284c7;"></i> Archivo <span class="required">*</span></label>'
                      + '<input type="file" class="form-control" id="fldArchivoRecurso" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.mp4,.jpg,.jpeg,.png" style="font-size:.83rem;border-radius:8px;border:1.5px solid #e2e8f0;padding:.4rem .5rem;">'
                      + '</div>'
                    : '<div style="padding:.65rem .9rem;background:rgba(14,165,233,.07);border:1px solid rgba(14,165,233,.25);border-radius:8px;font-size:.82rem;color:#0369a1;"><i class="ri-information-line"></i> Al editar un recurso solo se puede cambiar el nombre y la descripción. Para reemplazar el archivo, elimine este recurso y cree uno nuevo.</div>'),
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
            payload.display = parseInt(document.getElementById('fldDisplay')?.value || '5');
        }

        var adjuntoFile = (tipo === 'assign') ? (document.getElementById('fldAdjunto')?.files[0] || null) : null;
        var recursoFile = (tipo === 'resource') ? (document.getElementById('fldArchivoRecurso')?.files[0] || null) : null;

        var saveBtn = overlay.querySelector('.btn-save');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="ri-loader-4-line" style="animation:spin 1s linear infinite;"></i> Guardando...';

        // Recurso nuevo: requiere archivo — usar endpoint dedicado de subida (multipart)
        if (tipo === 'resource' && !cmid) {
            if (!recursoFile) {
                _guardando = false;
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="ri-check-line"></i> Guardar en Moodle';
                mostrarToast('error', 'Debe seleccionar un archivo para el recurso.');
                return;
            }
            if (!payload.name) {
                _guardando = false;
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="ri-check-line"></i> Guardar en Moodle';
                mostrarToast('error', 'Debe ingresar un nombre para el recurso.');
                return;
            }
            var fdRec = new FormData();
            fdRec.append('file', recursoFile);
            fdRec.append('section', payload.section);
            fdRec.append('name', payload.name);
            fdRec.append('course_id', payload.course_id);
            fdRec.append('description', payload.description || '');
            fetch(getApiBase() + '/' + getModuloId() + '/subir-archivo', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken() },
                body: fdRec,
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                _guardando = false;
                saveBtn.disabled = false;
                if (!res.success) {
                    saveBtn.innerHTML = '<i class="ri-check-line"></i> Guardar en Moodle';
                    mostrarToast('error', res.message || 'Error al subir el recurso');
                    return;
                }
                cerrarModal();
                mostrarToast('success', res.message || 'Recurso creado correctamente');
                cargarYRenderizar();
            })
            .catch(function () {
                _guardando = false;
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="ri-check-line"></i> Guardar en Moodle';
                mostrarToast('error', 'Error de conexión al subir el recurso');
            });
            return;
        }

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

            var html = '';
            if (data.descripcion && data.descripcion.trim().length > 0) {
                html += '<div class="foro-descripcion" style="padding:0.75rem 1rem;margin-bottom:1rem;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;font-size:0.85rem;color:#334155;line-height:1.6;">' + data.descripcion + '</div>';
            }

            var posts = data.posts || [];
            if (posts.length === 0) {
                html += '<div class="disc-empty"><i class="ri-inbox-line" style="font-size:1.5rem;display:block;margin-bottom:0.5rem;opacity:0.4;"></i>No hay respuestas de estudiantes en este foro.</div>';
                body.innerHTML = html;
                return;
            }

            var grupos = {};
            posts.forEach(function (p) {
                var g = p.discussion_id || 0;
                if (!grupos[g]) grupos[g] = { name: p.discussion_name || 'Discusi\u00f3n', posts: [] };
                grupos[g].posts.push(p);
            });

            html += '<div style="font-size:0.78rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:0.75rem;"><i class="ri-chat-1-line"></i> Respuestas de estudiantes</div>';

            Object.keys(grupos).forEach(function (dg) {
                var grp = grupos[dg];
                html += '<div style="margin-bottom:1rem;border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;">';
                html += '<div style="padding:0.5rem 0.75rem;background:#f1f5f9;font-size:0.8rem;font-weight:700;color:#1e293b;border-bottom:1px solid #e2e8f0;"><i class="ri-discuss-line"></i> ' + escHtml(grp.name) + '</div>';
                grp.posts.forEach(function (p) {
                    var isFirst = p.parent === '0' || !p.parent;
                    html += '<div style="padding:0.6rem 0.75rem;border-bottom:1px solid #f1f5f9;' + (isFirst ? 'background:#fff;' : 'background:#fafafa;margin-left:1.25rem;border-left:2px solid #e2e8f0;') + '">' +
                        '<div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.25rem;">' +
                            '<span style="width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#16a34a,#15803d);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.7rem;flex-shrink:0;">' + escHtml((p.author || 'D').charAt(0).toUpperCase()) + '</span>' +
                            '<strong style="font-size:0.8rem;color:#1e293b;">' + escHtml(p.author) + '</strong>' +
                            '<span style="font-size:0.7rem;color:#94a3b8;">' + (p.created ? new Date(p.created * 1000).toLocaleString('es-BO', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' }) : '') + '</span>' +
                        '</div>' +
                        '<div style="font-size:0.82rem;color:#334155;line-height:1.5;padding-left:2rem;">' + p.message + '</div>' +
                    '</div>';
                });
                html += '</div>';
            });

            body.innerHTML = html;
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
    function _emptyCalificar(msg) {
        return '<div style="text-align:center;padding:2.5rem 1rem;">' +
            '<div style="width:52px;height:52px;border-radius:14px;background:rgba(148,163,184,.1);display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;">' +
            '<i class="ri-user-unfollow-line" style="font-size:1.5rem;color:#94a3b8;"></i></div>' +
            '<p style="font-size:.87rem;color:#64748b;margin:0;">' + escHtml(msg) + '</p></div>';
    }

    // CALIFICAR FORO
    // ============================================================

    function calificarForo(moduloId, cmid, forumId, nombre) {
        _calificarCtx = { tipo: 'foro', moduloId: moduloId, cmid: cmid, nombre: nombre };
        _abrirModalCalificar(nombre, 'foro');

        fetch(getApiBase() + '/' + moduloId + '/actividades/' + cmid + '/foro/calificaciones', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (r) { if (!r.ok) return r.json().catch(function () { throw new Error('Error ' + r.status); }).then(function (d) { throw new Error(d.message || d.error || 'Error del servidor'); }); return r.json(); })
            .then(function (data) {
                document.getElementById('calificarLoading').classList.add('d-none');
                if (!data.success) throw new Error(data.message);
                var container = document.getElementById('calificarTableBody');
                if (!data.students || data.students.length === 0) {
                    container.innerHTML = _emptyCalificar('No hay estudiantes matriculados en este módulo.');
                    document.getElementById('calificarContent').classList.remove('d-none');
                    return;
                }
                var foroGradeMax = data.grade_max || 100;
                container.innerHTML = data.students.map(function (s) {
                    var postCount = s.post_count || 0;
                    var postBg = postCount > 0 ? 'rgba(22,163,74,.1)' : 'rgba(148,163,184,.1)';
                    var postColor = postCount > 0 ? '#15803d' : '#94a3b8';
                    var postsHtml = '<span style="display:inline-flex;align-items:center;gap:.35rem;padding:.22rem .7rem;border-radius:20px;background:' + postBg + ';color:' + postColor + ';font-size:.78rem;font-weight:600;">' +
                        '<i class="ri-chat-1-line"></i> ' + postCount + ' post' + (postCount !== 1 ? 's' : '') + '</span>';

                    var gradeVal = s.grade !== null && s.grade !== undefined ? s.grade : '';
                    var gradeMax = foroGradeMax;

                    return '<div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:12px;padding:.85rem 1rem;margin-bottom:.6rem;transition:box-shadow .15s;" onmouseover="this.style.boxShadow=\'0 2px 12px rgba(0,0,0,.07)\'" onmouseout="this.style.boxShadow=\'none\'">' +
                        '<div style="display:flex;align-items:center;gap:.85rem;flex-wrap:wrap;">' +
                        '<div style="display:flex;align-items:center;gap:.6rem;flex:1;min-width:180px;">' +
                        '<span style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#16a34a,#15803d);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.85rem;flex-shrink:0;">' + escHtml((s.name || 'E').charAt(0).toUpperCase()) + '</span>' +
                        '<div><div style="font-size:.87rem;font-weight:700;color:#1e293b;">' + escHtml(s.name) + '</div>' +
                        '<div style="font-size:.72rem;color:#94a3b8;">CI: ' + escHtml(s.carnet) + '</div></div></div>' +
                        '<div style="flex:1;min-width:120px;">' + postsHtml + '</div>' +
                        '<div style="display:flex;flex-direction:column;gap:.25rem;min-width:130px;">' +
                        '<label style="font-size:.72rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">Nota</label>' +
                        '<div style="display:flex;align-items:center;gap:.4rem;">' +
                        '<input type="number" class="form-control grade-input" data-user="' + s.userid + '" data-grade-max="' + gradeMax + '" value="' + gradeVal + '" min="0" max="' + gradeMax + '" step="0.5" style="width:80px;font-size:.85rem;border-radius:7px;border:1.5px solid #e2e8f0;padding:.35rem .5rem;text-align:center;" oninput="ActividadesEditor.validarNota(this)">' +
                        '<span style="font-size:.75rem;color:#94a3b8;">/ ' + gradeMax + '</span></div>' +
                        '<div id="err-' + s.userid + '" style="font-size:.7rem;color:#dc2626;display:none;margin-top:2px;"></div></div>' +
                        '<div style="display:flex;align-items:flex-end;">' +
                        '<button onclick="ActividadesEditor.guardarCalificacionForo(' + moduloId + ', ' + cmid + ', ' + s.userid + ')" ' +
                        'style="padding:.4rem .95rem;border-radius:8px;font-size:.8rem;font-weight:700;background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;border:none;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:.35rem;transition:opacity .15s;" onmouseover="this.style.opacity=\'.85\'" onmouseout="this.style.opacity=\'1\'">' +
                        '<i class="ri-save-line"></i> Guardar</button></div>' +
                        '</div></div>';
                }).join('');
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
        if (gradeInput && !validarNota(gradeInput)) {
            mostrarToast('error', 'Corrige la nota antes de guardar.');
            return;
        }
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

    var _calificarCtx = null; // { tipo, moduloId, cmid, nombre }

    function _abrirModalCalificar(nombre, tipo) {
        document.getElementById('calificarTareaNombre').textContent = nombre;
        var tipoLabel = document.getElementById('calificarTipoLabel');
        var tipoIcono = document.getElementById('calificarTipoIcono');
        if (tipoLabel) tipoLabel.textContent = tipo === 'foro' ? 'Calificar foro' : 'Calificar tarea';
        if (tipoIcono) {
            var icon = tipo === 'foro' ? 'ri-discuss-line' : 'ri-task-line';
            var color = tipo === 'foro' ? '#16a34a' : '#fc7b04';
            tipoIcono.style.background = tipo === 'foro' ? 'rgba(22,163,74,.18)' : 'rgba(252,123,4,.18)';
            tipoIcono.innerHTML = '<i class="' + icon + '" style="color:' + color + ';font-size:1.05rem;"></i>';
        }
        document.getElementById('calificarLoading').classList.remove('d-none');
        document.getElementById('calificarContent').classList.add('d-none');
        document.getElementById('calificarError').classList.add('d-none');
        document.getElementById('modalCalificarTarea').style.display = 'flex';
    }

    function _renderStudentCardTarea(s, moduloId, cmid, gradeMax) {
        gradeMax = gradeMax || 100;
        var hasSubmission = s.has_submission;
        var statusBadge = '';
        var filesHtml = '';

        if (hasSubmission) {
            var isSubmitted = s.status === 'submitted';
            var badgeBg = isSubmitted ? 'rgba(22,163,74,.12)' : 'rgba(234,179,8,.12)';
            var badgeColor = isSubmitted ? '#15803d' : '#92400e';
            var badgeIcon = isSubmitted ? 'ri-checkbox-circle-line' : 'ri-edit-line';
            var badgeLabel = isSubmitted ? 'Entregado' : 'Borrador';
            var lateHtml = s.late
                ? '<span style="margin-left:.4rem;font-size:.68rem;font-weight:700;color:#dc2626;background:rgba(220,38,38,.1);padding:1px 6px;border-radius:8px;"><i class="ri-time-warning-line"></i> Tardía</span>'
                : '';
            var timeHtml = s.timemodified
                ? '<div style="font-size:.7rem;color:#94a3b8;margin-top:2px;">' + fmtTsAdmin(s.timemodified) + '</div>'
                : '';
            statusBadge = '<div style="display:inline-flex;align-items:center;gap:.3rem;padding:.22rem .65rem;border-radius:20px;background:' + badgeBg + ';color:' + badgeColor + ';font-size:.78rem;font-weight:600;">' +
                '<i class="' + badgeIcon + '"></i> ' + badgeLabel + lateHtml + '</div>' + timeHtml;

            if (s.files && s.files.length > 0) {
                filesHtml = '<div style="margin-top:.5rem;display:flex;flex-direction:column;gap:.25rem;">';
                s.files.forEach(function (f) {
                    var dlUrl = getApiBase() + '/' + moduloId + '/actividades/' + cmid + '/archivo/' + s.userid + '/' + encodeURIComponent(f.filename);
                    filesHtml += '<a href="' + dlUrl + '" download style="display:inline-flex;align-items:center;gap:.3rem;font-size:.75rem;color:#3b82f6;text-decoration:none;padding:.2rem .55rem;background:rgba(59,130,246,.07);border-radius:6px;width:fit-content;">' +
                        '<i class="ri-download-2-line"></i>' + escHtml(f.filename) + '</a>';
                });
                filesHtml += '</div>';
            }
        } else {
            statusBadge = '<span style="display:inline-flex;align-items:center;gap:.3rem;padding:.22rem .65rem;border-radius:20px;background:rgba(148,163,184,.1);color:#94a3b8;font-size:.78rem;font-weight:600;"><i class="ri-close-circle-line"></i> Sin entregar</span>';
        }

        var gradeVal = s.grade !== null && s.grade !== undefined ? s.grade : '';
        var feedbackVal = s.feedback || '';

        return '<div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:12px;padding:.85rem 1rem;margin-bottom:.6rem;transition:box-shadow .15s;" onmouseover="this.style.boxShadow=\'0 2px 12px rgba(0,0,0,.07)\'" onmouseout="this.style.boxShadow=\'none\'">' +
            '<div style="display:flex;align-items:flex-start;gap:.85rem;flex-wrap:wrap;">' +
            // Avatar + nombre
            '<div style="display:flex;align-items:center;gap:.6rem;flex:1;min-width:180px;">' +
            '<span style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#fc7b04,#c96004);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.85rem;flex-shrink:0;">' + escHtml((s.name || 'E').charAt(0).toUpperCase()) + '</span>' +
            '<div><div style="font-size:.87rem;font-weight:700;color:#1e293b;">' + escHtml(s.name) + '</div>' +
            '<div style="font-size:.72rem;color:#94a3b8;">CI: ' + escHtml(s.carnet) + '</div></div>' +
            '</div>' +
            // Estado
            '<div style="flex:1;min-width:150px;">' + statusBadge + filesHtml + '</div>' +
            // Nota
            '<div style="display:flex;flex-direction:column;gap:.25rem;min-width:130px;">' +
            '<label style="font-size:.72rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">Nota</label>' +
            '<div style="display:flex;align-items:center;gap:.4rem;">' +
            '<input type="number" class="form-control grade-input" data-user="' + s.userid + '" data-grade-max="' + gradeMax + '" value="' + gradeVal + '" min="0" max="' + gradeMax + '" step="0.5" style="width:80px;font-size:.85rem;border-radius:7px;border:1.5px solid #e2e8f0;padding:.35rem .5rem;text-align:center;" oninput="ActividadesEditor.validarNota(this)">' +
            '<span style="font-size:.75rem;color:#94a3b8;">/ ' + gradeMax + '</span></div>' +
            '<div id="err-' + s.userid + '" style="font-size:.7rem;color:#dc2626;display:none;margin-top:2px;"></div></div>' +
            // Feedback
            '<div style="flex:2;min-width:200px;">' +
            '<label style="font-size:.72rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:.2rem;">Retroalimentación</label>' +
            '<textarea class="form-control feedback-input" data-user="' + s.userid + '" rows="2" placeholder="Comentario al estudiante..." style="font-size:.82rem;border-radius:7px;border:1.5px solid #e2e8f0;padding:.35rem .55rem;resize:none;">' + escHtml(feedbackVal) + '</textarea></div>' +
            // Botón guardar
            '<div style="display:flex;align-items:flex-end;">' +
            '<button onclick="ActividadesEditor.guardarCalificacion(' + moduloId + ', ' + cmid + ', ' + s.userid + ')" ' +
            'style="padding:.4rem .95rem;border-radius:8px;font-size:.8rem;font-weight:700;background:linear-gradient(135deg,#fc7b04,#c96004);color:#fff;border:none;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:.35rem;transition:opacity .15s;" onmouseover="this.style.opacity=\'.85\'" onmouseout="this.style.opacity=\'1\'">' +
            '<i class="ri-save-line"></i> Guardar</button></div>' +
            '</div></div>';
    }

    function calificarTarea(moduloId, cmid, nombre) {
        _calificarCtx = { tipo: 'tarea', moduloId: moduloId, cmid: cmid, nombre: nombre };
        _abrirModalCalificar(nombre, 'tarea');

        fetch(getApiBase() + '/' + moduloId + '/actividades/' + cmid + '/entregas', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (r) { if (!r.ok) return r.json().catch(function () { throw new Error('Error ' + r.status); }).then(function (d) { throw new Error(d.message || d.error || 'Error del servidor'); }); return r.json(); })
            .then(function (data) {
                document.getElementById('calificarLoading').classList.add('d-none');
                if (!data.success) throw new Error(data.message);
                var container = document.getElementById('calificarTableBody');
                if (!data.students || data.students.length === 0) {
                    container.innerHTML = _emptyCalificar('Sin estudiantes matriculados en este módulo.');
                    document.getElementById('calificarContent').classList.remove('d-none');
                    return;
                }
                var gMax = data.grade_max || 100;
                container.innerHTML = data.students.map(function (s) { return _renderStudentCardTarea(s, moduloId, cmid, gMax); }).join('');
                document.getElementById('calificarContent').classList.remove('d-none');
            })
            .catch(function (err) {
                document.getElementById('calificarLoading').classList.add('d-none');
                document.getElementById('calificarErrorMsg').textContent = err.message || 'Error al cargar entregas.';
                document.getElementById('calificarError').classList.remove('d-none');
            });
    }

    function validarNota(input) {
        var val = parseFloat(input.value);
        var max = parseFloat(input.getAttribute('data-grade-max') || input.max || 100);
        var userId = input.getAttribute('data-user');
        var errEl = userId ? document.getElementById('err-' + userId) : null;
        var esInvalido = false;
        var msg = '';
        if (input.value !== '' && !isNaN(val)) {
            if (val < 0) { esInvalido = true; msg = 'La nota no puede ser negativa.'; }
            else if (val > max) { esInvalido = true; msg = 'La nota no puede superar ' + max + '.'; }
        }
        input.style.borderColor = esInvalido ? '#dc2626' : '';
        if (errEl) { errEl.textContent = msg; errEl.style.display = esInvalido ? 'block' : 'none'; }
        return !esInvalido;
    }

    function guardarCalificacion(moduloId, cmid, userId) {
        var gradeInput = document.querySelector('.grade-input[data-user="' + userId + '"]');
        var feedbackInput = document.querySelector('.feedback-input[data-user="' + userId + '"]');
        if (gradeInput && !validarNota(gradeInput)) {
            mostrarToast('error', 'Corrige la nota antes de guardar.');
            return;
        }
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
        _calificarCtx = null;
    }

    function reintentarCalificar() {
        if (!_calificarCtx) return;
        if (_calificarCtx.tipo === 'foro') {
            calificarForo(_calificarCtx.moduloId, _calificarCtx.cmid, null, _calificarCtx.nombre);
        } else {
            calificarTarea(_calificarCtx.moduloId, _calificarCtx.cmid, _calificarCtx.nombre);
        }
    }

    // ============================================================
    // RESULTADOS QUIZ
    // ============================================================

    function verResultadosQuiz(moduloId, quizId, nombre) {
        var modal = document.getElementById('modalQuizResultados');
        if (!modal) return;
        document.getElementById('quizResultadosNombre').textContent = nombre;
        document.getElementById('quizLoading').style.display = 'block';
        document.getElementById('quizContent').style.display = 'none';
        document.getElementById('quizAttemptDetail').style.display = 'none';
        document.getElementById('quizError').style.display = 'none';
        modal.style.display = 'flex';

        fetch(getApiBase() + '/' + moduloId + '/actividades/quiz/' + quizId + '/resultados')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                document.getElementById('quizLoading').style.display = 'none';
                if (!data.success) throw new Error(data.message);
                var container = document.getElementById('quizCardsContainer');
                container.innerHTML = '';
                if (!data.attempts || data.attempts.length === 0) {
                    container.innerHTML = '<div style="text-align:center;padding:2rem;color:#94a3b8;"><i class="ri-inbox-line" style="font-size:1.5rem;display:block;margin-bottom:0.5rem;opacity:0.4;"></i>Sin intentos aun.</div>';
                    document.getElementById('quizContent').style.display = 'block';
                    return;
                }
                data.attempts.forEach(function (a) {
                    var stateText = a.state === 'finished' ? 'Completado'
                        : a.state === 'inprogress' ? 'En progreso'
                        : a.state === 'overdue' ? 'Vencido'
                        : a.state;
                    var stateIcon = a.state === 'finished' ? 'ri-checkbox-circle-line'
                        : a.state === 'inprogress' ? 'ri-timer-flash-line'
                        : a.state === 'overdue' ? 'ri-time-warning-line'
                        : 'ri-question-line';
                    var stateColor = a.state === 'finished' ? '#15803d'
                        : a.state === 'inprogress' ? '#d97706'
                        : a.state === 'overdue' ? '#dc2626'
                        : '#64748b';
                    var stateBg = a.state === 'finished' ? 'rgba(22,163,74,.12)'
                        : a.state === 'inprogress' ? 'rgba(217,119,6,.12)'
                        : a.state === 'overdue' ? 'rgba(220,38,38,.12)'
                        : 'rgba(100,116,139,.1)';
                    var score = a.grade !== null ? a.grade : '-';
                    var maxScore = a.grade_max || 100;
                    var pct = a.grade !== null ? Math.round((a.grade / maxScore) * 100) : 0;
                    var barColor = pct >= 80 ? '#16a34a' : pct >= 50 ? '#d97706' : '#dc2626';
                    var barBg = pct >= 80 ? 'rgba(22,163,74,.15)' : pct >= 50 ? 'rgba(217,119,6,.15)' : 'rgba(220,38,38,.15)';
                    var initial = (a.user_name || 'U').charAt(0).toUpperCase();
                    container.innerHTML +=
                        '<div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:12px;padding:0.85rem 1rem;margin-bottom:0.6rem;transition:box-shadow .15s;" onmouseover="this.style.boxShadow=\'0 2px 12px rgba(0,0,0,.07)\'" onmouseout="this.style.boxShadow=\'none\'">' +
                            '<div style="display:flex;align-items:center;gap:0.85rem;flex-wrap:wrap;">' +
                                '<div style="display:flex;align-items:center;gap:0.6rem;flex:1;min-width:160px;">' +
                                    '<span style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#d97706,#b45309);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;flex-shrink:0;">' + escHtml(initial) + '</span>' +
                                    '<div><div style="font-size:0.87rem;font-weight:700;color:#1e293b;">' + escHtml(a.user_name || 'Usuario #' + a.userid) + '</div></div>' +
                                '</div>' +
                                '<div style="flex:1;min-width:120px;">' +
                                    '<span style="display:inline-flex;align-items:center;gap:0.3rem;padding:0.22rem 0.65rem;border-radius:20px;background:' + stateBg + ';color:' + stateColor + ';font-size:0.78rem;font-weight:600;"><i class="' + stateIcon + '"></i> ' + stateText + '</span>' +
                                    '<span style="display:inline-flex;align-items:center;gap:0.3rem;margin-left:0.4rem;padding:0.22rem 0.65rem;border-radius:20px;background:#eff6ff;color:#1d4ed8;font-size:0.78rem;font-weight:600;"><i class="ri-stack-line"></i> Intento ' + a.attempt + '</span>' +
                                '</div>' +
                                '<div style="min-width:180px;flex:1;">' +
                                    '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.2rem;">' +
                                        '<span style="font-size:0.75rem;font-weight:600;color:#64748b;">Calificaci\u00f3n</span>' +
                                        '<span style="font-size:0.82rem;font-weight:700;color:' + barColor + ';">' + score + ' / ' + maxScore + '</span>' +
                                    '</div>' +
                                    '<div style="height:6px;background:' + barBg + ';border-radius:3px;overflow:hidden;">' +
                                        '<div style="height:100%;width:' + pct + '%;background:' + barColor + ';border-radius:3px;transition:width .3s;"></div>' +
                                    '</div>' +
                                '</div>' +
                                '<div style="display:flex;align-items:center;">' +
                                    '<button onclick="ActividadesEditor.verDetalleIntento(' + moduloId + ', ' + quizId + ', ' + a.id + ', \'' + escHtml(a.user_name || '') + '\')" style="padding:0.4rem 0.9rem;border-radius:8px;font-size:0.78rem;font-weight:700;background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;border:none;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:0.3rem;transition:opacity .15s;" onmouseover="this.style.opacity=\'.85\'" onmouseout="this.style.opacity=\'1\'"><i class="ri-eye-line"></i> Detalle</button>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
                });
                document.getElementById('quizContent').style.display = 'block';
            })
            .catch(function (err) {
                document.getElementById('quizLoading').style.display = 'none';
                document.getElementById('quizErrorText').textContent = err.message || 'Error al cargar resultados.';
                document.getElementById('quizError').style.display = 'block';
            });
    }

    function verDetalleIntento(moduloId, quizId, attemptId, userName) {
        var container = document.getElementById('quizQuestionsContainer');
        document.getElementById('quizDetailNombre').textContent = userName ? 'Intento de ' + userName : 'Detalle del intento';
        container.innerHTML = '<div style="text-align:center;padding:1.5rem;color:#64748b;"><i class="ri-loader-4-line" style="font-size:1.2rem;animation:spin 1s linear infinite;"></i><p style="margin-top:.3rem;font-size:0.8rem;">Cargando detalle...</p></div>';
        document.getElementById('quizContent').style.display = 'none';
        document.getElementById('quizAttemptDetail').style.display = 'block';

        fetch(getApiBase() + '/' + moduloId + '/actividades/quiz/' + quizId + '/resultados/' + attemptId)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.success) throw new Error(data.message);
                container.innerHTML = '';
                if (!data.questions || data.questions.length === 0) {
                    container.innerHTML = '<div style="text-align:center;padding:1.5rem;color:#94a3b8;">No hay preguntas disponibles.</div>';
                    return;
                }
                var qIdx = 0;
                data.questions.forEach(function (q) {
                    qIdx++;
                    var pct = q.fraction !== null && q.fraction !== undefined ? Math.round(q.fraction * 100) : 0;
                    var isCorrect = q.fraction >= 1;
                    var isPartial = q.fraction > 0 && q.fraction < 1;
                    var isWrong = q.fraction <= 0;
                    var icon = isCorrect ? 'ri-checkbox-circle-line' : isPartial ? 'ri-indeterminate-circle-line' : 'ri-close-circle-line';
                    var iconColor = isCorrect ? '#16a34a' : isPartial ? '#d97706' : '#dc2626';
                    var borderColor = isCorrect ? '#bbf7d0' : isPartial ? '#fde68a' : '#fecaca';
                    var bgColor = isCorrect ? 'rgba(22,163,74,.05)' : isPartial ? 'rgba(217,119,6,.05)' : 'rgba(220,38,38,.05)';
                    container.innerHTML +=
                        '<div style="background:#fff;border:1.5px solid ' + borderColor + ';border-radius:10px;margin-bottom:0.6rem;overflow:hidden;">' +
                            '<div style="display:flex;align-items:flex-start;gap:0.6rem;padding:0.7rem 0.85rem;background:' + bgColor + ';">' +
                                '<i class="' + icon + '" style="font-size:1.1rem;color:' + iconColor + ';margin-top:0.1rem;flex-shrink:0;"></i>' +
                                '<div style="flex:1;min-width:0;">' +
                                    '<div style="display:flex;align-items:center;justify-content:space-between;gap:0.5rem;margin-bottom:0.25rem;">' +
                                        '<span style="font-size:0.72rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.03em;">Pregunta ' + (q.questionnumber || qIdx) + '</span>' +
                                        '<span style="font-size:0.78rem;font-weight:700;color:' + iconColor + ';">' + pct + '%</span>' +
                                    '</div>' +
                                    '<div style="font-size:0.85rem;color:#1e293b;line-height:1.5;margin-bottom:0.4rem;">' + (q.questiontext || '') + '</div>' +
                                    '<div style="display:flex;flex-wrap:wrap;gap:0.4rem;">' +
                                        '<div style="flex:1;min-width:140px;padding:0.35rem 0.55rem;background:#f8fafc;border-radius:6px;font-size:0.78rem;">' +
                                            '<span style="font-weight:600;color:#475569;">Respuesta:</span> ' + (q.response || '<span style="color:#94a3b8;">(sin respuesta)</span>') +
                                        '</div>' +
                                        (q.rightanswer ? '<div style="flex:1;min-width:140px;padding:0.35rem 0.55rem;background:rgba(22,163,74,.06);border-radius:6px;font-size:0.78rem;">' +
                                            '<span style="font-weight:600;color:#16a34a;">Correcta:</span> ' + q.rightanswer +
                                        '</div>' : '') +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
                });
            })
            .catch(function (err) {
                container.innerHTML = '<div style="padding:0.75rem 1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;color:#dc2626;font-size:0.85rem;">' + (err.message || 'Error al cargar detalle.') + '</div>';
            });
    }

    function cerrarDetalleIntento() {
        document.getElementById('quizAttemptDetail').style.display = 'none';
        document.getElementById('quizContent').style.display = 'block';
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
            container.innerHTML =
                '<div style="text-align:center;padding:2rem 1rem;">' +
                '<div style="width:52px;height:52px;border-radius:14px;background:rgba(217,119,6,.1);display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;">' +
                '<i class="ri-question-line" style="font-size:1.5rem;color:#d97706;"></i></div>' +
                '<p style="font-size:.87rem;font-weight:600;color:#475569;margin:0 0 .25rem;">Sin preguntas aún</p>' +
                '<p style="font-size:.78rem;color:#94a3b8;margin:0;">Usa los botones de arriba para agregar el primer tipo de pregunta.</p>' +
                '</div>';
            return;
        }

        var typeCfg = {
            multichoice: { label: 'Opción múltiple', icon: 'ri-list-check',   bg: 'rgba(99,102,241,.1)',  color: '#4f46e5' },
            truefalse:   { label: 'Verd. / Falso',   icon: 'ri-toggle-line',  bg: 'rgba(22,163,74,.1)',   color: '#15803d' },
            match:       { label: 'Coincidencia',     icon: 'ri-links-line',   bg: 'rgba(2,132,199,.1)',   color: '#0369a1' },
        };

        // Contador total de puntos
        var totalPts = questions.reduce(function(s, q) { return s + (parseFloat(q.defaultmark) || 0); }, 0);

        var header =
            '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.65rem;padding:.4rem .6rem;background:#f1f5f9;border-radius:8px;">' +
            '<span style="font-size:.78rem;font-weight:700;color:#475569;">' +
            '<i class="ri-list-unordered" style="color:#fc7b04;margin-right:.25rem;"></i>' + questions.length + ' pregunta' + (questions.length !== 1 ? 's' : '') + '</span>' +
            '<span style="font-size:.75rem;font-weight:600;color:#d97706;background:rgba(217,119,6,.1);padding:2px 9px;border-radius:10px;">' + totalPts.toFixed(2) + ' pts total</span>' +
            '</div>';

        var rows = questions.map(function(q, idx) {
            var cfg = typeCfg[q.qtype] || { label: q.qtype, icon: 'ri-question-line', bg: 'rgba(156,163,175,.12)', color: '#64748b' };
            return '<div style="display:flex;align-items:center;gap:.65rem;padding:.6rem .75rem;background:#fff;border:1.5px solid #e2e8f0;border-radius:10px;margin-bottom:.4rem;transition:box-shadow .15s;" ' +
                'onmouseover="this.style.boxShadow=\'0 2px 10px rgba(0,0,0,.07)\'" onmouseout="this.style.boxShadow=\'none\'">' +
                '<span style="width:26px;height:26px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;color:#94a3b8;flex-shrink:0;">' + (idx + 1) + '</span>' +
                '<span style="width:28px;height:28px;border-radius:7px;background:' + cfg.bg + ';display:flex;align-items:center;justify-content:center;flex-shrink:0;">' +
                '<i class="' + cfg.icon + '" style="color:' + cfg.color + ';font-size:.85rem;"></i></span>' +
                '<div style="flex:1;min-width:0;">' +
                '<div style="font-size:.84rem;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + escHtml(q.name) + '</div>' +
                '<div style="display:flex;align-items:center;gap:.5rem;margin-top:2px;">' +
                '<span style="font-size:.7rem;font-weight:600;padding:1px 7px;border-radius:10px;background:' + cfg.bg + ';color:' + cfg.color + ';">' + cfg.label + '</span>' +
                '<span style="font-size:.7rem;color:#94a3b8;"><i class="ri-award-line" style="color:#d97706;"></i> ' + q.defaultmark + ' pts</span>' +
                '</div></div>' +
                '<div style="display:flex;gap:.35rem;flex-shrink:0;">' +
                '<button onclick="ActividadesEditor.editarPregunta(' + q.question_id + ', \'' + q.qtype + '\', ' + getModuloId() + ')" ' +
                'style="width:30px;height:30px;border-radius:7px;background:rgba(99,102,241,.08);border:none;color:#4f46e5;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.85rem;transition:all .15s;" ' +
                'title="Editar pregunta" onmouseover="this.style.background=\'rgba(99,102,241,.2)\'" onmouseout="this.style.background=\'rgba(99,102,241,.08)\'">' +
                '<i class="ri-pencil-line"></i></button>' +
                '<button onclick="ActividadesEditor.eliminarPregunta(' + currentQuizId + ', ' + q.slot_id + ')" ' +
                'style="width:30px;height:30px;border-radius:7px;background:rgba(239,68,68,.08);border:none;color:#dc2626;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.85rem;transition:all .15s;" ' +
                'title="Eliminar pregunta" onmouseover="this.style.background=\'rgba(239,68,68,.2)\'" onmouseout="this.style.background=\'rgba(239,68,68,.08)\'">' +
                '<i class="ri-delete-bin-line"></i></button>' +
                '</div>' +
                '</div>';
        }).join('');

        container.innerHTML = header + rows;
    }

    function editarPregunta(questionId, qtype, moduloId) {
        // Mostrar spinner en la lista mientras carga
        var listContainer = document.getElementById('preguntasList');
        if (listContainer) {
            listContainer.innerHTML =
                '<div style="text-align:center;padding:2rem;">' +
                '<i class="ri-loader-4-line" style="font-size:1.8rem;color:#d97706;animation:spin 1s linear infinite;display:block;margin-bottom:.5rem;"></i>' +
                '<p style="font-size:.84rem;color:#64748b;margin:0;">Cargando datos de la pregunta...</p></div>';
        }

        fetch(getApiBase() + '/' + moduloId + '/actividades/quiz/' + currentQuizId + '/preguntas/' + questionId)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.success) throw new Error(data.message);
                var q = data.question;
                _editingQuestion = { questionId: questionId, qtype: qtype, moduloId: moduloId, quizId: currentQuizId };

                if (qtype === 'multichoice') {
                    mostrarFormMC(q);
                } else if (qtype === 'truefalse') {
                    mostrarFormTF(q);
                } else if (qtype === 'match') {
                    mostrarFormMatch(q);
                } else {
                    mostrarToast('error', 'Tipo de pregunta no soportado para edición.');
                    _editingQuestion = null;
                    verPreguntasQuiz(moduloId, currentQuizId, document.getElementById('preguntasQuizNombre').textContent);
                }
            })
            .catch(function (err) {
                mostrarToast('error', err.message || 'Error al cargar la pregunta.');
                _editingQuestion = null;
                verPreguntasQuiz(moduloId, currentQuizId, document.getElementById('preguntasQuizNombre').textContent);
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

    // ── Estado edición de preguntas ──
    var _editingQuestion = null; // { questionId, qtype, moduloId, quizId } | null

    // ── Helpers de filas reutilizables ──

    function _mcDelBtn() {
        return '<button onclick="this.closest(\'.mc-option\').remove()" style="width:28px;height:28px;border-radius:6px;background:rgba(239,68,68,.1);border:none;color:#dc2626;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.8rem;"><i class="ri-delete-bin-line"></i></button></div>';
    }
    function _matchDelBtn() {
        return '<button onclick="this.closest(\'.match-pair\').remove()" style="width:28px;height:28px;border-radius:6px;background:rgba(239,68,68,.1);border:none;color:#dc2626;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.8rem;"><i class="ri-delete-bin-line"></i></button></div>';
    }

    function makeMcOptionRow(fracVal) {
        return '<div class="mc-option" style="display:grid;grid-template-columns:1fr 76px 32px;gap:.4rem;margin-bottom:.35rem;align-items:center;">' +
            '<input class="form-control" placeholder="Texto" style="font-size:.83rem;border-radius:7px;border:1.5px solid #e2e8f0;padding:.35rem .55rem;">' +
            '<input class="form-control" type="number" step="0.01" value="' + (fracVal || '0') + '" style="font-size:.83rem;border-radius:7px;border:1.5px solid #e2e8f0;padding:.35rem .4rem;text-align:center;">' +
            _mcDelBtn();
    }

    function makeMcOptionRowWithText(text, fracVal) {
        return '<div class="mc-option" style="display:grid;grid-template-columns:1fr 76px 32px;gap:.4rem;margin-bottom:.35rem;align-items:center;">' +
            '<input class="form-control" placeholder="Texto" value="' + escHtml(text || '') + '" style="font-size:.83rem;border-radius:7px;border:1.5px solid #e2e8f0;padding:.35rem .55rem;">' +
            '<input class="form-control" type="number" step="0.01" value="' + (fracVal != null ? fracVal : '0') + '" style="font-size:.83rem;border-radius:7px;border:1.5px solid #e2e8f0;padding:.35rem .4rem;text-align:center;">' +
            _mcDelBtn();
    }

    function makeMatchPairRow() {
        return '<div class="match-pair" style="display:grid;grid-template-columns:1fr 1fr 32px;gap:.4rem;margin-bottom:.35rem;align-items:center;">' +
            '<input class="form-control" placeholder="Pregunta" style="font-size:.83rem;border-radius:7px;border:1.5px solid #e2e8f0;padding:.35rem .55rem;">' +
            '<input class="form-control" placeholder="Respuesta" style="font-size:.83rem;border-radius:7px;border:1.5px solid #e2e8f0;padding:.35rem .55rem;">' +
            _matchDelBtn();
    }

    function makeMatchPairRowWithValues(pregunta, respuesta) {
        return '<div class="match-pair" style="display:grid;grid-template-columns:1fr 1fr 32px;gap:.4rem;margin-bottom:.35rem;align-items:center;">' +
            '<input class="form-control" placeholder="Pregunta" value="' + escHtml(pregunta || '') + '" style="font-size:.83rem;border-radius:7px;border:1.5px solid #e2e8f0;padding:.35rem .55rem;">' +
            '<input class="form-control" placeholder="Respuesta" value="' + escHtml(respuesta || '') + '" style="font-size:.83rem;border-radius:7px;border:1.5px solid #e2e8f0;padding:.35rem .55rem;">' +
            _matchDelBtn();
    }

    // ── Crear pregunta opción múltiple ──

    // ── Helpers de modo edición ──
    function setModalEditMode(modalId, saveBtnOnclick, isEditing) {
        var badge = document.getElementById(modalId + '_modeBadge');
        var saveBtn = document.querySelector('#' + modalId + ' .btn-guardar-modal');
        if (badge) {
            badge.style.display = isEditing ? 'inline-flex' : 'none';
        }
        if (saveBtn) {
            saveBtn.innerHTML = isEditing
                ? '<i class="ri-save-line"></i> Guardar cambios'
                : '<i class="ri-check-line"></i> Crear pregunta';
        }
    }

    // ── Opción múltiple ──

    function mostrarFormMC(editData) {
        var editing = !!editData;
        document.getElementById('mcName').value        = editing ? editData.name : '';
        document.getElementById('mcQuestionText').value = editing ? editData.questiontext : '';
        document.getElementById('mcDefaultMark').value  = editing ? editData.defaultmark : '1';
        document.getElementById('mcSingle').value        = editing ? (editData.single || 'true') : 'true';

        var container = document.getElementById('mcOptionsContainer');
        if (editing && editData.options && editData.options.length > 0) {
            container.innerHTML = editData.options.map(function(opt) {
                return makeMcOptionRowWithText(opt.text, opt.fraction);
            }).join('');
        } else {
            container.innerHTML = makeMcOptionRow('1') + makeMcOptionRow('0') + makeMcOptionRow('0');
        }

        // Indicador modo edición en el header
        var hdr = document.querySelector('#modalMC .disc-modal-hdr span');
        if (hdr) hdr.innerHTML = editing
            ? '<i class="ri-list-check" style="font-size:1.1rem;opacity:.85;"></i> Editar pregunta &mdash; Opción múltiple'
            : '<i class="ri-list-check" style="font-size:1.1rem;opacity:.85;"></i> Nueva pregunta &mdash; Opción múltiple';
        var saveBtn = document.querySelector('#modalMC .disc-modal-footer button:last-child');
        if (saveBtn) saveBtn.innerHTML = editing ? '<i class="ri-save-line"></i> Guardar cambios' : '<i class="ri-check-line"></i> Crear pregunta';

        document.getElementById('modalMC').classList.add('open');
    }

    function cerrarModalMC() {
        _editingQuestion = null;
        document.getElementById('modalMC').classList.remove('open');
        // Restaurar título
        var hdr = document.querySelector('#modalMC .disc-modal-hdr span');
        if (hdr) hdr.innerHTML = '<i class="ri-list-check" style="font-size:1.1rem;opacity:.85;"></i> Nueva pregunta &mdash; Opción múltiple';
        var saveBtn = document.querySelector('#modalMC .disc-modal-footer button:last-child');
        if (saveBtn) saveBtn.innerHTML = '<i class="ri-check-line"></i> Crear pregunta';
    }

    function guardarMC() {
        var name = document.getElementById('mcName').value.trim();
        var qtext = document.getElementById('mcQuestionText').value.trim();
        if (!name || !qtext) { mostrarToast('error', 'Nombre y texto de pregunta requeridos.'); return; }

        var options = [];
        document.querySelectorAll('#mcOptionsContainer .mc-option').forEach(function (div) {
            var text = div.querySelector('input[placeholder="Texto"]')?.value || '';
            var frac = parseFloat(div.querySelector('input[type="number"]')?.value || '0');
            if (text) options.push({ text: text, fraction: frac, feedback: '' });
        });
        if (options.length < 2) { mostrarToast('error', 'Debe tener al menos 2 opciones.'); return; }

        var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        var defaultMark = parseFloat(document.getElementById('mcDefaultMark').value) || 1;
        var single = document.getElementById('mcSingle').value;
        var editing = !!_editingQuestion;

        var url = editing
            ? getApiBase() + '/' + _editingQuestion.moduloId + '/actividades/quiz/' + _editingQuestion.quizId + '/preguntas/' + _editingQuestion.questionId + '/multichoice'
            : getApiBase() + '/' + getModuloId() + '/actividades/quiz/' + currentQuizId + '/preguntas/multichoice';

        fetch(url, {
            method: editing ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ name: name, questiontext: qtext, defaultmark: defaultMark, single: single, options: options }),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                cerrarModalMC();
                mostrarToast('success', editing ? 'Pregunta actualizada.' : 'Pregunta creada.');
                verPreguntasQuiz(getModuloId(), currentQuizId, document.getElementById('preguntasQuizNombre').textContent);
            } else {
                mostrarToast('error', data.message || (editing ? 'Error al actualizar.' : 'Error al crear.'));
            }
        })
        .catch(function () { mostrarToast('error', 'Error de conexión.'); });
    }

    // ── V/F ──

    function mostrarFormTF(editData) {
        var editing = !!editData;
        document.getElementById('tfName').value         = editing ? editData.name : '';
        document.getElementById('tfQuestionText').value  = editing ? editData.questiontext : '';
        document.getElementById('tfDefaultMark').value   = editing ? editData.defaultmark : '1';
        document.getElementById('tfCorrect').value        = editing ? (editData.correctanswer || 'true') : 'true';

        var hdr = document.querySelector('#modalTF .disc-modal-hdr span');
        if (hdr) hdr.innerHTML = editing
            ? '<i class="ri-toggle-line" style="font-size:1.1rem;opacity:.85;"></i> Editar pregunta &mdash; Verdadero / Falso'
            : '<i class="ri-toggle-line" style="font-size:1.1rem;opacity:.85;"></i> Nueva pregunta &mdash; Verdadero / Falso';
        var saveBtn = document.querySelector('#modalTF .disc-modal-footer button:last-child');
        if (saveBtn) saveBtn.innerHTML = editing ? '<i class="ri-save-line"></i> Guardar cambios' : '<i class="ri-check-line"></i> Crear pregunta';

        document.getElementById('modalTF').classList.add('open');
    }

    function cerrarModalTF() {
        _editingQuestion = null;
        document.getElementById('modalTF').classList.remove('open');
        var hdr = document.querySelector('#modalTF .disc-modal-hdr span');
        if (hdr) hdr.innerHTML = '<i class="ri-toggle-line" style="font-size:1.1rem;opacity:.85;"></i> Nueva pregunta &mdash; Verdadero / Falso';
        var saveBtn = document.querySelector('#modalTF .disc-modal-footer button:last-child');
        if (saveBtn) saveBtn.innerHTML = '<i class="ri-check-line"></i> Crear pregunta';
    }

    function guardarTF() {
        var name = document.getElementById('tfName').value.trim();
        var qtext = document.getElementById('tfQuestionText').value.trim();
        if (!name || !qtext) { mostrarToast('error', 'Nombre y texto requeridos.'); return; }

        var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        var defaultMark = parseFloat(document.getElementById('tfDefaultMark').value) || 1;
        var correct = document.getElementById('tfCorrect').value;
        var editing = !!_editingQuestion;

        var url = editing
            ? getApiBase() + '/' + _editingQuestion.moduloId + '/actividades/quiz/' + _editingQuestion.quizId + '/preguntas/' + _editingQuestion.questionId + '/truefalse'
            : getApiBase() + '/' + getModuloId() + '/actividades/quiz/' + currentQuizId + '/preguntas/truefalse';

        fetch(url, {
            method: editing ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ name: name, questiontext: qtext, defaultmark: defaultMark, correctanswer: correct }),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                cerrarModalTF();
                mostrarToast('success', editing ? 'Pregunta actualizada.' : 'Pregunta creada.');
                verPreguntasQuiz(getModuloId(), currentQuizId, document.getElementById('preguntasQuizNombre').textContent);
            } else {
                mostrarToast('error', data.message || (editing ? 'Error al actualizar.' : 'Error al crear.'));
            }
        })
        .catch(function () { mostrarToast('error', 'Error de conexión.'); });
    }

    // ── Coincidencia ──

    function mostrarFormMatch(editData) {
        var editing = !!editData;
        document.getElementById('matchName').value         = editing ? editData.name : '';
        document.getElementById('matchQuestionText').value  = editing ? editData.questiontext : '';
        document.getElementById('matchDefaultMark').value   = editing ? editData.defaultmark : '1';

        var container = document.getElementById('matchPairsContainer');
        if (editing && editData.pairs && editData.pairs.length > 0) {
            container.innerHTML = editData.pairs.map(function(p) {
                return makeMatchPairRowWithValues(p.question, p.answer);
            }).join('');
        } else {
            container.innerHTML = makeMatchPairRow() + makeMatchPairRow();
        }

        var hdr = document.querySelector('#modalMatch .disc-modal-hdr span');
        if (hdr) hdr.innerHTML = editing
            ? '<i class="ri-links-line" style="font-size:1.1rem;opacity:.85;"></i> Editar pregunta &mdash; Coincidencia'
            : '<i class="ri-links-line" style="font-size:1.1rem;opacity:.85;"></i> Nueva pregunta &mdash; Coincidencia';
        var saveBtn = document.querySelector('#modalMatch .disc-modal-footer button:last-child');
        if (saveBtn) saveBtn.innerHTML = editing ? '<i class="ri-save-line"></i> Guardar cambios' : '<i class="ri-check-line"></i> Crear pregunta';

        document.getElementById('modalMatch').classList.add('open');
    }

    function cerrarModalMatch() {
        _editingQuestion = null;
        document.getElementById('modalMatch').classList.remove('open');
        var hdr = document.querySelector('#modalMatch .disc-modal-hdr span');
        if (hdr) hdr.innerHTML = '<i class="ri-links-line" style="font-size:1.1rem;opacity:.85;"></i> Nueva pregunta &mdash; Coincidencia';
        var saveBtn = document.querySelector('#modalMatch .disc-modal-footer button:last-child');
        if (saveBtn) saveBtn.innerHTML = '<i class="ri-check-line"></i> Crear pregunta';
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
        var editing = !!_editingQuestion;

        var url = editing
            ? getApiBase() + '/' + _editingQuestion.moduloId + '/actividades/quiz/' + _editingQuestion.quizId + '/preguntas/' + _editingQuestion.questionId + '/matching'
            : getApiBase() + '/' + getModuloId() + '/actividades/quiz/' + currentQuizId + '/preguntas/matching';

        fetch(url, {
            method: editing ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ name: name, questiontext: qtext, defaultmark: defaultMark, pairs: pairs }),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                cerrarModalMatch();
                mostrarToast('success', editing ? 'Pregunta actualizada.' : 'Pregunta creada.');
                verPreguntasQuiz(getModuloId(), currentQuizId, document.getElementById('preguntasQuizNombre').textContent);
            } else {
                mostrarToast('error', data.message || (editing ? 'Error al actualizar.' : 'Error al crear.'));
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
        validarNota: validarNota,
        calificarTarea: calificarTarea,
        guardarCalificacion: guardarCalificacion,
        calificarForo: calificarForo,
        guardarCalificacionForo: guardarCalificacionForo,
        cerrarModalCalificar:  cerrarModalCalificar,
        reintentarCalificar:   reintentarCalificar,
        verResultadosQuiz: verResultadosQuiz,
        verDetalleIntento: verDetalleIntento,
        cerrarModalQuiz: cerrarModalQuiz,
        verPreguntasQuiz: verPreguntasQuiz,
        editarPregunta:   editarPregunta,
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
