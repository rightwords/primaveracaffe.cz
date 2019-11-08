<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>

    <table class="table-bordered">
        <tr>
            <th colspan="2" class="cena_top"><?php _e('Německá pošta','zasilkovna'); ?></th>
        </tr>
        <tr>
            <th><?php _e('Položka','zasilkovna'); ?></th>
            <th><?php _e('Cena','zasilkovna'); ?></th>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 1kg','zasilkovna'); ?></td>
            <td><input type="text" name="germany-de-1kg" value="<?php if(!empty($zasilkovna_prices['germany-de-1kg'])){  echo $zasilkovna_prices['germany-de-1kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 2kg','zasilkovna'); ?></td>
            <td><input type="text" name="germany-de-2kg" value="<?php if(!empty($zasilkovna_prices['germany-de-2kg'])){  echo $zasilkovna_prices['germany-de-2kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
            <td><input type="text" name="germany-de-5kg" value="<?php if(!empty($zasilkovna_prices['germany-de-5kg'])){  echo $zasilkovna_prices['germany-de-5kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
            <td><input type="text" name="germany-de-10kg" value="<?php if(!empty($zasilkovna_prices['germany-de-10kg'])){  echo $zasilkovna_prices['germany-de-10kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zásilka do 20kg','zasilkovna'); ?></td>
            <td><input type="text" name="germany-de-20kg" value="<?php if(!empty($zasilkovna_prices['germany-de-20kg'])){  echo $zasilkovna_prices['germany-de-20kg']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
            <td><input type="text" name="germany-de-dobirka" value="<?php if(!empty($zasilkovna_prices['germany-de-dobirka'])){  echo $zasilkovna_prices['germany-de-dobirka']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Příplatek za pojištění','zasilkovna'); ?></td>
            <td><input type="text" name="germany-de-pojisteni" value="<?php if(!empty($zasilkovna_prices['germany-de-pojisteni'])){  echo $zasilkovna_prices['germany-de-pojisteni']; } ?>"></td>
        </tr>
    </table>