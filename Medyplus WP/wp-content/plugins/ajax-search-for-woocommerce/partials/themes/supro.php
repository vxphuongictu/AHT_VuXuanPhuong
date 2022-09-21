<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

if ( ! function_exists( 'supro_extra_search' ) ) {
	function supro_extra_search() {
		$extras = supro_get_menu_extras();

		if ( empty( $extras ) ) {
			return;
		}

		if ( ! in_array( 'search', $extras ) ) {
			return;
		}

		$form = do_shortcode( '[wcas-search-form layout="icon" mobile_overlay="1"]' );

		echo sprintf(
			'<li class="extra-menu-item menu-item-search">%s</li>',
			$form
		);
	}
}

add_action( 'wp_footer', function () {
	?>
	<style>
		.header-layout-1 .site-header .menu-extra.menu-extra-au .menu-item-search {
			display: none;
		}
		.site-header .menu-extra.s-right .menu-item-search .dgwt-wcas-search-wrapp {
			margin-top: -3px;
		}
	</style>
	<?php
} );
