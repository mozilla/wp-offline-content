var $debug = false;
var $resources = [];
var $excludedPaths = [];
var $cacheName = 'testCache';
var $networkTimeout = 1000;

describe('get()', function() {
  'use strict';

  beforeEach(function() {
    importScripts('/base/wp-offline/lib/js/sw.js');
  });

  it('try to fetch from network', function() {
    assert(true);
  });
});
