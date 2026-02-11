@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:ml-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Gestión de Usuarios
                </h1>
                <a href="{{ route('admin.usuarios.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nuevo usuario
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <form method="GET" action="{{ route('admin.usuarios.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label for="buscar" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input type="text" id="buscar" name="buscar" value="{{ request('buscar') }}"
                               placeholder="Nombre, apellido, email o RUT"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                    <div class="w-48">
                        <label for="sucursal_id" class="block text-sm font-medium text-gray-700 mb-1">Sucursal</label>
                        <select id="sucursal_id" name="sucursal_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                            <option value="">Todas</option>
                            @foreach($sucursales as $s)
                                <option value="{{ $s->id }}" {{ request('sucursal_id') == $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-48">
                        <label for="perfil" class="block text-sm font-medium text-gray-700 mb-1">Perfil</label>
                        <select id="perfil" name="perfil" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                            <option value="">Todos</option>
                            <option value="1" {{ request('perfil') === '1' ? 'selected' : '' }}>Administrador</option>
                            <option value="2" {{ request('perfil') === '2' ? 'selected' : '' }}>Supervisor</option>
                            <option value="3" {{ request('perfil') === '3' ? 'selected' : '' }}>Supervisor-Usuario</option>
                            <option value="4" {{ request('perfil') === '4' ? 'selected' : '' }}>Usuario</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800">Filtrar</button>
                    <a href="{{ route('admin.usuarios.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Limpiar</a>
                </form>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nombre</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">RUT / Email</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Perfil</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Sucursal</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($usuarios as $u)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <span class="font-medium text-gray-900">{{ $u->nombre_completo }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        <div>{{ $u->rut }}</div>
                                        <div class="text-gray-500">{{ $u->email }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($u->perfil == 1) bg-red-100 text-red-800
                                            @elseif($u->perfil == 2) bg-purple-100 text-purple-800
                                            @elseif($u->perfil == 3) bg-indigo-100 text-indigo-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                            {{ $u->nombre_perfil }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $u->nombre_sucursal }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('admin.usuarios.edit', $u) }}" class="text-red-600 hover:text-red-800 font-medium">Editar</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">No hay usuarios que coincidan con los filtros.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($usuarios->hasPages())
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                        {{ $usuarios->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
