<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accion extends Model
{
    use SoftDeletes;

    protected $table = 'acciones';
    
    protected $fillable = [
        'id_usuario',
        'sucursal_id',
        'sector_id',
        'tipo',
        'tipo_hecho',
        'importancia',
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

    /** Hechos/categorías para filtrar novedades (Punto 1). */
    public static function hechos(): array
    {
        return [
            'incidente' => 'Incidente',
            'observacion' => 'Observación',
            'informacion' => 'Información',
            'delito' => 'Delito',
            'accidente' => 'Accidente',
        ];
    }

    /** Niveles de importancia (Punto 15). */
    public static function nivelesImportancia(): array
    {
        return [
            'cotidiana' => 'Cotidiana',
            'importante' => 'Importante',
            'critica' => 'Crítica',
        ];
    }

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

    /**
     * Scope por tipo de hecho (Punto 1)
     */
    public function scopePorTipoHecho($query, $tipoHecho)
    {
        if (blank($tipoHecho)) {
            return $query;
        }
        return $query->where('tipo_hecho', $tipoHecho);
    }

    /**
     * Scope por importancia (Punto 15): importante / cotidiana
     */
    public function scopePorImportancia($query, $importancia)
    {
        if (blank($importancia)) {
            return $query;
        }
        return $query->where('importancia', $importancia);
    }

    public function getNombreHechoAttribute(): ?string
    {
        return $this->tipo_hecho ? (self::hechos()[$this->tipo_hecho] ?? $this->tipo_hecho) : null;
    }

    public function getNombreImportanciaAttribute(): ?string
    {
        return $this->importancia ? (self::nivelesImportancia()[$this->importancia] ?? $this->importancia) : null;
    }

    /** Reporte especial generado desde esta acción (elevación, Punto 6). */
    public function reporteEspecial()
    {
        return $this->hasOne(\App\Models\ReporteEspecial::class, 'accion_id');
    }
}




