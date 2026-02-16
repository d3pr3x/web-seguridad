@extends('layouts.ronda-escaner')

@section('title', 'Escanear QR - Ronda')

@section('content')
<div class="w-full max-w-md mx-auto">
    <p class="text-center text-slate-400 text-sm mb-4">Apunte la cámara al código QR del punto de ronda. Debe estar en el lugar físico (a menos de 10 m).</p>
    <div id="reader" class="w-full rounded-xl overflow-hidden bg-black border-2 border-teal-500"></div>
    <p id="mensaje-escaneo" class="mensaje-escaneo hidden"></p>
    <div class="text-center mt-4">
        <a href="{{ route('usuario.ronda.index') }}" class="btn-volver inline-block">Volver a Rondas</a>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function() {
    var readerDiv = document.getElementById('reader');
    var mensaje = document.getElementById('mensaje-escaneo');
    var escaner = null;
    var escaneando = false;

    function esUrlRondaEscanear(texto) {
        if (!texto || typeof texto !== 'string') return false;
        return texto.indexOf('/ronda/escanear/') !== -1;
    }

    function obtenerUrlEscaneo(texto) {
        if (texto.startsWith('http://') || texto.startsWith('https://')) return texto;
        if (texto.indexOf('/ronda/escanear/') !== -1) {
            var match = texto.match(/\/ronda\/escanear\/([^?\s#]+)/);
            if (match) return window.location.origin + '/ronda/escanear/' + match[1];
        }
        return null;
    }

    function agregarParamsUbicacion(url, lat, lng) {
        var sep = url.indexOf('?') !== -1 ? '&' : '?';
        return url + sep + 'lat=' + encodeURIComponent(lat) + '&lng=' + encodeURIComponent(lng);
    }

    function registrarEscaneoConUbicacion(urlBase) {
        if (!navigator.geolocation) {
            mensaje.textContent = 'Active la ubicación para validar el escaneo.';
            mensaje.classList.remove('hidden');
            return;
        }
        mensaje.textContent = 'Obteniendo ubicación…';
        mensaje.classList.remove('hidden');
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                var url = agregarParamsUbicacion(urlBase, pos.coords.latitude, pos.coords.longitude);
                mensaje.textContent = 'Registrando escaneo…';
                window.location.href = url;
            },
            function(err) {
                mensaje.classList.remove('hidden');
                if (err.code === 1) {
                    mensaje.textContent = 'Permita la ubicación para registrar el punto.';
                } else {
                    mensaje.textContent = 'No se pudo obtener la ubicación. Intente de nuevo.';
                }
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }

    function iniciarCamara() {
        if (escaneando) return;
        escaneando = true;
        mensaje.classList.add('hidden');
        mensaje.textContent = '';

        escaner = new Html5Qrcode('reader');
        escaner.start(
            { facingMode: 'environment' },
            { fps: 10 },
            function(decodedText) {
                if (!esUrlRondaEscanear(decodedText)) return;
                var url = obtenerUrlEscaneo(decodedText);
                if (url) {
                    escaner.stop().then(function() {
                        escaneando = false;
                        registrarEscaneoConUbicacion(url);
                    }).catch(function() { escaneando = false; });
                }
            },
            function() {}
        ).catch(function(err) {
            escaneando = false;
            mensaje.classList.remove('hidden');
            mensaje.textContent = 'No se pudo acceder a la cámara. Use HTTPS y permita el acceso.';
            console.warn(err);
        });
    }

    // Iniciar cámara al cargar la vista
    if (readerDiv) {
        iniciarCamara();
    }
})();
</script>
@endsection
