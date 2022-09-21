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

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
$product_id 	= $product->id;
?>

<div class="header-product">
	<div class="header-title">
		<?php echo do_action('woocommerce_template_single_title_custom'); ?>

		<div class="btn-favorite">
			 <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
		</div>
	</div>

	<div class="header-body">
		<div class="product-image">
			<?php $image 	= wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'full'); ?>
			<img src="<?php echo $image[0]; ?>" alt="product-image" id="product-image" draggable="false">

			<div class="btn-slide">
				<?php
				$count 				= 0;
				$attachment_ids 	= $product->get_gallery_image_ids();
				$attachment_ids[] 	= get_post_thumbnail_id($product_id);
				if (count($attachment_ids) > 0):
					foreach ($attachment_ids as $attachment_id):
						$count ++;
				?>

				<div class="dot" onclick="changeImg('<?php echo wp_get_attachment_url( $attachment_id ); ?>')">
					<input type="hidden" value="<?php echo wp_get_attachment_url( $attachment_id ); ?>" class="url-image">
				</div>

				<?php 
				$max_btn 	= empty(get_theme_mod('maximum-show-gallery')) === false ? get_theme_mod('maximum-show-gallery') : 3;
				if($count >= $max_btn) break; ?>
				<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>

		<div class="product-information-group">
			<div class="product-information">
				<h3><?php echo get_theme_mod('specification', $product_id); ?></h3>

				<div class="more-info">

					<div class="more-info-line-1">
						<?php if(get_field('kilometer_content')): ?>
		                <div class="kilometer">
		                    <div class="icon">
		                        <?php $image    = get_field('kilometer_icon', $product_id); ?>
		                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
		                    </div>
		                    <span class="content"><?php echo get_field('kilometer_content'); ?> km</span>
		                </div>
		            	<?php endif; ?>

		            	<?php if(get_field('fuel_content')): ?>
		                <div class="fuel">
		                    <div class="icon">
		                        <?php $image    = get_field('fuel_icon'); ?>
		                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
		                    </div>
		                    <span class="content"><?php echo get_field('fuel_content'); ?></span>
		                </div>
		            	<?php endif; ?>
		            </div>

		            <div class="more-info-line-2">
	            	<?php if(get_field('clock_content')): ?>
		                <div class="time">
		                    <div class="icon">
		                        <?php $image    = get_field('clock_icon'); ?>
		                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
		                    </div>
		                    <span class="content"><?php echo get_field('clock_content'); ?></span>
		                </div>
		                <?php endif; ?>

		            	<?php if(get_field('unknow_content')): ?>
		                <div class="unknow">
		                    <div class="icon">
		                        <?php $image    = get_field('unknow_icon'); ?>
		                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
		                    </div>
		                    <span class="content"><?php echo get_field('unknow_content'); ?></span>
		                </div>
		            	<?php endif; ?>
	            	</div>

	            	<div class="more-info-line-3">
		                <?php if(get_field('cheaper_content')): ?>
		                <div class="cheaper">
		                    <div class="icon">
		                        <?php $image    = get_field('cheaper_icon'); ?>
		                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
		                    </div>
		                    <span class="content"><?php echo get_field('cheaper_content'); ?> VND Cheaper</span>
		                </div>
		            	<?php endif; ?>
	            	</div>
	            </div>

	            <div class="line"></div>

	            <div class="auction-price">
	            	<h2 class="title"><?php echo get_field('auction_price_title'); ?></h2>
	            	<div class="content"><?php echo get_field('auction_price_content'); ?> VND</div>
	            </div>

	            <div class="time-remaining">
	            	<h2 class="title"><?php echo get_field('time_remaining_content'); ?></h2>
	            	<div class="content">
	            		<span id="days-<?php echo $product_id; ?>"></span>
                        <span id="hours-<?php echo $product_id; ?>"></span>
                        <span id="minutes-<?php echo $product_id; ?>"></span>
	            	</div>
	            </div>
			</div>
		</div>
	</div>

	<div class="product-detail">
		<h2><?php echo get_theme_mod( 'product_detail' ); ?></h2>
		<div class="detail-content">
			<?php echo $product->get_description(); ?>
		</div>
	</div>

	<div class="product-vehicle">
		<h2><?php echo get_theme_mod( 'vehicle_summary' ); ?></h2>
		<div class="vehicle-content">
			<div class="condition">
				<div class="title"><?php echo get_field('condition_title'); ?></div>
				<div class="content"><?php echo get_field('condition_content'); ?></div>
			</div>

			<div class="colour">
				<div class="title"><?php echo get_field('original_colour_title'); ?></div>
				<div class="content"><?php echo get_field('original_colour_content'); ?></div>
			</div>

			<div class="reg-date">
				<div class="title"><?php echo get_field('reg_date_title'); ?></div>
				<div class="content"><?php echo get_field('reg_date_content'); ?></div>
			</div>

			<div class="body-type">
				<div class="title"><?php echo get_field('body_type_title'); ?></div>
				<div class="content"><?php echo get_field('body_type_content'); ?></div>
			</div>

			<div class="manufacturing-year">
				<div class="title"><?php echo get_field('manufacturing_year_title'); ?></div>
				<div class="content"><?php echo get_field('manufacturing_year_content'); ?></div>
			</div>

			<div class="power">
				<div class="title"><?php echo get_field('power_title'); ?></div>
				<div class="content"><?php echo get_field('power_content'); ?></div>
			</div>

			<div class="seats">
				<div class="title"><?php echo get_field('seats_title'); ?></div>
				<div class="content"><?php echo get_field('seats_content'); ?></div>
			</div>

			<div class="colour">
				<div class="title"><?php echo get_field('condition_title'); ?></div>
				<div class="content"><?php echo get_field('condition_content'); ?></div>
			</div>
		</div>
	</div>

	<div class="line"></div>

	<div class="footer-product">
		<h2><?php echo get_theme_mod('like_this_vehicle'); ?></h2>

		<div class="result-quick-quote" id="result-quick-quote-<?php echo $product_id; ?>"></div>

		<div class="content">
			<div class="search-group">
				<input type="text" placeholder="<?php echo get_field('placeholder_quick_quote'); ?>" id="quick-quote-message-<?php echo $product_id; ?>">
				<button type="submit" class="btn-submit-quick-quote" id="btn-submit-quick-quote-<?php echo $product_id; ?>" onclick="user_send_mail(this);" value="<?php echo $product_id; ?>" name=""><?php echo get_field('submit_content'); ?></button>
			</div>
		</div>
	</div>
</div>
<script> makeTimer('<?php echo $product_id; ?>','<?php echo get_field('ending_date_content'); ?>'); </script>
<?php do_action( 'woocommerce_after_single_product' ); ?>