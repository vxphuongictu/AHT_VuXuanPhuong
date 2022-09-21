<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Medyplus
 */

global $product;
$id 	= $product->get_id();
?>

<div class="content-product container">
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
				<?php echo $product->get_description(); ?>
			</div>

			<div class="add-to-cart">
				<div class="form-add-to-cart">
					<form action="#" method="POST">
						<input type="number" name="quantity" id="quantity" placeholder="1" value="1">
						<button class="btn-add-to-cart">
							<span>Add To Cart</span>
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>