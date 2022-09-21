<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

/**
 * Default search
 */
add_action( 'wp_footer', function () {
	echo '<div id="wcas-theme-search" style="display: block;"><div class="goya-search"><fieldset><div class="search-button-group">' . do_shortcode( '[wcas-search-form layout="classic"]' ) . '</div></fieldset></div></div>';
	echo '<div id="wcas-theme-search-mobile" style="display: block;"><div class="goya-search"><fieldset><div class="search-button-group">' . do_shortcode( '[wcas-search-form layout="classic"]' ) . '</div></fieldset></div></div>';
	?>
	<script>
		var wcasThemeSearch = document.querySelector('.search-panel .side-panel-content .goya-search');
		if (wcasThemeSearch !== null) {
			wcasThemeSearch.replaceWith(document.querySelector('#wcas-theme-search > div'));
		}
		document.querySelector('#wcas-theme-search').remove();

		var wcasThemeSearchMobile = document.querySelector('.side-mobile-menu .side-panel-content .goya-search');
		if (wcasThemeSearchMobile !== null) {
			wcasThemeSearchMobile.replaceWith(document.querySelector('#wcas-theme-search-mobile > div'));
		}
		document.querySelector('#wcas-theme-search-mobile').remove();

		(function ($) {
			$(window).on('load', function () {
				$('.site-header .quick_search').on('click', function (e) {
					setTimeout(function () {
						var $input = $('.search-panel .side-panel-content .dgwt-wcas-search-input');
						if ($input.length > 0 && $input.val().length === 0) {
							$input.focus();
						}
					}, 500);
				});
			});
		}(jQuery));
	</script>
	<style>
		.search-panel .search-button-group {
			border-bottom: none;
		}

		.search-panel .search-button-group .dgwt-wcas-search-wrapp {
			max-width: 100%;
		}
	</style>
	<?php
}, 12);
