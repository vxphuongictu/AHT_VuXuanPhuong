<?php

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_action( 'wp_head', function () {
	?>
	<style>
		.dgwt-wcas-search-wrapp {
			max-width: none;
		}
		.ekommart-handheld-footer-bar ul li.search.active .site-search{
			bottom: -100%;
		}
	</style>
	<?php
} );

if ( ! function_exists( 'ekommart_product_search' ) ) {
	function ekommart_product_search() {
		?>
		<div class="site-search">
			<?php echo do_shortcode( '[wcas-search-form]' ); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'ekommart_handheld_footer_bar_search' ) ) {
	function ekommart_handheld_footer_bar_search() {
		?>
		<a href=""><span class="title"><?php echo esc_attr__( 'Search', 'ekommart' ); ?></span></a>
		<div class="site-search">
			<?php echo do_shortcode( '[wcas-search-form]' ); ?>
		</div>
		<?php
	}
}

add_action( 'wp_footer', 'dgwt_wcas_ekommart_mobile_search', 100 );

function dgwt_wcas_ekommart_mobile_search() {
	?>
	<script>
		(function ($) {
			$(window).on('load', function () {
				$(document).on('click', '.ekommart-handheld-footer-bar .search > a', function (e) {
					var $siteSearch = $(this).next();
					var $form = $siteSearch.find('.js-dgwt-wcas-enable-mobile-form');
					if ($form.eq(0)) {
						$form.click();
					}
					e.preventDefault();
				});
			});
		}(jQuery));
	</script>
	<?php
}
