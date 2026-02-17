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
            @if(session('error'))
                <div class="mb-4 p-4 rounded-xl border border-red-200 bg-red-50 text-red-800">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h1 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-10 h-10 rounded-xl flex items-center justify-center text-teal-600 shrink-0" style="background: rgba(15, 118, 110, 0.12);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h.01M9 16h.01"></path>
                        </svg>
                    </span>
                    Ingresos
                </h1>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('qr-automatico') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium text-white transition shadow-sm hover:shadow" style="background: var(--app-primary);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        QR carnet
                    </a>
                    @if(config('app.ingresos_entrada_manual_solo', true))
                    <a href="{{ route('ingresos.entrada-manual') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium text-white transition shadow-sm hover:shadow" style="background: var(--app-primary);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Entrada manual
                    </a>
                    <a href="{{ route('ingresos.escaner-nuevo') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium border transition bg-white hover:bg-slate-50" style="border-color: var(--app-border); color: var(--app-text);">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        Cédula nuevo formato
                    </a>
                    @else
                    <a href="{{ route('ingresos.escaner') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium text-white transition shadow-sm hover:shadow" style="background: var(--app-primary);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        Escáner
                    </a>
                    <a href="{{ route('ingresos.escaner-nuevo') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium border transition bg-white hover:bg-slate-50" style="border-color: var(--app-border); color: var(--app-text);">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        Cédula nuevo formato
                    </a>
                    @endif
                    <a href="{{ route('personas.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium border transition bg-white hover:bg-slate-50" style="border-color: var(--app-border); color: var(--app-text);">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Personas
                    </a>
                    <a href="{{ route('blacklist.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium border transition bg-white hover:bg-slate-50" style="border-color: var(--app-border); color: var(--app-text);">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        Blacklist
                    </a>
                    <form action="{{ route('ingresos.exportar-csv') }}" method="post" class="inline">
                        @csrf
                        <input type="hidden" name="fecha_desde" value="{{ request('fecha_desde') }}">
                        <input type="hidden" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                        <input type="hidden" name="tipo" value="{{ request('tipo') }}">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium border transition bg-white hover:bg-slate-50" style="border-color: var(--app-border); color: var(--app-text);">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Exportar CSV
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <div class="px-5 py-4 border-b border-slate-100" style="background: var(--app-surface);">
                    <h2 class="text-sm font-semibold text-slate-700">Filtros</h2>
                </div>
                <div class="p-5">
                    <form method="get" action="{{ route('ingresos.index') }}" class="flex flex-wrap gap-4 items-end">
                        <div class="w-full sm:w-auto sm:min-w-[160px]">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">RUT / Pasaporte</label>
                            <input type="text" name="rut" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);" value="{{ request('rut') }}" placeholder="Buscar por RUT o pasaporte">
                        </div>
                        <div class="w-full sm:w-auto sm:min-w-[140px]">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Desde</label>
                            <input type="date" name="fecha_desde" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);" value="{{ request('fecha_desde') }}">
                        </div>
                        <div class="w-full sm:w-auto sm:min-w-[140px]">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Hasta</label>
                            <input type="date" name="fecha_hasta" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);" value="{{ request('fecha_hasta') }}">
                        </div>
                        <div class="w-full sm:w-auto sm:min-w-[140px]">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tipo</label>
                            <select name="tipo" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);">
                                <option value="">Todos</option>
                                <option value="peatonal" {{ request('tipo') === 'peatonal' ? 'selected' : '' }}>Peatonal</option>
                                <option value="vehicular" {{ request('tipo') === 'vehicular' ? 'selected' : '' }}>Vehicular</option>
                            </select>
                        </div>
                        <div class="w-full sm:w-auto sm:min-w-[140px]">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Estado</label>
                            <select name="estado" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);">
                                <option value="">Todos</option>
                                <option value="ingresado" {{ request('estado') === 'ingresado' ? 'selected' : '' }}>Ingresado</option>
                                <option value="salida" {{ request('estado') === 'salida' ? 'selected' : '' }}>Salida</option>
                                <option value="bloqueado" {{ request('estado') === 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full sm:w-auto px-4 py-2.5 rounded-xl font-medium text-white transition shadow-sm hover:shadow" style="background: var(--app-primary);">
                            Filtrar
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200" style="background: var(--app-surface);">
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Fecha ingreso</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tipo</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">RUT / Nombre / Patente</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Guardia</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Estado</th>
                                <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($ingresos as $ingreso)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-3.5 text-sm text-slate-800">{{ $ingreso->fecha_ingreso->format('d/m/Y H:i') }}</td>
                                <td class="px-5 py-3.5">
                                    @if($ingreso->tipo === 'peatonal')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-teal-50 text-teal-700">Peatonal</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700">Vehicular</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-sm">
                                    <span class="font-medium text-slate-800">{{ $ingreso->rut }}</span>
                                    @if($ingreso->nombre) <br><span class="text-slate-500">{{ $ingreso->nombre }}</span> @endif
                                    @if($ingreso->patente) <br><span class="text-slate-500">Patente: {{ $ingreso->patente }}</span> @endif
                                </td>
                                <td class="px-5 py-3.5 text-sm text-slate-600">{{ $ingreso->guardia->nombre_completo ?? '-' }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex flex-wrap gap-1.5">
                                        @if($ingreso->estado === 'ingresado')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-700">Ingresado</span>
                                        @elseif($ingreso->estado === 'bloqueado')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-50 text-red-700">Bloqueado</span>
                                            @if($ingreso->alerta_blacklist)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-amber-50 text-amber-700">Blacklist</span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-600">Salida</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($ingreso->estado === 'ingresado')
                                            <form action="{{ route('ingresos.salida', $ingreso->id) }}" method="post" class="inline">
                                                @csrf
                                                <button type="submit" class="text-sm font-medium text-teal-600 hover:text-teal-700 hover:underline">Salida</button>
                                            </form>
                                            <span class="text-slate-300">·</span>
                                        @endif
                                        <a href="{{ route('ingresos.show', $ingreso->id) }}" class="text-sm font-medium text-teal-600 hover:text-teal-700 hover:underline">Detalle</a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-500">No hay ingresos registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($ingresos->hasPages())
                    <div class="px-5 py-3 border-t border-slate-200" style="background: var(--app-surface);">
                        {{ $ingresos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
