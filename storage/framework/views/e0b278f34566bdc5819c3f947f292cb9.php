<?php $__env->startSection('title'); ?> Docentes <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" />
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

.doc-animate { opacity: 0; transform: translateY(16px); animation: docFadeUp 0.55s cubic-bezier(0.16,1,0.3,1) forwards; }
.doc-animate-1 { animation-delay: 0.04s; }
.doc-animate-2 { animation-delay: 0.1s; }
.doc-animate-3 { animation-delay: 0.18s; }
@keyframes docFadeUp { to { opacity: 1; transform: translateY(0); } }

.doc-page { position: relative; min-height: 100%; }
.doc-page::before { content: ''; position: fixed; inset: 0; z-index: -1; background: var(--doc-bg); }
.doc-page::after { content: ''; position: fixed; inset: 0; z-index: -1; opacity: 0.02; background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E"); background-size: 200px 200px; pointer-events: none; }

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

.doc-search-card { background: var(--doc-surface); border: 1px solid var(--doc-border); border-radius: 16px; box-shadow: var(--doc-shadow-sm); padding: 1.4rem; margin-bottom: 1.4rem; }
.doc-search-row { display: flex; align-items: flex-end; gap: 0.85rem; flex-wrap: wrap; }
.doc-search-field { flex: 1; min-width: 200px; }
.doc-search-field label { font-weight: 600; font-size: 0.8rem; color: var(--doc-text); margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.3rem; }
.doc-search-field label i { color: var(--doc-primary); }
.doc-search-field .form-control { background: var(--doc-surface-alt); border: 1.5px solid var(--doc-border); border-radius: 10px; padding: 0.6rem 0.95rem; font-size: 0.88rem; color: var(--doc-text); font-weight: 500; transition: border-color 0.2s, box-shadow 0.2s, background 0.2s; font-family: 'Plus Jakarta Sans', sans-serif; }
.doc-search-field .form-control:focus { border-color: var(--doc-primary); box-shadow: 0 0 0 3.5px rgba(252,123,4,0.1); background: #fff; outline: none; }
.doc-search-field .form-control::placeholder { color: #b8b2aa; }

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

.doc-section-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.6px; color: var(--doc-text-secondary); border-bottom: 1.5px solid var(--doc-border); padding-bottom: 0.3rem; margin: 1.2rem 0 0.9rem; display: flex; align-items: center; gap: 0.4rem; }
.doc-section-label:first-child { margin-top: 0; }
.doc-section-label i { font-size: 0.82rem; color: var(--doc-primary); }

.doc-badge-active { display: inline-flex; align-items: center; gap: 0.22rem; background: var(--doc-success-bg); color: var(--doc-success); border: 1px solid var(--doc-success-border); border-radius: 20px; padding: 0.18rem 0.55rem; font-size: 0.7rem; font-weight: 700; }
.doc-badge-inactive { display: inline-flex; align-items: center; gap: 0.22rem; background: rgba(150,150,150,0.06); color: var(--doc-text-muted); border: 1px solid rgba(150,150,150,0.15); border-radius: 20px; padding: 0.18rem 0.55rem; font-size: 0.7rem; font-weight: 600; }

.doc-card { background: var(--doc-surface); border: 1px solid var(--doc-border); border-radius: 16px; overflow: hidden; box-shadow: var(--doc-shadow-sm); transition: box-shadow 0.3s; }
.doc-card:hover { box-shadow: var(--doc-shadow-md); }
.doc-card-header { padding: 1rem 1.3rem; border-bottom: 1px solid var(--doc-border-light); background: linear-gradient(135deg, #ffffff, #fdf8f2); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
.doc-card-header-left { display: flex; align-items: center; gap: 0.8rem; }
.doc-card-header-icon { width: 36px; height: 36px; background: linear-gradient(135deg, rgba(252,123,4,0.1), rgba(252,123,4,0.04)); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.doc-card-header-icon i { color: var(--doc-primary); font-size: 1rem; }
.doc-card-title { font-size: 0.92rem; font-weight: 600; color: var(--doc-text); margin: 0; letter-spacing: -0.01em; }
.doc-card-subtitle { font-size: 0.76rem; color: var(--doc-text-secondary); margin: 2px 0 0; font-weight: 400; }
.doc-card-body { padding: 0; }

.doc-table { width: 100% !important; border-collapse: collapse; margin: 0 !important; }
.doc-table thead th { background: var(--doc-surface-alt); color: var(--doc-text); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.8rem 1rem; border-bottom: 2px solid var(--doc-border); white-space: nowrap; font-family: 'Outfit', sans-serif; }
.doc-table tbody td { padding: 0.8rem 1rem; font-size: 0.85rem; color: var(--doc-text); border-bottom: 1px solid var(--doc-border-light); vertical-align: middle; }
.doc-table tbody tr { transition: background 0.2s, transform 0.15s; }
.doc-table tbody tr:last-child td { border-bottom: none; }
.doc-table tbody tr:hover td { background: rgba(252,123,4,0.03); }
.doc-table tbody tr:hover { transform: translateX(3px); }

.doc-carnet-cell { display: flex; flex-direction: column; gap: 2px; }
.doc-carnet-num { font-weight: 700; font-size: 0.85rem; color: var(--doc-text); letter-spacing: 0.01em; }
.doc-carnet-exp { font-size: 0.67rem; color: var(--doc-text-muted); font-weight: 500; }

.doc-name-cell { display: flex; align-items: center; gap: 0.65rem; }
.doc-name-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--doc-primary), var(--doc-primary-dark)); color: #fff; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-family: 'Outfit', sans-serif; box-shadow: 0 2px 6px rgba(252,123,4,0.25); letter-spacing: 0.02em; }
.doc-name-info { min-width: 0; flex: 1; }
.doc-name-full { font-weight: 700; font-size: 0.875rem; color: var(--doc-text); line-height: 1.2; }
.doc-name-ape { font-size: 0.78rem; color: var(--doc-text-secondary); font-weight: 400; line-height: 1.2; margin-top: 1px; }
.doc-name-email { font-size: 0.68rem; color: var(--doc-text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; margin-top: 2px; opacity: 0.85; }

.doc-cel-cell { display: flex; align-items: center; gap: 0.35rem; font-size: 0.83rem; color: var(--doc-text); white-space: nowrap; }
.doc-cel-cell i { color: var(--doc-primary); font-size: 0.85rem; flex-shrink: 0; }

.doc-action-cell { display: flex; align-items: center; justify-content: center; gap: 0.28rem; }
.doc-btn-action { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: all 0.2s; background: transparent; color: var(--doc-text-muted); cursor: pointer; }
.doc-btn-action i { font-size: 0.92rem; }
.doc-btn-action:hover { background: var(--doc-border-light); }
.doc-btn-action-view:hover { background: rgba(252,123,4,0.08); color: var(--doc-primary); }
.doc-btn-action-edit:hover { background: rgba(252,123,4,0.08); color: var(--doc-primary); }
.doc-btn-action-cuenta { background: var(--doc-purple-bg); color: var(--doc-purple); border: 1px solid var(--doc-purple-border); }
.doc-btn-action-cuenta:hover { background: rgba(124,58,237,0.14); color: #5b21b6; }
.doc-btn-action-whatsapp { background: var(--doc-whatsapp-bg); color: var(--doc-whatsapp); border: 1px solid var(--doc-whatsapp-border); }
.doc-btn-action-whatsapp:hover { background: rgba(37,211,102,0.14); color: #128C7E; }

/* ── Modal WhatsApp Accesos ── */
.wa-modal-content { background:white;border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18); }
.wa-modal-header { position:relative;display:flex;align-items:flex-start;gap:10px;padding:1.1rem 1.25rem;background:linear-gradient(135deg,#075e54 0%,#128c7e 50%,#25d366 100%);overflow:hidden; }
.wa-modal-header-deco { position:absolute;top:-50px;right:-30px;width:150px;height:150px;background:radial-gradient(circle,rgba(255,255,255,.12) 0%,transparent 70%);border-radius:50%;pointer-events:none; }
.wa-modal-header-body { display:flex;align-items:center;gap:12px;flex:1;min-width:0;position:relative;z-index:1; }
.wa-modal-icon { width:42px;height:42px;flex-shrink:0;background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.28);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:white; }
.wa-modal-header-text { flex:1;min-width:0; }
.wa-modal-title { font-size:.95rem;font-weight:700;color:white;margin:0 0 2px; }
.wa-modal-subtitle { font-size:.73rem;color:rgba(255,255,255,.82);margin:0; }
.wa-modal-close { position:relative;z-index:1;flex-shrink:0;width:30px;height:30px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:8px;color:white;font-size:1.1rem;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .2s; }
.wa-modal-close:hover { background:rgba(255,255,255,.28); }
.wa-persona-bar { display:flex;align-items:center;gap:14px;padding:1rem 1.25rem;background:#fef3e2;border-bottom:1px solid rgba(154,73,4,.15); }
.wa-persona-avatar { width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#9a4904,#df6a04);display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:white;flex-shrink:0;box-shadow:0 3px 10px rgba(154,73,4,.3); }
.wa-persona-rol { font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9a4904;display:flex;align-items:center;gap:4px;margin-bottom:2px; }
.wa-persona-nombre { font-size:.95rem;font-weight:700;color:#7c3c00; }
.wa-modal-body { padding:1.1rem 1.25rem; }
.wa-preview-label { display:flex;align-items:center;gap:5px;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#64748b;margin-bottom:.6rem; }
.wa-preview-label i { color:#25d366; }
.wa-bubble-wrap { background:#e9f5fb;border-radius:12px;padding:.85rem 1rem .6rem;position:relative;margin-bottom:.9rem; }
.wa-bubble { background:white;border-radius:0 10px 10px 10px;padding:.75rem 1rem;box-shadow:0 1px 4px rgba(0,0,0,.08);display:flex;flex-direction:column;gap:.45rem;position:relative; }
.wa-bubble::before { content:'';position:absolute;top:0;left:-8px;width:0;height:0;border-top:8px solid white;border-left:8px solid transparent; }
.wa-bubble-row { display:flex;align-items:baseline;gap:6px;font-size:.85rem;line-height:1.4; }
.wa-bubble-key { font-weight:700;color:#1e293b;white-space:nowrap;flex-shrink:0; }
.wa-bubble-val { color:#334155; }
.wa-mono { font-family:'Courier New',monospace;font-size:.82rem; }
.wa-pass { background:#f0fdf4;border:1px solid #86efac;border-radius:5px;padding:1px 8px;font-weight:600;color:#15803d; }
.wa-bubble-tick { text-align:right;margin-top:.3rem;font-size:.82rem;color:#34b7f1; }
.wa-note { display:flex;align-items:flex-start;gap:.6rem;padding:.65rem .9rem;background:#fffbeb;border:1px solid rgba(245,158,11,.25);border-radius:8px;font-size:.78rem;color:#92400e;line-height:1.55; }
.wa-note-icon { width:22px;height:22px;background:rgba(154,73,4,.12);border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:.85rem;color:#9a4904;flex-shrink:0; }
.wa-modal-footer { display:flex;align-items:center;justify-content:space-between;gap:.5rem;padding:.85rem 1.25rem;background:#f8fafc;border-top:1px solid #e2e8f0; }
.wa-btn-reset { display:inline-flex;align-items:center;gap:.35rem;padding:.45rem 1rem;background:white;border:1.5px solid #e2e8f0;border-radius:8px;font-size:.8rem;font-weight:600;color:#475569;cursor:pointer;transition:all .2s;font-family:inherit; }
.wa-btn-reset:hover { border-color:#94a3b8;color:#334155; }
.wa-btn-send { display:inline-flex;align-items:center;gap:.4rem;padding:.45rem 1.2rem;background:linear-gradient(135deg,#25d366,#128c7e);border:none;border-radius:8px;color:white;font-size:.82rem;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(37,211,102,.3);transition:all .2s;font-family:inherit; }
.wa-btn-send:hover { background:linear-gradient(135deg,#1da851,#0d6e60);transform:translateY(-1px);box-shadow:0 6px 16px rgba(37,211,102,.35); }
.doc-btn-action-delete:hover { background: var(--doc-danger-bg); color: var(--doc-danger); }

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

.doc-modal-form .modal-body { max-height: calc(100vh - 200px); overflow-y: auto; }
.doc-modal-form .modal-content { max-height: calc(100vh - 50px); display: flex; flex-direction: column; }
.doc-modal-section-header { display: flex; align-items: center; gap: 0.45rem; padding: 0.6rem 0 0.45rem; margin-bottom: 0.9rem; border-bottom: 2px solid var(--doc-primary); }
.doc-modal-section-header i { color: var(--doc-primary); font-size: 0.95rem; }
.doc-modal-section-header span { font-size: 0.76rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--doc-text); font-family: 'Outfit', sans-serif; }

.doc-label { font-size: 0.8rem; font-weight: 600; color: var(--doc-text); margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.3rem; }
.doc-label i { color: var(--doc-primary); font-size: 0.88rem; }
.doc-req { color: var(--doc-danger); font-weight: 700; }
.doc-field .form-control, .doc-field .form-select { border: 1.5px solid var(--doc-border); border-radius: 10px; padding: 0.52rem 0.85rem; font-size: 0.85rem; color: var(--doc-text); background: var(--doc-surface-alt); transition: border-color 0.2s, box-shadow 0.2s, background 0.2s; font-family: 'Plus Jakarta Sans', sans-serif; }
.doc-field .form-control:focus, .doc-field .form-select:focus { border-color: var(--doc-primary); box-shadow: 0 0 0 3px rgba(252,123,4,0.1); background: #fff; outline: none; }
.doc-field .form-control::placeholder { color: #b8b2aa; font-weight: 400; }
.doc-field .form-control.is-valid { border-color: var(--doc-success); background: #f4faf6; }
.doc-field .form-control.is-invalid { border-color: var(--doc-danger); background: #fef4f4; }

.doc-validation-icon { position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%); font-size: 1rem; pointer-events: none; opacity: 0; transition: opacity 0.2s; }
.doc-validation-icon.valid, .doc-validation-icon.invalid { opacity: 1; }
.doc-validation-icon.valid i { color: var(--doc-success); }
.doc-validation-icon.invalid i { color: var(--doc-danger); }
.doc-feedback { font-size: 0.76rem; margin-top: 0.3rem; min-height: 1rem; display: flex; align-items: center; gap: 0.3rem; transition: color 0.2s; }
.doc-feedback.error { color: var(--doc-danger); }
.doc-feedback.success { color: var(--doc-success); }

.doc-photo-wrap { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; }
.doc-photo-circle { width: 100px; height: 100px; border-radius: 50%; overflow: hidden; border: 3px solid var(--doc-border); cursor: pointer; transition: border-color 0.25s, box-shadow 0.25s; position: relative; }
.doc-photo-circle:hover { border-color: var(--doc-primary); box-shadow: 0 0 0 4px rgba(252,123,4,0.1); }
.doc-photo-circle img { width: 100%; height: 100%; object-fit: cover; }
.doc-photo-circle .doc-photo-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.35); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.25s; border-radius: 50%; }
.doc-photo-circle:hover .doc-photo-overlay { opacity: 1; }
.doc-photo-circle .doc-photo-overlay i { color: #fff; font-size: 1.4rem; }
.doc-photo-hint { font-size: 0.72rem; color: var(--doc-text-muted); }

.doc-delete-box { text-align: center; padding: 0.4rem 0; }
.doc-delete-icon { width: 68px; height: 68px; border-radius: 50%; background: var(--doc-danger-bg); border: 2px solid var(--doc-danger-border); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; transition: transform 0.3s; }
.doc-delete-icon i { font-size: 1.8rem; color: var(--doc-danger); }
.doc-delete-box:hover .doc-delete-icon { transform: scale(1.05); }
.doc-delete-msg { font-size: 1rem; font-weight: 700; color: var(--doc-text); margin-bottom: 0.25rem; }
.doc-delete-name { font-size: 0.88rem; color: var(--doc-text); margin-bottom: 0.5rem; }
.doc-delete-name strong { color: var(--doc-primary); }
.doc-delete-warn { font-size: 0.76rem; color: var(--doc-text-secondary); margin-bottom: 0; display: flex; align-items: center; justify-content: center; gap: 0.3rem; }
.doc-delete-warn i { color: var(--doc-warning); font-size: 0.85rem; }

.doc-cred-alert { background: rgba(232,160,48,0.08); border: 1px solid rgba(232,160,48,0.2); border-radius: 10px; padding: 0.65rem 0.95rem; display: flex; gap: 0.55rem; align-items: flex-start; font-size: 0.8rem; margin-bottom: 0.9rem; }
.doc-cred-alert i { color: var(--doc-warning); font-size: 1.05rem; flex-shrink: 0; margin-top: 1px; }
.doc-cred-alert span { color: var(--doc-text); }
.doc-cred-label { font-size: 0.66rem; font-weight: 600; text-transform: uppercase; color: var(--doc-text-secondary); display: block; margin-bottom: 0.22rem; font-family: 'Outfit', sans-serif; }
.doc-cred-value { background: var(--doc-surface-alt); border: 1px solid var(--doc-border); border-radius: 8px; padding: 0.45rem 0.7rem; font-family: 'Plus Jakarta Sans', monospace; font-size: 0.88rem; font-weight: 600; color: var(--doc-text); display: flex; justify-content: space-between; align-items: center; }
.doc-cred-copy { background: none; border: none; cursor: pointer; color: var(--doc-text-muted); padding: 0 0 0 0.45rem; font-size: 0.95rem; transition: color 0.15s; }
.doc-cred-copy:hover { color: var(--doc-primary); }

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

.doc-table-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
.doc-table-wrap table.dataTable { width: 100% !important; }
.doc-table-wrap table.dataTable td { white-space: normal !important; word-break: break-word; }

table.dataTable > tbody > tr.child { background: rgba(252,123,4,0.02); }
table.dataTable > tbody > tr.child td { padding: 0.75rem 1rem !important; }
table.dataTable > tbody > tr.child ul.dtr-details { margin: 0; padding: 0; }
table.dataTable > tbody > tr.child ul.dtr-details > li { border-bottom: 1px solid var(--doc-border-light); padding: 0.5rem 0; display: flex; align-items: flex-start; gap: 0.5rem; }
table.dataTable > tbody > tr.child ul.dtr-details > li:last-child { border-bottom: none; }
table.dataTable > tbody > tr.child span.dtr-title { font-weight: 700; color: var(--doc-text); min-width: 100px; font-family: 'Outfit', sans-serif; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.04em; flex-shrink: 0; padding-top: 1px; }
table.dataTable > tbody > tr.child span.dtr-data { font-size: 0.85rem; color: var(--doc-text); }
table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control { cursor: pointer; }
table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control::before { background: var(--doc-primary) !important; border: none !important; box-shadow: 0 2px 6px rgba(252,123,4,0.3) !important; width: 18px !important; height: 18px !important; line-height: 18px !important; font-size: 0.75rem !important; }

.doc-estudio-row { display:flex; align-items:center; gap:.5rem; padding:.6rem .75rem; border:1px solid var(--doc-border); border-radius:10px; margin-bottom:.4rem; background:#fff; transition:box-shadow .2s; }
.doc-estudio-row:hover { box-shadow:var(--doc-shadow-sm); }
.doc-estudio-principal { flex-shrink:0; width:26px; height:26px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.8rem; cursor:pointer; transition:all .2s; }
.doc-estudio-principal.is-principal { background:linear-gradient(135deg,var(--doc-primary),var(--doc-primary-dark)); color:#fff; box-shadow:0 2px 6px rgba(252,123,4,.3); }
.doc-estudio-principal.not-principal { background:#f0ece8; color:var(--doc-text-muted); }
.doc-estudio-principal.not-principal:hover { background:rgba(252,123,4,.1); color:var(--doc-primary); }
.doc-estudio-info { flex:1; min-width:0; }
.doc-estudio-grado { font-size:.82rem; font-weight:700; color:var(--doc-text); }
.doc-estudio-sub { font-size:.72rem; color:var(--doc-text-muted); margin-top:.1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.doc-estudio-estado { font-size:.68rem; font-weight:600; padding:.15rem .5rem; border-radius:20px; flex-shrink:0; }
.doc-estudio-estado.concluido { background:var(--doc-success-bg); color:var(--doc-success); }
.doc-estudio-estado.en-desarrollo { background:rgba(240,160,48,.1); color:var(--doc-warning); }
.doc-estudio-del { background:none; border:none; cursor:pointer; color:var(--doc-text-muted); font-size:.95rem; padding:.2rem; border-radius:6px; transition:all .2s; flex-shrink:0; }
.doc-estudio-del:hover { background:var(--doc-danger-bg); color:var(--doc-danger); }

@media (max-width: 992px) {
    .doc-header-inner { flex-direction: column; align-items: flex-start; }
    .doc-header-right { width: 100%; }
    .doc-modal-form .modal-dialog { max-width: 100% !important; margin: 0.5rem; }
    .doc-name-email { max-width: 160px; }
}
@media (max-width: 768px) {
    .doc-search-row { flex-direction: column; }
    .doc-search-field { min-width: 100%; }
    .doc-card-header { flex-direction: column; align-items: flex-start; }
    .doc-table thead th, .doc-table tbody td { padding: 0.48rem 0.55rem; font-size: 0.76rem; }
    .doc-btn-search, .doc-btn-register { width: 100%; justify-content: center; }
    .doc-name-avatar { width: 32px; height: 32px; font-size: 0.68rem; }
    .doc-action-cell { gap: 0.15rem; }
    .doc-btn-action { width: 30px; height: 30px; }
    .doc-card .dataTables_wrapper .dataTables_filter input { min-width: 100px; }
}
@media (max-width: 576px) {
    .doc-table thead th, .doc-table tbody td { padding: 0.38rem 0.45rem; font-size: 0.7rem; }
    .doc-table thead th { font-size: 0.63rem; }
    .doc-name-avatar { width: 28px; height: 28px; font-size: 0.62rem; }
    .doc-name-full { font-size: 0.82rem; }
    .doc-name-ape { display: none; }
    .doc-carnet-num { font-size: 0.78rem; }
    .doc-btn-action { width: 28px; height: 28px; }
    .doc-btn-action i { font-size: 0.82rem; }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
                                    <th data-priority="2">Carnet</th>
                                    <th data-priority="1">Docente</th>
                                    <th data-priority="5">Celular</th>
                                    <th data-priority="3" class="text-center">Cuentas</th>
                                    <th data-priority="1" class="text-center">Acciones</th>
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


<div class="modal fade doc-modal-form" id="modalRegistro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistroTitle"><i class="ri-teacher-line"></i> Registrar Docente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formRegistro" novalidate autocomplete="off" enctype="multipart/form-data">
                <div class="modal-body">
                    <div id="registroYaDocente" class="doc-ya-docente" style="display:none;">
                        <i class="ri-checkbox-circle-fill"></i>
                        <span>Esta persona ya está registrada como docente.</span>
                    </div>

                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="doc-photo-wrap">
                                <div class="doc-photo-circle" onclick="document.getElementById('fotografiaRegistro').click()">
                                    <img id="previewFotografiaRegistro" src="<?php echo e(URL::asset('build/images/users/avatar-1.jpg')); ?>" alt="Foto">
                                    <div class="doc-photo-overlay"><i class="ri-camera-line"></i></div>
                                    <input type="file" id="fotografiaRegistro" name="fotografia" accept="image/*" style="display:none;" onchange="previewImage(this, 'previewFotografiaRegistro')">
                                </div>
                                <span class="doc-photo-hint">Click para cambiar foto</span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="doc-modal-section-header"><i class="ri-id-card-line"></i><span>Datos de Identidad</span></div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="doc-label">Carnet <span class="doc-req">*</span></label>
                            <div class="doc-field" style="position:relative;">
                                <input type="text" class="form-control" id="rCarnet" placeholder="Ej: 12345678" maxlength="20" autocomplete="off">
                                <span class="doc-validation-icon" id="iconRCarnet"></span>
                            </div>
                            <div class="doc-feedback" id="fbRCarnet"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="doc-label">Expedido</label>
                            <input type="text" class="form-control" id="rExpedido" placeholder="Ej: LP" maxlength="10" autocomplete="off">
                        </div>
                        <div class="col-md-5">
                            <label class="doc-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="rFechaNacimiento">
                        </div>
                        <div class="col-md-6">
                            <label class="doc-label">Nombres <span class="doc-req">*</span></label>
                            <div class="doc-field" style="position:relative;">
                                <input type="text" class="form-control" id="rNombres" placeholder="Ej: Juan Carlos" maxlength="100" autocomplete="off">
                                <span class="doc-validation-icon" id="iconRNombres"></span>
                            </div>
                            <div class="doc-feedback" id="fbRNombres"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="doc-label">Ap. Paterno</label>
                            <div class="doc-field">
                                <input type="text" class="form-control" id="rApPaterno" placeholder="García" maxlength="80" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="doc-label">Ap. Materno</label>
                            <div class="doc-field">
                                <input type="text" class="form-control" id="rApMaterno" placeholder="López" maxlength="80" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="doc-feedback" id="fbRApellidos"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="doc-label">Sexo <span class="doc-req">*</span></label>
                            <select class="form-select" id="rSexo">
                                <option value="">— Seleccione —</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="doc-label">Estado Civil <span class="doc-req">*</span></label>
                            <select class="form-select" id="rEstadoCivil">
                                <option value="">— Seleccione —</option>
                                <option value="Soltero/a">Soltero/a</option>
                                <option value="Casado/a">Casado/a</option>
                                <option value="Divorciado/a">Divorciado/a</option>
                                <option value="Viudo/a">Viudo/a</option>
                                <option value="Unión Libre">Unión Libre</option>
                            </select>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-5">
                            <label class="doc-label">Departamento</label>
                            <select class="form-select" id="rDepto">
                                <option value="">— Seleccione —</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="doc-label">Ciudad <span class="doc-req">*</span></label>
                            <select class="form-select" id="rCiudad" disabled>
                                <option value="">— Seleccione depto. —</option>
                            </select>
                        </div>
                    </div>

                    
                    <div class="doc-modal-section-header mt-4"><i class="ri-phone-line"></i><span>Datos de Contacto</span></div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="doc-label">Correo Electrónico <span class="doc-req">*</span></label>
                            <div class="doc-field" style="position:relative;">
                                <input type="email" class="form-control" id="rCorreo" placeholder="correo@dominio.com" maxlength="150" autocomplete="off">
                                <span class="doc-validation-icon" id="iconRCorreo"></span>
                            </div>
                            <div class="doc-feedback" id="fbRCorreo"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="doc-label">Celular <span class="doc-req">*</span></label>
                            <input type="text" class="form-control" id="rCelular" placeholder="70000000" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label class="doc-label">Teléfono</label>
                            <input type="text" class="form-control" id="rTelefono" placeholder="2000000" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-12">
                            <label class="doc-label">Dirección</label>
                            <input type="text" class="form-control" id="rDireccion" placeholder="Av. 6 de Agosto N° 123" maxlength="200" autocomplete="off">
                        </div>
                    </div>

                    
                    <div class="doc-modal-section-header mt-4"><i class="ri-graduation-cap-line"></i><span>Estudios Académicos</span></div>
                    <div id="registroEstudiosContainer">
                        <div id="registroEstudiosEmpty" style="text-align:center;padding:1.2rem 1rem;border:2px dashed rgba(252,123,4,.2);border-radius:12px;background:rgba(252,123,4,.02);margin-bottom:.5rem;">
                            <div style="width:40px;height:40px;border-radius:12px;background:rgba(252,123,4,.08);display:flex;align-items:center;justify-content:center;margin:0 auto .5rem;">
                                <i class="ri-graduation-cap-line" style="font-size:1.2rem;color:rgba(252,123,4,.5);"></i>
                            </div>
                            <div style="font-size:.8rem;font-weight:600;color:#64748b;margin-bottom:.2rem;">Sin estudios agregados</div>
                            <div style="font-size:.72rem;color:#94a3b8;">Presiona <strong style="color:var(--doc-primary);">Agregar Estudio</strong> para incluir formación académica</div>
                        </div>
                        <div id="registroEstudiosList" style="display:flex;flex-direction:column;gap:.5rem;"></div>
                        <button type="button" id="btnAddRegistroEstudio"
                            style="margin-top:.65rem;display:inline-flex;align-items:center;gap:.35rem;font-size:.8rem;font-weight:600;color:var(--doc-primary);background:rgba(252,123,4,.06);border:1px dashed rgba(252,123,4,.3);border-radius:8px;padding:.4rem .85rem;cursor:pointer;transition:all .2s;">
                            <i class="ri-add-line"></i> Agregar Estudio
                        </button>
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


<div class="modal fade doc-modal-form" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-pencil-line"></i> Editar Docente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditar" novalidate autocomplete="off" enctype="multipart/form-data">
                <input type="hidden" id="editId">
                <div class="modal-body">
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="doc-photo-wrap">
                                <div class="doc-photo-circle" onclick="document.getElementById('fotografiaEditar').click()">
                                    <img id="previewFotografiaEditar" src="<?php echo e(URL::asset('build/images/users/avatar-1.jpg')); ?>" alt="Foto">
                                    <div class="doc-photo-overlay"><i class="ri-camera-line"></i></div>
                                    <input type="file" id="fotografiaEditar" name="fotografia" accept="image/*" style="display:none;" onchange="previewImage(this, 'previewFotografiaEditar')">
                                </div>
                                <span class="doc-photo-hint">Click para cambiar foto</span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="doc-modal-section-header"><i class="ri-id-card-line"></i><span>Datos de Identidad</span></div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="doc-label">Carnet <span class="doc-req">*</span></label>
                            <div class="doc-field" style="position:relative;">
                                <input type="text" class="form-control" id="editCarnet" maxlength="20" autocomplete="off">
                                <span class="doc-validation-icon" id="iconECarnet"></span>
                            </div>
                            <div class="doc-feedback" id="fbECarnet"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="doc-label">Expedido</label>
                            <input type="text" class="form-control" id="editExpedido" maxlength="10" autocomplete="off">
                        </div>
                        <div class="col-md-5">
                            <label class="doc-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="editFechaNacimiento">
                        </div>
                        <div class="col-md-6">
                            <label class="doc-label">Nombres <span class="doc-req">*</span></label>
                            <div class="doc-field" style="position:relative;">
                                <input type="text" class="form-control" id="editNombres" maxlength="100" autocomplete="off">
                                <span class="doc-validation-icon" id="iconENombres"></span>
                            </div>
                            <div class="doc-feedback" id="fbENombres"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="doc-label">Ap. Paterno</label>
                            <input type="text" class="form-control" id="editApPaterno" maxlength="80" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label class="doc-label">Ap. Materno</label>
                            <input type="text" class="form-control" id="editApMaterno" maxlength="80" autocomplete="off">
                        </div>
                        <div class="col-12">
                            <div class="doc-feedback" id="fbEApellidos"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="doc-label">Sexo</label>
                            <select class="form-select" id="editSexo">
                                <option value="">— Seleccione —</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="doc-label">Estado Civil</label>
                            <select class="form-select" id="editEstadoCivil">
                                <option value="">— Seleccione —</option>
                                <option value="Soltero/a">Soltero/a</option>
                                <option value="Casado/a">Casado/a</option>
                                <option value="Divorciado/a">Divorciado/a</option>
                                <option value="Viudo/a">Viudo/a</option>
                                <option value="Unión Libre">Unión Libre</option>
                            </select>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-5">
                            <label class="doc-label">Departamento</label>
                            <select class="form-select" id="editDepto">
                                <option value="">— Seleccione —</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="doc-label">Ciudad</label>
                            <select class="form-select" id="editCiudad" disabled>
                                <option value="">— Seleccione depto. —</option>
                            </select>
                        </div>
                    </div>

                    
                    <div class="doc-modal-section-header mt-4"><i class="ri-phone-line"></i><span>Datos de Contacto</span></div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="doc-label">Correo Electrónico</label>
                            <div class="doc-field" style="position:relative;">
                                <input type="email" class="form-control" id="editCorreo" maxlength="150" autocomplete="off">
                                <span class="doc-validation-icon" id="iconECorreo"></span>
                            </div>
                            <div class="doc-feedback" id="fbECorreo"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="doc-label">Celular</label>
                            <input type="text" class="form-control" id="editCelular" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label class="doc-label">Teléfono</label>
                            <input type="text" class="form-control" id="editTelefono" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-12">
                            <label class="doc-label">Dirección</label>
                            <input type="text" class="form-control" id="editDireccion" maxlength="200" autocomplete="off">
                        </div>
                    </div>

                    
                    <div class="doc-modal-section-header mt-4"><i class="ri-graduation-cap-line"></i><span>Estudios Académicos</span></div>
                    <div id="editEstudiosContainer">
                        <div id="editEstudiosLoading" class="text-center py-2" style="display:none;">
                            <span class="spinner-border spinner-border-sm" style="color:var(--doc-primary);"></span>
                            <span class="ms-2" style="font-size:.8rem;color:var(--doc-text-muted);">Cargando estudios...</span>
                        </div>
                        <div id="editEstudiosList"></div>
                        <div id="editEstudioFormWrap" style="display:none;margin-top:.75rem;padding:1rem;background:#faf8f5;border:1px solid var(--doc-border);border-radius:12px;">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="doc-label" style="font-size:.75rem;">Grado Académico <span class="doc-req">*</span></label>
                                    <select class="form-select form-select-sm" id="newEstGrado"></select>
                                </div>
                                <div class="col-md-4">
                                    <label class="doc-label" style="font-size:.75rem;">Profesión</label>
                                    <select class="form-select form-select-sm" id="newEstProfesion"></select>
                                </div>
                                <div class="col-md-4">
                                    <label class="doc-label" style="font-size:.75rem;">Universidad</label>
                                    <select class="form-select form-select-sm" id="newEstUniversidad"></select>
                                </div>
                                <div class="col-md-3">
                                    <label class="doc-label" style="font-size:.75rem;">Estado</label>
                                    <select class="form-select form-select-sm" id="newEstEstado">
                                        <option value="Concluido">Concluido</option>
                                        <option value="En Desarrollo">En Desarrollo</option>
                                    </select>
                                </div>
                                <div class="col-md-9 d-flex align-items-end gap-2">
                                    <button type="button" class="doc-btn-submit btn-sm" id="btnGuardarNuevoEstudio" style="padding:.4rem 1rem;font-size:.82rem;">
                                        <i class="ri-check-line"></i> Agregar
                                    </button>
                                    <button type="button" class="doc-btn-cancel btn-sm" id="btnCancelarNuevoEstudio" style="padding:.4rem .75rem;font-size:.82rem;">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="btnAddEstudio" style="margin-top:.75rem;display:inline-flex;align-items:center;gap:.35rem;font-size:.8rem;font-weight:600;color:var(--doc-primary);background:rgba(252,123,4,.06);border:1px dashed rgba(252,123,4,.3);border-radius:8px;padding:.4rem .85rem;cursor:pointer;transition:all .2s;">
                            <i class="ri-add-line"></i> Agregar Estudio
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="doc-btn-cancel" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                    <button type="submit" class="doc-btn-submit" id="btnGuardarEdicion"><i class="ri-save-line"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>


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


<div class="modal fade" id="modalCrearCuentasSistema" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18);">

            
            <div class="modal-header" style="background:linear-gradient(135deg,#3b1900 0%,#7a3b03 50%,#c96004 100%);color:white;padding:1.25rem 1.5rem;border:none;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(10px);flex-shrink:0;">
                        <i class="ri-teacher-line" style="font-size:1.4rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-size:1rem;font-weight:700;letter-spacing:-.01em;color:#fff;">Crear Cuentas de Usuario</h5>
                        <div style="font-size:.73rem;opacity:.75;margin-top:.15rem;letter-spacing:.01em;color:rgba(255,255,255,.85);">Portal del docente + plataforma Moodle</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            
            <div class="modal-body" style="padding:0;">

                
                <div id="sistemaCuentasLoading" class="text-center" style="padding:3rem 1.5rem;">
                    <div class="spinner-border" style="color:#fc7b04;width:2.25rem;height:2.25rem;" role="status"></div>
                    <p class="mt-3 mb-0" style="font-size:.85rem;color:#64748b;font-weight:500;">Verificando cuentas del docente…</p>
                </div>

                
                <div id="sistemaCuentasEmpty" style="display:none;padding:3rem 1.5rem;text-align:center;">
                    <div style="width:68px;height:68px;background:linear-gradient(135deg,rgba(252,123,4,.12),rgba(154,73,4,.05));border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.1rem;border:2px solid rgba(252,123,4,.2);">
                        <i class="ri-shield-check-line" style="font-size:1.9rem;color:#fc7b04;"></i>
                    </div>
                    <h6 style="font-weight:700;color:#1e293b;margin-bottom:.4rem;font-size:.95rem;">¡Todo está al día!</h6>
                    <p style="font-size:.83rem;color:#64748b;margin:0;max-width:300px;margin-inline:auto;line-height:1.6;">Este docente ya tiene cuentas activas en el sistema y en Moodle.</p>
                </div>

                
                <div id="sistemaCuentasList" style="display:none;">
                    <div style="padding:1rem 1.5rem;background:linear-gradient(135deg,rgba(252,123,4,.05),rgba(154,73,4,.03));border-bottom:1px solid rgba(252,123,4,.1);">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div style="display:inline-flex;align-items:center;gap:.45rem;background:rgba(252,123,4,.1);border:1px solid rgba(252,123,4,.2);color:#9a4904;font-size:.8rem;font-weight:700;padding:.3rem .8rem;border-radius:20px;white-space:nowrap;">
                                <i class="ri-user-line"></i>
                                <span id="sistemaCuentasCount">0</span> sin cuenta
                            </div>
                            <p class="mb-0" style="font-size:.78rem;color:#64748b;flex:1;line-height:1.55;">Se creará la cuenta del <strong style="color:#475569;">portal</strong> y de <strong style="color:#475569;">Moodle</strong> con las mismas credenciales.</p>
                        </div>
                    </div>
                    <div style="padding:.75rem 1.25rem 1.25rem;max-height:360px;overflow-y:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:.8rem;" id="tablaCuentasSistema">
                            <thead>
                                <tr style="border-bottom:2px solid #e2e8f0;">
                                    <th style="padding:.65rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;text-align:left;">Docente</th>
                                    <th style="padding:.65rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;text-align:left;">CI</th>
                                    <th style="padding:.65rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;text-align:left;">Usuario sugerido</th>
                                    <th style="padding:.65rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;text-align:left;">Contraseña</th>
                                </tr>
                            </thead>
                            <tbody id="sistemaCuentasTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            
            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;background:#f8fafc;gap:.5rem;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;font-size:.82rem;padding:.4rem 1rem;">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="btnConfirmarCrearCuentasSistema" disabled
                    style="background:linear-gradient(135deg,#fc7b04,#d46604);border:none;border-radius:8px;font-size:.82rem;padding:.4rem 1.15rem;font-weight:600;box-shadow:0 4px 12px rgba(252,123,4,.3);transition:all .2s;">
                    <i class="ri-user-add-line me-1"></i>Crear Cuenta
                </button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="modalWhatsappAccesos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
        <div class="modal-content wa-modal-content">

            
            <div class="wa-modal-header">
                <div class="wa-modal-header-deco"></div>
                <div class="wa-modal-header-body">
                    <div class="wa-modal-icon">
                        <i class="ri-whatsapp-line"></i>
                    </div>
                    <div class="wa-modal-header-text">
                        <h5 class="wa-modal-title">Enviar Accesos por WhatsApp</h5>
                        <p class="wa-modal-subtitle">Credenciales de acceso — Docente</p>
                    </div>
                </div>
                <button type="button" class="wa-modal-close" data-bs-dismiss="modal" aria-label="Cerrar">
                    <i class="ri-close-line"></i>
                </button>
            </div>

            
            <div class="wa-persona-bar">
                <div class="wa-persona-avatar wa-persona-avatar-doc">
                    <i class="ri-user-star-line"></i>
                </div>
                <div class="wa-persona-info">
                    <div class="wa-persona-rol">
                        <i class="ri-briefcase-line"></i> Docente
                    </div>
                    <div class="wa-persona-nombre" id="waModalNombre">—</div>
                </div>
            </div>

            
            <div class="wa-modal-body">

                
                <div class="wa-preview-label">
                    <i class="ri-message-3-line"></i> Vista previa del mensaje
                </div>
                <div class="wa-bubble-wrap">
                    <div class="wa-bubble">
                        <div class="wa-bubble-row">
                            <span class="wa-bubble-key">Docente:</span>
                            <span id="waModalNombrePreview" class="wa-bubble-val"></span>
                        </div>
                        <div class="wa-bubble-row">
                            <span class="wa-bubble-key">Usuario:</span>
                            <span id="waModalUsuario" class="wa-bubble-val wa-mono"></span>
                        </div>
                        <div class="wa-bubble-row">
                            <span class="wa-bubble-key">Contraseña:</span>
                            <span id="waModalPassword" class="wa-bubble-val wa-mono wa-pass"></span>
                        </div>
                    </div>
                    <div class="wa-bubble-tick">
                        <i class="ri-check-double-line"></i>
                    </div>
                </div>

                
                <div class="wa-note">
                    <div class="wa-note-icon"><i class="ri-information-line"></i></div>
                    <p class="mb-0">Si el docente cambió su contraseña en Moodle, usa <strong>Restablecer</strong> para sincronizarla al valor original antes de enviar.</p>
                </div>

                <input type="hidden" id="waModalCelular">
                <input type="hidden" id="waModalDocenteId">
            </div>

            
            <div class="wa-modal-footer">
                <button type="button" id="waModalBtnReset" class="wa-btn-reset">
                    <i class="ri-refresh-line"></i> Restablecer contraseña
                </button>
                <button type="button" id="waModalBtnEnviar" class="wa-btn-send">
                    <i class="ri-whatsapp-line"></i> Enviar por WhatsApp
                </button>
            </div>

        </div>
    </div>
</div>

<div id="toastContainer" class="toast-container"></div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

(function () {
    'use strict';

    let tabla;
    let idEliminar = null;
    let personaEncontrada = null;
    let todasCiudades = [];
    let carnetTimer = null, correoTimer = null;
    let registroEstudioCount = 0;
    const CSRF = '<?php echo e(csrf_token()); ?>';

    function init() {
        cargarSelectores();
        initDataTable();
        bindEvents();
    }

    function cargarSelectores() {
        $.getJSON('/admin/personas/listar-departamentos', function (r) {
            const opts = r.data.map(d => '<option value="' + d.id + '">' + esc(d.nombre) + '</option>').join('');
            $('#rDepto').append(opts);
            $('#editDepto').append(opts);
        });
        $.getJSON('/admin/personas/listar-ciudades', function (r) {
            todasCiudades = r.data;
        });
        $.getJSON('/admin/personas/listar-grados', function(r) {
            const opts = '<option value="">— Grado —</option>' + r.data.map(g => '<option value="'+g.id+'">'+esc(g.nombre)+'</option>').join('');
            $('#newEstGrado').html(opts);
            window._gradosOpts = opts;
        });
        $.getJSON('/admin/personas/listar-profesiones', function(r) {
            const opts = '<option value="">— Profesión —</option>' + r.data.map(p => '<option value="'+p.id+'">'+esc(p.nombre)+'</option>').join('');
            $('#newEstProfesion').html(opts);
            window._profesionesOpts = opts;
        });
        $.getJSON('/admin/personas/listar-universidades', function(r) {
            const opts = '<option value="">— Universidad —</option>' + r.data.map(u => '<option value="'+u.id+'">'+esc(u.nombre+(u.sigla?' ('+u.sigla+')':''))+'</option>').join('');
            $('#newEstUniversidad').html(opts);
            window._universidadesOpts = opts;
        });
    }

    function filtrarCiudades() {
        const deptoId = $('#rDepto').val();
        const $ciudad = $('#rCiudad');
        $ciudad.find('option:not(:first)').remove();
        if (!deptoId) {
            $ciudad.prop('disabled', true).find('option:first').text('— Seleccione depto. —');
            return;
        }
        const filtradas = todasCiudades.filter(function (c) { return c.departamento_id == deptoId; });
        $ciudad.append(filtradas.map(c => '<option value="' + c.id + '">' + esc(c.nombre) + '</option>').join(''));
        $ciudad.prop('disabled', false).find('option:first').text('— Seleccione ciudad —');
    }

    function initDataTable() {
        tabla = $('#tabla-docentes').DataTable({
            ajax: { url: '<?php echo e(route("admin.docentes.listar")); ?>', dataSrc: 'data' },
            ordering: true,
            responsive: true,
            autoWidth: false,
            columns: [
                {
                    data: null, responsivePriority: 2,
                    render: d => {
                        const p = d.persona;
                        if (!p) return '<span class="text-muted">—</span>';
                        let html = '<div class="doc-carnet-cell">'
                            + '<span class="doc-carnet-num">' + esc(p.carnet) + '</span>';
                        if (p.expedido) html += '<span class="doc-carnet-exp">exp. ' + esc(p.expedido) + '</span>';
                        html += '</div>';
                        return html;
                    }
                },
                {
                    data: null, responsivePriority: 1,
                    render: d => {
                        const p = d.persona;
                        if (!p) return '<span class="text-muted">—</span>';
                        const n = esc(p.nombres);
                        const ap = [p.apellido_paterno, p.apellido_materno].filter(Boolean).map(esc).join(' ');
                        const correo = p.correo ? '<div class="doc-name-email">' + esc(p.correo) + '</div>' : '';
                        const initials = ((p.nombres || '').charAt(0) + (p.apellido_paterno || '').charAt(0)).toUpperCase();
                        return '<div class="doc-name-cell">'
                            + '<div class="doc-name-avatar">' + esc(initials) + '</div>'
                            + '<div class="doc-name-info">'
                            + '<div class="doc-name-full">' + n + '</div>'
                            + (ap ? '<div class="doc-name-ape">' + ap + '</div>' : '')
                            + correo
                            + '</div></div>';
                    }
                },
                {
                    data: null, responsivePriority: 5,
                    render: d => {
                        const p = d.persona;
                        return p && p.celular
                            ? '<div class="doc-cel-cell"><i class="ri-phone-line"></i>' + esc(p.celular) + '</div>'
                            : '<span class="text-muted">—</span>';
                    }
                },
                {
                    data: null, className: 'text-center', responsivePriority: 3, width: '120px',
                    render: d => {
                        const sis = d.tiene_cuenta_sistema
                            ? '<span class="doc-badge-active"><i class="ri-check-line"></i>Sistema</span>'
                            : '<span class="doc-badge-inactive"><i class="ri-close-line"></i>Sistema</span>';
                        const mood = d.tiene_cuenta_moodle
                            ? '<span class="doc-badge-active"><i class="ri-check-line"></i>Moodle</span>'
                            : '<span class="doc-badge-inactive"><i class="ri-close-line"></i>Moodle</span>';
                        return '<div style="display:flex;flex-direction:column;gap:4px;align-items:flex-start;">' + sis + mood + '</div>';
                    }
                },
                {
                    data: null, className: 'text-center', responsivePriority: 1,
                    render: d => {
                        const nombre = esc(d.persona ? d.persona.nombres + ' ' + (d.persona.apellido_paterno || '') : 'Docente');
                        const celular = d.persona ? (d.persona.celular || '') : '';
                        const celularLimpio = celular.replace(/\D/g, '');
                        const tieneCuenta = d.tiene_cuenta_sistema;
                        const tieneMoodle = d.tiene_cuenta_moodle;
                        const username = d.usuario_username || '';
                        const carnet = d.persona ? (d.persona.carnet || '') : '';
                        const password = generarPassword(carnet);

                        let btns = '<div class="doc-action-cell">'
                            + '<a class="btn doc-btn-action doc-btn-action-view" href="/admin/docentes/' + d.id + '/detalle" title="Ver docente"><i class="ri-eye-line"></i></a>'
                            + '<button class="btn doc-btn-action doc-btn-action-edit btn-accion-editar" data-id="' + d.id + '" title="Editar docente"><i class="ri-pencil-fill"></i></button>';
                        if (!tieneCuenta || !tieneMoodle) {
                            const titulo = tieneCuenta ? 'Crear cuenta Moodle' : (tieneMoodle ? 'Crear cuenta sistema' : 'Crear cuentas (sistema + Moodle)');
                            btns += '<button class="btn doc-btn-action doc-btn-action-cuenta btn-crear-cuentas" data-id="' + d.id + '" title="' + titulo + '"><i class="ri-user-add-line"></i></button>';
                        }
                        if (celularLimpio.length >= 8 && tieneCuenta && username) {
                            btns += '<button class="btn doc-btn-action doc-btn-action-whatsapp btn-whatsapp-cuenta" '
                                + 'data-celular="' + celularLimpio + '" '
                                + 'data-nombre="' + nombre + '" '
                                + 'data-username="' + username + '" '
                                + 'data-password="' + password + '" '
                                + 'data-docente-id="' + d.id + '" '
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
        $('#formEditar').on('submit', function (e) { e.preventDefault(); guardarEdicion(); });
        $('#rDepto').on('change', filtrarCiudades);
        $('#editDepto').on('change', function () {
            const deptoId = $(this).val();
            const $ciudad = $('#editCiudad');
            $ciudad.find('option:not(:first)').remove();
            if (!deptoId) {
                $ciudad.prop('disabled', true).find('option:first').text('— Seleccione depto. —');
                return;
            }
            const filtradas = todasCiudades.filter(function (c) { return c.departamento_id == deptoId; });
            $ciudad.append(filtradas.map(c => '<option value="' + c.id + '">' + esc(c.nombre) + '</option>').join(''));
            $ciudad.prop('disabled', false).find('option:first').text('— Seleccione ciudad —');
        });

        $('#rCarnet').on('input', function () {
            clearTimeout(carnetTimer);
            const val = this.value.trim();
            if (!val) { return setError('rCarnet','iconRCarnet','fbRCarnet','El carnet es obligatorio.'); }
            if (val.length < 3) { return setError('rCarnet','iconRCarnet','fbRCarnet','Debe tener al menos 3 caracteres.'); }
            setChecking('rCarnet','iconRCarnet','fbRCarnet');
            carnetTimer = setTimeout(function () { verificarCarnetRegistro(val); }, 400);
        });

        $('#rNombres').on('input', function () { validarNombres('rNombres','iconRNombres','fbRNombres'); });
        $('#rApPaterno, #rApMaterno').on('input', validarApellidos);

        $('#rCorreo').on('input', function () {
            clearTimeout(correoTimer);
            const val = this.value.trim();
            if (!val) { return resetField('rCorreo','iconRCorreo','fbRCorreo'); }
            if (!isEmail(val)) { return setError('rCorreo','iconRCorreo','fbRCorreo','Formato de correo inválido.'); }
            setChecking('rCorreo','iconRCorreo','fbRCorreo');
            correoTimer = setTimeout(function () { verificarCorreoRegistro(val); }, 400);
        });

        $(document).on('click', '.btn-accion-eliminar', function () {
            idEliminar = $(this).data('id');
            $('#nombreEliminar').text($(this).data('nombre'));
            openModal('modalEliminar');
        });
        $('#btnConfirmarEliminar').on('click', function () {
            if (idEliminar) eliminarDocente(idEliminar);
        });

        $(document).on('click', '.btn-accion-editar', function () {
            editarDocente($(this).data('id'));
        });

        $(document).on('click', '.btn-crear-cuentas', function () {
            crearCuentas($(this).data('id'));
        });

        $('#btnAddRegistroEstudio').on('click', function() {
            addRegistroEstudioRow();
            syncRegistroEstudiosEmpty();
        });

        $(document).on('click', '.btn-remove-registro-estudio', function() {
            $(this).closest('.registro-estudio-row').remove();
            syncRegistroEstudiosEmpty();
        });

        document.getElementById('modalRegistro').addEventListener('hidden.bs.modal', resetFormRegistro);

        document.getElementById('modalEditar').addEventListener('hidden.bs.modal', function() {
            $('#editEstudiosList').empty();
            $('#editEstudioFormWrap').hide();
            $('#btnAddEstudio').show();
        });

        $('#btnAddEstudio').on('click', function() {
            $('#editEstudioFormWrap').slideDown(200);
            $(this).hide();
            if (window._gradosOpts) $('#newEstGrado').html(window._gradosOpts);
            if (window._profesionesOpts) $('#newEstProfesion').html(window._profesionesOpts);
            if (window._universidadesOpts) $('#newEstUniversidad').html(window._universidadesOpts);
        });

        $('#btnCancelarNuevoEstudio').on('click', function() {
            $('#editEstudioFormWrap').slideUp(200);
            $('#btnAddEstudio').show();
        });

        $('#btnGuardarNuevoEstudio').on('click', function() {
            var personaId = $('#formEditar').data('persona-id');
            var docenteId = $('#formEditar').data('docente-id');
            if (!personaId || !docenteId) return;
            var gradoId = $('#newEstGrado').val();
            if (!gradoId) { toast('warning', 'El grado académico es obligatorio.'); return; }
            var $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
            var estudiosActuales = $('#editEstudiosList .doc-estudio-row').length;
            $.post('/admin/docentes/' + docenteId + '/estudios', {
                _token: CSRF,
                grados_academico_id: gradoId,
                profesione_id: $('#newEstProfesion').val() || '',
                universidade_id: $('#newEstUniversidad').val() || '',
                estado: $('#newEstEstado').val(),
                principal: estudiosActuales === 0 ? 1 : 0
            })
            .done(function(r) {
                toast('success', r.message || 'Estudio agregado.');
                $('#editEstudioFormWrap').slideUp(200);
                $('#btnAddEstudio').show();
                recargarEstudios(docenteId);
            })
            .fail(function(xhr) {
                toast('error', xhr.responseJSON?.message || 'Error al agregar estudio.');
            })
            .always(function() { $btn.prop('disabled', false).html('<i class="ri-check-line"></i> Agregar'); });
        });

        $(document).on('click', '.btn-del-estudio', function() {
            var docenteId = $('#formEditar').data('docente-id');
            var estudioId = $(this).data('estudio-id');
            if (!confirm('¿Eliminar este estudio?')) return;
            $.ajax({ url: '/admin/docentes/' + docenteId + '/estudios/' + estudioId, type: 'DELETE', data: { _token: CSRF } })
                .done(function(r) { toast('success', r.message); recargarEstudios(docenteId); })
                .fail(function() { toast('error', 'Error al eliminar estudio.'); });
        });

        $(document).on('click', '.doc-estudio-principal.not-principal', function() {
            var docenteId = $('#formEditar').data('docente-id');
            var estudioId = $(this).data('estudio-id');
            var personaId = $('#formEditar').data('persona-id');
            var $btn = $(this);
            $btn.prop('disabled', true);
            $.get('<?php echo e(route("admin.docentes.obtener", ["id" => "__ID__"])); ?>'.replace('__ID__', docenteId))
                .done(function(r) {
                    var estudios = (r.data.persona && r.data.persona.estudios) ? r.data.persona.estudios : [];
                    var est = estudios.find(function(e) { return e.id == estudioId; });
                    if (!est) { toast('error', 'Estudio no encontrado.'); return; }
                    $.ajax({
                        url: '/admin/docentes/' + docenteId + '/estudios/' + estudioId + '/principal',
                        type: 'POST',
                        data: {
                            _token: CSRF,
                            _method: 'PATCH',
                            grados_academico_id: est.grados_academico_id,
                            profesione_id: est.profesione_id || '',
                            universidade_id: est.universidade_id || '',
                            estado: est.estado,
                            principal: 1
                        }
                    })
                    .done(function(r2) { toast('success', 'Estudio marcado como principal.'); recargarEstudios(docenteId); })
                    .fail(function() { toast('error', 'Error al actualizar.'); $btn.prop('disabled', false); });
                })
                .fail(function() { toast('error', 'Error al obtener datos.'); $btn.prop('disabled', false); });
        });
    }

    function buscarPersona() {
        const carnet = $('#searchCarnet').val().trim();
        if (!carnet) {
            toast('warning', 'Ingrese un número de carnet para buscar.');
            return;
        }

        setBtnLoading('#btnBuscar', true, 'Buscando…');
        $.post('<?php echo e(route("admin.docentes.buscarCarnet")); ?>', { _token: CSRF, carnet: carnet })
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
            $('#rDireccion').val(p.direccion || '').prop('readonly', true);
            $('#rExpedido').val(p.expedido || '').prop('readonly', true);
            $('#rFechaNacimiento').val(p.fecha_nacimiento || '').prop('readonly', true);
            $('#rSexo').val(p.sexo || '').prop('disabled', true);
            $('#rEstadoCivil').val(p.estado_civil || '').prop('disabled', true);

            if (p.ciudad) {
                $('#rDepto').val(p.ciudad.departamento_id).trigger('change');
                setTimeout(function () {
                    $('#rCiudad').val(p.ciudad.id).prop('disabled', true);
                }, 200);
            }

            ['rCarnet','rNombres'].forEach(function (id) {
                const input = document.getElementById(id);
                if (input) { input.classList.remove('is-invalid'); input.classList.add('is-valid'); }
            });

            if (p.correo) {
                const input = document.getElementById('rCorreo');
                if (input) { input.classList.remove('is-invalid'); input.classList.add('is-valid'); }
            }

            if (p.estudios && p.estudios.length) {
                prePopularRegistroEstudios(p.estudios);
            }

            $.post('<?php echo e(route("admin.docentes.buscarCarnet")); ?>', { _token: CSRF, carnet: p.carnet })
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
        setBtnLoading('#btnGuardarDocente', true, 'Guardando…');

        if (personaEncontrada && personaEncontrada.id) {
            registrarComoDocente(personaEncontrada.id);
            return;
        }

        const okC = validarCarnetSync('rCarnet','iconRCarnet','fbRCarnet');
        const okN = validarNombres('rNombres','iconRNombres','fbRNombres');
        const okAp = validarApellidos();
        if (!okC || !okN || !okAp) {
            setBtnLoading('#btnGuardarDocente', false, '<i class="ri-save-line"></i> Registrar Docente');
            return;
        }
        if (document.getElementById('rCarnet').classList.contains('is-invalid')) {
            setBtnLoading('#btnGuardarDocente', false, '<i class="ri-save-line"></i> Registrar Docente');
            return;
        }
        if (document.getElementById('rCorreo').classList.contains('is-invalid')) {
            setBtnLoading('#btnGuardarDocente', false, '<i class="ri-save-line"></i> Registrar Docente');
            return;
        }

        var formData = new FormData();
        formData.append('_token', CSRF);
        formData.append('carnet', $('#rCarnet').val().trim());
        formData.append('expedido', $('#rExpedido').val().trim());
        formData.append('nombres', $('#rNombres').val().trim());
        formData.append('apellido_paterno', $('#rApPaterno').val().trim());
        formData.append('apellido_materno', $('#rApMaterno').val().trim());
        formData.append('sexo', $('#rSexo').val());
        formData.append('estado_civil', $('#rEstadoCivil').val());
        formData.append('fecha_nacimiento', $('#rFechaNacimiento').val() || '');
        formData.append('correo', $('#rCorreo').val().trim());
        formData.append('direccion', $('#rDireccion').val().trim());
        formData.append('celular', $('#rCelular').val().trim());
        formData.append('telefono', $('#rTelefono').val().trim());
        formData.append('ciudade_id', $('#rCiudad').val() || '');

        var fotoInput = document.getElementById('fotografiaRegistro');
        if (fotoInput && fotoInput.files && fotoInput.files[0]) {
            formData.append('fotografia', fotoInput.files[0]);
        }

        $.ajax({
            url: '<?php echo e(route("admin.docentes.guardarPersona")); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        })
        .done(function (r) {
            registrarComoDocente(r.data.id);
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                const errs = xhr.responseJSON.errors || {};
                if (errs.carnet)  setError('rCarnet','iconRCarnet','fbRCarnet', errs.carnet[0]);
                if (errs.nombres) setError('rNombres','iconRNombres','fbRNombres', errs.nombres[0]);
                if (errs.correo)  setError('rCorreo','iconRCorreo','fbRCorreo', errs.correo[0]);
                if (errs.apellidos) {
                    $('#fbRApellidos').addClass('error').html('<i class="ri-error-warning-line"></i>' + errs.apellidos[0]);
                }
            } else {
                toast('error', 'Error al registrar. Intente nuevamente.');
            }
            setBtnLoading('#btnGuardarDocente', false, '<i class="ri-save-line"></i> Registrar Docente');
        });
    }

    function registrarComoDocente(personaId) {
        $.post('<?php echo e(route("admin.docentes.registrar")); ?>', {
            _token: CSRF,
            persona_id: personaId
        })
        .done(function (r2) {
            var $rows = $('#registroEstudiosList .registro-estudio-row');
            var estudiosPromesas = [];
            $rows.each(function() {
                if ($(this).data('existente')) return;
                const gradoId = $(this).find('.reg-est-grado').val();
                if (!gradoId) return;
                var docenteId = r2.data ? r2.data.id : null;
                if (!docenteId) return;
                estudiosPromesas.push($.post('/admin/docentes/' + docenteId + '/estudios', {
                    _token: CSRF,
                    grados_academico_id: gradoId,
                    profesione_id: $(this).find('.reg-est-profesion').val() || '',
                    universidade_id: $(this).find('.reg-est-universidad').val() || '',
                    estado: $(this).find('.reg-est-estado').val(),
                    principal: $(this).find('.reg-est-principal').is(':checked') ? 1 : 0
                }));
            });
            $.when.apply($, estudiosPromesas.length ? estudiosPromesas : [$.when()])
                .always(function() {
                    toast('success', r2.message);
                    closeModal('modalRegistro');
                    tabla.ajax.reload(null, false);
                    limpiarBusqueda();
                });
        })
        .fail(function (xhr) {
            const msg = xhr.responseJSON?.message || 'Error al registrar como docente.';
            toast('error', msg);
        })
        .always(function () {
            setBtnLoading('#btnGuardarDocente', false, '<i class="ri-save-line"></i> Registrar Docente');
        });
    }

    function editarDocente(id) {
        $.get('<?php echo e(route("admin.docentes.obtener", ["id" => "__ID__"])); ?>'.replace('__ID__', id))
            .done(function (r) {
                const e = r.data;
                const p = e.persona;
                $('#editCarnet').val(p ? p.carnet : '');
                $('#editExpedido').val(p ? p.expedido || '' : '');
                $('#editNombres').val(p ? p.nombres : '');
                $('#editApPaterno').val(p ? p.apellido_paterno || '' : '');
                $('#editApMaterno').val(p ? p.apellido_materno || '' : '');
                $('#editSexo').val(p ? p.sexo || '' : '');
                $('#editEstadoCivil').val(p ? p.estado_civil || '' : '');
                $('#editFechaNacimiento').val(p ? p.fecha_nacimiento || '' : '');
                $('#editCorreo').val(p && p.correo ? p.correo : '');
                $('#editCelular').val(p && p.celular ? p.celular : '');
                $('#editTelefono').val(p && p.telefono ? p.telefono : '');
                $('#editDireccion').val(p && p.direccion ? p.direccion : '');

                if (p && p.fotografia) {
                    var fotoUrl = '<?php echo e(url("images/personas")); ?>/' + p.fotografia;
                    $('#previewFotografiaEditar').attr('src', fotoUrl);
                } else {
                    $('#previewFotografiaEditar').attr('src', '<?php echo e(URL::asset("build/images/users/avatar-1.jpg")); ?>');
                }

                if (p && p.ciudad) {
                    $('#editDepto').val(p.ciudad.departamento_id).trigger('change');
                    setTimeout(function () {
                        $('#editCiudad').val(p.ciudad.id).prop('disabled', false);
                    }, 200);
                } else {
                    $('#editDepto').val('');
                    $('#editCiudad').find('option:not(:first)').remove().prop('disabled', true).find('option:first').text('— Seleccione depto. —');
                }

                $('#formEditar').data('docente-id', id);
                $('#formEditar').data('persona-id', p ? p.id : null);
                $('#editId').val(id);

                openModal('modalEditar');

                var estudios = (e.persona && e.persona.estudios) ? e.persona.estudios : [];
                var personaId = p ? p.id : null;
                renderEstudios(estudios, personaId, id);
            })
            .fail(function () { toast('error', 'Error al cargar los datos.'); });
    }

    function guardarEdicion() {
        const id = $('#formEditar').data('docente-id');
        const personaId = $('#formEditar').data('persona-id');
        if (!id || !personaId) return;

        setBtnLoading('#btnGuardarEdicion', true, 'Guardando…');

        var formData = new FormData();
        formData.append('_token', CSRF);
        formData.append('_method', 'PUT');
        formData.append('carnet', $('#editCarnet').val().trim());
        formData.append('expedido', $('#editExpedido').val().trim());
        formData.append('nombres', $('#editNombres').val().trim());
        formData.append('apellido_paterno', $('#editApPaterno').val().trim());
        formData.append('apellido_materno', $('#editApMaterno').val().trim());
        formData.append('sexo', $('#editSexo').val());
        formData.append('estado_civil', $('#editEstadoCivil').val());
        formData.append('fecha_nacimiento', $('#editFechaNacimiento').val() || '');
        formData.append('correo', $('#editCorreo').val().trim());
        formData.append('direccion', $('#editDireccion').val().trim());
        formData.append('celular', $('#editCelular').val().trim());
        formData.append('telefono', $('#editTelefono').val().trim());
        formData.append('ciudade_id', $('#editCiudad').val() || '');

        var fotoInput = document.getElementById('fotografiaEditar');
        if (fotoInput && fotoInput.files && fotoInput.files[0]) {
            formData.append('fotografia', fotoInput.files[0]);
        }

        $.ajax({
            url: '/admin/personas/' + personaId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        })
        .done(function (r) {
            toast('success', r.message);
            closeModal('modalEditar');
            tabla.ajax.reload(null, false);
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                const errs = xhr.responseJSON.errors || {};
                if (errs.carnet) toast('error', errs.carnet[0]);
                else if (errs.nombres) toast('error', errs.nombres[0]);
                else if (errs.correo) toast('error', errs.correo[0]);
            } else {
                toast('error', 'Error al actualizar. Intente nuevamente.');
            }
        })
        .always(function () {
            setBtnLoading('#btnGuardarEdicion', false, '<i class="ri-save-line"></i> Guardar Cambios');
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
        resetField('rCarnet', 'iconRCarnet', 'fbRCarnet');
        resetField('rNombres', 'iconRNombres', 'fbRNombres');
        resetField('rCorreo', 'iconRCorreo', 'fbRCorreo');
        $('#fbRApellidos').removeClass('error success').html('');
        $('#rDepto').val('');
        $('#rCiudad').find('option:not(:first)').remove().end().prop('disabled', true).find('option:first').text('— Seleccione depto. —');

        $('#previewFotografiaRegistro').attr('src', '<?php echo e(URL::asset("build/images/users/avatar-1.jpg")); ?>');

        ['rCarnet','rNombres','rApPaterno','rApMaterno','rCorreo','rCelular','rTelefono','rDireccion','rExpedido','rFechaNacimiento'].forEach(function (id) {
            $('#' + id).prop('readonly', false);
        });
        ['rSexo','rEstadoCivil','rCiudad'].forEach(function (id) {
            $('#' + id).prop('disabled', false);
        });

        $('#registroYaDocente').hide();
        $('#btnGuardarDocente').prop('disabled', false).html('<i class="ri-save-line"></i> Registrar Docente');
        $('#modalRegistroTitle').html('<i class="ri-teacher-line"></i> Registrar Docente');

        $('#registroEstudiosList').empty();
        registroEstudioCount = 0;
        syncRegistroEstudiosEmpty();
    }

    let docentesSinCuenta = [];

    function generarUsernameSistema(nombres, apPaterno, apMaterno) {
        const norm = s => (s || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim();
        const words = norm(nombres).split(/\s+/).filter(Boolean);
        const pn = words[0] || '';
        const inicial = pn.charAt(0);
        const ap = norm(apPaterno).replace(/\s/g, '');
        const am = norm(apMaterno).replace(/\s/g, '');
        return (inicial + ap + am).substring(0, 20);
    }

    function crearCuentas(id) {
        $('#sistemaCuentasLoading').show();
        $('#sistemaCuentasEmpty').hide();
        $('#sistemaCuentasList').hide();
        $('#btnConfirmarCrearCuentasSistema').prop('disabled', true);
        openModal('modalCrearCuentasSistema');

        $.get('<?php echo e(route("admin.docentes.obtener", ["id" => "__ID__"])); ?>'.replace('__ID__', id))
            .done(function (r) {
                const e = r.data;
                const p = e.persona;
                docentesSinCuenta = [];

                if (!e.tiene_cuenta_sistema || !e.tiene_cuenta_moodle) {
                    const nombres = p ? (p.nombres || '') : '';
                    const ap = p ? (p.apellido_paterno || '') : '';
                    const am = p ? (p.apellido_materno || '') : '';
                    const carnet = p ? (p.carnet || '') : '';
                    const fullName = [nombres, ap, am].filter(Boolean).join(' ');
                    const username = generarUsernameSistema(nombres, ap, am);
                    const password = generarPassword(carnet);

                    docentesSinCuenta.push({
                        id: e.id,
                        nombre: fullName,
                        ci: carnet,
                        username: username,
                        password: password
                    });
                }

                $('#sistemaCuentasLoading').hide();

                if (docentesSinCuenta.length === 0) {
                    $('#sistemaCuentasEmpty').show();
                } else {
                    $('#sistemaCuentasCount').text(docentesSinCuenta.length);
                    renderDocentesSinCuenta();
                    $('#sistemaCuentasList').show();
                }
            })
            .fail(function () {
                closeModal('modalCrearCuentasSistema');
                toast('error', 'Error al cargar los datos del docente.');
            });
    }

    function renderDocentesSinCuenta() {
        let html = '';
        docentesSinCuenta.forEach(function (d) {
            html += '<tr style="border-bottom:1px solid #f1f5f9;">'
                + '<td style="padding:.6rem .75rem;font-weight:600;color:#1e293b;font-size:.82rem;">' + esc(d.nombre) + '</td>'
                + '<td style="padding:.6rem .75rem;color:#64748b;font-size:.8rem;">' + esc(d.ci) + '</td>'
                + '<td style="padding:.6rem .75rem;"><code style="font-size:.75rem;background:rgba(252,123,4,.07);color:#9a4904;padding:.15rem .4rem;border-radius:5px;border:1px solid rgba(252,123,4,.12);">' + esc(d.username) + '</code></td>'
                + '<td style="padding:.6rem .75rem;"><code style="font-size:.75rem;background:rgba(16,185,129,.07);color:#047857;padding:.15rem .4rem;border-radius:5px;border:1px solid rgba(16,185,129,.12);">' + esc(d.password) + '</code></td>'
                + '</tr>';
        });
        $('#sistemaCuentasTableBody').html(html);
        $('#btnConfirmarCrearCuentasSistema').prop('disabled', false);
    }

    $('#btnConfirmarCrearCuentasSistema').on('click', function () {
        const target = docentesSinCuenta[0];
        if (!target) {
            toast('warning', 'No hay docente pendiente.');
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Creando...');

        $.post('/admin/docentes/' + target.id + '/crear-cuentas', { _token: CSRF })
            .done(function (r) {
                closeModal('modalCrearCuentasSistema');
                toast('success', r.message);
                mostrarCredenciales(r.data);
                tabla.ajax.reload(null, false);
            })
            .fail(function (xhr) {
                var msg = 'Error al crear la cuenta.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try { var j = JSON.parse(xhr.responseText); if (j.message) msg = j.message; } catch(e) {}
                }
                console.error('Error crear cuenta:', msg, xhr);
                toast('error', msg);
            })
            .always(function () {
                btn.prop('disabled', false).html('<i class="ri-user-add-line me-1"></i>Crear Cuenta');
            });
    });

    function mostrarCredenciales(data) {
        const titulo = document.getElementById('modalCredencialesTitle') || { innerHTML: '' };
        titulo.innerHTML = '<i class="ri-key-2-line"></i> Credenciales Generadas';
        let html = '';

        if (data.sistema) {
            html += '<p class="doc-cred-label">Acceso al Sistema (Laravel)</p>';
            html += credRow('Usuario', data.sistema.username);
            html += credRow('Correo', data.sistema.email);
            html += credRow('Contraseña', data.sistema.password);
        }

        if (data.moodle) {
            html += '<hr style="border-color:var(--doc-border);margin:.75rem 0;">';
            html += '<p class="doc-cred-label">Acceso a Moodle</p>';
            html += credRow('Usuario', data.moodle.username);
            if (data.moodle.password) {
                html += credRow('Contraseña', data.moodle.password);
            }
            html += credRow('Correo', data.moodle.email || data.sistema?.email);
            if (data.moodle.nota) {
                html += '<p style="font-size:.78rem;color:var(--doc-text-secondary);margin-top:.4rem;"><i class="ri-information-line"></i> ' + esc(data.moodle.nota) + '</p>';
            }
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
        const celular      = btn.data('celular');
        const nombre       = btn.data('nombre');
        const username     = btn.data('username');
        const password     = btn.data('password');
        const docenteId    = btn.data('docente-id');

        $('#waModalNombre').text(nombre);
        $('#waModalNombrePreview').text(nombre);
        $('#waModalUsuario').text(username);
        $('#waModalPassword').text(password);
        $('#waModalCelular').val(celular);
        $('#waModalDocenteId').val(docenteId);
        $('#waModalBtnReset').data('btn-origen', btn);

        new bootstrap.Modal(document.getElementById('modalWhatsappAccesos')).show();
    });

    $('#waModalBtnEnviar').on('click', function () {
        const celular  = $('#waModalCelular').val();
        const nombre   = $('#waModalNombre').text();
        const username = $('#waModalUsuario').text();
        const password = $('#waModalPassword').text();

        const mensaje = '*¡Bienvenido/a a tu plataforma académica!*\n\n' +
            '  Estimado/a ' + nombre + ',\n' +
            'A continuación encontrarás tus credenciales de acceso a la plataforma virtual.\n\n' +
            '  *ACCESO A LA PLATAFORMA*\n' +
            '\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\n' +
            '   Sitio web:  https://posgradosinnovaciencia.com\n' +
            '   Usuario:      ' + username + '\n' +
            '   Contrase\u00f1a:  ' + password + '\n' +
            '\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\n\n' +
            '  *PASOS PARA INGRESAR*\n' +
            '  Abre tu navegador (Chrome, Edge o Firefox)\n' +
            '  Visita \u2192 https://posgradosinnovaciencia.com\n' +
            '  Ingresa tu usuario y contrase\u00f1a\n' +
            '  Completa tu perfil en el primer acceso\n\n' +
            '  *IMPORTANTE*\n' +
            '* Guarda tus credenciales en un lugar seguro.\n' +
            '* No compartas tu contrase\u00f1a con nadie.\n' +
            '* Si olvidas tu acceso, cont\u00e1ctanos de inmediato.\n\n' +
            '  \u00a1\u00c9xitos en tu proceso de formaci\u00f3n!\n\n' +
            '\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\n' +
            '  \u00c1rea Acad\u00e9mica\n' +
            '  Innova Ciencia Virtual\n' +
            '  soporte@posgradosinnovaciencia.com\n' +
            '  +591 XXX XXX XXX';

        window.open('https://wa.me/' + celular + '?text=' + encodeURIComponent(mensaje), '_blank');
        bootstrap.Modal.getInstance(document.getElementById('modalWhatsappAccesos')).hide();
    });

    $('#waModalBtnReset').on('click', function () {
        const docenteId  = $('#waModalDocenteId').val();
        const btnReset   = $(this);
        const btnOrigen  = btnReset.data('btn-origen');

        btnReset.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Restableciendo...');

        $.ajax({
            url: '/admin/docentes/' + docenteId + '/reset-password-moodle',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                if (res.success) {
                    $('#waModalPassword').text(res.password);
                    if (btnOrigen) {
                        btnOrigen.data('password', res.password);
                    }
                    toast('success', 'Contraseña restablecida en Moodle correctamente.');
                } else {
                    toast('error', res.message || 'Error al restablecer la contraseña.');
                }
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.message || 'Error al conectar con Moodle.';
                toast('error', msg);
            },
            complete: function () {
                btnReset.prop('disabled', false).html('<i class="ri-refresh-line me-1"></i>Restablecer contraseña en Moodle');
            }
        });
    });

    /* ════════════════════ VALIDACIÓN ════════════════════ */

    function setError(inputId, iconId, fbId, msg) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        if (input) { input.classList.remove('is-valid'); input.classList.add('is-invalid'); }
        if (icon) { icon.className = 'doc-validation-icon invalid'; icon.innerHTML = '<i class="ri-close-circle-fill"></i>'; }
        if (fb) { fb.className = 'doc-feedback error'; fb.innerHTML = '<i class="ri-error-warning-line"></i>' + msg; }
        return false;
    }

    function setOk(inputId, iconId, fbId, msg) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        if (input) { input.classList.remove('is-invalid'); input.classList.add('is-valid'); }
        if (icon) { icon.className = 'doc-validation-icon valid'; icon.innerHTML = '<i class="ri-checkbox-circle-fill"></i>'; }
        if (fb) { fb.className = 'doc-feedback success'; fb.innerHTML = '<i class="ri-check-line"></i>' + msg; }
        return true;
    }

    function setChecking(inputId, iconId, fbId) {
        const icon = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        if (icon) { icon.className = 'doc-validation-icon'; icon.innerHTML = '<i class="ri-loader-4-line" style="animation:spin 1s linear infinite;"></i>'; }
        if (fb) { fb.className = 'doc-feedback'; fb.innerHTML = 'Verificando…'; }
    }

    function resetField(inputId, iconId, fbId) {
        const input = document.getElementById(inputId);
        if (input) input.classList.remove('is-valid', 'is-invalid');
        const icon = document.getElementById(iconId);
        if (icon) { icon.className = 'doc-validation-icon'; icon.innerHTML = ''; }
        const fb = document.getElementById(fbId);
        if (fb) { fb.className = 'doc-feedback'; fb.innerHTML = ''; }
    }

    function isEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

    function verificarCarnetRegistro(val) {
        $.post('/admin/personas/verificar-carnet', { _token: CSRF, carnet: val }, function (r) {
            if (r.existe) setError('rCarnet','iconRCarnet','fbRCarnet','Este carnet ya está registrado.');
            else setOk('rCarnet','iconRCarnet','fbRCarnet','Carnet disponible');
        }).fail(function () { resetField('rCarnet','iconRCarnet','fbRCarnet'); });
    }

    function verificarCorreoRegistro(val) {
        $.post('/admin/personas/verificar-correo', { _token: CSRF, correo: val }, function (r) {
            if (r.existe) setError('rCorreo','iconRCorreo','fbRCorreo','Este correo ya está registrado.');
            else setOk('rCorreo','iconRCorreo','fbRCorreo','Correo disponible');
        }).fail(function () { resetField('rCorreo','iconRCorreo','fbRCorreo'); });
    }

    function validarCarnetSync(inputId, iconId, fbId) {
        const val = document.getElementById(inputId).value.trim();
        if (!val) return setError(inputId, iconId, fbId, 'El carnet es obligatorio.');
        if (val.length < 3) return setError(inputId, iconId, fbId, 'Debe tener al menos 3 caracteres.');
        return true;
    }

    function validarNombres(inputId, iconId, fbId) {
        const val = document.getElementById(inputId).value.trim();
        if (!val) return setError(inputId, iconId, fbId, 'El nombre es obligatorio.');
        if (val.length < 2) return setError(inputId, iconId, fbId, 'Debe tener al menos 2 caracteres.');
        return setOk(inputId, iconId, fbId, 'Nombre válido');
    }

    function validarApellidos() {
        const p = $('#rApPaterno').val().trim();
        const m = $('#rApMaterno').val().trim();
        const fb = document.getElementById('fbRApellidos');
        if (!p && !m) {
            fb.className = 'doc-feedback error';
            fb.innerHTML = '<i class="ri-error-warning-line"></i>Debe registrar al menos un apellido (paterno o materno).';
            return false;
        }
        fb.className = 'doc-feedback success';
        fb.innerHTML = '<i class="ri-check-line"></i>Apellido(s) válido(s)';
        return true;
    }

    /* ════════════════════ ESTUDIOS ════════════════════ */

    function addRegistroEstudioRow(data) {
        registroEstudioCount++;
        const idx = registroEstudioCount;
        const gradosOpts = window._gradosOpts || '<option value="">— Grado —</option>';
        const profOpts   = window._profesionesOpts || '<option value="">— Profesión —</option>';
        const univOpts   = window._universidadesOpts || '<option value="">— Universidad —</option>';
        const esExistente = !!(data && data.grado_academico_id);
        const badgeExistente = esExistente
            ? '<span style="font-size:.65rem;font-weight:600;background:rgba(46,154,110,.1);color:var(--doc-success);border:1px solid rgba(46,154,110,.2);border-radius:20px;padding:.1rem .5rem;margin-left:.4rem;">Registrado</span>'
            : '';
        const btnEliminar = esExistente
            ? ''
            : '<button type="button" class="btn-remove-registro-estudio" style="background:rgba(217,79,79,.08);border:none;border-radius:6px;padding:.2rem .5rem;color:var(--doc-danger);cursor:pointer;font-size:.8rem;"><i class="ri-close-line"></i></button>';
        const html = '<div class="registro-estudio-row"' + (esExistente ? ' data-existente="1"' : '') + ' style="display:flex;flex-direction:column;gap:.5rem;padding:.85rem 1rem;background:' + (esExistente ? 'rgba(46,154,110,.03)' : '#fff') + ';border:1px solid ' + (esExistente ? 'rgba(46,154,110,.2)' : 'var(--doc-border)') + ';border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.05);">'
            + '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.25rem;">'
            + '<span style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--doc-primary);">Estudio #' + idx + badgeExistente + '</span>'
            + btnEliminar
            + '</div>'
            + '<div class="row g-2">'
            + '<div class="col-md-4"><label style="font-size:.7rem;font-weight:700;color:var(--doc-text-muted);display:block;margin-bottom:.3rem;">Grado Académico <span class="doc-req">*</span></label><select class="form-select form-select-sm reg-est-grado">' + gradosOpts + '</select></div>'
            + '<div class="col-md-4"><label style="font-size:.7rem;font-weight:700;color:var(--doc-text-muted);display:block;margin-bottom:.3rem;">Profesión</label><select class="form-select form-select-sm reg-est-profesion">' + profOpts + '</select></div>'
            + '<div class="col-md-4"><label style="font-size:.7rem;font-weight:700;color:var(--doc-text-muted);display:block;margin-bottom:.3rem;">Universidad</label><select class="form-select form-select-sm reg-est-universidad">' + univOpts + '</select></div>'
            + '<div class="col-md-4"><label style="font-size:.7rem;font-weight:700;color:var(--doc-text-muted);display:block;margin-bottom:.3rem;">Estado</label><select class="form-select form-select-sm reg-est-estado"><option value="Concluido">Concluido</option><option value="En Desarrollo">En Desarrollo</option></select></div>'
            + '<div class="col-md-4 d-flex align-items-end"><div class="form-check mb-1"><input class="form-check-input reg-est-principal" type="checkbox" id="regEstPrincipal' + idx + '"' + (idx === 1 ? ' checked' : '') + '><label class="form-check-label" for="regEstPrincipal' + idx + '" style="font-size:.78rem;font-weight:600;">Principal</label></div></div>'
            + '</div></div>';
        $('#registroEstudiosList').append(html);
        if (data) {
            const $row = $('#registroEstudiosList .registro-estudio-row').last();
            if (data.grado_academico_id) $row.find('.reg-est-grado').val(data.grado_academico_id);
            if (data.profesion_id)       $row.find('.reg-est-profesion').val(data.profesion_id);
            if (data.universidad_id)     $row.find('.reg-est-universidad').val(data.universidad_id);
            if (data.estado)             $row.find('.reg-est-estado').val(data.estado);
            if (data.principal)          $row.find('.reg-est-principal').prop('checked', true);
            if (esExistente) {
                $row.find('select, input').prop('disabled', true);
            }
        }
    }

    function syncRegistroEstudiosEmpty() {
        const count = $('#registroEstudiosList .registro-estudio-row').length;
        $('#registroEstudiosEmpty').toggle(count === 0);
    }

    function prePopularRegistroEstudios(estudios) {
        $('#registroEstudiosList').empty();
        registroEstudioCount = 0;
        (estudios || []).forEach(function(est) {
            addRegistroEstudioRow(est);
        });
        syncRegistroEstudiosEmpty();
    }

    function renderEstudios(estudios, personaId, docenteId) {
        $('#formEditar').data('persona-id', personaId);
        $('#formEditar').data('docente-id', docenteId);
        var $list = $('#editEstudiosList');
        $list.empty();
        if (!estudios.length) {
            $list.html('<p style="font-size:.8rem;color:var(--doc-text-muted);margin:.25rem 0;">Sin estudios registrados.</p>');
            return;
        }
        estudios.forEach(function(est) {
            var isPrincipal = !!est.principal;
            var grado = est.grado_academico ? esc(est.grado_academico.nombre) : '—';
            var prof = est.profesion ? esc(est.profesion.nombre) : '';
            var uni = est.universidad ? esc(est.universidad.nombre) : '';
            var sub = [prof, uni].filter(Boolean).join(' · ') || '—';
            var estadoCls = est.estado === 'Concluido' ? 'concluido' : 'en-desarrollo';
            var row = $('<div class="doc-estudio-row" data-estudio-id="'+est.id+'">'
                +'<button type="button" class="doc-estudio-principal '+(isPrincipal?'is-principal':'not-principal')+'" title="'+(isPrincipal?'Estudio principal':'Marcar como principal')+'" data-estudio-id="'+est.id+'" data-persona-id="'+personaId+'">'
                +'<i class="ri-star-'+(isPrincipal?'fill':'line')+'"></i></button>'
                +'<div class="doc-estudio-info"><div class="doc-estudio-grado">'+grado+'</div><div class="doc-estudio-sub">'+sub+'</div></div>'
                +'<span class="doc-estudio-estado '+estadoCls+'">'+esc(est.estado)+'</span>'
                +'<button type="button" class="doc-estudio-del btn-del-estudio" data-estudio-id="'+est.id+'" title="Eliminar"><i class="ri-delete-bin-line"></i></button>'
                +'</div>');
            $list.append(row);
        });
    }

    function recargarEstudios(docenteId) {
        $.get('<?php echo e(route("admin.docentes.obtener", ["id" => "__ID__"])); ?>'.replace('__ID__', docenteId))
            .done(function(r) {
                var estudios = (r.data.persona && r.data.persona.estudios) ? r.data.persona.estudios : [];
                renderEstudios(estudios, r.data.persona ? r.data.persona.id : null, docenteId);
            });
    }

    /* ════════════════════ UTILIDADES ════════════════════ */

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/docentes/index.blade.php ENDPATH**/ ?>