<!-- Menú Móvil (solo visible en móvil) - Mismo estilo que sidebar con Bootstrap + Font Awesome -->
<div id="sideMenu" class="d-lg-none position-fixed top-0 end-0 h-100 z-50 overflow-hidden transition-all duration-300 shadow" style="width: 256px; background: var(--app-sidebar); transform: translateX(100%);">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between" style="border-color: rgba(148,163,184,0.2) !important;">
        <h2 class="mb-0 small fw-bold text-white">Menú</h2>
        <button type="button" onclick="toggleMenu()" class="btn btn-link p-2 text-decoration-none rounded" style="color: var(--app-sidebar-text);">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="flex-grow-1 overflow-auto py-2 px-2">
        <ul class="nav flex-column">
            @php
                $homeRoute = auth()->user()->esAdministrador()
                    ? 'administrador.index'
                    : (auth()->user()->esSupervisor() ? 'supervisor.index' : 'usuario.index');
                $isHomeActive = request()->routeIs($homeRoute);
            @endphp
            <li class="nav-item">
                <a href="{{ route($homeRoute) }}" class="nav-link d-flex align-items-center py-2 rounded {{ $isHomeActive ? 'active' : '' }}" style="{{ $isHomeActive ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #cbd5e1;' }}">
                    <i class="fas fa-home me-2" style="width: 1.25rem;"></i><span>Inicio</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('ingresos.index') }}" class="nav-link d-flex align-items-center py-2 rounded {{ request()->routeIs('ingresos.*') || request()->routeIs('blacklist.*') ? 'active' : '' }}" style="{{ request()->routeIs('ingresos.*') || request()->routeIs('blacklist.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #cbd5e1;' }}">
                    <i class="fas fa-qrcode me-2" style="width: 1.25rem;"></i><span>Control de acceso</span>
                </a>
            </li>
            @if(auth()->user()->esUsuario() || auth()->user()->esSupervisorUsuario())
            <li class="nav-item">
                <a href="{{ route('usuario.perfil.index') }}" class="nav-link d-flex align-items-center py-2 rounded {{ request()->routeIs('usuario.perfil.*') ? 'active' : '' }}" style="{{ request()->routeIs('usuario.perfil.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #cbd5e1;' }}">
                    <i class="fas fa-user me-2" style="width: 1.25rem;"></i><span>Mi perfil</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 rounded collapsed" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#mobile-mis-reportes">
                    <i class="fas fa-chevron-right me-2 mobile-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-exclamation-triangle me-2" style="width: 1.25rem;"></i><span>Mis reportes</span>
                </a>
                <div class="collapse {{ request()->routeIs('usuario.reportes.*') ? 'show' : '' }}" id="mobile-mis-reportes">
                    <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                        <li class="nav-item"><a href="{{ route('usuario.reportes.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('usuario.reportes.*') ? 'active' : '' }}" style="{{ request()->routeIs('usuario.reportes.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Ver mis reportes</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 rounded collapsed" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#mobile-mis-documentos">
                    <i class="fas fa-chevron-right me-2 mobile-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-file-alt me-2" style="width: 1.25rem;"></i><span>Mis documentos</span>
                </a>
                <div class="collapse {{ request()->routeIs('usuario.documentos.*') ? 'show' : '' }}" id="mobile-mis-documentos">
                    <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                        <li class="nav-item"><a href="{{ route('usuario.documentos.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('usuario.documentos.*') ? 'active' : '' }}" style="{{ request()->routeIs('usuario.documentos.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Ver mis documentos</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="{{ route('usuario.ronda.index') }}" class="nav-link d-flex align-items-center py-2 rounded {{ request()->routeIs('usuario.ronda.*') ? 'active' : '' }}" style="{{ request()->routeIs('usuario.ronda.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #cbd5e1;' }}">
                    <i class="fas fa-route me-2" style="width: 1.25rem;"></i><span>Rondas QR</span>
                </a>
            </li>
            @endif
            @if(auth()->user()->esSupervisor() || auth()->user()->esAdministrador())
            <li class="nav-item"><hr class="my-2" style="border-color: rgba(148,163,184,0.3);"></li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 rounded collapsed" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#mobile-reportes-admin">
                    <i class="fas fa-chevron-right me-2 mobile-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-chart-bar me-2" style="width: 1.25rem;"></i><span>Reportes y estadísticas</span>
                </a>
                <div class="collapse {{ request()->routeIs(['admin.reportes-especiales.*', 'admin.rondas.reporte', 'admin.reportes-diarios', 'admin.reporte-sucursal']) ? 'show' : '' }}" id="mobile-reportes-admin">
                    <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                        <li class="nav-item"><a href="{{ route('admin.reportes-especiales.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.reportes-especiales.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.reportes-especiales.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Todos los reportes</a></li>
                        <li class="nav-item"><a href="{{ route('admin.rondas.reporte') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.rondas.reporte') ? 'active' : '' }}" style="{{ request()->routeIs('admin.rondas.reporte') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Reporte escaneos QR</a></li>
                        @if(auth()->user()->esAdministrador())
                        <li class="nav-item"><a href="{{ route('admin.reportes-diarios') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.reportes-diarios') ? 'active' : '' }}" style="{{ request()->routeIs('admin.reportes-diarios') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Reportes diarios</a></li>
                        <li class="nav-item"><a href="{{ route('admin.reporte-sucursal') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.reporte-sucursal') ? 'active' : '' }}" style="{{ request()->routeIs('admin.reporte-sucursal') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Reporte por sucursal</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 rounded collapsed" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#mobile-usuarios-docs">
                    <i class="fas fa-chevron-right me-2 mobile-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-users me-2" style="width: 1.25rem;"></i><span>Usuarios y supervisión</span>
                </a>
                <div class="collapse {{ request()->routeIs(['admin.usuarios.*', 'admin.documentos.*', 'admin.novedades.*', 'supervisor.documentos.*']) ? 'show' : '' }}" id="mobile-usuarios-docs">
                    <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                        @if(auth()->user()->esAdministrador())
                        <li class="nav-item"><a href="{{ route('admin.usuarios.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.usuarios.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Usuarios</a></li>
                        @endif
                        <li class="nav-item"><a href="{{ auth()->user()->esAdministrador() ? route('admin.documentos.index') : route('supervisor.documentos.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs(['admin.documentos.*', 'supervisor.documentos.*']) ? 'active' : '' }}" style="{{ request()->routeIs(['admin.documentos.*', 'supervisor.documentos.*']) ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Aprobar documentos</a></li>
                        @if(auth()->user()->esAdministrador())
                        <li class="nav-item"><a href="{{ route('admin.novedades.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.novedades.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.novedades.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Novedades</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
            @if(auth()->user()->esAdministrador())
            <li class="nav-item"><hr class="my-2" style="border-color: rgba(148,163,184,0.3);"></li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 rounded collapsed" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#mobile-gestion">
                    <i class="fas fa-chevron-right me-2 mobile-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-cog me-2" style="width: 1.25rem;"></i><span>Gestión</span>
                </a>
                <div class="collapse {{ request()->routeIs(['admin.dispositivos.*', 'admin.ubicaciones.*', 'admin.sectores.*', 'admin.rondas.*']) && !request()->routeIs('admin.rondas.reporte') ? 'show' : '' }}" id="mobile-gestion">
                    <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                        <li class="nav-item"><a href="{{ route('admin.dispositivos.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.dispositivos.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.dispositivos.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Dispositivos</a></li>
                        <li class="nav-item"><a href="{{ route('admin.ubicaciones.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.ubicaciones.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.ubicaciones.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Ubicaciones</a></li>
                        <li class="nav-item"><a href="{{ route('admin.sectores.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.sectores.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.sectores.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Sectores</a></li>
                        <li class="nav-item"><a href="{{ route('admin.rondas.index') }}" class="nav-link py-1 small rounded {{ request()->routeIs('admin.rondas.index') || request()->routeIs('admin.rondas.show') || request()->routeIs('admin.rondas.create') || request()->routeIs('admin.rondas.edit') ? 'active' : '' }}" style="{{ request()->routeIs('admin.rondas.index') || request()->routeIs('admin.rondas.show') || request()->routeIs('admin.rondas.create') || request()->routeIs('admin.rondas.edit') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;' }}">Puntos de ronda (QR)</a></li>
                    </ul>
                </div>
            </li>
            @endif
            <li class="nav-item"><hr class="my-2" style="border-color: rgba(148,163,184,0.3);"></li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link d-flex align-items-center py-2 rounded w-100 text-start border-0 bg-transparent" style="color: #94a3b8;">
                        <i class="fas fa-sign-out-alt me-2" style="width: 1.25rem;"></i><span>Cerrar sesión</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
<div id="menuOverlay" class="d-lg-none position-fixed top-0 start-0 w-100 h-100 z-40 bg-black bg-opacity-50" style="display: none;" onclick="toggleMenu()"></div>
<style>
#sideMenu .nav-link { text-decoration: none; }
#sideMenu .nav-link:hover { background: rgba(51,65,85,0.5) !important; color: #f1f5f9 !important; }
#sideMenu .nav-link[data-bs-toggle="collapse"].collapsed .mobile-chevron { transform: none; }
#sideMenu .nav-link[data-bs-toggle="collapse"]:not(.collapsed) .mobile-chevron { transform: rotate(90deg); }
#sideMenu.show { transform: translateX(0) !important; }
/* Evitar que el texto desaparezca al abrir acordeones */
#sideMenu [data-bs-toggle="collapse"].nav-link,
#sideMenu [data-bs-toggle="collapse"].nav-link span,
#sideMenu [data-bs-toggle="collapse"].nav-link i.fas { color: #cbd5e1 !important; }
#sideMenu [data-bs-toggle="collapse"].nav-link:hover,
#sideMenu [data-bs-toggle="collapse"].nav-link:hover span,
#sideMenu [data-bs-toggle="collapse"].nav-link:hover i.fas { color: #f1f5f9 !important; }
#sideMenu .collapse .nav-link { color: #94a3b8 !important; }
#sideMenu .collapse .nav-link.active { color: var(--app-sidebar-active-text) !important; }
#sideMenu .collapse .nav-link:hover { color: #f1f5f9 !important; }
#sideMenu .collapse.show { visibility: visible !important; }
#sideMenu .collapse .nav-link,
#sideMenu .collapse .nav-link span { opacity: 1 !important; visibility: visible !important; }
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
