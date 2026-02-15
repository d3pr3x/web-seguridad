@extends('layouts.usuario')

@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <x-usuario.sidebar />
    <div class="flex-1 lg:mr-64">
        <x-usuario.header />
        <x-usuario.mobile-menu />
        <div class="container mx-auto px-4 py-6 max-w-7xl">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Revisar Documento</h1>
                    <p class="text-muted">{{ $documento->nombre_tipo }}</p>
                </div>
                <a href="{{ route('supervisor.documentos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Información del Usuario -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Información del Usuario
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Nombre Completo</label>
                        <p class="fw-bold mb-0">{{ $documento->user->nombre_completo }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">RUT</label>
                        <p class="fw-bold mb-0">{{ $documento->user->run }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Sucursal</label>
                        <p class="fw-bold mb-0">{{ $documento->user->nombre_sucursal }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Perfil</label>
                        <p class="mb-0">
                            @if($documento->user->esAdministrador())
                                <span class="badge bg-purple">Administrador</span>
                            @elseif($documento->user->esSupervisor())
                                <span class="badge bg-info">{{ $documento->user->nombre_perfil }}</span>
                            @else
                                <span class="badge bg-success">Usuario</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Estado del Documento -->
            <div class="card">
                <div class="card-header bg-{{ $documento->estado_badge['color'] == 'yellow' ? 'warning' : $documento->estado_badge['color'] }}
                    {{ $documento->estado_badge['color'] == 'yellow' ? 'text-dark' : 'text-white' }}">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Estado del Documento
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Tipo de Documento</label>
                        <p class="fw-bold mb-0">{{ $documento->nombre_tipo }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Estado</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $documento->estado_badge['color'] == 'yellow' ? 'warning text-dark' : $documento->estado_badge['color'] }}">
                                {{ $documento->estado_badge['texto'] }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Fecha de Envío</label>
                        <p class="mb-0">{{ $documento->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    @if($documento->es_cambio)
                        <div class="mb-3">
                            <label class="text-muted small">Tipo de Solicitud</label>
                            <p class="mb-0">
                                <span class="badge bg-info">
                                    <i class="fas fa-sync-alt me-1"></i> Solicitud de Cambio
                                </span>
                            </p>
                        </div>
                    @endif

                    @if($documento->estado == 'aprobado')
                        <div class="mb-3">
                            <label class="text-muted small">Aprobado Por</label>
                            <p class="mb-0">{{ $documento->aprobador->nombre_completo ?? 'Sistema' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Fecha de Aprobación</label>
                            <p class="mb-0">{{ $documento->aprobado_en->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif

                    @if($documento->estado == 'rechazado' && $documento->motivo_rechazo)
                        <div class="alert alert-danger mb-0">
                            <strong>Motivo del Rechazo:</strong>
                            <p class="mb-0 mt-2">{{ $documento->motivo_rechazo }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Imágenes del Documento -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-images me-2"></i>
                        Imágenes del Documento
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Imagen Frente -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-image me-2"></i> Frente
                            </h6>
                            @if($documento->imagen_frente)
                                <a href="{{ Storage::url($documento->imagen_frente) }}" target="_blank">
                                    <img src="{{ Storage::url($documento->imagen_frente) }}" 
                                         alt="Frente del documento" 
                                         class="img-fluid rounded border border-2 hover-shadow"
                                         style="cursor: pointer;">
                                </a>
                                <p class="text-muted small text-center mt-2">Click para ampliar</p>
                            @else
                                <div class="bg-light rounded p-5 text-center">
                                    <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No disponible</p>
                                </div>
                            @endif
                        </div>

                        <!-- Imagen Reverso -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-image me-2"></i> Reverso
                            </h6>
                            @if($documento->imagen_reverso)
                                <a href="{{ Storage::url($documento->imagen_reverso) }}" target="_blank">
                                    <img src="{{ Storage::url($documento->imagen_reverso) }}" 
                                         alt="Reverso del documento" 
                                         class="img-fluid rounded border border-2 hover-shadow"
                                         style="cursor: pointer;">
                                </a>
                                <p class="text-muted small text-center mt-2">Click para ampliar</p>
                            @else
                                <div class="bg-light rounded p-5 text-center">
                                    <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones de Aprobación/Rechazo -->
            @if($documento->estado == 'pendiente')
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-tasks me-2"></i>
                        Acciones de Revisión
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Aprobar -->
                        <div class="col-md-6">
                            <div class="border border-success rounded p-4 h-100">
                                <h6 class="text-success mb-3">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Aprobar Documento
                                </h6>
                                <p class="text-muted small mb-3">
                                    Al aprobar este documento, quedará registrado en el sistema y el usuario podrá verlo en su perfil.
                                    @if($documento->es_cambio)
                                        El documento anterior será reemplazado automáticamente.
                                    @endif
                                </p>
                                <form action="{{ route('supervisor.documentos.aprobar', $documento->id) }}" method="POST" id="formAprobar">
                                    @csrf
                                    @method('PUT')
                                    <button type="button" class="btn btn-success w-100" onclick="confirmarAprobacion()">
                                        <i class="fas fa-check me-2"></i>
                                        Aprobar Documento
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Rechazar -->
                        <div class="col-md-6">
                            <div class="border border-danger rounded p-4 h-100">
                                <h6 class="text-danger mb-3">
                                    <i class="fas fa-times-circle me-2"></i>
                                    Rechazar Documento
                                </h6>
                                <button type="button" class="btn btn-danger w-100" onclick="confirmarRechazo()">
                                    <i class="fas fa-times me-2"></i>
                                    Rechazar Documento
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transform: scale(1.02);
}
</style>

<script>
function confirmarAprobacion() {
    Swal.fire({
        title: '¿Aprobar Documento?',
        text: 'El documento quedará registrado en el sistema y será visible para el usuario.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check me-2"></i>Sí, Aprobar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formAprobar').submit();
        }
    });
}

function confirmarRechazo() {
    Swal.fire({
        title: 'Rechazar Documento',
        html: '<textarea id="swal-motivo" class="form-control" rows="4" placeholder="Explica el motivo del rechazo..." required></textarea>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-times me-2"></i>Rechazar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        preConfirm: () => {
            const motivo = document.getElementById('swal-motivo').value;
            if (!motivo || motivo.trim() === '') {
                Swal.showValidationMessage('Debes escribir un motivo para el rechazo');
                return false;
            }
            return motivo;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear formulario dinámico
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('supervisor.documentos.rechazar', $documento->id) }}';
            
            // CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            // Method PUT
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);
            
            // Motivo
            const motivoInput = document.createElement('input');
            motivoInput.type = 'hidden';
            motivoInput.name = 'motivo_rechazo';
            motivoInput.value = result.value;
            form.appendChild(motivoInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
        </div>
    </div>
</div>
@endsection

