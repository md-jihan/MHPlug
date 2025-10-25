<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * MH_Admin_Menu Class
 * Final Hybrid Version: Injects menu styles and enqueues page styles.
 */

class MH_Admin_Menu {

    // --- CHANGE THIS PROPERTY ---
    // Use an associative array for better management.
    // Key => Internal Name, Value => Display Name
    private $widgets = [
        'mh_heading'      => 'Advanced Heading',
        'mh_site_logo'    => 'Site Logo', // <-- ADD THIS LINE
        'mh_site_title'   => 'Site Title', // <-- ADD THIS LINE
    ];

    public function __construct() {
        add_action('admin_menu', [$this, 'register_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        
        // Hook to inject critical menu styles on ALL admin pages.
        add_action('admin_head', [$this, 'add_menu_inline_styles']);

        // Hook to load page-specific assets (CSS for accordion, JS).
        add_action('admin_enqueue_scripts', [$this, 'enqueue_page_assets']);
    }

    public function register_menu() {
        add_menu_page(
            esc_html__('MH Plug Settings', 'mh-plug'),
            'MH Plug',
            'manage_options',
            'mh-plug-settings',
            [$this, 'render_settings_page'],
            'dashicons-admin-generic', // Placeholder
            58
        );
    }

    /**
     * Injects ONLY the menu icon and background styles into the <head>.
     */
    public function add_menu_inline_styles() {
        ?>
<style id="mh-plug-menu-styles">
/* Default (Inactive) State Icon */
#adminmenu #toplevel_page_mh-plug-settings .wp-menu-image {
    background-image: url('<?php echo esc_url(MH_PLUG_URL . 'admin/assets/images/MH-icon.png'); ?>') !important;
    background-repeat: no-repeat !important;
    background-position: center center !important;
    background-size: 20px auto !important;
}

/* Hide placeholder Dashicon */
#adminmenu #toplevel_page_mh-plug-settings .wp-menu-image::before {
    content: '' !important;
}

/* Active & Hover State Background */
#adminmenu li#toplevel_page_mh-plug-settings:hover a,
#adminmenu li.current#toplevel_page_mh-plug-settings a,
#adminmenu li.wp-has-current-submenu#toplevel_page_mh-plug-settings a {
    background: #004265 !important;
    color: #fff !important;
}
</style>
<?php
    }

    /**
     * Enqueues assets ONLY for the plugin's settings page.
     */
    public function enqueue_page_assets($hook) {
        // This ensures these files only load on our settings page, not the entire admin area.
        if ('toplevel_page_mh-plug-settings' !== $hook) {
            return;
        }

        // Enqueue the external stylesheet for accordion and widget cards.
        $css_version = filemtime(MH_PLUG_PATH . 'admin/assets/css/admin-styles.css');
        wp_enqueue_style('mh-plug-admin-styles', MH_PLUG_URL . 'admin/assets/css/admin-styles.css', [], $css_version);
        
        // Enqueue the JavaScript for the accordion.
        wp_enqueue_script('mh-plug-admin-scripts', MH_PLUG_URL . 'admin/assets/js/admin-scripts.js', ['jquery'], MH_PLUG_VERSION, true);
    }
    
    // --- The rest of the functions are unchanged ---
    public function render_settings_page() { require_once MH_PLUG_PATH . 'admin/settings-page.php'; }
    public function register_settings() {
        register_setting(
            'mh_plug_settings_group',
            'mh_plug_widgets_settings',
            [$this, 'sanitize_widgets_settings'] // <-- Add this sanitize callback
        );
        add_settings_section('mh_plug_widgets_section', null, null, 'mh-plug-settings-page');
        // Use the new class property to loop through widgets
        foreach ($this->widgets as $key => $label) {
            add_settings_field($key, $label, [$this, 'render_widget_toggle_field'],
            'mh-plug-settings-page', 'mh_plug_widgets_section', ['id' => $key, 'label' => $label]);
        }
    }
    public function render_widget_toggle_field($args) {
        $options = get_option('mh_plug_widgets_settings'); $id = esc_attr($args['id']);
        $checked = isset($options[$id]) ? checked($options[$id], 1, false) : 'checked';
        echo "<div class='mh-widget-card'><div class='mh-widget-card-header'><div class='mh-widget-title'>" . esc_html($args['label']) . "</div><label class='mh-switch'><input type='checkbox' name='mh_plug_widgets_settings[{$id}]' value='1' {$checked} /><span class='mh-slider mh-round'></span></label></div></div>";
    }

    /**
     * --- ADD THIS ENTIRE NEW FUNCTION ---
     * Sanitize Callback for Widget Settings.
     * This function ensures that unchecked boxes are saved as '0' (off).
     * @param array $input The raw data submitted from the form.
     * @return array The cleaned data to be saved.
     */
    public function sanitize_widgets_settings($input) {
        // --- MODIFY THIS FUNCTION ---
        $sanitized_data = [];

        // Get all the valid keys from our new 'widgets' property.
        $widget_keys = array_keys($this->widgets);

        foreach ($widget_keys as $widget_key) {
            if (isset($input[$widget_key]) && $input[$widget_key] == '1') {
                $sanitized_data[$widget_key] = 1;
            } else {
                $sanitized_data[$widget_key] = 0;
            }
        }
        return $sanitized_data;
    }
}

new MH_Admin_Menu();