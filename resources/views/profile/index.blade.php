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
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-user me-2"></i>Mi Perfil
            </h1>
            <a href="{{ url('/') }}" class="btn btn-outline-secondary">
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
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="nombre_completo" class="form-label">
                                    <i class="fas fa-user me-1"></i>Nombre completo
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nombre_completo') is-invalid @enderror" 
                                       id="nombre_completo" 
                                       name="nombre_completo" 
                                       value="{{ old('nombre_completo', auth()->user()->nombre_completo) }}"
                                       required>
                                @error('nombre_completo')
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
                                <label for="run" class="form-label">
                                    <i class="fas fa-id-card me-1"></i>RUN
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control rut-input @error('run') is-invalid @enderror" 
                                       id="run" 
                                       name="run" 
                                       value="{{ old('run', auth()->user()->run) }}"
                                       required
                                       readonly>
                                <div class="form-text">
                                    El RUN no se puede modificar por seguridad.
                                </div>
                                @error('run')
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
                        <label class="form-label">
                            <i class="fas fa-building me-1"></i>Sucursal
                        </label>
                        <p class="form-control-plaintext border rounded px-3 py-2 bg-light mb-0">
                            {{ auth()->user()->sucursal?->nombre ?? '—' }}@if(auth()->user()->sucursal?->ciudad) — {{ auth()->user()->sucursal->ciudad }}@endif
                        </p>
                        <div class="form-text">La sucursal solo puede ser modificada por un administrador.</div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ url('/') }}" class="btn btn-secondary me-md-2">
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
        </div>
    </div>
</div>
@endsection


