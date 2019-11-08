<?php
/**
 * Customer Waiting Payment
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails/Plain
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

echo "= " . $email_heading . " =\n\n";


printf( __( 'Chyba odeslání zásilky pro objednávku č.: %s', 'woo-sledovani' ), $order->get_order_number() ) . '\n';
echo __('Zásilka objednávky nebyla vložena do systému Zásilkovny, z důvodu chyby:', 'zasilkovna') . '\n';
echo $note . '\n'

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
