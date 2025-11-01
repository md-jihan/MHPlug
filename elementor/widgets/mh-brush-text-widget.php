<?php
// elementor/widgets/mh-brush-text-widget.php

namespace MH_Plug\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Elementor MH Brush Text Widget
 *
 * @since 1.0.0
 */
class MH_Brush_Text_Widget extends Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name() {
        return 'mh-brush-text';
    }

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('MH Brush Text', 'mh-plug');
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-paint-brush'; // A relevant icon for brush/paint
    }

    /**
     * Get widget categories.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['mh-plug-widgets']; // Your custom category
    }

    /**
     * Get custom help URL.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget help URL.
     */
    public function get_custom_help_url() {
        return 'https://yourwebsite.com/docs/mh-brush-text'; // Replace with your documentation URL
    }

    /**
     * Register MH Brush Text widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {

        // --- Content Tab ---
        $this->start_controls_section(
            'content_section',
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
                'placeholder' => esc_html__('Enter your text', 'mh-plug'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'secondary_text',
            [
                'label' => esc_html__('Secondary Text', 'mh-plug'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('years', 'mh-plug'),
                'placeholder' => esc_html__('Enter secondary text', 'mh-plug'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'mh-plug'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'mh-plug'),
                'options' => ['url', 'is_external', 'nofollow'],
                'default' => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                ],
                'dynamic' => [
                    'active' => true,
                ],
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
                    '{{WRAPPER}} .mh-brush-text-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // --- Style Tab: Background Brush Image ---
        $this->start_controls_section(
            'brush_background_section',
            [
                'label' => esc_html__('Brush Background', 'mh-plug'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // We'll use Elementor's Group_Control_Background for the brush image
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'brush_image_background',
                'label' => esc_html__('Brush Image', 'mh-plug'),
                'types' => ['classic'], // Only allow classic (image) background
                'selector' => '{{WRAPPER}} .mh-brush-text-wrapper',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'image' => [
                        'label' => esc_html__('Brush Image', 'mh-plug'),
                        'description' => esc_html__('Upload a brush stroke or splatter image.', 'mh-plug'),
                        'default' => [ // You can provide a default image here if you have one
                            'url' => plugin_dir_url(__FILE__) . '../assets/images/brush.png', // Example path
                        ],
                    ],
                    'position' => [
                        'default' => 'center center',
                    ],
                    'repeat' => [
                        'default' => 'no-repeat',
                    ],
                    'size' => [
                        'default' => 'contain', // Ensure the image fits well
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'brush_min_height',
            [
                'label' => esc_html__('Min Height', 'mh-plug'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 180, // Adjust default height as needed
                ],
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
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mh-brush-text-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => 30,
                    'right' => 20,
                    'bottom' => 30,
                    'left' => 20,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
            ]
        );

        $this->end_controls_section();

        // --- Style Tab: Primary Text ---
        $this->start_controls_section(
            'primary_text_style_section',
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
                'selectors' => [
                    '{{WRAPPER}} .mh-brush-primary-text' => 'color: {{VALUE}};',
                ],
                'default' => '#000000',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'primary_text_typography',
                'selector' => '{{WRAPPER}} .mh-brush-primary-text',
                'label' => esc_html__('Typography', 'mh-plug'),
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => ['default' => 'Arial'],
                    'font_size' => ['default' => ['size' => 42, 'unit' => 'px']],
                    'font_weight' => ['default' => 'bold'],
                ],
            ]
        );

        $this->add_responsive_control(
            'primary_text_spacing',
            [
                'label' => esc_html__('Bottom Spacing', 'mh-plug'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mh-brush-primary-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // --- Style Tab: Secondary Text ---
        $this->start_controls_section(
            'secondary_text_style_section',
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
                'selectors' => [
                    '{{WRAPPER}} .mh-brush-secondary-text' => 'color: {{VALUE}};',
                ],
                'default' => '#000000',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'secondary_text_typography',
                'selector' => '{{WRAPPER}} .mh-brush-secondary-text',
                'label' => esc_html__('Typography', 'mh-plug'),
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => ['default' => 'Arial'],
                    'font_size' => ['default' => ['size' => 18, 'unit' => 'px']],
                    'font_weight' => ['default' => 'normal'],
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render MH Brush Text widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $link_html = '';
        if (!empty($settings['link']['url'])) {
            $this->add_link_attributes('link', $settings['link']);
            $link_html = '<a ' . $this->get_render_attribute_string('link') . '>';
        }
        ?>
        <div class="mh-brush-text-wrapper">
            <?php echo $link_html; ?>
            <div class="mh-brush-primary-text"><?php echo esc_html($settings['primary_text']); ?></div>
            <div class="mh-brush-secondary-text"><?php echo esc_html($settings['secondary_text']); ?></div>
            <?php if (!empty($settings['link']['url'])) { echo '</a>'; } ?>
        </div>
        <?php
    }

    /**
     * Render MH Brush Text widget output in the Elementor editor.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _content_template() {
        ?>
        <#
        var link_url = settings.link.url;
        var link_html = '';
        if ( link_url ) {
            link_html = '<a href="' + link_url + '"';
            if ( settings.link.is_external ) {
                link_html += ' target="_blank"';
            }
            if ( settings.link.nofollow ) {
                link_html += ' rel="nofollow"';
            }
            link_html += '>';
        }
        #>
        <div class="mh-brush-text-wrapper">
            {{{ link_html }}}
            <div class="mh-brush-primary-text">{{{ settings.primary_text }}}</div>
            <div class="mh-brush-secondary-text">{{{ settings.secondary_text }}}</div>
            <# if ( link_url ) { #></a><# } #>
        </div>
        <?php
    }
}