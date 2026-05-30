@extends('layouts.master')
@section('title', 'Mi Perfil')

@section('css')
    @include('admin.profile.styles')
@endsection

@section('content')
<div class="profile-page">

    @include('admin.profile.header')

    <div class="row g-4">

        <div class="col-12">
            <div class="profile-card">
                <div class="profile-card-header">
                    @include('admin.profile.tabs.navigation')
                </div>
                <div class="profile-card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personal" role="tabpanel">
                            @include('admin.profile.tabs.personal')
                        </div>
                        <div class="tab-pane" id="documentos" role="tabpanel">
                            @include('admin.profile.tabs.documentos')
                        </div>
                        <div class="tab-pane" id="password" role="tabpanel">
                            @include('admin.profile.tabs.password')
                        </div>
                        @if($tieneMarketing)
                        <div class="tab-pane" id="marketing" role="tabpanel">
                            @include('admin.profile.tabs.marketing')
                        </div>
                        <div class="tab-pane" id="ofertas-activas" role="tabpanel">
                            @include('admin.profile.tabs.ofertas-activas')
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('admin.profile.modals.upload-foto')
    @include('admin.profile.modals.upload-doc')
    @if($tieneMarketing)
    @include('admin.profile.modals.enlace-preinscripcion')
    @endif

</div>
@endsection

@push('scripts')
    @if($tieneMarketing)
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    @endif
    @include('admin.profile.scripts')
@endpush
