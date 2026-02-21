<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Construye el menú lateral dinámico según:
 * - Usuario autenticado
 * - Empresa activa (sucursal del usuario → empresa)
 * - Modalidad de la empresa
 * - Orden de roles en modalidad_roles (define orden de secciones del menú)
 * - Rol principal vs secundario: usuario_supervisor → Usuario principal, Supervisor en acordeón;
 *   supervisor_usuario → Supervisor principal, Usuario en acordeón.
 */
class MenuBuilder
{
    protected ?User $user = null;

    protected ?Empresa $empresa = null;

    /** @var array<string, int> tier (usuario|supervisor|admin) => orden en modalidad */
    protected array $tierOrden = [];

    /** @var string|null 'usuario'|'supervisor'|'admin' */
    protected ?string $tierPrincipal = null;

    /** @var string|null tier que va en acordeón para este rol */
    protected ?string $tierSecundario = null;

    public function __construct(?User $user = null)
    {
        $this->user = $user ?? auth()->user();
        if ($this->user) {
            $this->empresa = $this->getEmpresaActiva();
            $this->computeTierOrden();
            $this->computePrincipalSecundario();
        }
    }

    /**
     * Empresa activa en sesión: la de la sucursal del usuario.
     * Usamos getRelationValue('empresa') para obtener la relación y no la columna string 'empresa' de sucursales.
     */
    public function getEmpresaActiva(): ?Empresa
    {
        if (! $this->user || ! $this->user->sucursal_id) {
            return null;
        }
        $this->user->loadMissing('sucursal');
        $sucursal = $this->user->sucursal;
        if (! $sucursal) {
            return null;
        }
        $empresa = $sucursal->getRelationValue('empresa');

        return $empresa instanceof Empresa ? $empresa : null;
    }

    /**
     * Modalidad de la empresa activa (para orden de menú).
     */
    public function getModalidad()
    {
        if (! $this->empresa) {
            return null;
        }
        $this->empresa->loadMissing('modalidad');

        return $this->empresa->modalidad;
    }

    /**
     * Roles de la modalidad ordenados por modalidad_roles.orden.
     */
    public function getRolesOrdenados(): Collection
    {
        $modalidad = $this->getModalidad();
        if (! $modalidad) {
            return collect();
        }

        return $modalidad->rolesOrdenados()
            ->orderByPivot('orden')
            ->get();
    }

    /**
     * Orden por "tier" (usuario, supervisor, admin) desde modalidad_roles.
     * Menor orden = más arriba en la lista de roles; para menú mostramos primero el bloque "usuario".
     */
    private function computeTierOrden(): void
    {
        $this->tierOrden = ['usuario' => 999, 'supervisor' => 999, 'admin' => 999];
        $modalidad = $this->getModalidad();
        if (! $modalidad) {
            return;
        }
        $pivots = DB::table('modalidad_roles')
            ->join('roles_usuario', 'roles_usuario.id', '=', 'modalidad_roles.rol_id')
            ->where('modalidad_roles.modalidad_id', $modalidad->id)
            ->where('modalidad_roles.deleted_at', null)
            ->select('roles_usuario.slug', 'modalidad_roles.orden')
            ->get();
        foreach ($pivots as $row) {
            $slug = strtoupper($row->slug);
            $orden = (int) $row->orden;
            if (in_array($slug, ['USUARIO', 'USUARIO_SUPERVISOR', 'SUPERVISOR_USUARIO', 'GUARDIA'], true)) {
                if ($orden < $this->tierOrden['usuario']) {
                    $this->tierOrden['usuario'] = $orden;
                }
            }
            if (in_array($slug, ['SUPERVISOR', 'SUPERVISOR_USUARIO', 'USUARIO_SUPERVISOR'], true)) {
                if ($orden < $this->tierOrden['supervisor']) {
                    $this->tierOrden['supervisor'] = $orden;
                }
            }
            if (in_array($slug, ['ADMIN', 'ADMIN_CONTRATO'], true)) {
                if ($orden < $this->tierOrden['admin']) {
                    $this->tierOrden['admin'] = $orden;
                }
            }
        }
    }

    /**
     * Rol principal y secundario (para acordeón) según slug del rol del usuario.
     */
    private function computePrincipalSecundario(): void
    {
        $this->tierPrincipal = 'usuario';
        $this->tierSecundario = null;
        if (! $this->user || ! $this->user->rol) {
            return;
        }
        $slug = strtoupper($this->user->rol->slug ?? '');
        if ($slug === 'USUARIO_SUPERVISOR') {
            $this->tierPrincipal = 'usuario';
            $this->tierSecundario = 'supervisor';
        } elseif ($slug === 'SUPERVISOR_USUARIO') {
            $this->tierPrincipal = 'supervisor';
            $this->tierSecundario = 'usuario';
        } elseif (in_array($slug, ['SUPERVISOR', 'ADMIN', 'ADMIN_CONTRATO'], true)) {
            $this->tierPrincipal = $slug === 'ADMIN' || $slug === 'ADMIN_CONTRATO' ? 'admin' : 'supervisor';
            $this->tierSecundario = null;
        } else {
            $this->tierPrincipal = 'usuario';
        }
    }

    /**
     * Orden y etiquetas de secciones del menú (solo se muestran si tienen ítems).
     */
    protected function definicionSecciones(): array
    {
        return [
            ['key' => 'inicio', 'label' => ''],
            ['key' => 'operacion', 'label' => 'Operación'],
            ['key' => 'supervision', 'label' => 'Supervisión'],
            ['key' => 'infraestructura', 'label' => 'Infraestructura'],
            ['key' => 'personal', 'label' => 'Personal'],
        ];
    }

    /**
     * Definición estática de ítems del menú (key, tier, section, suborder, visibility).
     * Regla: cada rol solo ve lo que usa; secciones sin ítems visibles no se renderizan.
     */
    protected function definicionItems(): array
    {
        $items = [
            [
                'key' => 'inicio',
                'tier' => 'usuario',
                'section' => 'inicio',
                'suborder' => 0,
                'label' => 'Inicio',
                'icon' => 'fa-home',
                'route' => null,
                'routes_active' => ['administrador.index', 'supervisor.index', 'usuario.index'],
                'module' => null,
                'permission' => null,
                'type' => 'link',
            ],
            [
                'key' => 'control_acceso',
                'tier' => 'usuario',
                'section' => 'operacion',
                'suborder' => 1,
                'label' => 'Control de acceso',
                'icon' => 'fa-qrcode',
                'route' => 'ingresos.index',
                'routes_active' => ['ingresos.*', 'blacklist.*'],
                'module' => 'control_acceso',
                'permission' => 'puedeVerControlAcceso',
                'type' => 'link',
            ],
            [
                'key' => 'rondas_qr',
                'tier' => 'usuario',
                'section' => 'operacion',
                'suborder' => 2,
                'label' => 'Rondas QR',
                'icon' => 'fa-route',
                'route' => 'usuario.ronda.index',
                'routes_active' => ['usuario.ronda.*'],
                'module' => 'rondas_qr',
                'permission' => 'puedeVerRondasQR',
                'type' => 'link',
            ],
            [
                'key' => 'incidentes',
                'tier' => 'usuario',
                'section' => 'operacion',
                'suborder' => 3,
                'label' => 'Incidentes',
                'icon' => 'fa-exclamation-triangle',
                'route' => null,
                'routes_active' => ['usuario.acciones.*', 'usuario.reportes.*'],
                'module' => null,
                'permission' => 'puedeVerMisReportes',
                'type' => 'collapse',
                'children' => [
                    ['label' => 'Novedades', 'route' => 'usuario.acciones.index', 'routes_active' => ['usuario.acciones.*']],
                    ['label' => 'Reportes', 'route' => 'usuario.reportes.index', 'routes_active' => ['usuario.reportes.*']],
                ],
            ],
            [
                'key' => 'perfil',
                'tier' => 'usuario',
                'section' => 'personal',
                'suborder' => 0,
                'label' => 'Mi perfil',
                'icon' => 'fa-user',
                'route' => 'usuario.perfil.index',
                'routes_active' => ['usuario.perfil.*'],
                'module' => null,
                'permission' => null,
                'type' => 'link',
            ],
            [
                'key' => 'mis_documentos',
                'tier' => 'usuario',
                'section' => 'operacion',
                'suborder' => 5,
                'label' => 'Mis documentos',
                'icon' => 'fa-file-alt',
                'route' => null,
                'routes_active' => ['usuario.documentos.*'],
                'module' => 'documentos_guardias',
                'permission' => 'puedeVerMisReportes',
                'type' => 'collapse',
                'children' => [
                    ['label' => 'Ver mis documentos', 'route' => 'usuario.documentos.index', 'routes_active' => ['usuario.documentos.*']],
                ],
            ],
            // Admin: Operación en orden B.2 (solo admin; supervisor usa reportes_estadisticas)
            [
                'key' => 'operacion_reporte_qr',
                'tier' => 'admin',
                'section' => 'operacion',
                'suborder' => 0,
                'label' => 'Reporte escaneos QR',
                'icon' => 'fa-route',
                'route' => 'admin.rondas.reporte',
                'routes_active' => ['admin.rondas.reporte'],
                'module' => 'rondas_qr',
                'permission' => 'puedeVerReportesEstadisticasCompletos',
                'type' => 'link',
            ],
            [
                'key' => 'operacion_novedades',
                'tier' => 'admin',
                'section' => 'operacion',
                'suborder' => 1,
                'label' => 'Novedades',
                'icon' => 'fa-clipboard-list',
                'route' => 'admin.novedades.index',
                'routes_active' => ['admin.novedades.*'],
                'module' => null,
                'permission' => 'puedeVerSupervision',
                'type' => 'link',
            ],
            [
                'key' => 'operacion_reportes',
                'tier' => 'admin',
                'section' => 'operacion',
                'suborder' => 2,
                'label' => 'Reportes',
                'icon' => 'fa-exclamation-triangle',
                'route' => 'admin.reportes-especiales.index',
                'routes_active' => ['admin.reportes-especiales.*'],
                'module' => null,
                'permission' => 'puedeVerReportesEstadisticasCompletos',
                'type' => 'link',
            ],
            [
                'key' => 'operacion_reporte_sucursal',
                'tier' => 'admin',
                'section' => 'operacion',
                'suborder' => 3,
                'label' => 'Reporte por sucursal',
                'icon' => 'fa-building',
                'route' => 'admin.reporte-sucursal',
                'routes_active' => ['admin.reporte-sucursal'],
                'module' => null,
                'permission' => 'puedeVerReporteSucursal',
                'type' => 'link',
            ],
            [
                'key' => 'operacion_todos_reportes',
                'tier' => 'admin',
                'section' => 'operacion',
                'suborder' => 4,
                'label' => 'Todos los reportes',
                'icon' => 'fa-chart-bar',
                'route' => 'admin.reportes-especiales.index',
                'routes_active' => ['admin.reportes-especiales.*'],
                'module' => null,
                'permission' => 'puedeVerReportesEstadisticasCompletos',
                'type' => 'link',
            ],
            // Supervisor: Reportes y estadísticas (collapse; admin no lo ve)
            [
                'key' => 'reportes_estadisticas',
                'tier' => 'supervisor',
                'section' => 'operacion',
                'suborder' => 0,
                'label' => 'Reportes y estadísticas',
                'icon' => 'fa-chart-bar',
                'route' => null,
                'routes_active' => ['admin.reportes-especiales.*', 'admin.rondas.reporte', 'admin.reportes-diarios', 'admin.reporte-sucursal'],
                'module' => null,
                'permission' => null,
                'type' => 'collapse',
                'admin_hide' => true,
                'children' => [
                    ['label' => 'Reporte escaneos QR', 'route' => 'admin.rondas.reporte', 'routes_active' => ['admin.rondas.reporte'], 'permission' => 'puedeVerReportesEstadisticasCompletos', 'module' => 'rondas_qr'],
                    ['label' => 'Novedades', 'route' => 'admin.novedades.index', 'routes_active' => ['admin.novedades.*']],
                    ['label' => 'Reportes', 'route' => 'admin.reportes-especiales.index', 'routes_active' => ['admin.reportes-especiales.*'], 'permission' => 'puedeVerReportesEstadisticasCompletos'],
                    ['label' => 'Reporte por sucursal', 'route' => 'admin.reporte-sucursal', 'routes_active' => ['admin.reporte-sucursal'], 'permission' => 'puedeVerReporteSucursal'],
                    ['label' => 'Todos los reportes', 'route' => 'admin.reportes-especiales.index', 'routes_active' => ['admin.reportes-especiales.*'], 'permission' => 'puedeVerReportesEstadisticasCompletos'],
                    ['label' => 'Reportes diarios', 'route' => 'admin.reportes-diarios', 'routes_active' => ['admin.reportes-diarios'], 'permission' => 'puedeVerReportesDiarios', 'module' => 'reportes_diarios'],
                ],
            ],
            [
                'key' => 'supervision',
                'tier' => 'supervisor',
                'section' => 'supervision',
                'suborder' => 1,
                'label' => 'Supervisión',
                'icon' => 'fa-users',
                'route' => null,
                'routes_active' => ['admin.usuarios.*', 'admin.documentos.*', 'admin.novedades.*', 'supervisor.documentos.*'],
                'module' => null,
                'permission' => 'puedeVerSupervision',
                'type' => 'collapse',
                'children' => [
                    ['label' => 'Usuarios', 'route' => 'admin.usuarios.index', 'routes_active' => ['admin.usuarios.*'], 'admin_only' => true],
                    ['label' => 'Aprobar documentos', 'route' => null, 'routes_active' => ['admin.documentos.*', 'supervisor.documentos.*'], 'module' => 'documentos_guardias', 'route_supervisor' => 'supervisor.documentos.index', 'route_admin' => 'admin.documentos.index'],
                    ['label' => 'Novedades', 'route' => 'admin.novedades.index', 'routes_active' => ['admin.novedades.*']],
                    ['label' => 'Grupos de incidentes', 'route' => 'admin.grupos-incidentes.index', 'routes_active' => ['admin.grupos-incidentes.*'], 'admin_only' => true],
                    ['label' => 'Todos los reportes', 'route' => null, 'routes_active' => ['admin.reportes-especiales.*', 'reportes-especiales.*'], 'route_supervisor' => 'reportes-especiales.index', 'route_admin' => 'admin.reportes-especiales.index'],
                ],
            ],
            [
                'key' => 'gestion',
                'tier' => 'admin',
                'section' => 'infraestructura',
                'suborder' => 0,
                'label' => 'Gestión',
                'icon' => 'fa-cog',
                'route' => null,
                'routes_active' => ['admin.clientes.*', 'admin.dispositivos.*', 'admin.ubicaciones.*', 'admin.sectores.*', 'admin.rondas.*', 'admin.auditorias.*'],
                'module' => null,
                'permission' => 'puedeVerGestion',
                'type' => 'collapse',
                'children' => [
                    ['label' => 'Clientes', 'route' => 'admin.clientes.index', 'routes_active' => ['admin.clientes.*']],
                    ['label' => 'Dispositivos', 'route' => 'admin.dispositivos.index', 'routes_active' => ['admin.dispositivos.*']],
                    ['label' => 'Ubicaciones', 'route' => 'admin.ubicaciones.index', 'routes_active' => ['admin.ubicaciones.*']],
                    ['label' => 'Sectores', 'route' => 'admin.sectores.index', 'routes_active' => ['admin.sectores.*']],
                    ['label' => 'Puntos de ronda (QR)', 'route' => 'admin.rondas.index', 'routes_active' => ['admin.rondas.index', 'admin.rondas.show', 'admin.rondas.create', 'admin.rondas.edit'], 'module' => 'rondas_qr'],
                    ['label' => 'Auditorías', 'route' => 'admin.auditorias.index', 'routes_active' => ['admin.auditorias.*']],
                ],
            ],
        ];

        return $items;
    }

    /**
     * Ítems del menú filtrados por permisos y módulos, ordenados por modalidad_roles y con flag enAcordeon.
     *
     * @return array<int, array{key: string, label: string, icon: string, route: ?string, routes_active: array, type: string, enAcordeon: bool, orden: int, children?: array, module?: string}>
     */
    public function getItems(): array
    {
        if (! $this->user) {
            return [];
        }
        $def = $this->definicionItems();
        $items = [];
        foreach ($def as $entry) {
            if (! $this->isVisible($entry)) {
                continue;
            }
            $tier = $entry['tier'];
            // Orden: primero bloque usuario (mayor orden en modalidad_roles), luego supervisor, luego admin
            $tierOrd = $this->tierOrden[$tier] ?? 999;
            $orden = (1000 - $tierOrd) * 1000 + $entry['suborder'];
            $enAcordeon = ($this->tierSecundario !== null && $tier === $this->tierSecundario);
            $resolved = [
                'key' => $entry['key'],
                'label' => $entry['label'],
                'icon' => $entry['icon'],
                'route' => $this->resolveRoute($entry),
                'routes_active' => $entry['routes_active'],
                'type' => $entry['type'],
                'enAcordeon' => $enAcordeon,
                'orden' => $orden,
            ];
            if (isset($entry['children'])) {
                $resolved['children'] = $this->filterChildren($entry['children'], $entry);
            }
            if (isset($entry['module'])) {
                $resolved['module'] = $entry['module'];
            }
            $resolved['section'] = $entry['section'] ?? 'operacion';
            // No renderizar collapse sin ítems visibles (regla: solo ve lo que usa)
            if ($resolved['type'] === 'collapse' && empty($resolved['children'])) {
                continue;
            }
            $items[] = $resolved;
        }
        usort($items, fn ($a, $b) => $a['orden'] <=> $b['orden']);

        return $items;
    }

    protected function isVisible(array $entry): bool
    {
        if (! empty($entry['admin_hide']) && $this->user->esAdministrador()) {
            return false;
        }
        if ($entry['module'] && ! module_enabled_for_empresa($entry['module'], $this->empresa)) {
            return false;
        }
        if ($entry['permission'] && ! $this->user->{$entry['permission']}()) {
            return false;
        }
        if ($entry['key'] === 'reportes_estadisticas' && ! $this->user->puedeVerReporteSucursal() && ! $this->user->puedeVerReportesEstadisticasCompletos()) {
            return false;
        }

        return true;
    }

    protected function resolveRoute(array $entry): ?string
    {
        if ($entry['route'] ?? null) {
            return $entry['route'];
        }
        if ($entry['key'] === 'inicio') {
            return $this->user->esAdministrador() ? 'administrador.index' : ($this->user->esSupervisor() ? 'supervisor.index' : 'usuario.index');
        }

        return null;
    }

    protected function filterChildren(array $children, array $parent): array
    {
        $out = [];
        foreach ($children as $c) {
            if (isset($c['permission']) && ! $this->user->{$c['permission']}()) {
                continue;
            }
            if (isset($c['admin_only']) && $c['admin_only'] && ! $this->user->esAdministrador()) {
                continue;
            }
            if (isset($c['module']) && ! module_enabled_for_empresa($c['module'], $this->empresa)) {
                continue;
            }
            $route = $c['route'] ?? null;
            if (isset($c['route_admin'], $c['route_supervisor'])) {
                $route = $this->user->esAdministrador() ? $c['route_admin'] : $c['route_supervisor'];
            }
            $out[] = [
                'label' => $c['label'],
                'route' => $route,
                'routes_active' => $c['routes_active'] ?? [],
            ];
        }

        return $out;
    }

    /**
     * Ítems principales (no en acordeón).
     */
    public function getItemsPrincipales(): array
    {
        return array_values(array_filter($this->getItems(), fn ($i) => ! ($i['enAcordeon'] ?? false)));
    }

    /**
     * Ítems que van en el acordeón (rol secundario).
     */
    public function getItemsAcordeon(): array
    {
        return array_values(array_filter($this->getItems(), fn ($i) => $i['enAcordeon'] ?? false));
    }

    /**
     * Menú por secciones: solo secciones con al menos un ítem visible.
     * Regla A.1: si una sección queda sin ítems, no se renderiza.
     *
     * @return array<int, array{key: string, label: string, items: array}>
     */
    public function buildMenu(): array
    {
        $principales = $this->getItemsPrincipales();
        $grouped = collect($principales)->groupBy('section');
        $sections = [];
        foreach ($this->definicionSecciones() as $def) {
            $key = $def['key'];
            $items = $grouped->get($key, collect())->values()->all();
            if (count($items) > 0) {
                $sections[] = ['key' => $key, 'label' => $def['label'], 'items' => $items];
            }
        }

        return $sections;
    }

    /**
     * Ruta de inicio según rol (para uso en vistas).
     */
    public function getHomeRoute(): string
    {
        if (! $this->user) {
            return 'usuario.index';
        }

        return $this->user->esAdministrador() ? 'administrador.index' : ($this->user->esSupervisor() ? 'supervisor.index' : 'usuario.index');
    }

    public function user(): ?User
    {
        return $this->user;
    }

    public function empresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function tierPrincipal(): ?string
    {
        return $this->tierPrincipal;
    }

    public function tierSecundario(): ?string
    {
        return $this->tierSecundario;
    }
}
