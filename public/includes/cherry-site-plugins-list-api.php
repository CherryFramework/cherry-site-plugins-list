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

if ( ! class_exists( 'Cherry_Site_Plugins_List_API' ) ) {

	/**
	 * Define Cherry_Site_Plugins_List_API class
	 */
	class Cherry_Site_Plugins_List_API {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Cherry tag to get plugins by
		 *
		 * @var string
		 */
		private $tag = 'cherry-framework';

		/**
		 * Retrieve a list of plugins tagged with cherry-framework tag
		 *
		 * @return array
		 */
		public function get_plugins( $args = array() ) {

			$action   = 'query_plugins';
			$defaults = array(
				'tag'    => $this->tag,
				'fields' => array(
					'downloaded'   => true,
					'downloadlink' => true,
				),
			);

			$args = wp_parse_args( $args, $defaults );

			if ( ! function_exists( 'plugins_api' ) ) {
				require $this->get_admin_path( 'includes/plugin-install.php' );
			}

			$response = plugins_api( $action, $args );

			if ( ! empty( $response->results ) ) {
				update_option( 'cherry_plugins_counter', $response['results'] );
			}

			if ( ! empty( $response->plugins ) ) {
				return $response->plugins;
			} else {
				return array();
			}

		}

		/**
		 * Returns path inside wp-admin dir
		 * @param  string $path Path to return.
		 * @return string
		 */
		public function get_admin_path( $path = null ) {

			$path = str_replace( get_bloginfo( 'url' ) . '/', ABSPATH, get_admin_url() ) . $path;

			if ( file_exists( $path ) ) {
				return $path;
			}
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
 * Returns instance of Cherry_Site_Plugins_List_API
 *
 * @return object
 */
function cs_plugins_list_api() {
	return Cherry_Site_Plugins_List_API::get_instance();
}
