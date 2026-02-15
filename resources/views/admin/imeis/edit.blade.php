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
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>Editar IMEI Permitido
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.imeis.update', $imei) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="imei" class="form-label">IMEI <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('imei') is-invalid @enderror" 
                                   id="imei" 
                                   name="imei" 
                                   value="{{ old('imei', $imei->imei) }}"
                                   placeholder="123456789012345"
                                   maxlength="15"
                                   pattern="[0-9]{15}"
                                   required>
                            @error('imei')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted">
                                Ingrese el IMEI de 15 dígitos del dispositivo
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" 
                                   class="form-control @error('descripcion') is-invalid @enderror" 
                                   id="descripcion" 
                                   name="descripcion" 
                                   value="{{ old('descripcion', $imei->descripcion) }}"
                                   placeholder="Ej: Teléfono de Juan Pérez">
                            @error('descripcion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted">
                                Descripción opcional para identificar el dispositivo
                            </small>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="activo" 
                                       name="activo" 
                                       value="1"
                                       {{ old('activo', $imei->activo) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">
                                    IMEI Activo
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Los IMEIs inactivos no podrán acceder a la aplicación
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.imeis.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Actualizar IMEI
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imeiInput = document.getElementById('imei');
        
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
    });
</script>
@endpush
