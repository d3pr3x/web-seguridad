<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ingreso extends Model
{
    use HasFactory;

    protected $table = 'ingresos';

    protected $fillable = [
        'tipo',
        'rut',
        'nombre',
        'patente',
        'id_guardia',
        'fecha_ingreso',
        'fecha_salida',
        'estado',
        'alerta_blacklist',
        'ip_ingreso',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'fecha_ingreso' => 'datetime',
            'fecha_salida' => 'datetime',
            'alerta_blacklist' => 'boolean',
        ];
    }

    public function guardia(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_guardia', 'id_usuario');
    }

    public function scopeByGuardia($query, $guardiaId)
    {
        return $query->where('id_guardia', $guardiaId);
    }

    public function scopeRecientes($query, int $dias = 7)
    {
        return $query->where('fecha_ingreso', '>=', now()->subDays($dias));
    }

    public function scopeIngresados($query)
    {
        return $query->where('estado', 'ingresado');
    }
}
