<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

// Default search form
add_filter( 'get_search_form', function ( $form, $args ) {
	return do_shortcode( '[wcas-search-form layout="classic"]' );
}, 10, 2 );

// WooCommerce search form
add_filter( 'get_product_search_form', function ( $form ) {
	return do_shortcode( '[wcas-search-form layout="classic"]' );
} );

add_action( 'wp_footer', function () {
	?>
	<script>
		(function ($) {
			$(window).on('load', function () {
				// Search icon - mobile
				if ($(window).width() <= 768) {
					$('.search-wrapper .search-icon').off('click').on('click', function (e) {
						var $handler = $('.search-wrapper .header-search-box .js-dgwt-wcas-enable-mobile-form');
						if ($handler.length) {
							$handler[0].click();
						}
					});
				}
			});
		}(jQuery));
	</script>
	<style>
		.admin-bar .dgwt-wcas-suggestions-wrapp {
			margin-top: -32px !important;
		}

		.admin-bar .dgwt-wcas-details-wrapp {
			margin-top: -32px !important;
		}
	</style>
	<?php
}, 100 );
