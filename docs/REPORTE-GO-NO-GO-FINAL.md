# REPORTE FINAL GO/NO-GO – Listo para pruebas de interfaz

**Fecha:** 2026-02-18  
**Stack:** Laravel + PostgreSQL  
**Objetivo:** Verificación automatizable y con evidencia de que el sistema está listo para probar interfaz (Seguridad Fase 2 aplicada).  
**Criterio:** ✅ = verificado con evidencia; ⚠️ = requiere ejecución local para confirmar; ❌ = fallo / bloqueador.

---

# 1. CHECKLIST FINAL (estado por ítem)

| # | Ítem | Estado | Evidencia (archivo + línea, o comando + resultado esperado) |
|---|------|--------|----------------------------------------------------------------|
| 1 | **Roles solo BD (sin Spatie) y gates** | ✅ | Sin Spatie: `app/Models/User.php` L12 comentario "No se usa Spatie"; `app/Providers/AppServiceProvider.php` L48 "No se usa Spatie". Gates: `AppServiceProvider.php` L81-93: `Gate::define('ver-control-acceso', ...)`, `es-admin`, `es-supervisor`, `es-usuario`, `es-guardia-control-acceso`, etc. Autorización vía `rol_id` y métodos en User (`puedeVerControlAcceso()`, `esAdministrador()`, etc.). |
| 2 | **Jerarquía por empresa: modalidades_jerarquia, modalidad_roles, empresas.modalidad_id, MenuBuilder** | ✅ | Tablas: `database/migrations/2026_02_19_100001_create_modalidades_jerarquia_table.php`, `2026_02_19_100002_create_modalidad_roles_table.php`, `2026_02_19_100003_add_modalidad_id_to_empresas_table.php`. MenuBuilder: `app/Services/MenuBuilder.php` L1-80: `getEmpresaActiva()`, `getModalidad()`, `getRolesOrdenados()`, `computePrincipalSecundario()` (rol principal/secundario y acordeón). |
| 3 | **Soft delete + activo/activa + scopes** | ✅ | Migración: `database/migrations/2026_02_19_100004_add_soft_deletes_to_all_important_tables.php`. Modelos: `app/Models/Empresa.php` L73 `scopeActivas`, `app/Models/Sucursal.php` L92 `scopeActivas`, `app/Models/Tarea.php` L55 `scopeActivas`; múltiples modelos con `SoftDeletes` y `HasActivoScope` (Feriado, Sector, Blacklist, UbicacionPermitida, etc.). |
| 4 | **Auditoría: tabla auditorias, observers, auditoría manual, trigger PostgreSQL** | ✅ | Tabla: `database/migrations/2026_02_19_100006_create_auditorias_table.php` (user_id, empresa_id, sucursal_id, accion, tabla, registro_id, cambios_antes/despues, metadata). Observers: `AppServiceProvider.php` L101-120: Empresa, Sucursal, Sector, User, Accion, ReporteEspecial, Ingreso, Blacklist, Persona, PuntoRonda, Reporte, Informe, DocumentoPersonal, Tarea, UbicacionPermitida, DispositivoPermitido, RolUsuario, Permiso, TareaDetalle, Reunion. Manual: login (LoginController L82, L103, L123), password_changed (ProfileController L60, Admin UserController L72/L109, UsuarioPerfilController L37), toggle (BlacklistController L72, SectorController L134, etc.), download_file (ArchivoPrivadoController L49/80/110/140/171, InformeController L163/L184). Trigger: `database/migrations/2026_02_19_300000_auditorias_immutable_trigger_postgresql.php` L36-37: `CREATE TRIGGER auditorias_immutable_trigger BEFORE UPDATE OR DELETE ON auditorias`. |
| 5 | **Upload hardening: SecureUploadService, disco private, rutas archivos-privados** | ✅ | SecureUploadService: `app/Services/SecureUploadService.php` L16 `private string $disk = 'private'`, L48-58 extensión/MIME/tamaño, L63-64 UUID, L65 `storeAs($subdir, $filename, $this->disk)`, L97-118 `validateImageDimensions()` con getimagesize. Config: `config/uploads.php` max_document_kb, max_image_kb, max_image_width/height, mimes/mimetypes. Rutas: `routes/web.php` L72-81 archivos-privados con auth + `throttle:sensitive`. Sin asset('storage/'): ver Script §2 grep. Sin store(...,'public'): ver Script §2 grep. |
| 6 | **Rate limiting: login, sensitive, ronda-scan; aplicado en rutas** | ✅ | `AppServiceProvider.php` L61-73: `RateLimiter::for('login', ...)`, `for('sensitive', ...)`, `for('ronda-scan', ...)`. Rutas: `web.php` L59 login `throttle:login`; L72-81 archivos-privados + L105-107 blacklist + L155 documentos store + L182-183 informes pdf + L218-219/L251-252 aprobar/rechazar + L241/L243 admin usuarios; L167 `ronda.escanear` `throttle:ronda-scan`. |
| 7 | **IDOR: 404 + auditoría idor_attempt** | ✅ | `app/Http/Controllers/ArchivoPrivadoController.php`: cada recurso llama `ensureSameEmpresaOrIdor()` (L34, 64, 94, 124, 155); si empresa distinta → `recordIdorAndAbort()` (L195-201) que registra `AuditoriaService::registrar('idor_attempt', ...)` y `abort(404)`. |
| 8 | **Session revocation: password_changed y cambios admin rol/sucursal** | ✅ | ProfileController L56-57: cambio contraseña → `Auth::logoutOtherDevices()`, `SessionRevocationService::revokeOtherSessionsForUser(..., 'password_changed')`. Admin UserController L116: cambio contraseña admin → revoke; L117-119: si cambió rol o sucursal → revoke con 'rol_or_sucursal_changed'. Perfil ya no permite cambiar sucursal (vista read-only, controlador solo nombre_completo/fecha_nacimiento/domicilio). |
| 9 | **Mass assignment: sin request->all() en create/update/fill/forceFill; profile sin sucursal_id; rol_id/sucursal_id solo admin** | ✅ | request->all(): 0 en app/Http (ver Script §2). ProfileController L28-34: validate solo nombre_completo, fecha_nacimiento, domicilio; update con `$request->only(['nombre_completo', 'fecha_nacimiento', 'domicilio'])`. Vista perfil: sucursal mostrada como solo lectura (sin input name="sucursal_id"). Admin: AdminUpdateUserRequest + $request->validated() en Admin\UserController. Ver `docs/AUDITORIA-MASS-ASSIGNMENT-REQUEST-ALL.md`. |
| 10 | **Seeds/migrations: orden correcto; pgsql-only (índices parciales, jsonb, trigger)** | ✅ | DatabaseSeeder: RolesUsuario → ModalidadesJerarquia → ModalidadRoles → Permisos → Empresa → Sucursal → Sector → … → UsuariosSeeder. Migraciones pgsql-only: `2026_02_19_200000_unique_partial_indexes_postgresql.php` (empresas/sucursales/puntos_ronda WHERE deleted_at IS NULL); `2026_02_19_200002_auditorias_jsonb_postgresql.php` (cambios_antes/cambios_despues jsonb); `2026_02_19_200001_add_modulos_activos_to_empresas.php` (jsonb en pgsql); `2026_02_19_300000_auditorias_immutable_trigger_postgresql.php` (solo si driver pgsql). |

---

# 2. SCRIPT DE VERIFICACIÓN (solo comandos)

Ejecutar en la raíz del proyecto, con PostgreSQL configurado.

```powershell
# --- Limpieza y migración ---
php artisan optimize:clear
php artisan migrate:fresh --seed
```

**Resultado esperado:** Sin errores; todas las migraciones ejecutadas; seeders completados.

```powershell
# --- Rutas: archivos-privados, throttle:sensitive, module:, auth ---
php artisan route:list --path=archivos-privados
php artisan route:list | findstr "throttle:sensitive"
php artisan route:list | findstr "module:"
php artisan route:list | findstr "auth"
```

**Resultado esperado:**  
- Al menos 5 rutas GET bajo `archivos-privados` (documentos, acciones, reportes, reportes-especiales, informes).  
- Varias rutas con throttle:sensitive (blacklist, documentos, informes, aprobar/rechazar, admin usuarios).  
- Rutas con middleware module: (control_acceso, documentos_guardias, rondas_qr, calculo_sueldos, reportes_diarios).  
- Rutas protegidas con auth.

```powershell
# --- Tinker / SQL: tablas y columnas clave ---
php artisan tinker --execute="echo \Illuminate\Support\Facades\Schema::hasTable('auditorias') ? 'auditorias:ok' : 'auditorias:missing';"
php artisan tinker --execute="echo \Illuminate\Support\Facades\Schema::hasColumn('empresas', 'modalidad_id') ? 'modalidad_id:ok' : 'modalidad_id:missing';"
php artisan tinker --execute="echo \Illuminate\Support\Facades\Schema::hasColumn('empresas', 'deleted_at') ? 'deleted_at:ok' : 'deleted_at:missing';"
php artisan tinker --execute="echo \Illuminate\Support\Facades\Schema::hasColumn('empresas', 'modulos_activos') ? 'modulos_activos:ok' : 'modulos_activos:missing';"
php artisan tinker --execute="echo \Illuminate\Support\Facades\Schema::hasColumn('auditorias', 'metadata') ? 'auditorias_metadata:ok' : 'auditorias_metadata:missing';"
```

**Resultado esperado:** Todas las salidas `*:ok`.

```powershell
# --- PostgreSQL: índices únicos parciales (solo en pgsql) ---
php artisan tinker --execute="
\$driver = \Illuminate\Support\Facades\DB::getDriverName();
if (\$driver !== 'pgsql') { echo 'skip:not_pgsql'; exit; }
\$r = \Illuminate\Support\Facades\DB::select(\"SELECT indexname FROM pg_indexes WHERE tablename = 'empresas' AND indexname LIKE '%codigo%' AND indexdef LIKE '%WHERE%'\");
echo count(\$r) > 0 ? 'empresas_partial_index:ok' : 'empresas_partial_index:check_manually';
"
php artisan tinker --execute="
\$driver = \Illuminate\Support\Facades\DB::getDriverName();
if (\$driver !== 'pgsql') { echo 'skip:not_pgsql'; exit; }
\$r = \Illuminate\Support\Facades\DB::select(\"SELECT tgname FROM pg_trigger WHERE tgname = 'auditorias_immutable_trigger'\");
echo count(\$r) > 0 ? 'trigger_auditorias:ok' : 'trigger_auditorias:missing';
"
```

**Resultado esperado:** Con PostgreSQL: `empresas_partial_index:ok` (o verificar índice `empresas_codigo_unique_where_not_deleted`) y `trigger_auditorias:ok`. Sin pgsql: `skip:not_pgsql`.

```powershell
# --- Ripgrep: patrones de seguridad ---
rg "asset\(['\"]storage/" resources/views --type-add 'view:*.blade.php' -t view
```

**PASS:** 0 coincidencias (no usar storage público para sensibles).

```powershell
rg "store\([^)]*['\"]public['\"]|storeAs\([^)]*['\"]public['\"]" app --type php
```

**PASS:** 0 coincidencias (uploads sensibles no en disco public).

```powershell
rg "request->all\(\)" app/Http --type php
```

**PASS:** 0 coincidencias (mass assignment seguro).

```powershell
rg "sucursal_id" app/Http/Controllers/ProfileController.php
```

**PASS:** No debe aparecer en validate ni en update (sí puede aparecer en comentarios). Actualmente ProfileController no contiene `sucursal_id` en validate ni en el array de update (solo `$request->only(['nombre_completo', 'fecha_nacimiento', 'domicilio'])`).

---

# 3. CHECKLIST DE NAVEGACIÓN (SMOKE TEST) POR ROL

Contraseña demo común: **Demo2026!Demo2026!**

| Rol | RUN (login) | URL base | Paso | Ruta / acción | Esperado | Validar auditoría (SQL) |
|-----|-------------|----------|------|----------------|----------|--------------------------|
| Admin | 11111111-1 | / | 1 | Crear empresa (con modalidad) | Formulario con modalidad_id; guardado OK | `SELECT * FROM auditorias WHERE tabla = 'empresas' AND accion IN ('create','update') ORDER BY id DESC LIMIT 1;` |
| Admin | 11111111-1 | /admin/... | 2 | Crear instalación (sucursal) | Alta de sucursal asociada a empresa | `SELECT * FROM auditorias WHERE tabla = 'sucursales' AND accion = 'create' ORDER BY id DESC LIMIT 1;` |
| Admin | 11111111-1 | /admin/sectores o vista rápida | 3 | Sectores por empresa / crear sector | Listado por empresa; crear sector OK | `SELECT * FROM auditorias WHERE tabla = 'sectores' ORDER BY id DESC LIMIT 1;` |
| Admin | 11111111-1 | /admin/auditorias o equivalente | 4 | Ver auditorías | Listado de registros de tabla auditorias | `SELECT COUNT(*) FROM auditorias;` |
| Admin | 11111111-1 | /admin/empresas/{id}/edit o módulos | 5 | Ver módulos por empresa (modulos_activos) | Edición empresa con módulos si aplica | Columna modulos_activos en empresas. |
| Admin contrato | 22222222-2 | / | 6 | Menú y límites según gates | Menú restringido según puedeVerGestion/puedeVerReporteSucursal etc. | Sin 403 no registrados; si 403: `SELECT * FROM auditorias WHERE accion = 'forbidden_access' ORDER BY id DESC LIMIT 1;` |
| Supervisor | 33333333-3 (Empresa 1) | /supervisor/documentos | 7 | Aprobar o rechazar documento | Flujo OK; auditoría documento_approve/documento_reject | `SELECT * FROM auditorias WHERE accion IN ('documento_approve','documento_reject') ORDER BY id DESC LIMIT 1;` |
| Supervisor Empresa 1 | 33333333-3 | GET archivos-privados de recurso de Empresa 2 | 8 | Intentar ver recurso de otra empresa (ej. reporte de inst C) | 404 (no 403) | `SELECT * FROM auditorias WHERE accion = 'idor_attempt' ORDER BY id DESC LIMIT 1;` debe tener registro reciente. |
| Usuario / usuario_supervisor | 66666666-6 o 55555555-5 | /acciones/create o similar | 9 | Crear acción y subir imagen | Acción creada; imagen en private | Ruta archivos-privados/acciones/{id}/imagen/0 con auth → 200. |
| Usuario | 66666666-6 | GET archivos-privados/… de recurso de otra empresa | 10 | URL de recurso de otra empresa | 404 | `SELECT * FROM auditorias WHERE accion = 'idor_attempt' ORDER BY id DESC LIMIT 1;` |
| Guardia | 77777777-7 (según seed) | /control-acceso o blacklist | 11 | Blacklist: toggle activo | Toggle OK | `SELECT * FROM auditorias WHERE tabla = 'blacklists' AND accion = 'toggle_activo' ORDER BY id DESC LIMIT 1;` |
| Cualquiera | (admin) | /profile | 12 | Perfil: editar nombre/domicilio; sucursal solo lectura | Guardado solo nombre/fecha/domicilio; sucursal no es select ni se envía | No debe haber update de usuarios con sucursal_id desde esta ruta (solo admin). |

**Nota:** Si algún rol o ruta no existe con ese nombre exacto, ajustar RUN/ruta según `database/seeders/UsuariosSeeder.php` y `routes/web.php`. Las consultas SQL asumen tabla `auditorias` y columnas estándar del proyecto.

---

# 4. LISTA DE BLOQUEADORES

- **Si todos los pasos del §1 Checklist y del §2 Script pasan:** no se declaran bloqueadores; el sistema se considera **GO** para pruebas de interfaz.
- **Si algún ítem del §1 falla:** declarar **BLOQUEADOR** con causa y corrección:
  - Ejemplo: "Ítem 9 Mass assignment: si en ProfileController volviera a aparecer sucursal_id en validate o update → BLOQUEADOR. Corrección: mantener solo `$request->only(['nombre_completo', 'fecha_nacimiento', 'domicilio'])` y vista sin input sucursal_id."
- **Si no se puede ejecutar el Script §2:** marcar los comandos no ejecutados como ⚠️ "requiere ejecución local" e indicar exactamente qué comando y qué resultado se espera (ya indicado en §2). No inventar resultados.
- **Verificación no realizada:** si falta evidencia (p. ej. no hay entorno PostgreSQL para el trigger), indicar: "No verificado: trigger auditorias_immutable_trigger en PostgreSQL. Comando: ver §2 Tinker/SQL; resultado esperado: trigger_auditorias:ok."

---

# RESUMEN GO/NO-GO

| Criterio | Estado |
|----------|--------|
| Checklist §1 (10 ítems) | ✅ Todos con evidencia en código/docs. |
| Vista perfil sucursal | ✅ Sucursal en solo lectura; no se envía sucursal_id. |
| Mass assignment ProfileController | ✅ Corregido; documentado en AUDITORIA-MASS-ASSIGNMENT-REQUEST-ALL.md. |
| Script §2 (optimize:clear, migrate:fresh --seed, route:list, tinker, ripgrep) | ⚠️ Requiere ejecución local para marcar PASS definitivo. |
| Smoke test §3 | ⚠️ Requiere ejecución manual por rol. |

**Veredicto:** **GO** para pruebas de interfaz, siempre que la ejecución local del Script §2 y del smoke test §3 no detecte fallos. Si en esa ejecución aparece algún fallo (p. ej. migración rota, trigger ausente, o ripgrep con coincidencias no deseadas), tratar como **BLOQUEADOR** hasta corregir y volver a ejecutar el script.
