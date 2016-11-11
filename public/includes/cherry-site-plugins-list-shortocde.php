<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Cherry_Site_Plugins_List_Shortcode' ) ) {

	/**
	 * Define Cherry_Site_Plugins_List_Shortcode class
	 */
	class Cherry_Site_Plugins_List_Shortcode {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		function __construct() {
			add_shortcode( 'cherry_plugins_list', array( $this, 'do_shortcode' ) );
		}

		/**
		 * Shortode callback
		 *
		 * @return string
		 */
		public function do_shortcode( $atts = array() ) {

			$atts = shortcode_atts(
				array(
					'download_text'    => 'Download',
					'per_page'         => 20,
					'template_listing' => 'list',
					'template_item'    => 'list-item',
				),
				$atts,
				'cherry_plugins_list'
			);

			$args = array(
				'per_page' => $atts['per_page'],
			);

			$template_listing = $this->locate_template( $atts['template_listing'] );
			$template_item    = $this->locate_template( $atts['template_item'] );

			$atts['template_listing'] = $template_listing;
			$atts['template_item']    = $template_item;

			$cached = $this->get_cache( $atts );

			if ( false !== $cached ) {
				return $cached;
			}


			$plugins = cs_plugins_list_api()->get_plugins( $args );

			if ( empty( $plugins ) ) {
				return;
			}

			ob_start();
			include $template_listing;
			$result = ob_get_clean();

			$this->set_cache( $atts, $result );

			return $result;
		}

		/**
		 * Returns plugin thumbnail image
		 *
		 * @param  object $plugin Current plugin object.
		 * @param  string $size   Thumb size.
		 * @param  string $class  CSS class.
		 * @return string
		 */
		public function plugin_thumb( $plugin = null, $size = 'icon-256x256', $class = 'plugin-thumb' ) {

			if ( ! is_object( $plugin ) ) {
				return '';
			}

			$format = apply_filters(
				'cherry-site-plugin-thumb-format',
				'<img src="%1$s" alt="%2$s" class="%3$s">'
			);

			$ext = '.png';

			if ( 'cherry-sidebars' == $plugin->slug ) {
				$ext = '.jpg';
			}

			$url = '//ps.w.org/' . $plugin->slug . '/assets/' . $size . $ext;

			return sprintf( $format, $url, esc_attr( $plugin->name ), esc_attr( $class ) );

		}

		/**
		 * Try to get cached shortcode content.
		 *
		 * @param  array $args Arguments array to define key
		 * @return string|bool
		 */
		public function get_cache( $args = array() ) {

			if ( defined( 'CHERRY_SITE_PLUGINS_DEBUG' ) && true === CHERRY_SITE_PLUGINS_DEBUG ) {
				return false;
			}

			return get_transient( $this->cahce_key( $args ) );
		}

		/**
		 * Set shortcode cache
		 *
		 * @param array   $args    Arguments array to define key.
		 * @param string  $content Shortcode content.
		 */
		public function set_cache( $args = array(), $content = null ) {

			if ( defined( 'CHERRY_SITE_PLUGINS_DEBUG' ) && true === CHERRY_SITE_PLUGINS_DEBUG ) {
				return false;
			}

			set_transient( $this->cahce_key( $args ), $content, 60 * 60 * 8 );

		}

		/**
		 * Return transient cache key for current shortcode.
		 *
		 * @param  array $args Arguments array to define key.
		 * @return string
		 */
		public function cahce_key( $args = array() ) {
			return md5( implode( '', $args ) );
		}

		/**
		 * Locate plugin tempaltes
		 *
		 * @param  strint $slug Template slug.
		 * @return string
		 */
		public function locate_template( $slug = null ) {

			$file          = $slug . '.php';
			$template_file = locate_template( cs_plugins_list()->template_path() . $file );

			if ( ! $template_file ) {
				$template_file = cs_plugins_list()->path( 'templates/' . $file );
			}

			if ( file_exists( $template_file ) ) {
				return $template_file;
			}

			return false;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of Cherry_Site_Plugins_List_Shortcode
 *
 * @return object
 */
function cs_plugins_list_shortcode() {
	return Cherry_Site_Plugins_List_Shortcode::get_instance();
}

cs_plugins_list_shortcode();
