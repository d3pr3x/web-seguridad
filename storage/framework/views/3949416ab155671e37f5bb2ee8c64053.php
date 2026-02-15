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
                        <p class="text-sm text-gray-500 mb-3">Encuadre el QR de la cédula y pulse «Capturar y leer QR».</p>
                        <div id="lector-cedula" class="bg-gray-900 rounded overflow-hidden mb-2">
                            <video id="video-cedula" autoplay playsinline muted class="w-full max-h-[400px] object-cover block"></video>
                            <p id="mensaje-captura-cedula" class="text-white text-center text-sm py-2 hidden"></p>
                            <button type="button" id="btn-capturar-cedula" class="w-full py-3 mt-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition disabled:opacity-50">
                                Capturar y leer QR
                            </button>
                        </div>
                        <canvas id="canvas-cedula" style="display:none"></canvas>
                        <canvas id="canvas-cedula-espejo" style="display:none"></canvas>
                        <div id="dummy-cedula" style="width:1px;height:1px;overflow:hidden;position:absolute;opacity:0;pointer-events:none;"></div>
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
                        <details class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
                            <summary class="px-4 py-3 bg-gray-50 font-medium text-gray-800 cursor-pointer select-none">Ver proceso de captura (consola)</summary>
                            <div id="log-qr" class="p-3 bg-gray-900 text-green-400 text-xs font-mono max-h-48 overflow-y-auto whitespace-pre-wrap break-all"></div>
                        </details>
                    </div>
                </div>
            </div>

            <div id="panel-vehicular" class="tab-panel hidden">
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-4">
                    <div class="p-6">
                        <p class="text-sm text-gray-500 mb-3">Encuadre la patente del vehículo y pulse «Capturar y leer patente».</p>
                        <video id="video-patente" autoplay playsinline muted width="100%" height="300" class="rounded bg-gray-900"></video>
                        <p id="mensaje-captura-patente" class="text-center text-sm py-2 hidden"></p>
                        <button type="button" id="btn-capturar-patente" class="w-full py-3 mt-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition disabled:opacity-50">
                            Capturar y leer patente
                        </button>
                        <canvas id="canvas" width="640" height="480" style="display:none"></canvas>
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
                        <details class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
                            <summary class="px-4 py-3 bg-gray-50 font-medium text-gray-800 cursor-pointer select-none">Ver proceso de captura (consola)</summary>
                            <div id="log-patente" class="p-3 bg-gray-900 text-green-400 text-xs font-mono max-h-48 overflow-y-auto whitespace-pre-wrap break-all"></div>
                        </details>
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
<script src="https://cdn.jsdelivr.net/npm/qr-scanner@1.4.2/qr-scanner.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
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
            detenerCedula();
            iniciarCamaraPatente();
        } else {
            detenerCamaraPatente();
            iniciarLectorCedula();
        }
    }

    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            switchTab(this.getAttribute('data-tab'));
        });
    });

    var streamCedula = null;
    const videoCedula = document.getElementById('video-cedula');
    const btnCapturarCedula = document.getElementById('btn-capturar-cedula');
    const canvasCedula = document.getElementById('canvas-cedula');
    const lectorCedula = document.getElementById('lector-cedula');
    const rutInput = document.getElementById('rut');
    const nombreInput = document.getElementById('nombre');
    const rutManual = document.getElementById('rut-manual');
    const nombreManual = document.getElementById('nombre-manual');

    function iniciarLectorCedula() {
        if (videoCedula.srcObject) return;
        var constraints = { video: { facingMode: 'environment' }, audio: false };
        navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
            streamCedula = stream;
            videoCedula.srcObject = stream;
            return videoCedula.play();
        }).catch(function() {
            return navigator.mediaDevices.getUserMedia({ video: true, audio: false }).then(function(stream) {
                streamCedula = stream;
                videoCedula.srcObject = stream;
                return videoCedula.play();
            });
        }).then(function() {
            btnCapturarCedula.disabled = false;
        }).catch(function(err) {
            lectorCedula.innerHTML = '<p class="text-white p-3">No se pudo acceder a la cámara. Use entrada manual.</p>';
        });
    }

    var mensajeCapturaCedula = document.getElementById('mensaje-captura-cedula');
    var logQR = document.getElementById('log-qr');
    function escribirLogQR(linea) {
        if (!logQR) return;
        var t = new Date().toLocaleTimeString('es-CL', { hour12: false });
        logQR.textContent += '[' + t + '] ' + linea + '\n';
        logQR.scrollTop = logQR.scrollHeight;
    }
    function limpiarLogQR() {
        if (logQR) logQR.textContent = '';
    }
    function capturarYLeerQR() {
        if (!videoCedula.srcObject || videoCedula.readyState < 2) {
            alert('Espere a que la cámara esté lista.');
            return;
        }
        var w = videoCedula.videoWidth;
        var h = videoCedula.videoHeight;
        if (!w || !h) {
            alert('La cámara no tiene tamaño aún. Espere un momento.');
            return;
        }
        limpiarLogQR();
        escribirLogQR('1. Iniciando captura…');
        btnCapturarCedula.disabled = true;
        mensajeCapturaCedula.textContent = 'Procesando…';
        mensajeCapturaCedula.classList.remove('hidden');
        requestAnimationFrame(function() {
        var maxLado = 1920;
        if (w > maxLado || h > maxLado) {
            var scale = Math.min(maxLado / w, maxLado / h);
            w = Math.round(w * scale);
            h = Math.round(h * scale);
        }
        canvasCedula.width = w;
        canvasCedula.height = h;
        var ctx = canvasCedula.getContext('2d', { willReadFrequently: true });
        ctx.drawImage(videoCedula, 0, 0, w, h);
        escribirLogQR('2. Imagen capturada (' + w + '×' + h + ' px)');
        function terminar() {
            mensajeCapturaCedula.classList.add('hidden');
            mensajeCapturaCedula.textContent = '';
            btnCapturarCedula.disabled = false;
        }
        function exitoQR(decodedText) {
            escribirLogQR('4. QR obtenido: ' + (decodedText.length > 60 ? decodedText.slice(0, 60) + '…' : decodedText));
            onScanCedula(decodedText);
            var runMatch = decodedText.match(/[?&]RUN=([^&\s]+)/i) || decodedText.match(/RUN=([^&\s]+)/i);
            if (runMatch) escribirLogQR('5. RUT extraído: ' + formatearRut(runMatch[1].trim()));
            var parts = decodedText.split('|').map(function(p) { return p.trim(); }).filter(Boolean);
            if (parts.length >= 2) escribirLogQR('   Nombre: ' + parts.slice(1).join(' '));
            escribirLogQR('--- Listo.');
            terminar();
        }
        function decodificarConJsQR(canvasEl) {
            try {
                if (typeof jsQR === 'undefined') return null;
                var ctxEl = canvasEl.getContext('2d');
                var imageData = ctxEl.getImageData(0, 0, canvasEl.width, canvasEl.height);
                var code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'attemptBoth' });
                return code ? code.data : null;
            } catch (e) { return null; }
        }
        function canvasRecorteIzquierdo(srcCanvas, fraccionAncho) {
            var c = document.createElement('canvas');
            var sw = srcCanvas.width, sh = srcCanvas.height;
            c.width = Math.max(80, Math.round(sw * fraccionAncho));
            c.height = sh;
            var ctx = c.getContext('2d');
            ctx.drawImage(srcCanvas, 0, 0, c.width, sh, 0, 0, c.width, sh);
            return c;
        }
        function canvasBinarizar(srcCanvas, umbral) {
            var c = document.createElement('canvas');
            c.width = srcCanvas.width;
            c.height = srcCanvas.height;
            var ctx = srcCanvas.getContext('2d');
            var img = ctx.getImageData(0, 0, c.width, c.height);
            var d = img.data;
            var t = umbral != null ? umbral : 128;
            for (var i = 0; i < d.length; i += 4) {
                var L = 0.299 * d[i] + 0.587 * d[i+1] + 0.114 * d[i+2];
                var v = L >= t ? 255 : 0;
                d[i] = d[i+1] = d[i+2] = v;
            }
            c.getContext('2d').putImageData(img, 0, 0);
            return c;
        }
        function canvasConMasContraste(srcCanvas) {
            var c = document.createElement('canvas');
            c.width = srcCanvas.width;
            c.height = srcCanvas.height;
            var ctx = srcCanvas.getContext('2d');
            var img = ctx.getImageData(0, 0, c.width, c.height);
            var d = img.data;
            var lums = [];
            for (var i = 0; i < d.length; i += 4) {
                lums.push(0.299 * d[i] + 0.587 * d[i+1] + 0.114 * d[i+2]);
            }
            lums.sort(function(a,b) { return a - b; });
            var p2 = lums[Math.floor(lums.length * 0.02)] || 0;
            var p98 = lums[Math.floor(lums.length * 0.98)] || 255;
            var span = p98 - p2 || 1;
            for (i = 0; i < d.length; i += 4) {
                var L = 0.299 * d[i] + 0.587 * d[i+1] + 0.114 * d[i+2];
                var v = Math.round((L - p2) * 255 / span);
                v = v < 0 ? 0 : v > 255 ? 255 : v;
                d[i] = d[i+1] = d[i+2] = v;
            }
            var ctxOut = c.getContext('2d');
            ctxOut.putImageData(img, 0, 0);
            return c;
        }
        function decodificarConBarcodeDetector(canvasEl) {
            if (typeof BarcodeDetector === 'undefined') return Promise.resolve(null);
            return new BarcodeDetector({ formats: ['qr_code'] }).detect(canvasEl)
                .then(function(barcodes) { return barcodes.length ? barcodes[0].rawValue : null; })
                .catch(function() { return null; });
        }
        function intentarDecodificar() {
            function seguirConJsQR() {
            escribirLogQR('3. Decodificando QR (jsQR, imagen original)…');
            var decoded = decodificarConJsQR(canvasCedula);
            if (decoded) { exitoQR(decoded); return; }
            escribirLogQR('3a. Probando recorte zona izquierda (QR cédula)…');
            var cIzq40 = canvasRecorteIzquierdo(canvasCedula, 0.4);
            decoded = decodificarConJsQR(cIzq40);
            if (decoded) { exitoQR(decoded); return; }
            decoded = decodificarConJsQR(canvasRecorteIzquierdo(canvasCedula, 0.35));
            if (decoded) { exitoQR(decoded); return; }
            escribirLogQR('3b. Probando imagen espejo…');
            var c2 = document.getElementById('canvas-cedula-espejo');
            c2.width = w;
            c2.height = h;
            var ctx2 = c2.getContext('2d');
            ctx2.translate(w, 0);
            ctx2.scale(-1, 1);
            ctx2.drawImage(canvasCedula, 0, 0);
            ctx2.setTransform(1, 0, 0, 1, 0, 0);
            decoded = decodificarConJsQR(c2);
            if (decoded) { exitoQR(decoded); return; }
            escribirLogQR('3c. Probando con más contraste (cédula)…');
            var cContraste = canvasConMasContraste(canvasCedula);
            decoded = decodificarConJsQR(cContraste);
            if (decoded) { exitoQR(decoded); return; }
            decoded = decodificarConJsQR(canvasConMasContraste(c2));
            if (decoded) { exitoQR(decoded); return; }
            decoded = decodificarConJsQR(canvasConMasContraste(cIzq40));
            if (decoded) { exitoQR(decoded); return; }
            escribirLogQR('3c2. Probando binarizado (recorte 40%)…');
            for (var ub = 0; ub < 3; ub++) {
                var umbral = [128, 140, 110][ub];
                decoded = decodificarConJsQR(canvasBinarizar(cIzq40, umbral));
                if (decoded) { exitoQR(decoded); return; }
            }
            escribirLogQR('3d. Probando recorte izquierdo 2x…');
            var cIzq2x = document.createElement('canvas');
            cIzq2x.width = Math.min(cIzq40.width * 2, 1920);
            cIzq2x.height = Math.min(h * 2, 1920);
            var ctxIzq2 = cIzq2x.getContext('2d');
            ctxIzq2.imageSmoothingEnabled = true;
            ctxIzq2.imageSmoothingQuality = 'high';
            ctxIzq2.drawImage(cIzq40, 0, 0, cIzq40.width, cIzq40.height, 0, 0, cIzq2x.width, cIzq2x.height);
            decoded = decodificarConJsQR(cIzq2x);
            if (decoded) { exitoQR(decoded); return; }
            decoded = decodificarConJsQR(canvasConMasContraste(cIzq2x));
            if (decoded) { exitoQR(decoded); return; }
            escribirLogQR('3e. Probando imagen completa 2x…');
            var w2 = Math.min(w * 2, 1920), h2 = Math.min(h * 2, 1920);
            if (w2 > w || h2 > h) {
                var cBig = document.createElement('canvas');
                cBig.width = w2;
                cBig.height = h2;
                var ctxBig = cBig.getContext('2d');
                ctxBig.imageSmoothingEnabled = true;
                ctxBig.imageSmoothingQuality = 'high';
                ctxBig.drawImage(canvasCedula, 0, 0, w, h, 0, 0, w2, h2);
                decoded = decodificarConJsQR(cBig);
                if (decoded) { exitoQR(decoded); return; }
                decoded = decodificarConJsQR(canvasConMasContraste(cBig));
                if (decoded) { exitoQR(decoded); return; }
            }
            escribirLogQR('3f. Probando con Html5Qrcode (archivo)…');
            canvasCedula.toBlob(function(blob) {
                if (!blob) {
                    var dataUrl = canvasCedula.toDataURL('image/png');
                    fetch(dataUrl).then(function(r) { return r.blob(); }).then(function(b) {
                        procesarConArchivo(b, null, null);
                    }).catch(falloQR);
                    return;
                }
                var cContrasteBlob = canvasConMasContraste(canvasCedula);
                cContrasteBlob.toBlob(function(blobContraste) {
                    cIzq40.toBlob(function(blobIzq) {
                        procesarConArchivo(blob, blobContraste, blobIzq);
                    }, 'image/png', 1);
                }, 'image/png', 1);
            }, 'image/png', 1);
        }
            escribirLogQR('3. Probando BarcodeDetector (nativo)…');
            if (typeof QrScanner !== 'undefined') QrScanner.WORKER_PATH = 'https://cdn.jsdelivr.net/npm/qr-scanner@1.4.2/qr-scanner-worker.min.js';
            decodificarConBarcodeDetector(canvasCedula).then(function(decoded) {
                if (decoded) { exitoQR(decoded); return; }
                escribirLogQR('3a0. Probando BarcodeDetector en recorte izquierdo…');
                return decodificarConBarcodeDetector(canvasRecorteIzquierdo(canvasCedula, 0.4));
            }).then(function(decoded) {
                if (decoded) { exitoQR(decoded); return; }
                if (typeof QrScanner !== 'undefined') {
                    escribirLogQR('3a0b. Probando QrScanner…');
                    return QrScanner.scanImage(canvasCedula).then(function(r) { return typeof r === 'string' ? r : (r && r.data) || null; }).catch(function() { return null; });
                }
                return null;
            }).then(function(decoded) {
                if (decoded) { exitoQR(decoded); return; }
                if (typeof QrScanner !== 'undefined') {
                    return QrScanner.scanImage(canvasRecorteIzquierdo(canvasCedula, 0.4)).then(function(r) { return typeof r === 'string' ? r : (r && r.data) || null; }).catch(function() { return null; });
                }
                return null;
            }).then(function(decoded) {
                if (decoded) { exitoQR(decoded); return; }
                seguirConJsQR();
            }).catch(function() { seguirConJsQR(); });
        }
        function procesarConArchivo(blob, blobContraste, blobIzq) {
            var file = new File([blob], 'captura.png', { type: 'image/png' });
            var scanner = new Html5Qrcode('dummy-cedula');
            function intentarConEspejo() {
                escribirLogQR('3h. Reintento con imagen espejo (archivo)…');
                var c2 = document.getElementById('canvas-cedula-espejo');
                c2.width = w;
                c2.height = h;
                var ctx2 = c2.getContext('2d');
                ctx2.translate(w, 0);
                ctx2.scale(-1, 1);
                ctx2.drawImage(canvasCedula, 0, 0);
                ctx2.setTransform(1, 0, 0, 1, 0, 0);
                c2.toBlob(function(blob2) {
                    if (blob2) {
                        var file2 = new File([blob2], 'captura-espejo.png', { type: 'image/png' });
                        new Html5Qrcode('dummy-cedula').scanFile(file2, false).then(exitoQR).catch(falloQR);
                    } else falloQR();
                }, 'image/png', 1);
            }
            function intentarRecorteIzq() {
                if (blobIzq) {
                    escribirLogQR('3g2. Reintento con recorte izquierdo (archivo)…');
                    var fileIzq = new File([blobIzq], 'captura-izq.png', { type: 'image/png' });
                    new Html5Qrcode('dummy-cedula').scanFile(fileIzq, false).then(exitoQR).catch(intentarConEspejo);
                } else intentarConEspejo();
            }
            scanner.scanFile(file, false).then(exitoQR).catch(function() {
                if (blobContraste) {
                    escribirLogQR('3g. Reintento con imagen con más contraste (archivo)…');
                    var fileC = new File([blobContraste], 'captura-contraste.png', { type: 'image/png' });
                    new Html5Qrcode('dummy-cedula').scanFile(fileC, false).then(exitoQR).catch(intentarRecorteIzq);
                } else intentarRecorteIzq();
            });
        }
        function falloQR() {
            escribirLogQR('4. No se detectó QR en la imagen.');
            escribirLogQR('--- Fallo.');
            alert('No se detectó un QR. Encuadre bien el código, asegure buena luz y vuelva a capturar.');
            terminar();
        }
        setTimeout(intentarDecodificar, 100);
        });
    }
    if (btnCapturarCedula) btnCapturarCedula.addEventListener('click', capturarYLeerQR);
    if (btnCapturarCedula) btnCapturarCedula.disabled = true;

    function onScanCedula(decodedText) {
        var runMatch = decodedText.match(/[?&]RUN=([^&\s]+)/i) || decodedText.match(/RUN=([^&\s]+)/i);
        if (runMatch) {
            rutInput.value = formatearRut(runMatch[1].trim());
        }
        var parts = decodedText.split('|').map(function(p) { return p.trim(); }).filter(Boolean);
        if (parts.length >= 2) {
            if (!runMatch) rutInput.value = formatearRut(parts[0]);
            nombreInput.value = parts.slice(1).join(' ').trim();
        } else if (parts.length === 1 && !runMatch && /^[0-9kK\-\.]+$/i.test(parts[0].replace(/\./g, ''))) {
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

    function detenerCedula() {
        if (streamCedula) {
            streamCedula.getTracks().forEach(function(t) { t.stop(); });
            streamCedula = null;
        }
        videoCedula.srcObject = null;
    }

    var streamPatente = null;
    var videoPatente = document.getElementById('video-patente');
    var btnCapturarPatente = document.getElementById('btn-capturar-patente');
    var canvas = document.getElementById('canvas');
    var ctx = canvas.getContext('2d');
    var patenteResult = document.getElementById('patente-result');

    function iniciarCamaraPatente() {
        if (videoPatente.srcObject) return;
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment', width: { ideal: 640 }, height: { ideal: 480 } }, audio: false })
            .then(function(stream) {
                streamPatente = stream;
                videoPatente.srcObject = stream;
                return videoPatente.play();
            })
            .catch(function() {
                return navigator.mediaDevices.getUserMedia({ video: true, audio: false }).then(function(stream) {
                    streamPatente = stream;
                    videoPatente.srcObject = stream;
                    return videoPatente.play();
                });
            })
            .catch(function() {});
    }

    function detenerCamaraPatente() {
        if (streamPatente) {
            streamPatente.getTracks().forEach(function(t) { t.stop(); });
            streamPatente = null;
        }
        videoPatente.srcObject = null;
    }

    var mensajeCapturaPatente = document.getElementById('mensaje-captura-patente');
    var logPatente = document.getElementById('log-patente');
    function escribirLogPatente(linea) {
        if (!logPatente) return;
        var t = new Date().toLocaleTimeString('es-CL', { hour12: false });
        logPatente.textContent += '[' + t + '] ' + linea + '\n';
        logPatente.scrollTop = logPatente.scrollHeight;
    }
    function limpiarLogPatente() {
        if (logPatente) logPatente.textContent = '';
    }
    function capturarYLeerPatente() {
        if (!videoPatente.srcObject || videoPatente.readyState < 2) {
            alert('Espere a que la cámara esté lista.');
            return;
        }
        limpiarLogPatente();
        escribirLogPatente('1. Iniciando captura…');
        ctx.drawImage(videoPatente, 0, 0, 640, 480);
        escribirLogPatente('2. Imagen capturada (640×480 px)');
        btnCapturarPatente.disabled = true;
        mensajeCapturaPatente.textContent = 'Procesando…';
        mensajeCapturaPatente.classList.remove('hidden');
        setTimeout(function() {
            escribirLogPatente('3. Reconociendo texto (OCR)…');
            Tesseract.recognize(canvas, 'eng', { tessedit_char_whitelist: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', tessedit_pageseg_mode: '8' })
                .then(function(result) {
                    var raw = (result.data.text || '').trim();
                    var text = raw.toUpperCase().replace(/\s/g, '').replace(/[^A-Z0-9]/g, '');
                    escribirLogPatente('4. OCR crudo: "' + (raw.slice(0, 80) || '(vacío)') + (raw.length > 80 ? '…' : '') + '"');
                    escribirLogPatente('   Normalizado: ' + (text.slice(0, 40) || '(vacío)'));
                    var match = text.match(/([A-Z]{4}\d{2}|[A-Z]{3}\d{3})/);
                    if (match) {
                        patenteResult.value = match[1];
                        escribirLogPatente('5. Patente detectada: ' + match[1]);
                        escribirLogPatente('--- Listo.');
                    } else {
                        escribirLogPatente('5. No se encontró patente en el texto.');
                        escribirLogPatente('--- Fallo.');
                        alert('No se detectó una patente en la imagen. Encuadre bien la patente y vuelva a capturar.');
                    }
                })
                .catch(function(err) {
                    escribirLogPatente('4. Error OCR: ' + (err && err.message ? err.message : 'Error'));
                    escribirLogPatente('--- Fallo.');
                    alert('Error al leer la imagen.');
                })
                .finally(function() {
                    mensajeCapturaPatente.classList.add('hidden');
                    mensajeCapturaPatente.textContent = '';
                    btnCapturarPatente.disabled = false;
                });
        }, 100);
    }
    if (btnCapturarPatente) btnCapturarPatente.addEventListener('click', capturarYLeerPatente);

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