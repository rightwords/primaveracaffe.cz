<?php if ( ! defined( 'ABSPATH' ) ) die( 'Forbidden' );
/**
 * Sliders Shortcode
 */

$args = get_query_var('like_sc_google_maps');

if ( !empty($args['id']) ) $id = ' id="'. esc_attr($args['id']). '"'; else $id = '';

$warning = '';
$google_api = fw_get_db_settings_option( 'google_api' );
if ( function_exists( 'fw_get_db_settings_option' ) && empty($google_api) ) {

	$warning = esc_html__("Google Map API Key is Wrong", 'like-themes-plugins');
}

echo '<div '. $id .' data-marker="'.esc_url(get_stylesheet_directory_uri()).'/assets/images/location-black.png" class="like-google-maps" data-style="'. esc_attr($args['style']) .'" data-lng="'. esc_attr($args['lng']) .'" data-lat="'. esc_attr($args['lat']) .'" data-zoom="'. esc_attr($args['zoom']) .'" style="width: '.esc_attr($args['width']).'; height: '.esc_attr($args['height']).';">'.$warning.'</div>';

if ( function_exists( 'fw_get_db_settings_option' ) && !empty($google_api) ) {

	wp_enqueue_script(
		'google-maps-api-v3',
		'https://maps.googleapis.com/maps/api/js?v=3&key=' . esc_attr( fw_get_db_settings_option( 'google_api' ) ),
		array( 'jquery' ),
		'',
		true
	);
}

