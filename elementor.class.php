<?php
namespace ThemeElementor;

/**
 * Class Elementor_Main
 *
 * Main Plugin class
 */
class Theme_Elementor_Main {

	private static $_instance = null;

	/**
	 * Instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
            self::$_instance->init();
		}
		return self::$_instance;
	}

	/**
	 *  Plugin class init
	 */
	public function init() {

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

		add_action( 'elementor/elements/categories_registered', [ $this, 'register_widget_category' ] );
	}

	/**
	 * Include Widgets files
	 */
	private function include_widgets_files() {
		require_once( __DIR__ . '/templates/top-banner.php' );
		require_once( __DIR__ . '/templates/portpolio.php' );
	
	}

	/**
	 * Register Widgets
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Top_Banner_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Port_Polio_Widget() );
	
	}

	public function register_widget_category( $elements_manager ) {

		$elements_manager->add_category(
			'items',
			[
				'title' => __( 'Theme Elements' ),
				'icon' => 'fa fa-plug',
			],'files',
			[
				'title' => __( 'Theme Elements' ),
				'icon' => 'fa fa-plug',
			]
		);

	}
}

Theme_Elementor_Main::instance();
?>