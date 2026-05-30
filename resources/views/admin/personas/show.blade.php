@extends('layouts.master')
@section('title') Ver Persona @endsection
@section('css')
<style>
.btn-volver {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: var(--d-card);
    border: 1px solid var(--d-card-border);
    color: var(--d-body);
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.6rem 1.2rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}
.btn-volver:hover {
    background: var(--d-card-bg);
    border-color: #fc7b04;
    color: #fc7b04;
}
.btn-action-ver {
    background: rgba(4, 132, 244, 0.10);
    border: 1px solid rgba(4, 132, 244, 0.20);
    color: #0484f4;
}
.btn-action-ver:hover {
    background: #0484f4;
    color: #fff;
}
html[data-bs-theme="dark"] .btn-action-ver {
    background: rgba(4, 132, 244, 0.15);
    border-color: rgba(4, 132, 244, 0.30);
    color: #2da9ff;
}
html[data-bs-theme="dark"] .btn-action-ver:hover {
    background: #0484f4;
    color: #fff;
}
.detail-card {
    background: var(--d-card);
    border: 1px solid var(--d-card-border);
    border-radius: 16px;
    box-shadow: var(--d-card-shadow);
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.detail-header {
    background: var(--d-header-bg);
    border-bottom: 1px solid var(--d-header-border);
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.detail-header h5 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--d-title);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.detail-header h5 i { color: #fc7b04; }
.detail-body { padding: 1.5rem; }
.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.25rem;
}
.detail-item label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--d-muted);
    margin-bottom: 0.35rem;
}
.detail-item span {
    font-size: 0.92rem;
    font-weight: 600;
    color: var(--d-body);
}
.estudios-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.estudios-table thead th {
    background: var(--d-thead-bg);
    color: var(--d-thead-color);
    font-size: 0.67rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 0.65rem 0.85rem;
    border-bottom: 2px solid var(--d-header-border);
    text-align: left;
}
.estudios-table tbody td {
    padding: 0.75rem 0.85rem;
    border-bottom: 1px solid var(--d-row-border);
    color: var(--d-body);
    vertical-align: middle;
    font-size: 0.83rem;
}
.estudios-table tbody tr:hover td { background: var(--d-row-hover); }
.estudios-table tbody tr:last-child td { border-bottom: none; }
.badge-estado-concluido {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: rgba(91,138,48,0.12);
    color: #5a8a30;
    border: 1px solid rgba(91,138,48,0.25);
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.25rem 0.65rem;
    border-radius: 6px;
}
.badge-estado-desarrollo {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: rgba(252,123,4,0.12);
    color: #bc5404;
    border: 1px solid rgba(252,123,4,0.25);
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.25rem 0.65rem;
    border-radius: 6px;
}
html[data-bs-theme="dark"] .badge-estado-concluido {
    background: rgba(109,191,64,0.12);
    color: #6dbf40;
    border-color: rgba(109,191,64,0.25);
}
html[data-bs-theme="dark"] .badge-estado-desarrollo {
    background: rgba(252,123,4,0.15);
    color: #fc7b04;
    border-color: rgba(252,123,4,0.30);
}
.badge-principal {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    background: rgba(252,200,4,0.15);
    color: #a07804;
    border: 1px solid rgba(252,200,4,0.30);
    font-size: 0.68rem;
    font-weight: 700;
    padding: 0.15rem 0.5rem;
    border-radius: 5px;
}
html[data-bs-theme="dark"] .badge-principal {
    background: rgba(252,200,4,0.12);
    color: #fcc804;
    border-color: rgba(252,200,4,0.25);
}
.estudios-empty {
    text-align: center;
    color: var(--d-muted);
    padding: 2rem;
    font-size: 0.85rem;
}
</style>
@endsection

@section('content')
<div class="dept-page-header">
    <div class="container-fluid">
        <div class="dph-inner">
            <div class="dph-left">
                <div class="dph-icon-wrap"><i class="ri-user-line"></i></div>
                <div>
                    <h1 class="dph-title">Detalle de Persona</h1>
                    <p class="dph-desc">Información completa de la persona registrada</p>
                </div>
            </div>
            <div class="dph-right">
                <a href="{{ route('admin.personas.index') }}" class="btn-volver">
                    <i class="ri-arrow-left-line"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    {{-- Datos Personales --}}
    <div class="detail-card">
        <div class="detail-header">
            <h5><i class="ri-id-card-line"></i> Datos Personales</h5>
        </div>
        <div class="detail-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Carnet</label>
                    <span>{{ $persona->carnet }}{{ $persona->expedido ? ' (' . $persona->expedido . ')' : '' }}</span>
                </div>
                <div class="detail-item">
                    <label>Nombres</label>
                    <span>{{ $persona->nombres }}</span>
                </div>
                <div class="detail-item">
                    <label>Apellido Paterno</label>
                    <span>{{ $persona->apellido_paterno ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <label>Apellido Materno</label>
                    <span>{{ $persona->apellido_materno ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <label>Sexo</label>
                    <span>{{ $persona->sexo == 'M' ? 'Masculino' : ($persona->sexo == 'F' ? 'Femenino' : '—') }}</span>
                </div>
                <div class="detail-item">
                    <label>Estado Civil</label>
                    <span>{{ $persona->estado_civil ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <label>Fecha de Nacimiento</label>
                    <span>{{ $persona->fecha_nacimiento ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') : '—' }}</span>
                </div>
                <div class="detail-item">
                    <label>Correo</label>
                    <span>{{ $persona->correo ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <label>Celular</label>
                    <span>{{ $persona->celular ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <label>Teléfono</label>
                    <span>{{ $persona->telefono ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <label>Dirección</label>
                    <span>{{ $persona->direccion ?? '—' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Ubicación --}}
    <div class="detail-card">
        <div class="detail-header">
            <h5><i class="ri-map-pin-line"></i> Ubicación</h5>
        </div>
        <div class="detail-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Departamento</label>
                    <span>{{ $persona->ciudad?->departamento?->nombre ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <label>Ciudad</label>
                    <span>{{ $persona->ciudad?->nombre ?? '—' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Estudios --}}
    <div class="detail-card">
        <div class="detail-header">
            <h5><i class="ri-book-3-line"></i> Estudios</h5>
        </div>
        <div class="detail-body">
            @if($persona->estudios->count() > 0)
                <table class="estudios-table">
                    <thead>
                        <tr>
                            <th>Grado Académico</th>
                            <th>Universidad</th>
                            <th>Profesión</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($persona->estudios as $estudio)
                            <tr>
                                <td>
                                    @if($estudio->principal)
                                        <span class="badge-principal">PRINCIPAL</span>
                                    @endif
                                    {{ $estudio->grado_academico?->nombre ?? '—' }}
                                </td>
                                <td>{{ $estudio->universidad?->nombre ?? '—' }}</td>
                                <td>{{ $estudio->profesion?->nombre ?? '—' }}</td>
                                <td>
                                    @if($estudio->estado == 'Concluido')
                                        <span class="badge-estado-concluido"><i class="ri-checkbox-circle-fill"></i> Concluido</span>
                                    @else
                                        <span class="badge-estado-desarrollo"><i class="ri-loader-4-line"></i> En Desarrollo</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="estudios-empty">No hay estudios registrados.</div>
            @endif
        </div>
    </div>
</div>
@endsection