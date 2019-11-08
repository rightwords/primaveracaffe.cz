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

          include( 'forms/1160.php' ); //Ukrajina na adresu',
            echo '<div class="clear"></div>';
            
   ?>


   	<input type="hidden" name="services_price" value="ok" />
   	<input type="submit" class="button" <?php _e('Save','zasilkovna'); ?> />

</form>

