<?php

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_filter( 'dgwt/wcas/form/magnifier_ico', function ( $html, $class ) {
	if ( $class === 'dgwt-wcas-ico-magnifier-handler' ) {
		$html = '<svg class="dgwt-wcas-ico-magnifier-handler" viewBox="0 0 20 20" id="search"><circle fill="none" stroke-width="2" stroke-miterlimit="10" cx="8.35" cy="8.35" r="6.5"></circle><path fill="none" stroke-width="2" stroke-miterlimit="10" d="M12.945 12.945l5.205 5.205"></path></svg>';
	}

	return $html;
}, 10, 2 );

add_action( 'wp_footer', function () {
	echo '<div id="wcas-sober-mobile-search" style="display: none;">' . do_shortcode( '[wcas-search-form]' ) . '</div>';
	echo '<div id="wcas-sober-search" style="display: block;">' . do_shortcode( '[wcas-search-form layout="icon"]' ) . '</div>';
	?>
	<script>
		var soberSearch = document.querySelector('.menu-item-search a');
		if (soberSearch !== null) {
			soberSearch.replaceWith(document.querySelector('#wcas-sober-search > div'));
		}
		(function ($) {
			$(window).on('load', function () {
				var soberSearchMobile = $('#mobile-menu .search-form');
				if (soberSearchMobile.eq(0)) {
					soberSearchMobile.replaceWith($('#wcas-sober-mobile-search > div'));
				}
			});
		}(jQuery));
	</script>
	<?php
} );

if ( ! function_exists( 'sober_search_modal' ) ) {
	function sober_search_modal() {
	}
}

add_action( 'wp_head', function () {
	?>
	<style>
		#mobile-menu .dgwt-wcas-search-wrapp {
			margin-bottom: 15px;
		}

		.menu-item-search .dgwt-wcas-search-wrapp.dgwt-wcas-layout-icon .dgwt-wcas-ico-magnifier-handler {
			max-width: 18px;
		}
	</style>
	<?php
} );
