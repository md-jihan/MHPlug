<?php
// Exit if this file is called directly to prevent security vulnerabilities.
if (!defined('ABSPATH')) {
    exit;
}

// These 'use' statements import necessary Elementor classes.
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

/**
 * MH Heading Widget Class
 *
 * This class defines the structure and behavior of the custom heading widget.
 */
class MH_Heading_Widget extends Widget_Base {

    /**
     * Get Widget Name.
     *
     * Returns a unique internal name for the widget.
     * This is used in the code and should be in kebab-case.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'mh-heading';
    }

    /**
     * Get Widget Title.
     *
     * Returns the user-facing title of the widget, which is displayed
     * in the Elementor editor panel.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('MH Heading', 'mh-plug');
    }

    /**
     * Get Widget Icon.
     *
     * Returns the icon for the widget, which is displayed in the editor panel.
     * You can use any icon from the eicon library.
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-t-letter';
    }

    /**
     * Get Widget Categories.
     *
     * Assigns the widget to one or more categories in the editor panel.
     * We use our custom category ID 'mh-plug-widgets' that we registered earlier.
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['mh-plug-widgets'];
    }

    /**
     * Register Widget Controls.
     *
     * This is the core function where you define all the settings (controls)
     * that users can change in the Elementor editor.
     */
    protected function _register_controls() {

        // --- Start of Content Tab ---
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'mh-plug'),
                'tab'   => Controls_Manager::TAB_CONTENT, // Assigns this section to the Content tab.
            ]
        );

        // Control for the heading text input.
        $this->add_control(
            'heading_text',
            [
                'label'       => esc_html__('Heading Text', 'mh-plug'),
                'type'        => Controls_Manager::TEXT, // A simple text input field.
                'default'     => esc_html__('Default Heading', 'mh-plug'),
                'label_block' => true, // Makes the label appear on its own line.
            ]
        );

        // Control for selecting the HTML tag (h1, h2, p, etc.).
        $this->add_control(
            'heading_html_tag',
            [
                'label'   => esc_html__('HTML Tag', 'mh-plug'),
                'type'    => Controls_Manager::SELECT, // A dropdown select field.
                'options' => [
                    'h1'  => 'H1',
                    'h2'  => 'H2',
                    'h3'  => 'H3',
                    'h4'  => 'H4',
                    'h5'  => 'H5',
                    'h6'  => 'H6',
                    'p'   => 'P',
                    'div' => 'DIV',
                ],
                'default' => 'h2',
            ]
        );

        $this->end_controls_section();
        // --- End of Content Tab ---


        // --- Start of Style Tab ---
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'mh-plug'),
                'tab'   => Controls_Manager::TAB_STYLE, // Assigns this section to the Style tab.
            ]
        );

        // Control for the text color.
        $this->add_control(
            'heading_color',
            [
                'label'     => esc_html__('Text Color', 'mh-plug'),
                'type'      => Controls_Manager::COLOR, // A color picker control.
                'default'   => '#000000',
                'selectors' => [
                    // '{{WRAPPER}}' is a special selector that targets the widget's main container.
                    // This rule applies the selected color to our heading element.
                    '{{WRAPPER}} .mh-plug-heading-widget' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Group Control for Typography (font family, size, weight, etc.).
        // Group controls combine multiple related settings into one block.
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'heading_typography',
                'label'    => esc_html__('Typography', 'mh-plug'),
                'selector' => '{{WRAPPER}} .mh-plug-heading-widget',
            ]
        );

        // Group Control for Text Shadow.
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'heading_text_shadow',
                'label'    => esc_html__('Text Shadow', 'mh-plug'),
                'selector' => '{{WRAPPER}} .mh-plug-heading-widget',
            ]
        );

        // Control for text alignment (left, center, right).
        $this->add_responsive_control(
            'heading_alignment',
            [
                'label'   => esc_html__('Alignment', 'mh-plug'),
                'type'    => Controls_Manager::CHOOSE, // A button group control.
                'options' => [
                    'left'   => ['title' => esc_html__('Left', 'mh-plug'), 'icon' => 'eicon-text-align-left'],
                    'center' => ['title' => esc_html__('Center', 'mh-plug'), 'icon' => 'eicon-text-align-center'],
                    'right'  => ['title' => esc_html__('Right', 'mh-plug'), 'icon' => 'eicon-text-align-right'],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .mh-plug-heading-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        // Control for padding.
        $this->add_responsive_control(
            'heading_padding',
            [
                'label'      => esc_html__('Padding', 'mh-plug'),
                'type'       => Controls_Manager::DIMENSIONS, // Control for top, right, bottom, left values.
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .mh-plug-heading-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Control for margin.
        $this->add_responsive_control(
            'heading_margin',
            [
                'label'      => esc_html__('Margin', 'mh-plug'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .mh-plug-heading-widget' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        // --- End of Style Tab ---
    }

    /**
     * Render Widget Output on the Frontend.
     *
     * This function generates the final HTML of the widget on the live page.
     */
    protected function render() {
        // Get all the settings values that the user has saved.
        $settings = $this->get_settings_for_display();

        // Get the selected HTML tag, defaulting to 'h2' if for some reason it's empty.
        $heading_tag = !empty($settings['heading_html_tag']) ? $settings['heading_html_tag'] : 'h2';

        // Add a CSS class to the main HTML element. This is important for styling.
        $this->add_render_attribute('heading', 'class', 'mh-plug-heading-widget');

        // Echo the final HTML.
        // We use esc_attr() for the tag name and esc_html() for the text content to ensure security.
        echo '<' . esc_attr($heading_tag) . ' ' . $this->get_render_attribute_string('heading') . '>';
        echo esc_html($settings['heading_text']);
        echo '</' . esc_attr($heading_tag) . '>';
    }
}