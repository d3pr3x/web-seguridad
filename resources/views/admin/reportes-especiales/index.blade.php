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
                    <svg class="w-8 h-8 mr-3 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Todos los Reportes Especiales
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
                <form method="GET" action="{{ route('admin.reportes-especiales.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="sucursal_id" class="block text-sm font-medium text-gray-700 mb-2">Sucursal</label>
                        <select name="sucursal_id" id="sucursal_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                            <option value="">Todas las sucursales</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}" @if(request('sucursal_id') == $sucursal->id) selected @endif>
                                    {{ $sucursal->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Reporte</label>
                        <select name="tipo" id="tipo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                            <option value="">Todos</option>
                            @foreach($tipos as $key => $nombre)
                                <option value="{{ $key }}" @if(request('tipo') == $key) selected @endif>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select name="estado" id="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                            <option value="">Todos</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado }}" @if(request('estado') == $estado) selected @endif>
                                    {{ ucfirst(str_replace('_', ' ', $estado)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="px-6 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filtrar
                        </button>
                        <a href="{{ route('admin.reportes-especiales.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Contadores compactos -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-pink-500 rounded-lg shadow p-3 text-white">
                    <p class="text-pink-100 text-xs font-medium">Total</p>
                    <p class="text-xl font-bold">{{ number_format($totalReportes) }}</p>
                </div>
                @if(isset($reportesPorEstado['pendiente']))
                <div class="bg-yellow-500 rounded-lg shadow p-3 text-white">
                    <p class="text-yellow-100 text-xs font-medium">Pendientes</p>
                    <p class="text-xl font-bold">{{ number_format($reportesPorEstado['pendiente']) }}</p>
                </div>
                @endif
                @if(isset($reportesPorEstado['en_revision']))
                <div class="bg-blue-500 rounded-lg shadow p-3 text-white">
                    <p class="text-blue-100 text-xs font-medium">En Revisión</p>
                    <p class="text-xl font-bold">{{ number_format($reportesPorEstado['en_revision']) }}</p>
                </div>
                @endif
                @if(isset($reportesPorEstado['completado']))
                <div class="bg-green-500 rounded-lg shadow p-3 text-white">
                    <p class="text-green-100 text-xs font-medium">Completados</p>
                    <p class="text-xl font-bold">{{ number_format($reportesPorEstado['completado']) }}</p>
                </div>
                @endif
            </div>

            <!-- Listado: cards compactas en grid -->
            @if($reportes->count() > 0)
                <h2 class="text-lg font-bold text-gray-800 mb-3">Listado de Reportes Especiales</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3 mb-4">
                    @foreach($reportes as $reporte)
                        @php
                            $borderColor = match($reporte->tipo) {
                                'incidentes' => 'border-yellow-500',
                                'denuncia' => 'border-red-500',
                                'detenido' => 'border-purple-500',
                                default => 'border-orange-500',
                            };
                        @endphp
                        <div class="bg-white rounded-lg shadow border-l-4 {{ $borderColor }} overflow-hidden hover:shadow-md transition">
                            <div class="p-3">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-700">
                                            {{ $reporte->dia->format('d/m/Y') }} · {{ \Carbon\Carbon::parse($reporte->hora)->format('H:i') }}
                                        </p>
                                        <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded
                                            @if($reporte->estado == 'pendiente') bg-yellow-100 text-yellow-800
                                            @elseif($reporte->estado == 'en_revision') bg-blue-100 text-blue-800
                                            @elseif($reporte->estado == 'completado') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $reporte->estado)) }}
                                        </span>
                                    </div>
                                    <a href="{{ route('admin.reportes-especiales.show', $reporte) }}" class="flex-shrink-0 px-3 py-1.5 bg-pink-600 hover:bg-pink-700 text-white rounded text-sm whitespace-nowrap">
                                        Ver detalle
                                    </a>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 text-sm">
                                    <div>
                                        <span class="text-gray-500">Usuario:</span>
                                        <span class="font-medium text-gray-800 truncate-2lines block">{{ $reporte->user->nombre_completo ?? '—' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Sucursal:</span>
                                        <span class="font-medium text-gray-800 truncate-2lines block">{{ $reporte->sucursal?->nombre ?? '—' }}</span>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span class="text-gray-500">Tipo:</span>
                                        <span class="font-medium text-gray-800">{{ $reporte->nombre_tipo }}</span>
                                    </div>
                                    @if(trim($reporte->novedad ?? '') !== '' || trim($reporte->accion ?? '') !== '')
                                    <div class="sm:col-span-2">
                                        <span class="text-gray-500">Resumen:</span>
                                        <p class="text-gray-700 truncate-2lines mt-0.5">{{ Str::limit($reporte->novedad ?: $reporte->accion ?: '—', 80) }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $reportes->links() }}
                </div>
            @else
                <div class="bg-gray-50 border-l-4 border-gray-400 p-6 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-12 h-12 text-gray-400 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <h4 class="text-lg font-bold text-gray-700">No se encontraron reportes especiales</h4>
                            <p class="text-gray-600">Intenta ajustar los filtros de búsqueda.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<style>
.truncate-2lines {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    line-clamp: 2;
}
</style>
@endsection
