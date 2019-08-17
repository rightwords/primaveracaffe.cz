<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

class FW_Extension_Switch_Style_Panel extends FW_Extension {

	private $cache_key = 'wrap-style-panel';

	private $options;

	/**
	 * @internal
	 */
	public function _init() {

		if ( is_admin() ) {
			$this->add_admin_filters();
		}else {
			$this->check_settings();
		}
	}

	private function add_admin_filters(){
		add_filter( 'fw_ext_styling_settings_options', array($this, '_filter_add_config_options'));
	}

	/**
	 * Add options in dashboard
	 * @param $options
	 * @internal
	 * @return array
	 */
	public function _filter_add_config_options($options){
		return array_merge($options, $this->get_options('settings'));
	}

	protected function check_settings() {
		if ( ! fw_get_db_ext_settings_option($this->get_parent()->get_name(), 'switch_style_panel_display') ) {
			return;
		}
		$theme_options = fw_extract_only_options( $this->get_parent()->get_options('appearance-settings') );
		$options       = false;

		foreach ( $theme_options as $option_name => $option_settings ) {
			if ( $option_settings['type'] !== 'style' ) {
				unset ( $theme_options[ $option_name ] );
				continue;
			}
			$options = $option_settings;
			break;
		}

		if ( ! empty( $options['predefined'] ) ) {
			$this->options = $options;
			$this->add_theme_actions();
		}
	}

	protected function add_theme_actions() {
		add_action( 'wp_head', array( $this, '_theme_action_print_saved_css' ), 99 );
		add_action( 'wp_footer', array( $this, '_theme_action_print_styling_switcher' ), 10 );
	}

	/**
	 * @internal
	 */
	public function _theme_action_print_saved_css() {
		$stored_style = FW_Request::COOKIE( $this->cache_key );
		if ( ! empty( $this->options['predefined'][ $stored_style ] ) ) {
			echo $this->generate_initial_css( $this->options['blocks'], $this->options['predefined'][ $stored_style ] );
		};
	}

	private function generate_initial_css( $blocks, $style_options ) {
		$data = FW_Switch_Style_Panel_Css_Generator::get_css( $blocks, $style_options );
		$css  = $data['google_fonts'];
		$css .= '<style data-rel="' . $this->cache_key . '" type="text/css">' . $data['css'] . '</style>';

		return $css;
	}

	/**
	 * @internal
	 */
	public function _theme_action_print_styling_switcher() {

		echo $this->render_view( 'panel', array(
			'options'     => $this->options,
			'description' => fw_get_db_ext_settings_option($this->get_parent()->get_name(), 'switch_style_panel_description')
		) );

		// add static
		{
			wp_enqueue_style(
				'fw-ext-' . $this->get_name(),
				$this->locate_URI( '/static/css/panel.css' ),
				array(),
				fw()->theme->manifest->get_version()
			);
			wp_enqueue_script(
				'fw-ext-' . $this->get_name(),
				$this->locate_URI( '/static/js/panel.js' ),
				array( 'jquery' ),
				fw()->theme->manifest->get_version()
			);

			wp_localize_script( 'fw-ext-' . $this->get_name(), 'fwGoogleFonts', fw_get_google_fonts() );
			wp_localize_script( 'fw-ext-' . $this->get_name(), 'fwSwitchStylePanel', array( 'cache_key' => $this->cache_key ) );
		}

	}
}
