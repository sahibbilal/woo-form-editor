<?php
if (!defined('ABSPATH')) exit;

class WFE_Settings {
    public static function get_option($key, $default = '') {
        $options = get_option('wfe_options', []);
        return isset($options[$key]) ? $options[$key] : $default;
    }

    public static function update_option($key, $value) {
        $options = get_option('wfe_options', []);
        $options[$key] = $value;
        update_option('wfe_options', $options);
    }
}