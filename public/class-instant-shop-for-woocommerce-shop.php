<?php

/**
 * show one page checkout and order again functionality.
 *
 * @link       https://www.presstigers.com/
 * @since      1.0.0
 *
 * @package      Instant_Shop_For_Woocommerce
 * @subpackage   Instant_Shop_For_Woocommerce/admin
 * @author     PressTigers <support@presstigers.com >
 */

 
class Instant_Shop_For_Woocommerce_Shop{
    /**
     * Initialize the class and set its properties.
     *
     * @since   1.0.0
     */
    public function __construct() {
        add_action( 'woocommerce_after_single_product', array( $this, 'isfw_after_single_product' ) );
        if(esc_html(get_option( 'isfw-one-page-checkout' ))=="yes"){
            add_action( 'wc_ajax_ace_add_to_cart', array( $this, 'isfw_ajax_add_to_cart_handler' ));
            add_action( 'wc_ajax_nopriv_ace_add_to_cart', array( $this, 'isfw_ajax_add_to_cart_handler' ));
            add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'isfw_ajax_add_to_cart_add_fragments' )); 
            add_action( 'plugins_loaded', function() {
                remove_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ), 20 );
            });
            add_action( 'wp_ajax_nopriv_isfw_woocommerce_button_after__add_to_cart', array( $this, 'isfw_woocommerce_button_after__add_to_cart'));
            add_action( 'wp_ajax_isfw_woocommerce_button_after__add_to_cart', array( $this, 'isfw_woocommerce_button_after__add_to_cart'));
        }   
  
        add_action( 'wp_ajax_isfw_addition_in_add_to_cart_function', array( $this, 'isfw_addition_in_add_to_cart_function'));
        add_action( 'wc_ajax_nopriv_isfw_addition_in_add_to_cart_function', array( $this, 'isfw_addition_in_add_to_cart_function'));
        add_action( 'wp_ajax_isfw_reload_table_data', array( $this, 'isfw_reload_table_data'));
        add_action( 'wc_ajax_nopriv_isfw_reload_table_data', array( $this, 'isfw_reload_table_data'));
        add_filter( 'woocommerce_my_account_my_orders_actions', array( $this, 'isfw_order_again_to_my_orders_actions'), 50, 2);    
    }
    

    /**
     * Ajax function which show woocommerce checkout  in single product page.
     *
     * @since   1.0.0
     * 
    */
    public function isfw_after_single_product() {
        echo '<div id="isfw-checkout">';
        echo '</div>';
    }
    
     /**
     * Get woocommerce checkout page from shortcode.
     *
     * @since   1.0.0
     * 
    */
    public function isfw_woocommerce_button_after__add_to_cart() {
        echo '<div id="isfw-one-page-checkout">' ;
        echo "<hr>
              <div ><h3 class='Place-order'>".__('Place an Order','instant-shop-for-woocommerce')."</h3><div>";
        echo do_shortcode( '[woocommerce_checkout]');
        echo '</div>';
    } 
    
     /**
     * Single product add to cart button ajax function.
     *
     * @since   1.0.0
     * 
    */
    public function isfw_ajax_add_to_cart_handler() {
        WC_Form_Handler::add_to_cart_action();
        WC_AJAX::get_refreshed_fragments();
    }

    /**
     * Display WooCommerce add to cart notice.
     *
     * @since   1.0.0
     * 
     * @param  array  $fragments   WooCommerce add to cart notice. 
     * @return array  $fragments   Return WooCommerce add to cart notice.
     */
    public function isfw_ajax_add_to_cart_add_fragments( $fragments ) {
        $all_notices  = WC()->session->get( 'wc_notices', array() );
        $notice_types = apply_filters( 'woocommerce_notice_types', array( 'error', 'success', 'notice' ) );
        ob_start();
        foreach ( $notice_types as $notice_type ) {
            if ( wc_notice_count( $notice_type ) > 0 ) {
                wc_get_template( "notices/{$notice_type}.php", array(
                'notices' => array_filter( $all_notices[ $notice_type ] ),
                ));
            }

        }

        $fragments['notices_html'] = ob_get_clean();
        wc_clear_notices();
       
        return $fragments;
        
    }
    
    /**
     * This functions perform order again and view order functionality.
     * Filter Hook -> Provide order again and view order functionality.
     * 
     * @since  1.0.0
     * 
     * @param  array    $actions   Order detail page with link.
     * @param  object   $order     WooCommerce Order Object.
     * @return array    $actions   Link and button text for order detail view.
     * @return object   $order     Button with popup to order again. 
    */
    public function isfw_order_again_to_my_orders_actions( $actions, $order ) {
        
        $order_status_processing =esc_html(get_option( 'isfw-processing' ));
        $order_status_completed =esc_html(get_option( 'isfw-completed' ));
        if ( ($order->has_status( 'processing' ) && $order_status_processing == 'yes')|| ( $order->has_status( 'completed' ) && $order_status_completed == 'yes')) {
            $this-> isfw_order_again( $order ); 
        }
        return $actions;
    }

    /**
     * Complete front end view  of order again functionality.
     *
     * @since  1.0.0
     * 
     * @param  object    $order     WooCommerce Order Object.
    */
    public function isfw_order_again( $order ) {
        ?>
        <div class="isfw_box">
            <a class="button order-again" data-orderid='<?php echo  esc_attr($order->get_id()); ?>' ><?php _e('Order again','instant-shop-for-woocommerce')?></a>
        </div>
        <div id="order-again-<?php echo esc_attr($order->get_id()); ?>" class="isfw_overlay">
            <div class="isfw_popup">
                <?php  echo "<p class='isfw_text'>" .__('Please order again against the order id','instant-shop-for-woocommerce').' #'.esc_attr($order->get_id()).' </p>'; ?> 
                <a class="isfw_close" style="cursor: pointer">&times;</a>
                <div class="content">
                    <form method="post">
                        <p  class='isfw_ofc-message'><p>    
                    
                        <div id='isfw-popup-data-<?php echo esc_attr($order->get_id()); ?>'>
                        
                            <?php
                            echo $this->isfw_table_data($order);
                        ?>
                        </div>
                        <div class="isfw_modal-footer">
                            <img class='isfw_loader-image' src="<?php echo plugin_dir_url( __FILE__ ) . 'images/ajax-loader.gif'; ?>">
                            <br>
                            <div class="isfw-popup-btn">
                            <button type="submit" data-orderid='<?php echo esc_attr($order->get_id()); ?>'   class="ATC-order-again" ></i> <?php _e('Add to cart','instant-shop-for-woocommerce')?></button>
                            </div>
                            <br>

                            <p class='isfw_success-message'> <p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Display order data in popup.
     *
     * @since  1.0.0
     * 
     * @param  object   $order         WooCommerce Order Object.
     * @return string   $order_data    View order data in popup.
    */
    public function isfw_table_data( $order ) {
        $order_data = '
     
        <div class="isfw-popup-btn">
        <button type="button" data-orderid="'.esc_attr($order->get_id()).'" class="isfw_select_all">'.__('Select All','instant-shop-for-woocommerce').'</button> 
        </div>
        <div class="isfw-popup-data">
            <div class="isfw_heading"></div>
            <div class="isfw_heading"><b>'.__('Product','instant-shop-for-woocommerce').'</b></div>
            <div class="isfw_heading"style="text-align:center"><b>'.__('price','instant-shop-for-woocommerce').'</b></div>
            <div class="isfw_heading"style="text-align: center"><b>'.__('Total','instant-shop-for-woocommerce').'</b></div>

        </div>
        <div class="isfw-scroll">';
            foreach ( $order->get_items() as $item_id => $item ){
                $item_name   = $item->get_name(); 
                $item_quantity  = $item->get_quantity();
                $item_total   = $item->get_total();
                $product_id     = $item->get_product_id();
                $variation_id=$item->get_variation_id();
                $product = wc_get_product($product_id);
                $product_url = get_permalink($product_id);
                $product_name=$product->name;
                $product_price=$product->price;
                $variable_product = wc_get_product($variation_id);
                $variable_product_name=$variable_product->name;
                $variable_product_price=$variable_product->price;
                $variable_product_url = get_permalink($variation_id);
                $cart_item_quantities = WC()->cart->get_cart_item_quantities();
                $product_qty_in_cart =  $cart_item_quantities[ $product_id ] ?? null;
                $variable_product_qty_in_cart =  $cart_item_quantities[$variation_id] ?? null;
                $order_data .= '  <div class="isfw-popup-data">';
             
                
                if(!$product OR $product->get_status()!='publish') {
                    $order_data .= $this-> isfw_not_available($item_name,$item_quantity, $item_total );
                }
                elseif( $product->is_type( 'variable' ) &&(!$variable_product || $variable_product->get_status()!='publish')) {
                    $order_data .= $this-> isfw_not_available($item_name,$item_quantity, $item_total );
                }
                elseif($product->is_type( 'simple' ) && $product->get_sold_individually()=="yes" && $item_quantity>1 ){
                    $order_data .= $this-> isfw_sold_individually($product_name,$item_quantity,$product_price,$product_url );
                }
                elseif($product->is_type( 'variable' ) && $product->get_sold_individually()=="yes" && $item_quantity>1 ){
                    $order_data .= $this-> isfw_sold_individually( $variable_product_name,$item_quantity,$variable_product_price,$variable_product_url );
                }
            
                else{
                        if( $product->is_type( 'simple' ) ){
                            $order_data .= $this->isfw_product_data(
                                $product,
                                $product_qty_in_cart,
                                $item,
                                $product_name,
                                $item_quantity,
                                $product_price,
                                $product_url
                            ) ;   
                        }

                        if( $product->is_type( 'variable' ) ){
                            $order_data .= $this->isfw_product_data(
                                $variable_product,
                                $variable_product_qty_in_cart,
                                $item,
                                $variable_product_name,
                                $item_quantity,
                                $variable_product_price,
                                $variable_product_url
                            ) ;
                        }

                }
                $order_data .= '</div>';
            }
         
            $order_data .= '</div>';
        return $order_data;
        
    }

    /**
     * This functions tells product is in stock or not.
     *
     * @since   1.0.0
     * 
     * @param  object   $product                 product object
     * @param  int      $product_qty_in_cart     product quantity in cart
     * @param  object   $item                    Order object with item variable
     * @param  string   $product_name            Product name
     * @param  int      $item_quantity           product quantity in order
     * @param  float    $product_price           product price
     * @param  string   $product_url             product link
     * @return string                            Return products after checking the inventory    
     
    */
    public function isfw_product_data( $product,$product_qty_in_cart,$item,$product_name,$item_quantity,$product_price , $product_url ){

        if ( $product->get_backorders() == "notify" ) { 
            return $this->isfw_in_stock_product( $item,$product_name,$item_quantity,$product_price, $product_url  );
        }

        if ( $product->get_backorders() == "yes" ) { 
            return $this->isfw_in_stock_product( $item,$product_name,$item_quantity,$product_price, $product_url  );
        }

        if ( $product->get_backorders() == "no" && $product->get_stock_status() == 'outofstock' ) {
            return $this->isfw_out_of_stock_product( $product_name,$item_quantity,$product_price, $product_url  );
        }
                                    
        if (
            $product->get_backorders() == "no" &&
            $product->get_stock_status() =='instock' && 
            $product->get_stock_quantity() !== NULL &&
            $product->get_stock_quantity() < ( $item_quantity+$product_qty_in_cart ) 
            ) {
                return $this-> isfw_out_of_stock_product( $product_name,$item_quantity,$product_price, $product_url  );
        }

        if (
            $product->get_backorders() =="no" &&
            $product->get_stock_status() =='instock' &&  $product->get_stock_quantity() !== NULL &&
            $product->get_stock_quantity() >= $item_quantity+$product_qty_in_cart 
            ) {
                return $this->isfw_in_stock_product( $item,$product_name,$item_quantity,$product_price, $product_url  );
        }

        if ( 
            $product->get_backorders() =="no" && 
            $product->get_stock_status()=='instock' &&  
            $product->get_stock_quantity()==NULL
            ) {
                return $this->isfw_in_stock_product( $item,$product_name,$item_quantity,$product_price, $product_url  );
        }

        if ( 
            $product->get_backorders() =="no" &&
            $product->get_stock_status() == 'onbackorder' &&
            $product->get_stock_quantity() == NULL
            ) {
                return $this->isfw_in_stock_product( $item,$product_name,$item_quantity,$product_price, $product_url  );
        }

    }

    /**
     * Display in stock products.
     *
     * @since   1.0.0
     * 
     * @param  object   $item                    Order object with item variable
     * @param  string   $product_name            Product name
     * @param  int      $item_quantity           Product quantity in order
     * @param  float    $product_price           Product price
     * @param  string   $product_url             Product link
     * @return string                            Return instock products
     
    */
    public function isfw_in_stock_product( $item,$product_name,$item_quantity,$product_price, $product_url ){
        return ' <div class="isfw_product_detail style="text-align: center" ">
        <input type="checkbox"class="isfw_select_product isfw_product_checkbox" name="product_id[]" value='.esc_attr($item->get_product_id()).
        ','.esc_attr($item->get_quantity()).','.esc_attr($item->get_variation_id()).' />    &nbsp;   
        </div>
        <div class="isfw_product_detail"><a href="'.esc_url($product_url).'" target="_blank" >' .esc_attr($product_name).'</a> &times; <b>'.esc_attr($item_quantity).'</b></div>
        <div class="isfw_product_detail"style="text-align: center">' .wc_price(esc_attr($product_price)). '</div>
        <div class="isfw_product_detail"style="text-align: center;">'.wc_price(esc_attr($product_price*$item_quantity)). '</div>';
    }

    /**
     * Display out of stock product.
     *
     * @since   1.0.0
     * 
     * @param  string   $product_name            Product name
     * @param  int      $item_quantity           product quantity in order
     * @param  float    $product_price           product price
     * @param  string   $product_url             product link
     * @return string                            Return out of stock products.
     
    */
    public function isfw_sold_individually( $product_name,$item_quantity,$product_price,$product_url ){
        return ' <div class="isfw_product_detail">
        </div>
        <div class="isfw_product_detail"><a href="'.esc_url($product_url).'" target="_blank" >' .esc_attr($product_name).'</a> &times; <b>'.esc_attr($item_quantity).
        '</b><p class="isfw-out-of-stock"> ('.__('only allow one of this item','instant-shop-for-woocommerce').')</p></div>
        <div class="isfw_product_detail"style="text-align: center">' .wc_price(esc_attr($product_price)). '</div>
        <div class="isfw_product_detail"style="text-align: center">'.wc_price(esc_attr($product_price*$item_quantity)). '</div>';
    }

    /**
     * Display those products which can sell only one time.
     *
     * @since   1.0.0
     * 
     * @param  string   $product_name            Product name
     * @param  int      $item_quantity           product quantity in order
     * @param  float    $product_price           product price
     * @param  string   $product_price           product link
     * @return string                            Return product that sell at once
     
    */
    public function isfw_out_of_stock_product( $product_name,$item_quantity,$product_price,$product_url ){
        return ' <div class="isfw_product_detail">
        </div>
        <div class="isfw_product_detail"><a href="'.esc_url($product_url).'" target="_blank" >' .esc_attr($product_name).'</a> &times; <b>'.esc_attr($item_quantity).'</b><p class="isfw-out-of-stock"> ('.__('out of stock','instant-shop-for-woocommerce').')</p></div>
        <div class="isfw_product_detail"style="text-align: center">' .wc_price(esc_attr($product_price)). '</div>
        <div class="isfw_product_detail"style="text-align: center;">'.wc_price(esc_attr($product_price*$item_quantity)). '</div>';
    }

    /**
     * Display non available products.
     *
     * @since   1.0.0
     * 
     * @param  string   $product_name       Product name
     * @param  int      $item_quantity      Product quantity in order
     * @param  float    $item_total         Total price of product
     * @param  string   $product_price      Product link
     * @return string                       Return non available products.
     
    */
    function isfw_not_available( $product_name,$item_quantity,$item_total ){
        return ' <div class="isfw_product_detail">
        </div>
        <div class="isfw_product_detail">' .esc_attr($product_name).' &times; '.esc_attr($item_quantity).'<p class="isfw-out-of-stock"> ('.__('Not available','instant-shop-for-woocommerce').')</p></div>
        <div class="isfw_product_detail"style="text-align: center">' .wc_price(esc_attr($item_total/$item_quantity)). '</div>
        <div class="isfw_product_detail"style="text-align: center;">'.wc_price(esc_attr($item_total)). '</div>';
    }

    /**
     * Order again products are added in the cart by ajax.
     *
     * @since   1.0.0
     * 
     * @return  string   $message   Add to cart notice.
     */
    public function isfw_addition_in_add_to_cart_function () {
        $product = array_map( 'sanitize_text_field', (array)$_POST['product'] );
        $cart_link= wc_get_cart_url();
        if ( isset($product) ) {
            foreach($product as $item){
               
                $items= explode(",",$item);
                $data = WC()->cart->add_to_cart( $items[0],$items[1],$items[2]);
                if( $data ){
                    $added_product[]=$data;
                    $product_count = count($added_product);
                    $product_in_stock = sprintf( _n( '%s Product has been added', '%s Products have been added', esc_attr($product_count), 'instant-shop-for-woocommerce' ), esc_attr($product_count )).'<a class="isfw-cart-link" href="'.esc_url($cart_link).'">'.__('View cart', 'instant-shop-for-woocommerce').'</a> <br>'; 
            
                }

                else {
                    if( $items[2] ){
                            $product = wc_get_product( $items[2] );
                            $product_not_in_stock[] = ''.__('You cannot add another','instant-shop-for-woocommerce').'  "' .esc_attr($product->get_name()). '"  '.__(' to your cart.','instant-shop-for-woocommerce').' <br>';
                    }

                    else{
                            $product = wc_get_product( $items[0] );
                            $product_not_in_stock[] = ''.__('You cannot add another','instant-shop-for-woocommerce').'  "' .esc_attr($product->get_name()). '"  '.__(' to your cart.','instant-shop-for-woocommerce').' <br>';
                    }
                    
                }
                
            }

        }
        $message = array(
            'success_message' => $product_in_stock,
            'not_in_message' => $product_not_in_stock 
            );
        echo json_encode( $message);
        wp_die();
    }

    /**
     *An ajax function which reload table with updated products status, after previous values is added in the cart
     *
     * @since   1.0.0
     * 
    */
    public function isfw_reload_table_data(){
        $order_id = sanitize_text_field( $_POST['orderid'] );
        $order = wc_get_order( $order_id );
        $new_html=$this->isfw_table_data($order);
        echo json_encode($new_html);
        wp_die();
    }

}
new  Instant_Shop_For_Woocommerce_Shop();
