@extends('layouts.master')
@section('title', 'Cuentas Bancarias')

@section('css')
<style>
    .cuenta-card { transition: all 0.3s; }
    .cuenta-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .tipo-badge { font-size: 0.7rem; padding: 0.2rem 0.4rem; }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="page-title-box d-flex align-items-center justify-content-between">
        <h4 class="mb-0">Cuentas Bancarias</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaCuenta">
            <i class="ri-add-circle-line me-1"></i> Nueva Cuenta
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($cuentas as $cuenta)
        <div class="col-md-6 col-xl-4">
            <div class="card cuenta-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-1">{{ $cuenta->banco->nombre }}</h6>
                            <p class="text-muted mb-0 small">{{ $cuenta->numero_cuenta }}</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-info tipo-badge">{{ $cuenta->tipo_cuenta }}</span>
                            @if($cuenta->es_principal)
                            <span class="badge bg-success tipo-badge ms-1">Principal</span>
                            @endif
                        </div>
                    </div>
                    @if($cuenta->titular)
                    <p class="mb-1 small"><strong>Titular:</strong> {{ $cuenta->titular }}</p>
                    @endif
                    @if($cuenta->imagen_qr)
                    <p class="mb-2 small"><i class="ri-qr-code-line me-1"></i> QR disponible</p>
                    @endif
                    <div class="mt-2 pt-2 border-top d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $cuenta->id }}">
                            <i class="ri-edit-line"></i>
                        </button>
                        @if(!$cuenta->es_principal)
                        <a href="{{ route('admin.cuentas-bancarias.principal', $cuenta->id) }}" class="btn btn-sm btn-outline-success" title="Establecer principal">
                            <i class="ri-star-line"></i>
                        </a>
                        @endif
                        <form action="{{ route('admin.cuentas-bancarias.toggle', $cuenta->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-{{ $cuenta->estado ? 'warning' : 'success' }}">
                                <i class="ri-{{ $cuenta->estado ? 'eye-off' : 'eye' }}-line"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.cuentas-bancarias.destroy', $cuenta->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar cuenta?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="ri-bank-line fs-1 text-muted"></i>
                <p class="mt-2">No hay cuentas bancarias registradas</p>
            </div>
        </div>
        @endforelse
    </div>

    {{ $cuentas->links() }}
</div>

<!-- Modal Nueva Cuenta -->
<div class="modal fade" id="modalNuevaCuenta" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.cuentas-bancarias.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Cuenta Bancaria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Banco *</label>
                        <select name="banco_id" class="form-select" required>
                            <option value="">Seleccionar...</option>
                            @foreach($bancos as $banco)
                            <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Número de Cuenta *</label>
                        <input type="text" name="numero_cuenta" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de Cuenta *</label>
                        <select name="tipo_cuenta" class="form-select" required>
                            <option value="Cuenta Corriente">Cuenta Corriente</option>
                            <option value="Cuenta de Ahorro">Cuenta de Ahorro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Titular</label>
                        <input type="text" name="titular" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">CI Titular</label>
                        <input type="text" name="ci_titular" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Imagen QR</label>
                        <input type="file" name="imagen_qr" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha Vencimiento QR</label>
                        <input type="date" name="fecha_vencimiento_qr" class="form-control">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="es_principal" class="form-check-input" id="esPrincipal">
                        <label class="form-check-label" for="esPrincipal">Cuenta principal</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modales Editar -->
@foreach($cuentas as $cuenta)
<div class="modal fade" id="modalEditar{{ $cuenta->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.cuentas-bancarias.update', $cuenta->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Cuenta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Banco *</label>
                        <select name="banco_id" class="form-select" required>
                            @foreach($bancos as $banco)
                            <option value="{{ $banco->id }}" {{ $cuenta->banco_id == $banco->id ? 'selected' : '' }}>{{ $banco->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Número de Cuenta *</label>
                        <input type="text" name="numero_cuenta" class="form-control" value="{{ $cuenta->numero_cuenta }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de Cuenta *</label>
                        <select name="tipo_cuenta" class="form-select" required>
                            <option value="Cuenta Corriente" {{ $cuenta->tipo_cuenta == 'Cuenta Corriente' ? 'selected' : '' }}>Cuenta Corriente</option>
                            <option value="Cuenta de Ahorro" {{ $cuenta->tipo_cuenta == 'Cuenta de Ahorro' ? 'selected' : '' }}>Cuenta de Ahorro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Titular</label>
                        <input type="text" name="titular" class="form-control" value="{{ $cuenta->titular }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">CI Titular</label>
                        <input type="text" name="ci_titular" class="form-control" value="{{ $cuenta->ci_titular }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha Vencimiento QR</label>
                        <input type="date" name="fecha_vencimiento_qr" class="form-control" value="{{ $cuenta->fecha_vencimiento_qr }}">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="es_principal" class="form-check-input" id="esPrincipal{{ $cuenta->id }}" {{ $cuenta->es_principal ? 'checked' : '' }}>
                        <label class="form-check-label" for="esPrincipal{{ $cuenta->id }}">Cuenta principal</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection