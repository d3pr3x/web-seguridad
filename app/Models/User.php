<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'rut',
        'apellido',
        'fecha_nacimiento',
        'domicilio',
        'sucursal_id',
        'imei',
        'imei_verificado',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'fecha_nacimiento' => 'date',
            'password' => 'hashed',
            'imei_verificado' => 'boolean',
        ];
    }

    /**
     * Relación con sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relación con días trabajados
     */
    public function diasTrabajados()
    {
        return $this->hasMany(DiaTrabajado::class);
    }

    /**
     * Relación con reportes
     */
    public function reportes()
    {
        return $this->hasMany(Reporte::class);
    }

    /**
     * Obtener el nombre completo del usuario
     */
    public function getNombreCompletoAttribute()
    {
        return $this->name . ' ' . $this->apellido;
    }

    /**
     * Obtener el nombre de la sucursal
     */
    public function getNombreSucursalAttribute()
    {
        return $this->sucursal ? $this->sucursal->nombre : 'Sin sucursal';
    }

    /**
     * Verificar si el IMEI del usuario está permitido
     */
    public function isImeiPermitido()
    {
        if (!$this->imei) {
            return false;
        }

        return \App\Models\ImeiPermitido::isPermitido($this->imei);
    }
}
