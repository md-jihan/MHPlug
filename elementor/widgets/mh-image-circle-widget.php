<?php
/**
 * MH Image Circle Widget
 *
 * A widget that displays an image in a circle with text below it.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;

class MH_Image_Circle_Widget extends Widget_Base {

    public function get_name() {
        return 'mh-image-circle';
    }

    public function get_title() {
        return esc_html__('MH Image Circle', 'mh-plug');
    }

    public function get_icon() {
        return 'mhi-border-image'; // Using a standard Elementor icon
    }

    public function get_categories() {
        return ['mh-plug-widgets']; 
    }

    protected function register_controls() {

        // --- Content Tab: Image & Text ---
        $this->start_controls_section(
            'section_content_image_text',
            [
                'label' => esc_html__('Image & Text', 'mh-plug'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'mh-plug'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image_size',
                'default' => 'medium',
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'text',
            [
                'label' => esc_html__('Text', 'mh-plug'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Melody Mates', 'mh-plug'),
                'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'mh-plug'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'mh-plug'),
                'dynamic' => ['active' => true],
            ]
        );
        
        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__( 'Alignment', 'mh-plug' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'mh-plug' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'mh-plug' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'mh-plug' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .mh-image-circle-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // --- Style Tab: Image ---
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__('Image', 'mh-plug'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'image_size_control',
            [
                'label' => esc_html__( 'Image Size (Circle Diameter)', 'mh-plug' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 50, 'max' => 500, 'step' => 1],
                    '%' => ['min' => 10, 'max' => 100],
                ],
                'default' => [ 'unit' => 'px', 'size' => 180 ],
                'size_units' => [ 'px', '%', 'em', 'vw' ],
                'selectors' => [
                    '{{WRAPPER}} .mh-image-circle-image-wrapper' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_spacing',
            [
                'label' => esc_html__('Spacing Below Image', 'mh-plug'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 100]],
                'default' => [ 'unit' => 'px', 'size' => 20 ],
                'selectors' => [
                    '{{WRAPPER}} .mh-image-circle-image-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .mh-image-circle-image-wrapper',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid', // Or 'dashed', 'dotted'
                    ],
                    'width' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => 2,
                        ],
                    ],
                    'color' => [
                        'default' => '#DDDDDD',
                    ],
                ],
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'image_border_style',
            [
                'label' => esc_html__( 'Border Style', 'mh-plug' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'solid' => esc_html__( 'Solid', 'mh-plug' ),
                    'dashed' => esc_html__( 'Dashed', 'mh-plug' ),
                    'dotted' => esc_html__( 'Dotted', 'mh-plug' ),
                    'double' => esc_html__( 'Double', 'mh-plug' ),
                    'groove' => esc_html__( 'Groove', 'mh-plug' ),
                    'ridge' => esc_html__( 'Ridge', 'mh-plug' ),
                    'inset' => esc_html__( 'Inset', 'mh-plug' ),
                    'outset' => esc_html__( 'Outset', 'mh-plug' ),
                    'none' => esc_html__( 'None', 'mh-plug' ),
                ],
                'default' => 'dotted', // Default to dotted as in the image
                'selectors' => [
                    '{{WRAPPER}} .mh-image-circle-image-wrapper' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mh-plug'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'unit' => '%',
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mh-image-circle-image-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .mh-image-circle-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', // To ensure image itself is also round
                ],
            ]
        );

        $this->add_control(
            'image_hover_animation',
            [
                'label' => esc_html__('Hover Animation', 'mh-plug'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_section();

        // --- Style Tab: Text ---
        $this->start_controls_section(
            'section_style_text',
            [
                'label' => esc_html__('Text', 'mh-plug'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Color', 'mh-plug'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mh-image-circle-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .mh-image-circle-text',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .mh-image-circle-text',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('wrapper', 'class', 'mh-image-circle-wrapper');
        
        // Image Wrapper attributes
        $image_wrapper_class = 'mh-image-circle-image-wrapper';
        if ( ! empty( $settings['image_hover_animation'] ) ) {
            $image_wrapper_class .= ' elementor-animation-' . $settings['image_hover_animation'];
        }
        $this->add_render_attribute('image_wrapper', 'class', $image_wrapper_class);


        // Link attributes
        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_link_attributes('link', $settings['link']);
            $link_tag = 'a';
        } else {
            $link_tag = 'div';
        }

        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <<?php echo $link_tag; ?> <?php echo $this->get_render_attribute_string('link'); ?>>
                <div <?php echo $this->get_render_attribute_string('image_wrapper'); ?>>
                    <?php if ( $settings['image']['url'] ) : ?>
                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_size', 'image' ); ?>
                    <?php endif; ?>
                </div>
                <?php if ( $settings['text'] ) : ?>
                    <div class="mh-image-circle-text">
                        <?php echo esc_html( $settings['text'] ); ?>
                    </div>
                <?php endif; ?>
            </<?php echo $link_tag; ?>>
        </div>
        <?php
    }

    /**
     * Render widget output in the editor (backend).
     */
    protected function _content_template() {
        ?>
        <#
        var image = {
            id: settings.image.id,
            url: settings.image.url,
            size: settings.image_size_size,
            dimension: settings.image_size_custom_dimension,
            model: view.getEditModel()
        };

        var imageUrl = elementor.imagesManager.getImageUrl( image );

        var link_url = settings.link.url;
        var linkTag = link_url ? 'a' : 'div';
        var linkAttrs = '';

        if ( link_url ) {
            linkAttrs += 'href="' + link_url + '"';
            if ( settings.link.is_external ) {
                linkAttrs += ' target="_blank"';
            }
            if ( settings.link.nofollow ) {
                linkAttrs += ' rel="nofollow"';
            }
        }
        
        var imageWrapperClasses = 'mh-image-circle-image-wrapper';
        if ( settings.image_hover_animation ) {
            imageWrapperClasses += ' elementor-animation-' + settings.image_hover_animation;
        }

        #>
        <div class="mh-image-circle-wrapper">
            <{{{ linkTag }}} {{{ linkAttrs }}}>
                <div class="{{{ imageWrapperClasses }}}">
                    <# if ( imageUrl ) { #>
                        <img class="mh-image-circle-image" src="{{{ imageUrl }}}" alt="{{{ settings.text }}}" />
                    <# } #>
                </div>
                <# if ( settings.text ) { #>
                    <div class="mh-image-circle-text">
                        {{{ settings.text }}}
                    </div>
                <# } #>
            </{{{ linkTag }}}>
        </div>
        <?php
    }
}