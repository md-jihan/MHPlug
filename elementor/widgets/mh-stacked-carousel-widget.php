<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class MH_Stacked_Carousel_Widget extends Widget_Base {

	public function get_name() {
		return 'mh_stacked_carousel';
	}

	public function get_title() {
		return __( 'MH Stacked Carousel', 'mhds-plug' );
	}

	public function get_icon() {
		return 'eicon-post-slider';
	}

	public function get_categories() {
		return [ 'mh-plug-widgets' ];
	}

	/**
	 * Load Styles & Scripts
	 * Note: Slick is already loaded globally by mh-plug.php
	 */
	public function get_script_depends() {
		return [ 'mh-slick-js' ]; // Ensure Slick is available
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Carousel Items', 'mhds-plug' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'image',
			[
				'label'   => __( 'Image', 'mhds-plug' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'slides',
			[
				'label'       => __( 'Slides', 'mhds-plug' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
					[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
					[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
					[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
					[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
				],
				'title_field' => 'Slide',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Carousel Settings', 'mhds-plug' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'slide_height',
			[
				'label'      => __( 'Card Height', 'mhds-plug' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 600,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 400,
				],
				'selectors'  => [
					'{{WRAPPER}} .mh-stacked-item' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'slide_width',
			[
				'label'      => __( 'Card Width', 'mhds-plug' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 600,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 400,
				],
				'selectors'  => [
					'{{WRAPPER}} .mh-stacked-item' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['slides'] ) ) {
			return;
		}

		// Unique ID for this widget instance to avoid conflicts
		$id = 'mh-stacked-slider-' . $this->get_id();
		?>

		<div class="mh-stacked-wrap">
			<div class="mh-stacked-slider" id="<?php echo esc_attr( $id ); ?>">
				<?php foreach ( $settings['slides'] as $slide ) : ?>
					<div class="mh-stacked-item item">
						<?php 
						$image_url = ! empty( $slide['image']['url'] ) ? $slide['image']['url'] : '';
						if ( $image_url ) : ?>
							<img src="<?php echo esc_url( $image_url ); ?>" alt="Slide Image" />
							<div class="mh-overlay"></div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<script>
		jQuery(document).ready(function($) {
			var $slider = $('#<?php echo esc_attr( $id ); ?>');

			// 1. Image Conversion Function (as requested)
			$slider.find('.mh-stacked-item').each(function() {
				var $img = $(this).find('img');
				var src = $img.attr('src');
				if(src) {
					$(this).css({
						'background-image': 'url(' + src + ')',
						'background-size': 'cover',
						'background-position': 'center'
					});
					$img.hide();
				}
			});

			// 2. Initialize Slick Slider
			$slider.slick({
				centerMode: true,
				centerPadding: '0px',
				variableWidth: true,
				slidesToShow: 3,
				arrows: false,
				dots: false,
				focusOnSelect: true,
				infinite: true,
				speed: 400,
				cssEase: 'ease'
			});

			// 3. Custom Class Handling for 3D Depth
			// Slick sets .slick-center. We need to identify Next/Prev for specific transforms.
			// This logic runs on init and after change to ensure classes are correct.
			function updateClasses() {
				$slider.find('.slick-slide').removeClass('prev next next-2 prev-2');
				var $center = $slider.find('.slick-center');
				
				// Identify immediate siblings
				$center.prev('.slick-slide').addClass('prev');
				$center.next('.slick-slide').addClass('next');
				
				// Identify outer slides (2 steps away)
				$center.prev().prev('.slick-slide').addClass('prev-2');
				$center.next().next('.slick-slide').addClass('next-2');
			}

			$slider.on('init afterChange', function(event, slick, currentSlide){
				updateClasses();
			});
			
			// Initial call (Slick sometimes needs a moment)
			setTimeout(updateClasses, 50);
		});
		</script>
		<?php
	}
}