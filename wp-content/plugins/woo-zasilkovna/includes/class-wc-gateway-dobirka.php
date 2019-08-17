<?php


if ( ! function_exists( 'woocommerce_gateway_dobirka_plus_init' ) ) { 
	
	function woocommerce_gateway_dobirka_plus_init(){

		if ( !class_exists( 'WC_Payment_Gateway' ) ) 
      		return;
 
		/**
		 * Platba při doručení 
		 *
		 * Umožňuje využít platbu při doručení - dobírku
		 *
		 * @author 		Vladislav Musílek
		 */
		if ( ! class_exists( 'WC_Gateway_Dobirka_Plus' ) ) { 
			class WC_Gateway_Dobirka_Plus extends WC_Payment_Gateway{

  				/**
   			 	 * Plugin slug
  				 *
  				 */        
  				private $plugin_slug = 'woo-doprava';
    			
    			/**
    			 * Constructor for the gateway.
    			 *
    			 * @access public
    			 * @return void
    			 */
				public function __construct() {
  
    				$this->id           = 'dobirka';
    				$this->item_title   = __( 'Dobirka', $this->plugin_slug );
					$this->method_title = __( 'Dobirka', $this->plugin_slug );
					$this->has_fields   = false;
     
					//Extra price
    				$this->current_gateway_extra_charges = '';

					// Get settings
					$this->title              = $this->get_option( 'title' );
					$this->description        = $this->get_option( 'description' );
					$this->instructions       = $this->get_option( 'instructions' );
					$this->enable_for_methods = $this->get_option( 'enable_for_methods', array() );
    				$this->enable_dobirka_countries = $this->get_option( 'enable_dobirka_countries', array() );
    				$this->order_status 	  = $this->get_option( 'order_status', array() );
    				$this->taxable            = $this->get_option( 'taxable' );
    				$this->show_cod           = $this->get_option( 'show_cod' );

    
    				// Load the settings
					$this->init_form_fields();
					$this->init_settings();
	    
					add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
					add_action( 'woocommerce_thankyou_dobirka', array( $this, 'thankyou' ) );
    
    				// Customer Emails
					add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
    
				}


				/**
				 * Admin Panel Options
				 * - Options for bits like 'title' and availability on a country-by-country basis
				 *
				 * @access public
				 * @return void
				 */
				public function admin_options() {
					?>
					<h3><?php _e('Dobírka',$this->plugin_slug); ?></h3>
    				<p><?php _e('Umožňuje zákazníkům platit zboží při doručení (na dobírku).', $this->plugin_slug ); ?></p>
    				<table class="form-table">
    					<?php $this->generate_settings_html(); ?>
					</table> <?php
    			}


    			/**
    			 * Initialise Gateway Settings Form Fields
    			 *
    			 * @access public
    			 * @return void
    			 */
				public function init_form_fields() {
    	
    				$shipping_methods = array();

    				if ( is_admin() )
	    				foreach ( WC()->shipping->load_shipping_methods() as $method ) {
		    				$shipping_methods[ $method->id ] = $method->get_method_title();
	    				}
      				$countries = WC()->countries->get_allowed_countries();
    				$this->form_fields = array(
						'enabled' => array(
							'title'       => __( 'Povolit platbu na dobírku', $this->plugin_slug ),
							'label'       => __( 'Povolit platbu na dobírku', $this->plugin_slug ),
							'type'        => 'checkbox',
							'description' => '',
							'default'     => 'no'
						),
						'title' => array(
							'title'       => __( 'Titulek', $this->plugin_slug ),
							'type'        => 'text',
							'description' => __( 'Název platební metody, který uvidí zákazníci při objednávce.', $this->plugin_slug ),
							'default'     => __( 'Platba na dobírku', $this->plugin_slug ),
							'desc_tip'    => true,
						),
						'description'   => array(
							'title'       => __( 'Popis', $this->plugin_slug ),
							'type'        => 'textarea',
							'description' => __( 'Popis platební metody, který se zobrazí zákazníkovi na stránce.', $this->plugin_slug ),
							'default'     => __( 'Platba v hotovosti po doručení.', $this->plugin_slug ),
						),
						'instructions'  => array(
							'title'       => __( 'Instrukce', $this->plugin_slug ),
							'type'        => 'textarea',
							'description' => __( 'Instrukce, které se zobrazí na děkovné stránce.', $this->plugin_slug ),
							'default'     => __( 'Platbu proveďte až po doručení zboží.', $this->plugin_slug )
						),
						'enable_for_methods' => array(
							'title' 	  => __( 'Povolit způsob dopravy', $this->plugin_slug ),
							'type' 		  => 'multiselect',
							'class'		  => 'chosen_select',
							'css'	      => 'width: 450px;',
							'default' 	  => '',
							'description' => __( 'Pokud je platba na dobírku aktivní, zde můžete definovat způsoby dopravy. Pro povolení všech způsobů dopravy, zanechte pole prázdné.', $this->plugin_slug ),
							'options'     => $shipping_methods,
							'desc_tip'    => true,
						),
      					'enable_dobirka_countries' => array(
							'title' 		  => __( 'Povolit pro země', $this->plugin_slug  ),
							'type' 			  => 'multiselect',
							'class'			  => 'chosen_select',
							'css'			    => 'width: 450px;',
							'default' 		=> '',
							'description' => __( 'Vyberte, pro které země bude dobírka dostupná.', $this->plugin_slug  ),
							'options'		  => $countries,
							'desc_tip'    => true,
		  				),
		  				'order_status' => array(
							'title' 		  => __( 'Stav objednávky', $this->plugin_slug  ),
							'type' 			  => 'select',
							'class'			  => 'chosen_select',
							'css'			    => 'width: 450px;',
							'default' 		=> '',
							'description' => __( 'Stav objednávky po zaplacení.', $this->plugin_slug  ),
							'options'		  => array(
								'on-hold' => __( 'Čeká na zaplacení', $this->plugin_slug  ),
								'processing' => __( 'Zpracovává se', $this->plugin_slug  ),
								'completed' => __( 'Dokončeno.', $this->plugin_slug  )
							),
							'desc_tip'    => true,
		  				),
      					'taxable' => array(
							'title'       => __( 'Započítat daň?', $this->plugin_slug ),
							'label'       => __( 'Započítat daň?', $this->plugin_slug ),
							'type'        => 'checkbox',
							'description' => __( 'Kalkulovat pro poplatek za dobírku daň?', $this->plugin_slug ),
							'default'     => 'yes'
						),
						'show_cod' => array(
							'title'       => __( 'Zobrazit dobírku, pokud je cena zásilky 0', $this->plugin_slug ),
							'label'       => __( 'Zobrazit dobírku, pokud je cena zásilky 0', $this->plugin_slug ),
							'type'        => 'checkbox',
							'description' => __( 'Zobrazit dobírku, pokud je cena zásilky 0', $this->plugin_slug ),
							'default'     => 'yes'
						)
 	   				);
    			}


				/**
				 * Check If The Gateway Is Available For Use
				 *
				 * @access public
				 * @return bool
				 */
				public function is_available() {
		                              
    				$enable_for_country  = $this->is_available_for_country();
    				if( $enable_for_country === false ){ return false; }

    				if( $this->is_virtual_product_in_cart() == true ){ return false; }
        
    				if ( ! empty( $this->enable_for_methods ) ) {

			   			// Only apply if all packages are being shipped via local pickup
			   			$chosen_shipping_methods_session = WC()->session->get( 'chosen_shipping_methods' );

			   				if ( isset( $chosen_shipping_methods_session ) ) {
      	   						$chosen_shipping_methods = array_unique( $chosen_shipping_methods_session );
			   				} else {
      	   						$chosen_shipping_methods = array();
         					}
      
							//Set default check method false
							$check_method = false;

							if ( is_page( wc_get_page_id( 'checkout' ) ) && ! empty( $wp->query_vars['order-pay'] ) ) {

      							$order_id = absint( $wp->query_vars['order-pay'] );
								$order    = new WC_Order( $order_id );

								if ( $order->shipping_method ){ $check_method = $order->shipping_method; }

							} elseif ( empty( $chosen_shipping_methods ) || sizeof( $chosen_shipping_methods ) > 1 ) {
			
      							$check_method = false;
			
      						} elseif ( sizeof( $chosen_shipping_methods ) == 1 ) {
			
      							$check_method = $chosen_shipping_methods[0];
			
      						}

      						//return false if not exist selected shippping method
							if ( ! $check_method ){
								return false;
      						}  

							$found = false;
      						//find method in enabled methods
							foreach ( $this->enable_for_methods as $method_id ) {
								if ( strpos( $check_method, $method_id ) === 0 ) {
								$found = true;
								break;
								}
							}
      						//return false if method isnt in enable methods
							if ( ! $found ){ return false; }
        
      
      						/**
      						 * Available dobirka for Woo Doprava plugin
      						 *
       						 */  

      						$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
      
      						if(WC()->session->chosen_shipping_methods[0] != 'free_shipping'){
      
      							$doprava_name = explode('>',WC()->session->chosen_shipping_methods[0]);
          						if(count($doprava_name)>1){
              						$d_name = $doprava_name[1];
          						}else{ 
              						$d_name = WC()->session->chosen_shipping_methods[0];
          						}
          						if(!empty($doprava_name[1]) && trim($doprava_name[1])!=''){         
        
									$instance_id = WC()->session->get( 'instance_woo' );
									$shipping_methods = WC()->shipping->shipping_methods;

            						if( !empty( $shipping_methods ) ){
          								foreach($shipping_methods as $keys => $item){ 
      				  						if( is_int( $keys ) ){
      					  						if($item->id == 'doprava'){ $instance_id = $keys; }
                    							WC()->session->set( 'instance_woo', $instance_id);
      				    					}
      			  						}
            						}

  									$doprava = get_option('woocommerce_doprava_'.$instance_id.'_settings');
        							if(!empty($doprava)){
            							foreach($doprava['doprava'] as $item){
              								if(sanitize_title($item['doprava_name'])==trim($d_name)){
                								if(empty($item['doprava_dobirka_active'])){
	                   								return false;
                								}
                								if($item['doprava_dobirka_active']!='yes'){
	                   								return false;
                								}
              								}
            							}
            						}
          						}
      						}    
						}
    
    
    				/**
    				 *  User role shipping fix
    				 *  Setting for user role shipping pluign   
    				 */
    				if(WC()->session->chosen_shipping_methods[0] == 'user-role>doruceni-zdarma-pro-partnery'){
      					return false;
    				}elseif(WC()->session->chosen_shipping_methods[0] == 'user-role>doruceni-pro-partnery'){
      					return false;
    				}
    
    				//Return available
					return parent::is_available();
				}
  
  
				/**
  				 * Check is shipping method available for selected country
  				 *
  				 * return true or false
  				 */            
  				public function is_available_for_country() {
     				
  					if( !empty( WC()->customer ) ){

     				$country = $this->get_customer_country();              
       					if ( ! empty( $this->enable_dobirka_countries ) ) {  
        					if( !in_array( $country, $this->enable_dobirka_countries ) ){ 
	          					return false; 
        					}else{
          						return true;
        					}    
       					}else{
          					return true;
       					} 

       				}
  				
  				}   

  				/**
  				 * Get customer country
  				 *
  				 * return $country
  				 */ 
  				private function get_customer_country(){

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

  				/**
  				 * Check if is virtual product in cart
  				 *
  				 * return true or false
  				 *
  				 */            
  				public function is_virtual_product_in_cart() {
   
    				$has_virtual = true;
    				$cart_data = $this->get_cart_content();
    				if( !empty( $cart_data ) ){
    					foreach($cart_data as $item){
		    				$product = wc_get_product( $item['variation_id'] ? $item['variation_id'] : $item['product_id'] );
          					if ( !$product->is_virtual() ) {
				    			$has_virtual = false;
	        				} 

    					} 
    				}
	  				return apply_filters( 'woo_doprava_is_virtual_product_in_cart', $has_virtual, $this );
  				}

  				/**
  				 * Získáme obsah košíku
  				 *
  				 *
  				 */            
  				private function get_cart_content() {
   
    				if( !empty( WC()->session->cart->cart_contents ) ){
        				$cart_data = WC()->session->cart->cart_contents;
    				}else{
        				$cart_data = WC()->session->cart;
    				}
	    
    				return $cart_data;
   				}


    			/**
    			 * Process the payment and return the result
    			 *
    			 * @access public
    			 * @param int $order_id
    			 * @return array
    			 */
	 			public function process_payment ($order_id) {
		
					$order = new WC_Order( $order_id );

					// Mark as on-hold (we're awaiting the cheque)
					if ( ! empty( $this->order_status ) ) {
						$order->update_status( $this->order_status, __( 'Platba dobírkou.', $this->plugin_slug ) );
					}else{
						$order->update_status( 'on-hold', __( 'Platba dobírkou.', $this->plugin_slug ) );
					}

					// Reduce stock levels
					$order->reduce_order_stock();

					// Remove cart
					WC()->cart->empty_cart();
    
    				// Add order note
    				$order->add_order_note( __( 'Zákazník vybral platbu na dobírku', $this->plugin_slug ) );

					// Return thankyou redirect
					return array(
						'result' 	=> 'success',
						'redirect'	=> $this->get_return_url( $order )
					);
				}


    			/**
    			 * Output for the order received page.
    			 *
    			 * @access public
    			 * @return void
    			 */
				public function thankyou() {
					echo $this->instructions != '' ? wpautop( $this->instructions ) : '';
				}             
  
  				/**
				 * Add instructions to the WC emails.
				 *
				 * @since 1.1.9
				 */
				public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {

					if ( ! $sent_to_admin && 'dobirka' === $order->payment_method  ) {
						if ( $this->instructions ) {
							echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
						}
					}

				}

 
  				/**
  				 * Get current gateway
  				 *
  				 * @since 1.1.9  
  				 */        
 	 			public function get_current_gateway(){

					$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
					$current_gateway = null;
		
					if ( ! empty( $available_gateways ) ) {
		   				//Get Chosen Method
		   				$current_gateway = $this->get_selected_shipping_method( $available_gateways );
					}
					if ( ! is_null( $current_gateway ) )
						return $current_gateway;
					else 
						return false;
					}
  
  				/**
  				 * Get selected shipping method
  				 *
  				 * @since 1.2.0
  				 */
  				private function get_selected_shipping_method( $available_gateways ){
  
    				$default_gateway = get_option( 'woocommerce_default_gateway' );
  
    				if ( isset( WC()->session->chosen_payment_method ) && isset( $available_gateways[ WC()->session->chosen_payment_method ] ) ) {
						$current_gateway = $available_gateways[ WC()->session->chosen_payment_method ];
					} elseif ( isset( $available_gateways[ $default_gateway ] ) ) {
						$current_gateway = $available_gateways[ $default_gateway ];
					} else {
						$current_gateway = current( $available_gateways );
					}
  
     				return $current_gateway;
  				} 
              
  				/**
  				 * Get if cart has fee
  				 *
  				 * @since 1.1.9  
  				 */        
				public function cart_has_fee( &$cart , $item_title , $amount ) {
					
					$fees = $cart->get_fees();
					$item_id = sanitize_title($item_title);
					$amount = (float) esc_attr( $amount );
					foreach ( $fees as $fee )
						if ( $fee->amount == $amount && $fee->id == $item_id )
					
						return true;
					
					return false;
				}

   
  


		}//End class

	}//End class exist


}
  
add_action('plugins_loaded', 'woocommerce_gateway_dobirka_plus_init');
  
   /**
     *
     *  Add the Gateway to WooCommerce
     *       
     */
                 
  function woocommerce_add_gateway_dobirka_plus( $methods ) {
        $methods[] = 'WC_Gateway_Dobirka_Plus';
        return $methods;
    }
    //Woocommerce payment gateways filter
    add_filter('woocommerce_payment_gateways', 'woocommerce_add_gateway_dobirka_plus' );
    

}//End function exist

