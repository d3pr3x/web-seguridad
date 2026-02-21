@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <x-usuario.sidebar />

    <!-- Contenido principal -->
    <div class="flex-1 lg:mr-64">
        <!-- Header -->
        <x-usuario.header />

        <!-- Menú Móvil -->
        <x-usuario.mobile-menu />

        <!-- Contenido Principal -->
        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <!-- Título -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Gestión de Sectores
                </h1>
                <a href="{{ url('/') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>

            <!-- Mensajes -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Información -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-blue-800 font-semibold mb-1">Vista Sectores</h3>
                        <p class="text-blue-700 text-sm">Seleccione una <strong>empresa</strong> para ver sus instalaciones y administrar los sectores de cada una.</p>
                    </div>
                </div>
            </div>

            <!-- Lista de empresas -->
            @if($empresas->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($empresas as $empresa)
                        <a href="{{ route('admin.sectores.por-empresa', $empresa) }}" class="block">
                            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden border-l-4 border-cyan-500">
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $empresa->nombre }}</h3>
                                            @if($empresa->codigo)
                                                <p class="text-sm text-gray-600">Código: {{ $empresa->codigo }}</p>
                                            @endif
                                        </div>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $empresa->activa ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $empresa->activa ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center text-cyan-600">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                <span class="font-semibold">{{ $empresa->sucursales_count }} {{ $empresa->sucursales_count == 1 ? 'instalación' : 'instalaciones' }}</span>
                                            </div>
                                            <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="mt-6">
                    {{ $empresas->links() }}
                </div>
            @else
                <div class="bg-gray-50 border-l-4 border-gray-400 p-6 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-12 h-12 text-gray-400 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <div>
                            <h4 class="text-lg font-bold text-gray-700">No hay empresas registradas</h4>
                            <p class="text-gray-600">Cree empresas e instalaciones desde <a href="{{ route('admin.clientes.index') }}" class="text-cyan-600 hover:underline">Clientes</a> para poder gestionar sectores.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
