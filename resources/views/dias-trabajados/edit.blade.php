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
                <i class="fas fa-edit me-2"></i>Editar Día Trabajado
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
                <form action="{{ route('dias-trabajados.update', $diaTrabajado->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="fecha" class="form-label">
                            <i class="fas fa-calendar me-1"></i>Fecha
                            <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               class="form-control @error('fecha') is-invalid @enderror" 
                               id="fecha" 
                               name="fecha" 
                               value="{{ old('fecha', $diaTrabajado->fecha->format('Y-m-d')) }}"
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
                            <option value="0.5" @if(old('ponderacion', $diaTrabajado->ponderacion) == '0.5') selected @endif>0.5x - Medio día</option>
                            <option value="1.0" @if(old('ponderacion', $diaTrabajado->ponderacion) == '1.0') selected @endif>1.0x - Día normal</option>
                            <option value="1.25" @if(old('ponderacion', $diaTrabajado->ponderacion) == '1.25') selected @endif>1.25x - Día y cuarto</option>
                            <option value="1.5" @if(old('ponderacion', $diaTrabajado->ponderacion) == '1.5') selected @endif>1.5x - Día y medio</option>
                            <option value="2.0" @if(old('ponderacion', $diaTrabajado->ponderacion) == '2.0') selected @endif>2.0x - Doble día</option>
                            <option value="2.5" @if(old('ponderacion', $diaTrabajado->ponderacion) == '2.5') selected @endif>2.5x - Día y medio extra</option>
                            <option value="3.0" @if(old('ponderacion', $diaTrabajado->ponderacion) == '3.0') selected @endif>3.0x - Triple día</option>
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
                                  placeholder="Agregar comentarios sobre este día trabajado...">{{ old('observaciones', $diaTrabajado->observaciones) }}</textarea>
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
                            <i class="fas fa-save me-1"></i>Actualizar Día
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
                    <i class="fas fa-info-circle me-1"></i>Información del registro
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    <strong>Fecha original:</strong><br>
                    {{ $diaTrabajado->fecha->format('d/m/Y') }}
                </p>
                <p class="card-text">
                    <strong>Día de la semana:</strong><br>
                    <span class="badge bg-secondary">
                        {{ $diaTrabajado->fecha->locale('es')->dayName }}
                    </span>
                </p>
                <p class="card-text">
                    <strong>Ponderación actual:</strong><br>
                    <span class="badge 
                        @if($diaTrabajado->ponderacion == 1.0) bg-success
                        @elseif($diaTrabajado->ponderacion > 1.0) bg-warning
                        @else bg-info
                        @endif
                    ">
                        {{ $diaTrabajado->ponderacion }}x
                    </span>
                </p>
                <p class="card-text">
                    <strong>Creado:</strong><br>
                    {{ $diaTrabajado->created_at->format('d/m/Y H:i') }}
                </p>
                <p class="card-text">
                    <strong>Última actualización:</strong><br>
                    {{ $diaTrabajado->updated_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i>Advertencias
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning p-2">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Importante:</strong> No puedes cambiar la fecha a una que ya tenga un registro.
                    </small>
                </div>
                <div class="alert alert-info p-2">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        Los cambios se reflejarán inmediatamente en el cálculo de sueldo.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
@endsection


