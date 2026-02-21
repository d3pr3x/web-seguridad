<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte por Sucursal - {{ $fecha }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        
        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-left {
            flex: 1;
        }
        
        .header-right {
            text-align: right;
        }
        
        .logo {
            max-height: 60px;
            margin-bottom: 10px;
        }
        
        .stats-row {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }
        
        .stat-box {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            flex: 1;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #1e3c72;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .sucursal-header {
            background: #1e3c72;
            color: white;
            padding: 15px;
            margin: 20px 0 0 0;
            border-radius: 8px 8px 0 0;
        }
        
        .sucursal-title {
            margin: 0;
            font-size: 18px;
        }
        
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .report-table th {
            background: #1e3c72;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
        }
        
        .report-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e9ecef;
            font-size: 11px;
            vertical-align: top;
        }
        
        .report-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge-info {
            background: #17a2b8;
            color: white;
        }
        
        .badge-success {
            background: #28a745;
            color: white;
        }
        
        .badge-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .badge-danger {
            background: #dc3545;
            color: white;
        }
        
        .foto-cell {
            width: 60px;
            text-align: center;
        }
        
        .foto-thumb {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            border-top: 2px solid #1e3c72;
            padding-top: 15px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            body { margin: 0; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div>
                        <div style="background: #f8f9fa; padding: 8px 12px; border-radius: 4px; display: inline-block; margin-right: 10px;">
                            <strong>FILE:</strong> {{ $totalReportes }}
                        </div>
                        @if($sucursal)
                            <div style="background: #f8f9fa; padding: 8px 12px; border-radius: 4px; display: inline-block; margin-right: 10px;">
                                <strong>{{ strtoupper($sucursal->nombre) }}</strong>
                            </div>
                        @endif
                        <div style="background: #28a745; color: white; padding: 8px 12px; border-radius: 4px; display: inline-block; margin-right: 10px;">
                            <strong>ACCIONES DISUASIVAS:</strong> {{ $accionesDisuasivas }}
                        </div>
                        <div style="background: #1e3c72; color: white; padding: 8px 12px; border-radius: 4px; display: inline-block;">
                            <strong>DELITOS EN TURNOS:</strong> {{ $delitosEnTurnos }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-right">
                <div style="text-align: right;">
                    <div style="font-size: 14px; margin-bottom: 5px;">EMACOF</div>
                    <div style="font-size: 12px;">León de la Seguridad</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-number">{{ $totalReportes }}</div>
            <div class="stat-label">Total Reportes</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $accionesDisuasivas }}</div>
            <div class="stat-label">Acciones Disuasivas</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $delitosEnTurnos }}</div>
            <div class="stat-label">Delitos en Turnos</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $reportesPorSucursal->count() }}</div>
            <div class="stat-label">Instalaciones</div>
        </div>
    </div>

    <!-- Reportes por Sucursal -->
    @if($reportesPorSucursal->count() > 0)
        @foreach($reportesPorSucursal as $nombreSucursal => $reportesSucursal)
            <div class="sucursal-header">
                <h3 class="sucursal-title">{{ $nombreSucursal }} ({{ $reportesSucursal->count() }} reportes)</h3>
            </div>
            
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Hora</th>
                        <th>Novedad</th>
                        <th>Acciones</th>
                        <th>Resultado</th>
                        <th>Fotografía</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportesSucursal as $reporte)
                        <tr>
                            <td>{{ \App\Helpers\DateHelper::formatChile($reporte->created_at, 'd.m.Y') }}</td>
                            <td>{{ $reporte->created_at->format('H.i') }}</td>
                            <td>
                                <span class="badge badge-info">{{ $reporte->tarea->nombre }}</span>
                            </td>
                            <td>
                                @if(isset($reporte->datos['acciones']))
                                    {{ $reporte->datos['acciones'] }}
                                @else
                                    <span style="color: #6c757d;">-</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($reporte->datos['resultado']))
                                    <span class="badge badge-success">{{ $reporte->datos['resultado'] }}</span>
                                @else
                                    <span style="color: #6c757d;">-</span>
                                @endif
                            </td>
                            <td class="foto-cell">
                                @if($reporte->imagenes_abs && count($reporte->imagenes_abs) > 0)
                                    @foreach($reporte->imagenes_abs as $imagenPath)
                                        <img src="{{ $imagenPath }}" 
                                             class="foto-thumb" 
                                             alt="Imagen del reporte">
                                    @endforeach
                                @else
                                    <span style="color: #6c757d;">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    @else
        <div class="no-data">
            <h4>No hay reportes</h4>
            <p>No se encontraron reportes para la fecha y sucursal seleccionadas.</p>
        </div>
    @endif

    <!-- Pie de página -->
    <div class="footer">
        <p>© {{ date('Y') }} Sistema de Seguridad. Todos los derechos reservados.</p>
        <p>Generado el {{ \App\Helpers\DateHelper::formatChileWithTime(now()) }}</p>
    </div>
</body>
</html>

