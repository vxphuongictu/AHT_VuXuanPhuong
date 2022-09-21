<?php

use DgoraWcas\Helpers;

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

if ( ! function_exists( 'storefront_product_search' ) ) {
	function storefront_product_search() {
		if ( storefront_is_woocommerce_activated() ) { ?>
			<div class="site-search">
				<?php echo do_shortcode( '[fibosearch]' ); ?>
			</div>
			<?php
		}
	}
}

add_action( 'wp_footer', function () {
	if ( Helpers::isAMPEndpoint() ) {
		?>
		<style>
			#page.search-mobile-active .storefront-handheld-footer-bar ul li.search .site-search {
				bottom: 100%;
			}
		</style>
		<?php

		return;
	}
	?>
	<style>
		.dgwt-wcas-open .storefront-handheld-footer-bar,
		.dgwt-wcas-focused .storefront-handheld-footer-bar {
			display: none;
		}
	</style>
	<script>
		(function ($) {
			$(window).on('load', function () {
				$(document).on('click', '.storefront-handheld-footer-bar .search > a', function (e) {
					var $wrapper = $(this).parent();
					$wrapper.removeClass('active');

					setTimeout(function () {
						$wrapper.find('.js-dgwt-wcas-enable-mobile-form')[0].click();
					}, 200);
					e.preventDefault();
				});
			});
		}(jQuery));
	</script>
	<?php
}, 100 );

/**
 * Toole mobile search when AMP is active
 */
if ( ! function_exists( 'storefront_handheld_footer_bar_search' ) ) {
	function storefront_handheld_footer_bar_search() {
		if ( Helpers::isAMPEndpoint() ) {
			echo '<a on="tap:page.toggleClass(class=\'search-mobile-active\')" href="javascript:void(0);">' . esc_attr__( 'Search', 'storefront' ) . '</a>';
		} else {
			echo '<a href="">' . esc_attr__( 'Search', 'storefront' ) . '</a>';
		}
		storefront_product_search();
	}
}
