<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            \App\Http\Middleware\LoadUserRole::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        $middleware->alias([
            'validar.imei' => \App\Http\Middleware\ValidarImei::class,
            'verificar.sucursal' => \App\Http\Middleware\VerificarSucursal::class,
            'guardia.control_acceso' => \App\Http\Middleware\EnsureGuardiaControlAcceso::class,
            'module' => \App\Http\Middleware\EnsureModuleEnabled::class,
            'context.consistency' => \App\Http\Middleware\EnsureContextConsistency::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (Throwable $e, \Illuminate\Http\Request $request) {
            \App\Exceptions\ForbiddenAccessLogger::logIfForbidden($e, $request);
            return null;
        });
    })->create();
