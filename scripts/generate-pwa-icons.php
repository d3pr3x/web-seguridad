<?php
/**
 * Genera iconos PNG 192x192 y 512x512 para la PWA.
 * Ejecutar desde la raíz del proyecto: php scripts/generate-pwa-icons.php
 * Requiere extensión PHP GD.
 */
$baseDir = dirname(__DIR__);
$outDir = $baseDir . '/public/icons';
if (!is_dir($outDir)) {
    mkdir($outDir, 0755, true);
}

if (!extension_loaded('gd')) {
    echo "La extensión PHP GD no está instalada. Opciones:\n";
    echo "1. Instálala (ej. en Ubuntu: sudo apt install php-gd) y vuelve a ejecutar.\n";
    echo "2. Crea iconos manualmente: sube icon-192.png e icon-512.png a public/icons/\n";
    echo "   Puedes usar https://www.pwabuilder.com/imageGenerator con tu logo.\n";
    exit(1);
}

foreach ([192, 512] as $size) {
    $im = imagecreatetruecolor($size, $size);
    if (!$im) {
        echo "Error creando imagen {$size}x{$size}\n";
        continue;
    }
    $color = imagecolorallocate($im, 0x0f, 0x76, 0x6e); // #0f766e
    $white = imagecolorallocate($im, 255, 255, 255);
    imagefill($im, 0, 0, $color);
    // Círculo blanco simple (cámara/objetivo)
    $cx = $size / 2;
    $cy = $size / 2;
    $r = (int)($size * 0.35);
    imagefilledellipse($im, $cx, $cy, $r * 2, $r * 2, $white);
    imagefilledellipse($im, $cx, $cy, (int)($r * 0.6), (int)($r * 0.6), $color);
    $path = $outDir . "/icon-{$size}.png";
    imagepng($im, $path);
    imagedestroy($im);
    echo "Creado: $path\n";
}

echo "Listo. Recarga la web (HTTPS) y revisa el menú de Chrome para 'Instalar app'.\n";
