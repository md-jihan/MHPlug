<?php
/**
 * MH Modern Slider Widget
 * Layout: Text Box (Left) + Image Slider (Right)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;

class MH_Synced_Slider_Widget extends Widget_Base {

	public function get_name() {
		return 'mh-modern-slider';
	}

	public function get_title() {
		return esc_html__( 'MH Modern Slider', 'mh-plug' );
	}

	public function get_icon() {
		return 'eicon-slides';
	}

	public function get_categories() {
		return [ 'mh-plug-widgets' ];
	}

    public function get_script_depends() {
		return [ 'jquery', 'slick' ];
	}

    public function get_style_depends() {
		return [ 'slick', 'slick-theme' ];
	}

	protected function register_controls() {

		// --- CONTENT: SLIDES ---
		$this->start_controls_section(
			'section_slides',
			[
				'label' => esc_html__( 'Slides', 'mh-plug' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'mh-plug' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control( 'subtitle', [ 'label' => 'Subtitle', 'type' => Controls_Manager::TEXT, 'default' => 'ARCHITECTURE' ] );
		$repeater->add_control( 'title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXTAREA, 'default' => 'MODERN HOUSE' ] );
		$repeater->add_control( 'description', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis.' ] );
		$repeater->add_control( 'button_text', [ 'label' => 'Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'VIEW PROJECT' ] );
		$repeater->add_control( 'button_link', [ 'label' => 'Link', 'type' => Controls_Manager::URL, 'placeholder' => 'https://your-link.com' ] );

		$this->add_control(
			'slides',
			[
				'label' => esc_html__( 'Slides', 'mh-plug' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'subtitle' => 'ARCHITECTURE', 'title' => 'MODERN HOUSE', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'button_text' => 'VIEW PROJECT' ],
					[ 'subtitle' => 'INTERIOR', 'title' => 'LUXURY VILLA', 'description' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'button_text' => 'VIEW PROJECT' ],
				],
				'title_field' => '{{{ title }}}',
			]
		);

        $this->add_group_control( Group_Control_Image_Size::get_type(), [ 'name' => 'image_size', 'default' => 'full', 'separator' => 'before' ] );

		$this->end_controls_section();

        // --- SETTINGS ---
        $this->start_controls_section( 'section_settings', [ 'label' => 'Slider Settings', 'tab' => Controls_Manager::TAB_CONTENT ] );
        $this->add_control( 'autoplay', [ 'label' => 'Autoplay', 'type' => Controls_Manager::SWITCHER, 'default' => 'no' ] );
        $this->add_control( 'autoplay_speed', [ 'label' => 'Speed (ms)', 'type' => Controls_Manager::NUMBER, 'default' => 5000, 'condition' => [ 'autoplay' => 'yes' ] ] );
        $this->add_control( 'pause_on_hover', [ 'label' => 'Pause on Hover', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => [ 'autoplay' => 'yes' ] ] );
        $this->add_control( 'infinite', [ 'label' => 'Infinite Loop', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );
        $this->add_control( 'effect', [ 'label' => 'Effect', 'type' => Controls_Manager::SELECT, 'default' => 'slide', 'options' => [ 'slide' => 'Slide', 'fade' => 'Fade' ] ] );
        $this->end_controls_section();

		// --- STYLE: TEXT BOX ---
		$this->start_controls_section( 'section_style_text_box', [ 'label' => 'Text Box', 'tab' => Controls_Manager::TAB_STYLE ] );
        
        $this->add_control( 'text_box_bg', [ 'label' => 'Background Color', 'type' => Controls_Manager::COLOR, 'default' => '#1a1a1a', 'selectors' => [ '{{WRAPPER}} .mh-modern-text-content' => 'background-color: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'text_box_padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%' ], 'default' => [ 'top' => 60, 'right' => 60, 'bottom' => 60, 'left' => 60, 'unit' => 'px', 'isLinked' => true ], 'selectors' => [ '{{WRAPPER}} .mh-modern-text-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'text_box_width', [ 'label' => 'Width (%)', 'type' => Controls_Manager::SLIDER, 'size_units' => [ '%' ], 'range' => [ '%' => [ 'min' => 20, 'max' => 80 ] ], 'default' => [ 'unit' => '%', 'size' => 40 ], 'selectors' => [ '{{WRAPPER}} .mh-modern-text-col' => 'width: {{SIZE}}%; flex: 0 0 {{SIZE}}%;', '{{WRAPPER}} .mh-modern-image-col' => 'width: calc(100% - {{SIZE}}%); flex: 0 0 calc(100% - {{SIZE}}%);' ] ] );

        // Typography Controls
        $this->add_control( 'heading_subtitle', [ 'label' => 'Subtitle', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'subtitle_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'default' => '#888', 'selectors' => [ '{{WRAPPER}} .mh-modern-subtitle' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'subtitle_typography', 'selector' => '{{WRAPPER}} .mh-modern-subtitle' ] );
        $this->add_responsive_control( 'subtitle_margin', [ 'label' => 'Margin', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px' ], 'selectors' => [ '{{WRAPPER}} .mh-modern-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

        $this->add_control( 'heading_title', [ 'label' => 'Title', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'title_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'default' => '#fff', 'selectors' => [ '{{WRAPPER}} .mh-modern-title' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typography', 'selector' => '{{WRAPPER}} .mh-modern-title' ] );
        $this->add_responsive_control( 'title_margin', [ 'label' => 'Margin', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px' ], 'selectors' => [ '{{WRAPPER}} .mh-modern-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

        $this->add_control( 'heading_desc', [ 'label' => 'Description', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'desc_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'default' => '#ccc', 'selectors' => [ '{{WRAPPER}} .mh-modern-desc' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'desc_typography', 'selector' => '{{WRAPPER}} .mh-modern-desc' ] );
        $this->add_responsive_control( 'desc_margin', [ 'label' => 'Margin', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px' ], 'selectors' => [ '{{WRAPPER}} .mh-modern-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

        // Button
        $this->add_control( 'heading_btn', [ 'label' => 'Button', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
        $this->start_controls_tabs( 'tabs_button' );
        $this->start_controls_tab( 'tab_btn_normal', [ 'label' => 'Normal' ] );
        $this->add_control( 'btn_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'default' => '#fff', 'selectors' => [ '{{WRAPPER}} .mh-modern-btn' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'btn_bg_color', [ 'label' => 'Bg Color', 'type' => Controls_Manager::COLOR, 'default' => 'transparent', 'selectors' => [ '{{WRAPPER}} .mh-modern-btn' => 'background-color: {{VALUE}};' ] ] );
        $this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'btn_border', 'selector' => '{{WRAPPER}} .mh-modern-btn', 'fields_options' => [ 'border' => [ 'default' => 'solid' ], 'width' => [ 'default' => [ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1, 'unit' => 'px' ] ], 'color' => [ 'default' => '#fff' ] ] ] );
        $this->end_controls_tab();
        $this->start_controls_tab( 'tab_btn_hover', [ 'label' => 'Hover' ] );
        $this->add_control( 'btn_color_hover', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'default' => '#000', 'selectors' => [ '{{WRAPPER}} .mh-modern-btn:hover' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'btn_bg_color_hover', [ 'label' => 'Bg Color', 'type' => Controls_Manager::COLOR, 'default' => '#fff', 'selectors' => [ '{{WRAPPER}} .mh-modern-btn:hover' => 'background-color: {{VALUE}};' ] ] );
        $this->add_control( 'btn_border_color_hover', [ 'label' => 'Border Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-modern-btn:hover' => 'border-color: {{VALUE}};' ] ] );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'btn_typography', 'selector' => '{{WRAPPER}} .mh-modern-btn' ] );
        $this->add_responsive_control( 'btn_padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px' ], 'default' => [ 'top' => 12, 'right' => 30, 'bottom' => 12, 'left' => 30, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .mh-modern-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'btn_radius', [ 'label' => 'Radius', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%' ], 'selectors' => [ '{{WRAPPER}} .mh-modern-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
		$this->end_controls_section();

        // --- STYLE: NAVIGATION ---
        $this->start_controls_section( 'section_style_nav', [ 'label' => 'Navigation', 'tab' => Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'nav_color', [ 'label' => 'Icon Color', 'type' => Controls_Manager::COLOR, 'default' => '#fff', 'selectors' => [ '{{WRAPPER}} .mh-modern-nav-btn' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'nav_border_color', [ 'label' => 'Border Color', 'type' => Controls_Manager::COLOR, 'default' => 'rgba(255,255,255,0.3)', 'selectors' => [ '{{WRAPPER}} .mh-modern-nav-btn' => 'border-color: {{VALUE}};' ] ] );
        $this->add_control( 'nav_hover_color', [ 'label' => 'Hover Icon', 'type' => Controls_Manager::COLOR, 'default' => '#fff', 'selectors' => [ '{{WRAPPER}} .mh-modern-nav-btn:hover' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'nav_hover_bg', [ 'label' => 'Hover Bg', 'type' => Controls_Manager::COLOR, 'default' => '#004265', 'selectors' => [ '{{WRAPPER}} .mh-modern-nav-btn:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'nav_size', [ 'label' => 'Size', 'type' => Controls_Manager::SLIDER, 'default' => [ 'size' => 50 ], 'selectors' => [ '{{WRAPPER}} .mh-modern-nav-btn' => 'width: {{SIZE}}px; height: {{SIZE}}px; line-height: {{SIZE}}px;' ] ] );
        $this->end_controls_section();
    }

	protected function render() {
		$settings = $this->get_settings_for_display();
		$slides = $settings['slides'];

		if ( empty( $slides ) ) return;

		$id = $this->get_id();
		$slider_id = 'mh-modern-slider-' . $id;

        // Slider Options
        $slick_options = [
            'slidesToShow' => 1,
            'slidesToScroll' => 1,
            'autoplay' => ($settings['autoplay'] === 'yes'),
            'autoplaySpeed' => (int)$settings['autoplay_speed'],
            'infinite' => ($settings['infinite'] === 'yes'),
            'pauseOnHover' => ($settings['pause_on_hover'] === 'yes'),
            'fade' => ($settings['effect'] === 'fade'),
            'arrows' => false, // We use custom arrows
            'dots' => false,
        ];
		?>

		<div class="mh-modern-slider-wrapper">
            
            <div class="mh-modern-slider" id="<?php echo esc_attr( $slider_id ); ?>" data-slick='<?php echo json_encode( $slick_options ); ?>'>
                <?php foreach ( $slides as $slide ) : ?>
                    <div class="mh-modern-slide-item">
                        <div class="mh-modern-flex-container">
                            
                            <!-- Left Side: Text Content (Hidden but used for syncing or layout structure) -->
                            <!-- In this layout, we put text ON TOP of the slider or side-by-side. 
                                 Actually, to match the image exactly, the text box OVERLAPS the slider.
                                 So we put both in a flex container. -->
                            
                             <div class="mh-modern-text-col">
                                <div class="mh-modern-text-content">
                                    <?php if ( ! empty( $slide['subtitle'] ) ) : ?>
                                        <h5 class="mh-modern-subtitle"><?php echo esc_html( $slide['subtitle'] ); ?></h5>
                                    <?php endif; ?>
                                    <?php if ( ! empty( $slide['title'] ) ) : ?>
                                        <h2 class="mh-modern-title"><?php echo wp_kses_post( $slide['title'] ); ?></h2>
                                    <?php endif; ?>
                                    <?php if ( ! empty( $slide['description'] ) ) : ?>
                                        <div class="mh-modern-desc"><?php echo wp_kses_post( $slide['description'] ); ?></div>
                                    <?php endif; ?>
                                    <?php if ( ! empty( $slide['button_text'] ) ) : ?>
                                        <div class="mh-modern-btn-wrapper">
                                            <a href="<?php echo esc_url( $slide['button_link']['url'] ); ?>" class="mh-modern-btn">
                                                <?php echo esc_html( $slide['button_text'] ); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Navigation Arrows (Inside Text Column) -->
                                    <div class="mh-modern-nav-wrapper">
                                        <button class="mh-modern-nav-btn mh-prev">
                                            <i class="eicon-chevron-left"></i>
                                        </button>
                                        <button class="mh-modern-nav-btn mh-next">
                                            <i class="eicon-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                             </div>

                             <div class="mh-modern-image-col">
                                <?php if ( ! empty( $slide['image']['id'] ) ) : ?>
                                    <div class="mh-modern-image-bg" style="background-image: url('<?php echo esc_url( $slide['image']['url'] ); ?>');"></div>
                                <?php endif; ?>
                             </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

		</div>

		<script>
		jQuery(document).ready(function($) {
            var $slider = $('#<?php echo esc_attr( $slider_id ); ?>');
            if ( $slider.length && $.fn.slick ) {
                $slider.slick($slider.data('slick'));
                // Custom Nav
                $slider.find('.mh-prev').click(function() { $slider.slick('slickPrev'); });
                $slider.find('.mh-next').click(function() { $slider.slick('slickNext'); });
            }
		});
		</script>
		<?php
	}
}