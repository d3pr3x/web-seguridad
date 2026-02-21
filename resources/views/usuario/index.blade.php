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

            <!-- Cards principales: alineadas al menú (Control de acceso, Rondas QR, Incidentes) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @if(module_enabled('control_acceso') && auth()->user()->puedeVerControlAcceso())
                <a href="{{ route('ingresos.index') }}" class="block">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden h-full hover:shadow-lg transition">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4">
                            <h2 class="text-lg font-bold text-white flex items-center">
                                <i class="fas fa-qrcode mr-2"></i>
                                Control de acceso
                            </h2>
                            <p class="text-blue-50 text-sm mt-1">Ingresos, salidas y blacklist</p>
                        </div>
                        <div class="p-4">
                            <span class="text-blue-600 text-sm font-medium">Ir al módulo <i class="fas fa-chevron-right ml-1"></i></span>
                        </div>
                    </div>
                </a>
                @endif

                @if(module_enabled('rondas_qr') && auth()->user()->puedeVerRondasQR())
                <a href="{{ route('usuario.ronda.index') }}" class="block">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden h-full hover:shadow-lg transition">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 p-4">
                            <h2 class="text-lg font-bold text-white flex items-center">
                                <i class="fas fa-route mr-2"></i>
                                Rondas QR
                            </h2>
                            <p class="text-emerald-50 text-sm mt-1">Puntos de ronda y escaneos</p>
                        </div>
                        <div class="p-4">
                            <span class="text-emerald-600 text-sm font-medium">Ir al módulo <i class="fas fa-chevron-right ml-1"></i></span>
                        </div>
                    </div>
                </a>
                @endif

                <!-- Incidentes: solo Novedades y Reportes -->
                @if(auth()->user()->puedeVerMisReportes())
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
                @endif
            </div>

            <!-- Mi actividad: solo historial (registro de puntos escaneados, historial de reportes) -->
            @php
                $mostrarMiActividad = (module_enabled('rondas_qr') && auth()->user()->puedeVerRondasQR()) || auth()->user()->puedeVerMisReportes();
            @endphp
            @if($mostrarMiActividad)
            <div class="mt-6">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-600 to-slate-700 p-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-history mr-2"></i>
                            Mi actividad
                        </h2>
                        <p class="text-slate-200 text-sm mt-1">Historial y registros recientes</p>
                    </div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                        @if(auth()->user()->puedeVerMisReportes())
                        <a href="{{ route('usuario.reportes.index') }}" class="block">
                            <div class="bg-slate-50 border-l-4 border-slate-500 p-4 rounded-r-lg hover:bg-slate-100 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-slate-800">Historial de reportes</h3>
                                        <p class="text-sm text-slate-600">Ver mis reportes enviados</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-slate-500"></i>
                                </div>
                            </div>
                        </a>
                        @endif
                        @if(module_enabled('rondas_qr') && auth()->user()->puedeVerRondasQR())
                        <a href="{{ route('usuario.ronda.index') }}" class="block">
                            <div class="bg-slate-50 border-l-4 border-slate-500 p-4 rounded-r-lg hover:bg-slate-100 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-slate-800">Registro de puntos escaneados</h3>
                                        <p class="text-sm text-slate-600">Historial de escaneos de ronda</p>
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
        </div>
    </div>
</div>
@endsection
