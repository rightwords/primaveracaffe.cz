<?php 
/**
 * @package    Zásilkovna
 * @author    Woo
 * @license   GPL-2.0+
 * @link      http://woo.cz
 * @copyright 2014 woo
 */

class Zasilkovna_Fee {

    private $shipping_total;

    private $dobirka_option;

    private $zasilkovna_prices;

    private $disable_product;

    private $country;

    private $doprava_name;


    /**
     * Zasilkovna Fee constructor.
     * 
     */
    public function __construct()
    {
        $this->shipping_total = WC()->shipping->shipping_total;
        $this->dobirka_option = get_option( 'woocommerce_dobirka_settings' );
        $this->zasilkovna_prices = get_option('zasilkovna_prices');
        $this->disable_product = Zasilkovna_Helper::is_disable_product_in_cart(); 
        $this->country = woo_get_customer_country(); 
        $this->doprava_name = explode( '>', WC()->session->chosen_shipping_methods[0] ); 

        $this->calculate_fee();

    }

    /**
     * Calculate fee
     * 
     * @since 1.5.4
     */ 
    public function calculate_fee(){
                
        if( $this->disable_product === true ){ return false; }

        if ( ($current_gateway = Zasilkovna_Helper::get_current_gateway() ) && ( $settings = Zasilkovna_Helper::get_current_gateway_settings() ) ) {
    
            if( $current_gateway->id == 'dobirka' ){            
            
                if( $this->doprava_name[0] == 'zasilkovna' ){
                
                    $fee = $this->get_fee_value();

                    if( $fee != false ){

                        $dobirka_option = get_option( 'woocommerce_dobirka_settings' );
                        if(!empty($dobirka_option['taxable']) && $dobirka_option['taxable'] == 'yes' ){ $taxable = true; }else{ $taxable = false; }  

                        $fee = Zasilkovna_Helper::set_fee_by_dobirka_free_shipping( $fee, $this->shipping_total, $this->dobirka_option );
                
                        //WooCommerce Multilingual compatibility
                        $filtered_fee = apply_filters( 'wcml_raw_price_amount', $fee );
                        if( !empty( $filtered_fee ) ){ $fee = $filtered_fee; }

                        //WooCommerce currency Switcher compatibility
                        $filtered_fee = apply_filters('woocs_exchange_value', $fee);
                        if( !empty( $filtered_fee ) ){ $fee = $filtered_fee; }
                
                        $dobirka_label = apply_filters( 'zasilkovna_dobirka_label', __( 'Příplatek za Dobírku', 'zasilkovna' ) );
                        WC()->cart->add_fee( $dobirka_label, $fee, $taxable ); 

                    }   
                }   
            }

            if( $this->doprava_name[0] == 'zasilkovna' ){

                $this->set_insurance();

            }
        }
    }


    /**
     * Calculate fee
     * 
     * @since 1.5.4
     */ 
    public function get_fee_value(){

        if( $this->doprava_name[1] == 'z-points' ){ 
            //Get fee for Zasilkovna shipping by country
            if( $this->country == 'CZ' ){

                $fee = $this->is_empty_price( 'zasilkovna-cr-dobirka' );
            
            }elseif($this->country == 'SK'){
            
                $fee = $this->is_empty_price( 'zasilkovna-sk-dobirka' );
            
            }elseif($this->country == 'PL'){
            
                $fee = $this->is_empty_price( 'zasilkovna-pl-dobirka' );
            
            }elseif($this->country == 'HU'){
            
                $fee = $this->is_empty_price( 'zasilkovna-hu-dobirka' );
            
            }elseif($this->country == 'RO'){
            
                $fee = $this->is_empty_price( 'zasilkovna-ro-dobirka' );
            
            }else{

                $fee =false;
            
            }

        }else{
            //Get any fee        
            if( $this->doprava_name[1] == 'slovensko-na-adresu' ){

                $fee = $this->is_empty_price( 'slovensko-doruceni-dobirka' );
            
            }else{
            
                $fee = $this->is_empty_price( $this->doprava_name[1].'-dobirka' );
            
            }               
        
        }

        //Return
        if( $fee === false ){

            return false;
        
        }else{
        
            return $fee;
        
        }

    }

    /**
     * Set insurance
     * 
     * @since 1.5.4
     */ 
    public function set_insurance(){

        $pojisteni_label = apply_filters( 'zasilkovna_pojisteni_label', __( 'Pojištění zásilky', 'zasilkovna' ) );

        if( !empty( $this->zasilkovna_prices[$this->doprava_name[1].'-pojisteni'] ) ){

            $pojisteni = $this->zasilkovna_prices[$this->doprava_name[1].'-pojisteni'];
                
            //WooCommerce Multilingual compatibility
            $pojisteni = apply_filters( 'wcml_raw_price_amount', $pojisteni );

            //WooCommerce currency Switcher compatibility
            $pojisteni = apply_filters('woocs_exchange_value', $pojisteni);

            WC()->cart->add_fee( $pojisteni_label, $pojisteni, true );

        }

    }

    /**
     * Ïs empty
     * 
     * @since 1.5.4
     */ 
    public function is_empty_price( $option_name ){

        if( !empty( $this->zasilkovna_prices[$option_name] ) ){
            $fee = $this->zasilkovna_prices[$option_name];
        }else{
            $fee = false;
        }

        return $fee;

    }


}//End class
