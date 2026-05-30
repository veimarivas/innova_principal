{{-- Usado con variable $prefix: 'SinPlan' o 'ConPlan' --}}
<div class="row g-4 align-items-start">

    {{-- QR --}}
    <div class="col-md-4 text-center">
        <div style="background:#fff;border-radius:12px;padding:.9rem;display:inline-block;box-shadow:0 4px 20px rgba(0,0,0,.15);">
            <div id="qrContainer{{ $prefix }}"></div>
        </div>
        <p style="font-size:.67rem;color:#64748b;margin-top:.5rem;">Escanea para abrir el formulario</p>
    </div>

    {{-- Enlace y acciones --}}
    <div class="col-md-8">
        <label style="font-size:.7rem;font-weight:600;color:#64748b;letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:.4rem;">
            Enlace personalizado
        </label>
        <div style="display:flex;gap:.45rem;margin-bottom:.9rem;">
            <input type="text" id="urlInput{{ $prefix }}" readonly
                style="flex:1;background:rgba(0,0,0,.2);border:1px solid var(--prof-border);border-radius:8px;padding:.55rem .85rem;color:var(--prof-text);font-size:.76rem;font-family:monospace;outline:none;">
            <button type="button" onclick="copiarEnlace('{{ $prefix }}')"
                id="btnCopiar{{ $prefix }}"
                style="padding:.55rem .85rem;background:#9a4904;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:.8rem;white-space:nowrap;display:flex;align-items:center;gap:.3rem;transition:background .2s;">
                <i class="ri-file-copy-line"></i><span id="lblCopiar{{ $prefix }}">Copiar</span>
            </button>
        </div>

        <div style="background:rgba(154,73,4,.07);border:1px solid rgba(154,73,4,.18);border-radius:9px;padding:.85rem 1rem;margin-bottom:.85rem;">
            <p style="font-size:.75rem;color:#9a4904;font-weight:600;margin-bottom:.35rem;">
                <i class="ri-information-line me-1"></i> ¿Cómo funciona?
            </p>
            <ul style="font-size:.73rem;color:#64748b;padding-left:1rem;line-height:1.75;margin:0;">
                <li>Comparte el enlace o QR con el prospecto.</li>
                <li>El formulario muestra la info del programa y tus datos.</li>
                <li>Las solicitudes quedan registradas como <strong style="color:var(--prof-text);">Pre-Inscrito</strong> automáticamente.</li>
            </ul>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a id="btnAbrir{{ $prefix }}" href="#" target="_blank"
                style="display:inline-flex;align-items:center;gap:.35rem;padding:.5rem .95rem;background:transparent;border:1px solid #9a4904;color:#9a4904;border-radius:8px;font-size:.78rem;font-weight:600;text-decoration:none;transition:background .2s;">
                <i class="ri-external-link-line"></i> Abrir
            </a>
            <button type="button" onclick="descargarQr('{{ $prefix }}')"
                style="display:inline-flex;align-items:center;gap:.35rem;padding:.5rem .95rem;background:transparent;border:1px solid var(--prof-border);color:var(--prof-text);border-radius:8px;font-size:.78rem;font-weight:600;cursor:pointer;transition:background .2s;">
                <i class="ri-download-2-line"></i> Descargar QR
            </button>
        </div>
    </div>

</div>
