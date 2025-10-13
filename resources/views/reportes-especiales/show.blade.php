@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Detalle de Reporte Especial</h4>
                    <a href="{{ route('reportes-especiales.index') }}" class="btn btn-sm btn-secondary">Volver</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tipo de Reporte:</strong>
                            <p class="mb-0">
                                <span class="badge bg-danger fs-6">{{ $reporteEspecial->nombre_tipo }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Estado:</strong>
                            <p class="mb-0">
                                @php
                                    $badgeClass = match($reporteEspecial->estado) {
                                        'pendiente' => 'bg-warning',
                                        'en_revision' => 'bg-info',
                                        'completado' => 'bg-success',
                                        'rechazado' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} fs-6">
                                    {{ ucfirst(str_replace('_', ' ', $reporteEspecial->estado)) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Fecha:</strong>
                            <p>{{ $reporteEspecial->dia->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Hora:</strong>
                            <p>{{ \Carbon\Carbon::parse($reporteEspecial->hora)->format('H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Usuario:</strong>
                            <p>{{ $reporteEspecial->user->name }} ({{ $reporteEspecial->user->rut }})</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Sucursal:</strong>
                            <p>{{ $reporteEspecial->sucursal->nombre }}</p>
                        </div>
                    </div>

                    @if($reporteEspecial->sector)
                        <div class="mb-3">
                            <strong>Sector:</strong>
                            <p>{{ $reporteEspecial->sector->nombre }}</p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>Novedad:</strong>
                        <div class="border p-3 bg-light">{{ $reporteEspecial->novedad }}</div>
                    </div>

                    <div class="mb-3">
                        <strong>Acción Tomada:</strong>
                        <div class="border p-3 bg-light">{{ $reporteEspecial->accion }}</div>
                    </div>

                    @if($reporteEspecial->resultado)
                        <div class="mb-3">
                            <strong>Resultado:</strong>
                            <div class="border p-3 bg-light">{{ $reporteEspecial->resultado }}</div>
                        </div>
                    @endif

                    @if($reporteEspecial->imagenes && count($reporteEspecial->imagenes) > 0)
                        <div class="mb-3">
                            <strong>Imágenes:</strong>
                            <div class="row mt-2">
                                @foreach($reporteEspecial->imagenes as $imagen)
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

                    @if($reporteEspecial->latitud && $reporteEspecial->longitud)
                        <div class="mb-3">
                            <strong>Ubicación:</strong>
                            <p>
                                Lat: {{ $reporteEspecial->latitud }}, Long: {{ $reporteEspecial->longitud }}
                                @if($reporteEspecial->precision)
                                    (Precisión: {{ round($reporteEspecial->precision) }}m)
                                @endif
                            </p>
                            <a href="https://www.google.com/maps?q={{ $reporteEspecial->latitud }},{{ $reporteEspecial->longitud }}" 
                               target="_blank" 
                               class="btn btn-sm btn-info">
                                Ver en Google Maps
                            </a>
                        </div>
                    @endif

                    @if($reporteEspecial->comentarios_admin)
                        <div class="mb-3">
                            <strong>Comentarios del Supervisor/Admin:</strong>
                            <div class="border p-3 bg-warning bg-opacity-10">{{ $reporteEspecial->comentarios_admin }}</div>
                        </div>
                    @endif

                    <hr>

                    <!-- Formulario para actualizar estado (solo admin/supervisor) -->
                    @if(in_array(auth()->user()->rol, ['admin', 'supervisor']))
                        <div class="card bg-light mt-4">
                            <div class="card-body">
                                <h5>Actualizar Estado del Reporte</h5>
                                <form method="POST" action="{{ route('reportes-especiales.update-estado', $reporteEspecial) }}">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div class="mb-3">
                                        <label for="estado" class="form-label">Estado</label>
                                        <select name="estado" id="estado" class="form-select" required>
                                            <option value="pendiente" {{ $reporteEspecial->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="en_revision" {{ $reporteEspecial->estado == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                                            <option value="completado" {{ $reporteEspecial->estado == 'completado' ? 'selected' : '' }}>Completado</option>
                                            <option value="rechazado" {{ $reporteEspecial->estado == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="comentarios_admin" class="form-label">Comentarios</label>
                                        <textarea name="comentarios_admin" id="comentarios_admin" class="form-control" rows="3">{{ $reporteEspecial->comentarios_admin }}</textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Actualizar Estado</button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <hr>

                    <div class="text-muted small">
                        <p class="mb-1">Registrado: {{ $reporteEspecial->created_at->format('d/m/Y H:i:s') }}</p>
                        @if($reporteEspecial->updated_at != $reporteEspecial->created_at)
                            <p class="mb-0">Actualizado: {{ $reporteEspecial->updated_at->format('d/m/Y H:i:s') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




