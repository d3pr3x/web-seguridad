<?php

namespace App\Providers;

use App\Models\Accion;
use App\Models\Empresa;
use App\Models\Ingreso;
use App\Models\Persona;
use App\Models\PuntoRonda;
use App\Models\Reporte;
use App\Models\ReporteEspecial;
use App\Models\Sector;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\Blacklist;
use App\Models\Informe;
use App\Models\DocumentoPersonal;
use App\Models\Tarea;
use App\Models\UbicacionPermitida;
use App\Models\DispositivoPermitido;
use App\Models\RolUsuario;
use App\Models\Permiso;
use App\Models\TareaDetalle;
use App\Models\Reunion;
use App\Observers\AuditoriaObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * Autorización basada únicamente en roles de BD (roles_usuario, rol_id).
     * No se usa Spatie ni paquetes de roles externos.
     */
    public function boot(): void
    {
        $this->registerRoleGates();
        $this->registerAuditoriaObservers();
        $this->configureRateLimiting();
        Event::listen(Login::class, \App\Listeners\UpdateSessionUserIdOnLogin::class);
        Route::bind('cliente', fn ($value) => \App\Models\Empresa::withTrashed()->findOrFail($value));
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('login', function ($request) {
            $key = $request->input('rut', $request->ip());
            return Limit::perMinute(5)->by($key)->response(function () {
                return response()->json(['message' => 'Demasiados intentos. Espere un minuto.'], 429);
            });
        });
        RateLimiter::for('sensitive', function ($request) {
            $key = ($request->user()?->id_usuario ?? 'guest') . '|' . $request->ip();
            return Limit::perMinute(30)->by($key);
        });
        RateLimiter::for('ronda-scan', function ($request) {
            return Limit::perMinute(60)->by($request->user()?->id_usuario ?: $request->ip());
        });
    }

    /**
     * Registra Gates que delegan en el rol del usuario (tabla roles_usuario).
     */
    private function registerRoleGates(): void
    {
        Gate::define('ver-control-acceso', fn ($user) => $user->puedeVerControlAcceso());
        Gate::define('ver-rondas-qr', fn ($user) => $user->puedeVerRondasQR());
        Gate::define('ver-mis-reportes', fn ($user) => $user->puedeVerMisReportes());
        Gate::define('ver-reporte-sucursal', fn ($user) => $user->puedeVerReporteSucursal());
        Gate::define('ver-supervision', fn ($user) => $user->puedeVerSupervision());
        Gate::define('ver-reportes-estadisticas', fn ($user) => $user->puedeVerReportesEstadisticasCompletos());
        Gate::define('ver-reportes-diarios', fn ($user) => $user->puedeVerReportesDiarios());
        Gate::define('ver-gestion', fn ($user) => $user->puedeVerGestion());

        Gate::define('es-admin', fn ($user) => $user->esAdministrador());
        Gate::define('es-supervisor', fn ($user) => $user->esSupervisor());
        Gate::define('es-usuario', fn ($user) => $user->esUsuario());
        Gate::define('es-guardia-control-acceso', fn ($user) => $user->esGuardiaControlAcceso());
    }

    private function registerAuditoriaObservers(): void
    {
        if (!Schema::hasTable('auditorias')) {
            return;
        }
        Empresa::observe(AuditoriaObserver::class);
        Sucursal::observe(AuditoriaObserver::class);
        Sector::observe(AuditoriaObserver::class);
        User::observe(AuditoriaObserver::class);
        Accion::observe(AuditoriaObserver::class);
        ReporteEspecial::observe(AuditoriaObserver::class);
        Ingreso::observe(AuditoriaObserver::class);
        Blacklist::observe(AuditoriaObserver::class);
        Persona::observe(AuditoriaObserver::class);
        PuntoRonda::observe(AuditoriaObserver::class);
        Reporte::observe(AuditoriaObserver::class);
        Informe::observe(AuditoriaObserver::class);
        DocumentoPersonal::observe(AuditoriaObserver::class);
        Tarea::observe(AuditoriaObserver::class);
        UbicacionPermitida::observe(AuditoriaObserver::class);
        DispositivoPermitido::observe(AuditoriaObserver::class);
        RolUsuario::observe(AuditoriaObserver::class);
        Permiso::observe(AuditoriaObserver::class);
        TareaDetalle::observe(AuditoriaObserver::class);
        Reunion::observe(AuditoriaObserver::class);
    }
}
