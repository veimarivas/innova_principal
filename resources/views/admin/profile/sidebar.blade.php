@php
    $persona = auth()->user()->persona;
    $avatarUrl = auth()->user()->avatar
        ? asset('images/' . auth()->user()->avatar)
        : ($persona?->fotografia ? asset($persona->fotografia) : asset('build/images/users/avatar-1.jpg'));
@endphp

<div class="col-xl-3 col-lg-3">

    {{-- Tarjeta de perfil --}}
    <div class="profile-sidebar-card mb-3">

        {{-- Banner superior --}}
        <div class="profile-sidebar-banner"></div>

        <div class="profile-sidebar-body">

            {{-- Avatar --}}
            <div class="profile-avatar-wrapper">
                <img id="profileAvatar"
                     src="{{ $avatarUrl }}"
                     alt="Avatar"
                     class="profile-avatar"
                     onerror="this.src='{{ asset('build/images/users/avatar-1.jpg') }}'">
                <button class="profile-avatar-btn"
                        data-bs-toggle="modal" data-bs-target="#uploadFotoModal"
                        title="Cambiar foto">
                    <i class="ri-camera-line"></i>
                </button>
            </div>

            {{-- Nombre completo --}}
            <h5 id="profileName" class="profile-name">
                @if($persona)
                    {{ trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')) ?: auth()->user()->name }}
                @else
                    {{ auth()->user()->name }}
                @endif
            </h5>

            <p class="profile-cargo">
                {{ auth()->user()->email }}
            </p>

            <span class="profile-role-badge">
                <i class="ri-shield-user-line"></i>{{ auth()->user()->role ?? 'Usuario' }}
            </span>

            {{-- Mini badges --}}
            @if($persona)
            <div class="profile-mini-badges">
                @if($persona->carnet)
                    <span class="profile-mini-badge">
                        <i class="ri-id-card-line"></i>{{ $persona->carnet }}
                    </span>
                @endif
                @if($persona->sexo)
                    <span class="profile-mini-badge">
                        <i class="ri-genderless-line"></i>{{ $persona->sexo }}
                    </span>
                @endif
                @if($persona->fecha_nacimiento)
                    <span class="profile-mini-badge">
                        <i class="ri-cake-line"></i>{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->age }} años
                    </span>
                @endif
            </div>

            {{-- Contacto --}}
            <div class="profile-contact-section">
                @foreach([
                    ['icon'=>'ri-mail-line',    'color'=>'primary', 'label'=>'Correo',    'value'=> $persona->correo  ?? null],
                    ['icon'=>'ri-phone-line',   'color'=>'success', 'label'=>'Celular',   'value'=> $persona->celular ?? null],
                    ['icon'=>'ri-map-pin-line', 'color'=>'info',    'label'=>'Ubicación', 'value'=> optional($persona->ciudad)->nombre],
                ] as $item)
                    @if($item['value'])
                    <div class="profile-contact-item">
                        <div class="profile-contact-icon bg-{{ $item['color'] }}-subtle text-{{ $item['color'] }}">
                            <i class="{{ $item['icon'] }}"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="profile-contact-label">{{ $item['label'] }}</div>
                            <div class="profile-contact-value text-truncate">{{ $item['value'] }}</div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            @endif

        </div>
    </div>

    {{-- Info rápida --}}
    <div class="quick-info-card">
        <div class="quick-info-header">
            <i class="ri-information-line"></i>
            <span>Información Rápida</span>
        </div>
        <div class="card-body p-0">
            @foreach([
                ['label'=>'Estudios',      'icon'=>'ri-graduation-cap-line', 'value'=> $persona?->estudios?->count() ?? 0],
                ['label'=>'Miembro desde', 'icon'=>'ri-calendar-check-line', 'value'=> auth()->user()->created_at->format('d/m/Y')],
                ['label'=>'Estado',        'icon'=>'ri-checkbox-circle-line', 'value'=> auth()->user()->estado ?? 'Activo'],
            ] as $item)
                <div class="quick-info-item">
                    <div class="qi-left">
                        <i class="{{ $item['icon'] }}"></i>
                        <span class="qi-label">{{ $item['label'] }}</span>
                    </div>
                    <span class="qi-value">{{ $item['value'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

</div>
