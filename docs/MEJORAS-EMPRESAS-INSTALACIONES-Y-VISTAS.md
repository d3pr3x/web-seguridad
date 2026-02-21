# Estructura final tras mejoras: Empresas, instalaciones y vistas

Después de aplicar las mejoras (tabla empresas, jerarquía empresa → instalaciones, vista Clientes y vista Sectores), esta es la estructura de la base de datos y el funcionamiento de las vistas, paso a paso.

---

## 1. Estructura final de la base de datos (relevante a la jerarquía)

### Nueva tabla: `empresas`

Define el nivel superior (clientes). Cada **instalación** (sucursal) pertenece a una empresa.

| Columna       | Tipo           | Descripción                          |
|---------------|----------------|--------------------------------------|
| id            | bigint PK      |                                      |
| nombre        | string         | Nombre de la empresa/cliente         |
| codigo        | string unique  | Código opcional                      |
| razon_social  | string nullable|                                      |
| rut           | string nullable|                                      |
| direccion     | text nullable  |                                      |
| comuna        | string nullable|                                      |
| ciudad        | string nullable|                                      |
| region        | string nullable|                                      |
| telefono      | string nullable|                                      |
| email         | string nullable|                                      |
| activa        | boolean        | Default true                         |
| created_at    | timestamp      |                                      |
| updated_at    | timestamp      |                                      |

### Cambio en tabla: `sucursales`

Se agrega la relación con la empresa. En la aplicación, cada fila de `sucursales` se considera una **instalación** de una empresa.

| Columna (nueva) | Tipo              | Descripción                    |
|-----------------|-------------------|--------------------------------|
| empresa_id      | bigint FK nullable| Referencia a `empresas.id` (nullOnDelete) |

El resto de columnas de `sucursales` se mantiene (nombre, codigo, direccion, comuna, ciudad, region, telefono, email, activa, creado_en, actualizado_en). La columna legacy `empresa` (string) se mantiene por compatibilidad; la jerarquía real es `empresa_id` → `empresas`.

### Jerarquía resumida

```
Empresa (cliente)
  └── Instalaciones (sucursales con empresa_id = esta empresa)
        └── Sectores (por instalación)
        └── Puntos de ronda (por instalación)
        └── Usuarios asignados (sucursal_id)
        └── Acciones / Reportes especiales (sucursal_id)
```

Las instalaciones “heredan” la jerarquía en el sentido de que siempre se accede por empresa: primero se elige la empresa y luego sus instalaciones.

---

## 2. Cómo funciona la vista **Clientes**

Objetivo: **crear y editar empresas e instalaciones** (no sectores; eso va en la vista Sectores).

### Paso 1: Listado de empresas

- **Ruta:** `GET /admin/clientes`  
- **Vista:** `admin.clientes.index`
- **Qué hace:** Lista todas las empresas (clientes) con cantidad de instalaciones. Cada tarjeta muestra:
  - Nombre y código de la empresa
  - Estado (Activa / Inactiva)
  - Enlaces: **Instalaciones (N)** → ver instalaciones de esa empresa, **Editar**, **Eliminar**
- **Acción principal:** Botón **Nueva empresa** para crear una empresa.

### Paso 2: Crear o editar empresa

- **Crear:** `GET /admin/clientes/crear` → formulario (nombre, código, RUT, razón social, dirección, comuna, ciudad, región, teléfono, email, activa).  
  `POST /admin/clientes` guarda y redirige al listado de empresas.

- **Editar:** `GET /admin/clientes/{id}/editar` → mismo formulario con datos de la empresa.  
  `PUT /admin/clientes/{id}` actualiza y redirige al listado.

- **Eliminar:** Solo si la empresa no tiene instalaciones; si tiene, se muestra error y no se elimina.

### Paso 3: Instalaciones de una empresa

- **Ruta:** `GET /admin/clientes/{empresa_id}/instalaciones`
- **Vista:** `admin.clientes.instalaciones.index`
- **Qué hace:** Lista las instalaciones (sucursales) de esa empresa. Cada tarjeta muestra:
  - Nombre y código de la instalación
  - Dirección (si existe)
  - Estado (Activa / Inactiva)
  - Enlaces: **Sectores (N)** (va a la vista Sectores para esa instalación), **Editar**, **Eliminar**
- **Acción principal:** **Nueva instalación** para crear una sucursal asociada a esta empresa.

### Paso 4: Crear o editar instalación

- **Crear:** `GET /admin/clientes/{empresa_id}/instalaciones/crear` → formulario (nombre, código, dirección, comuna, ciudad, región, teléfono, email, activa).  
  `POST /admin/clientes/{empresa_id}/instalaciones` guarda con `empresa_id = empresa_id` y redirige al listado de instalaciones de esa empresa.

- **Editar:** `GET /admin/clientes/{empresa_id}/instalaciones/{sucursal_id}/editar` → formulario con datos de la instalación.  
  `PUT /admin/clientes/{empresa_id}/instalaciones/{sucursal_id}` actualiza y redirige al listado de instalaciones.

- **Eliminar:** Solo si la instalación no tiene usuarios ni sectores; si tiene, se muestra error.

Resumen del flujo de la vista Clientes:

1. Entrar a **Clientes** → listado de empresas.  
2. Crear/editar/eliminar empresas.  
3. Entrar a **Instalaciones** de una empresa → listado de instalaciones de esa empresa.  
4. Crear/editar/eliminar instalaciones (cada una queda asociada a la empresa por `empresa_id`).

---

## 3. Cómo funciona la vista **Sectores**

Objetivo: **elegir empresa → ver sus instalaciones → administrar sectores** de cada instalación.

### Paso 1: Elegir empresa

- **Ruta:** `GET /admin/sectores`
- **Vista:** `admin.sectores.index`
- **Qué hace:** Lista **solo empresas** (no todas las sucursales). Cada tarjeta muestra:
  - Nombre y código de la empresa
  - Estado (Activa / Inactiva)
  - Cantidad de instalaciones
  - Al hacer clic se va al paso 2 (instalaciones de esa empresa).

Importante: las sucursales que no tengan `empresa_id` (datos antiguos) no aparecen en este flujo hasta que se les asigne una empresa desde **Clientes → Editar instalación** (asignando empresa) o se cree una empresa y se reasocie la sucursal.

### Paso 2: Ver instalaciones de la empresa

- **Ruta:** `GET /admin/sectores/empresa/{empresa_id}`
- **Vista:** `admin.sectores.por-empresa`
- **Qué hace:** Lista las instalaciones (sucursales) de esa empresa con cantidad de sectores por instalación. Cada tarjeta tiene:
  - Nombre y código de la instalación
  - Dirección (si existe)
  - Estado (Activa / Inactiva)
  - Cantidad de sectores
  - Enlace **“Gestionar sectores”** (o equivalente) que lleva al paso 3.

### Paso 3: Administrar sectores de una instalación

- **Ruta:** `GET /admin/sectores/sucursal/{sucursal_id}`
- **Vista:** `admin.sectores.show` (la que ya existía)
- **Qué hace:** Lista los sectores de esa instalación (sucursal). Desde aquí se puede:
  - Agregar sector (crear)
  - Editar sector
  - Activar/desactivar sector
  - Eliminar sector

El flujo de creación/edición de sectores (rutas `sectores/crear`, `sectores/store`, `sectores/{sector}/editar`, etc.) no cambia; solo cambia la forma de **llegar** a una instalación: ahora siempre es **Sectores → Empresa → Instalación → Sectores de esa instalación**.

Resumen del flujo de la vista Sectores:

1. Entrar a **Sectores** → listado de empresas.  
2. Clic en una empresa → listado de **instalaciones** de esa empresa.  
3. Clic en una instalación → listado de **sectores** de esa instalación.  
4. Desde ahí se crean, editan, activan/desactivan y eliminan sectores como antes.

---

## 4. Rutas y controladores implicados

| Ruta (ejemplo) | Controlador (método) | Descripción |
|----------------|----------------------|-------------|
| GET /admin/clientes | ClienteController@index | Lista empresas |
| GET /admin/clientes/crear | ClienteController@create | Formulario nueva empresa |
| POST /admin/clientes | ClienteController@store | Guardar empresa |
| GET /admin/clientes/{id}/editar | ClienteController@edit | Formulario editar empresa |
| PUT /admin/clientes/{id} | ClienteController@update | Actualizar empresa |
| DELETE /admin/clientes/{id} | ClienteController@destroy | Eliminar empresa |
| GET /admin/clientes/{id}/instalaciones | ClienteController@instalaciones | Lista instalaciones de la empresa |
| GET /admin/clientes/{id}/instalaciones/crear | ClienteController@createInstalacion | Formulario nueva instalación |
| POST /admin/clientes/{id}/instalaciones | ClienteController@storeInstalacion | Guardar instalación |
| GET /admin/clientes/{id}/instalaciones/{sucursal_id}/editar | ClienteController@editInstalacion | Formulario editar instalación |
| PUT /admin/clientes/{id}/instalaciones/{sucursal_id} | ClienteController@updateInstalacion | Actualizar instalación |
| DELETE /admin/clientes/{id}/instalaciones/{sucursal_id} | ClienteController@destroyInstalacion | Eliminar instalación |
| GET /admin/sectores | SectorController@index | Lista empresas (para elegir y luego sectores) |
| GET /admin/sectores/empresa/{empresa_id} | SectorController@porEmpresa | Lista instalaciones de la empresa |
| GET /admin/sectores/sucursal/{sucursal_id} | SectorController@show | Lista sectores de la instalación (CRUD sectores como antes) |

---

## 5. Menú de administración

En **Gestión** (sidebar y menú móvil) quedan:

- **Clientes:** listado de empresas (vista Clientes).
- **Dispositivos**
- **Ubicaciones**
- **Sectores:** listado de empresas para luego elegir instalación y gestionar sectores (vista Sectores).
- **Puntos de ronda (QR)**

---

## 6. Migraciones a ejecutar

Para aplicar la estructura en base de datos:

```bash
php artisan migrate
```

Migraciones añadidas:

- `2026_02_18_100000_create_empresas_table.php` – crea la tabla `empresas`.
- `2026_02_18_100001_add_empresa_id_to_sucursales.php` – agrega `empresa_id` a `sucursales`.

Después de migrar, se pueden crear empresas desde **Clientes → Nueva empresa** y, para sucursales ya existentes, asignarles una empresa editando la instalación desde **Clientes → [Empresa] → Instalaciones → Editar** y (cuando lo implementes en el formulario de edición de instalación) eligiendo la empresa. Por ahora el formulario de instalaciones no incluye selector de empresa porque la instalación se crea siempre bajo la empresa desde la que se entró; para sucursales creadas antes (sin empresa), se puede hacer una migración de datos o asignar empresa desde un futuro campo en edición de instalación.

---

Con esta estructura y estos flujos puedes revisar o ajustar cualquier detalle (por ejemplo, filtros por empresa en reportes, o que al editar una instalación se pueda cambiar de empresa) manteniendo la misma jerarquía: **Empresa → Instalaciones (sucursales) → Sectores**.
