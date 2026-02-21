@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-4xl">
            <div class="mb-6">
                <a href="{{ route('admin.auditorias.index') }}" class="text-indigo-600 hover:underline text-sm">← Volver al listado</a>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Detalle de auditoría #{{ $auditoria->id }}</h1>

            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <p><span class="font-medium text-gray-700">Fecha:</span> {{ $auditoria->ocurrido_en?->format('d/m/Y H:i:s') }}</p>
                <p><span class="font-medium text-gray-700">Usuario:</span> {{ $auditoria->usuario?->nombre_completo ?? '—' }}</p>
                <p><span class="font-medium text-gray-700">Acción:</span> {{ $auditoria->accion }}</p>
                <p><span class="font-medium text-gray-700">Tabla:</span> {{ $auditoria->tabla }}</p>
                <p><span class="font-medium text-gray-700">ID registro:</span> {{ $auditoria->registro_id ?? '—' }}</p>
                <p><span class="font-medium text-gray-700">Ruta:</span> {{ $auditoria->route ?? '—' }}</p>
                <p><span class="font-medium text-gray-700">IP:</span> {{ $auditoria->ip ?? '—' }}</p>

                @if($auditoria->cambios_antes && count($auditoria->cambios_antes) > 0)
                    <div>
                        <p class="font-medium text-gray-700 mb-2">Valores anteriores</p>
                        <pre class="bg-gray-50 p-4 rounded text-sm overflow-auto max-h-64">{{ json_encode($auditoria->cambios_antes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                @endif
                @if($auditoria->cambios_despues && count($auditoria->cambios_despues) > 0)
                    <div>
                        <p class="font-medium text-gray-700 mb-2">Valores nuevos</p>
                        <pre class="bg-gray-50 p-4 rounded text-sm overflow-auto max-h-64">{{ json_encode($auditoria->cambios_despues, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
