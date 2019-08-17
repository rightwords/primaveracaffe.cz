<?php 
/**
 * @package    Zásilkovna
 * @author    Woo
 * @license   GPL-2.0+
 * @link      http://woo.cz
 * @copyright 2014 woo
 */

class Zasilkovna_Helper {

    /**
     * Get weight by weight unit
     * 
     * @since 1.1.9
     */ 
    public static function get_weight( $weight ){
        
        $weight_unit = get_option('woocommerce_weight_unit');
        if(!empty($weight_unit) && $weight_unit == 'g'){ 
    
            $weight = $weight * 0.001; 
    
        }
    
        return $weight;
    }


    /**
     * Multi currency handle
     *
     */  
    public static function currency_handle($fee){ 
  
        $active_plugins = get_option('active_plugins');
            if(in_array('woocommerce-currency-switcher/index.php', $active_plugins)){
    
                $fee = apply_filters('woocs_exchange_value', $fee);
    
            }elseif( in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins ) ){
                // WPML WooCommerce Multicurrency fix
                global $woocommerce_wpml;                            
                if(!empty($woocommerce_wpml)){
                    if(property_exists($woocommerce_wpml,'multi_currency')){
                        $fee = $woocommerce_wpml->multi_currency->convert_price_amount($fee);
                    }
                } 
            }
    
        return $fee;  
    }


    /**
     *
     * Get current payment gateway
     *
     */
    
    public static function get_current_gateway(){
        
        $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
        
        $current_gateway = null;
        
        $default_gateway = get_option( 'woocommerce_default_gateway' );
        if ( ! empty( $available_gateways ) ) {

           // Chosen Method
            if ( isset( WC()->session->chosen_payment_method ) && isset( $available_gateways[ WC()->session->chosen_payment_method ] ) ) {
                
                $current_gateway = $available_gateways[ WC()->session->chosen_payment_method ];
            
            } elseif ( isset( $available_gateways[ $default_gateway ] ) ) {
                $current_gateway = $available_gateways[ $default_gateway ];
            } else {
                $current_gateway = current( $available_gateways );
            }
        }

        if ( ! is_null( $current_gateway ) )
            return $current_gateway;
        else 
            return false;
    }


    /**
     *
     * Current payment gateway setting
     *
     */   
 
    public static function get_current_gateway_settings( ) {
        if ( $current_gateway = self::get_current_gateway() ) {
            $settings = $current_gateway->settings;
            return $settings;
        }
        return false;
    }

    /**
     * Is free shipping available
     *
     */   
    public static function free_shippping_available(){

        $all_packages = WC()->shipping->get_packages();
        $packages = $all_packages[0]['rates'];
    
        $is_available = false;
        foreach( $packages as $item ){
            if( $item->id == 'zasilkovna'  ){
                if( $item->cost == 0 ){ $is_available = true; }
            }
        }
    
        return $is_available;

    } 

    /**
     * Check if is disable product in cart
     *
     * return true or false
     */            
    public static function is_disable_product_in_cart() {
   
        $has_disable = false;
        if(!empty( WC()->session->cart->cart_contents)){
            $cart_data = WC()->session->cart->cart_contents;
        }else{
            $cart_data = WC()->session->cart;
        }
        if( !empty( $cart_data ) ){
            foreach($cart_data as $item){
        
                $product = wc_get_product( $item['product_id'] );
                $product_id = Woo_Product_Compatibility::get_id( $product );
                $disable_product = get_post_meta( $product_id, 'disable_zasilkovna_shipping', true ); 
            
                if( !empty($disable_product) && $disable_product == 'yes' ){
                    $has_disable = true;
                } 
            } 
        }
        
        return $has_disable;
   
    }

    /**
     * Check if is disable product in cart
     *
     * return true or false
     */            
    public static function set_fee_by_dobirka_free_shipping( $fee, $shipping_total, $dobirka_option ){
   
        if( $shipping_total == 0 ){
        
            if( !empty( $dobirka_option['show_cod'] ) && $dobirka_option['show_cod'] == 'yes' ){
                return $fee;
            }else{
                return 0;
            }
        }else{
            return $fee;
        }
   
    }

    /**
     * Set shipping services
     *
     */
    public static function set_shipping_services(){

        $services = array(
            '13'   => __( 'Česká pošta - balík na poštu ', 'zasilkovna' ),
             '14'   => __( 'Česká pošta - balík do ruky', 'zasilkovna' ),
            '15'   => __( 'Česká Pošta - balík do balíkovny', 'zasilkovna' ),
            '633'  => __( 'Česká republika DPD', 'zasilkovna' ),
            '136'  => __( 'Expresní doručení Brno', 'zasilkovna' ),
            '134'  => __( 'Expresní doručení Ostrava', 'zasilkovna' ),
            '257'  => __( 'Expresní doručení Praha', 'zasilkovna' ),
            '80'   => __( 'Rakouská pošta', 'zasilkovna' ),
            '111'  => __( 'Německá pošta', 'zasilkovna' ),
//            '763'  => __( 'Maďarská pošta', 'zasilkovna' ),
//            '805'  => __( 'Maďarsko DPD', 'zasilkovna' ),
//            '151'  => __( 'Maďarsko Transoflex', 'zasilkovna' ),
            '272'  => __( 'Polská pošta', 'zasilkovna' ),
            '1406' => __( 'Polsko DPD', 'zasilkovna' ),
//            '836'  => __( 'Rumunsko DPD', 'zasilkovna' ),
//            '762'  => __( 'Rumunsko FAN', 'zasilkovna' ),
            '131'  => __( 'Doručenie na adresu SR', 'zasilkovna' ),
            '132'  => __( 'Expresné doručenie Bratislava', 'zasilkovna' ),
            '16'   => __( 'Slovenská pošta', 'zasilkovna' ),
            '149'  => __( 'Slovensko kurýr', 'zasilkovna' ),
//            '1234' => __( 'Bulharsko DPD', 'zasilkovna' ), 
            //'1160' => 'Ukrajina na adresu',
        );

        return $services;

    }

    /**
     * Set shipping services
     *
     */
    public static function set_shipping_ids(){

        $ids = array(
            'ceska-posta-cz'      => '13',
             'ceska-posta-cz-ruka'      => '14',
            'ceska-posta-cz-balikovna'      => '15',
            'dpd-cz'              => '633',
            'express-brno'        => '136',
            'express-ostrava'     => '134',
            'express-praha'       => '257',
            'austria-at'          => '80',
            'germany-de'          => '111',
            'hungary-hu'          => '763',
            'dpd-hu'              => '805',
            'transoflex-hu'       => '151',
            'poland-pl'           => '272',
            'dpd-pl'              => '1406',
            'slovensko-na-adresu' => '131',
            'slovenska-posta'     => '16',
            'express-bratislava'  => '132',
            'slovensko-kuryr'     => '149',
            'dpd-ro'              => '836',
            'fan-ro'              => '762',
            'dpd-bl'              => '1234',
            //'ukrajina-doruceni'   => '1160',
        );

        return $ids;       

    }

    /**
     * Set shipping services ids for order
     *
     */
    public static function set_order_shipping_ids(){

        $ids = array(
            'zasilkovna>z-points',
            'zasilkovna>ceska-posta-cz',
             'zasilkovna>ceska-posta-cz-ruka',
             'zasilkovna>ceska-posta-cz-balikovna',
            'zasilkovna>dpd-cz',
            'zasilkovna>express-brno',
            'zasilkovna>express-ostrava',
            'zasilkovna>express-praha',
            'zasilkovna>austria-at',
            'zasilkovna>germany-de',
            'zasilkovna>hungary-hu',
            'zasilkovna>dpd-hu',
            'zasilkovna>transoflex-hu',
            'zasilkovna>poland-pl',
            'zasilkovna>dpd-pl',
            'zasilkovna>slovensko-na-adresu',
            'zasilkovna>slovenska-posta',
            'zasilkovna>express-bratislava',
            'zasilkovna>slovensko-kuryr',
            'zasilkovna>dpd-ro',
            'zasilkovna>fan-ro',
            'zasilkovna>dpd-bl',
            //'zasilkovna>ukrajina-doruceni',
        );

        return $ids;       

    }

    /**
     * Set shipping services
     *
     */
    public static function set_services(){

        $services = array(13,14,15,153,633,136,134,257,80,111,763,805,151,272,1406,836,762,131,132,16,149,1234,1160);
        return $services;

    }

    /**
     * Set shipping services 
     *
     */
    /*
    public static function define_shippings(){

        $data = array(
            'z-points' => array(
                'fields' => array(
                )
            ),
            'ceska-posta-cz' => array(),
            'dpd-cz' => array(),
            'express-brno' => array(),
            'express-ostrava' => array(),
            'express-praha' => array(),
            'austria-at' => array(),
            'germany-de' => array(),
            'hungary-hu' => array(),
            'dpd-hu' => array(),
            'transoflex-hu' => array(),
            'poland-pl' => array(),
            'dpd-pl' => array(
                'fields' => array(
                    'dpd-pl-1kg',
                    'dpd-pl-2kg',
                    'dpd-pl-5kg',
                    'dpd-pl-10kg',
                    'dpd-pl-30kg'
                ),
                'service_id' => 1406
            ),
            'slovensko-na-adresu' => array(),
            'slovenska-posta' => array(),
            'express-bratislava' => array(),
            'slovensko-kuryr' => array(),
            'dpd-ro' => array(),
            'fan-ro' => array(),
            'dpd-bl' => array(),
            //'zasilkovna>ukrajina-doruceni',
        );

        return $data;       

    }
    */

    /**
     * Control isset shipping
     *
     */
    public static function isset_shipping( $option, $value ){

        if( isset( $option[$value] ) ){
            return $option[$value];
        }else{
            //return 0;
            return false;
        }

    }

    static public function get_customer_country(){

        $version = woo_check_wc_version();
        if( $version === false ){

            $country = WC()->customer->__get('country');   
                    
        }else{

            $shipping_country = WC()->customer->get_shipping_country();

            if( !empty( $shipping_country ) ){

                $country = WC()->customer->get_shipping_country();
            
            }else{

                $country = WC()->customer->get_billing_country();

            }

        }

        return $country;

    }

    static public function cr_setting_fields(){

        $fields = array(
            'zasilkovna-cr-5kg',
            'zasilkovna-cr-10kg',
            'zasilkovna-cr-20kg',
            'zasilkovna-cr-30kg',
             'zasilkovna-cr-40kg',
             'zasilkovna-cr-50kg',
             'zasilkovna-cr-60kg',
            'zasilkovna-cr-nad60kg',
            'zasilkovna-cr-dobirka',
            'zasilkovna-cr-free',
            'ceska-posta-cz-2kg',
            'ceska-posta-cz-3kg',
            'ceska-posta-cz-4kg',
            'ceska-posta-cz-5kg',
            'ceska-posta-cz-10kg',
            'ceska-posta-cz-30kg',
            'ceska-posta-cz-dobirka',
            'ceska-posta-cz-pojisteni',
            'ceska-posta-cz-ruka-2kg',
            'ceska-posta-cz-ruka-3kg',
            'ceska-posta-cz-ruka-4kg',
            'ceska-posta-cz-ruka-5kg',
            'ceska-posta-cz-ruka-10kg',
            'ceska-posta-cz-ruka-30kg',
            'ceska-posta-cz-ruka-dobirka',
            'ceska-posta-cz-ruka-pojisteni',
             'ceska-posta-cz-balikovna-2kg',
            'ceska-posta-cz-balikovna-3kg',
            'ceska-posta-cz-balikovna-4kg',
            'ceska-posta-cz-balikovna-5kg',
            'ceska-posta-cz-balikovna-10kg',
            'ceska-posta-cz-balikovna-30kg',
            'ceska-posta-cz-balikovna-dobirka',
            'ceska-posta-cz-balikovna-pojisteni',
            'dpd-cz-5kg',
            'dpd-cz-10kg',
            'dpd-cz-30kg',
            'dpd-cz-50kg',
            'dpd-cz-dobirka',
            'dpd-cz-pojisteni',
            'express-brno-5kg',
            'express-brno-10kg',
            'express-brno-30kg',
            'express-brno-dobirka',
            'express-brno-pojisteni',
            'express-ostrava-5kg',
            'express-ostrava-10kg',
            'express-ostrava-30kg',
            'express-ostrava-dobirka',
            'express-ostrava-pojisteni',
            'express-praha-5kg',
            'express-praha-10kg',
            'express-praha-30kg',
            'express-praha-dobirka',
            'express-praha-pojisteni'
        );

        return $fields;

    }

    static public function sk_setting_fields(){

        $fields = array(
            'zasilkovna-sk-5kg',
            'zasilkovna-sk-10kg',
            'zasilkovna-sk-20kg',
            'zasilkovna-sk-30kg',
            'zasilkovna-sk-dobirka',
            'zasilkovna-sk-free',
            'slovensko-doruceni-1kg',
            'slovensko-doruceni-2kg',
            'slovensko-doruceni-5kg',
            'slovensko-doruceni-10kg',
            'slovensko-doruceni-20kg',
            'slovensko-doruceni-30kg',
            'slovensko-doruceni-40kg',
            'slovensko-doruceni-50kg',
            'slovensko-doruceni-dobirka',
            'express-bratislava-5kg',
            'express-bratislava-10kg',
            'express-bratislava-30kg',
            'express-bratislava-dobirka',
            'express-bratislava-pojisteni',
            'slovenska-posta-100g',
            'slovenska-posta-1kg',
            'slovenska-posta-2kg',
            'slovenska-posta-5kg',
            'slovenska-posta-10kg',
            'slovenska-posta-15kg',
            'slovenska-posta-dobirka',
            'slovenska-posta-pojisteni',
            'slovensko-kuryr-1kg',
            'slovensko-kuryr-2kg',
            'slovensko-kuryr-5kg',
            'slovensko-kuryr-10kg',
            'slovensko-kuryr-20kg',
            'slovensko-kuryr-30kg',
            'slovensko-kuryr-40kg',
            'slovensko-kuryr-50kg',
            'slovensko-kuryr-dobirka',
            'slovensko-kuryr-pojisteni'            
        );

        return $fields;

    }

    static public function pl_setting_fields(){

        $fields = array(
            'zasilkovna-pl-5kg',
            'zasilkovna-pl-10kg',
            'zasilkovna-pl-20kg',
            'zasilkovna-pl-30kg',
            'zasilkovna-pl-dobirka',
            'zasilkovna-pl-free',
            'poland-pl-1kg',
            'poland-pl-2kg',
            'poland-pl-5kg',
            'poland-pl-10kg',
            'poland-pl-15kg',
            'poland-pl-20kg',
            'poland-pl-dobirka',
            'poland-pl-pojisteni',
            'dpd-pl-1kg',
            'dpd-pl-2kg',
            'dpd-pl-5kg',
            'dpd-pl-10kg',
            'dpd-pl-30kg',
        );

        return $fields;

    }

    static public function hu_setting_fields(){

        $fields = array(
            'zasilkovna-hu-5kg',
            'zasilkovna-hu-10kg',
            'zasilkovna-hu-20kg',
            'zasilkovna-hu-30kg',
            'zasilkovna-hu-dobirka',
            'zasilkovna-hu-free',
            'hungary-hu-1kg',
            'hungary-hu-2kg',
            'hungary-hu-5kg',
            'hungary-hu-10kg',
            'hungary-hu-30kg',
            'dpd-hu-1kg',
            'dpd-hu-2kg',
            'dpd-hu-5kg',
            'dpd-hu-10kg',
            'dpd-hu-30kg',
            'transoflex-hu-1kg',
            'transoflex-hu-2kg',
            'transoflex-hu-5kg',
            'transoflex-hu-10kg',
            'transoflex-hu-15kg',
            'transoflex-hu-30kg',
        );

        return $fields;

    }

    static public function de_setting_fields(){

        $fields = array(
            'germany-de-1kg',
            'germany-de-2kg',
            'germany-de-5kg',
            'germany-de-10kg',
            'germany-de-20kg',
            'germany-de-dobirka',
            'germany-de-pojisteni',            
        );

        return $fields;

    }

    static public function at_setting_fields(){

        $fields = array(
            'austria-at-1kg',
            'austria-at-2kg',
            'austria-at-5kg',
            'austria-at-10kg',
            'austria-at-dobirka',
            'austria-at-pojisteni',            
        );

        return $fields;

    }

    static public function ro_setting_fields(){

        $fields = array(
            'zasilkovna-ro-5kg',
            'zasilkovna-ro-10kg',
            'zasilkovna-ro-20kg',
            'zasilkovna-ro-30kg',
            'zasilkovna-ro-dobirka',
            'zasilkovna-ro-free',
            'fan-ro-1kg',
            'fan-ro-2kg',
            'fan-ro-5kg',
            'fan-ro-10kg',
            'fan-ro-15kg',
            'fan-ro-dobirka',
            'fan-ro-pojisteni',
            'dpd-ro-1kg',
            'dpd-ro-2kg',
            'dpd-ro-5kg',
            'dpd-ro-10kg',
            'dpd-ro-15kg',
            'dpd-ro-30kg',
            'dpd-ro-dobirka',
            'dpd-ro-pojisteni',           
        );

        return $fields;

    }

    static public function bl_setting_fields(){

        $fields = array(
            'dpd-bl-1kg',
            'dpd-bl-2kg',
            'dpd-bl-5kg',
            'dpd-bl-10kg',
            'dpd-bl-15kg',
            'dpd-bl-30kg',
            'dpd-bl-dobirka',
            'dpd-bl-pojisteni',          
        );

        return $fields;

    }

    static public function services_price_kurzy(){

        $fields = array(
            'kurz-euro',
            'kurz-forint',
            'kurz-zloty',
            'kurz-lei'
        );

        return $fields;

    }




}//End class
