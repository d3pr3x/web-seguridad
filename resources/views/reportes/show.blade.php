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
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-file-alt me-2"></i>Detalle del Reporte
            </h1>
            <div>
                <a href="{{ route('informes.create', $reporte->id) }}" class="btn btn-primary me-2">
                    <i class="fas fa-file-pdf me-1"></i>Generar Informe
                </a>
                <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    {{ $reporte->tarea->nombre }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Estado:</strong>
                        <span class="badge 
                            @switch($reporte->estado)
                                @case('pendiente') bg-warning @break
                                @case('en_revision') bg-info @break
                                @case('completado') bg-success @break
                                @case('rechazado') bg-danger @break
                                @default bg-secondary
                            @endswitch
                        ">
                            {{ ucfirst(str_replace('_', ' ', $reporte->estado)) }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Fecha de envío:</strong>
                        {{ $reporte->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
                
                <hr>
                
                <h6>Datos del reporte:</h6>
                <div class="row">
                    @foreach($reporte->datos as $detalleId => $valor)
                        @php
                            $detalle = $reporte->tarea->detalles->find($detalleId);
                        @endphp
                        @if($detalle)
                            <div class="col-md-6 mb-3">
                                <strong>{{ $detalle->campo_nombre }}:</strong>
                                <p class="mb-0">{{ $valor }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
                
                @if($reporte->imagenes && count($reporte->imagenes) > 0)
                    <hr>
                    <h6>Imágenes adjuntas:</h6>
                    <div class="row">
@foreach($reporte->imagenes as $idx => $imagen)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <img src="{{ route('archivos-privados.reporte', [$reporte, $idx]) }}"
                                         class="card-img-top"
                                         style="height: 200px; object-fit: cover;"
                                         alt="Imagen del reporte">
                                    <div class="card-body p-2">
                                        <small class="text-muted">
                                            <a href="{{ route('archivos-privados.reporte', [$reporte, $idx]) }}"
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt me-1"></i>Ver completa
                                            </a>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                @if($reporte->latitud && $reporte->longitud)
                    <hr>
                    <h6>
                        <i class="fas fa-map-marker-alt me-2"></i>Ubicación GPS
                    </h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Latitud:</strong>
                            <p class="mb-0">{{ $reporte->latitud }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Longitud:</strong>
                            <p class="mb-0">{{ $reporte->longitud }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Precisión:</strong>
                            <p class="mb-0">{{ $reporte->precision ? round($reporte->precision) . 'm' : 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <a href="https://www.google.com/maps?q={{ $reporte->latitud }},{{ $reporte->longitud }}" 
                           target="_blank" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-map me-1"></i>Ver en Google Maps
                        </a>
                        <a href="https://www.openstreetmap.org/?mlat={{ $reporte->latitud }}&mlon={{ $reporte->longitud }}&zoom=16" 
                           target="_blank" 
                           class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-map-marked-alt me-1"></i>Ver en OpenStreetMap
                        </a>
                    </div>
                    
                    <!-- Mapa embebido -->
                    <div class="ratio ratio-16x9 mb-3">
                        <iframe 
                            src="https://www.openstreetmap.org/export/embed.html?bbox={{ $reporte->longitud - 0.01 }},{{ $reporte->latitud - 0.01 }},{{ $reporte->longitud + 0.01 }},{{ $reporte->latitud + 0.01 }}&layer=mapnik&marker={{ $reporte->latitud }},{{ $reporte->longitud }}" 
                            style="border: 1px solid #ccc; border-radius: 0.25rem;">
                        </iframe>
                    </div>
                @endif
                
                @if($reporte->comentarios_admin)
                    <hr>
                    <h6>Comentarios del administrador:</h6>
                    <div class="alert alert-info">
                        <i class="fas fa-comment me-2"></i>
                        {{ $reporte->comentarios_admin }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-1"></i>Información
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    <strong>Usuario:</strong><br>
                    {{ $reporte->user->nombre_completo }}
                </p>
                <p class="card-text">
                    <strong>RUT:</strong><br>
                    {{ $reporte->user->run }}
                </p>
                <p class="card-text">
                    <strong>Sucursal:</strong><br>
                    {{ $reporte->user->sucursal }}
                </p>
                <p class="card-text">
                    <strong>Última actualización:</strong><br>
                    {{ $reporte->updated_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-1"></i>Estadísticas
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    <strong>Campos completados:</strong><br>
                    {{ count($reporte->datos) }} de {{ $reporte->tarea->detalles->count() }}
                </p>
                <p class="card-text">
                    <strong>Imágenes adjuntas:</strong><br>
                    {{ $reporte->imagenes ? count($reporte->imagenes) : 0 }}
                </p>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
@endsection


