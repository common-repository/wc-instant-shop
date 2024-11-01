<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.presstigers.com/
 * @since      1.0.0
 *
 * @package    Instant_Shop_For_Woocommerce
 * @subpackage Instant_Shop_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Instant_Shop_For_Woocommerce
 * @subpackage Instant_Shop_For_Woocommerce/admin
 * @author     PressTigers < support@presstigers.com >
 */
class Instant_Shop_For_Woocommerce_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name    The name of this plugin.
	 * @param    string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
                require_once plugin_dir_path(dirname(__FILE__)) . '/admin/class-instant-shop-for-woocommerce-shop-tab.php';
                add_filter('plugin_action_links_' . ISFW_PLUGIN_BASENAME, array($this, 'isfw_settings_link'));

	}

    /**
     * Add setting option in WordPress plugin
     *
     * @since    1.0.0
     */
    public function isfw_settings_link($links) {
        if( ! isfw_is_woocommerce_active() ) return $links;
        $url = get_admin_url() . "admin.php?page=wc-settings&tab=instant-shop";
        $settings_link = '<a href="' . $url . '">' . __('Settings', 'instant-shop-for-woocommerce') . '</a>';
        $links[] = $settings_link;
        return $links;
    }

}
