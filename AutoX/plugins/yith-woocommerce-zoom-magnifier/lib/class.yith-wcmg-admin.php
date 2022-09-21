<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Admin class
 *
 * @author YITH
 * @package YITH\ZoomMagnifier\Classes
 * @version 1.1.2
 */

if ( ! defined( 'YITH_WCMG' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCMG_Admin' ) ) {
	/**
	 * Admin class.
	 * The class manage all the admin behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WCMG_Admin {
		/**
		 * Plugin options
		 *
		 * @var array
		 * @access public
		 * @since  1.0.0
		 */
		public $options = array();

		/**
		 * Docs URL.
		 *
		 * @var string
		 * @access public
		 * @since  1.0.0
		 */
		public $doc_url = 'https://docs.yithemes.com/yith-woocommerce-zoom-magnifier/';

		/**
		 * Constructor
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function __construct() {

			// Actions.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );

			// YITH WCMG Loaded.
			do_action( 'yith_wcmg_loaded' );
		}

		/**
		 * Enqueue admin styles and scripts
		 *
		 * @access public
		 * @return void
		 * @since  1.0.0
		 */
		public function enqueue_styles_scripts() {

			wp_enqueue_script( 'jquery-ui' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-mouse' );
			wp_enqueue_script( 'jquery-ui-slider' );

			wp_enqueue_style( 'yith_wcmg_admin', YITH_WCMG_URL . 'assets/css/admin.css', array(), YITH_YWZM_SCRIPT_VERSION );

			if ( isset( $_REQUEST[ 'page' ] ) && $_REQUEST[ 'page' ] === 'yith_woocommerce_zoom-magnifier_panel' ){

				wp_register_script(
					'ywzm_backend',
					YITH_YWZM_ASSETS_URL . '/js/ywzm_backend.js',
					array(
						'jquery',
					),
					YITH_YWZM_SCRIPT_VERSION,
					true
				);

				wp_localize_script(
					'ywzm_backend',
					'ywzm_data',
					array()
				);

				wp_enqueue_script( 'ywzm_backend' );

			}



		}
	}
}
