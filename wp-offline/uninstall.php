if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

include_once(plugin_dir_path(__FILE__) . 'class-wp-offline-options.php');

WP_Offline_Options::get_options()->remove_all();