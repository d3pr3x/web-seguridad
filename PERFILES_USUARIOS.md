# Sistema de Perfiles de Usuario

## 📋 Definición de Perfiles

El sistema utiliza una columna numérica `perfil` en la tabla `users` para definir los niveles de acceso.

### Valores de Perfiles:

| Número | Perfil | Descripción | Permisos |
|--------|--------|-------------|----------|
| **1** | **Administrador** | Control total del sistema | - Gestión completa de usuarios<br>- Aprobación de documentos<br>- Acceso a todos los reportes<br>- Configuración del sistema |
| **2** | **Supervisor** | Supervisión y aprobaciones | - Aprobación de documentos<br>- Visualización de reportes<br>- Gestión de su sucursal |
| **3** | **Supervisor-Usuario** | Supervisor que también trabaja en campo | - Permisos de supervisor<br>- Puede registrar acciones y reportes<br>- Acceso al portal usuario |
| **4** | **Usuario** | Usuario regular del sistema | - Acceso al portal usuario<br>- Registro de novedades<br>- Registro de reportes<br>- Gestión de documentos personales |

## 🔧 Uso en el Código

### En el Modelo User:

```php
// Verificar si es administrador
$user->esAdministrador()  // Retorna true si perfil === 1

// Verificar si es supervisor
$user->esSupervisor()  // Retorna true si perfil === 2 o 3

// Obtener nombre del perfil
$user->nombre_perfil  // Retorna "Administrador", "Supervisor", etc.
```

### En Blade:

```php
@if($user->perfil === 1)
    // Es administrador
@elseif($user->perfil === 2)
    // Es supervisor
@elseif($user->perfil === 3)
    // Es supervisor-usuario
@else
    // Es usuario (perfil === 4)
@endif
```

### En la Base de Datos:

```sql
-- Crear administrador
UPDATE users SET perfil = 1 WHERE rut = '12345678-9';

-- Crear supervisor
UPDATE users SET perfil = 2 WHERE rut = '98765432-1';

-- Crear supervisor-usuario
UPDATE users SET perfil = 3 WHERE rut = '11223344-5';

-- Crear usuario regular
UPDATE users SET perfil = 4 WHERE rut = '22334455-6';
```

## 👥 Usuarios de Prueba

| Nombre | RUT | Email | Perfil | Número | Password |
|--------|-----|-------|--------|--------|----------|
| Roberto Silva | 12345678-9 | roberto.silva@empresa.com | Administrador | **1** | 123456 |
| María González | 98765432-1 | maria.gonzalez@empresa.com | Supervisor | **2** | 123456 |
| Carlos Rodríguez | 11223344-5 | carlos.rodriguez@empresa.com | Supervisor-Usuario | **3** | 123456 |
| Ana Martínez | 22334455-6 | ana.martinez@empresa.com | Usuario | **4** | 123456 |

## 🚀 Ventajas del Sistema Numérico

1. ✅ **Fácil de manejar desde la base de datos** - Solo cambias un número
2. ✅ **Consultas más rápidas** - Comparación de enteros es más eficiente
3. ✅ **Sin errores de tipeo** - No hay problemas con mayúsculas/minúsculas
4. ✅ **Escalable** - Fácil agregar nuevos perfiles (5, 6, etc.)
5. ✅ **Compatible con PostgreSQL** - No requiere ENUMs complejos

## 📝 Notas Importantes

- El perfil por defecto es **4** (Usuario)
- Los perfiles 2 y 3 tienen permisos de supervisión
- El perfil 3 además tiene acceso al portal usuario
- Todos los métodos helper en el modelo User ya están actualizados


