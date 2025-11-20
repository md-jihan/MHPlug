<?php
/**
 * MH Post Carousel Widget (Layout Builder Edition)
 * Features: Slider/Grid toggle, Drag & Drop Layout Builder for all elements.
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
use Elementor\Repeater;

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

        // --- 1. QUERY SECTION ---
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

        $this->end_controls_section();

        // --- 2. LAYOUT BUILDER (The Drag & Drop Magic) ---
        $this->start_controls_section(
            'section_layout_builder',
            [
                'label' => esc_html__('Card Layout Builder', 'mh-plug'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout_note',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<small>' . __('Add items to the list below. Drag and drop to reorder them in the card.', 'mh-plug') . '</small>',
                'content_classes' => 'elementor-descriptor',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'element_type',
            [
                'label' => esc_html__('Element Type', 'mh-plug'),
                'type' => Controls_Manager::SELECT,
                'default' => 'title',
                'options' => [
                    'image'       => esc_html__('Featured Image', 'mh-plug'),
                    'title'       => esc_html__('Title', 'mh-plug'),
                    'excerpt'     => esc_html__('Description', 'mh-plug'),
                    'button'      => esc_html__('Read More Button', 'mh-plug'),
                    'date'        => esc_html__('Date', 'mh-plug'),
                    'author'      => esc_html__('Author', 'mh-plug'),
                    'category'    => esc_html__('Category', 'mh-plug'),
                    'tags'        => esc_html__('Tags', 'mh-plug'),
                ],
            ]
        );

        // --- Settings per element ---
        
        // Image Settings
        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'medium_large',
                'condition' => ['element_type' => 'image'],
            ]
        );

        // Excerpt Settings
        $repeater->add_control(
            'excerpt_length',
            [
                'label' => esc_html__('Length (Words)', 'mh-plug'),
                'type' => Controls_Manager::NUMBER,
                'default' => 15,
                'condition' => ['element_type' => 'excerpt'],
            ]
        );

        // Button Settings
        $repeater->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'mh-plug'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Read More', 'mh-plug'),
                'condition' => ['element_type' => 'button'],
            ]
        );

        // Icons for Meta/Terms
        $repeater->add_control(
            'meta_icon',
            [
                'label' => esc_html__('Icon', 'mh-plug'),
                'type' => Controls_Manager::ICONS,
                'condition' => ['element_type' => ['date', 'author', 'category', 'tags']],
            ]
        );

        $this->add_control(
            'card_elements',
            [
                'label' => esc_html__('Elements', 'mh-plug'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'element_type' => 'image' ],
                    [ 'element_type' => 'category' ],
                    [ 'element_type' => 'title' ],
                    [ 'element_type' => 'date', 'meta_icon' => [ 'value' => 'far fa-calendar-alt', 'library' => 'regular' ] ],
                    [ 'element_type' => 'excerpt' ],
                    [ 'element_type' => 'button' ],
                ],
                'title_field' => '{{{ element_type }}}',
            ]
        );
        
        $this->add_responsive_control(
            'content_align',
            [
                'label' => esc_html__('Content Alignment', 'mh-plug'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .mh-post-card' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .mh-post-button-wrapper' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .mh-post-meta-item' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // --- 3. SLIDER SETTINGS ---
        $this->start_controls_section(
            'section_slider_settings',
            [
                'label' => esc_html__('Slider / Layout', 'mh-plug'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_slider',
            [
                'label' => esc_html__('Enable Slider', 'mh-plug'),
                'type' => Controls_Manager::SWITCHER,
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
            ]
        );

        // Slider specific
        $this->add_control( 'autoplay', [ 'label' => 'Autoplay', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => [ 'enable_slider' => 'yes' ] ] );
        $this->add_control( 'autoplay_speed', [ 'label' => 'Speed (ms)', 'type' => Controls_Manager::NUMBER, 'default' => 3000, 'condition' => [ 'enable_slider' => 'yes', 'autoplay' => 'yes' ] ] );
        $this->add_control( 'show_arrows', [ 'label' => 'Arrows', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => [ 'enable_slider' => 'yes' ] ] );
        $this->add_control( 'show_dots', [ 'label' => 'Dots', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => [ 'enable_slider' => 'yes' ] ] );

        // Gap
        $this->add_responsive_control(
            'grid_gap',
            [
                'label' => esc_html__( 'Gap', 'mh-plug' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [ 'size' => 20 ],
                'selectors' => [
                    '{{WRAPPER}} .mh-post-grid' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mh-post-carousel-item' => 'padding: 0 calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .mh-post-carousel .slick-list' => 'margin: 0 calc(-{{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->end_controls_section();

        // --- STYLES: CARD ---
        $this->start_controls_section( 'section_style_card', [ 'label' => 'Card Box', 'tab' => Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'box_bg_color', [ 'label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-post-card' => 'background-color: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'box_padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', 'em'], 'selectors' => [ '{{WRAPPER}} .mh-post-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'box_border', 'selector' => '{{WRAPPER}} .mh-post-card' ] );
        $this->add_responsive_control( 'box_radius', [ 'label' => 'Radius', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%'], 'selectors' => [ '{{WRAPPER}} .mh-post-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', '{{WRAPPER}} .mh-post-element-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;' ] ] );
        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'box_shadow', 'selector' => '{{WRAPPER}} .mh-post-card' ] );
        $this->end_controls_section();

        // --- STYLES: TYPOGRAPHY ---
        $this->start_controls_section( 'section_style_typography', [ 'label' => 'Content Styles', 'tab' => Controls_Manager::TAB_STYLE ] );
        
        // Title
        $this->add_control( 'heading_title', [ 'label' => 'Title', 'type' => Controls_Manager::HEADING ] );
        $this->add_control( 'title_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-post-element-title a' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .mh-post-element-title' ] );
        $this->add_responsive_control( 'title_spacing', [ 'label' => 'Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .mh-post-element-title' => 'margin-bottom: {{SIZE}}{{UNIT}};' ] ] );

        // Meta (Date, Author, etc)
        $this->add_control( 'heading_meta', [ 'label' => 'Meta / Terms', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
        $this->add_control( 'meta_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-post-meta-item' => 'color: {{VALUE}};', '{{WRAPPER}} .mh-post-meta-item a' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'meta_typo', 'selector' => '{{WRAPPER}} .mh-post-meta-item' ] );
        $this->add_responsive_control( 'meta_spacing', [ 'label' => 'Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .mh-post-meta-item' => 'margin-bottom: {{SIZE}}{{UNIT}};' ] ] );

        // Description
        $this->add_control( 'heading_desc', [ 'label' => 'Description', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
        $this->add_control( 'desc_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-post-element-excerpt' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'desc_typo', 'selector' => '{{WRAPPER}} .mh-post-element-excerpt' ] );
        $this->add_responsive_control( 'desc_spacing', [ 'label' => 'Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .mh-post-element-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};' ] ] );

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

        if (!$query->have_posts()) return;

        // Slider Mode
        if ( 'yes' === $settings['enable_slider'] ) {
            $slick_options = [
                'slidesToShow' => (int)$settings['slides_to_show'],
                'slidesToScroll' => 1,
                'autoplay' => ($settings['autoplay'] === 'yes'),
                'autoplaySpeed' => (int)$settings['autoplay_speed'],
                'arrows' => ($settings['show_arrows'] === 'yes'),
                'dots' => ($settings['show_dots'] === 'yes'),
                'infinite' => true,
                'responsive' => [
                    [ 'breakpoint' => 1024, 'settings' => [ 'slidesToShow' => (int)($settings['slides_to_show_tablet'] ?: 2) ] ],
                    [ 'breakpoint' => 767, 'settings' => [ 'slidesToShow' => (int)($settings['slides_to_show_mobile'] ?: 1) ] ]
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
                <script>jQuery(document).ready(function($){var s=$('.elementor-element-<?php echo esc_attr($widget_id); ?> .mh-post-carousel');if(s.length&&$.fn.slick){s.slick(s.data('slick'));}});</script>
            </div>
            <?php
        } 
        // Grid Mode
        else {
            $grid_style = '--mh-grid-cols: ' . $settings['slides_to_show'] . ';';
            if ( ! empty( $settings['slides_to_show_tablet'] ) ) $grid_style .= ' --mh-grid-cols-tablet: ' . $settings['slides_to_show_tablet'] . ';';
            if ( ! empty( $settings['slides_to_show_mobile'] ) ) $grid_style .= ' --mh-grid-cols-mobile: ' . $settings['slides_to_show_mobile'] . ';';
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

    // --- THE MAGIC: Render elements in Drag & Drop Order ---
    protected function render_post_card($settings) {
        echo '<div class="mh-post-card">';
        
        foreach ( $settings['card_elements'] as $element ) {
            switch ( $element['element_type'] ) {
                
                case 'image':
                    if ( has_post_thumbnail() ) {
                        echo '<div class="mh-post-element-image">';
                        echo '<a href="' . get_permalink() . '">';
                        the_post_thumbnail( $element['thumbnail_size'] );
                        echo '</a>';
                        echo '</div>';
                    }
                    break;

                case 'title':
                    echo '<h3 class="mh-post-element-title">';
                    echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
                    echo '</h3>';
                    break;

                case 'excerpt':
                    echo '<div class="mh-post-element-excerpt">';
                    echo wp_trim_words( get_the_excerpt(), $element['excerpt_length'] );
                    echo '</div>';
                    break;

                case 'button':
                    echo '<div class="mh-post-button-wrapper">';
                    echo '<a href="' . get_permalink() . '" class="mh-post-button">' . esc_html( $element['button_text'] ) . '</a>';
                    echo '</div>';
                    break;

                // --- Meta & Terms ---
                case 'date':
                    $this->render_meta_item( $element, get_the_date() );
                    break;

                case 'author':
                    $this->render_meta_item( $element, get_the_author() );
                    break;

                case 'category':
                    $cats = get_the_category_list( ', ' );
                    if ( $cats ) $this->render_meta_item( $element, $cats );
                    break;

                case 'tags':
                    $tags = get_the_tag_list( '', ', ' );
                    if ( $tags ) $this->render_meta_item( $element, $tags );
                    break;
            }
        }
        
        echo '</div>';
    }

    // Helper for meta items with icons
    protected function render_meta_item( $element, $content ) {
        echo '<div class="mh-post-meta-item">';
        if ( ! empty( $element['meta_icon']['value'] ) ) {
            Icons_Manager::render_icon( $element['meta_icon'], [ 'aria-hidden' => 'true', 'class' => 'mh-meta-icon' ] );
        }
        echo '<span class="mh-meta-text">' . $content . '</span>';
        echo '</div>';
    }
}