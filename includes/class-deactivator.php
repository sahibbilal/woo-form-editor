<?php
if (!defined('ABSPATH')) exit;

class WFE_Deactivator {
    public static function deactivate() {
        $remove_data = get_option(WFE_REMOVE_DATA_KEY);

        if ($remove_data) {
            self::drop_database_table();
        }

        delete_option(WFE_REMOVE_DATA_KEY);
        if (class_exists('WFE_Cron_Job')) {
            WFE_Cron_Job::deactivate_cron();
        }
    }

    public static function drop_database_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'woo_form_editor_checkout_fields';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }
}