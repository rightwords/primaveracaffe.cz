<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Bulharsko DPD','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
    <tr>
      <td><?php _e('Zásilka do 1kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-bl-1kg" value="<?php if(!empty($zasilkovna_prices['dpd-bl-1kg'])){  echo $zasilkovna_prices['dpd-bl-1kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 2kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-bl-2kg" value="<?php if(!empty($zasilkovna_prices['dpd-bl-2kg'])){  echo $zasilkovna_prices['dpd-bl-2kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-bl-5kg" value="<?php if(!empty($zasilkovna_prices['dpd-bl-5kg'])){  echo $zasilkovna_prices['dpd-bl-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-bl-10kg" value="<?php if(!empty($zasilkovna_prices['dpd-bl-10kg'])){  echo $zasilkovna_prices['dpd-bl-10kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 15kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-bl-15kg" value="<?php if(!empty($zasilkovna_prices['dpd-bl-15kg'])){  echo $zasilkovna_prices['dpd-bl-15kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-bl-30kg" value="<?php if(!empty($zasilkovna_prices['dpd-bl-30kg'])){  echo $zasilkovna_prices['dpd-bl-30kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-bl-dobirka" value="<?php if(!empty($zasilkovna_prices['dpd-bl-dobirka'])){  echo $zasilkovna_prices['dpd-bl-dobirka']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Příplatek za pojištění','zasilkovna'); ?></td>
      <td><input type="text" name="dpd-bl-pojisteni" value="<?php if(!empty($zasilkovna_prices['dpd-bl-pojisteni'])){  echo $zasilkovna_prices['dpd-bl-pojisteni']; } ?>"></td>
    </tr>
   </table>