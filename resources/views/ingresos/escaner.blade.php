@extends('layouts.app')

@section('title', 'Control de acceso - Escáner')

@push('styles')
<style>
    #lector-cedula { width: 100%; min-height: 400px; position: relative; }
    #lector-cedula video { width: 100% !important; max-height: 400px; object-fit: cover; display: block; }
    #video-patente { width: 100%; max-height: 300px; object-fit: cover; }
    #canvas { display: none; }
    .tab-pane { padding: 1rem 0; }
    .form-control[readonly] { background-color: #e9ecef; }
    @media (max-width: 768px) {
        #lector-cedula { min-height: 320px; }
        #lector-cedula video { max-height: 320px; }
        #video-patente { max-height: 260px; }
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-qrcode me-2"></i>Control de acceso</h1>
        <a href="{{ route('ingresos.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-list me-1"></i>Ver listado
        </a>
    </div>

    <ul class="nav nav-tabs mb-3" id="tabsAcceso" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="peatonal-tab" data-bs-toggle="tab" data-bs-target="#peatonal" type="button">Peatonal (cédula)</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="vehicular-tab" data-bs-toggle="tab" data-bs-target="#vehicular" type="button">Vehicular (patente)</button>
        </li>
    </ul>

    <div class="tab-content" id="tabsAccesoContent">
        {{-- Tab Peatonal --}}
        <div class="tab-pane fade show active" id="peatonal" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted small">Escaneé el código de la cédula de identidad chilena (QR/DataMatrix) o ingrese datos manualmente.</p>
                    <div id="lector-cedula" class="bg-dark rounded overflow-hidden mb-3"></div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">RUT</label>
                            <input type="text" id="rut" class="form-control rut-input" placeholder="12.345.678-9" maxlength="12" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" id="nombre" class="form-control" placeholder="Nombre completo" maxlength="100" readonly>
                        </div>
                    </div>
                    <p class="small text-muted">Si el escáner no detecta, ingrese manualmente:</p>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="text" id="rut-manual" class="form-control rut-input" placeholder="RUT manual">
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="nombre-manual" class="form-control" placeholder="Nombre manual">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab Vehicular --}}
        <div class="tab-pane fade" id="vehicular" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted small">Enfoque la patente del vehículo (formato ABCD12 o ABC123). Buena luz y 30–50 cm de distancia.</p>
                    <video id="video-patente" autoplay playsinline muted width="100%" height="300" class="rounded bg-dark"></video>
                    <canvas id="canvas" width="640" height="480"></canvas>
                    <div class="row g-2 mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Patente detectada</label>
                            <input type="text" id="patente-result" class="form-control text-uppercase" placeholder="ABCD12" maxlength="7" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">RUT conductor (opcional)</label>
                            <input type="text" id="conductor-rut" class="form-control rut-input" placeholder="12.345.678-9">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <input type="hidden" id="tipo-actual" value="peatonal">
            <button type="button" id="btn-registrar" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-check-circle me-2"></i>Registrar ingreso
            </button>
        </div>
    </div>

    <div id="alerta-resultado" class="mt-3" style="display:none;"></div>
    <div id="qr-salida-container" class="mt-3 text-center" style="display:none;">
        <p class="small text-success mb-2">Ingreso registrado. QR para registrar salida:</p>
        <div id="qr-salida-img"></div>
        <p class="small text-muted mt-2">Escanear al salir para registrar la salida.</p>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoActual = document.getElementById('tipo-actual');
    const tabsAcceso = document.getElementById('tabsAcceso');
    const btnRegistrar = document.getElementById('btn-registrar');
    const alertaResultado = document.getElementById('alerta-resultado');
    const qrSalidaContainer = document.getElementById('qr-salida-container');
    const qrSalidaImg = document.getElementById('qr-salida-img');

    tabsAcceso.querySelectorAll('button[data-bs-toggle="tab"]').forEach(function(tab) {
        tab.addEventListener('shown.bs.tab', function(e) {
            tipoActual.value = e.target.getAttribute('data-bs-target') === '#vehicular' ? 'vehicular' : 'peatonal';
            if (tipoActual.value === 'vehicular') {
                iniciarOCRPatente();
            } else {
                detenerOCRPatente();
            }
        });
    });

    // ——— Lector QR cédula (peatonal) ———
    let html5QrCode = null;
    const lectorCedula = document.getElementById('lector-cedula');
    const rutInput = document.getElementById('rut');
    const nombreInput = document.getElementById('nombre');
    const rutManual = document.getElementById('rut-manual');
    const nombreManual = document.getElementById('nombre-manual');

    function iniciarLectorCedula() {
        if (html5QrCode && html5QrCode.isScanning) return;
        // Área de escaneo = casi todo el visor (QR de lejos se ve pequeño, debe caber en el cuadro)
        var config = {
            fps: 10,
            qrbox: function(viewfinderWidth, viewfinderHeight) {
                var w = Math.max(200, Math.floor(viewfinderWidth * 0.98));
                var h = Math.max(200, Math.floor(viewfinderHeight * 0.98));
                return { width: w, height: h };
            }
        };
        var tryStart = function(constraints) {
            if (html5QrCode && html5QrCode.isScanning) return Promise.resolve();
            if (!html5QrCode) html5QrCode = new Html5Qrcode('lector-cedula');
            return html5QrCode.start(constraints, config, onScanCedula, function() {}).catch(function(err) {
                throw err;
            });
        };
        Html5Qrcode.getCameras().then(function(cameras) {
            if (!cameras.length) {
                lectorCedula.innerHTML = '<p class="text-white p-3">No se detectó cámara. Use entrada manual.</p>';
                return;
            }
            var constraints = { facingMode: 'environment' };
            tryStart(constraints).catch(function(err) {
                return tryStart({ video: true });
            }).catch(function(err) {
                if (cameras.length && html5QrCode) return html5QrCode.start(cameras[0].id, config, onScanCedula, function() {});
                throw err;
            }).catch(function(err) {
                lectorCedula.innerHTML = '<p class="text-white p-3">Error: ' + (err.message || err) + '. Use entrada manual.</p>';
            });
        }).catch(function() {
            tryStart({ video: true }).catch(function(err) {
                lectorCedula.innerHTML = '<p class="text-white p-3">No se pudo acceder a la cámara. Use entrada manual.</p>';
            });
        });
    }

    function onScanCedula(decodedText) {
        const parts = decodedText.split('|').map(p => p.trim());
        if (parts.length >= 2) {
            rutInput.value = formatearRut(parts[0]);
            nombreInput.value = parts[1] || '';
        } else if (parts.length === 1 && /^[0-9kK\-\.]+$/i.test(parts[0].replace(/\./g, ''))) {
            rutInput.value = formatearRut(parts[0]);
        }
    }

    function formatearRut(val) {
        let r = (val || '').replace(/[^0-9kK]/g, '').toUpperCase();
        if (r.length < 2) return r;
        return r.slice(0, -1).replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '-' + r.slice(-1);
    }

    rutManual.addEventListener('input', function() {
        rutInput.value = formatearRut(this.value);
    });
    nombreManual.addEventListener('input', function() {
        nombreInput.value = this.value;
    });

    // ——— OCR patente (vehicular) ———
    let videoStream = null;
    let intervalOCR = null;
    const videoPatente = document.getElementById('video-patente');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const patenteResult = document.getElementById('patente-result');

    function iniciarOCRPatente() {
        if (intervalOCR) return;
        var constraints = { video: { facingMode: 'environment', width: { ideal: 640 }, height: { ideal: 480 } }, audio: false };
        navigator.mediaDevices.getUserMedia(constraints)
            .then(function(stream) {
                videoStream = stream;
                videoPatente.srcObject = stream;
                return videoPatente.play();
            })
            .then(function() {
                intervalOCR = setInterval(capturarYReconocer, 2000);
            })
            .catch(function(err) {
                navigator.mediaDevices.getUserMedia({ video: true, audio: false }).then(function(stream) {
                    videoStream = stream;
                    videoPatente.srcObject = stream;
                    videoPatente.play().then(function() { intervalOCR = setInterval(capturarYReconocer, 2000); });
                }).catch(function(e) { console.warn('Cámara no disponible:', e); });
            });
    }

    function detenerOCRPatente() {
        if (intervalOCR) { clearInterval(intervalOCR); intervalOCR = null; }
        if (videoStream) {
            videoStream.getTracks().forEach(t => t.stop());
            videoStream = null;
        }
        videoPatente.srcObject = null;
    }

    function capturarYReconocer() {
        if (!videoPatente.srcObject || videoPatente.readyState < 2) return;
        ctx.drawImage(videoPatente, 0, 0, 640, 480);
        Tesseract.recognize(canvas, 'eng', {
            tessedit_char_whitelist: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789- '
        }).then(result => {
            const text = (result.data.text || '').trim().toUpperCase().replace(/\s/g, '');
            const match = text.match(/([A-Z]{4}\d{2}|[A-Z]{3}\d{3})/);
            if (match) patenteResult.value = match[1];
        }).catch(() => {});
    }

    // ——— Registrar ingreso ———
    btnRegistrar.addEventListener('click', function() {
        const tipo = tipoActual.value;
        let rut = '';
        let nombre = '';
        let patente = '';

        if (tipo === 'peatonal') {
            rut = (rutInput.value || rutManual.value || '').replace(/\s/g, '');
            nombre = (nombreInput.value || nombreManual.value || '').trim();
            if (!rut) {
                alert('Ingrese o escanee el RUT.');
                return;
            }
        } else {
            patente = (patenteResult.value || '').replace(/\s/g, '').toUpperCase();
            rut = (document.getElementById('conductor-rut').value || '').replace(/\s/g, '');
            if (!patente) {
                alert('Enfoque la patente o ingrésela manualmente.');
                return;
            }
        }

        btnRegistrar.disabled = true;
        alertaResultado.style.display = 'none';
        qrSalidaContainer.style.display = 'none';

        const payload = {
            tipo: tipo,
            rut: rut || null,
            nombre: nombre || null,
            patente: patente || null,
            _token: document.querySelector('meta[name="csrf-token"]').content
        };

        axios.post('{{ route("ingresos.store") }}', payload)
            .then(function(res) {
                if (res.data.success) {
                    alertaResultado.className = 'alert alert-success';
                    alertaResultado.textContent = res.data.message;
                    alertaResultado.style.display = 'block';
                    if (res.data.qr_salida_url) {
                        qrSalidaImg.innerHTML = '<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(res.data.qr_salida_url) + '" alt="QR Salida" class="img-fluid">';
                        qrSalidaContainer.style.display = 'block';
                    }
                    if (tipo === 'peatonal') {
                        rutInput.value = ''; nombreInput.value = ''; rutManual.value = ''; nombreManual.value = '';
                    } else {
                        patenteResult.value = ''; document.getElementById('conductor-rut').value = '';
                    }
                }
            })
            .catch(function(err) {
                const data = err.response && err.response.data;
                const msg = data && data.motivo ? 'Motivo: ' + data.motivo : (data && data.message ? data.message : 'Error al registrar.');
                alertaResultado.className = 'alert alert-danger';
                alertaResultado.textContent = msg;
                alertaResultado.style.display = 'block';
            })
            .finally(function() {
                btnRegistrar.disabled = false;
            });
    });

    // Iniciar cámara cuando el tab esté visible (evita que falle en tabs ocultos)
    var tabPeatonal = document.getElementById('peatonal');
    function iniciarCuandoVisible() {
        if (tabPeatonal && tabPeatonal.classList.contains('show')) {
            iniciarLectorCedula();
        } else {
            setTimeout(iniciarCuandoVisible, 200);
        }
    }
    setTimeout(iniciarCuandoVisible, 300);
});
</script>
@endpush
@endsection
