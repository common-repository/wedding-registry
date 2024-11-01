<?php
/**
 * Plugin Name: Wedding Registry
 * Plugin URI: https://themebeans.com/plugins/bean-registry
 * Description: The easiest way to beautifully add wedding registry locations to your website.
 * Version: 1.0.2
 * Author: Rich Tabor of ThemeBeans
 * Author URI: https://themebeans.com
 * License: GPL2
 * Requires at least: 4.0
 * Tested up to: 4.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Wedding_Registry' ) ) :

	/**
	 * Main Wedding Registry Class
	 *
	 * @package WeddingRegistry
	 * @author Richard Tabor (ThemeBeans)
	 * @link http://themebeans.com
	 */
	class Wedding_Registry {


		/**
		 * Plugin version.
		 *
		 * @var string Version.
		 */
		public $version = '1.0.2';

		private $wedding_registry_tinymce_uri;
		private $wedding_registry_tinymce_dir;

		/**
		 * Main WeddingRegistry Instance
		 *
		 * Ensures only one instance of WeddingRegistry is loaded or can be loaded.
		 *
		 * @static
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * WeddingRegistry Constructor.
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {

			// Define version constant.
			define( 'WEDDINGREGISTRY_VERSION', $this->version );

			// Hooks.
			$this->wedding_registry_tinymce_uri = plugin_dir_url( __FILE__ ) .'tinymce';
			$this->wedding_registry_tinymce_dir = dirname( __FILE__ ) .'/tinymce';

			add_action( 'init', array( &$this, 'init' ) );
			add_action( 'init', array( &$this, 'action_admin_init' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'admin_menu_styles' ) );
		}

		/**
		 * Initiate all the stuff.
		 *
		 * @return void
		 */
		function init() {
			add_action( 'wp_enqueue_scripts', array( &$this, 'frontend_style' ), 0 );
		}

		/**
		 * Add frontend scripts and styles.
		 *
		 * @return void
		 */
		public function frontend_style() {
			wp_enqueue_style( 'wedding-registry', plugin_dir_url( __FILE__ ) . '/css/bean-registry.css' , $this->version, 'all' );
		}

		/**
		 * Add admin scripts and styles.
		 */
		public function admin_menu_styles( $hook ) {
			if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
				global $weddingregistry;
				wp_enqueue_style( 'wedding-registry-admin', plugin_dir_url( __FILE__ ) . '/tinymce/css/popup.css' );
			}
		}

		/**
		 * Registers TinyMCE Rich Editor Buttons
		 *
		 * @return void
		 */
		function action_admin_init() {
			if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
				return;
			}

			if ( get_user_option( 'rich_editing' ) == 'true' && is_admin() ) {
				add_filter( 'mce_external_plugins', array( &$this, 'add_rich_plugins' ) );
				add_filter( 'mce_buttons', array( &$this, 'register_rich_buttons' ) );
			}
		}

		/**
		 * Define TinyMCE Editor Plugin
		 */
		function add_rich_plugins( $plugin_array ) {
	    		$plugin_array['WeddingRegistry'] = $this->wedding_registry_tinymce_uri . '/plugin.js';

			return $plugin_array;
		}

		/**
		 * Add TinyMCE Button
		 */
		function register_rich_buttons( $buttons ) {
			array_push( $buttons, '|', 'bean_registry_button' );
			return $buttons;
		}
	}

	new Wedding_Registry;

endif;

/**
 * Returns the main instance of WC to prevent the need to use globals.
 */
function weddingregistry() {
	return Wedding_Registry::instance();
}

/**
 * Flush the rewrite rules on activation.
 */
function weddingregistry_activation() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'weddingregistry_activation' );



/**
 * Also flush the rewrite rules on deactivation.
 */
function weddingregistry_deactivation() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'weddingregistry_activation' );



/**
 * Returns the shortcode output.
 */
if ( ! function_exists( 'wedding_registry_shortcode_output' ) ) :
	function wedding_registry_shortcode_output( $atts, $content = null ) {
		
		global $shortcode_registry;
		
		extract(shortcode_atts(array(
			'url' => '',
			'info' => ''
	     ), $atts));

		$output = '<a href="'.esc_url( $url ).'" class="wedding-registry-link '.esc_attr($info).'"><img src="'.plugin_dir_url( __FILE__ ).'images/'.esc_html( $info ).'.png" alt="'.esc_attr( $info ).'"></a>';

		return $output;

	}
endif;
add_shortcode( 'registry', 'wedding_registry_shortcode_output' );
