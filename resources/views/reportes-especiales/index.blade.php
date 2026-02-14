@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:ml-64">
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
        <h2>Reportes Especiales</h2>
        <div>
            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown">
                    Nuevo Reporte
                </button>
                <ul class="dropdown-menu">
                    @foreach(\App\Models\ReporteEspecial::tipos() as $key => $nombre)
                        <li>
                            <a class="dropdown-item" href="{{ route('reportes-especiales.create', ['tipo' => $key]) }}">
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
            <form method="GET" action="{{ route('reportes-especiales.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="tipo" class="form-label">Tipo de Reporte</label>
                    <select name="tipo" id="tipo" class="form-select">
                        <option value="">Todos</option>
                        @foreach(\App\Models\ReporteEspecial::tipos() as $key => $nombre)
                            <option value="{{ $key }}" {{ request('tipo') == $key ? 'selected' : '' }}>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="en_revision" {{ request('estado') == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                        <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                        <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ request('fecha') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                    <a href="{{ route('reportes-especiales.index') }}" class="btn btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de reportes -->
    @if($reportes->count() > 0)
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
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportes as $reporte)
                                <tr>
                                    <td>{{ $reporte->dia->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reporte->hora)->format('H:i') }}</td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ $reporte->nombre_tipo }}
                                        </span>
                                    </td>
                                    <td>{{ $reporte->sector?->nombre ?? 'N/A' }}</td>
                                    <td>{{ $reporte->user->nombre_completo }}</td>
                                    <td>{{ $reporte->sucursal->nombre }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($reporte->estado) {
                                                'pendiente' => 'bg-warning',
                                                'en_revision' => 'bg-info',
                                                'completado' => 'bg-success',
                                                'rechazado' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $reporte->estado)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('reportes-especiales.show', $reporte) }}" class="btn btn-sm btn-info">
                                            Ver Detalle
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $reportes->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            No se encontraron reportes especiales.
        </div>
    @endif
</div>
        </div>
    </div>
</div>
@endsection




