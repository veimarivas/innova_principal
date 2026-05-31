{{-- Usado con variable $prefix: 'SinPlan' o 'ConPlan' --}}
<div class="enl-display">

    {{-- QR + Enlace en fila --}}
    <div class="enl-display-grid">

        {{-- QR Card --}}
        <div class="enl-qr-card">
            <div class="enl-qr-label">
                <i class="ri-qr-code-line"></i> Código QR
            </div>
            <div class="enl-qr-wrap">
                <div id="qrContainer{{ $prefix }}" class="enl-qr-container"></div>
            </div>
            <p class="enl-qr-hint">
                <i class="ri-smartphone-line"></i> Escanea para abrir el formulario
            </p>
            <button type="button" onclick="descargarQr('{{ $prefix }}')" class="enl-btn-download">
                <i class="ri-download-2-line"></i> Descargar QR
            </button>
        </div>

        {{-- Enlace + Acciones --}}
        <div class="enl-link-section">

            {{-- URL --}}
            <div class="enl-url-block">
                <div class="enl-url-label">
                    <i class="ri-links-line"></i> Enlace personalizado
                </div>
                <div class="enl-url-group">
                    <input type="text" id="urlInput{{ $prefix }}" readonly
                        class="enl-url-input" placeholder="Generando enlace...">
                    <button type="button" onclick="copiarEnlace('{{ $prefix }}')"
                        id="btnCopiar{{ $prefix }}" class="enl-btn-copy">
                        <i class="ri-file-copy-line"></i>
                        <span id="lblCopiar{{ $prefix }}">Copiar</span>
                    </button>
                </div>
            </div>

            {{-- Cómo funciona --}}
            <div class="enl-how-it-works">
                <div class="enl-how-title">
                    <div class="enl-how-icon"><i class="ri-information-line"></i></div>
                    <span>¿Cómo funciona?</span>
                </div>
                <ul class="enl-how-list">
                    <li>
                        <span class="enl-step">1</span>
                        Comparte el enlace o QR con el prospecto.
                    </li>
                    <li>
                        <span class="enl-step">2</span>
                        El formulario muestra la info del programa y tus datos de contacto.
                    </li>
                    <li>
                        <span class="enl-step">3</span>
                        Las solicitudes quedan registradas como <strong>Pre-Inscrito</strong> automáticamente.
                    </li>
                </ul>
            </div>

            {{-- Botón abrir --}}
            <a id="btnAbrir{{ $prefix }}" href="#" target="_blank" class="enl-btn-open">
                <i class="ri-external-link-line"></i>
                Abrir formulario en nueva pestaña
            </a>

        </div>

    </div>

</div>

<style>
    /* ── Display grid ── */
    .enl-display-grid {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 1.25rem;
        align-items: start;
    }

    /* ── QR Card ── */
    .enl-qr-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    .enl-qr-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
        align-self: flex-start;
        width: 100%;
    }

    .enl-qr-label i { color: #9a4904; }

    .enl-qr-wrap {
        background: white;
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        flex-shrink: 0;
        line-height: 0;
    }

    .enl-qr-container {
        width: 140px;
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .enl-qr-container canvas,
    .enl-qr-container img {
        display: block !important;
        max-width: 140px !important;
        max-height: 140px !important;
        width: 140px !important;
        height: 140px !important;
    }

    .enl-qr-hint {
        font-size: 0.68rem;
        color: #94a3b8;
        margin: 0;
        text-align: center;
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .enl-btn-download {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 7px 12px;
        background: white;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .enl-btn-download:hover {
        border-color: #9a4904;
        color: #9a4904;
        background: #fef3e2;
    }

    /* ── Link section ── */
    .enl-link-section {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* URL block */
    .enl-url-block {}

    .enl-url-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 6px;
    }

    .enl-url-label i { color: #9a4904; }

    .enl-url-group {
        display: flex;
        gap: 6px;
    }

    .enl-url-input {
        flex: 1;
        padding: 9px 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.75rem;
        font-family: 'Courier New', monospace;
        color: #334155;
        background: #f8fafc;
        outline: none;
        min-width: 0;
        transition: border-color 0.2s;
    }

    .enl-url-input:focus { border-color: #9a4904; }

    .enl-btn-copy {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 9px 14px;
        background: linear-gradient(135deg, #9a4904, #df6a04);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
        font-family: 'Plus Jakarta Sans', sans-serif;
        box-shadow: 0 2px 8px rgba(154,73,4,0.25);
        flex-shrink: 0;
    }

    .enl-btn-copy:hover {
        background: linear-gradient(135deg, #743c04, #9a4904);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(154,73,4,0.35);
    }

    /* How it works */
    .enl-how-it-works {
        background: rgba(154,73,4,0.05);
        border: 1px solid rgba(154,73,4,0.15);
        border-radius: 10px;
        padding: 12px 14px;
    }

    .enl-how-title {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 0.78rem;
        font-weight: 700;
        color: #9a4904;
        margin-bottom: 10px;
    }

    .enl-how-icon {
        width: 22px; height: 22px;
        background: rgba(154,73,4,0.12);
        border-radius: 5px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem;
        color: #9a4904;
        flex-shrink: 0;
    }

    .enl-how-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .enl-how-list li {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 0.76rem;
        color: #475569;
        line-height: 1.5;
    }

    .enl-how-list li strong { color: #1e293b; font-weight: 700; }

    .enl-step {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        min-width: 18px;
        background: linear-gradient(135deg, #9a4904, #df6a04);
        color: white;
        border-radius: 50%;
        font-size: 0.65rem;
        font-weight: 700;
        margin-top: 1px;
    }

    /* Open button */
    .enl-btn-open {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 9px 16px;
        background: transparent;
        border: 1.5px solid #9a4904;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #9a4904;
        text-decoration: none;
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
        width: 100%;
        text-align: center;
    }

    .enl-btn-open:hover {
        background: #9a4904;
        color: white;
        box-shadow: 0 4px 12px rgba(154,73,4,0.3);
    }

    /* ── Responsive ── */
    @media (max-width: 576px) {
        .enl-display-grid {
            grid-template-columns: 1fr;
        }
        .enl-qr-card {
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
        }
    }
</style>
