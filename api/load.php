<?php
/**
 * iThemes Exchange Featured Video Add-on
 * load theme API functions
 * @package IT_Exchange_Addon_Featured_Video
 * @since 1.0.0
*/

if ( is_admin() ) {
	// Admin only
} else {
	// Frontend only
	include( 'theme.php' );
}