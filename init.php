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
