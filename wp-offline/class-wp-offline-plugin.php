<?php

include_once(plugin_dir_path(__FILE__) . 'class-wp-offline-router.php');
include_once(plugin_dir_path(__FILE__) . 'class-wp-offline-options.php');

class WP_Offline_Plugin {
    private static $instance;

    public static function init() {
        if(!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private $sw_manager_script_url;

    private $options;

    private function __construct() {
        $this->options = WP_Offline_Options::get_options();
        $this->set_urls();
        $this->set_script_routes();
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        register_uninstall_hook(__FILE__, array($this, 'uninstall'));
        add_action('wp_enqueue_scripts', array($this, 'inject_scripts'));
    }

    private function set_urls() {
        $this->sw_manager_script_url = plugins_url('lib/js/sw-manager.js', __FILE__);
        $this->sw_script_url = plugins_url('lib/js/sw.js', __FILE__);
        $this->sw_scope = home_url('/');
    }

    private function set_script_routes() {
        $router = WP_Offline_Router::get_router();
        $router->add_route($this->sw_manager_script_url, array($this, 'render_manager'));
        $router->add_route($this->sw_script_url, array($this, 'render_sw'));
    }

    public function activate() {
        $this->options->set_defaults();
    }

    public static function deactivate() {
    }

    public static function uninstall() {
        $this->options()->remove_all();
    }

    public function inject_scripts() {
        $router = WP_Offline_Router::get_router();
        wp_enqueue_script('sw-manager-script', $router->route($this->sw_manager_script_url));
    }

    public function render_manager() {
        $router = WP_Offline_Router::get_router();
        $sw_scope = $this->sw_scope;
        $sw_url = $router->route($this->sw_script_url);
        header('Content-Type: application/javascript');
        include_once(plugin_dir_path(__FILE__) . 'lib/js/sw-manager.js');
        exit;
    }

    public function render_sw() {
        $sw_scope = $this->sw_scope;
        $debug = $this->options->get('offline_debug_sw');
        $network_timeout = $this->options->get('offline_network_timeout');
        $cache_name = $this->options->get('offline_cache_name');
        $resources = $this->get_precache_list();
        $excluded_paths = $this->get_excluded_paths();
        header('Content-Type: application/javascript');
        header("Service-Worker-Allowed: $sw_scope");
        include_once(plugin_dir_path(__FILE__) . 'lib/js/sw.js');
        exit;
    }

    private function get_precache_list() {
        return array();
    }

    private function get_excluded_paths() {
        return array(admin_url(), content_url(), includes_url());
    }
}

?>