<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\DiaTrabajadoController;
use App\Http\Controllers\Admin\ReporteDiarioController;
use App\Http\Controllers\Admin\CalculoSueldoController;
use App\Http\Controllers\Admin\DispositivoPermitidoController;
use App\Http\Controllers\Admin\UbicacionPermitidaController;
use App\Http\Controllers\ReporteSucursalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\InicioUnificadoController;
use App\Http\Controllers\AccionController;
use App\Http\Controllers\ReporteEspecialController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\Admin\SectorController as AdminSectorController;
use App\Http\Controllers\Admin\NovedadController as AdminNovedadController;
use App\Http\Controllers\Admin\ReporteEspecialController as AdminReporteEspecialController;
use App\Http\Controllers\Admin\GruposIncidentesController as AdminGruposIncidentesController;
use App\Http\Controllers\UsuarioAccionController;
use App\Http\Controllers\UsuarioReporteController;
use App\Http\Controllers\UsuarioRondaController;
use App\Http\Controllers\RondaEscaneoController;
use App\Http\Controllers\Admin\PuntoRondaController;
use App\Http\Controllers\Admin\RondaQrController;
use App\Http\Controllers\Admin\RondaReporteController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AuditoriasController as AdminAuditoriasController;
use App\Http\Controllers\Admin\ClienteController as AdminClienteController;
use App\Http\Controllers\IngresosController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\PersonasController;

// Ruta principal - redirigir según autenticación y rol
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        
        // Redirigir según el perfil
        if ($user->esAdministrador()) {
            return redirect()->route('administrador.index');
        } elseif ($user->esSupervisor()) {
            return redirect()->route('supervisor.index');
        } else {
            return redirect()->route('usuario.index');
        }
    }
    return redirect()->route('login');
});

// Rutas de autenticación (rate limit login)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// API para verificar requisitos de dispositivo
Route::post('/api/verificar-dispositivo', [LoginController::class, 'verificarDispositivo'])->name('api.verificar-dispositivo');

// Vista previa del PDF de informe (sin auth, usa el primer informe de la BD)
Route::get('/informes-preview-pdf', [InformeController::class, 'previewPdf'])->name('informes.preview-pdf');

// Rutas protegidas (requieren autenticación)
// NOTA: Validación por IMEI desactivada temporalmente
Route::middleware(['auth'])->group(function () {
    // Descarga de archivos privados: auth + autorización + throttle + auditoría
    Route::get('/archivos-privados/documentos/{documento}/{lado}', [\App\Http\Controllers\ArchivoPrivadoController::class, 'documentoArchivo'])
        ->name('archivos-privados.documento')->where('lado', 'frente|reverso')->middleware('throttle:sensitive');
    Route::get('/archivos-privados/acciones/{accion}/imagen/{index}', [\App\Http\Controllers\ArchivoPrivadoController::class, 'accionImagen'])
        ->name('archivos-privados.accion')->where('index', '[0-9]+')->middleware('throttle:sensitive');
    Route::get('/archivos-privados/reportes/{reporte}/imagen/{index}', [\App\Http\Controllers\ArchivoPrivadoController::class, 'reporteImagen'])
        ->name('archivos-privados.reporte')->where('index', '[0-9]+')->middleware('throttle:sensitive');
    Route::get('/archivos-privados/reportes-especiales/{reporte_especial}/imagen/{index}', [\App\Http\Controllers\ArchivoPrivadoController::class, 'reporteEspecialImagen'])
        ->name('archivos-privados.reporte-especial')->where('index', '[0-9]+')->middleware('throttle:sensitive');
    Route::get('/archivos-privados/informes/{informe}/fotografia/{index}', [\App\Http\Controllers\ArchivoPrivadoController::class, 'informeFotografia'])
        ->name('archivos-privados.informe')->where('index', '[0-9]+')->middleware('throttle:sensitive');

    // Perfil de usuario (sin verificación de sucursal - acceso siempre permitido)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Control de acceso (QR cédula + OCR patente) – módulo activable en config/modules.php
    Route::middleware(['module:control_acceso'])->group(function () {
        Route::get('/qr-automatico', [IngresosController::class, 'qrAutomatico'])->name('qr-automatico');
        Route::prefix('ingresos')->name('ingresos.')->group(function () {
            Route::get('/', [IngresosController::class, 'index'])->name('index');
            Route::get('/escaner', [IngresosController::class, 'escaner'])->name('escaner');
            Route::get('/escaner-nuevo', [IngresosController::class, 'escanerNuevo'])->name('escaner-nuevo');
            Route::get('/entrada-manual', [IngresosController::class, 'entradaManual'])->name('entrada-manual');
            Route::get('/buscar-persona', [IngresosController::class, 'buscarPersona'])->name('buscar-persona');
            Route::get('/{id}/qr-salida', [IngresosController::class, 'qrSalida'])->name('qr-salida');
            Route::get('/{id}', [IngresosController::class, 'show'])->name('show');
            Route::post('/', [IngresosController::class, 'store'])->name('store');
            Route::post('/exportar-csv', [IngresosController::class, 'exportarCsv'])->name('exportar-csv');
            Route::post('/{id}/salida', [IngresosController::class, 'salida'])->name('salida');
        });
        Route::get('/blacklist', [BlacklistController::class, 'index'])->name('blacklist.index');
        Route::post('/blacklist', [BlacklistController::class, 'store'])->name('blacklist.store')->middleware('throttle:sensitive');
        Route::delete('/blacklist/{id}', [BlacklistController::class, 'destroy'])->name('blacklist.destroy')->middleware('throttle:sensitive');
        Route::patch('/blacklist/{id}/toggle', [BlacklistController::class, 'toggle'])->name('blacklist.toggle')->middleware('throttle:sensitive');
    });

    Route::get('/personas', [PersonasController::class, 'index'])->name('personas.index');
    Route::get('/personas/crear', [PersonasController::class, 'create'])->name('personas.create');
    Route::post('/personas', [PersonasController::class, 'store'])->name('personas.store');
    Route::get('/personas/{id}/editar', [PersonasController::class, 'edit'])->name('personas.edit');
    Route::put('/personas/{id}', [PersonasController::class, 'update'])->name('personas.update');
    Route::delete('/personas/{id}', [PersonasController::class, 'destroy'])->name('personas.destroy');

    // Rutas que requieren verificación de sucursal
    Route::middleware(['verificar.sucursal'])->group(function () {
        // Portal de Supervisor
        Route::get('/supervisor', [SupervisorController::class, 'index'])->name('supervisor.index');
        
        // Portal de Administrador
        Route::get('/administrador', [AdministradorController::class, 'index'])->name('administrador.index');

        // Vista de inicio unificada (pruebas) — una sola vista que discrimina por perfil
        Route::get('/inicio-unificado', [InicioUnificadoController::class, 'index'])->name('inicio-unificado.index');
        
        // Portal de Usuario
        Route::prefix('usuario')->name('usuario.')->group(function () {
            Route::get('/', [UsuarioController::class, 'index'])->name('index');
            
            // Perfil del usuario (solo lectura, excepto contraseña)
            Route::get('/perfil', [\App\Http\Controllers\UsuarioPerfilController::class, 'index'])->name('perfil.index');
            Route::put('/perfil/password', [\App\Http\Controllers\UsuarioPerfilController::class, 'updatePassword'])->name('perfil.password.update');
            
            // Acciones del usuario
            Route::get('/acciones', [UsuarioAccionController::class, 'index'])->name('acciones.index');
            Route::get('/acciones/crear', [UsuarioAccionController::class, 'create'])->name('acciones.create');
            Route::post('/acciones', [UsuarioAccionController::class, 'store'])->name('acciones.store');
            Route::get('/acciones/{accion}', [UsuarioAccionController::class, 'show'])->name('acciones.show');
            
            // Reportes especiales del usuario
            Route::get('/reportes', [UsuarioReporteController::class, 'index'])->name('reportes.index');
            Route::get('/reportes/crear', [UsuarioReporteController::class, 'create'])->name('reportes.create');
            Route::post('/reportes', [UsuarioReporteController::class, 'store'])->name('reportes.store');
            Route::get('/reportes/{reporteEspecial}', [UsuarioReporteController::class, 'show'])->name('reportes.show');
            
            // Historial completo
            Route::get('/historial', [\App\Http\Controllers\UsuarioHistorialController::class, 'index'])->name('historial.index');
            
            // Documentos personales (módulo documentos_guardias)
            Route::middleware(['module:documentos_guardias'])->group(function () {
                Route::get('/documentos', [\App\Http\Controllers\UsuarioDocumentoController::class, 'index'])->name('documentos.index');
                Route::get('/documentos/crear', [\App\Http\Controllers\UsuarioDocumentoController::class, 'create'])->name('documentos.create');
                Route::post('/documentos', [\App\Http\Controllers\UsuarioDocumentoController::class, 'store'])->name('documentos.store')->middleware('throttle:sensitive');
                Route::get('/documentos/{documento}', [\App\Http\Controllers\UsuarioDocumentoController::class, 'show'])->name('documentos.show');
            });

            // Rondas QR (módulo rondas_qr)
            Route::middleware(['module:rondas_qr'])->group(function () {
                Route::get('/ronda', [UsuarioRondaController::class, 'index'])->name('ronda.index');
                Route::get('/ronda/escaner', [UsuarioRondaController::class, 'escaner'])->name('ronda.escaner');
            });
        });

        // Escaneo de QR de ronda (módulo rondas_qr)
        Route::get('/ronda/escanear/{codigo}', [RondaEscaneoController::class, 'escanear'])->name('ronda.escanear')->middleware(['module:rondas_qr', 'throttle:ronda-scan']);
        
        // Tareas
        Route::get('/tareas/{id}', [TareaController::class, 'show'])->name('tareas.show');
        
        // Reportes
        Route::post('/reportes', [ReporteController::class, 'store'])->name('reportes.store');
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/{id}', [ReporteController::class, 'show'])->name('reportes.show');
        
        // Informes
        Route::get('/informes', [InformeController::class, 'index'])->name('informes.index');
        Route::get('/informes/create/{reporteId}', [InformeController::class, 'create'])->name('informes.create');
        Route::post('/informes', [InformeController::class, 'store'])->name('informes.store');
        Route::get('/informes/{id}', [InformeController::class, 'show'])->name('informes.show');
        Route::get('/informes/{id}/pdf', [InformeController::class, 'pdf'])->name('informes.pdf')->middleware('throttle:sensitive');
        Route::get('/informes/{id}/ver-pdf', [InformeController::class, 'verPdf'])->name('informes.ver-pdf')->middleware('throttle:sensitive');
        Route::post('/informes/{id}/aprobar', [InformeController::class, 'aprobar'])->name('informes.aprobar');
        Route::post('/informes/{id}/rechazar', [InformeController::class, 'rechazar'])->name('informes.rechazar');
        Route::post('/informes/{id}/reenviar', [InformeController::class, 'reenviar'])->name('informes.reenviar');
        
        // Días trabajados (módulo calculo_sueldos)
        Route::middleware(['module:calculo_sueldos'])->group(function () {
            Route::get('/dias-trabajados', [DiaTrabajadoController::class, 'index'])->name('dias-trabajados.index');
            Route::get('/dias-trabajados/create', [DiaTrabajadoController::class, 'create'])->name('dias-trabajados.create');
            Route::post('/dias-trabajados', [DiaTrabajadoController::class, 'store'])->name('dias-trabajados.store');
            Route::get('/dias-trabajados/{id}/edit', [DiaTrabajadoController::class, 'edit'])->name('dias-trabajados.edit');
            Route::put('/dias-trabajados/{id}', [DiaTrabajadoController::class, 'update'])->name('dias-trabajados.update');
            Route::delete('/dias-trabajados/{id}', [DiaTrabajadoController::class, 'destroy'])->name('dias-trabajados.destroy');
        });
        
        // Acciones del servicio
        Route::get('/acciones', [AccionController::class, 'index'])->name('acciones.index');
        Route::get('/acciones/crear', [AccionController::class, 'create'])->name('acciones.create');
        Route::post('/acciones', [AccionController::class, 'store'])->name('acciones.store');
        Route::get('/acciones/{accion}', [AccionController::class, 'show'])->name('acciones.show');
        Route::get('/sectores/sucursal/{sucursalId}', [AccionController::class, 'sectoresPorSucursal'])->name('sectores.por-sucursal');
        
        // Reportes especiales
        Route::get('/reportes-especiales', [ReporteEspecialController::class, 'index'])->name('reportes-especiales.index');
        Route::get('/reportes-especiales/crear', [ReporteEspecialController::class, 'create'])->name('reportes-especiales.create');
        Route::post('/reportes-especiales', [ReporteEspecialController::class, 'store'])->name('reportes-especiales.store');
        Route::get('/reportes-especiales/{reporteEspecial}', [ReporteEspecialController::class, 'show'])->name('reportes-especiales.show');
        Route::patch('/reportes-especiales/{reporteEspecial}/estado', [ReporteEspecialController::class, 'updateEstado'])->name('reportes-especiales.update-estado');
        
        // Supervisor - Documentos Personales (módulo documentos_guardias)
        Route::prefix('supervisor')->name('supervisor.')->middleware(['module:documentos_guardias'])->group(function () {
            Route::get('/documentos', [\App\Http\Controllers\Supervisor\DocumentoPersonalController::class, 'index'])->name('documentos.index');
            Route::get('/documentos/usuarios', [\App\Http\Controllers\Supervisor\DocumentoPersonalController::class, 'usuarios'])->name('documentos.usuarios');
            Route::get('/documentos/usuario/{user}', [\App\Http\Controllers\Supervisor\DocumentoPersonalController::class, 'usuarioDocumentos'])->name('documentos.usuario');
            Route::get('/documentos/{documento}', [\App\Http\Controllers\Supervisor\DocumentoPersonalController::class, 'show'])->name('documentos.show');
            Route::put('/documentos/{documento}/aprobar', [\App\Http\Controllers\Supervisor\DocumentoPersonalController::class, 'aprobar'])->name('documentos.aprobar')->middleware('throttle:sensitive');
            Route::put('/documentos/{documento}/rechazar', [\App\Http\Controllers\Supervisor\DocumentoPersonalController::class, 'rechazar'])->name('documentos.rechazar')->middleware('throttle:sensitive');
        });
        
        // Administración
        Route::prefix('admin')->name('admin.')->group(function () {
            // Vista Clientes: empresas e instalaciones (jerarquía)
            Route::get('clientes', [AdminClienteController::class, 'index'])->name('clientes.index');
            Route::get('clientes/crear', [AdminClienteController::class, 'create'])->name('clientes.create');
            Route::post('clientes', [AdminClienteController::class, 'store'])->name('clientes.store');
            Route::get('clientes/{cliente}/editar', [AdminClienteController::class, 'edit'])->name('clientes.edit');
            Route::put('clientes/{cliente}', [AdminClienteController::class, 'update'])->name('clientes.update');
            Route::delete('clientes/{cliente}', [AdminClienteController::class, 'destroy'])->name('clientes.destroy');
            Route::get('clientes/{cliente}/instalaciones', [AdminClienteController::class, 'instalaciones'])->name('clientes.instalaciones');
            Route::get('clientes/{cliente}/instalaciones/crear', [AdminClienteController::class, 'createInstalacion'])->name('clientes.instalaciones.create');
            Route::post('clientes/{cliente}/instalaciones', [AdminClienteController::class, 'storeInstalacion'])->name('clientes.instalaciones.store');
            Route::get('clientes/{cliente}/instalaciones/{sucursal}/editar', [AdminClienteController::class, 'editInstalacion'])->name('clientes.instalaciones.edit');
            Route::put('clientes/{cliente}/instalaciones/{sucursal}', [AdminClienteController::class, 'updateInstalacion'])->name('clientes.instalaciones.update');
            Route::delete('clientes/{cliente}/instalaciones/{sucursal}', [AdminClienteController::class, 'destroyInstalacion'])->name('clientes.instalaciones.destroy');

            // Gestión de usuarios
            Route::get('usuarios', [AdminUserController::class, 'index'])->name('usuarios.index');
            Route::get('usuarios/crear', [AdminUserController::class, 'create'])->name('usuarios.create');
            Route::post('usuarios', [AdminUserController::class, 'store'])->name('usuarios.store')->middleware('throttle:sensitive');
            Route::get('usuarios/{usuario}/editar', [AdminUserController::class, 'edit'])->name('usuarios.edit');
            Route::put('usuarios/{usuario}', [AdminUserController::class, 'update'])->name('usuarios.update')->middleware('throttle:sensitive');

            // Documentos Personales (módulo documentos_guardias)
            Route::middleware(['module:documentos_guardias'])->group(function () {
                Route::get('/documentos', [\App\Http\Controllers\Admin\DocumentoPersonalController::class, 'index'])->name('documentos.index');
                Route::get('/documentos/usuarios', [\App\Http\Controllers\Admin\DocumentoPersonalController::class, 'usuarios'])->name('documentos.usuarios');
                Route::get('/documentos/usuario/{user}', [\App\Http\Controllers\Admin\DocumentoPersonalController::class, 'usuarioDocumentos'])->name('documentos.usuario');
                Route::get('/documentos/{documento}', [\App\Http\Controllers\Admin\DocumentoPersonalController::class, 'show'])->name('documentos.show');
                Route::put('/documentos/{documento}/aprobar', [\App\Http\Controllers\Admin\DocumentoPersonalController::class, 'aprobar'])->name('documentos.aprobar')->middleware('throttle:sensitive');
                Route::put('/documentos/{documento}/rechazar', [\App\Http\Controllers\Admin\DocumentoPersonalController::class, 'rechazar'])->name('documentos.rechazar')->middleware('throttle:sensitive');
            });

            Route::get('/reportes-diarios', [ReporteDiarioController::class, 'index'])->name('reportes-diarios')->middleware('module:reportes_diarios');
            Route::get('/reportes-diarios/exportar', [ReporteDiarioController::class, 'exportar'])->name('reportes-diarios.exportar')->middleware('module:reportes_diarios');
            Route::get('/calculo-sueldos', [CalculoSueldoController::class, 'index'])->name('calculo-sueldos')->middleware('module:calculo_sueldos');
            Route::get('/calculo-sueldos/exportar', [CalculoSueldoController::class, 'exportar'])->name('calculo-sueldos.exportar')->middleware('module:calculo_sueldos');
            
            // Reporte por sucursal
            Route::get('/reporte-sucursal', [ReporteSucursalController::class, 'index'])->name('reporte-sucursal');
            Route::get('/reporte-sucursal/exportar', [ReporteSucursalController::class, 'exportar'])->name('reporte-sucursal.exportar');
            
            // Gestión de dispositivos permitidos
            Route::resource('dispositivos', DispositivoPermitidoController::class);
            Route::patch('/dispositivos/{dispositivo}/toggle', [DispositivoPermitidoController::class, 'toggle'])->name('dispositivos.toggle');
            Route::patch('/dispositivos/{dispositivo}/toggle-ubicacion', [DispositivoPermitidoController::class, 'toggleUbicacion'])->name('dispositivos.toggle-ubicacion');
            
            // Gestión de ubicaciones permitidas
            Route::resource('ubicaciones', UbicacionPermitidaController::class);
            Route::patch('/ubicaciones/{ubicacion}/toggle', [UbicacionPermitidaController::class, 'toggle'])->name('ubicaciones.toggle');
            
            // Vista Sectores: empresa → instalaciones → sectores
            Route::get('sectores', [AdminSectorController::class, 'index'])->name('sectores.index');
            Route::get('sectores/empresa/{empresa}', [AdminSectorController::class, 'porEmpresa'])->name('sectores.por-empresa');
            Route::get('sectores/sucursal/{sucursal}', [AdminSectorController::class, 'show'])->name('sectores.show');
            Route::get('sectores/crear', [AdminSectorController::class, 'create'])->name('sectores.create');
            Route::post('sectores', [AdminSectorController::class, 'store'])->name('sectores.store');
            Route::get('sectores/{sector}/editar', [AdminSectorController::class, 'edit'])->name('sectores.edit');
            Route::put('sectores/{sector}', [AdminSectorController::class, 'update'])->name('sectores.update');
            Route::delete('sectores/{sector}', [AdminSectorController::class, 'destroy'])->name('sectores.destroy');
            Route::patch('sectores/{sector}/toggle', [AdminSectorController::class, 'toggle'])->name('sectores.toggle');
            
            // Gestión de novedades (admin): listar, crear, ver detalle, elevar a reporte
            Route::get('novedades', [AdminNovedadController::class, 'index'])->name('novedades.index');
            Route::get('novedades/crear', [AdminNovedadController::class, 'create'])->name('novedades.create');
            Route::post('novedades', [AdminNovedadController::class, 'store'])->name('novedades.store');
            Route::get('novedades/{accion}', [AdminNovedadController::class, 'show'])->name('novedades.show');
            Route::post('novedades/{accion}/elevar-reporte', [AdminNovedadController::class, 'elevarAReporte'])->name('novedades.elevar-reporte');

            // Punto 2: Grupos de delitos/incidentes y tipos
            Route::get('grupos-incidentes', [AdminGruposIncidentesController::class, 'index'])->name('grupos-incidentes.index');
            
            // Gestión de reportes especiales (admin)
            Route::get('reportes-especiales', [AdminReporteEspecialController::class, 'index'])->name('reportes-especiales.index');
            Route::get('reportes-especiales/{reporteEspecial}', [AdminReporteEspecialController::class, 'show'])->name('reportes-especiales.show');
            Route::patch('reportes-especiales/{reporteEspecial}/estado', [AdminReporteEspecialController::class, 'updateEstado'])->name('reportes-especiales.update-estado');
            Route::post('reportes-especiales/{reporteEspecial}/marcar-leido', [AdminReporteEspecialController::class, 'marcarLeido'])->name('reportes-especiales.marcar-leido');

            // Puntos de ronda (QR) y reporte de escaneos (módulo rondas_qr)
            Route::middleware(['module:rondas_qr'])->group(function () {
                Route::get('rondas', [PuntoRondaController::class, 'index'])->name('rondas.index');
                Route::get('rondas/sucursal/{sucursal}', [PuntoRondaController::class, 'show'])->name('rondas.show');
                Route::get('rondas/crear', [PuntoRondaController::class, 'create'])->name('rondas.create');
                Route::post('rondas', [PuntoRondaController::class, 'store'])->name('rondas.store');
                Route::get('rondas/{punto}/editar', [PuntoRondaController::class, 'edit'])->name('rondas.edit');
                Route::put('rondas/{punto}', [PuntoRondaController::class, 'update'])->name('rondas.update');
                Route::delete('rondas/{punto}', [PuntoRondaController::class, 'destroy'])->name('rondas.destroy');
                Route::get('rondas/{punto}/qr', [RondaQrController::class, 'show'])->name('rondas.qr.show');
                Route::get('rondas/{punto}/qr/descargar', [RondaQrController::class, 'download'])->name('rondas.qr.download');
                Route::get('rondas-reporte', [RondaReporteController::class, 'index'])->name('rondas.reporte');
            });

            // Auditorías (solo lectura)
            Route::get('auditorias', [AdminAuditoriasController::class, 'index'])->name('auditorias.index');
            Route::get('auditorias/{auditoria}', [AdminAuditoriasController::class, 'show'])->name('auditorias.show');
        });
    });
});
