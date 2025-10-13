@extends('layouts.app')

@section('title', 'Días Trabajados')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-calendar me-2"></i>Días Trabajados
            </h1>
            <div>
                <a href="{{ route('dias-trabajados.create') }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-1"></i>Agregar Día
                </a>
                <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Resumen del mes -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-calendar-check fa-2x mb-2"></i>
                <h4>{{ $totalDias }}</h4>
                <p class="mb-0">Días trabajados ({{ $mesActual }})</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-list fa-2x mb-2"></i>
                <h4>{{ $diasTrabajados->count() }}</h4>
                <p class="mb-0">Registros del mes</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-2x mb-2"></i>
                <h4>{{ $diasTrabajados->count() > 0 ? number_format($totalDias / $diasTrabajados->count(), 2) : 0 }}</h4>
                <p class="mb-0">Promedio por día</p>
            </div>
        </div>
    </div>
</div>

@if($diasTrabajados->count() > 0)
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-1"></i>Registros del mes de {{ \Carbon\Carbon::parse($mesActual . '-01')->format('F Y') }}
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Día de la semana</th>
                            <th>Ponderación</th>
                            <th>Observaciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($diasTrabajados as $dia)
                            <tr>
                                <td>
                                    <strong>{{ $dia->fecha->format('d/m/Y') }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $dia->fecha->locale('es')->dayName }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($dia->ponderacion == 1.0) bg-success
                                        @elseif($dia->ponderacion > 1.0) bg-warning
                                        @else bg-info
                                        @endif
                                    ">
                                        {{ $dia->ponderacion }}x
                                    </span>
                                    @if($dia->ponderacion > 1.0)
                                        <small class="text-muted d-block">Día extra</small>
                                    @elseif($dia->ponderacion < 1.0)
                                        <small class="text-muted d-block">Día parcial</small>
                                    @endif
                                </td>
                                <td>
                                    @if($dia->observaciones)
                                        <span class="text-muted">{{ Str::limit($dia->observaciones, 50) }}</span>
                                    @else
                                        <span class="text-muted">Sin observaciones</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('dias-trabajados.edit', $dia->id) }}" 
                                           class="btn btn-outline-primary" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                title="Eliminar"
                                                onclick="confirmarEliminacion({{ $dia->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-3x mb-3"></i>
                <h4>No hay registros</h4>
                <p class="mb-3">No tienes días trabajados registrados para este mes.</p>
                <a href="{{ route('dias-trabajados.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Agregar primer día
                </a>
            </div>
        </div>
    </div>
@endif

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este día trabajado? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmarEliminacion(id) {
        const form = document.getElementById('deleteForm');
        form.action = `/dias-trabajados/${id}`;
        
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        modal.show();
    }
</script>
@endpush


