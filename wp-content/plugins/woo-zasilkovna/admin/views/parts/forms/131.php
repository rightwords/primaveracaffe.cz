<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Doručení na adresu SR','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
     <tr>
      <td><?php _e('Zásilka do 1kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-doruceni-1kg" value="<?php if(!empty($zasilkovna_prices['slovensko-doruceni-1kg'])){  echo $zasilkovna_prices['slovensko-doruceni-1kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 2kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-doruceni-2kg" value="<?php if(!empty($zasilkovna_prices['slovensko-doruceni-2kg'])){  echo $zasilkovna_prices['slovensko-doruceni-2kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-doruceni-5kg" value="<?php if(!empty($zasilkovna_prices['slovensko-doruceni-5kg'])){  echo $zasilkovna_prices['slovensko-doruceni-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-doruceni-10kg" value="<?php if(!empty($zasilkovna_prices['slovensko-doruceni-10kg'])){  echo $zasilkovna_prices['slovensko-doruceni-10kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 20kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-doruceni-20kg" value="<?php if(!empty($zasilkovna_prices['slovensko-doruceni-20kg'])){  echo $zasilkovna_prices['slovensko-doruceni-20kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-doruceni-30kg" value="<?php if(!empty($zasilkovna_prices['slovensko-doruceni-30kg'])){  echo $zasilkovna_prices['slovensko-doruceni-30kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 40kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-doruceni-40kg" value="<?php if(!empty($zasilkovna_prices['slovensko-doruceni-40kg'])){  echo $zasilkovna_prices['slovensko-doruceni-40kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 50kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-doruceni-50kg" value="<?php if(!empty($zasilkovna_prices['slovensko-doruceni-50kg'])){  echo $zasilkovna_prices['slovensko-doruceni-50kg']; } ?>"></td>
    </tr>
     <tr>
      <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-doruceni-dobirka" value="<?php if(!empty($zasilkovna_prices['slovensko-doruceni-dobirka'])){  echo $zasilkovna_prices['slovensko-doruceni-dobirka']; } ?>"></td>
    </tr>
   </table>
   