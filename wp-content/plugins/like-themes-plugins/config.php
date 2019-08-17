<?php if ( ! defined( 'ABSPATH' ) ) die( 'Forbidden' );
/**
 * TaxiPark Config
 */

$like_cfg = array(

	'path'	=> plugin_dir_path(__DIR__),
	'base' 	=> plugin_basename(__DIR__),
	'url'	=> plugin_dir_url(__FILE__),

	'like_sections'	=> array(),
);


add_action( 'after_setup_theme', 'like_vc_config', 4 );
function like_vc_config() {

	global $like_cfg;

    $value = array();
    $value = apply_filters( 'like_get_vc_config', $value );

    $like_cfg = array_merge($like_cfg, $value);

    return $value;
}


add_action( 'plugins_loaded', 'like_load_plugin_textdomain' );
function like_load_plugin_textdomain() {
	load_plugin_textdomain( 'like-themes-plugins', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
