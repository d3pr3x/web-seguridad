            @php
                $user = auth()->user();
                $homeRoute = $user->esAdministrador() ? 'administrador.index' : ($user->esSupervisor() ? 'supervisor.index' : 'usuario.index');
                $isHomeActive = request()->routeIs($homeRoute);
            @endphp
<aside class="d-none d-lg-flex flex-column position-fixed top-0 end-0 h-100 z-30 overflow-hidden" style="width: 256px; background: var(--app-sidebar);">
    <div class="p-3 border-bottom" style="border-color: rgba(148,163,184,0.2) !important;">
        <p class="mb-0 small fw-semibold text-white text-truncate" title="Portal {{ $user->nombre_perfil }}">Portal {{ $user->nombre_perfil }}</p>
    </div>
    <nav class="flex-grow-1 overflow-auto py-2">
        <ul class="nav flex-column px-2">
            <li class="nav-item">
                <a href="{{ route($homeRoute) }}" class="nav-link d-flex align-items-center py-2 rounded {{ $isHomeActive ? 'active' : '' }}" style="{{ $isHomeActive ? 'color: var(--app-sidebar-active-text);' : 'color: #cbd5e1;' }}">
                    <i class="fas fa-home me-2" style="width: 1.25rem;"></i>
                    <span>Inicio</span>
                </a>
            </li>
            @if($user->puedeVerControlAcceso())
            <li class="nav-item">
                <a href="{{ route('ingresos.index') }}" class="nav-link d-flex align-items-center py-2 rounded {{ request()->routeIs('ingresos.*') || request()->routeIs('blacklist.*') ? 'active' : '' }}" style="{{ request()->routeIs('ingresos.*') || request()->routeIs('blacklist.*') ? 'color: var(--app-sidebar-active-text);' : 'color: #cbd5e1;' }}">
                    <i class="fas fa-qrcode me-2" style="width: 1.25rem;"></i>
                    <span>Control de acceso</span>
                </a>
            </li>
            @endif
            <li class="nav-item">
                <a href="{{ route('usuario.perfil.index') }}" class="nav-link d-flex align-items-center py-2 rounded {{ request()->routeIs('usuario.perfil.*') ? 'active' : '' }}" style="{{ request()->routeIs('usuario.perfil.*') ? 'color: var(--app-sidebar-active-text);' : 'color: #cbd5e1;' }}">
                    <i class="fas fa-user me-2" style="width: 1.25rem;"></i>
                    <span>Mi perfil</span>
                </a>
            </li>
            @if($user->puedeVerMisReportes())
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 rounded {{ request()->routeIs('usuario.reportes.*') ? '' : 'collapsed' }}" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#sidebar-mis-reportes" aria-expanded="{{ request()->routeIs('usuario.reportes.*') ? 'true' : 'false' }}">
                    <i class="fas fa-chevron-right me-2 sidebar-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-exclamation-triangle me-2" style="width: 1.25rem;"></i>
                    <span>Mis reportes</span>
                </a>
                <div class="collapse {{ request()->routeIs('usuario.reportes.*') ? 'show' : '' }}" id="sidebar-mis-reportes">
                    <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                        <li class="nav-item"><a href="{{ route('usuario.reportes.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('usuario.reportes.*') ? 'active' : '' }}" style="{{ request()->routeIs('usuario.reportes.*') ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Ver mis reportes</a></li>
                    </ul>
                </div>
            </li>
            @endif
            @if($user->puedeVerMisReportes() && config('app.show_documentos_guardias'))
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 rounded {{ request()->routeIs('usuario.documentos.*') ? '' : 'collapsed' }}" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#sidebar-mis-documentos" aria-expanded="{{ request()->routeIs('usuario.documentos.*') ? 'true' : 'false' }}">
                    <i class="fas fa-chevron-right me-2 sidebar-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-file-alt me-2" style="width: 1.25rem;"></i>
                    <span>Mis documentos</span>
                </a>
                <div class="collapse {{ request()->routeIs('usuario.documentos.*') ? 'show' : '' }}" id="sidebar-mis-documentos">
                    <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                        <li class="nav-item"><a href="{{ route('usuario.documentos.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('usuario.documentos.*') ? 'active' : '' }}" style="{{ request()->routeIs('usuario.documentos.*') ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Ver mis documentos</a></li>
                    </ul>
                </div>
            </li>
            @endif
            @if($user->puedeVerRondasQR())
            <li class="nav-item">
                <a href="{{ route('usuario.ronda.index') }}" class="nav-link d-flex align-items-center py-2 rounded {{ request()->routeIs('usuario.ronda.*') ? 'active' : '' }}" style="{{ request()->routeIs('usuario.ronda.*') ? 'color: var(--app-sidebar-active-text);' : 'color: #cbd5e1;' }}">
                    <i class="fas fa-route me-2" style="width: 1.25rem;"></i>
                    <span>Rondas QR</span>
                </a>
            </li>
            @endif

            @if($user->puedeVerReporteSucursal() || $user->puedeVerReportesEstadisticasCompletos())
            <li class="nav-item"><hr class="my-2" style="border-color: rgba(148,163,184,0.3);"></li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 rounded {{ request()->routeIs(['admin.reportes-especiales.*', 'admin.rondas.reporte', 'admin.reportes-diarios', 'admin.reporte-sucursal']) ? '' : 'collapsed' }}" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#sidebar-reportes-admin" aria-expanded="{{ request()->routeIs(['admin.reportes-especiales.*', 'admin.rondas.reporte', 'admin.reportes-diarios', 'admin.reporte-sucursal']) ? 'true' : 'false' }}">
                    <i class="fas fa-chevron-right me-2 sidebar-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-chart-bar me-2" style="width: 1.25rem;"></i>
                    <span>Reportes y estadísticas</span>
                </a>
                <div class="collapse {{ request()->routeIs(['admin.reportes-especiales.*', 'admin.rondas.reporte', 'admin.reportes-diarios', 'admin.reporte-sucursal']) ? 'show' : '' }}" id="sidebar-reportes-admin">
                    <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                        @if($user->puedeVerReportesEstadisticasCompletos())
                        <li class="nav-item"><a href="{{ route('admin.reportes-especiales.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.reportes-especiales.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.reportes-especiales.*') ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Todos los reportes</a></li>
                        <li class="nav-item"><a href="{{ route('admin.rondas.reporte') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.rondas.reporte') ? 'active' : '' }}" style="{{ request()->routeIs('admin.rondas.reporte') ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Reporte escaneos QR</a></li>
                        @endif
                        @if($user->puedeVerReporteSucursal())
                        <li class="nav-item"><a href="{{ route('admin.reporte-sucursal') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.reporte-sucursal') ? 'active' : '' }}" style="{{ request()->routeIs('admin.reporte-sucursal') ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Reporte por sucursal</a></li>
                        @endif
                        @if($user->puedeVerReportesDiarios())
                        <li class="nav-item"><a href="{{ route('admin.reportes-diarios') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.reportes-diarios') ? 'active' : '' }}" style="{{ request()->routeIs('admin.reportes-diarios') ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Reportes diarios</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
            @if($user->puedeVerSupervision())
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 rounded {{ request()->routeIs(['admin.usuarios.*', 'admin.documentos.*', 'admin.novedades.*', 'supervisor.documentos.*']) ? '' : 'collapsed' }}" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#sidebar-usuarios-docs" aria-expanded="{{ request()->routeIs(['admin.usuarios.*', 'admin.documentos.*', 'admin.novedades.*', 'supervisor.documentos.*']) ? 'true' : 'false' }}">
                    <i class="fas fa-chevron-right me-2 sidebar-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-users me-2" style="width: 1.25rem;"></i>
                    <span>Supervisión</span>
                </a>
                <div class="collapse {{ request()->routeIs(['admin.usuarios.*', 'admin.documentos.*', 'admin.novedades.*', 'supervisor.documentos.*', 'reportes-especiales.*']) ? 'show' : '' }}" id="sidebar-usuarios-docs">
                    <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                        @if($user->esAdministrador())
                        <li class="nav-item"><a href="{{ route('admin.usuarios.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.usuarios.*') ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Usuarios</a></li>
                        @endif
                        @if(config('app.show_documentos_guardias'))
                        <li class="nav-item"><a href="{{ $user->esAdministrador() ? route('admin.documentos.index') : route('supervisor.documentos.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs(['admin.documentos.*', 'supervisor.documentos.*']) ? 'active' : '' }}" style="{{ request()->routeIs(['admin.documentos.*', 'supervisor.documentos.*']) ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Aprobar documentos</a></li>
                        @endif
                        <li class="nav-item"><a href="{{ route('admin.novedades.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.novedades.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.novedades.*') ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Novedades</a></li>
                        @if($user->esAdministrador())
                        <li class="nav-item"><a href="{{ route('admin.grupos-incidentes.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.grupos-incidentes.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.grupos-incidentes.*') ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Grupos de incidentes</a></li>
                        @endif
                        <li class="nav-item"><a href="{{ $user->esAdministrador() ? route('admin.reportes-especiales.index') : route('reportes-especiales.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs(['admin.reportes-especiales.*', 'reportes-especiales.*']) ? 'active' : '' }}" style="{{ request()->routeIs(['admin.reportes-especiales.*', 'reportes-especiales.*']) ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Todos los reportes</a></li>
                    </ul>
                </div>
            </li>
            @endif

            @if($user->puedeVerGestion())
            <li class="nav-item"><hr class="my-2" style="border-color: rgba(148,163,184,0.3);"></li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 rounded {{ request()->routeIs(['admin.dispositivos.*', 'admin.ubicaciones.*', 'admin.sectores.*', 'admin.rondas.*']) && !request()->routeIs('admin.rondas.reporte') ? '' : 'collapsed' }}" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#sidebar-gestion" aria-expanded="{{ request()->routeIs(['admin.dispositivos.*', 'admin.ubicaciones.*', 'admin.sectores.*', 'admin.rondas.*']) && !request()->routeIs('admin.rondas.reporte') ? 'true' : 'false' }}">
                    <i class="fas fa-chevron-right me-2 sidebar-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-cog me-2" style="width: 1.25rem;"></i>
                    <span>Gestión</span>
                </a>
                <div class="collapse {{ request()->routeIs(['admin.dispositivos.*', 'admin.ubicaciones.*', 'admin.sectores.*', 'admin.rondas.*']) && !request()->routeIs('admin.rondas.reporte') ? 'show' : '' }}" id="sidebar-gestion">
                    <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                        <li class="nav-item"><a href="{{ route('admin.dispositivos.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.dispositivos.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.dispositivos.*') ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Dispositivos</a></li>
                        <li class="nav-item"><a href="{{ route('admin.ubicaciones.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.ubicaciones.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.ubicaciones.*') ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Ubicaciones</a></li>
                        <li class="nav-item"><a href="{{ route('admin.sectores.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.sectores.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.sectores.*') ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Sectores</a></li>
                        <li class="nav-item"><a href="{{ route('admin.rondas.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.rondas.index') || request()->routeIs('admin.rondas.show') || request()->routeIs('admin.rondas.create') || request()->routeIs('admin.rondas.edit') ? 'active' : '' }}" style="{{ request()->routeIs('admin.rondas.index') || request()->routeIs('admin.rondas.show') || request()->routeIs('admin.rondas.create') || request()->routeIs('admin.rondas.edit') ? 'color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Puntos de ronda (QR)</a></li>
                    </ul>
                </div>
            </li>
            @endif

            <li class="nav-item"><hr class="my-2" style="border-color: rgba(148,163,184,0.3);"></li>
            <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                    <button type="submit" class="nav-link d-flex align-items-center py-2 rounded w-100 text-start border-0 bg-transparent" style="color: #94a3b8;">
                        <i class="fas fa-sign-out-alt me-2" style="width: 1.25rem;"></i>
                        <span>Cerrar sesión</span>
                </button>
            </form>
            </li>
        </ul>
    </nav>
</aside>
<style>
aside .nav-link { text-decoration: none; }
aside .nav-link:hover { background: rgba(51,65,85,0.5); color: #f1f5f9 !important; }
aside .nav-link[data-bs-toggle="collapse"]:not(.collapsed) .sidebar-chevron { transform: rotate(90deg); }
aside .nav-link[aria-expanded="true"] .sidebar-chevron { transform: rotate(90deg); }
/* Evitar que el texto desaparezca al abrir acordeones: forzar color siempre visible */
aside [data-bs-toggle="collapse"].nav-link,
aside [data-bs-toggle="collapse"].nav-link span,
aside [data-bs-toggle="collapse"].nav-link i.fas { color: #cbd5e1 !important; }
aside [data-bs-toggle="collapse"].nav-link:hover,
aside [data-bs-toggle="collapse"].nav-link:hover span,
aside [data-bs-toggle="collapse"].nav-link:hover i.fas { color: #f1f5f9 !important; }
aside .collapse .nav-link { color: #94a3b8 !important; }
aside .collapse .nav-link.active { color: var(--app-sidebar-active-text) !important; }
aside .collapse .nav-link:hover { color: #f1f5f9 !important; }
aside .collapse.show { visibility: visible !important; }
aside .collapse .collapse .nav-link,
aside .collapse .nav-link span { opacity: 1 !important; visibility: visible !important; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('aside [data-bs-toggle="collapse"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            this.classList.toggle('collapsed', !this.getAttribute('aria-expanded') || this.getAttribute('aria-expanded') === 'false');
        });
        if (btn.getAttribute('aria-expanded') === 'true') btn.classList.remove('collapsed');
    });
});
</script>
