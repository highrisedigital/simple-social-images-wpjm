<?php
/**
 * Registers the associted plugin settings with the WP Job Manager plugin.
 */

/**
 * Registers the plugin settings with WordPress.
 */
function ssi_wpjm_register_settings() {

	// get the registered settings.
	$settings = ssi_wpjm_get_settings();

	// if we have no settings.
	if ( empty( $settings ) ) {
		return;
	}

	// set up default option args.
	$defaults = array(
		'label'             => '',
		'option_name'       => '',
		'input_type'        => 'text',
		'type'              => 'string',
		'description'       => '',
		'sanitize_callback' => null,
		'show_in_rest'      => false,
		'order'             => 10,
	);

	// loop through each setting.
	foreach ( $settings as $setting ) {

		// merge the args with defaults.
		$args = wp_parse_args( $setting, $defaults );

		// if no setting key is set.
		if ( '' === $args['option_name'] ) {

			// don't register the setting.
			continue;

		}

		// register this setting.
		register_setting(
			'ssi_wpjm_settings', // setting group name.
			$args['option_name'], // setting name - the option key.
			array(
				'type'              => $args['type'],
				'group'             => 'ssi_wpjm_settings',
				'description'       => $args['description'],
				'sanitize_callback' => $args['sanitize_callback'],
				'show_in_rest'      => $args['show_in_rest'],
			)
		);

	}

}

add_action( 'admin_init', 'ssi_wpjm_register_settings' );

/**
 * Registers the plugins default settings.
 *
 * @param  array $settings The current array of settings.
 * @return array $settings The modified array of settings.
 */
function ssi_wpjm_register_default_settings( $settings ) {

	$settings['license_key'] = array(
		'option_name'    => 'ssi_wpjm_license_key',
		'label'          => __( 'License Key', 'simple-social-images-wpjm' ),
		'description'    => sprintf( __( 'Enter your %1$sSimple Social Images%2$s license key.  This is required in order to generate your social sharing images.', 'simple-social-images-wpjm' ), '<a href="https://simplesocialimages.com">', '</a>' ),
		'input_type'     => 'text',
		'order'          => 10,
	);

	$settings['template'] = array(
		'option_name'    => 'ssi_wpjm_template',
		'label'          => __( 'Select a Template', 'simple-social-images-wpjm' ),
		'description'    => __( 'Choose which template to use.', 'simple-social-images-wpjm' ),
		'input_type'     => 'select',
		'options'        => array(
			'1'      => __( 'Template 1', 'simple-social-images-wpjm' ),
			'2'      => __( 'Template 2', 'simple-social-images-wpjm' ),
			'3'      => __( 'Template 3', 'simple-social-images-wpjm' ),
			'4'      => __( 'Template 4', 'simple-social-images-wpjm' ),
			'5'      => __( 'Template 5', 'simple-social-images-wpjm' ),
		),
		'order'          => 10,
	);

	$settings['colors_section'] = array(
		'option_name'    => 'ssi_wpjm_colors_section',
		'label'          => __( 'Colour Settings', 'simple-social-images-wpjm' ),
		'input_type'     => 'section',
		'order'          => 20,
	);

	$settings['text_color'] = array(
		'option_name'    => 'ssi_wpjm_text_color',
		'label'          => __( 'Text Colour', 'simple-social-images-wpjm' ),
		'description'    => __( 'Enter or choose the text colour.', 'simple-social-images-wpjm' ),
		'input_type'     => 'color_picker',
		'order'          => 30,
	);

	$settings['text_bg_color'] = array(
		'option_name'    => 'ssi_wpjm_text_bg_color',
		'label'          => __( 'Text Background Colour', 'simple-social-images-wpjm' ),
		'description'    => __( 'Enter or choose the text background colour.', 'simple-social-images-wpjm' ),
		'input_type'     => 'color_picker',
		'order'          => 40,
	);

	$settings['bg_color'] = array(
		'option_name'    => 'ssi_wpjm_bg_color',
		'label'          => __( 'Background Colour', 'simple-social-images-wpjm' ),
		'description'    => __( 'Enter or choose the background colour.', 'simple-social-images-wpjm' ),
		'input_type'     => 'color_picker',
		'order'          => 50,
	);

	$settings['logo_section'] = array(
		'option_name' => 'ssi_wpjm_logo_section',
		'label'       => __( 'Logo Settings', 'simple-social-images-wpjm' ),
		'input_type'  => 'section',
		'order'       => 60,
	);

	$settings['logo'] = array(
		'option_name' => 'ssi_wpjm_logo',
		'label'       => __( 'Image', 'simple-social-images-wpjm' ),
		'description' => __( 'Upload your logo to display on your images.', 'simple-social-images-wpjm' ),
		'input_type'  => 'image',
		'order'       => 70,
	);

	$settings['logo_size'] = array(
		'option_name' => 'ssi_wpjm_logo_size',
		'label'       => __( 'Size', 'simple-social-images-wpjm' ),
		'description' => __( 'Select a size for the logo.', 'simple-social-images-wpjm' ),
		'input_type'  => 'range',
		'min'         => '4',
		'max'         => '12',
		'step'        => '0.1', 
		'order'       => 80,
	);

	$settings['background_images_section'] = array(
		'option_name' => 'ssi_wpjm_background_images_section',
		'label'       => __( 'Background Images Settings', 'simple-social-images-wpjm' ),
		'input_type'  => 'section',
		'order'       => 90,
	);

	$settings['background_images'] = array(
		'option_name' => 'ssi_wpjm_background_images',
		'label'       => __( 'Add Images', 'simple-social-images-wpjm' ),
		'description' => __( 'Upload background images to use on your template. Each template uses the background image slightly differently. Images are chosen at random from the images uploaded here, assuming your job does not have a featured image.', 'simple-social-images-wpjm' ),
		'input_type'  => 'gallery',
		'order'       => 100,
	);

	$settings['fonts_section'] = array(
		'option_name' => 'ssi_wpjm_font_sizes_section',
		'label'       => __( 'Font Settings', 'simple-social-images-wpjm' ),
		'input_type'  => 'section',
		'order'       => 110,
	);

	$settings['title_size'] = array(
		'option_name' => 'ssi_wpjm_title_size',
		'label'       => __( 'Title Size', 'simple-social-images-wpjm' ),
		'description' => __( 'Select a size for the title.', 'simple-social-images-wpjm' ),
		'input_type'  => 'range',
		'min'         => '2',
		'max'         => '12',
		'step'        => '0.1',
		'order'       => 120,
	);

	$settings['location_size'] = array(
		'option_name' => 'ssi_wpjm_location_size',
		'label'       => __( 'Location Size', 'simple-social-images-wpjm' ),
		'description' => __( 'Select a size for the location.', 'simple-social-images-wpjm' ),
		'input_type'  => 'range',
		'min'         => '2',
		'max'         => '6',
		'step'        => '0.1',
		'order'       => 130,
	);

	$settings['salary_size'] = array(
		'option_name' => 'ssi_wpjm_salary_size',
		'label'       => __( 'Salary Size', 'simple-social-images-wpjm' ),
		'description' => __( 'Select a size for the salary.', 'simple-social-images-wpjm' ),
		'input_type'  => 'range',
		'min'         => '2',
		'max'         => '6',
		'step'        => '0.1',
		'order'       => 140,
	);

	$settings['google_font_url'] = array(
		'option_name'       => 'ssi_wpjm_google_font_url',
		'label'             => __( 'Google Font URL', 'simple-social-images-wpjm' ),
		'description'       => __( 'Enter the URL of the Google font you wish to use.', 'simple-social-images-wpjm' ),
		'description'  => sprintf( __( 'Enter the URL of the Google font. %1$sSee an example of what is required%2$s (the highlighted text).', 'simple-social-images-wpjm' ), '<a target="_blank" href="' . esc_url( SSI_WPJM_LOCATION_URL . '/assets/img/google-font-url-example.jpg' ) . '">', '</a>' ),
		'input_type'        => 'text',
		'sanitize_callback' => 'sanitize_url',
		'order'             => 150,
	);

	$settings['google_font_family'] = array(
		'option_name' => 'ssi_wpjm_google_font_family',
		'label'       => __( 'Google Font Family', 'simple-social-images-wpjm' ),
		'description'  => sprintf( __( 'Enter the name of the Google font family. %1$sSee an example of what is required%2$s (the highlighted text).', 'simple-social-images-wpjm' ), '<a target="_blank" href="' . esc_url( SSI_WPJM_LOCATION_URL . '/assets/img/google-font-family-example.jpg' ) . '">', '</a>' ),
		'input_type'  => 'text',
		'order'       => 160,
	);

	// return the registered settings array.
	return $settings;

}

add_filter( 'ssi_wpjm_settings', 'ssi_wpjm_register_default_settings' );

/**
 * Controls the output of text input setting.
 *
 * @param  array $setting an array of the current setting.
 * @param  mixed $value   the current value of this setting saved in the database.
 */
function ssi_wpjm_setting_input_type_text( $setting, $value ) {

	// handle output for a text input.
	?>

	<input type="text" name="<?php echo esc_attr( $setting['option_name'] ); ?>" id="<?php echo esc_attr( $setting['option_name'] ); ?>" class="regular-text ssi-wpjm-input ssi-wpjm-input--text" value="<?php echo esc_attr( $value ); ?>" />

	<?php

}

add_action( 'ssi_wpjm_setting_type_text', 'ssi_wpjm_setting_input_type_text', 10, 2 );

/**
 * Controls the output of textarea input setting.
 *
 * @param  array $setting an array of the current setting.
 * @param  mixed $value   the current value of this setting saved in the database.
 */
function ssi_wpjm_setting_input_type_textarea( $setting, $value ) {

	// handle output for a text input.
	?>

	<textarea name="<?php echo esc_attr( $setting['option_name'] ); ?>" id="<?php echo esc_attr( $setting['option_name'] ); ?>" class="regular-text ssi-wpjm-input ssi-wpjm-input--textarea" value="<?php echo esc_attr( $value ); ?>"></textarea>

	<?php

}

add_action( 'ssi_wpjm_setting_type_textarea', 'ssi_wpjm_setting_input_type_textarea', 10, 2 );

/**
 * Controls the output of select input setting.
 *
 * @param  array $setting an array of the current setting.
 * @param  mixed $value   the current value of this setting saved in the database.
 */
function ssi_wpjm_setting_input_type_select( $setting, $value ) {

	// handle the output for a select input type setting.
	?>

	<select name="<?php echo esc_attr( $setting['option_name'] ); ?>" id="<?php echo esc_attr( $setting['option_name'] ); ?>" class="ssi-wpjm-input ssi-wpjm-input--select">

		<?php

		// check we have some options.
		if ( isset( $setting['options'] ) ) {

			// loop through each select option.
			foreach ( $setting['options'] as $option_value => $option_label ) {

				?>

				<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $value, $option_value ); ?>><?php echo esc_attr( $option_label ); ?></option>

				<?php

			} // End foreach().
		} // End if().

		?>

	</select>

	<?php

}

add_action( 'ssi_wpjm_setting_type_select', 'ssi_wpjm_setting_input_type_select', 10, 2 );

/**
 * Controls the output of checkbox input setting.
 *
 * @param  array $setting an array of the current setting.
 * @param  mixed $value   the current value of this setting saved in the database.
 */
function ssi_wpjm_setting_input_type_checkbox( $setting, $value ) {

	// handle output for a text input.
	?>

	<label for="<?php echo esc_attr( $setting['option_name'] ); ?>">
		<input type="checkbox" name="<?php echo esc_attr( $setting['option_name'] ); ?>" id="<?php echo esc_attr( $setting['option_name'] ); ?>" class="regular-text ssi-wpjm-input ssi-wpjm-input--checkbox" value="1" <?php checked( $value, 1 ); ?> />
		<span style="line-height: 1.8;"><?php echo wp_kses_post( $setting['description'] ); ?></span>
	</label>

	<?php

}

add_action( 'ssi_wpjm_setting_type_checkbox', 'ssi_wpjm_setting_input_type_checkbox', 10, 2 );

/**
 * Controls the output of checkbox input setting.
 *
 * @param  array $setting an array of the current setting.
 * @param  mixed $value   the current value of this setting saved in the database.
 */
function ssi_wpjm_setting_input_type_color_picker( $setting, $value ) {

	// handle output for a text input.
	?>

	<input type="text" name="<?php echo esc_attr( $setting['option_name'] ); ?>" id="<?php echo esc_attr( $setting['option_name'] ); ?>" class="regular-text ssi-wpjm-input ssi-wpjm-input--color-picker" value="<?php echo esc_attr( $value ); ?>" />

	<?php

}

add_action( 'ssi_wpjm_setting_type_color_picker', 'ssi_wpjm_setting_input_type_color_picker', 10, 2 );

/**
 * Controls the output of text input setting.
 *
 * @param  array $setting an array of the current setting.
 * @param  mixed $value   the current value of this setting saved in the database.
 */
function ssi_wpjm_setting_input_type_image( $setting, $value ) {

	// handle output for a text input.
	?>
	
	<div class="ssi-wpjm-image-wrapper">
		
		<?php

		// if we have an image already.
		if ( ! empty( $value ) ) {

			// get the url of the image.
			echo wp_get_attachment_image(
				absint( $value ),
				'thumbnail',
				false,
				array(
					'class' => 'ssi-wpjm-image',
					'id'    => $setting['option_name'] . 'image',
				)
			);

		} else {

			?>
			<img class="ssi-wpjm-image" src="<?php echo esc_url( SSI_WPJM_LOCATION_URL . '/assets/img/no-image.jpg' ); ?>" />
			<?php

		}

		?>
		
		<span class="dashicons dashicons-no ssi-wpjm-image--remove" data-placeholder="<?php echo esc_url( SSI_WPJM_LOCATION_URL . '/assets/img/no-image.jpg' ); ?>" data-input-id="<?php echo esc_attr( $setting['option_name'] ); ?>-input"></span>

	</div>

	<input type="text" name="<?php echo esc_attr( $setting['option_name'] ); ?>" id="<?php echo esc_attr( $setting['option_name'] ); ?>-input" class="regular-text ssi-wpjm-input ssi-wpjm-input--image" value="<?php echo esc_attr( $value ); ?>" />

	<a href="#" class="button-secondary ssi-wpjm-image-button"><?php esc_html_e( 'Upload/Choose Image', 'simple-social-images-wpjm' ); ?></a>

	<?php

}

add_action( 'ssi_wpjm_setting_type_image', 'ssi_wpjm_setting_input_type_image', 10, 2 );

/**
 * Controls the output of text input setting.
 *
 * @param  array $setting an array of the current setting.
 * @param  mixed $value   the current value of this setting saved in the database.
 */
function ssi_wpjm_setting_input_type_gallery( $setting, $value ) {

	// handle output for a text input.
	?>
	
	<div class="ssi-wpjm-gallery-wrapper" data-placeholder="<?php echo esc_url( SSI_WPJM_LOCATION_URL . '/assets/img/no-image.jpg' ); ?>">
		
		<?php

		// if we have an image already.
		if ( ! empty( $value ) ) {

			// turn the value into an array.
			$values = explode( ',', $value );
			
			// if value is an array.
			if ( is_array( $values ) && ! empty( $values ) ) {

				// loop through each image.
				foreach ( $values as $image ) {

					?>

					<figure class="ssi-wpjm-gallery-item">

						<?php

						// output the image.
						echo wp_get_attachment_image(
							absint( $image ),
							'thumbnail',
							false,
							array(
								'class' => 'ssi-wpjm-gallery-image',
								//'id'    => $setting['option_name'] . '-image',
							)
						);

						?>

						<span class="dashicons dashicons-no ssi-wpjm-gallery--remove" data-image-id="<?php echo esc_attr( absint( $image ) ); ?>"></span>

					</figure>

					<?php

				}

			}

		}

		?>

	</div>

	<input type="text" name="<?php echo esc_attr( $setting['option_name'] ); ?>" id="<?php echo esc_attr( $setting['option_name'] ); ?>-input" class="regular-text ssi-wpjm-input ssi-wpjm-input--gallery" value="<?php echo esc_attr( $value ); ?>" />

	<a href="#" class="button-secondary ssi-wpjm-gallery-button"><?php esc_html_e( 'Upload/Choose Images', 'simple-social-images-wpjm' ); ?></a>

	<?php

}

add_action( 'ssi_wpjm_setting_type_gallery', 'ssi_wpjm_setting_input_type_gallery', 10, 2 );

/**
 * Controls the output of text input setting.
 *
 * @param  array $setting an array of the current setting.
 * @param  mixed $value   the current value of this setting saved in the database.
 */
function ssi_wpjm_setting_input_type_range( $setting, $value ) {

	// defaults for min, max and step.
	$min = '1';
	$max = '20';
	$step = '1';

	// if we have a max.
	if ( ! empty( $setting['max'] ) ) {
		$max = $setting['max'];
	}

	// if we have a min.
	if ( ! empty( $setting['min'] ) ) {
		$min = $setting['min'];
	}

	// if we have a step.
	if ( ! empty( $setting['step'] ) ) {
		$step = $setting['step'];
	}

	// handle output for a text input.
	?>

	<input type="range" name="<?php echo esc_attr( $setting['option_name'] ); ?>" id="<?php echo esc_attr( $setting['option_name'] ); ?>" class="regular-text ssi-wpjm-input ssi-wpjm-input--range" min="<?php echo esc_attr( $min ); ?>" max="<?php echo esc_attr( $max ); ?>" step="<?php echo esc_attr( $step ); ?>" value="<?php echo esc_attr( $value ); ?>" />

	<?php

}

add_action( 'ssi_wpjm_setting_type_range', 'ssi_wpjm_setting_input_type_range', 10, 2 );

/**
 * Controls the output of license input setting.
 *
 * @param  array $setting an array of the current setting.
 * @param  mixed $value   the current value of this setting saved in the database.
 */
function ssi_wpjm_setting_input_type_section( $setting, $value ) {

	// if we have any section text.
	if ( ! empty( $setting['description'] ) ) {

		// output the text.
		?>
		<p class="section-text"><?php echo esc_html( $setting['description'] ); ?></p>
		<?php

	}

}

add_action( 'wpbb_setting_type_section', 'ssi_wpjm_setting_input_type_section', 10, 2 );

/**
 * Adds the description beneath each input.
 */
function ssi_wpjm_output_setting_descriptions( $setting, $value ) {

	// if we have a description.
	if ( empty( $setting['description'] ) ) {
		return;
	}

	// output the description.
	?>
	<p class="ssi-wpjm-input-description"><?php echo wp_kses_post( $setting['description'] ); ?></p>
	<?php

}

add_action( 'ssi_wpjm_after_setting', 'ssi_wpjm_output_setting_descriptions', 10, 2 );
