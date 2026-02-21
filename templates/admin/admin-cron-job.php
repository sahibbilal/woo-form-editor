<?php
if (!defined('ABSPATH')) exit;

class WFE_Cron_Job {

    public static function init() {
        add_action('wfe_sync_woo_checkout_fields', [__CLASS__, 'sync_checkout_fields_to_db']);
    }

    public static function activate_cron() {
        if (!wp_next_scheduled('wfe_sync_woo_checkout_fields')) {
            wp_schedule_event(time(), 'daily', 'wfe_sync_woo_checkout_fields');
        }
    }

    public static function deactivate_cron() {
        wp_clear_scheduled_hook('wfe_sync_woo_checkout_fields');
    }

    public static function sync_checkout_fields_to_db() {
        if (!class_exists('WC_Checkout')) {
            return;
        }

        $checkout_fields = WC()->checkout()->get_checkout_fields();
        global $wpdb;

        foreach ($checkout_fields as $location => $fields) {
            foreach ($fields as $name => $field) {
                // Check if this field already exists in DB
                $exists = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->prefix}woo_form_editor_checkout_fields WHERE location = %s AND name = %s",
                    $location,
                    $name
                ));

                if (!$exists) {
                    $wpdb->insert($wpdb->prefix . 'woo_form_editor_checkout_fields', [
                        'location'        => $location,
                        'name'            => $name,
                        'label'           => $field['label'] ?? '',
                        'type'            => $field['type'] ?? 'text',
                        'required'        => !empty($field['required']) ? 1 : 0,
                        'class'           => !empty($field['class']) ? json_encode($field['class']) : '',
                        'priority'        => $field['priority'] ?? 0,
                        'placeholder'     => $field['placeholder'] ?? '',
                        'default_value'   => $field['default'] ?? '',
                        'enabled'         => 1
                    ]);
                }
            }
        }
    }
}
