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
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-red-500 to-red-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-white">{{ $reporteEspecial->nombre_tipo }}</h2>
                            <p class="text-red-100">{{ $reporteEspecial->dia->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($reporteEspecial->hora)->format('H:i') }}</p>
                        </div>
                        <div class="text-right">
                            @php
                                $badgeClass = match($reporteEspecial->estado) {
                                    'pendiente' => 'bg-yellow-100 text-yellow-800',
                                    'en_revision' => 'bg-blue-100 text-blue-800',
                                    'completado' => 'bg-green-100 text-green-800',
                                    'rechazado' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $badgeClass }}">
                                {{ ucfirst(str_replace('_', ' ', $reporteEspecial->estado)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Información básica -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Información General</h3>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sucursal:</dt>
                                    <dd class="text-sm text-gray-900">{{ $reporteEspecial->sucursal->nombre }}</dd>
                                </div>
                                @if($reporteEspecial->sector)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Sector:</dt>
                                        <dd class="text-sm text-gray-900">{{ $reporteEspecial->sector->nombre }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Registrado:</dt>
                                    <dd class="text-sm text-gray-900">{{ $reporteEspecial->created_at->format('d/m/Y H:i:s') }}</dd>
                                </div>
                            </dl>
                        </div>

                        @if($reporteEspecial->latitud && $reporteEspecial->longitud)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Ubicación</h3>
                                <dl class="space-y-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Coordenadas:</dt>
                                        <dd class="text-sm text-gray-900">{{ $reporteEspecial->latitud }}, {{ $reporteEspecial->longitud }}</dd>
                                    </div>
                                    @if($reporteEspecial->precision)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Precisión:</dt>
                                            <dd class="text-sm text-gray-900">{{ round($reporteEspecial->precision) }}m</dd>
                                        </div>
                                    @endif
                                </dl>
                                <div class="mt-3">
                                    <a href="https://www.google.com/maps?q={{ $reporteEspecial->latitud }},{{ $reporteEspecial->longitud }}" 
                                       target="_blank" 
                                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Ver en Google Maps
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Contenido -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Novedad</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $reporteEspecial->novedad }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Acción Tomada</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $reporteEspecial->accion }}</p>
                        </div>
                    </div>

                    @if($reporteEspecial->resultado)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Resultado</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $reporteEspecial->resultado }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Imágenes -->
                    @if($reporteEspecial->imagenes && count($reporteEspecial->imagenes) > 0)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Imágenes</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($reporteEspecial->imagenes as $imagen)
                                    <div class="relative group">
                                        <a href="{{ asset('storage/' . $imagen) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $imagen) }}" 
                                                 alt="Imagen del reporte" 
                                                 class="w-full h-48 object-cover rounded-lg shadow-md hover:shadow-lg transition">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($reporteEspecial->comentarios_admin)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Comentarios del Supervisor/Admin</h3>
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $reporteEspecial->comentarios_admin }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Acciones -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('usuario.reportes.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                            Volver al Listado
                        </a>
                        <a href="{{ route('usuario.index') }}" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                            Ir al Portal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


