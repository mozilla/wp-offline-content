<?php

class WP_Offline_Content_Router {
    private static $TRIGGER = '_wpofflinecontent';

    private static $instance;

    public static function get_router() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private $routes;

    public function add_route($url, $callback) {
        $components = parse_url($url);
        $path = $components['path'];
        $this->routes[$path] = $callback;
    }

    public function route($url) {
        $tokens = parse_url($url);
        $query = array_key_exists('query', $tokens) ? ('?' . $tokens['query']) : '';
        $fragment = array_key_exists('fragment', $tokens) ? ('#' . $tokens['fragment']) : '';
        $path = $tokens['path'];
        $route = urlencode($path . $query . $fragment);
        return home_url('/') . '?' . self::$TRIGGER . '=' . $route;
    }

    private function __construct() {
        $this->routes = array();
        add_action('parse_request', array($this, 'parse_request'));
        add_filter('query_vars', array($this, 'query_vars'));
    }

    public function parse_request($query) {
        $query_vars = $query->query_vars;
        list($route_trigger, $additional_args) = $this->identify_trigger($query_vars);
        if ($route_trigger) {
            // merge with query args
            $handler = $this->routes[$route_trigger];
            $this->call_handler($handler, $query);
        }
    }

    public function query_vars($query_vars) {
        $query_vars[] = self::$TRIGGER;
        return $query_vars;
    }

    public function identify_trigger($query_args) {
        $handler = NULL;
        $args = array();
        if (array_key_exists(self::$TRIGGER, $query_args)) {
            $tokens = explode('?', $query_args[self::$TRIGGER]);
            $handler = $tokens[0];
            // add args
        }
        return array($handler, $args);
    }

    public function call_handler($handler, $args) {
        if(is_array($handler)) {
            $handler[0]->$handler[1]($args);
        } else {
            $handler($args);
        }
    }
}

?>