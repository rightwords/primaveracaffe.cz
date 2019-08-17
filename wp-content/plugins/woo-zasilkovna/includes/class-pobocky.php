<?php 
/**
 *
 * @package   Zasilkovna_Pobocky
 * @author    Woo
 * @license   GPL-2.0+
 * @link      http://woo.cz
 * @copyright 2018 Woo
 *
 * Version 1.5.4
 *  
 */

class Zasilkovna_Pobocky {


    static public function get_branches(){

        $zasilkovna_option = get_option( 'zasilkovna_option');

        $feed = self::load_file( $zasilkovna_option );

        $zasilkovna_mista = array();
        $zasilkovna_mista_cz = array();
        $zasilkovna_mista_sk = array();
        $zasilkovna_mista_hu = array();
        $zasilkovna_mista_pl = array();
        $zasilkovna_mista_ro = array();
        
        foreach($feed->branches as $branch){
            foreach($branch->branch as $item){

                //CZ pobočky
                if($item->country == 'cz' && ( !empty($zasilkovna_option['cz_pobocky']) && $zasilkovna_option['cz_pobocky'] == 'cz' ) ){
                    $first_id = (string)$item->id;

                    $zasilkovna_mista[$first_id]['id']              = $first_id;
                    $zasilkovna_mista[$first_id]['name']            = (string)$item->name;
                    $zasilkovna_mista[$first_id]['nameStreet']      = (string)$item->nameStreet;
                    $zasilkovna_mista[$first_id]['place']           = (string)$item->place;
                    $zasilkovna_mista[$first_id]['street']          = (string)$item->street;
                    $zasilkovna_mista[$first_id]['city']            = (string)$item->city;
                    $zasilkovna_mista[$first_id]['zip']             = (string)$item->zip;
                    $zasilkovna_mista[$first_id]['country']         = (string)$item->country;
                    $zasilkovna_mista[$first_id]['url']             = (string)$item->url;
    
                    $zasilkovna_mista_cz[$first_id]['id']           = $first_id;
                    $zasilkovna_mista_cz[$first_id]['name']         = (string)$item->name;
                    $zasilkovna_mista_cz[$first_id]['nameStreet']   = (string)$item->nameStreet;
                    $zasilkovna_mista_cz[$first_id]['place']        = (string)$item->place;
                    $zasilkovna_mista_cz[$first_id]['street']       = (string)$item->street;
                    $zasilkovna_mista_cz[$first_id]['city']         = (string)$item->city;
                    $zasilkovna_mista_cz[$first_id]['zip']          = (string)$item->zip;
                    $zasilkovna_mista_cz[$first_id]['country']      = (string)$item->country;
                    $zasilkovna_mista_cz[$first_id]['url']          = (string)$item->url;
    
                }

                //SK pobočky
                if($item->country == 'sk' && ( !empty($zasilkovna_option['sk_pobocky']) && $zasilkovna_option['sk_pobocky'] == 'sk' ) ){
                    $first_id = (string)$item->id;

                    $zasilkovna_mista[$first_id]['id']              = $first_id;
                    $zasilkovna_mista[$first_id]['name']            = (string)$item->name;
                    $zasilkovna_mista[$first_id]['nameStreet']      = (string)$item->nameStreet;
                    $zasilkovna_mista[$first_id]['place']           = (string)$item->place;
                    $zasilkovna_mista[$first_id]['street']          = (string)$item->street;
                    $zasilkovna_mista[$first_id]['city']            = (string)$item->city;
                    $zasilkovna_mista[$first_id]['zip']             = (string)$item->zip;
                    $zasilkovna_mista[$first_id]['country']         = (string)$item->country;
                    $zasilkovna_mista[$first_id]['url']             = (string)$item->url;
    
                    $zasilkovna_mista_sk[$first_id]['id']           = $first_id;
                    $zasilkovna_mista_sk[$first_id]['name']         = (string)$item->name;
                    $zasilkovna_mista_sk[$first_id]['nameStreet']   = (string)$item->nameStreet;
                    $zasilkovna_mista_sk[$first_id]['place']        = (string)$item->place;
                    $zasilkovna_mista_sk[$first_id]['street']       = (string)$item->street;
                    $zasilkovna_mista_sk[$first_id]['city']         = (string)$item->city;
                    $zasilkovna_mista_sk[$first_id]['zip']          = (string)$item->zip;
                    $zasilkovna_mista_sk[$first_id]['country']      = (string)$item->country;
                    $zasilkovna_mista_sk[$first_id]['url']          = (string)$item->url;
    
                }

                //HU pobočky
                if($item->country == 'de' && ( !empty($zasilkovna_option['de_pobocky']) && $zasilkovna_option['de_pobocky'] == 'hu' ) ){
                    $first_id = (string)$item->id;

                    $zasilkovna_mista[$first_id]['id']              = $first_id;
                    $zasilkovna_mista[$first_id]['name']            = (string)$item->name;
                    $zasilkovna_mista[$first_id]['nameStreet']      = (string)$item->nameStreet;
                    $zasilkovna_mista[$first_id]['place']           = (string)$item->place;
                    $zasilkovna_mista[$first_id]['street']          = (string)$item->street;
                    $zasilkovna_mista[$first_id]['city']            = (string)$item->city;
                    $zasilkovna_mista[$first_id]['zip']             = (string)$item->zip;
                    $zasilkovna_mista[$first_id]['country']         = (string)$item->country;
                    $zasilkovna_mista[$first_id]['url']             = (string)$item->url;
    
                    $zasilkovna_mista_hu[$first_id]['id']           = $first_id;
                    $zasilkovna_mista_hu[$first_id]['name']         = (string)$item->name;
                    $zasilkovna_mista_hu[$first_id]['nameStreet']   = (string)$item->nameStreet;
                    $zasilkovna_mista_hu[$first_id]['place']        = (string)$item->place;
                    $zasilkovna_mista_hu[$first_id]['street']       = (string)$item->street;
                    $zasilkovna_mista_hu[$first_id]['city']         = (string)$item->city;
                    $zasilkovna_mista_hu[$first_id]['zip']          = (string)$item->zip;
                    $zasilkovna_mista_hu[$first_id]['country']      = (string)$item->country;
                    $zasilkovna_mista_hu[$first_id]['url']          = (string)$item->url;
    
                }

                //PL pobočky
                if($item->country == 'pl' && ( !empty($zasilkovna_option['pl_pobocky']) && $zasilkovna_option['pl_pobocky'] == 'pl' ) ){
                    $first_id = (string)$item->id;

                    $zasilkovna_mista[$first_id]['id']              = $first_id;
                    $zasilkovna_mista[$first_id]['name']            = (string)$item->name;
                    $zasilkovna_mista[$first_id]['nameStreet']      = (string)$item->nameStreet;
                    $zasilkovna_mista[$first_id]['place']           = (string)$item->place;
                    $zasilkovna_mista[$first_id]['street']          = (string)$item->street;
                    $zasilkovna_mista[$first_id]['city']            = (string)$item->city;
                    $zasilkovna_mista[$first_id]['zip']             = (string)$item->zip;
                    $zasilkovna_mista[$first_id]['country']         = (string)$item->country;
                    $zasilkovna_mista[$first_id]['url']             = (string)$item->url;
    
                    $zasilkovna_mista_pl[$first_id]['id']           = $first_id;
                    $zasilkovna_mista_pl[$first_id]['name']         = (string)$item->name;
                    $zasilkovna_mista_pl[$first_id]['nameStreet']   = (string)$item->nameStreet;
                    $zasilkovna_mista_pl[$first_id]['place']        = (string)$item->place;
                    $zasilkovna_mista_pl[$first_id]['street']       = (string)$item->street;
                    $zasilkovna_mista_pl[$first_id]['city']         = (string)$item->city;
                    $zasilkovna_mista_pl[$first_id]['zip']          = (string)$item->zip;
                    $zasilkovna_mista_pl[$first_id]['country']      = (string)$item->country;
                    $zasilkovna_mista_pl[$first_id]['url']          = (string)$item->url;
    
                }

                //RO pobočky
                if($item->country == 'at' && ( !empty($zasilkovna_option['at_pobocky']) && $zasilkovna_option['at_pobocky'] == 'ro' ) ){
                    $first_id = (string)$item->id;

                    $zasilkovna_mista[$first_id]['id']              = $first_id;
                    $zasilkovna_mista[$first_id]['name']            = (string)$item->name;
                    $zasilkovna_mista[$first_id]['nameStreet']      = (string)$item->nameStreet;
                    $zasilkovna_mista[$first_id]['place']           = (string)$item->place;
                    $zasilkovna_mista[$first_id]['street']          = (string)$item->street;
                    $zasilkovna_mista[$first_id]['city']            = (string)$item->city;
                    $zasilkovna_mista[$first_id]['zip']             = (string)$item->zip;
                    $zasilkovna_mista[$first_id]['country']         = (string)$item->country;
                    $zasilkovna_mista[$first_id]['url']             = (string)$item->url;
    
                    $zasilkovna_mista_ro[$first_id]['id']           = $first_id;
                    $zasilkovna_mista_ro[$first_id]['name']         = (string)$item->name;
                    $zasilkovna_mista_ro[$first_id]['nameStreet']   = (string)$item->nameStreet;
                    $zasilkovna_mista_ro[$first_id]['place']        = (string)$item->place;
                    $zasilkovna_mista_ro[$first_id]['street']       = (string)$item->street;
                    $zasilkovna_mista_ro[$first_id]['city']         = (string)$item->city;
                    $zasilkovna_mista_ro[$first_id]['zip']          = (string)$item->zip;
                    $zasilkovna_mista_ro[$first_id]['country']      = (string)$item->country;
                    $zasilkovna_mista_ro[$first_id]['url']          = (string)$item->url;
    
                }

            }
        }

        update_option( 'zasilkovna_mista', $zasilkovna_mista );
        update_option( 'zasilkovna_mista_cz', $zasilkovna_mista_cz );
        update_option( 'zasilkovna_mista_sk', $zasilkovna_mista_sk );
        update_option( 'zasilkovna_mista_hu', $zasilkovna_mista_hu );
        update_option( 'zasilkovna_mista_pl', $zasilkovna_mista_pl );
        update_option( 'zasilkovna_mista_ro', $zasilkovna_mista_ro );
    
    }

    /**
     *
     * Load Xml file
     *
     */   
    static public function load_file( $zasilkovna_option ){

        $xml ='http://www.zasilkovna.cz/api/v2/'.$zasilkovna_option['api_key'].'/branch.xml';

        $c = curl_init();
        curl_setopt( $c, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $c, CURLOPT_URL, $xml );
        $contents = curl_exec( $c );
        curl_close( $c );

        $feed = simplexml_load_string( $contents );

        return $feed;
    
    }






}    