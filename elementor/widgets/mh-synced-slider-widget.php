<?php
/**
 * MH Synced Slider Widget (Final Version)
 * Features: Two-way Synced Slider, Full Styling Options, Responsive Controls.
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
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;

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

    // Ensure scripts are loaded
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

        // Image
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

        // Text Content
		$repeater->add_control(
			'subtitle',
			[
				'label' => esc_html__( 'Subtitle', 'mh-plug' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'WELCOME TO', 'mh-plug' ),
                'label_block' => true,
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

        // --- SETTINGS SECTION ---
        $this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Slider Settings', 'mh-plug' ),
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

        $this->end_controls_section();


		// --- STYLE: TEXT CONTENT ---
		$this->start_controls_section(
			'section_style_text',
			[
				'label' => esc_html__( 'Text Content (Left)', 'mh-plug' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->add_responsive_control(
			'text_width',
			[
				'label' => esc_html__( 'Section Width (%)', 'mh-plug' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [ '%' => [ 'min' => 20, 'max' => 80 ] ],
				'default' => [ 'unit' => '%', 'size' => 40 ],
				'selectors' => [
					'{{WRAPPER}} .mh-text-slider-wrapper' => 'width: {{SIZE}}%;',
                    '{{WRAPPER}} .mh-image-slider-wrapper' => 'width: calc(100% - {{SIZE}}%);',
				],
			]
		);
        
        $this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__( 'Padding', 'mh-plug' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .mh-text-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        // Subtitle
        $this->add_control( 'heading_subtitle', [ 'label' => 'Subtitle', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'subtitle_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-subtitle' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'subtitle_typography', 'selector' => '{{WRAPPER}} .mh-synced-subtitle' ] );
        $this->add_responsive_control( 'subtitle_spacing', [ 'label' => 'Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .mh-synced-subtitle' => 'margin-bottom: {{SIZE}}px;' ] ] );

        // Title
        $this->add_control( 'heading_title_main', [ 'label' => 'Title', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'title_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-title' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typography', 'selector' => '{{WRAPPER}} .mh-synced-title' ] );
        $this->add_responsive_control( 'title_spacing', [ 'label' => 'Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .mh-synced-title' => 'margin-bottom: {{SIZE}}px;' ] ] );

        // Description
        $this->add_control( 'heading_desc', [ 'label' => 'Description', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'desc_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-desc' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'desc_typography', 'selector' => '{{WRAPPER}} .mh-synced-desc' ] );
        $this->add_responsive_control( 'desc_spacing', [ 'label' => 'Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .mh-synced-desc' => 'margin-bottom: {{SIZE}}px;' ] ] );

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
        $this->add_responsive_control( 'btn_padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .mh-synced-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'btn_radius', [ 'label' => 'Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .mh-synced-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

		$this->end_controls_section();

        // --- STYLE: IMAGE ---
        $this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Image Content (Right)', 'mh-plug' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->add_responsive_control(
            'image_height',
            [
                'label' => esc_html__( 'Image Height', 'mh-plug' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 200, 'max' => 800 ] ],
                'default' => [ 'unit' => 'px', 'size' => 450 ],
                'selectors' => [
                    '{{WRAPPER}} .mh-slider-img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'image_radius',
            [
                'label' => esc_html__( 'Border Radius', 'mh-plug' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .mh-slider-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$slides = $settings['slides'];

		if ( empty( $slides ) ) {
			return;
		}

		$id = $this->get_id();
		$text_slider_class = 'mh-text-slider-' . $id;
		$image_slider_class = 'mh-image-slider-' . $id;
        
        // Slider Settings
        $autoplay = $settings['autoplay'] === 'yes' ? 'true' : 'false';
        $autoplay_speed = $settings['autoplay_speed'];
        $infinite = $settings['infinite'] === 'yes' ? 'true' : 'false';

		?>

		<div class="mh-synced-slider-wrapper">
            
            <div class="mh-text-slider-wrapper">
                <div class="mh-text-slider <?php echo esc_attr( $text_slider_class ); ?>">
                    <?php foreach ( $slides as $slide ) : ?>
                        <div class="mh-text-slide-item">
                            <div class="mh-text-content">
                                <?php if ( ! empty( $slide['subtitle'] ) ) : ?>
                                    <h5 class="mh-synced-subtitle"><?php echo esc_html( $slide['subtitle'] ); ?></h5>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $slide['title'] ) ) : ?>
                                    <h2 class="mh-synced-title"><?php echo wp_kses_post( $slide['title'] ); ?></h2>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $slide['description'] ) ) : ?>
                                    <div class="mh-synced-desc"><?php echo wp_kses_post( $slide['description'] ); ?></div>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $slide['button_text'] ) ) : ?>
                                    <div class="mh-synced-btn-wrapper">
                                        <a href="<?php echo esc_url( $slide['button_link']['url'] ); ?>" class="mh-synced-btn">
                                            <?php echo esc_html( $slide['button_text'] ); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mh-image-slider-wrapper">
                <div class="mh-image-slider <?php echo esc_attr( $image_slider_class ); ?>">
                    <?php foreach ( $slides as $slide ) : ?>
                        <div class="mh-image-slide-item">
                            <div class="mh-image-box">
                                <?php if ( ! empty( $slide['image']['id'] ) ) : ?>
                                    <?php echo wp_get_attachment_image( $slide['image']['id'], $settings['image_size_size'], false, [ 'class' => 'mh-slider-img' ] ); ?>
                                <?php else: ?>
                                    <img src="<?php echo esc_url($slide['image']['url']); ?>" class="mh-slider-img" alt="Slide Image">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

		</div>

        <script>
		jQuery(document).ready(function($) {
            var textSlider = $('.<?php echo esc_attr( $text_slider_class ); ?>');
            var imageSlider = $('.<?php echo esc_attr( $image_slider_class ); ?>');

            // Ensure both exist before init
            if ( textSlider.length && imageSlider.length ) {

                // Left Side: Text
                textSlider.slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    fade: true,
                    asNavFor: imageSlider,
                    autoplay: <?php echo $autoplay; ?>,
                    autoplaySpeed: <?php echo $autoplay_speed; ?>,
                    infinite: <?php echo $infinite; ?>
                });

                // Right Side: Images
                imageSlider.slick({
                    slidesToShow: 3, 
                    slidesToScroll: 1,
                    asNavFor: textSlider,
                    dots: false,
                    centerMode: true,
                    centerPadding: '0px',
                    focusOnSelect: true,
                    arrows: false,
                    autoplay: <?php echo $autoplay; ?>,
                    autoplaySpeed: <?php echo $autoplay_speed; ?>,
                    infinite: <?php echo $infinite; ?>,
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
            }
		});
		</script>
		<?php
	}
}