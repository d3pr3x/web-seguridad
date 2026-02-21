<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Seguridad')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
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
            --app-nav-bg: #0f172a;
            --app-nav-text: #f1f5f9;
        }
        body {
            font-family: var(--app-font);
            background: var(--app-surface);
            color: var(--app-text);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: -0.02em;
        }
        .card {
            transition: box-shadow 0.2s ease, transform 0.2s ease;
            border: 1px solid var(--app-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        .btn-task {
            min-height: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: white;
            border-radius: 12px;
            margin-bottom: 20px;
            padding: 20px;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-task:hover {
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 118, 110, 0.25);
        }
        .btn-task i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .stats-card {
            background: linear-gradient(135deg, var(--app-primary) 0%, #115e59 100%);
            color: white;
            border: none;
        }
        .footer {
            background: var(--app-card);
            border-top: 1px solid var(--app-border);
            padding: 24px 0;
            margin-top: 48px;
        }
        .footer p {
            color: var(--app-text-muted);
            font-size: 0.875rem;
        }
        .form-control, .input-group-text {
            border-radius: 8px;
            border-color: var(--app-border);
        }
        .form-control:focus {
            border-color: var(--app-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15);
        }
        .btn-primary {
            background: var(--app-primary);
            border-color: var(--app-primary);
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: var(--app-primary-hover);
            border-color: var(--app-primary-hover);
        }
        .btn-outline-app {
            border: 1px solid var(--app-primary);
            color: var(--app-primary);
            background: transparent;
        }
        .btn-outline-app:hover {
            background: rgba(15, 118, 110, 0.08);
            color: var(--app-primary);
            border-color: var(--app-primary);
        }
        .dropdown-menu {
            border-radius: 10px;
            border: 1px solid var(--app-border);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        .dropdown-item {
            border-radius: 6px;
            margin: 2px 6px;
        }
        .dropdown-item:hover {
            background: var(--app-surface);
        }
        .alert {
            border-radius: 10px;
            border: 1px solid transparent;
        }
        
        @media (max-width: 768px) {
            .btn-task {
                min-height: 100px;
                padding: 15px;
            }
            .btn-task i {
                font-size: 1.5rem;
                margin-bottom: 8px;
            }
            .btn-task h5 {
                font-size: 0.9rem;
            }
            .btn-task small {
                font-size: 0.75rem;
            }
            .navbar-brand {
                font-size: 1rem;
            }
            .container {
                padding-left: 12px;
                padding-right: 12px;
            }
            .card-body {
                padding: 1rem;
            }
            .table-responsive {
                font-size: 0.875rem;
                -webkit-overflow-scrolling: touch;
            }
            .btn-group-sm .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
        
        @media (max-width: 576px) {
            .btn-task {
                min-height: 80px;
                padding: 10px;
            }
            .btn-task i {
                font-size: 1.25rem;
                margin-bottom: 5px;
            }
            .btn-task h5 {
                font-size: 0.8rem;
                margin-bottom: 2px;
            }
            .btn-task small {
                font-size: 0.7rem;
            }
            .stats-card .card-body {
                padding: 1rem 0.5rem;
            }
            .stats-card h4 {
                font-size: 1.5rem;
            }
            .stats-card p {
                font-size: 0.8rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="overflow-x-hidden">
    @auth
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--app-nav-bg);">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-shield-alt me-2"></i>Sistema de Seguridad
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">
                            <i class="fas fa-home me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-tasks me-1"></i>Acciones
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('acciones.index') }}">
                                <i class="fas fa-list me-1"></i>Ver Todas las Acciones
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('acciones.create', ['tipo' => 'inicio_servicio']) }}">
                                <i class="fas fa-play-circle me-1"></i>Inicio del Servicio
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('acciones.create', ['tipo' => 'rondas']) }}">
                                <i class="fas fa-route me-1"></i>Rondas
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('acciones.create', ['tipo' => 'constancias']) }}">
                                <i class="fas fa-file-signature me-1"></i>Constancias
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('acciones.create', ['tipo' => 'concurrencia_autoridades']) }}">
                                <i class="fas fa-user-shield me-1"></i>Concurrencia de autoridades
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('acciones.create', ['tipo' => 'concurrencia_servicios']) }}">
                                <i class="fas fa-ambulance me-1"></i>Concurrencia de Servicios
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('acciones.create', ['tipo' => 'entrega_servicio']) }}">
                                <i class="fas fa-stop-circle me-1"></i>Entrega del Servicio
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-exclamation-triangle me-1"></i>Reportes
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('reportes-especiales.index') }}">
                                <i class="fas fa-list me-1"></i>Ver Todos los Reportes
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('reportes-especiales.create', ['tipo' => 'incidentes']) }}">
                                <i class="fas fa-exclamation-circle me-1"></i>Incidentes
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('reportes-especiales.create', ['tipo' => 'denuncia']) }}">
                                <i class="fas fa-file-alt me-1"></i>Denuncia
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('reportes-especiales.create', ['tipo' => 'detenido']) }}">
                                <i class="fas fa-user-lock me-1"></i>Detenido
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('reportes-especiales.create', ['tipo' => 'accion_sospechosa']) }}">
                                <i class="fas fa-eye me-1"></i>Acción Sospechosa
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reportes.index') }}">
                            <i class="fas fa-file-alt me-1"></i>Mis Tareas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('informes.index') }}">
                            <i class="fas fa-file-pdf me-1"></i>Mis Informes
                        </a>
                    </li>
                    @if(module_enabled('calculo_sueldos'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dias-trabajados.index') }}">
                            <i class="fas fa-calendar me-1"></i>Días Trabajados
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ingresos.index') }}">
                            <i class="fas fa-qrcode me-1"></i>Control de acceso
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-1"></i>Administración
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.reportes-diarios') }}">
                                <i class="fas fa-chart-line me-1"></i>Reportes Diarios
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.reporte-sucursal') }}">
                                <i class="fas fa-building me-1"></i>Reporte por Sucursal
                            </a></li>
                            @if(module_enabled('calculo_sueldos'))
                            <li><a class="dropdown-item" href="{{ route('admin.calculo-sueldos') }}">
                                <i class="fas fa-calculator me-1"></i>Cálculo de Sueldos
                            </a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.sectores.index') }}">
                                <i class="fas fa-map-marked-alt me-1"></i>Gestión de Sectores
                            </a></li>
                            @if(module_enabled('documentos_guardias'))
                            <li><a class="dropdown-item" href="{{ route('admin.documentos.index') }}">
                                <i class="fas fa-file-alt me-1"></i>Documentos Personales
                            </a></li>
                            @endif
                        </ul>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>{{ auth()->user()->nombre_completo }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}">
                                <i class="fas fa-user-circle me-1"></i>Mi Perfil
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @endauth

    <main class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Sistema de Seguridad. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- RUT Formatter -->
    <script src="{{ asset('js/rut-formatter.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
