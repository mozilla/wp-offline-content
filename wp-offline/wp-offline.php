<?php
/*
Plugin Name: Offline
Description: Allow your users to continue accessing your content even while offline.
*/
define('WP_DEBUG', true);

include_once(plugin_dir_path(__FILE__) . 'class-wp-offline-plugin.php');

WP_Offline_Plugin::init();
?>