<?php
if (!defined('ABSPATH')) {
    exit;
}

class WFE_Frontend {

    public function __construct() {
        add_filter('woocommerce_checkout_get_checkout_fields', function($fields) {
            return $fields;
        });
        require_once WFE_PATH . 'templates/frontend/wfe-form-fields-form-ordering.php';
    }

}
