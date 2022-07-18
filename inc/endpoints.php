<?php
/**
 * Registers the SSI API endpoints in WordPress.
 */
function ssi_wpjm_register_endpoints() {

	// add the endpoint for social image generator.
	add_rewrite_endpoint(
		'ssi-wpjm/v1/generate-html', // this is the endpoint part of the url.
		EP_ROOT,
		'ssi_wpjm_generate' // this is var that is set when the endpoint is reached.
	);

}

add_action( 'init', 'ssi_wpjm_register_endpoints' );

/**
 * Makes sure that the endpoint variable has a true value when set.
 *
 * @param array $vars The current query vars.
 */
function ssi_wpjm_fix_api_endpoint_requests( $vars ) {

	// if the endpoint var is set.
	if ( isset( $vars['ssi_wpjm_generate'] ) ) {

		// make sure it is always equal to true.
		$vars['ssi_wpjm_generate'] = true;

	} else { // if the endpoint var is not set.

		// make sure it always is unset completely and not empty.
		unset( $vars['ssi_wpjm_generate'] );

	}

	// return modified vars.
	return $vars;

}

add_filter( 'request', 'ssi_wpjm_fix_api_endpoint_requests' );

/**
 * When the endpoint for generate is visited load the correct template file.
 *
 * @param  string $template The current template WordPress will load from the theme.
 * @return string           The modified tempalte string WordPress will load.
 */
function ssi_wpjm_load_jobfeed_endpoint_template( $template ) {

	// check the endpoint var is set to true - if not pass back original template.
	if ( true !== get_query_var( 'ssi_wpjm_generate' ) ) {
		return $template;
	}

	// check for a app push template file in the theme folder.
	if ( file_exists( STYLESHEETPATH . '/ssi-wpjm/generate-html.php' ) ) {

		// load the file from the theme folder.
		return STYLESHEETPATH . '/ssi-wpjm/generate-html.php';

	} else { // file not in theme folder.

		// load the timetables file from the plugin.
		return SSI_WPJM_LOCATION . '/endpoints/generate-html.php';

	}

	return $template;

}

add_filter( 'template_include', 'ssi_wpjm_load_jobfeed_endpoint_template' );

function ssi_wpjm_register_rest_endpoint() {

	// register a new rest route or endpoint for getting slot posts.
	register_rest_route(
		'ssi-wpjm/v1',
		'/getimage/',
		array(
			'methods'             => 'GET',
			'callback'            => 'ssi_wpjm_generate_endpoint_output',
			'permission_callback' => '__return_true',
		)
	);

}

add_action( 'rest_api_init', 'ssi_wpjm_register_rest_endpoint' );

/**
 * Callback function used for the registered rest route for getting latest post content.
 *
 * @param  \WP_REST_Request $request The paramters passed to the endpoint url.
 * @return mixed                     THe HTML outputs for the requested slots posts.
 */
function ssi_wpjm_generate_endpoint_output( \WP_REST_Request $request ) {

	// if no post ID is present.
	if ( empty( $request['post_id'] ) ) {
		
		// output error.
		return array(
			'success' => false,
			'error' => __( 'No post ID provided.', 'simple-social-image-wpjm' ),
		);

	}

	return ssi_wpjm_generate_social_image( $request['post_id'] );

}
