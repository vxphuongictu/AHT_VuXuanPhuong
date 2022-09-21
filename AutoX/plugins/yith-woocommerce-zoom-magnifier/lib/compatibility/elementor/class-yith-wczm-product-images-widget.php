<?php // phpcs:ignore WordPress.Files.FileName
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH\ZoomMagnifier\Classes\Compatibility\Elementor
 */

use Elementor\Controls_Manager;
use Elementor\Widget_Button;
use ElementorPro\Modules\QueryControl\Module;

/**
 * Class YITH_WCZM_Product_Images_Elementor_Widget
 */
class YITH_WCZM_Product_Images_Elementor_Widget extends ElementorPro\Modules\Woocommerce\Widgets\Product_Images {

	/**
	 * Get name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'yith-wczm-auction-form';
	}

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Zoom Product images', 'yith-woocommerce-zoom-magnifier' );
	}


	/**
	 * Get categories
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'yith' );
	}

	/**
	 * Render
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		global $product;

		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		if ( 'yes' === $settings['sale_flash'] ) {
			wc_get_template( 'loop/sale-flash.php' );
		}
		wc_get_template( 'single-product/product-image-magnifier.php', array(), '', YITH_YWZM_DIR . 'templates/' );
		// On render widget from Editor - trigger the init manually.
		if ( wp_doing_ajax() ) {
			?>
			<script>
				jQuery( '.woocommerce-product-gallery' ).each( function() {
					jQuery( this ).wc_product_gallery();
				} );
			</script>
			<?php
		}
	}


}
