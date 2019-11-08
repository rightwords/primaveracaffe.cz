<?php 
/**
 * @package    Zásilkovna
 * @author    Woo
 * @license   GPL-2.0+
 * @link      http://woo.cz
 * @copyright 2014 woo
 */

class Zasilkovna_Ticket {

    /**
     * Get weight by weight unit
     * 
     * @since 1.1.9
     */ 
    public static function send_ticket( $order_id ){
        
        $barcode = get_post_meta( $order_id, 'zasilkovna_barcode', true );
        if( !empty( $barcode ) ){ return false; }

        zasilkovna_log('Send ticket: order id ', $order_id);

        $zasilkovna_option = get_option( 'zasilkovna_option' );
        $order_meta = get_post_meta( $order_id );
        $zasilkovna_id = get_post_meta( $order_id, 'zasilkovna_id_pobocky', true );
  
        if( !empty( $zasilkovna_id ) ){
  
            zasilkovna_log( 'Id pobočky ', $zasilkovna_id );

            $order = wc_get_order($order_id);
  
            //$zasil = true;
            if( self::check_ship_id( $order ) === true ){
  
                $zasilkovna_order = self::set_zasilkovna_order( $order_meta, $order, $zasilkovna_option, $zasilkovna_id );
                $apiPassword = $zasilkovna_option['api_password'];
                
                
  
                $control = get_post_meta( $order_id, '_zasilkovna_odeslano', true );
                if(empty($control) && $control != 'ok'){
  
 
                    $xml = '
                        <createPacket>
                            <apiPassword>'.$apiPassword.'</apiPassword>
                            <packetAttributes>
                                <number>'.$zasilkovna_order['number'].'</number>
                                <name>'.$zasilkovna_order['name'].'</name>
                                <surname>'.$zasilkovna_order['surname'].'</surname>
                                <email>'.$zasilkovna_order['email'].'</email>
                                <phone>'.$zasilkovna_order['phone'].'</phone>
                                <street>'.$zasilkovna_order['street'].'</street>
                                <houseNumber>'.$zasilkovna_order['houseNumber'].'</houseNumber>
                                <city>'.$zasilkovna_order['city'].'</city>
                                <zip>'.$zasilkovna_order['zip'].'</zip>
                                <currency>'.$zasilkovna_order['currency'].'</currency>
                                <cod>'.$zasilkovna_order['cod'].'</cod>
                                <addressId>'.$zasilkovna_order['addressId'].'</addressId>
                                <value>'.$zasilkovna_order['value'].'</value>
                                <eshop>'.$zasilkovna_order['eshop'].'</eshop>
                            </packetAttributes>
                        </createPacket>
                    ';

                    zasilkovna_log('Odeslaná data', $xml);

                    $ch = curl_init('http://www.zasilkovna.cz/api/rest');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                    $result = curl_exec($ch);
                    zasilkovna_log('Odpověď Zásikovny', serialize($result));

                    $result = json_decode(json_encode(simplexml_load_string($result)), true);                                        
                   
                    if( $result['status'] == 'ok' ){
                        
                        update_post_meta($order_id,'_zasilkovna_odeslano','ok');
                        update_post_meta( $order_id, 'zasilkovna_id_zasilky', $result['result']['id'] ); 
                        update_post_meta( $order_id, 'zasilkovna_barcode', $result['result']['barcode'] );
                        update_post_meta( $order_id, 'zasilkovna_barcodeText', $result['result']['barcodeText'] );
                        delete_post_meta( $order_id, 'zasilkovna_failText' );

                        zasilkovna_log('Cena zásilky', $zasilkovna_order['value']);
                        zasilkovna_log('Odpověď Zásikovny', serialize($result));

                    }elseif( $result['status'] == 'fault' ){
                        
                        if( !empty( $result['detail'] ) ){
                        
                            if( !class_exists( 'WC_Email' ) ){
                                WC()->mailer();
                            }

                            if( !empty(  $result['detail']['attributes']['fault']['fault'] ) ){
                                update_post_meta( $order_id, 'zasilkovna_failText', $result['detail']['attributes']['fault']['fault'] );

                                $note = __( 'Zásilka nebyla uložena s chybou:', 'zasilkovna' ) . ' ' . $result['detail']['attributes']['fault']['fault'];

                                $order->add_order_note( $note );

                                if( !empty( $zasilkovna_option['error_email'] ) && $zasilkovna_option['error_email'] == 'email' ){
                                    
                                    require_once( WOOZASILKOVNADIR . 'includes/class-wc-zasilkovna-admin-error-info.php' );          
                                    $send = new WC_Zasilkovna_Admin_Error_Info();            
                                    $mail = $send->trigger( $order_id, $note );

                                }

                            }elseif( !empty(  $result['detail']['attributes']['fault'][0]['fault'] ) ){
                                update_post_meta( $order_id, 'zasilkovna_failText', $result['detail']['attributes']['fault'][0]['fault'] );                             
                                $note = __( 'Zásilka nebyla uložena s chybou:', 'zasilkovna' ) . ' ' . $result['detail']['attributes']['fault'][0]['fault'];

                                $order->add_order_note( $note );

                                if( !empty( $zasilkovna_option['error_email'] ) && $zasilkovna_option['error_email'] == 'email' ){

                                    require_once( WOOZASILKOVNADIR . 'includes/class-wc-zasilkovna-admin-error-info.php' );          
                                    $send = new WC_Zasilkovna_Admin_Error_Info();          
                                    $mail = $send->trigger( $order_id, $note );

                                }

                            }
                        }
                        zasilkovna_log('Fail: Odpověď Zásikovny', serialize($result));



                    }

                    update_post_meta( $order_id, 'zasilkovna_status', $result['status'] );


                }   
            }
  
        }
        
    }

    /**
     * Control if is shipping method on Zasilkovna methods
     *     
     */
    public static function set_zasilkovna_order( $meta, $order, $zasilkovna_option, $zasilkovna_id ){

                
        $country = self::set_order_country( $meta );

        $order_id = Woo_Order_Compatibility::get_order_id( $order );

        $currency_price = self::convert_price( $meta, $country, $meta['_order_total'][0], $order );


        $email   = $order->get_billing_email();
        $phone   = $meta['_billing_phone'][0];    

        $version = woo_check_wc_version();

        if( $version === false ){

            if ( $order->get_formatted_billing_address() != $order->get_formatted_shipping_address() ){

                $name    = $meta['_shipping_first_name'][0];
                $surname = $meta['_shipping_last_name'][0];
                $street  = $meta['_shipping_address_1'][0];
                $house   = $meta['_shipping_address_2'][0];
                $town    = $meta['_shipping_city'][0];
                $zip     = $meta['_shipping_postcode'][0];

            }else{

                $name    = $meta['_billing_first_name'][0];
                $surname = $meta['_billing_last_name'][0];            
                $street  = $meta['_billing_address_1'][0];
                $house   = $meta['_billing_address_2'][0];
                $town    = $meta['_billing_city'][0];
                $zip     = $meta['_billing_postcode'][0];

            }

        }else{

            $order_data = $order->get_data();

                $name    = $order_data['shipping']['first_name'];
                $surname = $order_data['shipping']['last_name'];            
                $street  = $order_data['shipping']['address_1'];
                $house   = $order_data['shipping']['address_2'];
                $town    = $order_data['shipping']['city'];
                $zip     = $order_data['shipping']['postcode'];
        
        }

                $price   = $currency_price[0];
                $eshop   = $zasilkovna_option['nazev_eshopu'];
                
                $order_number = $order->get_order_number();
                $zasilkovna_order_number = apply_filters( 'zasilkovna_order_number', $order_number, $order );

                $zasilkovna_order = array();
                $zasilkovna_order['number']      = (string)$zasilkovna_order_number;
                $zasilkovna_order['name']        = (string)$name;
                $zasilkovna_order['surname']     = (string)$surname;
                $zasilkovna_order['email']       = (string)$email;
                $zasilkovna_order['phone']       = (string)$phone;
                $zasilkovna_order['street']      = (string)$street;
                $zasilkovna_order['houseNumber'] = (string)$house;
                $zasilkovna_order['city']        = (string)$town;
                $zasilkovna_order['zip']         = (string)$zip;

                $zasilkovna_order['currency']    = (string)$currency_price[1];

                $s_method = get_post_meta( $order_id, '_payment_method', true );
  
  
                $zasilkovna_order['cod']        = zasilkovna_get_cod_value($s_method, $price, $country);
                $zasilkovna_order['addressId']  = (int)$zasilkovna_id;
                $zasilkovna_order['value']      = (float)$price;

                $zasilkovna_order['eshop']      = (string)$eshop;

                return $zasilkovna_order;

    }

    

    /**
     * Convert
     *     
     */
    public static function convert_price( $meta, $country, $price, $order ){

        $currency = Woo_Order_Compatibility::get_order_currency( $order );
        $zasilkovna_prices = get_option('zasilkovna_prices');
        $country_currency = get_option('zasilkovna_country_currency');

            if(empty($zasilkovna_prices['kurz-euro'])){ $kurz_eur = 27; }else{ $kurz_eur = $zasilkovna_prices['kurz-euro']; }
            if(empty($zasilkovna_prices['kurz-forint'])){ $kurz_forint = 9; }else{ $kurz_forint = $zasilkovna_prices['kurz-forint']; }
            if(empty($zasilkovna_prices['kurz-zloty'])){ $kurz_zloty = 6.5; }else{ $kurz_zloty = $zasilkovna_prices['kurz-zloty']; }
                 
        
        if( $country == 'CZ' ){
            //Defaultní měna je CZK                        
            if( $currency == 'EUR' ){ 
                //$czk_kurz = 1/$kurz_eur;
                $price = round( $price * $kurz_eur, 0); 
            }   

            $return = array( $price, 'CZK');


        
        }elseif($country == 'BG'){
        
            //Defaultní měna je CZK                        
            if( $currency == 'EUR' ){ 
                $czk_kurz = 1/$kurz_eur;
                $price = round( $price * $czk_kurz, 0); 
            }   
            $return = array( $price, 'CZK');
        
        }elseif($country == 'PL'){
        
            //Defaultní měna je CZK                        
            if( $currency == 'EUR' ){ 
                $czk_kurz = 1/$kurz_eur;
                $price = round( $price * $czk_kurz, 0); 
            }elseif( $currency == 'PLN' ){
                $pl_kurz = 1/$kurz_zloty;
                $price = round( $price * $pl_kurz, 0); 
            }   
            $return = array( $price, 'CZK');

        
        }elseif($country == 'RO'){
        
            //Defaultní měna je CZK                        
            if( $currency == 'EUR' ){ 
                $czk_kurz = 1/$kurz_eur;
                $price = round( $price * $czk_kurz, 2); 
            }
            elseif( $currency == 'PLN' ){
                $pl_kurz = 1/$kurz_zloty;
                $price = round( $price * $pl_kurz, 0); 
            }   
            $return = array( $price, 'CZK');
        
        }elseif($country == 'SK'){
                    
            if( !empty( $country_currency['country_currency_sk'] ) ){
                $allowed_currency = $country_currency['country_currency_sk'];
            }else{
                $allowed_currency = 'CZK';
            }

            if( $currency == 'CZK' ){ 
                
                if( $allowed_currency == 'EUR' ){
                    $price = round( $price / $kurz_eur, 2 ); 
                }

            }elseif( $currency == 'EUR' ){ 

                if( $allowed_currency == 'CZK' ){
                    $czk_kurz = 1/$kurz_eur;
                    $price = round( $price * $kurz_eur, 0);
                }

            }

            $return = array( $price, $allowed_currency); 
       
        }elseif($country == 'HU'){
        
            $price = round($price / $kurz_forint);
        
        }elseif($country == 'DE'){
        
            if( !empty( $country_currency['country_currency_de'] ) ){
                $allowed_currency = $country_currency['country_currency_de'];
            }else{
                $allowed_currency = 'CZK';
            }

            if( $currency == 'CZK' ){ 
                
                if( $allowed_currency == 'EUR' ){
                    $price = round( $price / $kurz_eur, 2 ); 
                }

            }elseif( $currency == 'EUR' ){ 

                if( $allowed_currency == 'CZK' ){
                    $czk_kurz = 1/$kurz_eur;
                    $price = round( $price * $kurz_eur, 0);
                }

            }

            $return = array( $price, $allowed_currency); 
        
        }elseif($country == 'AT'){

            if( !empty( $country_currency['country_currency_at'] ) ){
                $allowed_currency = $country_currency['country_currency_at'];
            }else{
                $allowed_currency = 'CZK';
            }

            if( $currency == 'CZK' ){ 
                
                if( $allowed_currency == 'EUR' ){
                    $price = round( $price / $kurz_eur, 2 ); 
                }

            }elseif( $currency == 'EUR' ){ 

                if( $allowed_currency == 'CZK' ){
                    $czk_kurz = 1/$kurz_eur;
                    $price = round( $price * $kurz_eur, 0);
                }

            }

            $return = array( $price, $allowed_currency);      
        
        }

        return $return;

    }



    /**
     * Control if is shipping method on Zasilkovna methods
     *     
     */
    public static function set_order_country( $meta ){
        
        if( !empty( $meta['_billing_country'][0] ) ){
            $country = $meta['_billing_country'][0];
        }else{
            $country = woo_get_customer_country();
        }

        return $country;
    }

    /**
     * Control if is shipping method on Zasilkovna methods
     *     
     */
    private static function check_ship_id( $order ){
            
            $shipping = get_post_meta( $order->get_id(), 'zasilkovna_id_dopravy', true );
            zasilkovna_log( 'Shipping id ', $shipping ); 

            $zasil = false;
  
            $ship_ids = self::shipping_ids();
  
            //foreach( $ship as $item ){
                if( in_array( $shipping, $ship_ids ) ){ $zasil = true; }
            return $zasil;
            
    }

    /**
     * Array of Zasilkovna ids
     *     
     */
    private static function shipping_ids(){

        $ids = Zasilkovna_Helper::set_order_shipping_ids();

        return $ids;

    }  



}//End class
