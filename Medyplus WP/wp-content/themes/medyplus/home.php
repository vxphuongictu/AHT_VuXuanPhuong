<?php
/*
* Template Name: home
*/
get_header();
?>
<div class="silde-bar container-fluid">
	<div class="main bg-grey">
		<?php 
		echo do_shortcode('[smartslider3 slider="2"]');
		?>
		<div class="container">
			<div class="main-text-slider">
				<div class="find-medicine">
					<div class="header-line-1">
						<?php echo get_theme_mod( 'first_text_title' ); ?> 
					</div>
					<div class="header-line-2">
						<span class="you-need">
							<?php echo get_theme_mod( 'second_text_title' ); ?> 
						</span>
					</div>
				</div>
				<div class="text-in-slider">
					<h6><?php echo get_theme_mod( 'content_text_title' ); ?></h6>
					<div class="link-shop-now">
						<a href="#" class="black"><?php echo get_theme_mod( 'link_text_title' ); ?></a>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
<div class="main-body container-fluid">
	<div class="container">
		<div class="container-body">
			<div class="first-menu">
				<div class="free-ship">
					<div class="free-ship-icon icon"></div>
					<div class="content">
						<a href="#" class="font-bold"><?php echo get_post_meta( $post->ID, 'free_shipping', true ); ?></a><br>
						<span class="located grey"><?php echo get_post_meta( $post->ID, 'free_shipping_content', true ); ?></span>
					</div>
				</div>

				<div class="support">
					<div class="support-247-icon icon"></div>
					<div class="content">
						<a href="#" class="font-bold"><?php echo get_post_meta( $post->ID, 'support', true ); ?></a><br>
						<span class="located grey"><?php echo get_post_meta( $post->ID, 'support_content', true ); ?></span>
					</div>
				</div>

				<div class="day-to-return">
					<div class="day-to-return-icon icon"></div>
					<div class="content">
						<a href="#" class="font-bold"><?php echo get_post_meta( $post->ID, 'day_to_return', true ); ?></a><br>
						<span class="located grey"><?php echo get_post_meta( $post->ID, 'day_to_return_content', true ); ?></span>
					</div>
				</div>

				<div class="secure-payments">
					<div class="secure-payment-icon icon"></div>
					<div class="content">
						<a href="#" class="font-bold"><?php echo get_post_meta( $post->ID, '_secure_payments', true ); ?></a><br>
						<span class="located grey"><?php echo get_post_meta( $post->ID, '_secure_payments_content', true ); ?></span>
					</div>
				</div>
			</div>
			<div class="second-menu">
				<div class="item">
					<div class="big-item">
						<?php
							$image 	= get_post_meta( $post->ID, 'big_item_img', true );
							$size 	= 'full'; // (thumbnail, medium, large, full or custom size)
							if( $image ) {
							    echo wp_get_attachment_image( $image, $size );
							}
						?>
						<!-- <img class="big-item-img" src="<?php //echo SITE_URL; ?>/assets/images/big-item-img.png"></img> -->
						<div class="content-big-item">
							<div class="box-groups">
								<div class="box">
									<div class="content">
										<div class="number blue font-bold" id="remaining-day"></div>
										<div class="text black font-bold">DAYS</div>
									</div>
								</div>
								<div class="box">
									<div class="content">
										<div class="number blue font-bold" id="remaining-hour"></div>
										<div class="text black font-bold">HRS</div>
									</div>
								</div>
								<div class="box">
									<div class="content">
										<div class="number blue font-bold" id="remaining-min"></div>
										<div class="text black font-bold">MINS</div>
									</div>
								</div>
								<div class="box">
									<div class="content">
										<div class="number blue font-bold" id="remaining-sec"></div>
										<div class="text black font-bold">SEC</div>
									</div>
								</div>
							</div>

							<div class="title">
								<span class="text black font-bold header"><?php echo get_post_meta( $post->ID, 'big_item_img_title', true ); ?></span><br>
							</div>

							<div class="description">
								<span class="description-text grey"><?php echo get_post_meta( $post->ID, 'big_item_img_content', true ); ?></span>
							</div>
						</div>
					</div>

					<div class="group-item-right">
						<div class="item-1">
							<div class="content">
								<div class="content-groups">
									<div class="number blue font-bold"><?php echo get_post_meta( $post->ID, 'second_percent_content_img', true ); ?></div>
									<div class="black discount"><?php echo get_post_meta( $post->ID, 'second_content_title', true ); ?></div>
									<div class="grey description"><?php echo get_post_meta( $post->ID, 'second_content', true ); ?></div>
								</div>
							</div>
							<div class="selling-item">
								<?php
									$image 	= get_post_meta( $post->ID, '_second_img', true );
									$size 	= 'full'; // (thumbnail, medium, large, full or custom size)
									if( $image ) {
									    echo wp_get_attachment_image( $image, $size );
									}
								?>
								<!-- <img src="<?php //echo SITE_URL; ?>/assets/images/item-1.png"></img> -->
							</div>
						</div>

						<div class="item-2">
							<div class="content">
								<div class="content-groups">
									<div class="text black font-bold"><?php echo get_post_meta( $post->ID, 'third_content_title', true ); ?></div>
									<div class="description">
										<span class="description-text grey"><?php echo get_post_meta( $post->ID, 'third_content', true ); ?></span>
									</div>
								</div>
							</div>
							<div class="selling-item">
								<?php
									$image 	= get_post_meta( $post->ID, 'third_img', true );
									$size 	= 'full'; // (thumbnail, medium, large, full or custom size)
									if( $image ) {
									    echo wp_get_attachment_image( $image, $size );
									}
								?>
								<!-- <img src="<?php // echo SITE_URL; ?>/assets/images/item-2.png"></img> -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="top-selling">
				<div class="title">
					<div class="text-bg">
						<span><?php echo get_post_meta( $post->ID, 'big_title_products', true ); ?></span>
						<h2 class="black"><?php echo get_post_meta( $post->ID, 'small_title_products', true ); ?></h2>
						<div class="slider">
							<i class="ti-angle-left"></i>
							<i class="ti-angle-right"></i>
						</div>
					</div>
				</div>
			</div>
			<?php get_template_part('products'); ?>
		</div>
	</div>
</div>
<?php get_template_part('latestnew'); ?>

<?php
get_footer();
?>