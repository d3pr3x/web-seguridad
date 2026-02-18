# Cómo ver la opción "Instalar app" (PWA)

## Requisito importante: HTTPS

**Chrome solo muestra "Instalar app" si el sitio se abre por HTTPS** (o en `localhost`).  
Si tu servidor está en HTTP (sin certificado), esa opción no aparecerá. En ese caso configura SSL en el servidor (Let's Encrypt, Cloudflare, etc.).

---

## Dónde aparece la opción

### En Chrome (escritorio)
- **Barra de direcciones**: a la derecha puede aparecer un icono de instalación (⊕ o un monitor con flecha). Haz clic ahí.
- **Menú** (⋮ tres puntos) → busca **"Instalar [nombre de la app]..."** o **"Crear acceso directo"**.

### En Chrome (Android / móvil)
- **Menú** (⋮ tres puntos) → **"Añadir a la pantalla de inicio"** o **"Instalar aplicación"** (el texto puede variar según idioma y versión de Chrome).

### Si no aparece
1. Comprueba que estés en **HTTPS** (o localhost).
2. Abre **DevTools** (F12) → pestaña **Application** → **Manifest**. Ahí verás si el manifest se carga y si hay errores (por ejemplo iconos rotos).
3. En **Application** → **Service Workers** comprueba que `sw.js` esté registrado.
4. A veces Chrome pide **un mínimo de uso**: haber estado unos segundos en la página y haber hecho al menos un clic. Navega un poco y vuelve a mirar el menú.

---

## Iconos

Los iconos 192×192 y 512×512 están en `public/icons/`. Si en el servidor no los tienes, genera los PNG desde la raíz del proyecto:

```bash
php scripts/generate-pwa-icons.php
```

(Requiere PHP con extensión GD.)

---

## Resumen

- **npm install** no es necesario para la PWA; los archivos están en `public/` y en las vistas.
- **HTTPS** es obligatorio para que Chrome ofrezca instalar.
- Iconos 192/512 ya están en el manifest; si faltan los PNG, ejecuta el script anterior.
- Una vez instalada la app, el permiso de cámara se suele recordar entre sesiones.
