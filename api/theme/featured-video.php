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
			);
			
			$options = ITUtility::merge_defaults( $options, $defaults );
			
			$result = '';
			
			$product_featured_video = it_exchange_get_product_feature( $this->product->ID, 'featured-video' );
			
			if ( isset( $product_featured_video ) && ! empty( $product_featured_video ) ) {
				if ( strpos( $product_featured_video, '[video' ) !== false ) {
					echo '<div class="featured-video-wrapper featured-video-uploaded">';
						echo do_shortcode( $product_featured_video );
					echo '</div>';
				} else {
					echo '<div class="featured-video-wrapper featured-video-embeded">';
						echo wp_oembed_get( $product_featured_video );
					echo '</div>';
				}
			}
			
			return $result;
		}

		return false;
	}
}
