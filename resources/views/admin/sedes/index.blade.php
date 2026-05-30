@extends('layouts.master')
@section('title') Sedes @endsection
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300..700&family=DM+Sans:opsz,wght@9..40,300..700&display=swap" rel="stylesheet">
<style>
:root {
    --sedes-primary: #fc7b04;
    --sedes-primary-rgb: 252, 123, 4;
    --sedes-primary-dark: #d46604;
    --sedes-primary-light: #fff0e0;
    --sedes-primary-glow: rgba(252, 123, 4, 0.15);
    --sedes-bg-warm: #faf7f4;
    --sedes-card-bg: #ffffff;
    --sedes-text: #2d2924;
    --sedes-text-muted: #8c8880;
    --sedes-border: #ede8e2;
    --sedes-border-light: #f5f0eb;
    --sedes-shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.03);
    --sedes-shadow-md: 0 4px 16px rgba(0, 0, 0, 0.05), 0 2px 8px rgba(0, 0, 0, 0.03);
    --sedes-shadow-lg: 0 12px 40px rgba(0, 0, 0, 0.06), 0 4px 16px rgba(0, 0, 0, 0.04);
    --sedes-success: #2e9a6e;
    --sedes-danger: #e05050;
    --sedes-warning: #f0a030;
}

body {
    font-family: 'DM Sans', sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

h1, h2, h3, h4, h5, h6, .dph-title, .dept-title, .modal-title, .dph-stat-num {
    font-family: 'Lexend', sans-serif;
}

/* ─── STAGED ENTRANCE ─── */
.sede-animate {
    opacity: 0;
    transform: translateY(18px);
    animation: sedeFadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
.sede-animate-1 { animation-delay: 0.05s; }
.sede-animate-2 { animation-delay: 0.15s; }
.sede-animate-3 { animation-delay: 0.25s; }

@keyframes sedeFadeUp {
    to { opacity: 1; transform: translateY(0); }
}

/* ─── PAGE WRAPPER ─── */
.sede-page {
    position: relative;
    min-height: 100%;
}

.sede-page::before {
    content: '';
    position: fixed;
    inset: 0;
    z-index: -1;
    background: var(--sedes-bg-warm);
}

.sede-page::after {
    content: '';
    position: fixed;
    inset: 0;
    z-index: -1;
    opacity: 0.025;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    background-size: 256px 256px;
    pointer-events: none;
}

/* ─── HEADER ─── */
.sede-header {
    position: relative;
    padding: 1.75rem 0 1.5rem;
    background: linear-gradient(135deg, #ffffff 0%, #fef9f4 50%, #fdf6ee 100%);
    border-bottom: 1px solid var(--sedes-border);
    overflow: hidden;
}

.sede-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 500px;
    height: 500px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(252, 123, 4, 0.05) 0%, transparent 70%);
    pointer-events: none;
}

.sede-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(252, 123, 4, 0.15), transparent);
}

.sede-header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    position: relative;
    z-index: 1;
}

.sede-header-left {
    display: flex;
    align-items: center;
    gap: 1.1rem;
}

.sede-header-icon {
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, rgba(252, 123, 4, 0.12), rgba(252, 123, 4, 0.05));
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 1px solid rgba(252, 123, 4, 0.1);
    box-shadow: 0 2px 8px rgba(252, 123, 4, 0.06);
}
.sede-header-icon i {
    font-size: 1.5rem;
    color: var(--sedes-primary);
}

.sede-header-text h1 {
    font-size: 1.45rem;
    font-weight: 600;
    color: var(--sedes-text);
    margin: 0 0 0.1rem;
    line-height: 1.2;
    letter-spacing: -0.02em;
}
.sede-header-text p {
    font-size: 0.85rem;
    color: var(--sedes-text-muted);
    margin: 0;
    font-weight: 400;
}
.sede-breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    margin-top: 0.5rem;
    font-size: 0.75rem;
    color: var(--sedes-text-muted);
    list-style: none;
    padding: 0;
}
.sede-breadcrumb li { display: flex; align-items: center; gap: 0.3rem; }
.sede-breadcrumb .sede-sep { opacity: 0.4; }
.sede-breadcrumb .active { color: var(--sedes-primary); font-weight: 600; }
.sede-breadcrumb i { font-size: 0.8rem; }

.sede-header-right {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.sede-stat-card {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid var(--sedes-border);
    border-radius: 12px;
    padding: 0.6rem 1.1rem;
    transition: box-shadow 0.25s, transform 0.2s;
    backdrop-filter: blur(4px);
}
.sede-stat-card:hover {
    box-shadow: var(--sedes-shadow-md);
    transform: translateY(-1px);
}
.sede-stat-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, rgba(252, 123, 4, 0.12), rgba(252, 123, 4, 0.04));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.sede-stat-icon i { color: var(--sedes-primary); font-size: 1rem; }
.sede-stat-num {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--sedes-text);
    line-height: 1;
    letter-spacing: -0.02em;
}
.sede-stat-label {
    font-size: 0.72rem;
    color: var(--sedes-text-muted);
    margin-top: 2px;
    font-weight: 450;
}

.sede-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    background: linear-gradient(135deg, var(--sedes-primary) 0%, var(--sedes-primary-dark) 100%);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 0.6rem 1.2rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: box-shadow 0.25s, transform 0.2s, background 0.2s;
    white-space: nowrap;
    font-family: 'DM Sans', sans-serif;
    position: relative;
    overflow: hidden;
}
.sede-btn-primary::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
    pointer-events: none;
}
.sede-btn-primary i { font-size: 1.1rem; position: relative; z-index: 1; }
.sede-btn-primary span { position: relative; z-index: 1; }
.sede-btn-primary:hover {
    background: var(--sedes-primary-dark);
    box-shadow: 0 6px 20px rgba(252, 123, 4, 0.35);
    color: #fff;
    transform: translateY(-1px);
}
.sede-btn-primary:active { transform: translateY(0); }

/* ─── MAIN CARD ─── */
.sede-card {
    background: var(--sedes-card-bg);
    border: 1px solid var(--sedes-border);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--sedes-shadow-sm);
    transition: box-shadow 0.3s;
}
.sede-card:hover {
    box-shadow: var(--sedes-shadow-md);
}

.sede-card-header {
    padding: 1.1rem 1.35rem;
    border-bottom: 1px solid var(--sedes-border-light);
    background: linear-gradient(135deg, #ffffff, #fefaf7);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}
.sede-card-header-left {
    display: flex;
    align-items: center;
    gap: 0.85rem;
}
.sede-card-header-icon {
    width: 38px;
    height: 38px;
    background: linear-gradient(135deg, rgba(252, 123, 4, 0.1), rgba(252, 123, 4, 0.04));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.sede-card-header-icon i { color: var(--sedes-primary); font-size: 1.05rem; }
.sede-card-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--sedes-text);
    margin: 0;
    letter-spacing: -0.01em;
}
.sede-card-subtitle {
    font-size: 0.78rem;
    color: var(--sedes-text-muted);
    margin: 2px 0 0;
    font-weight: 400;
}

.sede-card-body {
    padding: 0;
}

/* ─── TABLE ─── */
.sede-table {
    width: 100% !important;
    border-collapse: collapse;
    margin: 0 !important;
}

.sede-table thead th {
    background: #faf7f4;
    color: var(--sedes-text);
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 0.85rem 1.1rem;
    border-bottom: 2px solid var(--sedes-border);
    white-space: nowrap;
    font-family: 'Lexend', sans-serif;
}

.sede-table tbody td {
    padding: 0.85rem 1.1rem;
    font-size: 0.875rem;
    color: var(--sedes-text);
    border-bottom: 1px solid var(--sedes-border-light);
    vertical-align: middle;
}

.sede-table tbody tr {
    transition: background 0.2s, transform 0.15s;
}
.sede-table tbody tr:last-child td { border-bottom: none; }

.sede-table tbody tr:hover td {
    background: rgba(252, 123, 4, 0.035);
}
.sede-table tbody tr:hover {
    transform: translateX(3px);
}

.sede-table .nombre-sede {
    font-weight: 600;
    color: var(--sedes-text);
}

/* ─── BADGES ─── */
.sede-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    background: linear-gradient(135deg, rgba(252, 123, 4, 0.1), rgba(252, 123, 4, 0.04));
    color: var(--sedes-primary-dark);
    border: 1px solid rgba(252, 123, 4, 0.18);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    transition: all 0.25s ease;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
}
.sede-badge:hover {
    background: linear-gradient(135deg, rgba(252, 123, 4, 0.16), rgba(252, 123, 4, 0.08));
    border-color: rgba(252, 123, 4, 0.3);
    max-width: 320px;
    box-shadow: 0 2px 8px rgba(252, 123, 4, 0.1);
}
.sede-badge i { font-size: 0.75rem; }
.sede-badge-vacio {
    background: rgba(252, 123, 4, 0.04);
    border: 1px dashed rgba(252, 123, 4, 0.25);
    color: var(--sedes-text-muted);
}
.sede-badge-vacio:hover {
    background: rgba(252, 123, 4, 0.08);
    color: var(--sedes-primary-dark);
}

/* ─── ACTION BUTTONS ─── */
.sede-action-cell {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
}
.sede-btn-action {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: none;
    transition: all 0.2s;
    background: transparent;
    color: var(--sedes-text-muted);
    cursor: pointer;
}
.sede-btn-action i { font-size: 0.95rem; }
.sede-btn-action:hover { background: var(--sedes-border-light); }
.sede-btn-edit:hover { background: rgba(252, 123, 4, 0.1); color: var(--sedes-primary); }
.sede-btn-delete:hover { background: rgba(224, 80, 80, 0.08); color: var(--sedes-danger); }

/* ─── DATATABLES OVERRIDES ─── */
.sede-card .dataTables_wrapper {
    padding: 0;
}
.sede-card .dataTables_wrapper .dataTables_filter {
    padding: 0.85rem 1.1rem 0;
}
.sede-card .dataTables_wrapper .dataTables_filter label {
    font-size: 0.82rem;
    color: var(--sedes-text-muted);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 450;
}
.sede-card .dataTables_wrapper .dataTables_filter input {
    border: 1px solid var(--sedes-border);
    border-radius: 8px;
    padding: 0.4rem 0.75rem;
    font-size: 0.82rem;
    color: var(--sedes-text);
    background: #faf8f5;
    transition: border-color 0.2s, box-shadow 0.2s;
    font-family: 'DM Sans', sans-serif;
    outline: none;
    min-width: 200px;
}
.sede-card .dataTables_wrapper .dataTables_filter input:focus {
    border-color: var(--sedes-primary);
    box-shadow: 0 0 0 3px rgba(252, 123, 4, 0.1);
    background: #fff;
}
.sede-card .dataTables_wrapper .dataTables_length {
    padding: 0.85rem 1.1rem 0;
}
.sede-card .dataTables_wrapper .dataTables_length label {
    font-size: 0.82rem;
    color: var(--sedes-text-muted);
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-weight: 450;
}
.sede-card .dataTables_wrapper .dataTables_length select {
    border: 1px solid var(--sedes-border);
    border-radius: 6px;
    padding: 0.3rem 1.5rem 0.3rem 0.5rem;
    font-size: 0.82rem;
    color: var(--sedes-text);
    background: #faf8f5 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%238c8880'/%3E%3C/svg%3E") no-repeat right 0.5rem center;
    appearance: none;
    cursor: pointer;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.sede-card .dataTables_wrapper .dataTables_length select:focus {
    border-color: var(--sedes-primary);
    box-shadow: 0 0 0 3px rgba(252, 123, 4, 0.1);
    background-color: #fff;
}
.sede-card .dataTables_wrapper .dataTables_info {
    padding: 0.85rem 1.1rem;
    font-size: 0.78rem;
    color: var(--sedes-text-muted);
}
.sede-card .dataTables_wrapper .dataTables_paginate {
    padding: 0.85rem 1.1rem;
}
.sede-card .dataTables_wrapper .dataTables_paginate .paginate_button {
    border: 1px solid var(--sedes-border) !important;
    border-radius: 8px !important;
    padding: 0.35rem 0.75rem !important;
    margin: 0 0.15rem;
    font-size: 0.78rem;
    color: var(--sedes-text) !important;
    background: #fff !important;
    transition: all 0.2s;
    font-family: 'DM Sans', sans-serif;
}
.sede-card .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    border-color: rgba(252, 123, 4, 0.3) !important;
    background: rgba(252, 123, 4, 0.06) !important;
    color: var(--sedes-primary) !important;
}
.sede-card .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    border-color: var(--sedes-primary) !important;
    background: linear-gradient(135deg, var(--sedes-primary), var(--sedes-primary-dark)) !important;
    color: #fff !important;
    font-weight: 600;
}
.sede-card .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.sede-card table.dataTable.no-footer {
    border-bottom: 1px solid var(--sedes-border-light);
}

/* ─── MODALS ─── */
.modal-content {
    border: none;
    border-radius: 16px;
    box-shadow: var(--sedes-shadow-lg);
    overflow: hidden;
}
.modal-header {
    padding: 1.1rem 1.35rem 0.85rem;
    border-bottom: 1px solid var(--sedes-border-light);
    background: linear-gradient(135deg, #ffffff, #fefaf7);
}
.modal-header .modal-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--sedes-text);
    letter-spacing: -0.01em;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.modal-header .modal-title i { color: var(--sedes-primary); font-size: 1.15rem; }
.modal-header .btn-close {
    transition: transform 0.2s, opacity 0.2s;
    opacity: 0.5;
}
.modal-header .btn-close:hover {
    transform: rotate(90deg);
    opacity: 1;
}
.modal-body {
    padding: 1rem 1.35rem;
}
.modal-footer {
    padding: 0.85rem 1.35rem;
    border-top: 1px solid var(--sedes-border-light);
    background: #faf8f5;
}

.form-label {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--sedes-text);
    margin-bottom: 0.35rem;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}
.form-label i { color: var(--sedes-primary); font-size: 0.9rem; }
.req { color: var(--sedes-danger); font-weight: 700; }

.field-wrapper {
    position: relative;
}
.field-wrapper .form-control {
    border: 1px solid var(--sedes-border);
    border-radius: 10px;
    padding: 0.55rem 2.5rem 0.55rem 0.9rem;
    font-size: 0.875rem;
    color: var(--sedes-text);
    background: #faf8f5;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    font-family: 'DM Sans', sans-serif;
}
.field-wrapper .form-control:focus {
    border-color: var(--sedes-primary);
    box-shadow: 0 0 0 3px rgba(252, 123, 4, 0.1);
    background: #fff;
}
.field-wrapper .form-control.is-valid {
    border-color: var(--sedes-success);
    background: #f4faf6;
}
.field-wrapper .form-control.is-invalid {
    border-color: var(--sedes-danger);
    background: #fef4f4;
}
.field-wrapper .form-control::placeholder {
    color: #b5b0a8;
    font-weight: 400;
}

.validation-icon {
    position: absolute;
    right: 0.85rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1rem;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.2s;
}
.validation-icon.valid,
.validation-icon.invalid { opacity: 1; }
.validation-icon.valid i { color: var(--sedes-success); }
.validation-icon.invalid i { color: var(--sedes-danger); }

.field-feedback {
    font-size: 0.76rem;
    margin-top: 0.3rem;
    min-height: 1rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
    transition: color 0.2s;
}
.field-feedback.error { color: var(--sedes-danger); }
.field-feedback.success { color: var(--sedes-success); }

.char-hint {
    font-size: 0.7rem;
    color: var(--sedes-text-muted);
    text-align: right;
    margin-top: 3px;
    transition: color 0.25s;
}
.char-hint.warning { color: var(--sedes-primary); }
.char-hint.danger { color: var(--sedes-danger); }

.sede-btn-cancel {
    background: #f0ece8;
    color: var(--sedes-text);
    border: none;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.55rem 1.1rem;
    transition: background 0.15s, transform 0.15s;
    font-family: 'DM Sans', sans-serif;
}
.sede-btn-cancel:hover {
    background: #e5dfd9;
    color: var(--sedes-text);
}

.sede-btn-submit {
    background: linear-gradient(135deg, var(--sedes-primary), var(--sedes-primary-dark));
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.55rem 1.3rem;
    transition: box-shadow 0.2s, transform 0.15s;
    font-family: 'DM Sans', sans-serif;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}
.sede-btn-submit:hover:not(:disabled) {
    box-shadow: 0 4px 14px rgba(252, 123, 4, 0.3);
    color: #fff;
    transform: scale(1.02);
}
.sede-btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }

.sede-btn-danger {
    background: var(--sedes-danger);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.55rem 1.3rem;
    transition: box-shadow 0.2s, transform 0.15s;
    font-family: 'DM Sans', sans-serif;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}
.sede-btn-danger:hover {
    box-shadow: 0 4px 14px rgba(224, 80, 80, 0.3);
    color: #fff;
    transform: scale(1.02);
}

/* ─── DELETE WARNING ─── */
.sede-delete-box {
    text-align: center;
    padding: 0.5rem 0;
}
.sede-delete-icon {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: rgba(224, 80, 80, 0.08);
    border: 2px solid rgba(224, 80, 80, 0.18);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.1rem;
    transition: transform 0.3s;
}
.sede-delete-icon i { font-size: 2rem; color: var(--sedes-danger); }
.sede-delete-box:hover .sede-delete-icon { transform: scale(1.05); }
.sede-delete-msg { font-size: 1.05rem; font-weight: 700; color: var(--sedes-text); margin-bottom: 0.3rem; }
.sede-delete-name { font-size: 0.92rem; color: var(--sedes-text); margin-bottom: 0.6rem; }
.sede-delete-name strong { color: var(--sedes-primary); }
.sede-delete-warn { font-size: 0.78rem; color: var(--sedes-text-muted); margin-bottom: 0; display: flex; align-items: center; justify-content: center; gap: 0.3rem; }
.sede-delete-warn i { color: var(--sedes-warning); font-size: 0.9rem; }

/* ─── SUCURSALES SECTION ─── */
.sucursales-color-input {
    width: 100% !important;
    padding: 0.35rem !important;
    height: 40px;
    border-radius: 8px;
    border: 1px solid var(--sedes-border);
    background: #faf8f5;
    cursor: pointer;
}
.sucursales-color-input::-webkit-color-swatch-wrapper { padding: 2px; }
.sucursales-color-input::-webkit-color-swatch { border: none; border-radius: 4px; }

.sucursales-table {
    border: 1px solid var(--sedes-border-light);
    border-radius: 10px;
    overflow: hidden;
}
.sucursales-table thead th {
    background: #faf7f4;
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 0.65rem 0.85rem;
    border-bottom: 1px solid var(--sedes-border);
    color: var(--sedes-text);
    font-family: 'Lexend', sans-serif;
}
.sucursales-table tbody td {
    padding: 0.7rem 0.85rem;
    font-size: 0.85rem;
    border-bottom: 1px solid var(--sedes-border-light);
    vertical-align: middle;
}
.sucursales-table tbody tr:last-child td { border-bottom: none; }
.sucursales-table tbody tr:hover { background: rgba(252, 123, 4, 0.03); }

.sucursal-color-preview {
    width: 26px;
    height: 26px;
    border-radius: 7px;
    border: 2px solid rgba(252, 123, 4, 0.12);
    display: inline-block;
    vertical-align: middle;
    transition: transform 0.2s;
}
.sucursal-color-preview:hover { transform: scale(1.15); }

.sucursal-nombre { font-weight: 600; font-size: 0.85rem; }
.sucursal-direccion { font-size: 0.78rem; color: var(--sedes-text-muted); }

.sede-empty-state {
    text-align: center;
    padding: 2rem 0;
}
.sede-empty-state i {
    font-size: 2.2rem;
    color: var(--sedes-text-muted);
    opacity: 0.3;
    display: block;
    margin-bottom: 0.5rem;
}
.sede-empty-state p {
    color: var(--sedes-text-muted);
    font-size: 0.85rem;
    margin: 0;
}

/* ─── TOASTS ─── */
.toast-container {
    position: fixed;
    right: 20px;
    z-index: 1060;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    pointer-events: none;
}
.toast-notify {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.75rem 1rem;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(12px);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    font-size: 0.82rem;
    font-weight: 500;
    color: var(--sedes-text);
    border-left: 4px solid;
    animation: sedeToastIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    pointer-events: auto;
    min-width: 280px;
    max-width: 420px;
}
.toast-notify.hiding {
    animation: sedeToastOut 0.25s ease forwards;
}
.toast-notify.success { border-left-color: var(--sedes-success); }
.toast-notify.error { border-left-color: var(--sedes-danger); }
.toast-notify.warning { border-left-color: var(--sedes-warning); }
.toast-icon { flex-shrink: 0; font-size: 1.1rem; }
.toast-notify.success .toast-icon i { color: var(--sedes-success); }
.toast-notify.error .toast-icon i { color: var(--sedes-danger); }
.toast-notify.warning .toast-icon i { color: var(--sedes-warning); }
.toast-body-text { flex: 1; }
.toast-close {
    background: none;
    border: none;
    color: var(--sedes-text-muted);
    cursor: pointer;
    padding: 0;
    font-size: 1.1rem;
    opacity: 0.5;
    transition: opacity 0.2s;
    flex-shrink: 0;
}
.toast-close:hover { opacity: 1; }

@keyframes sedeToastIn {
    0% { opacity: 0; transform: translateX(100%) scale(0.95); }
    100% { opacity: 1; transform: translateX(0) scale(1); }
}
@keyframes sedeToastOut {
    0% { opacity: 1; transform: translateX(0) scale(1); }
    100% { opacity: 0; transform: translateX(100%) scale(0.95); }
}

/* ─── MODAL BACKDROP ─── */
.modal-backdrop { background: rgba(0, 0, 0, 0.3); }
@supports (backdrop-filter: blur(3px)) {
    .modal-backdrop {
        backdrop-filter: blur(3px);
        background: rgba(0, 0, 0, 0.2);
    }
}
.modal-backdrop.fade { transition: opacity 0.2s; }

/* ─── MODAL ANIMATION ─── */
.modal.fade .modal-dialog {
    transform: scale(0.92) translateY(-10px);
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s;
}
.modal.show .modal-dialog {
    transform: scale(1) translateY(0);
}

/* ─── SCROLLBAR ─── */
.sede-card ::-webkit-scrollbar,
.sucursales-list::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
.sede-card ::-webkit-scrollbar-track,
.sucursales-list::-webkit-scrollbar-track {
    background: transparent;
}
.sede-card ::-webkit-scrollbar-thumb,
.sucursales-list::-webkit-scrollbar-thumb {
    background: rgba(252, 123, 4, 0.2);
    border-radius: 3px;
}
.sede-card ::-webkit-scrollbar-thumb:hover,
.sucursales-list::-webkit-scrollbar-thumb:hover {
    background: rgba(252, 123, 4, 0.35);
}

/* ─── RESPONSIVE ─── */
@media (max-width: 768px) {
    .sede-header { padding: 1.25rem 0; }
    .sede-header-inner { flex-direction: column; align-items: flex-start; }
    .sede-header-right { width: 100%; }
    .sede-stat-card { flex: 1; }
    .sede-btn-primary { flex: 1; justify-content: center; }
    .sede-card-header { flex-direction: column; align-items: flex-start; }
    .sede-table thead th,
    .sede-table tbody td { padding: 0.6rem 0.75rem; }
}
</style>
@endsection

@section('content')
<div class="sede-page">
    <!-- Header -->
    <div class="sede-header">
        <div class="container-fluid">
            <div class="sede-header-inner">
                <div class="sede-header-left sede-animate sede-animate-1">
                    <div class="sede-header-icon">
                        <i class="ri-building-line"></i>
                    </div>
                    <div class="sede-header-text">
                        <h1>Sedes</h1>
                        <p>Gestión y administración de sedes y sus sucursales</p>
                        <ol class="sede-breadcrumb">
                            <li><i class="ri-home-4-line"></i> Ubicaciones</li>
                            <li class="sede-sep"><i class="ri-arrow-right-s-line"></i></li>
                            <li class="active">Sedes</li>
                        </ol>
                    </div>
                </div>
                <div class="sede-header-right sede-animate sede-animate-2">
                    <div class="sede-stat-card">
                        <div class="sede-stat-icon"><i class="ri-building-line"></i></div>
                        <div>
                            <div class="sede-stat-num" id="stat-total">—</div>
                            <div class="sede-stat-label">Total Registros</div>
                        </div>
                    </div>
                    <button type="button" class="sede-btn-primary" id="btn-nuevo">
                        <i class="ri-add-line"></i>
                        <span>Nueva Sede</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="sede-card sede-animate sede-animate-3">
                    <div class="sede-card-header">
                        <div class="sede-card-header-left">
                            <div class="sede-card-header-icon">
                                <i class="ri-table-line"></i>
                            </div>
                            <div>
                                <h5 class="sede-card-title">Listado de Sedes</h5>
                                <p class="sede-card-subtitle">Consulta, edita o elimina las sedes existentes</p>
                            </div>
                        </div>
                    </div>

                    <div class="sede-card-body">
                        <table id="tabla-sedes" class="sede-table">
                            <thead>
                                <tr>
                                    <th>Nombre de la Sede</th>
                                    <th style="width:150px;">Sucursales</th>
                                    <th class="text-center" style="width:110px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL CREAR ===================== -->
    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-add-circle-line"></i> Nueva Sede
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formCrear" novalidate autocomplete="off">
                    <div class="modal-body">
                        <div class="mb-1">
                            <label for="nombreCrear" class="form-label">
                                <i class="ri-building-line"></i>
                                Nombre de la Sede <span class="req">*</span>
                            </label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="nombreCrear"
                                       placeholder="Ej: Sede Central, Sede Norte…"
                                       maxlength="100" autocomplete="off">
                                <span class="validation-icon" id="iconCrear"></span>
                            </div>
                            <div class="field-feedback" id="fbCrear"></div>
                            <div class="char-hint" id="hintCrear">0 / 100</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="sede-btn-cancel" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="sede-btn-submit" id="btnGuardar">
                            <i class="ri-save-line"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL EDITAR ===================== -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-edit-2-line"></i> Editar Sede
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formEditar" novalidate autocomplete="off">
                    <input type="hidden" id="idEditar">
                    <div class="modal-body">
                        <div class="mb-1">
                            <label for="nombreEditar" class="form-label">
                                <i class="ri-building-line"></i>
                                Nombre de la Sede <span class="req">*</span>
                            </label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="nombreEditar"
                                       placeholder="Ej: Sede Central, Sede Norte…"
                                       maxlength="100" autocomplete="off">
                                <span class="validation-icon" id="iconEditar"></span>
                            </div>
                            <div class="field-feedback" id="fbEditar"></div>
                            <div class="char-hint" id="hintEditar">0 / 100</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="sede-btn-cancel" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="sede-btn-submit" id="btnActualizar">
                            <i class="ri-refresh-line"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL ELIMINAR ===================== -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-error-warning-line"></i> Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="sede-delete-box">
                        <div class="sede-delete-icon">
                            <i class="ri-delete-bin-5-line"></i>
                        </div>
                        <p class="sede-delete-msg">¿Eliminar sede?</p>
                        <p class="sede-delete-name">
                            <strong id="nombreEliminar"></strong>
                        </p>
                        <p class="sede-delete-warn">
                            <i class="ri-information-line"></i>
                            Esta acción es permanente y no puede deshacerse.
                        </p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center gap-3">
                    <button type="button" class="sede-btn-cancel px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="button" class="sede-btn-danger px-4" id="btnConfirmarEliminar">
                        <i class="ri-delete-bin-line"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL SUCURSALES ===================== -->
    <div class="modal fade" id="modalSucursales" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width:700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-building-2-line"></i> Sucursales de <span id="sedeSucursalesNombre"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ri-add-circle-line"></i>
                            Nueva Sucursal <span class="req">*</span>
                        </label>
                        <div class="row g-2">
                            <div class="col-md-5">
                                <input type="text" class="form-control" id="nombreSucursal"
                                       placeholder="Nombre de la sucursal"
                                       maxlength="100" autocomplete="off">
                                <input type="hidden" id="idSucursalEditar">
                            </div>
                            <div class="col-md-3">
                                <input type="color" class="form-control sucursales-color-input" id="colorSucursal"
                                       value="#fc7b04" title="Color de la sucursal">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="direccionSucursal"
                                       placeholder="Dirección"
                                       maxlength="200" autocomplete="off">
                            </div>
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-md-6">
                                <input type="number" step="any" class="form-control" id="latitudSucursal"
                                       placeholder="Latitud" autocomplete="off">
                            </div>
                            <div class="col-md-6">
                                <input type="number" step="any" class="form-control" id="longitudSucursal"
                                       placeholder="Longitud" autocomplete="off">
                            </div>
                        </div>
                        <div class="field-feedback" id="fbSucursal"></div>
                        <div class="mt-2 d-flex gap-2">
                            <button type="button" class="sede-btn-submit" id="btnAgregarSucursal">
                                <i class="ri-add-line"></i> <span id="txtBtnSucursal">Agregar</span>
                            </button>
                            <button type="button" class="sede-btn-cancel d-none" id="btnCancelarSucursal">
                                <i class="ri-close-line"></i> Cancelar
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive rounded">
                        <table class="table sucursales-table mb-0" id="tablaSucursales">
                            <thead>
                                <tr>
                                    <th style="width:50px;">Color</th>
                                    <th>Sucursal</th>
                                    <th>Dirección</th>
                                    <th>Ubicación</th>
                                    <th class="text-center" style="width:100px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="sucursalesList"></tbody>
                        </table>
                    </div>
                    <div class="sede-empty-state text-center d-none" id="sucursalEmpty">
                        <i class="ri-building-2-line"></i>
                        <p>No hay sucursales agregadas</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="sede-btn-cancel" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL ELIMINAR SUCURSAL ===================== -->
    <div class="modal fade" id="modalEliminarSucursal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-error-warning-line"></i> Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="sede-delete-box">
                        <div class="sede-delete-icon">
                            <i class="ri-delete-bin-5-line"></i>
                        </div>
                        <p class="sede-delete-msg">¿Eliminar sucursal?</p>
                        <p class="sede-delete-name">
                            <strong id="nombreEliminarSucursal"></strong>
                        </p>
                        <p class="sede-delete-warn">
                            <i class="ri-information-line"></i>
                            Esta acción es permanente y no puede deshacerse.
                        </p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center gap-3">
                    <button type="button" class="sede-btn-cancel px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="button" class="sede-btn-danger px-4" id="btnConfirmarEliminarSucursal">
                        <i class="ri-delete-bin-line"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast container -->
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
    let idEliminarSucursal = null;
    const CSRF = '{{ csrf_token() }}';

    function init() {
        initDataTable();
        bindEvents();
    }

    function initDataTable() {
        tabla = $('#tabla-sedes').DataTable({
            ajax: { url: '{{ route("admin.sedes.listar") }}', dataSrc: 'data' },
            ordering: true,
            columns: [
                {
                    data: 'nombre',
                    render: n => '<span class="nombre-sede">' + escHtml(n) + '</span>'
                },
                {
                    data: null,
                    render: d => {
                        const sucursales = d.sucursales || [];
                        if (sucursales.length === 0) {
                            return '<span class="sede-badge sede-badge-vacio btn-abrir-sucursales" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '"><i class="ri-add-line"></i> Agregar</span>';
                        }
                        return '<span class="sede-badge btn-abrir-sucursales" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '" title="Click para gestionar sucursales"><i class="ri-building-2-line"></i> ' + sucursales.length + '</span>';
                    }
                },
                {
                    data: null, className: 'text-center',
                    render: d =>
                        '<div class="sede-action-cell">'
                        + '<button class="sede-btn-action sede-btn-edit btn-accion-editar" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '" title="Editar sede"><i class="ri-pencil-fill"></i></button>'
                        + '<button class="sede-btn-action sede-btn-delete btn-accion-eliminar" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '" title="Eliminar sede"><i class="ri-delete-bin-fill"></i></button>'
                        + '</div>'
                }
            ],
            language: {
                processing:     'Procesando...',
                search:         'Buscar:',
                lengthMenu:     'Mostrar _MENU_ registros',
                info:           'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty:      'Mostrando 0 a 0 de 0 registros',
                infoFiltered:   '(filtrado de _MAX_ registros totales)',
                loadingRecords: 'Cargando...',
                zeroRecords:    'No se encontraron registros',
                emptyTable:     'No hay datos disponibles',
                paginate: {
                    first:    'Primero',
                    previous: 'Anterior',
                    next:     'Siguiente',
                    last:     'Último'
                },
                aria: {
                    sortAscending:  ': activar para ordenar ascendente',
                    sortDescending: ': activar para ordenar descendente'
                }
            },
            order: [[0, 'asc']],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Todos']],
            pageLength: 10,
            drawCallback: function () {
                const info = this.api().page.info();
                $('#stat-total').text(info.recordsTotal);
            }
        });
    }

    function bindEvents() {
        $('#btn-nuevo').on('click', () => {
            resetField('nombreCrear', 'iconCrear', 'fbCrear', 'hintCrear');
            $('#formCrear')[0].reset();
            openModal('modalCrear');
        });

        $(document).on('click', '.btn-accion-editar', function () {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            $('#idEditar').val(id);
            $('#nombreEditar').val(nombre);
            actualizarHint('nombreEditar', 'hintEditar');
            validar('nombreEditar', 'iconEditar', 'fbEditar');
            openModal('modalEditar');
        });

        $(document).on('click', '.btn-accion-eliminar', function () {
            idEliminar = $(this).data('id');
            $('#nombreEliminar').text($(this).data('nombre'));
            openModal('modalEliminar');
        });

        $(document).on('click', '.btn-abrir-sucursales', function () {
            const sedeId = $(this).data('id');
            const sedeNombre = $(this).data('nombre');
            $('#sedeSucursalesNombre').text(sedeNombre);
            $('#nombreSucursal').val('');
            $('#colorSucursal').val('#fc7b04');
            $('#direccionSucursal').val('');
            $('#latitudSucursal').val('');
            $('#longitudSucursal').val('');
            $('#idSucursalEditar').val('');
            $('#fbSucursal').text('').removeClass('text-danger text-success');
            $('#txtBtnSucursal').text('Agregar');
            $('#btnCancelarSucursal').addClass('d-none');
            cargarSucursales(sedeId);
            window.idSedeSucursal = sedeId;
            openModal('modalSucursales');
        });

        $('#btnAgregarSucursal').on('click', function () {
            agregarSucursal();
        });

        $('#nombreSucursal').on('keypress', function (e) {
            if (e.which === 13) {
                agregarSucursal();
            }
        });

        $(document).on('click', '.btn-eliminar-sucursal', function () {
            idEliminarSucursal = $(this).data('id');
            const nombre = $(this).closest('tr').find('.sucursal-nombre').text();
            $('#nombreEliminarSucursal').text(nombre);
            openModal('modalEliminarSucursal');
        });

        $('#btnConfirmarEliminarSucursal').on('click', function () {
            if (!idEliminarSucursal) return;
            setBtnLoading('#btnConfirmarEliminarSucursal', true, '<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                url: '/admin/sedes/' + window.idSedeSucursal + '/sucursales/' + idEliminarSucursal,
                type: 'DELETE',
                data: { _token: CSRF }
            })
            .done(r => {
                closeModal('modalEliminarSucursal');
                toast('success', r.message || 'Sucursal eliminada correctamente.');
                cargarSucursales(window.idSedeSucursal);
                tabla.ajax.reload();
            })
            .fail(xhr => {
                const msg = xhr.responseJSON?.message || 'No se pudo eliminar la sucursal.';
                toast('error', msg);
            })
            .always(() => {
                setBtnLoading('#btnConfirmarEliminarSucursal', false, '<i class="ri-delete-bin-line"></i> Eliminar');
                idEliminarSucursal = null;
            });
        });

        $(document).on('click', '.btn-editar-sucursal', function () {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            const color = $(this).data('color');
            const direccion = $(this).data('direccion');
            const latitud = $(this).data('latitud');
            const longitud = $(this).data('longitud');
            $('#idSucursalEditar').val(id);
            $('#nombreSucursal').val(nombre).focus();
            $('#colorSucursal').val(color || '#fc7b04');
            $('#direccionSucursal').val(direccion || '');
            $('#latitudSucursal').val(latitud || '');
            $('#longitudSucursal').val(longitud || '');
            $('#txtBtnSucursal').text('Actualizar');
            $('#btnCancelarSucursal').removeClass('d-none');
            $('#fbSucursal').text('').removeClass('text-danger text-success');
        });

        $('#btnCancelarSucursal').on('click', function () {
            $('#idSucursalEditar').val('');
            $('#nombreSucursal').val('');
            $('#colorSucursal').val('#fc7b04');
            $('#direccionSucursal').val('');
            $('#latitudSucursal').val('');
            $('#longitudSucursal').val('');
            $('#txtBtnSucursal').text('Agregar');
            $(this).addClass('d-none');
            $('#fbSucursal').text('').removeClass('text-danger text-success');
        });

        $('#formCrear').on('submit', e => { e.preventDefault(); guardar(); });
        $('#formEditar').on('submit', e => { e.preventDefault(); actualizar(); });
        $('#btnConfirmarEliminar').on('click', confirmarEliminar);

        $('#nombreCrear').on('input', function () {
            actualizarHint('nombreCrear', 'hintCrear');
            validar('nombreCrear', 'iconCrear', 'fbCrear');
        });

        $('#nombreEditar').on('input', function () {
            actualizarHint('nombreEditar', 'hintEditar');
            validar('nombreEditar', 'iconEditar', 'fbEditar');
        });

        document.getElementById('modalCrear').addEventListener('hidden.bs.modal', () => {
            resetField('nombreCrear', 'iconCrear', 'fbCrear', 'hintCrear');
            $('#formCrear')[0].reset();
        });
    }

    function validar(inputId, iconId, fbId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        const fb    = document.getElementById(fbId);
        const val   = input.value.trim();

        const setError = msg => {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            icon.className = 'validation-icon invalid';
            icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
            fb.className   = 'field-feedback error';
            fb.innerHTML   = '<i class="ri-error-warning-line"></i>' + msg;
            return false;
        };

        const setOk = () => {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            icon.className = 'validation-icon valid';
            icon.innerHTML = '<i class="ri-checkbox-circle-fill"></i>';
            fb.className   = 'field-feedback success';
            fb.innerHTML   = '<i class="ri-check-line"></i>Nombre válido';
            return true;
        };

        if (!val)                                    return setError('El nombre de la sede es obligatorio.');
        if (val.length < 2)                          return setError('Debe tener al menos 2 caracteres.');
        if (val.length > 100)                        return setError('No puede superar los 100 caracteres.');

        return setOk();
    }

    function resetField(inputId, iconId, fbId, hintId) {
        const input = document.getElementById(inputId);
        input.classList.remove('is-valid', 'is-invalid');
        document.getElementById(iconId).className = 'validation-icon';
        document.getElementById(iconId).innerHTML = '';
        document.getElementById(fbId).className   = 'field-feedback';
        document.getElementById(fbId).innerHTML   = '';
        document.getElementById(hintId).textContent = '0 / 100';
        document.getElementById(hintId).className = 'char-hint';
    }

    function actualizarHint(inputId, hintId) {
        const len  = document.getElementById(inputId).value.length;
        const hint = document.getElementById(hintId);
        hint.textContent = len + ' / 100';
        hint.className   = 'char-hint' + (len > 90 ? ' warning' : '');
    }

    function guardar() {
        if (!validar('nombreCrear', 'iconCrear', 'fbCrear')) return;
        setBtnLoading('#btnGuardar', true, 'Guardando…');
        $.post('{{ route("admin.sedes.guardar") }}', {
            _token: CSRF,
            nombre: $('#nombreCrear').val().trim()
        })
        .done(r => {
            closeModal('modalCrear');
            tabla.ajax.reload();
            toast('success', r.message || 'Sede guardada correctamente.');
        })
        .fail(xhr => handleAjaxError(xhr, 'nombreCrear', 'iconCrear', 'fbCrear', 'guardar'))
        .always(() => setBtnLoading('#btnGuardar', false, '<i class="ri-save-line"></i> Guardar'));
    }

    function actualizar() {
        if (!validar('nombreEditar', 'iconEditar', 'fbEditar')) return;
        const id = $('#idEditar').val();
        setBtnLoading('#btnActualizar', true, 'Actualizando…');
        $.ajax({
            url: '/admin/sedes/' + id,
            type: 'PUT',
            data: {
                _token: CSRF,
                nombre: $('#nombreEditar').val().trim()
            }
        })
        .done(r => {
            closeModal('modalEditar');
            tabla.ajax.reload();
            toast('success', r.message || 'Sede actualizada correctamente.');
        })
        .fail(xhr => handleAjaxError(xhr, 'nombreEditar', 'iconEditar', 'fbEditar', 'actualizar'))
        .always(() => setBtnLoading('#btnActualizar', false, '<i class="ri-refresh-line"></i> Actualizar'));
    }

    function confirmarEliminar() {
        if (!idEliminar) return;
        setBtnLoading('#btnConfirmarEliminar', true, '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
        $.ajax({ url: '/admin/sedes/' + idEliminar, type: 'DELETE', data: { _token: CSRF } })
        .done(r => {
            closeModal('modalEliminar');
            tabla.ajax.reload();
            toast('success', r.message || 'Sede eliminada correctamente.');
        })
        .fail(xhr => {
            const msg = xhr.responseJSON ? xhr.responseJSON.message : 'No se pudo eliminar.';
            toast(xhr.status === 400 ? 'warning' : 'error', msg);
        })
        .always(() => {
            setBtnLoading('#btnConfirmarEliminar', false, '<i class="ri-delete-bin-line"></i> Eliminar');
            idEliminar = null;
        });
    }

    function cargarSucursales(sedeId) {
        $.get('/admin/sedes/listar')
        .done(r => {
            const sede = r.data.find(s => s.id === sedeId);
            const sucursales = sede ? sede.sucursales || [] : [];
            const list = $('#sucursalesList');
            const empty = $('#sucursalEmpty');

            if (sucursales.length === 0) {
                list.html('');
                empty.removeClass('d-none');
                return;
            }

            empty.addClass('d-none');
            list.html(sucursales.map(s => {
                const coords = (s.latitud && s.longitud) ? s.latitud + ', ' + s.longitud : '—';
                return '<tr>'
                    + '<td><span class="sucursal-color-preview" style="background-color:' + escHtml(s.color) + ';"></span></td>'
                    + '<td class="sucursal-nombre">' + escHtml(s.nombre) + '</td>'
                    + '<td class="sucursal-direccion">' + escHtml(s.direccion || '—') + '</td>'
                    + '<td class="sucursal-direccion">' + escHtml(coords) + '</td>'
                    + '<td>'
                        + '<div class="sede-action-cell">'
                            + '<button class="sede-btn-action sede-btn-edit btn-editar-sucursal" data-id="' + s.id + '" data-nombre="' + escHtml(s.nombre) + '" data-color="' + escHtml(s.color) + '" data-direccion="' + escHtml(s.direccion || '') + '" data-latitud="' + (s.latitud || '') + '" data-longitud="' + (s.longitud || '') + '" title="Editar sucursal"><i class="ri-pencil-fill"></i></button>'
                            + '<button class="sede-btn-action sede-btn-delete btn-eliminar-sucursal" data-id="' + s.id + '" title="Eliminar sucursal"><i class="ri-delete-bin-fill"></i></button>'
                        + '</div>'
                    + '</td>'
                + '</tr>';
            }).join(''));
        });
    }

    function agregarSucursal() {
        const nombre = $('#nombreSucursal').val().trim();
        const color = $('#colorSucursal').val();
        const direccion = $('#direccionSucursal').val().trim();
        const latitud = $('#latitudSucursal').val().trim();
        const longitud = $('#longitudSucursal').val().trim();
        const fb = $('#fbSucursal');
        const idSucursal = $('#idSucursalEditar').val();

        if (!nombre) {
            fb.text('El nombre de la sucursal es obligatorio.').addClass('text-danger').removeClass('text-success');
            return;
        }
        if (!color) {
            fb.text('El color es obligatorio.').addClass('text-danger').removeClass('text-success');
            return;
        }

        fb.text('').removeClass('text-danger text-success');

        if (idSucursal) {
            setBtnLoading('#btnAgregarSucursal', true, '<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                url: '/admin/sedes/' + window.idSedeSucursal + '/sucursales/' + idSucursal,
                type: 'PUT',
                data: { _token: CSRF, nombre: nombre, color: color, direccion: direccion, latitud: latitud || null, longitud: longitud || null }
            })
            .done(r => {
                $('#nombreSucursal').val('');
                $('#colorSucursal').val('#fc7b04');
                $('#direccionSucursal').val('');
                $('#latitudSucursal').val('');
                $('#longitudSucursal').val('');
                $('#idSucursalEditar').val('');
                $('#txtBtnSucursal').text('Agregar');
                $('#btnCancelarSucursal').addClass('d-none');
                fb.text(r.message).addClass('text-success').removeClass('text-danger');
                cargarSucursales(window.idSedeSucursal);
                tabla.ajax.reload();
            })
            .fail(xhr => {
                const errs = xhr.responseJSON?.errors || {};
                const msg = errs.nombre?.[0] || errs.color?.[0] || xhr.responseJSON?.message || 'Error al actualizar sucursal.';
                fb.text(msg).addClass('text-danger').removeClass('text-success');
            })
            .always(() => setBtnLoading('#btnAgregarSucursal', false, '<i class="ri-refresh-line"></i> Actualizar'));
        } else {
            setBtnLoading('#btnAgregarSucursal', true, '<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                url: '/admin/sedes/' + window.idSedeSucursal + '/sucursales',
                type: 'POST',
                data: { _token: CSRF, nombre: nombre, color: color, direccion: direccion, latitud: latitud || null, longitud: longitud || null }
            })
            .done(r => {
                $('#nombreSucursal').val('');
                $('#colorSucursal').val('#fc7b04');
                $('#direccionSucursal').val('');
                $('#latitudSucursal').val('');
                $('#longitudSucursal').val('');
                fb.text(r.message).addClass('text-success').removeClass('text-danger');
                cargarSucursales(window.idSedeSucursal);
                tabla.ajax.reload();
            })
            .fail(xhr => {
                const errs = xhr.responseJSON?.errors || {};
                const msg = errs.nombre?.[0] || errs.color?.[0] || xhr.responseJSON?.message || 'Error al agregar sucursal.';
                fb.text(msg).addClass('text-danger').removeClass('text-success');
            })
            .always(() => setBtnLoading('#btnAgregarSucursal', false, '<i class="ri-add-line"></i> <span id="txtBtnSucursal">Agregar</span>'));
        }
    }

    function handleAjaxError(xhr, inputId, iconId, fbId, ctx) {
        if (xhr.status === 422) {
            const errs = xhr.responseJSON.errors || {};
            if (errs.nombre) {
                const input = document.getElementById(inputId);
                const icon  = document.getElementById(iconId);
                const fb    = document.getElementById(fbId);
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                icon.className = 'validation-icon invalid';
                icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                fb.className   = 'field-feedback error';
                fb.innerHTML   = '<i class="ri-error-warning-line"></i>' + errs.nombre[0];
            }
        } else {
            toast('error', 'Ocurrió un error al ' + (ctx === 'guardar' ? 'guardar' : 'actualizar') + '. Intente nuevamente.');
        }
    }

    function setBtnLoading(sel, loading, labelHtml) {
        const btn = document.querySelector(sel);
        if (!btn) return;
        btn.disabled = loading;
        if (loading) {
            btn.dataset.orig = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>' + labelHtml;
        } else {
            btn.innerHTML = labelHtml;
        }
    }

    function openModal(id) { new bootstrap.Modal(document.getElementById(id)).show(); }

    function closeModal(id) {
        const el = document.getElementById(id);
        const m  = bootstrap.Modal.getInstance(el);
        if (m) m.hide();
    }

    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function getToastContainer() {
        let c = document.getElementById('toastContainer');
        if (c && c.parentElement !== document.body) {
            document.body.appendChild(c);
        }
        return c;
    }

    function toast(tipo, mensaje) {
        const iconMap = { success: 'ri-check-double-line', error: 'ri-close-circle-line', warning: 'ri-alert-line' };
        const el = document.createElement('div');
        el.className = 'toast-notify ' + tipo;
        el.innerHTML =
            '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') + '"></i></div>'
            + '<div class="toast-body-text"><span>' + mensaje + '</span></div>'
            + '<button class="toast-close" title="Cerrar"><i class="ri-close-line"></i></button>';

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
        el.addEventListener('animationend', () => el.remove(), { once: true });
    }

    $(document).ready(init);
})();
</script>
@endsection
