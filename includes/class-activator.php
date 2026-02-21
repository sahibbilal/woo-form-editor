<?php
if (!defined('ABSPATH')) exit;

class WFE_Activator {
    public static function activate() {
        if (!is_plugin_active('woocommerce/woocommerce.php') && !class_exists('WooCommerce')) {
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die(
                __('WooCommerce must be installed and active before using Woo Form Editor.', 'woo-form-editor'),
                __('Plugin Activation Error', 'woo-form-editor'),
                ['back_link' => true]
            );
        }
        self::create_database_table();
        self::insert_default_checkout_fields();
        if (class_exists('WFE_Cron_Job')) {
            WFE_Cron_Job::activate_cron();
        }
    }

    private static function create_database_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'woo_form_editor_checkout_fields';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            location VARCHAR(50) NOT NULL, -- billing | shipping | order
            name VARCHAR(100) NOT NULL, -- e.g. billing_my_custom_field
            label VARCHAR(255) DEFAULT '',
            type VARCHAR(50) DEFAULT 'text', -- text, select, checkbox, etc.
            required TINYINT(1) DEFAULT 0,
            class TEXT DEFAULT NULL, -- JSON encoded array
            priority INT DEFAULT 10,
            field_order INT DEFAULT 0, -- used to sort fields by position
            default_value TEXT DEFAULT NULL,
            placeholder TEXT DEFAULT NULL,
            options TEXT DEFAULT NULL, -- for dropdowns etc (JSON)
            enabled TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_field (location, name)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public static function insert_default_checkout_fields() {
        if (!class_exists('WooCommerce')) return;

        global $wpdb;
        $checkout_fields = WC()->checkout()->get_checkout_fields();
        $table = $wpdb->prefix . 'woo_form_editor_checkout_fields';
        $order = 0;
        foreach ($checkout_fields as $location => $fields) {
            foreach ($fields as $name => $field) {
                $wpdb->insert($table, [
                    'location'       => $location,
                    'name'           => $name,
                    'label'          => $field['label'] ?? '',
                    'type'           => $field['type'] ?? '',
                    'required'       => !empty($field['required']) ? 1 : 0,
                    'class'          => !empty($field['class']) ? json_encode($field['class']) : '',
                    'priority'       => $field['priority'] ?? 10,
                    'field_order'    => $order++,
                    'placeholder'    => $field['placeholder'] ?? '',
                    'default_value'  => $field['default'] ?? '',
                    'enabled'        => 1
                ]);
            }
        }
    }
}
