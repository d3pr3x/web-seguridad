# Portal de Usuario - Interfaz Móvil

## Descripción General

El portal de usuario es una interfaz diseñada específicamente para dispositivos móviles, optimizada para que los trabajadores de campo puedan registrar novedades y reportes de manera rápida y eficiente.

## Características Principales

### ✅ Diseño Móvil-First
- Interfaz optimizada para pantallas de celular
- Navegación táctil intuitiva
- Formularios adaptados para ingreso rápido
- Botones grandes y fáciles de presionar

### ✅ Menú Acordeón
- Acceso rápido a todas las secciones
- Deslizable desde el borde derecho
- Opciones disponibles:
  - Inicio (Portal Usuario)
  - Mi Perfil
  - Historial de Reportes
  - Cerrar Sesión

## Estructura del Portal

### 🟣 Sección NOVEDADES

Permite registrar eventos importantes que requieren atención. Tiene 3 tipos de acciones:

#### 1. **Incidente** (Rojo)
- Para eventos críticos que requieren atención inmediata
- Incluye nivel de gravedad: Baja, Media, Alta, Crítica
- **Paso múltiple**: Si es gravedad Alta o Crítica, requiere un segundo paso con información adicional:
  - Personas involucradas
  - Testigos
  - Acciones tomadas (obligatorio)
  - Si se notificó a autoridades

**Campos del formulario:**
- Título (obligatorio)
- Descripción (obligatorio)
- Fecha y Hora (obligatorio)
- Ubicación (obligatorio)
- Nivel de Gravedad (obligatorio)
- Evidencias fotográficas (opcional)

#### 2. **Observación** (Amarillo)
- Para situaciones que requieren revisión pero no son críticas
- Formulario de un solo paso

**Campos del formulario:**
- Título (obligatorio)
- Descripción (obligatorio)
- Fecha y Hora (obligatorio)
- Ubicación (obligatorio)
- Evidencias fotográficas (opcional)

#### 3. **Información** (Azul)
- Para comunicar información general
- Formulario de un solo paso

**Campos del formulario:**
- Título (obligatorio)
- Descripción (obligatorio)
- Fecha y Hora (obligatorio)
- Ubicación (obligatorio)
- Evidencias fotográficas (opcional)

### 🟢 Sección REPORTES

Permite registrar actividades y tareas realizadas. Tiene 3 tipos de acciones:

#### 1. **Ronda de Seguridad** (Verde)
- Para registrar rondas de seguridad realizadas
- Incluye áreas recorridas

**Campos del formulario:**
- Fecha y Hora de Inicio (obligatorio)
- Fecha y Hora de Término (opcional)
- Áreas/Sectores Recorridos (obligatorio)
- Descripción de Actividades (obligatorio)
- Observaciones o Anomalías (opcional)
- Estado General: Normal / Observado / Crítico
- Evidencias fotográficas (opcional)

#### 2. **Reporte de Turno** (Índigo)
- Para resumir todo el turno de trabajo
- El más completo de los reportes

**Campos del formulario:**
- Fecha y Hora de Inicio (obligatorio)
- Fecha y Hora de Término (obligatorio)
- Descripción de Actividades (obligatorio)
- Observaciones o Anomalías (opcional)
- Novedades del Turno (opcional)
- Estado de Equipos e Instalaciones: Normal / Con observaciones / Requiere atención / Estado crítico
- Evidencias fotográficas (opcional)

#### 3. **Actividad Especial** (Teal)
- Para tareas específicas o eventos especiales

**Campos del formulario:**
- Fecha y Hora de Inicio (obligatorio)
- Fecha y Hora de Término (opcional)
- Tipo de Actividad (obligatorio):
  - Evento Especial
  - Supervisión de Mantenimiento
  - Control de Visitas
  - Control de Transporte/Carga
  - Otro
- Descripción de Actividades (obligatorio)
- Observaciones o Anomalías (opcional)
- Estado General: Normal / Observado / Crítico
- Evidencias fotográficas (opcional)

## Características de los Formularios

### Similitudes entre Formularios de la Misma Sección

Como solicitado, los formularios dentro de cada sección comparten estructura similar:

**Novedades** (todas comparten):
- Título
- Descripción
- Fecha/Hora
- Ubicación
- Evidencias

**Reportes** (todos comparten):
- Fecha/Hora Inicio/Término
- Descripción de Actividades
- Observaciones
- Estado General
- Evidencias

### Sistema de Pasos Múltiples

Los **incidentes de gravedad Alta o Crítica** tienen un segundo paso que solicita:
- Información adicional de personas involucradas
- Testigos
- Acciones tomadas
- Notificación a autoridades

Este diseño permite:
1. Registro rápido de la información básica
2. Completar detalles adicionales solo cuando es necesario
3. Evitar formularios largos para casos simples

## Códigos de Color

Para facilitar la identificación visual:

| Sección | Color | Uso |
|---------|-------|-----|
| Novedades - Incidente | Rojo | Eventos críticos |
| Novedades - Observación | Amarillo | Situaciones a revisar |
| Novedades - Información | Azul | Información general |
| Reportes - Ronda | Verde | Rondas de seguridad |
| Reportes - Turno | Índigo | Reportes de turno |
| Reportes - Actividad | Teal | Actividades especiales |

## Navegación

### Redirección Automática por Rol

Al iniciar sesión, los usuarios son redirigidos automáticamente:

- **Usuario** o **Supervisor-Usuario**: → Portal de Usuario (`/usuario`)
- **Supervisor** o **Administrador**: → Dashboard (`/dashboard`)

### Acceso a Otras Secciones

Desde el menú acordeón, los usuarios pueden acceder a:
- **Mi Perfil**: Ver y editar información personal
- **Historial Reportes**: Ver reportes anteriores
- **Cerrar Sesión**: Salir del sistema

## Rutas del Portal

```php
// Portal principal
GET /usuario

// Novedades
GET /usuario/novedades
GET /usuario/novedades/crear/{tipo}    // tipo: incidente, observacion, informacion
POST /usuario/novedades

// Reportes
GET /usuario/reportes
GET /usuario/reportes/crear/{tipo}     // tipo: ronda, turno, actividad
POST /usuario/reportes
```

## Validación de Sucursal

Todos los usuarios deben tener una sucursal asignada para acceder al portal. Si un usuario intenta acceder sin sucursal:
1. Es redirigido a su perfil
2. Ve un mensaje solicitando contactar al administrador
3. Solo puede editar su perfil hasta que se le asigne una sucursal

Los administradores pueden acceder sin sucursal asignada.

## Archivos Creados

```
app/Http/Controllers/
└── UsuarioController.php

resources/views/usuario/
├── index.blade.php
├── novedades/
│   └── create.blade.php
└── reportes/
    └── create.blade.php
```

## Próximas Mejoras Sugeridas

1. **Geolocalización**: Capturar ubicación GPS automáticamente
2. **Modo Offline**: Permitir registro sin conexión
3. **Notificaciones Push**: Alertas de nuevas tareas o mensajes
4. **Firma Digital**: Para validar reportes
5. **Escaneo QR**: Para validar puntos de ronda
6. **Historial Personal**: Ver mis novedades y reportes
7. **Estadísticas**: Gráficos de actividad personal
8. **Chat con Supervisor**: Comunicación directa

## Usuarios de Prueba para el Portal

Para probar el portal de usuario, puedes usar:

| RUT | Rol | Contraseña |
|-----|-----|------------|
| 22334455-6 | usuario | 123456 |
| 11223344-5 | supervisor-usuario | 123456 |

Al iniciar sesión con estos usuarios, serás redirigido automáticamente al portal de usuario.

## Tecnologías Utilizadas

- **Laravel 11**: Framework backend
- **Blade Templates**: Motor de plantillas
- **Tailwind CSS**: Framework CSS para diseño responsive
- **JavaScript vanilla**: Para interactividad (menú acordeón, formularios multipaso)

## Características de Usabilidad

1. **Campos Pre-rellenados**: Fecha y hora actuales por defecto
2. **Validación en Tiempo Real**: Feedback visual inmediato
3. **Botones Grandes**: Fáciles de presionar en móvil
4. **Iconos Claros**: Identificación visual rápida
5. **Mensajes Descriptivos**: Guías claras en cada campo
6. **Carga de Múltiples Imágenes**: Para evidencias completas
7. **Navegación Intuitiva**: Flechas de retroceso siempre visibles

