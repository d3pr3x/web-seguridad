/**
 * Prueba decodificar QR en la imagen capturada (cedula-captura-test.png).
 * Ejecutar: node test-cedula-captura.mjs
 */
import { fileURLToPath } from 'url';
import path from 'path';
import fs from 'fs';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const imgPath = path.join(__dirname, 'public', 'cedula-captura-test.png');

async function main() {
  if (!fs.existsSync(imgPath)) {
    console.error('No existe public/cedula-captura-test.png');
    process.exit(1);
  }
  const { Jimp } = await import('jimp');
  const { default: jsQR } = await import('jsqr');

  function decode(image) {
    const w = image.bitmap.width;
    const h = image.bitmap.height;
    const data = new Uint8ClampedArray(image.bitmap.data);
    return jsQR(data, w, h, { inversionAttempts: 'attemptBoth' });
  }

  function contrastStretch(image) {
    const d = image.bitmap.data;
    const lums = [];
    for (let i = 0; i < d.length; i += 4) {
      lums.push(0.299 * d[i] + 0.587 * d[i + 1] + 0.114 * d[i + 2]);
    }
    lums.sort((a, b) => a - b);
    const p2 = lums[Math.floor(lums.length * 0.02)] || 0;
    const p98 = lums[Math.floor(lums.length * 0.98)] || 255;
    const span = p98 - p2 || 1;
    for (let i = 0; i < d.length; i += 4) {
      const L = 0.299 * d[i] + 0.587 * d[i + 1] + 0.114 * d[i + 2];
      let v = Math.round((L - p2) * 255 / span);
      v = v < 0 ? 0 : v > 255 ? 255 : v;
      d[i] = d[i + 1] = d[i + 2] = v;
    }
    return image;
  }

  function binarize(image, threshold) {
    const d = image.bitmap.data;
    for (let i = 0; i < d.length; i += 4) {
      const L = 0.299 * d[i] + 0.587 * d[i + 1] + 0.114 * d[i + 2];
      const v = L >= (threshold ?? 128) ? 255 : 0;
      d[i] = d[i + 1] = d[i + 2] = v;
    }
    return image;
  }

  const full = await Jimp.read(imgPath);
  const w = full.bitmap.width;
  const h = full.bitmap.height;
  console.log('Imagen:', w, 'x', h);

  let code = decode(full);
  if (code) { console.log('OK (completa):', code.data); process.exit(0); }
  console.log('Completa: no QR');

  const left40W = Math.max(80, Math.round(w * 0.4));
  const left40 = full.clone().crop({ x: 0, y: 0, w: left40W, h });
  code = decode(left40);
  if (code) { console.log('OK (recorte izq 40%):', code.data); process.exit(0); }
  console.log('Recorte 40%: no QR');

  const left35W = Math.max(80, Math.round(w * 0.35));
  const left35 = full.clone().crop({ x: 0, y: 0, w: left35W, h });
  code = decode(left35);
  if (code) { console.log('OK (recorte izq 35%):', code.data); process.exit(0); }
  console.log('Recorte 35%: no QR');

  const fullContrast = contrastStretch(full.clone());
  code = decode(fullContrast);
  if (code) { console.log('OK (contraste completa):', code.data); process.exit(0); }
  console.log('Contraste completa: no QR');

  const left40Contrast = contrastStretch(left40.clone());
  code = decode(left40Contrast);
  if (code) { console.log('OK (contraste + recorte 40%):', code.data); process.exit(0); }
  console.log('Contraste+recorte: no QR');

  const scale2 = full.clone().scale(2, Jimp.RESIZE_BICUBIC);
  code = decode(scale2);
  if (code) { console.log('OK (2x):', code.data); process.exit(0); }
  console.log('2x: no QR');

  const left40scale2 = left40.clone().scale(2, Jimp.RESIZE_BICUBIC);
  code = decode(left40scale2);
  if (code) { console.log('OK (recorte 40% + 2x):', code.data); process.exit(0); }
  console.log('Recorte 40%+2x: no QR');

  for (const th of [128, 140, 110, 160]) {
    const bin = binarize(left40.clone(), th);
    code = decode(bin);
    if (code) { console.log('OK (recorte 40% binarizado ' + th + '):', code.data); process.exit(0); }
  }
  console.log('Binarizado 40%: no QR');

  const left40scale3 = left40.clone().scale(3, Jimp.RESIZE_BICUBIC);
  code = decode(left40scale3);
  if (code) { console.log('OK (recorte 40% + 3x):', code.data); process.exit(0); }
  console.log('Recorte 40%+3x: no QR');

  const fullBin = binarize(full.clone(), 128);
  code = decode(fullBin);
  if (code) { console.log('OK (completa binarizado 128):', code.data); process.exit(0); }
  console.log('Completa binarizado: no QR');

  try {
    const left40sharp = left40.clone();
    if (typeof left40sharp.convolute === 'function') {
      left40sharp.convolute([[0, -1, 0], [-1, 5, -1], [0, -1, 0]]);
      code = decode(left40sharp);
      if (code) { console.log('OK (recorte 40% sharpen):', code.data); process.exit(0); }
    }
  } catch (_) {}
  console.log('Recorte 40% sharpen: no QR');

  console.log('No se encontró QR en ninguna variante.');
  process.exit(1);
}

main().catch((e) => { console.error(e); process.exit(1); });
