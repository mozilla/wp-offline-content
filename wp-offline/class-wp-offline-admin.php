<?php

load_plugin_textdomain('wpoffline', false, dirname(plugin_basename(__FILE__)) . '/lang');

include_once(plugin_dir_path(__FILE__) . 'class-wp-offline-options.php');

// Based on: https://codex.wordpress.org/Creating_Options_Pages#Example_.232
class WP_Offline_Admin {
    private static $instance;

    public static $options_page_id = 'offline-options';

    public static $options_group = 'offline-settings-group';

    public static function init() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private $options;

    private function __construct() {
        $this->options = WP_Offline_Options::get_options();
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
    }

    public function admin_init() {
        $group = self::$options_group;
        register_setting($group, 'offline_cache_name', array($this, 'sanitize_cache_name'));
        register_setting($group, 'offline_network_timeout', array($this, 'sanitize_network_timeout'));

        add_settings_section(
            'default',
            '',
            function () {},
            self::$options_page_id
        );

        add_settings_section(
            'serving-policy',
            __('Serving policy', 'wpoffline'),
            array($this, 'print_serving_policy_info'),
            self::$options_page_id
        );

        add_settings_field(
            'cache-name',
            __('Cache name'),
            array($this, 'cache_name_input'),
            self::$options_page_id,
            'default'
        );

        add_settings_field(
            'network-timeout',
            __('Network timeout'),
            array($this, 'network_timeout_input'),
            self::$options_page_id,
            'serving-policy'
        );
    }

    public function admin_menu() {
        add_options_page(
            __('Offline Options', 'wpoffline'), __('Offline', 'wpoffline'),
            'manage_options', self::$options_page_id, array($this, 'create_admin_page')
        );
    }

    public function create_admin_page() {
        include_once(plugin_dir_path(__FILE__) . 'lib/pages/admin.php');
    }

    public function cache_name_input() {
        $cache_name = $this->options->get('offline_cache_name');
        ?>
        <input id="offline-cache-name" type="text" name="offline_cache_name"
         value="<?php echo esc_attr($cache_name); ?>"
         class="normal-text"/>
        <p class="description">
          <?php _e('Name of the cache used to store offline content.'); ?>
        </p>
        <?php
    }

    public function network_timeout_input() {
        $network_timeout = $this->options->get('offline_network_timeout') / 1000;
        ?>
        <input id="offline-network-timeout" type="number" name="offline_network_timeout"
         value="<?php echo esc_attr($network_timeout); ?>" min="1" step="1"
         class="small-text"/> <?php _e('seconds before serving cached content'); ?>
        <?php
    }

    public function sanitize_cache_name($value) {
        if (isset($value) && !trim($value)) {
            add_settings_error(
                'cache_name',
                'cache-name-empty',
                __('Cache name can not be empty.', 'wpoffline')
            );
            $value = $this->options->get('offline_cache_name');
        }
        return $value;
    }

    public function sanitize_network_timeout($value) {
        $value = $value * 1000; // convert to milliseconds
        if (isset($value) && $value < 1000) {
            add_settings_error(
                'network_timeout',
                'incorrect-network-timeout',
                __('Network timeout must be at least 1 second.', 'wpoffline')
            );
            $value = $this->options->get('offline_network_timeout');
        }
        return $value;
    }

    public function print_serving_policy_info() {
        ?>
        <p><?php _e('Offline plugin prefers to serve fresh living content from the Internet but it will serve cached content in case network is not available or not reliable.', 'wpoffline');?></p>
        <?php
    }

}

?>