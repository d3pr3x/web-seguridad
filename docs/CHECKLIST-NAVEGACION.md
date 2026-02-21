# Checklist de navegación post-seed

Después de ejecutar `php artisan migrate:fresh --seed`, usar este checklist para validar módulos, roles, multiempresa, auditoría y seguridad.

**Contraseña común para todos los usuarios demo:** `Demo2026!Demo2026!`

---

## Usuarios demo (RUN → contraseña → rol)

| RUN         | Contraseña       | Rol              | Uso recomendado                          |
|------------|------------------|------------------|------------------------------------------|
| 11111111-1 | Demo2026!Demo2026! | admin            | Admin global                             |
| 22222222-2 | Demo2026!Demo2026! | admin_contrato   | Admin de contrato                        |
| 33333333-3 | Demo2026!Demo2026! | supervisor       | Supervisor Empresa 1 (Instalación A)     |
| 44444444-4 | Demo2026!Demo2026! | supervisor       | Supervisor Empresa 2 (Instalación C)     |
| 55555555-5 | Demo2026!Demo2026! | usuario_supervisor | Jefe de turno Empresa 1                |
| 66666666-6 | Demo2026!Demo2026! | usuario          | Usuario Instalación A                     |
| 77777777-7 | Demo2026!Demo2026! | usuario          | Usuario Instalación B                    |
| 88888888-8 | Demo2026!Demo2026! | usuario          | Usuario Instalación C                    |
| 99999999-9 | Demo2026!Demo2026! | usuario          | Usuario Instalación D                    |
| 12121212-3 | Demo2026!Demo2026! | guardia          | Control de acceso                        |

---

## URLs principales

- **Login:** `/login`
- **Admin:** `/administrador` (tras login como admin o admin_contrato)
- **Supervisor:** `/supervisor` (tras login como supervisor / usuario_supervisor / admin)
- **Usuario:** `/usuario` (tras login como usuario / usuario_supervisor / supervisor)

---

## Checklist por rol

### Admin

1. **Login** → Entrar con `11111111-1` / `Demo2026!Demo2026!`.
2. **Gestión → Clientes**  
   - Crear/editar empresa, ver modalidad.  
   - Comprobar toggles inactivos/borrados (soft delete).
3. **Empresa → Instalaciones**  
   - Crear/editar instalación (sucursal) por empresa.
4. **Sectores**  
   - Seleccionar empresa → instalación → CRUD sectores.  
   - Toggle activo y comprobar que se audita.
5. **Auditorías**  
   - Filtrar por tabla / usuario / acción.
6. **Módulos por empresa**  
   - Cambiar de empresa y comprobar que el menú refleja `modulos_activos` (Empresa 1 tiene más módulos que Empresa 2).

### Supervisor

1. **Login** → Entrar con `33333333-3` o `44444444-4`.
2. **Supervisor**  
   - Aprobar/rechazar documentos (comprobar que se audita).  
   - Ver reportes por empresa/instalación.

### Usuario

1. **Login** → Entrar con `66666666-6` (o otro usuario de instalación).
2. **Menú**  
   - Ver acordeón según rol compuesto (usuario / usuario_supervisor).
3. **Rondas QR**  
   - Scan y throttle según configuración.
4. **Control de acceso**  
   - Ingresos y blacklist (si el rol tiene acceso).
5. **Reportes / Acciones**  
   - Crear reporte o acción y verificar que el sector se asocia correctamente.

### Guardia (control de acceso)

1. **Login** → Entrar con `12121212-3`.
2. **Control de acceso**  
   - Registrar ingresos y comprobar blacklist.

---

## Empresas e instalaciones seed

- **Empresa 1 (EMP1):** modalidad `con_jefe_turno`. Módulos: control_acceso, rondas_qr, documentos_guardias, reportes_diarios, calculo_sueldos.  
  - Instalación A (INST-A), Instalación B (INST-B).
- **Empresa 2 (EMP2):** modalidad `directa`. Módulos: control_acceso, rondas_qr.  
  - Instalación C (INST-C), Instalación D (INST-D).

Cada instalación tiene 5 sectores (Acceso principal, Bodega, Oficinas, Perímetro exterior, Estacionamiento).
