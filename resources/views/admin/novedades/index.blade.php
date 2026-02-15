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
            <!-- Título y botón volver -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Todas las Novedades
                </h1>
                <a href="{{ url('/') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('admin.novedades.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="sucursal_id" class="block text-sm font-medium text-gray-700 mb-2">Sucursal</label>
                        <select name="sucursal_id" id="sucursal_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Todas las sucursales</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}" @if(request('sucursal_id') == $sucursal->id) selected @endif>
                                    {{ $sucursal->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Acción</label>
                        <select name="tipo" id="tipo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Todos</option>
                            @foreach($tipos as $key => $nombre)
                                <option value="{{ $key }}" @if(request('tipo') == $key) selected @endif>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="fecha_desde" class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                        <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filtrar
                        </button>
                        <a href="{{ route('admin.novedades.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-sm font-medium">Total Acciones</p>
                            <h3 class="text-3xl font-bold mt-2">{{ number_format($totalAcciones) }}</h3>
                        </div>
                        <svg class="w-12 h-12 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>

                @php
                    $colors = ['purple', 'cyan', 'blue'];
                    $colorIndex = 0;
                @endphp

                @foreach($accionesPorTipo as $tipo => $total)
                    @if($loop->index < 3)
                        @php
                            $color = $colors[$colorIndex++ % count($colors)];
                        @endphp
                        <div class="bg-gradient-to-br from-{{ $color }}-500 to-{{ $color }}-600 rounded-lg shadow-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-{{ $color }}-100 text-sm font-medium">{{ \App\Models\Accion::tipos()[$tipo] ?? $tipo }}</p>
                                    <h3 class="text-3xl font-bold mt-2">{{ number_format($total) }}</h3>
                                </div>
                                <svg class="w-12 h-12 text-{{ $color }}-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Lista de acciones recientes -->
            @if($acciones->count() > 0)
                <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Listado de Acciones
                </h2>

                <div class="space-y-4 mb-6">
                    @foreach($acciones as $accion)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                            <div class="flex flex-col md:flex-row">
                                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-6 text-white md:w-48 flex-shrink-0">
                                    <div class="text-center">
                                        <p class="text-indigo-100 text-sm">{{ $accion->dia->format('d/m/Y') }}</p>
                                        <p class="text-2xl font-bold">{{ \Carbon\Carbon::parse($accion->hora)->format('H:i') }}</p>
                                        <div class="mt-3">
                                            <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-xs font-medium">
                                                {{ $accion->nombre_tipo }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-6 flex-1">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-gray-600 text-sm">Usuario</p>
                                            <p class="font-semibold text-gray-800">{{ $accion->user->nombre_completo }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 text-sm">Sucursal</p>
                                            <p class="font-semibold text-gray-800">{{ $accion->sucursal->nombre }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 text-sm">Sector</p>
                                            <p class="font-semibold text-gray-800">{{ $accion->sector?->nombre ?? 'N/A' }}</p>
                                        </div>
                                        <div class="flex items-end justify-end">
                                            <a href="{{ route('acciones.show', $accion) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition text-sm">
                                                Ver Detalle
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="mt-6">
                    {{ $acciones->appends(request()->query())->links() }}
                </div>
            @else
                <div class="bg-gray-50 border-l-4 border-gray-400 p-6 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-12 h-12 text-gray-400 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <div>
                            <h4 class="text-lg font-bold text-gray-700">No se encontraron acciones</h4>
                            <p class="text-gray-600">Intenta ajustar los filtros de búsqueda.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
