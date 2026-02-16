# Implementación de los 18 Requerimientos

Resumen de lo implementado en el sistema web-seguridad según el documento de requerimientos.

---

## ✅ Punto 1: Filtrar novedades por hecho

- **Modelo:** En `acciones` se añadieron los campos `tipo_hecho` (nullable) e `importancia` (nullable).
- **Valores de tipo_hecho:** incidente, observacion, informacion, delito, accidente (método `Accion::hechos()`).
- **Vista:** En el listado de novedades (admin) hay filtros por **Tipo de hecho** e **Importancia**.
- **Controlador:** `Admin\NovedadController@index` aplica `porTipoHecho()` y `porImportancia()`.

**Pendiente:** En el formulario de creación de novedad/acción (usuario/supervisor) incluir los campos `tipo_hecho` e `importancia` para que se guarden al registrar.

---

## ✅ Punto 2: Vista de grupos y delitos asociados

- **Tablas:** `grupos_incidentes` (nombre, slug, orden, activo) y `tipos_incidente` (grupo_id, nombre, slug, orden).
- **Modelos:** `GrupoIncidente`, `TipoIncidente`. En `ReporteEspecial` se añadió `tipo_incidente_id` (FK opcional) y relación `tipoIncidente()`.
- **Vista:** Ruta `/administrador/grupos-incidentes` lista grupos y, dentro de cada uno, los tipos de incidente. Enlace en el menú lateral (Supervisión) para Admin.
- **Seeder:** `GruposIncidentesSeeder` con datos iniciales (delitos contra la propiedad/personas, desórdenes). Ejecutar: `php artisan db:seed --class=GruposIncidentesSeeder`.

**Pendiente:** En el formulario de alta de reporte especial permitir elegir `tipo_incidente_id` (opcional) según el grupo.

---

## ✅ Punto 3: Admin también puede modificar reportes

- **Lógica:** En `ReporteEspecialController@updateEstado` ya podían supervisores y admin. Se dejó documentado que ADMIN siempre puede modificar.
- **Usuarios:** Solo **ADMIN** puede editar usuarios (crear sí puede el supervisor). En `Admin\UserController@edit` y `@update` se comprueba `esAdministrador()`; si no, 403.

---

## ✅ Punto 4: Comentario interno en reportes

- **Estado:** Los reportes y reportes especiales ya tenían `comentarios_admin`. Se usa como comentario interno.
- **PDF:** El reporte por sucursal (`reporte-sucursal-pdf.blade.php`) **no** incluye `comentarios_admin`; solo datos públicos. En la vista admin del reporte especial se indica que el comentario interno no se incluye en PDF.

---

## ✅ Punto 5: Check de lectura por parte del jefe

- **BD:** En `reportes` y `reportes_especiales` se añadieron `leido_por_id` (FK a usuarios) y `fecha_lectura` (timestamp).
- **Modelos:** Relación `leidoPor()` y método `fueLeido()` en `Reporte` y `ReporteEspecial`.
- **Vista admin reporte especial:** Botón “Marcar como leído” (solo si no estaba leído) y texto “Leído por [nombre] el [fecha]”.
- **Ruta:** `POST admin/reportes-especiales/{reporteEspecial}/marcar-leido` → `Admin\ReporteEspecialController@marcarLeido`.

---

## ✅ Punto 6: Novedad elevable a reporte por jefe de turno

- **BD:** En `reportes_especiales` se añadió `accion_id` (FK nullable a `acciones`).
- **Modelos:** `Accion` tiene `reporteEspecial()`; `ReporteEspecial` tiene `accionOrigen()`.
- **Vista:** En el detalle de novedad (admin) hay botón “Elevar a reporte” para jefe de turno / supervisor / admin. Si ya fue elevada, enlace “Ver reporte generado”.
- **Controlador:** `Admin\NovedadController@elevarAReporte` crea un `ReporteEspecial` con datos de la acción y asigna `accion_id`. Ruta: `POST admin/novedades/{accion}/elevar-reporte`.

---

## ✅ Punto 7: Módulo de novedades formal

- **Campos en `acciones`:** tipo (acción), tipo_hecho, importancia, sucursal_id, sector_id, id_usuario, dia, hora, novedad, accion, resultado, imagenes, lat/long.
- **Filtros:** Por tipo de acción, tipo de hecho, importancia, instalación, usuario, fechas (ya en listado admin).
- **Vista detalle:** `admin.novedades.show` con todos los datos y opción de elevar a reporte.

**Pendiente:** En el formulario de creación de novedad (usuario) añadir `tipo_hecho` e `importancia` para completar el módulo.

---

## ✅ Punto 8: Sucursal = Instalación en interfaz

- **Cambios:** En listado y detalle de novedades (admin) las etiquetas pasan de “Sucursal” a “Instalación”. En el PDF del reporte por sucursal, la etiqueta de estadística es “Instalaciones”. En vista admin de reporte especial se usa “Instalación”.

**Pendiente:** Revisar resto de vistas y reportes para reemplazar “Sucursal” por “Instalación” donde vaya dirigido al usuario final (EFE u otros).

---

## ✅ Punto 9: Jefe de turno solo ve su instalación

- **Novedades:** En `Admin\NovedadController@index`, si el usuario es `esSupervisorUsuario()` y tiene `sucursal_id`, se filtra `where('sucursal_id', $user->sucursal_id)`.
- **Reportes especiales:** En `Admin\ReporteEspecialController@index` se aplica el mismo criterio para jefe de turno.

**Pendiente:** Aplicar el mismo filtro por sucursal en reporte por sucursal, rondas QR, ingresos, etc., cuando el usuario sea jefe de turno.

---

## ✅ Punto 10: Gestión de usuarios por nombre y perfil

- **Estado:** El listado de usuarios ya ordena por `nombre_completo` y permite buscar por nombre, email y RUN. El rol se asigna en crear/editar. No se cambió la lógica; se mantiene id_usuario como PK y nombre/RUT como referencia operativa.

---

## ✅ Punto 11: Ingreso por RUT asociado a cuenta

- **Estado:** El login ya usa RUT (`run`) y contraseña (`LoginController`). No se requirieron cambios.

---

## ✅ Punto 12: Filtro de ingreso por RUT en control de acceso

- **Controlador:** En `IngresosController@index` se añadió filtro por `rut` (y, si existe columna `pasaporte`, también por pasaporte).
- **Vista:** En el listado de ingresos se añadió el campo de filtro “RUT / Pasaporte”.

---

## ✅ Punto 13: Supervisor crea usuarios pero no edita

- **Controlador:** `Admin\UserController@edit` y `@update` exigen `auth()->user()->esAdministrador()`; si no, devuelven 403.
- **Vista:** En el listado de usuarios, el enlace “Editar” solo se muestra si `auth()->user()->esAdministrador()`. Para supervisores se muestra “—”.

---

## ✅ Punto 14: Teléfono como dato principal frente a email

- **BD:** Columna `telefono` (nullable) en `usuarios`. Migración opcional para hacer `email` nullable: `2026_02_16_100005_make_usuarios_email_nullable.php`.
- **Modelo User:** `telefono` en `$fillable`.
- **Formularios:** En crear y editar usuario se añadió campo “Teléfono” y “Email (opcional)”. En store/update se normaliza email vacío a null.
- **Listado:** Se muestra RUT, teléfono y email (cuando existan).

---

## ✅ Punto 15: Filtro de novedades por importancia

- **Modelo:** `Accion::nivelesImportancia()` (cotidiana, importante, critica). Scope `porImportancia()`.
- **Vista y controlador:** Mismo listado de novedades (admin) con filtro “Importancia” (Puntos 1 y 15 unificados).

---

## Punto 16: Uso de reportes como herramienta estadística

- **Estado:** La estructura actual de reportes y reportes especiales (sucursal, usuario, tipo, fecha) permite agregaciones. Los módulos de “Reportes y estadísticas” y “Reporte por sucursal” ya explotan estos datos. No se añadió lógica nueva; el diseño es compatible.

---

## Punto 17: Reporte por sucursal = todo lo ocurrido en la instalación

- **Estado:** El reporte por sucursal actual agrupa reportes (tareas) por sucursal y fecha. Para que sea un consolidado completo (novedades + reportes formales + rondas, etc.) habría que ampliar la consulta en `ReporteSucursalController` para incluir `Accion` (novedades) y otros módulos por sucursal y fecha. Pendiente de definir alcance exacto.

---

## ✅ Punto 18: Personas identificadas por RUT o pasaporte

- **BD:** En `personas` e `ingresos` se añadió columna `pasaporte` (nullable). En `Persona` y `Ingreso` está en `$fillable`.
- **Pendiente:** En formularios de personas e ingresos permitir identificar por RUT **o** pasaporte (validación: al menos uno obligatorio; regla ChileRut para RUT). Ajustar `Persona::registrarOActualizar` y búsquedas para aceptar pasaporte.

---

## Migraciones ejecutadas

- `2026_02_16_100000_add_tipo_hecho_e_importancia_to_acciones.php`
- `2026_02_16_100001_add_lectura_jefe_to_reportes.php`
- `2026_02_16_100002_add_accion_id_to_reportes_especiales.php`
- `2026_02_16_100003_create_grupos_incidentes_y_tipos.php`
- `2026_02_16_100004_add_telefono_to_usuarios_pasaporte_to_personas.php`
- Opcional: `2026_02_16_100005_make_usuarios_email_nullable.php` (requiere `doctrine/dbal` para `->change()` si se usa)

## Seeders

- `php artisan db:seed --class=GruposIncidentesSeeder` — Carga grupos y tipos de incidente iniciales.

---

## Resumen de archivos tocados

| Archivo | Cambios |
|---------|--------|
| `app/Models/Accion.php` | tipo_hecho, importancia, hechos(), nivelesImportancia(), scopes, reporteEspecial() |
| `app/Models/Reporte.php` | leido_por_id, fecha_lectura, leidoPor(), fueLeido() |
| `app/Models/ReporteEspecial.php` | accion_id, tipo_incidente_id, leido_por_id, fecha_lectura, accionOrigen(), tipoIncidente(), leidoPor(), fueLeido() |
| `app/Models/User.php` | telefono en fillable |
| `app/Models/Persona.php` | pasaporte en fillable |
| `app/Models/Ingreso.php` | pasaporte en fillable |
| `app/Models/GrupoIncidente.php` | Nuevo |
| `app/Models/TipoIncidente.php` | Nuevo |
| `app/Http/Controllers/Admin/NovedadController.php` | Filtros tipo_hecho/importancia, filtro sucursal jefe, elevarAReporte() |
| `app/Http/Controllers/Admin/ReporteEspecialController.php` | Filtro sucursal jefe, marcarLeido(), show con leidoPor/accionOrigen |
| `app/Http/Controllers/Admin/UserController.php` | Solo admin puede edit/update, telefono, email opcional |
| `app/Http/Controllers/Admin/GruposIncidentesController.php` | Nuevo (índice grupos) |
| `app/Http/Controllers/IngresosController.php` | Filtro por RUT/pasaporte |
| `resources/views/admin/novedades/index.blade.php` | Filtros tipo hecho e importancia, “Instalación” |
| `resources/views/admin/novedades/show.blade.php` | Nuevo; botón “Elevar a reporte” |
| `resources/views/admin/reportes-especiales/show.blade.php` | Nuevo; “Marcar como leído”, comentario interno, Instalación |
| `resources/views/admin/usuarios/index.blade.php` | Editar solo para admin, columna teléfono |
| `resources/views/admin/usuarios/create.blade.php` | Campo teléfono, email opcional |
| `resources/views/admin/usuarios/edit.blade.php` | Campo teléfono, email opcional |
| `resources/views/ingresos/listado.blade.php` | Filtro RUT/pasaporte |
| `resources/views/admin/reporte-sucursal-pdf.blade.php` | Etiqueta “Instalaciones” |
| `resources/views/components/usuario/sidebar.blade.php` | Enlace “Grupos de incidentes” |
| `routes/web.php` | Rutas grupos-incidentes, elevar-reporte, marcar-leido |
