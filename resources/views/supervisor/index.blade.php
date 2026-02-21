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

            @php
                $esUsuarioSupervisor = auth()->user()->esUsuarioSupervisor();
                $esSupervisorUsuario = auth()->user()->esSupervisorUsuario();
            @endphp

            @if($esUsuarioSupervisor)
            {{-- Usuario-Supervisor: Incidentes (1), Supervisión (2), Reportes y estadísticas (3) — alineado al menú --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <!-- 1. Incidentes (Novedades + Reportes) -->
                @if(auth()->user()->puedeVerMisReportes())
                <div>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-red-600 to-red-700 p-4">
                            <h2 class="text-lg font-bold text-white flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Incidentes
                            </h2>
                            <p class="text-red-50 text-sm mt-1">Novedades y reportes</p>
                        </div>
                        <div class="p-4 space-y-3">
                            <a href="{{ route('usuario.acciones.index') }}" class="block">
                                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg hover:bg-red-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-red-800">Novedades</h3>
                                            <p class="text-sm text-red-600">Registro de novedades</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-red-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('usuario.reportes.index') }}" class="block">
                                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg hover:bg-rose-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-rose-800">Reportes</h3>
                                            <p class="text-sm text-rose-600">Crear y ver reportes</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-rose-500"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- 2. Supervisión -->
                <div>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-clipboard-check mr-2"></i>
                                Supervisión
                            </h2>
                            <p class="text-purple-100 text-sm mt-1">Aprobaciones y revisión</p>
                        </div>
                        <div class="p-4 space-y-3">
                            @if(module_enabled('documentos_guardias'))
                            <a href="{{ route('supervisor.documentos.index') }}" class="block">
                                <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r-lg hover:bg-purple-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-purple-800">Aprobar Documentos</h3>
                                            <p class="text-sm text-purple-600">Revisar documentos personales</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-purple-500"></i>
                                    </div>
                                </div>
                            </a>
                            @endif
                            <a href="{{ route('admin.novedades.index') }}" class="block">
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
                            <a href="{{ route('reportes-especiales.index') }}" class="block">
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
            </div>

            <!-- 3. Reportes y estadísticas (Usuario-Supervisor) -->
            @if(auth()->user()->puedeVerReporteSucursal() || auth()->user()->puedeVerReportesEstadisticasCompletos())
            <div class="mt-6">
                <div class="bg-white rounded-lg shadow-md overflow-hidden max-w-2xl">
                    <div class="bg-gradient-to-r from-slate-600 to-slate-700 p-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Reportes y estadísticas
                        </h2>
                        <p class="text-slate-200 text-sm mt-1">Consultas y exportación</p>
                    </div>
                    <div class="p-4 space-y-3">
                        <a href="{{ route('admin.reportes-especiales.index') }}" class="block">
                            <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-r-lg hover:bg-teal-100 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-teal-800">Todos los reportes</h3>
                                        <p class="text-sm text-teal-600">Ver reportes especiales</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-teal-500"></i>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('admin.rondas.reporte') }}" class="block">
                            <div class="bg-cyan-50 border-l-4 border-cyan-500 p-4 rounded-r-lg hover:bg-cyan-100 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-cyan-800">Reporte escaneos QR</h3>
                                        <p class="text-sm text-cyan-600">Rondas y puntos</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-cyan-500"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @else
            {{-- Supervisor puro o Supervisor-Usuario: Supervisión (1), Reportes y estadísticas (2), Reportes (3 solo supervisor-usuario) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- 1. Supervisión -->
                <div>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-clipboard-check mr-2"></i>
                                Supervisión
                            </h2>
                            <p class="text-purple-100 text-sm mt-1">Aprobaciones y revisión</p>
                        </div>
                        <div class="p-4 space-y-3">
                            @if(module_enabled('documentos_guardias'))
                            <a href="{{ route('supervisor.documentos.index') }}" class="block">
                                <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r-lg hover:bg-purple-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-purple-800">Aprobar Documentos</h3>
                                            <p class="text-sm text-purple-600">Revisar documentos personales</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-purple-500"></i>
                                    </div>
                                </div>
                            </a>
                            @endif
                            <a href="{{ route('admin.novedades.index') }}" class="block">
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
                            <a href="{{ route('reportes-especiales.index') }}" class="block">
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

                <!-- 2. Reportes y estadísticas -->
                <div>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-slate-600 to-slate-700 p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Reportes y estadísticas
                            </h2>
                            <p class="text-slate-200 text-sm mt-1">Consultas y exportación</p>
                        </div>
                        <div class="p-4 space-y-3">
                            <a href="{{ route('admin.reportes-especiales.index') }}" class="block">
                                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-r-lg hover:bg-teal-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-teal-800">Todos los reportes</h3>
                                            <p class="text-sm text-teal-600">Ver reportes especiales</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-teal-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('admin.rondas.reporte') }}" class="block">
                                <div class="bg-cyan-50 border-l-4 border-cyan-500 p-4 rounded-r-lg hover:bg-cyan-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-cyan-800">Reporte escaneos QR</h3>
                                            <p class="text-sm text-cyan-600">Rondas y puntos</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-cyan-500"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if($esSupervisorUsuario && auth()->user()->puedeVerMisReportes())
            <!-- 3. Supervisor-Usuario: sección Incidentes (Novedades + Reportes) — alineado al menú -->
            <div class="mt-6">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-red-600 to-red-700 p-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Incidentes
                        </h2>
                        <p class="text-red-50 text-sm mt-1">Novedades y reportes</p>
                    </div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <a href="{{ route('usuario.acciones.index') }}" class="block">
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg hover:bg-red-100 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-red-800">Novedades</h3>
                                        <p class="text-sm text-red-600">Registro de novedades</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-red-500"></i>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('usuario.reportes.index') }}" class="block">
                            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg hover:bg-rose-100 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-rose-800">Reportes</h3>
                                        <p class="text-sm text-rose-600">Crear y ver reportes</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-rose-500"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
</div>
@endsection
