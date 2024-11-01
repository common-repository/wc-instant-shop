<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.presstigers.com/
 * @since      1.0.0
 *
 * @package    Instant_Shop_For_Woocommerce
 * @subpackage Instant_Shop_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Instant_Shop_For_Woocommerce
 * @subpackage Instant_Shop_For_Woocommerce/public
 * @author     PressTigers < support@presstigers.com >
 */
class Instant_Shop_For_Woocommerce_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
                require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-instant-shop-for-woocommerce-shop.php';

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Instant_Shop_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Instant_Shop_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/instant-shop-for-woocommerce-public.css', array(), $this->version, 'all' );

	}

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Instant_Shop_For_Woocommerce_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Instant_Shop_For_Woocommerce_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if (!isfw_is_woocommerce_active())
            return;
        
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/instant-shop-for-woocommerce-public.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'isfw_ajax_obj', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'checkout_option' => get_option('isfw-one-page-checkout'),
            'isfw_select_product' => __('Please select a product', 'instant-shop-for-woocommerce'),
            'isfw_ajax_issue' => __('AJAX is not working', 'instant-shop-for-woocommerce'),
            'checkout_ajax_url' => plugin_dir_url( 'woocommerce/woocommerce.php' ).'assets/js/frontend/checkout.min.js'
        ));
        $params = array(
            'ajax_url' => WC()->ajax_url(),
            'wc_ajax_url' => WC_AJAX::get_endpoint('%%endpoint%%'),
            'update_order_review_nonce' => wp_create_nonce('update-order-review'),
            'apply_coupon_nonce' => wp_create_nonce('apply-coupon'),
            'remove_coupon_nonce' => wp_create_nonce('remove-coupon'),
            'option_guest_checkout' => get_option('woocommerce_enable_guest_checkout'),
            'checkout_url' => WC_AJAX::get_endpoint('checkout'),
            'is_checkout' => is_checkout() && empty($wp->query_vars['order-pay']) && !isset($wp->query_vars['order-received']) ? 1 : 0,
            'debug_mode' => false,
            'i18n_checkout_error' => esc_attr__('Error processing checkout. Please try again.', 'woocommerce'),
        );
        wp_localize_script($this->plugin_name, 'wc_checkout_params', $params);
    }

}
