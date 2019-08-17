<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Česká pošta - balík na poštu','zasilkovna'); ?></th> 
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
     <tr>
      <td><?php _e('Zásilka do 2kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-2kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-2kg'])){  echo $zasilkovna_prices['ceska-posta-cz-2kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 3kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-3kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-3kg'])){  echo $zasilkovna_prices['ceska-posta-cz-3kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 4kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-4kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-4kg'])){  echo $zasilkovna_prices['ceska-posta-cz-4kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-5kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-5kg'])){  echo $zasilkovna_prices['ceska-posta-cz-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-10kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-10kg'])){  echo $zasilkovna_prices['ceska-posta-cz-10kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-30kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-30kg'])){  echo $zasilkovna_prices['ceska-posta-cz-30kg']; } ?>"></td>
    </tr> 
    <tr>
      <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-dobirka" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-dobirka'])){  echo $zasilkovna_prices['ceska-posta-cz-dobirka']; } ?>"></td>
    </tr> 
    <tr>
      <td><?php _e('Pojištění','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-pojisteni" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-pojisteni'])){  echo $zasilkovna_prices['ceska-posta-cz-pojisteni']; } ?>"></td>
    </tr>    
   </table>