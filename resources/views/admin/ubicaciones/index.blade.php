@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <x-usuario.sidebar />

    <!-- Contenido principal -->
    <div class="flex-1 lg:ml-64">
        <!-- Header -->
        <x-usuario.header />

        <!-- Menú Móvil -->
        <x-usuario.mobile-menu />

        <!-- Contenido Principal -->
        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <!-- Título -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Gestión de Ubicaciones Permitidas
                </h1>
                <div class="flex gap-2">
                    <a href="{{ route('admin.ubicaciones.create') }}" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Agregar Ubicación
                    </a>
                    <a href="{{ url('/') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>

            <!-- Mensajes -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Información -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-blue-800 font-semibold mb-1">Control de Acceso por Geolocalización</h3>
                        <p class="text-blue-700 text-sm">Los usuarios solo pueden iniciar sesión si se encuentran dentro del radio permitido de alguna de estas ubicaciones. Para obtener coordenadas precisas, usa Google Maps: clic derecho en el mapa → copiar coordenadas.</p>
                    </div>
                </div>
            </div>

            <!-- Lista de ubicaciones -->
            @if($ubicaciones->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($ubicaciones as $ubicacion)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                            <div class="bg-gradient-to-r {{ $ubicacion->activa ? 'from-orange-500 to-orange-600' : 'from-gray-400 to-gray-500' }} p-4 text-white">
                                <h3 class="font-bold text-lg flex items-center justify-between">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $ubicacion->nombre }}
                                    </span>
                                    <span class="px-2 py-1 text-xs bg-white {{ $ubicacion->activa ? 'text-orange-600' : 'text-gray-600' }} rounded-full">
                                        {{ $ubicacion->activa ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </h3>
                            </div>
                            <div class="p-4 space-y-3">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-500">Sucursal</p>
                                        <p class="text-gray-900 font-medium">{{ $ubicacion->sucursal->nombre ?? 'Sin asignar' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500">Coordenadas GPS</p>
                                        <p class="text-sm text-gray-900 font-mono">{{ $ubicacion->latitud }}, {{ $ubicacion->longitud }}</p>
                                        <a href="https://www.google.com/maps?q={{ $ubicacion->latitud }},{{ $ubicacion->longitud }}" 
                                           target="_blank" 
                                           class="text-xs text-blue-600 hover:text-blue-800 flex items-center mt-1">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            Ver en Google Maps
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-500">Radio de Acceso</p>
                                        <p class="text-gray-900 font-medium">{{ $ubicacion->radio }} metros</p>
                                    </div>
                                </div>

                                @if($ubicacion->descripcion)
                                <div class="pt-3 border-t border-gray-200">
                                    <p class="text-sm text-gray-600">{{ $ubicacion->descripcion }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="px-4 pb-4 pt-2 flex gap-2">
                                <form action="{{ route('admin.ubicaciones.toggle', $ubicacion) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition">
                                        {{ $ubicacion->activa ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.ubicaciones.destroy', $ubicacion) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta ubicación?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="mt-6">
                    {{ $ubicaciones->links() }}
                </div>
            @else
                <div class="bg-gray-50 border-l-4 border-gray-400 p-6 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-12 h-12 text-gray-400 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <h4 class="text-lg font-bold text-gray-700">No hay ubicaciones registradas</h4>
                            <p class="text-gray-600">Agrega ubicaciones permitidas para controlar desde dónde pueden acceder los usuarios al sistema.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

