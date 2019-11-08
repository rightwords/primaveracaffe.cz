<?php

function woocommerce_zasilkovna_shipping_init(){


if ( !class_exists( 'WC_Shipping_Method' ) ) 
      return;



 
		if ( ! class_exists( 'WC_Zasilkovna_Shipping_Method' ) ) {
			class WC_Zasilkovna_Shipping_Method extends WC_Shipping_Method {
				

                private $zasilkovna_option;

                private $zasilkovna_prices;
                
                private $zasilkovna_services;

                /**
				 * Constructor for your shipping class
				 *
				 * @access public
				 * @return void
				 */
				public function __construct( $instance_id = 0 ) {
        
                    $licence_status = get_option('woo-zasilkovna-licence');
                    if ( empty( $licence_status ) ) {
	                   return false;
                    }
        
        
					$this->id                 = 'zasilkovna'; 
                    $this->instance_id        = absint( $instance_id );
					$this->enabled            = "yes";

                    $this->supports           = array(
                        'shipping-zones',
                        'instance-settings',
                        'instance-settings-modal',
                    );

					$this->init();
                    $this->method_title     = __( 'Zásilkovna', 'zasilkovna' );
                    
					$this->method_description = $this->zasilkovna_title;
          
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
          
                    $this->availability     = $this->get_option( 'availability' );
                    $this->countries 	    = $this->get_option( 'countries' );         
                    $this->cost             = $this->get_option( 'cost_zasilkovna' );
                    $this->cost_dobirka     = $this->get_option( 'cost_dobirka' );
                    $this->zasilkovna_title = $this->get_option( 'zasilkovna_title' );
                    $this->title            = $this->get_option( 'title' );
          
          
					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );

                    $this->zasilkovna_option   = get_option( 'zasilkovna_option' );
                    $this->zasilkovna_prices   = get_option( 'zasilkovna_prices' );
                    $this->zasilkovna_services = get_option( 'zasilkovna_services' );


				}
        
        
                function init_form_fields() {
					
					$this->instance_form_fields = array(
						'enabled' => array(
							'title'       => __( 'Povolit Zásilkovnu', 'woocommerce' ),
							'type' 	      => 'checkbox',
							'label'       => __( 'Povolit tento způsob', 'woocommerce' ),
							'default'     => 'no',
							),
						'title' => array(
							'title'       => __( 'Název dopravce', 'woocommerce' ),
							'type'        => 'text',
							'description' => __( '', 'woocommerce' ),
							'default'     => __( 'Zásilkovna', 'woocommerce' ),
							),
						'zasilkovna_title' => array(
							'title'       => __( 'Zásilkovna titulek', 'woocommerce' ),
							'type'        => 'text',
							'description' => __( 'Text, který zákazník uvidí', 'woocommerce' ),
							'default'     => 'Zásilkovna',
						)
					);

				}
        
        
 
				/**
				 * calculate_shipping function.
				 *
				 */
				public function calculate_shipping( $package = array() ) {
        
                    $disable_product = Zasilkovna_Helper::is_disable_product_in_cart(); 
                    if( $disable_product === true ){ 
          
                        $rates = array(
                            'id'       => $this->id.'>charita',
                            'label'    => __('Zásilkovna', 'woocommerce-zasilkovna'),
                            'cost'     => 0,
                            'calc_tax' => 'per_order'
                        );
                        // Register the rate
                        $this->add_rate( $rates );
          
                    }else{
        
                        $zasilkovna_option   = get_option( 'zasilkovna_option' );
                        $zasilkovna_prices   = get_option( 'zasilkovna_prices' );
                        $zasilkovna_services = get_option( 'zasilkovna_services' );
          
                        $country = woo_get_customer_country();
                        $weight  = Zasilkovna_Helper::get_weight( WC()->cart->cart_contents_weight );
          
                        if($country == 'CZ'){

                            $this->set_z_point_rates( $zasilkovna_prices, $weight, 'cr' );
                            $this->set_ceska_posta_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight);
                            $this->set_ceska_posta_ruka_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight); 
                             $this->set_ceska_posta_balikovna_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight);
                            $this->set_in_time_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight);
                            $this->set_dpd_cz_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight);
                            $this->set_express_brno_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight);
                            $this->set_express_praha_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight);
                            $this->set_express_ostrava_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight);
                        
                        }
                        elseif($country == 'SK'){

                            $this->set_z_point_rates( $zasilkovna_prices, $weight, 'sk' );;
                            $this->set_slovensko_posta_rates_sk($zasilkovna_prices,$zasilkovna_services,$weight);
                            $this->set_slovensko_na_adresu_rates_sk($zasilkovna_prices,$zasilkovna_services,$weight);
                            $this->set_express_bratislava_rates_sk($zasilkovna_prices,$zasilkovna_services,$weight);
                            $this->set_slovensko_kuryr_rates_sk($zasilkovna_prices,$zasilkovna_services,$weight);
                        
                        }
                        elseif($country == 'HU'){
                        
                            $this->set_z_point_rates( $zasilkovna_prices, $weight, 'hu' );
                            $this->set_hungary_rates_hu($zasilkovna_prices,$zasilkovna_services,$weight);
                            $this->set_dpd_hu_rates_hu($zasilkovna_prices,$zasilkovna_services,$weight);
                            $this->set_transoflex_hu_rates_hu($zasilkovna_prices,$zasilkovna_services,$weight);
                        
                        }
                        elseif($country == 'DE'){
                            $this->set_germany_rates_de($zasilkovna_prices,$zasilkovna_services,$weight);
                        }
                        elseif($country == 'PL'){
                            $this->set_z_point_rates( $zasilkovna_prices, $weight, 'pl' );
                            $this->set_poland_rates_pl($zasilkovna_prices,$zasilkovna_services,$weight);
                            $this->set_dpd_pl_rates( $zasilkovna_prices, $zasilkovna_services, $weight );
                        }
                        elseif($country == 'AT'){
                            $this->set_austria_rates_at($zasilkovna_prices,$zasilkovna_services,$weight);
                        }
                        elseif($country == 'RO'){
                            $this->set_z_point_rates( $zasilkovna_prices, $weight, 'ro' );
                            $this->set_fan_ro_rates($zasilkovna_prices,$zasilkovna_services,$weight);
                            $this->set_dpd_ro_rates($zasilkovna_prices,$zasilkovna_services,$weight);
                        }
                        elseif($country == 'BG'){
                            //$this->set_z_point_rates( $zasilkovna_prices, $weight, 'bg' );
                            $this->set_dpd_bl_rates($zasilkovna_prices,$zasilkovna_services,$weight);
                        }
                    } 
          
				}
        
                /**
                 * Set Z Point rates Czech
                 *
                 */                
                private function set_z_point_rates_cz( $zasilkovna_prices, $weight ){
                    $currency = get_option('woocommerce_currency');
    
                    $cost = false;

                    if($weight > 0.01 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-cr-5kg' );
                    }elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-cr-10kg' );
                    }elseif($weight > 10.01 && $weight < 20){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-cr-20kg' );
                         }elseif($weight > 20.01 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-cr-30kg' );
                        }elseif($weight > 30.01 && $weight < 40){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-cr-40kg' );
                        }elseif($weight > 40.01 && $weight < 50){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-cr-50kg' );
                        }elseif($weight > 50.01 && $weight < 60){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-cr-60kg' );
                        }
                        elseif($weight > 60.01 && $weight < 100){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-cr-nad60kg' );
                    } 
//                    else{ 
//                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-cr-nad60kg' );
//                    }
    
                    if( $cost === false ){ return false; }    

                    $rates = array(
						'id'       => $this->id.'>z-points',
						'label'    => $this->zasilkovna_title,
						'cost'     => $cost,
                        'calc_tax' => 'per_order'
					);
                    // Register the rate
                    $this->add_rate( $rates );
                }
  
                /**
                 * Set Z Point rates Slovak
                 *
                 */                
                private function set_z_point_rates_sk($zasilkovna_prices,$weight){
     
                    $cost = false;

                    if($weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-sk-5kg' );
                    }elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-sk-10kg' );
                    }elseif($weight > 10.01 && $weight < 20){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-sk-20kg' );
                    }else{ 
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-sk-5kg' );
                    }
                    $currency = get_option('woocommerce_currency');
    
                    if( $cost === false ){ return false; }  
                    $rates = array(
						'id'       => $this->id.'>z-points',
						'label'    => $this->zasilkovna_title,
						'cost'     => $cost,
                        'calc_tax' => 'per_order'
					);
                    // Register the rate
                    $this->add_rate( $rates );
                }      

                /**
                 * Set Z Point rates hungary
                 *
                 */                
                private function set_z_point_rates( $zasilkovna_prices, $weight, $country ){
     
                    $cost = false;

                    if( $weight < 5 ){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-'.$country.'-5kg' );
                    }elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-'.$country.'-10kg' );
                    }elseif($weight > 10.01 && $weight < 20){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-'.$country.'-20kg' );
                    }elseif($weight > 20.01 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-'.$country.'-30kg' );
                    }elseif($weight > 30.01 && $weight < 40){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-'.$country.'-40kg' );
                    }elseif($weight > 40.01 && $weight < 50){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-'.$country.'-50kg' );
                    }elseif($weight > 50.01 && $weight < 60){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-'.$country.'-60kg' );
                         }elseif($weight > 60.01 && $weight < 100){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-'.$country.'-nad60kg' );
                    }else{ 
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'zasilkovna-'.$country.'-5kg' );
                    }

                    if( !empty( $zasilkovna_prices['zasilkovna-'.$country.'-free'] ) ){

                        $free = $zasilkovna_prices['zasilkovna-'.$country.'-free'];

                        //WooCommerce Multilingual compatibility
                        $filtered_free = apply_filters( 'wcml_raw_price_amount', $free );
                        if( !empty( $filtered_free ) ){ $free = $filtered_free; }

                        //WooCommerce currency Switcher compatibility
                        $filtered_free = apply_filters('woocs_exchange_value', $free);
                        if( !empty( $filtered_free ) ){ $free = $filtered_free; }

                        $subtotal = WC()->cart->get_subtotal();

                        if( !empty( $subtotal ) && $subtotal > $free ){
                            $cost = 0;
                        }

                    }

                    if( $cost === false ){ return false; }  
                    $rates = array(
                        'id'       => $this->id.'>z-points',
                        'label'    => $this->zasilkovna_title,
                        'cost'     => $cost,
                        'calc_tax' => 'per_order'
                    );
                    // Register the rate
                    $this->add_rate( $rates );
                }      
  
  
                /**
                 * Set Česká Pošta - balík na poštu rates CZ
                 *
                 */                
                private function set_ceska_posta_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight){
                    
                    $cost = false;

                    $currency = get_option('woocommerce_currency');
                    if(empty($zasilkovna_services['service-active-13'])){ return false; }
    
                    if($weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-2kg' );
                    }elseif($weight > 1.99 && $weight < 3){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-3kg' );
                    }elseif($weight > 2.99 && $weight < 4){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-4kg' );
                    }elseif($weight > 3.99 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-5kg' );
                    }elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-10kg' );
                    }elseif($weight > 9.99 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-30kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }  
                    $rates = array(
						'id'       => $this->id.'>ceska-posta-cz',
						'label'    => $zasilkovna_services['service-label-13'],
						'cost'     => $cost,
                        'calc_tax' => 'per_order'
					);
                    $this->add_rate( $rates );
                }
                
                /**
                 * Set Česká Pošta - balik do ruky rates CZ
                 *
                 */                
                private function set_ceska_posta_ruka_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight){
                    
                    $cost = false;

                    $currency = get_option('woocommerce_currency');
                    if(empty($zasilkovna_services['service-active-14'])){ return false; }
    
                    if($weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-ruka-2kg' );
                    }elseif($weight > 1.99 && $weight < 3){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-ruka-3kg' );
                    }elseif($weight > 2.99 && $weight < 4){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-ruka-4kg' );
                    }elseif($weight > 3.99 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-ruka-5kg' );
                    }elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-ruka-10kg' );
                    }elseif($weight > 9.99 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-ruka-30kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }  
                    $rates = array(
						'id'       => $this->id.'>ceska-posta-cz-ruka',
						'label'    => $zasilkovna_services['service-label-14'],
						'cost'     => $cost,
                        'calc_tax' => 'per_order'
					);
                    $this->add_rate( $rates );
                }
                
                  /**
                 * Set Česká Pošta - balik do ruky rates CZ
                 *
                 */                
                private function set_ceska_posta_balikovna_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight){
                    
                    $cost = false;

                    $currency = get_option('woocommerce_currency');
                    if(empty($zasilkovna_services['service-active-15'])){ return false; }
    
                    if($weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-balikovna-2kg' );
                    }elseif($weight > 1.99 && $weight < 3){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-balikovna-3kg' );
                    }elseif($weight > 2.99 && $weight < 4){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-balikovna-4kg' );
                    }elseif($weight > 3.99 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-balikovna-5kg' );
                    }elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-balikovna-10kg' );
                    }elseif($weight > 9.99 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'ceska-posta-cz-balikovna-30kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }  
                    $rates = array(
						'id'       => $this->id.'>ceska-posta-cz-balikovna',
						'label'    => $zasilkovna_services['service-label-15'],
						'cost'     => $cost,
                        'calc_tax' => 'per_order'
					);
                    $this->add_rate( $rates );
                }
                
                
                
                /**
                 * Set In Time rates CZ
                 *
                 */                
                private function set_in_time_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if( empty( $zasilkovna_services['service-active-153'] ) ){ return false; }
        
                    if( $weight < 30 ){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'in-time-30kg' );
                    }elseif($weight > 29.99 && $weight < 50){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'in-time-50kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }  
                    $rates = array(
						'id'       => $this->id.'>in-time-cz',
						'label'    => $zasilkovna_services['service-label-153'],
						'cost'     => $cost,
                        'calc_tax' => 'per_order'
					);
                    $this->add_rate( $rates );
                }

                /**
                 * Set DPD CZ
                 *
                 */                
                private function set_dpd_cz_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-633'])){ return false; }
        
                    if($weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-cz-5kg' );
                    }elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-cz-10kg' );
                    }elseif($weight > 9.99 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-cz-30kg' );
                    }elseif($weight > 29.99 && $weight < 50){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-cz-50kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }  
                    $rates = array(
                        'id'       => $this->id.'>dpd-cz',
                        'label'    => $zasilkovna_services['service-label-633'],
                        'cost'     => $cost,
                        'calc_tax' => 'per_order'
                    );
                    $this->add_rate( $rates );
                }

                /**
                 * Set Express Brno
                 *
                 */                
                private function set_express_brno_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-136'])){ return false; }
        
                    if($weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'express-brno-5kg' );
                    }elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'express-brno-10kg' );
                    }elseif($weight > 9.99 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'express-brno-30kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
                        'id'       => $this->id.'>express-brno',
                        'label'    => $zasilkovna_services['service-label-136'],
                        'cost'     => $cost,
                        'calc_tax' => 'per_order'
                    );
                    $this->add_rate( $rates );
                }

                /**
                 * Set Express Praha
                 *
                 */                
                private function set_express_praha_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-257'])){ return false; }
        
                    if($weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'express-praha-5kg' );
                    }elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'express-praha-10kg' );
                    }elseif($weight > 9.99 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'express-praha-30kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
                        'id'       => $this->id.'>express-praha',
                        'label'    => $zasilkovna_services['service-label-257'],
                        'cost'     => $cost,
                        'calc_tax' => 'per_order'
                    );
                    $this->add_rate( $rates );
                }

                /**
                 * Set Express Ostrava
                 *
                 */                
                private function set_express_ostrava_rates_cz($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-134'])){ return false; }
        
                    if($weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'express-ostrava-5kg' );
                    }elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'express-ostrava-10kg' );
                    }elseif($weight > 9.99 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'express-ostrava-30kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
                        'id'       => $this->id.'>express-ostrava',
                        'label'    => $zasilkovna_services['service-label-134'],
                        'cost'     => $cost,
                        'calc_tax' => 'per_order'
                    );
                    $this->add_rate( $rates );
                }


                           
                /**
                 * Set Austria rates AT
                 *
                 */                
                private function set_austria_rates_at($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-80'])){ return false; }
        
                    if($weight < 1){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'austria-at-1kg' );
                    }
                    elseif($weight > 0.99 && $weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'austria-at-2kg' );
                    }
                    elseif($weight > 1.99 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'austria-at-5kg' );
                    }
                    elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'austria-at-10kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
						'id'       => $this->id.'>austria-at',
						'label'    => $zasilkovna_services['service-label-80'],
						'cost'     => $cost,
                        'calc_tax' => 'per_order'
					);
                    $this->add_rate( $rates );
                }
  
                /**
                 * Set Germany rates CZ
                 *
                 */                
                private function set_germany_rates_de($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-111'])){ return false; }
    
                    if($weight < 1){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'germany-de-1kg' );
                    }
                    elseif($weight > 0.99 && $weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'germany-de-2kg' );
                    }
                    elseif($weight > 1.99 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'germany-de-5kg' );
                    }
                    elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'germany-de-10kg' );
                    }
                    elseif($weight > 9.99 && $weight < 20){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'germany-de-20kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
						'id'       => $this->id.'>germany-de',
						'label'    => $zasilkovna_services['service-label-111'],
						'cost'     => $cost,
                        'calc_tax' => 'per_order'
					);
                    $this->add_rate( $rates );  
                }
  
                /**
                 * Set Maďarská Pošta
                 *
                 */                
                private function set_hungary_rates_hu($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-763'])){ return false; }
    
                    if($weight < 1){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'hungary-hu-1kg' );
                    }
                    elseif($weight > 0.99 && $weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'hungary-hu-2kg' );
                    }
                    elseif($weight > 1.99 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'hungary-hu-5kg' );
                    }
                    elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'hungary-hu-10kg' );
                    }
                    elseif($weight > 9.99 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'hungary-hu-30kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
						'id'       => $this->id.'>hungary-hu',
						'label'    => $zasilkovna_services['service-label-763'],
						'cost'     => $cost,
                        'calc_tax' => 'per_order'
					);
                    $this->add_rate( $rates );
                }

                /**
                 * Set DPD Maďarsko
                 *
                 */                
                private function set_dpd_hu_rates_hu($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-805'])){ return false; }
    
                    if($weight < 1){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-hu-1kg' );
                    }
                    elseif($weight > 0.99 && $weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-hu-2kg' );
                    }
                    elseif($weight > 1.99 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-hu-5kg' );
                    }
                    elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-hu-10kg' );
                    }
                    elseif($weight > 9.99 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-hu-30kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
                        'id'       => $this->id.'>dpd-hu',
                        'label'    => $zasilkovna_services['service-label-805'],
                        'cost'     => $cost,
                        'calc_tax' => 'per_order'
                    );
                    $this->add_rate( $rates );
                }

                /**
                 * Set Transoflex Maďarsko
                 *
                 */                
                private function set_transoflex_hu_rates_hu($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-151'])){ return false; }
    
                    if($weight < 1){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'transoflex-hu-1kg' );
                    }
                    elseif($weight > 0.99 && $weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'transoflex-hu-2kg' );
                    }
                    elseif($weight > 1.99 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'transoflex-hu-5kg' );
                    }
                    elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'transoflex-hu-10kg' );
                    }
                    elseif($weight > 9.99 && $weight < 15){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'transoflex-hu-15kg' );
                    }
                    elseif($weight > 14.99 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'transoflex-hu-30kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
                        'id'       => $this->id.'>transoflex-hu',
                        'label'    => $zasilkovna_services['service-label-151'],
                        'cost'     => $cost,
                        'calc_tax' => 'per_order'
                    );
                    $this->add_rate( $rates );
                }


  
                /**
                 * Set Poland rates CZ
                 *
                 */                
                private function set_poland_rates_pl($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-272'])){ return false; }
    
                    if($weight < 1){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'poland-pl-1kg' );
                    }
                    elseif($weight > 0.99 && $weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'poland-pl-2kg' );
                    }
                    elseif($weight > 1.00 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'poland-pl-5kg' );
                    }
                    elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'poland-pl-10kg' );
                    }
                    elseif($weight > 9.99 && $weight < 15){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'poland-pl-15kg' );
                    }
                    elseif($weight > 14.99 && $weight < 20){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'poland-pl-20kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
						'id'       => $this->id.'>poland-pl',
						'label'    => $zasilkovna_services['service-label-272'],
						'cost'     => $cost,
                        'calc_tax' => 'per_order'
					);
                    $this->add_rate( $rates );
                }

                /**
                 * Set Poland DPD rates CZ
                 *
                 */                
                private function set_dpd_pl_rates( $zasilkovna_prices, $zasilkovna_services, $weight ){
    
                    $cost = false;

                    if( empty( $zasilkovna_services['service-active-1406'] ) ){ return false; }
    
                    if($weight < 1){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-pl-1kg' );
                    }
                    elseif($weight > 0.99 && $weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-pl-2kg' );
                    }
                    elseif($weight > 1.00 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-pl-5kg' );
                    }
                    elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-pl-10kg' );
                    }
                    elseif($weight > 10.01 && $weight < 20){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-pl-30kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
                        'id'       => $this->id.'>dpd-pl',
                        'label'    => $zasilkovna_services['service-label-1406'],
                        'cost'     => $cost,
                        'calc_tax' => 'per_order'
                    );
                    $this->add_rate( $rates );
                }

                
  
                /**
                 * Set Slovensko na adresu rates CZ
                 *
                 */                
                private function set_slovensko_na_adresu_rates_sk($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-131'])){ return false; }
    
                    if($weight < 1){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-doruceni-1kg' );
                    }
                    elseif($weight > 0.99 && $weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-doruceni-2kg' );
                    }
                    elseif($weight > 1.99 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-doruceni-5kg' );
                    }
                    elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-doruceni-10kg' );
                    }
                    elseif($weight > 9.99 && $weight < 20){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-doruceni-20kg' );
                    }
                    elseif($weight > 19.99 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-doruceni-30kg' );
                    }
                    elseif($weight > 29.99 && $weight < 40){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-doruceni-40kg' );
                    }
                    elseif($weight > 39.99 && $weight < 50){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-doruceni-50kg' );
                    }else{ return false; }
    
                    //if($currency == 'CZK'){ $cost = round($cost * $kurz,2);}
                    if( $cost === false ){ return false; }
                    $rates = array(
						'id'       => $this->id.'>slovensko-na-adresu',
						'label'    => $zasilkovna_services['service-label-131'],
						'cost'     => $cost,
                        'calc_tax' => 'per_order'
					);
                    $this->add_rate( $rates );
                }
   

                /**
                 * Set Slovensko pošta rates CZ
                 *
                 */                
                private function set_slovensko_posta_rates_sk($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-16'])){ return false; }
    
                    if($weight < 1){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovenska-posta-1kg' );
                    }
                    elseif($weight > 0.99 && $weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovenska-posta-2kg' );
                    }
                    elseif($weight > 1.99 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovenska-posta-5kg' );
                    }
                    elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovenska-posta-10kg' );
                    }
                    elseif($weight > 9.99 && $weight < 35){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovenska-posta-15kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
						'id'       => $this->id.'>slovenska-posta',
						'label'    => $zasilkovna_services['service-label-16'],
						'cost'     => $cost,
                        'calc_tax' => 'per_order'
					);
                    $this->add_rate( $rates );
                }  

                /**
                 * Set Express Bratislava rates CZ
                 *
                 */                
                private function set_express_bratislava_rates_sk($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-132'])){ return false; }
    
                    if($weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'express-bratislava-5kg' );
                    }
                    elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'express-bratislava-10kg' );
                    }
                    elseif($weight > 9.99 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'express-bratislava-30kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
                        'id'       => $this->id.'>express-bratislava',
                        'label'    => $zasilkovna_services['service-label-132'],
                        'cost'     => $cost,
                        'calc_tax' => 'per_order'
                    );
                    $this->add_rate( $rates );
                }  

                /**
                 * Set Slovensko kurýr rates
                 *
                 */                
                private function set_slovensko_kuryr_rates_sk($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-149'])){ return false; }
    
                    if($weight < 1){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-kuryr-1kg' );
                    }
                    elseif($weight > 0.99 && $weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-kuryr-2kg' );
                    }
                    elseif($weight > 1.99 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-kuryr-5kg' );
                    }
                    elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-kuryr-10kg' );
                    }
                    elseif($weight > 9.99 && $weight < 20){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-kuryr-20kg' );
                    }
                    elseif($weight > 19.99 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-kuryr-30kg' );
                    }
                    elseif($weight > 29.99 && $weight < 40){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-kuryr-40kg' );
                    }
                    elseif($weight > 39.99 && $weight < 50){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'slovensko-kuryr-50kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
                        'id'       => $this->id.'>slovensko-kuryr',
                        'label'    => $zasilkovna_services['service-label-149'],
                        'cost'     => $cost,
                        'calc_tax' => 'per_order'
                    );
                    $this->add_rate( $rates );
                }  



                /**
                 * Set Gargus Rumunsko rates
                 *
                 */                
                private function set_fan_ro_rates( $zasilkovna_prices, $zasilkovna_services, $weight ){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-762'])){ return false; }
    
                    if($weight < 1){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'fan-ro-1kg' );
                    }
                    elseif($weight > 0.99 && $weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'fan-ro-2kg' );
                    }
                    elseif($weight > 1.99 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'fan-ro-5kg' );
                    }
                    elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'fan-ro-10kg' );
                    }
                    elseif($weight > 9.99 && $weight < 15){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'fan-ro-15kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
                        'id'       => $this->id.'>fan-ro',
                        'label'    => $zasilkovna_services['service-label-762'],
                        'cost'     => $cost,
                        'calc_tax' => 'per_order'
                    );
                    $this->add_rate( $rates );
                }  


                /**
                 * Set DPD Rumunsko rates
                 *
                 */                
                private function set_dpd_ro_rates($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-836'])){ return false; }
    
                    if($weight < 1){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-ro-1kg' );
                    }
                    elseif($weight > 0.99 && $weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-ro-2kg' );
                    }
                    elseif($weight > 1.99 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-ro-5kg' );
                    }
                    elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-ro-10kg' );
                    }
                    elseif($weight > 9.99 && $weight < 15){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-ro-15kg' );
                        }
                    elseif($weight > 14.99 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-ro-30kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
                        'id'       => $this->id.'>dpd-ro',
                        'label'    => $zasilkovna_services['service-label-836'],
                        'cost'     => $cost,
                        'calc_tax' => 'per_order'
                    );
                    $this->add_rate( $rates );
                }  

                /**
                 * Set DPD Bulharsko rates
                 *
                 */                
                private function set_dpd_bl_rates($zasilkovna_prices,$zasilkovna_services,$weight){
    
                    $cost = false;

                    if(empty($zasilkovna_services['service-active-1234'])){ return false; }
    
                    if($weight < 1){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-bl-1kg' );
                    }
                    elseif($weight > 0.99 && $weight < 2){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-bl-2kg' );
                    }
                    elseif($weight > 1.99 && $weight < 5){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-bl-5kg' );
                    }
                    elseif($weight > 4.99 && $weight < 10){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-bl-10kg' );
                    }
                    elseif($weight > 9.99 && $weight < 15){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-bl-15kg' );
                    }
                    elseif($weight > 14.99 && $weight < 30){
                        $cost = Zasilkovna_Helper::isset_shipping( $zasilkovna_prices, 'dpd-bl-30kg' );
                    }else{ return false; }
    
                    if( $cost === false ){ return false; }
                    $rates = array(
                        'id'       => $this->id.'>dpd-bl',
                        'label'    => $zasilkovna_services['service-label-1234'],
                        'cost'     => $cost,
                        'calc_tax' => 'per_order'
                    );
                    $this->add_rate( $rates );
                }  
  


        
			}//End class
		}
	
} 
add_action('plugins_loaded', 'woocommerce_zasilkovna_shipping_init');
 
	function add_woo_zasilkovna_shipping_method( $methods ) {
		$methods['zasilkovna'] = 'WC_Zasilkovna_Shipping_Method';
		return $methods;
	}
    add_filter( 'woocommerce_shipping_methods', 'add_woo_zasilkovna_shipping_method' );


  

?>