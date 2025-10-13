<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoPersonal extends Model
{
    use HasFactory;

    protected $table = 'documentos_personales';

    protected $fillable = [
        'user_id',
        'tipo_documento',
        'imagen_frente',
        'imagen_reverso',
        'estado',
        'motivo_rechazo',
        'aprobado_por',
        'aprobado_en',
        'es_cambio',
        'documento_anterior_id',
    ];

    protected $casts = [
        'aprobado_en' => 'datetime',
        'es_cambio' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aprobador()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function documentoAnterior()
    {
        return $this->belongsTo(DocumentoPersonal::class, 'documento_anterior_id');
    }

    public function getNombreTipoAttribute()
    {
        $nombres = [
            'cedula_identidad' => 'Cédula de Identidad',
            'licencia_conductor' => 'Licencia de Conductor',
            'certificado_antecedentes' => 'Certificado de Antecedentes',
            'certificado_os10' => 'Certificado de O.S. 10',
        ];

        return $nombres[$this->tipo_documento] ?? $this->tipo_documento;
    }

    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'pendiente' => ['color' => 'yellow', 'texto' => 'Pendiente'],
            'aprobado' => ['color' => 'green', 'texto' => 'Aprobado'],
            'rechazado' => ['color' => 'red', 'texto' => 'Rechazado'],
        ];

        return $badges[$this->estado] ?? ['color' => 'gray', 'texto' => 'Desconocido'];
    }
}


