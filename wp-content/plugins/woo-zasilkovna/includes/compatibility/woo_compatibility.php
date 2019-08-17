<?php
/**
 * @package    Comaptibility
 * @author    Woo
 * @license   GPL-2.0+
 * @link      http://woo.cz
 * @copyright 2014 woo
 */

/**
 *
 * Check WooCommerce version
 *
 */ 
if( !function_exists( 'woo_check_wc_version' ) ){

    function woo_check_wc_version( $version = '2.6.14' ){
        if ( function_exists( 'WC' ) && ( version_compare( WC()->version, $version, ">" ) ) ) {
            return true;
        }else{
            return false;
        }
    }   
}


/**
 * Get customer country
 *
 * return $country
 */ 
if( !function_exists( 'woo_get_customer_country' ) ){
	
	function woo_get_customer_country(){

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

include( 'woo_order_compatibility.php' );
include( 'woo_product_compatibility.php' );

include( 'woo_order_product_line_compatibility.php' ); 
include( 'woo_order_shipping_line_compatibility.php' );
include( 'woo_order_fee_line_compatibility.php' );

