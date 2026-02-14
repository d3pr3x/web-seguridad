<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\DispositivoPermitido;
use App\Models\UbicacionPermitida;

class LoginController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $request->validate([
            'rut' => 'required|string|min:8|max:12',
            'password' => 'required|string',
            'browser_fingerprint' => 'required|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ]);

        $rut = $this->limpiarRut($request->rut);
        
        // Validar formato básico del RUT (con guión)
        if (!preg_match('/^[0-9]{7,8}-[0-9kK]$/', $rut)) {
            return back()->withErrors([
                'rut' => 'El formato del RUT no es válido. Use el formato: 12.345.678-9',
            ])->onlyInput('rut');
        }

        // Validar dispositivo primero
        if (!DispositivoPermitido::isPermitido($request->browser_fingerprint)) {
            return back()->withErrors([
                'dispositivo' => 'Su dispositivo no está autorizado para acceder a esta aplicación. Por favor, contacte al administrador.',
            ])->onlyInput('rut');
        }

        // Validar ubicación solo si el dispositivo lo requiere
        if (DispositivoPermitido::requiereUbicacion($request->browser_fingerprint)) {
            if (!$request->latitud || !$request->longitud) {
                return back()->withErrors([
                    'ubicacion' => 'No se pudo obtener su ubicación. Por favor, asegúrese de permitir el acceso a la geolocalización.',
                ])->onlyInput('rut');
            }

            $verificacionUbicacion = UbicacionPermitida::verificarUbicacion(
                $request->latitud,
                $request->longitud
            );

            if (!$verificacionUbicacion['permitido']) {
                $mensaje = 'No se encuentra en una ubicación autorizada para acceder al sistema.';
                if ($verificacionUbicacion['ubicacion']) {
                    $mensaje .= ' La ubicación más cercana (' . $verificacionUbicacion['ubicacion']->nombre . ') está a ' . $verificacionUbicacion['distancia'] . ' metros.';
                }
                
                return back()->withErrors([
                    'ubicacion' => $mensaje,
                ])->onlyInput('rut');
            }
        }
        
        $user = User::where('run', $rut)->first();

        if ($user && Hash::check($request->password, $user->clave)) {
            // Actualizar el fingerprint del dispositivo si es diferente
            if ($user->browser_fingerprint !== $request->browser_fingerprint) {
                $user->update([
                    'browser_fingerprint' => $request->browser_fingerprint,
                    'dispositivo_verificado' => true,
                ]);
            } else {
                $user->update(['dispositivo_verificado' => true]);
            }

            Auth::login($user);
            $request->session()->regenerate();
            
            // Verificar si el usuario tiene sucursal asignada (excepto administradores)
            if (!$user->esAdministrador() && !$user->tieneSucursal()) {
                return redirect()->route('profile.index')
                    ->with('warning', 'Bienvenido al sistema. Debe estar asignado a una sucursal para acceder a todas las funcionalidades. Por favor, contacte al administrador.');
            }
            
            // Redirigir según el perfil (sin usar intended para evitar redirecciones a URLs previas)
            if ($user->esAdministrador()) {
                return redirect()->route('administrador.index');
            } elseif ($user->esSupervisor()) {
                return redirect()->route('supervisor.index');
            } else {
                return redirect()->route('usuario.index');
            }
        }

        return back()->withErrors([
            'rut' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('rut');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    /**
     * Verificar si un dispositivo requiere validación de ubicación
     */
    public function verificarDispositivo(Request $request)
    {
        $request->validate([
            'browser_fingerprint' => 'required|string',
        ]);

        $dispositivo = DispositivoPermitido::where('browser_fingerprint', $request->browser_fingerprint)
            ->where('activo', true)
            ->first();

        if (!$dispositivo) {
            return response()->json([
                'autorizado' => false,
                'requiere_ubicacion' => true,
                'mensaje' => 'Dispositivo no autorizado'
            ]);
        }

        return response()->json([
            'autorizado' => true,
            'requiere_ubicacion' => $dispositivo->requiere_ubicacion,
            'mensaje' => $dispositivo->requiere_ubicacion 
                ? 'Dispositivo autorizado - Requiere ubicación GPS' 
                : 'Dispositivo autorizado - Sin restricción de ubicación'
        ]);
    }

    /**
     * Limpiar y formatear RUT
     */
    private function limpiarRut($rut)
    {
        // Remover solo los puntos, mantener el guión
        $rut = str_replace('.', '', $rut);
        
        // Convertir a mayúsculas
        $rut = strtoupper($rut);
        
        return $rut;
    }
}
