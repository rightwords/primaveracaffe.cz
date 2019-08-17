<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">2, 3, 4 5 10 30
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Česká Pošta - balík do balíkovny','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
     <tr>
      <td><?php _e('Zásilka do 2kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-balikovna-2kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-balikovna-2kg'])){  echo $zasilkovna_prices['ceska-posta-cz-balikovna-2kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 3kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-balikovna-3kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-balikovna-3kg'])){  echo $zasilkovna_prices['ceska-posta-cz-balikovna-3kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 4kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-balikovna-4kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-balikovna-4kg'])){  echo $zasilkovna_prices['ceska-posta-cz-balikovna-4kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-balikovna-5kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-balikovna-5kg'])){  echo $zasilkovna_prices['ceska-posta-cz-balikovna-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-balikovna-10kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-balikovna-10kg'])){  echo $zasilkovna_prices['ceska-posta-cz-balikovna-10kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-balikovna-30kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-balikovna-30kg'])){  echo $zasilkovna_prices['ceska-posta-cz-balikovna-30kg']; } ?>"></td>
    </tr> 
    <tr>
      <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-balikovna-dobirka" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-balikovna-dobirka'])){  echo $zasilkovna_prices['ceska-posta-cz-balikovna-dobirka']; } ?>"></td>
    </tr> 
    <tr>
      <td><?php _e('Pojištění','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-balikovna-pojisteni" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-balikovna-pojisteni'])){  echo $zasilkovna_prices['ceska-posta-cz-balikovna-pojisteni']; } ?>"></td>
    </tr>    
   </table>