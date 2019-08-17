<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">2, 3, 4 5 10 30
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Česká Pošta - balík do ruky','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
     <tr>
      <td><?php _e('Zásilka do 2kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-ruka-2kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-ruka-2kg'])){  echo $zasilkovna_prices['ceska-posta-cz-ruka-2kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 3kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-ruka-3kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-ruka-3kg'])){  echo $zasilkovna_prices['ceska-posta-cz-ruka-3kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 4kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-ruka-4kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-ruka-4kg'])){  echo $zasilkovna_prices['ceska-posta-cz-ruka-4kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-ruka-5kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-ruka-5kg'])){  echo $zasilkovna_prices['ceska-posta-cz-ruka-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-ruka-10kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-ruka-10kg'])){  echo $zasilkovna_prices['ceska-posta-cz-ruka-10kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-ruka-30kg" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-ruka-30kg'])){  echo $zasilkovna_prices['ceska-posta-cz-ruka-30kg']; } ?>"></td>
    </tr> 
    <tr>
      <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-ruka-dobirka" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-ruka-dobirka'])){  echo $zasilkovna_prices['ceska-posta-cz-ruka-dobirka']; } ?>"></td>
    </tr> 
    <tr>
      <td><?php _e('Pojištění','zasilkovna'); ?></td>
      <td><input type="text" name="ceska-posta-cz-ruka-pojisteni" value="<?php if(!empty($zasilkovna_prices['ceska-posta-cz-ruka-pojisteni'])){  echo $zasilkovna_prices['ceska-posta-cz-ruka-pojisteni']; } ?>"></td>
    </tr>    
   </table>