<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Main class
 *
 * @author YITH
 * @package YITH\ZoomMagnifier\Classes
 * @version 1.1.2
 */

if ( ! defined( 'YITH_WCMG' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WooCommerce_Zoom_Magnifier' ) ) {
	/**
	 * YITH WooCommerce Product Gallery & Image Zoom
	 *
	 * @since 1.0.0
	 */
	class YITH_WooCommerce_Zoom_Magnifier {

		/**
		 * Plugin object
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $obj = null;

		/**
		 * Plugin panel page
		 *
		 * @var string
		 */
		protected $panel_page = 'yith_woocommerce_zoom-magnifier_panel';

		/**
		 * Constructor
		 *
		 * @return mixed|YITH_WCMG_Admin|YITH_WCMG_Frontend
		 * @since 1.0.0
		 */
		public function __construct() {

			add_action(
				'wp_ajax_nopriv_yith_wc_zoom_magnifier_get_main_image',
				array(
					$this,
					'yith_wc_zoom_magnifier_get_main_image_call_back',
				),
				10
			);

			add_action(
				'wp_ajax_yith_wc_zoom_magnifier_get_main_image',
				array(
					$this,
					'yith_wc_zoom_magnifier_get_main_image_call_back',
				),
				10
			);

			// actions.
			add_action( 'init', array( $this, 'init' ) );

			if ( is_admin() && ( ! isset( $_REQUEST['action'] ) || ( isset( $_REQUEST['action'] ) && 'yith_load_product_quick_view' !== $_REQUEST['action'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->obj = new YITH_WCMG_Admin();
			} else {


				/** Stop the plugin on mobile devices */
				if ( ( 'yes' == get_option( 'ywzm_hide_zoom_mobile' ) ) && wp_is_mobile() ) {
					return;
				}

				$this->obj = new YITH_WCMG_Frontend();
			}

			return $this->obj;
		}

		/**
		 * Init method:
		 *  - default options
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function init() {

			/* === Show Plugin Information === */
			add_filter( 'plugin_action_links_' . plugin_basename( YITH_YWZM_DIR . '/' . basename( YITH_YWZM_FILE ) ), array( $this, 'action_links' ) );
			add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );
		}

		/**
		 * Action links.
		 *
		 * @param array $links Action links.
		 * @since    1.4.1
		 * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
		 *
		 * @return array
		 */
		public function action_links( $links ) {
			$links = yith_add_action_links( $links, $this->panel_page, false, YITH_YWZM_SLUG );
			return $links;
		}
		/**
		 * Plugin Row Meta.
		 *
		 * @param mixed $new_row_meta_args Row meta args.
		 * @param mixed $plugin_meta Plugin meta.
		 * @param mixed $plugin_file Plugin file.
		 * @param mixed $plugin_data Plugin data.
		 * @param mixed $status Status.
		 * @param mixed $init_file Init file.
		 *
		 * @since    1.4.1
		 * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
		 *
		 * @return array
		 */
		public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_YWZM_FREE_INIT' ) {
			if ( defined( $init_file ) && constant( $init_file ) === $plugin_file ) {
				$new_row_meta_args['slug'] = YITH_YWZM_SLUG;
			}

			return $new_row_meta_args;
		}

		/**
		 * Ajax method to retrieve the product main imavge
		 *
		 * @access public
		 * @author Daniel Sanchez Saez
		 * @since  1.3.4
		 */
		public function yith_wc_zoom_magnifier_get_main_image_call_back() {

			// set the main wp query for the product.
			global $post, $product;

			$product_id = isset( $_POST['product_id'] ) ? $_POST['product_id'] : 0; // phpcs:ignore
			$post       = get_post( $product_id ); // phpcs:ignore
			$product    = wc_get_product( $product_id );

			if ( empty( $product ) ) {
				wp_send_json_error();
			}

			$url = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'full' );

			if ( function_exists( 'YITH_WCCL_Frontend' ) && function_exists( 'yith_wccl_get_variation_gallery' ) ) {

				$gallery = yith_wccl_get_variation_gallery( $product );
				// filter gallery based on current variation.
				if ( ! empty( $gallery ) ) {

					add_filter( 'woocommerce_product_variation_get_gallery_image_ids', array( YITH_WCCL_Frontend(), 'filter_gallery_ids' ), 10, 2 );
				}
			}

			ob_start();
			wc_get_template( 'single-product/product-thumbnails-magnifier.php', array(), '', YITH_YWZM_DIR . 'templates/' );
			$gallery_html = ob_get_clean();

			wp_send_json(
				array(
					'url'     => isset( $url[0] ) ? $url[0] : '',
					'gallery' => $gallery_html,
				)
			);

		}
	}
}
