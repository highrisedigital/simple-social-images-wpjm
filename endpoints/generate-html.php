<?php
// default job post.
$post_id = 0;

// if a job post is available to generate the html of.
if ( ! empty( $_GET['post_id'] ) ) {

	// sanitize and set as the job post.
	$post_id = absint( $_GET['post_id'] );

}

// set some args.
$args = array(
	'template'           => ssi_wpjm_get_template(),
	'post_id'           => $post_id,
	'bg_color'           => ssi_wpjm_get_bg_color(),
	'bg_text_color'      => ssi_wpjm_get_text_bg_color(),
	'text_color'         => ssi_wpjm_get_text_color(),
	'title_size'         => ssi_wpjm_get_title_font_size(),
	'location_size'      => ssi_wpjm_get_location_font_size(),
	'salary_size'        => ssi_wpjm_get_salary_font_size(),
	'google_font_url'    => ssi_wpjm_get_google_font_url(),
	'google_font_family' => ssi_wpjm_get_google_font_family(),
	'logo_size'          => ssi_wpjm_get_logo_size(),
	'image'              => wp_get_attachment_image_url( ssi_wpjm_get_random_image_id(), 'full' ),
	'logo'               => wp_get_attachment_image_url( ssi_wpjm_get_logo_id(), 'full' ),
);

// if the current post has a featured image.
if ( has_post_thumbnail( $args['post_id'] ) ) {

	// set the image url to that of the featured image.
	$args['image'] = get_the_post_thumbnail_url( $args['post_id'], 'full' );

}

// allow the args to be filtered.
$args = apply_filters( 'ssi_wpjm_endpoint_generate_args', $args );

// set the location of the template file, making it filterable.
$template_location = apply_filters( 'ssi_wpjm_endpoint_generate_template_location', SSI_WPJM_LOCATION . '/templates/', $args );

// start output buffering.
ob_start();

?>
<!doctype html>
<html class="no-js" lang="">

	<head>

		<link rel="stylesheet" href="<?php echo esc_url( SSI_WPJM_LOCATION_URL . '/assets/css/ssi-wpjm-generate.css' ); ?>" />
		<style>
			.hdsmi-template{
				<?php
				if ( ! empty( $args['text_color'] ) ) { 
					echo "--hdsmi--text--color:" . esc_attr( $args['text_color'] ) . ";";
				}

				if ( ! empty( $args['bg_text_color'] ) ) { 
					echo "--hdsmi--text--background-color:" . esc_attr( $args['bg_text_color'] ) . ";";
				}

				if ( ! empty( $args['bg_color'] ) ) { 
					echo "--hdsmi--background-color:" . esc_attr( $args['bg_color'] ) . ";";
				}

				if ( ! empty( $args['title_size'] ) ) { 
					echo "--hdsmi--title--font-size:" . esc_attr( $args['title_size'] ) . ";";
				}

				if ( ! empty( $args['location_size'] ) ) { 
					echo "--hdsmi--location--font-size:" . esc_attr( $args['location_size'] ) . ";";
				}

				if ( ! empty( $args['salary_size'] ) ) { 
					echo "--hdsmi--salary--font-size:" . esc_attr( $args['salary_size'] ) . ";";
				}

				if ( ! empty( $args['logo_size'] ) ) { 
					echo "--hdsmi--logo--height:" . esc_attr( $args['logo_size'] ) . ";";
				}

				if ( ! empty( $args['google_font_family'] ) ) { 
					echo "--hdsmi--font-family:" . wp_kses_post( $args['google_font_family'] ) . ";";
				}

				?>
			}
		</style>
		
		<?php

		// if we have a google font url.
		if ( ! empty( $args['google_font_family'] ) && ! empty( $args['google_font_url'] ) ) {

			// output the link elements to load the font.
			?>

			<link rel="preconnect" href="https://fonts.googleapis.com">
			<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
			<link href="<?php echo esc_url( $args['google_font_url'] ); ?>" rel="stylesheet">

			<?php

		}

	?>

	</head>

<?php

// if our template exists.
if ( file_exists( $template_location . $args['template'] . '.html' ) ) {

	// load the template markup, passing our args.
	load_template( $template_location . $args['template'] . '.html', true, $args );

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
			$match_value = get_post_meta( $args['post_id'], $match_key, true );

			// filter the match post value.
			$match_value = apply_filters( 'ssi_wpjm_template_' . $match_key, $match_value, $match_key );

			// replace the original text string with the new 
			$text = str_replace( $match, $match_value, $text );

		}

		// if this is a tax replace.
		if ( strpos( $match_value, 'tax' ) !== false ) {

			// remove the tax: string
			$match_key = str_replace( 'tax:', '', $match_value );

			// if the taxonomy exists.
			if ( taxonomy_exists( $match_key ) ) {
				
				// get the value of this meta.
				$match_value = wp_strip_all_tags(
					get_the_term_list(
						$args['post_id'],
						$match_key,
						'',
						', ',
						''
					)
				);

			} else {

				// set empty match value.
				$match_value = '';

			}

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
			$match_value = get_post_field( $match_key, $args['post_id'] );

			// if we have no job post id.
			if ( empty( $args['post_id'] ) ) {

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
			$args['image'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

		}

		// if we have no template.
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

echo $text . '</body></html>';
