<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap mh-plug-admin-wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <form method="post" action="options.php">
        <?php
        // This function outputs the necessary hidden fields for the settings group we registered.
        settings_fields('mh_plug_settings_group');
        ?>

        <div class="mh-accordion">

            <div class="mh-accordion-item">
                <button type="button" class="mh-accordion-header">
                    <?php esc_html_e('Global Settings (Future)', 'mh-plug'); ?>
                    <span class="mh-accordion-icon">+</span>
                </button>
                <div class="mh-accordion-content">
                    <p>More settings will be added here in a future update.</p>
                </div>
            </div>
            <div class="mh-accordion-item">
                <button type="button" class="mh-accordion-header">
                    <?php esc_html_e('Elementor Widgets', 'mh-plug'); ?>
                    <span class="mh-accordion-icon">+</span>
                </button>
                <div class="mh-accordion-content">
                    
                    <div class="mh-widget-controls">
                        <button type="button" class="button button-secondary mh-toggle-all" data-action="enable"><?php esc_html_e('Enable All', 'mh-plug'); ?></button>
                        <button type="button" class="button button-secondary mh-toggle-all" data-action="disable"><?php esc_html_e('Disable All', 'mh-plug'); ?></button>
                    </div>
                    

                    <div class="mh-settings-grid">
                        <?php
                        // This will render all fields from 'mh_plug_widgets_section'
                        global $wp_settings_fields;
                        if (isset($wp_settings_fields['mh-plug-settings-page']['mh_plug_widgets_section'])) {
                            foreach ((array) $wp_settings_fields['mh-plug-settings-page']['mh_plug_widgets_section'] as $field) {
                                call_user_func($field['callback'], $field['args']);
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>

        <?php
        // This function outputs the "Save Changes" button.
        submit_button();
        ?>
    </form>
</div>