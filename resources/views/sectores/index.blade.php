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
        <h2>Gestión de Sectores</h2>
        <a href="{{ route('sectores.create') }}" class="btn btn-primary">
            Crear Nuevo Sector
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($sectores->count() > 0)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Sucursal</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sectores as $sector)
                                <tr>
                                    <td>{{ $sector->id }}</td>
                                    <td><strong>{{ $sector->nombre }}</strong></td>
                                    <td>{{ $sector->sucursal->nombre }}</td>
                                    <td>{{ $sector->descripcion ? Str::limit($sector->descripcion, 50) : '-' }}</td>
                                    <td>
                                        <span class="badge {{ $sector->activo ? 'bg-success' : 'bg-danger' }}">
                                            {{ $sector->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('sectores.edit', $sector) }}" 
                                               class="btn btn-sm btn-warning">
                                                Editar
                                            </a>
                                            <form action="{{ route('sectores.destroy', $sector) }}" 
                                                  method="POST" 
                                                  style="display: inline;"
                                                  onsubmit="return confirm('¿Está seguro de eliminar este sector?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $sectores->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            No hay sectores registrados. 
            <a href="{{ route('sectores.create') }}" class="alert-link">Crear el primero</a>
        </div>
    @endif
</div>
        </div>
    </div>
</div>
@endsection




