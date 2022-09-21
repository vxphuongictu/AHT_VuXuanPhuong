<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_action( 'init', function () {
	// Header Vertical
	remove_filter( 'wp_nav_menu_items', 'thegem_menu_item_search', 10, 2 );
	add_filter( 'wp_nav_menu_items', function ( $items, $args ) {
		if ( $args->theme_location == 'primary' && ! thegem_get_option( 'hide_search_icon' ) ) {
			$items .= '<li class="menu-item menu-item-search dgwt-wcas-thegem-menu-search">';
			$items .= do_shortcode( '[fibosearch layout="icon"]' );
			$items .= '</li>';
		}

		return $items;
	}, 10, 2 );

	// Header Fullwidth hamburger
	remove_filter( 'wp_nav_menu_items', 'thegem_menu_item_hamburger_widget', 100, 2 );
	add_action( 'thegem_before_nav_menu', function () {
		if ( in_array( thegem_get_option( 'header_layout' ), array(
			'perspective',
			'fullwidth_hamburger',
			'overlay'
		) ) ) {
			echo do_shortcode( '[fibosearch]' );
		}
	} );

	// Perspective header
	remove_filter( 'get_search_form', 'thegem_serch_form_vertical_header' );
	add_action( 'thegem_perspective_menu_buttons', function () {
		echo do_shortcode( '[fibosearch]' );
	} );

	// Remove the search bar from vertical header
	add_filter( 'get_search_form', function ( $form ) {
		if ( in_array( thegem_get_option( 'header_layout' ), array( 'fullwidth_hamburger', 'vertical' ) ) ) {
			$form = '';
		}

		return $form;
	}, 100 );

	add_action( 'thegem_before_header', function () {
		if ( ! in_array( thegem_get_option( 'header_layout' ), array( 'vertical' ) ) ) {
			return;
		}

		$html = '<div class="dgwt-wcas-thegem-vertical-search">';
		$html .= do_shortcode( '[fibosearch]' );
		$html .= '</div>';

		echo $html;
	}, 20 );

	// Force enable overlay for mobile search
	add_filter( 'dgwt/wcas/settings/load_value/key=enable_mobile_overlay', function () {
		return 'on';
	} );
} );

add_action( 'wp_head', function () {
	$color       = thegem_get_option( 'main_menu_level1_color' );
	$colorHover  = thegem_get_option( 'main_menu_level1_hover_color' );
	$mobileColor = thegem_get_option( 'mobile_menu_level1_color' );
	?>
	<style>
		.dgwt-wcas-thegem-menu-search .dgwt-wcas-search-wrapp {
			margin-left: 10px;
			margin-right: 10px;
		}

		.dgwt-wcas-thegem-menu-search .dgwt-wcas-ico-magnifier-handler {
			max-width: 19px;
		}

		.dgwt-wcas-thegem-menu-search .dgwt-wcas-search-icon path {
			fill: <?php echo $color; ?>;
		}

		.dgwt-wcas-thegem-menu-search .dgwt-wcas-search-icon:hover path {
			fill: <?php echo $colorHover; ?>;
		}

		#primary-navigation.responsive .dgwt-wcas-thegem-menu-search .dgwt-wcas-search-icon path {
			fill: <?php echo $mobileColor; ?>;
		}

		#primary-navigation.responsive .dgwt-wcas-thegem-menu-search {
			padding: 16px 20px;
		}

		.header-layout-perspective > .dgwt-wcas-search-wrapp {
			top: 30px;
			position: absolute;
			max-width: 600px;
			left: 270px;
			right: auto;
			margin: 0 auto;
			z-index: 10;
		}

		@media (max-width: 979px) {
			.header-layout-fullwidth_hamburger #primary-navigation > .dgwt-wcas-search-wrapp,
			.header-layout-perspective > .dgwt-wcas-search-wrapp {
				max-width: 350px;
			}

			.header-style-vertical #site-header-wrapper .dgwt-wcas-thegem-vertical-search {
				display: none;
			}
		}

		@media (max-width: 769px) {
			.header-layout-fullwidth_hamburger #primary-navigation > .dgwt-wcas-search-wrapp,
			.header-layout-overlay .primary-navigation.responsive .overlay-menu-cell > .dgwt-wcas-search-wrapp,
			.header-layout-perspective > .dgwt-wcas-search-wrapp {
				display: none !important;
			}
		}

		#page.vertical-header .dgwt-wcas-thegem-vertical-search {
			margin-right: auto;
			margin-left: auto;
			padding-left: 21px;
			padding-right: 21px;
		}

		.header-layout-fullwidth_hamburger #primary-navigation > .dgwt-wcas-search-wrapp {
			top: 30px;
			position: absolute;
			left: 150px;
			max-width: 600px;

			-webkit-transition: all .3s, transform 1s;
			transition: all .3s, transform 1s;
			-webkit-transform: translateX(0);
			transform: translateX(0);
		}

		@media (max-width: 979px) {
			.header-layout-fullwidth_hamburger #primary-navigation > .dgwt-wcas-search-wrapp {
				top: 12px;
			}
		}

		.header-layout-fullwidth_hamburger #primary-navigation.hamburger-active > .dgwt-wcas-search-wrapp {
			-moz-transform: translateX(-450px);
			-webkit-transform: translateX(-450px);
			transform: translateX(-450px);
		}

		.site-header.fixed .header-layout-fullwidth_hamburger #primary-navigation > .dgwt-wcas-search-wrapp,
		.site-header.fixed .header-layout-perspective > .dgwt-wcas-search-wrapp {
			top: 4px;
		}

		.site-header.fixed .header-layout-fullwidth_hamburger #primary-navigation > .dgwt-wcas-search-wrapp {
			left: 80px;
		}

		@media (max-width: 979px) {
			.header-layout-perspective > .dgwt-wcas-search-wrapp {
				top: 10px;
			}
		}

		@media (max-width: 1212px) and (min-width: 980px) {
			.header-layout-perspective #perspective-menu-buttons {
				padding-bottom: 30px;
			}

			.header-layout-perspective > .dgwt-wcas-search-wrapp {
				top: 110px;
			}
		}
	</style>
	<?php
} );
