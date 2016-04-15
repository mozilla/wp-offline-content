=== Offline Content ===
Contributors: delapuente, mozillawebapps
Tags: offline, serivce, workers, service workers, read later, read offline, precache
Requires at least: 3.8
Tested up to: 4.5
Stable tag: 0.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allow your users to read your content even while offline.

== Description ==
This plugin uses new [ServiceWorker](https://developer.mozilla.org/en-US/docs/Web/API/Service_Worker_API) and [Cache API](https://developer.mozilla.org/en-US/docs/Web/API/Cache) to allow your users to access the contents of your site while they are offline or under unreliable network situations by caching part of your site.

Once you've installed this plugin, anyone visiting your site in [browsers that support the Service Workers API](http://caniuse.com/#feat=serviceworkers) will be able of access your content even if they run out of network or experiment network instability.

Configure the plugin in the "Settings > Offline content" section of your WordPress dashboard.

== Installation ==
1. Download and install the plugin from the WordPress.org plugin directory
2. Activate the plugin through the "Plugins" menu in WordPress Dashboard.

Alternatively,

1. Clone or download the project repository
2. Copy `wp-offline-content` directory inside your WordPress installation plugins directory
3. Enable the plugin from the admin panel

== Frequently Asked Questions ==
= What browsers support the W3C Service Workers API? =
[Browser support for the W3C Service Worker API](http://caniuse.com/#feat=serviceworkers) currently exists in Firefox, Chrome, and Chrome for Android, with other likely to follow.

= What is the default policy for caching content? =
The plugin will try to always serve fresh content from the Internet. After visiting a post or a page, the content will be cached in the background. In case of an unreliable network or lack of connectivity, the plugin will serve the cached content.

= Can I use the plugin in combination with other plugins using SW =
Since version 0.2.0, you can use this plugin in combination with other using the [WordPress Service Worker Manager library](https://github.com/mozilla/wp-sw-manager/blob/master/README.md).

= Can I configure which content is available for offline reading? =
In a very limited way, yes. You can enable/disable if pages should be precached is such a way the will be availables by the user even if they were never visited before.

More options will be available with new versions of the plugin.

== Change Log ==

= 0.6.1 =
Includes latest Service Worker Manager which fixes a problem unregistering the service worker when all plugins using it are disabled.

= 0.6.0 =
The Service Worker unregister itself when no plugin using service workers is enabled.
Use WordPress AJAX infrastructure for dynamically generating the service worker file while reducing server footprint.
Use [WP_Serve_File](http://github.com/marco-c/wp_serve_file) to efficiently generate the registrar and avoid unnecessary WordPress loads.
Relying on composer's autoload to manage plugin dependencies.

= 0.5.0 =
Prevent undesired updates when used with other service worker supported plugins for WordPress.

= 0.4.0 =
New smart update algorithm minifies the number of background downloads when adding new content.

= 0.3.0 =
Cleaning old caches when changing the name of the cache where offline content is stored.

= 0.2.0 =
Now can be combined with other WP plugins using the [WordPress Service Worker Manager library](https://github.com/mozilla/wp-sw-manager/blob/master/README.md) such as [Web Push](https://wordpress.org/plugins/web-push/).

= 0.1.0 =
Initial release.
