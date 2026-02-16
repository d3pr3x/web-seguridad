@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Grupos de delitos e incidentes
                </h1>
                <a href="{{ url('/') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver
                </a>
            </div>

            <p class="text-gray-600 mb-6">Clasificación de incidentes y delitos por grupo. Cada tipo de incidente se asocia a un grupo para reportes y estadísticas.</p>

            @forelse($grupos as $grupo)
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="bg-indigo-600 text-white px-6 py-3 font-semibold text-lg">
                        {{ $grupo->nombre }}
                    </div>
                    <div class="p-6">
                        @if($grupo->tiposIncidente->count() > 0)
                            <ul class="space-y-2">
                                @foreach($grupo->tiposIncidente as $tipo)
                                    <li class="flex items-center gap-2 text-gray-700">
                                        <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                                        {{ $tipo->nombre }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">Sin tipos de incidente definidos.</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-lg">
                    <p class="text-amber-800">No hay grupos configurados. Ejecute el seeder: <code class="bg-amber-100 px-1 rounded">php artisan db:seed --class=GruposIncidentesSeeder</code></p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
