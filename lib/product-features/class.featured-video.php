<?php
/**
 * This will control featured video options on the frontend products
 *
 * @since 1.0.0
 * @package IT_Exchange_Addon_Featured_Video
*/


class IT_Exchange_Addon_Product_Feature_Product_Featured_Video {

	/**
	 * Constructor. Registers hooks
	 *
	 * @since 1.0.0
	 * @return void
	*/
	function IT_Exchange_Addon_Product_Feature_Product_Featured_Video() {
		if ( is_admin() ) {
			add_action( 'load-post-new.php', array( $this, 'init_feature_metaboxes' ) );
			add_action( 'load-post.php', array( $this, 'init_feature_metaboxes' ) );
			add_action( 'it_exchange_save_product', array( $this, 'save_feature_on_product_save' ) );
		}
		add_action( 'it_exchange_enabled_addons_loaded', array( $this, 'add_feature_support_to_product_types' ) );
		add_action( 'it_exchange_update_product_feature_featured-video', array( $this, 'save_feature' ), 9, 3 );
		add_filter( 'it_exchange_get_product_feature_featured-video', array( $this, 'get_feature' ), 9, 3 );
		add_filter( 'it_exchange_product_has_feature_featured-video', array( $this, 'product_has_feature') , 9, 2 );
		add_filter( 'it_exchange_product_supports_feature_featured-video', array( $this, 'product_supports_feature') , 9, 2 );
	}

	/**
	 * Register the product feature and add it to enabled product-type addons
	 *
	 * @since 1.0.0
	*/
	function add_feature_support_to_product_types() {
		// Register the product feature
		$slug        = 'featured-video';
		$description = __( "This displays a custom pricing options for all Exchange product types", 'LION' );
		it_exchange_register_product_feature( $slug, $description );

		// Add it to all enabled product-type addons
		$products = it_exchange_get_enabled_addons( array( 'category' => 'product-type' ) );
		foreach( $products as $key => $params ) {
			it_exchange_add_feature_support_to_product_type( 'featured-video', $params['slug'] );
		}
	}

	/**
	 * Register's the metabox for any product type that supports the feature
	 *
	 * @since 1.0.0
	 * @return void
	*/
	function init_feature_metaboxes() {
		
		global $post;
		
		if ( isset( $_REQUEST['post_type'] ) ) {
			$post_type = $_REQUEST['post_type'];
		} else {
			if ( isset( $_REQUEST['post'] ) )
				$post_id = (int) $_REQUEST['post'];
			elseif ( isset( $_REQUEST['post_ID'] ) )
				$post_id = (int) $_REQUEST['post_ID'];
			else
				$post_id = 0;

			if ( $post_id )
				$post = get_post( $post_id );

			if ( isset( $post ) && !empty( $post ) )
				$post_type = $post->post_type;
		}
			
		if ( !empty( $_REQUEST['it-exchange-product-type'] ) )
			$product_type = $_REQUEST['it-exchange-product-type'];
		else
			$product_type = it_exchange_get_product_type( $post );
		
		if ( !empty( $post_type ) && 'it_exchange_prod' === $post_type ) {
			if ( !empty( $product_type ) &&  it_exchange_product_type_supports_feature( $product_type, 'featured-video' ) )
				add_action( 'it_exchange_product_metabox_callback_' . $product_type, array( $this, 'register_metabox' ), 5 );
		}
		
	}

	/**
	 * Registers the feature metabox for a specific product type
	 *
	 * Hooked to it_exchange_product_metabox_callback_[product-type] where product type supports the feature 
	 *
	 * @since 1.0.0
	 * @return void
	*/
	function register_metabox() {
		add_meta_box( 'it-exchange-product-featured-video', __( 'Featured Video', 'LION' ), array( $this, 'print_metabox' ), 'it_exchange_prod', 'it_exchange_side', 'default' );
	}

	/**
	 * This echos the feature metabox.
	 *
	 * @since 1.0.0
	 * @return void
	*/
	function print_metabox( $post ) {
		// Grab the iThemes Exchange Product object from the WP $post object
		$product = it_exchange_get_product( $post );

		// Set the value of the feature for this product
		$product_featured_video = it_exchange_get_product_feature( $product->ID, 'featured-video' );
		
		$video_host = '';
		
		if ( ! empty( $product_featured_video ) ) {
			$video_host = parse_url( $product_featured_video );
			$site_host  = parse_url( get_bloginfo( 'url' ) );
		}
		
		// Echo the form field
		do_action( 'it_exchange_before_print_metabox_featured_video', $product );
		?>
			<label for="featured-video-field"><?php _e( 'Featured Video', 'LION' ); ?></label>
			<div class="featured-video-insert" data-current="<?php echo $product_featured_video; ?>">
				<input type="url" placeholder="http://" id="featured-video" name="it-exchange-product-featured-video" value="<?php echo $product_featured_video; ?>" tabindex="4" />
				<a href class="it-exchange-featured-video-upload"><?php _e( 'Upload', 'LION' ) ?></a>
			</div>
			<div class="featured-video-placeholder">
				<?php
					if ( ! empty( $video_host ) ) {
						if ( $video_host['host'] == $site_host['host'] ) {
							echo '<div class="featured-video-wrapper featured-video-uploaded">';
								echo do_shortcode( '[video width="400" height="225" src="' . $product_featured_video . '"][/video]' );
							echo '</div>';
						} else {
							echo '<div class="featured-video-wrapper featured-video-embeded">';
								echo wp_oembed_get( $product_featured_video );
							echo '</div>';
						}
					}
				?>
			</div>
		<?php
		do_action( 'it_exchange_after_print_metabox_featured_video', $product );
	}

	/**
	 * This saves the value
	 *
	 * @since 1.0.0
	 *
	 * @param object $post wp post object
	 * @return void
	*/
	function save_feature_on_product_save() {
		// Abort if we can't determine a product type
		if ( ! $product_type = it_exchange_get_product_type() )
			return;

		// Abort if we don't have a product ID
		$product_id = empty( $_POST['ID'] ) ? false : $_POST['ID'];
		if ( ! $product_id )
			return;

		// Abort if this product type doesn't support this feature
		if ( ! it_exchange_product_type_supports_feature( $product_type, 'featured-video' ) )
			return;

		// Abort if key for feature option isn't set in POST data
		if ( ! isset( $_POST['it-exchange-product-featured-video'] ) )
			return;

		// Get new value from post
		$new_value = $_POST['it-exchange-product-featured-video'];

		// Save new value
		it_exchange_update_product_feature( $product_id, 'featured-video', $new_value );
	}

	/**
	 * Return the product's features
	 *
	 * @since 1.0.0
	 * @param mixed $existing the values passed in by the WP Filter API. Ignored here.
	 * @param integer product_id the WordPress post ID
	 * @return string product feature
	*/
	function save_feature( $product_id, $new_value, $options=array() ) {
		if ( ! it_exchange_get_product( $product_id ) )
			return false;
		update_post_meta( $product_id, '_it-exchange-product-featured-video', $new_value );
	}

	/**
	 * Return the product's features
	 *
	 * @since 0.4.0
	 *
	 * @param mixed $existing the values passed in by the WP Filter API. Ignored here.
	 * @param integer product_id the WordPress post ID
	 * @return string product feature
	*/
	function get_feature( $existing, $product_id, $options=array() ) {
		$value = get_post_meta( $product_id, '_it-exchange-product-featured-video', true );
		return $value;
	}

	/**
	 * Does the product have the feature?
	 *
	 * @since 1.0.0
	 * @param mixed $result Not used by core
	 * @param integer $product_id
	 * @return boolean
	*/
	function product_has_feature( $result, $product_id, $options=array() ) {
		$defaults['setting'] = 'enabled';
		$options = ITUtility::merge_defaults( $options, $defaults );

		// Does this product type support this feature?
		if ( false === $this->product_supports_feature( false, $product_id, $options ) )
			return false;

		// If it does support, does it have it?
		return (boolean) $this->get_feature( false, $product_id, $options );
	}

	/**
	 * Does the product support this feature?
	 *
	 * This is different than if it has the feature, a product can 
	 * support a feature but might not have the feature set.
	 *
	 * @since 1.0.0
	 * @param mixed $result Not used by core
	 * @param integer $product_id
	 * @return boolean
	*/
	function product_supports_feature( $result, $product_id ) {
		// Does this product type support this feature?
		$product_type = it_exchange_get_product_type( $product_id );
		if ( ! it_exchange_product_type_supports_feature( $product_type, 'featured-video' ) )
			return false;
			
		// Determine if this product has turned on product availability
		if ( 'no' == it_exchange_get_product_feature( $product_id, 'featured-video', array( 'setting' => 'enabled' ) ) ) 
			return false;

		return true;
	}
}
$IT_Exchange_Addon_Product_Feature_Product_Featured_Video = new IT_Exchange_Addon_Product_Feature_Product_Featured_Video();