<?php
// default job post.
$job_post = 0;

// if a job post is available to generate the html of.
if ( ! empty( $_GET['job_id'] ) ) {

	// sanitize and set as the job post.
	$job_post = absint( $_GET['job_id'] );

}

// set some args.
$args = array(
	'template'      => get_option( 'ssi_wpjm_template' ),
	'job_post'      => $job_post,
	'bg_color'      => get_option( 'ssi_wpjm_bg_color' ),
	'bg_text_color' => get_option( 'ssi_wpjm_text_bg_color' ),
	'text_color'    => get_option( 'ssi_wpjm_text_color' ),
	'image'         => wp_get_attachment_image_url( ssi_wpjm_get_random_image_id(), 'full' ),
	'logo'          => wp_get_attachment_image_url( get_option( 'ssi_wpjm_logo_id' ), 'full' ),
);

// allow the args to be filtered.
$args = apply_filters( 'ssi_wpjm_endpoint_generate_args', $args );

// set the location of the template file, making it filterable.
$template_location = apply_filters( 'ssi_wpjm_generate_template_location', SSI_WPJM_LOCATION . '/templates/', $args );
$template_css_location = apply_filters( 'ssi_wpjm_generate_template_css_location', SSI_WPJM_LOCATION . '/assets/css/', $args );

// start output buffering.
ob_start();

// load the template markup, passing our args.
load_template( SSI_WPJM_LOCATION . '/assets/css/global.php', true, $args );

// if our template exists.
if ( file_exists( $template_css_location . $args['template'] . '.php' ) ) {

	// load the template markup, passing our args.
	load_template( $template_css_location . $args['template'] . '.php', true, $args );

}

// if our template exists.
if ( file_exists( $template_location . $args['template'] . '.php' ) ) {

	// load the template markup, passing our args.
	load_template( $template_location . $args['template'] . '.php', true, $args );

}

// get the contents of the buffer, the template markup and clean the buffer.
$text = ob_get_clean();

// find all of the strings that need replacing.
preg_match_all( "/\[[^\]]*\]/", $text, $matches );

// if we have matches.
if ( $matches !== false ) {

	// loop through the matches.
	foreach ( $matches[0] as $match ) {

		// remove the brackets for the string.
		$match_value = str_replace( '[', '', $match );
		$match_value = str_replace( ']', '', $match_value );

		// if this is a meta replace.
		if ( strpos( $match_value, 'meta' ) !== false ) {

			// remove the meta: string
			$match_key = str_replace( 'meta:', '', $match_value );
			
			// get the value of this meta.
			$match_value = get_post_meta( $args['job_post'], $match_key, true );

			// filter the match post value.
			$match_value = apply_filters( 'ssi_wpjm_template_' . $match_key, $match_value, $match_key );

			// replace the original text string with the new 
			$text = str_replace( $match, $match_value, $text );

		}

		// if this is a tax replace.
		if ( strpos( $match_value, 'tax' ) !== false ) {

			// remove the tax: string
			$match_key = str_replace( 'tax:', '', $match_value );

			// get the value of this meta.
			$match_value = wp_strip_all_tags(
				get_the_term_list(
					$args['job_post'],
					$match_key,
					'',
					', ',
					''
				)
			);

			// filter the match post value.
			$match_value = apply_filters( 'ssi_wpjm_template_' . $match_key, $match_value, $match_key );

			// replace the original text string with the new 
			$text = str_replace( $match, $match_value, $text );

		}

		// if this is a post field replace.
		if ( strpos( $match_value, 'post' ) !== false ) {

			// remove the tax: string
			$match_key = str_replace( 'post:', '', $match_value );

			// get the value of this meta.
			$match_value = get_post_field( $match_key, $args['job_post'] );

			// if we have no job post id.
			if ( empty( $args['job_post'] ) ) {

				// set the match value to the site title.
				$match_value = get_bloginfo( 'title' );

			}

			// filter the match post value.
			$match_value = apply_filters( 'ssi_wpjm_template_' . $match_key, $match_value, $match_key );

			// replace the original text string with the new 
			$text = str_replace( $match, $match_value, $text );

		}
		
		// if we have a logo.
		if ( empty( $args['logo'] ) ) {

			// set the logo to a transparent image.
			$args['logo'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

		}

		// if we have a image.
		if ( empty( $args['image'] ) ) {

			// set the image to a transparent image.
			$args['logo'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

		}

		// if we have a template.
		if ( empty( $args['template'] ) ) {

			// default the template.
			$args['template'] = '1';

		}
		
		// replace the logo string.
		$text = str_replace( '[logo]', $args['logo'], $text );

		// replace the template string.
		$text = str_replace( '[template]', $args['template'], $text );

		// replace the image string.
		$text = str_replace( '[image]', $args['image'], $text );

	}

}

echo $text;
