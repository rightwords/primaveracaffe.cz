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
    <table class="table-bordered zasilkovna-woo doprava">
        <!--<tr>
            <th><?php _e('Dopravce','zasilkovna'); ?></th>
            <th><?php _e('Titulek služby','zasilkovna'); ?></th>
            <th><?php _e('Aktivní','zasilkovna'); ?></th>
        </tr>-->
          <tr><td colspan="3" class="first-item"></td></tr> 
        <?php 
        foreach( Zasilkovna_Helper::set_shipping_services() as $key => $service ){
        ?>
        <tr>
            <th><?php _e($service,'zasilkovna'); ?></th>
            <td><input type="text" name="service-label-<?php echo $key; ?>" value="<?php if(!empty($zasilkovna_services['service-label-'.$key])){  echo $zasilkovna_services['service-label-'.$key];}else{ _e($service, 'zasilkovna'); } ?>"></td>
            <td><input type="checkbox" name="service-active-<?php echo $key; ?>" <?php if(!empty($zasilkovna_services['service-active-'.$key])){  echo 'checked="checked"'; } ?> ></td>
        </tr>
        <?php
        }
        ?>
          
   	</table>
   	<input type="hidden" name="services" value="ok" />
   	<input type="submit" class="button" value="<?php _e('Uložit dopravce','zasilkovna'); ?>" />
</form>