<?php

/**
 * Create Instance Shop tab in WooCommerce settings.
 *
 * @link       https://www.presstigers.com/
 * @since      1.0.0
 *
 * @package      Instant_Shop_For_Woocommerce
 * @subpackage   Instant_Shop_For_Woocommerce/admin
 * @author       PressTigers <support@presstigers.com >
 */

class Instant_Shop_For_Woocommerce_Shop_Tab {

    /**
     * Initialize the class and set its properties.
     *
     * @since   1.0.0
     */
    public function __construct(){
        add_filter( 'woocommerce_settings_tabs_array',array($this,'isfw_add_instant_shop_tab'),50);
        add_action( 'woocommerce_settings_tabs_instant-shop',array($this, 'isfw_get_instant_shop_tab_data' ));
        add_action( 'woocommerce_update_options_instant-shop',array($this, 'isfw_update_instant_shop_tab_data' ));
      
    } 

    /**
     * This function create instant shop tab in WooCommerce settings.
     *
     * @since   1.0.0
     * 
     * @param   array   $settings_tabs    Have all tabs in wc settings.
     * @return  array   $settings_tabs    Return all tabs in wc settings.
    */
    public function isfw_add_instant_shop_tab( $settings_tabs )
    {
        $settings_tabs['instant-shop'] = __( 'Instant Shop', 'instant-shop-for-woocommerce' );
        return $settings_tabs;
    }

    /**
     * This function get data from instant shop tab and store it in the data base.
     *
     * @since   1.0.0
    */
    public function isfw_get_instant_shop_tab_data() {
        woocommerce_admin_fields( $this->isfw_instant_shop_tab_data() );
        
    }

    /**
     * This function display settings fields in the instant shop tab.
     *
     * @since   1.0.0
     * 
     * @return  array   WooCommerce settings tab fields
     */
    public function isfw_instant_shop_tab_data() {
        $settings =array(
            array(
                'name'     => __( 'One Page Checkout', 'instant-shop-for-woocommerce' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wc_settings_tab_section_title'
            ),
            array(
                'name' => __( 'One Page Checkout', 'instant-shop-for-woocommerce' ),
                'type' => 'checkbox',
                'desc' => __( 'If you want to show checkout page on single product page,just enable this checkbox.', 'instant-shop-for-woocommerce' ),
                'id'   => 'isfw-one-page-checkout'
            ),
            array(
                'type' => 'sectionend',
                'id' => 'wc_settings_tab_section_end'
            ),
            array(
                'name'     => __( 'Order Again', 'instant-shop-for-woocommerce' ),
                'type'     => 'title',
                'desc'     => __('Select the below order status options to allow the customers to reorder a product instantly.','instant-shop-for-woocommerce'),
                'id'       => 'wc_settings_tab_section_title'
            ),
            array(
                'name' => __( 'Processing status', 'instant-shop-for-woocommerce' ),
                'type' => 'checkbox',
                'desc' => __( 'Show order again button against processing status.', 'instant-shop-for-woocommerce' ),
                'id'   => 'isfw-processing'
            ),
            array(
                'name' => __( 'Completed status', 'instant-shop-for-woocommerce' ),
                'type' => 'checkbox',
                'desc' => __( 'Show order again button against completed status.', 'instant-shop-for-woocommerce' ),
                'id'   => 'isfw-completed'
            ),
            array(
                'type' => 'sectionend',
                'id' => 'wc_settings_tab_section_end'
            ),
        );
        
        return apply_filters( 'wc_settings_tab_demo_settings', $settings );
    }
    
    /**
     * This function is used for update the data in instant shop tab.
     *
     * @since   1.0.0
     */
    function isfw_update_instant_shop_tab_data() {
        woocommerce_update_options( $this->isfw_instant_shop_tab_data() );

    }
     
}
new Instant_Shop_For_Woocommerce_Shop_Tab();