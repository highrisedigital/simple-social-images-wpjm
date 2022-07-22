<?php
/**
 * Plugin Name: Simple Social Images for WP Job Manager
 * Plugin URI: https://simplesocialimages.com/wp-job-manager/
 * Description: Create automated, beautiful and branded images for jobs shared on social media channels. This plugin requires a license for <a href="https://simplesocialimages.com">Simple Social Images</a>.
 * Version: 0.1
 * Author: Highrise Digital
 * Author URI: https://highrise.digital/
 * Text Domain: simple-social-images-wpjm
 * Domain Path: /languages/
 * License: GPL2+
 */

// define variable for path to this plugin file.
define( 'SSI_WPJM_LOCATION', dirname( __FILE__ ) );
define( 'SSI_WPJM_LOCATION_URL', plugins_url( '', __FILE__ ) );
define( 'SSI_WPJM_VERSION', '1.0' );

/**
 * Function to run on plugins load.
 */
function ssi_wpjm_plugins_loaded() {

	$locale = apply_filters( 'plugin_locale', get_locale(), 'simple-social-images-wpjm' );
	load_textdomain( 'simple-social-images-wpjm', WP_LANG_DIR . '/simple-social-images-wpjm/simple-social-images-wpjm-' . $locale . '.mo' );
	load_plugin_textdomain( 'simple-social-images-wpjm', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );

}

add_action( 'plugins_loaded', 'ssi_wpjm_plugins_loaded' );

// load in the loader file which loads everything up.
require_once( dirname( __FILE__ ) . '/inc/loader.php' );
