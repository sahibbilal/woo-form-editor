<?php

add_action('wp_ajax_wfe_save_field_order', function () {
    if (
        !current_user_can('manage_options') ||
        !check_ajax_referer('wfe_save_field_order_nonce', '_ajax_nonce', false)
    ) {
        wp_send_json_error(['message' => 'Permission denied or invalid nonce']);
    }

    global $wpdb;
    $table = $wpdb->prefix . 'woo_form_editor_checkout_fields';

    if (!isset($_POST['fields']) || !is_array($_POST['fields'])) {
        wp_send_json_error(['message' => 'No data received']);
    }

    foreach ($_POST['fields'] as $field) {
        $id = intval($field['id']);
        $order = intval($field['field_order']);
        $enabled = isset($field['enabled']) ? intval($field['enabled']) : null;
        $required = isset($field['required']) ? intval($field['required']) : null;

        $updateData = ['field_order' => $order];

        if (!is_null($enabled)) {
            $updateData['enabled'] = $enabled;
        }

        if (!is_null($required)) {
            $updateData['required'] = $required;
        }

        $wpdb->update($table, $updateData, ['id' => $id]);
    }

    wp_send_json_success(['message' => 'Field order updated']);
});


add_action('wp_ajax_wfe_update_fields', function () {
    if (!current_user_can('manage_options') || !check_ajax_referer('wfe_save_field_order_nonce', '_ajax_nonce', false)) {
        wp_send_json_error(['message' => 'Unauthorized']);
    }

    global $wpdb;
    $table = $wpdb->prefix . 'woo_form_editor_checkout_fields';

    if (!isset($_POST['fields']) || !is_array($_POST['fields'])) {
        wp_send_json_error(['message' => 'Invalid or missing data']);
    }

    foreach ($_POST['fields'] as $field) {
        $id = intval($field['id']);
        $update = [];

        if (isset($field['field_order'])) {
            $update['field_order'] = intval($field['field_order']);
        }

        if (isset($field['required'])) {
            $update['required'] = intval($field['required']);
        }

        if (isset($field['enabled'])) {
            $update['enabled'] = intval($field['enabled']);
        }

        if (!empty($update)) {
            $wpdb->update($table, $update, ['id' => $id]);
        }
    }

    wp_send_json_success(['message' => 'Fields updated']);
});
