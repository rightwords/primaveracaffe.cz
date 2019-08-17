<?php
/**
 * @package   WooCommerce Zásilkovna
 * @author    Woo
 * @license   GPL-2.0+
 * @link      http://woo.cz
 * @copyright 2014 woo
 */
 
?>


<form method="post" action="" class="setting-form">	
	
    <?php

    include( 'forms/80.php'  ); //Rakouská pošta',
            echo '<div class="clear"></div>';
   
   ?>


   	<input type="hidden" name="services_price" value="ok" />
   	<input type="hidden" name="services_price_country" value="at" />
   	<input type="submit" class="button" value="<?php _e('Uložit','zasilkovna'); ?>" />

</form>