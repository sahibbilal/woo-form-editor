<?php
if (!defined('ABSPATH')) exit;

global $wpdb;
$table_name = $wpdb->prefix . 'woo_form_editor_checkout_fields';
$fields = $wpdb->get_results("
    SELECT * FROM {$table_name}
    ORDER BY field_order ASC, priority ASC
");

?>
<div class="wrap">
    <h1>WooCommerce Form Fields</h1>

    <?php if (current_user_can('manage_options')) : ?>
        <?php
        $cron_ran = false;
        if (isset($_POST['wfe_run_cron_now']) && check_admin_referer('wfe_run_cron_now', 'wfe_run_cron_now_nonce')) {
            $cron_ran = true;
            do_action('wfe_sync_woo_checkout_fields');
        }
        ?>

        <div id="wfe-cron-container">
            <?php if (!$cron_ran): ?>
                <form method="post">
                    <?php wp_nonce_field('wfe_run_cron_now', 'wfe_run_cron_now_nonce'); ?>
                    <input type="submit" name="wfe_run_cron_now" class="button button-primary" value="Run Cron Job Now">
                </form>
            <?php endif; ?>

            <?php if ($cron_ran): ?>
                <div class="notice notice-success is-dismissible" id="wfe-success-msg">
                    <p><strong>Cron job executed successfully!</strong></p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form id="wfe-save-order-form">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th class="wfe-drag-handle"></th>
                    <th>Label</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Required</th>
                    <th>Priority</th>
                    <th>Order</th>
                    <th>Placeholder</th>
                    <th>Enabled</th>
                </tr>
            </thead>
            <tbody id="sortable-fields">
                <?php foreach ($fields as $field): ?>
                    <tr data-id="<?php echo esc_attr($field->id); ?>">
                        <td class="wfe-drag-handle"><span class="dashicons dashicons-menu"></span></td>
                        <td><?php echo esc_html($field->label); ?></td>
                        <td><?php echo esc_html($field->name); ?></td>
                        <td><?php echo esc_html($field->type); ?></td>
                        <td><?php echo esc_html($field->location); ?></td>
                        <td>
                            <label class="wfe-switch">
                                <input type="checkbox" class="wfe-required-toggle" <?= $field->required ? 'checked' : ''; ?>>
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td><?php echo (int) $field->priority; ?></td>
                        <td class="field-order"><?php echo (int) $field->field_order; ?></td>
                        <td><?php echo esc_html($field->placeholder); ?></td>
                        <td> 
                            <label class="wfe-switch">
                                <input type="checkbox" class="wfe-enabled-toggle" <?= $field->enabled ? 'checked' : ''; ?>>
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p>
            <button type="button" id="save-field-order" class="button button-primary">Save Order</button>
        </p>
    </form>
</div>
