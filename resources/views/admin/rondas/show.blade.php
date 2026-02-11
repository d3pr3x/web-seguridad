@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:ml-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <div class="mb-6">
                <div class="flex items-center text-sm text-gray-600 mb-2">
                    <a href="{{ route('admin.rondas.index') }}" class="hover:text-emerald-600 flex items-center">Sucursales</a>
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    <span>{{ $sucursal->nombre }}</span>
                </div>
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h1 class="text-3xl font-bold text-gray-800">Puntos de ronda – {{ $sucursal->nombre }}</h1>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.rondas.create', ['sucursal_id' => $sucursal->id]) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Nuevo punto
                        </a>
                        <a href="{{ route('admin.rondas.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">Volver</a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6"><p class="text-green-700 font-medium">{{ session('success') }}</p></div>
            @endif

            @if($puntos->count() > 0)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orden</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sector</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($puntos as $punto)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $punto->orden }}</td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $punto->nombre }}</div>
                                            @if($punto->descripcion)
                                                <div class="text-xs text-gray-500">{{ Str::limit($punto->descripcion, 40) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">{{ $punto->codigo }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $punto->sector?->nombre ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $punto->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $punto->activo ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('admin.rondas.qr.show', $punto) }}" target="_blank" class="text-emerald-600 hover:text-emerald-800" title="Ver QR">QR</a>
                                                <a href="{{ route('admin.rondas.qr.download', $punto) }}" class="text-blue-600 hover:text-blue-800" title="Descargar PNG">Descargar</a>
                                                <a href="{{ route('admin.rondas.edit', $punto) }}" class="text-gray-600 hover:text-gray-800">Editar</a>
                                                <form action="{{ route('admin.rondas.destroy', $punto) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este punto de ronda?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">Eliminar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-6">{{ $puntos->links() }}</div>
            @else
                <div class="bg-gray-50 border-l-4 border-gray-400 p-6 rounded-lg flex items-center justify-between">
                    <p class="text-gray-700">No hay puntos de ronda. Cree el primero para generar códigos QR.</p>
                    <a href="{{ route('admin.rondas.create', ['sucursal_id' => $sucursal->id]) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">Crear punto</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
