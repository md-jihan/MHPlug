<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Import necessary Elementor classes
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

/**
 * MH Site Title Widget Class
 */
class MH_Site_Title_Widget extends Widget_Base {

    public function get_name() {
        return 'mh-site-title';
    }

    public function get_title() {
        return esc_html__('MH Site Title', 'mh-plug');
    }

    public function get_icon() {
        // Use an appropriate icon from your custom library or Elementor's library
        return 'mhi-site-title'; // Example using your custom icon
        // return 'eicon-site-title'; // Alternative Elementor icon
    }

    public function get_categories() {
        return ['mh-plug-widgets']; // Your custom category
    }

    protected function _register_controls() {

        // --- Content Tab ---
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'mh-plug'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'link_to',
            [
                'label' => esc_html__( 'Link', 'mh-plug' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'home',
                'options' => [
                    'none' => esc_html__( 'None', 'mh-plug' ),
                    'home' => esc_html__( 'Home URL', 'mh-plug' ),
                    'custom' => esc_html__( 'Custom URL', 'mh-plug' ),
                ],
            ]
        );

        $this->add_control(
            'custom_link',
            [
                'label' => esc_html__( 'Custom URL', 'mh-plug' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'mh-plug' ),
                'condition' => [
                    'link_to' => 'custom',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title_html_tag',
            [
                'label'   => esc_html__('HTML Tag', 'mh-plug'),
                'type'    => Controls_Manager::SELECT,
                'options' => [ 'h1'=>'H1', 'h2'=>'H2', 'h3'=>'H3', 'h4'=>'H4', 'h5'=>'H5', 'h6'=>'H6', 'p'=>'P', 'div'=>'DIV', 'span'=>'Span' ],
                'default' => 'h1',
            ]
        );

        $this->end_controls_section();


        // --- Style Tab ---
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Style', 'mh-plug'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

         $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__( 'Alignment', 'mh-plug' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => esc_html__( 'Left', 'mh-plug' ), 'icon' => 'eicon-text-align-left', ],
                    'center' => [ 'title' => esc_html__( 'Center', 'mh-plug' ), 'icon' => 'eicon-text-align-center', ],
                    'right' => [ 'title' => esc_html__( 'Right', 'mh-plug' ), 'icon' => 'eicon-text-align-right', ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .mh-site-title-container' => 'text-align: {{VALUE}};', // Target the container for alignment
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'     => esc_html__('Text Color', 'mh-plug'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mh-site-title-content' => 'color: {{VALUE}};', // Target the inner element
                    '{{WRAPPER}} .mh-site-title-content a' => 'color: {{VALUE}};', // Target the link inside
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .mh-site-title-content', // Target the inner element
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'text_shadow',
                'selector' => '{{WRAPPER}} .mh-site-title-content', // Target the inner element
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label'      => esc_html__('Padding', 'mh-plug'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .mh-site-title-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin',
            [
                'label'      => esc_html__('Margin', 'mh-plug'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .mh-site-title-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', // Apply margin to the outer container
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Get the actual site title from WordPress
        $site_title = get_bloginfo('name');
        if (empty($site_title)) {
             if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                 echo '<div style="text-align:center; padding: 20px; border: 1px dashed #ccc;">' . esc_html__('Site Title is Empty. Set one in Settings > General.', 'mh-plug') . '</div>';
             }
             return;
        }

        // Determine the link URL
        $link_url = '';
        $link_attributes_string = '';
        $has_link = false;

        if ($settings['link_to'] === 'home') {
            $link_url = home_url('/');
            $has_link = true;
            $this->add_render_attribute('link', 'href', esc_url($link_url));
            $link_attributes_string = $this->get_render_attribute_string('link');

        } elseif ($settings['link_to'] === 'custom' && !empty($settings['custom_link']['url'])) {
            $link_url = $settings['custom_link']['url'];
            $has_link = true;
            $this->add_link_attributes('link', $settings['custom_link']); // Adds href, target, rel
            $link_attributes_string = $this->get_render_attribute_string('link');
        }

        // Get the chosen HTML tag
        $title_tag = tag_escape($settings['title_html_tag']); // Sanitize the tag

        ?>
        <div class="mh-site-title-container">
            <<?php echo $title_tag; ?> class="mh-site-title-content">
                <?php if ($has_link) : ?>
                    <a <?php echo $link_attributes_string; ?>>
                        <?php echo esc_html($site_title); ?>
                    </a>
                <?php else : ?>
                    <?php echo esc_html($site_title); ?>
                <?php endif; ?>
            </<?php echo $title_tag; ?>>
        </div>
        <?php
    }

     /**
     * Render site title output in the editor.
     * Written as a Backbone JavaScript template and used to generate the live preview.
     * Useful for widgets needing dynamic JS updates without full page refresh.
     * For this simple widget, it's less critical but good practice.
     */
    /* // Optional: Add _content_template for better editor preview performance
    protected function _content_template() {
        ?>
        <#
        var site_title = '<?php echo esc_js(get_bloginfo('name')); ?>';
        var link_url = '';
        var has_link = false;
        var link_attrs = '';
        var title_tag = settings.title_html_tag || 'h1';

        if ( settings.link_to === 'home' ) {
            link_url = '<?php echo esc_js(home_url('/')); ?>';
            has_link = true;
            link_attrs = 'href="' + link_url + '"';
        } else if ( settings.link_to === 'custom' && settings.custom_link.url ) {
            link_url = settings.custom_link.url;
            has_link = true;
            link_attrs = 'href="' + link_url + '"';
            if ( settings.custom_link.is_external ) {
                link_attrs += ' target="_blank"';
            }
            if ( settings.custom_link.nofollow ) {
                link_attrs += ' rel="nofollow"';
            }
        }
        #>
        <div class="mh-site-title-container">
            <{{{ title_tag }}} class="mh-site-title-content">
                <# if ( has_link ) { #>
                    <a {{{ link_attrs }}}>
                        {{{ site_title }}}
                    </a>
                <# } else { #>
                    {{{ site_title }}}
                <# } #>
            </{{{ title_tag }}}>
        </div>
        <?php
    }
    */
}