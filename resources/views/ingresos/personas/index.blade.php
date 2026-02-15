@extends('layouts.usuario')

@section('content')
<div class="min-h-screen flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:ml-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-7xl">
            @if(session('success'))
                <div class="mb-4 p-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h1 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-10 h-10 rounded-xl flex items-center justify-center text-teal-600 shrink-0" style="background: rgba(15, 118, 110, 0.12);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </span>
                    Base de personas
                </h1>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('ingresos.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium border transition bg-white hover:bg-slate-50" style="border-color: var(--app-border); color: var(--app-text);">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Volver a ingresos
                    </a>
                    <a href="{{ route('personas.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium text-white transition shadow-sm" style="background: var(--app-primary);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Agregar persona
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <div class="px-5 py-4 border-b border-slate-100" style="background: var(--app-surface);">
                    <p class="text-sm text-slate-600 mb-3">Registro histórico: se agregan aquí automáticamente las personas que se registran en ingresos (escaner), los guardias/usuarios al darlos de alta, y las que agregue manualmente. Sirve para completar el nombre al escanear un RUT y para consultar quién ha pasado por el sistema.</p>
                    <form method="get" action="{{ route('personas.index') }}" class="flex flex-wrap gap-3 items-center">
                        <label class="text-sm font-medium text-slate-700">Buscar</label>
                        <input type="text" name="buscar" class="px-3 py-2 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition max-w-xs" style="border-color: var(--app-border);" placeholder="Nombre, RUT, empresa…" value="{{ request('buscar') }}">
                        <button type="submit" class="px-4 py-2 rounded-xl font-medium text-white transition" style="background: var(--app-primary);">Buscar</button>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200" style="background: var(--app-surface);">
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">RUT</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Nombre</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Teléfono</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Email</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Empresa</th>
                                <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($personas as $p)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-3.5 text-sm font-medium text-slate-800">{{ $p->rut }}</td>
                                <td class="px-5 py-3.5 text-sm text-slate-800">{{ $p->nombre }}</td>
                                <td class="px-5 py-3.5 text-sm text-slate-600">{{ $p->telefono ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-sm text-slate-600">{{ $p->email ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-sm text-slate-600">{{ $p->empresa ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('personas.edit', $p->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 transition">Editar</a>
                                    <form action="{{ route('personas.destroy', $p->id) }}" method="post" class="inline" onsubmit="return confirm('¿Eliminar esta persona?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-500">No hay personas registradas. Agregue personas para que al escanear un RUT se complete el nombre automáticamente.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($personas->hasPages())
                <div class="px-5 py-3 border-t border-slate-100">
                    {{ $personas->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/rut-formatter.js') }}"></script>
@endsection
