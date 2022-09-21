<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH\ZoomMagnifier\Classes\Compatibility
 */

if ( ! defined( 'ABSPATH' ) || ! defined( 'YITH_YWZM_VERSION' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Implements the YITH_WCZM_Elementor_Compatibility class.
 *
 * @class   YITH_WCZM_Elementor_Compatibility
 * @since   1.5.6
 * @author  YITH
 */
if ( ! class_exists( 'YITH_WCZM_Elementor_Compatibility' ) ) {

	/**
	 * Class YITH_WCZM_Elementor_Compatibility
	 */
	class YITH_WCZM_Elementor_Compatibility {
		/**
		 * Single instance of the class
		 *
		 * @var YITH_WCZM_Elementor_Compatibility
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_WCZM_Elementor_Compatibility
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * YITH_WCZM_Elementor_Compatibility constructor.
		 */
		public function __construct() {
			if ( did_action( 'elementor/loaded' ) ) {
				add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_yith_widget_category' ) );
				add_action( 'elementor/widgets/widgets_registered', array( $this, 'elementor_init_widgets' ) );

			}
		}

		/**
		 * Add elementor category for YITH.
		 *
		 * @param mixed $elements_manager Elementor manager.
		 */
		public function add_elementor_yith_widget_category( $elements_manager ) {
			$elements_manager->add_category(
				'yith',
				array(
					'title' => 'YITH',
					'icon'  => 'fa fa-plug',
				)
			);

		}

		/**
		 * Initialize Elementor widget.
		 *
		 * @throws Exception OnCreateClass.
		 */
		public function elementor_init_widgets() {
			// Include Widget files.
			require_once YITH_YWZM_LIB_DIR . 'compatibility/elementor/class-yith-wczm-product-images-widget.php';

			// Register widget.
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \YITH_WCZM_Product_Images_Elementor_Widget() );

		}
	}

}

/**
 * Unique access to instance of YITH_WCZM_Elementor_Compatibility class
 *
 * @return YITH_WCZM_Elementor_Compatibility
 */
function yith_wczm_elementor_compatibility() {
	return YITH_WCZM_Elementor_Compatibility::get_instance();
}

yith_wczm_elementor_compatibility();
