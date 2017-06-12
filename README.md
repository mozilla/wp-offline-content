# wp-offline-content
> A WordPress plugin for offlining content.

[![Build Status](https://travis-ci.org/mozilla/wp-offline-content.svg?branch=master)](https://travis-ci.org/mozilla/wp-offline-content) [![WordPress plugin](https://img.shields.io/wordpress/plugin/v/offline-content.svg)](https://wordpress.org/plugins/offline-content/) [![WordPress](https://img.shields.io/wordpress/plugin/dt/offline-content.svg)](https://wordpress.org/plugins/offline-content/)

> **IMPORTANT**: I'm very sorry to announce this plugin is **unmaintained** thought it is still compatible with WordPress up to version 4.4.10.

## Install the plugin

You can find this plugin in the [WordPress Plugin repository](https://wordpress.org/plugins/offline-content/) so you can install it from the _Plugins_ menu of your WordPress installation.

In case you want to do it manually, here are the instructions:

First, clone the repository.

Now, at the root of the repository, run (you need [composer](https://getcomposer.org) for this):

```
$ composer install --working-dir=wp-offline-content --optimize-autoloader
```

And copy (or symlink) the folder `wp-offline-content` inside your WordPress `plugins` directory.

Once installed, activate the plugin from the _Plugins_ menu in the _Dashboard_. Options are available to customize under the _Offline content_ submenu in _Settings_.

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
