# Orden y dependencias de migraciones

## Orden de ejecución (por fecha en nombre de archivo)

1. **2025_12_31_000000** – `jerarquias` (tabla base)
2. **2025_12_31_000001** – `jerarquia_rol` (depende: jerarquias, roles_usuario)
3. **2025_12_31_000002** – `empresas` (depende: jerarquias; incluye `activa`, `deleted_at`)
4. **2025_12_31_000003** – `sucursales` (depende: empresas; incluye `activa`, `deleted_at`)
5. **2025_12_31_000004** – `roles_usuario`
6. **2025_12_31_000005** – `permisos`
7. **2025_12_31_000006** – `rol_permiso`
8. **2025_12_31_000007** – `usuarios` (depende: roles_usuario, sucursales; incluye `deleted_at`)
9. … resto tablas consolidadas (tareas, sectores, reportes, ingresos, etc.)
10. **2026_02_19_100001** – `modalidades_jerarquia`
11. **2026_02_19_100002** – `modalidad_roles` (depende: modalidades_jerarquia, roles_usuario)
12. **2026_02_19_100003** – `empresas.modalidad_id` (depende: empresas, modalidades_jerarquia)
13. **2026_02_19_100004** – **Soft deletes** en tablas importantes (debe ir **antes** de índices parciales)
14. **2026_02_19_100005** – Columnas `activo`/`activa` en catálogos
15. **2026_02_19_100006** – `auditorias`
16. **2026_02_19_100007** – Índices adicionales (sectores, sucursales, auditorias)
17. **2026_02_19_200000** – **Índices parciales PostgreSQL** (WHERE deleted_at IS NULL). Solo se ejecuta si `DB::getDriverName() === 'pgsql'`. Requiere que las tablas tengan `deleted_at` (por tanto, después de 100004).
18. **2026_02_19_200001** – `empresas.modulos_activos` (jsonb en pgsql, json en otros)
19. **2026_02_19_200002** – Auditorías jsonb (PostgreSQL only)
20. **2026_02_19_300000** – Trigger PostgreSQL en `auditorias`: bloquea UPDATE y DELETE (solo INSERT). Solo se ejecuta si el driver es `pgsql`.

## Dependencias clave

- **Soft delete antes de índices parciales:** la migración `2026_02_19_100004_add_soft_deletes_to_all_important_tables` debe ejecutarse antes de `2026_02_19_200000_unique_partial_indexes_postgresql`, ya que los índices usan `WHERE deleted_at IS NULL`.
- **Solo PostgreSQL:** las migraciones que usan `jsonb` o índices parciales comprueban `DB::getDriverName() === 'pgsql'` (o `config('database.default') === 'pgsql'`) y no se aplican en MySQL/SQLite.
- **Columnas activo/activa:** existen en empresas (`activa`), sucursales (`activa`), sectores (`activo`), roles_usuario/permisos/tareas/puntos_ronda/etc. (`activo`) añadidas en 100005 o en las tablas base.

## Tablas con `deleted_at` (soft delete)

Añadidas en 100004: empresas, sucursales, sectores, usuarios, roles_usuario, permisos, rol_permiso, tareas, detalles_tarea, reportes, informes, acciones, reportes_especiales, puntos_ronda, escaneos_ronda, ingresos, blacklists, personas, documentos, reuniones, dias_trabajados, configuraciones_sueldo, feriados, ubicaciones_permitidas, dispositivos_permitidos, grupos_incidentes, tipos_incidente, modalidades_jerarquia, modalidad_roles.

## Nota sobre tabla `auditoria` (singular)

Existe además la migración `2026_02_18_100007_create_auditoria_table` que crea la tabla `auditoria`. La aplicación usa la tabla `auditorias` (modelo `Auditoria`). Ambas pueden coexistir; la funcionalidad de auditoría del sistema usa `auditorias`.
