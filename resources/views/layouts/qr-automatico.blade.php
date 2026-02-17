<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'QR Carnet - Control de acceso')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        body { margin: 0; font-family: system-ui, sans-serif; background: #0f172a; color: #fff; min-height: 100vh; display: flex; flex-direction: column; }
        .barra { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background: #0f172a; border-bottom: 1px solid #334155; }
        .barra a { color: #94a3b8; text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.5rem; }
        .barra a:hover { color: #5eead4; }
        .contenido { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1rem; }
        #reader { border-radius: 12px; overflow: hidden; border: 3px solid #0d9488; max-width: 100%; }
        #reader video { width: 100% !important; }
        .mensaje { margin-top: 1rem; text-align: center; font-size: 0.875rem; min-height: 1.25rem; }
        .mensaje.ok { color: #5eead4; }
        .mensaje.err { color: #f87171; }
        .btn-volver { padding: 0.5rem 1rem; background: #334155; color: #e2e8f0; border-radius: 8px; font-size: 0.875rem; margin-top: 1rem; text-decoration: none; display: inline-block; }
        .btn-volver:hover { background: #475569; color: #fff; }
        #qr-salida-box { margin-top: 1rem; padding: 1rem; background: #1e293b; border-radius: 12px; text-align: center; }
        #qr-salida-box img { max-width: 200px; border-radius: 8px; }
        #lector-cedula { width: 100%; position: relative; }
        #lector-cedula video { width: 100% !important; max-height: 50vh; min-height: 220px; object-fit: cover; display: block; }
    </style>
</head>
<body>
    <div class="barra">
        <a href="{{ route('ingresos.index') }}">&larr; Ingresos</a>
        <span class="text-sm text-slate-400">QR carnet</span>
    </div>
    <main class="contenido">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
