# Estructura de la base de datos – Web Seguridad

Documento para revisar que la base de datos esté bien armada: tablas, columnas, relaciones (FK) y diagrama de conexiones.

---

## 1. Diagrama de relaciones (Mermaid)

Puedes visualizar este diagrama en GitHub, GitLab, VS Code (con extensión Mermaid) o en [mermaid.live](https://mermaid.live).

```mermaid
erDiagram
    jerarquias ||--o{ empresas : "jerarquia_id"
    modalidades_jerarquia ||--o{ empresas : "modalidad_id"
    modalidades_jerarquia ||--o{ modalidad_roles : "modalidad_id"
    roles_usuario ||--o{ modalidad_roles : "rol_id"
    jerarquias ||--o{ jerarquia_rol : "jerarquia_id"
    roles_usuario ||--o{ jerarquia_rol : "rol_id"
    roles_usuario ||--o{ rol_permiso : "rol_id"
    permisos ||--o{ rol_permiso : "permiso_id"

    empresas ||--o{ sucursales : "empresa_id"
    sucursales ||--o{ usuarios : "sucursal_id"
    roles_usuario ||--o{ usuarios : "rol_id"
    sucursales ||--o{ sectores : "sucursal_id"
    empresas ||--o{ sectores : "empresa_id"
    sucursales ||--o{ acciones : "sucursal_id"
    sectores ||--o{ acciones : "sector_id"
    usuarios ||--o{ acciones : "id_usuario"
    sucursales ||--o{ reportes_especiales : "sucursal_id"
    sectores ||--o{ reportes_especiales : "sector_id"
    tipos_incidente ||--o{ reportes_especiales : "tipo_incidente_id"
    acciones ||--o{ reportes_especiales : "accion_id"
    usuarios ||--o{ reportes_especiales : "id_usuario"
    usuarios ||--o{ reportes_especiales : "leido_por_id"
    sucursales ||--o{ puntos_ronda : "sucursal_id"
    sectores ||--o{ puntos_ronda : "sector_id"
    puntos_ronda ||--o{ escaneos_ronda : "punto_ronda_id"
    usuarios ||--o{ escaneos_ronda : "id_usuario"
    sucursales ||--o{ ubicaciones_permitidas : "sucursal_id"

    tareas ||--o{ detalles_tarea : "tarea_id"
    tareas ||--o{ reportes : "tarea_id"
    sectores ||--o{ reportes : "sector_id"
    usuarios ||--o{ reportes : "id_usuario"
    usuarios ||--o{ reportes : "leido_por_id"
    reportes ||--o{ informes : "reporte_id"

    grupos_incidentes ||--o{ tipos_incidente : "grupo_id"
    usuarios ||--o{ documentos : "id_usuario"
    usuarios ||--o{ documentos : "aprobado_por"
    documentos ||--o{ documentos : "documento_anterior_id"
    usuarios ||--o{ ingresos : "id_guardia"
    usuarios ||--o{ blacklists : "creado_por"
    sucursales ||--o{ personas : "sucursal_id"
    usuarios ||--o{ dias_trabajados : "id_usuario"
    usuarios ||--o{ reuniones : "id_usuario_creador"
    usuarios ||--o{ sesiones : "user_id"

    usuarios ||--o{ auditorias : "user_id"
    empresas ||--o{ auditorias : "empresa_id"
    sucursales ||--o{ auditorias : "sucursal_id"

    jerarquias {
        bigint id PK
        string nombre
        text descripcion
        timestamps
    }
    modalidades_jerarquia {
        bigint id PK
        string nombre
        text descripcion
        boolean activo
        timestamps
        soft_deletes
    }
    empresas {
        bigint id PK
        bigint jerarquia_id FK
        bigint modalidad_id FK
        string nombre
        string codigo
        string razon_social
        string rut
        text direccion
        string comuna
        string ciudad
        string region
        string telefono
        string email
        boolean activa
        json modulos_activos
        timestamps
        soft_deletes
    }
    sucursales {
        bigint id PK
        bigint empresa_id FK
        string nombre
        string codigo UK
        text direccion
        string comuna
        string ciudad
        string region
        string telefono
        string email
        boolean activa
        timestamp creado_en
        timestamp actualizado_en
        soft_deletes
    }
    roles_usuario {
        bigint id PK
        string nombre
        string slug UK
        text descripcion
        timestamp creado_en
        timestamp actualizado_en
        boolean activo
    }
    permisos {
        bigint id PK
        string nombre
        string slug UK
        text descripcion
        timestamps
        boolean activo
    }
    rol_permiso {
        bigint id PK
        bigint rol_id FK
        bigint permiso_id FK
        timestamp creado_en
        unique rol_id permiso_id
    }
    usuarios {
        bigint id_usuario PK
        string run UK
        string nombre_completo
        string rango
        string email
        string telefono
        string clave
        date fecha_nacimiento
        text domicilio
        bigint rol_id FK
        bigint sucursal_id FK
        string browser_fingerprint
        boolean dispositivo_verificado
        remember_token
        timestamp creado_en
        timestamp actualizado_en
        soft_deletes
    }
    sectores {
        bigint id PK
        bigint sucursal_id FK
        bigint empresa_id FK
        string nombre
        text descripcion
        boolean activo
        timestamps
        soft_deletes
    }
    acciones {
        bigint id PK
        bigint id_usuario FK
        bigint sucursal_id FK
        bigint sector_id FK
        enum tipo
        string tipo_hecho
        string importancia
        date dia
        time hora
        text novedad
        text accion
        text resultado
        json imagenes
        decimal latitud
        decimal longitud
        timestamps
        soft_deletes
    }
    tareas {
        bigint id PK
        string nombre
        string categoria
        text descripcion
        string icono
        string color
        boolean activa
        timestamps
        soft_deletes
    }
    detalles_tarea {
        bigint id PK
        bigint tarea_id FK
        string campo_nombre
        string tipo_campo
        text opciones
        boolean requerido
        int orden
        timestamps
    }
    reportes {
        bigint id PK
        bigint id_usuario FK
        bigint tarea_id FK
        bigint sector_id FK
        json datos
        json imagenes
        decimal latitud
        decimal longitud
        enum estado
        text comentarios_admin
        bigint leido_por_id FK
        timestamp fecha_lectura
        timestamps
        soft_deletes
    }
    informes {
        bigint id PK
        bigint reporte_id FK
        int numero_informe UK
        time hora
        text descripcion
        text lesionados
        json acciones_inmediatas
        json conclusiones
        json fotografias
        string estado
        timestamp fecha_aprobacion
        string aprobado_por
        text comentarios_aprobacion
        timestamps
        soft_deletes
    }
    grupos_incidentes {
        bigint id PK
        string nombre
        string slug UK
        text descripcion
        int orden
        boolean activo
        timestamps
    }
    tipos_incidente {
        bigint id PK
        bigint grupo_id FK
        string nombre
        string slug
        int orden
        boolean activo
        timestamps
        unique grupo_id slug
    }
    reportes_especiales {
        bigint id PK
        bigint id_usuario FK
        bigint accion_id FK
        bigint sucursal_id FK
        bigint sector_id FK
        bigint tipo_incidente_id FK
        enum tipo
        date dia
        time hora
        text novedad
        text accion
        text resultado
        json imagenes
        enum estado
        text comentarios_admin
        bigint leido_por_id FK
        timestamp fecha_lectura
        timestamps
        soft_deletes
    }
    documentos {
        bigint id PK
        bigint id_usuario FK
        enum tipo_documento
        string imagen_frente
        string imagen_reverso
        enum estado
        text motivo_rechazo
        bigint aprobado_por FK
        timestamp aprobado_en
        boolean es_cambio
        bigint documento_anterior_id FK
        timestamp creado_en
        timestamp actualizado_en
        soft_deletes
    }
    personas {
        bigint id PK
        string rut UK
        string pasaporte
        string nombre
        string telefono
        string email
        string empresa
        text notas
        bigint sucursal_id FK
        timestamps
        soft_deletes
    }
    ingresos {
        bigint id PK
        string tipo
        string rut
        string pasaporte
        string nombre
        string patente
        bigint id_guardia FK
        timestamp fecha_ingreso
        timestamp fecha_salida
        string estado
        boolean alerta_blacklist
        string ip_ingreso
        text user_agent
        timestamps
        soft_deletes
    }
    blacklists {
        bigint id PK
        string rut
        string patente
        text motivo
        date fecha_inicio
        date fecha_fin
        boolean activo
        bigint creado_por FK
        timestamps
        soft_deletes
    }
    dispositivos_permitidos {
        bigint id PK
        string browser_fingerprint UK
        string descripcion
        boolean activo
        boolean requiere_ubicacion
        timestamps
    }
    ubicaciones_permitidas {
        bigint id PK
        string nombre
        decimal latitud
        decimal longitud
        int radio
        boolean activa
        text descripcion
        bigint sucursal_id FK
        timestamps
    }
    puntos_ronda {
        bigint id PK
        bigint sucursal_id FK
        bigint sector_id FK
        string nombre
        string codigo UK
        text descripcion
        int orden
        decimal lat
        decimal lng
        int distancia_maxima_metros
        boolean activo
        timestamps
        soft_deletes
    }
    escaneos_ronda {
        bigint id PK
        bigint punto_ronda_id FK
        bigint id_usuario FK
        timestamp escaneado_en
        decimal lat
        decimal lng
        timestamps
        soft_deletes
    }
    dias_trabajados {
        bigint id PK
        bigint id_usuario FK
        date fecha
        decimal ponderacion
        text observaciones
        timestamp creado_en
        timestamp actualizado_en
        unique id_usuario fecha
    }
    configuraciones_sueldo {
        bigint id PK
        string tipo_dia
        decimal multiplicador
        text descripcion
        boolean activo
        timestamps
    }
    feriados {
        bigint id PK
        string nombre
        date fecha UK
        boolean irrenunciable
        boolean activo
        timestamps
    }
    reuniones {
        bigint id PK
        string titulo
        text descripcion
        timestamp fecha_reunion
        string ubicacion
        bigint id_usuario_creador FK
        string estado
        timestamp creado_en
        timestamp actualizado_en
        soft_deletes
    }
    sesiones {
        string id PK
        bigint user_id FK
        string ip_address
        text user_agent
        longtext payload
        int last_activity
    }
    tokens_recuperacion {
        string email PK
        string token
        timestamp creado_en
    }
    auditorias {
        bigint id PK
        bigint user_id FK
        bigint empresa_id FK
        bigint sucursal_id FK
        string accion
        string tabla
        string registro_id
        string route
        string ip
        text user_agent
        json cambios_antes
        json cambios_despues
        timestamp ocurrido_en
        json metadata
    }
```

---

## 2. Cómo se conectan las tablas

Resumen de **foreign keys** y sentido de la relación (quién apunta a quién).

### 2.1 Jerarquía y clientes

| Tabla origen | Columna FK | Tabla destino | Comportamiento al borrar |
|--------------|------------|---------------|--------------------------|
| empresas | jerarquia_id | jerarquias | SET NULL |
| empresas | modalidad_id | modalidades_jerarquia | SET NULL |
| sucursales | empresa_id | empresas | SET NULL |
| modalidad_roles | modalidad_id | modalidades_jerarquia | CASCADE |
| modalidad_roles | rol_id | roles_usuario | CASCADE |
| jerarquia_rol | jerarquia_id | jerarquias | CASCADE |
| jerarquia_rol | rol_id | roles_usuario | CASCADE |

### 2.2 Usuarios y permisos

| Tabla origen | Columna FK | Tabla destino | Comportamiento al borrar |
|--------------|------------|---------------|--------------------------|
| usuarios | rol_id | roles_usuario | SET NULL |
| usuarios | sucursal_id | sucursales | SET NULL |
| rol_permiso | rol_id | roles_usuario | CASCADE |
| rol_permiso | permiso_id | permisos | CASCADE |
| sesiones | user_id | usuarios (id_usuario) | CASCADE |

### 2.3 Sectores y operación

| Tabla origen | Columna FK | Tabla destino | Comportamiento al borrar |
|--------------|------------|---------------|--------------------------|
| sectores | sucursal_id | sucursales | CASCADE |
| sectores | empresa_id | empresas | SET NULL |
| acciones | id_usuario | usuarios | CASCADE |
| acciones | sucursal_id | sucursales | CASCADE |
| acciones | sector_id | sectores | SET NULL |
| detalles_tarea | tarea_id | tareas | CASCADE |
| reportes | id_usuario | usuarios | CASCADE |
| reportes | tarea_id | tareas | CASCADE |
| reportes | sector_id | sectores | SET NULL |
| reportes | leido_por_id | usuarios | SET NULL |
| informes | reporte_id | reportes | CASCADE |
| reportes_especiales | id_usuario | usuarios | CASCADE |
| reportes_especiales | accion_id | acciones | SET NULL |
| reportes_especiales | sucursal_id | sucursales | CASCADE |
| reportes_especiales | sector_id | sectores | SET NULL |
| reportes_especiales | tipo_incidente_id | tipos_incidente | SET NULL |
| reportes_especiales | leido_por_id | usuarios | SET NULL |
| tipos_incidente | grupo_id | grupos_incidentes | CASCADE |

### 2.4 Documentos y control de acceso

| Tabla origen | Columna FK | Tabla destino | Comportamiento al borrar |
|--------------|------------|---------------|--------------------------|
| documentos | id_usuario | usuarios | CASCADE |
| documentos | aprobado_por | usuarios | SET NULL |
| documentos | documento_anterior_id | documentos | SET NULL (autorreferencia) |
| personas | sucursal_id | sucursales | SET NULL |
| ingresos | id_guardia | usuarios | CASCADE |
| blacklists | creado_por | usuarios | SET NULL |
| ubicaciones_permitidas | sucursal_id | sucursales | CASCADE |

### 2.5 Rondas QR

| Tabla origen | Columna FK | Tabla destino | Comportamiento al borrar |
|--------------|------------|---------------|--------------------------|
| puntos_ronda | sucursal_id | sucursales | CASCADE |
| puntos_ronda | sector_id | sectores | SET NULL |
| escaneos_ronda | punto_ronda_id | puntos_ronda | CASCADE |
| escaneos_ronda | id_usuario | usuarios | CASCADE |

### 2.6 Días trabajados, reuniones y auditoría

| Tabla origen | Columna FK | Tabla destino | Comportamiento al borrar |
|--------------|------------|---------------|--------------------------|
| dias_trabajados | id_usuario | usuarios | CASCADE |
| reuniones | id_usuario_creador | usuarios | SET NULL |
| auditorias | user_id | usuarios (id_usuario) | SET NULL |
| auditorias | empresa_id | empresas | SET NULL |
| auditorias | sucursal_id | sucursales | SET NULL |

---

## 3. Datos de cada tabla

Para cada tabla se indica: **nombre**, **para qué sirve** y **columnas con tipo/dato**.

---

### jerarquias

**Uso:** Catálogo de tipos de jerarquía (por cliente/empresa).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| nombre | string | | |
| descripcion | text | nullable | |
| created_at | timestamp | nullable | |
| updated_at | timestamp | nullable | |

---

### modalidades_jerarquia

**Uso:** Modalidad de jerarquía por empresa (directa, con_jefe_turno, custom). Las instalaciones heredan la modalidad de la empresa.

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| nombre | string | | ej: directa, con_jefe_turno |
| descripcion | text | nullable | |
| activo | boolean | default true | |
| created_at | timestamp | nullable | |
| updated_at | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |

---

### jerarquia_rol

**Uso:** Pivot jerarquía ↔ roles (orden de roles por jerarquía).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| jerarquia_id | bigint | FK → jerarquias, CASCADE | |
| rol_id | bigint | FK → roles_usuario, CASCADE | |
| orden | smallint | default 0 | |
| created_at | timestamp | nullable | |
| updated_at | timestamp | nullable | |
| UNIQUE | | (jerarquia_id, rol_id) | |

---

### empresas

**Uso:** Clientes (nivel superior). Tienen modalidad y opcionalmente módulos activos por empresa.

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| jerarquia_id | bigint | FK → jerarquias, nullable, SET NULL | |
| modalidad_id | bigint | FK → modalidades_jerarquia, nullable, SET NULL | (migración posterior) |
| nombre | string | | |
| codigo | string(50) | nullable, unique | |
| razon_social | string(200) | nullable | |
| rut | string(20) | nullable | |
| direccion | text | nullable | |
| comuna | string(100) | nullable | |
| ciudad | string(100) | nullable | |
| region | string(100) | nullable | |
| telefono | string(50) | nullable | |
| email | string(100) | nullable | |
| activa | boolean | default true | |
| modulos_activos | json/jsonb | nullable | claves de módulos habilitados; null = todos los globales |
| created_at | timestamp | nullable | |
| updated_at | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |

---

### sucursales

**Uso:** Instalaciones/sedes de una empresa. Aquí se asocian usuarios, sectores, acciones, reportes especiales, puntos de ronda, ubicaciones.

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| empresa_id | bigint | FK → empresas, nullable, SET NULL | |
| nombre | string | | |
| empresa | string | nullable | texto redundante si se usa |
| codigo | string | unique | |
| direccion | text | | |
| comuna | string | nullable | |
| ciudad | string | | |
| region | string | | |
| telefono | string | nullable | |
| email | string | nullable | |
| activa | boolean | default true | |
| creado_en | timestamp | nullable | |
| actualizado_en | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |

---

### roles_usuario

**Uso:** Roles del sistema (ADMIN, SUPERVISOR, USUARIO, GUARDIA, SUPERVISOR_USUARIO, USUARIO_SUPERVISOR, etc.).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| nombre | string(80) | | |
| slug | string(50) | unique | ej: ADMIN, USUARIO |
| descripcion | text | nullable | |
| creado_en | timestamp | nullable | |
| actualizado_en | timestamp | nullable | |
| activo | boolean | default true | (migración posterior) |

---

### permisos

**Uso:** Permisos granulares (opcionales; la app usa sobre todo el rol).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| nombre | string(80) | | |
| slug | string(100) | unique | |
| descripcion | text | nullable | |
| creado_en | timestamp | nullable | |
| actualizado_en | timestamp | nullable | |
| activo | boolean | default true | (migración posterior) |

---

### rol_permiso

**Uso:** Relación N:M entre roles y permisos.

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| rol_id | bigint | FK → roles_usuario, CASCADE | |
| permiso_id | bigint | FK → permisos, CASCADE | |
| creado_en | timestamp | nullable | |
| UNIQUE | | (rol_id, permiso_id) | |

---

### modalidad_roles

**Uso:** Orden de roles por modalidad (define orden en menú/flujo).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| modalidad_id | bigint | FK → modalidades_jerarquia, CASCADE | |
| rol_id | bigint | FK → roles_usuario, CASCADE | |
| orden | int | default 0 | menor = más importante |
| activo | boolean | default true | |
| created_at | timestamp | nullable | |
| updated_at | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |
| UNIQUE | | (modalidad_id, rol_id) | |

---

### usuarios

**Uso:** Usuarios del sistema. PK es `id_usuario`; identificador lógico `run`.

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id_usuario | bigint | PK, auto | |
| run | string(20) | unique | ej: 987403M |
| nombre_completo | string(200) | | |
| rango | string(80) | nullable | cargo o grado |
| email | string | nullable, unique | |
| telefono | string(30) | nullable | |
| email_verificado_en | timestamp | nullable | |
| clave | string | | hash de contraseña |
| fecha_nacimiento | date | nullable | |
| domicilio | text | nullable | |
| rol_id | bigint | FK → roles_usuario, nullable, SET NULL | |
| sucursal_id | bigint | FK → sucursales, nullable, SET NULL | |
| browser_fingerprint | string(255) | nullable | |
| dispositivo_verificado | boolean | default false | |
| remember_token | string | nullable | |
| creado_en | timestamp | nullable | |
| actualizado_en | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |

---

### sesiones

**Uso:** Sesiones de Laravel (driver database). PK string.

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | string | PK | |
| user_id | bigint | FK → usuarios.id_usuario, nullable, CASCADE | |
| ip_address | string(45) | nullable | |
| user_agent | text | nullable | |
| payload | longtext | | |
| last_activity | int | index | |

---

### tokens_recuperacion

**Uso:** Tokens para recuperación de contraseña (reemplaza password_reset_tokens estándar).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| email | string | PK | |
| token | string | | |
| creado_en | timestamp | nullable | |

---

### sectores

**Uso:** Sectores/áreas dentro de una sucursal (y opcionalmente empresa).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| sucursal_id | bigint | FK → sucursales, CASCADE | |
| empresa_id | bigint | FK → empresas, nullable, SET NULL | |
| nombre | string | | |
| descripcion | text | nullable | |
| activo | boolean | default true | |
| created_at | timestamp | nullable | |
| updated_at | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |

---

### acciones

**Uso:** Novedades del servicio (inicio de servicio, rondas, constancias, concurrencias, entrega, etc.).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| id_usuario | bigint | FK → usuarios, CASCADE | |
| sucursal_id | bigint | FK → sucursales, CASCADE | |
| sector_id | bigint | FK → sectores, nullable, SET NULL | |
| tipo | enum | | inicio_servicio, rondas, constancias, concurrencia_autoridades, concurrencia_servicios, entrega_servicio |
| tipo_hecho | string(50) | nullable | incidente, observacion, informacion, delito, accidente |
| importancia | string(20) | nullable | importante, cotidiana, critica |
| dia | date | | |
| hora | time | | |
| novedad | text | nullable | |
| accion | text | nullable | |
| resultado | text | nullable | |
| imagenes | json | nullable | |
| latitud | decimal(10,8) | nullable | |
| longitud | decimal(11,8) | nullable | |
| precision | decimal(8,2) | nullable | |
| created_at | timestamp | nullable | |
| updated_at | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |

---

### tareas

**Uso:** Catálogo de tipos de tarea/reporte (novedades_servicio, etc.).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| nombre | string | | |
| categoria | string | default 'novedades_servicio' | |
| descripcion | text | nullable | |
| icono | string | nullable | |
| color | string | default '#007bff' | |
| activa | boolean | default true | |
| activo | boolean | default true | (migración posterior, si se añade) |
| creado_en | timestamp | nullable | |
| actualizado_en | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |

---

### detalles_tarea

**Uso:** Campos dinámicos por tarea (nombre, tipo, opciones, requerido, orden).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| tarea_id | bigint | FK → tareas, CASCADE | |
| campo_nombre | string | | |
| tipo_campo | string | | |
| opciones | text | nullable | |
| requerido | boolean | default false | |
| orden | int | default 0 | |
| creado_en | timestamp | nullable | |
| actualizado_en | timestamp | nullable | |

---

### reportes

**Uso:** Reportes ligados a una tarea (datos e imágenes en JSON; estado y lectura por jefe).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| id_usuario | bigint | FK → usuarios, CASCADE | |
| tarea_id | bigint | FK → tareas, CASCADE | |
| sector_id | bigint | FK → sectores, nullable, SET NULL | |
| datos | json | | |
| imagenes | json | nullable | |
| latitud | decimal(10,8) | nullable | |
| longitud | decimal(11,8) | nullable | |
| precision | decimal(8,2) | nullable | |
| estado | enum | default 'pendiente' | pendiente, en_revision, completado, rechazado |
| comentarios_admin | text | nullable | |
| leido_por_id | bigint | FK → usuarios, nullable, SET NULL | |
| fecha_lectura | timestamp | nullable | |
| created_at | timestamp | nullable | |
| updated_at | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |

---

### informes

**Uso:** Informes generados a partir de un reporte (descripción, lesionados, acciones, conclusiones, fotos, aprobación).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| reporte_id | bigint | FK → reportes, CASCADE | |
| numero_informe | int | unique | |
| hora | time | | |
| descripcion | text | | |
| lesionados | text | | |
| acciones_inmediatas | json | | |
| conclusiones | json | | |
| fotografias | json | nullable | |
| estado | string | default 'pendiente_revision' | |
| fecha_aprobacion | timestamp | nullable | |
| aprobado_por | string | nullable | |
| comentarios_aprobacion | text | nullable | |
| created_at | timestamp | nullable | |
| updated_at | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |

---

### grupos_incidentes

**Uso:** Catálogo de grupos de delitos/incidentes.

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| nombre | string(100) | | |
| slug | string(80) | unique | |
| descripcion | text | nullable | |
| orden | tinyint | default 0 | |
| activo | boolean | default true | |
| created_at | timestamp | nullable | |
| updated_at | timestamp | nullable | |

---

### tipos_incidente

**Uso:** Tipos de incidente dentro de cada grupo.

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| grupo_id | bigint | FK → grupos_incidentes, CASCADE | |
| nombre | string(100) | | |
| slug | string(80) | | |
| orden | tinyint | default 0 | |
| activo | boolean | default true | |
| created_at | timestamp | nullable | |
| updated_at | timestamp | nullable | |
| UNIQUE | | (grupo_id, slug) | |

---

### reportes_especiales

**Uso:** Reportes especiales (incidentes, denuncia, detenido, accion_sospechosa). Pueden vincularse a una acción y a un tipo de incidente.

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| id_usuario | bigint | FK → usuarios, CASCADE | |
| accion_id | bigint | FK → acciones, nullable, SET NULL | |
| sucursal_id | bigint | FK → sucursales, CASCADE | |
| sector_id | bigint | FK → sectores, SET NULL | |
| tipo_incidente_id | bigint | FK → tipos_incidente, nullable, SET NULL | |
| tipo | enum | | incidentes, denuncia, detenido, accion_sospechosa |
| dia | date | | |
| hora | time | | |
| novedad | text | nullable | |
| accion | text | nullable | |
| resultado | text | nullable | |
| imagenes | json | nullable | |
| latitud, longitud, precision | decimal | nullable | |
| estado | enum | default 'pendiente' | pendiente, en_revision, completado, rechazado |
| comentarios_admin | text | nullable | |
| leido_por_id | bigint | FK → usuarios, nullable, SET NULL | |
| fecha_lectura | timestamp | nullable | |
| created_at / updated_at | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |

---

### documentos

**Uso:** Documentos personales de guardias (cédula, licencia, certificados). Flujo de aprobación y versiones (documento_anterior_id).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| id_usuario | bigint | FK → usuarios, CASCADE | |
| tipo_documento | enum | | cedula_identidad, licencia_conductor, certificado_antecedentes, certificado_os10 |
| imagen_frente | string | nullable | ruta/identificador |
| imagen_reverso | string | nullable | |
| estado | enum | default 'pendiente' | pendiente, aprobado, rechazado |
| motivo_rechazo | text | nullable | |
| aprobado_por | bigint | FK → usuarios, nullable, SET NULL | |
| aprobado_en | timestamp | nullable | |
| es_cambio | boolean | default false | |
| documento_anterior_id | bigint | FK → documentos, nullable, SET NULL | |
| creado_en | timestamp | nullable | |
| actualizado_en | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |

---

### personas

**Uso:** Base de personas (visitantes): RUT/pasaporte, nombre, contacto. Opcionalmente asociada a sucursal.

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| rut | string(12) | unique | RUT normalizado |
| pasaporte | string(30) | nullable | |
| nombre | string(100) | | |
| telefono | string(20) | nullable | |
| email | string(100) | nullable | |
| empresa | string(100) | nullable | |
| notas | text | nullable | |
| sucursal_id | bigint | FK → sucursales, nullable, SET NULL | |
| created_at / updated_at | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |

---

### ingresos

**Uso:** Registro de ingresos y salidas (peatonal/vehicular). Guardia que registra, fechas, estado, alerta blacklist.

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| tipo | string(20) | | peatonal \| vehicular |
| rut | string(12) | index | |
| pasaporte | string(30) | nullable | |
| nombre | string(100) | | |
| patente | string(10) | nullable, index | |
| id_guardia | bigint | FK → usuarios, CASCADE | |
| fecha_ingreso | timestamp | default now | |
| fecha_salida | timestamp | nullable | |
| estado | string(20) | default 'ingresado' | |
| alerta_blacklist | boolean | default false | |
| ip_ingreso | string(45) | nullable | |
| user_agent | text | nullable | |
| created_at / updated_at | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |
| Índices | | | fecha_ingreso, (estado, fecha_ingreso) |

---

### blacklists

**Uso:** Personas o patentes bloqueadas (motivo, vigencia, activo).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| rut | string(12) | index | |
| patente | string(10) | nullable, index | |
| motivo | text | | |
| fecha_inicio | date | | |
| fecha_fin | date | nullable | |
| activo | boolean | default true | |
| creado_por | bigint | FK → usuarios, nullable, SET NULL | |
| created_at / updated_at | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |
| Índice | | | (activo, fecha_fin) |

---

### dispositivos_permitidos

**Uso:** Dispositivos permitidos por browser_fingerprint (y si requieren ubicación).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| browser_fingerprint | string(255) | unique | |
| descripcion | string | nullable | |
| activo | boolean | default true | |
| requiere_ubicacion | boolean | default true | |
| created_at / updated_at | timestamp | nullable | |

---

### ubicaciones_permitidas

**Uso:** Zonas geográficas permitidas (lat, lng, radio). Opcionalmente por sucursal.

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| nombre | string | | |
| latitud | decimal(10,8) | | |
| longitud | decimal(11,8) | | |
| radio | int | default 50 | metros |
| activa | boolean | default true | |
| descripcion | text | nullable | |
| sucursal_id | bigint | FK → sucursales, nullable, CASCADE | |
| created_at / updated_at | timestamp | nullable | |

---

### puntos_ronda

**Uso:** Puntos de ronda por sucursal (QR): nombre, código único, orden, coordenadas, distancia máxima.

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| sucursal_id | bigint | FK → sucursales, CASCADE | |
| sector_id | bigint | FK → sectores, nullable, SET NULL | |
| nombre | string | | |
| codigo | string(32) | unique | para QR |
| descripcion | text | nullable | |
| orden | smallint | default 0 | |
| lat, lng | decimal | nullable | |
| distancia_maxima_metros | smallint | default 10 | |
| activo | boolean | default true | |
| created_at / updated_at | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |

---

### escaneos_ronda

**Uso:** Cada escaneo de un punto por un usuario (fecha/hora y opcionalmente coordenadas).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| punto_ronda_id | bigint | FK → puntos_ronda, CASCADE | |
| id_usuario | bigint | FK → usuarios, CASCADE | |
| escaneado_en | timestamp | | |
| lat, lng | decimal | nullable | |
| created_at / updated_at | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |
| Índices | | | (punto_ronda_id, escaneado_en), (id_usuario, escaneado_en) |

---

### dias_trabajados

**Uso:** Días trabajados por usuario (fecha, ponderación, observaciones). Un usuario no puede tener dos registros para la misma fecha.

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| id_usuario | bigint | FK → usuarios, CASCADE | |
| fecha | date | | |
| ponderacion | decimal(3,2) | default 1.00 | |
| observaciones | text | nullable | |
| creado_en | timestamp | nullable | |
| actualizado_en | timestamp | nullable | |
| UNIQUE | | (id_usuario, fecha) | |

---

### configuraciones_sueldo

**Uso:** Multiplicadores por tipo de día (normal, festivo, etc.).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| tipo_dia | string | | |
| multiplicador | decimal(3,2) | | |
| descripcion | text | nullable | |
| activo | boolean | default true | |
| created_at / updated_at | timestamp | nullable | |

---

### feriados

**Uso:** Calendario de feriados (nombre, fecha única, irrenunciable).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| nombre | string | | |
| fecha | date | unique | |
| irrenunciable | boolean | default false | |
| activo | boolean | default true | |
| created_at / updated_at | timestamp | nullable | |

---

### reuniones

**Uso:** Reuniones (título, descripción, fecha, ubicación, creador, estado).

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| titulo | string(200) | | |
| descripcion | text | nullable | |
| fecha_reunion | timestamp | nullable | |
| ubicacion | string(255) | nullable | |
| id_usuario_creador | bigint | FK → usuarios, nullable, SET NULL | |
| estado | string(40) | default 'programada' | |
| creado_en | timestamp | nullable | |
| actualizado_en | timestamp | nullable | |
| deleted_at | timestamp | nullable | soft delete |

---

### auditorias

**Uso:** Trazabilidad de acciones (quién, qué tabla, qué registro, tipo de acción, cambios antes/después, cuándo). Tabla principal de auditoría usada por el código reciente.

| Columna | Tipo | Restricción | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, auto | |
| user_id | bigint | FK → usuarios.id_usuario, nullable, SET NULL | |
| empresa_id | bigint | FK → empresas, nullable, SET NULL | |
| sucursal_id | bigint | FK → sucursales, nullable, SET NULL | |
| accion | string(50) | | create, update, delete, restore, etc. |
| tabla | string | | nombre de tabla afectada |
| registro_id | string(100) | nullable | |
| route | string(255) | nullable | |
| ip | string(45) | nullable | |
| user_agent | text | nullable | |
| cambios_antes | json | nullable | |
| cambios_despues | json | nullable | |
| ocurrido_en | timestamp | | |
| metadata | json | nullable | |
| Índices | | | (tabla, accion, ocurrido_en), user_id, empresa_id |

---

### Tablas de sistema Laravel

- **cache** / **cache_locks**: driver de cache en BD.
- **jobs** / **job_batches** / **failed_jobs**: cola de trabajos.

No se detallan aquí; son estándar de Laravel.

---

## 4. Revisión rápida: ¿está todo bien armado?

Checklist para validar la estructura:

| Punto | Comentario |
|-------|------------|
| **PK de usuarios** | La única tabla con PK distinta de `id` es **usuarios** (`id_usuario`). Todas las FK que apuntan a usuarios usan `id_usuario` o `user_id` según la migración; coherente con el modelo. |
| **Jerarquía** | Hay **jerarquias** y **modalidades_jerarquia**. Empresas tienen `jerarquia_id` y `modalidad_id`. La lógica de menú usa **modalidad_roles** (modalidad → roles con orden). **jerarquia_rol** queda como alternativa/legado por jerarquía. |
| **Cascadas** | Donde tiene sentido (ej. sucursal → sectores, usuario → acciones), está CASCADE. Donde se quiere preservar historial o flexibilidad (ej. reportes.leido_por_id, documentos.aprobado_por), está SET NULL. |
| **Soft deletes** | Coinciden con tablas “de negocio” (empresas, sucursales, usuarios, sectores, acciones, reportes, informes, reportes_especiales, documentos, ingresos, blacklists, personas, puntos_ronda, escaneos_ronda, reuniones, tareas, modalidades_jerarquia, modalidad_roles). |
| **Índices** | Ingresos, blacklists, escaneos_ronda y auditorias tienen índices por fechas y filtros habituales. |
| **Tabla auditoría** | Existe **auditoria** (singular, migración antigua) y **auditorias** (plural, migración 2026_02_19). El código actual usa **auditorias**. Si solo quieres una, conviene deprecar la antigua. |
| **IMEI** | El modelo `ImeiPermitido` existe pero no hay migración de tabla `imei_permitidos` en el listado actual. Si la funcionalidad IMEI está en uso, debe existir esa tabla (o una migración que la cree). |

Si quieres, en un siguiente paso se puede revisar una migración concreta o proponer cambios (índices, FKs o tablas faltantes).
