<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
        <tr>
            <th colspan="2" class="cena_top"><?php _e('In-time','zasilkovna'); ?></th>
        </tr>
        <tr>
            <th><?php _e('Položka','zasilkovna'); ?></th>
            <th><?php _e('Cena','zasilkovna'); ?></th>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
            <td><input type="text" name="in-time-30kg" value="<?php if(!empty($zasilkovna_prices['in-time-30kg'])){  echo $zasilkovna_prices['in-time-30kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 50kg','zasilkovna'); ?></td>
            <td><input type="text" name="in-time-50kg" value="<?php if(!empty($zasilkovna_prices['in-time-50kg'])){  echo $zasilkovna_prices['in-time-50kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
            <td><input type="text" name="in-time-cz-dobirka" value="<?php if(!empty($zasilkovna_prices['in-time-cz-dobirka'])){  echo $zasilkovna_prices['in-time-cz-dobirka']; } ?>"></td>
        </tr> 
        <tr>
            <td><?php _e('Pojištění','zasilkovna'); ?></td>
            <td><input type="text" name="in-time-cz-pojisteni" value="<?php if(!empty($zasilkovna_prices['in-time-cz-pojisteni'])){  echo $zasilkovna_prices['in-time-cz-pojisteni']; } ?>"></td>
        </tr>        
    </table>
   