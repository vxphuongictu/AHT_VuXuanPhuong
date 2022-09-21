<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
	<div class="content-product">
		<div class="img-product">
			<?php
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'full' );
			echo '<a href="'.$product->get_permalink($product->get_id()).'"><img class="selling-item" src="'.$image[0].'"></img></a>';
			?>
		</div>
		<div class="info-product">
			<div class="container-bottom-product">
				<div class="info-group">
					<div class="name-item">
						<a href="<?php echo $product->get_permalink($product->get_id());?>">
							<?php echo $product->get_name(); ?>
						</a>
					</div>
					<div class="product-price">
						<a href="<?php echo $product->get_permalink($product->get_id());?>">
							<?php
							if ($product->is_on_sale()):
								echo '<span class="price grey line-through">'.money_format("$%(n", $product->get_sale_price()).'</span>';
							endif;
							?>
							<span class="price total-product-price blue"><?php  echo money_format("$%(n", $product->get_regular_price()); ?></span>
						</a>
					</div>
				</div>

				<div class="add-to-cart">
					<button class="up-sells-add-to-cart" value="<?php echo $product->get_id(); ?>">
						<span>Add to cart</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</li>
