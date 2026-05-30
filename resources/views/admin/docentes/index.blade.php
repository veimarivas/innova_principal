@extends('layouts.master')
@section('title') Docentes @endsection
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300..700&family=Plus+Jakarta+Sans:wght@400..800&display=swap" rel="stylesheet">
<style>
:root {
    --doc-primary: #fc7b04;
    --doc-primary-dark: #d46604;
    --doc-primary-deeper: #a85203;
    --doc-accent: #e8860a;
    --doc-bg: #faf8f5;
    --doc-surface: #ffffff;
    --doc-surface-alt: #fdf9f4;
    --doc-text: #2a2520;
    --doc-text-secondary: #7a746c;
    --doc-text-muted: #a8a29a;
    --doc-border: #ede8e2;
    --doc-border-light: #f5f0eb;
    --doc-shadow-sm: 0 1px 3px rgba(42,37,32,0.04), 0 1px 2px rgba(42,37,32,0.03);
    --doc-shadow-md: 0 4px 16px rgba(42,37,32,0.06), 0 2px 6px rgba(42,37,32,0.03);
    --doc-shadow-lg: 0 12px 40px rgba(42,37,32,0.07), 0 4px 12px rgba(42,37,32,0.04);
    --doc-success: #2e9a6e;
    --doc-success-bg: rgba(46,154,110,0.08);
    --doc-success-border: rgba(46,154,110,0.2);
    --doc-danger: #d94f4f;
    --doc-danger-bg: rgba(217,79,79,0.08);
    --doc-danger-border: rgba(217,79,79,0.2);
    --doc-warning: #e8a030;
    --doc-purple: #7c3aed;
    --doc-purple-bg: rgba(124,58,237,0.08);
    --doc-purple-border: rgba(124,58,237,0.2);
    --doc-whatsapp: #25D366;
    --doc-whatsapp-bg: rgba(37,211,102,0.08);
    --doc-whatsapp-border: rgba(37,211,102,0.2);
}

body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
h1,h2,h3,h4,h5,h6,.doc-title,.doc-stat-num,.doc-section-label,.modal-title { font-family: 'Outfit', sans-serif; }

/* ─── ANIMATIONS ─── */
.doc-animate { opacity: 0; transform: translateY(16px); animation: docFadeUp 0.55s cubic-bezier(0.16,1,0.3,1) forwards; }
.doc-animate-1 { animation-delay: 0.04s; }
.doc-animate-2 { animation-delay: 0.1s; }
.doc-animate-3 { animation-delay: 0.18s; }
@keyframes docFadeUp { to { opacity: 1; transform: translateY(0); } }

/* ─── PAGE BACKGROUND ─── */
.doc-page { position: relative; min-height: 100%; }
.doc-page::before { content: ''; position: fixed; inset: 0; z-index: -1; background: var(--doc-bg); }
.doc-page::after { content: ''; position: fixed; inset: 0; z-index: -1; opacity: 0.02; background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E"); background-size: 200px 200px; pointer-events: none; }

/* ─── PAGE HEADER ─── */
.doc-header { position: relative; padding: 1.6rem 0 1.35rem; background: linear-gradient(135deg, #ffffff 0%, #fef8f1 45%, #fdf3e7 100%); border-bottom: 1px solid var(--doc-border); overflow: hidden; }
.doc-header::before { content: ''; position: absolute; top: -60%; right: -8%; width: 550px; height: 550px; border-radius: 50%; background: radial-gradient(circle, rgba(252,123,4,0.06) 0%, transparent 70%); pointer-events: none; }
.doc-header::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent 0%, rgba(252,123,4,0.18) 50%, transparent 100%); }
.doc-header-inner { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; position: relative; z-index: 1; }
.doc-header-left { display: flex; align-items: center; gap: 1rem; }
.doc-header-icon { width: 50px; height: 50px; background: linear-gradient(135deg, rgba(252,123,4,0.13), rgba(252,123,4,0.04)); border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(252,123,4,0.1); box-shadow: 0 2px 10px rgba(252,123,4,0.08); }
.doc-header-icon i { font-size: 1.45rem; color: var(--doc-primary); }
.doc-header-text h1 { font-size: 1.4rem; font-weight: 600; color: var(--doc-text); margin: 0 0 0.1rem; line-height: 1.2; letter-spacing: -0.02em; }
.doc-header-text p { font-size: 0.83rem; color: var(--doc-text-secondary); margin: 0; font-weight: 400; }
.doc-header-right { display: flex; align-items: center; gap: 0.7rem; flex-wrap: wrap; }

.doc-stat-card { display: flex; align-items: center; gap: 0.65rem; background: rgba(255,255,255,0.75); border: 1px solid var(--doc-border); border-radius: 12px; padding: 0.55rem 1rem; transition: box-shadow 0.25s, transform 0.2s; backdrop-filter: blur(4px); }
.doc-stat-card:hover { box-shadow: var(--doc-shadow-md); transform: translateY(-1px); }
.doc-stat-icon { width: 34px; height: 34px; background: linear-gradient(135deg, rgba(252,123,4,0.12), rgba(252,123,4,0.04)); border-radius: 9px; display: flex; align-items: center; justify-content: center; }
.doc-stat-icon i { color: var(--doc-primary); font-size: 0.95rem; }
.doc-stat-num { font-size: 1.2rem; font-weight: 700; color: var(--doc-text); line-height: 1; letter-spacing: -0.02em; }
.doc-stat-label { font-size: 0.7rem; color: var(--doc-text-secondary); margin-top: 2px; font-weight: 450; }

/* ─── SEARCH CARD ─── */
.doc-search-card { background: var(--doc-surface); border: 1px solid var(--doc-border); border-radius: 16px; box-shadow: var(--doc-shadow-sm); padding: 1.4rem; margin-bottom: 1.4rem; }
.doc-search-row { display: flex; align-items: flex-end; gap: 0.85rem; flex-wrap: wrap; }
.doc-search-field { flex: 1; min-width: 200px; }
.doc-search-field label { font-weight: 600; font-size: 0.8rem; color: var(--doc-text); margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.3rem; }
.doc-search-field label i { color: var(--doc-primary); }
.doc-search-field .form-control { background: var(--doc-surface-alt); border: 1.5px solid var(--doc-border); border-radius: 10px; padding: 0.6rem 0.95rem; font-size: 0.88rem; color: var(--doc-text); font-weight: 500; transition: border-color 0.2s, box-shadow 0.2s, background 0.2s; font-family: 'Plus Jakarta Sans', sans-serif; }
.doc-search-field .form-control:focus { border-color: var(--doc-primary); box-shadow: 0 0 0 3.5px rgba(252,123,4,0.1); background: #fff; outline: none; }
.doc-search-field .form-control::placeholder { color: #b8b2aa; }

/* ─── BUTTONS ─── */
.doc-btn { display: inline-flex; align-items: center; gap: 0.4rem; border: none; font-weight: 600; font-size: 0.84rem; padding: 0.62rem 1.25rem; border-radius: 10px; cursor: pointer; transition: all 0.22s ease; white-space: nowrap; font-family: 'Plus Jakarta Sans', sans-serif; position: relative; overflow: hidden; }
.doc-btn i { font-size: 1rem; transition: transform 0.25s ease; }
.doc-btn:hover i { transform: scale(1.12); }

.doc-btn-search { background: linear-gradient(135deg, #fc7b04 0%, #e06b00 50%, #c96004 100%); background-size: 200% 200%; animation: docShimmer 3.5s ease-in-out infinite; color: #fff; box-shadow: 0 3px 12px rgba(252,123,4,0.28); }
.doc-btn-search:hover { background-position: 100% 50%; transform: translateY(-1px); box-shadow: 0 5px 18px rgba(252,123,4,0.38); color: #fff; }
@keyframes docShimmer { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }

.doc-btn-register { background: linear-gradient(135deg, #5a8a30 0%, #6dbf40 50%, #7dd455 100%); background-size: 200% 200%; animation: docShimmer 3.5s ease-in-out infinite; color: #fff; box-shadow: 0 3px 12px rgba(90,138,48,0.28); }
.doc-btn-register:hover { background-position: 100% 50%; transform: translateY(-1px); box-shadow: 0 5px 18px rgba(90,138,48,0.38); color: #fff; }

.doc-btn-cancel { background: #ede9e4; color: var(--doc-text); border: none; border-radius: 10px; font-size: 0.84rem; font-weight: 500; padding: 0.55rem 1.05rem; transition: background 0.15s; font-family: 'Plus Jakarta Sans', sans-serif; }
.doc-btn-cancel:hover { background: #e2ddd7; color: var(--doc-text); }

.doc-btn-submit { background: linear-gradient(135deg, var(--doc-primary), var(--doc-primary-dark)); color: #fff; border: none; border-radius: 10px; font-size: 0.84rem; font-weight: 600; padding: 0.55rem 1.2rem; transition: box-shadow 0.2s, transform 0.15s; font-family: 'Plus Jakarta Sans', sans-serif; display: inline-flex; align-items: center; gap: 0.4rem; }
.doc-btn-submit:hover:not(:disabled) { box-shadow: 0 4px 14px rgba(252,123,4,0.32); color: #fff; transform: scale(1.02); }
.doc-btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }

.doc-btn-danger { background: var(--doc-danger); color: #fff; border: none; border-radius: 10px; font-size: 0.84rem; font-weight: 600; padding: 0.55rem 1.2rem; transition: box-shadow 0.2s, transform 0.15s; font-family: 'Plus Jakarta Sans', sans-serif; display: inline-flex; align-items: center; gap: 0.4rem; }
.doc-btn-danger:hover { box-shadow: 0 4px 14px rgba(217,79,79,0.32); color: #fff; transform: scale(1.02); }

/* ─── PERSONA FOUND ─── */
.doc-persona-found { background: var(--doc-surface); border: 1px solid var(--doc-border); border-radius: 16px; box-shadow: var(--doc-shadow-sm); overflow: hidden; margin-bottom: 1.4rem; display: none; }
.doc-pf-header { background: linear-gradient(135deg, #4a2406 0%, #7a3f06 40%, #c96004 100%); padding: 0.95rem 1.4rem; display: flex; align-items: center; justify-content: space-between; position: relative; }
.doc-pf-header::after { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 85% 50%, rgba(252,123,4,0.18) 0%, transparent 60%); pointer-events: none; }
.doc-pf-header h5 { color: #fff; font-weight: 600; font-size: 0.95rem; margin: 0; display: flex; align-items: center; gap: 0.45rem; position: relative; z-index: 1; }
.doc-pf-header h5 i { font-size: 1.1rem; }
.doc-pf-badge { background: rgba(255,255,255,0.15); color: #fff; font-size: 0.68rem; font-weight: 700; padding: 0.22rem 0.6rem; border-radius: 6px; text-transform: uppercase; position: relative; z-index: 1; backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.1); letter-spacing: 0.03em; }
.doc-pf-body { padding: 1.2rem 1.4rem; }
.doc-pf-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 0.7rem; }
.doc-pf-item label { font-size: 0.66rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--doc-text-muted); margin-bottom: 0.18rem; display: block; font-family: 'Outfit', sans-serif; }
.doc-pf-item span { font-size: 0.86rem; font-weight: 600; color: var(--doc-text); }

.doc-ya-docente { background: var(--doc-success-bg); border: 1px solid var(--doc-success-border); border-radius: 10px; padding: 0.65rem 0.95rem; display: flex; align-items: center; gap: 0.5rem; margin-top: 0.7rem; }
.doc-ya-docente i { color: var(--doc-success); font-size: 1.05rem; }
.doc-ya-docente span { font-size: 0.8rem; font-weight: 600; color: var(--doc-success); }

/* ─── SECTION LABELS ─── */
.doc-section-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.6px; color: var(--doc-text-secondary); border-bottom: 1.5px solid var(--doc-border); padding-bottom: 0.3rem; margin: 1.2rem 0 0.9rem; display: flex; align-items: center; gap: 0.4rem; }
.doc-section-label:first-child { margin-top: 0; }
.doc-section-label i { font-size: 0.82rem; color: var(--doc-primary); }

/* ─── BADGES ─── */
.doc-badge-active { display: inline-flex; align-items: center; gap: 0.22rem; background: var(--doc-success-bg); color: var(--doc-success); border: 1px solid var(--doc-success-border); border-radius: 20px; padding: 0.18rem 0.55rem; font-size: 0.7rem; font-weight: 700; }
.doc-badge-inactive { display: inline-flex; align-items: center; gap: 0.22rem; background: rgba(150,150,150,0.06); color: var(--doc-text-muted); border: 1px solid rgba(150,150,150,0.15); border-radius: 20px; padding: 0.18rem 0.55rem; font-size: 0.7rem; font-weight: 600; }

/* ─── TABLE CARD ─── */
.doc-card { background: var(--doc-surface); border: 1px solid var(--doc-border); border-radius: 16px; overflow: hidden; box-shadow: var(--doc-shadow-sm); transition: box-shadow 0.3s; }
.doc-card:hover { box-shadow: var(--doc-shadow-md); }
.doc-card-header { padding: 1rem 1.3rem; border-bottom: 1px solid var(--doc-border-light); background: linear-gradient(135deg, #ffffff, #fdf8f2); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
.doc-card-header-left { display: flex; align-items: center; gap: 0.8rem; }
.doc-card-header-icon { width: 36px; height: 36px; background: linear-gradient(135deg, rgba(252,123,4,0.1), rgba(252,123,4,0.04)); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.doc-card-header-icon i { color: var(--doc-primary); font-size: 1rem; }
.doc-card-title { font-size: 0.92rem; font-weight: 600; color: var(--doc-text); margin: 0; letter-spacing: -0.01em; }
.doc-card-subtitle { font-size: 0.76rem; color: var(--doc-text-secondary); margin: 2px 0 0; font-weight: 400; }
.doc-card-body { padding: 0; }

/* ─── TABLE ─── */
.doc-table { width: 100% !important; border-collapse: collapse; margin: 0 !important; }
.doc-table thead th { background: var(--doc-surface-alt); color: var(--doc-text); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.8rem 1rem; border-bottom: 2px solid var(--doc-border); white-space: nowrap; font-family: 'Outfit', sans-serif; }
.doc-table tbody td { padding: 0.8rem 1rem; font-size: 0.85rem; color: var(--doc-text); border-bottom: 1px solid var(--doc-border-light); vertical-align: middle; }
.doc-table tbody tr { transition: background 0.2s, transform 0.15s; }
.doc-table tbody tr:last-child td { border-bottom: none; }
.doc-table tbody tr:hover td { background: rgba(252,123,4,0.03); }
.doc-table tbody tr:hover { transform: translateX(3px); }

.doc-action-cell { display: flex; align-items: center; justify-content: center; gap: 0.28rem; }
.doc-btn-action { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: all 0.2s; background: transparent; color: var(--doc-text-muted); cursor: pointer; }
.doc-btn-action i { font-size: 0.92rem; }
.doc-btn-action:hover { background: var(--doc-border-light); }
.doc-btn-action-view:hover { background: rgba(252,123,4,0.08); color: var(--doc-primary); }
.doc-btn-action-cuenta { background: var(--doc-purple-bg); color: var(--doc-purple); border: 1px solid var(--doc-purple-border); }
.doc-btn-action-cuenta:hover { background: rgba(124,58,237,0.14); color: #5b21b6; }
.doc-btn-action-whatsapp { background: var(--doc-whatsapp-bg); color: var(--doc-whatsapp); border: 1px solid var(--doc-whatsapp-border); }
.doc-btn-action-whatsapp:hover { background: rgba(37,211,102,0.14); color: #128C7E; }
.doc-btn-action-delete:hover { background: var(--doc-danger-bg); color: var(--doc-danger); }

/* ─── MODAL ─── */
.modal-content { border: none; border-radius: 16px; box-shadow: var(--doc-shadow-lg); overflow: hidden; }
.modal-header { padding: 1rem 1.3rem 0.8rem; border-bottom: 1px solid var(--doc-border-light); background: linear-gradient(135deg, #ffffff, #fdf8f2); }
.modal-header .modal-title { font-family: 'Outfit', sans-serif; font-size: 0.98rem; font-weight: 600; color: var(--doc-text); letter-spacing: -0.01em; display: flex; align-items: center; gap: 0.45rem; }
.modal-header .modal-title i { color: var(--doc-primary); font-size: 1.1rem; }
.modal-header .btn-close { transition: transform 0.2s, opacity 0.2s; opacity: 0.5; }
.modal-header .btn-close:hover { transform: rotate(90deg); opacity: 1; }
.modal-body { padding: 0.95rem 1.3rem; }
.modal-footer { padding: 0.8rem 1.3rem; border-top: 1px solid var(--doc-border-light); background: var(--doc-surface-alt); }
.modal-backdrop { background: rgba(0,0,0,0.3); }
@supports (backdrop-filter: blur(3px)) { .modal-backdrop { backdrop-filter: blur(3px); background: rgba(0,0,0,0.2); } }
.modal.fade .modal-dialog { transform: scale(0.93) translateY(-8px); transition: transform 0.35s cubic-bezier(0.16,1,0.3,1), opacity 0.25s; }
.modal.show .modal-dialog { transform: scale(1) translateY(0); }

/* ─── MODAL FORM ─── */
.doc-modal-form .modal-body { max-height: calc(100vh - 200px); overflow-y: auto; }
.doc-modal-form .modal-content { max-height: calc(100vh - 50px); display: flex; flex-direction: column; }
.doc-modal-section-header { display: flex; align-items: center; gap: 0.45rem; padding: 0.6rem 0 0.45rem; margin-bottom: 0.9rem; border-bottom: 2px solid var(--doc-primary); }
.doc-modal-section-header i { color: var(--doc-primary); font-size: 0.95rem; }
.doc-modal-section-header span { font-size: 0.76rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--doc-text); font-family: 'Outfit', sans-serif; }

/* ─── FORM FIELDS ─── */
.doc-label { font-size: 0.8rem; font-weight: 600; color: var(--doc-text); margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.3rem; }
.doc-label i { color: var(--doc-primary); font-size: 0.88rem; }
.doc-req { color: var(--doc-danger); font-weight: 700; }
.doc-field .form-control, .doc-field .form-select { border: 1.5px solid var(--doc-border); border-radius: 10px; padding: 0.52rem 0.85rem; font-size: 0.85rem; color: var(--doc-text); background: var(--doc-surface-alt); transition: border-color 0.2s, box-shadow 0.2s, background 0.2s; font-family: 'Plus Jakarta Sans', sans-serif; }
.doc-field .form-control:focus, .doc-field .form-select:focus { border-color: var(--doc-primary); box-shadow: 0 0 0 3px rgba(252,123,4,0.1); background: #fff; outline: none; }
.doc-field .form-control::placeholder { color: #b8b2aa; font-weight: 400; }

/* ─── DELETE MODAL ─── */
.doc-delete-box { text-align: center; padding: 0.4rem 0; }
.doc-delete-icon { width: 68px; height: 68px; border-radius: 50%; background: var(--doc-danger-bg); border: 2px solid var(--doc-danger-border); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; transition: transform 0.3s; }
.doc-delete-icon i { font-size: 1.8rem; color: var(--doc-danger); }
.doc-delete-box:hover .doc-delete-icon { transform: scale(1.05); }
.doc-delete-msg { font-size: 1rem; font-weight: 700; color: var(--doc-text); margin-bottom: 0.25rem; }
.doc-delete-name { font-size: 0.88rem; color: var(--doc-text); margin-bottom: 0.5rem; }
.doc-delete-name strong { color: var(--doc-primary); }
.doc-delete-warn { font-size: 0.76rem; color: var(--doc-text-secondary); margin-bottom: 0; display: flex; align-items: center; justify-content: center; gap: 0.3rem; }
.doc-delete-warn i { color: var(--doc-warning); font-size: 0.85rem; }

/* ─── CREDENTIALS ─── */
.doc-cred-alert { background: rgba(232,160,48,0.08); border: 1px solid rgba(232,160,48,0.2); border-radius: 10px; padding: 0.65rem 0.95rem; display: flex; gap: 0.55rem; align-items: flex-start; font-size: 0.8rem; margin-bottom: 0.9rem; }
.doc-cred-alert i { color: var(--doc-warning); font-size: 1.05rem; flex-shrink: 0; margin-top: 1px; }
.doc-cred-alert span { color: var(--doc-text); }
.doc-cred-label { font-size: 0.66rem; font-weight: 600; text-transform: uppercase; color: var(--doc-text-secondary); display: block; margin-bottom: 0.22rem; font-family: 'Outfit', sans-serif; }
.doc-cred-value { background: var(--doc-surface-alt); border: 1px solid var(--doc-border); border-radius: 8px; padding: 0.45rem 0.7rem; font-family: 'Plus Jakarta Sans', monospace; font-size: 0.88rem; font-weight: 600; color: var(--doc-text); display: flex; justify-content: space-between; align-items: center; }
.doc-cred-copy { background: none; border: none; cursor: pointer; color: var(--doc-text-muted); padding: 0 0 0 0.45rem; font-size: 0.95rem; transition: color 0.15s; }
.doc-cred-copy:hover { color: var(--doc-primary); }

/* ─── TOASTS ─── */
.toast-container { position: fixed; right: 20px; z-index: 1060; display: flex; flex-direction: column; gap: 0.45rem; pointer-events: none; }
.toast-notify { display: flex; align-items: center; gap: 0.6rem; padding: 0.7rem 0.95rem; background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); font-size: 0.8rem; font-weight: 500; color: var(--doc-text); border-left: 4px solid; animation: docToastIn 0.35s cubic-bezier(0.16,1,0.3,1); pointer-events: auto; min-width: 270px; max-width: 400px; font-family: 'Plus Jakarta Sans', sans-serif; }
.toast-notify.hiding { animation: docToastOut 0.25s ease forwards; }
.toast-notify.success { border-left-color: var(--doc-success); }
.toast-notify.error { border-left-color: var(--doc-danger); }
.toast-notify.warning { border-left-color: var(--doc-warning); }
.toast-icon { flex-shrink: 0; font-size: 1.05rem; }
.toast-notify.success .toast-icon i { color: var(--doc-success); }
.toast-notify.error .toast-icon i { color: var(--doc-danger); }
.toast-notify.warning .toast-icon i { color: var(--doc-warning); }
.toast-body-text { flex: 1; }
.toast-close { background: none; border: none; color: var(--doc-text-muted); cursor: pointer; padding: 0; font-size: 1.05rem; opacity: 0.5; transition: opacity 0.2s; flex-shrink: 0; }
.toast-close:hover { opacity: 1; }
@keyframes docToastIn { 0% { opacity: 0; transform: translateX(100%) scale(0.94); } 100% { opacity: 1; transform: translateX(0) scale(1); } }
@keyframes docToastOut { 0% { opacity: 1; transform: translateX(0) scale(1); } 100% { opacity: 0; transform: translateX(100%) scale(0.94); } }

/* ─── DATATABLES OVERRIDES ─── */
.doc-card .dataTables_wrapper { padding: 0; }
.doc-card .dataTables_wrapper .dataTables_filter { padding: 0.8rem 1rem 0; }
.doc-card .dataTables_wrapper .dataTables_filter label { font-size: 0.8rem; color: var(--doc-text-secondary); display: flex; align-items: center; gap: 0.45rem; font-weight: 450; }
.doc-card .dataTables_wrapper .dataTables_filter input { border: 1px solid var(--doc-border); border-radius: 8px; padding: 0.38rem 0.7rem; font-size: 0.8rem; color: var(--doc-text); background: var(--doc-surface-alt); transition: border-color 0.2s; font-family: 'Plus Jakarta Sans', sans-serif; outline: none; min-width: 180px; }
.doc-card .dataTables_wrapper .dataTables_filter input:focus { border-color: var(--doc-primary); box-shadow: 0 0 0 3px rgba(252,123,4,0.1); background: #fff; }
.doc-card .dataTables_wrapper .dataTables_length { padding: 0.8rem 1rem 0; }
.doc-card .dataTables_wrapper .dataTables_length label { font-size: 0.8rem; color: var(--doc-text-secondary); display: flex; align-items: center; gap: 0.35rem; font-weight: 450; }
.doc-card .dataTables_wrapper .dataTables_length select { border: 1px solid var(--doc-border); border-radius: 6px; padding: 0.28rem 1.4rem 0.28rem 0.45rem; font-size: 0.8rem; color: var(--doc-text); background: var(--doc-surface-alt) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%237a746c'/%3E%3C/svg%3E") no-repeat right 0.45rem center; appearance: none; cursor: pointer; outline: none; }
.doc-card .dataTables_wrapper .dataTables_length select:focus { border-color: var(--doc-primary); box-shadow: 0 0 0 3px rgba(252,123,4,0.1); background-color: #fff; }
.doc-card .dataTables_wrapper .dataTables_info { padding: 0.8rem 1rem; font-size: 0.76rem; color: var(--doc-text-secondary); }
.doc-card .dataTables_wrapper .dataTables_paginate { padding: 0.8rem 1rem; }
.doc-card .dataTables_wrapper .dataTables_paginate .paginate_button { border: 1px solid var(--doc-border) !important; border-radius: 8px !important; padding: 0.32rem 0.7rem !important; margin: 0 0.12rem; font-size: 0.76rem; color: var(--doc-text) !important; background: #fff !important; transition: all 0.2s; font-family: 'Plus Jakarta Sans', sans-serif; }
.doc-card .dataTables_wrapper .dataTables_paginate .paginate_button:hover { border-color: rgba(252,123,4,0.3) !important; background: rgba(252,123,4,0.06) !important; color: var(--doc-primary) !important; }
.doc-card .dataTables_wrapper .dataTables_paginate .paginate_button.current { border-color: var(--doc-primary) !important; background: linear-gradient(135deg, var(--doc-primary), var(--doc-primary-dark)) !important; color: #fff !important; font-weight: 600; }
.doc-card .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: 0.4; cursor: not-allowed; }
.doc-card table.dataTable.no-footer { border-bottom: 1px solid var(--doc-border-light); }

/* ─── RESPONSIVE ─── */
.doc-table-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
.doc-table-wrap table.dataTable { width: 100% !important; }
.doc-table-wrap table.dataTable td { white-space: normal !important; word-break: break-word; }

@media (max-width: 992px) {
    .doc-header-inner { flex-direction: column; align-items: flex-start; }
    .doc-header-right { width: 100%; }
    .doc-modal-form .modal-dialog { max-width: 100% !important; margin: 0.5rem; }
}
@media (max-width: 768px) {
    .doc-search-row { flex-direction: column; }
    .doc-search-field { min-width: 100%; }
    .doc-card-header { flex-direction: column; align-items: flex-start; }
    .doc-table thead th, .doc-table tbody td { padding: 0.48rem 0.55rem; font-size: 0.76rem; }
    .doc-btn-search, .doc-btn-register { width: 100%; justify-content: center; }
}
@media (max-width: 576px) {
    .doc-table thead th, .doc-table tbody td { padding: 0.38rem 0.45rem; font-size: 0.7rem; }
    .doc-table thead th { font-size: 0.63rem; }
}
</style>
@endsection

@section('content')
<div class="doc-page">
<div class="doc-header">
    <div class="container-fluid">
        <div class="doc-header-inner">
            <div class="doc-header-left doc-animate doc-animate-1">
                <div class="doc-header-icon"><i class="ri-teacher-line"></i></div>
                <div class="doc-header-text">
                    <h1>Docentes</h1>
                    <p>Gestión y registro de docentes</p>
                </div>
            </div>
            <div class="doc-header-right doc-animate doc-animate-2">
                <div class="doc-stat-card">
                    <div class="doc-stat-icon"><i class="ri-hashtag"></i></div>
                    <div>
                        <div class="doc-stat-num" id="stat-total">—</div>
                        <div class="doc-stat-label">Total Registros</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    {{-- ═══ SEARCH + REGISTER ═══ --}}
    <div class="doc-search-card doc-animate doc-animate-3">
        <div class="doc-search-row">
            <div class="doc-search-field">
                <label><i class="ri-id-card-line"></i> Buscar por Carnet</label>
                <input type="text" class="form-control" id="searchCarnet" placeholder="Ingrese el número de carnet…" maxlength="20" autocomplete="off">
            </div>
            <button type="button" class="doc-btn doc-btn-search" id="btnBuscar">
                <i class="ri-search-line"></i> Buscar
            </button>
            <button type="button" class="doc-btn doc-btn-register" id="btnAbrirRegistro">
                <i class="ri-teacher-line"></i> Registrar Docente
            </button>
            <button type="button" class="doc-btn-cancel" id="btnLimpiarBusqueda" style="display:none;">
                <i class="ri-close-line"></i> Limpiar
            </button>
        </div>
    </div>

    {{-- ═══ PERSONA FOUND ═══ --}}
    <div class="doc-persona-found" id="personaFound">
        <div class="doc-pf-header">
            <h5><i class="ri-user-line"></i> Persona Encontrada</h5>
            <span class="doc-pf-badge" id="pfCarnet"></span>
        </div>
        <div class="doc-pf-body">
            <div class="doc-pf-grid">
                <div class="doc-pf-item"><label>Nombres</label><span id="pfNombres"></span></div>
                <div class="doc-pf-item"><label>Apellido Paterno</label><span id="pfApPaterno"></span></div>
                <div class="doc-pf-item"><label>Apellido Materno</label><span id="pfApMaterno"></span></div>
                <div class="doc-pf-item"><label>Sexo</label><span id="pfSexo"></span></div>
                <div class="doc-pf-item"><label>Estado Civil</label><span id="pfEstadoCivil"></span></div>
                <div class="doc-pf-item"><label>Correo</label><span id="pfCorreo"></span></div>
                <div class="doc-pf-item"><label>Celular</label><span id="pfCelular"></span></div>
                <div class="doc-pf-item"><label>Ciudad</label><span id="pfCiudad"></span></div>
            </div>
            <div id="yaDocenteBox" class="doc-ya-docente" style="display:none;">
                <i class="ri-checkbox-circle-fill"></i>
                <span>Esta persona ya está registrada como docente.</span>
            </div>
        </div>
    </div>

    {{-- ═══ TABLE ═══ --}}
    <div class="row">
        <div class="col-12">
            <div class="doc-card">
                <div class="doc-card-header">
                    <div class="doc-card-header-left">
                        <div class="doc-card-header-icon"><i class="ri-table-line"></i></div>
                        <div>
                            <h5 class="doc-card-title">Listado de Docentes</h5>
                            <p class="doc-card-subtitle">Consulta y gestiona los docentes registrados</p>
                        </div>
                    </div>
                </div>
                <div class="doc-card-body">
                    <div class="doc-table-wrap">
                        <table id="tabla-docentes" class="doc-table">
                            <thead>
                                <tr>
                                    <th data-priority="1">Carnet</th>
                                    <th data-priority="2">Nombres y Apellidos</th>
                                    <th data-priority="5">Correo</th>
                                    <th data-priority="6">Celular</th>
                                    <th data-priority="3" class="text-center" style="width:120px;">Cuenta Sistema</th>
                                    <th data-priority="1" class="text-center" style="width:170px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════ MODAL REGISTRAR DOCENTE ════════════════ --}}
<div class="modal fade doc-modal-form" id="modalRegistro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistroTitle"><i class="ri-teacher-line"></i> Registrar Docente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formRegistro" novalidate autocomplete="off">
                <div class="modal-body">
                    <div id="registroYaDocente" class="doc-ya-docente" style="display:none;">
                        <i class="ri-checkbox-circle-fill"></i>
                        <span>Esta persona ya está registrada como docente.</span>
                    </div>

                    <div class="doc-modal-section-header"><i class="ri-id-card-line"></i><span>Datos de Identidad</span></div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="doc-label"><i class="ri-id-card-line"></i> Carnet <span class="doc-req">*</span></label>
                            <div class="doc-field">
                                <input type="text" class="form-control" id="rCarnet" placeholder="Ej: 12345678" maxlength="20" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="doc-label"><i class="ri-map-pin-line"></i> Expedido</label>
                            <div class="doc-field">
                                <input type="text" class="form-control" id="rExpedido" placeholder="Ej: LP" maxlength="10" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="doc-label"><i class="ri-calendar-line"></i> Fecha de Nacimiento</label>
                            <div class="doc-field">
                                <input type="date" class="form-control" id="rFechaNacimiento">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="doc-label"><i class="ri-user-line"></i> Nombres <span class="doc-req">*</span></label>
                            <div class="doc-field">
                                <input type="text" class="form-control" id="rNombres" placeholder="Ej: Juan Carlos" maxlength="100" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="doc-label"><i class="ri-user-3-line"></i> Sexo</label>
                            <select class="form-select" id="rSexo">
                                <option value="">— Seleccione —</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="doc-label">Apellido Paterno</label>
                            <div class="doc-field">
                                <input type="text" class="form-control" id="rApPaterno" placeholder="Ej: García" maxlength="80" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="doc-label">Apellido Materno</label>
                            <div class="doc-field">
                                <input type="text" class="form-control" id="rApMaterno" placeholder="Ej: López" maxlength="80" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="doc-label"><i class="ri-heart-line"></i> Estado Civil</label>
                            <select class="form-select" id="rEstadoCivil">
                                <option value="">— Seleccione —</option>
                                <option value="Soltero/a">Soltero/a</option>
                                <option value="Casado/a">Casado/a</option>
                                <option value="Divorciado/a">Divorciado/a</option>
                                <option value="Viudo/a">Viudo/a</option>
                            </select>
                        </div>
                    </div>

                    <div class="doc-modal-section-header mt-4"><i class="ri-phone-line"></i><span>Datos de Contacto</span></div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="doc-label"><i class="ri-mail-line"></i> Correo Electrónico</label>
                            <div class="doc-field">
                                <input type="email" class="form-control" id="rCorreo" placeholder="Ej: correo@dominio.com" maxlength="150" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="doc-label"><i class="ri-smartphone-line"></i> Celular</label>
                            <div class="doc-field">
                                <input type="text" class="form-control" id="rCelular" placeholder="Ej: 70000000" maxlength="20" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="doc-label"><i class="ri-phone-line"></i> Teléfono</label>
                            <div class="doc-field">
                                <input type="text" class="form-control" id="rTelefono" placeholder="Ej: 2000000" maxlength="20" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="doc-btn-cancel" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                    <button type="submit" class="doc-btn-submit" id="btnGuardarDocente"><i class="ri-save-line"></i> Registrar Docente</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════ MODAL ELIMINAR ════════════════ --}}
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-error-warning-line"></i> Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="doc-delete-box">
                    <div class="doc-delete-icon"><i class="ri-delete-bin-5-line"></i></div>
                    <p class="doc-delete-msg">¿Eliminar docente?</p>
                    <p class="doc-delete-name"><strong id="nombreEliminar"></strong></p>
                    <p class="doc-delete-warn"><i class="ri-information-line"></i> Esta acción es permanente y no puede deshacerse.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-3">
                <button type="button" class="doc-btn-cancel px-4" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                <button type="button" class="doc-btn-danger px-4" id="btnConfirmarEliminar"><i class="ri-delete-bin-line"></i> Eliminar</button>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════ MODAL CREDENCIALES ════════════════ --}}
<div class="modal fade" id="modalCredenciales" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCredencialesTitle"><i class="ri-key-line"></i> Credenciales Generadas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="doc-cred-alert">
                    <i class="ri-alert-line"></i>
                    <span>Guarde estas credenciales y compártalas con el docente. <strong>No serán mostradas nuevamente.</strong></span>
                </div>
                <div id="credencialesContent"></div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="doc-btn-submit px-4" data-bs-dismiss="modal"><i class="ri-check-line me-1"></i>Entendido</button>
            </div>
        </div>
    </div>
</div>

<div id="toastContainer" class="toast-container"></div>
</div>
@endsection

@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
(function () {
    'use strict';

    let tabla;
    let idEliminar = null;
    let personaEncontrada = null;
    const CSRF = '{{ csrf_token() }}';

    function init() {
        initDataTable();
        bindEvents();
    }

    function initDataTable() {
        tabla = $('#tabla-docentes').DataTable({
            ajax: { url: '{{ route("admin.docentes.listar") }}', dataSrc: 'data' },
            ordering: true,
            responsive: true,
            autoWidth: false,
            columns: [
                {
                    data: null, responsivePriority: 1,
                    render: d => {
                        const p = d.persona;
                        if (!p) return '<span style="color:var(--doc-text-muted)">—</span>';
                        let txt = '<span style="font-weight:700;">' + esc(p.carnet) + '</span>';
                        if (p.expedido) txt += '<br><small style="color:var(--doc-text-muted);font-size:0.7rem;">exp. ' + esc(p.expedido) + '</small>';
                        return txt;
                    }
                },
                {
                    data: null, responsivePriority: 2,
                    render: d => {
                        const p = d.persona;
                        if (!p) return '<span style="color:var(--doc-text-muted)">—</span>';
                        const n = esc(p.nombres);
                        const ap = [p.apellido_paterno, p.apellido_materno].filter(Boolean).map(esc).join(' ');
                        return '<span style="font-weight:600;">' + n + '</span>' + (ap ? '<br><small style="color:var(--doc-text-muted);">' + ap + '</small>' : '');
                    }
                },
                {
                    data: null, responsivePriority: 5,
                    render: d => {
                        const p = d.persona;
                        return p && p.correo ? esc(p.correo) : '<span style="color:var(--doc-text-muted)">—</span>';
                    }
                },
                {
                    data: null, responsivePriority: 6,
                    render: d => {
                        const p = d.persona;
                        return p && p.celular ? esc(p.celular) : '<span style="color:var(--doc-text-muted)">—</span>';
                    }
                },
                {
                    data: null, className: 'text-center', responsivePriority: 3,
                    render: d => d.tiene_cuenta_sistema
                        ? '<span class="doc-badge-active"><i class="ri-check-line"></i> Activa</span>'
                        : '<span class="doc-badge-inactive"><i class="ri-close-line"></i> Sin cuenta</span>'
                },
                {
                    data: null, className: 'text-center', responsivePriority: 1,
                    render: d => {
                        const nombre = esc(d.persona ? d.persona.nombres + ' ' + (d.persona.apellido_paterno || '') : 'Docente');
                        const celular = d.persona ? (d.persona.celular || '') : '';
                        const celularLimpio = celular.replace(/\D/g, '');
                        const tieneCuenta = d.tiene_cuenta_sistema;
                        const username = d.usuario_username || '';
                        const carnet = d.persona ? (d.persona.carnet || '') : '';
                        const password = generarPassword(carnet);
                        
                        let btns = '<div class="doc-action-cell">';
                        btns += '<a class="btn doc-btn-action doc-btn-action-view" href="/admin/docentes/' + d.id + '/detalle" title="Ver docente"><i class="ri-eye-line"></i></a>';
                        if (!tieneCuenta) {
                            btns += '<button class="btn doc-btn-action doc-btn-action-cuenta btn-crear-cuentas" data-id="' + d.id + '" title="Crear cuenta del sistema"><i class="ri-user-add-line"></i></button>';
                        }
                        if (celularLimpio.length >= 8 && tieneCuenta && username) {
                            btns += '<button class="btn doc-btn-action doc-btn-action-whatsapp" '
                                + 'data-celular="' + celularLimpio + '" '
                                + 'data-nombre="' + nombre + '" '
                                + 'data-username="' + username + '" '
                                + 'data-password="' + password + '" '
                                + 'title="Enviar accesos por WhatsApp"><i class="ri-whatsapp-line"></i></button>';
                        } else if (celularLimpio.length >= 8 && tieneCuenta && !username) {
                            btns += '<button class="btn doc-btn-action" title="Sin usuario" disabled style="opacity:0.3;cursor:not-allowed;"><i class="ri-whatsapp-line"></i></button>';
                        } else {
                            btns += '<button class="btn doc-btn-action" title="Sin cuenta" disabled style="opacity:0.3;cursor:not-allowed;"><i class="ri-whatsapp-line"></i></button>';
                        }
                        btns += '<button class="btn doc-btn-action doc-btn-action-delete btn-accion-eliminar" data-id="' + d.id + '" data-nombre="' + nombre + '" title="Eliminar docente"><i class="ri-delete-bin-fill"></i></button>'
                            + '</div>';
                        return btns;
                    }
                }
            ],
            language: {
                processing: 'Procesando...', search: 'Buscar:', zeroRecords: 'No se encontraron registros',
                emptyTable: 'No hay datos disponibles',
                paginate: { first: 'Primero', previous: 'Anterior', next: 'Siguiente', last: 'Último' }
            },
            order: [[0, 'asc']],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Todos']],
            pageLength: 10,
            drawCallback: function () {
                $('#stat-total').text(this.api().page.info().recordsTotal);
            }
        });
    }

    function bindEvents() {
        $('#btnBuscar').on('click', buscarPersona);
        $('#searchCarnet').on('keypress', function (e) { if (e.which === 13) buscarPersona(); });
        $('#btnAbrirRegistro').on('click', abrirModalRegistro);
        $('#btnLimpiarBusqueda').on('click', function () { limpiarBusqueda(); resetFormRegistro(); });
        $('#formRegistro').on('submit', function (e) { e.preventDefault(); guardarDocente(); });

        $(document).on('click', '.btn-accion-eliminar', function () {
            idEliminar = $(this).data('id');
            $('#nombreEliminar').text($(this).data('nombre'));
            openModal('modalEliminar');
        });
        $('#btnConfirmarEliminar').on('click', function () {
            if (idEliminar) eliminarDocente(idEliminar);
        });

        $(document).on('click', '.btn-crear-cuentas', function () {
            crearCuentas($(this).data('id'));
        });

        document.getElementById('modalRegistro').addEventListener('hidden.bs.modal', resetFormRegistro);
    }

    function buscarPersona() {
        const carnet = $('#searchCarnet').val().trim();
        if (!carnet) {
            toast('warning', 'Ingrese un número de carnet para buscar.');
            return;
        }

        setBtnLoading('#btnBuscar', true, 'Buscando…');
        $.post('{{ route("admin.docentes.buscarCarnet") }}', { _token: CSRF, carnet: carnet })
            .done(function (r) {
                if (r.encontrado) {
                    personaEncontrada = r.persona;
                    mostrarPersonaEncontrada(r.persona, r.ya_docente);
                } else {
                    $('#personaFound').slideUp(200);
                    toast('warning', 'No se encontró ninguna persona con el carnet: ' + carnet);
                }
            })
            .fail(function () { toast('error', 'Error al buscar. Intente nuevamente.'); })
            .always(function () { setBtnLoading('#btnBuscar', false, '<i class="ri-search-line"></i> Buscar'); });
    }

    function mostrarPersonaEncontrada(p, yaDocente) {
        $('#pfCarnet').text(p.carnet);
        $('#pfNombres').text(p.nombres || '—');
        $('#pfApPaterno').text(p.apellido_paterno || '—');
        $('#pfApMaterno').text(p.apellido_materno || '—');
        $('#pfSexo').text(p.sexo ? (p.sexo === 'M' ? 'Masculino' : 'Femenino') : '—');
        $('#pfEstadoCivil').text(p.estado_civil || '—');
        $('#pfCorreo').text(p.correo || '—');
        $('#pfCelular').text(p.celular || '—');
        $('#pfCiudad').text(p.ciudad ? p.ciudad.nombre : '—');

        if (yaDocente) {
            $('#yaDocenteBox').show();
        } else {
            $('#yaDocenteBox').hide();
        }

        $('#personaFound').slideDown(300);
        $('#btnLimpiarBusqueda').show();
    }

    function abrirModalRegistro() {
        resetFormRegistro();
        if (personaEncontrada) {
            const p = personaEncontrada;
            $('#rCarnet').val(p.carnet).prop('readonly', true);
            $('#rNombres').val(p.nombres || '').prop('readonly', true);
            $('#rApPaterno').val(p.apellido_paterno || '').prop('readonly', true);
            $('#rApMaterno').val(p.apellido_materno || '').prop('readonly', true);
            $('#rCorreo').val(p.correo || '').prop('readonly', true);
            $('#rCelular').val(p.celular || '').prop('readonly', true);
            $('#rTelefono').val(p.telefono || '').prop('readonly', true);
            $('#rExpedido').val(p.expedido || '').prop('readonly', true);
            $('#rFechaNacimiento').val(p.fecha_nacimiento || '').prop('readonly', true);
            $('#rSexo').val(p.sexo || '').prop('disabled', true);
            $('#rEstadoCivil').val(p.estado_civil || '').prop('disabled', true);

            $.post('{{ route("admin.docentes.buscarCarnet") }}', { _token: CSRF, carnet: p.carnet })
                .done(function (r) {
                    if (r.ya_docente) {
                        $('#registroYaDocente').show();
                        $('#btnGuardarDocente').prop('disabled', true).html('<i class="ri-error-warning-line"></i> Ya es docente');
                    } else {
                        $('#registroYaDocente').hide();
                        $('#btnGuardarDocente').prop('disabled', false).html('<i class="ri-save-line"></i> Registrar Docente');
                    }
                });

            $('#modalRegistroTitle').html('<i class="ri-user-check-line"></i> Registrar como Docente — ' + esc(p.carnet));
        } else {
            $('#modalRegistroTitle').html('<i class="ri-teacher-line"></i> Registrar Docente');
        }

        openModal('modalRegistro');
    }

    function guardarDocente() {
        const carnet = $('#rCarnet').val().trim();
        const nombres = $('#rNombres').val().trim();
        
        if (!carnet || carnet.length < 3) {
            toast('error', 'El carnet es obligatorio y debe tener al menos 3 caracteres.');
            return;
        }
        if (!nombres || nombres.length < 2) {
            toast('error', 'El nombre es obligatorio.');
            return;
        }

        setBtnLoading('#btnGuardarDocente', true, 'Guardando…');
        
        $.post('{{ route("admin.docentes.guardarPersona") }}', {
            _token: CSRF,
            carnet: $('#rCarnet').val().trim(),
            expedido: $('#rExpedido').val().trim(),
            nombres: $('#rNombres').val().trim(),
            apellido_paterno: $('#rApPaterno').val().trim(),
            apellido_materno: $('#rApMaterno').val().trim(),
            sexo: $('#rSexo').val(),
            estado_civil: $('#rEstadoCivil').val(),
            fecha_nacimiento: $('#rFechaNacimiento').val() || '',
            correo: $('#rCorreo').val().trim(),
            celular: $('#rCelular').val().trim(),
            telefono: $('#rTelefono').val().trim(),
        })
        .done(function (r) {
            $.post('{{ route("admin.docentes.registrar") }}', {
                _token: CSRF,
                persona_id: r.data.id
            })
            .done(function (r2) {
                toast('success', r2.message);
                closeModal('modalRegistro');
                tabla.ajax.reload(null, false);
                limpiarBusqueda();
            })
            .fail(function (xhr) {
                const msg = xhr.responseJSON?.message || 'Error al registrar como docente.';
                toast('error', msg);
            });
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                const errs = xhr.responseJSON.errors || {};
                if (errs.carnet) toast('error', errs.carnet[0]);
                else if (errs.nombres) toast('error', errs.nombres[0]);
                else if (errs.correo) toast('error', errs.correo[0]);
            } else {
                toast('error', 'Error al registrar. Intente nuevamente.');
            }
        })
        .always(function () {
            setBtnLoading('#btnGuardarDocente', false, '<i class="ri-save-line"></i> Registrar Docente');
        });
    }

    function eliminarDocente(id) {
        setBtnLoading('#btnConfirmarEliminar', true, '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
        $.ajax({ url: '/admin/docentes/' + id, type: 'DELETE', data: { _token: CSRF } })
            .done(function (r) {
                closeModal('modalEliminar');
                tabla.ajax.reload(null, false);
                toast('success', r.message);
            })
            .fail(function (xhr) {
                const msg = xhr.responseJSON?.message || 'No se pudo eliminar.';
                toast('error', msg);
            })
            .always(function () {
                setBtnLoading('#btnConfirmarEliminar', false, '<i class="ri-delete-bin-line"></i> Eliminar');
                idEliminar = null;
            });
    }

    function limpiarBusqueda() {
        $('#searchCarnet').val('');
        $('#personaFound').slideUp(200);
        $('#btnLimpiarBusqueda').hide();
        personaEncontrada = null;
    }

    function resetFormRegistro() {
        $('#formRegistro')[0].reset();
        $('#rDepto').val('');
        $('#registroYaDocente').hide();
        $('#btnGuardarDocente').prop('disabled', false).html('<i class="ri-save-line"></i> Registrar Docente');
        $('#modalRegistroTitle').html('<i class="ri-teacher-line"></i> Registrar Docente');
        
        ['rCarnet','rNombres','rApPaterno','rApMaterno','rCorreo','rCelular','rTelefono','rExpedido','rFechaNacimiento'].forEach(function (id) {
            $('#' + id).prop('readonly', false);
        });
        ['rSexo','rEstadoCivil'].forEach(function (id) {
            $('#' + id).prop('disabled', false);
        });
    }

    function crearCuentas(id) {
        if (!confirm('¿Crear cuenta del sistema para este docente?')) return;
        $.post('/admin/docentes/' + id + '/crear-cuentas', { _token: CSRF })
            .done(function (r) {
                toast('success', r.message);
                mostrarCredenciales(r.data);
                tabla.ajax.reload(null, false);
            })
            .fail(function (xhr) {
                toast('error', xhr.responseJSON?.message || 'Error al crear la cuenta.');
            });
    }

    function mostrarCredenciales(data) {
        const titulo = document.getElementById('modalCredencialesTitle') || { innerHTML: '' };
        titulo.innerHTML = '<i class="ri-key-2-line"></i> Credenciales Generadas';
        let html = '';

        if (data.sistema) {
            html += '<p class="doc-cred-label">Acceso al Sistema</p>';
            html += credRow('Usuario', data.sistema.username);
            html += credRow('Correo', data.sistema.email);
            html += credRow('Contraseña', data.sistema.password);
        }

        document.getElementById('credencialesContent').innerHTML = html;
        openModal('modalCredenciales');
    }

    function credRow(label, valor) {
        const v = esc(valor);
        return '<div class="mt-2">'
            + '<label class="doc-cred-label">' + label + '</label>'
            + '<div class="doc-cred-value"><span>' + v + '</span>'
            + '<button class="doc-cred-copy" onclick="copiarCredencial(\'' + v + '\')" title="Copiar"><i class="ri-file-copy-line"></i></button>'
            + '</div></div>';
    }

    window.copiarCredencial = function (texto) {
        navigator.clipboard.writeText(texto).then(function () {
            toast('success', 'Copiado al portapapeles.');
        });
    };

    function generarPassword(carnet) {
        const digits = carnet.replace(/\D/g, '');
        return digits.length >= 7 ? digits : 'innova' + digits;
    }

    $(document).on('click', '.btn-whatsapp-cuenta', function(e) {
        e.stopPropagation();
        const btn = $(this);
        const celular = btn.data('celular');
        const nombre = btn.data('nombre');
        const username = btn.data('username');
        const password = btn.data('password');

        const mensaje = '*¡Bienvenido/a como Docente!*\n\n' +
            'Estimado/a ' + nombre + ',\n\n' +
            'Le proporcionamos sus datos de acceso al sistema:\n\n' +
            '*Usuario:* ' + username + '\n' +
            '*Contraseña:* ' + password + '\n\n' +
            '*Área Académica Innova-Ciencia-Virtual*';

        const url = 'https://wa.me/' + celular + '?text=' + encodeURIComponent(mensaje);
        window.open(url, '_blank');
    });

    function setBtnLoading(sel, loading, labelHtml) {
        const btn = document.querySelector(sel);
        if (!btn) return;
        btn.disabled = loading;
        if (loading) {
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

    function esc(str) {
        return String(str || '')
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function toast(tipo, mensaje) {
        const iconMap = { success: 'ri-check-double-line', error: 'ri-close-circle-line', warning: 'ri-alert-line' };
        const el = document.createElement('div');
        el.className = 'toast-notify ' + tipo;
        el.innerHTML = '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') + '"></i></div>'
            + '<div class="toast-body-text"><span>' + mensaje + '</span></div>'
            + '<button class="toast-close" title="Cerrar"><i class="ri-close-line"></i></button>';
        let c = document.getElementById('toastContainer');
        if (!c) { c = document.createElement('div'); c.id = 'toastContainer'; c.className = 'toast-container'; document.body.appendChild(c); }
        if (c.parentElement !== document.body) document.body.appendChild(c);
        c.style.top = Math.max(20, window.scrollY + 20) + 'px';
        c.appendChild(el);
        el.querySelector('.toast-close').addEventListener('click', function () { removeToast(el); });
        setTimeout(function () { removeToast(el); }, 4500);
    }

    function removeToast(el) {
        el.classList.add('hiding');
        el.addEventListener('animationend', function () { el.remove(); }, { once: true });
    }

    $(document).ready(init);
})();
</script>
@endsection
