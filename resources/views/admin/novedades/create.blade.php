@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />

        <div class="container mx-auto px-4 py-6 max-w-4xl">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Registrar novedad o incidente
                </h1>
                <a href="{{ route('admin.novedades.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al listado
                </a>
            </div>

            <p class="text-gray-600 mb-6">Como administrador puede registrar cualquier novedad o incidente en cualquier instalación. Use <strong>tipo de hecho</strong> e <strong>importancia</strong> para que luego pueda filtrar y atender primero lo relevante.</p>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('admin.novedades.store') }}" method="POST" enctype="multipart/form-data" id="formNovedad">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="sucursal_id" class="block text-sm font-medium text-gray-700 mb-1">Instalación <span class="text-red-500">*</span></label>
                            <select name="sucursal_id" id="sucursal_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('sucursal_id') border-red-500 @enderror">
                                <option value="">Seleccione instalación</option>
                                @foreach($sucursales as $s)
                                    <option value="{{ $s->id }}" {{ old('sucursal_id') == $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                                @endforeach
                            </select>
                            @error('sucursal_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="sector_id" class="block text-sm font-medium text-gray-700 mb-1">Sector</label>
                            <select name="sector_id" id="sector_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('sector_id') border-red-500 @enderror">
                                <option value="">Sin sector</option>
                                @foreach($sectores as $sucId => $sectoresGrupo)
                                    @foreach($sectoresGrupo as $sec)
                                        <option value="{{ $sec->id }}" data-sucursal="{{ $sec->sucursal_id }}" {{ old('sector_id') == $sec->id ? 'selected' : '' }}>{{ $sec->nombre }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            @error('sector_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de acción <span class="text-red-500">*</span></label>
                            <select name="tipo" id="tipo" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('tipo') border-red-500 @enderror">
                                @foreach($tipos as $key => $nombre)
                                    <option value="{{ $key }}" {{ old('tipo', 'rondas') == $key ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                            @error('tipo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="tipo_hecho" class="block text-sm font-medium text-gray-700 mb-1">Tipo de hecho</label>
                            <select name="tipo_hecho" id="tipo_hecho" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('tipo_hecho') border-red-500 @enderror">
                                <option value="">— Seleccione si aplica —</option>
                                @foreach($hechos as $key => $nombre)
                                    <option value="{{ $key }}" {{ old('tipo_hecho') == $key ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Incidente, observación, información, delito, accidente</p>
                            @error('tipo_hecho')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="importancia" class="block text-sm font-medium text-gray-700 mb-1">Importancia</label>
                            <select name="importancia" id="importancia" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('importancia') border-red-500 @enderror">
                                <option value="">— Seleccione —</option>
                                @foreach($nivelesImportancia as $key => $nombre)
                                    <option value="{{ $key }}" {{ old('importancia') == $key ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Le permite filtrar después por "solo importantes" o "críticas"</p>
                            @error('importancia')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="id_usuario" class="block text-sm font-medium text-gray-700 mb-1">Asignar a usuario</label>
                            <select name="id_usuario" id="id_usuario" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('id_usuario') border-red-500 @enderror">
                                <option value="">Yo (administrador)</option>
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->id_usuario }}" {{ old('id_usuario') == $u->id_usuario ? 'selected' : '' }}>{{ $u->nombre_completo }} ({{ $u->run }})</option>
                                @endforeach
                            </select>
                            @error('id_usuario')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="dia" class="block text-sm font-medium text-gray-700 mb-1">Fecha <span class="text-red-500">*</span></label>
                            <input type="date" name="dia" id="dia" value="{{ old('dia', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('dia') border-red-500 @enderror">
                            @error('dia')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="hora" class="block text-sm font-medium text-gray-700 mb-1">Hora <span class="text-red-500">*</span></label>
                            <input type="time" name="hora" id="hora" value="{{ old('hora', date('H:i')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('hora') border-red-500 @enderror">
                            @error('hora')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div>
                            <label for="novedad" class="block text-sm font-medium text-gray-700 mb-1">Novedad / Descripción</label>
                            <textarea name="novedad" id="novedad" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('novedad') border-red-500 @enderror" placeholder="Qué ocurrió...">{{ old('novedad') }}</textarea>
                            @error('novedad')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="accion" class="block text-sm font-medium text-gray-700 mb-1">Acción tomada</label>
                            <textarea name="accion" id="accion" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('accion') border-red-500 @enderror" placeholder="Qué se hizo...">{{ old('accion') }}</textarea>
                            @error('accion')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="resultado" class="block text-sm font-medium text-gray-700 mb-1">Resultado</label>
                            <textarea name="resultado" id="resultado" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('resultado') border-red-500 @enderror" placeholder="Resultado o estado final...">{{ old('resultado') }}</textarea>
                            @error('resultado')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fotos / evidencias (máx. 4)</label>
                        <input type="file" name="imagenes[]" multiple accept="image/jpeg,image/png,image/heic" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        @error('imagenes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition font-medium">
                            Guardar novedad
                        </button>
                        <a href="{{ route('admin.novedades.index') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('sucursal_id').addEventListener('change', function() {
    var sucId = this.value;
    var opts = document.querySelectorAll('#sector_id option[data-sucursal]');
    opts.forEach(function(o) {
        o.style.display = o.getAttribute('data-sucursal') === sucId ? '' : 'none';
    });
    document.getElementById('sector_id').value = '';
});
// Inicializar visibilidad de sectores
document.getElementById('sucursal_id').dispatchEvent(new Event('change'));
</script>
@endsection
