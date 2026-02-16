@extends('layouts.usuario')

@section('content')
@php
    $user = auth()->user();
    $rolSlug = $user->rol->slug ?? 'USUARIO';
@endphp
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />

    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6 max-w-7xl min-w-0 w-full">
            {{-- Banner y selector de perfil (simular vista por tipo de usuario) --}}
            <div class="mb-4 p-3 bg-amber-100 border border-amber-400 text-amber-800 rounded-lg text-sm">
                <i class="fas fa-flask me-2"></i><strong>Vista de inicio unificada (pruebas).</strong>
                <span class="ms-2">Ver como:</span>
                <select id="perfil-preview" class="form-select form-select-sm d-inline-block w-auto ms-2" style="max-width: 220px;" aria-label="Cambiar tipo de usuario para previsualizar">
                    <option value="USUARIO" {{ $rolSlug === 'USUARIO' ? 'selected' : '' }}>Guardia (Usuario)</option>
                    <option value="USUARIO_SUPERVISOR" {{ $rolSlug === 'USUARIO_SUPERVISOR' ? 'selected' : '' }}>2º jefe (Usuario-Supervisor)</option>
                    <option value="SUPERVISOR_USUARIO" {{ $rolSlug === 'SUPERVISOR_USUARIO' ? 'selected' : '' }}>Jefe turno (Supervisor-Usuario)</option>
                    <option value="SUPERVISOR" {{ $rolSlug === 'SUPERVISOR' ? 'selected' : '' }}>Jefe de contrato (Supervisor)</option>
                    <option value="ADMIN" {{ $rolSlug === 'ADMIN' ? 'selected' : '' }}>Administrador</option>
                </select>
            </div>

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

            <div class="hidden lg:block mb-6">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-0">Panel de control</p>
                <h1 class="text-xl font-bold text-gray-800 mt-0">Resumen</h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="inicio-secciones">
                {{-- 1. Reportes (crear) — Guardia, 2do jefe, Jefe turno --}}
                <div class="inicio-section" data-perfiles="USUARIO USUARIO_SUPERVISOR SUPERVISOR_USUARIO">
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

                {{-- 2. Supervisión — Jefe turno, Jefe contrato, Admin --}}
                <div class="inicio-section" data-perfiles="SUPERVISOR_USUARIO SUPERVISOR ADMIN">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r {{ $user->esAdministrador() ? 'from-teal-500 to-teal-600' : 'from-purple-500 to-purple-600' }} p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-clipboard-check mr-2"></i>
                                Supervisión
                            </h2>
                            <p class="text-sm mt-1 {{ $user->esAdministrador() ? 'text-teal-100' : 'text-purple-100' }}">Aprobaciones y revisión</p>
                        </div>
                        <div class="p-4 space-y-3">
                            @if(config('app.show_documentos_guardias'))
                            <a href="{{ $user->esAdministrador() ? route('admin.documentos.index') : route('supervisor.documentos.index') }}" class="block">
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
                            <a href="{{ $user->esAdministrador() ? route('admin.reportes-especiales.index') : route('reportes-especiales.index') }}" class="block">
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

                {{-- 3. Reportes y estadísticas — 2do jefe (sucursal), Jefe turno, Jefe contrato, Admin --}}
                <div class="inicio-section" data-perfiles="USUARIO_SUPERVISOR SUPERVISOR_USUARIO SUPERVISOR ADMIN">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-slate-600 to-slate-700 p-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Reportes y estadísticas
                            </h2>
                            <p class="text-slate-200 text-sm mt-1">Consultas y exportación</p>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="inicio-subitem" data-perfiles="SUPERVISOR_USUARIO SUPERVISOR ADMIN">
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
                            <div class="inicio-subitem" data-perfiles="USUARIO_SUPERVISOR SUPERVISOR_USUARIO SUPERVISOR ADMIN">
                            <a href="{{ route('admin.reporte-sucursal') }}" class="block">
                                <div class="bg-violet-50 border-l-4 border-violet-500 p-4 rounded-r-lg hover:bg-violet-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-violet-800">Reporte por sucursal</h3>
                                            <p class="text-sm text-violet-600">Análisis por ubicación</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-violet-500"></i>
                                    </div>
                                </div>
                            </a>
                            </div>
                            <div class="inicio-subitem" data-perfiles="ADMIN">
                            <a href="{{ route('admin.reportes-diarios') }}" class="block">
                                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg hover:bg-green-100 transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-bold text-green-800">Reportes diarios</h3>
                                            <p class="text-sm text-green-600">Reportes del sistema</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-green-500"></i>
                                    </div>
                                </div>
                            </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. Administración / Gestión — Solo Admin --}}
                <div class="inicio-section" data-perfiles="ADMIN">
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

                {{-- 5. Mi actividad — Guardia, 2do jefe, Jefe turno --}}
                <div class="inicio-section" data-perfiles="USUARIO USUARIO_SUPERVISOR SUPERVISOR_USUARIO">
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    var select = document.getElementById('perfil-preview');
    if (!select) return;
    function filtrarPorPerfil() {
        var perfil = select.value;
        document.querySelectorAll('#inicio-secciones .inicio-section').forEach(function(section) {
            var perfiles = (section.getAttribute('data-perfiles') || '').split(/\s+/);
            var mostrar = perfiles.indexOf(perfil) !== -1;
            section.style.display = mostrar ? '' : 'none';
            section.querySelectorAll('.inicio-subitem').forEach(function(sub) {
                var p = (sub.getAttribute('data-perfiles') || '').split(/\s+/);
                sub.style.display = p.indexOf(perfil) !== -1 ? '' : 'none';
            });
        });
    }
    select.addEventListener('change', filtrarPorPerfil);
    filtrarPorPerfil();
});
</script>
@endsection
