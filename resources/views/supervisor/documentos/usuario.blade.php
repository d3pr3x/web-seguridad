@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Documentos de {{ $user->nombre_completo }}</h1>
                    <p class="text-muted">RUT: {{ $user->rut }} | Sucursal: {{ $user->nombre_sucursal }}</p>
                </div>
                <a href="{{ route('supervisor.documentos.usuarios') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Resumen de Documentos -->
    <div class="row mb-4">
        @foreach($tiposDocumentos as $tipo => $nombre)
            @php
                $doc = $documentos->where('tipo_documento', $tipo)->where('estado', 'aprobado')->first();
                $pendiente = $documentos->where('tipo_documento', $tipo)->where('estado', 'pendiente')->first();
            @endphp
            <div class="col-md-6 mb-3">
                <div class="card {{ $doc ? 'border-success' : ($pendiente ? 'border-warning' : 'border-secondary') }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-2">{{ $nombre }}</h6>
                                @if($doc)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i> Aprobado
                                    </span>
                                    <p class="text-muted small mb-0 mt-2">
                                        Aprobado el {{ $doc->aprobado_en->format('d/m/Y') }}
                                    </p>
                                @elseif($pendiente)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i> Pendiente
                                    </span>
                                    <p class="text-muted small mb-0 mt-2">
                                        Enviado el {{ $pendiente->created_at->format('d/m/Y') }}
                                    </p>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-times me-1"></i> Sin cargar
                                    </span>
                                @endif
                            </div>
                            @if($doc)
                                <a href="{{ route('supervisor.documentos.show', $doc->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                            @elseif($pendiente)
                                <a href="{{ route('supervisor.documentos.show', $pendiente->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-eye"></i> Revisar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Historial Completo -->
    @if($documentos->count() > 0)
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">
                <i class="fas fa-history me-2"></i>
                Historial de Documentos
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tipo Documento</th>
                            <th>Fecha Envío</th>
                            <th>Estado</th>
                            <th>Tipo Solicitud</th>
                            <th>Revisado Por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documentos as $doc)
                        <tr>
                            <td>{{ $doc->nombre_tipo }}</td>
                            <td>{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($doc->estado == 'pendiente')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                @elseif($doc->estado == 'aprobado')
                                    <span class="badge bg-success">Aprobado</span>
                                @else
                                    <span class="badge bg-danger">Rechazado</span>
                                @endif
                            </td>
                            <td>
                                @if($doc->es_cambio)
                                    <span class="badge bg-info">Cambio</span>
                                @else
                                    <span class="badge bg-light text-dark">Nuevo</span>
                                @endif
                            </td>
                            <td>
                                @if($doc->aprobador)
                                    {{ $doc->aprobador->nombre_completo }}
                                    <br>
                                    <small class="text-muted">{{ $doc->aprobado_en->format('d/m/Y') }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('supervisor.documentos.show', $doc->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection


