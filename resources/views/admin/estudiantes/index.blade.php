@extends('layouts.master')
@section('title') Estudiantes @endsection
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300..700&family=DM+Sans:opsz,wght@9..40,300..700&display=swap" rel="stylesheet">
<style>
:root {
    --est-primary: #fc7b04;
    --est-primary-dark: #d46604;
    --est-bg-warm: #faf7f4;
    --est-card-bg: #ffffff;
    --est-text: #2d2924;
    --est-text-muted: #8c8880;
    --est-border: #ede8e2;
    --est-border-light: #f5f0eb;
    --est-shadow-sm: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
    --est-shadow-md: 0 4px 16px rgba(0,0,0,0.05), 0 2px 8px rgba(0,0,0,0.03);
    --est-shadow-lg: 0 12px 40px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    --est-success: #2e9a6e;
    --est-danger: #e05050;
    --est-warning: #f0a030;
    --est-green: #5a8a30;
}

body { font-family: 'DM Sans', sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
h1,h2,h3,h4,h5,h6,.modal-title,.est-card-title,.est-stat-num,.est-section-title { font-family: 'Lexend', sans-serif; }

.est-animate { opacity: 0; transform: translateY(18px); animation: estFadeUp 0.6s cubic-bezier(0.16,1,0.3,1) forwards; }
.est-animate-1 { animation-delay: 0.05s; }
.est-animate-2 { animation-delay: 0.12s; }
.est-animate-3 { animation-delay: 0.2s; }
@keyframes estFadeUp { to { opacity: 1; transform: translateY(0); } }

.est-page { position: relative; min-height: 100%; }
.est-page::before { content: ''; position: fixed; inset: 0; z-index: -1; background: var(--est-bg-warm); }
.est-page::after { content: ''; position: fixed; inset: 0; z-index: -1; opacity: 0.025; background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E"); background-size: 256px 256px; pointer-events: none; }

.est-header-page { position: relative; padding: 1.75rem 0 1.5rem; background: linear-gradient(135deg, #ffffff 0%, #fef9f4 50%, #fdf6ee 100%); border-bottom: 1px solid var(--est-border); overflow: hidden; }
.est-header-page::before { content: ''; position: absolute; top: -50%; right: -10%; width: 500px; height: 500px; border-radius: 50%; background: radial-gradient(circle, rgba(252,123,4,0.05) 0%, transparent 70%); pointer-events: none; }
.est-header-page::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(252,123,4,0.15), transparent); }
.est-header-inner { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; position: relative; z-index: 1; }
.est-header-left { display: flex; align-items: center; gap: 1.1rem; }
.est-header-icon { width: 52px; height: 52px; background: linear-gradient(135deg, rgba(252,123,4,0.12), rgba(252,123,4,0.05)); border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(252,123,4,0.1); box-shadow: 0 2px 8px rgba(252,123,4,0.06); }
.est-header-icon i { font-size: 1.5rem; color: var(--est-primary); }
.est-header-text h1 { font-size: 1.45rem; font-weight: 600; color: var(--est-text); margin: 0 0 0.1rem; line-height: 1.2; letter-spacing: -0.02em; font-family: 'Lexend', sans-serif; }
.est-header-text p { font-size: 0.85rem; color: var(--est-text-muted); margin: 0; font-weight: 400; }
.est-header-right { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }

.est-stat-card { display: flex; align-items: center; gap: 0.7rem; background: rgba(255,255,255,0.8); border: 1px solid var(--est-border); border-radius: 12px; padding: 0.6rem 1.1rem; transition: box-shadow 0.25s, transform 0.2s; backdrop-filter: blur(4px); }
.est-stat-card:hover { box-shadow: var(--est-shadow-md); transform: translateY(-1px); }
.est-stat-icon { width: 36px; height: 36px; background: linear-gradient(135deg, rgba(252,123,4,0.12), rgba(252,123,4,0.04)); border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.est-stat-icon i { color: var(--est-primary); font-size: 1rem; }
.est-stat-num { font-size: 1.25rem; font-weight: 600; color: var(--est-text); line-height: 1; letter-spacing: -0.02em; }
.est-stat-label { font-size: 0.72rem; color: var(--est-text-muted); margin-top: 2px; font-weight: 450; }

.est-search-card { background: var(--est-card-bg); border: 1px solid var(--est-border); border-radius: 16px; box-shadow: var(--est-shadow-sm); padding: 1.5rem; margin-bottom: 1.5rem; }
.est-search-row { display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap; }
.est-search-input { flex: 1; min-width: 200px; }
.est-search-input label { font-weight: 600; font-size: 0.82rem; color: var(--est-text); margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.3rem; }
.est-search-input label i { color: var(--est-primary); }
.est-search-input .form-control { background: #faf8f5; border: 1px solid var(--est-border); border-radius: 10px; padding: 0.65rem 1rem; font-size: 0.9rem; color: var(--est-text); font-weight: 500; transition: border-color 0.2s, box-shadow 0.2s; font-family: 'DM Sans', sans-serif; }
.est-search-input .form-control:focus { border-color: var(--est-primary); box-shadow: 0 0 0 4px rgba(252,123,4,0.1); background: #fff; outline: none; }
.est-search-input .form-control::placeholder { color: #b5b0a8; }

.est-btn { display: inline-flex; align-items: center; gap: 0.4rem; border: none; font-weight: 600; font-size: 0.85rem; padding: 0.65rem 1.3rem; border-radius: 10px; cursor: pointer; transition: all 0.2s; white-space: nowrap; font-family: 'DM Sans', sans-serif; }
.est-btn-search { background: linear-gradient(135deg, var(--est-primary) 0%, var(--est-primary-dark) 100%); color: #fff; box-shadow: 0 3px 10px rgba(252,123,4,0.25); }
.est-btn-search:hover { background: var(--est-primary-dark); transform: translateY(-1px); box-shadow: 0 5px 15px rgba(252,123,4,0.35); color: #fff; }
.est-btn-register { background: linear-gradient(135deg, var(--est-green) 0%, #6dbf40 100%); color: #fff; box-shadow: 0 3px 10px rgba(90,138,48,0.25); }
.est-btn-register:hover { background: linear-gradient(135deg, #6dbf40 0%, #82d455 100%); transform: translateY(-1px); box-shadow: 0 5px 15px rgba(90,138,48,0.35); color: #fff; }
.est-btn-register:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

.est-persona-found { background: var(--est-card-bg); border: 1px solid var(--est-border); border-radius: 16px; box-shadow: var(--est-shadow-sm); overflow: hidden; margin-bottom: 1.5rem; display: none; }
.est-pf-header { background: linear-gradient(135deg, #4a2406 0%, #7a3f06 40%, #c96004 100%); padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; position: relative; }
.est-pf-header::after { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 90% 50%, rgba(252,123,4,0.2) 0%, transparent 60%); pointer-events: none; }
.est-pf-header h5 { color: #fff; font-weight: 600; font-size: 1rem; margin: 0; display: flex; align-items: center; gap: 0.5rem; position: relative; z-index: 1; font-family: 'Lexend', sans-serif; }
.est-pf-badge { background: rgba(255,255,255,0.15); color: #fff; font-size: 0.7rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 6px; text-transform: uppercase; position: relative; z-index: 1; backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.1); }
.est-pf-body { padding: 1.25rem 1.5rem; }
.est-pf-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 0.75rem; }
.est-pf-item label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--est-text-muted); margin-bottom: 0.2rem; display: block; font-family: 'Lexend', sans-serif; }
.est-pf-item span { font-size: 0.88rem; font-weight: 600; color: var(--est-text); }

.est-ya-estudiante { background: rgba(90,138,48,0.08); border: 1px solid rgba(90,138,48,0.2); border-radius: 10px; padding: 0.7rem 1rem; display: flex; align-items: center; gap: 0.5rem; margin-top: 0.75rem; }
.est-ya-estudiante i { color: var(--est-green); font-size: 1.1rem; }
.est-ya-estudiante span { font-size: 0.82rem; font-weight: 600; color: var(--est-green); }

.est-section-title { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.7px; color: var(--est-text-muted); border-bottom: 1px solid var(--est-border); padding-bottom: 0.35rem; margin: 1.25rem 0 1rem; display: flex; align-items: center; gap: 0.4rem; }
.est-section-title:first-child { margin-top: 0; }
.est-section-title i { font-size: 0.85rem; color: var(--est-primary); }

.est-badge-si { display: inline-flex; align-items: center; gap: 0.25rem; background: rgba(90,138,48,0.1); color: var(--est-green); border: 1px solid rgba(90,138,48,0.25); border-radius: 20px; padding: 0.2rem 0.6rem; font-size: 0.72rem; font-weight: 700; }
.est-badge-no { display: inline-flex; align-items: center; gap: 0.25rem; background: rgba(150,150,150,0.06); color: var(--est-text-muted); border: 1px solid rgba(150,150,150,0.15); border-radius: 20px; padding: 0.2rem 0.6rem; font-size: 0.72rem; font-weight: 600; }

.est-card { background: var(--est-card-bg); border: 1px solid var(--est-border); border-radius: 16px; overflow: hidden; box-shadow: var(--est-shadow-sm); transition: box-shadow 0.3s; }
.est-card:hover { box-shadow: var(--est-shadow-md); }
.est-card-header { padding: 1.1rem 1.35rem; border-bottom: 1px solid var(--est-border-light); background: linear-gradient(135deg, #ffffff, #fefaf7); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
.est-card-header-left { display: flex; align-items: center; gap: 0.85rem; }
.est-card-header-icon { width: 38px; height: 38px; background: linear-gradient(135deg, rgba(252,123,4,0.1), rgba(252,123,4,0.04)); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.est-card-header-icon i { color: var(--est-primary); font-size: 1.05rem; }
.est-card-title { font-size: 0.95rem; font-weight: 600; color: var(--est-text); margin: 0; letter-spacing: -0.01em; }
.est-card-subtitle { font-size: 0.78rem; color: var(--est-text-muted); margin: 2px 0 0; font-weight: 400; }
.est-card-body { padding: 0; }

.est-table { width: 100% !important; border-collapse: collapse; margin: 0 !important; }
.est-table thead th { background: linear-gradient(135deg, #faf7f4, #fef9f4); color: var(--est-text); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; padding: 0.9rem 1rem; border-bottom: 2px solid var(--est-border); font-family: 'Lexend', sans-serif; }
.est-table tbody td { padding: 0.75rem 1rem; font-size: 0.875rem; color: var(--est-text); border-bottom: 1px solid var(--est-border-light); vertical-align: middle; }
.est-table tbody tr { transition: background 0.18s; }
.est-table tbody tr:last-child td { border-bottom: none; }
.est-table tbody tr:nth-child(even) td { background: rgba(252,123,4,0.018); }
.est-table tbody tr:hover td { background: rgba(252,123,4,0.05) !important; }

/* ─── CELL COMPONENTS ─── */
.est-carnet-cell { display: flex; flex-direction: column; gap: 2px; }
.est-carnet-num { font-weight: 700; font-size: 0.85rem; color: var(--est-text); letter-spacing: 0.01em; }
.est-carnet-exp { font-size: 0.67rem; color: var(--est-text-muted); font-weight: 500; }

.est-stu-cell { display: flex; align-items: center; gap: 0.65rem; }
.est-stu-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--est-primary), var(--est-primary-dark)); color: #fff; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-family: 'Lexend', sans-serif; box-shadow: 0 2px 6px rgba(252,123,4,0.25); letter-spacing: 0.02em; }
.est-stu-info { min-width: 0; flex: 1; }
.est-stu-name { font-weight: 700; font-size: 0.875rem; color: var(--est-text); line-height: 1.2; }
.est-stu-ape { font-size: 0.78rem; color: var(--est-text-muted); font-weight: 400; line-height: 1.2; margin-top: 1px; }
.est-stu-email { font-size: 0.68rem; color: var(--est-text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; margin-top: 2px; opacity: 0.85; }

.est-cel-cell { display: flex; align-items: center; gap: 0.35rem; font-size: 0.83rem; color: var(--est-text); white-space: nowrap; }
.est-cel-cell i { color: var(--est-primary); font-size: 0.85rem; flex-shrink: 0; }

.est-action-cell { display: flex; align-items: center; justify-content: center; gap: 0.3rem; }
.est-btn-action { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: all 0.2s; background: transparent; color: var(--est-text-muted); cursor: pointer; }
.est-btn-action i { font-size: 0.95rem; }
.est-btn-action:hover { background: var(--est-border-light); }
.est-btn-cuenta { background: rgba(124,58,237,0.08); color: #7c3aed; border: 1px solid rgba(124,58,237,0.2); }
.est-btn-cuenta:hover { background: rgba(124,58,237,0.15); color: #5b21b6; }
.est-btn-whatsapp { background: rgba(37,211,102,0.08); color: #25D366; border: 1px solid rgba(37,211,102,0.2); }
.est-btn-whatsapp:hover { background: rgba(37,211,102,0.15); color: #128C7E; }

.est-btn-edit:hover { background: rgba(252,123,4,0.1); color: var(--est-primary); }
.est-btn-delete:hover { background: rgba(224,80,80,0.08); color: var(--est-danger); }

.est-label { font-size: 0.82rem; font-weight: 600; color: var(--est-text); margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.35rem; }
.est-label i { color: var(--est-primary); font-size: 0.9rem; }
.req { color: var(--est-danger); font-weight: 700; }

.est-field { position: relative; }
.est-field .form-control { border: 1px solid var(--est-border); border-radius: 10px; padding: 0.55rem 2.5rem 0.55rem 0.9rem; font-size: 0.875rem; color: var(--est-text); background: #faf8f5; transition: border-color 0.2s, box-shadow 0.2s, background 0.2s; font-family: 'DM Sans', sans-serif; width: 100%; }
.est-field .form-control:focus { border-color: var(--est-primary); box-shadow: 0 0 0 3px rgba(252,123,4,0.1); background: #fff; outline: none; }
.est-field .form-control.is-valid { border-color: var(--est-success); background: #f4faf6; }
.est-field .form-control.is-invalid { border-color: var(--est-danger); background: #fef4f4; }
.est-field .form-control::placeholder { color: #b5b0a8; font-weight: 400; }
.est-field select, .est-field select.form-select { border: 1px solid var(--est-border); border-radius: 10px; padding: 0.55rem 0.9rem; font-size: 0.875rem; color: var(--est-text); background: #faf8f5; transition: border-color 0.2s, box-shadow 0.2s; font-family: 'DM Sans', sans-serif; width: 100%; cursor: pointer; }
.est-field select:focus { border-color: var(--est-primary); box-shadow: 0 0 0 3px rgba(252,123,4,0.1); background: #fff; outline: none; }
.est-checking { animation: estSpin 1s linear infinite; }
@keyframes estSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

.est-validation-icon { position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%); font-size: 1rem; pointer-events: none; opacity: 0; transition: opacity 0.2s; }
.est-validation-icon.valid, .est-validation-icon.invalid { opacity: 1; }
.est-validation-icon.valid i { color: var(--est-success); }
.est-validation-icon.invalid i { color: var(--est-danger); }
.est-feedback { font-size: 0.76rem; margin-top: 0.3rem; min-height: 1rem; display: flex; align-items: center; gap: 0.3rem; transition: color 0.2s; }
.est-feedback.error { color: var(--est-danger); }
.est-feedback.success { color: var(--est-success); }

.est-btn-cancel { background: #f0ece8; color: var(--est-text); border: none; border-radius: 10px; font-size: 0.875rem; font-weight: 500; padding: 0.55rem 1.1rem; transition: background 0.15s; font-family: 'DM Sans', sans-serif; }
.est-btn-cancel:hover { background: #e5dfd9; color: var(--est-text); }
.est-btn-submit { background: linear-gradient(135deg, var(--est-primary), var(--est-primary-dark)); color: #fff; border: none; border-radius: 10px; font-size: 0.875rem; font-weight: 600; padding: 0.55rem 1.3rem; transition: box-shadow 0.2s, transform 0.15s; font-family: 'DM Sans', sans-serif; display: inline-flex; align-items: center; gap: 0.4rem; }
.est-btn-submit:hover:not(:disabled) { box-shadow: 0 4px 14px rgba(252,123,4,0.3); color: #fff; transform: scale(1.02); }
.est-btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }
.est-btn-danger { background: var(--est-danger); color: #fff; border: none; border-radius: 10px; font-size: 0.875rem; font-weight: 600; padding: 0.55rem 1.3rem; transition: box-shadow 0.2s, transform 0.15s; font-family: 'DM Sans', sans-serif; display: inline-flex; align-items: center; gap: 0.4rem; }
.est-btn-danger:hover { box-shadow: 0 4px 14px rgba(224,80,80,0.3); color: #fff; transform: scale(1.02); }

.est-delete-box { text-align: center; padding: 0.5rem 0; }
.est-delete-icon { width: 72px; height: 72px; border-radius: 50%; background: rgba(224,80,80,0.08); border: 2px solid rgba(224,80,80,0.18); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.1rem; transition: transform 0.3s; }
.est-delete-icon i { font-size: 2rem; color: var(--est-danger); }
.est-delete-box:hover .est-delete-icon { transform: scale(1.05); }
.est-delete-msg { font-size: 1.05rem; font-weight: 700; color: var(--est-text); margin-bottom: 0.3rem; }
.est-delete-name { font-size: 0.92rem; color: var(--est-text); margin-bottom: 0.6rem; }
.est-delete-name strong { color: var(--est-primary); }
.est-delete-warn { font-size: 0.78rem; color: var(--est-text-muted); margin-bottom: 0; display: flex; align-items: center; justify-content: center; gap: 0.3rem; }
.est-delete-warn i { color: var(--est-warning); font-size: 0.9rem; }

.est-cred-item label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; color: var(--est-text-muted); display: block; margin-bottom: 0.25rem; font-family: 'Lexend', sans-serif; }
.est-cred-value { background: #faf8f5; border: 1px solid var(--est-border); border-radius: 8px; padding: 0.5rem 0.75rem; font-family: 'DM Sans', monospace; font-size: 0.9rem; font-weight: 600; color: var(--est-text); display: flex; justify-content: space-between; align-items: center; }
.est-cred-copy { background: none; border: none; cursor: pointer; color: var(--est-text-muted); padding: 0 0 0 0.5rem; font-size: 1rem; transition: color 0.15s; }
.est-cred-copy:hover { color: var(--est-primary); }

.modal-content { border: none; border-radius: 16px; box-shadow: var(--est-shadow-lg); overflow: hidden; }
.modal-header { padding: 1.1rem 1.35rem 0.85rem; border-bottom: 1px solid var(--est-border-light); background: linear-gradient(135deg, #ffffff, #fefaf7); }
.modal-header .modal-title { font-family: 'Lexend', sans-serif; font-size: 1rem; font-weight: 600; color: var(--est-text); letter-spacing: -0.01em; display: flex; align-items: center; gap: 0.5rem; }
.modal-header .modal-title i { color: var(--est-primary); font-size: 1.15rem; }
.modal-header .btn-close { transition: transform 0.2s, opacity 0.2s; opacity: 0.5; }
.modal-header .btn-close:hover { transform: rotate(90deg); opacity: 1; }
.modal-body { padding: 1rem 1.35rem; }
.modal-footer { padding: 0.85rem 1.35rem; border-top: 1px solid var(--est-border-light); background: #faf8f5; }
.modal-backdrop { background: rgba(0,0,0,0.3); }
@supports (backdrop-filter: blur(3px)) { .modal-backdrop { backdrop-filter: blur(3px); background: rgba(0,0,0,0.2); } }
.modal.fade .modal-dialog { transform: scale(0.92) translateY(-10px); transition: transform 0.35s cubic-bezier(0.16,1,0.3,1), opacity 0.25s; }
.modal.show .modal-dialog { transform: scale(1) translateY(0); }

.est-card .dataTables_wrapper { padding: 0; }
.est-card .dataTables_wrapper .dataTables_filter { padding: 0.85rem 1.1rem 0; }
.est-card .dataTables_wrapper .dataTables_filter label { font-size: 0.82rem; color: var(--est-text-muted); display: flex; align-items: center; gap: 0.5rem; font-weight: 450; }
.est-card .dataTables_wrapper .dataTables_filter input { border: 1px solid var(--est-border); border-radius: 8px; padding: 0.4rem 0.75rem; font-size: 0.82rem; color: var(--est-text); background: #faf8f5; transition: border-color 0.2s; font-family: 'DM Sans', sans-serif; outline: none; min-width: 200px; }
.est-card .dataTables_wrapper .dataTables_filter input:focus { border-color: var(--est-primary); box-shadow: 0 0 0 3px rgba(252,123,4,0.1); background: #fff; }
.est-card .dataTables_wrapper .dataTables_length { padding: 0.85rem 1.1rem 0; }
.est-card .dataTables_wrapper .dataTables_length label { font-size: 0.82rem; color: var(--est-text-muted); display: flex; align-items: center; gap: 0.4rem; font-weight: 450; }
.est-card .dataTables_wrapper .dataTables_length select { border: 1px solid var(--est-border); border-radius: 6px; padding: 0.3rem 1.5rem 0.3rem 0.5rem; font-size: 0.82rem; color: var(--est-text); background: #faf8f5 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%238c8880'/%3E%3C/svg%3E") no-repeat right 0.5rem center; appearance: none; cursor: pointer; outline: none; }
.est-card .dataTables_wrapper .dataTables_length select:focus { border-color: var(--est-primary); box-shadow: 0 0 0 3px rgba(252,123,4,0.1); background-color: #fff; }
.est-card .dataTables_wrapper .dataTables_info { padding: 0.85rem 1.1rem; font-size: 0.78rem; color: var(--est-text-muted); }
.est-card .dataTables_wrapper .dataTables_paginate { padding: 0.85rem 1.1rem; }
.est-card .dataTables_wrapper .dataTables_paginate .paginate_button { border: 1px solid var(--est-border) !important; border-radius: 8px !important; padding: 0.35rem 0.75rem !important; margin: 0 0.15rem; font-size: 0.78rem; color: var(--est-text) !important; background: #fff !important; transition: all 0.2s; font-family: 'DM Sans', sans-serif; }
.est-card .dataTables_wrapper .dataTables_paginate .paginate_button:hover { border-color: rgba(252,123,4,0.3) !important; background: rgba(252,123,4,0.06) !important; color: var(--est-primary) !important; }
.est-card .dataTables_wrapper .dataTables_paginate .paginate_button.current { border-color: var(--est-primary) !important; background: linear-gradient(135deg, var(--est-primary), var(--est-primary-dark)) !important; color: #fff !important; font-weight: 600; }
.est-card .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: 0.4; cursor: not-allowed; }
.est-card table.dataTable.no-footer { border-bottom: 1px solid var(--est-border-light); }

/* ─── ENHANCED BUTTONS ─── */
.est-btn-search, .est-btn-register { position: relative; padding: 0.7rem 1.5rem; font-size: 0.88rem; letter-spacing: 0.01em; }
.est-btn-search i, .est-btn-register i { font-size: 1.05rem; transition: transform 0.25s ease; }
.est-btn-search:hover i, .est-btn-register:hover i { transform: scale(1.15); }

.est-btn-search { background: linear-gradient(135deg, #fc7b04 0%, #e06b00 50%, #c96004 100%); background-size: 200% 200%; animation: estBtnShimmer 3s ease-in-out infinite; }
.est-btn-register { background: linear-gradient(135deg, #5a8a30 0%, #6dbf40 50%, #82d455 100%); background-size: 200% 200%; animation: estBtnShimmer 3s ease-in-out infinite; }
@keyframes estBtnShimmer { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }

/* ─── PHOTO UPLOAD ─── */
.est-photo-wrap { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; }
.est-photo-circle { width: 100px; height: 100px; border-radius: 50%; overflow: hidden; border: 3px solid var(--est-border); cursor: pointer; transition: border-color 0.25s, box-shadow 0.25s; position: relative; }
.est-photo-circle:hover { border-color: var(--est-primary); box-shadow: 0 0 0 4px rgba(252,123,4,0.1); }
.est-photo-circle img { width: 100%; height: 100%; object-fit: cover; }
.est-photo-circle .est-photo-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.35); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.25s; border-radius: 50%; }
.est-photo-circle:hover .est-photo-overlay { opacity: 1; }
.est-photo-circle .est-photo-overlay i { color: #fff; font-size: 1.4rem; }
.est-photo-hint { font-size: 0.72rem; color: var(--est-text-muted); }

/* ─── MODAL FORM ENHANCEMENTS ─── */
.est-modal-form .modal-body { padding: 1.35rem; max-height: calc(100vh - 210px); overflow-y: auto; }
.est-modal-form .modal-footer { position: sticky; bottom: 0; z-index: 2; border-radius: 0 0 16px 16px; }
.est-modal-form .modal-content { max-height: calc(100vh - 60px); display: flex; flex-direction: column; }
.est-modal-sections { display: flex; flex-direction: column; gap: 1px; }
.est-modal-section { }
.est-modal-section-header { display: flex; align-items: center; gap: 0.5rem; padding: 0.65rem 0 0.5rem; margin-bottom: 1rem; border-bottom: 2px solid var(--est-primary); }
.est-modal-section-header i { color: var(--est-primary); font-size: 1rem; }
.est-modal-section-header span { font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--est-text); font-family: 'Lexend', sans-serif; }

/* ─── RESPONSIVE TABLE ─── */
.est-table-wrap { width: 100%; overflow-x: hidden; }
.est-table-wrap table.dataTable { width: 100% !important; }
.est-table-wrap table.dataTable td { white-space: normal !important; word-break: break-word; }
.est-table-wrap .dataTables_wrapper { overflow-x: hidden; }
.est-table-wrap .dataTables_wrapper .dataTables_filter input { min-width: 120px; }

/* DataTables responsive child rows */
table.dataTable > tbody > tr.child { background: rgba(252,123,4,0.02); }
table.dataTable > tbody > tr.child td { padding: 0.75rem 1rem !important; }
table.dataTable > tbody > tr.child ul.dtr-details { margin: 0; padding: 0; }
table.dataTable > tbody > tr.child ul.dtr-details > li { border-bottom: 1px solid var(--est-border-light); padding: 0.5rem 0; display: flex; align-items: flex-start; gap: 0.5rem; }
table.dataTable > tbody > tr.child ul.dtr-details > li:last-child { border-bottom: none; }
table.dataTable > tbody > tr.child span.dtr-title { font-weight: 700; color: var(--est-text); min-width: 100px; font-family: 'Lexend', sans-serif; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.04em; flex-shrink: 0; padding-top: 1px; }
table.dataTable > tbody > tr.child span.dtr-data { font-size: 0.85rem; color: var(--est-text); }
table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control { cursor: pointer; }
table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control::before { background: var(--est-primary) !important; border: none !important; box-shadow: 0 2px 6px rgba(252,123,4,0.3) !important; width: 18px !important; height: 18px !important; line-height: 18px !important; font-size: 0.75rem !important; }

.toast-container { position: fixed; right: 20px; z-index: 1060; display: flex; flex-direction: column; gap: 0.5rem; pointer-events: none; }
.toast-notify { display: flex; align-items: center; gap: 0.65rem; padding: 0.75rem 1rem; background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); font-size: 0.82rem; font-weight: 500; color: var(--est-text); border-left: 4px solid; animation: estToastIn 0.35s cubic-bezier(0.16,1,0.3,1); pointer-events: auto; min-width: 280px; max-width: 420px; font-family: 'DM Sans', sans-serif; }
.toast-notify.hiding { animation: estToastOut 0.25s ease forwards; }
.toast-notify.success { border-left-color: var(--est-success); }
.toast-notify.error { border-left-color: var(--est-danger); }
.toast-notify.warning { border-left-color: var(--est-warning); }
.toast-icon { flex-shrink: 0; font-size: 1.1rem; }
.toast-notify.success .toast-icon i { color: var(--est-success); }
.toast-notify.error .toast-icon i { color: var(--est-danger); }
.toast-notify.warning .toast-icon i { color: var(--est-warning); }
.toast-body-text { flex: 1; }
.toast-close { background: none; border: none; color: var(--est-text-muted); cursor: pointer; padding: 0; font-size: 1.1rem; opacity: 0.5; transition: opacity 0.2s; flex-shrink: 0; }
.toast-close:hover { opacity: 1; }
@keyframes estToastIn { 0% { opacity: 0; transform: translateX(100%) scale(0.95); } 100% { opacity: 1; transform: translateX(0) scale(1); } }
@keyframes estToastOut { 0% { opacity: 1; transform: translateX(0) scale(1); } 100% { opacity: 0; transform: translateX(100%) scale(0.95); } }

@media (max-width: 992px) {
    .est-header-inner { flex-direction: column; align-items: flex-start; }
    .est-header-right { width: 100%; }
    .est-modal-form .modal-dialog { max-width: 100% !important; margin: 0.5rem; }
    .est-modal-form .modal-body { max-height: calc(100vh - 180px); }
    .est-stu-email { max-width: 160px; }
}
@media (max-width: 768px) {
    .est-search-row { flex-direction: column; }
    .est-search-input { min-width: 100%; }
    .est-card-header { flex-direction: column; align-items: flex-start; }
    .est-table thead th, .est-table tbody td { padding: 0.6rem 0.75rem; font-size: 0.78rem; }
    .est-btn-search, .est-btn-register { width: 100%; justify-content: center; }
    .est-modal-form .modal-body-inner { padding: 1rem; }
    .est-stu-avatar { width: 32px; height: 32px; font-size: 0.68rem; }
    .est-action-cell { gap: 0.15rem; }
    .est-btn-action { width: 30px; height: 30px; }
    .est-card .dataTables_wrapper .dataTables_filter input { min-width: 100px; }
}
@media (max-width: 576px) {
    .est-table thead th, .est-table tbody td { padding: 0.5rem 0.6rem; font-size: 0.74rem; }
    .est-table thead th { font-size: 0.63rem; letter-spacing: 0.05em; }
    .est-stu-avatar { width: 28px; height: 28px; font-size: 0.62rem; }
    .est-stu-name { font-size: 0.82rem; }
    .est-stu-ape { display: none; }
    .est-carnet-num { font-size: 0.78rem; }
    .est-btn-action { width: 28px; height: 28px; }
    .est-btn-action i { font-size: 0.82rem; }
}

/* ─── ESTUDIOS ─── */
.est-estudio-row { display:flex; align-items:center; gap:.5rem; padding:.6rem .75rem; border:1px solid var(--est-border); border-radius:10px; margin-bottom:.4rem; background:#fff; transition:box-shadow .2s; }
.est-estudio-row:hover { box-shadow:var(--est-shadow-sm); }
.est-estudio-principal { flex-shrink:0; width:26px; height:26px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.8rem; cursor:pointer; transition:all .2s; }
.est-estudio-principal.is-principal { background:linear-gradient(135deg,var(--est-primary),var(--est-primary-dark)); color:#fff; box-shadow:0 2px 6px rgba(252,123,4,.3); }
.est-estudio-principal.not-principal { background:#f0ece8; color:var(--est-text-muted); }
.est-estudio-principal.not-principal:hover { background:rgba(252,123,4,.1); color:var(--est-primary); }
.est-estudio-info { flex:1; min-width:0; }
.est-estudio-grado { font-size:.82rem; font-weight:700; color:var(--est-text); }
.est-estudio-sub { font-size:.72rem; color:var(--est-text-muted); margin-top:.1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.est-estudio-estado { font-size:.68rem; font-weight:600; padding:.15rem .5rem; border-radius:20px; flex-shrink:0; }
.est-estudio-estado.concluido { background:rgba(46,154,110,.1); color:var(--est-success); }
.est-estudio-estado.en-desarrollo { background:rgba(240,160,48,.1); color:var(--est-warning); }
.est-estudio-del { background:none; border:none; cursor:pointer; color:var(--est-text-muted); font-size:.95rem; padding:.2rem; border-radius:6px; transition:all .2s; flex-shrink:0; }
.est-estudio-del:hover { background:rgba(224,80,80,.08); color:var(--est-danger); }
</style>
@endsection

@section('content')
<div class="est-page">
<div class="est-header-page">
    <div class="container-fluid">
        <div class="est-header-inner">
            <div class="est-header-left est-animate est-animate-1">
                <div class="est-header-icon"><i class="ri-graduation-cap-line"></i></div>
                <div>
                    <h1>Estudiantes</h1>
                    <p>Gestión y registro de estudiantes</p>
                </div>
            </div>
    <div class="est-header-right est-animate est-animate-2">
        <div class="est-stat-card">
            <div class="est-stat-icon"><i class="ri-hashtag"></i></div>
                    <div>
                        <div class="est-stat-num" id="stat-total">—</div>
                        <div class="est-stat-label">Total Registros</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    {{-- ═══ SEARCH + REGISTER ═══ --}}
    <div class="est-search-card est-animate est-animate-3">
        <div class="est-search-row">
            <div class="est-search-input">
                <label><i class="ri-id-card-line"></i> Buscar por Carnet</label>
                <input type="text" class="form-control" id="searchCarnet" placeholder="Ingrese el número de carnet…" maxlength="20" autocomplete="off">
            </div>
            <button type="button" class="est-btn est-btn-search" id="btnBuscar">
                <i class="ri-search-line"></i> Buscar
            </button>
            <button type="button" class="est-btn est-btn-register" id="btnAbrirRegistro">
                <i class="ri-graduation-cap-line"></i> Registrar Estudiante
            </button>
            <button type="button" class="est-btn-cancel" id="btnLimpiarBusqueda" style="display:none;">
                <i class="ri-close-line"></i> Limpiar
            </button>
        </div>
    </div>

    {{-- ═══ PERSONA FOUND ═══ --}}
    <div class="est-persona-found" id="personaFound">
        <div class="est-pf-header">
            <h5><i class="ri-user-line"></i> Persona Encontrada</h5>
            <span class="est-pf-badge" id="pfCarnet"></span>
        </div>
        <div class="est-pf-body">
            <div class="est-pf-grid">
                <div class="est-pf-item"><label>Nombres</label><span id="pfNombres"></span></div>
                <div class="est-pf-item"><label>Apellido Paterno</label><span id="pfApPaterno"></span></div>
                <div class="est-pf-item"><label>Apellido Materno</label><span id="pfApMaterno"></span></div>
                <div class="est-pf-item"><label>Sexo</label><span id="pfSexo"></span></div>
                <div class="est-pf-item"><label>Estado Civil</label><span id="pfEstadoCivil"></span></div>
                <div class="est-pf-item"><label>Correo</label><span id="pfCorreo"></span></div>
                <div class="est-pf-item"><label>Celular</label><span id="pfCelular"></span></div>
                <div class="est-pf-item"><label>Ciudad</label><span id="pfCiudad"></span></div>
            </div>
            <div id="yaEstudianteBox" class="est-ya-estudiante" style="display:none;">
                <i class="ri-checkbox-circle-fill"></i>
                <span>Esta persona ya está registrada como estudiante.</span>
            </div>
        </div>
    </div>

    {{-- ═══ TABLE ═══ --}}
    <div class="row">
        <div class="col-12">
            <div class="est-card">
                <div class="est-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="est-card-header-icon"><i class="ri-table-line"></i></div>
                        <div>
                            <h5 class="est-card-title">Listado de Estudiantes</h5>
                            <p class="est-card-subtitle">Consulta y gestiona los estudiantes registrados</p>
                        </div>
                    </div>
                </div>
                <div class="est-card-body">
                    <div class="est-table-wrap">
                        <table id="tabla-estudiantes" class="est-table">
                            <thead>
                                <tr>
                                    <th>Carnet</th>
                                    <th>Estudiante</th>
                                    <th>Celular</th>
                                    <th class="text-center">Cuentas</th>
                                    <th class="text-center">Acciones</th>
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

{{-- ════════════════ MODAL REGISTRAR ESTUDIANTE ════════════════ --}}
<div class="modal fade est-modal-form" id="modalRegistro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistroTitle"><i class="ri-graduation-cap-line"></i> Registrar Estudiante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formRegistro" novalidate autocomplete="off" enctype="multipart/form-data">
                <div class="modal-body">
                    <div id="registroYaEstudiante" class="est-ya-estudiante" style="display:none;">
                        <i class="ri-checkbox-circle-fill"></i>
                        <span>Esta persona ya está registrada como estudiante.</span>
                    </div>

                    {{-- Photo --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="est-photo-wrap">
                                <div class="est-photo-circle" onclick="document.getElementById('fotografiaRegistro').click()">
                                    <img id="previewFotografiaRegistro" src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" alt="Foto">
                                    <div class="est-photo-overlay"><i class="ri-camera-line"></i></div>
                                    <input type="file" id="fotografiaRegistro" name="fotografia" accept="image/*" style="display:none;" onchange="previewImage(this, 'previewFotografiaRegistro')">
                                </div>
                                <span class="est-photo-hint">Click para cambiar foto</span>
                            </div>
                        </div>
                    </div>

                    {{-- Identity Section --}}
                    <div class="est-modal-section-header"><i class="ri-id-card-line"></i><span>Datos de Identidad</span></div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="est-label">Carnet <span class="req">*</span></label>
                            <div class="est-field">
                                <input type="text" class="form-control" id="rCarnet" placeholder="Ej: 12345678" maxlength="20" autocomplete="off">
                                <span class="est-validation-icon" id="iconRCarnet"></span>
                            </div>
                            <div class="est-feedback" id="fbRCarnet"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="est-label">Expedido</label>
                            <input type="text" class="form-control" id="rExpedido" placeholder="Ej: LP" maxlength="10" autocomplete="off">
                        </div>
                        <div class="col-md-5">
                            <label class="est-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="rFechaNacimiento">
                        </div>
                        <div class="col-md-6">
                            <label class="est-label">Nombres <span class="req">*</span></label>
                            <div class="est-field">
                                <input type="text" class="form-control" id="rNombres" placeholder="Ej: Juan Carlos" maxlength="100" autocomplete="off">
                                <span class="est-validation-icon" id="iconRNombres"></span>
                            </div>
                            <div class="est-feedback" id="fbRNombres"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="est-label">Ap. Paterno</label>
                            <div class="est-field">
                                <input type="text" class="form-control" id="rApPaterno" placeholder="García" maxlength="80" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="est-label">Ap. Materno</label>
                            <div class="est-field">
                                <input type="text" class="form-control" id="rApMaterno" placeholder="López" maxlength="80" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="est-feedback" id="fbRApellidos"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="est-label">Sexo</label>
                            <select class="form-select" id="rSexo">
                                <option value="">— Seleccione —</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="est-label">Estado Civil</label>
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
                            <label class="est-label">Departamento</label>
                            <select class="form-select" id="rDepto">
                                <option value="">— Seleccione —</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="est-label">Ciudad</label>
                            <select class="form-select" id="rCiudad" disabled>
                                <option value="">— Seleccione depto. —</option>
                            </select>
                        </div>
                    </div>

                    {{-- Contact Section --}}
                    <div class="est-modal-section-header mt-4"><i class="ri-phone-line"></i><span>Datos de Contacto</span></div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="est-label">Correo Electrónico</label>
                            <div class="est-field">
                                <input type="email" class="form-control" id="rCorreo" placeholder="correo@dominio.com" maxlength="150" autocomplete="off">
                                <span class="est-validation-icon" id="iconRCorreo"></span>
                            </div>
                            <div class="est-feedback" id="fbRCorreo"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="est-label">Celular</label>
                            <input type="text" class="form-control" id="rCelular" placeholder="70000000" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label class="est-label">Teléfono</label>
                            <input type="text" class="form-control" id="rTelefono" placeholder="2000000" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-12">
                            <label class="est-label">Dirección</label>
                            <input type="text" class="form-control" id="rDireccion" placeholder="Av. 6 de Agosto N° 123" maxlength="200" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="est-btn-cancel" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                    <button type="submit" class="est-btn-submit" id="btnGuardarEstudiante"><i class="ri-save-line"></i> Registrar Estudiante</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════ MODAL EDITAR ════════════════ --}}
<div class="modal fade est-modal-form" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-pencil-line"></i> Editar Estudiante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditar" novalidate autocomplete="off" enctype="multipart/form-data">
                <input type="hidden" id="editId">
                <div class="modal-body">
                    {{-- Photo --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="est-photo-wrap">
                                <div class="est-photo-circle" onclick="document.getElementById('fotografiaEditar').click()">
                                    <img id="previewFotografiaEditar" src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" alt="Foto">
                                    <div class="est-photo-overlay"><i class="ri-camera-line"></i></div>
                                    <input type="file" id="fotografiaEditar" name="fotografia" accept="image/*" style="display:none;" onchange="previewImage(this, 'previewFotografiaEditar')">
                                </div>
                                <span class="est-photo-hint">Click para cambiar foto</span>
                            </div>
                        </div>
                    </div>

                    {{-- Identity Section --}}
                    <div class="est-modal-section-header"><i class="ri-id-card-line"></i><span>Datos de Identidad</span></div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="est-label">Carnet <span class="req">*</span></label>
                            <div class="est-field">
                                <input type="text" class="form-control" id="editCarnet" maxlength="20" autocomplete="off">
                                <span class="est-validation-icon" id="iconECarnet"></span>
                            </div>
                            <div class="est-feedback" id="fbECarnet"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="est-label">Expedido</label>
                            <input type="text" class="form-control" id="editExpedido" maxlength="10" autocomplete="off">
                        </div>
                        <div class="col-md-5">
                            <label class="est-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="editFechaNacimiento">
                        </div>
                        <div class="col-md-6">
                            <label class="est-label">Nombres <span class="req">*</span></label>
                            <div class="est-field">
                                <input type="text" class="form-control" id="editNombres" maxlength="100" autocomplete="off">
                                <span class="est-validation-icon" id="iconENombres"></span>
                            </div>
                            <div class="est-feedback" id="fbENombres"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="est-label">Ap. Paterno</label>
                            <input type="text" class="form-control" id="editApPaterno" maxlength="80" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label class="est-label">Ap. Materno</label>
                            <input type="text" class="form-control" id="editApMaterno" maxlength="80" autocomplete="off">
                        </div>
                        <div class="col-12">
                            <div class="est-feedback" id="fbEApellidos"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="est-label">Sexo</label>
                            <select class="form-select" id="editSexo">
                                <option value="">— Seleccione —</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="est-label">Estado Civil</label>
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
                            <label class="est-label">Departamento</label>
                            <select class="form-select" id="editDepto">
                                <option value="">— Seleccione —</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="est-label">Ciudad</label>
                            <select class="form-select" id="editCiudad" disabled>
                                <option value="">— Seleccione depto. —</option>
                            </select>
                        </div>
                    </div>

                    {{-- Contact Section --}}
                    <div class="est-modal-section-header mt-4"><i class="ri-phone-line"></i><span>Datos de Contacto</span></div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="est-label">Correo Electrónico</label>
                            <div class="est-field">
                                <input type="email" class="form-control" id="editCorreo" maxlength="150" autocomplete="off">
                                <span class="est-validation-icon" id="iconECorreo"></span>
                            </div>
                            <div class="est-feedback" id="fbECorreo"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="est-label">Celular</label>
                            <input type="text" class="form-control" id="editCelular" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label class="est-label">Teléfono</label>
                            <input type="text" class="form-control" id="editTelefono" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-12">
                            <label class="est-label">Dirección</label>
                            <input type="text" class="form-control" id="editDireccion" maxlength="200" autocomplete="off">
                        </div>
                    </div>

                    {{-- Studies Section --}}
                    <div class="est-modal-section-header mt-4"><i class="ri-graduation-cap-line"></i><span>Estudios Académicos</span></div>
                    <div id="editEstudiosContainer">
                        <div id="editEstudiosLoading" class="text-center py-2" style="display:none;">
                            <span class="spinner-border spinner-border-sm" style="color:var(--est-primary);"></span>
                            <span class="ms-2" style="font-size:.8rem;color:var(--est-text-muted);">Cargando estudios...</span>
                        </div>
                        <div id="editEstudiosList"></div>
                        {{-- Form: add new study --}}
                        <div id="editEstudioFormWrap" style="display:none;margin-top:.75rem;padding:1rem;background:#faf8f5;border:1px solid var(--est-border);border-radius:12px;">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="est-label" style="font-size:.75rem;">Grado Académico <span class="req">*</span></label>
                                    <select class="form-select form-select-sm" id="newEstGrado"></select>
                                </div>
                                <div class="col-md-4">
                                    <label class="est-label" style="font-size:.75rem;">Profesión</label>
                                    <select class="form-select form-select-sm" id="newEstProfesion"></select>
                                </div>
                                <div class="col-md-4">
                                    <label class="est-label" style="font-size:.75rem;">Universidad</label>
                                    <select class="form-select form-select-sm" id="newEstUniversidad"></select>
                                </div>
                                <div class="col-md-3">
                                    <label class="est-label" style="font-size:.75rem;">Estado</label>
                                    <select class="form-select form-select-sm" id="newEstEstado">
                                        <option value="Concluido">Concluido</option>
                                        <option value="En Desarrollo">En Desarrollo</option>
                                    </select>
                                </div>
                                <div class="col-md-9 d-flex align-items-end gap-2">
                                    <button type="button" class="est-btn-submit btn-sm" id="btnGuardarNuevoEstudio" style="padding:.4rem 1rem;font-size:.82rem;">
                                        <i class="ri-check-line"></i> Agregar
                                    </button>
                                    <button type="button" class="est-btn-cancel btn-sm" id="btnCancelarNuevoEstudio" style="padding:.4rem .75rem;font-size:.82rem;">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="btnAddEstudio" style="margin-top:.75rem;display:inline-flex;align-items:center;gap:.35rem;font-size:.8rem;font-weight:600;color:var(--est-primary);background:rgba(252,123,4,.06);border:1px dashed rgba(252,123,4,.3);border-radius:8px;padding:.4rem .85rem;cursor:pointer;transition:all .2s;">
                            <i class="ri-add-line"></i> Agregar Estudio
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="est-btn-cancel" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                    <button type="submit" class="est-btn-submit" id="btnGuardarEdicion"><i class="ri-save-line"></i> Guardar Cambios</button>
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
                <div class="est-delete-box">
                    <div class="est-delete-icon"><i class="ri-delete-bin-5-line"></i></div>
                    <p class="est-delete-msg">¿Eliminar estudiante?</p>
                    <p class="est-delete-name"><strong id="nombreEliminar"></strong></p>
                    <p class="est-delete-warn"><i class="ri-information-line"></i> Esta acción es permanente y no puede deshacerse.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-3">
                <button type="button" class="btn est-btn-cancel px-4" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                <button type="button" class="btn est-btn-danger px-4" id="btnConfirmarEliminar"><i class="ri-delete-bin-line"></i> Eliminar</button>
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
                <div style="background:rgba(240,160,48,0.08);border:1px solid rgba(240,160,48,0.2);border-radius:10px;padding:0.7rem 1rem;display:flex;gap:0.6rem;align-items:flex-start;font-size:0.82rem;margin-bottom:1rem;">
                    <i class="ri-alert-line" style="font-size:1.1rem;flex-shrink:0;margin-top:1px;color:var(--est-warning);"></i>
                    <span style="color:var(--est-text);">Guarde estas credenciales y compártalas con el estudiante. <strong>No serán mostradas nuevamente.</strong></span>
                </div>
                <div id="credencialesContent"></div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn est-btn-submit px-4" data-bs-dismiss="modal"><i class="ri-check-line me-1"></i>Entendido</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalWhatsappAccesos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#25d366,#128c7e);color:#fff;border-radius:12px 12px 0 0;">
                <h5 class="modal-title"><i class="ri-whatsapp-line me-2"></i>Enviar accesos por WhatsApp</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <p class="mb-3" style="font-size:0.9rem;color:var(--est-text);">
                    Se enviará el siguiente mensaje con los accesos a la plataforma Moodle.
                </p>
                <div style="background:#f4f8f4;border:1px solid #d0e8d0;border-radius:10px;padding:1rem 1.2rem;font-size:0.87rem;line-height:1.7;">
                    <div><strong>Estudiante:</strong> <span id="waModalNombre"></span></div>
                    <div><strong>Usuario:</strong> <span id="waModalUsuario" style="font-family:monospace;"></span></div>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <strong>Contraseña:</strong>
                        <span id="waModalPassword" style="font-family:monospace;background:#fff;border:1px solid #c8e6c8;border-radius:6px;padding:2px 8px;"></span>
                    </div>
                </div>
                <div style="background:rgba(255,193,7,0.1);border:1px solid rgba(255,193,7,0.3);border-radius:8px;padding:0.7rem 1rem;font-size:0.81rem;margin-top:0.9rem;color:#7a6000;">
                    <i class="ri-information-line me-1"></i>
                    Si el estudiante cambió su contraseña en Moodle, usa <strong>Restablecer</strong> para sincronizarla al valor original antes de enviar.
                </div>
                <input type="hidden" id="waModalCelular">
                <input type="hidden" id="waModalEstudianteId">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" id="waModalBtnReset" class="btn btn-outline-secondary btn-sm">
                    <i class="ri-refresh-line me-1"></i>Restablecer contraseña en Moodle
                </button>
                <button type="button" id="waModalBtnEnviar" class="btn btn-success btn-sm px-4">
                    <i class="ri-whatsapp-line me-1"></i>Enviar por WhatsApp
                </button>
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
    const CSRF = '{{ csrf_token() }}';

    function init() {
        cargarSelectores();
        initDataTable();
        bindEvents();
    }

    function cargarSelectores() {
        $.getJSON('{{ route("admin.estudiantes.listarDepartamentos") }}', function (r) {
            const opts = r.data.map(d => '<option value="' + d.id + '">' + esc(d.nombre) + '</option>').join('');
            $('#rDepto').append(opts);
            $('#editDepto').append(opts);
        });
        $.getJSON('{{ route("admin.estudiantes.listarCiudades") }}', function (r) {
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
        tabla = $('#tabla-estudiantes').DataTable({
            ajax: { url: '{{ route("admin.estudiantes.listar") }}', dataSrc: 'data' },
            ordering: true,
            responsive: true,
            autoWidth: false,
            columns: [
                {
                    data: null, width: '95px', responsivePriority: 2,
                    render: d => {
                        const p = d.persona;
                        if (!p) return '<span class="text-muted">—</span>';
                        let html = '<div class="est-carnet-cell">'
                            + '<span class="est-carnet-num">' + esc(p.carnet) + '</span>';
                        if (p.expedido) html += '<span class="est-carnet-exp">exp. ' + esc(p.expedido) + '</span>';
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
                        const correo = p.correo ? '<div class="est-stu-email">' + esc(p.correo) + '</div>' : '';
                        const initials = ((p.nombres || '').charAt(0) + (p.apellido_paterno || '').charAt(0)).toUpperCase();
                        return '<div class="est-stu-cell">'
                            + '<div class="est-stu-avatar">' + esc(initials) + '</div>'
                            + '<div class="est-stu-info">'
                            + '<div class="est-stu-name">' + n + '</div>'
                            + (ap ? '<div class="est-stu-ape">' + ap + '</div>' : '')
                            + correo
                            + '</div></div>';
                    }
                },
                {
                    data: null, width: '110px', responsivePriority: 10004,
                    render: d => {
                        const p = d.persona;
                        return p && p.celular
                            ? '<div class="est-cel-cell"><i class="ri-phone-line"></i>' + esc(p.celular) + '</div>'
                            : '<span class="text-muted">—</span>';
                    }
                },
                {
                    data: null, className: 'text-center', width: '120px', responsivePriority: 10003,
                    render: d => {
                        const sis = d.tiene_cuenta_sistema
                            ? '<span class="est-badge-si"><i class="ri-check-line"></i>Sistema</span>'
                            : '<span class="est-badge-no"><i class="ri-close-line"></i>Sistema</span>';
                        const mood = d.tiene_cuenta_moodle
                            ? '<span class="est-badge-si"><i class="ri-check-line"></i>Moodle</span>'
                            : '<span class="est-badge-no"><i class="ri-close-line"></i>Moodle</span>';
                        return '<div style="display:flex;flex-direction:column;gap:4px;align-items:flex-start;">' + sis + mood + '</div>';
                    }
                },
                {
                    data: null, className: 'text-center', width: '120px', responsivePriority: 3,
                    render: d => {
                        const nombre = esc(d.persona ? d.persona.nombres + ' ' + (d.persona.apellido_paterno || '') : 'Estudiante');
                        const celular = d.persona ? (d.persona.celular || '') : '';
                        const celularLimpio = celular.replace(/\D/g, '');
                        const tieneMoodle = d.tiene_cuenta_moodle;
                        const username = d.usuario_username || '';
                        const carnet = d.persona ? (d.persona.carnet || '') : '';
                        const password = d.usuario_moodle_password || generarPassword(carnet);

                        let btns = '<div class="est-action-cell">'
                            + '<a class="btn est-btn-action" href="/admin/estudiantes/' + d.id + '/detalle" title="Ver estudiante"><i class="ri-eye-line"></i></a>'
                            + '<button class="btn est-btn-action est-btn-edit btn-accion-editar" data-id="' + d.id + '" title="Editar estudiante"><i class="ri-pencil-fill"></i></button>';
                        if (!d.tiene_cuenta_sistema || !d.tiene_cuenta_moodle) {
                            const titulo = d.tiene_cuenta_sistema ? 'Crear cuenta Moodle' : (d.tiene_cuenta_moodle ? 'Crear cuenta sistema' : 'Crear cuentas (sistema + Moodle)');
                            btns += '<button class="btn est-btn-action est-btn-cuenta btn-crear-cuentas" data-id="' + d.id + '" title="' + titulo + '"><i class="ri-user-add-line"></i></button>';
                        }
                        if (celularLimpio.length >= 8 && tieneMoodle && username) {
                            btns += '<button class="btn est-btn-action est-btn-whatsapp" '
                                + 'data-celular="' + celularLimpio + '" '
                                + 'data-nombre="' + nombre + '" '
                                + 'data-username="' + username + '" '
                                + 'data-password="' + password + '" '
                                + 'data-estudiante-id="' + d.id + '" '
                                + 'title="Enviar accesos por WhatsApp"><i class="ri-whatsapp-line"></i></button>';
                        } else if (celularLimpio.length >= 8 && tieneMoodle && !username) {
                            btns += '<button class="btn est-btn-action" title="Sin usuario" disabled style="opacity:0.3;cursor:not-allowed;"><i class="ri-whatsapp-line"></i></button>';
                        } else {
                            btns += '<button class="btn est-btn-action" title="Sin cuenta Moodle" disabled style="opacity:0.3;cursor:not-allowed;"><i class="ri-whatsapp-line"></i></button>';
                        }
                        btns += '<button class="btn est-btn-action est-btn-delete btn-accion-eliminar" data-id="' + d.id + '" data-nombre="' + nombre + '" title="Eliminar estudiante"><i class="ri-delete-bin-fill"></i></button>'
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

        $('#formRegistro').on('submit', function (e) { e.preventDefault(); guardarEstudiante(); });
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
            if (idEliminar) eliminarEstudiante(idEliminar);
        });

        $(document).on('click', '.btn-accion-editar', function () {
            editarEstudiante($(this).data('id'));
        });

        $(document).on('click', '.btn-crear-cuentas', function () {
            crearCuentas($(this).data('id'));
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
            if (!personaId) return;
            var gradoId = $('#newEstGrado').val();
            if (!gradoId) { toast('warning', 'El grado académico es obligatorio.'); return; }
            var $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
            var estudiosActuales = $('#editEstudiosList .est-estudio-row').length;
            $.post('/admin/personas/' + personaId + '/estudios', {
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
                recargarEstudios(personaId);
            })
            .fail(function(xhr) {
                toast('error', xhr.responseJSON?.message || 'Error al agregar estudio.');
            })
            .always(function() { $btn.prop('disabled', false).html('<i class="ri-check-line"></i> Agregar'); });
        });

        $(document).on('click', '.btn-del-estudio', function() {
            var personaId = $(this).data('persona-id');
            var estudioId = $(this).data('estudio-id');
            if (!confirm('¿Eliminar este estudio?')) return;
            $.ajax({ url: '/admin/personas/' + personaId + '/estudios/' + estudioId, type: 'POST', data: { _token: CSRF, _method: 'DELETE' } })
                .done(function(r) { toast('success', r.message); recargarEstudios(personaId); })
                .fail(function() { toast('error', 'Error al eliminar estudio.'); });
        });

        $(document).on('click', '.est-estudio-principal.not-principal', function() {
            var personaId = $(this).data('persona-id');
            var estudioId = $(this).data('estudio-id');
            var $btn = $(this);
            $btn.prop('disabled', true);
            $.get('{{ route("admin.estudiantes.obtener", ["id" => "__ID__"]) }}'.replace('__ID__', $('#formEditar').data('estudiante-id')))
                .done(function(r) {
                    var estudios = (r.data.persona && r.data.persona.estudios) ? r.data.persona.estudios : [];
                    var est = estudios.find(function(e) { return e.id == estudioId; });
                    if (!est) { toast('error', 'Estudio no encontrado.'); return; }
                    $.ajax({
                        url: '/admin/personas/' + personaId + '/estudios/' + estudioId,
                        type: 'POST',
                        data: {
                            _token: CSRF,
                            _method: 'PUT',
                            grados_academico_id: est.grados_academico_id,
                            profesione_id: est.profesione_id || '',
                            universidade_id: est.universidade_id || '',
                            estado: est.estado,
                            principal: 1
                        }
                    })
                    .done(function(r2) { toast('success', 'Estudio marcado como principal.'); recargarEstudios(personaId); })
                    .fail(function() { toast('error', 'Error al actualizar.'); $btn.prop('disabled', false); });
                })
                .fail(function() { toast('error', 'Error al obtener datos.'); $btn.prop('disabled', false); });
        });
    }

    /* ── BUSCAR PERSONA ── */
    function buscarPersona() {
        const carnet = $('#searchCarnet').val().trim();
        if (!carnet) {
            toast('warning', 'Ingrese un número de carnet para buscar.');
            return;
        }

        setBtnLoading('#btnBuscar', true, 'Buscando…');
        $.post('{{ route("admin.estudiantes.buscarCarnet") }}', { _token: CSRF, carnet: carnet })
            .done(function (r) {
                if (r.encontrado) {
                    personaEncontrada = r.persona;
                    mostrarPersonaEncontrada(r.persona, r.ya_estudiante);
                } else {
                    $('#personaFound').slideUp(200);
                    toast('warning', 'No se encontró ninguna persona con el carnet: ' + carnet);
                }
            })
            .fail(function () { toast('error', 'Error al buscar. Intente nuevamente.'); })
            .always(function () { setBtnLoading('#btnBuscar', false, '<i class="ri-search-line"></i> Buscar'); });
    }

    function mostrarPersonaEncontrada(p, yaEstudiante) {
        $('#pfCarnet').text(p.carnet);
        $('#pfNombres').text(p.nombres || '—');
        $('#pfApPaterno').text(p.apellido_paterno || '—');
        $('#pfApMaterno').text(p.apellido_materno || '—');
        $('#pfSexo').text(p.sexo ? (p.sexo === 'M' ? 'Masculino' : 'Femenino') : '—');
        $('#pfEstadoCivil').text(p.estado_civil || '—');
        $('#pfCorreo').text(p.correo || '—');
        $('#pfCelular').text(p.celular || '—');
        $('#pfCiudad').text(p.ciudad ? p.ciudad.nombre : '—');

        if (yaEstudiante) {
            $('#yaEstudianteBox').show();
        } else {
            $('#yaEstudianteBox').hide();
        }

        $('#personaFound').slideDown(300);
        $('#btnLimpiarBusqueda').show();
    }

    /* ── ABRIR MODAL REGISTRO ── */
    function abrirModalRegistro() {
        resetFormRegistro();

        /* Si hay persona buscada, pre-llenar el formulario */
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

            /* Marcar campos como válidos */
            ['rCarnet','rNombres'].forEach(function (id) {
                const input = document.getElementById(id);
                if (input) { input.classList.remove('is-invalid'); input.classList.add('is-valid'); }
            });

            if (p.correo) {
                const input = document.getElementById('rCorreo');
                if (input) { input.classList.remove('is-invalid'); input.classList.add('is-valid'); }
            }

            /* Verificar si ya es estudiante */
            $.post('{{ route("admin.estudiantes.buscarCarnet") }}', { _token: CSRF, carnet: p.carnet })
                .done(function (r) {
                    if (r.ya_estudiante) {
                        $('#registroYaEstudiante').show();
                        $('#btnGuardarEstudiante').prop('disabled', true).html('<i class="ri-error-warning-line"></i> Ya es estudiante');
                    } else {
                        $('#registroYaEstudiante').hide();
                        $('#btnGuardarEstudiante').prop('disabled', false).html('<i class="ri-save-line"></i> Registrar Estudiante');
                    }
                });

            $('#modalRegistroTitle').html('<i class="ri-user-check-line"></i> Registrar como Estudiante — ' + esc(p.carnet));
        } else {
            $('#modalRegistroTitle').html('<i class="ri-graduation-cap-line"></i> Registrar Estudiante');
        }

        openModal('modalRegistro');
    }

    /* ── GUARDAR ESTUDIANTE ── */
    function guardarEstudiante() {
        const okC = validarCarnetSync('rCarnet','iconRCarnet','fbRCarnet');
        const okN = validarNombres('rNombres','iconRNombres','fbRNombres');
        const okAp = validarApellidos();
        if (!okC || !okN || !okAp) return;
        if (document.getElementById('rCarnet').classList.contains('is-invalid')) return;
        if (document.getElementById('rCorreo').classList.contains('is-invalid')) return;

        setBtnLoading('#btnGuardarEstudiante', true, 'Guardando…');
        
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
            url: '{{ route("admin.estudiantes.guardarPersona") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        })
        .done(function (r) {
            /* Persona creada, ahora registrar como estudiante */
            $.post('{{ route("admin.estudiantes.registrar") }}', {
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
                const msg = xhr.responseJSON?.message || 'Error al registrar como estudiante.';
                toast('error', msg);
            });
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                const errs = xhr.responseJSON.errors || {};
                if (errs.carnet) setError('rCarnet','iconRCarnet','fbRCarnet', errs.carnet[0]);
                if (errs.nombres) setError('rNombres','iconRNombres','fbRNombres', errs.nombres[0]);
                if (errs.correo) setError('rCorreo','iconRCorreo','fbRCorreo', errs.correo[0]);
                if (errs.apellidos) {
                    $('#fbRApellidos').addClass('error').html('<i class="ri-error-warning-line"></i>' + errs.apellidos[0]);
                }
            } else {
                toast('error', 'Error al registrar. Intente nuevamente.');
            }
        })
        .always(function () {
            setBtnLoading('#btnGuardarEstudiante', false, '<i class="ri-save-line"></i> Registrar Estudiante');
        });
    }

    /* ── EDITAR ESTUDIANTE ── */
    function editarEstudiante(id) {
        $.get('{{ route("admin.estudiantes.obtener", ["id" => "__ID__"]) }}'.replace('__ID__', id))
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

                /* Fotografía */
                if (p && p.fotografia) {
                    var fotoUrl = '{{ url("images/personas") }}/' + p.fotografia;
                    $('#previewFotografiaEditar').attr('src', fotoUrl);
                } else {
                    $('#previewFotografiaEditar').attr('src', '{{ URL::asset("build/images/users/avatar-1.jpg") }}');
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

                /* Reset validation */
                ['editCarnet','editNombres','editCorreo'].forEach(function (id) {
                    resetField(id, 'icon' + id.charAt(4).toUpperCase() + id.slice(5), 'fb' + id.charAt(4).toUpperCase() + id.slice(5));
                });
                resetField('editCarnet', 'iconECarnet', 'fbECarnet');
                resetField('editNombres', 'iconENombres', 'fbENombres');
                resetField('editCorreo', 'iconECorreo', 'fbECorreo');
                $('#fbEApellidos').removeClass('error success').html('');

                /* Store id for submit */
                $('#formEditar').data('estudiante-id', id);
                $('#formEditar').data('persona-id', p ? p.id : null);

                openModal('modalEditar');

                var estudios = (e.persona && e.persona.estudios) ? e.persona.estudios : [];
                var personaId = p ? p.id : null;
                renderEstudios(estudios, personaId);
            })
            .fail(function () { toast('error', 'Error al cargar los datos.'); });
    }

    /* ── GUARDAR EDICION ── */
    function guardarEdicion() {
        const id = $('#formEditar').data('estudiante-id');
        const personaId = $('#formEditar').data('persona-id');
        if (!id || !personaId) return;

        const okC = validarCarnetSync('editCarnet','iconECarnet','fbECarnet');
        const okN = validarNombres('editNombres','iconENombres','fbENombres');
        const okAp = validarApellidosEdit();
        if (!okC || !okN || !okAp) return;
        if (document.getElementById('editCarnet').classList.contains('is-invalid')) return;
        if (document.getElementById('editCorreo').classList.contains('is-invalid')) return;

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
                if (errs.carnet) setError('editCarnet','iconECarnet','fbECarnet', errs.carnet[0]);
                if (errs.nombres) setError('editNombres','iconENombres','fbENombres', errs.nombres[0]);
                if (errs.correo) setError('editCorreo','iconECorreo','fbECorreo', errs.correo[0]);
            } else {
                toast('error', 'Error al actualizar. Intente nuevamente.');
            }
        })
        .always(function () {
            setBtnLoading('#btnGuardarEdicion', false, '<i class="ri-save-line"></i> Guardar Cambios');
        });
    }

    function validarApellidosEdit() {
        const p = $('#editApPaterno').val().trim();
        const m = $('#editApMaterno').val().trim();
        const fb = document.getElementById('fbEApellidos');
        if (!p && !m) {
            fb.className = 'est-feedback error';
            fb.innerHTML = '<i class="ri-error-warning-line"></i>Debe registrar al menos un apellido.';
            return false;
        }
        fb.className = 'est-feedback success';
        fb.innerHTML = '<i class="ri-check-line"></i>Apellido(s) válido(s)';
        return true;
    }

    /* ── VER ESTUDIANTE ── */
    function verEstudiante(id) {
        $.get('{{ route("admin.estudiantes.obtener", ["id" => "__ID__"]) }}'.replace('__ID__', id))
            .done(function (r) {
                const e = r.data;
                const p = e.persona;
                $('#editCarnet').val(p ? p.carnet : '—');
                $('#editNombres').val(p ? p.nombres : '—');
                $('#editApPaterno').val(p ? p.apellido_paterno || '' : '—');
                $('#editApMaterno').val(p ? p.apellido_materno || '' : '—');
                $('#editCorreo').val(p && p.correo ? p.correo : '—');
                $('#editCelular').val(p && p.celular ? p.celular : '—');
                $('#editCiudad').val(p && p.ciudad ? p.ciudad.nombre : '—');
                openModal('modalEditar');
            })
            .fail(function () { toast('error', 'Error al cargar los datos.'); });
    }

    /* ── ELIMINAR ESTUDIANTE ── */
    function eliminarEstudiante(id) {
        setBtnLoading('#btnConfirmarEliminar', true, '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
        $.ajax({ url: '/admin/estudiantes/' + id, type: 'DELETE', data: { _token: CSRF } })
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

    /* ── LIMPIAR BÚSQUEDA ── */
    function limpiarBusqueda() {
        $('#searchCarnet').val('');
        $('#personaFound').slideUp(200);
        $('#btnLimpiarBusqueda').hide();
        personaEncontrada = null;
    }

    /* ── RESET FORM REGISTRO ── */
    function resetFormRegistro() {
        $('#formRegistro')[0].reset();
        resetField('rCarnet', 'iconRCarnet', 'fbRCarnet');
        resetField('rNombres', 'iconRNombres', 'fbRNombres');
        resetField('rCorreo', 'iconRCorreo', 'fbRCorreo');
        $('#fbRApellidos').removeClass('error success').html('');
        $('#rDepto').val('');
        $('#rCiudad').find('option:not(:first)').remove().end().prop('disabled', true).find('option:first').text('— Seleccione depto. —');
        
        /* Reset fotografía */
        $('#previewFotografiaRegistro').attr('src', '{{ URL::asset("build/images/users/avatar-1.jpg") }}');

        /* Reset readonly/disabled */
        ['rCarnet','rNombres','rApPaterno','rApMaterno','rCorreo','rCelular','rTelefono','rDireccion','rExpedido','rFechaNacimiento'].forEach(function (id) {
            $('#' + id).prop('readonly', false);
        });
        ['rSexo','rEstadoCivil','rCiudad'].forEach(function (id) {
            $('#' + id).prop('disabled', false);
        });

        $('#registroYaEstudiante').hide();
        $('#btnGuardarEstudiante').prop('disabled', false).html('<i class="ri-save-line"></i> Registrar Estudiante');
        $('#modalRegistroTitle').html('<i class="ri-graduation-cap-line"></i> Registrar Estudiante');
    }

    /* ════════════════════ VALIDACIÓN ════════════════════ */

    function setError(inputId, iconId, fbId, msg) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        if (icon) { icon.className = 'est-validation-icon invalid'; icon.innerHTML = '<i class="ri-close-circle-fill"></i>'; }
        if (fb) { fb.className = 'est-feedback error'; fb.innerHTML = '<i class="ri-error-warning-line"></i>' + msg; }
        return false;
    }

    function setOk(inputId, iconId, fbId, msg) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        if (icon) { icon.className = 'est-validation-icon valid'; icon.innerHTML = '<i class="ri-checkbox-circle-fill"></i>'; }
        if (fb) { fb.className = 'est-feedback success'; fb.innerHTML = '<i class="ri-check-line"></i>' + msg; }
        return true;
    }

    function setChecking(inputId, iconId, fbId) {
        const icon = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        if (icon) { icon.className = 'est-validation-icon'; icon.innerHTML = '<i class="ri-loader-4-line" style="animation:spin 1s linear infinite;"></i>'; }
        if (fb) { fb.className = 'est-feedback'; fb.innerHTML = 'Verificando…'; }
    }

    function resetField(inputId, iconId, fbId) {
        const input = document.getElementById(inputId);
        if (input) input.classList.remove('is-valid', 'is-invalid');
        const icon = document.getElementById(iconId);
        if (icon) { icon.className = 'est-validation-icon'; icon.innerHTML = ''; }
        const fb = document.getElementById(fbId);
        if (fb) { fb.className = 'est-feedback'; fb.innerHTML = ''; }
    }

    function isEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

    function verificarCarnetRegistro(val) {
        $.post('{{ route("admin.estudiantes.verificarCarnetPersona") }}', { _token: CSRF, carnet: val }, function (r) {
            if (r.existe) setError('rCarnet','iconRCarnet','fbRCarnet','Este carnet ya está registrado.');
            else setOk('rCarnet','iconRCarnet','fbRCarnet','Carnet disponible');
        }).fail(function () { resetField('rCarnet','iconRCarnet','fbRCarnet'); });
    }

    function verificarCorreoRegistro(val) {
        $.post('{{ route("admin.estudiantes.verificarCorreoPersona") }}', { _token: CSRF, correo: val }, function (r) {
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
            fb.className = 'est-feedback error';
            fb.innerHTML = '<i class="ri-error-warning-line"></i>Debe registrar al menos un apellido (paterno o materno).';
            return false;
        }
        fb.className = 'est-feedback success';
        fb.innerHTML = '<i class="ri-check-line"></i>Apellido(s) válido(s)';
        return true;
    }

    /* ════════════════════ CUENTAS ════════════════════ */

    function crearCuentas(id) {
        if (!confirm('¿Crear cuentas de acceso (sistema + Moodle) para este estudiante?\n\nAmbas usarán las mismas credenciales.')) return;
        $.post('/admin/estudiantes/' + id + '/crear-cuentas', { _token: CSRF })
            .done(function (r) {
                toast('success', r.message);
                mostrarCredenciales(r.data);
                tabla.ajax.reload(null, false);
            })
            .fail(function (xhr) {
                toast('error', xhr.responseJSON?.message || 'Error al crear las cuentas.');
            });
    }

    function mostrarCredenciales(data) {
        const titulo = document.getElementById('modalCredencialesTitle');
        titulo.innerHTML = '<i class="ri-key-2-line"></i> Credenciales Generadas';
        let html = '';

        if (data.sistema) {
            html += '<p style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:var(--est-text-muted);margin-bottom:0.5rem;">Acceso al Sistema</p>';
            if (data.sistema.username) html += credRow('Usuario', data.sistema.username);
            html += credRow('Correo', data.sistema.email);
            html += credRow('Contraseña', data.sistema.password);
        }
        if (data.moodle) {
            if (html) html += '<hr style="border-color:var(--est-border);margin:1rem 0;">';
            html += '<p style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:var(--est-text-muted);margin-bottom:0.5rem;">Acceso a Moodle</p>';
            html += credRow('Usuario Moodle', data.moodle.username);
            html += credRow('Correo', data.moodle.email);
            if (!data.moodle.nota) html += credRow('Contraseña', data.moodle.password);
            else html += '<p style="font-size:0.82rem;color:var(--est-text-muted);margin-top:0.5rem;"><i class="ri-information-line"></i> ' + esc(data.moodle.nota) + '</p>';
        }

        document.getElementById('credencialesContent').innerHTML = html;
        openModal('modalCredenciales');
    }

    function credRow(label, valor) {
        const v = esc(valor);
        return '<div class="est-cred-item mt-2">'
            + '<label>' + label + '</label>'
            + '<div class="est-cred-value"><span>' + v + '</span>'
            + '<button class="est-cred-copy" onclick="copiarCredencial(\'' + v + '\')" title="Copiar"><i class="ri-file-copy-line"></i></button>'
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

    $(document).on('click', '.est-btn-whatsapp', function(e) {
        e.stopPropagation();
        const btn = $(this);
        const celular      = btn.data('celular');
        const nombre       = btn.data('nombre');
        const username     = btn.data('username');
        const estudianteId = btn.data('estudiante-id');
        let   password     = btn.data('password');

        $('#waModalNombre').text(nombre);
        $('#waModalUsuario').text(username);
        $('#waModalPassword').text(password);
        $('#waModalCelular').val(celular);
        $('#waModalEstudianteId').val(estudianteId);
        $('#waModalBtnReset').data('btn-origen', btn);

        new bootstrap.Modal(document.getElementById('modalWhatsappAccesos')).show();
    });

    $('#waModalBtnEnviar').on('click', function () {
        const celular  = $('#waModalCelular').val();
        const nombre   = $('#waModalNombre').text();
        const username = $('#waModalUsuario').text();
        const password = $('#waModalPassword').text();

        const mensaje = '*¡Bienvenido/a!*\n\n' +
            'Estimado/a ' + nombre + ',\n\n' +
            'Le proporcionamos sus datos de acceso a la plataforma:\n\n' +
            '*Plataforma:* http://moodle52.localhost/\n' +
            '*Usuario:* ' + username + '\n' +
            '*Contraseña:* ' + password + '\n\n' +
            '*Área Académica Innova-Ciencia-Virtual*';

        window.open('https://wa.me/' + celular + '?text=' + encodeURIComponent(mensaje), '_blank');
        bootstrap.Modal.getInstance(document.getElementById('modalWhatsappAccesos')).hide();
    });

    $('#waModalBtnReset').on('click', function () {
        const estudianteId = $('#waModalEstudianteId').val();
        const btnReset     = $(this);
        const btnOrigen    = btnReset.data('btn-origen');

        btnReset.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Restableciendo...');

        $.ajax({
            url: '/admin/estudiantes/' + estudianteId + '/reset-password-moodle',
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

    function renderEstudios(estudios, personaId) {
        $('#formEditar').data('persona-id', personaId);
        var $list = $('#editEstudiosList');
        $list.empty();
        if (!estudios.length) {
            $list.html('<p style="font-size:.8rem;color:var(--est-text-muted);margin:.25rem 0;">Sin estudios registrados.</p>');
            return;
        }
        estudios.forEach(function(est) {
            var isPrincipal = !!est.principal;
            var grado = est.grado_academico ? esc(est.grado_academico.nombre) : '—';
            var prof = est.profesion ? esc(est.profesion.nombre) : '';
            var uni = est.universidad ? esc(est.universidad.nombre) : '';
            var sub = [prof, uni].filter(Boolean).join(' · ') || '—';
            var estadoCls = est.estado === 'Concluido' ? 'concluido' : 'en-desarrollo';
            var row = $('<div class="est-estudio-row" data-estudio-id="'+est.id+'" data-persona-id="'+personaId+'">'
                +'<button type="button" class="est-estudio-principal '+(isPrincipal?'is-principal':'not-principal')+'" title="'+(isPrincipal?'Estudio principal':'Marcar como principal')+'" data-estudio-id="'+est.id+'" data-persona-id="'+personaId+'">'
                +'<i class="ri-star-'+(isPrincipal?'fill':'line')+'"></i></button>'
                +'<div class="est-estudio-info"><div class="est-estudio-grado">'+grado+'</div><div class="est-estudio-sub">'+sub+'</div></div>'
                +'<span class="est-estudio-estado '+estadoCls+'">'+esc(est.estado)+'</span>'
                +'<button type="button" class="est-estudio-del btn-del-estudio" data-estudio-id="'+est.id+'" data-persona-id="'+personaId+'" title="Eliminar"><i class="ri-delete-bin-line"></i></button>'
                +'</div>');
            $list.append(row);
        });
    }

    function recargarEstudios(personaId) {
        $.get('{{ route("admin.estudiantes.obtener", ["id" => "__ID__"]) }}'.replace('__ID__', $('#formEditar').data('estudiante-id')))
            .done(function(r) {
                var estudios = (r.data.persona && r.data.persona.estudios) ? r.data.persona.estudios : [];
                renderEstudios(estudios, personaId);
            });
    }

    $(document).ready(init);
})();
</script>
@endsection
