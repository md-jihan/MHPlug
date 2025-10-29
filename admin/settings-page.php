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
                <div class="mh-accordion-header">
                    <span class="mh-accordion-title"><?php esc_html_e('Global Settings (Future)', 'mh-plug'); ?></span>
                   <?php // Add an inner wrapper for the right-side elements ?>
                    <span class="mh-header-controls">
                        <span class="mh-widget-controls">
                            <button type="button" class="button button-small mh-toggle-all" data-action="enable"><?php esc_html_e('Enable All', 'mh-plug'); ?></button>
                            <button type="button" class="button button-small mh-toggle-all" data-action="disable"><?php esc_html_e('Disable All', 'mh-plug'); ?></button>
                        </span>
                        <span class="mh-accordion-icon">+</span>
                    </span>
                </div>
                <div class="mh-accordion-content">
                    
                    <?php // --- ADD A TITLE WITH ICON --- ?>
                    <h3 class="mh-section-header">
                        <span class="dashicons dashicons-admin-generic"></span> <?php // WordPress Dashicon ?>
                        <?php esc_html_e('General Features', 'mh-plug'); ?>
                    </h3>
                
                    <?php // --- RENDER THE GLOBAL SETTINGS SECTION --- ?>
                    <div class="mh-settings-grid">
                        <?php
                        global $wp_settings_fields;
                        if (isset($wp_settings_fields['mh-plug-settings-page']['mh_plug_global_settings_section'])) {
                            foreach ((array) $wp_settings_fields['mh-plug-settings-page']['mh_plug_global_settings_section'] as $field) {
                                call_user_func($field['callback'], $field['args']);
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="mh-accordion-item">
                <?php // Change header from button to div ?>
                <div class="mh-accordion-header">
                    <span class="mh-accordion-title"><?php esc_html_e('Elementor Widgets', 'mh-plug'); ?></span>
                    <?php // Add an inner wrapper for the right-side elements ?>
                    <span class="mh-header-controls">
                        <span class="mh-widget-controls">
                            <button type="button" class="button button-small mh-toggle-all" data-action="enable"><?php esc_html_e('Enable All', 'mh-plug'); ?></button>
                            <button type="button" class="button button-small mh-toggle-all" data-action="disable"><?php esc_html_e('Disable All', 'mh-plug'); ?></button>
                        </span>
                        <span class="mh-accordion-icon">+</span>
                    </span>
                </div>
                <div class="mh-accordion-content">
                     <?php // Remove controls div from here ?>
                    <div class="mh-settings-grid">
                        <?php
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