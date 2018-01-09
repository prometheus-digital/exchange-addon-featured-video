<?php
/*
 * Plugin Name: ExchangeWP - Featured Video Add-on
 * Version: 1.1.3
 * Description: Adds the featured video to ExchangeWP products.
 * Plugin URI: https://exchangewp.com/downloads/featured-video/
 * Author: ExchangeWP
 * Author URI: https://exchangewp.com
 * ExchangeWP Package: exchange-addon-featured-video

 * Installation:
 * 1. Download and unzip the latest release zip file.
 * 2. If you use the WordPress plugin uploader to install this plugin skip to step 4.
 * 3. Upload the entire plugin directory to your `/wp-content/plugins/` directory.
 * 4. Activate the plugin through the 'Plugins' menu in WordPress Administration.
 *
*/

/**
 * This registers our plugin as a featured video addon
 *
 * @since 1.0.0
 *
 * @return void
*/
function it_exchange_register_featured_video_addon() {
	$options = array(
		'name'              => __( 'Featured Video', 'LION' ),
		'description'       => __( 'Allows store owners to embed or upload a featured video to their products.', 'LION' ),
		'author'            => 'ExchangeWP',
		'author_url'        => 'https://exchangewp.com/downloads/featured-video/',
		'icon'              => ITUtility::get_url_from_file( dirname( __FILE__ ) . '/lib/images/featuredvideo50px.png' ),
		'file'              => dirname( __FILE__ ) . '/init.php',
		'category'          => 'video',
		'basename'          => plugin_basename( __FILE__ ),
		'labels'      => array(
			'singular_name' => __( 'Featured Video', 'LION' ),
		),
	);
	it_exchange_register_addon( 'featured-video', $options );
}
add_action( 'it_exchange_register_addons', 'it_exchange_register_featured_video_addon' );

/**
 * Loads the translation data for WordPress
 *
 * @uses load_plugin_textdomain()
 * @since 1.0.0
 * @return void
*/
function it_exchange_featured_video_set_textdomain() {
	load_plugin_textdomain( 'LION', false, dirname( plugin_basename( __FILE__  ) ) . '/lang/' );
}
add_action( 'plugins_loaded', 'it_exchange_featured_video_set_textdomain' );

/**
 * Registers Plugin with iThemes updater class
 *
 * @since 1.0.0
 *
 * @param object $updater ithemes updater object
 * @return void
*/
function exchange_featured_video_plugin_updater() {

	$license_data = get_transient( 'exchangewp_license_check' );

		if ( $license_data->license == 'valid' ) {

			$exchangewp_license = it_exchange_get_option( 'exchangewp_licenses' );
			$license = $exchangewp_license['exchangewp_license'];
			// setup the updater
			$edd_updater = new EDD_SL_Plugin_Updater( 'http://exchangewp.local/', __FILE__, array(
					'version' 		=> '1.1.3', 						// current version number
					'license' 		=> $license, 						// license key (used get_option above to retrieve from DB)
					'item_id'		 	=> 529,							 	  // name of this plugin
					'author' 	  	=> 'ExchangeWP',   		  // author of this plugin
					'url'       	=> home_url(),
					'wp_override' => true,
					'beta'		  	=> false
				)
			);

		}

}

 add_action( 'admin_init', 'exchange_featured_video_plugin_updater', 0 );
