<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
            'imei' => 'required|string|size:15',
        ]);

        $rut = $this->limpiarRut($request->rut);
        
        // Validar formato básico del RUT (con guión)
        if (!preg_match('/^[0-9]{7,8}-[0-9kK]$/', $rut)) {
            return back()->withErrors([
                'rut' => 'El formato del RUT no es válido. Use el formato: 12.345.678-9',
            ])->onlyInput('rut');
        }

        // Validar formato del IMEI (15 dígitos)
        if (!preg_match('/^[0-9]{15}$/', $request->imei)) {
            return back()->withErrors([
                'imei' => 'El IMEI debe contener exactamente 15 dígitos numéricos.',
            ])->onlyInput('rut');
        }
        
        $user = User::where('rut', $rut)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Verificar si el IMEI está permitido
            if (!\App\Models\ImeiPermitido::isPermitido($request->imei)) {
                return back()->withErrors([
                    'imei' => 'Su dispositivo no está autorizado para acceder a esta aplicación.',
                ])->onlyInput('rut');
            }

            // Actualizar el IMEI del usuario si es diferente
            if ($user->imei !== $request->imei) {
                $user->update([
                    'imei' => $request->imei,
                    'imei_verificado' => true,
                ]);
            } else {
                $user->update(['imei_verificado' => true]);
            }

            Auth::login($user);
            $request->session()->regenerate();
            
            return redirect()->intended('/dashboard');
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
