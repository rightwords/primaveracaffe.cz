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
   <table class="table-bordered">
     <tr>
      <th colspan="2" class="cena_top"><?php _e('Zásilkovna Česká republika','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
     <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="zasilkovna-cr-5kg" value="<?php if(!empty($zasilkovna_prices['zasilkovna-cr-5kg'])){  echo $zasilkovna_prices['zasilkovna-cr-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="zasilkovna-cr-10kg" value="<?php if(!empty($zasilkovna_prices['zasilkovna-cr-10kg'])){  echo $zasilkovna_prices['zasilkovna-cr-10kg']; } ?>"></td>
    </tr>
     <tr>
      <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
      <td><input type="text" name="zasilkovna-cr-dobirka" value="<?php if(!empty($zasilkovna_prices['zasilkovna-cr-dobirka'])){  echo $zasilkovna_prices['zasilkovna-cr-dobirka']; } ?>"></td>
    </tr>
   </table>
   <table class="table-bordered">
    <tr>
      <th colspan="2" class="cena_top"><?php _e('Zásilkovna Slovenská republika','zasilkovna'); ?></th>
     </tr>
     <tr>
      <th><?php _e('Položka','zasilkovna'); ?></th>
      <th><?php _e('Cena','zasilkovna'); ?></th>
     </tr>
     <tr>
      <td><?php _e('Zásilka do 5kg','zasilkovna'); ?></td>
      <td><input type="text" name="zasilkovna-sk-5kg" value="<?php if(!empty($zasilkovna_prices['zasilkovna-sk-5kg'])){  echo $zasilkovna_prices['zasilkovna-sk-5kg']; } ?>"></td>
    </tr>
    <tr>
      <td><?php _e('Zásilka do 10kg','zasilkovna'); ?></td>
      <td><input type="text" name="zasilkovna-sk-10kg" value="<?php if(!empty($zasilkovna_prices['zasilkovna-sk-10kg'])){  echo $zasilkovna_prices['zasilkovna-sk-10kg']; } ?>"></td>
    </tr>
     <tr>
      <td><?php _e('Příplatek za dobírku','zasilkovna'); ?></td>
      <td><input type="text" name="zasilkovna-sk-dobirka" value="<?php if(!empty($zasilkovna_prices['zasilkovna-sk-dobirka'])){  echo $zasilkovna_prices['zasilkovna-sk-dobirka']; } ?>"></td>
    </tr>
   </table>
   
   
   
   
   <?php 
            include( '13.php'  ); //Česká pošta - balík na poštu',
            echo '<div class="clear"></div>';
            
            include( '14.php'  ); //Česká pošta - balik do ruky',
            echo '<div class="clear"></div>';
            
             include( '15.php'  ); //Česká pošta - balik do balíkovny',
            echo '<div class="clear"></div>';
            
            include( '153.php' ); //Česká republika InTime',
            echo '<div class="clear"></div>';
            
            include( '633.php' ); //Česká republika DPD',
            echo '<div class="clear"></div>';
            
            include( '136.php' ); //Expresní doručení Brno',
            echo '<div class="clear"></div>';
            
            include( '134.php' ); //Expresní doručení Ostrava',
            echo '<div class="clear"></div>';
            
            include( '257.php' ); //Expresní doručení Praha',
            echo '<div class="clear"></div>';
            
            include( '80.php'  ); //Rakouská pošta',
            echo '<div class="clear"></div>';
            
            include( '111.php' ); //Německá pošta',
            echo '<div class="clear"></div>';
            
            include( '763.php' ); //Maďarská pošta',
            echo '<div class="clear"></div>';
            
            include( '805.php' ); //Maďarsko DPD',
            echo '<div class="clear"></div>';
            
            include( '151.php' ); //Maďarsko Transoflex',
            echo '<div class="clear"></div>';
            
            include( '272.php' ); //Polská pošta',
            echo '<div class="clear"></div>';
            
            include( '590.php' ); //Rumunsko Cargus',
            echo '<div class="clear"></div>';
            
            include( '836.php' ); //Rumunsko DPD',
            echo '<div class="clear"></div>';
            
            include( '131.php' ); //Doručenie na adresu SR',
            echo '<div class="clear"></div>';
            
            include( '132.php' ); //Expresné doručenie Bratislava',
            echo '<div class="clear"></div>';
            
            include( '16.php'  ); //Slovenská pošta',
            echo '<div class="clear"></div>';
            
            include( '149.php' ); //Slovensko kurýr',
            echo '<div class="clear"></div>';
            
            include( '1234.php'); //Bulharsko DPD'
            echo '<div class="clear"></div>';
    ?>
   
   <input type="hidden" name="services_price" value="ok" />
   <input type="submit" class="button" value="<?php _e('Uložit','zasilkovna'); ?>" />
   </div>
   </form>
  </div>
</div>
