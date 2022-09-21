<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_action( 'wp_footer', function () {
	echo '<div id="wcas-theme-search" style="display: block;">' . do_shortcode( '[wcas-search-form]' ) . '</div>';
} );

add_action( 'wp_footer', function () {
	?>
	<script>
		var wcasThemeSearch = document.querySelector('#search_popup .search_form-inner');
		if (wcasThemeSearch !== null) {
			wcasThemeSearch.replaceWith(document.querySelector('#wcas-theme-search > div'));
		}
		document.querySelector('#wcas-theme-search').remove();

		(function ($) {
			$(document).on('click', '.search-button', function (e) {
				setTimeout(function () {
					var $input = $('#search_popup .dgwt-wcas-search-input');
					if ($input.length > 0) {
						$input.focus();
					}
				}, 500);
			});
			$(document).on('click', '.mobile-search-trigger', function (e) {
				var $handler = $('#search_popup .js-dgwt-wcas-enable-mobile-form');
				if ($handler.length) {
					$handler[0].click();
				}

				setTimeout(function () {
					var $mobileSearchWrap = $('.mobile-search-wrap');
					if ($mobileSearchWrap.length) {
						$mobileSearchWrap.removeClass('active');
					}
				}, 500);
			});
		}(jQuery));
	</script>
	<style>
		.search_form-wrap {
			margin-top: -30%;
		}
	</style>
	<?php
}, 100 );
