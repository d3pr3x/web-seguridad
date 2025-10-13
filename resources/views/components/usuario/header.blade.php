<!-- Header móvil (solo visible en móvil) -->
<div class="lg:hidden bg-gradient-to-r from-blue-600 to-blue-700 text-white sticky top-0 z-50 shadow-lg">
    <div class="px-4 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold">Portal {{ auth()->user()->nombre_perfil }}</h1>
                <p class="text-sm text-blue-100">{{ auth()->user()->nombre_completo }}</p>
            </div>
            <button onclick="toggleMenu()" class="p-2 hover:bg-blue-500 rounded-lg transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Header Desktop (solo visible en escritorio) -->
<div class="hidden lg:block bg-white shadow-sm sticky top-0 z-40">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Bienvenido, {{ auth()->user()->name }}</h1>
                <p class="text-sm text-gray-600">{{ auth()->user()->nombre_sucursal }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-700">{{ auth()->user()->nombre_completo }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->nombre_perfil }} • RUT: {{ auth()->user()->rut }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

