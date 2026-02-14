<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Incidente N° {{ $informe->numero_informe }}</title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            border: 2px solid #000;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .header-row {
            width: 100%;
            border-collapse: collapse;
        }
        
        .header-cell {
            float: left;
            padding: 10px;
            border-right: 1px solid #000;
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        
        .header-cell:last-child {
            border-right: none;
        }
        
        .logo-section {
            width: 20%;
        }
        
        .title-section {
            width: 30%;
            font-weight: bold;
            font-size: 16px;
        }
        
        .logo-efe-section {
            width: 25%;
        }
        
        .page-section {
            width: 25%;
        }
        
        .header-row:after {
            content: "";
            display: table;
            clear: both;
        }
        
        .bottom-row {
            width: 100%;
            border-collapse: collapse;
            border-top: 1px solid #000;
            clear: both;
        }
        
        .bottom-cell {
            float: left;
            padding: 10px;
            border-right: 1px solid #000;
            min-height: 40px;
        }
        
        .bottom-cell:last-child {
            border-right: none;
        }
        
        .report-number {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            width: 20%;
        }
        
        .elaboration {
            width: 40%;
        }
        
        .approval {
            width: 40%;
        }
        
        .bottom-row:after {
            content: "";
            display: table;
            clear: both;
        }
        
        .content {
            margin-top: 20px;
        }
        
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        .info-table .label {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 30%;
        }
        
        .list-items {
            margin: 10px 0;
        }
        
        .list-item {
            margin-bottom: 5px;
            padding-left: 20px;
            position: relative;
        }
        
        .list-item:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #333;
        }
        
        .photography-section {
            page-break-before: always;
        }
        
        .photo-grid {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .photo-cell {
            float: left;
            width: 48%;
            padding: 10px;
            margin: 1%;
            text-align: center;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        
        .photo-grid:after {
            content: "";
            display: table;
            clear: both;
        }
        
        .photo-image {
            max-width: 100%;
            max-height: 200px;
            border: 1px solid #ccc;
        }
        
        .photo-caption {
            margin-top: 5px;
            font-size: 10px;
            color: #666;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
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
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
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
                Página 1 de {{ count($fotografiasPorHoja) + 1 }}
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
    <div class="content">
        <!-- Información general -->
        <div class="section">
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
        <div class="section">
            <div class="section-title">DESCRIPCIÓN DEL HECHO</div>
            <p>{{ $informe->descripcion }}</p>
        </div>

        <!-- Lesionados -->
        <div class="section">
            <div class="section-title">LESIONADOS</div>
            <p>{{ $informe->lesionados }}</p>
        </div>

        <!-- Acciones inmediatas -->
        <div class="section">
            <div class="section-title">ACCIONES INMEDIATAS</div>
            <div class="list-items">
                @foreach($informe->acciones_inmediatas as $accion)
                    <div class="list-item">{{ $accion }}</div>
                @endforeach
            </div>
        </div>

        <!-- Conclusiones -->
        <div class="section">
            <div class="section-title">CONCLUSIONES</div>
            <div class="list-items">
                @foreach($informe->conclusiones as $conclusion)
                    <div class="list-item">{{ $conclusion }}</div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Set Fotográfico -->
    @if($informe->total_fotografias > 0)
        @foreach($fotografiasPorHoja as $hojaIndex => $fotografiasHoja)
            <div class="photography-section">
                <!-- Encabezado para cada hoja de fotografías -->
                <div class="header">
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
                            Página {{ $hojaIndex + 2 }} de {{ count($fotografiasPorHoja) + 1 }}
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
                <div class="section">
                    <div class="section-title">ANEXO: SET FOTOGRÁFICO</div>
                    <p><strong>Hoja {{ $hojaIndex + 1 }} de {{ count($fotografiasPorHoja) }}</strong></p>
                    
                    @php
                        $fotosReporte = $informe->reporte->imagenes ?? [];
                        $fotosInforme = array_slice($informe->fotografias, count($fotosReporte));
                    @endphp
                    
                    @if(count($fotosReporte) > 0 && count($fotosInforme) > 0)
                        <p style="font-size: 10px; color: #666; margin: 10px 0;">
                            <strong>Contenido fotográfico:</strong> 
                            {{ count($fotosReporte) }} fotografía(s) del reporte base + 
                            {{ count($fotosInforme) }} fotografía(s) adicional(es) del informe
                        </p>
                    @elseif(count($fotosReporte) > 0)
                        <p style="font-size: 10px; color: #666; margin: 10px 0;">
                            <strong>Contenido fotográfico:</strong> 
                            {{ count($fotosReporte) }} fotografía(s) del reporte base
                        </p>
                    @elseif(count($fotosInforme) > 0)
                        <p style="font-size: 10px; color: #666; margin: 10px 0;">
                            <strong>Contenido fotográfico:</strong> 
                            {{ count($fotosInforme) }} fotografía(s) del informe
                        </p>
                    @endif
                </div>

                <!-- Grid de fotografías -->
                <div class="photo-grid">
                    @for($i = 0; $i < 4; $i++)
                        <div class="photo-cell">
                            @if(isset($fotografiasHoja[$i]))
                                @php
                                    $fotoPath = public_path('storage/' . $fotografiasHoja[$i]);
                                    $fotoNumber = $hojaIndex * 4 + $i + 1;
                                    $fotoIndex = $hojaIndex * 4 + $i;
                                    $esFotoReporte = $fotoIndex < count($fotosReporte);
                                @endphp
                                
                                @if(file_exists($fotoPath))
                                    <img src="{{ $fotoPath }}" class="photo-image" alt="Fotografía {{ $fotoNumber }}">
                                    <div class="photo-caption">
                                        Fotografía {{ $fotoNumber }}
                                        @if($esFotoReporte)
                                            <br><small style="color: #0066cc;">(del reporte base)</small>
                                        @else
                                            <br><small style="color: #28a745;">(del informe)</small>
                                        @endif
                                    </div>
                                @else
                                    <div style="height: 200px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; color: #999;">
                                        Imagen no disponible
                                    </div>
                                    <div class="photo-caption">
                                        Fotografía {{ $fotoNumber }} - No disponible
                                        @if($esFotoReporte)
                                            <br><small style="color: #0066cc;">(del reporte base)</small>
                                        @else
                                            <br><small style="color: #28a745;">(del informe)</small>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div style="height: 200px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; color: #999;">
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
    <div class="footer">
        <p>Informe generado el {{ $informe->created_at->format('d/m/Y H:i') }} | Sistema de Reportes EMACOF</p>
    </div>
</body>
</html>
