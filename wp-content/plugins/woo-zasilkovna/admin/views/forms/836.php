<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Rumunsko DPD','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
     <tr>
      <td><?php _e('Zásilka do 1kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-ro-1kg" value="<?php if(!empty($zasilkovna_prices['dpd-ro-1kg'])){  echo $zasilkovna_prices['dpd-ro-1kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 2kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-ro-2kg" value="<?php if(!empty($zasilkovna_prices['dpd-ro-2kg'])){  echo $zasilkovna_prices['dpd-ro-2kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-ro-5kg" value="<?php if(!empty($zasilkovna_prices['dpd-ro-5kg'])){  echo $zasilkovna_prices['dpd-ro-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-ro-10kg" value="<?php if(!empty($zasilkovna_prices['dpd-ro-10kg'])){  echo $zasilkovna_prices['dpd-ro-10kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 15kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-ro-15kg" value="<?php if(!empty($zasilkovna_prices['dpd-ro-15kg'])){  echo $zasilkovna_prices['dpd-ro-15kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-ro-30kg" value="<?php if(!empty($zasilkovna_prices['dpd-ro-30kg'])){  echo $zasilkovna_prices['dpd-ro-30kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-ro-dobirka" value="<?php if(!empty($zasilkovna_prices['dpd-ro-dobirka'])){  echo $zasilkovna_prices['dpd-ro-dobirka']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Příplatek za pojištění','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-ro-pojisteni" value="<?php if(!empty($zasilkovna_prices['dpd-ro-pojisteni'])){  echo $zasilkovna_prices['dpd-ro-pojisteni']; } ?>"></td>
    </tr>
   </table>
   