<?php
/**
 * Plugin Name: Custom Elemetor
 * Description: Elementor page builder Custom
 * Version: 1.0.0
 * Author: scott
 * Author URI: 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Theme_Elementor {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action('plugins_loaded', array($this, 'init'));
	}

	/**
	 * Initialize the plugin
	 */
	public function init() {

		// Check if Elementor installed
		if (!did_action('elementor/loaded')) {
			add_action('admin_notices', array($this, 'admin_notice_missing_elementor'));
			return;
		}

		require_once('elementor.class.php');
	}

	/**
	 * Admin notice
	 */
	public function admin_notice_missing_elementor() {
		if (isset($_GET['activate'])) { unset($_GET['activate']); }

		echo '<div class="notice notice-warning is-dismissible"><p>"    Theme Elementor Addon" requires "Elementor" to be installed and activated.</p></div>';
	}
}

// Instantiate Elementor_Post_Grid.
new Theme_Elementor();
?>