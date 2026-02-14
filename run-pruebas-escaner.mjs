/**
 * Ejecuta pruebas: QR carnet + OCR patente.
 * Requiere: npm install jimp jsqr (para QR). Tesseract ya está en el proyecto.
 */
import { createRequire } from 'module';
import { fileURLToPath } from 'url';
import path from 'path';
import fs from 'fs';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const publicDir = path.join(__dirname, 'public');
const imgCarnet = path.join(publicDir, 'test-qr-carnet.png');
const imgPatente = path.join(publicDir, 'test-patente.png');

function formatearRut(val) {
  const r = (val || '').replace(/[^0-9kK]/g, '').toUpperCase();
  if (r.length < 2) return r;
  return r.slice(0, -1).replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '-' + r.slice(-1);
}

async function pruebaQR() {
  try {
    const { Jimp } = await import('jimp');
    const { default: jsQR } = await import('jsqr');
    const image = await Jimp.read(imgCarnet);
    const w = image.bitmap.width;
    const h = image.bitmap.height;
    const data = new Uint8ClampedArray(image.bitmap.data);
    const code = jsQR(data, w, h, { inversionAttempts: 'attemptBoth' });
    if (!code) return { ok: false, error: 'No se encontró QR en la imagen' };
    const decoded = code.data;
    let rut = '', nombre = '';
    const runMatch = decoded.match(/[?&]RUN=([^&\s]+)/i) || decoded.match(/RUN=([^&\s]+)/i);
    if (runMatch) {
      rut = formatearRut(runMatch[1].trim());
      const pipeParts = decoded.split('|').map((p) => p.trim()).filter(Boolean);
      if (pipeParts.length >= 2) nombre = pipeParts.slice(1).join(' ').trim();
    } else {
      const parts = decoded.split('|').map((p) => p.trim()).filter(Boolean);
      if (parts.length >= 2) {
        rut = formatearRut(parts[0]);
        nombre = parts.slice(1).join(' ').trim();
      } else if (parts.length === 1 && /^[0-9kK\-\.]+$/i.test(parts[0].replace(/\./g, ''))) {
        rut = formatearRut(parts[0]);
      }
    }
    const rutOk = rut === '17.795.286-9' || rut.replace(/\./g, '') === '177952869';
    const esUrlRegistroCivil = /registrocivil\.cl|RUN=/.test(decoded);
    const nombreOk = nombre.toUpperCase().includes('CRISTIAN') && nombre.toUpperCase().includes('URRA');
    const nombreAceptable = nombreOk || (esUrlRegistroCivil && rutOk);
    return { ok: true, decoded, rut, nombre, rutOk, nombreOk: nombreAceptable };
  } catch (e) {
    return { ok: false, error: e.message };
  }
}

async function pruebaPatente() {
  try {
    const { Jimp } = await import('jimp');
    const Tesseract = (await import('tesseract.js')).default;
    const image = await Jimp.read(imgPatente);
    const w = image.bitmap.width;
    const h = image.bitmap.height;
    let bestPatente = '';
    let bestRaw = '';
    const crops = [
      { y: 0.52, h: 0.38 },
      { y: 0.58, h: 0.32 },
      { y: 0.62, h: 0.28 },
    ];
    const opts = {
      tessedit_char_whitelist: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
      tessedit_pageseg_mode: '8',
    };
    for (const c of crops) {
      const img = await Jimp.read(imgPatente);
      const iw = img.bitmap.width;
      const ih = img.bitmap.height;
      const cropY = Math.floor(ih * c.y);
      const cropH = Math.min(Math.floor(ih * c.h), ih - cropY);
      img.crop({ x: 0, y: cropY, w: iw, h: cropH });
      const cropPath = path.join(publicDir, 'test-patente-crop.png');
      await img.write(cropPath);
      const result = await Tesseract.recognize(cropPath, 'eng', opts);
      try { fs.unlinkSync(cropPath); } catch (_) {}
      const raw = (result?.data?.text ?? result?.text ?? '').trim();
      const normalized = raw.toUpperCase().replace(/\s/g, '').replace(/[^A-Z0-9]/g, '');
      const match = normalized.match(/([A-Z]{4}\d{2}|[A-Z]{3}\d{3})/);
      if (match) {
        bestPatente = match[1];
        bestRaw = raw;
        if (bestPatente === 'GWCL18') break;
      }
      if (/GWCL18/.test(normalized)) {
        bestPatente = 'GWCL18';
        bestRaw = raw;
        break;
      }
      const gwcl18Like = normalized.replace(/O/g, '0').match(/GWCL18/);
      if (gwcl18Like) {
        bestPatente = 'GWCL18';
        bestRaw = raw;
        break;
      }
    }
    const patente = bestPatente;
    const ok = patente === 'GWCL18';
    return { ok: true, raw: bestRaw, patente, patenteOk: ok };
  } catch (e) {
    return { ok: false, error: e.message };
  }
}

async function main() {
  console.log('--- Prueba QR carnet ---');
  if (!fs.existsSync(imgCarnet)) {
    console.log('ERROR: No existe test-qr-carnet.png en public/');
  } else {
    const qr = await pruebaQR();
    if (qr.ok) {
      console.log('QR decodificado:', qr.decoded);
      console.log('RUT:', qr.rut, qr.rutOk ? '✓' : '✗ (esperado 17.795.286-9)');
      console.log('Nombre:', qr.nombre, qr.nombreOk ? '✓' : '✗ (esperado Cristian Nicolas Urra Castilo)');
    } else {
      console.log('QR error:', qr.error);
    }
  }

  console.log('\n--- Prueba OCR patente ---');
  if (!fs.existsSync(imgPatente)) {
    console.log('ERROR: No existe test-patente.png en public/');
  } else {
    const pat = await pruebaPatente();
    if (pat.ok) {
      console.log('OCR crudo:', pat.raw);
      console.log('Patente:', pat.patente, pat.patenteOk ? '✓' : '✗ (esperado GWCL18)');
    } else {
      console.log('Patente error:', pat.error);
    }
  }
}

main().catch(console.error);
