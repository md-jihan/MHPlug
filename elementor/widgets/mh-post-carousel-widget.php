<?php
/**
 * MH Post Carousel Widget
 * Features:
 * - Dynamic Post Query
 * - Layout: Image > Date/Author > Title > Description > Button
 * - Slider Mode (Slick) vs Grid Mode (CSS Grid)
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;

class MH_Post_Carousel_Widget extends Widget_Base {

    public function get_name() {
        return 'mh-post-carousel';
    }

    public function get_title() {
        return esc_html__('MH Post Carousel', 'mh-plug');
    }

    public function get_icon() {
        return 'eicon-posts-carousel';
    }

    public function get_categories() {
        return ['mh-plug-widgets'];
    }

    protected function register_controls() {

        // --- Content Tab: Query ---
        $this->start_controls_section(
            'section_query',
            [
                'label' => esc_html__('Query', 'mh-plug'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Posts Per Page', 'mh-plug'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'medium_large',
                'exclude' => ['custom'],
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => esc_html__('Excerpt Length (Words)', 'mh-plug'),
                'type' => Controls_Manager::NUMBER,
                'default' => 15,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'mh-plug'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Learn More', 'mh-plug'),
            ]
        );

        $this->end_controls_section();

        // --- Content Tab: Layout & Slider ---
        $this->start_controls_section(
            'section_slider_settings',
            [
                'label' => esc_html__('Layout Settings', 'mh-plug'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // --- 1. Slider On/Off Switch ---
        $this->add_control(
            'enable_slider',
            [
                'label' => esc_html__('Enable Slider', 'mh-plug'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mh-plug'),
                'label_off' => esc_html__('No', 'mh-plug'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'slides_to_show',
            [
                'label' => esc_html__('Columns / Slides', 'mh-plug'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'description' => esc_html__('Number of columns in Grid mode, or slides in Slider mode.', 'mh-plug'),
            ]
        );

        // --- 2. Slider Options (Only visible if Slider is ON) ---
        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'mh-plug'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [ 'enable_slider' => 'yes' ],
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => esc_html__('Autoplay Speed (ms)', 'mh-plug'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3000,
                'condition' => [ 'enable_slider' => 'yes', 'autoplay' => 'yes' ],
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => esc_html__('Arrows', 'mh-plug'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [ 'enable_slider' => 'yes' ],
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label' => esc_html__('Dots', 'mh-plug'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [ 'enable_slider' => 'yes' ],
            ]
        );
        
        // --- 3. Gap Control (Works for both) ---
        $this->add_responsive_control(
            'grid_gap',
            [
                'label' => esc_html__( 'Gap', 'mh-plug' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [ 'size' => 20 ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    // Grid Mode Gap
                    '{{WRAPPER}} .mh-post-grid' => 'gap: {{SIZE}}{{UNIT}};',
                    // Slider Mode Gap (Margin on items)
                    '{{WRAPPER}} .mh-post-carousel-item' => 'padding: 0 calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .mh-post-carousel .slick-list' => 'margin: 0 calc(-{{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->end_controls_section();

        // --- Style Tab: Card Box ---
        $this->start_controls_section(
            'section_style_box',
            [
                'label' => esc_html__('Card Box', 'mh-plug'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_bg_color',
            [
                'label' => esc_html__('Background Color', 'mh-plug'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mh-post-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label' => esc_html__('Content Padding', 'mh-plug'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mh-post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'selector' => '{{WRAPPER}} .mh-post-card',
            ]
        );

        $this->add_control(
            'box_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mh-plug'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .mh-post-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .mh-post-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .mh-post-card',
            ]
        );

        $this->end_controls_section();

        // --- Style Tab: Meta ---
        $this->start_controls_section(
            'section_style_meta',
            [
                'label' => esc_html__('Date & Author', 'mh-plug'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => esc_html__('Color', 'mh-plug'),
                'type' => Controls_Manager::COLOR,
                'default' => '#888888',
                'selectors' => [
                    '{{WRAPPER}} .mh-post-meta' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mh-post-meta a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .mh-post-meta',
            ]
        );

        $this->add_control(
            'meta_icon_color',
            [
                'label' => esc_html__('Icon Color', 'mh-plug'),
                'type' => Controls_Manager::COLOR,
                'default' => '#004265',
                'selectors' => [
                    '{{WRAPPER}} .mh-post-meta i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // --- Style Tab: Title ---
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Title', 'mh-plug'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'mh-plug'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mh-post-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__('Hover Color', 'mh-plug'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mh-post-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mh-post-title',
            ]
        );

        $this->end_controls_section();

        // --- Style Tab: Button ---
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__('Button', 'mh-plug'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_button_style');
        
        $this->start_controls_tab( 'tab_button_normal', ['label' => esc_html__('Normal', 'mh-plug')] );
        $this->add_control( 'button_text_color', [ 'label' => esc_html__('Text Color', 'mh-plug'), 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => [ '{{WRAPPER}} .mh-post-button' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'button_bg_color', [ 'label' => esc_html__('Background Color', 'mh-plug'), 'type' => Controls_Manager::COLOR, 'default' => '#004265', 'selectors' => [ '{{WRAPPER}} .mh-post-button' => 'background-color: {{VALUE}};' ] ] );
        $this->end_controls_tab();

        $this->start_controls_tab( 'tab_button_hover', ['label' => esc_html__('Hover', 'mh-plug')] );
        $this->add_control( 'button_text_color_hover', [ 'label' => esc_html__('Text Color', 'mh-plug'), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-post-button:hover' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'button_bg_color_hover', [ 'label' => esc_html__('Background Color', 'mh-plug'), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-post-button:hover' => 'background-color: {{VALUE}};' ] ] );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control( 'button_padding', [ 'label' => esc_html__('Padding', 'mh-plug'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', 'em'], 'selectors' => [ '{{WRAPPER}} .mh-post-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_control( 'button_border_radius', [ 'label' => esc_html__('Border Radius', 'mh-plug'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%'], 'selectors' => [ '{{WRAPPER}} .mh-post-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id();
        
        $args = [
            'post_type' => 'post',
            'posts_per_page' => $settings['posts_per_page'],
            'post_status' => 'publish',
        ];
        $query = new \WP_Query($args);

        if (!$query->have_posts()) {
            return;
        }
        
        // --- 1. SLIDER LAYOUT ---
        if ( 'yes' === $settings['enable_slider'] ) {
            
            $slick_options = [
                'slidesToShow' => (int)$settings['slides_to_show'],
                'slidesToScroll' => 1,
                'autoplay' => ($settings['autoplay'] === 'yes'),
                'autoplaySpeed' => (int)$settings['autoplay_speed'],
                'arrows' => ($settings['show_arrows'] === 'yes'),
                'dots' => ($settings['show_dots'] === 'yes'),
                'infinite' => true,
            ];
            
            // Responsive settings
            $slick_options['responsive'] = [
                [
                    'breakpoint' => 1024,
                    'settings' => [ 'slidesToShow' => (int)($settings['slides_to_show_tablet'] ?: 2) ]
                ],
                [
                    'breakpoint' => 767,
                    'settings' => [ 'slidesToShow' => (int)($settings['slides_to_show_mobile'] ?: 1) ]
                ]
            ];

            if ($settings['show_arrows'] === 'yes') {
                $slick_options['prevArrow'] = '<button type="button" class="slick-prev"><i class="eicon-chevron-left"></i></button>';
                $slick_options['nextArrow'] = '<button type="button" class="slick-next"><i class="eicon-chevron-right"></i></button>';
            }

            ?>
            <div class="mh-post-carousel-wrapper">
                <div class="mh-post-carousel" data-slick='<?php echo json_encode($slick_options); ?>'>
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="mh-post-carousel-item">
                            <?php $this->render_post_card($settings); ?>
                        </div>
                    <?php endwhile; ?>
                </div>
                <script>
                    jQuery(document).ready(function($) {
                        var $slider = $('.elementor-element-<?php echo esc_attr($widget_id); ?> .mh-post-carousel');
                        if ( $slider.length && $.fn.slick ) {
                            $slider.slick($slider.data('slick'));
                        }
                    });
                </script>
            </div>
            <?php

        } 
        // --- 2. GRID LAYOUT (Slider OFF) ---
        else {
            
            $grid_style = '--mh-grid-cols: ' . $settings['slides_to_show'] . ';';
            if ( ! empty( $settings['slides_to_show_tablet'] ) ) {
                $grid_style .= ' --mh-grid-cols-tablet: ' . $settings['slides_to_show_tablet'] . ';';
            }
            if ( ! empty( $settings['slides_to_show_mobile'] ) ) {
                $grid_style .= ' --mh-grid-cols-mobile: ' . $settings['slides_to_show_mobile'] . ';';
            }

            ?>
            <div class="mh-post-grid-wrapper" style="<?php echo esc_attr($grid_style); ?>">
                <div class="mh-post-grid">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="mh-post-grid-item">
                            <?php $this->render_post_card($settings); ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php
        }

        wp_reset_postdata();
    }

    // Helper to render single card
    protected function render_post_card($settings) {
        ?>
        <div class="mh-post-card">
            <?php if (has_post_thumbnail()) : ?>
                <div class="mh-post-thumbnail">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail($settings['thumbnail_size']); ?>
                    </a>
                </div>
            <?php endif; ?>

            <div class="mh-post-content">
                <div class="mh-post-meta">
                    <span class="mh-meta-date"><i class="far fa-calendar-alt"></i> <?php echo get_the_date(); ?></span>
                    <span class="mh-meta-separator">|</span>
                    <span class="mh-meta-author"><i class="far fa-user"></i> <?php the_author(); ?></span>
                </div>

                <h3 class="mh-post-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>

                <div class="mh-post-excerpt">
                    <?php echo wp_trim_words(get_the_excerpt(), $settings['excerpt_length']); ?>
                </div>

                <div class="mh-post-button-wrapper">
                    <a href="<?php the_permalink(); ?>" class="mh-post-button">
                        <?php echo esc_html($settings['button_text']); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
}