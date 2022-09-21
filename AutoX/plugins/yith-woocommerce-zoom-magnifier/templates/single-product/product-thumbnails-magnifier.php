<?php
/**
 * Single Product Image
 *
 * @author YITH
 * @package YITH\ZoomMagnifier\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post, $product, $woocommerce;

$enable_slider     = get_option( 'yith_wcmg_enableslider' ) === 'yes' ? true : false;
$post_thumbnail_id = apply_filters( 'yith_wcmg_get_post_thumbnail_id', get_post_thumbnail_id(), $post->ID );

$attachment_ids = $product->get_gallery_image_ids();
if ( ! empty( $attachment_ids ) ) {
	array_unshift( $attachment_ids, $post_thumbnail_id );
}

// make sure attachments ids are unique.
$attachment_ids = array_unique( $attachment_ids );
if ( $attachment_ids ) {

	$columns = apply_filters( 'woocommerce_product_thumbnails_columns', get_option( 'yith_wcmg_slider_items', 3 ) );
	if ( ! isset( $columns ) || null === $columns ) {
		$columns = 3;
	}

	$li_width = floor( 90 / $columns );
	$li_width = 90 / $columns;

	$li_margins_l_r = 10 / ( $columns * 2 );

	$li_style = apply_filters( 'woocommerce_single_product_image_thumbnail_html_cloumns_style', 'width: ' . $li_width . '%; margin-left: ' . $li_margins_l_r . '%; margin-right: ' . $li_margins_l_r . '%;', $columns );


	$slider_type = apply_filters( 'yith_wcmg_slider_infinite_type', get_option( 'yith_wcmg_slider_infinite_type', 'circular') );
	$infinite = apply_filters( 'yith_wcmg_slider_infinite', get_option( 'yith_wcmg_slider_infinite', 'yes'  ) ) === 'yes' ? 'true' : 'false';
	$is_circular = $slider_type === 'circular' && $infinite === 'true' ? 'yes' : 'no';

	?>

	<div class="thumbnails <?php echo $enable_slider ? 'slider' : 'noslider'; ?>">
		<ul class="yith_magnifier_gallery" data-columns="<?php echo esc_attr( $columns ); ?>" data-circular="<?php echo esc_attr( $is_circular ); ?>" data-slider_infinite="<?php echo esc_attr( get_option( 'yith_wcmg_slider_infinite' ) ); ?>" data-auto_carousel="<?php echo esc_attr( get_option( 'ywzm_auto_carousel' ) ); ?>">
			<?php
			$loop = 1;

			foreach ( $attachment_ids as $attachment_id ) {

				$classes = array( 'yith_magnifier_thumbnail' );

				if ( 1 === $loop ) {
					$classes[] = 'first active-thumbnail';

				}
				else{
					$classes[] = 'inactive-thumbnail';
				}

				if ( intval( $columns ) === $loop ) {
					$classes[] = 'last';
				}

				if ( $loop > $columns ) {
					$li_style = apply_filters( 'woocommerce_single_product_image_thumbnail_html_cloumns_style', 'display: none; width: ' . $li_width . '%; margin-left:' . $li_margins_l_r . '%; margin-right: ' . $li_margins_l_r . '%;', $columns );
				}

				$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
				$image_class = esc_attr( implode( ' ', $classes ) );
				$image_title = apply_filters( 'ywcmg_get_image_title', esc_attr( get_the_title( $attachment_id ) ), $attachment_id );

				list( $thumbnail_url, $thumbnail_width, $thumbnail_height ) = wp_get_attachment_image_src( $attachment_id, apply_filters( 'yith_zoom_magnifier_thumbnail_size', 'shop_single' ) );
				list( $magnifier_url, $magnifier_width, $magnifier_height ) = wp_get_attachment_image_src( $attachment_id, 'full' );

				echo wp_kses_post( apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<li class="%s" style="%s"><a href="%s" class="%s" title="%s" data-small="%s">%s</a></li>', $image_class, $li_style, $magnifier_url, $image_class, $image_title, $thumbnail_url, $image ), $attachment_id, $post->ID, $image_class, $columns ) );

				$loop++;
			}
			?>
		</ul>

		<?php if ( $enable_slider && ( count( $attachment_ids ) > $columns ) ) : ?>
			<div id="slider-prev" class="yith_slider_arrow">
				<span>
					<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:none;}</style></defs><title/><g data-name="Layer 2" id="Layer_2"><path d="M20,25a1,1,0,0,1-.71-.29l-8-8a1,1,0,0,1,0-1.42l8-8a1,1,0,1,1,1.42,1.42L13.41,16l7.3,7.29a1,1,0,0,1,0,1.42A1,1,0,0,1,20,25Z"/></g><g id="frame"><rect class="cls-1" height="32" width="32"/></g></svg>
				</span>
			</div>
			<div id="slider-next" class="yith_slider_arrow">
				<span>
					<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:none;}</style></defs><title/><g data-name="Layer 2" id="Layer_2"><path d="M12,25a1,1,0,0,1-.71-.29,1,1,0,0,1,0-1.42L18.59,16l-7.3-7.29a1,1,0,1,1,1.42-1.42l8,8a1,1,0,0,1,0,1.42l-8,8A1,1,0,0,1,12,25Z"/></g><g id="frame"><rect class="cls-1" height="32" width="32"/></g></svg>
				</span>
			</div>
		<?php endif; ?>
		<input id="yith_wc_zm_carousel_controler" type="hidden" value="1">
	</div>

	<?php
}
?>
