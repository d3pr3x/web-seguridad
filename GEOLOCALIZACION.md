# 📍 Geolocalización GPS en Reportes

## Descripción
Sistema de captura automática de ubicación GPS para todos los reportes enviados por los usuarios.

## Características Implementadas

### 1. **Captura Automática de Ubicación**
- Se solicita permiso de ubicación al cargar el formulario de reporte
- Captura GPS con alta precisión (`enableHighAccuracy: true`)
- Timeout de 10 segundos
- No usa caché de ubicación antigua

### 2. **Datos Capturados**
- **Latitud**: Coordenada geográfica (rango: -90 a 90)
- **Longitud**: Coordenada geográfica (rango: -180 a 180)  
- **Precisión**: En metros, indica qué tan precisa es la ubicación

### 3. **Feedback Visual**
El usuario ve mensajes en tiempo real:
- ⏳ "Obteniendo ubicación GPS..." (azul)
- ✅ "Ubicación GPS capturada correctamente" (verde)
- ⚠️ "Permiso denegado / Error" (amarillo)

### 4. **Manejo de Errores**
El sistema maneja elegantemente:
- Permiso denegado por el usuario
- GPS no disponible
- Timeout de conexión
- Navegador sin soporte de geolocalización

**Importante:** Si falla la captura, el reporte se envía de todas formas sin ubicación.

### 5. **Visualización de Ubicación**

#### Vista de Detalle de Reporte:
- Muestra latitud, longitud y precisión
- Botones para abrir en:
  - 🗺️ Google Maps
  - 🗺️ OpenStreetMap
- Mapa embebido interactivo con marcador

#### Vista de Lista:
- Indicador visual verde si tiene GPS
- Enlace directo a Google Maps (solo admin)

## Estructura de Base de Datos

### Tabla: `reportes`
```
- latitud (decimal 10,8, nullable)
- longitud (decimal 11,8, nullable)
- precision (decimal 8,2, nullable)
```

## Archivos Modificados

1. **Migración**: `database/migrations/2025_10_07_230406_add_ubicacion_to_reportes_table.php`
2. **Modelo**: `app/Models/Reporte.php`
3. **Controlador**: `app/Http/Controllers/ReporteController.php`
4. **Vista Formulario**: `resources/views/tareas/formulario.blade.php`
5. **Vista Detalle**: `resources/views/reportes/show.blade.php`
6. **Vista Lista Usuario**: `resources/views/reportes/index.blade.php`
7. **Vista Admin**: `resources/views/admin/reportes-diarios.blade.php`

## Requisitos del Navegador

### ✅ Compatible:
- Chrome/Brave para Android e iOS
- Safari para iOS
- Firefox Mobile
- Edge Mobile

### ⚠️ Requisitos:
- **HTTPS obligatorio** (ngrok ya lo provee)
- Permisos de ubicación habilitados en el dispositivo
- GPS activado en el celular

## Privacidad y Seguridad

- La ubicación solo se solicita cuando el usuario está por enviar un reporte
- El usuario puede denegar el permiso
- Los reportes se envían igualmente sin GPS si el usuario lo rechaza
- Solo administradores pueden ver ubicaciones de otros usuarios
- Los datos GPS son opcionales (nullable en DB)

## Casos de Uso

1. **Reportar incidentes**: Saber dónde exactamente ocurrió un suceso
2. **Verificación de asistencia**: Confirmar que el empleado está en el lugar correcto
3. **Auditoría**: Validar ubicaciones de tareas realizadas
4. **Análisis geográfico**: Identificar patrones de incidentes por zona

## Próximas Mejoras Sugeridas

- [ ] Agregar filtro por ubicación/zona en reportes admin
- [ ] Mapa con todos los reportes del día
- [ ] Geocodificación inversa (convertir coordenadas a dirección)
- [ ] Radio de geofencing para validar que está en la sucursal correcta
- [ ] Historial de ubicaciones por usuario

---

**Desarrollado:** Octubre 2025
**Estado:** ✅ Funcional y en producción


