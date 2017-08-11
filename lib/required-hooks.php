<?php
/**
 * ExchangeWP Featured Video Add-on
 * @package IT_Exchange_Addon_Featured_Video
 * @since 1.0.0
*/

/**
 * Shows the nag when needed.
 *
 * @since 1.0.0
 *
 * @return void
*/
function it_exchange_featured_video_addon_show_version_nag() {
	if ( version_compare( $GLOBALS['it_exchange']['version'], '1.5.0', '<' ) ) {
		?>
		<div class="it-exchange-nag it-exchange-add-on-min-version-nag">
			<?php printf( __( 'The Featured Video add-on requires ExchangeWP version 1.5.0 or greater. %sPlease upgrade Exchange%s.', 'LION' ), '<a href="' . admin_url( 'update-core.php' ) . '">', '</a>' ); ?>
		</div>
		<script type="text/javascript">
			jQuery( document ).ready( function() {
				if ( jQuery( '.wrap > h2' ).length == '1' ) {
					jQuery(".it-exchange-add-on-min-version-nag").insertAfter('.wrap > h2').addClass( 'after-h2' );
				}
			});
		</script>
		<?php
	}
}
add_action( 'admin_notices', 'it_exchange_featured_video_addon_show_version_nag' );

/**
 * Adds actions to the plugins page for the ExchangeWP Featured Video plugin
 *
 * @since 1.0.0
 *
 * @param array $meta Existing meta
 * @param string $plugin_file the wp plugin slug (path)
 * @param array $plugin_data the data WP harvested from the plugin header
 * @param string $context
 * @return array
*/
function it_exchange_featured_video_plugin_row_actions( $actions, $plugin_file, $plugin_data, $context ) {

	$actions['setup_addon'] = '<a href="' . get_admin_url( NULL, 'admin.php?page=it-exchange-addons&add-on-settings=featured-video' ) . '">' . __( 'Setup Add-on', 'LION' ) . '</a>';

	return $actions;

}
add_filter( 'plugin_action_links_exchange-addon-featured-video/exchange-addon-featured-video.php', 'it_exchange_featured_video_plugin_row_actions', 10, 4 );

/**
 * Enqueues Featured Video scripts to WordPress Dashboard
 *
 * @since 1.0.0
 *
 * @param string $hook_suffix WordPress passed variable
 * @return void
*/
function it_exchange_featured_video_addon_admin_wp_enqueue_scripts( $hook_suffix ) {
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

	if ( isset( $post_type ) && 'it_exchange_prod' === $post_type ) {
		wp_register_script( 'fitvids', ITUtility::get_url_from_file( dirname( __FILE__ ) ) . '/admin/js/fitvids.min.js', array( 'jquery') );

		$deps = array( 'post', 'jquery-ui-sortable', 'jquery-ui-droppable', 'jquery-ui-tabs', 'jquery-ui-tooltip', 'jquery-ui-datepicker', 'autosave', 'fitvids' );
		wp_enqueue_script( 'it-exchange-featured-video-addon-add-edit-product', ITUtility::get_url_from_file( dirname( __FILE__ ) ) . '/admin/js/add-edit-product.js', $deps );
	}
}
add_action( 'admin_enqueue_scripts', 'it_exchange_featured_video_addon_admin_wp_enqueue_scripts' );

/**
 * Enqueues Featured Video styles to WordPress Dashboard
 *
 * @since 1.0.0
 *
 * @return void
*/
function it_exchange_featured_video_addon_admin_wp_enqueue_styles() {
	global $post, $hook_suffix;

	if ( isset( $_REQUEST['post_type'] ) ) {
		$post_type = $_REQUEST['post_type'];
	} else {
		if ( isset( $_REQUEST['post'] ) ) {
			$post_id = (int) $_REQUEST['post'];
		} else if ( isset( $_REQUEST['post_ID'] ) ) {
			$post_id = (int) $_REQUEST['post_ID'];
		} else {
			$post_id = 0;
		}


		if ( $post_id )
			$post = get_post( $post_id );

		if ( isset( $post ) && !empty( $post ) )
			$post_type = $post->post_type;
	}

	// Exchange Product pages
	if ( isset( $post_type ) && 'it_exchange_prod' === $post_type ) {
		wp_enqueue_style( 'it-exchange-featured-video-addon-add-edit-product', ITUtility::get_url_from_file( dirname( __FILE__ ) ) . '/admin/styles/add-edit-product.css' );
	}
}
add_action( 'admin_print_styles', 'it_exchange_featured_video_addon_admin_wp_enqueue_styles' );

/**
 * Enqueues Featured Video scripts to WordPress frontend
 *
 * @since 1.0.0
 *
 * @param string $current_view WordPress passed variable
 * @return void
*/
function it_exchange_featured_video_addon_load_public_scripts( $current_view ) {
	// Frontend Featured Video Dashboard CSS & JS
	wp_register_script( 'fitvids', ITUtility::get_url_from_file( dirname( __FILE__ ) . '/assets/js/fitvids.js' ), array( 'jquery' ), false, true );

	wp_enqueue_script( 'it-exchange-featured-video-addon-public-js', ITUtility::get_url_from_file( dirname( __FILE__ ) . '/assets/js/featured-video.js' ), array( 'jquery', 'fitvids' ), false, true );
	wp_enqueue_style( 'it-exchange-featured-video-addon-public-css', ITUtility::get_url_from_file( dirname( __FILE__ ) . '/assets/styles/featured-video.css' ) );
}
add_action( 'wp_enqueue_scripts', 'it_exchange_featured_video_addon_load_public_scripts' );

/**
 * Adds Featured Video Template Path to ExchangeWP Template paths
 *
 * @since 1.0.0
 * @param array $possible_template_paths ExchangeWP existing Template paths array
 * @param array $template_names
 * @return array
*/
function it_exchange_featured_video_addon_template_path( $possible_template_paths, $template_names ) {
	$possible_template_paths[] = dirname( __FILE__ ) . '/templates/';
	return $possible_template_paths;
}
add_filter( 'it_exchange_possible_template_paths', 'it_exchange_featured_video_addon_template_path', 10, 2 );

function it_exchange_featured_video_before_product_info_hook() {
	it_exchange_get_template_part( 'content', 'product/elements/featured-video' );
}
add_action( 'it_exchange_content_product_begin_wrap', 'it_exchange_featured_video_before_product_info_hook' );
