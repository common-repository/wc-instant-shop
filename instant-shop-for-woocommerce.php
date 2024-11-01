<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.presstigers.com/
 * @since             1.0.0
 * @package           Instant_Shop_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Instant Shop for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/instant-shop-for-woocommerce/
 * Description:       User friendly plugin for WooCommerce with single page checkout which facilitates users to shop instantly and to reorder their previously purchased products.
 * Version:           1.0.0
 * Author:            PressTigers
 * Author URI:        https://www.presstigers.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       instant-shop-for-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ISFW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'INSTANT_SHOP_FOR_WOOCOMMERCE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-instant-shop-for-woocommerce-activator.php
 */
function activate_instant_shop_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-instant-shop-for-woocommerce-activator.php';
	Instant_Shop_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-instant-shop-for-woocommerce-deactivator.php
 */
function deactivate_instant_shop_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-instant-shop-for-woocommerce-deactivator.php';
	Instant_Shop_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_instant_shop_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_instant_shop_for_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-instant-shop-for-woocommerce.php';

/**
 * Check if WooCommerce is activated
 */
function isfw_is_woocommerce_active(){
    return class_exists( 'woocommerce' );
}
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_instant_shop_for_woocommerce() {

	$plugin = new Instant_Shop_For_Woocommerce();
	$plugin->run();

}
run_instant_shop_for_woocommerce();
