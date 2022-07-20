<?php
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

				$placeholder_pixel = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
				$logo_src = $placeholder_pixel;

				if ( ! empty( ssi_wpjm_get_logo_id() ) ) {
					$logo_src = wp_get_attachment_image_url( ssi_wpjm_get_logo_id(), 'full' );
				} 

				$bg_img_src = $placeholder_pixel;

				if ( ! empty( ssi_wpjm_get_background_images() ) ) {
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
