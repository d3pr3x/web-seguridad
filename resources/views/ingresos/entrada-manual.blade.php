@extends('layouts.usuario')

@section('content')
<div class="min-h-screen flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-4xl">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-slate-800 flex items-center">
                    <span class="w-10 h-10 rounded-xl flex items-center justify-center text-teal-600 shrink-0 mr-2" style="background: rgba(15, 118, 110, 0.12);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </span>
                    Entrada manual
                </h1>
                <a href="{{ route('ingresos.index') }}" class="px-4 py-2 border rounded-lg text-sm transition" style="border-color: var(--app-border); color: var(--app-text);">Ver listado</a>
            </div>

            <p class="text-sm text-slate-600 mb-4">Registre el ingreso ingresando RUT y nombre (peatonal) o patente (vehicular).</p>

            <div class="flex border-b border-slate-200 mb-4">
                <button type="button" id="tab-peatonal" class="tab-btn px-4 py-2 font-medium rounded-t-lg bg-teal-600 text-white" data-tab="peatonal">Peatonal</button>
                <button type="button" id="tab-vehicular" class="tab-btn px-4 py-2 font-medium rounded-t-lg bg-slate-100 text-slate-600 hover:bg-slate-200" data-tab="vehicular">Vehicular</button>
            </div>

            <div id="panel-peatonal" class="tab-panel">
                <div class="bg-white rounded-xl border shadow-sm overflow-hidden mb-4" style="border-color: var(--app-border);">
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">RUT <span class="text-red-500">*</span></label>
                                <input type="text" id="rut" class="w-full px-3 py-2.5 border rounded-lg rut-input" style="border-color: var(--app-border);" placeholder="12.345.678-9" maxlength="12">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nombre</label>
                                <input type="text" id="nombre" class="w-full px-3 py-2.5 border rounded-lg" style="border-color: var(--app-border);" placeholder="Nombre completo" maxlength="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="panel-vehicular" class="tab-panel hidden">
                <div class="bg-white rounded-xl border shadow-sm overflow-hidden mb-4" style="border-color: var(--app-border);">
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Patente <span class="text-red-500">*</span></label>
                                <input type="text" id="patente" class="w-full px-3 py-2.5 border rounded-lg uppercase" style="border-color: var(--app-border);" placeholder="ABCD12" maxlength="7">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">RUT conductor (opcional)</label>
                                <input type="text" id="conductor-rut" class="w-full px-3 py-2.5 border rounded-lg rut-input" style="border-color: var(--app-border);" placeholder="12.345.678-9" maxlength="12">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="bloque-registrar" class="bg-white rounded-xl border shadow-sm overflow-hidden" style="border-color: var(--app-border);">
                <div class="p-4">
                    <input type="hidden" id="tipo-actual" value="peatonal">
                    <button type="button" id="btn-registrar" class="w-full px-4 py-3 rounded-xl font-medium text-white transition" style="background: var(--app-primary);">
                        Registrar ingreso
                    </button>
                </div>
            </div>

            <div id="alerta-resultado" class="mt-4 hidden p-4 rounded-lg"></div>
            <div id="qr-salida-container" class="mt-4 text-center hidden p-4 bg-white rounded-xl border" style="border-color: var(--app-border);">
                <p class="text-sm text-green-600 mb-2">Ingreso registrado. QR para registrar salida:</p>
                <div id="qr-salida-img"></div>
                <p class="text-sm text-slate-500 mt-2">Escanear al salir para registrar la salida.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/rut-formatter.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tipoActual = document.getElementById('tipo-actual');
    var btnRegistrar = document.getElementById('btn-registrar');
    var alertaResultado = document.getElementById('alerta-resultado');
    var qrSalidaContainer = document.getElementById('qr-salida-container');
    var qrSalidaImg = document.getElementById('qr-salida-img');

    function actualizarVisibilidadRegistrar() {
        var tipo = tipoActual.value;
        var mostrar = false;
        if (tipo === 'peatonal') {
            var rut = (document.getElementById('rut').value || '').replace(/\s/g, '');
            mostrar = rut.length >= 8;
        } else {
            var patente = (document.getElementById('patente').value || '').trim();
            mostrar = patente.length >= 5;
        }
        document.getElementById('bloque-registrar').classList.toggle('hidden', !mostrar);
    }

    document.getElementById('rut').addEventListener('input', actualizarVisibilidadRegistrar);
    document.getElementById('nombre').addEventListener('input', actualizarVisibilidadRegistrar);
    document.getElementById('patente').addEventListener('input', actualizarVisibilidadRegistrar);

    // Opcional: completar nombre al buscar por RUT
    var rutInput = document.getElementById('rut');
    var nombreInput = document.getElementById('nombre');
    var rutDebounce = null;
    rutInput.addEventListener('blur', function() {
        var rut = (rutInput.value || '').trim();
        if (rut.length < 8) return;
        if (rutDebounce) clearTimeout(rutDebounce);
        rutDebounce = setTimeout(function() {
            axios.get('{{ route("ingresos.buscar-persona") }}', { params: { rut: rut } })
                .then(function(res) {
                    if (res.data.found && res.data.nombre && !nombreInput.value) {
                        nombreInput.value = res.data.nombre;
                    }
                })
                .catch(function() {});
        }, 300);
    });

    function switchTab(tab) {
        tipoActual.value = tab;
        document.querySelectorAll('.tab-btn').forEach(function(btn) {
            if (btn.getAttribute('data-tab') === tab) {
                btn.classList.add('bg-teal-600', 'text-white');
                btn.classList.remove('bg-slate-100', 'text-slate-600');
            } else {
                btn.classList.remove('bg-teal-600', 'text-white');
                btn.classList.add('bg-slate-100', 'text-slate-600');
            }
        });
        document.querySelectorAll('.tab-panel').forEach(function(panel) {
            panel.classList.toggle('hidden', panel.id !== 'panel-' + tab);
        });
        actualizarVisibilidadRegistrar();
    }

    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            switchTab(this.getAttribute('data-tab'));
        });
    });

    btnRegistrar.addEventListener('click', function() {
        var tipo = tipoActual.value;
        var rut = '', nombre = '', patente = '';
        if (tipo === 'peatonal') {
            rut = (document.getElementById('rut').value || '').replace(/\s/g, '');
            nombre = (document.getElementById('nombre').value || '').trim();
            if (!rut || rut.length < 8) {
                alert('Ingrese el RUT.');
                return;
            }
        } else {
            patente = (document.getElementById('patente').value || '').replace(/\s/g, '').toUpperCase();
            rut = (document.getElementById('conductor-rut').value || '').replace(/\s/g, '');
            if (!patente || patente.length < 5) {
                alert('Ingrese la patente.');
                return;
            }
        }

        btnRegistrar.disabled = true;
        alertaResultado.classList.add('hidden');
        qrSalidaContainer.classList.add('hidden');

        var payload = {
            tipo: tipo,
            rut: rut || null,
            nombre: nombre || null,
            patente: patente || null,
            _token: document.querySelector('meta[name="csrf-token"]').content
        };

        axios.post('{{ route("ingresos.store") }}', payload)
            .then(function(res) {
                if (res.data.success) {
                    alertaResultado.className = 'mt-4 p-4 rounded-lg bg-green-100 text-green-800';
                    alertaResultado.textContent = res.data.message;
                    alertaResultado.classList.remove('hidden');
                    if (res.data.qr_salida_url) {
                        qrSalidaImg.innerHTML = '<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(res.data.qr_salida_url) + '" alt="QR Salida" class="mx-auto rounded-lg">';
                        qrSalidaContainer.classList.remove('hidden');
                    }
                    document.getElementById('rut').value = '';
                    document.getElementById('nombre').value = '';
                    document.getElementById('patente').value = '';
                    document.getElementById('conductor-rut').value = '';
                    actualizarVisibilidadRegistrar();
                }
            })
            .catch(function(err) {
                var data = err.response && err.response.data;
                var msg = data && data.motivo ? 'Motivo: ' + data.motivo : (data && data.message ? data.message : 'Error al registrar.');
                alertaResultado.className = 'mt-4 p-4 rounded-lg bg-red-100 text-red-800';
                alertaResultado.textContent = msg;
                alertaResultado.classList.remove('hidden');
            })
            .finally(function() { btnRegistrar.disabled = false; });
    });

    actualizarVisibilidadRegistrar();
});
</script>
@endpush
@endsection
