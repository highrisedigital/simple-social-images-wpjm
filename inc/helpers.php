<?php
/**
 * Setup some helper functions we can use in the plugin.
 */

/**
 * Checks whether a post has a current generated ssi image.
 *
 * @param  integer $post_id The post ID to check.
 * @return mixed   Zero if no image is present or the image ID is present.
 */
function ssi_wpjm_has_image( $post_id = 0 ) {

	// if we have no post id to check.
	if ( $post_id === 0 ) {

		// use current global post id.
		global $post;
		$post_id = $post->ID;

	}

	// get the image id stored as meta.
	$image_id = get_post_meta( $post_id, 'ssi_wpjm_image_id', true );

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

/**
 * Sorts an array by the order paramter.
 */
function ssi_wpjm_array_sort_by_order_key( $a, $b ) {
	
	// if no order paramter is provided.
	if ( ! isset( $a['order'] ) ) {

		// set the order to 10.
		$a['order'] = 10;

	}

	// if no order paramter is provided.
	if ( ! isset( $b['order'] ) ) {

		// set the order to 10.
		$b['order'] = 10;

	}

	// if the first array element is the same as the next.
	if ( $a['order'] === $b['order'] ) {
		return 0;
	}

	// return -1 is the first array element is less than the second, otherwise return 1.
	return ( $a['order'] < $b['order'] ) ? -1 : 1;

}

/**
 * Returns an array of all the registered settings.
 */
function ssi_wpjm_get_settings() {

	$settings = apply_filters(
		'ssi_wpjm_settings',
		array()
	);

	// sort the settings based on the order parameter.
	uasort( $settings, 'ssi_wpjm_array_sort_by_order_key' );

	// return the settings.
	return $settings;

}

/**
 * Gets the current active template selected.
 *
 * @return string The template name.
 */
function ssi_wpjm_get_template() {

	return apply_filters(
		'ssi_wpjm_template',
		get_option( 'ssi_wpjm_template' )
	);

}

/**
 * Gets the current active text color.
 */
function ssi_wpjm_get_text_color() {

	return apply_filters(
		'ssi_wpjm_text_color',
		get_option( 'ssi_wpjm_text_color' )
	);

}

/**
 * Gets the current active text background color.
 */
function ssi_wpjm_get_text_bg_color() {

	return apply_filters(
		'ssi_wpjm_text_bg_color',
		get_option( 'ssi_wpjm_text_bg_color' )
	);

}

/**
 * Gets the current active background color.
 */
function ssi_wpjm_get_bg_color() {

	return apply_filters(
		'ssi_wpjm_bg_color',
		get_option( 'ssi_wpjm_bg_color' )
	);

}

/**
 * Gets the currently uploaded logo attachment ID.
 */
function ssi_wpjm_get_logo_id() {

	return apply_filters(
		'ssi_wpjm_logo_id',
		get_option( 'ssi_wpjm_logo' )
	);

}

/**
 * Gets the currently set logo size.
 */
function ssi_wpjm_get_logo_size() {

	return apply_filters(
		'ssi_wpjm_logo_size',
		get_option( 'ssi_wpjm_logo_size' )
	);

}

/**
 * Gets the currently uploaded logo attachment ID.
 */
function ssi_wpjm_get_background_images() {

	// get the background images.
	$bg_images = get_option( 'ssi_wpjm_background_images' );

	// if we have no bg images.
	if ( empty( $bg_images ) ) {
		return array();
	}

	return apply_filters(
		'ssi_wpjm_background_images',
		explode( ',', $bg_images )
	);

}

/**
 * Gets the current title font size.
 */
function ssi_wpjm_get_title_font_size() {

	return apply_filters(
		'ssi_wpjm_title_font_size',
		get_option( 'ssi_wpjm_title_size' )
	);

}

/**
 * Gets the current location font size.
 */
function ssi_wpjm_get_location_font_size() {

	return apply_filters(
		'ssi_wpjm_location_font_size',
		get_option( 'ssi_wpjm_location_size' )
	);

}

/**
 * Gets the current salary font size.
 */
function ssi_wpjm_get_salary_font_size() {

	return apply_filters(
		'ssi_wpjm_salary_font_size',
		get_option( 'ssi_wpjm_salary_size' )
	);

}

/**
 * Gets the current Google font URL.
 */
function ssi_wpjm_get_google_font_url() {

	return apply_filters(
		'ssi_wpjm_google_font_url',
		get_option( 'ssi_wpjm_google_font_url' )
	);

}

/**
 * Gets the current Google font family.
 */
function ssi_wpjm_get_google_font_family() {

	// get the font family from settings.
	$font_family = get_option( 'ssi_wpjm_google_font_family' );

	// if the font family is empty.
	if ( empty( $font_family ) ) {

		// set to default system family for sans serif.
		$font_family = 'sans-serif;';

	}

	return apply_filters(
		'ssi_wpjm_google_font_family',
		$font_family
	);

}

/**
 * Grabs a random image ID from those added to the settings page.
 */
function ssi_wpjm_get_random_image_id() {

	// get the image ids from options.
	$images = ssi_wpjm_get_background_images();

	$image_id_key = array_rand( $images, 1 );
	$image_id = $images[ $image_id_key ];	

	return absint( $image_id );

}
