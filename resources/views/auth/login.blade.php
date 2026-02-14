@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-3" style="width: 64px; height: 64px; background: rgba(15, 118, 110, 0.12);">
                        <i class="fas fa-shield-alt fa-2x" style="color: var(--app-primary);"></i>
                    </div>
                    <h2 class="card-title fw-bold mb-1" style="color: var(--app-text); font-size: 1.5rem;">Sistema de Seguridad</h2>
                    <p class="text-muted small mb-0">Inicia sesión con tu RUT</p>
                </div>

                <!-- Mensajes de error generales -->
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @error('ubicacion')
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-map-marker-alt me-2"></i>{{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @enderror

                @error('dispositivo')
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-mobile-alt me-2 mt-1"></i>
                            <div>
                                <strong>{{ $message }}</strong>
                                <p class="mb-0 mt-2 small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Para solicitar acceso: Haz clic en el cuadro de "Verificación" abajo para ver y copiar tu ID de dispositivo, luego envíaselo al administrador.
                                </p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @enderror

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <!-- Campos ocultos para fingerprint y geolocalización -->
                    <input type="hidden" name="browser_fingerprint" id="browser_fingerprint">
                    <input type="hidden" name="latitud" id="latitud">
                    <input type="hidden" name="longitud" id="longitud">
                    
                    <div class="mb-3">
                        <label for="rut" class="form-label">RUT</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-id-card"></i>
                            </span>
                            <input type="text" 
                                   class="form-control rut-input @error('rut') is-invalid @enderror" 
                                   id="rut" 
                                   name="rut" 
                                   value="{{ old('rut') }}" 
                                   placeholder="12.345.678-9"
                                   required 
                                   autofocus>
                        </div>
                        @error('rut')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Estado de verificación -->
                    <div class="mb-3">
                        <div class="card border" style="cursor: pointer; border-color: var(--app-border) !important; background: var(--app-surface);" onclick="toggleDeviceInfo()" title="Haz clic para ver el ID del dispositivo">
                            <div class="card-body p-2">
                                <small class="d-flex align-items-center mb-1" id="device-status">
                                    <i class="fas fa-circle-notch fa-spin text-warning me-2"></i>
                                    <span>Verificando dispositivo...</span>
                                </small>
                                <small class="d-flex align-items-center" id="location-status">
                                    <i class="fas fa-circle-notch fa-spin text-warning me-2"></i>
                                    <span>Obteniendo ubicación...</span>
                                </small>
                                
                                <!-- Información expandible del dispositivo -->
                                <div id="device-details" style="display: none;" class="mt-3 pt-3 border-top">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <strong style="color: var(--app-primary);">ID del Dispositivo:</strong>
                                        <button type="button" 
                                                onclick="event.stopPropagation(); copiarFingerprint();" 
                                                class="btn btn-sm btn-outline-app"
                                                id="copyButton">
                                            <i class="fas fa-copy me-1"></i>Copiar
                                        </button>
                                    </div>
                                    <div class="bg-white rounded p-2 border">
                                        <code id="fingerprint-display" class="text-xs d-block text-break" style="word-break: break-all;">-</code>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Si tu dispositivo no está autorizado, copia este ID y envíaselo al administrador para que te autorice el acceso.
                                    </small>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-hand-pointer me-1"></i>Haz clic en el cuadro para ver el ID del dispositivo
                        </small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" id="loginButton" disabled>
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/browser-fingerprint.js') }}"></script>
<script>
let currentFingerprint = null;

function toggleDeviceInfo() {
    const details = document.getElementById('device-details');
    if (details.style.display === 'none') {
        details.style.display = 'block';
    } else {
        details.style.display = 'none';
    }
}

function copiarFingerprint() {
    if (!currentFingerprint) {
        alert('El fingerprint aún no se ha generado. Por favor espera un momento.');
        return;
    }
    
    // Copiar al portapapeles
    navigator.clipboard.writeText(currentFingerprint).then(() => {
        // Cambiar el botón temporalmente para confirmar
        const copyButton = document.getElementById('copyButton');
        const originalHTML = copyButton.innerHTML;
        copyButton.innerHTML = '<i class="fas fa-check me-1"></i>¡Copiado!';
        copyButton.classList.remove('btn-outline-app');
        copyButton.classList.add('btn-success');
        
        setTimeout(() => {
            copyButton.innerHTML = originalHTML;
            copyButton.classList.remove('btn-success');
            copyButton.classList.add('btn-outline-app');
        }, 2000);
    }).catch(err => {
        // Fallback para navegadores antiguos
        const textarea = document.createElement('textarea');
        textarea.value = currentFingerprint;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        alert('ID copiado al portapapeles');
    });
}

document.addEventListener('DOMContentLoaded', async function() {
    const form = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const deviceStatus = document.getElementById('device-status');
    const locationStatus = document.getElementById('location-status');
    
    let fingerprintReady = false;
    let locationReady = false;
    let requiereUbicacion = true; // Por defecto requiere ubicación
    
    function updateLoginButton() {
        // Habilitar botón si:
        // - El fingerprint está listo Y
        // - (No requiere ubicación O la ubicación está lista)
        if (fingerprintReady && (!requiereUbicacion || locationReady)) {
            loginButton.disabled = false;
        }
    }
    
    function setDeviceStatus(success, message) {
        const icon = deviceStatus.querySelector('i');
        const text = deviceStatus.querySelector('span');
        
        icon.className = success 
            ? 'fas fa-check-circle text-success me-2' 
            : 'fas fa-exclamation-circle text-danger me-2';
        text.textContent = message;
        
        fingerprintReady = success;
        updateLoginButton();
    }
    
    function setLocationStatus(success, message) {
        const icon = locationStatus.querySelector('i');
        const text = locationStatus.querySelector('span');
        
        icon.className = success 
            ? 'fas fa-check-circle text-success me-2' 
            : 'fas fa-exclamation-circle text-danger me-2';
        text.textContent = message;
        
        locationReady = success;
        updateLoginButton();
    }
    
    // Generar fingerprint del navegador
    try {
        const fingerprint = await getBrowserFingerprint();
        currentFingerprint = fingerprint;
        document.getElementById('browser_fingerprint').value = fingerprint;
        document.getElementById('fingerprint-display').textContent = fingerprint;
        setDeviceStatus(true, 'Dispositivo verificado (' + fingerprint.substring(0, 8) + '...)');
        
        // Consultar al servidor si este dispositivo requiere ubicación
        try {
            const response = await fetch('{{ route('api.verificar-dispositivo') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    browser_fingerprint: fingerprint
                })
            });
            
            const data = await response.json();
            requiereUbicacion = data.requiere_ubicacion;
            
            if (!data.autorizado) {
                setDeviceStatus(false, 'Dispositivo no autorizado (haz clic arriba para ver tu ID)');
            }
            
            // Si no requiere ubicación, marcar como OK y habilitar botón
            if (!requiereUbicacion) {
                setLocationStatus(true, 'Ubicación GPS no requerida para este dispositivo');
                locationReady = true;
                updateLoginButton();
                return; // No intentar obtener ubicación
            }
        } catch (error) {
            console.error('Error verificando dispositivo:', error);
            // Si falla la verificación, asumir que requiere ubicación por seguridad
            requiereUbicacion = true;
        }
        
    } catch (error) {
        console.error('Error generando fingerprint:', error);
        setDeviceStatus(false, 'Error al verificar dispositivo');
        return; // No continuar si no hay fingerprint
    }
    
    // Obtener geolocalización solo si es necesario
    if (requiereUbicacion) {
        try {
            const position = await getCurrentPosition();
            document.getElementById('latitud').value = position.latitud;
            document.getElementById('longitud').value = position.longitud;
            setLocationStatus(true, `Ubicación obtenida (±${Math.round(position.precision)}m)`);
        } catch (error) {
            console.error('Error obteniendo ubicación:', error);
            setLocationStatus(false, error.message || 'Error al obtener ubicación');
            
            // Mostrar alerta específica
            if (error.message.includes('denegado')) {
                alert('⚠️ Permiso de ubicación denegado\n\nEste dispositivo requiere validación de ubicación GPS.\n\nPor favor, habilita la ubicación en tu navegador y recarga la página.');
            }
        }
    }
    
    // Validar antes de enviar
    form.addEventListener('submit', function(e) {
        if (!fingerprintReady) {
            e.preventDefault();
            alert('Por favor, espera a que se complete la verificación del dispositivo.');
            return false;
        }
        
        if (requiereUbicacion && !locationReady) {
            e.preventDefault();
            alert('Este dispositivo requiere validación de ubicación GPS. Por favor, espera a que se obtenga tu ubicación.');
            return false;
        }
        
        // Deshabilitar botón para evitar doble envío
        loginButton.disabled = true;
        loginButton.innerHTML = '<i class="fas fa-circle-notch fa-spin me-2"></i>Iniciando sesión...';
    });
});
</script>
@endpush
