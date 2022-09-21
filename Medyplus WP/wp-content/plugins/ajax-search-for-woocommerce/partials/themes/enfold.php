<?php

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

if ( ! function_exists( 'avia_append_search_nav' ) ) {
	add_filter( 'wp_nav_menu_items', 'avia_append_search_nav', 9997, 2 );
	add_filter( 'avf_fallback_menu_items', 'avia_append_search_nav', 9997, 2 );

	function avia_append_search_nav( $items, $args ) {
		if ( avia_get_option( 'header_searchicon', 'header_searchicon' ) != "header_searchicon" ) {
			return $items;
		}
		if ( avia_get_option( 'header_position', 'header_top' ) != "header_top" ) {
			return $items;
		}

		if ( ( is_object( $args ) && $args->theme_location == 'avia' ) || ( is_string( $args ) && $args = "fallback_menu" ) ) {
			ob_start();
			echo do_shortcode( '[wcas-search-form layout="icon"]' );
			$search = ob_get_clean();
			$items  .= '<li class="noMobile menu-item menu-item-search-dropdown menu-item-avia-special"><a class="dgwt-wcas-search-enfold-wrapper" href="#">' . $search . '</a></li>';
		}

		return $items;
	}
}

add_action( 'wp_footer', function () {
	?>
	<script>
		(function ($) {
			$(window).on('load', function () {
				$('.dgwt-wcas-search-enfold-wrapper').on('click', function () {
					return false;
				});
			});
		}(jQuery));
	</script>
	<script>
		(function ($) {
			function avia_apply_quant_btn() {
				jQuery(".quantity input[type=number]").each(function () {
					var number = $(this),
						max = parseFloat(number.attr('max')),
						min = parseFloat(number.attr('min')),
						step = parseInt(number.attr('step'), 10),
						newNum = jQuery(jQuery('<div />').append(number.clone(true)).html().replace('number', 'text')).insertAfter(number);
					number.remove();

					setTimeout(function () {
						if (newNum.next('.plus').length === 0) {
							var minus = jQuery('<input type="button" value="-" class="minus">').insertBefore(newNum),
								plus = jQuery('<input type="button" value="+" class="plus">').insertAfter(newNum);

							minus.on('click', function () {
								var the_val = parseInt(newNum.val(), 10) - step;
								the_val = the_val < 0 ? 0 : the_val;
								the_val = the_val < min ? min : the_val;
								newNum.val(the_val).trigger("change");
							});
							plus.on('click', function () {
								var the_val = parseInt(newNum.val(), 10) + step;
								the_val = the_val > max ? max : the_val;
								newNum.val(the_val).trigger("change");

							});
						}
					}, 10);

				});
			}

			$(document).ready(function () {

				$(document).on('dgwtWcasDetailsPanelLoaded', function () {
					avia_apply_quant_btn();
				});
			});

		}(jQuery));
	</script>
	<?php
} );

add_action( 'wp_head', function () {
	?>
	<style>
		#top .dgwt-wcas-no-submit .dgwt-wcas-sf-wrapp input[type="search"].dgwt-wcas-search-input {
			padding: 10px 15px 10px 40px;
			margin: 0;
		}

		#top.rtl .dgwt-wcas-no-submit .dgwt-wcas-sf-wrapp input[type="search"].dgwt-wcas-search-input {
			padding: 10px 40px 10px 15px
		}

		#top .av-main-nav .dgwt-wcas-no-submit .dgwt-wcas-sf-wrapp input[type="search"].dgwt-wcas-search-input {
			padding: 10px 15px 10px 15px;
			margin: 0;
		}

		#top.rtl .av-main-nav .dgwt-wcas-no-submit .dgwt-wcas-sf-wrapp input[type="search"].dgwt-wcas-search-input {
			padding: 10px 15px 10px 15px
		}

		.dgwt-wcas-search-enfold-wrapper {
			cursor: default;
		}

		.dgwt-wcas-search-wrapp {
			margin: 0;
			position: absolute;
			top: 48%;
			-ms-transform: translateY(-48%);
			transform: translateY(-48%);
		}

		.dgwt-wcas-overlay-mobile .dgwt-wcas-search-wrapp {
			position: relative;
			top: 0;
			-ms-transform: none;
			transform: none;
		}

		.dgwt-wcas-ico-magnifier-handler {
			max-width: 14px;
		}

		.dgwt-wcas-layout-icon-open .dgwt-wcas-search-icon-arrow {
			top: calc(100% + 5px);
		}

		html:not(.dgwt-wcas-overlay-mobile-on) .dgwt-wcas-search-wrapp.dgwt-wcas-layout-icon .dgwt-wcas-search-form {
			top: calc(100% + 11px);
		}

		@media (max-width: 767px) {
			.menu-item-search-dropdown {
				z-index: 100;
				padding-right: 25px;
			}

			.dgwt-wcas-ico-magnifier-handler {
				max-width: 20px;
			}
		}
	</style>
	<?php
} );
