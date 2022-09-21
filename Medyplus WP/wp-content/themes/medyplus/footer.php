<?php
/**
 * Template Name: Footer
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Medyplus
 */
wp_footer();
$args 	= array(
    'post_type' 		=> 'page',
    's' 				=> 'footer'
);

$size 					= 'full';
$the_query 				= new WP_Query( $args );

while ($the_query->have_posts()):
	$the_query->the_post();
?>
<div class="sign-up container-fluid">
				<div class="container">
					<div class="main-group">
						<div class="keep-update">
							<span class="text"><?php echo get_field( 'keep_updated_title' ); ?></span>
						</div>

						<div class="sub-content">
							<span class="text"><?php echo get_field( 'keep_updated_content' ); ?></span>
						</div>

						<div class="search-group">
							<div class="search">
								<input type="text" placeholder="<?php echo get_field( 'placeholder_input' ); ?>">
								<button class="bg-blue"><?php echo get_field( 'btn_search' ); ?></button>
							</div>
						</div>
					</div>	
				</div>
			</div>

			<div class="certificate container-fluid">
				<div class="container">
					<a href="#" class="item-1">
					<?php
					$image 	 	= get_post_meta($post->ID, 'footer_img_1', true);
					if ($image){
						echo wp_get_attachment_image($image, $size);
					}
					?>
					</a>

					<a href="#" class="item-2">
					<?php
					$image 	 	= get_post_meta($post->ID, 'footer_img_2', true);
					if ($image){
						echo wp_get_attachment_image($image, $size);
					}
					?>
					</a>

					<a href="#" class="item-3">
					<?php
					$image 	 	= get_post_meta($post->ID, 'footer_img_3', true);
					if ($image){
						echo wp_get_attachment_image($image, $size);
					}
					?>
					</a>

					<a href="#" class="item-4">
					<?php
					$image 	 	= get_post_meta($post->ID, 'footer_img_4', true);
					if ($image){
						echo wp_get_attachment_image($image, $size);
					}
					?>
					</a>

					<a href="#" class="item-5">
					<?php
					$image 	 	= get_post_meta($post->ID, 'footer_img_5', true);
					if ($image){
						echo wp_get_attachment_image($image, $size);
					}
					?>
					</a>
				</div>
			</div>

			<div class="certificate-mobile container-fluid">
				<div class="container">
					<div class="certificate-mobile-groups">
						<a href="#" class="item-1">
						<?php
						$image 	 	= get_post_meta($post->ID, 'footer_img_1', true);
						if ($image){
							echo wp_get_attachment_image($image, $size);
						}
						?>
						</a>

						<a href="#" class="item-2">
						<?php
						$image 	 	= get_post_meta($post->ID, 'footer_img_2', true);
						if ($image){
							echo wp_get_attachment_image($image, $size);
						}
						?>
						</a>
					</div>
					<div class="certificate-mobile-groups">
						<a href="#" class="item-3">
						<?php
						$image 	 	= get_post_meta($post->ID, 'footer_img_3', true);
						if ($image){
							echo wp_get_attachment_image($image, $size);
						}
						?>
						</a>

						<a href="#" class="item-4">
						<?php
						$image 	 	= get_post_meta($post->ID, 'footer_img_4', true);
						if ($image){
							echo wp_get_attachment_image($image, $size);
						}
						?>
						</a>

						<a href="#" class="item-5">
						<?php
						$image 	 	= get_post_meta($post->ID, 'footer_img_5', true);
						if ($image){
							echo wp_get_attachment_image($image, $size);
						}
						?>
						</a>
					</div>
				</div>
			</div>

		<div class="more-info container-fluid">
			<div class="container">
				<div class="container-body">
					<div class="contact">
						<div class="content">
							<div class="title">
								<span><?php echo get_field('contact_title'); ?></span>
							</div>

							<div class="body">
								<div class="location">
									<div class="location-icon">
										<i class="fa fa-map-marker" aria-hidden="true"></i>
									</div>
									<a href="<?php echo(get_field('contact_item_1')['url']); ?>" class="location-text">
										<span><?php echo(get_field('contact_item_1')['title']); ?></span>
									</a>
								</div>

								<div class="mail-contact">
									<div class="mail-icon">
										<i class="fa fa-envelope-o" aria-hidden="true"></i>
									</div>
									<a href="mailto: <?php echo(get_field('contact_item_2')['url']); ?>" class="mail-text">
										<span><?php echo(get_field('contact_item_2')['title']); ?></span>
									</a>
								</div>

								<div class="phone-contact">
									<div class="phone-icon">
										<i class="fa fa-mobile" aria-hidden="true"></i>
									</div>
									<a href="tel:<?php echo(get_field('contact_item_3')['url']); ?>" class="phone-number">
										<span><?php echo(get_field('contact_item_3')['title']); ?></span>
									</a>
								</div>

								<div class="social">
									<a href="<?php cot_facebook(); ?>"><i class="fa-brands fa-facebook-f"></i></a>
									<a href="<?php cot_twitter(); ?>"><i class="fa-brands fa-twitter"></i></a>
									<a href="<?php cot_googleplus(); ?>"><i class="fa-brands fa-google-plus-g"></i></a>
									<a href="<?php cot_instagram(); ?>"><i class="fa-brands fa-instagram"></i></a>
								</div>
							</div>
						</div>
					</div>

					<div class="company">
						<div class="content">
							<div class="title">
								<span><?php echo get_field('company_title'); ?></span>
							</div>

							<div class="body">
								<div class="about-us">
									<div class="about-text">
										<a href="<?php echo(get_field('company_item_1')['url']); ?>"><?php echo(get_field('company_item_1')['title']); ?></a>
									</div>
								</div>

								<div class="team-member">
									<div class="team-member-text">
										<a href="<?php echo(get_field('company_item_2')['url']); ?>"><?php echo(get_field('company_item_2')['title']); ?></a>
									</div>
								</div>

								<div class="carrer">
									<div class="carrer-text">
										<a href="<?php echo(get_field('company_item_3')['url']); ?>"><?php echo(get_field('company_item_3')['title']); ?></a>
									</div>
								</div>

								<div class="contact">
									<div class="contact-text">
										<a href="<?php echo(get_field('company_item_4')['url']); ?>"><?php echo(get_field('company_item_4')['title']); ?></a>
									</div>
								</div>

								<div class="affilate">
									<div class="affilate-text">
										<a href="<?php echo(get_field('company_item_5')['url']); ?>"><?php echo(get_field('company_item_5')['title']); ?></a>
									</div>
								</div>

							</div>
						</div>
					</div>

					<div class="categories">
						<div class="content">
							<div class="title">
								<span><?php echo get_field('categories_title'); ?></span>
							</div>

							<div class="body">
								<div class="home-medicine">
									<div class="home-medicine-text">
										<a href="<?php echo(get_field('categories_item_1')['url']); ?>"><?php echo(get_field('categories_item_1')['title']); ?></a>
									</div>
								</div>

								<div class="baby-child">
									<div class="baby-child-text">
										<a href="<?php echo(get_field('categories_item_2')['url']); ?>"><?php echo(get_field('categories_item_2')['title']); ?></a>
									</div>
								</div>

								<div class="diet">
									<div class="diet-text">
										<a href="<?php echo(get_field('categories_item_3')['url']); ?>"><?php echo(get_field('categories_item_3')['title']); ?></a>
									</div>
								</div>

								<div class="beauty">
									<div class="beauty-text">
										<a href="<?php echo(get_field('categories_item_4')['url']); ?>"><?php echo(get_field('categories_item_4')['title']); ?></a>
									</div>
								</div>

								<div class="fitness">
									<div class="fitness-text">
										<a href="<?php echo(get_field('categories_item_5')['url']); ?>"><?php echo(get_field('categories_item_5')['title']); ?></a>
									</div>
								</div>

							</div>
						</div>
					</div>

					<div class="quick-links">
						<div class="content">
							<div class="title">
								<span><?php echo get_field('quick_links_title'); ?></span>
							</div>

							<div class="body">
								<div class="my-account">
									<div class="my-account-text">
										<a href="<?php echo(get_field('quick_link_item_1')['url']); ?>"><?php echo(get_field('quick_link_item_1')['title']); ?></a>
									</div>
								</div>

								<div class="store-location">
									<div class="store-location-text">
										<a href="<?php echo(get_field('quick_link_item_2')['url']); ?>"><?php echo(get_field('quick_link_item_2')['title']); ?></a>
									</div>
								</div>

								<div class="orders">
									<div class="orders-text">
										<a href="<?php echo(get_field('quick_link_item_3')['url']); ?>"><?php echo(get_field('quick_link_item_3')['title']); ?></a>
									</div>
								</div>

								<div class="faqs">
									<div class="faqs-text">
										<a href="<?php echo(get_field('quick_link_item_4')['url']); ?>"><?php echo(get_field('quick_link_item_4')['title']); ?></a>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="footer container-fluid">
			<div class="container">
				<?php do_action('footer_copy_right'); ?>
				<div class="payment">
					<a href="#" class="cirrus-icon">
					<?php
					$image 	 	= get_post_meta($post->ID, 'cirrus_icon', true);
					$size 		= 'full';
					if ($image){
						echo wp_get_attachment_image($image, $size);
					}
					?>
					</a>
					<a href="#" class="mastercard-icon">
					<?php
					$image 	 	= get_post_meta($post->ID, 'mastercard_icon', true);
					$size 		= 'full';
					if ($image){
						echo wp_get_attachment_image($image, $size);
					}
					?>
					</a>
					<a href="#" class="visa-icon">
					<?php
					$image 	 	= get_post_meta($post->ID, 'visa_icon', true);
					$size 		= 'full';
					if ($image){
						echo wp_get_attachment_image($image, $size);
					}
					?>
					</a>
					<a href="#" class="skrill-icon">
					<?php
					$image 	 	= get_post_meta($post->ID, 'skrill_icon', true);
					$size 		= 'full';
					if ($image){
						echo wp_get_attachment_image($image, $size);
					}
					?>
					</a>
					<a href="#" class="paypal-icon">
					<?php
					$image 	 	= get_post_meta($post->ID, 'paypal_icon', true);
					$size 		= 'full';
					if ($image){
						echo wp_get_attachment_image($image, $size);
					}
					?>
					</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<?php
endwhile;
wp_reset_postdata();
wp_footer();
?>