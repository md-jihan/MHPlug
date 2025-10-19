<?php
// Exit if this file is called directly to prevent security vulnerabilities.
if (!defined('ABSPATH')) {
    exit;
}

// These 'use' statements import all necessary Elementor classes.
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Repeater;

/**
 * MH Advanced Heading Widget Class
 * Final Corrected Version with Full Interactivity and Advanced Underline
 */
class MH_Heading_Widget extends Widget_Base {

    public function get_name() {
        return 'mh-heading';
    }

    public function get_title() {
        return esc_html__('MH Heading', 'mh-plug');
    }

    public function get_icon() {
        return 'eicon-t-letter';
    }

    public function get_categories() {
        return ['mh-plug-widgets'];
    }

    protected function _register_controls() {

        // --- Content Tab: Heading Parts (Repeater) ---
        $this->start_controls_section(
            'section_heading_parts',
            [
                'label' => esc_html__('Heading Parts', 'mh-plug'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        // Content Control inside Repeater
        $repeater->add_control(
            'part_text',
            [
                'label'   => esc_html__('Text', 'mh-plug'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Text Part', 'mh-plug'),
                'label_block' => true,
                'dynamic' => ['active' => true],
            ]
        );

        // --- Start of Style Tab inside Repeater ---
        $repeater->add_control(
            'part_styles_heading',
            [
                'label' => esc_html__('Styling', 'mh-plug'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Style Controls inside Repeater
        $repeater->add_control(
            'part_color',
            [
                'label'     => esc_html__('Text Color', 'mh-plug'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    // CORRECTED SELECTOR: {{CURRENT_ITEM}} makes it interactive.
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $repeater->add_control(
            'part_background_color',
            [
                'label'     => esc_html__('Background Color', 'mh-plug'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'part_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
            ]
        );

        $repeater->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'part_text_shadow',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
            ]
        );
        
        // --- End of Style Tab inside Repeater ---

        // Add the repeater control to the section
        $this->add_control(
            'heading_parts',
            [
                'label'   => esc_html__('Text Parts', 'mh-plug'),
                'type'    => Controls_Manager::REPEATER,
                'fields'  => $repeater->get_controls(),
                'default' => [
                    ['part_text' => esc_html__('Advanced', 'mh-plug')],
                    ['part_text' => esc_html__('Heading', 'mh-plug')],
                ],
                'title_field' => '{{{ part_text }}}',
            ]
        );
        
        $this->end_controls_section();

        // --- Content Tab: General Settings ---
        $this->start_controls_section(
            'section_general_settings',
            [
                'label' => esc_html__('General Settings', 'mh-plug'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'heading_alignment',
            [
                'label'   => esc_html__('Alignment', 'mh-plug'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [ 'left' => ['title' => esc_html__('Left', 'mh-plug'), 'icon' => 'eicon-text-align-left'], 'center' => ['title' => esc_html__('Center', 'mh-plug'), 'icon' => 'eicon-text-align-center'], 'right' => ['title' => esc_html__('Right', 'mh-plug'), 'icon' => 'eicon-text-align-right'], ],
                'default'   => 'left',
                'selectors' => [ '{{WRAPPER}} .mh-advanced-heading-wrapper' => 'text-align: {{VALUE}};' ],
            ]
        );
        
        $this->add_control(
            'heading_html_tag',
            [ 'label' => esc_html__('HTML Tag', 'mh-plug'), 'type' => Controls_Manager::SELECT, 'options' => [ 'h1'=>'H1', 'h2'=>'H2', 'h3'=>'H3', 'h4'=>'H4', 'h5'=>'H5', 'h6'=>'H6', 'p'=>'P', 'div'=>'DIV' ], 'default' => 'h2' ]
        );

        $this->end_controls_section();

        // --- Style Tab: Underline ---
        $this->start_controls_section(
            'section_underline_style',
            [ 'label' => esc_html__('Underline', 'mh-plug'), 'tab' => Controls_Manager::TAB_STYLE ]
        );

        $this->add_control(
            'underline_apply_to',
            [
                'label' => esc_html__('Apply Underline To', 'mh-plug'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'all' => esc_html__('All Parts', 'mh-plug'),
                    'last' => esc_html__('Last Part Only', 'mh-plug'),
                ],
                'default' => 'all',
            ]
        );

        $this->add_control(
            'underline_style',
            [
                'label' => esc_html__('Style', 'mh-plug'),
                'type' => Controls_Manager::SELECT,
                'options' => [ 'none' => esc_html__('None', 'mh-plug'), 'solid' => esc_html__('Solid', 'mh-plug'), 'dotted' => esc_html__('Dotted', 'mh-plug'), 'dashed' => esc_html__('Dashed', 'mh-plug'), 'wavy' => esc_html__('Wavy', 'mh-plug'), 'custom' => esc_html__('Custom Wavy', 'mh-plug') ],
                'default' => 'none',
            ]
        );

        $this->add_control(
            'underline_color',
            [
                'label' => esc_html__('Color', 'mh-plug'),
                'type' => Controls_Manager::COLOR,
                'condition' => [ 'underline_style!' => 'none' ],
                'selectors' => [
                    '{{WRAPPER}} .mh-underline' => 'text-decoration-color: {{VALUE}};',
                    // This is for the custom wavy SVG
                    '{{WRAPPER}} .mh-underline-custom::after' => '--underline-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'underline_size',
            [
                'label' => esc_html__('Thickness', 'mh-plug'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 1, 'max' => 20]],
                'default' => ['unit' => 'px', 'size' => 3],
                'condition' => [ 'underline_style!' => 'none' ],
                'selectors' => [
                    '{{WRAPPER}} .mh-underline' => 'text-decoration-thickness: {{SIZE}}{{UNIT}};',
                     // This is for the custom wavy SVG
                    '{{WRAPPER}} .mh-underline-custom::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'underline_position',
            [
                'label' => esc_html__('Position', 'mh-plug'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [ 'title' => esc_html__('Top', 'mh-plug'), 'icon' => 'eicon-v-align-top' ],
                    'bottom' => [ 'title' => esc_html__('Bottom', 'mh-plug'), 'icon' => 'eicon-v-align-bottom' ],
                ],
                'default' => 'bottom',
                'toggle' => false,
                'condition' => [ 'underline_style' => 'custom' ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tag = esc_attr($settings['heading_html_tag']);
        $last_item_index = count($settings['heading_parts']) - 1;

        // Start the main wrapper
        echo "<$tag class='mh-advanced-heading-wrapper'>";

        // Loop through each heading part from the repeater
        foreach ($settings['heading_parts'] as $index => $item) {
            $repeater_setting_key = $this->get_repeater_setting_key('part_text', 'heading_parts', $index);
            
            $part_classes = ['mh-heading-part', 'elementor-repeater-item-' . $item['_id']];

            // Check if underline should be applied
            if ($settings['underline_style'] !== 'none') {
                if ($settings['underline_apply_to'] === 'all' || ($settings['underline_apply_to'] === 'last' && $index === $last_item_index)) {
                    if ($settings['underline_style'] === 'custom') {
                        $part_classes[] = 'mh-underline-custom';
                        $part_classes[] = 'mh-underline-position-' . $settings['underline_position'];
                    } else {
                        $part_classes[] = 'mh-underline';
                    }
                }
            }

            $this->add_render_attribute($repeater_setting_key, 'class', $part_classes);
            $this->add_inline_editing_attributes($repeater_setting_key, 'none');

            echo '<span ' . $this->get_render_attribute_string($repeater_setting_key) . '>';
            echo esc_html($item['part_text']);
            echo '</span>';
        }

        echo "</$tag>";

        // If custom underline is chosen, print the necessary inline CSS
        if ($settings['underline_style'] === 'custom') {
            ?>
            <style>
                .elementor-element-<?php echo $this->get_id(); ?> .mh-underline-custom {
                    text-decoration: none !important;
                    position: relative;
                    display: inline-block;
                }
                .elementor-element-<?php echo $this->get_id(); ?> .mh-underline-custom::after {
                    content: '';
                    position: absolute;
                    left: 0;
                    width: 100%;
                    --underline-color: <?php echo esc_attr($settings['underline_color']); ?>; /* Fallback */
                    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 10' preserveAspectRatio='none'%3E%3Cpath d='M0,5 Q25,-1 50,5 T100,5' stroke='currentColor' stroke-width='3' fill='none'/%3E%3C/svg%3E");
                    background-repeat: no-repeat;
                    background-size: 100% 100%;
                    color: var(--underline-color); /* The SVG stroke uses this color */
                }
                .elementor-element-<?php echo $this->get_id(); ?> .mh-underline-position-bottom::after {
                    bottom: 0;
                    transform: translateY(100%);
                }
                .elementor-element-<?php echo $this->get_id(); ?> .mh-underline-position-top::after {
                    top: 0;
                    transform: translateY(-100%);
                }
            </style>
            <?php
        }
    }
}