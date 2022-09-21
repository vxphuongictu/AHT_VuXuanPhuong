<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

if ( defined( 'ASTRA_EXT_VER' ) ) {
	add_filter( 'dgwt/wcas/suggestion_details/show_quantity', '__return_false' );
}

function dgwt_wcas_astra_header_break_point() {
	$header_break_point = 921;
	if ( function_exists( 'astra_header_break_point' ) ) {
		$header_break_point = astra_header_break_point();
	}

	return $header_break_point;
}

function dgwt_wcas_astra_search_box() {
	$search_box = '';
	if ( function_exists( 'astra_get_option' ) ) {
		$search_box = astra_get_option( 'header-main-rt-section' );
	}

	return $search_box;
}

function dgwt_wcas_astra_search_box_style() {
	$search_box_style = '';
	if ( function_exists( 'astra_get_option' ) ) {
		$search_box_style = astra_get_option( 'header-main-rt-section-search-box-type' );
	}

	return $search_box_style;
}

// Change mobile breakpoint
add_filter( 'dgwt/wcas/scripts/mobile_breakpoint', function () {
	return dgwt_wcas_astra_header_break_point();
} );

// Overwrite search in Slide Search and Search Box mode
add_filter( 'astra_get_search_form', function ( $form ) {
	$header_break_point = dgwt_wcas_astra_header_break_point();
	$form               = '<div class="search-form"><span class="search-field"></span>';
	$form               .= do_shortcode( '[wcas-search-form layout="classic" mobile_overlay="1" mobile_breakpoint="' . $header_break_point . '"]' );
	$form               .= '</div>';

	return $form;
} );

add_action( 'wp_footer', function () {
	$header_break_point = dgwt_wcas_astra_header_break_point();
	$search_box         = dgwt_wcas_astra_search_box();
	$search_box_style   = dgwt_wcas_astra_search_box_style();

	// Header Cover Search
	if ( $search_box === 'search' && $search_box_style === 'header-cover' ) {
		echo '<div id="wcas-search-instance" style="display: block;"><div class="search-form"><div class="search-text-wrap"><input class="search-field" type="text" style="display:none;">' . do_shortcode( '[wcas-search-form layout="classic" mobile_overlay="1" mobile_breakpoint="' . $header_break_point . '" ]' ) . '</div><span id="close" class="close"></span></div></div>';
	}
	// Full Screen Search
	if ( $search_box === 'search' && $search_box_style === 'full-screen' ) {
		echo '<div id="wcas-search-instance" style="display: block;"><div class="search-form"><input class="search-field" type="text" style="display:none;">' . do_shortcode( '[wcas-search-form layout="classic" mobile_overlay="1" mobile_breakpoint="' . $header_break_point . '" ]' ) . '</div>';
	}
	?>
	<script>
		(function ($) {
			<?php if ( $search_box === 'search' && $search_box_style === 'header-cover' ) { ?>
			// Replace search form (Header Cover Search)
			$(window).on('load', function () {
				var wcasSearch = $('#wcas-search-instance > div');
				var themeSearch = $('.ast-search-box.header-cover .ast-container');
				if (themeSearch.eq(0)) {
					themeSearch.html(wcasSearch);
				}
				$('#wcas-search-instance').remove();
			});
			<?php } ?>

			<?php if ( $search_box === 'search' && $search_box_style === 'full-screen' ) { ?>
			// Replace search form (Full Screen Search)
			$(window).on('load', function () {
				var wcasSearch = $('#wcas-search-instance > div');
				var themeSearchFull = $('.ast-search-box.full-screen .ast-container');
				if (themeSearchFull.eq(0)) {
					themeSearchFull.find('.search-form').remove();
					themeSearchFull.append(wcasSearch)
				}
				$('#wcas-search-instance').remove();
			});
			<?php } ?>

			// Autofocus
			$('.astra-search-icon').on('click', function () {
				setTimeout(function () {
					// Slide Search, Search Box
					var $input = $('.search-custom-menu-item .dgwt-wcas-search-input');
					if ($input.length > 0) {
						$input.focus();
					}

					// Header Cover Search
					var $inputHeaderCover = $('.ast-search-box.header-cover .dgwt-wcas-search-input');
					if ($inputHeaderCover.length > 0) {
						$inputHeaderCover.focus();
					}

					// Full Screen Search
					var $inputFullScreen = $('.ast-search-box.full-screen .dgwt-wcas-search-input');
					if ($inputFullScreen.length > 0) {
						$inputFullScreen.focus();
					}
				}, 100);

				if ($(window).width() <= <?php echo $header_break_point ?>) {
					// Slide Search, Search Box
					var $mobile = $('.search-custom-menu-item .js-dgwt-wcas-enable-mobile-form');
					if ($mobile.length > 0) {
						$mobile.click();
					}

					// Header Cover Search / Full Screen Search
					var $mobile2 = $('.ast-search-box.header-cover .js-dgwt-wcas-enable-mobile-form, .ast-search-box.full-screen .js-dgwt-wcas-enable-mobile-form');
					if ($mobile2.length > 0) {
						$mobile2.click();
					}
				}
			});

			// Header Cover / Full Screen Search - close cover when in mobile mode
			$(document).on('click', '.js-dgwt-wcas-om-return', function (e) {
				$('.ast-search-box.header-cover #close, .ast-search-box.full-screen #close').click();
			});
		}(jQuery));
	</script>
	<?php
} );

add_filter( 'wp_head', function () {
	?>
	<style>
		/* Slide Search */
		.ast-dropdown-active .search-form {
			padding-left: 0 !important;
		}

		.ast-dropdown-active .ast-search-icon {
			visibility: hidden;
		}

		.ast-search-menu-icon .search-form {
			padding: 0;
		}

		.search-custom-menu-item .search-field {
			display: none;
		}

		.search-custom-menu-item .search-form {
			background-color: transparent !important;
			border: 0;
		}

		/* Search Box */
		.site-header .ast-inline-search.ast-search-menu-icon .search-form {
			padding-right: 0;
		}

		/* Full Screen Search */
		.ast-search-box.full-screen .ast-search-wrapper {
			top: 25%;
			transform: translate(-50%, -25%);
		}
	</style>
	<?php
} );
