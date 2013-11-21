<?php
/**
 * Featured Video class for THEME API
 *
 * @since 1.0.0
*/

class IT_Theme_API_Featured_Video implements IT_Theme_API {
	
	/**
	 * API context
	 * @var string $_context
	 * @since 1.0.0
	*/
	private $_context = 'featured-video';

	/**u
	 * Maps api tags to methods
	 * @var array $_tag_map
	 * @since 1.0.0
	*/
	var $_tag_map = array(
		'featuredvideo' => 'featured_video',
	);

	/**
	 * Current product in iThemes Exchange Global
	 * @var object $product
	 * @since 1.0.0
	*/
	private $product;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @return void
	*/
	function IT_Theme_API_Featured_Video() {
		// Set the current global product as a property
		$this->product = empty( $GLOBALS['it_exchange']['product'] ) ? false : $GLOBALS['it_exchange']['product'];
	}

	/**
	 * Returns the context. Also helps to confirm we are an iThemes Exchange theme API class
	 *
	 * @since 1.0.0
	 * 
	 * @return string
	*/
	function get_api_context() {
		return $this->_context;
	}

	/**
	 * The product featured video
	 *
	 * @since 1.0.0
	 * @return mixed
	*/
	function featured_video( $options=array() ) {

		// Return boolean if has flag was set
		if ( $options['supports'] )
			return it_exchange_product_supports_feature( $this->product->ID, 'featured-video', array( 'setting' => 'enabled' ) );
			
		// Return boolean if has flag was set
		if ( $options['has'] )
			return it_exchange_product_has_feature( $this->product->ID, 'featured-video', array( 'setting' => 'enabled' ) );

		if ( it_exchange_product_supports_feature( $this->product->ID, 'featured-video', array( 'setting' => 'enabled' ) )
				&& it_exchange_product_has_feature( $this->product->ID, 'featured-video', array( 'setting' => 'enabled' ) ) ) {
					
			$addon_settings = it_exchange_get_option( 'addon_featured_video' );
			
			$defaults   = array(
				'before'      => '',
				'after'       => '',
				'nyop-label'  => $addon_settings['featured-video-nyop-label'],
				'output-type' => $addon_settings['featured-video-output-type'],
			);
			$options = ITUtility::merge_defaults( $options, $defaults );

			$hidden = '';
			$result = '';
			
			$price_options = it_exchange_get_product_feature( $this->product->ID, 'featured-video', array( 'setting' => 'pricing-options' ) );
			//nyop = Name Your Own Price
			$nyop_enabled = it_exchange_get_product_feature( $this->product->ID, 'featured-video', array( 'setting' => 'nyop_enabled' ) );
			$nyop_min = it_exchange_get_product_feature( $this->product->ID, 'featured-video', array( 'setting' => 'nyop_min' ) );
			$nyop_max = it_exchange_get_product_feature( $this->product->ID, 'featured-video', array( 'setting' => 'nyop_max' ) );
		
			$result .= $options['before'];
				
			if ( !empty( $price_options ) ) {
				
				if ( 'no' === $nyop_enabled && 1 === count( $price_options ) ) {
					
					//Don't display anything, just pretend like the normal base-price is the setting
					//even though they selected featured-video with only one option!
					
				} else {
						
					switch ( $options['output-type'] ) {
					
						case 'select':
							$result .= '<select class="it-exchange-featured-video-base-price-selector" name="it_exchange_featured_video_base_price_selector">';
							foreach( $price_options as $price_option ) {
								$fprice = it_exchange_format_price( it_exchange_convert_from_database_number( $price_option['price'] ) );
								$result .= '<option data-price="' . $fprice . '" value="' . $price_option['price'] . '" ' . selected( 'checked', $price_option['default'], false ) . ' >' . $fprice;
								if ( !empty( $price_option['label'] ) )
									$result .= ' - ' . $price_option['label'];
								
								$result .= '</option>';
							}
							if ( 'yes' === $nyop_enabled ) {
								$result .= '<option value="other">' . $options['nyop-label'] . '</option>';
								$hidden = 'it-exchange-hidden';
							}
							$result .= '</select>';
							break;
	
						case 'radio':
						default:
							$result .= '<ul>';
							foreach( $price_options as $price_option ) {
								$fprice = it_exchange_format_price( it_exchange_convert_from_database_number( $price_option['price'] ) );
								$result .= '<li><input id="it-exchange-featured-video-' . $fprice . '" class="it-exchange-featured-video-base-price-selector" type="radio" name="it_exchange_featured_video_base_price_selector" data-price="' . $fprice . '" value="' . $price_option['price'] . '" ' . checked( 'checked', $price_option['default'], false ) . ' /><label for="it-exchange-featured-video-' . $fprice . '">' . $fprice;
								if ( !empty( $price_option['label'] ) )
									$result .= ' - ' . $price_option['label'];
								$result .= '</label>';
								$result .= '</li>';
							}
							if ( 'yes' === $nyop_enabled ) {
								$result .= '<li class="it-exchange-nyop-option"><input id="it-exchange-featured-video-nyop" class="it-exchange-featured-video-base-price-selector" type="radio" name="it_exchange_featured_video_base_price_selector" value="other" /><label for="it-exchange-featured-video-nyop">' . $options['nyop-label'] . '</label></li>';
								$hidden = 'it-exchange-hidden';
							}
							$result .= '</ul>';
							break;
						
					}
				
				}
			
			}
			
			if ( 'yes' === $nyop_enabled ) {
				$result .= '<div class="it-exchange-customer-nyop-section">';
				$result .= '<input class="it-exchange-featured-video-base-price-nyop-input ' . $hidden . '" type="text" name="it_exchange_featured_video_base_price_selector" value="" />';
				if ( empty( $price_options ) )
					$result .= ' ' . $options['nyop-label'];
					
				$result .= '<p class="it-exchange-featured-video-base-price-nyop-description ' . $hidden . '">';
				if ( !empty( $nyop_min ) && 0 < $nyop_min )
					$result .= '<span class="it-exchange-customer-price-min"><small>' . sprintf( __( 'Min: %s', 'LION' ), it_exchange_format_price( it_exchange_convert_from_database_number( $nyop_min ) ) ) . '</small></span>';
					
				if ( !empty( $nyop_max ) && 0 < $nyop_max )
					$result .= '<span class="it-exchange-customer-price-max"><small>' . sprintf( __( 'Max: %s', 'LION' ), it_exchange_format_price( it_exchange_convert_from_database_number( $nyop_max ) ) ) . '</small></span>';
				$result .= '</p>';
				$result .= '</div>';
			}
			
			global $post;
			$result .= '<input type="hidden" class="it-exchange-featured-video-product-id" name="it-exchange-featured-video-product-id" value="' . $post->ID . '">';

			$result .= $options['after'];

			return $result;
		}

		return false;
	}
}
