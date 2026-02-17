@extends('layouts.qr-automatico')

@section('title', 'QR Carnet - Control de acceso')

@section('content')
<div class="w-full max-w-md mx-auto">
    <p class="text-center text-slate-400 text-sm mb-4">Encuadre el código QR del carnet (cédula) y pulse «Capturar y leer». Se registrará el ingreso peatonal automáticamente.</p>

    <div class="rounded-xl overflow-hidden bg-black border-2 border-teal-500 relative">
        <video id="video-cedula" autoplay playsinline muted class="w-full aspect-[4/3] object-cover block"></video>
        <p id="mensaje-captura" class="text-white text-center text-sm py-2 hidden bg-black/70"></p>
        <div class="p-3 bg-slate-900/90">
            <button type="button" id="btn-capturar" class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition disabled:opacity-50">
                Capturar y leer QR
            </button>
            <button type="button" id="btn-reintentar" class="w-full py-2.5 mt-2 border border-amber-400 bg-amber-500/20 hover:bg-amber-500/30 text-amber-200 font-medium rounded-lg transition hidden">
                Capturar de nuevo
            </button>
        </div>
    </div>
    <canvas id="canvas-captura" class="hidden" width="640" height="480"></canvas>
    <div id="dummy-scan" style="width:1px;height:1px;overflow:hidden;position:absolute;opacity:0;"></div>

    <p id="mensaje" class="mensaje hidden"></p>
    <div id="qr-salida-box" class="hidden">
        <p class="text-sm text-emerald-400 mb-2">Ingreso registrado</p>
        <div id="qr-salida-img"></div>
        <p class="text-xs text-slate-500 mt-2">Escanear al salir para registrar la salida.</p>
    </div>
    <div class="text-center mt-4">
        <a href="{{ route('ingresos.index') }}" class="btn-volver">Volver a Ingresos</a>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function() {
    var video = document.getElementById('video-cedula');
    var btnCapturar = document.getElementById('btn-capturar');
    var btnReintentar = document.getElementById('btn-reintentar');
    var mensajeCaptura = document.getElementById('mensaje-captura');
    var mensaje = document.getElementById('mensaje');
    var qrSalidaBox = document.getElementById('qr-salida-box');
    var qrSalidaImg = document.getElementById('qr-salida-img');
    var canvas = document.getElementById('canvas-captura');
    var stream = null;
    var procesando = false;

    function normalizarRut(str) {
        if (!str || typeof str !== 'string') return '';
        str = str.replace(/\./g, '').replace(/\s/g, '').toUpperCase();
        if (str.indexOf('-') === -1 && str.length >= 2) {
            str = str.slice(0, -1) + '-' + str.slice(-1);
        }
        return str;
    }

    function parsearQrCedula(texto) {
        if (!texto || typeof texto !== 'string') return null;
        var parts = texto.split('|').map(function(p) { return p.trim(); });
        if (parts.length < 2) {
            parts = texto.split('\n').map(function(p) { return p.trim(); });
        }
        if (parts.length < 2) return null;
        var rut = normalizarRut(parts[0]);
        var nombre = (parts[1] || '').trim();
        if (rut.length < 9 || nombre.length < 2) return null;
        return { rut: rut, nombre: nombre };
    }

    function registrarIngreso(rut, nombre) {
        if (procesando) return;
        procesando = true;
        mensaje.textContent = 'Registrando ingreso…';
        mensaje.className = 'mensaje ok';
        mensaje.classList.remove('hidden');
        mensajeCaptura.classList.add('hidden');

        var form = new FormData();
        form.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        form.append('tipo', 'peatonal');
        form.append('rut', rut);
        form.append('nombre', nombre);
        form.append('browser_fingerprint', 'qr-automatico');

        fetch('{{ url("ingresos") }}', {
            method: 'POST',
            body: form,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, status: r.status, data: data }; }); })
        .then(function(result) {
            procesando = false;
            if (result.ok && result.data.success) {
                mensaje.textContent = 'Ingreso registrado: ' + (result.data.message || '');
                mensaje.className = 'mensaje ok';
                if (result.data.qr_salida_url) {
                    qrSalidaImg.innerHTML = '<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(result.data.qr_salida_url) + '" alt="QR Salida">';
                    qrSalidaBox.classList.remove('hidden');
                }
                btnReintentar.classList.remove('hidden');
                btnCapturar.classList.add('hidden');
            } else {
                var msg = result.data.message || (result.data.errors && result.data.errors.rut && result.data.errors.rut[0]) || 'Error al registrar.';
                mensaje.textContent = msg;
                mensaje.className = 'mensaje err';
                btnReintentar.classList.remove('hidden');
                btnCapturar.disabled = false;
            }
        })
        .catch(function(err) {
            procesando = false;
            mensaje.textContent = 'Error de conexión. Intente de nuevo.';
            mensaje.className = 'mensaje err';
            mensaje.classList.remove('hidden');
            btnCapturar.disabled = false;
        });
    }

    function analizarImagen(blob) {
        var file = new File([blob], 'captura.png', { type: 'image/png' });
        var dummy = document.getElementById('dummy-scan');
        var scanner = new Html5Qrcode(dummy.id);

        function intentarRecorteIzq() {
            var w = canvas.width;
            var h = canvas.height;
            var ctx = canvas.getContext('2d');
            var cropW = Math.floor(w * 0.5);
            var c2 = document.createElement('canvas');
            c2.width = cropW;
            c2.height = h;
            c2.getContext('2d').drawImage(canvas, 0, 0, cropW, h, 0, 0, cropW, h);
            c2.toBlob(function(blob2) {
                if (!blob2) { falloQR(); return; }
                var file2 = new File([blob2], 'captura-izq.png', { type: 'image/png' });
                new Html5Qrcode(dummy.id).scanFile(file2, false).then(exitoQR).catch(falloQR);
            }, 'image/png', 1);
        }

        function exitoQR(decodedText) {
            mensajeCaptura.textContent = 'QR detectado, registrando…';
            mensajeCaptura.classList.remove('hidden');
            var datos = parsearQrCedula(decodedText);
            if (datos) {
                registrarIngreso(datos.rut, datos.nombre);
            } else {
                mensajeCaptura.textContent = 'El QR no tiene formato de cédula (RUT|Nombre).';
                mensajeCaptura.classList.add('hidden');
                mensaje.textContent = 'QR no reconocido. Use un carnet con formato RUT|Nombre.';
                mensaje.className = 'mensaje err';
                mensaje.classList.remove('hidden');
                btnReintentar.classList.remove('hidden');
                btnCapturar.disabled = false;
            }
        }

        function falloQR() {
            mensajeCaptura.classList.add('hidden');
            mensaje.textContent = 'No se detectó QR. Encuadre bien el código del carnet y pulse «Capturar de nuevo».';
            mensaje.className = 'mensaje err';
            mensaje.classList.remove('hidden');
            btnReintentar.classList.remove('hidden');
            btnCapturar.disabled = false;
        }

        scanner.scanFile(file, false).then(exitoQR).catch(intentarRecorteIzq);
    }

    function capturarYLeer() {
        if (!video.videoWidth || procesando) return;
        btnCapturar.disabled = true;
        mensaje.classList.add('hidden');
        mensajeCaptura.textContent = 'Analizando imagen…';
        mensajeCaptura.classList.remove('hidden');

        var w = video.videoWidth;
        var h = video.videoHeight;
        canvas.width = w;
        canvas.height = h;
        var ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, w, h);

        canvas.toBlob(function(blob) {
            if (!blob) {
                mensajeCaptura.classList.add('hidden');
                mensaje.textContent = 'Error al capturar. Intente de nuevo.';
                mensaje.className = 'mensaje err';
                mensaje.classList.remove('hidden');
                btnCapturar.disabled = false;
                return;
            }
            analizarImagen(blob);
        }, 'image/png', 1);
    }

    function iniciarCamara() {
        mensaje.classList.add('hidden');
        qrSalidaBox.classList.add('hidden');
        btnReintentar.classList.add('hidden');
        btnCapturar.classList.remove('hidden');
        btnCapturar.disabled = false;

        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(function(s) {
                stream = s;
                video.srcObject = s;
                video.onloadedmetadata = function() { video.play(); };
            })
            .catch(function(err) {
                mensaje.classList.remove('hidden');
                mensaje.className = 'mensaje err';
                mensaje.textContent = 'No se pudo acceder a la cámara. Use HTTPS y permita el acceso.';
            });
    }

    function detenerCamara() {
        if (stream) {
            stream.getTracks().forEach(function(t) { t.stop(); });
            stream = null;
        }
        video.srcObject = null;
    }

    btnCapturar.addEventListener('click', capturarYLeer);
    btnReintentar.addEventListener('click', function() {
        mensaje.classList.add('hidden');
        mensaje.textContent = '';
        qrSalidaBox.classList.add('hidden');
        btnReintentar.classList.add('hidden');
        btnCapturar.classList.remove('hidden');
        btnCapturar.disabled = false;
        mensajeCaptura.classList.add('hidden');
    });

    if (video) iniciarCamara();

    window.addEventListener('beforeunload', detenerCamara);
})();
</script>
@endsection
