<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

// Change mobile breakpoint
add_filter( 'dgwt/wcas/scripts/mobile_breakpoint', function () {
	return 782;
} );

add_action( 'wp_footer', function () {
	echo '<div id="wcas-theme-search" style="display: block;">' . do_shortcode( '[wcas-search-form]' ) . '</div>';
} );

add_action( 'wp_footer', function () {
	?>
	<script>
		var wcasThemeSearch = document.querySelector('#bigcart-search-box .woocommerce-product-search');
		if (wcasThemeSearch !== null) {
			wcasThemeSearch.replaceWith(document.querySelector('#wcas-theme-search > div'));
		}
		document.querySelector('#wcas-theme-search').remove();

		(function ($) {
			$(window).on('load', function () {
				$('a.product-search').on('click', function (e) {
					$('#bigcart-search-box').addClass('dgwt-wcas');
					setTimeout(function () {
						var $input = $('#bigcart-search-box .dgwt-wcas-search-input');
						if ($input.length > 0) {
							$input.focus();
						}
					}, 500);

					if ($(window).width() <= 782) {
						var $handler = $('#bigcart-search-box .js-dgwt-wcas-enable-mobile-form');
						if ($handler.length) {
							$handler[0].click();
						}
					}
				});
			});
		}(jQuery));
	</script>
	<style>
		.search-box .dgwt-wcas-search-wrapp {
			position: absolute;
			width: 50%;
			top: 30%;
			left: 50%;
			opacity: 1;
			transform: translate(-50%, -50%);
		}

		.search-box form {
			position: relative;
			padding: 0;
			width: auto;
			top: 0;
			left: 0;
			opacity: 1 !important;
			transform: none !important;
		}
	</style>
	<?php
} );
