self.addEventListener('install', event => {
  console.log('sw installed');
});

self.addEventListener('activate', event => {
  console.log('sw activated');
});

self.addEventListener('fetch', event => {
  console.log('fetching ' + event.request.url);
  event.respondWith(fetch(event.respondWith));
});
