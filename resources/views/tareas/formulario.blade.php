@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:ml-64">
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
                @if($tarea->icono)
                    <i class="{{ $tarea->icono }} me-2"></i>
                @else
                    <i class="fas fa-tasks me-2"></i>
                @endif
                {{ $tarea->nombre }}
            </h1>
            <a href="{{ url('/') }}" class="btn btn-outline-secondary">
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
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation-triangle me-2"></i>Error al procesar el formulario:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Indicador de ubicación GPS -->
                <div id="ubicacion-status" class="alert alert-info" style="display: none;">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    <span id="ubicacion-mensaje">Obteniendo ubicación GPS...</span>
                </div>
                
                <form action="{{ route('reportes.store') }}" method="POST" enctype="multipart/form-data" id="reporteForm">
                    @csrf
                    <input type="hidden" name="tarea_id" value="{{ $tarea->id }}">
                    <input type="hidden" name="latitud" id="latitud">
                    <input type="hidden" name="longitud" id="longitud">
                    <input type="hidden" name="precision" id="precision">
                    
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
                                   accept="image/*,image/jpeg,image/jpg,image/png,image/gif,image/webp">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Formatos permitidos: JPG, PNG, GIF, WEBP. Tamaño máximo: 15MB por imagen.
                            </div>
                            @error('imagenes')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            @error('imagenes.*')
                                <div class="invalid-feedback d-block">
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
                        <a href="{{ url('/') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" id="btnEnviar">
                            <i class="fas fa-paper-plane me-1"></i>
                            <span id="btnTexto">Enviar Reporte</span>
                            <span id="btnLoading" style="display: none;">
                                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                Enviando...
                            </span>
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
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Captura de ubicación GPS
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener ubicación GPS al cargar la página
        const ubicacionStatus = document.getElementById('ubicacion-status');
        const ubicacionMensaje = document.getElementById('ubicacion-mensaje');
        const latitudInput = document.getElementById('latitud');
        const longitudInput = document.getElementById('longitud');
        const precisionInput = document.getElementById('precision');

        if (navigator.geolocation) {
            ubicacionStatus.style.display = 'block';
            ubicacionStatus.className = 'alert alert-info';
            ubicacionMensaje.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Obteniendo ubicación GPS...';

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Éxito al obtener ubicación
                    latitudInput.value = position.coords.latitude;
                    longitudInput.value = position.coords.longitude;
                    precisionInput.value = position.coords.accuracy;

                    ubicacionStatus.className = 'alert alert-success';
                    ubicacionMensaje.innerHTML = `<i class="fas fa-check-circle me-2"></i>Ubicación GPS capturada correctamente (Precisión: ${Math.round(position.coords.accuracy)}m)`;
                    
                    // Ocultar el mensaje después de 5 segundos
                    setTimeout(() => {
                        ubicacionStatus.style.display = 'none';
                    }, 5000);
                },
                function(error) {
                    // Error al obtener ubicación
                    let mensaje = '';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            mensaje = 'Permiso de ubicación denegado. El reporte se enviará sin ubicación GPS.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            mensaje = 'Ubicación no disponible. El reporte se enviará sin ubicación GPS.';
                            break;
                        case error.TIMEOUT:
                            mensaje = 'Tiempo de espera agotado. El reporte se enviará sin ubicación GPS.';
                            break;
                        default:
                            mensaje = 'Error al obtener ubicación. El reporte se enviará sin ubicación GPS.';
                    }
                    ubicacionStatus.className = 'alert alert-warning';
                    ubicacionMensaje.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${mensaje}`;
                    
                    // Ocultar el mensaje después de 7 segundos
                    setTimeout(() => {
                        ubicacionStatus.style.display = 'none';
                    }, 7000);
                },
                {
                    enableHighAccuracy: true, // Máxima precisión
                    timeout: 10000, // 10 segundos de timeout
                    maximumAge: 0 // No usar caché
                }
            );
        } else {
            ubicacionStatus.style.display = 'block';
            ubicacionStatus.className = 'alert alert-warning';
            ubicacionMensaje.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Tu navegador no soporta geolocalización. El reporte se enviará sin ubicación GPS.';
            
            setTimeout(() => {
                ubicacionStatus.style.display = 'none';
            }, 5000);
        }

        // Vista previa de imágenes
        const imagenesInput = document.getElementById('imagenes');
        
        if (imagenesInput) {
            imagenesInput.addEventListener('change', function(e) {
                const files = e.target.files;
                const previewContainer = document.getElementById('preview-container');
                const imagePreview = document.getElementById('image-preview');
                
                if (files.length > 0) {
                    // Validar cantidad de imágenes
                    if (files.length > 5) {
                        alert('Solo puedes subir un máximo de 5 imágenes');
                        this.value = '';
                        return;
                    }
                    
                    previewContainer.style.display = 'block';
                    imagePreview.innerHTML = '';
                    
                    Array.from(files).forEach((file, index) => {
                        // Validar tamaño
                        if (file.size > 15 * 1024 * 1024) { // 15MB
                            alert(`La imagen ${file.name} es muy grande. Máximo 15MB por imagen.`);
                            return;
                        }
                        
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
            
            // Mejora para móviles - hacer tap en el input abre la cámara/galería
            imagenesInput.addEventListener('click', function() {
                console.log('Abriendo selector de imágenes...');
            });
        }
        
        // Mostrar spinner al enviar formulario
        const form = document.getElementById('reporteForm');
        const btnEnviar = document.getElementById('btnEnviar');
        const btnTexto = document.getElementById('btnTexto');
        const btnLoading = document.getElementById('btnLoading');
        
        if (form && btnEnviar) {
            form.addEventListener('submit', function(e) {
                // Validar que no haya errores antes de mostrar el spinner
                const inputs = form.querySelectorAll('[required]');
                let valid = true;
                
                inputs.forEach(input => {
                    if (!input.value) {
                        valid = false;
                    }
                });
                
                if (valid) {
                    btnEnviar.disabled = true;
                    btnTexto.style.display = 'none';
                    btnLoading.style.display = 'inline';
                }
            });
        }
    });
</script>
@endpush


