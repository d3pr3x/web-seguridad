/**
 * Generador de Browser Fingerprint
 * Crea un identificador único basado en características del navegador y dispositivo
 */

async function generateBrowserFingerprint() {
    const components = [];

    // 1. User Agent
    components.push(navigator.userAgent);

    // 2. Idioma
    components.push(navigator.language);

    // 3. Zona horaria
    components.push(Intl.DateTimeFormat().resolvedOptions().timeZone);

    // 4. Resolución de pantalla
    components.push(`${screen.width}x${screen.height}x${screen.colorDepth}`);

    // 5. Plataforma
    components.push(navigator.platform);

    // 6. Número de procesadores lógicos
    components.push(navigator.hardwareConcurrency || 'unknown');

    // 7. Memoria del dispositivo (si está disponible)
    components.push(navigator.deviceMemory || 'unknown');

    // 8. WebGL Vendor y Renderer
    try {
        const canvas = document.createElement('canvas');
        const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
        if (gl) {
            const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
            if (debugInfo) {
                components.push(gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL));
                components.push(gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL));
            }
        }
    } catch (e) {
        components.push('no-webgl');
    }

    // 9. Canvas Fingerprint
    try {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        ctx.textBaseline = 'top';
        ctx.font = '14px "Arial"';
        ctx.textBaseline = 'alphabetic';
        ctx.fillStyle = '#f60';
        ctx.fillRect(125, 1, 62, 20);
        ctx.fillStyle = '#069';
        ctx.fillText('Canvas Fingerprint 🔐', 2, 15);
        ctx.fillStyle = 'rgba(102, 204, 0, 0.7)';
        ctx.fillText('Canvas Fingerprint 🔐', 4, 17);
        components.push(canvas.toDataURL());
    } catch (e) {
        components.push('no-canvas');
    }

    // 10. Plugins instalados
    const plugins = Array.from(navigator.plugins || [])
        .map(p => p.name)
        .sort()
        .join(',');
    components.push(plugins || 'no-plugins');

    // 11. Touch support
    components.push('maxTouchPoints:' + (navigator.maxTouchPoints || 0));

    // 12. Cookies habilitadas
    components.push('cookies:' + navigator.cookieEnabled);

    // 13. Do Not Track
    components.push('dnt:' + (navigator.doNotTrack || 'unknown'));

    // Combinar todos los componentes
    const fingerprint = components.join('|||');
    
    // Generar hash SHA-256
    const hashBuffer = await crypto.subtle.digest('SHA-256', new TextEncoder().encode(fingerprint));
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    
    return hashHex;
}

/**
 * Obtener o generar el fingerprint y almacenarlo
 */
async function getBrowserFingerprint() {
    // Intentar obtener de localStorage
    let fingerprint = localStorage.getItem('browser_fingerprint');
    
    if (!fingerprint) {
        // Generar nuevo fingerprint
        fingerprint = await generateBrowserFingerprint();
        localStorage.setItem('browser_fingerprint', fingerprint);
    }
    
    return fingerprint;
}

/**
 * Obtener la geolocalización del usuario
 */
function getCurrentPosition() {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject(new Error('Geolocalización no soportada por este navegador'));
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                resolve({
                    latitud: position.coords.latitude,
                    longitud: position.coords.longitude,
                    precision: position.coords.accuracy
                });
            },
            (error) => {
                let errorMessage = 'Error al obtener ubicación';
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'Permiso de ubicación denegado';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Ubicación no disponible';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'Tiempo de espera agotado';
                        break;
                }
                reject(new Error(errorMessage));
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    });
}

