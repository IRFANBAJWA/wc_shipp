<?php
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    function tutsplus_shipping_method() {
        if ( ! class_exists( 'TutsPlus_Shipping_Method' ) ) {
            class TutsPlus_Shipping_Method extends WC_Shipping_Method {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct() {
                    $this->id                 = 'tutsplus'; 
                    $this->method_title       = __( 'TutsPlus Shipping', 'tutsplus' );  
                    $this->method_description = __( 'Custom Shipping Method for TutsPlus', 'tutsplus' ); 
 
                    $this->init();
 
                    $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'TutsPlus Shipping', 'tutsplus' );
                }
 
                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init() {
                    // Load the settings API
                    $this->init_form_fields(); 
                    $this->init_settings(); 
 
                    // Save settings in admin if you have any defined
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }
 
                /**
                 * Define settings field for this shipping
                 * @return void 
                 */
                 function init_form_fields() { 
 
                    $this->form_fields = array(
                 
                     'enabled' => array(
                          'title' => __( 'Enable', 'tutsplus' ),
                          'type' => 'checkbox',
                          'description' => __( 'Enable this shipping.', 'tutsplus' ),
                          'default' => 'yes'
                          ),
                 
                     'title' => array(
                        'title' => __( 'Title', 'tutsplus' ),
                          'type' => 'text',
                          'description' => __( 'Title to be display on site', 'tutsplus' ),
                          'default' => __( 'TutsPlus Shipping', 'tutsplus' )
                          ),
                 
                     'weight' => array(
                        'title' => __( 'Weight (kg)', 'tutsplus' ),
                          'type' => 'number',
                          'description' => __( 'Maximum allowed weight', 'tutsplus' ),
                          'default' => 100
                          ),
                 
                     );
                 
                }
 
                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                 public function calculate_shipping( $package ) {
                    
                    $weight = 0;
                    $cost = 0; 
                    $quantity = 0; 
                    $country = $package["destination"]["country"];
                    $crattotal = count(WC()->cart->get_cart());
                    foreach ( $package['contents'] as $item_id => $values ) 
                    { 
                        $_product = $values['data']; 
                        $price = $_product->get_price();
                        $weight = $weight + $_product->get_weight() * $values['quantity']; 
                        $quantity =  $values['quantity']; 
                   /*     if($price >= 0.01 and $price <= 25 and !empty($crattotal) ){
                            $tcost = 0;
                       for($i = 0; $i < $crattotal; $i++){
                            
                            $a = (($price - 0.01)*100)/ (25-0.01);
                           
                            $b = 5 * number_format($a,2)/100;
                        
                            $c = 40 - $b;
                            
                            $d = ($price * $c)/100;
                            
                            $tcost = number_format($d,2);
                            $cost = $cost + $tcost;
                        } 
                 }else{
                     $cost = 5805;
                 }*/
                    }

                    //testtin code

                    $tcost = 0;
                    foreach ( WC()->cart->get_cart() as $cart_item ) {
                        $item_name = $cart_item['data']->get_title();
                        $quantity = $cart_item['quantity'];
                        $price = $cart_item['data']->get_price();
                       // $cost = $cost + $price;
                        if($price >= 0.01 and $price <= 25 and !empty($crattotal) ){
                          
                            
                            $a = (($price - 0.01)*100)/ (25-0.01);
                           
                            $b = 5 * number_format($a,2)/100;
                        
                            $c = 40 - $b;
                            
                            $d = ($price * $c)/100;
                            
                            $tcost = number_format($d,2);

                            if($quantity > 1){
                               $tcost = $tcost * $quantity;
                            }else{
                                $tcost = $tcost;
                            }

                            $cost = $cost + $tcost;
                        }else{
                            $cost = 5805;
                        }
                    }
            
               /*     $crattotal = count(WC()->cart->get_cart());
                    echo $crattotal;
                    $weight = wc_get_weight( $weight, 'kg' );
                    
                    if($price >= 0.01 and $price <= 25 and !empty($crattotal) ){
                        $tcost = 0;
                   for($i = 0; $i < $crattotal; $i++){
                        
                        $a = (($price - 0.01)*100)/ (25-0.01);
                       
                        $b = 5 * number_format($a,2)/100;
                    
                        $c = 40 - $b;
                        
                        $d = ($price * $c)/100;
                        
                        $tcost = number_format($d,2);
                        $cost = $cost + $tcost;
                    } 
             }else{
                 $cost = 5805;
             }*/

                    /*
                    if( $weight <= 10 ) {
                 
                        $cost = 0;
                 
                    } elseif( $weight <= 30 ) {
                 
                        $cost = 250;
                 
                    } elseif( $weight <= 50 ) {
                 
                        $cost = $price;
                 
                    } else {
                 
                        $cost = 20;
                 
                    }*/
                 
                    $countryZones = array(
                        'HR' => 0,
                        'US' => 3,
                        'GB' => 2,
                        'CA' => 3,
                        'ES' => 2,
                        'DE' => 1,
                        'IT' => 1
                        );
                 
                    $zonePrices = array(
                        0 => 10,
                        1 => 30,
                        2 => 50,
                        3 => 70
                        );
                 
                   // $zoneFromCountry = $countryZones[ $country ];
                   // $priceFromZone = $zonePrices[ $zoneFromCountry ];
                 
                    //$cost += $priceFromZone;
                 
                    $rate = array(
                        'id' => $this->id,
                        'label' => $this->title,
                        'cost' => $cost
                    );
                 
                    $this->add_rate( $rate );
                    
                }
            }
        }
    }
 
    add_action( 'woocommerce_shipping_init', 'tutsplus_shipping_method' );
 
    function add_tutsplus_shipping_method( $methods ) {
        $methods[] = 'TutsPlus_Shipping_Method';
        return $methods;
    }
 
    add_filter( 'woocommerce_shipping_methods', 'add_tutsplus_shipping_method' );


    function tutsplus_validate_order( $posted )   {
 
        $packages = WC()->shipping->get_packages();
         
        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
         
        if( is_array( $chosen_methods ) && in_array( 'tutsplus', $chosen_methods ) ) {
             
            foreach ( $packages as $i => $package ) {
         
                if ( $chosen_methods[ $i ] != "tutsplus" ) {
                             
                    continue;
                             
                }
         
                $TutsPlus_Shipping_Method = new TutsPlus_Shipping_Method();
                $weightLimit = (int) $TutsPlus_Shipping_Method->settings['weight'];
                $weight = 0;
         
                foreach ( $package['contents'] as $item_id => $values ) 
                { 
                    $_product = $values['data']; 
                    $weight = $weight + $_product->get_weight() * $values['quantity']; 
                }
         
                $weight = wc_get_weight( $weight, 'kg' );
                
                if( $weight > $weightLimit ) {
         
                        $message = sprintf( __( 'Sorry, %d kg exceeds the maximum weight of %d kg for %s', 'tutsplus' ), $weight, $weightLimit, $TutsPlus_Shipping_Method->title );
                             
                        $messageType = "error";
         
                        if( ! wc_has_notice( $message, $messageType ) ) {
                         
                            wc_add_notice( $message, $messageType );
                      
                        }
                }
            }       
        } 
    }
    add_action( 'woocommerce_review_order_before_cart_contents', 'tutsplus_validate_order' , 10 );
    add_action( 'woocommerce_after_checkout_validation', 'tutsplus_validate_order' , 10 );
}