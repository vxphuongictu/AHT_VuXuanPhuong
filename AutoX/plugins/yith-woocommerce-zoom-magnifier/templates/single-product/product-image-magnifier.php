<?php
/**
 * Single Product Image
 *
 * @author YITH
 * @package YITH\ZoomMagnifier\Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post, $woocommerce, $product, $is_IE;

$enable_slider = get_option( 'yith_wcmg_enableslider' ) === 'yes' ? true : false;

$placeholder = function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src() : woocommerce_placeholder_img_src();

$slider_items = get_option( 'yith_wcmg_slider_items', 3 );
if ( ! isset( $slider_items ) || ( null === $slider_items ) ) {
	$slider_items = 3;
}

$extra_classes = apply_filters( 'yith_wcmg_single_product_image_extra_classes', array() );
if ( is_array( $extra_classes ) ) {
	$extra_classes = implode( ' ', $extra_classes );
}


$infinite = apply_filters( 'yith_wcmg_slider_infinite', get_option( 'yith_wcmg_slider_infinite', 'yes'  ) ) === 'yes' ? 'true' : 'false';
$circular =  apply_filters( 'yith_wcmg_slider_infinite_type', get_option( 'yith_wcmg_slider_infinite_type', 'circular'  ) ) === 'circular' && $infinite === 'true' ? 'true' : 'false';
$auto_slider =  ( 'yes' === get_option( 'ywzm_auto_carousel', 'no' ) ) ? 'true' : 'false';

?>
<input type="hidden" id="yith_wczm_traffic_light" value="free">

<div class="images
<?php
if ( $is_IE ) :
	?>
	ie<?php endif ?>">

	<?php
	if ( has_post_thumbnail() ) {

		$image       = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );
		$image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
		$image_link  = wp_get_attachment_url( get_post_thumbnail_id() );
		list( $magnifier_url, $magnifier_width, $magnifier_height ) = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

		echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="woocommerce-product-gallery__image %s"><a href="%s" itemprop="image" class="yith_magnifier_zoom woocommerce-main-image" title="%s">%s</a></div>', $extra_classes, $magnifier_url, $image_title, $image ), $post->ID ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

	} else {
		echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="yith_magnifier_zoom woocommerce-main-image %s"><img src="%s" alt="Placeholder" /></a>', $placeholder, $extra_classes, $placeholder ), $post->ID ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
	}
	?>

	<div class="expand-button-hidden" style="display: none;">
	<svg width="19px" height="19px" viewBox="0 0 19 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
		<defs>
			<rect id="path-1" x="0" y="0" width="30" height="30"></rect>
		</defs>
		<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
			<g id="Product-page---example-1" transform="translate(-940.000000, -1014.000000)">
				<g id="arrow-/-expand" transform="translate(934.500000, 1008.500000)">
					<mask id="mask-2" fill="white">
						<use xlink:href="#path-1"></use>
					</mask>
					<g id="arrow-/-expand-(Background/Mask)"></g>
					<path d="M21.25,8.75 L15,8.75 L15,6.25 L23.75,6.25 L23.740468,15.0000006 L21.25,15.0000006 L21.25,8.75 Z M8.75,21.25 L15,21.25 L15,23.75 L6.25,23.75 L6.25953334,14.9999988 L8.75,14.9999988 L8.75,21.25 Z" fill="#000000" mask="url(#mask-2)"></path>
				</g>
			</g>
		</g>
	</svg>
	</div>

	<div class="zoom-button-hidden" style="display: none;">
		<svg width="22px" height="22px" viewBox="0 0 22 22" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
			<defs>
				<rect id="path-1" x="0" y="0" width="30" height="30"></rect>
			</defs>
			<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
				<g id="Product-page---example-1" transform="translate(-990.000000, -1013.000000)">
					<g id="edit-/-search" transform="translate(986.000000, 1010.000000)">
						<mask id="mask-2" fill="white">
							<use xlink:href="#path-1"></use>
						</mask>
						<g id="edit-/-search-(Background/Mask)"></g>
						<path d="M17.9704714,15.5960917 C20.0578816,12.6670864 19.7876957,8.57448101 17.1599138,5.94669908 C14.2309815,3.01776677 9.4822444,3.01776707 6.55331239,5.94669908 C3.62438008,8.87563139 3.62438008,13.6243683 6.55331239,16.5533006 C9.18109432,19.1810825 13.2736993,19.4512688 16.2027049,17.3638582 L23.3470976,24.5082521 L25.1148653,22.7404845 L17.9704714,15.5960917 C19.3620782,13.6434215 19.3620782,13.6434215 17.9704714,15.5960917 Z M15.3921473,7.71446586 C17.3447686,9.6670872 17.3447686,12.8329128 15.3921473,14.7855341 C13.4395258,16.7381556 10.273701,16.7381555 8.32107961,14.7855341 C6.36845812,12.8329127 6.36845812,9.66708735 8.32107961,7.71446586 C10.273701,5.76184452 13.4395258,5.76184437 15.3921473,7.71446586 C16.6938949,9.01621342 16.6938949,9.01621342 15.3921473,7.71446586 Z" fill="#000000" mask="url(#mask-2)"></path>
					</g>
				</g>
			</g>
		</svg>

	</div>


	<?php do_action( 'woocommerce_product_thumbnails' ); ?>

</div>


<script type="text/javascript" charset="utf-8">

	var yith_magnifier_options = {
		enableSlider: <?php echo $enable_slider ? 'true' : 'false'; ?>,

		<?php if ( $enable_slider ) : ?>
		sliderOptions: {
			responsive: 'true',
			circular: <?php echo $circular ?>,
			infinite: <?php echo $infinite ?>,
			direction: 'left',
			debug: false,
			auto: <?php echo $auto_slider; ?>,
			align: 'left',
			prev: {
				button: "#slider-prev",
				key: "left"
			},
			next: {
				button: "#slider-next",
				key: "right"
			},
			scroll: {
				items: 1,
				pauseOnHover: true
			},
			items: {
				visible: <?php echo esc_html( apply_filters( 'woocommerce_product_thumbnails_columns', $slider_items ) ); ?>
			}
		},

		<?php endif ?>


		<?php

		$sizes_default = Array(
			'dimensions' => array(
				'width' => '0',
				'height' => '0',
			));


		$zoom_window_sizes = get_option( 'ywzm_zoom_window_sizes', $sizes_default );

		$zoom_window_width = $zoom_window_sizes['dimensions']['width'];
		$zoom_window_height = $zoom_window_sizes['dimensions']['height'];

		if ( $zoom_window_width == '0' ){
			$zoom_window_width = 'auto';
		}

		if ( $zoom_window_height == '0' ){
			$zoom_window_height = 'auto';
		}

		?>

		showTitle: false,
		zoomWidth: '<?php echo esc_html( $zoom_window_width ); ?>',
		zoomHeight: '<?php echo esc_html(  $zoom_window_height ); ?>',
		position: '<?php echo apply_filters( 'yith_wcmg_zoom_position', esc_html( get_option( 'yith_wcmg_zoom_position' ) ) ); ?>',
		softFocus: <?php echo get_option( 'yith_wcmg_softfocus' ) === 'yes' ? 'true' : 'false'; ?>,
		adjustY: 0,
		disableRightClick: false,
		phoneBehavior: '<?php echo apply_filters( 'yith_wcmg_zoom_position', esc_html( get_option( 'yith_wcmg_zoom_position' ) ) ); ?>',
		zoom_wrap_additional_css: '<?php echo esc_html( apply_filters( 'yith_ywzm_zoom_wrap_additional_css', '', $post->ID ) ); ?>',
		lensOpacity: '<?php echo esc_html( get_option( 'yith_wcmg_lens_opacity' ), '0.5' ); ?>',
		loadingLabel: '<?php echo esc_html( stripslashes( get_option( 'yith_wcmg_loading_label' ) ) ); ?>',
	};

</script>
