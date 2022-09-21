<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

// Change mobile breakpoint
add_filter( 'dgwt/wcas/scripts/mobile_breakpoint', function () {
	return 1024;
} );

add_action( 'wp_footer', function () {
	echo '<div id="wcas-search-instance" style="display: block;">' . do_shortcode( '[wcas-search-form layout="classic" mobile_overlay="1" mobile_breakpoint="1024" ]' ) . '</div>';
	echo '<div id="wcas-search-icon-instance" style="display: block;">' . do_shortcode( '[wcas-search-form layout="classic" mobile_overlay="1" mobile_breakpoint="1024" ]' ) . '</div>';
	?>
	<script>
		var themeSearch = document.querySelector('#main_header .head_search');
		if (themeSearch !== null) {
			themeSearch.innerHTML = '';
			themeSearch.append(document.querySelector('#wcas-search-instance > div'))
		}
		document.querySelector('#wcas-search-instance').remove();

		var themeSearchPopup = document.querySelector('.search-header-contents');
		if (themeSearchPopup !== null) {
			themeSearchPopup.innerHTML = '';
			themeSearchPopup.append(document.querySelector('#wcas-search-icon-instance > div'));
		}
		document.querySelector('#wcas-search-icon-instance').remove();

		(function ($) {
			$(window).on('load', function () {
				$('.icon-search-onclick').on('click', function () {
					// Autofocus
					if ($(window).width() >= 1024) {
						setTimeout(function () {
							var $input = $('.search-header-contents .dgwt-wcas-search-input');
							if ($input.length > 0) {
								$input.focus();
							}
						}, 500);
					} else {
						var $mobile = $('.search-header-contents .js-dgwt-wcas-enable-mobile-form');
						if ($mobile.length > 0) {
							$mobile.click();

							// Support for new version of search popup (version >= v16.2)
							if (document.querySelector('#rhSplashSearch') !== null) {
								setTimeout(function () {
									document.getElementById('rhSplashSearch').classList.remove('top-search-onclick-open');
									document.querySelector('.rh-outer-wrap').classList.remove('rh-outer-wrap-move');
								}, 100);
							}
						}
					}
				});
			});
		}(jQuery));
	</script>
	<style>
		/* Fix for iPhone sticky header */
		@media (max-width: 1024px) {
			.sticky-wrapper.re-stickyheader .rh-stickme {
				width: 100% !important;
			}
		}
	</style>
	<?php
}, 15 );
