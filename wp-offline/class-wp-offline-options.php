<?php

class WP_Offline_Options {
    private static $instance;

    private static $DEFAULTS = array(
        'offline_network_timeout' => 4000,
        'offline_cache_name' => 'wpOffline'
    );

    public static function get_options() {
        if(!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
    }

    public function set_defaults() {
        foreach (self::$DEFAULTS as $name => $value) {
            update_option($name, $value);
        }
    }

    public function remove_all() {
        foreach (self::$DEFAULTS as $name => $value) {
            delete_option($name);
        }
    }

    public function set($name, $value) {
        update_option($name, $value);
        return $this;
    }

    public function get($name) {
        return get_option($name);
    }
}

?>