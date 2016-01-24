<?php

include_once('class-wp-offline-router.php');

class WP_Offline_Plugin {
    private static $instance;

    public static function init() {
        if(!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private $sw_manager_script_url;

    private function __construct() {
        $this->set_urls();
        $this->set_script_routes();
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
        header('Content-Type: application/javascript');
        header("Service-Worker-Allowed: $sw_scope");
        include_once(plugin_dir_path(__FILE__) . 'lib/js/sw.js');
        exit;
    }
}

?>