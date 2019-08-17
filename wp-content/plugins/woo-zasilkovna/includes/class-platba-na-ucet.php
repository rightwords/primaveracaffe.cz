<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	if ( ! function_exists( 'woocommerce_gateway_pnu_init' ) ) { 
		
		add_action('plugins_loaded', 'woocommerce_gateway_pnu_init', 0);

		function woocommerce_gateway_pnu_init(){

  			if(!class_exists('WC_Payment_Gateway')) return;


			class WC_Gateway_PNU extends WC_Payment_Gateway {

    			/**
    			 * Plugin slug
    			 *
    			 */        
    			private $plugin_slug = 'woocommerce-zasilkovna';

    			/**
    			 * Constructor for the gateway.
    			 */
    			public function __construct() {
   
					// Setup general properties
                	$this->setup_properties();

    				// Load the settings.
					$this->init_form_fields();
					$this->init_settings();

					// Define user set variables
					$this->title                = $this->get_option( 'title' );
					$this->description          = $this->get_option( 'description' );
					$this->instructions         = $this->get_option( 'instructions', $this->description );
    				$this->enable_for_methods   = $this->get_option( 'enable_for_methods', array() );
    				$this->enable_pnu_countries = $this->get_option( 'enable_pnu_countries', array() );
    				$this->order_status 	    = $this->get_option( 'order_status', array() );

					// pnu account fields shown on the thanks page and in emails
					$this->account_details = get_option( 'woocommerce_pnu_accounts',
						array(
							array(
								'account_name'   => $this->get_option( 'account_name' ),
								'account_number' => $this->get_option( 'account_number' ),
								'sort_code'      => $this->get_option( 'sort_code' ),
								'bank_name'      => $this->get_option( 'bank_name' ),
								'iban'           => $this->get_option( 'iban' ),
								'bic'            => $this->get_option( 'bic' )
							)
						)
					);


					// Actions
					add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
					add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'save_account_details' ) );
    				add_action( 'woocommerce_thankyou_pnu', array( $this, 'thankyou_page' ) );

    				// Customer Emails
    				add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
				}

				/**
            	 * Setup general properties for the gateway.
            	 */
            	protected function setup_properties() {

                	$this->id                 = 'pnu';
					$this->icon               = apply_filters('woocommerce_pnu_icon', '');
					$this->has_fields         = false;
                	$this->method_title       = __( 'Platba na účet', $this->plugin_slug );
                	$this->method_description = __( 'Platby pomocí bankovního účtu.', $this->plugin_slug );
            	
            	}

    			/**
    			 * Initialise Gateway Settings Form Fields
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
								'title'   => __( 'Povolit/Zakázat', $this->plugin_slug ),
								'type'    => 'checkbox',
								'label'   => __( 'Enable Bank Transfer', $this->plugin_slug ),
								'default' => 'yes'
							),
							'title' => array(
								'title'       => __( 'Titulek', $this->plugin_slug ),
								'type'        => 'text',
								'description' => __( 'Titulek, který uvidí zákazník na pokladně.', $this->plugin_slug ),
								'desc_tip'    => true,
							),
							'description' => array(
								'title'       => __( 'Popis', $this->plugin_slug ),
								'type'        => 'textarea',
								'description' => __( 'Popis platební metody zobrazené na pokladně.', $this->plugin_slug ),
								'default'     => __( 'Platbu proveďte převodem na bankovní účet. Použijte ID objednávky pro identifikaci platby. Vaše objednávka bude odeslána po provedení platby.', $this->plugin_slug ),
								'desc_tip'    => true,
							),
							'instructions' => array(
								'title'       => __( 'Instrukce', $this->plugin_slug ),
								'type'        => 'textarea',
								'description' => __( 'Instrukce budou zobrazeny na děkovné stránce a pokaldně.', $this->plugin_slug ),
								'default'     => '',
								'desc_tip'    => true,
							),
							'enable_for_methods' => array(
								'title' 		    => __( 'Povolit způsob dopravy', $this->plugin_slug ),
								'type' 			    => 'multiselect',
								'class'			    => 'chosen_select',
								'css'			      => 'width: 450px;',
								'default' 		  => '',
								'description' 	=> __( 'Pokud je platba na dobírku aktivní, zde můžete definovat způsoby dopravy. Pro povolení všech způsobů dopravy, zanechte pole prázdné.', $this->plugin_slug),
								'options'		    => $shipping_methods,
								'desc_tip'      => true,
							),
      						'enable_pnu_countries' => array(
								'title' 		    => __( 'Povolit pro země', $this->plugin_slug  ),
								'type' 			    => 'multiselect',
								'class'			    => 'chosen_select',
								'css'			      => 'width: 450px;',
								'default' 		  => '',
								'description' 	=> __( 'Vyberte, pro které země bude převod dostupný.', $this->plugin_slug  ),
								'options'		    => $countries,
								'desc_tip'      => true,
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
      						'account_details' => array(
								'type'          => 'account_details'
							),
    					);
				}

    			/**
    			 * generate_account_details_html function.
    			 */
    			public function generate_account_details_html() {
    				ob_start();
	    			?>
	    				<tr valign="top">
            				<th scope="row" class="titledesc"><?php _e( 'Detail účtu', $this->plugin_slug ); ?>:</th>
            				<td class="forminp" id="pnu_accounts">
			    				<table class="widefat wc_input_table sortable" cellspacing="0">
		    						<thead>
		    							<tr>
		    								<th class="sort">&nbsp;</th>
		    								<th><?php _e( 'Název účtu', $this->plugin_slug ); ?></th>
			          						<th><?php _e( 'Číslo účtu', $this->plugin_slug ); ?></th>
			          						<th><?php _e( 'Jméno banky', $this->plugin_slug ); ?></th>
			          						<th><?php _e( 'Sort Code', $this->plugin_slug ); ?></th>
			          						<th><?php _e( 'IBAN', $this->plugin_slug ); ?></th>
			          						<th><?php _e( 'BIC / Swift', $this->plugin_slug ); ?></th>
		    							</tr>
		    						</thead>
		    						<tfoot>
		    							<tr>
		    								<th colspan="7"><a href="#" class="add button"><?php _e( '+ Přidat účet', $this->plugin_slug ); ?></a> <a href="#" class="remove_rows button"><?php _e( 'Odstranit vybraný účet(účty)', $this->plugin_slug ); ?></a></th>
		    							</tr>
		    						</tfoot>
		    						<tbody class="accounts">
		            				<?php
		            					$i = -1;
		            					if ( $this->account_details ) {
		            						foreach ( $this->account_details as $account ) {
		                						$i++;

		                						echo '<tr class="account">
		                							<td class="sort"></td>
		                							<td><input type="text" value="' . esc_attr( $account['account_name'] ) . '" name="pnu_account_name[' . $i . ']" /></td>
		                							<td><input type="text" value="' . esc_attr( $account['account_number'] ) . '" name="pnu_account_number[' . $i . ']" /></td>
		                							<td><input type="text" value="' . esc_attr( $account['bank_name'] ) . '" name="pnu_bank_name[' . $i . ']" /></td>
		                							<td><input type="text" value="' . esc_attr( $account['sort_code'] ) . '" name="pnu_sort_code[' . $i . ']" /></td>
		                							<td><input type="text" value="' . esc_attr( $account['iban'] ) . '" name="pnu_iban[' . $i . ']" /></td>
		                							<td><input type="text" value="' . esc_attr( $account['bic'] ) . '" name="pnu_bic[' . $i . ']" /></td>
			                    				</tr>';
		            						}
		            					}
		            				?>
		        					</tbody>
		        				</table>
		       					<script type="text/javascript">
									jQuery(function() {
										jQuery('#pnu_accounts').on( 'click', 'a.add', function(){

											var size = jQuery('#pnu_accounts tbody .account').size();

											jQuery('<tr class="account">\
		                						<td class="sort"></td>\
		                						<td><input type="text" name="pnu_account_name[' + size + ']" /></td>\
		                						<td><input type="text" name="pnu_account_number[' + size + ']" /></td>\
		                						<td><input type="text" name="pnu_bank_name[' + size + ']" /></td>\
		                						<td><input type="text" name="pnu_sort_code[' + size + ']" /></td>\
		                						<td><input type="text" name="pnu_iban[' + size + ']" /></td>\
		                						<td><input type="text" name="pnu_bic[' + size + ']" /></td>\
			                    			</tr>').appendTo('#pnu_accounts table tbody');

											return false;
										});
									});
								</script>
            				</td>
	    				</tr>
        				<?php
        				return ob_get_clean();
				}

    			/**
    			 * Save account details table
    			 */
    			public function save_account_details() {
    				$accounts = array();

    				if ( isset( $_POST['pnu_account_name'] ) ) {

						$account_names   = array_map( 'wc_clean', $_POST['pnu_account_name'] );
						$account_numbers = array_map( 'wc_clean', $_POST['pnu_account_number'] );
						$bank_names      = array_map( 'wc_clean', $_POST['pnu_bank_name'] );
						$sort_codes      = array_map( 'wc_clean', $_POST['pnu_sort_code'] );
						$ibans           = array_map( 'wc_clean', $_POST['pnu_iban'] );
						$bics            = array_map( 'wc_clean', $_POST['pnu_bic'] );

						foreach ( $account_names as $i => $name ) {
							if ( ! isset( $account_names[ $i ] ) ) {
								continue;
							}

	    					$accounts[] = array(
	    						'account_name'   => $account_names[ $i ],
								'account_number' => $account_numbers[ $i ],
								'bank_name'      => $bank_names[ $i ],
								'sort_code'      => $sort_codes[ $i ],
								'iban'           => $ibans[ $i ],
								'bic'            => $bics[ $i ]
	    					);
	    				}
    				}

    				update_option( 'woocommerce_pnu_accounts', $accounts );
    			}
    
				/**
  				 * Check if is virtual product in cart
  				 *
  				 * return true or false
  				 */            
  				public function is_virtual_product_in_cart() {
    				$has_virtual = true;
    				if(!empty(WC()->session->cart->cart_contents)){
        				$cart_data = WC()->session->cart->cart_contents;
    				}else{
        				$cart_data = WC()->session->cart;
    				}
    				if( !empty( $cart_data ) ){
    					foreach( $cart_data as $item ){
		        
		    				$product = wc_get_product( $item['variation_id'] ? $item['variation_id'] : $item['product_id'] );
          					if ( !$product->is_virtual() ) {
				    			$has_virtual = false;
	        				} 
    					} 
    				}
	  				return $has_virtual;
   
				}
   
    
				/**
				 * Check If The Gateway Is Available For Use
				 *
				 * @access public
				 * @return bool
				 */
				function is_available() {
					
    				if($this->is_virtual_product_in_cart()){
      					return parent::is_available();
    				}
    
    				$enable_for_country  = $this->is_available_for_country();
    				if($enable_for_country === false){ return false; }
    
					if ( ! empty( $this->enable_for_methods ) ) {

						// Only apply if all packages are being shipped via local pickup
						$chosen_shipping_methods_session = WC()->session->get( 'chosen_shipping_methods' );

						if ( isset( $chosen_shipping_methods_session ) ) {
							$chosen_shipping_methods = array_unique( $chosen_shipping_methods_session );
						} else {
							$chosen_shipping_methods = array();
						}

						$check_method = false;

						if ( is_page( wc_get_page_id( 'checkout' ) ) && ! empty( $wp->query_vars['order-pay'] ) ) {

							$order_id = absint( $wp->query_vars['order-pay'] );
							$order    = new WC_Order( $order_id );

							if ( $order->shipping_method )
								$check_method = $order->shipping_method;

						} elseif ( empty( $chosen_shipping_methods ) || sizeof( $chosen_shipping_methods ) > 1 ) {
							$check_method = false;
						} elseif ( sizeof( $chosen_shipping_methods ) == 1 ) {
							$check_method = $chosen_shipping_methods[0];
						}

						if ( ! $check_method )
						return false;

						$found = false;

						foreach ( $this->enable_for_methods as $method_id ) {
							if ( strpos( $check_method, $method_id ) === 0 ) {
								$found = true;
								break;
							}
						}

						if ( ! $found )
						return false;
					}

    				//User role shipping fix
    				if(WC()->session->chosen_shipping_methods[0] == 'user-role>dobirka-pro-partnery'){
      					return false;
    				}
    
					return parent::is_available();
				}
 
    
  
  				/**
  				 * Check is payment method available for selected country
  				 *
  				 * return true or false
  				 */            
  				public function is_available_for_country() {
     				
     				if( !empty( WC()->customer ) ){

     					$country = $this->get_customer_country();     

       					if ( ! empty( $this->enable_pnu_countries ) ) {  
        					
        					if( !in_array( $country, $this->enable_pnu_countries ) ){ 
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
    			 * Output for the order received page.
    			 */
    			public function thankyou_page( $order_id ) {
					if ( $this->instructions ) {
        				echo wpautop( wptexturize( wp_kses_post( $this->instructions ) ) );
        			}
        			$this->bank_details( $order_id );
    			}

    			/**
    			 * Add content to the WC emails.
    			 *
    			 * @access public
    			 * @param WC_Order $order
    			 * @param bool $sent_to_admin
    			 * @param bool $plain_text
    			 * @return void
    			 */
    			public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {

    				if ( $sent_to_admin || $order->status !== 'on-hold' || $order->payment_method !== 'pnu' ) {
    					return;
    				}

					if ( $this->instructions ) {
        				echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
        			}

					$this->bank_details( $order->id );
    			}

    			/**
    			 * Get bank details and place into a list format
    			 */
   				private function bank_details( $order_id = '' ) {
    				if ( empty( $this->account_details ) ) {
    					return;
    				}

    				echo '<h2>' . __( 'Detail bankovního účtu', $this->plugin_slug ) . '</h2>' . PHP_EOL;

    				$pnu_accounts = apply_filters( 'woocommerce_pnu_accounts', $this->account_details );

    				if ( ! empty( $pnu_accounts ) ) {
	    				foreach ( $pnu_accounts as $pnu_account ) {
	    					echo '<ul class="order_details pnu_details">' . PHP_EOL;

	    					$pnu_account = (object) $pnu_account;

	    					// pnu account fields shown on the thanks page and in emails
							$account_fields = apply_filters( 'woocommerce_pnu_account_fields', array(
								'account_number'=> array(
						                'label' => __( 'Číslo účtu', $this->plugin_slug ),
						                'value' => $pnu_account->account_number
								),
								'sort_code'		=> array(
						                'label' => __( 'Sort Code', $this->plugin_slug ),
						                'value' => $pnu_account->sort_code
								),
								'iban'			=> array(
						                'label' => __( 'IBAN', $this->plugin_slug ),
						                'value' => $pnu_account->iban
								),
								'bic'			=> array(
						                'label' => __( 'BIC', $this->plugin_slug ),
						                'value' => $pnu_account->bic
								)
							), $order_id );

							if ( $pnu_account->account_name || $pnu_account->bank_name ) {
								echo '<h3>' . implode( ' - ', array_filter( array( $pnu_account->account_name, $pnu_account->bank_name ) ) ) . '</h3>' . PHP_EOL;
							}

	    					foreach ( $account_fields as $field_key => $field ) {
				    			if ( ! empty( $field['value'] ) ) {
				    				echo '<li class="' . esc_attr( $field_key ) . '">' . esc_attr( $field['label'] ) . ': <strong>' . wptexturize( $field['value'] ) . '</strong></li>' . PHP_EOL;
				    			}	
							}

	    					echo '</ul>';
	    				}
	    			}
    			}

    			/**
    			 * Process the payment and return the result
    			 *
    			 * @param int $order_id
    			 * @return array
    			 */
    			public function process_payment( $order_id ) {

					$order = new WC_Order( $order_id );

					// Mark as on-hold (we're awaiting the cheque)
					if ( ! empty( $this->order_status ) ) {
						$order->update_status( $this->order_status, __( 'Čeká na dokončení bankovního převodu', $this->plugin_slug ) );
					}else{
						$order->update_status( 'on-hold', __( 'Čeká na dokončení bankovního převodu', $this->plugin_slug ) );
					}

					// Reduce stock levels
					$order->reduce_order_stock();

					// Remove cart
					WC()->cart->empty_cart();

					// Return thankyou redirect
					return array(
						'result' 	  => 'success',
						'redirect'	=> $this->get_return_url( $order )
					);
    			}


				public function get_customer_country(){

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

			}
		}//End class


 
	/**
     *
     *  Add the Gateway to WooCommerce
     *       
     */
                 
  	function woocommerce_add_gateway_pnu($methods) {
        $methods[] = 'WC_Gateway_PNU';
        return $methods;
    }
    //Woocommerce payment gateways filter
    add_filter('woocommerce_payment_gateways', 'woocommerce_add_gateway_pnu' );
    
}    