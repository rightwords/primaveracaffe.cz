<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Maďarsko DPD','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
     <tr>
      <td><?php _e('Zásilka do 1kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-hu-1kg" value="<?php if(!empty($zasilkovna_prices['dpd-hu-1kg'])){  echo $zasilkovna_prices['dpd-hu-1kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 2kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-hu-2kg" value="<?php if(!empty($zasilkovna_prices['dpd-hu-2kg'])){  echo $zasilkovna_prices['dpd-hu-2kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-hu-5kg" value="<?php if(!empty($zasilkovna_prices['dpd-hu-5kg'])){  echo $zasilkovna_prices['dpd-hu-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-hu-10kg" value="<?php if(!empty($zasilkovna_prices['dpd-hu-10kg'])){  echo $zasilkovna_prices['dpd-hu-10kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-hu-30kg" value="<?php if(!empty($zasilkovna_prices['dpd-hu-30kg'])){  echo $zasilkovna_prices['dpd-hu-30kg']; } ?>"></td>
    </tr>
   </table>
   