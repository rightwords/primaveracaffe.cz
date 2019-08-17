<?php

/**
 * @package   Woo Zásilkovna
 * @author    Woo
 * @license   GPL-2.0+
 * @link      http://woo.cz
 * @copyright 2016 woo
 */
class Woo_Zasilkovna_Admin {

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     * Initialize the plugin by loading admin scripts & styles and adding a
     * settings page and menu.
     *
     * @since     1.0.0
     */
    private function __construct() {

        $plugin = Woo_Zasilkovna::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();

        // Load admin style sheet and JavaScript.
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Add the options page and menu item.
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));

        /**
         *  Output fix
         */
        add_action('admin_init', array($this, 'output_buffer'));

        add_action('admin_init', array($this, 'send_ticket'));

        add_filter('manage_edit-shop_order_columns', array($this, 'barcode_column'), 99999);
        add_action('manage_shop_order_posts_custom_column', array($this, 'barcode_column_display'), 10, 2);
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
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_styles() {
        if (isset($_GET['page']) && $_GET['page'] == 'zasilkovna') {
            wp_enqueue_style($this->plugin_slug . '-admin-styles', WOOZASILKOVNAURL . 'assets/css/admin-zasilkovna.css', array(), Woo_Zasilkovna::VERSION);

            wp_enqueue_style('icheck', plugins_url('assets/css/iCheck/flat/green.css', __FILE__), array(), Woo_Zasilkovna::VERSION);
            wp_enqueue_style('icheckred', plugins_url('assets/css/iCheck/flat/red.css', __FILE__), array(), Woo_Zasilkovna::VERSION);
            wp_enqueue_style('font-awesome', plugins_url('assets/fonts/font-awesome/css/font-awesome.css', __FILE__), array(), Woo_Zasilkovna::VERSION);
        }
    }

    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts() {

        wp_enqueue_script($this->plugin_slug . '-admin-script', plugins_url('assets/js/admin-zasilkovna.js', __FILE__), array('jquery'), Woo_Zasilkovna::VERSION);
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {

        if (!defined('MAINMENU')) {

            add_menu_page(
                    __('Woo plugins', $this->plugin_slug), __('Woo plugins', $this->plugin_slug), 'manage_options', 'main-plugins', array($this, 'display_main_plugins_admin_page'), WOOZASILKOVNAURL . '/assets/m-icon.png'
            );

            define('MAINMENU', true);
        }

        add_submenu_page(
                'main-plugins', __('Zásilkovna', $this->plugin_slug), __('Zásilkovna', $this->plugin_slug), 'manage_options', 'zasilkovna', array($this, 'control_xml')
        );
    }

    /**
     * Render the settings page for all plugins
     *
     * @since    1.0.0
     */
    public function display_main_plugins_admin_page() {
        include_once( 'views/main-menu.php' );
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function control_xml() {
        include_once( 'views/control-xml.php' );
    }

    /**
     * Headers allready sent fix
     * 
     * @since    1.0.0        
     */
    public function output_buffer() {
        ob_start();
    }

    /**
     *
     *
     */
    public function barcode_column($columns) {
        $new_columns = array();
        foreach ($columns as $key => $item) {
            $new_columns[$key] = $item;
            if ($key == 'cb') {
                $new_columns['zasilkovna'] = __('Barcode', $this->plugin_slug);
            }
        }
        $new_columns['zasilkovna_send'] = __('Zásilkovna', $this->plugin_slug);
        return $new_columns;
    }

    /**
     * Display send button and barcode
     *
     */
    public function barcode_column_display($column_name, $post_id) {
        global $post;

        if ($column_name == 'zasilkovna') {
            $field = get_post_meta($post_id, 'zasilkovna_barcode', true);
            echo '<a href="https://www.zasilkovna.cz/vyhledavani?det=' . $field . '" target="_blank">' . $field . '</a>';
        }

        if ($column_name == 'zasilkovna_send') {

            $zasilkovna_id = get_post_meta($post_id, 'zasilkovna_id_pobocky', true);

            if (!empty($zasilkovna_id)) {
                $field = get_post_meta($post_id, 'zasilkovna_barcode', true);
                if (empty($field)) {
                    echo '<a href="' . admin_url() . 'edit.php?post_type=shop_order&zasilkovna_id_objednavky=' . $post_id . '" class="button" style="padding: 2px 4px 1px 5px;"><span class="dashicons dashicons-external"></span></a>';
                }

                $id_zasilky = get_post_meta($post_id, 'zasilkovna_id_zasilky', true);

                if (!empty($id_zasilky)) {
                    echo '<a href="' . admin_url() . 'edit.php?post_type=shop_order&zasilkovna_ticket_id=' . $id_zasilky . '" class="button" style="padding: 2px 4px 1px 5px;"><span class="dashicons dashicons-arrow-down-alt"></span></a>';
                }
            }
        }
    }

    /**
     * Send ticket or download packet
     *
     */
    public function send_ticket() {

        if (!empty($_GET['zasilkovna_id_objednavky'])) {
            Zasilkovna_Ticket::send_ticket($_GET['zasilkovna_id_objednavky']);
        }
        if (!empty($_GET['zasilkovna_ticket_id'])) {

            $zasilkovna_option = get_option('zasilkovna_option');
            $apiPassword = $zasilkovna_option['api_password'];

            $gw = new SoapClient("http://www.zasilkovna.cz/api/soap.wsdl");
            try {

                $packetId = $_GET['zasilkovna_ticket_id'];
                $format = "A7 on A7";
                $offset = 0;

                $packet = $gw->packetLabelPdf($apiPassword, $packetId, $format, $offset);

                header('Content-type: application/pdf');
                header('Content-Disposition: attachment; filename=ticket-' . $_GET['zasilkovna_ticket_id'] . '.pdf');

                echo $packet;
            } catch (SoapFault $e) {
                // TODO: process error
                var_dump($e);
            }
            die();
        }
    }

    /**
     * Get form 
     *
     */
    public function get_form() {

        if (!empty($_GET['form'])) {
            $form = esc_attr($_GET['form']);
            $forms = array(
                'main-setting' => 'main',
                'shipping-setting' => 'shipping_methods',
                'cr' => 'cr',
                'sk' => 'sk',
                'pl' => 'pl',
                'hu' => 'hu',
                'de' => 'de',
                'at' => 'at',
                'ru' => 'ru',
                'bl' => 'bl',
            );

            return $forms[$form];
        } else {
            return 'main';
        }
    }

    /**
     * Get form 
     *
     */
    public function get_active($value) {

        if ($_GET['form'] == $value) {
            echo 'active';
        }
    }

    /**
     * Save services prices
     * 
     */
    public function save_service_prices() {

        if (isset($_POST['services_price'])) {

            $zasilkovna_prices = get_option('zasilkovna_prices');
            if (empty($zasilkovna_prices)) {
                $zasilkovna_prices = array();
            }

            if (isset($_POST['services_price_country']) && $_POST['services_price_country'] == 'cr') {
                $fields = Zasilkovna_Helper::cr_setting_fields();
            } elseif (isset($_POST['services_price_country']) && $_POST['services_price_country'] == 'sk') {
                $fields = Zasilkovna_Helper::sk_setting_fields();
            } elseif (isset($_POST['services_price_country']) && $_POST['services_price_country'] == 'pl') {
                $fields = Zasilkovna_Helper::pl_setting_fields();
            } elseif (isset($_POST['services_price_country']) && $_POST['services_price_country'] == 'hu') {
                $fields = Zasilkovna_Helper::hu_setting_fields();
            } elseif (isset($_POST['services_price_country']) && $_POST['services_price_country'] == 'de') {
                $fields = Zasilkovna_Helper::de_setting_fields();
            } elseif (isset($_POST['services_price_country']) && $_POST['services_price_country'] == 'at') {
                $fields = Zasilkovna_Helper::at_setting_fields();
            } elseif (isset($_POST['services_price_country']) && $_POST['services_price_country'] == 'ro') {
                $fields = Zasilkovna_Helper::ro_setting_fields();
            } elseif (isset($_POST['services_price_country']) && $_POST['services_price_country'] == 'bl') {
                $fields = Zasilkovna_Helper::bl_setting_fields();
            } elseif (isset($_POST['services_price_kurzy']) && $_POST['services_price_kurzy'] == 'ok') {
                $fields = Zasilkovna_Helper::services_price_kurzy();
            }

            foreach ($fields as $field) {

                if (!empty($_POST[$field])) {
                    $zasilkovna_prices[$field] = $_POST[$field];
                } else {
                    unset($zasilkovna_prices[$field]);
                }
            }

            update_option('zasilkovna_prices', $zasilkovna_prices);
        }
    }

    /**
     * Save main setting
     * 
     */
    public function save_setting() {

        if (isset($_POST['update'])) {

            if (!empty($_POST['licence'])) {
                if (trim($_POST['licence']) != '') {
                    woo_zasilkovna_control_licence($_POST['licence']);
                }
            }

            $zasilkovna_option = array();

            if (!empty($_POST['api_key'])) {
                $zasilkovna_option['api_key'] = sanitize_text_field($_POST['api_key']);
            }
            if (!empty($_POST['api_password'])) {
                $zasilkovna_option['api_password'] = sanitize_text_field($_POST['api_password']);
            }
            if (!empty($_POST['nazev_eshopu'])) {
                $zasilkovna_option['nazev_eshopu'] = sanitize_text_field($_POST['nazev_eshopu']);
            }
            if (!empty($_POST['cz_pobocky']) && $_POST['cz_pobocky'] == 'cz') {
                $zasilkovna_option['cz_pobocky'] = 'cz';
            }
            if (!empty($_POST['sk_pobocky']) && $_POST['sk_pobocky'] == 'sk') {
                $zasilkovna_option['sk_pobocky'] = 'sk';
            }
            if (!empty($_POST['de_pobocky']) && $_POST['de_pobocky'] == 'hu') {
                $zasilkovna_option['de_pobocky'] = 'hu';
            }
            if (!empty($_POST['pl_pobocky']) && $_POST['pl_pobocky'] == 'pl') {
                $zasilkovna_option['pl_pobocky'] = 'pl';
            }
            if (!empty($_POST['at_pobocky']) && $_POST['at_pobocky'] == 'ro') {
                $zasilkovna_option['at_pobocky'] = 'ro';
            }
            if (!empty($_POST['odeslani_zasilky'])) {
                $zasilkovna_option['odeslani_zasilky'] = sanitize_text_field($_POST['odeslani_zasilky']);
            }
            if (!empty($_POST['doprava_zdarma'])) {
                $zasilkovna_option['doprava_zdarma'] = sanitize_text_field($_POST['doprava_zdarma']);
            }
            if (!empty($_POST['icon_url'])) {
                $zasilkovna_option['icon_url'] = sanitize_text_field($_POST['icon_url']);
            }
            if (!empty($_POST['icon_url_sk'])) {
                $zasilkovna_option['icon_url_sk'] = sanitize_text_field($_POST['icon_url_sk']);
            }
            if (!empty($_POST['icon_url_de'])) {
                $zasilkovna_option['icon_url_de'] = sanitize_text_field($_POST['icon_url_de']);
            }
            if (!empty($_POST['icon_url_pl'])) {
                $zasilkovna_option['icon_url_pl'] = sanitize_text_field($_POST['icon_url_pl']);
            }
            if (!empty($_POST['icon_url_at'])) {
                $zasilkovna_option['icon_url_at'] = sanitize_text_field($_POST['icon_url_at']);
            }
            if (!empty($_POST['no_send'])) {
                $zasilkovna_option['no_send'] = sanitize_text_field($_POST['no_send']);
            }
            if (!empty($_POST['error_email']) && $_POST['error_email'] == 'email') {
                $zasilkovna_option['error_email'] = 'email';
            }

            update_option('zasilkovna_option', $zasilkovna_option);
        }
    }

}

//Class end
