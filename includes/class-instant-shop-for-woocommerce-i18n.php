<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.presstigers.com/
 * @since      1.0.0
 *
 * @package    Instant_Shop_For_Woocommerce
 * @subpackage Instant_Shop_For_Woocommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Instant_Shop_For_Woocommerce
 * @subpackage Instant_Shop_For_Woocommerce/includes
 * @author     PressTigers < support@presstigers.com >
 */
class Instant_Shop_For_Woocommerce_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'instant-shop-for-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
