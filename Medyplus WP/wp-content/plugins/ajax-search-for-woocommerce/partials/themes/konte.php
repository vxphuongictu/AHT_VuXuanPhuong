<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

// Change mobile breakpoint
add_filter( 'dgwt/wcas/scripts/mobile_breakpoint', function () {
	return 1200;
} );

add_action( 'wp_footer', function () {
	if ( ! function_exists( 'konte_get_option' ) ) {
		return;
	}
	// Search style: 'icon-modal', 'icon', 'form'
	$header_search_style = konte_get_option( 'header_search_style' );
	$layout              = $header_search_style === 'icon' ? 'icon' : 'classic';
	$class               = $header_search_style === 'icon' ? 'icon' : 'form';
	echo '<div id="wcas-search-instance" style="display: block;"><div class="header-search ' . $class . '">' . do_shortcode( '[wcas-search-form layout="' . $layout . '" mobile_overlay="1" mobile_breakpoint="1200" ]' ) . '</div></div>';
	echo '<div id="wcas-search-instance-mobile" style="display: block;">' . do_shortcode( '[wcas-search-form layout="classic" mobile_overlay="1" mobile_breakpoint="1200" ]' ) . '</div>';
	?>
	<script>
		(function ($) {
			<?php if ($header_search_style === 'form' || $header_search_style === 'icon') { ?>
			// Replace search form (icon or form)
			var themeSearch = document.querySelector('#masthead .header-search');
			if (themeSearch !== null) {
				themeSearch.replaceWith(document.querySelector('#wcas-search-instance > div'));
			}
			<?php } ?>

			<?php if ($header_search_style === 'icon-modal') { ?>
			var searchBtn = $('#masthead [data-target="search-modal"]');

			// Replace search form in modal
			$(window).on('load', function () {
				var themeSearch = $('#search-modal .instance-search');
				var wcasSearch = $('#wcas-search-instance > div');
				if (themeSearch.eq(0)) {
					themeSearch.html(wcasSearch);
				}
				$('#wcas-search-instance').remove();
			});

			// Autofocus
			if ($(window).width() >= 1200) {
				searchBtn.on('click', function () {
					setTimeout(function () {
						var $input = $('#search-modal .dgwt-wcas-search-input');
						if ($input.length > 0) {
							$input.focus();
						}
					}, 500)
				});
			}
			<?php } ?>

			// Mobile search in sidebar
			$(window).on('load', function () {
				var themeSearchMobile = $('.mobile-menu__search-form');
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
	if ( ! function_exists( 'konte_get_option' ) ) {
		return;
	}
	?>
	<style>
		.search-modal .modal-content {
			top: 25%;
		}

		.dark .header-search.icon .dgwt-wcas-ico-magnifier-handler path {
			fill: #ffffff;
		}

		.mobile-menu-panel .panel .mobile-menu__search-form {
			padding-top: 20px;
		}
	</style>
	<?php
} );
