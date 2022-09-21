<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Plugin FW Loader class
 *
 * @author YITH
 * @package YITH\ZoomMagnifier\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'YITH_YWZM_Plugin_FW_Loader' ) ) {

	/**
	 * Implements features related to an invoice document
	 */
	class YITH_YWZM_Plugin_FW_Loader {

		/**
		 * Panel Object
		 *
		 * @var $panel
		 */
		protected $panel;

		/**
		 * Premium tab template file name
		 *
		 * @var $_premium
		 */
		protected $_premium = 'premium.php';

		/**
		 * Premium version landing link
		 *
		 * @var string
		 */
		protected $_premium_landing = 'https://yithemes.com/themes/plugins/yith-woocommerce-zoom-magnifier/';

		/**
		 * Plugin official documentation
		 *
		 * @var string
		 */
		protected $official_documentation = 'https://docs.yithemes.com/yith-woocommerce-zoom-magnifier/';

		/**
		 * Plugin panel page
		 *
		 * @var string
		 */
		protected $panel_page = 'yith_woocommerce_zoom-magnifier_panel';

		/**
		 * Single instance of the class
		 *
		 * @var YITH_YWZM_Plugin_FW_Loader
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			/**
			 * Register actions and filters to be used for creating an entry on YIT Plugin menu
			 */
			add_action( 'admin_init', array( $this, 'register_pointer' ) );

			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );

			// Add stylesheets and scripts files.
			add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );

			if ( ! defined( 'YITH_YWZM_PREMIUM' ) ) {

				// Show plugin premium tab.
				add_action( 'yith_zoom_magnifier_premium', array( $this, 'premium_tab' ) );
			} else {
				/**
				 * Register plugin to licence/update system.
				 */
				$this->licence_activation();
			}
		}


		/**
		 * Load YIT core plugin
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 * @author Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @return   void
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use      /Yit_Plugin_Panel class
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function register_panel() {

			if ( ! empty( $this->panel ) ) {
				return;
			}

			$admin_tabs['image'] = esc_html__( 'Main image options', 'yith-woocommerce-zoom-magnifier' );
			$admin_tabs['gallery'] = esc_html__( 'Product gallery options', 'yith-woocommerce-zoom-magnifier' );
			$admin_tabs['premium'] = esc_html__( 'Premium Version', 'yith-woocommerce-zoom-magnifier' );

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'page_title'       => 'YITH WooCommerce Product Gallery & Image Zoom',
				'menu_title'       => 'Product Gallery & Image Zoom',
				'capability'       => 'manage_options',
				'parent'           => '',
				'parent_page'      => 'yit_plugin_panel',
				'page'             => $this->panel_page,
				'admin-tabs'       => $admin_tabs,
				'class'            => yith_set_wrapper_class(),
				'options-path'     => YITH_YWZM_DIR . '/plugin-options',
				'plugin_slug'      => YITH_YWZM_SLUG,
			);

			/* === Fixed: not updated theme  === */
			if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {

				require_once 'plugin-fw/lib/yit-plugin-panel-wc.php';
			}

			$this->panel = new YIT_Plugin_Panel_WooCommerce( $args );

		}

		/**
		 * Premium Tab Template
		 *
		 * Load the premium tab template on admin page
		 *
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @return void
		 */
		public function premium_tab() {
			$premium_tab_template = YITH_YWZM_TEMPLATE_DIR . '/admin/' . $this->_premium;
			if ( file_exists( $premium_tab_template ) ) {
				include_once( $premium_tab_template );
			}
		}

		/**
		 * Register pointer.
		 */
		public function register_pointer() {
			if ( ! class_exists( 'YIT_Pointers' ) ) {
				include_once 'plugin-fw/lib/yit-pointers.php';
			}

			$premium_message = defined( 'YITH_YWZM_PREMIUM' )
				? ''
				: esc_html__( 'YITH WooCommerce Product Gallery & Image Zoom  is available in an outstanding PREMIUM version with many new options, discover it now.', 'yith-woocommerce-zoom-magnifier' ) .
				' <a href="' . $this->_premium_landing . '">' . esc_html__( 'Premium version', 'yith-woocommerce-zoom-magnifier' ) . '</a>';

			$args[] = array(
				'screen_id'  => 'plugins',
				'pointer_id' => 'yith_woocommerce_zoom-magnifier',
				'target'     => '#toplevel_page_yit_plugin_panel',
				'content'    => sprintf(
					'<h3> %s </h3> <p> %s </p>',
					esc_html__( 'YITH WooCommerce Product Gallery & Image Zoom ', 'yith-woocommerce-zoom-magnifier' ),
					esc_html__( 'In YIT Plugins tab you can find  YITH WooCommerce Product Gallery & Image Zoom  options.<br> From this menu you can access all settings of the YITH plugins activated.', 'yith-woocommerce-zoom-magnifier' ) . '<br>' . $premium_message
				),
				'position'   => array(
					'edge'  => 'left',
					'align' => 'center',
				),
				'init'       => defined( 'YITH_YWZM_PREMIUM' ) ? YITH_YWZM_INIT : YITH_YWZM_FREE_INIT,
			);

			YIT_Pointers()->register( $args );
		}

		/**
		 * Get the premium landing uri
		 *
		 * @since   1.0.0
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 * @return  string The premium landing link
		 */
		public function get_premium_landing_uri() {
			return apply_filters( 'yith_plugin_fw_premium_landing_uri', $this->_premium_landing, YITH_YWZM_SLUG );
		}

		// region    ****    licence related methods ****.

		/**
		 * Add actions to manage licence activation and updates
		 */
		public function licence_activation() {
			if ( ! defined( 'YITH_YWZM_PREMIUM' ) ) {
				return;
			}

			add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
			add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );
		}

		/**
		 * Register plugins for activation tab
		 *
		 * @return void
		 * @since    2.0.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function register_plugin_for_activation() {
			if ( ! class_exists( 'YIT_Plugin_Licence' ) ) {
				require_once 'plugin-fw/lib/yit-plugin-licence.php';
			}
			YIT_Plugin_Licence()->register( YITH_YWZM_INIT, YITH_YWZM_SECRET_KEY, YITH_YWZM_SLUG );
		}

		/**
		 * Register plugins for update tab
		 *
		 * @return void
		 * @since    2.0.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function register_plugin_for_updates() {
			if ( ! class_exists( 'YIT_Upgrade' ) ) {
				require_once 'plugin-fw/lib/yit-upgrade.php';
			}
			YIT_Upgrade()->register( YITH_YWZM_SLUG, YITH_YWZM_INIT );
		}
		// endregion.
	}
}
