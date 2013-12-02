<?php
/*
 * Plugin Name: iThemes Exchange - Featured Video Add-on
 * Version: 0.0.2
 * Description: Adds the featured video to iThemes Exchange products.
 * Plugin URI: http://ithemes.com/exchange/featured-video/
 * Author: iThemes
 * Author URI: http://ithemes.com
 * iThemes Package: exchange-addon-featured-video
 
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
		'author'            => 'iThemes',
		'author_url'        => 'http://ithemes.com/exchange/featured-video/',
		'icon'              => ITUtility::get_url_from_file( dirname( __FILE__ ) . '/lib/images/featuredvideo50px.png' ),
		'file'              => dirname( __FILE__ ) . '/init.php',
		'category'          => 'pricing',
		'basename'          => plugin_basename( __FILE__ ),
		'labels'      => array(
			'singular_name' => __( 'Featured Video', 'LION' ),
		),
		'settings-callback' => 'it_exchange_featured_video_settings_callback',
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
function ithemes_exchange_addon_featured_video_updater_register( $updater ) { 
	    $updater->register( 'exchange-addon-featured-video', __FILE__ );
}
add_action( 'ithemes_updater_register', 'ithemes_exchange_addon_featured_video_updater_register' );
require( dirname( __FILE__ ) . '/lib/updater/load.php' );