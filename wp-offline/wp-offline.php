<?php
/*
Plugin Name: Offline
Description: Allow your users to continue accessing your content even while offline.
*/

load_plugin_textdomain('wpoffline', false, dirname(plugin_basename(__FILE__)) . '/lang');

include_once(plugin_dir_path(__FILE__) . 'class-wp-offline-plugin.php');

if (is_admin()) {
    include_once(plugin_dir_path(__FILE__) . 'class-wp-offline-admin.php');
    WP_Offline_Admin::init();
}
WP_Offline_Plugin::init();
?>