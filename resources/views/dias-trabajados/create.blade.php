@extends('layouts.app')

@section('title', 'Agregar Día Trabajado')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-plus me-2"></i>Agregar Día Trabajado
            </h1>
            <a href="{{ route('dias-trabajados.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('dias-trabajados.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="fecha" class="form-label">
                            <i class="fas fa-calendar me-1"></i>Fecha
                            <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               class="form-control @error('fecha') is-invalid @enderror" 
                               id="fecha" 
                               name="fecha" 
                               value="{{ old('fecha', date('Y-m-d')) }}"
                               required>
                        @error('fecha')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="ponderacion" class="form-label">
                            <i class="fas fa-weight me-1"></i>Ponderación
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control @error('ponderacion') is-invalid @enderror" 
                                id="ponderacion" 
                                name="ponderacion" 
                                required>
                            <option value="">Seleccione una ponderación</option>
                            <option value="0.5" @if(old('ponderacion') == '0.5') selected @endif>0.5x - Medio día</option>
                            <option value="1.0" @if(old('ponderacion') == '1.0') selected @endif>1.0x - Día normal</option>
                            <option value="1.25" @if(old('ponderacion') == '1.25') selected @endif>1.25x - Día y cuarto</option>
                            <option value="1.5" @if(old('ponderacion') == '1.5') selected @endif>1.5x - Día y medio</option>
                            <option value="2.0" @if(old('ponderacion') == '2.0') selected @endif>2.0x - Doble día</option>
                            <option value="2.5" @if(old('ponderacion') == '2.5') selected @endif>2.5x - Día y medio extra</option>
                            <option value="3.0" @if(old('ponderacion') == '3.0') selected @endif>3.0x - Triple día</option>
                        </select>
                        <div class="form-text">
                            La ponderación determina cuánto vale este día en el cálculo de sueldo.
                        </div>
                        @error('ponderacion')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">
                            <i class="fas fa-comment me-1"></i>Observaciones
                            <small class="text-muted">(Opcional)</small>
                        </label>
                        <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                  id="observaciones" 
                                  name="observaciones" 
                                  rows="4" 
                                  placeholder="Agregar comentarios sobre este día trabajado...">{{ old('observaciones') }}</textarea>
                        <div class="form-text">
                            Máximo 500 caracteres.
                        </div>
                        @error('observaciones')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('dias-trabajados.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Guardar Día
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-1"></i>Información
                </h5>
            </div>
            <div class="card-body">
                <h6>Ponderaciones disponibles:</h6>
                <ul class="list-unstyled">
                    <li><span class="badge bg-info me-2">0.5x</span> Medio día</li>
                    <li><span class="badge bg-success me-2">1.0x</span> Día normal</li>
                    <li><span class="badge bg-warning me-2">1.25x</span> Día y cuarto</li>
                    <li><span class="badge bg-warning me-2">1.5x</span> Día y medio</li>
                    <li><span class="badge bg-danger me-2">2.0x</span> Doble día</li>
                    <li><span class="badge bg-danger me-2">2.5x</span> Día y medio extra</li>
                    <li><span class="badge bg-dark me-2">3.0x</span> Triple día</li>
                </ul>
                
                <hr>
                
                <h6>Reglas importantes:</h6>
                <ul class="list-unstyled small">
                    <li><i class="fas fa-check text-success me-1"></i> No puedes registrar dos días para la misma fecha</li>
                    <li><i class="fas fa-check text-success me-1"></i> La ponderación afecta el cálculo de sueldo</li>
                    <li><i class="fas fa-check text-success me-1"></i> Puedes editar o eliminar registros después</li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-1"></i>Resumen del mes
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    <strong>Mes actual:</strong><br>
                    {{ \App\Helpers\DateHelper::yearMonth() }}
                </p>
                <p class="card-text">
                    <strong>Días registrados:</strong><br>
                    {{ auth()->user()->diasTrabajados()->whereRaw("TO_CHAR(fecha, 'YYYY-MM') = ?", [\Carbon\Carbon::now()->format('Y-m')])->count() }}
                </p>
                <p class="card-text">
                    <strong>Total ponderado:</strong><br>
                    {{ auth()->user()->diasTrabajados()->whereRaw("TO_CHAR(fecha, 'YYYY-MM') = ?", [\Carbon\Carbon::now()->format('Y-m')])->sum('ponderacion') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fechaInput = document.getElementById('fecha');
        
        // Mostrar la fecha actual en formato chileno (DD-MM-YYYY)
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();
        
        // El input de tipo date siempre usa formato ISO (YYYY-MM-DD)
        // pero podemos mostrar información adicional al usuario
        console.log('Fecha actual en formato chileno:', `${day}-${month}-${year}`);
        
        // Agregar un pequeño indicador visual de la fecha
        const fechaLabel = document.querySelector('label[for="fecha"]');
        const fechaInfo = document.createElement('small');
        fechaInfo.className = 'text-muted d-block mt-1';
        fechaInfo.innerHTML = `<i class="fas fa-calendar-day me-1"></i>Hoy: ${day}-${month}-${year}`;
        fechaLabel.appendChild(fechaInfo);
        
        // Agregar información sobre el formato
        const formatoInfo = document.createElement('small');
        formatoInfo.className = 'text-info d-block mt-1';
        formatoInfo.innerHTML = `<i class="fas fa-info-circle me-1"></i>El calendario usa formato internacional (DD/MM/YYYY)`;
        fechaLabel.appendChild(formatoInfo);
    });
</script>
@endpush
