<?php
/**
 * The Template for displaying product variation info in Details Panel
 *
 * This template can be overridden by copying it to yourtheme/fibosearch/details-panel/product-variation.php.
 */

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

?>
<div class="dgwt-wcas-details-inner dgwt-wcas-details-inner-product">

	<?php do_action( 'dgwt/wcas/details_panel/product_variation/container_before', $vars ); ?>

	<div class="dgwt-wcas-product-details">

		<?php do_action( 'dgwt/wcas/details_panel/product_variation/image_before', $vars ); ?>
		<a href="<?php echo esc_url( $vars->link ); ?>" title="<?php echo wp_strip_all_tags($vars->name); ?>">
			<div class="dgwt-wcas-details-main-image">
				<img
					src="<?php echo esc_url( $vars->imageSrc ); ?>"
					<?php echo ( ! empty( $vars->imageSrcset ) && ! empty( $vars->imageSizes ) ) ? 'srcset="' . esc_attr( $vars->imageSrcset ) . '"' : '' ?>
					<?php echo ( ! empty( $vars->imageSrcset ) && ! empty( $vars->imageSizes ) ) ? 'sizes="' . esc_attr( $vars->imageSizes ) . '"' : '' ?>
					alt="<?php echo wp_strip_all_tags( $vars->name ); ?>"
				>
			</div>
		</a>
		<?php do_action( 'dgwt/wcas/details_panel/product_variation/image_after', $vars ); ?>

		<div class="dgwt-wcas-details-space">
			<a class="dgwt-wcas-details-product-title" href="<?php echo esc_url( $vars->link ); ?>" title="<?php echo wp_strip_all_tags($vars->name); ?>">
				<?php echo $vars->name; ?>
			</a>
			<?php if ( ! empty( $vars->sku ) ): ?>
				<span class="dgwt-wcas-details-product-sku"><?php echo $vars->sku; ?></span>
			<?php endif; ?>

			<?php if ( $vars->reviewCount > 0 ): ?>

				<div class="dgwt-wcas-pd-rating">
					<?php echo $vars->ratingHtml . ' <span class="dgwt-wcas-pd-review">(' . $vars->reviewCount . ')</span>'; ?>
				</div>

			<?php endif; ?>

			<?php do_action( 'dgwt/wcas/details_panel/product_variation/price_before', $vars ); ?>
			<div class="dgwt-wcas-pd-price">
				<?php echo $vars->priceHtml; ?>
			</div>
			<?php do_action( 'dgwt/wcas/details_panel/product_variation/price_after', $vars ); ?>

			<?php if ( ! empty( $vars->attributes ) ): ?>
				<div class="dgwt-wcas-details-hr"></div>
				<div class="dgwt-wcas-details-attributes">
					<?php foreach ( $vars->attributes as $attribute ): ?>
						<div class="dgwt-wcas-details-attribute"><b><?php echo esc_html( $attribute['label'] ); ?></b>: <span><?php echo esc_html( $attribute['value'] ); ?></span></div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="dgwt-wcas-details-hr"></div>

			<?php if ( ! empty( $vars->stockAvailability ) ) {
				echo $vars->stockAvailability;
			}; ?>

			<?php do_action( 'dgwt/wcas/details_panel/product_variation/add_to_cart_before', $vars ); ?>
			<div class="dgwt-wcas-pd-addtc js-dgwt-wcas-pd-addtc">
				<form class="dgwt-wcas-pd-addtc-form" action="" method="post" enctype="multipart/form-data">
					<?php

					if ( $vars->showQuantity ) {
						woocommerce_quantity_input( array(
							'input_name' => 'js-dgwt-wcas-quantity',
						), $vars->wooObject, true );
					}

					echo WC_Shortcodes::product_add_to_cart( array(
						'id'         => $vars->ID,
						'show_price' => false,
						'style'      => '',
					) );
					?>
				</form>
			</div>
			<?php do_action( 'dgwt/wcas/details_panel/product_variation/add_to_cart_after', $vars ); ?>

		</div>

	</div>

	<?php do_action( 'dgwt/wcas/details_panel/product_variation/container_after', $vars ); ?>

</div>
