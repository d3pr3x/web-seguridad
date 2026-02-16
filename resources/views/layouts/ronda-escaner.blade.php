<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Escanear QR - Ronda')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { margin: 0; font-family: system-ui, sans-serif; background: #0f172a; color: #fff; min-height: 100vh; display: flex; flex-direction: column; }
        .barra { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background: #0f172a; border-bottom: 1px solid #334155; }
        .barra a { color: #94a3b8; text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.5rem; }
        .barra a:hover { color: #5eead4; }
        .contenido-escaner { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1rem; }
        #reader { border-radius: 12px; overflow: hidden; border: 3px solid #0d9488; max-width: 100%; }
        #reader video { width: 100% !important; }
        .mensaje-escaneo { margin-top: 1rem; text-align: center; font-size: 0.875rem; color: #5eead4; min-height: 1.25rem; }
        .btn-volver { padding: 0.5rem 1rem; background: #334155; color: #e2e8f0; border-radius: 8px; font-size: 0.875rem; margin-top: 1rem; }
        .btn-volver:hover { background: #475569; color: #fff; }
    </style>
</head>
<body>
    <div class="barra">
        <a href="{{ route('usuario.ronda.index') }}">&larr; Rondas</a>
        <span class="text-sm text-slate-400">Escanear QR</span>
    </div>
    <main class="contenido-escaner">
        @yield('content')
    </main>
</body>
</html>
