<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Toggle tabs activo ── */
    document.querySelectorAll('.profile-tab').forEach(function (btn) {
        btn.addEventListener('shown.bs.tab', function () {
            document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
        });
    });

    /* ── Toggle visibilidad contraseña ── */
    document.querySelectorAll('.toggle-pw').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = btn.closest('.password-input-group').querySelector('input');
            const icon  = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'ri-eye-off-line';
            } else {
                input.type = 'password';
                icon.className = 'ri-eye-line';
            }
        });
    });

    /* ── Indicador de fortaleza de contraseña ── */
    const newPwInput = document.getElementById('new_password');
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');

    if (newPwInput) {
        newPwInput.addEventListener('input', function () {
            const val = this.value;
            let score = 0;
            if (val.length >= 8)           score++;
            if (/[A-Z]/.test(val))         score++;
            if (/[0-9]/.test(val))         score++;
            if (/[^A-Za-z0-9]/.test(val))  score++;

            const levels = [
                { pct: '0%',   color: '',        label: '' },
                { pct: '25%',  color: '#ef4444', label: 'Muy débil' },
                { pct: '50%',  color: '#f97316', label: 'Débil' },
                { pct: '75%',  color: '#eab308', label: 'Aceptable' },
                { pct: '100%', color: '#22c55e', label: 'Fuerte' },
            ];
            const lvl = levels[score] || levels[0];
            strengthBar.style.width    = lvl.pct;
            strengthBar.style.background = lvl.color;
            strengthText.textContent   = lvl.label;
            strengthText.style.color   = lvl.color;

            checkMatch();
        });
    }

    /* ── Verificar coincidencia de contraseñas ── */
    const confirmInput = document.getElementById('new_password_confirmation');
    const matchDiv     = document.getElementById('passwordMatch');

    function checkMatch() {
        if (!confirmInput || !newPwInput || !confirmInput.value) { matchDiv.textContent = ''; return; }
        if (confirmInput.value === newPwInput.value) {
            matchDiv.textContent = '✔ Las contraseñas coinciden';
            matchDiv.style.color = '#22c55e';
        } else {
            matchDiv.textContent = '✘ Las contraseñas no coinciden';
            matchDiv.style.color = '#ef4444';
        }
    }

    if (confirmInput) confirmInput.addEventListener('input', checkMatch);

    /* ── Cambio de contraseña via AJAX ── */
    const pwForm  = document.getElementById('changePasswordForm');
    const pwAlert = document.getElementById('pwAlert');
    const pwBtn   = document.getElementById('changePasswordBtn');

    if (pwForm) {
        pwForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (confirmInput.value !== newPwInput.value) {
                showAlert(pwAlert, 'danger', 'Las contraseñas no coinciden.');
                return;
            }

            const formData = new FormData(pwForm);
            pwBtn.disabled = true;
            pwBtn.innerHTML = '<i class="ri-loader-4-line me-1"></i>Actualizando...';

            fetch('{{ route('admin.profile.change-password') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        || '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(r => r.json())
            .then(function (data) {
                if (data.success) {
                    showAlert(pwAlert, 'success', data.message);
                    pwForm.reset();
                    strengthBar.style.width = '0%';
                    strengthText.textContent = '';
                    matchDiv.textContent = '';
                } else {
                    showAlert(pwAlert, 'danger', data.message || 'Error al actualizar la contraseña.');
                }
            })
            .catch(function () {
                showAlert(pwAlert, 'danger', 'Error de conexión. Intenta nuevamente.');
            })
            .finally(function () {
                pwBtn.disabled = false;
                pwBtn.innerHTML = '<i class="ri-shield-check-line me-1"></i>Actualizar Contraseña';
            });
        });
    }

    /* ── Subida de foto ── */
    const fotoInput  = document.getElementById('fotoInput');
    const fotoPreview= document.getElementById('fotoPreview');
    const btnSubir   = document.getElementById('btnSubirFoto');
    const fotoAlert  = document.getElementById('fotoAlert');
    const fotoDrop   = document.getElementById('fotoDrop');
    let   selectedFile = null;

    if (fotoInput) {
        fotoInput.addEventListener('change', function () {
            handleFile(this.files[0]);
        });
    }

    if (fotoDrop) {
        fotoDrop.addEventListener('dragover', e => { e.preventDefault(); fotoDrop.style.borderColor = '#9a4904'; });
        fotoDrop.addEventListener('dragleave', ()=> { fotoDrop.style.borderColor = ''; });
        fotoDrop.addEventListener('drop', function (e) {
            e.preventDefault();
            fotoDrop.style.borderColor = '';
            handleFile(e.dataTransfer.files[0]);
        });
    }

    function handleFile(file) {
        if (!file) return;
        if (!['image/jpeg','image/jpg','image/png'].includes(file.type)) {
            showAlert(fotoAlert, 'danger', 'Solo se aceptan archivos JPG, JPEG o PNG.');
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            showAlert(fotoAlert, 'danger', 'El archivo supera el límite de 2 MB.');
            return;
        }
        selectedFile = file;
        const reader = new FileReader();
        reader.onload = e => { fotoPreview.src = e.target.result; };
        reader.readAsDataURL(file);
        btnSubir.disabled = false;
        fotoAlert.classList.add('d-none');
    }

    if (btnSubir) {
        btnSubir.addEventListener('click', function () {
            if (!selectedFile) return;

            const fd = new FormData();
            fd.append('foto', selectedFile);
            fd.append('_token', '{{ csrf_token() }}');

            btnSubir.disabled = true;
            btnSubir.innerHTML = '<i class="ri-loader-4-line me-1"></i>Subiendo...';

            fetch('{{ route('admin.profile.upload-foto') }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: fd,
            })
            .then(r => r.json())
            .then(function (data) {
                if (data.success) {
                    const newUrl = data.url + '?v=' + Date.now();

                    // Header avatars
                    document.querySelectorAll('.header-profile-user, .tud-avatar').forEach(img => {
                        img.src = newUrl;
                    });

                    // Carnet photo
                    const carnetImg = document.getElementById('ci-foto-img');
                    if (carnetImg) {
                        carnetImg.src = newUrl;
                        carnetImg.style.display = 'block';
                    }
                    const initials = document.getElementById('ci-initials');
                    if (initials) initials.style.display = 'none';

                    // Modal preview
                    const fotoPreviewEl = document.getElementById('fotoPreview');
                    if (fotoPreviewEl) fotoPreviewEl.src = newUrl;

                    showAlert(fotoAlert, 'success', data.message);
                    selectedFile = null;
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('uploadFotoModal'));
                        if (modal) modal.hide();
                    }, 1200);
                } else {
                    showAlert(fotoAlert, 'danger', data.message || 'Error al subir la foto.');
                }
            })
            .catch(function () {
                showAlert(fotoAlert, 'danger', 'Error de conexión. Intenta nuevamente.');
            })
            .finally(function () {
                btnSubir.disabled = false;
                btnSubir.innerHTML = '<i class="ri-save-line me-1"></i>Guardar Foto';
            });
        });
    }

    /* ── Resetear modal al cerrar ── */
    const modalEl = document.getElementById('uploadFotoModal');
    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function () {
            if (fotoInput) fotoInput.value = '';
            selectedFile = null;
            if (btnSubir) btnSubir.disabled = true;
            if (fotoAlert) fotoAlert.classList.add('d-none');
        });
    }

    /* ── Utilidad: mostrar alerta ── */
    function showAlert(el, type, msg) {
        if (!el) return;
        el.className = 'alert alert-' + type;
        el.textContent = msg;
        el.classList.remove('d-none');
        setTimeout(() => el.classList.add('d-none'), 5000);
    }

    /* ═══════════════════════════════════════
       DOCUMENTOS TAB
    ═══════════════════════════════════════ */
    
    let currentDocTipo = null;
    let selectedDocFile = null;

    const docInput = document.getElementById('docInput');
    const docDrop = document.getElementById('docDrop');
    const btnSubirDoc = document.getElementById('btnSubirDoc');
    const docAlert = document.getElementById('docAlert');

    if (docInput) {
        docInput.addEventListener('change', function() { handleDocFile(this.files[0]); });
    }
    if (docDrop) {
        docDrop.addEventListener('dragover', e => { e.preventDefault(); docDrop.style.borderColor = '#9a4904'; });
        docDrop.addEventListener('dragleave', () => { docDrop.style.borderColor = ''; });
        docDrop.addEventListener('drop', function(e) {
            e.preventDefault();
            docDrop.style.borderColor = '';
            handleDocFile(e.dataTransfer.files[0]);
        });
    }

    function handleDocFile(file) {
        if (!file) return;
        const tiposValidos = ['application/pdf', 'image/jpg', 'image/jpeg', 'image/png'];
        if (!tiposValidos.includes(file.type)) {
            showAlert(docAlert, 'danger', 'Solo se aceptan archivos PDF, JPG o PNG.');
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            showAlert(docAlert, 'danger', 'El archivo supera el límite de 5 MB.');
            return;
        }
        selectedDocFile = file;
        btnSubirDoc.disabled = false;
        docAlert.classList.add('d-none');
    }

    if (btnSubirDoc) {
        btnSubirDoc.addEventListener('click', function() {
            if (!selectedDocFile || !currentDocTipo) return;

            const fd = new FormData();
            fd.append('documento', selectedDocFile);
            fd.append('tipo', currentDocTipo);
            fd.append('_token', '{{ csrf_token() }}');

            btnSubirDoc.disabled = true;
            btnSubirDoc.innerHTML = '<i class="ri-loader-4-line me-1"></i>Subiendo...';

            fetch('{{ route("admin.profile.documento.subir") }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: fd,
            })
            .then(r => r.json())
            .then(function(data) {
                if (data.success) {
                    showAlert(docAlert, 'success', data.message);
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('uploadDocModal'));
                        if (modal) modal.hide();
                        location.reload();
                    }, 1200);
                } else {
                    showAlert(docAlert, 'danger', data.message || 'Error al subir el documento.');
                }
            })
            .catch(function() {
                showAlert(docAlert, 'danger', 'Error de conexión. Intenta nuevamente.');
            })
            .finally(function() {
                btnSubirDoc.disabled = false;
                btnSubirDoc.innerHTML = '<i class="ri-save-line me-1"></i>Guardar Documento';
            });
        });
    }

    document.querySelectorAll('.replace').forEach(function(btn) {
        btn.addEventListener('click', function() {
            currentDocTipo = this.dataset.tipo;
            selectedDocFile = null;
            if (docInput) docInput.value = '';
            if (btnSubirDoc) btnSubirDoc.disabled = true;
            if (docAlert) docAlert.classList.add('d-none');
            new bootstrap.Modal(document.getElementById('uploadDocModal')).show();
        });
    });

    document.querySelectorAll('.preview-doc').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            document.getElementById('previewDocFrame').src = url;
            document.getElementById('downloadDocLink').href = url;
            new bootstrap.Modal(document.getElementById('previewDocModal')).show();
        });
    });

    document.querySelectorAll('.verify').forEach(function(btn) {
        btn.addEventListener('click', function() {
            currentDocTipo = this.dataset.tipo;
            new bootstrap.Modal(document.getElementById('verifyDocModal')).show();
        });
    });

    document.querySelectorAll('.unverify').forEach(function(btn) {
        btn.addEventListener('click', function() {
            currentDocTipo = this.dataset.tipo;
            verificarDocumento('quitar');
        });
    });

    const btnConfirmVerify = document.getElementById('btnConfirmVerify');
    if (btnConfirmVerify) {
        btnConfirmVerify.addEventListener('click', function() {
            verificarDocumento('verificar');
        });
    }

    function verificarDocumento(accion) {
        fetch('{{ route("admin.profile.documento.verificar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ tipo: currentDocTipo, accion: accion }),
        })
        .then(r => r.json())
        .then(function(data) {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('verifyDocModal'));
                if (modal) modal.hide();
                location.reload();
            } else {
                alert(data.message || 'Error al procesar.');
            }
        })
        .catch(function() {
            alert('Error de conexión.');
        });
    }

    /* ── Resetear modal documentos al cerrar ── */
    const uploadDocModal = document.getElementById('uploadDocModal');
    if (uploadDocModal) {
        uploadDocModal.addEventListener('hidden.bs.modal', function() {
            if (docInput) docInput.value = '';
            selectedDocFile = null;
            if (btnSubirDoc) btnSubirDoc.disabled = true;
            if (docAlert) docAlert.classList.add('d-none');
        });
    }

    /* ════════════════════════════════════
       MARKETING TAB
    ════════════════════════════════════ */
    @if(isset($tieneMarketing) && $tieneMarketing)

    /* ── Helpers ── */
    let marketingChart   = null;
    let programasChart   = null;
    let mktCurrentPage   = 1;
    let mktCurrentFilters = {};

    const mktRouteStats        = '{{ route("admin.profile.marketing.estadisticas") }}';
    const mktRouteInscripciones= '{{ route("admin.profile.marketing.inscripciones") }}';
    const mktRouteDocumentosBase = '/admin/profile/marketing/documentos/';
    const mktRouteOfertas      = '{{ route("admin.profile.marketing.ofertas-activas") }}';

    /* ── Helpers ── */
    function mktParams(extra) {
        const f  = document.getElementById('marketingFilterForm');
        const fd = f ? new FormData(f) : new FormData();
        const p  = new URLSearchParams(fd);
        if (extra) Object.entries(extra).forEach(([k,v]) => p.set(k, v));
        return p.toString();
    }

    function ofertasParams(extra) {
        const f  = document.getElementById('ofertasFilterForm');
        const fd = f ? new FormData(f) : new FormData();
        const p  = new URLSearchParams(fd);
        if (extra) Object.entries(extra).forEach(([k,v]) => p.set(k, v));
        return p.toString();
    }

    function mktSpinner(containerId) {
        document.getElementById(containerId).innerHTML =
            '<div class="text-center py-5"><div class="spinner-border" role="status" style="color:#9a4904;"></div><p class="mt-2 text-muted small">Cargando...</p></div>';
    }

    /* ── Charts ── */
    function renderMarketingCharts(data) {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js no está cargado');
            return;
        }
        const mesesCortos = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

        /* Bar chart */
        const ctxBar = document.getElementById('marketingChart');
        if (!ctxBar) return;
        if (marketingChart) marketingChart.destroy();

        marketingChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: data.grafico.meses,
                datasets: [
                    {
                        label: 'Inscritos',
                        data: data.grafico.inscritos,
                        backgroundColor: 'rgba(16,185,129,0.8)',
                        borderRadius: 4,
                    },
                    {
                        label: 'Pre-Inscritos',
                        data: data.grafico.pre_inscritos,
                        backgroundColor: 'rgba(252,123,4,0.75)',
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 10 }, stepSize: 1 } }
                }
            }
        });

        /* Doughnut chart — top programas */
        const ctxDough = document.getElementById('programasChart');
        if (!ctxDough) return;
        if (programasChart) programasChart.destroy();

        const palette = ['#9a4904','#df6a04','#fc7b04','#bc5404','#743c04','#391b04','#e88c30','#c96b0a'];
        const labels  = data.programas.map(p => p.programa_nombre.length > 22 ? p.programa_nombre.slice(0,22)+'…' : p.programa_nombre);
        const values  = data.programas.map(p => p.total);

        if (values.length === 0) {
            ctxDough.parentElement.innerHTML = '<div class="text-center py-5 text-muted small">Sin datos para el período</div>';
            return;
        }

        programasChart = new Chart(ctxDough, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{ data: values, backgroundColor: palette.slice(0, values.length), borderWidth: 2, borderColor: '#fff' }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 }, padding: 8 } },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.label}: ${ctx.raw} (${Math.round(ctx.raw/values.reduce((a,b)=>a+b,0)*100)}%)`
                        }
                    }
                }
            }
        });
    }

    /* ── Documentos modal ── */
    window.openDocumentosModal = function(estudianteId, estudianteNombre) {
        document.getElementById('docEstudianteNombre').textContent = estudianteNombre;
        document.getElementById('docEstudianteCarnet').textContent = '—';
        document.getElementById('documentosList').innerHTML =
            '<div class="text-center py-4"><div class="spinner-border" role="status" style="color:#9a4904;"></div><p class="mt-2 text-muted">Cargando documentos...</p></div>';

        const modal = new bootstrap.Modal(document.getElementById('documentosModal'));
        modal.show();

        fetch(mktRouteDocumentosBase + estudianteId, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    document.getElementById('documentosList').innerHTML =
                        '<div class="text-center py-4 text-danger small">Error al cargar documentos.</div>';
                    return;
                }

                document.getElementById('docEstudianteCarnet').textContent = 'CI: ' + (data.estudiante.carnet ?? '—');

                const iconos = {
                    fotografia_carnet: 'ri-user-3-line',
                    certificado_nacimiento: 'ri-article-line',
                    documento_academico: 'ri-graduation-cap-line',
                    documento_provision_nacional: 'ri-award-line',
                };

                let html = '';
                Object.entries(data.documentos).forEach(([key, doc]) => {
                    const icono = iconos[key] ?? 'ri-file-line';
                    if (doc.ruta) {
                        const esImagen = /\.(jpg|jpeg|png|gif|webp)$/i.test(doc.ruta);
                        html += `<div class="col-md-6">
                            <div class="doc-card p-3 border rounded" style="background:var(--prof-surface);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="${icono}" style="color:#9a4904;font-size:1.2rem;"></i>
                                    <span style="font-weight:600;font-size:0.82rem;">${doc.nombre}</span>
                                    <span class="ms-auto badge" style="background:#d1fae5;color:#065f46;font-size:0.68rem;">Subido</span>
                                </div>
                                ${esImagen
                                    ? `<img src="${doc.ruta}" class="img-fluid rounded" style="max-height:140px;object-fit:cover;width:100%;" onerror="this.parentElement.innerHTML='<div class=\'text-center text-muted small py-2\'>No se pudo cargar la imagen</div>'">`
                                    : `<a href="${doc.ruta}" target="_blank" class="btn btn-sm btn-outline-secondary w-100"><i class="ri-eye-line me-1"></i>Ver documento</a>`
                                }
                                ${doc.detalle ? `<div style="font-size:0.7rem;color:#64748b;margin-top:6px;">${doc.detalle}</div>` : ''}
                            </div>
                        </div>`;
                    } else {
                        html += `<div class="col-md-6">
                            <div class="doc-card p-3 border rounded" style="background:var(--prof-surface);opacity:0.7;">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="${icono}" style="color:#94a3b8;font-size:1.2rem;"></i>
                                    <span style="font-weight:600;font-size:0.82rem;color:#64748b;">${doc.nombre}</span>
                                    <span class="ms-auto badge" style="background:#fee2e2;color:#991b1b;font-size:0.68rem;">Faltante</span>
                                </div>
                                <div style="font-size:0.75rem;color:#94a3b8;">
                                    ${doc.detalle ?? 'No se ha subido este documento'}
                                </div>
                            </div>
                        </div>`;
                    }
                });

                document.getElementById('documentosList').innerHTML = html || '<div class="col-12 text-center text-muted small">Sin documentos registrados.</div>';
            })
            .catch(() => {
                document.getElementById('documentosList').innerHTML =
                    '<div class="text-center py-4 text-danger small">Error de conexión.</div>';
            });
    };

    /* ── Load stats ── */
    function loadMarketingData() {
        fetch(`${mktRouteStats}?${mktParams()}`, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                if (!data.success) { console.warn('Marketing stats error:', data); return; }

                document.getElementById('totalInscripcionesCard').textContent = data.resumen.total;
                document.getElementById('totalInscritosCard').textContent     = data.resumen.inscritos;
                document.getElementById('totalPreInscritosCard').textContent  = data.resumen.pre_inscritos;
                document.getElementById('periodoActualCard').textContent =
                    data.resumen.mes_seleccionado + ' ' + data.resumen.anio_seleccionado;

                const titleEl = document.getElementById('chartTitle');
                if (titleEl) titleEl.textContent = `Inscripciones por Mes (${data.resumen.anio_seleccionado})`;

                renderMarketingCharts(data);
            })
            .catch(() => {});
    }

    /* ── Render inscription table (agrupada por programa) ── */
    function renderMarketingTable(data) {
        const container  = document.getElementById('marketingTableContainer');
        const pagination = document.getElementById('marketingPagination');
        const countEl    = document.getElementById('tableCount');

        if (countEl) countEl.textContent = data.pagination.total;

        const grouped = data.agrupadas;
        if (!grouped || grouped.length === 0) {
            container.innerHTML = '<div class="text-center py-5 text-muted small">No se encontraron inscripciones.</div>';
            pagination.innerHTML = '';
            return;
        }

        let html = '';
        
        grouped.forEach((grupo, grupoIndex) => {
            html += `
            <div class="mkt-program-group mb-4">
                <div class="mkt-program-header">
                    <i class="ri-book-2-line"></i>
                    <span class="mkt-program-title">${grupo.programa}</span>
                    <span class="mkt-program-badge">${grupo.total} inscripción${grupo.total !== 1 ? 'es' : ''}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mkt-group-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Estudiante</th>
                                <th>Documentos</th>
                                <th>Plan</th>
                                <th>Sucursal</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>`;
            
            grupo.inscripciones.forEach((ins, i) => {
                const persona  = ins.estudiante?.persona;
                const nombre   = persona ? `${persona.nombres ?? ''} ${persona.apellido_paterno ?? ''}`.trim() : '—';
                const carnet   = persona?.carnet ?? '—';
                const planPago = ins.plan_pago_nombre ?? '-';
                const sucursal = ins.sucursal_nombre ?? '—';
                const estudianteId = ins.estudiante?.id ?? 0;
                const fecha    = ins.fecha_registro ? ins.fecha_registro.slice(0,10).split('-').reverse().join('/') : '—';
                
                const docs = {};
                if (persona) {
                    docs.fotografia_carnet = persona.fotografia_carnet;
                    docs.fotografia_certificado_nacimiento = persona.fotografia_certificado_nacimiento;
                }
                
                const estudio = ins.estudio;
                if (estudio) {
                    docs.documento_academico = estudio.documento_academico;
                    docs.documento_provision_nacional = estudio.documento_provision_nacional;
                }
                
                let docsHtml = '';
                if (ins.estado === 'Inscrito') {
                    const docKeys = Object.keys(docs);
                    if (docKeys.length > 0) {
                        const badges = [];
                        for (let j = 0; j < docKeys.length; j++) {
                            const key = docKeys[j];
                            const val = docs[key];
                            let docName = key;
                            if (key === 'fotografia_carnet') docName = 'CI';
                            else if (key === 'fotografia_certificado_nacimiento') docName = 'CN';
                            else if (key === 'documento_academico') docName = 'Tit';
                            else if (key === 'documento_provision_nacional') docName = 'PN';
                            
                            if (val) {
                                badges.push('<span class="badge" style="background:#059669;color:#ffffff;font-size:0.65rem;" title="' + key + '"><i class="ri-check-line"></i>' + docName + '</span>');
                            } else {
                                badges.push('<span class="badge" style="background:#dc2626;color:#ffffff;font-size:0.65rem;" title="' + key + '"><i class="ri-close-line"></i>' + docName + '</span>');
                            }
                        }
                        docsHtml = '<div class="d-flex gap-1 flex-wrap">' + badges.join('') + '</div>';
                    } else {
                        docsHtml = '<span class="text-muted" style="font-size:0.7rem;">Sin datos</span>';
                    }
                } else {
                    docsHtml = '<span class="text-muted" style="font-size:0.7rem;">—</span>';
                }

                const planBadge = `<span class="mkt-badge-plan"><i class="ri-card-line"></i>${planPago}</span>`;
                const sucursalBadge = `<span class="mkt-badge-sucursal"><i class="ri-map-pin-line"></i>${sucursal}</span>`;
                const fechaBadge = `<span class="mkt-badge-fecha"><i class="ri-calendar-line"></i>${fecha}</span>`;

                const estadoBadge = ins.estado === 'Inscrito'
                    ? `<span class="mkt-status-inscrito"><i class="ri-checkbox-circle-line"></i>${ins.estado}</span>`
                    : `<span class="mkt-status-preinscrito"><i class="ri-time-line"></i>${ins.estado}</span>`;

                const estudianteNombre = nombre;
                const ofertaId = ins.ofertas_academica_id ?? 0;
                const planPagoId = ins.planes_pago_id ?? 0;
                const btnCambiar = ins.estado === 'Pre-Inscrito'
                    ? `<button type="button" class="btn btn-sm mkt-btn-cambiar-inscrito"
                            data-inscripcion-id="${ins.id}"
                            data-oferta-id="${ofertaId}"
                            data-estudiante="${nombre.replace(/"/g,'&quot;')}"
                            data-ci="${carnet}"
                            data-plan-pago-id="${planPagoId}"
                            title="Cambiar a Inscrito"
                            style="display:inline-flex;align-items:center;gap:3px;background:linear-gradient(135deg,#f59e0b,#fbbf24);border:none;color:#78350f;border-radius:6px;padding:.3rem .55rem;font-size:.85rem;line-height:1;">
                            <i class="ri-user-check-line"></i><i class="ri-arrow-right-s-line"></i>
                        </button>`
                    : '';

                html += `<tr>
                    <td class="text-muted">${i + 1}</td>
                    <td>
                        <div style="font-weight:600;font-size:0.82rem;">${nombre}</div>
                        <div style="font-size:0.7rem;color:#64748b;">${carnet}</div>
                    </td>
                    <td>${docsHtml}</td>
                    <td>${planBadge}</td>
                    <td>${sucursalBadge}</td>
                    <td>${estadoBadge}</td>
                    <td>${fechaBadge}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="/admin/estudiantes/${estudianteId}/detalle" class="btn btn-sm btn-outline-secondary" title="Ver detalle">
                                <i class="ri-user-line"></i>
                            </a>
                            ${btnCambiar}
                        </div>
                    </td>
                </tr>`;
            });

            html += `</tbody></table></div></div>`;
        });

        container.innerHTML = html;

        renderMktPagination(data.pagination, 'marketingPagination', goToMktPage);
    }

    function goToMktPage(page) {
        mktCurrentPage = page;
        mktSpinner('marketingTableContainer');
        fetch(`${mktRouteInscripciones}?${mktParams({ page })}`, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => { if (data.success) renderMarketingTable(data); })
            .catch(() => {});
    }

    /* ════════════════════════════════════════════════════════
       ENLACE PRE-INSCRIPCIÓN
    ════════════════════════════════════════════════════════ */
    const mktRouteGenerarEnlace = '{{ route("admin.profile.marketing.generar-enlace") }}';
    const mktRoutePlanesBase    = '{{ url("admin/profile/marketing/oferta") }}';
    const csrfToken             = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    let _enlaceOfertaId     = null;
    let _planesData         = [];   // planes cargados para el select
    let _qrSinPlan          = null;
    let _qrConPlan          = null;

    /* Abrir modal desde el botón de la fila */
    window.abrirModalEnlace = function(ofertaId, programaNombre) {
        _enlaceOfertaId = ofertaId;
        _planesData     = [];
        _qrSinPlan      = null;
        _qrConPlan      = null;

        // Reset UI
        document.getElementById('enlaceModalPrograma').textContent = programaNombre;
        enlaceResetPanel('SinPlan');
        enlaceResetPanel('ConPlan');
        document.getElementById('selectPlan').innerHTML = '<option value="">— Elige un plan —</option>';
        document.getElementById('planDetalle').style.display = 'none';
        document.getElementById('planDetalle').innerHTML = '';
        enlaceSwitchTab('sin-plan');   // activa tab sin plan y dispara carga

        new bootstrap.Modal(document.getElementById('enlacePreinscripcionModal')).show();
    };

    /* Cambio de tab */
    window.enlaceSwitchTab = function(tab) {
        const isSin = tab === 'sin-plan';

        // Estilos de tabs
        const tabSin = document.getElementById('tabSinPlan');
        const tabCon = document.getElementById('tabConPlan');
        const ACTIVE = 'background:rgba(154,73,4,.12);color:#9a4904;border-color:rgba(154,73,4,.3);';
        const IDLE   = 'background:transparent;color:#64748b;border-color:var(--prof-border);';
        tabSin.style.cssText = (tabSin.style.cssText || '') + (isSin ? ACTIVE : IDLE);
        tabCon.style.cssText = (tabCon.style.cssText || '') + (isSin ? IDLE : ACTIVE);

        document.getElementById('panelSinPlan').style.display = isSin ? 'block' : 'none';
        document.getElementById('panelConPlan').style.display = isSin ? 'none'  : 'block';

        if (isSin) {
            // Cargar enlace sin plan si aún no está
            if (document.getElementById('contentSinPlan').style.display === 'none'
                && document.getElementById('loadingSinPlan').style.display !== 'block') {
                enlaceCargar(null, 'SinPlan');
            }
        } else {
            // Cargar planes disponibles para el select si aún no se cargaron
            if (_planesData.length === 0) {
                enlaceCargarPlanes();
            }
        }
    };

    /* Cargar planes para el select */
    function enlaceCargarPlanes() {
        const sel = document.getElementById('selectPlan');
        const loadingMsg = document.getElementById('planesLoadingMsg');
        sel.style.display = 'none';
        loadingMsg.style.display = 'block';

        fetch(`${mktRoutePlanesBase}/${_enlaceOfertaId}/planes`, { headers: { Accept: 'application/json' } })
            .then(r => r.json())
            .then(data => {
                loadingMsg.style.display = 'none';
                sel.style.display = 'block';
                if (!data.success || !data.planes.length) {
                    sel.innerHTML = '<option value="">— Sin planes configurados —</option>';
                    return;
                }
                _planesData = data.planes;
                sel.innerHTML = '<option value="">— Elige un plan —</option>';
                data.planes.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.id;
                    opt.textContent = p.nombre;
                    sel.appendChild(opt);
                });
            })
            .catch(() => {
                loadingMsg.style.display = 'none';
                sel.style.display = 'block';
                sel.innerHTML = '<option value="">— Error al cargar —</option>';
            });
    }

    /* Cambio en el select de plan */
    window.onPlanChange = function() {
        const planId = document.getElementById('selectPlan').value;
        const detalle = document.getElementById('planDetalle');

        // Reset contenido con plan
        enlaceResetPanel('ConPlan');

        if (!planId) {
            detalle.style.display = 'none';
            detalle.innerHTML = '';
            return;
        }

        // Mostrar detalle del plan seleccionado
        const plan = _planesData.find(p => String(p.id) === String(planId));
        if (plan) {
            let html = `<div style="font-size:.72rem;font-weight:700;color:#9a4904;margin-bottom:.5rem;">${plan.nombre}</div>`;
            let total = 0;
            plan.conceptos.forEach(c => {
                total += parseFloat(c.pago_bs);
                html += `<div style="display:flex;justify-content:space-between;font-size:.75rem;color:#94a3b8;padding:.2rem 0;">
                    <span>${c.concepto ?? 'Concepto'}</span>
                    <span style="color:var(--prof-text);font-weight:600;">Bs. ${Number(c.pago_bs).toLocaleString('es-BO')}${c.n_cuotas > 1 ? ` <span style="font-size:.67rem;color:#64748b;">×${c.n_cuotas}</span>` : ''}</span>
                </div>`;
            });
            html += `<div style="display:flex;justify-content:space-between;font-size:.78rem;font-weight:700;color:#e8b84a;padding-top:.4rem;margin-top:.35rem;border-top:1px solid rgba(255,255,255,.08);">
                <span>Total</span><span>Bs. ${total.toLocaleString('es-BO')}</span>
            </div>`;
            detalle.innerHTML = html;
            detalle.style.display = 'block';
        }

        // Generar enlace con este plan
        enlaceCargar(planId, 'ConPlan');
    };

    /* Generar / recuperar enlace para un panel dado */
    function enlaceCargar(planId, prefix) {
        document.getElementById(`loading${prefix}`).style.display = 'block';
        document.getElementById(`content${prefix}`).style.display = 'none';
        document.getElementById(`error${prefix}`).style.display   = 'none';
        document.getElementById(`qrContainer${prefix}`).innerHTML = '';
        if (prefix === 'SinPlan') _qrSinPlan = null;
        else _qrConPlan = null;

        const body = { oferta_academica_id: _enlaceOfertaId };
        if (planId) body.planes_pago_id = planId;

        fetch(mktRouteGenerarEnlace, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(body),
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById(`loading${prefix}`).style.display = 'none';
            if (!data.success) {
                document.getElementById(`errorMsg${prefix}`).textContent = data.message || 'Error al generar.';
                document.getElementById(`error${prefix}`).style.display = 'block';
                return;
            }

            document.getElementById(`urlInput${prefix}`).value = data.url;
            document.getElementById(`btnAbrir${prefix}`).href  = data.url;
            document.getElementById(`content${prefix}`).style.display = 'block';

            if (typeof QRCode !== 'undefined') {
                const qr = new QRCode(document.getElementById(`qrContainer${prefix}`), {
                    text: data.url, width: 170, height: 170,
                    colorDark: '#1c0d00', colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H,
                });
                if (prefix === 'SinPlan') _qrSinPlan = qr;
                else _qrConPlan = qr;
            }
        })
        .catch(() => {
            document.getElementById(`loading${prefix}`).style.display = 'none';
            document.getElementById(`errorMsg${prefix}`).textContent = 'Error de conexión.';
            document.getElementById(`error${prefix}`).style.display = 'block';
        });
    }

    /* Reset de un panel sin destruir el DOM */
    function enlaceResetPanel(prefix) {
        document.getElementById(`loading${prefix}`).style.display = 'none';
        document.getElementById(`content${prefix}`).style.display = 'none';
        document.getElementById(`error${prefix}`).style.display   = 'none';
        document.getElementById(`qrContainer${prefix}`).innerHTML = '';
    }

    /* Copiar enlace */
    window.copiarEnlace = function(prefix) {
        const input = document.getElementById(`urlInput${prefix}`);
        const btn   = document.getElementById(`btnCopiar${prefix}`);
        const lbl   = document.getElementById(`lblCopiar${prefix}`);
        navigator.clipboard.writeText(input.value).then(() => {
            lbl.textContent = '¡Copiado!';
            btn.style.background = '#059669';
            setTimeout(() => { lbl.textContent = 'Copiar'; btn.style.background = '#9a4904'; }, 2000);
        }).catch(() => { input.select(); document.execCommand('copy'); });
    };

    /* Descargar QR */
    window.descargarQr = function(prefix) {
        const canvas = document.querySelector(`#qrContainer${prefix} canvas`);
        if (!canvas) return;
        const link = document.createElement('a');
        link.download = `qr-preinscripcion-${prefix.toLowerCase()}.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();
    };

    /* ── Ofertas table ── */
    let ofertasCurrentPage = 1;

    function renderOfertasTable(data) {
        const container  = document.getElementById('ofertasTableContainer');
        const pagination = document.getElementById('ofertasPagination');
        const countEl    = document.getElementById('ofertasCount');

        if (countEl) countEl.textContent = data.ofertas.total;

        const banner = document.getElementById('cargoPrincipalBanner');
        const bannerText = document.getElementById('cargoPrincipalText');
        if (banner && data.cargo_principal) {
            bannerText.textContent = `Tu cargo principal: ${data.cargo_principal.cargo_nombre} — ${data.cargo_principal.sucursal_nombre}`;
            banner.classList.remove('d-none');
        }

        const rows = data.ofertas.data;
        if (!rows || rows.length === 0) {
            container.innerHTML = '<div class="text-center py-5 text-muted small">No hay ofertas activas.</div>';
            if (pagination) pagination.innerHTML = '';
            return;
        }

        let html = '<div class="table-responsive"><table class="table table-hover">';
        html += `<thead><tr>
            <th>Código</th>
            <th>Programa</th>
            <th>Sucursal</th>
            <th>Modalidad</th>
            <th>Inicio Inscr.</th>
            <th>Fin Programa</th>
            <th>Enlace</th>
        </tr></thead><tbody>`;

        rows.forEach(o => {
            const programaNombreEsc = (o.programa_nombre || '').replace(/'/g, "\\'");
            html += `<tr>
                <td><span style="font-weight:600;font-family:'Outfit',sans-serif;color:#9a4904;">${o.codigo ?? '—'}</span></td>
                <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${o.programa_nombre}</td>
                <td>${o.sucursal_nombre}${o.sede_nombre ? `<span style="font-size:0.7rem;color:#64748b;display:block;">${o.sede_nombre}</span>` : ''}</td>
                <td>${o.modalidad_nombre}</td>
                <td style="white-space:nowrap;">${o.fecha_inicio_formateada}</td>
                <td style="white-space:nowrap;">${o.fecha_fin_formateada}</td>
                <td>
                    <button type="button"
                        onclick="abrirModalEnlace(${o.id}, '${programaNombreEsc}')"
                        title="Generar enlace y QR de pre-inscripción"
                        style="padding:.3rem .6rem;background:rgba(154,73,4,.12);border:1px solid rgba(154,73,4,.3);color:#9a4904;border-radius:6px;cursor:pointer;font-size:.78rem;display:inline-flex;align-items:center;gap:.3rem;transition:background .2s;">
                        <i class="ri-qr-code-line"></i>
                    </button>
                </td>
            </tr>`;
        });

        html += '</tbody></table></div>';
        container.innerHTML = html;

        renderMktPagination(data.ofertas, 'ofertasPagination', goToOfertasPage);
    }

    function goToOfertasPage(page) {
        ofertasCurrentPage = page;
        mktSpinner('ofertasTableContainer');
        fetch(`${mktRouteOfertas}?${ofertasParams({ page })}`, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    renderOfertasTable(data);
                } else {
                    document.getElementById('ofertasTableContainer').innerHTML =
                        `<div class="text-center py-5 text-muted small">${data.message || 'Error al cargar'}</div>`;
                }
            })
            .catch(() => {
                document.getElementById('ofertasTableContainer').innerHTML =
                    '<div class="text-center py-5 text-danger small">Error de conexión.</div>';
            });
    }

    function loadOfertasData() {
        mktSpinner('ofertasTableContainer');
        fetch(`${mktRouteOfertas}?${ofertasParams()}`, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    renderOfertasTable(data);
                } else {
                    document.getElementById('ofertasTableContainer').innerHTML =
                        `<div class="text-center py-5 text-muted small">
                            <i class="ri-error-warning-line" style="font-size:2rem;color:#ef4444;display:block;margin-bottom:8px;"></i>
                            ${data.message || data.error || 'No se pudieron cargar las ofertas'}
                        </div>`;
                }
            })
            .catch(() => {
                document.getElementById('ofertasTableContainer').innerHTML =
                    '<div class="text-center py-5 text-danger small"><i class="ri-wifi-off-line" style="font-size:2rem;display:block;margin-bottom:8px;"></i>Error de conexión al cargar ofertas.</div>';
            });
    }

    /* ── Shared pagination renderer ── */
    window._mktPageFns = {};

    function renderMktPagination(pag, containerId, goFn) {
        const el = document.getElementById(containerId);
        if (!el) return;
        if (pag.last_page <= 1) { el.innerHTML = ''; return; }

        window._mktPageFns[containerId] = goFn;
        const fnRef = `window._mktPageFns['${containerId}']`;

        let html = '<div class="mkt-pagination">';

        html += `<button class="mkt-page-btn" ${pag.current_page===1?'disabled':''} onclick="${fnRef}(${pag.current_page-1})">
            <i class="ri-arrow-left-s-line"></i>
        </button>`;

        for (let p = 1; p <= pag.last_page; p++) {
            if (pag.last_page > 7 && (p > 2 && p < pag.current_page - 1)) {
                html += '<span class="mkt-page-btn" style="cursor:default;">…</span>';
                p = pag.current_page - 2; continue;
            }
            if (pag.last_page > 7 && (p > pag.current_page + 1 && p < pag.last_page - 1)) {
                html += '<span class="mkt-page-btn" style="cursor:default;">…</span>';
                p = pag.last_page - 1; continue;
            }
            html += `<button class="mkt-page-btn ${p===pag.current_page?'active':''}" onclick="${fnRef}(${p})">${p}</button>`;
        }

        html += `<button class="mkt-page-btn" ${pag.current_page===pag.last_page?'disabled':''} onclick="${fnRef}(${pag.current_page+1})">
            <i class="ri-arrow-right-s-line"></i>
        </button>`;

        html += '</div>';
        el.innerHTML = html;
    }

    /* ── Tab activation triggers ── */
    let mktLoaded     = false;
    let ofertasLoaded = false;

    function onTabShown(e) {
        const target = e.target.getAttribute('data-bs-target') || e.target.getAttribute('href');

        if (target === '#marketing' && !mktLoaded) {
            mktLoaded = true;
            loadMarketingData();
            goToMktPage(1);
        }

        if (target === '#ofertas-activas' && !ofertasLoaded) {
            ofertasLoaded = true;
            loadOfertasData();
        }
    }

    document.querySelectorAll('.profile-tab').forEach(btn => {
        btn.addEventListener('shown.bs.tab', onTabShown);
    });

    /* Fallback: si el tab ya está activo al cargar (raro pero posible) */
    const activeTab = document.querySelector('.profile-tab.active');
    if (activeTab) {
        const t = activeTab.getAttribute('data-bs-target');
        if (t === '#marketing' && !mktLoaded) {
            mktLoaded = true;
            loadMarketingData();
            goToMktPage(1);
        }
        if (t === '#ofertas-activas' && !ofertasLoaded) {
            ofertasLoaded = true;
            loadOfertasData();
        }
    }

    /* ── Filter form submissions ── */
    const mktFilterForm = document.getElementById('marketingFilterForm');
    if (mktFilterForm) {
        mktFilterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            mktLoaded = true;
            loadMarketingData();
            goToMktPage(1);
        });
    }

    const resetMktBtn = document.getElementById('resetMarketingFilter');
    if (resetMktBtn) {
        resetMktBtn.addEventListener('click', function () {
            if (mktFilterForm) mktFilterForm.reset();
            loadMarketingData();
            goToMktPage(1);
        });
    }

    const refreshMktBtn = document.getElementById('refreshMarketing');
    if (refreshMktBtn) {
        refreshMktBtn.addEventListener('click', function () {
            loadMarketingData();
            goToMktPage(1);
        });
    }

    const ofertasFilterForm = document.getElementById('ofertasFilterForm');
    if (ofertasFilterForm) {
        ofertasFilterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            ofertasCurrentPage = 1;
            loadOfertasData();
        });
    }

    const resetOfertasBtn = document.getElementById('resetOfertasFilter');
    if (resetOfertasBtn) {
        resetOfertasBtn.addEventListener('click', function () {
            if (ofertasFilterForm) ofertasFilterForm.reset();
            ofertasCurrentPage = 1;
            loadOfertasData();
        });
    }

    const refreshOfertasBtn = document.getElementById('refreshOfertas');
    if (refreshOfertasBtn) {
        refreshOfertasBtn.addEventListener('click', loadOfertasData);
    }

    @endif

    /* ── Comprobantes de Pago ── */

    @if($tieneMarketing)

    let compInscripcionId = null;

    function mostrarToast(tipo, mensaje) {
        const bg = tipo === 'success' ? '#16a34a' : tipo === 'warning' ? '#d97706' : '#dc2626';
        const icono = tipo === 'success' ? 'ri-checkbox-circle-line' : tipo === 'warning' ? 'ri-alert-line' : 'ri-close-circle-line';
        const toast = document.createElement('div');
        toast.style.cssText = `position:fixed;bottom:24px;right:24px;z-index:9999;background:${bg};color:#fff;padding:.75rem 1.25rem;border-radius:10px;font-size:.85rem;font-weight:500;display:flex;align-items:center;gap:.6rem;box-shadow:0 8px 24px rgba(0,0,0,.18);max-width:360px;animation:slideInToast .3s ease;`;
        toast.innerHTML = `<i class="${icono}" style="font-size:1.1rem;flex-shrink:0;"></i><span>${mensaje}</span>`;
        if (!document.getElementById('toast-anim-style')) {
            const s = document.createElement('style');
            s.id = 'toast-anim-style';
            s.textContent = '@keyframes slideInToast{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}';
            document.head.appendChild(s);
        }
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity .4s'; setTimeout(() => toast.remove(), 400); }, 3500);
    }

    function escAttr(str) {
        return String(str || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;');
    }

    function escHtml(str) {
        return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function loadInscritos() {
        const container = document.getElementById('inscritosComprobanteContainer');
        if (!container) return;
        container.innerHTML = '<div class="text-center py-4"><div class="spinner-border spinner-border-sm" style="color:#9a4904;"></div><span class="ms-2 text-muted" style="font-size:.875rem;">Cargando...</span></div>';

        fetch('{{ route("admin.profile.marketing.inscritos-comprobante") }}')
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    container.innerHTML = '<p class="text-muted text-center py-3">No se pudieron cargar los datos.</p>';
                    return;
                }

                const inscritos = data.inscritos || [];
                const badge = document.getElementById('comprobantesCount');
                if (badge) badge.textContent = inscritos.length;

                if (inscritos.length === 0) {
                    container.innerHTML = '<div class="text-center py-4" style="color:#94a3b8;"><i class="ri-user-line" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i><p style="font-size:.875rem;">No tienes estudiantes inscritos actualmente.</p></div>';
                    return;
                }

                let html = '<div style="overflow-x:auto;"><table style="width:100%;border-collapse:collapse;">'
                    + '<thead><tr style="background:#f8fafc;">'
                    + '<th style="padding:.6rem 1rem;font-size:.72rem;font-weight:600;text-transform:uppercase;color:#64748b;border-bottom:1px solid #e2e8f0;">Estudiante</th>'
                    + '<th style="padding:.6rem 1rem;font-size:.72rem;font-weight:600;text-transform:uppercase;color:#64748b;border-bottom:1px solid #e2e8f0;">Programa</th>'
                    + '<th style="padding:.6rem 1rem;font-size:.72rem;font-weight:600;text-transform:uppercase;color:#64748b;border-bottom:1px solid #e2e8f0;">Plan de Pago</th>'
                    + '<th style="padding:.6rem 1rem;font-size:.72rem;font-weight:600;text-transform:uppercase;color:#64748b;border-bottom:1px solid #e2e8f0;">Comprobantes</th>'
                    + '<th style="padding:.6rem 1rem;font-size:.72rem;font-weight:600;text-transform:uppercase;color:#64748b;border-bottom:1px solid #e2e8f0;">Acción</th>'
                    + '</tr></thead><tbody>';

                inscritos.forEach(ins => {
                    const compsBadges = ins.comprobantes.length
                        ? ins.comprobantes.map(c => {
                            const bg = c.estado === 'verificado' ? '#d1fae5' : c.estado === 'rechazado' ? '#fee2e2' : '#fef3c7';
                            const fg = c.estado === 'verificado' ? '#065f46' : c.estado === 'rechazado' ? '#991b1b' : '#92400e';
                            return `<span style="display:inline-block;padding:.15rem .5rem;border-radius:4px;font-size:.7rem;font-weight:600;background:${bg};color:${fg};margin:.1rem;">${escHtml(c.fecha)} · ${escHtml(c.estado)}</span>`;
                        }).join('')
                        : '<span style="font-size:.75rem;color:#94a3b8;">Ninguno</span>';

                    const accionHtml = ins.tiene_cuotas_pendientes
                        ? `<button class="btn-subir-comprobante"
                                data-inscripcion-id="${ins.inscripcion_id}"
                                data-nombre="${escAttr(ins.estudiante_nombre)}"
                                data-programa="${escAttr(ins.programa)}"
                                data-plan="${escAttr(ins.plan_pago)}"
                                style="padding:.35rem .85rem;background:#9a4904;color:white;border:none;border-radius:6px;font-size:.78rem;font-weight:500;cursor:pointer;">
                                <i class="ri-upload-cloud-line me-1"></i>Subir Comprobante
                            </button>`
                        : `<span style="font-size:.75rem;color:#16a34a;font-weight:600;"><i class="ri-checkbox-circle-line me-1"></i>Al día</span>`;

                    html += `<tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:.65rem 1rem;">
                            <div style="font-weight:600;font-size:.85rem;color:#1e293b;">${escHtml(ins.estudiante_nombre)}</div>
                            <div style="font-size:.72rem;color:#94a3b8;">CI: ${escHtml(ins.estudiante_carnet)}</div>
                        </td>
                        <td style="padding:.65rem 1rem;font-size:.82rem;color:#475569;">${escHtml(ins.programa)}</td>
                        <td style="padding:.65rem 1rem;font-size:.82rem;color:#475569;">${escHtml(ins.plan_pago)}</td>
                        <td style="padding:.65rem 1rem;">${compsBadges}</td>
                        <td style="padding:.65rem 1rem;">${accionHtml}</td>
                    </tr>`;
                });

                html += '</tbody></table></div>';
                container.innerHTML = html;
            })
            .catch(() => { container.innerHTML = '<p class="text-muted text-center py-3">Error al cargar datos.</p>'; });
    }

    function abrirModalComprobante(inscripcionId, nombre, programa, plan) {
        compInscripcionId = inscripcionId;

        document.getElementById('compEstudianteNombre').textContent = nombre;
        document.getElementById('compEstudianteDetalle').textContent = programa + ' · ' + plan;
        document.getElementById('compArchivo').value = '';
        document.getElementById('compObservaciones').value = '';

        const cuotasContainer = document.getElementById('compCuotasContainer');
        const cuotasLoading   = document.getElementById('compCuotasLoading');
        cuotasContainer.style.display = 'none';
        cuotasContainer.innerHTML = '';
        cuotasLoading.style.display = 'block';

        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalComprobante')).show();

        fetch(`{{ url('admin/profile/marketing/inscripcion') }}/${inscripcionId}/cuotas`)
            .then(r => r.json())
            .then(data => {
                cuotasLoading.style.display = 'none';
                if (!data.success) {
                    cuotasContainer.innerHTML = '<p class="text-muted" style="font-size:.82rem;">No se pudieron cargar las cuotas.</p>';
                    cuotasContainer.style.display = 'block';
                    return;
                }

                const grupo = data.grupo;
                let html = `<div style="border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;">
                    <div style="background:#f8fafc;padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;font-weight:600;font-size:.82rem;color:#475569;">
                        <i class="ri-bank-card-line me-1"></i>${escHtml(grupo.plan_nombre)}
                    </div>
                    <div style="padding:.75rem;">`;

                if (!grupo.cuotas.length) {
                    html += '<p style="color:#16a34a;font-size:.82rem;margin:0;"><i class="ri-checkbox-circle-line me-1"></i>Todas las cuotas están al día. No hay pagos pendientes.</p>';
                } else {
                    grupo.cuotas.forEach(c => {
                        const estadoColor = c.estado === 'pagado' ? '#16a34a' : c.estado === 'vencido' ? '#dc2626' : '#f59e0b';
                        html += `<label style="display:flex;align-items:center;gap:.75rem;padding:.5rem .25rem;border-bottom:1px solid #f8fafc;cursor:pointer;">
                            <input type="checkbox" name="cuotas[]" value="${c.id}" style="width:15px;height:15px;accent-color:#9a4904;flex-shrink:0;">
                            <div style="flex:1;">
                                <div style="font-size:.83rem;font-weight:500;color:#1e293b;">${escHtml(c.nombre)} #${c.n_cuota}</div>
                                <div style="font-size:.72rem;color:#64748b;">Bs ${c.monto_bs} · Pendiente Bs ${c.pago_pendiente_bs} · Vence: ${c.fecha_vencimiento ?? '—'}</div>
                            </div>
                            <span style="font-size:.7rem;font-weight:600;color:${estadoColor};background:${estadoColor}1a;padding:.15rem .45rem;border-radius:4px;">${escHtml(c.estado)}</span>
                        </label>`;
                    });
                }

                html += '</div></div>';
                cuotasContainer.innerHTML = html;
                cuotasContainer.style.display = 'block';
            })
            .catch(() => {
                cuotasLoading.style.display = 'none';
                cuotasContainer.innerHTML = '<p class="text-muted" style="font-size:.82rem;">Error al cargar cuotas.</p>';
                cuotasContainer.style.display = 'block';
            });
    }

    // Event delegation — funciona aunque el botón se genere dinámicamente
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-subir-comprobante');
        if (!btn) return;
        abrirModalComprobante(
            btn.dataset.inscripcionId,
            btn.dataset.nombre,
            btn.dataset.programa,
            btn.dataset.plan
        );
    });

    const btnEnviar = document.getElementById('btnEnviarComprobante');
    if (btnEnviar) {
        btnEnviar.addEventListener('click', function () {
            if (!compInscripcionId) return;

            const archivo = document.getElementById('compArchivo').files[0];
            if (!archivo) { mostrarToast('error', 'Debes seleccionar un archivo.'); return; }

            const cuotasChecked = [...document.querySelectorAll('#compCuotasContainer input[name="cuotas[]"]:checked')];
            if (!cuotasChecked.length) { mostrarToast('error', 'Selecciona al menos una cuota.'); return; }

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('inscripcion_id', compInscripcionId);
            formData.append('archivo', archivo);
            formData.append('observaciones', document.getElementById('compObservaciones').value);
            cuotasChecked.forEach(cb => formData.append('cuotas[]', cb.value));

            btnEnviar.disabled = true;
            btnEnviar.innerHTML = '<i class="ri-loader-4-line me-1"></i>Enviando...';

            fetch('{{ route("admin.profile.marketing.comprobante.subir") }}', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    btnEnviar.disabled = false;
                    btnEnviar.innerHTML = '<i class="ri-upload-cloud-line me-1"></i> Enviar Comprobante';
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('modalComprobante'))?.hide();
                        mostrarToast('success', data.mensaje || 'Comprobante enviado correctamente.');
                        loadInscritos();
                    } else {
                        mostrarToast('error', data.message || 'Error al enviar el comprobante.');
                    }
                })
                .catch(() => {
                    btnEnviar.disabled = false;
                    btnEnviar.innerHTML = '<i class="ri-upload-cloud-line me-1"></i> Enviar Comprobante';
                    mostrarToast('error', 'Error de conexión.');
                });
        });
    }

    const refreshInscritosBtn = document.getElementById('refreshInscritos');
    if (refreshInscritosBtn) refreshInscritosBtn.addEventListener('click', loadInscritos);

    document.querySelectorAll('[data-bs-target="#marketing"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', loadInscritos);
    });

    if (document.getElementById('marketing')?.classList.contains('active')) {
        loadInscritos();
    }

    /* ══════════════════════════════════════════════════
       CAMBIAR PRE-INSCRITO A INSCRITO
    ══════════════════════════════════════════════════ */
    let mktCambiarInscripcionId = null;
    let mktCambiarOfertaId      = null;

    const mktRoutePlanes  = '{{ url("admin/profile/marketing/oferta") }}';
    const mktRouteCambiar = '{{ url("admin/profile/marketing/inscripcion") }}';
    const mktCsrf         = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    const MESES_MKT = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                       'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

    /* Devuelve el día ajustado al último día válido del mes dado */
    function mktClampDay(year, month, day) {
        const maxDay = new Date(year, month, 0).getDate(); // día 0 del mes siguiente = último día del mes actual
        return Math.min(day, maxDay);
    }

    /* ── Abrir modal al hacer clic en el botón ── */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.mkt-btn-cambiar-inscrito');
        if (!btn) return;

        mktCambiarInscripcionId = btn.dataset.inscripcionId;
        mktCambiarOfertaId      = btn.dataset.ofertaId;

        document.getElementById('mktCambiarEstNombre').textContent = btn.dataset.estudiante || '—';
        document.getElementById('mktCambiarEstCi').textContent     = btn.dataset.ci || '—';

        const sel = document.getElementById('mktCambiarPlanSelect');
        sel.innerHTML = '<option value="">Cargando planes...</option>';
        mktOcultarDetalle();
        mktActualizarBotonConfirmar();

        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCambiarAInscritoMkt')).show();

        fetch(`${mktRoutePlanes}/${mktCambiarOfertaId}/planes`, { headers: { Accept: 'application/json' } })
            .then(r => r.json())
            .then(data => {
                if (!data.success || !data.planes.length) {
                    sel.innerHTML = '<option value="">— Sin planes configurados —</option>';
                    return;
                }
                let opts = '<option value="">— Seleccionar plan de pago —</option>';
                data.planes.forEach(p => {
                    const seleccionado = String(p.id) === String(btn.dataset.planPagoId) ? 'selected' : '';
                    opts += `<option value="${p.id}" ${seleccionado}>${escHtml(p.nombre)}</option>`;
                });
                sel.innerHTML = opts;
                if (sel.value) mktCargarDetallePlan(sel.value);
            })
            .catch(() => { sel.innerHTML = '<option value="">— Error al cargar —</option>'; });
    });

    /* ── Cambio de plan → recargar detalle ── */
    document.getElementById('mktCambiarPlanSelect')?.addEventListener('change', function () {
        mktOcultarDetalle();
        mktActualizarBotonConfirmar();
        if (this.value) mktCargarDetallePlan(this.value);
    });

    /* ── Cargar detalle del plan desde la API ── */
    function mktCargarDetallePlan(planId) {
        const cuerpo = document.getElementById('mktCambiarPlanDetalleBody');
        const detalle = document.getElementById('mktCambiarPlanDetalle');
        detalle.style.display = 'block';
        cuerpo.innerHTML = '<div class="text-center py-3"><span class="spinner-border spinner-border-sm" style="color:#f59e0b;"></span> Cargando detalle...</div>';

        fetch(`${mktRoutePlanes}/${mktCambiarOfertaId}/plan/${planId}/detalle`, { headers: { Accept: 'application/json' } })
            .then(r => r.json())
            .then(data => {
                if (!data.success || !data.data.length) {
                    cuerpo.innerHTML = '<p class="text-muted p-2" style="font-size:.82rem;">No hay conceptos configurados para este plan.</p>';
                    return;
                }
                mktRenderConceptos(data.data);
            })
            .catch(() => {
                cuerpo.innerHTML = '<p class="text-danger p-2" style="font-size:.82rem;">Error al cargar el detalle del plan.</p>';
            });
    }

    /* ── Renderizar tabla editable de conceptos/cuotas ── */
    function mktRenderConceptos(data) {
        const cuerpo = document.getElementById('mktCambiarPlanDetalleBody');
        const mesesOpts = MESES_MKT.map((m, i) => `<option value="${i+1}">${m}</option>`).join('');
        let html = '';

        data.forEach(function (item, idx) {
            const pagoBsOriginal = parseFloat(item.pago_bs);
            const esUnaCuota    = item.n_cuotas === 1;
            const disabledDiv   = esUnaCuota ? 'disabled' : '';

            html += `<div class="mb-3 p-3 rounded" style="background:#f8fafc;border:1px solid #e2e8f0;">`;

            /* Cabecera del concepto */
            html += `<div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold" style="color:#334155;">${escHtml(item.concepto)}</span>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge mkt-badge-total-concepto" style="background:rgba(16,185,129,.15);color:#059669;font-size:.8rem;" data-original="${pagoBsOriginal}">Total: Bs. ${pagoBsOriginal.toFixed(2)}</span>
                    <span class="badge mkt-badge-diferencia" style="font-size:.7rem;"></span>
                </div>
            </div>`;

            /* Herramienta dividir cuotas */
            html += `<div class="mb-2 p-2 rounded d-flex align-items-center gap-2 flex-wrap" style="background:#f0f9ff;border:1px solid #bae6fd;">
                <input type="number" class="form-control form-control-sm" id="mkt-monto-pagar-${idx}" placeholder="Monto por cuota" min="0" step="1" style="width:130px;" ${disabledDiv}>
                <button type="button" class="btn btn-sm btn-info text-white mkt-btn-dividir" data-idx="${idx}" data-n="${item.n_cuotas}" ${disabledDiv}>
                    <i class="ri-divide-line"></i> Aplicar a ${item.n_cuotas} cuotas
                </button>`;
            if (!esUnaCuota) {
                html += `<button type="button" class="btn btn-sm btn-secondary mkt-btn-invertir" data-idx="${idx}" title="Invertir orden de cuotas">
                    <i class="ri-swap-line"></i> Invertir
                </button>`;
            } else {
                html += `<small class="text-muted ms-2"><i class="ri-information-line"></i> Cuota única</small>`;
            }
            html += `</div>`;

            /* Herramienta fechas */
            html += `<div class="mb-2 p-2 rounded d-flex align-items-center gap-3 flex-wrap" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                <div class="d-flex align-items-center gap-2">
                    <label style="font-size:.74rem;font-weight:600;color:#15803d;white-space:nowrap;margin:0;">Día de pago:</label>
                    <input type="number" class="form-control form-control-sm" id="mkt-dia-venc-${idx}" min="1" max="31" placeholder="1–31" style="width:72px;font-size:.8rem;">
                    <button type="button" class="btn btn-sm mkt-btn-aplicar-dia" data-idx="${idx}" style="background:#15803d;color:#fff;font-size:.73rem;padding:.22rem .65rem;">
                        <i class="ri-calendar-check-line"></i> Aplicar
                    </button>
                </div>
                <div style="width:1px;height:26px;background:#86efac;"></div>
                <div class="d-flex align-items-center gap-2">
                    <label style="font-size:.74rem;font-weight:600;color:#15803d;white-space:nowrap;margin:0;">Mes inicio:</label>
                    <select class="form-select form-select-sm" id="mkt-mes-inicio-${idx}" style="width:135px;font-size:.78rem;">
                        <option value="">— Mes —</option>${mesesOpts}
                    </select>
                    <button type="button" class="btn btn-sm mkt-btn-aplicar-mes" data-idx="${idx}" data-n="${item.n_cuotas}" style="background:#15803d;color:#fff;font-size:.73rem;padding:.22rem .65rem;">
                        <i class="ri-calendar-line"></i> Aplicar
                    </button>
                </div>
            </div>`;

            /* Tabla de cuotas */
            html += `<table class="table table-sm mb-2" style="font-size:.8rem;">
                <thead style="background:#f1f5f9;"><tr><th>#</th><th>Monto (Bs)</th><th>Fecha Vencimiento</th></tr></thead>
                <tbody>`;
            item.cuotas.forEach(function (cuota, ci) {
                const fechaVal  = cuota.fecha_vencimiento || '';
                const disabled  = esUnaCuota ? 'disabled' : '';
                html += `<tr>
                    <td>Cuota ${cuota.n_cuota}</td>
                    <td><input type="number" class="form-control form-control-sm mkt-cuota-monto" data-idx="${idx}" data-ci="${ci}" value="${cuota.monto_bs}" min="0" step="1" style="width:100px;" ${disabled}></td>
                    <td><input type="date" class="form-control form-control-sm mkt-cuota-fecha" data-idx="${idx}" data-ci="${ci}" value="${fechaVal}" style="width:150px;"></td>
                </tr>`;
            });
            html += `</tbody></table>`;

            if (esUnaCuota) {
                html += `<small class="text-muted"><i class="ri-information-line"></i> Cuota única — monto no modificable</small>`;
            }
            html += `</div>`;
        });

        html += `<div class="text-end mt-2"><div id="mktMensajeValidacion" style="display:inline-block;"></div></div>`;

        cuerpo.innerHTML = html;

        /* ── Eventos: cambio de monto → recalcular ── */
        cuerpo.querySelectorAll('.mkt-cuota-monto').forEach(inp => {
            inp.addEventListener('change', () => mktRecalcularConcepto(inp.dataset.idx));
        });

        /* ── Dividir cuotas ── */
        cuerpo.querySelectorAll('.mkt-btn-dividir').forEach(btn => {
            btn.addEventListener('click', function () {
                const idx      = this.dataset.idx;
                const nCuotas  = parseInt(this.dataset.n);
                const monto    = parseFloat(document.getElementById(`mkt-monto-pagar-${idx}`).value) || 0;
                const totalOrig= parseFloat(cuerpo.querySelector(`.mkt-badge-total-concepto[data-idx-group="${idx}"]`)?.dataset.original
                                 ?? cuerpo.querySelectorAll('.mkt-badge-total-concepto')[idx]?.dataset.original) || 0;
                if (monto <= 0) { mostrarToast('warning', 'Ingrese un monto mayor a 0.'); return; }
                const ultima = totalOrig - (monto * (nCuotas - 1));
                if (ultima <= 0) { mostrarToast('warning', `El monto genera una última cuota ≤ 0. Total: Bs. ${totalOrig.toFixed(2)}`); return; }
                cuerpo.querySelectorAll(`.mkt-cuota-monto[data-idx="${idx}"]`).forEach((inp, i) => {
                    inp.value = (i === nCuotas - 1) ? ultima : monto;
                });
                document.getElementById(`mkt-monto-pagar-${idx}`).value = '';
                mktRecalcularConcepto(idx);
                mostrarToast('success', `Monto distribuido en ${nCuotas} cuota${nCuotas !== 1 ? 's' : ''}.`);
            });
        });

        /* ── Invertir cuotas ── */
        cuerpo.querySelectorAll('.mkt-btn-invertir').forEach(btn => {
            btn.addEventListener('click', function () {
                const idx    = this.dataset.idx;
                const inputs = [...cuerpo.querySelectorAll(`.mkt-cuota-monto[data-idx="${idx}"]`)];
                const vals   = inputs.map(i => i.value).reverse();
                inputs.forEach((i, j) => i.value = vals[j]);
                mktRecalcularConcepto(idx);
                mostrarToast('success', 'Orden de cuotas invertido.');
            });
        });

        /* ── Aplicar día de pago ── */
        cuerpo.querySelectorAll('.mkt-btn-aplicar-dia').forEach(btn => {
            btn.addEventListener('click', function () {
                const idx = this.dataset.idx;
                const dia = parseInt(document.getElementById(`mkt-dia-venc-${idx}`).value);
                if (!dia || dia < 1 || dia > 31) { mostrarToast('warning', 'Ingrese un día válido entre 1 y 31.'); return; }
                let aplicados = 0;
                cuerpo.querySelectorAll(`.mkt-cuota-fecha[data-idx="${idx}"]`).forEach(inp => {
                    if (inp.value) {
                        const parts = inp.value.split('-');
                        if (parts.length === 3) {
                            const year  = parseInt(parts[0]);
                            const month = parseInt(parts[1]);
                            const diaReal = mktClampDay(year, month, dia);
                            parts[2] = String(diaReal).padStart(2, '0');
                            inp.value = parts.join('-');
                            aplicados++;
                        }
                    }
                });
                mostrarToast(aplicados ? 'success' : 'warning',
                    aplicados ? `Día ${dia} aplicado a ${aplicados} fecha${aplicados !== 1 ? 's' : ''}.` : 'No hay fechas con valores para modificar.');
            });
        });

        /* ── Aplicar mes de inicio ── */
        cuerpo.querySelectorAll('.mkt-btn-aplicar-mes').forEach(btn => {
            btn.addEventListener('click', function () {
                const idx      = this.dataset.idx;
                const nCuotas  = parseInt(this.dataset.n) || 1;
                const mesInicio= parseInt(document.getElementById(`mkt-mes-inicio-${idx}`).value);
                if (!mesInicio) { mostrarToast('warning', 'Seleccione un mes de inicio.'); return; }
                const fechas  = [...cuerpo.querySelectorAll(`.mkt-cuota-fecha[data-idx="${idx}"]`)];
                let dia = 1;
                const diaInput = parseInt(document.getElementById(`mkt-dia-venc-${idx}`).value);
                if (diaInput >= 1 && diaInput <= 31) { dia = diaInput; }
                else if (fechas[0]?.value) { const p = fechas[0].value.split('-'); dia = parseInt(p[2]) || 1; }
                let anioBase = new Date().getFullYear();
                for (const f of fechas) { if (f.value) { anioBase = parseInt(f.value.split('-')[0]); break; } }
                fechas.forEach((inp, ci) => {
                    let mes  = mesInicio + ci;
                    let anio = anioBase + Math.floor((mes - 1) / 12);
                    mes = ((mes - 1) % 12) + 1;
                    const diaReal = mktClampDay(anio, mes, dia);
                    inp.value = `${anio}-${String(mes).padStart(2,'0')}-${String(diaReal).padStart(2,'0')}`;
                });
                mostrarToast('success', `Fechas redistribuidas desde ${MESES_MKT[mesInicio-1]} (${nCuotas} cuota${nCuotas!==1?'s':''}).`);
            });
        });

        /* Recalcular todos los totales al iniciar */
        data.forEach((_, idx) => mktRecalcularConcepto(idx));
    }

    /* ── Recalcular total de un concepto ── */
    function mktRecalcularConcepto(idx) {
        const cuerpo = document.getElementById('mktCambiarPlanDetalleBody');
        let total = 0;
        cuerpo.querySelectorAll(`.mkt-cuota-monto[data-idx="${idx}"]`).forEach(inp => {
            total += parseFloat(inp.value) || 0;
        });
        const badges = cuerpo.querySelectorAll('.mkt-badge-total-concepto');
        const badge  = badges[parseInt(idx)];
        if (!badge) return;
        const original   = parseFloat(badge.dataset.original) || 0;
        const diferencia = total - original;
        badge.textContent = `Total: Bs. ${total.toFixed(2)}`;
        const diffBadge = cuerpo.querySelectorAll('.mkt-badge-diferencia')[parseInt(idx)];
        if (diffBadge) {
            if (diferencia === 0) {
                diffBadge.innerHTML = '<span style="background:rgba(34,197,94,.15);color:#16a34a;padding:.15rem .4rem;border-radius:4px;"><i class="ri-check-line"></i> Correcto</span>';
            } else if (diferencia > 0) {
                diffBadge.innerHTML = `<span style="background:rgba(239,68,68,.15);color:#dc2626;padding:.15rem .4rem;border-radius:4px;"><i class="ri-error-warning-line"></i> Exceso: Bs. ${diferencia.toFixed(2)}</span>`;
            } else {
                diffBadge.innerHTML = `<span style="background:rgba(245,158,11,.15);color:#d97706;padding:.15rem .4rem;border-radius:4px;"><i class="ri-alert-line"></i> Falta: Bs. ${Math.abs(diferencia).toFixed(2)}</span>`;
            }
        }
        mktActualizarBotonConfirmar();
    }

    /* ── Habilitar/deshabilitar botón confirmar según validación ── */
    function mktActualizarBotonConfirmar() {
        const btnEl  = document.getElementById('mktBtnConfirmarCambiar');
        const msgEl  = document.getElementById('mktMensajeValidacion');
        const cuerpo = document.getElementById('mktCambiarPlanDetalleBody');
        if (!btnEl) return;

        const badges = cuerpo ? [...cuerpo.querySelectorAll('.mkt-badge-total-concepto')] : [];
        let hayErrores = false;
        badges.forEach(b => {
            const original = parseFloat(b.dataset.original) || 0;
            const actual   = parseFloat(b.textContent.replace('Total: Bs. ', '')) || 0;
            if (Math.abs(actual - original) > 0.01) hayErrores = true;
        });

        if (hayErrores) {
            btnEl.disabled = true;
            if (msgEl) msgEl.innerHTML = '<span style="color:#dc2626;font-size:.78rem;"><i class="ri-alert-line"></i> Corrige los montos antes de guardar</span>';
        } else {
            btnEl.disabled = badges.length === 0;
            if (msgEl && badges.length > 0) msgEl.innerHTML = '<span style="color:#16a34a;font-size:.78rem;"><i class="ri-check-line"></i> Los montos coinciden</span>';
            else if (msgEl) msgEl.innerHTML = '';
        }
    }

    function mktOcultarDetalle() {
        const detalle = document.getElementById('mktCambiarPlanDetalle');
        const cuerpo  = document.getElementById('mktCambiarPlanDetalleBody');
        if (detalle) detalle.style.display = 'none';
        if (cuerpo)  cuerpo.innerHTML = '';
    }

    /* ── Confirmar inscripción ── */
    document.getElementById('mktBtnConfirmarCambiar')?.addEventListener('click', function () {
        const planId = document.getElementById('mktCambiarPlanSelect').value;
        if (!planId) { mostrarToast('warning', 'Debe seleccionar un plan de pago.'); return; }

        const planNombre   = document.getElementById('mktCambiarPlanSelect').options[document.getElementById('mktCambiarPlanSelect').selectedIndex].text;
        const estNombre    = document.getElementById('mktCambiarEstNombre').textContent;
        const cuerpo = document.getElementById('mktCambiarPlanDetalleBody');
        const cuotasPersonalizadas = [];
        cuerpo.querySelectorAll('.mkt-cuota-monto').forEach(inp => {
            const fecha = cuerpo.querySelector(`.mkt-cuota-fecha[data-idx="${inp.dataset.idx}"][data-ci="${inp.dataset.ci}"]`);
            cuotasPersonalizadas.push({
                concepto_idx: parseInt(inp.dataset.idx),
                cuota_idx:    parseInt(inp.dataset.ci),
                monto:        parseFloat(inp.value) || 0,
                fecha:        fecha?.value || null,
            });
        });

        const btnEl = this;
        btnEl.disabled = true;
        btnEl.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Procesando...';

        fetch(`${mktRouteCambiar}/${mktCambiarInscripcionId}/cambiar-a-inscrito`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': mktCsrf },
            body: JSON.stringify({
                planes_pago_id:        planId,
                cuotas_personalizadas: cuotasPersonalizadas.length ? JSON.stringify(cuotasPersonalizadas) : null,
            }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalCambiarAInscritoMkt'))?.hide();
                mostrarToast('success', data.message || 'Inscripción completada correctamente.');
                loadMarketingData();
                goToMktPage(mktCurrentPage);
                // Abrir modal de comprobante de pago
                const programa = data.programa_nombre || '';
                abrirModalComprobante(mktCambiarInscripcionId, estNombre, programa, planNombre);
            } else {
                mostrarToast('error', data.message || 'Error al procesar.');
            }
        })
        .catch(() => { mostrarToast('error', 'Error de conexión.'); })
        .finally(() => {
            btnEl.disabled = false;
            btnEl.innerHTML = '<i class="ri-user-check-line me-1"></i>Confirmar Inscripción';
        });
    });

    @endif

});
</script>
