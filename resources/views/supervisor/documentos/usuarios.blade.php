@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Documentos por Usuario</h1>
                    <p class="text-muted">Vista general de documentos de todos los usuarios</p>
                </div>
                <a href="{{ route('supervisor.documentos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Volver a Lista
                </a>
            </div>
        </div>
    </div>

    <!-- Lista de Usuarios -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-users me-2"></i>
                Usuarios y sus Documentos
            </h5>
        </div>
        <div class="card-body">
            @if($usuarios->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>RUT</th>
                                <th>Sucursal</th>
                                <th class="text-center">Cédula</th>
                                <th class="text-center">Licencia</th>
                                <th class="text-center">Antecedentes</th>
                                <th class="text-center">O.S. 10</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                            @php
                                $docs = $usuario->documentosPersonales->keyBy('tipo_documento');
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <strong>{{ $usuario->nombre_completo }}</strong>
                                    </div>
                                </td>
                                <td>{{ $usuario->rut }}</td>
                                <td>{{ $usuario->nombre_sucursal }}</td>
                                <td class="text-center">
                                    @if($docs->has('cedula_identidad'))
                                        <i class="fas fa-check-circle fa-lg text-success"></i>
                                    @else
                                        <i class="fas fa-times-circle fa-lg text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($docs->has('licencia_conductor'))
                                        <i class="fas fa-check-circle fa-lg text-success"></i>
                                    @else
                                        <i class="fas fa-times-circle fa-lg text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($docs->has('certificado_antecedentes'))
                                        <i class="fas fa-check-circle fa-lg text-success"></i>
                                    @else
                                        <i class="fas fa-times-circle fa-lg text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($docs->has('certificado_os10'))
                                        <i class="fas fa-check-circle fa-lg text-success"></i>
                                    @else
                                        <i class="fas fa-times-circle fa-lg text-danger"></i>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('supervisor.documentos.usuario', $usuario->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-folder-open me-1"></i> Ver Documentos
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay usuarios registrados</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


