# Reset de desarrollo y verificación

Instrucciones para dejar la base de datos y caché en estado limpio y ejecutar el seed completo.

## Comandos de reset (ejecutar en este orden)

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan migrate:fresh --seed
php artisan optimize:clear
```

## Opcionales después del reset

- **Tests:** `php artisan test` (si hay tests definidos)
- **Rutas y middleware:** `php artisan route:list` para confirmar middleware en rutas protegidas

## Requisitos

- Base de datos configurada en `.env` (por ejemplo PostgreSQL o MySQL)
- Las migraciones “solo PostgreSQL” (índices parciales, `jsonb` en auditorías) se ejecutan solo si el driver es `pgsql`; con otros drivers el resto de migraciones y seeders siguen funcionando

## Resultado esperado

Tras `migrate:fresh --seed`:

- Todas las migraciones ejecutadas sin error
- Seeders ejecutados en orden (roles, modalidades, empresas, sucursales, sectores, tareas, grupos incidentes, usuarios, datos demo, etc.)
- Usuarios demo disponibles con contraseña común: **Demo2026!Demo2026!**

Ver lista de usuarios y checklist de navegación en [CHECKLIST-NAVEGACION.md](CHECKLIST-NAVEGACION.md).

## Orden de migraciones y dependencias

Ver [MIGRACIONES-ORDEN.md](MIGRACIONES-ORDEN.md) para el listado de migraciones y dependencias (soft delete antes de índices parciales, uso solo en PostgreSQL donde aplique).
