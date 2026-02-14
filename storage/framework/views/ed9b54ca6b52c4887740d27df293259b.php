<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex">
    <?php if (isset($component)) { $__componentOriginal43bea641c2438270a49238c99ecefb58 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal43bea641c2438270a49238c99ecefb58 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.usuario.sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('usuario.sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal43bea641c2438270a49238c99ecefb58)): ?>
<?php $attributes = $__attributesOriginal43bea641c2438270a49238c99ecefb58; ?>
<?php unset($__attributesOriginal43bea641c2438270a49238c99ecefb58); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal43bea641c2438270a49238c99ecefb58)): ?>
<?php $component = $__componentOriginal43bea641c2438270a49238c99ecefb58; ?>
<?php unset($__componentOriginal43bea641c2438270a49238c99ecefb58); ?>
<?php endif; ?>
    <div class="flex-1 lg:ml-64">
        <?php if (isset($component)) { $__componentOriginal68a91bba458c966ce613394dc1ac6078 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal68a91bba458c966ce613394dc1ac6078 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.usuario.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('usuario.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal68a91bba458c966ce613394dc1ac6078)): ?>
<?php $attributes = $__attributesOriginal68a91bba458c966ce613394dc1ac6078; ?>
<?php unset($__attributesOriginal68a91bba458c966ce613394dc1ac6078); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal68a91bba458c966ce613394dc1ac6078)): ?>
<?php $component = $__componentOriginal68a91bba458c966ce613394dc1ac6078; ?>
<?php unset($__componentOriginal68a91bba458c966ce613394dc1ac6078); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal34cad1f9e1defdf87895216072b487b3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal34cad1f9e1defdf87895216072b487b3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.usuario.mobile-menu','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('usuario.mobile-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal34cad1f9e1defdf87895216072b487b3)): ?>
<?php $attributes = $__attributesOriginal34cad1f9e1defdf87895216072b487b3; ?>
<?php unset($__attributesOriginal34cad1f9e1defdf87895216072b487b3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal34cad1f9e1defdf87895216072b487b3)): ?>
<?php $component = $__componentOriginal34cad1f9e1defdf87895216072b487b3; ?>
<?php unset($__componentOriginal34cad1f9e1defdf87895216072b487b3); ?>
<?php endif; ?>

        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="mb-3 px-3 py-2 rounded-2 border font-medium" style="font-size: 0.8125rem; background: rgba(15, 118, 110, 0.08); border-color: rgba(15, 118, 110, 0.25); color: #0f766e;">
                <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="mb-3 px-3 py-2 rounded-2 border font-medium" style="font-size: 0.8125rem; background: rgba(220, 38, 38, 0.08); border-color: rgba(220, 38, 38, 0.25); color: #b91c1c;">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Info usuario (solo móvil) -->
            <div class="lg:hidden bg-white rounded-2 border p-3 mb-4 shadow-sm portal-card">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: var(--app-sidebar-active); color: var(--app-sidebar-active-text); font-size: 0.85rem;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="mb-0 fw-semibold text-dark" style="font-size: 0.9rem;"><?php echo e(auth()->user()->nombre_completo); ?></p>
                        <p class="mb-0 text-secondary" style="font-size: 0.75rem;"><?php echo e(auth()->user()->nombre_perfil); ?> · <?php echo e(auth()->user()->nombre_sucursal); ?></p>
                    </div>
                </div>
            </div>

            <!-- Título (escritorio) -->
            <div class="d-none d-lg-block mb-3">
                <p class="mb-0 small text-secondary text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Panel de control</p>
                <h1 class="h5 mb-0 mt-0 fw-bold" style="color: var(--app-text); font-size: 1.1rem;">Resumen</h1>
            </div>

            <!-- Secciones -->
            <div class="row g-3">
                <!-- Supervisión -->
                <div class="col-12 col-lg-6">
                    <div class="portal-card h-100 rounded-3 overflow-hidden border shadow-sm">
                        <div class="px-3 py-2 border-bottom d-flex align-items-center gap-2" style="background: var(--app-surface); border-color: var(--app-border) !important;">
                            <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0 portal-card-icon" style="width: 32px; height: 32px; background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);">
                                <i class="fas fa-clipboard-check" style="font-size: 0.8rem;"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold portal-card-title" style="color: var(--app-text); font-size: 0.95rem;">Supervisión</h2>
                                <p class="mb-0 portal-card-subtitle text-secondary">Aprobaciones y revisión</p>
                            </div>
                        </div>
                        <div class="p-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                                ['route' => 'admin.documentos.index', 'title' => 'Aprobar Documentos', 'desc' => 'Revisar documentos personales', 'icon' => 'fa-file-alt'],
                                ['route' => 'admin.novedades.index', 'title' => 'Todas las Novedades', 'desc' => 'Historial completo de novedades', 'icon' => 'fa-bell'],
                                ['route' => 'admin.reportes-especiales.index', 'title' => 'Todos los Reportes', 'desc' => 'Historial completo de reportes', 'icon' => 'fa-chart-bar'],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route($item['route'])); ?>" class="portal-link d-flex align-items-center gap-2 px-2 py-2 rounded-2 text-decoration-none">
                                <span class="portal-link-icon rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; background: var(--app-surface); color: var(--app-text-muted); font-size: 0.75rem;">
                                    <i class="fas <?php echo e($item['icon']); ?>"></i>
                                </span>
                                <div class="min-w-0 flex-grow-1">
                                    <span class="fw-semibold d-block portal-link-title" style="color: var(--app-text); font-size: 0.875rem;"><?php echo e($item['title']); ?></span>
                                    <span class="portal-link-desc text-secondary" style="font-size: 0.75rem;"><?php echo e($item['desc']); ?></span>
                                </div>
                                <i class="fas fa-chevron-right flex-shrink-0 text-secondary" style="font-size: 0.7rem;"></i>
                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Administración -->
                <div class="col-12 col-lg-6">
                    <div class="portal-card h-100 rounded-3 overflow-hidden border shadow-sm">
                        <div class="px-3 py-2 border-bottom d-flex align-items-center gap-2" style="background: var(--app-surface); border-color: var(--app-border) !important;">
                            <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0 portal-card-icon" style="width: 32px; height: 32px; background: var(--app-sidebar-active); color: var(--app-sidebar-active-text);">
                                <i class="fas fa-cog" style="font-size: 0.8rem;"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold portal-card-title" style="color: var(--app-text); font-size: 0.95rem;">Administración</h2>
                                <p class="mb-0 portal-card-subtitle text-secondary">Herramientas administrativas</p>
                            </div>
                        </div>
                        <div class="p-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                                ['route' => 'admin.usuarios.index', 'title' => 'Gestión de Usuarios', 'desc' => 'Ver, crear y editar usuarios', 'icon' => 'fa-users'],
                                ['route' => 'admin.reportes-diarios', 'title' => 'Reportes Diarios', 'desc' => 'Ver reportes del sistema', 'icon' => 'fa-calendar-day'],
                                ['route' => 'admin.reporte-sucursal', 'title' => 'Reporte por Sucursal', 'desc' => 'Análisis por ubicación', 'icon' => 'fa-building'],
                                ['route' => 'admin.dispositivos.index', 'title' => 'Gestión de Dispositivos', 'desc' => 'Control de navegadores permitidos', 'icon' => 'fa-laptop'],
                                ['route' => 'admin.ubicaciones.index', 'title' => 'Gestión de Ubicaciones', 'desc' => 'Zonas de acceso permitidas', 'icon' => 'fa-map-marker-alt'],
                                ['route' => 'admin.sectores.index', 'title' => 'Gestión de Sectores', 'desc' => 'Configurar zonas', 'icon' => 'fa-th-large'],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route($item['route'])); ?>" class="portal-link d-flex align-items-center gap-2 px-2 py-2 rounded-2 text-decoration-none">
                                <span class="portal-link-icon rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; background: var(--app-surface); color: var(--app-text-muted); font-size: 0.75rem;">
                                    <i class="fas <?php echo e($item['icon']); ?>"></i>
                                </span>
                                <div class="min-w-0 flex-grow-1">
                                    <span class="fw-semibold d-block portal-link-title" style="color: var(--app-text); font-size: 0.875rem;"><?php echo e($item['title']); ?></span>
                                    <span class="portal-link-desc text-secondary" style="font-size: 0.75rem;"><?php echo e($item['desc']); ?></span>
                                </div>
                                <i class="fas fa-chevron-right flex-shrink-0 text-secondary" style="font-size: 0.7rem;"></i>
                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.usuario', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/administrador/index.blade.php ENDPATH**/ ?>