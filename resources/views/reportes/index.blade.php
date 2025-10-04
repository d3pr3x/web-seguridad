@extends('layouts.app')

@section('title', 'Mis Reportes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-file-alt me-2"></i>Mis Reportes
            </h1>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Volver al Dashboard
            </a>
        </div>
    </div>
</div>

@if($reportes->count() > 0)
    <div class="row">
        @foreach($reportes as $reporte)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">{{ $reporte->tarea->nombre }}</h6>
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
                    <div class="card-body">
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $reporte->created_at->format('d/m/Y H:i') }}
                            </small>
                        </p>
                        
                        @if($reporte->imagenes && count($reporte->imagenes) > 0)
                            <p class="card-text">
                                <i class="fas fa-images me-1"></i>
                                {{ count($reporte->imagenes) }} imagen(es)
                            </p>
                        @endif
                        
                        @if($reporte->comentarios_admin)
                            <div class="alert alert-info p-2">
                                <small>
                                    <strong>Comentario:</strong> {{ $reporte->comentarios_admin }}
                                </small>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('reportes.show', $reporte->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Paginación -->
    <div class="row">
        <div class="col-12">
            {{ $reportes->links() }}
        </div>
    </div>
@else
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-3x mb-3"></i>
                <h4>No tienes reportes</h4>
                <p class="mb-3">Aún no has enviado ningún reporte.</p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Crear mi primer reporte
                </a>
            </div>
        </div>
    </div>
@endif
@endsection
