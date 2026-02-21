@extends('layouts.guest')

@section('title', 'Iniciar sesión')
@section('subtitle', 'Inicia sesión con tu RUT')

@section('content')
<h2 class="h5 fw-bold mb-1" style="color: var(--app-text);">Iniciar sesión</h2>
<p class="text-muted small mb-4">Ingresa tus credenciales para continuar</p>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start" role="alert">
        <i class="fas fa-exclamation-circle me-2 mt-1"></i>
        <div class="flex-grow-1">{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@error('ubicacion')
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start" role="alert">
        <i class="fas fa-map-marker-alt me-2 mt-1"></i>
        <div class="flex-grow-1">{{ $message }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@enderror

@error('dispositivo')
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-start">
            <i class="fas fa-mobile-alt me-2 mt-1"></i>
            <div>
                <strong>{{ $message }}</strong>
                <p class="mb-0 mt-2 small text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Haz clic en el cuadro de verificación para ver tu ID de dispositivo y envíaselo al administrador.
                </p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@enderror

<form method="POST" action="{{ route('login') }}" id="loginForm">
    @csrf
    <input type="hidden" name="browser_fingerprint" id="browser_fingerprint">
    <input type="hidden" name="latitud" id="latitud">
    <input type="hidden" name="longitud" id="longitud">

    <div class="mb-3">
        <label for="rut" class="form-label">RUT</label>
        <div class="input-group input-group-lg login-input-group">
            <span class="input-group-text"><i class="fas fa-id-card text-muted"></i></span>
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
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <div class="input-group input-group-lg login-input-group">
            <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="password"
                   name="password"
                   placeholder="••••••••"
                   required>
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <div class="device-box rounded-3 border p-3 bg-light cursor-pointer" onclick="toggleDeviceInfo()" title="Clic para ver ID del dispositivo" style="cursor: pointer;">
            <small class="d-flex align-items-center mb-1" id="device-status">
                <i class="fas fa-circle-notch fa-spin text-warning me-2"></i>
                <span>Verificando dispositivo...</span>
            </small>
            <small class="d-flex align-items-center" id="location-status">
                <i class="fas fa-circle-notch fa-spin text-warning me-2"></i>
                <span>Obteniendo ubicación...</span>
            </small>
            <div id="device-details" class="mt-3 pt-3 border-top" style="display: none;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <strong class="small" style="color: var(--app-primary);">ID del dispositivo</strong>
                    <button type="button" onclick="event.stopPropagation(); copiarFingerprint();" class="btn btn-sm btn-outline-app" id="copyButton">
                        <i class="fas fa-copy me-1"></i>Copiar
                    </button>
                </div>
                <code id="fingerprint-display" class="d-block small text-break bg-white rounded p-2 border" style="word-break: break-all;">-</code>
                <small class="text-muted d-block mt-2">
                    <i class="fas fa-info-circle me-1"></i>Si no estás autorizado, copia este ID y envíalo al administrador.
                </small>
            </div>
        </div>
        <small class="text-muted d-block mt-1"><i class="fas fa-hand-pointer me-1"></i>Clic en el cuadro para ver el ID del dispositivo</small>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold" id="loginButton" disabled>
            <i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script src="{{ asset('js/browser-fingerprint.js') }}"></script>
<script src="{{ asset('js/rut-formatter.js') }}"></script>
<script>
let currentFingerprint = null;

function toggleDeviceInfo() {
    var details = document.getElementById('device-details');
    details.style.display = details.style.display === 'none' ? 'block' : 'none';
}

function copiarFingerprint() {
    if (!currentFingerprint) {
        alert('El ID aún no se ha generado. Espera un momento.');
        return;
    }
    navigator.clipboard.writeText(currentFingerprint).then(function() {
        var btn = document.getElementById('copyButton');
        var orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check me-1"></i>¡Copiado!';
        btn.classList.remove('btn-outline-app');
        btn.classList.add('btn-success');
        setTimeout(function() {
            btn.innerHTML = orig;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-app');
        }, 2000);
    }).catch(function() {
        var ta = document.createElement('textarea');
        ta.value = currentFingerprint;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        alert('ID copiado al portapapeles');
    });
}

document.addEventListener('DOMContentLoaded', async function() {
    var form = document.getElementById('loginForm');
    var loginButton = document.getElementById('loginButton');
    var deviceStatus = document.getElementById('device-status');
    var locationStatus = document.getElementById('location-status');
    var fingerprintReady = false;
    var locationReady = false;
    var requiereUbicacion = true;

    function updateLoginButton() {
        if (fingerprintReady && (!requiereUbicacion || locationReady)) {
            loginButton.disabled = false;
        }
    }

    function setDeviceStatus(success, message) {
        var icon = deviceStatus.querySelector('i');
        var text = deviceStatus.querySelector('span');
        icon.className = success ? 'fas fa-check-circle text-success me-2' : 'fas fa-exclamation-circle text-danger me-2';
        text.textContent = message;
        fingerprintReady = success;
        updateLoginButton();
    }

    function setLocationStatus(success, message) {
        var icon = locationStatus.querySelector('i');
        var text = locationStatus.querySelector('span');
        icon.className = success ? 'fas fa-check-circle text-success me-2' : 'fas fa-exclamation-circle text-danger me-2';
        text.textContent = message;
        locationReady = success;
        updateLoginButton();
    }

    try {
        var fingerprint = await getBrowserFingerprint();
        currentFingerprint = fingerprint;
        document.getElementById('browser_fingerprint').value = fingerprint;
        document.getElementById('fingerprint-display').textContent = fingerprint;
        setDeviceStatus(true, 'Dispositivo verificado (' + fingerprint.substring(0, 8) + '...)');

        try {
            var response = await fetch('{{ route('api.verificar-dispositivo') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ browser_fingerprint: fingerprint })
            });
            var data = await response.json();
            requiereUbicacion = data.requiere_ubicacion;
            if (!data.autorizado) {
                setDeviceStatus(false, 'Dispositivo no autorizado (clic arriba para ver tu ID)');
            }
            if (!requiereUbicacion) {
                setLocationStatus(true, 'Ubicación GPS no requerida');
                locationReady = true;
                updateLoginButton();
                return;
            }
        } catch (e) {
            requiereUbicacion = true;
        }
    } catch (e) {
        setDeviceStatus(false, 'Error al verificar dispositivo');
        return;
    }

    if (requiereUbicacion) {
        try {
            var position = await getCurrentPosition();
            document.getElementById('latitud').value = position.latitud;
            document.getElementById('longitud').value = position.longitud;
            setLocationStatus(true, 'Ubicación obtenida (±' + Math.round(position.precision) + 'm)');
        } catch (err) {
            setLocationStatus(false, err.message || 'Error al obtener ubicación');
            if (err.message && err.message.indexOf('denegado') !== -1) {
                alert('Permiso de ubicación denegado. Este dispositivo requiere validación GPS. Habilita la ubicación y recarga.');
            }
        }
    }

    form.addEventListener('submit', function(e) {
        if (!fingerprintReady) {
            e.preventDefault();
            alert('Espera a que termine la verificación del dispositivo.');
            return false;
        }
        if (requiereUbicacion && !locationReady) {
            e.preventDefault();
            alert('Se requiere validación de ubicación GPS. Espera a que se obtenga.');
            return false;
        }
        loginButton.disabled = true;
        loginButton.innerHTML = '<i class="fas fa-circle-notch fa-spin me-2"></i>Iniciando sesión...';
    });
});
</script>
@endpush
