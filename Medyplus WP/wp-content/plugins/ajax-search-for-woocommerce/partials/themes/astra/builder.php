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

function dgwt_wcas_astra_search_box_type() {
	$search_box_type = '';
	if ( function_exists( 'astra_get_option' ) ) {
		$search_box_type = astra_get_option( 'header-search-box-type' );
	}

	return $search_box_type;
}

// Change mobile breakpoint
add_filter( 'dgwt/wcas/scripts/mobile_breakpoint', function () {
	return dgwt_wcas_astra_header_break_point();
} );

function dgwt_wcas_astra_search_form() {
	$header_break_point = dgwt_wcas_astra_header_break_point();
	$form               = '<div class="search-form"><span class="search-field"></span>';
	$form               .= do_shortcode( '[wcas-search-form layout="classic" mobile_overlay="1" mobile_breakpoint="' . $header_break_point . '"]' );
	$form               .= '</div>';

	return $form;
}

// Overwrite search in Slide Search and Search Box mode
if ( ! function_exists( 'astra_get_search_form' ) ) {
	function astra_get_search_form( $echo = true ) {
		$form = dgwt_wcas_astra_search_form();

		if ( $echo ) {
			echo $form;
		} else {
			return $form;
		}
	}
}

add_filter( 'astra_get_search_form', function ( $form ) {
	return dgwt_wcas_astra_search_form();
} );

// Template for Header Cover
add_filter( 'astra_addon_get_template', function ( $located, $template_name, $args, $template_path, $default_path ) {
	if ( $template_name === 'advanced-search/template/header-cover.php' ) {
		$located = __DIR__ . '/template/header-cover.php';
	}

	return $located;
}, 100, 5 );


add_action( 'wp_footer', function () {
	$header_break_point = dgwt_wcas_astra_header_break_point();
	$search_box_type    = dgwt_wcas_astra_search_box_type();

	// Full Screen Search
	if ( $search_box_type === 'full-screen' ) {
		echo '<div id="wcas-search-instance" style="display: block;"><div class="search-form"><input class="search-field" type="text" style="display:none;">' . do_shortcode( '[fibosearch layout="classic" mobile_overlay="1" mobile_breakpoint="' . $header_break_point . '" ]' ) . '</div>';
	}
	?>
	<script>
		(function ($) {
			<?php if ( $search_box_type === 'full-screen' ) { ?>
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
					$input = $('.ast-search-menu-icon .dgwt-wcas-search-input');
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
					var $mobile = $('.ast-search-menu-icon .js-dgwt-wcas-enable-mobile-form');
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

		.ast-search-menu-icon .search-field {
			display: none;
		}

		.ast-search-menu-icon .search-form {
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

		/* Header Cover */
		.ast-search-box.header-cover .search-text-wrap {
			width: 50%;
			vertical-align: middle;
			margin-left: calc(25% - 10px);
		}

		.ast-search-box.header-cover .close {
			margin-top: -5px;
		}
	</style>
	<?php
} );
