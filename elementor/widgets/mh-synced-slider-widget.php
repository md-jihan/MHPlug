<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;

class MH_Synced_Slider_Widget extends Widget_Base {

	public function get_name() {
		return 'mh_synced_slider';
	}

	public function get_title() {
		return __( 'MH Synced Slider', 'mhds-plug' );
	}

	public function get_icon() {
		return 'eicon-slides';
	}

	public function get_categories() {
		return [ 'mh-plug-widgets' ];
	}

	public function get_script_depends() {
		return [ 'mh-slick-js' ];
	}

	protected function register_controls() {
		// Content Tab
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Slides', 'mhds-plug' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'image',
			[
				'label'   => __( 'Product Image', 'mhds-plug' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'subtitle',
			[
				'label'   => __( 'Subtitle', 'mhds-plug' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'SEASONAL SALE - UP TO 60% OFF', 'mhds-plug' ),
			]
		);

		$repeater->add_control(
			'heading',
			[
				'label'   => __( 'Heading', 'mhds-plug' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Product Name', 'mhds-plug' ),
			]
		);

		$repeater->add_control(
			'price_label',
			[
				'label'   => __( 'Price Label', 'mhds-plug' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Price from :', 'mhds-plug' ),
			]
		);

		$repeater->add_control(
			'price',
			[
				'label'   => __( 'Price Value', 'mhds-plug' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Rs.954', 'mhds-plug' ),
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label'   => __( 'Button Text', 'mhds-plug' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Shop now', 'mhds-plug' ),
			]
		);

		$repeater->add_control(
			'button_link',
			[
				'label'       => __( 'Button Link', 'mhds-plug' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'mhds-plug' ),
			]
		);

		$this->add_control(
			'slides',
			[
				'label'       => __( 'Slides', 'mhds-plug' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[ 'heading' => 'Blankie Buddies', 'subtitle' => 'SEASONAL SALE - UP TO 60% OFF', 'price' => 'Rs.954' ],
					[ 'heading' => 'Plush Animals', 'subtitle' => 'NEW ARRIVALS', 'price' => 'Rs.1200' ],
					[ 'heading' => 'Teething Toys', 'subtitle' => 'BEST SELLERS', 'price' => 'Rs.500' ],
				],
				'title_field' => '{{{ heading }}}',
			]
		);

		$this->end_controls_section();

		// Style Tab - Container
		$this->start_controls_section(
			'container_style',
			[
				'label' => __( 'Container', 'mhds-plug' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'wrapper_bg',
				'selector' => '{{WRAPPER}} .mh-synced-wrapper',
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label'      => __( 'Padding', 'mhds-plug' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .mh-synced-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab - Typography
		$this->start_controls_section(
			'typo_style',
			[
				'label' => __( 'Typography', 'mhds-plug' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control( 'heading_style_heading', [ 'label' => __( 'Heading', 'mhds-plug' ), 'type' => Controls_Manager::HEADING ] );
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[ 'name' => 'heading_typo', 'selector' => '{{WRAPPER}} .mh-sync-title' ]
		);

		$this->add_control( 'subtitle_style_heading', [ 'label' => __( 'Subtitle', 'mhds-plug' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[ 'name' => 'subtitle_typo', 'selector' => '{{WRAPPER}} .mh-sync-subtitle' ]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$id       = $this->get_id();

		if ( empty( $settings['slides'] ) ) {
			return;
		}
		?>

		<div class="mh-synced-wrapper">
			<div class="mh-synced-container">
				
				<div class="mh-synced-content-slider" id="mh-content-<?php echo esc_attr( $id ); ?>">
					<?php foreach ( $settings['slides'] as $slide ) : ?>
						<div class="mh-content-slide">
							<div class="mh-content-inner">
								<?php if ( $slide['subtitle'] ) : ?>
									<h4 class="mh-sync-subtitle"><?php echo esc_html( $slide['subtitle'] ); ?></h4>
								<?php endif; ?>
								
								<?php if ( $slide['heading'] ) : ?>
									<h2 class="mh-sync-title"><?php echo esc_html( $slide['heading'] ); ?></h2>
								<?php endif; ?>
								
								<div class="mh-sync-price-box">
									<?php if ( $slide['price_label'] ) : ?>
										<span class="mh-sync-label"><?php echo esc_html( $slide['price_label'] ); ?></span>
									<?php endif; ?>
									<?php if ( $slide['price'] ) : ?>
										<span class="mh-sync-price"><?php echo esc_html( $slide['price'] ); ?></span>
									<?php endif; ?>
								</div>

								<?php if ( $slide['button_text'] ) : 
									$link_attrs = '';
									if ( ! empty( $slide['button_link']['url'] ) ) {
										$this->add_link_attributes( 'button_' . $slide['_id'], $slide['button_link'] );
										$link_attrs = $this->get_render_attribute_string( 'button_' . $slide['_id'] );
									}
								?>
									<a href="<?php echo esc_url( $slide['button_link']['url'] ); ?>" class="mh-sync-btn" <?php echo $link_attrs; ?>>
										<?php echo esc_html( $slide['button_text'] ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="mh-synced-image-slider" id="mh-image-<?php echo esc_attr( $id ); ?>">
					<?php foreach ( $settings['slides'] as $slide ) : ?>
						<div class="mh-image-slide">
							<div class="mh-image-inner">
								<?php if ( ! empty( $slide['image']['url'] ) ) : ?>
									<img src="<?php echo esc_url( $slide['image']['url'] ); ?>" alt="<?php echo esc_attr( $slide['heading'] ); ?>">
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

			</div>
		</div>

		<script>
		jQuery(document).ready(function($) {
			var contentId = '#mh-content-<?php echo esc_attr( $id ); ?>';
			var imageId = '#mh-image-<?php echo esc_attr( $id ); ?>';

			// 1. Content Slider (Fade, Control)
			$(contentId).slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: true,
				fade: true,
				asNavFor: imageId,
				cssEase: 'linear'
			});

			// 2. Image Slider (Center Mode, Follow)
			$(imageId).slick({
				slidesToShow: 3, // Focus on 1 main image, neighbors visible via overflowing CSS
				slidesToScroll: 3,
				asNavFor: contentId,
				dots: false,
				arrows: false,
				centerMode: true,
				variableWidth: false, 
				focusOnSelect: true,
				speed: 600,
				cssEase: 'cubic-bezier(0.25, 1, 0.5, 1)',
				responsive: [
					{
						breakpoint: 768,
						settings: {
							centerMode: true
						}
					}
				]
			});
		});
		</script>
		<?php
	}
}