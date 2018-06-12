<?php
/**
 * Plugin Name: Google Analytics Connector WP
 * Plugin URI: https://intelligencewp.com/google-analytics-plus-wordpress
 * Description: Displays Google Analytics Reports and Real-Time Statistics in your dashboard. Automatically inserts the tracking code in every page of your website.
 * Author: LevelTen
 * Version: 5.4.0
 * Author URI: https://getlevelten.com
 * Text Domain: google-analytics-plus-wp
 * Domain Path: /languages
 *
 * This plugin was originally created by Alin Marcu (https://deconf.com).
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit();

// Plugin Version
if ( ! defined( 'GACWP_CURRENT_VERSION' ) ) {
	define( 'GACWP_CURRENT_VERSION', '5.4.0.0-dev' );
}

if ( ! defined( 'GACWP_ENDPOINT_URL' ) ) {
	define( 'GACWP_ENDPOINT_URL', '' );
}


if ( ! class_exists( 'GACWP_Manager' ) ) {

	final class GACWP_Manager {

		private static $instance = null;

		public $config = null;

		public $frontend_actions = null;

		public $common_actions = null;

		public $backend_actions = null;

		public $tracking = null;

		public $frontend_item_reports = null;

		public $backend_setup = null;

		public $frontend_setup = null;

		public $backend_widgets = null;

		public $backend_item_reports = null;

		public $gapi_controller = null;

		/**
		 * Construct forbidden
		 */
		private function __construct() {
			if ( null !== self::$instance ) {
				_doing_it_wrong( __FUNCTION__, __( "This is not allowed, read the documentation!", 'google-analytics-plus-wp' ), '4.6' );
			}
		}

		/**
		 * Clone warning
		 */
		private function __clone() {
			_doing_it_wrong( __FUNCTION__, __( "This is not allowed, read the documentation!", 'google-analytics-plus-wp' ), '4.6' );
		}

		/**
		 * Wakeup warning
		 */
		private function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( "This is not allowed, read the documentation!", 'google-analytics-plus-wp' ), '4.6' );
		}

		/**
		 * Creates a single instance for GACWP and makes sure only one instance is present in memory.
		 *
		 * @return GACWP_Manager
		 */
		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
				self::$instance->setup();
				self::$instance->config = new GACWP_Config();
			}
			return self::$instance;
		}

		/**
		 * Defines constants and loads required resources
		 */
		private function setup() {

			// Plugin Path
			if ( ! defined( 'GACWP_DIR' ) ) {
				define( 'GACWP_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin URL
			if ( ! defined( 'GACWP_URL' ) ) {
				define( 'GACWP_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin main File
			if ( ! defined( 'GACWP_FILE' ) ) {
				define( 'GACWP_FILE', __FILE__ );
			}

			/*
			 * Load Tools class
			 */
			include_once ( GACWP_DIR . 'tools/tools.php' );

			/*
			 * Load Config class
			 */
			include_once ( GACWP_DIR . 'config.php' );

			/*
			 * Load GAPI Controller class
			 */
			include_once ( GACWP_DIR . 'tools/gapi.php' );

			/*
			 * Plugin i18n
			 */
			add_action( 'init', array( self::$instance, 'load_i18n' ) );

			/*
			 * Plugin Init
			 */
			add_action( 'init', array( self::$instance, 'load' ) );

			/*
			 * Include Install
			 */
			include_once ( GACWP_DIR . 'install/install.php' );
			register_activation_hook( GACWP_FILE, array( 'GACWP_Install', 'install' ) );

			/*
			 * Include Uninstall
			 */
			include_once ( GACWP_DIR . 'install/uninstall.php' );
			register_uninstall_hook( GACWP_FILE, array( 'GACWP_Uninstall', 'uninstall' ) );

			/*
			 * Load Frontend Widgets
			 * (needed during ajax)
			 */
			include_once ( GACWP_DIR . 'front/widgets.php' );

			/*
			 * Add Frontend Widgets
			 * (needed during ajax)
			 */
			add_action( 'widgets_init', array( self::$instance, 'add_frontend_widget' ) );
		}

		/**
		 * Load i18n
		 */
		public function load_i18n() {
			load_plugin_textdomain( 'google-analytics-plus-wp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Register Frontend Widgets
		 */
		public function add_frontend_widget() {
			register_widget( 'GACWP_Frontend_Widget' );
		}

		/**
		 * Conditional load
		 */
		public function load() {
			if ( is_admin() ) {
				if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
					if ( GACWP_Tools::check_roles( self::$instance->config->options['access_back'] ) ) {
						/*
						 * Load Backend ajax actions
						 */
						include_once ( GACWP_DIR . 'admin/ajax-actions.php' );
						self::$instance->backend_actions = new GACWP_Backend_Ajax();
					}

					/*
					 * Load Frontend ajax actions
					 */
					include_once ( GACWP_DIR . 'front/ajax-actions.php' );
					self::$instance->frontend_actions = new GACWP_Frontend_Ajax();

					/*
					 * Load Common ajax actions
					 */
					include_once ( GACWP_DIR . 'common/ajax-actions.php' );
					self::$instance->common_actions = new GACWP_Common_Ajax();

					if ( self::$instance->config->options['backend_item_reports'] ) {
						/*
						 * Load Backend Item Reports for Quick Edit
						 */
						include_once ( GACWP_DIR . 'admin/item-reports.php' );
						self::$instance->backend_item_reports = new GACWP_Backend_Item_Reports();
					}
				} else if ( GACWP_Tools::check_roles( self::$instance->config->options['access_back'] ) ) {

					/*
					 * Load Backend Setup
					 */
					include_once ( GACWP_DIR . 'admin/setup.php' );
					self::$instance->backend_setup = new GACWP_Backend_Setup();

					if ( self::$instance->config->options['dashboard_widget'] ) {
						/*
						 * Load Backend Widget
						 */
						include_once ( GACWP_DIR . 'admin/widgets.php' );
						self::$instance->backend_widgets = new GACWP_Backend_Widgets();
					}

					if ( self::$instance->config->options['backend_item_reports'] ) {
						/*
						 * Load Backend Item Reports
						 */
						include_once ( GACWP_DIR . 'admin/item-reports.php' );
						self::$instance->backend_item_reports = new GACWP_Backend_Item_Reports();
					}
				}
			} else {
				if ( GACWP_Tools::check_roles( self::$instance->config->options['access_front'] ) ) {
					/*
					 * Load Frontend Setup
					 */
					include_once ( GACWP_DIR . 'front/setup.php' );
					self::$instance->frontend_setup = new GACWP_Frontend_Setup();

					if ( self::$instance->config->options['frontend_item_reports'] ) {
						/*
						 * Load Frontend Item Reports
						 */
						include_once ( GACWP_DIR . 'front/item-reports.php' );
						self::$instance->frontend_item_reports = new GACWP_Frontend_Item_Reports();
					}
				}

				if ( ! GACWP_Tools::check_roles( self::$instance->config->options['track_exclude'], true ) && 'disabled' != self::$instance->config->options['tracking_type'] ) {
					/*
					 * Load tracking class
					 */
					include_once ( GACWP_DIR . 'front/tracking.php' );
					self::$instance->tracking = new GACWP_Tracking();
				}
			}
		}
	}
}

/**
 * Returns a unique instance of GACWP
 */
function GACWP() {
	return GACWP_Manager::instance();
}

/*
 * Start GACWP
 */
GACWP();
