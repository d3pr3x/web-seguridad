# Reporte UI — Menú y Dashboard por rol (Opción B: “solo ve lo que usa”)

**Fecha:** 2026-02-21  
**Objetivo:** Menú y dashboards coherentes; cada rol solo ve lo que usa; secciones sin ítems no se renderizan.

---

## 1. Menú final por rol (captura textual)

### 1.1 USUARIO / GUARDIA

**Secciones visibles (solo las que tienen ítems):**

| Sección    | Ítems visibles (orden) | Rutas |
|-----------|-------------------------|-------|
| (inicio)  | Inicio                  | `usuario.index` |
| Operación | Control de acceso       | `ingresos.index` |
|           | Rondas QR               | `usuario.ronda.index` |
|           | Incidentes (collapse)   | —     |
|           | ├ Novedades             | `usuario.acciones.index` |
|           | └ Reportes             | `usuario.reportes.index` |
|           | Mis documentos (collapse, si módulo) | `usuario.documentos.index` |
| Personal  | Mi perfil               | `usuario.perfil.index` |
| (fijo)    | Instalar aplicación     | —     |
| (fijo)    | Cerrar sesión           | `logout` |

- Control de acceso solo si `module:control_acceso` y `puedeVerControlAcceso()`.
- Rondas QR solo si `module:rondas_qr` y `puedeVerRondasQR()`.
- Incidentes solo si `puedeVerMisReportes()`.
- Mis documentos solo si `module:documentos_guardias`.

---

### 1.2 SUPERVISOR

**Secciones visibles:**

| Sección        | Ítems visibles (orden) | Rutas |
|----------------|------------------------|-------|
| (inicio)       | Inicio                 | `supervisor.index` |
| Operación      | Reportes y estadísticas (collapse) | — |
|                | ├ Reporte escaneos QR  | `admin.rondas.reporte` |
|                | ├ Novedades            | `admin.novedades.index` |
|                | ├ Reportes             | `admin.reportes-especiales.index` |
|                | ├ Reporte por sucursal | `admin.reporte-sucursal` |
|                | ├ Todos los reportes  | `admin.reportes-especiales.index` |
|                | └ Reportes diarios    | `admin.reportes-diarios` (si módulo) |
| Supervisión    | Supervisión (collapse) | —     |
|                | ├ Usuarios (solo admin) | `admin.usuarios.index` |
|                | ├ Aprobar documentos  | supervisor/admin documentos |
|                | ├ Novedades            | `admin.novedades.index` |
|                | ├ Grupos de incidentes (solo admin) | `admin.grupos-incidentes.index` |
|                | └ Todos los reportes  | `reportes-especiales.index` / `admin.reportes-especiales.index` |
| Personal       | Mi perfil              | `usuario.perfil.index` |
| (fijo)         | Instalar aplicación / Cerrar sesión | — |

- Si el rol es **Usuario-Supervisor**, además puede verse el acordeón “Usuario” con ítems de usuario (Control de acceso, Rondas QR, Incidentes, etc.).

---

### 1.3 ADMIN

**Secciones visibles:**

| Sección        | Ítems visibles (orden B.2 / uso) | Rutas |
|----------------|-----------------------------------|-------|
| (inicio)       | Inicio                            | `administrador.index` |
| Operación      | Reporte escaneos QR               | `admin.rondas.reporte` |
|                | Novedades                         | `admin.novedades.index` |
|                | Reportes                          | `admin.reportes-especiales.index` |
|                | Reporte por sucursal              | `admin.reporte-sucursal` |
|                | Todos los reportes               | `admin.reportes-especiales.index` |
| Supervisión    | (solo si tiene permiso) Supervisión (collapse) | — |
| Infraestructura| Gestión (collapse)                | —     |
|                | ├ Clientes                        | `admin.clientes.index` |
|                | ├ Dispositivos                    | `admin.dispositivos.index` |
|                | ├ Ubicaciones                     | `admin.ubicaciones.index` |
|                | ├ Sectores                        | `admin.sectores.index` |
|                | ├ Puntos de ronda (QR)            | `admin.rondas.index` |
|                | └ Auditorías                      | `admin.auditorias.index` |
| Personal       | Mi perfil                         | `usuario.perfil.index` |
| (fijo)         | Instalar aplicación / Cerrar sesión | — |

- Admin **no** ve el collapse “Reportes y estadísticas”; ve los 5 ítems de Operación como enlaces directos en el orden B.2.

---

## 2. Evidencia: filtrado de secciones vacías

**Regla A.1:** Solo se renderizan secciones que tienen al menos un ítem visible.

**Implementación en `app/Services/MenuBuilder.php`:**

```php
/**
 * Menú por secciones: solo secciones con al menos un ítem visible.
 */
public function buildMenu(): array
{
    $principales = $this->getItemsPrincipales();
    $grouped = collect($principales)->groupBy('section');
    $sections = [];
    foreach ($this->definicionSecciones() as $def) {
        $key = $def['key'];
        $items = $grouped->get($key, collect())->values()->all();
        if (count($items) > 0) {
            $sections[] = ['key' => $key, 'label' => $def['label'], 'items' => $items];
        }
    }
    return $sections;
}
```

- **Collapse sin hijos:** En `getItems()` se excluyen ítems de tipo `collapse` cuyo array `children` (tras filtrado por permiso/módulo) queda vacío:

```php
if ($resolved['type'] === 'collapse' && empty($resolved['children'])) {
    continue;
}
```

Con esto, una sección solo aparece si tiene al menos un ítem, y ningún encabezado de sección se muestra vacío.

---

## 3. Dashboard cards por rol

### 3.1 USUARIO — `/usuario` (`usuario/index.blade.php`)

| Card / Bloque      | Orden | Ruta                    | Condición |
|--------------------|-------|-------------------------|-----------|
| Control de acceso  | 1     | `ingresos.index`        | `module:control_acceso` + `puedeVerControlAcceso()` |
| Rondas QR         | 2     | `usuario.ronda.index`   | `module:rondas_qr` + `puedeVerRondasQR()` |
| Incidentes        | 3     | (bloque con 2 cards)    | `puedeVerMisReportes()` |
| ├ Novedades       | —     | `usuario.acciones.index`| — |
| └ Reportes        | —     | `usuario.reportes.index`| — |
| Mi actividad      | (abajo) | Historial reportes / Registro puntos escaneados | Solo historial; mismos destinos que menú |

- Solo 3 bloques principales de uso (Control de acceso, Rondas QR, Incidentes).
- “Mi actividad” solo muestra historial, sin duplicar accesos principales.

### 3.2 SUPERVISOR — `/supervisor` (`supervisor/index.blade.php`)

| Card / Bloque   | Orden | Ruta                    | Condición |
|-----------------|-------|-------------------------|-----------|
| Incidentes      | 1     | Novedades / Reportes    | `puedeVerMisReportes()` (Usuario-Supervisor) |
| Supervisión     | 2     | Documentos, Novedades, Todos reportes | — |
| Reportes y estadísticas | 3 | Varios                  | Permisos reporte/estadísticas |
| (Supervisor-Usuario) Incidentes | (abajo) | Novedades / Reportes | `puedeVerMisReportes()` |

- Mismo lenguaje que el menú: Incidentes (Novedades + Reportes).

### 3.3 ADMIN — `/administrador` (`administrador/index.blade.php`)

| Sección / Card   | Orden (B.2) | Ruta                         | Condición |
|------------------|-------------|------------------------------|-----------|
| **Operación**    | —           | —                            | Al menos un permiso de operación |
| Reporte escaneos QR | 1        | `admin.rondas.reporte`       | `rondas_qr` + `puedeVerReportesEstadisticasCompletos()` |
| Novedades        | 2           | `admin.novedades.index`      | `puedeVerSupervision()` |
| Reportes         | 3           | `admin.reportes-especiales.index` | `puedeVerReportesEstadisticasCompletos()` |
| Reporte por sucursal | 4      | `admin.reporte-sucursal`     | `puedeVerReporteSucursal()` |
| Todos los reportes | 5        | `admin.reportes-especiales.index` | `puedeVerReportesEstadisticasCompletos()` |
| **Supervisión**  | —           | Usuarios, Documentos, Novedades, Grupos, Reportes | `puedeVerSupervision()` |
| **Gestión**      | —           | Clientes, Dispositivos, Ubicaciones, Sectores, Rondas QR, Auditorías | `puedeVerGestion()` |

- Operación ordenada según B.2; solo se muestran secciones con contenido.

---

## 4. Script de verificación (Windows PowerShell)

Ejecutar en la raíz del proyecto:

```powershell
# Limpiar caché
php artisan optimize:clear

# Rutas clave (usuario, supervisor, admin, menú/dashboard)
php artisan route:list 2>$null | Select-String -Pattern "usuario\.(index|acciones|reportes|ronda|perfil)|supervisor\.index|administrador\.index|ingresos\.index|admin\.(novedades|reportes-especiales|reporte-sucursal|rondas\.reporte|reportes-diarios)"
```

Para comprobar que las rutas existen sin filtrar por patrón:

```powershell
php artisan route:list --columns=method,uri,name
```

---

## 5. Checklist final (DONE)

- [x] **Admin:** Sidebar con Operación en orden B.2 (Reporte escaneos QR → Novedades → Reportes → Reporte por sucursal → Todos los reportes).
- [x] **Usuario:** Sidebar orden B.3 (Inicio → Control de acceso → Rondas QR → Incidentes (Novedades, Reportes) → Mi perfil → Instalar aplicación → Cerrar sesión); sin secciones vacías.
- [x] **Usuario /usuario:** Título principal “Incidentes” con solo 2 cards: Novedades y Reportes.
- [x] **Dashboard ↔ Sidebar:** Mismos nombres, mismo orden y mismas rutas por rol.
- [x] **Reporte generado:** `docs/REPORTE-UI-MENU-ROL.md` con menú por rol, evidencia de filtrado de secciones vacías, tabla de cards por rol y script PowerShell.

---

## 6. Archivos modificados en esta fase

| Archivo | Cambio |
|---------|--------|
| `app/Services/MenuBuilder.php` | Secciones (inicio, operacion, supervision, infraestructura, personal); `definicionSecciones()`; ítems con `section`; Admin Operación B.2 (5 ítems); `reportes_estadisticas` con `admin_hide`; collapse sin hijos no se añade; `buildMenu()`; filtrado por módulo en hijos con `module_enabled_for_empresa`. |
| `resources/views/components/usuario/sidebar.blade.php` | Uso de `buildMenu()`; render por secciones; separador solo si `section['label']` no vacío. |
| `resources/views/components/usuario/mobile-menu.blade.php` | Igual que sidebar con `buildMenu()`. |
| `resources/views/administrador/index.blade.php` | Bloque “Operación” en orden B.2; solo se muestra si hay al menos un ítem visible. |
| `docs/REPORTE-UI-MENU-ROL.md` | Nuevo: menú por rol, evidencia filtrado, tabla dashboard cards, script PowerShell, checklist. |
