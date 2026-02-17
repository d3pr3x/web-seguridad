@extends('layouts.qr-automatico')

@section('title', 'QR Carnet - Control de acceso')

@section('content')
<div class="w-full max-w-md mx-auto">
    <p class="text-center text-slate-400 text-sm mb-4">Encuadre el código QR del carnet (cédula) en el recuadro. Se registrará el ingreso peatonal automáticamente.</p>
    <div id="reader" class="w-full rounded-xl overflow-hidden bg-black border-2 border-teal-500"></div>
    <p id="mensaje" class="mensaje hidden"></p>
    <div id="qr-salida-box" class="hidden">
        <p class="text-sm text-emerald-400 mb-2">Ingreso registrado</p>
        <div id="qr-salida-img"></div>
        <p class="text-xs text-slate-500 mt-2">Escanear al salir para registrar la salida.</p>
        <button type="button" id="btn-otro" class="mt-3 w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition text-sm">Escanear otro ingreso</button>
    </div>
    <div class="text-center mt-4">
        <a href="{{ route('ingresos.index') }}" class="btn-volver">Volver a Ingresos</a>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function() {
    var readerDiv = document.getElementById('reader');
    var mensaje = document.getElementById('mensaje');
    var qrSalidaBox = document.getElementById('qr-salida-box');
    var qrSalidaImg = document.getElementById('qr-salida-img');
    var btnOtro = document.getElementById('btn-otro');
    var escaner = null;
    var escaneando = false;
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
            } else {
                var msg = result.data.message || (result.data.errors && result.data.errors.rut && result.data.errors.rut[0]) || 'Error al registrar.';
                mensaje.textContent = msg;
                mensaje.className = 'mensaje err';
                iniciarCamara();
            }
        })
        .catch(function(err) {
            procesando = false;
            mensaje.textContent = 'Error de conexión. Intente de nuevo.';
            mensaje.className = 'mensaje err';
            mensaje.classList.remove('hidden');
            iniciarCamara();
        });
    }

    function onQrSuccess(decodedText) {
        if (procesando) return;
        var datos = parsearQrCedula(decodedText);
        if (!datos) return;
        escaner.stop().then(function() {
            escaneando = false;
            registrarIngreso(datos.rut, datos.nombre);
        }).catch(function() { escaneando = false; registrarIngreso(datos.rut, datos.nombre); });
    }

    function iniciarCamara() {
        if (escaneando || !readerDiv) return;
        escaneando = true;
        mensaje.classList.add('hidden');
        mensaje.textContent = '';
        qrSalidaBox.classList.add('hidden');

        escaner = new Html5Qrcode('reader');
        escaner.start(
            { facingMode: 'environment' },
            { fps: 10 },
            onQrSuccess,
            function() {}
        ).catch(function(err) {
            escaneando = false;
            mensaje.classList.remove('hidden');
            mensaje.className = 'mensaje err';
            mensaje.textContent = 'No se pudo acceder a la cámara. Use HTTPS y permita el acceso.';
            console.warn(err);
        });
    }

    btnOtro.addEventListener('click', function() {
        qrSalidaBox.classList.add('hidden');
        iniciarCamara();
    });

    if (readerDiv) {
        iniciarCamara();
    }
})();
</script>
@endsection
