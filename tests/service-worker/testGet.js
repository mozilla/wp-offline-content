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

  var nonGetRequest = new Request('some/valid/url', { method: 'POST'});

  function addFetchFromNetworkIfExcluded(expectedResponse) {
    it('always fetches from network if excluded', function() {
      sinon.stub(wpOffline, 'isExcluded').returns(true);
      return wpOffline.get(new Request('/test/url'))
      .then(response => {
        assert.equal(response, expectedResponse);
        wpOffline.isExcluded.restore();
      })
      .catch(error => {
        wpOffline.isExcluded.restore();
        throw error;
      });
    });
  }

  function addFetchFromNetworkIfNotGet(expectedResponse) {
    it('always fetches from network if it is not a GET request', function() {
      return wpOffline.get(new Request('/test/url', { method: 'POST' }))
      .then(response => {
        assert.equal(response, expectedResponse);
      });
    });
  }

  function addByPassTestCases() {
    describe('get() when request is not a GET or url is excluded', function() {
      var networkResponse = new Response();

      before(function() {
        sinon.stub(self, 'fetch').returns(Promise.resolve(networkResponse));
      });

      after(function() {
        self.fetch.restore();
      });

      addFetchFromNetworkIfNotGet(networkResponse);
      addFetchFromNetworkIfExcluded(networkResponse);
    });
  }

  describe('get() when network is available and it does not time out', function() {

    addByPassTestCases();

    it('fetches from network', function() {

    });

    it('stores a fresh copy in the cache', function() {

    });

  });

  describe('get() when network is available but times out', function() {

    addByPassTestCases();

    it('fetches from cache', function() {

    });

    it('stores a fresh copy in the cache', function() {

    });

  });

  describe('get() when network is not available', function() {

    addByPassTestCases();

    it('fetches from cache if there is a match', function() {

    });

    it('error if there is no match', function() {

    });

  });
});
