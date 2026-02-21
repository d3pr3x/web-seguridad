@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />
        <div class="container mx-auto px-4 py-6 max-w-7xl">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
<style>
    .informe-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    
    .informe-header {
        border: 2px solid #000;
        margin-bottom: 20px;
        background: white;
    }
    
    .header-row {
        display: flex;
        border-bottom: 1px solid #000;
    }
    
    .header-cell {
        padding: 15px;
        border-right: 1px solid #000;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
    
    .header-cell:last-child {
        border-right: none;
    }
    
    .logo-section {
        width: 20%;
        text-align: center;
    }
    
    .title-section {
        width: 30%;
        font-weight: bold;
        font-size: 16px;
    }
    
    .logo-efe-section {
        width: 25%;
        text-align: center;
    }
    
    .page-section {
        width: 25%;
        text-align: center;
    }
    
    .bottom-row {
        display: flex;
    }
    
    .bottom-cell {
        padding: 15px;
        border-right: 1px solid #000;
        vertical-align: top;
    }
    
    .bottom-cell:last-child {
        border-right: none;
    }
    
    .report-number {
        width: 20%;
        text-align: center;
        font-weight: bold;
        font-size: 14px;
    }
    
    .elaboration {
        width: 40%;
    }
    
    .approval {
        width: 40%;
    }
    
    .logo-text {
        font-size: 14px;
        font-weight: bold;
    }
    
    .logo-text.blue {
        color: #0066cc;
    }
    
    .logo-text.red {
        color: #cc0000;
    }
    
    .efe-logo {
        font-size: 18px;
        font-weight: bold;
        color: #003366;
    }
    
    .efe-red {
        color: #cc0000;
    }
    
    .content-section {
        margin-bottom: 30px;
    }
    
    .section-title {
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 15px;
        border-bottom: 2px solid #333;
        padding-bottom: 5px;
    }
    
    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    
    .info-table td {
        padding: 10px;
        border: 1px solid #ddd;
    }
    
    .info-table .label {
        background-color: #f5f5f5;
        font-weight: bold;
        width: 30%;
    }
    
    .list-items {
        margin: 15px 0;
    }
    
    .list-item {
        margin-bottom: 8px;
        padding-left: 20px;
        position: relative;
    }
    
    .list-item:before {
        content: "•";
        position: absolute;
        left: 0;
        color: #333;
        font-weight: bold;
    }
    
    .photography-section {
        margin-top: 40px;
        page-break-before: always;
    }
    
    .photo-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .photo-item {
        text-align: center;
        border: 1px solid #ddd;
        padding: 15px;
        background: #f9f9f9;
    }
    
    .photo-image {
        max-width: 100%;
        max-height: 300px;
        border: 1px solid #ccc;
        margin-bottom: 10px;
    }
    
    .photo-caption {
        font-size: 12px;
        color: #666;
        font-weight: bold;
    }
    
    .print-button {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }
    
    @media print {
        .print-button {
            display: none;
        }
        
        .informe-container {
            box-shadow: none;
            margin: 0;
            padding: 0;
        }
        
        .photography-section {
            page-break-before: always;
        }
    }
</style>

<div class="print-button">
    @if($informe->isAprobado())
        <a href="{{ route('informes.pdf', $informe->id) }}" class="btn btn-success me-2">
            <i class="fas fa-file-pdf me-1"></i>Descargar PDF
        </a>
        <button onclick="window.print()" class="btn btn-primary me-2">
            <i class="fas fa-print me-1"></i>Imprimir
        </button>
    @endif
    <a href="{{ route('reportes.show', $informe->reporte_id) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Volver al Reporte
    </a>
</div>

    <!-- Encabezado del Informe -->
    <div class="informe-header">
        <!-- Fila superior -->
        <div class="header-row">
            <div class="header-cell logo-section">
                <div class="logo-text blue">EMACOF</div>
                <div class="logo-text blue" style="font-size: 10px;">León de la Seguridad</div>
            </div>
            <div class="header-cell title-section">
                Reporte de Incidente
            </div>
            <div class="header-cell logo-efe-section">
                <div class="efe-logo">efe</div>
                <div class="logo-text red">TRENES</div>
                <div class="logo-text red">DE</div>
                <div class="logo-text red">CHILE</div>
            </div>
            <div class="header-cell page-section">
                @php
                    $totalHojas = $informe->total_fotografias > 0 ? ceil($informe->total_fotografias / 4) + 1 : 1;
                @endphp
                Página 1 de {{ $totalHojas }}
            </div>
        </div>
        
        <!-- Fila inferior -->
        <div class="bottom-row">
            <div class="bottom-cell report-number">
                N° {{ $informe->numero_informe }}
            </div>
            <div class="bottom-cell elaboration">
                <strong>Elaboró:</strong> {{ $informe->reporte->user->nombre_completo }}<br>
                <strong>Fecha:</strong> {{ $informe->created_at->format('d \d\e F \d\e Y') }}
            </div>
            <div class="bottom-cell approval">
                <strong>Aprobó:</strong> Gerencia de Operaciones<br>
                <strong>Fecha:</strong> {{ $informe->created_at->format('d \d\e F Y') }}
            </div>
        </div>
    </div>

    <!-- Contenido del informe -->
    <div class="content-section">
        <!-- Información general -->
        <div class="section-title">INFORMACIÓN GENERAL</div>
        <table class="info-table">
            <tr>
                <td class="label">Número de Informe:</td>
                <td>{{ $informe->numero_informe }}</td>
            </tr>
            <tr>
                <td class="label">Fecha del Incidente:</td>
                <td>{{ $informe->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Hora del Incidente:</td>
                <td>{{ $informe->hora_formateada }}</td>
            </tr>
            <tr>
                <td class="label">Reporte Base:</td>
                <td>#{{ $informe->reporte->id }} - {{ $informe->reporte->tarea->nombre }}</td>
            </tr>
            <tr>
                <td class="label">Elaborado por:</td>
                <td>{{ $informe->reporte->user->nombre_completo }} ({{ $informe->reporte->user->run }})</td>
            </tr>
            <tr>
                <td class="label">Sucursal:</td>
                <td>{{ $informe->reporte->user->sucursal }}</td>
            </tr>
        </table>
    </div>

    <!-- Descripción del hecho -->
    <div class="content-section">
        <div class="section-title">DESCRIPCIÓN DEL HECHO</div>
        <p>{{ $informe->descripcion }}</p>
    </div>

    <!-- Lesionados -->
    <div class="content-section">
        <div class="section-title">LESIONADOS</div>
        <p>{{ $informe->lesionados }}</p>
    </div>

    <!-- Acciones inmediatas -->
    <div class="content-section">
        <div class="section-title">ACCIONES INMEDIATAS</div>
        <div class="list-items">
            @foreach($informe->acciones_inmediatas as $accion)
                <div class="list-item">{{ $accion }}</div>
            @endforeach
        </div>
    </div>

    <!-- Conclusiones -->
    <div class="content-section">
        <div class="section-title">CONCLUSIONES</div>
        <div class="list-items">
            @foreach($informe->conclusiones as $conclusion)
                <div class="list-item">{{ $conclusion }}</div>
            @endforeach
        </div>
    </div>

    <!-- Set Fotográfico -->
    @if($informe->total_fotografias > 0)
        @php
            // Separar fotos del reporte base de las nuevas
            $fotosReporte = $informe->reporte->imagenes ?? [];
            $fotosInforme = array_slice($informe->fotografias, count($fotosReporte));
            $fotografiasPorHoja = array_chunk($informe->fotografias, 4);
        @endphp
        
        @foreach($fotografiasPorHoja as $hojaIndex => $fotografiasHoja)
            <div class="photography-section">
                <!-- Encabezado para cada hoja de fotografías -->
                <div class="informe-header">
                    <div class="header-row">
                        <div class="header-cell logo-section">
                            <div class="logo-text blue">EMACOF</div>
                            <div class="logo-text blue" style="font-size: 10px;">León de la Seguridad</div>
                        </div>
                        <div class="header-cell title-section">
                            Reporte de Incidente
                        </div>
                        <div class="header-cell logo-efe-section">
                            <div class="efe-logo">efe</div>
                            <div class="logo-text red">TRENES</div>
                            <div class="logo-text red">DE</div>
                            <div class="logo-text red">CHILE</div>
                        </div>
                        <div class="header-cell page-section">
                            Página {{ $hojaIndex + 2 }} de {{ $totalHojas }}
                        </div>
                    </div>
                    
                    <div class="bottom-row">
                        <div class="bottom-cell report-number">
                            N° {{ $informe->numero_informe }}
                        </div>
                        <div class="bottom-cell elaboration">
                            <strong>Elaboró:</strong> {{ $informe->reporte->user->nombre_completo }}<br>
                            <strong>Fecha:</strong> {{ $informe->created_at->format('d \d\e F \d\e Y') }}
                        </div>
                        <div class="bottom-cell approval">
                            <strong>Aprobó:</strong> Gerencia de Operaciones<br>
                            <strong>Fecha:</strong> {{ $informe->created_at->format('d \d\e F Y') }}
                        </div>
                    </div>
                </div>

                <!-- Título de la sección fotográfica -->
                <div class="content-section">
                    <div class="section-title">ANEXO: SET FOTOGRÁFICO</div>
                    <p><strong>Hoja {{ $hojaIndex + 1 }} de {{ count($fotografiasPorHoja) }}</strong></p>
                    
                    @if(count($fotosReporte) > 0 && count($fotosInforme) > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Contenido fotográfico:</strong> 
                            {{ count($fotosReporte) }} fotografía(s) del reporte base + 
                            {{ count($fotosInforme) }} fotografía(s) adicional(es) del informe
                        </div>
                    @elseif(count($fotosReporte) > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Contenido fotográfico:</strong> 
                            {{ count($fotosReporte) }} fotografía(s) del reporte base
                        </div>
                    @elseif(count($fotosInforme) > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Contenido fotográfico:</strong> 
                            {{ count($fotosInforme) }} fotografía(s) del informe
                        </div>
                    @endif
                </div>

                <!-- Grid de fotografías -->
                <div class="photo-grid">
                    @for($i = 0; $i < 4; $i++)
                        <div class="photo-item">
                            @if(isset($fotografiasHoja[$i]))
                                @php
                                    $fotoNumber = $hojaIndex * 4 + $i + 1;
                                    $fotoIndex = $hojaIndex * 4 + $i;
                                    $esFotoReporte = $fotoIndex < count($fotosReporte);
                                @endphp
                                
                                <img src="{{ route('archivos-privados.informe', [$informe, $fotoIndex]) }}" 
                                     class="photo-image" 
                                     alt="Fotografía {{ $fotoNumber }}">
                                <div class="photo-caption">
                                    Fotografía {{ $fotoNumber }}
                                    @if($esFotoReporte)
                                        <br><small class="text-primary">(del reporte base)</small>
                                    @else
                                        <br><small class="text-success">(del informe)</small>
                                    @endif
                                </div>
                            @else
                                <div style="height: 300px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; color: #999; background: #f9f9f9;">
                                    -
                                </div>
                                <div class="photo-caption">-</div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        @endforeach
    @endif

    <!-- Pie de página -->
    <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666;">
        <p>Informe generado el {{ $informe->created_at->format('d/m/Y H:i') }} | Sistema de Reportes EMACOF</p>
    </div>

    <!-- Sección de Aprobación -->
    @if($informe->isPendienteRevision() || $informe->isRechazado())
        <div style="margin-top: 40px; padding: 20px; border: 2px solid #007bff; border-radius: 8px; background: #f8f9fa;">
            <h5 style="color: #007bff; margin-bottom: 20px;">
                <i class="fas fa-clipboard-check me-2"></i>Revisión del Informe
            </h5>
            
            <div class="row">
                <div class="col-md-8">
                    <p><strong>Estado actual:</strong> 
                        <span class="badge 
                            @if($informe->isPendienteRevision()) bg-warning
                            @elseif($informe->isRechazado()) bg-danger
                            @endif
                        ">
                            {{ $informe->estado_formateado }}
                        </span>
                    </p>
                    
                    @if($informe->isRechazado())
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-1"></i>Informe Rechazado</h6>
                            @if($informe->comentarios_aprobacion)
                                <p><strong>Motivo del rechazo:</strong></p>
                                <p>{{ $informe->comentarios_aprobacion }}</p>
                            @endif
                            <p><strong>Rechazado por:</strong> {{ $informe->aprobado_por }} el {{ $informe->fecha_aprobacion_formateada }}</p>
                        </div>
                    @endif
                </div>
                
                <div class="col-md-4">
                    @if($informe->isPendienteRevision())
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#aprobarModal">
                                <i class="fas fa-check me-1"></i>Aprobar Informe
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rechazarModal">
                                <i class="fas fa-times me-1"></i>Rechazar Informe
                            </button>
                        </div>
                    @elseif($informe->isRechazado())
                        <div class="d-grid">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reenviarModal">
                                <i class="fas fa-redo me-1"></i>Reenviar para Revisión
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @elseif($informe->isAprobado())
        <div style="margin-top: 40px; padding: 20px; border: 2px solid #28a745; border-radius: 8px; background: #d4edda;">
            <h5 style="color: #28a745; margin-bottom: 20px;">
                <i class="fas fa-check-circle me-2"></i>Informe Aprobado
            </h5>
            
            <div class="row">
                <div class="col-md-8">
                    <p><strong>Aprobado por:</strong> {{ $informe->aprobado_por }}</p>
                    <p><strong>Fecha de aprobación:</strong> {{ $informe->fecha_aprobacion_formateada }}</p>
                    @if($informe->comentarios_aprobacion)
                        <p><strong>Comentarios:</strong> {{ $informe->comentarios_aprobacion }}</p>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('informes.pdf', $informe->id) }}" class="btn btn-success">
                            <i class="fas fa-file-pdf me-1"></i>Descargar PDF
                        </a>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print me-1"></i>Imprimir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal para Aprobar -->
<div class="modal fade" id="aprobarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check text-success me-2"></i>Aprobar Informe
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('informes.aprobar', $informe->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>¿Está seguro de que desea aprobar este informe?</p>
                    <div class="mb-3">
                        <label for="comentarios_aprobacion" class="form-label">Comentarios (opcional):</label>
                        <textarea class="form-control" id="comentarios_aprobacion" name="comentarios_aprobacion" rows="3" 
                                  placeholder="Agregar comentarios sobre la aprobación..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Aprobar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Rechazar -->
<div class="modal fade" id="rechazarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-times text-danger me-2"></i>Rechazar Informe
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('informes.rechazar', $informe->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>¿Está seguro de que desea rechazar este informe?</p>
                    <div class="mb-3">
                        <label for="comentarios_rechazo" class="form-label">Motivo del rechazo <span class="text-danger">*</span>:</label>
                        <textarea class="form-control" id="comentarios_rechazo" name="comentarios_aprobacion" rows="3" 
                                  placeholder="Explique el motivo del rechazo..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>Rechazar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Reenviar -->
<div class="modal fade" id="reenviarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-redo text-primary me-2"></i>Reenviar Informe
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('informes.reenviar', $informe->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>¿Está seguro de que desea reenviar este informe para revisión?</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        El informe volverá al estado "Pendiente de Revisión" y podrá ser evaluado nuevamente.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-redo me-1"></i>Reenviar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
@endsection
