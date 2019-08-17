<?php $zasilkovna_prices = get_option('zasilkovna_prices'); ?>

<div class="t-col-9">
  <div class="woo-box box-info">
    <div class="box-header">
      <h3 class="box-title"><?php _e('Nastavení cen za dopravu','zasilkovna'); ?></h3>
    </div>
    <div class="box-body">
	 <form method="post" action="" class="setting-form">
   <table class="table-bordered">
     <tr>
      <th colspan="2" class="cena_top"><?php _e('Kurzy měn','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Kurz','zasilkovna'); ?></th>
     </tr>
     <tr>
      <td><?php _e('Euro','zasilkovna'); ?></td>
      <td><input type="text" name="kurz-euro" value="<?php if(!empty($zasilkovna_prices['kurz-euro'])){  echo $zasilkovna_prices['kurz-euro']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Forint','zasilkovna'); ?></td>
      <td><input type="text" name="kurz-forint" value="<?php if(!empty($zasilkovna_prices['kurz-forint'])){  echo $zasilkovna_prices['kurz-forint']; } ?>"></td>
    </tr>
     <tr>
      <td><?php _e('Zlotý','zasilkovna'); ?></td>
      <td><input type="text" name="kurz-zloty" value="<?php if(!empty($zasilkovna_prices['kurz-zloty'])){  echo $zasilkovna_prices['kurz-zloty']; } ?>"></td>
    </tr>
   </table>	
   

   <input type="hidden" name="services_price" value="ok" />
   <input type="submit" class="button" value="<?php _e('Uložit','zasilkovna'); ?>" />
   </div>
   </form>
  </div>
</div>
