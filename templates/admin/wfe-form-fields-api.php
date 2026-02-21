<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', function () {
    register_rest_route('wfe/v1', '/fields', [
        'methods' => 'POST',
        'callback' => 'wfe_add_checkout_field',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
        'args' => [
            'location' => ['required' => true],
            'name' => ['required' => true],
            'label' => ['required' => true],
            'type' => ['required' => true],
        ],
    ]);

    register_rest_route('wfe/v1', '/fields', [
        'methods' => 'GET',
        'callback' => 'wfe_get_checkout_fields',
        'permission_callback' => '__return_true',
    ]);
});

function wfe_add_checkout_field($request) {
    global $wpdb;

    $table = $wpdb->prefix . 'woo_form_editor_checkout_fields';

    $data = [
        'location'       => sanitize_text_field($request['location']),
        'name'           => sanitize_text_field($request['name']),
        'label'          => sanitize_text_field($request['label']),
        'type'           => sanitize_text_field($request['type']),
        'required'       => isset($request['required']) ? intval($request['required']) : 0,
        'class'          => isset($request['class']) ? json_encode((array) $request['class']) : json_encode([]),
        'priority'       => isset($request['priority']) ? intval($request['priority']) : 10,
        'default_value'  => isset($request['default_value']) ? sanitize_text_field($request['default_value']) : '',
        'placeholder'    => isset($request['placeholder']) ? sanitize_text_field($request['placeholder']) : '',
        'options'        => isset($request['options']) ? json_encode($request['options']) : null,
        'enabled'        => 1,
    ];

    $result = $wpdb->insert($table, $data);

    if ($result === false) {
        return new WP_Error('db_insert_error', 'Failed to insert field.', ['status' => 500]);
    }

    return [
        'success' => true,
        'id' => $wpdb->insert_id
    ];
}

function wfe_get_checkout_fields($request) {
    global $wpdb;

    $fields = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}woo_form_editor_checkout_fields ORDER BY priority ASC", ARRAY_A);

    // Decode JSON fields
    foreach ($fields as &$field) {
        $field['class'] = json_decode($field['class'], true);
        $field['options'] = json_decode($field['options'], true);
    }

    return $fields;
}