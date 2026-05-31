<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

    :root {
        --prof-primary:       #9a4904;
        --prof-primary-dark:  #743c04;
        --prof-primary-light: #fef3e2;
        --prof-accent:        #fc7b04;
        --prof-accent-light:  #fff7ed;
        --prof-surface:       #f8fafc;
        --prof-border:        #e2e8f0;
        --prof-text:          #1e293b;
        --prof-text-muted:    #64748b;
        --prof-success:       #10b981;
        --prof-success-light: #ecfdf5;
        --prof-info:          #0891b2;
        --prof-info-light:    #ecfeff;
        --prof-danger:        #ef4444;
        --prof-danger-light:  #fef2f2;
        --prof-warning:       #f59e0b;
        --prof-warning-light: #fffbeb;
        --radius-sm:  8px;
        --radius-md:  12px;
        --radius-lg:  16px;
        --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md:  0 4px 8px -2px rgba(0,0,0,0.08), 0 2px 4px -2px rgba(0,0,0,0.04);
        --shadow-lg:  0 10px 25px -4px rgba(0,0,0,0.1), 0 4px 8px -4px rgba(0,0,0,0.06);
    }

    .profile-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--prof-text);
        animation: profFadeIn 0.45s ease-out;
    }

    @keyframes profFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Header ── */
    .profile-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding: 20px 28px;
        background: linear-gradient(135deg, #9a4904 0%, #df6a04 100%);
        border-radius: var(--radius-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: -40%; right: -5%;
        width: 260px; height: 260px;
        background: radial-gradient(circle, rgba(255,255,255,0.10) 0%, transparent 70%);
        border-radius: 50%;
    }

    .profile-header::after {
        content: '';
        position: absolute;
        bottom: -30%; left: 20%;
        width: 180px; height: 180px;
        background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 70%);
        border-radius: 50%;
    }

    .profile-header h1 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.02em;
        position: relative; z-index: 1;
        color: white;
    }

    .profile-header p {
        margin: 4px 0 0;
        opacity: 0.85;
        font-size: 0.85rem;
        position: relative; z-index: 1;
        color: white;
    }

    .profile-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        position: relative; z-index: 1;
    }

    .profile-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(255,255,255,0.18);
        color: white;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.25);
    }

    /* ── Sidebar Card ── */
    .profile-sidebar-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .profile-sidebar-banner {
        height: 80px;
        background: linear-gradient(135deg, #9a4904 0%, #df6a04 100%);
        position: relative;
    }

    .profile-sidebar-banner::after {
        content: '';
        position: absolute;
        top: -30%; right: -10%;
        width: 160px; height: 160px;
        background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 70%);
        border-radius: 50%;
    }

    .profile-sidebar-body {
        text-align: center;
        padding: 0 20px 20px;
        margin-top: -48px;
        position: relative; z-index: 1;
    }

    .profile-avatar-wrapper {
        position: relative;
        display: inline-block;
    }

    .profile-avatar {
        width: 96px;
        height: 96px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: var(--shadow-md);
    }

    .profile-avatar-btn {
        position: absolute;
        bottom: 2px; right: 2px;
        width: 28px; height: 28px;
        border-radius: 50%;
        background: var(--prof-primary);
        color: white;
        border: 2px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .profile-avatar-btn:hover {
        background: var(--prof-primary-dark);
        transform: scale(1.1);
    }

    .profile-name {
        font-family: 'Outfit', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        margin: 12px 0 2px;
        color: var(--prof-text);
    }

    .profile-cargo {
        font-size: 0.82rem;
        color: var(--prof-primary);
        font-weight: 600;
        margin-bottom: 6px;
    }

    .profile-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        background: var(--prof-primary-light);
        color: var(--prof-primary);
        border: 1px solid rgba(154,73,4,0.2);
        margin-bottom: 14px;
    }

    .profile-mini-badges {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 6px;
        margin-bottom: 16px;
    }

    .profile-mini-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.68rem;
        font-weight: 600;
        background: var(--prof-surface);
        color: var(--prof-text-muted);
        border: 1px solid var(--prof-border);
    }

    /* Contact section */
    .profile-contact-section {
        border-top: 1px solid var(--prof-border);
        padding-top: 14px;
        text-align: left;
    }

    .profile-contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
    }

    .profile-contact-item + .profile-contact-item {
        border-top: 1px solid var(--prof-border);
    }

    .profile-contact-icon {
        width: 32px; height: 32px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .profile-contact-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--prof-text-muted);
        font-weight: 600;
    }

    .profile-contact-value {
        font-size: 0.82rem;
        font-weight: 500;
        color: var(--prof-text);
        max-width: 160px;
    }

    /* ── Quick Info Card ── */
    .quick-info-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .quick-info-header {
        padding: 12px 18px;
        background: var(--prof-surface);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--prof-text-muted);
    }

    .quick-info-header i { color: var(--prof-accent); }

    .quick-info-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 18px;
    }

    .quick-info-item + .quick-info-item { border-top: 1px solid var(--prof-border); }

    .quick-info-item .qi-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .quick-info-item .qi-left i { font-size: 0.85rem; color: var(--prof-primary); }
    .quick-info-item .qi-label  { font-size: 0.8rem; color: var(--prof-text-muted); }
    .quick-info-item .qi-value  { font-size: 0.82rem; font-weight: 700; font-family: 'Outfit', sans-serif; color: var(--prof-text); }

    /* ── Main Profile Card ── */
    .profile-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .profile-card-header {
        border-bottom: 1px solid var(--prof-border);
        background: white;
        padding: 0;
    }

    .profile-card-body { padding: 24px; }

    /* ── Navigation Tabs ── */
    .profile-tabs {
        display: flex;
        overflow-x: auto;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
        padding: 0 20px;
    }

    .profile-tabs::-webkit-scrollbar { display: none; }

    .profile-tab {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 14px 18px;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--prof-text-muted);
        border: none;
        background: none;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .profile-tab:hover:not(.active) {
        color: var(--prof-primary);
        border-bottom-color: rgba(154,73,4,0.2);
    }

    .profile-tab.active {
        color: var(--prof-primary);
        border-bottom-color: var(--prof-primary);
    }

    .profile-tab i { font-size: 1rem; }

    /* ── Data Cards (tab Personal) ── */
    .data-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        height: 100%;
    }

    .data-card-header {
        padding: 14px 18px;
        border-bottom: 1px solid var(--prof-border);
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--prof-surface);
    }

    .data-card-icon {
        width: 34px; height: 34px;
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.95rem;
    }

    .data-card-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0;
        color: var(--prof-text);
    }

    .data-card-body { padding: 0; }

    .data-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 18px;
    }

    .data-row + .data-row { border-top: 1px solid var(--prof-border); }

    .data-row-icon {
        width: 30px; height: 30px;
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.82rem;
        flex-shrink: 0;
    }

    .data-row-label {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--prof-text-muted);
        font-weight: 600;
    }

    .data-row-value {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--prof-text);
    }

    /* ── Password Card ── */
    .password-card, .tips-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .password-card-header, .tips-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--prof-border);
        background: var(--prof-surface);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .password-card-header-icon, .tips-card-header-icon {
        width: 38px; height: 38px;
        border-radius: var(--radius-sm);
        background: linear-gradient(135deg, #9a4904, #df6a04);
        display: flex; align-items: center; justify-content: center;
        color: white;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .password-card-header h6, .tips-card-header h6 {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 0.95rem;
        margin: 0;
        color: var(--prof-text);
    }

    .password-card-header p, .tips-card-header p {
        font-size: 0.78rem;
        color: var(--prof-text-muted);
        margin: 2px 0 0;
    }

    .password-card-body { padding: 24px; }

    .password-input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-input-group .input-icon {
        position: absolute;
        left: 12px;
        color: var(--prof-text-muted);
        font-size: 0.9rem;
        pointer-events: none;
        z-index: 2;
    }

    .password-input-group .form-control {
        padding-left: 36px;
        padding-right: 42px;
        border-color: var(--prof-border);
    }

    .password-input-group .form-control:focus {
        border-color: var(--prof-primary);
        box-shadow: 0 0 0 3px rgba(154,73,4,0.12);
    }

    .toggle-pw {
        position: absolute;
        right: 10px;
        background: none;
        border: none;
        color: var(--prof-text-muted);
        cursor: pointer;
        font-size: 0.9rem;
        padding: 2px 4px;
        z-index: 2;
    }

    .toggle-pw:hover { color: var(--prof-primary); }

    /* Strength bar */
    .pw-strength-bar {
        height: 4px;
        background: var(--prof-border);
        border-radius: 2px;
        overflow: hidden;
    }

    .pw-strength-fill {
        height: 100%;
        border-radius: 2px;
        transition: width 0.4s ease, background 0.4s ease;
    }

    .btn-update-password {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 24px;
        border-radius: var(--radius-sm);
        background: linear-gradient(135deg, #9a4904, #df6a04);
        color: white;
        font-size: 0.85rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .btn-update-password:hover {
        background: linear-gradient(135deg, #743c04, #9a4904);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(154,73,4,0.3);
    }

    /* Tips card */
    .tips-card-body { padding: 16px 20px; }

    .tip-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 8px 0;
    }

    .tip-item + .tip-item { border-top: 1px solid var(--prof-border); }

    .tip-icon {
        width: 30px; height: 30px;
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.82rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .tip-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--prof-text);
    }

    .tip-desc {
        font-size: 0.72rem;
        color: var(--prof-text-muted);
        margin: 0;
    }

    /* ── Upload Foto Modal ── */
    .upload-foto-preview {
        width: 120px; height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--prof-primary-light);
        box-shadow: var(--shadow-md);
    }

    .upload-foto-drop {
        border: 2px dashed var(--prof-border);
        border-radius: var(--radius-md);
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: var(--prof-surface);
    }

    .upload-foto-drop:hover {
        border-color: var(--prof-primary);
        background: var(--prof-primary-light);
    }

    .upload-foto-drop i {
        font-size: 2rem;
        color: var(--prof-primary);
        margin-bottom: 8px;
    }

    .btn-upload-foto {
        background: linear-gradient(135deg, #9a4904, #df6a04);
        color: white;
        border: none;
        padding: 9px 22px;
        border-radius: var(--radius-sm);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-upload-foto:hover {
        background: linear-gradient(135deg, #743c04, #9a4904);
    }

    .btn-upload-foto:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* ── Responsive ── */
    @media (max-width: 767px) {
        .profile-header { padding: 16px; }
        .profile-header h1 { font-size: 1.2rem; }
        .profile-card-body { padding: 16px; }
    }

    /* ═══════════════════════════════════════
       Carnet de Identificación (tab Personal)
    ═══════════════════════════════════════ */

    .ci-wrap {
        background: #fff;
        border: 1.5px solid var(--prof-border);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 6px 30px rgba(0,0,0,.07);
        position: relative;
    }

    /* Franja superior de color */
    .ci-stripe {
        height: 5px;
        background: linear-gradient(90deg, #391b04 0%, #9a4904 35%, #fc7b04 65%, #9a4904 100%);
    }

    .ci-body {
        display: grid;
        grid-template-columns: 220px 1fr 280px;
        gap: 0;
    }

    /* ── Columna izquierda ── */
    .ci-left {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .85rem;
        padding: 1.5rem 1rem 1.25rem;
        background: linear-gradient(180deg, #9a4904 0%, #5a2800 100%);
        position: relative;
    }

    .ci-foto-label {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: .65rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: rgba(255,255,255,.75);
    }

    .ci-foto {
        width: 140px;
        height: 175px;
        border-radius: 10px;
        border: 3px solid rgba(255,255,255,.45);
        background: rgba(255,255,255,.12);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
        box-shadow: 0 6px 20px rgba(0,0,0,.3);
        flex-shrink: 0;
    }

    .ci-foto img { width: 100%; height: 100%; object-fit: cover; }

    .ci-initials {
        font-family: 'Outfit', sans-serif;
        font-size: 2.6rem;
        font-weight: 800;
        color: rgba(255,255,255,.7);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }

    .ci-foto-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: rgba(0,0,0,.65);
        padding: .4rem;
        display: flex;
        justify-content: center;
        opacity: 0;
        transition: opacity .2s;
    }

    .ci-foto:hover .ci-foto-overlay { opacity: 1; }

    .ci-btn-foto {
        background: none;
        border: none;
        color: white;
        font-size: .72rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: .3rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .ci-quick-data {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: .28rem;
    }

    .ci-qd-item {
        display: grid;
        grid-template-columns: 14px auto 1fr;
        align-items: center;
        gap: .35rem;
        font-size: .7rem;
        padding: .28rem .4rem;
        background: rgba(255,255,255,.11);
        border-radius: 6px;
        color: rgba(255,255,255,.9);
    }

    .ci-qd-item i    { color: rgba(255,255,255,.65); font-size: .82rem; }
    .ci-qd-label     { color: rgba(255,255,255,.58); font-size: .63rem; text-transform: uppercase; letter-spacing: .03em; }
    .ci-qd-val       { color: #fff; font-weight: 600; text-align: right; font-size: .72rem; }

    /* ── Columna central ── */
    .ci-center {
        display: flex;
        flex-direction: column;
        padding: 1.4rem 1.25rem 1.25rem;
        border-right: 1.5px solid var(--prof-border);
    }

    .ci-nombre-wrap {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: .6rem;
        margin-bottom: 1rem;
        padding-bottom: .85rem;
        border-bottom: 1.5px solid var(--prof-border);
        flex-wrap: wrap;
    }

    .ci-nombre {
        font-family: 'Outfit', sans-serif;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--prof-text);
        line-height: 1.2;
    }

    .ci-cargo-line {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: .78rem;
        color: var(--prof-primary);
        font-weight: 600;
        margin-top: 4px;
    }

    .ci-estado-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: .25rem .65rem;
        border-radius: 20px;
        font-size: .68rem;
        font-weight: 700;
        white-space: nowrap;
        align-self: flex-start;
    }

    .ci-badge-activo   { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
    .ci-badge-inactivo { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

    .ci-section-title {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--prof-text-muted);
        margin-bottom: .65rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .ci-section-title i { color: var(--prof-accent); }

    .ci-datos-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: .65rem;
    }

    .ci-dato         { display: flex; flex-direction: column; gap: .1rem; }
    .ci-dato.ci-full { grid-column: 1 / -1; }

    .ci-label {
        font-size: .62rem;
        color: var(--prof-text-muted);
        text-transform: uppercase;
        letter-spacing: .05em;
        font-weight: 600;
    }

    .ci-value {
        font-size: .86rem;
        font-weight: 500;
        color: var(--prof-text);
    }

    /* ── Columna derecha ── */
    .ci-right {
        display: flex;
        flex-direction: column;
        padding: 1.25rem 1rem 1.25rem;
        background: var(--prof-surface);
        gap: .75rem;
    }

    .ci-right-header {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--prof-text-muted);
        padding-bottom: .7rem;
        border-bottom: 1.5px solid var(--prof-border);
    }

    .ci-right-header i { color: var(--prof-accent); font-size: 1rem; }

    .ci-account-list {
        display: flex;
        flex-direction: column;
        gap: .5rem;
        flex: 1;
    }

    .ci-acc-item {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .45rem .55rem;
        background: white;
        border: 1px solid var(--prof-border);
        border-radius: var(--radius-sm);
    }

    .ci-acc-icon {
        width: 28px; height: 28px;
        border-radius: 6px;
        background: var(--prof-primary-light);
        color: var(--prof-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .85rem;
        flex-shrink: 0;
    }

    .ci-acc-label {
        font-size: .62rem;
        color: var(--prof-text-muted);
        text-transform: uppercase;
        letter-spacing: .04em;
        font-weight: 600;
    }

    .ci-acc-value {
        font-size: .82rem;
        font-weight: 600;
        color: var(--prof-text);
        word-break: break-all;
    }

    .ci-btn-cambiar-foto {
        width: 100%;
        padding: .55rem;
        background: linear-gradient(135deg, #9a4904, #df6a04);
        color: white;
        border: none;
        border-radius: var(--radius-sm);
        font-size: .78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .ci-btn-cambiar-foto:hover {
        background: linear-gradient(135deg, #743c04, #9a4904);
        transform: translateY(-1px);
    }

    /* Franja inferior */
    .ci-bottom-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .5rem 1.25rem;
        background: linear-gradient(90deg, #391b04 0%, #9a4904 60%, #bc5404 100%);
        color: rgba(255,255,255,.8);
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .ci-bottom-bar i { margin-right: 4px; }

    /* ── Responsive ── */
    @media (max-width: 991px) {
        .ci-body {
            grid-template-columns: 180px 1fr;
            grid-template-rows: auto auto;
        }
        .ci-right {
            grid-column: 1 / -1;
            border-top: 1.5px solid var(--prof-border);
        }
    }

    @media (max-width: 575px) {
        .ci-body { grid-template-columns: 1fr; }
        .ci-left  { flex-direction: row; flex-wrap: wrap; align-items: flex-start; gap: .75rem; }
        .ci-foto  { width: 100px; height: 125px; }
        .ci-right { grid-column: 1; }
    }

    /* ═══════════════════════════════════════
       Marketing / Ofertas Activas tabs
    ═══════════════════════════════════════ */

    /* Filters card */
    .mkt-filters-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .mkt-filters-header {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 18px;
        background: var(--prof-surface);
        border-bottom: 1px solid var(--prof-border);
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--prof-text-muted);
    }

    .mkt-filters-header i { color: var(--prof-accent); font-size: 1rem; }

    .mkt-filters-body { padding: 14px 18px; }

    .mkt-label {
        display: block;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--prof-text-muted);
        margin-bottom: 5px;
    }

    .mkt-select {
        width: 100%;
        padding: 7px 10px;
        border: 1px solid var(--prof-border);
        border-radius: var(--radius-sm);
        font-size: 0.82rem;
        color: var(--prof-text);
        background: white;
        transition: border-color 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .mkt-select:focus {
        outline: none;
        border-color: var(--prof-primary);
        box-shadow: 0 0 0 3px rgba(154,73,4,0.1);
    }

    .mkt-search-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .mkt-search-group i {
        position: absolute;
        left: 10px;
        color: var(--prof-text-muted);
        font-size: 0.9rem;
        pointer-events: none;
    }

    .mkt-search-input {
        width: 100%;
        padding: 7px 10px 7px 32px;
        border: 1px solid var(--prof-border);
        border-radius: var(--radius-sm);
        font-size: 0.82rem;
        color: var(--prof-text);
        background: white;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: border-color 0.2s;
    }

    .mkt-search-input:focus {
        outline: none;
        border-color: var(--prof-primary);
        box-shadow: 0 0 0 3px rgba(154,73,4,0.1);
    }

    .mkt-btn-filter {
        padding: 7px 14px;
        background: linear-gradient(135deg, #9a4904, #df6a04);
        color: white;
        border: none;
        border-radius: var(--radius-sm);
        font-size: 0.82rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mkt-btn-filter:hover {
        background: linear-gradient(135deg, #743c04, #9a4904);
        transform: translateY(-1px);
    }

    .mkt-btn-reset {
        padding: 7px 10px;
        background: var(--prof-surface);
        color: var(--prof-text-muted);
        border: 1px solid var(--prof-border);
        border-radius: var(--radius-sm);
        font-size: 0.82rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mkt-btn-reset:hover {
        border-color: var(--prof-primary);
        color: var(--prof-primary);
    }

    /* Stat cards */
    .mkt-stat-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        height: 100%;
    }

    .mkt-stat-body {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 18px;
    }

    .mkt-stat-value {
        font-family: 'Outfit', sans-serif;
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--prof-text);
        line-height: 1;
        margin-bottom: 4px;
    }

    .mkt-stat-label {
        font-size: 0.75rem;
        color: var(--prof-text-muted);
        font-weight: 500;
        margin: 0;
    }

    .mkt-stat-icon {
        width: 46px; height: 46px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    /* Chart cards */
    .mkt-chart-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        height: 100%;
    }

    .mkt-chart-header {
        padding: 14px 18px;
        border-bottom: 1px solid var(--prof-border);
        background: var(--prof-surface);
    }

    .mkt-chart-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.88rem;
        font-weight: 700;
        margin: 0;
        color: var(--prof-text);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .mkt-chart-title i { color: var(--prof-accent); }

    .mkt-chart-body { padding: 14px; }

    /* Table card */
    .mkt-table-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .mkt-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        border-bottom: 1px solid var(--prof-border);
        background: var(--prof-surface);
    }

    .mkt-table-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.88rem;
        font-weight: 700;
        margin: 0;
        color: var(--prof-text);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .mkt-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 22px;
        height: 22px;
        padding: 0 7px;
        background: var(--prof-primary-light);
        color: var(--prof-primary);
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .mkt-btn-outline {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border: 1px solid var(--prof-border);
        background: white;
        color: var(--prof-text-muted);
        border-radius: var(--radius-sm);
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .mkt-btn-outline:hover {
        border-color: var(--prof-primary);
        color: var(--prof-primary);
    }

    .mkt-table-body { padding: 0; }

    /* Responsive table */
    .mkt-table-body .table {
        font-size: 0.82rem;
        margin: 0;
    }

    .mkt-table-body .table th {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 700;
        color: var(--prof-text-muted);
        background: var(--prof-surface);
        border-bottom: 1px solid var(--prof-border);
        padding: 10px 14px;
        white-space: nowrap;
    }

    .mkt-table-body .table td {
        padding: 10px 14px;
        vertical-align: middle;
        border-bottom: 1px solid var(--prof-border);
        color: var(--prof-text);
    }

    .mkt-table-body .table tbody tr:hover { background: var(--prof-surface); }

    /* Status badges */
    .mkt-status-inscrito {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        background: rgba(16,185,129,0.1);
        color: #10b981;
    }

    .mkt-status-preinscrito {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        background: rgba(252,123,4,0.1);
        color: #fc7b04;
    }

    /* Pagination */
    .mkt-pagination {
        display: flex;
        justify-content: center;
        gap: 4px;
        padding: 12px 18px;
        flex-wrap: wrap;
    }

    .mkt-page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 8px;
        border: 1px solid var(--prof-border);
        background: white;
        color: var(--prof-text-muted);
        border-radius: var(--radius-sm);
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .mkt-page-btn:hover { border-color: var(--prof-primary); color: var(--prof-primary); }

    .mkt-page-btn.active {
        background: linear-gradient(135deg, #9a4904, #df6a04);
        border-color: transparent;
        color: white;
    }

    .mkt-page-btn:disabled { opacity: 0.4; cursor: not-allowed; }

    /* Cargo banner */
    .mkt-cargo-banner {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: var(--prof-primary-light);
        border: 1px solid rgba(154,73,4,0.2);
        border-radius: var(--radius-sm);
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--prof-primary);
    }

    @media (max-width: 767px) {
        .mkt-stat-value { font-size: 1.25rem; }
        .mkt-table-body { overflow-x: auto; }
    }

    /* Grupo de programas */
    .mkt-program-group {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .mkt-program-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: linear-gradient(135deg, var(--prof-primary-light) 0%, #fff7ed 100%);
        border-bottom: 1px solid var(--prof-border);
    }

    .mkt-program-header i {
        color: var(--prof-primary);
        font-size: 1.1rem;
    }

    .mkt-program-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--prof-text);
    }

    .mkt-program-badge {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        background: var(--prof-primary);
        color: white;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .mkt-group-table {
        margin: 0;
        font-size: 0.82rem;
    }

    .mkt-group-table thead th {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 700;
        color: var(--prof-text-muted);
        background: var(--prof-surface);
        border-bottom: 1px solid var(--prof-border);
        padding: 10px 14px;
        white-space: nowrap;
    }

    .mkt-group-table tbody td {
        padding: 10px 14px;
        vertical-align: middle;
        border-bottom: 1px solid var(--prof-border);
        color: var(--prof-text);
    }

    .mkt-group-table tbody tr:last-child td {
        border-bottom: none;
    }

    .mkt-group-table tbody tr:hover {
        background: linear-gradient(90deg, rgba(154,73,4,0.03), rgba(154,73,4,0.06));
    }

    /* Badges adicionales */
    .mkt-badge-plan {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        background: rgba(139, 92, 246, 0.1);
        color: #7c3aed;
    }

    .mkt-badge-sucursal {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        background: rgba(20, 184, 166, 0.1);
        color: #0d9488;
    }

    .mkt-badge-fecha {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        background: var(--prof-surface);
        color: var(--prof-text-muted);
    }

    /* Documentos section */
    .doc-item {
        background: white;
        border: 1px solid var(--prof-border);
        border-radius: var(--radius-md);
        padding: 14px;
        transition: all 0.2s;
    }

    .doc-item:hover {
        box-shadow: var(--shadow-md);
    }

    .doc-item.completo {
        border-left: 4px solid var(--prof-success);
    }

    .doc-item.incompleto {
        border-left: 4px solid var(--prof-danger);
    }

    .doc-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }

    .doc-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .doc-icon.completo {
        background: var(--prof-success-light);
        color: var(--prof-success);
    }

    .doc-icon.incompleto {
        background: var(--prof-danger-light);
        color: var(--prof-danger);
    }

    .doc-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--prof-text);
    }

    .doc-detail {
        font-size: 0.75rem;
        color: var(--prof-text-muted);
    }

    .doc-status {
        margin-left: auto;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .doc-status.completo {
        background: var(--prof-success-light);
        color: var(--prof-success);
    }

    .doc-status.incompleto {
        background: var(--prof-danger-light);
        color: var(--prof-danger);
    }

    .doc-preview {
        margin-top: 10px;
        border-radius: var(--radius-sm);
        overflow: hidden;
        border: 1px solid var(--prof-border);
    }

    .doc-preview img {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
    }

    .doc-preview a {
        display: block;
        padding: 8px;
        background: var(--prof-surface);
        text-align: center;
        font-size: 0.8rem;
        color: var(--prof-primary);
        text-decoration: none;
    }

    .doc-preview a:hover {
        background: var(--prof-primary-light);
    }

    /* ═══════════════════════════════════════
       Tab Documentos (documentos.blade.php)
    ═══════════════════════════════════════ */

    .documents-tab .docs-stat-card {
        background: white;
        border: 1px solid var(--prof-border);
        border-radius: var(--radius-md);
        padding: 14px;
        text-align: center;
        box-shadow: var(--shadow-sm);
    }

    .documents-tab .docs-stat-card.verified {
        border-color: var(--prof-success);
        background: var(--prof-success-light);
    }

    .documents-tab .docs-stat-card.pending {
        border-color: var(--prof-warning);
        background: var(--prof-warning-light);
    }

    .documents-tab .docs-stat-card.missing {
        border-color: var(--prof-danger);
        background: var(--prof-danger-light);
    }

    .documents-tab .docs-stat-value {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--prof-text);
    }

    .documents-tab .docs-stat-label {
        font-size: 0.7rem;
        color: var(--prof-text-muted);
        font-weight: 600;
    }

    .documents-tab .docs-progress {
        background: white;
        border: 1px solid var(--prof-border);
        border-radius: var(--radius-md);
        padding: 12px 16px;
        box-shadow: var(--shadow-sm);
    }

    .documents-tab .docs-progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 0.8rem;
    }

    .documents-tab .docs-progress-header span:first-child {
        color: var(--prof-text-muted);
    }

    .documents-tab .docs-progress-badge {
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .documents-tab .docs-progress-bar {
        height: 8px;
        background: var(--prof-border);
        border-radius: 4px;
        overflow: hidden;
    }

    .documents-tab .docs-progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.4s ease;
    }

    .documents-tab .doc-card {
        background: white;
        border: 1px solid var(--prof-border);
        border-radius: var(--radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        height: 100%;
    }

    .documents-tab .doc-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-bottom: 1px solid var(--prof-border);
        background: var(--prof-surface);
    }

    .documents-tab .doc-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .documents-tab .doc-info {
        flex: 1;
        min-width: 0;
    }

    .documents-tab .doc-name {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--prof-text);
    }

    .documents-tab .doc-group {
        font-size: 0.7rem;
        color: var(--prof-text-muted);
    }

    .documents-tab .doc-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .documents-tab .doc-card-body {
        padding: 14px 16px;
    }

    .documents-tab .doc-file {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: var(--prof-surface);
        border-radius: var(--radius-sm);
        margin-bottom: 12px;
    }

    .documents-tab .doc-file i:first-child {
        font-size: 1.5rem;
        color: var(--prof-danger);
    }

    .documents-tab .doc-file-info {
        flex: 1;
        min-width: 0;
    }

    .documents-tab .doc-filename {
        font-weight: 600;
        font-size: 0.82rem;
        color: var(--prof-text);
    }

    .documents-tab .doc-filedate {
        font-size: 0.72rem;
        color: var(--prof-text-muted);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .documents-tab .doc-file i.verified {
        font-size: 1.25rem;
        color: var(--prof-success);
    }

    .documents-tab .doc-file i.pending {
        font-size: 1.25rem;
        color: var(--prof-warning);
    }

    .documents-tab .doc-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .documents-tab .btn-doc {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border: 1px solid var(--prof-border);
        background: white;
        color: var(--prof-text-muted);
        border-radius: var(--radius-sm);
        font-size: 0.78rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .documents-tab .btn-doc:hover {
        border-color: var(--prof-primary);
        color: var(--prof-primary);
    }

    .documents-tab .btn-doc.download:hover {
        border-color: var(--prof-success);
        color: var(--prof-success);
    }

    .documents-tab .btn-doc.verify {
        background: var(--prof-success);
        border-color: var(--prof-success);
        color: white;
    }

    .documents-tab .btn-doc.verify:hover {
        background: #059669;
        border-color: #059669;
    }

    .documents-tab .btn-doc.unverify {
        background: var(--prof-surface);
        color: var(--prof-text-muted);
    }

    .documents-tab .doc-empty {
        text-align: center;
        padding: 16px;
    }

    .documents-tab .doc-empty i {
        font-size: 1.75rem;
        color: var(--prof-danger);
        margin-bottom: 6px;
        display: block;
    }

    .documents-tab .doc-empty p {
        font-size: 0.8rem;
        color: var(--prof-text-muted);
        margin: 0;
    }

    /* ═══════════════════════════════════════
       Tab Ofertas Activas
    ═══════════════════════════════════════ */

    /* Cargo banner */
    .oa-cargo-banner {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 20px;
        background: linear-gradient(135deg, #9a4904 0%, #df6a04 60%, #fc7b04 100%);
        border-radius: var(--radius-md);
        box-shadow: 0 4px 16px rgba(154,73,4,0.25);
        position: relative;
        overflow: hidden;
        color: white;
    }

    .oa-cargo-decoration {
        position: absolute;
        top: -30px; right: -20px;
        width: 120px; height: 120px;
        background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .oa-cargo-icon {
        width: 42px; height: 42px;
        background: rgba(255,255,255,0.18);
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        color: white;
        flex-shrink: 0;
        border: 1px solid rgba(255,255,255,0.25);
        position: relative; z-index: 1;
    }

    .oa-cargo-body {
        position: relative; z-index: 1;
    }

    .oa-cargo-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255,255,255,0.75);
        font-weight: 600;
        margin-bottom: 2px;
    }

    .oa-cargo-text {
        font-family: 'Outfit', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: white;
    }

    /* Filters card */
    .oa-filters-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        border-top: 3px solid var(--prof-accent);
    }

    .oa-filters-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        background: linear-gradient(135deg, var(--prof-surface) 0%, #fff7ed 100%);
        border-bottom: 1px solid var(--prof-border);
    }

    .oa-filters-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .oa-filters-icon {
        width: 36px; height: 36px;
        background: linear-gradient(135deg, #9a4904, #df6a04);
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(154,73,4,0.3);
    }

    .oa-filters-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--prof-text);
        line-height: 1.2;
    }

    .oa-filters-subtitle {
        font-size: 0.72rem;
        color: var(--prof-text-muted);
        margin-top: 1px;
    }

    .oa-filters-body {
        padding: 18px 20px;
    }

    .oa-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--prof-text-muted);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .oa-label i { color: var(--prof-accent); font-size: 0.82rem; }

    .oa-search-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .oa-search-icon {
        position: absolute;
        left: 11px;
        color: var(--prof-text-muted);
        font-size: 0.9rem;
        pointer-events: none;
        z-index: 1;
    }

    .oa-search-input {
        width: 100%;
        padding: 8px 12px 8px 34px;
        border: 1.5px solid var(--prof-border);
        border-radius: var(--radius-sm);
        font-size: 0.83rem;
        color: var(--prof-text);
        background: var(--prof-surface);
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: all 0.2s ease;
    }

    .oa-search-input:focus {
        outline: none;
        border-color: var(--prof-primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(154,73,4,0.1);
    }

    .oa-search-input::placeholder { color: var(--prof-text-muted); }

    .oa-select {
        width: 100%;
        padding: 8px 12px;
        border: 1.5px solid var(--prof-border);
        border-radius: var(--radius-sm);
        font-size: 0.83rem;
        color: var(--prof-text);
        background: var(--prof-surface);
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        padding-right: 30px;
    }

    .oa-select:focus {
        outline: none;
        border-color: var(--prof-primary);
        background-color: white;
        box-shadow: 0 0 0 3px rgba(154,73,4,0.1);
    }

    .oa-btn-filter {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 16px;
        background: linear-gradient(135deg, #9a4904, #df6a04);
        color: white;
        border: none;
        border-radius: var(--radius-sm);
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(154,73,4,0.25);
    }

    .oa-btn-filter:hover {
        background: linear-gradient(135deg, #743c04, #9a4904);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(154,73,4,0.35);
    }

    .oa-btn-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 10px;
        background: white;
        color: var(--prof-text-muted);
        border: 1.5px solid var(--prof-border);
        border-radius: var(--radius-sm);
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .oa-btn-reset:hover {
        border-color: var(--prof-primary);
        color: var(--prof-primary);
        background: var(--prof-primary-light);
    }

    /* Table card */
    .oa-table-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .oa-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 20px;
        background: linear-gradient(135deg, var(--prof-surface) 0%, #fff7ed 100%);
        border-bottom: 1px solid var(--prof-border);
        flex-wrap: wrap;
    }

    .oa-table-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .oa-table-icon {
        width: 40px; height: 40px;
        background: linear-gradient(135deg, #9a4904, #df6a04);
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(154,73,4,0.3);
    }

    .oa-table-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        margin: 0 0 2px;
        color: var(--prof-text);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .oa-table-subtitle {
        font-size: 0.72rem;
        color: var(--prof-text-muted);
        margin: 0;
    }

    .oa-count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 22px;
        padding: 0 8px;
        background: var(--prof-primary);
        color: white;
        border-radius: 50px;
        font-size: 0.68rem;
        font-weight: 700;
    }

    .oa-table-header-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .oa-btn-refresh {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border: 1.5px solid var(--prof-border);
        background: white;
        color: var(--prof-text-muted);
        border-radius: var(--radius-sm);
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .oa-btn-refresh:hover {
        border-color: var(--prof-primary);
        color: var(--prof-primary);
        background: var(--prof-primary-light);
    }

    .oa-hint-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        background: rgba(252,123,4,0.05);
        border-bottom: 1px solid rgba(252,123,4,0.12);
        font-size: 0.78rem;
        color: var(--prof-text-muted);
        line-height: 1.5;
    }

    .oa-hint-icon {
        width: 28px; height: 28px;
        background: rgba(252,123,4,0.12);
        border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        color: var(--prof-accent);
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .oa-hint-bar strong { color: var(--prof-primary); }

    /* Loading state */
    .oa-loading-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3.5rem 1rem;
        gap: 12px;
    }

    .oa-loading-text {
        font-size: 0.85rem;
        color: var(--prof-text-muted);
        margin: 0;
    }

    @media (max-width: 767px) {
        .oa-table-header { flex-direction: column; align-items: flex-start; }
        .oa-table-header-right { width: 100%; }
        .oa-btn-refresh { width: 100%; justify-content: center; }
        .oa-filters-body { padding: 14px 16px; }
        .oa-cargo-banner { padding: 14px 16px; }
    }

    /* Color utilities */
    .bg-success-subtle { background: var(--prof-success-light); }
    .text-success-subtle { color: var(--prof-success); }
    .bg-warning-subtle { background: var(--prof-warning-light); }
    .text-warning-subtle { color: var(--prof-warning); }
    .bg-danger-subtle { background: var(--prof-danger-light); }
    .text-danger-subtle { color: var(--prof-danger); }
    .bg-secondary-subtle { background: var(--prof-surface); }
    .text-secondary-subtle { color: var(--prof-text-muted); }
</style>
