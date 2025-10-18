<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

final class MH_Elementor_Loader {

    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        if (!did_action('elementor/loaded')) {
            return;
        }

        add_action('elementor/elements/categories_registered', [$this, 'register_widget_category']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'print_inline_editor_styles']);
    }

    public function register_widget_category($elements_manager) {
        $elements_manager->add_category(
            'mh-plug-widgets',
            [
                'title' => esc_html__('MH Plug', 'mh-plug'),
                'icon' => 'eicon-plug',
            ]
        );
    }

    /**
     * Prints the CSS for the widget badge directly into the editor's head.
     * This version uses a highly specific selector based on your provided HTML.
     */
    public function print_inline_editor_styles() {
        ?>
        <style id="mh-plug-editor-badge-styles">
            /*
             * CORRECTED SELECTOR:
             * We target the specific category ID `#elementor-panel-category-mh-plug-widgets`
             * and then the `.elementor-element-wrapper` inside it. This guarantees
             * the style only applies to your widgets and has high specificity.
            */
            #elementor-panel-category-mh-plug-widgets .elementor-element-wrapper {
                position: relative !important;
            }

            #elementor-panel-category-mh-plug-widgets .elementor-element-wrapper::after {
                content: 'MH';
                position: absolute;
                top: 4px;
                right: 4px;
                z-index: 10;
                background-color: #2293e9ff; /* A slightly brighter purple */
                color: #ffffff;
                padding: 2px 6px;
                font-size: 10px;
                line-height: 1;
                font-weight: 600;
                border-radius: 4px;
                text-transform: uppercase;
                box-shadow: 0 1px 2px rgba(0,0,0,0.2);
            }
        </style>
        <?php
    }

    public function register_widgets($widgets_manager) {
        $widget_options = get_option('mh_plug_widgets_settings', []);
        $widget_map = [
            'mh_heading'      => ['file' => 'mh-heading-widget.php', 'class' => 'MH_Heading_Widget'],
            'mh_button'       => ['file' => 'mh-button-widget.php', 'class' => 'MH_Button_Widget'],
            'mh_post_slider'  => ['file' => 'mh-post-slider.php', 'class' => 'MH_Post_Slider_Widget'],
            'mh_post'         => ['file' => 'mh-post-widget.php', 'class' => 'MH_Posts_Widget'],
            'mh_testimonials' => ['file' => 'mh-testimonials-widget.php', 'class' => 'MH_Testimonials_Widget'],
        ];

        foreach ($widget_map as $option_key => $widget_data) {
            $is_enabled = isset($widget_options[$option_key]) ? (bool)$widget_options[$option_key] : true;
            if ($is_enabled) {
                $file_path = MH_PLUG_PATH . 'elementor/widgets/' . $widget_data['file'];
                if (is_readable($file_path)) {
                    require_once $file_path;
                    if (class_exists($widget_data['class'])) {
                        $widgets_manager->register(new $widget_data['class']());
                    }
                }
            }
        }
    }
}

MH_Elementor_Loader::instance();