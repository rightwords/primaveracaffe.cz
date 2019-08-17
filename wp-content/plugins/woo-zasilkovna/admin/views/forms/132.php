<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Expresné doručenie Bratislava','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
     <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="express-bratislava-5kg" value="<?php if(!empty($zasilkovna_prices['express-bratislava-5kg'])){  echo $zasilkovna_prices['express-bratislava-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="express-bratislava-10kg" value="<?php if(!empty($zasilkovna_prices['express-bratislava-10kg'])){  echo $zasilkovna_prices['express-bratislava-10kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
      <td><input type="text" name="express-bratislava-30kg" value="<?php if(!empty($zasilkovna_prices['express-bratislava-30kg'])){  echo $zasilkovna_prices['express-bratislava-30kg']; } ?>"></td>
    </tr>
     <tr>
      <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
      <td><input type="text" name="express-bratislava-dobirka" value="<?php if(!empty($zasilkovna_prices['express-bratislava-dobirka'])){  echo $zasilkovna_prices['express-bratislava-dobirka']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Příplatek za pojištění','zasilkovna'); ?></td>
      <td><input type="text" name="express-bratislava-pojisteni" value="<?php if(!empty($zasilkovna_prices['express-bratislava-pojisteni'])){  echo $zasilkovna_prices['express-bratislava-pojisteni']; } ?>"></td>
    </tr>
   </table>
   