<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Polská pošta','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
     <tr>
      <td><?php _e('Zásilka do 1kg','zasilkovna'); ?></td>
      <td><input type="text" name="poland-pl-1kg" value="<?php if(!empty($zasilkovna_prices['poland-pl-1kg'])){  echo $zasilkovna_prices['poland-pl-1kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 2kg','zasilkovna'); ?></td>
      <td><input type="text" name="poland-pl-2kg" value="<?php if(!empty($zasilkovna_prices['poland-pl-2kg'])){  echo $zasilkovna_prices['poland-pl-2kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="poland-pl-5kg" value="<?php if(!empty($zasilkovna_prices['poland-pl-5kg'])){  echo $zasilkovna_prices['poland-pl-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="poland-pl-10kg" value="<?php if(!empty($zasilkovna_prices['poland-pl-10kg'])){  echo $zasilkovna_prices['poland-pl-10kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 15kg','zasilkovna'); ?></td>
      <td><input type="text" name="poland-pl-15kg" value="<?php if(!empty($zasilkovna_prices['poland-pl-15kg'])){  echo $zasilkovna_prices['poland-pl-15kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 20kg','zasilkovna'); ?></td>
      <td><input type="text" name="poland-pl-20kg" value="<?php if(!empty($zasilkovna_prices['poland-pl-20kg'])){  echo $zasilkovna_prices['poland-pl-20kg']; } ?>"></td>
    </tr>
     <tr>
      <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
      <td><input type="text" name="poland-pl-dobirka" value="<?php if(!empty($zasilkovna_prices['poland-pl-dobirka'])){  echo $zasilkovna_prices['poland-pl-dobirka']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Příplatek za pojištění','zasilkovna'); ?></td>
      <td><input type="text" name="poland-pl-pojisteni" value="<?php if(!empty($zasilkovna_prices['poland-pl-pojisteni'])){  echo $zasilkovna_prices['poland-pl-pojisteni']; } ?>"></td>
    </tr>
   </table>
   