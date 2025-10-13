<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    protected $fillable = [
        'reporte_id',
        'numero_informe',
        'hora',
        'descripcion',
        'lesionados',
        'acciones_inmediatas',
        'conclusiones',
        'fotografias',
        'estado',
        'fecha_aprobacion',
        'aprobado_por',
        'comentarios_aprobacion',
    ];

    protected function casts(): array
    {
        return [
            'acciones_inmediatas' => 'array',
            'conclusiones' => 'array',
            'fotografias' => 'array',
            'fecha_aprobacion' => 'datetime',
        ];
    }

    /**
     * Relación con reporte
     */
    public function reporte()
    {
        return $this->belongsTo(Reporte::class);
    }

    /**
     * Scope para informes por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Obtener fecha formateada
     */
    public function getFechaFormateadaAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    /**
     * Obtener hora formateada
     */
    public function getHoraFormateadaAttribute()
    {
        return date('H:i', strtotime($this->hora));
    }

    /**
     * Obtener acciones inmediatas como string
     */
    public function getAccionesInmediatasTextoAttribute()
    {
        return implode('; ', $this->acciones_inmediatas);
    }

    /**
     * Obtener conclusiones como string
     */
    public function getConclusionesTextoAttribute()
    {
        return implode('; ', $this->conclusiones);
    }

    /**
     * Obtener número total de fotografías
     */
    public function getTotalFotografiasAttribute()
    {
        return $this->fotografias ? count($this->fotografias) : 0;
    }

    /**
     * Verificar si el informe está aprobado
     */
    public function isAprobado()
    {
        return $this->estado === 'aprobado';
    }

    /**
     * Verificar si el informe está pendiente de revisión
     */
    public function isPendienteRevision()
    {
        return $this->estado === 'pendiente_revision';
    }

    /**
     * Verificar si el informe fue rechazado
     */
    public function isRechazado()
    {
        return $this->estado === 'rechazado';
    }

    /**
     * Obtener el estado formateado
     */
    public function getEstadoFormateadoAttribute()
    {
        return match($this->estado) {
            'pendiente_revision' => 'Pendiente de Revisión',
            'aprobado' => 'Aprobado',
            'rechazado' => 'Rechazado',
            default => ucfirst($this->estado)
        };
    }

    /**
     * Obtener la fecha de aprobación formateada
     */
    public function getFechaAprobacionFormateadaAttribute()
    {
        return $this->fecha_aprobacion ? $this->fecha_aprobacion->format('d/m/Y H:i') : null;
    }
}
