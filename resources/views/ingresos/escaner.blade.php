@extends(isset($modo_qr_automatico) && $modo_qr_automatico ? 'layouts.qr-automatico' : 'layouts.usuario')

@push('styles')
<style>
    #lector-cedula { width: 100%; position: relative; }
    #lector-cedula video { width: 100% !important; max-height: 50vh; min-height: 220px; object-fit: cover; display: block; }
    #video-patente { width: 100%; max-height: 40vh; object-fit: cover; }
    #canvas { display: none; }
    @media (max-width: 768px) {
        #lector-cedula video { min-height: 200px; max-height: 45vh; }
    }
</style>
@endpush

@section('content')
@if(isset($modo_qr_automatico) && $modo_qr_automatico)
{{-- Vista /qr-automatico: mismo escáner de carnet (Capturar y leer), registro automático --}}
<div class="w-full max-w-md mx-auto">
    <p class="text-center text-slate-400 text-sm mb-4">Encuadre el código QR del carnet (cédula) y pulse «Capturar y leer». Se registrará el ingreso peatonal automáticamente.</p>
    <div id="lector-cedula" class="bg-gray-900 rounded-xl overflow-hidden border-2 border-teal-500 relative">
        <video id="video-cedula" autoplay playsinline muted class="w-full max-h-[50vh] min-h-[240px] object-cover block"></video>
        <p id="mensaje-captura-cedula" class="text-white text-center text-sm py-2 hidden"></p>
        <div class="flex flex-col gap-2 p-3">
            <button type="button" id="btn-capturar-cedula" class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition disabled:opacity-50" disabled>
                Capturar y leer
            </button>
            <button type="button" id="btn-capturar-cedula-reintentar" class="w-full py-2.5 border border-amber-400 bg-amber-500/20 hover:bg-amber-500/30 text-amber-200 font-medium rounded-lg transition hidden">
                Capturar de nuevo
            </button>
        </div>
    </div>
    <canvas id="canvas-cedula" style="display:none"></canvas>
    <canvas id="canvas-cedula-espejo" style="display:none"></canvas>
    <div id="dummy-cedula" style="width:1px;height:1px;overflow:hidden;position:absolute;opacity:0;pointer-events:none;"></div>
    <div id="ingreso-manual-peatonal" class="hidden"></div>
    <input type="text" id="rut" class="hidden" aria-hidden="true">
    <input type="text" id="nombre" class="hidden" aria-hidden="true">
    <input type="text" id="rut-manual" class="hidden" aria-hidden="true">
    <input type="text" id="nombre-manual" class="hidden" aria-hidden="true">
    <div id="bloque-registrar" class="hidden"></div>
    <input type="hidden" id="tipo-actual" value="peatonal">
    <div id="alerta-resultado" class="mt-4 hidden p-4 rounded"></div>
    <div id="qr-salida-container" class="mt-4 text-center hidden">
        <p class="text-sm text-emerald-400 mb-2">Ingreso registrado. QR para registrar salida:</p>
        <div id="qr-salida-img"></div>
        <p class="text-sm text-slate-500 mt-2">Escanear al salir para registrar la salida.</p>
        <button type="button" id="btn-escaner-otro" class="mt-3 w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition text-sm">Escanear otro ingreso</button>
    </div>
    <div class="text-center mt-4">
        <a href="{{ route('ingresos.index') }}" class="btn-volver">Volver a Ingresos</a>
    </div>
</div>
@else
{{-- Vista ingresos/escaner completa (tabs peatonal + vehicular) --}}
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-4xl">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <svg class="w-8 h-8 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                    Control de acceso
                </h1>
                <a href="{{ route('ingresos.index') }}" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition text-sm">Ver listado</a>
            </div>

            {{-- Tabs --}}
            <div class="flex border-b border-gray-200 mb-4">
                <button type="button" id="tab-peatonal" class="tab-btn px-4 py-2 font-medium rounded-t-lg bg-emerald-600 text-white" data-tab="peatonal">Peatonal (cédula)</button>
                <button type="button" id="tab-vehicular" class="tab-btn px-4 py-2 font-medium rounded-t-lg bg-gray-100 text-gray-600 hover:bg-gray-200" data-tab="vehicular">Vehicular (patente)</button>
            </div>

            <div id="panel-peatonal" class="tab-panel">
                {{-- Orden para móvil: de arriba a abajo = cámara, RUT, Nombre. Al cargar se hace scroll al final para ver de abajo hacia arriba: Nombre, RUT, cámara. --}}
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-4">
                    <div class="p-4 pb-6 flex flex-col gap-4">
                        {{-- 1. Cámara (arriba en DOM = se ve arriba tras scroll) --}}
                        <div id="lector-cedula" class="bg-gray-900 rounded-xl overflow-hidden order-first relative">
                            <video id="video-cedula" autoplay playsinline muted class="w-full max-h-[50vh] min-h-[240px] object-cover block"></video>
                            <p id="mensaje-captura-cedula" class="text-white text-center text-sm py-2 hidden"></p>
                            <div class="flex flex-col gap-2 p-3">
                                <button type="button" id="btn-capturar-cedula" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition disabled:opacity-50" disabled>
                                    Capturar y leer
                                </button>
                                <button type="button" id="btn-capturar-cedula-reintentar" class="w-full py-2.5 border border-amber-400 bg-amber-50 hover:bg-amber-100 text-amber-800 font-medium rounded-lg transition hidden">
                                    Capturar de nuevo
                                </button>
                            </div>
                        </div>
                        <canvas id="canvas-cedula" style="display:none"></canvas>
                        <canvas id="canvas-cedula-espejo" style="display:none"></canvas>
                        <div id="dummy-cedula" style="width:1px;height:1px;overflow:hidden;position:absolute;opacity:0;pointer-events:none;"></div>

                        {{-- 2. RUT obtenido --}}
                        <div class="order-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">RUT</label>
                            <input type="text" id="rut" class="w-full px-3 py-3 border border-gray-200 rounded-lg bg-gray-50 rut-input text-gray-800" placeholder="Se obtiene al escanear" maxlength="12" readonly>
                        </div>
                        {{-- 3. Nombre --}}
                        <div class="order-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input type="text" id="nombre" class="w-full px-3 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-800" placeholder="Se completa al escanear o manual" maxlength="100" readonly>
                        </div>

                        {{-- 4. Ingreso manual (solo si falla la captura) --}}
                        <div id="ingreso-manual-peatonal" class="order-4 hidden border-t border-gray-200 pt-4 mt-2">
                            <p class="text-sm text-amber-700 mb-3">No se detectó el código. Ingrese manualmente:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <input type="text" id="rut-manual" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg rut-input" placeholder="RUT">
                                <input type="text" id="nombre-manual" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg" placeholder="Nombre">
                            </div>
                        </div>
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
                    </div>
                </div>
            </div>

            <div id="bloque-registrar" class="bg-white rounded-lg shadow-md overflow-hidden hidden">
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
@endif

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qr-scanner@1.4.2/qr-scanner.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script src="https://unpkg.com/@zxing/browser@0.1.5/umd/zxing-browser.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ asset('js/rut-formatter.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var MODO_QR_AUTOMATICO = {{ (isset($modo_qr_automatico) && $modo_qr_automatico) ? 'true' : 'false' }};
    const tipoActual = document.getElementById('tipo-actual');
    const btnRegistrar = document.getElementById('btn-registrar');
    const bloqueRegistrar = document.getElementById('bloque-registrar');
    const alertaResultado = document.getElementById('alerta-resultado');
    const qrSalidaContainer = document.getElementById('qr-salida-container');
    const qrSalidaImg = document.getElementById('qr-salida-img');

    function scrollAlFinalEscaner() {
        requestAnimationFrame(function() {
            var panel = document.getElementById('panel-peatonal');
            if (panel && !panel.classList.contains('hidden')) {
                panel.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }
        });
    }

    function actualizarVisibilidadRegistrar() {
        var tipo = tipoActual.value;
        var mostrar = false;
        if (tipo === 'peatonal') {
            var rut = (document.getElementById('rut').value || document.getElementById('rut-manual').value || '').trim();
            mostrar = rut.length >= 8;
        } else {
            var patente = (document.getElementById('patente-result').value || '').trim();
            mostrar = patente.length >= 5;
        }
        if (bloqueRegistrar) {
            var estabaOculta = bloqueRegistrar.classList.contains('hidden');
            bloqueRegistrar.classList.toggle('hidden', !mostrar);
            if (mostrar && estabaOculta) bloqueRegistrar.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

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
        actualizarVisibilidadRegistrar();
        if (tab === 'peatonal') setTimeout(scrollAlFinalEscaner, 300);
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
        var constraints = {
            video: {
                facingMode: 'environment',
                width: { ideal: 1280, min: 640 },
                height: { ideal: 720, min: 480 }
            },
            audio: false
        };
        navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
            streamCedula = stream;
            videoCedula.srcObject = stream;
            return videoCedula.play();
        }).catch(function() {
            return navigator.mediaDevices.getUserMedia({
                video: { width: { ideal: 1280 }, height: { ideal: 720 } },
                audio: false
            }).then(function(stream) {
                streamCedula = stream;
                videoCedula.srcObject = stream;
                return videoCedula.play();
            });
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
    function escribirLogQR() {}
    function limpiarLogQR() {}
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
        btnCapturarCedula.disabled = true;
        mensajeCapturaCedula.textContent = 'Procesando…';
        mensajeCapturaCedula.classList.remove('hidden');
        var btnReintentar = document.getElementById('btn-capturar-cedula-reintentar');
        if (btnReintentar) btnReintentar.classList.remove('hidden');
        requestAnimationFrame(function() {
        var lumActual = 0;
        var captureTimeoutId = setTimeout(function() {
            terminar();
            mensajeCapturaCedula.textContent = 'Tiempo agotado. Puede intentar de nuevo.';
            mensajeCapturaCedula.classList.remove('hidden');
            setTimeout(function() {
                mensajeCapturaCedula.classList.add('hidden');
                mensajeCapturaCedula.textContent = '';
            }, 4000);
        }, 90000);
        function terminar() {
            clearTimeout(captureTimeoutId);
            if (typeof window._terminarCapturaCedula === 'function') window._terminarCapturaCedula = null;
            mensajeCapturaCedula.classList.add('hidden');
            mensajeCapturaCedula.textContent = '';
            btnCapturarCedula.disabled = false;
            if (btnReintentar) btnReintentar.classList.add('hidden');
        }
        window._terminarCapturaCedula = terminar;
        function exitoQR(decodedText) {
            escribirLogQR('4. QR obtenido: ' + (decodedText.length > 60 ? decodedText.slice(0, 60) + '…' : decodedText));
            onScanCedula(decodedText);
            var runMatch = decodedText.match(/[?&]RUN=([^&\s]+)/i) || decodedText.match(/RUN=([^&\s]+)/i);
            if (runMatch) escribirLogQR('5. RUT extraído: ' + formatearRut(runMatch[1].trim()));
            var parts = decodedText.split('|').map(function(p) { return p.trim(); }).filter(Boolean);
            if (parts.length >= 2) escribirLogQR('   Nombre: ' + parts.slice(1).join(' '));
            else if (nombreInput && nombreInput.value) escribirLogQR('   Nombre: ' + nombreInput.value);
            escribirLogQR('--- Listo.');
            terminar();
        }
        function dibujarCanvas() {
            var maxLado = 2560;
            var cw = w, ch = h;
            if (w > maxLado || h > maxLado) {
                var scale = Math.min(maxLado / w, maxLado / h);
                cw = Math.round(w * scale);
                ch = Math.round(h * scale);
            }
            canvasCedula.width = cw;
            canvasCedula.height = ch;
            var ctx = canvasCedula.getContext('2d', { willReadFrequently: true });
            ctx.drawImage(videoCedula, 0, 0, w, h, 0, 0, cw, ch);
            w = cw;
            h = ch;
            escribirLogQR('2. Imagen capturada (' + w + '×' + h + ' px)');
            lumActual = 0;
            try {
                var id = ctx.getImageData(0, 0, cw, ch);
                var d = id.data, sum = 0, n = d.length / 4;
                for (var i = 0; i < d.length; i += 4) sum += 0.299 * d[i] + 0.587 * d[i+1] + 0.114 * d[i+2];
                lumActual = n ? Math.round(sum / n) : 0;
                escribirLogQR('   Diagnóstico: luminosidad media = ' + lumActual + ' (0=negro, 255=blanco; óptimo ~100-180)');
            } catch (e) {}
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
        function canvasRecorteSuperiorIzquierdo(srcCanvas, fracAncho, fracAlto) {
            fracAncho = fracAncho != null ? fracAncho : 0.55;
            fracAlto = fracAlto != null ? fracAlto : 0.6;
            var c = document.createElement('canvas');
            var sw = srcCanvas.width, sh = srcCanvas.height;
            c.width = Math.max(100, Math.round(sw * fracAncho));
            c.height = Math.max(100, Math.round(sh * fracAlto));
            var ctx = c.getContext('2d');
            ctx.drawImage(srcCanvas, 0, 0, c.width, c.height, 0, 0, c.width, c.height);
            return c;
        }
        function canvasEscalar(srcCanvas, factor) {
            factor = factor != null ? factor : 2;
            var c = document.createElement('canvas');
            c.width = Math.min(1920, Math.round(srcCanvas.width * factor));
            c.height = Math.min(1920, Math.round(srcCanvas.height * factor));
            var ctx = c.getContext('2d');
            ctx.imageSmoothingEnabled = true;
            ctx.imageSmoothingQuality = 'high';
            ctx.drawImage(srcCanvas, 0, 0, srcCanvas.width, srcCanvas.height, 0, 0, c.width, c.height);
            return c;
        }
        function canvasRotar(srcCanvas, grados) {
            var c = document.createElement('canvas');
            var rad = grados * Math.PI / 180;
            var cos = Math.abs(Math.cos(rad)), sin = Math.abs(Math.sin(rad));
            c.width = Math.round(srcCanvas.width * cos + srcCanvas.height * sin);
            c.height = Math.round(srcCanvas.width * sin + srcCanvas.height * cos);
            var ctx = c.getContext('2d');
            ctx.translate(c.width / 2, c.height / 2);
            ctx.rotate(rad);
            ctx.drawImage(srcCanvas, -srcCanvas.width / 2, -srcCanvas.height / 2);
            return c;
        }
        function canvasAfilar(srcCanvas, factor) {
            factor = factor != null ? factor : 0.3;
            var c = document.createElement('canvas');
            c.width = srcCanvas.width;
            c.height = srcCanvas.height;
            var ctx = srcCanvas.getContext('2d');
            var img = ctx.getImageData(0, 0, c.width, c.height);
            var d = img.data, w = c.width, h = c.height;
            for (var y = 1; y < h - 1; y++) {
                for (var x = 1; x < w - 1; x++) {
                    var i = (y * w + x) * 4;
                    var ctr = 0.299 * d[i] + 0.587 * d[i+1] + 0.114 * d[i+2];
                    var top = 0.299 * d[((y-1)*w+x)*4] + 0.587 * d[((y-1)*w+x)*4+1] + 0.114 * d[((y-1)*w+x)*4+2];
                    var bot = 0.299 * d[((y+1)*w+x)*4] + 0.587 * d[((y+1)*w+x)*4+1] + 0.114 * d[((y+1)*w+x)*4+2];
                    var left = 0.299 * d[(y*w+(x-1))*4] + 0.587 * d[(y*w+(x-1))*4+1] + 0.114 * d[(y*w+(x-1))*4+2];
                    var right = 0.299 * d[(y*w+(x+1))*4] + 0.587 * d[(y*w+(x+1))*4+1] + 0.114 * d[(y*w+(x+1))*4+2];
                    var v = Math.round(ctr + factor * (4 * ctr - top - bot - left - right));
                    v = v < 0 ? 0 : v > 255 ? 255 : v;
                    d[i] = d[i+1] = d[i+2] = v;
                }
            }
            c.getContext('2d').putImageData(img, 0, 0);
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
        function canvasBrillar(srcCanvas, factor, offset) {
            factor = factor != null ? factor : 1.5;
            offset = offset != null ? offset : 0;
            var c = document.createElement('canvas');
            c.width = srcCanvas.width;
            c.height = srcCanvas.height;
            var ctx = srcCanvas.getContext('2d');
            var img = ctx.getImageData(0, 0, c.width, c.height);
            var d = img.data;
            for (var i = 0; i < d.length; i += 4) {
                d[i] = Math.max(0, Math.min(255, Math.round(d[i] * factor + offset)));
                d[i+1] = Math.max(0, Math.min(255, Math.round(d[i+1] * factor + offset)));
                d[i+2] = Math.max(0, Math.min(255, Math.round(d[i+2] * factor + offset)));
            }
            c.getContext('2d').putImageData(img, 0, 0);
            return c;
        }
        function canvasGamma(srcCanvas, gamma) {
            gamma = gamma != null ? gamma : 1.5;
            var lut = [];
            for (var i = 0; i < 256; i++) {
                lut[i] = Math.min(255, Math.round(255 * Math.pow(i / 255, 1 / gamma)));
            }
            var c = document.createElement('canvas');
            c.width = srcCanvas.width;
            c.height = srcCanvas.height;
            var ctx = srcCanvas.getContext('2d');
            var img = ctx.getImageData(0, 0, c.width, c.height);
            var d = img.data;
            for (var i = 0; i < d.length; i += 4) {
                d[i] = lut[d[i]];
                d[i+1] = lut[d[i+1]];
                d[i+2] = lut[d[i+2]];
            }
            c.getContext('2d').putImageData(img, 0, 0);
            return c;
        }
        function decodificarConZXing(canvasEl) {
            if (typeof ZXingBrowser === 'undefined' || !ZXingBrowser.BrowserQRCodeReader) return Promise.resolve(null);
            return new Promise(function(resolve) {
                var img = new Image();
                img.onload = function() {
                    try {
                        var reader = new ZXingBrowser.BrowserQRCodeReader();
                        reader.decodeFromImage(img).then(function(result) { resolve(result ? result.text : null); }).catch(function() { resolve(null); });
                    } catch (e) { resolve(null); }
                };
                img.onerror = function() { resolve(null); };
                img.src = canvasEl.toDataURL('image/png');
            });
        }
        function decodificarConBarcodeDetector(canvasEl, formatos) {
            if (typeof BarcodeDetector === 'undefined') return Promise.resolve(null);
            formatos = formatos || ['qr_code', 'pdf417'];
            var detector;
            try {
                detector = new BarcodeDetector({ formats: formatos });
            } catch (e) {
                try { detector = new BarcodeDetector({ formats: ['qr_code'] }); } catch (e2) { return Promise.resolve(null); }
            }
            return detector.detect(canvasEl)
                .then(function(barcodes) { return barcodes.length ? barcodes[0].rawValue : null; })
                .catch(function() { return null; });
        }
        function intentarDecodificar() {
            function seguirConJsQR() {
            escribirLogQR('3. Decodificando QR (jsQR, imagen original)…');
            var decoded = decodificarConJsQR(canvasCedula);
            if (decoded) { exitoQR(decoded); return; }
            if (lumActual < 100) {
                escribirLogQR('3a1. Imagen oscura: jsQR con brillo (2.2x + 25)…');
                var cBrillo = canvasBrillar(canvasCedula, 2.2, 25);
                decoded = decodificarConJsQR(cBrillo);
                if (decoded) { exitoQR(decoded); return; }
                if (lumActual < 85) {
                    escribirLogQR('3a1b. Muy oscura: brillo 2.8x + 35…');
                    decoded = decodificarConJsQR(canvasBrillar(canvasCedula, 2.8, 35));
                    if (decoded) { exitoQR(decoded); return; }
                }
                escribirLogQR('3a2. Imagen oscura: jsQR con gamma 1.8…');
                decoded = decodificarConJsQR(canvasGamma(canvasCedula, 1.8));
                if (decoded) { exitoQR(decoded); return; }
                decoded = decodificarConJsQR(canvasBrillar(canvasCedula, 1.8, 40));
                if (decoded) { exitoQR(decoded); return; }
                decoded = decodificarConJsQR(canvasGamma(cBrillo, 1.3));
                if (decoded) { exitoQR(decoded); return; }
            }
            escribirLogQR('3a0. Recorte superior izquierdo (QR cédula nueva)…');
            var cSupIzq = canvasRecorteSuperiorIzquierdo(canvasCedula, 0.55, 0.6);
            decoded = decodificarConJsQR(cSupIzq);
            if (decoded) { exitoQR(decoded); return; }
            decoded = decodificarConJsQR(canvasConMasContraste(cSupIzq));
            if (decoded) { exitoQR(decoded); return; }
            escribirLogQR('3a0b. Escalando recorte 3x y 4x (QR pequeño)…');
            decoded = decodificarConJsQR(canvasEscalar(cSupIzq, 3));
            if (decoded) { exitoQR(decoded); return; }
            decoded = decodificarConJsQR(canvasEscalar(cSupIzq, 4));
            if (decoded) { exitoQR(decoded); return; }
            decoded = decodificarConJsQR(canvasConMasContraste(canvasEscalar(cSupIzq, 3)));
            if (decoded) { exitoQR(decoded); return; }
            escribirLogQR('3a0c. Probando ZXing en recorte escalado…');
            decodificarConZXing(canvasEscalar(cSupIzq, 3)).then(function(d) {
                if (d) { exitoQR(d); return; }
                return decodificarConZXing(cSupIzq);
            }).then(function(d) {
                if (d) { exitoQR(d); return; }
                return decodificarConZXing(canvasConMasContraste(cSupIzq));
            }).then(function(d) {
                if (d) { exitoQR(d); return; }
                (function continuarFlujoJsQR() {
            var decoded;
            if (lumActual < 100) {
                decoded = decodificarConJsQR(canvasBrillar(cSupIzq, 2.5, 30));
                if (decoded) { exitoQR(decoded); return; }
            }
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
            escribirLogQR('3b2. Probando rotaciones (card inclinada)…');
            for (var rot = 0; rot < 3; rot++) {
                var grados = [90, -90, 180][rot];
                var cRot = canvasRotar(canvasCedula, grados);
                decoded = decodificarConJsQR(cRot);
                if (decoded) { exitoQR(decoded); return; }
                decoded = decodificarConJsQR(canvasRecorteSuperiorIzquierdo(cRot, 0.55, 0.6));
                if (decoded) { exitoQR(decoded); return; }
            }
            escribirLogQR('3b3. Probando con afilado…');
            decoded = decodificarConJsQR(canvasAfilar(canvasCedula, 0.4));
            if (decoded) { exitoQR(decoded); return; }
            decoded = decodificarConJsQR(canvasAfilar(cSupIzq, 0.4));
            if (decoded) { exitoQR(decoded); return; }
            if (lumActual < 100) {
                decoded = decodificarConJsQR(canvasAfilar(canvasBrillar(cSupIzq, 2.2, 25), 0.3));
                if (decoded) { exitoQR(decoded); return; }
            }
            escribirLogQR('3c. Probando con más contraste (cédula)…');
            var cContraste = canvasConMasContraste(canvasCedula);
            decoded = decodificarConJsQR(cContraste);
            if (decoded) { exitoQR(decoded); return; }
            decoded = decodificarConJsQR(canvasConMasContraste(c2));
            if (decoded) { exitoQR(decoded); return; }
            decoded = decodificarConJsQR(canvasConMasContraste(cIzq40));
            if (decoded) { exitoQR(decoded); return; }
            escribirLogQR('3c2. Probando binarizado (recorte 40%)…');
            var umbrales = lumActual < 100 ? [70, 85, 100, 115, 128, 140] : [128, 140, 110];
            for (var ub = 0; ub < umbrales.length; ub++) {
                decoded = decodificarConJsQR(canvasBinarizar(cIzq40, umbrales[ub]));
                if (decoded) { exitoQR(decoded); return; }
            }
            if (lumActual < 100) {
                escribirLogQR('3c2b. Binarizado sobre imagen con brillo…');
                var cBrilloIzq = canvasBrillar(cIzq40, 2.0, 30);
                for (var ub2 = 0; ub2 < 4; ub2++) {
                    decoded = decodificarConJsQR(canvasBinarizar(cBrilloIzq, [90, 110, 130, 150][ub2]));
                    if (decoded) { exitoQR(decoded); return; }
                }
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
                if (lumActual < 100) {
                    decoded = decodificarConJsQR(canvasBrillar(cBig, 2.0, 30));
                    if (decoded) { exitoQR(decoded); return; }
                }
            }
            escribirLogQR('3f. Probando con Html5Qrcode (archivo)…');
            canvasCedula.toBlob(function(blob) {
                if (!blob) {
                    var dataUrl = canvasCedula.toDataURL('image/png');
                    fetch(dataUrl).then(function(r) { return r.blob(); }).then(function(b) {
                        procesarConArchivo(b, null, null, null, null);
                    }).catch(falloQR);
                    return;
                }
                var cContrasteBlob = canvasConMasContraste(canvasCedula);
                var cBrilloBlob = lumActual < 100 ? canvasBrillar(canvasCedula, 2.2, 25) : null;
                var cSupIzq = canvasRecorteSuperiorIzquierdo(canvasCedula, 0.55, 0.6);
                cContrasteBlob.toBlob(function(blobContraste) {
                    cIzq40.toBlob(function(blobIzq) {
                        cSupIzq.toBlob(function(blobSupIzq) {
                            var blobBrillo = null;
                            if (cBrilloBlob) {
                                cBrilloBlob.toBlob(function(b) {
                                    procesarConArchivo(blob, blobContraste, blobIzq, b, blobSupIzq);
                                }, 'image/png', 1);
                            } else {
                                procesarConArchivo(blob, blobContraste, blobIzq, null, blobSupIzq);
                            }
                        }, 'image/png', 1);
                    }, 'image/png', 1);
                }, 'image/png', 1);
            }, 'image/png', 1);
                })();
            }).catch(function() {
                (function() {
                    escribirLogQR('3a0c. Probando ZXing en recorte escalado…');
                    var cSupIzq = canvasRecorteSuperiorIzquierdo(canvasCedula, 0.55, 0.6);
                    var cIzq40 = canvasRecorteIzquierdo(canvasCedula, 0.4);
                    seguirConJsQRSync(cSupIzq, cIzq40);
                })();
            });
            return;
        }
        function seguirConJsQRSync(cSupIzq, cIzq40) {
            var decoded;
            escribirLogQR('3a. Probando recorte zona izquierda…');
            decoded = decodificarConJsQR(cIzq40);
            if (decoded) { exitoQR(decoded); return; }
            decoded = decodificarConJsQR(canvasRecorteIzquierdo(canvasCedula, 0.35));
            if (decoded) { exitoQR(decoded); return; }
            escribirLogQR('3b. Probando imagen espejo…');
            var c2 = document.getElementById('canvas-cedula-espejo');
            c2.width = w; c2.height = h;
            var ctx2 = c2.getContext('2d');
            ctx2.translate(w, 0); ctx2.scale(-1, 1);
            ctx2.drawImage(canvasCedula, 0, 0);
            ctx2.setTransform(1, 0, 0, 1, 0, 0);
            decoded = decodificarConJsQR(c2);
            if (decoded) { exitoQR(decoded); return; }
            escribirLogQR('3b2. Probando rotaciones…');
            for (var rot = 0; rot < 3; rot++) {
                var cRot = canvasRotar(canvasCedula, [90, -90, 180][rot]);
                decoded = decodificarConJsQR(cRot);
                if (decoded) { exitoQR(decoded); return; }
                decoded = decodificarConJsQR(canvasRecorteSuperiorIzquierdo(cRot, 0.55, 0.6));
                if (decoded) { exitoQR(decoded); return; }
            }
            escribirLogQR('3b3. Probando con afilado…');
            decoded = decodificarConJsQR(canvasAfilar(canvasCedula, 0.4));
            if (decoded) { exitoQR(decoded); return; }
            decoded = decodificarConJsQR(canvasAfilar(cSupIzq, 0.4));
            if (decoded) { exitoQR(decoded); return; }
            if (lumActual < 100) {
                decoded = decodificarConJsQR(canvasAfilar(canvasBrillar(cSupIzq, 2.2, 25), 0.3));
                if (decoded) { exitoQR(decoded); return; }
            }
            escribirLogQR('3c. Probando con más contraste…');
            var cContraste = canvasConMasContraste(canvasCedula);
            decoded = decodificarConJsQR(cContraste);
            if (decoded) { exitoQR(decoded); return; }
            decoded = decodificarConJsQR(canvasConMasContraste(c2));
            if (decoded) { exitoQR(decoded); return; }
            decoded = decodificarConJsQR(canvasConMasContraste(cIzq40));
            if (decoded) { exitoQR(decoded); return; }
            escribirLogQR('3c2. Probando binarizado…');
            var umbrales = lumActual < 100 ? [70, 85, 100, 115, 128, 140] : [128, 140, 110];
            for (var ub = 0; ub < umbrales.length; ub++) {
                decoded = decodificarConJsQR(canvasBinarizar(cIzq40, umbrales[ub]));
                if (decoded) { exitoQR(decoded); return; }
            }
            if (lumActual < 100) {
                var cBrilloIzq = canvasBrillar(cIzq40, 2.0, 30);
                for (var ub2 = 0; ub2 < 4; ub2++) {
                    decoded = decodificarConJsQR(canvasBinarizar(cBrilloIzq, [90, 110, 130, 150][ub2]));
                    if (decoded) { exitoQR(decoded); return; }
                }
            }
            escribirLogQR('3d. Recorte izquierdo 2x…');
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
            escribirLogQR('3e. Imagen completa 2x…');
            var w2 = Math.min(w * 2, 1920), h2 = Math.min(h * 2, 1920);
            if (w2 > w || h2 > h) {
                var cBig = document.createElement('canvas');
                cBig.width = w2; cBig.height = h2;
                var ctxBig = cBig.getContext('2d');
                ctxBig.imageSmoothingEnabled = true;
                ctxBig.imageSmoothingQuality = 'high';
                ctxBig.drawImage(canvasCedula, 0, 0, w, h, 0, 0, w2, h2);
                decoded = decodificarConJsQR(cBig);
                if (decoded) { exitoQR(decoded); return; }
                decoded = decodificarConJsQR(canvasConMasContraste(cBig));
                if (decoded) { exitoQR(decoded); return; }
                if (lumActual < 100) {
                    decoded = decodificarConJsQR(canvasBrillar(cBig, 2.0, 30));
                    if (decoded) { exitoQR(decoded); return; }
                }
            }
            escribirLogQR('3f. Html5Qrcode (archivo)…');
            canvasCedula.toBlob(function(blob) {
                if (!blob) {
                    var dataUrl = canvasCedula.toDataURL('image/png');
                    fetch(dataUrl).then(function(r) { return r.blob(); }).then(function(b) { procesarConArchivo(b, null, null, null, null); }).catch(falloQR);
                    return;
                }
                var cContrasteBlob = canvasConMasContraste(canvasCedula);
                var cBrilloBlob = lumActual < 100 ? canvasBrillar(canvasCedula, 2.2, 25) : null;
                var cSupIzqBlob = canvasRecorteSuperiorIzquierdo(canvasCedula, 0.55, 0.6);
                cContrasteBlob.toBlob(function(blobContraste) {
                    cIzq40.toBlob(function(blobIzq) {
                        cSupIzqBlob.toBlob(function(blobSupIzq) {
                            if (cBrilloBlob) {
                                cBrilloBlob.toBlob(function(b) { procesarConArchivo(blob, blobContraste, blobIzq, b, blobSupIzq); }, 'image/png', 1);
                            } else {
                                procesarConArchivo(blob, blobContraste, blobIzq, null, blobSupIzq);
                            }
                        }, 'image/png', 1);
                    }, 'image/png', 1);
                }, 'image/png', 1);
            }, 'image/png', 1);
        }
            function probarImagenesMejoradas() {
                if (lumActual >= 100) return Promise.resolve(null);
                escribirLogQR('3x. Imagen oscura (lum=' + lumActual + '): probando brillo, gamma y recorte superior-izq…');
                var cBrillo = canvasBrillar(canvasCedula, 2.2, 25);
                var cGamma = canvasGamma(canvasCedula, 1.8);
                var cSupIzq = canvasRecorteSuperiorIzquierdo(canvasCedula, 0.55, 0.6);
                var cSupIzqBrillo = canvasBrillar(cSupIzq, 2.5, 30);
                return decodificarConBarcodeDetector(cBrillo).then(function(d) {
                    if (d) return d;
                    return decodificarConBarcodeDetector(cGamma);
                }).then(function(d) {
                    if (d) return d;
                    return decodificarConBarcodeDetector(cSupIzq);
                }).then(function(d) {
                    if (d) return d;
                    return decodificarConBarcodeDetector(cSupIzqBrillo);
                }).then(function(d) {
                    if (d) return d;
                    if (typeof QrScanner !== 'undefined') {
                        return QrScanner.scanImage(cBrillo).then(function(r) { return typeof r === 'string' ? r : (r && r.data) || null; }).catch(function() { return null; });
                    }
                    return null;
                }).then(function(d) {
                    if (d) return d;
                    if (typeof QrScanner !== 'undefined') {
                        return QrScanner.scanImage(cSupIzqBrillo).then(function(r) { return typeof r === 'string' ? r : (r && r.data) || null; }).catch(function() { return null; });
                    }
                    return null;
                }).then(function(d) {
                    if (d) return d;
                    if (typeof QrScanner !== 'undefined') {
                        return QrScanner.scanImage(cGamma).then(function(r) { return typeof r === 'string' ? r : (r && r.data) || null; }).catch(function() { return null; });
                    }
                    return null;
                }).then(function(d) {
                    if (d) return d;
                    return decodificarConZXing(cSupIzqBrillo);
                }).then(function(d) {
                    if (d) return d;
                    return decodificarConZXing(canvasEscalar(cSupIzq, 3));
                });
            }
            escribirLogQR('3. Probando BarcodeDetector (nativo)…');
            if (typeof QrScanner !== 'undefined') QrScanner.WORKER_PATH = 'https://cdn.jsdelivr.net/npm/qr-scanner@1.4.2/qr-scanner-worker.min.js';
            probarImagenesMejoradas().then(function(decoded) {
                if (decoded) { exitoQR(decoded); return; }
                return decodificarConBarcodeDetector(canvasCedula);
            }).then(function(decoded) {
                if (decoded) { exitoQR(decoded); return; }
                escribirLogQR('3a0. Probando BarcodeDetector en recorte izquierdo…');
                return decodificarConBarcodeDetector(canvasRecorteIzquierdo(canvasCedula, 0.4));
            }).then(function(decoded) {
                if (decoded) { exitoQR(decoded); return; }
                escribirLogQR('3a0c. Probando BarcodeDetector en recorte superior-izq…');
                return decodificarConBarcodeDetector(canvasRecorteSuperiorIzquierdo(canvasCedula, 0.55, 0.6));
            }).then(function(decoded) {
                if (decoded) { exitoQR(decoded); return; }
                escribirLogQR('3a0c2. Probando BarcodeDetector solo PDF417 (cédula antigua)…');
                return decodificarConBarcodeDetector(canvasRecorteSuperiorIzquierdo(canvasCedula, 0.55, 0.6), ['pdf417']);
            }).then(function(decoded) {
                if (decoded) { exitoQR(decoded); return; }
                return decodificarConBarcodeDetector(canvasRecorteIzquierdo(canvasCedula, 0.4), ['pdf417']);
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
                escribirLogQR('3a0d. Probando ZXing…');
                return decodificarConZXing(canvasCedula);
            }).then(function(decoded) {
                if (decoded) { exitoQR(decoded); return; }
                var cSupIzq = canvasRecorteSuperiorIzquierdo(canvasCedula, 0.55, 0.6);
                return decodificarConZXing(canvasEscalar(cSupIzq, 3));
            }).then(function(decoded) {
                if (decoded) { exitoQR(decoded); return; }
                escribirLogQR('3a0e. Imagen de baja resolución: probando 2x/3x y BarcodeDetector…');
                var w2 = Math.min(w * 2, 1920), h2 = Math.min(h * 2, 1920);
                var cBig = document.createElement('canvas');
                cBig.width = w2; cBig.height = h2;
                var ctxBig = cBig.getContext('2d');
                ctxBig.imageSmoothingEnabled = true;
                ctxBig.imageSmoothingQuality = 'high';
                ctxBig.drawImage(canvasCedula, 0, 0, w, h, 0, 0, w2, h2);
                return decodificarConBarcodeDetector(cBig).then(function(d) {
                    if (d) return d;
                    if (typeof QrScanner !== 'undefined') {
                        return QrScanner.scanImage(cBig).then(function(r) { return typeof r === 'string' ? r : (r && r.data) || null; }).catch(function() { return null; });
                    }
                    return null;
                }).then(function(d) {
                    if (d) return d;
                    if (w * h < 500000) {
                        var w3 = Math.min(w * 3, 1920), h3 = Math.min(h * 3, 1920);
                        var cBig3 = document.createElement('canvas');
                        cBig3.width = w3; cBig3.height = h3;
                        var ctx3 = cBig3.getContext('2d');
                        ctx3.imageSmoothingEnabled = true;
                        ctx3.imageSmoothingQuality = 'high';
                        ctx3.drawImage(canvasCedula, 0, 0, w, h, 0, 0, w3, h3);
                        var dJs = decodificarConJsQR(cBig3);
                        if (dJs) return dJs;
                        return decodificarConBarcodeDetector(cBig3);
                    }
                    return null;
                });
            }).then(function(decoded) {
                if (decoded) { exitoQR(decoded); return; }
                seguirConJsQR();
            }).catch(function() {
                try { seguirConJsQR(); } catch (e) { falloQR(); }
            }).catch(falloQR);
        }
        function procesarConArchivo(blob, blobContraste, blobIzq, blobBrillo, blobSupIzq) {
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
                    new Html5Qrcode('dummy-cedula').scanFile(fileIzq, false).then(exitoQR).catch(intentarSupIzq);
                } else intentarSupIzq();
            }
            function intentarSupIzq() {
                if (blobSupIzq) {
                    escribirLogQR('3g2b. Reintento con recorte superior-izq (archivo)…');
                    var fileSupIzq = new File([blobSupIzq], 'captura-supizq.png', { type: 'image/png' });
                    new Html5Qrcode('dummy-cedula').scanFile(fileSupIzq, false).then(exitoQR).catch(intentarConEspejo);
                } else intentarConEspejo();
            }
            scanner.scanFile(file, false).then(exitoQR).catch(function() {
                if (blobBrillo) {
                    escribirLogQR('3g0. Reintento con imagen con brillo (archivo)…');
                    var fileB = new File([blobBrillo], 'captura-brillo.png', { type: 'image/png' });
                    new Html5Qrcode('dummy-cedula').scanFile(fileB, false).then(exitoQR).catch(function() {
                        if (blobSupIzq) {
                            escribirLogQR('3g0b. Reintento con recorte superior-izq (archivo)…');
                            var fileSupIzq = new File([blobSupIzq], 'captura-supizq.png', { type: 'image/png' });
                            new Html5Qrcode('dummy-cedula').scanFile(fileSupIzq, false).then(exitoQR).catch(function() {
                                if (blobContraste) {
                                    escribirLogQR('3g. Reintento con imagen con más contraste (archivo)…');
                                    var fileC = new File([blobContraste], 'captura-contraste.png', { type: 'image/png' });
                                    new Html5Qrcode('dummy-cedula').scanFile(fileC, false).then(exitoQR).catch(intentarRecorteIzq);
                                } else intentarRecorteIzq();
                            });
                        } else if (blobContraste) {
                            escribirLogQR('3g. Reintento con imagen con más contraste (archivo)…');
                            var fileC = new File([blobContraste], 'captura-contraste.png', { type: 'image/png' });
                            new Html5Qrcode('dummy-cedula').scanFile(fileC, false).then(exitoQR).catch(intentarRecorteIzq);
                        } else intentarRecorteIzq();
                    });
                } else if (blobSupIzq) {
                    escribirLogQR('3g0b. Reintento con recorte superior-izq (archivo)…');
                    var fileSupIzq = new File([blobSupIzq], 'captura-supizq.png', { type: 'image/png' });
                    new Html5Qrcode('dummy-cedula').scanFile(fileSupIzq, false).then(exitoQR).catch(function() {
                        if (blobContraste) {
                            escribirLogQR('3g. Reintento con imagen con más contraste (archivo)…');
                            var fileC = new File([blobContraste], 'captura-contraste.png', { type: 'image/png' });
                            new Html5Qrcode('dummy-cedula').scanFile(fileC, false).then(exitoQR).catch(intentarRecorteIzq);
                        } else intentarRecorteIzq();
                    });
                } else if (blobContraste) {
                    escribirLogQR('3g. Reintento con imagen con más contraste (archivo)…');
                    var fileC = new File([blobContraste], 'captura-contraste.png', { type: 'image/png' });
                    new Html5Qrcode('dummy-cedula').scanFile(fileC, false).then(exitoQR).catch(intentarRecorteIzq);
                } else intentarRecorteIzq();
            });
        }
        function falloQR() {
            if (!MODO_QR_AUTOMATICO) {
                var manualSection = document.getElementById('ingreso-manual-peatonal');
                if (manualSection) manualSection.classList.remove('hidden');
            }
            alert('No se detectó un QR. Encuadre bien el código, asegure buena luz y pulse «Capturar de nuevo»' + (MODO_QR_AUTOMATICO ? '.' : ' o ingrese RUT y nombre manualmente.'));
            terminar();
        }
        function continuarConCanvas() {
            requestAnimationFrame(function() {
                dibujarCanvas();
                setTimeout(intentarDecodificar, 80);
            });
        }
        escribirLogQR('2. Probando lectura directa del video (móvil)…');
        if (typeof QrScanner !== 'undefined') {
            if (!QrScanner.WORKER_PATH) QrScanner.WORKER_PATH = 'https://cdn.jsdelivr.net/npm/qr-scanner@1.4.2/qr-scanner-worker.min.js';
            QrScanner.scanImage(videoCedula).then(function(r) {
                var decoded = typeof r === 'string' ? r : (r && r.data) || null;
                if (decoded) { exitoQR(decoded); return; }
                continuarConCanvas();
            }).catch(function() { continuarConCanvas(); });
        } else {
            continuarConCanvas();
        }
        });
    }
    if (btnCapturarCedula) btnCapturarCedula.addEventListener('click', capturarYLeerQR);
    if (btnCapturarCedula) btnCapturarCedula.disabled = true;
    var btnCapturarCedulaReintentar = document.getElementById('btn-capturar-cedula-reintentar');
    if (btnCapturarCedulaReintentar) {
        btnCapturarCedulaReintentar.addEventListener('click', function() {
            if (typeof window._terminarCapturaCedula === 'function') {
                window._terminarCapturaCedula();
            }
        });
    }

    function extraerParametroUrl(texto, param) {
        var regex = new RegExp('[?&]' + param + '=([^&\\s]+)', 'i');
        var m = texto.match(regex);
        if (!m) return '';
        try {
            return decodeURIComponent(m[1].replace(/\+/g, ' ')).trim();
        } catch (e) {
            return m[1].replace(/\+/g, ' ').trim();
        }
    }

    function formatearRut(val) {
        var r = (val || '').replace(/[^0-9kK]/g, '').toUpperCase();
        if (r.length < 2) return r;
        return r.slice(0, -1).replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '-' + r.slice(-1);
    }

    function parsearCedulaDesdeQR(decodedText) {
        if (!decodedText || typeof decodedText !== 'string') return null;
        var runMatch = decodedText.match(/[?&]RUN=([^&\s]+)/i) || decodedText.match(/RUN=([^&\s]+)/i);
        var rut = '';
        var nombre = '';
        if (runMatch) {
            rut = formatearRut(runMatch[1].trim());
            nombre = extraerParametroUrl(decodedText, 'NOMBRE') || extraerParametroUrl(decodedText, 'NOMBRES') || extraerParametroUrl(decodedText, 'NAME') || '';
        }
        var parts = decodedText.split(/[\|@\n\r\t;]/).map(function(p) { return p.trim(); }).filter(Boolean);
        if (parts.length >= 2) {
            if (!rut) rut = formatearRut(parts[0]);
            if (!nombre) nombre = parts.slice(1).join(' ').trim();
        } else if (parts.length === 1 && !runMatch && /^[0-9kK\-\.]+$/i.test(parts[0].replace(/\./g, ''))) {
            if (!rut) rut = formatearRut(parts[0]);
        }
        if (!rut) {
            var rutEnTexto = decodedText.match(/\b(\d{7,8}[-]?[0-9kK])\b/i);
            if (rutEnTexto) rut = formatearRut(rutEnTexto[1]);
        }
        if (!rut || rut.length < 8) return null;
        return { rut: rut.replace(/\s/g, ''), nombre: nombre || '' };
    }

    function registrarIngresoAuto(rut, nombre) {
        if (!alertaResultado || !qrSalidaContainer || !qrSalidaImg) return;
        alertaResultado.classList.add('hidden');
        qrSalidaContainer.classList.add('hidden');
        var payload = { tipo: 'peatonal', rut: rut, nombre: nombre || '', _token: document.querySelector('meta[name="csrf-token"]').content };
        axios.post('{{ route("ingresos.store") }}', payload)
            .then(function(res) {
                if (res.data.success) {
                    alertaResultado.className = 'mt-4 p-4 rounded bg-green-100 text-green-800';
                    if (document.body.classList.contains('bg-gray-100')) alertaResultado.className = 'mt-4 p-4 rounded bg-green-100 text-green-800';
                    else alertaResultado.className = 'mt-4 p-4 rounded bg-emerald-500/20 text-emerald-200';
                    alertaResultado.textContent = res.data.message || 'Ingreso registrado.';
                    alertaResultado.classList.remove('hidden');
                    if (res.data.qr_salida_url) {
                        qrSalidaImg.innerHTML = '<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(res.data.qr_salida_url) + '" alt="QR Salida" class="mx-auto rounded-lg max-w-[200px]">';
                        qrSalidaContainer.classList.remove('hidden');
                    }
                    var btnOtro = document.getElementById('btn-escaner-otro');
                    if (btnOtro) btnOtro.classList.remove('hidden');
                } else {
                    alertaResultado.className = 'mt-4 p-4 rounded bg-red-100 text-red-800';
                    if (!document.body.classList.contains('bg-gray-100')) alertaResultado.className = 'mt-4 p-4 rounded bg-red-500/20 text-red-200';
                    alertaResultado.textContent = res.data.motivo || res.data.message || 'Error al registrar.';
                    alertaResultado.classList.remove('hidden');
                }
            })
            .catch(function(err) {
                var data = err.response && err.response.data;
                var msg = data && data.motivo ? data.motivo : (data && data.message ? data.message : 'Error de conexión.');
                alertaResultado.className = 'mt-4 p-4 rounded bg-red-100 text-red-800';
                if (!document.body.classList.contains('bg-gray-100')) alertaResultado.className = 'mt-4 p-4 rounded bg-red-500/20 text-red-200';
                alertaResultado.textContent = msg;
                alertaResultado.classList.remove('hidden');
            });
    }

    function buscarPersonaPorRut() {
        var rut = (rutInput.value || rutManual.value || '').replace(/\s/g, '');
        if (rut.length < 8) return;
        var url = '{{ route("ingresos.buscar-persona") }}?rut=' + encodeURIComponent(rut);
        fetch(url, { method: 'GET', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.found && data.nombre && !nombreInput.value) nombreInput.value = data.nombre;
            })
            .catch(function() {});
    }

    function onScanCedula(decodedText) {
        if (MODO_QR_AUTOMATICO) {
            var p = parsearCedulaDesdeQR(decodedText);
            if (p && p.rut) registrarIngresoAuto(p.rut, p.nombre);
            if (typeof window._terminarCapturaCedula === 'function') window._terminarCapturaCedula();
            return;
        }
        var runMatch = decodedText.match(/[?&]RUN=([^&\s]+)/i) || decodedText.match(/RUN=([^&\s]+)/i);
        if (runMatch) {
            if (rutInput) rutInput.value = formatearRut(runMatch[1].trim());
            var nombreUrl = extraerParametroUrl(decodedText, 'NOMBRE') || extraerParametroUrl(decodedText, 'NOMBRES') || extraerParametroUrl(decodedText, 'NAME');
            if (nombreUrl && nombreInput) nombreInput.value = nombreUrl;
        }
        var parts = decodedText.split(/[\|@\n\r\t;]/).map(function(p) { return p.trim(); }).filter(Boolean);
        if (parts.length >= 2) {
            if (!runMatch && rutInput) rutInput.value = formatearRut(parts[0]);
            var nombrePartes = parts.slice(1).join(' ').trim();
            if (nombrePartes && nombreInput) nombreInput.value = nombrePartes;
        } else if (parts.length === 1 && !runMatch && /^[0-9kK\-\.]+$/i.test(parts[0].replace(/\./g, ''))) {
            if (rutInput) rutInput.value = formatearRut(parts[0]);
        }
        if (rutInput && !rutInput.value) {
            var rutEnTexto = decodedText.match(/\b(\d{7,8}[-]?[0-9kK])\b/i);
            if (rutEnTexto) rutInput.value = formatearRut(rutEnTexto[1]);
        }
        if (rutInput && rutInput.value && nombreInput && !nombreInput.value) setTimeout(buscarPersonaPorRut, 150);
        if (typeof actualizarVisibilidadRegistrar === 'function') actualizarVisibilidadRegistrar();
    }

    function formatearRut(val) {
        var r = (val || '').replace(/[^0-9kK]/g, '').toUpperCase();
        if (r.length < 2) return r;
        return r.slice(0, -1).replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '-' + r.slice(-1);
    }

    rutManual.addEventListener('input', function() {
        rutInput.value = formatearRut(this.value);
        actualizarVisibilidadRegistrar();
        clearTimeout(window._timeoutBuscarPersona);
        window._timeoutBuscarPersona = setTimeout(buscarPersonaPorRut, 400);
    });
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
                        actualizarVisibilidadRegistrar();
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
        axios.post('{{ route("ingresos.store") }}', payload)
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
                    actualizarVisibilidadRegistrar();
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
        if (MODO_QR_AUTOMATICO) {
            iniciarLectorCedula();
            return;
        }
        if (tabPeatonal && !tabPeatonal.classList.contains('hidden')) {
            iniciarLectorCedula();
            setTimeout(scrollAlFinalEscaner, 600);
        } else {
            setTimeout(iniciarCuandoVisible, 200);
        }
    }
    var btnEscanerOtro = document.getElementById('btn-escaner-otro');
    if (btnEscanerOtro) {
        btnEscanerOtro.addEventListener('click', function() {
            if (qrSalidaContainer) qrSalidaContainer.classList.add('hidden');
            if (alertaResultado) { alertaResultado.classList.add('hidden'); alertaResultado.textContent = ''; }
            btnEscanerOtro.classList.add('hidden');
        });
    }
    setTimeout(iniciarCuandoVisible, 300);
});
</script>
@endpush
@endsection
