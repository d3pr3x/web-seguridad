@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:ml-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-4xl">
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.rondas.index') }}" class="hover:text-emerald-600">Puntos de ronda</a>
                <span class="mx-2">/</span>
                <a href="{{ route('admin.rondas.show', $sucursal) }}" class="hover:text-emerald-600">{{ $sucursal->nombre }}</a>
                <span class="mx-2">/</span>
                <span>Nuevo punto</span>
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-6">Nuevo punto de ronda</h1>

            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('admin.rondas.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="sucursal_id" value="{{ $sucursal->id }}">

                    <div class="mb-4">
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre del punto <span class="text-red-500">*</span></label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500" placeholder="Ej: Entrada principal, Estacionamiento" required>
                        @error('nombre')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="sector_id" class="block text-sm font-medium text-gray-700 mb-1">Sector (opcional)</label>
                        <select id="sector_id" name="sector_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                            <option value="">— Sin sector —</option>
                            @foreach($sectores as $sector)
                                <option value="{{ $sector->id }}" {{ old('sector_id') == $sector->id ? 'selected' : '' }}>{{ $sector->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="orden" class="block text-sm font-medium text-gray-700 mb-1">Orden en ruta</label>
                        <input type="number" id="orden" name="orden" value="{{ old('orden', 0) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <p class="text-sm font-medium text-amber-800 mb-2">Ubicación del punto (obligatoria para validar escaneo)</p>
                        <p class="text-xs text-amber-700 mb-3">Configure la ubicación física donde está el QR. El escaneo solo será válido si el guardia está dentro del radio configurado.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="lat" class="block text-sm text-gray-700 mb-1">Latitud</label>
                                <input type="text" id="lat" name="lat" value="{{ old('lat') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500" placeholder="Ej: -33.448890" inputmode="decimal">
                                @error('lat')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="lng" class="block text-sm text-gray-700 mb-1">Longitud</label>
                                <input type="text" id="lng" name="lng" value="{{ old('lng') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500" placeholder="Ej: -70.669265" inputmode="decimal">
                                @error('lng')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="distancia_maxima_metros" class="block text-sm text-gray-700 mb-1">Radio de proximidad (metros)</label>
                            <input type="number" id="distancia_maxima_metros" name="distancia_maxima_metros" value="{{ old('distancia_maxima_metros', 10) }}" min="1" max="500" class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                            <span class="ml-2 text-sm text-gray-500">El escaneo será válido si el guardia está a esta distancia o menos del punto.</span>
                            @error('distancia_maxima_metros')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <button type="button" id="btn-ubicacion" class="mt-2 inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 text-sm font-medium rounded-lg transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Obtener ubicación actual
                        </button>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="activo" value="1" {{ old('activo', true) ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2 text-sm text-gray-700">Punto activo</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('admin.rondas.show', $sucursal) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    var btn = document.getElementById('btn-ubicacion');
    var latInput = document.getElementById('lat');
    var lngInput = document.getElementById('lng');
    if (!btn || !latInput || !lngInput) return;
    btn.addEventListener('click', function() {
        btn.disabled = true;
        btn.textContent = 'Obteniendo…';
        if (!navigator.geolocation) {
            alert('Su navegador no soporta geolocalización.');
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>Obtener ubicación actual';
            return;
        }
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                latInput.value = pos.coords.latitude;
                lngInput.value = pos.coords.longitude;
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>Obtener ubicación actual';
            },
            function() {
                alert('No se pudo obtener la ubicación. Compruebe permisos o use HTTPS.');
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>Obtener ubicación actual';
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    });
})();
</script>
@endpush
@endsection
