@php
    $isAuth = auth()->check();
    $primaryUrl = url('/go');
    $primaryLabel = $isAuth ? 'Ir a inicio' : 'Ir al login';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1e293b">
    <title>404 - Página no encontrada</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .container {
            max-width: 360px;
            width: 100%;
            text-align: center;
        }
        .code {
            font-size: 4rem;
            font-weight: 800;
            color: #fbbf24;
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #f8fafc;
        }
        p {
            font-size: 0.9375rem;
            color: #94a3b8;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
        .actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 1rem;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            transition: opacity 0.2s;
        }
        .btn:active { opacity: 0.9; }
        .btn-primary {
            background: #0ea5e9;
            color: #fff;
        }
        .btn-secondary {
            background: #334155;
            color: #e2e8f0;
        }
        .btn-danger {
            background: #475569;
            color: #e2e8f0;
            font-size: 0.9375rem;
        }
        form { width: 100%; }
        form .btn { width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="code">404</div>
        <h1>Página no encontrada</h1>
        <p>La página solicitada no existe o fue movida.</p>
        <div class="actions">
            <a href="{{ $primaryUrl }}" class="btn btn-primary">{{ $primaryLabel }}</a>
            <button type="button" class="btn btn-secondary" onclick="window.location.reload()">Recargar</button>
            @if($isAuth && Route::has('logout'))
            <form method="POST" action="{{ route('logout') }}" class="actions">
                @csrf
                <button type="submit" class="btn btn-danger">Cerrar sesión</button>
            </form>
            @endif
        </div>
    </div>
</body>
</html>
