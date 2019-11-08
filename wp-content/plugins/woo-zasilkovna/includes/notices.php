<?php 

/**
 *
 * @package   Notices
 * @author    Woo
 * @license   GPL-2.0+
 * @link      http://woo.cz
 * @copyright 2018 Woo
 *
 * Version 1.5.4
 *  
 */

class WooCommerce_Zasilkovna_Notices {

    
    public $plugin_name = 'WooCommerce Zásilkovna';

    public $plugin_slug = 'zasilkovna';

    public function __construct() {

        add_action( 'admin_notices', array( $this, 'admin_notices' ) );

    }


    /**
     * Handle notices
     *
     * @since 1.5.4
     */
    public function admin_notices(){

        //Notice for all plugins
        $this->woocommerce_exist();
        $this->woocommerce_version(); 
        $this->curl_exist();
        $this->soap_exist();  

        $this->licence_key_exist();
 
    }

    /**
     * Upozornění na neexistence WooCommerce
     *
     * @since 1.5.4
     */
    public function woocommerce_exist(){

        if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {     

        ?>
            <div class="notice error <?php echo $plugin_slug; ?>-woocommerce-notice" >
                <h2><?php echo $this->plugin_name . ' - ' . __( 'notice', $this->plugin_slug ); ?></h2>
                <p><?php _e( '<strong>Pro fungování pluginu WooCommerce Zásilkovna je nutné mít nainstalovaný plugin WooCommerce.</strong>', $this->plugin_slug ); ?></p>
            </div>

        <?php

        }

    }

    /**
     * Upozornění na nekompatibilní verzi WooCommerce
     *
     * @since 1.5.4
     */
    public function woocommerce_version(){

        if ( function_exists( 'WC' ) && ( version_compare( WC()->version, '3.0.0', '<' ) ) ) {     

        ?>
            <div class="notice error <?php echo $plugin_slug; ?>-woocommerce-version-notice" >
                <h2><?php echo $this->plugin_name . ' - ' . __( 'notice', $this->plugin_slug ); ?></h2>
                <p><?php _e( '<strong>Plugin vyžaduje pro správné fungování verzi WooCommerce 3.0 a vyšší. Plugin by měl být kompatibilní i s verzí 2.6., avšak správné fungování není garantováno. Doporučujeme aktualizovat.</strong>', $this->plugin_slug ); ?></p>
            </div>

        <?php

        }

    }


    /**
     * Upozornění na neaktivní cURL
     *
     * @since 1.5.4
     */
    public function curl_exist(){

        if ( !extension_loaded( 'curl' ) ) {     

        ?>
            <div class="notice error <?php echo $this->plugin_slug; ?>-curl-notice" >
                <h2><?php echo $this->plugin_name . ' - ' . __( 'upozornění', $this->plugin_slug ); ?></h2>
                <p><?php _e( '<strong>Plugin vyžaduje aktivní cURL knihovnu pro správné fungování. Prosím, kontaktujte vašeho správce serveru.</strong>', $this->plugin_slug ); ?></p>
            </div>

        <?php

        }

    }

    /**
     * Upozornění na neaktivní Soap
     *
     * @since 1.5.4
     */
    public function soap_exist(){

        if( !class_exists( 'SOAPClient' ) ) {    

        ?>
            <div class="notice error <?php echo $this->plugin_slug; ?>-soap-notice" >
                <h2><?php echo $this->plugin_name . ' - ' . __( 'upozornění', $this->plugin_slug ); ?></h2>
                <p><?php _e( '<strong>Plugin vyžaduje aktivní Soap knihovnu pro správné fungování. Prosím, kontaktujte vašeho správce serveru.</strong>', $this->plugin_slug ); ?></p>
            </div>

        <?php

        }

    }

    /**
     * Upozornění na nezadanou licenci
     *
     * @since 1.5.4
     */
    public function licence_key_exist(){

//        $licence_key  = get_option( 'woo-zasilkovna-licence' );
//
//        if ( empty( $licence_key ) ) {     
//
//        ?>
           <!-- <div class="notice error //<?php echo $plugin_slug; ?>-licence-notice" >
                <h2>//<?php echo $this->plugin_name . ' - ' . __( 'upozornění', $this->plugin_slug ); ?></h2>
                <p>//<?php _e( '<strong>Plugin vyžaduje aktivaci licence pro správné fungování. Prosím, ověřte licenci v <a href="'.admin_url().'admin.php?page=woo-doprava">nastavení pluginu</a>.</strong>', $this->plugin_slug ); ?></p>
            </div>-->

        <?php
//
//        }

    }

  
}//End class