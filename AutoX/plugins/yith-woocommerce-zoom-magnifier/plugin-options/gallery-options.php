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

$gallery_settings = array(
	array(
		'name' => __( 'Product gallery options', 'yith-woocommerce-zoom-magnifier' ),
		'type' => 'title',
		'desc' => '',
		'id'   => 'yith_wcmg_slider',
	),
	array(
		'name'      => __( 'Hide gallery thumbnails from single product page', 'yith-woocommerce-zoom-magnifier' ),
		'desc'      => __( 'Enable to hide the product gallery thumbs from the single product details page.', 'yith-woocommerce-zoom-magnifier' ),
		'id'        => 'ywzm_hide_thumbnails',
		'default'   => 'no',
		'type'      => 'yith-field',
		'yith-type' => 'onoff',
	),
	array(
		'name'      => __( 'Enable Slider', 'yith-woocommerce-zoom-magnifier' ),
		'desc'      => __( 'Enable the thumbnails slider.', 'yith-woocommerce-zoom-magnifier' ),
		'id'        => 'yith_wcmg_enableslider',
		'std'       => 'yes',
		'default'   => 'yes',
		'type'      => 'yith-field',
		'yith-type' => 'onoff',
		'deps'      => array(
			'id'    => 'ywzm_hide_thumbnails',
			'value' => 'no',
		)
	),
	array(
		'name'      => __( 'Thumbnails to show', 'yith-woocommerce-zoom-magnifier' ),
		'desc'      => __( 'Set how many thumbnails to show. You can show max 10 thumbnails.', 'yith-woocommerce-zoom-magnifier' ),
		'id'        => 'yith_wcmg_slider_items',
		'default'   => 3,
		'type'      => 'yith-field',
		'yith-type' => 'slider',
		'option'    => array(
			'min' => 1,
			'max' => 10,
		),
		'step'      => 1,
		'deps'      => array(
			'id'    => 'yith_wcmg_enableslider',
			'value' => 'yes',
		)
	),
	array(
		'name'      => __( 'Slider colors', 'yith-woocommerce-zoom-magnifier' ),
		'id'        => 'yith_wcmg_slider_style_colors',
		'type'      => 'yith-field',
		'yith-type' => 'multi-colorpicker',
		'colorpickers'  => array(
			array(
				'id' => 'background',
				'name' => __( 'Background color', 'yith-woocommerce-zoom-magnifier' ),
				'default' => ' #ffffff'
			),
			array(
				'id' => 'border',
				'name' => __( 'Border color', 'yith-woocommerce-zoom-magnifier' ),
				'default' => '#000000'
			),
			array(
				'id' => 'arrow',
				'name' => __( 'Arrow color', 'yith-woocommerce-zoom-magnifier' ),
				'default' => '#000000'
			),
		),
		'deps'      => array(
		'id'    => 'yith_wcmg_enableslider',
		'value' => 'yes',
		)
	),
	array(
		'desc'    => __( 'Set colors of the slider elements.', 'yith-woocommerce-zoom-magnifier' ),
		'id'        => 'yith_wcmg_slider_style_colors_hover',
		'type'      => 'yith-field',
		'yith-type'         => 'multi-colorpicker',
		'colorpickers'  => array(
			array(
				'id' => 'background',
				'name' => __( 'Background color hover', 'yith-woocommerce-zoom-magnifier' ),
				'default' => ' #ffffff'
			),
			array(
				'id' => 'border',
				'name' => __( 'Border color hover', 'yith-woocommerce-zoom-magnifier' ),
				'default' => '#000000'
			),
			array(
				'id' => 'arrow',
				'name' => __( 'Arrow color hover', 'yith-woocommerce-zoom-magnifier' ),
				'default' => '#000000'
			),
		),
		'deps'      => array(
			'id'    => 'yith_wcmg_enableslider',
			'value' => 'yes',
		)
	),
	array(
		'id'      => 'yith_wcmg_slider_sizes',
		'name'    => __( 'Slider sizes (px)', 'yith-woocommerce-zoom-magnifier' ),
		'desc'    => __( 'Set the size of the slider elements.', 'yith-woocommerce-zoom-magnifier' ),
		'type'    => 'yith-field',
		'yith-type' => 'dimensions',
		'dimensions' => array(
			'slider'    => __( 'Slider', 'yith-woocommerce-zoom-magnifier' ),
			'arrow'  =>  __( 'Arrow', 'yith-woocommerce-zoom-magnifier' ),
			'border' =>  __( 'Border', 'yith-woocommerce-zoom-magnifier' ),
		),
		'units'      => array(),
		'allow_linked'     => 'no',
		'min'        => 0,
		'default' => array(
			'dimensions' => array(
				'slider'    => 25,
				'arrow'  => 22,
				'border' => 2,
			),
			'linked'     => 'no',
		),
		'deps'      => array(
			'id'    => 'yith_wcmg_enableslider',
			'value' => 'yes',
		)
	),
	array(
		'name'      => __( 'Infinite slider', 'yith-woocommerce-zoom-magnifier' ),
		'desc'      => __( 'Enable to set the slider as infinite. At the last thumbnail, the slider will start again from the 1st image.', 'yith-woocommerce-zoom-magnifier' ),
		'id'        => 'yith_wcmg_slider_infinite',
		'std'       => 'yes',
		'default'   => 'yes',
		'type'      => 'yith-field',
		'yith-type' => 'onoff',
		'deps'      => array(
			'id'    => 'yith_wcmg_enableslider',
			'value' => 'yes',
		)
	),
	array(
		'name'      => __( 'Infinite slider type', 'yith-woocommerce-zoom-magnifier' ),
		'desc'      => __( 'Choose the infinite slider type to apply.', 'yith-woocommerce-zoom-magnifier' ),
		'id'        => 'yith_wcmg_slider_infinite_type',
		'type'      => 'yith-field',
		'yith-type' => 'radio',
		'options'   => array(
			'circular' => esc_html__( 'Circular - the thumbnail after the last one will be the 1st of the list and the slider will start again.', 'yith-woocommerce-zoom-magnifier' ),
			'back'  =>  esc_html__( 'Back to the 1st - after the last thumbnail the slider goes back and jumps to the 1st thumbnail.', 'yith-woocommerce-zoom-magnifier' ),
		),
		'default'   => 'circular',
		'deps'      => array(
			'id'    => 'yith_wcmg_enableslider',
			'value' => 'yes',
		)
	),
	array(
		'name'      => __( 'Autoplay slider', 'yith-woocommerce-zoom-magnifier' ),
		'desc'      => __( 'Enable to set the slider in autoplay mode.', 'yith-woocommerce-zoom-magnifier' ),
		'id'        => 'ywzm_auto_carousel',
		'std'       => 'no',
		'default'   => 'no',
		'type'      => 'yith-field',
		'yith-type' => 'onoff',
		'deps'      => array(
			'id'    => 'yith_wcmg_enableslider',
			'value' => 'yes',
		)
	),
	array(
		'type' => 'sectionend',
		'id'   => 'yith_wcmg_slider_end',
	),
);

$gallery_settings   = apply_filters( 'yith_ywzm_general_settings', $gallery_settings );

$options['gallery'] = array();

$options['gallery'] = array_merge( $options['gallery'], $gallery_settings );

return apply_filters( 'yith_wcmg_tab_options', $options );
