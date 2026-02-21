# Reporte final: proyecto testeable end-to-end (seed y navegación)

**Fecha:** 2026-02-18  
**Objetivo:** Dejar el proyecto en estado testeable end-to-end tras `php artisan migrate:fresh --seed` y documentar usuarios demo, URLs y checklist de navegación.

---

## 1) Resultado de migrate:fresh --seed

- **Estado:** ✅ **Pasó correctamente**
- **Comando:** `php artisan migrate:fresh --seed`
- Todas las migraciones se ejecutaron sin error (incluidas las condicionadas a PostgreSQL).
- Todos los seeders listados abajo se ejecutaron en orden sin fallos.

---

## 2) Listado de seeders ejecutados

1. RolesUsuarioSeeder  
2. ModalidadesJerarquiaSeeder  
3. ModalidadRolesSeeder  
4. PermisosSeeder  
5. EmpresaSeeder  
6. SucursalSeeder  
7. SectorSeeder  
8. TareaSeeder  
9. GruposIncidentesSeeder  
10. UbicacionPermitidaSeeder  
11. UsuariosSeeder  
12. TareaNovedadesSeeder  
13. TareaIncidentesSeeder  
14. TareaSeguridadSeeder  
15. TareaDetalleSeeder  
16. FeriadoSeeder  
17. ConfiguracionSueldoSeeder  
18. DispositivoPermitidoSeeder  
19. ReunionesSeeder  
20. ControlAccesoSeeder  
21. DemoDatosOperacionalesSeeder  

---

## 3) Usuarios demo

Contraseña común: **Demo2026!Demo2026!**

| RUN         | Rol              | Descripción breve        |
|------------|------------------|---------------------------|
| 11111111-1 | admin            | Administrador global      |
| 22222222-2 | admin_contrato    | Admin de contrato         |
| 33333333-3 | supervisor       | Supervisor Empresa 1      |
| 44444444-4 | supervisor       | Supervisor Empresa 2      |
| 55555555-5 | usuario_supervisor | Jefe de turno Empresa 1 |
| 66666666-6 | usuario          | Usuario Instalación A     |
| 77777777-7 | usuario          | Usuario Instalación B     |
| 88888888-8 | usuario          | Usuario Instalación C     |
| 99999999-9 | usuario          | Usuario Instalación D     |
| 12121212-3 | guardia          | Guardia control de acceso |

Listado detallado y checklist de navegación: [CHECKLIST-NAVEGACION.md](CHECKLIST-NAVEGACION.md).

---

## 4) URLs principales

- **Login:** `/login`
- **Panel admin:** `/administrador`
- **Panel supervisor:** `/supervisor`
- **Panel usuario:** `/usuario`

Reset y comandos de verificación: [DEV-RESET.md](DEV-RESET.md).

---

## 5) Cambios y correcciones aplicados

| Tema | Cambio |
|------|--------|
| **Migraciones** | Documentado orden y dependencias en `docs/MIGRACIONES-ORDEN.md`. Soft delete (100004) antes de índices parciales (200000); migraciones pgsql-only verificadas. |
| **HasActivoScope** | Conflicto de propiedad `$activoColumn` entre trait y modelos (Empresa, Sucursal, Tarea, UbicacionPermitida). Sustituido por método `activoColumn()` en el trait y override en los modelos que usan columna `activa`. |
| **Seeders** | EmpresaSeeder creado (2 empresas con modalidad y modulos_activos). SucursalSeeder ajustado a empresa_id y 2 instalaciones por empresa (INST-A/B, INST-C/D). SectorSeeder idempotente por (sucursal_id, nombre). RolesUsuarioSeeder con slugs exactos en minúsculas (usuario, admin, guardia, etc.). TareaSeeder idempotente por nombre. UsuariosSeeder con dataset demo y contraseña Demo2026!Demo2026!. ControlAccesoSeeder usa guardia por rol y blacklists/idempotencia. DemoDatosOperacionalesSeeder para puntos ronda, escaneos, acciones, reportes especiales, reportes, informe y documentos pendientes. |
| **DatabaseSeeder** | Orden corregido: EmpresaSeeder antes de SucursalSeeder; SectorSeeder después de SucursalSeeder; GruposIncidentesSeeder antes de DemoDatosOperacionalesSeeder; DemoDatosOperacionalesSeeder al final. |
| **Modelos** | Sector: `empresa_id` añadido a fillable. Reporte: `sector_id` añadido a fillable. |
| **Documentación** | Añadidos DEV-RESET.md, CHECKLIST-NAVEGACION.md, MIGRACIONES-ORDEN.md y sección en README para reset y seed. |

---

## 6) Pendiente (opcional)

- **Política de contraseña:** Mínimo 10–12 caracteres y confirmación en cambio de contraseña y creación/edición de usuario (ver REPORTE-FINAL-SEGURIDAD-Y-CIERRE.md).
- **Uploads privados:** Disco private, nombres UUID, validación estricta, descarga por controlador con auth y auditoría (si aún no está cerrado).
- **Auditoría:** Ya documentada en REPORTE-FINAL-SEGURIDAD-Y-CIERRE.md (toggle_activo, approve/reject documentos, download PDF, login). Trigger PostgreSQL inmutable opcional.
