<?php
/**
 * MH Feature Card Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

class MH_Feature_Card_Widget extends Widget_Base {

	public function get_name() {
		return 'mh-feature-card';
	}

	public function get_title() {
		return esc_html__( 'MH Feature Card', 'mh-plug' );
	}

	public function get_icon() {
		return 'eicon-number-field';
	}

	public function get_categories() {
		return [ 'mh-plug-widgets' ];
	}

	protected function register_controls() {

		// --- CONTENT SECTION ---
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Card Content', 'mh-plug' ),
			]
		);

		$this->add_control(
			'card_number',
			[
				'label' => esc_html__( 'Number', 'mh-plug' ),
				'type' => Controls_Manager::TEXT,
				'default' => '01',
				'placeholder' => '01',
			]
		);

		$this->add_control(
			'card_icon',
			[
				'label' => esc_html__( 'Icon', 'mh-plug' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'card_title',
			[
				'label' => esc_html__( 'Title', 'mh-plug' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Feature Heading Title', 'mh-plug' ),
				'placeholder' => esc_html__( 'Enter title here', 'mh-plug' ),
			]
		);

		$this->end_controls_section();

		// --- STYLE SECTION: BOX ---
		$this->start_controls_section(
			'section_style_box',
			[
				'label' => esc_html__( 'Card Box', 'mh-plug' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'box_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'mh-plug' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .mh-feature-card-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => esc_html__( 'Padding', 'mh-plug' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 30,
					'right' => 30,
					'bottom' => 30,
					'left' => 30,
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .mh-feature-card-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mh-plug' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .mh-feature-card-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => esc_html__( 'Box Shadow', 'mh-plug' ),
				'selector' => '{{WRAPPER}} .mh-feature-card-wrapper',
				'default' => [
					'horizontal' => 0,
					'vertical' => 5,
					'blur' => 15,
					'spread' => 0,
					'color' => 'rgba(0,0,0,0.05)',
				]
			]
		);

		$this->end_controls_section();

		// --- STYLE SECTION: NUMBER ---
		$this->start_controls_section(
			'section_style_number',
			[
				'label' => esc_html__( 'Number', 'mh-plug' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'number_color',
			[
				'label' => esc_html__( 'Color', 'mh-plug' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .mh-feature-card-number' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'number_typography',
				'label' => esc_html__( 'Typography', 'mh-plug' ),
				'selector' => '{{WRAPPER}} .mh-feature-card-number',
				'fields_options' => [
					'typography' => [ 'default' => 'custom' ],
					'font_size' => [ 'default' => [ 'size' => 60, 'unit' => 'px' ] ],
					'font_weight' => [ 'default' => '700' ],
					'line_height' => [ 'default' => [ 'size' => 1, 'unit' => 'em' ] ],
				],
			]
		);

		$this->end_controls_section();

		// --- STYLE SECTION: ICON ---
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'mh-plug' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'mh-plug' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFC107', // Example Gold color
				'selectors' => [
					'{{WRAPPER}} .mh-feature-card-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mh-feature-card-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'mh-plug' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [ 'px' => [ 'min' => 10, 'max' => 100 ] ],
				'default' => [ 'unit' => 'px', 'size' => 30 ],
				'selectors' => [
					'{{WRAPPER}} .mh-feature-card-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mh-feature-card-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// --- STYLE SECTION: TITLE ---
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'mh-plug' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'mh-plug' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .mh-feature-card-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'mh-plug' ),
				'selector' => '{{WRAPPER}} .mh-feature-card-title',
				'fields_options' => [
					'typography' => [ 'default' => 'custom' ],
					'font_size' => [ 'default' => [ 'size' => 24, 'unit' => 'px' ] ],
					'font_weight' => [ 'default' => '600' ],
				],
			]
		);

		$this->add_responsive_control(
			'title_margin_top',
			[
				'label' => esc_html__( 'Margin Top', 'mh-plug' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
				'default' => [ 'unit' => 'px', 'size' => 30 ],
				'selectors' => [
					'{{WRAPPER}} .mh-feature-card-title' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="mh-feature-card-wrapper">
			
			<div class="mh-feature-card-header">
				<?php if ( ! empty( $settings['card_number'] ) ) : ?>
					<div class="mh-feature-card-number">
						<?php echo esc_html( $settings['card_number'] ); ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $settings['card_icon']['value'] ) ) : ?>
					<div class="mh-feature-card-icon">
						<?php Icons_Manager::render_icon( $settings['card_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $settings['card_title'] ) ) : ?>
				<div class="mh-feature-card-title">
					<?php echo esc_html( $settings['card_title'] ); ?>
				</div>
			<?php endif; ?>

		</div>
		<?php
	}
}