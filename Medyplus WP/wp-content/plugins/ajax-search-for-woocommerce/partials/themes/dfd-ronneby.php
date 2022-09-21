<?php

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

// Change mobile breakpoint
add_filter( 'dgwt/wcas/scripts/mobile_breakpoint', function () {
	return 500;
} );

add_action( 'wp_footer', function () {
	if ( ! class_exists( 'Dfd_Theme_Helpers' ) || ! method_exists( 'Dfd_Theme_Helpers', 'getHeaderStyleOption' ) ) {
		return;
	}
	$header_style_option = Dfd_Theme_Helpers::getHeaderStyleOption();
	if ( $header_style_option !== '10' ) {
		return;
	}
	echo '<div id="wcas-search-instance" style="display: block;"><div>' . do_shortcode( '[wcas-search-form layout="classic" mobile_overlay="1" mobile_breakpoint="500" ]' ) . '<i class="header-search-switcher close-search active"></i></div></div>';
	echo '<div id="wcas-search-instance-mobile" style="display: block;">' . do_shortcode( '[wcas-search-form layout="classic" mobile_overlay="1" mobile_breakpoint="500" ]' ) . '</div>';
	?>
	<script>
		(function ($) {
			// Autofocus
			if ($(window).width() >= 500) {
				// Wait until theme unbind clicks on this element
				setTimeout(function () {
					$('.header-search-switcher').on('click', function () {
						setTimeout(function () {
							var $input = $('.form-search-section .dgwt-wcas-search-input');
							if ($input.length > 0) {
								$input.focus();
							}
						}, 800)
					});
				}, 500);
			}

			// Replace search forms
			$(window).on('load', function () {
				var themeSearch = $('.form-search-section .row');
				var wcasSearch = $('#wcas-search-instance > div');
				if (themeSearch.eq(0)) {
					themeSearch.html(wcasSearch);
				}
				$('#wcas-search-instance').remove();

				var themeSearchMobile = $('.dfd-search-mobile-show');
				var wcasSearchMobile = $('#wcas-search-instance-mobile > div');
				if (themeSearchMobile.eq(0)) {
					themeSearchMobile.html(wcasSearchMobile);
				}
				$('#wcas-search-instance-mobile').remove();
			});
		}(jQuery));
	</script>
	<?php
} );

add_action( 'wp_head', function () {
	?>
	<style>
		/** Desktop search */
		.form-search-section .row {
			top: 25%;
		}

		.form-search-section .dgwt-wcas-search-wrapp {
			z-index: 1;
		}

		.form-search-section input[type="text"], .form-search-section input[type="search"] {
			font-style: normal;
			font-weight: normal;
			font-size: 14px;
			line-height: 100%;
		}

		.form-search-section .dgwt-wcas-search-wrapp ::-webkit-input-placeholder {
			font-style: normal;
			font-weight: normal;
			font-size: 14px;
			line-height: 100%;
		}

		.form-search-section .dgwt-wcas-search-wrapp ::-moz-placeholder {
			font-style: normal;
			font-weight: normal;
			font-size: 14px;
			line-height: 100%;
		}

		.form-search-section .dgwt-wcas-search-wrapp :-ms-input-placeholder {
			font-style: normal;
			font-weight: normal;
			font-size: 14px;
			line-height: 100%;
		}

		.form-search-section input:-moz-placeholder {
			font-style: normal;
			font-weight: normal;
			font-size: 14px;
			line-height: 100%;
		}

		/** Mobile search */
		.dfd-search-mobile-show {
			padding: 0 25px;
		}

		.dfd-search-mobile-show .dgwt-wcas-search-wrapp {
			min-width: 200px;
		}
	</style>
	<?php
} );

