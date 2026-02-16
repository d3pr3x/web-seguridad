@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-4xl">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-8 h-8 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
                Rondas con QR
            </h1>

            {{-- 1. Abrir cámara (acción principal) --}}
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex flex-wrap gap-3 items-center">
                    <button type="button" id="btn-abrir-camara" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg shadow focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition text-base">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13v7a2 2 0 01-2 2H7a2 2 0 01-2-2v-7"></path></svg>
                        Abrir cámara aquí
                    </button>
                    <a href="{{ route('usuario.ronda.escaner') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4 bg-slate-700 hover:bg-slate-800 text-white font-medium rounded-lg shadow transition text-base" target="_blank">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        Vista solo para escanear QR
                    </a>
                </div>
                <div id="contenedor-escanner" class="hidden mt-4">
                    <div id="reader" class="rounded-lg overflow-hidden border-2 border-gray-300 bg-black max-w-sm mx-auto"></div>
                    <p class="text-center text-sm text-gray-500 mt-2">Apunte la cámara al código QR del punto de ronda.</p>
                    <button type="button" id="btn-cerrar-camara" class="mt-3 w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition">
                        Cerrar cámara
                    </button>
                </div>
                <p id="mensaje-escaneo" class="hidden mt-2 text-sm text-emerald-600 font-medium"></p>
            </div>

            {{-- 2. Listado de visitas diarias --}}
            @php
                $misEscaneosHoy = auth()->user()->rondaEscaneos()->whereDate('escaneado_en', today())->orderByDesc('escaneado_en')->limit(10)->get();
            @endphp
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <h2 class="text-lg font-semibold text-gray-800 p-4 border-b">Mis escaneos de hoy</h2>
                @if($misEscaneosHoy->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($misEscaneosHoy as $e)
                            <li class="px-4 py-3 flex justify-between items-center">
                                <span class="font-medium text-gray-800">{{ $e->puntoRonda->nombre }}</span>
                                <span class="text-sm text-gray-500">{{ $e->escaneado_en->format('H:i') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="px-4 py-6 text-gray-500 text-sm">Aún no hay escaneos hoy. Use el botón de arriba para registrar el primer punto.</p>
                @endif
            </div>

            {{-- 3. Instrucciones en acordeón --}}
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <details class="group">
                    <summary class="list-none cursor-pointer p-4 border-b border-gray-200 hover:bg-gray-50 flex items-center justify-between">
                        <span class="font-semibold text-gray-800">Cómo registrar un punto de ronda</span>
                        <svg class="w-5 h-5 text-gray-500 transition group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="p-4 pt-0 text-gray-700">
                        <ol class="list-decimal list-inside space-y-2">
                            <li>Mantenga esta sesión iniciada en su dispositivo móvil y permita el acceso a la ubicación cuando lo pida la aplicación.</li>
                            <li>Pulse «Abrir cámara para escanear QR» y apunte al código QR <strong>en el lugar físico</strong> del punto (debe estar a menos de 10 m). No se aceptan fotos del QR escaneadas desde otro lugar.</li>
                            <li>Se registrará su identidad, la hora, el punto y la ubicación. El escaneo solo es válido si está en el sitio.</li>
                            <li>Verá una confirmación cuando el escaneo se haya registrado correctamente.</li>
                        </ol>
                        <p class="mt-4 text-sm text-gray-500">Los supervisores pueden ver en tiempo real qué puntos ha cubierto y a qué hora.</p>
                    </div>
                </details>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function() {
    var btnAbrir = document.getElementById('btn-abrir-camara');
    var btnCerrar = document.getElementById('btn-cerrar-camara');
    var contenedor = document.getElementById('contenedor-escanner');
    var readerDiv = document.getElementById('reader');
    var mensaje = document.getElementById('mensaje-escaneo');
    var escaner = null;
    var escaneando = false;

    function esUrlRondaEscanear(texto) {
        if (!texto || typeof texto !== 'string') return false;
        return texto.indexOf('/ronda/escanear/') !== -1;
    }

    function obtenerUrlEscaneo(texto) {
        if (texto.startsWith('http://') || texto.startsWith('https://')) {
            return texto;
        }
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
            alert('Su dispositivo no permite ubicación. El escaneo solo es válido desde la app con ubicación activada.');
            return;
        }
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                var url = agregarParamsUbicacion(urlBase, pos.coords.latitude, pos.coords.longitude);
                mensaje.textContent = 'Registrando escaneo…';
                window.location.href = url;
            },
            function(err) {
                mensaje.classList.add('hidden');
                if (err.code === 1) {
                    alert('Debe permitir el acceso a la ubicación para registrar el escaneo. Así se comprueba que está en el punto físico.');
                } else {
                    alert('No se pudo obtener la ubicación. Compruebe que el GPS esté activado e intente de nuevo.');
                }
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }

    function iniciarCamara() {
        if (escaneando) return;
        escaneando = true;
        contenedor.classList.remove('hidden');
        btnAbrir.classList.add('hidden');
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
                        mensaje.textContent = 'Obteniendo ubicación para validar…';
                        mensaje.classList.remove('hidden');
                        registrarEscaneoConUbicacion(url);
                    }).catch(function() {});
                }
            },
            function() {}
        ).catch(function(err) {
            if (escaner) {
                escaner.stop().catch(function() {}).finally(function() {
                    if (escaner) { escaner.clear(); escaner = null; }
                    escaneando = false;
                    contenedor.classList.add('hidden');
                    btnAbrir.classList.remove('hidden');
                    alert('No se pudo acceder a la cámara. Compruebe que ha dado permiso y que usa HTTPS o localhost.');
                    console.warn(err);
                });
            } else {
                escaneando = false;
                contenedor.classList.add('hidden');
                btnAbrir.classList.remove('hidden');
                alert('No se pudo acceder a la cámara. Compruebe que ha dado permiso y que usa HTTPS o localhost.');
                console.warn(err);
            }
        });
    }

    function cerrarCamara() {
        if (!escaner || !escaneando) return;
        escaner.stop().then(function() {
            escaner.clear();
            escaner = null;
            escaneando = false;
            contenedor.classList.add('hidden');
            btnAbrir.classList.remove('hidden');
            mensaje.classList.add('hidden');
        }).catch(function() {
            contenedor.classList.add('hidden');
            btnAbrir.classList.remove('hidden');
            escaneando = false;
        });
    }

    if (btnAbrir) btnAbrir.addEventListener('click', iniciarCamara);
    if (btnCerrar) btnCerrar.addEventListener('click', cerrarCamara);
})();
</script>
@endpush
@endsection
