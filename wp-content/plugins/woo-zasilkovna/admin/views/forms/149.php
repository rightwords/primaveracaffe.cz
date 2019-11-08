<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Slovensko kurýr','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
      <tr>
      <td><?php _e('Zásilka do 1kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-kuryr-1kg" value="<?php if(!empty($zasilkovna_prices['slovensko-kuryr-1kg'])){  echo $zasilkovna_prices['slovensko-kuryr-1kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 2kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-kuryr-2kg" value="<?php if(!empty($zasilkovna_prices['slovensko-kuryr-2kg'])){  echo $zasilkovna_prices['slovensko-kuryr-2kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-kuryr-5kg" value="<?php if(!empty($zasilkovna_prices['slovensko-kuryr-5kg'])){  echo $zasilkovna_prices['slovensko-kuryr-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-kuryr-10kg" value="<?php if(!empty($zasilkovna_prices['slovensko-kuryr-10kg'])){  echo $zasilkovna_prices['slovensko-kuryr-10kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 20kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-kuryr-20kg" value="<?php if(!empty($zasilkovna_prices['slovensko-kuryr-20kg'])){  echo $zasilkovna_prices['slovensko-kuryr-20kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-kuryr-30kg" value="<?php if(!empty($zasilkovna_prices['slovensko-kuryr-30kg'])){  echo $zasilkovna_prices['slovensko-kuryr-30kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 40kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-kuryr-40kg" value="<?php if(!empty($zasilkovna_prices['slovensko-kuryr-40kg'])){  echo $zasilkovna_prices['slovensko-kuryr-40kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 50kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-kuryr-50kg" value="<?php if(!empty($zasilkovna_prices['slovensko-kuryr-50kg'])){  echo $zasilkovna_prices['slovensko-kuryr-50kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-kuryr-dobirka" value="<?php if(!empty($zasilkovna_prices['slovensko-kuryr-dobirka'])){  echo $zasilkovna_prices['slovensko-kuryr-dobirka']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Příplatek za pojištění','zasilkovna'); ?></td>
      <td><input type="text" name="slovensko-kuryr-pojisteni" value="<?php if(!empty($zasilkovna_prices['slovensko-kuryr-pojisteni'])){  echo $zasilkovna_prices['s-pojisteni']; } ?>"></td>
    </tr>
   </table>