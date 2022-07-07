<?php
/**
 * Plugin Name: Simple Social Images for WP Job Manager
 * Plugin URI: https://simplesocialimages.com/wp-job-manager/
 * Description: Create automated, beautiful and branded images for jobs shared on social media channels. This plugin requires a license for <a href="https://simplesocialimages.com">Simple Social Images</a>.
 * Version: 1.0
 * Author: Highrise Digital
 * Author URI: https://highrise.digital/
 * Text Domain: simple-social-images-wpjm
 * Domain Path: /languages/
 * License: GPL2+
 */

// define variable for path to this plugin file.
define( 'SSI_WPJM_LOCATION', dirname( __FILE__ ) );
define( 'SSI_WPJM_LOCATION_URL', plugins_url( '', __FILE__ ) );
define( 'SSI_WPJM_VERSION', '1.0' );

/**
 * Function to run on plugins load.
 */
function ssi_wpjm_plugins_loaded() {

	$locale = apply_filters( 'plugin_locale', get_locale(), 'simple-social-images-wpjm' );
	load_textdomain( 'simple-social-images-wpjm', WP_LANG_DIR . '/simple-social-images-wpjm/simple-social-images-wpjm-' . $locale . '.mo' );
	load_plugin_textdomain( 'simple-social-images-wpjm', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );

}

add_action( 'plugins_loaded', 'ssi_wpjm_plugins_loaded' );

// load in the loader file which loads everything up.
require_once( dirname( __FILE__ ) . '/inc/loader.php' );

/**
 * Grabs a random image ID from those added to the settings page.
 */
function ssi_wpjm_get_random_image_id() {

	// get the image ids from options.
	$image_ids = get_option( 'ssi_wpjm_images' );

	// convert the id string into an array.
	$images = explode( ',', $image_ids );

	$image_id_key = array_rand( $images, 1 );
	$image_id = $images[ $image_id_key ];	

	return absint( $image_id );

}

/**
 * Returns the URL of the SSI API to generate an image.
 */
function ssi_wpjm_generate_api_url() {

	return apply_filters(
		'ssi_wpjm_generate_api_url',
		'https://simplesocialimages.com/ssi-api/v1/generate/'
	);

}

/**
 * Enqueues the admin js file.
 * Only enqueued on the post edit screen for WPJM jobs.
 */
function ssi_wpjm_enqueue_scripts( $hook ) {

	// if this is not the post edit screen.
	if ( $hook !== 'post.php' ) {
		return;
	}

	global $post_type;

	// if the post type is not job listing.
	if ( $post_type !== 'job_listing' ) {
		return;
	}

	wp_localize_script(
		'wp-api',
		'wpApiSettings',
		array(
			'root' => esc_url_raw( rest_url() ),
			'nonce' => wp_create_nonce( 'wp_rest' )
		)
	);

	// register the js.
	wp_enqueue_script(
		'ssi_wpjm_admin_js',
		SSI_WPJM_LOCATION_URL . '/assets/js/ssi-wpjm-admin.js',
		array( 'jquery' ),
		SSI_WPJM_VERSION,
		true
	);

	// register the admin css.
	wp_enqueue_style(
		'ssi_wpjm_admin_css',
		SSI_WPJM_LOCATION_URL . '/assets/css/ssi-wpjm-admin.css',
		array(),
		SSI_WPJM_VERSION
	);

}

add_action( 'admin_enqueue_scripts', 'ssi_wpjm_enqueue_scripts' );

/**
 * Add the og:image size in WP.
 * Allows images to be cropped to og:image size.
 */
function ssi_wpjm_add_image_size() {

	// add the og:image size.
	add_image_size( 'ssi_image', 1200, 630, true );

}

add_action( 'after_setup_theme', 'ssi_wpjm_add_image_size' );

function ssi_wpjm_has_image( $post_id = 0 ) {

	// if we have no post id to check.
	if ( $post_id === 0 ) {

		// use current global post id.
		global $post;
		$post_id = $post->ID;

	}

	// get the image id stored as meta.
	$image_id = get_post_meta( $post_id, '', true );

	// if we have no image id.
	if ( empty( $image_id ) ) {
		return 0;
	}

	// get the image url for the associated meta.
	$image_url = wp_get_attachment_image_url( $image_id, 'ssi_image' );

	// if we have no image url.
	if ( $image_url === false ) {
		return 0;
	}

	// go this far, we must have an image.
	return apply_filters( 'ssi_wpjm_has_image', $image_id, $post_id );

}
