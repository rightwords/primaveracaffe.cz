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
	
    <table class="table-bordered">
         <tr><td colspan="2" class="first-item"></td></tr>
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Zásilkovna Slovenská republika','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
     <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="zasilkovna-hu-5kg" value="<?php if(!empty($zasilkovna_prices['zasilkovna-hu-5kg'])){  echo $zasilkovna_prices['zasilkovna-hu-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="zasilkovna-hu-10kg" value="<?php if(!empty($zasilkovna_prices['zasilkovna-hu-10kg'])){  echo $zasilkovna_prices['zasilkovna-hu-10kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 20kg','zasilkovna'); ?></td>
      <td><input type="text" name="zasilkovna-hu-20kg" value="<?php if(!empty($zasilkovna_prices['zasilkovna-hu-20kg'])){  echo $zasilkovna_prices['zasilkovna-hu-20kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
      <td><input type="text" name="zasilkovna-hu-30kg" value="<?php if(!empty($zasilkovna_prices['zasilkovna-hu-30kg'])){  echo $zasilkovna_prices['zasilkovna-hu-30kg']; } ?>"></td>
    </tr>
     <tr>
      <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
      <td><input type="text" name="zasilkovna-hu-dobirka" value="<?php if(!empty($zasilkovna_prices['zasilkovna-hu-dobirka'])){  echo $zasilkovna_prices['zasilkovna-hu-dobirka']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Doprava zdarma od','zasilkovna'); ?></td>
      <td><input type="text" name="zasilkovna-hu-free" value="<?php if(!empty($zasilkovna_prices['zasilkovna-hu-free'])){  echo $zasilkovna_prices['zasilkovna-hu-free']; } ?>"></td>
    </tr>
   </table>

   <?php

    include( 'forms/763.php' ); //Maďarská pošta',
            echo '<div class="clear"></div>';
            
            include( 'forms/805.php' ); //Maďarsko DPD',
            echo '<div class="clear"></div>';
            
            include( 'forms/151.php' ); //Maďarsko Transoflex',
            echo '<div class="clear"></div>';
   
   ?>


   	<input type="hidden" name="services_price" value="ok" />
    <input type="hidden" name="services_price_country" value="hu" />
   	<input type="submit" class="button" value="<?php _e('Uložit','zasilkovna'); ?>" />

</form>

      </div>
    </div>
</div> 

<div class="t-col-12">
  <div class="woo-box box-info">
    <div class="box-header">
      <h3 class="box-title"><?php _e('Pobočky zásilkovny','woocommerce-ulozenka'); ?></h3>
    </div>
    <div class="box-body">


<table class="table-bordered">
  <tr>
    <th><?php _e('ID','zasilkovna'); ?></th>
    <th><?php _e('Jméno','zasilkovna'); ?></th>
    <th><?php _e('Provozovna','zasilkovna'); ?></th>
    <th><?php _e('Ulice','zasilkovna'); ?></th>
    <th><?php _e('Město','zasilkovna'); ?></th>
    <th><?php _e('PSČ','zasilkovna'); ?></th>
    <th><?php _e('Země','zasilkovna'); ?></th>
  </tr>
<?php 
if( !empty( $zasilkovna_mista_hu ) ){
foreach( $zasilkovna_mista_hu as $key => $item ){ ?>  
  <tr>
    <td><?php echo $item['id'] ?></td>
    <td><?php echo $item['name'] ?></td>
    <td><?php echo $item['place'] ?></td>
    <td><?php echo $item['street'] ?></td>
    <td><?php echo $item['city'] ?></td>
    <td><?php echo $item['zip'] ?></td>
    <td><?php echo $item['country'] ?></td>
  </tr>
<?php } 
}
?>  
</table>  

<a class="button" href="<?php echo home_url().'/wp-admin/admin.php?page=zasilkovna&check=ok'; ?>"><?php _e('Aktualizovat pobočky','zasilkovna'); ?></a> 
