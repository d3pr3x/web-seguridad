            <?php
                $homeRoute = auth()->user()->esAdministrador()
                    ? 'administrador.index'
        : (auth()->user()->esSupervisor() ? 'supervisor.index' : 'usuario.index');
                $isHomeActive = request()->routeIs($homeRoute);
            ?>
<aside class="d-none d-lg-flex flex-column position-fixed top-0 start-0 h-100 z-30 overflow-hidden" style="width: 256px; background: var(--app-sidebar);">
    <div class="p-3 border-bottom" style="border-color: rgba(148,163,184,0.2) !important;">
        <p class="mb-0 small fw-semibold text-white text-truncate" title="Portal <?php echo e(auth()->user()->nombre_perfil); ?>">Portal <?php echo e(auth()->user()->nombre_perfil); ?></p>
        <p class="mb-0 small text-truncate" style="color: var(--app-sidebar-text);" title="<?php echo e(auth()->user()->nombre_completo); ?>"><?php echo e(auth()->user()->nombre_completo); ?></p>
    </div>
    <nav class="flex-grow-1 overflow-auto py-2">
        <ul class="nav flex-column px-2">
            <li class="nav-item">
                <a href="<?php echo e(route($homeRoute)); ?>" class="nav-link d-flex align-items-center py-2 rounded <?php echo e($isHomeActive ? 'active' : ''); ?>" style="<?php echo e($isHomeActive ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #cbd5e1;'); ?>">
                    <i class="fas fa-home me-2" style="width: 1.25rem;"></i>
                    <span>Inicio</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(route('ingresos.index')); ?>" class="nav-link d-flex align-items-center py-2 rounded <?php echo e(request()->routeIs('ingresos.*') || request()->routeIs('blacklist.*') ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs('ingresos.*') || request()->routeIs('blacklist.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #cbd5e1;'); ?>">
                    <i class="fas fa-qrcode me-2" style="width: 1.25rem;"></i>
                    <span>Control de acceso</span>
                </a>
            </li>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->esUsuario() || auth()->user()->esSupervisorUsuario()): ?>
            <li class="nav-item">
                <a href="<?php echo e(route('usuario.perfil.index')); ?>" class="nav-link d-flex align-items-center py-2 rounded <?php echo e(request()->routeIs('usuario.perfil.*') ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs('usuario.perfil.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #cbd5e1;'); ?>">
                    <i class="fas fa-user me-2" style="width: 1.25rem;"></i>
                    <span>Mi perfil</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 rounded <?php echo e(request()->routeIs('usuario.reportes.*') ? '' : 'collapsed'); ?>" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#sidebar-mis-reportes" aria-expanded="<?php echo e(request()->routeIs('usuario.reportes.*') ? 'true' : 'false'); ?>">
                    <i class="fas fa-chevron-right me-2 sidebar-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-exclamation-triangle me-2" style="width: 1.25rem;"></i>
                    <span>Mis reportes</span>
                </a>
                <div class="collapse <?php echo e(request()->routeIs('usuario.reportes.*') ? 'show' : ''); ?>" id="sidebar-mis-reportes">
                    <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                        <li class="nav-item"><a href="<?php echo e(route('usuario.reportes.index')); ?>" class="nav-link py-1 small rounded <?php echo e(request()->routeIs('usuario.reportes.*') ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs('usuario.reportes.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;'); ?>">Ver mis reportes</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 rounded <?php echo e(request()->routeIs('usuario.documentos.*') ? '' : 'collapsed'); ?>" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#sidebar-mis-documentos" aria-expanded="<?php echo e(request()->routeIs('usuario.documentos.*') ? 'true' : 'false'); ?>">
                    <i class="fas fa-chevron-right me-2 sidebar-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-file-alt me-2" style="width: 1.25rem;"></i>
                    <span>Mis documentos</span>
                </a>
                <div class="collapse <?php echo e(request()->routeIs('usuario.documentos.*') ? 'show' : ''); ?>" id="sidebar-mis-documentos">
                    <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                        <li class="nav-item"><a href="<?php echo e(route('usuario.documentos.index')); ?>" class="nav-link py-1 small rounded <?php echo e(request()->routeIs('usuario.documentos.*') ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs('usuario.documentos.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;'); ?>">Ver mis documentos</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(route('usuario.ronda.index')); ?>" class="nav-link d-flex align-items-center py-2 rounded <?php echo e(request()->routeIs('usuario.ronda.*') ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs('usuario.ronda.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #cbd5e1;'); ?>">
                    <i class="fas fa-route me-2" style="width: 1.25rem;"></i>
                    <span>Rondas QR</span>
                </a>
            </li>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->esSupervisor() || auth()->user()->esAdministrador()): ?>
            <li class="nav-item"><hr class="my-2" style="border-color: rgba(148,163,184,0.3);"></li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 rounded <?php echo e(request()->routeIs(['admin.reportes-especiales.*', 'admin.rondas.reporte', 'admin.reportes-diarios', 'admin.reporte-sucursal']) ? '' : 'collapsed'); ?>" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#sidebar-reportes-admin" aria-expanded="<?php echo e(request()->routeIs(['admin.reportes-especiales.*', 'admin.rondas.reporte', 'admin.reportes-diarios', 'admin.reporte-sucursal']) ? 'true' : 'false'); ?>">
                    <i class="fas fa-chevron-right me-2 sidebar-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-chart-bar me-2" style="width: 1.25rem;"></i>
                    <span>Reportes y estadísticas</span>
                </a>
                <div class="collapse <?php echo e(request()->routeIs(['admin.reportes-especiales.*', 'admin.rondas.reporte', 'admin.reportes-diarios', 'admin.reporte-sucursal']) ? 'show' : ''); ?>" id="sidebar-reportes-admin">
                    <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                        <li class="nav-item"><a href="<?php echo e(route('admin.reportes-especiales.index')); ?>" class="nav-link py-1 small rounded <?php echo e(request()->routeIs('admin.reportes-especiales.*') ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs('admin.reportes-especiales.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;'); ?>">Todos los reportes</a></li>
                        <li class="nav-item"><a href="<?php echo e(route('admin.rondas.reporte')); ?>" class="nav-link py-1 small rounded <?php echo e(request()->routeIs('admin.rondas.reporte') ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs('admin.rondas.reporte') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;'); ?>">Reporte escaneos QR</a></li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->esAdministrador()): ?>
                        <li class="nav-item"><a href="<?php echo e(route('admin.reportes-diarios')); ?>" class="nav-link py-1 small rounded <?php echo e(request()->routeIs('admin.reportes-diarios') ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs('admin.reportes-diarios') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;'); ?>">Reportes diarios</a></li>
                        <li class="nav-item"><a href="<?php echo e(route('admin.reporte-sucursal')); ?>" class="nav-link py-1 small rounded <?php echo e(request()->routeIs('admin.reporte-sucursal') ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs('admin.reporte-sucursal') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;'); ?>">Reporte por sucursal</a></li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 rounded <?php echo e(request()->routeIs(['admin.usuarios.*', 'admin.documentos.*', 'admin.novedades.*', 'supervisor.documentos.*']) ? '' : 'collapsed'); ?>" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#sidebar-usuarios-docs" aria-expanded="<?php echo e(request()->routeIs(['admin.usuarios.*', 'admin.documentos.*', 'admin.novedades.*', 'supervisor.documentos.*']) ? 'true' : 'false'); ?>">
                    <i class="fas fa-chevron-right me-2 sidebar-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-users me-2" style="width: 1.25rem;"></i>
                    <span>Usuarios y supervisión</span>
                </a>
                <div class="collapse <?php echo e(request()->routeIs(['admin.usuarios.*', 'admin.documentos.*', 'admin.novedades.*', 'supervisor.documentos.*']) ? 'show' : ''); ?>" id="sidebar-usuarios-docs">
                    <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->esAdministrador()): ?>
                        <li class="nav-item"><a href="<?php echo e(route('admin.usuarios.index')); ?>" class="nav-link py-1 small rounded <?php echo e(request()->routeIs('admin.usuarios.*') ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs('admin.usuarios.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;'); ?>">Usuarios</a></li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <li class="nav-item"><a href="<?php echo e(auth()->user()->esAdministrador() ? route('admin.documentos.index') : route('supervisor.documentos.index')); ?>" class="nav-link py-1 small rounded <?php echo e(request()->routeIs(['admin.documentos.*', 'supervisor.documentos.*']) ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs(['admin.documentos.*', 'supervisor.documentos.*']) ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;'); ?>">Aprobar documentos</a></li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->esAdministrador()): ?>
                        <li class="nav-item"><a href="<?php echo e(route('admin.novedades.index')); ?>" class="nav-link py-1 small rounded <?php echo e(request()->routeIs('admin.novedades.*') ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs('admin.novedades.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;'); ?>">Novedades</a></li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
            </li>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->esAdministrador()): ?>
            <li class="nav-item"><hr class="my-2" style="border-color: rgba(148,163,184,0.3);"></li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center py-2 rounded <?php echo e(request()->routeIs(['admin.dispositivos.*', 'admin.ubicaciones.*', 'admin.sectores.*', 'admin.rondas.*']) && !request()->routeIs('admin.rondas.reporte') ? '' : 'collapsed'); ?>" style="color: #cbd5e1;" data-bs-toggle="collapse" data-bs-target="#sidebar-gestion" aria-expanded="<?php echo e(request()->routeIs(['admin.dispositivos.*', 'admin.ubicaciones.*', 'admin.sectores.*', 'admin.rondas.*']) && !request()->routeIs('admin.rondas.reporte') ? 'true' : 'false'); ?>">
                    <i class="fas fa-chevron-right me-2 sidebar-chevron" style="width: 1.25rem; transition: transform 0.2s;"></i>
                    <i class="fas fa-cog me-2" style="width: 1.25rem;"></i>
                    <span>Gestión</span>
                </a>
                <div class="collapse <?php echo e(request()->routeIs(['admin.dispositivos.*', 'admin.ubicaciones.*', 'admin.sectores.*', 'admin.rondas.*']) && !request()->routeIs('admin.rondas.reporte') ? 'show' : ''); ?>" id="sidebar-gestion">
                    <ul class="nav flex-column ms-4 ps-2 border-start border-secondary">
                        <li class="nav-item"><a href="<?php echo e(route('admin.dispositivos.index')); ?>" class="nav-link py-1 small rounded <?php echo e(request()->routeIs('admin.dispositivos.*') ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs('admin.dispositivos.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;'); ?>">Dispositivos</a></li>
                        <li class="nav-item"><a href="<?php echo e(route('admin.ubicaciones.index')); ?>" class="nav-link py-1 small rounded <?php echo e(request()->routeIs('admin.ubicaciones.*') ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs('admin.ubicaciones.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;'); ?>">Ubicaciones</a></li>
                        <li class="nav-item"><a href="<?php echo e(route('admin.sectores.index')); ?>" class="nav-link py-1 small rounded <?php echo e(request()->routeIs('admin.sectores.*') ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs('admin.sectores.*') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;'); ?>">Sectores</a></li>
                        <li class="nav-item"><a href="<?php echo e(route('admin.rondas.index')); ?>" class="nav-link py-1 small rounded <?php echo e(request()->routeIs('admin.rondas.index') || request()->routeIs('admin.rondas.show') || request()->routeIs('admin.rondas.create') || request()->routeIs('admin.rondas.edit') ? 'active' : ''); ?>" style="<?php echo e(request()->routeIs('admin.rondas.index') || request()->routeIs('admin.rondas.show') || request()->routeIs('admin.rondas.create') || request()->routeIs('admin.rondas.edit') ? 'background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);' : 'color: #94a3b8;'); ?>">Puntos de ronda (QR)</a></li>
                    </ul>
                </div>
            </li>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <li class="nav-item"><hr class="my-2" style="border-color: rgba(148,163,184,0.3);"></li>
            <li class="nav-item">
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
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
aside .collapse .nav-link,
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
<?php /**PATH C:\Users\UrraServer\Documents\Docker\server-apache\web-seguridad\resources\views/components/usuario/sidebar.blade.php ENDPATH**/ ?>