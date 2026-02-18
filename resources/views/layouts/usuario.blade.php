<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f766e">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <title>@yield('title', 'Portal Usuario - Sistema de Seguridad')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <!-- Vite + Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Bootstrap (para vistas que aún usan card, btn, form-control, etc.) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --app-font: 'DM Sans', system-ui, sans-serif;
            --app-primary: #0f766e;
            --app-primary-hover: #0d9488;
            --app-surface: #f0ebe3;
            --app-card: #f9f6f1;
            --app-border: #e5dfd6;
            --app-text: #1e293b;
            --app-text-muted: #64748b;
            --app-sidebar: #0f172a;
            --app-sidebar-text: #94a3b8;
            --app-sidebar-active: rgba(15, 118, 110, 0.15);
            --app-sidebar-active-text: #0d9488;
        }
        body { font-family: var(--app-font); background: var(--app-surface) !important; }
        .portal-layout .bg-gray-100 { background: var(--app-surface) !important; }

        /* Área principal: mismo tono que el sistema (armonía con header y menú) */
        .portal-layout .min-h-screen { background: var(--app-surface); }
        .portal-layout div.flex-1,
        .portal-layout div[class*="flex-grow-1"] { background: var(--app-surface); min-height: 100vh; }

        /* Contenedor del contenido: sin caja (fondo beige continuo) */
        .portal-layout div.flex-1 > div.container,
        .portal-layout div[class*="flex-grow-1"] > div.container {
            background: transparent;
            border: none;
            border-radius: 0;
            box-shadow: none;
            padding: 1rem;
            margin: 0.75rem;
        }
        @media (min-width: 1024px) {
            .portal-layout div.flex-1 > div.container,
            .portal-layout div[class*="flex-grow-1"] > div.container { margin: 1rem; padding: 1.25rem; }
        }

        /* Alertas de sesión: paleta teal/slate */
        .portal-layout div.flex-1 .bg-green-100,
        .portal-layout div.flex-1 .bg-emerald-50,
        .portal-layout div.flex-1 [class*="emerald-50"] {
            background: rgba(15, 118, 110, 0.08) !important;
            border-color: rgba(15, 118, 110, 0.3) !important;
            color: #0f766e !important;
            border-radius: 10px;
        }
        .portal-layout div.flex-1 .bg-red-100,
        .portal-layout div.flex-1 .bg-red-50,
        .portal-layout div.flex-1 [class*="red-50"] {
            background: rgba(220, 38, 38, 0.08) !important;
            border-color: rgba(220, 38, 38, 0.3) !important;
            color: #b91c1c !important;
            border-radius: 10px;
        }

        /* Tarjetas: misma lógica de fondo para todas (crema, borde, sombra) */
        .portal-layout div.flex-1 .bg-white.rounded-lg,
        .portal-layout div.flex-1 .bg-white.rounded-xl,
        .portal-layout div.flex-1 .rounded-xl.border {
            background: var(--app-card) !important;
            border: 1px solid var(--app-border) !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }
        .portal-layout div.flex-1 .shadow-md { box-shadow: 0 2px 8px rgba(0,0,0,0.06); }

        /* Tablas dentro de cards: mismo fondo que las cards (evitar blanco/gris duro) */
        .portal-layout div.flex-1 table thead.bg-gray-50 {
            background: var(--app-border) !important;
        }
        .portal-layout div.flex-1 table tbody.bg-white {
            background: var(--app-card) !important;
        }
        .portal-layout div.flex-1 table tbody tr.hover\:bg-gray-50:hover,
        .portal-layout div.flex-1 table tbody tr:hover {
            background: rgba(0,0,0,0.03) !important;
        }
        .portal-layout div.flex-1 .divide-y.divide-gray-200 > * {
            border-color: var(--app-border) !important;
        }

        /* Bloques tipo “vacío” o informativo con bg-gray-50: tono coherente */
        .portal-layout div.flex-1 .bg-gray-50 {
            background: var(--app-card) !important;
        }

        /* Títulos de sección con recuadro: usar teal del sistema */
        .portal-layout div.flex-1 [style*="rgba(15, 118, 110")] {
            background: var(--app-sidebar-active) !important;
            color: var(--app-sidebar-active-text) !important;
        }

        /* Tarjetas del portal: aspecto moderno */
        .portal-layout .portal-card {
            background: var(--app-card);
            border: 1px solid var(--app-border);
        }
        .portal-layout .portal-card + .portal-card { margin-top: 0; }

        /* Cards vista inicial (Panel de control): alto más ajustado al texto */
        .portal-layout div.flex-1 .bg-white.rounded-lg:has(.bg-gradient-to-r) .bg-gradient-to-r {
            padding: 0.5rem 1rem;
        }
        .portal-layout div.flex-1 .bg-white.rounded-lg:has(.bg-gradient-to-r) .bg-gradient-to-r h2 {
            font-size: 1rem;
        }
        .portal-layout div.flex-1 .bg-white.rounded-lg:has(.bg-gradient-to-r) .bg-gradient-to-r p {
            margin-top: 0.15rem;
            font-size: 0.8rem;
        }
        .portal-layout div.flex-1 .bg-white.rounded-lg:has(.bg-gradient-to-r) .p-4.space-y-3 {
            padding: 0.5rem 1rem;
        }
        .portal-layout div.flex-1 .bg-white.rounded-lg:has(.bg-gradient-to-r) .space-y-3 > a > div[class*="border-l-4"] {
            padding: 0.4rem 0.75rem;
        }
        .portal-layout div.flex-1 .bg-white.rounded-lg:has(.bg-gradient-to-r) .space-y-3 h3 {
            font-size: 0.9rem;
        }
        .portal-layout div.flex-1 .bg-white.rounded-lg:has(.bg-gradient-to-r) .space-y-3 p.text-sm {
            font-size: 0.75rem;
        }

        /* Enlaces del contenido: sin subrayado, estilo refinado */
        .portal-layout div.flex-1 > div.container a,
        .portal-layout div[class*="flex-grow-1"] > div.container a {
            text-decoration: none;
            color: inherit;
        }
        .portal-layout .portal-link {
            display: flex;
            text-decoration: none;
            color: inherit;
            border: none;
            background: transparent;
        }
        .portal-layout .portal-link:hover {
            background: var(--app-sidebar-active) !important;
        }
        .portal-layout .portal-link:hover span,
        .portal-layout .portal-link:hover .fw-semibold {
            color: var(--app-sidebar-active-text) !important;
        }
        .portal-layout .portal-link:hover .text-secondary {
            color: var(--app-sidebar-active-text) !important;
            opacity: 0.9;
        }
        .portal-layout .portal-link:hover .fa-chevron-right {
            color: var(--app-sidebar-active-text) !important;
            transform: translateX(2px);
        }
        .portal-layout .portal-link .fa-chevron-right {
            transition: color 0.2s ease, transform 0.2s ease;
        }

        /* Contenido: tamaños y espaciado ordenados (menús/ítems no grandes) */
        .portal-layout .portal-card-title { font-size: 0.95rem !important; }
        .portal-layout .portal-card-subtitle { font-size: 0.75rem !important; }
        .portal-layout .portal-link-title { font-size: 0.875rem !important; }
        .portal-layout .portal-link-desc { font-size: 0.75rem !important; }
        .portal-layout .portal-link { min-height: 2.25rem; }
        .portal-layout .portal-link + .portal-link { margin-top: 0; }

        /* Responsive: evitar scroll horizontal y ajustar contenido en móvil */
        .portal-layout { overflow-x: hidden; }
        .portal-layout .flex-1 { min-width: 0; max-width: 100%; }
        @media (max-width: 768px) {
            .portal-layout div.flex-1 > div.container,
            .portal-layout div[class*="flex-grow-1"] > div.container {
                margin-left: 0.5rem;
                margin-right: 0.5rem;
                padding: 0.75rem 1rem;
                max-width: 100%;
            }
        }
        /* Tablas: scroll horizontal en móvil */
        .overflow-x-auto { -webkit-overflow-scrolling: touch; }
    </style>
    @stack('styles')
</head>
<body class="portal-layout overflow-x-hidden">
    @yield('content')

    @if (!request()->routeIs('login'))
    <script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('{{ asset('sw.js') }}').catch(function() {});
    }
    window._pwaInstallPrompt = null;
    window.addEventListener('beforeinstallprompt', function(e) { e.preventDefault(); window._pwaInstallPrompt = e; });
    window.triggerPwaInstall = function() {
        if (window._pwaInstallPrompt) {
            window._pwaInstallPrompt.prompt();
            window._pwaInstallPrompt.userChoice.then(function() { window._pwaInstallPrompt = null; });
        } else {
            alert('Para instalar la app: abra el menú del navegador (⋮) y elija "Añadir a la pantalla de inicio" o "Instalar aplicación". Así la cámara recordará el permiso.');
        }
    };
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('menu-item-instalar-app');
        if (el && (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone)) {
            el.style.display = 'none';
        }
    });
    </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

