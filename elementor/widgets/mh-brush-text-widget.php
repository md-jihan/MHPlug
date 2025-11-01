<?php
/**
 * MH Brush Text Widget
 *
 * A widget that displays text overlaid on a custom brush stroke background image.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;

class MH_Brush_Text_Widget extends Widget_Base {

    public function get_name() {
        return 'mh-brush-text';
    }

    public function get_title() {
        return esc_html__('MH Brush Text', 'mh-plug');
    }

    public function get_icon() {
        // Using an icon from your custom font
        return 'mhi-text'; 
    }

    public function get_categories() {
        // This matches your category in elementor-loader.php
        return ['mh-plug-widgets']; 
    }

    protected function register_controls() {

        // --- Content Tab: Text & Link ---
        $this->start_controls_section(
            'section_content_text',
            [
                'label' => esc_html__('Content', 'mh-plug'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'primary_text',
            [
                'label' => esc_html__('Primary Text', 'mh-plug'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('1 - 2', 'mh-plug'),
                'placeholder' => esc_html__('e.g., 1 - 2', 'mh-plug'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'secondary_text',
            [
                'label' => esc_html__('Secondary Text', 'mh-plug'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('years', 'mh-plug'),
                'placeholder' => esc_html__('e.g., years', 'mh-plug'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'mh-plug'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'mh-plug'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => esc_html__('Alignment', 'mh-plug'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'mh-plug'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'mh-plug'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'mh-plug'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .mh-brush-text-wrapper' => 'align-items: {{VALUE}}; text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // --- Style Tab: Brush Background ---
        $this->start_controls_section(
            'section_style_background',
            [
                'label' => esc_html__('Brush Background', 'mh-plug'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'brush_background',
                'label' => esc_html__('Background', 'mh-plug'),
                'types' => ['classic'], // Only allow image
                'selector' => '{{WRAPPER}} .mh-brush-text-wrapper',
                'fields_options' => [
                    'background' => ['default' => 'classic'],
                    'image' => [
                        'label' => esc_html__('Brush Image', 'mh-plug'),
                        'default' => [
                            // Using your brash.png file as the default
                            'url' => plugin_dir_url(__FILE__) . '../assets/images/brash.png', 
                        ],
                    ],
                    'position' => ['default' => 'center center'],
                    'repeat' => ['default' => 'no-repeat'],
                    'size' => ['default' => 'contain'],
                ],
            ]
        );

        $this->add_responsive_control(
            'brush_min_height',
            [
                'label' => esc_html__('Min Height', 'mh-plug'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 50, 'max' => 500],
                    'vh' => ['min' => 10, 'max' => 100],
                ],
                'default' => ['unit' => 'px', 'size' => 180],
                'size_units' => ['px', 'vh'],
                'selectors' => [
                    '{{WRAPPER}} .mh-brush-text-wrapper' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'brush_padding',
            [
                'label' => esc_html__('Padding', 'mh-plug'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mh-brush-text-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // --- Style Tab: Primary Text ---
        $this->start_controls_section(
            'section_style_primary_text',
            [
                'label' => esc_html__('Primary Text', 'mh-plug'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_text_color',
            [
                'label' => esc_html__('Color', 'mh-plug'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .mh-brush-primary-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'primary_text_typography',
                'selector' => '{{WRAPPER}} .mh-brush-primary-text',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'primary_text_shadow',
                'selector' => '{{WRAPPER}} .mh-brush-primary-text',
            ]
        );

        $this->add_responsive_control(
            'primary_text_spacing',
            [
                'label' => esc_html__('Bottom Spacing', 'mh-plug'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 100]],
                'selectors' => [
                    '{{WRAPPER}} .mh-brush-primary-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // --- Style Tab: Secondary Text ---
        $this->start_controls_section(
            'section_style_secondary_text',
            [
                'label' => esc_html__('Secondary Text', 'mh-plug'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'secondary_text_color',
            [
                'label' => esc_html__('Color', 'mh-plug'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .mh-brush-secondary-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'secondary_text_typography',
                'selector' => '{{WRAPPER}} .mh-brush-secondary-text',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'secondary_text_shadow',
                'selector' => '{{WRAPPER}} .mh-brush-secondary-text',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $wrapper_tag = 'div';
        $wrapper_attrs_string = 'class="mh-brush-text-wrapper"';

        if ( ! empty( $settings['link']['url'] ) ) {
            $wrapper_tag = 'a';
            $this->add_link_attributes( 'link_wrapper', $settings['link'] );
            // Get all attributes as a string, including class
            $wrapper_attrs_string = $this->get_render_attribute_string( 'link_wrapper' );
            // Manually add your class to the attributes
            if (strpos($wrapper_attrs_string, 'class="') !== false) {
                 $wrapper_attrs_string = str_replace( 'class="', 'class="mh-brush-text-wrapper ', $wrapper_attrs_string );
            } else {
                 $wrapper_attrs_string .= ' class="mh-brush-text-wrapper"';
            }
        }
        
        ?>
        <<?php echo $wrapper_tag; ?> <?php echo $wrapper_attrs_string; ?>>
            <?php if ( ! empty( $settings['primary_text'] ) ) : ?>
                <span class="mh-brush-primary-text">
                    <?php echo wp_kses_post( $settings['primary_text'] ); ?>
                </span>
            <?php endif; ?>
            
            <?php if ( ! empty( $settings['secondary_text'] ) ) : ?>
                <span class="mh-brush-secondary-text">
                    <?php echo wp_kses_post( $settings['secondary_text'] ); ?>
                </span>
            <?php endif; ?>
        </<?php echo $wrapper_tag; ?>>
        <?php
    }

    /**
     * Render widget output in the editor (backend).
     */
    protected function _content_template() {
        ?>
        <#
            var wrapperTag = 'div';
            var wrapperAttrs = 'class="mh-brush-text-wrapper"';

            if ( settings.link.url ) {
                wrapperTag = 'a';
                wrapperAttrs = 'href="' + settings.link.url + '" ' + wrapperAttrs;
                if ( settings.link.is_external ) {
                    wrapperAttrs += ' target="_blank"';
                }
                if ( settings.link.nofollow ) {
                    wrapperAttrs += ' rel="nofollow"';
                }
            }
        #>
        <{{{ wrapperTag }}} {{{ wrapperAttrs }}}>
            <# if ( settings.primary_text ) { #>
                <span class="mh-brush-primary-text">
                    {{{ settings.primary_text }}}
                </span>
            <# } #>
            
            <# if ( settings.secondary_text ) { #>
                <span class="mh-brush-secondary-text">
                    {{{ settings.secondary_text }}}
                </span>
            <# } #>
        </{{{ wrapperTag }}}>
        <?php
    }
}