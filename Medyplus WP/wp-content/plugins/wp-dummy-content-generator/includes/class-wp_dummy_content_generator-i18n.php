<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://tutsocean.com/about-me
 * @since      1.0.0
 *
 * @package    wp_dummy_content_generator
 * @subpackage wp_dummy_content_generator/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    wp_dummy_content_generator
 * @subpackage wp_dummy_content_generator/includes
 * @author     Deepak anand <anand.deepak9988@gmail.com>
 */
class wp_dummy_content_generator_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp_dummy_content_generator',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
