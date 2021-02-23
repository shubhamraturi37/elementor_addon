<?php
namespace ThemeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class Top_Banner_Widget extends Widget_Base {

	public function get_name() {
		return 'banner';
	}

	public function get_title() {
		return __( 'Top Banner' );
	}

	public function get_icon() {
		return 'eicon-image';
	}

	public function get_categories() {
		return [ 'items' ];
	}

	protected function _register_controls() {

		$this->wpcap_content_layout_options();

		$this->wpcap_style_layout_options();
		
	}

	/**
	 * Content Layout Options.
	 */
	private function wpcap_content_layout_options() {

		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'elementor' ),
			]
		);

		$this->add_control(
			'background_image_pc',
			[
				'label' => __( 'Image (desktop)', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				]
			]
		);

	

		$this->add_control(
			'background_image_mob',
			[
				'label' => __( 'Image (mobile)', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => __( 'Title', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor' ),
				'label_off' => __( 'Hide', 'elementor' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_text',
			[
				'label' => __( 'Title & Description', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'This is the heading', 'elementor' ),
				'placeholder' => __( 'Enter your title', 'elementor' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4'=>'H4'
				],
				'default' => 'h1',
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);
		

		$this->end_controls_section();

	}

	/**
	 * Style Layout Options.
	 */
	private function wpcap_style_layout_options() {

		// Layout.
		$this->start_controls_section(
			'section_layout_style',
			[
				'label' => __( 'Layout', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

      // Image dimension
	




		
		
		// banner width
		$this->add_control(
			'banner_image_width',
			[
				'label'     => __( 'Width (%)', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => "100",
				],
				'range'     => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .banner-image' => ' width:{{SIZE}}% !important;',
					
				],
			]
		);
		// banner height
		$this->add_control(
			'banner_image_height',
			[
				'label'     => __( 'Height (px)', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => "600",
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .banner-image' => ' height:{{SIZE}}px !important;',
					
				],
			]
		);
		// Columns margin.
		$this->add_control(
			'blur_image',
			[
				'label'     => __( 'Blur Image(px)', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .banner-image' => ' -webkit-filter: blur({{SIZE}}px) !important',
					
				],
			]
		);
		// Row margin.
		

		$this->end_controls_section();

	}

	protected function render( $instance = [] ) {
		// Get settings.
		$settings = $this->get_settings();
		$title_text = $this->get_value('title_text');
		$background_image_pc = $this->get_value('background_image_pc');
		$background_image_mob = $this->get_value('background_image_mob');
		if (!strlen($background_image_mob['url'])) {
			$background_image_mob['url'] = $background_image_pc['url'];
		}
		if (is_admin()) {
			if (!strlen($background_image_pc['url'])) {
				$background_image_pc['url'] = Utils::get_placeholder_image_src();
			}
			if (!strlen($background_image_mob['url'])) {
				$background_image_mob['url'] = Utils::get_placeholder_image_src();
			}
		}
		?>
		<style>
		.banner-image{
		width:<?php echo $settings['banner_image_width']['size'].'%'; ?>;
		height:<?php echo $settings['banner_image_height']['size'].'px'; ?>;
		-webkit-filter: blur(<?php echo $settings['blur_image']['size'].'px'?>);
		}
		</style>

		<div class="banner">
		
			<?php if ($settings['show_title'] == 'yes') { ?>
				<div class="banner__title">
					<div class="container">
						<<?php echo $settings['title_tag']; ?>><?php echo $title_text; ?></<?php echo $settings['title_tag']; ?>>
					</div>
				</div>
			<?php } ?>
			<?php if ($background_image_pc && strlen($background_image_pc['url'])) { ?><img  class="lg banner-image" src="<?php echo $background_image_pc['url']; ?>" alt=""><?php } ?>
			
		</div>
		<?php

	}


	protected function get_value( $field_key ) {
		global $post;
		$settings = $this->get_settings();
		$field_value = $settings[$field_key];
		if (isset($settings['__dynamic__']) && is_array($settings['__dynamic__'])) {
			if (isset($settings['__dynamic__'][$field_key])) {
				switch($field_key) {
					case 'title_text':
						$field_value = $post->post_title;
					break;
					case 'background_image_pc':
						$thumb_id = get_post_thumbnail_id($post->ID);
						if ($thumb_id) {
							$image_attributes = wp_get_attachment_image_src($thumb_id, 'full');
							$field_value['url'] = $image_attributes[0];
						}
					break;
				}
			}
		}
		return $field_value;
	}
}
