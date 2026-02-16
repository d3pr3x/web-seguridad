<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo Usuario (tabla usuarios). PK: id_usuario, identificador: run (ej. 987403M).
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $primaryKey = 'id_usuario';

    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'run',
        'nombre_completo',
        'rango',
        'email',
        'clave',
        'fecha_nacimiento',
        'domicilio',
        'rol_id',
        'sucursal_id',
        'browser_fingerprint',
        'dispositivo_verificado',
    ];

    protected $hidden = [
        'clave',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verificado_en' => 'datetime',
            'fecha_nacimiento' => 'date',
            'clave' => 'hashed',
            'dispositivo_verificado' => 'boolean',
        ];
    }

    public function getAuthPassword()
    {
        return $this->clave;
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function rol()
    {
        return $this->belongsTo(RolUsuario::class, 'rol_id');
    }

    public function diasTrabajados()
    {
        return $this->hasMany(DiaTrabajado::class, 'id_usuario');
    }

    public function reportes()
    {
        return $this->hasMany(Reporte::class, 'id_usuario');
    }

    public function acciones()
    {
        return $this->hasMany(Accion::class, 'id_usuario');
    }

    public function reportesEspeciales()
    {
        return $this->hasMany(ReporteEspecial::class, 'id_usuario');
    }

    public function documentosPersonales()
    {
        return $this->hasMany(DocumentoPersonal::class, 'id_usuario');
    }

    public function rondaEscaneos()
    {
        return $this->hasMany(RondaEscaneo::class, 'id_usuario');
    }

    public function ingresosRegistrados()
    {
        return $this->hasMany(Ingreso::class, 'id_guardia');
    }

    public function esGuardiaControlAcceso()
    {
        return $this->rol && $this->rol->slug === 'GUARDIA';
    }

    public function getNombreCompletoAttribute($value)
    {
        return $value ?? $this->attributes['nombre_completo'] ?? '';
    }

    public function getNombreSucursalAttribute()
    {
        return $this->sucursal ? $this->sucursal->nombre : 'Sin sucursal';
    }

    public function isDispositivoPermitido()
    {
        if (!$this->browser_fingerprint) {
            return false;
        }
        return DispositivoPermitido::isPermitido($this->browser_fingerprint);
    }

    public function tieneSucursal()
    {
        return !is_null($this->sucursal_id);
    }

    public function esAdministrador()
    {
        return $this->rol && $this->rol->slug === 'ADMIN';
    }

    public function esSupervisor()
    {
        return $this->rol && in_array($this->rol->slug, ['SUPERVISOR', 'SUPERVISOR_USUARIO', 'USUARIO_SUPERVISOR']);
    }

    public function getNombrePerfilAttribute()
    {
        return $this->rol ? $this->rol->nombre : 'Sin rol';
    }

    public function esSupervisorUsuario()
    {
        return $this->rol && $this->rol->slug === 'SUPERVISOR_USUARIO';
    }

    public function esUsuarioSupervisor()
    {
        return $this->rol && $this->rol->slug === 'USUARIO_SUPERVISOR';
    }

    public function esUsuario()
    {
        return $this->rol && $this->rol->slug === 'USUARIO';
    }

    // ─── Accesos por perfil (5 niveles: Admin, Supervisor, Supervisor-Usuario, Usuario-Supervisor, Usuario/Guardia) ───

    /** Niveles operativos: Guardia, 2do jefe, Jefe turno (Admin no ve Control de acceso, Rondas QR ni Mis reportes). */
    public function puedeVerControlAcceso(): bool
    {
        return $this->esUsuario() || $this->esUsuarioSupervisor() || $this->esSupervisorUsuario();
    }

    public function puedeVerRondasQR(): bool
    {
        return $this->puedeVerControlAcceso();
    }

    public function puedeVerMisReportes(): bool
    {
        return $this->puedeVerControlAcceso();
    }

    /** Reporte por sucursal: 2do jefe, Jefe turno, Jefe contrato y Admin. */
    public function puedeVerReporteSucursal(): bool
    {
        return $this->esUsuarioSupervisor() || $this->esSupervisorUsuario() || $this->esSupervisor() || $this->esAdministrador();
    }

    /** Vista Supervisión (aprobar documentos, novedades, todos los reportes): Jefe turno, Jefe contrato y Admin. */
    public function puedeVerSupervision(): bool
    {
        return $this->esSupervisorUsuario() || $this->esSupervisor() || $this->esAdministrador();
    }

    /** Todos los reportes / Reporte escaneos QR: Jefe turno, Jefe contrato y Admin. */
    public function puedeVerReportesEstadisticasCompletos(): bool
    {
        return $this->esSupervisorUsuario() || $this->esSupervisor() || $this->esAdministrador();
    }

    /** Reportes diarios: solo Admin. */
    public function puedeVerReportesDiarios(): bool
    {
        return $this->esAdministrador();
    }

    /** Gestión (usuarios, dispositivos, ubicaciones, sectores, puntos ronda): solo Admin. */
    public function puedeVerGestion(): bool
    {
        return $this->esAdministrador();
    }

    public function getRouteKeyName(): string
    {
        return 'id_usuario';
    }
}
