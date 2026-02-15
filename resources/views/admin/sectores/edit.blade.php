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
        <div class="container mx-auto px-4 py-6 max-w-4xl">
            <!-- Breadcrumb -->
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.sectores.index') }}" class="hover:text-cyan-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Sucursales
                </a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('admin.sectores.show', $sucursal) }}" class="hover:text-cyan-600">
                    {{ $sucursal->nombre }}
                </a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span>Editar Sector</span>
            </div>

            <!-- Título -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar Sector
                </h1>
                <p class="text-gray-600 mt-1">{{ $sector->nombre }} - {{ $sucursal->nombre }}</p>
            </div>

            <!-- Formulario -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('admin.sectores.update', $sector) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Información de la Sucursal (solo lectura) -->
                    <div class="bg-cyan-50 border-l-4 border-cyan-500 p-4 mb-6 rounded-r-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-cyan-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-cyan-800">{{ $sucursal->nombre }}</p>
                                @if($sucursal->empresa)
                                    <p class="text-sm text-cyan-700">{{ $sucursal->empresa }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div class="mb-6">
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Sector <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               value="{{ old('nombre', $sector->nombre) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('nombre') border-red-500 @enderror"
                               placeholder="Ej: Recepción, Vigilancia, Administración"
                               required>
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="mb-6">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción
                        </label>
                        <textarea id="descripcion" 
                                  name="descripcion" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('descripcion') border-red-500 @enderror"
                                  placeholder="Descripción opcional del sector...">{{ old('descripcion', $sector->descripcion) }}</textarea>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado Activo -->
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="activo" 
                                   name="activo" 
                                   {{ old('activo', $sector->activo) ? 'checked' : '' }}
                                   class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                            <label for="activo" class="ml-2 block text-sm text-gray-700">
                                Sector activo
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Los sectores inactivos no estarán disponibles para su uso</p>
                    </div>

                    <!-- Información adicional -->
                    <div class="bg-gray-50 border-l-4 border-gray-400 p-4 mb-6 rounded-r-lg">
                        <p class="text-sm text-gray-600">
                            <strong>Creado:</strong> {{ $sector->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            <strong>Última actualización:</strong> {{ $sector->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.sectores.show', $sucursal) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                            Cancelar
                        </a>
                        <button type="submit" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Actualizar Sector
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

