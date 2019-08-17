<?php
/**
 * @package   WooCommerce Zásilkovna
 * @author    Woo
 * @license   GPL-2.0+
 * @link      http://woo.cz
 * @copyright 2014 woo
 */
 
?>
   

<form method="post" action="" class="setting-form woo-hlavni-body">	
	<table class="table-bordered zasilkovna-woo">
    
        <?php if(!empty($licence_info)){ ?>
        <tr><td colspan="2"><strong><?php echo $licence_info; ?></strong></td></tr>
        <?php
            delete_option('woo-zasilkovna-info');  
        }
        
        $licence_status = get_option('woo-zasilkovna-licence');
        if(!empty($licence_status)){
            if($licence_status=='active'){ ?>
                <tr><td colspan="2"><strong>Licence je ativována.</strong></td></tr>  
         <?php }
        }
        ?>
    
                <tr class="licence">
      		<th><?php _e('Licenční klíč','zasilkovna'); ?></th>
      		<td>
        		<input type="text" name="licence" value="<?php if(!empty($licence_key)){ echo $licence_key; } ?>">
      		</td>
    	</tr>
    
    	<tr>
      		<th><?php _e('Api klíč','zasilkovna'); ?></th>
      		<td><input type="text" name="api_key" value="<?php if(!empty($zasilkovna_option['api_key'])){  echo $zasilkovna_option['api_key'];} ?>"></td>
    	</tr>
    
    	<tr>
      		<th><?php _e('Api heslo','zasilkovna'); ?></th>
      		<td><input type="text" name="api_password" value="<?php if(!empty($zasilkovna_option['api_password'])){  echo $zasilkovna_option['api_password'];} ?>"></td>
    	</tr>
    	<tr>
      		<th><?php _e('Jméno eshopu','zasilkovna'); ?></th>
      		<td><input type="text" name="nazev_eshopu" value="<?php if(!empty($zasilkovna_option['nazev_eshopu'])){  echo $zasilkovna_option['nazev_eshopu'];} ?>"></td>
    	</tr>
    	
    	<tr>
      		<th><?php _e('Zobrazit České pobočky','zasilkovna'); ?></th>
      		<td><input type="checkbox" name="cz_pobocky" value="cz" <?php if(!empty($zasilkovna_option['cz_pobocky']) && $zasilkovna_option['cz_pobocky'] == 'cz'){  echo 'checked="checked"'; } ?> /></td>
    	</tr>
    	<tr>
      		<th><?php _e('Zobrazit Slovenské pobočky','zasilkovna'); ?></th>
      		<td><input type="checkbox" name="sk_pobocky" value="sk" <?php if(!empty($zasilkovna_option['sk_pobocky']) && $zasilkovna_option['sk_pobocky'] == 'sk'){  echo 'checked="checked"'; } ?> /></td>
    	</tr>
        <tr>
            <th><?php _e('Zobrazit Německé pobočky','zasilkovna'); ?></th>
            <td><input type="checkbox" name="de_pobocky" value="hu" <?php if(!empty($zasilkovna_option['de_pobocky']) && $zasilkovna_option['de_pobocky'] == 'hu'){  echo 'checked="checked"'; } ?> /></td>
        </tr>
        <tr>
            <th><?php _e('Zobrazit Polské pobočky','zasilkovna'); ?></th>
            <td><input type="checkbox" name="pl_pobocky" value="pl" <?php if(!empty($zasilkovna_option['pl_pobocky']) && $zasilkovna_option['pl_pobocky'] == 'pl'){  echo 'checked="checked"'; } ?> /></td>
        </tr>
       <tr>
            <th><?php _e('Zobrazit Rakouské pobočky','zasilkovna'); ?></th>
            <td><input type="checkbox" name="at_pobocky" value="ro" <?php if(!empty($zasilkovna_option['at_pobocky']) && $zasilkovna_option['at_pobocky'] == 'ro'){  echo 'checked="checked"'; } ?> /></td>
        </tr>
        <tr>
      		<th><?php _e('Url ikony pro česko','zasilkovna'); ?></th>
                <td><input type="text" name="icon_url"  value="<?php if(!empty($zasilkovna_option['icon_url'])){  echo $zasilkovna_option['icon_url'];} ?>" ></td>
    	</tr>
    	<tr>
      		<th><?php _e('Url ikony pro slovensko','zasilkovna'); ?></th>
      		<td><input type="text" name="icon_url_sk" value="<?php if(!empty($zasilkovna_option['icon_url_sk'])){  echo $zasilkovna_option['icon_url_sk'];} ?>"></td>
    	</tr>
        <tr>
            <th><?php _e('Url ikony pro německo','zasilkovna'); ?></th>
            <td><input type="text" name="icon_url_de" value="<?php if(!empty($zasilkovna_option['icon_url_de'])){  echo $zasilkovna_option['icon_url_de'];} ?>"></td>
        </tr>
        <tr>
            <th><?php _e('Url ikony pro polsko','zasilkovna'); ?></th>
            <td><input type="text" name="icon_url_pl" value="<?php if(!empty($zasilkovna_option['icon_url_pl'])){  echo $zasilkovna_option['icon_url_pl'];} ?>"></td>
        </tr>
       <tr>
            <th><?php _e('Url ikony pro rakousko','zasilkovna'); ?></th>
            <td><input type="text" name="icon_url_at" value="<?php if(!empty($zasilkovna_option['icon_url_at'])){  echo $zasilkovna_option['icon_url_at'];} ?>"></td>
        </tr>
    	<tr>
      		<th><?php _e('Neodesílat do zásilkovny','zasilkovna'); ?></th>
      		<td><input type="checkbox" name="no_send" value="yes" <?php if(!empty($zasilkovna_option['no_send']) && $zasilkovna_option['no_send'] == 'yes'){  echo 'checked="checked"'; } ?> /></td>
    	</tr>
      	<tr>
      		<th><?php _e('Nastavení odesílání zásilky do Zásilkovny','zasilkovna'); ?></th>
      		<td>
        		<select name="odeslani_zasilky" id="odeslani_zasilky">
          			<option value="thankyou" <?php if(!empty($zasilkovna_option['odeslani_zasilky']) && $zasilkovna_option['odeslani_zasilky'] == 'thankyou'){  echo 'selected="selected"';} ?>><?php _e('Po vytvoření objednávky na děkovné stránce','zasilkovna'); ?></option>
          			<option value="processing" <?php if(!empty($zasilkovna_option['odeslani_zasilky']) && $zasilkovna_option['odeslani_zasilky'] == 'processing'){  echo 'selected="selected"';} ?>><?php _e('Při označení objednávky "Zpracovává se"','zasilkovna'); ?></option>
          			<option value="finished" <?php if(!empty($zasilkovna_option['odeslani_zasilky']) && $zasilkovna_option['odeslani_zasilky'] == 'finished'){  echo 'selected="selected"';} ?>><?php _e('Při označení objednávky "Dokončená"','zasilkovna'); ?></option>
        		</select>
      		</td>
    	</tr>
    	<tr>
      		<th><?php _e('Doprava zdarma','zasilkovna'); ?></th>
      		<td>
        		<select name="doprava_zdarma" id="doprava_zdarma">
          			<option value="all" <?php if(!empty($zasilkovna_option['doprava_zdarma']) && $zasilkovna_option['doprava_zdarma'] == 'all'){  echo 'selected="selected"';} ?>><?php _e('Zdarma budou všechny druhy dopravy','zasilkovna'); ?></option>
          			<option value="zasilkovna" <?php if(!empty($zasilkovna_option['doprava_zdarma']) && $zasilkovna_option['doprava_zdarma'] == 'zasilkovna'){  echo 'selected="selected"';} ?>><?php _e('Zdarma pouze dopravy Zásilkovny','zasilkovna'); ?></option>
          			<option value="default" <?php if(!empty($zasilkovna_option['doprava_zdarma']) && $zasilkovna_option['doprava_zdarma'] == 'default'){  echo 'selected="selected"';} ?>><?php _e('Doprava zdarma ve výběru dopravy','zasilkovna'); ?></option>
          			<option value="only" <?php if(!empty($zasilkovna_option['doprava_zdarma']) && $zasilkovna_option['doprava_zdarma'] == 'only'){  echo 'selected="selected"';} ?>><?php _e('Odstranit dopravu zdarma - ceny zústanou','zasilkovna'); ?></option>
       			</select>
      		</td>
    	</tr>
    	
        <!--<tr>
            <th><?php _e('Odesílat upozornění emailem o chybě vytvoření zásilky','zasilkovna'); ?></th>
            <td><input type="checkbox" name="error_email" value="email" <?php if(!empty($zasilkovna_option['error_email']) && $zasilkovna_option['error_email'] == 'email'){  echo 'checked="checked"'; } ?> /></td>
        </tr>-->
   	</table>
   	<input type="hidden" name="update" value="ok" />
   	<input type="submit" class="button" <?php _e('Save','zasilkovna'); ?> value="<?php _e('Uložit nastavení','zasilkovna'); ?>" />
</form>

<div class="zasilkovna-kurz">
        <h3 class="kurz-btn"><span class=" fa fa-plus-square"></span> Zobrazit nastavení kurzu </h3>
<form method="post" action="" class="setting-form zasilkovna-woo-kurz">
    <table class="table-bordered zasilkovna-woo kurz">
        <tr>
            <th colspan="2" class="cena_top"><?php _e('Kurzy měny','zasilkovna'); ?></th>
        </tr>
        <!--<tr>
            <th><?php _e('Položka','zasilkovna'); ?></th>
            <th><?php _e('Kurz','zasilkovna'); ?></th>
        </tr>-->
        <tr>
            <td><?php _e('Euro','zasilkovna'); ?></td>
            <td><input type="text" name="kurz-euro" value="<?php if(!empty($zasilkovna_prices['kurz-euro'])){  echo $zasilkovna_prices['kurz-euro']; } ?>"></td>
        </tr>
       <!-- <tr>
            <td><?php _e('Forint','zasilkovna'); ?></td>
            <td><input type="text" name="kurz-forint" value="<?php if(!empty($zasilkovna_prices['kurz-forint'])){  echo $zasilkovna_prices['kurz-forint']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Zlotý','zasilkovna'); ?></td>
            <td><input type="text" name="kurz-zloty" value="<?php if(!empty($zasilkovna_prices['kurz-zloty'])){  echo $zasilkovna_prices['kurz-zloty']; } ?>"></td>
        </tr>
        <tr>
            <td><?php _e('Lei','zasilkovna'); ?></td>
            <td><input type="text" name="kurz-lei" value="<?php if(!empty($zasilkovna_prices['kurz-lei'])){  echo $zasilkovna_prices['kurz-lei']; } ?>"></td>
        </tr>-->
    </table> 
   
    <input type="hidden" name="services_price" value="ok" />
    <input type="hidden" name="services_price_kurzy" value="ok" />
    <input type="submit" class="button" value="<?php _e('Uložit kurzy','zasilkovna'); ?>" />
   
</form>
</div>
        <div class="zasilkovna-mena">
        <h3 class="mena-btn"><span class=" fa fa-plus-square"></span> Zobrazit nastavení měny </h3>
<form method="post" action="" class="setting-form zasilkovna-woo-mena"> 
    <table class="table-bordered zasilkovna-woo mena">
        <tr>
            <th colspan="2" class="cena_top"><?php _e('Nastavení měny u jednotlivých států','zasilkovna'); ?></th>
        </tr>
       <!-- <tr>
            <th><?php _e('Země','zasilkovna'); ?></th>
            <th><?php _e('Povolená měna','zasilkovna'); ?></th>
        </tr>-->
        <!--<tr>
            <th>Bulharsko</th>
            <td>
                <input type="hidden" name="country_currency_bg" value="CZK" />CZK
            </td>
        </tr>-->
        <tr>
            <th>Česká republika</th>
            <td>
                <input type="hidden" name="country_currency_cz" value="CZK" />CZK
            </td>
        </tr>
        <!--<tr>
            <th>Maďarsko</th>
            <td>
                <select name="country_currency_hu">
                    <option value="CZK" <?php if( !empty( $country_currency['country_currency_hu'] ) && $country_currency['country_currency_hu'] == 'CZK' ){ echo 'selected="selected"'; } ?>>CZK</option>
                    <option value="HUF" <?php if( !empty( $country_currency['country_currency_hu'] ) && $country_currency['country_currency_hu'] == 'HUF' ){ echo 'selected="selected"'; } ?>>HUF</option>
                </select>                
            </td>
        </tr>-->
        <tr>
            <th>Německo</th>
            <td>
                <select name="country_currency_de">
                    <option value="CZK" <?php if( !empty( $country_currency['country_currency_de'] ) && $country_currency['country_currency_de'] == 'CZK' ){ echo 'selected="selected"'; } ?>>CZK</option>
                    <option value="EUR" <?php if( !empty( $country_currency['country_currency_de'] ) && $country_currency['country_currency_de'] == 'EUR' ){ echo 'selected="selected"'; } ?>>EUR</option>
                </select>                
            </td>
        </tr>
        <tr>
            <th>Polsko</th>
            <td>
                <input type="hidden" name="country_currency_pl" value="CZK" />CZK
            </td>
        </tr>
        <tr>
            <th>Rakousko</th>
            <td>
                <select name="country_currency_at">
                    <option value="CZK" <?php if( !empty( $country_currency['country_currency_at'] ) && $country_currency['country_currency_at'] == 'CZK' ){ echo 'selected="selected"'; } ?>>CZK</option>
                    <option value="EUR" <?php if( !empty( $country_currency['country_currency_at'] ) && $country_currency['country_currency_at'] == 'EUR' ){ echo 'selected="selected"'; } ?>>EUR</option>
                </select> 
            </td>
        </tr>
<!--        <tr>
            <th>Rumunsko</th>
            <td>
                <input type="hidden" name="country_currency_ru" value="CZK" />CZK
            </td>
        </tr>-->
        <tr>
            <th>Slovensko</th>
            <td>
                <select name="country_currency_sk">
                    <option value="CZK" <?php if( !empty( $country_currency['country_currency_sk'] ) && $country_currency['country_currency_sk'] == 'CZK' ){ echo 'selected="selected"'; } ?>>CZK</option>
                    <option value="EUR" <?php if( !empty( $country_currency['country_currency_sk'] ) && $country_currency['country_currency_sk'] == 'EUR' ){ echo 'selected="selected"'; } ?>>EUR</option>
                </select> 
            </td>
        </tr>
        
    </table>
    <input type="hidden" name="country_currency" value="ok" />
    <input type="submit" class="button" value="<?php _e('Uložit měny','zasilkovna'); ?>" />
</form>
        
        </div>
