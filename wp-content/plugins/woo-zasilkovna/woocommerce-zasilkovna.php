<?php
/*
Plugin Name: Woo Zásilkovna
Plugin URI: 
Description: Zásilkovna pro Woocommerce Wordpress 
Version: 2.0
Author: Woo  
Author URI: http://woo.cz
Text Domain: zasilkovna
Domain Path: /languages
*/



define( 'WOOZASILKOVNADIR', plugin_dir_path( __FILE__ ) );
define( 'WOOZASILKOVNAURL', plugin_dir_url( __FILE__ ) );

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    return false;
}

//Load notice class
if ( is_admin() ) {

    require_once( plugin_dir_path( __FILE__ ) . 'includes/notices.php' );
    $notices = new WooCommerce_Zasilkovna_Notices();

}



include( 'includes/compatibility/woo_compatibility.php' );
include( 'includes/setting.php' );
include( 'includes/class-zasilkovna-helper.php' );
include( 'includes/class-zasilkovna-ticket.php' );
include( 'includes/class-zasilkovna-shipping.php' );
include( 'includes/class-zasilkovna-fee.php' );
include( 'includes/class-pobocky.php' );
//Doprava
include( 'includes/class-wc-gateway-dobirka.php' );
include( 'includes/class-platba-na-ucet.php' );



/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-zasilkovna.php' );

register_activation_hook(   __FILE__, array( 'Woo_Zasilkovna', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Woo_Zasilkovna', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Woo_Zasilkovna', 'get_instance' ) );



add_action( 'wp_enqueue_scripts','zasilkovna_enqueue_scripts'  );
function zasilkovna_enqueue_scripts() {
		wp_enqueue_script( 'zasilkovna-public', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ) );
	}
  
  
/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-zasilkovna-admin.php' );
	add_action( 'plugins_loaded', array( 'Woo_Zasilkovna_Admin', 'get_instance' ) );

}

add_filter( 'woocommerce_email_classes', 'add_zasilkovna_error_woocommerce_email' );
function add_zasilkovna_error_woocommerce_email( $email_classes ) {
  
    require_once WOOZASILKOVNADIR . 'includes/class-wc-zasilkovna-admin-error-info.php';

    $email_classes['WC_Zasilkovna_Admin_Error_Info'] = new WC_Zasilkovna_Admin_Error_Info();
 
    return $email_classes;
 
}


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
 * Custom endpoint
 *
 */  
add_action( 'init', 'zasikovna_add_json_endpoint' ); 
function zasikovna_add_json_endpoint() {
    add_rewrite_endpoint( 'zasilkovna', EP_ALL );
}


/**
 *  Add template redirect
 *
 */
add_action( 'template_redirect', 'zasilkovna_json_template_redirect' );   
function zasilkovna_json_template_redirect() {
    global $wp_query;
 
    if ( ! isset( $wp_query->query_vars['zasilkovna'] ) )
        return;
 
    if($wp_query->query_vars['zasilkovna'] == 'pobocky'){
        include plugin_dir_path( __FILE__ ) . 'includes/pobocky.php';
    }
    exit;
}
 
/**
 * Vytvořit tiket na Zásilkovně
 * 
 * @since @1.0.0
 */  
 
    $zasilkovna_option = get_option( 'zasilkovna_option');
    if( !empty( $zasilkovna_option['no_send'] ) && $zasilkovna_option['no_send'] == 'yes' ){

    }else{
        if(empty($zasilkovna_option['odeslani_zasilky'])){
            add_action( 'woocommerce_thankyou', 'send_zasilkovna_ticket');
        }elseif($zasilkovna_option['odeslani_zasilky'] == 'thankyou'){
            add_action( 'woocommerce_thankyou', 'send_zasilkovna_ticket');
        }elseif($zasilkovna_option['odeslani_zasilky'] == 'processing'){
            add_action( 'woocommerce_order_status_processing', 'send_zasilkovna_ticket');
        }elseif($zasilkovna_option['odeslani_zasilky'] == 'finished'){
            add_action( 'woocommerce_order_status_completed', 'send_zasilkovna_ticket');
        }else{
            add_action( 'woocommerce_thankyou', 'send_zasilkovna_ticket');
        }
    }
 
 
 
function send_zasilkovna_ticket( $order_id ){
  
    Zasilkovna_Ticket::send_ticket( $order_id );    

}






/**
 * Získat hodnotu dobírky
 *
 * @since 1.2.2
 */   
function zasilkovna_get_cod_value( $s_method, $price, $country ){

    if( !empty( $s_method ) ){
    
        if( $s_method == 'dobirka' ){  
            if( $country == 'SK' ){
                $cod = (float)$price;
            }else{
                $cod = (int)$price;
            } 
        }else{
            $cod = (int)0;
        }
    
    }else{
        $cod = (int)0;
    }

    return $cod;

} 
 
 
/**
 * Calculate fee
 */ 
add_action( 'woocommerce_cart_calculate_fees' , 'calculate_zasilkovna_fee', 10 );

function calculate_zasilkovna_fee(){

    $fee = new Zasilkovna_Fee();
    $fee->calculate_fee();

}


/**
 * Save log info
 *
 * @since 1.2.2
 */  
function zasilkovna_log($action, $text){ 

    $file = WOOZASILKOVNADIR.'notify_log.txt';
    $current = file_get_contents($file);
    $current .= date('D, d M Y H:i:s').$action.PHP_EOL;
    $current .= $text.PHP_EOL;
    file_put_contents($file, $current);

} 
 
?>