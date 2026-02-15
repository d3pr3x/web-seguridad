@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />
        <div class="container mx-auto px-4 py-6 max-w-7xl">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Detalle de Acción</h4>
                    <a href="{{ route('acciones.index') }}" class="btn btn-sm btn-secondary">Volver</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tipo de Acción:</strong>
                            <p class="mb-0">
                                <span class="badge bg-primary">{{ $accion->nombre_tipo }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Estado:</strong>
                            <p class="mb-0">Registrado</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Fecha:</strong>
                            <p>{{ $accion->dia->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Hora:</strong>
                            <p>{{ \Carbon\Carbon::parse($accion->hora)->format('H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Usuario:</strong>
                            <p>{{ $accion->user->nombre_completo }} ({{ $accion->user->run }})</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Sucursal:</strong>
                            <p>{{ $accion->sucursal->nombre }}</p>
                        </div>
                    </div>

                    @if($accion->sector)
                        <div class="mb-3">
                            <strong>Sector:</strong>
                            <p>{{ $accion->sector->nombre }}</p>
                        </div>
                    @endif

                    @if($accion->novedad)
                        <div class="mb-3">
                            <strong>Novedad:</strong>
                            <p class="border p-3 bg-light">{{ $accion->novedad }}</p>
                        </div>
                    @endif

                    @if($accion->accion)
                        <div class="mb-3">
                            <strong>Acción:</strong>
                            <p class="border p-3 bg-light">{{ $accion->accion }}</p>
                        </div>
                    @endif

                    @if($accion->resultado)
                        <div class="mb-3">
                            <strong>Resultado:</strong>
                            <p class="border p-3 bg-light">{{ $accion->resultado }}</p>
                        </div>
                    @endif

                    @if($accion->imagenes && count($accion->imagenes) > 0)
                        <div class="mb-3">
                            <strong>Imágenes:</strong>
                            <div class="row mt-2">
                                @foreach($accion->imagenes as $imagen)
                                    <div class="col-md-4 mb-3">
                                        <a href="{{ asset('storage/' . $imagen) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $imagen) }}" 
                                                 alt="Imagen" 
                                                 class="img-fluid img-thumbnail">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($accion->latitud && $accion->longitud)
                        <div class="mb-3">
                            <strong>Ubicación:</strong>
                            <p>
                                Lat: {{ $accion->latitud }}, Long: {{ $accion->longitud }}
                                @if($accion->precision)
                                    (Precisión: {{ round($accion->precision) }}m)
                                @endif
                            </p>
                            <a href="https://www.google.com/maps?q={{ $accion->latitud }},{{ $accion->longitud }}" 
                               target="_blank" 
                               class="btn btn-sm btn-info">
                                Ver en Google Maps
                            </a>
                        </div>
                    @endif

                    <hr>

                    <div class="text-muted small">
                        <p class="mb-1">Registrado: {{ $accion->created_at->format('d/m/Y H:i:s') }}</p>
                        @if($accion->updated_at != $accion->created_at)
                            <p class="mb-0">Actualizado: {{ $accion->updated_at->format('d/m/Y H:i:s') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
@endsection




