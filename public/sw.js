// Service Worker mínimo para PWA (permite "Instalar app" y que el permiso de cámara se recuerde)
const CACHE_NAME = 'web-seguridad-v1';
self.addEventListener('install', function(event) {
  self.skipWaiting();
});
self.addEventListener('activate', function(event) {
  event.waitUntil(self.clients.claim());
});
self.addEventListener('fetch', function(event) {
  // Sin cacheo: siempre red. Evita problemas con auth y datos en tiempo real.
  event.respondWith(fetch(event.request));
});
