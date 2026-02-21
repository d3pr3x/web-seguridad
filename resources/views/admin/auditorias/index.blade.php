@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Auditorías (solo lectura)</h1>

            <form method="GET" class="bg-white rounded-lg shadow p-4 mb-6 flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tabla</label>
                    <select name="tabla" class="rounded border-gray-300">
                        <option value="">Todas</option>
                        @foreach($tablas as $t)
                            <option value="{{ $t }}" {{ request('tabla') === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Acción</label>
                    <input type="text" name="accion" value="{{ request('accion') }}" placeholder="create, update, delete..." class="rounded border-gray-300 w-40">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                    <input type="date" name="desde" value="{{ request('desde') }}" class="rounded border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                    <input type="date" name="hasta" value="{{ request('hasta') }}" class="rounded border-gray-300">
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Filtrar</button>
            </form>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tabla</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID registro</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($auditorias as $a)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-sm text-gray-600">{{ $a->ocurrido_en?->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-2 text-sm">{{ $a->usuario?->nombre_completo ?? '—' }}</td>
                                <td class="px-4 py-2 text-sm">{{ $a->accion }}</td>
                                <td class="px-4 py-2 text-sm">{{ $a->tabla }}</td>
                                <td class="px-4 py-2 text-sm">{{ $a->registro_id ?? '—' }}</td>
                                <td class="px-4 py-2"><a href="{{ route('admin.auditorias.show', $a) }}" class="text-indigo-600 hover:underline text-sm">Ver</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No hay registros de auditoría.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-4 py-2 border-t">{{ $auditorias->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
