<?php
if (!defined('ABSPATH')) {
    exit;
}
add_filter('woocommerce_billing_fields', 'wfe_override_billing_fields', 9999);
function wfe_override_billing_fields($fields) {
    global $wpdb;
    $table = $wpdb->prefix . 'woo_form_editor_checkout_fields';

    // Get all enabled billing fields from DB
    $results = $wpdb->get_results("
        SELECT * FROM {$table}
        WHERE enabled = 1 AND location = 'billing'
        ORDER BY field_order ASC
    ");

    if (empty($results)) {
        return $fields;
    }
    $final_fields = [];

    foreach ($results as $row) {
        $key = $row->name; // e.g. billing_first_name

        // Determine the base WooCommerce key (e.g. first_name)
        $short_key = $row->location;

        // If the field exists in original Woo fields, update it
        if (isset($fields[$key])) {
            $final_field['label']       = $row->label;
            $final_field['type']        = $row->type ?: 'text';
            $final_field['required']    = $row->required == 1;
            $final_field['class']       = maybe_unserialize($row->class);
            $final_field['priority']    = intval($row->field_order);
            $final_field['placeholder'] = $row->placeholder;
            $final_field['default']     = $row->default_value;

            $final_fields[$key] = $final_field;
        } else {
            // Add new custom fields
            $final_fields[$key] = [
                'label'       => $row->label,
                'type'        => $row->type ?: 'text',
                'required'    => $row->required == 1,
                'class'       => maybe_unserialize($row->class),
                'priority'    => intval($row->field_order),
                'placeholder' => $row->placeholder,
                'default'     => $row->default_value,
            ];
        }
    }
    return $final_fields;
}


add_filter('woocommerce_shipping_fields', 'wfe_override_shipping_fields', 9999);
function wfe_override_shipping_fields($fields) {
    global $wpdb;
    $table = $wpdb->prefix . 'woo_form_editor_checkout_fields';

    $results = $wpdb->get_results("
        SELECT * FROM {$table}
        WHERE enabled = 1 AND location = 'shipping'
        ORDER BY field_order ASC
    ");

    $custom_fields = [];

    foreach ($results as $row) {
        $key = $row->name;

        $custom_fields[$key] = [
            'label'       => $row->label,
            'type'        => $row->type ?: 'text',
            'required'    => $row->required == 1,
            'class'       => maybe_unserialize($row->class),
            'priority'    => intval($row->priority),
            'placeholder' => $row->placeholder,
            'default'     => $row->default_value,
        ];
    }

    return $custom_fields;
}

