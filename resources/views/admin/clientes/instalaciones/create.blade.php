@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />
        <div class="container mx-auto px-4 py-6 max-w-4xl">
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.clientes.index') }}" class="hover:text-indigo-600">Clientes</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <a href="{{ route('admin.clientes.instalaciones', $cliente) }}" class="hover:text-indigo-600">{{ $cliente->nombre }}</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span>Nueva instalación</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Nueva instalación en {{ $cliente->nombre }}</h1>

            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-r-lg mb-6">
                <p class="text-indigo-800 font-medium">{{ $cliente->nombre }}</p>
                <p class="text-indigo-700 text-sm">La instalación heredará la jerarquía de esta empresa.</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('admin.clientes.instalaciones.store', $cliente) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la instalación *</label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('nombre') border-red-500 @enderror" required>
                            @error('nombre')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="codigo" class="block text-sm font-medium text-gray-700 mb-1">Código *</label>
                            <input type="text" id="codigo" name="codigo" value="{{ old('codigo') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('codigo') border-red-500 @enderror" required>
                            @error('codigo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                            <input type="text" id="direccion" name="direccion" value="{{ old('direccion') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('direccion') border-red-500 @enderror" required>
                            @error('direccion')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="comuna" class="block text-sm font-medium text-gray-700 mb-1">Comuna</label>
                            <input type="text" id="comuna" name="comuna" value="{{ old('comuna') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="ciudad" class="block text-sm font-medium text-gray-700 mb-1">Ciudad *</label>
                            <input type="text" id="ciudad" name="ciudad" value="{{ old('ciudad') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('ciudad') border-red-500 @enderror" required>
                            @error('ciudad')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="region" class="block text-sm font-medium text-gray-700 mb-1">Región *</label>
                            <input type="text" id="region" name="region" value="{{ old('region') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('region') border-red-500 @enderror" required>
                            @error('region')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="activa" value="1" {{ old('activa', true) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Instalación activa</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.clientes.instalaciones', $cliente) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">Crear instalación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
