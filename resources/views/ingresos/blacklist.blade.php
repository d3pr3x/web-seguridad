@extends('layouts.usuario')

@section('content')
<div class="min-h-screen flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
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
                    <span class="w-10 h-10 rounded-xl flex items-center justify-center text-red-600 shrink-0 bg-red-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </span>
                    Blacklist
                </h1>
                <a href="{{ route('ingresos.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium border transition bg-white hover:bg-slate-50" style="border-color: var(--app-border); color: var(--app-text);">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a ingresos
                </a>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <div class="px-5 py-4 border-b border-slate-100" style="background: var(--app-surface);">
                    <h2 class="text-sm font-semibold text-slate-700">Agregar a blacklist</h2>
                </div>
                <div class="p-5">
                    <form action="{{ route('blacklist.store') }}" method="post" class="flex flex-wrap gap-4 items-end">
                        @csrf
                        <div class="w-full sm:w-auto sm:min-w-[140px]">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">RUT <span class="text-red-500">*</span></label>
                            <input type="text" name="rut" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition rut-input" style="border-color: var(--app-border);" placeholder="12.345.678-9" required maxlength="12">
                            @error('rut')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="w-full sm:w-auto sm:min-w-[120px]">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Patente (opcional)</label>
                            <input type="text" name="patente" class="w-full px-3 py-2.5 rounded-lg border uppercase text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);" placeholder="ABCD12" maxlength="7">
                            @error('patente')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="w-full sm:flex-1 sm:min-w-[180px]">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Motivo <span class="text-red-500">*</span></label>
                            <input type="text" name="motivo" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);" placeholder="Motivo" required maxlength="500" value="{{ old('motivo') }}">
                            @error('motivo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="w-full sm:w-auto sm:min-w-[140px]">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Fecha inicio</label>
                            <input type="date" name="fecha_inicio" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);" value="{{ old('fecha_inicio', date('Y-m-d')) }}" required>
                        </div>
                        <div class="w-full sm:w-auto sm:min-w-[140px]">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Fecha fin (opcional)</label>
                            <input type="date" name="fecha_fin" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);" value="{{ old('fecha_fin') }}">
                        </div>
                        <button type="submit" class="w-full sm:w-auto px-4 py-2.5 rounded-xl font-medium text-white transition shadow-sm bg-red-600 hover:bg-red-700">Agregar</button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200" style="background: var(--app-surface);">
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">RUT</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Patente</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Motivo</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Inicio</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Fin</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Activo</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Creado por</th>
                                <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($blacklists as $b)
                            <tr class="{{ $b->trashed() ? 'bg-slate-50/50' : 'hover:bg-slate-50/80' }} transition">
                                <td class="px-5 py-3.5 text-sm text-slate-800 font-medium">{{ $b->rut }}</td>
                                <td class="px-5 py-3.5 text-sm text-slate-600">{{ $b->patente ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-sm text-slate-600">{{ Str::limit($b->motivo, 40) }}</td>
                                <td class="px-5 py-3.5 text-sm text-slate-600">{{ $b->fecha_inicio->format('d/m/Y') }}</td>
                                <td class="px-5 py-3.5 text-sm text-slate-600">{{ $b->fecha_fin ? $b->fecha_fin->format('d/m/Y') : '-' }}</td>
                                <td class="px-5 py-3.5">
                                    @if($b->activo)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-700">Activo</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-600">Inactivo</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-sm text-slate-600">{{ $b->creador->nombre_completo ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2 flex-wrap">
                                        @if(!$b->trashed())
                                            <form action="{{ route('blacklist.toggle', $b->id) }}" method="post" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-sm font-medium text-amber-600 hover:text-amber-700 hover:underline">{{ $b->activo ? 'Desactivar' : 'Activar' }}</button>
                                            </form>
                                            <span class="text-slate-300">·</span>
                                            <form action="{{ route('blacklist.destroy', $b->id) }}" method="post" class="inline" onsubmit="return confirm('¿Eliminar de la blacklist?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700 hover:underline">Eliminar</button>
                                            </form>
                                        @else
                                            <form action="{{ route('blacklist.toggle', $b->id) }}" method="post" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-sm font-medium text-teal-600 hover:text-teal-700 hover:underline">Restaurar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center text-slate-500">No hay entradas en la blacklist.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($blacklists->hasPages())
                    <div class="px-5 py-3 border-t border-slate-200" style="background: var(--app-surface);">
                        {{ $blacklists->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="{{ asset('js/rut-formatter.js') }}"></script>
@endpush
@endsection
