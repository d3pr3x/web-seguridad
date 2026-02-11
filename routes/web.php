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
use App\Http\Controllers\AccionController;
use App\Http\Controllers\ReporteEspecialController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\Admin\SectorController as AdminSectorController;
use App\Http\Controllers\Admin\NovedadController as AdminNovedadController;
use App\Http\Controllers\Admin\ReporteEspecialController as AdminReporteEspecialController;
use App\Http\Controllers\UsuarioAccionController;
use App\Http\Controllers\UsuarioReporteController;
use App\Http\Controllers\UsuarioRondaController;
use App\Http\Controllers\RondaEscaneoController;
use App\Http\Controllers\Admin\PuntoRondaController;
use App\Http\Controllers\Admin\RondaQrController;
use App\Http\Controllers\Admin\RondaReporteController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

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

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// API para verificar requisitos de dispositivo
Route::post('/api/verificar-dispositivo', [LoginController::class, 'verificarDispositivo'])->name('api.verificar-dispositivo');

// Rutas protegidas (requieren autenticación)
// NOTA: Validación por IMEI desactivada temporalmente
Route::middleware(['auth'])->group(function () {
    // Perfil de usuario (sin verificación de sucursal - acceso siempre permitido)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // Rutas que requieren verificación de sucursal
    Route::middleware(['verificar.sucursal'])->group(function () {
        // Portal de Supervisor
        Route::get('/supervisor', [SupervisorController::class, 'index'])->name('supervisor.index');
        
        // Portal de Administrador
        Route::get('/administrador', [AdministradorController::class, 'index'])->name('administrador.index');
        
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
            
            // Documentos personales
            Route::get('/documentos', [\App\Http\Controllers\UsuarioDocumentoController::class, 'index'])->name('documentos.index');
            Route::get('/documentos/crear', [\App\Http\Controllers\UsuarioDocumentoController::class, 'create'])->name('documentos.create');
            Route::post('/documentos', [\App\Http\Controllers\UsuarioDocumentoController::class, 'store'])->name('documentos.store');
            Route::get('/documentos/{documento}', [\App\Http\Controllers\UsuarioDocumentoController::class, 'show'])->name('documentos.show');

            // Rondas QR (guardia: instrucciones y listado de escaneos del día)
            Route::get('/ronda', [UsuarioRondaController::class, 'index'])->name('ronda.index');
        });

        // Escaneo de QR de ronda (el guardia abre esta URL al escanear; debe estar autenticado)
        Route::get('/ronda/escanear/{codigo}', [RondaEscaneoController::class, 'escanear'])->name('ronda.escanear');
        
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
        Route::get('/informes/{id}/pdf', [InformeController::class, 'pdf'])->name('informes.pdf');
        Route::post('/informes/{id}/aprobar', [InformeController::class, 'aprobar'])->name('informes.aprobar');
        Route::post('/informes/{id}/rechazar', [InformeController::class, 'rechazar'])->name('informes.rechazar');
        Route::post('/informes/{id}/reenviar', [InformeController::class, 'reenviar'])->name('informes.reenviar');
        
        // Días trabajados
        Route::get('/dias-trabajados', [DiaTrabajadoController::class, 'index'])->name('dias-trabajados.index');
        Route::get('/dias-trabajados/create', [DiaTrabajadoController::class, 'create'])->name('dias-trabajados.create');
        Route::post('/dias-trabajados', [DiaTrabajadoController::class, 'store'])->name('dias-trabajados.store');
        Route::get('/dias-trabajados/{id}/edit', [DiaTrabajadoController::class, 'edit'])->name('dias-trabajados.edit');
        Route::put('/dias-trabajados/{id}', [DiaTrabajadoController::class, 'update'])->name('dias-trabajados.update');
        Route::delete('/dias-trabajados/{id}', [DiaTrabajadoController::class, 'destroy'])->name('dias-trabajados.destroy');
        
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
        
        // Supervisor - Documentos Personales
        Route::prefix('supervisor')->name('supervisor.')->group(function () {
            Route::get('/documentos', [\App\Http\Controllers\Supervisor\DocumentoPersonalController::class, 'index'])->name('documentos.index');
            Route::get('/documentos/usuarios', [\App\Http\Controllers\Supervisor\DocumentoPersonalController::class, 'usuarios'])->name('documentos.usuarios');
            Route::get('/documentos/usuario/{user}', [\App\Http\Controllers\Supervisor\DocumentoPersonalController::class, 'usuarioDocumentos'])->name('documentos.usuario');
            Route::get('/documentos/{documento}', [\App\Http\Controllers\Supervisor\DocumentoPersonalController::class, 'show'])->name('documentos.show');
            Route::put('/documentos/{documento}/aprobar', [\App\Http\Controllers\Supervisor\DocumentoPersonalController::class, 'aprobar'])->name('documentos.aprobar');
            Route::put('/documentos/{documento}/rechazar', [\App\Http\Controllers\Supervisor\DocumentoPersonalController::class, 'rechazar'])->name('documentos.rechazar');
        });
        
        // Administración
        Route::prefix('admin')->name('admin.')->group(function () {
            // Gestión de usuarios
            Route::get('usuarios', [AdminUserController::class, 'index'])->name('usuarios.index');
            Route::get('usuarios/crear', [AdminUserController::class, 'create'])->name('usuarios.create');
            Route::post('usuarios', [AdminUserController::class, 'store'])->name('usuarios.store');
            Route::get('usuarios/{usuario}/editar', [AdminUserController::class, 'edit'])->name('usuarios.edit');
            Route::put('usuarios/{usuario}', [AdminUserController::class, 'update'])->name('usuarios.update');

            // Documentos Personales
            Route::get('/documentos', [\App\Http\Controllers\Admin\DocumentoPersonalController::class, 'index'])->name('documentos.index');
            Route::get('/documentos/usuarios', [\App\Http\Controllers\Admin\DocumentoPersonalController::class, 'usuarios'])->name('documentos.usuarios');
            Route::get('/documentos/usuario/{user}', [\App\Http\Controllers\Admin\DocumentoPersonalController::class, 'usuarioDocumentos'])->name('documentos.usuario');
            Route::get('/documentos/{documento}', [\App\Http\Controllers\Admin\DocumentoPersonalController::class, 'show'])->name('documentos.show');
            Route::put('/documentos/{documento}/aprobar', [\App\Http\Controllers\Admin\DocumentoPersonalController::class, 'aprobar'])->name('documentos.aprobar');
            Route::put('/documentos/{documento}/rechazar', [\App\Http\Controllers\Admin\DocumentoPersonalController::class, 'rechazar'])->name('documentos.rechazar');
            
            Route::get('/reportes-diarios', [ReporteDiarioController::class, 'index'])->name('reportes-diarios');
            Route::get('/reportes-diarios/exportar', [ReporteDiarioController::class, 'exportar'])->name('reportes-diarios.exportar');
            Route::get('/calculo-sueldos', [CalculoSueldoController::class, 'index'])->name('calculo-sueldos');
            Route::get('/calculo-sueldos/exportar', [CalculoSueldoController::class, 'exportar'])->name('calculo-sueldos.exportar');
            
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
            
            // Gestión de sectores por sucursal
            Route::get('sectores', [AdminSectorController::class, 'index'])->name('sectores.index');
            Route::get('sectores/sucursal/{sucursal}', [AdminSectorController::class, 'show'])->name('sectores.show');
            Route::get('sectores/crear', [AdminSectorController::class, 'create'])->name('sectores.create');
            Route::post('sectores', [AdminSectorController::class, 'store'])->name('sectores.store');
            Route::get('sectores/{sector}/editar', [AdminSectorController::class, 'edit'])->name('sectores.edit');
            Route::put('sectores/{sector}', [AdminSectorController::class, 'update'])->name('sectores.update');
            Route::delete('sectores/{sector}', [AdminSectorController::class, 'destroy'])->name('sectores.destroy');
            Route::patch('sectores/{sector}/toggle', [AdminSectorController::class, 'toggle'])->name('sectores.toggle');
            
            // Gestión de novedades (admin)
            Route::get('novedades', [AdminNovedadController::class, 'index'])->name('novedades.index');
            Route::get('novedades/{accion}', [AdminNovedadController::class, 'show'])->name('novedades.show');
            
            // Gestión de reportes especiales (admin)
            Route::get('reportes-especiales', [AdminReporteEspecialController::class, 'index'])->name('reportes-especiales.index');
            Route::get('reportes-especiales/{reporteEspecial}', [AdminReporteEspecialController::class, 'show'])->name('reportes-especiales.show');
            Route::patch('reportes-especiales/{reporteEspecial}/estado', [AdminReporteEspecialController::class, 'updateEstado'])->name('reportes-especiales.update-estado');

            // Puntos de ronda (QR) y reporte de escaneos
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
    });
});
