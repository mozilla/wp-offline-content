
(function (self) {
  self.addEventListener('install', event => {
    console.log('sw installed');
  });

  self.addEventListener('activate', event => {
    console.log('sw activated');
  });

  self.addEventListener('fetch', event => {
    event.respondWith(wpOffline.get(event.request));
  });

  var wpOffline = self.wpOffline = {

    debug: <?php echo $debug; ?>,

    cacheName: '<?php echo $cache_name; ?>',

    networkTimeout: <?php echo $network_timeout; ?>,

    log: function () {
      if (this.debug) {
        console.log.apply(console, arguments);
      }
    },

    get: function (request) {
      var url = request.url;
      this.log('Fetching', url);

      var fetchFromNetwork = fetch(request).catch(error => this.log('Failed to fetch', url));
      if (request.method !== 'GET') {
        return fetchFromNetwork;
      }

      var fetchAndCache = fetchFromNetwork.then(responseFromNetwork => {
        if (responseFromNetwork && responseFromNetwork.ok) {
          this.log('Caching', responseFromNetwork.url);
          this.openCache()
          .then(cache => cache.put(request.clone(), responseFromNetwork.clone()));
        }
      });

      var waitForNetwork = new Promise((fulfill, reject) => {
        var expired = false;

        var timeout = setTimeout(() => {
          this.log('Timeout for', url)
          expired = true;
          reject();
        }, this.networkTimeout);

        fetchFromNetwork
        .then(
          responseFromNetwork => {
            if (!expired) {
              clearTimeout(timeout);
              if (!responseFromNetwork) {
                this.log('Undefined response for', url);
                reject('network-error');
              } else {
                this.log('Success from network for', url);
                fulfill(responseFromNetwork.clone());
              }
            }
          },
          error => {
            if (!expired) {
              this.log('Network error for', url);
              clearTimeout(timeout);
              reject(error);
            }
          }
        );
      });

      var fetchFromCache = self.caches.match(request).catch(error => console.error(error));

      return waitForNetwork
        .catch(() => fetchFromCache.then(responseFromCache => {
          if (!responseFromCache) {
            this.log('Cache miss for', url);
            return fetchFromNetwork;
          }
          this.log('Cache hit for', url);
          return responseFromCache;
        }));
    },

    openCache: function () {
      if (!this._openCache) {
        this._openCache = self.caches.open(this.cacheName);
      }
      return this._openCache;
    }
  };
})(self);
