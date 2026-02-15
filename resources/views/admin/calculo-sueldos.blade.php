@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <x-usuario.sidebar />

    <!-- Contenido principal -->
    <div class="flex-1 lg:mr-64">
        <!-- Header -->
        <x-usuario.header />

        <!-- Menú Móvil -->
        <x-usuario.mobile-menu />

        <!-- Contenido Principal -->
        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <!-- Título y botón volver -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Cálculo de Sueldos
                </h1>
                <a href="{{ url('/') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('admin.calculo-sueldos') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="mes" class="block text-sm font-medium text-gray-700 mb-2">Mes</label>
                        <input type="month" id="mes" name="mes" value="{{ $mes }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="sucursal_id" class="block text-sm font-medium text-gray-700 mb-2">Sucursal</label>
                        <select id="sucursal_id" name="sucursal_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Todas las sucursales</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}" @if($sucursalId == $sucursal->id) selected @endif>
                                    {{ $sucursal->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="sueldo_base" class="block text-sm font-medium text-gray-700 mb-2">Sueldo Base Diario</label>
                        <input type="number" id="sueldo_base" name="sueldo_base" value="{{ $sueldoBase }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Calcular
                        </button>
                        <a href="{{ route('admin.calculo-sueldos.exportar', request()->query()) }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Exportar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Estadísticas generales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Empleados</p>
                            <h3 class="text-3xl font-bold mt-2">{{ $totalEmpleados }}</h3>
                        </div>
                        <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Días trabajados</p>
                            <h3 class="text-3xl font-bold mt-2">{{ $totalDiasTrabajados }}</h3>
                        </div>
                        <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-cyan-100 text-sm font-medium">Sueldo bruto total</p>
                            <h3 class="text-2xl font-bold mt-2">${{ number_format($totalSueldoBruto, 0, ',', '.') }}</h3>
                        </div>
                        <svg class="w-12 h-12 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Sueldo neto total</p>
                            <h3 class="text-2xl font-bold mt-2">${{ number_format($totalSueldoNeto, 0, ',', '.') }}</h3>
                        </div>
                        <svg class="w-12 h-12 text-yellow-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Cálculos por empleado -->
            @if(count($calculos) > 0)
                <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Cálculos por Empleado - {{ \Carbon\Carbon::parse($mes . '-01')->format('F Y') }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($calculos as $calculo)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 p-4 text-white">
                                <h3 class="font-bold flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $calculo['usuario']->nombre_completo }}
                                </h3>
                            </div>
                            <div class="p-4">
                                <div class="space-y-2 mb-4">
                                    <p class="text-sm text-gray-700"><span class="font-semibold">Sucursal:</span> {{ $calculo['usuario']->nombre_sucursal }}</p>
                                    <p class="text-sm text-gray-700"><span class="font-semibold">RUN:</span> {{ $calculo['usuario']->run }}</p>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-4 mb-4">
                                    <div class="grid grid-cols-2 gap-4 text-center">
                                        <div>
                                            <h4 class="text-2xl font-bold text-blue-600">{{ $calculo['dias_trabajados'] }}</h4>
                                            <p class="text-xs text-gray-500">Días trabajados</p>
                                        </div>
                                        <div>
                                            <h4 class="text-2xl font-bold text-green-600">${{ number_format($calculo['sueldo_neto'], 0, ',', '.') }}</h4>
                                            <p class="text-xs text-gray-500">Sueldo neto</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-4 mb-4">
                                    <h5 class="text-sm font-semibold text-gray-700 mb-2">Desglose de días:</h5>
                                    <div class="grid grid-cols-2 gap-2">
                                        @if($calculo['dias_normales'] > 0)
                                            <div class="text-center">
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">{{ $calculo['dias_normales'] }}</span>
                                                <p class="text-xs text-gray-500 mt-1">Hábiles</p>
                                            </div>
                                        @endif
                                        @if($calculo['dias_sabados'] > 0)
                                            <div class="text-center">
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">{{ $calculo['dias_sabados'] }}</span>
                                                <p class="text-xs text-gray-500 mt-1">Sábados</p>
                                            </div>
                                        @endif
                                        @if($calculo['dias_domingos'] > 0)
                                            <div class="text-center">
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">{{ $calculo['dias_domingos'] }}</span>
                                                <p class="text-xs text-gray-500 mt-1">Domingos</p>
                                            </div>
                                        @endif
                                        @if($calculo['dias_feriados'] > 0)
                                            <div class="text-center">
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">{{ $calculo['dias_feriados'] }}</span>
                                                <p class="text-xs text-gray-500 mt-1">Feriados</p>
                                            </div>
                                        @endif
                                        @if($calculo['dias_extras'] > 0)
                                            <div class="text-center">
                                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">{{ $calculo['dias_extras'] }}</span>
                                                <p class="text-xs text-gray-500 mt-1">Extras</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-4">
                                    <h5 class="text-sm font-semibold text-gray-700 mb-2">Detalle económico:</h5>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-500">Sueldo bruto:</p>
                                            <p class="font-bold text-gray-900">${{ number_format($calculo['sueldo_bruto'], 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Descuentos:</p>
                                            <p class="font-bold text-red-600">-${{ number_format($calculo['descuentos'], 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 border-t border-gray-200">
                                <button onclick="verDetalle({{ $calculo['usuario']->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver Detalle
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-12 h-12 text-blue-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="text-lg font-bold text-blue-800">No hay datos</h4>
                            <p class="text-blue-700">No se encontraron empleados con días trabajados para el mes seleccionado.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para ver detalle -->
<div id="detalleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-bold text-gray-900">Detalle de días trabajados</h3>
            <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="detalleContent" class="mt-4">
            <!-- Contenido dinámico -->
        </div>
        <div class="flex justify-end pt-4 border-t mt-4">
            <button onclick="cerrarModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                Cerrar
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function verDetalle(usuarioId) {
        document.getElementById('detalleContent').innerHTML = `
            <div class="text-center py-8">
                <svg class="animate-spin h-10 w-10 text-blue-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-600">Cargando detalle de días trabajados...</p>
            </div>
        `;
        
        document.getElementById('detalleModal').classList.remove('hidden');
        
        setTimeout(() => {
            document.getElementById('detalleContent').innerHTML = `
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-blue-700">Funcionalidad de detalle en desarrollo. Aquí se mostrarían todos los días trabajados del empleado con sus respectivos cálculos.</p>
                    </div>
                </div>
            `;
        }, 1000);
    }
    
    function cerrarModal() {
        document.getElementById('detalleModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
