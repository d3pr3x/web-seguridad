# Estado de sistemas solicitados – Resumen para el cliente

Resumen de lo que **sí tiene** el proyecto, lo que **no tiene** y cómo puede solicitar lo que falta.

---

## 1. Sistema de reporte

### ¿Lo tiene? **SÍ**

El proyecto incluye un **sistema de reportes** operativo con varias vistas y usos.

#### Objetivos y alcance actual

| Objetivo | Descripción | Estado |
|----------|-------------|--------|
| **Reportes de tareas en terreno** | El personal registra reportes desde móvil asociados a tareas (sucesos, inspección, control de acceso, ronda de vigilancia, etc.) con datos dinámicos, fotos y geolocalización. | ✅ Implementado |
| **Reporte diario (admin)** | Vista por fecha con reportes del día, días trabajados, estadísticas y agrupación por sucursal. | ✅ Implementado |
| **Reporte por sucursal** | Filtro por fecha y sucursal, estadísticas (total reportes, acciones disuasivas, delitos en turnos), tabla con día, hora, novedad, acciones, resultado, fotografía y usuario. | ✅ Implementado |
| **Exportación a PDF** | Generación de reporte por sucursal en PDF con encabezado, estadísticas y tabla. | ✅ Implementado |
| **Reportes especiales** | Tipos: Incidentes, Denuncia, Detenido, Acción sospechosa; por sector/sucursal, con imágenes y geolocalización. | ✅ Implementado |
| **Novedades / informes** | Registro de incidentes, observaciones e información general con nivel de gravedad y evidencias. | ✅ Implementado |
| **Acciones de servicio** | Registro de inicio de servicio, rondas, constancias, concurrencia autoridades/servicios, entrega de servicio. | ✅ Implementado |

#### Requerimientos funcionales que ya cumple (para mostrar al cliente)

- Creación de reportes desde la app por el usuario (tarea, datos, fotos, ubicación).
- Filtrado por fecha y sucursal.
- Visualización en tabla con columnas: Día, Hora, Novedad, Acciones, Resultado, Fotografía, Usuario.
- Estadísticas: total de reportes, acciones disuasivas, delitos en turnos, sucursales con reportes.
- Exportación del reporte por sucursal a PDF.
- Reportes especiales con tipos definidos y filtros (tipo, estado, fecha).
- Geolocalización y múltiples imágenes por reporte.

#### Dónde se usa en la aplicación

- **Usuario:** Portal usuario → Reportes (por tarea) y Reportes especiales.
- **Supervisor/Admin:** Reporte diario, Reporte por sucursal, gestión de reportes especiales.

---

## 2. Sistema de rondas QR

### ¿Lo tiene? **SÍ** (implementado)

El proyecto incluye un sistema de rondas con códigos QR: puntos por sucursal, QR únicos, escaneo por guardias, reporte para supervisión. Existe registro de **rondas** como acción y como tarea (“Ronda de Vigilancia”), pero **no** hay sistema de **rondas con validación por QR** (puntos de ronda con código QR que el guardia deba escanear).

#### Qué hay hoy (sin QR)

- Tipo de acción “Rondas” para registrar que se hizo una ronda.
- Tarea “Ronda de Vigilancia” con campos como ruta, hora, hallazgos (registro manual, sin puntos ni QR).
- Ningún modelo de “punto de ronda”, “código QR” ni flujo de escaneo QR.

#### Cómo debe solicitarlo el cliente (requerimientos sugeridos)

Para cotizar y desarrollar el **sistema de rondas QR**, el cliente debería definir al menos:

1. **Puntos de ronda**
   - ¿Quién define los puntos? (admin por sucursal/sector).
   - ¿Cada punto tiene un QR único o un código reutilizable por ubicación?
   - ¿Orden obligatorio de los puntos o solo “registrar que se pasó por cada uno”?

2. **Flujo del guardia**
   - Escanear QR en cada punto → ¿solo registrar hora y ubicación, o también fotos/observaciones?
   - ¿Se exige completar todos los puntos en un tiempo máximo o en un orden determinado?
   - ¿Qué pasa si no escanea un punto (alerta, reporte, bloqueo)?

3. **Supervisión**
   - ¿El supervisor ve en tiempo real qué puntos se han escaneado y cuáles faltan?
   - ¿Reportes por ronda (completada / incompleta) y por período (día/semana)?
   - ¿Necesidad de exportar (PDF/Excel) por sucursal o por guardia?

4. **Integración**
   - ¿Las rondas QR deben aparecer dentro del reporte diario o del reporte por sucursal actual, o en una pantalla aparte?

Con esto se puede definir alcance, pantallas y esfuerzo de desarrollo.

---

## 3. Sistema de acceso visitas

### ¿Lo tiene? **NO** (solo registro manual dentro de reportes)

Hay **registro de “control de acceso”** como **una tarea más** que el guardia llena a mano (tipo de acceso, nombre, RUT, motivo de visita, hora de entrada). **No** existe un **módulo dedicado de gestión de visitas** (pre-registro, listado de visitantes, check-in/check-out, invitaciones, etc.).

#### Qué hay hoy

- Tarea “Control de Acceso” con campos: tipo (Persona/Vehículo/Mercancía), nombre/razón social, RUT/documento, motivo de visita, hora de entrada.
- En “Actividad especial” existe la opción “Control de Visitas” como tipo de actividad a reportar.
- No hay tablas de visitantes, visitas programadas, invitaciones ni flujo de entrada/salida.

#### Cómo debe solicitarlo el cliente (requerimientos sugeridos)

Para cotizar el **sistema de acceso visitas**, el cliente debería definir:

1. **Tipos de visita**
   - ¿Solo personas o también vehículos/cargas?
   - ¿Visitas espontáneas, pre-registradas o ambas?
   - ¿Necesidad de “invitación” previa (link o código) para que el visitante se pre-registre?

2. **Registro de entrada/salida**
   - ¿Check-in y check-out obligatorios (hora de entrada y salida)?
   - ¿Quién registra: solo el guardia en recepción o también el visitante (totem/tablet)?
   - ¿Se requiere fotografía o documento del visitante al ingresar?

3. **Datos del visitante**
   - Campos mínimos: nombre, RUT/documento, empresa, motivo, persona/área a visitar.
   - ¿Otros campos? (placa, proveedor, contacto interno, etc.)

4. **Autorización y restricciones**
   - ¿Áreas o horarios permitidos por tipo de visitante?
   - ¿Listas de visitantes no permitidos o restringidos?
   - ¿Aprobación previa por un responsable antes de permitir el ingreso?

5. **Consultas y reportes**
   - ¿Listado de visitas del día por sucursal?
   - ¿Historial por visitante o por empresa?
   - ¿Exportación (PDF/Excel) para auditoría?
   - ¿Integración con el reporte por sucursal actual (incluir visitas del día)?

6. **Experiencia del visitante**
   - ¿Solo registro en recepción o también pre-registro web (formulario antes de llegar)?
   - ¿Notificación o comprobante (email/SMS) al visitante al registrarse o al salir?

Con esto se puede diseñar el módulo de visitas y su integración con el sistema actual.

---

## Resumen ejecutivo para presentar al cliente

| Sistema              | ¿Está en el proyecto? | Acción recomendada |
|----------------------|------------------------|---------------------|
| **Sistema de reporte**   | **SÍ** – Completo (reportes de tareas, diario, por sucursal, PDF, reportes especiales, novedades, acciones). | Mostrar objetivos y requerimientos de este documento y la app actual como referencia. |
| **Sistema de rondas QR** | **SÍ** – Puntos de ronda por sucursal, QR únicos, escaneo por guardias, reporte de escaneos para supervisión. | Uso: Admin crea puntos y genera/descarga QR; guardias escanean; supervisores/Admin ven reporte por fecha, sucursal y guardia. |
| **Sistema acceso visitas** | **NO** – Solo tarea “Control de Acceso” (registro manual). | Pedir al cliente que complete los requerimientos de la sección 3 (tipos de visita, entrada/salida, datos, autorización, reportes, experiencia visitante) para cotizar y desarrollar. |

---

**Uso sugerido:**  
- Para **reportes**: usar la tabla de “Requerimientos funcionales que ya cumple” y las pantallas actuales para alinear expectativas y mostrar el objetivo cumplido.  
- Para **rondas QR** y **acceso visitas**: usar las preguntas de cada sección como checklist o formulario de requerimientos que el cliente complete y envíe para definir alcance y presupuesto.
