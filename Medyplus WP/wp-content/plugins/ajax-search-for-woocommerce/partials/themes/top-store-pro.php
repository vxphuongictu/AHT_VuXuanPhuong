<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

/**
 * Default search
 */
add_action( 'wp_footer', function () {
	echo '<div id="wcas-theme-search" style="display: block;">' . do_shortcode( '[wcas-search-form]' ) . '</div>';
	?>
	<script>
		var wcasThemeSearch = document.querySelector('.main-header #search-box form');
		if (wcasThemeSearch !== null) {
			wcasThemeSearch.replaceWith(document.querySelector('#wcas-theme-search > div'));
		}
		document.querySelector('#wcas-theme-search').remove();
	</script>
	<style>
		.main-header .dgwt-wcas-search-wrapp {
			max-width: 800px;
		}

		.main-header .dgwt-wcas-search-form {
			width: 100% !important;
			margin: 0 !important;
		}
	</style>
	<?php
} );

/**
 * Search in sticky header
 */
add_action( 'wp_footer', function () {
	if ( get_theme_mod( 'top_store_pro_sticky_header', false ) === false && get_theme_mod( 'top_store_sticky_header', false ) === false ) {
		return;
	}
	echo '<div id="wcas-theme-search-sticky" style="display: block;">' . do_shortcode( '[wcas-search-form]' ) . '</div>';
	?>
	<script>
		var wcasThemeSearchSticky = document.querySelector('.search-wrapper #search-box form');
		if (wcasThemeSearchSticky !== null) {
			wcasThemeSearchSticky.replaceWith(document.querySelector('#wcas-theme-search-sticky > div'));
		}
		document.querySelector('#wcas-theme-search-sticky').remove();

		(function ($) {
			$(document).on('click', '.prd-search', function (e) {
				if ($(window).width() <= 990) {
					var $handler = $('.search-wrapper .js-dgwt-wcas-enable-mobile-form');
					if ($handler.length) {
						$handler[0].click();
					}

					setTimeout(function () {
						var $closeBtn = $('.search-wrapper .search-close-btn');
						if ($closeBtn.length) {
							$closeBtn[0].click();
						}
					}, 1100)
				} else {
					setTimeout(function () {
						var $input = $('.search-wrapper .dgwt-wcas-search-input');
						if ($input.length > 0) {
							$input.focus();
						}
					}, 500);
				}
			});
		}(jQuery));
	</script>
	<style>
		.search-wrapper .container {
			display: flex;
			justify-content: center;
			flex-direction: row-reverse;
			align-items: center;
		}

		.search-wrapper .search-close {
			margin: 0 0 0 30px;
		}

		.search-wrapper .dgwt-wcas-search-form {
			min-width: 500px;
		}
	</style>
	<?php
} );
