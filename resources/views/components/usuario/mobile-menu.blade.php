<!-- Menú Acordeón Móvil (solo visible en móvil) -->
<div id="sideMenu" class="lg:hidden fixed inset-y-0 right-0 w-64 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-50">
    <div class="p-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-800">Menú</h2>
            <button onclick="toggleMenu()" class="p-2 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="space-y-2">
            @php
                $homeRoute = auth()->user()->esAdministrador() 
                    ? 'administrador.index' 
                    : (auth()->user()->esSupervisor() 
                        ? 'supervisor.index' 
                        : 'usuario.index');
            @endphp
            <a href="{{ route($homeRoute) }}" class="flex items-center p-3 hover:bg-blue-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="font-medium">Inicio</span>
            </a>
            
            @if(auth()->user()->esUsuario() || auth()->user()->esSupervisorUsuario())

            <a href="{{ route('usuario.perfil.index') }}" class="flex items-center p-3 hover:bg-blue-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="font-medium">Mi Perfil</span>
            </a>

            <a href="{{ route('usuario.acciones.index') }}" class="flex items-center p-3 hover:bg-blue-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <span class="font-medium">Mis Novedades</span>
            </a>

            <a href="{{ route('usuario.reportes.index') }}" class="flex items-center p-3 hover:bg-blue-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <span class="font-medium">Mis Reportes</span>
            </a>

            <a href="{{ route('usuario.historial.index') }}" class="flex items-center p-3 hover:bg-blue-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="font-medium">Historial Completo</span>
            </a>

            <a href="{{ route('usuario.documentos.index') }}" class="flex items-center p-3 hover:bg-blue-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="font-medium">Mis Documentos</span>
            </a>
            @endif

            @if(auth()->user()->esSupervisor() || auth()->user()->esAdministrador())
            <hr class="my-4 border-t-2 border-purple-200">
            
            <div class="px-3 py-2">
                <span class="text-xs font-semibold text-purple-600 uppercase tracking-wide">Gestión</span>
            </div>

            <a href="{{ auth()->user()->esAdministrador() ? route('admin.documentos.index') : route('supervisor.documentos.index') }}" class="flex items-center p-3 hover:bg-purple-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">Aprobar Documentos</span>
            </a>

            <a href="{{ route('acciones.index') }}" class="flex items-center p-3 hover:bg-purple-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="font-medium">Todas las Novedades</span>
            </a>

            <a href="{{ route('reportes-especiales.index') }}" class="flex items-center p-3 hover:bg-purple-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <span class="font-medium">Todos los Reportes</span>
            </a>
            @endif

            @if(auth()->user()->esAdministrador())
            <hr class="my-4 border-t-2 border-red-200">
            
            <div class="px-3 py-2">
                <span class="text-xs font-semibold text-red-600 uppercase tracking-wide">Administración</span>
            </div>

            <a href="{{ route('admin.reportes-diarios') }}" class="flex items-center p-3 hover:bg-red-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span class="font-medium">Reportes Diarios</span>
            </a>

            <a href="{{ route('admin.calculo-sueldos') }}" class="flex items-center p-3 hover:bg-red-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">Cálculo de Sueldos</span>
            </a>

            <a href="{{ route('admin.reporte-sucursal') }}" class="flex items-center p-3 hover:bg-red-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span class="font-medium">Reporte Sucursal</span>
            </a>

            <a href="{{ route('admin.dispositivos.index') }}" class="flex items-center p-3 hover:bg-red-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <span class="font-medium">Gestión Dispositivos</span>
            </a>

            <a href="{{ route('admin.ubicaciones.index') }}" class="flex items-center p-3 hover:bg-red-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="font-medium">Gestión Ubicaciones</span>
            </a>

            <a href="{{ route('admin.sectores.index') }}" class="flex items-center p-3 hover:bg-red-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="font-medium">Gestión Sectores</span>
            </a>
            @endif

            <hr class="my-4">

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
    </div>
</div>

<!-- Overlay para el menú móvil -->
<div id="menuOverlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden" onclick="toggleMenu()"></div>

<script>
    function toggleMenu() {
        const menu = document.getElementById('sideMenu');
        const overlay = document.getElementById('menuOverlay');
        
        if (menu.classList.contains('translate-x-full')) {
            menu.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
        } else {
            menu.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        }
    }
</script>

