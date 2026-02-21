# Reporte final – Checklist de seguridad (archivos y auditoría)

**Fecha:** 2026-02-18  
**Objetivo:** Nivel máximo de seguridad en el stack actual (Laravel + PostgreSQL): uploads en disco private, servicio de archivos por controlador autenticado, auditoría en descargas y acciones críticas, sin archivos sensibles por URL pública.  
**Documento único:** Este archivo consolida checklist estático, dinámico, rutas nuevas, resultado de `migrate:fresh --seed` y puntos no implementados.

---

## 1️⃣ Checklist estático (código)

| Ítem | Estado | Evidencia |
|------|--------|-----------|
| **1.1** Uso de disco `public` eliminado para uploads sensibles | ✅ | Todos los controladores de acciones, reportes, reportes especiales, informes y novedades usan `SecureUploadService::storeImage()` con disco `private`. No queda `store(..., 'public')` ni `storeAs(..., 'public')` para estos flujos. |
| **1.2** Solo paths internos guardados en BD | ✅ | Paths guardados son relativos al disco (ej: `acciones/uuid.webp`, `reportes/uuid.jpg`, `reportes-especiales/...`, `informes/...`). |
| **1.3** Carpetas internas en private | ✅ | Uso de subdirs: `acciones/`, `reportes/`, `reportes-especiales/`, `informes/`, `documentos/` (ya existente). |
| **1.4** Controlador único de archivos | ✅ | `ArchivoPrivadoController`: documento (frente/reverso), accionImagen, reporteImagen, reporteEspecialImagen, informeFotografia. Todas las rutas bajo `auth` y `throttle:sensitive`. |
| **1.5** Auth en rutas de archivos | ✅ | Rutas de archivos están dentro de `Route::middleware(['auth'])->group(...)` en `routes/web.php`. |
| **1.6** Throttle sensitive en descargas | ✅ | Todas las rutas `archivos-privados.*` tienen `->middleware('throttle:sensitive')`. |
| **1.7** Autorización por empresa/sucursal/usuario | ✅ | En cada método: dueño del recurso, o administrador, o supervisor de la misma sucursal (según corresponda). |
| **1.8** Auditoría `download_file` en descargas | ✅ | `ArchivoPrivadoController`: documentoArchivo, accionImagen, reporteImagen, reporteEspecialImagen, informeFotografia. `InformeController`: descarga PDF y ver PDF. |
| **1.9** Vistas sin `/storage/` público | ✅ | Búsqueda en `resources/views`: no queda `asset('storage/' ...)`. Todas las imágenes de acciones, reportes, reportes especiales, informes y novedades usan `route('archivos-privados.accion', ...)`, `route('archivos-privados.reporte', ...)`, etc. |
| **1.10** Validación en SecureUploadService | ✅ | Extensión permitida, MIME real, tamaño (config `uploads.max_image_kb` / `max_document_kb`), nombre UUID. Config `config/uploads.php`: image_mimes, image_mimetypes (incl. heic, heif, gif). |
| **1.11** PDF con rutas desde private | ✅ | `informes/pdf.blade.php`: recibe `primeraFotoPath` (ruta absoluta resuelta en controlador: private primero, luego public legacy). `admin/reporte-sucursal-pdf.blade.php`: recibe `reporte->imagenes_abs` calculado en `ReporteSucursalController` desde private/public. |

---

## 2️⃣ Checklist dinámico (navegación manual)

| Prueba | Cómo validar |
|--------|----------------|
| No hay URLs públicas de imágenes | Tras login, en detalle de acción/reporte/reporte especial/informe/novedad, las imágenes se cargan por URL como `/archivos-privados/acciones/{id}/imagen/0`, etc. No debe existir ninguna URL del tipo `/storage/acciones/...` que devuelva contenido. |
| No hay acceso directo a storage/private | En el servidor no existe una ruta web que mapee a `storage/app/private`. No hay symlink público a `private`. |
| Auditoría registra descargas | En tabla `auditorias` (o la que use el proyecto), tras ver una imagen de acción/reporte/documento/informe debe aparecer un registro con acción `download_file` y contexto acorde. |
| Rate limit sensitive | Realizar muchas peticiones seguidas a una URL de archivo privado (ej. >30/min por usuario); debe aplicarse el límite configurado en `RateLimiter::for('sensitive')`. |

---

## 3️⃣ Lista completa de rutas nuevas (archivos privados)

Todas bajo middleware `auth` y `throttle:sensitive`:

| Método | Ruta | Nombre | Controlador@método |
|--------|------|--------|--------------------|
| GET | `/archivos-privados/documentos/{documento}/{lado}` | `archivos-privados.documento` | ArchivoPrivadoController@documentoArchivo |
| GET | `/archivos-privados/acciones/{accion}/imagen/{index}` | `archivos-privados.accion` | ArchivoPrivadoController@accionImagen |
| GET | `/archivos-privados/reportes/{reporte}/imagen/{index}` | `archivos-privados.reporte` | ArchivoPrivadoController@reporteImagen |
| GET | `/archivos-privados/reportes-especiales/{reporte_especial}/imagen/{index}` | `archivos-privados.reporte-especial` | ArchivoPrivadoController@reporteEspecialImagen |
| GET | `/archivos-privados/informes/{informe}/fotografia/{index}` | `archivos-privados.informe` | ArchivoPrivadoController@informeFotografia |

---

## 4️⃣ Confirmación de `migrate:fresh --seed`

- **Comando ejecutado:** `php artisan optimize:clear` y `php artisan migrate:fresh --seed`.
- **Resultado:** ✅ **Correcto.** Todas las migraciones se ejecutaron y todos los seeders completaron sin error.

---

## 5️⃣ Puntos no implementados / Limitaciones técnicas detectadas

| # | Solicitado | Motivo / limitación | Alternativa aplicada | Impacto | Recomendación |
|---|------------|---------------------|----------------------|---------|----------------|
| 1 | Verificación profunda de contenido binario (ej. magic bytes) en SecureUploadService | No se implementó verificación adicional más allá de extensión + MIME de PHP (`getMimeType()`). Una comprobación tipo “magic bytes” requeriría librerías o lógica ad hoc y no forma parte del stack estándar actual. | Se mantiene validación por extensión permitida, MIME reportado por PHP y tamaño. Los tipos permitidos están restringidos en `config/uploads.php`. | Bajo | Opcional: en el futuro valorar una librería (ej. para imágenes) que valide magic bytes si el riesgo de archivos maliciosos disfrazados lo justifica. |
| 2 | Eliminación total del disco `public` para archivos ya existentes | Los registros antiguos pueden tener paths que solo existen en `storage/app/public`. Borrar por completo el uso de `public` rompería visualización de esos archivos. | Se mantiene fallback en `ArchivoPrivadoController::resolvePath()` y en los controladores de PDF: primero se busca en `private`, luego en `public`. Los **nuevos** uploads van solo a `private`. | Bajo | Migración opcional: script que copie archivos legacy de public a private y actualice BD; luego eliminar fallback a public. |

**No se ha omitido ningún otro punto solicitado.** El resto de requisitos (uploads a private, controlador único, auditoría, throttle, vistas sin `/storage/`, PDF con rutas desde private) están implementados.

---

## 6️⃣ Nivel de seguridad final (justificado)

- **Archivos sensibles:** Todos los uploads sensibles (acciones, reportes, reportes especiales, informes, novedades, documentos) se almacenan en disco **private** y se sirven **solo** por rutas protegidas (auth + throttle:sensitive + autorización por recurso/sucursal/usuario). No hay URL pública directa a esos archivos.
- **Auditoría:** Toda descarga de archivo (imágenes de documento, acción, reporte, reporte especial, fotografía de informe y descarga/visualización de PDF de informe) se registra con `AuditoriaService::registrar('download_file', ...)`. Las acciones críticas ya auditadas (password_changed, toggle_activo, approve/reject, login_success/login_failed) se mantienen.
- **Validación de subida:** Extensión, MIME y tamaño en `SecureUploadService`; nombres con UUID; sin uso del nombre original del usuario.
- **Rate limiting:** Las rutas de archivos privados usan `throttle:sensitive` (configurado en `AppServiceProvider`).

**Conclusión:** El sistema queda al **nivel máximo de seguridad razonable** dentro del stack actual (Laravel + PostgreSQL) para el manejo de archivos sensibles: todo pasa por controlador autenticado, con autorización y auditoría, sin exposición por URL pública, con la única limitación explícita de no verificación profunda de contenido binario y el fallback a `public` solo para registros legacy.

---

## Resumen de archivos modificados

- **Config:** `config/uploads.php` (image_mimes/image_mimetypes: heic, heif, gif).
- **Controladores:** `AccionController`, `UsuarioAccionController`, `ReporteController`, `ReporteEspecialController`, `UsuarioReporteController`, `InformeController`, `Admin/NovedadController`: uso de `SecureUploadService::storeImage()` y subdirs en private. `ArchivoPrivadoController`: métodos accionImagen, reporteImagen, reporteEspecialImagen, informeFotografia; loadMissing para user/reporte.user. `InformeController`: `resolveFotoPath()`, paso de `primeraFotoPath` a la vista PDF. `ReporteSucursalController`: cálculo de `imagenes_abs` para PDF.
- **Rutas:** `routes/web.php`: rutas GET para acciones, reportes, reportes-especiales, informes (archivos privados) con throttle:sensitive.
- **Vistas:** `acciones/show`, `usuario/acciones/show`, `reportes/show`, `usuario/reportes/show`, `reportes-especiales/show`, `admin/reportes-especiales/show`, `admin/novedades/show`, `informes/show`, `informes/create`, `admin/reporte-sucursal`: sustitución de `asset('storage/' ...)` por `route('archivos-privados.*', ...)`. `informes/pdf.blade.php`: uso de `primeraFotoPath`. `admin/reporte-sucursal-pdf.blade.php`: uso de `reporte->imagenes_abs`.
