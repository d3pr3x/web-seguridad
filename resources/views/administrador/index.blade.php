@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />

    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6 max-w-7xl min-w-0 w-full">
            @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                <p class="font-medium"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
            </div>
            @endif
            @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                <p class="font-medium"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</p>
            </div>
            @endif

            <!-- Título (escritorio) -->
            <div class="hidden lg:block mb-6">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-0">Panel de control</p>
                <h1 class="text-xl font-bold text-gray-800 mt-0">Resumen</h1>
            </div>

            <!-- Operación: orden B.2 (Reporte escaneos QR → Novedades → Reportes → Reporte por sucursal → Todos los reportes) -->
            @php
                $tieneOperacion = (module_enabled('rondas_qr') && auth()->user()->puedeVerReportesEstadisticasCompletos())
                    || auth()->user()->puedeVerSupervision()
                    || auth()->user()->puedeVerReportesEstadisticasCompletos()
                    || auth()->user()->puedeVerReporteSucursal();
            @endphp
            @if($tieneOperacion)
            <div class="mb-6">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-600 to-slate-700 p-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-tasks mr-2"></i>
                            Operación
                        </h2>
                        <p class="text-slate-200 text-sm mt-1">Por orden de uso</p>
                    </div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3">
                        @if(module_enabled('rondas_qr') && auth()->user()->puedeVerReportesEstadisticasCompletos())
                        <a href="{{ route('admin.rondas.reporte') }}" class="block">
                            <div class="bg-cyan-50 border-l-4 border-cyan-500 p-4 rounded-r-lg hover:bg-cyan-100 transition h-full">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-cyan-800">Reporte escaneos QR</h3>
                                        <p class="text-sm text-cyan-600">Rondas y puntos</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-cyan-500"></i>
                                </div>
                            </div>
                        </a>
                        @endif
                        @if(auth()->user()->puedeVerSupervision())
                        <a href="{{ route('admin.novedades.index') }}" class="block">
                            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-r-lg hover:bg-indigo-100 transition h-full">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-indigo-800">Novedades</h3>
                                        <p class="text-sm text-indigo-600">Incidentes / Novedades</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-indigo-500"></i>
                                </div>
                            </div>
                        </a>
                        @endif
                        @if(auth()->user()->puedeVerReportesEstadisticasCompletos())
                        <a href="{{ route('admin.reportes-especiales.index') }}" class="block">
                            <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-r-lg hover:bg-teal-100 transition h-full">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-teal-800">Reportes</h3>
                                        <p class="text-sm text-teal-600">Incidentes / Reportes</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-teal-500"></i>
                                </div>
                            </div>
                        </a>
                        @endif
                        @if(auth()->user()->puedeVerReporteSucursal())
                        <a href="{{ route('admin.reporte-sucursal') }}" class="block">
                            <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r-lg hover:bg-purple-100 transition h-full">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-purple-800">Reporte por sucursal</h3>
                                        <p class="text-sm text-purple-600">Análisis por ubicación</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-purple-500"></i>
                                </div>
                            </div>
                        </a>
                        @endif
                        @if(auth()->user()->puedeVerReportesEstadisticasCompletos())
                        <a href="{{ route('admin.reportes-especiales.index') }}" class="block">
                            <div class="bg-slate-50 border-l-4 border-slate-500 p-4 rounded-r-lg hover:bg-slate-100 transition h-full">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-slate-800">Todos los reportes</h3>
                                        <p class="text-sm text-slate-600">Reportes especiales</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-slate-500"></i>
                                </div>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                @if(auth()->user()->puedeVerSupervision())
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
                            @if(auth()->user()->esAdministrador())
                            <a href="{{ route('admin.usuarios.index') }}" class="block">
                                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg hover:bg-blue-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-blue-800">Usuarios</h3>
                                            <p class="text-sm text-blue-600">Gestión de usuarios</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-blue-500"></i>
                                    </div>
                                </div>
                            </a>
                            @endif
                            @if(module_enabled('documentos_guardias'))
                            <a href="{{ route('admin.documentos.index') }}" class="block">
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
                            @endif
                            <a href="{{ route('admin.novedades.index') }}" class="block">
                                <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-r-lg hover:bg-indigo-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-indigo-800">Novedades</h3>
                                            <p class="text-sm text-indigo-600">Historial de novedades</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-indigo-500"></i>
                                    </div>
                                </div>
                            </a>
                            @if(auth()->user()->esAdministrador())
                            <a href="{{ route('admin.grupos-incidentes.index') }}" class="block">
                                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg hover:bg-amber-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-amber-800">Grupos de incidentes</h3>
                                            <p class="text-sm text-amber-600">Configuración</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-amber-500"></i>
                                    </div>
                                </div>
                            </a>
                            @endif
                            <a href="{{ route('admin.reportes-especiales.index') }}" class="block">
                                <div class="bg-pink-50 border-l-4 border-pink-500 p-4 rounded-r-lg hover:bg-pink-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-pink-800">Todos los Reportes</h3>
                                            <p class="text-sm text-pink-600">Historial de reportes</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-pink-500"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if(auth()->user()->puedeVerGestion())
                <!-- Gestión -->
                <div>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-slate-600 to-slate-700 p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-cog mr-2"></i>
                                Gestión
                            </h2>
                            <p class="text-slate-200 text-sm mt-1">Herramientas administrativas</p>
                        </div>
                        <div class="p-4 space-y-3">
                            <a href="{{ route('admin.clientes.index') }}" class="block">
                                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg hover:bg-blue-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-blue-800">Clientes</h3>
                                            <p class="text-sm text-blue-600">Gestión de clientes</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-blue-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('admin.dispositivos.index') }}" class="block">
                                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r-lg hover:bg-orange-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-orange-800">Dispositivos</h3>
                                            <p class="text-sm text-orange-600">Navegadores permitidos</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-orange-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('admin.ubicaciones.index') }}" class="block">
                                <div class="bg-cyan-50 border-l-4 border-cyan-500 p-4 rounded-r-lg hover:bg-cyan-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-cyan-800">Ubicaciones</h3>
                                            <p class="text-sm text-cyan-600">Zonas de acceso</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-cyan-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('admin.sectores.index') }}" class="block">
                                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg hover:bg-amber-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-amber-800">Sectores</h3>
                                            <p class="text-sm text-amber-600">Configurar zonas</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-amber-500"></i>
                                    </div>
                                </div>
                            </a>
                            @if(module_enabled('rondas_qr'))
                            <a href="{{ route('admin.rondas.index') }}" class="block">
                                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg hover:bg-emerald-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-emerald-800">Puntos de ronda (QR)</h3>
                                            <p class="text-sm text-emerald-600">Rondas y escaneos</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-emerald-500"></i>
                                    </div>
                                </div>
                            </a>
                            @endif
                            <a href="{{ route('admin.auditorias.index') }}" class="block">
                                <div class="bg-slate-50 border-l-4 border-slate-500 p-4 rounded-r-lg hover:bg-slate-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-slate-800">Auditorías</h3>
                                            <p class="text-sm text-slate-600">Registro de auditoría</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-slate-500"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
