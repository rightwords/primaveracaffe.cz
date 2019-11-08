<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Ukrajina na adresu','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
     <tr>
      <td><?php _e('Zásilka do 1kg','zasilkovna'); ?></td>
      <td><input type="text" name="ukrajina-doruceni-1kg" value="<?php if(!empty($zasilkovna_prices['ukrajina-doruceni-1kg'])){  echo $zasilkovna_prices['ukrajina-doruceni-1kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 2kg','zasilkovna'); ?></td>
      <td><input type="text" name="ukrajina-doruceni-2kg" value="<?php if(!empty($zasilkovna_prices['ukrajina-doruceni-2kg'])){  echo $zasilkovna_prices['ukrajina-doruceni-2kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="ukrajina-doruceni-5kg" value="<?php if(!empty($zasilkovna_prices['ukrajina-doruceni-5kg'])){  echo $zasilkovna_prices['ukrajina-doruceni-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="ukrajina-doruceni-10kg" value="<?php if(!empty($zasilkovna_prices['ukrajina-doruceni-10kg'])){  echo $zasilkovna_prices['ukrajina-doruceni-10kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 15kg','zasilkovna'); ?></td>
      <td><input type="text" name="ukrajina-doruceni-15kg" value="<?php if(!empty($zasilkovna_prices['ukrajina-doruceni-15kg'])){  echo $zasilkovna_prices['ukrajina-doruceni-15kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
      <td><input type="text" name="ukrajina-doruceni-dobirka" value="<?php if(!empty($zasilkovna_prices['ukrajina-doruceni-dobirka'])){  echo $zasilkovna_prices['ukrajina-doruceni-dobirka']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Příplatek za pojištění','zasilkovna'); ?></td>
      <td><input type="text" name="ukrajina-doruceni-pojisteni" value="<?php if(!empty($zasilkovna_prices['ukrajina-doruceni-pojisteni'])){  echo $zasilkovna_prices['ukrajina-doruceni-pojisteni']; } ?>"></td>
    </tr>
   </table>
   