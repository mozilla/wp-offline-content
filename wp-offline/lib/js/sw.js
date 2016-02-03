
(function (self) {
  self.addEventListener('install', event => {
    event.waitUntil(Promise.all([
      self.skipWaiting(),
      wpOffline.precache()
    ]));
  });

  self.addEventListener('activate', event => {
    event.waitUntil(self.clients.claim());
  });

  self.addEventListener('fetch', event => {
    event.respondWith(wpOffline.get(event.request));
  });

  var wpOffline = self.wpOffline = {

    resources: $resources,

    excludedPaths: $excludedPaths,

    debug: $debug,

    cacheName: $cacheName,

    networkTimeout: $networkTimeout,

    log: function () {
      if (this.debug) {
        console.log.apply(console, arguments);
      }
    },

    origin: self.location.origin,

    precache: function () {
      return this.openCache().then(cache => cache.addAll(this.resources.map(entry => entry[0])));
    },

    get: function (request) {
      var url = request.url;
      this.log('Fetching', url);

      var fetchFromNetwork = fetch(request)
      .catch(error => {
        this.log('Failed to fetch', url);
        throw error;
      });

      if (request.method !== 'GET' || this.isExcluded(url)) {
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
          this.log('Timeout for', url);
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

    isExcluded: function (url) {
      return this.isAnotherOrigin(url) ||
             this.excludedPaths.some(path => url.startsWith(path));
    },

    openCache: function () {
      if (!this._openCache) {
        this._openCache = self.caches.open(this.cacheName);
      }
      return this._openCache;
    },

    isAnotherOrigin: function (url) {
      return !url.startsWith(this.origin);
    }
  };
})(self);
