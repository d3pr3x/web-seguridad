<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'color',
        'activa',
        'categoria',
    ];

    protected function casts(): array
    {
        return [
            'activa' => 'boolean',
        ];
    }

    /**
     * Relación con detalles de tarea
     */
    public function detalles()
    {
        return $this->hasMany(TareaDetalle::class)->orderBy('orden');
    }

    /**
     * Relación con reportes
     */
    public function reportes()
    {
        return $this->hasMany(Reporte::class);
    }

    /**
     * Scope para tareas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    /**
     * Scope para tareas por categoría
     */
    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Obtener categorías disponibles
     */
    public static function getCategorias()
    {
        return [
            'novedades_servicio' => 'Novedades del Servicio',
            'reporte_incidentes' => 'Reporte de Incidentes'
        ];
    }

    /**
     * Obtener el nombre formateado de la categoría
     */
    public function getCategoriaFormateadaAttribute()
    {
        return self::getCategorias()[$this->categoria] ?? $this->categoria;
    }

    /**
     * Verificar si es categoría de novedades
     */
    public function isNovedadesServicio()
    {
        return $this->categoria === 'novedades_servicio';
    }

    /**
     * Verificar si es categoría de incidentes
     */
    public function isReporteIncidentes()
    {
        return $this->categoria === 'reporte_incidentes';
    }
}
