<?php

namespace App\Models;

use App\Models\Concerns\HasActivoScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Empresa (cliente): nivel superior. Usa modalidad de jerarquía.
 * Las instalaciones heredan la modalidad desde la empresa.
 */
class Empresa extends Model
{
    use HasActivoScope, SoftDeletes;

    protected $table = 'empresas';

    protected function activoColumn(): string
    {
        return 'activa';
    }

    protected $fillable = [
        'modalidad_id',
        'nombre',
        'codigo',
        'razon_social',
        'rut',
        'direccion',
        'comuna',
        'ciudad',
        'region',
        'telefono',
        'email',
        'activa',
        'modulos_activos',
    ];

    protected function casts(): array
    {
        return [
            'activa' => 'boolean',
            'modulos_activos' => 'array',
        ];
    }

    /**
     * Indica si esta empresa tiene habilitado el módulo (clave).
     * Si modulos_activos es null, se permiten todos los módulos globalmente habilitados.
     * Si es array, solo las claves presentes están habilitadas.
     */
    public function permiteModulo(string $clave): bool
    {
        $lista = $this->modulos_activos;
        if ($lista === null || $lista === []) {
            return true;
        }

        return in_array($clave, $lista, true);
    }

    public function modalidad()
    {
        return $this->belongsTo(ModalidadJerarquia::class, 'modalidad_id');
    }

    public function sucursales()
    {
        return $this->hasMany(Sucursal::class, 'empresa_id');
    }

    public function scopeActivas($query)
    {
        return $this->scopeActivos($query);
    }
}
