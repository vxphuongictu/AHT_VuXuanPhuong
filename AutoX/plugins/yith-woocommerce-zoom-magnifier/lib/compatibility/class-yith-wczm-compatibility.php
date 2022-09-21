<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH\ZoomMagnifier\Classes\Compatibility
 */

if ( ! defined( 'YITH_YWZM_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

/**
 * Implements the YITH_WCZM_Compatibility class.
 *
 * @class  YITH_WCZM_Compatibility
 * @since  1.5.6
 * @author YITH
 */
if ( ! class_exists( 'YITH_WCZM_Compatibility' ) ) {
	/**
	 * Class YITH_WCZM_Compatibility
	 */
	class YITH_WCZM_Compatibility {

		/**
		 * Single instance of the class
		 *
		 * @var YITH_WCZM_Compatibility
		 */
		protected static $instance;

		/**
		 * Plugins
		 *
		 * @var array
		 */
		protected $_plugins = array(); // phpcs:ignore

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_WCZM_Compatibility
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * YITH_WCZM_Compatibility constructor.
		 */
		public function __construct() {
			$this->_plugins = array(
				'elementor' => 'Elementor',
			);
			$this->_load();
		}

		/**
		 * _load
		 */
		private function _load() { // phpcs:ignore
			foreach ( $this->_plugins as $slug => $class_slug ) {
				$filename  = YITH_YWZM_LIB_DIR . 'compatibility/class-yith-wczm-' . $slug . '-compatibility.php';
				$classname = 'YITH_WCZM_' . $class_slug . '_Compatibility';
				$var       = str_replace( '-', '_', $slug );
				if ( $this::has_plugin( $slug ) && file_exists( $filename ) && ! function_exists( $classname ) ) {
					require_once $filename;
				}

				if ( function_exists( $classname ) ) {
					$this->$var = $classname();
				}
			}
		}

		/**
		 * Has plugin
		 *
		 * @param  mixed $slug The slug.
		 *
		 * @return bool
		 */
		public static function has_plugin( $slug ) {
			switch ( $slug ) {
				case 'elementor':
					return defined( 'ELEMENTOR_VERSION' ) && ELEMENTOR_VERSION;
				default:
					return false;
			}
		}
	}
}


/**
 * Unique access to instance of YITH_WCZM_Compatibility class
 *
 * @return YITH_WCZM_Compatibility
 */
function yith_wczm_compatibility() {
	return YITH_WCZM_Compatibility::get_instance();
}

yith_wczm_compatibility();
