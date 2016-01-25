
(function (self) {
  self.addEventListener('install', event => {
    console.log('sw installed');
  });

  self.addEventListener('activate', event => {
    console.log('sw activated');
  });

  self.addEventListener('fetch', event => {
    console.log('fetching ' + event.request.url);
    event.respondWith(wpOffline.get(event.request));
  });

  var wpOffline = self.wpOffline = {

    cacheName: '<?php echo $cache_name; ?>',

    networkTimeout: <?php echo $network_timeout; ?>,

    get: function (request) {
      var fetchFromNetwork = fetch(request);
      if (request.method !== 'GET') {
        return fetchFromNetwork;
      }

      var fetchAndCache = fetchFromNetwork.then(response => {
        if (response.ok) {
          this.openCache().then(cache => cache.put(request, response.clone()));
        }
      });

      var waitForNetwork = new Promise((fulfill, reject) => {
        var expired = false;

        var timeout = setTimeout(() => {
          expired = true;
          reject();
        }, this.networkTimeout);

        fetchFromNetwork
        .then(
          response => {
            if (!expired) {
              clearTimeout(timeout);
              fulfill(response);
            }
          },
          error => {
            if (!expired) {
              clearTimeout(timeout);
              reject(error);
            }
          }
        );
      });

      var fetchFromCache = self.caches.match(request);

      return waitForNetwork
      .catch(() => fetchFromCache.then(response => response || fetchFromNetwork));
    },

    openCache: function () {
      if (!this._openCache) {
        this._openCache = self.caches.open(this.cacheName);
      }
      return this._openCache;
    }
  };
})(self);
