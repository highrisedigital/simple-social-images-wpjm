<?php
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
 * Add the og:image size in WP.
 * Allows images to be cropped to og:image size.
 */
function ssi_wpjm_add_image_size() {

	// add the og:image size.
	add_image_size( 'ssi_image', 1200, 630, true );

}

add_action( 'after_setup_theme', 'ssi_wpjm_add_image_size' );

/**
 * Outputs the custom variables on the settings page for the template preview.
 */
function ssi_wpjm_add_template_custom_properties() {

	?>

	<style>
		.hdsmi-template{
			<?php
				if ( ! empty( ssi_wpjm_get_text_color() ) ) { 
					echo "--hdsmi--text--color:" . esc_attr( ssi_wpjm_get_text_color() ) . ";";
				}

				if ( ! empty(  ssi_wpjm_get_text_bg_color() ) ) { 
					echo "--hdsmi--text--background-color:" . esc_attr(  ssi_wpjm_get_text_bg_color() ) . ";";
				}

				if ( ! empty( ssi_wpjm_get_bg_color() ) ) { 
					echo "--hdsmi--background-color:" . esc_attr( ssi_wpjm_get_bg_color() ) . ";";
				}

				if ( ! empty( ssi_wpjm_get_title_font_size() ) ) { 
					echo "--hdsmi--title--font-size:" . esc_attr( ssi_wpjm_get_title_font_size() ) . ";";
				}

				if ( ! empty( ssi_wpjm_get_location_font_size() ) ) { 
					echo "--hdsmi--location--font-size:" . esc_attr( ssi_wpjm_get_location_font_size() ) . ";";
				}

				if ( ! empty( ssi_wpjm_get_salary_font_size() ) ) { 
					echo "--hdsmi--salary--font-size:" . esc_attr( ssi_wpjm_get_salary_font_size() ) . ";";
				}

				if ( ! empty( ssi_wpjm_get_logo_size() ) ) { 
					echo "--hdsmi--logo--height:" . esc_attr( ssi_wpjm_get_logo_size() ) . ";";
				}

				if ( ! empty( ssi_wpjm_get_google_font_family() ) ) { 
					echo "--hdsmi--font-family:" . wp_kses_post( ssi_wpjm_get_google_font_family() ) . ";";
				}
			?>
		}
	</style>

	<?php

}

add_action( 'ssi_wpjm_before_settings_form_output', 'ssi_wpjm_add_template_custom_properties' );

/**
 * Adds markup to the end of the settings page for the template preview.
 */
function ssi_wpjm_add_preview_markup_to_settings_page() {

	?>
	<div class="ssi-wpjm-template-preview">

		<div class="hdsmi-template hdsmi-template--<?php echo esc_attr( ssi_wpjm_get_template() ); ?>">
			<div class="hdsmi-template__inner">
				<div class="hdsmi-template__text">
					<span class="hdsmi-template__title" contenteditable="true">Test job title</span>
					<span class="hdsmi-template__location" contenteditable="true">London, UK</span>
					<span class="hdsmi-template__salary" contenteditable="true">£30,000 per annum</span>
				</div>

				<?php

				// set a place holder transparent pixel to use as defaults.
				$placeholder_pixel = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
				
				// set the logo and background images to default to transparent pixel.
				$logo_src = $placeholder_pixel;
				$bg_img_src = $placeholder_pixel;
				
				// if we have a logo already added.
				if ( ! empty( ssi_wpjm_get_logo_id() ) ) {

					// set the logo src to the added logo image src.
					$logo_src = wp_get_attachment_image_url( ssi_wpjm_get_logo_id(), 'full' );

				} 

				// if we have background images already added.
				if ( ! empty( ssi_wpjm_get_background_images() ) ) {

					// set a random background image src to the added background image src.
					$bg_img_src = wp_get_attachment_image_url( ssi_wpjm_get_random_image_id(), 'full' );

				} 

				?>
				<img class="hdsmi-template__image" src="<?php echo esc_url( $bg_img_src ); ?>" />
				<img class="hdsmi-template__logo" src="<?php echo esc_url( $logo_src ); ?>" />
			</div>
		</div>

	</div>
	<?php

}

add_action( 'ssi_wpjm_after_settings_form_output', 'ssi_wpjm_add_preview_markup_to_settings_page' );

/**
 * Generates the social media sharing image for a post.
 * Also saves the image to the media library and attaches it to the post.
 *
 * @param integer $post_id The ID of the post to generate an image for.
 * @return array           An array of image id and url on success
 *                         An error array with error message on failure.
 */
function ssi_wpjm_generate_social_image( $post_id = 0 ) {

	// if no post id then use the global post ID.
	if ( $post_id === 0 ) {
		global $post;
		$post_id = $post->ID;
	}

	// get the license key.
	$license_key = get_option( 'ssi_wpjm_license_key' );

	// if we have no license key.
	if ( empty( $license_key ) ) {

		// output error.
		return array(
			'success' => false,
			'error'   => __( 'No license key provided.', 'simple-social-image-wpjm' ),
		);

	}

	$social_image_html_url = home_url( '/ssi-wpjm/v1/generate-html/' );
	$social_image_html_url = add_query_arg(
		array(
			'post_id'   => absint( $post_id ),
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
			'timeout'   => 30,
		)
	);

	// if there was an error.
	if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {

		// output error.
		return array(
			'success' => false,
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
			'error'   => __( 'No image was generated.', 'simple-social-image-wpjm' ),
		);

	}

	// we are outside of WP Admin so need to include these files.
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	// grab the image and store in the media library.
	$image_id = media_sideload_image( $response['url'], absint( $post_id ), '', 'id' );

	// if we have an image set.
	if ( ! is_wp_error( $image_id ) ) {

		// save meta data indicating this is image generated by hd og images.
		update_post_meta( $image_id, 'ssi_wpjm_image', true );

		// get the current image id for the og:image.
		$current_image_id = get_post_meta( absint( $post_id ), 'ssi_wpjm_image_id', true );

		// if we have a current image.
		if ( ! empty( $current_image_id ) ) {

			// delete the attachment.
			wp_delete_attachment( $current_image_id );

		}

		// store the image ID as meta against the job.
		$result = update_post_meta( absint( $post_id ), 'ssi_wpjm_image_id', $image_id );

	}

	return apply_filters(
		'ssi_wpjm_generated_social_image',
		array(
			'success' => true,
			'id'      => $image_id,
			'url'     => wp_get_attachment_image_url( $image_id, 'ssi_image' ),
		),
		$post_id
	);

}
