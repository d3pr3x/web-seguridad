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
                    <svg class="w-8 h-8 mr-3 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Agregar Nuevo Dispositivo
                </h1>
                <a href="{{ route('admin.dispositivos.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition flex items-center">
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
                        <h3 class="text-blue-800 font-semibold mb-1">¿Cómo obtener el Browser Fingerprint?</h3>
                        <ol class="text-blue-700 text-sm list-decimal list-inside space-y-1">
                            <li>El usuario debe intentar iniciar sesión desde su dispositivo</li>
                            <li>El sistema mostrará un error de "Dispositivo no autorizado"</li>
                            <li>El fingerprint se genera automáticamente y se puede ver en los logs</li>
                            <li>Copia el fingerprint completo y pégalo en el campo correspondiente</li>
                        </ol>
                        <p class="text-blue-700 text-sm mt-2">
                            <strong>Opción alternativa:</strong> Usa el botón "Detectar este dispositivo" para obtener el fingerprint del dispositivo actual.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('admin.dispositivos.store') }}" method="POST" id="dispositivoForm">
                    @csrf

                    <!-- Detección automática -->
                    <div class="mb-6 bg-teal-50 p-4 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-teal-800 font-semibold mb-1">Detección Automática</h3>
                                <p class="text-teal-700 text-sm mb-3">Haz clic en el botón para obtener el fingerprint de este dispositivo automáticamente</p>
                            </div>
                            <button type="button" 
                                    onclick="detectarDispositivo()" 
                                    class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition flex items-center whitespace-nowrap">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Detectar este Dispositivo
                            </button>
                        </div>
                    </div>

                    <!-- Browser Fingerprint -->
                    <div class="mb-6">
                        <label for="browser_fingerprint" class="block text-sm font-medium text-gray-700 mb-2">
                            Browser Fingerprint *
                        </label>
                        <textarea id="browser_fingerprint" 
                                  name="browser_fingerprint" 
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent font-mono text-sm @error('browser_fingerprint') border-red-500 @enderror"
                                  placeholder="El fingerprint se generará automáticamente al hacer clic en 'Detectar este Dispositivo' o puedes pegarlo manualmente"
                                  required>{{ old('browser_fingerprint') }}</textarea>
                        @error('browser_fingerprint')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Este es un hash SHA-256 único de 64 caracteres que identifica al dispositivo</p>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-6">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción *
                        </label>
                        <input type="text" 
                               id="descripcion" 
                               name="descripcion" 
                               value="{{ old('descripcion') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('descripcion') border-red-500 @enderror"
                               placeholder="Ej: Laptop del Director - Chrome"
                               required>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Descripción que te ayude a identificar el dispositivo</p>
                    </div>

                    <!-- Requiere Ubicación -->
                    <div class="mb-6">
                        <label class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       id="requiere_ubicacion" 
                                       name="requiere_ubicacion" 
                                       value="1"
                                       {{ old('requiere_ubicacion', true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                            </div>
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Requiere Validación de Ubicación GPS</span>
                                <p class="text-xs text-gray-500 mt-1">
                                    Si está marcado, el dispositivo deberá estar en una ubicación permitida para acceder. 
                                    Desmarca esta opción para dispositivos de administradores que puedan acceder desde cualquier lugar.
                                </p>
                            </div>
                        </label>
                    </div>

                    <!-- Información del dispositivo detectado -->
                    <div id="deviceInfo" class="mb-6 bg-gray-50 p-4 rounded-lg" style="display: none;">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Información del Dispositivo Detectado:</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                            <div>
                                <dt class="text-gray-500">Navegador:</dt>
                                <dd id="deviceBrowser" class="font-medium text-gray-900">-</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Sistema Operativo:</dt>
                                <dd id="deviceOS" class="font-medium text-gray-900">-</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Resolución:</dt>
                                <dd id="deviceResolution" class="font-medium text-gray-900">-</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Idioma:</dt>
                                <dd id="deviceLanguage" class="font-medium text-gray-900">-</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.dispositivos.index') }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Guardar Dispositivo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/browser-fingerprint.js') }}"></script>
<script>
async function detectarDispositivo() {
    try {
        // Mostrar indicador de carga
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Detectando...';
        btn.disabled = true;

        // Generar fingerprint
        const fingerprint = await getBrowserFingerprint();
        
        // Llenar el campo
        document.getElementById('browser_fingerprint').value = fingerprint;
        
        // Obtener información del dispositivo
        const userAgent = navigator.userAgent;
        let browser = 'Desconocido';
        let os = 'Desconocido';
        
        // Detectar navegador
        if (userAgent.includes('Chrome') && !userAgent.includes('Edg')) browser = 'Google Chrome';
        else if (userAgent.includes('Safari') && !userAgent.includes('Chrome')) browser = 'Safari';
        else if (userAgent.includes('Firefox')) browser = 'Firefox';
        else if (userAgent.includes('Edg')) browser = 'Microsoft Edge';
        else if (userAgent.includes('Opera') || userAgent.includes('OPR')) browser = 'Opera';
        
        // Detectar OS
        if (userAgent.includes('Windows')) os = 'Windows';
        else if (userAgent.includes('Mac')) os = 'macOS';
        else if (userAgent.includes('Linux')) os = 'Linux';
        else if (userAgent.includes('Android')) os = 'Android';
        else if (userAgent.includes('iOS')) os = 'iOS';
        
        // Mostrar información
        document.getElementById('deviceBrowser').textContent = browser;
        document.getElementById('deviceOS').textContent = os;
        document.getElementById('deviceResolution').textContent = `${screen.width}x${screen.height}`;
        document.getElementById('deviceLanguage').textContent = navigator.language;
        document.getElementById('deviceInfo').style.display = 'block';
        
        // Sugerir descripción si está vacía
        const descripcionInput = document.getElementById('descripcion');
        if (!descripcionInput.value) {
            descripcionInput.value = `${os} - ${browser} (${new Date().toLocaleDateString('es-CL')})`;
        }
        
        // Restaurar botón
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        
        // Mostrar mensaje de éxito
        alert('✅ Dispositivo detectado exitosamente!\n\nEl fingerprint se ha generado y copiado al formulario.');
        
    } catch (error) {
        console.error('Error detectando dispositivo:', error);
        alert('❌ Error al detectar el dispositivo: ' + error.message);
        
        // Restaurar botón
        const btn = event.target.closest('button');
        btn.innerHTML = originalHTML;
        btn.disabled = false;
    }
}

// Validar antes de enviar
document.getElementById('dispositivoForm').addEventListener('submit', function(e) {
    const fingerprint = document.getElementById('browser_fingerprint').value;
    
    if (fingerprint.length < 32) {
        e.preventDefault();
        alert('El fingerprint parece ser inválido. Por favor, usa el botón "Detectar este Dispositivo" o copia un fingerprint válido.');
        return false;
    }
});
</script>
@endpush
@endsection

