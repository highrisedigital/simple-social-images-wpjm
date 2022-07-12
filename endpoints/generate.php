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
	'logo'               => wp_get_attachment_image_url( get_option( 'ssi_wpjm_logo_id' ), 'full' ),
);

// allow the args to be filtered.
$args = apply_filters( 'ssi_wpjm_endpoint_generate_args', $args );

// set the location of the template file, making it filterable.
$template_location = apply_filters( 'ssi_wpjm_endpoint_generate_template_location', SSI_WPJM_LOCATION . '/templates/', $args );

// start output buffering.
ob_start();

?>
<style>
	:root{--hdsmi--text--color:white;--hdsmi--text--background-color:rgb(91, 212, 218);--hdsmi--background-color:rgb(23, 139, 145);--hdsmi--font-family:sans-serif;--hdsmi--font-size:6vw;--hdsmi--title--font-size:6vw;--hdsmi--title--font-weight:inherit;--hdsmi--title--text-transform:inherit;--hdsmi--location--font-size:4vw;--hdsmi--location--font-weight:inherit;--hdsmi--location--text-transform:inherit;--hdsmi--salary--font-size:3vw;--hdsmi--salary--font-weight:inherit;--hdsmi--salary--text-transform:inherit;--hdsmi--image--background-blend-mode:none;--hdsmi--logo--height:4vw;--hdsmi--logo--width:auto}body{margin:0;padding:0}.hdsmi-template{width:100vw;aspect-ratio:120/63;display:grid;place-items:center;background-color:var(--hdsmi--background-color)}.hdsmi-template__inner{position:relative;aspect-ratio:120/63;width:100%}.hdsmi-template__text{font-size:var(--hdsmi--font-size);font-family:var(--hdsmi--font-family);color:var(--hdsmi--text--color)}.hdsmi-template__title{color:var(--hdsmi--title--color,var(--hdsmi--text--color));font-size:var(--hdsmi--title--font-size, var(--hdsmi--font-size));font-weight:var(--hdsmi--title--font-weight);text-transform:var(--hdsmi--title--text-transform)}.hdsmi-template__location{color:var(--hdsmi--location--color,var(--hdsmi--text--color));font-size:var(--hdsmi--location--font-size, var(--hdsmi--font-size));font-weight:var(--hdsmi--location--font-weight);text-transform:var(--hdsmi--location--text-transform)}.hdsmi-template__salary{color:var(--hdsmi--salary--color,var(--hdsmi--text--color));font-size:var(--hdsmi--salary--font-size, var(--hdsmi--font-size));font-weight:var(--hdsmi--salary--font-weight);text-transform:var(--hdsmi--salary--text-transform)}.hdsmi-template__logo{height:var(--hdsmi--logo--height);width:var(--hdsmi--logo--width)}
	.hdsmi-template{
		--hdsmi--text--color: <?php echo esc_attr( $args['text_color'] ); ?>;
		--hdsmi--text--background-color: <?php echo esc_attr( $args['bg_text_color'] ); ?>;
		--hdsmi--background-color: <?php echo esc_attr( $args['bg_color'] ); ?>;
		--hdsmi--title--font-size: <?php echo esc_attr( $args['title_size'] ); ?>vw;
		--hdsmi--location--font-size: <?php echo esc_attr( $args['location_size'] ); ?>vw;
		--hdsmi--salary--font-size: <?php echo esc_attr( $args['salary_size'] ); ?>vw;
		--hdsmi--logo--height: <?php echo esc_attr( $args['logo_size'] ); ?>vw;
		<?php if ( ! empty( $args['google_font_family'] ) ) { ?>--hdsmi--font-family: <?php echo $args['google_font_family']; ?>;<?php } ?>
	}
</style>
<?php

// if we have a google font url.
if ( ! empty( $args['google_font_family'] ) ) {

	// output the link elements to load the font.
	?>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="<?php echo esc_url( $args['google_font_family'] ); ?>" rel="stylesheet">

	<?php

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
			$args['logo'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

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

echo $text;
