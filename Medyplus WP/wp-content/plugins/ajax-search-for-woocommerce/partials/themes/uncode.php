<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_filter( 'dgwt/wcas/scripts/mobile_breakpoint', function () {
	return 960;
} );

add_action( 'wp_footer', function () {
	echo '<div id="wcas-desktop-search" style="display: none;">' . do_shortcode( '[wcas-search-form layout="icon"]' ) . '</div>';
	echo '<div id="wcas-mobile-search" style="display: none;">' . do_shortcode( '[wcas-search-form layout="icon"]' ) . '</div>';
	?>
	<script>
		var desktopSearch = document.querySelector('.menu-wrapper a.search-icon');
		if (desktopSearch !== null) {
			desktopSearch.replaceWith(document.querySelector('#wcas-desktop-search > div'));
		}
		document.querySelector('#wcas-desktop-search').remove()

		var mobileSearch = document.querySelector('.menu-wrapper a.mobile-search-icon');
		if (mobileSearch !== null) {
			mobileSearch.replaceWith(document.querySelector('#wcas-mobile-search > div'));
		}
		document.querySelector('#wcas-mobile-search').remove();
	</script>

	<style>
		.menu-icons .dgwt-wcas-search-wrapp {
			margin-right: 9px;
		}

		.dgwt-wcas-ico-magnifier-handler {
			max-width: 18px;
		}

		.dgwt-wcas-sf-wrapp input[type=search].dgwt-wcas-search-input {
			border-color: #ddd !important;
		}

		.menu-dark .dgwt-wcas-search-icon path {
			fill: #ffffff;
		}

		.menu-dark .dgwt-wcas-search-icon:hover path {
			fill: rgba(255, 255, 255, 0.5);
		}

		@media (max-width: 959px) {
			.menu-icons .dgwt-wcas-search-wrapp {
				padding: 9px 36px 9px 36px;
				max-width: none;
			}
		}
	</style>
	<?php
} );
