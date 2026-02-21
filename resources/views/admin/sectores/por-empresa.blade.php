@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <!-- Breadcrumb -->
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.sectores.index') }}" class="hover:text-cyan-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Empresas
                </a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span>{{ $empresa->nombre }}</span>
            </div>

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Instalaciones de {{ $empresa->nombre }}
                </h1>
                <p class="text-gray-600 mt-1">Seleccione una instalación para gestionar sus sectores.</p>
            </div>

            @if($instalaciones->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($instalaciones as $instalacion)
                        <a href="{{ route('admin.sectores.show', $instalacion) }}" class="block">
                            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden border-l-4 {{ $instalacion->activa ? 'border-cyan-500' : 'border-gray-400' }}">
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $instalacion->nombre }}</h3>
                                            @if($instalacion->codigo)
                                                <p class="text-sm text-gray-600">Código: {{ $instalacion->codigo }}</p>
                                            @endif
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $instalacion->activa ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $instalacion->activa ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </div>
                                    @if($instalacion->direccion)
                                        <p class="text-sm text-gray-600 mb-2 truncate" title="{{ $instalacion->direccion }}">{{ $instalacion->direccion }}</p>
                                    @endif
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <span class="font-semibold text-cyan-600">{{ $instalacion->sectores_count }} {{ $instalacion->sectores_count == 1 ? 'sector' : 'sectores' }}</span>
                                            <span class="text-sm text-cyan-600">Gestionar sectores →</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-6">{{ $instalaciones->links() }}</div>
            @else
                <div class="bg-gray-50 border-l-4 border-gray-400 p-6 rounded-lg">
                    <p class="text-gray-700">Esta empresa no tiene instalaciones. Agregue instalaciones desde <a href="{{ route('admin.clientes.instalaciones', $empresa) }}" class="text-cyan-600 hover:underline">Clientes → {{ $empresa->nombre }}</a>.</p>
                    <a href="{{ route('admin.sectores.index') }}" class="mt-3 inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Volver a empresas</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
