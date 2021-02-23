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
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class Port_Polio_Widget extends Widget_Base {

	public function get_name() {
		return 'Portpolio';
	}

	public function get_title() {
		return __( 'Portpolio' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
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

		//counting 0 to 20 array;
		$count_array = array();
		$count_array[0] = 'All';
		for($i=1;$i<=20;$i++){
		 $count_array[$i] = $i;
		}

		//category Loop
		$category = array();
		foreach(get_categories() as $cat){
		$category[$cat->cat_ID] = $cat->name;
		}

		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'elementor' ),
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
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
	

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'elementor' ),
				'type' => Controls_Manager::SELECT2,
				
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4'=>'H4'
				],
				'default' => 'h1',
				
			]
		);
		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		
		$this->add_control(
			'select_post_type',
			[
				'label' => __( 'Select Post Type', 'elementor' ),
				'type' => Controls_Manager::SELECT2,
				'options' => get_post_types(),
				'default' => 'post',
				
			]
		);
		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->add_control(
			'count_post',
			[
				'label' => __( 'Post Per page', 'elementor' ),
			
				'type' => Controls_Manager::SELECT,
				'options' => $count_array,
				'default' => '3',
				
			]
		);
		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->add_control(
			'post_category',
			[
				'label' => __( 'Select Category', 'elementor' ),
				 'show_label'=>true,
				'multiple'=>true,
				'label_block'=>true,
				'type' => Controls_Manager::SELECT2,
				'options' => $category,
				'default' => '1',
				
			]
		);
		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
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
	



// image option heading 
	  $this->add_control(
		'more_options1',
		[
			'label' => __( 'Image Option', 'plugin-name' ),
			'type' => \Elementor\Controls_Manager::HEADING,
			'separator' => 'before',
		]
	);
		
		// portpolio width
		$this->add_control(
			'auto_width',
			[
				'label' => __( 'Auto Width', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor' ),
				'label_off' => __( 'Hide', 'elementor' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'portpolio_image_width',
			[
				'label'     => __( 'Width', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => "100",
				],
				'size_units' => [ 'px', '%', 'em' ],
				
				'condition' => [
					'auto_width' => '',
				],
				'selectors' => [
					'.portpolio .portpolio-image' => ' width:{{SIZE}}{{UNIT}} !important;',
					
				],
			]
		);
		// portpolio height
		$this->add_control(
			'auto_height',
			[
				'label' => __( 'Auto Height', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor' ),
				'label_off' => __( 'Hide', 'elementor' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'portpolio_image_height',
			[
				'label'     => __( 'Height', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => "600",
				],
				'size_units' => [ 'px', '%', 'em' ],
				
				'condition' => [
					'auto_height' => '',
				],
				'selectors' => [
					'.portpolio .portpolio-image' => ' height:{{SIZE}}{{UNIT}} !important;',
					
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
					'.portpolio .portpolio-image' => ' -webkit-filter: blur({{SIZE}}px) !important',
					
				],
			]
		);

		//line break 
		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->add_control(
			'2',
			[
				'label' => __( 'Posts Area Option', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

	//set column
       $this->add_control(
			'set_column',
			[
				'label'     => __( 'Set Column', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 3,
				],
				
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 12,
					],
				],
				
				'selectors' => [
					'',
					
				],
			]
		);
		// Row margin.

		$this->add_control(
			'more_options3',
			[
				'label' => __( 'Post Text Option', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'post_text_margin',
			[
				'label' => __( 'Post Text Margin', 'Elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'.elementor .figure-caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'text_font_size',
			[
				'label'     => __( 'Text Font Size', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => "14",
				],
				'size_units' => [ 'px', '%', 'em' ],
				
				
				'selectors' => [
					'.portpolio .figure-caption' => ' font-size:{{SIZE}}{{UNIT}} !important;',
					
				],
			]
		);
		

		$this->end_controls_section();

	}

	protected function render( $instance = [] ) {
		global $post, $wp_query;
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
		$args = array(
			'numberposts' => $settings['count_post'],
			'post_type'   => $settings['select_post_type'],
			'category'=>$settings['post_category']
		  );
		
		$posts = get_posts($args);
		
		?>
	
	

<div class="portpolio">
		
	
		<div class="container">
		<div class="container">
						<<?php echo $settings['title_tag']; ?>><?php echo $title_text; ?></<?php echo $settings['title_tag']; ?>>
					</div>
			<div class="row">
			<?php
			
			foreach($posts as $post){
				$image= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ));
				if($image[0]==''){
					$image[0] = site_url().'/wp-content/uploads/woocommerce-placeholder-150x150.png';
				}
			
				$set_column  = intdiv(12,$settings['set_column']['size']);
				
			?>
			<a href="<?php echo get_permalink($post->ID);?>">
<div class="col-md-<?php echo $set_column;?>">
<figure class="figure">
                        <img src="<?php echo $image[0]; ?>" class="img-fluid mb-3 img-thumbnail portpolio-image" alt="<?php echo $post->post_title;?>">
                        <figcaption class="figure-caption"><?php echo $post->post_title;?></figcaption>
                </figure>

</div>
</a>

<?php
			}
?>
</div>
		 </div>
		

		
		
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
