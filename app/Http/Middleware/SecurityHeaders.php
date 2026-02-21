<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self)');
        $response->headers->set('Content-Security-Policy', $this->getCsp());

        if (app()->environment('production') && $request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }

    private function getCsp(): string
    {
        // unsafe-inline/unsafe-eval: muchas vistas usan <script> inline (Alpine, lógica ad-hoc).
        // Eliminarlos requiere nonce por request o mover todo a JS externo; ver REPORTE-FINAL-SEGURIDAD-FASE2.md.
        $scriptSrc = "'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net";
        $styleSrc = "'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com https://cdnjs.cloudflare.com";
        $fontSrc = "'self' data: https://cdn.jsdelivr.net https://fonts.gstatic.com https://cdnjs.cloudflare.com";
        $connectSrc = "'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com https://fonts.gstatic.com";

        if (! app()->environment('production')) {
            // Vite dev server: solo 127.0.0.1 y localhost (sin [::1], no válido en CSP en muchos navegadores).
            // Usar server.host 127.0.0.1 en vite.config.js para que las URLs coincidan con la URL de la app.
            $viteHttp = 'http://127.0.0.1:5173 http://localhost:5173';
            $viteWs = 'ws://127.0.0.1:5173 ws://localhost:5173';
            $scriptSrc .= ' ' . $viteHttp;
            $styleSrc .= ' ' . $viteHttp;
            $connectSrc .= ' ' . $viteHttp . ' ' . $viteWs;
        }

        return "default-src 'self'; script-src {$scriptSrc}; style-src {$styleSrc}; img-src 'self' data: https: blob:; font-src {$fontSrc}; connect-src {$connectSrc}; frame-ancestors 'self'; base-uri 'self'; form-action 'self'";
    }
}
