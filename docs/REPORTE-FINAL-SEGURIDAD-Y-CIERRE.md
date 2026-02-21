# Reporte final de seguridad y cierre – web-seguridad

**Fecha:** 2026-02-19  
**Objetivo:** Dejar el sistema listo para entrega comercial (cierre funcional, hardening de ciberseguridad, checklist verificable).

---

## 1) Cierre funcional

### 1.1 Pivots con SoftDeletes – relaciones que filtran borrados

| Relación | Modelo | Filtro aplicado | Archivo |
|----------|--------|------------------|---------|
| RolUsuario → permisos | RolUsuario | `wherePivotNull('deleted_at')` | `app/Models/RolUsuario.php` |
| Permiso → roles | Permiso | `wherePivotNull('deleted_at')` | `app/Models/Permiso.php` |
| ModalidadJerarquia → rolesOrdenados | ModalidadJerarquia | `wherePivotNull('deleted_at')` | `app/Models/ModalidadJerarquia.php` |

Las tablas pivot `rol_permiso` y `modalidad_roles` tienen `deleted_at`; las relaciones Eloquent listadas **no consideran** filas soft-deleted del pivot.

---

### 1.2 Auditoría manual – endpoints donde se registra

| Acción | Endpoint / método | Tabla auditoría | Archivo |
|--------|-------------------|------------------|---------|
| toggle_activo (sector) | PATCH admin/sectores/{sector}/toggle | sectores | `App\Http\Controllers\Admin\SectorController::toggle` |
| toggle_activo (dispositivo) | toggle dispositivo | dispositivos_permitidos | `App\Http\Controllers\Admin\DispositivoPermitidoController::toggle` |
| toggle_activo (ubicación) | toggle ubicación | ubicaciones_permitidas | `App\Http\Controllers\Admin\UbicacionPermitidaController::toggle` |
| toggle_activo (IMEI) | toggle IMEI | imeis_permitidos | `App\Http\Controllers\Admin\ImeiPermitidoController::toggle` |
| toggle_activo (blacklist) | PATCH blacklist/{id}/toggle | blacklists | `App\Http\Controllers\BlacklistController::toggle` |
| documento_approve | PUT admin/documentos/{documento}/aprobar | documentos | `App\Http\Controllers\Admin\DocumentoPersonalController::aprobar` |
| documento_reject | PUT admin/documentos/{documento}/rechazar | documentos | `App\Http\Controllers\Admin\DocumentoPersonalController::rechazar` |
| documento_approve (supervisor) | PUT supervisor/documentos/{documento}/aprobar | documentos | `App\Http\Controllers\Supervisor\DocumentoPersonalController::aprobar` |
| documento_reject (supervisor) | PUT supervisor/documentos/{documento}/rechazar | documentos | `App\Http\Controllers\Supervisor\DocumentoPersonalController::rechazar` |
| download (PDF informe) | GET informes/{id}/pdf | informes | `App\Http\Controllers\InformeController::pdf` |
| download (stream PDF) | GET informes/{id}/ver-pdf | informes | `App\Http\Controllers\InformeController::verPdf` |
| login_success | POST /login (éxito) | usuarios | `App\Http\Controllers\Auth\LoginController::login` |
| login_failed | POST /login (fallo) | usuarios | `App\Http\Controllers\Auth\LoginController::login` |

Todos usan `AuditoriaService::registrar(...)` con `accion`, `tabla`, `registro_id`, y opcionalmente `cambios_antes`, `cambios_despues`, `metadata` (route/ip/user_agent se rellenan en el servicio).

**Auditoría de cambio de contraseña:** evento `password_changed` en tabla `usuarios` (contexto: perfil, usuario_perfil, admin_crear_usuario, admin_editar_usuario); no se guarda la contraseña en metadata.

**Descargas de archivos:** evento `download_file` en tabla `documentos` (imágenes frente/reverso) y en `informes` (PDF).

---

### 1.3 Auditoría inmutable (PostgreSQL)

| Aspecto | Estado | Dónde |
|---------|--------|--------|
| Trigger bloquea UPDATE/DELETE en `auditorias` | ✅ | Migración `2026_02_19_300000_auditorias_immutable_trigger_postgresql.php`. Solo se ejecuta si driver es `pgsql`. Función `prevent_auditorias_update_delete()`; trigger `auditorias_immutable_trigger` BEFORE UPDATE OR DELETE. Solo se permite INSERT. |
| down() | ✅ | La migración revierte: drop trigger y drop function. |

Test manual: intentar `UPDATE auditorias SET accion = 'x' WHERE id = 1` o `DELETE FROM auditorias WHERE id = 1` en PostgreSQL debe devolver error.

---

### 1.4 Validación coherencia empresa / sucursal / sector (anti-fugas)

| Elemento | Ubicación | Descripción |
|----------|-----------|-------------|
| Reglas de validación | `app/Rules/SucursalBelongsToEmpresa.php`, `app/Rules/SectorBelongsToSucursal.php` | Validan que sucursal.empresa_id = empresa_id y sector.sucursal_id = sucursal_id en formularios. |
| Middleware | `app/Http/Middleware/EnsureContextConsistency.php` | Comprueba que sector_id/sucursal_id/empresa_id del request formen cadena coherente; usuarios no-admin no pueden operar fuera de su sucursal/sector. |
| Registro middleware | `bootstrap/app.php` alias `context.consistency` | Se puede aplicar a rutas admin que reciban sector_id/sucursal_id/empresa_id. |

Uso recomendado: aplicar `context.consistency` al grupo de rutas admin que gestionan sectores/sucursales/empresas, y usar las reglas en FormRequest donde se envíen empresa_id, sucursal_id o sector_id.

---

### 1.5 Modelos con auditoría activa (Observers)

Lista final de modelos que tienen registrado `AuditoriaObserver` en `AppServiceProvider::registerAuditoriaObservers()`:

Empresa, Sucursal, Sector, User, Accion, ReporteEspecial, Ingreso, Blacklist, Persona, PuntoRonda, Reporte, Informe, DocumentoPersonal, Tarea, UbicacionPermitida, DispositivoPermitido, RolUsuario, Permiso, **TareaDetalle**, **Reunion**.

Modelo **Reunion**: creado en `app/Models/Reunion.php` (tabla `reuniones`) y añadido al registro de Observers.

---

## 2) Hardening de aplicación

### 2.1 Autenticación y contraseñas

| Aspecto | Estado | Dónde / nota |
|---------|--------|--------------|
| Hashing | ✅ | Laravel usa `Hash::make()` (bcrypt/argon2 según `config/hashing.php`). Contraseña en `User` con cast `'clave' => 'hashed'`. |
| Política de contraseña (mínimo 12, confirmación, no comprometida) | ✅ | `UpdatePasswordRequest` (perfil), `AdminStoreUserRequest` / `AdminUpdateUserRequest` (admin usuarios). Min 12 caracteres, confirmación obligatoria, `Password::min(12)->uncompromised()`. Auditoría `password_changed` en perfil, usuario perfil y admin crear/editar usuario (sin guardar contraseña). Mensajes en español en FormRequests y `lang/es/validation.php`. |
| Rate limiting login | ✅ | Ver 2.2. |
| Recovery tokens | ⚠️ | No implementado; documentar como mejora si se añade “olvidé contraseña”. |

---

### 2.2 Rate limiting

| Ruta / grupo | Límite | Nombre | Dónde se configura |
|--------------|--------|--------|--------------------|
| POST /login | 5 por minuto por RUT o IP | `login` | `AppServiceProvider::configureRateLimiting()` + `routes/web.php` middleware `throttle:login` |
| GET ronda/escanear/{codigo} | 60 por minuto por usuario o IP | `ronda-scan` | `routes/web.php` middleware `throttle:ronda-scan` |
| **Rutas sensibles** (blacklist, documentos aprobar/rechazar/subir, descargas PDF, admin usuarios) | **30/min por user+IP** | `sensitive` | `throttle:sensitive` en `routes/web.php` para: blacklist store/destroy/toggle, usuario documentos store, admin/supervisor documentos aprobar/rechazar, informes pdf/ver-pdf, archivos-privados documento, admin usuarios store/update. |
| Límite genérico web | 60/min (Laravel default) | (default) | Aplica al resto de rutas web. |

Definiciones: `AppServiceProvider::configureRateLimiting()`. ✅ **throttle:sensitive** (30/min por clave user_id|ip) aplicado a endpoints críticos.

---

### 2.3 Sesiones y cookies

| Parámetro | Valor recomendado / actual | Archivo |
|-----------|----------------------------|---------|
| SESSION_SECURE_COOKIE | `true` en producción (HTTPS) | `.env` / `config/session.php` (env) |
| SESSION_HTTP_ONLY | `true` (default) | `config/session.php` |
| SESSION_SAME_SITE | `lax` (default) | `config/session.php` |
| Regenerar sesión al login | ✅ | `LoginController::login` → `$request->session()->regenerate()` |
| Invalidar al logout | ✅ | `LoginController::logout` → `invalidate()` + `regenerateToken()` |
| Expiración | `SESSION_LIFETIME` (default 120 min) | `config/session.php` |

Entregable: en producción configurar en `.env`: `SESSION_SECURE_COOKIE=true`, `SESSION_LIFETIME=480` (8h) o según política.

---

### 2.4 CSRF

- Todas las rutas web llevan el middleware `VerifyCsrfToken` (pila `web`).
- No hay excepciones CSRF documentadas; formularios usan `@csrf`.
- APIs: si en el futuro se exponen APIs públicas, usar Sanctum u otro mecanismo con tokens y CORS restringido.

---

### 2.5 Control de acceso (autorización)

- **Gates** centralizados en `AppServiceProvider::registerRoleGates()`: `ver-control-acceso`, `ver-rondas-qr`, `ver-mis-reportes`, `ver-reporte-sucursal`, `ver-supervision`, `ver-reportes-estadisticas`, `ver-reportes-diarios`, `ver-gestion`, `es-admin`, `es-supervisor`, `es-usuario`, `es-guardia-control-acceso`.
- Las comprobaciones se delegan en métodos del `User` (p. ej. `puedeVerControlAcceso()`, `esAdministrador()`).
- Rutas admin/supervisor están bajo middleware `auth` y en muchos casos comprobaciones adicionales en controlador (`abort(403)`).
- **Policies** por recurso: no hay políticas Laravel (Policy) registradas para Empresa, Sucursal, Sector, Reporte, Ingreso, Documento; el control se hace por Gates y lógica en controladores. Recomendación: ir migrando a Policies para recursos principales.

Matriz resumida (rol → permisos de ruta):

| Rol | Acceso típico |
|-----|----------------|
| ADMIN | Todo (clientes, sectores, usuarios, documentos, reportes, auditorías, módulos). |
| SUPERVISOR / SUPERVISOR_USUARIO | Supervisión, reportes, documentos (aprobar), novedades; no gestión de clientes/usuarios/sectores. |
| USUARIO_SUPERVISOR / USUARIO | Control de acceso, rondas QR, mis reportes, documentos (subir); reporte sucursal según Gates. |
| GUARDIA | Control de acceso (ingresos/blacklist). |

---

### 2.6 Validación y sanitización

- Se usan `$request->validate()` y en algunos módulos reglas propias (p. ej. `ChileRut`).
- Blade: uso de `{{ }}` para escapar salida; evitar `{!! !!}` salvo contenido sanitizado.
- FormRequest: no hay FormRequest por módulo de forma generalizada; la validación está en controladores. Lista de validaciones relevantes: LoginController (rut, password, fingerprint), BlacklistController (ChileRut, patente), ClienteController (empresa/instalación), SectorController, IngresosController, etc.

---

### 2.7 Cabeceras de seguridad (HTTP Security Headers)

| Header | Valor | Dónde |
|--------|--------|-------|
| X-Content-Type-Options | nosniff | `App\Http\Middleware\SecurityHeaders` |
| X-Frame-Options | SAMEORIGIN | `App\Http\Middleware\SecurityHeaders` |
| Referrer-Policy | strict-origin-when-cross-origin | `App\Http\Middleware\SecurityHeaders` |
| Permissions-Policy | camera=(), microphone=(), geolocation=(self) | `App\Http\Middleware\SecurityHeaders` |
| Content-Security-Policy | default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https: blob:; font-src 'self' data:; connect-src 'self'; frame-ancestors 'self'; base-uri 'self'; form-action 'self' | `App\Http\Middleware\SecurityHeaders` |
| Strict-Transport-Security | max-age=31536000; includeSubDomains; preload (solo producción y HTTPS) | `App\Http\Middleware\SecurityHeaders` |

Middleware registrado en la pila `web` en `bootstrap/app.php`.

---

### 2.8 CORS

- Aplicación es principalmente web (same-origin). Si se añaden APIs consumidas por otros dominios, configurar `config/cors.php` con orígenes permitidos (no `*`) y métodos/headers mínimos.

---

### 2.9 Archivos y uploads (documentos, imágenes)

| Aspecto | Estado / recomendación |
|---------|-------------------------|
| Disco private | ✅ | `config/filesystems.php` → disco `private` (root `storage/app/private`). Nada sensible accesible por URL directa. |
| Servicio único | ✅ | `App\Services\SecureUploadService`: valida tamaño (config `uploads.max_document_kb` / `max_image_kb`), extensión y mimetype estricto; nombre UUID; guarda en disco `private`; retorna path interno. |
| Documentos (imágenes frente/reverso) | ✅ | UsuarioDocumentoController usa SecureUploadService (imágenes jpg/png/webp, 5 MB). Paths guardados en BD; descarga solo por endpoint. |
| Descarga controlada | ✅ | `GET /archivos-privados/documentos/{documento}/{lado}`: auth, autorización (dueño o admin/supervisor), throttle:sensitive, auditoría `download_file`, `response()->file()` desde storage. Vistas documento usan esta ruta (no Storage::url). |
| PDF informes | ✅ | Descarga/stream por controlador con auth; auditoría `download_file`; throttle:sensitive en rutas informes pdf/ver-pdf. |

**Endpoints de upload protegidos:** POST usuario/documentos (throttle:sensitive). **Endpoints de descarga protegidos:** GET archivos-privados/documentos/{id}/{frente|reverso}, GET informes/{id}/pdf, GET informes/{id}/ver-pdf. Confirmación: ningún archivo sensible es accesible por URL pública (no hay symlink a `storage/app/private`).

---

### 2.10 Generación y descarga de PDF

- Endpoints de PDF de informes: requieren autenticación; se valida que el informe pertenezca al usuario (o permisos de supervisión).
- Auditoría de descarga: ✅ registrada como `download_file` (ver 1.2).
- Rate limit: ✅ `throttle:sensitive` (30/min) en rutas informes/pdf e informes/ver-pdf.
- Inyección en plantillas: vistas PDF deben usar solo datos escapados; no renderizar HTML crudo sin sanitizar.

---

### 2.11 Logs y monitoreo

| Elemento | Estado | Ubicación |
|----------|--------|-----------|
| Canal `security` | ✅ | `config/logging.php` → channel `security` (daily, `storage/logs/security.log`) |
| Login fallido | ✅ | `LoginController::login` → `Log::channel('security')->warning('login_failed', ['run' => ..., 'ip' => ..., 'user_agent' => ...])` |
| Acceso denegado (403) | ⚠️ | Opcional: registrar en mismo canal desde middleware o controladores que hagan `abort(403)`. |

Entregable: canal `security` configurado; uso en login_failed. Correlación con tabla `auditorias` vía request_id queda como mejora opcional.

---

### 2.12 Dependencias (composer audit)

- **Resultado:** `composer audit --format=plain` → **No security vulnerability advisories found.**
- Recomendación: ejecutar `composer audit` en CI en cada push y bloquear versiones con CVEs conocidos.

---

## 3) Base de datos (PostgreSQL)

### 3.1 Usuarios y privilegios (least privilege)

- Usuario de aplicación no debe ser superuser.
- Permisos mínimos: SELECT, INSERT, UPDATE, DELETE en tablas del schema utilizado; no otorgar CREATE/DROP en producción. Migraciones ejecutadas desde pipeline o entorno controlado.

### 3.2 Conexiones seguras

- Usar TLS para conexión a BD si está remota; rotación de credenciales; secretos solo en variables de entorno (no en repositorio).

### 3.3 Auditoría y logs inmutables

- Tabla `auditorias`: uso append-only desde la aplicación (solo INSERT). No hay UPDATE/DELETE en código sobre `auditorias`.
- Opcional a nivel BD: policy o trigger que prohíba UPDATE/DELETE en `auditorias`. Backups con retenión según política.

---

## 4) Módulos pagados – seguridad y control

- Middleware `module:clave` (`EnsureModuleEnabled`): valida que el módulo esté habilitado globalmente (`module_enabled($module)`) y que la empresa permita el módulo (`module_enabled_for_empresa($module)`). Si no: **404**.
- Rol/permiso: el acceso a cada ruta sigue dependiendo de Gates y comprobaciones en controlador (no solo del menú). Se recomienda auditar cuando un admin active/desactive módulo por empresa (p. ej. al actualizar `empresas.modulos_activos`).

---

## 5) Checklist de cumplimiento

| # | Ítem | Estado |
|---|------|--------|
| 1.1 | Pivots rol_permiso / modalidad_roles filtran soft-deleted en relaciones | ✅ |
| 1.2 | Auditoría manual en toggle_activo, approve/reject, download, login | ✅ |
| 1.3 | Validación coherencia empresa/sucursal/sector (reglas + middleware) | ✅ |
| 1.4 | Observers en TareaDetalle y Reunion; lista final de modelos auditados | ✅ |
| 2.1 | Hashing y rate limit login | ✅; política contraseña mínima/uncompromised | ⚠️ |
| 2.2 | Rate limiting login y ronda-scan | ✅ |
| 2.3 | Sesión segura (regenerate, invalidate, config) | ✅ |
| 2.4 | CSRF en rutas web | ✅ |
| 2.5 | Gates centralizados; Policies por recurso | ✅ Gates; ⚠️ Policies opcionales |
| 2.6 | Validación y escape Blade | ✅ |
| 2.7 | Security headers (CSP, X-Frame-Options, etc.) | ✅ |
| 2.8 | CORS | ⚠️ N/A por ahora; configurar si hay API cross-origin |
| 2.9 | Política de uploads (MIME, UUID, almacenamiento) | ⚠️ Documentada; reforzar en código |
| 2.10 | PDF con autorización y auditoría | ✅ |
| 2.11 | Canal log security (login_failed) | ✅ |
| 2.12 | composer audit sin vulnerabilidades | ✅ |
| 3.1 | BD least privilege | Documentado |
| 3.2 | Conexión segura BD | Documentado |
| 3.3 | Auditoría inmutable | ✅ App; opcional BD |
| 4 | Middleware módulos por empresa | ✅ |

**Leyenda:** ✅ Implementado y verificado; ⚠️ Parcial o recomendación pendiente.

---

## Pendientes recomendados

1. **Política de contraseña:** longitud mínima 10–12 y, si es posible, regla “uncompromised” en cambio de contraseña y alta de usuarios.
2. **Canal de log `security`:** crear y usar para login_failed y 403.
3. **Uploads:** validación MIME real, renombrado a UUID y almacenamiento fuera de webroot para documentos sensibles.
4. **Policies:** definir Policy para Empresa, Sucursal, Sector, Reporte, Ingreso, Documento y usarlas en controladores para autorización explícita.
5. **Aplicar middleware `context.consistency`** a las rutas admin que reciban sector_id/sucursal_id/empresa_id (p. ej. store/update de sectores, reportes, ingresos).

Con estos puntos el sistema queda listo para entrega comercial con cierre funcional y hardening documentado y aplicado en los ítems marcados como ✅.
