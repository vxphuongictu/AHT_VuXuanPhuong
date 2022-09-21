<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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
global $product;
$id 	= $product->get_id();
?>

<div class="content-product">
	<div class="header">
		<div class="header-left"></div>
		<div class="header-right">
			<h2><?php echo $product->get_title(); ?></h2>
		</div>
	</div>
	<div class="rewview-prodcut">
		<div class="image-product">
			<?php
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'full' );
			echo '<img class="selling-item" src="'.$image[0].'"></img>';
			?>
		</div>

		<div class="description-product">
			<div class="description">
				<?php if ($product->post->post_excerpt) { echo $product->post->post_excerpt; } else { echo "The author didn't write description for this product"; } ?>
			</div>

			<div class="product-status">
				<div class="status-content">
					<label for="status">Status: </label>
					<span>
						<?php
						$stock 	= $product->get_stock_status();
						$stock 	= str_replace ( ['instock', 'outofstock'], ['Available', 'Unavailable'], $stock );
						echo $stock;
						?>
					</span>
				</div>

				<div class="product-price">
					<div class="container-price">
						<label for="cost">Price: </label>
						<input type="hidden" id="price" value="<?php echo $product->get_regular_price(); ?>">
						<?php
						if ($product->is_on_sale()):
							echo '<span class="price grey line-through">'.money_format("$%(n", $product->get_sale_price()).'</span>';
						endif;
						?>
						<span class="price total-single-product-price blue"><?php  echo money_format("$%(n", $product->get_regular_price()); ?></span>
					</div>
				</div>
			</div>

			<div class="single-meta">
				<?php do_action('woocommerce_template_single_meta_custom'); ?>
			</div>

			<div class="add-to-cart">
				<div class="form-add-to-cart">
					<?php do_action( 'woocommerce_single_product_add_to_cart_custom' ); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="description-table">
		<hr>
		<h2>Description</h2>
		<div class="description-content">
			<?php echo $product->get_description(); ?>
		</div>
	</div>

	<?php do_action('woocommerce_upsell_display_upsell'); ?>

	<div class="other-products">
		<?php do_action('woocommerce_output_related_products_custom'); ?>
	</div>
</div>
<script type="text/javascript">
	catulate_price_product();
</script>