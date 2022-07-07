<?php
/**
 * Registers the associted plugin settings with the WP Job Manager plugin.
 */

function ssi_wpjm_register_settings( $settings ) {

	// create a new tab in the settings page.
	$settings['ssi_wpjm'] = array(
		__( 'Simple Social Images', 'simple-social-images-wpjm' ),
		array(
			array(
				'name'       => 'ssi_wpjm_license_key',
				'label'      => __( 'License Key', 'simple-social-images-wpjm' ),
				'desc'       => __( 'Enter your license key for the Simple Social Images service. You can <a target="_blank" href="https://simplesocialimages.com/">signup for a license here</a>.', 'simple-social-images-wpjm' ),
				'type'       => 'text',
				'attributes' => array(),
			),
			array(
				'name'       => 'ssi_wpjm_template',
				'std'        => '',
				'label'      => __( 'Template', 'simple-social-images-wpjm' ),
				'desc'       => __( 'Choose your template for the social sharing images that are generated.', 'simple-social-images-wpjm' ),
				'type'       => 'select',
				'options'    => array(
					'1' => __( 'Template 1', 'simple-social-images-wpjm' ),
					'2' => __( 'Template 2', 'simple-social-images-wpjm' ),
					'3' => __( 'Template 3', 'simple-social-images-wpjm' ),
					'4' => __( 'Template 4', 'simple-social-images-wpjm' ),
					'5' => __( 'Template 5', 'simple-social-images-wpjm' ),
				),
				'attributes' => array(),
			),
			array(
				'name'       => 'ssi_wpjm_text_color',
				'label'      => __( 'Text Colour', 'simple-social-images-wpjm' ),
				'desc'       => __( 'Enter the hex code of your text colour. The text on your image will be this colour.', 'simple-social-images-wpjm' ),
				'type'       => 'text',
				'attributes' => array(),
			),
			array(
				'name'       => 'ssi_wpjm_text_bg_color',
				'label'      => __( 'Text Background Colour', 'simple-social-images-wpjm' ),
				'desc'       => __( 'Enter the hex code of your text background colour. The colour behind your text on your image will be this colour.', 'simple-social-images-wpjm' ),
				'type'       => 'text',
				'attributes' => array(),
			),
			array(
				'name'       => 'ssi_wpjm_bg_color',
				'label'      => __( 'Background Colour', 'simple-social-images-wpjm' ),
				'desc'       => __( 'Enter the hex code of your background colour.', 'simple-social-images-wpjm' ),
				'type'       => 'text',
				'attributes' => array(),
			),
			array(
				'name'       => 'ssi_wpjm_images',
				'label'      => __( 'Image IDs', 'simple-social-images-wpjm' ),
				'desc'       => __( 'Enter a comma seperated list of WordPress attachment IDs of the images you want to be randomly used when your image is generated.', 'simple-social-images-wpjm' ),
				'type'       => 'text',
				'attributes' => array(),
			),
			array(
				'name'       => 'ssi_wpjm_logo_id',
				'label'      => __( 'Logo Attachment ID', 'simple-social-images-wpjm' ),
				'desc'       => __( 'Enter the attachment IDs of the image you want to use as the logo.', 'simple-social-images-wpjm' ),
				'type'       => 'text',
				'attributes' => array(),
			),
		),
		array(
			'before' => __( 'Simple Social Images generates a automatic, beautiful and branded image for each job which, if the job is shared on social media, for example LinkedIn, shows as the preview image for the job. This really makes your jobs stand out from the crowd in social media feeds. Enter your settings below.', 'simple-social-image-wpjm' ),
		),
	);

	// return the settings array.
	return $settings;

}

add_filter( 'job_manager_settings', 'ssi_wpjm_register_settings', 10, 1 );
