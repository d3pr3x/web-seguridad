<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-100 flex">
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
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                <p class="font-medium"><i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?></p>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                <p class="font-medium"><i class="fas fa-exclamation-circle mr-2"></i><?php echo e(session('error')); ?></p>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Información de Usuario (solo móvil) -->
            <div class="lg:hidden bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="flex items-center">
                    <div class="bg-teal-100 rounded-full p-3 mr-4">
                        <i class="fas fa-user text-teal-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-lg font-bold text-gray-800"><?php echo e(auth()->user()->nombre_completo); ?></h2>
                        <p class="text-sm text-gray-600"><?php echo e(auth()->user()->nombre_perfil); ?></p>
                        <p class="text-sm text-gray-600"><?php echo e(auth()->user()->nombre_sucursal); ?></p>
                    </div>
                </div>
            </div>

            <!-- Título (escritorio) -->
            <div class="hidden lg:block mb-6">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-0">Panel de control</p>
                <h1 class="text-xl font-bold text-gray-800 mt-0">Resumen</h1>
            </div>

            <!-- Secciones -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Supervisión -->
                <div>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-teal-500 to-teal-600 p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-clipboard-check mr-2"></i>
                                Supervisión
                            </h2>
                            <p class="text-teal-100 text-sm mt-1">Aprobaciones y revisión</p>
                        </div>
                        <div class="p-4 space-y-3">
                            <a href="<?php echo e(route('admin.documentos.index')); ?>" class="block">
                                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-r-lg hover:bg-teal-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-teal-800">Aprobar Documentos</h3>
                                            <p class="text-sm text-teal-600">Revisar documentos personales</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-teal-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="<?php echo e(route('admin.novedades.index')); ?>" class="block">
                                <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-r-lg hover:bg-indigo-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-indigo-800">Todas las Novedades</h3>
                                            <p class="text-sm text-indigo-600">Historial completo de novedades</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-indigo-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="<?php echo e(route('admin.reportes-especiales.index')); ?>" class="block">
                                <div class="bg-pink-50 border-l-4 border-pink-500 p-4 rounded-r-lg hover:bg-pink-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-pink-800">Todos los Reportes</h3>
                                            <p class="text-sm text-pink-600">Historial completo de reportes</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-pink-500"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Administración -->
                <div>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-slate-600 to-slate-700 p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-cog mr-2"></i>
                                Administración
                            </h2>
                            <p class="text-slate-200 text-sm mt-1">Herramientas administrativas</p>
                        </div>
                        <div class="p-4 space-y-3">
                            <a href="<?php echo e(route('admin.usuarios.index')); ?>" class="block">
                                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg hover:bg-blue-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-blue-800">Gestión de Usuarios</h3>
                                            <p class="text-sm text-blue-600">Ver, crear y editar usuarios</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-blue-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="<?php echo e(route('admin.reportes-diarios')); ?>" class="block">
                                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg hover:bg-green-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-green-800">Reportes Diarios</h3>
                                            <p class="text-sm text-green-600">Ver reportes del sistema</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-green-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="<?php echo e(route('admin.reporte-sucursal')); ?>" class="block">
                                <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r-lg hover:bg-purple-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-purple-800">Reporte por Sucursal</h3>
                                            <p class="text-sm text-purple-600">Análisis por ubicación</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-purple-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="<?php echo e(route('admin.dispositivos.index')); ?>" class="block">
                                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r-lg hover:bg-orange-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-orange-800">Gestión de Dispositivos</h3>
                                            <p class="text-sm text-orange-600">Control de navegadores permitidos</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-orange-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="<?php echo e(route('admin.ubicaciones.index')); ?>" class="block">
                                <div class="bg-cyan-50 border-l-4 border-cyan-500 p-4 rounded-r-lg hover:bg-cyan-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-cyan-800">Gestión de Ubicaciones</h3>
                                            <p class="text-sm text-cyan-600">Zonas de acceso permitidas</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-cyan-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="<?php echo e(route('admin.sectores.index')); ?>" class="block">
                                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg hover:bg-amber-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-amber-800">Gestión de Sectores</h3>
                                            <p class="text-sm text-amber-600">Configurar zonas</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-amber-500"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.usuario', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/administrador/index.blade.php ENDPATH**/ ?>