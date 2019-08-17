<?php 

$zasilkovna_option = get_option( 'zasilkovna_option');

/**
 *
 * Load Xml file
 *
 */   
$xml ='http://www.zasilkovna.cz/api/v2/995b1fabdc0fb8cd/branch.xml';
//$xml ='http://www.zasilkovna.cz/api/v2/'.$zasilkovna_option['api_key'].'/branch.xml';



$zasilkovna_mista = array();
$zasilkovna_mista_cz = array();
$zasilkovna_mista_sk = array();
$feed = simplexml_load_file($xml);
                    
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
var_dump( $feed );
foreach($feed->branches as $branch){
  foreach($branch->branch as $item){

    //CZ pobočky
    if(!empty($zasilkovna_option['cz_pobocky'])){
    
    if($item->country == 'cz' && $zasilkovna_option['cz_pobocky'] == 'cz' ){
    $first_id = (string)$item->id;

    $zasilkovna_mista[$first_id]['id'] = $first_id;
    $zasilkovna_mista[$first_id]['name'] = (string)$item->name;
    $zasilkovna_mista[$first_id]['nameStreet'] = (string)$item->nameStreet;
    $zasilkovna_mista[$first_id]['place'] = (string)$item->place;
    $zasilkovna_mista[$first_id]['street'] = (string)$item->street;
    $zasilkovna_mista[$first_id]['city'] = (string)$item->city;
    $zasilkovna_mista[$first_id]['zip'] = (string)$item->zip;
    $zasilkovna_mista[$first_id]['country'] = (string)$item->country;
    $zasilkovna_mista[$first_id]['url'] = (string)$item->url;
    
    $zasilkovna_mista_cz[$first_id]['id'] = $first_id;
    $zasilkovna_mista_cz[$first_id]['name'] = (string)$item->name;
    $zasilkovna_mista_cz[$first_id]['nameStreet'] = (string)$item->nameStreet;
    $zasilkovna_mista_cz[$first_id]['place'] = (string)$item->place;
    $zasilkovna_mista_cz[$first_id]['street'] = (string)$item->street;
    $zasilkovna_mista_cz[$first_id]['city'] = (string)$item->city;
    $zasilkovna_mista_cz[$first_id]['zip'] = (string)$item->zip;
    $zasilkovna_mista_cz[$first_id]['country'] = (string)$item->country;
    $zasilkovna_mista_cz[$first_id]['url'] = (string)$item->url;
    
    
    }
    }
    //SK pobočky
    if(!empty($zasilkovna_option['sk_pobocky'])){
    if($item->country == 'sk' && $zasilkovna_option['sk_pobocky'] == 'sk' ){
    $first_id = (string)$item->id;

    $zasilkovna_mista[$first_id]['id'] = $first_id;
    $zasilkovna_mista[$first_id]['name'] = (string)$item->name;
    $zasilkovna_mista[$first_id]['nameStreet'] = (string)$item->nameStreet;
    $zasilkovna_mista[$first_id]['place'] = (string)$item->place;
    $zasilkovna_mista[$first_id]['street'] = (string)$item->street;
    $zasilkovna_mista[$first_id]['city'] = (string)$item->city;
    $zasilkovna_mista[$first_id]['zip'] = (string)$item->zip;
    $zasilkovna_mista[$first_id]['country'] = (string)$item->country;
    $zasilkovna_mista[$first_id]['url'] = (string)$item->url;
    
    $zasilkovna_mista_sk[$first_id]['id'] = $first_id;
    $zasilkovna_mista_sk[$first_id]['name'] = (string)$item->name;
    $zasilkovna_mista_sk[$first_id]['nameStreet'] = (string)$item->nameStreet;
    $zasilkovna_mista_sk[$first_id]['place'] = (string)$item->place;
    $zasilkovna_mista_sk[$first_id]['street'] = (string)$item->street;
    $zasilkovna_mista_sk[$first_id]['city'] = (string)$item->city;
    $zasilkovna_mista_sk[$first_id]['zip'] = (string)$item->zip;
    $zasilkovna_mista_sk[$first_id]['country'] = (string)$item->country;
    $zasilkovna_mista_sk[$first_id]['url'] = (string)$item->url;
    
    }
    }

  }
}

    update_option( 'zasilkovna_mista', $zasilkovna_mista );
    update_option( 'zasilkovna_mista_cz', $zasilkovna_mista_cz );
    update_option( 'zasilkovna_mista_sk', $zasilkovna_mista_sk );

    