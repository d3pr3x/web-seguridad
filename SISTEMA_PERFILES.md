# Sistema de Perfiles de Usuario

## Descripción General

El sistema ahora cuenta con 4 perfiles de usuario diferentes, cada uno con características y permisos específicos:

## Perfiles Disponibles

### 1. Usuario (rol: `usuario`)
- **Descripción**: Perfil básico para trabajadores de campo
- **Características**:
  - Identificado por su RUT (7 u 8 números + dígito verificador)
  - **DEBE** estar asignado a una sucursal para acceder al sistema
  - Sin sucursal asignada, solo puede acceder a su perfil
  - Acceso limitado a funcionalidades operativas

### 2. Supervisor-Usuario (rol: `supervisor-usuario`)
- **Descripción**: Perfil híbrido que combina funciones de supervisión y operativas
- **Características**:
  - Identificado por su RUT
  - **DEBE** estar asignado a una sucursal
  - Puede realizar funciones de usuario y supervisar
  - Acceso a reportes y funciones de supervisión

### 3. Supervisor (rol: `supervisor`)
- **Descripción**: Perfil enfocado en supervisión y control
- **Características**:
  - Identificado por su RUT
  - **DEBE** estar asignado a una sucursal
  - Enfocado en funciones de supervisión
  - Acceso a reportes y gestión de equipos

### 4. Administrador (rol: `administrador`)
- **Descripción**: Perfil con acceso completo al sistema
- **Características**:
  - Identificado por su RUT
  - **NO requiere** estar asignado a una sucursal
  - Acceso completo a todas las funcionalidades
  - Puede gestionar usuarios, sucursales y configuraciones

## Identificación por RUT

- Todos los usuarios se identifican con su RUT chileno
- Formato: `12345678-9` (7 u 8 dígitos + guión + dígito verificador)
- El RUT es único en el sistema
- Se usa para el login en lugar del email

## Estructura de Sucursales

Cada sucursal ahora cuenta con los siguientes campos:

| Campo | Tipo | Descripción | Requerido |
|-------|------|-------------|-----------|
| nombre | string | Nombre de la sucursal | Sí |
| empresa | string | Empresa a la que pertenece | No |
| codigo | string | Código único de la sucursal | Sí |
| direccion | string | Dirección física | Sí |
| comuna | string | Comuna donde se ubica | No |
| ciudad | string | Ciudad | Sí |
| region | string | Región | Sí |
| telefono | string | Teléfono de contacto | No |
| email | string | Email de contacto | No |
| activa | boolean | Estado de la sucursal | Sí |

## Validación de Sucursal

### Middleware `verificar.sucursal`

El sistema implementa un middleware que verifica:

1. **Para usuarios NO administradores**: 
   - Deben tener una sucursal asignada para acceder a funcionalidades del sistema
   - Sin sucursal, son redirigidos a su perfil con un mensaje de advertencia
   - Solo pueden acceder a las rutas de perfil (ver y editar perfil)

2. **Para administradores**:
   - Pueden acceder a todas las funcionalidades sin necesidad de sucursal
   - Tienen acceso completo al sistema

### Rutas Protegidas

Las siguientes rutas requieren verificación de sucursal:
- Dashboard
- Tareas
- Reportes
- Informes
- Días trabajados
- Administración (reportes, cálculos, etc.)

Las siguientes rutas NO requieren verificación de sucursal:
- Perfil de usuario
- Cambio de contraseña

## Login y Autenticación

### Proceso de Login

1. Usuario ingresa su RUT y contraseña
2. Sistema valida las credenciales
3. Si las credenciales son correctas:
   - **Usuario NO administrador SIN sucursal**: Redirigido a perfil con advertencia
   - **Usuario NO administrador CON sucursal**: Redirigido al dashboard
   - **Administrador**: Redirigido al dashboard (con o sin sucursal)

## Usuarios de Prueba

El sistema incluye los siguientes usuarios de prueba:

| Nombre | RUT | Rol | Sucursal | Contraseña |
|--------|-----|-----|----------|------------|
| Juan Pérez | 12345678-9 | administrador | Central | 123456 |
| María González | 98765432-1 | supervisor | Norte | 123456 |
| Carlos Rodríguez | 11223344-5 | supervisor-usuario | Sur | 123456 |
| Ana Martínez | 22334455-6 | usuario | Central | 123456 |
| Pedro López | 33445566-7 | usuario | Sin sucursal | 123456 |

> **Nota**: Pedro López no tiene sucursal asignada, por lo que al iniciar sesión será redirigido a su perfil con un mensaje indicando que debe contactar al administrador.

## Métodos Helper en el Modelo User

El modelo `User` incluye los siguientes métodos helper:

```php
// Verificar si tiene sucursal
$user->tieneSucursal(); // bool

// Verificar roles
$user->esAdministrador(); // bool
$user->esSupervisor(); // bool (incluye supervisor-usuario)
$user->esSupervisorUsuario(); // bool
$user->esUsuario(); // bool
```

## Base de Datos

### Migraciones Ejecutadas

1. `add_rol_to_users_table`: Agrega campo `rol` a la tabla users
2. `add_comuna_empresa_to_sucursales_table`: Agrega campos `comuna` y `empresa` a sucursales

### Seeders Actualizados

- `SucursalSeeder`: Actualizado para incluir empresa y comuna
- `UserSeeder`: Actualizado para incluir roles y un usuario sin sucursal

## Próximos Pasos Sugeridos

1. **Pantalla principal para usuarios**: Crear una vista específica para usuarios en lugar de usar el dashboard
2. **Organización por carpetas**: Organizar las vistas por perfiles (admin, supervisor, usuario)
3. **Permisos específicos**: Implementar control de acceso más granular según el rol
4. **Gestión de usuarios**: Crear interfaz para que administradores puedan asignar sucursales
5. **Dashboard personalizado**: Crear dashboards específicos para cada rol

## Estructura de Archivos Modificados

```
app/
├── Models/
│   ├── User.php (métodos helper agregados)
│   └── Sucursal.php (campos empresa y comuna)
├── Http/
│   ├── Middleware/
│   │   └── VerificarSucursal.php (nuevo)
│   └── Controllers/
│       └── Auth/
│           └── LoginController.php (validación de sucursal)
database/
├── migrations/
│   ├── 2025_10_08_210435_add_rol_to_users_table.php (nuevo)
│   └── 2025_10_08_210442_add_comuna_empresa_to_sucursales_table.php (nuevo)
└── seeders/
    ├── UserSeeder.php (actualizado)
    └── SucursalSeeder.php (actualizado)
bootstrap/
└── app.php (registro del middleware)
routes/
└── web.php (aplicación del middleware)
```

