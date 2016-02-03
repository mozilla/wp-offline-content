var $debug = false;
var $resources = [];
var $excludedPaths = [];
var $cacheName = 'testCache';
var $networkTimeout = 1000;

describe('get()', function() {
  'use strict';

  var clock;

  beforeEach(function() {
    clock = sinon.useFakeTimers();
    importScripts('/base/wp-offline/lib/js/sw.js');
  });

  afterEach(function() {
    clock.restore();
  });

  function addByPassWhenNetwork() {
    describe('get() when network available but request is not a GET or url is excluded', function() {
      var networkResponse = new Response();

      before(function() {
        sinon.stub(self, 'fetch').returns(Promise.resolve(networkResponse));
      });

      after(function() {
        self.fetch.restore();
      });

      it('always fetches from network if excluded', function() {
        sinon.stub(wpOffline, 'isExcluded').returns(true);
        return wpOffline.get(new Request('/test/url'))
        .then(response => {
          wpOffline.isExcluded.restore();
          return response;
        })
        .then(response => {
          assert.equal(response, networkResponse);
        });
      });

      it('always fetches from network if it is not a GET request', function() {
        var nonGetRequest = new Request('some/valid/url', { method: 'POST'});
        return wpOffline.get(nonGetRequest)
        .then(response => {
          assert.equal(response, networkResponse);
        });
      });
    });
  }

  function addByPassWhenNoNetwork() {
    describe('get() when network non available and request is not a GET or url is excluded', function() {
      var networkError = {};

      before(function() {
        sinon.stub(self, 'fetch').returns(Promise.reject(networkError));
      });

      after(function() {
        self.fetch.restore();
      });

      it('error if excluded', function() {
        sinon.stub(wpOffline, 'isExcluded').returns(true);
        return wpOffline.get(new Request('/test/url'))
        .catch(error => {
          wpOffline.isExcluded.restore();
          return error;
        })
        .then(error => {
          assert.equal(error, networkError);
        });
      });

      it('error if it is not a GET request', function() {
        var nonGetRequest = new Request('some/valid/url', { method: 'POST'});
        return wpOffline.get(nonGetRequest)
        .catch(error => {
          assert.equal(error, networkError);
        });
      });
    });
  }

  describe('get() when network is available and it does not time out', function() {

    addByPassWhenNetwork();

    it('fetches from network', function() {

    });

    it('stores a fresh copy in the cache', function() {

    });

  });

  describe('get() when network is available but times out', function() {

    addByPassWhenNoNetwork();

    it('fetches from cache', function() {

    });

    it('stores a fresh copy in the cache', function() {

    });

  });

  describe('get() when network is not available', function() {

    addByPassWhenNoNetwork();

    it('fetches from cache if there is a match', function() {

    });

    it('error if there is no match', function() {

    });

  });
});
