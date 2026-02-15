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
            <!-- Título -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Agregar Nueva Ubicación
                </h1>
                <a href="{{ route('admin.ubicaciones.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>

            <!-- Instrucciones -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-blue-800 font-semibold mb-1">¿Cómo obtener las coordenadas GPS?</h3>
                        <ol class="text-blue-700 text-sm list-decimal list-inside space-y-1">
                            <li>Abre <a href="https://www.google.com/maps" target="_blank" class="underline">Google Maps</a></li>
                            <li>Haz clic derecho en la ubicación deseada</li>
                            <li>Selecciona las coordenadas que aparecen (primer elemento del menú)</li>
                            <li>Pega las coordenadas en los campos correspondientes</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('admin.ubicaciones.store') }}" method="POST" id="ubicacionForm">
                    @csrf

                    <!-- Nombre -->
                    <div class="mb-6">
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la Ubicación *
                        </label>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               value="{{ old('nombre') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('nombre') border-red-500 @enderror"
                               placeholder="Ej: Oficina Central - Santiago"
                               required>
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sucursal -->
                    <div class="mb-6">
                        <label for="sucursal_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Sucursal (Opcional)
                        </label>
                        <select id="sucursal_id" 
                                name="sucursal_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('sucursal_id') border-red-500 @enderror">
                            <option value="">Sin asignar</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}" {{ old('sucursal_id') == $sucursal->id ? 'selected' : '' }}>
                                    {{ $sucursal->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('sucursal_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Coordenadas GPS -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="latitud" class="block text-sm font-medium text-gray-700 mb-2">
                                Latitud *
                            </label>
                            <input type="text" 
                                   id="latitud" 
                                   name="latitud" 
                                   value="{{ old('latitud') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('latitud') border-red-500 @enderror"
                                   placeholder="-33.4489"
                                   step="0.00000001"
                                   required>
                            @error('latitud')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Rango: -90 a 90</p>
                        </div>

                        <div>
                            <label for="longitud" class="block text-sm font-medium text-gray-700 mb-2">
                                Longitud *
                            </label>
                            <input type="text" 
                                   id="longitud" 
                                   name="longitud" 
                                   value="{{ old('longitud') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('longitud') border-red-500 @enderror"
                                   placeholder="-70.6693"
                                   step="0.00000001"
                                   required>
                            @error('longitud')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Rango: -180 a 180</p>
                        </div>
                    </div>

                    <!-- Vista previa del mapa -->
                    <div class="mb-6" id="mapPreview" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Vista Previa de la Ubicación
                        </label>
                        <div class="bg-gray-100 rounded-lg p-4">
                            <a id="mapLink" href="#" target="_blank" class="text-orange-600 hover:text-orange-800 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Ver ubicación en Google Maps
                            </a>
                        </div>
                    </div>

                    <!-- Radio de Acceso -->
                    <div class="mb-6">
                        <label for="radio" class="block text-sm font-medium text-gray-700 mb-2">
                            Radio de Acceso (metros) *
                        </label>
                        <input type="number" 
                               id="radio" 
                               name="radio" 
                               value="{{ old('radio', 50) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('radio') border-red-500 @enderror"
                               min="1"
                               max="1000"
                               required>
                        @error('radio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Los usuarios podrán acceder si están dentro de este radio. Rango: 1 - 1000 metros</p>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-6">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción (Opcional)
                        </label>
                        <textarea id="descripcion" 
                                  name="descripcion" 
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('descripcion') border-red-500 @enderror"
                                  placeholder="Descripción adicional de la ubicación...">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.ubicaciones.index') }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Guardar Ubicación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const latitudInput = document.getElementById('latitud');
    const longitudInput = document.getElementById('longitud');
    const mapPreview = document.getElementById('mapPreview');
    const mapLink = document.getElementById('mapLink');

    function updateMapPreview() {
        const lat = latitudInput.value;
        const lon = longitudInput.value;

        if (lat && lon) {
            mapPreview.style.display = 'block';
            mapLink.href = `https://www.google.com/maps?q=${lat},${lon}`;
        } else {
            mapPreview.style.display = 'none';
        }
    }

    latitudInput.addEventListener('input', updateMapPreview);
    longitudInput.addEventListener('input', updateMapPreview);

    // Validar coordenadas al enviar
    document.getElementById('ubicacionForm').addEventListener('submit', function(e) {
        const lat = parseFloat(latitudInput.value);
        const lon = parseFloat(longitudInput.value);

        if (isNaN(lat) || lat < -90 || lat > 90) {
            e.preventDefault();
            alert('La latitud debe estar entre -90 y 90');
            latitudInput.focus();
            return false;
        }

        if (isNaN(lon) || lon < -180 || lon > 180) {
            e.preventDefault();
            alert('La longitud debe estar entre -180 y 180');
            longitudInput.focus();
            return false;
        }
    });

    // Actualizar vista previa si hay valores iniciales
    updateMapPreview();
});
</script>
@endpush
@endsection

