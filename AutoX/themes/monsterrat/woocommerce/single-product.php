<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();
?>
<article class="container-fluid">
	<div class="container single-product-content">
		<section class="column-left">
			<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

			<?php endwhile; // end of the loop. ?>
		</section>
		<section class="column-right">
			<div class="group-1">
				<div class="column-right-title">
					<h1><?php echo get_theme_mod('like_this_vehicle'); ?></h1>
				</div>
				<div class="column-right-content">
					<div class="like-vehicle">
						<div class="result-quick-quote" id="like-vehicle-<?php echo the_ID(); ?>"></div>
						<div class="form-quote">
							<input type="text" id="quick-quote-message-col-right-<?php echo the_ID(); ?>" placeholder="<?php echo get_theme_mod('like_this_vehicle_column_right'); ?>">
						</div>
						<button type="submit" class="btn-submit-quick-quote" value="<?php echo the_ID(); ?>" onclick="user_send_mail_col_right(this)" name=""><?php echo get_field('submit_content'); ?></button>
					</div>
				</div>
			</div>

			<div class="line"></div>

			<div class="group-2">
				<div class="column-right-title">
					<h1><?php echo get_theme_mod('need_help_column_right'); ?></h1>
				</div>

				<div class="column-right-content">
					<a class="location" href="#">
						<i class="fa fa-map-marker" aria-hidden="true"></i>
						<div class="location-text">
							<?php echo get_theme_mod('location_column_right'); ?>
						</div>
					</a>

					<a class="email" href="#">
						<i class="fa fa-envelope" aria-hidden="true"></i>
						<div class="email-text">
							<?php echo get_theme_mod('email_column_right'); ?>
						</div>
					</a>

					<a class="phone" href="#">
						<i class="fa fa-phone" aria-hidden="true"></i>
						<div class="phone-text">
							<?php echo get_theme_mod('phone_column_right'); ?>
						</div>
					</a>
				</div>
			</div>


		</section>
	</div>
</article>
<?php do_action('single_product_scripts'); ?>
<?php
get_footer();
?>