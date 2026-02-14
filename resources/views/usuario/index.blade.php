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
        <div class="container mx-auto px-4 py-6 max-w-7xl">
        <!-- Mensajes de éxito/error -->
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
        @endif

            <!-- Información de Usuario (solo móvil) -->
            <div class="lg:hidden bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-lg font-bold text-gray-800">{{ auth()->user()->nombre_completo }}</h2>
                        <p class="text-sm text-gray-600">RUN: {{ auth()->user()->run }}</p>
                        <p class="text-sm text-gray-600">{{ auth()->user()->nombre_sucursal }}</p>
                    </div>
                </div>
            </div>

            <!-- Grid de Secciones - Solo REPORTES -->
            <div class="grid grid-cols-1 gap-6">
                {{-- Temporalmente comentado - Sección NOVEDADES --}}
                {{-- <div>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <!-- Header de Sección -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                Novedades
                            </h2>
                            <p class="text-blue-100 text-sm mt-1">Registra las actividades de tu turno</p>
                        </div>

                        <!-- Novedades del Servicio -->
                        <div class="p-4 space-y-3">
                            <a href="{{ route('usuario.acciones.create', ['tipo' => 'inicio_servicio']) }}" class="block">
                                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg hover:bg-green-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-green-800">Inicio del Servicio</h3>
                                            <p class="text-sm text-green-600">Registro de inicio de turno</p>
                                        </div>
                                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('usuario.acciones.create', ['tipo' => 'rondas']) }}" class="block">
                                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg hover:bg-blue-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-blue-800">Rondas</h3>
                                            <p class="text-sm text-blue-600">Registro de patrullaje</p>
                                        </div>
                                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('usuario.acciones.create', ['tipo' => 'constancias']) }}" class="block">
                                <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r-lg hover:bg-purple-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-purple-800">Constancias</h3>
                                            <p class="text-sm text-purple-600">Documentación y constancias</p>
                                        </div>
                                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('usuario.acciones.create', ['tipo' => 'concurrencia_autoridades']) }}" class="block">
                                <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-r-lg hover:bg-indigo-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-indigo-800">Concurrencia de autoridades</h3>
                                            <p class="text-sm text-indigo-600">Presencia de policía</p>
                                        </div>
                                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('usuario.acciones.create', ['tipo' => 'concurrencia_servicios']) }}" class="block">
                                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r-lg hover:bg-orange-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-orange-800">Concurrencia Servicios</h3>
                                            <p class="text-sm text-orange-600">Servicios de emergencia</p>
                                        </div>
                                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('usuario.acciones.create', ['tipo' => 'entrega_servicio']) }}" class="block">
                                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg hover:bg-red-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-red-800">Entrega del Servicio</h3>
                                            <p class="text-sm text-red-600">Fin de turno</p>
                                        </div>
                                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div> --}}

                <!-- Sección REPORTES -->
                <div>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <!-- Header de Sección -->
                        <div class="bg-gradient-to-r from-red-500 to-red-600 p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                Reportes
                            </h2>
                            <p class="text-red-100 text-sm mt-1">Situaciones críticas que requieren atención</p>
                        </div>

                        <!-- Reportes Especiales -->
                        <div class="p-4 space-y-3">
                            <a href="{{ route('usuario.reportes.create', ['tipo' => 'incidentes']) }}" class="block">
                                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg hover:bg-red-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-red-800">Incidentes</h3>
                                            <p class="text-sm text-red-600">Eventos críticos</p>
                                        </div>
                                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('usuario.reportes.create', ['tipo' => 'denuncia']) }}" class="block">
                                <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r-lg hover:bg-purple-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-purple-800">Denuncia</h3>
                                            <p class="text-sm text-purple-600">Reportar delito</p>
                                        </div>
                                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('usuario.reportes.create', ['tipo' => 'detenido']) }}" class="block">
                                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r-lg hover:bg-orange-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-orange-800">Detenido</h3>
                                            <p class="text-sm text-orange-600">Persona detenida</p>
                                        </div>
                                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('usuario.reportes.create', ['tipo' => 'accion_sospechosa']) }}" class="block">
                                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg hover:bg-yellow-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-yellow-800">Acción Sospechosa</h3>
                                            <p class="text-sm text-yellow-600">Comportamiento extraño</p>
                                        </div>
                                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
