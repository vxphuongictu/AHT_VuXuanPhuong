<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_filter( 'get_search_form', function ( $form ) {
	return do_shortcode( '[wcas-search-form]' );
}, 100 );

add_action( 'init', function () {
	remove_filter( 'wp_nav_menu_items', 'avada_add_search_to_main_nav', 20, 2 );

	// Add search to the main navigation.
	add_filter( 'wp_nav_menu_items', function ( $items, $args ) {
		// Disable woo cart on ubermenu navigations.
		$ubermenu = ( function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) && ubermenu_get_menu_instance_by_theme_location( $args->theme_location ) );

		if ( 'v6' !== Avada()->settings->get( 'header_layout' ) && false === $ubermenu ) {
			if ( 'main_navigation' === $args->theme_location || 'sticky_navigation' === $args->theme_location ) {
				if ( Avada()->settings->get( 'main_nav_search_icon' ) ) {
					$items .= '<li class="fusion-custom-menu-item fusion-main-menu-search">';
					$items .= do_shortcode( '[wcas-search-form layout="icon"]' );
					$items .= '</li>';
				}
			}
		}

		return $items;
	}, 20, 2 );
} );

// Fusion search
add_filter( 'search_form_after_fields', function ( $args ) {
	add_action( 'wp_footer', function () {
		echo '<div class="dgwt-wcas-avada-fus-search-replace-wrapper">';
		echo do_shortcode( '[wcas-search-form]' );
		echo '</div>';
	} );

	$args['after_fields'] = '<div class="dgwt-wcas-avada-fus-search-replace"></div>';

	return $args;
} );

add_filter( 'dgwt/wcas/icon', function ( $svg, $name, $class, $color ) {
	if ( $name === 'magnifier-thin' ) {
		ob_start();
		?>
		<svg version="1.1" class="<?php echo $class; ?>" xmlns="http://www.w3.org/2000/svg"
			 viewBox="0 0 30 32">
			<path
				d="M20.571 15.143q0-3.304-2.348-5.652t-5.652-2.348-5.652 2.348-2.348 5.652 2.348 5.652 5.652 2.348 5.652-2.348 2.348-5.652zM29.714 30q0 0.929-0.679 1.607t-1.607 0.679q-0.964 0-1.607-0.679l-6.125-6.107q-3.196 2.214-7.125 2.214-2.554 0-4.884-0.991t-4.018-2.679-2.679-4.018-0.991-4.884 0.991-4.884 2.679-4.018 4.018-2.679 4.884-0.991 4.884 0.991 4.018 2.679 2.679 4.018 0.991 4.884q0 3.929-2.214 7.125l6.125 6.125q0.661 0.661 0.661 1.607z"></path>
		</svg>
		<?php
		$svg = ob_get_clean();
	}

	return $svg;
}, 10, 4 );

add_action( 'wp_head', function () {
	?>
	<style>
		.fusion-secondary-menu-search {
			width: 500px;
		}

		.fusion-flyout-search .dgwt-wcas-search-wrapp {
			margin-top: 21px;
		}

		.dgwt-wcas-details-wrapp .quantity {
			width: auto;
		}

		.fusion-main-menu-search .dgwt-wcas-search-wrapp {
			margin-top: calc((var(--nav_height) / 2) - (var(--nav_typography-font-size) / 2));
		}

		.fusion-header-v7 .fusion-main-menu-search .dgwt-wcas-search-wrapp {
			margin-top: 0;
		}

		.dgwt-wcas-ico-magnifier, .dgwt-wcas-ico-magnifier-handler {
			max-width: none;
			fill: var(--nav_typography-color);
			max-height: var(--nav_typography-font-size);
		}

		.dgwt-wcas-ico-magnifier:hover, .dgwt-wcas-ico-magnifier-handler:hover {
			fill: var(--menu_hover_first_color);
		}

		.dgwt-wcas-sf-wrapp .dgwt-wcas-ico-magnifier:hover {
			fill: currentColor;
			opacity: 0.7;
		}

		.fusion-is-sticky .dgwt-wcas-ico-magnifier, .fusion-is-sticky .dgwt-wcas-ico-magnifier-handler {
			fill: var(--header_sticky_menu_color);
		}

		.fusion-is-sticky .dgwt-wcas-ico-magnifier:hover, .fusion-is-sticky .dgwt-wcas-ico-magnifier-handler:hover {
			fill: var(--menu_hover_first_color);
		}

		.fusion-header-v4 .fusion-main-menu {
			overflow: visible;
		}

		.fusion-search-form {
			display: none;
		}

		html:not(.dgwt-wcas-overlay-mobile-on) .fusion-header-v4 .fusion-main-menu .dgwt-wcas-search-wrapp.dgwt-wcas-layout-icon .dgwt-wcas-search-form {
			top: 100%;
		}

		.fusion-header-v4 .fusion-main-menu .dgwt-wcas-layout-icon-open .dgwt-wcas-search-icon-arrow {
			top: calc(100% + -4px);
		}

		@media (max-width: 1100px) {
			.fusion-flyout-search .dgwt-wcas-search-wrapp {
				margin-top: 73px;
				max-width: 100%;
				padding: 0 30px 0 30px;
			}

		}

		@media (max-width: 800px) {
			.fusion-logo .dgwt-wcas-search-wrapp {
				display: none;
			}
		}
	</style>
	<?php
} );

add_action( 'wp_footer', function () {
	?>
	<script>
		(function ($) {

			function dgwtWcasAvadaGetActiveInstance() {
				var $el = $('.dgwt-wcas-search-wrapp.dgwt-wcas-active'),
					instance;
				if ($el.length > 0) {
					$el.each(function () {
						var $input = $(this).find('.dgwt-wcas-search-input');
						if (typeof $input.data('autocomplete') == 'object') {
							instance = $input.data('autocomplete');
							return false;
						}
					});
				}

				return instance;
			}

			$(document).ready(function () {
				// Header 6
				if ($('.fusion-header-v6').length) {
					$('.fusion-header-v6 .fusion-icon-search').on('click', function () {
						var $input = $('.fusion-flyout-search .dgwt-wcas-search-input');
						if ($input.length > 0) {
							$input.focus();
						}
					});

					$('.fusion-header-v6 .fusion-icon-search').on('click', function () {
						var $input = $('.fusion-flyout-search .dgwt-wcas-search-input');
						if ($input.length > 0) {
							$input.focus();
						}
					});

					$('.fusion-icon-search').on('click', function () {

						if ($('.fusion-header-v6').hasClass('fusion-flyout-search-active')) {

							var instance = dgwtWcasAvadaGetActiveInstance();

							if (typeof instance == 'object') {
								instance.suggestions = [];
								instance.hide();
								instance.el.val('');
							}
						}
					});
				}

				// Fusion search
				var $fusionSearchForm = $('.fusion-search-form');
				if ($fusionSearchForm.length) {
					$(this).remove();
				}

				var $placeholders = $('.dgwt-wcas-avada-fus-search-replace')
				var $barsToReplace = $('.dgwt-wcas-avada-fus-search-replace-wrapper .dgwt-wcas-search-wrapp')
				if ($placeholders.length && $barsToReplace.length) {
					$placeholders.each(function (i) {
						var $parentForm = $(this).closest('form');
						$parentForm.after($(this));
						$parentForm.remove();
					});

					$placeholders.each(function (i) {
						$(this).append($($barsToReplace[i]));
					});
				}

				// Remove unused search forms
				$('.dgwt-wcas-avada-fus-search-replace-wrapper').remove();

				$(document).on('click', '.fusion-icon-search', function () {
					var $handler = $('.fusion-mobile-menu-search .js-dgwt-wcas-enable-mobile-form');
					var $handler2 = $('.fusion-flyout-search .js-dgwt-wcas-enable-mobile-form');

					if ($handler.length) {

						setTimeout(function () {
							$('.fusion-mobile-menu-search').hide();
						}, 100);

						$handler[0].click();
					}

					if ($handler2.length) {
						$handler2[0].click();
					}

				});

				$(document).on('click', '.js-dgwt-wcas-om-return', function () {
					var $activeFlyout = $('.fusion-flyout-active');
					if ($activeFlyout) {
						$activeFlyout.removeClass('fusion-flyout-search-active');
						$activeFlyout.removeClass('fusion-flyout-active');
					}
				});
			});
		}(jQuery));
	</script>
	<?php
}, 1000 );
