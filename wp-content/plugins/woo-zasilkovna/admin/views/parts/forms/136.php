<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
        <tr>
            <th colspan="2" class="cena_top"><?php _e('Expresní doručení Brno','zasilkovna'); ?></th>
        </tr>
        <tr>
            <th><?php _e('Položka','zasilkovna'); ?></th>
            <th><?php _e('Cena','zasilkovna'); ?></th>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
            <td><input type="text" name="express-brno-5kg" value="<?php if(!empty($zasilkovna_prices['express-brno-5kg'])){  echo $zasilkovna_prices['express-brno-5kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
            <td><input type="text" name="express-brno-10kg" value="<?php if(!empty($zasilkovna_prices['express-brno-10kg'])){  echo $zasilkovna_prices['express-brno-10kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
            <td><input type="text" name="express-brno-30kg" value="<?php if(!empty($zasilkovna_prices['express-brno-30kg'])){  echo $zasilkovna_prices['express-brno-30kg']; } ?>"></td>
        </tr>
         <tr>
            <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
            <td><input type="text" name="express-brno-dobirka" value="<?php if(!empty($zasilkovna_prices['express-brno-dobirka'])){  echo $zasilkovna_prices['express-brno-dobirka']; } ?>"></td>
        </tr> 
        <tr>
            <td><?php _e('Pojištění','zasilkovna'); ?></td>
            <td><input type="text" name="express-brno-pojisteni" value="<?php if(!empty($zasilkovna_prices['express-brno-pojisteni'])){  echo $zasilkovna_prices['express-brno-pojisteni']; } ?>"></td>
        </tr>        
    </table>
   