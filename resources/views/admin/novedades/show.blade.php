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
            @if(session('info'))
                <div class="mb-4 p-4 rounded-lg bg-blue-50 border border-blue-200 text-blue-800">{{ session('info') }}</div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-2xl font-bold text-gray-800">Detalle de novedad</h1>
                <div class="flex gap-2">
                    <a href="{{ route('admin.novedades.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">Volver al listado</a>
                    @if(auth()->user()->esSupervisorUsuario() || auth()->user()->esSupervisor() || auth()->user()->esAdministrador())
                        @if(!$accion->reporteEspecial)
                            <form action="{{ route('admin.novedades.elevar-reporte', $accion) }}" method="post" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">Elevar a reporte</button>
                            </form>
                        @else
                            <a href="{{ route('admin.reportes-especiales.show', $accion->reporteEspecial) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">Ver reporte generado</a>
                        @endif
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Tipo de acción</p>
                            <p class="font-medium">{{ $accion->nombre_tipo }}</p>
                        </div>
                        @if($accion->tipo_hecho)
                        <div>
                            <p class="text-sm text-gray-500">Tipo de hecho</p>
                            <p class="font-medium">{{ $accion->nombre_hecho }}</p>
                        </div>
                        @endif
                        @if($accion->importancia)
                        <div>
                            <p class="text-sm text-gray-500">Importancia</p>
                            <p class="font-medium">{{ $accion->nombre_importancia }}</p>
                        </div>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Fecha / Hora</p>
                            <p>{{ $accion->dia->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($accion->hora)->format('H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Usuario</p>
                            <p>{{ $accion->user->nombre_completo }} ({{ $accion->user->run }})</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Instalación</p>
                            <p>{{ $accion->sucursal->nombre }}</p>
                        </div>
                        @if($accion->sector)
                        <div>
                            <p class="text-sm text-gray-500">Sector</p>
                            <p>{{ $accion->sector->nombre }}</p>
                        </div>
                        @endif
                    </div>
                    @if($accion->novedad)
                    <div>
                        <p class="text-sm text-gray-500">Novedad</p>
                        <p class="border p-3 bg-gray-50 rounded">{{ $accion->novedad }}</p>
                    </div>
                    @endif
                    @if($accion->accion)
                    <div>
                        <p class="text-sm text-gray-500">Acción</p>
                        <p class="border p-3 bg-gray-50 rounded">{{ $accion->accion }}</p>
                    </div>
                    @endif
                    @if($accion->resultado)
                    <div>
                        <p class="text-sm text-gray-500">Resultado</p>
                        <p class="border p-3 bg-gray-50 rounded">{{ $accion->resultado }}</p>
                    </div>
                    @endif
                    @if($accion->imagenes && count($accion->imagenes) > 0)
                    <div>
                        <p class="text-sm text-gray-500 mb-2">Imágenes</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($accion->imagenes as $imagen)
                                <a href="{{ asset('storage/' . $imagen) }}" target="_blank" class="block">
                                    <img src="{{ asset('storage/' . $imagen) }}" alt="Evidencia" class="w-24 h-24 object-cover rounded border">
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <p class="text-gray-400 text-sm pt-2">Registrado: {{ $accion->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
