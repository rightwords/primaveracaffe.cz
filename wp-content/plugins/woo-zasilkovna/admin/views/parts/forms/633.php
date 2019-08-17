<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
        <tr>
            <th colspan="2" class="cena_top"><?php _e('Česká republika DPD','zasilkovna'); ?></th>
        </tr>
        <tr>
            <th><?php _e('Položka','zasilkovna'); ?></th>
            <th><?php _e('Cena','zasilkovna'); ?></th>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
            <td><input type="text" name="dpd-cz-5kg" value="<?php if(!empty($zasilkovna_prices['dpd-cz-5kg'])){  echo $zasilkovna_prices['dpd-cz-5kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
            <td><input type="text" name="dpd-cz-10kg" value="<?php if(!empty($zasilkovna_prices['dpd-cz-10kg'])){  echo $zasilkovna_prices['dpd-cz-10kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
            <td><input type="text" name="dpd-cz-30kg" value="<?php if(!empty($zasilkovna_prices['dpd-cz-30kg'])){  echo $zasilkovna_prices['dpd-cz-30kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 50kg','zasilkovna'); ?></td>
            <td><input type="text" name="dpd-cz-50kg" value="<?php if(!empty($zasilkovna_prices['dpd-cz-50kg'])){  echo $zasilkovna_prices['dpd-cz-50kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
            <td><input type="text" name="dpd-cz-dobirka" value="<?php if(!empty($zasilkovna_prices['dpd-cz-dobirka'])){  echo $zasilkovna_prices['dpd-cz-dobirka']; } ?>"></td>
        </tr> 
        <tr>
            <td><?php _e('Pojištění','zasilkovna'); ?></td>
            <td><input type="text" name="dpd-cz-pojisteni" value="<?php if(!empty($zasilkovna_prices['dpd-cz-pojisteni'])){  echo $zasilkovna_prices['dpd-cz-pojisteni']; } ?>"></td>
        </tr>        
    </table>
   