@extends('layouts.app')

@section('title', 'Blacklist - Control de acceso')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-ban me-2"></i>Blacklist</h1>
        <a href="{{ route('ingresos.index') }}" class="btn btn-outline-primary btn-sm">Volver a ingresos</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong>Agregar a blacklist</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('blacklist.store') }}" method="post">
                @csrf
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">RUT <span class="text-danger">*</span></label>
                        <input type="text" name="rut" class="form-control form-control-sm rut-input" placeholder="12.345.678-9" required maxlength="12">
                        @error('rut')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Patente (opcional)</label>
                        <input type="text" name="patente" class="form-control form-control-sm text-uppercase" placeholder="ABCD12" maxlength="7">
                        @error('patente')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Motivo <span class="text-danger">*</span></label>
                        <input type="text" name="motivo" class="form-control form-control-sm" placeholder="Motivo" required maxlength="500" value="{{ old('motivo') }}">
                        @error('motivo')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Fecha inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control form-control-sm" value="{{ old('fecha_inicio', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Fecha fin (opcional)</label>
                        <input type="date" name="fecha_fin" class="form-control form-control-sm" value="{{ old('fecha_fin') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-danger btn-sm">Agregar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>RUT</th>
                    <th>Patente</th>
                    <th>Motivo</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Activo</th>
                    <th>Creado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blacklists as $b)
                <tr class="{{ $b->trashed() ? 'table-secondary' : '' }}">
                    <td>{{ $b->rut }}</td>
                    <td>{{ $b->patente ?? '-' }}</td>
                    <td><small>{{ Str::limit($b->motivo, 40) }}</small></td>
                    <td>{{ $b->fecha_inicio->format('d/m/Y') }}</td>
                    <td>{{ $b->fecha_fin ? $b->fecha_fin->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if($b->activo)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </td>
                    <td>{{ $b->creador->name ?? '-' }}</td>
                    <td>
                        @if(!$b->trashed())
                            <form action="{{ route('blacklist.toggle', $b->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-warning">{{ $b->activo ? 'Desactivar' : 'Activar' }}</button>
                            </form>
                            <form action="{{ route('blacklist.destroy', $b->id) }}" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar de la blacklist?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </form>
                        @else
                            <form action="{{ route('blacklist.toggle', $b->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-success">Restaurar</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No hay entradas en la blacklist.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $blacklists->links() }}
    </div>
</div>
@endsection
