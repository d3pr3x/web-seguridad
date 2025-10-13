@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white sticky top-0 z-50 shadow-lg">
        <div class="px-4 lg:px-8 py-4 max-w-4xl mx-auto">
            <div class="flex items-center">
                <a href="{{ route('usuario.index') }}" class="mr-3 p-2 hover:bg-purple-500 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl lg:text-2xl font-bold">
                        @if($tipo === 'incidente') Incidente
                        @elseif($tipo === 'observacion') Observación
                        @else Información
                        @endif
                    </h1>
                    <p class="text-sm text-purple-100">Nueva novedad</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <form method="POST" action="{{ route('usuario.novedades.store') }}" id="novedadForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tipo" value="{{ $tipo }}">

            <!-- Paso 1: Información Básica -->
            <div id="paso1" class="bg-white rounded-lg shadow-md p-6 mb-4">
                <!-- Badge de tipo -->
                <div class="mb-4">
                    @if($tipo === 'incidente')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            Incidente
                        </span>
                    @elseif($tipo === 'observacion')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Observación
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            Información
                        </span>
                    @endif
                </div>

                <h2 class="text-lg font-bold text-gray-800 mb-4">Datos de la Novedad</h2>

                <!-- Título -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Título <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="titulo" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Resumen breve de la novedad">
                    @error('titulo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="descripcion" 
                        rows="5" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Describe detalladamente lo ocurrido"></textarea>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha y Hora -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            name="fecha" 
                            required
                            value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Hora <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="time" 
                            name="hora" 
                            required
                            value="{{ date('H:i') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Ubicación -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ubicación <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="ubicacion" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Sector, piso, área, etc.">
                    @error('ubicacion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if($tipo === 'incidente')
                <!-- Nivel de Gravedad (solo para incidentes) -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nivel de Gravedad <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="gravedad" 
                        id="gravedadSelect"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Seleccione...</option>
                        <option value="baja">Baja</option>
                        <option value="media">Media</option>
                        <option value="alta">Alta</option>
                        <option value="critica">Crítica</option>
                    </select>
                    @error('gravedad')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Evidencias (Fotos) -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Evidencias Fotográficas (opcional)
                    </label>
                    <input 
                        type="file" 
                        name="fotos[]" 
                        multiple
                        accept="image/*"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Puede seleccionar múltiples imágenes</p>
                </div>

                <!-- Botón para siguiente paso (solo para incidentes de gravedad alta o crítica) -->
                @if($tipo === 'incidente')
                <div id="btnSiguientePaso" class="hidden">
                    <button 
                        type="button"
                        onclick="mostrarPaso2()"
                        class="w-full bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700 transition flex items-center justify-center">
                        Siguiente: Detalles Adicionales
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                </div>
                @endif

                <!-- Botón enviar (para los que no necesitan paso 2) -->
                <div id="btnEnviar" class="{{ $tipo === 'incidente' ? 'hidden' : '' }}">
                    <button 
                        type="submit"
                        class="w-full bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700 transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Registrar Novedad
                    </button>
                </div>
            </div>

            <!-- Paso 2: Información Adicional (solo para incidentes graves) -->
            @if($tipo === 'incidente')
            <div id="paso2" class="bg-white rounded-lg shadow-md p-6 mb-4 hidden">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Información Adicional</h2>

                <!-- Personas Involucradas -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Personas Involucradas
                    </label>
                    <textarea 
                        name="personas_involucradas" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Nombres, RUT o identificación de personas involucradas"></textarea>
                </div>

                <!-- Testigos -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Testigos
                    </label>
                    <textarea 
                        name="testigos" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Nombres y contacto de testigos"></textarea>
                </div>

                <!-- Acciones Tomadas -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Acciones Tomadas <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="acciones_tomadas" 
                        rows="4"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Describe las acciones inmediatas que tomaste"></textarea>
                </div>

                <!-- Autoridades Notificadas -->
                <div class="mb-4">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="autoridades_notificadas" 
                            value="1"
                            class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-700">Se notificó a autoridades (Carabineros, PDI, Bomberos, etc.)</span>
                    </label>
                </div>

                <!-- Botones de Navegación -->
                <div class="flex gap-3">
                    <button 
                        type="button"
                        onclick="mostrarPaso1()"
                        class="flex-1 bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-400 transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        Volver
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700 transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Registrar Incidente
                    </button>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>

<script>
// Control de pasos para incidentes
@if($tipo === 'incidente')
document.getElementById('gravedadSelect').addEventListener('change', function() {
    const gravedad = this.value;
    const btnSiguiente = document.getElementById('btnSiguientePaso');
    const btnEnviar = document.getElementById('btnEnviar');
    
    if (gravedad === 'alta' || gravedad === 'critica') {
        btnSiguiente.classList.remove('hidden');
        btnEnviar.classList.add('hidden');
    } else {
        btnSiguiente.classList.add('hidden');
        btnEnviar.classList.remove('hidden');
    }
});

function mostrarPaso2() {
    // Validar campos requeridos del paso 1
    const form = document.getElementById('novedadForm');
    const requiredFields = form.querySelectorAll('#paso1 [required]');
    let valid = true;
    
    requiredFields.forEach(field => {
        if (!field.value) {
            valid = false;
            field.classList.add('border-red-500');
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    if (valid) {
        document.getElementById('paso1').classList.add('hidden');
        document.getElementById('paso2').classList.remove('hidden');
        window.scrollTo(0, 0);
    } else {
        alert('Por favor complete todos los campos requeridos');
    }
}

function mostrarPaso1() {
    document.getElementById('paso2').classList.add('hidden');
    document.getElementById('paso1').classList.remove('hidden');
    window.scrollTo(0, 0);
}
@endif
</script>
@endsection

