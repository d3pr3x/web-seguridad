@extends('layouts.app')

@section('title', $tarea->nombre)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                @if($tarea->icono)
                    <i class="{{ $tarea->icono }} me-2"></i>
                @else
                    <i class="fas fa-tasks me-2"></i>
                @endif
                {{ $tarea->nombre }}
            </h1>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>
</div>

@if($tarea->descripcion)
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            {{ $tarea->descripcion }}
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('reportes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tarea_id" value="{{ $tarea->id }}">
                    
                    @foreach($tarea->detalles as $detalle)
                        <div class="mb-3">
                            <label for="datos_{{ $detalle->id }}" class="form-label">
                                {{ $detalle->campo_nombre }}
                                @if($detalle->requerido)
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            
                            @switch($detalle->tipo_campo)
                                @case('text')
                                    <input type="text" 
                                           class="form-control @error('datos.'.$detalle->id) is-invalid @enderror" 
                                           id="datos_{{ $detalle->id }}" 
                                           name="datos[{{ $detalle->id }}]" 
                                           value="{{ old('datos.'.$detalle->id) }}"
                                           @if($detalle->requerido) required @endif>
                                    @break
                                    
                                @case('textarea')
                                    <textarea class="form-control @error('datos.'.$detalle->id) is-invalid @enderror" 
                                              id="datos_{{ $detalle->id }}" 
                                              name="datos[{{ $detalle->id }}]" 
                                              rows="4"
                                              @if($detalle->requerido) required @endif>{{ old('datos.'.$detalle->id) }}</textarea>
                                    @break
                                    
                                @case('date')
                                    <input type="date" 
                                           class="form-control @error('datos.'.$detalle->id) is-invalid @enderror" 
                                           id="datos_{{ $detalle->id }}" 
                                           name="datos[{{ $detalle->id }}]" 
                                           value="{{ old('datos.'.$detalle->id) }}"
                                           @if($detalle->requerido) required @endif>
                                    @break
                                    
                                @case('select')
                                    <select class="form-control @error('datos.'.$detalle->id) is-invalid @enderror" 
                                            id="datos_{{ $detalle->id }}" 
                                            name="datos[{{ $detalle->id }}]"
                                            @if($detalle->requerido) required @endif>
                                        <option value="">Seleccione una opción</option>
                                        @if($detalle->opciones)
                                            @foreach($detalle->opciones as $opcion)
                                                <option value="{{ $opcion }}" 
                                                        @if(old('datos.'.$detalle->id) == $opcion) selected @endif>
                                                    {{ $opcion }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @break
                                    
                                @case('number')
                                    <input type="number" 
                                           class="form-control @error('datos.'.$detalle->id) is-invalid @enderror" 
                                           id="datos_{{ $detalle->id }}" 
                                           name="datos[{{ $detalle->id }}]" 
                                           value="{{ old('datos.'.$detalle->id) }}"
                                           @if($detalle->requerido) required @endif>
                                    @break
                                    
                                @default
                                    <input type="text" 
                                           class="form-control @error('datos.'.$detalle->id) is-invalid @enderror" 
                                           id="datos_{{ $detalle->id }}" 
                                           name="datos[{{ $detalle->id }}]" 
                                           value="{{ old('datos.'.$detalle->id) }}"
                                           @if($detalle->requerido) required @endif>
                            @endswitch
                            
                            @error('datos.'.$detalle->id)
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @endforeach
                    
                    <!-- Subida de imágenes (especialmente para "Reportar suceso") -->
                    @if(strtolower($tarea->nombre) == 'reportar suceso' || str_contains(strtolower($tarea->nombre), 'imagen'))
                        <div class="mb-3">
                            <label for="imagenes" class="form-label">
                                <i class="fas fa-camera me-1"></i>Imágenes
                                <small class="text-muted">(Opcional - Máximo 5 imágenes)</small>
                            </label>
                            <input type="file" 
                                   class="form-control @error('imagenes') is-invalid @enderror" 
                                   id="imagenes" 
                                   name="imagenes[]" 
                                   multiple 
                                   accept="image/*">
                            <div class="form-text">
                                Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB por imagen.
                            </div>
                            @error('imagenes')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Vista previa de imágenes -->
                        <div id="preview-container" class="mb-3" style="display: none;">
                            <label class="form-label">Vista previa:</label>
                            <div id="image-preview" class="row"></div>
                        </div>
                    @endif
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i>Enviar Reporte
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
                <p class="card-text">
                    <strong>Tarea:</strong> {{ $tarea->nombre }}
                </p>
                <p class="card-text">
                    <strong>Campos requeridos:</strong> {{ $tarea->detalles->where('requerido', true)->count() }}
                </p>
                <p class="card-text">
                    <strong>Total de campos:</strong> {{ $tarea->detalles->count() }}
                </p>
                
                @if($tarea->detalles->where('requerido', true)->count() > 0)
                    <hr>
                    <h6>Campos obligatorios:</h6>
                    <ul class="list-unstyled">
                        @foreach($tarea->detalles->where('requerido', true) as $detalle)
                            <li><i class="fas fa-asterisk text-danger me-1"></i>{{ $detalle->campo_nombre }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Vista previa de imágenes
    document.getElementById('imagenes').addEventListener('change', function(e) {
        const files = e.target.files;
        const previewContainer = document.getElementById('preview-container');
        const imagePreview = document.getElementById('image-preview');
        
        if (files.length > 0) {
            previewContainer.style.display = 'block';
            imagePreview.innerHTML = '';
            
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-6 mb-2';
                        col.innerHTML = `
                            <div class="card">
                                <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <small class="text-muted">${file.name}</small>
                                </div>
                            </div>
                        `;
                        imagePreview.appendChild(col);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            previewContainer.style.display = 'none';
        }
    });
</script>
@endpush
