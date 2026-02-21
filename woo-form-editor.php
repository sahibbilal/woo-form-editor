<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://wpcorex.com
 * @since             1.0.0
 * @package           Woo_Form_Editor
 *
 * @wordpress-plugin
 * Plugin Name:       Woo Form Editor
 * Plugin URI:        https://wpcorex.com
 * Description:       Customize WooCommerce checkout/account/product forms with ease.
 * Version:           1.0.0
 * Author:            WP Corex
 * Author URI:        https://wpcorex.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-form-editor
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define constants
define('WFE_PATH', plugin_dir_path(__FILE__));
define('WFE_URL', plugin_dir_url(__FILE__));
define('WFE_INC', WFE_PATH . 'includes/');
define('WFE_ASSETS', WFE_URL . 'assets/');
define('WFE_REMOVE_DATA_KEY', 'wfe_remove_data_on_deactivation');

require_once WFE_PATH . 'templates/admin/admin-cron-job.php';
WFE_Cron_Job::init();

require_once WFE_INC . 'class-activator.php';
require_once WFE_INC . 'class-deactivator.php';
register_activation_hook(__FILE__, ['WFE_Activator', 'activate']);
register_deactivation_hook(__FILE__, ['WFE_Deactivator', 'deactivate']);

add_action('plugins_loaded', 'wfe_plugins_loaded');
function wfe_plugins_loaded() {
    if (class_exists('WooCommerce')) {
        txtdomain_load();
        require_once WFE_INC . 'class-init.php';
        WFE_Init::instance();
        add_filter('woocommerce_checkout_fields', 'wfe_override_checkout_fields', 9999);
    }
}

function txtdomain_load() {
    load_plugin_textdomain('woo-form-editor', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
