<?php

include_once(plugin_dir_path(__FILE__) . 'class-wp-offline-content-options.php');
include_once(plugin_dir_path(__FILE__) . 'vendor/mozilla/wp-sw-manager/class-wp-sw-manager.php');

class WP_Offline_Content_Plugin {
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
        $plugin_main_file = plugin_dir_path(__FILE__) . 'wp-offline-content.php';
        $this->options = WP_Offline_Content_Options::get_options();
        $this->set_urls();
        $this->setup_sw();
        register_activation_hook($plugin_main_file, array($this, 'activate'));
        register_deactivation_hook($plugin_main_file, array($this, 'deactivate'));
    }

    private function set_urls() {
        $this->sw_manager_script_url = plugins_url('lib/js/sw-manager.js', __FILE__);
        $this->sw_script_url = plugins_url('lib/js/sw.js', __FILE__);
        $this->sw_scope = home_url('/');
    }

    private function setup_sw() {
        WP_SW_Manager::get_manager()->sw()->add_content(array($this, 'render_sw'));
    }

    public function activate() {
        $this->options->set_defaults();
    }

    public static function deactivate() {
    }

    public function render_sw() {
        $sw_scope = $this->sw_scope;
        $this->render(plugin_dir_path(__FILE__) . 'lib/js/sw.js', array(
            '$debug' => boolval($this->options->get('offline_debug_sw')),
            '$cacheName' => $this->options->get('offline_cache_name'),
            '$networkTimeout' => intval($this->options->get('offline_network_timeout')),
            '$resources' => $this->get_precache_list(),
            '$excludedPaths' => $this->get_excluded_paths()
        ));
    }

    private function render($path, $replacements) {
        $contents = file_get_contents($path);
        foreach ($replacements as $key => $replacement) {
            $contents = str_replace($key, json_encode($replacement), $contents);
        }
        echo $contents;
    }

    private function get_precache_list() {
        $precache_options = $this->options->get('offline_precache');
        $precache_list = array();
        if ($precache_options['pages']) {
            foreach (get_pages() as $page) {
                $precache_list[] = array(
                    get_page_link($page),
                    wp_hash($page->post_content)
                );
            }
        }
        return $precache_list;
    }

    private function get_excluded_paths() {
        return array(admin_url(), content_url(), includes_url());
    }
}

?>