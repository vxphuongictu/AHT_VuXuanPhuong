<?php
/**
 * The Template for displaying single product on the list of products from a given category, etc. in the Details Panel
 *
 * This template can be overridden by copying it to yourtheme/fibosearch/details-panel/term-product.php.
 */

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

do_action( 'dgwt/wcas/details_panel/term_products/product/container_before', $vars ); ?>

<a class="dgwt-wcas-tax-product-details" href="<?php echo esc_url( $vars->link ); ?>" title="<?php echo esc_attr( $vars->name ); ?>">

	<?php do_action( 'dgwt/wcas/details_panel/term_products/product/image_before', $vars ); ?>
	<div class="dgwt-wcas-tpd-image">
		<img
			src="<?php echo esc_url( $vars->imageSrc ); ?>"
			<?php echo ( ! empty( $vars->imageSrcset ) && ! empty( $vars->imageSizes ) ) ? 'srcset="' . esc_attr( $vars->imageSrcset ) . '"' : '' ?>
			<?php echo ( ! empty( $vars->imageSrcset ) && ! empty( $vars->imageSizes ) ) ? 'sizes="' . esc_attr( $vars->imageSizes ) . '"' : '' ?>
			alt="<?php echo wp_strip_all_tags( $vars->name ); ?>"
		>
	</div>
	<?php do_action( 'dgwt/wcas/details_panel/term_products/product/image_after', $vars ); ?>

	<div class="dgwt-wcas-tpd-rest">

		<span class="dgwt-wcas-tpd-rest-title"><?php echo esc_attr( $vars->name ); ?></span>

		<?php if ( $vars->reviewCount > 0 ): ?>
			<div class="dgwt-wcas-pd-rating">
				<?php echo $vars->ratingHtml . ' <span class="dgwt-wcas-pd-review">(' . $vars->reviewCount . ')</span>'; ?>
			</div>
		<?php endif; ?>

		<?php do_action( 'dgwt/wcas/details_panel/term_products/product/price_before', $vars ); ?>
		<div class="dgwt-wcas-tpd-price">
			<?php echo $vars->priceHtml; ?>
		</div>
		<?php do_action( 'dgwt/wcas/details_panel/term_products/product/price_before', $vars ); ?>

	</div>
</a>

<?php do_action( 'dgwt/wcas/details_panel/term_products/product/container_after', $vars );
