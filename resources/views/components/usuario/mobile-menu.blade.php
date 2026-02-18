<!-- Menú Móvil (solo visible en móvil) - Abre por la derecha -->
<div id="sideMenu" class="d-lg-none position-fixed top-0 end-0 h-100 z-50 overflow-hidden transition-all duration-300 shadow side-menu-mobile" style="width: 280px; max-width: 85vw; transform: translateX(100%);">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between side-menu-header" style="border-color: rgba(226,232,240,0.25) !important;">
        <h2 class="mb-0 fw-bold" style="color: #f8fafc; font-size: 1.1rem;">Menú</h2>
        <button type="button" onclick="toggleMenu()" class="btn btn-link p-2 text-decoration-none rounded" style="color: #f1f5f9;">
            <i class="fas fa-times" style="font-size: 1.25rem;"></i>
        </button>
    </div>
    <div class="flex-grow-1 overflow-auto py-2 px-2 side-menu-body">
        <ul class="nav flex-column side-menu-nav">
            @php
                $user = auth()->user();
                $homeRoute = $user->esAdministrador() ? 'administrador.index' : ($user->esSupervisor() ? 'supervisor.index' : 'usuario.index');
                $isHomeActive = request()->routeIs($homeRoute);
            @endphp
            <li class="nav-item">
                <a href="{{ route($homeRoute) }}" class="nav-link side-menu-link d-flex align-items-center py-2 rounded {{ $isHomeActive ? 'active' : '' }}" style="{{ $isHomeActive ? 'color: #5eead4;' : 'color: #e2e8f0;' }}">
                    <i class="fas fa-home me-2" style="width: 1.25rem;"></i><span>Inicio</span>
                </a>
            </li>
            @if($user->puedeVerControlAcceso())
            <li class="nav-item">
                <a href="{{ route('ingresos.index') }}" class="nav-link side-menu-link d-flex align-items-center py-2 rounded {{ request()->routeIs('ingresos.*') || request()->routeIs('blacklist.*') ? 'active' : '' }}" style="{{ request()->routeIs('ingresos.*') || request()->routeIs('blacklist.*') ? 'color: #5eead4;' : 'color: #e2e8f0;' }}">
                    <i class="fas fa-qrcode me-2" style="width: 1.25rem;"></i><span>Control de acceso</span>
                </a>
            </li>
            @endif
            <li class="nav-item">
                <a href="{{ route('usuario.perfil.index') }}" class="nav-link side-menu-link d-flex align-items-center py-2 rounded {{ request()->routeIs('usuario.perfil.*') ? 'active' : '' }}" style="{{ request()->routeIs('usuario.perfil.*') ? 'color: #5eead4;' : 'color: #e2e8f0;' }}">
                    <i class="fas fa-user me-2" style="width: 1.25rem;"></i><span>Mi perfil</span>
                </a>
            </li>
            <li class="nav-item" id="menu-item-instalar-app">
                <button type="button" class="nav-link side-menu-link d-flex align-items-center py-2 rounded w-100 text-start border-0 bg-transparent" style="color: #e2e8f0;" id="btn-instalar-app" onclick="toggleMenu(); if (typeof triggerPwaInstall === 'function') triggerPwaInstall();">
                    <i class="fas fa-download me-2" style="width: 1.25rem;"></i><span>Instalar aplicación</span>
                </button>
            </li>
            @if($user->puedeVerMisReportes())
            <li class="nav-item">
                <a class="nav-link side-menu-link side-menu-toggle d-flex align-items-center py-2 rounded collapsed" style="color: #e2e8f0;" data-bs-toggle="collapse" data-bs-target="#mobile-mis-reportes">
                    <i class="fas fa-chevron-right me-2 mobile-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-exclamation-triangle me-2" style="width: 1.25rem;"></i><span>Mis reportes</span>
                </a>
                <div class="collapse {{ request()->routeIs('usuario.reportes.*') ? 'show' : '' }}" id="mobile-mis-reportes">
                    <ul class="nav flex-column ms-4 ps-2 border-start" style="border-color: rgba(226,232,240,0.2) !important;">
                        <li class="nav-item"><a href="{{ route('usuario.reportes.index') }}" class="nav-link side-menu-sublink py-2 small rounded {{ request()->routeIs('usuario.reportes.*') ? 'active' : '' }}" style="{{ request()->routeIs('usuario.reportes.*') ? 'color: #5eead4;' : 'color: #cbd5e1;' }}">Ver mis reportes</a></li>
                    </ul>
                </div>
            </li>
            @if(config('app.show_documentos_guardias'))
            <li class="nav-item">
                <a class="nav-link side-menu-link side-menu-toggle d-flex align-items-center py-2 rounded collapsed" style="color: #e2e8f0;" data-bs-toggle="collapse" data-bs-target="#mobile-mis-documentos">
                    <i class="fas fa-chevron-right me-2 mobile-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-file-alt me-2" style="width: 1.25rem;"></i><span>Mis documentos</span>
                </a>
                <div class="collapse {{ request()->routeIs('usuario.documentos.*') ? 'show' : '' }}" id="mobile-mis-documentos">
                    <ul class="nav flex-column ms-4 ps-2 border-start" style="border-color: rgba(226,232,240,0.2) !important;">
                        <li class="nav-item"><a href="{{ route('usuario.documentos.index') }}" class="nav-link side-menu-sublink py-2 small rounded {{ request()->routeIs('usuario.documentos.*') ? 'active' : '' }}" style="{{ request()->routeIs('usuario.documentos.*') ? 'color: #5eead4;' : 'color: #cbd5e1;' }}">Ver mis documentos</a></li>
                    </ul>
                </div>
            </li>
            @endif
            <li class="nav-item">
                <a href="{{ route('usuario.ronda.index') }}" class="nav-link side-menu-link d-flex align-items-center py-2 rounded {{ request()->routeIs('usuario.ronda.*') ? 'active' : '' }}" style="{{ request()->routeIs('usuario.ronda.*') ? 'color: #5eead4;' : 'color: #e2e8f0;' }}">
                    <i class="fas fa-route me-2" style="width: 1.25rem;"></i><span>Rondas QR</span>
                </a>
            </li>
            @endif
            @if($user->puedeVerReporteSucursal() || $user->puedeVerReportesEstadisticasCompletos())
            <li class="nav-item"><hr class="my-2" style="border-color: rgba(226,232,240,0.25);"></li>
            <li class="nav-item">
                <a class="nav-link side-menu-link side-menu-toggle d-flex align-items-center py-2 rounded collapsed" style="color: #e2e8f0;" data-bs-toggle="collapse" data-bs-target="#mobile-reportes-admin">
                    <i class="fas fa-chevron-right me-2 mobile-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-chart-bar me-2" style="width: 1.25rem;"></i><span>Reportes y estadísticas</span>
                </a>
                <div class="collapse {{ request()->routeIs(['admin.reportes-especiales.*', 'admin.rondas.reporte', 'admin.reportes-diarios', 'admin.reporte-sucursal']) ? 'show' : '' }}" id="mobile-reportes-admin">
                    <ul class="nav flex-column ms-4 ps-2 border-start" style="border-color: rgba(226,232,240,0.2) !important;">
                        @if($user->puedeVerReportesEstadisticasCompletos())
                        <li class="nav-item"><a href="{{ route('admin.reportes-especiales.index') }}" class="nav-link side-menu-sublink py-2 small rounded {{ request()->routeIs('admin.reportes-especiales.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.reportes-especiales.*') ? 'color: #5eead4;' : 'color: #cbd5e1;' }}">Todos los reportes</a></li>
                        <li class="nav-item"><a href="{{ route('admin.rondas.reporte') }}" class="nav-link side-menu-sublink py-2 small rounded {{ request()->routeIs('admin.rondas.reporte') ? 'active' : '' }}" style="{{ request()->routeIs('admin.rondas.reporte') ? 'color: #5eead4;' : 'color: #cbd5e1;' }}">Reporte escaneos QR</a></li>
                        @endif
                        @if($user->puedeVerReporteSucursal())
                        <li class="nav-item"><a href="{{ route('admin.reporte-sucursal') }}" class="nav-link side-menu-sublink py-2 small rounded {{ request()->routeIs('admin.reporte-sucursal') ? 'active' : '' }}" style="{{ request()->routeIs('admin.reporte-sucursal') ? 'color: #5eead4;' : 'color: #cbd5e1;' }}">Reporte por sucursal</a></li>
                        @endif
                        @if($user->puedeVerReportesDiarios())
                        <li class="nav-item"><a href="{{ route('admin.reportes-diarios') }}" class="nav-link side-menu-sublink py-2 small rounded {{ request()->routeIs('admin.reportes-diarios') ? 'active' : '' }}" style="{{ request()->routeIs('admin.reportes-diarios') ? 'color: #5eead4;' : 'color: #cbd5e1;' }}">Reportes diarios</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
            @if($user->puedeVerSupervision())
            <li class="nav-item">
                <a class="nav-link side-menu-link side-menu-toggle d-flex align-items-center py-2 rounded collapsed" style="color: #e2e8f0;" data-bs-toggle="collapse" data-bs-target="#mobile-usuarios-docs">
                    <i class="fas fa-chevron-right me-2 mobile-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-users me-2" style="width: 1.25rem;"></i><span>Supervisión</span>
                </a>
                <div class="collapse {{ request()->routeIs(['admin.usuarios.*', 'admin.documentos.*', 'admin.novedades.*', 'supervisor.documentos.*', 'reportes-especiales.*']) ? 'show' : '' }}" id="mobile-usuarios-docs">
                    <ul class="nav flex-column ms-4 ps-2 border-start" style="border-color: rgba(226,232,240,0.2) !important;">
                        @if($user->esAdministrador())
                        <li class="nav-item"><a href="{{ route('admin.usuarios.index') }}" class="nav-link side-menu-sublink py-2 small rounded {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.usuarios.*') ? 'color: #5eead4;' : 'color: #cbd5e1;' }}">Usuarios</a></li>
                        @endif
                        @if(config('app.show_documentos_guardias'))
                        <li class="nav-item"><a href="{{ $user->esAdministrador() ? route('admin.documentos.index') : route('supervisor.documentos.index') }}" class="nav-link side-menu-sublink py-2 small rounded {{ request()->routeIs(['admin.documentos.*', 'supervisor.documentos.*']) ? 'active' : '' }}" style="{{ request()->routeIs(['admin.documentos.*', 'supervisor.documentos.*']) ? 'color: #5eead4;' : 'color: #cbd5e1;' }}">Aprobar documentos</a></li>
                        @endif
                        <li class="nav-item"><a href="{{ route('admin.novedades.index') }}" class="nav-link side-menu-sublink py-2 small rounded {{ request()->routeIs('admin.novedades.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.novedades.*') ? 'color: #5eead4;' : 'color: #cbd5e1;' }}">Novedades</a></li>
                        <li class="nav-item"><a href="{{ $user->esAdministrador() ? route('admin.reportes-especiales.index') : route('reportes-especiales.index') }}" class="nav-link side-menu-sublink py-2 small rounded {{ request()->routeIs(['admin.reportes-especiales.*', 'reportes-especiales.*']) ? 'active' : '' }}" style="{{ request()->routeIs(['admin.reportes-especiales.*', 'reportes-especiales.*']) ? 'color: #5eead4;' : 'color: #cbd5e1;' }}">Todos los reportes</a></li>
                    </ul>
                </div>
            </li>
            @endif
            @if($user->puedeVerGestion())
            <li class="nav-item"><hr class="my-2" style="border-color: rgba(226,232,240,0.25);"></li>
            <li class="nav-item">
                <a class="nav-link side-menu-link side-menu-toggle d-flex align-items-center py-2 rounded collapsed" style="color: #e2e8f0;" data-bs-toggle="collapse" data-bs-target="#mobile-gestion">
                    <i class="fas fa-chevron-right me-2 mobile-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-cog me-2" style="width: 1.25rem;"></i><span>Gestión</span>
                </a>
                <div class="collapse {{ request()->routeIs(['admin.dispositivos.*', 'admin.ubicaciones.*', 'admin.sectores.*', 'admin.rondas.*']) && !request()->routeIs('admin.rondas.reporte') ? 'show' : '' }}" id="mobile-gestion">
                    <ul class="nav flex-column ms-4 ps-2 border-start" style="border-color: rgba(226,232,240,0.2) !important;">
                        <li class="nav-item"><a href="{{ route('admin.dispositivos.index') }}" class="nav-link side-menu-sublink py-2 small rounded {{ request()->routeIs('admin.dispositivos.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.dispositivos.*') ? 'color: #5eead4;' : 'color: #cbd5e1;' }}">Dispositivos</a></li>
                        <li class="nav-item"><a href="{{ route('admin.ubicaciones.index') }}" class="nav-link side-menu-sublink py-2 small rounded {{ request()->routeIs('admin.ubicaciones.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.ubicaciones.*') ? 'color: #5eead4;' : 'color: #cbd5e1;' }}">Ubicaciones</a></li>
                        <li class="nav-item"><a href="{{ route('admin.sectores.index') }}" class="nav-link side-menu-sublink py-2 small rounded {{ request()->routeIs('admin.sectores.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.sectores.*') ? 'color: #5eead4;' : 'color: #cbd5e1;' }}">Sectores</a></li>
                        <li class="nav-item"><a href="{{ route('admin.rondas.index') }}" class="nav-link side-menu-sublink py-2 small rounded {{ request()->routeIs('admin.rondas.index') || request()->routeIs('admin.rondas.show') || request()->routeIs('admin.rondas.create') || request()->routeIs('admin.rondas.edit') ? 'active' : '' }}" style="{{ request()->routeIs('admin.rondas.index') || request()->routeIs('admin.rondas.show') || request()->routeIs('admin.rondas.create') || request()->routeIs('admin.rondas.edit') ? 'color: #5eead4;' : 'color: #cbd5e1;' }}">Puntos de ronda (QR)</a></li>
                    </ul>
                </div>
            </li>
            @endif
            <li class="nav-item"><hr class="my-2" style="border-color: rgba(226,232,240,0.25);"></li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link side-menu-link d-flex align-items-center py-2 rounded w-100 text-start border-0 bg-transparent" style="color: #cbd5e1;">
                        <i class="fas fa-sign-out-alt me-2" style="width: 1.25rem;"></i><span>Cerrar sesión</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
<div id="menuOverlay" class="d-lg-none position-fixed top-0 start-0 w-100 h-100 z-40 bg-black bg-opacity-50" style="display: none; left: 0;" onclick="toggleMenu()"></div>
<style>
/* Menú móvil: fondo oscuro forzado (evitar que Bootstrap o temas pinten de claro) */
#sideMenu.side-menu-mobile,
#sideMenu.side-menu-mobile .side-menu-header,
#sideMenu.side-menu-mobile .side-menu-body,
#sideMenu.side-menu-mobile .side-menu-nav,
#sideMenu.side-menu-mobile ul.nav { background: #0f172a !important; }
#sideMenu.side-menu-mobile .nav-item { background: transparent !important; }
/* Contraste alto: texto claro sobre fondo oscuro */
.side-menu-mobile .nav-link { text-decoration: none !important; }
.side-menu-mobile .side-menu-link,
.side-menu-mobile .side-menu-link span,
.side-menu-mobile .side-menu-link i.fas { color: #e2e8f0 !important; }
.side-menu-mobile .side-menu-link:hover,
.side-menu-mobile .side-menu-link:hover span,
.side-menu-mobile .side-menu-link:hover i.fas { background: transparent !important; color: #f8fafc !important; }
.side-menu-mobile .side-menu-link.active,
.side-menu-mobile .side-menu-link.active span,
.side-menu-mobile .side-menu-link.active i.fas { color: #5eead4 !important; background: transparent !important; }
.side-menu-mobile .side-menu-sublink,
.side-menu-mobile .side-menu-sublink span { color: #cbd5e1 !important; font-size: 0.9rem !important; }
.side-menu-mobile .side-menu-sublink:hover { color: #f1f5f9 !important; background: transparent !important; }
.side-menu-mobile .side-menu-sublink.active { color: #5eead4 !important; background: transparent !important; }
.side-menu-mobile .side-menu-toggle,
.side-menu-mobile .side-menu-toggle span,
.side-menu-mobile .side-menu-toggle i.fas { color: #e2e8f0 !important; }
.side-menu-mobile .side-menu-toggle:hover,
.side-menu-mobile .side-menu-toggle[aria-expanded="true"] { color: #f8fafc !important; background: transparent !important; }
.side-menu-mobile .side-menu-toggle[data-bs-toggle="collapse"].collapsed .mobile-chevron { transform: none; }
.side-menu-mobile .side-menu-toggle[data-bs-toggle="collapse"]:not(.collapsed) .mobile-chevron { transform: rotate(90deg); }
#sideMenu.show { transform: translateX(0) !important; }
.side-menu-mobile .collapse.show { visibility: visible !important; }
.side-menu-mobile .collapse .nav-link { opacity: 1 !important; visibility: visible !important; }
/* Sin fondo al hacer clic, tap, hover ni focus; solo color en activo */
.side-menu-mobile .nav-link:active,
.side-menu-mobile .nav-link:focus,
.side-menu-mobile .nav-link:hover { background: transparent !important; box-shadow: none !important; outline: none !important; }
.side-menu-mobile .nav-link { -webkit-tap-highlight-color: transparent; }
.side-menu-mobile .side-menu-link.active,
.side-menu-mobile .side-menu-sublink.active { -webkit-tap-highlight-color: transparent; }
</style>
<script>
function toggleMenu() {
    var menu = document.getElementById('sideMenu');
    var overlay = document.getElementById('menuOverlay');
    if (menu.classList.contains('show')) {
        menu.classList.remove('show');
        overlay.style.display = 'none';
    } else {
        menu.classList.add('show');
        overlay.style.display = 'block';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#sideMenu [data-bs-toggle="collapse"]').forEach(function(btn) {
        btn.addEventListener('click', function() { this.classList.toggle('collapsed', this.getAttribute('aria-expanded') !== 'true'); });
        if (btn.getAttribute('aria-expanded') === 'true') btn.classList.remove('collapsed');
    });
});
</script>
