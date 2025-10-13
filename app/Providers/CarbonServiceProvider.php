<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class CarbonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configurar Carbon para usar español
        Carbon::setLocale('es');
        
        // Configurar zona horaria por defecto
        date_default_timezone_set('America/Santiago');
    }
}
