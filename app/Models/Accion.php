<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accion extends Model
{
    protected $table = 'acciones';
    
    protected $fillable = [
        'id_usuario',
        'sucursal_id',
        'sector_id',
        'tipo',
        'dia',
        'hora',
        'novedad',
        'accion',
        'resultado',
        'imagenes',
        'latitud',
        'longitud',
        'precision',
    ];

    protected function casts(): array
    {
        return [
            'dia' => 'date',
            'imagenes' => 'array',
            'latitud' => 'decimal:8',
            'longitud' => 'decimal:8',
            'precision' => 'decimal:2',
        ];
    }

    /**
     * Tipos de acciones disponibles
     */
    public static function tipos(): array
    {
        return [
            'inicio_servicio' => 'Inicio del Servicio',
            'rondas' => 'Rondas',
            'constancias' => 'Constancias',
            'concurrencia_autoridades' => 'Concurrencia de autoridades',
            'concurrencia_servicios' => 'Concurrencia de Servicios',
            'entrega_servicio' => 'Entrega del Servicio',
        ];
    }

    /**
     * Obtener el nombre del tipo de acción
     */
    public function getNombreTipoAttribute(): string
    {
        return self::tipos()[$this->tipo] ?? $this->tipo;
    }

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación con sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relación con sector
     */
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    /**
     * Scope por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope por fecha
     */
    public function scopePorFecha($query, $fecha)
    {
        return $query->whereDate('dia', $fecha);
    }

    /**
     * Scope por sucursal
     */
    public function scopePorSucursal($query, $sucursalId)
    {
        return $query->where('sucursal_id', $sucursalId);
    }
}




