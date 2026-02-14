@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:ml-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-4xl">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <svg class="w-8 h-8 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Ingreso #{{ $ingreso->id }}
                </h1>
                <a href="{{ route('ingresos.index') }}" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition text-sm">Volver al listado</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 font-medium text-gray-700">Datos del ingreso</div>
                    <div class="p-6 space-y-2 text-sm">
                        <p><span class="font-medium text-gray-600">Fecha ingreso:</span> {{ $ingreso->fecha_ingreso->format('d/m/Y H:i:s') }}</p>
                        <p><span class="font-medium text-gray-600">Tipo:</span> {{ $ingreso->tipo === 'peatonal' ? 'Peatonal' : 'Vehicular' }}</p>
                        <p><span class="font-medium text-gray-600">RUT:</span> {{ $ingreso->rut }}</p>
                        <p><span class="font-medium text-gray-600">Nombre:</span> {{ $ingreso->nombre ?: '-' }}</p>
                        @if($ingreso->patente)
                            <p><span class="font-medium text-gray-600">Patente:</span> {{ $ingreso->patente }}</p>
                        @endif
                        <p><span class="font-medium text-gray-600">Guardia:</span> {{ $ingreso->guardia->nombre_completo ?? '-' }}</p>
                        <p>
                            <span class="font-medium text-gray-600">Estado:</span>
                            @if($ingreso->estado === 'ingresado')
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Ingresado</span>
                            @elseif($ingreso->estado === 'bloqueado')
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Bloqueado</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Salida</span>
                                @if($ingreso->fecha_salida)
                                    <br><span class="text-gray-500">{{ $ingreso->fecha_salida->format('d/m/Y H:i:s') }}</span>
                                @endif
                            @endif
                        </p>
                        @if($ingreso->estado === 'ingresado')
                            <form action="{{ route('ingresos.salida', $ingreso->id) }}" method="post" class="mt-4">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">Registrar salida</button>
                            </form>
                        @endif
                    </div>
                </div>
                @if($ingreso->estado === 'ingresado')
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 font-medium text-gray-700">QR para registrar salida</div>
                        <div class="p-6 text-center">
                            <p class="text-sm text-gray-500 mb-3">Escanear al salir para registrar la salida.</p>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($qrSalidaUrl) }}" alt="QR Salida" class="mx-auto rounded-lg">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
