<?php
/**
 * ExchangeWP Featured Video Add-on
 * @package exchange-addon-featured-video
 * @since 1.0.0
*/

/**
 * New API functions.
*/
include( 'api/load.php' );

/**
 * Exchange Add-ons require several hooks in order to work properly.
 * We've placed them all in one file to help add-on devs identify them more easily
*/
include( 'lib/required-hooks.php' );

/**
 * New Product Features added by the ExchangeWP Featured Video Add-on.
*/
require( 'lib/product-features/load.php' );

/**
 * Exchange will build your add-on's settings page for you and link to it from our add-on
 * screen. You are free to link from it elsewhere as well if you'd like... or to not use our API
 * at all. This file has all the functions related to registering the page, printing the form, and saving
 * the options. This includes the wizard settings. Additionally, we use the Exchange storage API to
 * save / retreive options. Add-ons are not required to do this.
*/
include( 'lib/addon-settings.php' );
