<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Iniciar sesión') — Sistema de Seguridad</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --app-font: 'DM Sans', system-ui, sans-serif;
            --app-primary: #0f766e;
            --app-primary-hover: #0d9488;
            --app-primary-light: rgba(15, 118, 110, 0.12);
            --app-card: #ffffff;
            --app-border: #e5dfd6;
            --app-text: #1e293b;
            --app-text-muted: #64748b;
            --login-bg-start: #0f172a;
            --login-bg-end: #1e293b;
        }
        * { box-sizing: border-box; }
        body {
            font-family: var(--app-font);
            min-height: 100vh;
            margin: 0;
            color: var(--app-text);
            background: linear-gradient(160deg, var(--login-bg-start) 0%, var(--login-bg-end) 50%, #334155 100%);
            background-attachment: fixed;
        }
        .guest-wrap {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .guest-brand { text-align: center; margin-bottom: 1.5rem; }
        .guest-brand-icon {
            width: 72px; height: 72px;
            border-radius: 18px;
            background: rgba(255,255,255,0.12);
            display: inline-flex; align-items: center; justify-content: center;
            margin-bottom: 0.75rem;
        }
        .guest-brand-icon i,
.guest-brand-icon svg { color: #5eead4; }
.guest-brand-icon svg { width: 2rem; height: 2rem; }
        .guest-brand h1 { font-size: 1.35rem; font-weight: 700; color: #f1f5f9; letter-spacing: -0.02em; margin: 0; }
        .guest-brand p { font-size: 0.875rem; color: #94a3b8; margin: 0.25rem 0 0 0; }
        .guest-card {
            width: 100%; max-width: 420px;
            background: var(--app-card);
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35);
            border: 1px solid var(--app-border);
            overflow: hidden;
        }
        .guest-card .card-body { padding: 2rem 1.75rem; }
        .guest-footer { margin-top: 1.5rem; text-align: center; font-size: 0.8rem; color: #94a3b8; }

        /* Estilos autocontenidos para que el login se vea bien aunque no cargue Bootstrap/Font Awesome */
        .form-label {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--app-text);
        }
        .form-control {
            display: block;
            width: 100%;
            padding: 0.6rem 0.9rem;
            font-size: 1rem;
            font-family: inherit;
            line-height: 1.5;
            color: var(--app-text);
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .form-control:focus {
            outline: 0;
            border-color: var(--app-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.2);
        }
        .form-control::placeholder { color: #adb5bd; }
        .input-group {
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            width: 100%;
        }
        .input-group .input-group-text {
            border-right: 0 !important;
        }
        .input-group .form-control {
            border-left: 0 !important;
        }
        .input-group .input-group-text,
        .input-group .form-control {
            box-shadow: none;
        }
        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0.6rem 0.9rem;
            font-size: 1rem;
            color: var(--app-text-muted);
            background: #f1f5f9;
            border: 1px solid #dee2e6;
            border-right: 0;
            border-radius: 12px 0 0 12px;
        }
        .input-group-lg .form-control {
            padding: 0.7rem 1rem;
            font-size: 1rem;
            border-radius: 0 12px 12px 0;
        }
        .input-group-lg .input-group-text {
            padding: 0.7rem 1rem;
            border-radius: 12px 0 0 12px;
        }
        .input-group .form-control {
            flex: 1 1 auto;
            width: 1%;
            min-width: 0;
            border-radius: 0 12px 12px 0;
            border-left: 0;
        }
        .input-group .form-control:focus { border-left: 0 !important; box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.2); }
        .input-group .form-control.is-invalid { border-left: 0 !important; }
        .mb-1 { margin-bottom: 0.25rem; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .mt-1 { margin-top: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-3 { margin-top: 1rem; }
        .pt-3 { padding-top: 1rem; }
        .border-top { border-top: 1px solid #e5e7eb; }
        .rounded-3 { border-radius: 12px; }
        .bg-light { background: #f8fafc; }
        .text-muted { color: var(--app-text-muted); }
        .small { font-size: 0.875rem; }
        .d-block { display: block; }
        .d-flex { display: flex; }
        .align-items-start { align-items: flex-start; }
        .align-items-center { align-items: center; }
        .justify-content-between { justify-content: space-between; }
        .flex-grow-1 { flex-grow: 1; }
        .text-break { word-break: break-word; }
        .invalid-feedback { font-size: 0.875rem; color: #dc3545; margin-top: 0.25rem; }
        .is-invalid { border-color: #dc3545 !important; }
        .invalid-feedback.d-block { display: block; }

        .btn {
            display: inline-block;
            font-weight: 500;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            font-family: inherit;
            border-radius: 10px;
            transition: color 0.15s ease, background 0.15s ease, border-color 0.15s ease;
        }
        .btn-primary {
            background: var(--app-primary);
            border-color: var(--app-primary);
            color: #fff;
            padding: 0.65rem 1.25rem;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: var(--app-primary-hover);
            border-color: var(--app-primary-hover);
            color: #fff;
        }
        .btn-primary:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }
        .btn-lg { padding: 0.75rem 1.5rem; font-size: 1.0625rem; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
        .fw-bold { font-weight: 700; }
        .btn-outline-app {
            border: 1px solid var(--app-primary);
            color: var(--app-primary);
            background: transparent;
            border-radius: 8px;
        }
        .btn-outline-app:hover {
            background: var(--app-primary-light);
            color: var(--app-primary);
            border-color: var(--app-primary);
        }
        .d-grid { display: grid; }
        .d-grid .btn { width: 100%; }

        .alert {
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 12px;
            font-size: 0.9375rem;
        }
        .alert-danger {
            color: #721c24;
            background: #f8d7da;
            border-color: #f5c6cb;
        }
        .alert-dismissible { padding-right: 3rem; }
        .alert .btn-close {
            position: absolute;
            top: 0.75rem;
            right: 1rem;
            padding: 0.25rem;
            background: transparent;
            border: 0;
            font-size: 1.25rem;
            line-height: 1;
            cursor: pointer;
            opacity: 0.5;
        }
        .alert .btn-close:hover { opacity: 1; }
        .position-relative { position: relative; }

        .device-box { transition: background 0.2s ease; }
        .device-box:hover { background: #f1f5f9 !important; }
        .cursor-pointer { cursor: pointer; }
        .card { background: var(--app-card); }
        .card-body { padding: 2rem 1.75rem; }
        .border { border: 1px solid #e5e7eb; }
        .border-0 { border: 0 !important; }
        .me-1 { margin-right: 0.25rem; }
        .me-2 { margin-right: 0.5rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .h5 { font-size: 1.25rem; }
        .text-success { color: #198754; }
        .text-danger { color: #dc3545; }
        .text-warning { color: #ffc107; }
        .fa-spin { animation: fa-spin 1s linear infinite; }
        @keyframes fa-spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Login input-group: una sola pieza; borde y focus en el contenedor (carga después de Bootstrap) */
        .login-input-group {
            display: flex !important;
            flex-wrap: nowrap !important;
            align-items: stretch !important;
            border: 1px solid var(--bs-border-color, #dee2e6);
            border-radius: 0.75rem;
            overflow: hidden;
            background: #fff;
        }
        .login-input-group > .input-group-text,
        .login-input-group > .form-control {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        .login-input-group > .input-group-text {
            border: 0 !important;
            background: var(--bs-tertiary-bg, #f8f9fa);
            display: flex;
            align-items: center;
        }
        .login-input-group > .form-control {
            border: 0 !important;
            box-shadow: none !important;
            flex: 1 1 auto !important;
            width: 1% !important;
            min-width: 0 !important;
        }
        .login-input-group:focus-within {
            border-color: var(--app-primary);
            box-shadow: 0 0 0 0.25rem rgba(15, 118, 110, 0.2);
        }
        .login-input-group .form-control.is-invalid {
            border: 0 !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="guest-wrap">
        <div class="guest-brand">
            <div class="guest-brand-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <h1>Sistema de Seguridad</h1>
            <p>@yield('subtitle', 'Acceso restringido')</p>
        </div>
        <div class="guest-card card border-0">
            <div class="card-body">
                @yield('content')
            </div>
        </div>
        <div class="guest-footer">
            &copy; {{ date('Y') }} Sistema de Seguridad
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
