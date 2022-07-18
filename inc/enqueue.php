<?php
/**
 * Enqueues the admin js file.
 * Only enqueued on the post edit screen for WPJM jobs.
 */
function ssi_wpjm_enqueue_scripts( $hook ) {

	// if this is the post edit screen.
	if ( $hook === 'post.php' ) {

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
			'ssi_wpjm_editor_js',
			SSI_WPJM_LOCATION_URL . '/assets/js/ssi-wpjm-editor.js',
			array( 'jquery' ),
			SSI_WPJM_VERSION,
			true
		);

	}

	// register the admin css.
	wp_enqueue_style(
		'ssi_wpjm_admin_css',
		SSI_WPJM_LOCATION_URL . '/assets/css/ssi-wpjm-admin.css',
		array(),
		SSI_WPJM_VERSION
	);

	// if this is the ssi settings page.
	if ( $hook === 'job_listing_page_ssi-wpjm-settings' ) {
		
		// add the color picker css file       
		wp_enqueue_style( 'wp-color-picker' ); 

		// include our custom jQuery file with WordPress Color Picker dependency
		wp_enqueue_script(
			'ssi_wpjm_admin_js',
			SSI_WPJM_LOCATION_URL . '/assets/js/ssi-wpjm-settings.js',
			array( 'wp-color-picker' ),
			false,
			true
		);

		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}

	}

}

add_action( 'admin_enqueue_scripts', 'ssi_wpjm_enqueue_scripts' );
