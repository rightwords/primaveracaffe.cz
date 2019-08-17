<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Polsko DPD','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
     <tr>
      <td><?php _e('Zásilka do 1kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-pl-1kg" value="<?php if(!empty($zasilkovna_prices['dpd-pl-1kg'])){  echo $zasilkovna_prices['dpd-pl-1kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 2kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-pl-2kg" value="<?php if(!empty($zasilkovna_prices['dpd-pl-2kg'])){  echo $zasilkovna_prices['dpd-pl-2kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-pl-5kg" value="<?php if(!empty($zasilkovna_prices['dpd-pl-5kg'])){  echo $zasilkovna_prices['dpd-pl-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-pl-10kg" value="<?php if(!empty($zasilkovna_prices['dpd-pl-10kg'])){  echo $zasilkovna_prices['dpd-pl-10kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-pl-30kg" value="<?php if(!empty($zasilkovna_prices['dpd-pl-30kg'])){  echo $zasilkovna_prices['dpd-pl-30kg']; } ?>"></td>
    </tr>
   </table>
   