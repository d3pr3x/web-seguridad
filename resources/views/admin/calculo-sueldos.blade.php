@extends('layouts.app')

@section('title', 'Cálculo de Sueldos')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-calculator me-2"></i>Cálculo de Sueldos
            </h1>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.calculo-sueldos') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="mes" class="form-label">Mes</label>
                        <input type="month" class="form-control" id="mes" name="mes" value="{{ $mes }}">
                    </div>
                    <div class="col-md-3">
                        <label for="sucursal_id" class="form-label">Sucursal</label>
                        <select class="form-control" id="sucursal_id" name="sucursal_id">
                            <option value="">Todas las sucursales</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}" @if($sucursalId == $sucursal->id) selected @endif>
                                    {{ $sucursal->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="sueldo_base" class="form-label">Sueldo Base Diario</label>
                        <input type="number" class="form-control" id="sueldo_base" name="sueldo_base" value="{{ $sueldoBase }}" min="0">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-calculator me-1"></i>Calcular
                        </button>
                        <a href="{{ route('admin.calculo-sueldos.exportar', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download me-1"></i>Exportar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas generales -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x mb-2"></i>
                <h4>{{ $totalEmpleados }}</h4>
                <p class="mb-0">Empleados</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-calendar-check fa-2x mb-2"></i>
                <h4>{{ $totalDiasTrabajados }}</h4>
                <p class="mb-0">Días trabajados</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-dollar-sign fa-2x mb-2"></i>
                <h4>${{ number_format($totalSueldoBruto, 0, ',', '.') }}</h4>
                <p class="mb-0">Sueldo bruto total</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                <h4>${{ number_format($totalSueldoNeto, 0, ',', '.') }}</h4>
                <p class="mb-0">Sueldo neto total</p>
            </div>
        </div>
    </div>
</div>

<!-- Cálculos por empleado -->
@if(count($calculos) > 0)
    <div class="row">
        <div class="col-12">
            <h3 class="h5 mb-3">
                <i class="fas fa-list me-2"></i>Cálculos por Empleado - {{ \Carbon\Carbon::parse($mes . '-01')->format('F Y') }}
            </h3>
        </div>
    </div>
    
    <div class="row">
        @foreach($calculos as $calculo)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-user me-1"></i>{{ $calculo['usuario']->nombre_completo }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <strong>Sucursal:</strong> {{ $calculo['usuario']->nombre_sucursal }}
                        </p>
                        <p class="card-text">
                            <strong>RUT:</strong> {{ $calculo['usuario']->rut }}
                        </p>
                        
                        <hr>
                        
                        <div class="row text-center">
                            <div class="col-6">
                                <h6 class="text-primary">{{ $calculo['dias_trabajados'] }}</h6>
                                <small class="text-muted">Días trabajados</small>
                            </div>
                            <div class="col-6">
                                <h6 class="text-success">${{ number_format($calculo['sueldo_neto'], 0, ',', '.') }}</h6>
                                <small class="text-muted">Sueldo neto</small>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-2">Desglose de días:</h6>
                                <div class="row text-center">
                                    @if($calculo['dias_normales'] > 0)
                                        <div class="col-6 mb-1">
                                            <span class="badge bg-success">{{ $calculo['dias_normales'] }}</span>
                                            <small class="d-block text-muted">Hábiles</small>
                                        </div>
                                    @endif
                                    @if($calculo['dias_sabados'] > 0)
                                        <div class="col-6 mb-1">
                                            <span class="badge bg-warning">{{ $calculo['dias_sabados'] }}</span>
                                            <small class="d-block text-muted">Sábados</small>
                                        </div>
                                    @endif
                                    @if($calculo['dias_domingos'] > 0)
                                        <div class="col-6 mb-1">
                                            <span class="badge bg-info">{{ $calculo['dias_domingos'] }}</span>
                                            <small class="d-block text-muted">Domingos</small>
                                        </div>
                                    @endif
                                    @if($calculo['dias_feriados'] > 0)
                                        <div class="col-6 mb-1">
                                            <span class="badge bg-danger">{{ $calculo['dias_feriados'] }}</span>
                                            <small class="d-block text-muted">Feriados</small>
                                        </div>
                                    @endif
                                    @if($calculo['dias_extras'] > 0)
                                        <div class="col-6 mb-1">
                                            <span class="badge bg-primary">{{ $calculo['dias_extras'] }}</span>
                                            <small class="d-block text-muted">Extras</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-2">Detalle económico:</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Sueldo bruto:</small>
                                        <div class="fw-bold">${{ number_format($calculo['sueldo_bruto'], 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Descuentos:</small>
                                        <div class="fw-bold text-danger">-${{ number_format($calculo['descuentos'], 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-outline-primary btn-sm" onclick="verDetalle({{ $calculo['usuario']->id }})">
                            <i class="fas fa-eye me-1"></i>Ver Detalle
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-3x mb-3"></i>
                <h4>No hay datos</h4>
                <p class="mb-3">No se encontraron empleados con días trabajados para el mes seleccionado.</p>
            </div>
        </div>
    </div>
@endif

<!-- Modal para ver detalle -->
<div class="modal fade" id="detalleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de días trabajados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalleContent">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function verDetalle(usuarioId) {
        // Aquí se cargaría el detalle de días trabajados del usuario
        // Por ahora mostramos un mensaje
        document.getElementById('detalleContent').innerHTML = `
            <div class="text-center">
                <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                <p>Cargando detalle de días trabajados...</p>
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('detalleModal'));
        modal.show();
        
        // Simular carga de datos
        setTimeout(() => {
            document.getElementById('detalleContent').innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Funcionalidad de detalle en desarrollo. Aquí se mostrarían todos los días trabajados del empleado con sus respectivos cálculos.
                </div>
            `;
        }, 1000);
    }
</script>
@endpush
