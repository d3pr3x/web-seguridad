@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />
        <div class="container mx-auto px-4 py-6 max-w-7xl">
            @if(session('success'))
                <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">{{ session('success') }}</div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-2xl font-bold text-gray-800">Detalle de reporte especial</h1>
                <div class="flex gap-2 flex-wrap">
                    <a href="{{ route('admin.reportes-especiales.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">Volver al listado</a>
                    @if(auth()->user()->esSupervisorUsuario() || auth()->user()->esSupervisor() || auth()->user()->esAdministrador())
                        @if(!$reporteEspecial->fueLeido())
                            <form action="{{ route('admin.reportes-especiales.marcar-leido', $reporteEspecial) }}" method="post" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition">Marcar como leído</button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="p-6 space-y-4">
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">{{ $reporteEspecial->nombre_tipo }}</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($reporteEspecial->estado === 'completado') bg-green-100 text-green-800
                            @elseif($reporteEspecial->estado === 'rechazado') bg-red-100 text-red-800
                            @elseif($reporteEspecial->estado === 'en_revision') bg-blue-100 text-blue-800
                            @else bg-amber-100 text-amber-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $reporteEspecial->estado)) }}
                        </span>
                        @if($reporteEspecial->fueLeido())
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800" title="Leído por {{ $reporteEspecial->leidoPor->nombre_completo ?? 'N/A' }} el {{ $reporteEspecial->fecha_lectura?->format('d/m/Y H:i') }}">
                                ✓ Leído
                            </span>
                        @endif
                    </div>
                    @if($reporteEspecial->fueLeido() && $reporteEspecial->leidoPor)
                        <p class="text-sm text-gray-500">Leído por <strong>{{ $reporteEspecial->leidoPor->nombre_completo }}</strong> el {{ $reporteEspecial->fecha_lectura->format('d/m/Y H:i') }}</p>
                    @endif
                    @if($reporteEspecial->accionOrigen)
                        <p class="text-sm text-gray-500">Elevado desde novedad (acción #{{ $reporteEspecial->accion_id }})</p>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><p class="text-sm text-gray-500">Fecha / Hora</p><p>{{ $reporteEspecial->dia->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($reporteEspecial->hora)->format('H:i') }}</p></div>
                        <div><p class="text-sm text-gray-500">Usuario</p><p>{{ $reporteEspecial->user->nombre_completo }} ({{ $reporteEspecial->user->run }})</p></div>
                        <div><p class="text-sm text-gray-500">Instalación</p><p>{{ $reporteEspecial->sucursal->nombre }}</p></div>
                        @if($reporteEspecial->sector)<div><p class="text-sm text-gray-500">Sector</p><p>{{ $reporteEspecial->sector->nombre }}</p></div>@endif
                    </div>
                    <div><p class="text-sm text-gray-500">Novedad</p><div class="border p-3 bg-gray-50 rounded">{{ $reporteEspecial->novedad }}</div></div>
                    <div><p class="text-sm text-gray-500">Acción tomada</p><div class="border p-3 bg-gray-50 rounded">{{ $reporteEspecial->accion }}</div></div>
                    @if($reporteEspecial->resultado)<div><p class="text-sm text-gray-500">Resultado</p><div class="border p-3 bg-gray-50 rounded">{{ $reporteEspecial->resultado }}</div></div>@endif
                    @if($reporteEspecial->comentarios_admin)
                        <div><p class="text-sm text-gray-500">Comentario interno (no se incluye en PDF)</p><div class="border p-3 bg-amber-50 rounded">{{ $reporteEspecial->comentarios_admin }}</div></div>
                    @endif
                    @if($reporteEspecial->imagenes && count($reporteEspecial->imagenes) > 0)
                        <div><p class="text-sm text-gray-500 mb-2">Imágenes</p><div class="flex flex-wrap gap-2">
                            @foreach($reporteEspecial->imagenes as $imagen)
                                <a href="{{ route('archivos-privados.reporte-especial', [$reporteEspecial, $loop->index]) }}" target="_blank"><img src="{{ route('archivos-privados.reporte-especial', [$reporteEspecial, $loop->index]) }}" alt="Evidencia" class="w-24 h-24 object-cover rounded border"></a>
                            @endforeach
                        </div></div>
                    @endif
                </div>
            </div>

            @if(auth()->user()->esSupervisor() || auth()->user()->esAdministrador())
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Actualizar estado y comentario interno</h3>
                <form method="POST" action="{{ route('admin.reportes-especiales.update-estado', $reporteEspecial) }}">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <select name="estado" id="estado" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                                <option value="pendiente" {{ $reporteEspecial->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="en_revision" {{ $reporteEspecial->estado == 'en_revision' ? 'selected' : '' }}>En revisión</option>
                                <option value="completado" {{ $reporteEspecial->estado == 'completado' ? 'selected' : '' }}>Completado</option>
                                <option value="rechazado" {{ $reporteEspecial->estado == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="comentarios_admin" class="block text-sm font-medium text-gray-700 mb-1">Comentario interno</label>
                        <textarea name="comentarios_admin" id="comentarios_admin" class="w-full px-3 py-2 border border-gray-300 rounded-lg" rows="3">{{ $reporteEspecial->comentarios_admin }}</textarea>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">Actualizar estado</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
