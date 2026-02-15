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
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Historial de Novedades</h1>
                <p class="text-gray-600 text-sm md:text-base">Todas las novedades que has registrado</p>
            </div>

            <!-- Mensajes -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="font-medium text-sm md:text-base">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filtros</h3>
                <form method="GET" action="{{ route('usuario.acciones.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Novedad</label>
                            <select name="tipo" id="tipo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todas</option>
                                <option value="inicio_servicio" {{ request('tipo') == 'inicio_servicio' ? 'selected' : '' }}>Inicio del Servicio</option>
                                <option value="rondas" {{ request('tipo') == 'rondas' ? 'selected' : '' }}>Rondas</option>
                                <option value="constancias" {{ request('tipo') == 'constancias' ? 'selected' : '' }}>Constancias</option>
                                <option value="concurrencia_autoridades" {{ request('tipo') == 'concurrencia_autoridades' ? 'selected' : '' }}>Concurrencia de autoridades</option>
                                <option value="concurrencia_servicios" {{ request('tipo') == 'concurrencia_servicios' ? 'selected' : '' }}>Concurrencia Servicios</option>
                                <option value="entrega_servicio" {{ request('tipo') == 'entrega_servicio' ? 'selected' : '' }}>Entrega del Servicio</option>
                            </select>
                        </div>
                        <div>
                            <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                            <input type="date" name="fecha" id="fecha" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ request('fecha') }}">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filtrar
                            </button>
                            @if(request()->hasAny(['tipo', 'fecha']))
                                <a href="{{ route('usuario.acciones.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition">
                                    Limpiar
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Lista de Novedades -->
            @if($acciones->count() > 0)
                <!-- Vista Desktop: Tabla -->
                <div class="hidden md:block bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Hora</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Sector</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($acciones as $accion)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $accion->dia->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($accion->hora)->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $accion->nombre_tipo }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $accion->sector?->nombre ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('usuario.acciones.show', $accion) }}" class="text-blue-600 hover:text-blue-900 transition">
                                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Vista Móvil: Cards -->
                <div class="md:hidden space-y-4">
                    @foreach($acciones as $accion)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="bg-blue-50 px-4 py-3 border-l-4 border-blue-500">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-blue-900">{{ $accion->nombre_tipo }}</h3>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $accion->dia->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4 space-y-2">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <strong>Hora:</strong> <span class="ml-1">{{ \Carbon\Carbon::parse($accion->hora)->format('H:i') }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <strong>Sector:</strong> <span class="ml-1">{{ $accion->sector?->nombre ?? 'N/A' }}</span>
                                </div>
                                <div class="pt-3">
                                    <a href="{{ route('usuario.acciones.show', $accion) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2 rounded-md transition">
                                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver Detalle
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="mt-6 bg-white rounded-lg shadow-md p-4">
                    {{ $acciones->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-8 md:p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-2">No hay novedades registradas</h3>
                    <p class="text-sm md:text-base text-gray-500 mb-6">Comienza registrando tu primera novedad del servicio.</p>
                    <a href="{{ route('usuario.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ir al Portal
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
