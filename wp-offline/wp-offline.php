<?php
/*
Plugin Name: Offline
Plugin URI: https://github.com/delapuente/wp-offline
Description: Allow your users to continue accessing your content even while offline.
Version: 0.0.1
Author: Mozilla
Author URI: https://www.mozilla.org/
License: GPLv2 or later
Text Domain: offline
*/

load_plugin_textdomain('offline', false, dirname(plugin_basename(__FILE__)) . '/lang');

include_once(plugin_dir_path(__FILE__) . 'class-wp-offline-plugin.php');

if (is_admin()) {
    include_once(plugin_dir_path(__FILE__) . 'class-wp-offline-admin.php');
    WP_Offline_Admin::init();
}
WP_Offline_Plugin::init();
?>
