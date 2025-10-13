@extends('layouts.app')

@section('title', 'Generar Informe de Incidente')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-file-pdf me-2"></i>Generar Informe de Incidente
            </h1>
            <a href="{{ route('reportes.show', $reporte->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Volver al Reporte
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clipboard-list me-1"></i>Datos del Informe
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('informes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="reporte_id" value="{{ $reporte->id }}">
                    
                    <!-- Información del reporte base -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i>Información del Reporte Base</h6>
                        <p class="mb-0">
                            <strong>Tarea:</strong> {{ $reporte->tarea->nombre }}<br>
                            <strong>Fecha del reporte:</strong> {{ $reporte->created_at->format('d/m/Y H:i') }}<br>
                            <strong>Usuario:</strong> {{ $reporte->user->nombre_completo }}
                        </p>
                    </div>

                    <!-- Hora del incidente -->
                    <div class="mb-3">
                        <label for="hora" class="form-label">
                            <i class="fas fa-clock me-1"></i>Hora del Incidente <span class="text-danger">*</span>
                        </label>
                        <input type="time" class="form-control @error('hora') is-invalid @enderror" 
                               id="hora" name="hora" value="{{ old('hora') }}" required>
                        @error('hora')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Descripción del hecho -->
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">
                            <i class="fas fa-align-left me-1"></i>Descripción o Detalle del Hecho <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" name="descripcion" rows="4" 
                                  placeholder="Describa detalladamente lo ocurrido..." required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Lesionados -->
                    <div class="mb-3">
                        <label for="lesionados" class="form-label">
                            <i class="fas fa-user-injured me-1"></i>Lesionados <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('lesionados') is-invalid @enderror" 
                                  id="lesionados" name="lesionados" rows="3" 
                                  placeholder="Describa si hubo lesionados, sus datos y estado..." required>{{ old('lesionados') }}</textarea>
                        @error('lesionados')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Acciones inmediatas -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-tasks me-1"></i>Acciones Inmediatas <span class="text-danger">*</span>
                        </label>
                        <p class="text-muted small">Seleccione todas las acciones que se realizaron:</p>
                        
                        <div class="row">
                            @php
                                $opcionesAcciones = [
                                    'Se notificó al supervisor inmediatamente',
                                    'Se activó el protocolo de emergencia',
                                    'Se evacuó el área afectada',
                                    'Se contactó con servicios de emergencia',
                                    'Se tomó evidencia fotográfica del incidente',
                                    'Se aisló la zona de riesgo',
                                    'Se proporcionó primeros auxilios a los lesionados',
                                    'Se coordinó con el personal de seguridad',
                                    'Se documentó el incidente en el libro de novedades',
                                    'Se notificó a la gerencia de operaciones'
                                ];
                            @endphp
                            
                            @foreach($opcionesAcciones as $index => $accion)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="acciones_inmediatas[]" 
                                               value="{{ $accion }}" 
                                               id="accion_{{ $index }}"
                                               {{ in_array($accion, old('acciones_inmediatas', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="accion_{{ $index }}">
                                            {{ $accion }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @error('acciones_inmediatas')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Conclusiones -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-clipboard-check me-1"></i>Conclusiones <span class="text-danger">*</span>
                        </label>
                        <p class="text-muted small">Seleccione todas las conclusiones que aplican:</p>
                        
                        <div class="row">
                            @php
                                $opcionesConclusiones = [
                                    'El incidente fue causado por factores humanos',
                                    'El incidente fue causado por condiciones ambientales',
                                    'El incidente fue causado por fallas en el equipo',
                                    'Se requieren medidas preventivas adicionales',
                                    'Se debe capacitar al personal involucrado',
                                    'Se deben revisar los procedimientos de seguridad',
                                    'El incidente no tuvo consecuencias graves',
                                    'Se requiere investigación adicional',
                                    'Se implementarán medidas correctivas inmediatas',
                                    'El personal actuó según los protocolos establecidos'
                                ];
                            @endphp
                            
                            @foreach($opcionesConclusiones as $index => $conclusion)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="conclusiones[]" 
                                               value="{{ $conclusion }}" 
                                               id="conclusion_{{ $index }}"
                                               {{ in_array($conclusion, old('conclusiones', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="conclusion_{{ $index }}">
                                            {{ $conclusion }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @error('conclusiones')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Fotografías del Reporte Base -->
                    @if($reporte->imagenes && count($reporte->imagenes) > 0)
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-images me-1"></i>Fotografías del Reporte Base
                            </label>
                            <p class="text-muted small">Estas fotografías se incluirán automáticamente en el informe:</p>
                            
                            <div class="row">
                                @foreach($reporte->imagenes as $index => $imagen)
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <img src="{{ asset('storage/' . $imagen) }}" 
                                                 class="card-img-top" 
                                                 style="height: 150px; object-fit: cover;"
                                                 alt="Imagen del reporte">
                                            <div class="card-body p-2">
                                                <small class="text-muted">
                                                    Foto {{ $index + 1 }} del reporte
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Set Fotográfico Adicional -->
                    <div class="mb-4">
                        <label for="fotografias" class="form-label">
                            <i class="fas fa-camera me-1"></i>Set Fotográfico Adicional
                        </label>
                        <p class="text-muted small">
                            Subir fotografías adicionales para el informe 
                            @if($reporte->imagenes && count($reporte->imagenes) > 0)
                                (se sumarán a las {{ count($reporte->imagenes) }} fotos del reporte base):
                            @else
                                (máximo 4 por hoja en el PDF):
                            @endif
                        </p>
                        
                        <input type="file" class="form-control @error('fotografias') is-invalid @enderror" 
                               id="fotografias" name="fotografias[]" 
                               accept="image/*" multiple>
                        
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Formatos permitidos: JPEG, PNG, JPG, GIF, WEBP. Máximo 15MB por imagen.
                            @if($reporte->imagenes && count($reporte->imagenes) > 0)
                                <br><strong>Total de fotos en el informe:</strong> {{ count($reporte->imagenes) }} (del reporte) + las que subas aquí.
                            @endif
                        </div>
                        
                        @error('fotografias')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('reportes.show', $reporte->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-pdf me-1"></i>Generar Informe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-1"></i>Información
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    <strong>Reporte ID:</strong><br>
                    #{{ $reporte->id }}
                </p>
                <p class="card-text">
                    <strong>Tarea:</strong><br>
                    {{ $reporte->tarea->nombre }}
                </p>
                <p class="card-text">
                    <strong>Usuario:</strong><br>
                    {{ $reporte->user->nombre_completo }}
                </p>
                <p class="card-text">
                    <strong>RUT:</strong><br>
                    {{ $reporte->user->rut }}
                </p>
                <p class="card-text">
                    <strong>Sucursal:</strong><br>
                    {{ $reporte->user->sucursal }}
                </p>
                <p class="card-text">
                    <strong>Fecha del reporte:</strong><br>
                    {{ $reporte->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-lightbulb me-1"></i>Consejos
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small">
                    <li><i class="fas fa-check text-success me-1"></i> Sea específico en la descripción</li>
                    <li><i class="fas fa-check text-success me-1"></i> Incluya todos los detalles relevantes</li>
                    <li><i class="fas fa-check text-success me-1"></i> Seleccione todas las acciones realizadas</li>
                    <li><i class="fas fa-check text-success me-1"></i> Las fotografías ayudan a documentar</li>
                    <li><i class="fas fa-check text-success me-1"></i> El informe será generado en PDF</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
