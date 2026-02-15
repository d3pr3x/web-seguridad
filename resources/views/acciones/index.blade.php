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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Acciones del Servicio</h2>
        <div>
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                    Nueva Acción
                </button>
                <ul class="dropdown-menu">
                    @foreach(\App\Models\Accion::tipos() as $key => $nombre)
                        <li>
                            <a class="dropdown-item" href="{{ route('acciones.create', ['tipo' => $key]) }}">
                                {{ $nombre }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('acciones.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="tipo" class="form-label">Tipo de Acción</label>
                    <select name="tipo" id="tipo" class="form-select">
                        <option value="">Todas</option>
                        @foreach(\App\Models\Accion::tipos() as $key => $nombre)
                            <option value="{{ $key }}" {{ request('tipo') == $key ? 'selected' : '' }}>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ request('fecha') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                    <a href="{{ route('acciones.index') }}" class="btn btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de acciones -->
    @if($acciones->count() > 0)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Tipo</th>
                                <th>Sector</th>
                                <th>Usuario</th>
                                <th>Sucursal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($acciones as $accion)
                                <tr>
                                    <td>{{ $accion->dia->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($accion->hora)->format('H:i') }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $accion->nombre_tipo }}
                                        </span>
                                    </td>
                                    <td>{{ $accion->sector?->nombre ?? 'N/A' }}</td>
                                    <td>{{ $accion->user->nombre_completo }}</td>
                                    <td>{{ $accion->sucursal->nombre }}</td>
                                    <td>
                                        <a href="{{ route('acciones.show', $accion) }}" class="btn btn-sm btn-info">
                                            Ver Detalle
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $acciones->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            No se encontraron acciones registradas.
        </div>
    @endif
</div>
        </div>
    </div>
</div>
@endsection




