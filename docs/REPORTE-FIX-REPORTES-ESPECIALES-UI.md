# Reporte — Fix UI “Todos los Reportes Especiales” (cards compactas)

**Fecha:** 2026-02-21  
**Objetivo:** Rediseñar el listado en `/admin/reportes-especiales` para eliminar cards gigantes (panel naranja), usar layout compacto, responsivo y paginado.

---

## 1. Archivos involucrados

| Tipo      | Ruta |
|-----------|------|
| **Ruta**  | `GET /admin/reportes-especiales` → `admin.reportes-especiales.index` |
| **Controller** | `app/Http/Controllers/Admin/ReporteEspecialController.php` |
| **Vista** | `resources/views/admin/reportes-especiales/index.blade.php` |
| **CSS**   | Clase `.truncate-2lines` añadida en la misma vista (blade) dentro de `<style>` |

---

## 2. Cambios realizados

### 2.1 Controller

- **Paginación:** `paginate(20)` sustituido por `paginate(15)->withQueryString()`.
- **Filtros en paginación:** `withQueryString()` mantiene sucursal_id, tipo, estado, fecha_desde, fecha_hasta e id_usuario en los enlaces de paginación.
- **Estadísticas:** Sin cambios; se siguen calculando `$totalReportes`, `$reportesPorEstado` y `$reportesPorTipo` con los mismos criterios (incl. filtros de sucursal donde aplica).

### 2.2 Vista (blade)

**Contadores (estadísticas):**

- Antes: 4 cards grandes con gradiente, iconos y `p-6` / `text-3xl`.
- Después: Grid compacto `grid-cols-2 md:grid-cols-4`, `gap-3`, `p-3`, `text-xl` para el número y `text-xs` para la etiqueta. Sin iconos para reducir altura.

**Listado (cards de cada reporte):**

- Antes: Una card por fila con:
  - Barra lateral izquierda de color (gradiente) con ancho fijo `md:w-48` y gran altura (panel naranja/amarillo/rojo/morado).
  - Bloque derecho con grid de 3 columnas y botón “Ver Detalle”.
- Después:
  - **Grid de listado:** `grid-cols-1 md:grid-cols-2 xl:grid-cols-3` (1 col móvil, 2 en md, 3 en xl).
  - **Card compacta por ítem:**
    - Borde izquierdo fino (4px) con color según tipo (`border-l-4` + clase por tipo: yellow/red/purple/orange). Sin barra vertical alta.
    - **Header:** Fecha y hora en una línea; badge de estado (Pendiente / En revisión / Completado / Rechazado).
    - **Body en 2 columnas (responsive):** Izquierda: Usuario, Sucursal, Tipo. Derecha: botón “Ver detalle” alineado arriba a la derecha.
    - **Resumen (opcional):** Hasta 2 líneas de texto con `Str::limit(..., 80)` y clase `truncate-2lines` para limitar a 2 líneas visuales (novedad o acción).
  - Sin `min-height` ni alturas fijas grandes; padding `p-3`.

**Truncado de texto:**

- Clase `.truncate-2lines` definida en la misma vista:
  - `overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-clamp: 2;`
- Aplicada a Usuario, Sucursal y al párrafo de resumen para que las cards no crezcan por texto largo.

**Botón “Ver detalle”:**

- Ruta corregida de `route('reportes-especiales.show', $reporte)` a `route('admin.reportes-especiales.show', $reporte)` para que el listado admin lleve al detalle admin.

**Paginación:**

- Se mantiene al final del listado: `{{ $reportes->links() }}`. Con `withQueryString()` en el controller, los enlaces de página conservan los parámetros de filtro.

### 2.3 Filtros y funcionalidad

- Formulario de filtros (Sucursal, Tipo, Estado, etc.) sin cambios.
- Botones “Filtrar” y “Limpiar” sin cambios.
- Enlace “Volver” sin cambios.

---

## 3. Antes / Después (descripción)

| Aspecto | Antes | Después |
|---------|--------|---------|
| **Card por reporte** | Una fila completa por ítem; barra vertical de color ancha y alta (panel naranja/amarillo/etc.). | Card compacta con borde izquierdo fino 4px; contenido en bloque único con header + body en grid; sin barras altas. |
| **Grid del listado** | `space-y-4` (una card por fila, apilado). | `grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3` (2–3 cards por fila en desktop). |
| **Contadores** | 4 cards grandes con gradiente, iconos, `p-6`, `text-3xl`. | 4 bloques compactos `p-3`, `text-xl`, grid 2 cols en móvil y 4 en md. |
| **Paginación** | `paginate(20)` y `appends(request()->query())`. | `paginate(15)->withQueryString()` y `$reportes->links()`. |
| **Ruta “Ver detalle”** | `reportes-especiales.show` (ruta pública). | `admin.reportes-especiales.show`. |

---

## 4. Paginación

- **Controller:** Sí, se usa `->paginate(15)->withQueryString()`.
- **Vista:** Sí, se muestra `{{ $reportes->links() }}` debajo del grid.
- Los filtros se conservan en la querystring al cambiar de página.

---

## 5. Checklist de verificación

| Ítem | Estado |
|------|--------|
| Las cards ya no son gigantes | OK |
| No existe barra vertical alta por ítem (solo borde izquierdo fino 4px) | OK |
| Layout responsivo (1 col móvil, 2–3 en desktop) | OK |
| Botón “Ver detalle” funciona y apunta a `admin.reportes-especiales.show` | OK |
| Filtros siguen filtrando | OK |
| Paginación visible y funcional, con 15 ítems por página | OK |
| Con muchos registros no hay scroll infinito (hay paginación) | OK |
| Contadores compactos y adaptados en móvil (2 columnas) | OK |
| Textos largos truncados (máx. 2 líneas / 80 caracteres en resumen) | OK |

---

## 6. Comandos de verificación (PowerShell)

```powershell
cd C:\Users\UrraDac\Documents\GitHub\web-seguridad
php artisan route:list 2>$null | Select-String -Pattern "reportes-especiales"
```

Ruta esperada: `admin/reportes-especiales` → `Admin\ReporteEspecialController@index`.
