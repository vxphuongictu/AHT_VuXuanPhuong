<?php

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

if ( ! function_exists( 'shopical_product_search_form' ) ) {
	function shopical_product_search_form() {
		echo do_shortcode( '[wcas-search-form]' );
	}
}

add_action( 'wp_footer', 'dgwt_wcas_shopical_mobile_search', 100 );

function dgwt_wcas_shopical_mobile_search() {
	?>
	<script>
		(function ($) {
			$(window).on('load', function () {
				$('.open-search-form').off('click');
				$(document).on('click', '.open-search-form', function (e) {
					var $overlay = $(this).prev();
					var $form = $overlay.find('.js-dgwt-wcas-enable-mobile-form');
					if ($form.eq(0)) {
						$form.click();
					}
					e.preventDefault();
				});
			});
		}(jQuery));
	</script>
	<style>
		.dgwt-wcas-overlay-mobile .dgwt-wcas-search-form {
			padding: 0;
		}
	</style>
	<?php
}
