# Requerimientos Completos del Sistema Web-Seguridad – Documento para Análisis

**Propósito de este documento:** Contiene toda la información detallada del proyecto para que una IA (ej. Perplexity) pueda leerlo, generar **puntos/checklist de requerimientos** y **indicar qué hay que hacer para cumplir cada punto**.

---

## 1. CONTEXTO DEL PROYECTO

### 1.1 Descripción general
Sistema web para **empresas de seguridad privada** en Chile. Permite gestionar guardias, rondas de vigilancia con QR, control de acceso de visitantes, reportes, novedades, documentos personales y estadísticas.

### 1.2 Stack tecnológico
- **Backend:** PHP 8.2, Laravel 12
- **Frontend:** Blade, Livewire 3, Tailwind CSS, Vite
- **Base de datos:** MySQL (tablas en español)
- **Dependencias principales:** barryvdh/laravel-dompdf (PDF), endroid/qr-code (QR), livewire/livewire

### 1.3 Estructura clave del código
- Rutas: `routes/web.php`
- Modelos: `app/Models/` (User, Sucursal, Reporte, Accion, RondaEscaneo, Ingreso, PuntoRonda, etc.)
- Controladores: `app/Http/Controllers/` (Admin, Supervisor, Usuario, Auth, etc.)
- Vistas: `resources/views/`
- Middleware: `VerificarSucursal`, `EnsureGuardiaControlAcceso`
- Identificador de usuario: tabla `usuarios`, PK `id_usuario`, login por `run` (RUT chileno, ej. 987403M)

---

## 2. SISTEMA DE PERFILES Y ROLES

### 2.1 Tabla de roles (roles_usuario)
Los roles se identifican por `slug` en la tabla `roles_usuario`. Relación: `usuarios.rol_id` → `roles_usuario.id`.

| Slug | Nombre | Descripción |
|------|--------|-------------|
| ADMIN | Administrador | Acceso total, no requiere sucursal |
| SUPERVISOR | Supervisor (Jefe de contrato) | Solo supervisión |
| SUPERVISOR_USUARIO | Supervisor-Usuario (Jefe de turno) | Operativo + supervisión |
| USUARIO_SUPERVISOR | Usuario-Supervisor (2º jefe) | Operativo + reporte sucursal |
| USUARIO | Usuario (Guardia) | Operativo: Control acceso, Rondas QR, Reportes |

### 2.2 Métodos helper en modelo User
```php
$user->tieneSucursal()        // bool
$user->esAdministrador()      // bool
$user->esSupervisor()         // bool (incluye SUPERVISOR, SUPERVISOR_USUARIO, USUARIO_SUPERVISOR)
$user->esSupervisorUsuario()  // bool
$user->esUsuarioSupervisor()  // bool
$user->esUsuario()            // bool (guardia)
$user->puedeVerControlAcceso()
$user->puedeVerRondasQR()
$user->puedeVerMisReportes()
$user->puedeVerReporteSucursal()
$user->puedeVerSupervision()
$user->puedeVerReportesEstadisticasCompletos()
$user->puedeVerReportesDiarios()
$user->puedeVerGestion()
```

### 2.3 Matriz de acceso (menú / vistas)

| Recurso | Guardia | 2º jefe | Jefe turno | Jefe contrato | Admin |
|---------|:-------:|:-------:|:----------:|:-------------:|:-----:|
| Inicio | ✓ | ✓ | ✓ | ✓ | ✓ |
| Mi perfil | ✓ | ✓ | ✓ | ✓ | ✓ |
| Control de acceso | ✓ | ✓ | ✓ | — | — |
| Mis reportes | ✓ | ✓ | ✓ | — | — |
| Mis documentos | ✓ | ✓ | ✓ | — | — |
| Rondas QR | ✓ | ✓ | ✓ | — | — |
| Reporte por sucursal | — | ✓ | ✓ | ✓ | ✓ |
| Reportes y estadísticas (todos, escaneos QR) | — | — | ✓ | ✓ | ✓ |
| Reportes diarios | — | — | — | — | ✓ |
| Supervisión (aprobar docs, novedades, reportes) | — | — | ✓ | ✓ | ✓ |
| Gestión (usuarios, dispositivos, ubicaciones, sectores, puntos ronda) | — | — | — | — | ✓ |

### 2.4 Validación de sucursal
- **Middleware `verificar.sucursal`:** usuarios NO admin deben tener sucursal asignada para acceder a funcionalidades. Sin sucursal, solo pueden ver/editar su perfil.
- **Admin:** puede acceder sin sucursal.
- Rutas sin verificación de sucursal: perfil, cambio de contraseña.

### 2.5 Login
- Login por **RUT** (no email) y contraseña.
- Redirección post-login: Admin → `/administrador`, Supervisor → `/supervisor`, resto → `/usuario`.
- Si usuario no admin sin sucursal → redirige a perfil con advertencia.

---

## 3. MÓDULOS IMPLEMENTADOS

### 3.1 Sistema de reportes
- Reportes de tareas en terreno: datos dinámicos, fotos, geolocalización (latitud, longitud, precisión).
- Reporte diario (admin): fecha, estadísticas, agrupación por sucursal.
- Reporte por sucursal: filtros fecha/sucursal, tabla (Día, Hora, Novedad, Acciones, Resultado, Fotografía, Usuario), estadísticas, exportación PDF.
- Reportes especiales: Incidentes, Denuncia, Detenido, Acción sospechosa; por sector/sucursal, imágenes, geolocalización.
- Novedades/informes: nivel de gravedad, evidencias.
- Acciones de servicio: inicio, rondas, constancias, concurrencia autoridades, entrega.

### 3.2 Sistema de rondas QR
- Puntos de ronda por sucursal con código único (`codigo` en `puntos_ronda`).
- Admin crea puntos, genera/descarga QR.
- Guardias escanean en ruta; se registra usuario, punto, fecha/hora.
- Reporte de escaneos para supervisión (filtros: fecha, sucursal, guardia).
- Tabla `ronda_escaneos` (o similar) relacionada con `puntos_ronda` y `usuarios`.

### 3.3 Control de acceso (ingresos)
- Registro de ingresos: tipo (peatonal/vehicular), RUT, nombre, patente (opcional), id_guardia, fecha_ingreso, fecha_salida, estado, alerta_blacklist.
- Blacklist: listado de personas no autorizadas; al registrar ingreso se verifica y marca `alerta_blacklist`.
- Personas: CRUD de personas (pre-registro).
- Entrada manual y escáner (QR cédula, OCR patente según implementación).
- Exportación CSV.

### 3.4 Geolocalización
- Reportes incluyen latitud, longitud, precisión (nullable).
- En vista de detalle: enlaces Google Maps / OpenStreetMap, mapa embebido.
- Requiere HTTPS y permisos del navegador.

### 3.5 Portal usuario (móvil)
- Novedades: Incidente (rojo), Observación (amarillo), Información (azul).
- Reportes: Ronda de seguridad, Reporte de turno, Actividad especial.
- Incidentes críticos: segundo paso con personas involucradas, testigos, acciones, notificación autoridades.
- Menú acordeón, formularios multipaso, carga de fotos.
- Historial de reportes, documentos personales (ver mis documentos).

### 3.6 Supervisión
- Aprobar/rechazar documentos personales.
- Gestión de novedades.
- Ver todos los reportes.

### 3.7 Gestión (solo Admin)
- Usuarios: CRUD.
- Dispositivos permitidos.
- Ubicaciones permitidas.
- Sectores por sucursal.
- Puntos de ronda (QR).

---

## 4. ESTRUCTURA DE BASE DE DATOS (relevante)

### Tablas principales
- `usuarios` (id_usuario, run, nombre_completo, rango, email, clave, rol_id, sucursal_id, browser_fingerprint, dispositivo_verificado, etc.)
- `sucursales` (nombre, empresa, codigo, direccion, comuna, ciudad, region, telefono, email, activa)
- `roles_usuario` (nombre, slug, descripcion)
- `sectores` (sucursal_id, nombre, etc.)
- `puntos_ronda` (sucursal_id, sector_id, nombre, codigo, orden, lat, lng, distancia_maxima_metros, activo)
- `ronda_escaneos` (usuario, punto, fecha/hora, etc.)
- `ingresos` (tipo, rut, nombre, patente, id_guardia, fecha_ingreso, fecha_salida, estado, alerta_blacklist)
- `personas` (datos de personas pre-registradas)
- `blacklists` (personas no autorizadas)
- `reportes`, `reportes_especiales`, `acciones`, `informes`, `documentos_personales`, `dias_trabajados`, etc.

---

## 5. LO QUE NO ESTÁ IMPLEMENTADO (o está incompleto)

### 5.1 Sistema de visitas completo
- **Estado actual:** existe la tarea "Control de Acceso" como registro manual dentro de reportes; no hay módulo dedicado de visitas.
- **Falta:** pre-registro de visitantes, invitaciones, listado de visitas programadas, check-in/check-out estructurado, flujo de aprobación previa, notificaciones al visitante, historial por visitante/empresa, integración con reporte sucursal.

### 5.2 Mejoras sugeridas (en documentación)
- Modo offline para reportes.
- Notificaciones push.
- Firma digital en reportes.
- Geofencing (validar que el guardia está en la sucursal correcta).
- Filtros avanzados en reportes (por tipo de tarea, usuario).
- Gráficos estadísticos.
- Exportación Excel además de PDF.
- Dashboard en tiempo real.
- Geolocalización en PDF de reporte sucursal.
- Mapa con todos los reportes del día.
- Validación por IMEI: actualmente desactivada.

### 5.3 Discrepancias en documentación
- `SISTEMA_PERFILES.md` habla de 5 niveles con slugs (ADMIN, SUPERVISOR, etc.).
- `PERFILES_USUARIOS.md` menciona campo numérico `perfil` (1–4). En el modelo actual se usa `rol_id` y tabla `roles_usuario` con slugs.
- Verificar si existe rol `GUARDIA` (usado en `esGuardiaControlAcceso()`) vs `USUARIO`.

---

## 6. RUTAS RELEVANTES

```
/                       → Redirige según rol
/login, /logout
/usuario                → Portal usuario
/usuario/acciones       → Acciones del usuario
/usuario/reportes       → Reportes especiales
/usuario/documentos     → Documentos personales
/usuario/ronda          → Rondas QR (instrucciones, escaneos del día)
/ronda/escanear/{codigo}→ Escaneo QR (guardia)
/ingresos               → Control de acceso
/blacklist
/personas
/supervisor             → Portal supervisor
/administrador          → Portal administrador
/admin/usuarios
/admin/dispositivos
/admin/ubicaciones
/admin/sectores
/admin/rondas           → Puntos de ronda
/admin/rondas-reporte   → Reporte escaneos QR
/admin/reporte-sucursal
/admin/reportes-diarios
/admin/documentos
/admin/novedades
/admin/reportes-especiales
/profile                → Perfil usuario (siempre accesible)
```

---

## 7. ARCHIVOS CLAVE

| Archivo | Función |
|---------|---------|
| `app/Models/User.php` | Perfil, permisos, relaciones |
| `app/Models/RolUsuario.php` | Roles |
| `app/Models/Sucursal.php` | Sucursales |
| `app/Models/PuntoRonda.php` | Puntos de ronda QR |
| `app/Models/RondaEscaneo.php` | Escaneos de ronda |
| `app/Models/Ingreso.php` | Control de accesos |
| `app/Models/Persona.php` | Personas |
| `app/Models/Blacklist.php` | Lista negra |
| `app/Http/Middleware/VerificarSucursal.php` | Validación sucursal |
| `app/Http/Controllers/Admin/RondaQrController.php` | Generación QR (actualmente modificado) |
| `app/Http/Controllers/IngresosController.php` | Control de accesos |
| `app/Http/Controllers/RondaEscaneoController.php` | Escaneo QR |
| `app/Rules/ChileRut.php` | Validación RUT chileno |
| `routes/web.php` | Todas las rutas |

---

## 8. USUARIOS DE PRUEBA

| Nombre | RUT | Rol | Sucursal | Contraseña |
|--------|-----|-----|----------|------------|
| Juan Pérez | 12345678-9 | administrador | Central | 123456 |
| María González | 98765432-1 | supervisor | Norte | 123456 |
| Carlos Rodríguez | 11223344-5 | supervisor-usuario | Sur | 123456 |
| Ana Martínez | 22334455-6 | usuario | Central | 123456 |
| Pedro López | 33445566-7 | usuario | Sin sucursal | 123456 |

---

## 9. INSTRUCCIÓN PARA LA IA (Perplexity u otra)

**Tarea que debes realizar:**

1. **Leer todo el documento anterior** y entender el estado actual del proyecto.
2. **Generar una lista de puntos/checklist** de requerimientos funcionales y técnicos, considerando:
   - Lo que ya está implementado.
   - Lo que falta o está incompleto.
   - Posibles inconsistencias o deuda técnica.
   - Mejoras sugeridas en la documentación.
3. **Para cada punto**, indicar claramente:
   - **Qué hay que hacer** (descripción concreta de la tarea).
   - **Dónde intervenir** (archivos, rutas, modelos, vistas).
   - **Prioridad sugerida** (alta/media/baja), si aplica.
4. **Formato de salida:** entregar los puntos numerados en un formato tipo checklist, con subtareas concretas cuando sea necesario.

Ejemplo de formato esperado:
```
Punto 1: [Título del requerimiento]
- Qué hacer: [descripción]
- Dónde: [archivos/rutas]
- Prioridad: [alta/media/baja]

Punto 2: ...
```

---

**Fin del documento.**
