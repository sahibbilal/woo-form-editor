<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div id="wfe-deactivation-modal" class="wfe-modal-overlay" style="display:none;">
    <div class="wfe-modal-content">
        <h2><?php esc_html_e('Remove Plugin Data?', 'woo-form-editor'); ?></h2>
        <p><?php esc_html_e('Do you want to remove all plugin data (like custom database tables) on deactivation?', 'woo-form-editor'); ?></p>
        <div class="wfe-modal-actions">
            <a href="<?php echo esc_url(wp_nonce_url(add_query_arg('wfe_data', 'keep'), 'wfe_data_choice')); ?>" class="button button-secondary">
                <?php esc_html_e('Keep Data', 'woo-form-editor'); ?>
            </a>
            <a href="<?php echo esc_url(wp_nonce_url(add_query_arg('wfe_data', 'remove'), 'wfe_data_choice')); ?>" class="button button-primary">
                <?php esc_html_e('Remove Data', 'woo-form-editor'); ?>
            </a>
        </div>
    </div>
</div>