<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blacklist extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blacklists';

    protected $fillable = [
        'rut',
        'patente',
        'motivo',
        'fecha_inicio',
        'fecha_fin',
        'activo',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'activo' => 'boolean',
        ];
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Registros activos: activo=true y (sin fecha_fin o fecha_fin > hoy).
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true)
            ->where(function ($q) {
                $q->whereNull('fecha_fin')
                    ->orWhere('fecha_fin', '>', now()->toDateString());
            });
    }

    /**
     * Buscar por RUT o patente.
     */
    public function scopeByRutOrPatente($query, string $valor)
    {
        $valor = trim($valor);
        return $query->where(function ($q) use ($valor) {
            $q->where('rut', $valor)->orWhere('patente', $valor);
        });
    }
}
