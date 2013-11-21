<?php
/**
 * iThemes Exchange Featured Video Add-on
 * @package IT_Exchange_Addon_Featured_Video
 * @since 1.0.0
*/

/**
 * Builds pricing option row for WP Dashboard Product Edit Page
 *
 * @since 1.0.0
 *
 * @param array $price_option Current Price Option
 * @param int $count Current Row count 
 * @param bool $add_new_first (optional) If this is the first rule being added, set to true
 * @return string HTML formated price option row
*/
function it_exchange_featured_video_addon_build_price_option( $price_option, $count, $add_new_first = false ) {
	
	$price = empty( $price_option['price'] ) ? '' : it_exchange_format_price( it_exchange_convert_from_database_number( $price_option['price'] ) );
	$label  = empty( $price_option['label'] ) ? '' : $price_option['label'];
	$default  = empty( $price_option['default'] ) ? 'unchecked' : $price_option['default'];
	
	$return = '<div class="it-exchange-featured-video-option">';
	
	$return .= '<div class="it-exchange-featured-video-content-price-option columns-wrapper" data-count="' . $count . '">';
	
	$return .= '<div class="it-exchange-featured-video-content-price column">';
	$return .= '<div class="column-inner">';
	$return .= '<input type="text" class="it-exchange-featured-video-price" name="it-exchange-featured-video-options[' . $count . '][price]" value="' . $price . '" />';
	$return .= '</div>';
	$return .= '</div>';
	
	$return .= '<div class="it-exchange-featured-video-content-label column">';
	$return .= '<div class="column-inner">';
	$return .= '<input type="text" class="it-exchange-featured-video-label" name="it-exchange-featured-video-options[' . $count . '][label]" value="' . $label . '" />';
	$return .= '<div class="it-exchange-featured-video-content-default">';
	if ( 'checked' == $default || $add_new_first ) {
		$return .= '<span class="it-exchange-featured-video-content-default-checkmark it-exchange-featured-video-content-default-checkmark-checked"></span>';	
		$return .= '<input type="hidden" class="it-exchange-featured-video-default" name="it-exchange-featured-video-options[' . $count . '][default]" value="checked" />';
	} else {
		$return .= '<span class="it-exchange-featured-video-content-default-checkmark"></span>';
		$return .= '<input type="hidden" class="it-exchange-featured-video-default" name="it-exchange-featured-video-options[' . $count . '][default]" value="unchecked" />';
	}
	$return .= '</div>';
	$return .= '</div>';
	$return .= '</div>';
	
	$return .= '<div class="it-exchange-featured-video-remove-option it-exchange-remove-item-wrapper column">';
	$return .= '<div class="column-inner">';
	$return .= '<a href="#" class="it-exchange-remove-item">×</a>';
	$return .= '</div>';
	$return .= '</div>';
		
	$return .= '</div>';
	
	$return .= '</div>';
	
	return $return;
	
}