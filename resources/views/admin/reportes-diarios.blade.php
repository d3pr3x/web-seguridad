@extends('layouts.app')

@section('title', 'Reportes Diarios')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-line me-2"></i>Reportes Diarios
            </h1>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reportes-diarios') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" value="{{ $fecha }}">
                    </div>
                    <div class="col-md-4">
                        <label for="sucursal_id" class="form-label">Sucursal</label>
                        <select class="form-control" id="sucursal_id" name="sucursal_id">
                            <option value="">Todas las sucursales</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}" @if($sucursalId == $sucursal->id) selected @endif>
                                    {{ $sucursal->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Filtrar
                        </button>
                        <a href="{{ route('admin.reportes-diarios.exportar', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download me-1"></i>Exportar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-file-alt fa-2x mb-2"></i>
                <h4>{{ $totalReportes }}</h4>
                <p class="mb-0">Reportes del día</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-calendar-check fa-2x mb-2"></i>
                <h4>{{ $totalDiasTrabajados }}</h4>
                <p class="mb-0">Días trabajados</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x mb-2"></i>
                <h4>{{ $usuariosActivos }}</h4>
                <p class="mb-0">Usuarios activos</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-building fa-2x mb-2"></i>
                <h4>{{ $reportesPorSucursal->count() }}</h4>
                <p class="mb-0">Sucursales activas</p>
            </div>
        </div>
    </div>
</div>

<!-- Reportes por sucursal -->
@if($reportesPorSucursal->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="h5 mb-3">
                <i class="fas fa-chart-bar me-2"></i>Reportes por Sucursal
            </h3>
        </div>
    </div>
    
    <div class="row">
        @foreach($reportesPorSucursal as $nombreSucursal => $reportesSucursal)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-building me-1"></i>{{ $nombreSucursal }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <strong>Reportes:</strong> {{ $reportesSucursal->count() }}
                        </p>
                        <p class="card-text">
                            <strong>Días trabajados:</strong> 
                            {{ $diasPorSucursal->get($nombreSucursal, collect())->count() }}
                        </p>
                        <p class="card-text">
                            <strong>Usuarios:</strong> 
                            {{ $reportesSucursal->pluck('user_id')->unique()->count() }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<!-- Reportes del día -->
<div class="row">
    <div class="col-12">
        <h3 class="h5 mb-3">
            <i class="fas fa-list me-2"></i>Reportes del {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
        </h3>
    </div>
</div>

@if($reportes->count() > 0)
    <div class="row">
        @foreach($reportes as $reporte)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card">
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
                            <strong>Usuario:</strong> {{ $reporte->user->nombre_completo }}
                        </p>
                        <p class="card-text">
                            <strong>Sucursal:</strong> {{ $reporte->user->nombre_sucursal }}
                        </p>
                        <p class="card-text">
                            <strong>Hora:</strong> {{ $reporte->created_at->format('H:i') }}
                        </p>
                        @if($reporte->imagenes && count($reporte->imagenes) > 0)
                            <p class="card-text">
                                <i class="fas fa-images me-1"></i>
                                {{ count($reporte->imagenes) }} imagen(es)
                            </p>
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
@else
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-3x mb-3"></i>
                <h4>No hay reportes</h4>
                <p class="mb-3">No se encontraron reportes para la fecha seleccionada.</p>
            </div>
        </div>
    </div>
@endif

<!-- Días trabajados -->
@if($diasTrabajados->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <h3 class="h5 mb-3">
                <i class="fas fa-calendar-alt me-2"></i>Días Trabajados
            </h3>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Usuario</th>
                            <th>Sucursal</th>
                            <th>Ponderación</th>
                            <th>Observaciones</th>
                            <th>Hora registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($diasTrabajados as $dia)
                            <tr>
                                <td>{{ $dia->user->nombre_completo }}</td>
                                <td>{{ $dia->user->nombre_sucursal }}</td>
                                <td>
                                    <span class="badge 
                                        @if($dia->ponderacion == 1.0) bg-success
                                        @elseif($dia->ponderacion > 1.0) bg-warning
                                        @else bg-info
                                        @endif
                                    ">
                                        {{ $dia->ponderacion }}x
                                    </span>
                                </td>
                                <td>{{ Str::limit($dia->observaciones, 30) ?: 'Sin observaciones' }}</td>
                                <td>{{ $dia->created_at->format('H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection
