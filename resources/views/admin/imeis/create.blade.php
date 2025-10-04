@extends('layouts.app')

@section('title', 'Agregar IMEI Permitido')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus me-2"></i>Agregar IMEI Permitido
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.imeis.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="imei" class="form-label">IMEI <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('imei') is-invalid @enderror" 
                                   id="imei" 
                                   name="imei" 
                                   value="{{ old('imei') }}"
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

                        <div class="mb-4">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" 
                                   class="form-control @error('descripcion') is-invalid @enderror" 
                                   id="descripcion" 
                                   name="descripcion" 
                                   value="{{ old('descripcion') }}"
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

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.imeis.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar IMEI
                            </button>
                        </div>
                    </form>
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
