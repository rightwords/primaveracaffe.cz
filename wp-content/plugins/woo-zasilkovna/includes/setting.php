<?php 


add_action( 'admin_init', 'check_woo_zasilkovna_licence' );
add_action( 'init', 'check_woo_zasilkovna_licence' );

/**
 *
 * Check if licence is active
 *
 */   
function check_woo_zasilkovna_licence(){
  $licence_status = get_option('woo-zasilkovna-licence');
  if(!empty($licence_status)){
    if($licence_status=='active'){
      global $lic;
      $lic = 'active';  
    }
  }
}

/**
 *
 * Control licence
 *
 */   
function woo_zasilkovna_control_licence($licence){

  $ip = $_SERVER['REMOTE_ADDR'];

    $api_params = array(
				'licence' => $licence,
				//'ip' 	    => $ip,
				//'url'     => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( 'http://licence.toret.cz/wp-content/plugins/plc/control.php', array( 'timeout' => 35, 'sslverify' => false, 'body' => $api_params ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) ){
				return false;
      }
        
        if($response['body']=='double'){
        update_option('woo-zasilkovna-licence','active');
        update_option('woo-zasilkovna-info','Vaše licence byla aktivována');
        update_option('woo-zasilkovna-licence-key',$licence);
      }elseif($response['body']=='fail'){
        update_option('woo-zasilkovna-info','Chyba ověřování, kontaktujte prosím podporu');
      }
//      elseif($response['body']=='double'){
//        update_option('woo-zasilkovna-info','Licenční klíč byl již použit');
//      }
        
}
