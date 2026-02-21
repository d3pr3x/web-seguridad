# Política obligatoria de migraciones del proyecto

A partir de ahora se deben seguir estrictamente estas reglas al generar migraciones.

## Antes de crear una nueva migración

Evaluar:

| ¿La tabla ya existe en producción? | Acción |
|------------------------------------|--------|
| No | **Modificar la migración original** y dejarla con la estructura final correcta. |
| Sí | Crear **una nueva migración incremental** (solo una por cambio). |

## Reglas

### Una migración por tabla (estructura final consolidada)

- No generar migraciones incrementales para agregar, modificar o eliminar columnas si la tabla **aún no está en producción**.
- Si la tabla no ha sido desplegada, modificar la migración **original** con la estructura final.
- Solo se permite una migración adicional si la tabla **ya está en producción**.

### Evitar migraciones redundantes

No generar migraciones tipo:

- `add_xxx_to_table`
- `modify_xxx_in_table`
- `change_xxx_type`

En su lugar, **consolidar** todos los cambios en la migración principal de la tabla.

### Estructura limpia y definitiva

Cada migración debe:

- Incluir **todas las columnas definitivas**.
- Incluir **índices y foreign keys** finales.
- Definir correctamente: `nullable`, `default`, `unique`, `cascade on delete/update`.
- Incluir `timestamps()` solo si realmente se necesitan.

### Seeders organizados

- Un seeder por **entidad lógica**; no múltiples seeders fragmentados.
- Evitar seeders duplicados.
- Consolidar datos estáticos en **un solo seeder por módulo**.

### Orden lógico

1. Primero tablas base.
2. Luego tablas con relaciones.
3. Luego tablas pivot.

### Prohibido

- No crear migraciones para pruebas temporales.
- No generar migraciones que luego serán reemplazadas.
