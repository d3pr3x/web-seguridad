@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <x-usuario.sidebar />

    <!-- Contenido principal -->
    <div class="flex-1 lg:ml-64">
        <!-- Headers -->
        <x-usuario.header />

        <!-- Menú Móvil -->
        <x-usuario.mobile-menu />

        <!-- Contenido Principal -->
        <div class="container mx-auto px-4 py-6 max-w-4xl">
            <!-- Mensajes -->
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 flex items-center justify-between" id="formHeader">
                    <div>
                        <h2 class="text-xl font-bold text-white" id="formTitle">{{ $tipo ?? 'Acción' }}</h2>
                        <p class="text-white opacity-90" id="formDescription">Completa los datos de tu acción</p>
                    </div>
                    <a href="{{ route('usuario.index') }}" class="text-white px-4 py-2 rounded-lg transition-colors font-medium border border-white border-opacity-30 hover:bg-white hover:bg-opacity-10" id="formBackButton">
                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver
                    </a>
                </div>

                <form method="POST" action="{{ route('usuario.acciones.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="tipo" value="{{ $tipo }}">
                    
                    <!-- Campos comunes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="dia" class="block text-sm font-medium text-gray-700 mb-1">Día <span class="text-red-500">*</span></label>
                            <input type="date" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('dia') border-red-500 @enderror" 
                                   id="dia" 
                                   name="dia" 
                                   value="{{ old('dia', date('Y-m-d')) }}" 
                                   required>
                            @error('dia')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="hora" class="block text-sm font-medium text-gray-700 mb-1">Hora <span class="text-red-500">*</span></label>
                            <input type="time" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('hora') border-red-500 @enderror" 
                                   id="hora" 
                                   name="hora" 
                                   value="{{ old('hora', date('H:i')) }}" 
                                   required>
                            @error('hora')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="sector_id" class="block text-sm font-medium text-gray-700 mb-1">Sector</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sector_id') border-red-500 @enderror" 
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
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="novedad" class="block text-sm font-medium text-gray-700 mb-1">Novedad</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('novedad') border-red-500 @enderror" 
                                  id="novedad" 
                                  name="novedad" 
                                  rows="3" 
                                  placeholder="Describa cualquier novedad observada...">{{ old('novedad') }}</textarea>
                        @error('novedad')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="accion" class="block text-sm font-medium text-gray-700 mb-1">Acción</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('accion') border-red-500 @enderror" 
                                  id="accion" 
                                  name="accion" 
                                  rows="3" 
                                  placeholder="Describa la acción realizada...">{{ old('accion') }}</textarea>
                        @error('accion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="resultado" class="block text-sm font-medium text-gray-700 mb-1">Resultado</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('resultado') border-red-500 @enderror" 
                                  id="resultado" 
                                  name="resultado" 
                                  rows="3" 
                                  placeholder="Describa el resultado obtenido...">{{ old('resultado') }}</textarea>
                        @error('resultado')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="imagenes" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="inline w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Adjuntar Fotos (Opcional)
                        </label>
                        
                        <div class="space-y-3">
                            <!-- Botón para seleccionar archivos -->
                            <div class="flex items-center space-x-4">
                                <input type="file" 
                                       class="hidden" 
                                       id="imagenes" 
                                       name="imagenes[]" 
                                       multiple 
                                       accept="image/*,image/heic,image/heif">
                                <button type="button" 
                                        class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-md px-4 py-2 text-sm font-medium text-gray-700 transition-colors"
                                        onclick="document.getElementById('imagenes').click()">
                                    Seleccionar archivo
                                </button>
                                <span class="text-sm text-gray-500" id="fileStatus">No se eligió archivo</span>
                            </div>
                            
                            <!-- Lista de archivos seleccionados -->
                            <div id="fileList" class="space-y-2 hidden">
                                <div class="text-sm font-medium text-gray-700">Archivos seleccionados:</div>
                                <div id="fileItems" class="space-y-2"></div>
                            </div>
                            
                            <p class="text-xs text-gray-500">Máximo 4 fotos • Hasta 15MB cada una</p>
                        </div>
                        
                        @error('imagenes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('imagenes.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campos ocultos para geolocalización -->
                    <input type="hidden" name="latitud" id="latitud">
                    <input type="hidden" name="longitud" id="longitud">
                    <input type="hidden" name="precision" id="precision">

                    <div class="flex justify-end pt-6 border-t">
                        <button type="submit" class="px-6 py-2 text-white rounded-md transition" id="submitButton">
                            <span id="submitText">Registrar Acción</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Configuración de acciones con colores y títulos
    const actionConfig = {
        'inicio_servicio': {
            title: 'Inicio del Servicio',
            description: 'Registro de inicio de turno',
            color: 'bg-green-500',
            buttonColor: 'bg-green-600 hover:bg-green-700'
        },
        'rondas': {
            title: 'Rondas',
            description: 'Registro de patrullaje',
            color: 'bg-blue-500',
            buttonColor: 'bg-blue-600 hover:bg-blue-700'
        },
        'constancias': {
            title: 'Constancias',
            description: 'Documentación y constancias',
            color: 'bg-purple-500',
            buttonColor: 'bg-purple-600 hover:bg-purple-700'
        },
        'concurrencia_carabineros': {
            title: 'Concurrencia Carabineros',
            description: 'Presencia de policía',
            color: 'bg-indigo-500',
            buttonColor: 'bg-indigo-600 hover:bg-indigo-700'
        },
        'concurrencia_servicios': {
            title: 'Concurrencia Servicios',
            description: 'Servicios de emergencia',
            color: 'bg-orange-500',
            buttonColor: 'bg-orange-600 hover:bg-orange-700'
        },
        'entrega_servicio': {
            title: 'Entrega del Servicio',
            description: 'Fin de turno',
            color: 'bg-red-500',
            buttonColor: 'bg-red-600 hover:bg-red-700'
        }
    };

    // Obtener tipo de acción de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const actionType = urlParams.get('tipo') || 'inicio_servicio';

    // Configurar header del formulario según el tipo de acción
    function configureHeader() {
        const config = actionConfig[actionType];
        if (config) {
            // Actualizar títulos del formulario
            document.getElementById('formTitle').textContent = config.title;
            document.getElementById('formDescription').textContent = config.description;
            
            // Actualizar colores del header del formulario
            const formHeader = document.getElementById('formHeader');
            formHeader.className = `px-6 py-4 flex items-center justify-between ${config.color}`;
            
            // Actualizar color del botón de envío
            const submitButton = document.getElementById('submitButton');
            if (submitButton) {
                submitButton.className = `px-6 py-2 text-white rounded-md transition ${config.buttonColor}`;
            }
            
            // Actualizar texto del botón de envío
            const submitText = document.getElementById('submitText');
            if (submitText) {
                submitText.textContent = `Registrar ${config.title}`;
            }
        }
    }

    // Configurar header al cargar la página
    configureHeader();

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
            fileList.classList.add('hidden');
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
            fileItem.className = 'flex items-center justify-between bg-gray-50 border border-gray-200 rounded-md p-3';
            fileItem.innerHTML = `
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-gray-900">${file.name}</p>
                        <p class="text-xs text-gray-500">${formatFileSize(file.size)}</p>
                    </div>
                </div>
                <button type="button" 
                        class="text-red-500 hover:text-red-700 p-1"
                        onclick="removeFile(${index})">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            fileItems.appendChild(fileItem);
        });

        fileList.classList.remove('hidden');
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


