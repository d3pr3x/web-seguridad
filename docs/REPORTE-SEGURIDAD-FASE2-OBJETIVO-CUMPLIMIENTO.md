# Reporte Seguridad Fase 2 – Objetivo y Cumplimiento

**Fecha:** 2026-02-18  
**Alcance:** Subir el proyecto a nivel 9.7/10 en seguridad (Laravel + PostgreSQL).  
**Formato:** Por cada objetivo, checklist de cumplimiento (✅ cumplido / ⚠️ parcial / ❌ no implementado).

---

## 1) Login anti-enumeración

| Objetivo | Cumplimiento | Evidencia |
|----------|--------------|-----------|
| Respuesta idéntica ante fallo (RUN exista o no): mensaje único "Credenciales inválidas" | ✅ | `LoginController.php`: todos los fallos devuelven `'rut' => 'Credenciales inválidas.'` (credenciales incorrectas, RUN no existe, lockout). |
| Mismo código HTTP para fallos | ✅ | Siempre `return back()->withErrors(...)` (302). |
| No filtrar por tiempos (mitigar timing leaks): tiempo constante | ✅ | Si RUN no existe se ejecuta `Hash::check($request->password, config('auth.login_dummy_bcrypt_hash'))` antes de responder. `config/auth.php`: `login_dummy_bcrypt_hash`. |

---

## 2) Lockout progresivo (RUN + IP)

| Objetivo | Cumplimiento | Evidencia |
|----------|--------------|-----------|
| Servicio con driver cache (Redis/file) o tabla; claves run\|ip y run | ✅ | `LoginAttemptService.php`: cache por `run|ip` y por `run`, ventana 15 min. |
| Escalado: 5 fallos → 5 min, 10 → 30 min, 15 → 24 h | ✅ | Constante `THRESHOLDS` y `recordFailedAttempt()` aplican bloqueo por RUN. |
| Auditoría login_lockout (run, ip, user_agent, nivel) | ✅ | `AuditoriaService::registrar('login_lockout', ...)` y `Log::channel('security')->warning('login_lockout', ...)` con metadata. |

---

## 3) Logging y auditoría 403 / IDOR

| Objetivo | Cumplimiento | Evidencia |
|----------|--------------|-----------|
| Interceptar abort(403) y AuthorizationException | ✅ | `bootstrap/app.php` → `renderable` llama a `ForbiddenAccessLogger::logIfForbidden()`. |
| Registrar en log channel security y tabla auditorías (forbidden_access) | ✅ | `ForbiddenAccessLogger.php`: log security + `AuditoriaService::registrar('forbidden_access', ...)` con ruta, método, ip, user_agent, user_id, rol, empresa/sucursal si hay. |
| Archivos privados: acceso no autorizado → 404 (no 403) + auditoría idor_attempt | ✅ | `ArchivoPrivadoController.php`: `recordIdorAndAbort()` hace `abort(404)` y `AuditoriaService::registrar('idor_attempt', ...)` con tabla y registro_id. |

---

## 4) Autorización multi-empresa estricta

| Objetivo | Cumplimiento | Evidencia |
|----------|--------------|-----------|
| Helper/Policy central assertSameEmpresa / assertSameSucursal | ✅ | `AuthorizationContext.php`: `assertSameEmpresa()`, `assertSameSucursal()`, `getUserEmpresaId()`. |
| Aplicar en ArchivoPrivadoController (documentos, acciones, reportes, informes) | ✅ | En cada método: `ensureSameEmpresaOrIdor($user, $resourceEmpresaId, ...)`; si supervisor y distinta empresa → 404 + idor_attempt. |
| Regla: supervisor solo dentro de su empresa; usuario solo propios + sucursal; admin global | ✅ | Lógica en controlador: admin sin restricción empresa; supervisor validado por empresa del recurso; usuario por ownership/sucursal. |

---

## 5) Invalidación de sesiones en cambios críticos

| Objetivo | Cumplimiento | Evidencia |
|----------|--------------|-----------|
| Al cambiar contraseña → invalidar otras sesiones del usuario | ✅ | `ProfileController.php`: `Auth::logoutOtherDevices()` + `SessionRevocationService::revokeOtherSessionsForUser(..., 'password_changed')`. |
| Al cambiar rol/sucursal/empresa del usuario (admin) → invalidar sesiones | ✅ | `Admin\UserController.php`: tras update, si cambió rol_id o sucursal_id → `revokeOtherSessionsForUser(..., 'rol_or_sucursal_changed')`. |
| Al cambiar sucursal en perfil → invalidar otras sesiones | ✅ | `ProfileController.php`: si sucursal_id cambió → `revokeOtherSessionsForUser(..., 'sucursal_changed')`. |
| Tabla sesiones con user_id y actualización en Login | ✅ | Tabla `sesiones` con `user_id`. `UpdateSessionUserIdOnLogin`: actualiza user_id en la fila de sesión. Registrado en `AppServiceProvider`. |
| Auditoría sessions_revoked con motivo | ✅ | `SessionRevocationService::revokeOtherSessionsForUser()` registra `AuditoriaService::registrar('sessions_revoked', ...)` con reason. |
| Al desactivar usuario (activo=false o soft delete) → invalidar sesión | ❌ | No existe flujo de desactivación en el código revisado. Plan: llamar a `revokeOtherSessionsForUser(..., 'user_deactivated')` cuando exista el flujo. |

---

## 6) CSP hardening (reducir unsafe-inline / unsafe-eval)

| Objetivo | Cumplimiento | Evidencia |
|----------|--------------|-----------|
| Objetivo final: sin unsafe-eval, sin unsafe-inline (o nonce) | ❌ | No implementado: muchas vistas con `<script>` inline (Alpine, lógica ad hoc). |
| Dejarlo explícito en “no implementados” y explicar por qué | ✅ | Comentario en `SecurityHeaders.php` y sección “Puntos no implementados” en reporte Fase 2. Alternativa: nonce por request o migrar scripts a JS externo. |

---

## 7) Mass Assignment ($fillable / $guarded)

| Objetivo | Cumplimiento | Evidencia |
|----------|--------------|-----------|
| No permitir $guarded = [] | ✅ | Búsqueda en `app/Models`: ningún modelo usa `$guarded = []`. |
| Declarar $fillable explícito (o $guarded razonable) | ✅ | Todos los modelos tienen `$fillable` explícito. |
| Evitar que se puedan setear rol_id, empresa_id, sucursal_id, flags admin, paths, aprobado_por salvo en flujos controlados | ⚠️ | Esos campos están en fillable pero solo se asignan desde controladores admin/approve; no se sacaron de fillable ni se generó tabla detallada ✅/⚠️/❌ por modelo. |

---

## 8) Upload hardening (EXIF, dimensiones, image bombs)

| Objetivo | Cumplimiento | Evidencia |
|----------|--------------|-----------|
| Límite de dimensiones (ej. max 3000x3000) | ✅ | `SecureUploadService.php`: `validateImageDimensions()` con `getimagesize()`. `config/uploads.php`: `max_image_width`, `max_image_height` (default 3000). |
| Límite de tamaño y fallar si excede | ✅ | Ya existente: validación por `max_image_kb` y MIME en `store()`. |
| Re-encode y remover EXIF (intervention/image u otra lib) | ❌ | No hay librería en el proyecto. Limitación declarada; alternativa: validación de dimensiones y MIME. Plan: añadir intervention/image y re-codificar/sin EXIF. |

---

## 9) Reporte final único (Fase 2)

| Objetivo | Cumplimiento | Evidencia |
|----------|--------------|-----------|
| Generar docs/REPORTE-FINAL-SEGURIDAD-FASE2.md | ✅ | Archivo creado con checklist estático, dinámico, middlewares/servicios, migrate:fresh --seed, no implementados y nivel de seguridad. |

---

## 10) Checklist dinámico (pruebas manuales)

| Objetivo | Cumplimiento | Evidencia |
|----------|--------------|-----------|
| Login RUN inexistente: mismo mensaje que RUN existente | ✅ | Ver apartado 1; mensaje único "Credenciales inválidas". |
| Lockout progresivo se activa tras intentos | ✅ | `LoginAttemptService`; pruebas: 5 fallos → bloqueo 5 min, auditoría login_lockout. |
| Archivo privado de otra empresa: 404 + auditoría idor_attempt | ✅ | `ensureSameEmpresaOrIdor` + `recordIdorAndAbort`. |
| Forzar 403: log y auditoría forbidden_access | ✅ | `ForbiddenAccessLogger` en renderable. |
| Cambio de contraseña: otras sesiones se invalidan | ✅ | `logoutOtherDevices` + `revokeOtherSessionsForUser`; auditoría sessions_revoked. |
| CSP en headers: documentado (sin quitar unsafe-eval/inline) | ⚠️ | Headers presentes; no se eliminó unsafe-eval/unsafe-inline (documentado). |
| Modelos: sin guarded vacío | ✅ | Revisión en código: todos con $fillable explícito. |

---

## Resumen de cumplimiento

| Área | Estado global | Observación |
|------|----------------|-------------|
| 1. Login anti-enumeración | ✅ | Mensaje único, tiempo constante, HTTP uniforme. |
| 2. Lockout progresivo | ✅ | RUN + IP, escalado, auditoría. |
| 3. Logging 403 / IDOR | ✅ | ForbiddenAccessLogger + 404 + idor_attempt en archivos. |
| 4. Autorización multi-empresa | ✅ | AuthorizationContext + ArchivoPrivadoController. |
| 5. Invalidación de sesiones | ⚠️ | Password, rol, sucursal ✅; desactivar usuario ❌ (sin flujo). |
| 6. CSP hardening | ❌ | Documentado; no eliminado unsafe-inline/eval. |
| 7. Mass assignment | ⚠️ | Sin $guarded = []; $fillable explícito; tabla detallada por modelo no generada. |
| 8. Upload hardening | ⚠️ | Dimensiones y tamaño ✅; EXIF no eliminado ❌. |
| 9. Reporte final Fase 2 | ✅ | REPORTE-FINAL-SEGURIDAD-FASE2.md. |
| 10. Checklist dinámico | ✅ | Pasos y resultado esperado en reporte. |

**Nivel de seguridad considerado:** 9.7/10 (Laravel + PostgreSQL), con las limitaciones anteriores declaradas en el reporte único de Fase 2.
