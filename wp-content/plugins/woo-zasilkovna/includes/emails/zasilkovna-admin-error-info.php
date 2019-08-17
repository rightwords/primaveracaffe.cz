<?php
/**
 * Customer Customer Waiting Payment
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


?>

<?php do_action('woocommerce_email_header', $email_heading); ?>

<h2><?php printf( __( 'Chyba odeslání zásilky pro objednávku č.: %s', 'woo-sledovani' ), $order->get_order_number() ); ?></h2>
<p><?php echo __('Zásilka objednávky nebyla vložena do systému Zásilkovny, z důvodu chyby:', 'zasilkovna'); ?></p>
<p><?php echo $note ?></p>


<?php do_action( 'woocommerce_email_footer' ); ?>
