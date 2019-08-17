<?php

	header('Content-type: text/css');
	require '/../../../../wp-load.php'; // load wordpress bootstrap
	require '/../inc/theme-style/theme-style.php';

	$css = '';
	if ( function_exists( 'fw_get_db_settings_option' ) ){

		$header_bg = fw_get_db_settings_option( 'header_bg' );
		if (! empty( $header_bg ) ) {

			$css .= '.page-header { background-image: url(' . esc_attr( $header_bg['url'] ) . ') !important; } ';
		}

		$footer_bg = fw_get_db_settings_option( 'footer_bg' );
		if (! empty( $footer_bg ) ) {

			$css .= '#block-footer { background-image: url(' . esc_attr( $footer_bg['url'] ) . ') !important; } ';
		}

		$bg_404 = fw_get_db_settings_option( '404_bg' );
		if (! empty( $bg_404 ) ) {

			$css .= 'body.error404 { background-image: url(' . esc_attr( $bg_404['url'] ) . ') !important; } ';
		}

		$go_top_img = fw_get_db_settings_option( 'go_top_img' );
		if (! empty( $go_top_img ) ) {

			$css .= '.go-top:before { background-image: url(' . esc_attr( $go_top_img['url'] ) . ') !important; } ';
		}

		$coffeeking_pace = fw_get_db_settings_option( 'page-loader' );

		if ( !empty($coffeeking_pace) AND !empty($coffeeking_pace['image']) AND !empty($coffeeking_pace['image']['loader_img'])) {

			$css .= '.paceloader-image .pace-image { background-image: url(' . esc_attr( $coffeeking_pace['image']['loader_img']['url'] ) . ') !important; } ';
		}
		
	}

	echo coffeeking_generate_css().$css;

