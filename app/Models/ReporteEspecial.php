<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteEspecial extends Model
{
    protected $table = 'reportes_especiales';
    
    protected $fillable = [
        'id_usuario',
        'accion_id',
        'sucursal_id',
        'sector_id',
        'tipo',
        'tipo_incidente_id',
        'dia',
        'hora',
        'novedad',
        'accion',
        'resultado',
        'imagenes',
        'latitud',
        'longitud',
        'precision',
        'estado',
        'comentarios_admin',
        'leido_por_id',
        'fecha_lectura',
    ];

    protected function casts(): array
    {
        return [
            'dia' => 'date',
            'imagenes' => 'array',
            'latitud' => 'decimal:8',
            'longitud' => 'decimal:8',
            'precision' => 'decimal:2',
            'fecha_lectura' => 'datetime',
        ];
    }

    /**
     * Tipos de reportes especiales disponibles
     */
    public static function tipos(): array
    {
        return [
            'incidentes' => 'Incidentes',
            'denuncia' => 'Denuncia',
            'detenido' => 'Detenido',
            'accion_sospechosa' => 'Acción Sospechosa',
        ];
    }

    /**
     * Obtener el nombre del tipo de reporte
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
     * Scope por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
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

    /** Novedad/acción desde la que se elevó este reporte (Punto 6). */
    public function accionOrigen()
    {
        return $this->belongsTo(Accion::class, 'accion_id');
    }

    /** Tipo de incidente dentro del grupo (Punto 2). */
    public function tipoIncidente()
    {
        return $this->belongsTo(TipoIncidente::class, 'tipo_incidente_id');
    }

    /** Usuario que marcó como leído (Punto 5). */
    public function leidoPor()
    {
        return $this->belongsTo(User::class, 'leido_por_id', 'id_usuario');
    }

    public function fueLeido(): bool
    {
        return $this->fecha_lectura !== null;
    }
}




