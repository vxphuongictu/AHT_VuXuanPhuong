<?php

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

// Remove native form
add_action( 'init', function () {
	remove_action( 'wp_loaded', 'shopkeeper_predictive_search', 100 );
} );

// Embed search bar
add_action( 'getbowtied_product_search', function () {
	echo do_shortcode( '[wcas-search-form layout="classic" mobile_overlay="1" mobile_breakpoint="767"]' );
} );

// Change mobile breakpoint from 992 to 767
add_filter( 'dgwt/wcas/scripts/mobile_breakpoint', function () {
	return 767;
} );

add_action( 'wp_head', function () {
	?>
	<style>
		.site-search.off-canvas {
			min-height: 100px;
		}

		.admin-bar .site-search.off-canvas {
			min-height: 130px;
		}

		.site-search.off-canvas > .row {
			margin-top: 30px;
		}

		.site-search.off-canvas p.search-text {
			position: absolute;
			top: 14px;
			left: 20px;
		}

		.site-search-close {
			position: absolute;
			top: 5px;
			right: 20px;
		}

		.site-search .dgwt-wcas-search-wrapp {
			max-width: 800px;
		}

		.site-search .dgwt-wcas-search-input {
			font-size: 20px;
			border: none;
			border-bottom: 1px solid #ccc;
		}

		@media (max-width: 1400px) {
			.site-search .dgwt-wcas-search-wrapp {
				max-width: 700px;
			}
		}

		@media (max-width: 1250px) {
			.site-search .dgwt-wcas-search-wrapp {
				max-width: 500px;
			}
		}

		@media (max-width: 1000px) {
			.site-search.off-canvas p.search-text {
				display: none;
			}

			.site-search .dgwt-wcas-search-wrapp {
				max-width: calc(100% - 30px);
				margin-left: 0;
			}
		}

		@media (max-width: 768px) {
			/*.site-search.off-canvas {*/
			/*	display: none;*/
			/*}*/
		}

	</style>
	<?php
} );

add_action( 'wp_footer', function () {
	?>
	<script>
		(function ($) {
			if ($(window).width() > 767) {
				$('.search-button').on('click', function () {
					var $input = $('.site-search .dgwt-wcas-search-input');

					if ($input.length) {
						setTimeout(function () {
							$input.focus();
						}, 500);
					}
				});
			} else {
				$('.search-button').on('click', function () {
					var $mobileHandler = $('.site-search .js-dgwt-wcas-enable-mobile-form');

					if ($mobileHandler.length) {
						$mobileHandler[0].click();

						setTimeout(function () {
							if ($('.site-search-close button').length) {
								$('.site-search-close button').click();
							}
						}, 500);
					}
				});
			}
		})(jQuery);
	</script>
	<?php
} );
