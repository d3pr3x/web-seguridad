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
        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <!-- Header de Página -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Historial Completo</h1>
                <p class="text-gray-600">Todas tus novedades y reportes en un solo lugar</p>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <form method="GET" action="{{ route('usuario.historial.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Registro</label>
                        <select name="tipo_registro" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="novedad" {{ request('tipo_registro') == 'novedad' ? 'selected' : '' }}>Novedades</option>
                            <option value="reporte" {{ request('tipo_registro') == 'reporte' ? 'selected' : '' }}>Reportes</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Desde</label>
                        <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hasta</label>
                        <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
                            <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filtrar
                        </button>
                        @if(request()->hasAny(['tipo_registro', 'fecha_desde', 'fecha_hasta']))
                            <a href="{{ route('usuario.historial.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition">
                                Limpiar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Registros</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $registros->count() }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Novedades</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $registros->where('tipo_registro', 'novedad')->count() }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Reportes</p>
                            <p class="text-2xl font-bold text-red-600">{{ $registros->where('tipo_registro', 'reporte')->count() }}</p>
                        </div>
                        <div class="bg-red-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Registros -->
            @if($registros->isEmpty())
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay registros</h3>
                    <p class="text-gray-500">Aún no has creado ninguna novedad o reporte</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($registros as $registro)
                        <a href="{{ $registro['ruta_detalle'] }}" class="block bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                            <div class="flex items-start p-4">
                                <!-- Icono y Color -->
                                <div class="flex-shrink-0 mr-4">
                                    <div class="bg-{{ $registro['color'] }}-100 rounded-full p-3">
                                        <svg class="w-6 h-6 text-{{ $registro['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $registro['icono'] }}"></path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Contenido -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $registro['nombre_tipo'] }}</h3>
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $registro['tipo_registro'] == 'novedad' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $registro['tipo_registro'] == 'novedad' ? 'Novedad' : 'Reporte' }}
                                                </span>
                                                @if($registro['tipo_registro'] == 'reporte' && isset($registro['estado_badge']))
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                        bg-{{ $registro['estado_badge']['color'] }}-100 text-{{ $registro['estado_badge']['color'] }}-800">
                                                        {{ $registro['estado_badge']['texto'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600">
                                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($registro['dia'])->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($registro['hora'])->format('H:i') }}
                                            </p>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-700 line-clamp-2">{{ $registro['descripcion'] }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


