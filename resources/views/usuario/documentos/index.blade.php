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
        <div class="container mx-auto px-4 py-6 max-w-6xl">
            <!-- Header de Página -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">Mis Documentos Personales</h1>
                        <p class="text-gray-600">Gestiona tus documentos y solicitudes de aprobación</p>
                    </div>
                    <a href="{{ route('usuario.perfil.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition">
                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver al Perfil
                    </a>
                </div>
            </div>

            <!-- Mensajes -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
            @endif

            <!-- Documentos Aprobados -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 bg-green-50 border-b border-green-200">
                    <h2 class="text-xl font-bold text-green-800 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Documentos Aprobados
                    </h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($tiposDocumentos as $tipo => $nombre)
                            @php
                                $documento = $documentosAprobados->get($tipo);
                            @endphp
                            <div class="border rounded-lg p-4 {{ $documento ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="font-semibold {{ $documento ? 'text-green-900' : 'text-gray-700' }}">{{ $nombre }}</h3>
                                        @if($documento)
                                            <p class="text-xs text-green-700 mt-1">
                                                Aprobado el {{ $documento->aprobado_en->format('d/m/Y') }}
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-500 mt-1">No cargado</p>
                                        @endif
                                    </div>
                                    @if($documento)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            ✓ Aprobado
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-600">
                                            Sin cargar
                                        </span>
                                    @endif
                                </div>

                                @if($documento)
                                    <div class="flex gap-2">
                                        <a href="{{ route('usuario.documentos.show', $documento->id) }}" 
                                           class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm text-center transition">
                                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Ver
                                        </a>
                                        <a href="{{ route('usuario.documentos.create', ['tipo' => $tipo]) }}" 
                                           class="flex-1 bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded text-sm text-center transition">
                                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Cambiar
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('usuario.documentos.create', ['tipo' => $tipo]) }}" 
                                       class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-center transition">
                                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Cargar Documento
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Solicitudes Pendientes y Rechazadas -->
            @if($documentosPendientes->count() > 0)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-200">
                    <h2 class="text-xl font-bold text-yellow-800 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Solicitudes en Proceso
                    </h2>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($documentosPendientes as $doc)
                        <div class="border rounded-lg p-4 {{ $doc->estado == 'rechazado' ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="font-semibold text-gray-900">{{ $doc->nombre_tipo }}</h3>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            bg-{{ $doc->estado_badge['color'] }}-100 text-{{ $doc->estado_badge['color'] }}-800">
                                            {{ $doc->estado_badge['texto'] }}
                                        </span>
                                        @if($doc->es_cambio)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Cambio
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        Enviado el {{ $doc->created_at->format('d/m/Y H:i') }}
                                    </p>
                                    @if($doc->estado == 'rechazado' && $doc->motivo_rechazo)
                                        <div class="mt-2 p-3 bg-red-100 border border-red-200 rounded">
                                            <p class="text-sm font-medium text-red-800">Motivo del rechazo:</p>
                                            <p class="text-sm text-red-700">{{ $doc->motivo_rechazo }}</p>
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('usuario.documentos.show', $doc->id) }}" 
                                   class="ml-4 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm transition">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Información -->
            <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Información Importante</p>
                        <ul class="text-sm text-blue-700 mt-2 space-y-1 list-disc list-inside">
                            <li>Todos los documentos deben ser aprobados por un supervisor o administrador.</li>
                            <li>Debes subir una foto del frente y reverso de cada documento.</li>
                            <li>Las imágenes deben ser claras y legibles.</li>
                            <li>Tamaño máximo por imagen: 15MB.</li>
                            <li>Si necesitas cambiar un documento ya aprobado, debes solicitar el cambio y esperar aprobación.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


