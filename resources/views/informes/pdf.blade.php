<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Incidente N° {{ $informe->numero_informe }}</title>
    <style>
        @page {
            size: letter;
            margin-top: 1.2cm;
            margin-bottom: 2.5cm;
            margin-left: 1.2cm;
            margin-right: 1.2cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .header-doc {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 25px;
        }
        .header-doc td {
            border: 1px solid #000;
            vertical-align: middle;
            padding: 8px 10px;
        }
        .header-doc .col-logo {
            width: 22%;
        }
        .header-doc .col-titulo {
            width: 28%;
            text-align: center;
        }
        .header-doc .col-logos-derecha {
            width: 28%;
            text-align: center;
        }
        .header-doc .col-pagina {
            width: 22%;
            text-align: right;
            font-size: 11pt;
        }
        .header-doc .logo-placeholder {
            min-height: 50px;
        }
        .header-doc .logos-placeholder {
            min-height: 44px;
        }
        .header-doc .titulo-reporte {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
        }
        .header-doc .fila-metadatos td {
            padding: 10px 12px;
            vertical-align: top;
        }
        .header-doc .celda-numero {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            width: 15%;
        }
        .header-doc .celda-elaboro {
            width: 42%;
            font-size: 10pt;
        }
        .header-doc .celda-aprobo {
            width: 43%;
            font-size: 10pt;
        }

        h2 {
            font-size: 13pt;
            font-weight: bold;
            margin: 25px 0 15px 0;
        }
        h3 {
            font-size: 11pt;
            font-weight: bold;
            margin: 25px 0 10px 0;
        }

        p {
            margin: 0 0 15px 0;
            text-align: justify;
        }

        .foto {
            text-align: center;
            margin: 20px 0;
        }
        .foto img {
            max-width: 80%;
            border: 1px solid #000;
        }
        .foto p {
            font-size: 10pt;
            margin-top: 10px;
            text-align: center;
        }

        .foto-placeholder {
            width: 80%;
            margin: 0 auto;
            min-height: 180px;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fafafa;
            color: #333;
            font-size: 10pt;
        }

        ol {
            margin: 0 0 15px 20px;
            padding: 0;
        }
        ol li {
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .letras {
            list-style: none;
            margin: 0 0 15px 20px;
            padding: 0;
        }
        .letras li {
            margin-bottom: 8px;
            line-height: 1.5;
        }

        /* Evita que una sección se parta entre páginas; si no cabe, salta a la siguiente */
        .seccion {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    @php
        $fechaTexto = $informe->created_at->locale('es')->translatedFormat('d \d\e F \d\e Y');
        $elaboro = $informe->reporte->user->nombre_completo ?? 'Supervisor';
        $aprobo = $informe->aprobado_por ?? 'Administrador de Contrato';
        $fechaAprobacion = $informe->fecha_aprobacion
            ? $informe->fecha_aprobacion->locale('es')->translatedFormat('d \d\e F \d\e Y')
            : $fechaTexto;
        $primeraFoto = isset($informe->fotografias[0]) ? $informe->fotografias[0] : null;
        $primeraFotoPath = $primeraFotoPath ?? null;
    @endphp

    <table class="header-doc">
        <tr>
            <td class="col-logo">
                <div class="logo-placeholder"></div>
            </td>
            <td class="col-titulo">
                <p class="titulo-reporte">Reporte de Incidente</p>
            </td>
            <td class="col-logos-derecha">
                <div class="logos-placeholder"></div>
            </td>
            <td class="col-pagina">Página 1</td>
        </tr>
        <tr class="fila-metadatos">
            <td class="celda-numero">N° {{ $informe->numero_informe }}</td>
            <td class="celda-elaboro">
                Elaboró: {{ $elaboro }}<br>
                Fecha: {{ $fechaTexto }}
            </td>
            <td colspan="2" class="celda-aprobo">
                Aprobó: {{ $aprobo }}<br>
                Fecha: {{ $fechaAprobacion }}
            </td>
        </tr>
    </table>

    <div class="seccion">
        <h2>I. ANTECEDENTES EVENTO.</h2>
        <h3>RELACION DE LOS HECHOS:</h3>
        <p>{!! nl2br(e($informe->descripcion)) !!}</p>
    </div>

    <div class="seccion">
        <h2>II. CURSO DE ACCION</h2>
        <ol>
            @foreach($informe->acciones_inmediatas as $accion)
                <li>{{ $accion }}</li>
            @endforeach
        </ol>
    </div>

    <div class="seccion">
        <h2>III. CONCLUSIONES</h2>
        <ul class="letras">
            @foreach($informe->conclusiones as $idx => $conclusion)
                <li>{{ chr(97 + $idx) }}) {{ $conclusion }}</li>
            @endforeach
        </ul>
    </div>

    <div class="seccion">
        <h3 style="margin-top: 30px;">FIJACION FOTOGRÁFICA:</h3>
        <div class="foto">
            @if($primeraFotoPath && file_exists($primeraFotoPath))
                <img src="{{ $primeraFotoPath }}" alt="Foto 1">
            @else
                <div class="foto-placeholder">[ESPACIO PARA FOTOGRAFÍA]</div>
            @endif
            <p>Foto 1: {{ $informe->reporte->tarea->nombre ?? 'Evidencia del incidente' }}</p>
        </div>
    </div>
</body>
</html>
