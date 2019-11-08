<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>
  
    <table class="table-bordered">
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Maďarsko Transoflex','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
     <tr>
      <td><?php _e('Zásilka do 1kg','zasilkovna'); ?></td>
      <td><input type="text" name="transoflex-hu-1kg" value="<?php if(!empty($zasilkovna_prices['transoflex-hu-1kg'])){  echo $zasilkovna_prices['transoflex-hu-1kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 2kg','zasilkovna'); ?></td>
      <td><input type="text" name="transoflex-hu-2kg" value="<?php if(!empty($zasilkovna_prices['transoflex-hu-2kg'])){  echo $zasilkovna_prices['transoflex-hu-2kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="transoflex-hu-5kg" value="<?php if(!empty($zasilkovna_prices['transoflex-hu-5kg'])){  echo $zasilkovna_prices['transoflex-hu-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="transoflex-hu-10kg" value="<?php if(!empty($zasilkovna_prices['transoflex-hu-10kg'])){  echo $zasilkovna_prices['transoflex-hu-10kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 15kg','zasilkovna'); ?></td>
      <td><input type="text" name="transoflex-hu-15kg" value="<?php if(!empty($zasilkovna_prices['transoflex-hu-15kg'])){  echo $zasilkovna_prices['transoflex-hu-15kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 30kg','zasilkovna'); ?></td>
      <td><input type="text" name="transoflex-hu-30kg" value="<?php if(!empty($zasilkovna_prices['transoflex-hu-30kg'])){  echo $zasilkovna_prices['transoflex-hu-30kg']; } ?>"></td>
    </tr>
   </table>
   