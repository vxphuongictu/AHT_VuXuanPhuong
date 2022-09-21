<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_action( 'init', function () {
	remove_action( 'shop_isle_header', 'shop_isle_primary_navigation', 50 );
} );

add_action( 'shop_isle_header', function () {
	if ( function_exists( 'shop_isle_primary_navigation' ) ) {
		ob_start();
		shop_isle_primary_navigation();
		$html = ob_get_clean();
		// https://regex101.com/r/AvkuEr/1/
		$re    = '/(.*<div class="header-search">)(.*<\/form>\s*<\/div>\s*)(<\/div>.*)/s';
		$subst = '$1' . do_shortcode( '[wcas-search-form layout="icon"]' ) . '$3';
		echo preg_replace( $re, $subst, $html );
	}
}, 60 );

add_action( 'wp_footer', function () {
	$menuItemsColor      = empty( get_theme_mod( 'shop_isle_menu_items_color' ) ) ? '#cbc7c2' : get_theme_mod( 'shop_isle_menu_items_color' );
	$menuItemsHoverColor = empty( get_theme_mod( 'shop_isle_menu_items_hover_color' ) ) ? '#ffffff' : get_theme_mod( 'shop_isle_menu_items_hover_color' );
	?>
	<style>
		.dgwt-wcas-ico-magnifier-handler {
			max-width: 16px;
			margin-top: 3px;
		}

		.dgwt-wcas-search-icon path {
			fill: <?php echo esc_attr($menuItemsColor); ?>;
			max-width: 16px;
			margin-top: 4px;
		}

		.dgwt-wcas-search-icon:hover path {
			fill: <?php echo esc_attr($menuItemsHoverColor); ?>;
		}

		.dgwt-wcas-is-mobile .dgwt-wcas-ico-magnifier-handler {
			max-width: 20px;
		}
	</style>
	<?php
} );
