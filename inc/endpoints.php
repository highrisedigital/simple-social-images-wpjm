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

	// get the license key.
	$license_key = get_option( 'ssi_wpjm_license_key' );

	// if we have no license key.
	if ( empty( $license_key ) ) {

		// output error.
		return array(
			'success' => false,
			'error' => __( 'No license key provided.', 'simple-social-image-wpjm' ),
		);

	}

	$social_image_html_url = home_url( '/ssi-wpjm/v1/generate-html/' );
	$social_image_html_url = add_query_arg(
		array(
			'post_id'   => absint( $request['post_id'] ),
			'timestamp' => time(),
		),
		$social_image_html_url
	);

	// set the URL of the simple social images api.
	$api_url = ssi_wpjm_generate_api_url();

	// add the paramters to the api_url.
	$api_url = add_query_arg(
		apply_filters(
			'ssi_wpjm_api_url_query_args',
			array(
				'license_key' => sanitize_text_field( $license_key ),
				'site_url'    => home_url(),
				'url'         => urlencode( $social_image_html_url ),
				'element'     => '.hdsmi-template',
				'ttl'         => 300,
			),
		),
		$api_url
	);

	// send the request to the api.
	$response = wp_remote_get(
		$api_url,
		array(
			'sslverify' => false,
		)
	);

	// if there was an error.
	if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {

		// output error.
		return array(
			'success' => false,
			//'error' => __( 'Request returned an error.', 'simple-social-image-wpjm' ),
			'error'   => $response->get_error_message()
		);

	}

	// get the body of the request, decoded as an array.
	$response = json_decode( wp_remote_retrieve_body( $response ), true );

	// if we have no url returned.
	if ( empty( $response['url'] ) ) {

		// output error.
		return array(
			'success' => false,
			'error' => __( 'No image was generated.', 'simple-social-image-wpjm' ),
		);

	}

	// we are outside of WP Admin so need to include these files.
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	// grab the image and store in the media library.
	$image_id = media_sideload_image( $response['url'], absint( $request['post_id'] ), '', 'id' );

	// if we have an image set.
	if ( ! is_wp_error( $image_id ) ) {

		// save meta data indicating this is image generated by hd og images.
		update_post_meta( $image_id, 'ssi_wpjm_image', true );

		// get the current image id for the og:image.
		$current_image_id = get_post_meta( absint( $request['post_id'] ), 'ssi_wpjm_image_id', true );

		// if we have a current image.
		if ( ! empty( $current_image_id ) ) {

			// delete the attachment.
			wp_delete_attachment( $current_image_id );

		}

		// store the image ID as meta against the job.
		update_post_meta( absint( $request['post_id'] ), 'ssi_wpjm_image_id', $image_id );

	}

	return apply_filters(
		'ssi_wpjm_generated_social_image',
		array(
			'id'  => $image_id,
			'url' => wp_get_attachment_image_url( $image_id, 'ssi_image' ),
		),
		$request
	);

}
