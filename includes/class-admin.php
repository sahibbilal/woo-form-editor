<?php
if (!defined('ABSPATH')) {
    exit;
}

class WFE_Admin {

    public function __construct() {
        $plugin_instance = WFE_Init::instance();
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'wfe_handle_data_retention_choice']);
        add_action('admin_footer-plugins.php', [$this, 'wfe_deactivation_modal_prompt']);
        
        add_filter('plugin_action_links_woo-form-editor/woo-form-editor.php', [$this, 'wfe_plugin_settings_link']);

        require_once WFE_PATH . 'templates/admin/wfe-form-fields-api.php';
        require_once WFE_PATH . 'templates/admin/admin-ajax.php';
    }

    public function add_admin_menu() {
        $parent_slug = 'woocommerce';
        add_submenu_page(
            $parent_slug,
            __('Woo Field Editor', 'woo-form-editor'), // Page title
            __('Woo Field Editor', 'woo-form-editor'), // Menu title
            'manage_options',        // Capability
            'woo-form-editor',            // Menu slug
            [$this, 'render_admin_page'], // Callback function
            21                       // Position (WooCommerce uses 10–30 range, "Products" is 20)
        );
    }

    public function render_admin_page() {
        $template_path = WFE_PATH . 'templates/admin/admin-checkout.php';
        if (file_exists($template_path)) {
            include $template_path;
        } else {
            echo '<div class="notice notice-error"><p>Admin template not found.</p></div>';
        }
    }

    public function wfe_handle_data_retention_choice() {
        if (isset($_GET['wfe_data']) && check_admin_referer('wfe_data_choice')) {
            $choice = sanitize_text_field($_GET['wfe_data']);
            update_option(WFE_REMOVE_DATA_KEY, $choice === 'remove');
        }
    }

    public function wfe_deactivation_modal_prompt() {
        $plugin_slug = 'woo-form-editor/woo-form-editor.php';
        require_once WFE_PATH . 'templates/admin/deactivation-modal.php';

        if (!file_exists($modal_template)) return;
        ?>
        <div id="wfe-deactivation-modal-container">
            <?php include $modal_template; ?>
        </div>
        <?php
    }

    public function wfe_plugin_settings_link($links) {
        $settings_link = '<a href="' . admin_url('admin.php?page=woo-form-editor') . '">' . __('Settings', 'wfe') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

}
