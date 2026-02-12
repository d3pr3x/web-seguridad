@extends('layouts.app')

@section('title', 'Detalle ingreso #' . $ingreso->id)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-user me-2"></i>Ingreso #{{ $ingreso->id }}</h1>
        <a href="{{ route('ingresos.index') }}" class="btn btn-outline-primary btn-sm">Volver al listado</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">Datos del ingreso</div>
                <div class="card-body">
                    <p><strong>Fecha ingreso:</strong> {{ $ingreso->fecha_ingreso->format('d/m/Y H:i:s') }}</p>
                    <p><strong>Tipo:</strong> {{ $ingreso->tipo === 'peatonal' ? 'Peatonal' : 'Vehicular' }}</p>
                    <p><strong>RUT:</strong> {{ $ingreso->rut }}</p>
                    <p><strong>Nombre:</strong> {{ $ingreso->nombre ?: '-' }}</p>
                    @if($ingreso->patente)
                        <p><strong>Patente:</strong> {{ $ingreso->patente }}</p>
                    @endif
                    <p><strong>Guardia:</strong> {{ $ingreso->guardia->name ?? '-' }}</p>
                    <p><strong>Estado:</strong>
                        @if($ingreso->estado === 'ingresado')
                            <span class="badge bg-success">Ingresado</span>
                        @elseif($ingreso->estado === 'bloqueado')
                            <span class="badge bg-danger">Bloqueado</span>
                        @else
                            <span class="badge bg-secondary">Salida</span>
                            @if($ingreso->fecha_salida)
                                <br><small>{{ $ingreso->fecha_salida->format('d/m/Y H:i:s') }}</small>
                            @endif
                        @endif
                    </p>
                    @if($ingreso->estado === 'ingresado')
                        <form action="{{ route('ingresos.salida', $ingreso->id) }}" method="post" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-primary">Registrar salida</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            @if($ingreso->estado === 'ingresado')
                <div class="card shadow-sm">
                    <div class="card-header bg-light">QR para registrar salida</div>
                    <div class="card-body text-center">
                        <p class="small text-muted">Escanear al salir para registrar la salida.</p>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($qrSalidaUrl) }}" alt="QR Salida" class="img-fluid">
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
