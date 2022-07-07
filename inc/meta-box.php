<?php
/**
 * Adds the meta boxes required for the plugin.
 */

/**
 * Registers the Simple Social Images meta box.
 * Displays the meta box on the jobs post edit screen.
 */
function ssi_wpjm_add_ssi_jobs_meta_box() {

	add_meta_box(
		'ssi_wpjm',
		__( 'Simple Social Images', 'simple-social-image-wpjm'),
		'ssi_wpjm_jobs_meta_box_output',
		'job_listing',
		'normal',
		'default'
	);

}

add_action( 'add_meta_boxes', 'ssi_wpjm_add_ssi_jobs_meta_box' );

/**
 * Output the contents of the Simple Social Images meta box.
 */
function ssi_wpjm_jobs_meta_box_output( $post ) {

	// default social image id.
	$social_image_id = 0;

	// get the current social image for this post.
	$social_image_id = ssi_wpjm_has_image( $post->ID );

	// get the URL for the current social image.
	$social_image_url = wp_get_attachment_image_url( $social_image_id, 'ssi_image' );

	// if we don't have a social image URL.
	if ( empty ( $social_image_url ) ) {

		// set the url of the image to the placeholder.
		$social_image_url = SSI_WPJM_LOCATION_URL . '/assets/img/social-placeholder.jpg';

	}

	?>
	<p><?php esc_html_e( 'Use the button below to generate the social sharing image for this job.', 'simpe-social-images-wpjm' ); ?></p>
	
	<button class="generate-ssi-image-button button-secondary" id="generate-ssi-image" data-endpoint-url="<?php echo esc_url( get_rest_url( null, 'ssi-wpjm/v1/getimage' ) ); ?>/?post_id=<?php echo esc_attr( $post->ID ); ?>">
		<?php esc_html_e( 'Generate / Regenerate Social Sharing Image', 'simple-social-sharing-wpjm' ); ?>
	</button>

	<?php

	// set class for the delete button.
	$delete_button_class = array(
		'button-secondary',
		'delete-ssi-image-button',
		'ssi-hidden',
	);

	// if the is a current social image set.
	if ( $social_image_id !== 0 ) {

		// we should be showing the button so remove the hide class.
		$key = array_search( 'delete-ssi-image-button--hidden', $delete_button_class, true );

		// if we have found the class.
		if ( $key !== false ) {

			// remove the class from the array.
			unset( $delete_button_class[ $key ] );

		}

	}

	// output the delete button.
	?>

	<button class="<?php echo esc_attr( implode( ' ', $delete_button_class ) ); ?>" id="delete-ssi-image" data-endpoint-url="<?php echo esc_url( get_rest_url( null, 'wp/v2/media/' ) ); ?>" data-media-id="<?php echo esc_attr( $social_image_id ); ?>" data-placeholder-img="<?php echo esc_url( SSI_WPJM_LOCATION_URL . '/assets/img/social-placeholder.jpg' ); ?>">
			<?php esc_html_e( 'Remove Social Sharing Image', 'simple-social-sharing-wpjm' ); ?>
		</button>

	<?php

	?>

	<img class="ssi-wpjm-spinner" src="<?php echo esc_url( admin_url( '/images/spinner.gif' ) ); ?>" />

	<?php

	?>

	<img class="ssi-image" src="<?php echo esc_url( $social_image_url ); ?>" data-image-id="<?php echo esc_attr( absint( $social_image_id ) ); ?>" />

	<?php

}