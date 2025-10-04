@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                    <h2 class="card-title">Sistema de Seguridad</h2>
                    <p class="text-muted">Inicia sesión con tu RUT</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="rut" class="form-label">RUT</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-id-card"></i>
                            </span>
                            <input type="text" 
                                   class="form-control @error('rut') is-invalid @enderror" 
                                   id="rut" 
                                   name="rut" 
                                   value="{{ old('rut') }}" 
                                   placeholder="12.345.678-9"
                                   required 
                                   autofocus>
                        </div>
                        @error('rut')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="imei" class="form-label">IMEI del Dispositivo</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-mobile-alt"></i>
                            </span>
                            <input type="text" 
                                   class="form-control @error('imei') is-invalid @enderror" 
                                   id="imei" 
                                   name="imei" 
                                   value="{{ old('imei') }}"
                                   placeholder="123456789012345"
                                   maxlength="15"
                                   pattern="[0-9]{15}"
                                   required>
                        </div>
                        @error('imei')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="form-text text-muted">
                            Ingrese el IMEI de 15 dígitos de su dispositivo móvil
                        </small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // El formateo de RUT se maneja automáticamente con rut-formatter.js
    // Solo agregar validación adicional si es necesario
    document.addEventListener('DOMContentLoaded', function() {
        const rutInput = document.getElementById('rut');
        if (rutInput) {
            // Agregar clase para el formateador automático
            rutInput.classList.add('rut-input');
        }

        // Validación del IMEI
        const imeiInput = document.getElementById('imei');
        if (imeiInput) {
            imeiInput.addEventListener('input', function() {
                // Solo permitir números
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Limitar a 15 dígitos
                if (this.value.length > 15) {
                    this.value = this.value.substring(0, 15);
                }
            });

            imeiInput.addEventListener('paste', function(e) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const numbersOnly = paste.replace(/[^0-9]/g, '').substring(0, 15);
                this.value = numbersOnly;
            });
        }
    });
</script>
@endpush
