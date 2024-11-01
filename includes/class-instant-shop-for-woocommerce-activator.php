<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.presstigers.com/
 * @since      1.0.0
 *
 * @package    Instant_Shop_For_Woocommerce
 * @subpackage Instant_Shop_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Instant_Shop_For_Woocommerce
 * @subpackage Instant_Shop_For_Woocommerce/includes
 * @author     PressTigers < support@presstigers.com >
 */
class Instant_Shop_For_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
            update_option('isfw-processing','yes');
            update_option( 'isfw-completed','yes');
            update_option( 'isfw-one-page-checkout','yes');
	}

}
