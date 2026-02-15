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

            <!-- Secciones: mismo diseño que administrador -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Reportes -->
                <div>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-red-600 to-red-700 p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Reportes
                            </h2>
                            <p class="text-red-50 text-sm mt-1">Situaciones críticas que requieren atención</p>
                        </div>
                        <div class="p-4 space-y-3">
                            <a href="{{ route('usuario.reportes.create', ['tipo' => 'incidentes']) }}" class="block">
                                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg hover:bg-red-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-red-800">Incidentes</h3>
                                            <p class="text-sm text-red-600">Eventos críticos</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-red-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('usuario.reportes.create', ['tipo' => 'denuncia']) }}" class="block">
                                <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r-lg hover:bg-purple-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-purple-800">Denuncia</h3>
                                            <p class="text-sm text-purple-600">Reportar delito</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-purple-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('usuario.reportes.create', ['tipo' => 'detenido']) }}" class="block">
                                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r-lg hover:bg-orange-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-orange-800">Detenido</h3>
                                            <p class="text-sm text-orange-600">Persona detenida</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-orange-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('usuario.reportes.create', ['tipo' => 'accion_sospechosa']) }}" class="block">
                                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg hover:bg-yellow-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-yellow-800">Acción Sospechosa</h3>
                                            <p class="text-sm text-yellow-600">Comportamiento extraño</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-yellow-500"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mi actividad -->
                <div>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-slate-600 to-slate-700 p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                Mi actividad
                            </h2>
                            <p class="text-slate-200 text-sm mt-1">Perfil, reportes y rondas</p>
                        </div>
                        <div class="p-4 space-y-3">
                            <a href="{{ route('usuario.perfil.index') }}" class="block">
                                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg hover:bg-blue-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-blue-800">Mi perfil</h3>
                                            <p class="text-sm text-blue-600">Datos personales y contraseña</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-blue-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('usuario.reportes.index') }}" class="block">
                                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-r-lg hover:bg-teal-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-teal-800">Mis reportes</h3>
                                            <p class="text-sm text-teal-600">Ver historial de reportes</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-teal-500"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('usuario.ronda.index') }}" class="block">
                                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg hover:bg-emerald-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-emerald-800">Rondas QR</h3>
                                            <p class="text-sm text-emerald-600">Registro de puntos de ronda</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-emerald-500"></i>
                                    </div>
                                </div>
                            </a>
                            @if(config('app.show_documentos_guardias'))
                            <a href="{{ route('usuario.documentos.index') }}" class="block">
                                <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-r-lg hover:bg-indigo-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-indigo-800">Mis documentos</h3>
                                            <p class="text-sm text-indigo-600">Documentos personales</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-indigo-500"></i>
                                    </div>
                                </div>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
