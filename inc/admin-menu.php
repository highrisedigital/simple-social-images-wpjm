<?php

function ssi_wpjm_register_settings_page() {

	// add the sub menu page to settings.
	add_submenu_page(
		'edit.php?post_type=job_listing',
		__( 'Simple Social Images', 'simple-social-images-wpjm' ),
		__( 'Simple Social Images', 'simple-social-images-wpjm' ),
		'manage_options',
		'ssi-wpjm-settings',
		'ssi_wpjm_settings_page_output'
	);
	
}

add_action( 'admin_menu', 'ssi_wpjm_register_settings_page' );

function ssi_wpjm_settings_page_output() {

	?>

	<div class="wrap">

		<h1><?php echo esc_html_e( 'Simple Social Images Settings', 'simple-social-images-wpjm' ); ?></h1>

		<form method="post" action="options.php" class="ssi-wpjm-settings-form">

			<?php

			// output settings field nonce action fields etc.
			settings_fields( 'ssi_wpjm_settings' );

			// get the registered settings for this settings page.
			$settings = ssi_wpjm_get_settings();

			// check we have registered settings.
			if ( ! empty( $settings ) ) {

				// fores before the settings table output.
				do_action( 'ssi_wpjm_before_settings_output' );

				?>

				<table class="form-table">

					<tbody>

						<?php

						// loop through each registered setting.
						foreach ( $settings as $setting ) {

							// get the current value of this setting.
							$value = get_option( $setting['option_name'], '' );

							?>

							<tr valign="top" class="setting-type--<?php echo esc_attr( $setting['input_type'] ); ?>">

								<th scope="row">

									<?php

									// if this is a checkbox field.
									if ( 'checkbox' === $setting['input_type'] ) {

										// output the label.
										?>
										<span class="label"><?php echo esc_html( $setting['label'] ); ?></span>
										<?php

									} else { // setting type is not a checkbox.

										// output the field label.
										?>
										<label for="<?php echo esc_attr( $setting['option_name'] ); ?>"><?php echo esc_attr( $setting['label'] ); ?></label>
										<?php

									}

									?>

								</th>

								<td>

									<?php

									/**
									 * Fire a before setting action.
									 */
									do_action( 'ssi_wpjm_before_setting', $setting, $value );

									/**
									 * Create an action for this setting type
									 * functions for output can be hooked to it
									 */
									do_action( 'ssi_wpjm_setting_type_' . $setting['input_type'], $setting, $value );

									/**
									 * Fire a after setting action.
									 *
									 * @hooked ssi_wpjm_output_setting_descriptions - 10
									 */
									do_action( 'ssi_wpjm_after_setting', $setting, $value );

									?>

								</td>

							</tr>

							<?php

						}

						?>

					</tbody>

				</table>

				<?php

			}

			?>

			<p class="submit">
				<input type="submit" name="wpbb_settings_submit" id="submit" class="button-primary" value="<?php echo esc_attr( 'Save Changes', 'wpbroadbean' ); ?>" />
			</p>

			<?php

			// Fires after the settings output table is closed.
			do_action( 'ssi_wpjm_after_settings_output' );

			?>

		</form>

	</div>

	<?php

}