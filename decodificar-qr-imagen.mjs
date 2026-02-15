/**
 * Decodifica un código QR desde un archivo de imagen.
 * Uso: node decodificar-qr-imagen.mjs <ruta-imagen>
 */
import { createRequire } from 'module';

const require = createRequire(import.meta.url);

async function decodificarQR(rutaImagen) {
  const { Jimp } = await import('jimp');
  const { default: jsQR } = await import('jsqr');

  let image = await Jimp.read(rutaImagen);
  const w = image.bitmap.width;
  const h = image.bitmap.height;

  function tryDecode(img) {
    const width = img.bitmap.width;
    const height = img.bitmap.height;
    const data = new Uint8ClampedArray(img.bitmap.data);
    return jsQR(data, width, height, { inversionAttempts: 'attemptBoth' });
  }

  let code = tryDecode(image);
  if (code) return code.data;

  // Escalar 2x
  image = image.scale(2, Jimp.RESIZE_LANCZOS3);
  code = tryDecode(image);
  if (code) return code.data;

  // Escalar 3x (desde original)
  image = await Jimp.read(rutaImagen);
  image = image.scale(3, Jimp.RESIZE_LANCZOS3);
  code = tryDecode(image);
  if (code) return code.data;

  // Normalizar contraste y reintentar
  image = await Jimp.read(rutaImagen);
  image.normalize();
  code = tryDecode(image);
  if (code) return code.data;

  image = await Jimp.read(rutaImagen);
  image.grayscale().contrast(0.3);
  code = tryDecode(image);
  if (code) return code.data;

  return null;
}

const ruta = process.argv[2];
if (!ruta) {
  console.error('Uso: node decodificar-qr-imagen.mjs <ruta-imagen>');
  process.exit(1);
}

decodificarQR(ruta)
  .then((texto) => {
    if (texto) {
      console.log('Contenido del QR:\n');
      console.log(texto);
      if (/^https?:\/\//i.test(texto)) console.log('\n(Es un enlace)');
    } else {
      console.log('No se detectó ningún código QR en la imagen.');
      process.exit(1);
    }
  })
  .catch((err) => {
    console.error('Error:', err.message);
    process.exit(1);
  });
