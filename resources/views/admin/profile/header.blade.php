@php
    $rolNombre = auth()->user()->role ?? 'Usuario';
@endphp

<div class="profile-header">
    <div>
        <h1><i class="ri-user-line me-2"></i>Mi Perfil</h1>
        <p>Gestiona tu información personal y configuración de cuenta</p>
    </div>
    <div class="profile-badges">
        <span class="profile-badge">
            <i class="ri-shield-user-line"></i>{{ $rolNombre }}
        </span>
        <span class="profile-badge">
            <i class="ri-calendar-check-line"></i>Miembro desde {{ auth()->user()->created_at->format('Y') }}
        </span>
    </div>
</div>
