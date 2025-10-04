@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-user me-2"></i>Mi Perfil
            </h1>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-id-card me-1"></i>Información Personal
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i>Nombre
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', auth()->user()->name) }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apellido" class="form-label">
                                    <i class="fas fa-user me-1"></i>Apellido
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('apellido') is-invalid @enderror" 
                                       id="apellido" 
                                       name="apellido" 
                                       value="{{ old('apellido', auth()->user()->apellido) }}"
                                       required>
                                @error('apellido')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rut" class="form-label">
                                    <i class="fas fa-id-card me-1"></i>RUT
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control rut-input @error('rut') is-invalid @enderror" 
                                       id="rut" 
                                       name="rut" 
                                       value="{{ old('rut', auth()->user()->rut) }}"
                                       required
                                       readonly>
                                <div class="form-text">
                                    El RUT no se puede modificar por seguridad.
                                </div>
                                @error('rut')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_nacimiento" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>Fecha de Nacimiento
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                                       id="fecha_nacimiento" 
                                       name="fecha_nacimiento" 
                                       value="{{ old('fecha_nacimiento', auth()->user()->fecha_nacimiento?->format('Y-m-d')) }}"
                                       required>
                                @error('fecha_nacimiento')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="domicilio" class="form-label">
                            <i class="fas fa-home me-1"></i>Domicilio
                            <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('domicilio') is-invalid @enderror" 
                                  id="domicilio" 
                                  name="domicilio" 
                                  rows="3"
                                  required>{{ old('domicilio', auth()->user()->domicilio) }}</textarea>
                        @error('domicilio')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="sucursal_id" class="form-label">
                            <i class="fas fa-building me-1"></i>Sucursal
                        </label>
                        <select class="form-control @error('sucursal_id') is-invalid @enderror" 
                                id="sucursal_id" 
                                name="sucursal_id">
                            <option value="">Seleccione una sucursal</option>
                            @foreach(\App\Models\Sucursal::activas()->get() as $sucursal)
                                <option value="{{ $sucursal->id }}" 
                                        @if(old('sucursal_id', auth()->user()->sucursal_id) == $sucursal->id) selected @endif>
                                    {{ $sucursal->nombre }} - {{ $sucursal->ciudad }}
                                </option>
                            @endforeach
                        </select>
                        @error('sucursal_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Información de la cuenta -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-1"></i>Información de la Cuenta
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    <strong>Usuario desde:</strong><br>
                    {{ auth()->user()->created_at->format('d/m/Y') }}
                </p>
                <p class="card-text">
                    <strong>Última actualización:</strong><br>
                    {{ auth()->user()->updated_at->format('d/m/Y H:i') }}
                </p>
                <p class="card-text">
                    <strong>Estado:</strong><br>
                    <span class="badge bg-success">Activo</span>
                </p>
            </div>
        </div>
        
        <!-- Estadísticas del usuario -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-1"></i>Estadísticas
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    <strong>Reportes enviados:</strong><br>
                    {{ auth()->user()->reportes()->count() }}
                </p>
                <p class="card-text">
                    <strong>Días trabajados:</strong><br>
                    {{ auth()->user()->diasTrabajados()->count() }}
                </p>
                <p class="card-text">
                    <strong>Total ponderado:</strong><br>
                    {{ auth()->user()->diasTrabajados()->sum('ponderacion') }}
                </p>
            </div>
        </div>
        
        <!-- Cambiar contraseña -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-lock me-1"></i>Seguridad
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    <strong>Contraseña:</strong><br>
                    Última actualización: {{ auth()->user()->updated_at->format('d/m/Y') }}
                </p>
                <a href="{{ route('profile.password') }}" class="btn btn-outline-warning btn-sm">
                    <i class="fas fa-key me-1"></i>Cambiar Contraseña
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
