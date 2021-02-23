<?php
namespace NHCThemeElementor\Widgets;

use Elementor\Repeater;
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
class NHC_Resources_List_Widget extends Widget_Base {

	public function get_name() {
		return 'nhc-resources-list';
	}

	public function get_title() {
		return __( 'NHC Resources List' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'nhc-items' ];
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
				'label' => esc_html__( 'Layout', 'elementor-pro' ),
			]
		);

        $repeater = new Repeater();

		$terms = array('0' => '-- All --');
		$categories = get_categories(array('hide_empty' => false));
		foreach($categories as $category) { $terms[$category->term_id] = $category->name; }
		$repeater->add_control(
			'category',
			[
				'label' => __( 'Category', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => $terms,
				'default' => 0
			]
		);

        $this->add_control(
            'categories',
            [
				'label' => __( 'Show resources only for categories', 'elementor-pro' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'category' => 0
                    ]
                ]
            ]
        );

		$tags = array();
		$wp_tags = get_terms(array('taxonomy' => 'post_tag'));
		if ($wp_tags) {
			foreach($wp_tags as $wp_tag) { $tags[$wp_tag->term_id] = $wp_tag->name; }
		}
        $this->add_control(
            'tags',
            [
				'label' => __( 'Tags', 'elementor-pro' ),
                'type' => Controls_Manager::SELECT2,
                'options' => $tags,
				'multiple' => true,
				'default' => [],
				'label_block' => true,
            ]
        );

		$this->add_control(
			'list_cols',
			[
				'label' => __( 'List Columns', 'mc-elementor-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(1 => '1 Column', 2 => '2 Columns', 3 => '3 Columns', 4 => '4 Columns'),
				'default' => 3,
			]
		);

		$this->add_control(
			'is_events',
			[
				'label' => __( 'Is Events', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-pro' ),
				'label_off' => __( 'No', 'elementor-pro' ),
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'events_type',
			[
				'label' => __( 'Events Type', 'mc-elementor-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => array('u' => 'Upcoming', 'past' => 'Past'),
				'default' => 'u',
				'condition' => [
					'is_events' => 'yes',
				],
			]
		);

		$this->add_control(
			'events_show_filter',
			[
				'label' => __( 'Show Month Filter', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-pro' ),
				'label_off' => __( 'No', 'elementor-pro' ),
				'default' => 'no',
				'condition' => [
					'is_events' => 'yes',
				],
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Posts Number', 'elementor-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 9,
				'separator' => 'before',
			]
		);

		$tags = array('h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6');
		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title Tag', 'mc-elementor-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => $tags,
				'default' => 'h3',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_image',
			[
				'label' => __( 'Show Image', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-pro' ),
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label' => __( 'Show Excerpt', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-pro' ),
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label' => __( 'Excerpt Length', 'elementor-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 25,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_date',
			[
				'label' => __( 'Show Date', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-pro' ),
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_read_more',
			[
				'label' => __( 'Show Read More', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-pro' ),
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'read_more',
			[
				'label' => __( 'Read More Text', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Read More', 'elementor-pro' ),
				'condition' => [
					'show_read_more' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label' => __( 'Show Pagination', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-pro' ),
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'default' => 'yes',
				'separator' => 'before',
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
				'label' => __( 'Layout', 'elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Columns margin.
		$this->add_control(
			'grid_style_columns_margin',
			[
				'label'     => __( 'Columns margin', 'elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 15,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpcap-grid-container' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
					
				],
			]
		);

		// Row margin.
		$this->add_control(
			'grid_style_rows_margin',
			[
				'label'     => __( 'Rows margin', 'elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 30,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpcap-grid-container' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render( $instance = [] ) {
		global $post, $wp_query;
		// Get settings.
		$r_page = 1;
		$current_post_id = $wp_query->get_queried_object_id();
		$settings = $this->get_settings();
	
		$date_format = get_option('date_format');
		$ttag = $settings['title_tag'];
		$categories = $settings['categories'];
		$tags = $settings['tags'];
		$posts_per_page = (int)$settings['posts_per_page'];
		$events_category = (int)get_theme_option('events_category');
		$news_category = (int)get_theme_option('news_category');

		if (!$posts_per_page) { $posts_per_page = 9; }
		if (is_admin() && isset($_GET['post'])) { $current_post_id = (int)$_GET['post']; }

		$cats = array();
		if ($categories && is_array($categories)) {
			foreach($categories as $category) {
				$cat_id = (int)$category['category'];
				if ($cat_id > 0) { $cats[] = $cat_id; }
			}
		}

		$args = array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => $posts_per_page);

		$archived_posts = $this->get_archived_posts();
		if ($archived_posts && count($archived_posts)) {
			$args['post__not_in'] = $archived_posts;
		}

		$nav_key = '';
		if (count($cats)) {
			$args['category__in'] = $cats;
			$nav_key = ''.md5(implode('-', $cats));
		}

		if ($tags && is_array($tags) && count($tags)) {
			$args['tag__in'] = $tags;
		}

		$nav_url = get_permalink($current_post_id);

		if (strpos($nav_url, '?')) { $nav_url .= '&'; } else { $nav_url .= '?'; }

		if (strlen($nav_key)) {
			$nav_url .= 'r_nkey='.$nav_key.'&';
		}

		$r_nkey = '';
		if (isset($_GET['r_nkey'])) { $r_nkey = $_GET['r_nkey']; }

		if (isset($_GET['r_page']) && $r_nkey == $nav_key) { $r_page = (int)$_GET['r_page']; }

		if ($r_page > 1) {
			$args['paged'] = $r_page;
		}

		if ($settings['is_events'] == 'yes') {
			if ($settings['events_type'] == 'u') {
				if (isset($_REQUEST['f_emonth']) && $_REQUEST['f_emonth']) {
					$edd = '01';
					$emm = substr($_REQUEST['f_emonth'], 4, 2);
					$eyy = substr($_REQUEST['f_emonth'], 0, 4);
					if ($emm == date('m')) { $edd = date('d'); }
					$mq_sdate = $eyy.'-'.$emm.'-'.$edd;
					$mq_edate = $eyy.'-'.$emm.'-31';
					$args['meta_query'] = array(
						array(
							'key' => 'date',
							'type'=> 'DATE',
							'value' => $mq_sdate,
							'compare' => '>='
						),
						array(
							'key' => 'date',
							'type'=> 'DATE',
							'value' => $mq_edate,
							'compare' => '<='
						)
					);
				} else {
					$args['meta_query'] = array(
						array(
							'key' => 'date',
							'type'=> 'DATE',
							'value' => date('Y-m-d'),
							'compare' => '>='
						)
					);
				}
				$args['orderby'] = 'meta_value';
				$args['order'] = 'ASC';
			} else {
				$args['meta_query'] = array(
					array(
						'key' => 'date',
						'type'=> 'DATE',
						'value' => date('Y-m-d'),
						'compare' => '<'
					)
				);
				$args['orderby'] = 'meta_value';
				$args['order'] = 'DESC';
			}
		}
		if ($current_post_id) {
			$tags = array();
			$post_tags = get_the_terms($current_post_id, 'post_tag');
			if ($post_tags) {
				foreach($post_tags as $post_tag) {
					$tags[] = $post_tag->term_id;
				}
			}
			if (count($tags)) {
				$args['tag__in'] = $tags;
			}
		}
		$posts_query = new WP_Query( $args );
		$posts_pages = $posts_query->max_num_pages; ?>

		<?php if ($settings['events_show_filter'] == 'yes') {
			$filter_months = $this->get_upcoming_months();
			if ($filter_months) { ?>
				<form class="b-filter-form">
					<div class="b-filter">
						<div class="b-filter__select">
							<select name="f_emonth" onchange="jQuery('.b-filter-form').submit()">
								<option value="">-- Select Month --</option>
								<?php foreach($filter_months as $filter_month) {
									$m_val = date('F Y', mktime(0, 0, 0, substr($filter_month, 4, 2), 1, substr($filter_month, 0, 4))); ?>
									<option value="<?php echo $filter_month; ?>"<?php if (isset($_REQUEST['f_emonth']) && $_REQUEST['f_emonth'] == $filter_month) { echo ' SELECTED'; } ?>><?php echo $m_val; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</form>
			<?php } ?>
		<?php } ?>

		<?php if ($posts_query->have_posts()) { ?>
			<div class="list-wrapp r-cols-<?php echo $settings['list_cols']; ?>">
				<?php while ($posts_query->have_posts()) { $posts_query->the_post(); setup_postdata($post);
					$post_id = get_the_ID();
					$post_image = get_post_thumbnail_id($post_id);
					$post_date = apply_filters( 'the_date', get_the_date(), $date_format, 'm', 'd', 'Y' );
					$post_url = get_permalink();
					$post_url_target = '';
					if (in_category($events_category, $post)) {
						$edate = get_field('date', $post_id);
						$etime = get_field('time', $post_id);
						$eloc = get_field('location', $post_id);
						if ($edate) {
							$post_date = date($date_format, strtotime($edate));
							if ($etime) {
								$post_date .= ' '.$etime;
							}
							if ($eloc) {
								$post_date .= ', '.$eloc;
							}
						}
					} else if (in_category($news_category, $post)) {
						$news_url = get_field('news_url', $post_id);
						if (strlen($news_url)) {
							$post_url = $news_url;
							$post_url_target = ' target="_blank"';
						}
					}
					?>
					<div class="b-post">
						<?php if ($settings['show_image'] == 'yes' && $post_image) { ?><a href="<?php echo $post_url; ?>" class="b-post__thumbnail" title="<?php the_title(); ?>"<?php echo $post_url_target; ?>><img src="<?php echo $this->get_thumbnail_url($post_image); ?>" alt=""></a><?php } ?>
						<<?php echo $ttag; ?> class="p-title"><a href="<?php echo $post_url; ?>" title="<?php the_title(); ?>"<?php echo $post_url_target; ?>><?php the_title(); ?></a></<?php echo $ttag; ?>>
						<?php if ($settings['show_date'] == 'yes') { ?><span><?php echo $post_date; ?></span><?php } ?>
						<?php 
					/*	if(!empty(get_the_tags())):
						$tages = '';
					  foreach(get_the_tags() as $tag){
                        $tages = $tages.$tag->name." | ";
						  }
						$tages = rtrim($tages," | ");
						?>
						<span><?php echo $tages;?></span>
                     <?php
					     endif;*/
					  
						//print_r(get_the_tags('name'));?>

						<?php if ($settings['show_excerpt'] == 'yes') { $this->render_excerpt($post); } ?>
						<?php if ($settings['show_read_more'] == 'yes') { ?><a href="<?php echo $post_url; ?>" class="b-post__read-more-red"<?php echo $post_url_target; ?>><?php echo $settings['read_more']; ?></a><?php } ?>
					</div>
				<?php } ?>
			</div>
		<?php } else { ?>
			<p>No resources found.</p>
		<?php } ?>
		<?php if ($settings['show_pagination'] == 'yes' && $posts_pages > 1) { ?>
			<div class="list-nav">
				<?php if ($r_page > 1) { ?>
					<a href="<?php echo $nav_url.'r_page='.($r_page - 1); ?>" class="page-numbers prev">&laquo; Previous</a>
				<?php } else { ?>
					<span class="page-numbers prev">&laquo; Previous</span>
				<?php } ?>
				<?php for ($p=1; $p<=$posts_pages; $p++) { ?>
					<?php if ($p == $r_page) { ?>
						<span class="page-numbers current"><?php echo $p; ?></span>
					<?php } else { ?>
						<a href="<?php echo $nav_url.'r_page='.$p; ?>" class="page-numbers"><?php echo $p; ?></a>
					<?php } ?>
				<?php } ?>
				<?php if ($r_page < $posts_pages) { ?>
					<a href="<?php echo $nav_url.'r_page='.($r_page + 1); ?>" class="page-numbers next">Next &raquo;</a>
				<?php } else { ?>
					<span class="page-numbers next">Next &raquo;</span>
				<?php } ?>
			</div>
		<?php } ?>
		<?php
		wp_reset_query();
	}

	public function render_excerpt($post) {
		add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
		add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );
		?>
		<p><?php echo $post->post_excerpt; ?></p>
		<?php
		remove_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );
		remove_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
	}

	public function filter_excerpt_more( $more ) {
		return '';
	}
	
	public function filter_excerpt_length() {
		$settings = $this->get_settings();
		return $settings['excerpt_length'];
	}

	public function get_archived_posts() {
		global $wpdb;
		$archived = array();
		$archived_posts = $wpdb->get_results(sprintf("SELECT post_id FROM %spostmeta WHERE meta_key = 'archived' AND meta_value = '1'", $wpdb->prefix));
		if ($archived_posts) {
			foreach($archived_posts as $archived_post) {
				$archived[] = $archived_post->post_id;
			}
		}
		return $archived;
	}

	public function get_upcoming_months() {
		global $wpdb;
		$upcoming_months = array();
		$all_dates = $wpdb->get_results(sprintf("SELECT * FROM %spostmeta WHERE meta_key = 'date' AND (meta_value+0) >= '%s' ORDER BY meta_value", $wpdb->prefix, date('Ymd')));
		if ($all_dates) {
			foreach($all_dates as $all_date) {
				$meta_value = substr($all_date->meta_value, 0, 6);
				if (!in_array($meta_value, $upcoming_months)) {
					$upcoming_months[] = $meta_value;
				}
			}
		}
		return $upcoming_months;
	}

	public function get_thumbnail_url($thumb_id) {
		$image_attributes = wp_get_attachment_image_src($thumb_id, 'large');
		if ($image_attributes) {
			return $image_attributes[0];
		}
	}
}
