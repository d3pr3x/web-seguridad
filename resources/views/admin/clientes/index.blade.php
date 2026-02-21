@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Clientes (Empresas e instalaciones)
                </h1>
                <a href="{{ route('admin.clientes.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nueva empresa
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6"><p class="text-green-700 font-medium">{{ session('success') }}</p></div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6"><p class="text-red-700 font-medium">{{ session('error') }}</p></div>
            @endif

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
                <p class="text-blue-800 text-sm">Las <strong>empresas</strong> son los clientes. Cada empresa tiene <strong>instalaciones</strong> (sucursales). Desde aquí se crean y editan empresas e instalaciones.</p>
            </div>

            <form method="get" class="flex flex-wrap gap-4 items-center mb-6 p-4 bg-white rounded-lg border border-gray-200">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="incluir_inactivos" value="1" {{ request('incluir_inactivos') ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-700">Incluir inactivos</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="incluir_borrados" value="1" {{ request('incluir_borrados') ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-700">Incluir borrados</span>
                </label>
            </form>

            @if($empresas->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($empresas as $empresa)
                        @php $borrado = $empresa->trashed(); @endphp
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden border-l-4 {{ $borrado ? 'border-gray-400 bg-gray-50' : ($empresa->activa ? 'border-indigo-500' : 'border-gray-400') }}">
                            <div class="p-6">
                                @if($borrado)
                                    <p class="text-xs font-semibold text-amber-700 mb-2">Registro histórico (borrado)</p>
                                @endif
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $empresa->nombre }}</h3>
                                        @if($empresa->codigo)
                                            <p class="text-sm text-gray-600">Código: {{ $empresa->codigo }}</p>
                                        @endif
                                        @if($empresa->modalidad)
                                            <p class="text-sm text-gray-500">Modalidad: {{ $empresa->modalidad->nombre }}</p>
                                        @endif
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $empresa->activa ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $empresa->activa ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-200 flex flex-wrap gap-2">
                                    @if(!$borrado)
                                        <a href="{{ route('admin.clientes.instalaciones', $empresa) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-800 rounded-lg text-sm font-medium hover:bg-indigo-200">Instalaciones ({{ $empresa->sucursales_count }})</a>
                                        <a href="{{ route('admin.clientes.edit', $empresa) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">Editar</a>
                                        <form action="{{ route('admin.clientes.destroy', $empresa) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta empresa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-sm hover:bg-red-100">Eliminar</button>
                                        </form>
                                    @else
                                        <span class="text-sm text-gray-500">Solo lectura (histórico)</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">{{ $empresas->links() }}</div>
            @else
                <div class="bg-gray-50 border-l-4 border-gray-400 p-6 rounded-lg">
                    <p class="text-gray-700">No hay empresas. Cree una empresa para asociar instalaciones.</p>
                    <a href="{{ route('admin.clientes.create') }}" class="mt-3 inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Crear empresa</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
