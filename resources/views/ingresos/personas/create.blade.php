@extends('layouts.usuario')

@section('content')
<div class="min-h-screen flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-2xl">
            <div class="mb-6">
                <a href="{{ route('personas.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a base de personas
                </a>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100" style="background: var(--app-surface);">
                    <h1 class="text-lg font-bold text-slate-800">Agregar persona</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Al escanear un RUT en el escáner, si está aquí se completará el nombre automáticamente.</p>
                </div>
                <div class="p-5">
                    <form action="{{ route('personas.store') }}" method="post">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">RUT <span class="text-red-500">*</span></label>
                                <input type="text" name="rut" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition rut-input" style="border-color: var(--app-border);" placeholder="12.345.678-9" required maxlength="12" value="{{ old('rut') }}">
                                @error('rut')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nombre <span class="text-red-500">*</span></label>
                                <input type="text" name="nombre" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);" required maxlength="100" value="{{ old('nombre') }}">
                                @error('nombre')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Teléfono</label>
                                    <input type="text" name="telefono" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);" maxlength="20" value="{{ old('telefono') }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                                    <input type="email" name="email" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);" maxlength="100" value="{{ old('email') }}">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Empresa</label>
                                <input type="text" name="empresa" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);" maxlength="100" value="{{ old('empresa') }}">
                            </div>
                            @if($sucursales->isNotEmpty())
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Sucursal (opcional)</label>
                                <select name="sucursal_id" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);">
                                    <option value="">— Ninguna —</option>
                                    @foreach($sucursales as $s)
                                    <option value="{{ $s->id }}" {{ old('sucursal_id') == $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Notas</label>
                                <textarea name="notas" rows="2" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);" maxlength="1000">{{ old('notas') }}</textarea>
                            </div>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="px-4 py-2.5 rounded-xl font-medium text-white transition" style="background: var(--app-primary);">Guardar persona</button>
                            <a href="{{ route('personas.index') }}" class="px-4 py-2.5 rounded-xl font-medium border transition" style="border-color: var(--app-border); color: var(--app-text);">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/rut-formatter.js') }}"></script>
@endsection
