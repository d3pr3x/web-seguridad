# UI Rework Fase 1 — Menú ↔ Dashboard consistente (todos los roles)

**Fecha:** 2026-02-21  
**Objetivo:** Interfaz más “pro” y consistente: lo que aparece en el menú lateral debe reflejarse en la vista de inicio (dashboard) de cada rol. Portal usuario: bloque “Reportes” reemplazado por “Incidentes” con solo Novedades y Reportes.

---

## 1. Archivos modificados

| Archivo | Cambios |
|---------|--------|
| `app/Services/MenuBuilder.php` | Menú canónico usuario: orden Inicio → Control de acceso → Rondas QR → **Incidentes** (collapse: Novedades, Reportes) → Mi perfil → Mis documentos. Eliminado ítem “Mis reportes”; añadido “Incidentes” con hijos Novedades (`usuario.acciones.index`) y Reportes (`usuario.reportes.index`). Reordenados `suborder` para usuario. |
| `resources/views/components/usuario/sidebar.blade.php` | Añadido ítem **Instalar aplicación** antes de Cerrar sesión (mismo comportamiento que en menú móvil). |
| `resources/views/usuario/index.blade.php` | Dashboard usuario: (1) Cards principales alineadas al menú: Control de acceso, Rondas QR, Incidentes (solo 2 cards: Novedades, Reportes). (2) Bloque “Mi actividad” solo como historial: “Historial de reportes” y “Registro de puntos escaneados” (sin “Rondas QR” como atajo principal, sin Mi perfil duplicado). Grid 1/2/3 columnas (responsive). Respeto de módulos y permisos. |
| `resources/views/supervisor/index.blade.php` | Bloque “Reportes” (4 cards) reemplazado por **Incidentes** (Novedades + Reportes) en Usuario-Supervisor y en Supervisor-Usuario. Rutas: `usuario.acciones.index`, `usuario.reportes.index`. Reportes y estadísticas envuelto en comprobación de permisos. Grid consistente. |
| `resources/views/administrador/index.blade.php` | (1) Nueva sección **Reportes y estadísticas** (arriba) con cards: Todos los reportes, Reporte escaneos QR (si `rondas_qr`), Reporte por sucursal, Reportes diarios (si módulo + permiso). (2) Supervisión y Gestión con comprobación `puedeVerSupervision()` / `puedeVerGestion()`. (3) Gestión renombrada de “Administración” a “Gestión”; añadidos Clientes, Auditorías, Puntos de ronda (QR); Usuarios movido a Supervisión. Cards solo si el usuario tiene el permiso correspondiente. |

**No modificados (solo UI):** rutas, controladores, modelos. Labels de menú centralizados en `MenuBuilder`; las vistas de dashboard usan los mismos términos (Incidentes, Novedades, Reportes).

---

## 2. Descripción lógica por rol

### 2.1 Usuario / Guardia

**Menú (orden final):**
1. Inicio  
2. Control de acceso (si `module:control_acceso` + `puedeVerControlAcceso`)  
3. Rondas QR (si `module:rondas_qr` + `puedeVerRondasQR`)  
4. **Incidentes** (collapse)  
   - 4.1 Novedades → `usuario.acciones.index`  
   - 4.2 Reportes → `usuario.reportes.index`  
5. Mi perfil  
6. Mis documentos (si `module:documentos_guardias`)  
7. Instalar aplicación  
8. Cerrar sesión  

**Dashboard:**  
- Cards principales: Control de acceso (si aplica), Rondas QR (si aplica), bloque **Incidentes** con 2 cards (Novedades, Reportes).  
- Sección **Mi actividad**: solo historial — “Historial de reportes” y “Registro de puntos escaneados” (si aplican módulo/permiso). No se muestra “Rondas QR” como atajo principal ni “Mi perfil” duplicado.

### 2.2 Supervisor

**Menú:**  
Depende de rol (Usuario-Supervisor vs Supervisor-Usuario). Incluye Inicio, ítems de usuario si aplica (Control de acceso, Rondas QR, Incidentes, Mi perfil, Mis documentos), separador, Reportes y estadísticas, Supervisión, y en acordeón el bloque del rol secundario. Final: Instalar aplicación, Cerrar sesión.

**Dashboard:**  
- **Usuario-Supervisor:** Incidentes (Novedades + Reportes), Supervisión, Reportes y estadísticas (si permisos).  
- **Supervisor puro / Supervisor-Usuario:** Supervisión, Reportes y estadísticas; si es Supervisor-Usuario además bloque Incidentes (Novedades + Reportes).

### 2.3 Admin

**Menú:**  
Inicio, (separador), Reportes y estadísticas, Supervisión, Gestión, Instalar aplicación, Cerrar sesión.

**Dashboard:**  
1. **Reportes y estadísticas** (si `puedeVerReporteSucursal` o `puedeVerReportesEstadisticasCompletos`): Todos los reportes, Reporte escaneos QR (si `rondas_qr`), Reporte por sucursal, Reportes diarios (si módulo + permiso).  
2. **Supervisión** (si `puedeVerSupervision`): Usuarios (solo admin), Aprobar Documentos (si módulo), Novedades, Grupos de incidentes (solo admin), Todos los Reportes.  
3. **Gestión** (si `puedeVerGestion`): Clientes, Dispositivos, Ubicaciones, Sectores, Puntos de ronda (QR) (si `rondas_qr`), Auditorías.

---

## 3. Lista final del menú por rol (orden)

### Usuario / Guardia
1. Inicio  
2. Control de acceso  
3. Rondas QR  
4. Incidentes → Novedades, Reportes  
5. Mi perfil  
6. Mis documentos  
7. Instalar aplicación  
8. Cerrar sesión  

*(Ítems 2, 3, 6 dependen de módulo/permiso.)*

### Supervisor (principal)
- Inicio  
- (Si tiene rol usuario) Control de acceso, Rondas QR, Incidentes, Mi perfil, Mis documentos  
- ———  
- Reportes y estadísticas  
- Supervisión  
- (Si tiene acordeón Usuario) mismo bloque usuario  
- Instalar aplicación  
- Cerrar sesión  

### Admin
1. Inicio  
2. ———  
3. Reportes y estadísticas  
4. Supervisión  
5. Gestión  
6. Instalar aplicación  
7. Cerrar sesión  

---

## 4. Rutas usadas por cada card (dashboard)

### Usuario
| Card / Bloque        | Ruta                    |
|----------------------|-------------------------|
| Control de acceso    | `ingresos.index`        |
| Rondas QR            | `usuario.ronda.index`   |
| Incidentes → Novedades | `usuario.acciones.index` |
| Incidentes → Reportes  | `usuario.reportes.index`  |
| Mi actividad → Historial de reportes | `usuario.reportes.index` |
| Mi actividad → Registro de puntos escaneados | `usuario.ronda.index` |

### Supervisor (bloque usuario)
| Card   | Ruta                    |
|--------|-------------------------|
| Novedades | `usuario.acciones.index` |
| Reportes  | `usuario.reportes.index`  |

(Otras cards del dashboard supervisor usan `admin.novedades.index`, `reportes-especiales.index`, `admin.reportes-especiales.index`, `admin.rondas.reporte`, etc.)

### Admin
| Sección / Card        | Ruta |
|------------------------|-----|
| Reportes y estadísticas → Todos los reportes | `admin.reportes-especiales.index` |
| Reportes y estadísticas → Reporte escaneos QR | `admin.rondas.reporte` |
| Reportes y estadísticas → Reporte por sucursal | `admin.reporte-sucursal` |
| Reportes y estadísticas → Reportes diarios | `admin.reportes-diarios` |
| Supervisión → Usuarios | `admin.usuarios.index` |
| Supervisión → Aprobar Documentos | `admin.documentos.index` |
| Supervisión → Novedades | `admin.novedades.index` |
| Supervisión → Grupos de incidentes | `admin.grupos-incidentes.index` |
| Supervisión → Todos los Reportes | `admin.reportes-especiales.index` |
| Gestión → Clientes | `admin.clientes.index` |
| Gestión → Dispositivos | `admin.dispositivos.index` |
| Gestión → Ubicaciones | `admin.ubicaciones.index` |
| Gestión → Sectores | `admin.sectores.index` |
| Gestión → Puntos de ronda (QR) | `admin.rondas.index` |
| Gestión → Auditorías | `admin.auditorias.index` |

---

## 5. Checklist de validación manual (mínimo)

- [ ] **Usuario/Guardia:** Menú en orden: Inicio → Control de acceso → Rondas QR → Incidentes (Novedades, Reportes) → Mi perfil → (Mis documentos) → Instalar aplicación → Cerrar sesión.  
- [ ] **Usuario/Guardia:** Dashboard con cards: Control de acceso, Rondas QR, Incidentes (solo Novedades y Reportes). No aparecen las 4 cards antiguas (Incidentes, Denuncia, Detenido, Acción sospechosa).  
- [ ] **Supervisor:** Menú coherente con su rol; dashboard con cards coherentes (Incidentes = 2 opciones cuando aplica).  
- [ ] **Admin:** Menú y dashboard coherentes (Reportes y estadísticas, Supervisión, Gestión).  
- [ ] **Responsivo:** Móvil → cards en 1 columna; desktop → 2–3 columnas según Bootstrap/Tailwind.  
- [ ] **Módulos:** Si un módulo está desactivado en `config/modules.php` (o por empresa), no aparece en menú ni en dashboard.

---

## 6. Notas

- **Novedades** = listado/crear acciones del usuario → `usuario.acciones.index` (desde ahí se puede ir a crear).  
- **Reportes** = listado/crear reportes especiales del usuario → `usuario.reportes.index`.  
- Las 4 categorías (incidentes, denuncia, detenido, accion_sospechosa) siguen existiendo en el formulario de creación de reportes (`usuario.reportes.create` con `tipo`); solo se dejaron de mostrar como 4 cards en el dashboard.  
- **Instalar aplicación:** mismo comportamiento en sidebar desktop y en menú móvil (botón que llama a `triggerPwaInstall` si existe).
