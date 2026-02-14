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
                <div class="mb-4 p-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800">
                    <p class="font-medium"><?php echo e(session('success')); ?></p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                <div class="mb-4 p-4 rounded-xl border border-red-200 bg-red-50 text-red-800">
                    <p class="font-medium"><?php echo e(session('error')); ?></p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h1 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-10 h-10 rounded-xl flex items-center justify-center text-teal-600 shrink-0" style="background: rgba(15, 118, 110, 0.12);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h.01M9 16h.01"></path>
                        </svg>
                    </span>
                    Ingresos
                </h1>
                <div class="flex flex-wrap gap-2">
                    <a href="<?php echo e(route('ingresos.escaner')); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium text-white transition shadow-sm hover:shadow" style="background: var(--app-primary);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        Escáner
                    </a>
                    <a href="<?php echo e(route('blacklist.index')); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium border transition bg-white hover:bg-slate-50" style="border-color: var(--app-border); color: var(--app-text);">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        Blacklist
                    </a>
                    <form action="<?php echo e(route('ingresos.exportar-csv')); ?>" method="post" class="inline">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="fecha_desde" value="<?php echo e(request('fecha_desde')); ?>">
                        <input type="hidden" name="fecha_hasta" value="<?php echo e(request('fecha_hasta')); ?>">
                        <input type="hidden" name="tipo" value="<?php echo e(request('tipo')); ?>">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium border transition bg-white hover:bg-slate-50" style="border-color: var(--app-border); color: var(--app-text);">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Exportar CSV
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <div class="px-5 py-4 border-b border-slate-100" style="background: var(--app-surface);">
                    <h2 class="text-sm font-semibold text-slate-700">Filtros</h2>
                </div>
                <div class="p-5">
                    <form method="get" action="<?php echo e(route('ingresos.index')); ?>" class="flex flex-wrap gap-4 items-end">
                        <div class="min-w-[140px]">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Desde</label>
                            <input type="date" name="fecha_desde" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);" value="<?php echo e(request('fecha_desde')); ?>">
                        </div>
                        <div class="min-w-[140px]">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Hasta</label>
                            <input type="date" name="fecha_hasta" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);" value="<?php echo e(request('fecha_hasta')); ?>">
                        </div>
                        <div class="min-w-[140px]">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tipo</label>
                            <select name="tipo" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);">
                                <option value="">Todos</option>
                                <option value="peatonal" <?php echo e(request('tipo') === 'peatonal' ? 'selected' : ''); ?>>Peatonal</option>
                                <option value="vehicular" <?php echo e(request('tipo') === 'vehicular' ? 'selected' : ''); ?>>Vehicular</option>
                            </select>
                        </div>
                        <div class="min-w-[140px]">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Estado</label>
                            <select name="estado" class="w-full px-3 py-2.5 rounded-lg border text-slate-800 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition" style="border-color: var(--app-border);">
                                <option value="">Todos</option>
                                <option value="ingresado" <?php echo e(request('estado') === 'ingresado' ? 'selected' : ''); ?>>Ingresado</option>
                                <option value="salida" <?php echo e(request('estado') === 'salida' ? 'selected' : ''); ?>>Salida</option>
                                <option value="bloqueado" <?php echo e(request('estado') === 'bloqueado' ? 'selected' : ''); ?>>Bloqueado</option>
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2.5 rounded-xl font-medium text-white transition shadow-sm hover:shadow" style="background: var(--app-primary);">
                            Filtrar
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200" style="background: var(--app-surface);">
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Fecha ingreso</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tipo</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">RUT / Nombre / Patente</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Guardia</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Estado</th>
                                <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $ingresos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingreso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-3.5 text-sm text-slate-800"><?php echo e($ingreso->fecha_ingreso->format('d/m/Y H:i')); ?></td>
                                <td class="px-5 py-3.5">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ingreso->tipo === 'peatonal'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-teal-50 text-teal-700">Peatonal</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700">Vehicular</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td class="px-5 py-3.5 text-sm">
                                    <span class="font-medium text-slate-800"><?php echo e($ingreso->rut); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ingreso->nombre): ?> <br><span class="text-slate-500"><?php echo e($ingreso->nombre); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ingreso->patente): ?> <br><span class="text-slate-500">Patente: <?php echo e($ingreso->patente); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td class="px-5 py-3.5 text-sm text-slate-600"><?php echo e($ingreso->guardia->nombre_completo ?? '-'); ?></td>
                                <td class="px-5 py-3.5">
                                    <div class="flex flex-wrap gap-1.5">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ingreso->estado === 'ingresado'): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-700">Ingresado</span>
                                        <?php elseif($ingreso->estado === 'bloqueado'): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-50 text-red-700">Bloqueado</span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ingreso->alerta_blacklist): ?>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-amber-50 text-amber-700">Blacklist</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-600">Salida</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ingreso->estado === 'ingresado'): ?>
                                            <form action="<?php echo e(route('ingresos.salida', $ingreso->id)); ?>" method="post" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="text-sm font-medium text-teal-600 hover:text-teal-700 hover:underline">Salida</button>
                                            </form>
                                            <span class="text-slate-300">·</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <a href="<?php echo e(route('ingresos.show', $ingreso->id)); ?>" class="text-sm font-medium text-teal-600 hover:text-teal-700 hover:underline">Detalle</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-500">No hay ingresos registrados.</td>
                            </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ingresos->hasPages()): ?>
                    <div class="px-5 py-3 border-t border-slate-200" style="background: var(--app-surface);">
                        <?php echo e($ingresos->links()); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.usuario', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/ingresos/listado.blade.php ENDPATH**/ ?>