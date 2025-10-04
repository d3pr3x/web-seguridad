<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DiaTrabajado;
use App\Models\Sucursal;
use App\Models\Feriado;
use App\Models\ConfiguracionSueldo;
use Carbon\Carbon;

class CalculoSueldoController extends Controller
{
    /**
     * Mostrar cálculo de sueldos
     */
    public function index(Request $request)
    {
        $mes = $request->get('mes', Carbon::now()->format('Y-m'));
        $sucursalId = $request->get('sucursal_id');
        $sueldoBase = $request->get('sueldo_base', 50000); // Sueldo base diario
        
        $sucursales = Sucursal::activas()->get();
        
        // Obtener usuarios
        $usuarios = User::with(['sucursal', 'diasTrabajados' => function($query) use ($mes) {
            $query->whereRaw("TO_CHAR(fecha, 'YYYY-MM') = ?", [$mes]);
        }])
        ->when($sucursalId, function($query) use ($sucursalId) {
            return $query->where('sucursal_id', $sucursalId);
        })
        ->get();
        
        // Calcular sueldos
        $calculos = [];
        foreach ($usuarios as $usuario) {
            $calculo = $this->calcularSueldoUsuario($usuario, $mes, $sueldoBase);
            $calculos[] = $calculo;
        }
        
        // Estadísticas generales
        $totalEmpleados = count($calculos);
        $totalDiasTrabajados = collect($calculos)->sum('dias_trabajados');
        $totalSueldoBruto = collect($calculos)->sum('sueldo_bruto');
        $totalSueldoNeto = collect($calculos)->sum('sueldo_neto');
        
        return view('admin.calculo-sueldos', compact(
            'mes', 'sucursales', 'sucursalId', 'sueldoBase',
            'calculos', 'totalEmpleados', 'totalDiasTrabajados',
            'totalSueldoBruto', 'totalSueldoNeto'
        ));
    }
    
    /**
     * Calcular sueldo de un usuario
     */
    private function calcularSueldoUsuario($usuario, $mes, $sueldoBase)
    {
        $diasTrabajados = $usuario->diasTrabajados;
        $sueldoBruto = 0;
        $diasNormales = 0;
        $diasExtras = 0;
        $diasFeriados = 0;
        $diasSabados = 0;
        $diasDomingos = 0;
        
        foreach ($diasTrabajados as $dia) {
            $tipoDia = Feriado::getTipoDia($dia->fecha);
            $multiplicador = ConfiguracionSueldo::getMultiplicador($tipoDia);
            
            // Aplicar multiplicador del día + ponderación del usuario
            $multiplicadorFinal = $multiplicador * $dia->ponderacion;
            $sueldoDia = $sueldoBase * $multiplicadorFinal;
            $sueldoBruto += $sueldoDia;
            
            // Contar tipos de días
            switch ($tipoDia) {
                case 'habil':
                    $diasNormales++;
                    break;
                case 'feriado':
                    $diasFeriados++;
                    break;
                case 'sabado':
                    $diasSabados++;
                    break;
                case 'domingo':
                    $diasDomingos++;
                    break;
            }
            
            if ($dia->ponderacion > 1.0) {
                $diasExtras++;
            }
        }
        
        // Calcular descuentos (simplificado)
        $descuentos = $sueldoBruto * 0.20; // 20% de descuentos (impuestos, etc.)
        $sueldoNeto = $sueldoBruto - $descuentos;
        
        return [
            'usuario' => $usuario,
            'dias_trabajados' => $diasTrabajados->count(),
            'dias_normales' => $diasNormales,
            'dias_extras' => $diasExtras,
            'dias_feriados' => $diasFeriados,
            'dias_sabados' => $diasSabados,
            'dias_domingos' => $diasDomingos,
            'sueldo_bruto' => $sueldoBruto,
            'descuentos' => $descuentos,
            'sueldo_neto' => $sueldoNeto,
            'dias_detalle' => $diasTrabajados
        ];
    }
    
    /**
     * Exportar cálculo de sueldos
     */
    public function exportar(Request $request)
    {
        // Lógica para exportar a Excel/PDF
        return $this->index($request);
    }
}
