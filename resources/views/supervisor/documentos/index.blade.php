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
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Documentos Personales</h1>
                    <p class="text-muted">Aprobar o rechazar documentos de los usuarios</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Pendientes</p>
                            <h3 class="mb-0 text-warning">{{ $estadisticas['pendientes'] }}</h3>
                        </div>
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Aprobados</p>
                            <h3 class="mb-0 text-success">{{ $estadisticas['aprobados'] }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Rechazados</p>
                            <h3 class="mb-0 text-danger">{{ $estadisticas['rechazados'] }}</h3>
                        </div>
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total</p>
                            <h3 class="mb-0 text-primary">{{ $estadisticas['total'] }}</h3>
                        </div>
                        <i class="fas fa-file-alt fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('supervisor.documentos.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                        <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobados</option>
                        <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazados</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tipo de Documento</label>
                    <select name="tipo_documento" class="form-select">
                        <option value="">Todos</option>
                        <option value="cedula_identidad" {{ request('tipo_documento') == 'cedula_identidad' ? 'selected' : '' }}>Cédula de Identidad</option>
                        <option value="licencia_conductor" {{ request('tipo_documento') == 'licencia_conductor' ? 'selected' : '' }}>Licencia de Conductor</option>
                        <option value="certificado_antecedentes" {{ request('tipo_documento') == 'certificado_antecedentes' ? 'selected' : '' }}>Cert. Antecedentes</option>
                        <option value="certificado_os10" {{ request('tipo_documento') == 'certificado_os10' ? 'selected' : '' }}>Cert. O.S. 10</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Usuario</label>
                    <select name="user_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->nombre_completo }} ({{ $u->run }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-search me-1"></i> Filtrar
                    </button>
                    @if(request()->hasAny(['estado', 'tipo_documento', 'user_id']))
                        <a href="{{ route('supervisor.documentos.index') }}" class="btn btn-secondary">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Documentos -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-file-alt me-2"></i>
                Lista de Documentos
            </h5>
        </div>
        <div class="card-body">
            @if($documentos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>RUT</th>
                                <th>Tipo Documento</th>
                                <th>Fecha Envío</th>
                                <th>Estado</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documentos as $doc)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $doc->user->nombre_completo }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $doc->user->nombre_sucursal }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $doc->user->run }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $doc->nombre_tipo }}</span>
                                </td>
                                <td>{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($doc->estado == 'pendiente')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i> Pendiente
                                        </span>
                                    @elseif($doc->estado == 'aprobado')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i> Aprobado
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i> Rechazado
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($doc->es_cambio)
                                        <span class="badge bg-info">
                                            <i class="fas fa-sync-alt me-1"></i> Cambio
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark">Nuevo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('supervisor.documentos.show', $doc->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-3">
                    {{ $documentos->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay documentos que mostrar</p>
                </div>
            @endif
        </div>
    </div>
</div>
        </div>
    </div>
</div>
@endsection


