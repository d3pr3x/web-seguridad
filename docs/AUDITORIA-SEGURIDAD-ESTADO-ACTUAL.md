# Auditoría de seguridad – Estado actual del proyecto

**Fecha de auditoría:** 2026-02-18  
**Alcance:** Verificación en código existente. No se asume nada; lo no implementado explícitamente se marca como ❌.

---

## 1) Verificación en código

### A. Política de contraseña

| Pregunta | Estado | Evidencia en código |
|----------|--------|---------------------|
| ¿Existe validación mínima 10/12? | ✅ Implementado (min 12) | `app/Http/Requests/UpdatePasswordRequest.php` L23: `Password::min(12)`. `AdminStoreUserRequest.php` L27, `AdminUpdateUserRequest.php` L30: mismo. `UsuarioPerfilController.php` L26: `Password::min(12)`. |
| ¿Existe confirmación obligatoria? | ✅ Implementado | `UpdatePasswordRequest.php` L22: `'confirmed'`. `AdminStoreUserRequest.php` L26, `AdminUpdateUserRequest.php` L29: `'confirmed'`. `UsuarioPerfilController.php` L26: `'confirmed'`. |
| ¿Se usa regla Password (min/uncompromised)? | ✅ Implementado | `UpdatePasswordRequest.php` L23: `Password::min(12)->uncompromised()`. Mismo en AdminStoreUserRequest, AdminUpdateUserRequest, UsuarioPerfilController. No existe un método único `Password::rules()` en Laravel; se usan `Password::min(12)->uncompromised()`. |
| ¿Se audita password_changed? | ✅ Implementado | `ProfileController.php` L62: `AuditoriaService::registrar('password_changed', 'usuarios', ...)`. `UsuarioPerfilController.php` L37. `Admin\UserController.php` L71 (store), L105 (update cuando hay password). |
| ¿En qué archivos? | — | **Validación:** UpdatePasswordRequest.php, AdminStoreUserRequest.php, AdminUpdateUserRequest.php, UsuarioPerfilController.php (validate). **Uso:** ProfileController.php (updatePassword), Admin\UserController.php (store, update), UsuarioPerfilController.php (updatePassword). **Auditoría:** los mismos controladores llaman a AuditoriaService::registrar('password_changed', ...). |

**Resumen A:** ✅ **Implementado correctamente** (min 12, confirmación, Password::min(12)->uncompromised(), auditoría password_changed en perfil, usuario perfil, admin crear y admin editar con contraseña).

---

### B. Uploads privados

| Pregunta | Estado | Evidencia en código |
|----------|--------|---------------------|
| ¿Existe disco `private` en config/filesystems? | ✅ | `config/filesystems.php` L40-45: disco `'private'` con `root` = `storage_path('app/private')`. |
| ¿Se usa Storage::disk('private')? | ✅ | `app/Services/SecureUploadService.php` L16: `$disk = 'private'`; L64: `$file->storeAs(..., $this->disk)`; L73-78: `Storage::disk($this->disk)`. `app/Http/Controllers/ArchivoPrivadoController.php` L53-54, L56-57: `Storage::disk('private')->exists()` y `path()`. |
| ¿Se usa UUID para nombre de archivo? | ✅ | `SecureUploadService.php` L62: `$filename = Str::uuid() . '.' . $ext`. |
| ¿Se valida MIME y tamaño? | ✅ | `SecureUploadService.php` L49-60: extensión en `$allowedExtensions`, MIME con `$file->getMimeType()` en `$allowedMimetypes`, tamaño con `$maxKb * 1024`. Límites en `config/uploads.php` (max_document_kb, max_image_kb). |
| ¿Las descargas pasan por controlador? | ✅ | `routes/web.php` L71-73: ruta `GET /archivos-privados/documentos/{documento}/{lado}` → `ArchivoPrivadoController::documentoArchivo`. Respuesta con `response()->file($absolutePath)`. Vistas documento usan `route('archivos-privados.documento', ...)` (usuario/admin/supervisor show). |
| ¿Existe acceso directo público al disco private? | ❌ No | `config/filesystems.php` `links` solo define `public_path('storage') => storage_path('app/public')`. No hay symlink ni ruta pública a `storage/app/private`. |

**Archivos donde ocurre upload (sensibles):**

- **Documentos personales (frente/reverso):** `app/Http/Controllers/UsuarioDocumentoController.php` L97-100: usa `SecureUploadService::storeImage()`, disco private, UUID, MIME y tamaño en el servicio. ✅

**Archivos donde ocurre upload a disco público (no sensibles o no migrados):**

- `app/Http/Controllers/InformeController.php` L103: `storeAs(..., 'public')` para fotografías de informes.
- `app/Http/Controllers/AccionController.php` L96: `store('acciones', 'public')`.
- `app/Http/Controllers/Admin/NovedadController.php` L135: `store('acciones', 'public')`.
- `app/Http/Controllers/UsuarioAccionController.php` L90: `store('acciones', 'public')`.
- `app/Http/Controllers/ReporteController.php` L43: `storeAs('reportes', ..., 'public')`.
- `app/Http/Controllers/ReporteEspecialController.php` L100, `UsuarioReporteController.php` L94: `store('reportes-especiales', 'public')`.

**Archivos donde ocurre descarga controlada:**

- `app/Http/Controllers/ArchivoPrivadoController.php`: documento imagen frente/reverso (auth, autorización, auditoría download_file).
- `app/Http/Controllers/InformeController.php`: pdf() y verPdf() (auth, auditoría download_file).

**Resumen B:** ✅ **Implementado** para documentos personales (disco private, UUID, MIME/tamaño, descarga por controlador, sin URL pública). ⚠️ **Parcial:** informes, acciones, reportes y reportes-especiales siguen usando disco `public` para imágenes.

---

### C. Throttle sensitive

| Pregunta | Estado | Evidencia en código |
|----------|--------|---------------------|
| ¿Existe RateLimiter::for('sensitive')? | ✅ | `app/Providers/AppServiceProvider.php` L64-66: `RateLimiter::for('sensitive', function ($request) { $key = ($request->user()?->id_usuario ?? 'guest') . '|' . $request->ip(); return Limit::perMinute(30)->by($key); });` |
| ¿Está aplicado en rutas? | ✅ | Véase lista abajo. |

**Rutas que usan `throttle:sensitive` (archivo `routes/web.php`):**

- L73: `GET /archivos-privados/documentos/{documento}/{lado}`
- L97-99: `POST /blacklist`, `DELETE /blacklist/{id}`, `PATCH /blacklist/{id}/toggle`
- L147: `POST /usuario/documentos` (store)
- L174-175: `GET /informes/{id}/pdf`, `GET /informes/{id}/ver-pdf`
- L210-211: supervisor `PUT .../documentos/{documento}/aprobar`, `.../rechazar`
- L233, L235: admin `POST usuarios`, `PUT usuarios/{usuario}`
- L243-244: admin `PUT .../documentos/{documento}/aprobar`, `.../rechazar`

**Resumen C:** ✅ **Implementado correctamente.**

---

### D. Auditoría inmutable (PostgreSQL)

| Pregunta | Estado | Evidencia en código |
|----------|--------|---------------------|
| ¿Existe migración con trigger? | ✅ | `database/migrations/2026_02_19_300000_auditorias_immutable_trigger_postgresql.php`. |
| ¿Bloquea UPDATE y DELETE? | ✅ | Función y trigger descritos abajo. |

**SQL del trigger (extraído de la migración):**

```sql
CREATE OR REPLACE FUNCTION prevent_auditorias_update_delete()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'UPDATE' THEN
        RAISE EXCEPTION 'No está permitido modificar registros de auditoría (UPDATE bloqueado).';
    ELSIF TG_OP = 'DELETE' THEN
        RAISE EXCEPTION 'No está permitido eliminar registros de auditoría (DELETE bloqueado).';
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS auditorias_immutable_trigger ON auditorias;
CREATE TRIGGER auditorias_immutable_trigger
    BEFORE UPDATE OR DELETE ON auditorias
    FOR EACH ROW
    EXECUTE FUNCTION prevent_auditorias_update_delete();
```

La migración solo se ejecuta si `DB::getDriverName() === 'pgsql'`. El `down()` elimina el trigger y la función.

**Resumen D:** ✅ **Implementado correctamente** (solo en PostgreSQL).

---

### E. Auditoría manual (AuditoriaService::registrar)

Todos los lugares donde aparece `AuditoriaService::registrar(` y la acción que registra:

| Archivo | Línea | Acción registrada | Tabla | Notas |
|---------|-------|-------------------|-------|--------|
| `app/Http/Controllers/ProfileController.php` | 62 | password_changed | usuarios | contexto: perfil |
| `app/Http/Controllers/UsuarioPerfilController.php` | 37 | password_changed | usuarios | contexto: usuario_perfil |
| `app/Http/Controllers/Admin/UserController.php` | 71 | password_changed | usuarios | contexto: admin_crear_usuario |
| `app/Http/Controllers/Admin/UserController.php` | 105 | password_changed | usuarios | contexto: admin_editar_usuario (solo si se envía password) |
| `app/Http/Controllers/Auth/LoginController.php` | 94 | login_success | usuarios | run en metadata |
| `app/Http/Controllers/Auth/LoginController.php` | 112 | login_failed | usuarios | run, reason en metadata |
| `app/Http/Controllers/Admin/SectorController.php` | 134 | toggle_activo | sectores | nombre en metadata |
| `app/Http/Controllers/Admin/DispositivoPermitidoController.php` | 106 | toggle_activo | dispositivos_permitidos | descripcion en metadata |
| `app/Http/Controllers/Admin/UbicacionPermitidaController.php` | 127 | toggle_activo | ubicaciones_permitidas | nombre en metadata |
| `app/Http/Controllers/Admin/ImeiPermitidoController.php` | 103 | toggle_activo | imeis_permitidos | — |
| `app/Http/Controllers/BlacklistController.php` | 72 | toggle_activo | blacklists | rut, patente en metadata |
| `app/Http/Controllers/Admin/DocumentoPersonalController.php` | 72 | documento_approve | documentos | tipo_documento, id_usuario |
| `app/Http/Controllers/Admin/DocumentoPersonalController.php` | 97 | documento_reject | documentos | tipo_documento, id_usuario, motivo_rechazo |
| `app/Http/Controllers/Supervisor/DocumentoPersonalController.php` | 72 | documento_approve | documentos | rol: supervisor |
| `app/Http/Controllers/Supervisor/DocumentoPersonalController.php` | 97 | documento_reject | documentos | rol: supervisor |
| `app/Http/Controllers/InformeController.php` | 164 | download_file | informes | numero_informe, tipo: pdf_download |
| `app/Http/Controllers/InformeController.php` | 184 | download_file | informes | numero_informe, tipo: pdf_stream |
| `app/Http/Controllers/ArchivoPrivadoController.php` | 40 | download_file | documentos | tipo: documento_imagen, lado, id_usuario_documento |
| `app/Observers/AuditoriaObserver.php` | 28 | force_delete o delete | (tabla del modelo) | al eliminar/force-delete modelo observado |

**Resumen E:** ✅ Las acciones críticas (password_changed, login_success/login_failed, toggle_activo en sectores/dispositivos/ubicaciones/IMEI/blacklist, documento_approve/reject, download_file) están registradas manualmente; además los modelos observados generan create/update/delete/restore vía AuditoriaObserver.

---

## 2) Checklist final

Clasificación por categoría. **Evidencia** = archivo y línea donde se verifica.

### Seguridad aplicación

| Ítem | Estado | Evidencia (archivo / línea) |
|------|--------|-----------------------------|
| CSRF en rutas web | ✅ | Middleware web (VerifyCsrfToken) por defecto en Laravel |
| Auth en rutas sensibles | ✅ | `routes/web.php`: rutas bajo `Route::middleware(['auth'])` |
| Autorización (dueño/admin/supervisor) en descarga documentos | ✅ | `ArchivoPrivadoController.php` L24-28: comprobación id_usuario / esAdministrador() / esSupervisor() |
| Gates (roles) registrados | ✅ | `AppServiceProvider.php` registerRoleGates(): ver-control-acceso, ver-rondas-qr, es-admin, etc. |

### Seguridad BD

| Ítem | Estado | Evidencia (archivo / línea) |
|------|--------|-----------------------------|
| Trigger auditorías inmutable (solo pgsql) | ✅ | `database/migrations/2026_02_19_300000_auditorias_immutable_trigger_postgresql.php`: función + trigger BEFORE UPDATE OR DELETE |
| Soft deletes en tablas críticas | ✅ | Migración `2026_02_19_100004`; modelos User, Empresa, Sucursal, Sector, DocumentoPersonal, etc. usan SoftDeletes |

### Upload

| Ítem | Estado | Evidencia (archivo / línea) |
|------|--------|-----------------------------|
| Disco private configurado | ✅ | `config/filesystems.php` L40-45 |
| Servicio SecureUploadService (UUID, MIME, tamaño) | ✅ | `app/Services/SecureUploadService.php` L41-66, L62 (UUID), config/uploads.php |
| Documentos personales a disco private | ✅ | `UsuarioDocumentoController.php` L97-100: SecureUploadService::storeImage() |
| Descarga documento por controlador (no URL pública) | ✅ | `ArchivoPrivadoController.php`; rutas archivos-privados/documentos; vistas con route() |
| Sin symlink a storage/app/private | ✅ | `config/filesystems.php`: links solo public → app/public |
| Imágenes de informes/acciones/reportes en private | ❌ | InformeController L103, AccionController L96, NovedadController L135, etc.: usan disco `public` |

### Password

| Ítem | Estado | Evidencia (archivo / línea) |
|------|--------|-----------------------------|
| Validación min 12 caracteres | ✅ | UpdatePasswordRequest L23, AdminStoreUserRequest L27, AdminUpdateUserRequest L30, UsuarioPerfilController L26 |
| Confirmación obligatoria | ✅ | Mismos archivos: regla `confirmed` |
| Regla no comprometida (uncompromised) | ✅ | Password::min(12)->uncompromised() en los mismos |
| Auditoría password_changed (perfil) | ✅ | ProfileController L62, UsuarioPerfilController L37 |
| Auditoría password_changed (admin crear/editar usuario) | ✅ | Admin\UserController L71, L105 |
| Mensajes en español | ✅ | UpdatePasswordRequest messages(), lang/es/validation.php (uncompromised) |

### Rate limit

| Ítem | Estado | Evidencia (archivo / línea) |
|------|--------|-----------------------------|
| RateLimiter::for('login') | ✅ | AppServiceProvider.php L58-62, 5/min por RUT o IP |
| RateLimiter::for('sensitive') | ✅ | AppServiceProvider.php L64-66, 30/min por user_id\|ip |
| RateLimiter::for('ronda-scan') | ✅ | AppServiceProvider.php L67-69 |
| throttle:sensitive en blacklist, documentos, informes PDF, admin usuarios | ✅ | routes/web.php L73, L97-99, L147, L174-175, L210-211, L233, L235, L243-244 |

### Auditoría

| Ítem | Estado | Evidencia (archivo / línea) |
|------|--------|-----------------------------|
| AuditoriaService::registrar en login_success / login_failed | ✅ | LoginController L94, L112 |
| AuditoriaService::registrar en password_changed | ✅ | ProfileController L62, UsuarioPerfilController L37, Admin\UserController L71, L105 |
| AuditoriaService::registrar en toggle_activo (sector, dispositivo, ubicación, IMEI, blacklist) | ✅ | SectorController L134, DispositivoPermitidoController L106, UbicacionPermitidaController L127, ImeiPermitidoController L103, BlacklistController L72 |
| AuditoriaService::registrar en documento_approve / documento_reject | ✅ | Admin\DocumentoPersonalController L72, L97; Supervisor\DocumentoPersonalController L72, L97 |
| AuditoriaService::registrar en download_file (PDF informes, archivo documento) | ✅ | InformeController L164, L184; ArchivoPrivadoController L40 |
| Observer para create/update/delete/restore en modelos | ✅ | AppServiceProvider registerAuditoriaObservers(); AuditoriaObserver.php |

### Headers

| Ítem | Estado | Evidencia (archivo / línea) |
|------|--------|-----------------------------|
| X-Content-Type-Options: nosniff | ✅ | SecurityHeaders.php L15 |
| X-Frame-Options: SAMEORIGIN | ✅ | SecurityHeaders.php L16 |
| Referrer-Policy | ✅ | SecurityHeaders.php L17 |
| Permissions-Policy | ✅ | SecurityHeaders.php L18 |
| Content-Security-Policy | ✅ | SecurityHeaders.php L19, getCsp() L29-32 |
| Strict-Transport-Security (producción + HTTPS) | ✅ | SecurityHeaders.php L21-22 |
| Middleware en pila web | ✅ | bootstrap/app.php: SecurityHeaders en web |

### Soft delete

| Ítem | Estado | Evidencia (archivo / línea) |
|------|--------|-----------------------------|
| Migración add soft deletes a tablas importantes | ✅ | `2026_02_19_100004_add_soft_deletes_to_all_important_tables.php` |
| Modelos con SoftDeletes | ✅ | User, Empresa, Sucursal, Sector, DocumentoPersonal, Blacklist, Ingreso, Reporte, Informe, Accion, PuntoRonda, etc. (múltiples en app/Models) |

### Módulos

| Ítem | Estado | Evidencia (archivo / línea) |
|------|--------|-----------------------------|
| Middleware module:clave (EnsureModuleEnabled) | ✅ | bootstrap/app.php L25; app/Http/Middleware/EnsureModuleEnabled.php |
| Rutas con module:control_acceso, documentos_guardias, rondas_qr, calculo_sueldos, reportes_diarios | ✅ | routes/web.php L82, L144, L152, L159, L181, L205, L238, L247-250, L293 |

---

## 3) Resultado final

### Estado general del sistema

**Nivel de seguridad: Alto**

- Política de contraseña: mínima 12, confirmación y no comprometida aplicadas; auditoría de cambio de contraseña en todos los flujos relevantes.
- Uploads sensibles (documentos personales): disco private, UUID, validación MIME/tamaño, descarga solo por controlador, sin URL pública.
- Rate limit: login, sensitive (30/min) y ronda-scan definidos y aplicados en rutas críticas.
- Auditoría: manual en login, password_changed, toggle_activo, documento approve/reject, download_file; Observer para CRUD; trigger PostgreSQL (solo pgsql) para auditorías inmutables.
- Headers de seguridad y soft delete en uso.

### Pendientes reales (evidenciados en código)

1. **Imágenes de informes, acciones, reportes y reportes-especiales** siguen guardándose en disco `public` (InformeController, AccionController, NovedadController, UsuarioAccionController, ReporteController, ReporteEspecialController, UsuarioReporteController). No es un fallo de seguridad si se considera que esas imágenes no son “documentos personales sensibles”; si se desea tratar todo como sensible, habría que migrarlas a private y servir por controlador.
2. **Recovery/“olvidé contraseña”** no está implementado (no verificado en esta auditoría; solo se constata que no hay flujo de reset en las rutas/controladores revisados).
3. **Trigger de auditoría inmutable** solo actúa en PostgreSQL; en MySQL/SQLite no existe equivalente en esta migración.

### Recomendación antes de entrega comercial

- Mantener la política de contraseña y la auditoría actual.
- Definir explícitamente qué archivos se consideran sensibles: si solo documentos personales (frente/reverso), el estado actual es suficiente; si también informes/acciones/reportes, planificar migración de esos uploads a private y descarga por controlador.
- En producción: `SESSION_SECURE_COOKIE=true`, HTTPS y comprobar que el trigger de auditorías esté creado en PostgreSQL.
- Opcional: documentar o implementar flujo de recuperación de contraseña si el producto lo requiere.

---

*Documento generado por auditoría estática del código. No se ha ejecutado la aplicación ni tests automatizados.*
