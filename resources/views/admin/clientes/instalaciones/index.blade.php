@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.clientes.index') }}" class="hover:text-indigo-600">Clientes</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span>{{ $cliente->nombre }}</span>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-3xl font-bold text-gray-800">Instalaciones de {{ $cliente->nombre }}</h1>
                <div class="flex gap-2">
                    <a href="{{ route('admin.clientes.instalaciones.create', $cliente) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Nueva instalación
                    </a>
                    <a href="{{ route('admin.clientes.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">Volver a clientes</a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6"><p class="text-green-700 font-medium">{{ session('success') }}</p></div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6"><p class="text-red-700 font-medium">{{ session('error') }}</p></div>
            @endif

            @if($instalaciones->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($instalaciones as $instalacion)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden border-l-4 {{ $instalacion->activa ? 'border-indigo-500' : 'border-gray-400' }}">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $instalacion->nombre }}</h3>
                                        <p class="text-sm text-gray-600">Código: {{ $instalacion->codigo }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $instalacion->activa ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $instalacion->activa ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </div>
                                @if($instalacion->direccion)
                                    <p class="text-sm text-gray-600 mb-2 truncate" title="{{ $instalacion->direccion }}">{{ $instalacion->direccion }}</p>
                                @endif
                                <div class="mt-4 pt-4 border-t border-gray-200 flex flex-wrap gap-2">
                                    <a href="{{ route('admin.sectores.show', $instalacion) }}" class="inline-flex items-center px-3 py-1.5 bg-cyan-100 text-cyan-800 rounded-lg text-sm font-medium hover:bg-cyan-200">
                                        Sectores ({{ $instalacion->sectores_count }})
                                    </a>
                                    <a href="{{ route('admin.clientes.instalaciones.edit', [$cliente, $instalacion]) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">Editar</a>
                                    <form action="{{ route('admin.clientes.instalaciones.destroy', [$cliente, $instalacion]) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta instalación?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-sm hover:bg-red-100">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">{{ $instalaciones->links() }}</div>
            @else
                <div class="bg-gray-50 border-l-4 border-gray-400 p-6 rounded-lg">
                    <p class="text-gray-700">Esta empresa no tiene instalaciones. Agregue una instalación (sucursal) para poder gestionar sectores y asignar usuarios.</p>
                    <a href="{{ route('admin.clientes.instalaciones.create', $cliente) }}" class="mt-3 inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Crear instalación</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
