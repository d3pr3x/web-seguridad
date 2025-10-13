@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Registrar: {{ \App\Models\Accion::tipos()[$tipo] ?? 'Acción' }}</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('acciones.store') }}" enctype="multipart/form-data" id="formAccion">
                        @csrf

                        <input type="hidden" name="tipo" value="{{ $tipo }}">
                        
                        <!-- Campos comunes -->
                        <div class="mb-3">
                            <label for="dia" class="form-label">Día <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('dia') is-invalid @enderror" 
                                   id="dia" 
                                   name="dia" 
                                   value="{{ old('dia', date('Y-m-d')) }}" 
                                   required>
                            @error('dia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hora" class="form-label">Hora <span class="text-danger">*</span></label>
                            <input type="time" 
                                   class="form-control @error('hora') is-invalid @enderror" 
                                   id="hora" 
                                   name="hora" 
                                   value="{{ old('hora', date('H:i')) }}" 
                                   required>
                            @error('hora')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="sector_id" class="form-label">Sector</label>
                            <select class="form-select @error('sector_id') is-invalid @enderror" 
                                    id="sector_id" 
                                    name="sector_id">
                                <option value="">Sin sector específico</option>
                                @foreach($sectores as $sector)
                                    <option value="{{ $sector->id }}" {{ old('sector_id') == $sector->id ? 'selected' : '' }}>
                                        {{ $sector->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sector_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="novedad" class="form-label">Novedad</label>
                            <textarea class="form-control @error('novedad') is-invalid @enderror" 
                                      id="novedad" 
                                      name="novedad" 
                                      rows="3">{{ old('novedad') }}</textarea>
                            @error('novedad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="accion" class="form-label">Acción</label>
                            <textarea class="form-control @error('accion') is-invalid @enderror" 
                                      id="accion" 
                                      name="accion" 
                                      rows="3">{{ old('accion') }}</textarea>
                            @error('accion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="resultado" class="form-label">Resultado</label>
                            <textarea class="form-control @error('resultado') is-invalid @enderror" 
                                      id="resultado" 
                                      name="resultado" 
                                      rows="3">{{ old('resultado') }}</textarea>
                            @error('resultado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-camera text-primary me-2"></i>
                                Agregar Fotos (Opcional)
                            </label>
                            
                            <!-- Área de subida visual -->
                            <div class="d-flex align-items-center mb-3">
                                <input type="file" 
                                       class="d-none" 
                                       id="imagenes" 
                                       name="imagenes[]" 
                                       multiple 
                                       accept="image/*,image/heic,image/heif">
                                <button type="button" 
                                        class="btn btn-outline-secondary me-4"
                                        onclick="document.getElementById('imagenes').click()">
                                    <i class="fas fa-camera me-2"></i>
                                    Seleccionar archivo
                                </button>
                                <span class="text-muted" id="fileStatus">No se eligió archivo</span>
                            </div>
                            
                            <!-- Lista de archivos seleccionados -->
                            <div id="fileList" class="d-none mb-3">
                                <div class="text-muted small mb-2">Archivos seleccionados:</div>
                                <div id="fileItems"></div>
                            </div>
                            
                            @error('imagenes')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('imagenes.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Campos ocultos para geolocalización -->
                        <input type="hidden" name="latitud" id="latitud">
                        <input type="hidden" name="longitud" id="longitud">
                        <input type="hidden" name="precision" id="precision">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('acciones.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Registrar Acción</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Obtener geolocalización al cargar la página
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('latitud').value = position.coords.latitude;
                document.getElementById('longitud').value = position.coords.longitude;
                document.getElementById('precision').value = position.coords.accuracy;
            },
            function(error) {
                console.log('Error al obtener geolocalización:', error);
            }
        );
    }

    // Manejo de archivos
    const fileInput = document.getElementById('imagenes');
    const fileStatus = document.getElementById('fileStatus');
    const fileList = document.getElementById('fileList');
    const fileItems = document.getElementById('fileItems');

    fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        // Validar número máximo
        if (files.length > 4) {
            alert('⚠️ Solo puede seleccionar un máximo de 4 fotografías.');
            fileInput.value = '';
            updateFileDisplay([]);
            return;
        }
        
        // Validar tamaño de cada imagen
        for (let i = 0; i < files.length; i++) {
            if (files[i].size > 15360 * 1024) { // 15MB
                alert('⚠️ La imagen "' + files[i].name + '" es muy grande. Máximo 15MB por foto.');
                fileInput.value = '';
                updateFileDisplay([]);
                return;
            }
        }

        updateFileDisplay(files);
    });

    function updateFileDisplay(files) {
        if (files.length === 0) {
            fileStatus.textContent = 'No se eligió archivo';
            fileList.classList.add('d-none');
            return;
        }

        // Actualizar estado
        if (files.length === 1) {
            fileStatus.textContent = '1 archivo seleccionado';
        } else {
            fileStatus.textContent = files.length + ' archivos seleccionados';
        }

        // Mostrar lista de archivos
        fileItems.innerHTML = '';
        files.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'd-flex justify-content-between align-items-center bg-light border rounded p-2 mb-2';
            fileItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-image text-primary me-3"></i>
                    <div>
                        <div class="fw-medium">${file.name}</div>
                        <small class="text-muted">${formatFileSize(file.size)}</small>
                    </div>
                </div>
                <button type="button" 
                        class="btn btn-outline-danger btn-sm"
                        onclick="removeFile(${index})">
                    <i class="fas fa-times"></i>
                </button>
            `;
            fileItems.appendChild(fileItem);
        });

        fileList.classList.remove('d-none');
    }

    function removeFile(index) {
        const dt = new DataTransfer();
        const files = Array.from(fileInput.files);
        
        files.splice(index, 1);
        
        files.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
        
        updateFileDisplay(files);
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
</script>
@endsection




