<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_action( 'template_redirect', function () {
	remove_action( 'generate_menu_bar_items', 'generate_do_navigation_search_button' );

	add_action( 'generate_menu_bar_items', function () {
		echo '<div class="dgwt-wcas-menu-bar-item">';
		echo do_shortcode( '[fibosearch layout="icon"]' );
		echo '</div>';
	} );

	// If generate_is_using_flexbox() === false
	add_filter( 'generate_navigation_search_menu_item_output', function ( $html ) {
		return '<li class="dgwt-wcas-menu-search-item">' . do_shortcode( '[fibosearch layout="icon"]' ) . '</li>';
	} );

	// If generate_is_using_flexbox() === false
	add_action( 'generate_inside_mobile_menu_bar', function () {
		echo '<div class="dgwt-wcas-search-item">';
		echo do_shortcode( '[fibosearch layout="icon"]' );
		echo '</div>';
	} );
} );

add_action( 'wp_head', function () {
	$settings = false;
	if ( function_exists( 'generate_get_color_defaults' ) ) {
		$settings = wp_parse_args(
			get_option( 'generate_settings', array() ),
			generate_get_color_defaults()
		);
	}
	?>
	<style>
		.dgwt-wcas-menu-bar-item, .dgwt-wcas-menu-search-item, .dgwt-wcas-search-item {
			padding-left: 20px;
			padding-right: 20px;
		}

		.menu-bar-items .dgwt-wcas-ico-magnifier-handler,
		.dgwt-wcas-menu-search-item .dgwt-wcas-ico-magnifier-handler,
		.dgwt-wcas-search-item .dgwt-wcas-ico-magnifier-handler {
			max-width: 16px;
		}

		.mobile-bar-items .search-item {
			display: none;
		}

		#mobile-menu .dgwt-wcas-menu-search-item {
			display: none;
		}

		#masthead .dgwt-wcas-search-item, .dgwt-wcas-menu-search-item {
			padding-top: 20px;
		}

		<?php if ($settings) { ?>

		.dgwt-wcas-search-icon path {
			fill: <?php echo esc_attr($settings['navigation_text_color']); ?>;
		}

		.dgwt-wcas-search-icon:hover path {
			fill: <?php echo esc_attr($settings['navigation_text_hover_color']); ?>;
		}

		<?php } ?>
	</style>
	<?php
} );
