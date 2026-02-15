@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <x-usuario.sidebar />

    <!-- Contenido principal -->
    <div class="flex-1 lg:mr-64">
        <!-- Headers -->
        <x-usuario.header />

        <!-- Menú Móvil -->
        <x-usuario.mobile-menu />

        <!-- Contenido Principal -->
        <div class="container mx-auto px-4 py-6 max-w-4xl">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    {{ $documentoExistente ? 'Solicitar Cambio de Documento' : 'Cargar Nuevo Documento' }}
                </h1>
                <p class="text-gray-600">{{ $tiposDocumentos[$tipo] }}</p>
            </div>

            <!-- Avisos -->
            @if($solicitudPendiente)
                <div class="mb-6 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <p class="font-medium">Ya tienes una solicitud pendiente</p>
                            <p class="text-sm">Ya has enviado este documento para aprobación. No puedes enviar otro hasta que se resuelva la solicitud actual.</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($documentoExistente)
                <div class="mb-6 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 rounded">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-medium">Solicitando cambio de documento</p>
                            <p class="text-sm">Ya tienes un documento aprobado. Esta solicitud reemplazará el documento actual una vez sea aprobada por un supervisor o administrador.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulario -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-blue-600">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Cargar Imágenes del Documento
                    </h2>
                </div>

                <form action="{{ route('usuario.documentos.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <input type="hidden" name="tipo_documento" value="{{ $tipo }}">

                    <div class="space-y-6">
                        <!-- Imagen Frente -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="inline w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Foto del Frente *
                            </label>
                            
                            <div class="space-y-3">
                                <div class="flex items-center space-x-4">
                                    <input type="file" 
                                           class="hidden" 
                                           id="imagen_frente" 
                                           name="imagen_frente" 
                                           accept="image/*,image/heic,image/heif"
                                           required>
                                    <button type="button" 
                                            class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-md px-4 py-2 text-sm font-medium text-gray-700 transition-colors"
                                            onclick="document.getElementById('imagen_frente').click()">
                                        Seleccionar archivo
                                    </button>
                                    <span class="text-sm text-gray-500" id="fileStatusFrente">No se eligió archivo</span>
                                </div>
                                
                                <div id="previewFrente" class="hidden">
                                    <img id="imgFrente" src="" alt="Vista previa" class="max-w-full h-auto rounded-lg border border-gray-300">
                                </div>
                            </div>
                            
                            @error('imagen_frente')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Imagen Reverso -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="inline w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Foto del Reverso *
                            </label>
                            
                            <div class="space-y-3">
                                <div class="flex items-center space-x-4">
                                    <input type="file" 
                                           class="hidden" 
                                           id="imagen_reverso" 
                                           name="imagen_reverso" 
                                           accept="image/*,image/heic,image/heif"
                                           required>
                                    <button type="button" 
                                            class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-md px-4 py-2 text-sm font-medium text-gray-700 transition-colors"
                                            onclick="document.getElementById('imagen_reverso').click()">
                                        Seleccionar archivo
                                    </button>
                                    <span class="text-sm text-gray-500" id="fileStatusReverso">No se eligió archivo</span>
                                </div>
                                
                                <div id="previewReverso" class="hidden">
                                    <img id="imgReverso" src="" alt="Vista previa" class="max-w-full h-auto rounded-lg border border-gray-300">
                                </div>
                            </div>
                            
                            @error('imagen_reverso')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Información -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Requisitos de las imágenes:</h3>
                            <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                                <li>Las fotos deben ser claras y legibles</li>
                                <li>Formatos aceptados: JPG, PNG, HEIC</li>
                                <li>Tamaño máximo: 15MB por imagen</li>
                                <li>Asegúrate de que todos los datos sean visibles</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-6 flex gap-4">
                        <a href="{{ route('usuario.documentos.index') }}" 
                           class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-md text-center transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md transition"
                                {{ $solicitudPendiente ? 'disabled' : '' }}>
                            {{ $documentoExistente ? 'Enviar Solicitud de Cambio' : 'Enviar Documento' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Preview imagen frente
document.getElementById('imagen_frente').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const status = document.getElementById('fileStatusFrente');
    const preview = document.getElementById('previewFrente');
    const img = document.getElementById('imgFrente');
    
    if (file) {
        if (file.size > 15360 * 1024) {
            alert('⚠️ La imagen es muy grande. Máximo 15MB.');
            e.target.value = '';
            status.textContent = 'No se eligió archivo';
            preview.classList.add('hidden');
            return;
        }
        
        status.textContent = file.name;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        status.textContent = 'No se eligió archivo';
        preview.classList.add('hidden');
    }
});

// Preview imagen reverso
document.getElementById('imagen_reverso').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const status = document.getElementById('fileStatusReverso');
    const preview = document.getElementById('previewReverso');
    const img = document.getElementById('imgReverso');
    
    if (file) {
        if (file.size > 15360 * 1024) {
            alert('⚠️ La imagen es muy grande. Máximo 15MB.');
            e.target.value = '';
            status.textContent = 'No se eligió archivo';
            preview.classList.add('hidden');
            return;
        }
        
        status.textContent = file.name;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        status.textContent = 'No se eligió archivo';
        preview.classList.add('hidden');
    }
});
</script>
@endsection


