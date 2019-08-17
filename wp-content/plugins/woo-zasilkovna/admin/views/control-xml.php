<div class="wrap">
  <h2><?php _e('Zásilkovna nastavení','zasilkovna'); ?></h2>
  
  
<?php 
//Save main setting
$this->save_setting();


/**
 * Save country currency
 *
 */ 

if( isset( $_POST['country_currency'] ) ){ 

    $country_currency = array();
    $default_array = array(
//            'country_currency_bg',
//            'country_currency_hu',
            'country_currency_de',
            'country_currency_pl',
            'country_currency_at',
            'country_currency_ru',
            'country_currency_sk'
        );


        foreach( $default_array as $item ){
            if( !empty( $_POST[$item] ) ){  
                $country_currency[$item] = sanitize_text_field( $_POST[$item] );
            }else{
                $country_currency[$item] = 'CZK';   
            }
        }
        
    update_option( 'zasilkovna_country_currency', $country_currency );

}

/**
 * Save services data
 *
 */  
if(isset($_POST['services'])){ 

    $zasilkovna_services = array();
        foreach( Zasilkovna_Helper::set_shipping_services() as $key => $service ){
            if(!empty($_POST['service-label-'.$key])){  
              $zasilkovna_services['service-label-'.$key]  = $_POST['service-label-'.$key]; 
              //WPML save option fix
              if (function_exists ( 'icl_register_string' )){
                icl_register_string( 'Zasilkovna', $zasilkovna_services['service-label-'.$key], $_POST['service-label-'.$key] );
              }
            }
            if(!empty($_POST['service-active-'.$key])){ $zasilkovna_services['service-active-'.$key] = $_POST['service-active-'.$key]; }            
        }

    update_option('zasilkovna_services',$zasilkovna_services);
    //wp_redirect(home_url().'/wp-admin/admin.php?page=control_xml');
}

//Save services price
$this->save_service_prices();

//Get and save branches
if(isset($_GET['check'])){

    Zasilkovna_Pobocky::get_branches();

}

$zasilkovna_mista     = get_option( 'zasilkovna_mista');
$zasilkovna_option    = get_option( 'zasilkovna_option');
$zasilkovna_mista_cz  = get_option( 'zasilkovna_mista_cz');
$zasilkovna_mista_sk  = get_option( 'zasilkovna_mista_sk');
$zasilkovna_mista_hu  = get_option( 'zasilkovna_mista_hu');
$zasilkovna_mista_pl  = get_option( 'zasilkovna_mista_pl');
$zasilkovna_mista_ro  = get_option( 'zasilkovna_mista_ro');

$licence_key  = get_option('woo-zasilkovna-licence-key');
$licence_info = get_option('woo-zasilkovna-info');
global $lic;

$zasilkovna_services = get_option('zasilkovna_services');
$country_currency = get_option('zasilkovna_country_currency');

$zasilkovna_prices = get_option( 'zasilkovna_prices' );

?>  
 
   
 
  
<div class="w-col-12 woo-zasilkovna-content">
     
       <div class="w-col-12">
           <div class="woo-box box-info" style="display:none;">
      <div class="box-header">
        <h3 class="box-title h3-licence"><?php _e('Zadejte licenční klíč','zasilkovna'); ?></h3>
      </div>
      <div class="box-body licence-<?php if(!empty($licence_info)){ ?>
        <?php
            delete_option('woo-zasilkovna-info');  
        }
        $licence_status = get_option('woo-zasilkovna-licence');
        if(!empty($licence_status)){
            if($licence_status=='active'){ ?>aktivni<?php }
        } else {
            ?>neaktivni<?php } 
        ?> ">
         <div class="navod">
           <h2 class="dashicons-before dashicons-admin-page h2-licence"><a class="licence" target="_blank" href="<?php echo plugins_url(); ?>/woo-zasilkovna/Instalace-a-licence.pdf ">Návod a licence pro plugin Woo zásilkovnu</a></h2>
    
      </div>
               <?php if(!empty($licence_info)){ ?>
     <p><strong><?php echo $licence_info; ?></strong></p>
  <?php
  delete_option('woo-zasilkovna-info');  
    }
  ?>
  <?php if(!empty($lic)){ ?>
     <?php $url = home_url(); ?>
              <p><strong>Vaše stránky: <a href="<?php echo esc_url( $url ); ?>"><?php echo esc_url( $url ); ?></a>.</strong></p>

  <?php
    }

    $blog_id = get_current_blog_id();
        if( is_multisite() ){
            $xml_file_id = '_'.$blog_id;
            $xml_file_get = '?blog_id='.$blog_id;
            $xml_file_update = '&blog_id='.$blog_id;
        }else{
            $xml_file_id = '';
            $xml_file_get = '';
            $xml_file_update = '';
        }

  ?>
 <?php if(!empty($lic)){ ?><div class="pass-blur active"> <?php
            }
            ?>
  <form method="post" style="margin-bottom:10px;">
    <input type="text" name="licence" id="licence" style="width:400px;" value="<?php if(!empty($licence_key)){ echo $licence_key; } ?>" />
    <input type="hidden" name="control" value="ok" />
    <input type="submit" class="button licence" value="Aktivovat licenci" />
  </form>
     
     <?php if(!empty($lic)){ ?></div> <?php
            }
            ?>
     
      </div>
    </div>
    <div class="navod">
           <h2 class="dashicons-before dashicons-admin-page h2-licence"><a class="licence" target="_blank" href="<?php echo plugins_url(); ?>/woo-zasilkovna/Instalace-a-licence.pdf ">Návod a licence pro plugin Woo zásilkovnu</a></h2>
    
      </div>
       
  <div class="woo-box box-info zasilkovna-obsah <?php if(!empty($licence_info)){ ?>
        <?php
            delete_option('woo-zasilkovna-info');  
        }
        $licence_status = get_option('woo-zasilkovna-licence');
        if(!empty($licence_status)){
            if($licence_status=='active'){ ?>aktivni<?php }
        } else {
            ?>neaktivni<?php } 
        ?> ">  
    <div class="box-header">
      <h3 class="box-title"><?php _e('Nastavení','zasilkovna'); ?></h3>
      
    </div>
      
    <div class="box-body">
        
        

        <ul class="zasilkovna-menu">
            <li>
                <a href="<?php echo admin_url(); ?>admin.php?page=zasilkovna&form=main-setting" class="<?php $this->get_active( 'main-setting' ); ?>">
                    <?php _e('Nastavení zásilkovny','zasilkovna'); ?>                
                </a>
            </li>
            <li>
                <a href="<?php echo admin_url(); ?>admin.php?page=zasilkovna&form=shipping-setting" class="<?php $this->get_active( 'shipping-setting' ); ?>">
                    <?php _e('Nastavení dopravců','zasilkovna'); ?>    
                </a>
            </li>
            <li>
                <a href="<?php echo admin_url(); ?>admin.php?page=zasilkovna&form=cr" class="<?php $this->get_active( 'cr' ); ?>">
                    <?php _e('Česko','zasilkovna'); ?>                
                </a >
            </li>
            <li>
                <a href="<?php echo admin_url(); ?>admin.php?page=zasilkovna&form=sk" class="<?php $this->get_active( 'sk' ); ?>">
                    <?php _e('Slovensko','zasilkovna'); ?>                
                </a>
            </li>
            <li>
                <a href="<?php echo admin_url(); ?>admin.php?page=zasilkovna&form=pl" class="<?php $this->get_active( 'pl' ); ?>">
                    <?php _e('Polsko','zasilkovna'); ?>                
                </a>
            </li>
            <!--<li>
                <a href="<?php echo admin_url(); ?>admin.php?page=zasilkovna&form=hu" class="<?php $this->get_active( 'hu' ); ?>">
                    <?php _e('Maďarsko','zasilkovna'); ?>                
                </a>
            </li>-->
            <li>
                <a href="<?php echo admin_url(); ?>admin.php?page=zasilkovna&form=de" class="<?php $this->get_active( 'de' ); ?>">
                    <?php _e('Německo','zasilkovna'); ?>                
                </a>
            </li>
            <li>
                <a href="<?php echo admin_url(); ?>admin.php?page=zasilkovna&form=at" class="<?php $this->get_active( 'at' ); ?>">
                    <?php _e('Rakousko','zasilkovna'); ?>                
                </a>
            </li>
<!--            <li>
                <a href="<?php echo admin_url(); ?>admin.php?page=zasilkovna&form=ru" class="<?php $this->get_active( 'ru' ); ?>">
                    <?php _e('Rumunsko','zasilkovna'); ?>                
                </a>
            </li>
            <li>
                <a href="<?php echo admin_url(); ?>admin.php?page=zasilkovna&form=bl" class="<?php $this->get_active( 'bl' ); ?>">
                    <?php _e('Bulharsko','zasilkovna'); ?>                
                </a>
            </li>-->
        </ul>

        <?php 
            include( 'parts/'.$this->get_form().'.php' ); 
        ?>

        </div>
    </div>
</div>     
   
  
<div class="clear"></div>
</div>  
  </div> 
  

