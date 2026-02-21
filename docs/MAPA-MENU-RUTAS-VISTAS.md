# Mapa Menú → Ruta → Controller → Vista

**Fecha:** 2026-02-21  
**Objetivo:** Auditoría de navegación (sidebar), detección de duplicidades (Reportes / Todos los reportes) y vistas potencialmente no usadas.

---

## 1. Fuente del menú

### 1.1 Archivos del sidebar

| Archivo | Uso |
|---------|-----|
| `app/Services/MenuBuilder.php` | **Source of truth:** define ítems en `definicionItems()`, secciones en `definicionSecciones()`. `buildMenu()` devuelve solo secciones con ítems visibles. |
| `resources/views/components/usuario/sidebar.blade.php` | Render del menú desktop: usa `$menuBuilder->buildMenu()` e itera `$section['items']` (link o collapse con children). |
| `resources/views/components/usuario/mobile-menu.blade.php` | Mismo dato: `$menuBuilder->buildMenu()` + acordeón por rol secundario. |

El menú **no** está hardcodeado en Blade: se construye en PHP desde `MenuBuilder`. Los ítems tienen `route` (nombre de ruta), `label`, `type` (link/collapse), `children` (con `route` y `label`). La visibilidad se decide por:

- **Módulo:** `module_enabled_for_empresa($module, $empresa)` en ítem y en hijos.
- **Permiso:** `$user->puedeVer*()` (p. ej. `puedeVerControlAcceso`, `puedeVerRondasQR`, `puedeVerMisReportes`, `puedeVerReporteSucursal`, `puedeVerReportesEstadisticasCompletos`, `puedeVerSupervision`, `puedeVerGestion`).
- **Rol:** `admin_hide` (admin no ve “Reportes y estadísticas” collapse), `admin_only` en hijos (solo admin ve Usuarios, Grupos de incidentes).
- **Tier:** usuario / supervisor / admin según `tierOrden` y `tierPrincipal` / `tierSecundario` (acordeón).

Rutas con `route_supervisor` / `route_admin` se resuelven en `filterChildren()` según `$user->esAdministrador()`.

---

## 2. Tabla principal (Menú → Ruta → Controller → Vista)

Orden por sección (inicio, operacion, supervision, infraestructura, personal) y por ítem dentro de cada una.  
Rutas que dependen del rol (inicio, Aprobar documentos, Todos los reportes en Supervisión) se indican en Observaciones.

| Grupo | Label visible | routeName / href | URI | Controller@method | Vista final (blade) | Observaciones |
|-------|----------------|------------------|-----|-------------------|---------------------|---------------|
| (inicio) | Inicio | *(resuelto por rol)* | usuario / supervisor / administrador | UsuarioController@index / SupervisorController@index / AdministradorController@index | usuario.index, supervisor.index, administrador.index | Ruta dinámica según rol. |
| Operación | Control de acceso | ingresos.index | ingresos | IngresosController@index | ingresos.listado | Módulo control_acceso. |
| Operación | Rondas QR | usuario.ronda.index | usuario/ronda | UsuarioRondaController@index | usuario.ronda.index | Módulo rondas_qr. |
| Operación | Incidentes | *(collapse)* | — | — | — | Padre sin ruta. |
| Operación | → Novedades | usuario.acciones.index | usuario/acciones | UsuarioAccionController@index | usuario.acciones.index | |
| Operación | → Reportes | usuario.reportes.index | usuario/reportes | UsuarioReporteController@index | usuario.reportes.index | |
| Personal | Mi perfil | usuario.perfil.index | usuario/perfil | UsuarioPerfilController@index | usuario.perfil.index | |
| Operación | Mis documentos | *(collapse)* | — | — | — | Módulo documentos_guardias. |
| Operación | → Ver mis documentos | usuario.documentos.index | usuario/documentos | UsuarioDocumentoController@index | usuario.documentos.index | |
| Operación | Reporte escaneos QR | admin.rondas.reporte | admin/rondas-reporte | Admin\RondaReporteController@index | admin.rondas.reporte | Solo admin (Operación B.2). |
| Operación | Novedades | admin.novedades.index | admin/novedades | Admin\NovedadController@index | admin.novedades.index | Solo admin. |
| Operación | Reportes | admin.reportes-especiales.index | admin/reportes-especiales | Admin\ReporteEspecialController@index | admin.reportes-especiales.index | **Duplicado** (ver §3). |
| Operación | Reporte por sucursal | admin.reporte-sucursal | admin/reporte-sucursal | ReporteSucursalController@index | admin.reporte-sucursal | Solo admin. |
| Operación | Todos los reportes | admin.reportes-especiales.index | admin/reportes-especiales | Admin\ReporteEspecialController@index | admin.reportes-especiales.index | **Duplicado** (ver §3). |
| Operación | Reportes y estadísticas | *(collapse, solo supervisor)* | — | — | — | admin_hide: admin no lo ve. |
| Operación | → Reporte escaneos QR | admin.rondas.reporte | admin/rondas-reporte | Admin\RondaReporteController@index | admin.rondas.reporte | |
| Operación | → Novedades | admin.novedades.index | admin/novedades | Admin\NovedadController@index | admin.novedades.index | |
| Operación | → Reportes | admin.reportes-especiales.index | admin/reportes-especiales | Admin\ReporteEspecialController@index | admin.reportes-especiales.index | **Duplicado**. |
| Operación | → Reporte por sucursal | admin.reporte-sucursal | admin/reporte-sucursal | ReporteSucursalController@index | admin.reporte-sucursal | |
| Operación | → Todos los reportes | admin.reportes-especiales.index | admin/reportes-especiales | Admin\ReporteEspecialController@index | admin.reportes-especiales.index | **Duplicado**. |
| Operación | → Reportes diarios | admin.reportes-diarios | admin/reportes-diarios | Admin\ReporteDiarioController@index | admin.reportes-diarios | Módulo reportes_diarios. |
| Supervisión | Supervisión | *(collapse)* | — | — | — | |
| Supervisión | → Usuarios | admin.usuarios.index | admin/usuarios | Admin\UserController@index | admin.usuarios.index | admin_only. |
| Supervisión | → Aprobar documentos | supervisor.documentos.index / admin.documentos.index | supervisor/documentos o admin/documentos | Supervisor\DocumentoPersonalController@index / Admin\DocumentoPersonalController@index | supervisor.documentos.index / admin.documentos.index | Ruta según rol. |
| Supervisión | → Novedades | admin.novedades.index | admin/novedades | Admin\NovedadController@index | admin.novedades.index | |
| Supervisión | → Grupos de incidentes | admin.grupos-incidentes.index | admin/grupos-incidentes | Admin\GruposIncidentesController@index | admin.grupos-incidentes.index | admin_only. |
| Supervisión | → Todos los reportes | reportes-especiales.index / admin.reportes-especiales.index | reportes-especiales o admin/reportes-especiales | ReporteEspecialController@index / Admin\ReporteEspecialController@index | reportes-especiales.index / admin.reportes-especiales.index | **Ruta según rol.** Supervisor → misma vista conceptual (listado reportes) pero distinta ruta/vista Blade (reportes-especiales.index vs admin.reportes-especiales.index). |
| Infraestructura | Gestión | *(collapse)* | — | — | — | |
| Infraestructura | → Clientes | admin.clientes.index | admin/clientes | Admin\ClienteController@index | admin.clientes.index | |
| Infraestructura | → Dispositivos | admin.dispositivos.index | admin/dispositivos | Admin\DispositivoPermitidoController@index | admin.dispositivos.index | |
| Infraestructura | → Ubicaciones | admin.ubicaciones.index | admin/ubicaciones | Admin\UbicacionPermitidaController@index | admin.ubicaciones.index | |
| Infraestructura | → Sectores | admin.sectores.index | admin/sectores | Admin\SectorController@index | admin.sectores.index | |
| Infraestructura | → Puntos de ronda (QR) | admin.rondas.index | admin/rondas | Admin\PuntoRondaController@index | admin.rondas.index | Módulo rondas_qr. |
| Infraestructura | → Auditorías | admin.auditorias.index | admin/auditorias | Admin\AuditoriasController@index | admin.auditorias.index | |

*(Fuera del MenuBuilder: “Instalar aplicación” no tiene ruta; “Cerrar sesión” → `logout`, Auth\LoginController@logout, sin vista Blade de contenido.)*

---

## 3. Duplicidades detectadas

### 3.1 Admin — Operación: “Reportes” y “Todos los reportes”

| Ítem | routeName | Controller@method | Vista Blade |
|------|-----------|--------------------|-------------|
| Reportes (operacion_reportes) | admin.reportes-especiales.index | Admin\ReporteEspecialController@index | admin/reportes-especiales/index.blade.php |
| Todos los reportes (operacion_todos_reportes) | admin.reportes-especiales.index | Admin\ReporteEspecialController@index | admin/reportes-especiales/index.blade.php |

**Conclusión:** Duplicidad real. Dos ítems del menú admin (Operación) apuntan a la misma ruta, controlador y vista. El usuario ve dos entradas que llevan al mismo listado.

**Recomendación:** Dejar un solo ítem en Operación (por ejemplo “Reportes” o “Todos los reportes”) y eliminar el otro del `definicionItems()` en MenuBuilder.

---

### 3.2 Supervisor — Reportes y estadísticas: “Reportes” y “Todos los reportes”

| Ítem | routeName | Controller@method | Vista Blade |
|------|-----------|-------------------|-------------|
| Reportes (hijo de reportes_estadisticas) | admin.reportes-especiales.index | Admin\ReporteEspecialController@index | admin/reportes-especiales/index.blade.php |
| Todos los reportes (hijo de reportes_estadisticas) | admin.reportes-especiales.index | Admin\ReporteEspecialController@index | admin/reportes-especiales/index.blade.php |

**Conclusión:** Duplicidad real. Dentro del collapse “Reportes y estadísticas” (supervisor) hay dos hijos que llevan al mismo destino.

**Recomendación:** En el collapse “Reportes y estadísticas”, dejar un solo hijo para el listado (p. ej. “Todos los reportes” o “Reportes”) y quitar el duplicado en `definicionItems()`.

---

### 3.3 Supervisión → “Todos los reportes” (ruta según rol)

| Rol | routeName | Vista Blade |
|-----|-----------|-------------|
| Admin | admin.reportes-especiales.index | admin/reportes-especiales/index.blade.php |
| Supervisor | reportes-especiales.index | reportes-especiales/index.blade.php |

**Conclusión:** No es duplicidad de ítem: es un solo ítem con destino distinto según rol (route_supervisor / route_admin). Son dos rutas, dos controladores (ReporteEspecialController vs Admin\ReporteEspecialController) y dos vistas (reportes-especiales.index vs admin.reportes-especiales.index). Intención: supervisor puede usar la ruta “pública” de reportes especiales y admin la de admin.

**Recomendación:** Sin cambio por duplicidad. Si se quisiera unificar en el futuro, habría que valorar una sola ruta/controlador/vista con permisos por rol.

---

## 4. Vistas potencialmente no usadas

Criterio: vistas Blade que **no** aparecen como `return view('...')` en ningún controlador bajo `app/Http/Controllers` ni en `routes/`, y que no son solo partials (incluidas por otros blades).

### 4.1 Vistas referenciadas por controladores (resumen)

Las siguientes vistas **sí** están referenciadas desde algún controller (return view):  
auth.login, ingresos.*, usuario.*, supervisor.*, administrador.index, admin.* (reportes-especiales, novedades, reporte-sucursal, reportes-diarios, rondas.*, documentos, usuarios, clientes, dispositivos, ubicaciones, sectores, grupos-incidentes, auditorias, calculo-sueldos), reportes.index/show, reportes-especiales.*, acciones.*, informes.*, profile.*, tareas.formulario, dias-trabajados.*, sectores.*, inicio-unificado.index.

### 4.2 Posibles pantallas huérfanas o duplicadas

| Vista (path relativo a resources/views) | Comentario |
|----------------------------------------|------------|
| `usuario/novedades/index.blade.php` | UsuarioController tiene `novedades()` que hace `return view('usuario.novedades.index')`, pero en `routes/web.php` **no hay** ruta que llame a `UsuarioController@novedades`. Las rutas de listado/crear del usuario son `usuario/acciones` (UsuarioAccionController) y `usuario/reportes` (UsuarioReporteController). **Vista huérfana** (método sin ruta). |
| `usuario/novedades/create.blade.php` | UsuarioController tiene `novedadesCreate($tipo)` que devuelve esta vista; **no existe ruta** que invoque ese método. **Vista huérfana**. |
| `layouts/app.blade.php` | Layout; puede ser usado por vistas que extienden `@extends('layouts.app')`. No es pantalla de ruta directa. |
| `welcome.blade.php` | Típica página de bienvenida; si la raíz `/` redirige a login o dashboard, puede no estar en uso. |

No se ha detectado ninguna otra vista Blade de “pantalla completa” que no esté referenciada por al menos una ruta/controller. Otras vistas son partials o componentes (p. ej. `components/usuario/sidebar.blade.php`, `mobile-menu.blade.php`) usados por múltiples pantallas.

### 4.3 Comprobación rápida (PowerShell)

Para listar blades y buscar referencias:

```powershell
# Listar blades
Get-ChildItem -Recurse resources\views -Filter "*.blade.php" | Select-Object FullName

# Buscar referencias a vistas en app y routes
rg -n "view\('|view\(" app routes
```

---

## 5. Recomendaciones (sin aplicar cambios)

1. **Fusionar ítems duplicados “Reportes” / “Todos los reportes”:**
   - En **Admin → Operación:** dejar un solo ítem (p. ej. “Todos los reportes” o “Reportes”) que apunte a `admin.reportes-especiales.index`.
   - En **Supervisor → Reportes y estadísticas:** en el collapse, dejar un solo hijo para ese listado (eliminar el otro).

2. **Rutas/nombres:** No es estrictamente necesario renombrar rutas; la duplicidad es de **entradas de menú**, no de nombres de ruta. Si se unifica la etiqueta (“Reportes” vs “Todos los reportes”), se puede mantener una sola ruta y un solo ítem en el menú.

3. **Vistas que podrían eliminarse o reutilizarse (evaluar antes de tocar):**
   - `usuario/novedades/index.blade.php` y `usuario/novedades/create.blade.php`: si se confirma que no hay ruta que las invoque, o bien se eliminan y se limpia el código muerto en UsuarioController, o se crea una ruta explícita si se desea mantener esa entrada alternativa a “Novedades” (usuario.acciones.*).
   - `welcome.blade.php`: si la aplicación no usa una landing pública, puede quedar sin uso; en ese caso se puede eliminar o dejar para futuro.

4. **Siguiente paso sugerido:** Corregir en `MenuBuilder` las duplicidades de §3.1 y §3.2 (un ítem por destino en Operación y en el collapse Reportes y estadísticas) y, en una segunda pasada, revisar rutas y métodos de UsuarioController que devuelven `usuario.novedades.*` para decidir si se eliminan o se enlazan desde el menú.

---

## 6. Resumen de rutas clave (para cruce con route:list)

| routeName | URI | Action |
|-----------|-----|--------|
| usuario.index | usuario | UsuarioController@index |
| supervisor.index | supervisor | SupervisorController@index |
| administrador.index | administrador | AdministradorController@index |
| ingresos.index | ingresos | IngresosController@index |
| usuario.ronda.index | usuario/ronda | UsuarioRondaController@index |
| usuario.acciones.index | usuario/acciones | UsuarioAccionController@index |
| usuario.reportes.index | usuario/reportes | UsuarioReporteController@index |
| usuario.perfil.index | usuario/perfil | UsuarioPerfilController@index |
| usuario.documentos.index | usuario/documentos | UsuarioDocumentoController@index |
| admin.rondas.reporte | admin/rondas-reporte | Admin\RondaReporteController@index |
| admin.novedades.index | admin/novedades | Admin\NovedadController@index |
| admin.reportes-especiales.index | admin/reportes-especiales | Admin\ReporteEspecialController@index |
| admin.reporte-sucursal | admin/reporte-sucursal | ReporteSucursalController@index |
| admin.reportes-diarios | admin/reportes-diarios | Admin\ReporteDiarioController@index |
| admin.usuarios.index | admin/usuarios | Admin\UserController@index |
| supervisor.documentos.index | supervisor/documentos | Supervisor\DocumentoPersonalController@index |
| admin.documentos.index | admin/documentos | Admin\DocumentoPersonalController@index |
| admin.grupos-incidentes.index | admin/grupos-incidentes | Admin\GruposIncidentesController@index |
| reportes-especiales.index | reportes-especiales | ReporteEspecialController@index |
| admin.clientes.index | admin/clientes | Admin\ClienteController@index |
| admin.dispositivos.index | admin/dispositivos | Admin\DispositivoPermitidoController@index |
| admin.ubicaciones.index | admin/ubicaciones | Admin\UbicacionPermitidaController@index |
| admin.sectores.index | admin/sectores | Admin\SectorController@index |
| admin.rondas.index | admin/rondas | Admin\PuntoRondaController@index |
| admin.auditorias.index | admin/auditorias | Admin\AuditoriasController@index |

El archivo `docs/route-list.txt` generado con `php artisan route:list` contiene la lista completa de rutas para cruce con esta tabla.
