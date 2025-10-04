@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </h1>
            <div class="text-muted">
                Bienvenido, <strong>{{ auth()->user()->nombre_completo }}</strong>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="fas fa-calendar-check fa-2x mb-2"></i>
                <h4>{{ $totalDias }}</h4>
                <p class="mb-0">Días trabajados este mes</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-tasks fa-2x mb-2"></i>
                <h4>{{ $tareas->count() }}</h4>
                <p class="mb-0">Tareas disponibles</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-building fa-2x mb-2"></i>
                <h4>{{ auth()->user()->nombre_sucursal }}</h4>
                <p class="mb-0">Sucursal</p>
            </div>
        </div>
    </div>
</div>

<!-- Tareas -->
<div class="row">
    <div class="col-12">
        <h2 class="h4 mb-3">
            <i class="fas fa-list me-2"></i>Tareas Disponibles
        </h2>
    </div>
</div>

<div class="row">
    @forelse($tareas as $tarea)
        <div class="col-6 col-md-4 col-lg-3 mb-3">
            <a href="{{ route('tareas.show', $tarea->id) }}" class="btn-task d-block" style="background-color: {{ $tarea->color }}">
                @if($tarea->icono)
                    <i class="{{ $tarea->icono }}"></i>
                @else
                    <i class="fas fa-tasks"></i>
                @endif
                <h5 class="mb-1">{{ $tarea->nombre }}</h5>
                @if($tarea->descripcion)
                    <small class="opacity-75 d-none d-md-block">{{ Str::limit($tarea->descripcion, 30) }}</small>
                @endif
            </a>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                No hay tareas disponibles en este momento.
            </div>
        </div>
    @endforelse
</div>

<!-- Acciones rápidas -->
<div class="row mt-4">
    <div class="col-12">
        <h2 class="h4 mb-3">
            <i class="fas fa-bolt me-2"></i>Acciones Rápidas
        </h2>
    </div>
</div>

<div class="row">
    <div class="col-6 col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body text-center p-2">
                <i class="fas fa-plus-circle fa-2x text-primary mb-2"></i>
                <h6 class="card-title mb-1">Registrar Día</h6>
                <p class="card-text text-muted small mb-2">Agregar día trabajado</p>
                <a href="{{ route('dias-trabajados.create') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Agregar
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body text-center p-2">
                <i class="fas fa-calendar-alt fa-2x text-success mb-2"></i>
                <h6 class="card-title mb-1">Ver Días</h6>
                <p class="card-text text-muted small mb-2">Consultar días</p>
                <a href="{{ route('dias-trabajados.index') }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-eye me-1"></i>Ver
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body text-center p-2">
                <i class="fas fa-file-alt fa-2x text-info mb-2"></i>
                <h6 class="card-title mb-1">Mis Reportes</h6>
                <p class="card-text text-muted small mb-2">Ver reportes</p>
                <a href="{{ route('reportes.index') }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-list me-1"></i>Ver
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body text-center p-2">
                <i class="fas fa-user fa-2x text-warning mb-2"></i>
                <h6 class="card-title mb-1">Mi Perfil</h6>
                <p class="card-text text-muted small mb-2">Información</p>
                <a href="{{ route('profile.index') }}" class="btn btn-outline-warning btn-sm">
                    <i class="fas fa-user me-1"></i>Ver Perfil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
