<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * 
 * @since 0.1
 * @extends \WC_Email
 */
class WC_Zasilkovna_Admin_Error_Info extends WC_Email {
	
  	/**
	 * Unique identifier
	 *    
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'zasilkovna';
  
  
  	/**
	 * Set email defaults
	 *
	 * @since 0.1
	 */
	public function __construct() {
		
    	$this->id          = 'wc_zasilkovna_admin_error_info';
    	$this->customer_email = true;
		$this->title       = __('Chyba odeslání do Zásilkovny', $this->plugin_slug);
		$this->description = __('Email s informací o chybě zásilky', $this->plugin_slug);
		$this->heading     = __('Chyba odeslání do Zásilkovny', $this->plugin_slug);
		$this->subject     = __('Chyba odeslání do Zásilkovny z obchodu {site_title}', $this->plugin_slug);
    
		
    	// these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
		$this->template_html  = 'zasilkovna-admin-error-info.php';
		$this->template_plain = 'zasilkovna-admin-error-info-plain.php';
		$this->templates = array( 'zasilkovna-admin-error-info.php', 'zasilkovna-admin-error-info-plain.php' );

		// Call parent constructor to load any other defaults not explicity defined here
		parent::__construct();	  

  	}

  	/**
	 * Determine if the email should actually be sent and setup email merge variables
	 *
	 * @since 0.1
	 * @param int $order_id
	 */
	public function trigger( $order_id, $note ) {
  
	if ( $order_id ) {
			$this->object 		           = wc_get_order( $order_id );  
      		$this->recipient               = $this->get_option( 'recipient', get_option( 'admin_email' ) );
      		$this->note               	   = $note;
			
			$this->find['order-date']      = '{order_date}';
			$this->find['order-number']    = '{order_number}';

			$this->replace['order-date']   = date_i18n( wc_date_format(), strtotime( $this->object->get_date_created() ) );
			$this->replace['order-number'] = $this->object->get_order_number();
		}else{
      
    }

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		return $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
    
	}
	
  
  	/**
	 * get_content_html function.
	 *
	 * @since 0.1
	 * @return string
	 */
	public function get_content_html() {
		
		return wc_get_template_html( $this->template_html, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => true,
			'plain_text'    => false,
			'email'			=> $this,
			'note'			=> $this->note
		) );
	
	}
	
  
  	/**
	 * get_content_plain function.
	 *
	 * @since 0.1
	 * @return string
	 */
	public function get_content_plain() {
		
		return wc_get_template_html( $this->template_plain, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => true,
			'plain_text'    => true,
			'email'			=> $this,
			'note'			=> $this->note
		) );
	
	}     
	
  
  	/**
	 * Initialise Settings Form Fields
	 *
	 * @access public
	 * @return void
	 */
	function init_form_fields() {
  
  
  		$types = array(
			'plain' => __( 'Plain text', $this->plugin_slug )
		);

		if ( class_exists( 'DOMDocument' ) ) {
			$types['html'] = __( 'HTML', $this->plugin_slug );
			$types['multipart'] = __( 'Multipart', $this->plugin_slug );
		}
    
  
		$this->form_fields = array(
			'enabled' => array(
				'title' 		=> __( 'Povolit/Zakázat', $this->plugin_slug ),
				'type' 			=> 'checkbox',
				'label' 		=> __( 'Povolit tuto emailovou notifikaci', $this->plugin_slug ),
				'default' 		=> 'yes'
			),
			'recipient' => array(
				'title'         => __( 'Příjemce(ci)', $this->plugin_slug ),
				'type'          => 'text',
				/* translators: %s: admin email */
				'description'   => sprintf( __( 'Vložte příjemce (oddělené čárkou) pro tento email. Výchozí je %s.', $this->plugin_slug ), '<code>' . esc_attr( get_option( 'admin_email' ) ) . '</code>' ),
				'placeholder'   => '',
				'default'       => '',
				'desc_tip'      => true,
			),
			'subject' => array(
				'title' 		=> __( 'Předmět emailu', $this->plugin_slug ),
				'type' 			=> 'text',
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'heading' => array(
				'title' 		=> __( 'Nadpis emailu', $this->plugin_slug ),
				'type' 			=> 'text',
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'email_type' => array(
				'title' 		=> __( 'Typ emailu', $this->plugin_slug ),
				'type' 			=> 'select',
				'description' 	=> __( 'Zvolte, jaký formát se má odesílat.', $this->plugin_slug ),
				'default' 		=> 'html',
				'class'			=> 'email_type wc-enhanced-select',
				'options'		=> $types
			)
		);
	}
  

} // end