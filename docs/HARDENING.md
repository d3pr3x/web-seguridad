# Hardening: política de contraseña y uploads

Resumen de las medidas de seguridad implementadas para contraseñas y archivos sensibles.

---

## 1) Política de contraseña

### Requisitos

- **Longitud mínima:** 12 caracteres.
- **Confirmación:** el campo de confirmación es obligatorio (debe coincidir con la contraseña).
- **No comprometida:** se usa la regla `Password::uncompromised()` (API Have I Been Pwned) para rechazar contraseñas que hayan aparecido en filtraciones conocidas.
- **Auditoría:** en cada cambio de contraseña se registra el evento `password_changed` en la tabla `auditorias` (tabla `usuarios`, registro_id = id del usuario). **No se guarda la contraseña** en ningún campo de auditoría.

### Dónde se aplica

| Contexto | FormRequest / validación | Auditoría |
|----------|---------------------------|-----------|
| Cambio de contraseña en perfil (admin) | `UpdatePasswordRequest` en `ProfileController::updatePassword` | `password_changed`, metadata `contexto: perfil` |
| Cambio de contraseña en portal usuario | Mismas reglas en `UsuarioPerfilController::updatePassword` (atributos `new_password` / `new_password_confirmation`) | `password_changed`, metadata `contexto: usuario_perfil` |
| Crear usuario (admin) | `AdminStoreUserRequest` en `Admin\UserController::store` | `password_changed`, metadata `contexto: admin_crear_usuario` |
| Editar usuario y cambiar contraseña (admin) | `AdminUpdateUserRequest` en `Admin\UserController::update` | `password_changed`, metadata `contexto: admin_editar_usuario` |

### Mensajes de error (español)

- Definidos en los FormRequests (`UpdatePasswordRequest`, `AdminStoreUserRequest`, `AdminUpdateUserRequest`) y en `lang/es/validation.php` para la regla `uncompromised` (“Esta contraseña ha aparecido en una filtración de datos…”).

### Nota sobre `uncompromised()`

La regla realiza una consulta a un servicio externo (Have I Been Pwned). Si el entorno no tiene salida a internet o el servicio no está disponible, la validación puede fallar por tiempo de espera. En ese caso se puede valorar desactivar temporalmente la regla en ese entorno o usar una lista local de contraseñas prohibidas.

---

## 2) Uploads privados (documentos e imágenes sensibles)

### Disco y servicio

- **Disco:** `config/filesystems.php` define el disco `private` con `root` = `storage_path('app/private')`. **No hay URL pública** ni enlace simbólico desde `public` a ese directorio; los archivos no son accesibles por URL directa.
- **Servicio:** `App\Services\SecureUploadService`:
  - Valida **tamaño** (límites en `config/uploads.php` y env: `UPLOAD_MAX_DOCUMENT_KB`, `UPLOAD_MAX_IMAGE_KB`; por defecto 10 MB documentos, 5 MB imágenes).
  - Valida **extensión** y **mimetype** de forma estricta (listas en `config/uploads.php`).
  - Genera **nombre de archivo UUID** (no se usa el nombre original del cliente).
  - Guarda en disco **private** y retorna solo el **path interno** (relativo al disco), nunca una URL pública.

### Tipos de archivo permitidos

- **Documentos (para uso del servicio):** pdf, jpg, jpeg, png (y MIME correspondientes).
- **Imágenes (documentos personales frente/reverso):** jpg, jpeg, png, webp (5 MB por defecto).

### Dónde se usa

- **Subida:** `UsuarioDocumentoController::store` sube las imágenes frente/reverso con `SecureUploadService::storeImage()` y guarda el path en `documentos.imagen_frente` / `imagen_reverso`.
- **Descarga:** no se sirve ningún archivo sensible desde `public`. La descarga se hace siempre por controlador.

### Endpoints de descarga protegidos

| Ruta | Descripción | Auth | Autorización | Throttle | Auditoría |
|------|-------------|------|--------------|----------|-----------|
| GET `/archivos-privados/documentos/{documento}/{lado}` | Imagen frente o reverso de un documento personal | Sí | Dueño del documento o admin/supervisor | `throttle:sensitive` (30/min) | `download_file`, tabla `documentos` |
| GET `/informes/{id}/pdf` | Descarga PDF del informe | Sí | Informe del usuario (o permisos de supervisión) | `throttle:sensitive` | `download_file`, tabla `informes` |
| GET `/informes/{id}/ver-pdf` | Ver PDF en navegador | Sí | Igual que arriba | `throttle:sensitive` | `download_file`, tabla `informes` |

### Compatibilidad con datos antiguos

- Los paths ya guardados en BD pueden apuntar a archivos que estaban en disco `public`. El controlador `ArchivoPrivadoController::documentoArchivo` intenta primero en disco `private` y, si no existe el archivo, en disco `public`, para no romper documentos subidos antes de esta política.

### Confirmación

- **Ningún archivo sensible** (documentos personales, imágenes de documentos) es accesible por URL directa (p. ej. `/storage/...` para esos archivos). Solo se sirven a través de los endpoints anteriores, con autenticación, autorización, límite de tasa y auditoría.
