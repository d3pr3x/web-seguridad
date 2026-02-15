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
                            @if(config('app.show_documentos_guardias'))
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
                                            <h3 class="font-bold text-indigo-800">Todas las Novedades</h3>
                                            <p class="text-sm text-indigo-600">Historial completo de novedades</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-indigo-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('admin.reportes-especiales.index') }}" class="block">
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
                            <a href="{{ route('admin.usuarios.index') }}" class="block">
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
                            <a href="{{ route('admin.reportes-diarios') }}" class="block">
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
                            <a href="{{ route('admin.reporte-sucursal') }}" class="block">
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
                            <a href="{{ route('admin.dispositivos.index') }}" class="block">
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
                            <a href="{{ route('admin.ubicaciones.index') }}" class="block">
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
                            <a href="{{ route('admin.sectores.index') }}" class="block">
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
@endsection
