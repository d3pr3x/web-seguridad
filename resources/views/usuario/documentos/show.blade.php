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
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $documento->nombre_tipo }}</h1>
                        <p class="text-gray-600">Detalle del documento</p>
                    </div>
                    <a href="{{ route('usuario.documentos.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition">
                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>

            <!-- Estado del Documento -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 bg-{{ $documento->estado_badge['color'] }}-50 border-b border-{{ $documento->estado_badge['color'] }}-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-{{ $documento->estado_badge['color'] }}-800 flex items-center">
                            @if($documento->estado == 'aprobado')
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($documento->estado == 'rechazado')
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                            Estado: {{ $documento->estado_badge['texto'] }}
                        </h2>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                            bg-{{ $documento->estado_badge['color'] }}-100 text-{{ $documento->estado_badge['color'] }}-800">
                            {{ $documento->estado_badge['texto'] }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Tipo de Documento</label>
                            <p class="text-gray-900 font-medium">{{ $documento->nombre_tipo }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Fecha de Envío</label>
                            <p class="text-gray-900 font-medium">{{ $documento->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        @if($documento->estado == 'aprobado')
                            <div class="bg-green-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-green-700 mb-1">Aprobado Por</label>
                                <p class="text-green-900 font-medium">{{ $documento->aprobador->nombre_completo ?? 'Sistema' }}</p>
                            </div>

                            <div class="bg-green-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-green-700 mb-1">Fecha de Aprobación</label>
                                <p class="text-green-900 font-medium">{{ $documento->aprobado_en->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif

                        @if($documento->es_cambio)
                            <div class="bg-blue-50 rounded-lg p-4 md:col-span-2">
                                <label class="block text-sm font-medium text-blue-700 mb-1">Tipo de Solicitud</label>
                                <p class="text-blue-900 font-medium">
                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Solicitud de Cambio de Documento
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($documento->estado == 'rechazado' && $documento->motivo_rechazo)
                        <div class="mt-4 p-4 bg-red-100 border-l-4 border-red-500 rounded-r-lg">
                            <p class="text-sm font-medium text-red-800 mb-1">Motivo del Rechazo:</p>
                            <p class="text-sm text-red-700">{{ $documento->motivo_rechazo }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Imágenes del Documento -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Imágenes del Documento
                    </h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Imagen Frente -->
                        <div>
                            <h3 class="font-medium text-gray-700 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                </svg>
                                Frente
                            </h3>
                            @if($documento->imagen_frente)
                                <a href="{{ Storage::url($documento->imagen_frente) }}" target="_blank" class="block">
                                    <img src="{{ Storage::url($documento->imagen_frente) }}" 
                                         alt="Frente del documento" 
                                         class="w-full h-auto rounded-lg border-2 border-gray-300 hover:border-blue-500 transition cursor-pointer">
                                </a>
                                <p class="text-xs text-gray-500 mt-2 text-center">Click para ver en tamaño completo</p>
                            @else
                                <div class="bg-gray-100 rounded-lg p-8 text-center">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500">No disponible</p>
                                </div>
                            @endif
                        </div>

                        <!-- Imagen Reverso -->
                        <div>
                            <h3 class="font-medium text-gray-700 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                </svg>
                                Reverso
                            </h3>
                            @if($documento->imagen_reverso)
                                <a href="{{ Storage::url($documento->imagen_reverso) }}" target="_blank" class="block">
                                    <img src="{{ Storage::url($documento->imagen_reverso) }}" 
                                         alt="Reverso del documento" 
                                         class="w-full h-auto rounded-lg border-2 border-gray-300 hover:border-blue-500 transition cursor-pointer">
                                </a>
                                <p class="text-xs text-gray-500 mt-2 text-center">Click para ver en tamaño completo</p>
                            @else
                                <div class="bg-gray-100 rounded-lg p-8 text-center">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500">No disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            @if($documento->estado == 'pendiente')
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Documento en Revisión</p>
                            <p class="text-sm text-yellow-700 mt-1">
                                Tu documento está siendo revisado por un supervisor o administrador. 
                                Recibirás una notificación cuando sea aprobado o rechazado.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($documento->estado == 'rechazado')
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-red-800">Documento Rechazado</p>
                            <p class="text-sm text-red-700 mt-1">
                                Tu documento fue rechazado. Por favor revisa el motivo y carga nuevamente el documento.
                            </p>
                            <div class="mt-3">
                                <a href="{{ route('usuario.documentos.create', ['tipo' => $documento->tipo_documento]) }}" 
                                   class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Volver a Intentar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($documento->estado == 'aprobado')
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-green-800">Documento Aprobado</p>
                            <p class="text-sm text-green-700 mt-1">
                                Tu documento ha sido aprobado y está activo en el sistema.
                                Si necesitas actualizarlo, puedes solicitar un cambio desde la página de documentos.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


