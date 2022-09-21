<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH WooCommerce Product Gallery & Image Zoom
 * @since   2.0.0
 * @author  YITH
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'image' => array(
		'image-options' => array(
			'type'     => 'multi_tab',
			'sub-tabs' => array(
				'image-zoom'     => array(
					'title' => esc_html_x( 'Zoom options', 'Admin title of tab', 'yith-woocommerce-zoom-magnifier' ),
				),
//				'image-navigation' => array(
//					'title' => esc_html_x( 'Navigation', 'Admin title of tab', 'yith-woocommerce-zoom-magnifier' ),
//				),
				'image-lightbox' => array(
					'title' => esc_html_x( 'Lightbox', 'Admin title of tab', 'yith-woocommerce-zoom-magnifier' ),
				),
			),
		),
	),
);
