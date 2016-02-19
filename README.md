# wp-offline-content
> A WordPress plugin for offlining content.

[![Build Status](https://travis-ci.org/delapuente/wp-offline-content.svg?branch=master)](https://travis-ci.org/delapuente/wp-offline-content) [![WordPress plugin](https://img.shields.io/wordpress/plugin/v/offline-content.svg)](https://wordpress.org/plugins/offline-content/) [![WordPress](https://img.shields.io/wordpress/plugin/dt/offline-content.svg)](https://wordpress.org/plugins/offline-content/)

## Install the plugin

Clone the repository and copy the folder `wp-offline-content` inside your WordPress `plugins` directory.

Activate the plugin from the _Plugins_ menu in the _Dashboard_. Options are available to customize under the _Offline content_ submenu in _Settings_.

## Running tests

Install dependencies:
```bash
./bin/install-wp-tests.sh MYSQL_DATABASE_NAME MYSQL_USER MYSQL_PASSWORD localhost latest
```

Run tests:
```bash
make test
```

Run service worker tests:
```bash
make test-sw
```
