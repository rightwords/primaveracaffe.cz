<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access forbidden.' ); }

class coffeeking_Theme_Includes {

	private static $rel_path = null;

	private static $include_isolated_callable;

	private static $initialized = false;

	public static function init() {

		if ( self::$initialized ) {
			return;
		} else {
			self::$initialized = true;
		}

		/**
		 * Include a file isolated, to not have access to current context variables
		 */
		self::$include_isolated_callable = create_function( '$path', 'include $path;' );

		/**
		 * Both frontend and backend
		 */
		{
			self::include_child_first( '/helpers.php' );
			self::include_child_first( '/hooks.php' );
			self::include_child_first( '/woocommerce.php' );
			self::include_all_child_first( '/includes' );

			add_action( 'init', array( __CLASS__, 'coffeeking_action_init' ) );
			add_action( 'widgets_init', array( __CLASS__, 'coffeeking_action_widgets_init' ) );
		}

		/**
		 * Only frontend
		 */
		if ( ! is_admin() ) {
			add_action('wp_enqueue_scripts', array( __CLASS__, 'coffeeking_action_enqueue_scripts' ),
				20 // Include later to be able to make wp_dequeue_style|script()
			);
		}
	}

	private static function get_includes_files_list($dir_rel_path){
		
		$path  	= self::get_parent_path($dir_rel_path). '/'; 

		$includes_files_list = array(
			
			$path.'content-width.php',
			$path.'sub-includes.php',
		);	
		
		$custom_list =  apply_filters ('coffeeking_filter_init_includes_custom_files', array());
		
		if ( !empty($custom_list) ){
			
			$prefixed_files = array();
			$child_path = self::get_child_path($dir_rel_path). '/';
			
			foreach ($custom_list as $file) {

				$prefixed_files[] = $child_path . $file;
			}	
					
			unset($custom_list);
			
			$includes_files_list = array_merge($includes_files_list, $prefixed_files);	
			
			unset($prefixed_files);
		}
		
		return $includes_files_list;
	}

	private static function get_rel_path($append = '') {

		if (self::$rel_path === null) {
			self::$rel_path = '/inc';
		}

		return self::$rel_path . $append;
	}

	private static function include_all_child_first($dir_rel_path) {

		$files 	= self::get_includes_files_list($dir_rel_path);

		foreach ($files as $file) {

			if (file_exists( $file )){
				
				self::include_isolated( $file );
				
			}
		}
		unset($files);
	}


	/**
	 * @param string $widget_name 'foo-bar'
	 * @return string 'Foo_Bar'
	 */
	private static function widget_classname( $widget_name ) {
		$class_name = explode( '-', $widget_name );
		$class_name = array_map( 'ucfirst', $class_name );
		$class_name = implode( '_', $class_name );

		return $class_name;
	}

	public static function get_parent_path( $rel_path ) {
		return get_template_directory() . self::get_rel_path( $rel_path );
	}

	public static function get_child_path( $rel_path ) {
		if ( ! is_child_theme() ) {
			return null;
		}

		return get_stylesheet_directory() . self::get_rel_path( $rel_path );
	}

	public static function include_isolated( $path ) {
		call_user_func( self::$include_isolated_callable, $path );
	}

	public static function include_child_first( $rel_path ) {
		if ( is_child_theme() ) {
			$path = self::get_child_path( $rel_path );

			if ( file_exists( $path ) ) {
				self::include_isolated( $path );
			}
		}

		{
			$path = self::get_parent_path( $rel_path );

			if ( file_exists( $path ) ) {
				self::include_isolated( $path );
			}
		}
	}

	/**
	 * @internal
	 */
	public static function coffeeking_action_enqueue_scripts() {
		self::include_child_first( '/static.php' );
	}

	/**
	 * @internal
	 */
	public static function coffeeking_action_init() {
		self::include_child_first( '/menus.php' );
		self::include_child_first( '/posts.php' );
	}

	/**
	 * @internal
	 */
	public static function coffeeking_action_widgets_init() {

		{
			$paths = array();

			/**
			 * Widgets list
			 */
			$parent_widgets = array(
				'icons',
			);
 
	 		if ( is_child_theme() ) {

				$child_path = self::get_child_path( '/widgets' );
			}

			$parent_path = self::get_parent_path( '/widgets' );
		}

		/**
		 * Generating widgets include array, child first
		 */
		$items = array();
 		if ( is_child_theme() ) {

			$child_widgets =  apply_filters ('coffeeking_filter_init_includes_widgets_list', array());
			if ( !empty( $child_widgets ) ) {

				foreach ( $child_widgets as $item ) {

					$items[] = array( 'path' => $child_path . '/' . $item . '/' , 'name' => $item );
				}
			}
 		}

		if ( !empty( $parent_widgets ) ) {

			foreach ( $parent_widgets as $item ) {

				$items[] = array( 'path' => $parent_path . '/' . $item . '/' , 'name' => $item );
			}
		}

		$included_widgets = array();
		if ( !empty( $items ) ) {

			foreach ( $items as $item ) {

				if ( isset( $included_widgets[ $item['name'] ] ) ) {
					// this happens when a widget in child theme wants to overwrite the widget from parent theme
					continue;
				} else {
					$included_widgets[ $item['name'] ] = true;
				}

				self::include_isolated( $item['path'] . '/class-widget-' . $item['name'] . '.php' );

				$widget_class = 'coffeeking_Widget_' . self::widget_classname( $item['name'] );
				if ( class_exists( $widget_class ) ) {

					register_widget( $widget_class );
				}
			}
		}
	}
}

coffeeking_Theme_Includes::init();
