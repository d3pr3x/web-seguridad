@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-key me-2"></i>Cambiar Contraseña
            </h1>
            <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Volver al Perfil
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-lock me-1"></i>Nueva Contraseña
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">
                            <i class="fas fa-lock me-1"></i>Contraseña Actual
                            <span class="text-danger">*</span>
                        </label>
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password" 
                               required
                               autofocus>
                        @error('current_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-key me-1"></i>Nueva Contraseña
                            <span class="text-danger">*</span>
                        </label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required>
                        <div class="form-text">
                            Mínimo 8 caracteres.
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-key me-1"></i>Confirmar Nueva Contraseña
                            <span class="text-danger">*</span>
                        </label>
                        <input type="password" 
                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('profile.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Cambiar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Información de seguridad -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-shield-alt me-1"></i>Recomendaciones de Seguridad
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li><i class="fas fa-check text-success me-2"></i>Usa al menos 8 caracteres</li>
                    <li><i class="fas fa-check text-success me-2"></i>Combina letras mayúsculas y minúsculas</li>
                    <li><i class="fas fa-check text-success me-2"></i>Incluye números y símbolos</li>
                    <li><i class="fas fa-check text-success me-2"></i>No uses información personal</li>
                    <li><i class="fas fa-check text-success me-2"></i>Evita contraseñas comunes</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
