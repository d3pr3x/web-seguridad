@extends('layouts.app')

@section('title', 'Ingresos - Control de acceso')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-list me-2"></i>Ingresos</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('ingresos.escaner') }}" class="btn btn-primary">
                <i class="fas fa-qrcode me-1"></i>Escáner
            </a>
            <a href="{{ route('blacklist.index') }}" class="btn btn-outline-secondary">Blacklist</a>
            <form action="{{ route('ingresos.exportar-csv') }}" method="post" class="d-inline">
                @csrf
                <input type="hidden" name="fecha_desde" value="{{ request('fecha_desde') }}">
                <input type="hidden" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                <input type="hidden" name="tipo" value="{{ request('tipo') }}">
                <button type="submit" class="btn btn-outline-success btn-sm">Exportar CSV</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="get" action="{{ route('ingresos.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ request('fecha_desde') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ request('fecha_hasta') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo</label>
                    <select name="tipo" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="peatonal" {{ request('tipo') === 'peatonal' ? 'selected' : '' }}>Peatonal</option>
                        <option value="vehicular" {{ request('tipo') === 'vehicular' ? 'selected' : '' }}>Vehicular</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="ingresado" {{ request('estado') === 'ingresado' ? 'selected' : '' }}>Ingresado</option>
                        <option value="salida" {{ request('estado') === 'salida' ? 'selected' : '' }}>Salida</option>
                        <option value="bloqueado" {{ request('estado') === 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

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

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Fecha ingreso</th>
                    <th>Tipo</th>
                    <th>RUT / Nombre / Patente</th>
                    <th>Guardia</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ingresos as $ingreso)
                <tr>
                    <td>{{ $ingreso->fecha_ingreso->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($ingreso->tipo === 'peatonal')
                            <span class="badge bg-info">Peatonal</span>
                        @else
                            <span class="badge bg-secondary">Vehicular</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $ingreso->rut }}</strong>
                        @if($ingreso->nombre) <br><small>{{ $ingreso->nombre }}</small> @endif
                        @if($ingreso->patente) <br><small>Patente: {{ $ingreso->patente }}</small> @endif
                    </td>
                    <td>{{ $ingreso->guardia->name ?? '-' }}</td>
                    <td>
                        @if($ingreso->estado === 'ingresado')
                            <span class="badge bg-success">Ingresado</span>
                        @elseif($ingreso->estado === 'bloqueado')
                            <span class="badge bg-danger">Bloqueado</span>
                            @if($ingreso->alerta_blacklist)
                                <span class="badge bg-warning text-dark">Blacklist</span>
                            @endif
                        @else
                            <span class="badge bg-secondary">Salida</span>
                        @endif
                    </td>
                    <td>
                        @if($ingreso->estado === 'ingresado')
                            <form action="{{ route('ingresos.salida', $ingreso->id) }}" method="post" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">Salida</button>
                            </form>
                        @endif
                        <a href="{{ route('ingresos.show', $ingreso->id) }}" class="btn btn-sm btn-link">Detalle</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No hay ingresos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $ingresos->links() }}
    </div>
</div>
@endsection
