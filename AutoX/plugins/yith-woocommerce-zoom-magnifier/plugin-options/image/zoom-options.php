<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH\ZoomMagnifier
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


$image_settings = array(
	array(
		'type' => 'title',
		'desc' => '',
		'id'   => 'yith_wcmg_zoom_settings',
	),
//	array(
//		'name'      => __( 'Enable zoom feature', 'yith-woocommerce-zoom-magnifier' ),
//		'desc'      => __( 'Enable the zoom feature in the main product image', 'yith-woocommerce-zoom-magnifier' ),
//		'id'        => 'ywzm_enable_zoom_feature',
//		'std'       => 'yes',
//		'default'   => 'yes',
//		'type'      => 'yith-field',
//		'yith-type' => 'onoff',
//	),
	array(
		'name'      => __( 'Hide zoom on mobile devices', 'yith-woocommerce-zoom-magnifier' ),
		'desc'      => __( 'Enable to hide the zoom on mobile devices', 'yith-woocommerce-zoom-magnifier' ),
		'id'        => 'ywzm_hide_zoom_mobile',
		'std'       => 'yes',
		'default'   => 'yes',
		'type'      => 'yith-field',
		'yith-type' => 'onoff',
	),
	array(
		'name'    => __( 'Zoom window position', 'yith-woocommerce-zoom-magnifier' ),
		'desc'    => __( 'Choose on which position to open the zoom window.', 'yith-woocommerce-zoom-magnifier' ),
		'id'      => 'yith_wcmg_zoom_position',
		'std'     => 'right',
		'default' => 'right',
		'type'      => 'yith-field',
		'yith-type' => 'select',
		'class'   => 'wc-enhanced-select',
		'options' => array(
			'right'  => __( 'Right side', 'yith-woocommerce-zoom-magnifier' ),
			'inside' => __( 'Inside', 'yith-woocommerce-zoom-magnifier' ),
		),
	),
	array(
		'id'      => 'ywzm_zoom_window_sizes',
		'name'    => __( 'Zoom window sizes (px)', 'yith-woocommerce-zoom-magnifier' ),
		'desc'    => __( 'Set the zoom window width and height.', 'yith-woocommerce-zoom-magnifier' ),
		'type'    => 'yith-field',
		'yith-type' => 'dimensions',
		'dimensions' => array(
			'width'    => __( 'Width', 'yith-woocommerce-zoom-magnifier' ),
			'height'  =>  __( 'Height', 'yith-woocommerce-zoom-magnifier' ),
		),
		'units'      => array(),
		'allow_linked'     => 'yes',
		'min'        => 0,
		'default' => array(
			'dimensions' => array(
				'width'    => '0',
				'height'  => '0',
			),
			'linked'     => 'no',
		),
		'deps'      => array(
			'id'    => 'yith_wcmg_zoom_position',
			'value' => 'right',
		),

	),
	array(
		'name'    => __( 'Loading label', 'yith-woocommerce-zoom-magnifier' ),
		'desc'    => __( 'Choose the text to show when the zoom is loading.', 'yith-woocommerce-zoom-magnifier' ),
		'id'      => 'yith_wcmg_loading_label',
		'std'     => __( 'Loading...', 'yith-woocommerce-zoom-magnifier' ),
		'default' => __( 'Loading...', 'yith-woocommerce-zoom-magnifier' ),
		'type'      => 'yith-field',
		'yith-type' => 'text',
	),
	array(
		'name'      => __( 'Lens border opacity', 'yith-woocommerce-zoom-magnifier' ),
		'desc'      => __( 'Set the opacity of the zoom lens border.', 'yith-woocommerce-zoom-magnifier' ),
		'id'        => 'yith_wcmg_lens_opacity',
		'type'      => 'yith-field',
		'yith-type' => 'slider',
		'option'    => array(
			'min' => 0,
			'max' => 1,
		),
		'step'      => .1,
		'default'   => 0.5,
	),

	array(
		'name'      => __( 'Blur main image', 'yith-woocommerce-zoom-magnifier' ),
		'desc'      => __( 'Enable to blur the main image when zooming.', 'yith-woocommerce-zoom-magnifier' ),
		'id'        => 'yith_wcmg_softfocus',
		'std'       => 'no',
		'default'   => 'no',
		'type'      => 'yith-field',
		'yith-type' => 'onoff',
	),
	array(
		'type' => 'sectionend',
		'id'   => 'yith_wcmg_zoom_settings_end',
	),
);

return array( 'image-zoom' => apply_filters( 'yith_wcmg_tab_options', $image_settings ) );
