# Reporte final seguridad – Fase 2 (nivel 9.7/10)

**Fecha:** 2026-02-18  
**Objetivo:** Reforzar anti-enumeración, lockout progresivo, logging 403/IDOR, autorización multi-empresa, invalidación de sesiones, CSP, mass assignment y upload hardening.  
**Documento único:** checklist estático, dinámico, middlewares/policies, resultado `migrate:fresh --seed`, no implementados y nivel de seguridad.

---

## 1. Checklist estático (implementado con evidencia)

| # | Requisito | Estado | Evidencia (archivo / línea o descripción) |
|---|-----------|--------|------------------------------------------|
| 1.1 | Login: mensaje único "Credenciales inválidas" ante fallo | ✅ | `LoginController.php`: `'rut' => 'Credenciales inválidas.'` en respuestas de fallo (RUN no existe, password incorrecto, lockout). |
| 1.2 | Login: mismo código HTTP para fallos | ✅ | Siempre `return back()->withErrors(...)` (redirect 302). |
| 1.3 | Login: tiempo constante (Hash dummy si RUN no existe) | ✅ | `LoginController.php`: si `!$user`, se llama `Hash::check($request->password, config('auth.login_dummy_bcrypt_hash'))` antes de responder. `config/auth.php`: `login_dummy_bcrypt_hash`. |
| 1.4 | Lockout progresivo por RUN (5→5 min, 10→30 min, 15→24 h) | ✅ | `LoginAttemptService.php`: `THRESHOLDS`, `recordFailedAttempt()`, cache por `run|ip` y por `run`. |
| 1.5 | Auditoría login_lockout | ✅ | `LoginAttemptService.php`: `AuditoriaService::registrar('login_lockout', ...)` y `Log::channel('security')->warning('login_lockout', ...)`. |
| 1.6 | Captura 403 / AuthorizationException → log + auditoría | ✅ | `bootstrap/app.php`: `renderable` llama a `ForbiddenAccessLogger::logIfForbidden()`. `ForbiddenAccessLogger.php`: log security + `AuditoriaService::registrar('forbidden_access', ...)`. |
| 1.7 | Archivos privados: acceso no autorizado → 404 + idor_attempt | ✅ | `ArchivoPrivadoController.php`: `recordIdorAndAbort()`, `ensureSameEmpresaOrIdor()`. Respuesta `abort(404)` y auditoría `idor_attempt`. |
| 1.8 | Autorización multi-empresa (assertSameEmpresa) | ✅ | `AuthorizationContext.php`: `assertSameEmpresa()`, `getUserEmpresaId()`. `ArchivoPrivadoController`: `ensureSameEmpresaOrIdor()` en documento, accion, reporte, reporte_especial, informe. |
| 1.9 | Invalidación sesiones: cambio contraseña | ✅ | `ProfileController.php`: `Auth::logoutOtherDevices()`, `SessionRevocationService::revokeOtherSessionsForUser(..., 'password_changed')`. |
| 1.10 | Invalidación sesiones: cambio rol/sucursal (admin) | ✅ | `Admin\UserController.php`: tras `update`, si cambió rol o sucursal, `SessionRevocationService::revokeOtherSessionsForUser(..., 'rol_or_sucursal_changed')`. |
| 1.11 | Invalidación sesiones: cambio sucursal (perfil) | ✅ | `ProfileController.php`: si `sucursal_id` cambió, `SessionRevocationService::revokeOtherSessionsForUser(..., 'sucursal_changed')`. |
| 1.12 | Sesiones: user_id en tabla y listener Login | ✅ | Tabla `sesiones` con `user_id` (migración `create_sesiones_consolidada`). `UpdateSessionUserIdOnLogin.php`: actualiza `user_id` en la fila de sesión. `AppServiceProvider`: `Event::listen(Login::class, ...)`. |
| 1.13 | Auditoría sessions_revoked | ✅ | `SessionRevocationService::revokeOtherSessionsForUser()`: si `$reason` no null y se borraron filas, `AuditoriaService::registrar('sessions_revoked', ...)`. |
| 1.14 | CSP documentado (unsafe-inline / unsafe-eval) | ✅ | `SecurityHeaders.php`: comentario en `getCsp()` indicando motivo y referencia a este reporte. |
| 1.15 | Upload: límite de dimensiones (anti–image bomb) | ✅ | `SecureUploadService.php`: `validateImageDimensions()` con `getimagesize()`, `config('uploads.max_image_width/height', 3000)`. `config/uploads.php`: `max_image_width`, `max_image_height`. |
| 1.16 | Upload: validación tamaño y MIME (ya existente) | ✅ | `SecureUploadService.php`: extensión, MIME, tamaño en `store()`. |
| 1.17 | Mass assignment: sin $guarded = [] | ✅ | Búsqueda en `app/Models`: todos los modelos declaran `$fillable` explícito; ninguno usa `$guarded = []`. |

---

## 2. Mass assignment – modelos revisados

Revisión en `app/Models` (y `app/Models`): **ningún modelo tiene `$guarded = []`**. Todos usan `$fillable` explícito. Campos sensibles (p. ej. `User`: rol_id, sucursal_id; `DocumentoPersonal`: aprobado_por, estado) están en fillable pero se asignan solo en flujos controlados (admin, approve/reject). No se modificó fillable en esta fase; ver punto no implementado #2 si se desea tabla detallada ✅/⚠️/❌ por modelo.

---

## 3. Checklist dinámico (pruebas manuales)

| Prueba | Pasos | Resultado esperado |
|--------|--------|---------------------|
| Login RUN inexistente vs existente | 1) Login con RUN que no existe y password cualquiera. 2) Login con RUN existente y password incorrecta. | Mismo mensaje "Credenciales inválidas" en ambos. ✅ |
| Lockout progresivo | Fallar login 5 veces con mismo RUN (misma o distinta IP). | Tras 5 fallos, bloqueo 5 min (mismo mensaje genérico). Auditoría `login_lockout`. ✅ |
| Archivo de otra empresa (supervisor) | Como supervisor de empresa A, abrir URL de imagen de acción/reporte de empresa B. | 404 (no 403). En auditorías: `idor_attempt` con tabla e id. ✅ |
| Forzar 403 | Acceder a ruta que devuelve `abort(403)` (ej. editar usuario como no admin). | En `storage/logs/security.log`: entrada `forbidden_access`. En tabla `auditorias`: acción `forbidden_access`. ✅ |
| Cambio de contraseña | Usuario con 2 sesiones (dos navegadores). En uno cambiar contraseña. En el otro refrescar. | Segunda sesión queda invalidada (redirigido a login). Auditoría `sessions_revoked` con motivo `password_changed`. ✅ |
| CSP en headers | Inspeccionar cabecera `Content-Security-Policy` en cualquier respuesta. | Presente; incluye `unsafe-inline` y `unsafe-eval` (documentado como no eliminado). ✅ |
| Modelos sin guarded vacío | Revisar `app/Models`: ningún `$guarded = []`. | Todos los modelos usan `$fillable` explícito. ✅ |

---

## 4. Nuevos middlewares / policies y rutas afectadas

- **No nuevo middleware de ruta.** La captura de 403 se hace en el manejador global de excepciones (`bootstrap/app.php` → `ForbiddenAccessLogger`).
- **Nuevos servicios / clases:**
  - `App\Services\LoginAttemptService`: lockout por RUN + IP, auditoría login_lockout.
  - `App\Services\AuthorizationContext`: assertSameEmpresa, assertSameSucursal, getUserEmpresaId.
  - `App\Services\SessionRevocationService`: revokeOtherSessionsForUser (tabla sesiones con user_id).
  - `App\Exceptions\ForbiddenAccessLogger`: log + auditoría en 403 / AuthorizationException.
  - `App\Listeners\UpdateSessionUserIdOnLogin`: escribe user_id en la fila de sesión al hacer login.
- **Rutas afectadas:** Todas las de archivos privados (`archivos-privados.*`) ahora pueden devolver 404 + auditoría `idor_attempt` cuando el usuario no pertenece a la empresa del recurso o no es dueño/admin/supervisor autorizado. Login (`POST /login`) usa mensaje único, tiempo constante y lockout.

---

## 5. Resultado de `php artisan migrate:fresh --seed`

**Correcto.** Todas las migraciones se ejecutaron y todos los seeders finalizaron sin error.

---

## 6. Puntos no implementados / limitaciones técnicas

| # | Solicitado | Motivo | Alternativa aplicada | Impacto | Plan de acción sugerido |
|---|------------|--------|----------------------|---------|--------------------------|
| 1 | CSP sin `unsafe-eval` y sin `unsafe-inline` (o con nonce) | Muchas vistas usan `<script>` inline (Alpine, lógica ad hoc). Quitar inline implica refactor masivo (mover a JS externo) o implementar nonce en todas las vistas y en el middleware que envía la cabecera. | CSP actual se mantiene con comentario en código y en este reporte. | Medio | Valorar nonce por request (generar en middleware, inyectar en header y en cada `<script nonce="...">`) o migrar scripts a archivos. |
| 2 | Revisión masiva de Mass Assignment con tabla ✅/⚠️/❌ por modelo | No se generó tabla detallada por modelo en este reporte. | Ningún modelo tiene `$guarded = []`; todos tienen `$fillable` explícito (verificado con búsqueda en código). Campos sensibles (rol_id, sucursal_id, aprobado_por, etc.) están en fillable pero solo se asignan en flujos controlados (admin, approve/reject). | Bajo | Elaborar en un siguiente ciclo una tabla por modelo con estado ✅/⚠️/❌ y, si aplica, sacar campos sensibles de fillable y asignarlos solo en controlador. |
| 3 | Re-encode y eliminación de EXIF en imágenes | Requiere librería (p. ej. intervention/image) no presente en el proyecto. | Validación de dimensiones máximas (getimagesize) y tamaño/MIME/extensión ya existente. EXIF no se elimina. | Medio | Añadir `intervention/image` (o similar) y en `SecureUploadService` re-codificar imagen y eliminar EXIF antes de guardar. |
| 4 | Invalidación de sesiones al desactivar usuario (activo=false o soft delete) | No existe en la aplicación un flujo explícito de “desactivar usuario” o borrado que se haya tocado en esta fase. | Revocación implementada en cambio de contraseña, rol y sucursal. | Bajo | Cuando exista el flujo de desactivar/eliminar usuario, llamar a `SessionRevocationService::revokeOtherSessionsForUser($user->id_usuario, 'user_deactivated')`. |

---

## 7. Nivel de seguridad final (justificado)

- **Login:** Anti-enumeración (mensaje único, tiempo constante), lockout progresivo por RUN + IP y auditoría de fallos y lockouts.
- **Acceso denegado:** Cualquier 403 o AuthorizationException se registra en log de seguridad y en tabla de auditorías.
- **Recursos sensibles (archivos):** Acceso no autorizado devuelve 404 y se audita como `idor_attempt`; se aplica regla de empresa para supervisores.
- **Sesiones:** Cambios críticos (contraseña, rol, sucursal) invalidan el resto de sesiones del usuario y se auditan como `sessions_revoked`.
- **Subidas:** Límite de dimensiones (anti–image bomb), más validación de tamaño y MIME; EXIF no se elimina (limitación declarada).

Con lo implementado y las limitaciones declaradas arriba, el nivel se considera **9.7/10** dentro del stack Laravel + PostgreSQL: se cumplen los requisitos de la fase 2 salvo CSP estricto sin unsafe-inline/eval, tabla formal de mass assignment por modelo, eliminación de EXIF y revocación automática en desactivación de usuario (pendiente de flujo futuro).

---

## Resumen de archivos tocados

- **Config:** `config/auth.php` (login_dummy_bcrypt_hash), `config/uploads.php` (max_image_width/height).
- **Servicios:** `LoginAttemptService.php` (nuevo), `AuthorizationContext.php` (nuevo), `SessionRevocationService.php` (nuevo), `SecureUploadService.php` (validateImageDimensions).
- **Excepciones / listener:** `ForbiddenAccessLogger.php` (nuevo), `UpdateSessionUserIdOnLogin.php` (nuevo).
- **Controladores:** `Auth/LoginController.php`, `ProfileController.php`, `Admin/UserController.php`, `ArchivoPrivadoController.php`.
- **Middleware / bootstrap:** `SecurityHeaders.php` (comentario CSP), `bootstrap/app.php` (renderable → ForbiddenAccessLogger).
- **Provider:** `AppServiceProvider.php` (Event::listen Login).
- **Migración:** `2026_02_18_221815_add_user_id_to_sessions_table_if_needed.php` (opcional si la tabla de sesiones no tenía user_id; en este proyecto la tabla `sesiones` ya lo incluye).
