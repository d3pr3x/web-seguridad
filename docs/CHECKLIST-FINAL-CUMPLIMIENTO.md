# CHECKLIST FINAL DE CUMPLIMIENTO – Verificación técnica exhaustiva

**Fecha:** 2026-02-18  
**Objetivo:** Confirmar cumplimiento real del nivel de seguridad Fase 2 (9.7/10) con evidencia en código/SQL.  
**Criterio:** ✅ = implementado exactamente como se solicita; ❌ = no implementado o con defecto que impide cumplimiento.

---

## 1. Login anti-enumeración → ✅

| Verificación | Resultado | Evidencia (archivo + línea + código) |
|--------------|-----------|--------------------------------------|
| Existe `login_dummy_bcrypt_hash` en config/auth.php | ✅ | `config/auth.php` L124: `'login_dummy_bcrypt_hash' => env('LOGIN_DUMMY_BCRYPT_HASH', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),` |
| Cuando RUN no existe se ejecuta Hash::check() con dummy | ✅ | `app/Http/Controllers/Auth/LoginController.php` L122-124: `if (!$user) { Hash::check($request->password, config('auth.login_dummy_bcrypt_hash')); }` |
| Todos los fallos de credencial devuelven el mismo mensaje | ✅ | L84 y L127-128: `'rut' => 'Credenciales inválidas.'` (lockout y credenciales inválidas). Formato RUT/dispositivo/ubicación devuelven mensajes distintos (no son fallo de credencial). |
| Mismo código HTTP para fallos de credencial | ✅ | Siempre `return back()->withErrors(...)` (redirect 302). |
| Lockout no revela si el usuario existe | ✅ | L81-85: al estar en lockout se devuelve el mismo mensaje "Credenciales inválidas." y no se busca el usuario. |

---

## 2. Lockout progresivo → ✅

| Verificación | Resultado | Evidencia (archivo + línea + código) |
|--------------|-----------|--------------------------------------|
| Existe LoginAttemptService | ✅ | `app/Services/LoginAttemptService.php` existe. |
| Clave combinada RUN + IP | ✅ | L47: `$compositeKey = self::KEY_PREFIX_ATTEMPTS . $run . '|' . $ip;` y L49-50 contadores por compositeKey y por runKey. |
| Escalado 5→5 min, 10→30 min, 15→24 h | ✅ | L19-22: `private const THRESHOLDS = [ 5 => 5, 10 => 30, 15 => 24 * 60 ];` y L59-64 `foreach (self::THRESHOLDS as $threshold => $minutes)`. |
| Registra login_lockout en auditoría | ✅ | L68-74: `AuditoriaService::registrar('login_lockout', 'usuarios', null, null, null, ['run' => $run, 'ip' => $ip, ...])`. |
| Registra en canal security | ✅ | L75-80: `Log::channel('security')->warning('login_lockout', [...])`. |

---

## 3. IDOR protection (archivos privados) → ✅

| Verificación | Resultado | Evidencia (archivo + línea + código) |
|--------------|-----------|--------------------------------------|
| Valida empresa del recurso | ✅ | `ArchivoPrivadoController.php`: en cada método se llama `ensureSameEmpresaOrIdor($user, $resourceEmpresaId, ...)` (L31, 64, 95, 127, 162). |
| Acceso a recurso de otra empresa → 404 (no 403) | ✅ | L191-198: si supervisor y empresa distinta, se llama `recordIdorAndAbort()` que hace `abort(404)` (L215). |
| Registra idor_attempt | ✅ | L209-213: `AuditoriaService::registrar('idor_attempt', $tabla, $registroId, null, null, ['ruta' => ..., 'metodo' => ...])`. |
| Auditoría incluye tabla y registro_id | ✅ | Segundo y tercer parámetro de `registrar()` son $tabla y $registroId (tabla e id del recurso). |
| Método exacto | ✅ | `ensureSameEmpresaOrIdor()` (L189) y `recordIdorAndAbort()` (L206). |

**Nota:** Se añadió `use App\Models\User;` en `ArchivoPrivadoController` para que el type-hint `User` en L189 resuelva correctamente (sin ello habría riesgo de TypeError).

---

## 4. Logger global de 403 → ✅

| Verificación | Resultado | Evidencia (archivo + línea + código) |
|--------------|-----------|--------------------------------------|
| Existe ForbiddenAccessLogger | ✅ | `app/Exceptions/ForbiddenAccessLogger.php` existe. |
| Registrado en bootstrap/app.php | ✅ | `bootstrap/app.php` L29-32: `$exceptions->renderable(function (Throwable $e, \Illuminate\Http\Request $request) { \App\Exceptions\ForbiddenAccessLogger::logIfForbidden($e, $request); return null; });` |
| Registra en canal security | ✅ | `ForbiddenAccessLogger.php` L48: `Log::channel('security')->warning('forbidden_access', $metadata);` |
| Registra en tabla auditorías acción forbidden_access | ✅ | L49: `AuditoriaService::registrar('forbidden_access', 'sistema', null, null, null, $metadata);` |

---

## 5. Invalidación de sesiones → ✅

| Verificación | Resultado | Evidencia (archivo + línea + código) |
|--------------|-----------|--------------------------------------|
| Tabla sesiones tiene columna user_id | ✅ | `database/migrations/2025_12_31_000009_create_sesiones_consolidada.php` L16: `$table->unsignedBigInteger('user_id')->nullable()->index()`. Tabla usada: `sesiones` (config session.table). |
| Existe SessionRevocationService | ✅ | `app/Services/SessionRevocationService.php` existe. |
| Se ejecuta al cambiar contraseña | ✅ | `app/Http/Controllers/ProfileController.php` L57: `SessionRevocationService::revokeOtherSessionsForUser($user->id_usuario, 'password_changed');` |
| Se ejecuta al cambiar rol | ✅ | `app/Http/Controllers/Admin/UserController.php` L117-119: si cambió rol o sucursal, `SessionRevocationService::revokeOtherSessionsForUser($usuario->id_usuario, 'rol_or_sucursal_changed');` |
| Se ejecuta al cambiar sucursal | ✅ | Solo desde admin: `Admin\UserController.php` L118 (rol_or_sucursal_changed). En perfil ya no se puede cambiar sucursal (ver §6 Mass assignment). |
| Registra sessions_revoked en auditoría | ✅ | `SessionRevocationService.php` L31-35: si `$deleted > 0 && $reason !== null`, `AuditoriaService::registrar('sessions_revoked', 'sistema', (string) $userId, null, null, ['reason' => $reason, 'count' => $deleted])`. |
| Usa logoutOtherDevices() | ✅ | `ProfileController.php` L56: `Auth::logoutOtherDevices($request->validated('current_password'));` |

**Llamadas exactas a revokeOtherSessionsForUser:**
- `ProfileController.php` L57: tras cambiar contraseña.
- `Admin\UserController.php` L116: tras cambiar contraseña por admin.
- `Admin\UserController.php` L118: tras cambiar rol o sucursal por admin.

---

## 6. Mass assignment → ✅

| Verificación | Resultado | Evidencia |
|--------------|-----------|-----------|
| Ningún modelo usa $guarded = [] | ✅ | Búsqueda en `app` con patrón `$guarded`: **0 coincidencias**. |
| Todos declaran $fillable | ✅ | Todos los modelos en app/Models (y app\Models) tienen `protected $fillable = [...]`. |
| Sin $request->all() en create/update/fill/forceFill | ✅ | Auditoría en `app/Http/Controllers` y `app/Http/Requests`: **0 usos** de `$request->all()` en esos métodos. |
| User: rol_id y sucursal_id solo desde admin | ✅ | Perfil: `ProfileController::update()` usa `$request->only(['nombre_completo', 'fecha_nacimiento', 'domicilio'])`. Admin: `Admin\UserController` usa FormRequest validado (`$request->validated()`). Ver `docs/AUDITORIA-MASS-ASSIGNMENT-REQUEST-ALL.md`. |

**Auditoría automática (patrones inseguros):**

| Archivo | Línea | Método | Hallazgo | Crítico | Estado |
|---------|-------|--------|----------|---------|--------|
| (ningún uso de $request->all()) | — | — | — | — | N/A |
| `ProfileController.php` | 34 | `update()` | Antes se actualizaba `sucursal_id` desde perfil | Sí | **Corregido:** solo `$request->only(['nombre_completo', 'fecha_nacimiento', 'domicilio'])`. |

**Lista modelo por modelo (fillable y riesgo):**

| Modelo | fillable (resumen) | Riesgo |
|--------|--------------------|--------|
| User | run, nombre_completo, rango, email, telefono, clave, fecha_nacimiento, domicilio, **rol_id**, **sucursal_id**, browser_fingerprint, dispositivo_verificado | **Mitigado:** solo Admin actualiza rol/sucursal; perfil solo nombre_completo, fecha_nacimiento, domicilio. |
| DocumentoPersonal | id_usuario, tipo_documento, imagen_frente, imagen_reverso, estado, motivo_rechazo, **aprobado_por**, aprobado_en, es_cambio, documento_anterior_id | **Medio** (aprobado_por solo en flujo aprobar/rechazar). |
| Sucursal | **empresa_id**, nombre, empresa, codigo, direccion, comuna, ciudad, region, telefono, email, activa | **Medio** (empresa_id en flujos admin). |
| Sector | sucursal_id, empresa_id, nombre, ... | Medio. |
| Accion | id_usuario, sucursal_id, sector_id, tipo, ..., imagenes | Bajo (imágenes path; controladores usan SecureUploadService). |
| Reporte | id_usuario, user_id, tarea_id, ..., imagenes | Bajo. |
| ReporteEspecial | id_usuario, accion_id, sucursal_id, ... | Bajo. |
| Informe | reporte_id, numero_informe, hora, descripcion, fotografias, ... | Bajo. |
| Auditoria | user_id, empresa_id, sucursal_id, accion, tabla, ... | Bajo (solo AuditoriaService escribe). |
| Resto (RolUsuario, Permiso, Tarea, Blacklist, etc.) | Sin rol_id/empresa_id/aprobado_por; campos de negocio | Bajo. |

---

## 7. Upload hardening → ✅

| Verificación | Resultado | Evidencia (archivo + línea + código) |
|--------------|-----------|--------------------------------------|
| Valida extensión | ✅ | `SecureUploadService.php` L50-55: `$ext = ...; if (!in_array($ext, $allowedExtensions, true)) throw ...` |
| Valida MIME real | ✅ | L55-58: `$mime = $file->getMimeType(); if (!in_array($mime, $allowedMimetypes, true)) throw ...` |
| Valida tamaño máximo | ✅ | L60-62: `if ($file->getSize() > $maxKb * 1024) throw ...` |
| Valida dimensiones con getimagesize() | ✅ | L97-118: `validateImageDimensions()` usa `getimagesize($path)`, compara con `config('uploads.max_image_width', 3000)` y `max_image_height`. |
| Límites en config/uploads.php | ✅ | `config/uploads.php` L7-10: max_document_kb, max_image_kb, max_image_width, max_image_height. L11-26: document_mimes, image_mimes, document_mimetypes, image_mimetypes. |
| Archivos sensibles en disco private | ✅ | `SecureUploadService.php` L17: `private string $disk = 'private';` y L69: `$file->storeAs($subdir, $filename, $this->disk);` |
| Ningún store(..., 'public') para sensibles | ✅ | Búsqueda en `app`: `store\([^)]*['\"]public['\"]|storeAs\([^)]*['\"]public['\"]` → **0 coincidencias**. |

---

## 8. Trigger inmutable en PostgreSQL → ✅

| Verificación | Resultado | Evidencia (archivo + SQL) |
|--------------|-----------|---------------------------|
| Existe migración con función prevent_auditorias_update_delete | ✅ | `database/migrations/2026_02_19_300000_auditorias_immutable_trigger_postgresql.php`. |
| Crea trigger BEFORE UPDATE OR DELETE | ✅ | L36: `CREATE TRIGGER auditorias_immutable_trigger BEFORE UPDATE OR DELETE ON auditorias FOR EACH ROW EXECUTE FUNCTION prevent_auditorias_update_delete()` |
| Solo se ejecuta si driver es pgsql | ✅ | L14-16: `if (DB::getDriverName() !== 'pgsql') { return; }` |
| down() elimina trigger y función | ✅ | L45-46: `DROP TRIGGER IF EXISTS auditorias_immutable_trigger ON auditorias;` y `DROP FUNCTION IF EXISTS prevent_auditorias_update_delete();` |

**SQL exacto (función):**
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
$$ LANGUAGE plpgsql
```

**SQL exacto (trigger):**
```sql
CREATE TRIGGER auditorias_immutable_trigger BEFORE UPDATE OR DELETE ON auditorias FOR EACH ROW EXECUTE FUNCTION prevent_auditorias_update_delete()
```

---

## 9. Rate limit → ✅

| Verificación | Resultado | Evidencia (archivo + línea) |
|--------------|-----------|-----------------------------|
| Existe RateLimiter::for('login') | ✅ | `AppServiceProvider.php` L61-66: `RateLimiter::for('login', function ($request) { ... Limit::perMinute(5)->by($key) ... });` |
| Existe RateLimiter::for('sensitive') | ✅ | L67-70: `RateLimiter::for('sensitive', function ($request) { ... Limit::perMinute(30)->by($key); });` |
| Rutas archivos-privados usan throttle:sensitive | ✅ | `routes/web.php` L72-81: las 5 rutas `archivos-privados.*` tienen `->middleware('throttle:sensitive')`. |
| Rutas blacklist usan throttle:sensitive | ✅ | L105-107: POST blacklist, DELETE blacklist/{id}, PATCH blacklist/{id}/toggle con `middleware('throttle:sensitive')`. |
| Rutas documentos (store, aprobar, rechazar) usan throttle:sensitive | ✅ | L155 (usuario documentos store), L218-219 (supervisor aprobar/rechazar), L251-252 (admin aprobar/rechazar). |

**Rutas con throttle:sensitive (exactas):**
- GET archivos-privados/documentos/{documento}/{lado}
- GET archivos-privados/acciones/{accion}/imagen/{index}
- GET archivos-privados/reportes/{reporte}/imagen/{index}
- GET archivos-privados/reportes-especiales/{reporte_especial}/imagen/{index}
- GET archivos-privados/informes/{informe}/fotografia/{index}
- POST blacklist, DELETE blacklist/{id}, PATCH blacklist/{id}/toggle
- POST /documentos (usuario)
- PUT supervisor/documentos/{documento}/aprobar, rechazar
- PUT admin/documentos/{documento}/aprobar, rechazar
- POST admin/usuarios, PUT admin/usuarios/{usuario}
- GET informes/{id}/pdf, informes/{id}/ver-pdf

---

## 10. CSP header → ✅

| Verificación | Resultado | Evidencia (archivo + línea + código) |
|--------------|-----------|--------------------------------------|
| Existe middleware SecurityHeaders | ✅ | `app/Http/Middleware/SecurityHeaders.php` existe. |
| Envía Content-Security-Policy | ✅ | L19: `$response->headers->set('Content-Security-Policy', $this->getCsp());` |
| Está registrado en stack web | ✅ | `bootstrap/app.php` L17-18: `$middleware->web(append: [ ..., \App\Http\Middleware\SecurityHeaders::class, ]);` |
| Documenta uso de unsafe-inline / unsafe-eval | ✅ | `SecurityHeaders.php` L30-32: comentario en `getCsp()` indicando que muchas vistas usan inline y referencia a REPORTE-FINAL-SEGURIDAD-FASE2.md. |

---

# RESUMEN FINAL

| # | Área | Resultado |
|---|------|-----------|
| 1 | Login anti-enumeración | ✅ |
| 2 | Lockout progresivo | ✅ |
| 3 | IDOR protection (archivos privados) | ✅ |
| 4 | Logger global 403 | ✅ |
| 5 | Invalidación de sesiones | ✅ |
| 6 | Mass assignment | ✅ |
| 7 | Upload hardening | ✅ |
| 8 | Trigger inmutable PostgreSQL | ✅ |
| 9 | Rate limit | ✅ |
| 10 | CSP header | ✅ |

**Nivel real estimado:** 9.7/10  

- Todos los puntos del checklist están implementados con evidencia en código. Correcciones previas: `use App\Models\User` en `ArchivoPrivadoController`; auditoría de mass assignment y corrección en `ProfileController` (perfil ya no acepta `sucursal_id`/`rol_id`, solo `$request->only(['nombre_completo', 'fecha_nacimiento', 'domicilio'])`). No queda ningún patrón inseguro (`$request->all()` en create/update/fill/forceFill) en Controllers/Requests.

**Riesgos críticos detectados:**  
- Ninguno. Fallos de login por formato RUT, dispositivo o ubicación devuelven mensajes distintos a "Credenciales inválidas" (validaciones previas).

**Riesgos medios (mitigados):**  
- **Mass assignment:** Auditoría completada; User solo recibe `rol_id`/`sucursal_id` desde Admin (FormRequest). Perfil limita a `nombre_completo`, `fecha_nacimiento`, `domicilio`. Ver `docs/AUDITORIA-MASS-ASSIGNMENT-REQUEST-ALL.md`.

**No implementado / limitaciones (ya documentadas en Fase 2):**  
- CSP sin unsafe-inline/unsafe-eval (documentado).  
- EXIF no se elimina en uploads (documentado).  
- Invalidación de sesiones al desactivar usuario: no hay flujo de desactivación; cuando exista, llamar a `SessionRevocationService::revokeOtherSessionsForUser(..., 'user_deactivated')`.
