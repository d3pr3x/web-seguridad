<!-- Sidebar Desktop (oculto en móvil) -->
<aside class="hidden lg:block w-64 bg-white shadow-lg fixed h-full overflow-y-auto">
    <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
        <h2 class="text-xl font-bold">Portal {{ auth()->user()->nombre_perfil }}</h2>
        <p class="text-sm text-blue-100 mt-1">{{ auth()->user()->nombre_completo }}</p>
    </div>

    <nav class="p-4">
        <div class="space-y-1">
            @php
                $homeRoute = auth()->user()->esAdministrador()
                    ? 'administrador.index'
                    : (auth()->user()->esSupervisor()
                        ? 'supervisor.index'
                        : 'usuario.index');
                $isHomeActive = request()->routeIs($homeRoute);
            @endphp
            <a href="{{ route($homeRoute) }}" class="flex items-center p-3 {{ $isHomeActive ? 'bg-blue-50 text-blue-700 font-medium' : 'hover:bg-blue-50' }} rounded-lg transition">
                <svg class="w-5 h-5 mr-3 {{ $isHomeActive ? 'text-blue-600' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="{{ $isHomeActive ? '' : 'text-gray-700' }}">Inicio</span>
            </a>

            <a href="{{ route('ingresos.index') }}" class="flex items-center p-3 {{ request()->routeIs('ingresos.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'hover:bg-gray-50' }} rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
                <span class="text-gray-700">Control de acceso</span>
            </a>

            @if(auth()->user()->esUsuario() || auth()->user()->esSupervisorUsuario())
            <a href="{{ route('usuario.perfil.index') }}" class="flex items-center p-3 {{ request()->routeIs('usuario.perfil.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'hover:bg-gray-50' }} rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-gray-700">Mi Perfil</span>
            </a>

            {{-- Acordeón: Reportes (usuario) --}}
            <details class="sidebar-accordion group" data-section="reportes" {{ request()->routeIs('usuario.reportes.*') ? 'open' : '' }}>
                <summary class="flex items-center p-3 cursor-pointer hover:bg-gray-50 rounded-lg list-none transition">
                    <svg class="w-5 h-5 mr-3 text-gray-600 group-open:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <span class="text-gray-700 font-medium">Reportes</span>
                </summary>
                <div class="pl-4 pb-1">
                    <a href="{{ route('usuario.reportes.index') }}" class="flex items-center py-2 px-3 {{ request()->routeIs('usuario.reportes.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'hover:bg-gray-50' }} rounded-lg transition text-sm">
                        <span class="ml-6 text-gray-700">Mis Reportes</span>
                    </a>
                </div>
            </details>

            {{-- Acordeón: Documentos (usuario) --}}
            <details class="sidebar-accordion group" data-section="documentos" {{ request()->routeIs('usuario.documentos.*') ? 'open' : '' }}>
                <summary class="flex items-center p-3 cursor-pointer hover:bg-gray-50 rounded-lg list-none transition">
                    <svg class="w-5 h-5 mr-3 text-gray-600 group-open:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-gray-700 font-medium">Documentos</span>
                </summary>
                <div class="pl-4 pb-1">
                    <a href="{{ route('usuario.documentos.index') }}" class="flex items-center py-2 px-3 {{ request()->routeIs('usuario.documentos.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'hover:bg-gray-50' }} rounded-lg transition text-sm">
                        <span class="ml-6 text-gray-700">Mis Documentos</span>
                    </a>
                </div>
            </details>

            <a href="{{ route('usuario.ronda.index') }}" class="flex items-center p-3 {{ request()->routeIs('usuario.ronda.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'hover:bg-gray-50' }} rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
                <span class="text-gray-700">Rondas QR</span>
            </a>
            @endif

            @if(auth()->user()->esSupervisor() || auth()->user()->esAdministrador())
            <hr class="my-3 border-t border-gray-200">

            {{-- Acordeón: Reportes (supervisor/admin) --}}
            <details class="sidebar-accordion group" data-section="reportes" {{ request()->routeIs(['admin.reportes-especiales.*', 'admin.rondas.reporte', 'admin.reportes-diarios', 'admin.reporte-sucursal']) ? 'open' : '' }}>
                <summary class="flex items-center p-3 cursor-pointer hover:bg-purple-50 rounded-lg list-none transition">
                    <svg class="w-5 h-5 mr-3 text-purple-600 group-open:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <span class="text-gray-700 font-medium">Reportes</span>
                </summary>
                <div class="pl-4 pb-1 space-y-0.5">
                    <a href="{{ route('admin.reportes-especiales.index') }}" class="flex items-center py-2 px-3 {{ request()->routeIs('admin.reportes-especiales.*') ? 'bg-purple-50 text-purple-700 font-medium' : 'hover:bg-purple-50' }} rounded-lg transition text-sm">
                        <span class="ml-6">Todos los Reportes</span>
                    </a>
                    <a href="{{ route('admin.rondas.reporte') }}" class="flex items-center py-2 px-3 {{ request()->routeIs('admin.rondas.reporte') ? 'bg-purple-50 text-purple-700 font-medium' : 'hover:bg-purple-50' }} rounded-lg transition text-sm">
                        <span class="ml-6">Reporte Escaneos QR</span>
                    </a>
                    @if(auth()->user()->esAdministrador())
                    <a href="{{ route('admin.reportes-diarios') }}" class="flex items-center py-2 px-3 {{ request()->routeIs('admin.reportes-diarios') ? 'bg-purple-50 text-purple-700 font-medium' : 'hover:bg-purple-50' }} rounded-lg transition text-sm">
                        <span class="ml-6">Reportes Diarios</span>
                    </a>
                    <a href="{{ route('admin.reporte-sucursal') }}" class="flex items-center py-2 px-3 {{ request()->routeIs('admin.reporte-sucursal') ? 'bg-purple-50 text-purple-700 font-medium' : 'hover:bg-purple-50' }} rounded-lg transition text-sm">
                        <span class="ml-6">Reporte por Sucursal</span>
                    </a>
                    @endif
                </div>
            </details>

            {{-- Acordeón: Usuarios y Documentos --}}
            <details class="sidebar-accordion group" data-section="usuarios-documentos" {{ request()->routeIs(['admin.usuarios.*', 'admin.documentos.*', 'supervisor.documentos.*']) ? 'open' : '' }}>
                <summary class="flex items-center p-3 cursor-pointer hover:bg-purple-50 rounded-lg list-none transition">
                    <svg class="w-5 h-5 mr-3 text-purple-600 group-open:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="text-gray-700 font-medium">Usuarios y Documentos</span>
                </summary>
                <div class="pl-4 pb-1 space-y-0.5">
                    @if(auth()->user()->esAdministrador())
                    <a href="{{ route('admin.usuarios.index') }}" class="flex items-center py-2 px-3 {{ request()->routeIs('admin.usuarios.*') ? 'bg-purple-50 text-purple-700 font-medium' : 'hover:bg-purple-50' }} rounded-lg transition text-sm">
                        <span class="ml-6">Usuarios</span>
                    </a>
                    @endif
                    <a href="{{ auth()->user()->esAdministrador() ? route('admin.documentos.index') : route('supervisor.documentos.index') }}" class="flex items-center py-2 px-3 {{ request()->routeIs(['admin.documentos.*', 'supervisor.documentos.*']) ? 'bg-purple-50 text-purple-700 font-medium' : 'hover:bg-purple-50' }} rounded-lg transition text-sm">
                        <span class="ml-6">Aprobar Documentos</span>
                    </a>
                </div>
            </details>
            @endif

            @if(auth()->user()->esAdministrador())
            <hr class="my-3 border-t border-red-200">

            {{-- Acordeón: Gestión (solo admin) --}}
            <details class="sidebar-accordion group" data-section="gestion" {{ request()->routeIs(['admin.dispositivos.*', 'admin.ubicaciones.*', 'admin.sectores.*', 'admin.rondas.*']) && !request()->routeIs('admin.rondas.reporte') ? 'open' : '' }}>
                <summary class="flex items-center p-3 cursor-pointer hover:bg-red-50 rounded-lg list-none transition">
                    <svg class="w-5 h-5 mr-3 text-red-600 group-open:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-gray-700 font-medium">Gestión</span>
                </summary>
                <div class="pl-4 pb-1 space-y-0.5">
                    <a href="{{ route('admin.dispositivos.index') }}" class="flex items-center py-2 px-3 {{ request()->routeIs('admin.dispositivos.*') ? 'bg-red-50 text-red-700 font-medium' : 'hover:bg-red-50' }} rounded-lg transition text-sm">
                        <span class="ml-6">Dispositivos</span>
                    </a>
                    <a href="{{ route('admin.ubicaciones.index') }}" class="flex items-center py-2 px-3 {{ request()->routeIs('admin.ubicaciones.*') ? 'bg-red-50 text-red-700 font-medium' : 'hover:bg-red-50' }} rounded-lg transition text-sm">
                        <span class="ml-6">Ubicaciones</span>
                    </a>
                    <a href="{{ route('admin.sectores.index') }}" class="flex items-center py-2 px-3 {{ request()->routeIs('admin.sectores.*') ? 'bg-red-50 text-red-700 font-medium' : 'hover:bg-red-50' }} rounded-lg transition text-sm">
                        <span class="ml-6">Sectores</span>
                    </a>
                    <a href="{{ route('admin.rondas.index') }}" class="flex items-center py-2 px-3 {{ request()->routeIs('admin.rondas.index') || request()->routeIs('admin.rondas.show') || request()->routeIs('admin.rondas.create') || request()->routeIs('admin.rondas.edit') ? 'bg-red-50 text-red-700 font-medium' : 'hover:bg-red-50' }} rounded-lg transition text-sm">
                        <span class="ml-6">Puntos de Ronda (QR)</span>
                    </a>
                </div>
            </details>
            @endif

            <hr class="my-3">

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center p-3 hover:bg-red-50 rounded-lg transition w-full text-left">
                    <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="text-red-600 font-medium">Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </nav>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.sidebar-accordion').forEach(function(details) {
        details.querySelector('summary').addEventListener('click', function(e) {
            var open = document.querySelectorAll('.sidebar-accordion[open]');
            open.forEach(function(d) {
                if (d !== details) d.removeAttribute('open');
            });
        });
    });
});
</script>
