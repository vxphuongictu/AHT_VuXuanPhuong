<div class="item-selling">
	<div class="slider-item-selling">
		<div class="item-list">
			<?php
			$countItem 	= 0;
			$products 	= wc_get_products(
				[
					'status' 	=> 'publish',
					'limit' 	=> 8
				]
			);
			setlocale(LC_MONETARY, "en_US");
			foreach ($products as $product):
				if ($product->get_status() == "publish")
				$countItem+=1;
			?>
			<div class="item-<?php echo $countItem; ?>">
				<a href="<?php echo $product->get_permalink($product->get_id());?>">
					<div class="image-box">
						<?php
						$image 	= $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'full' );
						echo '<img class="selling-item" src="'.$image[0].'"></img>';
						?>
					</div>

					<div class="item-name">
						<span class="name"><?php echo $product->get_name(); ?></span>
					</div>

					<div class="item-price">
						<?php
						if ($product->is_on_sale()):
							echo '<span class="price grey line-through">'.money_format("$%(n", $product->get_sale_price()).'</span>';
						endif;
						?>
						<span class="price blue"><?php  echo money_format("$%(n", $product->get_regular_price()); ?></span>
					</div>
				</a>
			</div>
			<?php
			endforeach;
			?>
		</div>
	</div>
</div>