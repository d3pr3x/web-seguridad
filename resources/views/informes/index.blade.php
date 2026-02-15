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
                <i class="fas fa-file-pdf me-2"></i>Mis Informes
            </h1>
            <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Volver a Reportes
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        @if($informes->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-1"></i>Lista de Informes ({{ $informes->total() }} total)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>N° Informe</th>
                                    <th>Reporte Base</th>
                                    <th>Tarea</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Fotografías</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($informes as $informe)
                                    <tr>
                                        <td>
                                            <strong>#{{ $informe->numero_informe }}</strong>
                                        </td>
                                        <td>
                                            <a href="{{ route('reportes.show', $informe->reporte_id) }}" 
                                               class="text-decoration-none">
                                                #{{ $informe->reporte_id }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $informe->reporte->tarea->nombre }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $informe->created_at->format('d/m/Y') }}</strong><br>
                                                <small class="text-muted">{{ $informe->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @switch($informe->estado)
                                                    @case('pendiente_revision') bg-warning @break
                                                    @case('aprobado') bg-success @break
                                                    @case('rechazado') bg-danger @break
                                                    @default bg-secondary
                                                @endswitch
                                            ">
                                                {{ $informe->estado_formateado }}
                                            </span>
                                            
                                            @if($informe->isAprobado() && $informe->fecha_aprobacion)
                                                <br>
                                                <small class="text-muted">
                                                    Aprobado: {{ $informe->fecha_aprobacion_formateada }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $informe->total_fotografias }} fotos
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('informes.show', $informe->id) }}" 
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Ver informe">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($informe->isAprobado())
                                                    <a href="{{ route('informes.pdf', $informe->id) }}" 
                                                       class="btn btn-sm btn-outline-success"
                                                       title="Descargar PDF">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($informes->hasPages())
                    <div class="card-footer">
                        {{ $informes->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No tienes informes generados</h5>
                    <p class="text-muted mb-4">
                        Los informes se crean desde los reportes que has enviado. 
                        Ve a tus reportes para generar un informe de incidente.
                    </p>
                    <a href="{{ route('reportes.index') }}" class="btn btn-primary">
                        <i class="fas fa-file-alt me-1"></i>Ver Mis Reportes
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Estadísticas rápidas -->
@if($informes->count() > 0)
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                    <h5 class="card-title">{{ $informes->where('estado', 'pendiente_revision')->count() }}</h5>
                    <p class="card-text text-muted">Pendientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h5 class="card-title">{{ $informes->where('estado', 'aprobado')->count() }}</h5>
                    <p class="card-text text-muted">Aprobados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                    <h5 class="card-title">{{ $informes->where('estado', 'rechazado')->count() }}</h5>
                    <p class="card-text text-muted">Rechazados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-camera fa-2x text-info mb-2"></i>
                    <h5 class="card-title">{{ $informes->sum('total_fotografias') }}</h5>
                    <p class="card-text text-muted">Fotografías</p>
                </div>
            </div>
        </div>
    </div>
@endif
        </div>
    </div>
</div>
@endsection
