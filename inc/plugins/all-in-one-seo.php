<?php
/**
 * Functions for outputting sharing tags when usingthe All in One SEO plugin.
 */

/**
 * Change Open graph image output with All in one SEO if necessary.
 *
 * @param string $url URL of the image.
 */
function ssi_wpjm_aioseo_change_open_graph_tags( $facebook_tags ) {

	// if this is not a single job.
	if ( ! is_singular( 'job_listing') ) {
		return $facebook_tags;
	}

	// get custom open graph and twitter images.
	$custom_og_image = \aioseo()->social->facebook->getImage();

	// if we have a custom open graph image set.
	if ( ! empty( $custom_og_image ) ) {
		
		// prevent this plugin outputting an og image.
		add_filter( 'ssi_wpjm_render_og_image_tags', '__return_false' );

	}

	// return the meta tags.
	return $facebook_tags;
}

add_filter( 'aioseo_facebook_tags', 'ssi_wpjm_aioseo_change_open_graph_tags' );

/**
 * Change Twitter image output with All in one SEO if necessary.
 *
 * @param string $url URL of the image.
 */
function ssi_wpjm_aioseo_change_twitter_tags( $twitter_tags ) {

	// if this is not a single job.
	if ( ! is_singular( 'job_listing') ) {
		return $twitter_tags;
	}

	// get custom twitter images.
	$custom_twitter_image = \aioseo()->social->twitter->getImage();

	// if we have a custom twitter image set. 
	if ( ! empty( $custom_twitter_image ) ) {
		
		// prevent this plugin outputting a twitter image.
		add_filter( 'ssi_wpjm_render_twitter_image_tags', '__return_false' );

	}

	// return the meta tags.
	return $twitter_tags;
}

add_filter( 'aioseo_twitter_tags', 'ssi_wpjm_aioseo_change_twitter_tags' );
