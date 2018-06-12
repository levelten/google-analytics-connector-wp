<?php
/**
 * Copyright 2013 Alin Marcu
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit();

if ( ! class_exists( 'GAPWP_Frontend_Setup' ) ) {

	final class GAPWP_Frontend_Setup {

		private $gacwp;

		public function __construct() {
			$this->gacwp = GAPWP();

			// Styles & Scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'load_styles_scripts' ) );
		}

		/**
		 * Styles & Scripts conditional loading
		 *
		 * @param
		 *            $hook
		 */
		public function load_styles_scripts() {
			$lang = get_bloginfo( 'language' );
			$lang = explode( '-', $lang );
			$lang = $lang[0];

			/*
			 * Item reports Styles & Scripts
			 */
			if ( GAPWP_Tools::check_roles( $this->gacwp->config->options['access_front'] ) && $this->gacwp->config->options['frontend_item_reports'] ) {

				wp_enqueue_style( 'gacwp-nprogress', GAPWP_URL . 'common/nprogress/nprogress.css', null, GAPWP_CURRENT_VERSION );

				wp_enqueue_style( 'gacwp-frontend-item-reports', GAPWP_URL . 'front/css/item-reports.css', null, GAPWP_CURRENT_VERSION );

				$country_codes = GAPWP_Tools::get_countrycodes();
				if ( $this->gacwp->config->options['ga_target_geomap'] && isset( $country_codes[$this->gacwp->config->options['ga_target_geomap']] ) ) {
					$region = $this->gacwp->config->options['ga_target_geomap'];
				} else {
					$region = false;
				}

				wp_enqueue_style( "wp-jquery-ui-dialog" );

				wp_register_script( 'googlecharts', 'https://www.gstatic.com/charts/loader.js', array(), null );

				wp_enqueue_script( 'gacwp-nprogress', GAPWP_URL . 'common/nprogress/nprogress.js', array( 'jquery' ), GAPWP_CURRENT_VERSION );

				wp_enqueue_script( 'gacwp-frontend-item-reports', GAPWP_URL . 'common/js/reports5.js', array( 'gacwp-nprogress', 'googlecharts', 'jquery', 'jquery-ui-dialog' ), GAPWP_CURRENT_VERSION, true );

				/* @formatter:off */
				wp_localize_script( 'gacwp-frontend-item-reports', 'gacwpItemData', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'security' => wp_create_nonce( 'gacwp_frontend_item_reports' ),
					'dateList' => array(
						'today' => __( "Today", 'google-analytics-plus-wp' ),
						'yesterday' => __( "Yesterday", 'google-analytics-plus-wp' ),
						'7daysAgo' => sprintf( __( "Last %d Days", 'google-analytics-plus-wp' ), 7 ),
						'14daysAgo' => sprintf( __( "Last %d Days", 'google-analytics-plus-wp' ), 14 ),
						'30daysAgo' =>  sprintf( __( "Last %d Days", 'google-analytics-plus-wp' ), 30 ),
						'90daysAgo' =>  sprintf( __( "Last %d Days", 'google-analytics-plus-wp' ), 90 ),
						'365daysAgo' =>  sprintf( _n( "%s Year", "%s Years", 1, 'google-analytics-plus-wp' ), __('One', 'google-analytics-plus-wp') ),
						'1095daysAgo' =>  sprintf( _n( "%s Year", "%s Years", 3, 'google-analytics-plus-wp' ), __('Three', 'google-analytics-plus-wp') ),
					),
					'reportList' => array(
						'uniquePageviews' => __( "Unique Views", 'google-analytics-plus-wp' ),
						'users' => __( "Users", 'google-analytics-plus-wp' ),
						'organicSearches' => __( "Organic", 'google-analytics-plus-wp' ),
						'pageviews' => __( "Page Views", 'google-analytics-plus-wp' ),
						'visitBounceRate' => __( "Bounce Rate", 'google-analytics-plus-wp' ),
						'locations' => __( "Location", 'google-analytics-plus-wp' ),
						'referrers' => __( "Referrers", 'google-analytics-plus-wp' ),
						'searches' => __( "Searches", 'google-analytics-plus-wp' ),
						'trafficdetails' => __( "Traffic", 'google-analytics-plus-wp' ),
						'technologydetails' => __( "Technology", 'google-analytics-plus-wp' ),
					),
					'i18n' => array(
							__( "A JavaScript Error is blocking plugin resources!", 'google-analytics-plus-wp' ), //0
							__( "Traffic Mediums", 'google-analytics-plus-wp' ),
							__( "Visitor Type", 'google-analytics-plus-wp' ),
							__( "Search Engines", 'google-analytics-plus-wp' ),
							__( "Social Networks", 'google-analytics-plus-wp' ),
							__( "Unique Views", 'google-analytics-plus-wp' ),
							__( "Users", 'google-analytics-plus-wp' ),
							__( "Page Views", 'google-analytics-plus-wp' ),
							__( "Bounce Rate", 'google-analytics-plus-wp' ),
							__( "Organic Search", 'google-analytics-plus-wp' ),
							__( "Pages/Session", 'google-analytics-plus-wp' ),
							__( "Invalid response", 'google-analytics-plus-wp' ),
							__( "No Data", 'google-analytics-plus-wp' ),
							__( "This report is unavailable", 'google-analytics-plus-wp' ),
							__( "report generated by", 'google-analytics-plus-wp' ), //14
							__( "This plugin needs an authorization:", 'google-analytics-plus-wp' ) . ' <strong>' . __( "authorize the plugin", 'google-analytics-plus-wp' ) . '</strong>!',
							__( "Browser", 'google-analytics-plus-wp' ), //16
							__( "Operating System", 'google-analytics-plus-wp' ),
							__( "Screen Resolution", 'google-analytics-plus-wp' ),
							__( "Mobile Brand", 'google-analytics-plus-wp' ),
							__( "Future Use", 'google-analytics-plus-wp' ),
							__( "Future Use", 'google-analytics-plus-wp' ),
							__( "Future Use", 'google-analytics-plus-wp' ),
							__( "Future Use", 'google-analytics-plus-wp' ),
							__( "Future Use", 'google-analytics-plus-wp' ),
							__( "Future Use", 'google-analytics-plus-wp' ), //25
							__( "Time on Page", 'google-analytics-plus-wp' ),
							__( "Page Load Time", 'google-analytics-plus-wp' ),
							__( "Exit Rate", 'google-analytics-plus-wp' ),
							__( "Precision: ", 'google-analytics-plus-wp' ), //29
					),
					'colorVariations' => GAPWP_Tools::variations( $this->gacwp->config->options['theme_color'] ),
					'region' => $region,
					'mapsApiKey' => apply_filters( 'gacwp_maps_api_key', $this->gacwp->config->options['maps_api_key'] ),
					'language' => get_bloginfo( 'language' ),
					'filter' => $_SERVER["REQUEST_URI"],
					'viewList' => false,
					'scope' => 'front-item',
				 )
				);
				/* @formatter:on */
			}
		}
	}
}
