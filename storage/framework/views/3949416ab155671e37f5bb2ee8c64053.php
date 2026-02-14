<?php $__env->startPush('styles'); ?>
<style>
    #lector-cedula { width: 100%; min-height: 400px; position: relative; }
    #lector-cedula video { width: 100% !important; max-height: 400px; object-fit: cover; display: block; }
    #video-patente { width: 100%; max-height: 300px; object-fit: cover; }
    #canvas { display: none; }
    @media (max-width: 768px) {
        #lector-cedula { min-height: 320px; }
        #lector-cedula video { max-height: 320px; }
        #video-patente { max-height: 260px; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-100 flex">
    <?php if (isset($component)) { $__componentOriginal43bea641c2438270a49238c99ecefb58 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal43bea641c2438270a49238c99ecefb58 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.usuario.sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('usuario.sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal43bea641c2438270a49238c99ecefb58)): ?>
<?php $attributes = $__attributesOriginal43bea641c2438270a49238c99ecefb58; ?>
<?php unset($__attributesOriginal43bea641c2438270a49238c99ecefb58); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal43bea641c2438270a49238c99ecefb58)): ?>
<?php $component = $__componentOriginal43bea641c2438270a49238c99ecefb58; ?>
<?php unset($__componentOriginal43bea641c2438270a49238c99ecefb58); ?>
<?php endif; ?>
    <div class="flex-1 lg:ml-64">
        <?php if (isset($component)) { $__componentOriginal68a91bba458c966ce613394dc1ac6078 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal68a91bba458c966ce613394dc1ac6078 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.usuario.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('usuario.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal68a91bba458c966ce613394dc1ac6078)): ?>
<?php $attributes = $__attributesOriginal68a91bba458c966ce613394dc1ac6078; ?>
<?php unset($__attributesOriginal68a91bba458c966ce613394dc1ac6078); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal68a91bba458c966ce613394dc1ac6078)): ?>
<?php $component = $__componentOriginal68a91bba458c966ce613394dc1ac6078; ?>
<?php unset($__componentOriginal68a91bba458c966ce613394dc1ac6078); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal34cad1f9e1defdf87895216072b487b3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal34cad1f9e1defdf87895216072b487b3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.usuario.mobile-menu','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('usuario.mobile-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal34cad1f9e1defdf87895216072b487b3)): ?>
<?php $attributes = $__attributesOriginal34cad1f9e1defdf87895216072b487b3; ?>
<?php unset($__attributesOriginal34cad1f9e1defdf87895216072b487b3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal34cad1f9e1defdf87895216072b487b3)): ?>
<?php $component = $__componentOriginal34cad1f9e1defdf87895216072b487b3; ?>
<?php unset($__componentOriginal34cad1f9e1defdf87895216072b487b3); ?>
<?php endif; ?>

        <div class="container mx-auto px-4 py-6 max-w-4xl">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <svg class="w-8 h-8 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                    Control de acceso
                </h1>
                <a href="<?php echo e(route('ingresos.index')); ?>" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition text-sm">Ver listado</a>
            </div>

            
            <div class="flex border-b border-gray-200 mb-4">
                <button type="button" id="tab-peatonal" class="tab-btn px-4 py-2 font-medium rounded-t-lg bg-emerald-600 text-white" data-tab="peatonal">Peatonal (cédula)</button>
                <button type="button" id="tab-vehicular" class="tab-btn px-4 py-2 font-medium rounded-t-lg bg-gray-100 text-gray-600 hover:bg-gray-200" data-tab="vehicular">Vehicular (patente)</button>
            </div>

            <div id="panel-peatonal" class="tab-panel">
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-4">
                    <div class="p-6">
                        <p class="text-sm text-gray-500 mb-3">Escaneé el código de la cédula de identidad chilena (QR/DataMatrix) o ingrese datos manualmente.</p>
                        <div id="lector-cedula" class="bg-gray-900 rounded overflow-hidden mb-4"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">RUT</label>
                                <input type="text" id="rut" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 rut-input" placeholder="12.345.678-9" maxlength="12" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                                <input type="text" id="nombre" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" placeholder="Nombre completo" maxlength="100" readonly>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mb-2">Si el escáner no detecta, ingrese manualmente:</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" id="rut-manual" class="w-full px-3 py-2 border border-gray-300 rounded-lg rut-input" placeholder="RUT manual">
                            <input type="text" id="nombre-manual" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Nombre manual">
                        </div>
                    </div>
                </div>
            </div>

            <div id="panel-vehicular" class="tab-panel hidden">
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-4">
                    <div class="p-6">
                        <p class="text-sm text-gray-500 mb-3">Enfoque la patente del vehículo (formato ABCD12 o ABC123). Buena luz y 30–50 cm de distancia.</p>
                        <video id="video-patente" autoplay playsinline muted width="100%" height="300" class="rounded bg-gray-900"></video>
                        <canvas id="canvas" width="640" height="480"></canvas>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Patente detectada</label>
                                <input type="text" id="patente-result" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 uppercase" placeholder="ABCD12" maxlength="7" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">RUT conductor (opcional)</label>
                                <input type="text" id="conductor-rut" class="w-full px-3 py-2 border border-gray-300 rounded-lg rut-input" placeholder="12.345.678-9">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4">
                    <input type="hidden" id="tipo-actual" value="peatonal">
                    <button type="button" id="btn-registrar" class="w-full px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition">
                        Registrar ingreso
                    </button>
                </div>
            </div>

            <div id="alerta-resultado" class="mt-4 hidden p-4 rounded"></div>
            <div id="qr-salida-container" class="mt-4 text-center hidden">
                <p class="text-sm text-green-600 mb-2">Ingreso registrado. QR para registrar salida:</p>
                <div id="qr-salida-img"></div>
                <p class="text-sm text-gray-500 mt-2">Escanear al salir para registrar la salida.</p>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="<?php echo e(asset('js/rut-formatter.js')); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoActual = document.getElementById('tipo-actual');
    const btnRegistrar = document.getElementById('btn-registrar');
    const alertaResultado = document.getElementById('alerta-resultado');
    const qrSalidaContainer = document.getElementById('qr-salida-container');
    const qrSalidaImg = document.getElementById('qr-salida-img');

    function switchTab(tab) {
        tipoActual.value = tab;
        document.querySelectorAll('.tab-btn').forEach(function(btn) {
            if (btn.getAttribute('data-tab') === tab) {
                btn.classList.add('bg-emerald-600', 'text-white');
                btn.classList.remove('bg-gray-100', 'text-gray-600');
            } else {
                btn.classList.remove('bg-emerald-600', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-600');
            }
        });
        document.querySelectorAll('.tab-panel').forEach(function(panel) {
            panel.classList.toggle('hidden', panel.id !== 'panel-' + tab);
        });
        if (tab === 'vehicular') {
            iniciarOCRPatente();
        } else {
            detenerOCRPatente();
        }
    }

    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            switchTab(this.getAttribute('data-tab'));
        });
    });

    let html5QrCode = null;
    const lectorCedula = document.getElementById('lector-cedula');
    const rutInput = document.getElementById('rut');
    const nombreInput = document.getElementById('nombre');
    const rutManual = document.getElementById('rut-manual');
    const nombreManual = document.getElementById('nombre-manual');

    function aplicarZoomCamara(selectorContenedor) {
        var container = document.querySelector(selectorContenedor);
        var video = container ? container.querySelector('video') : null;
        if (video && video.srcObject) {
            var stream = video.srcObject;
            var track = stream.getVideoTracks && stream.getVideoTracks()[0];
            if (track && typeof track.applyConstraints === 'function')
                track.applyConstraints({ zoom: { ideal: 1.8 } }).catch(function() {});
        }
    }
    function iniciarLectorCedula() {
        if (html5QrCode && html5QrCode.isScanning) return;
        var config = { fps: 10, qrbox: function(w, h) { return { width: Math.max(200, Math.floor(w * 0.98)), height: Math.max(200, Math.floor(h * 0.98)) }; } };
        function tryStart(constraints) {
            if (html5QrCode && html5QrCode.isScanning) return Promise.resolve();
            if (!html5QrCode) html5QrCode = new Html5Qrcode('lector-cedula');
            return html5QrCode.start(constraints, config, onScanCedula, function() {}).catch(function(err) { throw err; });
        }
        function retryAfter(fn, ms) {
            return new Promise(function(resolve, reject) {
                setTimeout(function() { fn().then(resolve).catch(reject); }, ms);
            });
        }
        function cuandoInicio() { setTimeout(function() { aplicarZoomCamara('#lector-cedula'); }, 150); }
        Html5Qrcode.getCameras().then(function(cameras) {
            if (!cameras.length) {
                lectorCedula.innerHTML = '<p class="text-white p-3">No se detectó cámara. Use entrada manual.</p>';
                return;
            }
            tryStart({ facingMode: 'environment' })
                .catch(function() { return retryAfter(function() { return tryStart({ video: true }); }, 400); })
                .catch(function() { return retryAfter(function() {
                    if (cameras.length && html5QrCode) return html5QrCode.start(cameras[0].id, config, onScanCedula, function() {});
                    return Promise.reject(new Error('Sin cámara'));
                }, 400); })
                .then(cuandoInicio)
                .catch(function(err) {
                    lectorCedula.innerHTML = '<p class="text-white p-3">Error: ' + (err.message || err) + '. Use entrada manual.</p>';
                });
        }).catch(function() {
            retryAfter(function() { return tryStart({ video: true }); }, 400).then(cuandoInicio).catch(function(err) {
                lectorCedula.innerHTML = '<p class="text-white p-3">No se pudo acceder a la cámara. Use entrada manual.</p>';
            });
        });
    }

    function onScanCedula(decodedText) {
        var parts = decodedText.split('|').map(function(p) { return p.trim(); });
        if (parts.length >= 2) {
            rutInput.value = formatearRut(parts[0]);
            nombreInput.value = parts[1] || '';
        } else if (parts.length === 1 && /^[0-9kK\-\.]+$/i.test(parts[0].replace(/\./g, ''))) {
            rutInput.value = formatearRut(parts[0]);
        }
    }

    function formatearRut(val) {
        var r = (val || '').replace(/[^0-9kK]/g, '').toUpperCase();
        if (r.length < 2) return r;
        return r.slice(0, -1).replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '-' + r.slice(-1);
    }

    rutManual.addEventListener('input', function() { rutInput.value = formatearRut(this.value); });
    nombreManual.addEventListener('input', function() { nombreInput.value = this.value; });

    var videoStream = null, intervalOCR = null;
    var videoPatente = document.getElementById('video-patente');
    var canvas = document.getElementById('canvas');
    var ctx = canvas.getContext('2d');
    var patenteResult = document.getElementById('patente-result');

    function iniciarOCRPatente() {
        if (intervalOCR) return;
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment', width: { ideal: 640 }, height: { ideal: 480 }, zoom: { ideal: 1.8 } }, audio: false })
            .then(function(stream) {
                videoStream = stream;
                videoPatente.srcObject = stream;
                return videoPatente.play();
            }).then(function() { intervalOCR = setInterval(capturarYReconocer, 2000); })
            .catch(function() {
                navigator.mediaDevices.getUserMedia({ video: { zoom: { ideal: 1.8 } }, audio: false }).then(function(stream) {
                    videoStream = stream;
                    videoPatente.srcObject = stream;
                    videoPatente.play().then(function() { intervalOCR = setInterval(capturarYReconocer, 2000); });
                }).catch(function() {});
            });
    }

    function detenerOCRPatente() {
        if (intervalOCR) { clearInterval(intervalOCR); intervalOCR = null; }
        if (videoStream) { videoStream.getTracks().forEach(function(t) { t.stop(); }); videoStream = null; }
        videoPatente.srcObject = null;
    }

    function capturarYReconocer() {
        if (!videoPatente.srcObject || videoPatente.readyState < 2) return;
        ctx.drawImage(videoPatente, 0, 0, 640, 480);
        Tesseract.recognize(canvas, 'eng', { tessedit_char_whitelist: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789- ' })
            .then(function(result) {
                var text = (result.data.text || '').trim().toUpperCase().replace(/\s/g, '');
                var match = text.match(/([A-Z]{4}\d{2}|[A-Z]{3}\d{3})/);
                if (match) patenteResult.value = match[1];
            }).catch(function() {});
    }

    btnRegistrar.addEventListener('click', function() {
        var tipo = tipoActual.value;
        var rut = '', nombre = '', patente = '';
        if (tipo === 'peatonal') {
            rut = (rutInput.value || rutManual.value || '').replace(/\s/g, '');
            nombre = (nombreInput.value || nombreManual.value || '').trim();
            if (!rut) { alert('Ingrese o escanee el RUT.'); return; }
        } else {
            patente = (patenteResult.value || '').replace(/\s/g, '').toUpperCase();
            rut = (document.getElementById('conductor-rut').value || '').replace(/\s/g, '');
            if (!patente) { alert('Enfoque la patente o ingrésela manualmente.'); return; }
        }
        btnRegistrar.disabled = true;
        alertaResultado.classList.add('hidden');
        qrSalidaContainer.classList.add('hidden');
        var payload = { tipo: tipo, rut: rut || null, nombre: nombre || null, patente: patente || null, _token: document.querySelector('meta[name="csrf-token"]').content };
        axios.post('<?php echo e(route("ingresos.store")); ?>', payload)
            .then(function(res) {
                if (res.data.success) {
                    alertaResultado.className = 'mt-4 p-4 rounded bg-green-100 text-green-800';
                    alertaResultado.textContent = res.data.message;
                    alertaResultado.classList.remove('hidden');
                    if (res.data.qr_salida_url) {
                        qrSalidaImg.innerHTML = '<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(res.data.qr_salida_url) + '" alt="QR Salida" class="mx-auto rounded-lg">';
                        qrSalidaContainer.classList.remove('hidden');
                    }
                    if (tipo === 'peatonal') { rutInput.value = ''; nombreInput.value = ''; rutManual.value = ''; nombreManual.value = ''; }
                    else { patenteResult.value = ''; document.getElementById('conductor-rut').value = ''; }
                }
            })
            .catch(function(err) {
                var data = err.response && err.response.data;
                var msg = data && data.motivo ? 'Motivo: ' + data.motivo : (data && data.message ? data.message : 'Error al registrar.');
                alertaResultado.className = 'mt-4 p-4 rounded bg-red-100 text-red-800';
                alertaResultado.textContent = msg;
                alertaResultado.classList.remove('hidden');
            })
            .finally(function() { btnRegistrar.disabled = false; });
    });

    var tabPeatonal = document.getElementById('panel-peatonal');
    function iniciarCuandoVisible() {
        if (tabPeatonal && !tabPeatonal.classList.contains('hidden')) {
            iniciarLectorCedula();
        } else {
            setTimeout(iniciarCuandoVisible, 200);
        }
    }
    setTimeout(iniciarCuandoVisible, 300);
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.usuario', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/ingresos/escaner.blade.php ENDPATH**/ ?>