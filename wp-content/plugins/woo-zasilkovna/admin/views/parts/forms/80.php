<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>

    <table class="table-bordered">
        <tr>
            <th colspan="2" class="cena_top"><?php _e('Rakouská pošta','zasilkovna'); ?></th>
        </tr>
        <tr>
            <th><?php _e('Položka','zasilkovna'); ?></th>
            <th><?php _e('Cena','zasilkovna'); ?></th>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 1kg','zasilkovna'); ?></td>
            <td><input type="text" name="austria-at-1kg" value="<?php if(!empty($zasilkovna_prices['austria-at-1kg'])){  echo $zasilkovna_prices['austria-at-1kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 2kg','zasilkovna'); ?></td>
            <td><input type="text" name="austria-at-2kg" value="<?php if(!empty($zasilkovna_prices['austria-at-2kg'])){  echo $zasilkovna_prices['austria-at-2kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
            <td><input type="text" name="austria-at-5kg" value="<?php if(!empty($zasilkovna_prices['austria-at-5kg'])){  echo $zasilkovna_prices['austria-at-5kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
            <td><input type="text" name="austria-at-10kg" value="<?php if(!empty($zasilkovna_prices['austria-at-10kg'])){  echo $zasilkovna_prices['austria-at-10kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Dobírka','zasilkovna'); ?></td>
            <td><input type="text" name="austria-at-dobirka" value="<?php if(!empty($zasilkovna_prices['austria-at-dobirka'])){  echo $zasilkovna_prices['austria-at-dobirka']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Pojištění','zasilkovna'); ?></td>
            <td><input type="text" name="austria-at-pojisteni" value="<?php if(!empty($zasilkovna_prices['austria-at-pojisteni'])){  echo $zasilkovna_prices['austria-at-pojisteni']; } ?>"></td>
        </tr>
    </table>