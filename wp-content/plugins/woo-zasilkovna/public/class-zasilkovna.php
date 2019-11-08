<?php
/**
 * @package    Zásilkovna
 * @author    Woo
 * @license   GPL-2.0+
 * @link      http://woo.cz
 * @copyright 2014 woo
 */

class Woo_Zasilkovna {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '2.0';

	/**
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'woo-zasilkovna';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		$licence = get_option( 'woo-zasilkovna-licence' );
		if( empty( $licence ) ){ return false; }
		elseif( $licence == 'inactive' ){ return false; }

		//Remove all shipping methods, when free is available
    	add_filter( 'woocommerce_package_rates', array( $this, 'hide_shipping_when_free_is_available' ), 10, 2 );

    	//Check default select
    	add_action('wp_head', array( $this, 'zasilkovna_check_select' ) );

    	//Recalculate cart
    	add_action( 'woocommerce_review_order_after_submit' , array( $this, 'woo_print_autoload_js' ) );

    	//Přidat info do detailu objednávky
    	add_action( 'woocommerce_order_details_after_order_table', array( $this, 'zasilkovna_customer_order_info' ) );

    	add_action( 'woocommerce_admin_order_data_after_billing_address', array( $this, 'zasilkovna_admin_customer_order_info' ) );

    	//Add info into email
    	add_action( 'woocommerce_email_after_order_table', array( $this, 'zasilkovna_customer_email_info' ) , 15, 2 );

    	//Zásilkovna select options
    	add_action('woocommerce_review_order_after_shipping', array( $this, 'zasilkovna_select_option' ) , 15, 2 );

    	//Uložit
    	add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'store_pickup_field_update_order_meta' ) , 15, 2 );

    	//Zkontrolovat vybranou pobočku
    	add_action( 'woocommerce_checkout_process', array( $this, 'zasilkovna_check_pobocka' ) );

    	//Change email template dir
    	add_filter( 'woocommerce_locate_template', array( $this, 'woo_local_template' ), 10, 3 );

    	add_action( 'init', array( 'WC_Emails', 'init_transactional_emails' ) );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}


	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {

    }

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {

	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = 'zasilkovna';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		$load = load_textdomain( $domain, WP_LANG_DIR . '/zasilkovna/' . $domain . '-' . $locale . '.mo' );

		if( $load === false ){
			load_textdomain( $domain, WOOZASILKOVNADIR . 'languages/' . $domain . '-' . $locale . '.mo' );
		}

	}


	/**
	 * Remove all shipping methods, when free is available
	 *
	 * since 1.1.0
	 */
  	public function hide_shipping_when_free_is_available( $rates, $package ) {

  		$old_rates = $rates;

  		$zasilkovna_option = get_option( 'zasilkovna_option');
  		if( !empty( $zasilkovna_option['doprava_zdarma'] ) && $zasilkovna_option['doprava_zdarma'] == 'default'){
  			return $rates;
  		}

    	if ( version_compare( WOOCOMMERCE_VERSION, '2.6.0', '>=' ) ) {

      		$free = false;
      		foreach ( $rates as $rate_id => $rate ) {
        		if ( 'free_shipping' === $rate->method_id ) {
          			$free = true;
          			$free_rate_id = $rate_id;
          			break;
        		}
      		}

      		if ( $free === true ) {
        		if( !empty( $zasilkovna_option['doprava_zdarma'] ) && $zasilkovna_option['doprava_zdarma'] == 'all'){
        			foreach($rates as $key => $item){
          				$rates[$key]->cost  = 0;
          				$rates[$key]->tax   = 0;
          				$rates[$key]->taxes = false;
        			}
      			}elseif( !empty( $zasilkovna_option['doprava_zdarma'] ) && $zasilkovna_option['doprava_zdarma'] == 'zasilkovna' ){
      				foreach($rates as $key => $item){
      					$check_if_s_zasilkovna = explode( '>', $key );

      					if( !empty( $check_if_s_zasilkovna[0] ) &&  $check_if_s_zasilkovna[0] == 'zasilkovna' ){
          					$rates[$key]->cost  = 0;
          					$rates[$key]->tax   = 0;
          					$rates[$key]->taxes = false;
          				}
        			}
      			}
        		unset( $rates[$free_rate_id] );
      		}

    	}else{

      		if ( isset( $rates['free_shipping'] ) ) {
  	    		if( !empty( $zasilkovna_option['doprava_zdarma'] ) && $zasilkovna_option['doprava_zdarma'] == 'all'){
  	    			foreach($rates as $key => $item){
          				$rates[$key]->cost  = 0;
          				$rates[$key]->tax   = 0;
          				$rates[$key]->taxes = false;
        			}
        		}
        		unset( $rates['free_shipping'] );
      		}

    	}

    	return $rates = apply_filters( 'zasilkovna_free_shipping_rates', $rates, $old_rates, $package );

  	}

  	/**
	 * Check default select
	 *
	 *
	 */
 	public function zasilkovna_check_select(){

  		if ( WC()->cart->needs_shipping() ){
  		?>
  		<script type="text/javascript">
        	jQuery(document).ready(function($){
	      		jQuery('body').on('click', '#place_order', function() {
		      		var pobocka = jQuery('body #zasilkovna_id option:selected').val();
          			if( pobocka == 'default' ){
            			alert('<?php _e('Prosím, vyberte pobočku.','zasilkovna'); ?>');
            			return false;
           			}
	       		});

	      		jQuery( document.body ).on( 'updated_checkout', function() {
                    console.log(sessionStorage.zasilkovnaVybranaPobocka);
                    if(typeof sessionStorage.zasilkovnaVybranaPobocka !== "undefined") {
                    	jQuery( '#zasilkovna_id' ).val(sessionStorage.zasilkovnaVybranaPobocka);
                    }
                });

                jQuery( 'body' ).on( 'change', '#zasilkovna_id', function() {
                	sessionStorage.zasilkovnaVybranaPobocka = jQuery( '#zasilkovna_id' ).val();
                	console.log(sessionStorage.zasilkovnaVybranaPobocka);
                });

        	});
  		</script>
  		<?php
  		}

  	}


  	/**
	 *
	 * Recalcute cart
	 *
	 */
	function woo_print_autoload_js(){
		?>
		<script type="text/javascript">
        	jQuery(document).ready(function($){
         		jQuery(document.body).on('change', 'input[name="payment_method"]', function() {
		       		jQuery('body').trigger('update_checkout');
	      		});
        	});
 		</script><?php
	}


	/**
	 *
	 * Add info to order detail
	 *
	 */

	public function zasilkovna_admin_customer_order_info( $order ) {

		$order_id = Woo_Order_Compatibility::get_order_id( $order );

  		$zasilkovna_id = get_post_meta( $order_id, 'zasilkovna_id_pobocky', true );
  		if(!empty($zasilkovna_id)){

  			$zas = Zasilkovna_Helper::set_services();

  			if( !in_array( $zasilkovna_id, $zas ) ){

  				$zasilkovna_mista = get_option( 'zasilkovna_mista');

  				$html = '';
        		$html .= '<strong>' . __('Zásilkovna - místo vyzvednutí: ','zasilkovna') . '</strong><br />';
  				$html .= '<strong>' . __('Název: ','zasilkovna') . '</strong>: ';
  				$html .= '' . $zasilkovna_mista[$zasilkovna_id]['name'] . '<br />';
  				$html .= '<strong>' . __('Místo: ','zasilkovna') . '</strong>: ';
  				$html .= '' . $zasilkovna_mista[$zasilkovna_id]['place'] . '<br />';
  				$html .= '<strong>' . __('Ulice: ','zasilkovna') . '</strong>: ';
  				$html .= '' . $zasilkovna_mista[$zasilkovna_id]['street'] . '<br />';
  				$html .= '<strong>' . __('Město: ','zasilkovna') . '</strong>: ';
  				$html .= '' . $zasilkovna_mista[$zasilkovna_id]['city'] . '<br />';
  				$html .= '<strong>' . __('PSČ: ','zasilkovna') . '</strong>: ';
  				$html .= '' . $zasilkovna_mista[$zasilkovna_id]['zip'] . '<br />';
  				$html .= '<a href="'.$zasilkovna_mista[$zasilkovna_id]['url'].'" target="_blank" class="button">'.__('Zobrazit detail místa','zasilkovna').'</a><br />';

  				$field = get_post_meta( $order_id, 'zasilkovna_barcode', true );
      			if( !empty( $field ) ){
      				$html .= '<a class="zasilkovna-sledovani" href="https://www.zasilkovna.cz/vyhledavani?det='.$field.'" target="_blank" class="button">'.__('Sledujte zásilku online','zasilkovna').'</a><br />';
      			}

              	echo $html;
   			}
  		}


	}


	/**
	 *
	 * Add info to order detail
	 *
	 */

	public function zasilkovna_customer_order_info( $order ) {

		$order_id = Woo_Order_Compatibility::get_order_id( $order );

  		$zasilkovna_id = get_post_meta( $order_id, 'zasilkovna_id_pobocky', true );
  		if(!empty($zasilkovna_id)){

  			$zas = Zasilkovna_Helper::set_services();

  			if( !in_array( $zasilkovna_id, $zas ) ){

  				$zasilkovna_mista = get_option( 'zasilkovna_mista');

  				$html = '<table class="shop_table order_details zasilkovna_detail">';
        		$html .= '<tr>';
        		$html .= '<th colspan="2">' . __('Zásilkovna - místo vyzvednutí: ','zasilkovna') . '</th>';
  				$html .= '</tr>';
  				$html .= '<tr>';
  				$html .= '<th>' . __('Název: ','zasilkovna') . '</th>';
  				$html .= '<td>' . $zasilkovna_mista[$zasilkovna_id]['name'] . '</td>';
  				$html .= '</tr>';
  				$html .= '<tr>';
  				$html .= '<th>' . __('Místo: ','zasilkovna') . '</th>';
  				$html .= '<td>' . $zasilkovna_mista[$zasilkovna_id]['place'] . '</td>';
  				$html .= '</tr>';
  				$html .= '<tr>';
  				$html .= '<th>' . __('Ulice: ','zasilkovna') . '</th>';
  				$html .= '<td>' . $zasilkovna_mista[$zasilkovna_id]['street'] . '</td>';
  				$html .= '</tr>';
  				$html .= '<tr>';
  				$html .= '<th>' . __('Město: ','zasilkovna') . '</th>';
  				$html .= '<td>' . $zasilkovna_mista[$zasilkovna_id]['city'] . '</td>';
  				$html .= '</tr>';
  				$html .= '<tr>';
  				$html .= '<th>' . __('PSČ: ','zasilkovna') . '</th>';
  				$html .= '<td>' . $zasilkovna_mista[$zasilkovna_id]['zip'] . '</td>';
  				$html .= '</tr>';

  				$html .= '<tr>';
  				$html .= '<td><a href="'.$zasilkovna_mista[$zasilkovna_id]['url'].'" target="_blank" class="button">'.__('Zobrazit detail místa','zasilkovna').'</a></td>';

  				$field = get_post_meta( $order_id, 'zasilkovna_barcode', true );
  				$html .= '<td>';
      			if( !empty( $field ) ){
      				$html .= '<a class="zasilkovna-sledovani" href="https://www.zasilkovna.cz/vyhledavani?det='.$field.'" target="_blank" class="button">'.__('Sledujte zásilku online','zasilkovna').'</a>';
      			}
  				$html .= '</td>';
  				$html .= '</tr>';
  				$html .= '</table>';

              	echo $html;
   			}
  		}


	}



	/**
	 * Add info to email
	 *
	 * @since 1.0.0
	 */
	public function zasilkovna_customer_email_info( $order, $is_admin ) {

		$order_id = Woo_Order_Compatibility::get_order_id( $order );

  		$zasilkovna_id = get_post_meta( $order_id, 'zasilkovna_id_pobocky', true );
  		if( !empty( $zasilkovna_id ) ){

  			$zas = Zasilkovna_Helper::set_services();

  			if( !in_array( $zasilkovna_id,$zas ) ){
        		$zasilkovna_mista = get_option( 'zasilkovna_mista' );

        		$html = '<p><strong>' . __('Zásilkovna - místo vyzvednutí:','zasilkovna') . ' </strong><br />';

              	$html .= $zasilkovna_mista[$zasilkovna_id]['name'].'<br />
                 '.$zasilkovna_mista[$zasilkovna_id]['place'].'<br />
                 '.$zasilkovna_mista[$zasilkovna_id]['street'].'<br />
                 '.$zasilkovna_mista[$zasilkovna_id]['city'].'<br />
                 '.$zasilkovna_mista[$zasilkovna_id]['zip'].'<br />
                 <a href="'.$zasilkovna_mista[$zasilkovna_id]['url'].'" target="_blank">'.__('Zobrazit detail místa','zasilkovna').'</a></p>';

                echo $html;
  			}
  		}

  			$field = get_post_meta( $order_id, 'zasilkovna_barcode', true );
      		if( !empty( $field ) ){
      			echo '<p><a class="zasilkovna-sledovani" href="https://www.zasilkovna.cz/vyhledavani?det='.$field.'" target="_blank">'.__('Sledujte zásilku online','zasilkovna').'</a></p>';
      		}
	}



	/**
	 * Create select option for Zasilkovna branches
	 *
	 * @since 1.0.0
	 */
	public function zasilkovna_select_option(){


    	$doprava_name = explode('>',WC()->session->chosen_shipping_methods[0]);

    	if ( !empty($doprava_name[1]) ){
    		if ( $doprava_name[1] == 'z-points' ){

          		$zasilkovna_mista = get_option( 'zasilkovna_mista');
          		$zasilkovna_option = get_option( 'zasilkovna_option' );
          		$country = woo_get_customer_country();

          		if( $country == 'SK' ){
                	$ico_url = $this->get_zasilkovna_icon( $zasilkovna_option, 'icon_url_sk' );
                	$zasilkovna_mista = get_option( 'zasilkovna_mista_sk');
          		}elseif( $country == 'CZ' ){
	                $ico_url = $this->get_zasilkovna_icon( $zasilkovna_option, 'icon_url' );
                	$zasilkovna_mista = get_option( 'zasilkovna_mista_cz');
          		}elseif( $country == 'PL' ){
	                $ico_url = $this->get_zasilkovna_icon( $zasilkovna_option, 'icon_url_pl' );
                	$zasilkovna_mista = get_option( 'zasilkovna_mista_pl');
          		}elseif( $country == 'DE' ){
	                $ico_url = $this->get_zasilkovna_icon( $zasilkovna_option, 'icon_url_de' );
                	$zasilkovna_mista = get_option( 'zasilkovna_mista_hu');
          		}elseif( $country == 'AT' ){
	                $ico_url = $this->get_zasilkovna_icon( $zasilkovna_option, 'icon_url_at' );
                	$zasilkovna_mista = get_option( 'zasilkovna_mista_ro');
          		}else{
	                $ico_url = $this->get_zasilkovna_icon( $zasilkovna_option, 'icon_url' );
    	            $zasilkovna_mista = get_option( 'zasilkovna_mista_cz');
          		}

            	echo '<tr>';
              		echo '<th class="zasikovna-ico">Zásilkovna</th>';
              		echo '<td>';
                		echo '<select name="zasilkovna_id" id="zasilkovna_id" style="width:100%;">';
                    		echo '<option value="default">'.__('Zvolte pobočku', 'zasilkovna').'</option>';
                  		foreach($zasilkovna_mista as $key => $item){
                    		echo '<option value="'.$item['id'].'">'.$item['name'].'</option>';
                  		}
                		echo '</select>';
              		echo '</td>';
            	echo '</tr>';

        	}else{
        		$ids = Zasilkovna_Helper::set_shipping_ids();
        		if( !empty( $ids[$doprava_name[1]] ) ){
       				echo '<input type="hidden" name="zasilkovna_id" value="'.$ids[$doprava_name[1]].'" />';
       			}
       		}
    	}
	}

	/**
	 * Získat ikonu Zásilkovny
	 *
	 * @since 1.0.0
	 */
	function get_zasilkovna_icon( $zasilkovna_option, $icon ) {

		if( !empty( $zasilkovna_option[$icon] ) ){
			$ico_url = $zasilkovna_option[$icon];
		} else{
			$ico_url = $zasilkovna_option['icon_url'];
		}

		return $ico_url;

	}


	/**
	 * Uložit id místa
	 *
	 * @since 1.0.0
	 */
	function store_pickup_field_update_order_meta( $order_id ) {
		$doprava_name = explode( '>', WC()->session->chosen_shipping_methods[0] );

		if( !empty( $doprava_name[0] ) && $doprava_name[0] == 'zasilkovna' ){

			if ( $_POST[ 'zasilkovna_id' ] ){

 	 			update_post_meta( $order_id, 'zasilkovna_id_pobocky', esc_attr( $_POST[ 'zasilkovna_id' ] ) );
 	 			update_post_meta( $order_id, 'zasilkovna_id_dopravy', WC()->session->chosen_shipping_methods[0] );

    		}
  		}
	}


	/**
	 * Zkontrolovat vybrání pobočky
	 *
	 */
	public function zasilkovna_check_pobocka(){

		if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ){

			$doprava_name = explode( '>', WC()->session->chosen_shipping_methods[0] );

			if( !empty( $doprava_name[0] ) && $doprava_name[0] == 'zasilkovna' ){

				if ( ! $_POST['zasilkovna_id'] ){
	        		wc_add_notice( __( 'Prosíme, zvolte pobočku pro vybranou dopravu.', 'zasilkovna' ), 'error' );
        		}else{
	 	 			if ( $_POST[ 'zasilkovna_id' ] ){

 	 					if ( $_POST['zasilkovna_id'] == 'default' ){
	 	 					wc_add_notice( __( 'Prosíme, zvolte pobočku pro vybranou dopravu.', 'zasilkovna' ), 'error' );
 		 				}
  					}
  				}
  			}
  		}

	}


	/**
	 * Force WooCommerce to load email template from plugin
	 *
	 * @since    1.0.0
	 */
	public function woo_local_template( $template, $template_name, $template_path ) {

		if ( $template_name == 'zasilkovna-admin-error-info.php' ){
            $template = WOOZASILKOVNADIR . 'includes/emails/zasilkovna-admin-error-info.php';
        }elseif( $template_name == 'zasilkovna-admin-error-info-plain.php' ){
            $template = WOOZASILKOVNADIR . 'includes/emails/zasilkovna-admin-error-info-plain.php';
        }

        return $template;

    }


}//End class
