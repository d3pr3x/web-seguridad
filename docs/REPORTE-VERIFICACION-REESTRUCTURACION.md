# Reporte de verificación – Reestructuración web-seguridad

**Fecha:** 2026-02-19  
**Objetivo:** Completar, corregir y verificar la reestructuración (jerarquía por empresa, soft delete, índices únicos parciales, auditoría, activo, sector_id, admin, módulos por empresa).

---

## A) Jerarquía – Implementación funcional (MenuBuilder)

| Elemento | Estado | Ubicación / evidencia |
|----------|--------|------------------------|
| MenuBuilder dinámico | Implementado | `app/Services/MenuBuilder.php` |
| Usuario y empresa activa (sucursal → empresa) | Implementado | `MenuBuilder::getEmpresaActiva()` |
| Modalidad y roles ordenados (`modalidad_roles.orden`) | Implementado | `getModalidad()`, `getRolesOrdenados()`, `computeTierOrden()` |
| Rol principal vs secundario (usuario_supervisor / supervisor_usuario) | Implementado | `computePrincipalSecundario()`: USUARIO_SUPERVISOR → principal usuario, acordeón supervisor; SUPERVISOR_USUARIO → al revés |
| Orden del menú por `modalidad_roles` | Implementado | `getItems()` ordena por tier (usuario, supervisor, admin) según orden en modalidad |
| Uso en sidebar y menú móvil | Implementado | `resources/views/components/usuario/sidebar.blade.php`, `resources/views/components/usuario/mobile-menu.blade.php` (usan `MenuBuilder`, `getItemsPrincipales()`, `getItemsAcordeon()`) |

---

## B) Soft Delete (PostgreSQL)

### B1) Trait SoftDeletes en modelos con `deleted_at`

Todos los modelos que tienen tabla con `deleted_at` usan `use Illuminate\Database\Eloquent\SoftDeletes` y `use SoftDeletes`:

| Modelo | Archivo |
|--------|---------|
| Empresa | `app/Models/Empresa.php` |
| Sucursal | `app/Models/Sucursal.php` |
| Sector | `app/Models/Sector.php` |
| User | `app/Models/User.php` |
| RolUsuario | `app/Models/RolUsuario.php` |
| Permiso | `app/Models/Permiso.php` |
| Tarea | `app/Models/Tarea.php` |
| TareaDetalle | `app/Models/TareaDetalle.php` |
| Reporte | `app/Models/Reporte.php` |
| Informe | `app/Models/Informe.php` |
| Accion | `app/Models/Accion.php` |
| ReporteEspecial | `app/Models/ReporteEspecial.php` |
| PuntoRonda | `app/Models/PuntoRonda.php` |
| RondaEscaneo | `app/Models/RondaEscaneo.php` |
| Ingreso | `app/Models/Ingreso.php` |
| Blacklist | `app/Models/Blacklist.php` |
| Persona | `app/Models/Persona.php` |
| DocumentoPersonal | `app/Models/DocumentoPersonal.php` |
| DiaTrabajado | `app/Models/DiaTrabajado.php` |
| ConfiguracionSueldo | `app/Models/ConfiguracionSueldo.php` |
| Feriado | `app/Models/Feriado.php` |
| UbicacionPermitida | `app/Models/UbicacionPermitida.php` |
| DispositivoPermitido | `app/Models/DispositivoPermitido.php` |
| GrupoIncidente | `app/Models/GrupoIncidente.php` |
| TipoIncidente | `app/Models/TipoIncidente.php` |
| ModalidadJerarquia | `app/Models/ModalidadJerarquia.php` |
| ModalidadRol | `app/Models/ModalidadRol.php` |

### B2) Tablas con `deleted_at` (lista completa)

Según migración `2026_02_19_100004_add_soft_deletes_to_all_important_tables.php` y tablas que ya lo tenían:

empresas, sucursales, sectores, usuarios, roles_usuario, permisos, rol_permiso, tareas, detalles_tarea, reportes, informes, acciones, reportes_especiales, puntos_ronda, escaneos_ronda, ingresos, blacklists, personas, documentos, reuniones, dias_trabajados, configuraciones_sueldo, feriados, ubicaciones_permitidas, dispositivos_permitidos, grupos_incidentes, tipos_incidente, modalidades_jerarquia, modalidad_roles.

### B3) Queries por defecto y withTrashed

- Por defecto los listados **no incluyen** borrados (Eloquent excluye automáticamente con SoftDeletes).
- **Incluir borrados** solo cuando el admin activa el filtro: ejemplo en `app/Http/Controllers/Admin/ClienteController.php` método `index()`: `if ($request->boolean('incluir_borrados')) { $query->withTrashed(); }`.

---

## C) Índices únicos parciales (PostgreSQL)

### Migración

`database/migrations/2026_02_19_200000_unique_partial_indexes_postgresql.php` (solo se ejecuta con driver `pgsql`).

### Política aplicada

| Tabla | Columna | Tipo | SQL exacto creado |
|-------|---------|------|-------------------|
| empresas | codigo | Parcial (reusable tras soft delete) | `CREATE UNIQUE INDEX empresas_codigo_unique_where_not_deleted ON empresas (codigo) WHERE deleted_at IS NULL` |
| sucursales | codigo | Parcial | `CREATE UNIQUE INDEX sucursales_codigo_unique_where_not_deleted ON sucursales (codigo) WHERE deleted_at IS NULL` |
| puntos_ronda | codigo | Parcial | `CREATE UNIQUE INDEX puntos_ronda_codigo_unique_where_not_deleted ON puntos_ronda (codigo) WHERE deleted_at IS NULL` |

### Unique histórico (no reutilizable, no modificado)

- `usuarios.run` – unique normal (mantener).
- `personas.rut` – unique normal.
- Identificadores legales se mantienen con UNIQUE normal.

---

## D) Unificación activo / activa

| Elemento | Estado | Ubicación |
|----------|--------|-----------|
| Trait `HasActivoScope` | Implementado | `app/Models/Concerns/HasActivoScope.php` (scope `scopeActivos()`, método `isActive()`) |
| Columna configurable | Implementado | `$activoColumn = 'activo'` o `'activa'` en cada modelo |
| Modelos con scope/método unificado | Implementado | Empresa, Sucursal, Sector, Tarea, UbicacionPermitida, PuntoRonda, Blacklist, ModalidadJerarquia, ModalidadRol, GrupoIncidente, TipoIncidente, ConfiguracionSueldo, Feriado, DispositivoPermitido |
| Alias `scopeActivas` donde aplica | Implementado | Empresa, Sucursal, ConfiguracionSueldo (llaman a `scopeActivos`) |

Nota: Empresas y Sucursales siguen usando columna `activa`; el trait unifica el comportamiento vía `activoColumn`.

---

## E) Auditoría completa

### Tabla y tipos

- Tabla: `auditorias` (migración `2026_02_19_100006_create_auditorias_table.php`).
- En PostgreSQL, `cambios_antes` y `cambios_despues` en JSONB: migración `2026_02_19_200002_auditorias_jsonb_postgresql.php`.

### Modelos con Observer de auditoría

Registrados en `app/Providers/AppServiceProvider.php` en `registerAuditoriaObservers()`:

Empresa, Sucursal, Sector, User, Accion, ReporteEspecial, Ingreso, Blacklist, Persona, PuntoRonda, Reporte, Informe, DocumentoPersonal, Tarea, UbicacionPermitida, DispositivoPermitido, RolUsuario, Permiso.

### Eventos registrados (AuditoriaObserver)

- create, update (antes/después), delete (soft), force_delete, restore.

Eventos adicionales (toggle_activo, approve/reject documentos) deben invocarse manualmente con `AuditoriaService::registrar()` donde se ejecuten esas acciones.

---

## F) sector_id en reportes

| Verificación | Resultado |
|--------------|-----------|
| `Schema::hasColumn('reportes', 'sector_id')` | **true** |
| Evidencia | Migración `database/migrations/2025_12_31_000018_create_reportes_consolidada.php`: `$table->foreignId('sector_id')->nullable()->constrained('sectores')->nullOnDelete();` |

No se requirió nueva migración. Coherencia sector → sucursal → empresa queda en reglas de negocio/validación.

---

## G) Interfaz Admin – Ajustes obligatorios

| Requisito | Estado | Ubicación |
|-----------|--------|-----------|
| Formulario empresa: select modalidad obligatorio | Implementado | `resources/views/admin/clientes/empresas/create.blade.php`, `edit.blade.php`; controlador valida `modalidad_id` required y pasa `$modalidades` desde `ModalidadJerarquia::activos()` |
| Listados: toggle "Incluir inactivos" | Implementado | `resources/views/admin/clientes/index.blade.php` (checkbox `incluir_inactivos`); `ClienteController::index()` aplica `activos()` si no se marca |
| Listados: toggle "Incluir borrados" | Implementado | Checkbox `incluir_borrados`; controlador usa `withTrashed()` cuando está marcado |
| Registros borrados: solo lectura, sin restaurar, texto histórico | Implementado | En index: badge "Registro histórico (borrado)" y sin botones Editar/Eliminar/Instalaciones; en edit: aviso, fieldset disabled, sin botón Guardar; binding `cliente` en `AppServiceProvider` con `Empresa::withTrashed()->findOrFail()` para poder abrir edición de borrados en solo lectura |

---

## H) Módulos pagados por empresa

| Elemento | Estado | Ubicación |
|----------|--------|-----------|
| Columna `empresas.modulos_activos` (JSONB en PostgreSQL) | Implementado | Migración `2026_02_19_200001_add_modulos_activos_to_empresas.php` |
| Lógica `module_enabled_global && empresaPermiteModulo` | Implementado | Helper `module_enabled_for_empresa($key, $empresa)` en `app/Helpers/helpers.php`; `Empresa::permiteModulo($clave)` en `app/Models/Empresa.php` |
| Middleware `module:clave` valida por empresa | Implementado | `app/Http/Middleware/EnsureModuleEnabled.php`: comprueba `module_enabled_for_empresa($module)` |
| Menú respeta módulos por empresa | Implementado | `app/Services/MenuBuilder.php` usa `module_enabled_for_empresa($entry['module'], $this->empresa)` en `isVisible()` |

---

## I) Resumen de migraciones nuevas (esta fase)

- `2026_02_19_200000_unique_partial_indexes_postgresql.php` – índices únicos parciales empresas/sucursales/puntos_ronda.
- `2026_02_19_200001_add_modulos_activos_to_empresas.php` – columna `modulos_activos`.
- `2026_02_19_200002_auditorias_jsonb_postgresql.php` – JSONB para cambios_antes/cambios_despues en auditorías (solo pgsql).

---

## Listas finales de verificación

- **Modelos con SoftDeletes:** Ver sección B1 (lista completa).
- **Tablas con deleted_at:** Ver sección B2.
- **Tablas con activo/activa:** empresas (activa), sucursales (activa), sectores (activo), tareas (activa), puntos_ronda (activo), blacklists (activo), ubicaciones_permitidas (activa), dispositivos_permitidos (activo), grupos_incidentes (activo), tipos_incidente (activo), modalidades_jerarquia (activo), modalidad_roles (activo), configuraciones_sueldo (activo), feriados (activo), imei_permitidos (activo).
- **Índices únicos parciales creados:** Ver sección C (tabla con SQL).
- **Modelos con auditoría activa:** Ver sección E.
- **sector_id en reportes:** Confirmado (sección F).
- **MenuBuilder:** Implementado (sección A).
- **Módulos por empresa:** Implementado (sección H).

---

## Problemas detectados

- Ninguno crítico. Opcional: migrar columna `activa` a `activo` en empresas/sucursales para homogeneizar nombres (comportamiento ya unificado vía trait).
- Tareas/DetalleTarea, Reunion, RolUsuario/Permiso con Observers: TareaDetalle (detalles_tarea) y Reunion no están en el listado de Observers; pueden añadirse si se desea trazabilidad en esas tablas.
