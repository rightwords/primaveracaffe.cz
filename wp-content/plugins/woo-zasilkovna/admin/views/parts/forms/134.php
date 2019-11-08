<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
        <tr>
            <th colspan="2" class="cena_top"><?php _e('Expresní doručení Ostrava','zasilkovna'); ?></th>
        </tr>
        <tr>
            <th><?php _e('Položka','zasilkovna'); ?></th>
            <th><?php _e('Cena','zasilkovna'); ?></th>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
            <td><input type="text" name="express-ostrava-5kg" value="<?php if(!empty($zasilkovna_prices['express-ostrava-5kg'])){  echo $zasilkovna_prices['express-ostrava-5kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
            <td><input type="text" name="express-ostrava-10kg" value="<?php if(!empty($zasilkovna_prices['express-ostrava-10kg'])){  echo $zasilkovna_prices['express-ostrava-10kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
            <td><input type="text" name="express-ostrava-30kg" value="<?php if(!empty($zasilkovna_prices['express-ostrava-30kg'])){  echo $zasilkovna_prices['express-ostrava-30kg']; } ?>"></td>
        </tr>
         <tr>
            <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
            <td><input type="text" name="express-ostrava-dobirka" value="<?php if(!empty($zasilkovna_prices['express-ostrava-dobirka'])){  echo $zasilkovna_prices['express-ostrava-dobirka']; } ?>"></td>
        </tr> 
        <tr>
            <td><?php _e('Pojištění','zasilkovna'); ?></td>
            <td><input type="text" name="express-ostrava-pojisteni" value="<?php if(!empty($zasilkovna_prices['express-ostrava-pojisteni'])){  echo $zasilkovna_prices['express-ostrava-pojisteni']; } ?>"></td>
        </tr>        
    </table>
   