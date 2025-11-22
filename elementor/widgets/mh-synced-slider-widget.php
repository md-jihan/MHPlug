<?php
/**
 * MH Synced Slider Widget
 * Two synced slick sliders: Left (Text) and Right (Images in Center Mode).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
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
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mh-plug' ),
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

		// --- STYLE: TEXT CONTENT ---
		$this->start_controls_section(
			'section_style_text',
			[
				'label' => esc_html__( 'Text Content (Left)', 'mh-plug' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        // Subtitle
        $this->add_control( 'heading_subtitle', [ 'label' => 'Subtitle', 'type' => Controls_Manager::HEADING ] );
		$this->add_control( 'subtitle_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-subtitle' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'subtitle_typography', 'selector' => '{{WRAPPER}} .mh-synced-subtitle' ] );

        // Title
        $this->add_control( 'heading_title_main', [ 'label' => 'Title', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'title_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-title' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typography', 'selector' => '{{WRAPPER}} .mh-synced-title' ] );

        // Description
        $this->add_control( 'heading_desc', [ 'label' => 'Description', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'desc_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-desc' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'desc_typography', 'selector' => '{{WRAPPER}} .mh-synced-desc' ] );

        // Button
        $this->add_control( 'heading_btn', [ 'label' => 'Button', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
        $this->add_control( 'btn_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-btn' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'btn_bg_color', [ 'label' => 'Background Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mh-synced-btn' => 'background-color: {{VALUE}};' ] ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'btn_typography', 'selector' => '{{WRAPPER}} .mh-synced-btn' ] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$slides = $settings['slides'];

		if ( empty( $slides ) ) {
			return;
		}

		$id = $this->get_id();
        // Unique IDs for the two sliders
		$text_slider_id = 'mh-text-slider-' . $id;
		$image_slider_id = 'mh-image-slider-' . $id;
		?>

		<div class="mh-synced-slider-wrapper">
            <div class="mh-text-slider" id="<?php echo esc_attr( $text_slider_id ); ?>">
				<?php foreach ( $slides as $slide ) : ?>
					<div class="mh-text-slide">
                        <div class="mh-text-content">
                            <?php if ( ! empty( $slide['subtitle'] ) ) : ?>
                                <h4 class="mh-synced-subtitle"><?php echo esc_html( $slide['subtitle'] ); ?></h4>
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

            <div class="mh-image-slider" id="<?php echo esc_attr( $image_slider_id ); ?>">
				<?php foreach ( $slides as $slide ) : ?>
					<div class="mh-image-slide">
                        <?php if ( ! empty( $slide['image']['id'] ) ) : ?>
                            <?php echo wp_get_attachment_image( $slide['image']['id'], $settings['image_size_size'], false, [ 'class' => 'mh-slider-img' ] ); ?>
                        <?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

        <script>
		jQuery(document).ready(function($) {
            // Left Text Slider
			$('#<?php echo esc_attr( $text_slider_id ); ?>').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: false,
				fade: true, // Text fades for smoother transition
				asNavFor: '#<?php echo esc_attr( $image_slider_id ); ?>' // Sync with image slider
			});

            // Right Image Slider (Center Mode)
			$('#<?php echo esc_attr( $image_slider_id ); ?>').slick({
				slidesToShow: 3, // Show 3 items for center mode effect
				slidesToScroll: 1,
				asNavFor: '#<?php echo esc_attr( $text_slider_id ); ?>', // Sync with text slider
				dots: false,
				centerMode: true, // The magic setting
				centerPadding: '0px', // Adjust if you want more side visibility
				focusOnSelect: true,
                arrows: false, // Hide default arrows, we will use custom ones if needed
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            centerMode: false // Disable center mode on mobile for simplicity
                        }
                    }
                ]
			});
		});
		</script>
		<?php
	}
}