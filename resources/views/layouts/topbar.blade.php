<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="index" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
                        </span>
                    </a>

                    <a href="index" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('build/images/logo_chico.png') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('build/images/logo_grande.png') }}" alt="" height="17">
                        </span>
                    </a>
                </div>

                <button type="button"
                    class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger material-shadow-none"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>


            </div>

            <div class="d-flex align-items-center">

                <div class="dropdown d-md-none topbar-head-dropdown header-item">
                    <button type="button"
                        class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle"
                        id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="bx bx-search fs-22"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                        aria-labelledby="page-header-search-dropdown">
                        <form class="p-3">
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search ..."
                                        aria-label="Recipient's username">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @auth
                    <div class="ms-1 header-item d-none d-sm-flex">
                        <button type="button"
                            class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle"
                            data-toggle="fullscreen">
                            <i class='bx bx-fullscreen fs-22'></i>
                        </button>
                    </div>

                    <div class="ms-1 header-item d-none d-sm-flex">
                        <button type="button"
                            class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle light-dark-mode">
                            <i class='bx bx-moon fs-22'></i>
                        </button>
                    </div>

                    <div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
                        <button type="button"
                            class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle"
                            id="page-header-notifications-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                            aria-haspopup="true" aria-expanded="false">
                            <i class='ri-file-list-3-line fs-22'></i>
                            @if ($comprobantesPendientes > 0)
                                <span
                                    class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">{{ $comprobantesPendientes }}<span
                                        class="visually-hidden">comprobantes pendientes</span></span>
                            @endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                            aria-labelledby="page-header-notifications-dropdown">

                            <div class="dropdown-head bg-primary bg-pattern rounded-top">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0 fs-16 fw-semibold text-white">Notificaciones</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-content position-relative" id="notificationItemsTabContent">
                                <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                    <div data-simplebar style="max-height: 300px;" class="pe-2">
                                        @if ($comprobantesPendientes > 0)
                                            <div
                                                class="text-reset notification-item d-block dropdown-item position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar-xs me-3 flex-shrink-0">
                                                        <span
                                                            class="avatar-title bg-warning-subtle text-warning rounded-circle fs-16">
                                                            <i class="ri-file-list-3-line"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <a href="{{ route('admin.comprobantes.index', ['tab' => 'nuevos']) }}"
                                                            class="stretched-link">
                                                            <h6 class="mt-0 mb-2 lh-base">Tienes <b
                                                                    class="text-danger">{{ $comprobantesPendientes }}</b>
                                                                comprobante(s) de pago pendiente(s)</h6>
                                                        </a>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> Requiere
                                                                verificación</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="empty-notification-elem text-center py-4">
                                                <i class="ri-notification-off-line fs-48 text-muted mb-2 d-block"></i>
                                                <h6 class="fs-16 fw-semibold lh-base">¡Hey! No tienes ninguna notificación
                                                </h6>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $authUser = Auth::user();
                        $authPersona = $authUser->persona;

                        if (
                            $authPersona &&
                            $authPersona->fotografia &&
                            file_exists(public_path('images/personas/' . $authPersona->fotografia))
                        ) {
                            $topbarAvatar = URL::asset('images/personas/' . $authPersona->fotografia);
                        } elseif ($authUser->avatar && file_exists(public_path('images/' . $authUser->avatar))) {
                            $topbarAvatar = URL::asset('images/' . $authUser->avatar);
                        } else {
                            $topbarAvatar = URL::asset('build/images/users/avatar-1.jpg');
                        }

                        $topbarNombre = $authPersona
                            ? (trim(($authPersona->nombres ?? '') . ' ' . ($authPersona->apellido_paterno ?? '')) ?:
                            $authUser->name)
                            : $authUser->name;

                        $topbarRol = ucfirst($authUser->role ?? 'Usuario');
                    @endphp

                    @if ($moodleConectado !== null)
                        <div class="ms-2 header-item d-none d-sm-flex align-items-center">
                            <span class="moodle-status-badge {{ $moodleConectado ? 'moodle-online' : 'moodle-offline' }}"
                                title="{{ $moodleConectado ? 'Moodle conectado' : 'Moodle desconectado' }}">
                                <span class="moodle-dot"></span>
                                <span class="moodle-label">Moodle</span>
                            </span>
                        </div>
                    @endif

                    <div class="dropdown ms-sm-3 header-item topbar-user topbar-user-dropdown">
                        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="d-flex align-items-center">
                                <img class="rounded-circle header-profile-user" src="{{ $topbarAvatar }}" alt="Avatar">
                                <span class="text-start ms-xl-2">
                                    <span
                                        class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ $topbarNombre }}</span>
                                    <span
                                        class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">{{ $topbarRol }}</span>
                                </span>
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end tud-menu">
                            <div class="tud-header">
                                <div class="tud-avatar-wrap">
                                    <img src="{{ $topbarAvatar }}" alt="Avatar" class="tud-avatar">
                                </div>
                                <div class="tud-info">
                                    <div class="tud-name">{{ $topbarNombre }}</div>
                                    <span class="tud-role">{{ $topbarRol }}</span>
                                </div>
                            </div>
                            <div class="tud-body">
                                <a class="tud-item" href="{{ route('admin.profile.index') }}">
                                    <span class="tud-item-icon"><i class="ri-user-line"></i></span>
                                    <span>Mi Perfil</span>
                                </a>
                                <div class="tud-divider"></div>
                                <a class="tud-item tud-item-danger" href="javascript:void(0);"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <span class="tud-item-icon tud-item-icon-danger"><i
                                            class="ri-logout-box-r-line"></i></span>
                                    <span>Cerrar Sesión</span>
                                </a>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @endauth

                <style>
                    /* ── Moodle Status Badge ── */
                    .moodle-status-badge {
                        display: inline-flex;
                        align-items: center;
                        gap: 5px;
                        padding: 4px 10px;
                        border-radius: 50px;
                        font-size: 0.72rem;
                        font-weight: 600;
                        letter-spacing: 0.02em;
                        transition: opacity 0.2s;
                    }

                    .moodle-status-badge.moodle-online {
                        background: rgba(34, 197, 94, 0.12);
                        color: #16a34a;
                        border: 1px solid rgba(34, 197, 94, 0.3);
                    }

                    .moodle-status-badge.moodle-offline {
                        background: rgba(239, 68, 68, 0.10);
                        color: #dc2626;
                        border: 1px solid rgba(239, 68, 68, 0.25);
                    }

                    .moodle-dot {
                        width: 7px;
                        height: 7px;
                        border-radius: 50%;
                        flex-shrink: 0;
                        background: currentColor;
                    }

                    .moodle-online .moodle-dot {
                        animation: moodle-pulse 2s infinite;
                    }

                    @keyframes moodle-pulse {

                        0%,
                        100% {
                            opacity: 1;
                        }

                        50% {
                            opacity: 0.4;
                        }
                    }

                    /* ── User Dropdown (topbar) ── */
                    .topbar-user-dropdown .tud-menu {
                        border: none;
                        border-radius: 14px;
                        box-shadow: 0 8px 30px rgba(154, 73, 4, 0.18), 0 2px 8px rgba(0, 0, 0, 0.08);
                        overflow: hidden;
                        min-width: 220px;
                        padding: 0;
                        margin-top: 8px !important;
                    }

                    .topbar-user-dropdown .tud-header {
                        background: linear-gradient(135deg, #9a4904 0%, #df6a04 100%);
                        padding: 16px;
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        position: relative;
                        overflow: hidden;
                    }

                    .topbar-user-dropdown .tud-header::after {
                        content: '';
                        position: absolute;
                        top: -30%;
                        right: -10%;
                        width: 100px;
                        height: 100px;
                        background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, transparent 70%);
                        border-radius: 50%;
                        pointer-events: none;
                    }

                    .topbar-user-dropdown .tud-avatar-wrap {
                        flex-shrink: 0;
                        position: relative;
                        z-index: 1;
                    }

                    .topbar-user-dropdown .tud-avatar {
                        width: 42px;
                        height: 42px;
                        border-radius: 50%;
                        object-fit: cover;
                        border: 2px solid rgba(255, 255, 255, 0.6);
                    }

                    .topbar-user-dropdown .tud-info {
                        position: relative;
                        z-index: 1;
                        overflow: hidden;
                    }

                    .topbar-user-dropdown .tud-name {
                        font-size: 0.875rem;
                        font-weight: 700;
                        color: #fff;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        max-width: 140px;
                        line-height: 1.2;
                    }

                    .topbar-user-dropdown .tud-role {
                        display: inline-flex;
                        align-items: center;
                        margin-top: 4px;
                        font-size: 0.68rem;
                        font-weight: 600;
                        padding: 2px 8px;
                        background: rgba(255, 255, 255, 0.2);
                        border: 1px solid rgba(255, 255, 255, 0.3);
                        border-radius: 50px;
                        color: rgba(255, 255, 255, 0.95);
                        backdrop-filter: blur(4px);
                    }

                    .topbar-user-dropdown .tud-body {
                        padding: 8px;
                    }

                    .topbar-user-dropdown .tud-item {
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        padding: 9px 12px;
                        border-radius: 8px;
                        font-size: 0.84rem;
                        font-weight: 500;
                        color: #391b04;
                        text-decoration: none;
                        transition: all 0.15s ease;
                        cursor: pointer;
                    }

                    .topbar-user-dropdown .tud-item:hover {
                        background: rgba(252, 123, 4, 0.09);
                        color: #9a4904;
                    }

                    .topbar-user-dropdown .tud-item-icon {
                        width: 30px;
                        height: 30px;
                        border-radius: 8px;
                        background: rgba(252, 123, 4, 0.10);
                        color: #df6a04;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 0.9rem;
                        flex-shrink: 0;
                        transition: all 0.15s ease;
                    }

                    .topbar-user-dropdown .tud-item:hover .tud-item-icon {
                        background: rgba(252, 123, 4, 0.18);
                        color: #9a4904;
                    }

                    .topbar-user-dropdown .tud-divider {
                        height: 1px;
                        background: rgba(154, 73, 4, 0.10);
                        margin: 4px 0;
                    }

                    .topbar-user-dropdown .tud-item-danger {
                        color: #b91c1c;
                    }

                    .topbar-user-dropdown .tud-item-danger:hover {
                        background: rgba(239, 68, 68, 0.07);
                        color: #991b1b;
                    }

                    .topbar-user-dropdown .tud-item-icon-danger {
                        background: rgba(239, 68, 68, 0.10);
                        color: #ef4444;
                    }

                    .topbar-user-dropdown .tud-item-danger:hover .tud-item-icon-danger {
                        background: rgba(239, 68, 68, 0.18);
                    }
                </style>
            </div>
        </div>
    </div>
</header>

<!-- removeNotificationModal -->
<div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete
                        It!</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
