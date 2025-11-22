<?php
/**
 * MH Synced Slider Widget (Fixed & Enhanced)
 * Features:
 * - Two-way Synced Sliders (Text & Image)
 * - Center Mode for Images
 * - Full Styling Controls (Typography, Colors, Button, Spacing)
 * - Slider Controls (Autoplay, Speed, Arrows, Dots)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;

class MH_Synced_Slider_Widget extends Widget_Base {

	public function get_name() {
		return 'mh-synced-slider';
	}

	public function get_title() {
		return esc_html__( 'MH Synced Slider', 'mh-plug' );
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

		// --- CONTENT SECTION ---
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Slides', 'mh-plug' ),
			]
		);

		$repeater = new Repeater();

        $repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Image (Right Side)', 'mh-plug' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'subtitle',
			[
				'label' => esc_html__( 'Subtitle', 'mh-plug' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'WELCOME TO', 'mh-plug' ),
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'mh-plug' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'NATURE', 'mh-plug' ),
			]
		);

		$repeater->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', 'mh-plug' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'mh-plug' ),
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'mh-plug' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'EXPLORE', 'mh-plug' ),
			]
		);

		$repeater->add_control(
			'button_link',
			[
				'label' => esc_html__( 'Button Link', 'mh-plug' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'mh-plug' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'slides',
			[
				'label' => esc_html__( 'Slides', 'mh-plug' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'subtitle' => 'WELCOME TO',
						'title' => 'NATURE',
						'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
						'button_text' => 'EXPLORE',
					],
					[
						'subtitle' => 'DISCOVER',
						'title' => 'ADVENTURE',
						'description' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
						'button_text' => 'VIEW MORE',
					],
					[
						'subtitle' => 'EXPERIENCE',
						'title' => 'FREEDOM',
						'description' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco.',
						'button_text' => 'START NOW',
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'large',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

        // --- SLIDER SETTINGS ---
        $this->start_controls_section(
			'section_slider_settings',
			[
				'label' => esc_html__( 'Slider Settings', 'mh-plug' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'mh-plug' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

        $this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Autoplay Speed (ms)', 'mh-plug' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3000,
                'condition' => [ 'autoplay' => 'yes' ],
			]
		);

        $this->add_control(
			'infinite',
			[
				'label' => esc_html__( 'Infinite Loop', 'mh-plug' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

        $this->add_control(
			'show_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'mh-plug' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

        $this->add_control(
			'arrow_prev_icon',
			[
				'label' => esc_html__( 'Previous Icon', 'mh-plug' ),
				'type' => Controls_Manager::ICONS,
				'default' => [ 'value' => 'eicon-chevron-left', 'library' => 'eicons' ],
				'condition' => [ 'show_arrows' => 'yes' ],
			]
		);

        $this->add_control(
			'arrow_next_icon',
			[
				'label' => esc_html__( 'Next Icon', 'mh-plug' ),
				'type' => Controls_Manager::ICONS,
				'default' => [ 'value' => 'eicon-chevron-right', 'library' => 'eicons' ],
				'condition' => [ 'show_arrows' => 'yes' ],
			]
		);

		$this->end_controls_section();

		// --- STYLE: TEXT CONTENT ---
		$this->start_controls_section(
			'section_style_text',
			[
				'label' => esc_html__( 'Text Content (Left)', 'mh-plug' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control( 'heading_subtitle', [ 'label' => 'Subtitle', 'type' => Controls_Manager::HEADING ] );
		$this->add_control( 'subtitle_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-subtitle' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'subtitle_typography', 'selector' => '{{WRAPPER}} .mh-synced-subtitle' ] );
        $this->add_responsive_control( 'subtitle_margin', [ 'label' => 'Margin', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em' ], 'selectors' => [ '{{WRAPPER}} .mh-synced-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );


        $this->add_control( 'heading_title_main', [ 'label' => 'Title', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'title_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-title' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typography', 'selector' => '{{WRAPPER}} .mh-synced-title' ] );
        $this->add_responsive_control( 'title_margin', [ 'label' => 'Margin', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em' ], 'selectors' => [ '{{WRAPPER}} .mh-synced-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

        $this->add_control( 'heading_desc', [ 'label' => 'Description', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'desc_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-desc' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'desc_typography', 'selector' => '{{WRAPPER}} .mh-synced-desc' ] );
        $this->add_responsive_control( 'desc_margin', [ 'label' => 'Margin', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em' ], 'selectors' => [ '{{WRAPPER}} .mh-synced-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

        // Button
        $this->add_control( 'heading_btn', [ 'label' => 'Button', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
        
        $this->start_controls_tabs( 'tabs_button_style' );
        $this->start_controls_tab( 'tab_button_normal', [ 'label' => 'Normal' ] );
        $this->add_control( 'btn_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-btn' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'btn_bg_color', [ 'label' => 'Background Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-btn' => 'background-color: {{VALUE}};' ] ] );
        $this->end_controls_tab();

        $this->start_controls_tab( 'tab_button_hover', [ 'label' => 'Hover' ] );
        $this->add_control( 'btn_color_hover', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-btn:hover' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'btn_bg_color_hover', [ 'label' => 'Background Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-btn:hover' => 'background-color: {{VALUE}};' ] ] );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'btn_typography', 'selector' => '{{WRAPPER}} .mh-synced-btn' ] );
        $this->add_responsive_control( 'btn_padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px' ], 'selectors' => [ '{{WRAPPER}} .mh-synced-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'btn_radius', [ 'label' => 'Border Radius', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%' ], 'selectors' => [ '{{WRAPPER}} .mh-synced-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

		$this->end_controls_section();

        // --- STYLE: IMAGE ---
        $this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Image (Right)', 'mh-plug' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->add_responsive_control(
			'image_height',
			[
				'label' => esc_html__( 'Image Height', 'mh-plug' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [ 'px' => [ 'min' => 100, 'max' => 1000 ] ],
				'default' => [ 'unit' => 'px', 'size' => 450 ],
				'selectors' => [ '{{WRAPPER}} .mh-slider-img' => 'height: {{SIZE}}{{UNIT}};' ],
			]
		);

        $this->add_responsive_control(
			'image_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mh-plug' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [ '{{WRAPPER}} .mh-slider-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_shadow',
				'selector' => '{{WRAPPER}} .mh-slider-img',
			]
		);

        $this->end_controls_section();

        // --- STYLE: NAVIGATION ---
        $this->start_controls_section(
			'section_style_nav',
			[
				'label' => esc_html__( 'Navigation', 'mh-plug' ),
				'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'show_arrows' => 'yes' ],
			]
		);

        $this->add_responsive_control(
			'arrow_size',
			[
				'label' => esc_html__( 'Arrow Size', 'mh-plug' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [ '{{WRAPPER}} .mh-synced-arrow' => 'font-size: {{SIZE}}{{UNIT}};' ],
			]
		);

        $this->start_controls_tabs( 'tabs_arrow_style' );
        $this->start_controls_tab( 'tab_arrow_normal', [ 'label' => 'Normal' ] );
        $this->add_control( 'arrow_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-arrow' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'arrow_bg', [ 'label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-arrow' => 'background-color: {{VALUE}};' ] ] );
        $this->end_controls_tab();
        $this->start_controls_tab( 'tab_arrow_hover', [ 'label' => 'Hover' ] );
        $this->add_control( 'arrow_color_hover', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-arrow:hover' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'arrow_bg_hover', [ 'label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-arrow:hover' => 'background-color: {{VALUE}};' ] ] );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
			'arrow_padding',
			[
				'label' => esc_html__( 'Padding', 'mh-plug' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [ '{{WRAPPER}} .mh-synced-arrow' => 'width: auto; height: auto; padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);

        $this->end_controls_section();
	}
protected function render() {
		$settings = $this->get_settings_for_display();
		$slides = $settings['slides'];

		if ( empty( $slides ) ) {
			return;
		}

		$id = $this->get_id();
		$text_slider_id = 'mh-text-slider-' . $id;
		$image_slider_id = 'mh-image-slider-' . $id;

        // Slider Options
        $autoplay = $settings['autoplay'] === 'yes' ? 'true' : 'false';
        $infinite = $settings['infinite'] === 'yes' ? 'true' : 'false';
        $speed = $settings['autoplay_speed'] ? $settings['autoplay_speed'] : 3000;

        // Arrows
        $show_arrows = $settings['show_arrows'] === 'yes';
        $prev_arrow = '';
        $next_arrow = '';

        if ( $show_arrows ) {
            ob_start();
            Icons_Manager::render_icon( $settings['arrow_prev_icon'], [ 'aria-hidden' => 'true' ] );
            $prev_icon_html = ob_get_clean();
            if(empty($prev_icon_html)) $prev_icon_html = '<i class="eicon-chevron-left"></i>';

            ob_start();
            Icons_Manager::render_icon( $settings['arrow_next_icon'], [ 'aria-hidden' => 'true' ] );
            $next_icon_html = ob_get_clean();
            if(empty($next_icon_html)) $next_icon_html = '<i class="eicon-chevron-right"></i>';

            $prev_arrow = '<button type="button" class="mh-synced-arrow mh-prev">' . $prev_icon_html . '</button>';
            $next_arrow = '<button type="button" class="mh-synced-arrow mh-next">' . $next_icon_html . '</button>';
        }
		?>

		<div class="mh-synced-slider-wrapper" 
             data-autoplay="<?php echo esc_attr($autoplay); ?>" 
             data-speed="<?php echo esc_attr($speed); ?>" 
             data-infinite="<?php echo esc_attr($infinite); ?>"
             data-text-id="<?php echo esc_attr($text_slider_id); ?>"
             data-image-id="<?php echo esc_attr($image_slider_id); ?>">
            
            <div class="mh-text-slider-container">
                <div class="mh-text-slider" id="<?php echo esc_attr( $text_slider_id ); ?>">
                    <?php foreach ( $slides as $slide ) : ?>
                        <div class="mh-text-slide">
                            <div class="mh-text-content">
                                <?php if ( ! empty( $slide['subtitle'] ) ) : ?>
                                    <div class="mh-synced-subtitle"><?php echo esc_html( $slide['subtitle'] ); ?></div>
                                <?php endif; ?>
                                <?php if ( ! empty( $slide['title'] ) ) : ?>
                                    <h2 class="mh-synced-title"><?php echo wp_kses_post( $slide['title'] ); ?></h2>
                                <?php endif; ?>
                                <?php if ( ! empty( $slide['description'] ) ) : ?>
                                    <div class="mh-synced-desc"><?php echo wp_kses_post( $slide['description'] ); ?></div>
                                <?php endif; ?>
                                <?php if ( ! empty( $slide['button_text'] ) ) : ?>
                                    <a href="<?php echo esc_url( $slide['button_link']['url'] ); ?>" class="mh-synced-btn">
                                        <?php echo esc_html( $slide['button_text'] ); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mh-image-slider-container">
                <div class="mh-image-slider" id="<?php echo esc_attr( $image_slider_id ); ?>">
                    <?php foreach ( $slides as $slide ) : ?>
                        <div class="mh-image-slide">
                            <div class="mh-image-box">
                                <?php if ( ! empty( $slide['image']['id'] ) ) : ?>
                                    <?php echo wp_get_attachment_image( $slide['image']['id'], $settings['image_size_size'], false, [ 'class' => 'mh-slider-img' ] ); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ( $show_arrows ) : ?>
                    <div class="mh-synced-arrows-container">
                        <?php echo $prev_arrow; ?>
                        <?php echo $next_arrow; ?>
                    </div>
                <?php endif; ?>
            </div>
		</div>

		<script>
		jQuery(document).ready(function($) {
            var $wrapper = $('.elementor-element-<?php echo esc_attr($id); ?> .mh-synced-slider-wrapper');
            var textId = '#' + $wrapper.data('text-id');
            var imageId = '#' + $wrapper.data('image-id');
            var autoplay = $wrapper.data('autoplay') === true;
            var speed = $wrapper.data('speed');
            var infinite = $wrapper.data('infinite') === true;

            // Left Text Slider
			$(textId).slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: false,
				fade: true,
				asNavFor: imageId,
                autoplay: autoplay,
                autoplaySpeed: speed,
                infinite: infinite,
                draggable: false
			});

            // Right Image Slider (Center Mode)
			$(imageId).slick({
				slidesToShow: 3, // Show 3 items
				slidesToScroll: 1,
				asNavFor: textId,
				dots: false,
				centerMode: true,
                centerPadding: '0px', // No extra padding from slick
				focusOnSelect: true,
                arrows: false,
                infinite: infinite,
                autoplay: autoplay,
                autoplaySpeed: speed,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            centerMode: false
                        }
                    }
                ]
			});

            $wrapper.find('.mh-prev').click(function(){
                $(imageId).slick('slickPrev');
            });
            $wrapper.find('.mh-next').click(function(){
                $(imageId).slick('slickNext');
            });
		});
		</script>
		<?php
	}
}