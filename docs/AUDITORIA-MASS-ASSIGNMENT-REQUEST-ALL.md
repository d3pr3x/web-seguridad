# Auditoría automática: $request->all() y actualización de User

**Fecha:** 2026-02-18  
**Alcance:** `app/Http/Controllers` y `app/Http/Requests`.  
**Patrones buscados:** `$request->all()` en `create()`, `update()`, `fill()`, `forceFill()`; y cualquier actualización de User que permita `rol_id` o `sucursal_id` desde rutas no admin.

---

## Resultado de búsqueda

| Búsqueda | Resultado |
|----------|-----------|
| `$request->all()` | **0 coincidencias** en app/Http. |
| `->fill($request->...)` / `->forceFill($request->...)` | **0 coincidencias**. |
| `::create($request->...)` | **0 coincidencias**. |

---

## Tabla de hallazgos (patrones inseguros o sensibles)

| Archivo | Línea | Método | Hallazgo | ¿Crítico? |
|---------|-------|--------|----------|-----------|
| `app/Http/Controllers/ProfileController.php` | 37-42 | `update()` | `$user->update([...])` incluía `sucursal_id` desde el request. Ruta perfil (no admin) permitía asignar `sucursal_id` al User. | **Sí** |

**Resto de controladores revisados:**  
- `Admin\UserController`: usa `$validated = $request->validated()` (AdminStoreUserRequest / AdminUpdateUserRequest) con `authorize()` que exige administrador; `rol_id` y `sucursal_id` solo desde admin. ✅  
- `Auth/LoginController`: `$user->update([...])` solo con `browser_fingerprint` y `dispositivo_verificado`. ✅  
- `UsuarioPerfilController`: `$user->update(['clave' => ...])` solo clave. ✅  
- Ningún otro controlador escribe en User con datos del request sin validación explícita o FormRequest.

---

## Corrección aplicada

**Archivo:** `app/Http/Controllers/ProfileController.php`

**Antes:**  
- Validación incluía `'sucursal_id' => 'nullable|exists:sucursales,id'`.  
- `$user->update(['nombre_completo' => ..., 'fecha_nacimiento' => ..., 'domicilio' => ..., 'sucursal_id' => $request->sucursal_id])`.  
- Revocación de sesiones cuando cambiaba sucursal.

**Después:**  
- Validación solo `nombre_completo`, `fecha_nacimiento`, `domicilio`.  
- `$user->update($request->only(['nombre_completo', 'fecha_nacimiento', 'domicilio']))`.  
- Eliminada la revocación de sesiones por cambio de sucursal en perfil (sucursal ya no se actualiza desde perfil).

Con esto, **rol_id** y **sucursal_id** del modelo User solo pueden asignarse desde rutas admin (Admin\UserController con AdminUpdateUserRequest / AdminStoreUserRequest).

**Nota:** La vista `resources/views/profile/index.blade.php` sigue mostrando el campo sucursal; si se envía, el backend lo ignora. Opcional: convertir ese campo en solo lectura o quitarlo del formulario.
