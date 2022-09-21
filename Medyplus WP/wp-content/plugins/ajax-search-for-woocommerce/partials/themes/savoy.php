<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_action( 'wp_head', function () {
	global $nm_theme_options;
	?>
	<style>
		.nm-shop-search-input-wrap .dgwt-wcas-search-wrapp {
			max-width: 100%;
		}

		.nm-menu-search .dgwt-wcas-search-wrapp.dgwt-wcas-layout-icon {
			padding: 16px 12px 16px 0;
			margin-left: 12px;
		}

		.nm-menu-search .dgwt-wcas-search-wrapp.dgwt-wcas-layout-icon .dgwt-wcas-ico-magnifier-handler {
			max-width: 16px;
		}

		<?php if (isset($nm_theme_options['header_navigation_highlight_color'])) { ?>
		.nm-menu-search .dgwt-wcas-search-wrapp.dgwt-wcas-layout-icon .dgwt-wcas-ico-magnifier-handler {
			fill: <?php echo esc_attr( $nm_theme_options['header_navigation_color'] ); ?>
		}

		<?php }
		if (isset($nm_theme_options['header_navigation_highlight_color'])) { ?>
		.nm-menu-search .dgwt-wcas-search-wrapp.dgwt-wcas-layout-icon .dgwt-wcas-ico-magnifier-handler:hover {
			fill: <?php echo esc_attr( $nm_theme_options['header_navigation_highlight_color'] ); ?>
		}

		<?php } ?>
	</style>
	<?php
} );
