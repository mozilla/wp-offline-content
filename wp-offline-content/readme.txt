=== Offline Content ===
Contributors: delapuente
Tags: offline, serivce, workers, service workers, read later, read offline, precache
Requires at least: 3.7
Tested up to: 4.4.1
Stable tag: 0.1.0
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

= Can I configure which content is available for offline reading? =
In a very limited way, yes. You can enable/disable if pages should be precached is such a way the will be availables by the user even if they were never visited before.

More options will be available with new versions of the plugin.
