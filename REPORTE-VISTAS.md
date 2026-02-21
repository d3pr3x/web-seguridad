# Reporte de vistas del proyecto Web Seguridad

Este documento lista todas las vistas Blade del proyecto, quién tiene acceso a cada una, en qué consiste cada vista y **cómo está hecha la base de datos**.

---

## Base de datos

### Visión general

La base de datos está organizada en:

- **Jerarquía y clientes:** empresas (clientes), sucursales (instalaciones), modalidades de jerarquía y roles por modalidad.
- **Usuarios y permisos:** usuarios (PK `id_usuario`, identificador `run`), roles, permisos y tabla pivote rol–permiso.
- **Operación:** sectores por sucursal, acciones/novedades, reportes, reportes especiales, informes, tareas y detalles de tarea.
- **Control de acceso:** ingresos/salidas, blacklist, personas (base de visitantes), dispositivos y ubicaciones permitidas.
- **Rondas QR:** puntos de ronda por sucursal y escaneos.
- **Sueldos y días trabajados:** días trabajados por usuario, configuraciones de sueldo, feriados.
- **Auditoría:** tabla de auditorías (acciones sobre tablas, usuario, cambios).
- **Sistema:** sesiones, tokens de recuperación, cache, jobs.

Las tablas usan nombres en **español** (usuarios, sucursales, roles_usuario, permisos, etc.). Muchas tienen `creado_en` / `actualizado_en` en lugar de `created_at` / `updated_at`, y **soft deletes** donde aplica.

---

### Diagrama de relaciones (resumido)

```
jerarquias
    └── empresas (modalidad_id → modalidades_jerarquia)
            └── sucursales
                    ├── usuarios (rol_id → roles_usuario, sucursal_id)
                    ├── sectores
                    ├── acciones (id_usuario, sector_id)
                    ├── reportes_especiales (id_usuario, sector_id, tipo_incidente_id)
                    ├── puntos_ronda → escaneos_ronda (id_usuario)
                    └── ubicaciones_permitidas

roles_usuario ←→ permisos (rol_permiso)
modalidades_jerarquia ←→ roles_usuario (modalidad_roles, orden)

tareas → detalles_tarea
reportes (id_usuario, tarea_id, sector_id) → informes
usuarios → dias_trabajados, documentos, ingresos (id_guardia), blacklists (creado_por)
personas (sucursal_id opcional)
grupos_incidentes → tipos_incidente
auditorias (user_id, empresa_id, sucursal_id)
```

---

### Tablas y columnas principales

#### Jerarquía y catálogos de estructura

| Tabla | Descripción | Columnas principales |
|-------|-------------|----------------------|
| **jerarquias** | Catálogo de tipos de jerarquía | id, nombre, descripcion, timestamps |
| **modalidades_jerarquia** | Modalidad por empresa (directa, con_jefe_turno, etc.) | id, nombre, descripcion, activo, timestamps, soft deletes |
| **modalidad_roles** | Orden de roles por modalidad (menú/flujo) | id, modalidad_id, rol_id, orden, activo, unique(modalidad_id, rol_id), soft deletes |
| **empresas** | Clientes (nivel superior) | id, modalidad_id, nombre, codigo, razon_social, rut, direccion, comuna, ciudad, region, telefono, email, activa, **modulos_activos** (json/jsonb), timestamps, soft deletes |
| **sucursales** | Instalaciones de una empresa | id, empresa_id, nombre, empresa (texto), codigo (unique), direccion, comuna, ciudad, region, telefono, email, activa, creado_en, actualizado_en, soft deletes |

#### Usuarios y permisos

| Tabla | Descripción | Columnas principales |
|-------|-------------|----------------------|
| **roles_usuario** | Roles (ADMIN, SUPERVISOR, USUARIO, GUARDIA, etc.) | id, nombre, slug (unique), descripcion, creado_en, actualizado_en; luego activo |
| **permisos** | Permisos granulares (opcionales) | id, nombre, slug (unique), descripcion, timestamps; luego activo |
| **rol_permiso** | Pivot rol ↔ permiso | id, rol_id, permiso_id, creado_en, unique(rol_id, permiso_id) |
| **usuarios** | Usuarios del sistema (PK id_usuario) | id_usuario, run (unique), nombre_completo, rango, email (nullable), telefono, email_verificado_en, clave, fecha_nacimiento, domicilio, rol_id, sucursal_id, browser_fingerprint, dispositivo_verificado, remember_token, creado_en, actualizado_en, soft deletes |
| **sesiones** | Sesiones de Laravel | id (string PK), user_id (→ usuarios.id_usuario), ip_address, user_agent, payload, last_activity |
| **tokens_recuperacion** | Tokens para recuperar contraseña | email (PK), token, creado_en |

#### Sectores y operación (novedades, reportes, informes)

| Tabla | Descripción | Columnas principales |
|-------|-------------|----------------------|
| **sectores** | Sectores por sucursal | id, sucursal_id, empresa_id, nombre, descripcion, activo, timestamps, soft deletes |
| **acciones** | Novedades del servicio | id, id_usuario, sucursal_id, sector_id, tipo (enum: inicio_servicio, rondas, constancias, concurrencia_autoridades, concurrencia_servicios, entrega_servicio), tipo_hecho, importancia, dia, hora, novedad, accion, resultado, imagenes (json), latitud, longitud, precision, timestamps, soft deletes |
| **tareas** | Tipos de tarea/reporte (catálogo) | id, nombre, categoria, descripcion, icono, color, activa, luego activo, timestamps, soft deletes |
| **detalles_tarea** | Campos dinámicos por tarea | id, tarea_id, campo_nombre, tipo_campo, opciones, requerido, orden, timestamps |
| **reportes** | Reportes ligados a tarea | id, id_usuario, tarea_id, sector_id, datos (json), imagenes (json), latitud, longitud, precision, estado (pendiente, en_revision, completado, rechazado), comentarios_admin, leido_por_id, fecha_lectura, timestamps, soft deletes |
| **informes** | Informes generados desde reportes | id, reporte_id, numero_informe (unique), hora, descripcion, lesionados, acciones_inmediatas (json), conclusiones (json), fotografias (json), estado, fecha_aprobacion, aprobado_por, comentarios_aprobacion, timestamps, soft deletes |
| **reportes_especiales** | Reportes especiales (incidentes, denuncia, etc.) | id, id_usuario, accion_id, sucursal_id, sector_id, tipo_incidente_id, tipo (enum: incidentes, denuncia, detenido, accion_sospechosa), dia, hora, novedad, accion, resultado, imagenes, lat/long/precision, estado, comentarios_admin, leido_por_id, fecha_lectura, timestamps, soft deletes |

#### Incidentes (catálogos)

| Tabla | Descripción | Columnas principales |
|-------|-------------|----------------------|
| **grupos_incidentes** | Grupos de delitos/incidentes | id, nombre, slug (unique), descripcion, orden, activo, timestamps |
| **tipos_incidente** | Tipos por grupo | id, grupo_id, nombre, slug, orden, activo, unique(grupo_id, slug), timestamps |

#### Documentos personales

| Tabla | Descripción | Columnas principales |
|-------|-------------|----------------------|
| **documentos** | Documentos de guardias (cédula, licencia, etc.) | id, id_usuario, tipo_documento (enum: cedula_identidad, licencia_conductor, certificado_antecedentes, certificado_os10), imagen_frente, imagen_reverso, estado (pendiente, aprobado, rechazado), motivo_rechazo, aprobado_por, aprobado_en, es_cambio, documento_anterior_id, creado_en, actualizado_en, soft deletes |

#### Control de acceso

| Tabla | Descripción | Columnas principales |
|-------|-------------|----------------------|
| **personas** | Base de personas (RUT, nombre, etc.) | id, rut (unique), pasaporte, nombre, telefono, email, empresa, notas, sucursal_id, timestamps, soft deletes |
| **ingresos** | Registro de ingresos/salidas | id, tipo (peatonal|vehicular), rut, pasaporte, nombre, patente, id_guardia, fecha_ingreso, fecha_salida, estado, alerta_blacklist, ip_ingreso, user_agent, timestamps, soft deletes. Índices: fecha_ingreso, (estado, fecha_ingreso) |
| **blacklists** | Personas/patentes bloqueadas | id, rut, patente, motivo, fecha_inicio, fecha_fin, activo, creado_por, timestamps, soft deletes |
| **dispositivos_permitidos** | Dispositivos por fingerprint | id, browser_fingerprint (unique), descripcion, activo, requiere_ubicacion, timestamps; luego activo |
| **ubicaciones_permitidas** | Zonas permitidas (geo) | id, nombre, latitud, longitud, radio, activa, descripcion, sucursal_id, timestamps |

Si existe tabla **imei_permitidos** (modelo `ImeiPermitido`): imei, descripcion, activo. No aparece en las migraciones listadas; puede venir de una migración antigua o de otro paquete.

#### Rondas QR

| Tabla | Descripción | Columnas principales |
|-------|-------------|----------------------|
| **puntos_ronda** | Puntos de ronda por sucursal | id, sucursal_id, sector_id, nombre, codigo (unique), descripcion, orden, lat, lng, distancia_maxima_metros, activo, timestamps, soft deletes |
| **escaneos_ronda** | Cada escaneo de un punto | id, punto_ronda_id, id_usuario, escaneado_en, lat, lng, timestamps, soft deletes. Índices: (punto_ronda_id, escaneado_en), (id_usuario, escaneado_en) |

#### Días trabajados y sueldos

| Tabla | Descripción | Columnas principales |
|-------|-------------|----------------------|
| **dias_trabajados** | Días trabajados por usuario | id, id_usuario, fecha, ponderacion, observaciones, creado_en, actualizado_en, unique(id_usuario, fecha) |
| **configuraciones_sueldo** | Multiplicadores por tipo de día | id, tipo_dia, multiplicador, descripcion, activo, timestamps |
| **feriados** | Feriados | id, nombre, fecha (unique), irrenunciable, activo, timestamps |

#### Reuniones

| Tabla | Descripción | Columnas principales |
|-------|-------------|----------------------|
| **reuniones** | Reuniones (título, fecha, ubicación) | id, titulo, descripcion, fecha_reunion, ubicacion, id_usuario_creador, estado, creado_en, actualizado_en, soft deletes |

#### Auditoría

| Tabla | Descripción | Columnas principales |
|-------|-------------|----------------------|
| **auditorias** | Trazabilidad de acciones | id, user_id, empresa_id, sucursal_id, accion (create, update, delete, etc.), tabla, registro_id, route, ip, user_agent, cambios_antes (json), cambios_despues (json), ocurrido_en, metadata (json). Índices: (tabla, accion, ocurrido_en), user_id, empresa_id |

#### Sistema Laravel

| Tabla | Descripción |
|-------|-------------|
| **cache** | Cache (Laravel) |
| **cache_locks** | Lock del driver de cache |
| **jobs** | Cola de trabajos |
| **job_batches** | Lotes de jobs |
| **failed_jobs** | Jobs fallidos |

---

### Convenciones y notas

- **Claves primarias:** la mayoría usan `id`; la tabla **usuarios** usa `id_usuario`.
- **Foreign keys a usuarios:** se referencian como `id_usuario` o `user_id` según la migración (ej. `reportes.id_usuario`, `auditorias.user_id`).
- **Soft deletes:** empresas, sucursales, usuarios, sectores, acciones, reportes, reportes_especiales, informes, documentos, ingresos, blacklists, puntos_ronda, escaneos_ronda, personas, reuniones, modalidades_jerarquia, modalidad_roles.
- **Módulos por empresa:** `empresas.modulos_activos` (json/jsonb). Si es `null` o vacío, la empresa tiene todos los módulos globalmente habilitados; si es array, solo esas claves están habilitadas para esa empresa.
- **PostgreSQL:** algunas migraciones usan `jsonb` y triggers para auditoría inmutable; en MySQL se usan tipos equivalentes donde aplique.

---

## Resumen de roles y acceso

| Rol (slug) | Descripción | Portales / Áreas |
|------------|-------------|------------------|
| **ADMIN** | Administrador del sistema | `/administrador`, `/admin/*` (gestión completa) |
| **SUPERVISOR** / **SUPERVISOR_USUARIO** / **USUARIO_SUPERVISOR** | Jefe turno, 2do jefe, jefe contrato | `/supervisor`, supervisión, reportes por sucursal, aprobar documentos |
| **USUARIO** / **GUARDIA** | Guardia / usuario operativo | `/usuario`, mis reportes, acciones, control de acceso, rondas QR |

- Todas las rutas (salvo login y welcome) requieren **autenticación** (`auth`).
- Las rutas bajo `verificar.sucursal` exigen que el usuario tenga **sucursal asignada** (salvo ADMIN).
- Algunas vistas dependen de **módulos** activos en `config/modules.php`: `control_acceso`, `documentos_guardias`, `rondas_qr`, `reportes_diarios`, `calculo_sueldos`.

---

## Capturas de pantalla

Para obtener capturas de cada vista:

1. Ejecutar el servidor: `php artisan serve`
2. Iniciar sesión con un usuario del rol deseado (admin, supervisor o usuario/guardia).
3. Navegar a cada ruta y capturar la pantalla. Las rutas se indican en cada sección.

En este reporte no se incluyen imágenes porque requieren sesión activa y contexto de datos.

---

## 1. Autenticación y bienvenida

### 1.1 `auth/login.blade.php`
- **Ruta:** `GET /login` — `login`
- **Acceso:** Público (no autenticado).
- **Consiste en:** Formulario de inicio de sesión con RUT y contraseña. Muestra errores de credenciales, dispositivo no permitido o ubicación no permitida. Incluye enlace a recuperación de contraseña si aplica.

### 1.2 `welcome.blade.php`
- **Ruta:** No asignada directamente; la raíz `GET /` redirige a login o al portal según rol.
- **Acceso:** Puede usarse como landing pública si se define una ruta.
- **Consiste en:** Página de bienvenida genérica de Laravel (enlace a login, etc.).

---

## 2. Layouts (plantillas base)

No son “pantallas” finales; son las bases que extienden el resto de vistas.

| Vista | Uso |
|------|-----|
| `layouts/app.blade.php` | Layout principal con sidebar/header para áreas autenticadas. |
| `layouts/guest.blade.php` | Layout para login y páginas de invitado (sin menú de usuario). |
| `layouts/usuario.blade.php` | Layout del portal usuario/supervisor/administrador (sidebar + header). |
| `layouts/ronda-escaner.blade.php` | Layout específico para pantalla de escaneo de rondas QR. |
| `layouts/qr-automatico.blade.php` | Layout para modo QR automático (control de acceso). |

---

## 3. Componentes reutilizables

| Vista | Uso |
|------|-----|
| `components/usuario/sidebar.blade.php` | Menú lateral (construido por `MenuBuilder` según rol y módulos). |
| `components/usuario/header.blade.php` | Cabecera con usuario y menú móvil. |
| `components/usuario/mobile-menu.blade.php` | Menú colapsable para móvil. |

---

## 4. Perfil (común a todos los autenticados)

### 4.1 `profile/index.blade.php`
- **Ruta:** `GET /profile` — `profile.index`
- **Acceso:** Cualquier usuario autenticado.
- **Consiste en:** Edición del perfil (nombre, email, teléfono, etc.). No depende de sucursal.

### 4.2 `profile/password.blade.php`
- **Ruta:** `GET /profile/password` — `profile.password`
- **Acceso:** Cualquier usuario autenticado.
- **Consiste en:** Formulario para cambiar contraseña.

---

## 5. Portales de inicio (por rol)

### 5.1 `administrador/index.blade.php`
- **Ruta:** `GET /administrador` — `administrador.index`
- **Acceso:** Solo **ADMIN** (el controlador comprueba `esAdministrador()`).
- **Consiste en:** Panel resumen del administrador con accesos rápidos a Supervisión (documentos, novedades, usuarios), Reportes y estadísticas, y Gestión (clientes, dispositivos, ubicaciones, sectores, rondas, auditorías).

### 5.2 `supervisor/index.blade.php`
- **Ruta:** `GET /supervisor` — `supervisor.index`
- **Acceso:** **SUPERVISOR**, **SUPERVISOR_USUARIO**, **USUARIO_SUPERVISOR** (el controlador comprueba `esSupervisor()`).
- **Consiste en:** Panel resumen del supervisor con secciones según rol: Reportes, Supervisión (aprobar documentos, novedades), Reportes y estadísticas. Si es Usuario-Supervisor o Supervisor-Usuario, muestra también bloque de usuario (mis reportes, control de acceso, etc.).

### 5.3 `usuario/index.blade.php`
- **Ruta:** `GET /usuario` — `usuario.index`
- **Acceso:** Usuarios con perfil **USUARIO** o **GUARDIA** (y sucursal asignada).
- **Consiste en:** Panel resumen del usuario: Reportes, Acciones/Novedades, enlaces a Mis reportes, Control de acceso, Mi perfil, Rondas QR, etc.

### 5.4 `inicio-unificado/index.blade.php`
- **Ruta:** `GET /inicio-unificado` — `inicio-unificado.index`
- **Acceso:** Cualquier autenticado con sucursal (pruebas).
- **Consiste en:** Una sola vista que discrimina contenido por perfil (admin/supervisor/usuario).

---

## 6. Control de acceso (módulo `control_acceso`)

Solo visible/activo si el módulo está habilitado. Según el modelo User, quienes pueden ver control de acceso son: USUARIO, USUARIO_SUPERVISOR, SUPERVISOR_USUARIO (guardias y perfiles que operan en terreno).

### 6.1 `ingresos/listado.blade.php`
- **Ruta:** `GET /ingresos` — `ingresos.index`
- **Acceso:** Usuarios con `puedeVerControlAcceso()` (y módulo activo).
- **Consiste en:** Listado paginado de ingresos con filtros (fechas, guardia, tipo, estado, RUT). Enlaces a ver detalle y exportar CSV.

### 6.2 `ingresos/escaner.blade.php`
- **Ruta:** `GET /ingresos/escaner`, `GET /qr-automatico` (con `modo_qr_automatico`)
- **Acceso:** Mismo que listado.
- **Consiste en:** Pantalla de escaneo QR para registro de entrada (cédula/patente). Modo automático para lectura de carnet.

### 6.3 `ingresos/escaner-nuevo.blade.php`
- **Ruta:** `GET /ingresos/escaner-nuevo` — `ingresos.escaner-nuevo`
- **Acceso:** Mismo que listado.
- **Consiste en:** Escáner para cédula de nuevo formato (QR centrado, MRZ, nombre en QR).

### 6.4 `ingresos/entrada-manual.blade.php`
- **Ruta:** `GET /ingresos/entrada-manual` — `ingresos.entrada-manual`
- **Acceso:** Mismo que listado.
- **Consiste en:** Entrada manual sin QR: registrar ingreso con RUT/nombre o patente.

### 6.5 `ingresos/show.blade.php`
- **Ruta:** `GET /ingresos/{id}` — `ingresos.show`
- **Acceso:** Mismo que listado.
- **Consiste en:** Detalle de un ingreso (fecha, persona, guardia, estado) y opción de registrar salida (QR salida).

### 6.6 `ingresos/blacklist.blade.php`
- **Ruta:** `GET /blacklist` — `blacklist.index`
- **Acceso:** Mismo que listado.
- **Consiste en:** Lista de personas/patentes en blacklist; alta/baja y activar/desactivar.

### 6.7 Personas (base para control de acceso)

#### `ingresos/personas/index.blade.php`
- **Ruta:** `GET /personas` — `personas.index`
- **Acceso:** Autenticado (dentro de `auth`; no está dentro del grupo `module:control_acceso` en rutas, pero suele usarse en ese contexto).
- **Consiste en:** Listado de personas (RUT, nombre, etc.) para gestión de base de visitantes.

#### `ingresos/personas/create.blade.php`
- **Ruta:** `GET /personas/crear` — `personas.create`
- **Consiste en:** Alta de nueva persona.

#### `ingresos/personas/edit.blade.php`
- **Ruta:** `GET /personas/{id}/editar` — `personas.edit`
- **Consiste en:** Edición de persona.

---

## 7. Portal Usuario (`/usuario/*`)

Todas bajo `verificar.sucursal`; el usuario debe tener sucursal (salvo que sea ADMIN, que no usa este portal como “usuario”).

### 7.1 Perfil usuario
- **`usuario/perfil/index.blade.php`** — `GET /usuario/perfil` — `usuario.perfil.index`  
  Mi perfil (solo lectura excepto cambio de contraseña). Acceso: cualquier usuario que llegue al portal usuario.

### 7.2 Acciones (novedades) del usuario
- **`usuario/acciones/index.blade.php`** — `GET /usuario/acciones` — Lista “Historial de Novedades” del usuario. Acceso: usuario/supervisor-usuario/usuario-supervisor según menú.
- **`usuario/acciones/create.blade.php`** — `GET /usuario/acciones/crear` — Formulario para registrar una acción/novedad (tipo, sector, descripción, imágenes).
- **`usuario/acciones/show.blade.php`** — `GET /usuario/acciones/{accion}` — Detalle de una acción/novedad del usuario.

### 7.3 Reportes especiales del usuario
- **`usuario/reportes/index.blade.php`** — `GET /usuario/reportes` — Listado de “Mis reportes” (reportes especiales del usuario).
- **`usuario/reportes/create.blade.php`** — `GET /usuario/reportes/crear` — Crear reporte especial (tipo, sector, descripción, etc.).
- **`usuario/reportes/show.blade.php`** — `GET /usuario/reportes/{reporteEspecial}` — Detalle de un reporte especial propio.

### 7.4 Historial
- **`usuario/historial/index.blade.php`** — `GET /usuario/historial` — “Historial Completo” del usuario (acciones, reportes, etc. en una línea de tiempo). Acceso: quien tenga menú usuario.

### 7.5 Documentos personales (módulo `documentos_guardias`)
- **`usuario/documentos/index.blade.php`** — `GET /usuario/documentos` — Lista de documentos personales (tipos, aprobados/pendientes).
- **`usuario/documentos/create.blade.php`** — `GET /usuario/documentos/crear` — Subir nuevo documento (frente/reverso por tipo).
- **`usuario/documentos/show.blade.php`** — `GET /usuario/documentos/{documento}` — Detalle de un documento personal.

### 7.6 Rondas QR (módulo `rondas_qr`)
- **`usuario/ronda/index.blade.php`** — `GET /usuario/ronda` — Pantalla de rondas: listado de puntos o instrucciones para escanear.
- **`usuario/ronda/escaner.blade.php`** — `GET /usuario/ronda/escaner` — Escáner para leer códigos QR de puntos de ronda.

### 7.7 Novedades (vistas referenciadas desde UsuarioController; rutas pueden no estar en web.php actual)
- **`usuario/novedades/create.blade.php`** — Crear novedad por tipo. Acceso: usuario (si la ruta está definida).

---

## 8. Supervisor – Documentos (módulo `documentos_guardias`)

Rutas bajo `supervisor.*` y middleware `module:documentos_guardias`. Acceso: quien tenga `puedeVerSupervision()` (supervisor, supervisor-usuario, usuario-supervisor, admin).

- **`supervisor/documentos/index.blade.php`** — `GET /supervisor/documentos` — Listado de documentos a supervisar (estadísticas, usuarios).
- **`supervisor/documentos/usuarios.blade.php`** — `GET /supervisor/documentos/usuarios` — Lista de usuarios con documentos.
- **`supervisor/documentos/usuario.blade.php`** — `GET /supervisor/documentos/usuario/{user}` — Documentos de un usuario concreto; aprobar/rechazar.
- **`supervisor/documentos/show.blade.php`** — `GET /supervisor/documentos/{documento}` — Detalle de un documento para aprobar/rechazar.

---

## 9. Reportes e informes (compartidos)

Vistas usadas por varios roles; el controlador filtra por sucursal para supervisores.

### 9.1 Reportes (reportes de servicio)
- **`reportes/index.blade.php`** — `GET /reportes` — Listado de reportes (según permiso/sucursal).
- **`reportes/show.blade.php`** — `GET /reportes/{id}` — Detalle de un reporte (lectura, imágenes, etc.).

### 9.2 Reportes especiales (fuera de /usuario y /admin)
- **`reportes-especiales/index.blade.php`** — `GET /reportes-especiales` — Listado de reportes especiales (supervisor ve los de su sucursal).
- **`reportes-especiales/create.blade.php`** — `GET /reportes-especiales/crear` — Crear reporte especial (supervisor/usuario con permiso).
- **`reportes-especiales/show.blade.php`** — `GET /reportes-especiales/{reporteEspecial}` — Detalle y cambio de estado.

### 9.3 Acciones (novedades del servicio)
- **`acciones/index.blade.php`** — `GET /acciones` — Listado de acciones/novedades (admin todas; supervisor por sucursal).
- **`acciones/create.blade.php`** — `GET /acciones/crear` — Crear acción (tipo, sector, descripción).
- **`acciones/show.blade.php`** — `GET /acciones/{accion}` — Detalle de una acción.

### 9.4 Informes
- **`informes/index.blade.php`** — `GET /informes` — Listado de informes (generados desde reportes).
- **`informes/create.blade.php`** — `GET /informes/create/{reporteId}` — Crear informe a partir de un reporte.
- **`informes/show.blade.php`** — `GET /informes/{id}` — Detalle de informe (estado, aprobar/rechazar/reenviar).
- **`informes/pdf.blade.php`** — `GET /informes/{id}/pdf` o `ver-pdf` — PDF del informe (I. Antecedentes, II. Curso de acción, III. Conclusiones).

### 9.5 Tareas
- **`tareas/formulario.blade.php`** — `GET /tareas/{id}` — Formulario/consulta de una tarea asignada.

### 9.6 Días trabajados (módulo `calculo_sueldos`)
- **`dias-trabajados/index.blade.php`** — `GET /dias-trabajados` — Listado de días trabajados del usuario.
- **`dias-trabajados/create.blade.php`** — `GET /dias-trabajados/create` — Alta de día trabajado.
- **`dias-trabajados/edit.blade.php`** — `GET /dias-trabajados/{id}/edit` — Editar día trabajado.

### 9.7 Sectores (vista no admin)
- **`sectores/index.blade.php`** — Listado de sectores (controlador exige admin en las acciones; si hay ruta pública sería solo lectura).
- **`sectores/create.blade.php`** — Crear sector (solo admin en controlador).
- **`sectores/edit.blade.php`** — Editar sector (solo admin).

---

## 10. Administración (`/admin/*`)

Todas las rutas bajo `admin.*` están pensadas para **ADMIN**; varios controladores hacen `abort(403)` si el usuario no es administrador.

### 10.1 Clientes (empresas e instalaciones)
- **`admin/clientes/index.blade.php`** — `GET /admin/clientes` — Listado de empresas (clientes).
- **`admin/clientes/empresas/create.blade.php`** — Crear empresa.
- **`admin/clientes/empresas/edit.blade.php`** — Editar empresa.
- **`admin/clientes/instalaciones/index.blade.php`** — Instalaciones (sucursales) de una empresa.
- **`admin/clientes/instalaciones/create.blade.php`** — Crear instalación.
- **`admin/clientes/instalaciones/edit.blade.php`** — Editar instalación.

### 10.2 Usuarios
- **`admin/usuarios/index.blade.php`** — `GET /admin/usuarios` — Gestión de usuarios (listado, filtros, roles).
- **`admin/usuarios/create.blade.php`** — Crear usuario.
- **`admin/usuarios/edit.blade.php`** — Editar usuario.

### 10.3 Documentos personales (módulo `documentos_guardias`)
- **`admin/documentos/index.blade.php`** — Listado de documentos a revisar (admin).
- **`admin/documentos/usuarios.blade.php`** — Usuarios con documentos.
- **`admin/documentos/usuario.blade.php`** — Documentos de un usuario; aprobar/rechazar.
- **`admin/documentos/show.blade.php`** — Detalle de documento.

### 10.4 Reportes y estadísticas
- **`admin/reportes-diarios.blade.php`** — `GET /admin/reportes-diarios` — Reportes diarios (módulo `reportes_diarios`). Solo admin.
- **`admin/calculo-sueldos.blade.php`** — `GET /admin/calculo-sueldos` — Cálculo de sueldos (módulo `calculo_sueldos`). Solo admin.
- **`admin/reporte-sucursal.blade.php`** — `GET /admin/reporte-sucursal` — Reporte por sucursal (filtros, exportar). Quien tenga `puedeVerReporteSucursal()` (admin y supervisores).
- **`admin/reporte-sucursal-pdf.blade.php`** — Vista/PDF del reporte por sucursal (exportar).
- **`admin/reportes-especiales/index.blade.php`** — Listado de todos los reportes especiales (admin/supervisor).
- **`admin/reportes-especiales/show.blade.php`** — Detalle de reporte especial; marcar leído, cambiar estado.

### 10.5 Dispositivos y ubicaciones
- **`admin/dispositivos/index.blade.php`** — Listado de dispositivos permitidos (fingerprint).
- **`admin/dispositivos/create.blade.php`** — Crear dispositivo permitido.
- **`admin/dispositivos/show.blade.php`** — Detalle de dispositivo (referenciado en controlador; **el archivo no existe** en el proyecto; habría que crearlo o el recurso redirigir a index).
- **`admin/dispositivos/edit.blade.php`** — Editar dispositivo (**archivo no encontrado** en el proyecto).
- **`admin/ubicaciones/index.blade.php`** — Listado de ubicaciones permitidas.
- **`admin/ubicaciones/create.blade.php`** — Crear ubicación.
- **`admin/ubicaciones/show.blade.php`** — Detalle de ubicación (referenciado en controlador; **el archivo no existe**).
- **`admin/ubicaciones/edit.blade.php`** — Editar ubicación (**archivo no encontrado**).

### 10.6 IMEIs
- **`admin/imeis/index.blade.php`** — Listado de IMEIs permitidos.
- **`admin/imeis/create.blade.php`** — Alta de IMEI.
- **`admin/imeis/edit.blade.php`** — Editar IMEI.
- **`admin/imeis/show.blade.php`** — Detalle de IMEI (referenciado en controlador; **archivo no encontrado** en el proyecto).

### 10.7 Sectores (admin)
- **`admin/sectores/index.blade.php`** — Listado por empresas (jerarquía empresa → instalaciones → sectores).
- **`admin/sectores/por-empresa.blade.php`** — Sectores por empresa (instalaciones).
- **`admin/sectores/show.blade.php`** — Sectores de una sucursal; crear/editar/eliminar sectores.
- **`admin/sectores/create.blade.php`** — Crear sector en una sucursal.
- **`admin/sectores/edit.blade.php`** — Editar sector.

### 10.8 Novedades (admin)
- **`admin/novedades/index.blade.php`** — Listado de novedades/acciones (filtros por sucursal, tipo, etc.).
- **`admin/novedades/create.blade.php`** — Crear novedad (admin).
- **`admin/novedades/show.blade.php`** — Detalle de novedad; opción “elevar a reporte”.

### 10.9 Grupos de incidentes
- **`admin/grupos-incidentes/index.blade.php`** — `GET /admin/grupos-incidentes` — CRUD de grupos y tipos de incidentes (delitos/incidentes). Solo admin.

### 10.10 Rondas QR (módulo `rondas_qr`)
- **`admin/rondas/index.blade.php`** — Listado de sucursales con puntos de ronda.
- **`admin/rondas/show.blade.php`** — Puntos de ronda de una sucursal.
- **`admin/rondas/create.blade.php`** — Crear punto de ronda.
- **`admin/rondas/edit.blade.php`** — Editar punto de ronda.
- **`admin/rondas/reporte.blade.php`** — Reporte de escaneos QR (por sucursal, usuario, fechas). Quien tenga `puedeVerReportesEstadisticasCompletos()`.

### 10.11 Auditorías
- **`admin/auditorias/index.blade.php`** — `GET /admin/auditorias` — Listado de auditorías (solo lectura). Solo admin.
- **`admin/auditorias/show.blade.php`** — Detalle de auditoría (tabla, registro, cambios).

---

## 11. Descargas de archivos privados

No son vistas HTML; son rutas que devuelven archivos (documentos, imágenes de acciones/reportes/informes). Acceso: autenticado + autorización (dueño del recurso, o supervisor de la sucursal, o admin). Rutas: `archivos-privados/documentos/...`, `archivos-privados/acciones/...`, `archivos-privados/reportes/...`, `archivos-privados/reportes-especiales/...`, `archivos-privados/informes/...`.

---

## 12. Vista previa PDF de informe

- **Ruta:** `GET /informes-preview-pdf` — `informes.preview-pdf`  
- **Acceso:** Sin autenticación (vista previa; usa el primer informe de la BD). Solo para pruebas/demo.

---

## Resumen por rol de acceso a vistas

| Área | ADMIN | SUPERVISOR / SUP_USU / USU_SUP | USUARIO / GUARDIA |
|------|------|--------------------------------|--------------------|
| Login, perfil (profile) | ✓ | ✓ | ✓ |
| Portal inicio (admin/supervisor/usuario) | Admin | Supervisor | Usuario |
| Control de acceso (ingresos, blacklist, personas) | No (según menú) | Sí si tiene permiso | Sí |
| Mis reportes / Mis acciones / Historial | No | Sí (acordeón usuario) | Sí |
| Mis documentos | No (módulo) | Sí (módulo) | Sí (módulo) |
| Rondas QR (usuario) | No | Sí (módulo) | Sí (módulo) |
| Supervisor documentos | Sí (admin.documentos) | Sí (supervisor.documentos) | No |
| Reportes / Acciones / Informes (listados compartidos) | Sí (todos) | Sí (sucursal) | Limitado (propios) |
| Reporte por sucursal, reportes diarios, cálculo sueldos | Sí | Reporte sucursal sí; diarios/sueldos no | No |
| Gestión (clientes, usuarios, dispositivos, ubicaciones, sectores, rondas, auditorías) | Sí | No | No |
| Novedades (admin) | Sí | Sí (sucursal) | No |
| Grupos incidentes | Sí | No | No |

---

*Documento generado a partir de `routes/web.php`, controladores y vistas del proyecto. Módulos y permisos según `config/modules.php` y `App\Models\User`.*
