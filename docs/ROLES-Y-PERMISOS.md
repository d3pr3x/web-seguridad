# Roles y permisos (solo base de datos)

Este proyecto **no utiliza Spatie Laravel Permission** ni ningún paquete externo de roles. Toda la autorización se basa en:

- **Tabla `roles_usuario`**: catálogo de roles (ADMIN, SUPERVISOR, USUARIO, GUARDIA, etc.).
- **Columna `usuarios.rol_id`**: FK al rol asignado a cada usuario.
- **Tablas opcionales `permisos` y `rol_permiso`**: para permisos granulares por rol si se desea.

## Cómo se usa en el código

- **Modelo `User`**: relación `$user->rol` (RolUsuario). Métodos de perfil: `esAdministrador()`, `esSupervisor()`, `esUsuario()`, `esGuardiaControlAcceso()`, etc. Métodos de acceso: `puedeVerControlAcceso()`, `puedeVerGestion()`, `puedeVerSupervision()`, etc. Todos consultan `$this->rol->slug` contra la BD.
- **Permisos por slug**: `$user->tienePermiso('slug')` comprueba la tabla `rol_permiso` / `permisos` si el rol tiene permisos asignados.
- **Gates de Laravel**: en `AppServiceProvider` se registran Gates (`ver-gestion`, `es-admin`, etc.) que delegan en los métodos anteriores. Se puede usar `@can('ver-gestion')` en Blade o `Gate::allows('ver-gestion')` en PHP.

## Añadir o cambiar roles

1. Insertar o editar filas en `roles_usuario` (nombre, slug, descripcion).
2. Asignar `rol_id` a cada usuario en `usuarios`.
3. Opcional: asignar permisos al rol en `rol_permiso` (rol_id, permiso_id) y usar `tienePermiso('slug')` donde haga falta.

No se requiere ni se usa ningún paquete Spatie de roles o permisos.
