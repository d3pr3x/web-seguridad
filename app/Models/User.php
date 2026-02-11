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
        'perfil',
        'apellido',
        'fecha_nacimiento',
        'domicilio',
        'sucursal_id',
        'browser_fingerprint',
        'dispositivo_verificado',
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
            'dispositivo_verificado' => 'boolean',
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
     * Relación con acciones (novedades)
     */
    public function acciones()
    {
        return $this->hasMany(\App\Models\Accion::class);
    }

    /**
     * Relación con reportes especiales
     */
    public function reportesEspeciales()
    {
        return $this->hasMany(\App\Models\ReporteEspecial::class);
    }

    /**
     * Relación con documentos personales
     */
    public function documentosPersonales()
    {
        return $this->hasMany(DocumentoPersonal::class);
    }

    /**
     * Relación con escaneos de ronda QR
     */
    public function rondaEscaneos()
    {
        return $this->hasMany(RondaEscaneo::class);
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
     * Verificar si el dispositivo del usuario está permitido
     */
    public function isDispositivoPermitido()
    {
        if (!$this->browser_fingerprint) {
            return false;
        }

        return \App\Models\DispositivoPermitido::isPermitido($this->browser_fingerprint);
    }

    /**
     * Verificar si el usuario tiene una sucursal asignada
     */
    public function tieneSucursal()
    {
        return !is_null($this->sucursal_id);
    }

    /**
     * Verificar si el usuario es administrador
     * Perfil 1 = Administrador
     */
    public function esAdministrador()
    {
        return $this->perfil === 1;
    }

    /**
     * Verificar si el usuario es supervisor (incluye supervisor-usuario)
     * Perfil 2 = Supervisor
     * Perfil 3 = Supervisor-Usuario
     */
    public function esSupervisor()
    {
        return in_array($this->perfil, [2, 3]);
    }

    /**
     * Obtener el nombre del perfil
     */
    public function getNombrePerfilAttribute()
    {
        $perfiles = [
            1 => 'Administrador',
            2 => 'Supervisor',
            3 => 'Supervisor-Usuario',
            4 => 'Usuario',
        ];
        
        return $perfiles[$this->perfil] ?? 'Desconocido';
    }

    /**
     * Verificar si el usuario es supervisor-usuario
     */
    public function esSupervisorUsuario()
    {
        return $this->perfil === 3;
    }

    /**
     * Verificar si el usuario es solo usuario
     */
    public function esUsuario()
    {
        return $this->perfil === 4;
    }
}
