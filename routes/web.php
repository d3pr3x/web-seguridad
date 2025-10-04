<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\DiaTrabajadoController;
use App\Http\Controllers\Admin\ReporteDiarioController;
use App\Http\Controllers\Admin\CalculoSueldoController;
use App\Http\Controllers\Admin\ImeiPermitidoController;
use App\Http\Controllers\ProfileController;

// Ruta principal - redirigir al login o dashboard según autenticación
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas (requieren autenticación y IMEI válido)
Route::middleware(['auth', 'validar.imei'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Tareas
    Route::get('/tareas/{id}', [TareaController::class, 'show'])->name('tareas.show');
    
    // Reportes
    Route::post('/reportes', [ReporteController::class, 'store'])->name('reportes.store');
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/{id}', [ReporteController::class, 'show'])->name('reportes.show');
    
    // Días trabajados
    Route::get('/dias-trabajados', [DiaTrabajadoController::class, 'index'])->name('dias-trabajados.index');
    Route::get('/dias-trabajados/create', [DiaTrabajadoController::class, 'create'])->name('dias-trabajados.create');
    Route::post('/dias-trabajados', [DiaTrabajadoController::class, 'store'])->name('dias-trabajados.store');
    Route::get('/dias-trabajados/{id}/edit', [DiaTrabajadoController::class, 'edit'])->name('dias-trabajados.edit');
    Route::put('/dias-trabajados/{id}', [DiaTrabajadoController::class, 'update'])->name('dias-trabajados.update');
    Route::delete('/dias-trabajados/{id}', [DiaTrabajadoController::class, 'destroy'])->name('dias-trabajados.destroy');
    
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // Administración
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/reportes-diarios', [ReporteDiarioController::class, 'index'])->name('reportes-diarios');
        Route::get('/reportes-diarios/exportar', [ReporteDiarioController::class, 'exportar'])->name('reportes-diarios.exportar');
        Route::get('/calculo-sueldos', [CalculoSueldoController::class, 'index'])->name('calculo-sueldos');
        Route::get('/calculo-sueldos/exportar', [CalculoSueldoController::class, 'exportar'])->name('calculo-sueldos.exportar');
        
        // Gestión de IMEIs permitidos
        Route::resource('imeis', ImeiPermitidoController::class);
        Route::patch('/imeis/{imei}/toggle', [ImeiPermitidoController::class, 'toggle'])->name('imeis.toggle');
    });
});
