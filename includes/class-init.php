<?php
if (!defined('ABSPATH')) exit;

class WFE_Init {
    private static $instance;

    public static function instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
            self::$instance->includes();
            self::$instance->init_hooks();
            self::$instance->register_assets();
        }
        return self::$instance;
    }

    private function includes() {
        require_once WFE_INC . 'class-admin.php';
        require_once WFE_INC . 'class-frontend.php';
        require_once WFE_INC . 'class-settings.php';
    }

    private function init_hooks() {
        if (is_admin()) {
            new WFE_Admin();
        }
        new WFE_Frontend();
    }

    public function register_assets() {
        add_action('admin_enqueue_scripts', [$this, 'load_admin_assets']);
        add_action('wp_enqueue_scripts', [$this, 'load_frontend_assets']);
    }

    public function load_admin_assets() {
        wp_enqueue_script('jquery-ui-sortable');

        $keep = wp_nonce_url(admin_url('plugins.php?wfe_data=keep'), 'wfe_data_choice');
        $remove = wp_nonce_url(admin_url('plugins.php?wfe_data=remove'), 'wfe_data_choice');
        wp_enqueue_style(
            'wfe-backend-style',
            WFE_ASSETS . 'admin/admin.css',
            [],
            '1.0.'. time()
        );
        wp_enqueue_script(
            'wfe-backend-script',
            WFE_ASSETS . 'admin/admin.js',
            ['jquery'],
            '1.0.'. time(),
            true
        );
        wp_localize_script('wfe-backend-script', 'wfeAdminVars', [
            'pluginSlug'      => $plugin_slug,
            'keepDataUrl'     => esc_url($keep),
            'removeDataUrl'   => esc_url($remove),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wfe_save_field_order_nonce'),
        ]);
    }

    public function load_frontend_assets() {
        wp_enqueue_style('wfe-frontend-style', WFE_ASSETS . 'frontend/frontend.css', [], '1.0');
        wp_enqueue_script('wfe-frontend-script', WFE_ASSETS . 'frontend/frontend.js', ['jquery'], '1.0', true);
    }
}