<?php
/**
 * Copyright 2013 Alin Marcu
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit();

if ( ! class_exists( 'GACWP_Backend_Widgets' ) ) {

	class GACWP_Backend_Widgets {

		private $gacwp;

		public function __construct() {
			$this->gacwp = GACWP();
			if ( GACWP_Tools::check_roles( $this->gacwp->config->options['access_back'] ) && ( 1 == $this->gacwp->config->options['dashboard_widget'] ) ) {
				add_action( 'wp_dashboard_setup', array( $this, 'add_widget' ) );
			}
		}

		public function add_widget() {
			wp_add_dashboard_widget( 'gacwp-widget', __( "Google Analytics", 'google-analytics-connector-wp' ), array( $this, 'dashboard_widget' ), $control_callback = null );
		}

		public function dashboard_widget() {
			$projectId = 0;
			
			if ( empty( $this->gacwp->config->options['token'] ) ) {
				echo '<p>' . __( "This plugin needs an authorization:", 'google-analytics-connector-wp' ) . '</p><form action="' . menu_page_url( 'gacwp_settings', false ) . '" method="POST">' . get_submit_button( __( "Authorize Plugin", 'google-analytics-connector-wp' ), 'secondary' ) . '</form>';
				return;
			}
			
			if ( current_user_can( 'manage_options' ) ) {
				if ( $this->gacwp->config->options['tableid_jail'] ) {
					$projectId = $this->gacwp->config->options['tableid_jail'];
				} else {
					echo '<p>' . __( "An admin should asign a default Google Analytics Profile.", 'google-analytics-connector-wp' ) . '</p><form action="' . menu_page_url( 'gacwp_settings', false ) . '" method="POST">' . get_submit_button( __( "Select Domain", 'google-analytics-connector-wp' ), 'secondary' ) . '</form>';
					return;
				}
			} else {
				if ( $this->gacwp->config->options['tableid_jail'] ) {
					$projectId = $this->gacwp->config->options['tableid_jail'];
				} else {
					echo '<p>' . __( "An admin should asign a default Google Analytics Profile.", 'google-analytics-connector-wp' ) . '</p><form action="' . menu_page_url( 'gacwp_settings', false ) . '" method="POST">' . get_submit_button( __( "Select Domain", 'google-analytics-connector-wp' ), 'secondary' ) . '</form>';
					return;
				}
			}
			
			if ( ! ( $projectId ) ) {
				echo '<p>' . __( "Something went wrong while retrieving property data. You need to create and properly configure a Google Analytics account:", 'google-analytics-connector-wp' ) . '</p>';

				//echo '<p>' . __( "Something went wrong while retrieving property data. You need to create and properly configure a Google Analytics account:", 'google-analytics-connector-wp' ) . '</p> <form action="https://intelligencewp.com/how-to-set-up-google-analytics-on-your-website/" method="POST">' . get_submit_button( __( "Find out more!", 'google-analytics-connector-wp' ), 'secondary' ) . '</form>';
				return;
			}
			
			?>
<div id="gacwp-window-1"></div>
<?php
		}
	}
}
