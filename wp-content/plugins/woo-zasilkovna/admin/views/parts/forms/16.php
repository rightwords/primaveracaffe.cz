<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Slovenská pošta','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
    <tr>
      <td><?php _e('Zásilka do 100 g','zasilkovna'); ?></td>
      <td><input type="text" name="slovenska-posta-100g" value="<?php if(!empty($zasilkovna_prices['slovenska-posta-100g'])){  echo $zasilkovna_prices['slovenska-posta-100g']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 1kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovenska-posta-1kg" value="<?php if(!empty($zasilkovna_prices['slovenska-posta-1kg'])){  echo $zasilkovna_prices['slovenska-posta-1kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 2kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovenska-posta-2kg" value="<?php if(!empty($zasilkovna_prices['slovenska-posta-2kg'])){  echo $zasilkovna_prices['slovenska-posta-2kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovenska-posta-5kg" value="<?php if(!empty($zasilkovna_prices['slovenska-posta-5kg'])){  echo $zasilkovna_prices['slovenska-posta-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovenska-posta-10kg" value="<?php if(!empty($zasilkovna_prices['slovenska-posta-10kg'])){  echo $zasilkovna_prices['slovenska-posta-10kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 15kg','zasilkovna'); ?></td>
      <td><input type="text" name="slovenska-posta-15kg" value="<?php if(!empty($zasilkovna_prices['slovenska-posta-15kg'])){  echo $zasilkovna_prices['slovenska-posta-15kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
      <td><input type="text" name="slovenska-posta-dobirka" value="<?php if(!empty($zasilkovna_prices['slovenska-posta-dobirka'])){  echo $zasilkovna_prices['slovenska-posta-dobirka']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Příplatek za pojištění','zasilkovna'); ?></td>
      <td><input type="text" name="slovenska-posta-pojisteni" value="<?php if(!empty($zasilkovna_prices['slovenska-posta-pojisteni'])){  echo $zasilkovna_prices['slovenska-posta-pojisteni']; } ?>"></td>
    </tr>
   </table>