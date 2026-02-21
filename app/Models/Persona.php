<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Registro histórico de personas (RUT + nombre y datos opcionales).
 * Cualquier módulo que capture RUT y nombre debe llamar a Persona::registrarOActualizar()
 * para que la persona quede en este historial: ingresos (escaner), guardias/usuarios,
 * futuras visitas anunciadas, etc.
 */
class Persona extends Model
{
    use SoftDeletes;

    protected $table = 'personas';

    protected $fillable = [
        'rut',
        'pasaporte',
        'nombre',
        'telefono',
        'email',
        'empresa',
        'notas',
        'sucursal_id',
    ];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id', 'id');
    }

    /**
     * Normalizar RUT al formato almacenado (cuerpo + guión + DV, sin puntos).
     */
    public static function normalizarRut(string $rut): string
    {
        $rut = preg_replace('/[^0-9kK]/', '', strtoupper($rut));
        if (strlen($rut) >= 2) {
            $rut = substr($rut, 0, -1) . '-' . substr($rut, -1);
        }
        return $rut;
    }

    /**
     * Buscar persona por RUT (acepta con o sin puntos/guión).
     */
    public static function buscarPorRut(string $rut): ?self
    {
        $rutNorm = static::normalizarRut($rut);
        if (strlen($rutNorm) < 10) {
            return null;
        }
        return static::where('rut', $rutNorm)->first();
    }

    /**
     * Registrar o actualizar persona en el historial (cualquier módulo que capture RUT + nombre debe llamar esto).
     * Crea la persona si no existe; si existe, actualiza el nombre y los campos opcionales que se pasen.
     *
     * @param string $rut RUT (con o sin puntos/guión)
     * @param string $nombre Nombre completo (obligatorio para crear/actualizar)
     * @param array $extra ['telefono' => ?, 'email' => ?, 'empresa' => ?, 'notas' => ?, 'sucursal_id' => ?]
     * @return self|null Persona creada/actualizada o null si RUT inválido o nombre vacío
     */
    public static function registrarOActualizar(string $rut, string $nombre, array $extra = []): ?self
    {
        $nombre = trim($nombre);
        if ($nombre === '') {
            return null;
        }
        $rutNorm = static::normalizarRut($rut);
        if (strlen($rutNorm) < 10) {
            return null;
        }
        $persona = static::firstOrCreate(
            ['rut' => $rutNorm],
            ['nombre' => $nombre]
        );
        if (!$persona->wasRecentlyCreated) {
            $persona->nombre = $nombre;
            foreach (['telefono', 'email', 'empresa', 'notas', 'sucursal_id'] as $key) {
                if (array_key_exists($key, $extra)) {
                    $persona->{$key} = $extra[$key];
                }
            }
            $persona->save();
        } else {
            foreach (['telefono', 'email', 'empresa', 'notas', 'sucursal_id'] as $key) {
                if (array_key_exists($key, $extra)) {
                    $persona->{$key} = $extra[$key];
                }
            }
            $persona->save();
        }
        return $persona;
    }
}
